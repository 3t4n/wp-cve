<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists('Stonehenge_Plugin')) :
Class Stonehenge_Plugin extends Stonehenge_License {

	var $plugin;
	var $text;
	var $version;
	var $new_version = false;
	var $core_version = '3.0';
	var $slug;
	var $file;
	var $path;
	var $url;
	var $is_licensed;
	var $is_valid;
	var $remote;


	#===============================================
	public function set_variables( $plugin ) {
		$plugin['text'] 	= isset($plugin['text']) ? $plugin['text'] : str_replace('_', '-', $plugin['slug']);
		$plugin['slug'] 	= isset($plugin['slug']) ? $plugin['slug'] : str_replace('_', '-', $plugin['text']);
		$plugin['icon'] 	= isset($plugin['icon']) ? trim($plugin['icon']) : null;

		$base 				= $plugin['base'];
		$path 				= WP_PLUGIN_DIR . "/{$base}/{$base}.php";
		$data 				= get_plugin_data( $path );
		$this->plugin 		= $plugin;
		$this->text 		= $plugin['text'];
		$this->version 		= $plugin['version'];
		$this->slug 		= $base;
		$this->file 		= "{$base}/{$base}.php";
		$this->path 		= WP_PLUGIN_DIR . "/{$base}/{$base}.php";
		$this->url 			= admin_url() . 'admin.php?page=' . $plugin['slug'];
		$this->is_licensed 	= $data['Network'];
		$this->is_valid 	= $this->is_valid($plugin);
	}


	#===============================================
	public function init_updater( $plugin ) {
		$base 	= trim(esc_attr($plugin['base']));
		$hook 	= "puc_cron_updater-{$base}";

		if( $this->is_licensed && !has_action($hook) ) {
			require('server/update-checker.php');
			$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
				STONEHENGE . "update-checker/?action=get_metadata&slug={$base}",
				WP_PLUGIN_DIR . "/{$base}/{$base}.php",
				$base
			);
			$class = $plugin['class'];
			add_action( $hook, array($class, 'validate') );
		}
		return;
	}


	#===============================================
	public function check_for_licensed() {
		require_once('server/update-checker.php');
		$plugins = array(
			'stonehenge-cf-mollie',
			'stonehenge-em-bulk',
			'stonehenge-em-email-pro',
			'stonehenge-em-waiting-lists',
		);

		if( is_multisite() && !is_main_site() ) {
			switch_to_blog(1);
			foreach( $plugins as $base ) {
				$path = WP_PLUGIN_DIR . "/{$base}/{$base}.php";
				if( file_exists( $path ) && !has_action( "puc_cron_updater-{$base}" ) ) {
					$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
						STONEHENGE . "update-checker/?action=get_metadata&slug={$base}", $path, $base
					);
				}
			}
			restore_current_blog();
		} else {
			foreach( $plugins as $base ) {
				$path = WP_PLUGIN_DIR . "/{$base}/{$base}.php";
				if( file_exists( $path ) && !has_action( "puc_cron_updater-{$base}" ) ) {
					$UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
						STONEHENGE . "update-checker/?action=get_metadata&slug={$base}", $path, $base
					);
				}
			}
		}
		return;
	}

} // End class.
endif;
