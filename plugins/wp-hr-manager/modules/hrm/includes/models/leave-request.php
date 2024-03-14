<?php
namespace WPHR\HR_MANAGER\HRM\Models;

use WPHR\HR_MANAGER\Framework\Model;

/**
 * Class Leave_request
 *
 * @package WPHR\HR_MANAGER\HRM\Models
 */
class Leave_request extends Model {
    /**
     * Custom created_at field
     *
     * @since 1.2.0
     */
    const CREATED_AT = 'created_on';

    /**
     * Custom updated_at field
     *
     * @since 1.2.0
     */
    const UPDATED_AT = 'updated_on';

    protected $table = 'wphr_hr_leave_requests';
    protected $fillable = [
        'user_id', 'policy_id', 'days', 'start_date',
        'end_date', 'comments', 'reason', 'status'
    ];

    /**
     * Relation to Leave model
     *
     * @since 1.2.0
     *
     * @return object
     */
    public function leave() {
        return $this->hasOne( 'WPHR\HR_MANAGER\HRM\Models\Leave', 'request_id' );
    }

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

    /**
     * Relation to Leave model
     *
     * @since 1.2.0
     *
     * @return object
     */
    public function employee() {
        return $this->belongsTo( 'WPHR\HR_MANAGER\HRM\Models\Employee', 'user_id', 'user_id' );
    }
}
