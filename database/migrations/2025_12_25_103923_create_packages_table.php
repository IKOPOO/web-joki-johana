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
         Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('studio_id')
                  ->constrained('studios')
                  ->cascadeOnDelete();

            $table->string('name', 100);
            $table->text('description');
            $table->integer('duration_minutes');
            $table->integer('price');

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
