<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'gender',
        'title',
        'first_name',
        'middle_name',
        'prefix',
        'last_name',
        'birthdate',
        'birth_place',
        'mothers_name',
        'alias',
        'ssn',
        'passport_number',
        'passport_country',
        'id_number',
        'nationality1',
        'nationality2',
        'nationality3',
        'residence',
        'phones',
        'address_type',
        'address',
        'city',
        'country_code',
        'occupation',
        'deceased',
        'deceased_date',
        'tax_number',
        'tax_reg_numebr',
        'source_of_wealth',
        'comments',
        'email',
        'email_verified_at',
        'is_delete',
        'status',
        'role_id',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
