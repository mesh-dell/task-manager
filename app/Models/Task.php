<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

/**
 * @property \App\Enums\Status $status
 * @property \App\Enums\Priority $priority
 * @property \Illuminate\Support\Carbon $due_date
 */
class Task extends Model
{
    //
    protected $fillable = [
        'title',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'priority' => Priority::class,
        'status' => Status::class,
    ];
}
