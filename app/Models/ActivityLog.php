<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'activity',
    ];

    /**
     * Log an activity.
     *
     * @param string $activity
     * @return void
     */
    public function log($activity)
    {
        $this->create([
            'activity' => $activity
        ]);
    }
}
