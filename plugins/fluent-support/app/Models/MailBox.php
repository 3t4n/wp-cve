<?php

namespace FluentSupport\App\Models;

use Exception;
use FluentSupport\App\Services\EmailNotification\Settings;
use FluentSupport\Framework\Support\Arr;

class MailBox extends Model
{
    protected $table = 'fs_mail_boxes';

    protected $appends = ['settings'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'box_type',
        'mapped_email',
        'email_footer',
        'settings',
        'avatar',
        'created_by',
        'is_default'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->slug = static::slugify($model->name);
            $model->settings = $model->settings ?: [
                'admin_email_address' => ''
            ];
        });
    }

    public function setSettingsAttribute($settings)
    {
        $this->attributes['settings'] = \maybe_serialize($settings);
    }

    public function getSettingsAttribute($value)
    {
        if (array_key_exists('settings', $this->attributes)) {
            return \maybe_unserialize($this->attributes['settings']);
        }

        return [];
    }

    public static function slugify($title)
    {
        $slug = sanitize_title($title, 'business', 'display');
        if (MailBox::where('slug', $slug)->first()) {
            $slug .= '-' . time();
        }
        return $slug;
    }

    public function getMailerHeader()
    {
        $headers = [];
        $fromString = $this->email; //$this->name;
        if($this->name) {
            $fromString = $this->name.' <'.$fromString.'>';
        }

        if ($fromString) {
            $headers[] = 'From: '. $fromString;
        }

        // Set Reply-To Header
        $headers[] = 'Reply-To: '. $fromString;
        $headers[] = 'In-Reply-To: '. $fromString;

        return $headers;
    }

    public function getMeta($key, $default = '')
    {
        $class = __NAMESPACE__ . '\MailBox';

        $meta = Meta::where('object_type', $class)
            ->where('object_id', $this->id)
            ->where('key', $key)
            ->first();

        if($meta) {
            return maybe_unserialize($meta->value);
        }

        return $default;
    }

    public function saveMeta($key, $value)
    {
        $class = __NAMESPACE__ . '\MailBox';

        $meta = Meta::where('object_type', $class)
            ->where('object_id', $this->id)
            ->where('key', $key)
            ->first();

        if($meta) {
            $meta->value = maybe_serialize($value);
            $meta->save();
            return true;
        }

        Meta::insert([
            'object_type' => $class,
            'object_id' => $this->id,
            'key' => $key,
            'value' => maybe_serialize($value)
        ]);
        return true;
    }

    public function deleteMeta($key)
    {
        $class = __NAMESPACE__ . '\MailBox';

        $meta = Meta::where('object_type', $class)
            ->where('object_id', $this->id)
            ->where('key', $key)
            ->delete();
    }

    public function deleteAllMeta()
    {
        $class = __NAMESPACE__ . '\MailBox';

        $meta = Meta::where('object_type', $class)
            ->where('object_id', $this->id)
            ->delete();
    }
    /**
     * This `getMailBox` method is used to get a mailbox by id.
     * @param int $id
     * @return MailBox
     */
    public function getMailBox ($id )
    {
        return MailBox::findOrFail($id);
    }

    /**
     * This `createMailBox` method is used to create a new MailBox.
     * @param array $data
     * @return MailBox
     */
    public function createMailBox ( $data )
    {
        if ($data['box_type'] == 'email') {
            $data['settings'] = [
                'admin_email_address' => ''
            ];
        } else {
            $data['settings'] = [
                'admin_email_address' => $data['email']
            ];
        }

        if (!MailBox::first()) {
            $data['is_default'] = 'yes';
        }

        return MailBox::create($data);
    }


    /**
     * This `updateMailBox` method is used to update a mailbox.
     * @param array $data
     * @param int $mailBoxId
     * @return object $mailbox
     */
    public function updateMailBox ($data, $mailBoxId )
    {

        $mailbox = MailBox::findOrFail($mailBoxId);

        if ($data['box_type'] == 'email' && empty($data['mapped_email'])) {
            throw new \Exception('Mapped Email Address is required');
        }

        $mailbox->fill($data);
        $mailbox->save();

        return $mailbox;
    }
}
