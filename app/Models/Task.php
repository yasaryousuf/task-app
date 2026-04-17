<?php

namespace App\Models;

use App\Enums\Task\Priority;
use App\Enums\Task\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_overdue'];
    protected $fillable = ['title', 'description', 'due_date', 'assigned_to', 'status', 'priority', 'non_compliant'];
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

    /**
     * Check the task is overdue.
     *
     * @return bool
     */
    public function getIsOverdueAttribute()
    {
        return $this->attributes['due_date'] < Carbon::today();
    }

    /**
     * Get tasks assigned to the user.
     */
    public function assigned_to_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
