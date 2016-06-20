<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $table = 'post_tag';
    protected $guarded = [];
    public $timestamps = false;


    public function tag()
    {
        return $this->hasOne(Tag::class,'tag_id','tag_id');
    }
}
