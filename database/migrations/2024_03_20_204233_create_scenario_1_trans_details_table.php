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
        Schema::create('scenario_1_trans_details', function (Blueprint $table) {
            $table->id();
            $table->integer('rentity_id'); //Mandatory
            $table->string('rentity_branch');
            $table->char('submission_code', 1); // CHAR(1) //Mandatory
            $table->char('report_code', 3); // CHAR(3) //Mandatory
            $table->string('entity_reference');
            $table->timestamp('submission_date');
            $table->char('currency_code_local', 3); // CHAR(3) //Mandatory
            $table->string('transactionnumber'); //Mandatory
            $table->string('internal_ref_number')->nullable();
            $table->string('transaction_location'); 
            $table->text('transaction_description');//Mandatory
            $table->timestamp('date_transaction'); //Mandatory
            $table->timestamp('value_date');
            $table->string('transmode_code'); //Mandatory
            $table->decimal('amount_local', 10, 2); //Mandatory
            $table->string('from_funds_code'); //Mandatory
            $table->string('from_entity_name'); //Mandatory
            $table->char('from_entity_incorporation_legal_form', 4); // CHAR(4) //Mandatory
            $table->string('from_entity_incorporation_number');
            $table->string('from_entity_business'); //Mandatory
            $table->char('from_entity_address_type', 4); // CHAR(4)
            $table->text('from_entity_address');
            $table->string('from_entity_address_city');
            $table->char('from_entity_address_country_code', 2); // CHAR(2)
            $table->char('from_entity_incorporation_country_code', 2); // CHAR(2)
            $table->char('from_entity_director_gender', 1); // CHAR(1)
            $table->char('from_entity_director_title', 4); // CHAR(4)
            $table->string('from_entity_director_first_name'); //Mandatory
            $table->string('from_entity_director_last_name'); //Mandatory
            $table->timestamp('from_entity_director_birthdate'); //Mandatory
            $table->string('from_entity_director_ssn');
            $table->string('from_entity_director_passport_number')->nullable();
            $table->char('from_entity_director_passport_country', 2); // CHAR(2)
            $table->char('from_entity_director_nationality1', 2)->nullable(); // CHAR(2) //Mandatory
            $table->char('from_entity_director_residence', 2)->nullable(); // CHAR(2) //Mandatory
            $table->char('from_entity_director_address_type', 4); // CHAR(4)
            $table->text('from_entity_director_address');
            $table->string('from_entity_director_city');
            $table->char('from_entity_director_country_code', 2); // CHAR(2)
            $table->string('from_entity_director_occupation')->nullable(); //Mandatory
            $table->char('from_entity_director_role', 5); // CHAR(5)
            $table->char('from_country', 2); // CHAR(2) //Mandatory
            $table->string('to_funds_code'); //Mandatory
            $table->text('to_account_institution_name');
            $table->string('to_swift');
            $table->boolean('to_non_bank_institution');
            $table->string('to_branch');
            $table->string('to_account');
            $table->char('to_currency_code', 3); // CHAR(3)
            $table->string('to_personal_account_type');
            $table->text('to_entity_name'); //Mandatory
            $table->char('to_entity_incorporation_legal_form', 4); // CHAR(4) //Mandatory
            $table->string('to_entity_incorporation_number');
            $table->string('to_entity_business'); //Mandatory
            $table->char('to_entity_address_type', 4); // CHAR(4) //Mandatory
            $table->text('to_entity_address'); //Mandatory
            $table->string('to_entity_city'); //Mandatory
            $table->char('to_entity_country_code', 2); // CHAR(2) //Mandatory
            $table->char('to_entity_incorporation_country_code', 2); // CHAR(2)
            $table->char('to_entity_director_gender', 1); // CHAR(1)
            $table->char('to_entity_director_title', 4); // CHAR(4)
            $table->string('to_entity_director_first_name'); //Mandatory
            $table->string('to_entity_director_last_name'); //Mandatory
            $table->timestamp('to_entity_director_birthdate'); //Mandatory
            $table->string('to_entity_director_ssn');
            $table->string('to_entity_director_passport_number');
            $table->char('to_entity_director_passport_country', 2); // CHAR(2)
            $table->char('to_entity_director_nationality1', 2); // CHAR(2) //Mandatory
            $table->char('to_entity_director_residence', 2); // CHAR(2) //Mandatory
            $table->char('to_entity_director_address_type', 4); // CHAR(4) //Mandatory
            $table->text('to_entity_director_address'); //Mandatory
            $table->string('to_entity_director_city'); //Mandatory
            $table->char('to_entity_director_country_code', 2); // CHAR(2) //Mandatory
            $table->string('to_entity_director_occupation'); //Mandatory
            $table->char('to_entity_director_role', 5); // CHAR(5)
            $table->string('to_status_code');
            $table->char('to_country', 2); // CHAR(2) //Mandatory
            $table->string('report_indicator'); //Mandatory 
            $table->char('from_person_gender', 1); // CHAR(1)
            $table->char('from_person_title', 4); // CHAR(4)
            $table->string('from_person_first_name'); //Mandatory
            $table->string('from_person_last_name'); //Mandatory
            $table->timestamp('from_person_birthdate'); //Mandatory
            $table->string('from_person_ssn')->nullable();
            $table->char('from_person_nationality1', 2); // CHAR(2) //Mandatory
            $table->char('from_person_residence', 2)->nullable(); // CHAR(2) //Mandatory
            $table->char('from_person_address_type', 4); // CHAR(4) //Mandatory
            $table->text('from_person_address'); //Mandatory 
            $table->string('from_person_city'); //Mandatory
            $table->char('from_person_country_code', 2); // CHAR(2) //Mandatory
            $table->string('from_person_occupation'); //Mandatory            
            $table->boolean('to_signatory_is_primary');
            $table->char('to_signatory_gender', 1); // CHAR(1)
            $table->char('to_signatory_title', 4); // CHAR(4)
            $table->string('to_signatory_first_name'); //Mandatory
            $table->string('to_signatory_last_name');//Mandatory
            $table->timestamp('to_signatory_birthdate');//Mandatory
            $table->string('to_signatory_ssn');
            $table->char('to_signatory_nationality1', 2); // CHAR(2) //Mandatory
            $table->char('to_signatory_residence', 2); // CHAR(2) //Mandatory
            $table->char('to_signatory_address_type', 4); ////Mandatory
            $table->string('to_signatory_address');//Mandatory
            $table->string('to_signatory_city');//Mandatory
            $table->string('to_signatory_country_code');//Mandatory
            $table->string('to_signatory_occupation'); //Mandatory
            $table->string('to_signatory_role');
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
        Schema::dropIfExists('scenario_1_trans_details');
    }
};



