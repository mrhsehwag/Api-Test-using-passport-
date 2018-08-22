<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserApi extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $fillable = [
        'name', 'mobile', 'password',
    ];

}
