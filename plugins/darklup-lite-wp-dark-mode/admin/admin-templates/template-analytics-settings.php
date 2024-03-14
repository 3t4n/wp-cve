<?php
namespace DarklupLite\Admin;
 /**
  *
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author
  * @Websites:
  *
  */


class Analytics_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'darkluplite_settings'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {

        $this->start_fields_section([

            'title' => 'USAGE ANALYTICS',
            'class' => 'darkluplite_analytics_settings darkluplite-d-hide darkluplite-settings-content',
            'icon'  => esc_url(  DARKLUPLITE_DIR_URL. 'assets/img/analysis.svg' ),
            'dark_icon'  => esc_url(  DARKLUPLITE_DIR_URL. 'assets/img/analysis-white.svg' ),
            'id' => 'darkluplite_analytics_settings'

        ]);

        $this->switch_field([
          'title'     => esc_html__( 'Enable Analytics?', 'darklup-lite' ),
          'sub_title' => esc_html__( 'Enable/ disable the dark mode usage analytics.', 'darklup-lite' ),
          'name'      => 'darkluplite_show_analytics',
          'is_pro'    => 'yes',
          'wrapper_class'     => 'pro-feature'
      ]);

        $this->switch_field([
          'title'     => esc_html__( 'Dashboard Widget?', 'darklup-lite' ),
          'sub_title' => esc_html__( 'Show/ hide the dark mode usage dashboard chart widget.', 'darklup-lite' ),
          'name'      => 'darkluplite_show_dashboard',
          'is_pro'    => 'yes',
          'wrapper_class'     => 'pro-feature'
      ]);

      $this->select_box([
        'title'     => esc_html__( 'Analytics Duration?', 'darklup-lite' ),
        'sub_title' => esc_html__( 'Select How much percentage of users use dark mode.', 'darklup-lite' ),
        'name'      => 'darkluplite_analytics_duration',
        'is_pro'    => 'yes',
        'wrapper_class'     => 'pro-feature',
        'options' => [
          '7'   => esc_html__('Last 7 Days', 'darklup'),
          '30'  => esc_html__('Last 30 Days', 'darklup'),
      ]
    ]);

        $this->switch_field([
          'title'     => esc_html__( 'Email Reporting?', 'darklup-lite' ),
          'sub_title' => esc_html__( 'Enable/ disable the dark mode usage email reporting.', 'darklup-lite' ),
          'name'      => 'darkluplite_email_reporting',
          'is_pro'    => 'yes',
          'wrapper_class'     => 'pro-feature'
      ]);

        $this->select_box([
          'title'     => esc_html__( 'Reporting Frequency', 'darklup-lite' ),
          'sub_title' => esc_html__( 'Select the reporting frequency, when the email will be send.', 'darklup-lite' ),
          'name'      => 'darkluplite_reporting _frequency',
          'is_pro'    => 'yes',
          'wrapper_class'     => 'pro-feature',
          'options' => [
            '7'   => esc_html__('Weekly', 'darklup'),
            '30'  => esc_html__('Monthly', 'darklup'),
        ]
      ]);

      
        
        $this->end_fields_section(); // End fields section

   }




}

new Analytics_Settings_Tab();