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
        Schema::create('hotel_bookings', function (Blueprint $table) 
        {
            $table->id();
            $table->string('code');
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hotel_room_id')->constrained()->cascadeOnDelete();
            $table->string('name');   // nama pemesan
            $table->string('email');
            $table->string('phone');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('number_of_guests');
            $table->integer('number_of_nights');
            $table->foreignId('promo_code_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->integer('subtotal')->nullable();
            $table->integer('grandtotal')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_bookings');
    }
};
