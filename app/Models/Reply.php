<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    public $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function likes(){
        return $this->hasMany(UserReplyLike::class)->where('status',1);
    }
    public function dislikes(){
        return $this->hasMany(UserReplyLike::class)->where('status',0);
    }
}
