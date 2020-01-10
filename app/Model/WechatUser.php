<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WechatUser extends Model
{
    protected $table = 'wechat_user';
    public $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded=[];

}
