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
            $table->bigIncrements('id');                             // bigint unsigned PK
            $table->unsignedInteger('par_id');                       // NOT NULL in dump
            $table->unsignedInteger('asset_id');
            $table->string('property_no_raw', 80)->nullable();
            $table->string('item_name', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('quantity', 12, 3)->default(1.000);
            $table->string('unit', 40)->nullable();
            $table->decimal('unit_cost', 12, 2)->nullable();
            // generated column: IF(unit_cost IS NULL, NULL, quantity * unit_cost)
            $table->decimal('total_amount', 14, 2)->storedAs('IF(unit_cost IS NULL, NULL, quantity * unit_cost)');
            $table->string('sticker_code', 80)->nullable();
            $table->smallInteger('warranty_months')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->foreign('par_id')->references('id')->on('property_ack_receipts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('asset_id')->references('id')->on('assets')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
