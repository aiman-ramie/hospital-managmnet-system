<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_request_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('prepared_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('verified_by')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('result_value')->nullable();
            $table->string('reference_range')->nullable();
            $table->text('findings')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['Draft', 'Verified', 'Released'])->default('Draft');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};