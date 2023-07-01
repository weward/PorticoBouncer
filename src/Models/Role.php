<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Role as SilberBouncerRole;
use App\Models\Admin\Ability;

class Role extends SilberBouncerRole
{
    use HasFactory;


}
