<?php

namespace App;

use Backpack\CRUD\CrudTrait; // <------------------------------- this one
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\Base\app\Notifications\ResetPasswordNotification as ResetPasswordNotification;

class Settings extends Model
{
    use CrudTrait; // <----- this
    protected $fillable = [
        'id', 'value'
    ];
    protected $table = 'settings';
}