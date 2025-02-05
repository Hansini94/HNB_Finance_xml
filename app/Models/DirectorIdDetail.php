<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorIdDetail extends Model
{
    use HasFactory;

    protected $table = 'director_details';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'scenario_no',
        'entity_type',
        'entity_id',
        'gender',
        'title',
        'first_name',
        'last_name',
        'birthdate',
        'ssn',
        'passport_number',
        'passport_country',
        'nationality1',
        'residence',
        'address_type',
        'address',
        'city',
        'country_code',
        'occupation',
        'role',
        'status',
        'is_delete'
    ];

}


