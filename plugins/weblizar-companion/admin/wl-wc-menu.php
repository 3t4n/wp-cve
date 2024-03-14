<?php
defined('ABSPATH') or die();

/**
 *  Add Admin Menu Panel 
 */
class WL_WC_ImportExportMenu {
	public static function create_menu() {
		$import_menu = add_theme_page(__('Import/Export', WL_COMPANION_DOMAIN), __('Import/Export', WL_COMPANION_DOMAIN), 'edit_theme_options', 'enigma-parallax-import-export', array('WL_WC_ImportExportMenu', 'theme_option_page'));
		add_action('admin_print_styles-' . $import_menu, array('WL_WC_ImportExportMenu', 'dashboard_assets'));
	}

	public static function pro_theme_menu() {
		$import_menu = add_theme_page(__('Import/Export', WL_COMPANION_DOMAIN), __('Import/Export', WL_COMPANION_DOMAIN), 'edit_theme_options', 'enigma-parallax-import-export', array('WL_WC_ImportExportMenu', 'pro_theme_option_page'));
		add_action('admin_print_styles-' . $import_menu, array('WL_WC_ImportExportMenu', 'dashboard_assets'));
	}

	public static function theme_option_page() {
		require_once('inc/wl_wc_enigma_import.php');
	}

	public static function pro_theme_option_page() {
		require_once('inc/wl_wc_pro_import.php');
	}

	public static function dashboard_assets() {
		wp_register_style('bootstrap', WL_COMPANION_PLUGIN_URL . 'admin/css/bootstrap.min.css');
		wp_enqueue_style('bootstrap');
		wp_register_script('Bootstrap', WL_COMPANION_PLUGIN_URL . 'admin/js/bootstrap.js', array('jquery'), true, true);
		wp_enqueue_script('Bootstrap');
		wp_register_style('enigma-parallax-import', WL_COMPANION_PLUGIN_URL . 'admin/css/import.css');
		wp_enqueue_style('enigma-parallax-import');
	}
}
