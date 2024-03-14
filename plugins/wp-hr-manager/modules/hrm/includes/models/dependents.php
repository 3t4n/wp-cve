<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Dependents
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Dependents extends Model {
    protected $table = 'wphr_hr_dependents';
    protected $fillable = [ 'employee_id', 'name', 'relation', 'dob' ];
}
