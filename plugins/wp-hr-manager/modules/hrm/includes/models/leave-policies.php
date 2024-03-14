<?php

namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Leave_Policies
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Leave_Policies extends Model {
    protected $table = 'wphr_hr_leave_policies';
    protected $fillable = [
        'name', 'value', 'color', 'department', 'designation', 'gender',
        'marital', 'description', 'location', 'effective_date', 'activate', 'execute_day'
    ];

    /**
     * Relation to Leave_Entitlement model
     *
     * @since 1.2.0
     *
     * @return object
     */
    public function entitlements() {
        return $this->hasMany( 'WPHR\HR_MANAGER\HRM\Models\Leave_Entitlement', 'policy_id' );
    }

    /**
     * Relation to Leave_request model
     *
     * @since 1.2.0
     *
     * @return object
     */
    public function leave_requests() {
        return $this->hasMany( 'WPHR\HR_MANAGER\HRM\Models\Leave_request', 'policy_id' );
    }
}
