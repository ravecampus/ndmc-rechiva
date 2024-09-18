<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $fillable = [
        'message',
        'document_id',
        'sender_id',
        // 'receiver_id'
    ];

    public function sender(){
        return $this->hasOne(User::class,'id', 'sender_id');
    }

    // public function receiver(){
    //     return $this->hasOne(User::class,'id', 'receiver_id');
    // }

}
