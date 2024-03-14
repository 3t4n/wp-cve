<?php
namespace WPHR\HR_MANAGER\HRM;

use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * HR Email handler class
 */
class Emailer {

    use Hooker;

    function __construct() {
        $this->filter( 'wphr_email_classes', 'register_emails' );
    }

    function register_emails( $emails ) {

        $emails['New_Employee_Welcome']   = new Emails\New_Employee_Welcome();
        $emails['New_Leave_Request']      = new Emails\New_Leave_Request();
        $emails['Approved_Leave_Request'] = new Emails\Approved_Leave_Request();
        $emails['Rejected_Leave_Request'] = new Emails\Rejected_Leave_Request();

        return apply_filters( 'wphr_hr_email_classes', $emails );
    }
}
