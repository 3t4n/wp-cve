<?php

global $arflite_memory_limit, $arflitememorylimit, $arfliteversion;
if ( isset( $arflite_memory_limit ) && isset( $arflitememorylimit ) && ( $arflite_memory_limit * 1024 * 1024 ) > $arflitememorylimit ) {
	@ini_set( 'memory_limit', $arflite_memory_limit . 'M' );
}
class arfliteformcontroller {
	function __construct() {
		//add_action( 'admin_menu', array( $this, 'arflitemenu' ) );

		add_action( 'admin_head-toplevel_page_ARForms', array( $this, 'arflitehead' ) );

		add_action( 'admin_footer', array( $this, 'arflite_insert_form_popup' ) );

		add_action( 'wp_ajax_arflite_change_show_hide_column', array( $this, 'arflite_change_show_hide_column' ) );

		add_action( 'wp_ajax_arfliteupdateformbulkoption', array( $this, 'arfliteupdateformbulkoption' ) );

		add_action( 'wp_ajax_arfliteformsavealloptions', array( $this, 'arfliteformsavealloptions' ) );

		add_action( 'ARFormslite_shortcode_atts', array( $this, 'ARFormslite_shortcode_atts' ) );

		add_action( 'media_buttons', array( $this, 'arflite_insert_form_button' ), 20 );

		add_action( 'wp_ajax_arflitesavepreviewdata', array( $this, 'arfliteformsavealloptions' ) );

		add_action( 'wp_ajax_arflite_delete_form', array( $this, 'arflite_delete_form_function' ) );

		add_action( 'wp_ajax_arflite_csv_form', array( $this, 'arflite_csv_form_function' ) );

		add_action( 'wp_ajax_arflitechangestyle', array( $this, 'arflite_change_input_style' ) );

		add_action( 'wp_ajax_arflite_send_form_data_admin', array( $this, 'arflite_upload_image_from_admin' ) );

		add_filter( 'arfliteadminactionformlist', array( $this, 'arflite_process_bulk_form_actions' ) );

		add_filter( 'getarflitestylesheet', array( $this, 'arflitecustom_stylesheet' ), 10, 2 );

		add_filter( 'arflitecontent', array( $this, 'arflite_filter_content' ), 10, 3 );

		add_filter( 'plugin_action_links_' . ARFLITE_PLUGIN_BASE_FILE, array( $this, 'arflite_add_action_links' ) );

		add_filter( 'arflite_after_submit_sucess_outside', array( $this, 'arflite_after_submit_sucess_outside_function' ), 10, 2 );

		add_filter( 'arflite_replace_default_value_shortcode', array( $this, 'arflite_replace_default_value_shortcode_func' ), 10, 3 );

		add_action( 'wp_ajax_arflite_remove_preview_opt', array( $this, 'arflite_remove_preview_data' ) );

		add_action( 'arflite_rewrite_css_after_update', array( $this, 'arflite_rewrite_form_css' ), 10, 2 );

	}

	function arflite_upload_image_from_admin() {

		$fn = ( isset( $_SERVER['HTTP_X_FILENAME'] ) ? sanitize_file_name( $_SERVER['HTTP_X_FILENAME'] ) : false );

		if ( $fn && isset( $_FILES['files']['name'] ) ) {

			$upload_main_url = ARFLITE_UPLOAD_DIR;

			$arflitefilecontroller = new arflitefilecontroller( $_FILES['files'], false ); //phpcs:ignore

			if ( ! $arflitefilecontroller ) {
				echo '<p class="error_upload">' . $arflitefilecontroller->error_message . '</p>'; //phpcs:ignore
				die;
			}

			$arflitefilecontroller->check_cap    = true;
			$arflitefilecontroller->capabilities = array( 'arfviewforms', 'arfeditforms', 'arfchangesettings' );

			$arflitefilecontroller->check_nonce = true;
			$arflitefilecontroller->nonce_data  = isset( $_POST['_wpnonce_arflite'] ) ? sanitize_text_field( $_POST['_wpnonce_arflite'] ) : ''; //phpcs:ignore

			$arflitefilecontroller->nonce_action     = 'arflite_wp_nonce';
			$arflitefilecontroller->check_only_image = true;

			$arflitefilecontroller->check_specific_ext = false;
			$arflitefilecontroller->allowed_ext        = array();

			$destination = $upload_main_url . '/' . $fn;

			$upload_file = $arflitefilecontroller->arflite_process_upload( $destination );

			if ( false == $upload_file ) {
				echo '<p class="error_upload">' .$arflitefilecontroller->error_message . '</p>'; //phpcs:ignore
				die;
			} else {
				echo esc_html( $fn );
				die;
			}
		}

		die;

	}

	function arflite_upload_file_function( $source, $destination ) {
		if ( empty( $source ) || empty( $destination ) ) {
			return false;
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();
		global $wp_filesystem;

		$file_content = $wp_filesystem->get_contents( $source );

		$wp_filesystem->put_contents( $destination, $file_content, 0777 );

		return true;
	}

	function arflite_add_action_links( $links ) {
		global $arformsmain;
		$mylinks = array();

		if( !$arformsmain->arforms_is_pro_active() ){
			$mylinks[] = '<a href="https://www.arformsplugin.com/thank-you/?utm_source=lite_version&utm_medium=wordpress_org&utm_campaign=upgrade_to_pro" target="_blank" rel="noopener noreferrer" style="font-weight:bold;">' . __( 'Upgrade To Premium', 'arforms-form-builder' ) . '</a>';
		}

		$mylinks[] = '<a href="' . esc_url_raw( admin_url( 'admin.php?page=ARForms-addons' ) ) . '">Addons</a>';

		return array_merge( $mylinks, $links );
	}

	function arflite_class_to_hide_form( $id, $hide_form = false ) {
		global $wpdb, $ARFLiteMdlDb,$arflitemainhelper, $tbl_arf_forms;

		$form_data = wp_cache_get( 'arflite_form_options_' . $id );
		if ( false == $form_data ) {
			$form_data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_forms . '` WHERE `id` = %d', $id ) ); //phpcs:ignore
			wp_cache_set( 'arflite_form_options_' . $id, $form_data );
		}

		if ( empty( $form_data ) ) {
			return;
		}

		$form_options = $form_data->options;
		if ( ! is_array( $form_data->options ) ) {
			$form_options = maybe_unserialize( $form_data->options );
		}

		$arf_disable_form  = false;
		$arf_current_token = $arflitemainhelper->arflite_generate_captcha_code( 10 );
		if ( isset( $_SESSION[ 'arf_form_' . $arf_current_token . '_fileuploads' ] ) ) {
			$_SESSION[ 'arf_form_' . $arf_current_token . '_fileuploads' ] = array(); }

		return '';
	}

	function arflite_include_remove_form_func( $form, $values ) {
		require ARFLITE_VIEWS_PATH . '/arflite_form.php';
	}

	function arflite_process_bulk_form_actions( $arflite_errors ) {

		if ( !isset( $_GET['arflite_page_nonce'] ) || ( isset( $_GET['arflite_page_nonce'] ) && '' != $_GET['arflite_page_nonce'] && wp_verify_nonce( sanitize_text_field( $_GET['arflite_page_nonce'] ), 'arflite_page_nonce' ) ) ) {
			return;
		}

		if ( ! isset( $_POST ) ) {
			return;
		}

		global $arfliteform, $arflitemainhelper;

		$bulkaction = $arflitemainhelper->arflite_get_param( 'action1' );

		if ( $bulkaction == -1 ) {
			$bulkaction = $arflitemainhelper->arflite_get_param( 'action2' );
		}

		if ( ! empty( $bulkaction ) && strpos( $bulkaction, 'bulk_' ) === 0 ) {

			if ( isset( $_GET ) && isset( $_GET['action1'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
				$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action1'] ), '', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
			}

			if ( isset( $_GET ) && isset( $_GET['action2'] ) ) {
				$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action2'] ), '', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
			}

			$bulkaction = str_replace( 'bulk_', '', $bulkaction );
		} else {

			$bulkaction = '-1';

			if ( isset( $_POST['bulkaction1'] ) && $_POST['bulkaction1'] != '-1' ) {
				$bulkaction = sanitize_text_field( $_POST['bulkaction1'] );

			} elseif ( isset( $_POST['bulkaction2'] ) && $_POST['bulkaction2'] != '-1' ) {
				$bulkaction = sanitize_text_field( $_POST['bulkaction2'] );
			}
		}

		$ids = $arflitemainhelper->arflite_get_param( 'item-action', '' );

		if ( empty( $ids ) ) {

			$arflite_errors[] = __( 'Please select one or more records.', 'arforms-form-builder' );
		} else {

			if ( ! current_user_can( 'arfdeleteforms' ) ) {

				global $arformsmain;
				$admin_permission = $arformsmain->arforms_get_settings('admin_permission','general_settings');
				$arf_admin_permission = !empty($admin_permission) ? $admin_permission : esc_html__('You do not have permission to perform this action','arforms-form-builder');

				$arflite_errors[] = $arf_admin_permission;
			} else {

				if ( ! is_array( $ids ) ) {
					$ids = explode( ',', $ids );
				}

				if ( is_array( $ids ) ) {

					if ( $bulkaction == 'delete' ) {

						foreach ( $ids as $form_id ) {
							$res_var = $arfliteform->arflitedestroy( $form_id );
						}

						if ( $res_var ) {
							$message = __( 'Record is deleted successfully.', 'arforms-form-builder' );
						}
					}
				}
			}
		}

		$return_array = array(
			'error'   => @$arflite_errors,
			'message' => @$message,
		);

		return $return_array;
	}

	function arflitemenu() {

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Forms', 'arforms-form-builder' ), __( 'Manage Forms', 'arforms-form-builder' ), 'arfviewforms', 'ARForms-Lite', array( $this, 'arfliteroute' ) );
		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Add New Form', 'arforms-form-builder' ), '<span>' . __( 'Add New Form', 'arforms-form-builder' ) . '</span>', 'arfeditforms', 'ARForms-Lite&amp;arfaction=new&amp;isp=1', array( $this, 'arflite_new_form' ) );
		add_action( 'admin_head-ARForms_page_ARForms-new', array( $this, 'arflitehead' ) );
		add_action( 'admin_head-ARForms_page_ARForms-templates', array( $this, 'arflitehead' ) );
	}

	function arflitehead() {
		global $arflitelitesettings, $arfliteversion;
		require ARFLITE_VIEWS_PATH . '/arflite_head.php';
	}

	function arflite_list_form() {
		$params          = $this->arflite_get_params();
		$return_array    = apply_filters( 'arfliteadminactionformlist', array() );
		$$arflite_errors = !empty( $return_array['error'] ) ? $return_array['error'] : '';
		$message         = !empty( $return_array['message'] ) ? $return_array['message'] : '';
		return $this->arflite_display_forms_list( $params, $message, false, false, $$arflite_errors );
	}

	function arflite_new_form( $newformid = 0 ) {
		global $arfliteform, $arfliteajaxurl, $arflitemainhelper, $arflitefieldhelper, $arfliteformhelper, $arfliteversion;
		do_action( 'before_arformslite_editor_init' );
		$action         = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';
		$action         = $arflitemainhelper->arflite_get_param( $action );
		$random_form_id = false;
		if ( $action == 'new' || $action == 'duplicate' ) {
			global $wpdb, $ARFLiteMdlDb;
			$arffield_selection    = $arflitefieldhelper->arflite_field_selection();
			$form_name             = ( isset( $_REQUEST['form_name'] ) ) ? sanitize_text_field( $_REQUEST['form_name'] ) : '';
			$form_desc             = ( isset( $_REQUEST['form_desc'] ) ) ? sanitize_text_field( $_REQUEST['form_desc'] ) : '';
			$values['name']        = trim( $form_name );
			$values['description'] = trim( $form_desc );
			$random_form_id        = true;
			$values['id']          = 0;
			require ARFLITE_VIEWS_PATH . '/arflite_edit.php';
		}
	}

	function arflitecustom_stylesheet( $previous_css, $location = 'header' ) {
		global $arflite_style_settings, $arflitedatepickerloaded, $arflitecssloaded;
		$uploads  = wp_upload_dir();
		$css_file = array();
		if ( ! $arflitecssloaded ) {
			if ( is_readable( ARFLITE_UPLOAD_DIR . '/css/arforms.css' ) ) {
				if ( is_ssl() && ! preg_match( '/^https:\/\/.*\..*$/', $uploads['baseurl'] ) ) {
					$uploads['baseurl'] = str_replace( 'http://', 'https://', $uploads['baseurl'] );
				}
			} else {
				$css_file[] = ARFLITESCRIPTURL . '&amp;controller=settings';
			}
		}
		return $css_file;
	}

	function ARFormslite_popup_shortcode_atts( $atts ) {
		global $arfliteformcontroller;
		$fid = $atts['id'];
	}

	function ARFormslite_shortcode_atts( $atts ) {
		global $arflitereadonly, $arfliteformcontroller, $arfliteeditingentry, $arfliteshowfields, $ARFLiteMdlDb, $wpdb, $arflite_fid, $tbl_arf_entries;
		$arflite_fid         = $atts['id'];
		$arflitereadonly     = $atts['readonly'];
		$arfliteeditingentry = false;
		if ( ! is_array( $atts['fields'] ) ) {
			$arfliteshowfields = explode( ',', $atts['fields'] );
		} else {
			$arfliteshowfields = array();
		}

		if ( $atts['entry_id'] == 'last' ) {
			global $user_ID, $arfliterecordmeta;
			if ( $user_ID ) {
				$where_meta          = array(
					'form_id' => $atts['id'],
					'user_id' => $user_ID,
				);
				$arfliteeditingentry = $ARFLiteMdlDb->arfliteget_var( $tbl_arf_entries, $where_meta, 'id', 'created_date DESC' );
			}
		} elseif ( $atts['entry_id'] ) {
			$arfliteeditingentry = $atts['entry_id'];
		}
		$referer_info = ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) ? addslashes( sanitize_text_field( $_SERVER['HTTP_HOST'] ) . '/' . sanitize_text_field( $_SERVER['REQUEST_URI'] ) ) : '';
		$formid       = ( isset( $_REQUEST['id'] ) ) ? intval( $_REQUEST['id'] ) : '';
		$ipaddress    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '';
		$useragent    = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '';
	}

	function arflite_filter_content( $content, $form, $entry = false ) {
		global $arflitemainhelper, $arflitefieldhelper;
		if ( $entry && is_numeric( $entry ) ) {
			global $arflite_db_record;

			$entry_cache_obj = wp_cache_get( 'get_one_entry_record_' . $entry );

			if ( ! $entry_cache_obj ) {
				$entry = $arflite_db_record->arflitegetOne( $entry );

				wp_cache_set( 'get_one_entry_record_' . $entry->id, $entry );
			} else {
				$entry = $entry_cache_obj;
			}
		} else {
			if ( !isset( $_POST['arflite_entry_nonce'] ) || ( isset( $_POST['arflite_entry_nonce'] ) && '' != $_POST['arflite_entry_nonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['arflite_entry_nonce'] ), 'arflite_entry_nonce' ) ) ) {
				return $content;
			}
			$entry_id = ( isset( $_POST ) && isset( $_POST['id'] ) ) ? intval( $_POST['id'] ) : false;
			if ( $entry_id ) {
				global $arflite_db_record;
				$entry_cache_obj = wp_cache_get( 'get_one_entry_record_' . $entry->id );

				if ( ! $entry_cache_obj ) {
					$entry = $arflite_db_record->arflitegetOne( $entry_id );

					wp_cache_set( 'get_one_entry_record_' . $entry->id, $entry );
				} else {
					$entry = $entry_cache_obj;
				}
			}
		}

		if ( ! $entry ) {
			return $content;
		}

		if ( is_object( $form ) ) {
			$form = $form->id;
		}

		$shortcodes = $arflitemainhelper->arfliteget_shortcodes( $content, $form );

		$content = $arflitefieldhelper->arflitereplaceshortcodes( $content, $entry, $shortcodes );

		return $content;
	}

	function arflitepreview( $form_key = '' ) {
		do_action( 'arflite_wp_process_entry' );

		global $arfliteform, $arflitemainhelper, $arfliterecordcontroller, $arflitemaincontroller;

		$arfliterecordcontroller->arflite_register_scripts();

		//$arflitemaincontroller->arfliteafterinstall();

		header( 'Content-Type: text/html; charset=utf-8' );

		header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );

		$plugin = $arflitemainhelper->arflite_get_param( 'plugin' );

		$controller = $arflitemainhelper->arflite_get_param( 'controller' );

		$new = ( isset( $_REQUEST['ptype'] ) ) ? sanitize_text_field( $_REQUEST['ptype'] ) : '';

		$key = ( isset( $_GET['form'] ) ? sanitize_text_field( $_GET['form'] ) : ( isset( $_POST['form'] ) ? sanitize_text_field( $_POST['form'] ) : '' ) ); //phpcs:ignore

		if ( $key == '' && $form_key != '' ) {
			$key = $form_key;
		}

		$form = $arfliteform->arflitegetAll( array( 'form_key' => $key ), '', 1 );

		$width  = ( isset( $_GET['width'] ) ) ? sanitize_text_field( $_GET['width'] ) : '';
		$height = ( isset( $_GET['height'] ) ) ? sanitize_text_field( $_GET['height'] ) : '';

		 $_SESSION['arfaction_ptype'] = ( isset( $_REQUEST['ptype'] ) ) ? sanitize_text_field( $_REQUEST['ptype'] ) : '';

		require ARFLITE_VIEWS_PATH . '/arflite_preview.php';
	}

	function arflitedestroy() {

		if ( ! current_user_can( 'arfdeleteforms' ) ) {

			global $arformsmain;
			$admin_permission = $arformsmain->arforms_get_settings('admin_permission', 'general_settings');
			$arf_admin_permission = !empty( $admin_permission ) ? $admin_permission : 'You do not have permission to perform this action';

			wp_die( esc_attr( $arf_admin_permission ) );
		}

		global $arfliteform;

		$params = $this->arflite_get_params();

		$message = __( 'Form is Successfully Deleted', 'arforms-form-builder' );

		if ( $arfliteform->arflitedestroy( $params['id'] ) ) {
			$this->arflite_display_forms_list( $params, $message, '', 1 );
		}
	}

	function arflite_insert_form_button( $content ) {

		global $arformsmain;

		if ( 'content' != $content  || $arformsmain->arforms_is_pro_active() ) {
			return;
		}

		if ( isset( $_SERVER['PHP_SELF'] ) && ! in_array( basename( sanitize_text_field( $_SERVER['PHP_SELF'] ) ), array( 'post.php', 'page.php', 'post-new.php', 'page-new.php' ) ) ) {
			return;
		}

		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}

		echo '<a data-toggle="arfmodal" class="arfinsertformbuttoncls" onclick="arfliteopenarfinsertform();" href="#arfinsertform" title="' . esc_attr( __( 'Add ARForms Lite Form', 'arforms-form-builder' ) ) . '">
                Shortcodes</a>';
	}

	function arflite_insert_form_popup() {
		global $arformsmain;
		if( $arformsmain->arforms_is_pro_active()  ){
			return;
		}

		$page = isset( $_SERVER['PHP_SELF'] ) ? basename( sanitize_text_field( $_SERVER['PHP_SELF'] ) ) : '';

		if ( in_array( $page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) || ( isset( $_GET ) && isset( $_GET['page'] ) && $_GET['page'] == 'ARForms-Lite-entry-templates' ) ) {

			require ARFLITE_VIEWS_PATH . '/arflite_insert_form_popup.php';
		}
	}

	function arflite_display_forms_list( $params = false, $message = '', $page_params_ov = false, $current_page_ov = false, $arflite_errors = array() ) {

		global $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $arfliteform, $arflite_db_record, $arflitepagesize, $tbl_arf_forms;

		if ( ! $params ) {
			$params = $this->arflite_get_params();
		}

		if ( $message == '' ) {
			$message = $arflitemainhelper->arflite_frm_get_main_message();
		}

			$page_params = '&action=0&&arfaction=0&page=ARForms';

		if ( $params['template'] ) {

			$default_templates = $arfliteform->arflitegetAll( array( 'is_template' => 1 ) );

			$all_templates = $arfliteform->arflitegetAll( array( 'is_template' => 1 ), 'name' );
		}

		$where_clause = " (status is NULL OR status = '' OR status = 'published') AND is_template = " . $params['template'];

		$form_vars = $this->arflite_get_form_sort_vars( $params, $where_clause );

		$current_page = ( $current_page_ov ) ? $current_page_ov : $params['paged'];

		$page_params .= ( $page_params_ov ) ? $page_params_ov : $form_vars['page_params'];

		$sort_str = $form_vars['sort_str'];

		$sdir_str = $form_vars['sdir_str'];

		$search_str = $form_vars['search_str'];

		$record_count = $arflitemainhelper->arflitegetRecordCount( $form_vars['where_clause'], $tbl_arf_forms );

		$page_count = $arflitemainhelper->arflitegetPageCount( $arflitepagesize, $record_count, $tbl_arf_forms );

		$forms = $arflitemainhelper->arflitegetPage( $current_page, $arflitepagesize, $form_vars['where_clause'], $form_vars['order_by'], $tbl_arf_forms );

		$page_last_record = $arflitemainhelper->arflitegetLastRecordNum( $record_count, $current_page, $arflitepagesize );

		$page_first_record = $arflitemainhelper->arflitegetFirstRecordNum( $record_count, $current_page, $arflitepagesize );

		require ARFLITE_VIEWS_PATH . '/arflite_list.php';
	}

	function arflite_get_version_val() {
		return 1;
	}

	function arflite_get_form_sort_vars( $params, $where_clause = '' ) {

		$order_by = '';

		$page_params = '';

		$sort_str = $params['sort'];

		$sdir_str = $params['sdir'];

		$search_str = $params['search'];

		if ( ! empty( $search_str ) ) {

			$search_params = explode( ' ', $search_str );

			foreach ( $search_params as $search_param ) {

				if ( ! empty( $where_clause ) ) {
					$where_clause .= ' AND';
				}

				$where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_date like '%$search_param%')";
			}

			$page_params .= "&search=$search_str";
		}

		if ( ! empty( $sort_str ) ) {
			$page_params .= "&sort=$sort_str";
		}

		if ( ! empty( $sdir_str ) ) {
			$page_params .= "&sdir=$sdir_str";
		}

		switch ( $sort_str ) {

			case 'id':
			case 'name':
			case 'description':
			case 'form_key':
				$order_by .= " ORDER BY $sort_str";

				break;

			default:
				$order_by .= ' ORDER BY name';
		}

		if ( ( empty( $sort_str ) && empty( $sdir_str ) ) || $sdir_str == 'asc' ) {

			$order_by .= ' ASC';

			$sdir_str = 'asc';
		} else {

			$order_by .= ' DESC';

			$sdir_str = 'desc';
		}

		return compact( 'order_by', 'sort_str', 'sdir_str', 'search_str', 'where_clause', 'page_params' );
	}

	function arflite_get_params() {

		global $arflitemainhelper;

		$values = array();

		foreach ( array(
			'template' => 0,
			'id'       => '',
			'paged'    => 1,
			'form'     => '',
			'search'   => '',
			'sort'     => '',
			'sdir'     => '',
		) as $var => $default ) {
			$values[ $var ] = $arflitemainhelper->arflite_get_param( $var, $default );
		}

		return $values;
	}

	function arfliteroute() {

		global $wpdb, $arflitemainhelper;

		$action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

		$newformid = isset( $_REQUEST['newformid'] ) ? intval( $_REQUEST['newformid'] ) : 0;

		$action = $arflitemainhelper->arflite_get_param( $action );		

		if ( $action == 'new' || $action == 'duplicate' ) {
			return $this->arflite_new_form( $newformid );
		} elseif ( $action == 'edit' ) {
			require ARFLITE_VIEWS_PATH . '/arflite_edit.php';
			return;
		} elseif ( $action == 'destroy' ) {
			return $this->arflitedestroy();
		} elseif ( $action == 'list-form' ) {
			return $this->arflite_list_form();
		} elseif ( $action == 'preview' ) {
			  return $this->arflitepreview();
		} elseif ( $action == 'settings' ) {
			  return $this->edit();
		} else {
			$action = $arflitemainhelper->arflite_get_param( 'action' );
			if ( $action == -1 ) {
				$action = $arflitemainhelper->arflite_get_param( 'action2' );
			}
			if ( strpos( $action, 'bulk_' ) === 0 ) {
				if ( isset( $_GET ) && isset( $_GET['action'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
					$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action'] ), '', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
				}
				if ( isset( $_GET ) && isset( $_GET['action2'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
					$_SERVER['REQUEST_URI'] = str_replace( '&action=' . sanitize_text_field( $_GET['action2'] ), '', esc_url_raw( $_SERVER['REQUEST_URI'] ) );
				}
				return $this->arflite_list_form();
			} else {
				return $this->arflite_display_forms_list();
			}
		}
	}

	function arflite_change_show_hide_column() {

		if ( !isset( $_POST['arflite_wp_nonce'] ) || ( isset( $_POST['arflite_wp_nonce'] ) && '' != $_POST['arflite_wp_nonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['arflite_wp_nonce'] ), 'arflite_wp_nonce' ) ) ) {
			echo '';
			die;
		}

		$colsArray = isset( $_POST['colsArray'] ) ? sanitize_text_field( $_POST['colsArray'] ) : '';

		$new_arr = explode( ',', $colsArray );

		$array_hidden = array();

		foreach ( $new_arr as $key => $val ) {
			if ( $key % 2 == 0 ) {
				if ( $new_arr[ $key + 1 ] == 'hidden' ) {
					$array_hidden[] = $val;
				}
			}
		}

		$ser_arr = $array_hidden;

		update_option( 'arfformcolumnlist', $ser_arr );

		die();
	}

	function arfliteupdateformbulkoption() {
		
		if ( !isset( $_POST['_arforms_wpnonce'] ) || ( isset( $_POST['_arforms_wpnonce'] ) && '' != $_POST['_arforms_wpnonce'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_arforms_wpnonce'] ), 'arflite_wp_nonce' ) ) ) {
			echo json_encode(
				array(
					'error'       => true,
					'message'     => __( 'Sorry, your request could not be processed due to security reason', 'arforms-form-builder' ),
					'total_forms' => 0,
				)
			);
			die();
		}

		$return_array = apply_filters( 'arfliteadminactionformlist', array() );

		$arflite_errors = !empty( $return_array['error'] ) ? $return_array['error'] : '';
		$total_forms    = 0;
		$message        = !empty( $return_array['message'] ) ? $return_array['message'] : '';
		$action1        = ( isset( $_REQUEST['action1'] ) && $_REQUEST['action1'] != '' ) ? sanitize_text_field( $_REQUEST['action1'] ) : '';
		$action2        = ( isset( $_REQUEST['action3'] ) && $_REQUEST['action3'] != '' ) ? sanitize_text_field( $_REQUEST['action3'] ) : '';

		if ( $action1 == '-1' && $action2 == '-1' ) {
			echo json_encode(
				array(
					'error'       => true,
					'message'     => __( 'Please select valid action.', 'arforms-form-builder' ),
					'total_forms' => $total_forms,
				)
			);
			die();
		}
		$items = isset( $_REQUEST['item-action'] ) ? array_map( 'intval', $_REQUEST['item-action'] ) : array();
		if ( count( $items ) == 0 ) {
			echo json_encode(
				array(
					'error'       => true,
					'message'     => __( 'Please select one or more record to perform action.', 'arforms-form-builder' ),
					'total_forms' => $total_forms,
				)
			);
			die();
		}

		$items = $this->arfliteObjtoArray( $items );
		if ( $action1 == 'bulk_delete' || $action2 == 'bulk_delete' ) {

			if ( ! current_user_can( 'arfdeleteforms' ) ) {
				echo json_encode(
					array(
						'error'       => true,
						'message'     => __( 'Sorry, you do not have enough permission to perform this action', 'arforms-form-builder' ),
						'total_forms' => $total_forms,
					)
				);
				die;
			}

			global $wpdb, $ARFLiteMdlDb, $tbl_arf_forms;
			$where  = ' WHERE 1=1 ';
			$where .= ' AND id IN(' . implode( ',', $items ) . ') ';
			$query  = 'DELETE FROM ' . $tbl_arf_forms . ' ' . $where;
			$wpdb->query( $query ); //phpcs:ignore
			if ( $wpdb->last_error != '' ) {
				echo json_encode(
					array(
						'error'       => true,
						'message'     => $wpdb->last_error,
						'total_forms' => $total_forms,
					)
				);
				die();
			} else {
				$where       = "WHERE 1=1 AND is_template = %d AND (status is NULL OR status = '' OR status = 'published') ";
				$totalRecord = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) as total_forms FROM ' . $tbl_arf_forms . ' ' . $where . ' ', 0 ) ); //phpcs:ignore
				$total_forms = $totalRecord[0]->total_forms;
				echo json_encode(
					array(
						'error'         => false,
						'message'       => __( 'Record is deleted successfully.', 'arforms-form-builder' ),
						'total_forms'   => $total_forms,
						'deleted_forms' => $items,
					)
				);
				die();
			}
		}
		die();
	}

	function arflite_load_form_grid_data() {
		global $wpdb, $arflite_db_record, $ARFLiteMdlDb, $tbl_arf_forms;

		$grid_columns = array(
			'input'        => '',
			'id'           => 'ID',
			'name'         => 'Name',
			'entries'      => 'Entries',
			'shortcode'    => 'Shortcodes',
			'created_date' => 'Create Date',
			'action'       => 'Action',
		);

		$query = $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . " WHERE is_template = %d AND (status is NULL OR status = '' OR status = 'published') ORDER BY id DESC", 0 ); //phpcs:ignore

		$form_results = $wpdb->get_results( $query ); //phpcs:ignore

		$data = '';
		$ai   = 0;
		foreach ( $form_results as $frm_key => $form_data ) {
			$ni    = 0;
			$data .= "<tr data-form-id='" . $form_data->id . "'>";
			foreach ( $grid_columns as $key => $tmp_data ) {
				switch ( $key ) {
					case 'input':
						$data .= "<td class='box'><div class='arf_custom_checkbox_div arfmarginl20'><div class='arf_custom_checkbox_wrapper'><input id='cb-item-action-" . esc_attr( $form_data->id ) . "' class='chkstanard' type='checkbox' value='" . esc_attr( $form_data->id ) . "' name='item-action[]'>
                                <svg width='18px' height='18px'>
                                " . ARFLITE_CUSTOM_UNCHECKED_ICON . '
                                ' . ARFLITE_CUSTOM_CHECKED_ICON . "
                                </svg>
                            </div>
                        </div>
                        <label for='cb-item-action-{$form_data->id}'><span></span></label></td>";
						$ni++;
						break;
					case 'id':
						$data .= "<td class='id_column'>" . $form_data->id . '</td>';
						$ni++;
						break;
					case 'name':
						$edit_link = "?page=ARForms&arfaction=edit&id={$form_data->id}";
						if ( current_user_can( 'arfeditforms' ) ) {
							$data .= "<td class='form_title_column'><a class='row-title' href='{$edit_link}'>" . html_entity_decode( stripslashes( $form_data->name ) ) . '</a></td>';
						} else {
							$data .= "<td class='form_title_column'>" . html_entity_decode( stripslashes_deep( $form_data->name ) ) . '</td>';
						}

						$ni++;
						break;
					case 'entries':
						$entries = $arflite_db_record->arflitegetRecordCount( $form_data->id );
						$data   .= "<td class='entry_column'>" . ( ( current_user_can( 'arfviewentries' ) ) ? "<a href='" . esc_url( admin_url( 'admin.php' ) . '?page=ARForms-entries&form=' . $form_data->id ) . "'>" . $entries . '</a>' : $entries ) . '</td>';
						$ni++;
						break;
					case 'shortcode':
						$data .= "<td class='arf_shortcode_width'>
                        <div class='arf_shortcode_div'>
                            <div class='arf_copied grid_copy_icon' data-attr='[ARForms id={$form_data->id}]'>" . __( 'Click to Copy', 'arforms-form-builder' ) . "</div>
                            <input type='text' class='shortcode_textfield' readonly='readonly' onclick='this.select();' onfocus='this.select();' value='[ARForms id=" . esc_attr( $form_data->id ) . "]' />
                        </div></td>";
						$ni++;
						break;
					case 'created_date':
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
						$data .= "<td class='arf_created_date_col'>" . date( $date_format_new, strtotime( $form_data->created_date ) ) . '</td>';
						$ni++;
						break;
					case 'action':
						$div = "<div class='arf-row-actions'>";
						if ( current_user_can( 'arfeditforms' ) ) {
							$edit_link = "?page=ARForms&arfaction=edit&id={$form_data->id}";
							$div      .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Edit Form', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( $edit_link ) . "'><svg width='30px' height='30px' viewBox='-5 -4 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill='#ffffff' d='M17.469,7.115v10.484c0,1.25-1.014,2.264-2.264,2.264H3.75c-1.25,0-2.262-1.014-2.262-2.264V5.082  c0-1.25,1.012-2.264,2.262-2.264h9.518l-2.264,2.001H3.489v13.042h11.979V9.379L17.469,7.115z M15.532,2.451l-0.801,0.8l2.4,2.401  l0.801-0.8L15.532,2.451z M17.131,0.85l-0.799,0.801l2.4,2.4l0.801-0.801L17.131,0.85z M6.731,11.254l2.4,2.4l7.201-7.202  l-2.4-2.401L6.731,11.254z M5.952,14.431h2.264l-2.264-2.264V14.431z' /></svg></a></div>";

							$duplicate_link = "?page=ARForms&arfaction=duplicate&id={$form_data->id}";

							if ( current_user_can( 'arfviewentries' ) ) {

								$div .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Form Entry', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( "?page=ARForms-entries&arfaction=list&form={$form_data->id}" ) . "' ><svg width='30px' height='30px' viewBox='-7 -4 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M1.489,19.829V0.85h14v18.979H1.489z M13.497,2.865H3.481v14.979  h10.016V2.865z M10.489,15.806H4.493v-2h5.996V15.806z M4.495,9.806h7.994v2H4.495V9.806z M4.495,5.806h7.994v2H4.495V5.806z' /></svg></a></div>";
							}

							$div .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Duplicate Form', 'arforms-form-builder' ) . "'><a href='" . wp_nonce_url( $duplicate_link ) . "' ><svg width='30px' height='30px' viewBox='-5 -5 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M16.501,15.946V2.85H5.498v-2h11.991v0.025h1.012v15.07H16.501z   M15.489,19.81h-14V3.894h14V19.81z M13.497,5.909H3.481v11.979h10.016V5.909z'/></svg></a></div>";
							if ( current_user_can( 'arfviewentries' ) ) {
								$div .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Export Entries', 'arforms-form-builder' ) . "'><a onclick='arfliteaction_func(\"export_csv\", \"{$form_data->id}\");'><svg width='30px' height='30px' viewBox='-3 -5 30 30' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill='#ffffff' d='M16.477,10.586V7.091c0-0.709-0.576-1.283-1.285-1.283H2.772c-0.709,0-1.283,0.574-1.283,1.283v3.495    c0,0.709,0.574,1.283,1.283,1.283h12.419C15.9,11.87,16.477,11.295,16.477,10.586z M5.131,9.887c0.277,0,0.492-0.047,0.67-0.116    l0.138,0.862c-0.208,0.092-0.6,0.17-1.047,0.17c-1.217,0-1.995-0.74-1.995-1.925c0-1.102,0.753-2.002,2.156-2.002    c0.308,0,0.646,0.054,0.893,0.146L5.762,7.892C5.623,7.83,5.415,7.776,5.107,7.776c-0.616,0-1.016,0.438-1.01,1.055    C4.098,9.524,4.561,9.887,5.131,9.887z M8.525,10.772c-0.492,0-1.369-0.107-1.654-0.262l0.646-0.839    C7.732,9.8,8.179,9.957,8.525,9.957c0.354,0,0.501-0.124,0.501-0.317c0-0.191-0.116-0.284-0.556-0.43    C7.695,8.948,7.395,8.524,7.402,8.077c0-0.701,0.6-1.231,1.531-1.231c0.44,0,0.832,0.101,1.063,0.216L9.789,7.87    c-0.17-0.094-0.494-0.216-0.816-0.216c-0.285,0-0.446,0.116-0.446,0.309c0,0.177,0.147,0.269,0.608,0.431    c0.717,0.246,1.016,0.608,1.023,1.162C10.158,10.255,9.604,10.772,8.525,10.772z M13.54,10.725h-1.171l-1.371-3.766h1.271    l0.509,1.748c0.092,0.315,0.162,0.617,0.216,0.916h0.023c0.062-0.308,0.124-0.593,0.208-0.916l0.486-1.748h1.23L13.54,10.725z     M19.961,0.85H6.02c-0.295,0-0.535,0.239-0.535,0.534v2.45h1.994V2.79h11.014v11.047l-2.447-0.002    c-0.158,0-0.309,0.064-0.421,0.177c-0.11,0.109-0.173,0.26-0.173,0.418l0.012,3.427H7.479V12.8H5.484v6.501    c0,0.294,0.239,0.533,0.535,0.533h10.389c0.153,0,0.297-0.065,0.398-0.179l3.553-4.048c0.088-0.098,0.135-0.224,0.135-0.355V1.384    C20.496,1.089,20.255,0.85,19.961,0.85z'/></svg></a></div>";
							}
						}

						global $arflite_style_settings, $arfliteformhelper;

						$target_url = $arfliteformhelper->arflite_get_direct_link( $form_data->form_key );

						$target_url = $target_url . '&ptype=list';

						$div .= "<div class='arfformicondiv arfhelptip' title='" . __( 'Preview', 'arforms-form-builder' ) . "'><a class='openpreview' href='javascript:void(0)'  data-url='" . $target_url . $tb_width . $tb_height . "&whichframe=preview&TB_iframe=true'><svg width='30px' height='30px' viewBox='-3 -8 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M12.993,15.23c-7.191,0-11.504-7.234-11.504-7.234  S5.801,0.85,12.993,0.85c7.189,0,11.504,7.19,11.504,7.19S20.182,15.23,12.993,15.23z M12.993,2.827  c-5.703,0-8.799,5.214-8.799,5.214s3.096,5.213,8.799,5.213c5.701,0,8.797-5.213,8.797-5.213S18.694,2.827,12.993,2.827z   M12.993,11.572c-1.951,0-3.531-1.581-3.531-3.531s1.58-3.531,3.531-3.531c1.949,0,3.531,1.581,3.531,3.531  S14.942,11.572,12.993,11.572z'/></svg></a></div>";

						if ( current_user_can( 'arfdeleteforms' ) ) {
							$delete_link = "?page=ARForms&arfaction=destroy&id={$form_data->id}";
							$id          = $form_data->id;
							$div        .= "<div class='arfformicondiv arfhelptip arfdeleteform_div_" . $id . "' title='" . __( 'Delete', 'arforms-form-builder' ) . "'><a class='arflite-cursor-pointer' id='delete_pop' data-toggle='arfmodal' data-id='" . $id . "'><svg width='30px' height='30px' viewBox='-5 -5 32 32' class='arfsvgposition'><path xmlns='http://www.w3.org/2000/svg' fill-rule='evenodd' clip-rule='evenodd' fill='#ffffff' d='M18.435,4.857L18.413,19.87L3.398,19.88L3.394,4.857H1.489V2.929  h1.601h3.394V0.85h8.921v2.079h3.336h1.601l0,0v1.928H18.435z M15.231,4.857H6.597H5.425l0.012,13.018h10.945l0.005-13.018H15.231z   M11.4,6.845h2.029v9.065H11.4V6.845z M8.399,6.845h2.03v9.065h-2.03V6.845z' /></svg></a></div>";
						}
						$data .= "<td class='arf_action_cell'>" . $div . '</td>';
						$ni++;
						break;
				}
			}
			$data .= '</tr>';
			$ai++;
		}

		return $data;
	}

	function arflite_wp_kses_recursive( $arra_input ){
		$check_allowed_html = arflite_retrieve_attrs_for_wp_kses(true);
		if ( is_array( $arra_input ) ) {
			return array_map( array( $this, __FUNCTION__ ), $arra_input );
		} else {
			return wp_kses( stripslashes_deep( $arra_input ), $check_allowed_html );
		}
	}
	function arfliteformsavealloptions() {
		global $arfliteform, $wpdb, $ARFLiteMdlDb, $arflitemainhelper, $arflitesettingcontroller, $arflitefield, $arfliteformhelper, $arflitefieldhelper, $arflitemaincontroller, $tbl_arf_forms, $tbl_arf_fields;

		$arflite_validate_request = $arflitemaincontroller->arflite_check_user_cap( 'arfeditforms', true );

		if ( 'success' != $arflite_validate_request ) {
			$arf_error_obj = arflite_json_decode( $arflite_validate_request, true );
			if ( ! empty( $arf_error_obj[1] ) && 'security_error' == $arf_error_obj[1] ) {
				echo 'reauth';
			} else {
				echo 'false^|^' . json_encode( $arf_error_obj );
			}
			die;
		}
		$is_preview = false;

		$arf_preview_form_data = array();
		$arflite_filterd_form = isset( $_REQUEST['filtered_form'] ) ?  $_REQUEST['filtered_form'] : array(); //phpcs:ignore
		$str = json_decode( stripslashes_deep( $arflite_filterd_form ), true );;
		$temp_form_id          = 0;

		if ( sanitize_text_field( $str['arfaction'] ) == 'new' || sanitize_text_field( $str['arfaction'] ) == 'duplicate' ) {
			$temp_form_id = intval( $str['id'] );
			$form_id      = $id = $str['id'] = 0;
		} else {
			$form_id = $id = intval( $str['id'] );
		}

		if ( ! empty( $_POST['arfaction'] ) && sanitize_text_field( $_POST['arfaction'] ) == 'preview' ) { //phpcs:ignore
			$is_preview                  = true;
			$form_id                     = $id = intval( $str['arfmf'] );
			$arf_preview_form_data['id'] = $form_id;
		}

		$arflite_errors = apply_filters( 'arflitevalidationofcurrentform', array(), $str );

		if ( count( $arflite_errors ) > 0 ) {
			echo 'false^|^' . json_encode( $arflite_errors );
			die();
		}

		$_REQUEST = $values = $str;
		
		$values   = apply_filters( 'arflitechangevaluesbeforeupdateform', $values );

		$ar_allowed_html_data = arflite_retrieve_attrs_for_wp_kses(true);
		
		/* form name */
		$values['name'] = !empty( $values['name'] ) ? wp_kses( $values['name'], $ar_allowed_html_data ) : '';

		/* form description */
		$values['description'] = !empty( $values['description'] ) ? wp_kses( $values['description'], $ar_allowed_html_data ) : '';
		/* useremail subject */
		$values['options']['ar_email_subject'] = !empty( $values['options']['ar_email_subject'] ) ? wp_kses( $values['options']['ar_email_subject'] , $ar_allowed_html_data ) : '';
		
		$values['options']['ar_email_message'] = !empty( $values['options']['ar_email_message'] ) ? wp_kses(htmlspecialchars_decode($values['options']['ar_email_message']), $ar_allowed_html_data) : '';

		/* uer from email */
		$values['options']['ar_user_from_email'] = !empty( $values['options']['ar_user_from_email'] ) ? wp_kses( $values['options']['ar_user_from_email'], $ar_allowed_html_data ) : '';

		/* user reply to email */
		$values['options']['reply_to'] = !empty( $values['options']['reply_to'] ) ? wp_kses( $values['options']['reply_to'], $ar_allowed_html_data ) : '';

		/* admin email subject */
		$values['options']['admin_email_subject'] = !empty( $values['options']['admin_email_subject'] ) ? wp_kses( $values['options']['admin_email_subject'], $ar_allowed_html_data ) : '';

		/* admin cc email */
		$values['options']['admin_cc_email'] = !empty( $values['options']['admin_cc_email'] ) ? wp_kses( $values['options']['admin_cc_email'], $ar_allowed_html_data ) : '';

		/* admin bcc email */
		$values['options']['admin_bcc_email'] = !empty( $values['options']['admin_bcc_email'] ) ? wp_kses( $values['options']['admin_bcc_email'], $ar_allowed_html_data ) : '';

		/* admin from name */
		$values['options']['ar_admin_from_name'] = !empty( $values['options']['ar_admin_from_name'] ) ? wp_kses( $values['options']['ar_admin_from_name'], $ar_allowed_html_data ) : '';

		/* admin from email */
		$values['options']['ar_admin_from_email'] = !empty( $values['options']['ar_admin_from_email'] ) ? wp_kses( $values['options']['ar_admin_from_email'], $ar_allowed_html_data ) : '';
		
		/* admin reply to email */
		$values['options']['ar_admin_reply_to_email'] = !empty( $values['options']['ar_admin_reply_to_email'] ) ? wp_kses( $values['options']['ar_admin_reply_to_email'], $ar_allowed_html_data ) : '';

		/* admin email message */
		$values['options']['ar_admin_email_message'] = !empty( $values['options']['ar_admin_email_message'] ) ? wp_kses(htmlspecialchars_decode($values['options']['ar_admin_email_message']), $ar_allowed_html_data) : '';

		/* success message */
		$values['options']['success_msg'] = !empty( $values['options']['success_msg'] ) ? wp_kses( $values['options']['success_msg'], $ar_allowed_html_data ) : '';

		do_action( 'arflitebeforeupdateform', $id, $values, false );
		do_action( 'arflitebeforeupdateform_' . $id, $id, $values, false );
		$db_data = array();
		if ( isset( $values['options'] ) || isset( $values['item_meta'] ) || isset( $values['field_options'] ) ) {
			$values['status'] = 'published';
		}

		if ( isset( $values['form_key'] ) ) {
			$values['form_key'] = $arflitemainhelper->arflite_get_unique_key( $values['form_key'], $tbl_arf_forms, 'form_key', $id );
		}

		$form_fields  = array( 'form_key', 'name', 'description', 'status' );
		$new_values   = array();
		$double_optin = 0;

		$options = array();
		if ( isset( $values['options'] ) ) {

			$defaults = $arfliteformhelper->arflite_get_default_opts();

			foreach ( $defaults as $var => $default ) {
				if ( $var == 'notification' ) {
					$options[ $var ] = isset( $values[ $var ] ) ? $values[ $var ] : $default;
				} else {
					$options[ $var ] = isset( $values['options'][ $var ] ) ? $values['options'][ $var ] : $default;
				}
			}
			$options['admin_cc_email'] = isset( $values['options']['admin_cc_email'] ) ? sanitize_text_field( $values['options']['admin_cc_email'] ) : '';

			$options['admin_bcc_email'] = isset( $values['options']['admin_bcc_email'] ) ? sanitize_text_field( $values['options']['admin_bcc_email'] ) : '';

			$options['arf_data_with_url'] = isset( $values['options']['arf_data_with_url'] ) ? sanitize_text_field( $values['options']['arf_data_with_url'] ) : 0;

			$options['arf_show_post_value'] = isset( $values['options']['arf_show_post_value'] ) ? sanitize_text_field( $values['options']['arf_show_post_value'] ) : 'no';

			$options['arf_post_value_url'] = isset( $values['options']['arf_post_value_url'] ) ? sanitize_text_field( $values['options']['arf_post_value_url'] ) : '';

			$options['arf_form_other_css'] = isset( $values['options']['arf_form_other_css'] ) ? sanitize_textarea_field( str_replace( "\n", '', str_replace( "\t", '', $values['options']['arf_form_other_css'] ) ) ) : '';

			$options['custom_style'] = isset( $values['options']['custom_style'] ) ? sanitize_text_field( $values['options']['custom_style'] ) : 0;

			$allowed_html = arflite_retrieve_attrs_for_wp_kses();

			$options['before_html'] = isset( $values['options']['before_html'] ) ? wp_kses( $values['options']['before_html'], $allowed_html ) : '';

			$options['after_html'] = isset( $values['options']['after_html'] ) ? wp_kses( $values['options']['after_html'], $allowed_html ) : '';

			$options = apply_filters( 'arfliteformoptionsbeforeupdateform', $options, $values );

			$options['display_title_form'] = isset( $values['options']['display_title_form'] ) ? intval( $values['options']['display_title_form'] ) : 0;

			$double_optin = $options['arf_enable_double_optin'] = isset( $values['options']['arf_enable_double_optin'] ) ? $values['options']['arf_enable_double_optin'] : 0;

			$options['email_to'] = sanitize_text_field( $options['reply_to'] );

			$options['arf_sub_track_code'] = isset( $values['options']['arf_sub_track_code'] ) ? addslashes( rawurlencode( sanitize_textarea_field( $values['options']['arf_sub_track_code'] ) ) ) : '';

			$options['arf_field_order'] = isset( $values['arf_field_order'] ) ? sanitize_text_field( $values['arf_field_order'] ) : json_encode( array() );

			$options['arf_field_resize_width'] = isset( $values['arf_field_resize_width'] ) ? sanitize_text_field( $values['arf_field_resize_width'] ) : json_encode( array() );
			$options['define_template']        = isset( $values['define_template'] ) ? intval( $values['define_template'] ) : 0;

			$options = apply_filters( 'arflite_save_form_options_outside', $options, $values, $form_id );
		}

		foreach ( $values as $value_key => $value ) {
			if ( in_array( $value_key, $form_fields ) ) {
				$db_data[ $value_key ]               = $this->arfliteHtmlEntities( $value, true );
				$arf_preview_form_data[ $value_key ] = $value;
			}
		}

		$sel_fields = $wpdb->prepare( 'SELECT id FROM ' . $tbl_arf_fields . ' where form_id = %d', $id ); //phpcs:ignore

		$sel_fields_arr = $wpdb->get_results( $sel_fields, 'ARRAY_A' ); //phpcs:ignore

		$old_field_array    = array();
		$change_field_value = array();
		if ( ! empty( $sel_fields_arr ) && count( $sel_fields_arr ) > 0 ) {
			foreach ( $sel_fields_arr as $id_temp => $temp_value ) {
				array_push( $old_field_array, $temp_value['id'] );
			}
		}

		$form_css = array();

		$form_css['display_title_form'] = isset( $options['display_title_form'] ) ? sanitize_text_field( $options['display_title_form'] ) : '';

		$form_css['arfmainformwidth'] = isset( $_REQUEST['arffw'] ) ? sanitize_text_field( $_REQUEST['arffw'] ) : '';
		$form_css['arfmainformwidth_tablet'] = isset($_REQUEST['arffw_tablet']) ? sanitize_text_field($_REQUEST['arffw_tablet']) : '';
        $form_css['arfmainformwidth_mobile'] = isset($_REQUEST['arffw_mobile']) ? sanitize_text_field($_REQUEST['arffw_mobile']) : '';

		$form_css['form_width_unit'] = isset( $_REQUEST['arffu'] ) ? sanitize_text_field( $_REQUEST['arffu'] ) : '';
		$form_css['form_width_unit_tablet'] = isset($_REQUEST['arffu_tablet']) ? sanitize_text_field($_REQUEST['arffu_tablet']) : '';
        $form_css['arf_width_unit_mobile'] = isset($_REQUEST['arffu_mobile']) ? sanitize_text_field($_REQUEST['arffu_mobile']) : '';

		$form_css['text_direction'] = isset( $_REQUEST['arftds'] ) ? sanitize_text_field( $_REQUEST['arftds'] ) : '';

		$form_css['form_align'] = isset( $_REQUEST['arffa'] ) ? sanitize_text_field( $_REQUEST['arffa'] ) : '';

		$form_css['arfmainfieldsetpadding'] = isset( $_REQUEST['arfmfsp'] ) ? sanitize_text_field( $_REQUEST['arfmfsp'] ) : '';
		$form_css['arfmainfieldsetpadding_tablet'] = isset( $_REQUEST['arfmfsp_tablet'] ) ? sanitize_text_field( $_REQUEST['arfmfsp_tablet'] ) : '';
		$form_css['arfmainfieldsetpadding_mobile'] = isset( $_REQUEST['arfmfsp_mobile'] ) ? sanitize_text_field( $_REQUEST['arfmfsp_mobile'] ) : '';

		$form_css['form_border_shadow'] = isset( $_REQUEST['arffbs'] ) ? sanitize_text_field( $_REQUEST['arffbs'] ) : '';

		$form_css['fieldset'] = isset( $_REQUEST['arfmfis'] ) ? sanitize_text_field( $_REQUEST['arfmfis'] ) : '';

		$form_css['arfmainfieldsetradius'] = isset( $_REQUEST['arfmfsr'] ) ? sanitize_text_field( $_REQUEST['arfmfsr'] ) : '';

		$form_css['arfmainfieldsetcolor'] = isset( $_REQUEST['arfmfsc'] ) ? sanitize_text_field( $_REQUEST['arfmfsc'] ) : '';

		$form_css['arfmainformbordershadowcolorsetting'] = isset( $_REQUEST['arffboss'] ) ? sanitize_text_field( $_REQUEST['arffboss'] ) : '';

		$form_css['arfmainformtitlecolorsetting'] = isset( $_REQUEST['arfftc'] ) ? sanitize_text_field( $_REQUEST['arfftc'] ) : '';

		$form_css['check_weight_form_title'] = isset( $_REQUEST['arfftws'] ) ? sanitize_text_field( $_REQUEST['arfftws'] ) : '';

		$form_css['form_title_font_size'] = isset( $_REQUEST['arfftfss'] ) ? sanitize_text_field( $_REQUEST['arfftfss'] ) : '';

		$form_css['arfmainformtitlepaddingsetting'] = isset( $_REQUEST['arfftps'] ) ? sanitize_text_field( $_REQUEST['arfftps'] ) : '';

		$form_css['arfmainformbgcolorsetting'] = isset( $_REQUEST['arffbcs'] ) ? sanitize_text_field( $_REQUEST['arffbcs'] ) : '';

		$form_css['font'] = isset( $_REQUEST['arfmfs'] ) ? sanitize_text_field( $_REQUEST['arfmfs'] ) : '';

		$form_css['label_color'] = isset( $_REQUEST['arflcs'] ) ? sanitize_text_field( $_REQUEST['arflcs'] ) : '';

		$form_css['weight'] = isset( $_REQUEST['arfmfws'] ) ? sanitize_text_field( $_REQUEST['arfmfws'] ) : '';

		$form_css['font_size'] = isset( $_REQUEST['arffss'] ) ? sanitize_text_field( $_REQUEST['arffss'] ) : '';

		$form_css['align'] = isset( $_REQUEST['arffrma'] ) ? sanitize_text_field( $_REQUEST['arffrma'] ) : '';

		$form_css['position'] = isset( $_REQUEST['arfmps'] ) ? sanitize_text_field( $_REQUEST['arfmps'] ) : '';

		$form_css['width'] = isset( $_REQUEST['arfmws'] ) ? sanitize_text_field( $_REQUEST['arfmws'] ) : '';

		$form_css['width_unit'] = isset( $_REQUEST['arfmwu'] ) ? sanitize_text_field( $_REQUEST['arfmwu'] ) : '';

		$form_css['arfdescfontsizesetting'] = isset( $_REQUEST['arfdfss'] ) ? sanitize_text_field( $_REQUEST['arfdfss'] ) : '';

		$form_css['arfdescalighsetting'] = isset( $_REQUEST['arfdas'] ) ? sanitize_text_field( $_REQUEST['arfdas'] ) : '';

		$form_css['hide_labels'] = isset( $_REQUEST['arfhl'] ) ? sanitize_text_field( $_REQUEST['arfhl'] ) : '';

		$form_css['check_font'] = isset( $_REQUEST['arfcbfs'] ) ? sanitize_text_field( $_REQUEST['arfcbfs'] ) : '';

		$form_css['check_weight'] = isset( $_REQUEST['arfcbws'] ) ? sanitize_text_field( $_REQUEST['arfcbws'] ) : '';

		$form_css['field_font_size'] = isset( $_REQUEST['arfffss'] ) ? sanitize_text_field( $_REQUEST['arfffss'] ) : '';

		$form_css['text_color'] = isset( $_REQUEST['arftcs'] ) ? sanitize_text_field( $_REQUEST['arftcs'] ) : '';

		$form_css['border_radius'] = isset( $_REQUEST['arfmbs'] ) ? sanitize_text_field( $_REQUEST['arfmbs'] ) : '';
		$form_css['border_radius_tablet'] = isset($_REQUEST['arfmbs_tablet']) ? sanitize_text_field($_REQUEST['arfmbs_tablet']) : '';
        $form_css['border_radius_mobile'] = isset($_REQUEST['arfmbs_mobile']) ? sanitize_text_field($_REQUEST['arfmbs_mobile']) : '';

		$form_css['border_color'] = isset( $_REQUEST['arffmboc'] ) ? sanitize_text_field( $_REQUEST['arffmboc'] ) : '';

		$form_css['arffieldborderwidthsetting'] = isset( $_REQUEST['arffbws'] ) ? sanitize_text_field( $_REQUEST['arffbws'] ) : '';

		$form_css['arffieldborderstylesetting'] = isset( $_REQUEST['arffbss'] ) ? sanitize_text_field( $_REQUEST['arffbss'] ) : '';

		$form_css['arfsubmitbuttonstyle'] = isset( $_REQUEST['arfsubmitbuttonstyle'] ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttonstyle'] ) : 'border';

		if ( isset( $_REQUEST['arffiu'] ) && sanitize_text_field( $_REQUEST['arffiu'] ) == '%' && isset( $_REQUEST['arfmfiws'] ) && sanitize_text_field( $_REQUEST['arfmfiws'] ) > '100' ) {
			$form_css['field_width'] = sanitize_text_field( '100' );
		} else {
			$form_css['field_width'] = isset( $_REQUEST['arfmfiws'] ) ? sanitize_text_field( $_REQUEST['arfmfiws'] ) : '';
		}
		$form_css['field_width_unit'] = isset( $_REQUEST['arffiu'] ) ? sanitize_text_field( $_REQUEST['arffiu'] ) : '';

		if ( isset( $_REQUEST['arffiu_tablet'] ) && sanitize_text_field( $_REQUEST['arffiu_tablet'] ) == '%' && isset( $_REQUEST['arfmfiws_tablet'] ) && sanitize_text_field( $_REQUEST['arfmfiws_tablet'] ) > '100' ) {
			$form_css['field_width_tablet'] = sanitize_text_field( '100' );
		} else {
			$form_css['field_width_tablet'] = isset( $_REQUEST['arfmfiws_tablet'] ) ? sanitize_text_field( $_REQUEST['arfmfiws_tablet'] ) : '';
		}

		if ( isset( $_REQUEST['arffiu_mobile'] ) && sanitize_text_field( $_REQUEST['arffiu_mobile'] ) == '%' && isset( $_REQUEST['arfmfiws_mobile'] ) && sanitize_text_field( $_REQUEST['arfmfiws_mobile'] ) > '100' ) {
			$form_css['field_width_mobile'] = sanitize_text_field( '100' );
		} else {
			$form_css['field_width_mobile'] = isset( $_REQUEST['arfmfiws_mobile'] ) ? sanitize_text_field( $_REQUEST['arfmfiws_mobile'] ) : '';
		}
		

		$form_css['field_width_unit_tablet'] = isset( $_REQUEST['arffiu_tablet'] ) ? sanitize_text_field( $_REQUEST['arffiu_tablet'] ) : '';
		$form_css['field_width_unit_mobile'] = isset( $_REQUEST['arffiu_mobile'] ) ? sanitize_text_field( $_REQUEST['arffiu_mobile'] ) : '';

		$form_css['arffieldmarginssetting'] = isset( $_REQUEST['arffms'] ) ? sanitize_text_field( $_REQUEST['arffms'] ) : '';

		$form_css['arffieldinnermarginssetting'] = isset( $_REQUEST['arffims'] ) ? sanitize_text_field( $_REQUEST['arffims'] ) : '';

		$form_css['bg_color'] = isset( $_REQUEST['arffmbc'] ) ? sanitize_text_field( $_REQUEST['arffmbc'] ) : '';

		$form_css['arfbgactivecolorsetting'] = isset( $_REQUEST['arfbcas'] ) ? sanitize_text_field( $_REQUEST['arfbcas'] ) : '';

		$form_css['arfborderactivecolorsetting'] = isset( $_REQUEST['arfbacs'] ) ? sanitize_text_field( $_REQUEST['arfbacs'] ) : '';

		$form_css['arferrorbgcolorsetting'] = isset( $_REQUEST['arfbecs'] ) ? sanitize_text_field( $_REQUEST['arfbecs'] ) : '';

		$form_css['arferrorbordercolorsetting'] = isset( $_REQUEST['arfboecs'] ) ? sanitize_text_field( $_REQUEST['arfboecs'] ) : '';

		$form_css['arfradioalignsetting'] = isset( $_REQUEST['arfras'] ) ? sanitize_text_field( $_REQUEST['arfras'] ) : '';

		$form_css['arfcheckboxalignsetting'] = isset( $_REQUEST['arfcbas'] ) ? sanitize_text_field( $_REQUEST['arfcbas'] ) : '';

		$form_css['auto_width'] = isset( $_REQUEST['arfautowidthsetting'] ) ? sanitize_text_field( $_REQUEST['arfautowidthsetting'] ) : '';

		$form_css['arfcalthemename'] = isset( $_REQUEST['arffths'] ) ? sanitize_text_field( $_REQUEST['arffths'] ) : '';

		$form_css['arfcalthemecss'] = isset( $_REQUEST['arffthc'] ) ? sanitize_text_field( $_REQUEST['arffthc'] ) : '';

		$form_css['date_format'] = isset( $_REQUEST['arffdaf'] ) ? sanitize_text_field( $_REQUEST['arffdaf'] ) : '';

		$form_css['arfsubmitbuttontext'] = isset( $_REQUEST['arfsubmitbuttontext'] ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttontext'] ) : '';

		$form_css['arfsubmitweightsetting'] = isset( $_REQUEST['arfsbwes'] ) ? sanitize_text_field( $_REQUEST['arfsbwes'] ) : '';

		$form_css['arfsubmitbuttonfontsizesetting'] = isset( $_REQUEST['arfsbfss'] ) ? sanitize_text_field( $_REQUEST['arfsbfss'] ) : '';

		$form_css['arfsubmitbuttonwidthsetting'] = isset( $_REQUEST['arfsbws'] ) ? sanitize_text_field( $_REQUEST['arfsbws'] ) : '';
		$form_css['arfsubmitbuttonwidthsetting_tablet'] = isset( $_REQUEST['arfsbws_tablet'] ) ? sanitize_text_field( $_REQUEST['arfsbws_tablet'] ) : '';
		$form_css['arfsubmitbuttonwidthsetting_mobile'] = isset( $_REQUEST['arfsbws_mobile'] ) ? sanitize_text_field( $_REQUEST['arfsbws_mobile'] ) : '';

		$form_css['arfsubmitbuttonheightsetting'] = isset( $_REQUEST['arfsbhs'] ) ? sanitize_text_field( $_REQUEST['arfsbhs'] ) : '';
		$form_css['submit_bg_color']              = isset( $_REQUEST['arfsbbcs'] ) ? sanitize_text_field( $_REQUEST['arfsbbcs'] ) : '';

		$form_css['arfsubmitbuttonbgcolorhoversetting'] = isset( $_REQUEST['arfsbchs'] ) ? sanitize_text_field( $_REQUEST['arfsbchs'] ) : '';

		$form_css['arfsubmitbgcolor2setting'] = isset( $_REQUEST['arfsbcs'] ) ? sanitize_text_field( $_REQUEST['arfsbcs'] ) : '';

		$form_css['arfsubmittextcolorsetting'] = isset( $_REQUEST['arfsbtcs'] ) ? sanitize_text_field( $_REQUEST['arfsbtcs'] ) : '';

		$form_css['arfsubmitbordercolorsetting'] = isset( $_REQUEST['arfsbobcs'] ) ? sanitize_text_field( $_REQUEST['arfsbobcs'] ) : '';

		$form_css['arfsubmitborderwidthsetting'] = isset( $_REQUEST['arfsbbws'] ) ? sanitize_text_field( $_REQUEST['arfsbbws'] ) : '';

		$form_css['arfsubmitboxxoffsetsetting'] = isset( $_REQUEST['arfsbxos'] ) ? sanitize_text_field( $_REQUEST['arfsbxos'] ) : '';

		$form_css['arfsubmitboxyoffsetsetting'] = isset( $_REQUEST['arfsbyos'] ) ? sanitize_text_field( $_REQUEST['arfsbyos'] ) : '';

		$form_css['arfsubmitboxblursetting'] = isset( $_REQUEST['arfsbbs'] ) ? sanitize_text_field( $_REQUEST['arfsbbs'] ) : '';

		$form_css['arfsubmitboxshadowsetting'] = isset( $_REQUEST['arfsbsps'] ) ? sanitize_text_field( $_REQUEST['arfsbsps'] ) : '';

		$form_css['arfsubmitborderradiussetting'] = isset( $_REQUEST['arfsbbrs'] ) ? sanitize_text_field( $_REQUEST['arfsbbrs'] ) : '';

		$form_css['arfsubmitshadowcolorsetting'] = isset( $_REQUEST['arfsbscs'] ) ? sanitize_text_field( $_REQUEST['arfsbscs'] ) : '';

		$form_css['arfsubmitbuttonmarginsetting'] = isset( $_REQUEST['arfsbms'] ) ? sanitize_text_field( $_REQUEST['arfsbms'] ) : '';
		$form_css['submit_bg_img']                = isset( $_REQUEST['arfsbis'] ) ? sanitize_text_field( $_REQUEST['arfsbis'] ) : '';

		$form_css['submit_hover_bg_img'] = isset( $_REQUEST['arfsbhis'] ) ? sanitize_text_field( $_REQUEST['arfsbhis'] ) : '';

		$form_css['error_font'] = isset( $_REQUEST['arfmefs'] ) ? sanitize_text_field( $_REQUEST['arfmefs'] ) : '';

		$form_css['error_font_other'] = isset( $_REQUEST['arfmofs'] ) ? sanitize_text_field( $_REQUEST['arfmofs'] ) : '';

		$form_css['arffontsizesetting'] = isset( $_REQUEST['arfmefss'] ) ? sanitize_text_field( $_REQUEST['arfmefss'] ) : '';

		$form_css['arferrorbgsetting'] = isset( $_REQUEST['arfmebs'] ) ? sanitize_text_field( $_REQUEST['arfmebs'] ) : '';

		$form_css['arferrortextsetting'] = isset( $_REQUEST['arfmets'] ) ? sanitize_text_field( $_REQUEST['arfmets'] ) : '';

		$form_css['arferrorbordersetting'] = isset( $_REQUEST['arfmebos'] ) ? sanitize_text_field( $_REQUEST['arfmebos'] ) : '';

		$form_css['arfsucessbgcolorsetting'] = isset( $_REQUEST['arfmsbcs'] ) ? sanitize_text_field( $_REQUEST['arfmsbcs'] ) : '';

		$form_css['arfsucessbordercolorsetting'] = isset( $_REQUEST['arfmsbocs'] ) ? sanitize_text_field( $_REQUEST['arfmsbocs'] ) : '';

		$form_css['arfsucesstextcolorsetting'] = isset( $_REQUEST['arfmstcs'] ) ? sanitize_text_field( $_REQUEST['arfmstcs'] ) : '';

		$form_css['arfformerrorbgcolorsettings'] = isset( $_REQUEST['arffebgc'] ) ? sanitize_text_field( $_REQUEST['arffebgc'] ) : '';

		$form_css['arfformerrorbordercolorsettings'] = isset( $_REQUEST['arffebrdc'] ) ? sanitize_text_field( $_REQUEST['arffebrdc'] ) : '';

		$form_css['arfformerrortextcolorsettings'] = isset( $_REQUEST['arffetxtc'] ) ? sanitize_text_field( $_REQUEST['arffetxtc'] ) : '';

		$form_css['arfsubmitalignsetting'] = isset( $_REQUEST['arfmsas'] ) ? sanitize_text_field( $_REQUEST['arfmsas'] ) : '';

		$form_css['checkbox_radio_style'] = isset( $_REQUEST['arfcrs'] ) ? sanitize_text_field( $_REQUEST['arfcrs'] ) : '';

		$form_css['arfmainform_bg_img'] = isset( $_REQUEST['arfmfbi'] ) ? sanitize_text_field( $_REQUEST['arfmfbi'] ) : '';

		$form_css['arfmainform_color_skin'] = isset( $_REQUEST['arfmcs'] ) ? sanitize_text_field( $_REQUEST['arfmcs'] ) : '';

		$form_css['arfinputstyle'] = isset( $_REQUEST['arfinpst'] ) ? sanitize_text_field( $_REQUEST['arfinpst'] ) : sanitize_text_field( 'standard' );

		$form_css['arfsubmitfontfamily'] = isset( $_REQUEST['arfsff'] ) ? sanitize_text_field( $_REQUEST['arfsff'] ) : '';

		$form_css['arfmainfieldcommonsize'] = isset( $_REQUEST['arfmainfieldcommonsize'] ) ? sanitize_text_field( $_REQUEST['arfmainfieldcommonsize'] ) : sanitize_text_field( '3' );

		$form_css['arfdatepickerbgcolorsetting']   = isset( $_REQUEST['arfdbcs'] ) ? sanitize_text_field( $_REQUEST['arfdbcs'] ) : sanitize_text_field( '#23b7e5' );
		$form_css['arfdatepickertextcolorsetting'] = isset( $_REQUEST['arfdtcs'] ) ? sanitize_text_field( $_REQUEST['arfdtcs'] ) : sanitize_text_field( '#ffffff' );

		$form_css['arfuploadbtntxtcolorsetting'] = isset( $_REQUEST['arfuptxt'] ) ? sanitize_text_field( $_REQUEST['arfuptxt'] ) : sanitize_text_field( '#ffffff' );
		$form_css['arfuploadbtnbgcolorsetting']  = isset( $_REQUEST['arfupbg'] ) ? sanitize_text_field( $_REQUEST['arfupbg'] ) : sanitize_text_field( '#077BDD' );

		$form_css['arf_bg_position_x'] = ( isset( $_REQUEST['arf_bg_position_x'] ) && $_REQUEST['arf_bg_position_x'] != '' ) ? sanitize_text_field( $_REQUEST['arf_bg_position_x'] ) : sanitize_text_field( 'left' );
		$form_css['arf_bg_position_y'] = ( isset( $_REQUEST['arf_bg_position_y'] ) && $_REQUEST['arf_bg_position_y'] != '' ) ? sanitize_text_field( $_REQUEST['arf_bg_position_y'] ) : sanitize_text_field( 'top' );

		$form_css['arf_bg_position_input_x'] = ( isset( $_REQUEST['arf_bg_position_input_x'] ) && $_REQUEST['arf_bg_position_input_x'] != '' ) ? sanitize_text_field( $_REQUEST['arf_bg_position_input_x'] ) : '';
		$form_css['arf_bg_position_input_y'] = ( isset( $_REQUEST['arf_bg_position_input_y'] ) && $_REQUEST['arf_bg_position_input_y'] != '' ) ? sanitize_text_field( $_REQUEST['arf_bg_position_input_y'] ) : '';

		$form_css['arfmainfieldsetpadding_1']         = ( isset( $_REQUEST['arfmainfieldsetpadding_1'] ) && $_REQUEST['arfmainfieldsetpadding_1'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_1'] ) : 0;
		$form_css['arfmainfieldsetpadding_2']         = ( isset( $_REQUEST['arfmainfieldsetpadding_2'] ) && $_REQUEST['arfmainfieldsetpadding_2'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_2'] ) : 0;
		$form_css['arfmainfieldsetpadding_3']         = ( isset( $_REQUEST['arfmainfieldsetpadding_3'] ) && $_REQUEST['arfmainfieldsetpadding_3'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_3'] ) : 0;
		$form_css['arfmainfieldsetpadding_4']         = ( isset( $_REQUEST['arfmainfieldsetpadding_4'] ) && $_REQUEST['arfmainfieldsetpadding_4'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_4'] ) : 0;

		$form_css['arfmainfieldsetpadding_1_tablet']         = ( isset( $_REQUEST['arfmainfieldsetpadding_1_tablet'] ) && $_REQUEST['arfmainfieldsetpadding_1'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_1_tablet'] ) : '';
		$form_css['arfmainfieldsetpadding_2_tablet']         = ( isset( $_REQUEST['arfmainfieldsetpadding_2_tablet'] ) && $_REQUEST['arfmainfieldsetpadding_2_tablet'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_2_tablet'] ) : '';
		$form_css['arfmainfieldsetpadding_3_tablet']         = ( isset( $_REQUEST['arfmainfieldsetpadding_3_tablet'] ) && $_REQUEST['arfmainfieldsetpadding_3_tablet'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_3_tablet'] ) : '';
		$form_css['arfmainfieldsetpadding_4_tablet']         = ( isset( $_REQUEST['arfmainfieldsetpadding_4_tablet'] ) && $_REQUEST['arfmainfieldsetpadding_4_tablet'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_4_tablet'] ) : '';

		$form_css['arfmainfieldsetpadding_1_mobile']         = ( isset( $_REQUEST['arfmainfieldsetpadding_1_mobile'] ) && $_REQUEST['arfmainfieldsetpadding_1'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_1_mobile'] ) : '';
		$form_css['arfmainfieldsetpadding_2_mobile']         = ( isset( $_REQUEST['arfmainfieldsetpadding_2_mobile'] ) && $_REQUEST['arfmainfieldsetpadding_2_mobile'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_2_mobile'] ) : '';
		$form_css['arfmainfieldsetpadding_3_mobile']         = ( isset( $_REQUEST['arfmainfieldsetpadding_3_mobile'] ) && $_REQUEST['arfmainfieldsetpadding_3_mobile'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_3_mobile'] ) : '';
		$form_css['arfmainfieldsetpadding_4_mobile']         = ( isset( $_REQUEST['arfmainfieldsetpadding_4_mobile'] ) && $_REQUEST['arfmainfieldsetpadding_4_mobile'] != '' ) ? sanitize_text_field( $_REQUEST['arfmainfieldsetpadding_4_mobile'] ) : '';

		$form_css['arfmainformtitlepaddingsetting_1'] = ( isset( $_REQUEST['arfformtitlepaddingsetting_1'] ) && $_REQUEST['arfformtitlepaddingsetting_1'] != '' ) ? sanitize_text_field( $_REQUEST['arfformtitlepaddingsetting_1'] ) : 0;
		$form_css['arfmainformtitlepaddingsetting_2'] = ( isset( $_REQUEST['arfformtitlepaddingsetting_2'] ) && $_REQUEST['arfformtitlepaddingsetting_2'] != '' ) ? sanitize_text_field( $_REQUEST['arfformtitlepaddingsetting_2'] ) : 0;
		$form_css['arfmainformtitlepaddingsetting_3'] = ( isset( $_REQUEST['arfformtitlepaddingsetting_3'] ) && $_REQUEST['arfformtitlepaddingsetting_3'] != '' ) ? sanitize_text_field( $_REQUEST['arfformtitlepaddingsetting_3'] ) : 0;
		$form_css['arfmainformtitlepaddingsetting_4'] = ( isset( $_REQUEST['arfformtitlepaddingsetting_4'] ) && $_REQUEST['arfformtitlepaddingsetting_4'] != '' ) ? sanitize_text_field( $_REQUEST['arfformtitlepaddingsetting_4'] ) : 0;
		$form_css['arffieldinnermarginssetting_1']    = ( isset( $_REQUEST['arffieldinnermarginsetting_1'] ) && $_REQUEST['arffieldinnermarginsetting_1'] != '' ) ? sanitize_text_field( $_REQUEST['arffieldinnermarginsetting_1'] ) : 0;
		$form_css['arffieldinnermarginssetting_2']    = ( isset( $_REQUEST['arffieldinnermarginsetting_2'] ) && $_REQUEST['arffieldinnermarginsetting_2'] != '' ) ? sanitize_text_field( $_REQUEST['arffieldinnermarginsetting_2'] ) : 0;
		$form_css['arffieldinnermarginssetting_3']    = ( isset( $_REQUEST['arffieldinnermarginsetting_3'] ) && $_REQUEST['arffieldinnermarginsetting_3'] != '' ) ? sanitize_text_field( $_REQUEST['arffieldinnermarginsetting_3'] ) : 0;
		$form_css['arffieldinnermarginssetting_4']    = ( isset( $_REQUEST['arffieldinnermarginsetting_4'] ) && $_REQUEST['arffieldinnermarginsetting_4'] != '' ) ? sanitize_text_field( $_REQUEST['arffieldinnermarginsetting_4'] ) : 0;
		$form_css['arfsubmitbuttonmarginsetting_1']   = ( isset( $_REQUEST['arfsubmitbuttonmarginsetting_1'] ) && $_REQUEST['arfsubmitbuttonmarginsetting_1'] != '' ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttonmarginsetting_1'] ) : 0;
		$form_css['arfsubmitbuttonmarginsetting_2']   = ( isset( $_REQUEST['arfsubmitbuttonmarginsetting_2'] ) && $_REQUEST['arfsubmitbuttonmarginsetting_2'] != '' ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttonmarginsetting_2'] ) : 0;
		$form_css['arfsubmitbuttonmarginsetting_3']   = ( isset( $_REQUEST['arfsubmitbuttonmarginsetting_3'] ) && $_REQUEST['arfsubmitbuttonmarginsetting_3'] != '' ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttonmarginsetting_3'] ) : 0;
		$form_css['arfsubmitbuttonmarginsetting_4']   = ( isset( $_REQUEST['arfsubmitbuttonmarginsetting_4'] ) && $_REQUEST['arfsubmitbuttonmarginsetting_4'] != '' ) ? sanitize_text_field( $_REQUEST['arfsubmitbuttonmarginsetting_4'] ) : 0;

		$form_css['arfcheckradiostyle']          = isset( $_REQUEST['arfcksn'] ) ? sanitize_text_field( $_REQUEST['arfcksn'] ) : '';
		$form_css['arfcheckradiocolor']          = isset( $_REQUEST['arfcksc'] ) ? sanitize_text_field( $_REQUEST['arfcksc'] ) : '';
		$form_css['arf_checked_checkbox_icon']   = isset( $_REQUEST['arf_checkbox_icon'] ) ? sanitize_text_field( $_REQUEST['arf_checkbox_icon'] ) : '';
		$form_css['enable_arf_checkbox']         = isset( $_REQUEST['enable_arf_checkbox'] ) ? sanitize_text_field( $_REQUEST['enable_arf_checkbox'] ) : '';
		$form_css['arf_checked_radio_icon']      = isset( $_REQUEST['arf_radio_icon'] ) ? sanitize_text_field( $_REQUEST['arf_radio_icon'] ) : '';
		$form_css['enable_arf_radio']            = isset( $_REQUEST['enable_arf_radio'] ) ? sanitize_text_field( $_REQUEST['enable_arf_radio'] ) : '';
		$form_css['checked_checkbox_icon_color'] = isset( $_REQUEST['cbscol'] ) ? sanitize_text_field( $_REQUEST['cbscol'] ) : '';
		$form_css['checked_radio_icon_color']    = isset( $_REQUEST['rbscol'] ) ? sanitize_text_field( $_REQUEST['rbscol'] ) : '';

		$form_css['arferrorstyle']         = isset( $_REQUEST['arfest'] ) ? sanitize_text_field( $_REQUEST['arfest'] ) : '';
		$form_css['arferrorstylecolor']    = isset( $_REQUEST['arfestc'] ) ? sanitize_text_field( $_REQUEST['arfestc'] ) : '';
		$form_css['arferrorstylecolor2']   = isset( $_REQUEST['arfestc2'] ) ? sanitize_text_field( $_REQUEST['arfestc2'] ) : '';
		$form_css['arferrorstyleposition'] = isset( $_REQUEST['arfestbc'] ) ? sanitize_text_field( $_REQUEST['arfestbc'] ) : '';

		$form_css['arfsuccessmsgposition'] = isset( $_REQUEST['arfsuccessmsgposition'] ) ? sanitize_text_field( $_REQUEST['arfsuccessmsgposition'] ) : '';

		$form_css['arfstandarderrposition'] = isset( $_REQUEST['arfstndrerr'] ) ? sanitize_text_field( $_REQUEST['arfstndrerr'] ) : 'relative';

		$form_css['arfvalidationbgcolorsetting']   = isset( $_REQUEST['arfmvbcs'] ) ? sanitize_text_field( $_REQUEST['arfmvbcs'] ) : sanitize_text_field( '#ed4040' );
		$form_css['arfvalidationtextcolorsetting'] = isset( $_REQUEST['arfmvtcs'] ) ? sanitize_text_field( $_REQUEST['arfmvtcs'] ) : sanitize_text_field( '#ffffff' );

		$form_css['arfformtitlealign']  = isset( $_REQUEST['arffta'] ) ? sanitize_text_field( $_REQUEST['arffta'] ) : '';
		$form_css['arfsubmitautowidth'] = isset( $_REQUEST['arfsbaw'] ) ? sanitize_text_field( $_REQUEST['arfsbaw'] ) : '';

		$form_css['arftitlefontfamily'] = isset( $_REQUEST['arftff'] ) ? sanitize_text_field( $_REQUEST['arftff'] ) : '';

		if ( isset( $_REQUEST['arfmainform_opacity'] ) and sanitize_text_field( $_REQUEST['arfmainform_opacity'] ) > 1 ) {
			$form_css['arfmainform_opacity'] = sanitize_text_field( '1' );
		} else {
			$form_css['arfmainform_opacity'] = isset( $_REQUEST['arfmainform_opacity'] ) ? sanitize_text_field( $_REQUEST['arfmainform_opacity'] ) : '';
		}

		if ( isset( $_REQUEST['arfplaceholder_opacity'] ) and sanitize_text_field( $_REQUEST['arfplaceholder_opacity'] ) > 1 ) {
			$form_css['arfplaceholder_opacity'] = sanitize_text_field( '1' );
		} else {
			$form_css['arfplaceholder_opacity'] = isset( $_REQUEST['arfplaceholder_opacity'] ) ? sanitize_text_field( $_REQUEST['arfplaceholder_opacity'] ) : sanitize_text_field( '0.50' );
		}

		$form_css['arfmainfield_opacity'] = isset( $_REQUEST['arfmfo'] ) ? sanitize_text_field( $_REQUEST['arfmfo'] ) : '';
		if ( sanitize_text_field( $_REQUEST['arfinpst'] ) == 'material' ) {
			$form_css['arfmainfield_opacity'] = intval( 1 );
		}
		$form_css['arf_req_indicator'] = isset( $_REQUEST['arfrinc'] ) ? sanitize_text_field( $_REQUEST['arfrinc'] ) : sanitize_text_field( '0' );

		$form_css['prefix_suffix_bg_color']   = isset( $_REQUEST['pfsfsbg'] ) ? sanitize_text_field( $_REQUEST['pfsfsbg'] ) : '';
		$form_css['prefix_suffix_icon_color'] = isset( $_REQUEST['pfsfscol'] ) ? sanitize_text_field( $_REQUEST['pfsfscol'] ) : '';

		$form_css['arf_tooltip_bg_color']   = isset( $_REQUEST['arf_tooltip_bg_color'] ) ? sanitize_text_field( $_REQUEST['arf_tooltip_bg_color'] ) : '';
		$form_css['arf_tooltip_font_color'] = isset( $_REQUEST['arf_tooltip_font_color'] ) ? sanitize_text_field( $_REQUEST['arf_tooltip_font_color'] ) : '';
		$form_css['arf_tooltip_width']      = isset( $_REQUEST['arf_tooltip_width'] ) ? sanitize_text_field( $_REQUEST['arf_tooltip_width'] ) : '';
		$form_css['arftooltipposition']     = isset( $_REQUEST['arflitetippos'] ) ? sanitize_text_field( $_REQUEST['arflitetippos'] ) : '';
		$form_css['arfcommonfont']          = isset( $_REQUEST['arfcommonfont'] ) ? sanitize_text_field( $_REQUEST['arfcommonfont'] ) : sanitize_text_field( 'Helvetica' );

		$form_css['arfmainbasecolor'] = isset( $_REQUEST['arfmbsc'] ) ? sanitize_text_field( $_REQUEST['arfmbsc'] ) : '';

		$form_css['arfsliderselectioncolor'] = isset( $_REQUEST['asldrsl'] ) ? sanitize_text_field( $_REQUEST['asldrsl'] ) : '';
		$form_css['arfslidertrackcolor']     = isset( $_REQUEST['asltrcl'] ) ? sanitize_text_field( $_REQUEST['asltrcl'] ) : '';

		if ( $form_css['arfcheckradiostyle'] == 'custom' ) {
			$is_font_awesome                = true;
			$options['font_awesome_loaded'] = $is_font_awesome;
		}

		$options = apply_filters( 'arflite_trim_values', $options );

		if ( ! $is_preview ) {
			if ( ! empty( $form_css ) ) {
				$db_data['options']  		 = maybe_serialize( $options );
				$db_data['form_css'] 		 = maybe_serialize( $form_css );
				$db_data['status']   		 = 'published';
				$db_data['arf_is_lite_form'] = 1;
				if ( $str['arfaction'] == 'new' || $str['arfaction'] == 'duplicate' ) {
					$db_data['form_key']     = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_forms, 'form_key' );
					$db_data['created_date'] = date( 'Y-m-d H:i:s' );
					$query_results           = $wpdb->insert( $tbl_arf_forms, $db_data );
					$form_id                 = $wpdb->insert_id;
					$str['id']               = $form_id;
					$values['id']            = $form_id;
					$id                      = $form_id;
					$query_results           = true;
				} else {
					if ( ! empty( $db_data ) ) {
						$query_results = $wpdb->update( $tbl_arf_forms, $db_data, array( 'id' => $id ) );
						if ( $query_results ) {
							wp_cache_delete( $id, 'arfform' );
						}
					} else {
						$query_results = true;
					}
				}
				$wpdb->update( $tbl_arf_forms, array('arf_lite_form_id' => $id), array( 'id' => $id ) );
			} else {
				$query_results = true;
			}
		} else {
			$options['arf_field_order']        = json_decode( $options['arf_field_order'] );
			$options['arf_field_resize_width'] = json_decode( $options['arf_field_resize_width'] );

			$arf_preview_form_data['options']  = $options;
			$arf_preview_form_data['form_css'] = $form_css;
		}

		$selectbox_field_available      = '';
		$radio_field_available          = '';
		$checkbox_field_available       = '';
		$new_field_order                = array();
		$temp_order                     = json_decode( $values['arf_field_order'], true );
		$type_array                     = array();
		$content_array                  = array();
		$new_id_array                   = array();
		$is_font_awesome                = 0;
		$is_prefix_suffix_enable        = 0;
		$is_checkbox_img_enable         = 0;
		$is_radio_img_enable            = 0;
		$is_tooltip                     = 0;
		$is_input_mask                  = 0;
		$animate_number                 = 0;
		$round_total_number             = 0;
		$arf_hide_bar_belt              = 0;
		$html_running_total_field_array = array();
		$google_captcha_loaded          = 0;
		$loaded_field                   = array();
		$i                              = 0;
		$return_json_data               = array();
		$changed_field_value            = array();
		$arf_temp_fields                = array();
		$hidden_field_ids               = array();
		$default_value_field_array      = apply_filters( 'arflite_default_value_array_field_type', array( 'checkbox', 'radio' ) );
		$default_value_from_itemmeta    = apply_filters( 'arflite_default_value_array_field_type_from_itemmeta', array( 'select', 'hidden' ) );

		foreach ( $values as $key => $value ) {
			if ( preg_match( '/(arf_field_data_)/', $key ) ) {

				$name_array         = explode( 'arf_field_data_', $key );
				$field_id_new       = $name_array[1];
				$field_otions_new   = array();
				$field_otions_new   = json_decode( $value, true );
				$type_array[ $key ] = $field_otions_new['type'];
				$default_value      = '';
				$field_options      = '';
				if ( in_array( $field_otions_new['type'], $default_value_field_array ) ) {
					$default_value = isset( $field_otions_new['default_value'] ) ? $field_otions_new['default_value'] : '';
				} elseif ( in_array( $field_otions_new['type'], $default_value_from_itemmeta ) ) {
					$default_value = isset( $values['item_meta'][ $field_id_new ] ) ? $values['item_meta'][ $field_id_new ] : '';
				} elseif ( $field_otions_new['default_value'] != '' ) {
					$default_value = $field_otions_new['default_value'];
				}

				$clear_on_focus          = isset( $field_otions_new['frm_clear_field'] ) ? $field_otions_new['frm_clear_field'] : 0;
				$default_blank           = isset( $field_otions_new['frm_default_blank'] ) ? $field_otions_new['frm_default_blank'] : 0;
				$value                   = json_decode( $value, true );
				$value['default_value']  = $default_value;
				$value['clear_on_focus'] = $clear_on_focus;
				$value['default_blank']  = $default_blank;
				if ( $default_blank == 1 || $clear_on_focus == 1 ) {
					$value['value'] = ( $default_value == '' ) ? $value['placeholdertext'] : $default_value;
				}

				$value = apply_filters( 'arflite_trim_values', $value );

				//if ( $is_preview ) {
					$new_temp_value = $value;
				/* } else {
					$new_temp_value = json_encode( $value );
				} */

				$check_allowed_html = arflite_retrieve_attrs_for_wp_kses(true);

				if( is_array( $new_temp_value ) ){
					$value = $this->arflite_wp_kses_recursive( $new_temp_value );
				} else {
					$value = wp_kses( stripslashes_deep( $new_temp_value ), $check_allowed_html );
				}

				if( is_array( $value ) && !$is_preview ){
					$value = json_encode( $value );
				}

				if ( isset( $field_otions_new['options'] ) && ! empty( $field_otions_new['options'] ) ) {
					if ( is_array( $field_otions_new['options'] ) ) {
						if ( $is_preview ) {
							$field_options = $field_otions_new['options'];
						} else {
							$field_options = json_encode( $field_otions_new['options'] );
						}
					} elseif ( is_object( $field_otions_new['options'] ) ) {
						$field_otions_new['options'] = $this->arfliteObjtoArray( $field_otions_new['options'] );
						if ( $is_preview ) {
							$field_options = $field_otions_new['options'];
						} else {
							$field_options = json_encode( $field_otions_new['options'] );
						}
					}
				}
				if ( ! isset( $values['item_meta'] ) ) {
					$values['item_meta'] = array();
				}

				$existing_keys = array_keys( $values['item_meta'] );

				if ( in_array( $field_id_new, $old_field_array ) ) {

					$field_data_to_save = array(
						'name'          => isset( $field_otions_new['name'] ) ? sanitize_text_field( $field_otions_new['name'] ) : '',
						'type'          => sanitize_text_field( $field_otions_new['type'] ),
						'options'       => $field_options,
						'required'      => isset( $field_otions_new['required'] ) ? sanitize_text_field( $field_otions_new['required'] ) : sanitize_text_field( '0' ),
						'field_options' => $value,
						'form_id'       => intval( $id ),
						'option_order'  => isset( $field_otions_new['option_order'] ) ? $field_otions_new['option_order'] : '',
					);

					if ( $field_otions_new['type'] == 'email' ) {
						if ( $field_otions_new['confirm_email'] == '1' ) {
							$email_field_key = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_fields, 'field_key' );
							if ( is_array( $options['arf_field_order'] ) || is_object( $options['arf_field_order'] ) ) {
								$confirm_field_order_arr = arflite_json_decode( json_encode( $options['arf_field_order'] ), true );
							} else {
								$confirm_field_order_arr = json_decode( $options['arf_field_order'], true );
							}
							$confirm_field_order = $confirm_field_order_arr[ $field_id_new . '_confirm' ];

							$arf_temp_fields[ 'confirm_email_' . $field_id_new ] = array(
								'key'                 => $email_field_key,
								'order'               => $confirm_field_order,
								'parent_field_id'     => $field_id_new,
								'confirm_inner_class' => $field_otions_new['confirm_email_inner_classes'],
							);
						}
					}
					if ( ! $is_preview ) {
						$update = $wpdb->update( $tbl_arf_fields, $field_data_to_save, array( 'id' => $field_id_new ) );
					} else {
						if ( ! isset( $arf_preview_form_data['fields'] ) ) {
							$arf_preview_form_data['fields'] = array();
						}
						$field_data_to_save['id']          = $field_id_new;
						$field_data_to_save['field_key']   = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_fields, 'field_key' );
						$arf_preview_form_data['fields'][] = $field_data_to_save;

					}

					$new_id_array[ $i ]['old_id'] = $field_id_new;
					$new_id_array[ $i ]['new_id'] = $field_id_new;
					$new_id_array[ $i ]['name']   = isset( $field_otions_new['name'] ) ? $field_otions_new['name'] : '';
					$new_id_array[ $i ]['type']   = $field_otions_new['type'];
					$loaded_field[ $i ]           = $field_otions_new['type'];

					if ( ( isset( $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] ) && $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] == 1 ) || ( isset( $field_otions_new[ 'enable_arf_suffix_' . $field_id_new ] ) && $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] == 1 ) || ( sanitize_text_field( $_REQUEST['arfcksn'] ) == 'custom' ) ) {
						$is_font_awesome = 1;
					}

					if ( $field_otions_new['type'] == 'checkbox' && ( isset( $field_otions_new['use_image'] ) && $field_otions_new['use_image'] == 1 ) ) {
						$is_font_awesome        = 1;
						$is_checkbox_img_enable = true;
					}

					if ( $field_otions_new['type'] == 'radio' && ( isset( $field_otions_new['use_image'] ) && $field_otions_new['use_image'] == 1 ) ) {
						$is_font_awesome     = 1;
						$is_radio_img_enable = true;
					}

					if ( $field_otions_new['type'] == 'phone' && ( isset( $field_otions_new['phone_validation'] ) && $field_otions_new['phone_validation'] != 'international' ) ) {
						$is_input_mask = 1;
					}

					if ( $field_otions_new['type'] == 'phone' && ( isset( $field_otions_new['phonetype'] ) && $field_otions_new['phonetype'] == 1 ) ) {
						$is_input_mask = 1;
					}

					if ( $field_otions_new['type'] == 'captcha' && ( isset( $field_otions_new[ 'is_recaptcha_' . $field_id_new ] ) && $field_otions_new[ 'is_recaptcha_' . $field_id_new ] == 'recaptcha' ) ) {
						$google_captcha_loaded = 1;
					}

					if ( ( isset( $field_otions_new['enable_arf_prefix'] ) && $field_otions_new['enable_arf_prefix'] == 1 ) || ( isset( $field_otions_new['enable_arf_suffix'] ) && $field_otions_new['enable_arf_suffix'] == 1 ) ) {
						$is_font_awesome         = 1;
						$is_prefix_suffix_enable = true;
					}

					if ( isset( $field_otions_new['tooltip_text'] ) && $field_otions_new['tooltip_text'] != '' ) {
						$is_tooltip = 1;
					}

					$field_id_all          = $field_id_new;
					$changed_field_value[] = $field_id_new;
					if ( $field_otions_new['type'] != 'hidden' ) {
						$new_field_order[ $field_id_new ] = isset( $temp_order[ $field_id_new ] ) ? $temp_order[ $field_id_new ] : '';
					}

					if ( ( ( isset( $options['font_awesome_loaded'] ) && false == $options['font_awesome_loaded'] ) || ! isset( $options['font_awesome_loaded'] ) ) ) {
						$is_font_awesome                = true;
						$options['font_awesome_loaded'] = $is_font_awesome;
					}
				} else {
					$field_otions_new['name'] = isset( $field_otions_new['name'] ) ? $field_otions_new['name'] : '';

					$insert_default_value = is_array( $default_value ) ? json_encode( $default_value ) : $default_value;

					$field_key      = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_fields, 'field_key' );
					$new_val        = arflite_json_decode( $value, true );
					$new_val['key'] = $field_key;
					if ( $is_preview ) {
						$final_val = $new_val;
					} else {
						$final_val = json_encode( $new_val );
					}
					$field_otions_new_order = isset( $field_otions_new['option_order'] ) ? $field_otions_new['option_order'] : '';
					$args                   = array(
						'field_key'     => $field_key,
						'name'          => sanitize_text_field( $field_otions_new['name'] ),
						'type'          => sanitize_text_field( $field_otions_new['type'] ),
						'options'       => $field_options,
						'required'      => isset( $field_otions_new['required'] ) ? sanitize_text_field( $field_otions_new['required'] ) : sanitize_text_field( '0' ),
						'field_options' => $final_val,
						'form_id'       => intval( $id ),
						'created_date'  => current_time( 'mysql' ),
						'option_order'  => $field_otions_new_order,
					);

					 
					$format                 = array( '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%s' );
					if ( ! $is_preview ) {
						$wpdb->insert( $tbl_arf_fields, $args, $format );
					} else {
						if ( ! isset( $arf_preview_form_data['fields'] ) ) {
							$arf_preview_form_data['fields'] = array();
						}
						$args['id']                        = $field_id_new;
						$args['field_key']                 = $field_key;
						$arf_preview_form_data['fields'][] = $args;
					}

					$new_id_array[ $i ]['old_id'] = $field_id_new;
					$new_id_array[ $i ]['new_id'] = $wpdb->insert_id;

					$new_id_array[ $i ]['name'] = $field_otions_new['name'];
					$new_id_array[ $i ]['type'] = $field_otions_new['type'];
					if ( $field_otions_new['type'] == 'hidden' ) {
						$hidden_field_ids[] = array(
							'old_id' => $field_id_new,
							'new_id' => $wpdb->insert_id,
						);
					}
					$field_opt = arflite_json_decode( $field_options, true );

					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_opt = maybe_unserialize( $field_options );
					}
					if ( ! $is_preview ) {
						$changed_field_value[] = $new_field_id = $field_id_all = $wpdb->insert_id;
					} else {
						$changed_field_value[] = $new_field_id = $field_id_all = $args['id'];
					}
					if ( $field_otions_new['type'] != 'hidden' ) {
						$new_field_order[ $new_field_id ] = isset( $temp_order[ $field_id_new ] ) ? $temp_order[ $field_id_new ] : '';
					}

					if ( $field_otions_new['type'] == 'email' ) {
						if ( $field_otions_new['confirm_email'] == '1' ) {
							$email_field_key = $arflitemainhelper->arflite_get_unique_key( '', $tbl_arf_fields, 'field_key' );
							if ( is_array( $options['arf_field_order'] ) || is_object( $options['arf_field_order'] ) ) {
								$confirm_field_order_arr = arflite_json_decode( json_encode( $options['arf_field_order'] ), true );
							} else {
								$confirm_field_order_arr = json_decode( $options['arf_field_order'], true );
							}
							$confirm_field_order = $confirm_field_order_arr[ $field_id_new . '_confirm' ];

							$arf_temp_fields[ 'confirm_email_' . $new_field_id ] = array(
								'key'                 => $email_field_key,
								'order'               => $confirm_field_order,
								'parent_field_id'     => $new_field_id,
								'confirm_inner_class' => $field_otions_new['confirm_email_inner_classes'],
							);
						}
					}

					$loaded_field[ $i ] = $field_otions_new['type'];

					if ( ( isset( $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] ) && $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] == 1 ) || ( isset( $field_otions_new[ 'enable_arf_suffix_' . $field_id_new ] ) && $field_otions_new[ 'enable_arf_prefix_' . $field_id_new ] == 1 ) || ( sanitize_text_field( $_REQUEST['arfcksn'] ) == 'custom' ) ) {
						$is_font_awesome = 1;
					}

					if ( $field_otions_new['type'] == 'checkbox' && ( isset( $field_otions_new['use_image'] ) && $field_otions_new['use_image'] == 1 ) ) {
						$is_font_awesome        = 1;
						$is_checkbox_img_enable = true;
					}

					if ( $field_otions_new['type'] == 'radio' && ( isset( $field_otions_new['use_image'] ) && $field_otions_new['use_image'] == 1 ) ) {
						$is_font_awesome     = 1;
						$is_radio_img_enable = true;
					}

					if ( $field_otions_new['type'] == 'phone' && ( isset( $field_otions_new['phone_validation'] ) && $field_otions_new['phone_validation'] != 'international' ) ) {
						$is_input_mask = 1;
					}

					if ( $field_otions_new['type'] == 'phone' && ( isset( $field_otions_new['phonetype'] ) && $field_otions_new['phonetype'] == 1 ) ) {
						$is_input_mask = 1;
					}

					if ( $field_otions_new['type'] == 'captcha' && ( isset( $field_otions_new[ 'is_recaptcha_' . $field_id_new ] ) && $field_otions_new[ 'is_recaptcha_' . $field_id_new ] == 'recaptcha' ) ) {
						$google_captcha_loaded = 1;
					}

					if ( ( isset( $field_otions_new['enable_arf_prefix'] ) && $field_otions_new['enable_arf_prefix'] == 1 ) || ( isset( $field_otions_new['enable_arf_suffix'] ) && $field_otions_new['enable_arf_suffix'] == 1 ) ) {
						$is_font_awesome         = 1;
						$is_prefix_suffix_enable = true;
					}

					if ( isset( $field_otions_new['tooltip_text'] ) && $field_otions_new['tooltip_text'] != '' ) {
						$is_tooltip = 1;
					}

					$ar_email_subject                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_email_subject        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_email_subject']          = str_replace( $ar_email_subject, $replace_with_ar_email_subject, $options['ar_email_subject'] );
					$return_json_data['ar_email_subject'] = $options['ar_email_subject'];

					$ar_user_from_email                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_user_from_email        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_user_from_email']          = str_replace( $ar_user_from_email, $replace_with_ar_user_from_email, $options['ar_user_from_email'] );
					$return_json_data['ar_user_from_email'] = $options['ar_user_from_email'];

					$ar_user_nreplyto_email                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_user_nreplyto_email        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_user_nreplyto_email']          = str_replace( $ar_user_nreplyto_email, $replace_with_ar_user_nreplyto_email, $options['ar_user_nreplyto_email'] );
					$return_json_data['ar_user_nreplyto_email'] = $options['ar_user_nreplyto_email'];

					$ar_email_message                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_email_message        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_email_message']          = str_replace( $ar_email_message, $replace_with_ar_email_message, $options['ar_email_message'] );
					$return_json_data['ar_email_message'] = $options['ar_email_message'];

					$reply_to              = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_reply_to = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['reply_to']   = str_replace( $reply_to, $replace_with_reply_to, $options['reply_to'] );
					$return_json_data['options_admin_reply_to_notification'] = $options['reply_to'];

					$admin_email_subject                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_admin_email_subject        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['admin_email_subject']          = str_replace( $admin_email_subject, $replace_with_admin_email_subject, $options['admin_email_subject'] );
					$return_json_data['admin_email_subject'] = $options['admin_email_subject'];

					$ar_admin_email_message                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_email_message        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_email_message']          = str_replace( $ar_admin_email_message, $replace_with_ar_admin_email_message, $options['ar_admin_email_message'] );
					$return_json_data['ar_admin_email_message'] = $options['ar_admin_email_message'];

					$ar_admin_from_name                             = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_from_name                = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_from_name']                  = str_replace( $ar_admin_from_name, $replace_with_ar_admin_from_name, $options['ar_admin_from_name'] );
					$return_json_data['options_ar_admin_from_name'] = $options['ar_admin_from_name'];

					$ar_admin_cc_email                                       = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_cc_email                          = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_cc_email']                            = str_replace( $ar_admin_cc_email, $replace_with_ar_admin_cc_email, $options['admin_cc_email'] );
					$options['admin_cc_email']                               = $options['ar_admin_cc_email'];
					$return_json_data['options_admin_cc_email_notification'] = $options['admin_cc_email'];

					$ar_admin_bcc_email                                       = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_bcc_email                          = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_bcc_email']                            = str_replace( $ar_admin_bcc_email, $replace_with_ar_admin_bcc_email, $options['admin_bcc_email'] );
					$options['admin_bcc_email']                               = $options['ar_admin_bcc_email'];
					$return_json_data['options_admin_bcc_email_notification'] = $options['admin_bcc_email'];

					$ar_admin_from_email                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_from_email        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_from_email']          = str_replace( $ar_admin_from_email, $replace_with_ar_admin_from_email, $options['ar_admin_from_email'] );
					$return_json_data['ar_admin_from_email'] = $options['ar_admin_from_email'];

					$ar_admin_reply_to_email                     = '[' . $field_otions_new['name'] . ':' . $field_id_new . ']';
					$replace_with_ar_admin_reply_to_email        = '[' . $field_otions_new['name'] . ':' . $new_field_id . ']';
					$options['ar_admin_reply_to_email']          = str_replace( $ar_admin_reply_to_email, $replace_with_ar_admin_reply_to_email, $options['ar_admin_reply_to_email'] );
					$return_json_data['ar_admin_reply_to_email'] = $options['ar_admin_reply_to_email'];
				}
				$i++;
			}
		}

		if ( $options['arf_form_other_css'] != '' && ( $str['arfaction'] == 'new' || $str['arfaction'] == 'duplicate' ) ) {
			$options['arf_form_other_css'] = isset( $values['options']['arf_form_other_css'] ) ? addslashes( str_replace( "\n", '', str_replace( "\t", '', $values['options']['arf_form_other_css'] ) ) ) : '';
			$temp_arf_form_other_css       = str_replace( $temp_form_id, $id, $options['arf_form_other_css'] );
			$options['arf_form_other_css'] = $temp_arf_form_other_css;
		}

		$options['arf_loaded_field']               = $loaded_field;
		$options['font_awesome_loaded']            = $is_font_awesome;
		$options['tooltip_loaded']                 = $is_tooltip;
		$options['arf_input_mask']                 = $is_input_mask;
		$options['arf_number_animation']           = $animate_number;
		$options['arf_number_round']               = $round_total_number;
		$options['arf_hide_bar_belt']              = $arf_hide_bar_belt;
		$options['html_running_total_field_array'] = $html_running_total_field_array;
		$options['google_captcha_loaded']          = $google_captcha_loaded;
		$options['calender_theme']                 = isset( $values['arffths'] ) ? $values['arffths'] : 'default_theme';
		$new_html_running_total                    = array();

		foreach ( $new_id_array as $key_new => $value_new ) {
			if ( $options['ar_email_to'] == $value_new['old_id'] ) {
				$options['ar_email_to']                       = str_replace( $value_new['old_id'], $value_new['new_id'], $options['ar_email_to'] );
				$return_json_data['options_ar_user_email_to'] = $options['ar_email_to'];
			}
			$options = apply_filters( 'arflite_update_form_option_outside', $options, $return_json_data, $value_new['old_id'], $value_new['new_id'] );

			$return_json_data = apply_filters( 'arflite_update_form_return_json_outside', $return_json_data, $options );
		}

		if ( $options['arf_data_with_url'] != 0 ) {
			$options['arf_data_with_url_type'] = isset( $values['options']['arf_data_with_url_type'] ) ? $values['options']['arf_data_with_url_type'] : 'post';

			$options['arf_data_key_with_url'] = isset( $values['options']['arf_data_key_with_url'] ) ? $values['options']['arf_data_key_with_url'] : 0;
			if ( $options['arf_data_key_with_url'] != 0 ) {

				$k_field_id    = $new_id_array;
				$key_name_list = array();

				foreach ( $k_field_id as $kfid => $val ) {

					$key_name_list[ $val['new_id'] ] = isset( $_REQUEST['options']['arf_data_with_url_data'][ $val['old_id'] ] ) ? sanitize_text_field( $_REQUEST['options']['arf_data_with_url_data'][ $val['old_id'] ] ) : '';
				}
				$options['arf_field_key_name'] = json_encode( $key_name_list );
			}
		}

		$values_field_order = json_decode( $values['arf_field_order'], true );
		$final_field_order  = array();
		if( !empty( $values_field_order ) ){
			foreach ( $values_field_order as $values_field_order_key => $values_field_order_value ) {
				if ( ! array_key_exists( $values_field_order_key, $new_field_order ) && is_int( $values_field_order_key ) ) {
					$changed_new_field_key                       = array_search( $values_field_order_value, $new_field_order );
					$final_field_order[ $changed_new_field_key ] = $values_field_order_value;

					if ( array_key_exists( $values_field_order_key . '_confirm', $values_field_order ) ) {
						unset( $final_field_order[ $values_field_order_key . '_confirm' ] );

						$final_field_order[ $changed_new_field_key . '_confirm' ] = $values_field_order[ $values_field_order_key . '_confirm' ];

						unset( $values_field_order[ $values_field_order_key . '_confirm' ] );
					}
				} elseif ( array_key_exists( $values_field_order_key, $new_field_order ) && is_int( $values_field_order_key ) ) {
					$final_field_order[ $values_field_order_key ] = $values_field_order_value;

					if ( array_key_exists( $values_field_order_key . '_confirm', $values_field_order ) ) {
						unset( $final_field_order[ $values_field_order_key . '_confirm' ] );

						$final_field_order[ $values_field_order_key . '_confirm' ] = $values_field_order[ $values_field_order_key . '_confirm' ];

						unset( $values_field_order[ $values_field_order_key . '_confirm' ] );
					}
				} else {
					if ( strpos( $values_field_order_key, '_confirm' ) === false ) {
						$final_field_order[ $values_field_order_key ] = $values_field_order_value;
					}
				}
			}
		}
		if ( ! $is_preview ) {
			$options['arf_field_order'] = isset( $final_field_order ) ? json_encode( $final_field_order ) : array();
		} else {
			$options['arf_field_order'] = isset( $final_field_order ) ? $final_field_order : array();
		}

		$selectDeletedFields = array();

		
		if ( ! $is_preview ) {
			if ( isset( $changed_field_value ) && ! empty( $changed_field_value ) ) {
				$selectDeletedFields = $wpdb->get_results( $wpdb->prepare( 'SELECT id FROM `' . $tbl_arf_fields . "` WHERE id NOT IN( '" . implode( '\',\'', $changed_field_value ) . "') AND form_id = %d", $id ) ); //phpcs:ignore
	
				$del_fields = $wpdb->query( $wpdb->prepare( 'delete from ' . $tbl_arf_fields . ' where form_id = %d and id NOT IN (' . rtrim(implode( ',', $changed_field_value ),',') . ')', $id ) ); //phpcs:ignore
	
			} elseif ( empty( $changed_field_value ) ) {
				$del_fields = $wpdb->query( $wpdb->prepare( 'delete from ' . $tbl_arf_fields . ' where form_id = %d', $id ) ); //phpcs:ignore
			}
			

			$query_results = $wpdb->query( 'update ' . $tbl_arf_forms . " set options = '" . addslashes( maybe_serialize( $options ) ) . "', temp_fields='" . maybe_serialize( $arf_temp_fields ) . "' where id = '" . $id . "'" ); //phpcs:ignore
		} else {
			$arf_preview_form_data['options']     = $options;
			$arf_preview_form_data['temp_fields'] = $arf_temp_fields;
		}

		if ( $is_preview ) {
			$random = rand( 11, 9999 );
			if ( get_option( 'arflite_previewtabledata_' . $random ) != '' ) {
				$random = rand( 11, 9999 );
			}
			$option_name = 'arflite_previewtabledata_' . $random;

			update_option( $option_name, addslashes( json_encode( $arf_preview_form_data ) ) );
			echo esc_html( $option_name );
			die;
		}

		do_action( 'arfliteafterupdateform', $id, $values, false, 0 );
		do_action( 'arfliteafterupdateform_' . $id, $id, $values, false, 0 );

		do_action( 'arfliteupdateform_' . $id, $values );

		$query_results = apply_filters( 'arflitechangevaluesafterupdateform', $query_results );

		$sel_fields = $wpdb->prepare( 'select * from ' . $tbl_arf_fields . ' where form_id = %d', $str['id'] ); //phpcs:ignore

		$res_fields_arr = $wpdb->get_results( $sel_fields, 'ARRAY_A' ); //phpcs:ignore

		$selectbox_field_available = '';
		$radio_field_available     = '';
		$checkbox_field_available  = '';

		foreach ( $res_fields_arr as $res_fields ) {

			if ( ( $res_fields['type'] == 'select' || $res_fields['type'] == 'time' ) && $selectbox_field_available == '' ) {
				$selectbox_field_available = true;
			}

			if ( $res_fields['type'] == 'checkbox' && $checkbox_field_available == '' ) {
				$checkbox_field_available = true;
			}

			if ( $res_fields['type'] == 'radio' && $radio_field_available == '' ) {
				$radio_field_available = true;
			}
		}

		$upload_dir   = ARFLITE_UPLOAD_DIR . '/css/';
		$dest_dir     = ARFLITE_UPLOAD_DIR . '/maincss/';
		$dest_css_url = ARFLITE_UPLOAD_URL . '/maincss/';

		$form_id = $id;

		$cssoptions = $form_css;

		$target_path = ARFLITE_UPLOAD_DIR . '/maincss';

		$arflite_preview = 'none';
		if ( count( $cssoptions ) > 0 ) {
			$new_values      = array();
			$temp_new_values = array();

			foreach ( $cssoptions as $k => $v ) {
				$new_values[ $k ] = $temp_new_values[ $k ] = str_replace( '##', '#', $v );
			}

			$saving       = true;
			$use_saved    = true;
			$is_form_save = false;

			$arfssl = ( is_ssl() ) ? 1 : 0;

			$arflite_preview = false;

			$form = $arfliteform->arflitegetOne( $form_id );

			$form->form_css = maybe_unserialize( $form->form_css );

			$css_common_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';

			$css_rtl_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';

			if ( 'standard' == $form_css['arfinputstyle'] || 'rounded' == $form_css['arfinputstyle'] ) {
				$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

				$wp_upload_dir = wp_upload_dir();

				$target_path = ARFLITE_UPLOAD_DIR . '/maincss';

				$temp_css_file = '';// $warn = "/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */";

				ob_start();

				include $filename;
				include $css_common_filename;
				if ( is_rtl() ) {
					include $css_rtl_filename;
				}

				$temp_css_file .= str_replace( '##', '#', ob_get_contents() );

				ob_end_clean();

			}

			if ( 'material' == $form_css['arfinputstyle'] ) {

				$file_name_materialize = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

				$wp_upload_dir = wp_upload_dir();

				$target_path = ARFLITE_UPLOAD_DIR . '/maincss';

				$temp_materialize_file = $materialize_warn = '/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */';

				$temp_materialize_file .= "\n";

				ob_start();

				include $file_name_materialize;
				include $css_common_filename;
				if ( is_rtl() ) {
					include $css_rtl_filename;
				}

				$temp_materialize_file .= str_replace( '##', '#', ob_get_contents() );

				ob_end_clean();

				$temp_materialize_file .= "\n " . $materialize_warn;

			}
		} else {

			$temp_css_file = file_get_contents( $upload_dir . 'arforms.css' );
			$temp_css_file = str_replace( '.arflite_main_div_', '.arflite_main_div_' . $id, $temp_css_file );
			$temp_css_file = str_replace( '#popup-form-', '#popup-form-' . $id, $temp_css_file );
			$temp_css_file = str_replace( 'cycle_', 'cycle_' . $id, $temp_css_file );
			$temp_css_file = str_replace( '##', '#', $temp_css_file );
		}

		/*INCLUDE ACTUAL DYNAMIC FILE IN AJAX RESPONSE START*/
		if ( count( $cssoptions ) > 0 ) {
			$new_values      = array();
			$temp_new_values = array();

			foreach ( $cssoptions as $k => $v ) {
				$new_values[ $k ] = $temp_new_values[ $k ] = str_replace( '##', '#', $v );
			}

			$saving       = true;
			$use_saved    = true;
			$is_form_save = true;

			$arfssl = ( is_ssl() ) ? 1 : 0;

			$arflite_preview = false;

			$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

			$standard_css_file = $warn = '/* WARNING: Any changes made to this file will be lost when your ARForms lite settings are updated */';

			$standard_css_file .= "\n";

			ob_start();

			include $filename;
			include $css_common_filename;
			if ( is_rtl() ) {
				include $css_rtl_filename;
			}
			$standard_css_file .= str_replace( '##', '#', ob_get_contents() );

			ob_end_clean();

			$standard_css_file .= "\n " . $warn;

			$file_name_materialize = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

			$materialize_css_file = $materialize_warn = '/* WARNING: Any changes made to this file will be lost when your ARForms lite settings are updated */';

			$materialize_css_file .= "\n";

			ob_start();

			include $file_name_materialize;
			include $css_common_filename;
			if ( is_rtl() ) {
				include $css_rtl_filename;
			}

			$materialize_css_file .= str_replace( '##', '#', ob_get_contents() );

			ob_end_clean();

			$materialize_css_file .= "\n " . $materialize_warn;
		}
		/*INCLUDE ACTUAL DYNAMIC FILE IN AJAX RESPONSE END*/

		$css_file_new = $dest_dir . 'maincss_' . $id . '.css';

		$material_css_file_new = $dest_dir . 'maincss_materialize_' . $id . '.css';

		WP_Filesystem();
		global $wp_filesystem;
		if ( 'standard' == $form_css['arfinputstyle'] || 'rounded' == $form_css['arfinputstyle'] ) {
			if ( $selectbox_field_available == '' ) {
				$start_get_css_selbox_position = strpos( $temp_css_file, '/*arf selectbox css start*/' );
				$end_get_css_selbox_position   = strpos( $temp_css_file, '/*arf selectbox css end*/' );

				$end_get_css_selbox_lenght = strlen( '/*arf selectbox css end*/' );

				if ( $start_get_css_selbox_position && $end_get_css_selbox_position ) {

					$temp_css_file_star_selectbox = substr( $temp_css_file, $start_get_css_selbox_position, ( $end_get_css_selbox_position + $end_get_css_selbox_lenght ) - $start_get_css_selbox_position );
					$temp_css_file                = str_replace( $temp_css_file_star_selectbox, '', $temp_css_file );
				}
			}

			if ( $radio_field_available == '' && $checkbox_field_available == '' ) {
				$start_get_css_radiocheck_position = strpos( $temp_css_file, '/*arf checkbox radio css start*/' );
				$end_get_css_radiocheck_position   = strpos( $temp_css_file, '/*arf checkbox radio css end*/' );

				$end_get_css_radiocheck_lenght = strlen( '/*arf checkbox radio css end*/' );

				if ( $start_get_css_radiocheck_position && $end_get_css_radiocheck_position ) {

					$temp_css_file_radiocheckbox = substr( $temp_css_file, $start_get_css_radiocheck_position, ( $end_get_css_radiocheck_position + $end_get_css_radiocheck_lenght ) - $start_get_css_radiocheck_position );
					$temp_css_file               = str_replace( $temp_css_file_radiocheckbox, '', $temp_css_file );
				}
			}

			$temp_css_file = str_replace( '##', '#', $temp_css_file );

			$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777 );
		}

		if ( 'material' == $form_css['arfinputstyle'] ) {
			$temp_materialize_file = str_replace( '##', '#', $temp_materialize_file );
			$wp_filesystem->put_contents( $material_css_file_new, $temp_materialize_file, 0777 );
		}

		$message = addslashes( esc_html__( 'Form is saved successfully.', 'arforms-form-builder' ) );
		if ( isset( $hidden_field_ids ) && ! empty( $hidden_field_ids ) && count( $hidden_field_ids ) > 0 ) {
			$return_json_data['arf_hidden_field_ids'] = $hidden_field_ids;
		}

		$return_json_data['arf_default_newarr']           = json_encode( $temp_new_values );
		$return_json_data['arf_new_standard_css_data']    = $standard_css_file;
		$return_json_data['arf_new_materialize_css_data'] = $materialize_css_file;
		
		$return_json_data_final = json_encode( $return_json_data );
		echo esc_html( $message ) . '^|^' . esc_html( $id ) . '^|^' . $return_json_data_final . '^|^'; //phpcs:ignore

		$all_fields     = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE form_id = %d', $id ) ); //phpcs:ignore
		$arf_all_fields = array();

		foreach ( $all_fields as $key => $field_ ) {
			foreach ( $field_ as $k => $field_val ) {
				if ( $k == 'options' ) {
					$arf_all_fields[ $key ][ $k ] = json_decode( $field_val, true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$arf_all_fields[ $key ][ $k ] = maybe_unserialize( $field_val );
					}
				} elseif ( $k == 'field_options' ) {
					$field_opts = json_decode( $field_val, true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_opts = maybe_unserialize( $field_val );
					}
					foreach ( $field_opts as $ki => $val_ ) {
						$arf_all_fields[ $key ][ $ki ] = $val_;
					}
				} else {
					$arf_all_fields[ $key ][ $k ] = $field_val;
				}
			}
		}

		$values['fields'] = $arf_all_fields;

		if ( isset( $values['fields'] ) && ! empty( $values['fields'] ) ) {
			$arf_load_confirm_email = array();
			$totalpass              = 0;
			foreach ( $values['fields'] as $arrkey => $field ) {

				if ( $field['type'] == 'email' ) {
					$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );
					if ( isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 && isset( $arf_load_confirm_email['confirm_email_field'] ) && $arf_load_confirm_email['confirm_email_field'] == $field['id'] ) {
						$values['confirm_email_arr'][ $field['id'] ] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					} else {
						$arf_load_confirm_email['confirm_email_field'] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					}
				}

				if ( $field['type'] == 'email' && isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 ) {
					if ( isset( $field['confirm_email'] ) && $field['confirm_email'] == 1 && isset( $arf_load_confirm_email['confirm_email_field'] ) && $arf_load_confirm_email['confirm_email_field'] == $field['id'] ) {
						$values['confirm_email_arr'][ $field['id'] ] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					} else {
						$arf_load_confirm_email['confirm_email_field'] = isset( $field['confirm_email_field'] ) ? $field['confirm_email_field'] : '';
					}
					$confirm_email_field = $arflitefieldhelper->arflite_get_confirm_email_field( $field );
					$values['fields']    = $arflitefieldhelper->arflitearray_push_after( $values['fields'], array( $confirm_email_field ), $arrkey + $totalpass );
					$totalpass++;
				}
			}
			$field_data = file_get_contents( ARFLITE_VIEWS_PATH . '/arflite_editor_data.json' );

			$field_data_obj = json_decode( $field_data );

			$arf_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE `form_id` = %d', $id ), ARRAY_A ); //phpcs:ignore

			$field_order = $final_field_order;

			$field_resize_width = json_decode( $options['arf_field_resize_width'], true );
			$data['form_css']   = $db_data['form_css'];
			$frm_css            = maybe_unserialize( $data['form_css'] );
			$newarr             = array();
			$arr                = $frm_css;
			if ( isset( $arr ) && ! empty( $arr ) && is_array( $arr ) ) {
				foreach ( $arr as $k => $v ) {
					$newarr[ $k ] = $v;
				}
			}
			$arf_sorted_fields = array();
			if ( $field_order != '' ) {
				if ( ! is_array( $field_order ) ) {
					$field_order = json_decode( $field_order, true );
				}
				asort( $field_order );
				foreach ( $field_order as $field_id => $order ) {
					if ( is_int( $field_id ) ) {
						foreach ( $arf_fields as $field ) {
							if ( $field_id == $field['id'] ) {
								$arf_sorted_fields[] = $field;
							}
						}
					} else {
						$arf_sorted_fields[] = $field_id;
					}
				}
			}

			if ( isset( $arf_sorted_fields ) && ! empty( $arf_sorted_fields ) ) {
				$arf_fields = $arf_sorted_fields;
			}
			$class_array       = array();
			$conut_arf_fields  = count( $arf_fields );
			$index_arf_fields  = 0;
			$arf_field_counter = 1;

			foreach ( $arf_fields as $key => $field ) {
				$display_field_in_editor_from_outside = apply_filters( 'arflite_display_field_in_editor_outside', false, $field );
				if ( is_array( $field ) ) {
					if ( $field['type'] == 'hidden' ) {
						continue;
					}

					$field_name = 'item_meta[' . $field['id'] . ']';

					$field_opt = json_decode( $field['field_options'], true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_opt = maybe_unserialize( $field['field_options'] );
					}
					  $class = $field_opt['inner_class'];
					array_push( $class_array, $field_opt['inner_class'] );
					$field['default_value'] = $field_opt['default_value'];

					$has_options = false;
					if ( isset( $field['options'] ) && $field['options'] != '' ) {
						$has_options  = true;
						$field_opt_db = json_decode( $field['options'], true );
						if ( json_last_error() != JSON_ERROR_NONE ) {
							$field_opt_db = maybe_unserialize( $field['optinos'] );
						}
					}

					foreach ( $field_opt as $k => $field_opt_val ) {
						if ( $k != 'options' ) {
							$field[ $k ] = $this->arflite_html_entity_decode( $field_opt_val );
						} else {
							if ( $has_options == true ) {
								$field[ $k ] = $field_opt_db;
							}
						}
					}
				}
				if ( ! $display_field_in_editor_from_outside ) {
					$is_form_save = true;
					require ARFLITE_VIEWS_PATH . '/arflite_field_editor.php';
				} else {
					do_action( 'arflite_render_field_in_editor_outside', $field, $field_data_obj, $field_order, $frm_css, $data, $id, array(), false, $newarr );
				}
				unset( $field );
				unset( $field_name );

				$arf_field_counter++;
			}
		}

		die();
	}

	function arflite_check_current_val() {
		return 1;
	}


	function arflite_check_valid_sample() {
		return 1;
	}


	function arflitechecksoringcode( $code, $info ) {
		global $arfliteformcontroller;

		$mysortid = base64_decode( $code );
		$mysortid = explode( '^', $mysortid );

		if ( $mysortid != '' && count( $mysortid ) > 0 ) {
			$setdata = $arfliteformcontroller->arflitesetdata( $code, $info );

			return $setdata;
			exit;
		} else {
			return 0;
			exit;
		}
	}

	function arflitesetdata( $code, $info ) {
		if ( $code != '' ) {
			$mysortid = base64_decode( $code );
			$mysortid = explode( '^', $mysortid );
			$mysortid = $mysortid[4];

			update_option( 'arfliteIsSorted', 'Yes' );
			update_option( 'arfliteSortOrder', $code );
			update_option( 'arfliteSortId', $mysortid );
			update_option( 'arfliteSortInfo', $info );

			global $wpdb;
			$res1 = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . "options WHERE option_name = 'arflite_options' ", OBJECT_K );
			foreach ( $res1 as $key1 => $val1 ) {
				$mynewarr = maybe_unserialize( $val1->option_value );
			}

			update_option( 'arflite_options', $mynewarr );
			set_transient( 'arflite_options', $mynewarr );

			return 1;
			exit;
		} else {
			return 0;
			exit;
		}
	}

	function get_arflite_google_fonts() {
		global $arflitegooglefontbaseurl;

		$font_list = array(
			'ABeeZee',
			'Abel',
			'Abril Fatface',
			'Aclonica',
			'Acme',
			'Actor',
			'Adamina',
			'Advent Pro',
			'Aguafina Script',
			'Akronim',
			'Aladin',
			'Aldrich',
			'Alef',
			'Alegreya',
			'Alegreya SC',
			'Alegreya Sans',
			'Alegreya Sans SC',
			'Alex Brush',
			'Alfa Slab One',
			'Alice',
			'Alike',
			'Alike Angular',
			'Allan',
			'Allerta',
			'Allerta Stencil',
			'Allura',
			'Almendra',
			'Almendra Display',
			'Almendra SC',
			'Amarante',
			'Amaranth',
			'Amatic SC',
			'Amethysta',
			'Amiri',
			'Amita',
			'Anaheim',
			'Andada',
			'Andika',
			'Angkor',
			'Annie Use Your Telescope',
			'Anonymous Pro',
			'Antic',
			'Antic Didone',
			'Antic Slab',
			'Anton',
			'Arapey',
			'Arbutus',
			'Arbutus Slab',
			'Architects Daughter',
			'Archivo Black',
			'Archivo Narrow',
			'Arimo',
			'Arizonia',
			'Armata',
			'Artifika',
			'Arvo',
			'Arya',
			'Asap',
			'Asar',
			'Asset',
			'Astloch',
			'Asul',
			'Atomic Age',
			'Aubrey',
			'Audiowide',
			'Autour One',
			'Average',
			'Average Sans',
			'Averia Gruesa Libre',
			'Averia Libre',
			'Averia Sans Libre',
			'Averia Serif Libre',
			'Bad Script',
			'Balthazar',
			'Bangers',
			'Basic',
			'Battambang',
			'Baumans',
			'Bayon',
			'Belgrano',
			'Belleza',
			'BenchNine',
			'Bentham',
			'Berkshire Swash',
			'Bevan',
			'Bigelow Rules',
			'Bigshot One',
			'Bilbo',
			'Bilbo Swash Caps',
			'Biryani',
			'Bitter',
			'Black Ops One',
			'Bokor',
			'Bonbon',
			'Boogaloo',
			'Bowlby One',
			'Bowlby One SC',
			'Brawler',
			'Bree Serif',
			'Bubblegum Sans',
			'Bubbler One',
			'Buda',
			'Buenard',
			'Butcherman',
			'Butterfly Kids',
			'Cabin',
			'Cabin Condensed',
			'Cabin Sketch',
			'Caesar Dressing',
			'Cagliostro',
			'Calligraffitti',
			'Cambay',
			'Cambo',
			'Candal',
			'Cantarell',
			'Cantata One',
			'Cantora One',
			'Capriola',
			'Cardo',
			'Carme',
			'Carrois Gothic',
			'Carrois Gothic SC',
			'Carter One',
			'Catamaran',
			'Caudex',
			'Caveat',
			'Caveat Brush',
			'Cedarville Cursive',
			'Ceviche One',
			'Changa One',
			'Chango',
			'Chau Philomene One',
			'Chela One',
			'Chelsea Market',
			'Chenla',
			'Cherry Cream Soda',
			'Cherry Swash',
			'Chewy',
			'Chicle',
			'Chivo',
			'Chonburi',
			'Cinzel',
			'Cinzel Decorative',
			'Clicker Script',
			'Coda',
			'Coda Caption',
			'Codystar',
			'Combo',
			'Comfortaa',
			'Coming Soon',
			'Concert One',
			'Condiment',
			'Content',
			'Contrail One',
			'Convergence',
			'Cookie',
			'Copse',
			'Corben',
			'Courgette',
			'Cousine',
			'Coustard',
			'Covered By Your Grace',
			'Crafty Girls',
			'Creepster',
			'Crete Round',
			'Crimson Text',
			'Croissant One',
			'Crushed',
			'Cuprum',
			'Cutive',
			'Cutive Mono',
			'Damion',
			'Dancing Script',
			'Dangrek',
			'Dawning of a New Day',
			'Days One',
			'Dekko',
			'Delius',
			'Delius Swash Caps',
			'Delius Unicase',
			'Della Respira',
			'Denk One',
			'Devonshire',
			'Dhurjati',
			'Didact Gothic',
			'Diplomata',
			'Diplomata SC',
			'Domine',
			'Donegal One',
			'Doppio One',
			'Dorsa',
			'Dosis',
			'Dr Sugiyama',
			'Droid Sans',
			'Droid Sans Mono',
			'Droid Serif',
			'Duru Sans',
			'Dynalight',
			'EB Garamond',
			'Eagle Lake',
			'Eater',
			'Economica',
			'Eczar',
			'Ek Mukta',
			'Electrolize',
			'Elsie',
			'Elsie Swash Caps',
			'Emblema One',
			'Emilys Candy',
			'Engagement',
			'Englebert',
			'Enriqueta',
			'Erica One',
			'Esteban',
			'Euphoria Script',
			'Ewert',
			'Exo',
			'Exo 2',
			'Expletus Sans',
			'Fanwood Text',
			'Fascinate',
			'Fascinate Inline',
			'Faster One',
			'Fasthand',
			'Fauna One',
			'Federant',
			'Federo',
			'Felipa',
			'Fenix',
			'Finger Paint',
			'Fira Mono',
			'Fira Sans',
			'Fjalla One',
			'Fjord One',
			'Flamenco',
			'Flavors',
			'Fondamento',
			'Fontdiner Swanky',
			'Forum',
			'Francois One',
			'Freckle Face',
			'Fredericka the Great',
			'Fredoka One',
			'Freehand',
			'Fresca',
			'Frijole',
			'Fruktur',
			'Fugaz One',
			'GFS Didot',
			'GFS Neohellenic',
			'Gabriela',
			'Gafata',
			'Galdeano',
			'Galindo',
			'Gentium Basic',
			'Gentium Book Basic',
			'Geo',
			'Geostar',
			'Geostar Fill',
			'Germania One',
			'Gidugu',
			'Gilda Display',
			'Give You Glory',
			'Glass Antiqua',
			'Glegoo',
			'Gloria Hallelujah',
			'Goblin One',
			'Gochi Hand',
			'Gorditas',
			'Goudy Bookletter 1911',
			'Graduate',
			'Grand Hotel',
			'Gravitas One',
			'Great Vibes',
			'Griffy',
			'Gruppo',
			'Gudea',
			'Gurajada',
			'Habibi',
			'Halant',
			'Hammersmith One',
			'Hanalei',
			'Hanalei Fill',
			'Handlee',
			'Hanuman',
			'Happy Monkey',
			'Headland One',
			'Henny Penny',
			'Herr Von Muellerhoff',
			'Hind',
			'Hind Siliguri',
			'Hind Vadodara',
			'Holtwood One SC',
			'Homemade Apple',
			'Homenaje',
			'IM Fell DW Pica',
			'IM Fell DW Pica SC',
			'IM Fell Double Pica',
			'IM Fell Double Pica SC',
			'IM Fell English',
			'IM Fell English SC',
			'IM Fell French Canon',
			'IM Fell French Canon SC',
			'IM Fell Great Primer',
			'IM Fell Great Primer SC',
			'Iceberg',
			'Iceland',
			'Imprima',
			'Inconsolata',
			'Inder',
			'Indie Flower',
			'Inika',
			'Inknut Antiqua',
			'Irish Grover',
			'Istok Web',
			'Italiana',
			'Italianno',
			'Itim',
			'Jacques Francois',
			'Jacques Francois Shadow',
			'Jaldi',
			'Jim Nightshade',
			'Jockey One',
			'Jolly Lodger',
			'Josefin Sans',
			'Josefin Slab',
			'Joti One',
			'Judson',
			'Julee',
			'Julius Sans One',
			'Junge',
			'Jura',
			'Just Another Hand',
			'Just Me Again Down Here',
			'Kadwa',
			'Kalam',
			'Kameron',
			'Kantumruy',
			'Karla',
			'Karma',
			'Kaushan Script',
			'Kavoon',
			'Kdam Thmor',
			'Keania One',
			'Kelly Slab',
			'Kenia',
			'Khand',
			'Khmer',
			'Khula',
			'Kite One',
			'Knewave',
			'Kotta One',
			'Koulen',
			'Kranky',
			'Kreon',
			'Kristi',
			'Krona One',
			'Kurale',
			'La Belle Aurore',
			'Laila',
			'Lakki Reddy',
			'Lancelot',
			'Lateef',
			'Lato',
			'League Script',
			'Leckerli One',
			'Ledger',
			'Lekton',
			'Lemon',
			'Libre Baskerville',
			'Life Savers',
			'Lilita One',
			'Lily Script One',
			'Limelight',
			'Linden Hill',
			'Lobster',
			'Lobster Two',
			'Londrina Outline',
			'Londrina Shadow',
			'Londrina Sketch',
			'Londrina Solid',
			'Lora',
			'Love Ya Like A Sister',
			'Loved by the King',
			'Lovers Quarrel',
			'Luckiest Guy',
			'Lusitana',
			'Lustria',
			'Macondo',
			'Macondo Swash Caps',
			'Magra',
			'Maiden Orange',
			'Mako',
			'Mallanna',
			'Mandali',
			'Marcellus',
			'Marcellus SC',
			'Marck Script',
			'Margarine',
			'Marko One',
			'Marmelad',
			'Martel',
			'Martel Sans',
			'Marvel',
			'Mate',
			'Mate SC',
			'Maven Pro',
			'McLaren',
			'Meddon',
			'MedievalSharp',
			'Medula One',
			'Megrim',
			'Meie Script',
			'Merienda',
			'Merienda One',
			'Merriweather',
			'Merriweather Sans',
			'Metal',
			'Metal Mania',
			'Metamorphous',
			'Metrophobic',
			'Michroma',
			'Milonga',
			'Miltonian',
			'Miltonian Tattoo',
			'Miniver',
			'Miss Fajardose',
			'Modak',
			'Modern Antiqua',
			'Molengo',
			'Molle',
			'Monda',
			'Monofett',
			'Monoton',
			'Monsieur La Doulaise',
			'Montaga',
			'Montez',
			'Montserrat',
			'Montserrat Alternates',
			'Montserrat Subrayada',
			'Moul',
			'Moulpali',
			'Mountains of Christmas',
			'Mouse Memoirs',
			'Mr Bedfort',
			'Mr Dafoe',
			'Mr De Haviland',
			'Mrs Saint Delafield',
			'Mrs Sheppards',
			'Muli',
			'Mystery Quest',
			'NTR',
			'Neucha',
			'Neuton',
			'New Rocker',
			'News Cycle',
			'Niconne',
			'Nixie One',
			'Nobile',
			'Nokora',
			'Norican',
			'Nosifer',
			'Nothing You Could Do',
			'Noticia Text',
			'Noto Sans',
			'Noto Serif',
			'Nova Cut',
			'Nova Flat',
			'Nova Mono',
			'Nova Oval',
			'Nova Round',
			'Nova Script',
			'Nova Slim',
			'Nova Square',
			'Numans',
			'Nunito',
			'Odor Mean Chey',
			'Offside',
			'Old Standard TT',
			'Oldenburg',
			'Oleo Script',
			'Oleo Script Swash Caps',
			'Open Sans',
			'Open Sans Condensed',
			'Oranienbaum',
			'Orbitron',
			'Oregano',
			'Orienta',
			'Original Surfer',
			'Oswald',
			'Over the Rainbow',
			'Overlock',
			'Overlock SC',
			'Ovo',
			'Oxygen',
			'Oxygen Mono',
			'PT Mono',
			'PT Sans',
			'PT Sans Caption',
			'PT Sans Narrow',
			'PT Serif',
			'PT Serif Caption',
			'Pacifico',
			'Palanquin',
			'Palanquin Dark',
			'Paprika',
			'Parisienne',
			'Passero One',
			'Passion One',
			'Pathway Gothic One',
			'Patrick Hand',
			'Patrick Hand SC',
			'Patua One',
			'Paytone One',
			'Peddana',
			'Peralta',
			'Permanent Marker',
			'Petit Formal Script',
			'Petrona',
			'Philosopher',
			'Piedra',
			'Pinyon Script',
			'Pirata One',
			'Plaster',
			'Play',
			'Playball',
			'Playfair Display',
			'Playfair Display SC',
			'Podkova',
			'Poiret One',
			'Poller One',
			'Poly',
			'Pompiere',
			'Pontano Sans',
			'Poppins',
			'Port Lligat Sans',
			'Port Lligat Slab',
			'Pragati Narrow',
			'Prata',
			'Preahvihear',
			'Press Start 2P',
			'Princess Sofia',
			'Prociono',
			'Prosto One',
			'Puritan',
			'Purple Purse',
			'Quando',
			'Quantico',
			'Quattrocento',
			'Quattrocento Sans',
			'Questrial',
			'Quicksand',
			'Quintessential',
			'Qwigley',
			'Racing Sans One',
			'Radley',
			'Rajdhani',
			'Raleway',
			'Raleway Dots',
			'Ramabhadra',
			'Ramaraja',
			'Rambla',
			'Rammetto One',
			'Ranchers',
			'Rancho',
			'Ranga',
			'Rationale',
			'Ravi Prakash',
			'Redressed',
			'Reenie Beanie',
			'Revalia',
			'Rhodium Libre',
			'Ribeye',
			'Ribeye Marrow',
			'Righteous',
			'Risque',
			'Roboto',
			'Roboto Condensed',
			'Roboto Mono',
			'Roboto Slab',
			'Rochester',
			'Rock Salt',
			'Rokkitt',
			'Romanesco',
			'Ropa Sans',
			'Rosario',
			'Rosarivo',
			'Rouge Script',
			'Rozha One',
			'Rubik',
			'Rubik Mono One',
			'Rubik One',
			'Ruda',
			'Rufina',
			'Ruge Boogie',
			'Ruluko',
			'Rum Raisin',
			'Ruslan Display',
			'Russo One',
			'Ruthie',
			'Rye',
			'Sacramento',
			'Sahitya',
			'Sail',
			'Salsa',
			'Sanchez',
			'Sancreek',
			'Sansita One',
			'Sarala',
			'Sarina',
			'Sarpanch',
			'Satisfy',
			'Scada',
			'Schoolbell',
			'Seaweed Script',
			'Sevillana',
			'Seymour One',
			'Shadows Into Light',
			'Shadows Into Light Two',
			'Shanti',
			'Share',
			'Share Tech',
			'Share Tech Mono',
			'Shojumaru',
			'Short Stack',
			'Siemreap',
			'Sigmar One',
			'Signika',
			'Signika Negative',
			'Simonetta',
			'Sintony',
			'Sirin Stencil',
			'Six Caps',
			'Skranji',
			'Slabo 13px',
			'Slabo 27px',
			'Slackey',
			'Smokum',
			'Smythe',
			'Sniglet',
			'Snippet',
			'Snowburst One',
			'Sofadi One',
			'Sofia',
			'Sonsie One',
			'Sorts Mill Goudy',
			'Source Code Pro',
			'Source Sans Pro',
			'Source Serif Pro',
			'Special Elite',
			'Spicy Rice',
			'Spinnaker',
			'Spirax',
			'Squada One',
			'Sree Krushnadevaraya',
			'Stalemate',
			'Stalinist One',
			'Stardos Stencil',
			'Stint Ultra Condensed',
			'Stint Ultra Expanded',
			'Stoke',
			'Strait',
			'Sue Ellen Francisco',
			'Sumana',
			'Sunshiney',
			'Supermercado One',
			'Sura',
			'Suranna',
			'Suravaram',
			'Suwannaphum',
			'Swanky and Moo Moo',
			'Syncopate',
			'Tangerine',
			'Taprom',
			'Tauri',
			'Teko',
			'Telex',
			'Tenali Ramakrishna',
			'Tenor Sans',
			'Text Me One',
			'The Girl Next Door',
			'Tienne',
			'Tillana',
			'Timmana',
			'Tinos',
			'Titan One',
			'Titillium Web',
			'Trade Winds',
			'Trocchi',
			'Trochut',
			'Trykker',
			'Tulpen One',
			'Ubuntu',
			'Ubuntu Condensed',
			'Ubuntu Mono',
			'Ultra',
			'Uncial Antiqua',
			'Underdog',
			'Unica One',
			'UnifrakturCook',
			'UnifrakturMaguntia',
			'Unkempt',
			'Unlock',
			'Unna',
			'VT323',
			'Vampiro One',
			'Varela',
			'Varela Round',
			'Vast Shadow',
			'Vesper Libre',
			'Vibur',
			'Vidaloka',
			'Viga',
			'Voces',
			'Volkhov',
			'Vollkorn',
			'Voltaire',
			'Waiting for the Sunrise',
			'Wallpoet',
			'Walter Turncoat',
			'Warnes',
			'Wellfleet',
			'Wendy One',
			'Wire One',
			'Work Sans',
			'Yanone Kaffeesatz',
			'Yantramanav',
			'Yellowtail',
			'Yeseva One',
			'Yesteryear',
			'Zeyada',
			'Abhaya Libre',
			'Amiko',
			'Archivo',
			'Aref Ruqaa',
			'Arima Madurai',
			'Arsenal',
			'Asap Condensed',
			'Assistant',
			'Athiti',
			'Atma',
			'Bahiana',
			'Baloo',
			'Baloo Bhai',
			'Baloo Bhaijaan',
			'Baloo Bhaina',
			'Baloo Chettan',
			'Baloo Da',
			'Baloo Paaji',
			'Baloo Tamma',
			'Baloo Tammudu',
			'Baloo Thambi',
			'Barlow',
			'Barlow Condensed',
			'Barlow Semi Condensed',
			'Barrio',
			'Bellefair',
			'BioRhyme',
			'BioRhyme Expanded',
			'Bungee',
			'Bungee Hairline',
			'Bungee Inline',
			'Bungee Outline',
			'Bungee Shade',
			'Cairo',
			'Changa',
			'Chathura',
			'Coiny',
			'Cormorant',
			'Cormorant Garamond',
			'Cormorant Infant',
			'Cormorant SC',
			'Cormorant Unicase',
			'Cormorant Upright',
			'David Libre',
			'El Messiri',
			'Encode Sans',
			'Encode Sans Condensed',
			'Encode Sans Expanded',
			'Encode Sans Semi Condensed',
			'Encode Sans Semi Expanded',
			'Farsan',
			'Faustina',
			'Fira Sans Condensed',
			'Fira Sans Extra Condensed',
			'Frank Ruhl Libre',
			'Galada',
			'Harmattan',
			'Heebo',
			'Hind Guntur',
			'Hind Madurai',
			'IM Fell English',
			'Jomhuria',
			'Kanit',
			'Katibeh',
			'Kavivanar',
			'Kumar One',
			'Kumar One Outline',
			'Lalezar',
			'Lemonada',
			'Libre Barcode 128',
			'Libre Barcode 128 Text',
			'Libre Barcode 39',
			'Libre Barcode 39 Extended',
			'Libre Barcode 39 Extended Text',
			'Libre Barcode 39 Text',
			'Libre Franklin',
			'Mada',
			'Maitree',
			'Manuale',
			'Meera Inimai',
			'Miriam Libre',
			'Mirza',
			'Mitr',
			'Mogra',
			'Mukta',
			'Mukta Mahee',
			'Mukta Malar',
			'Mukta Vaani',
			'Nunito Sans',
			'Overpass',
			'Overpass Mono',
			'Padauk',
			'Pangolin',
			'Pattaya',
			'Pavanam',
			'Pridi',
			'Prompt',
			'Proza Libre',
			'Rakkas',
			'Rasa',
			'Reem Kufi',
			'Saira',
			'Saira Condensed',
			'Saira Extra Condensed',
			'Saira Semi Condensed',
			'Sansita',
			'Scheherazade',
			'Scope One',
			'Secular One',
			'Sedgwick Ave',
			'Sedgwick Ave Display',
			'Shrikhand',
			'Space Mono',
			'Spectral',
			'Spectral SC',
			'Sriracha',
			'Suez One',
			'Taviraj',
			'Trirong',
			'Vollkorn SC',
			'Yatra One',
			'Yrsa',
			'Zilla Slab',
			'Zilla Slab Highlight',
		);

		sort( $font_list );
		return $font_list;
	}

	function arflitebr2nl( $string ) {
		return preg_replace( '/\<br(\s*)?\/?\>/i', "\n", $string );
	}

	function arflite_remove_br( $content ) {
		if ( trim( $content ) == '' ) {
			return $content;
		}

		$content = preg_replace( '|<br />\s*<br />|', '', $content );
		$content = preg_replace( "~\r?~", '', $content );
		$content = preg_replace( "~\r\n?~", '', $content );
		$content = preg_replace( "/\n\n+/", '', $content );

		$content = preg_replace( "|\n|", '', $content );
		$content = preg_replace( "~\n~", '', $content );

		return $content;
	}


	function arflitedeciamlseparator( $value = 0 ) {
		global $arformsmain, $arflite_decimal_separator;

		$value                     = number_format( (float) $value, 2 );

		$decimal_separator = $arformsmain->arforms_get_settings('decimal_separator','general_settings');
		$decimal_separator = !empty( $decimal_separator ) ? $decimal_separator : '.';

		$arflite_decimal_separator = $decimal_separator;

		if ( $arflite_decimal_separator == ',' ) {
			$value = str_replace( '.', ',', $value );
		} elseif ( $arflite_decimal_separator == '.' ) {
			$value = $value;
		} else {
			$value = round( $value );
		}
		return $value;
	}

	function arflite_get_form_hidden_field( $form, $fields, $values, $arflite_preview, $is_widget_or_modal, $arflite_data_uniq_id, $form_action, $loaded_field, $type, $is_close_link, $arf_current_token ) {

		$hidden_fields = '';
		global $arfliterecordcontroller, $arformsmain, $arflitefieldhelper, $arflite_form_all_footer_js,$arflite_decimal_separator,$is_gutenberg;

		$arflite_http_server_agent = !empty( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
		$browser_info              = $arfliterecordcontroller->arflitegetBrowser( $arflite_http_server_agent );

		$decimal_separator = $arformsmain->arforms_get_settings('decimal_separator','general_settings');
		$decimal_separator = !empty( $decimal_separator ) ? $decimal_separator : '.';
		$arflite_decimal_separator = $decimal_separator;

		$arf_form_hide_after_submit_val = ( isset( $form->options['arf_form_hide_after_submit'] ) && $form->options['arf_form_hide_after_submit'] == '1' ) ? $form->options['arf_form_hide_after_submit'] : '';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_browser_name" data-id="arf_browser_name" data-version="' . esc_attr( $browser_info['version'] ) . '" value="' . esc_attr( $browser_info['name'] ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arflite_entry_nonce" id="arflite_entry_nonce" value="' . wp_create_nonce( 'arflite_entry_nonce' ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="imagename_' . esc_attr( $form->id . '_' . $arflite_data_uniq_id ) . '" id="imagename_' . esc_attr( $form->id . '_' . $arflite_data_uniq_id ) . '" value="" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arfdecimal_separator" data-id="arfdecimal_separator" value="' . esc_attr( $arflite_decimal_separator ) . '" />';

		if ( in_array( 'date', $loaded_field ) ) {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arfform_date_formate_' . esc_attr( $form->id ) . '" data-id="arfform_date_formate_' . esc_attr( $form->id ) . '" value="' . esc_attr( $form->form_css['date_format'] ) . '" />';
		}

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_key_' . esc_attr( $form->id ) . '" data-id="form_key_' . esc_attr( $form->id ) . '" value="' . esc_attr( $form->form_key ) . '" />';

		$arf_success_message_show_time = $arformsmain->arforms_get_settings('arf_success_message_show_time','general_settings');
		$arf_success_message_show_time = !empty($arf_success_message_show_time) ? $arf_success_message_show_time : 3;

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_success_message_show_time_' . esc_attr( $form->id ) . '" data-id="arf_success_message_show_time_' . esc_attr( $form->id ) . '" value="' . esc_attr( $arf_success_message_show_time ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_form_hide_after_submit_' . esc_attr( $form->id ) . '" data-id="arf_form_hide_after_submit_' . esc_attr( $form->id ) . '" value="' . esc_attr( $arf_form_hide_after_submit_val ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="is_form_preview_' . esc_attr( $form->id ) . '" data-id="is_form_preview_' . esc_attr( $form->id ) . '" value="' . esc_attr( $arflite_preview ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_validate_outside_' . esc_attr( $form->id ) . '" data-id="arf_validate_outside_' . esc_attr( $form->id ) . '" data-validate="' . ( ( apply_filters( 'arflite_validateform_outside', false, $form ) ) ? 1 : 0 ) . '" value="' . ( ( apply_filters( 'arflite_validateform_outside', false, $form ) ) ? 1 : 0 ) . '" />';

		$arf_is_validateform_outside_filter = ( ( apply_filters( 'arflite_is_validateform_outside', false, $form ) ) ? 1 : 0 );
		$hidden_fields                     .= '<input type="hidden" data-jqvalidate="false" name="arf_is_validate_outside_' . esc_attr( $form->id ) . '" data-id="arf_is_validate_outside_' . esc_attr( $form->id ) . '" data-validate="' . esc_attr( $arf_is_validateform_outside_filter ) . '" value="' . esc_attr( $arf_is_validateform_outside_filter ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" data-id="arflite_validate_outside_token" value="' . wp_create_nonce( 'arflite_validate_outside_nonce' ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" data-id="arflite_reset_form_outside_token" value="' . wp_create_nonce( 'arflite_reset_form_outside_nonce' ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_is_resetform_aftersubmit_' . esc_attr( $form->id ) . '" data-id="arf_is_resetform_aftersubmit_' . esc_attr( $form->id ) . '" value="' . ( ( apply_filters( 'arflite_is_resetform_aftersubmit', true, $form ) ) ? 1 : 0 ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_is_resetform_outside_' . esc_attr( $form->id ) . '" data-id="arf_is_resetform_outside_' . esc_attr( $form->id ) . '" value="' . ( ( apply_filters( 'arflite_is_resetform_outside', false, $form ) ) ? 1 : 0 ) . '" />';

		$form->form_css            = maybe_unserialize( $form->form_css );
		$arf_field_tooltipposition = isset( $form->form_css['arftooltipposition'] ) ? $form->form_css['arftooltipposition'] : 'top';
		$hidden_fields            .= '<input type="hidden" data-jqvalidate="false" name="arf_tooltip_settings_' . esc_attr( $form->id ) . '" data-id="arf_tooltip_settings_' . esc_attr( $form->id ) . '" class="arf_front_tooltip_settings" data-form-id="' . esc_attr( $form->id ) . '" data-color="' . esc_attr( $form->form_css['arf_tooltip_font_color'] ) . '" data-position="' . esc_attr( $arf_field_tooltipposition ) . '" data-width="' . esc_attr( $form->form_css['arf_tooltip_width'] ) . '" data-bg-color="' . esc_attr( $form->form_css['arf_tooltip_bg_color'] ) . '" />';

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arfsuccessmsgposition_' . esc_attr( $form->id ) . '" data-id="arfsuccessmsgposition_' . esc_attr( $form->id ) . '"  value="' . ( isset( $form->form_css['arfsuccessmsgposition'] ) ? esc_attr( $form->form_css['arfsuccessmsgposition'] ) : 'top' ) . '" />';

		if ( isset( $arflite_preview ) && $arflite_preview ) {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_form_date_format" id="arf_form_date_format" value="' . esc_attr( $form->form_css['date_format'] ) . '" />';
		}

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_tooltip_error_' . esc_attr( $form->id ) . '" data-id="arflite_form_tooltip_error_' . esc_attr( $form->id ) . '" data-color="' . ( isset( $form->form_css['arferrorstylecolor'] ) ? esc_attr( $form->form_css['arferrorstylecolor'] ) : '' ) . '" data-position="' . ( isset( $form->form_css['arferrorstyleposition'] ) ? esc_attr( $form->form_css['arferrorstyleposition'] ) : '' ) . '" value="' . ( isset( $form->form_css['arferrorstyle'] ) ? esc_attr( $form->form_css['arferrorstyle'] ) : '' ) . '" />';
		
		global $is_beaverbuilder, $is_divibuilder , $is_fusionbuilder;
		
		if($is_gutenberg == false  && $is_beaverbuilder == false && $is_divibuilder == false  && $is_fusionbuilder == false)
		{
			$hidden_fields .= '<input type="text" data-jqvalidate="false" class="arflite_fake_text_input" name="fake_text" data-id="fake_text" value="" />';
		}

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arfaction" value="' . esc_attr( $form_action ) . '" />';
		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_id" data-id="form_id" value="' . esc_attr( $form->id ) . '" />';
		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_data_id" data-id="form_data_id" value="' . esc_attr( $arflite_data_uniq_id ) . '" />';
		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_key" data-id="form_key" value="' . esc_attr( $form->form_key ) . '" />';

		$arfmainformloadjscss = $arformsmain->arforms_get_settings('arfmainformloadjscss','general_settings');
		$arfmainformloadjscss = !empty( $arfmainformloadjscss ) ? $arfmainformloadjscss : 0;

		if ( '1' == $arfmainformloadjscss ) {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="is_load_js_and_css_in_all_pages" data-id="is_load_js_and_css_in_all_pages" value="' . esc_attr( $arfmainformloadjscss ) . '" />';
		}

		$pageURL = '';
		$pageURL = get_permalink( get_the_ID() );
		if ( $pageURL == '' ) {
			$pageURL = site_url();
		}

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_display_type" data-id="form_display_type" value="' . ( ( $is_widget_or_modal ) ? 1 : 0 ) . '|' . $pageURL . '" />';	

		$form_submit_type = $arformsmain->arforms_get_settings('form_submit_type','general_settings');
		$form_submit_type = isset($form_submit_type ) ? $form_submit_type : 1;


		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="form_submit_type" data-id="form_submit_type" value="' . esc_attr( $form_submit_type ) . '" />';

		$_SERVER['HTTP_REFERER'] = !empty( $_SERVER['HTTP_REFERER'] ) ? esc_url($_SERVER['HTTP_REFERER']) : ''; //phpcs:ignore
		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arf_http_referrer_url" data-id="arf_http_referrer_url" value="' . esc_url($_SERVER['HTTP_REFERER']) . '" />'; //phpcs:ignore

		if ( isset( $controller ) && isset( $plugin ) ) {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="controller" value="' . esc_attr( $controller ) . '" />';
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="plugin" value="' . esc_attr( $plugin ) . '" />';
		}

		if ( is_admin() ) {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="entry_key" value="' . esc_attr( $values['entry_key'] ) . '" />';
		} else {
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="entry_key" value="' . esc_attr( $values['entry_key'] ) . '" />';
		}

		if ( $type != '' ) {
			global $arfliteajaxurl;
			$hidden_fields              .= '<input type="hidden"  data-jqvalidate="false" value="' . $arfliteajaxurl . '" data-id="admin_ajax_url" name="admin_ajax_url" >';
			$_SESSION['last_open_modal'] = isset( $_SESSION['last_open_modal'] ) ? sanitize_text_field( $_SESSION['last_open_modal'] ) : '';
			$hidden_fields              .= '<input type="hidden" data-jqvalidate="false" value="' . esc_html( $_SESSION['last_open_modal'] ) . '" data-id="current_modal" name="current_modal" >';

			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" value="' . $is_close_link . '" data-id="is_close_link" name="is_close_link" >';
			$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arfmainformurl" data-id="arfmainformurl" value="' . ARFLITEURL . '" />';
		}	

		$hidden_captcha = $arformsmain->arforms_get_settings('hidden_captcha','general_settings');
		$hidden_captcha = !empty( $hidden_captcha ) ? $hidden_captcha : false;

		$hidden_fields .= '<input type="hidden" data-jqvalidate="false" name="arflite_skip_captcha" data-id="arflite_skip_captcha" value="' . esc_attr( $hidden_captcha ) . '" />';

		return $hidden_fields;
	}



	function arflite_label_top_position( $label_size ) {
		return 'arf_main_label_' . $label_size . 'px';
	}

	function arflite_get_all_field_html( $form, $values, $arflite_data_uniq_id, $fields, $arflite_preview, $arflite_errors, $inputStyle, $arf_arr_preset_data = array() ) {

		global $arflitefieldhelper, $arfliteformcontroller, $arflitefieldcontroller, $arflitemainhelper, $arfliterecordcontroller, $arflite_form_all_footer_js, $arflitemaincontroller, $wpdb, $ARFLiteMdlDb, $arfliteversion,$arflite_style_settings,$arflite_form_all_footer_css, $tbl_arf_fields;
		$return_string       = '';
		$arf_classes_blank   = '';
		$confirm_email_style = '';

		foreach ( $values['fields'] as $fieldkey => $fieldarr ) {
			$fields_key = '';
			$update_arr = false;

			if ( isset( $fields[ $fieldkey ] ) && $fieldarr['id'] == $fields[ $fieldkey ]->id ) {
				$fields_key = $fieldkey;
				$update_arr = true;
			} else {
				foreach ( $fields as $key => $value ) {
					if ( $fieldarr['id'] == $value->id ) {
						$fields_key = $key;
						$update_arr = true;
					}
				}
			}

			if ( $update_arr ) {

				if ( isset( $fieldarr['value'] ) && $fieldarr['value'] != '' ) {

					$fields[ $fields_key ]->value = $fieldarr['value'];

				}

				if ( isset( $fieldarr['default_value'] ) && $fieldarr['default_value'] != '' ) {

					$fields[ $fields_key ]->default_value = $fieldarr['default_value'];

				}
			}
		}

		$form_data           = new stdClass();
		$form_data->id       = $form->id;
		$form_data->form_key = $form->form_key;
		$form_data->options  = maybe_serialize( $form->options );
		$form_temp_fields    = maybe_unserialize( $form->temp_fields );

		if ( ! is_array( $form_temp_fields ) ) {
			$form_temp_fields = arflite_json_decode( json_encode( $form_temp_fields ), true );
		}

		foreach ( $fields as $key => $value ) {
			if ( ! isset( $res_data[ $key ] ) ) {
				$res_data[ $key ] = new stdClass();
			}
			$res_data[ $key ]->id            = $value->id;
			$res_data[ $key ]->type          = $value->type;
			$res_data[ $key ]->name          = $value->name;
			$res_data[ $key ]->field_options = json_encode( $value->field_options );
		}
		$css_data_arr = $form->form_css;
		$arr          = maybe_unserialize( $css_data_arr );
		$newarr       = array();

		$newarr                     = $arr;
		$_SESSION['label_position'] = $newarr['position'];
		if ( $newarr['position'] == 'right' ) {
			$class_position = 'right_container';
		} elseif ( $newarr['position'] == 'left' ) {
			$class_position = 'left_container';
		} else {
			$class_position = 'top_container';
		}

		if ( $newarr['hide_labels'] == 1 ) {
			$class_position .= ' none_container';
		}

		$arf_fields = $fields;

		$arf_column_field_custom_width = array(
			'arf_2' => '1.5',
			'arf_3' => '2',
			'arf_4' => '2.25',
			'arf_5' => '2.4',
			'arf_6' => '2.5',
		);

		$arf_fields_merged = array_merge( $arf_fields, $values['fields'] );
		$field_order       = isset( $form->options['arf_field_order'] ) ? $form->options['arf_field_order'] : '';
		$field_order       = ( $field_order != '' ) ? json_decode( $field_order, true ) : array();

		asort( $field_order );

		$field_resize_width = isset( $form->options['arf_field_resize_width'] ) ? $form->options['arf_field_resize_width'] : '';
		$field_resize_width = ( $field_resize_width != '' ) ? json_decode( $field_resize_width, true ) : array();

		$arf_sorted_fields = array();

		$temp_arf_fields = $values['fields'];

		$confirm_email_field_id = $confirm_pass_field_id = array();
		$email_field_ids        = array();
		$x                      = 0;
		$email_exist            = 0;
		$fields_key             = array();
		foreach ( $values['fields'] as $temp_key => $temp_value ) {
			$fields_key[ $temp_key ] = $temp_value['id'];
		}

		$all_hidden_fields = array();

		foreach ( $arf_fields as $key => $tmp_field ) {

			if ( $tmp_field->type == 'email' && ( isset($tmp_field->field_options['confirm_email']) && $tmp_field->field_options['confirm_email'] == '1') ) {
				$current_key = array_search( $tmp_field->id, $fields_key );

				$current_field_arr = $form_temp_fields[ 'confirm_email_' . $tmp_field->id ];

				$current_field_arr['key'] = $current_key;

				$confirm_email_field_id[ $x ] = $values['fields'][ $current_key + 1 ]['id'];

				$email_field_ids[ $x ] = $tmp_field->id;
				array_push( $arf_fields, $values['fields'][ $current_key + 1 ] );

				$email_field_key = array_keys( $email_field_ids, $tmp_field->id );

				if ( ( $key = array_search( $current_field_arr['order'], $field_order ) ) !== false ) {
					unset( $field_order[ $key ] );
					$field_order[ $confirm_email_field_id[ $email_field_key[0] ] ] = $current_field_arr['order'];
				}
			}
			if ( $tmp_field->type == 'hidden' ) {
				$all_hidden_fields[] = $tmp_field;
			}
			$x++;
		}

		$field_pos = $x;

		$field_order_updated = array();
		$field_order_updated = $field_order;

		asort( $field_order_updated );
		foreach ( $all_hidden_fields as $field_id => $field ) {
			$field_order_updated[ $field->id ] = $field_pos;
			$field_pos++;
		}

		foreach ( $field_order_updated as $field_id => $field ) {
			if ( is_int( $field_id ) ) {
				foreach ( $arf_fields as $temp_field ) {
					$temp_field    = $this->arfliteObjtoArray( $temp_field );
					$temp_field_id = $temp_field['id'];
					if ( $temp_field_id == $field_id ) {
						$arf_sorted_fields[] = $temp_field;
					}
				}
			} else {
				$arf_sorted_fields[] = $field_id;
			}
		}

		if ( isset( $arf_sorted_fields ) && ! empty( $arf_sorted_fields ) ) {
			$arf_fields = $arf_sorted_fields;
		}

		unset( $field );
		$class_array      = array();
		$conut_arf_fields = count( $arf_fields );
		$index_arf_fields = 0;

		$arf_field_front_counter = 1;

		$OFData = wp_cache_get( 'arflite_form_fields_' . $form->id );
		if ( false == $OFData ) {
			$OFData = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_fields . ' WHERE form_id = %d ORDER BY id', $form->id ) ); //phpcs:ignore
			wp_cache_set( 'arflite_form_fields_' . $form->id, $OFData );
		}

		$arf_cookie_field_arr = array();
		

		foreach ( $arf_fields as $field ) {
			$material_input_cls = ( $inputStyle == 'material' ) ? 'input-field' : '';
			
			if ( is_array( $field ) || is_object( $field ) ) {
				$field = $this->arfliteObjtoArray( $field );

				$field_opt = isset( $field['field_options'] ) ? $field['field_options'] : array();
				if ( is_array( $field_opt ) && ! empty( $field_opt ) ) {
					foreach ( $field_opt as $k => $fieldOpt ) {
						if ( $k != 'options' && $k != 'default_value' ) {
							$field[ $k ] = $fieldOpt;
						}
					}
				} else {
					$field_opt = isset( $field['field_options'] ) ? json_decode( $field['field_options'], true ) : json_decode( json_encode( array() ), true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_opt = maybe_unserialize( $field['field_options'] );
					}
					if ( is_array( $field_opt ) && ! empty( $field_opt ) ) {
						foreach ( $field_opt as $k => $fieldOpt ) {
							if ( $k != 'options' && $k != 'default_value' ) {
								$field[ $k ] = $fieldOpt;
							}
						}
					}
				}

				if ( isset( $field_resize_width[ $arf_field_front_counter ] ) ) {

					if ( $field['type'] == 'confirm_email' ) {
						$field_level_class = isset( $field['confirm_email_classes'] ) ? $field['confirm_email_classes'] : 'arf_1';
					} else {
						$field_level_class = isset( $field_opt['classes'] ) ? $field_opt['classes'] : 'arf_1';
					}
					$calculte_width = str_replace( '%', '', $field_resize_width[ $arf_field_front_counter ] ) - ( isset( $arf_column_field_custom_width[ $field_level_class ] ) ? $arf_column_field_custom_width[ $field_level_class ] : '0' );

					$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container{';
						$arflite_form_all_footer_css .= 'width:' . $calculte_width . '%;';
					$arflite_form_all_footer_css     .= '}';
				}

				if ( $field['type'] == 'confirm_email' ) {
					$class = isset( $field['confirm_email_inner_classes'] ) ? $field['confirm_email_inner_classes'] : 'arf_1col';
				} else {
					$class = isset( $field_opt['inner_class'] ) ? $field_opt['inner_class'] : 'arf_1col';
				}
				array_push( $class_array, $class );

				$field['value'] = isset( $field['value'] ) ? $field['value'] : '';

				$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

				$field_name = 'item_meta[' . $field['id'] . ']';

				$allowed_html = arflite_retrieve_attrs_for_wp_kses();

				if ( isset( $is_confirmation_method ) && ! $is_confirmation_method || ! isset( $is_confirmation_method ) ) {
					if ( isset( $_REQUEST ) && isset( $_REQUEST['item_meta'] ) && array_key_exists( intval( $field['id'] ), $_REQUEST['item_meta'] ) ) {

						$arflite_requested_itemid = !empty( $_REQUEST['item_meta'][ $field['id'] ] ) ? intval($_REQUEST['item_meta'][ $field['id'] ]) : '';
						$field['set_field_value'] = wp_kses( $arflite_requested_itemid , $allowed_html );
					}
				}

				
				$field = apply_filters( 'arflitebeforefielddisplay', $field );

				$required_class = '';
				$required_class = ( $field['required'] == '0' ) ? '' : ' arffieldrequired';

				if ( $field['type'] == 'confirm_email' ) {
					$required_class .= ' confirm_email_container arf_confirm_email_field_' . $field['confirm_email_field'];
				}

				$field_name = 'item_meta[' . $field['id'] . ']';

				$field_description = '';
				if ( isset( $field['description'] ) && $field['description'] != '' ) {

					$arf_textarea_charlimit_class = '';
					if ( $field['type'] == 'textarea' && $field['field_options']['max'] > 0 ) {
						$arf_textarea_charlimit_class = 'arf_textareachar_limit';
					}

					$field_description = '<div class="arf_field_description ' . $arf_textarea_charlimit_class . '">' . $field['description'] . '</div>';
				}

				if ( isset( $field['multiple'] ) && $field['multiple'] && ( $field['type'] == 'select' || ( $field['type'] == 'data' && isset( $field['data_type'] ) && $field['data_type'] == 'select' ) ) ) {
					$field_name .= '[]';
				}

				$field_tooltip          = '';
				$field_tooltip_class    = '';
				$field_standard_tooltip = '';
				if ( isset( $field['tooltip_text'] ) && $field['tooltip_text'] != '' ) {
					if ( $inputStyle == 'material' ) {
						$field_tooltip = $arflitefieldhelper->arflite_tooltip_display( $field['tooltip_text'], $inputStyle );
						if ( $field['type'] == 'text' || $field['type'] == 'textarea' || $field['type'] == 'email' || $field['type'] == 'number' || $field['type'] == 'phone' || $field['type'] == 'date' || $field['type'] == 'time' || $field['type'] == 'url' || $field['type'] == 'image' ) {
							$field_tooltip_class = ' arfhelptipfocus ';
						} else {
							$field_tooltip_class = ' arfhelptip ';
						}
					} else {
						$field_standard_tooltip = $arflitefieldhelper->arflite_tooltip_display( $field['tooltip_text'], $inputStyle );
					}
				}

				$error_class = isset( $arflite_errors[ 'field' . $field['id'] ] ) ? ' arfblankfield' : '';

				$field['label'] = ( isset( $values['label_position'] ) && $values['label_position'] != '' ) ? $values['label_position'] : '';//$arflite_style_settings->position;
				$error_class   .= ' ' . $field['label'] . '_container';

				if ( isset( $field['classes'] ) ) {

					$error_class .= ' arfformfield';

					global $arflite_column_classes, $arflite_is_multi_column_loaded;

					if ( $field['type'] == 'confirm_email' ) {
						$field['classes'] = $field['confirm_email_classes'];
					}
					if ( isset( $field['classes'] ) && $field['classes'] == 'arf_2' && empty( $arflite_column_classes['two'] ) ) {
						$arflite_column_classes['two'] = '1';
						$arf_classes                   = 'frm_first_half';

						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );

						$arflite_is_multi_column_loaded[] = $form->form_key;
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_2' && isset( $arflite_column_classes['two'] ) && $arflite_column_classes['two'] == '1' ) {
						$arf_classes                     = 'frm_last_half';
						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';
						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_3' && empty( $arflite_column_classes['three'] ) ) {
						$arflite_column_classes['three'] = '1';
						$arf_classes                     = 'frm_first_third';

						$arflite_column_classes['two']  = '';
						$arflite_column_classes['four'] = '';
						$arflite_column_classes['five'] = '';
						$arflite_column_classes['six']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );

						$arflite_is_multi_column_loaded[] = $form->form_key;
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_3' && isset( $arflite_column_classes['three'] ) && $arflite_column_classes['three'] == '1' ) {
						$arflite_column_classes['three'] = '2';
						$arf_classes                     = 'frm_third';

						$arflite_column_classes['two']  = '';
						$arflite_column_classes['four'] = '';
						$arflite_column_classes['five'] = '';
						$arflite_column_classes['six']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_3' && isset( $arflite_column_classes['three'] ) && $arflite_column_classes['three'] == '2' ) {
						$arf_classes = 'frm_last_third';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';
						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_4' && empty( $arflite_column_classes['four'] ) ) {
						$arflite_column_classes['four'] = '1';
						$arf_classes                    = 'frm_first_fourth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );

						$arflite_is_multi_column_loaded[] = $form->form_key;
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_4' && isset( $arflite_column_classes['four'] ) && $arflite_column_classes['four'] == '1' ) {
						$arflite_column_classes['four'] = '2';
						$arf_classes                    = 'frm_fourth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_4' && isset( $arflite_column_classes['four'] ) && $arflite_column_classes['four'] == '2' ) {
						$arflite_column_classes['four'] = '3';
						$arf_classes                    = 'frm_fourth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_4' && isset( $arflite_column_classes['four'] ) && $arflite_column_classes['four'] == '3' ) {
						$arf_classes = 'frm_last_fourth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';
						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );

					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_5' && empty( $arflite_column_classes['five'] ) ) {
						$arflite_column_classes['five'] = '1';
						$arf_classes                    = 'frm_first_fifth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['six'] );

						$arflite_is_multi_column_loaded[] = $form->form_key;
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_5' && isset( $arflite_column_classes['five'] ) && $arflite_column_classes['five'] == '1' ) {
						$arflite_column_classes['five'] = '2';
						$arf_classes                    = 'frm_fifth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_5' && isset( $arflite_column_classes['five'] ) && $arflite_column_classes['five'] == '2' ) {
						$arflite_column_classes['five'] = '3';
						$arf_classes                    = 'frm_fifth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_5' && isset( $arflite_column_classes['five'] ) && $arflite_column_classes['five'] == '3' ) {
						$arflite_column_classes['five'] = '4';
						$arf_classes                    = 'frm_fifth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_5' && isset( $arflite_column_classes['five'] ) && $arflite_column_classes['five'] == '4' ) {
						$arf_classes = 'frm_last_fifth';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';
						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && empty( $arflite_column_classes['six'] ) ) {
						$arflite_column_classes['six'] = '1';
						$arf_classes                   = 'frm_first_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '1' ) {
						$arflite_column_classes['six'] = '2';
						$arf_classes                   = 'frm_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '2' ) {
						$arflite_column_classes['six'] = '3';
						$arf_classes                   = 'frm_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '3' ) {
						$arflite_column_classes['six'] = '4';
						$arf_classes                   = 'frm_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '4' ) {
						$arflite_column_classes['six'] = '5';
						$arf_classes                   = 'frm_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
					} elseif ( isset( $field['classes'] ) && $field['classes'] == 'arf_6' && isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '5' ) {
						$arflite_column_classes['six'] = '6';
						$arf_classes                   = 'frm_last_six';

						$arflite_column_classes['two']   = '';
						$arflite_column_classes['three'] = '';
						$arflite_column_classes['four']  = '';
						$arflite_column_classes['five']  = '';
						$arflite_column_classes['six']   = '';

						unset( $arflite_column_classes['two'] );
						unset( $arflite_column_classes['three'] );
						unset( $arflite_column_classes['four'] );
						unset( $arflite_column_classes['five'] );
						unset( $arflite_column_classes['six'] );
					} else {
						$arflite_column_classes = array();
						$arf_classes            = '';
					}

					if ( isset( $arflite_column_classes['six'] ) && $arflite_column_classes['six'] == '6' ) {
						$arflite_column_classes['six'] = '';
						unset( $arflite_column_classes['six'] );
					}
					if ( isset( $arflite_column_classes['five'] ) && $arflite_column_classes['five'] == '5' ) {
						$arflite_column_classes['five'] = '';
						unset( $arflite_column_classes['five'] );
					}
					if ( isset( $arflite_column_classes['four'] ) && $arflite_column_classes['four'] == '4' ) {
						$arflite_column_classes['four'] = '';
						unset( $arflite_column_classes['four'] );
					}
					if ( isset( $arflite_column_classes['three'] ) && $arflite_column_classes['three'] == '3' ) {
						$arflite_column_classes['three'] = '';
						unset( $arflite_column_classes['three'] );
					}
					if ( isset( $arflite_column_classes['two'] ) && $arflite_column_classes['two'] == '2' ) {
						$arflite_column_classes['two'] = '';
						unset( $arflite_column_classes['two'] );
					}

					if ( $class == 'arf21colclass' ) {
						 $arf_classes = 'frm_first_half';
					} elseif ( $class == 'arf_2col' ) {
						 $arf_classes = 'frm_last_half';
					}

					if ( $class == 'arf31colclass' ) {
						$arf_classes = 'frm_first_third';
					} elseif ( $class == 'arf_23col' ) {
						$arf_classes = 'frm_third';
					} elseif ( $class == 'arf_3col' ) {
						$arf_classes = 'frm_last_third';
					} elseif ( $class == 'arf41colclass' ) {
						$arf_classes = 'frm_first_fourth';
					} elseif ( $class == 'arf42colclass' || $class == 'arf43colclass' ) {
						$arf_classes = 'frm_fourth';
					} elseif ( $class == 'arf_4col' ) {
						$arf_classes = 'frm_last_fourth';
					} elseif ( $class == 'arf51colclass' ) {
						$arf_classes = 'frm_first_fifth';
					} elseif ( $class == 'arf52colclass' || $class == 'arf53colclass' || $class == 'arf54colclass' ) {
						$arf_classes = 'frm_fifth';
					} elseif ( $class == 'arf_5col' ) {
						$arf_classes = 'frm_last_fifth';
					} elseif ( $class == 'arf61colclass' ) {
						$arf_classes = 'frm_first_six';
					} elseif ( $class == 'arf62colclass' || $class == 'arf63colclass' || $class == 'arf64colclass' || $class == 'arf65colclass' ) {
						$arf_classes = 'frm_six';
					} elseif ( $class == 'arf_6col' ) {
						$arf_classes = 'frm_last_six';
					}
					$arf_classes  = isset( $arf_classes ) ? $arf_classes : '';
					$error_class .= ' ' . $arf_classes;
				}
				$prefix = $suffix = '';
				if ( $inputStyle != 'material' ) {
					$prefix = $this->arflite_prefix_suffix( 'prefix', $field );
					$suffix = $this->arflite_prefix_suffix( 'suffix', $field );
				}

				$arf_required = '';
				if ( $field['required'] ) {
					$field['required_indicator'] = ( isset( $field['required_indicator'] ) && ( $field['required_indicator'] != '' ) ) ? $field['required_indicator'] : '*';
					$arf_required                = '<span class="arfcheckrequiredfield">' . $field['required_indicator'] . '</span>';
				}

				$arf_main_label_cls = $this->arflite_label_top_position( $newarr['font_size'] );

				$arf_main_label = '';

				if ( $field['type'] == 'select' && $inputStyle == 'material' ) {
					$arf_main_label_cls .= ' selectpicker_active ';
				}

				if ( $field['type'] == 'phone' && isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {
					$arf_main_label_cls .= ' arf_phone_label_cls ';
				}
				$arf_material_standard_cls = '';
				if ( $field['name'] != '' ) {

					$arf_label_for_attribute = 'for="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '"';

					$arf_main_label .= '<label data-type="' . $field['type'] . '" ' . $arf_label_for_attribute . ' class="arf_main_label ' . $arf_main_label_cls . '">' . $field['name'];
					$arf_main_label .= $arf_required;
					$arf_main_label .= '</label>';
				} else {
					$arf_material_standard_cls = ' arf_material_theme_display ';
				}

				if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
					$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls{';
						$arflite_form_all_footer_css .= 'width:' . $field['field_width'] . 'px;';
					$arflite_form_all_footer_css     .= '}';
					if ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] != 1 && isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] != 1 ) {
						$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input{';
							$arflite_form_all_footer_css .= 'width:' . $field['field_width'] . 'px;';
						$arflite_form_all_footer_css     .= '}';
					}

					if ( 'textarea' == $field['type'] ) {
						$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls textarea{';
							$arflite_form_all_footer_css .= 'width:' . $field['field_width'] . 'px !important;';
						$arflite_form_all_footer_css     .= '}';
					}
				}

				$arf_input_field_html  = '';
				$arf_input_field_html .= $arflitefieldcontroller->arflite_input_fieldhtml( $field, false );
				$arf_input_field_html .= $arflitefieldcontroller->arflite_input_html( $field, false );

				$frm_opt = maybe_unserialize( $form_data->options );

				$required_class .= " arf_field_type_{$field['type']} ";

				if ( ! isset( $field['default_value'] ) ) {
					$field['default_value'] = isset( $field['field_options']['default_value'] ) ? $field['field_options']['default_value'] : '';
				}
				$parent_field_id = ! empty( $field['parent_field'] ) ? $field['parent_field'] : 0;

				switch ( $field['type'] ) {
					case 'text':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . '' . $error_class . ' arf_field_' . $field['id'] . '" data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '" >';
						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}
						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {
							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {
							$return_string .= $prefix;

							$arf_single_custom_validation     = isset( $field['single_custom_validation'] ) ? $field['single_custom_validation'] : 'custom_validation_none';
							$arf_custom_validation_expression = '';
							if ( $arf_single_custom_validation == 'custom_validation_none' ) {
								$arf_custom_validation_expression = '';
							} elseif ( $arf_single_custom_validation == 'custom_validation_alpha' ) {
								$arf_custom_validation_expression = '^[a-zA-Z\s]*$';
							} elseif ( $arf_single_custom_validation == 'custom_validation_number' ) {
								$arf_custom_validation_expression = '^[0-9]*$';
							} elseif ( $arf_single_custom_validation == 'custom_validation_alphanumber' ) {
								$arf_custom_validation_expression = '^[a-zA-Z0-9\s]*$';
							} elseif ( $arf_single_custom_validation == 'custom_validation_regex' ) {
								$arf_custom_validation_expression = isset( $field['arf_regular_expression'] ) ? $field['arf_regular_expression'] : '';
							}

							$arf_regular_expression = ( isset( $field['single_custom_validation'] ) && $arf_custom_validation_expression != '' ) ? 'data-validation-regex-regex="' . esc_attr( $arf_custom_validation_expression ) . '"  data-validation-regex-message="' . esc_attr( $field['arf_regular_expression_msg'] ) . '"' : '';

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input ' . $arf_regular_expression . '  type="text" id="field_' . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . '" ';
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}
							$return_string .= 'name="' . esc_attr( $field_name ) . '" ';

							$return_string .= $arf_input_field_html;

							$default_value = isset( $field['default_value'] ) ? $field['default_value'] : '';

							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

								$default_value = $arf_arr_preset_data[ $field['id'] ];
							}

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( $default_value != '' ) {
								$return_string .= ' value="' . esc_attr( $default_value ) . '" ';
							}

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}

							if ( isset( $field['clear_on_focus'] ) && $field['clear_on_focus'] ) {
								$return_string .= ' onfocus="arflitecleardedaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
								$return_string .= ' onblur="arflitereplacededaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
							}

							if ( isset( $field['field_width'] ) and $field['field_width'] != '' and $field['enable_arf_prefix'] != 1 and $field['enable_arf_suffix'] != 1 ) {
								$return_string .= ' style="width:' . $field['field_width'] . 'px !important;"';
							}

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_html( $field['blank'] ) . '" ';
							}
							if ( isset($field['minlength']) && ($field['minlength'] != '' && 0 < $field['minlength']) ) {
								$return_string .= 'minlength="' . $field['minlength'] . '" data-validation-minlength-message="' . esc_attr( $field['minlength_message'] ) . '"';
							}
							$return_string .= '  />';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;
							$return_string .= $field_standard_tooltip;
							$return_string .= $field_description;
						}
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;

					case 'textarea':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . '' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}
						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							$arf_text_is_countable = ( ( isset($field['field_options']['max']) && $field['field_options']['max'] > 0) ) ? 'arf_text_is_countable' : '';

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<textarea name="' . $field_name . '" id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '" ';
							if ( isset( $field['max_rows'] ) && $field['max_rows'] ) {
								$return_string .= ' rows="' . $field['max_rows'] . '" ';
							}
							$return_string .= $arf_input_field_html;

							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							$default_value = $field['default_value'];
							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

								$default_value = $arf_arr_preset_data[ $field['id'] ];
							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}

							if ( isset( $field['clear_on_focus'] ) && $field['clear_on_focus'] ) {
								$return_string .= ' onfocus="arflitecleardedaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
								$return_string .= ' onblur="arflitereplacededaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
							}

							if ( isset( $field['field_width'] ) and $field['field_width'] != '' ) {
								$return_string .= ' style="width:' . $field['field_width'] . 'px !important;"';
							}

							if ( isset( $field['required'] ) and $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '" ';
							}

							if ( ( isset($field['max']) && $field['max'] != '') && 0 < $field['max'] ) {
								$return_string .= ' maxlength="' . $field['max'] . '" data-validation-maxlength-message="' . __( 'Invalid maximum characters length', 'arforms-form-builder' ) . '" ';
							}

							if ( ( isset( $field['minlength']) && $field['minlength'] != '') && 0 < $field['minlength'] ) {
								$return_string .= ' minlength="' . $field['minlength'] . '" data-validation-minlength-message="' . esc_attr( $field['minlength_message'] ) . '" ';
							}

							$return_string .= ' >';
							if ( $default_value != '' ) {
								$return_string .= $default_value;
							}

							$return_string .= '</textarea>';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									  $return_string   .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$number_of_allowed_char = ( isset( $field['field_options']['max'] ) && $field['field_options']['max'] != '' ) ? $field['field_options']['max'] : '';

							if ( $number_of_allowed_char != '' ) {

								$number_of_default_value = '0';

								if ( $default_value != '' ) {
									$count_default_value     = strlen( $default_value );
									$number_of_default_value = ( isset( $count_default_value ) && $count_default_value > 0 ) ? $count_default_value : '0';
								}

									$return_string .= '<div class="arfcount_text_char_div"><span class="arftextarea_char_count">' . $number_of_default_value . '</span> / ' . $number_of_allowed_char . '</div>';

							}

							$return_string .= $field_standard_tooltip;
							$return_string .= $field_description;
						}
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'checkbox':
						if ( $inputStyle == 'material' ) {
							$alignment_class = ( isset( $field['align'] ) && $field['align'] == 'block' ) ? ' arf_vertical_radio' : ' arf_horizontal_radio';
							$return_string  .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $alignment_class . ' ' . $required_class . ' ' . $error_class . ' ' . $class_position . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
							$return_string  .= $arf_main_label;

							$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input{';
								$arflite_form_all_footer_css .= 'padding-top: 5px;';
							$arflite_form_all_footer_css     .= '}';

							$checked_values = '';

							if ( $arflite_preview ) {
								if ( ! is_array( $field['field_options'] ) ) {
									$field['field_options'] = json_decode( $field['field_options'], true );
								}
								if ( isset( $field['field_options']['default_value'] ) && ! empty( $field['field_options']['default_value'] ) ) {
									$checked_values = $field['field_options']['default_value'];
								}

								if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

									if ( is_array( $checked_values ) ) {
										array_push( $checked_values, $arf_arr_preset_data[ $field['id'] ] );
									} else {

										$checked_values = array( $arf_arr_preset_data[ $field['id'] ] );
									}
								}
							} else {

								if ( isset( $field['default_value'] ) && ! empty( $field['default_value'] ) ) {
									$checked_values = $field['default_value'];
								}
								if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

									if ( is_array( $checked_values ) ) {
										array_push( $checked_values, $arf_arr_preset_data[ $field['id'] ] );
									} else {
											$checked_values = array( $arf_arr_preset_data[ $field['id'] ] );
									}
								}
							}

							if ( isset( $field['set_field_value'] ) ) {
								if ( is_array( $checked_values ) ) {
									if ( is_array( $field['set_field_value'] ) ) {
										$checked_values = array_unique( array_merge( $checked_values, $field['set_field_value'] ) );
									} else {
										array_push( $checked_values, $field['set_field_value'] );
									}
								} else {
									$checked_values = array( $field['set_field_value'] );
								}
								if ( is_array( $checked_values ) ) {
									array_unique( $checked_values );
								}
							}

							if ( ! is_array( $checked_values ) ) {
								$checked_values = array( $checked_values );
							}

							$requested_checked_values = '';
							if ( isset( $_REQUEST['checkbox_radio_style_requested'] ) ) {
								$requested_checked_values = sanitize_text_field( $_REQUEST['checkbox_radio_style_requested'] );
							}

							if ( $field['options'] ) {
								$checkbox_class      = 'arf_material_checkbox';
								$use_custom_checkbox = false;
								if ( $form->form_css['arfcheckradiostyle'] == 'custom' ) {
									$checkbox_class      = 'arf_custom_checkbox';
									$use_custom_checkbox = true;
								} elseif ( $form->form_css['arfcheckradiostyle'] == 'material' ) {
									$checkbox_class .= ' arf_default_material ';
								} else {
									$checkbox_class .= ' arf_advanced_material ';
								}
								$return_string .= '<div class="setting_checkbox controls ' . $field_tooltip_class . ' ' . $checkbox_class . '" ' . $field_tooltip . '>';
								if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

									$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
								} else {

									$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );
									$k                = 0;

									$arf_chk_counter = 1;

									if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
										$return_string .= '<div class="arf_chk_radio_col_two">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
										$return_string .= '<div class="arf_chk_radio_col_thiree">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
										$return_string .= '<div class="arf_chk_radio_col_four">';
									}

									$chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}

									$image_size = '';
									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$add_image_width = 'fixed';
										$image_size      = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );

										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
										}

										$arf_radion_image_class = '';
										if ( $field['use_image'] == 1 && $label_image != '' ) {
											$arf_radion_image_class = 'arf_enable_checkbox_image';
										}

										$checked     = '';
										$checked_cls = '';
										if ( is_array( $checked_values ) ) {
											foreach ( $checked_values as $as_val ) {
												$is_checkbox_checked = false;
												if ( $as_val != '' || $field_val != '' ) {
													if ( is_array( $as_val ) ) {
														if ( in_array( $field_val, $as_val ) ) {
															$is_checkbox_checked = true;
															$checked             = ' checked="checked"';
															$checked_cls         = ' arf_checked_checkbox ';
														}
													} else {
														if ( trim( esc_attr( $as_val ) ) === trim( esc_attr( $field_val ) ) ) {
															$is_checkbox_checked = true;
															$checked             = ' checked="checked"';
															$checked_cls         = ' arf_checked_checkbox ';
														}
													}
												}
											}
										}

										$return_string .= '<div class="arf_checkbox_style ' . $arf_radion_image_class . '" id="frm_checkbox_' . $field['id'] . '-' . $opt_key . '">';
										if ( ! isset( $atts ) || ! isset( $atts['label'] ) || $atts['label'] ) {
											$_REQUEST['arfaction'] = ( isset( $_REQUEST['arfaction'] ) ) ? sanitize_text_field( $_REQUEST['arfaction'] ) : '';

											$return_string .= "<div class='arf_checkbox_input_wrapper'>";
											$return_string .= '<input type="checkbox" name="' . $field_name . '[]" data-type="checkbox" id="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" value="' . esc_attr( $field_val ) . '" ' . $checked . ' ';

											$return_string .= $arf_input_field_html;
											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													if ( isset( $field['min_opt_sel'] ) && $field['min_opt_sel'] != '' && $field['min_opt_sel'] > 0 ) {
														$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '" ';
													} else {
														$return_string .= 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field['blank'] ) . '"';
													}
												}
											}
											if ( isset( $field['max_opt_sel'] ) && $field['max_opt_sel'] != '' && $field['max_opt_sel'] > 0 ) {
												if ( $field['max_opt_sel'] > count( $field['options'] ) ) {
													$return_string .= 'data-validation-maxchecked-maxchecked="' . count( $field['options'] ) . '" data-validation-maxchecked-message="' . esc_attr( $field['max_opt_sel_msg'] ) . '"';
												} else {
													$return_string .= 'data-validation-maxchecked-maxchecked="' . $field['max_opt_sel'] . '" data-validation-maxchecked-message="' . esc_attr( $field['max_opt_sel_msg'] ) . '"';
												}
											}

											if ( isset( $field['min_opt_sel'] ) && $field['min_opt_sel'] != '' && $field['min_opt_sel'] > 0 ) {

												if ( $field['min_opt_sel'] < count( $field['options'] ) ) {

													 $return_string .= 'data-validation-minchecked-minchecked="' . $field['min_opt_sel'] . '"  data-validation-minchecked-message="' . esc_attr( $field['min_opt_sel_msg'] ) . '"';

												} else {
													$return_string .= 'data-validation-minchecked-minchecked="' . count( $field['options'] ) . '" data-validation-minchecked-message="' . esc_attr( $field['min_opt_sel_msg'] ) . '"';
												}
											}

											$return_string .= ' />';
											$return_string .= '<span>';
											if ( $use_custom_checkbox == true ) {
												$custom_checkbox = $form->form_css['arf_checked_checkbox_icon'];
												$return_string  .= "<i class='{$custom_checkbox}'></i>";
											}
											$return_string .= '</span>';
											$return_string .= '</div>';
											$return_string .= '<label data-type="checkbox" for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" >';
											if ( $field['use_image'] == 1 && $label_image != '' ) {
												$temp_check = '';

												if ( $is_checkbox_checked ) {
													$temp_check = 'checked';

												}

												if ( $inputStyle == 'material' ) {
													$return_string          .= '<label for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="arf_checkbox_label_image ' . $temp_check . ' ' . $chk_icon . ' ">';
													$return_string          .= '<svg role"none" style="max-width:100%; width:' . $image_size . 'px; height:' . $image_size . 'px">';
														$return_string      .= '<mask id="clip-cutoff_field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '">';
															$return_string  .= '<rect fill="white" x="0" y="0" rx="8" ry="8" width="' . $image_size . 'px" height="' . $image_size . 'px"></rect>';
															 $return_string .= '<rect fill="black" rx="4" ry="4" width="27" height="27" class="rect-cutoff"></rect>';
														$return_string      .= '</mask>';
														$return_string      .= '<g mask="url(#clip-cutoff_field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . ')">';
															$return_string  .= '<image x="0" y="0" height="' . $image_size . 'px" preserveAspectRatio="xMidYMid slice" width="' . $image_size . 'px" href="' . esc_attr( $label_image ) . '"></image>';
															$return_string  .= '<rect fill="none"x="0" y="0" rx="8" ry="8" width="' . $image_size . 'px" height="' . $image_size . 'px" class="img_stroke"></rect>';
														$return_string      .= '</g>';
													$return_string          .= '</svg>';
													$return_string          .= '</label>';
												} else {

													$return_string     .= '<span data-fid="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '"   class="arf_checkbox_label_image ' . $temp_check . ' ' . $chk_icon . ' ">';
														$return_string .= '<img src=' . esc_attr( $label_image ) . ' style="width:' . $image_size . 'px; height:' . $image_size . 'px; max-width:100%;">';
														$return_string .= '</span>';
												}
												$return_string .= '<span class="arf_checkbox_label" style="width:' . $image_size . 'px">';
											}

											$return_string .= html_entity_decode( $opt );
											$return_string .= '</label>';

											if ( $field['use_image'] == 1 && $label_image != '' ) {
												$is_checkbox_img_enable = true;
												$return_string         .= '</span>';
											}
										}
										$return_string .= '</div>';

										if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
											if ( $arf_chk_counter % 2 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_two">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
											if ( $arf_chk_counter % 3 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_thiree">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
											if ( $arf_chk_counter % 4 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_four">';
											}
										}
										$k++;
										$arf_chk_counter++;
									}

									if ( isset( $field['align'] ) && ( $field['align'] == 'arf_col_2' || $field['align'] == 'arf_col_3' || $field['align'] == 'arf_col_4' ) ) {
										$return_string .= '</div>';
									}
								}
								$return_string .= $field_standard_tooltip;
								$return_string .= $field_description;
								$return_string .= '</div>';
							}
							$return_string .= '</div>';
						} else {
							$alignment_class = ( isset( $field['align'] ) && $field['align'] == 'block' ) ? ' arf_vertical_radio' : ' arf_horizontal_radio';
							$return_string  .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $alignment_class . ' ' . $required_class . '  ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
							$return_string  .= $arf_main_label;

							$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input{';
								$arflite_form_all_footer_css .= 'padding-top:5px;';
							$arflite_form_all_footer_css     .= '}';

							$checked_values = '';

							if ( $arflite_preview ) {
								if ( isset( $field['field_options']['default_value'] ) && ! empty( $field['field_options']['default_value'] ) ) {
									$checked_values = $field['field_options']['default_value'];
								}

								if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

									if ( is_array( $checked_values ) ) {
										array_push( $checked_values, $arf_arr_preset_data[ $field['id'] ] );
									} else {
										$checked_values = array( $arf_arr_preset_data[ $field['id'] ] );
									}
								}
							} else {
								if ( isset( $field['default_value'] ) && ! empty( $field['default_value'] ) ) {
									$checked_values = $field['default_value'];
								}

								if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

									if ( is_array( $checked_values ) ) {
										array_push( $checked_values, $arf_arr_preset_data[ $field['id'] ] );
									} else {
										$checked_values = array( $arf_arr_preset_data[ $field['id'] ] );
									}
								}
							}

							if ( isset( $field['set_field_value'] ) ) {
								if ( is_array( $checked_values ) ) {
									array_push( $checked_values, $field['set_field_value'] );
								} else {
									$checked_values = array( $field['set_field_value'] );
								}
								if ( is_array( $checked_values ) ) {
									array_unique( $checked_values );
								}
							}

							if ( ! is_array( $checked_values ) ) {
								$checked_values = array( $checked_values );
							}

							$requested_checked_values = '';
							if ( isset( $_REQUEST['checkbox_radio_style_requested'] ) ) {
								$requested_checked_values = sanitize_text_field( $_REQUEST['checkbox_radio_style_requested'] );
							}

							if ( $field['options'] ) {
								$checkbox_class      = 'arf_standard_checkbox';
								$use_custom_checkbox = false;
								if ( $form->form_css['arfcheckradiostyle'] == 'custom' ) {
									$checkbox_class      = 'arf_custom_checkbox';
									$use_custom_checkbox = true;
								}
								if ( $form->form_css['arfinputstyle'] == 'rounded' && $form->form_css['arfcheckradiostyle'] != 'custom' ) {
									$checkbox_class      = 'arf_rounded_flat_checkbox';
									$use_custom_checkbox = false;
								}
								if ( $form->form_css['arfinputstyle'] == 'rounded' && $form->form_css['arfcheckradiostyle'] == 'custom' ) {
									$checkbox_class      = 'arf_rounded_flat_checkbox arf_custom_checkbox';
									$use_custom_checkbox = true;
								}
								$return_string .= '<div class="setting_checkbox controls ' . $checkbox_class . '" >';
								if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

									$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
								} else {

									$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );
									$k                = 0;

									$arf_chk_counter = 1;

									if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
										$return_string .= '<div class="arf_chk_radio_col_two">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
										$return_string .= '<div class="arf_chk_radio_col_thiree">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
										$return_string .= '<div class="arf_chk_radio_col_four">';
									}

									 $chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}

									$image_size = '';
									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$add_image_width = 'fixed';
										$image_size      = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );

										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
										}

										$arf_radion_image_class = '';
										if ( $field['use_image'] == 1 && $label_image != '' ) {
											$is_checkbox_img_enable = true;
											$arf_radion_image_class = 'arf_enable_checkbox_image';
										}

										$checked = '';

										if ( is_array( $checked_values ) ) {
											foreach ( $checked_values as $as_val ) {
												$is_checkbox_checked = false;
												if ( $as_val != '' || $field_val != '' ) {
													if ( is_array( $as_val ) ) {
														if ( in_array( $field_val, $as_val ) ) {
															$is_checkbox_checked = true;
															$checked             = ' checked="checked"';
														}
													} else {
														if ( trim( esc_attr( $as_val ) ) === trim( esc_attr( $field_val ) ) ) {
															$is_checkbox_checked = true;
															$checked             = ' checked="checked"';
														}
													}
												}
											}
										}

										$return_string .= '<div class="arf_checkbox_style ' . $arf_radion_image_class . '" id="frm_checkbox_' . $field['id'] . '-' . $opt_key . '">';
										if ( ! isset( $atts ) || ! isset( $atts['label'] ) || $atts['label'] ) {
											$_REQUEST['arfaction'] = ( isset( $_REQUEST['arfaction'] ) ) ? sanitize_text_field( $_REQUEST['arfaction'] ) : '';

											$return_string .= "<div class='arf_checkbox_input_wrapper'>";
											$return_string .= '<input type="checkbox" name="' . $field_name . '[]" data-type="checkbox" id="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" value="' . esc_attr( $field_val ) . '" ' . $checked . ' ';

											$return_string .= $arf_input_field_html;
											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													if ( isset( $field['min_opt_sel'] ) && $field['min_opt_sel'] != '' && $field['min_opt_sel'] > 0 ) {
														$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '" ';
													} else {
														$return_string .= 'data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field['blank'] ) . '"';
													}
												}
											}
											if ( isset( $field['max_opt_sel'] ) && $field['max_opt_sel'] != '' && $field['max_opt_sel'] > 0 ) {
												if ( $field['max_opt_sel'] > count( $field['options'] ) ) {
													$return_string .= 'data-validation-maxchecked-maxchecked="' . count( $field['options'] ) . '" data-validation-maxchecked-message="' . esc_attr( $field['max_opt_sel_msg'] ) . '"';
												} else {
													$return_string .= 'data-validation-maxchecked-maxchecked="' . $field['max_opt_sel'] . '" data-validation-maxchecked-message="' . esc_attr( $field['max_opt_sel_msg'] ) . '"';
												}
											}
											if ( isset( $field['min_opt_sel'] ) && $field['min_opt_sel'] != '' && $field['min_opt_sel'] > 0 ) {

												if ( $field['min_opt_sel'] < count( $field['options'] ) ) {

													 $return_string .= 'data-validation-minchecked-minchecked="' . $field['min_opt_sel'] . '"  data-validation-minchecked-message="' . esc_attr( $field['min_opt_sel_msg'] ) . '"';

												} else {
													$return_string .= 'data-validation-minchecked-minchecked="' . count( $field['options'] ) . '" data-validation-minchecked-message="' . esc_attr( $field['min_opt_sel_msg'] ) . '"';
												}
											}

											$return_string .= ' />';

											$return_string .= '<span>';
											if ( $use_custom_checkbox ) {
												$custom_checkbox = $form->form_css['arf_checked_checkbox_icon'];
												$return_string  .= "<i class='{$custom_checkbox}'></i>";
											}
											$return_string .= '</span>';
											$return_string .= '</div>';
											$return_string .= '<label data-type="checkbox" for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" >';
											if ( $field['use_image'] == 1 && $label_image != '' ) {
												$temp_check             = '';
												$is_checkbox_img_enable = true;
												if ( $is_checkbox_checked ) {
													$temp_check = 'checked';
												}

												$return_string .= '<span data-fid="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '"   class="arf_checkbox_label_image ' . $temp_check . ' ' . $chk_icon . '">';
												$return_string .= '<img src="' . esc_attr( $label_image ) . '" style="max-width:100%; width:' . $image_size . 'px; height:' . $image_size . 'px">';
												$return_string .= '</span>';
												$return_string .= '<span class="arf_checkbox_label" style="width:' . $image_size . 'px">';

											}
											$return_string .= html_entity_decode( $opt );

											if ( $field['use_image'] == 1 && $label_image != '' ) {
												$is_checkbox_img_enable = true;
												$return_string         .= '</span>';
											}

											$return_string .= '</label>';

										}
										$return_string .= '</div>';

										if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
											if ( $arf_chk_counter % 2 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_two">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
											if ( $arf_chk_counter % 3 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_thiree">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
											if ( $arf_chk_counter % 4 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_four">';
											}
										}
										$k++;
										$arf_chk_counter++;
									}

									if ( isset( $field['align'] ) && ( $field['align'] == 'arf_col_2' || $field['align'] == 'arf_col_3' || $field['align'] == 'arf_col_4' ) ) {
										$return_string .= '</div>';
									}
								}
								$return_string .= $field_standard_tooltip;
								$return_string .= $field_description;
								$return_string .= '</div>';
							}
							$return_string .= '</div>';
						}
						break;
					case 'radio':
						if ( $inputStyle == 'material' ) {
							$alignment_class = ( isset( $field['align'] ) && $field['align'] == 'block' ) ? ' arf_vertical_radio' : ' arf_horizontal_radio';
							$return_string  .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $alignment_class . ' ' . $required_class . ' ' . $error_class . ' ' . $class_position . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
							$return_string  .= $arf_main_label;

							$requested_radio_checked_values = '';
							if ( isset( $_REQUEST['checkbox_radio_style_requested'] ) ) {
								$requested_radio_checked_values = sanitize_text_field( $_REQUEST['checkbox_radio_style_requested'] );
							}
							if ( isset( $field['set_field_value'] ) ) {
								$field['value'] = $field['set_field_value'];
							}

							$arf_radion_image_class = '';
							if ( isset( $field['label_image'] ) && $field['label_image'] ) {
								$arf_radion_image_class = 'arf_enable_radio_image';
							}

							if ( is_array( $field['options'] ) ) {

								$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input{';
									$arflite_form_all_footer_css .= 'padding-top: 5px;';
								$arflite_form_all_footer_css     .= '}';
								$radio_class                      = 'arf_material_radio';
								$use_custom_radio                 = false;
								if ( $form->form_css['arfcheckradiostyle'] == 'custom' ) {
									$radio_class      = 'arf_custom_radio';
									$use_custom_radio = true;
								} elseif ( $form->form_css['arfcheckradiostyle'] == 'material' ) {
									$radio_class .= ' arf_default_material ';
								} else {
									$radio_class .= ' arf_advanced_material ';
								}

								$return_string .= '<div class="setting_radio controls ' . $field_tooltip_class . ' ' . $radio_class . '" ' . $field_tooltip . '>';
								if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

									$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
								} else {
									$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );

									$k               = 0;
									$arf_chk_counter = 1;

									if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
										$return_string .= '<div class="arf_chk_radio_col_two">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
										$return_string .= '<div class="arf_chk_radio_col_thiree">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
										$return_string .= '<div class="arf_chk_radio_col_four">';
									}

									$chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}

									$image_size = '';
									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$image_size = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );
										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
										}

										if ( isset( $field['value'] ) && isset( $field['set_field_value'] ) && $field['value'] != '' ) {
											$field['default_value'] = $field['value'];
										}

										$arf_radio_input_wrapper_cls = '';
										if ( $field['use_image'] == 1 && isset( $label_image ) && $label_image != '' ) {
											$is_radio_img_enable         = true;
											$arf_radio_input_wrapper_cls = 'arf_enable_radio_image';
										}
										$return_string .= '<div class="arf_radiobutton ' . $arf_radio_input_wrapper_cls . '">';

										if ( ! isset( $atts ) || ! isset( $atts['label'] ) || $atts['label'] ) {

											$return_string .= "<div class='arf_radio_input_wrapper'>";

											$return_string   .= '<input type="radio" name="' . $field_name . '" data-type="radio" id="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" data-unique-id="' . $arflite_data_uniq_id . '" value="' . esc_attr( $field_val ) . '" ';
											$is_radio_checked = false;
											if ( isset( $field['default_value'] ) && $field['default_value'] != '' && $field_val == $field['default_value'] ) {
												$is_radio_checked = true;
												$return_string   .= 'checked="checked" ';
											}

											if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) && $field_val == $arf_arr_preset_data[ $field['id'] ] ) {

												$is_radio_checked = true;
												$return_string   .= 'checked="checked" ';
											}

											$return_string .= $arf_input_field_html;

											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													$return_string .= ' data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field['blank'] ) . '"';
												}
											}

											$return_string .= ' />';
											$return_string .= '<span>';
											if ( $use_custom_radio == true ) {
												$custom_radio   = $form->form_css['arf_checked_radio_icon'];
												$return_string .= "<i class='{$custom_radio}'></i>";
											}
											$return_string         .= '</span>';
											$return_string         .= '</div>';
											$arf_radion_image_class = '';
											if ( $field['use_image'] == 1 && isset( $label_image ) && $label_image != '' ) {
												$is_radio_img_enable    = true;
												$arf_radion_image_class = 'arf_enable_radio_image';
											}

											$return_string .= '<label data-type="radio" for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="' . $arf_radion_image_class . '">';
											if ( $field['use_image'] == 1 && $label_image != '' ) {

												$is_radio_img_enable = true;

												if ( $inputStyle == 'material' ) {
													$return_string .= '<label for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="arf_radio_label_image ' . $chk_icon . ' ' . ( ( $is_radio_checked ) ? ' checked ' : '' ) . '">';

															$return_string .= '<svg role"none" style="max-width:100%; width:' . $image_size . 'px; height:' . $image_size . 'px">';

																$return_string     .= '<mask id="clip-cutoff_field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '">';
																	$return_string .= '<rect fill="white" x="0" y="0" rx="8" ry="8" width="' . $image_size . 'px" height="' . $image_size . 'px"></rect>';
																	$return_string .= '<rect fill="black" rx="4" ry="4" width="27" height="27" class="rect-cutoff"></rect>';
																$return_string     .= '</mask>';
																$return_string     .= '<g mask="url(#clip-cutoff_field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . ')">';
																	$return_string .= '<image x="0" y="0" height="' . $image_size . 'px" preserveAspectRatio="xMidYMid slice" width="' . $image_size . 'px" href="' . esc_attr( $label_image ) . '"></image>';
																	$return_string .= '<rect fill="none"x="0" y="0" rx="8" ry="8" width="' . $image_size . 'px" height="' . $image_size . 'px" class="img_stroke"></rect>';
																$return_string     .= '</g>';
																$return_string     .= '</svg>';
														$return_string             .= '</label>';
												} else {

													$return_string .= '<span data-fid="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="arf_radio_label_image ' . $chk_icon . ' ' . ( ( $is_radio_checked ) ? ' checked ' : '' ) . '">';
													$return_string .= '<img src=' . esc_attr( $label_image ) . ' style="width: ' . $image_size . 'px; height: ' . $image_size . 'px; max-width:100%;"></span>';

												}
												$return_string .= '<span class="arf_radio_label" style="width:' . $image_size . 'px">';
											}
											$return_string .= html_entity_decode( $opt );
											if ( isset( $field['radio_use_image'] ) && $field['radio_use_image'] ) {
												$is_radio_img_enable = true;
												$return_string      .= '</span>';
											}

											$return_string .= '</label>';
										}
										$return_string .= '</div>';
										if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
											if ( $arf_chk_counter % 2 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_two">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
											if ( $arf_chk_counter % 3 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_thiree">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
											if ( $arf_chk_counter % 4 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_four">';
											}
										}
										$k++;
										$arf_chk_counter++;
									}

									if ( isset( $field['align'] ) && ( $field['align'] == 'arf_col_2' || $field['align'] == 'arf_col_3' || $field['align'] == 'arf_col_4' ) ) {
										$return_string .= '</div>';
									}
								}
								$return_string .= $field_standard_tooltip;
								$return_string .= $field_description;

								$return_string .= '</div>';
							}

							$return_string .= '</div>';
						} else {
							$alignment_class = ( isset( $field['align'] ) && $field['align'] == 'block' ) ? ' arf_vertical_radio' : ' arf_horizontal_radio';
							$return_string  .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $alignment_class . ' ' . $required_class . '  ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
							$return_string  .= $arf_main_label;

							$requested_radio_checked_values = '';
							if ( isset( $_REQUEST['checkbox_radio_style_requested'] ) ) {
								$requested_radio_checked_values = sanitize_text_field( $_REQUEST['checkbox_radio_style_requested'] );
							}
							if ( isset( $field['set_field_value'] ) ) {
								$field['value'] = $field['set_field_value'];
							}

							$arf_radion_image_class = '';
							if ( isset( $field['label_image'] ) && $field['label_image'] ) {
								$arf_radion_image_class = 'arf_enable_radio_image';
							}

							if ( is_array( $field['options'] ) ) {

								$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input{';
									$arflite_form_all_footer_css .= 'padding-top: 5px;';
								$arflite_form_all_footer_css     .= '}';
								$radio_class                      = 'arf_standard_radio';
								$use_custom_radio                 = false;
								if ( $form->form_css['arfcheckradiostyle'] == 'custom' ) {
									$radio_class      = 'arf_custom_radio';
									$use_custom_radio = true;
								}
								if ( $form->form_css['arfinputstyle'] == 'rounded' && $form->form_css['arfcheckradiostyle'] != 'custom' ) {
									$radio_class      = 'arf_rounded_flat_radio';
									$use_custom_radio = false;
								}

								$return_string .= '<div class="setting_radio controls ' . $radio_class . ' ">';
								if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

									$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
								} else {
									$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );

									$k               = 0;
									$arf_chk_counter = 1;

									if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
										$return_string .= '<div class="arf_chk_radio_col_two">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
										$return_string .= '<div class="arf_chk_radio_col_thiree">';
									} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
										$return_string .= '<div class="arf_chk_radio_col_four">';
									}

									 $chk_icon = '';
									if ( isset( $field['arflite_check_icon'] ) && $field['arflite_check_icon'] != '' ) {
										$chk_icon = $field['arflite_check_icon'];
									} else {
										$chk_icon = 'fas fa-check';
									}

									$image_size = '';
									if ( isset( $field['image_width'] ) && $field['image_width'] != '' ) {
										$image_size = $field['image_width'];
									} else {
										$image_size = 120;
									}

									foreach ( $field['options'] as $opt_key => $opt ) {
										$label_image = '';
										if ( isset( $atts ) && isset( $atts['opt'] ) && ( $atts['opt'] != $opt_key ) ) {
											continue;
										}

										$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

										$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );
										if ( is_array( $opt ) ) {
											$label_image = isset( $opt['label_image'] ) ? $opt['label_image'] : '';
											$opt         = $opt['label'];
											$field_val   = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
										}
										if ( isset( $field['value'] ) && isset( $field['set_field_value'] ) && $field['value'] != '' ) {
											$field['default_value'] = $field['value'];
										}

											$arf_radion_image_class = '';
										if ( $field['use_image'] == 1 && isset( $label_image ) && $label_image != '' ) {
											$is_radio_img_enable    = true;
											$arf_radion_image_class = 'arf_enable_radio_image';
										}
											$return_string .= '<div class="arf_radiobutton ' . $arf_radion_image_class . '">';

										if ( ! isset( $atts ) || ! isset( $atts['label'] ) || $atts['label'] ) {

											$return_string .= "<div class='arf_radio_input_wrapper'>";

												$return_string   .= '<input type="radio" name="' . $field_name . '" data-type="radio" id="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" data-unique-id="' . $arflite_data_uniq_id . '" value="' . esc_attr( $field_val ) . '" ';
												$is_radio_checked = false;
											if ( isset( $field['default_value'] ) && $field['default_value'] != '' && $field_val == $field['default_value'] ) {
												$is_radio_checked = true;
												$return_string   .= 'checked="checked" ';
											}

											if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) && $field_val == $arf_arr_preset_data[ $field['id'] ] ) {

												$is_radio_checked = true;
												$return_string   .= 'checked="checked" ';
											}

											$return_string .= $arf_input_field_html;

											if ( $k == 0 ) {
												if ( isset( $field['required'] ) && $field['required'] ) {
													$return_string .= ' data-validation-minchecked-minchecked="1" data-validation-minchecked-message="' . esc_attr( $field['blank'] ) . '"';
												}
											}

											$return_string .= ' />';
											$return_string .= '<span>';
											if ( $use_custom_radio == true ) {
												$custom_radio   = $form->form_css['arf_checked_radio_icon'];
												$return_string .= "<i class='{$custom_radio}'></i>";
											}
											$return_string .= '</span>';
											$return_string .= '</div>';

											$return_string .= '<label data-type="radio" for="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="' . $arf_radion_image_class . '">';

											if ( $field['use_image'] == 1 && $label_image != '' ) {

												$is_radio_img_enable = true;

												 $return_string   .= '<span data-fid="field_' . $field['id'] . '-' . $opt_key . '-' . $arflite_data_uniq_id . '" class="arf_radio_label_image ' . $chk_icon . '' . ( ( $is_radio_checked ) ? ' checked ' : '' ) . '">';
												   $return_string .= '<img src="' . esc_attr( $label_image ) . '" style="width:' . $image_size . 'px; height:' . $image_size . 'px; max-width:100%;"></span><span class="arf_radio_label" style="width:' . $image_size . 'px">';
											}

											$return_string .= html_entity_decode( $opt );

											if ( $label_image != '' ) {
												$return_string .= '</span>';
											}

											$return_string .= '</label>';
										}
										$return_string .= '</div>';
										if ( isset( $field['align'] ) && $field['align'] == 'arf_col_2' ) {
											if ( $arf_chk_counter % 2 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_two">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_3' ) {
											if ( $arf_chk_counter % 3 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_thiree">';
											}
										} elseif ( isset( $field['align'] ) && $field['align'] == 'arf_col_4' ) {
											if ( $arf_chk_counter % 4 == 0 ) {
												$return_string .= '</div><div class="arf_chk_radio_col_four">';
											}
										}
										$k++;
										$arf_chk_counter++;
									}

									if ( isset( $field['align'] ) && ( $field['align'] == 'arf_col_2' || $field['align'] == 'arf_col_3' || $field['align'] == 'arf_col_4' ) ) {
										$return_string .= '</div>';
									}
								}
								$return_string .= $field_standard_tooltip;
								$return_string .= $field_description;

								$return_string .= '</div>';
							}

							$return_string .= '</div>';
						}
						break;
					case 'select':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield input-field control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}
						$return_string .= '<div class=" sltstandard_front controls' . $field_tooltip_class . '" ' . $field_tooltip . '>';

						$arfdefault_selected_val = ( isset( $field['separate_value'] ) && $field['separate_value'] ) ? $field['default_value'] : ( isset( $field['value'] ) ? $field['value'] : '' );

						if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

							$arfdefault_selected_val = $arf_arr_preset_data[ $field['id'] ];
						}

						if ( isset( $field['set_field_value'] ) ) {
							$arfdefault_selected_val = $field['set_field_value'];
						}
						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {
							$field['options'] = $arflitefieldhelper->arflitechangeoptionorder( $field );

							$select_attrs = array();

							$sel_field_id = 'field_' . $field['field_key'] . '_' . $arflite_data_uniq_id;

							$select_field_opts = array();

							$list_attrs            = array();
							$arf_set_default_label = false;

							if ( ! empty( $field['options'] ) ) {
								$count_i = 0;
								foreach ( $field['options'] as $opt_key => $opt ) {
									$field_val = apply_filters( 'arflitedisplaysavedfieldvalue', $opt, $opt_key, $field );

									$opt = apply_filters( 'arflite_show_field_label', $opt, $opt_key, $field );

									if ( is_array( $opt ) ) {
										$opt = $opt['label'];
										if ( $field_val['value'] == '(Blank)' ) {
											$field_val['value'] = '';
										}
										$field_val = ( isset( $field['separate_value'] ) ) ? $field_val['value'] : $opt;
									}
									if ( $count_i == 0 and $opt == '' ) {
										$opt = esc_html__( 'Please select', 'arforms-form-builder' );
									}

									$field['value']          = isset( $field['value'] ) ? $field['value'] : '';
									$arfdefault_selected_val = ( isset( $field['separate_value'] ) ) ? $field['default_value'] : $field['value'];
									if ( isset( $field['set_field_value'] ) && ! empty( $field['set_field_value'] ) ) {
										$arfdefault_selected_val = $field['set_field_value'];
									}

									if ( ! empty( $arfdefault_selected_val ) ) {
										$arf_set_default_label = false;
									}

									$select_field_opts[ $field_val ] = $opt;
									$count_i++;
								}
							}

							$select_attrs['data-default-val'] = $arfdefault_selected_val;
							if ( isset( $field['required'] ) and $field['required'] ) {
								$select_attrs['data-validation-required-message'] = esc_attr( $field['blank'] );
							}

							$select_attrs['data-field_id'] = $field['id'];

							if ( $inputStyle == 'material' ) {
								$mo_active_container_cls = ( ! empty( $arfdefault_selected_val ) ) ? 'arf_material_active_container_open' : '';
								$return_string          .= '<div class="arf_material_theme_container ' . $mo_active_container_cls . '">';
							}

							if ( wp_is_mobile() ) {
								$select_attrs['readonly'] = 'readonly';
							}

							$return_string .= $arflitemaincontroller->arflite_selectpicker_dom( $field_name, $sel_field_id, ' arf_form_field_picker ', '', $arfdefault_selected_val, $select_attrs, $select_field_opts, false, array(), false, array(), true, $field, false, '', '', $arf_set_default_label );

							if ( $inputStyle == 'material' ) {
									$return_string         .= '<div class="arf_material_standard">';
										$return_string     .= '<div class="arf_material_theme_prefix"></div>';
										$return_string     .= '<div class="arf_material_theme_notch">';
											$return_string .= $arf_main_label;
										$return_string     .= '</div>';
										$return_string     .= '<div class="arf_material_theme_suffix"></div>';
									$return_string         .= '</div>';
								$return_string             .= '</div>';
							}

							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'number':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $material_input_cls . ' ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';

						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							$return_string .= $prefix;
							$num_field_type = 'text';
							$arflitehttpserver = !empty($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '';
							if ( wp_is_mobile() && strpos( $arflitehttpserver, 'Android' ) !== false ) {
								$num_field_type = 'number';
							}
							if ( 'material' == $inputStyle ) {

								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}
							$return_string .= '<input type="' . esc_attr( $num_field_type ) . '" class="arf_number_field" id="field_' . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . '" dir=" ';

							if ( isset( $field['text_direction'] ) && $field['text_direction'] == '0' ) {
								$return_string .= 'rtl';
							} else {
								$return_string .= 'ltr';
							}
							$return_string .= '"';
							$return_string .= 'name="' . esc_attr( $field_name ) . '" ';

							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							$default_value = $field['default_value'];

							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {
								$default_value = $arf_arr_preset_data[ $field['id'] ];
							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							if ( $default_value != '' ) {
								$return_string .= ' value=' . esc_attr( $default_value ) . "'";
							}

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}

							$return_string .= $arf_input_field_html;

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= 'data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}

							if ( $field['type'] == 'number' && $field['maxnum'] != '' && $field['maxnum'] > 0 ) {
								$return_string .= ' data-validation-max-message="' . esc_attr( $field['invalid'] ) . '" ';
							}

							if ( $field['minnum'] != '' ) {
								$return_string .= ' min="' . $field['minnum'] . '" ';
							}

							if ( $field['maxnum'] != '' ) {
								$return_string .= ' max="' . $field['maxnum'] . '" ';
							}

							$return_string .= ' onkeydown="arflitevalidatenumber(this,event);" ';
							if ( $field['minlength'] != '' && 0 < $field['minlength'] ) {
								$return_string .= ' minlength="' . $field['minlength'] . '" data-validation-minlength-message="' . esc_attr( $field['minlength_message'] ) . '" ';
							}
							$return_string .= '/>';
							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}
							$return_string .= $suffix;

							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'phone':
					case 'tel':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}
						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {
							$return_string .= $prefix;

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								$has_phone_with_utils  = false;
								$phone_with_utils_cls  = '';
								if ( isset( $field['phonetype'] ) ) {
									if ( $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
										$has_phone_with_utils = true;
										$phone_with_utils_cls = 'arf_phone_with_flag';
									}
								}
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons ' . $phone_with_utils_cls;
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input type="text" data-type="phone"  id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '"';
							$return_string .= 'name="' . $field_name . '"';

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}

							$return_string .= $arflitefieldcontroller->arflite_input_fieldhtml( $field, false );
							$phone_flag     = false;

							if ( isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {
								$phtypes = array();
								foreach ( $field['phtypes'] as $key => $vphtype ) {
									if ( $vphtype != 0 ) {
										array_push( $phtypes, strtolower( str_replace( 'phtypes_', '', $key ) ) );
									}
								}

								$return_string .= ' data-defaultCountryCode="' . $phtypes[0] . '" ';

								if ( isset( $field['country_validation'] ) && $field['country_validation'] == 1 ) {
									$return_string .= ' data-do-validation="true" ';
									$return_string .= 'data-invalid-format-message="' . esc_attr( $field['invalid'] ) . '"';
								} else {
									$return_string .= ' data-do-validation="false" ';
								}
								$phone_flag = true;
							}

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= 'data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}

							if ( ! $phone_flag ) {
								if ( $field['phone_validation'] == 'international' ) {
									$return_string .= 'data-validation-number-message="' . esc_attr( $field['invalid'] ) . '"';
									$phone_regex    = '';
									$inputmask      = '';
								} else {
									if ( $field['phone_validation'] == 'custom_validation_1' ) {
										$phone_regex = '^[(]{1}[0-9]{3,4}[)]{1}[0-9]{3}[\s]{1,1}[0-9]{4}$';
										$inputmask   = '(999)999 9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_2' ) {
										$phone_regex = '^[(]{1}[0-9]{3,4}[)]{1}[\s]{1}[0-9]{3}[\s]{1}[0-9]{4}$';
										$inputmask   = '(999) 999 9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_3' ) {
										$phone_regex = '^[(]{1}[0-9]{3,4}[)]{1}[0-9]{3}[-]{1}[0-9]{4}$';
										$inputmask   = '(999)999-9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_4' ) {
										$phone_regex = '^[(]{1}[0-9]{3,4}[)]{1}[\s]{1}[0-9]{3}[-]{1}[0-9]{4}$';
										$inputmask   = '(999) 999-9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_5' ) {
										$phone_regex = '^[0-9]{3,4}[\s]{1}[0-9]{3}[\s]{1}[0-9]{4}$';
										$inputmask   = '999 999 9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_6' ) {
										$phone_regex = '^[0-9]{3,4}[\s]{1}[0-9]{3}[-]{1}[0-9]{4}$';
										$inputmask   = '999 999-9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_7' ) {
										$phone_regex = '^[0-9]{3,4}[-]{1}[0-9]{3}[-]{1}[0-9]{4}$';
										$inputmask   = '999-999-9999';
									} elseif ( $field['phone_validation'] == 'custom_validation_8' ) {
										$phone_regex = '^[0-9]{4,5}[\s]{1}[0-9]{3}[\s]{1}[0-9]{3}$';
										$inputmask   = '99999 999 999';
									} elseif ( $field['phone_validation'] == 'custom_validation_9' ) {
										$phone_regex = '^[0-9]{4,5}[\s]{1}[0-9]{6}$';
										$inputmask   = '99999 999999';
									}
									$return_string .= ' data-validation-regex-regex="' . @$phone_regex . '"';
									$return_string .= ' data-mask="' . @$inputmask . '"';
									$return_string .= ' data-validation-regex-message="' . esc_attr( $field['invalid'] ) . '"';
								}
							}

							if ( wp_is_mobile() ) {
								if ( isset( $inputmask ) && $inputmask != '' ) {
									$return_string .= ' data-mask-input="' . $inputmask . '"';
									$return_string .= ' data-ismask="true" ';
								}
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							$default_value = $field['default_value'];

							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {
								$default_value = $arf_arr_preset_data[ $field['id'] ];

							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							if ( $default_value != '' ) {
								$return_string .= " value='{$default_value}'";
							}

							$return_string .= '/>';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;
							$return_string .= $field_standard_tooltip;
						}

						$return_string .= $field_description;
						$return_string .= '</div>';
						if ( isset( $field['phonetype'] ) && $field['phonetype'] == 1 ) {

							if ( isset( $phtypes ) && count( $phtypes ) > 0 ) {
								$return_string .= "<input type='hidden' data-jqvalidate='false' id='field_" . esc_attr( $field['field_key'] ) . "_country_list' value='" . esc_attr( json_encode( $phtypes ) ) . "' />";
							}

							$phone_hidden_name = 'item_meta[' . $field['id'] . '_country_code]';

							$return_string .= "<input type='hidden' data-jqvalidate='false' name='" . esc_attr( $phone_hidden_name ) . "' id='field_" . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . "_country_code' />";

							$default_country     = isset( $field['default_country'] ) ? $field['default_country'] : '';
							$arf_default_country = '';
							if ( $default_country != '' && in_array( $default_country, $phtypes ) ) {
								$arf_default_country = $default_country;
							}

							$return_string .= "<input type='hidden' data-jqvalidate='false' id='field_" . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . "_default_country' value='" . esc_attr( $arf_default_country ) . "' />";
						}
						$return_string .= '</div>';
						break;
					case 'url':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . '' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							$regex  = '((https?|ftp):\/\/)?';
							$regex .= '((HTTPS?|ftp):\/\/)?';
							$regex .= '([A-Za-z0-9+!*(),;?&=$_.-]+(:[A-Za-z0-9+!*(),;?&=$_.-]+)?@)?';
							$regex .= '([A-Za-z0-9-.]*)\.([A-Za-z]+)';
							$regex .= '(:[0-9]{2,5})?';
							$regex .= '(\/([A-Za-z0-9+!$_-]\.?)+)*\/?';
							$regex .= '(\?[A-Za-z+&$_.-][A-Za-z0-9;:@&%=+\/$_.-]*)?';
							$regex .= '(#[A-Za-z_.-][A-Za-z0-9+$_.-]*)?';

							$return_string .= $prefix;

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input type="url" id="field_' . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . '" ';

							$return_string .= 'name="' . esc_attr( $field_name ) . '" ';

							$default_value = $field['default_value'];

							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

								$default_value = $arf_arr_preset_data[ $field['id'] ];

							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							if ( $default_value != '' ) {
								$return_string .= " value='" . esc_attr( $default_value ) . "'";
							}

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}

							if ( isset( $field['clear_on_focus'] ) && $field['clear_on_focus'] ) {
								$return_string .= ' onfocus="arflitecleardedaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
								$return_string .= ' onblur="arflitereplacededaultvalueonfocus(\'' . $field['placeholdertext'] . '\',this,\'' . $is_default_blank . '\')"';
							}

							$return_string .= $arf_input_field_html;

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}
							$return_string .= ' data-validation-regex-regex="' . $regex . '" data-validation-regex-message="' . esc_attr( $field['invalid'] ) . '" ';

							$return_string .= '/>';
							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}
							$return_string .= $suffix;

							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'date':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';

						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="controls arf_date_main_controls ' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							$return_string .= $prefix;

							$wp_format_date     = get_option( 'date_format' );
							$defaultdate_format = '';
							if ( $wp_format_date == 'F j, Y' || $wp_format_date == 'm/d/Y' ) {
								if ( $field['arfnewdateformat'] == 'MMMM D, YYYY' ) {
									$defaultdate_format = 'F d, Y';
								} elseif ( $field['arfnewdateformat'] == 'MMM D, YYYY' ) {
									$defaultdate_format = 'M d, Y';
								} else {
									$defaultdate_format = 'm/d/Y';
								}
							} elseif ( $wp_format_date == 'd/m/Y' ) {
								if ( $field['arfnewdateformat'] == 'D MMMM, YYYY' ) {
									$defaultdate_format = 'd F, Y';
								} elseif ( $field['arfnewdateformat'] == 'D MMM, YYYY' ) {
									$defaultdate_format = 'd M, Y';
								} else {
									$defaultdate_format = 'd/m/Y';
								}
							} elseif ( $wp_format_date == 'Y/m/d' ) {
								if ( $field['arfnewdateformat'] == 'YYYY, MMMM D' ) {
									$defaultdate_format = 'Y, F d';
								} elseif ( $field['arfnewdateformat'] == 'YYYY, MMM D' ) {
									$defaultdate_format = 'Y, M d';
								} else {
									$defaultdate_format = 'Y/m/d';
								}
							} elseif ( $wp_format_date == 'd.F.y' || $wp_format_date == 'd.m.Y' || $wp_format_date == 'Y.m.d' || $wp_format_date == 'd. F Y' ) {
								if ( $field['arfnewdateformat'] == 'D.MM.YYYY' ) {
									$defaultdate_format = 'd.m.Y';
								} elseif ( $field['arfnewdateformat'] == 'D.MMMM.YY' ) {
									$defaultdate_format = 'd.F.y';
								} elseif ( $field['arfnewdateformat'] == 'YYYY.MM.D' ) {
									$defaultdate_format = 'Y.m.d';
								} elseif ( $field['arfnewdateformat'] == 'D. MMMM YYYY' ) {
									$defaultdate_format = 'd. F Y';
								}
							} else {
								if ( $field['arfnewdateformat'] == 'MMMM D, YYYY' ) {
									$defaultdate_format = 'F d, Y';
								} elseif ( $field['arfnewdateformat'] == 'MMM D, YYYY' ) {
									$defaultdate_format = 'M d, Y';
								} elseif ( $field['arfnewdateformat'] == 'YYYY/MM/DD' ) {
									$defaultdate_format = 'Y/m/d';
								} elseif ( $field['arfnewdateformat'] == 'MM/DD/YYYY' ) {
									$defaultdate_format = 'm/d/Y';
								} else {
									$defaultdate_format = 'd/m/Y';
								}
							}

							if ( '' == $defaultdate_format ) {
								$formate = ! empty( $form->form_css['date_format'] ) ? $form->form_css['date_format'] : $wp_format_date;
								if ( $formate == 'MM/DD/YYYY' ) {
									$formate = 'm/d/Y';
								} elseif ( $formate == 'MMM D, YYYY' ) {
									$formate = 'M d, Y';
								} elseif ( $formate == 'MMMM D, YYYY' ) {
									$formate = 'F d, Y';
								} elseif ( $formate == 'YYYY/MM/DD' ) {
									$formate = 'Y/m/d';
								} elseif ( $formate == 'DD/MM/YYYY' ) {
									$formate = 'd/m/Y';
								} elseif ( $formate == 'D.MM.YYYY' ) {
									$formate = 'd.m.Y';
								} elseif ( $formate == 'D.MMMM.YY' ) {
									$formate = 'd.F.y';
								} elseif ( $formate == 'YYYY.MM.D' ) {
									$formate = 'Y.m.d';
								} elseif ( $formate == 'D. MMMM YYYY' ) {
									$formate = 'd. F Y';
								}

								$defaultdate_format = $formate;
							}

							$show_year_month_calendar = 'true';

							if ( isset( $field['show_year_month_calendar'] ) && $field['show_year_month_calendar'] < 1 ) {
								$show_year_month_calendar = 'false';
							}

							$show_time_calendar = 'true';
							if ( @$field['show_time_calendar'] < 1 ) {
								$show_time_calendar = 'false';
							}

							$arf_show_min_current_date = 'true';
							if ( @$field['arf_show_min_current_date'] < 1 ) {
								$arf_show_min_current_date = 'false';
							}

							if ( $arf_show_min_current_date == 'true' ) {
								$field['start_date'] = current_time( 'd/m/Y' );
							} else {
								$field['start_date'] = $field['start_date'];
							}

							$arf_show_max_current_date = 'true';
							if ( @$field['arf_show_max_current_date'] < 1 ) {
								$arf_show_max_current_date = 'false';
							}

							if ( $arf_show_max_current_date == 'true' ) {
								$field['end_date'] = current_time( 'd/m/Y' );
							} else {
								$field['end_date'] = $field['end_date'];
							}

							$date = new DateTime();

							if ( $field['end_date'] == '' ) {
								$field['end_date'] = '31/12/2050';
							}

							if ( $field['start_date'] == '' ) {
								$field['start_date'] = '01/01/1950';
							}

							$end_date_temp = explode( '/', $field['end_date'] );
							$date->setDate( $end_date_temp[2], $end_date_temp[1], $end_date_temp[0] );
							$date1           = new DateTime();
							$start_date_temp = explode( '/', $field['start_date'] );
							$date1->setDate( $start_date_temp[2], $start_date_temp[1], $start_date_temp[0] );

							if ( $newarr['date_format'] == 'MM/DD/YYYY' || $newarr['date_format'] == 'MMMM D, YYYY' || $newarr['date_format'] == 'MMM D, YYYY' ) {
								$start_date      = $date1->format( 'm/d/Y' );
								$end_date        = $date->format( 'm/d/Y' );
								$date_new_format = 'MM/DD/YYYY';
							} elseif ( $newarr['date_format'] == 'DD/MM/YYYY' || $newarr['date_format'] == 'D MMMM, YYYY' || $newarr['date_format'] == 'D MMM, YYYY' ) {
								$start_date      = $date1->format( 'd/m/Y' );
								$end_date        = $date->format( 'd/m/Y' );
								$date_new_format = 'DD-MM-YYYY';
							} elseif ( $newarr['date_format'] == 'YYYY/MM/DD' || $newarr['date_format'] == 'YYYY, MMMM D' || $newarr['date_format'] == 'YYYY, MMM D' ) {
								$start_date      = $date1->format( 'Y/m/d' );
								$end_date        = $date->format( 'Y/m/d' );
								$date_new_format = 'YYYY-MM-DD';
							} else {
								$start_date           = $date1->format( 'm/d/Y' );
								$end_date             = $date->format( 'm/d/Y' );
								$date_new_format      = 'MM/DD/YYYY';
								$field['date_format'] = 'MMM D, YYYY';
							}

							if ( $newarr['date_format'] == 'MM/DD/YYYY' ) {
								$date_new_format_main = 'MM/DD/YYYY';
							} elseif ( $newarr['date_format'] == 'DD/MM/YYYY' ) {
								$date_new_format_main = 'DD/MM/YYYY';
							} elseif ( $newarr['date_format'] == 'YYYY/MM/DD' ) {
								$date_new_format_main = 'YYYY/MM/DD';
							} elseif ( $newarr['date_format'] == 'MMM D, YYYY' ) {
								$date_new_format_main = 'MMM D, YYYY';
							} elseif ( $newarr['date_format'] == 'MM.D.YYYY' ) {
								$date_new_format_main = 'MM.D.YYYY';
							} elseif ( $newarr['date_format'] == 'MMMM.D.YY' ) {
								$date_new_format_main = 'MMMM.D.YY';
							} elseif ( $newarr['date_format'] == 'D.MM.YYYY' ) {
								$date_new_format_main = 'D.MM.YYYY';
							} elseif ( $newarr['date_format'] == 'D.MMMM.YY' ) {
								$date_new_format_main = 'D.MMMM.YY';
							} elseif ( $newarr['date_format'] == 'YYYY.MM.D' ) {
								$date_new_format_main = 'YYYY.MM.D';
							} elseif ( $newarr['date_format'] == 'D. MMMM YYYY' ) {
								$date_new_format_main = 'D. MMMM YYYY';
							} else {
								$date_new_format_main = 'MMMM D, YYYY';
							}

							if ( isset( $field['clock'] ) && $field['clock'] == '24' ) {
								$format = 'H:mm';
							} else {
								$format = 'h:mm A';
							}

							$off_days = array();

							if ( $field['off_days'] != '' ) {
								$off_days = explode( ',', $field['off_days'] );
							}

							$off_days_result = '';
							$off_day_count   = '';

							$off_day_count1 = '';
							foreach ( $off_days as $offday ) {
								$off_day_count  .= ' day != ' . $offday . ' &&';
								$off_day_count1 .= ' day == ' . $offday . ' ||';
							}

							if ( $field['off_days'] != '' && $off_day_count != '' ) {
								$off_day_count   = substr( $off_day_count, 0, -2 );
								$off_days_result = ',beforeShowDay:function(date){ var day = date.getDay();return [(' . $off_day_count . ')]; }';
							} else {
								$off_days_result = ',beforeShowDay:function(date){ var day = date.getDay();return [true]; }';
							}
							$field['locale'] = ( $field['locale'] != '' ) ? $field['locale'] : 'en';

							$date_formate = $newarr['date_format'];
							if ( $show_time_calendar == 'true' ) {
								$field['clock']       = ( isset( $field['clock'] ) && $field['clock'] ) ? $field['clock'] : 'h:mm A';
								$date_new_format_main = $date_new_format_main . ' ' . $format;
								$date_formate        .= ' ' . $format;
							}

							$arflite_form_all_footer_js .= 'setTimeout(function(){ jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").trigger("change");},200);';

							$datetimepicker_locale = ( in_array( $field['locale'], array( 'ms', 'zh-HK' ) ) ) ? '' : $field['locale'];
							if ( $datetimepicker_locale == 'hy' ) {
								$datetimepicker_locale = 'hy-am';
							} elseif ( $datetimepicker_locale == 'no' ) {
								$datetimepicker_locale = 'nb';
							} elseif ( $datetimepicker_locale == 'tu' ) {
								$datetimepicker_locale = 'tr';
							}

							$step                        = ( isset( $field['step'] ) && $field['step'] ) ? $field['step'] : '30';
							$arflite_form_all_footer_js .= 'var date_data_id = jQuery(this).attr("data-id"); jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").datetimepicker({';

							$cl_date_format = 'YYYY-MM-DD';
							if ( $show_time_calendar == 'true' ) {
								$arflite_form_all_footer_js .= 'stepping: ' . $step . ',';
								$cl_date_format             .= ' ' . $format;
							}

							if ( $field['currentdefaultdate'] == 1 ) {
								$arflite_form_all_footer_js .= 'useCurrent:true,';
							} else {
								$arflite_form_all_footer_js .= 'useCurrent:false,';
							}

							$arflite_form_all_footer_js .= 'format: "' . $date_formate . '",
                                locale: "' . $datetimepicker_locale . '",
                                minDate: moment("' . $start_date . ' 00:00 AM", "' . $date_new_format . '"),
                                maxDate: moment("' . $end_date . ' 11:59 PM", "' . $date_new_format . '"),
                                daysOfWeekDisabled: [' . $field['off_days'] . '],
                                keyBinds:"",';
							if ( is_rtl() ) {
								$arflite_form_all_footer_js .= 'widgetPositioning: {
                                        horizontal: "right",
                                        vertical: "auto"
                                    },';
							}
							$arflite_form_all_footer_js .= '});

                            jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").on("dp.change", function(e) {
                                jQuery(this).trigger("change");
                                var act_val = jQuery(this).val();
                                if( "" == act_val ){
                                    jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").removeClass("arf_material_active");
                                    jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '_formatted").val(act_val).trigger("change");
                                } else {
		                            var formated_date = jQuery(this).data("DateTimePicker").viewDate();
		                            var formatted_date = formated_date._d;
		                            var data = moment(formatted_date).format("' . $cl_date_format . '");
                                    jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").addClass("arf_material_active");
		                            jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '_formatted").val(data).trigger("change");
		                        }
                            });

                            jQuery(document).on("click",".arf_submit_btn",function(){
                                jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").trigger("blur");
                            });';

							$arflite_form_all_footer_js .= 'var date_settings={';

							$cl_date_format = 'YYYY-MM-DD';
							if ( $show_time_calendar == 'true' ) {
								$arflite_form_all_footer_js .= 'stepping: ' . $step . ',';
								$cl_date_format             .= ' ' . $format;
							}

							if ( $field['currentdefaultdate'] == 1 ) {
								$arflite_form_all_footer_js .= 'useCurrent:true,';
							} else {
								$arflite_form_all_footer_js .= 'useCurrent:false,';
							}

							$arflite_form_all_footer_js .= 'format: "' . $date_formate . '",
                                locale: "' . $datetimepicker_locale . '",
                                minDate: moment("' . $start_date . ' 00:00 AM", "' . $date_new_format . '"),
                                maxDate: moment("' . $end_date . ' 11:59 PM", "' . $date_new_format . '"),
                                daysOfWeekDisabled: [' . $field['off_days'] . '],
                                keyBinds:"",';
							if ( is_rtl() ) {
								$arflite_form_all_footer_js .= 'widgetPositioning: {
                                        horizontal: "right",
                                        vertical: "auto"
                                    },';
							}
							$arflite_form_all_footer_js .= '};';

							$set_default_date = '';

							if ( isset( $field['set_field_value'] ) ) {
								$set_default_date = $arflitemainhelper->arfliteconvert_date( $field['set_field_value'], 'd/m/Y', $defaultdate_format );

								$field['default_blank'] = 1;
								$set_default_date       = date( $wp_format_date, strtotime( $field['set_field_value'] ) );

							} else {

								if ( isset( $field['currentdefaultdate'] ) && $field['currentdefaultdate'] == 1 ) {

									$set_default_date = date( $wp_format_date, current_time( 'timestamp' ) );

								} elseif ( isset( $field['selectdefaultdate'] ) && $field['selectdefaultdate'] != '' ) {

									$set_default_date = $field['selectdefaultdate'];
								}
							}

							$data_off_days = '';
							if ( ! empty( $off_days ) ) {
								$data_off_days = "data-off-days='" . json_encode( $off_days ) . "'";
							}

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input type="text" data-date-format="' . $date_formate . '" data-start-date="' . $start_date . ' 00:00 AM" data-cl-format="' . $cl_date_format . '" data-date-new-format="' . $date_new_format . '" data-end-date="' . $end_date . ' 11:59 PM" data-default-date="' . $set_default_date . '" id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '" ' . $data_off_days . ' ';

							$return_string .= $arf_input_field_html;

							$return_string .= " data-name='" . $field_name . "'";
							$return_string .= " data-format='" . $date_formate . "'";

							$date_field_options = $this->arflite_html_entity_decode( $field['field_options'] );

							$return_string .= ' data-field-options="' . htmlspecialchars( json_encode( $date_field_options ) ) . '"';

							$is_default_blank = 1;

							$placeholdertext_date = $field['placeholdertext'];

							if ( isset( $placeholdertext_date ) && $placeholdertext_date != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $placeholdertext_date ) . '" ';
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}
							if ( $field['currentdefaultdate'] == 1 ) {
								if ( $datetimepicker_locale != 'en' ) {
									$return_string .= ' data-default-value="' . $arflitefieldhelper->arflite_get_date_with_locale( $set_default_date, $defaultdate_format, $datetimepicker_locale ) . '" value="' . $set_default_date . '"';
								} else {
									$return_string .= ' data-default-value="' . $set_default_date . '" value="' . $set_default_date . '"';
								}
							} elseif ( $set_default_date != '' ) {
								if ( $datetimepicker_locale != 'en' ) {
									$return_string .= ' data-default-value="' . $set_default_date . '" value="' . $set_default_date . '"';
								} else {
									$return_string .= ' data-default-value="' . $set_default_date . '" value="' . $set_default_date . '"';
								}
							}

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}

							$return_string .= ' />';

							$return_string .= '<input type="hidden" name="' . $field_name . '" id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '_formatted"';
							if ( $field['currentdefaultdate'] == 1 ) {
								$return_string .= ' value="' . $set_default_date . '"';
							} elseif ( isset( $field['selectdefaultdate'] ) && $field['selectdefaultdate'] != '' ) {
								$return_string .= ' value="' . $field['selectdefaultdate'] . '"';
							}
							$return_string .= ' />';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;
							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'time':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';

						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="sltstandard_time controls arf_time_main_controls arf_cal_theme_' . $newarr['arfcalthemecss'] . ' ' . $field_tooltip_class . '" ' . $field_tooltip . '>';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {
							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							$arflite_form_all_footer_js .= 'setTimeout(function(){
                                jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").trigger("change");
                                jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").attr("data-value",jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").val());
                            },200);';

							$field['clock']              = ( isset( $field['clock'] ) && $field['clock'] == 24 ) ? 'H:mm' : 'h:mm A';
							$field['step']               = ( isset( $field['step'] ) && $field['step'] ) ? $field['step'] : '30';
							$field['default_hour']       = ( isset( $field['default_hour'] ) && $field['default_hour'] != '' ) ? $field['default_hour'] : '00';
							$field['default_minutes']    = ( isset( $field['default_minutes'] ) && $field['default_minutes'] != '' ) ? $field['default_minutes'] : '00';
							$arflite_form_all_footer_js .= 'jQuery("#field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '").datetimepicker({
                                format: "' . $field['clock'] . '",
                                stepping: ' . $field['step'] . ',';
							if ( is_rtl() ) {
								$arflite_form_all_footer_js .= 'widgetPositioning: {
                                            horizontal: "right",
                                            vertical: "auto"
                                        },';
							}
							$arflite_form_all_footer_js .= '});';

							$return_string .= $prefix;

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$default_value = $field['default_value'];
							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}
							$return_string .= '<input type="text" name="' . $field_name . '" class="arf_timepciker" id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '" value="' . $field['default_value'] . '" ';

							$time_field_options = $this->arflite_html_entity_decode( $field['field_options'] );

							$return_string .= ' data-field-options="' . htmlspecialchars( json_encode( $time_field_options ) ) . '"';

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}
							if ( isset( $field['placeholdertext'] ) && '' != $field['placeholdertext'] ) {
								$return_string .= 'placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}
							$date_field_options = $this->arflite_html_entity_decode( $field['field_options'] );

							$return_string .= ' data-field-options="' . htmlspecialchars( json_encode( $date_field_options ) ) . '"';
							$return_string .= '/>';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;

							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'image':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '"  data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';

						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {
							$return_string .= $prefix;

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input type="url" id="field_' . esc_attr( $field['field_key'] . '_' . $arflite_data_uniq_id ) . '" name="' . esc_attr( $field_name ) . '" ';
							if ( isset( $field['set_field_value'] ) ) {
								$return_string .= ' value="' . esc_attr( $field['set_field_value'] ) . '"';
							}

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							$default_value = $field['default_value'];

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							if ( $default_value != '' ) {
								$return_string .= " value='{$default_value}'";
							}

							$return_string .= $arf_input_field_html;

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= 'data-validation-required-message="' . esc_attr( $field['blank'] ) . '"';
							}
							$return_string .= '/>';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;
							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'hidden':
						$arfaction = ( isset( $_GET ) && isset( $_GET['arfaction'] ) ) ? 'arfaction' : 'action';

						if ( is_admin() && ( ! isset( $_GET[ $arfaction ] ) || sanitize_text_field( $_GET[ $arfaction ] ) != 'new' ) ) {

							global $is_divibuilder ,$is_fusionbuilder;
							if($is_divibuilder == false && $is_fusionbuilder == false)
							{
								$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield arfmainformfield top_container arf_field_' . $field['id'] . ']">';
								$return_string .= '<label class="arf_main_label">' . $field['name'] . ':</label>';
								$return_string .= $field['value'];
								$return_string .= '</div>';
							}
						}

						if ( ! is_admin() && apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {

							if ( isset( $field['set_field_value'] ) ) {
								$return_string .= '<input type="hidden" id="field_' . $field['field_key'] . '" name="' . $field_name . '" value="' . esc_attr( $field['set_field_value'] ) . '" />';
							} else {
								if ( isset( $field['value'] ) && is_array( $field['value'] ) ) {
									foreach ( $field['value'] as $checked ) {
										$checked        = apply_filters( 'arflitehiddenvalue', $checked, $field );
										$return_string .= '<input type="hidden" name="' . $field_name . '[]" value="' . esc_attr( $checked ) . '" />';
									}
								} else {
									$hidden_field_value = isset( $field['default_value'] ) ? $field['default_value'] : '';
									$arf_current_user   = wp_get_current_user();

									if ( preg_match( '/\[ARF_current_user_id\]/', $hidden_field_value ) ) {
										$hidden_field_value = str_replace( '[ARF_current_user_id]', $arf_current_user->ID, $hidden_field_value );
									}
									if ( preg_match( '/\[ARF_current_user_name\]/', $hidden_field_value ) ) {
										$hidden_field_value = str_replace( '[ARF_current_user_name]', $arf_current_user->user_login, $hidden_field_value );
									}
									if ( preg_match( '/\[ARF_current_user_email\]/', $hidden_field_value ) ) {
										$hidden_field_value = str_replace( '[ARF_current_user_email]', $arf_current_user->user_email, $hidden_field_value );
									}
									if ( preg_match( '/\[ARF_current_date\]/', $hidden_field_value ) ) {
										$wp_format_date     = get_option( 'date_format' );
										$arf_current_date   = date( $wp_format_date, current_time( 'timestamp' ) );
										$hidden_field_value = str_replace( '[ARF_current_date]', $arf_current_date, $hidden_field_value );
									}

									$hidden_field_value = apply_filters( 'arflite_replace_default_value_shortcode', $hidden_field_value, $field, $form );

									$return_string .= '<input type="hidden" id="field_' . $field['field_key'] . '" name="' . $field_name . '" value="' . esc_attr( $hidden_field_value ) . '" />';
								}
							}
						}
						break;
					case 'html':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '" data-field-type="html" data-parent_field="' . $parent_field_id . '" >';
						$return_string .= '<div class="arf_htmlfield_control">';

						$html_field_description = $this->arflite_html_entity_decode( $field['description'] );

						$return_string .= do_shortcode( $html_field_description );

						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					case 'email':
					case 'confirm_email':
						$return_string .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '" data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';

						if ( $inputStyle != 'material' ) {
							$return_string .= $arf_main_label;
						}

						$return_string .= '<div class="controls' . $field_tooltip_class . '" ' . $field_tooltip . ' >';

						if ( apply_filters( 'arflite_check_for_draw_outside', false, $field ) ) {

							$return_string = apply_filters( 'arflite_drawthisfieldfromoutside', $return_string, $field, $arflite_data_uniq_id );
						} else {
							$return_string .= $prefix;

							$confirm_email_field = '0';
							if ( $field['type'] == 'email' && isset( $field['confirm_email_arr'][ $field['id'] ] ) && $field['confirm_email_arr'][ $field['id'] ] != '' ) {
								$confirm_email_field = $field['confirm_email_arr'][ $field['id'] ];
							}
							if ( $field['type'] == 'confirm_email' ) {
								$field['value']           = $field['confirm_email_placeholder'];
								$field['placeholdertext'] = $field['confirm_email_placeholder'];
							}

							if ( 'material' == $inputStyle ) {
								$material_standard_cls = '';
								if ( ! empty( $field['enable_arf_prefix'] ) || ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls = 'arf_material_theme_container_with_icons';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_leading_icon ';
								}
								if ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_only_trailing_icon ';
								}
								if ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
									$material_standard_cls .= ' arf_both_icons ';
								}
								$return_string .= '<div class="arf_material_theme_container ' . $material_standard_cls . ' ">';

								$return_string .= $this->arflite_prefix_suffix_for_material( $field );
							}

							$return_string .= '<input type="text" id="field_' . $field['field_key'] . '_' . $arflite_data_uniq_id . '" name="' . $field_name . '" ';

							if ( isset( $field['placeholdertext'] ) && $field['placeholdertext'] != '' ) {
								$return_string .= ' placeholder="' . esc_attr( $field['placeholdertext'] ) . '" ';
							}
							if ( isset( $field['arf_enable_readonly'] ) && $field['arf_enable_readonly'] == 1 ) {
								$return_string .= 'readonly="readonly" ';
							}

							$default_value = $field['default_value'];

							if ( isset( $arf_arr_preset_data ) && count( $arf_arr_preset_data ) > 0 && isset( $arf_arr_preset_data[ $field['id'] ] ) ) {

								$default_value = $arf_arr_preset_data[ $field['id'] ];
							}

							$default_value = apply_filters( 'arflite_replace_default_value_shortcode', $default_value, $field, $form );

							if ( isset( $field['set_field_value'] ) && $field['set_field_value'] != '' ) {
								$default_value = $field['set_field_value'];
							}

							if ( $default_value != '' ) {
								$return_string .= " value='{$default_value}'";
							}

							$return_string .= $arf_input_field_html;

							if ( isset( $field['required'] ) && $field['required'] ) {
								$return_string .= ' data-validation-required-message="' . esc_attr( $field['blank'] ) . '" ';
							}

							$return_string .= ' data-validation-regex-regex="[\p{L}0-9._-]+@[\p{L}0-9.-]+\.[\p{L}]+" data-validation-regex-message="' . esc_attr( $field['invalid'] ) . '" ';

							if ( $field['type'] == 'confirm_email' ) {
								$return_string .= ' data-validation-match-match="item_meta[' . $field['confirm_email_field'] . ']" data-cpass="1" data-validation-match-message="' . esc_attr( $field['invalid'] ) . '"';
							}

							$return_string .= ' />';

							if ( 'material' == $inputStyle ) {
								$return_string         .= '<div class="arf_material_standard">';
									$return_string     .= '<div class="arf_material_theme_prefix"></div>';
									$return_string     .= '<div class="arf_material_theme_notch ' . $arf_material_standard_cls . '">';
										$return_string .= $arf_main_label;
									$return_string     .= '</div>';
									$return_string     .= '<div class="arf_material_theme_suffix"></div>';
								$return_string         .= '</div>';
								$return_string         .= '</div>';
							}

							$return_string .= $suffix;

							$return_string .= $field_standard_tooltip;
						}
						$return_string .= $field_description;
						$return_string .= '</div>';
						$return_string .= '</div>';
						break;
					default:
						if ( apply_filters( 'arflite_wrap_input_field', true, $field['type'] ) ) {
							$arf_material_input_cls = apply_filters( 'arflite_add_material_input_cls', $material_input_cls, $field['type'], $inputStyle );
							$return_string         .= '<div id="arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container" class="arfformfield control-group arfmainformfield ' . $arf_material_input_cls . ' ' . $required_class . ' ' . $class_position . ' ' . $error_class . ' arf_field_' . $field['id'] . '" data-field-type="' . $field['type'] . '" data-parent_field="' . $parent_field_id . '">';
							if ( $inputStyle != 'material' ) {
								$return_string .= $arf_main_label;
							}
							if ( $inputStyle != 'material' ) {
								$field_tooltip = $field_standard_tooltip;
							}

							$return_string = apply_filters( 'arflite_form_fields', $return_string, $form, $field_name, $arflite_data_uniq_id, $field, $field_tooltip, $field_description, $OFData, $inputStyle, $arf_main_label );

							$return_string .= '</div>';
						} else {
							$return_string = apply_filters( 'arflite_form_fields', $return_string, $form, $field_name, $arflite_data_uniq_id, $field, $field_tooltip, $field_description, $OFData, $inputStyle, $arf_main_label );
						}
				}

				global $arflite_column_classes;

				if ( ! isset( $field['inner_class'] ) ) {
					$field['inner_class'] = 'arf_1col';
				}

				if ( $field['type'] == 'confirm_email' ) {
					$field['inner_class'] = $field['confirm_email_inner_classes'];
				}

				if ( $field['inner_class'] == 'arf_1col' || $field['inner_class'] == 'arf_2col' || $field['inner_class'] == 'arf_3col' || $field['inner_class'] == 'arf_4col' || $field['inner_class'] == 'arf_5col' || $field['inner_class'] == 'arf_6col' ) {
						$return_string .= '<div class="arflite_clear_both"></div>';
				} elseif ( $field['inner_class'] == 'arf21colclass' || $field['inner_class'] == 'arf31colclass' || $field['inner_class'] == 'arf41colclass' || $field['inner_class'] == 'arf42colclass' || $field['inner_class'] == 'arf43colclass' || $field['inner_class'] == 'arf51colclass' || $field['inner_class'] == 'arf52colclass' || $field['inner_class'] == 'arf53colclass' || $field['inner_class'] == 'arf54colclass' || $field['inner_class'] == 'arf61colclass' || $field['inner_class'] == 'arf62colclass' || $field['inner_class'] == 'arf63colclass' || $field['inner_class'] == 'arf64colclass' || $field['inner_class'] == 'arf65colclass' ) {
					$return_string .= '<div class="arf_half_middle"></div>';
				} elseif ( $field['inner_class'] == 'arf_23col' ) {
					$return_string .= '<div class="arf_third_middle"></div>';
				}
			} else {
				$field_ext_extract       = explode( '|', $field );
				$field_level_class_blank = '';

				$arf_classes_blank_0  = $field_ext_extract[0];
				$arf_next_div_classes = '';

				if ( $arf_classes_blank_0 == 'arf21colclass' ) {
					 $arf_classes_blank       = 'frm_first_half';
					 $arf_next_div_classes    = 'arf_half_middle';
					 $field_level_class_blank = 'arf_2';
				} elseif ( $arf_classes_blank_0 == 'arf_2col' ) {
					 $arf_classes_blank       = 'frm_last_half';
					 $field_level_class_blank = 'arf_2';

				}
				if ( $arf_classes_blank_0 == 'arf31colclass' ) {
					$arf_classes_blank       = 'frm_first_third';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_3';
				} elseif ( $arf_classes_blank_0 == 'arf_23col' ) {
					$arf_classes_blank       = 'frm_third';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_3';
				} elseif ( $arf_classes_blank_0 == 'arf_3col' ) {
					$arf_classes_blank       = 'frm_last_third';
					$field_level_class_blank = 'arf_3';
				} elseif ( $arf_classes_blank_0 == 'arf41colclass' ) {
					$arf_classes_blank       = 'frm_first_fourth';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_4';
				} elseif ( $arf_classes_blank_0 == 'arf42colclass' || $arf_classes_blank_0 == 'arf43colclass' ) {
					$arf_classes_blank       = 'frm_fourth';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_4';
				} elseif ( $arf_classes_blank_0 == 'arf_4col' ) {
					$arf_classes_blank       = 'frm_last_fourth';
					$field_level_class_blank = 'arf_4';
				} elseif ( $arf_classes_blank_0 == 'arf51colclass' ) {
					$arf_classes_blank       = 'frm_first_fifth';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_5';
				} elseif ( $arf_classes_blank_0 == 'arf52colclass' || $arf_classes_blank_0 == 'arf53colclass' || $arf_classes_blank_0 == 'arf54colclass' ) {
					$arf_classes_blank       = 'frm_fifth';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_5';
				} elseif ( $arf_classes_blank_0 == 'arf_5col' ) {
					$arf_classes_blank       = 'frm_last_fifth';
					$field_level_class_blank = 'arf_5';
				} elseif ( $arf_classes_blank_0 == 'arf61colclass' ) {
					$arf_classes_blank       = 'frm_first_six';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_6';
				} elseif ( $arf_classes_blank_0 == 'arf62colclass' || $arf_classes_blank_0 == 'arf63colclass' || $arf_classes_blank_0 == 'arf64colclass' || $arf_classes_blank_0 == 'arf65colclass' ) {
					$arf_classes_blank       = 'frm_six';
					$arf_next_div_classes    = 'arf_half_middle';
					$field_level_class_blank = 'arf_6';
				} elseif ( $arf_classes_blank_0 == 'arf_6col' ) {
					$arf_classes_blank       = 'frm_last_six';
					$field_level_class_blank = 'arf_6';
				}

				$calculte_width = @$field_resize_width[ $arf_field_front_counter ] - ( isset( $arf_column_field_custom_width[ $field_level_class_blank ] ) ? @$arf_column_field_custom_width[ $field_level_class_blank ] : '0' );

				$return_string                   .= '<div data-field-counter="' . $form_data->id . '_' . $arf_field_front_counter . '_' . $arflite_data_uniq_id . '" class="arfformfield control-group arfmainformfield arfformfield arfemptyfield ' . $arf_classes_blank . '"></div>';
				$arflite_form_all_footer_css     .= '.arflite_main_div_' . $form_data->id . ' .arfemptyfield.arfformfield.' . $arf_classes_blank . "[data-field-counter='" . $form_data->id . '_' . $arf_field_front_counter . '_' . $arflite_data_uniq_id . "']{";
					$arflite_form_all_footer_css .= 'width:' . $calculte_width . '%;';
				$arflite_form_all_footer_css     .= '}';

				if ( $arf_next_div_classes == '' ) {
					$return_string .= '<div class="arflite_clear_both"></div>';
				} else {
					$return_string .= '<div class="' . $arf_next_div_classes . '"></div>';
				}
			}

			do_action( 'arfliteafterdisplayfield', $field );
			$arf_field_front_counter++;
		}

		return $return_string;
	}

	function arflite_field_wise_js_css() {

		global $arformsmain;

		if( $arformsmain->arforms_is_pro_active() ){
			global $arformcontroller;
			$arflite_field_wise_js_css = $arformcontroller->arf_field_wise_js_css();
		} else {	
			$arflite_field_wise_js_css = apply_filters(
				'arflite_field_wise_js_css',
				array(
					'dropdown'    => array(
						'title'  => addslashes( esc_html__( 'Drop Down', 'arforms-form-builder' ) ),
						'handle' => array(
							'js'  => array( 'arformslite_selectpicker' ),
							'css' => array( 'arformslite_selectpicker' ),
						),
					),
					'date_time'   => array(
						'title'  => __( 'Datepicker / Timepicker', 'arforms-form-builder' ),
						'handle' => array(
							'js'  => array( 'bootstrap-moment-with-locales', 'bootstrap-datetimepicker' ),
							'css' => array( 'bootstrap-datetimepicker' ),
						),
					),
					'fontawesome' => array(
						'title'  => __( 'Font Awesome', 'arforms-form-builder' ),
						'handle' => array(
							'css' => array( 'arflite-font-awesome' ),
						),
					),
					'mask_input'  => array(
						'title'  => __( 'Mask Input', 'arforms-form-builder' ),
						'handle' => array(
							'js' => array( 'bootstrap-inputmask', 'jquery-maskedinput', 'intltelinput', 'arformslite_phone_utils' ),
						),
					),
					'tooltip'     => array(
						'title'  => __( 'Tooltip', 'arforms-form-builder' ),
						'handle' => array(
							'js'  => array( 'tipso' ),
							'css' => array( 'tipso' ),
						),
					),
					'material'    => array(
						'title'  => __( 'Material', 'arforms-form-builder' ),
						'handle' => array(
							'css' => array( 'materialize' ),
							'js'  => array( 'materialize' ),
						),
					),
				)
			);
		}
		return $arflite_field_wise_js_css;
	}

	function arflite_get_form_style( $id, $arflite_data_uniq_id = '', $type = '', $position = '', $bgcolor = '', $txtcolor = '', $btn_angle = '', $modal_bgcolor = '', $overlay_value = '', $is_fullscrn = '', $inactive_min = '', $modal_effect = '' ) {

		global $arflite_loaded_form_unique_id_array, $arflitefieldhelper, $arfliterecordhelper, $arfliteform, $arflitemainhelper, $arfliteformcontroller,$arflitesettingcontroller;
		$return_css = '';
		if ( $arflite_data_uniq_id == '' ) {
			$arflite_data_uniq_id = rand( 1, 99999 );

			if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
				$arflite_data_uniq_id = $id;
			}

			if ( $type != '' ) {
				if ( $position != '' ) {
					$arflite_loaded_form_unique_id_array[ $id ]['type'][ $type ][ $position ][] = $arflite_data_uniq_id;
				} else {
					$arflite_loaded_form_unique_id_array[ $id ]['type'][ $type ][] = $arflite_data_uniq_id;
				}
			} else {
				$arflite_loaded_form_unique_id_array[ $id ]['normal'][] = $arflite_data_uniq_id;
			}
		}

		$form = $arfliteform->arflitegetOne( (int) $id );

		if ( ! isset( $form ) ) {
			return;
		}

		$form->options = maybe_unserialize( $form->options );
		$css_data_arr  = $form->form_css;

		$arr = maybe_unserialize( $css_data_arr );

		$newarr      = array();
		$newarr      = $arr;
		$return_css .= '<style type="text/css" id="' . $id . '" data-form-unique-id="' . $arflite_data_uniq_id . '" >';

		$form->form_css = maybe_unserialize( $form->form_css );

		$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();
		
		global $arformsmain;
        $arf_global_css = !empty( get_option('arf_global_css') ) ? stripslashes_deep(get_option('arf_global_css')) : stripslashes_deep($arformsmain->arforms_get_settings('arf_global_css','general_settings'));
        $return_css .= $arf_global_css;

		$fields = $arflitefieldhelper->arflite_get_form_fields_tmp( false, $form->id, false, 0 );

		$return_css                         .= stripslashes_deep( get_option( 'arflite_global_css' ) );
		$form->options['arf_form_other_css'] = $arfliteformcontroller->arflitebr2nl( $form->options['arf_form_other_css'] );
		$return_css                         .= $arflitemainhelper->arflite_esc_textarea( $form->options['arf_form_other_css'] );

		$newFields = array();

		foreach ( $fields as $k => $f ) {

			if ( is_array( $f ) ) {
				foreach ( $f as $n => $i ) {
					$newFields[ $k ][ $n ] = $i;
				}
			} elseif ( is_object( $f ) ) {
				$fi = $this->arfliteObjtoArray( $f );
				foreach ( $fi as $n => $i ) {
					$newFields[ $k ][ $n ] = $i;
				}
			} else {
				$newFields[ $k ] = $f;
			}
		}
		unset( $k );
		unset( $f );
		unset( $n );
		unset( $i );
		unset( $fi );

		$values['fields'] = $this->arfliteObjtoArray( $newFields );

		$custom_css_array_form = array(
			'arf_form_outer_wrapper'   => '.arf_form_outer_wrapper|.arfmodal',
			'arf_form_inner_wrapper'   => '.arf_fieldset|.arfmodal',
			'arf_form_title'           => '.formtitle_style',
			'arf_form_description'     => 'div.formdescription_style',
			'arf_form_element_wrapper' => '.arfformfield',
			'arf_form_element_label'   => 'label.arf_main_label',
			'arf_form_elements'        => '.controls',
			'arf_submit_outer_wrapper' => 'div.arfsubmitbutton',
			'arf_form_submit_button'   => '.arfsubmitbutton button.arf_submit_btn',
			'arf_form_success_message' => '#arf_message_success',
			'arf_form_error_message'   => '.control-group.arf_error .help-block|.control-group.arf_warning .help-block|.control-group.arf_warning .help-inline|.control-group.arf_warning .control-label|.control-group.arf_error .popover|.control-group.arf_warning .popover',
		);

		foreach ( $custom_css_array_form as $custom_css_block_form => $custom_css_classes_form ) {

			if ( isset( $form->options[ $custom_css_block_form ] ) && $form->options[ $custom_css_block_form ] != '' ) {

				$form->options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form->options[ $custom_css_block_form ] );

				if ( $custom_css_block_form == 'arf_form_outer_wrapper' ) {
					$arf_form_outer_wrapper_array = explode( '|', $custom_css_classes_form );

					foreach ( $arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ) {
						if ( $arf_form_outer_wrapper1 == '.arf_form_outer_wrapper' ) {
							$return_css .= '.arflite_main_div_' . $form->id . '.arf_form_outer_wrapper { ' . $form->options[ $custom_css_block_form ] . ' } ';
						}
						if ( $arf_form_outer_wrapper1 == '.arfmodal' ) {
							$return_css .= '#popup-form-' . $form->id . '.arfmodal{ ' . $form->options[ $custom_css_block_form ] . ' } ';
						}
					}
				} elseif ( $custom_css_block_form == 'arf_form_inner_wrapper' ) {
					$arf_form_inner_wrapper_array = explode( '|', $custom_css_classes_form );
					foreach ( $arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ) {
						if ( $arf_form_inner_wrapper1 == '.arf_fieldset' ) {
							$return_css .= '.arflite_main_div_' . $form->id . ' ' . $arf_form_inner_wrapper1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
						}
						if ( $arf_form_inner_wrapper1 == '.arfmodal' ) {
							$return_css .= '.arfmodal .arfmodal-body .arflite_main_div_' . $form->id . ' .arf_fieldset { ' . $form->options[ $custom_css_block_form ] . ' } ';
						}
					}
				} elseif ( $custom_css_block_form == 'arf_form_error_message' ) {
					$arf_form_error_message_array = explode( '|', $custom_css_classes_form );

					foreach ( $arf_form_error_message_array as $arf_form_error_message1 ) {
						$return_css .= '.arflite_main_div_' . $form->id . ' ' . $arf_form_error_message1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
					}
				} else {
					$return_css .= '.arflite_main_div_' . $form->id . ' ' . $custom_css_classes_form . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
				}
			}
		}

		foreach ( $values['fields'] as $field ) {
			foreach ( $field['field_options'] as $f => $fopt ) {
				$field[ $f ] = $fopt;
			}
			$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

			if ( $field['type'] == 'select' ) {
				if ( isset( $field['size'] ) && $field['size'] != 1 ) {
					if ( $newarr['auto_width'] != '1' ) {

						if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {

							$return_css .= '.arflite_main_div_' . $field['form_id'] . ' .select_controll_' . $field['id'] . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . $field['field_width'] . 'px !important;}';
						}
					}
				}
			} elseif ( $field['type'] == 'time' ) {
				if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
					$return_css .= '.arflite_main_div_' . $field['form_id'] . ' .time_controll_' . $field['id'] . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . $field['field_width'] . 'px !important;}';
				}
			}

			if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
				$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .help-block { width: ' . $field['field_width'] . 'px; } ';
			}

			$custom_css_array = array(
				'css_outer_wrapper' => '.arf_form_outer_wrapper',
				'css_label'         => '.css_label',
				'css_input_element' => '.css_input_element',
				'css_description'   => '.arf_field_description',
			);

			if ( in_array( $field['type'], array( 'text', 'email', 'date', 'time', 'number', 'image', 'url', 'phone', 'number' ) ) ) {
				$custom_css_array['css_add_icon'] = '.arf_prefix, .arf_suffix';
			}

			foreach ( $custom_css_array as $custom_css_block => $custom_css_classes ) {
				if ( isset( $field[ $custom_css_block ] ) && $field[ $custom_css_block ] != '' ) {

					$field[ $custom_css_block ] = $arfliteformcontroller->arflitebr2nl( $field[ $custom_css_block ] );

					if ( $custom_css_block == 'css_outer_wrapper' ) {
						$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container { ' . $field[ $custom_css_block ] . ' } ';
					} elseif ( $custom_css_block == 'css_outer_wrapper' ) {
						$return_css .= ' .arflite_main_div_' . $form->id . ' #heading_' . $field['id'] . ' { ' . $field[ $custom_css_block ] . ' } ';
					} elseif ( $custom_css_block == 'css_label' ) {
						$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container label.arf_main_label { ' . $field[ $custom_css_block ] . ' } ';
					} elseif ( $custom_css_block == 'css_label' ) {
						$return_css .= ' .arflite_main_div_' . $form->id . ' #heading_' . $field['id'] . ' h2.arf_sec_heading_field { ' . $field[ $custom_css_block ] . ' } ';
					} elseif ( $custom_css_block == 'css_input_element' ) {

						if ( $field['type'] == 'textarea' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls textarea { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $field['type'] == 'select' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls select { ' . $field[ $custom_css_block ] . ' } ';
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls .arfbtn.dropdown-toggle { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $field['type'] == 'radio' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_radiobutton label { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $field['type'] == 'checkbox' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_checkbox_style label { ' . $field[ $custom_css_block ] . ' } ';
						} else {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input { ' . $field[ $custom_css_block ] . ' } ';
							if ( $field['type'] == 'email' ) {
								$return_css .= '.arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container + .confirm_email_container .controls input {' . $field[ $custom_css_block ] . '}';
								$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container + .confirm_email_container .arf_prefix_suffix_wrapper{ ' . $field[ $custom_css_block ] . ' }';
							}
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_prefix_suffix_wrapper { ' . $field[ $custom_css_block ] . ' } ';
						}
					} elseif ( $custom_css_block == 'css_description' ) {
						$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_field_description { ' . $field[ $custom_css_block ] . ' } ';
					} elseif ( $custom_css_block == 'css_add_icon' ) {
						$return_css .= '.arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_prefix, .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_suffix { ' . $field[ $custom_css_block ] . ' } ';
						if ( $field['type'] == 'email' ) {
							$return_css .= '.arflite_main_div_' . $form->id . ' .arf_confirm_email_field_' . $field['id'] . ' .arf_prefix, .arflite_main_div_' . $form->id . ' .arf_confirm_email_field_' . $field['id'] . ' .arf_suffix {' . $field[ $custom_css_block ] . ' } ';
						}
					}

					do_action( 'arflite_add_css_from_outside', $field, $custom_css_block, $arflite_data_uniq_id );
				}
			}
		}
		$return_css .= '</style>';
		return $return_css;
	}

	function arflite_prefix_suffix_for_material( $field ) {
		$return_string        = '';
		$has_phone_with_utils = false;
		if ( isset( $field['phonetype'] ) ) {
			if ( $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
				$has_phone_with_utils = true;
			}
		}
		if ( ! empty( $field['enable_arf_prefix'] ) && $has_phone_with_utils == false ) {
			$return_string .= '<i class="arf_leading_icon ' . $field['arf_prefix_icon'] . '"></i>';
		}
		if ( ! empty( $field['enable_arf_suffix'] ) ) {
			$return_string .= '<i class="arf_trailing_icon ' . $field['arf_suffix_icon'] . '"></i>';
		}

		return $return_string;
	}

	function arflite_get_form_style_for_preview( $form, $id, $fields, $arflite_data_uniq_id = '' ) {

		global $arflite_loaded_form_unique_id_array, $arflitefieldhelper, $arfliterecordhelper, $arfliteform, $arflitemainhelper, $arfliteformcontroller;
		$return_css = '';
		$type       = '';
		if ( $arflite_data_uniq_id == '' ) {
			$arflite_data_uniq_id = rand( 1, 99999 );
			if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
				$arflite_data_uniq_id = $id;
			}

			if ( $type != '' ) {
				if ( $position != '' ) {
					$arflite_loaded_form_unique_id_array[ $id ]['type'][ $type ][ $position ][] = $arflite_data_uniq_id;
				} else {
					$arflite_loaded_form_unique_id_array[ $id ]['type'][ $type ][] = $arflite_data_uniq_id;
				}
			} else {
				$arflite_loaded_form_unique_id_array[ $id ]['normal'][] = $arflite_data_uniq_id;
			}
		}

		$css_data_arr = $form->form_css;

		$arr = maybe_unserialize( $css_data_arr );

		$newarr = array();
		$newarr = $arr;

		$return_css .= '<style type="text/css" id="arf_form_' . $id . '" data-form-unique-id="' . $arflite_data_uniq_id . '" >';

			$form->form_css = maybe_unserialize( $form->form_css );

			$loaded_field = isset( $form->options['arf_loaded_field'] ) ? $form->options['arf_loaded_field'] : array();

			$return_css                         .= stripslashes_deep( get_option( 'arflite_global_css' ) );
			$form->options['arf_form_other_css'] = $arfliteformcontroller->arflitebr2nl( $form->options['arf_form_other_css'] );
			$return_css                         .= stripslashes( $arflitemainhelper->arflite_esc_textarea( $form->options['arf_form_other_css'] ) );

			$custom_css_array_form = array(
				'arf_form_outer_wrapper'   => '.arf_form_outer_wrapper|.arfmodal',
				'arf_form_inner_wrapper'   => '.arf_fieldset|.arfmodal',
				'arf_form_title'           => '.formtitle_style',
				'arf_form_description'     => 'div.formdescription_style',
				'arf_form_element_wrapper' => '.arfformfield',
				'arf_form_element_label'   => 'label.arf_main_label',
				'arf_form_elements'        => '.controls',
				'arf_submit_outer_wrapper' => 'div.arfsubmitbutton',
				'arf_form_submit_button'   => '.arfsubmitbutton button.arf_submit_btn',
				'arf_form_success_message' => '#arf_message_success',
				'arf_form_error_message'   => '.control-group.arf_error .help-block|.control-group.arf_warning .help-block|.control-group.arf_warning .help-inline|.control-group.arf_warning .control-label|.control-group.arf_error .popover|.control-group.arf_warning .popover',
			);

			foreach ( $custom_css_array_form as $custom_css_block_form => $custom_css_classes_form ) {

				if ( isset( $form->options[ $custom_css_block_form ] ) && $form->options[ $custom_css_block_form ] != '' ) {

					$form->options[ $custom_css_block_form ] = $arfliteformcontroller->arflitebr2nl( $form->options[ $custom_css_block_form ] );

					if ( $custom_css_block_form == 'arf_form_outer_wrapper' ) {
						$arf_form_outer_wrapper_array = explode( '|', $custom_css_classes_form );

						foreach ( $arf_form_outer_wrapper_array as $arf_form_outer_wrapper1 ) {
							if ( $arf_form_outer_wrapper1 == '.arf_form_outer_wrapper' ) {
								$return_css .= '.arflite_main_div_' . $form->id . '.arf_form_outer_wrapper { ' . $form->options[ $custom_css_block_form ] . ' } ';
							}
							if ( $arf_form_outer_wrapper1 == '.arfmodal' ) {
								$return_css .= '#popup-form-' . $form->id . '.arfmodal{ ' . $form->options[ $custom_css_block_form ] . ' } ';
							}
						}
					} elseif ( $custom_css_block_form == 'arf_form_inner_wrapper' ) {
						$arf_form_inner_wrapper_array = explode( '|', $custom_css_classes_form );
						foreach ( $arf_form_inner_wrapper_array as $arf_form_inner_wrapper1 ) {
							if ( $arf_form_inner_wrapper1 == '.arf_fieldset' ) {
								$return_css .= '.arflite_main_div_' . $form->id . ' ' . $arf_form_inner_wrapper1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
							}
							if ( $arf_form_inner_wrapper1 == '.arfmodal' ) {
								$return_css .= '.arfmodal .arfmodal-body .arflite_main_div_' . $form->id . ' .arf_fieldset { ' . $form->options[ $custom_css_block_form ] . ' } ';
							}
						}
					} elseif ( $custom_css_block_form == 'arf_form_error_message' ) {
						$arf_form_error_message_array = explode( '|', $custom_css_classes_form );

						foreach ( $arf_form_error_message_array as $arf_form_error_message1 ) {
							$return_css .= '.arflite_main_div_' . $form->id . ' ' . $arf_form_error_message1 . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
						}
					} else {
						$return_css .= '.arflite_main_div_' . $form->id . ' ' . $custom_css_classes_form . ' { ' . $form->options[ $custom_css_block_form ] . ' } ';
					}
				}
			}

			foreach ( $fields as $field ) {
				$field       = $this->arfliteObjtoArray( $field );
				$field['id'] = $arflitefieldhelper->arfliteget_actual_id( $field['id'] );

				if ( $field['type'] == 'select' ) {
					if ( isset( $field['size'] ) && $field['size'] != 1 ) {
						if ( $newarr['auto_width'] != '1' ) {

							if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {

								$return_css .= '.arflite_main_div_' . $field['form_id'] . ' .select_controll_' . $field['id'] . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . $field['field_width'] . 'px !important;}';
							}
						}
					}
				} elseif ( $field['type'] == 'time' ) {
					if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
						$return_css .= '.arflite_main_div_' . $field['form_id'] . ' .time_controll_' . $field['id'] . ':not([class*="span"]):not([class*="col-"]):not([class*="form-control"]){width:' . $field['field_width'] . 'px !important;}';
					}
				}

				if ( isset( $field['field_width'] ) && $field['field_width'] != '' ) {
					$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .help-block { width: ' . $field['field_width'] . 'px; } ';
				}

				$custom_css_array = array(
					'css_outer_wrapper' => '.arf_form_outer_wrapper',
					'css_label'         => '.css_label',
					'css_input_element' => '.css_input_element',
					'css_description'   => '.arf_field_description',
				);

				if ( in_array( $field['type'], array( 'text', 'email', 'date', 'time', 'number', 'image', 'url', 'phone', 'number' ) ) ) {
					$custom_css_array['css_add_icon'] = '.arf_prefix, .arf_suffix';
				}

				foreach ( $custom_css_array as $custom_css_block => $custom_css_classes ) {
					if ( isset( $field[ $custom_css_block ] ) && $field[ $custom_css_block ] != '' ) {

						$field[ $custom_css_block ] = $arfliteformcontroller->arflitebr2nl( $field[ $custom_css_block ] );

						if ( $custom_css_block == 'css_outer_wrapper' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $custom_css_block == 'css_label' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container label.arf_main_label { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $custom_css_block == 'css_input_element' ) {

							if ( $field['type'] == 'textarea' ) {
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls textarea { ' . $field[ $custom_css_block ] . ' } ';
							} elseif ( $field['type'] == 'select' ) {
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls select { ' . $field[ $custom_css_block ] . ' } ';
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls .arfbtn.dropdown-toggle { ' . $field[ $custom_css_block ] . ' } ';
							} elseif ( $field['type'] == 'radio' ) {
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_radiobutton label { ' . $field[ $custom_css_block ] . ' } ';
							} elseif ( $field['type'] == 'checkbox' ) {
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_checkbox_style label { ' . $field[ $custom_css_block ] . ' } ';
							} else {
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .controls input { ' . $field[ $custom_css_block ] . ' } ';
								if ( $field['type'] == 'email' ) {
									$return_css .= '.arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container + .confirm_email_container .controls input {' . $field[ $custom_css_block ] . '}';
									$return_css .= ' .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container + .confirm_email_container .arf_prefix_suffix_wrapper{ ' . $field[ $custom_css_block ] . ' }';
								}
								$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_prefix_suffix_wrapper { ' . $field[ $custom_css_block ] . ' } ';
							}
						} elseif ( $custom_css_block == 'css_description' ) {
							$return_css .= ' .arflite_main_div_' . $form->id . '  #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_field_description { ' . $field[ $custom_css_block ] . ' } ';
						} elseif ( $custom_css_block == 'css_add_icon' ) {
							$return_css .= '.arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_prefix,
                                .arflite_main_div_' . $form->id . ' #arf_field_' . $field['id'] . '_' . $arflite_data_uniq_id . '_container .arf_suffix { ' . $field[ $custom_css_block ] . ' } ';
							if ( $field['type'] == 'email' ) {
								$return_css .= '.arflite_main_div_' . $form->id . ' .arf_confirm_email_field_' . $field['id'] . ' .arf_prefix,
                                        .arflite_main_div_' . $form->id . ' .arf_confirm_email_field_' . $field['id'] . ' .arf_suffix {' . $field[ $custom_css_block ] . ' } ';
							}
						}

						do_action( 'arflite_add_css_from_outside', $field, $custom_css_block, $arflite_data_uniq_id );
					}
				}
			}

			$return_css .= '</style>';

			return $return_css;
	}

	function arflite_prefix_suffix( $prefix_suffix, $field ) {
		$return_string = '';

		$has_phone_with_utils = false;
		$phone_with_utils_cls = '';
		if ( isset( $field['phonetype'] ) ) {
			if ( $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
				$has_phone_with_utils = true;
				$phone_with_utils_cls = 'arf_phone_with_flag';
			}
		}

		$wrapper_cls = '';
		if ( ! empty( $field['enable_arf_prefix'] ) && empty( $field['enable_arf_suffix'] ) ) {
			$wrapper_cls = ' arf_prefix_only ';
		} elseif ( empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
			$wrapper_cls = ' arf_suffix_only ';
		} elseif ( ! empty( $field['enable_arf_prefix'] ) && ! empty( $field['enable_arf_suffix'] ) ) {
			$wrapper_cls = ' arf_both_pre_suffix ';
			if ( $field['type'] == 'phone' && $field['phonetype'] == 1 ) {
					$wrapper_cls = ' arf_suffix_only';
			}
		}

		if ( $prefix_suffix == 'prefix' ) {
			if ( ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] == 1 ) || ( isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] == 1 ) ) {
				$return_string .= '<div class="arf_prefix_suffix_wrapper ' . $wrapper_cls . ' ' . $phone_with_utils_cls . '">';
				if ( $field['enable_arf_prefix'] == 1 ) {
					if ( $has_phone_with_utils == false ) {
						$return_string .= '<span id="arf_prefix_' . $field['field_key'] . '" class="arf_prefix" onclick="arfliteFocusInputField(this,\'' . $field['field_key'] . '\');"><i class="' . $field['arf_prefix_icon'] . '"></i></span>';
					}
				}
			}
		} else {
			if ( ( isset( $field['enable_arf_prefix'] ) && $field['enable_arf_prefix'] == 1 ) || ( isset( $field['enable_arf_suffix'] ) && $field['enable_arf_suffix'] == 1 ) ) {
				if ( $field['enable_arf_suffix'] == 1 ) {
					$return_string .= '<span id="arf_suffix_' . $field['field_key'] . '" class="arf_suffix" onclick="arfliteFocusInputField(this,\'' . $field['field_key'] . '\');"><i class="' . $field['arf_suffix_icon'] . '"></i></span>';
				}
				$return_string .= '</div>';
			}
		}
		return $return_string;
	}

	function arfliteObjtoArray( $obj ) {
		if ( is_object( $obj ) ) {
			$obj = get_object_vars( $obj );
		}
		if ( is_array( $obj ) ) {
			return array_map( array( $this, __FUNCTION__ ), $obj );
		} else {
			return $obj;
		}
	}

	function arfliteArraytoObj( $array ) {
		if ( is_array( $array ) ) {
			$array = json_decode( json_encode( $array ) );
		}
		return $array;
	}

	function arflite_csv_form_function() {

		$form_id  = isset( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';
		$data_url = site_url() . '/index.php?plugin=ARFormslite&controller=entries&form=' . $form_id . '&arfaction=csv';

		echo json_encode(
			array(
				'url_data' => $data_url,
				'error'    => false,
				'message'  =>
					__(
						'CSV generated successfully.',
						'arforms-form-builder'
					),
			)
		);
		die();
	}

	function get_arflite_default_fonts() {
		return array(
			'Arial'               => 'Arial',
			'Helvetica'           => 'Helvetica',
			'sans-serif'          => 'sans-serif',
			'Lucida Grande'       => 'Lucida Grande',
			'Lucida Sans Unicode' => 'Lucida Sans Unicode',
			'Tahoma'              => 'Tahoma',
			'Times New Roman'     => 'Times New Roman',
			'Courier New'         => 'Courier New',
			'Verdana'             => 'Verdana',
			'Geneva'              => 'Geneva',
			'Courier'             => 'Courier',
			'Monospace'           => 'Monospace',
			'Times'               => 'Times',
		);
	}

	function arflite_delete_form_function() {
		global $wpdb, $ARFLiteMdlDb,$arfliteform,$arflitemaincontroller, $tbl_arf_forms;

		$arflite_validate_request = $arflitemaincontroller->arflite_check_user_cap( 'arfdeleteforms', true );

		if ( 'success' != $arflite_validate_request ) {
			$validate_data = json_decode( $arflite_validate_request, true );

			echo json_encode(
				array(
					'error'       => true,
					'message'     => addslashes( $validate_data[0] ),
					'total_forms' => 0,
				)
			);
			die;
		}

		$total_forms = 0;
		$form_id     = isset( $_REQUEST['form_id'] ) ? intval( $_REQUEST['form_id'] ) : '';
		if ( $form_id == '' ) {
			echo json_encode(
				array(
					'error'       => true,
					'message'     => __( 'Please select valid form', 'arforms-form-builder' ),
					'total_forms' => $total_forms,
				)
			);
			die();
		}

		$result = $arfliteform->arflitedestroy( $form_id );
		if ( $result ) {
			echo json_encode(
				array(
					'error'       => true,
					'message'     => __( 'Please select valid form', 'arforms-form-builder' ),
					'total_forms' => $total_forms,
				)
			);
		} else {
			$where       = "WHERE 1=1 AND is_template = %d AND (status is NULL OR status = '' OR status = 'published') ";
			$totalRecord = $wpdb->get_results( $wpdb->prepare( 'SELECT COUNT(*) as total_forms FROM ' . $tbl_arf_forms . ' ' . $where . ' ', 0 ) ); //phpcs:ignore
			$total_forms = $totalRecord[0]->total_forms;
			echo json_encode(
				array(
					'error'       => false,
					'message'     => __( 'Record is deleted successfully.', 'arforms-form-builder' ),
					'total_forms' => $total_forms,
				)
			);
		}

		die();
	}

	function arflite_change_input_style() {

		global $arflitemaincontroller, $arflite_intval_keys;

		$arflite_validate_request = $arflitemaincontroller->arflite_check_user_cap( 'arfeditforms', true );

		if ( 'success' != $arflite_validate_request ) {
			$validate_data   = json_decode( $arflite_validate_request, true );
			$return['error'] = true;
			echo json_encode( $return );
			die();
		}

		$form_id      = $id = isset( $_POST['form_id'] ) ? intval( $_POST['form_id'] ) : ''; //phpcs:ignore
		$style        = isset( $_POST['style'] ) ? sanitize_text_field( $_POST['style'] ) : 'material'; //phpcs:ignore
		$styling_opts = isset( $_POST['styling_opts'] ) ? json_decode( stripslashes_deep( sanitize_text_field( $_POST['styling_opts'] ) ), true ) : array(); //phpcs:ignore

		$field_order        = isset( $_POST['field_order'] ) ? json_decode( stripslashes_deep( sanitize_text_field( $_POST['field_order'] ) ), true ) : ''; //phpcs:ignore
		$field_resize_width = isset( $_POST['field_resize_width'] ) ? json_decode( stripslashes_deep( sanitize_text_field( $_POST['field_resize_width'] ) ), true ) : ''; //phpcs:ignore

		foreach ( $field_order as $field_id => $order ) {
			$field_order[ $field_id ] = sanitize_text_field( $order );
		}

		foreach ( $field_resize_width as $field_id => $order ) {
			$field_resize_width[ $field_id ] = sanitize_text_field( $order );
		}

		if ( $form_id == '' || $form_id < 100 ) {
			$return['error'] = true;
			echo json_encode( $return );
			die();
		}
		global $wpdb, $ARFLiteMdlDb, $tbl_arf_fields;

		$unsaved_fields = ( isset( $_REQUEST['extra_fields'] ) && $_REQUEST['extra_fields'] != '' ) ? json_decode( stripslashes_deep( sanitize_text_field($_REQUEST['extra_fields']) ), true ) : array();

		$arf_fields = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM `' . $tbl_arf_fields . '` WHERE `form_id` = %d', $form_id ), ARRAY_A ); //phpcs:ignore
		asort( $field_order );
		$arf_sorted_fields = array();
		foreach ( $field_order as $field_id => $order ) {
			if ( is_int( $field_id ) ) {
				foreach ( $arf_fields as $field ) {
					if ( $field_id == $field['id'] && ! array_key_exists( $field_id, $unsaved_fields ) ) {
						$arf_sorted_fields[] = $field;
					}
				}
			} else {
				$arf_sorted_fields[] = $field_id;
			}
		}

		if ( isset( $arf_sorted_fields ) ) {
			$arf_fields = $arf_sorted_fields;
		}
		$field_data = file_get_contents( ARFLITE_VIEWS_PATH . '/arflite_editor_data.json' );

		$field_data_obj  = json_decode( $field_data );
		$return['error'] = false;
		$content         = '';
		if ( ! empty( $unsaved_fields ) ) {
			$arf_sorted_unsave_fields = array();
			foreach ( $field_order as $field_id => $order ) {
				foreach ( $unsaved_fields as $fid => $field_data ) {
					if ( $field_id == $fid ) {
						$arf_sorted_unsave_fields[ $fid ] = $field_data;
					}
				}
			}

			$unsaved_fields = $arf_sorted_unsave_fields;

			$temp_fields = array();
			foreach ( $unsaved_fields as $key => $value ) {
				$opts = json_decode( $value, true );
				if ( json_last_error() != JSON_ERROR_NONE ) {
					$opts = maybe_unserialize( $value );
				}
				foreach ( $opts as $k => $val ) {
					if ( $k == 'key' ) {
						$temp_fields[ $key ]['field_key'] = $val;
					} elseif ( $k == 'options' || $k == 'default_value' ) {
						$temp_fields[ $key ][ $k ] = ( $val != '' ) ? json_encode( $val ) : '';
					} else {
						$temp_fields[ $key ][ $k ] = $val;
					}
				}
				$temp_fields[ $key ]['field_options'] = $value;
				$temp_fields[ $key ]['id']            = $key;
			}
			$arf_fields = array_merge( $arf_fields, $temp_fields );
		}

		$arf_sorted_fields_new = array();
		foreach ( $field_order as $field_id => $order ) {
			if ( is_int( $field_id ) ) {
				foreach ( $arf_fields as $field ) {
					if ( isset( $field['id'] ) && ( $field_id == $field['id'] ) ) {
						$arf_sorted_fields_new[] = $field;
					}
				}
			} else {
				$arf_sorted_fields_new[] = $field_id;
			}
		}
		$arf_fields = $arf_sorted_fields_new;

		$allowed_html = arflite_retrieve_attrs_for_wp_kses();

		foreach ( $arf_fields as $fk => $fval ) {

			if ( is_array( $fval ) ) {
				foreach ( $fval as $fki => $fvali ) {
					if ( 'name' == $fki || 'description' == $fki || 'arf_tooltip' == $fki || 'tooltip_text' == $fki ) {
						$arf_fields[ $fk ][ $fki ] = wp_kses( $fvali, $allowed_html );
					} elseif ( 'field_options' == $fki ) {
						$fvali_arr = json_decode( $fvali, true );
						foreach ( $fvali_arr as $fopt_k => $fopt_v ) {
							if ( 'name' == $fopt_k || 'description' == $fopt_k || 'arf_tooltip' == $fopt_k || 'tooltip_text' == $fopt_k ) {
								$fvali_arr[ $fopt_k ] = wp_kses( $fopt_v, $allowed_html );
							} elseif ( in_array( $fopt_k, $arflite_intval_keys ) ) {
								$fvali_arr[ $fopt_k ] = intval( $fopt_v );
							} elseif ( $fopt_k == 'phtypes' ) {
								foreach ( $fopt_v as $fipot_k => $fipt_v ) {
									$fvali_arr[ $fopt_k ][ $fipot_k ] = sanitize_text_field( $fipt_v );
								}
							} else {
								if ( is_array( $fopt_v ) ) {
									$fvali_arr[ $fopt_k ] = array_map( 'sanitize_text_field', $fopt_v ); // sanitize_text_field( $fopt_v );
								} else {
									$fvali_arr[ $fopt_k ] = sanitize_text_field( $fopt_v );
								}
							}
						}
						$arf_fields[ $fk ][ $fki ] = json_encode( $fvali_arr );
					} elseif ( in_array( $fki, $arflite_intval_keys ) ) {
						$arf_fields[ $fk ][ $fki ] = intval( $fvali );
					} elseif ( $fki == 'phtypes' ) {
						foreach ( $fvali as $fipot_k => $fipt_v ) {
							$fvali_arr[ $fki ][ $fipot_k ] = sanitize_text_field( $fipt_v );
						}
					} else {
						if ( is_array( $fvali ) ) {

							$fvali_arr[ $fk ] = array_map( 'sanitize_text_field', $fvali );
						} else {
							$arf_fields[ $fk ][ $fki ] = sanitize_text_field( $fvali );
						}
					}
				}
			}
		}

		$class_array       = array();
		$arf_field_counter = 1;
		$index_arf_fields  = 0;
		if ( is_array( $styling_opts ) && ! empty( $styling_opts ) && count( $styling_opts ) > 0 ) {
			$form_css                                        = array();
			$form_css['arfmainformwidth']                    = isset( $styling_opts['arffw'] ) ? intval( $styling_opts['arffw'] ) : '';
			$form_css['arfmainformwidth_tablet'] = isset($styling_opts['arffw_tablet']) ? $styling_opts['arffw_tablet'] : '';
            $form_css['arfmainformwidth_mobile'] = isset($styling_opts['arffw_mobile']) ? $styling_opts['arffw_mobile'] : '';
			$form_css['form_width_unit']                     = isset( $styling_opts['arffu'] ) ? sanitize_text_field( $styling_opts['arffu'] ) : '';
			$form_css['text_direction']                      = isset( $styling_opts['arftds'] ) ? intval( $styling_opts['arftds'] ) : '';
			$form_css['form_align']                          = isset( $styling_opts['arffa'] ) ? sanitize_text_field( $styling_opts['arffa'] ) : '';
			$form_css['arfmainfieldsetpadding']              = isset( $styling_opts['arfmfsp'] ) ? sanitize_text_field( $styling_opts['arfmfsp'] ) : '';
			$form_css['arfmainfieldsetpadding_tablet']              = isset( $styling_opts['arfmfsp_tablet'] ) ? sanitize_text_field( $styling_opts['arfmfsp_tablet'] ) : '';
			$form_css['arfmainfieldsetpadding_mobile']              = isset( $styling_opts['arfmfsp_mobile'] ) ? sanitize_text_field( $styling_opts['arfmfsp_mobile'] ) : '';
			$form_css['form_border_shadow']                  = isset( $styling_opts['arffbs'] ) ? sanitize_text_field( $styling_opts['arffbs'] ) : '';
			$form_css['fieldset']                            = isset( $styling_opts['arfmfis'] ) ? intval( $styling_opts['arfmfis'] ) : '';
			$form_css['arfmainfieldsetradius']               = isset( $styling_opts['arfmfsr'] ) ? intval( $styling_opts['arfmfsr'] ) : '';
			$form_css['arfmainfieldsetcolor']                = isset( $styling_opts['arfmfsc'] ) ? sanitize_text_field( $styling_opts['arfmfsc'] ) : '';
			$form_css['arfmainformbordershadowcolorsetting'] = isset( $styling_opts['arffboss'] ) ? sanitize_text_field( $styling_opts['arffboss'] ) : '';
			$form_css['arfmainformtitlecolorsetting']        = isset( $styling_opts['arfftc'] ) ? sanitize_text_field( $styling_opts['arfftc'] ) : '';
			$form_css['check_weight_form_title']             = isset( $styling_opts['arfftws'] ) ? sanitize_text_field( $styling_opts['arfftws'] ) : '';
			$form_css['form_title_font_size']                = isset( $styling_opts['arfftfss'] ) ? intval( $styling_opts['arfftfss'] ) : '';
			$form_css['arfmainformtitlepaddingsetting']      = isset( $styling_opts['arfftps'] ) ? sanitize_text_field( $styling_opts['arfftps'] ) : '';
			$form_css['arfmainformbgcolorsetting']           = isset( $styling_opts['arffbcs'] ) ? sanitize_text_field( $styling_opts['arffbcs'] ) : '';
			$form_css['font']                                = isset( $styling_opts['arfmfs'] ) ? sanitize_text_field( $styling_opts['arfmfs'] ) : '';
			$form_css['label_color']                         = isset( $styling_opts['arflcs'] ) ? sanitize_text_field( $styling_opts['arflcs'] ) : '';
			$form_css['weight']                              = isset( $styling_opts['arfmfws'] ) ? sanitize_text_field( $styling_opts['arfmfws'] ) : '';
			$form_css['font_size']                           = isset( $styling_opts['arffss'] ) ? intval( $styling_opts['arffss'] ) : '';
			$form_css['align']                               = isset( $styling_opts['arffrma'] ) ? sanitize_text_field( $styling_opts['arffrma'] ) : '';
			$form_css['position']                            = isset( $styling_opts['arfmps'] ) ? sanitize_text_field( $styling_opts['arfmps'] ) : '';
			$form_css['width']                               = isset( $styling_opts['arfmws'] ) ? intval( $styling_opts['arfmws'] ) : '';
			$form_css['width_unit']                          = isset( $styling_opts['arfmwu'] ) ? sanitize_text_field( $styling_opts['arfmwu'] ) : '';
			$form_css['arfdescfontsizesetting']              = isset( $styling_opts['arfdfss'] ) ? intval( $styling_opts['arfdfss'] ) : '';
			$form_css['arfdescalighsetting']                 = isset( $styling_opts['arfdas'] ) ? sanitize_text_field( $styling_opts['arfdas'] ) : '';
			$form_css['hide_labels']                         = isset( $styling_opts['arfhl'] ) ? intval( $styling_opts['arfhl'] ) : '';
			$form_css['check_font']                          = isset( $styling_opts['arfcbfs'] ) ? sanitize_text_field( $styling_opts['arfcbfs'] ) : '';
			$form_css['check_weight']                        = isset( $styling_opts['arfcbws'] ) ? sanitize_text_field( $styling_opts['arfcbws'] ) : '';
			$form_css['field_font_size']                     = isset( $styling_opts['arfffss'] ) ? intval( $styling_opts['arfffss'] ) : '';
			$form_css['text_color']                          = isset( $styling_opts['arftcs'] ) ? sanitize_text_field( $styling_opts['arftcs'] ) : '';
			$form_css['border_radius']                       = isset( $styling_opts['arfmbs'] ) ? intval( $styling_opts['arfmbs'] ) : '';
			$form_css['border_radius_tablet']                = isset($styling_opts['arfmbs_tablet']) ? $styling_opts['arfmbs_tablet'] : '';
            $form_css['border_radius_mobile']                = isset($styling_opts['arfmbs_mobile']) ? $styling_opts['arfmbs_mobile'] : '';
			$form_css['border_color']                        = isset( $styling_opts['arffmboc'] ) ? sanitize_text_field( $styling_opts['arffmboc'] ) : '';
			$form_css['arffieldborderwidthsetting']          = isset( $styling_opts['arffbws'] ) ? intval( $styling_opts['arffbws'] ) : '';
			$form_css['arffieldborderstylesetting']          = isset( $styling_opts['arffbss'] ) ? sanitize_text_field( $styling_opts['arffbss'] ) : '';

			$form_css['arf_bg_position_x'] = ( isset( $styling_opts['arf_bg_position_x'] ) && $styling_opts['arf_bg_position_x'] != '' ) ? sanitize_text_field( $styling_opts['arf_bg_position_x'] ) : 'left';
			$form_css['arf_bg_position_y'] = ( isset( $styling_opts['arf_bg_position_y'] ) && $styling_opts['arf_bg_position_y'] != '' ) ? sanitize_text_field( $styling_opts['arf_bg_position_y'] ) : 'top';

			$form_css['arf_bg_position_input_x'] = ( isset( $styling_opts['arf_bg_position_input_x'] ) && $styling_opts['arf_bg_position_input_x'] != '' ) ? intval( $styling_opts['arf_bg_position_input_x'] ) : '';

			$arf_bg_position_input_y = ( isset( $_REQUEST['arf_bg_position_input_y'] ) && $_REQUEST['arf_bg_position_input_y'] != '' ) ? intval( $_REQUEST['arf_bg_position_input_y'] ) : '';

			$form_css['arf_bg_position_input_y'] = ( isset( $styling_opts['arf_bg_position_input_y'] ) && $styling_opts['arf_bg_position_input_y'] != '' ) ? intval( $arf_bg_position_input_y ) : '';

			if ( isset( $styling_opts['arffiu'] ) && $styling_opts['arffiu'] == '%' && isset( $styling_opts['arfmfiws'] ) && $styling_opts['arfmfiws'] > '100' ) {
				$form_css['field_width'] = '100';
			} else {
				$form_css['field_width'] = isset( $styling_opts['arfmfiws'] ) ? intval( $styling_opts['arfmfiws'] ) : '';
			}
			$form_css['field_width_unit']                   = isset( $styling_opts['arffiu'] ) ? sanitize_text_field( $styling_opts['arffiu'] ) : '';

			if ( isset( $styling_opts['arffiu_tablet'] ) && $styling_opts['arffiu_tablet'] == '%' && isset( $styling_opts['arfmfiws_tablet'] ) && $styling_opts['arfmfiws_tablet'] > '100' ) {
				$form_css['field_width_tablet'] = '100';
			} else {
				$form_css['field_width_tablet'] = isset( $styling_opts['arfmfiws_tablet'] ) ? intval( $styling_opts['arfmfiws_tablet'] ) : '';
			}
			$form_css['field_width_unit_tablet']                   = isset( $styling_opts['arffiu_tablet'] ) ? sanitize_text_field( $styling_opts['arffiu_tablet'] ) : '';

			if ( isset( $styling_opts['arffiu_mobile'] ) && $styling_opts['arffiu_mobile'] == '%' && isset( $styling_opts['arfmfiws_mobile'] ) && $styling_opts['arfmfiws_mobile'] > '100' ) {
				$form_css['field_width_mobile'] = '100';
			} else {
				$form_css['field_width_mobile'] = isset( $styling_opts['arfmfiws_mobile'] ) ? intval( $styling_opts['arfmfiws_mobile'] ) : '';
			}
			$form_css['field_width_unit_mobile']                   = isset( $styling_opts['arffiu_mobile'] ) ? sanitize_text_field( $styling_opts['arffiu_mobile'] ) : '';

			$form_css['arffieldmarginssetting']             = isset( $styling_opts['arffms'] ) ? intval( $styling_opts['arffms'] ) : '';
			$form_css['arffieldinnermarginssetting']        = isset( $styling_opts['arffims'] ) ? sanitize_text_field( $styling_opts['arffims'] ) : '';
			$form_css['bg_color']                           = isset( $styling_opts['arffmbc'] ) ? sanitize_text_field( $styling_opts['arffmbc'] ) : '';
			$form_css['arfbgactivecolorsetting']            = isset( $styling_opts['arfbcas'] ) ? sanitize_text_field( $styling_opts['arfbcas'] ) : '';
			$form_css['arfborderactivecolorsetting']        = isset( $styling_opts['arfbacs'] ) ? sanitize_text_field( $styling_opts['arfbacs'] ) : '';
			$form_css['arferrorbgcolorsetting']             = isset( $styling_opts['arfbecs'] ) ? sanitize_text_field( $styling_opts['arfbecs'] ) : '';
			$form_css['arferrorbordercolorsetting']         = isset( $styling_opts['arfboecs'] ) ? sanitize_text_field( $styling_opts['arfboecs'] ) : '';
			$form_css['arfradioalignsetting']               = isset( $styling_opts['arfras'] ) ? sanitize_text_field( $styling_opts['arfras'] ) : '';
			$form_css['arfcheckboxalignsetting']            = isset( $styling_opts['arfcbas'] ) ? sanitize_text_field( $styling_opts['arfcbas'] ) : '';
			$form_css['auto_width']                         = isset( $styling_opts['arfautowidthsetting'] ) ? sanitize_text_field( $styling_opts['arfautowidthsetting'] ) : '';
			$form_css['arfcalthemename']                    = isset( $styling_opts['arffths'] ) ? sanitize_text_field( $styling_opts['arffths'] ) : '';
			$form_css['arfcalthemecss']                     = isset( $styling_opts['arffthc'] ) ? sanitize_text_field( $styling_opts['arffthc'] ) : '';
			$form_css['date_format']                        = isset( $styling_opts['arffdaf'] ) ? sanitize_text_field( $styling_opts['arffdaf'] ) : '';
			$form_css['arfsubmitbuttontext']                = isset( $styling_opts['arfsubmitbuttontext'] ) ? sanitize_text_field( $styling_opts['arfsubmitbuttontext'] ) : '';
			$form_css['arfsubmitweightsetting']             = isset( $styling_opts['arfsbwes'] ) ? sanitize_text_field( $styling_opts['arfsbwes'] ) : '';
			$form_css['arfsubmitbuttonfontsizesetting']     = isset( $styling_opts['arfsbfss'] ) ? intval( $styling_opts['arfsbfss'] ) : '';
			$form_css['arfsubmitbuttonwidthsetting']        = isset( $styling_opts['arfsbws'] ) ? sanitize_text_field( $styling_opts['arfsbws'] ) : '';
			$form_css['arfsubmitbuttonwidthsetting_tablet']        = isset( $styling_opts['arfsbws_tablet'] ) ? sanitize_text_field( $styling_opts['arfsbws_tablet'] ) : '';
			$form_css['arfsubmitbuttonwidthsetting_mobile']        = isset( $styling_opts['arfsbws_mobile'] ) ? sanitize_text_field( $styling_opts['arfsbws_mobile'] ) : '';
			$form_css['arfsubmitbuttonheightsetting']       = isset( $styling_opts['arfsbhs'] ) ? intval( $styling_opts['arfsbhs'] ) : '';
			$form_css['submit_bg_color']                    = isset( $styling_opts['arfsbbcs'] ) ? sanitize_text_field( $styling_opts['arfsbbcs'] ) : '';
			$form_css['arfsubmitbuttonbgcolorhoversetting'] = isset( $styling_opts['arfsbchs'] ) ? sanitize_text_field( $styling_opts['arfsbchs'] ) : '';
			$form_css['arfsubmitbgcolor2setting']           = isset( $styling_opts['arfsbcs'] ) ? sanitize_text_field( $styling_opts['arfsbcs'] ) : '';
			$form_css['arfsubmittextcolorsetting']          = isset( $styling_opts['arfsbtcs'] ) ? sanitize_text_field( $styling_opts['arfsbtcs'] ) : '';
			$form_css['arfsubmitbordercolorsetting']        = isset( $styling_opts['arfsbobcs'] ) ? sanitize_text_field( $styling_opts['arfsbobcs'] ) : '';
			$form_css['arfsubmitborderwidthsetting']        = isset( $styling_opts['arfsbbws'] ) ? intval( $styling_opts['arfsbbws'] ) : '';

			$form_css['arfsubmitboxxoffsetsetting']       = isset( $styling_opts['arfsbxos'] ) ? intval( $styling_opts['arfsbxos'] ) : '';
			$form_css['arfsubmitboxyoffsetsetting']       = isset( $styling_opts['arfsbyos'] ) ? intval( $styling_opts['arfsbyos'] ) : '';
			$form_css['arfsubmitboxblursetting']          = isset( $styling_opts['arfsbbs'] ) ? intval( $styling_opts['arfsbbs'] ) : '';
			$form_css['arfsubmitboxshadowsetting']        = isset( $styling_opts['arfsbsps'] ) ? intval( $styling_opts['arfsbsps'] ) : '';
			$form_css['arfsubmitborderradiussetting']     = isset( $styling_opts['arfsbbrs'] ) ? intval( $styling_opts['arfsbbrs'] ) : '';
			$form_css['arfsubmitshadowcolorsetting']      = isset( $styling_opts['arfsbscs'] ) ? sanitize_text_field( $styling_opts['arfsbscs'] ) : '';
			$form_css['arfsubmitbuttonmarginsetting']     = isset( $styling_opts['arfsbms'] ) ? sanitize_text_field( $styling_opts['arfsbms'] ) : '';
			$form_css['submit_bg_img']                    = isset( $styling_opts['arfsbis'] ) ? esc_url_raw( $styling_opts['arfsbis'] ) : '';
			$form_css['submit_hover_bg_img']              = isset( $styling_opts['arfsbhis'] ) ? esc_url_raw( $styling_opts['arfsbhis'] ) : '';
			$form_css['error_font']                       = isset( $styling_opts['arfmefs'] ) ? sanitize_text_field( $styling_opts['arfmefs'] ) : '';
			$form_css['error_font_other']                 = isset( $styling_opts['arfmofs'] ) ? sanitize_text_field( $styling_opts['arfmofs'] ) : '';
			$form_css['arffontsizesetting']               = isset( $styling_opts['arfmefss'] ) ? intval( $styling_opts['arfmefss'] ) : '';
			$form_css['arferrorbgsetting']                = isset( $styling_opts['arfmebs'] ) ? sanitize_text_field( $styling_opts['arfmebs'] ) : '';
			$form_css['arferrortextsetting']              = isset( $styling_opts['arfmets'] ) ? sanitize_text_field( $styling_opts['arfmets'] ) : '';
			$form_css['arferrorbordersetting']            = isset( $styling_opts['arfmebos'] ) ? sanitize_text_field( $styling_opts['arfmebos'] ) : '';
			$form_css['arfsucessbgcolorsetting']          = isset( $styling_opts['arfmsbcs'] ) ? sanitize_text_field( $styling_opts['arfmsbcs'] ) : '';
			$form_css['arfsucessbordercolorsetting']      = isset( $styling_opts['arfmsbocs'] ) ? sanitize_text_field( $styling_opts['arfmsbocs'] ) : '';
			$form_css['arfsucesstextcolorsetting']        = isset( $styling_opts['arfmstcs'] ) ? sanitize_text_field( $styling_opts['arfmstcs'] ) : '';
			$form_css['arfformerrorbgcolorsettings']      = isset( $styling_opts['arffebgc'] ) ? sanitize_text_field( $styling_opts['arffebgc'] ) : '';
			$form_css['arfformerrorbordercolorsettings']  = isset( $styling_opts['arffebrdc'] ) ? sanitize_text_field( $styling_opts['arffebrdc'] ) : '';
			$form_css['arfformerrortextcolorsettings']    = isset( $styling_opts['arffetxtc'] ) ? sanitize_text_field( $styling_opts['arffetxtc'] ) : '';
			$form_css['arfsubmitalignsetting']            = isset( $styling_opts['arfmsas'] ) ? sanitize_text_field( $styling_opts['arfmsas'] ) : '';
			$form_css['checkbox_radio_style']             = isset( $styling_opts['arfcrs'] ) ? sanitize_text_field( $styling_opts['arfcrs'] ) : '';
			$form_css['arfmainform_bg_img']               = isset( $styling_opts['arfmfbi'] ) ? esc_url_raw( $styling_opts['arfmfbi'] ) : '';
			$form_css['arfmainform_color_skin']           = isset( $styling_opts['arfmcs'] ) ? sanitize_text_field( $styling_opts['arfmcs'] ) : '';
			$form_css['arfinputstyle']                    = isset( $styling_opts['arfinpst'] ) ? sanitize_text_field( $styling_opts['arfinpst'] ) : 'standard';
			$form_css['arfsubmitfontfamily']              = isset( $styling_opts['arfsff'] ) ? sanitize_text_field( $styling_opts['arfsff'] ) : '';
			$form_css['arfcommonfont']                    = isset( $styling_opts['arfcommonfont'] ) ? sanitize_text_field( $styling_opts['arfcommonfont'] ) : 'Helvetica';
			$form_css['arfmainfieldcommonsize']           = isset( $styling_opts['arfmainfieldcommonsize'] ) ? intval( $styling_opts['arfmainfieldcommonsize'] ) : '3';
			$form_css['arfdatepickerbgcolorsetting']      = isset( $styling_opts['arfdbcs'] ) ? sanitize_text_field( $styling_opts['arfdbcs'] ) : '#23b7e5';
			$form_css['arfuploadbtntxtcolorsetting']      = isset( $styling_opts['arfuptxt'] ) ? sanitize_text_field( $styling_opts['arfuptxt'] ) : '#ffffff';
			$form_css['arfuploadbtnbgcolorsetting']       = isset( $styling_opts['arfupbg'] ) ? sanitize_text_field( $styling_opts['arfupbg'] ) : '#077BDD';
			$form_css['arfdatepickertextcolorsetting']    = isset( $styling_opts['arfdtcs'] ) ? sanitize_text_field( $styling_opts['arfdtcs'] ) : '#ffffff';

			$form_css['arfmainfieldsetpadding_1']         = ( isset( $styling_opts['arfmainfieldsetpadding_1'] ) && $styling_opts['arfmainfieldsetpadding_1'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_1'] ) : 0;
			$form_css['arfmainfieldsetpadding_2']         = ( isset( $styling_opts['arfmainfieldsetpadding_2'] ) && $styling_opts['arfmainfieldsetpadding_2'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_2'] ) : 0;
			$form_css['arfmainfieldsetpadding_3']         = ( isset( $styling_opts['arfmainfieldsetpadding_3'] ) && $styling_opts['arfmainfieldsetpadding_3'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_3'] ) : 0;
			$form_css['arfmainfieldsetpadding_4']         = ( isset( $styling_opts['arfmainfieldsetpadding_4'] ) && $styling_opts['arfmainfieldsetpadding_4'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_4'] ) : 0;

			$form_css['arfmainfieldsetpadding_1_tablet']         = ( isset( $styling_opts['arfmainfieldsetpadding_1_tablet'] ) && $styling_opts['arfmainfieldsetpadding_1_tablet'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_1'] ) : '';
			$form_css['arfmainfieldsetpadding_2_tablet']         = ( isset( $styling_opts['arfmainfieldsetpadding_2_tablet'] ) && $styling_opts['arfmainfieldsetpadding_2_tablet'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_2_tablet'] ) : '';
			$form_css['arfmainfieldsetpadding_3_tablet']         = ( isset( $styling_opts['arfmainfieldsetpadding_3_tablet'] ) && $styling_opts['arfmainfieldsetpadding_3_tablet'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_3_tablet'] ) : '';
			$form_css['arfmainfieldsetpadding_4']         = ( isset( $styling_opts['arfmainfieldsetpadding_4_tablet'] ) && $styling_opts['arfmainfieldsetpadding_4_tablet'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_4_tablet'] ) : '';

			$form_css['arfmainfieldsetpadding_1_mobile']         = ( isset( $styling_opts['arfmainfieldsetpadding_1_mobile'] ) && $styling_opts['arfmainfieldsetpadding_1_mobile'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_1'] ) : '';
			$form_css['arfmainfieldsetpadding_2_mobile']         = ( isset( $styling_opts['arfmainfieldsetpadding_2_mobile'] ) && $styling_opts['arfmainfieldsetpadding_2_mobile'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_2_mobile'] ) : '';
			$form_css['arfmainfieldsetpadding_3_mobile']         = ( isset( $styling_opts['arfmainfieldsetpadding_3_mobile'] ) && $styling_opts['arfmainfieldsetpadding_3_mobile'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_3_mobile'] ) : '';
			$form_css['arfmainfieldsetpadding_4']         = ( isset( $styling_opts['arfmainfieldsetpadding_4_mobile'] ) && $styling_opts['arfmainfieldsetpadding_4_mobile'] != '' ) ? intval( $styling_opts['arfmainfieldsetpadding_4_mobile'] ) : '';

		

			$form_css['arfmainformtitlepaddingsetting_1'] = ( isset( $styling_opts['arfformtitlepaddingsetting_1'] ) && $styling_opts['arfformtitlepaddingsetting_1'] != '' ) ? intval( $styling_opts['arfformtitlepaddingsetting_1'] ) : 0;
			$form_css['arfmainformtitlepaddingsetting_2'] = ( isset( $styling_opts['arfformtitlepaddingsetting_2'] ) && $styling_opts['arfformtitlepaddingsetting_2'] != '' ) ? intval( $styling_opts['arfformtitlepaddingsetting_2'] ) : 0;
			$form_css['arfmainformtitlepaddingsetting_3'] = ( isset( $styling_opts['arfformtitlepaddingsetting_3'] ) && $styling_opts['arfformtitlepaddingsetting_3'] != '' ) ? intval( $styling_opts['arfformtitlepaddingsetting_3'] ) : 0;
			$form_css['arfmainformtitlepaddingsetting_4'] = ( isset( $styling_opts['arfformtitlepaddingsetting_4'] ) && $styling_opts['arfformtitlepaddingsetting_4'] != '' ) ? intval( $styling_opts['arfformtitlepaddingsetting_4'] ) : 0;
			$form_css['arffieldinnermarginssetting_1']    = ( isset( $styling_opts['arffieldinnermarginsetting_1'] ) && $styling_opts['arffieldinnermarginsetting_1'] != '' ) ? intval( $styling_opts['arffieldinnermarginsetting_1'] ) : 0;
			$form_css['arffieldinnermarginssetting_2']    = ( isset( $styling_opts['arffieldinnermarginsetting_2'] ) && $styling_opts['arffieldinnermarginsetting_2'] != '' ) ? intval( $styling_opts['arffieldinnermarginsetting_2'] ) : 0;
			$form_css['arffieldinnermarginssetting_3']    = ( isset( $styling_opts['arffieldinnermarginsetting_3'] ) && $styling_opts['arffieldinnermarginsetting_3'] != '' ) ? intval( $styling_opts['arffieldinnermarginsetting_3'] ) : 0;
			$form_css['arffieldinnermarginssetting_4']    = ( isset( $styling_opts['arffieldinnermarginsetting_4'] ) && $styling_opts['arffieldinnermarginsetting_4'] != '' ) ? intval( $styling_opts['arffieldinnermarginsetting_4'] ) : 0;
			$form_css['arfsubmitbuttonmarginsetting_1']   = ( isset( $styling_opts['arfsubmitbuttonmarginsetting_1'] ) && $styling_opts['arfsubmitbuttonmarginsetting_1'] != '' ) ? intval( $styling_opts['arfsubmitbuttonmarginsetting_1'] ) : 0;
			$form_css['arfsubmitbuttonmarginsetting_2']   = ( isset( $styling_opts['arfsubmitbuttonmarginsetting_2'] ) && $styling_opts['arfsubmitbuttonmarginsetting_2'] != '' ) ? intval( $styling_opts['arfsubmitbuttonmarginsetting_2'] ) : 0;
			$form_css['arfsubmitbuttonmarginsetting_3']   = ( isset( $styling_opts['arfsubmitbuttonmarginsetting_3'] ) && $styling_opts['arfsubmitbuttonmarginsetting_3'] != '' ) ? intval( $styling_opts['arfsubmitbuttonmarginsetting_3'] ) : 0;
			$form_css['arfsubmitbuttonmarginsetting_4']   = ( isset( $styling_opts['arfsubmitbuttonmarginsetting_4'] ) && $styling_opts['arfsubmitbuttonmarginsetting_4'] != '' ) ? intval( $styling_opts['arfsubmitbuttonmarginsetting_4'] ) : 0;

			$form_css['arfcheckradiostyle']          = isset( $styling_opts['arfcksn'] ) ? sanitize_text_field( $styling_opts['arfcksn'] ) : '';
			$form_css['arfcheckradiocolor']          = isset( $styling_opts['arfcksc'] ) ? sanitize_text_field( $styling_opts['arfcksc'] ) : '';
			$form_css['arf_checked_checkbox_icon']   = isset( $styling_opts['arf_checkbox_icon'] ) ? sanitize_text_field( $styling_opts['arf_checkbox_icon'] ) : '';
			$form_css['enable_arf_checkbox']         = isset( $styling_opts['enable_arf_checkbox'] ) ? intval( $styling_opts['enable_arf_checkbox'] ) : '';
			$form_css['arf_checked_radio_icon']      = isset( $styling_opts['arf_radio_icon'] ) ? sanitize_text_field( $styling_opts['arf_radio_icon'] ) : '';
			$form_css['enable_arf_radio']            = isset( $styling_opts['enable_arf_radio'] ) ? intval( $styling_opts['enable_arf_radio'] ) : '';
			$form_css['checked_checkbox_icon_color'] = isset( $styling_opts['cbscol'] ) ? sanitize_text_field( $styling_opts['cbscol'] ) : '';
			$form_css['checked_radio_icon_color']    = isset( $styling_opts['rbscol'] ) ? sanitize_text_field( $styling_opts['rbscol'] ) : '';
			$form_css['arferrorstyle']               = isset( $styling_opts['arfest'] ) ? sanitize_text_field( $styling_opts['arfest'] ) : '';
			$form_css['arferrorstylecolor']          = isset( $styling_opts['arfestc'] ) ? sanitize_text_field( $styling_opts['arfestc'] ) : '';
			$form_css['arferrorstylecolor2']         = isset( $styling_opts['arfestc2'] ) ? sanitize_text_field( $styling_opts['arfestc2'] ) : '';
			$form_css['arferrorstyleposition']       = isset( $styling_opts['arfestbc'] ) ? sanitize_text_field( $styling_opts['arfestbc'] ) : '';
			$form_css['arfsuccessmsgposition']       = isset( $styling_opts['arfsuccessmsgposition'] ) ? sanitize_text_field( $styling_opts['arfsuccessmsgposition'] ) : '';
			$form_css['arfstandarderrposition']      = isset( $styling_opts['arfstndrerr'] ) ? sanitize_text_field( $styling_opts['arfstndrerr'] ) : 'relative';
			$form_css['arfformtitlealign']           = isset( $styling_opts['arffta'] ) ? sanitize_text_field( $styling_opts['arffta'] ) : '';
			$form_css['arfsubmitautowidth']          = isset( $styling_opts['arfsbaw'] ) ? intval( $styling_opts['arfsbaw'] ) : '';
			$form_css['arftitlefontfamily']          = isset( $styling_opts['arftff'] ) ? sanitize_text_field( $styling_opts['arftff'] ) : '';

			if ( isset( $styling_opts['arfmainform_opacity'] ) && $styling_opts['arfmainform_opacity'] > 1 ) {
				$form_css['arfmainform_opacity'] = '1';
			} else {
				$form_css['arfmainform_opacity'] = isset( $styling_opts['arfmainform_opacity'] ) ? floatval( $styling_opts['arfmainform_opacity'] ) : '';
			}

			if ( isset( $styling_opts['arfplaceholder_opacity'] ) && $styling_opts['arfplaceholder_opacity'] > 1 ) {
				$form_css['arfplaceholder_opacity'] = '1';
			} else {
				$form_css['arfplaceholder_opacity'] = isset( $styling_opts['arfplaceholder_opacity'] ) ? floatval( $styling_opts['arfplaceholder_opacity'] ) : '0.50';
			}
			$form_css['arfmainfield_opacity']     = isset( $styling_opts['arfmfo'] ) ? intval( $styling_opts['arfmfo'] ) : '';
			$form_css['arf_req_indicator']        = isset( $styling_opts['arfrinc'] ) ? intval( $styling_opts['arfrinc'] ) : '0';
			$form_css['prefix_suffix_bg_color']   = isset( $styling_opts['pfsfsbg'] ) ? sanitize_text_field( $styling_opts['pfsfsbg'] ) : '';
			$form_css['prefix_suffix_icon_color'] = isset( $styling_opts['pfsfscol'] ) ? sanitize_text_field( $styling_opts['pfsfscol'] ) : '';
			$form_css['arf_tooltip_bg_color']     = isset( $styling_opts['arf_tooltip_bg_color'] ) ? sanitize_text_field( $styling_opts['arf_tooltip_bg_color'] ) : '';
			$form_css['arf_tooltip_font_color']   = isset( $styling_opts['arf_tooltip_font_color'] ) ? sanitize_text_field( $styling_opts['arf_tooltip_font_color'] ) : '';
			$form_css['arf_tooltip_width']        = isset( $styling_opts['arf_tooltip_width'] ) ? sanitize_text_field( $styling_opts['arf_tooltip_width'] ) : '';

			$form_css['arftooltipposition'] = isset( $styling_opts['arflitetippos'] ) ? sanitize_text_field( $styling_opts['arflitetippos'] ) : '';

			$form_css['arfsubmitbuttonstyle'] = isset( $styling_opts['arfsubmitbuttonstyle'] ) ? sanitize_text_field( $styling_opts['arfsubmitbuttonstyle'] ) : 'border';

			$form_css['arfmainbasecolor']           = isset( $styling_opts['arfmbsc'] ) ? sanitize_text_field( $styling_opts['arfmbsc'] ) : '';
			$form_css['arferrorbordercolorsetting'] = sanitize_text_field( $form_css['arfmainbasecolor'] );

			$form_css['arfsliderselectioncolor'] = isset( $styling_opts['asldrsl'] ) ? sanitize_text_field( $styling_opts['asldrsl'] ) : '';
			$form_css['arfslidertrackcolor']     = isset( $styling_opts['asltrcl'] ) ? sanitize_text_field( $styling_opts['asltrcl'] ) : '';
			$new_values                          = array();
			$arfssl                              = ( is_ssl() ) ? 1 : 0;
			foreach ( $form_css as $k => $val ) {
				$new_values[ $k ] = $val;
			}
			$css_rtl_filename    = ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';
			$css_common_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';
			$css_filename        = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';
			if ( $style == 'material' ) {
				$css_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';
			}
			ob_start();
			$use_saved    = true;
			$is_form_save = true;
			include $css_filename;
			include $css_common_filename;
			if ( is_rtl() ) {
				include $css_rtl_filename;
			}
			$css           = ob_get_contents();
			$css           = str_replace( '##', '#', $css );
			$return['css'] = $css;
			ob_end_clean();
		}
		$frm_css          = $new_values;
		$data['form_css'] = maybe_serialize( $frm_css );
		$newarr           = array();
		$arr              = $data['form_css'];
		if ( isset( $arr ) && ! empty( $arr ) && is_array( $arr ) ) {
			foreach ( $arr as $k => $v ) {
				$newarr[ $k ] = $v;
			}
		}
		foreach ( $arf_fields as $field ) {

			$display_field_in_editor_from_outside = apply_filters( 'arflite_display_field_in_editor_outside', false, $field );

			if ( is_array( $field ) ) {
				$field['name'] = $this->arflite_html_entity_decode( $field['name'], true );

				$field['form_id'] = $form_id;
				$field_name       = 'item_meta[' . $field['id'] . ']';
				$has_field_opt    = false;
				if ( isset( $field['options'] ) && $field['options'] != '' && ! empty( $field['options'] ) ) {
					$has_field_opt    = true;
					$field_options_db = @json_decode( $field['options'], true );
					if ( json_last_error() != JSON_ERROR_NONE ) {
						$field_options_db = maybe_unserialize( $field['options'], true );
					}
				}

				$field_opt = json_decode( $field['field_options'], true );
				$class     = ( isset( $field_opt['inner_class'] ) && $field_opt['inner_class'] ) ? $field_opt['inner_class'] : 'arf_1col';
				array_push( $class_array, $field_opt['inner_class'] );

				if ( json_last_error() != JSON_ERROR_NONE ) {
					$field_opt = maybe_unserialize( $field['field_options'] );
				}

				if ( isset( $field_opt ) && ! empty( $field_opt ) ) {
					foreach ( $field_opt as $k => $field_opt_val ) {
						if ( $k != 'options' ) {
							$field[ $k ] = $this->arflite_html_entity_decode( $field_opt_val );
						} else {
							if ( $has_field_opt == true && $k == 'options' ) {
								$field[ $k ] = $field_options_db;
							}
						}
					}
				}
			}
			if ( ! $display_field_in_editor_from_outside ) {
				$filename = ARFLITE_VIEWS_PATH . '/arflite_field_editor.php';
				ob_start();
				include $filename;
				$content .= ob_get_contents();
				ob_end_clean();
				unset( $field );
				unset( $field_name );
			}
			$arf_field_counter++;
		}
		$return['content'] = $content;
		echo json_encode( $return );
		die();
	}

	function arfliteSearchArray( $id, $column, $array ) {
		foreach ( $array as $key => $val ) {
			if ( $val[ $column ] == $id ) {
				return $key;
			}
		}
		return null;
	}

	function arflite_html_entity_decode( $data ) {
		if ( is_array( $data ) ) {
			return array_map( array( $this, __FUNCTION__ ), $data );
		} elseif ( is_object( $data ) ) {
			$data = $this->arfliteObjtoArray( $data );
			return array_map( array( $this, __FUNCTION__ ), $data );
		} else {
			return html_entity_decode( $data );
		}
	}

	function arfliteHtmlEntities( $data, $addslashes = false ) {
		if ( is_array( $data ) ) {
			return array_map( array( $this, __FUNCTION__ ), $data );
		} elseif ( is_object( $data ) ) {
			$data = $this->arfliteObjtoArray( $data );
			return array_map( array( $this, __FUNCTION__ ), $data );
		} else {
			if ( $addslashes ) {
				return addslashes( htmlentities( $data ) );
			} else {
				return htmlentities( $data );
			}
		}
	}

	function arflitegetfieldfromid( $field_id, $field_values, $type = 'object' ) {
		if ( $field_id == '' || $field_id < 1 ) {
			return false;
		}

		if ( preg_match( '/(\d+)\.(\d+)/', $field_id, $match ) ) {
			$field_id = $match[1];
		}

		if ( is_object( $field_values ) ) {
			$field_values = $this->arfliteObjtoArray( $field_values );
		}

		$newObject = array();
		$key       = $this->arfliteSearchArray( $field_id, 'id', $field_values );
		$object    = isset( $field_values[ $key ] ) ? $field_values[ $key ] : array();
		if ( $type == 'object' ) {
			$object = $this->arfliteArraytoObj( $object );
		}
		return $object;
	}

	function arflitecode_to_country( $code = '', $country_name = '', $all = fale ) {
		$countryList = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua and Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BQ' => 'Bonaire',
			'BA' => 'Bosnia and Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'VG' => 'British Virgin Islands',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CD' => 'Democratic Republic of the Congo',
			'CG' => 'Congo',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote d\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CW' => 'Curacao',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FO' => 'Faroe Islands',
			'FK' => 'Falkland Islands (Malvinas)',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island and McDonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KP' => 'Korea, Democratic People\'s Republic of',
			'KR' => 'Korea, Republic of',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia, the Former Yugoslav Republic of',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States of',
			'MD' => 'Moldova, Republic of',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'AN' => 'Netherlands Antilles',
			'NL' => 'Netherlands',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestine, State of',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts and Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin (French part)',
			'PM' => 'Saint Pierre and Miquelon',
			'VC' => 'Saint Vincent and the Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome and Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SX' => 'Sint Maarten (Dutch part)',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'SS' => 'South Sudan',
			'GS' => 'South Georgia and the South Sandwich Islands',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard and Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan, Province of China',
			'TJ' => 'Tajikistan',
			'TZ' => 'United Republic of Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad and Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks and Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States of America',
			'US' => 'United States',
			'UM' => 'United States Minor Outlying Islands',
			'VI' => 'United States Virgin Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'British Virgin Islands',
			'VI' => 'US Virgin Islands',
			'WF' => 'Wallis and Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
		if ( $all ) {
			return $countryList;
		}
		if ( isset( $code ) ) {
			return array_search( $code, $countryList );
		}
	}
	function arflitereturndateformate() {
		$return_array                      = array();
		$return_array['arfwp_dateformate'] = $wp_format_date = get_option( 'date_format' );
		if ( $wp_format_date == 'F j, Y' ) {
			$date_format_new = 'MMMM D, YYYY';
		} elseif ( $wp_format_date == 'Y-m-d' ) {
			$date_format_new = 'YYYY-MM-DD';
		} elseif ( $wp_format_date == 'm/d/Y' ) {
			$date_format_new = 'MM/DD/YYYY';
		} elseif ( $wp_format_date == 'd/m/Y' ) {
			$date_format_new = 'DD/MM/YYYY';
		} elseif ( $wp_format_date == 'Y/m/d' ) {
			$date_format_new = 'DD/MM/YYYY';
		} else {
			$date_format_new = 'MM/DD/YYYY';
		}
		$return_array['arfjs_dateformate'] = $date_format_new;
		return $return_array;
	}



	function arflite_after_submit_sucess_outside_function( $return_script, $form ) {
		$arf_form_option              = isset( $form->options ) ? $form->options : '';
		$arf_sub_track_code           = isset( $arf_form_option['arf_sub_track_code'] ) ? $arf_form_option['arf_sub_track_code'] : '';
		$arf_submission_tracking_code = trim( rawurldecode( stripslashes_deep( $arf_sub_track_code ) ) );
		if ( $arf_submission_tracking_code != '' ) {
			$return_script .= "<script type='text/javascript'>";
			$return_script .= $arf_submission_tracking_code;
			$return_script .= '</script>';
			return $return_script;
		}
	}

	function arflite_load_form_css( $form_id, $inputStyle ) {

		global $arformsmain, $arflite_jscss_version, $arfliteversion,$is_gutenberg;
		$arf_db_version  = get_option( 'arflite_db_version' );
		$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
		$is_material     = false;
		$materialize_css = '';
		if ( $inputStyle == 'material' ) {
			$materialize_css = 'materialize_';
			$is_material     = true;
		}
		if ( is_ssl() ) {
			$fid = str_replace( 'http://', 'https://', $upload_main_url . '/maincss_' . $materialize_css . $form_id . '.css?ver=' . $arflite_jscss_version );
		} else {
			$fid = $upload_main_url . '/maincss_' . $materialize_css . $form_id . '.css?ver=' . $arflite_jscss_version;
		}

		$fid = esc_url_raw( $fid );

		$return_link        = '';
		$stylesheet_handler = 'arfliteformscss_' . $materialize_css . $form_id;
		if($is_gutenberg)
		{
			$arflite_gutenberg_materialize_css= ARFLITEURL."/css/arflite_front.css?ver=$arflite_jscss_version";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$fid}' />";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$arflite_gutenberg_materialize_css}' />";
		}
		global $is_divibuilder;
		if($is_divibuilder)
		{
			$arf_selectpiker = ARFLITEURL . "/css/arflite_selectpicker.css?ver=1.6.0";
			$arflite_gutenberg_materialize_css= ARFLITEURL."/css/arflite_front.css?ver=$arflite_jscss_version";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$fid}' />";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$arflite_gutenberg_materialize_css}' />";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$arf_selectpiker}' />";
		}

		global $is_fusionbuilder;
		if($is_fusionbuilder)
		{
			$arf_selectpiker = ARFLITEURL . "/css/arflite_selectpicker.css?ver=1.6.0";
			$arflite_gutenberg_materialize_css= ARFLITEURL."/css/arflite_front.css?ver=$arflite_jscss_version";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$fid}' />";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$arflite_gutenberg_materialize_css}' />";
			$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$arf_selectpiker}' />";
		}

		if ( ! wp_style_is( $stylesheet_handler, 'enqueued' ) ) {
			$arfmainformloadjscss = $arformsmain->arforms_get_settings('arfmainformloadjscss','general_settings');
			$arfmainformloadjscss = !empty( $arfmainformloadjscss ) ? $arfmainformloadjscss : 0;
			if ( $arfmainformloadjscss != 1 ) {
				wp_enqueue_style( $stylesheet_handler, $fid, array(), $arflite_jscss_version );
			} else {
				$new_key = '';
				global $ARFLiteMdlDb,$arflitemainhelper, $tbl_arf_forms;
				$unique_key = $arflitemainhelper->arflite_get_unique_key( $new_key, $tbl_arf_forms, 'form_key' );
			}
		} 
		else {
			if ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) {
				$return_link .= "<link rel='stylesheet' type='text/css' id='{$stylesheet_handler}-fallback-css' href='{$fid}' />";
			}
		}
		return $return_link;
	}

	function arflite_replace_default_value_shortcode_func( $default_value, $field, $form ) {

		if ( '' == $default_value || is_array( $default_value ) ) {
			return $default_value;
		}

		$current_user = wp_get_current_user();

		$pattern = '/(\[arf_current_user_detail(\s+)field\=(\'|\")(.*?)(\'|\")\])/';

		preg_match_all( $pattern, $default_value, $matches );

		foreach ( $matches as $parent_k => $match ) {
			if ( isset( $match ) && is_array( $match ) && count( $match ) == 1 ) {
				if ( $parent_k == 4 ) {
					$meta_key = isset( $match[0] ) ? $match[0] : '';
					if ( $meta_key == '' ) {
						$default_value = preg_replace( $pattern, '', $default_value );
					} elseif ( 0 == $current_user->ID || $current_user->ID < 1 ) {
						$default_value = preg_replace( $pattern, '', $default_value );
					} else {
						$user_obj = get_userdata( $current_user->ID );

						if ( isset( $user_obj->data->$meta_key ) ) {
							$default_value = preg_replace( $pattern, $user_obj->data->$meta_key, $default_value );
						} else {
							$user_meta = get_user_meta( $current_user->ID, $meta_key, true );

							if ( is_array( $user_meta ) ) {
								$user_meta = implode( ',', $user_meta );
							}
							$default_value = preg_replace( $pattern, $user_meta, $default_value );
						}
					}
				}
			} elseif ( isset( $match ) && is_array( $match ) && count( $match ) > 1 ) {
				if ( $parent_k == 4 ) {
					$meta_keys = $match;
					foreach ( $meta_keys as $meta_key ) {
						$pattern_new = '/(\[arf_current_user_detail(\s+)field\=(\'|\")' . $meta_key . '(\'|\")\])/';
						if ( $meta_key == '' ) {
							$default_value = preg_replace( $pattern_new, '', $default_value );
						} elseif ( 0 == $current_user->ID || $current_user->ID < 1 ) {
							$default_value = preg_replace( $pattern_new, '', $default_value );
						} else {
							$user_obj = get_userdata( $current_user->ID );
							if ( isset( $user_obj->data->$meta_key ) ) {
								$default_value = preg_replace( $pattern_new, $user_obj->data->$meta_key, $default_value );
							} else {
								$user_meta = get_user_meta( $current_user->ID, $meta_key, true );

								if ( is_array( $user_meta ) ) {
									$user_meta = implode( ',', $user_meta );
								}

								$default_value = preg_replace( $pattern_new, $user_meta, $default_value );
							}
						}
					}
				}
			} else {
				$default_value = preg_replace( $pattern, '', $default_value );
			}
		}

		return $default_value;
	}

	function arflite_remove_preview_data() {

		if ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$opt_id = isset( $_POST['opt_id'] ) ? sanitize_text_field( $_POST['opt_id'] ) : '';

		if ( $opt_id != '' ) {
			delete_option( $opt_id );
			echo esc_html( $opt_id ) . ' removed successfully';
		}
		die;
	}

	function arflite_rewrite_form_css( $form_id, $frm_css ) {
		global $wpdb, $ARFLiteMdlDb, $arfliteform, $tbl_arf_fields;

		if ( empty( $form_id ) || empty( $frm_css ) ) {
			return;
		}

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$form_css = maybe_unserialize( $frm_css );

		WP_Filesystem();
		global $wp_filesystem;

		$saving    = true;
		$use_saved = true;

		$arfssl = ( is_ssl() ) ? 1 : 0;

		$arflite_preview = false;

		$arfinputstyle = $form_css['arfinputstyle'];

		$wp_upload_dir = wp_upload_dir();
		$upload_dir    = $wp_upload_dir['basedir'] . '/arforms-form-builder/css/';
		$dest_dir      = $wp_upload_dir['basedir'] . '/arforms-form-builder/maincss/';
		$dest_css_url  = $wp_upload_dir['baseurl'] . '/arforms-form-builder/maincss/';

		$new_values = $form_css;

		$form = $arfliteform->arflitegetOne( (int) $form_id );

		$form->form_css = maybe_unserialize( $form->form_css );

		$form_options = maybe_unserialize( $form->options );

		$is_prefix_suffix_enable = false;
		$is_checkbox_img_enable  = false;
		$is_radio_img_enable     = false;

		$temp_fres = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_fields . ' WHERE form_id = %d ORDER BY id', $form->id ) ); //phpcs:ignore

		$loaded_field = array();
		if ( empty( $temp_fres ) ) {
			return; }
		foreach ( $temp_fres as $temp_fdata ) {
			$loaded_field[] = $temp_fdata->type;

			$field_options = json_decode( $temp_fdata->field_options, true );

			if ( ! empty( $field_options['enable_arf_prefix'] ) || ! empty( $field_options['enable_arf_suffix'] ) ) {
				$is_prefix_suffix_enable = true;
			}

			if ( 'checkbox' == $temp_fdata->type && ! empty( $field_options['use_image'] ) ) {
				$is_checkbox_img_enable = true;
			}

			if ( 'radio' == $temp_fdata->type && ! empty( $field_options['use_image'] ) ) {
				$is_radio_img_enable = true;
			}
		}

		$css_common_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_common.php';

		$css_rtl_filename = ARFLITE_FORMPATH . '/core/arflite_css_create_rtl.php';
		if ( 'standard' == $arfinputstyle || 'rounded' == $arfinputstyle ) {
			$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_main.php';

			$temp_css_file = $warn = '/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */';

			$temp_css_file .= "\n";

			ob_start();

			include $filename;
			include $css_common_filename;
			if ( is_rtl() ) {
				include $css_rtl_filename;
			}

			$temp_css_file .= str_replace( '##', '#', ob_get_contents() );

			ob_end_clean();

			$temp_css_file .= "\n " . $warn;
		} elseif ( 'material' == $arfinputstyle ) {
			$filename = ARFLITE_FORMPATH . '/core/arflite_css_create_materialize.php';

			$temp_materialize_file = $warn = '/* WARNING: Any changes made to this file will be lost when your ARForms settings are updated */';

			$temp_materialize_file .= "\n";

			ob_start();

			include $filename;
			include $css_common_filename;
			if ( is_rtl() ) {
				include $css_rtl_filename;
			}

			$temp_materialize_file .= str_replace( '##', '#', ob_get_contents() );

			ob_end_clean();

			$temp_materialize_file .= "\n " . $warn;
		}

		$css_file_new = $dest_dir . 'maincss_' . $form_id . '.css';

		$material_css_file_new = $dest_dir . 'maincss_materialize_' . $form_id . '.css';

		if ( 'standard' == $form_css['arfinputstyle'] || 'rounded' == $form_css['arfinputstyle'] ) {
			$temp_css_file = str_replace( '##', '#', $temp_css_file );
			$wp_filesystem->put_contents( $css_file_new, $temp_css_file, 0777 );
		}

		if ( 'material' == $form_css['arfinputstyle'] ) {
			$temp_materialize_file = str_replace( '##', '#', $temp_materialize_file );
			$wp_filesystem->put_contents( $material_css_file_new, $temp_materialize_file, 0777 );
		}
	}
}
function arflite_sort_callback_event_start( $a, $b ) {
	return (int) $a->field_order - (int) $b->field_order;
}
