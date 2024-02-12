<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_details';
    public $timestamps = true;

    protected $fillable = [ 
        'id',
        'trans_date',
        'trans_branch',
        'invoice_no',
        'trans_type', 
        'trans_traveler', 
        'trans_provider', 
        'trans_sales',
        'trans_payments',
        'created_at',
        'updated_at'
    ];
}
