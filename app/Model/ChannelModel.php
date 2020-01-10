<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChannelModel extends Model
{
    protected $table = 'channel';
    public $primaryKey = 'c_id';
    public $timestamps = false;
    protected $guarded=[];
}
