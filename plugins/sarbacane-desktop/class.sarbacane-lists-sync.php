<?php

class SarbacaneListsSync {

	public function add_admin_menu() {
		add_submenu_page(
			'sarbacane',
			__( 'Configuration', 'sarbacane-desktop' ),
			__( 'Configuration', 'sarbacane-desktop' ),
			'administrator','wp_lists_sync',
			array(
				$this,
				'display_settings'
			)
		);
	}

	public function display_settings() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		$nonce_ok = false;
		if ( isset( $_POST ['sarbacane_token'] ) ) {
			if ( wp_verify_nonce( $_POST ['sarbacane_token'], 'sarbacane_config' ) ) {
				$nonce_ok = true;
			}
		}
		if ( $nonce_ok && isset( $_POST['sarbacane_config'] ) && '1' == $_POST['sarbacane_config'] ) {

			if ( isset( $_POST['sarbacane_users_list'] ) && "true" == $_POST['sarbacane_users_list'] ) {
				update_option( 'sarbacane_users_list', true, false );
			} else {
				update_option( 'sarbacane_users_list', false, false );
				$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
				foreach ( $sd_ids_saved as $sd_id_saved ) {
					delete_option( 'sarbacane_user_call_' . $sd_id_saved );
				}
				global $wpdb;
				$wpdb->query( "TRUNCATE TABLE `{$wpdb->prefix}sd_updates`" );
			}

			if ( isset( $_POST['sarbacane_news_list'] ) && $_POST['sarbacane_news_list'] == 'true' ) {
				update_option( 'sarbacane_news_list', true, false );
				$fields = get_option( 'sarbacane_news_fields' );
				if ( ! is_array( $fields ) || count( $fields ) == 0 ) {
					$default_email = new stdClass();
					$default_email->label = 'email';
					$default_email->placeholder = '';
					$default_email->mandatory = true;
					update_option( 'sarbacane_news_fields', array( $default_email ) );
				}
			} else {
				update_option( 'sarbacane_news_list', false, false );
				$sd_ids_saved = get_option( 'sarbacane_sd_id_list', array() );
				foreach ( $sd_ids_saved as $sd_id_saved ) {
					delete_option( 'sarbacane_news_call_' . $sd_id_saved );
				}
			}

			if ( isset( $_POST['sarbacane_theme_sync'] ) && $_POST['sarbacane_theme_sync'] == 'true' ) {
				update_option( 'sarbacane_theme_sync', true, false );
			} else {
				update_option( 'sarbacane_theme_sync', false, false );
			}

			if ( isset( $_POST['sarbacane_blog_content'] ) && $_POST['sarbacane_blog_content'] == 'true' ) {
				update_option( 'sarbacane_blog_content', true, false );
			} else {
				update_option( 'sarbacane_blog_content', false, false );
			}

			if ( isset( $_POST['sarbacane_media_content'] ) && $_POST['sarbacane_media_content'] == 'true' ) {
				update_option( 'sarbacane_media_content', true, false );
			} else {
				update_option( 'sarbacane_media_content', false, false );
			}

			if ( isset( $_POST['sarbacane_rss_data'] ) && $_POST['sarbacane_rss_data'] == 'true' ) {
				update_option( 'sarbacane_rss_data', true, false );
			} else {
				update_option( 'sarbacane_rss_data', false, false );
			}
		}
		wp_enqueue_style (
			'sarbacane_global.css',
			plugins_url ( 'css/sarbacane_global.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		wp_enqueue_style (
			'sarbacane_lists_config.css',
			plugins_url ( 'css/sarbacane_lists_config.css', __FILE__ ),
			array(),
			'1.4.9'
		);
		wp_enqueue_script(
			'sarbacane-lists-sync.js',
			plugins_url( 'js/sarbacane-lists-sync.js', __FILE__ ),
			array( 'jquery' ),
			'1.4.9'
		);
		$sarbacane_news_list = get_option( 'sarbacane_news_list', false );
		$sarbacane_users_list = get_option( 'sarbacane_users_list', false );

		$sarbacane_theme_sync = get_option( 'sarbacane_theme_sync', false );
		$sarbacane_blog_content = get_option( 'sarbacane_blog_content', false );
		$sarbacane_media_content = get_option( 'sarbacane_media_content', false );
		$sarbacane_rss_data = get_option( 'sarbacane_rss_data', false );

		require_once( 'views/sarbacane-lists-sync.php' );
	}

}
