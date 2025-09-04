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
        Schema::create('property_ack_receipts', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('par_number', 80);
            $table->date('par_date');
            $table->unsignedInteger('person_responsible_id');
            $table->unsignedInteger('office_id');
            $table->unsignedInteger('new_accountable_id');
            $table->unsignedInteger('issued_by_id');
            $table->unsignedInteger('previous_par_id');
            $table->unsignedInteger('fund_source_id');
            $table->unsignedInteger('location_id');
            $table->string('remark', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by');

            $table->unique('par_number');

            $table->foreign('person_responsible_id')->references('id')->on('accountable_persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('office_id')->references('id')->on('offices')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('new_accountable_id')->references('id')->on('accountable_persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('issued_by_id')->references('id')->on('accountable_persons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('previous_par_id')->references('id')->on('property_ack_receipts')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_ack_receipts');
    }
};
