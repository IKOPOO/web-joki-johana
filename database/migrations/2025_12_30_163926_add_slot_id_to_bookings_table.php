<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('tbl_booking', function (Blueprint $table) {
      $table->foreignId('slot_id')->nullable()->after('package_id')
        ->constrained('session_slots')
        ->nullOnDelete();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('tbl_booking', function (Blueprint $table) {
      $table->dropForeign(['slot_id']);
      $table->dropColumn('slot_id');
    });
  }
};
