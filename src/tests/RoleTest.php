<?php

namespace Tests\Feature\Feature\Admin;

use App\Models\Admin\Ability;
use App\Models\Admin\Role;
use App\Models\User;
use Bouncer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_render_index_page(): void
    {
        $user = User::factory()->create();
        $res = $this->actingAs($user)->get(route('roles.index'));

        $res->assertStatus(200);
    }

    public function test_list_all_roles(): void
    {
        $count = 2;
        $user = User::factory()->withCompany()->create();
        Bouncer::assign('admin')->to($user);
        Bouncer::assign('moderator')->to($user);

        $res = $this->actingAs($user)->get(route('roles.index'));

        $res->assertInertia(function (Assert $page) use ($count) {
            $page->has('roles.data', $count);
        });
    }

    public function test_can_render_create_page()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->get(route('roles.create'));

        $res->assertStatus(200);
    }

    public function test_can_render_create_page_with_abilities()
    {
        $user = User::factory()->create();

        Bouncer::allow('admin')->to('users.create');

        $res = $this->actingAs($user)->get(route('roles.create'));

        $res->assertInertia(fn (Assert $page) => $page->has('abilities', 1));
    }

    public function test_can_store_a_new_role()
    {
        $user = User::factory()->withCompany()->create();

        $name = 'admin';
        $title = 'Admin';

        Ability::insert([
            [
                'id' => 1,
                'name' => 'users.edit',
                'title' => 'Edit Users',
            ],
            [
                'id' => 2,
                'name' => 'users.delete',
                'title' => 'Delete Users',
            ],
        ]);

        // Role table is empty
        $roleCount = Role::count();
        $this->assertEquals(0, $roleCount);

        $res = $this->actingAs($user)
            ->post(
                route('roles.store'),
                [
                    'title' => $title,
                    'abilities' => [1, 2],
                ]
            );

        // Role table has exactly 1 record
        $role = Role::get();
        $this->assertEquals(1, $role->count());

        $res->assertValid()
            ->assertRedirectToRoute('roles.show', ['role' => $role->first()->id])
            ->assertSessionHas('successMsg', Role::CREATE_SUCCESS_MSG);
    }

    public function test_can_render_show_page_with_abilities()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';

        $role = Role::create([
            'name' => $name,
            'title' => $title,
        ]);

        Bouncer::allow($name)->to('users.create');

        $res = $this->actingAs($user)
            ->get(route('roles.show', $role->id));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('role.name', $name);
            $page->where('role.title', $title);
            $page->has('role.created_at_formatted');
            $page->has('role.abilities'); // with abilities
            $page->has('role.roleAbilities'); // with abilities
        });
    }

    public function test_can_render_edit_page_with_marked_abilities()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';

        $role = Role::create([
            'name' => $name,
            'title' => $title,
        ]);

        Bouncer::allow($name)->to('users.create');

        $res = $this->actingAs($user)
            ->get(route('roles.edit', $role->id));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('role.name', $name);
            $page->where('role.title', $title);
            $page->missing('role.created_at_formatted');
            $page->has('abilities'); // marked abilities
        });
    }

    public function test_can_update_role()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';
        $updatedName = 'superadmin';

        $role = Role::create([
            'name' => $name,
            'title' => $title,
        ]);

        // Role table has only 1 record
        $this->assertEquals(1, Role::count());

        // Attache ability into role
        Bouncer::allow($name)->to('old.ability');

        $res = $this->actingAs($user)
            ->get(route('roles.show', $role->id));

        $res->assertInertia(function (Assert $page) {
            $page->has('role.abilities', 1); // with one ability (old.ability)
        });

        // Attach new ability
        Ability::create([
            'title' => 'users.create',
            'name' => 'Create Users',
        ]);

        $res = $this->actingAs($user)
            ->put(route('roles.update', $role->id), [
                'title' => $updatedName,
                'abilities' => [1, 2],
            ]);

        // only 1 record
        $updatedRole = Role::first();
        $this->assertEquals($updatedName, $updatedRole->name);

        $res->assertValid()
            ->assertRedirectToRoute('roles.show', ['role' => $updatedRole->id])
            ->assertSessionHas('successMsg', Role::UPDATE_SUCCESS_MSG);
    }

    public function test_can_delete_a_role()
    {
        $user = User::factory()->create();

        $role = Role::create([
            'title' => 'Admin',
            'name' => 'admin',
        ]);

        $res = $this->actingAs($user)->delete(route('roles.destroy', $role->id));

        $res->assertStatus(200);
    }
}
