<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = "notification";
    protected  $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'role',
        'document_id',
        'caption'
    ];

    public function sender(){
        return $this->hasOne(User::class,'id', 'sender_id');
    }
    public function receiver(){
        return $this->hasOne(User::class,'id', 'receiver_id');
    }

}
