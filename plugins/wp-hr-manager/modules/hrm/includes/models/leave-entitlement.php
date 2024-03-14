<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Leave_Entitlement
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Leave_Entitlement extends Model {
    protected $table = 'wphr_hr_leave_entitlements';
    protected $fillable = [
        'user_id', 'policy_id', 'days', 'from_date',
        'to_date', 'comments', 'status', 'created_by', 'created_on'
    ];

    /**
     * Relation to Leave_Policies model
     *
     * @since 1.2.0
     *
     * @return object
     */
    public function policy() {
        return $this->belongsTo( 'WPHR\HR_MANAGER\HRM\Models\Leave_Policies', 'policy_id' );
    }
}
