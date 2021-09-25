<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentPlatForm extends Model
{
    use HasFactory;
    protected $fillable=[
        'name','image'
    ];  
}
