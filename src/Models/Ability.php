<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Ability as SilberBouncerAbility;
use App\Models\Admin\Role;

class Ability extends SilberBouncerAbility
{
    use HasFactory;
    
}
