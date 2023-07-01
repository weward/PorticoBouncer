<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Silber\Bouncer\Database\Role as SilberBouncerRole;

class Role extends SilberBouncerRole
{
    use HasFactory;
}
