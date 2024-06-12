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
        Schema::table('users', function (Blueprint $table) { 

            $table->unsignedBigInteger('role_id')->after('remember_token');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->char('status', 1)->default('Y')->after('remember_token');
            $table->tinyInteger('is_delete')->default(0)->after('remember_token');
            $table->longText('comments')->nullable()->after('first_name');
            $table->string('source_of_wealth',255)->nullable()->after('first_name');
            $table->string('tax_reg_numebr',100)->nullable()->after('first_name');
            $table->string('tax_number',100)->nullable()->after('first_name');
            $table->dateTime('deceased_date')->nullable()->after('first_name'); 
            $table->boolean('deceased')->default(false)->nullable()->after('first_name');
            $table->string('occupation',255)->after('first_name');
            $table->string('country_code',20)->nullable()->after('first_name');
            $table->string('city',100)->nullable()->after('first_name');
            $table->string('address',100)->nullable()->after('first_name');
            $table->string('address_type',20)->nullable()->after('first_name');
            $table->string('phones',20)->nullable()->after('first_name');
            $table->string('residence',255)->nullable()->after('first_name');
            $table->string('nationality3',255)->nullable()->after('first_name');
            $table->string('nationality2',255)->nullable()->after('first_name');
            $table->string('nationality1',255)->after('first_name');
            $table->string('id_number',255)->nullable()->after('first_name');
            $table->string('passport_country',255)->nullable()->after('first_name');
            $table->string('passport_number',255)->nullable()->after('first_name');
            $table->string('ssn',25)->nullable()->after('first_name');
            $table->string('alias',100)->nullable()->after('first_name');
            $table->string('mothers_name',100)->nullable()->after('first_name');
            $table->string('birth_place',255)->nullable()->after('first_name');
            $table->date('birthdate')->after('first_name'); 
            $table->string('last_name', 100)->after('first_name'); 
            $table->string('prefix', 100)->nullable()->after('first_name'); 
            $table->string('middle_name', 100)->nullable()->after('first_name');  
            $table->string('title', 20)->nullable()->after('id');           
            $table->char('gender', 1)->nullable()->after('id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
