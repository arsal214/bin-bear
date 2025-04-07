<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $user = User::where('email', $request->email)->first();

            Password::deleteToken($user);
            $token = Password::createToken($user);

            $link = route('password.reset', [$token, 'email' => $request->email]);
            // $template = adminSettings('forgot_password_email_template');

            $arr = array('{$link}' => $link);

//            $this->sendmail($request->email, 'ForgotPasswordEmailTemplate', $arr, 'ForgotPasswordEmailTemplate');
            // $data = strtr($template, $arr);
            // Mail::to($request->email)->send(new DynamicEmail($data));
            return back()->with('success', 'Password Reset Link Sent Successfully.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->withErrors(['msg' => $th->getMessage()]);
        }
    }
}
