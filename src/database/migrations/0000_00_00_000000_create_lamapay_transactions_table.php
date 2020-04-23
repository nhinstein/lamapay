<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLamapayTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('lamapay_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_number');
            $table->string('reference_number')->unique();
            $table->decimal('amount');
            $table->string('model_class_id');
            $table->string('model_class_type');

            $table->string('state')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('lamapay_transactions');
        Schema::enableForeignKeyConstraints();
    }
}
