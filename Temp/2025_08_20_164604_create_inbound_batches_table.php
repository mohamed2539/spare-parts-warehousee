<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('inbound_batches', function (Blueprint $table) {
          $table->id();
          $table->string('original_filename');
          $table->string('file_path'); // storage/app/...
          $table->enum('status', ['pending','running','done','failed'])->default('pending');
          $table->unsignedInteger('total_rows')->nullable();
          $table->unsignedInteger('processed_rows')->default(0);
          $table->unsignedInteger('failed_rows')->default(0);
          $table->string('error_file_path')->nullable(); // CSV للأخطاء إن وجدت
          $table->timestamp('started_at')->nullable();
          $table->timestamp('finished_at')->nullable();
          $table->timestamps();
        });
      }
      public function down(): void {
        Schema::dropIfExists('inbound_batches');
      }
};
