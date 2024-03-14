<?php
/**
 * Handle SocialFlow Plugin update
 *
 * @package SocialFlow
 * @since  2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Replace current site url to entered
 *
 * @package SocialFlow
 * @since 2.7.4
 */
class SF_Module_Replace_Site_Url {
	/**
	 * Slug
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $slug = 'socialflow';
	/**
	 * Field key
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $field_key = 'replace_site_url';
	/**
	 * Replacement
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $replacement = array( 'message', 'content_attributes' );
	/**
	 * Replace url
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $replace_url = '';
	/**
	 * Current url
	 *
	 * @since 0.1
	 * @var string
	 */
	protected $current_url = '';

	/**
	 * PHP5 constructor
	 *
	 * @since 2.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'set_urls' ) );
		add_action( 'toplevel_page_socialflow', array( $this, 'set_error' ), 1 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_filter( 'sf_oauth_post_request_params', array( $this, 'replace_oauth_post_request_params' ) );
	}

	/**
	 * Set Error
	 */
	public function set_error() {
		if ( $this->is_valid_url( $this->replace_url ) ) {
			return;
		}

		$this->add_settings_error( 'error_not_valid_site_url' );
	}

	/**
	 * Set used url
	 *
	 * @since 2.7.4
	 */
	public function set_urls() {
		global $socialflow;
		$this->replace_url = $socialflow->options->get( $this->field_key );
		$this->current_url = get_home_url( get_current_blog_id() );
	}

	/**
	 * Check is empty or valid url
	 *
	 * @since 2.7.4
	 * @param string $url .
	 * @return bool
	 */
	protected function is_valid_url( $url ) {
		if ( empty( $url ) ) {
			return true;
		}

		return ! ! filter_var( $url, FILTER_VALIDATE_URL );
	}

	/**
	 * Replace url on oauth post request
	 *
	 * @since 2.7.4
	 * @param array $params .
	 * @return array
	 */
	public function replace_oauth_post_request_params( array $params ) {
		if ( empty( $this->replace_url ) ) {
			return $params;
		}

		if ( ! $this->is_valid_url( $this->replace_url ) ) {
			return $params;
		}

		$params['message'] = $this->replace_site_url( $params['message'] );
		if ( ! isset( $params['content_attributes'] ) ) {
			return $params;
		}

		$atts = json_decode( $params['content_attributes'], true );
		if ( ! isset( $atts['link'] ) ) {
			return $params;
		}

		$atts['link']                 = $this->replace_site_url( $atts['link'] );
		$params['content_attributes'] = wp_json_encode( $atts );
		return $params;
	}

	/**
	 * Replace url in text
	 *
	 * @since 2.7.4
	 * @param string $text .
	 * @return  string
	 */
	protected function replace_site_url( $text ) {
		$new = $this->replace_url;
		if ( empty( $new ) ) {
			return $text;
		}

		$current = $this->current_url;
		return str_replace( $current, $new, $text );
	}

	/**
	 * This is callback for admin_menu action fired in construct
	 *
	 * @since 2.7.4
	 */
	public function admin_menu() {
		add_settings_field( $this->field_key, esc_attr__( 'Replace site name in post url:', 'socialflow' ), array( $this, 'setting_replace_site_url' ), $this->slug, 'general_settings_section' );
	}

	/**
	 * Setting field html data
	 *
	 * @since 2.7.4
	 */
	public function setting_replace_site_url() {
		global $socialflow;
		?>
			<input id="sf_<?php echo esc_html( $this->field_key ); ?>" type="text" value="<?php echo ( $this->is_valid_url( $this->replace_url ) ) ? esc_html( $socialflow->options->get( $this->field_key ) ) : ''; ?>" name="socialflow[<?php echo esc_html( $this->field_key ); ?>]" size="30" />
			<p class="description"><?php esc_html_e( 'Current url is' ); ?> <code><?php echo esc_html( $this->current_url ); ?></code></p>
		<?php

	}

	/**
	 * Settings error
	 *
	 * @since 2.7.4
	 * @param string $key .
	 */
	protected function add_settings_error( $key ) {
		switch ( $key ) {
			case 'error_not_valid_site_url':
				$message = __( 'Replacement Site Url is not valid.', 'socialflow' );
				break;
		}

		if ( isset( $message ) ) {
			add_settings_error( $this->slug, $key, $message, 'error' );
		}
	}
}
new SF_Module_Replace_Site_Url();
