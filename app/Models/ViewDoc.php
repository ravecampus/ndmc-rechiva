<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewDoc extends Model
{
    use HasFactory;

    protected $table = "view_docs";
    protected  $fillable = [
        'document_id'
    ];
}
