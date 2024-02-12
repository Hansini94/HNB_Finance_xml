<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->date('trans_date');
            $table->string('trans_branch', 255);
            $table->integer('invoice_no');
            $table->string('trans_type', 255);
            $table->string('trans_traveler', 255);
            $table->string('trans_provider', 255);
            $table->decimal('trans_sales', 10, 2);
            $table->decimal('trans_payments', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};
