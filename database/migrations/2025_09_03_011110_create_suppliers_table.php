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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('supplier_name', 180);
            $table->string('contact_no', 60)->nullable();
            $table->string('address', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unique('supplier_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
