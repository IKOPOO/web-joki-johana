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
         Schema::create('tbl_booking', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignId('studio_id')
                  ->constrained('studios')
                  ->cascadeOnDelete();

            $table->foreignId('package_id')
                  ->constrained('packages')
                  ->cascadeOnDelete();

            $table->dateTime('booking_datetime');
            $table->text('note')->nullable();

            $table->enum('status', [
                'PENDING',
                'CONFIRMED',
                'DONE',
                'CANCELLED'
            ])->default('PENDING');

            $table->integer('total_price')->nullable();

            $table->enum('payment_status', ['PAID', 'UNPAID'])
                  ->default('UNPAID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_booking');
    }
};
