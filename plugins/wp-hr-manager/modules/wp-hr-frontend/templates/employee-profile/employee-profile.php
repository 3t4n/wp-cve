<?php
$action = isset( $_GET['action'] ) ? sanitize_text_field($_GET['action']) : 'list';
$id     = isset( $_GET['id'] ) ? intval(sanitize_text_field( $_GET['id'] ) ) : 0;

switch ($action) {
    case 'view':
        $employee = new WPHR\HR_MANAGER\HRM\Employee( $id );
        if ( ! $employee->id ) {
            wp_die( __( 'Employee not found!', 'wp-hr-frontend' ) );
        }
		wphr_get_js_template( WPHR_HRM_JS_TMPL . '/new-leave-request.php', 'wphr-new-leave-req' );
		wphr_get_js_template( WPHR_HRM_JS_TMPL . '/leave-days.php', 'wphr-leave-days' );

        $template = WPHR_HRM_VIEWS . '/employee/single.php';
        break;

    default:
        $template = WPHR_HRM_VIEWS . '/employee.php';
        break;
}

if ( file_exists( $template ) ) {
    include $template;
}
