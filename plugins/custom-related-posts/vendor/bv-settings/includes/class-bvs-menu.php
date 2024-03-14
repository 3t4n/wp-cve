<?php
/**
 * Handle the settings menu.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    BV_Settings
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */

class BV_Menu {
    private $bvs;

	/**
	 * Store main instance and initialize.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $bvs ) {
        $this->bvs = $bvs;
		$this->init();
	}

	/**
	 * Register actions and filters.
	 *
	 * @since   1.0.0
	 */
	private function init() {
        add_action( 'admin_menu', array( $this, 'add_submenu_page' ), $this->bvs->atts['menu_priority'] );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
    }
    
    /**
	 * Add the settings menu page.
	 *
	 * @since   1.0.0
	 */
	public function add_submenu_page() {
		add_submenu_page( $this->bvs->atts['menu_parent'], $this->bvs->atts['page_title'], $this->bvs->atts['menu_title'], $this->bvs->atts['required_capability'], $this->bvs->atts['page_slug'], array( $this, 'settings_page_template' ) );
	}

    /**
	 * Get the template for the settings page.
	 *
	 * @since    1.0.0
	 */
	public function settings_page_template() {
		wp_localize_script( 'bv-settings', 'bv_settings', array(
			'structure' => array_values( $this->bvs->get_structure( true ) ),
			'settings' => $this->bvs->get_settings_with_defaults(),
			'defaults' => $this->bvs->get_defaults(),
			'required_addons' => $this->bvs->atts['required_addons'],
			'eol' => PHP_EOL,
			'api' => array(
				'endpoint' => get_rest_url( null, 'bv-settings/v1/' . $this->bvs->atts['uid'] ),
				'nonce' => wp_create_nonce( 'wp_rest' ),
			),
		) );

		echo '<div id="bvs-settings" class="wrap">Loading...</div>';
    }

    public function enqueue_admin() {
        $screen = get_current_screen();

        // Only enqueue on settings page.
        if ( $this->bvs->atts['page_slug'] === substr( $screen->id, -1 * strlen( $this->bvs->atts['page_slug'] ) ) ) {
            wp_enqueue_style( 'bv-settings', BVS_URL . 'dist/admin.css', array(), BVS_VERSION, 'all' );
            wp_enqueue_script( 'bv-settings', BVS_URL . 'dist/admin.js', array(), BVS_VERSION, true );
        }
    }
}
