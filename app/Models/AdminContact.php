<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminContact extends Model
{
    use HasFactory;

    protected $table = 'admin_contact';
    protected $fillable = ['contact_nnumber', 'information'];
}
