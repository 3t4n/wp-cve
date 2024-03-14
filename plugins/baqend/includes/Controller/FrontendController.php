<?php

namespace Baqend\WordPress\Controller;

use Baqend\WordPress\Loader;
use Baqend\WordPress\OptionEnums;

/**
 * Class Frontend created on 17.07.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @author Kevin Twesten
 * @package Baqend\WordPress\Controller
 */
class FrontendController extends Controller {

    public function register( Loader $loader ) {
        $loader->add_action( 'wp_head', [ $this, 'head' ], 1 );
        $loader->add_action( 'admin_head', [ $this, 'head' ], 1 );
    }

    /**
     * Is executed when WordPress is enqueueing scripts for the website.
     */
    public function head() {
        $app_name = $this->plugin->options->get( OptionEnums::APP_NAME );
        if ( empty( $app_name ) ) {
            return;
        }

        $install_resource_url = $this->plugin->options->get( OptionEnums::INSTALL_RESOURCE_URL );
        if ( ! empty( $install_resource_url ) ) {
            echo <<<HTML
<script src="$install_resource_url" async crossorigin="anonymous"></script>
HTML;
            return;
        }

        $config         = $this->plugin->speed_kit_config_builder->build_config();
        $config_content = '(function(){var speedKit=' . $config . ';';
        $snippet_path   = $this->plugin->snippet_path();
        $config_content .= file_get_contents( $snippet_path ) . '})();';

        //Write file to our plugin directory
        file_put_contents( $this->plugin->install_script_path(), $config_content );
        $install_url = $this->plugin->install_script_url();
        echo <<<HTML
<script async type="application/javascript" src="$install_url"></script>
HTML;

        $dynamic_block_config_option = trim( $this->plugin->options->get( OptionEnums::DYNAMIC_BLOCK_CONFIG ) );
        if ( ! empty( $dynamic_block_config_option ) ) {
            $dynamic_fetcher = file_get_contents( $this->plugin->dynamic_fetcher_path() );
            echo <<<HTML
<!-- Baqend Dynamic Fetcher -->
<script type="application/javascript">(function(){{$dynamic_block_config_option}
;{$dynamic_fetcher}})();</script>

HTML;
        }
    }
}
