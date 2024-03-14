<?php
namespace WPHR\HR_MANAGER\Admin\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Audit_Log
 *
 * @package WPHR\HR_MANAGER\Admin\Models
 */
class Audit_Log extends Model {
    protected $table = 'wphr_audit_log';
    protected $fillable = [ 'component', 'sub_component', 'data_id', 'old_value', 'new_value', 'message', 'changetype', 'created_by', 'created_at' ];
    public $timestamps = false;
}
