<?php
class arfliterecordcontroller {

	function __construct() {

		//add_action( 'admin_menu', array( $this, 'arflitemenu' ), 20 );

		add_action( 'admin_enqueue_scripts', array( $this, 'arflite_admin_js' ), 1 );

		add_action( 'init', array( $this, 'arflite_register_scripts' ) );

		add_action( 'wp_footer', array( $this, 'arflite_footer_js' ), 1 );

		add_action( 'admin_footer', array( $this, 'arflite_footer_js' ) );

		add_action( 'arfliteentryexecute', array( $this, 'arflite_process_update_entry' ), 10, 4 );

		add_filter( 'arfliteactionsubmitbutton', array( $this, 'arflite_ajax_submit_button' ), 10, 3 );

		add_filter( 'arfliteformsubmitsuccess', array( $this, 'arflite_get_confirmation_method' ), 10, 2 );

		add_filter( 'arflitefieldsreplaceshortcodes', array( $this, 'arflitefilter_shortcode_value' ), 10, 4 );

		add_action( 'wp_ajax_arflite_managecolumns', array( $this, 'arflitemanagecolumns' ) );

		add_action( 'wp_ajax_arflite_retrieve_form_entry', array( $this, 'arflite_retrieve_form_entry_data' ) );
		add_action( 'wp_ajax_arflite_retrieve_form_data', array( $this, 'arflite_retrieve_form_data' ) );

		add_action( 'wp_ajax_arflitechangebulkentries', array( $this, 'arflitechangebulkentries' ) );

		global $arformsmain;
		if( !$arformsmain->arforms_is_pro_active() ){

			add_action( 'wp', array( $this, 'arfliteprocess_entry' ), 10, 0 );
			
			add_action( 'arflite_wp_process_entry', array( $this, 'arfliteprocess_entry' ), 10, 0 );
		}

		add_filter( 'arfliteemailvalue', array( $this, 'arflite_filter_email_value' ), 10, 3 );

		add_action( 'wp_ajax_arflite_delete_single_entry', array( $this, 'arflite_delete_single_entry_function' ) );

	}

	function arflite_get_recordparams( $form = null ) {

		global $arfliteform, $arfliteform_params, $arflitemainhelper, $ARFLiteMdlDb;

		if ( ! $form ) {
			$form = $arfliteform->arflitegetAll( array(), 'name', 1 );
		}

		if ( $arfliteform_params && isset( $arfliteform_params[ $form->id ] ) ) {
			return $arfliteform_params[ $form->id ];
		}

		$action_var = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

		$action = apply_filters( 'arfliteshownewentrypage', $arflitemainhelper->arflite_get_param( $action_var, 'new' ), $form );

		$default_values = array(
			'id'        => '',
			'form_name' => '',
			'paged'     => 1,
			'form'      => $form->id,
			'form_id'   => $form->id,
			'field_id'  => '',
			'search'    => '',
			'sort'      => '',
			'sdir'      => '',
			'action'    => $action,
		);

		$values['posted_form_id'] = $arflitemainhelper->arflite_get_param( 'form_id' );

		if ( ! is_numeric( $values['posted_form_id'] ) ) {
			$values['posted_form_id'] = $arflitemainhelper->arflite_get_param( 'form' );
		}

		if ( $form->id == $values['posted_form_id'] ) {

			foreach ( $default_values as $var => $default ) {

				if ( $var == 'action' ) {
					$values[ $var ] = $arflitemainhelper->arflite_get_param( $action_var, $default );
				} else {
					$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
				}

				unset( $var );

				unset( $default );
			}
		} else {

			foreach ( $default_values as $var => $default ) {

				$values[ $var ] = $default;

				unset( $var );

				unset( $default );
			}
		}

		if ( in_array( $values['action'], array( 'create', 'update' ) ) && ( ! isset( $_POST ) || ( ! isset( $_POST['action'] ) && ! isset( $_POST['arfaction'] ) ) ) ) { //phpcs:ignore
			$values['action'] = 'new';
		}

		return $values;
	}

	function arfliteprocess_entry( $arflite_errors = '' ) {

		global $wpdb, $arfliteformcontroller, $ARFLiteMdlDb, $arformsmain;
		if ( ! isset( $_POST ) || ! isset( $_POST['form_id'] ) || ! is_numeric( $_POST['form_id'] ) || ! isset( $_POST['entry_key'] ) ) {  return; } //phpcs:ignore

		global $arflite_db_record, $arfliteform, $arflitecreatedentry, $arfliteform_params, $arfliterecordcontroller;

		$form_cache_obj = wp_cache_get( 'get_one_form_' . intval( $_POST['form_id'] ) ); //phpcs:ignore

		if ( ! $form_cache_obj ) {
			$form = $arfliteform->arflitegetOne( intval( $_POST['form_id'] ) );//phpcs:ignore

			wp_cache_set( 'get_one_form_' . $form->id, $form ); //phpcs:ignore
		} else {
			$form = $form_cache_obj;
		}

		if ( ! $form ) {
			return;
		}

		if ( ! $arfliteform_params ) {
			$arfliteform_params = array();
		}

		$params = $arfliterecordcontroller->arflite_get_recordparams( $form );

		$arfliteform_params[ $form->id ] = $params;

		if ( ! $arflitecreatedentry ) {
			$arflitecreatedentry = array();
		}

		if ( isset( $arflitecreatedentry[ $_POST['form_id'] ] ) ) { //phpcs:ignore
			return;
		}

		$_SESSION[ 'arf_recaptcha_allowed_' . intval( $_POST['form_id'] ) ] = isset( $_SESSION[ 'arf_recaptcha_allowed_' . intval( $_POST['form_id'] ) ] ) ? $_SESSION[ 'arf_recaptcha_allowed_' . intval( $_POST['form_id'] ) ] : ''; //phpcs:ignore

		$arferrormsg = '';
		$errors1     = array();
		if ( $arflite_errors == '' && $_SESSION[ 'arf_recaptcha_allowed_' . intval( $_POST['form_id'] ) ] == '' ) { //phpcs:ignore
			$arferr         = array();
			$arflite_errors = $arfliterecordcontroller->arflite_internal_check_recaptcha(); //phpcs:ignore
			if ( count( $arflite_errors ) > 0 ) {
				foreach ( $arflite_errors as $field_id => $field_value ) {
					$arferr[ $field_id ] = $field_value;
					$arferrormsg         = $field_value;
				}

				$return['conf_method'] = 'captchaerror';
				$return['message']     = $arferr;
				$return                = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore
				if ( isset( $_POST['form_submit_type'] ) && intval( $_POST['form_submit_type'] ) == 1 ) { //phpcs:ignore
					echo json_encode( $return );
					exit;
				}
			}
		}

		unset( $_SESSION[ 'arf_recaptcha_allowed_' . intval( $_POST['form_id'] ) ] ); //phpcs:ignore

		$arflitecreatedentry[ intval( $_POST['form_id'] ) ] = array( 'errors' => $arflite_errors ); //phpcs:ignore

		$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
		$submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

		if ( isset( $_POST['using_ajax'] ) && strtolower( trim( sanitize_text_field( $_POST['using_ajax'] ) ) ) == 'yes' ) { //phpcs:ignore

			$form_id = intval( $_POST['form_id'] ); //phpcs:ignore

			$arf_errors = array();

			$arf_form_data = array();

			$values = $_POST; //phpcs:ignore

			$arf_form_data = apply_filters( 'arflite_populate_field_from_outside', $arf_form_data, $form_id, $values ); //phpcs:ignore

			$arf_errors = apply_filters( 'arflite_validate_form_outside_errors', $arf_errors, $form_id, $values, $arf_form_data );

			if ( isset( $arf_errors['arf_form_data'] ) && $arf_errors['arf_form_data'] ) {
				$arf_form_data = array_merge( $arf_form_data, $arf_errors['arf_form_data'] );
			}

			unset( $arf_errors['arf_form_data'] );

			if ( count( $arf_form_data ) > 0 ) {
				foreach ( $arf_form_data as $fieldid => $fieldvalue ) {
					$_POST[ $fieldid ] = $fieldvalue;
				}
			}

			$formRandomKey = isset( $_POST['form_random_key'] ) ? sanitize_text_field( $_POST['form_random_key'] ) : ''; //phpcs:ignore
			$validate      = true;
			$is_check_spam = true;

			if ( $is_check_spam ) {
				$validate = apply_filters( 'arflite_is_to_validate_spam_filter', $validate, $formRandomKey );
			}
			if ( ! $validate ) {
				$return['conf_method'] = 'spamerror';
				$message               = '<div class="arf_form arflite_main_div_{arf_form_id} arf_error_wrapper" id="arffrm_{arf_form_id}_container"><div class="frm_error_style" id="arf_message_error"><div class="msg-detail"><div class="arf_res_front_msg_desc">' . __( 'Spam Detected', 'arforms-form-builder' ) . '</div></div></div></div>';
				$return['message']     = $message;
				$return                = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore
				echo json_encode( $return );
				exit;
			}
		} elseif ( ! isset( $_REQUEST['using_ajax'] ) || ( isset( $_REQUEST['using_ajax'] ) && strtolower( trim( sanitize_text_field( $_POST['using_ajax'] ) ) ) != 'yes' ) ) { //phpcs:ignore
			if ( $submit_type != 0 ) {
				$this->arflite_ajax_check_spam_filter();
			}
		}

		if ( ! isset( $arf_errors ) ) {
			$arf_errors = array();
		}
		if ( empty( $arflite_errors ) && @count( $arf_errors ) == 0 ) {

			$_POST['arfentrycookie'] = 1;

			if ( $params['action'] == 'create' ) {

				if ( apply_filters( 'arflitecontinuetocreate', true, intval( $_POST['form_id'] ) ) && ! isset( $arflitecreatedentry[ intval( $_POST['form_id'] ) ]['entry_id'] ) ) { //phpcs:ignore
					$arflitecreatedentry[ intval( $_POST['form_id'] ) ]['entry_id'] = $arflite_db_record->arflitecreate( $_POST ); //phpcs:ignore 
				}
			}

			$item_meta_values = isset( $_POST['item_meta'] ) ? array_map( 'sanitize_text_field', $_POST['item_meta'] ) : array(); //phpcs:ignore

			$item_meta_values = $arflite_db_record->arflitecreate( $_POST, true ); //phpcs:ignore
 
			do_action( 'arfliteentryexecute', $params, $arflite_errors, $form, $item_meta_values ); //phpcs:ignore
			unset( $_POST['arfentrycookie'] ); //phpcs:ignore
		} else {
			global $arformsmain;
			$failed_msg = $arformsmain->arforms_get_settings('failed_msg','general_settings');
			$failed_msg = !empty( $failed_msg ) ? $failed_msg : addcslashes(esc_html('We\'re sorry. Form is not submitted successfully.','arforms-form-builder'));
			if ( $arf_errors ) {

				$return['conf_method'] = 'validationerror';
				$return['message']     = $arf_errors;
				$arferrormsg           = $failed_msg;
				$return                = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore
				if ( isset( $_POST['form_submit_type'] ) && intval( $_POST['form_submit_type'] ) == 1 ) { //phpcs:ignore
					echo json_encode( $return );
					exit;
				}
			}

			if ( isset( $_POST['form_submit_type'] ) && intval( $_POST['form_submit_type'] ) == 1 ) { //phpcs:ignore
				exit;
			} else {
				do_shortcode( '[ARForms id=' . intval( $_POST['form_id'] ) . " arfsubmiterrormsg='" . $arferrormsg . "' ]" ); //phpcs:ignore
			}
		}

		if ( isset( $_POST['using_ajax'] ) && sanitize_text_field( $_POST['using_ajax'] ) == 'yes' ) { //phpcs:ignore
			echo do_shortcode( '[ARForms id=' . intval( $_POST['form_id'] ) . ']' ); //phpcs:ignore
		}
	}

	function arflitemenu() {

		global $arflitemainhelper;

		if ( current_user_can( 'administrator' ) && ! current_user_can( 'arfviewentries' ) ) {

			global $wp_roles;

			$arfroles = $arflitemainhelper->arflite_frm_capabilities();

			foreach ( $arfroles as $arfrole => $arfroledescription ) {

				if ( ! in_array( $arfrole, array( 'arfviewforms', 'arfeditforms', 'arfdeleteforms', 'arfchangesettings', 'arfimportexport', 'arfviewpopupform' ) ) ) {
					$wp_roles->add_cap( 'administrator', $arfrole );
				}
			}
		}

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite' . ' | ' . __( 'Form Entries', 'arforms-form-builder' ), __( 'Form Entries', 'arforms-form-builder' ), 'arfviewentries', 'ARForms-Lite-entries', array( $this, 'arfliteroute' ) );

		add_action( 'admin_head-' . 'ARForms' . '_page_ARForms-Lite-entries', array( $this, 'arflitehead' ) );
	}

	function arflitehead() {

		global $arflite_style_settings, $arflitemainhelper;

		$css_file = array( $arflitemainhelper->arflite_jquery_css_url( $arflite_style_settings->arfcalthemecss ) );

		require ARFLITE_VIEWS_PATH . '/arflite_head.php';
	}

	function arflite_admin_js() {

		if ( isset( $_GET ) && isset( $_GET['page'] ) && ( 'ARForms-popups' == sanitize_text_field( $_GET['page'] ) || sanitize_text_field( $_GET['page'] ) == 'ARForms-entries' || sanitize_text_field( $_GET['page'] ) == 'ARForms-entry-templates' || sanitize_text_field( $_GET['page'] ) == 'ARForms-import' || ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] == 'ARForms') ) && ( ( isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) == 'edit' ) || ( isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) == 'new' ) || ( isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) ) == 'duplicate' || ( isset( $_REQUEST['arfaction'] ) && sanitize_text_field( $_REQUEST['arfaction'] ) == 'update' ) ) ) ) {

			if ( ! function_exists( 'wp_editor' ) ) {

				add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );

				add_filter( 'tiny_mce_before_init', array( $this, 'arfliteremove_fullscreen' ) );

				if ( user_can_richedit() ) {

					wp_enqueue_script( 'editor' );

					wp_enqueue_script( 'media-upload' );
				}

				wp_enqueue_script( 'common' );

				wp_enqueue_script( 'post' );
			}

			if ( 'ARForms-popups' == sanitize_text_field( $_GET['page'] ) || sanitize_text_field( $_GET['page'] ) == 'ARForms-entries' || ( isset($_REQUEST['page']) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms') && ( sanitize_text_field( $_REQUEST['arfaction'] ) == 'edit' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'new' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'duplicate' || sanitize_text_field( $_REQUEST['arfaction'] ) == 'update' ) ) {
				wp_enqueue_script( 'bootstrap-moment-with-locales' );
				wp_enqueue_script( 'bootstrap-datetimepicker' );
			}
		}
	}

	function arfliteremove_fullscreen( $init ) {

		if ( isset( $init['plugins'] ) ) {

			$init['plugins'] = str_replace( 'wpfullscreen,', '', $init['plugins'] );

			$init['plugins'] = str_replace( 'fullscreen,', '', $init['plugins'] );
		}

		return $init;
	}

	function arflite_register_scripts() {

		global $wp_scripts, $arflitemainhelper, $arfliteversion;
		wp_register_script( 'bootstrap-moment-with-locales', ARFLITEURL . '/bootstrap/js/moment-with-locales.js', array( 'jquery' ), $arfliteversion );

		wp_register_script( 'bootstrap-datetimepicker', ARFLITEURL . '/bootstrap/js/bootstrap-datetimepicker.js', array( 'jquery' ), $arfliteversion, true );
	}


	function &arflite_filter_email_value( $value, $meta, $entry, $atts = array() ) {
		global $arflitefield;
		$field = $arflitefield->arflitegetOne( $meta->field_id );
		if ( ! $field ) {
			return $value;
		}
		$value = $this->arflite_filter_entry_display_value( $value, $field, $atts );
		return $value;
	}

	function arflite_footer_js( $arflite_preview = false, $is_print = false, $force_load = false, $is_return = false ) {

		global $wp_version, $is_gutenberg, $arformsmain;
		$path = !empty( $_SERVER['REQUEST_URI'] ) ? esc_url($_SERVER['REQUEST_URI']) : ''; //phpcs:ignore
		$file_path = basename( $path );

		$load_data = true;

		if ( strstr( $file_path, 'post.php' ) ) {
			if ( ! empty( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) {
				$force_load = true;
				$is_print   = true;
			} else {
				$load_data = false;
			}
		} else {
			if ( ! empty( $_REQUEST['action'] ) && 'elementor' == $_REQUEST['action'] ) {
				$force_load = true;
				$is_print   = true;
			}
		}

		if ( (isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || ( !empty( $_POST['context'] ) && 'edit' == $_POST['context'] ) ) { //phpcs:ignore
			$force_load = true;
			$is_print   = true;
		}

		if ( $load_data && !$arformsmain->arforms_is_pro_active()) {
			global $arfliteversion, $arflite_forms_loaded, $arflite_form_all_footer_js, $arflite_is_arf_preview, $arflite_form_all_footer_css, $arformsmain;

			$arflite_is_multi_column_loaded = array();

			if ( empty( $arflite_forms_loaded ) ) {
				return;
			}

			if ( $is_print ) {
				$print_style  = 'wp_print_styles';
				$print_script = 'wp_print_scripts';
			} else {
				$print_style  = 'wp_enqueue_style';
				$print_script = 'wp_enqueue_script';
			}
			$arf_load_js_css = $arformsmain->arforms_get_settings('arf_load_js_css','general_settings');
			$load_js_css = !empty( $arf_load_js_css ) ? json_decode($arf_load_js_css, true) : array();
			
			foreach ( $arflite_forms_loaded as $form ) {

				if ( ! is_object( $form ) ) {
					continue;
				}

				if ( is_ssl() ) {
					$upload_main_url = str_replace( 'http://', 'https://', ARFLITE_UPLOAD_URL . '/maincss' );
				} else {
					$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
				}

				$upload_main_url = esc_url_raw( $upload_main_url );

				$fid1 = $upload_main_url . '/maincss_' . $form->id . '.css';

				if ( ! $force_load || isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
					$print_script( 'jquery' );
					$print_script( 'jqbootstrapvalidation' );
				}

				$print_script( 'bootstrap' );

				$form->options = maybe_unserialize( $form->options );

				if ( ( isset( $form->options['font_awesome_loaded'] ) && $form->options['font_awesome_loaded'] ) || in_array( 'fontawesome', $load_js_css ) ) {
					$print_style( 'arflite-font-awesome' );
				}

				if ( ( isset( $form->options['tooltip_loaded'] ) && $form->options['tooltip_loaded'] ) || in_array( 'tooltip', $load_js_css ) ) {
					$print_style( 'tipso' );
					$print_script( 'tipso' );
				}
				if ( ( isset( $form->options['arf_input_mask'] ) && $form->options['arf_input_mask'] ) || in_array( 'mask_input', $load_js_css ) ) {
					$print_script( 'bootstrap-inputmask' );
					$print_script( 'jquery-maskedinput' );
					$print_script( 'intltelinput' );
					$print_script( 'arformslite_phone_utils' );
				}

				if ( ( isset( $form->options['arf_number_animation'] ) && $form->options['arf_number_animation'] ) || in_array( 'animate_number', $load_js_css ) ) {
					$print_script( 'jquery-animatenumber' );
				}

				$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

				if ( in_array( 'select', $loaded_field ) || in_array( 'dropdown', $load_js_css ) ) {
					$print_style( 'arformslite_selectpicker' );
					$print_script( 'arformslite_selectpicker' );
				}
				if ( in_array( 'time', $loaded_field ) || in_array( 'date', $loaded_field ) || in_array( 'date_time', $load_js_css ) ) {

					$css_file = ARFLITEURL . '/bootstrap/css/bootstrap-datetimepicker.css';
					$print_script( 'bootstrap-moment-with-locales' );
					$print_style( 'bootstrap-datetimepicker' );
					$print_script( 'bootstrap-datetimepicker' );
				}

				if ( isset( $form->options['arf_enable_recaptcha'] ) ) {

					if ( ( $form->options['arf_enable_recaptcha'] == 1 ) || ( in_array( 'captcha', $loaded_field ) || in_array( 'captcha', $load_js_css ) ) ) {
						$print_script( 'recaptcha-ajax' );
						$print_style( 'arfliterecaptchacss' );
						do_action( 'wp_arflite_recaptcha_check', $form->options['arf_enable_recaptcha'] );
					}
				}

				do_action( 'wp_arflite_footer', $loaded_field );
				if ( ! is_admin() ) {
					if( false == $is_gutenberg ){
						$print_script( 'wp-hooks' );
					}
					$print_script( 'arformslite_hooks' );
					$print_script( 'arformslite-js' );
				}
			}
			$arflite_front_script_data = "
                \"use strict\";
                var __ARFLITESCRIPTURL = '" . ARFLITESCRIPTURL . "';
                var __ARFLITE_FILE_ERROR = '" . addslashes( __( 'Sorry, this file type is not permitted for security reasons.', 'arforms-form-builder' ) ) . "';
                var __ARFLITE_NO_FILE_SELECTED = '" . addslashes( __( 'No File Selected', 'arforms-form-builder' ) ) . "';
                var __ARF_BLANKMSG = '" . addslashes( __( 'This field cannot be blank', 'arforms-form-builder' ) ) . "';
            ";

			wp_add_inline_script( 'arformslite-js', $arflite_front_script_data );

			$script_data = '';

			if ( $arflite_is_multi_column_loaded ) {
				$arflite_is_multi_column_loaded = array_unique( $arflite_is_multi_column_loaded );
			}
			if ( is_rtl() && count( $arflite_is_multi_column_loaded ) > 0 ) {
				$form_str = '';
				foreach ( $arflite_is_multi_column_loaded as $multicol_forms ) {
					$form_str .= '#form_' . $multicol_forms . ', ';
				}
				$form_str = rtrim( $form_str, ', ' );

				$script_data .= '<input type="hidden" class="arflite_front_form_str_rtl" value="' . esc_attr( $form_str ) . '" />';
			}

			$arflite_preview = isset( $arflite_is_arf_preview ) ? $arflite_is_arf_preview : 0;

			$script_data .= '<input type="hidden" class="arlite_is_preview" value="' . intval( esc_attr( $arflite_preview ) ) . '" />';

			if ( $arflite_preview == true ) {
				$script_data .= '<input type="hidden" class="arflite_preview_form" value="' . esc_attr( base64_encode( json_encode( $arflite_forms_loaded[0] ) ) ) . '" />';
			}

			if ( isset( $arflite_form_all_footer_js ) ) {

				$arflite_front_footer_script      = 'function arflite_initialize_control_js(){';
					$arflite_form_all_footer_js   = apply_filters( 'arflite_footer_javascript_from_outside', $arflite_form_all_footer_js );
					$arflite_front_footer_script .= $arflite_form_all_footer_js;
				$arflite_front_footer_script     .= '}';

				wp_add_inline_script( 'arformslite-js', $arflite_front_footer_script, 'before' );

				if ( $arflite_preview ) {
					if( false == $is_return ){	
						wp_print_scripts( 'arformslite-js' );
					} else {
						wp_enqueue_script( 'arformslite-js' );
					}
				}
			}
			if ( isset( $arflite_form_all_footer_css ) ) {
				wp_add_inline_style( 'arflitedisplayfootercss', $arflite_form_all_footer_css );
				if ( $arflite_preview ) {
					wp_print_styles( 'arflitedisplayfootercss' );
				} else {
					$print_style( 'arflitedisplayfootercss' );
				}
			}

			$script_data .= '<input type="hidden" class="arflite_script_url" value="' . esc_url( ARFLITESCRIPTURL ) . '" />';

			$script_data .= '<input type="hidden" class="arflite_file_error" value="' . esc_html__( 'Sorry, this file type is not permitted for security reasons', 'arforms-form-builder' ) . '" />';

			$script_data .= '<input type="hidden" class="arflite_no_file_selected" value="' . esc_html__( 'No File Selected', 'arforms-form-builder' ) . '" />';

			$script_data .= '<input type="hidden" class="arflite_blank_field_message" value="' . esc_html__( 'This field cannot be blank', 'arforms-form-builder' ) . '" />';

			$arfmainformloadjscss = $arformsmain->arforms_get_settings('arfmainformloadjscss','general_settings');
			$arfmainformloadjscss = !empty( $arfmainformloadjscss ) ? $arfmainformloadjscss : 0;
			if ( isset( $arfmainformloadjscss ) && 1 == $arfmainformloadjscss ) {

				$arflite_front_footer_force_script = '"use strict";';

				$arflite_front_footer_force_script .= 'setTimeout( function(){';

					$arflite_front_footer_force_script .= "var formLength = document.getElementsByClassName('allfields');";

					$arflite_front_footer_force_script .= 'for (var f = 0; f < formLength.length; f++) {';

						$arflite_front_footer_force_script .= "formLength[f].removeAttribute('style');";

					$arflite_front_footer_force_script .= '}';

					$arflite_front_footer_force_script .= 'arflite_initialize_control_js();';

					$arflite_front_footer_force_script .= 'arflite_initialize_form_control_onready(jQuery);';

					$arflite_front_footer_force_script .= 'render_force_arflite_captcha();';

					$arflite_front_footer_force_script .= $arflite_front_footer_script;

				$arflite_front_footer_force_script .= '},100);';

				wp_add_inline_script( 'arformslite-js', $arflite_front_footer_force_script );

			}

			if ( $force_load ) {
				$script_data .= '<script defer id="arflite_temp_script" type="text/javascript">';
					$script_data .= 'var arflite_force_load = (function($){
                        var ARFormsliteApp = {
                            init:function(){
                                var is_elementor = document.getElementsByClassName("arf_is_elementor")[0].value;
                                var formLength = document.getElementsByClassName("allfields");
                                setTimeout(function() {
                                    for (var f = 0; f < formLength.length; f++) {
                                        formLength[f].removeAttribute("style");
                                    }
                                    arflite_material_style_init();';
									$script_data .= $arflite_front_footer_script; //phpcs:ignore
                                    $script_data .= 'arflite_initialize_control_js();
									if( is_elementor != 1 ){'; 
										if ( ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ) {
											$script_data .= 'arflite_initialize_form_control_onready();';
											$script_data .= 'arflite_init_bootstrap_validation();';
										}
								$script_data .= '}}, 500);
                            }';
						$script_data .= '};
                        return ARFormsliteApp;
                    } (jQuery));
                    arflite_force_load.init();';
				$script_data .= '</script>';
			}

			if( true == $is_return ){
				return $script_data;
			} else {
				echo $script_data; //phpcs:ignore
			}
			return;
		}
	}

	function arflite_list_entries() {

		$params = $this->arflite_get_params();

		return $this->arflitedisplay_list( $params );
	}

	function arflitecreate() {

		global $arfliteform, $arflite_db_record;

		$params = $this->arflite_get_params();

		if ( $params['form'] ) {
			$form = $arfliteform->arflitegetOne( $params['form'] );
		}

		$arflite_errors = $arflite_db_record->arflitevalidate( $_POST ); //phpcs:ignore

		if ( count( $arflite_errors ) > 0 ) {

			$this->arflite_get_new_vars( $arflite_errors, $form );
		} else {

			if ( isset( $_POST[ 'arfpageorder' . $form->id ] ) ) { //phpcs:ignore

				$this->arflite_get_new_vars( '', $form );
			} else {

				$arfrequest_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : ''; //phpcs:ignore
				$_SERVER['REQUEST_URI'] = str_replace( '&arfaction=new', '', $arfrequest_uri );

				$record = $arflite_db_record->arflitecreate( $_POST ); //phpcs:ignore

				if ( $record ) {
					$message = __( 'Entry is Successfully Created', 'arforms-form-builder' );
				}

				$this->arflitedisplay_list( $params, $message, '', 1 );
			}
		}
	}

	function arflitedestroy() {

		if ( ! current_user_can( 'arfdeleteentries' ) ) {

			global $arformsmain;

			$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
			$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

			wp_die( $admin_permission ); //phpcs:ignore
		}

		global $arflite_db_record, $arfliteform;

		$params = $this->arflite_get_params();

		if ( $params['form'] ) {
			$form = $arfliteform->arflitegetOne( $params['form'] );
		}

		$message = '';

		if ( $arflite_db_record->arflitedestroy( $params['id'] ) ) {
			$message = __( 'Entry is Successfully Deleted', 'arforms-form-builder' );
		}

		$this->arflitedisplay_list( $params, $message, '', 1 );
	}

	function arflitedestroy_all() {

		if ( ! current_user_can( 'arfdeleteentries' ) ) {

			global $arformsmain;

			$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
			$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

			wp_die( $admin_permission ); //phpcs:ignore
		}

		global $arflite_db_record, $arfliteform, $ARFLiteMdlDb, $tbl_arf_entries;

		$params = $this->arflite_get_params();

		$message = '';

		$arflite_errors = array();

		if ( $params['form'] ) {

			$form = $arfliteform->arflitegetOne( $params['form'] );

			$entry_ids = $ARFLiteMdlDb->arfliteget_col( $tbl_arf_entries, array( 'form_id' => $form->id ) );

			foreach ( $entry_ids as $entry_id ) {

				if ( $arflite_db_record->arflitedestroy( $entry_id ) ) {
					$message = __( 'Entries were Successfully Destroyed', 'arforms-form-builder' );
				}
			}
		} else {

			$arflite_errors = __( 'No entries were specified', 'arforms-form-builder' );
		}

		$this->arflitedisplay_list( $params, $message, '', 0, $arflite_errors );
	}

	function arflitebulk_actions( $action = 'list-form' ) {

		global $arflite_db_record, $arflitemainhelper;

		$params = $this->arflite_get_params();

		$arflite_errors = array();

		$bulkaction = '-1';

		if ( $action == 'list-form' ) {

			if ( isset( $_REQUEST['bulkaction'] ) && $_REQUEST['bulkaction'] != '-1' ) {
				$bulkaction = !empty( $_REQUEST['bulkaction']) ? sanitize_text_field( $_REQUEST['bulkaction'] ) : '';
			} elseif ( isset( $_POST['bulkaction2'] ) && $_POST['bulkaction2'] != '-1' ) { //phpcs:ignore
				$bulkaction = !empty( $_REQUEST['bulkaction2'] ) ? sanitize_text_field( $_REQUEST['bulkaction2'] ) : '';
			}
		} else {

			$bulkaction = str_replace( 'bulk_', '', $action );
		}

		$items = $arflitemainhelper->arflite_get_param( 'item-action', '' );

		if ( empty( $items ) ) {

			$arflite_errors[] = __( 'Please select one or more records.', 'arforms-form-builder' );
		} else {

			if ( ! is_array( $items ) ) {
				$items = explode( ',', $items );
			}

			if ( $bulkaction == 'delete' ) {

				if ( ! current_user_can( 'arfdeleteentries' ) ) {

					global $arformsmain;
					$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
					$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

					$arflite_errors[] = $admin_permission;
				} else {

					if ( is_array( $items ) ) {

						foreach ( $items as $entry_id ) {
							$arflite_db_record->arflitedestroy( $entry_id );
						}
					}
				}
			} elseif ( $bulkaction == 'csv' ) {

				if ( ! current_user_can( 'arfviewentries' ) ) {
					global $arformsmain;
					$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
					$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

					wp_die( $admin_permission ); //phpcs:ignore
				}

				global $arfliteform;

				$form_id = $params['form'];

				if ( $form_id ) {

					$form = $arfliteform->arflitegetOne( $form_id );
				} else {

					$form = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1' );

					if ( $form ) {
						$form_id = $form->id;
					} else {
						$arflite_errors[] = __( 'No form is found', 'arforms-form-builder' );
					}
				}

				if ( $form_id && is_array( $items ) ) {

					echo '<script type="text/javascript" data-cfasync="false">window.onload=function(){location.href="' . site_url() . '/index.php?plugin=ARFormslite&controller=entries&form=' . $form_id . '&arfaction=csv&entry_id=' . implode( ',', $items ) . '";}</script>'; //phpcs:ignore
				}
			}
		}

		$this->arflitedisplay_list( $params, '', false, false, $arflite_errors );
	}

	function arflite_process_update_entry( $params, $arflite_errors, $form, $final_input_meta ) {

		global $arflite_db_record, $arflitesavedentries, $arflitecreatedentry, $arformsmain;

		$form->options = stripslashes_deep( maybe_unserialize( $form->options ) );

		if ( $params['action'] == 'update' && in_array( (int) $params['id'], (array) $arflitesavedentries ) ) {
			return;
		}

		if ( $params['action'] == 'create' && isset( $arflitecreatedentry[ $form->id ] ) && isset( $arflitecreatedentry[ $form->id ]['entry_id'] ) && is_numeric( $arflitecreatedentry[ $form->id ]['entry_id'] ) ) {

			$entry_id = $params['id'] = $arflitecreatedentry[ $form->id ]['entry_id'];

			$conf_method = apply_filters( 'arfliteformsubmitsuccess', 'message', $form, $form->options );

			$return_script    = '';
			$return['script'] = apply_filters( 'arflite_after_submit_sucess_outside', $return_script, $form );

			if ( $conf_method == 'redirect' ) {

				$success_url = apply_filters( 'arflitecontent', $form->options['success_url'], $form, $entry_id );
				global $arformsmain;
				$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type', 'general_settings');
				$form_submit_type = isset( $form_submit_type ) ? $form_submit_type : 1;

				if ( $success_url == false ) {
					
					$success_msg = $arformsmain->arforms_get_settings('success_msg','general_settings');
					$success_msg = !empty( $success_msg ) ? $success_msg : esc_html__('Form is successfully submitted. Thank you!','arforms-form-builder');

					$message               = '<div class="arf_form arflite_main_div_' . $form->id . '" id="arffrm_' . $form->id . '_container"><div  id="arf_message_success"><div class="msg-detail"><div class="msg-description-success">' . $success_msg . '</div></div></div>';
					$message              .= do_action( 'arflite_after_success_massage', $form );
					$message              .= '</div>';
					$return['conf_method'] = 'message';
					$return['message']     = $message;
					$return                = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore 
					if ( $form_submit_type == 1 ) { //phpcs:ignore
						echo json_encode( $return );
						exit;
					}
				} elseif ( $form_submit_type == 1 ) {

					$return['conf_method'] = 'redirect';
					$return['message']     = $success_url;

					$return = apply_filters( 'arflite_reset_built_in_captcha', $return, $_POST ); //phpcs:ignore
					echo json_encode( $return );
					exit;
				} else {
					wp_redirect( $success_url );
					exit;
				}
			}
		} elseif ( $params['action'] == 'destroy' ) {

			$this->arflite_ajax_destroy( $form->id, false, false );
		}
	}

	function arflite_ajax_submit_button( $arf_form, $form, $action = 'create' ) {

		global $arflitenovalidate;

		if ( $arflitenovalidate ) {
			$arf_form .= ' formnovalidate="formnovalidate"';
		}

		return $arf_form;
	}

	function arflite_get_confirmation_method( $method, $form ) {

		$method = ( isset( $form->options['success_action'] ) && ! empty( $form->options['success_action'] ) ) ? $form->options['success_action'] : $method;

		return $method;
	}

	function arflitecsv( $all_form_id, $search = '', $fid = '' ) {

		if ( ! current_user_can( 'arfviewentries' ) ) {

			global $arformsmain;

			$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
			$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

			wp_die( $admin_permission ); //phpcs:ignore
		}

		if ( ! ini_get( 'safe_mode' ) ) {

			@set_time_limit( 0 );
		}

		global $current_user, $arfliteform, $arflitefield, $arflite_db_record, $arfliterecordmeta, $wpdb, $arflite_style_settings;

		require ARFLITE_VIEWS_PATH . '/arflite_export_data.php';
	}

	function arflitedisplay_list( $params = false, $message = '', $page_params_ov = false, $current_page_ov = false, $arflite_errors = array() ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $arfliteform, $arflite_db_record, $arfliterecordmeta, $arflitepagesize, $arflitefield, $arflitecurrentform,$arfliteformcontroller, $tbl_arf_forms;

		if ( ! $params ) {
			$params = $this->arflite_get_params();
		}
		$arflite_errors = array();

		$form_select = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name' );

		if ( $params['form'] ) {
			$form = $arfliteform->arflitegetOne( $params['form'] );
		} else {
			$form = ( isset( $form_select[0] ) ) ? $form_select[0] : 0;
		}

		if ( $form ) {
			$params['form']     = $form->id;
			$arflitecurrentform = $form;
			$where_clause       = " it.form_id=$form->id";
		} else {
			$where_clause = '';
		}

		$page_params = '&action=0&arfaction=0&form=';

		$page_params .= ( $form ) ? $form->id : 0;

		if ( ! empty( $_REQUEST['s'] ) ) {
			$page_params .= '&s=' . urlencode( sanitize_text_field( $_REQUEST['s'] ) );
		}

		if ( ! empty( $_REQUEST['search'] ) ) {
			$page_params .= '&search=' . urlencode( sanitize_text_field( $_REQUEST['search'] ) );
		}

		if ( ! empty( $_REQUEST['fid'] ) ) {
			$page_params .= '&fid=' . intval( $_REQUEST['fid'] );
		}

		$item_vars = $this->arflite_get_sort_vars( $params, $where_clause );

		$page_params .= ( $page_params_ov ) ? $page_params_ov : $item_vars['page_params'];

		$form_cols_order      = array();
		$arfinnerfieldorder   = array();
		$form_inner_col_order = array();
		$arffieldorder        = array();
		$form_css             = array();

		if ( $form ) {

			$form_cols_temp = array();

			$form_cols = $arflitefield->arflitegetAll( "fi.type not in ('captcha', 'html') and fi.form_id=" . (int) $form->id, ' ORDER BY id' );

			$form_options = $wpdb->get_row( $wpdb->prepare( 'SELECT `form_css`,`options` FROM `' . $tbl_arf_forms . '` WHERE `id` = %d', (int) $form->id ) ); //phpcs:ignore

			if ( isset( $form_options->form_css ) && $form_options->form_css != '' ) {
				$form_css = maybe_unserialize( $form_options->form_css );
			}

			if ( isset( $form_options->options ) && $form_options->options != '' ) {

				$form_options = maybe_unserialize( $form_options->options );

				if ( isset( $form_options['arf_field_order'] ) && $form_options['arf_field_order'] != '' ) {
					$form_cols_order = json_decode( $form_options['arf_field_order'], true );
					asort( $form_cols_order );
					$arffieldorder = $form_cols_order;
				}

				if ( isset( $form_options['arf_inner_field_order'] ) && $form_options['arf_inner_field_order'] != '' ) {
					$form_inner_col_order = json_decode( $form_options['arf_inner_field_order'], true );
					$arfinnerfieldorder   = $form_inner_col_order;
				}
			}

			foreach ( $arffieldorder as $fieldkey => $fieldorder ) {
				foreach ( $form_cols as $frmoptkey => $frmoptarr ) {
					if ( $frmoptarr->id == $fieldkey ) {
						$form_cols_temp[] = $frmoptarr;

						unset( $form_cols[ $frmoptkey ] );
					}
				}
			}

			if ( count( $form_cols_temp ) > 0 ) {
				if ( count( $form_cols ) > 0 ) {
					$form_cols_other = $form_cols;
					$form_cols       = array_merge( $form_cols_temp, $form_cols_other );
				} else {
					$form_cols = $form_cols_temp;
				}
			}

			$record_where = ( $item_vars['where_clause'] == " it.form_id=$form->id" ) ? $form->id : $item_vars['where_clause'];
		} else {

			$form_cols = array();

			$record_where = $item_vars['where_clause'];
		}

		$current_page = ( $current_page_ov ) ? $current_page_ov : $params['paged'];

		$sort_str = $item_vars['sort_str'];

		$sdir_str = $item_vars['sdir_str'];

		$search_str = $item_vars['search_str'];

		$fid = $item_vars['fid'];

		$record_count = $arflite_db_record->arflitegetRecordCount( $record_where );

		$page_count = $arflite_db_record->arflitegetPageCount( $arflitepagesize, $record_count );

		$items = $arflite_db_record->arflitegetPage( '', '', $item_vars['where_clause'], $item_vars['order_by'], '', $arffieldorder );

		$page_last_record = $arflitemainhelper->arflitegetLastRecordNum( $record_count, $current_page, $arflitepagesize );

		$page_first_record = $arflitemainhelper->arflitegetFirstRecordNum( $record_count, $current_page, $arflitepagesize );

		if ( isset( $_REQUEST['form'] ) && $_REQUEST['form'] == '-1' || ( ! isset( $_REQUEST['form'] ) || empty( $_REQUEST['form'] ) ) ) {
			$form_cols = array();
			$items     = array();
		}

		require_once ARFLITE_VIEWS_PATH . '/arf_view_records.php';
	}

	function arflite_get_sort_vars( $params = false, $where_clause = '' ) {

		global $arfliterecordmeta, $arflitecurrentform;

		if ( ! $params ) {
			$params = $this->arflite_get_params( $arflitecurrentform );
		}

		$order_by = '';

		$page_params = '';

		$sort_str = $params['sort'];

		$sdir_str = $params['sdir'];

		$search_str = $params['search'];

		$fid = $params['fid'];

		if ( ! empty( $sort_str ) ) {
			$page_params .= "&sort=$sort_str";
		}

		if ( ! empty( $sdir_str ) ) {
			$page_params .= "&sdir=$sdir_str";
		}

		if ( ! empty( $search_str ) ) {

			$where_clause = $this->arflite_get_search_str( $where_clause, $search_str, $params['form'], $fid );

			$page_params .= "&search=$search_str";

			if ( is_numeric( $fid ) ) {
				$page_params .= "&fid=$fid";
			}
		}

		if ( is_numeric( $sort_str ) ) {
			$order_by .= ' ORDER BY ID';

		} elseif ( $sort_str == 'entry_key' ) {
			$order_by .= ' ORDER BY entry_key';
		} else {
			$order_by .= ' ORDER BY ID';
		}

		if ( ( empty( $sort_str ) && empty( $sdir_str ) ) || $sdir_str == 'desc' ) {

			$order_by .= ' DESC';

			$sdir_str = 'desc';
		} else {

			$order_by .= ' ASC';

			$sdir_str = 'asc';
		}

		return compact( 'order_by', 'sort_str', 'sdir_str', 'fid', 'search_str', 'where_clause', 'page_params' );
	}

	function arflite_get_search_str( $where_clause, $search_str, $form_id, $fid ) {

		global $arfliterecordmeta, $arflitemainhelper, $arfliteform, $wpdb;

		$where_item = '';

		$join = ' (';

		if ( ! is_array( $search_str ) ) {
			$search_str = explode( ' ', $search_str );
		}

		foreach ( $search_str as $search_param ) {

			$search_param = esc_sql( $wpdb->esc_like( $search_param ) );

			if ( ! is_numeric( $fid ) ) {

				$where_item .= ( empty( $where_item ) ) ? ' (' : ' OR';

				if ( in_array( $fid, array( 'created_date', 'user_id' ) ) ) {

					if ( $fid == 'user_id' && ! is_numeric( $search_param ) ) {
						$search_param = $arflitemainhelper->arflite_get_user_id_param( $search_param );
					}

					$where_item .= " it.{$fid} like '%$search_param%'";
				} else {

					$where_item .= " it.name like '%$search_param%' OR it.entry_key like '%$search_param%' OR it.description like '%$search_param%' OR it.created_date like '%$search_param%'";
				}
			}

			if ( empty( $fid ) || is_numeric( $fid ) ) {

				$where_entries = "(entry_value LIKE '%$search_param%'";

				if ( $data_fields = $arfliteform->arflite_has_field( 'data', $form_id, false ) ) {

					$df_form_ids = array();

					foreach ( (array) $data_fields as $df ) {

						$df->field_options = maybe_unserialize( $df->field_options );

						if ( is_numeric( $df->field_options['form_select'] ) ) {
							$df_form_ids[] = $df->field_options['form_select'];
						}

						unset( $df );
					}

					unset( $data_fields );

					global $wpdb, $tbl_arf_fields;

					$data_form_ids = $wpdb->get_col( "SELECT form_id FROM $tbl_arf_fields WHERE id in (" . implode( ',', $df_form_ids ) . ')' ); //phpcs:ignore

					unset( $df_form_ids );

					if ( $data_form_ids ) {

						$data_entry_ids = $arfliterecordmeta->arflitegetEntryIds( 'fi.form_id in (' . implode( ',', $data_form_ids ) . ") and entry_value LIKE '%" . $search_param . "%'" );

						if ( ! empty( $data_entry_ids ) ) {
							$where_entries .= ' OR entry_value in (' . implode( ',', $data_entry_ids ) . ')';
						}
					}

					unset( $data_form_ids );
				}

				$where_entries .= ')';

				if ( is_numeric( $fid ) ) {
					$where_entries .= " AND fi.id=$fid";
				}

				$meta_ids = $arfliterecordmeta->arflitegetEntryIds( $where_entries );

				if ( ! empty( $meta_ids ) ) {

					if ( ! empty( $where_clause ) ) {

						$where_clause .= ' AND' . $join;

						if ( ! empty( $join ) ) {
							$join = '';
						}
					}

					$where_clause .= ' it.id in (' . implode( ',', $meta_ids ) . ')';
				} else {

					if ( ! empty( $where_clause ) ) {

						$where_clause .= ' AND' . $join;

						if ( ! empty( $join ) ) {
							$join = '';
						}
					}

					$where_clause .= ' it.id=0';
				}
			}
		}

		if ( ! empty( $where_item ) ) {

			$where_item .= ')';

			if ( ! empty( $where_clause ) ) {
				$where_clause .= empty( $fid ) ? ' OR' : ' AND';
			}

			$where_clause .= $where_item;

			if ( empty( $join ) ) {
				$where_clause .= ')';
			}
		} else {

			if ( empty( $join ) ) {
				$where_clause .= ')';
			}
		}

		return $where_clause;
	}

	function arflite_get_new_vars( $arflite_errors = '', $form = '', $message = '' ) {

		global $arfliteform, $arflitefield, $arflite_db_record, $arformsmain, $arflitefieldhelper;

		$is_title = true;

		$description = true;

		$fields = $arflitefieldhelper->arflite_get_all_form_fields( $form->id, ! empty( $arflite_errors ) );

		$values = $arfliterecordhelper->arflite_setup_new_vars( $fields, $form );

		$submit_value = $arformsmain->arforms_get_settings('submit_value','general_settings');
		$submit_value = !empty( $submit_value ) ? $submit_value : esc_html__('submit','arforms-form-builder');

		$submit = isset( $values['submit_value'] ) ? $values['submit_value'] : $submit_value;

		require_once ARFLITE_VIEWS_PATH . '/arflite_new.php';
	}

	function arflite_get_params( $form = null ) {

		global $arfliteform, $arflitemainhelper;

		if ( ! $form ) {
			$form = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1' );
		}

		$values = array();

		foreach ( array(
			'id'        => '',
			'form_name' => '',
			'paged'     => 1,
			'form'      => ( ( $form ) ? $form->id : 0 ),
			'field_id'  => '',
			'search'    => '',
			'sort'      => '',
			'sdir'      => '',
			'fid'       => '',
		) as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
		}

		return $values;
	}

	function &arflitefilter_shortcode_value( $value, $tag, $atts, $field ) {

		if ( isset( $atts['show'] ) && $atts['show'] == 'value' ) {
			return $value;
		}

		$value = $this->arflite_filter_display_value( $value, $field );

		return $value;
	}

	function &arflite_filter_entry_display_value( $value, $field, $atts = array() ) {
		$field->field_options = maybe_unserialize( $field->field_options );
		$saved_value          = ( isset( $atts['saved_value'] ) && $atts['saved_value'] ) ? true : false;
		if ( ! in_array( $field->type, array( 'checkbox' ) ) || ! isset( $field->field_options['separate_value'] ) || ! $field->field_options['separate_value'] || $saved_value ) {
			return $value;
		}
		$field->options = maybe_unserialize( $field->options );
		$f_values       = array();
		$f_labels       = array();
		if ( is_array( $field->options ) ) {
			foreach ( $field->options as $opt_key => $opt ) {
				if ( ! is_array( $opt ) ) {
					continue;
				}
				$f_labels[ $opt_key ] = isset( $opt['label'] ) ? $opt['label'] : reset( $opt );
				$f_values[ $opt_key ] = isset( $opt['value'] ) ? $opt['value'] : $f_labels[ $opt_key ];
				if ( $f_labels[ $opt_key ] == $f_values[ $opt_key ] ) {
					unset( $f_values[ $opt_key ] );
					unset( $f_labels[ $opt_key ] );
				}
				unset( $opt_key );
				unset( $opt );
			}
		}
		if ( ! empty( $f_values ) ) {
			foreach ( (array) $value as $v_key => $val ) {
				if ( in_array( $val, $f_values ) ) {
					$opt = array_search( $val, $f_values );
					if ( is_array( $value ) ) {
						$value[ $v_key ] = $f_labels[ $opt ];
					} else {
						$value = $f_labels[ $opt ];
					}
				}
				unset( $v_key );
				unset( $val );
			}
		}
		return $value;
	}

	function &arflite_filter_display_value( $value, $field ) {
		global $arfliterecordcontroller;
		$value = $arfliterecordcontroller->arflite_filter_entry_display_value( $value, $field );

		return $value;
	}

	function arfliteroute() {

		global $arflitemainhelper;
		$action = $arflitemainhelper->arflite_get_param( 'arfaction' );

		if ( $action == 'create' ) {
			return $this->arflitecreate();

		} elseif ( $action == 'destroy' ) {
			return $this->arflitedestroy();

		} elseif ( $action == 'destroy_all' ) {
			return $this->arflitedestroy_all();

		} elseif ( $action == 'list-form' ) {
			return $this->arflitebulk_actions( $action );

		} else {
			$action = $arflitemainhelper->arflite_get_param( 'action' );

			if ( $action == -1 ) {
				$action = $arflitemainhelper->arflite_get_param( 'action2' );
			}

			if ( strpos( $action, 'bulk_' ) === 0 ) {

				//confirmwithazharsir
				$arfrequesturi = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( $_SERVER['REQUEST_URI'] ) : '';

				if ( isset( $_GET ) && isset( $_GET['action'] ) ) {
					$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action'] ), '', $arfrequesturi );
					//$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action'] ), '',sanitize_text_field( $_SERVER['REQUEST_URI'] ) );
				}
				if ( isset( $_GET ) && isset( $_GET['action2'] ) ) {
					$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action2'] ), '', $arfrequesturi );
				}

				return $this->arflitebulk_actions( $action );
			} else {
				return $this->arflitedisplay_list();
			}
		}
	}

	function arflite_get_form( $filename, $form, $is_title, $description, $arflite_preview = false, $is_widget_or_modal = false, $is_confirmation_method = false, $arflite_func_val = 'true' ) {
		global $arformsmain;
		if ( $arflite_func_val != 'true' ) {
			echo esc_html($arflite_func_val);
			exit;
		}
		$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
		$form_submit_type = isset($form_submit_type) ? $form_submit_type : 1;
		if ( $form_submit_type != 1 ) {
			wp_print_styles( 'arflitedisplaycss' );
			wp_print_scripts( 'jqbootstrapvalidation' );
			wp_print_scripts( 'bootstrap' );
		}

		if ( is_file( $filename ) ) {

			ob_start();

			include $filename;

			$contents = ob_get_contents();

			ob_end_clean();

			return $contents;
		}

		return false;
	}

	function arflite_ajax_create() {

		global $arflite_db_record;

		$arflite_errors = $arflite_db_record->arflitevalidate( $_POST, array( 'file' ) ); //phpcs:ignore

		if ( empty( $arflite_errors ) ) {

			echo false;
		} else {

			$arflite_errors = str_replace( '"', '&quot;', stripslashes_deep( $arflite_errors ) );

			$obj = array();

			foreach ( $arflite_errors as $field => $error ) {

				$field_id = str_replace( 'field', '', $field );

				$obj[ $field_id ] = $error;
			}

			echo json_encode( $obj );
		}

		die();
	}

	function arflite_ajax_update() {

		return $this->arflite_ajax_create();
	}

	function arflite_ajax_destroy( $form_id = false, $ajax = true, $echo = true ) {

		global $user_ID, $ARFLiteMdlDb, $arflite_db_record, $arflitedeletedentries, $arflitemainhelper, $tbl_arf_entries;

		$entry_key = $arflitemainhelper->arflite_get_param( 'entry' );

		if ( ! $form_id ) {
			$form_id = $arflitemainhelper->arflite_get_param( 'form_id' );
		}

		if ( ! $entry_key ) {
			return;
		}

		if ( is_array( $arflitedeletedentries ) && in_array( $entry_key, $arflitedeletedentries ) ) {
			return;
		}

		$where = array();

		if ( ! current_user_can( 'arfdeleteentries' ) ) {
			$where['user_id'] = $user_ID;
		}

		if ( is_numeric( $entry_key ) ) {
			$where['id'] = $entry_key;
		} else {
			$where['entry_key'] = $entry_key;
		}

		$entry = $ARFLiteMdlDb->arflite_get_one_record( $tbl_arf_entries, $where, 'id, form_id' );

		if ( $form_id && $entry->form_id != (int) $form_id ) {
			return;
		}
		$entry_id = $entry->id;

		apply_filters( 'arfliteallowdelete', $entry_id, $entry_key, $form_id );

		if ( ! $entry_id ) {

			$message = __( 'There is an error deleting that entry', 'arforms-form-builder' );

			if ( $echo ) {
				echo '<div class="frm_message">' . esc_html( $message ) . '</div>';
			}
		} else {

			$arflite_db_record->arflitedestroy( $entry_id );

			if ( ! $arflitedeletedentries ) {
				$arflitedeletedentries = array();
			}

			$arflitedeletedentries[] = $entry_id;

			if ( $ajax ) {

				if ( $echo ) {
					echo $message = 'success'; //phpcs:ignore
				}
			} else {

				$message = __( 'Your entry is successfully deleted', 'arforms-form-builder' );

				if ( $echo ) {
					echo '<div class="frm_message">' . esc_html( $message ) . '</div>';
				}
			}
		}

		return $message;
	}

	function arflite_send_email( $entry_id, $form_id, $type ) {

		global $arflitenotifymodel;

		if ( current_user_can( 'arfviewforms' ) || current_user_can( 'arfeditforms' ) ) {

			if ( $type == 'autoresponder' ) {
				$sent_to = $arflitenotifymodel->arfliteautoresponder( $entry_id, $form_id );
			} else {
				$sent_to = $arflitenotifymodel->arfliteentry_created( $entry_id, $form_id );
			}

			if ( is_array( $sent_to ) ) {
				echo esc_html(implode( ',', $sent_to )); 
			} else {
				echo esc_html($sent_to);
			}
		} else {

			echo esc_html__( 'No one! You do not have permission', 'arforms-form-builder' );
		}
	}

	function arflitemanagecolumns() {

		global $wpdb, $tbl_arf_forms;

		$response = array();
		if ( !isset( $_POST['_wpnonce_arflite'] ) || (isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' )) ) {
			$response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );

            echo json_encode($response);
            die;
		}

		if( !current_user_can( 'arfviewentries' ) ){
			$response['variant'] = 'error';
            $response['title'] = esc_html__( 'Error', 'arforms-form-builder');
            $response['msg'] = esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );;
            echo json_encode($response);
            die;
        }

		$form = !empty( $_POST['form'] ) ? intval( $_POST['form'] ) : ''; 

		$colsArray =  !empty( $_POST['colsArray'] ) ? sanitize_text_field( $_POST['colsArray'] ) : '';

		$new_arr = explode( ',', $colsArray );

		$array_hidden = array();

		foreach ( $new_arr as $key => $val ) {

			if ( $key % 2 == 0 ) {

				if ( $new_arr[ $key + 1 ] == 'hidden' ) {
					$array_hidden[] = $val;
				}
			}
		}

		$ser_arr = maybe_serialize( $array_hidden );

		$wpdb->update( $tbl_arf_forms, array( 'columns_list' => $ser_arr ), array( 'id' => $form ) );

		die();
	}

	function arflitechangebulkentries() {
		global $arflitemainhelper, $wpdb, $ARFLiteMdlDb,$arformsmain,$arflite_db_record;
		$action1 = isset( $_REQUEST['action1'] ) ? sanitize_text_field( $_REQUEST['action1'] ) : '-1';
		$action2 = isset( $_REQUEST['action3'] ) ? sanitize_text_field( $_REQUEST['action3'] ) : '-1';

		$form_id    = isset( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';
		$start_date = isset( $_REQUEST['start_date'] ) ? sanitize_text_field( $_REQUEST['start_date'] ) : '';
		$end_date   = isset( $_REQUEST['end_date'] ) ? sanitize_text_field( $_REQUEST['end_date'] ) : '';

		$items = isset( $_REQUEST['item-action'] ) ? array_map( 'intval', $_REQUEST['item-action'] ) : array();

		$bulk_action = '-1';
		if ( $action1 != '-1' ) {
			$bulk_action = $action1;
		} elseif ( $action1 == '-1' && $action2 != '-1' ) {
			$bulk_action = $action2;
		}

		if ( $bulk_action == '-1' ) {
			echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Please select valid action.',
							'arforms-form-builder'
						),
				)
			);
			die();
		}

		if ( empty( $items ) ) {
			echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Please select one or more records',
							'arforms-form-builder'
						),
				)
			);
			die();
		}

		if ( $bulk_action == 'bulk_delete' ) {
			if ( ! current_user_can( 'arfdeleteentries' ) ) {
				global $arformsmain;

				$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
				$admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

				echo json_encode(
					array(
						'error'   => true,
						'message' => $admin_permission,
					)
				);
				die();
			} else {
				if ( is_array( $items ) ) {
					foreach ( $items as $entry_id ) {
						$del_res = $arflite_db_record->arflitedestroy( $entry_id );
					}

					if ( $del_res ) {

						$total_records = '';
						if ( $form_id != '' ) {
							$total_records = $arflite_db_record->arflitegetRecordCount( (int) $form_id );
						}

						$message = __( 'Entries deleted successfully.', 'arforms-form-builder' );
						echo json_encode(
							array(
								'error'     => false,
								'message'   => $message,
								'arftotrec' => $total_records,
							)
						);
					}
				}
			}
		} elseif ( $bulk_action == 'bulk_csv' ) {

			global $arformsmain;
			$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
			$arf_admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

			if ( ! current_user_can( 'arfviewentries' ) ) {
				wp_die( $admin_permission ); //phpcs:ignore
			}

			global $arfliteform;

			if ( $form_id ) {

				$form = $arfliteform->arflitegetOne( $form_id );
			} else {

				$form = $arfliteform->arflitegetAll( "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1' );

				if ( $form ) {
					$form_id = $form->id;
				} else {
					$arflite_errors[] = __( 'No form is found', 'arforms-form-builder' );
				}
			}

			if ( $form_id && is_array( $items ) ) {
				$page_attr = $arformsmain->arforms_is_pro_active() ? 'ARForms' : 'ARFormslite';
				$link = site_url() . '/index.php?plugin='.$page_attr.'&controller=entries&form=' . $form_id . '&arfaction=csv&entry_id=' . implode( ',', $items );
				echo json_encode( $link );
			}
		}
		die();
	}

	function arflite_retrieve_form_entry_data() {
		global $wpdb, $ARFLiteMdlDb, $arflitefield, $arflitemainhelper, $arflite_db_record, $arfliteform, $arflitepagesize,$arfliteformcontroller, $tbl_arf_entries, $tbl_arf_forms, $tbl_arf_entry_values;

		$response = array();
		if ( !isset( $_POST['_wpnonce_arforms'] ) || (isset( $_POST['_wpnonce_arforms'] ) && '' != $_POST['_wpnonce_arforms'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arforms'] ), 'arforms_wp_nonce' )) ) {

			$data=array(
				'status'  =>'error',
				'msg'     =>__("Sorry, your request could not be processed due to security reason." , "arforms-form-builder"),
			);
			$return_data =array(
				'aaData'    =>  $data,
			);

			echo json_encode($return_data);
			die;
		}

		if( !current_user_can( 'arfviewentries' ) ){

			$data=array(
				'status'  =>'error',
				'msg'     =>__("Sorry, you do not have permission to perform this action" , "arforms-form-builder"),
			);
			$return_data =array(
				'aaData'    =>  $data,
			);

			echo json_encode($return_data);
			die;
        }


		$arfrequestdata = !empty( $_REQUEST['data'] ) ? $_REQUEST['data'] : array(); //phpcs:ignore

		$requested_data = json_decode( stripslashes_deep( $arfrequestdata ), true ); 

		$filtered_aoData = $requested_data['aoData'];

		$form_id    = isset( $filtered_aoData['form'] ) ? sanitize_text_Field( $filtered_aoData['form'] ) : '-1';
		$start_date = isset( $filtered_aoData['start_date'] ) ? sanitize_text_field( $filtered_aoData['start_date'] ) : '';
		$end_date   = isset( $filtered_aoData['end_date'] ) ? sanitize_text_field( $filtered_aoData['end_date'] ) : '';

		$return_data = array();

		$form_select = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_forms . '` WHERE `id` = %d AND `is_template` != %d AND `status` = %s', $form_id, 1, 'published' ) ); //phpcs:ignore

		$form_name = $form_select->name;

		$form_css = maybe_unserialize( $form_select->form_css );

		$form_options = maybe_unserialize( $form_select->options );

		$arffieldorder = array();

		$arfinnerfieldorder = array();

		$form_cols_order = array();

		$form_cols_inner_order = array();

		if ( isset( $form_options['arf_field_order'] ) && $form_options['arf_field_order'] != '' ) {
			$form_cols_order = json_decode( $form_options['arf_field_order'], true );
			asort( $form_cols_order );
			$arffieldorder = $form_cols_order;
		}

		if ( isset( $form_options['arf_inner_field_order'] ) && $form_options['arf_inner_field_order'] != '' ) {
			$form_cols_inner_order = json_decode( $form_options['arf_inner_field_order'], true );
			asort( $form_cols_inner_order );
			$arfinnerfieldorder = $form_cols_inner_order;
		}

		$offset = isset( $filtered_aoData['iDisplayStart'] ) ? sanitize_text_field( $filtered_aoData['iDisplayStart'] ) : 0;
		$limit  = isset( $filtered_aoData['iDisplayLength'] ) ? sanitize_text_field( $filtered_aoData['iDisplayLength'] ) : 10;

		$searchStr      = isset( $filtered_aoData['sSearch'] ) ? sanitize_text_field( $filtered_aoData['sSearch'] ) : '';
		$sorting_order  = isset( $filtered_aoData['sSortDir_0'] ) ? sanitize_text_field( $filtered_aoData['sSortDir_0'] ) : 'desc';
		$sorting_column = ( isset( $filtered_aoData['iSortCol_0'] ) && $filtered_aoData['iSortCol_0'] > 0 ) ? sanitize_text_field( $filtered_aoData['iSortCol_0'] ) : 1;

		$form_cols = $arflitefield->arflitegetAll( "fi.type not in ('captcha', 'html') and fi.form_id=" . (int) $form_id, ' ORDER BY id' );

		if ( count( $arffieldorder ) > 0 ) {
			$form_cols_temp = array();
			foreach ( $arffieldorder as $fieldkey => $fieldorder ) {
				foreach ( $form_cols as $frmoptkey => $frmoptarr ) {
					if ( $frmoptarr->id == $fieldkey ) {
						$form_cols_temp[] = $frmoptarr;

						unset( $form_cols[ $frmoptkey ] );
					}
				}
			}

			if ( count( $form_cols_temp ) > 0 ) {
				if ( count( $form_cols ) > 0 ) {
					$form_cols_other = $form_cols;
					$form_cols       = array_merge( $form_cols_temp, $form_cols_other );
				} else {
					$form_cols = $form_cols_temp;
				}
			}
		}

		global $arflite_style_settings, $wp_scripts;
		$wp_format_date = get_option( 'date_format' );

		if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
			$date_format_new = 'mm/dd/yy';
		} elseif ( $wp_format_date == 'd/m/Y' ) {
			$date_format_new = 'dd/mm/yy';
		} elseif ( $wp_format_date == 'Y/m/d' ) {
			$date_format_new = 'dd/mm/yy';
		} else {
			$date_format_new = 'mm/dd/yy';
		}
		$new_start_date      = $start_date;
		$new_end_date        = $end_date;
		$show_new_start_date = $new_start_date;
		$show_new_end_date   = $new_end_date;

		$arf_db_columns = array(
			'0' => '',
			'1' => 'id',
		);

		$form_cols = apply_filters( 'arflitepredisplayformcols', $form_cols, $form_id );

		$arf_sorting_array = array();

		if ( count( $form_cols ) > 0 ) {
			for ( $col_i = 2; $col_i <= count( $form_cols ) + 1; $col_i++ ) {
				$col_j                    = $col_i - 2;
				$arf_db_columns[ $col_i ] = $arflitemainhelper->arflitetruncate( $form_cols[ $col_j ]->name, 40 );
				$arf_sorting_array[ $form_cols[ $col_j ]->id ] = $col_i;
			}
			$arf_db_columns[ $col_i ]     = 'entry_key';
			$arf_db_columns[ $col_i + 1 ] = 'created_date';
			$arf_db_columns[ $col_i + 2 ] = 'browser_info';
			$arf_db_columns[ $col_i + 3 ] = 'ip_address';
			$arf_db_columns[ $col_i + 4 ] = 'country';
			$arf_db_columns[ $col_i + 5 ] = 'Page URL';
			$arf_db_columns[ $col_i + 6 ] = 'Referrer URL';
			$arf_db_columns[ $col_i + 7 ] = 'Action';

		} else {
			$arf_db_columns['2'] = 'entry_key';
			$arf_db_columns['3'] = 'created_date';
			$arf_db_columns['4'] = 'browser_info';
			$arf_db_columns['5'] = 'ip_address';
			$arf_db_columns['6'] = 'country';
			$arf_db_columns['7'] = 'Page URL';
			$arf_db_columns['8'] = 'Referrer URL';
			$arf_db_columns['9'] = 'Action';
		}

		$arforderbycolumn = isset( $arf_db_columns[ $sorting_column ] ) ? sanitize_text_field( $arf_db_columns[ $sorting_column ] ) : 'id';
		$item_order_by    = " ORDER BY it.$arforderbycolumn $sorting_order";

		$where_clause = 'it.form_id=' . $form_id;

		if ( $new_start_date != '' && $new_end_date != '' ) {
			if ( $date_format_new == 'dd/mm/yy' ) {
				$new_start_date = str_replace( '/', '-', $new_start_date );
				$new_end_date   = str_replace( '/', '-', $new_end_date );
			}
			$new_start_date_var = date( 'Y-m-d', strtotime( $new_start_date ) );

			$new_end_date_var = date( 'Y-m-d', strtotime( $new_end_date ) );

			$where_clause .= " and DATE(it.created_date) >= '" . $new_start_date_var . "' and DATE(it.created_date) <= '" . $new_end_date_var . "'";
		} elseif ( $new_start_date != '' && $new_end_date == '' ) {
			if ( $date_format_new == 'dd/mm/yy' ) {
				$new_start_date = str_replace( '/', '-', $new_start_date );
			}
			$new_start_date_var = date( 'Y-m-d', strtotime( $new_start_date ) );

			$where_clause .= " and DATE(it.created_date) >= '" . $new_start_date_var . "'";
		} elseif ( $new_start_date == '' && $new_end_date != '' ) {
			if ( $date_format_new == 'dd/mm/yy' ) {
				$new_end_date = str_replace( '/', '-', $new_end_date );
			}
			$new_end_date_var = date( 'Y-m-d', strtotime( $new_end_date ) );

			$where_clause .= " and DATE(it.created_date) <= '" . $new_end_date_var . "'";
		}

		$total_records = $wpdb->get_var( 'SELECT count(*) as total_entries FROM `' . $tbl_arf_entries . '` it WHERE ' . $where_clause ); //phpcs:ignore

		$item_order_by .= " LIMIT {$offset},{$limit}";
		if ( isset( $arf_sorting_array ) && ! empty( $arf_sorting_array ) && in_array( $sorting_column, $arf_sorting_array ) ) {
			$temp_items       = $arflite_db_record->arflitegetPage( '', '', $where_clause, '', $searchStr, $arffieldorder );
			$temp_field_metas = array();
			$sorting_value    = array_search( $sorting_column, $arf_sorting_array );
			foreach ( $temp_items as $K => $I ) {
				foreach ( $arf_sorting_array as $a => $b ) {
					$temp_field_metas[ $K ][ $a ]             = $I->metas[ $a ];
					$temp_field_metas[ $K ]['sorting_column'] = $sorting_value;
				}
			}

			if ( $sorting_order == 'asc' ) {
				uasort(
					$temp_field_metas,
					function( $a, $b ) {
						$sort_on = $a['sorting_column'];
						return strnatcasecmp( $a[ $sort_on ], $b[ $sort_on ] );
					}
				);
			} else {
				uasort(
					$temp_field_metas,
					function( $a, $b ) {
						$sort_on = $a['sorting_column'];
						return strnatcasecmp( $b[ $sort_on ], $a[ $sort_on ] );
					}
				);
			}
			$sorted_columns = array();
			$counter        = 0;

			foreach ( $temp_field_metas as $c => $d ) {
				$sorted_columns[ $c ] = $temp_items[ $c ];
				$counter++;
			}
			$sorted_cols = array_chunk( $sorted_columns, $limit );

			$chuncked_array_key = ceil( $offset / $limit ) + 1;

			$chunk_key = $chuncked_array_key - 1;
			$items     = $sorted_cols[ $chunk_key ];

		} else {

			$items = $arflite_db_record->arflitegetPage( '', '', $where_clause, $item_order_by, $searchStr, $arffieldorder );
		}

		$action_no = 0;

		$default_hide = array(
			'0' => '<div class="arflite_custom_checkbox_div_con"><div class="arf_custom_checkbox_div arfmarginl15"><div class="arf_custom_checkbox_wrapper arfmargin10custom"><input id="cb-select-all-1" type="checkbox" class=""><svg width="18px" height="18px">' . ARFLITE_CUSTOM_UNCHECKED_ICON . '
                                ' . ARFLITE_CUSTOM_CHECKED_ICON . '</svg></div></div>
            <label for="cb-select-all-1"  class="cb-select-all"><span class="cb-select-all-checkbox"></span></label></div>',
			'1' => 'ID',
		);

		$items = apply_filters( 'arflitepredisplaycolsitems', $items, $form_id );

		if ( ! empty( $searchStr ) ) {
			$total_records = $items;
		}

		if ( count( $form_cols ) > 0 ) {
			for ( $i = 2; $i <= count( $form_cols ) + 1; $i++ ) {
				$j                  = $i - 2;
				$default_hide[ $i ] = $arflitemainhelper->arflitetruncate( $form_cols[ $j ]->name, 40 );
			}
			$default_hide[ $i ]     = 'Entry key';
			$default_hide[ $i + 1 ] = 'Entry Creation Date';
			$default_hide[ $i + 2 ] = 'Browser Name';
			$default_hide[ $i + 3 ] = 'IP Address';
			$default_hide[ $i + 4 ] = 'Country';
			$default_hide[ $i + 5 ] = 'Page URL';
			$default_hide[ $i + 6 ] = 'Referrer URL';
			$default_hide[ $i + 7 ] = 'Action';
			$action_no              = $i + 7;
		} else {
			$default_hide['2'] = 'Entry Key';
			$default_hide['3'] = 'Entry creation date';
			$default_hide['4'] = 'Browser Name';
			$default_hide['5'] = 'IP Address';
			$default_hide['6'] = 'Country';
			$default_hide['7'] = 'Page URL';
			$default_hide['8'] = 'Referrer URL';
			$default_hide['9'] = 'Action';
			$action_no         = 9;
		}

		$columns_list_res = $wpdb->get_results( $wpdb->prepare( 'SELECT columns_list FROM ' . $tbl_arf_forms . ' WHERE id = %d', $form_id ), ARRAY_A ); //phpcs:ignore

		$columns_list_res = $columns_list_res[0];

		$columns_list = maybe_unserialize( $columns_list_res['columns_list'] );

		$is_colmn_array = is_array( $columns_list );

		$exclude = '';

		$exclude_array = array();

		if ( $is_colmn_array && count( $columns_list ) > 0 && $columns_list != '' ) {
			$exclude_no = 0;
			foreach ( $columns_list as $keys => $column ) {
				foreach ( $default_hide as $key => $val ) {
					if ( $column == $val ) {
						if ( $exclude_array == '' ) {
							$exclude_array[] = $key;
						} else {
							if ( ! in_array( $key, $exclude_array ) ) {
								$exclude_array[] = $key;
								$exclude_no++;
							}
						}
					}
				}
			}
		}

		$ipcolumn            = ( $action_no - 4 );
		$page_url_column     = ( $action_no - 2 );
		$referrer_url_column = ( $action_no - 1 );

		if ( $exclude_array == '' && ! $is_colmn_array ) {
			$exclude_array = array( $ipcolumn, $page_url_column, $referrer_url_column );
		} elseif ( is_array( $exclude_array ) && ! $is_colmn_array ) {

			if ( ! in_array( $ipcolumn, $exclude_array ) ) {
				array_push( $exclude_array, $ipcolumn );
			}
			if ( ! in_array( $page_url_column, $exclude_array ) ) {
				array_push( $exclude_array, $page_url_column );
			}
			if ( ! in_array( $referrer_url_column, $exclude_array ) ) {
				array_push( $exclude_array, $referrer_url_column );
			}
		}

		if ( $exclude_array != '' ) {
			$exclude = implode( ',', $exclude_array );
		}

		if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
			$date_format_new  = 'MM/DD/YYYY';
			$date_format_new1 = 'MM-DD-YYYY';
			$start_date_new   = '01/01/1970';
			$end_date_new     = '12/31/2050';
		} elseif ( $wp_format_date == 'd/m/Y' ) {
			$date_format_new  = 'DD/MM/YYYY';
			$date_format_new1 = 'DD-MM-YYYY';
			$start_date_new   = '01/01/1970';
			$end_date_new     = '31/12/2050';
		} elseif ( $wp_format_date == 'Y/m/d' ) {
			$date_format_new  = 'DD/MM/YYYY';
			$date_format_new1 = 'DD-MM-YYYY';
			$start_date_new   = '01/01/1970';
			$end_date_new     = '31/12/2050';
		} else {
			$date_format_new  = 'MM/DD/YYYY';
			$date_format_new1 = 'MM-DD-YYYY';
			$start_date_new   = '01/01/1970';
			$end_date_new     = '12/31/2050';
		}

		$data = array();

		if ( count( $items ) > 0 ) {
			$ai                    = 0;
			$arf_edit_select_array = array();
			foreach ( $items as $key => $item ) {

				$data[ $ai ][0] = "<div class='DataTables_sort_wrapper'><div class='DataTables_sort_wrapper-div'>
                       <div class='arf_custom_checkbox_div arfmarginl15'><div class='arf_custom_checkbox_wrapper'><input id='cb-item-action-" . esc_attr( $item->id ) . "' class='' type='checkbox' value='" . esc_attr( $item->id ) . "' name='item-action[]' />
                                        <svg width='18px' height='18px'>
                                        " . ARFLITE_CUSTOM_UNCHECKED_ICON . '
                                        ' . ARFLITE_CUSTOM_CHECKED_ICON . "
                                        </svg>
                                    </div>
                                </div>
                    <label for='cb-item-action-{$item->id}'><span></span></label></div></div>";
				$data[ $ai ][1] = $item->id;
				$ni             = 2;

				foreach ( $form_cols as $col ) {
					$field_value = isset( $item->metas[ $col->id ] ) ? $item->metas[ $col->id ] : false;

					if ( ! is_array( $col->field_options ) ) {
						$col->field_options = json_decode( $col->field_options, true );
					}

					if ( ! is_array( $col->options ) ) {
						$col->options = ! empty( $col->options ) ? json_decode( $col->options, true ) : array();
					}

					if ( $col->type == 'checkbox' || $col->type == 'radio' || $col->type == 'select' ) {
						if ( isset( $col->field_options['separate_value'] ) && $col->field_options['separate_value'] == '1' ) {
							$option_separate_value = array();

							foreach ( $col->options as $k => $options ) {
								$option_separate_value[] = array(
									'value' => htmlentities( $options['value'] ),
									'text'  => $options['label'],
								);
							}
							$arf_edit_select_array[] = array( $col->id => json_encode( $option_separate_value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) );
						} else {
							$option_value = '';
							$option_value = array();
							if ( is_array( $col->options ) ) {
								foreach ( $col->options as $k => $options ) {
									if ( is_array( $options ) ) {
										$option_value[] = ( $options['label'] );
									} else {
										$option_value[] = ( $options );
									}
								}
							}
							$arf_edit_select_array[] = array( $col->id => json_encode( $option_value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) );
						}
					}

					global $arfliterecordhelper;

					$data[ $ai ][ $ni ] = $arfliterecordhelper->arflitedisplay_value(
						$field_value,
						$col,
						array(
							'type'          => $col->type,
							'truncate'      => true,
							'attachment_id' => $item->attachment_id,
							'entry_id'      => $item->id,
						),
						$form_css,
						false,
						$form_name
					);
					$ni++;
				}
				$data[ $ai ][ $ni ]     = $item->entry_key;
				$data[ $ai ][ $ni + 1 ] = date( get_option( 'date_format' ), strtotime( $item->created_date ) );
				$browser_info           = $this->arflitegetBrowser( $item->browser_info );
				$data[ $ai ][ $ni + 2 ] = $browser_info['name'] . ' (Version: ' . $browser_info['version'] . ')';
				$data[ $ai ][ $ni + 3 ] = $item->ip_address;
				$data[ $ai ][ $ni + 4 ] = $item->country;
				$http_referrer          = maybe_unserialize( $item->description );
				$arfliteget_url = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . " WHERE field_id='%d' AND entry_id='%d'", '-' . 0, $item->id ) ); //phpcs:ignore
				if ( ! empty( $arfliteget_url ) ) {
					$http_referrer_url = $arfliteget_url->entry_value;
					if ( ! empty( $http_referrer_url ) ) {
						$http_page_url          = explode( '|', $http_referrer_url );
						$data[ $ai ][ $ni + 5 ] = isset( $http_page_url[1] ) ? urldecode( esc_url( $http_page_url[1] ) ) : '';
					}
				} else {
					$data[ $ai ][ $ni + 5 ] = isset( $http_referrer['page_url'] ) ? esc_url( urldecode( $http_referrer['page_url'] ) ) : '';
				}
				$data[ $ai ][ $ni + 6 ] = isset( $http_referrer['http_referrer'] ) ? esc_url( urldecode( $http_referrer['http_referrer'] ) ) : '';

				$view_entry_icon       = is_rtl() ? 'view_icon23_rtl.png' : 'view_icon23.png';
				$view_entry_icon_hover = is_rtl() ? 'view_icon23_hover_rtl.png' : 'view_icon23_hover.png';

				$view_entry_btn = "<div class='arfformicondiv arfhelptip' title='" . __( 'Preview', 'arforms-form-builder' ) . "'><a href='javascript:void(0);'  class='arflite_view_entry'  onclick='arflite_open_entry_thickbox({$item->id},\"" . htmlentities( $form_name, ENT_QUOTES ) . "\");'><svg width='30px' height='30px' viewBox='-3 -8 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M12.993,15.23c-7.191,0-11.504-7.234-11.504-7.234  S5.801,0.85,12.993,0.85c7.189,0,11.504,7.19,11.504,7.19S20.182,15.23,12.993,15.23z M12.993,2.827  c-5.703,0-8.799,5.214-8.799,5.214s3.096,5.213,8.799,5.213c5.701,0,8.797-5.213,8.797-5.213S18.694,2.827,12.993,2.827z   M12.993,11.572c-1.951,0-3.531-1.581-3.531-3.531s1.58-3.531,3.531-3.531c1.949,0,3.531,1.581,3.531,3.531  S14.942,11.572,12.993,11.572z'/></svg></a></div>";

				$id = $item->id;

				$delete_entry_icon       = is_rtl() ? 'delete_icon223_rtl.png' : 'delete_icon223.png';
				$delete_entry_icon_hover = is_rtl() ? 'delete_icon223_hover_rtl.png' : 'delete_icon223_hover.png';
				$delete_entry_btn        = '';
				if ( current_user_can( 'arfdeleteentries' ) ) {

					$delete_entry_btn = "<div class='arfformicondiv arfhelptip arfentry_delete_div_" . $item->id . "' title='" . __( 'Delete', 'arforms-form-builder' ) . "'><a data-id='" . $item->id . "' id='arf_delete_single_entry'><svg width='30px' height='30px' viewBox='-5 -5 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M18.435,4.857L18.413,19.87L3.398,19.88L3.394,4.857H1.489V2.929  h1.601h3.394V0.85h8.921v2.079h3.336h1.601l0,0v1.928H18.435z M15.231,4.857H6.597H5.425l0.012,13.018h10.945l0.005-13.018H15.231z   M11.4,6.845h2.029v9.065H11.4V6.845z M8.399,6.845h2.03v9.065h-2.03V6.845z' /></svg></a></div>";
				}

				$delete_entry_overlay = "<div class='display-none-cls view_entry_detail_container_' id='view_entry_detail_container_{$item->id}'>" . $this->arflite_get_entries_list_edit( $item->id, $arffieldorder, $arfinnerfieldorder ) . "</div><div class='arfmnarginbtm10'></div>";

				$data[ $ai ][ $ni + 7 ]  = "<div class='arf-row-actions'>{$view_entry_btn}{$delete_entry_btn} {$delete_entry_overlay}</div>";
				$data[ $ai ][ $ni + 7 ] .= "<input type='hidden' id='arf_edit_select_array_{$item->id}' value='" . json_encode( $arf_edit_select_array ) . "' />";
				$arflite_PDF_button      = '';
				$action_no               = $ni + 7;
				$ai++;
			}
			$sEcho = isset( $filtered_aoData['sEcho'] ) ? intval( $filtered_aoData['sEcho'] ) : intval( 10 );

			$return_data = array(
				'sEcho'                => $sEcho,
				'iTotalRecords'        => (int) $total_records,
				'iTotalDisplayRecords' => (int) $total_records,
				'aaData'               => $data,
			);
		} else {
			$sEcho = isset( $filtered_aoData['sEcho'] ) ? intval( $filtered_aoData['sEcho'] ) : intval( 10 );

			$return_data = array(
				'sEcho'                => $sEcho,
				'iTotalRecords'        => (int) $total_records,
				'iTotalDisplayRecords' => (int) $total_records,
				'aaData'               => $data,
			);
		}

		echo json_encode( $return_data );
		die;
	}

	function arflite_retrieve_form_data() {
		global $wpdb, $ARFLiteMdlDb, $arflitefield, $arflitemainhelper, $arflite_db_record, $arfliteform, $arflitepagesize, $arfliteformcontroller, $tbl_arf_forms, $tbl_arf_entries;

		if ( !isset( $_POST['_wpnonce_arforms'] ) || (isset( $_POST['_wpnonce_arforms'] ) && '' != $_POST['_wpnonce_arforms'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arforms'] ), 'arforms_wp_nonce' )) ) {
			echo esc_html__( 'Sorry, your request could not be processed due to security reason.', 'arforms-form-builder' );
			die;
 
		}

		if( !current_user_can( 'arfviewentries' ) ){
			echo esc_html__( 'Sorry, you do not have permission to perform this action', 'arforms-form-builder' );
            die;
        }

		$arfrequestdata = !empty( $_REQUEST['data'] ) ? $_REQUEST['data'] : array(); //phpcs:ignore
		$requested_data = json_decode( stripslashes_deep( $arfrequestdata ), true );

		$filtered_aoData = $requested_data['aoData'];

		$return_data = array();

		$order_by = ! empty( $filtered_aoData['iSortCol_0'] ) ? sanitize_text_field( $filtered_aoData['iSortCol_0'] ) : 1;

		$order_by_str = 'ORDER BY';

		if ( 1 == $order_by ) {
			$order_by_str .= ' f.id';
		} elseif ( 2 == $order_by ) {
			$order_by_str .= ' f.name';
		} elseif ( 3 == $order_by ) {
			$order_by_str .= ' total_entries';
		} elseif ( 5 == $order_by ) {
			$order_by_str .= ' f.created_date';
		} else {
			  $order_by_str .= ' f.id';
		}

		$order_by_str .= ' ' . ( ! empty( $filtered_aoData['sSortDir_0'] ) ? strtoupper( sanitize_text_field( $filtered_aoData['sSortDir_0'] ) ) : 'DESC' );

		$form_params = 'f.*,COUNT(e.form_id) AS total_entries';

		$form_table_param = $tbl_arf_forms . ' f LEFT JOIN ' . $tbl_arf_entries . ' e ON f.id = e.form_id';

		$group_by_param = 'GROUP BY f.id';

		$offset = isset( $filtered_aoData['iDisplayStart'] ) ? sanitize_text_field( $filtered_aoData['iDisplayStart'] ) : 0;
		$limit  = isset( $filtered_aoData['iDisplayLength'] ) ? sanitize_text_field( $filtered_aoData['iDisplayLength'] ) : 10;

		$limit_param = 'LIMIT ' . $offset . ', ' . $limit;

		$where_clause = 'WHERE f.is_template = %d AND ( f.status is NULL OR f.status = \'\' OR f.status = %s ) AND arf_is_lite_form = 1';
		$where_params = array( 0, 'published' );

		if ( ! empty( $filtered_aoData['sSearch'] ) ) {
			  $wild           = '%';
			  $find           = $filtered_aoData['sSearch'];
			  $like           = $wild . $wpdb->esc_like( $find ) . $wild;
			  $where_clause  .= ' AND ( f.name LIKE %s )';
			  $where_params[] = $like;
		}

		$where_clause2 = $where_clause;
		$where_params2 = $where_params;

		$sel_query = 'SELECT ' . $form_params . ' FROM ' . $form_table_param . ' ' . $where_clause . ' ' . $group_by_param . ' ' . $order_by_str . ' ' . $limit_param;

		array_unshift( $where_params, $sel_query );

		$build_query = call_user_func_array( array( $wpdb, 'prepare' ), $where_params );

		$form_results = $wpdb->get_results( $build_query ); //phpcs:ignore

		$sel_total_query = 'SELECT count(f.id) FROM ' . $tbl_arf_forms . ' f ' . $where_clause2;

		array_unshift( $where_params2, $sel_total_query );

		$build_query2 = call_user_func_array( array( $wpdb, 'prepare' ), $where_params2 ); //phpcs:ignore

		$total_records = $wpdb->get_var( $build_query2 ); //phpcs:ignore

		$data = array();
		if ( count( $form_results ) > 0 ) {

			  $ai = 0;
			foreach ( $form_results as $form_data ) {

				$data[ $ai ][0] = "<div class='arf_custom_checkbox_div arfmarginl20'><div class='arf_custom_checkbox_wrapper'><input id='cb-item-action-" . esc_attr( $form_data->id ) . " class='chkstanard' type='checkbox' value='" . esc_attr( $form_data->id ) . "' name='item-action[]'>
                                <svg width='18px' height='18px'>
                                " . ARFLITE_CUSTOM_UNCHECKED_ICON . '
                                ' . ARFLITE_CUSTOM_CHECKED_ICON . "
                                </svg>
                            </div>
                        </div>
                        <label for='cb-item-action-{$form_data->id}'><span></span></label>";

				$data[ $ai ][1] = $form_data->id;
				$edit_link      = "?page=ARForms&arfaction=edit&id={$form_data->id}";
				if ( current_user_can( 'arfeditforms' ) ) {
					$data[ $ai ][2] = "<a class='row-title' href='{$edit_link}'>" . $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes_deep( $form_data->name ) ), 33 ) . '</a>';
				} else {
					$data[ $ai ][2] = $arflitemainhelper->arflitetruncate( html_entity_decode( stripslashes_deep( $form_data->name ) ), 33 );
				}

				$data[ $ai ][3] = ( ( current_user_can( 'arfviewentries' ) ) ? "<a href='" . esc_url( admin_url( 'admin.php' ) . '?page=ARForms-entries&form=' . $form_data->id ) . "'>" . esc_html( $form_data->total_entries ) . '</a>' : $form_data->total_entries );

				$shortcode_data = "<div class='arf_shortcode_div'>
                            <div class='arf_copied grid_copy_icon' data-attr='[ARForms id={$form_data->id}]'>" . __( 'Click to Copy', 'arforms-form-builder' ) . "</div>
                            <input type='text' class='shortcode_textfield' readonly='readonly' onclick='this.select();' onfocus='this.select();' value='[ARForms id=" . esc_attr( $form_data->id ) . "]' />
                        </div>";

				$data[ $ai ][4] = $shortcode_data;

				$wp_format_date = get_option( 'date_format' );
				if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
					$date_format_new = 'M d, Y';
				} elseif ( $wp_format_date == 'd/m/Y' ) {
					$date_format_new = 'd M, Y';
				} elseif ( $wp_format_date == 'Y/m/d' ) {
					$date_format_new = 'Y, M d';
				} else {
					$date_format_new = 'M d, Y';
				}

				$data[ $ai ][5] = date( $date_format_new, strtotime( $form_data->created_date ) );

				$action_row_data = "<div class='arf-row-actions'>";

				if ( current_user_can( 'arfeditforms' ) ) {
					$edit_link = "?page=ARForms&arfaction=edit&id={$form_data->id}";

					$action_row_data .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Edit Form', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( $edit_link ) . "'><svg width='30px' height='30px' viewBox='-5 -4 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill='#ffffff' d='M17.469,7.115v10.484c0,1.25-1.014,2.264-2.264,2.264H3.75c-1.25,0-2.262-1.014-2.262-2.264V5.082  c0-1.25,1.012-2.264,2.262-2.264h9.518l-2.264,2.001H3.489v13.042h11.979V9.379L17.469,7.115z M15.532,2.451l-0.801,0.8l2.4,2.401  l0.801-0.8L15.532,2.451z M17.131,0.85l-0.799,0.801l2.4,2.4l0.801-0.801L17.131,0.85z M6.731,11.254l2.4,2.4l7.201-7.202  l-2.4-2.401L6.731,11.254z M5.952,14.431h2.264l-2.264-2.264V14.431z' /></svg></a></div>";

				}

				if ( current_user_can( 'arfviewentries' ) ) {

					$duplicate_link = "?page=ARForms&arfaction=duplicate&id={$form_data->id}";

					$action_row_data .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Form Entry', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( "?page=ARForms-entries&arfaction=list&form={$form_data->id}" ) . "' ><svg width='30px' height='30px' viewBox='-7 -4 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M1.489,19.829V0.85h14v18.979H1.489z M13.497,2.865H3.481v14.979  h10.016V2.865z M10.489,15.806H4.493v-2h5.996V15.806z M4.495,9.806h7.994v2H4.495V9.806z M4.495,5.806h7.994v2H4.495V5.806z' /></svg></a></div>";

				}

				if ( current_user_can( 'arfeditforms' ) ) {

					$action_row_data .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Duplicate Form', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( $duplicate_link ) . "' ><svg width='30px' height='30px' viewBox='-5 -5 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M16.501,15.946V2.85H5.498v-2h11.991v0.025h1.012v15.07H16.501z   M15.489,19.81h-14V3.894h14V19.81z M13.497,5.909H3.481v11.979h10.016V5.909z'/></svg></a></div>";
				}

				if ( current_user_can( 'arfviewentries' ) ) {
					$action_row_data .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Export Entries', 'arforms-form-builder' ) . "'><a onclick='arfliteaction_func(\"export_csv\", \"{$form_data->id}\");'><svg width='30px' height='30px' viewBox='-3 -5 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill='#ffffff' d='M16.477,10.586V7.091c0-0.709-0.576-1.283-1.285-1.283H2.772c-0.709,0-1.283,0.574-1.283,1.283v3.495    c0,0.709,0.574,1.283,1.283,1.283h12.419C15.9,11.87,16.477,11.295,16.477,10.586z M5.131,9.887c0.277,0,0.492-0.047,0.67-0.116    l0.138,0.862c-0.208,0.092-0.6,0.17-1.047,0.17c-1.217,0-1.995-0.74-1.995-1.925c0-1.102,0.753-2.002,2.156-2.002    c0.308,0,0.646,0.054,0.893,0.146L5.762,7.892C5.623,7.83,5.415,7.776,5.107,7.776c-0.616,0-1.016,0.438-1.01,1.055    C4.098,9.524,4.561,9.887,5.131,9.887z M8.525,10.772c-0.492,0-1.369-0.107-1.654-0.262l0.646-0.839    C7.732,9.8,8.179,9.957,8.525,9.957c0.354,0,0.501-0.124,0.501-0.317c0-0.191-0.116-0.284-0.556-0.43    C7.695,8.948,7.395,8.524,7.402,8.077c0-0.701,0.6-1.231,1.531-1.231c0.44,0,0.832,0.101,1.063,0.216L9.789,7.87    c-0.17-0.094-0.494-0.216-0.816-0.216c-0.285,0-0.446,0.116-0.446,0.309c0,0.177,0.147,0.269,0.608,0.431    c0.717,0.246,1.016,0.608,1.023,1.162C10.158,10.255,9.604,10.772,8.525,10.772z M13.54,10.725h-1.171l-1.371-3.766h1.271    l0.509,1.748c0.092,0.315,0.162,0.617,0.216,0.916h0.023c0.062-0.308,0.124-0.593,0.208-0.916l0.486-1.748h1.23L13.54,10.725z     M19.961,0.85H6.02c-0.295,0-0.535,0.239-0.535,0.534v2.45h1.994V2.79h11.014v11.047l-2.447-0.002    c-0.158,0-0.309,0.064-0.421,0.177c-0.11,0.109-0.173,0.26-0.173,0.418l0.012,3.427H7.479V12.8H5.484v6.501    c0,0.294,0.239,0.533,0.535,0.533h10.389c0.153,0,0.297-0.065,0.398-0.179l3.553-4.048c0.088-0.098,0.135-0.224,0.135-0.355V1.384    C20.496,1.089,20.255,0.85,19.961,0.85z'/></svg></a></div>";
				}

				global $style_settings, $arfliteformhelper;

				$target_url = $arfliteformhelper->arflite_get_direct_link( $form_data->form_key );

				$target_url = $target_url . '&ptype=list';

				$tb_width = '';

				$tb_height = '';

				$action_row_data .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Preview', 'arforms-form-builder' ) . "'><a class='openpreview' href='javascript:void(0)'  data-url='" . $target_url . $tb_width . $tb_height . "&whichframe=preview&TB_iframe=true'><svg width='30px' height='30px' viewBox='-3 -8 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M12.993,15.23c-7.191,0-11.504-7.234-11.504-7.234  S5.801,0.85,12.993,0.85c7.189,0,11.504,7.19,11.504,7.19S20.182,15.23,12.993,15.23z M12.993,2.827  c-5.703,0-8.799,5.214-8.799,5.214s3.096,5.213,8.799,5.213c5.701,0,8.797-5.213,8.797-5.213S18.694,2.827,12.993,2.827z   M12.993,11.572c-1.951,0-3.531-1.581-3.531-3.531s1.58-3.531,3.531-3.531c1.949,0,3.531,1.581,3.531,3.531  S14.942,11.572,12.993,11.572z'/></svg></a></div>";

				if ( current_user_can( 'arfdeleteforms' ) ) {
					$delete_link      = "?page=ARForms&arfaction=destroy&id={$form_data->id}";
					$id               = $form_data->id;
					$action_row_data .= "<div class='arfformicondiv arfhelptip arfdeleteform_div_" . $id . "' title='" . __( 'Delete', 'arforms-form-builder' ) . "'><a class='arflite-cursor-pointer' id='delete_pop' data-toggle='arfmodal' data-id='" . $id . "'><svg width='30px' height='30px' viewBox='-5 -5 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M18.435,4.857L18.413,19.87L3.398,19.88L3.394,4.857H1.489V2.929  h1.601h3.394V0.85h8.921v2.079h3.336h1.601l0,0v1.928H18.435z M15.231,4.857H6.597H5.425l0.012,13.018h10.945l0.005-13.018H15.231z   M11.4,6.845h2.029v9.065H11.4V6.845z M8.399,6.845h2.03v9.065h-2.03V6.845z' /></svg></a></div>";
				}

				$action_row_data .= '</div>';

				$data[ $ai ][6] = $action_row_data;

				$ai++;
			}

			$sEcho = isset( $filtered_aoData['sEcho'] ) ? intval( $filtered_aoData['sEcho'] ) : intval( 10 );

			$return_data = array(
				'sEcho'                => $sEcho,
				'iTotalRecords'        => (int) $total_records,
				'iTotalDisplayRecords' => (int) $total_records,
				'aaData'               => $data,
			);
		} else {
			  $sEcho       = isset( $filtered_aoData['sEcho'] ) ? intval( $filtered_aoData['sEcho'] ) : intval( 10 );
			  $return_data = array(
				  'sEcho'                => $sEcho,
				  'iTotalRecords'        => (int) $total_records,
				  'iTotalDisplayRecords' => (int) $total_records,
				  'aaData'               => $data,
			  );
		}

		echo json_encode( $return_data );
		die;
	}




	function arflite_get_entries_list( $id = '' ) {

		global $arflite_db_record, $arflitefield, $arfliterecordmeta, $user_ID, $arflitemainhelper, $arfliterecordhelper;

		if ( ! $id ) {
			$id = $arflitemainhelper->arflite_get_param( 'id' );
		}

		if ( ! $id ) {
			$id = $arflitemainhelper->arflite_get_param( 'entry_id' );
		}

		$entry = $arflite_db_record->arflitegetOne( $id, true );

		$data = maybe_unserialize( $entry->description );

		if ( ! isset( $data['referrer'] ) or ! is_array( $data ) ) {
			$data = array( 'referrer' => $data );
		}

		$fields = $arflitefield->arflitegetAll( "fi.type not in ('captcha','html') and fi.form_id=" . (int) $entry->form_id, ' ORDER BY id' );

		$fields = apply_filters( 'arflitepredisplayformcols', $fields, $entry->form_id );
		$entry  = apply_filters( 'arflitepredisplayonecol', $entry, $entry->form_id );

		$date_format = get_option( 'date_format' );

		$time_format = get_option( 'time_format' );

		$show_comments = true;

		if ( $show_comments ) {

			$comments = $arfliterecordmeta->arflitegetAll( "entry_id=$id and field_id=0", ' ORDER BY it.created_date ASC' );

			$to_emails = apply_filters( 'arflitetoemail', array(), $entry, $entry->form_id );
		}

		$var = '<table class="form-table"><tbody>';

		foreach ( $fields as $field ) {

			$var .= '<tr class="arfviewentry_row" valign="top">


                        <td class="arfviewentry_left" scope="row"><strong>' . stripslashes( $field->name ) . ':</strong></td>


                        <td  class="arfviewentry_right view-entry-text-align">';

			$field_value = isset( $entry->metas[ $field->id ] ) ? $entry->metas[ $field->id ] : false;

			$field->field_options = arflite_json_decode( $field->field_options, true );

			$var .= $display_value = $arfliterecordhelper->arflitedisplay_value(
				$field_value,
				$field,
				array(
					'type'          => $field->type,
					'attachment_id' => $entry->attachment_id,
					'show_filename' => true,
					'show_icon'     => true,
					'entry_id'      => $entry->id,
				)
			);

			if ( is_email( $display_value ) && ! in_array( $display_value, $to_emails ) ) {
				$to_emails[] = $display_value;
			}

			$var .= '</td>


                    </tr>';
		}

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left"><strong>' . __( 'Created at', 'arforms-form-builder' ) . ':</strong></td><td class="arfviewentry_right">' . $arflitemainhelper->arflite_get_formatted_time( $entry->created_date, $date_format, $time_format );

		if ( $entry->user_id ) {

		}

		$var .= '</td></tr>';

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left"><strong>' . __( 'Page url', 'arforms-form-builder' ) . ':</strong></td><td class="arfviewentry_right">' . $data['page_url'];
		$var .= '</td></tr>';

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left"><strong>' . __( 'Referrer url', 'arforms-form-builder' ) . ':</strong></td><td class="arfviewentry_right">' . esc_url( $data['http_referrer'] );
		$var .= '</td></tr>';

		$temp_var = apply_filters( 'arflite_entry_payment_detail', $id );

		$var .= ( $temp_var != $id ) ? $temp_var : '';

		$var = apply_filters( 'arfliteafterviewentrydetail', $var, $id );

		$var .= '</tbody></table>';

		return $var;
	}

	function arflite_get_entries_list_edit( $id = '', $arffieldorder = array(), $arfinnerfieldorder = array() ) {

		global $arflite_db_record, $arflitefield, $arfliterecordmeta, $user_ID, $arflitemainhelper, $arfliterecordhelper, $wpdb, $ARFLiteMdlDb, $tbl_arf_entry_values;

		if ( ! $id ) {
			$id = $arflitemainhelper->arflite_get_param( 'id' );
		}

		if ( ! $id ) {
			$id = $arflitemainhelper->arflite_get_param( 'entry_id' );
		}

		$entry = $arflite_db_record->arflitegetOne( $id, true );

		$data = maybe_unserialize( $entry->description );

		if ( ! isset( $data['referrer'] ) || ! is_array( $data ) ) {
			$data = array( 'referrer' => $data );
		}

		$fields = wp_cache_get( 'arflite_get_entries_list_edit_' . $entry->form_id );
		if ( false == $fields ) {
			$fields = $arflitefield->arflitegetAll( "fi.type not in ('captcha','html') and fi.form_id=" . (int) $entry->form_id );
			wp_cache_set( 'arflite_get_entries_list_edit_' . $entry->form_id, $fields );
		}

		$fields = apply_filters( 'arflitepredisplayformcols', $fields, $entry->form_id );
		$entry  = apply_filters( 'arflitepredisplayonecol', $entry, $entry->form_id );

		$date_format = get_option( 'date_format' );

		$time_format = get_option( 'time_format' );

		$show_comments = true;

		if ( $show_comments ) {

			$comments = $arfliterecordmeta->arflitegetAll( "entry_id=$id and field_id=0", ' ORDER BY it.created_date ASC' );

			$to_emails = apply_filters( 'arflitetoemail', array(), $entry, $entry->form_id );
		}

		$var = '<table class="form-table"><tbody>';

		$as_edit_entry_value = array();

		if ( count( $arffieldorder ) > 0 ) {

			$form_fields = array();
			foreach ( $arffieldorder as $fieldkey => $fieldorder ) {
				foreach ( $fields as $fieldordkey => $fieldordval ) {
					if ( $fieldordval->id == $fieldkey ) {
						$form_fields[] = $fieldordval;

						unset( $fields[ $fieldordkey ] );
					}
				}
			}

			if ( count( $form_fields ) > 0 ) {
				if ( count( $fields ) > 0 ) {
					$arfotherfields = $fields;
					$fields         = array_merge( $form_fields, $arfotherfields );
				} else {
					$fields = $form_fields;
				}
			}
		}

		foreach ( $fields as $field ) {

			if( empty( $field->name )){
				$field_name = 'Field ID['.$field->id .']'; 
			} else {
				 $field_name = $field->name;
			}

			$var .= '<tr class="arfviewentry_row" valign="top">

				<td class="arfviewentry_left arfwidth25" scope="row">' . stripslashes( $field_name ) . ':</td>

				<td  class="arfviewentry_right view-entry-text-align">';

			$field_value = isset( $entry->metas[ $field->id ] ) ? $entry->metas[ $field->id ] : false;

			$field->field_options = arflite_json_decode( $field->field_options, true );

			if ( $field->type == 'checkbox' ) {
				$as_edit_entry_value[ $field->id ] = $field_value;
			}

			if ( $field->type == 'radio' || $field->type == 'select' ) {
				$as_edit_entry_value[ $field->id ] = $field_value;
			}
			$var .= $display_value = $arfliterecordhelper->arflitedisplay_value(
				$field_value,
				$field,
				array(
					'type'          => $field->type,
					'attachment_id' => $entry->attachment_id,
					'show_filename' => true,
					'show_icon'     => true,
					'entry_id'      => $entry->id,
				)
			);

			if ( $field->type == 'date' ) {
				$var .= '<input type="hidden" id="arf_edit_new_values_' . esc_attr( $field->id . '_' . $entry->id ) . '" value="" data-id="' . esc_attr( $field->id ) . '" data-entry-id="' . esc_attr( $entry->id ) . '">';
				$var .= '<input type="hidden" name="arf_edit_form_field_values_' . esc_attr( $entry->id ) . '[]" id="arf_edit_new_values_' . esc_attr( $field->id . '_' . $entry->id ) . '_date" value="" data-id="' . esc_attr( $field->id ) . '" data-entry-id="' . esc_attr( $entry->id ) . '">';
			} else {
				$var .= '<input type="hidden" name="arf_edit_form_field_values_' . esc_attr( $entry->id ) . '[]" id="arf_edit_new_values_' . esc_attr( $field->id . '_' . $entry->id ) . '" value="" data-id="' . esc_attr( $field->id ) . '" data-entry-id="' . esc_attr( $entry->id ) . '">';
			}

			if ( is_email( $display_value ) && ! in_array( $display_value, $to_emails ) ) {
				$to_emails[] = $display_value;
			}

			$var .= '</td>
                    </tr>';
		}

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left arfwidth25">' . __( 'Created at', 'arforms-form-builder' ) . ':</td><td class="arfviewentry_right"><span class="arf_not_editable_values_container">' . $arflitemainhelper->arflite_get_formatted_time( $entry->created_date, $date_format, $time_format ) . '</span>';
		if ( $entry->user_id ) {

		}

		$json_data = json_encode( $as_edit_entry_value );

		$var .= '</td></tr>';

		$temp_var = apply_filters( 'arflite_entry_payment_detail', $id );

		$var                  .= ( $temp_var != $id ) ? sanitize_text_field( $temp_var ) : '';
		$data['page_url']      = isset( $data['page_url'] ) ? esc_url( $data['page_url'] ) : '';
		$data['http_referrer'] = isset( $data['http_referrer'] ) ? esc_url( $data['http_referrer'] ) : '';

		/* changes http_page_url start */
		$arfliteget_url = $wpdb->get_row( $wpdb->prepare( 'SELECT entry_value FROM ' . $tbl_arf_entry_values . " WHERE field_id='%d' AND entry_id='%d'", '-' . 0, $id ) ); //phpcs:ignore
		if ( ! empty( $arfliteget_url ) ) {
			$http_referrer_url = $arfliteget_url->entry_value;
			if ( ! empty( $http_referrer_url ) ) {
				$http_page_url    = explode( '|', $http_referrer_url );
				$data['page_url'] = isset( $http_page_url[1] ) ? esc_url( $http_page_url[1] ) : '';
			}
		} else {
			$data['page_url'] = isset( $data['page_url'] ) ? esc_url( $data['page_url'] ) : '';
		}
		/* changes http_page_url end */

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left arfwidth25">' . __( 'Page url', 'arforms-form-builder' ) . ':</td><td class="arfviewentry_right"><span class="arf_not_editable_values_container">' . urldecode( $data['page_url'] ) . '</span>';
		$var .= '</td></tr>';

		$var .= '<tr class="arfviewentry_row"><td class="arfviewentry_left arfwidth25">' . __( 'Referrer url', 'arforms-form-builder' ) . ':</td><td class="arfviewentry_right"><span class="arf_not_editable_values_container">' . esc_url( urldecode( $data['http_referrer'] ) ) . '</span>';
		$var .= '</td></tr>';

		$var = apply_filters( 'arfliteafterviewentrydetail', $var, $id );

		$var .= '</tbody></table>';

		return $var;
	}

	function arflite_include_css_from_form_content( $post_content ) {

		global $post, $arflite_submit_ajax_page, $arfliteversion, $arflite_jscss_version;

		$arflite_submit_ajax_page = 1;

		if ( is_ssl() ) {
			$upload_main_url = str_replace( 'http://', 'https://', ARFLITE_UPLOAD_URL . '/maincss' );
		} else {
			$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
		}

		$upload_main_url = esc_url_raw( $upload_main_url );

		$parts    = explode( '[ARForms', $post_content );
		$myidpart = explode( 'id=', $parts[1] );
		$myid     = explode( ']', $myidpart[1] );

		if ( ! is_admin() ) {
			global $wp_query;
			$posts   = $wp_query->posts;
			$pattern = get_shortcode_regex();

			if ( preg_match_all( '/' . $pattern . '/s', $post_content, $matches ) && array_key_exists( 2, $matches ) && in_array( 'ARForms', $matches[2] ) ) {

			}

			$formids = array();

			foreach ( $matches as $k => $v ) {
				foreach ( $v as $key => $val ) {
					$parts = explode( 'id=', $val );
					if ( $parts > 0 ) {

						if ( @stripos( $parts[1], ']' ) !== false ) {
							$partsnew  = @explode( ']', $parts[1] );
							$formids[] = @$partsnew[0];
						} elseif ( @stripos( $parts[1], ' ' ) !== false ) {

							$partsnew  = @explode( ' ', $parts[1] );
							$formids[] = @$partsnew[0];
						} else {

						}
					}
				}
			}

			$newvalarr = array();

			if ( is_array( $formids ) && count( $formids ) > 0 ) {
				foreach ( $formids as $newkey => $newval ) {
					if ( stripos( $newval, ' ' ) !== false ) {
						$partsnew    = explode( ' ', $newval );
						$newvalarr[] = $partsnew[0];
					} else {
						$newvalarr[] = $newval;
					}
				}
			}

			if ( is_array( $newvalarr ) && count( $newvalarr ) > 0 ) {
				$newvalarr = array_unique( $newvalarr );
				foreach ( $newvalarr as $newkey => $newval ) {
					$fid1 = $upload_main_url . '/maincss_' . $newval . '.css';

					wp_register_style( 'arfliteformscss_' . $newval, $upload_main_url . '/maincss_' . $newval . '.css', array(), $arflite_jscss_version );
					wp_print_styles( 'arfliteformscss_' . $newval );
				}
			}
		}
	}

	function arflite_ajax_check_recaptcha() {

		global $wpdb, $arflite_errors, $arflitefieldhelper, $arflitemaincontroller;

		$arflite_errors = array();

		$arf_options = get_option( 'arflite_options' );

		$default_blank_msg = $arf_options->blank_msg;

		$arfform_id = isset( $_POST['form_id']) ? intval( $_POST['form_id']) : ''; //phpcs:ignore
		$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, intval( $arfform_id ), false, 0 );

		foreach ( $fields as $field ) {
			$field_id = $field->id;

			if ( $field->type == 'captcha' && isset( $_POST['recaptcha_challenge_field'] ) ) { //phpcs:ignore

				//$arflitemaincontroller->arfliteafterinstall();

				global $arformsmain;

				require_once ARFLITE_FORMPATH . '/core/recaptchalib/recaptchalib.php';

				$recaptcha_details = $arformsmain->arforms_get_settings(['pubkey', 'privkey', 're_msg', 're_theme'],'general_settings');

				extract( $recaptcha_details );

				$site_key    = !empty( $pubkey ) ? $pubkey : '';
				$private_key = !empty( $privkey ) ? $privkey : '';
				$re_msg      = !empty( $re_msg ) ? $re_msg : esc_html__('Invalid reCaptcha. Please try again.','arforms-form-builder');

				if ( $site_key == '' || $private_key == '' ) {

					$arflite_errors[ $field_id ] = ( ! isset( $field->field_options['invalid'] ) || $field->field_options['invalid'] == '' ) ? $re_msg : $field->field_options['invalid'];
				} else {

					$recaptcha = new ARForms_ReCaptcha( $private_key );
					$arfremoteaddr = !empty( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
					$arfg_recaptcha = !empty( $_POST['g-recaptcha-response']) ? sanitize_textarea_field( $_POST['g-recaptcha-response'] ) : ''; //phpcs:ignore
					$response = $recaptcha->verifyResponse( $arfremoteaddr, $arfg_recaptcha );

					if ( $response->success ) {
						$arflite_errors['captcha'] = 'success';
						$arfform_id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : ''; //phpcs:ignore
						$_SESSION[ 'arf_recaptcha_allowed_' . intval( $arfform_id ) ] = 1;
					} else {
						$arflite_errors[ $field_id ] = ( ! isset( $field->field_options['invalid'] ) || $field->field_options['invalid'] == '' ) ? $re_msg : $field->field_options['invalid'];
					}
				}
			}
		}

		echo json_encode( $arflite_errors );
		die();
	}

	function arflite_internal_check_recaptcha() {

		global $wpdb, $arflite_errors, $arflitefieldhelper, $arflitemaincontroller,$arformsmain;

		$arflite_errors = array();

		$arf_options = get_option( 'arflite_options' ); 

		$default_blank_msg = !empty( $arf_options->blank_msg ) ? $arf_options->blank_msg : esc_html__('This field cannot be blank', 'arforms-form-builder');

		$arform_id = isset($_POST['form_id']) ? intval( $_POST['form_id'] ) : ''; //phpcs:ignore

		$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, intval( $arform_id ), false, 0 );

		foreach ( $fields as $field ) {

			global $arformsmain;

			$field_id = $field->id;

			$recaptcha_details = $arformsmain->arforms_get_settings(['pubkey', 'privkey', 're_msg', 're_theme'],'general_settings');

			extract( $recaptcha_details );

			$pubkey    = !empty( $pukey ) ? $pukey : '';
			$privkey = !empty( $privkey ) ? $privkey : '';
			$re_msg      = !empty( $re_msg ) ? $re_msg : esc_html__('Invalid reCaptcha. Please try again.','arforms-form-builder');

			if ( $field->type == 'captcha' && $pubkey != '' && $privkey != '' ) {

				//$arflitemaincontroller->arfliteafterinstall();

				require_once ARFLITE_FORMPATH . '/core/recaptchalib/recaptchalib.php';

				$sitekey = $pubkey;
				$secret  = $privkey;

				if ( $sitekey == '' || $secret == '' ) {
					$arflite_errors[ $field_id ] = ( ! isset( $field->field_options['invalid'] ) || $field->field_options['invalid'] == '' ) ? $re_msg : $field->field_options['invalid'];
				} else {

					$recaptcha         = new ARForms_ReCaptcha( $secret );
					$recptcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_textarea_field( $_POST['g-recaptcha-response'] ) : ''; //phpcs:ignore
					$arfremoteadd = isset( $_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
					$response          = $recaptcha->verifyResponse( $arfremoteadd, $recptcha_response );
					if ( $response->success ) {

					} else {
						$arflite_errors[ $field_id ] = ( ! isset( $field->field_options['invalid'] ) || $field->field_options['invalid'] == '' ) ? $re_msg : $field->field_options['invalid'];
					}
				}
			}
		}

		return $arflite_errors;
	}

	function arflitegetBrowser( $user_agent ) {
		$u_agent  = $user_agent;
		$bname    = 'Unknown';
		$platform = 'Unknown';
		$version  = '';

		if ( @preg_match( '/linux/i', $u_agent ) ) {
			$platform = 'linux';
		} elseif ( @preg_match( '/macintosh|mac os x/i', $u_agent ) ) {
			$platform = 'mac';
		} elseif ( @preg_match( '/windows|win32/i', $u_agent ) ) {
			$platform = 'windows';
		}

		$ub = 'Unknown';

		if ( @preg_match( '/MSIE/i', $u_agent ) && ! @preg_match( '/Opera/i', $u_agent ) ) {
			$bname = 'Internet Explorer';
			$ub    = 'MSIE';
		} elseif ( @preg_match( '/Firefox/i', $u_agent ) ) {
			$bname = 'Mozilla Firefox';
			$ub    = 'Firefox';
		} elseif ( @preg_match( '/OPR/i', $u_agent ) ) {
			$bname = 'Opera';
			$ub    = 'OPR';
		} elseif ( @preg_match( '/Edge/i', $u_agent ) || @preg_match( '/Edg/i', $u_agent ) ) {
			$bname = 'Edge';
			$ub    = 'Edg';
		} elseif ( @preg_match( '/Chrome/i', $u_agent ) ) {
			$bname = 'Google Chrome';
			$ub    = 'Chrome';
		} elseif ( @preg_match( '/Safari/i', $u_agent ) ) {
			$bname = 'Apple Safari';
			$ub    = 'Safari';
		} elseif ( @preg_match( '/Opera/i', $u_agent ) ) {
			$bname = 'Opera';
			$ub    = 'Opera';
		} elseif ( @preg_match( '/Netscape/i', $u_agent ) ) {
			$bname = 'Netscape';
			$ub    = 'Netscape';
		} elseif ( @preg_match( '/Trident/', $u_agent ) ) {
			$bname = 'Internet Explorer';
			$ub    = 'rv';
		}

		$known   = array( 'Version', $ub, 'other' );
		$pattern = '#(?<browser>' . join( '|', $known ) . ')[/ |:]+(?<version>[0-9.|a-zA-Z.]*)#';

		if ( ! @preg_match_all( $pattern, $u_agent, $matches ) ) {

		}

		$i = count( $matches['browser'] );
		if ( $i != 1 ) {
			if ( strripos( $u_agent, 'Version' ) < strripos( $u_agent, $ub ) ) {
				$version = isset( $matches['version'][0] ) ? $matches['version'][0] : 'unknown';
			} else {
				$version = isset( $matches['version'][1] ) ? $matches['version'][1] : 'unknown';
			}
		} else {
			$version = isset( $matches['version'][0] ) ? $matches['version'][0] : 'unknown';
		}

		if ( ( $version == null || $version == '' ) && ! preg_match( '/Edg/i', $u_agent ) ) {
			$version = '?';
		} elseif ( @preg_match( '/Edg/i', $u_agent ) && ( '' == $version || null == $version ) ) {
			$version = preg_replace( '/(.*?)(Edg\/)(\d+)/', '$3', $u_agent );
		}

		return array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'pattern'   => $pattern,
		);
	}

	function arflite_delete_single_entry_function() {
		global $arflite_db_record;

		if ( !isset( $_POST['_wpnonce'] ) || (isset( $_POST['_wpnonce'] ) && '' != $_POST['_wpnonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'arforms_wp_nonce' )) ) {
            echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Sorry, your request could not be processed due to security reason.',
							'arforms-form-builder'
						),
				)
			);
			die();
		}

        if( !current_user_can( 'arfviewentries' ) ){
           echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Sorry, you do not have permission to perform this action',
							'arforms-form-builder'
						),
				)
			);
			die();
        }

		$entry_id = isset( $_REQUEST['entry_id'] ) ? intval( $_REQUEST['entry_id'] ) : 0;
		$form_id  = isset( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';

		if ( $entry_id < 1 ) {
			echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Please select one or more record.',
							'arforms-form-builder'
						),
				)
			);
			die();
		}

		$del_res = $arflite_db_record->arflitedestroy( $entry_id );

		if ( $del_res ) {

			$total_records = '';
			if ( $form_id != '' ) {
				$total_records = $arflite_db_record->arflitegetRecordCount( (int) $form_id );
			}
			echo json_encode(
				array(
					'error'     => false,
					'message'   => __( 'Record is deleted successfully.', 'arforms-form-builder' ),
					'arftotrec' => $total_records,
				)
			);

		} else {
			echo json_encode(
				array(
					'error'   => true,
					'message' =>
						__(
							'Record could not be deleted',
							'arforms-form-builder'
						),
				)
			);
		}

		die();
	}

	function arflite_ajax_check_spam_filter() {
		
		$formRandomKey = isset( $_POST['form_random_key'] ) ? sanitize_text_field( $_POST['form_random_key'] ) : ''; //phpcs:ignore
		$validate      = true;
		$is_check_spam = true;

		if ( $is_check_spam ) {
			$validate = apply_filters( 'arflite_is_to_validate_spam_filter', $validate, $formRandomKey );
		}
		$response = array();
		if ( ! $validate ) {
			$response['error']   = true;
			$response['message'] = __( 'Spam Detected', 'arforms-form-builder' );
		} else {
			$response['error'] = false;
		}
		$response = apply_filters( 'arflite_reset_built_in_captcha', $response, $_POST ); //phpcs:ignore
		echo json_encode( $response );
		die();
	}
}
