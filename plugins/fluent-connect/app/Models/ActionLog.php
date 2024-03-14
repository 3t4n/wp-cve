<?php

namespace FluentConnect\App\Models;

use FluentConnect\App\Models\Model;

class ActionLog extends Model
{
    protected $table = 'fcon_action_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'feed_id',
        'action_id',
        'runner_id',
        'remote_action_id',
        'reference_url',
        'status',
        'description',
        'settings',
        'scheduled_at',
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

    /**
     * One2One: Runner Has one Action
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function action()
    {
        $class = __NAMESPACE__ . '\Action';
        return $this->belongsTo(
            $class, 'action_id', 'id'
        );
    }

    /**
     * One2One: Runner Has one Runner
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function runner()
    {
        $class = __NAMESPACE__ . '\FeedRunner';
        return $this->belongsTo(
            $class, 'runner_id', 'id'
        );
    }

    /**
     * One2One: Runner Has one Feed
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function feed()
    {
        $class = __NAMESPACE__ . '\Action';
        return $this->belongsTo(
            $class, 'feed_id', 'id'
        );
    }

}
