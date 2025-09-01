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
        Schema::create('incoming_items', function (Blueprint $table) {
            $table->id(); // id auto-increment primary key
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
        
            $table->string('code');
            $table->string('item');
            $table->string('unit');
            $table->decimal('quantity', 10, 2);
            $table->string('supplier')->nullable();
            $table->timestamp('date')->nullable();  // إضافة حقل التاريخ
            $table->unsignedBigInteger('created_by')->nullable();  // For the user who created the item
            $table->unsignedBigInteger('updated_by')->nullable();  // For the user who updated the item
            $table->timestamps();
            
            // Foreign key constraint for created_by and updated_by
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_items');
    }
};
