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
        Schema::create('transactions', function (Blueprint $table) {
            $table->timestamps();

            $table->uuid('id')->primary()->unique();
            $table->uuid('remote_id')->unique();
            $table->string('status');
            $table->string('raw_text')->nullable();
            $table->string('description')->nullable();
            $table->string('message')->nullable();
            $table->boolean('is_categorizable');

            $table->string('amount_currency_code')->nullable();
            $table->string('amount_value')->nullable();
            $table->integer('amount_base_unit_value')->nullable();

            $table->string('foreign_currency_code')->nullable();
            $table->string('foreign_value')->nullable();
            $table->integer('foreign_base_unit_value')->nullable();

            $table->string('holdinfo_amount_currency_code')->nullable();
            $table->string('holdinfo_amount_value')->nullable();
            $table->integer('holdinfo_amount_base_unit_value')->nullable();

            $table->string('holdinfo_foreign_currency_code')->nullable();
            $table->string('holdinfo_foreign_value')->nullable();
            $table->integer('holdinfo_foreign_base_unit_value')->nullable();

            $table->string('roundup_amount_currency_code')->nullable();
            $table->string('roundup_amount_value')->nullable();
            $table->integer('roundup_amount_base_unit_value')->nullable();

            $table->string('roundup_boost_portion_currency_code')->nullable();
            $table->string('roundup_boost_portion_value')->nullable();
            $table->integer('roundup_boost_portion_base_unit_value')->nullable();

            $table->string('cashback_description')->nullable();
            $table->string('cashback_amount_currency_code')->nullable();
            $table->string('cashback_amount_value')->nullable();
            $table->integer('cashback_amount_base_unit_value')->nullable();

            $table->string('card_purchase_method')->nullable();
            $table->string('card_number_suffix')->nullable();

            $table->timestamp('remote_settled_at')->nullable();
            $table->timestamp('remote_created_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
