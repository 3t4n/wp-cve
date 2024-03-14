<?php
/**
 * The admin preview.
 *
 * @link        https://shapedplugin.com/
 * @since      1.3.0
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin
 */

/**
 * The admin preview.
 */
class Woo_Category_Slider_Preview {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.3.0
	 */
	public function __construct() {
		$this->woo_category_slider_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function woo_category_slider_preview_action() {
		// admin Preview.
		add_action( 'wp_ajax_sp_wcsp_preview_meta_box', array( $this, 'sp_wcsp_preview_meta_box' ) );

	}

	/**
	 * Function Backed preview.
	 *
	 * @since 1.3.0
	 */
	public function sp_wcsp_preview_meta_box() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'spf_metabox_nonce' ) ) {
			return;
		}

		$setting = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting );
		// Shortcode id.
		$post_id        = esc_attr( $setting['post_ID'] );
		$shortcode_meta = $setting['sp_wcsp_shortcode_options'];
		$title          = $setting['post_title'];
		$dynamic_style  = Woo_Category_Slider_Public::load_dynamic_style( $post_id, $shortcode_meta );

		echo '<style id="sp_category_dynamic_css' . $post_id . '">' . wp_strip_all_tags( $dynamic_style['dynamic_css'] ) . '</style>';//phpcs:ignore
		Woo_Category_Slider_Shortcode::sp_wcsp_html_show( $post_id, $shortcode_meta, $title );
		?>
		<script src="<?php echo esc_url( SP_WCS_URL . 'public/js/swiper-config.js' ); ?>" ></script>
		<script src="<?php echo esc_url( SP_WCS_URL . 'public/js/preloader.js' ); ?>" ></script>
		<?php
		die();
	}

}
new Woo_Category_Slider_Preview();
