<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOO_F_LOOKBOOK_Data {
	private $params;

	/**
	 * WOO_F_LOOKBOOK_Data constructor.
	 * Init setting
	 */
	public function __construct() {

		global $wlb_settings;

		if ( ! $wlb_settings ) {
			$wlb_settings = get_option( 'woo_lookbook_params', array() );
		}
		$this->params = $wlb_settings;
		$args         = array(
			'link_redirect'          => 0,
			'external_product'       => 0,
			'background_color'       => '#fff',
			'text_color'             => '#212121',
			'border_radius'          => 0,
			'close_button'           => 0,
			'key'                    => 0,
			/*Node*/
			'icon_background_color'  => '#E8CE40',
			'icon_color'             => '#fff',
			'icon_border_color'      => '#E8CE40',
			'custom_css'             => '',
			'hide_title'             => 0,
			'title_color'            => '#212121',
			'title_background_color' => '#eee',
			/*Quick view*/
			'slide_width'            => 1170,
			'slide_height'           => 600,
			'slide_effect'           => 0,
			'slide_pagination'       => 0,
			'slide_navigation'       => 0,
			'slide_time'             => 5000,
			'slide_auto_play'        => 0,
			'see_more'               => 0,
			/*Instagram*/
			'ins_username'           => '',
			'ins_display'            => 0,
			'ins_items_per_row'      => 4,
			'ins_display_limit'      => 12,
			'ins_link'               => 0,
			'ins_client_id'          => '',
			'ins_client_secret'      => '',
			'ins_access_token'       => '',
			'fb_user_id'             => '',
			'ins_page_id'            => '',
		);
		$this->params = apply_filters( 'wlb_settings_args', wp_parse_args( $this->params, $args ) );
	}

/**
	 * Get Access token of Instagram
	 * @return mixed|void
	 */
	public function get_access_token() {
		return apply_filters( 'wlb_get_access_token', $this->params['ins_access_token'] );
	}
	/**
	 * Check enable instagram link
	 * @return mixed|void
	 */
	public function ins_link() {
		return apply_filters( 'wlb_ins_link', $this->params['ins_link'] );
	}

	/**
	 * Get Display Limit
	 * @return mixed|void
	 */
	public function get_ins_display_limit() {
		return apply_filters( 'wlb_get_ins_display_limit', $this->params['ins_display_limit'] );
	}

	/**
	 * Get Items per row
	 * @return mixed|void
	 */
	public function get_ins_items_per_row() {
		return apply_filters( 'wlb_get_ins_items_per_row', $this->params['ins_items_per_row'] );
	}

	/**
	 * Get Display as default
	 * @return mixed|void
	 */
	public function get_ins_display() {
		return apply_filters( 'wlb_get_ins_display', $this->params['ins_display'] );
	}

	/**
	 * Get Instagram user name
	 * @return mixed|void
	 */
	public function get_ins_username() {
		return apply_filters( 'wlb_get_ins_username', $this->params['ins_username'] );
	}

	/**
	 * Get Title Color
	 * @return mixed|void
	 */
	public function get_title_color() {
		return apply_filters( 'wlb_get_title_color', $this->params['title_color'] );
	}

	/**
	 * Get Title Background Color
	 * @return mixed|void
	 */
	public function get_title_background_color() {
		return apply_filters( 'wlb_get_title_background_color', $this->params['title_background_color'] );
	}

	/**
	 * Check show title
	 * @return mixed|void
	 */
	public function hide_title() {
		return apply_filters( 'wlb_hide_title', $this->params['hide_title'] );
	}

	/**
	 * Check show see more button
	 * @return mixed|void
	 */
	public function see_more() {
		return apply_filters( 'wlb_see_more', $this->params['see_more'] );
	}

	/**
	 * Get Time to next slide
	 * @return mixed|void
	 */
	public function get_slide_time() {
		return apply_filters( 'wlb_get_slide_time', $this->params['slide_time'] );
	}

	/**
	 * Auto play slide
	 * @return mixed|void
	 */
	public function slide_auto_play() {
		return apply_filters( 'wlb_slide_auto_play', $this->params['slide_auto_play'] );
	}

	/**
	 * Show Slide Navigation
	 * @return mixed|void
	 */
	public function slide_navigation() {
		return apply_filters( 'wlb_slide_navigation', $this->params['slide_navigation'] );
	}

	/**
	 * Show Slide Button
	 * @return mixed|void
	 */
	public function slide_pagination() {
		return apply_filters( 'wlb_slide_pagination', $this->params['slide_pagination'] );
	}

	/**
	 * Get Slide Effect
	 * @return mixed|void
	 */
	public function slide_effect() {
		return apply_filters( 'wlb_slide_effect', $this->params['slide_effect'] );
	}

	/**
	 * Get Slide Height
	 * @return mixed|void
	 */
	public function get_slide_height() {
		return apply_filters( 'wlb_get_slide_height', $this->params['slide_height'] );
	}

	/**
	 * Get Slide Width
	 * @return mixed|void
	 */
	public function get_slide_width() {
		return apply_filters( 'wlb_get_slide_width', $this->params['slide_width'] );
	}

	/**
	 * Get custom CSS
	 * @return mixed|void
	 */
	public function get_loading_icon() {
		return apply_filters( 'wlb_loading_icon', $this->params['loading_icon'] );
	}

	/**
	 * Get custom CSS
	 * @return mixed|void
	 */
	public function get_custom_css() {
		return apply_filters( 'wlb_get_custom_css', $this->params['custom_css'] );
	}

	/**
	 * Get Icon border color
	 * @return mixed|void
	 */
	public function get_icon_border_color() {
		return apply_filters( 'wlb_get_icon_border_color', $this->params['icon_border_color'] );
	}


	/**
	 * Get purchased code
	 * @return mixed|void
	 */
	public function get_key() {
		return apply_filters( 'wlb_get_key', $this->params['key'] );
	}

	/**
	 * Check close button
	 * @return mixed|void
	 */
	public function enable_close_button() {
		return apply_filters( 'wlb_enable_close_button', $this->params['close_button'] );
	}

	/**
	 * Get Border radius
	 * @return mixed|void
	 */
	public function get_border_radius() {
		return apply_filters( 'wlb_get_border_radius', $this->params['border_radius'] );
	}

	/**
	 * Get Text Color
	 * @return mixed|void
	 */
	public function get_text_color() {
		return apply_filters( 'wlb_get_text_color', $this->params['text_color'] );
	}

	/**
	 * Get Background color
	 * @return mixed|void
	 */
	public function get_background_color() {
		return apply_filters( 'wlb_get_background_color', $this->params['background_color'] );
	}

	/**
	 * Check working with external product
	 * @return mixed|void
	 */
	public function external_product() {
		return apply_filters( 'wlb_external_product', $this->params['external_product'] );
	}

	/**
	 * Check link redirect
	 * @return mixed|void
	 */
	public function link_redirect() {
		return apply_filters( 'wlb_link_redirect', $this->params['link_redirect'] );
	}

	/**
	 * Get Background Color
	 * @return mixed|void
	 */
	public function get_icon_background_color() {
		return apply_filters( 'wlb_get_icon_background_color', $this->params['icon_background_color'] );
	}

	/**
	 * Get Icon Color
	 * @return mixed|void
	 */
	public function get_icon_color() {
		return apply_filters( 'wlb_get_icon_color', $this->params['icon_color'] );
	}


	public function get_ins_client_id() {
		return $this->params['ins_client_id'];
	}

	public function get_ins_client_secret() {
		return $this->params['ins_client_secret'];
	}

	public function get_fb_page_id() {
		return $this->params['ins_page_id'];
	}

	public function get_gallery_to_slide_option() {
		return $this->params['gallery_to_slide'];
	}

}

new WOO_F_LOOKBOOK_Data();