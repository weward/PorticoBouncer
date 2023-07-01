<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Silber\Bouncer\Database\Ability as SilberBouncerAbility;

class Ability extends SilberBouncerAbility
{
    use HasFactory;
}
