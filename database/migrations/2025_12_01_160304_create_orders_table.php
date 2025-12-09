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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Customer info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->text('customer_address');

            // Appointment
            $table->date('date');
            $table->string('time_slot'); // ex: "08:00"

            // Distance (optional)
            $table->decimal('distance_km', 6, 2)->nullable();
            $table->integer('transport_fee')->default(0);

            // Pricing
            $table->integer('subtotal')->default(0);
            $table->integer('total')->default(0);

            $table->string('status')->default('pending');

            $table->foreignId('groomer_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
