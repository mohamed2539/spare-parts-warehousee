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
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // id auto-increment primary key
            
            // تأكد من أن النوع نفسه في كلا الجدولين
            $table->unsignedBigInteger('department_id')->nullable(); // يجب أن يتطابق مع نوع العمود في جدول departments
            
            // تعريف العلاقة (foreign key)
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
    
};
