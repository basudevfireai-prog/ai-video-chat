<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Model
{
    use HasRoles;
    protected $table = 'admins';

    // CRITICAL FIX: Define the guard used by this model
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
    ];

    protected $attributes = [
        'otp' => '0',
    ];

    protected $hidden = [
        'password',
    ];


}
