<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLamapayInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('lamapay_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('member_id');
            $table->string('model_class_id');
            $table->string('model_class_type');
            $table->string('description');
            $table->string('transaction_number')->unique();
            $table->datetime('duedate');
            $table->datetime('payment_approve_at')->nullable();
            $table->longText('payload')->nullable();
            $table->longText('payment_method_transaction_id')->nullable();
            $table->string('payment_method');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('sub_total');
            $table->decimal('fee')->default(0);
            $table->decimal('total');
            $table->string('currency_iso');
            $table->string('currency_code');


            $table->string('state')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');

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
        Schema::dropIfExists('lamapay_invoices');
        Schema::enableForeignKeyConstraints();
    }
}
