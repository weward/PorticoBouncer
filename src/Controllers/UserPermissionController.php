<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserPermissionRequest;
use App\Models\User;
use Bouncer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserPermissionController extends Controller
{
    /**
     * List all user roles and its abilities
     * List all special permissions iie user-specific/approval
     */
    public function edit(Request $request, User $user)
    {
        $user->load(['roles', 'roles.abilities', 'specialPermissions']);

        return Inertia::render('Admin/User/UserPermissions', [
            'roles' => $user->roles,
            'special_permissions' => $user->specialPermissions
                ->map->only('ability')->pluck('ability')->toArray(),
        ]);
    }

    public function update(UserPermissionRequest $request, User $user)
    {
        $user->load(['specialPermissions']);
        $specialPermissions = $user->specialPermissions;
        // Update user roles
        Bouncer::sync($user)->roles($request->roles);

        // Delete removed special permission
        $this->removeSpecialPermission($specialPermissions, $request->special_permissions, $user);
        // Insert new special permission
        $this->insertSpecialPermission($specialPermissions, $request->special_permissions, $user);

        $user->load(['roles', 'specialPermissions']);
        $user->fresh();

        return response()->jsonApi($user);
    }

    public function removeSpecialPermission($specialPermissions, $payload, $user): void
    {
        $toRemove = $specialPermissions->whereNotIn('ability_id', $payload);
        $toRemove = $toRemove->pluck('ability_id');

        $user->specialPermissions()->whereIn('ability_id', $toRemove->toArray())->delete();
    }

    public function insertSpecialPermission($specialPermissions, $payload, $user): void
    {
        $existing = $specialPermissions->whereIn('ability_id', $payload);
        $existing = $existing->pluck('ability_id');
        $new = collect($payload)->filter(function ($item) use ($existing) {
            return $existing->doesntContain($item);
        });
        // Compose
        $toSave = $new->map(fn ($item, $key) => [
            'ability_id' => $item,
            'entity_id' => $user->id,
            'entity_type' => User::class,
        ]);

        $user->specialPermissions()->insert($toSave->toArray());
    }
}
