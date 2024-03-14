<?php
namespace Ari_Cf7_Button\Helpers;

use Ari\Wordpress\Settings_Generic as Settings_Generic;

class Settings extends Settings_Generic {
    protected $settings_name = ARICF7BUTTON_SETTINGS_NAME;

    protected $default_settings = array(
        'order_by' => 'title',

        'order_dir' => 'ASC',

        'load_via_ajax' => true,
    );

    public function sanitize( $input, $defaults = false ) {
        return parent::sanitize( $input, $defaults );
    }
}
