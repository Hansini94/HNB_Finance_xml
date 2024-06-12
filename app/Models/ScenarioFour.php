<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioFour extends Model
{
    use HasFactory;

    protected $table = 'scenario_4_trans_details';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'scenario_type',
        'rentity_id',
        'rentity_branch',
        'submission_code',
        'report_code',
        'entity_reference',
        'submission_date',
        'currency_code_local',
        'reason',
        'transactionnumber',
        'internal_ref_number',
        'transaction_description',
        'date_transaction',
        'value_date',
        'transmode_code',
        'amount_local',
        'from_funds_code',
        'from_account_institution_name',
        'from_account_swift',
        'from_account_non_bank_institution',
        'from_account_account',
        'from_account_currency_code',
        'from_country',
        'to_funds_code',
        'to_account_institution_name',
        'to_account_swift',
        'to_account_non_bank_institution',
        'to_account_branch',
        'to_account_account',
        'to_account_currency_code',
        'to_account_personal_account_type',
        'to_account_name',
        'to_account_incorporation_legal_form',
        'to_account_incorporation_number',
        'to_account_business',
        'to_account_address_type',
        'to_account_address',
        'to_account_city',
        'to_account_country_code',
        'to_account_incorporation_country_code',
        'to_account_director_gender',
        'to_account_director_title',
        'to_account_director_first_name',
        'to_account_director_last_name',
        'to_account_director_birthdate',
        'to_account_director_ssn',
        'to_account_director_passport_number',
        'to_account_director_passport_country',
        'to_account_director_nationality1',
        'to_account_director_residence',
        'to_account_director_address_type',
        'to_account_director_address',
        'to_account_director_city',
        'to_account_director_country_code',
        'to_account_director_occupation',
        'to_account_director_role',
        'status_code',
        'to_country',
        'report_indicator',
        'to_account_signatory_is_primary',
        'to_person_signatory_gender',
        'to_person_signatory_title',
        'to_person_signatory_first_name',
        'to_person_signatory_last_name',
        'to_person_signatory_birthdate',
        'to_person_signatory_ssn',
        'to_person_signatory_passport_number',
        'to_person_signatory_passport_country',
        'to_person_signatory_nationality1',
        'to_person_signatory_residence',
        'to_person_signatory_address_type',
        'to_person_signatory_address',
        'to_person_signatory_city',
        'to_person_signatory_country_code',
        'to_person_signatory_occupation',
        'to_person_signatory_role',
        'status',
        'is_delete',
        'xml_gen_status',
        'created_at',
        'updated_at'
    ];




    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


