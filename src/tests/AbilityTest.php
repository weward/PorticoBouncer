<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Bouncer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Silber\Bouncer\Database\Ability;
use Tests\TestCase;

class AbilityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_can_render_ability_index_page(): void
    {
        $user = User::factory()->create();
        $res = $this->actingAs($user)->get(route('abilities.index'));

        $res->assertStatus(200);
    }

    public function test_list_all_abilities(): void
    {
        $count = 2;
        $user = User::factory()->withCompany()->create();
        Bouncer::assign('admin')->to($user);
        Bouncer::allow('admin')->to('edit.users');
        Bouncer::allow($user)->to('show.users');

        $res = $this->actingAs($user)->get(route('abilities.index'));

        $res->assertInertia(function (Assert $page) use ($count) {
            $page->has('abilities.data', $count);
        });
    }

    public function test_can_render_ability_create_page()
    {
        $user = User::factory()->create();

        $res = $this->actingAs($user)->get(route('abilities.create'));

        $res->assertStatus(200);
    }

    public function test_can_create_new_ability()
    {
        $user = User::factory()->withCompany()->create();

        $name = 'admin';
        $title = 'Admin';

        $res = $this->actingAs($user)
            ->post(
                route('abilities.store'),
                [
                    'name' => $name,
                    'title' => $title,
                ]
            );

        $res->assertJsonPath('name', 'admin');
        $res->assertJsonPath('title', 'Admin');
    }

    public function test_can_render_ability_show_page()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';

        Ability::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->get(route('abilities.show', 1));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('ability.name', $name);
            $page->where('ability.title', $title);
            $page->has('ability.created_at_formatted');
        });
    }

    public function test_can_render_ability_edit_page()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';

        Ability::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->get(route('abilities.edit', 1));

        $res->assertInertia(function (Assert $page) use ($name, $title) {
            $page->where('ability.name', $name);
            $page->where('ability.title', $title);
            $page->missing('ability.created_at_formatted');
        });
    }

    public function test_can_update_ability()
    {
        $user = User::factory()->create();
        $name = 'admin';
        $title = 'Admin';
        $updatedName = 'superadmin';

        Ability::create([
            'id' => 1,
            'name' => $name,
            'title' => $title,
        ]);

        $res = $this->actingAs($user)
            ->put(route('abilities.update', 1), [
                'name' => $updatedName,
                'title' => $title,
            ]);

        $res->assertJsonPath('name', $updatedName);
        $res->assertJsonPath('title', $title);
    }

    public function test_can_delete_an_ability()
    {
        $user = User::factory()->create();

        $role = Ability::create([
            'title' => 'Admin',
            'name' => 'admin',
        ]);

        $res = $this->actingAs($user)->delete(route('abilities.destroy', $role->id));

        $res->assertStatus(200);
    }
}
