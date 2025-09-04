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
            $table->unsignedInteger('fund_source_id');
            $table->unsignedInteger('pr_id');
            $table->unsignedInteger('po_id');
            $table->unsignedInteger('invoice_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('location_id');
            $table->string('sticker_code', 80)->nullable();
            $table->smallInteger('warranty_months')->nullable();
            $table->string('remark', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by');

            $table->unique('property_no');
            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('pr_id')->references('id')->on('purchase_requests')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('po_id')->references('id')->on('purchase_orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('invoice_id')->references('id')->on('invoices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
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
