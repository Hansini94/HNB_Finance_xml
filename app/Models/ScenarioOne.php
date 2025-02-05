<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioOne extends Model
{
    use HasFactory;

    protected $table = 'scenario_1_trans_details';
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
        'transactionnumber',
        'internal_ref_number',
        'transaction_location',
        'transaction_description',
        'date_transaction',
        'value_date',
        'transmode_code',
        'amount_local',
        'from_funds_code',
        'from_entity_name',
        'from_entity_incorporation_legal_form',
        'from_entity_incorporation_number',
        'from_entity_business',
        'from_entity_address_type',
        'from_entity_address',
        'from_entity_address_city',
        'from_entity_address_country_code',
        'from_entity_incorporation_country_code',
        'from_country',
        'to_funds_code',
        'to_account_institution_name',
        'to_swift',
        'to_non_bank_institution',
        'to_branch',
        'to_account',
        'to_currency_code',
        'to_personal_account_type',
        'to_entity_name',
        'to_entity_incorporation_legal_form',
        'to_entity_incorporation_number',
        'to_entity_business',
        'to_entity_address_type',
        'to_entity_address',
        'to_entity_city',
        'to_entity_country_code',
        'to_entity_incorporation_country_code',
        'to_status_code',
        'to_country',
        'report_indicator',
        'from_person_gender',
        'from_person_title',
        'from_person_first_name',
        'from_person_last_name',
        'from_person_birthdate',
        'from_person_ssn',
        'from_person_nationality1',
        'from_person_residence',
        'from_person_address_type',
        'from_person_address',
        'from_person_city',
        'from_person_country_code',
        'from_person_occupation',
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


