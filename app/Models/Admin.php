<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
// Define the table if it's not the default 'admins'
    protected $table = 'admins';
}

