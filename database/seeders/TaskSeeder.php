<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\Priority;
use App\Enums\Status;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tasks = [
            [
                'title'    => 'Set up production server',
                'due_date' => '2026-04-01',
                'priority' => Priority::HIGH->value,
                'status'   => Status::DONE->value,
            ],
            [
                'title'    => 'Fix critical login bug',
                'due_date' => '2026-04-01',
                'priority' => Priority::HIGH->value,
                'status'   => Status::IN_PROGRESS->value,
            ],
            [
                'title'    => 'Write API documentation',
                'due_date' => '2026-04-02',
                'priority' => Priority::MEDIUM->value,
                'status'   => Status::PENDING->value,
            ],
            [
                'title'    => 'Review pull requests',
                'due_date' => '2026-04-02',
                'priority' => Priority::MEDIUM->value,
                'status'   => Status::IN_PROGRESS->value,
            ],
            [
                'title'    => 'Update dependencies',
                'due_date' => '2026-04-03',
                'priority' => Priority::LOW->value,
                'status'   => Status::PENDING->value,
            ],
            [
                'title'    => 'Clean up old logs',
                'due_date' => '2026-04-03',
                'priority' => Priority::LOW->value,
                'status'   => Status::DONE->value,
            ],
            [
                'title'    => 'Deploy hotfix to staging',
                'due_date' => '2026-04-04',
                'priority' => Priority::HIGH->value,
                'status'   => Status::PENDING->value,
            ],
            [
                'title'    => 'Database backup verification',
                'due_date' => '2026-04-04',
                'priority' => Priority::MEDIUM->value,
                'status'   => Status::DONE->value,
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
