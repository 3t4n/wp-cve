<?php

namespace FluentSupport\App\Models;

class TicketTag extends Tag
{
    protected static $type = 'ticket_tag';

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->tag_type = static::$type;
            if(empty($model->created_by) && $userId = get_current_user_id()) {
                $model->created_by = $userId;
            }

            $model->slug = static::slugify($model->title);
        });

        static::addGlobalScope(function ($builder) {
            $builder->where('tag_type', static::$type);
        });
    }


    public static function slugify($title)
    {
        $slug = sanitize_title($title, 'ticket-tag', 'display');
        if (TicketTag::where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }
        return $slug;
    }

    public function tickets()
    {
        return $this->belongsToMany(
            __NAMESPACE__.'\Ticket', 'fs_tag_pivot', 'tag_id', 'source_id'
        )->where('source_type', 'ticket_tag');
    }

}
