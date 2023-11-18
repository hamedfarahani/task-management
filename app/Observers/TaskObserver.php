<?php

namespace App\Observers;

use App\Enum\TaskEnum;
use App\Models\Task;
use App\Notifications\SendEmailReviewTaskNotification;
use App\Notifications\SendEmailTaskNotification;

class TaskObserver
{
    public function created(Task $task): void
    {
        $user = $task->user;
        $user->notify(new SendEmailTaskNotification());
    }

    public function updating(Task $task)
    {
        if ($task->isDirty('status') && $task->status === TaskEnum::REVIEW && $task->creator_id != null && $task->user->is_admin == false) {
            $admin = $task->admin;
            $admin->notify(new SendEmailReviewTaskNotification());
        }
    }
}
