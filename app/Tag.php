<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public static function top()
    {
        $tags = Tag::all();
        $countTags = array();
        foreach ($tags as $t) {
            $countTags[$t->id] = count($t->posts);
        }
        arsort($countTags);
        $top = array();
        $IDs = array_keys($countTags);
        for ($i=0; $i <5 ; $i++) { 
            array_push($top, Tag::find($IDs[$i]));
        }
        return $top;
    }
}
