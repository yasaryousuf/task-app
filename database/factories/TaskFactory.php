<?php

namespace Database\Factories;

use App\Enums\Task\Priority;
use App\Enums\Task\Status;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
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
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'due_date' => fake()->dateTimeBetween('-1 week', '+1 week')->format('Y-m-d'),
            'priority' => fake()->randomElement(array_column(Priority::cases(), 'value')),
            'status' => fake()->randomElement(array_column(Status::cases(), 'value')),
            'assigned_to' => fake()->randomElement(User::pluck('id'))
        ];
    }
}
