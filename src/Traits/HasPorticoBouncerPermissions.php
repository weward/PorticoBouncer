<?php

namespace App\Traits;

use App\Models\Admin\Permission;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPorticoBouncerPermissions
{
    public function specialPermissions(): MorphMany
    {
        return $this->MorphMany(Permission::class, 'entity');
    }
}
