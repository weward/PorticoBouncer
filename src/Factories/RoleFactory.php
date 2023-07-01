<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = strtolower($this->faker->lastName);

        $name = $name;
        $title = ucwords($name);

        return [
            'title' => $title,
            'name' => $name,
        ];
    }
}
