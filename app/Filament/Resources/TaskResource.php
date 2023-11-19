<?php

namespace App\Filament\Resources;

use App\Enum\TaskEnum;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;

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
                                        TaskEnum::ACCEPTED => TaskEnum::ACCEPTED,
                                        TaskEnum::REJECTED => TaskEnum::REJECTED,
                                    ])
                                    ->required()
                                    ->visible($isUpdate),
                                Forms\Components\Select::make('user_id')
                                    ->label('User')
                                    ->options(User::take(10)->pluck('name', 'id')->toArray())
                                    ->required(),
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
                Tables\Columns\TextColumn::make('description'),
                TextColumn::make('status')
                    ->searchable()
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
                Filter::make('user')->form([
                    TextInput::make('email')->label('User Email')
                ])->query(function (Builder $query, array $data): Builder{
                    $email = $data['email'];
                    $result =  $query->whereHas('user', function (Builder $query) use ($email){
                        $query->where('email', 'like', "%$email%");
                    });
                    return $result;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
