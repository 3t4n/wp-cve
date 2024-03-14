<?php
namespace WPHR\HR_MANAGER\API;

/**
 * API_Registrar class
 */
class API_Registrar {
    /**
     * Constructor
     */
    public function __construct() {
        if ( ! class_exists( 'WP_REST_Server' ) ) {
            return;
        }

        // Init REST API routes.
        add_action( 'rest_api_init', [ $this, 'register_rest_routes' ], 10 );
    }

    /**
     * Register REST API routes.
     *
     * @since 1.2.0
     */
    public function register_rest_routes() {
        $controllers = [];

        if ( wphr_is_module_active( 'hrm' ) ) {
            $controllers = array_merge( $controllers, [
                '\WPHR\HR_MANAGER\API\Departments_Controller',
                '\WPHR\HR_MANAGER\API\Designations_Controller',
                '\WPHR\HR_MANAGER\API\Employees_Controller',
                '\WPHR\HR_MANAGER\API\Announcements_Controller',
                '\WPHR\HR_MANAGER\API\Leave_Policies_Controller',
                '\WPHR\HR_MANAGER\API\Leave_Entitlements_Controller',
                '\WPHR\HR_MANAGER\API\Leave_Holidays_Controller',
                '\WPHR\HR_MANAGER\API\Leave_Requests_Controller',
                '\WPHR\HR_MANAGER\API\HRM_Reports_Controller',
            ] );
        }

        
        $controllers = apply_filters( 'wphr_rest_api_controllers', $controllers );

        foreach ( $controllers as $controller ) {
            $controller = new $controller();
            $controller->register_routes();
        }
    }
}
