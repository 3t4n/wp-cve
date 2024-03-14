<?php

use  WPHR\HR_MANAGER\Framework\WPHR_Settings_Page ;
/**
 * General class
 */


class WPHR_Settings_widget extends WPHR_Settings_Page
{
    public function __construct()
    {
        $this->id = 'widget';
        $this->label = __( 'Dashboard Widget', 'wphr' );
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
            'title' => __( 'DashBoard Options', 'wphr' ),
            'type'  => 'title',
            'desc'  => '',
            'id'    => 'widget_options',
        ),
            array(
            'title'   => __( 'Birthday', 'wphr' ),
            'id'      => 'birthday_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
             array(
            'title'   => __( 'Work Anniversary', 'wphr' ),
            'id'      => 'work_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
              array(
            'title'   => __( 'In/Out of Office', 'wphr' ),
            'id'      => 'office_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
            
                  array(
            'title' => __( 'All Employee Opt Out', 'wphr' ),
            'type'  => 'title2',
            'desc'  => '',
            'id'    => 'widget_options',
        ),   array(
            'title'   => __( 'Birthday', 'wphr' ),
            'id'      => 'ebirthday_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
                array(
            'title'   => __( ' Work Anniversary', 'wphr' ),
            'id'      => 'ework_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
                  array(
            'title'   => __( 'In/Out of Office', 'wphr' ),
            'id'      => 'inout_id',
            'type'    => 'checkbox',
            'tooltip' => true,
        ),
            
            array(
            'type' => 'sectionend',
            'id'   => 'script_styling_options',
        )
        );
        return apply_filters( 'wphr_settings_widget', $fields );
    }

}
return new WPHR_Settings_widget();
?>
