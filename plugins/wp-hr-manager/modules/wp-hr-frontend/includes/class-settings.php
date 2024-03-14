<?php
namespace WPHR\HR_MANAGER\HR\Frontend;

use WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * HR front end settings class
 *
 * @since  1.0.0
 */
class Settings extends WPHR_Settings_Page {

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
     * Class init
     *
     * @since  1.0.0
     */
    function __construct() {
        add_filter( 'wphr_settings_hr_sections', [$this, 'frontend_page_settings'] );
        add_filter( 'wphr_settings_hr_section_fields', [$this, 'settings_field'], 10, 2 );
    }

    /**
     * HR front end settings field
     * 
     * @param  array $fields  
     * @param  string $section 
     *
     * @since  1.0.0
     * 
     * @return array
     */
    function settings_field( $fields, $section ) {
        if ( 'hr-frontend-page' != $section  ) {
            return $fields;
        }

        $fields['hr-frontend-page'][] = [
            'title' => __( 'HR Frontend Page', 'wp-hr-frontend' ),
            'type'  => 'title',
            'desc'  => __( 'Select HR frontend page', 'wp-hr-frontend' ),
            'id'    => 'general_options'
        ];

        $fields['hr-frontend-page'][] = [
            'title'   => __( 'Employee List', 'wp-hr-frontend'),
            'id'      => 'emp_list',
            'type'    => 'select',
            'options' => ['test', 'test'],
        ];

        $fields['hr-frontend-page'][] = [
            'title'   => __( 'Employee Profile', 'wp-hr-frontend'),
            'id'      => 'emp_profile',
            'type'    => 'select',
            'options' => ['test', 'test'],
        ];

        $fields['hr-frontend-page'][] = [
            'title'   => __( 'Dashboard', 'wp-hr-frontend'),
            'id'      => 'hr_dshboard',
            'type'    => 'select',
            'options' => ['test', 'test'],
        ];

        $fields['hr-frontend-page'][] = [
            'type'  => 'sectionend',
            'id'    => 'script_styling_options'
        ];

        return $fields;
    }

    /**
     * Include HR front end settings section
     * 
     * @param  array $sections
     *
     * @since  1.0.0
     * 
     * @return array
     */
    function frontend_page_settings( $sections ) {
        //do not change this key. because its use in crate page function as static value
        $sections['hr-frontend-page'] = __( 'Page', 'wp-hr-frontend' );
        return $sections;
    }
}

//\WPHR\HR_MANAGER\HR\Frontend\Settings::init();
