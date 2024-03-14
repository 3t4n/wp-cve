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


class Custom_Css_Settings_Tab extends Settings_Fields_Base {

  public function get_option_name() {
    return 'darkluplite_settings'; // set option name it will be same or different name
  }

   public function tab_setting_fields() {


        $this->start_fields_section([

            'title' => esc_html__( 'CUSTOM CSS SETTINGS', 'darklup-lite' ),
            'class' => 'darkluplite-customcss-settings darkluplite-d-hide darkluplite-settings-content',
            'icon'  => esc_url( DARKLUPLITE_DIR_URL. 'assets/img/css.svg' ),
            'dark_icon'  => esc_url( DARKLUPLITE_DIR_URL. 'assets/img/css-white.svg' ),
            'id'    => 'darkluplite_custom_css_settings'

        ]);

        $this->css_editor_field([
            'title' => esc_html__( 'Custom CSS', 'darklup-lite' ),
            'name' => '',
            'is_pro'  => 'yes',
            'wrapper_class' => 'pro-feature'
        ]);

        $this->end_fields_section(); // End fields section

   }

}

new Custom_Css_Settings_Tab();