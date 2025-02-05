<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioThree extends Model
{
    use HasFactory;

    protected $table = 'scenario_3_trans_details';
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
        'transaction_description',
        'date_transaction',
        'value_date',
        'transmode_code',
        'amount_local',
        'from_funds_code',
        'from_account_institution_name',
        'from_account_swift',
        'from_account_non_bank_institution',
        'from_account_branch',
        'from_account_account',
        'from_account_currency_code',
        'from_account_personal_account_type',
        'from_account_name',
        'from_account_incorporation_legal_form',
        'from_account_incorporation_number',
        'from_account_business',
        'from_account_address_type',
        'from_account_address',
        'from_account_city',
        'from_account_country_code',
        'from_account_incorporation_country_code',
        'status_code',
        'from_country',
        'to_funds_code',
        'to_account_institution_name',
        'to_account_swift',
        'to_account_non_bank_institution',
        'to_account_account',
        'to_account_currency_code',
        'to_country',
        'report_indicator',
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


