<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('inbound_rows', function (Blueprint $table) {
          $table->id();
          $table->foreignId('inbound_batch_id')->constrained('inbound_batches')->cascadeOnDelete();
          $table->string('department')->nullable();     // القسم
          $table->date('doc_date')->nullable();         // التاريخ
          $table->string('code')->nullable();           // الكود
          $table->string('item_name')->nullable();      // الصنف
          $table->string('unit')->nullable();           // الوحدة (عدد/كيلو/متر/رزمة...)
          $table->decimal('quantity', 18, 3)->nullable(); // الكمية
          $table->string('supplier')->nullable();       // المورد
          $table->json('errors')->nullable();           // أخطاء السطر إن وجدت
          $table->timestamps();
        });
      }
      public function down(): void {
        Schema::dropIfExists('inbound_rows');
      }
};
