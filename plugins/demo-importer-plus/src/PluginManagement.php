<?php

namespace KraftPlugins\DemoImporterPlus;

class PluginManagement {

	public function handle ( $required_plugins = array(), $options = array(), $enabled_extensions = array() ) {

		$response = array(
			'active'       => array(),
			'inactive'     => array(),
			'notinstalled' => array(),
		);

		$request = new \WP_REST_Request( 'POST' );

		$request->set_body_params( wp_unslash( $_POST ) );

		$template_data = DemoAPI::fetch( $request->get_param( 'demoId' ) );

		if ( !$template_data->success ) {
			wp_send_json_error();
		}

		$response_data = $template_data->data;

		$sanitized_required_plugins = array();
		if ( isset( $response_data[ 'required_plugins' ] ) ) {
			$sanitized_required_plugins = array_map(
				function ( $plugin ) {
					$sanitized_plugin[ 'name' ] = isset( $plugin[ 'name' ] ) ? sanitize_text_field( $plugin[ 'name' ] ) : '';
					$sanitized_plugin[ 'slug' ] = isset( $plugin[ 'slug' ] ) ? sanitize_title( $plugin[ 'slug' ] ) : '';
					$sanitized_plugin[ 'init' ] = isset( $plugin[ 'init' ] ) ? sanitize_text_field( $plugin[ 'init' ] ) : '';

					return $sanitized_plugin;
				},
				$response_data[ 'required_plugins' ]
			);
		}

		$required_plugins = ( isset( $response_data[ 'required_plugins' ] ) ) ? $sanitized_required_plugins : $required_plugins;

		$learndash_course_grid = 'https://www.learndash.com/add-on/course-grid/';
		$learndash_woocommerce = 'https://www.learndash.com/add-on/woocommerce/';
		if ( is_plugin_active( 'sfwd-lms/sfwd_lms.php' ) ) {
			$learndash_addons_url = admin_url( 'admin.php?page=learndash_lms_addons' );
			$learndash_course_grid = $learndash_addons_url;
			$learndash_woocommerce = $learndash_addons_url;
		}

		$third_party_required_plugins = array();
		$third_party_plugins = array(
			'sfwd-lms'              => array(
				'init' => 'sfwd-lms/sfwd_lms.php',
				'name' => 'LearnDash LMS',
				'link' => 'https://www.learndash.com/',
			),
			'learndash-course-grid' => array(
				'init' => 'learndash-course-grid/learndash_course_grid.php',
				'name' => 'LearnDash Course Grid',
				'link' => $learndash_course_grid,
			),
			'learndash-woocommerce' => array(
				'init' => 'learndash-woocommerce/learndash_woocommerce.php',
				'name' => 'LearnDash WooCommerce Integration',
				'link' => $learndash_woocommerce,
			),
		);

		$plugin_updates = get_plugin_updates();
		$update_avilable_plugins = array();

		if ( !empty( $required_plugins ) ) {
			foreach ( $required_plugins as $key => $plugin ) {

				$plugin_pro = $this->pro_plugin_exist( $plugin[ 'init' ] );
				if ( $plugin_pro ) {

					if ( array_key_exists( $plugin_pro[ 'init' ], $plugin_updates ) ) {
						$update_avilable_plugins[] = $plugin_pro;
					}

					if ( is_plugin_active( $plugin_pro[ 'init' ] ) ) {
						$response[ 'active' ][] = $plugin_pro;
					} else {
						$response[ 'inactive' ][] = $plugin_pro;
					}
				} else {
					if ( array_key_exists( $plugin[ 'init' ], $plugin_updates ) ) {
						$update_avilable_plugins[] = $plugin;
					}

					if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin[ 'init' ] ) && is_plugin_inactive( $plugin[ 'init' ] ) ) {

						$response[ 'inactive' ][] = $plugin;
					} else {
						if ( !file_exists( WP_PLUGIN_DIR . '/' . $plugin[ 'init' ] ) ) {

							if ( array_key_exists( $plugin[ 'slug' ], $third_party_plugins ) ) {
								$third_party_required_plugins[] = $third_party_plugins[ $plugin[ 'slug' ] ];
							} else {
								$response[ 'notinstalled' ][] = $plugin;
							}
						} else {
							$response[ 'active' ][] = $plugin;
						}
					}
				}
			}
		}

		if (
			( !defined( 'WP_CLI' ) ) &&
			( ( !current_user_can( 'install_plugins' ) && !empty( $response[ 'notinstalled' ] ) ) || ( !current_user_can( 'activate_plugins' ) && !empty( $response[ 'inactive' ] ) ) )
		) {
			$message = __( 'Insufficient Permission. Please contact your Super Admin to allow the install required plugin permissions.', 'demo-importer-plus' );
			$required_plugins_list = array_merge( $response[ 'notinstalled' ], $response[ 'inactive' ] );
			$markup = $message;
			$markup .= '<ul>';
			foreach ( $required_plugins_list as $key => $required_plugin ) {
				$markup .= '<li>' . esc_html( $required_plugin[ 'name' ] ) . '</li>';
			}
			$markup .= '</ul>';

			wp_send_json_error( $markup );
		}

		$data = array(
			'required_plugins'             => $response,
			'third_party_required_plugins' => $third_party_required_plugins,
			'update_avilable_plugins'      => $update_avilable_plugins,
		);

		if ( defined( 'WP_CLI' ) ) {
			return $data;
		} else {
			wp_send_json_success( $data );
		}
	}

	public function pro_plugin_exist ( $lite_version = '' ) {

		$plugins = apply_filters(
			'demo_importer_plus_pro_plugin_exist',
			array(
				'beaver-builder-lite-version/fl-builder.php'                    => array(
					'slug' => 'bb-plugin',
					'init' => 'bb-plugin/fl-builder.php',
					'name' => 'Beaver Builder Plugin',
				),
				'ultimate-addons-for-beaver-builder-lite/bb-ultimate-addon.php' => array(
					'slug' => 'bb-ultimate-addon',
					'init' => 'bb-ultimate-addon/bb-ultimate-addon.php',
					'name' => 'Ultimate Addon for Beaver Builder',
				),
				'wpforms-lite/wpforms.php'                                      => array(
					'slug' => 'wpforms',
					'init' => 'wpforms/wpforms.php',
					'name' => 'WPForms',
				),
			),
			$lite_version
		);

		if ( isset( $plugins[ $lite_version ] ) ) {

			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugins[ $lite_version ][ 'init' ] ) ) {
				return $plugins[ $lite_version ];
			}
		}

		return false;
	}
}
