<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Enum\TaskEnum;
use App\Filament\Resources\TaskResource;
use App\Models\TaskTag;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $data['status'] = TaskEnum::STATUS[$data['status']];
        $data['creator_id'] = auth()->user()->id;
        $tagsId = $data['tag_id'];
        unset($data['tag_id']);
        $task = static::getModel()::create($data);
        $task->tags()->attach($tagsId);

//        foreach ($tagsId as $tag){
//            TaskTag::create([
//                'tag_id' => $tag,
//                'task_id' => $task->id,
//            ]);
//        }

        return $task;
    }
}
