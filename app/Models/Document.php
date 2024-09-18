<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $fillable = [
        'title',
        'department_id',
        'type_of_paper_id',
        'publication_date',
        'doi',
        'abstract',
        'issue_numbers',
        'status',
        'user_id',
        'upload_type',
    ];

    public function document_file(){
        return $this->hasOne(DocumentFile::class,'document_id', 'id');
    }

    public function authors(){
        return $this->hasMany(DocumentAuthor::class,'document_id', 'id');
    }

    public function keywords(){
        return $this->hasMany(Keyword::class,'document_id', 'id');
    }

    public function typeofpaper(){
        return $this->hasOne(TypeOfPaper::class,'id', 'type_of_paper_id');
    }

    public function feedback(){
        return $this->hasMany(Feedback::class,'document_id', 'id')->with('sender')->oldest();
    }
    public function department(){
        return $this->hasOne(Department::class,'id', 'department_id');
    }

    public function userdoc(){
        return $this->hasOne(User::class,'id', 'user_id');
    }



}
