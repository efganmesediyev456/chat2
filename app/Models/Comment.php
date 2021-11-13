<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    public $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function replies(){
        return $this->hasMany(Reply::class);
    }
    public function likes(){
        return $this->hasMany(UserCommentLike::class)->where('status',1);
    }
    public function dislikes(){
        return $this->hasMany(UserCommentLike::class)->where('status',0);
    }
}
