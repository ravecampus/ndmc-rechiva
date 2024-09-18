<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAuthor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'author_id', 'document_id'];
}
