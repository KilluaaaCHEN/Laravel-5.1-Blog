<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = 'links';
    public $guarded = [];
    public static function getLinks()
    {
        $links=Links::orderBy('sort_num')->get();
        return $links;
    }
}
