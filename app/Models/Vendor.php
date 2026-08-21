<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'address', 
        'business_name', 
        'tax_number', 
        'registration_number', 
        'logo', 
        'description', 
        'bank_name', 
        'account_holder', 
        'account_number', 
        'iban_swift', 
        'website', 
        'is_active'
    ];
}
