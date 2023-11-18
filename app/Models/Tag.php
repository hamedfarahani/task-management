<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Task::class,'task_tags','task_tags')->using(TaskTag::class);
    }
}
