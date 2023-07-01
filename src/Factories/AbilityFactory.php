<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Ability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AbilityFactory extends Factory
{
    protected $model = Ability::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prefix = strtolower($this->faker->title);
        $lastName = strtolower($this->faker->lastName);

        $name = "{$prefix}{$lastName}"; // prefix.name
        $title = str_replace('.', ' ', ucwords("{$name}"));

        return [
            'title' => $title,
            'name' => $name,
        ];
    }
}
