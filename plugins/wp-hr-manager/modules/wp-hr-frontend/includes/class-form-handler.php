<?php
namespace WPHR\HR_MANAGER\HRM\Frontend;

use WPHR\HR_MANAGER\HRM\Form_Handler;

/**
 * Handle the form submissions from the frontend
 *
 * @since 1.0.0
 */
class Frontend_Form_Handler extends Form_Handler {

    /**
     * Class constructor
     *
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'template_redirect', 'wphr_process_actions' );
        add_action( 'template_redirect', array( $this, 'handle_employee_status_update' ) );
    }
}

if ( ! is_admin() ) {
    new Frontend_Form_Handler();
}
