<?php

namespace FluentConnect\App\Models;

use FluentConnect\App\Models\Model;

class Feed extends Model
{
    protected $table = 'fcon_connector_feeds';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
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
     * One2Many: Feed has to many actions
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function triggers()
    {
        $class = __NAMESPACE__ . '\Trigger';
        return $this->hasMany(
            $class, 'feed_id', 'id'
        );
    }

    /**
     * One2Many: Feed has to many actions
     * @return \FluentConnect\App\Models\Model Collection
     */
    public function actions()
    {
        $class = __NAMESPACE__ . '\Action';
        return $this->hasMany(
            $class, 'feed_id', 'id'
        );
    }
}
