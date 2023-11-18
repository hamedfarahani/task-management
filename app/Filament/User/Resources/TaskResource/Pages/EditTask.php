<?php

namespace App\Filament\User\Resources\Resources\TaskResource\Resources\TaskResource\Pages;

use App\Enum\TaskEnum;
use App\Filament\User\Resources\TaskResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
