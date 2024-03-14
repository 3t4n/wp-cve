<?php

namespace FluentSupport\App\Models;

class DataMetric extends Model
{
    protected $table = 'fs_data_metrix';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stat_date',
        'data_type',
        'agent_id',
        'replies',
        'active_tickets',
        'resolved_tickets',
        'unassigned_tickets',
        'close_to_average',
        'new_tickets'
    ];

}
