<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'abbreviation'
    ];

    public function docs(){
        return $this->hasMany(Document::class,'department_id', 'id');
    }
}
