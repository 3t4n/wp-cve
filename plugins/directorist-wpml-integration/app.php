<?php

use Directorist_WPML_Integration\Controller;
use Directorist_WPML_Integration\Helper;

final class Directorist_WPML_Integration {

    private static $instance;

    /**
	 * Constuctor
	 * 
     * @return void
	 */
    private function __construct() {

        // Check Compatibility
        if ( version_compare( ATBDP_VERSION, DIRECTORIST_WPML_INTEGRATION_MIN_DIRECTORIST_VERSION_REQUIRMENT, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'show_incompatibility_notice' ], 1, 1 );
            return;
        }

        // Load Textdomain
        add_action('plugins_loaded', [ $this, 'load_textdomain' ] );

        // Register Controllers
        $controllers = $this->get_controllers();
        Helper\Serve::register_services( $controllers );

    }

    /**
	 * Get Instance
	 * 
     * @return Directorist_WPML_Integration
	 */
    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new Directorist_WPML_Integration();
        }

        return self::$instance;
    }

    /**
	 * Get Controllers
	 * 
     * @return array $controllers
	 */
    protected function get_controllers() {
        return [
            Controller\Setup\Init::class,
            Controller\Asset\Init::class,
            Controller\Ajax\Init::class,
            Controller\Hook\Init::class,
        ];
    }

    /**
	 * Show Incompatibility Notice
	 * 
     * @return void
	 */
    public function show_incompatibility_notice() {
        ?>
        <div class="notice notice-error">
            <h3><?php esc_html_e( 'Directorist Update is Incomplete', 'directorist-wpml-integration' ); ?></h3>
            <p><?php echo wp_kses( __( '<b>Directorist WPML Integration</b> extension requires <b>Directorist '. DIRECTORIST_WPML_INTEGRATION_MIN_DIRECTORIST_VERSION_REQUIRMENT .'</b> or higher to work', 'directorist-wpml-integration' ), array( 'b' ) ); ?></p>
        </div>
        <?php
    }

    /**
     * Load Text Domain
     * 
     * @return void
     */
    public function load_textdomain() {
        load_plugin_textdomain( 'directorist-wpml-integration', false, DIRECTORIST_WPML_INTEGRATION_LANGUAGE_DIR );
    }

    /**
	 * Cloning instances of the class is forbidden.
	 * 
     * @return void
	 */
    public function __clone() {
		_doing_it_wrong( __FUNCTION__, __('Cheatin&#8217; huh?', 'directorist-wpml-integration'), '1.0' );
	}

    /**
	 * Unserializing instances of the class is forbidden.
	 * 
     * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __('Cheatin&#8217; huh?', 'directorist-wpml-integration'), '1.0' );
	}

}