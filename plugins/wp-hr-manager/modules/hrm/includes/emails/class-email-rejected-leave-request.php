<?php
namespace WPHR\HR_MANAGER\HRM\Emails;

use WPHR\HR_MANAGER\Email;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Rejected Leave Request
 */
class Rejected_Leave_Request extends Email {

    use Hooker;

    function __construct() {
        $this->id             = 'rejected-leave-request';
        $this->title          = __( 'Rejected Leave Request', 'wphr' );
        $this->description    = __( 'Rejected leave request notification to employee.', 'wphr' );

        $this->subject        = __( 'Your leave request has been rejected', 'wphr');
        $this->heading        = __( 'Leave Request Rejected', 'wphr');

        $this->find = [
            'full-name'    => '{employee_name}',
            'leave_type'   => '{leave_type}',
            'date_from'    => '{date_from}',
            'date_to'      => '{date_to}',
            'no_days'      => '{no_days}',
            'reason'       => '{reject_reason}',
        ];

        remove_all_actions('wphr_admin_field_' . $this->id . '_help_texts');
        $this->action( 'wphr_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    function get_args() {
        return [
            'email_heading' => $this->heading,
            'email_body'    => wpautop( $this->get_option( 'body' ) ),
        ];
    }

    public function trigger( $request_id = null ) {
        $request = wphr_hr_get_leave_request( $request_id );

        if ( ! $request ) {
            return;
        }

        $employee          = new \WPHR\HR_MANAGER\HRM\Employee( intval( $request->user_id ) );

        $this->recipient   = $employee->user_email;
        $this->heading     = $this->get_option( 'heading', $this->heading );
        $this->subject     = $this->get_option( 'subject', $this->subject );

        $format = wphr_get_option( 'date_format', 'wphr_settings_general', 'd-m-Y' );
        $days = $request->days;
        $days = $days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days);
        
        if (wphr_format_date($request->start_date) == wphr_format_date($request->end_date)) {
            $start_time = strtotime($request->start_date);
            $end_time = strtotime($request->end_date);
            $time_difference = $end_time - $start_time;
            $hours = date('G', $time_difference);
            $minutes = date('i', $time_difference);
            //$hours = ( $end_time - $start_time );
            $display_time = '';
            if ($hours < 23) {
                $format = $format.' H:i:s';
                if ($hours > 0) {
                    $display_time .= $hours > 1 ? sprintf(__('%d Hours', 'hrm'), $hours) : sprintf(__('%d Hour', 'hrm'), $hours);
                }
                if ($minutes > 0) {
                    $display_time .= ' ' . sprintf(__('%d Minutes', 'hrm'), $minutes);
                }
                $days = $display_time;
            } else {
                $days = $days > 1 ? sprintf(__('%d Days', 'hrm'), $days) : sprintf(__('%d Day', 'hrm'), $days);
            }
            
        }
        
        $this->replace = [
            'full-name'    => $request->display_name,
            'leave_type'   => $request->policy_name,
            'date_from'    => wphr_format_date( $request->start_date, $format ),
            'date_to'      => wphr_format_date( $request->end_date, $format ),
            'no_days'      => $days,
            'reason'       => $request->comments,
        ];

        if ( ! $this->get_recipient() ) {
            return;
        }

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }
}
