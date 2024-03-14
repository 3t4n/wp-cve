<?php

class arflite_pro_controller {

	function __construct() {

		add_shortcode( 'ARFormslite', array( $this, 'arforms_lite_pro_shortcode' ) );

		//add_action( 'admin_menu', array( $this, 'arforms_lite_admin_menu' ) );

		add_action( 'admin_init', array( $this, 'arflite_router' ) );

		add_filter( 'plugin_action_links', array( $this, 'arflite_plugin_action_links' ), 10, 2 );

		add_action( 'admin_footer', array( $this, 'arflite_deactivate_feedback_popup' ), 1 );

		add_action( 'admin_enqueue_scripts', array( $this, 'arflite_set_js' ), 12 );

		add_action( 'wp_ajax_arflite_deactivate_plugin', array( $this, 'arflite_deactivate_plugin_func' ) );

		add_filter( 'arf_display_lite_forms', array( $this, 'arflite_display_lite_forms_for_addons' ), 10, 2 );

		add_filter( 'arfformsdropdowm', array( $this, 'arflite_remove_where_clause' ), 10, 2 );

		add_filter( 'arfformsdropdown_incomplete_entries', array( $this, 'arflite_remove_where_clause' ), 10, 2 );

		add_action( 'wp_ajax_arflite_install_plugin', array( $this, 'arflite_install_plugin' ) );

	}

	function arflite_install_plugin() {
		require_once ARFLITE_CONTROLLERS_PATH . '/arflitesettingcontroller.php';
		global $arflitesettingcontroller;
		$arflitesettingcontroller = new arflitesettingcontroller();

		$arflitesettingcontroller->arflite_install_plugin();
	}

	function arforms_lite_pro_shortcode( $atts ) {

		$form_id = $atts['id'];

		if ( '' == $form_id ) {
			return 'Please select valid form';
		}

		global $wpdb, $MdlDb;

		$get_pro_form_id = $wpdb->get_var( $wpdb->prepare( 'SELECT id FROM `' . $MdlDb->forms . '` WHERE arf_is_lite_form = %d AND arf_lite_form_id = %d', 1, $form_id ) ); //phpcs:ignore

		if ( $get_pro_form_id > 0 ) {
			return do_shortcode( '[ARForms id=' . $get_pro_form_id . ']' );
		}

	}

	function arforms_lite_admin_menu() {

		$place = $this->arflite_get_free_menu_position( 26.1, .1 );

		add_menu_page( 'ARForms Lite', 'ARForms Lite', 'arfviewforms', 'ARForms-Lite', array( $this, 'arfliterouter' ), ARFLITEIMAGESURL . '/main-icon-small2n.png', (string) $place );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Forms', 'arforms-form-builder' ), __( 'Manage Forms', 'arforms-form-builder' ), 'arfviewforms', 'ARForms-Lite', array( $this, 'arfliterouter' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Add New Form', 'arforms-form-builder' ), '<span>' . __( 'Add New Form', 'arforms-form-builder' ) . '</span>', 'arfeditforms', 'ARForms-Lite&amp;arfaction=new&amp;isp=1', array( $this, 'arflite_new_form' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite' . ' | ' . __( 'Form Entries', 'arforms-form-builder' ), __( 'Form Entries', 'arforms-form-builder' ), 'arfviewentries', 'ARForms-Lite-entries', array( $this, 'arfliterouter' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'General Settings', 'arforms-form-builder' ), __( 'General Settings', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-settings', array( $this, 'arfliterouter' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Import Export', 'arforms-form-builder' ), __( 'Import / Export', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-import-export', array( $this, 'arfliterouter' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Status', 'arforms-form-builder' ), __( 'Status', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-status', array( $this, 'arfliterouter' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Add-ons', 'arforms-form-builder' ), __( 'Addons', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-addons', array( $this, 'arfliterouter' ) );

	}

	function arfliterouter() {
		if ( ! empty( $_GET['page'] ) && 'ARForms-Lite-addons' == sanitize_text_field( $_GET['page'] ) ) {
			if ( file_exists( ARFLITE_VIEWS_PATH . '/addon_lists.php' ) ) {

				require_once ARFLITE_CONTROLLERS_PATH . '/arflitesettingcontroller.php';
				global $arflitesettingcontroller;
				$arflitesettingcontroller = new arflitesettingcontroller();

				require_once ARFLITE_CONTROLLERS_PATH . '/arfliteformcontroller.php';
				global $arfliteformcontroller;
				$arfliteformcontroller = new arfliteformcontroller();

				require_once ARFLITE_MODELS_PATH . '/arfliteinstallermodel.php';
				global $ARFLiteMdlDb;
				$ARFLiteMdlDb = new arfliteinstallermodel();

				require_once ARFLITE_MODELS_PATH . '/arflitenotifymodel.php';
				global $arflitenotifymodel;
				$arflitenotifymodel = new arflitenotifymodel();

				require_once ARFLITE_MODELS_PATH . '/arfliteformmodel.php';
				global $arfliteform;
				$arfliteform = new arfliteformmodel();

				require_once ARFLITE_MODELS_PATH . '/arfliterecordmeta.php';
				global $arfliterecordmeta;
				$arfliterecordmeta = new arfliterecordmeta();

				include ARFLITE_VIEWS_PATH . '/addon_lists.php';

			}
		}
	}

	function arflite_router() {

		if ( ! empty( $_GET['page'] ) ) {

			if ( 'ARForms-Lite' == sanitize_text_field( $_GET['page'] ) ) {
				$redirect_url = admin_url( 'admin.php' );

				$query_args = array(
					'page' => 'ARForms',
				);

				unset( $_GET['page'] );
				foreach ( $_GET as $k => $v ) {
					$query_args[ $k ] = $v;
				}

				$redirect_url = add_query_arg(
					$query_args,
					$redirect_url
				);

				wp_redirect( $redirect_url );
				die;
			} elseif ( 'ARForms-entries' == sanitize_text_field( $_GET['page'] ) ) {
				$redirect_url = admin_url( 'admin.php' );

				$query_args = array(
					'page' => 'ARForms-entries',
				);

				unset( $_GET['page'] );
				foreach ( $_GET as $k => $v ) {
					$query_args[ $k ] = $v;
				}

				$redirect_url = add_query_arg(
					$query_args,
					$redirect_url
				);

				wp_redirect( $redirect_url );
				die;
			} elseif ( 'ARForms-Lite-settings' == sanitize_text_field( $_GET['page'] ) ) {
				$redirect_url = admin_url( 'admin.php' );

				$query_args = array(
					'page' => 'ARForms-settings',
				);

				$redirect_url = add_query_arg(
					$query_args,
					$redirect_url
				);

				wp_redirect( $redirect_url );
				die;
			} elseif ( 'ARForms-Lite-import-export' == sanitize_text_field( $_GET['page'] ) ) {
				$redirect_url = admin_url( 'admin.php' );

				$query_args = array(
					'page' => 'ARForms-import-export',
				);

				$redirect_url = add_query_arg(
					$query_args,
					$redirect_url
				);

				wp_redirect( $redirect_url );
				die;
			}
		}

	}

	function arflite_get_free_menu_position( $start, $increment = 0.1 ) {
		foreach ( $GLOBALS['menu'] as $key => $menu ) {
			$menus_positions[] = $key;
		}

		if ( ! in_array( $start, $menus_positions ) ) {
			return $start;
		} else {
			$start += $increment;
		}

		while ( in_array( $start, $menus_positions ) ) {
			$start += $increment;
		}
		return $start;
	}

	function arflite_plugin_action_links( $links, $file ) {

		if ( $file == 'arforms-form-builder/arforms-form-builder.php' ) {

			if ( isset( $links['deactivate'] ) ) {

				$deactivation_link = $links['deactivate'];

				$deactivation_link   = str_replace(
					'<a ',
					'<div class="arflite-deactivate-form-wrapper">
                         <span class="arflite-deactivate-form" id="arflite-deactivate-form-' . esc_attr( 'ARFormslite' ) . '"></span>
                     </div><a id="arflite-deactivate-link-' . esc_attr( 'ARFormslite' ) . '" ',
					$deactivation_link
				);
				$links['deactivate'] = $deactivation_link;
			}
		}
		return $links;
	}

	function arflite_deactivate_feedback_popup() {

		global $pagenow;
		if ( $pagenow == 'plugins.php' ) {
			$question_options = array();

			$question_options['list_data_options'] = array(
				'setup-difficult'  => __( 'Set up is too difficult', 'arforms-form-builder' ),
				'docs-improvement' => __( 'Lack of documentation', 'arforms-form-builder' ),
				'features'         => __( 'Not the features I wanted', 'arforms-form-builder' ),
				'better-plugin'    => __( 'Found a better plugin', 'arforms-form-builder' ),
				'incompatibility'  => __( 'Incompatible with theme or plugin', 'arforms-form-builder' ),
				'bought-premium'   => __( 'I bought premium version of ARForms', 'arforms-form-builder' ),
				'maintenance'      => __( 'Other', 'arforms-form-builder' ),
			);

			$html = '<div class="arflite-deactivate-form-head"><strong>' . esc_html( __( 'ARForms Lite - Sorry to see you go', 'arforms-form-builder' ) ) . '</strong></div>';

			$html .= '<div class="arflite-deactivate-form-body">';

			if ( is_array( $question_options['list_data_options'] ) ) {

				$html .= '<div class="arflite-deactivate-options">';

					$html .= '<p><strong>' . esc_html( __( 'Before you deactivate the ARForms Lite plugin, would you quickly give us your reason for doing so?', 'arforms-form-builder' ) ) . '</strong></p><p>';

				foreach ( $question_options['list_data_options'] as $key => $option ) {
					$html .= '<input type="radio" name="arflite-deactivate-reason" id="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '">' . esc_attr( $option ) . '</label><br>';
				}

					$html .= '</p><label id="arflite-deactivate-details-label" for="arflite-deactivate-reasons"><strong>' . esc_html( __( 'How could we improve ?', 'arforms-form-builder' ) ) . '</strong></label><textarea name="arflite-deactivate-details" id="arflite-deactivate-details" rows="2"></textarea>';

					$html .= '</div>';
			}

			$html .= '<hr/>';

			$html .= '</div>';

			$html .= '<p class="deactivating-spinner"><span class="spinner"></span> ' . __( 'Submitting form', 'arforms-form-builder' ) . '</p>';

			$html .= '<div class="arflite-deactivate-form-footer"><p>';

				$html .= '<label for="arflite_anonymous" title="'
					. __( 'If you UNCHECK this then your email address will be sent along with your feedback. This can be used by arflite to get back to you for more info or a solution.', 'arforms-form-builder' )
					. '"><input type="checkbox" name="arflite-deactivate-tracking" checked="checked" id="arflite_anonymous"> ' . __( 'Send anonymous', 'arforms-form-builder' ) . '</label><br>';

					$html .= '<a id="arflite-deactivate-submit-form" class="button button-primary" href="#">'. sprintf( __( '%s Submit and%s Deactivate', 'arforms-form-builder' ),'<span>','</span>'). '</a>'; //phpcs:ignore

			$html .= '</p></div>';
			?>
			<div class="arflite-deactivate-form-skeleton" id="arflite-deactivate-form-skeleton"><?php echo $html; //phpcs:ignore ?></div>
			<div class="arflite-deactivate-form-bg"></div>
			<?php
		}
	}

	function arflite_set_js( $hook ) {
		global $pagenow,$arfliteversion;

		if ( $pagenow == 'plugins.php' ) {
			wp_register_style( 'arflite-feedback-popup-style', ARFLITEURL . '/css/arflite_deactivation_style.css', array(), $arfliteversion );
			wp_enqueue_style( 'arflite-feedback-popup-style' );

			wp_register_script( 'arflite-feedback-popup-script', ARFLITEURL . '/js/arflite_deactivation_script.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'arflite-feedback-popup-script' );

			$scriptData = 'var arflite_detailsStrings = {
				"setup-difficult":"' . __( 'What was the dificult part?', 'arforms-form-builder' ) . '",
				"docs-improvement":"' . __( 'What can we describe more?', 'arforms-form-builder' ) . '",
				"features":"' . __( 'How could we improve?', 'arforms-form-builder' ) . '",
				"better-plugin":"' . __( 'Can you mention it?', 'arforms-form-builder' ) . '",
				"incompatibility":"' . __( 'With what plugin or theme is incompatible?', 'arforms-form-builder' ) . '",
				"bought-premium":"' . __( 'Please specify experience', 'arforms-form-builder' ) . '",
				"maintenance":"' . __( 'Please specify', 'arforms-form-builder' ) . '",
				"deactivate_btn":"'.__( 'Deactivate', 'arforms-form-builder') . '",
				"deactivate_submit_btn":"'.__( 'Submit and Deactivate', 'arforms-form-builder').'"
			};

			var pluginName = "' . esc_attr( 'ARFormslite' ) . '";
			var pluginSecurity = "' . wp_create_nonce( 'arflite_deactivate_plugin' ) . '";
			';

			wp_add_inline_script( 'arflite-feedback-popup-script', $scriptData );
		} elseif ( ! empty( $_GET['page'] ) && $_GET['page'] == 'ARForms-Lite-addons' ) {
			wp_enqueue_style( 'arforms_lite_admin_css', ARFLITEURL . '/css/arformslite_v3.0.css', array(), $arfliteversion );
			wp_enqueue_style( 'arforms_lite_admin_media_css', ARFLITEURL . '/css/arflite_media_css.css', array(), $arfliteversion );
			global $wp_version;
			if ( version_compare( $wp_version, '4.0', '>=' ) ) {
				wp_enqueue_style( 'arforms_wp_4_css', ARFLITEURL . '/css/arflite_plugin_4.0.css', array(), $arfliteversion );
			}
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'arformslite_hooks', ARFLITEURL . '/js/arformslite_hooks.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'arformslite_admin', ARFLITEURL . '/js/arformslite_admin.js', array( 'jquery' ), $arfliteversion );
			wp_enqueue_script( 'arformslite_admin_editor', ARFLITEURL . '/js/arformslite_admin_editor.js', array( 'jquery' ), $arfliteversion );
		}
	}

	function arflite_deactivate_plugin_func() {

		check_ajax_referer( 'arflite_deactivate_plugin', 'security' );

		if ( ! empty( $_POST['arflite_reason'] ) && isset( $_POST['arflite_details'] ) ) {

			$arflite_anonymous = isset( $_POST['arflite_anonymous'] ) && sanitize_text_field( $_POST['arflite_anonymous'] );

			$args                      = array();
			$args['arflite_reason']    = ! empty( $_POST['arflite_reason'] ) ? sanitize_text_field( $_POST['arflite_reason'] ) : 'maintenance';
			$args['arflite_details']   = ! empty( $_POST['arflite_details'] ) ? sanitize_text_field( $_POST['arflite_details'] ) : '';
			$args['security']          = ! empty( $_POST['security'] ) ? sanitize_text_field( $_POST['security'] ) : '';
			$args['action']            = ! empty( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';
			$args['dataType']          = ! empty( $_POST['dataType'] ) ? sanitize_text_field( $_POST['dataType'] ) : '';
			$args['arflite_anonymous'] = ! empty( $_POST['arflite_anonymous'] ) ? sanitize_text_field( $_POST['arflite_anonymous'] ) : '';
			$args['arflite_site_url']  = ARFLITE_HOME_URL;

			if ( ! $arflite_anonymous ) {

				$args['arf_lite_site_email'] = get_option( 'admin_email' );
			}

			$url = 'https://www.arformsplugin.com/download_samples/arflite_feedback.php';

			$response = wp_remote_post(
				$url,
				array(
					'body'    => $args,
					'timeout' => 500,
				)
			);
		}
		echo json_encode(
			array(
				'status' => 'OK',
			)
		);
		die();
	}

	function arflite_display_lite_forms_for_addons( $flag, $db_data ) {

		if ( ! empty( $db_data ) && isset( $db_data->arf_is_lite_form ) && $db_data->arf_is_lite_form ) {
			$flag = true;
		}

		return $flag;
	}

	function arflite_remove_where_clause( $where_clause, $field_name ) {

		if ( strpos( $where_clause, 'AND arf_is_lite_form = 0' ) > -1 ) {
			$where_clause = str_replace( 'AND arf_is_lite_form = 0', '', $where_clause );
		} elseif ( strpos( $where_clause, 'OR arf_is_lite_form = 0' ) > -1 ) {
			$where_clause = str_replace( 'AND arf_is_lite_form = 0', '', $where_clause );
		}

		return $where_clause;
	}

}

new arflite_pro_controller();
