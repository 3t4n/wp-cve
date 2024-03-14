<?php
/**
 * Display slider on front end.
 *
 * PHP version 7
 *
 * @package  Display_Slider_On_Front_End
 */

/**
 * Display slider on front end.
 *
 * Template Class
 *
 * @package  Display_Slider_On_Front_End
 */
class Display_Slider_On_Front_End {

	/** Create the custom post types */
	public function __construct() {
		$get_selected_pages = get_option( 'mws_select_page' );
		$check_enable = get_option( 'mws_enable' );
		if ( ! empty( $check_enable ) && ! empty( $get_selected_pages ) ) {
			add_shortcode( 'mws_view_popup', array( $this, 'mws_shortocde_for_front_end' ) );
			add_action( 'wp_footer', array( $this, 'mws_add_popup_to_site' ) );
		}
	}

	/** Callback function to view popup */
	public function mws_view_popup_callback() {

		require_once plugin_dir_path( __FILE__ ) . 'slider-html.php';
	}

	/** Shortcode for font end */
	public function mws_shortocde_for_front_end() {
		$html = '';
		if ( $this->mws_is_visible_popup( true ) ) {
			$html = $this->mws_view_popup_callback();
		}
		return $html;
	}

	/** This will add popup to site */
	public function mws_add_popup_to_site() {

		$html = '';
		if ( $this->mws_is_visible_popup() ) {
			$html = $this->mws_view_popup_callback();
		}
		echo esc_html( $html );

	}


	/**
	 * Define template file.
	 *
	 * @param string $is_shortcode Describe what this parameter is.
	 */
	public function mws_is_visible_popup( $is_shortcode = false ) {
		global $post;
		$post_slug = $post->post_name;
		if ( is_front_page() ) {
			$post_slug = 'home';
		}
		$get_selected_pages = get_option( 'mws_select_page' );
		if ( ! empty( $get_selected_pages ) ) {
			if ( $is_shortcode ) {
				if ( ! in_array( $post_slug, $get_selected_pages ) || ! in_array( 'all', $get_selected_pages ) ) {
					return true;
				} else {
					return false;
				}
			} else {
				if ( in_array( $post_slug, $get_selected_pages ) || in_array( 'all', $get_selected_pages ) ) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

}

new Display_Slider_On_Front_End();

