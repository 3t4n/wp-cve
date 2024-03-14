<?php

namespace FluentConnect\App\Models;

use FluentConnect\App\Models\Model;

class Trigger extends Model
{
    protected $table = 'fcon_feed_triggers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'feed_id',
        'priority',
        'trigger_name',
        'integration_id',
        'trigger_provider',
        'trigger_scope',
        'status',
        'settings',
        'updated_at',
        'created_at',
        'created_by',
    ];

    public static function boot()
    {
        static::creating(function ($model) {
            $model->created_by = get_current_user_id();
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
     * One to One: Trigger has a feed
     * @return \FluentConnect\App\Models\Model Model
     */
    public function feed()
    {
        return $this->belongsTo(
            __NAMESPACE__ . '\Feed', 'feed_id', 'id'
        );
    }

    /**
     * One to One: Trigger has a feed
     * @return \FluentConnect\App\Models\Model Model
     */
    public function integration()
    {
        return $this->belongsTo(
            __NAMESPACE__ . '\Integration', 'integration_id', 'id'
        );
    }
}
