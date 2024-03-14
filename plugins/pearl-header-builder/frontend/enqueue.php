<?php if ( ! defined( 'ABSPATH' ) ) exit;

if (!is_admin()) {
	add_action('wp_enqueue_scripts', 'stm_hb_load_scripts_and_styles', 90);

	function stm_hb_load_scripts_and_styles() {

		$jquery = array('jquery');

		$theme_info = stm_get_assets_path();

		wp_enqueue_style('stm_hb_main', $theme_info['frontend_css'] . 'header/main.css', null, $theme_info['v'], 'all');
		wp_enqueue_style('stm_hb_sticky', $theme_info['frontend_vendor'] . 'sticky.css', null, $theme_info['v'], 'all');
		wp_enqueue_style('fontawesome', $theme_info['frontend_css'] . 'font-awesome.min.css', null, $theme_info['v']);

		wp_enqueue_script('stm_hb_scripts', $theme_info['frontend_js'] . 'app.js', $jquery, $theme_info['v'], true);
		wp_enqueue_script('stm_hb_scripts_sticky', $theme_info['frontend_js'] . 'sticky.js', $jquery, $theme_info['v'], true);
		wp_register_script('stm_hb-theme-modal', $theme_info['frontend_js'] . 'modal.js', $jquery, $theme_info['v'], true);
		//wp_enqueue_script('stm_hb_vendors', $theme_info['frontend_vendor'] . 'vendor.js', $jquery, $theme_info['v'], true);

	}
}