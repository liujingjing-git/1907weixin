<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TypeModel extends Model
{
    protected $table = 'type';
    public $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded=[];

}
