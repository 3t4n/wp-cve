<?php

namespace Woo_MP\Payment_Gateway;

defined( 'ABSPATH' ) || die;

/**
 * A settings section.
 */
interface Settings_Section {

    /**
     * Get the settings in this section.
     *
     * @return array[] The settings.
     */
    public function get_settings();

}
