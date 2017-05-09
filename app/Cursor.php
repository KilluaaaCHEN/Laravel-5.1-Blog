<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cursor extends Model
{
    public $timestamps = false;
    protected $table='disqus_cursor';
}
