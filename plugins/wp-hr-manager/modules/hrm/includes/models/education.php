<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Education
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Education extends Model {
    protected $table = 'wphr_hr_education';
    protected $fillable = [ 'employee_id', 'school', 'degree', 'field', 'finished', 'notes', 'interest' ];
}
