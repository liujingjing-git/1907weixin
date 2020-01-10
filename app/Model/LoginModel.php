<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    public $primaryKey="login_id";

    protected $table="login";

    public $timestamps=false;
  
    protected $guarded = [];
}
