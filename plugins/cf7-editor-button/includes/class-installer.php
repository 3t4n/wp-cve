<?php
namespace Ari_Cf7_Button;

use Ari\App\Installer as Ari_Installer;
use Ari_Cf7_Button\Helpers\Settings as Settings;

class Installer extends Ari_Installer {
    function __construct( $options = array() ) {
        if ( ! isset( $options['installed_version'] ) ) {
            $installed_version = get_option( ARICF7BUTTON_VERSION );

            if ( false !== $installed_version) {
                $options['installed_version'] = $installed_version;
            }
        }

        if ( ! isset( $options['version'] ) ) {
            $options['version'] = ARICF7BUTTON_VERSION;
        }

        parent::__construct( $options );
    }

    public function run() {
        $this->init_settings();

        if ( ! $this->run_versions_updates() ) {
            return false;
        }

        update_option( ARICF7BUTTON_VERSION_OPTION, $this->options->version );

        return true;
    }

    private function init_settings() {
        if ( false !== get_option( ARICF7BUTTON_SETTINGS_NAME ) )
            return ;

        add_option( ARICF7BUTTON_SETTINGS_NAME, Settings::instance()->get_default_options() );
    }
}
