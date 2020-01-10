<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    protected $table = 'media';
    public $primaryKey = 'media_id';
    public $timestamps = false;
    protected $guarded=[];
}
