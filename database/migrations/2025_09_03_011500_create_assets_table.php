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
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('property_no', 80);
            $table->string('description', 255);
            $table->string('unit', 40)->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->date('date_acquired')->nullable();
            $table->unsignedInteger('fund_source_id')->nullable();
            $table->unsignedInteger('pr_id')->nullable();
            $table->unsignedInteger('po_id')->nullable();
            $table->unsignedInteger('invoice_id')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('sticker_code', 80)->nullable();
            $table->smallInteger('warranty_months')->nullable();
            $table->string('remark', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by')->nullable();

            $table->unique('property_no');
            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('pr_id')->references('id')->on('purchase_requests')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('po_id')->references('id')->on('purchase_orders')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
