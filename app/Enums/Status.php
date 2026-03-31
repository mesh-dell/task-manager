<?php

namespace App\Enums;

enum Status: string
{
    //
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function next(): ?static
    {
        return match ($this) {
            Status::PENDING => Status::IN_PROGRESS,
            Status::IN_PROGRESS => Status::DONE,
            Status::DONE => null,
        };
    }
}
