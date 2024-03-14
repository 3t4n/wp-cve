<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

/**
 * HR front end settings class
 *
 * @since  1.0.0
 */
class Install {

	/**
     * Itializes the class
     * Checks for an existing instance
     * and if it doesn't find one, creates it.
     *
     * @since 1.0.0
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Create HR frontend required page
     *
     * @since  1.0.0
     *
     * @return  void
     */
    public static function create_pages() {
        
        $pages = apply_filters( 'wphr_hr_frontend_page', array(
            array(
                'name'    => 'employee-list',
                'title'   => _x( 'Employee List', 'Page title', 'wphr-hr-frontend' ),
                'content' => '[' . apply_filters( 'wp-hr-employee-list', 'wp-hr-employee-list' ) . ']',
                'option'  => 'emp_list'
            ),

            array(
                'name'    => 'employee-profile',
                'title'   => _x( 'Employee profile', 'Page title', 'wphr-hr-frontend' ),
                'content' => '[' . apply_filters( 'wp-hr-employee-profile', 'wp-hr-employee-profile' ) . ']',
                'option'  => 'emp_profile'
            ),

            array(
                'name'    => 'dashboard',
                'title'   => _x( 'Dashboard', 'Page title', 'wphr-hr-frontend' ),
                'content' => '[' . apply_filters( 'wp-hr-dashboard', 'wp-hr-dashboard' ) . ']',
                'option'  => 'hr_dshboard'
            ),
            array(
                'name'    => 'my-profile',
                'title'   => _x( 'My Profile', 'Page title', 'wphr-hr-frontend' ),
                'content' => '[' . apply_filters( 'wp-hr-my-profile', 'wp-hr-my-profile' ) . ']',
                'option'  => 'my_profile'
            ),
        ));

        foreach ( $pages as $key => $page ) {
			
            wphr_hr_frontend_create_page( esc_sql( $page['name'] ), $page['option'], $page['title'], $page['content'], ! empty( $page['parent'] ) ? wc_get_page_id( $page['parent'] ) : '' );
            
        }
    }
}

\WPHR\HR_MANAGER\HR\Frontend\Install::create_pages();
