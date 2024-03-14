<?php

/**
 * Body Background Color
 * Sidebar Color
 * Content Color
 * Content Body Color
 * Ads Color
 *
 * Website Logo
 */


if ( ! class_exists( 'FEDT_Hooks' ) ) {
	/**
	 * Class FEDT_Hooks
	 */
	class FEDT_Hooks {

		public function __construct() {
			add_filter( 'frontend-dashboard_template_paths', array( $this, 'add_template_part' ), 30 );
			add_filter( 'fed_plugin_versions', array( $this, 'fedt_plugin_versions' ) );
			add_filter( 'init', array( $this, 'fedt_remove_admin_bar' ) );
			add_filter( 'widgets_init', array( $this, 'fedt_widgets_init' ) );
			add_filter( 'fed_change_author_frontend_page', array( $this, 'fedt_change_author_frontend_page' ) );
			add_filter( 'fed_admin_upl_colors_template', array( $this, 'fedt_admin_upl_colors_template' ), 10, 2 );
			add_filter( 'fed_admin_settings_upl_color', array( $this, 'fedt_admin_settings_upl_color' ), 10, 2 );
			add_action( 'fed_add_inline_css_at_head', array( $this, 'fedt_add_inline_css_at_head' ) );
			add_filter( 'fed_admin_settings_upl', array( $this, 'fedt_admin_settings_upl' ), 10, 2 );
			add_filter( 'fed_admin_upl_settings_template', array( $this, 'fedt_admin_upl_settings_template' ), 10, 2 );

			add_action( 'wp_ajax_fedt_upload_profile_image', array( $this, 'fedt_upload_profile_image' ) );
			add_action( 'wp_ajax_nopriv_fedt_upload_profile_image', 'fed_block_the_action' );

			add_filter(
				'fed_customize_admin_user_profile_layout_options', array(
				$this,
				'fedt_customize_admin_user_profile_layout_options',
			), 10
			);

			add_action(
				'fed_admin_settings_login_action', array(
					$this,
					'fedt_admin_settings_login_action',
				)
			);

			// add_action( 'fed_before_login_form', array( $this, 'fedt_before_login_form' ) );
		}

		/**
		 * Upload Profile Image.
		 */
		public function fedt_upload_profile_image() {
			$post_payload = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
			$get_payload  = filter_input_array( INPUT_GET, FILTER_SANITIZE_STRING );

			fed_verify_nonce( $get_payload );

			if ( isset( $post_payload['profile_image_url'] ) && ! empty( $post_payload['profile_image_url'] ) ) {
				update_user_meta( get_current_user_id(), 'fed_user_profile_image',
					$post_payload['profile_image_url'] );
				wp_send_json_success( array(
					'message' => __( 'Successfully Updated', 'frontend-dashboard-templates' ),
				) );
				exit();
			}
			wp_send_json_error( array(
				'message' => __( 'Something Went Wrong', 'frontend-dashboard-templates' ),
			) );
			exit();
		}


		public function fedt_before_login_form() {
			?>
			<div class="bc_fed container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3 flex-center">
						<?php echo fedt_get_website_logo(); ?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * @param $fed_admin_settings_upl
		 * @param $request
		 *
		 * @return mixed
		 */
		public function fedt_admin_settings_upl( $fed_admin_settings_upl, $request ) {
			$fed_admin_settings_upl['settings']['fed_upl_website_logo']        = isset( $request['settings']['fed_upl_website_logo'] ) ? (int) $request['settings']['fed_upl_website_logo'] : null;
			$fed_admin_settings_upl['settings']['fed_upl_website_logo_width']  = isset( $request['settings']['fed_upl_website_logo_width'] ) ? (int) $request['settings']['fed_upl_website_logo_width'] : '';
			$fed_admin_settings_upl['settings']['fed_upl_website_logo_height'] = isset( $request['settings']['fed_upl_website_logo_height'] ) ? (int) $request['settings']['fed_upl_website_logo_height'] : '100';
			$fed_admin_settings_upl['settings']['fed_upl_template_model']      = isset( $request['settings']['fed_upl_template_model'] ) ? $request['settings']['fed_upl_template_model'] : 'default';

			return $fed_admin_settings_upl;
		}

		/**
		 * @param $array
		 * @param $fed_admin_options
		 *
		 * @return mixed
		 */
		public function fedt_admin_upl_settings_template( $array, $fed_admin_options ) {
			if ( defined( 'BC_FED_EXTRA_PLUGIN_VERSION' ) ) {
				$array['input']['Website Logo']        = array(
					'col'   => 'col-md-12',
					'name'  => __( 'Website Logo', 'frontend-dashboard-templates' ),
					'input' => fed_get_input_details(
						array(
							'input_meta' => 'settings[fed_upl_website_logo]',
							'user_value' => isset( $fed_admin_options['settings']['fed_upl_website_logo'] ) ? $fed_admin_options['settings']['fed_upl_website_logo'] : null,
							'input_type' => 'file',
						)
					),
				);
				$array['input']['Website Logo Width']  = array(
					'col'   => 'col-md-6',
					'name'  => __( 'Website Logo Width (px)', 'frontend-dashboard-templates' ),
					'input' => fed_get_input_details(
						array(
							'input_meta' => 'settings[fed_upl_website_logo_width]',
							'user_value' => isset( $fed_admin_options['settings']['fed_upl_website_logo_width'] ) ? $fed_admin_options['settings']['fed_upl_website_logo_width'] : null,
							'input_type' => 'number',
						)
					),
				);
				$array['input']['Website Logo height'] = array(
					'col'   => 'col-md-6',
					'name'  => __( 'Website Logo Height (px)', 'frontend-dashboard-templates' ),
					'input' => fed_get_input_details(
						array(
							'input_meta' => 'settings[fed_upl_website_logo_height]',
							'user_value' => isset( $fed_admin_options['settings']['fed_upl_website_logo_height'] ) ? $fed_admin_options['settings']['fed_upl_website_logo_height'] : null,
							'input_type' => 'number',
						)
					),
				);
				$array['input']['Template Model']      = array(
					'col'   => 'col-md-6',
					'name'  => __( 'Template Model', 'frontend-dashboard-templates' ),
					'input' => fed_get_input_details(
						array(
							'input_meta'  => 'settings[fed_upl_template_model]',
							'input_value' => array(
								'default'   => 'Default',
								'template1' => 'Template 1',
							),
							'user_value'  => isset( $fed_admin_options['settings']['fed_upl_template_model'] ) ? $fed_admin_options['settings']['fed_upl_template_model'] : 'default',
							'input_type'  => 'select',
						)
					),
				);
				// $array['input']['Hide Admin Menu Bar'] = array(
				// 'col'   => 'col-md-6',
				// 'name'  => __( 'Hide Admin Menu Bar', 'frontend-dashboard-templates' ),
				// 'input' => fed_get_input_details( array(
				// 'input_meta'  => 'settings[fed_upl_hide_admin_bar]',
				// 'input_value' => fed_yes_no( 'ASC' ),
				// 'user_value'  => isset( $fed_admin_options['settings']['fed_upl_hide_admin_bar'] ) ? $fed_admin_options['settings']['fed_upl_hide_admin_bar'] : '',
				// 'input_type'  => 'select'
				// ) )
				// );
			} else {
				$array['input']['Website Logo'] = array(
					'col'   => 'col-md-12',
					'name'  => __( 'Website Logo', 'frontend-dashboard-templates' ),
					'input' => '<br>Please install <a href="https://buffercode.com/plugin/frontend-dashboard-extra">Frontend Dashboard Extra plugin</a> to upload logo',
				);
			}

			return $array;
		}

		/**
		 * @param $array
		 * @param $fed_admin_options
		 *
		 * @return mixed
		 */
		public function fedt_admin_upl_colors_template( $array, $fed_admin_options ) {
			$array['input']['Body Background Color'] = array(
				'col'          => 'col-md-6',
				'name'         => __( 'Body Background Color', 'frontend-dashboard-templates' ),
				'input'        => fed_get_input_details(
					array(
						'input_meta' => 'color[fed_upl_color_bbg_color]',
						'user_value' => isset( $fed_admin_options['color']['fed_upl_color_bbg_color'] ) ? $fed_admin_options['color']['fed_upl_color_bbg_color'] : '#033333',
						'input_type' => 'color',
					)
				),
				'help_message' => fed_show_help_message(
					array(
						'content' => __( 'Default Body Background Color #033333', 'frontend-dashboard-templates' ),
					)
				),
			);

			$array['input']['Content Background Color'] = array(
				'col'          => 'col-md-6',
				'name'         => __( 'Content Background Color', 'frontend-dashboard-templates' ),
				'input'        => fed_get_input_details(
					array(
						'input_meta' => 'color[fed_upl_color_cbg_color]',
						'user_value' => isset( $fed_admin_options['color']['fed_upl_color_cbg_color'] ) ? $fed_admin_options['color']['fed_upl_color_cbg_color'] : '#f3f3f3',
						'input_type' => 'color',
					)
				),
				'help_message' => fed_show_help_message(
					array(
						'content' => __( 'Default Content Background Color #f3f3f3' ),
						'frontend-dashboard-templates',
					)
				),
			);

			$array['input']['Widget Background Color'] = array(
				'col'          => 'col-md-6',
				'name'         => __( 'Widget Background Color', 'frontend-dashboard-templates' ),
				'input'        => fed_get_input_details(
					array(
						'input_meta' => 'color[fed_upl_color_wbg_color]',
						'user_value' => isset( $fed_admin_options['color']['fed_upl_color_wbg_color'] ) ? $fed_admin_options['color']['fed_upl_color_wbg_color'] : '#f3f3f3',
						'input_type' => 'color',
					)
				),
				'help_message' => fed_show_help_message(
					array(
						'content' => __( 'Default Widget Background Color #f3f3f3', 'frontend-dashboard-templates' ),
					)
				),
			);

			$array['input']['Panel Background Color'] = array(
				'col'          => 'col-md-6',
				'name'         => __( 'Panel Background Color', 'frontend-dashboard-templates' ),
				'input'        => fed_get_input_details(
					array(
						'input_meta' => 'color[fed_upl_color_pbg_color]',
						'user_value' => isset( $fed_admin_options['color']['fed_upl_color_pbg_color'] ) ? $fed_admin_options['color']['fed_upl_color_pbg_color'] : '#f3f3f3',
						'input_type' => 'color',
					)
				),
				'help_message' => fed_show_help_message(
					array(
						'content' => __( 'Default Widget Background Color #f3f3f3' ),
						'frontend-dashboard-templates',
					)
				),
			);

			return $array;
		}

		/**
		 * @param $fed_admin_settings_upl
		 * @param $request
		 *
		 * @return mixed
		 */
		public function fedt_admin_settings_upl_color( $fed_admin_settings_upl, $request ) {
			$fed_admin_settings_upl['color']['fed_upl_color_bbg_color'] = isset( $request['color']['fed_upl_color_bbg_color'] ) ? sanitize_text_field( $request['color']['fed_upl_color_bbg_color'] ) : '#033333';

			$fed_admin_settings_upl['color']['fed_upl_color_cbg_color'] = isset( $request['color']['fed_upl_color_cbg_color'] ) ? sanitize_text_field( $request['color']['fed_upl_color_cbg_color'] ) : '#f3f3f3';

			$fed_admin_settings_upl['color']['fed_upl_color_wbg_color'] = isset( $request['color']['fed_upl_color_wbg_color'] ) ? sanitize_text_field( $request['color']['fed_upl_color_wbg_color'] ) : '#f3f3f3';

			$fed_admin_settings_upl['color']['fed_upl_color_pbg_color'] = isset( $request['color']['fed_upl_color_pbg_color'] ) ? sanitize_text_field( $request['color']['fed_upl_color_pbg_color'] ) : '#f3f3f3';

			return $fed_admin_settings_upl;
		}

		/**
		 * @return bool
		 */
		public function fedt_remove_admin_bar() {
			$user_role         = fed_get_current_user_role_key();
			$fed_admin_options = get_option( 'fed_admin_settings_upl_hide_admin_bar' );
			if (
				$user_role &&
				$fed_admin_options &&
				isset( $fed_admin_options['hide_admin_menu_bar']['role'] ) &&
				count( $fed_admin_options['hide_admin_menu_bar']['role'] ) > 0
			) {
				if (
					isset( $fed_admin_options['hide_admin_menu_bar']['role'] ) && array_key_exists(
						fed_get_current_user_role_key(),
						$fed_admin_options['hide_admin_menu_bar']['role']
					)
				) {
					show_admin_bar( false );
				}
			}

			if (
				$user_role === false && isset( $fed_admin_options['hide_admin_menu_bar']['role'] ) && array_key_exists(
					'fed_disable_all_user',
					$fed_admin_options['hide_admin_menu_bar']['role']
				)
			) {
				show_admin_bar( false );
			}
		}

		/**
		 * @param $template
		 *
		 * @return mixed
		 */
		public function add_template_part( $template ) {
			$is_template_active = get_option( 'fed_admin_settings_upl', false );
			if ( $is_template_active ) {
				if ( isset( $is_template_active['settings']['fed_upl_template_model'] ) && $is_template_active['settings']['fed_upl_template_model'] === 'default' ) {
					$template[295] = FED_TEMPLATES_PLUGIN_DIR . '/templates';
				}
				if ( isset( $is_template_active['settings']['fed_upl_template_model'] ) && $is_template_active['settings']['fed_upl_template_model'] === 'template1' ) {
					$template[5] = FED_TEMPLATES_PLUGIN_DIR . '/templates';
				}
			}

			return $template;
		}

		/**
		 * @param $version
		 *
		 * @return array
		 */
		public function fedt_plugin_versions( $version ) {
			return array_merge(
				$version, array(
					'dashboard_template' => __(
						'Template (' . FED_TEMPLATES_PLUGIN_VERSION . ')',
						'frontend-dashboard-templates'
					),
				)
			);
		}

		public function fedt_widgets_init() {
			register_sidebar(
				array(
					'name'          => __( 'FED Right Sidebar', 'frontend-dashboard-templates' ),
					'id'            => 'fed_dashboard_right_sidebar',
					'description'   => __(
						'The Frontend Dashboard Right Sidebar on Custom Template',
						'frontend-dashboard-templates'
					),
					'before_widget' => '<aside id="%1$s" class="widget %2$s">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h3 class="widget-title">',
					'after_title'   => '</h3>',
				)
			);
		}

		public function fedt_add_inline_css_at_head() {
			if ( fed_is_shortcode_in_content() ) {
				$fed_colors = get_option( 'fed_admin_setting_upl_color' );

				if ( $fed_colors !== false ) {
					// Body BG Color
					$bbg_color = isset( $fed_colors['color']['fed_upl_color_bbg_color'] ) ? $fed_colors['color']['fed_upl_color_bbg_color'] : 'transparent';
					// Widget BG Color
					$wbg_font_color = isset( $fed_colors['color']['fed_upl_color_wbg_color'] ) ? $fed_colors['color']['fed_upl_color_wbg_color'] : '#f3f3f3';
					// Content BG Color
					$cbg_color = isset( $fed_colors['color']['fed_upl_color_cbg_color'] ) ? $fed_colors['color']['fed_upl_color_cbg_color'] : '#f3f3f3';
					// Primary BG Color
					$pbg_color = isset( $fed_colors['color']['fed_upl_color_bg_color'] ) ? $fed_colors['color']['fed_upl_color_bg_color'] : '#f3f3f3';

					$pfont_color = isset( $fed_colors['color']['fed_upl_color_bg_font_color'] ) ? $fed_colors['color']['fed_upl_color_bg_font_color'] : '#ffffff';
					// Secondary BG Color
					$sbg_color = isset( $fed_colors['color']['fed_upl_color_sbg_color'] ) ? $fed_colors['color']['fed_upl_color_sbg_color'] : '#033333';
					// Secondary Font Color
					$sfont_color = isset( $fed_colors['color']['fed_upl_color_sbg_font_color'] ) ? $fed_colors['color']['fed_upl_color_sbg_font_color'] : '#ffffff';
					// Panel Color
					$panel_color = isset( $fed_colors['color']['fed_upl_color_pbg_color'] ) ? $fed_colors['color']['fed_upl_color_pbg_color'] : '#0aaaaa';

					?>
					<style>
						body {
							background-color: <?php echo esc_attr($bbg_color); ?> !important;
						}

						.fed_login_wrapper a {
							color: <?php echo esc_attr($pfont_color); ?> !important;
						}

						.fed_dashboard_items {
							background-color: transparent !important;
						}

						.fed_bg_primary {
							background: <?php echo esc_attr( $pbg_color ); ?> !important;
							color: <?php echo esc_attr( $pfont_color ); ?> !important;
						}

						.fed_ads {
							background-color: <?php echo esc_attr($wbg_font_color); ?> !important;
						}

						.bc_fed .fed_menu_slug a {
							background-color: <?php echo esc_attr($sbg_color); ?> !important;
							color: <?php echo esc_attr($sfont_color); ?>;
							margin-right: 10px;
						}

						.bc_fed .fed_menu_slug.active a {
							background-color: <?php echo esc_attr($pbg_color); ?> !important;
						}

						.bc_fed .panel-body {
							background-color: <?php echo esc_attr($cbg_color); ?> !important;
						}

						.bc_fed #fed_template1_template {
							background-color: <?php echo esc_attr($panel_color); ?> !important;
						}

						.bc_fed .fed_dashboard_menus.fed_template1 {
							background: <?php echo esc_attr($sbg_color); ?>;
						}

						.swal2-icon.swal2-success {
							border-color: <?php echo esc_attr( $pbg_color ); ?> !important;
						}

						.swal2-icon.swal2-success [class^='swal2-success-line'] {
							background-color: <?php echo esc_attr( $pbg_color ); ?> !important;
						}

						.swal2-icon.swal2-success .swal2-success-ring {
							width: 80px;
							height: 80px;
							border: 4px solid <?php echo esc_attr( $pbg_color ); ?> !important;
						}

						.swal2-confirm.swal2-styled {
							background-color: <?php echo esc_attr( $pbg_color ); ?> !important;
							border-left-color: <?php echo esc_attr( $pbg_color ); ?> !important;
							border-right-color: <?php echo esc_attr( $pbg_color ); ?> !important;
						}

						.fed_primary_font_color {
							color: <?php echo esc_attr( $pbg_color ); ?> !important;
						}

						.fed_tab_menus.active {
							font-weight: 700;
							text-decoration: underline;
						}

						@media only screen and (min-width: 900px) {
							.bc_fed .fed_dashboard_menus.fed_template1 {
								min-height: 100vh !important;
							}
						}
					</style>
					<?php
				}
			}
		}

		/**
		 * @return string
		 */
		public function fedt_change_author_frontend_page() {
			return FED_TEMPLATES_PLUGIN_DIR;
		}

		/**
		 * @param $options
		 *
		 * @return array
		 */
		public function fedt_customize_admin_user_profile_layout_options( $options ) {
			$fed_admin_options                                   = get_option( 'fed_admin_settings_upl_hide_admin_bar' );
			$hide_bar['fedt_admin_user_profile_layout_hide_bar'] = array(
				'icon'      => 'fa fa-eye-slash',
				'name'      => __( 'Hide Admin Menu Bar', 'frontend-dashboard-templates' ),
				'callable'  => 'fedt_admin_user_profile_hide_bar_tab',
				'arguments' => $fed_admin_options,
			);

			return array_merge( $options, $hide_bar );
		}

		/**
		 * @param $request
		 */
		public function fedt_admin_settings_login_action( $request ) {
			if ( isset( $request['fed_admin_unique'] ) && 'fed_admin_setting_upl_hide_bar' == $request['fed_admin_unique'] ) {
				$fed_admin_settings_upl                        = get_option( 'fed_admin_settings_upl_hide_admin_bar' );
				$fed_admin_settings_upl['hide_admin_menu_bar'] = array(
					'role' => isset( $request['hide_menu_bar']['role'] ) ? $request['hide_menu_bar']['role'] : array(),
				);

				$new_settings = apply_filters(
					'fed_admin_settings_upl_hide_admin_bar', $fed_admin_settings_upl,
					$request
				);

				update_option( 'fed_admin_settings_upl_hide_admin_bar', $new_settings );

				wp_send_json_success(
					array(
						'message' => __( 'Hide Admin Menu Bar Updated Successfully ' ),
					)
				);

				exit();
			}
		}

	}

	new FEDT_Hooks();
}
