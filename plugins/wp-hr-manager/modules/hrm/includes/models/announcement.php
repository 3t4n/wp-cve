<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Announcement
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Announcement extends Model {
    protected $table    = 'wphr_hr_announcement';
    protected $fillable = [ 'user_id', 'post_id', 'status', 'email_status' ];
    public $timestamps  = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'post_id' => 'integer',
    ];
}
