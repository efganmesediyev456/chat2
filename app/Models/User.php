<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function replies(){
        return $this->hasMany(Comment::class);
    }

    public function likes(){
        return $this->hasMany(UserCommentLike::class);
    }
    public function replyLikes(){
        return $this->hasMany(UserReplyLike::class);
    }

    public function friend1(){
        return $this->belongsToMany(User::class,'friends','from_id','to_id')->withPivot('status');
    }

    public function friend2(){
        return $this->belongsToMany(User::class,'friends','to_id','from_id')->withPivot('status');
    }

    public function friends(){
        return $this->friend1->merge($this->friend2);
    }
    public function block1(){
        return $this->belongsToMany(User::class,'blocks','from_id','to_id');
    }

    public function block2(){
        return $this->belongsToMany(User::class,'blocks','to_id','from_id');
    }

    public function blocks(){
        return $this->block1->merge($this->block2);
    }

    public function message1(){
        return $this->belongsToMany(User::class,'messages','from_id','to_id')->withPivot('message')->withTimestamps();
    }

    public function message2(){
        return $this->belongsToMany(User::class,'messages','to_id','from_id')->withPivot('message')->withTimestamps();
    }

    public function messages(){
        return $this->message1->merge($this->message2);
    }


}
