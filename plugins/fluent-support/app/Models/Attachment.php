<?php

namespace FluentSupport\App\Models;

class Attachment extends Model
{
    protected $table = 'fs_attachments';

    protected $hidden = ['full_url', 'file_path'];

    protected $appends = ['secureUrl'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
        'person_id',
        'conversation_id',
        'file_type',
        'file_path',
        'full_url',
        'title',
        'driver',
        'file_size',
        'status',
        'settings'
    ];

    public static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $uid = wp_generate_uuid4();
            $model->file_hash = md5($uid . mt_rand(0, 1000));
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
     * Accessor to get dynamic full_name attribute
     * @return string
     */
    public function getSecureUrlAttribute()
    {
        return add_query_arg([
            'fst_file'    => $this->file_hash,
            'secure_sign' => md5($this->id . date('YmdH'))
        ], site_url('/index.php'));
    }
}
