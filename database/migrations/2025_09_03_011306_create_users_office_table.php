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
        Schema::create('users_office', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('office_id');
            $table->tinyInteger('status')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('office_id')->references('id')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_office');
    }
};
