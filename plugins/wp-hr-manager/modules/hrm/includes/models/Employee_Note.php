<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Employee_Note
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Employee_Note extends Model {

    protected $primaryKey = 'id';
    protected $table = 'wphr_hr_employee_notes';
    protected $fillable = [ 'user_id', 'comment', 'comment_by','additional' ];

    public function user() {
        return $this->belongsTo('\WPHR\HR_MANAGER\HRM\Models\Hr_User', 'comment_by' );
    }
}