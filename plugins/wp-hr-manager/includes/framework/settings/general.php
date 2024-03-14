<?php

use  WPHR\HR_MANAGER\Framework\WPHR_Settings_Page ;
/**
 * General class
 */
class WPHR_Settings_General extends WPHR_Settings_Page
{
    public function __construct()
    {
        $this->id = 'general';
        $this->label = __( 'General', 'wphr' );
    }
    
    /**
     * Get settings array
     *
     * @return array
     */
    public function get_settings()
    {
        $fields = array(
            array(
            'title' => __( 'General Options', 'wphr' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'general_options',
        ),
            array(
            'title'   => __( 'Company Start Date', 'wphr' ),
            'id'      => 'gen_com_start',
            'type'    => 'text',
            'desc'    => __( 'The date the company officially started.', 'wphr' ),
            'class'   => 'wphr-date-field',
            'tooltip' => true,
        ),
            array(
            'title'   => __( 'Financial Year Starts', 'wphr' ),
            'id'      => 'gen_financial_month',
            'type'    => 'select',
            'options' => wphr_months_dropdown(),
            'desc'    => __( 'Financial and tax calculation starts from this month of every year.', 'wphr' ),
            'tooltip' => false,
        ),
            array(
            'title'   => __( 'Date Format', 'wphr' ),
            'id'      => 'date_format',
            'desc'    => __( 'Format of date to show accross the system.', 'wphr' ),
            'tooltip' => true,
            'type'    => 'select',
            'options' => [
            'm-d-Y' => 'mm-dd-yyyy',
            'd-m-Y' => 'dd-mm-yyyy',
            'm/d/Y' => 'mm/dd/yyyy',
            'd/m/Y' => 'dd/mm/yyyy',
            'Y-m-d' => 'yyyy-mm-dd',
        ],
        ),
            array(
            'title'   => __( 'Display Employee Leave Publicly?', 'wphr' ),
            'id'      => 'employee_leave_public',
            'desc'    => __( 'All employees can see other employee\'s leave on their dashboard.', 'wphr' ),
            'tooltip' => true,
            'type'    => 'select',
            'options' => [
            '0' => __( 'No', 'wphr' ),
            '1' => __( 'Yes', 'wphr' ),
        ],
        ),
            array(
            'title'   => __( 'Display Only Assigned Leave Publicly To The Employee?', 'wphr' ),
            'id'      => 'employee_assigned_leave_policy',
            'desc'    => __( 'Under the employee’s profile, it can only show the assigned leave policies. The user can\'t see non-related policies.', 'wphr' ),
            'tooltip' => true,
            'type'    => 'select',
            'options' => [
            '1' => __( 'Yes', 'wphr' ),
            '0' => __( 'No', 'wphr' ),
        ],
        ),
            array(
            'title'   => __( 'Line Manager Can See Employee Leaves On Calendar?', 'wphr' ),
            'id'      => 'line_manager_show_leaves',
            'tooltip' => true,
            'type'    => 'select',
            'options' => [
            '1' => __( 'Yes', 'wphr' ),
            '0' => __( 'No', 'wphr' ),
        ],
        ),
            array(
            'title'   => __( 'Currency', 'wphr' ),
            'id'      => 'wphr_currency',
            'type'    => 'select',
            'class'   => 'wphr-select2',
            'options' => wphr_get_currency_list_with_symbol(),
            'default' => 'USD',
        ),
            array(
            'title'   => __( 'Enable Debug Mode', 'wphr' ),
            'id'      => 'wphr_debug_mode',
            'type'    => 'select',
            'options' => [
            1 => __( 'On', 'wphr' ),
            0 => __( 'Off', 'wphr' ),
        ],
            'desc'    => __( 'Switching testing or producting mode', 'wphr' ),
            'tooltip' => true,
            'default' => 0,
        ),
            array(
            'type' => 'sectionend',
            'id'   => 'script_styling_options',
        )
        );
        return apply_filters( 'wphr_settings_general', $fields );
    }

}
return new WPHR_Settings_General();