<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Leave
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Leave extends Model {
    protected $table = 'wphr_hr_leaves';
    protected $fillable = [ 'request_id', 'date', 'length_hours', 'length_days', 'start_time', 'end_time', 'duration_type'];
}
