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
        Schema::create('accountable_persons', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->unsignedInteger('office_id');
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('user_id');          // references users_office.user_id in dump; keep FK to users_office.user_id equivalent

            $table->foreign('office_id')->references('id')->on('offices')->cascadeOnDelete()->cascadeOnUpdate();
            // In the dump: FK to users_office(user_id). In normalized Laravel schema it's safer to point to users.id;
            // if you truly need users_office.user_id, create a composite/unique on users_office.user_id first.
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountable_persons');
    }
};
