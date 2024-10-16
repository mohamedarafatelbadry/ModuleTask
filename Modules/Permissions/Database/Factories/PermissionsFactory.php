<?php

namespace Modules\Permissions\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Permissions\App\Models\Permissions::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

