<?php

namespace App\Filament\User\Resources\TagResource\Pages;

use App\Enum\TaskEnum;
use App\Filament\User\Resources\TaskResource;
use App\Models\TaskTag;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['status'] = TaskEnum::OPEN;
        $userId = auth()->user()->id;
        $data['user_id'] = $userId;
        $data['creator_id'] = $userId;
        $tagsId = $data['tag_id'];
        unset($data['tag_id']);
        $task = static::getModel()::create($data);
        $task->tags()->attach($tagsId);

        return $task;
    }
}
