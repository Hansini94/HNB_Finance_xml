<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorIdDetail extends Model
{
    use HasFactory;

    protected $table = 'director_id_details';
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




    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}


