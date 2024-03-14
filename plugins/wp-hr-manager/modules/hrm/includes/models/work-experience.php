<?php

namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Work_Experience
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Work_Experience extends Model {
    protected $table = 'wphr_hr_work_exp';
    protected $fillable = [ 'employee_id', 'company_name', 'job_title', 'from', 'to', 'description' ];
}
