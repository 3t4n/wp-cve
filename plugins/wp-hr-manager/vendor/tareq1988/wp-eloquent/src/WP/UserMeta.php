<?php

namespace WPHR\ORM\WP;


use WPHR\ORM\Eloquent\Model;

class UserMeta extends Model
{
    protected $primaryKey = 'meta_id';

    public $timestamps    = false;

    public function getTable()
    {
        return $this->getConnection()->db->prefix . 'usermeta';
    }
}
