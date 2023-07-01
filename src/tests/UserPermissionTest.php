<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Ability;
use App\Models\Admin\Role;
use App\Models\User;
use Bouncer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_user_permissions_edit_page(): void
    {
        $user = User::factory()->create();

        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);

        $roleCount = $user->roles()->count();

        $res = $this->actingAs($user)->get(route('user-permissions.edit', $user->id));

        $res->assertInertia(function (Assert $page) use ($roleCount) {
            $page->has('roles', $roleCount);
        });
    }

    public function test_can_render_user_permissions_edit_page_with_special_permissions(): void
    {
        $user = User::factory()->create();
        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);

        $rolesCount = $user->roles()->count();
        // Assign special ability
        $user->allow('special.ability');
        $user->allow('special.ability-2');

        $specialPermissionsCount = $user->specialPermissions()->count();

        $res = $this->actingAs($user)->get(route('user-permissions.edit', $user->id));

        $res->assertInertia(function (Assert $page) use ($rolesCount, $specialPermissionsCount) {
            $page->has('roles', $rolesCount);
            $page->has('special_permissions', $specialPermissionsCount);
        });
    }

    public function test_can_update_user_permissions_with_new_role(): void
    {
        $user = User::factory()->create();
        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);

        $roles = $user->roles()->get();
        // Asssign a new Role
        $role = Role::create(['title' => 'New Role', 'name' => 'role.new']);

        $rolesArray = [...$roles->pluck('id')->toArray(), ...[$role->id]];
        $specialPermissionsArray = [];

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => $rolesArray,
            'special_permissions' => $specialPermissionsArray,
        ]);

        $rolesCount = $user->roles()->count();

        $res->assertJsonPath('name', $user->name);
        $res->assertJsonCount($rolesCount, 'roles');
    }

    public function test_can_update_user_permissions_remove_a_role(): void
    {
        $user = User::factory()->create();
        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);
        Bouncer::assign('author')->to($user);

        $roles = $user->roles()->get();

        $rolesCount = $roles->count();
        $roles = $roles->toArray();
        // remove the last one
        unset($roles[$rolesCount - 1]);

        $rolesArray = collect($roles)->pluck('id')->toArray();
        $specialPermissionsArray = [];

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => $rolesArray,
            'special_permissions' => $specialPermissionsArray,
        ]);

        $newRolesCount = $user->roles()->count();

        $res->assertJsonPath('name', $user->name);
        $this->assertEquals(true, ($rolesCount - 1) == $newRolesCount); // minus 1 role (removed)
        $res->assertJsonCount($newRolesCount, 'roles'); // new count
    }

    public function test_can_update_user_permissions_add_special_ability(): void
    {
        $user = User::factory()->create();
        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);
        Bouncer::assign('author')->to($user);

        $rolesArray = $user->roles()->get()->pluck('id')->toArray();
        $specialAbilities = Ability::factory()->count(2)->create();

        $specialPermissionsArray = $specialAbilities->pluck('id')->toArray();

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => $rolesArray,
            'special_permissions' => $specialPermissionsArray, // ids
        ]);

        $res->assertJsonPath('name', $user->name);
        $res->assertJsonCount(count($rolesArray), 'roles'); // new count
        $res->assertJsonCount(count($specialPermissionsArray), 'special_permissions'); // new count
    }

    public function test_can_update_user_permissions_remove_special_ability(): void
    {
        $user = User::factory()->create();
        // Assign abilities to roles
        Bouncer::allow('admin')->to('users.create');
        Bouncer::allow('admin')->to('users.edit');
        Bouncer::allow('moderator')->to('users.change-status');
        // Assign roles
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);
        Bouncer::assign('author')->to($user);

        $rolesArray = $user->roles()->get()->pluck('id')->toArray();
        $specialAbilities = Ability::factory()->count(2)->create();
        // Attach special permissions to user
        $specialAbilities->each(function ($item) use ($user) {
            $user->allow($item->name);
        });

        $originalSpecialPermissionsArrayCount = $specialAbilities->count();
        $specialPermissionsArray = $specialAbilities->pluck('id')->toArray();
        // Remove a special permission
        unset($specialPermissionsArray[count($specialPermissionsArray) - 1]);

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => $rolesArray,
            'special_permissions' => $specialPermissionsArray, // ids
        ]);

        $res->assertJsonPath('name', $user->name);
        $res->assertJsonCount(count($rolesArray), 'roles');
        $res->assertJsonCount($originalSpecialPermissionsArrayCount - 1, 'special_permissions'); // new count
    }

    public function test_updating_user_permissions_without_roles_returns_error(): void
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => [],
            'special_permissions' => [],
        ]);

        $res->assertStatus(302);
        $res->assertInvalid(['roles']);
    }

    public function test_updating_user_permission_using_strings_returns_error(): void
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->put(route('user-permissions.update', $user->id), [
            'roles' => ['test', 'test-2'],
            'special_permissions' => ['test.create', 'test.edit'],
        ]);

        $res->assertStatus(302);
        $res->assertInvalid([
            'roles.0',
            'roles.1',
            'special_permissions.0',
            'special_permissions.1',
        ]);
    }
}
