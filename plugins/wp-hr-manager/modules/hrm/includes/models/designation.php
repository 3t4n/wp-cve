<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Role
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Designation extends Model {
    protected $table = 'wphr_hr_designations';
    protected $fillable = [ 'title', 'description', 'status' ];
}
