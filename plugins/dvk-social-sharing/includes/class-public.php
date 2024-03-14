<?php

class DVKSS_Public {

	private $plugin_file;

	/**
	 * Constructor
	 *
	 * @param $plugin_file
	*/
	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
	}

	public function hook() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ), 99 );
		add_filter( 'the_content', array( $this, 'add_links' ) );
		add_shortcode( 'dvk_social_sharing', 'dvk_social_sharing' );
	}

	/**
	* Load plugin stylesheets and scripts
	*/
	public function load_assets() {
		$opts = dvkss_get_options();

		if( $opts['load_icon_css'] ) {
			wp_enqueue_style( 'dvk-social-sharing', plugins_url( '/assets/css/styles.min.css', $this->plugin_file ), array(), DVKSS_VERSION );
		}

		if( $opts['load_popup_js'] ) {
			wp_enqueue_script( 'dvk-social-sharing', plugins_url( '/assets/js/script.min.js', $this->plugin_file ), array(), DVKSS_VERSION, true );
		}
	}

	/**
	 * Automatically adds links to post content
	 *
	 * @param string $content
	 * @return string
	 */
	public function add_links( $content ) {
		$opts = dvkss_get_options();
		$show_buttons = false;

		if( ! empty( $opts['auto_add_post_types'] ) && in_array( get_post_type(), $opts['auto_add_post_types'] ) && is_singular( $opts['auto_add_post_types'] ) ) {
			$show_buttons = true;
		}

		// allow custom conditionals
		$show_buttons = apply_filters( 'dvkss_display', $show_buttons );

		if( ! $show_buttons ) {
			return $content;
		}

		// add buttons to content
		return $content . dvk_social_sharing();
	}
}
