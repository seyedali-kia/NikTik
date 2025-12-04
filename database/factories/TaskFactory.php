<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_by_id' => 1,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'estimation' => collect([1, 3, 5, 8, 13])->random(),
            'status' => collect(['todo', 'doing', 'done'])->random(),
        ];
    }
}
