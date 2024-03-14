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

class General_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([

            'title' => 'GENERAL SETTINGS',
            'class' => 'darkluplite-general-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/general.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/general-white.svg'),
            'id' => 'darkluplite_general_settings',

        ]);

        $this->end_fields_section(); // End fields section

    }

}

new General_Settings_Tab();