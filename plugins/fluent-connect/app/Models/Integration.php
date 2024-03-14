<?php

namespace FluentConnect\App\Models;

use FluentConnect\App\Models\Model;

class Integration extends Model
{
    protected $table = 'fcon_integrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'provider',
        'status',
        'remote_id',
        'settings',
        'description',
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
}
