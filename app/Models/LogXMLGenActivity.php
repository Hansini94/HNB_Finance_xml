<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogXMLGenActivity extends Model
{
    use HasFactory;

    protected $table = 'log_xml_gen_activities';
    public $timestamps = true;

    protected $fillable = [
        'xml_type','subject', 'url','method', 'ip', 'user_id','from_date','to_date', 'status','xml_gen_status'
    ];
}
