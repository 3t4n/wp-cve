<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\ORM\WP\User;
use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Hr_User
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Hr_User extends User {

    // protected $table = 'wp_users';
    public $timestamps = false;

    public function notes() {
        return $this->hasMany( 'WPHR\HR_MANAGER\HRM\Models\Employee_Note', 'user_id' )->orderBy( 'created_at', 'desc');
    }

    public function getTable() {
        return $this->getConnection()->db->prefix . 'users';
    }

}
