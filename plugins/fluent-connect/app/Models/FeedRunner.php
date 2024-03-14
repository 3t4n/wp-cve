<?php

namespace FluentConnect\App\Models;

use FluentConnect\App\Models\Model;

class FeedRunner extends Model
{
    protected $table = 'fcon_feed_runners';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feed_id',
        'trigger_id',
        'trigger_data',
        'status',
        'last_action_serial',
        'last_action_id',
        'scheduled_at',
        'runner_hash'
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_at = current_time('mysql');
            $model->updated_at = current_time('mysql');
        });
    }

    public function setSettingsAttribute($settings)
    {
        $this->attributes['settings'] = \maybe_serialize($settings);
    }

    public function getSettingsAttribute($settings)
    {
        return \maybe_unserialize($settings);
    }

    public function setTriggerDataAttribute($settings)
    {
        $this->attributes['trigger_data'] = \maybe_serialize($settings);
    }

    public function getTriggerDataAttribute($settings)
    {
        return \maybe_unserialize($settings);
    }

    /**
     * One2One: Runner Has one Trigger
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function trigger()
    {
        $class = __NAMESPACE__ . '\Trigger';
        return $this->belongsTo(
            $class, 'trigger_id', 'id'
        );
    }

    /**
     * One2One: Runner Has one Feed
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function feed()
    {
        $class = __NAMESPACE__ . '\Feed';
        return $this->belongsTo(
            $class, 'feed_id', 'id'
        );
    }

    public function actionLogs()
    {
        $class = __NAMESPACE__ . '\ActionLog';
        return $this->hasMany(
            $class, 'runner_id', 'id'
        );
    }
}
