<?php

namespace App\Models;

use App\Enums\Task\Priority;
use App\Enums\Task\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'priority' => Priority::class,
        ];
    }
}
