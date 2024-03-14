<?php

namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Performance
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Performance extends Model {

    protected $table = 'wphr_hr_employee_performance';

    public $timestamps = false;

    protected $fillable = [ 'employee_id', 'reporting_to', 'job_knowledge', 'work_quality', 'attendance', 'communication', 'dependablity', 'reviewer', 'comments', 'completion_date', 'goal_description', 'employee_assessment', 'supervisor', 'supervisor_assessment', 'type', 'performance_date','additional' ];

    //print_r($fillable);
   // die();

}

