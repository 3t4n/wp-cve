<?php
namespace WPHR\HR_MANAGER\API;

use WP_REST_Server;
use WP_REST_Response;
use WP_Error;

class HRM_Reports_Controller extends REST_Controller {
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wphr/v1';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = 'hrm/reports';

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {
        register_rest_route( $this->namespace, '/' . $this->rest_base . '/age-profiles', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_age_profiles' ],
                'permission_callback' => function ( $request ) {
                    return current_user_can( 'wphr_hr_manager' );
                },
            ],
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/gender-profiles', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_gender_profiles' ],
                'permission_callback' => function ( $request ) {
                    return current_user_can( 'wphr_hr_manager' );
                },
            ],
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/head-counts', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_head_counts' ],
                'permission_callback' => function ( $request ) {
                    return current_user_can( 'wphr_hr_manager' );
                },
            ],
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/salary-histories', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_salary_histories' ],
                'args'                => $this->get_collection_params(),
                'permission_callback' => function ( $request ) {
                    return current_user_can( 'wphr_hr_manager' );
                },
            ],
        ] );

        register_rest_route( $this->namespace, '/' . $this->rest_base . '/year-of-services', [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_year_of_services' ],
                'args'                => $this->get_collection_params(),
                'permission_callback' => function ( $request ) {
                    return current_user_can( 'wphr_hr_manager' );
                },
            ],
        ] );
    }

    /**
     * Get a collection of age profiles
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_age_profiles( $request ) {
        $args = [
            'number'    => $request['per_page'],
            'offset'    => ( $request['per_page'] * ( $request['page'] - 1 ) ),
            'dimension' => isset( $request['dimension'] ) ? $request['dimension'] : 'general'
        ];

        $employees = new \WPHR\HR_MANAGER\HRM\Models\Employee();

        if ( $args['dimension'] == 'department' ) {
            $departments  = wphr_hr_get_departments();

            $emp_all_data = [];
            foreach ( $departments as $department ) {
                $emp_by_dept = $employees->where( 'department', $department->id )->get();
                $breakdown   = get_employee_breakdown_by_age( $emp_by_dept );

                $emp_all_data[ $department->name ] = $breakdown;
            }
        } else {
            $emp_all      = $employees->get();
            $emp_all_data = get_employee_breakdown_by_age( $emp_all );
        }

        $formated_items = $emp_all_data;

        $response = rest_ensure_response( $formated_items );
        $response = $this->format_collection_response( $response, $request, 0 );

        return $response;
    }

    /**
     * Get a collection of gender profiles
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_gender_profiles( $request ) {
        $args = [
            'number'    => $request['per_page'],
            'offset'    => ( $request['per_page'] * ( $request['page'] - 1 ) ),
            'dimension' => isset( $request['dimension'] ) ? $request['dimension'] : 'general'
        ];

        if ( $args['dimension'] == 'department' ) {
            $departments  = wphr_hr_get_departments();

            $gender_ratio = [];
            foreach ( $departments as $department ) {
                $count_by_dept = wphr_hr_get_gender_count( $department->id );

                $gender_ratio[ $department->name ][] = $count_by_dept;
            }
        } else {
            $gender_ratio = wphr_hr_get_gender_ratio_data();
        }

        $formated_items = $gender_ratio;

        $response = rest_ensure_response( $formated_items );
        $response = $this->format_collection_response( $response, $request, 0 );

        return $response;
    }

    /**
     * Get a collection of head counts
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_head_counts( $request ) {
        $args = [
            'number' => $request['per_page'],
            'offset' => ( $request['per_page'] * ( $request['page'] - 1 ) ),
        ];

        $this_month = current_time( 'Y-m-01' );

        for ( $i = 0; $i <= 11; $i++ ) {
            $month        = date( "Y-m", strtotime( $this_month ." -$i months" ) );
            $js_month     = strtotime( $month. '-01' ) * 1000;
            $count        = wphr_hr_get_headcount( $month, '', 'month' );

            $chart_data[] = (object) [
                'month' => $month,
                'count' => $count
            ];
        }

        $formated_items = $chart_data;

        $response = rest_ensure_response( $formated_items );
        $response = $this->format_collection_response( $response, $request, 0 );

        return $response;
    }

    /**
     * Get a collection of salary histories
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_salary_histories( $request ) {
        $args = [
            'number' => $request['per_page'],
            'offset' => ( $request['per_page'] * ( $request['page'] - 1 ) ),
        ];

        global $wpdb;

        $user_ids    = $wpdb->get_col( $wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}wphr_hr_employees LIMIT %d OFFSET %d", $args['number'], $args['offset'] ));

        $total_items = (int) $wpdb->get_var( "SELECT count(*) FROM {$wpdb->prefix}wphr_hr_employees" );

        $date_format = get_option( 'date_format' );

        $formated_items = [];
        foreach ( $user_ids as $user_id ) {
            $employee      = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user_id ) );
            $compensations = $employee->get_history( 'compensation' );

            $data = [];

            foreach ( $compensations as $compensation ) {
                $data[] = [
                    'employee_id'   => (int) esc_attr( $employee->id ),
                    'employee_name' => $employee->display_name,
                    'date'          => date( $date_format, strtotime( esc_attr( $compensation->date ) ) ),
                    'pay_rate'      => (int) esc_attr( $compensation->type ),
                    'pay_type'      => esc_attr( $compensation->category ),
                ];
            }

            if ( $data ) {
                $formated_items[] = $data;
            }
        }

        $response = rest_ensure_response( $formated_items );
        $response = $this->format_collection_response( $response, $request, $total_items );

        return $response;
    }

    /**
     * Get a collection of year of services
     *
     * @param WP_REST_Request $request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_year_of_services( $request ) {
        $args = [
            'number' => $request['per_page'],
            'offset' => ( $request['per_page'] * ( $request['page'] - 1 ) ),
        ];

        global $wpdb;

        $user_ids = $wpdb->get_col( $wpdb->prepare("SELECT user_id FROM {$wpdb->prefix}wphr_hr_employees WHERE status = 'active' LIMIT %d OFFSET %d", $args['number'], $args['offset'] ));

        $total_items = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}wphr_hr_employees WHERE status = 'active'" );

        $hire_data = [];
        foreach ( $user_ids as $user_id ) {
            $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user_id ) );
            $date     = date_parse_from_format( 'Y-m-d', $employee->hiring_date );
            $month    = $date['month'];
            $day      = $date['day'];

            if ( $month > 0 ) {
                $hire_data[ $month ][ $day ][] = [
                    'employee_id'   => (int) $employee->id,
                    'employee_name' => $employee->display_name,
                    'hiring_date'   => $employee->hiring_date,
                    'job_age'       => date( 'Y', time() ) - date( 'Y', strtotime( $employee->hiring_date ) ),
                ];
            }
        }

        ksort( $hire_data );

        $formated_items = $hire_data;

        $response = rest_ensure_response( $formated_items );
        $response = $this->format_collection_response( $response, $request, $total_items );

        return $response;
    }
}
