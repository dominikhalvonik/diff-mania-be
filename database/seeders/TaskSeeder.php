<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some random tasks based on tasks table migration
        $tasks = [
            ['name' => 'Task 1', 'description' => 'Task 1 description', 'requested_amount' => 100, 'booster_id' => 1, 'reward_id' => 1],
            ['name' => 'Task 2', 'description' => 'Task 2 description', 'requested_amount' => 200, 'booster_id' => 2, 'user_attribute_definition_id' => 2, 'reward_id' => 2],
            ['name' => 'Task 3', 'description' => 'Task 3 description', 'requested_amount' => 300, 'user_attribute_definition_id' => 3, 'reward_id' => 3],
            ['name' => 'Task 4', 'description' => 'Task 4 description', 'requested_amount' => 500, 'booster_id' => 1, 'reward_id' => 4],
            ['name' => 'Task 5', 'description' => 'Task 5 description', 'requested_amount' => 400, 'user_attribute_definition_id' => 2, 'reward_id' => 5],
            ['name' => 'Task 6', 'description' => 'Task 6 description', 'requested_amount' => 200, 'booster_id' => 2, 'user_attribute_definition_id' => 3, 'reward_id' => 6],
        ];


        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
