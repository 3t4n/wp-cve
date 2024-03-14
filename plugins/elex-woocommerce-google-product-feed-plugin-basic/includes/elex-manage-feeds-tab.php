<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elex_Manage_Feeds {

	public function __construct() {
		$this->elex_gpf_manage_feeds_tabs();
		$this->elex_gpf_register_styles_scripts();
	}

	public function elex_gpf_manage_feeds_tabs() {
		?>
		<h2 class="nav-tab-wrapper">
			<a href="admin.php?page=elex-product-feed-manage" class="nav-tab  nav-tab-active"><?php esc_html_e( 'Manage Feeds', 'elex-product-feed' ); ?></a>
			<a href="admin.php?page=elex-product-feed" class="nav-tab"><?php esc_html_e( 'Create Feed', 'elex-product-feed' ); ?></a>
			<a href='admin.php?page=elex-product-feed-settings' class='nav-tab'><?php esc_html_e( 'Settings', 'elex-product-feed' ); ?></a>
			<a href="admin.php?page=elex-product-feed-go-premium" style="color:red;"  class="nav-tab"><?php esc_html_e( 'Go Premium!', 'elex-product-feed' ); ?></a>
		</h2>
		<div class="elex-gpf-steps-navigator">
			<div id ="elex_gpf_step1" class="elex-gpf-steps active">
				<?php esc_html_e( 'START', 'elex-product-feed' ); ?>
			</div>
			<div id ="elex_gpf_step2" class="elex-gpf-steps">
				<?php esc_html_e( 'MAP CATEGORY', 'elex-product-feed' ); ?>
			</div>
			<div id ="elex_gpf_step3" class="elex-gpf-steps ">
				<?php esc_html_e( 'MAP ATTRIBUTES', 'elex-product-feed' ); ?>
			</div>
			<div id ="elex_gpf_step4" class="elex-gpf-steps">
				<?php esc_html_e( 'FILTERING OPTIONS', 'elex-product-feed' ); ?>
			</div>
			<div id ="elex_gpf_step5" class="elex-gpf-steps">
				<?php esc_html_e( 'GENERATE FEED', 'elex-product-feed' ); ?>
			</div>
		</div>
		<?php
	}

	public function elex_gpf_register_styles_scripts() {
		global $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		wp_nonce_field( 'ajax-elex-gpf-manage-feed-nonce', '_ajax_elex_gpf_manage_feed_nonce' );
		wp_register_style( 'elex-manage-feed-style', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/css/elex-manage-feed-styles.css', array(), $woocommerce_version );
		wp_enqueue_style( 'elex-manage-feed-style' );
		wp_register_script( 'elex-manage-feed-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-manage-feed-scripts.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-manage-feed-script' );
		wp_nonce_field( 'ajax-elex-gpf-nonce', '_ajax_elex_gpf_nonce' );
		wp_register_style( 'elex-setting-style', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/css/elex-setting-styles.css', array(), $woocommerce_version );
		wp_enqueue_style( 'elex-setting-style' );
		wp_register_script( 'elex-setting-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-setting-scripts.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-setting-script' );
		wp_register_script( 'elex-edit-feeds', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-edit-feeds.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-edit-feeds' );
		wp_register_script( 'elex-typeahead-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-typeahead.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-typeahead-script' );
		$saved_settings_tab_data = get_option( 'elex_settings_tab_fields_data' );
		$language_selected = isset( $saved_settings_tab_data['cat_language'] ) ? $saved_settings_tab_data['cat_language'] : 'en';
		wp_register_script( 'elex-load-cat-language', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-load-google-categories-in-"' . $language_selected . '".js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-load-cat-language' );
		wp_register_script( 'elex-cats-auto-complete-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-cats-auto-complete.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-cats-auto-complete-script' );
		wp_register_script( 'elex-multiple-chosen-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/chosen.jquery.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-multiple-chosen-script' );
		wp_enqueue_style( 'woocommerce_admin_styles', $woocommerce->plugin_url() . '/assets/css/admin.css', array(), $woocommerce_version );
		wp_register_style( 'elex-gpf-plugin-bootstrap', plugins_url( '/assets/css/bootstrap.css', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_style( 'elex-gpf-plugin-bootstrap' );
		wp_register_script( 'elex-gpf-tooltip-jquery', plugins_url( '/assets/js/tooltip.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-gpf-tooltip-jquery' );
		wp_register_script( 'elex-gpf-fusioncharts', plugins_url( '/assets/js/fusioncharts.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'elex-gpf-fusioncharts' );
		wp_register_script( 'elex-gpf-fusioncharts-charts', plugins_url( '/assets/js/fusioncharts.charts.js', dirname( __FILE__ ) ), array(), $woocommerce_version );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'wc-enhanced-select' );
	}

}

new Elex_Manage_Feeds();
include_once ELEX_PRODUCT_FEED_TEMPLATE_PATH . '/elex-manage-feed-template.php';
