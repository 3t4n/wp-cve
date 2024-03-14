<?php

/**
 * Plugin Name: OT Zalo
 * Description: Zalo chat for WordPress
 * Plugin URI: https://ninewp.com/plugins/ot-zalo
 * Author: Thinhbg59
 * Author URI: https://ninewp.com
 * Version: 1.1.0
 *
 * @since 1.0.0
 */

define( 'OT_ZALO_VERSION', '1.1.0' );
define( 'OT_ZALO_DIR', plugin_dir_path( __FILE__ ) );
define( 'OT_ZALO_URI', plugins_url( '/', __FILE__ ) );

class OT_Zalo {

	/**
	 * OT_Zalo constructor.
	 */
	public function __construct() {
		$this->includes();
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_footer', array( $this, 'zalo_widget_chat' ) );
		add_action( 'widgets_init', array( $this, 'zalo_widget_follow' ) );
		add_filter( 'the_content', array( $this, 'zalo_button_share_content' ) );
		add_shortcode( 'zalo_share', array( $this, 'zalo_button_share_shortcode' ) );
	}

	public function includes() {
		include( OT_ZALO_DIR . 'includes/functions.php' );
		include( OT_ZALO_DIR . 'includes/class-ot-zalo-settings.php' );
		include( OT_ZALO_DIR . 'includes/class-ot-zalo-widget.php' );
	}

	public function scripts() {
		wp_enqueue_script( 'zalo-sdk', 'https://sp.zalo.me/plugins/sdk.js', array(), false, true );
	}

	public function zalo_widget_chat() {
		$zalo_oaid        = ot_zalo_get_option( 'zalo_oaid' );
		$zalo_chat_enable = ot_zalo_get_option( 'zalo_chat_enable' );
		$zalo_wm          = ot_zalo_get_option( 'zalo_wm', 'Rất vui khi được hỗ trợ bạn!' );

		if ( empty( $zalo_oaid ) ) {
			return;
		}

		if ( 'on' == $zalo_chat_enable ) {
			echo '<div class="zalo-chat-widget" data-oaid="' . $zalo_oaid . '" data-welcome-message="' . $zalo_wm . '" data-autopopup="1"></div>';
		}
	}

	function zalo_widget_follow() {
		register_widget( 'OT_Zalo_Widget' );
	}

	public function zalo_button_share_html( $url = '' ) {

		$url = ! empty( $url ) ? urlencode( $url ) : urlencode( get_the_permalink() );

		$zalo_oaid = ot_zalo_get_option( 'zalo_oaid' );

		$share_layout = ot_zalo_get_option( 'share_layout', 1 );
		$share_color  = ot_zalo_get_option( 'share_color', 'blue' );

		ob_start();

		?>
		<div class="zalo-share-button" data-href="<?php echo $url; ?>" data-oaid="<?php echo $zalo_oaid; ?>"
		     data-layout="<?php echo $share_layout; ?>" data-color="<?php echo $share_color; ?>"
		     data-customize=false></div>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	public function zalo_button_share_content( $content ) {

		$zalo_share_enable = ot_zalo_get_option( 'zalo_share_enable' );

		if ( 'on' != $zalo_share_enable ) {
			return $content;
		}

		$show_share = false;

		$zalo_share_enable_post_type = ot_zalo_get_option( 'zalo_share_enable_post_type' );

		if ( ! empty( $zalo_share_enable_post_type ) && in_array( get_post_type(), $zalo_share_enable_post_type ) && is_singular( $zalo_share_enable_post_type ) ) {
			$show_share = true;
		}

		if ( ! $show_share ) {
			return $content;
		}

		$share_position = ot_zalo_get_option( 'share_position', 'before' );

		$button = $this->zalo_button_share_html();

		if ( 'before' == $share_position ) {
			return $button . $content;
		} else {
			return $content . $button;
		}
	}

	public function zalo_button_share_shortcode( $atts, $content = null ) {

		extract( shortcode_atts( array(
			'url' => '',
		), $atts ) );

		return $this->zalo_button_share_html( $url );
	}

}

new OT_Zalo();