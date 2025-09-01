<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('incoming_items', function (Blueprint $table) {
            $table->integer('min_quantity')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('incoming_items', function (Blueprint $table) {
            $table->dropColumn('min_quantity');
        });
    }
    
};
