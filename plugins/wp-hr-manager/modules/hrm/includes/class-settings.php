<?php
namespace WPHR\HR_MANAGER\HRM;

use WPHR\HR_MANAGER\Framework\WPHR_Settings_Page;

/**
 * Settings class
 */
class Settings extends WPHR_Settings_Page {

    /**
     * [__construct description]
     */
    public function __construct() {
        $this->id            = 'wphr-hr';
        $this->label         = __( 'HR', 'wphr' );
        $this->single_option = true;
        $this->sections      = $this->get_sections();
    }

    /**
     * Get registered tabs
     *
     * @return array
     */
    public function get_sections() {
        $sections = array(
            'workdays' => __( 'Workdays', 'wphr' ),
        );

        return apply_filters( 'wphr_settings_hr_sections', $sections );
    }

    /**
     * Get sections fields
     *
     * @return array
     */
    public function get_section_fields( $section = '' ) {
        $options = array(
            '8' => __( 'Full Day', 'wphr' ),
            '4' => __( 'Half Day', 'wphr' ),
            '0' => __( 'Non-working Day', 'wphr' )
        );

        $week_days = array(
            'mon' => __( 'Monday', 'wphr' ),
            'tue' => __( 'Tuesday', 'wphr' ),
            'wed' => __( 'Wednesday', 'wphr' ),
            'thu' => __( 'Thursday', 'wphr' ),
            'fri' => __( 'Friday', 'wphr' ),
            'sat' => __( 'Saturday', 'wphr' ),
            'sun' => __( 'Sunday', 'wphr' )
        );

        $fields = [];

        $fields['workdays'][] = [
            'title' => __( 'Work Days', 'wphr' ),
            'type'  => 'title',
            'desc'  => __( 'Week day settings for this company.', 'domain' ),
            'id'    => 'general_options'
        ];

        foreach ($week_days as $key => $day) {
            $fields['workdays'][] = [
                'title'   => $day,
                'id'      => $key,
                'type'    => 'select',
                'options' => $options,
            ];
        }

        $fields['workdays'][] = [
            'type'  => 'sectionend',
            'id'    => 'script_styling_options'
        ];

        $fields = apply_filters( 'wphr_settings_hr_section_fields', $fields, $section );

        $section = $section === false ? $fields['workdays'] : $fields[$section];

        return $section;
    }
}

return new Settings();
