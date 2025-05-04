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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('service_name')->nullable();
            $table->string('service_option')->nullable();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('date')->nullable();
            $table->string('time')->nullable();
            $table->string('full_pickup_truck_load')->nullable();
            $table->string('half_pickup_truck_load')->nullable();
            $table->string('price')->nullable();
            $table->string('units')->nullable();
            $table->string('estimated_price')->nullable();
            $table->string('dumpster_size')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->text('detail')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
