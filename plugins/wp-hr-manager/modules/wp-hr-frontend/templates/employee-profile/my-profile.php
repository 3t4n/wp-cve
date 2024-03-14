<?php
$current_user_id = get_current_user_id();

$employee = new \WPHR\HR_MANAGER\HRM\Employee( $current_user_id );

if ( ! $employee->id ) {
    wp_die( __( 'Employee not found!', 'wphr' ) );
}
wphr_get_js_template( WPHR_HRM_JS_TMPL . '/new-leave-request.php', 'wphr-new-leave-req' );
wphr_get_js_template( WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days' );

$template = WPHR_HRM_VIEWS . '/employee/single.php';

if ( file_exists( $template ) ) {
    include $template;
}
