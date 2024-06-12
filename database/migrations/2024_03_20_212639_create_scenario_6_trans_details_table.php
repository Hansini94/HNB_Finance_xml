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
        Schema::create('scenario_6_trans_details', function (Blueprint $table) {
            $table->id();
            $table->string('account_type', 20);
            $table->unsignedBigInteger('rentity_id');
            $table->string('rentity_branch');
            $table->string('submission_code', 1);
            $table->string('report_code', 3);
            $table->string('entity_reference');
            $table->dateTime('submission_date');
            $table->string('currency_code_local', 3);
            $table->string('transactionnumber');
            $table->string('internal_ref_number');
            $table->text('transaction_description');
            $table->dateTime('date_transaction');
            $table->dateTime('value_date');
            $table->string('transmode_code', 4);
            $table->unsignedBigInteger('amount_local');
            $table->string('from_funds_code', 4);
            $table->string('from_account_institution_name');
            $table->string('from_account_swift');
            $table->boolean('from_account_non_bank_institution');
            $table->string('from_account_account');
            $table->string('from_account_currency_code', 3); 
            $table->string('from_country', 2);
            $table->string('to_funds_code', 4);
            $table->string('to_account_institution_name');
            $table->string('to_account_swift');
            $table->boolean('to_account_non_bank_institution');
            $table->string('to_account_branch');
            $table->string('to_account_account');
            $table->string('to_account_currency_code', 3);
            $table->string('to_account_personal_account_type');
            $table->string('to_account_name');
            $table->string('to_account_incorporation_legal_form');
            $table->string('to_account_incorporation_number');
            $table->string('to_account_business');
            $table->string('to_account_address_type', 4);
            $table->text('to_account_address');
            $table->string('to_account_city');
            $table->string('to_account_country_code', 2);
            $table->char('to_account_incorporation_country_code', 2); // CHAR(2)
            $table->string('to_account_director_gender', 1);
            $table->string('to_account_director_title');
            $table->string('to_account_director_first_name');
            $table->string('to_account_director_last_name');
            $table->dateTime('to_account_director_birthdate');
            $table->string('to_account_director_ssn');
            $table->string('to_account_director_nationality1', 2);
            $table->string('to_account_director_residence', 2);
            $table->string('to_account_director_address_type', 4);
            $table->text('to_account_director_address');
            $table->string('to_account_director_city');
            $table->string('to_account_director_country_code', 2);
            $table->string('to_account_director_occupation');
            $table->string('to_account_director_role');            
            $table->char('status_code', 4);
            $table->string('to_country', 2);
            $table->string('report_indicator');
            $table->boolean('to_account_signatory_is_primary');
            $table->string('to_account_signatory_gender', 1);
            $table->string('to_account_signatory_title');
            $table->string('to_account_signatory_first_name');
            $table->string('to_account_signatory_last_name');
            $table->dateTime('to_account_signatory_birthdate');
            $table->string('to_account_signatory_ssn');
            $table->string('to_account_signatory_nationality1', 2);
            $table->string('to_account_signatory_residence', 2);
            $table->string('to_account_signatory_address_type', 4);
            $table->text('to_account_signatory_address');
            $table->string('to_account_signatory_city');
            $table->string('to_account_signatory_country_code', 2);
            $table->string('to_account_signatory_occupation');
            $table->string('to_account_signatory_role');  
            $table->char('status', 1);
            $table->tinyInteger('is_delete')->default(0);
            $table->char('xml_gen_status', 1);
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
        Schema::dropIfExists('scenario_6_trans_details');
    }
};
