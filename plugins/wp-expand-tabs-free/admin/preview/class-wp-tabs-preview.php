<?php
/**
 * The admin preview.
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.15
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 */

/**
 * The admin preview.
 */
class WP_Tabs_Preview {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    2.0.15
	 */
	public function __construct() {
		$this->wp_tabs_preview_action();
	}

	/**
	 * Public Action
	 *
	 * @return void
	 */
	private function wp_tabs_preview_action() {
		// admin Preview.
		add_action( 'wp_ajax_sp_tab_preview_meta_box', array( $this, 'wp_tabs_backend_preview' ) );

	}

	/**
	 * Function Backed preview.
	 *
	 * @since 2.0.15
	 */
	public function wp_tabs_backend_preview() {
		$nonce = ( ! empty( $_POST['ajax_nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'wptabspro_metabox_nonce' ) ) {
			return;
		}

		$setting = array();
		// XSS ok.
		// No worries, This "POST" requests is sanitizing in the below array map.
		$data = ! empty( $_POST['data'] ) ? wp_unslash( $_POST['data'] )  : ''; // phpcs:ignore
		parse_str( $data, $setting );
		// Preset Layouts.
		$post_id                  = $setting['post_ID'];
		$sptpro_data_src          = $setting['sp_tab_source_options'];
		$sptpro_shortcode_options = $setting['sp_tab_shortcode_options'];
		$main_section_title       = $setting['post_title'];

		// Load dynamic style for the existing shortcode.
		$dynamic_style = WP_Tabs_Public::load_dynamic_style( $post_id, $sptpro_shortcode_options );
		echo '<style id="sp_tab_dynamic_style' . esc_attr( $post_id ) . '">' . $dynamic_style['dynamic_css'] . '</style>'; // phpcs:ignore
		WP_Tabs_Shortcode::sp_tabs_html_show( $post_id, $sptpro_data_src, $sptpro_shortcode_options, $main_section_title );

		?>
		<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/collapse.min.js' ); ?>" ></script>
		<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/tab.min.js' ); ?>" ></script>
		<script src="<?php echo esc_url( WP_TABS_URL . 'public/js/wp-tabs-public.min.js' ); ?>" ></script>
		<?php
		die();
	}

}
new WP_Tabs_Preview();
