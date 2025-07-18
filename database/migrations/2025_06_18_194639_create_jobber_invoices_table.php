<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobber_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jobber_invoice_id')->unique();
            $table->string('jobber_job_id');
            $table->string('invoice_number')->nullable();
            $table->integer('total')->default(0); // Store in cents
            $table->string('status')->default('draft');
            $table->text('payment_url')->nullable();
            $table->text('public_url')->nullable();
            $table->integer('payment_amount')->nullable(); // Store in cents
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable(); // Store additional data
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('jobber_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobber_invoices');
    }
};
