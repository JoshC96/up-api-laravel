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
        Schema::create('accounts', function (Blueprint $table) {
            $table->timestamps();

            $table->uuid('id')->primary()->unique();
            $table->uuid('remote_id')->unique();
            $table->string('display_name')->nullable();
            $table->string('account_type')->nullable();
            $table->string('ownership_type')->nullable();
            $table->string('balance_currency_code')->nullable();
            $table->string('balance_value')->nullable();
            $table->integer('balance_base_unit_value')->nullable();
            $table->timestamp('remote_created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
