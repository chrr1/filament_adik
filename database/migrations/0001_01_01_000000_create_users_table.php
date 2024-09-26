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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name', 250)->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('company_id')->nullable();
            $table->string('user_database', 50)->nullable();
            $table->integer('user_status')->default(0);
            $table->integer('user_group_id')->nullable();
            $table->integer('user_type_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->integer('salesman_id')->default(0);
            $table->integer('customer_id')->nullable();
            $table->integer('item_picture')->default(0);
            $table->integer('keep_status')->default(0);
            $table->integer('reseller_status')->default(0);
            $table->integer('change_price')->default(0);
            $table->integer('item_discount')->nullable();
            $table->integer('customer_status')->default(0);
            $table->integer('delivery_status')->default(0);
            $table->integer('receivable_status')->default(0);
            $table->integer('sales_order_status')->default(0);
            $table->string('printer_address', 50)->nullable();
            $table->integer('sync_status')->default(0);
            $table->date('sync_date')->nullable();
            $table->string('item_category_name', 20)->nullable();
            $table->integer('item_stock')->default(0);
            $table->integer('branch_id')->nullable();
            $table->integer('branch_status')->default(0);
            $table->integer('resto_status')->default(0);
            $table->integer('kitchen_status')->default(0);
            $table->integer('division_id')->nullable();
            $table->bigInteger('merchant_id')->nullable();
            $table->integer('warehouse_id')->default(0);
            $table->decimal('user_level', 1, 0)->default(0);
            $table->string('user_token', 250)->nullable();
            $table->enum('log_stat', ['on', 'off'])->nullable();
            $table->text('avatar')->nullable();
            $table->integer('school_period_id')->default(0);
            $table->string('school_period_semester', 10)->nullable();
            $table->integer('teacher_id')->default(0);
            $table->enum('data_state', ['0', '1'])->default('0');
            $table->integer('created_id')->nullable();
            $table->integer('updated_id')->nullable();
            $table->integer('deleted_id')->nullable();
            $table->timestamps();
            $table->softDeletesTz(); //* tambah softdelete
           
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};


