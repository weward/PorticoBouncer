<?php

namespace Tests\Feature\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Bouncer;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;
use Inertia\Testing\AssertableInertia as Assert;

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

    public function test_can_create_new_role()
    {
        $user = User::factory()->withCompany()->create();

        $name = 'admin';
        $title = 'Admin';

        $res = $this->actingAs($user)
        ->post(
            route('roles.store'), 
            [
                'name' => $name,
                'title' => $title,
            ]
        );
        
        $res->assertJsonPath('name', 'admin');
        $res->assertJsonPath('title', 'Admin');
    }

    public function test_can_render_show_page()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';


        Role::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->get(route('roles.show', 1));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('role.name', $name);
            $page->where('role.title', $title);
            $page->has('role.created_at_formatted');
        });
    }

    public function test_can_render_edit_page()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';

        Role::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->get(route('roles.edit', 1));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('role.name', $name);
            $page->where('role.title', $title);
            $page->missing('role.created_at_formatted');
        });
    }

    public function test_can_update_role()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';
        $updatedName = 'superadmin';

        Role::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->put(route('roles.update', 1), [
                'name' => $updatedName,
                'title' => $title,
            ]);

        $res->assertJsonPath('name', $updatedName);
        $res->assertJsonPath('title', $title);
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
