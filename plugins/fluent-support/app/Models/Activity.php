<?php

namespace FluentSupport\App\Models;

use FluentSupport\App\Models\Traits\ActivityTrait;

class Activity extends Model
{
    use ActivityTrait;

    protected $table = 'fs_activities';

    protected $fillable = ['person_id', 'person_type', 'event_type', 'object_id', 'object_type', 'description'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = current_time('mysql');
            $model->updated_at = current_time('mysql');
        });
    }

    public function person()
    {
        $class = __NAMESPACE__ . '\Person';

        return $this->belongsTo(
            $class, 'person_id', 'id'
        );
    }

}
