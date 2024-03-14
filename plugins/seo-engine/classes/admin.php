<?php
class Meow_SeoEngineAdmin extends MeowCommon_Admin {

	public $core;

	public function __construct( $core ) {
		$this->core = $core;
		
		parent::__construct( SEOENGINE_PREFIX, SEOENGINE_ENTRY, SEOENGINE_DOMAIN, class_exists( 'MeowPro_SeoEngineCore' ) );
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'app_menu' ) );

			// Load the scripts only if they are needed by the current screen
			$page = isset( $_GET["page"] ) ? sanitize_text_field( $_GET["page"] ) : null;
			$is_seo_engine_screen = in_array( $page, [ 'seoengine_settings', 'seo_engine_dashboard' ] );
			$is_meowapps_dashboard = $page === 'meowapps-main-menu';
			if ( $is_meowapps_dashboard || $is_seo_engine_screen ) {
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}
		}
	}

	function admin_enqueue_scripts() {

		// Load the scripts
		$physical_file = SEOENGINE_PATH . '/app/index.js';
		$cache_buster = file_exists( $physical_file ) ? filemtime( $physical_file ) : SEOENGINE_VERSION;
		wp_register_script( 'seo_engine_seo-vendor', SEOENGINE_URL . 'app/vendor.js',
			['wp-element', 'wp-i18n'], $cache_buster
		);
		wp_register_script( 'seo_engine_seo', SEOENGINE_URL . 'app/index.js',
			['seo_engine_seo-vendor', 'wp-i18n'], $cache_buster
		);
		wp_set_script_translations( 'seo_engine_seo', 'seo-engine' );
		wp_enqueue_script('seo_engine_seo' );

		// Load the fonts
		wp_register_style( 'meow-neko-ui-lato-font', '//fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap');
		wp_enqueue_style( 'meow-neko-ui-lato-font' );

		// Localize and options
		wp_localize_script( 'seo_engine_seo', 'seo_engine_seo', [
			'api_url' => rest_url( 'seo-engine/v1' ),
			'rest_url' => rest_url(),
			'plugin_url' => SEOENGINE_URL,
			'prefix' => SEOENGINE_PREFIX,
			'domain' => SEOENGINE_DOMAIN,
			'is_pro' => class_exists( 'MeowPro_SeoEngineCore' ),
			'is_registered' => !!$this->is_registered(),
			'rest_nonce' => wp_create_nonce( 'wp_rest' ),
			'fabicon_url' => get_site_icon_url(),
			'site_name' => get_bloginfo('name'),
			'options' => $this->core->get_all_options(),
		] );
	}

	function is_registered() {
		return apply_filters( SEOENGINE_PREFIX . '_meowapps_is_registered', false, SEOENGINE_PREFIX );
	}

	function app_menu() {
		add_submenu_page( 'meowapps-main-menu', 'SEO Engine', 'SEO Engine', 'manage_options',
			'seoengine_settings', array( $this, 'admin_settings' ) );
	}

	function admin_settings() {
		echo '<div id="seoengine-admin-settings"></div>';
	}

	
}

?>