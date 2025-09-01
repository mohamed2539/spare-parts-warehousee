<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('manual_withdrawals') && !Schema::hasColumn('manual_withdrawals', 'created_by')) {
            Schema::table('manual_withdrawals', function (Blueprint $table) {
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            });
        }
    }
    public function down(): void {
        if (Schema::hasTable('manual_withdrawals') && Schema::hasColumn('manual_withdrawals', 'created_by')) {
            Schema::table('manual_withdrawals', function (Blueprint $table) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            });
        }
    }
};
