<?php
/**
 * The plugin settings page.
 */

/**
 * Add a settings page link to the plugin listing in the plugins admin page.
 */
add_action(
	'plugin_action_links_quick-adsense/quick-adsense.php',
	function ( $links ) {
		$links = array_merge(
			[ '<a href="' . esc_url( admin_url( '/admin.php?page=quick-adsense' ) ) . '">Settings</a>' ],
			$links
		);
		return $links;
	}
);

/**
 * Create the Admin menu entry for the settings page.
 */
add_action(
	'admin_menu',
	function () {
		add_menu_page(
			'Quick Adsense Options',
			'Quick Adsense',
			'manage_options',
			'quick-adsense',
			function () {
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				// Contains textareas which need to allow output of scripts, iframes etc necessary to output ads and trackers.
				echo quick_adsense_load_file( 'templates/page-settings.php' );
				// phpcs:enable
			}
		);
	}
);

/**
 * Add scripts and styles for Settings page.
 */
add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		global $wp_scripts;
		if ( ( 'toplevel_page_quick-adsense' === $hook ) && ( current_user_can( 'manage_options' ) ) ) {
			wp_enqueue_script( 'quick-adsense-chart-scripts', plugins_url( '../assets/js/chart.min.js', __FILE__ ), [ 'jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-dialog' ], '3.7.1', false );
			wp_enqueue_style( 'quick-adsense-jquery-ui-styles', plugins_url( '../assets/css/jquery-ui.min.css', __FILE__ ), [], '1.9.1' );
			wp_enqueue_style( 'quick-adsense-admin-styles', plugins_url( '../assets/css/admin.css', __FILE__ ), [], '2.8.2' );
			wp_enqueue_script( 'quick-adsense-admin-scripts', plugins_url( '../assets/js/admin.js', __FILE__ ), [ 'jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'wp-util' ], '2.8.2', false );
			wp_localize_script(
				'quick-adsense-admin-scripts',
				'quick_adsense',
				[
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'quick-adsense-nonce' ),
				]
			);
		}
	}
);

/**
 * Register settings and sections.
 */
add_action(
	'admin_init',
	function () {
		if ( current_user_can( 'manage_options' ) ) {
			register_setting(
				'quick_adsense_settings',
				'quick_adsense_settings',
				function ( $input ) {
					delete_transient( 'quick_adsense_adstxt_adsense_autocheck_content' );
					return $input;
				}
			);
			$settings                      = get_option( 'quick_adsense_settings' );
			$settings['alignment_options'] = [
				[
					'text'  => 'Left',
					'value' => '1',
				],
				[
					'text'  => 'Center',
					'value' => '2',
				],
				[
					'text'  => 'Right',
					'value' => '3',
				],
				[
					'text'  => 'None',
					'value' => '4',
				],
			];
			add_settings_section(
				'quick_adsense_general',
				'',
				function () use ( $settings ) {
					echo wp_kses(
						quick_adsense_load_file( 'templates/section-general.php', $settings ),
						quick_adsense_get_allowed_html()
					);
				},
				'quick-adsense-general'
			);
			$settings['location'] = 'onpost';
			add_settings_section(
				'quick_adsense_onpost',
				'',
				function () use ( $settings ) {
					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					// Contains textareas which need to allow output of scripts, iframes etc necessary to output ads and trackers.
					echo quick_adsense_load_file( 'templates/section-onpost-content.php', $settings );
					// phpcs:enable
				},
				'quick-adsense-onpost'
			);
			$settings['location'] = 'widgets';
			add_settings_section(
				'quick_adsense_widgets',
				'',
				function () use ( $settings ) {
					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					// Contains textareas which need to allow output of scripts, iframes etc necessary to output ads and trackers.
					echo quick_adsense_load_file( 'templates/section-widgets.php', $settings );
					// phpcs:enable
				},
				'quick-adsense-widgets'
			);
			$settings['location'] = 'header_footer_codes';
			add_settings_section(
				'quick_adsense_header_footer_codes',
				'',
				function () use ( $settings ) {
					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					// Contains textareas which need to allow output of scripts, iframes etc necessary to output ads and trackers.
					echo quick_adsense_load_file( 'templates/section-header-footer.php', $settings );
					// phpcs:enable					
				},
				'quick-adsense-header-footer-codes'
			);
		}
	}
);

add_action(
	'wp_ajax_quick_adsense_onpost_ad_reset_stats',
	function () {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'quick-adsense-nonce' ) && ( isset( $_POST['index'] ) ) && ( current_user_can( 'manage_options' ) ) ) {
			delete_option( 'quick_adsense_onpost_ad_' . sanitize_key( $_POST['index'] ) . '_stats' );
			wp_send_json_success();
		}
		wp_send_json_error();
	}
);


add_action(
	'wp_ajax_quick_adsense_onpost_ad_get_stats_chart',
	function () {
		if ( isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ), 'quick-adsense-nonce' ) && ( isset( $_POST['index'] ) ) && ( current_user_can( 'manage_options' ) ) ) {
			$stats      = get_option( 'quick_adsense_onpost_ad_' . sanitize_key( $_POST['index'] ) . '_stats' );
			$stats_data = [];
			for ( $i = 0; $i < 30; $i++ ) {
				$clicks      = 0;
				$impressions = 0;
				if ( isset( $stats ) && is_array( $stats ) && isset( $stats[ gmdate( 'dmY', strtotime( '-' . $i . ' day' ) ) ] ) ) {
					$clicks      = $stats[ gmdate( 'dmY', strtotime( '-' . $i . ' day' ) ) ]['c'];
					$impressions = $stats[ gmdate( 'dmY', strtotime( '-' . $i . ' day' ) ) ]['i'];
				}
				$stats_data[] = [
					'x'  => gmdate( 'm/d/Y', strtotime( '-' . $i . ' day' ) ),
					'y'  => $impressions,
					'y1' => $clicks,
				];
			}
			wp_send_json_success(
				quick_adsense_load_file(
					'templates/block-stats-chart.php',
					[
						'stats_data' => wp_json_encode( $stats_data ),
					]
				)
			);
		}
		wp_send_json_error();
	}
);
