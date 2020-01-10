<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    protected $table = 'news';
    public $primaryKey = 'new_id';
    public $timestamps = false;
    protected $guarded=[];
}
