<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('payment_terms')->nullable();
            $table->enum('status', ['Active', 'On Hold'])->default('Active');
            $table->timestamps();
        });

        Schema::create('pharmacy_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_number')->unique();
            $table->foreignId('supplier_id')->constrained('pharmacy_suppliers')->restrictOnDelete();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['Draft', 'Ordered', 'Received'])->default('Received');
            $table->date('purchase_date');
            $table->timestamps();
        });

        Schema::create('pharmacy_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained('pharmacy_purchases')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });

        Schema::create('pharmacy_sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_number')->unique();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('payment_method', ['Cash', 'Card', 'Insurance', 'Credit'])->default('Cash');
            $table->enum('status', ['Completed', 'Pending', 'Cancelled'])->default('Completed');
            $table->timestamps();
        });

        Schema::create('pharmacy_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('pharmacy_sales')->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->timestamps();
        });

        Schema::create('pharmacy_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->foreignId('sale_id')->nullable()->constrained('pharmacy_sales')->nullOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->string('reason');
            $table->string('processed_by');
            $table->timestamps();
        });

        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->string('dosage')->nullable();
            $table->string('duration')->nullable();
            $table->timestamps();
        });

        Schema::create('pharmacy_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->string('responsibility');
            $table->string('counter')->nullable();
            $table->enum('status', ['Active', 'On Leave', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_staff_assignments');
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('pharmacy_returns');
        Schema::dropIfExists('pharmacy_sale_items');
        Schema::dropIfExists('pharmacy_sales');
        Schema::dropIfExists('pharmacy_purchase_items');
        Schema::dropIfExists('pharmacy_purchases');
        Schema::dropIfExists('pharmacy_suppliers');
    }
};