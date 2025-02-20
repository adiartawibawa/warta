<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasUuids;

    protected $table = 'issues';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assignee_id',
        'reporter_id'
    ];

    protected $dates = [
        'closed_at',
        'due_date',
        'started_at',
        'resolved_at',
    ];

    /**
     * Get the reporter of the issue.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Get the assignee of the issue.
     */
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    /**
     * Get the media for the issue.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the vote for the issue.
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }
}
