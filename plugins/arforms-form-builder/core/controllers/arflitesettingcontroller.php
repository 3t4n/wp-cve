<?php

class arflitesettingcontroller {

	function __construct() {

		add_action( 'wp_ajax_arflite_upload_submit_hover_bg', array( $this, 'arflite_upload_submit_hover_bg' ) );

		add_action( 'wp_ajax_arflite_delete_submit_bg_img', array( $this, 'arflite_delete_submit_bg_img' ) );

		add_action( 'wp_ajax_arflite_delete_submit_hover_bg_img', array( $this, 'arflite_delete_submit_hover_bg_img' ) );

		add_filter( 'arflite_trim_values', array( $this, 'arflite_array_map' ), 10, 1 );

		add_action( 'wp_ajax_arflite_install_plugin', array( $this, 'arflite_install_plugin' ) );

		add_action( 'wp_ajax_arflite_activate_plugin', array( $this, 'arflite_activate_plugin' ) );

		add_action( 'wp_ajax_arf_lite_deactivate_plugin', array( $this, 'arflite_deactivate_plugin' ) );

		add_filter( 'plugins_api_args', array( $this, 'arflite_plugin_api_args' ), 100000, 2 );

		add_filter( 'plugins_api', array( $this, 'arflite_plugin_api' ), 100000, 3 );

		add_filter( 'plugins_api_result', array( $this, 'arflite_plugins_api_result' ), 100000, 3 );

		add_filter( 'upgrader_package_options', array( $this, 'arflite_upgrader_package_options' ), 100000 );

		add_action( 'arflite_addon_page_retrieve_notice', array( $this, 'arflite_retrieve_addon_page_notice' ) );
		/* gmail api ajax call */
        add_action('wp_ajax_arf_send_test_gmail', array($this, 'arflite_send_test_gmail'));
	}
	function arflite_gmail_remove_auth(){

		global $arformsmain;
		if ( !isset( $_POST['_wpnonce_arflite'] ) || (isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' )) ) {

            echo esc_attr( 'security_error' );
            die;
		}

        $auth_token = !empty( $_POST['auth_token'] ) ? sanitize_text_field( $_POST['auth_token'] ) : '' ; //phpcs:ignore WordPress.Security.NonceVerification
        $auth_email = !empty( $_POST['connected_email']) ? sanitize_text_field( $_POST['connected_email']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        $auth_response = !empty( $_POST['access_token_data']) ? $_POST['access_token_data'] : ''; //phpcs:ignore

        if( !empty( $auth_response)){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_response_data', '', 'general_settings' );
        }
        if( !empty( $auth_token)){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_access_token', '', 'general_settings' );
        }
        if( !empty( $auth_email )){
			$arformsmain->arforms_update_settings( 'arf_gmail_api_connected_email', '', 'general_settings' );
        }

        $response['variant'] = 'success';
        $response['title']   = esc_html__( 'Success', 'arforms-form-builder' );
        $response['msg']     = esc_html__( 'Sign out successfully.', 'arforms-form-builder' );
        echo wp_json_encode( $response );
        die();
    }
	function arflite_send_test_gmail(){

        global $arflitenotifymodel;
		
		if ( !isset( $_POST['_wpnonce_arflite'] ) || (isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' )) ) {
			echo esc_attr( 'security_error' );
            die;
		}

        $from_to = (isset($_POST['from_to'])) && !empty($_POST['from_to']) ? sanitize_email($_POST['from_to']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        $send_to = (isset($_POST['send_to'])) && !empty($_POST['send_to']) ? sanitize_email($_POST['send_to']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        $subject = (isset($_POST['subject']) && !empty($_POST['subject'])) ? sanitize_text_field($_POST['subject']) : addslashes(esc_html__('GMAIL Test E-Mail', 'arforms-form-builder')); //phpcs:ignore WordPress.Security.NonceVerification
        $message = (isset($_POST['message']) && !empty($_POST['message'])) ? sanitize_text_field($_POST['message']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        $reply_to_name = (isset($_POST['reply_to_name']) && !empty($_POST['reply_to_name'])) ? sanitize_text_field($_POST['reply_to_name']) : ''; //phpcs:ignore WordPress.Security.NonceVerification
        if (empty($send_to) || empty($from_to) || empty($message) || empty($subject)) {
            return;
        }
        echo $arflitenotifymodel->arflite_send_notification_email_user($send_to, $subject, $message, $from_to, $reply_to_name, '', array(), true, true, true, true); //phpcs:ignore
        die();
					
    }

	function arflite_retrieve_addon_page_notice() {
		$getNoticeData = get_transient( 'arforms_form_builder_addon_page_notice' );

		if ( false == $getNoticeData ) {
			$notice_url = 'https://www.arformsplugin.com/addonlist/arformslite_addon_notices.php';

			$getNotice = wp_remote_get(
				$notice_url,
				array(
					'timeout' => 5000,
				)
			);

			if ( ! is_wp_error( $getNotice ) ) {
				$notice_data = wp_remote_retrieve_body( $getNotice );

				set_transient( 'arforms_form_builder_addon_page_notice', base64_encode( $notice_data ), DAY_IN_SECONDS );

				$allowed_html = arflite_retrieve_attrs_for_wp_kses();

				echo wp_kses( $notice_data, $allowed_html );
			}
		} else {
			echo base64_decode( $getNoticeData ); //phpcs:ignore
		}
	}

	function arflitegenerateplugincode() {
		$siteinfo = array();

		global $arflitenotifymodel, $arfliteform;

		$siteinfo[] = $arflitenotifymodel->arflite_sitename();
		$siteinfo[] = $arfliteform->arflite_sitedesc();
		$siteinfo[] = home_url();
		$siteinfo[] = get_bloginfo( 'admin_email' );
		$siteinfo[] = isset( $_SERVER['SERVER_ADDR'] ) ? sanitize_text_field($_SERVER['SERVER_ADDR']) : '';

		$newstr  = implode( '^', $siteinfo );
		$postval = base64_encode( $newstr );

		return $postval;
	}

	function arflitemenu() {

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'General Settings', 'arforms-form-builder' ), __( 'General Settings', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-settings', array( $this, 'arfliteroute' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Import Export', 'arforms-form-builder' ), __( 'Import / Export', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-import-export', array( $this, 'arfliteroute' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Addons', 'arforms-form-builder' ), __( 'Addons', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite-addons', array( $this, 'arfliteroute' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Status', 'arforms-form-builder' ), __( 'Status', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-status', array( $this, 'arfliteroute' ) );

		add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Growth Plugins', 'arforms-form-builder' ), __( 'Growth Plugins', 'arforms-form-builder' ), 'arfchangesettings', 'ARF-Growth-Tools' , array( $this, 'arfliteroute' ) );

		$arf_current_date = current_time('timestamp', true );
		$arf_sale_start_time = '1700503200';
		$arf_sale_end_time = '1701561600';

		//$arf_upgrade_link_text = __( 'Upgrade To Premium', 'arforms-form-builder' );

		if( $arf_current_date >= $arf_sale_start_time && $arf_current_date <= $arf_sale_end_time ){
			add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Black Friday Sale', 'arforms-form-builder' ), __( 'Black Friday Sale', 'arforms-form-builder' ), 'arfchangesettings', 'ARForms-Lite&amp;upgrade-to-pro=yes' , array( $this, 'arfliteroute' ) );
		} else {
			$page_hook = add_submenu_page( 'ARForms-Lite', 'ARForms Lite | ' . __( 'Upgrade To Premium', 'arforms-form-builder' ), __( 'Upgrade To Premium', 'arforms-form-builder' ), 'arfchangesettings', 'arflite_upgrade_to_premium' , array( $this, 'arflite_upgrade_to_premium' ) );
			add_action( 'load-' . $page_hook, array( $this,'arf_upgrade_ob_start' ) );
		}

	}

	function arflite_upgrade_to_premium(){
		wp_redirect( 'https://codecanyon.net/item/arforms-wordpress-form-builder-plugin/6023165', 301 );
		exit();
	}

	function arf_upgrade_ob_start(){
		ob_start();
	}

	function arfliteroute() {

		global $arflitesettingcontroller;
		if ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-import-export' ) {
			return $arflitesettingcontroller->arflite_import_export_form();
		} elseif ( isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == 'ARForms-addons' ) {
			if ( file_exists( ARFLITE_VIEWS_PATH . '/addon_lists.php' ) ) {
				include ARFLITE_VIEWS_PATH . '/addon_lists.php';
			}
		} else if( isset($_REQUEST['page']) && $_REQUEST['page'] == 'ARForms-status'){
            if(file_exists(ARFLITE_VIEWS_PATH . '/arf_status.php')){
                include(ARFLITE_VIEWS_PATH . '/arf_status.php');
            }
        } elseif( isset ( $_REQUEST['page']) && $_REQUEST['page']=="ARF-Growth-Tools")
		{
			require_once ARFLITE_VIEWS_PATH . '/arflite_cross_selling_content.php';
		} else {
			$action = isset( $_REQUEST['arfaction'] ) ? 'arfaction' : 'action';

			global $arflitemainhelper, $arflitesettingcontroller;

			$cur_tab = isset( $_REQUEST['arfcurrenttab'] ) ? sanitize_text_field( $_REQUEST['arfcurrenttab'] ) : '';

			$action = $arflitemainhelper->arflite_get_param( $action );

			if ( $action == 'process-form' ) {
				return $arflitesettingcontroller->arfliteprocess_form( $cur_tab );
			} else {
				return $arflitesettingcontroller->arflitedisplay_form();
			}
		}
	}

	function arflitegetdeactlicurl() {
		$deactlicurl = 'https://www.reputeinfosystems.com/tf/plugins/arforms/verify/deactivelicwc.php';

		return $deactlicurl;
	}

	function arflitegetdeactlicurl_wssl() {
		$deactlicurl = 'http://www.reputeinfosystems.com/tf/plugins/arforms/verify/deactivelicwc.php';

		return $deactlicurl;
	}

	function arflitedisplay_form() {

		global $arfliteajaxurl, $wpdb, $arfliteform, $arflitemainhelper, $ARFLiteMdlDb;

		$arfroles = $arflitemainhelper->arflite_frm_capabilities();

		$target_path = ARFLITE_UPLOAD_DIR . '/css';

		$sections = apply_filters( 'arfliteaddsettingssection', array() );

		if ( get_option( 'arforms_current_tab' ) == '' ) {

			update_option( 'arforms_current_tab', sanitize_text_field( 'general_settings' ) );
		}

		require ARFLITE_VIEWS_PATH . '/arflite_settings_form.php';
	}

	function arflite_display_addons( $arf_addons = '' ) {

		require ARFLITE_VIEWS_PATH . '/arflite_view_addons.php';

	}

	function arflite_import_export_form() {
		require ARFLITE_VIEWS_PATH . '/arforms_import_export_form.php';
	}

	function arfliteprocess_form( $cur_tab = '' ) {

		global $arfliteajaxurl, $wpdb, $ARFLiteMdlDb, $arformsmain;

		$arflite_errors = array();

		if ( $cur_tab == 'general_settings' ) {

			$arforms_default_opts = $arformsmain->arflite_default_options();

			foreach( $arforms_default_opts as $option_name => $option_val ){
				if( !empty( $_POST[ $option_name ] ) ){ //phpcs:ignore
					$opt_val = $_POST[ $option_name ]; //phpcs:ignore
					if( is_array( $opt_val ) ){
						$opt_val = json_encode( $opt_val );
					}
					$arformsmain->arforms_update_settings( $option_name, $opt_val, 'general_settings' );
				} else if( !empty( $_POST['frm_' . $option_name ] ) ){ //phpcs:ignore
					$opt_val = $_POST[ 'frm_'.$option_name ]; //phpcs:ignore
					if( is_array( $opt_val ) ){
						$opt_val = json_encode( $opt_val );
					}
					$arformsmain->arforms_update_settings( $option_name, $opt_val, 'general_settings' );
				}
			}

		}

		if ( $cur_tab != '' ) {

			update_option( 'arforms_current_tab', sanitize_text_field( $cur_tab ) );
		}

		if ( empty( $arflite_errors ) ) {


			$message_notRquireFeild = '';

			if ( $cur_tab == 'general_settings' ) {
				$message = __( 'General setting saved successfully.', 'arforms-form-builder' );
			} elseif ( $cur_tab == 'autoresponder_settings' ) {
				$message = __( 'Email Marketing Tools setting saved successfully.', 'arforms-form-builder' );
			} else {
				$message = __( 'Settings Saved.', 'arforms-form-builder' );
			}

			if ( isset( $web_form_msg ) && $web_form_msg != '' ) {
				$web_form_msg_default = __( 'You have made below required fields which may not supported by system.', 'arforms-form-builder' ) . '<br>';
			}

			$web_form_msg = ( ( isset( $web_form_msg_default ) ) ? $web_form_msg_default : '' ) . ( ( isset( $web_form_msg ) ) ? $web_form_msg : '' );

			@$message_notRquireFeild .= $web_form_msg;
		}

		global $arflitemainhelper;
		$arfroles = $arflitemainhelper->arflite_frm_capabilities();

		$sections = apply_filters( 'arfliteaddsettingssection', array() );

		require ARFLITE_VIEWS_PATH . '/arflite_settings_form.php';
	}
	/* function arflitehead() {

		global $arflitemainhelper, $arfliteversion;

		$uicss = ARFLITEURL . '/css/ui-all/ui.all.css?ver=' . $arfliteversion;

		wp_register_style( 'ui-css', $uicss, array(), $arfliteversion );
		$arflitemainhelper->arflite_load_styles( array( 'ui-css' ) );

		$customcss = ARFLITESCRIPTURL . '&amp;controller=settings';

		wp_register_style( 'custom-css', $customcss, array(), $arfliteversion );
		$arflitemainhelper->arflite_load_styles( array( 'custom-css' ) );
		?>
		<?php
		require ARFLITE_VIEWS_PATH . '/arflite_head.php';
	} */

	function arflite_upload_submit_hover_bg() {

		if ( !isset( $_POST['_wpnonce_arflite'] ) || ( isset( $_POST['_wpnonce_arflite'] ) && '' != $_POST['_wpnonce_arflite'] && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce_arflite'] ), 'arflite_wp_nonce' ) ) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$file = isset( $_POST['image'] ) ? esc_url_raw( $_POST['image'] ) : ''; 
		?>
		<input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="<?php echo esc_url( $file ); ?>" id="arfsubmithoverbuttonimagesetting" />
		<img src="<?php echo esc_url( $file ); ?>" class="arf_upload_submitbtn_hover-img" height="35" width="35" />&nbsp;<span class="arflite-submit-hover-bgimg-span" onclick="arflite_delete_image('button_hover_image');"><svg width="23px" height="27px" viewBox="0 0 30 30"><path xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" fill="#4786FF" d="M19.002,4.351l0.007,16.986L3.997,21.348L3.992,4.351H1.016V2.38  h1.858h4.131V0.357h8.986V2.38h4.146h1.859l0,0v1.971H19.002z M16.268,4.351H6.745H5.993l0.006,15.003h10.997L17,4.351H16.268z   M12.01,7.346h1.988v9.999H12.01V7.346z M9.013,7.346h1.989v9.999H9.013V7.346z"/></svg></span>
		<?php
		die();
	}

	function arflite_delete_submit_bg_img() {
		global $arfliteversion;
		?>

		<input type="hidden" name="arfsbis" onclick="clear_file_submit();" value="" id="arfsubmitbuttonimagesetting" />
		<div class="arfajaxfileupload">
			<?php echo esc_html__( 'Upload Image', 'arforms-form-builder' ); ?>
			<input type="file" name="submit_btn_img" id="submit_btn_img"  class="original arflite_del_submit_bg-img" />
		</div>

		<input type="hidden" name="imagename" id="imagename" value="" />
		<?php

		die();
	}

	function arflite_delete_submit_hover_bg_img() {
		global $arfliteversion;
		?>

		<input type="hidden" name="arfsbhis" onclick="clear_file_submit_hover();" value="" id="arfsubmithoverbuttonimagesetting" />
		<div class="arfajaxfileupload">
			<?php echo esc_html__( 'Upload Image', 'arforms-form-builder' ); ?>
			<input type="file" name="submit_hover_btn_img" id="submit_hover_btn_img" data-val="submit_hover_bg" class="original arflite_del_submit_hover-bg-img" />
		</div>

		<input type="hidden" name="imagename_submit_hover" id="imagename_submit_hover" value="" />
		<?php

		die();
	}

	function arflitehex2rgb( $hex ) {
		$hex = str_replace( '#', '', $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return implode( ',', $rgb );
	}

	function arflitergba2rgb( $rgb, $alpha ) {

		$r = 1 * $rgb[0] + $alpha * $rgb[0];
		$g = 1 * $rgb[1] + $alpha * $rgb[1];
		$b = 1 * $rgb[2] + $alpha * $rgb[2];

		return array( $r, $g, $b );

	}

	function arfliteisColorDark( $color ) {
		$colors   = explode( ',', $this->arflitehex2rgb( $color ) );
		$r        = $colors[0];
		$g        = $colors[1];
		$b        = $colors[2];
		$darkness = round( ( 1 - ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255 ), 2 );
		if ( $darkness < 0.5 ) {
			return false;
		} else {
			return true;
		}
	}


	function arflite_generate_color_tone( $hex, $steps ) {

		$steps = max( -255, min( 255, $steps ) );

		$hex = str_replace( '#', '', $hex );

		if ( $hex != '' && strlen( $hex ) < 6 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		$color_parts = str_split( $hex, 2 );
		$return      = '#';

		$acsteps = str_replace( array( '+', '-' ), array( '', '' ), $steps );

		if ( strlen( $acsteps ) > 2 ) {
			$lum = $steps / 1000;
		} else {
			$lum = $steps / 100;
		}

		foreach ( $color_parts as $color ) {
			$color   = hexdec( $color );
			$color   = round( max( 0, min( 255, $color + ( $color * $lum ) ) ) );
			$return .= str_pad( dechex( $color ), 2, '0', STR_PAD_LEFT );
		}

		return $return;
	}

	function arflite_array_map( $input = array() ) {
		if ( empty( $input ) ) {
			return $input;
		}

		return is_array( $input ) ? array_map( array( $this, __FUNCTION__ ), $input ) : trim( $input );
	}

	function arflite_remove_directory( $directory ) {
		if ( $directory == '' ) {
			return false;
		}

		if ( is_dir( $directory ) ) {
			$dir_handle = opendir( $directory );
		}

		if ( ! isset( $dir_handle ) ) {
			return false;
		}

		while ( $file = readdir( $dir_handle ) ) {
			if ( $file != '.' && $file != '..' ) {
				if ( ! is_dir( $directory . '/' . $file ) ) {
					if ( false == @unlink( $directory . '/' . $file ) ) {
						@chmod( $directory . '/' . $file, 0777 );
						@unlink( $directory . '/' . $file );
					}
				} else {
					$this->arflite_remove_directory( $directory . '/' . $file );
				}
			}
		}
		closedir( $dir_handle );
		WP_Filesystem();
		global $wp_filesystem;
		$wp_filesystem->rmdir( $directory );
		return true;
	}

	function addons_page() {
		global $arflitesettingcontroller, $arfliteversion, $ARFLiteMdlDb,$arflitenotifymodel,$arfliteform,$arfliterecordmeta;

		$bloginformation = array();
		$str             = $ARFLiteMdlDb->arflite_get_rand_alphanumeric( 10 );

		if ( is_multisite() ) {
			$multisiteenv = 'Multi Site';
		} else {
			$multisiteenv = 'Single Site';
		}

		$addon_listing = 1;

		$bloginformation[] = $arflitenotifymodel->arflite_sitename();
		$bloginformation[] = $arfliteform->arflite_sitedesc();
		$bloginformation[] = home_url();
		$bloginformation[] = get_bloginfo( 'admin_email' );
		$bloginformation[] = $arfliterecordmeta->arflitewpversioninfo();
		$bloginformation[] = $arfliterecordmeta->arflitegetlanguage();
		$bloginformation[] = $arfliteversion;
		$bloginformation[] = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '';
		$bloginformation[] = $str;
		$bloginformation[] = $multisiteenv;
		$bloginformation[] = $addon_listing;

		$valstring  = implode( '||', $bloginformation );
		$encodedval = base64_encode( $valstring );

		$addon_data = get_transient( 'arflite_addon_listing_data_page' );

		if ( false == $addon_data ) {

			$urltopost = 'https://www.arformsplugin.com/addonlist/addon_list_3.0.php';

			$raw_response = wp_remote_post(
				$urltopost,
				array(
					'method'      => 'POST',
					'timeout'     => 45,
					'redirection' => 5,
					'httpversion' => '1.0',
					'blocking'    => true,
					'headers'     => array(),
					'body'        => array(
						'wpversion'  => $encodedval,
						'user_agent' => !empty($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
					),
					'cookies'     => array(),
				)
			);

			if ( is_wp_error( $raw_response ) || $raw_response['response']['code'] != 200 ) {
				echo "<div class='error_message' style='margin-top:100px; padding:20px;'>" . esc_html__( 'Add-On listing is currently unavailable. Please try again later.', 'arforms-form-builder' ) . '</div>';
			} else {
				set_transient( 'arf_addon_listing_data_page', $raw_response['body'], DAY_IN_SECONDS );
				echo $this->arf_display_addons( $raw_response['body'] ); //phpcs:ignore
			}
		} else {
			echo $this->arf_display_addons( $addon_data ); //phpcs:ignore
		}
	}

	function arf_display_addons( $arf_addons = '' ) {
		require ARFLITE_VIEWS_PATH . '/arflite_view_addons.php';
	}

	function CheckpluginStatus( $mypluginsarray, $pluginname, $attr, $purchase_addon, $plugin_type, $install_url, $is_allowed_for_free = false ) {

		foreach ( $mypluginsarray as $pluginarr ) {
			$response = '';
			if ( $pluginname == $pluginarr[ $attr ] ) {
				if ( $pluginarr['is_active'] == 1 ) {
					$response          = 'ACTIVE';
					$actionurl         = $pluginarr['deactivation_url'];
					$active_action_url = $pluginarr['deactivation_url'];
					break;
				} else {
					$response          = 'NOT ACTIVE';
					$actionurl         = $pluginarr['activation_url'];
					$active_action_url = $pluginarr['activation_url'];
					break;
				}
			} else {
				if ( $plugin_type == 'free' ) {
					$response  = 'NOT INSTALLED FREE';
					$actionurl = $install_url;
				} elseif ( $plugin_type == 'paid' ) {
					$response  = 'NOT INSTALLED PAID';
					$actionurl = $install_url;
				}
			}
		}

		$active_plugin_text = __( 'Active', 'arforms-form-builder' );

		$myicon       = '';
		$divclassname = '';
		if ( true == $is_allowed_for_free ) {
			if ( $response == 'NOT INSTALLED FREE' ) {
				$myicon = '<button class="addon_button no_icon" data-action="free_addon_install" data-plugin="' . $pluginname . '" href="javascript:void(0);"><span class="addon_processing_div addon_processing_tick">' . __( 'Installed', 'arforms-form-builder' ) . '</span><span class="get_it_a">' . __( 'Install', 'arforms-form-builder' ) . '</span><span class="arf_addon_loader"><svg class="arf_circular" viewBox="0 0 60 60"><circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle></svg></span></button>';
			} elseif ( $response == 'NOT INSTALLED PAID' ) {
				$myicon = '<button class="addon_button" onClick="window.open(\'' . $actionurl . '\',\'_blank\')">
	                <span><svg width="25px" height="25px" viewBox="0 0 30 30"><g><path style="fill:#8e9fb2;" d="M26.818,19.037l3.607-10.796c0.181-0.519,0.044-0.831-0.102-1.037   c-0.374-0.527-1.143-0.532-1.292-0.532L8.646,6.668L8.102,4.087c-0.147-0.609-0.581-1.19-1.456-1.19H0.917   C0.323,2.897,0,3.175,0,3.73v1.49c0,0.537,0.322,0.677,0.938,0.677h4.837l3.702,15.717c-0.588,0.623-0.908,1.531-0.908,2.378   c0,1.864,1.484,3.582,3.38,3.582c1.79,0,3.132-1.677,3.35-2.677h7.21c0.218,1,1.305,2.717,3.349,2.717   c1.863,0,3.378-1.614,3.378-3.475c0-1.851-1.125-3.492-3.359-3.492c-0.929,0-2.031,0.5-2.543,1.25h-8.859   c-0.643-1-1.521-1.31-2.409-1.345l-0.123-0.655h13.479C26.438,19.897,26.638,19.527,26.818,19.037z M25.883,22.828   c0.701,0,1.27,0.569,1.27,1.27s-0.569,1.27-1.27,1.27s-1.271-0.568-1.271-1.27C24.613,23.397,25.182,22.828,25.883,22.828z    M13.205,24.098c0,0.709-0.576,1.286-1.283,1.286c-0.709-0.002-1.286-0.577-1.286-1.286s0.577-1.286,1.286-1.286   C12.629,22.812,13.205,23.389,13.205,24.098z"></path></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span><span class="get_it_a">' . __( 'Get It', 'arforms-form-builder' ) . '</span></button>';
			} elseif ( $response == 'ACTIVE' ) {
				$myicon = '<button class="addon_button no_icon" data-action="deactivate" data-plugin="' . $pluginname . '" href="javascript:void(0);" data-href=' . $actionurl . '><span class="addon_processing_div addon_processing_tick_deactivation">' . __( 'Deactivated', 'arforms-form-builder' ) . '</span><span class="get_it_a">' . __( 'Deactivate', 'arforms-form-builder' ) . '</span><span class="arf_addon_loader"><svg class="arf_circular" viewBox="0 0 60 60"><circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle></svg></span></button>';
			} elseif ( $response == 'NOT ACTIVE' ) {
				$myicon = '<button class="addon_button no_icon" data-action="activate" data-plugin="' . $pluginname . '" href="javascript:void(0);" data-href=' . $active_action_url . '><span class="addon_processing_div addon_processing_tick">' . __( 'Activated', 'arforms-form-builder' ) . '</span><span class="get_it_a">' . $active_plugin_text . '</span><span class="arf_addon_loader"><svg class="arf_circular" viewBox="0 0 60 60"><circle class="path" cx="25px" cy="23px" r="18" fill="none" stroke-width="4" stroke-miterlimit="7"></circle></svg></span></button>';
			}
		} else {
			$actionurl = 'https://1.envato.market/rdeQD';
			$myicon    = '<button class="addon_button" onClick="window.open(\'' . $actionurl . '\',\'_blank\')"><span><svg width="25px" height="25px" viewBox="0 0 30 30"><g><path style="fill:#8e9fb2;" d="M26.818,19.037l3.607-10.796c0.181-0.519,0.044-0.831-0.102-1.037   c-0.374-0.527-1.143-0.532-1.292-0.532L8.646,6.668L8.102,4.087c-0.147-0.609-0.581-1.19-1.456-1.19H0.917   C0.323,2.897,0,3.175,0,3.73v1.49c0,0.537,0.322,0.677,0.938,0.677h4.837l3.702,15.717c-0.588,0.623-0.908,1.531-0.908,2.378   c0,1.864,1.484,3.582,3.38,3.582c1.79,0,3.132-1.677,3.35-2.677h7.21c0.218,1,1.305,2.717,3.349,2.717   c1.863,0,3.378-1.614,3.378-3.475c0-1.851-1.125-3.492-3.359-3.492c-0.929,0-2.031,0.5-2.543,1.25h-8.859   c-0.643-1-1.521-1.31-2.409-1.345l-0.123-0.655h13.479C26.438,19.897,26.638,19.527,26.818,19.037z M25.883,22.828   c0.701,0,1.27,0.569,1.27,1.27s-0.569,1.27-1.27,1.27s-1.271-0.568-1.271-1.27C24.613,23.397,25.182,22.828,25.883,22.828z    M13.205,24.098c0,0.709-0.576,1.286-1.283,1.286c-0.709-0.002-1.286-0.577-1.286-1.286s0.577-1.286,1.286-1.286   C12.629,22.812,13.205,23.389,13.205,24.098z"></path></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg></span><span class="get_it_a">' . __( 'Get ARForms Pro', 'arforms-form-builder' ) . '</span></button>';
		}
		return $myicon;
	}

	function arflite_install_plugin() {

		if ( !isset( $_POST['_wpnonce'] ) || (isset( $_POST['_wpnonce'] ) && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'arf_wp_nonce' )) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		if ( empty( $_POST['slug'] ) ) { //phpcs:ignore
			wp_send_json_error(
				array(
					'slug'         => '',
					'errorCode'    => 'no_plugin_specified',
					'errorMessage' => __( 'No plugin specified.', 'arforms-form-builder' ),
				)
			);
		}

		$plugin      = sanitize_text_field( $_POST['slug'] ); //phpcs:ignore
		$plugin      = plugin_basename( trim( $plugin ) );
		$plugin_slug = explode( '/', $plugin );
		$plugin_slug = $plugin_slug[0];

		$status = array(
			'install' => 'plugin',
			'slug'    => sanitize_key( wp_unslash( $plugin_slug ) ),
		);

		if ( ! current_user_can( 'install_plugins' ) ) {
			$status['errorMessage'] = __( 'Sorry, you are not allowed to install plugins on this site.', 'arforms-form-builder' );
			wp_send_json_error( $status );
		}

		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' ) ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		}

		if ( file_exists( ABSPATH . 'wp-admin/includes/plugin-install.php' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		}

		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => sanitize_key( wp_unslash( $plugin_slug ) ),
				'fields' => array(
					'sections' => false,
				),
			)
		);

		if ( is_wp_error( $api ) ) {
			$status['errorMessage'] = $api->get_error_message();
			wp_send_json_error( $status );
		}

		$status['pluginName'] = $api->name;

		$skin     = new WP_Ajax_Upgrader_Skin();
		$upgrader = new Plugin_Upgrader( $skin );

		$result = $upgrader->install( $api->download_link );

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			$status['debug'] = $skin->get_upgrade_messages();
		}

		if ( is_wp_error( $result ) ) {
			$status['errorCode']    = $result->get_error_code();
			$status['errorMessage'] = $result->get_error_message();
			wp_send_json_error( $status );
		} elseif ( is_wp_error( $skin->result ) ) {
			$status['errorCode']    = $skin->result->get_error_code();
			$status['errorMessage'] = $skin->result->get_error_message();
			wp_send_json_error( $status );
		} elseif ( $skin->get_errors()->get_error_code() ) {
			$status['errorMessage'] = $skin->get_error_messages();
			wp_send_json_error( $status );
		} elseif ( is_null( $result ) ) {
			global $wp_filesystem;

			$status['errorCode']    = 'unable_to_connect_to_filesystem';
			$status['errorMessage'] = __( 'Unable to connect to the filesystem. Please confirm your credentials.', 'arforms-form-builder' );

			if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}

			wp_send_json_error( $status );
		}

		$install_status = $this->arflite_install_plugin_install_status( $api );

		if ( current_user_can( 'activate_plugins' ) && is_plugin_inactive( $install_status['file'] ) ) {
			$status['activateUrl'] = add_query_arg(
				array(
					'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $install_status['file'] ),
					'action'   => 'activate',
					'plugin'   => $install_status['file'],
				),
				network_admin_url( 'plugins.php' )
			);
		}

		if ( is_multisite() && current_user_can( 'manage_network_plugins' ) ) {
			$status['activateUrl'] = add_query_arg( array( 'networkwide' => 1 ), $status['activateUrl'] );
		}
		$status['pluginFile'] = $install_status['file'];

		wp_send_json_success( $status );
	}

	function arflite_activate_plugin() {

		$plugin       = !empty( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : ''; //phpcs:ignore
		$plugin       = plugin_basename( trim( $plugin ) );
		$network_wide = false;
		$silent       = false;
		$redirect     = '';

		
		/** Check if user can activate plugins or not */
		if( !current_user_can( 'activate_plugins' ) ){
			$response = array(
				'type' => 'error',
				'message' => esc_html__( 'Sorry! you do not have permission to activate the add-on', 'arforms-form-builder')
			);
			echo json_encode( $response );
			die();
		}

		if ( !isset( $_POST['_wpnonce'] ) || (isset( $_POST['_wpnonce'] )  && ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'arf_wp_nonce' )) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		if ( is_multisite() && ( $network_wide || is_network_only_plugin( $plugin ) ) ) {
			$network_wide        = true;
			$current             = get_site_option( 'active_sitewide_plugins', array() );
			$_GET['networkwide'] = 1;
		} else {
			$current = get_option( 'active_plugins', array() );
		}

		$valid = validate_plugin( $plugin );
		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		if ( ( $network_wide && ! isset( $current[ $plugin ] ) ) || ( ! $network_wide && ! in_array( $plugin, $current ) ) ) {
			if ( ! empty( $redirect ) ) {
				wp_redirect( add_query_arg( '_error_nonce', wp_create_nonce( 'plugin-activation-error_' . $plugin ), $redirect ) );
			}
			ob_start();
			wp_register_plugin_realpath( WP_PLUGIN_DIR . '/' . $plugin );
			$_wp_plugin_file = $plugin;
			include_once WP_PLUGIN_DIR . '/' . $plugin;
			$plugin = $_wp_plugin_file;

			if ( ! $silent ) {
				do_action( 'activate_plugin', $plugin, $network_wide );
				do_action( 'activate_' . $plugin, $network_wide );
			}

			if ( $network_wide ) {
				$current            = get_site_option( 'active_sitewide_plugins', array() );
				$current[ $plugin ] = time();
				update_site_option( 'active_sitewide_plugins', $current );
			} else {
				$current   = get_option( 'active_plugins', array() );
				$current[] = $plugin;
				sort( $current );
				update_option( 'active_plugins', $current );
			}

			if ( ! $silent ) {
				do_action( 'activated_plugin', $plugin, $network_wide );
			}
			$response = array();
			if ( ob_get_length() > 0 ) {
				$response = array(
					'type' => 'error',
				);
				echo json_encode( $response );
				die();
			} else {
				$response = array(
					'type' => 'success',
				);
				echo json_encode( $response );
				die();
			}
		}
		die();
	}

	function arflite_deactivate_plugin() {
		$plugin       = !empty( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : ''; //phpcs:ignore
		$silent       = false;
		$network_wide = false;
		if ( is_multisite() ) {
			$network_current = get_site_option( 'active_sitewide_plugins', array() );
		}

		/** Check if user can activate plugins or not */
		if( !current_user_can( 'activate_plugins' ) ){
			$response = array(
				'type' => 'error',
				'message' => esc_html__( 'Sorry! you do not have permission to deactivate the add-on','arforms-form-builder'),
			);
			echo json_encode( $response );
			die();
		}

		if ( !isset( $_POST['_wpnonce'] ) || (isset( $_POST['_wpnonce'] ) &&  ! wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), 'arf_wp_nonce' )) ) {
			echo esc_attr( 'security_error' );
			die;
		}

		$current = get_option( 'active_plugins', array() );
		$do_blog = $do_network = false;

		$plugin = plugin_basename( trim( $plugin ) );

		$network_deactivating = false !== $network_wide && is_plugin_active_for_network( $plugin );

		if ( ! $silent ) {
			do_action( 'deactivate_plugin', $plugin, $network_deactivating );
		}

		if ( false != $network_wide ) {
			if ( is_plugin_active_for_network( $plugin ) ) {
				$do_network = true;
				unset( $network_current[ $plugin ] );
			} elseif ( $network_wide ) {

			}
		}

		if ( true != $network_wide ) {
			$key = array_search( $plugin, $current );
			if ( false !== $key ) {
				$do_blog = true;
				unset( $current[ $key ] );
			}
		}

		if ( ! $silent ) {
			do_action( 'deactivate_' . $plugin, $network_deactivating );
			do_action( 'deactivated_plugin', $plugin, $network_deactivating );
		}

		if ( $do_blog ) {
			update_option( 'active_plugins', $current );
		}
		if ( $do_network ) {
			update_site_option( 'active_sitewide_plugins', $network_current );
		}

		$response = array(
			'type' => 'success',
		);

		echo json_encode( $response );
		die();
	}

	function arflite_install_plugin_install_status( $api, $loop = false ) {
		if ( is_array( $api ) ) {
			$api = (object) $api;
		}

		$status      = 'install';
		$url         = false;
		$update_file = false;

		/*
		 * Check to see if this plugin is known to be installed,
		 * and has an update awaiting it.
		 */
		$update_plugins = get_site_transient( 'update_plugins' );
		if ( isset( $update_plugins->response ) ) {
			foreach ( (array) $update_plugins->response as $file => $plugin ) {
				if ( $plugin->slug === $api->slug ) {
					$status      = 'update_available';
					$update_file = $file;
					$version     = $plugin->new_version;
					if ( current_user_can( 'update_plugins' ) ) {
						$url = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' . $update_file ), 'upgrade-plugin_' . $update_file );
					}
					break;
				}
			}
		}

		if ( 'install' == $status ) {
			if ( is_dir( WP_PLUGIN_DIR . '/' . $api->slug ) ) {
				$installed_plugin = get_plugins( '/' . $api->slug );
				if ( empty( $installed_plugin ) ) {
					if ( current_user_can( 'install_plugins' ) ) {
						$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $api->slug ), 'install-plugin_' . $api->slug );
					}
				} else {
					$key         = array_keys( $installed_plugin );
					$key         = reset( $key );
					$update_file = $api->slug . '/' . $key;
					if ( version_compare( $api->version, $installed_plugin[ $key ]['Version'], '=' ) ) {
						$status = 'latest_installed';
					} elseif ( version_compare( $api->version, $installed_plugin[ $key ]['Version'], '<' ) ) {
						$status  = 'newer_installed';
						$version = $installed_plugin[ $key ]['Version'];
					} else {
						if ( ! $loop ) {
							delete_site_transient( 'update_plugins' );
							wp_update_plugins();
							return $this->arflite_install_plugin_install_status( $api, true );
						}
					}
				}
			} else {
				if ( current_user_can( 'install_plugins' ) ) {
					$url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $api->slug ), 'install-plugin_' . $api->slug );
				}
			}
		}
		if ( isset( $_GET['from'] ) ) {
			$url .= '&amp;from=' . urlencode( intval( $_GET['from'] ) );
		}

		$url = esc_url_raw( $url );

		$file = $update_file;
		return compact( 'status', 'url', 'version', 'file' );
	}

	function arflite_upgrader_package_options( $options ) {
		$options['is_multi'] = false;
		return $options;
	}

	function arflite_plugin_api_args( $args, $action ) {
		return $args;
	}

	function arflite_plugin_api( $res, $action, $args ) {
		$arforms_addons = get_transient( 'arflite_addon_installation_page_data' );

		if ( isset( $arforms_addons ) && ! empty( $arforms_addons ) ) {
			$obj = array();
			foreach ( $arforms_addons as $slug => $arforms_addon ) {
				if ( isset( $slug ) && isset( $args->slug ) ) {
					if ( $slug != $args->slug ) {
						continue;
					} else {
						$obj['name']          = $arforms_addon['full_name'];
						$obj['slug']          = $slug;
						$obj['version']       = $arforms_addon['plugin_version'];
						$obj['download_link'] = $arforms_addon['install_url'];
						return (object) $obj;
					}
				} else {
					continue;
				}
			}
		}
		return $res;
	}

	function arflite_plugins_api_result( $res, $action, $args ) {
		return $res;
	}

}

function arfliteobject2array( $object ) {
	return @json_decode( @json_encode( $object ), 1 );
}
?>
