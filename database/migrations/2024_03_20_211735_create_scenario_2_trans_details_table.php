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
        Schema::create('scenario_2_trans_details', function (Blueprint $table) {
            $table->id();
            $table->string('account_type', 20);
            $table->integer('rentity_id');
            $table->string('rentity_branch');
            $table->char('submission_code', 1);
            $table->char('report_code', 3);
            $table->string('entity_reference');
            $table->timestamp('submission_date');
            $table->char('currency_code_local', 3);
            $table->text('reason');
            $table->string('transactionnumber');
            $table->string('internal_ref_number');
            $table->string('transaction_location');
            $table->text('transaction_description');
            $table->timestamp('date_transaction');
            $table->timestamp('value_date');
            $table->string('transmode_code');
            $table->decimal('amount_local', 10, 2);
            $table->string('from_funds_code');            
            $table->text('from_account_institution_name');
            $table->string('from_account_swift');
            $table->boolean('from_account_non_bank_institution');
            $table->string('from_account_branch');
            $table->string('from_account_number');
            $table->char('from_account_currency_code', 3); // CHAR(3)
            $table->string('from_account_personal_account_type');            
            $table->string('from_entity_name');
            $table->char('from_entity_incorporation_legal_form', 4);
            $table->string('from_entity_incorporation_number');
            $table->string('from_entity_business');
            $table->char('from_entity_address_type', 4);
            $table->text('from_entity_address');
            $table->string('from_entity_address_city');
            $table->char('from_entity_address_country_code', 2);
            $table->char('from_entity_incorporation_country_code', 2);
            $table->char('from_entity_director_gender', 1);
            $table->char('from_entity_director_title', 4);
            $table->string('from_entity_director_first_name');
            $table->string('from_entity_director_last_name');
            $table->timestamp('from_entity_director_birthdate');
            $table->string('from_entity_director_ssn');
            $table->char('from_entity_director_nationality1', 2);
            $table->char('from_entity_director_residence', 2);
            $table->char('from_entity_director_address_type', 4);
            $table->text('from_entity_director_address');
            $table->string('from_entity_director_city');
            $table->char('from_entity_director_country_code', 2);
            $table->string('from_entity_director_occupation');
            $table->char('from_entity_director_role', 5); 
            $table->char('from_account_status_code', 5);
            $table->char('from_country', 2); // CHAR(2)            
            $table->string('to_funds_code');
            $table->string('to_entity_name');
            $table->char('to_entity_incorporation_legal_form', 4);
            $table->string('to_entity_incorporation_number');
            $table->string('to_entity_business');
            $table->char('to_entity_address_type', 4);
            $table->text('to_entity_address');
            $table->string('to_entity_address_city');
            $table->char('to_entity_address_country_code', 2);
            $table->char('to_entity_incorporation_country_code', 2);
            $table->char('to_entity_director_gender', 1);
            $table->char('to_entity_director_title', 4);
            $table->string('to_entity_director_first_name');
            $table->string('to_entity_director_last_name');
            $table->timestamp('to_entity_director_birthdate');
            $table->string('to_entity_director_ssn');
            $table->char('to_entity_director_nationality1', 2);
            $table->char('to_entity_director_residence', 2);
            $table->char('to_entity_director_address_type', 4);
            $table->text('to_entity_director_address');
            $table->string('to_entity_director_city');
            $table->char('to_entity_director_country_code', 2);
            $table->string('to_entity_director_occupation');
            $table->char('to_entity_director_role', 5);
            $table->char('to_country', 2); // CHAR(2)
            $table->string('report_indicator');      
            $table->boolean('from_account_signatory_is_primary');
            $table->char('from_account_signatory_gender', 1); // CHAR(1)
            $table->char('from_account_signatory_title', 4); // CHAR(4)
            $table->string('from_account_signatory_first_name');
            $table->string('from_account_signatory_last_name');
            $table->timestamp('from_account_signatory_birthdate');
            $table->string('from_account_signatory_ssn');
            $table->char('from_account_signatory_nationality1', 2); // CHAR(2)
            $table->char('from_account_signatory_residence', 2); // CHAR(2)
            $table->char('from_account_signatory_address_type', 4); //
            $table->string('from_account_signatory_address');
            $table->string('from_account_signatory_city');
            $table->string('from_account_signatory_country_code');
            $table->string('from_account_signatory_occupation');
            $table->string('from_account_signatory_role');        
            $table->char('to_person_gender', 1); // CHAR(1)
            $table->char('to_person_title', 4); // CHAR(4)
            $table->string('to_person_first_name');
            $table->string('to_person_last_name');
            $table->timestamp('to_person_birthdate');
            $table->string('to_person_ssn');
            $table->char('to_person_nationality1', 2); // CHAR(2)
            $table->char('to_person_residence', 2); // CHAR(2)
            $table->char('to_person_address_type', 4); //
            $table->string('to_person_address');
            $table->string('to_person_city');
            $table->string('to_person_country_code');
            $table->string('to_person_occupation'); 
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
        Schema::dropIfExists('scenario_2_trans_details');
    }
};
