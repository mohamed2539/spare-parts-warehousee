<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('manual_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('code')->nullable();
            $table->string('item')->nullable();
            $table->string('unit')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('voucher')->nullable(); // سند الصرف
            $table->string('reason')->nullable();  // سبب الصرف
            $table->string('receiver')->nullable(); // المستلم
            $table->string('request_department')->nullable(); // القسم الطالب
            $table->text('notes')->nullable();  // ملاحظات
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manual_withdrawals');
    }
};
