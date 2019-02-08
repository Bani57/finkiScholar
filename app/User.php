<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = array('username', 'email', 'password', 'first_name', 'last_name', 'description', 'dob', 'picture');

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');
}
