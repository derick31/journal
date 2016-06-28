<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{
    //
    protected $fillable = ['title','content','time','date','privacy'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function userLikes(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}
