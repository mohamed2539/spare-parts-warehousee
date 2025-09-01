<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('code')->nullable();
            $table->string('item')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('requested_qty', 14, 4)->default(0);
            $table->string('supplier')->nullable();
            $table->string('request_department')->nullable();
            $table->string('requester_name')->nullable();
            $table->enum('status', ['pending','ordered','received','cancelled'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('purchase_requests');
    }
};
