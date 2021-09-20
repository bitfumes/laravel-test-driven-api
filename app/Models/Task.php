<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    public const STARTED = 'started';
    public const PENDING = 'pending';
    public const NOT_STARTED = 'not_started';

    protected $fillable = ['title', 'todo_list_id', 'status', 'description', 'label_id'];

    public function todo_list(): BelongsTo
    {
        return $this->belongsTo(TodoList::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }
}
