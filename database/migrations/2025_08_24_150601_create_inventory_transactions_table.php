<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
        
            // ربط بالجدول الحالي incoming_items بدل inventory_items
            $table->foreignId('incoming_item_id')
                  ->constrained('incoming_items')
                  ->onDelete('cascade');
        
            $table->enum('type', ['in', 'out']); // in = إضافة, out = خصم
            $table->integer('quantity');
            $table->text('note')->nullable();
        
            // ربط بالمستخدمين
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
