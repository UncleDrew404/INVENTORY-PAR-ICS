<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ---------- LOOKUPS ----------
        Schema::create('offices', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('office_name', 120);
            $table->tinyInteger('status')->default(0);
            $table->unique('office_name');
        });

        Schema::create('fund_sources', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('code', 60);
            $table->string('description', 180)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unique('code');
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('supplier_name', 180);
            $table->string('contact_no', 60)->nullable();
            $table->string('address', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unique('supplier_name');
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('location_name', 150);
            $table->tinyInteger('status')->default(0);
            $table->unique('location_name');
        });

        // ---------- USERS ----------
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });

        Schema::create('users_profile', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->unsignedInteger('user_id');
            $table->string('full_name', 100);
            $table->string('contact_number', 100);
            $table->tinyInteger('status')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('users_office', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('office_id');
            $table->tinyInteger('status')->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('office_id')->references('id')->on('offices');
        });

        // ---------- CORE ----------
        Schema::create('accountable_persons', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->unsignedInteger('office_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('user_id')->nullable();          // references users_office.user_id in dump; keep FK to users_office.user_id equivalent

            $table->foreign('office_id')->references('id')->on('offices')->nullOnDelete()->cascadeOnUpdate();
            // In the dump: FK to users_office(user_id). In normalized Laravel schema it's safer to point to users.id;
            // if you truly need users_office.user_id, create a composite/unique on users_office.user_id first.
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        // ---------- TRANSACTIONS ----------
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('pr_number', 60);
            $table->date('pr_date')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by')->nullable();

            $table->unique('pr_number');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('po_number', 60);
            $table->date('po_date')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by')->nullable();

            $table->unique('po_number');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('invoice_number', 60);
            $table->date('invoice_date')->nullable();
            $table->unsignedInteger('supplier_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by')->nullable();

            $table->unique('invoice_number');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        // ---------- ASSETS ----------
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

        // ---------- PAR (Property Acknowledgment Receipts) ----------
        Schema::create('property_ack_receipts', function (Blueprint $table) {
            $table->increments('id');                                // int unsigned PK
            $table->string('par_number', 80);
            $table->date('par_date');
            $table->unsignedInteger('person_responsible_id')->nullable();
            $table->unsignedInteger('office_id')->nullable();
            $table->unsignedInteger('new_accountable_id')->nullable();
            $table->unsignedInteger('issued_by_id')->nullable();
            $table->unsignedInteger('previous_par_id')->nullable();
            $table->unsignedInteger('fund_source_id')->nullable();
            $table->unsignedInteger('location_id')->nullable();
            $table->string('remark', 255)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->unsignedInteger('created_by')->nullable();

            $table->unique('par_number');

            $table->foreign('person_responsible_id')->references('id')->on('accountable_persons')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('office_id')->references('id')->on('offices')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('new_accountable_id')->references('id')->on('accountable_persons')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('issued_by_id')->references('id')->on('accountable_persons')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('previous_par_id')->references('id')->on('property_ack_receipts')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('fund_source_id')->references('id')->on('fund_sources')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
        });

        // ---------- ITEMS (PAR line items) ----------
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');                             // bigint unsigned PK
            $table->unsignedInteger('par_id');                       // NOT NULL in dump
            $table->unsignedInteger('asset_id')->nullable();
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
            $table->foreign('asset_id')->references('id')->on('assets')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('property_ack_receipts');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_requests');
        Schema::dropIfExists('accountable_persons');
        Schema::dropIfExists('users_office');
        Schema::dropIfExists('users_profile');
        Schema::dropIfExists('users');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('fund_sources');
        Schema::dropIfExists('offices');
    }
};
