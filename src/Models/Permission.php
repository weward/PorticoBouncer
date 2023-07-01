<?php

namespace App\Models;

use App\Models\Admin\Ability;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $guarded = ['updated_at'];

    public function ability(): BelongsTo
    {
        return $this->belongsTo(Ability::class);
    }
}
