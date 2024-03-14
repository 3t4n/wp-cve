<?php 
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Leave_Holiday
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Leave_Holiday extends Model {
    protected $table = 'wphr_hr_holiday';
    protected $fillable = [ 'title', 'start', 'end', 'description', 'range_status', 'created_at', 'updated_at', 'location_id' ];
}
