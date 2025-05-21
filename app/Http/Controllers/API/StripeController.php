<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\StripePayments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class StripeController extends BaseController
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('app.stripe_key'));
    }

    public function paymentKey()
    {
        try {
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);
            //             Create a PaymentIntent with amount and currency
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $this->calculateOrderAmount($jsonObj->items),
                'currency' => 'USD',
                'description' => 'Create Subscription Plan',
                'setup_future_usage' => 'on_session',
            ]);
            $output = [
                'payment_intent_id' => $paymentIntent->client_secret
            ];
            return $this->sendResponse($output, 'ID Created SuccessFully', 200);
        } catch (\Exception $ex) {
            return $this->sendException([$ex->getMessage()]);
        }
    }

    /** Calculate order total for stripe */
    public function calculateOrderAmount(array $items): int
    {
        foreach ($items as $item) {
            return $item->price * 100;
        }
    }

    public function processStripePayment(Request $request)
    {
        try {
            DB::beginTransaction();

            // Create or retrieve customer
            $existingCustomers = \Stripe\Customer::all(['email' => $request->email]);
            $customer = count($existingCustomers->data) > 0
                ? $existingCustomers->data[0]
                : \Stripe\Customer::create(['email' => $request->email]);

            // Attach payment method
            $paymentMethod = \Stripe\PaymentMethod::retrieve($request->payment_data['payment_method']);
            $paymentMethod->attach(['customer' => $customer->id]);

            \Stripe\Customer::update($customer->id, [
                'invoice_settings' => ['default_payment_method' => $paymentMethod->id],
            ]);

            // Create product if not exists
            $existingProducts = \Stripe\Product::all();
            $product = collect($existingProducts->data)
                ->firstWhere('name', $request->product_name);

            if (!$product) {
                $product = \Stripe\Product::create([
                    'name' => $request->product_name,
                ]);
            }

            // Create one-time price
            $price = \Stripe\Price::create([
                'currency' => 'USD',
                'unit_amount' => ($request->price * 100),
                'product' => $product->id,
            ]);

            // Create one-time PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->price * 100,
                'currency' => 'USD',
                'customer' => $customer->id,
                'payment_method' => $paymentMethod->id,
                'off_session' => true,
                'confirm' => true,
                'description' => 'One-time payment for ' . $product->name,
            ]);


            $stripeSubscription = StripePayments::create([
                'user_id' => auth()->user()->id ?? null,
                'product_name' => $request->product_name,
                'customer_email' => $request->email,
                // 'coupon_id' => $nautaesPlan->coupon->id ?? null,
                'currency' => 'USD',
                'last_4_digit' => $paymentMethod->card->last4,
                'card_exp_month' => $paymentMethod->card->exp_month,
                'card_exp_year' => $paymentMethod->card->exp_year,
                'price' => $request->price,
                'stripe_payment_id' => $paymentIntent->id,
                 'stripe_customer_id' => $customer->id,
                'stripe_response' => json_encode($request->payment_data),
            ]);

            DB::commit();
            return $this->sendResponse($paymentIntent, 'Payment successful', 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendException([$th->getMessage()]);
        }
    }
}
