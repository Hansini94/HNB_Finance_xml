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
        Schema::create('reporting_entities', function (Blueprint $table) {
            $table->id();
            $table->integer('rentitiy_id');
            $table->string('rentity_branch', 100);
            $table->string('submission_code', 100);
            $table->string('report_code', 100);
            $table->string('entity_reference', 100);
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
        Schema::dropIfExists('reporting_entities');
    }
};
