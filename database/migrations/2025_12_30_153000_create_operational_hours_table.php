<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('operational_hours', function (Blueprint $table) {
      $table->id();
      $table->foreignId('studio_id')->constrained('studios')->cascadeOnDelete();
      $table->integer('day_of_week'); // 0 (Sunday) to 6 (Saturday) or 1-7
      $table->time('opening_time')->nullable();
      $table->time('closing_time')->nullable();
      $table->boolean('is_closed')->default(false);
      $table->timestamps();

      $table->unique(['studio_id', 'day_of_week']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('operational_hours');
  }
};
