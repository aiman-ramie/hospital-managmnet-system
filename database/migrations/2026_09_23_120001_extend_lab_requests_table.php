<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lab_requests', function (Blueprint $table) {
            $table->string('request_number')->unique()->after('id');
            $table->foreignId('lab_test_id')->nullable()->after('requested_by')->constrained('lab_test_catalogue')->nullOnDelete();
            $table->foreignId('assigned_staff_id')->nullable()->after('lab_test_id')->constrained('staff')->nullOnDelete();
            $table->enum('priority', ['Routine', 'Urgent', 'Stat'])->default('Routine')->after('test_name');
            $table->timestamp('requested_at')->nullable()->after('priority');
            $table->timestamp('collected_at')->nullable()->after('requested_at');
            $table->timestamp('reported_at')->nullable()->after('collected_at');
            $table->text('clinical_notes')->nullable()->after('reported_at');
        });
    }

    public function down(): void
    {
        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropForeign(['lab_test_id']);
            $table->dropForeign(['assigned_staff_id']);
            $table->dropColumn([
                'request_number', 'lab_test_id', 'assigned_staff_id', 'priority',
                'requested_at', 'collected_at', 'reported_at', 'clinical_notes',
            ]);
        });
    }
};