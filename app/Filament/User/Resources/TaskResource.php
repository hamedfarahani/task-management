<?php

namespace App\Filament\User\Resources;

use App\Enum\TaskEnum;
use App\Filament\User\Resources\Resources;
use App\Filament\User\Resources\Resources\TaskResource\Resources\TaskResource\Pages\EditTask;
use App\Filament\User\Resources\TagResource\Pages\CreateTask;
use App\Filament\User\Resources\TaskResource\Pages\ListTasks;
use App\Models\Tag;
use App\Models\Task;
use Filament\Forms\Components\Radio;
use Filament\Tables\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\In;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $isUpdate = $form->getOperation() === "edit";
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                TextInput::make('title')->required(),
                                TextInput::make('description')->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        TaskEnum::OPEN => TaskEnum::OPEN,
                                        TaskEnum::PENDING => TaskEnum::PENDING,
                                        TaskEnum::PROGRESS => TaskEnum::PROGRESS,
                                        TaskEnum::REVIEW => TaskEnum::REVIEW,
                                    ])
                                    ->required()
                                    ->visible($isUpdate)
                            ])->columns(2),
                        Select::make('tag_id')
                            ->label('Tags')
                            ->options(Tag::latest()->take(10)->pluck('name','id'))
                            ->multiple()
                            ->searchable()
                            ->visible(fn (string $context): bool => $context === 'create'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('title'),
                SelectColumn::make('status')
                    ->label('Change')
                    ->options(TaskEnum::STATUS),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        TaskEnum::OPEN=> 'warning',
                        TaskEnum::PENDING => 'warning',
                        TaskEnum::PROGRESS => 'warning',
                        TaskEnum::REVIEW => 'warning',
                        TaskEnum::ACCEPTED => 'success',
                        TaskEnum::REJECTED => 'danger',
                        default => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        TaskEnum::OPEN => TaskEnum::OPEN,
                        TaskEnum::PENDING => TaskEnum::PENDING,
                        TaskEnum::PROGRESS => TaskEnum::PROGRESS,
                        TaskEnum::REVIEW => TaskEnum::REVIEW,
                        TaskEnum::ACCEPTED => TaskEnum::ACCEPTED,
                        TaskEnum::REJECTED => TaskEnum::REJECTED,
                    ]),
                Filter::make('tag')->form([
                    TextInput::make('tag')->label('Tag Name')
                ])->query(function (Builder $query, array $data): Builder{
                    $tag = $data['tag'];
                    if($tag != null){
                        $result =  $query->whereHas('tags', function (Builder $query) use ($tag){
                            $query->where('name', 'like', "%$tag%");
                        });
                        return $result;
                    }else{
                        return $query;
                    }
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(function (Task $task) { return auth()->user()->id === $task->creator_id;}),
                Tables\Actions\DeleteAction::make()->visible(function (Task $task) { return auth()->user()->id === $task->creator_id;}),
                \Filament\Tables\Actions\Action::make('updateStatus')
                    ->form([
                        Radio::make('status')
                            ->options(function (Task $task) {
                                if(auth()->user()->id === $task->creator_id){
                                    return
                                        [
                                            TaskEnum::OPEN => TaskEnum::OPEN,
                                            TaskEnum::PENDING => TaskEnum::PENDING,
                                            TaskEnum::PROGRESS => TaskEnum::PROGRESS,
                                            TaskEnum::REVIEW => TaskEnum::REVIEW
                                    ];
                                }
                            })
                            ->required()
                            ->default(fn (Task $record) => $record->status),
                    ])
                    ->action(function (array $data, Task $record): void {
                        $record->status = $data['status'];
                        $record->save();
                    })->visible(function (Task $task) {
                        return auth()->user()->id !== $task->creator_id  && auth()->user()->is_admin == false;
                    })

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
