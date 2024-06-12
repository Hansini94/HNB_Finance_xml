<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{

    use HasFactory;

    protected $table = 'employee_details';
    public $timestamps = true;

    protected $fillable = [
        'employer_name',
        'address_type',
        'address',
        'town',
        'city',
        'zip',
        'country_code',
        'state',
        'comments',
        'tph_contact_type',
        'tph_communication_type',
        'tph_number',
        'tph_extension',
        'employer_phone_id_comments',
        'identification_type',
        'identification_number',
        'identification_issue_date',
        'identification_issued_by',
        'identification_issue_country',
        'identification_comments'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'identification_issue_date' => 'datetime',
    ];
}
