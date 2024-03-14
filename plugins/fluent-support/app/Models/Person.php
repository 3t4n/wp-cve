<?php

namespace FluentSupport\App\Models;

use FluentSupport\Framework\Database\Orm\ScopeInterface;

class Person extends Model
{
    protected $table = 'fs_persons';

    protected $appends = ['full_name', 'photo'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'title',
        'person_type',
        'user_id',
        'remote_uid',
        'hash',
        'last_response_at',
        'status',
        'ip_address',
        'last_ip_address',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip',
        'country',
        'note'
    ];


    /**
     * $searchable Columns in table to search
     * @var array
     */
    protected $searchable = [
        'first_name',
        'last_name',
        'email'
    ];

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param string $search
     * @return ModelQueryBuilder
     */
    public function scopeSearchBy($query, $search = '')
    {
        if (!$search) {
            return $query;
        }

        $fields = $this->searchable;
        $query->where(function ($query) use ($fields, $search) {
            $query->where(array_shift($fields), 'LIKE', "%$search%");

            $nameArray = explode(' ', $search);
            if (count($nameArray) >= 2) {
                $query->orWhere(function ($q) use ($nameArray) {
                    $fname = array_shift($nameArray);
                    $lastName = implode(' ', $nameArray);
                    $q->where('first_name', 'LIKE', "%$fname%");
                    $q->where('last_name', 'LIKE', "%$lastName%");
                });
            }

            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', "%$search%");
            }
        });

        return $query;
    }

    /**
     * Local scope to filter subscribers by search/query string
     * @param ModelQueryBuilder $query
     * @param array $statuses
     * @return ModelQueryBuilder
     */
    public function scopeFilterByStatues($query, $statuses)
    {
        if ($statuses) {
            $query->whereIn('status', $statuses);
        }

        return $query;
    }


    /**
     * Accessor to get dynamic photo attribute
     * @return string
     */
    public function getPhotoAttribute()
    {
        if (!empty($this->attributes['avatar'])) {
            return $this->attributes['avatar'];
        }

        $email = '';
        if (isset($this->attributes['email'])) {
            $email = trim($this->attributes['email']);
        }

        $hash = md5(strtolower($email));

        /*
         * Filter person profile avatar, by default it use gravatar profile picture
         *
         * @since v1.0.0
         * @param string $url  link to the profile picture
         * @pram string $email user gravatar email address
         */
        /*return apply_filters(
            'fluent_support/get_avatar',
            "https://www.gravatar.com/avatar/${hash}?s=128",
            $email
        );*/
        $defaultAvatar = get_option('avatar_default');
        if('gravatar_default' === $defaultAvatar){
            return apply_filters(
                'fluent_support/get_avatar',
                "https://www.gravatar.com/avatar/${hash}?s=128",
                $email
            );
        }else{
            return apply_filters(
                'fluent_support/get_avatar',
                get_avatar_url('', array('default' => $defaultAvatar)),
                $email
            );
        }
    }

    /**
     * Accessor to get dynamic full_name attribute
     * @return string
     */
    public function getFullNameAttribute()
    {
        $fname = isset($this->attributes['first_name']) ? $this->attributes['first_name'] : '';
        $lname = isset($this->attributes['last_name']) ? $this->attributes['last_name'] : '';
        return trim("{$fname} {$lname}");
    }

    public static function explodeFullName($record)
    {
        if (!empty($record['first_name']) || !empty($record['last_name'])) {
            return $record;
        }
        if (!empty($record['full_name'])) {
            $fullNameArray = explode(' ', $record['full_name']);
            $record['first_name'] = array_shift($fullNameArray);
            if ($fullNameArray) {
                $record['last_name'] = implode(' ', $fullNameArray);
            }
            unset($record['full_name']);
        }

        return $record;
    }

    public function getUserProfileEditUrl()
    {
        $userEditUrl = '';
        if ($this->user_id) {
            $userEditUrl = get_edit_user_link($this->user_id);
        }

        /*
         * Filter person profile edit url
         *
         * @since v1.0.0
         * @param string $userEditUrl User profile edit link
         * @param object $this        Model object
         */
        return apply_filters('fluent_support/person_user_edit_url', $userEditUrl, $this);
    }

    public function getMeta($metaKey, $default = '')
    {
        $meta = Meta::where('object_id', $this->id)
            ->where('object_type', 'person_meta')
            ->where('key', $metaKey)
            ->first();
        if ($meta) {
            $value = maybe_unserialize($meta->value);
            if ($value) {
                return $value;
            }
        }

        return $default;
    }

    public function updateMeta($metaKey, $metaValue)
    {
        $meta = Meta::where('object_id', $this->id)
            ->where('object_type', 'person_meta')
            ->where('key', $metaKey)
            ->first();
        if ($meta) {
            $meta->value = maybe_serialize($metaValue);
            $meta->update();
        }

        if (!$meta){
            Meta::create([
                'object_type' => 'person_meta',
                'object_id'   => $this->id,
                'key'         => $metaKey,
                'value'       => maybe_serialize($metaValue)
            ]);
        }

        return true;
    }

    public function deleteAllMeta()
    {
        Meta::where('object_id', $this->id)
            ->where('object_type', 'person_meta')
            ->delete();
    }

    public function restoreAvatar($person, $id){
        $person->where('id', $id)->update([
            'avatar' => null
        ]);

        return $person;
    }
}
