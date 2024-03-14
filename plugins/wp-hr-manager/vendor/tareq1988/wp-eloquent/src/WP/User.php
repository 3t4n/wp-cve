<?php

namespace WPHR\ORM\WP;


use WPHR\ORM\Eloquent\Model;

class User extends Model
{
    protected $primaryKey = 'ID';
    protected $timestamp = false;

    public function meta()
    {
        return $this->hasMany('WPHR\ORM\WP\UserMeta', 'user_id');
    }
}
