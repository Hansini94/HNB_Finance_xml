<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioEight extends Model
{
    use HasFactory;

    protected $table = 'scenario_8_trans_details';
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
        'transaction_location',
        'transaction_description',
        'date_transaction',
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
        'from_status_code',
        'from_country',
        'to_funds_code',
        'to_account_institution_name',
        'to_account_swift',
        'to_account_non_bank_institution',
        'to_account_branch',
        'to_account_account',
        'to_account_currency_code',
        'to_account_personal_account_type',
        'to_status_code',
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


