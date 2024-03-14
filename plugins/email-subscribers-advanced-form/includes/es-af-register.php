<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class es_af_registerhook {
	public static function es_af_activation() {

		global $wpdb;

		$es_af_pluginversion = "";
		$es_af_tableexists = "YES";
		$es_af_pluginversion = get_option("es_af_pluginversion");

		$es_af_dbtable = $wpdb->get_var("show tables like '". $wpdb->prefix . ES_AF_TABLE . "'");
		
		if($es_af_dbtable != "") {
			if( strtoupper($es_af_dbtable) != strtoupper($wpdb->prefix . ES_AF_TABLE) ) {
				$es_af_tableexists = "NO";
			}
		} else {
			$es_af_tableexists = "NO";
		}

		if(($es_af_tableexists == "NO") || ($es_af_pluginversion != ES_AF_DBVERSION)) {
			$sSql = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix . ES_AF_TABLE . " (
						es_af_id mediumint(9) NOT NULL AUTO_INCREMENT,
						es_af_title VARCHAR(1024) DEFAULT 'Form 1' NOT NULL,
						es_af_desc VARCHAR(1024) DEFAULT 'Welcome to Email Subscribers Group Selector' NOT NULL,
						es_af_name VARCHAR(20) DEFAULT 'YES' NOT NULL,
						es_af_name_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,	 
						es_af_email VARCHAR(20) DEFAULT 'YES' NOT NULL,
						es_af_email_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,
						es_af_group VARCHAR(20) DEFAULT 'YES' NOT NULL,
						es_af_group_mand VARCHAR(20) DEFAULT 'YES' NOT NULL,
						es_af_group_list VARCHAR(1024) DEFAULT 'Public' NOT NULL,
						es_af_plugin VARCHAR(20) DEFAULT 'es-af' NOT NULL,
						UNIQUE KEY es_af_id (es_af_id)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sSql );

			if($es_af_pluginversion == "") {
				add_option('es_af_pluginversion', "1.0");
			} else {
				update_option( "es_af_pluginversion", ES_AF_DBVERSION );
			}

			if($es_af_tableexists == "NO") {
				$welcome_text = "Form 1";		
				$rows_affected = $wpdb->insert( $wpdb->prefix . ES_AF_TABLE , array( 'es_af_title' => $welcome_text) );
			}
		}
	}

	public static function es_af_deactivation() {
		// No action required
	}

	public static function es_af_adminmenu() {
		if (is_admin() && is_plugin_active('email-subscribers/email-subscribers.php') )	{
			add_submenu_page('es-view-subscribers', __( 'Group Selector', ES_AF_TDOMAIN ), 
				__( 'Group Selector', ES_AF_TDOMAIN ), 'manage_options', 'es-af-advancedform', array( 'es_af_intermediate', 'es_af_advancedform' ));
		} else {
			?><div class="notice notice-error is-dismissible">
				<p><?php echo __( '<strong>Email Subscribers</strong> plugin should be installed & activated before activating <strong>Email Subscribers - Group Selector</strong>', ES_AF_TDOMAIN ); ?></p>
			</div>
			<?php
		}	
	}

	public static function es_af_load_scripts() {
		if( !empty( $_GET['page'] ) ) {
			if( $_GET['page'] == 'es-af-advancedform' ) {
				wp_register_script( 'esaf-settings', ES_AF_URL . 'includes/form/setting.js' );
				wp_enqueue_script( 'esaf-settings', ES_AF_URL . 'includes/form/setting.js' );
				$esaf_select_params = array(
					'esaf_form_title'				=> _x( 'Enter title for your form.', 'settings-enhanced-select', ES_AF_TDOMAIN ),
					'esaf_settings_delete_record'	=> _x( 'Do you want to delete this record?', 'settings-enhanced-select', ES_AF_TDOMAIN )
				);
				wp_localize_script( 'esaf-settings', 'esaf_settings_notices', $esaf_select_params );
			}
		}
	}

	public static function esaf_add_admin_notices() {

		$screen = get_current_screen();
		if ( !in_array( $screen->id, array( 'email-subscribers_page_es-af-advancedform', 'plugins' ), true ) ) return;

		// Notice to inform about GDPR
		$esaf_gdpr_consent_notify = get_option( 'esaf_gdpr_consent_notify_group_selector' );
		if( $esaf_gdpr_consent_notify != 'no' ) {
			?>
			<style type="text/css">
				a.es-gdpr-admin-btn {
				margin-left: 10px;
				padding: 4px 8px;
				position: relative;
				text-decoration: none;
				border: none;
				-webkit-border-radius: 2px;
				border-radius: 2px;
				background: #e0e0e0;
				text-shadow: none;
				font-weight: 600;
				font-size: 13px;
				background-color: green;
				color: white;
			}
			a.es-gdpr-admin-btn:hover {
				color: #FFF;
				background-color: #363b3f;
			}
			a.es-gdpr-admin-btn-secondary {
				margin-left: 1em;
				font-weight: 400;
				background-color: #FFFFFF;
				color: #000000;
			}
			</style>
			<?php

				$url = 'https://www.icegram.com/documentation/esaf-gdpr-how-to-enable-consent-checkbox-in-the-subscription-form/?utm_source=es&utm_medium=in_app_gdpr_banner&utm_campaign=view_banner';
				$admin_notice_text_esaf_gdpr_consent = __( '<b style="letter-spacing:0.4px;">To honour GDPR, kindly enable the \'Consent Checkbox\' in the subscription form.</b>', ES_AF_TDOMAIN );
				echo '<div class="notice notice-warning"><p style="letter-spacing: 0.6px;">'.$admin_notice_text_esaf_gdpr_consent.'<a target="_blank" style="display:inline-block" class="es-gdpr-admin-btn" href="'.$url.'">'.__( 'Steps to enable', ES_AF_TDOMAIN ).'</a><a style="display:inline-block" class="es-gdpr-admin-btn es-gdpr-admin-btn-secondary" href="?esaf_dismiss_admin_notice=1&option_name=esaf_gdpr_consent_notify">'.__( 'Ok, got it', ES_AF_TDOMAIN ).'</a></p></div>';
		}

	}

	public static function esaf_dismiss_admin_notice() {

		if(isset($_GET['esaf_dismiss_admin_notice']) && $_GET['esaf_dismiss_admin_notice'] == '1' && isset($_GET['option_name'])) {
			$option_name = sanitize_text_field($_GET['option_name']);
			update_option( $option_name.'_group_selector', 'no' );

			$referer = wp_get_referer();
			wp_safe_redirect( $referer );
			exit();

		}

	}

	public static function es_af_widget_loading() {
		register_widget( 'es_af_widget_register' );
	}

	public static function esaf_special_letters() {
		$string = "/[\'^$%&*()}{@#~?><>|=_+\"]/";
		return $string;
	}
}

class es_af_form_submuit {
	public static function es_af_preparation($es_af_name = "", $es_af_email = "", $es_af_group = array(), $es_af_nonce = "") {
		$sts = "";

		// Made compatible with ES 3.3
		$es_optintype = get_option( 'ig_es_optintype', 'norecord' );
		if ( $es_optintype == 'norecord' ) {
			$data = es_cls_settings::es_setting_select(1);
			$es_optin_type = $data['es_c_optinoption'];
			$es_welcome_email = $data['es_c_usermailoption'];
		} else {
			$es_optin_type = $es_optintype;
			$es_welcome_email = get_option( 'ig_es_welcomeemail' );
		}

		$form = array(
			'es_email_name' => '',
			'es_email_status' => '',
			'es_email_group' => '',
			'es_email_mail' => '',
			'es_af_nonce' => ''
		);

		$email_saved_single_opt_in = 0;
		$email_saved_double_opt_in = 0;
		$email_already_exists = 0;

		$es_af_group_count = count($es_af_group);
		if ($es_af_group_count > 0) {
			for ($i=0; $i<$es_af_group_count; $i++) {
				$form['es_email_name'] = $es_af_name;
				$form['es_email_mail'] = $es_af_email;
				$form['es_email_group'] = $es_af_group[$i];					
				$form['es_af_nonce'] = $es_af_nonce;

				if ( $es_optin_type == "Double Opt In" ) {
					$form['es_email_status'] = "Unconfirmed";
				} else {
					$form['es_email_status'] = "Single Opt In";
				}

				$action = es_cls_dbquery::es_view_subscriber_widget($form);

				if ($action == "sus") {
					$subscribers = array();
					$subscribers = es_cls_dbquery::es_view_subscriber_one($form['es_email_mail'],$form['es_email_group']);
					if( $es_optin_type == "Double Opt In" ) {
						if( $email_saved_double_opt_in == 0 ) {
							es_cls_sendmail::es_sendmail("optin", $template = 0, $subscribers, "optin", 0);
						}
						$email_saved_double_opt_in = $email_saved_double_opt_in + 1;
					} else {
						if( $es_welcome_email == "YES" ) {
							if($email_saved_single_opt_in == 0) {
								es_cls_sendmail::es_sendmail("welcome", $template = 0, $subscribers, "welcome", 0);
							}
						}
						$email_saved_single_opt_in = $email_saved_single_opt_in + 1;
					}
				} elseif($action == "ext") {
					$email_already_exists = $email_already_exists + 1;
				}
			}
		}

		if($email_saved_double_opt_in > 0) {
			$sts = "double_opt_in_saved";
		} elseif($email_saved_single_opt_in > 0) {
			$sts = "single_opt_in_saved";
		} elseif($email_already_exists > 0) {
			$sts = "emails_already_exists";
		} else {
			$sts = "no_email_saved";
		}
		return $sts;
	}

	public static function es_af_formdisplay($form_setting = array()) {
		$es_af = "";
		$es_af_alt_nm = '';
		$es_af_alt_em = '';
		$es_af_alt_gp = '';
		$es_af_alt_success = '';
		$es_af_alt_techerror = '';
		$es_af_error = false;

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( !is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
			$es_af = _e('This is a add-on plugin for Email Subscribers plugin. Please note that this plugin works only if you have activated Email Subscribers plugin.', ES_AF_TDOMAIN);
			return $es_af;
		}

		if( count($form_setting) == 0 ) {
			return $es_af;
		} else {
			$es_af_title 		= $form_setting['es_af_title'];
			$es_af_desc			= $form_setting['es_af_desc'];
			$es_af_name			= $form_setting['es_af_name'];
			$es_af_name_mand	= $form_setting['es_af_name_mand'];
			$es_af_email		= $form_setting['es_af_email'];
			$es_af_email_mand	= $form_setting['es_af_email_mand'];
			$es_af_group		= $form_setting['es_af_group'];
			$es_af_group_mand	= $form_setting['es_af_group_mand'];
			$es_af_group_list	= $form_setting['es_af_group_list'];
		}

		if ( isset( $_POST['es_af_btn'] ) ) {
			
			check_admin_referer('es_af_form_subscribers','es_af_form_subscribers');

			if( $es_af_name == "YES" ) {
				$es_af_txt_nm = isset($_POST['es_af_txt_nm']) ? $_POST['es_af_txt_nm'] : '';
			} else {
				$es_af_txt_nm = '';
			}

			if( $es_af_email == "YES" ) {
				$es_af_txt_em = isset($_POST['es_af_txt_em']) ? $_POST['es_af_txt_em'] : '';
			}

			if( $es_af_group == "YES" ) {
				if( $es_af_group_mand == "YES" ) {
					$es_af_chk = isset($_POST['es_af_chk']) ? $_POST['es_af_chk'] : '';
				} else {
					$es_af_chk = array();
					$es_af_chk[0] = __( "Public", ES_AF_TDOMAIN );
				}
			} else {
				$es_af_chk = array();
				$es_af_chk[0] = __( "Public", ES_AF_TDOMAIN );
			}

			// Nonce Field
			if ( isset( $_POST['es_af_form_subscribers'] ) ) {
				$es_af_nonce = $_POST['es_af_form_subscribers'];
			}

			if($es_af_name == "YES" && $es_af_name_mand == "YES" && $es_af_txt_nm == "") {
				$es_af_alt_nm = '<span class="es_af_validation">'.__('Please fill in the required field.', ES_AF_TDOMAIN).'</span>';
				$es_af_error = true;
			}

			if($es_af_email == "YES" && $es_af_email_mand == "YES" && $es_af_txt_em == "") {
				$es_af_alt_em = '<span class="es_af_validation">'.__('Please fill in the required field.', ES_AF_TDOMAIN).'</span>';
				$es_af_error = true;
			}

			if (!filter_var($es_af_txt_em, FILTER_VALIDATE_EMAIL) && $es_af_txt_em != "") {
				$es_af_alt_em = '<span class="es_af_validation">'.__('Email address seems invalid.', ES_AF_TDOMAIN).'</span>';
				$es_af_error = true;
			}

			if($es_af_group_mand == "YES" && empty($es_af_chk)) {
				$es_af_alt_gp = '<span class="es_af_validation">'.__('Please select the interested groups.', ES_AF_TDOMAIN).'</span>';
				$es_af_error = true;
			}

			if(!$es_af_error) {
				$homeurl = home_url();
				$samedomain = strpos($_SERVER['HTTP_REFERER'], $homeurl);
				if (($samedomain !== false) && $samedomain < 5) {					
					$sts = es_af_form_submuit::es_af_preparation($es_af_txt_nm, $es_af_txt_em, $es_af_chk, $es_af_nonce);
					if($sts == "double_opt_in_saved") {
						$es_af_alt_success = '<span class="es_af_sent_successfully">'.__('You will receive a confirmation email in few minutes. Please follow the link in it to confirm your subscription.', ES_AF_TDOMAIN).'</span>';
					} elseif($sts == "single_opt_in_saved") {
						$es_af_alt_success = '<span class="es_af_sent_successfully">'.__('Subscribed successfully.', ES_AF_TDOMAIN).'</span>';
					} elseif($sts == "emails_already_exists") {
						$es_af_alt_success = '<span class="es_af_tech_error">'.__('Email already exist.', ES_AF_TDOMAIN).'</span>';
					} elseif($sts == "no_email_saved") {
						$es_af_alt_success = '<span class="es_af_tech_error">'.__('Oops.. Unexpected error occurred 0.', ES_AF_TDOMAIN).'</span>';
					} else {
						$es_af_alt_success = '<span class="es_af_tech_error">'.__('Oops.. Unexpected error occurred 1.', ES_AF_TDOMAIN).'</span>';
					}
				} else {
					$es_af_alt_success = '<span class="es_af_tech_error">'.__('Oops.. Unexpected error occurred 2.', ES_AF_TDOMAIN).'</span>';
				}
			}
		}

		// Compatibility for GDPR
		$active_plugins = (array) get_option('active_plugins', array());
		if (is_multisite()) {
			$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
		}

		$es_af .= '<form method="post" class="esaf-subscribe-form" action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '">';

		if($es_af_desc != "") {
			$es_af .= '<p>';
				$es_af .= '<span class="es_af_short_desc">';
					$es_af .= $es_af_desc;
				$es_af .= '</span>';
			$es_af .= '</p>';
		
		}

		if($es_af_name == "YES") {
			$es_af .= '<p>';
				$es_af .= __('Name', ES_AF_TDOMAIN);
				if($es_af_name_mand == "YES") {
					$es_af .= ' *';
				}
				$es_af .= '<br>';
				$es_af .= '<span class="es_af_css_txt">';
					$es_af .= '<input class="es_af_tb_css" name="es_af_txt_nm" id="es_af_txt_nm" value="" maxlength="225" type="text">';
				$es_af .= '</span>';
				$es_af .= $es_af_alt_nm;
			$es_af .= '</p>';
		}

		if($es_af_email == "YES") {
			$es_af .= '<p>';
				$es_af .= __('Email', ES_AF_TDOMAIN);
				if($es_af_email_mand == "YES") {
					$es_af .= ' *';
				}
				$es_af .= '<br>';
				$es_af .= '<span class="es_af_css_txt">';
					$es_af .= '<input class="es_af_tb_css" name="es_af_txt_em" id="es_af_txt_em" value="" maxlength="225" type="email">';
				$es_af .= '</span>';
				$es_af .= $es_af_alt_em;
			$es_af .=  '</p>';
		}

		if($es_af_group == "YES") {
			$es_af .= '<p>';
				$es_af .= __('Interested groups', ES_AF_TDOMAIN);
				if($es_af_group_mand == "YES") {
					$es_af .= ' *';
				}
				$es_af .= '<br>';
				if($es_af_group_list != "") {
					$groups = explode(',', $es_af_group_list);
					foreach ($groups as $group) {
						$es_af .= '<input type="checkbox" value="'.$group.'" name="es_af_chk[]"> <span class="">'.$group.'</span> <br>';
					}
				} else {
					$es_af .= '<input type="checkbox" value="Public" name="es_af_chk[]"> <span class="">'.__( "Public",ES_AF_TDOMAIN ).'</span> <br>';
				}
				$es_af .= $es_af_alt_gp;
			$es_af .= '</p>';
		}

		if (( in_array('gdpr/gdpr.php', $active_plugins) || array_key_exists('gdpr/gdpr.php', $active_plugins) )) {
			$es_af .= GDPR::get_consent_checkboxes();
		}

		$es_af .= '<p>';
			$es_af = $es_af . '<input class="es_af_bt_css" name="es_af_btn" id="es_af_btn" value="'.__( 'Subscribe', ES_AF_TDOMAIN ).'" type="submit" onClick="resetCheckbox()">';
		$es_af .= '</p>';

		if($es_af_error) {
			$es_af .= '<span class="es_af_validation_full">'.__('Validation errors occurred. Please confirm the fields and submit it again.', ES_AF_TDOMAIN).'</span>';
		} else {
			$es_af .= $es_af_alt_success;
			?>
			<script type="text/javascript">
				function resetCheckbox() {
					document.getElementById("privacy-policy-consent").value = "";
				}
			</script>
			<?php
		}

		$es_af .= wp_nonce_field('es_af_form_subscribers', 'es_af_form_subscribers', true, false);

		$es_af .= '</form>';

		return $es_af;
	}
}

function es_af_shortcode( $atts ) {
	if ( ! is_array( $atts ) ) {
		return '';
	}

	//[email-subscribers-advanced-form id="1"]
	$id = isset($atts['id']) ? $atts['id'] : '0';
	if(!is_numeric($id)) { return "Error in your short code."; }

	$data = array();
	$data = es_af_query::es_af_select($id);
	if(count($data) == 0) {
		$error_notice = _e( 'Error in your shortcode. Record does not exists for this ID.', ES_AF_TDOMAIN );
		return $error_notice;
	}

	$arr = array();
	$arr["es_af_title"] 		= $data[0]['es_af_title'];
	$arr["es_af_desc"] 			= $data[0]['es_af_desc'];
	$arr["es_af_name"] 			= $data[0]['es_af_name'];
	$arr["es_af_name_mand"] 	= $data[0]['es_af_name_mand'];
	$arr["es_af_email"] 		= $data[0]['es_af_email'];
	$arr["es_af_email_mand"] 	= $data[0]['es_af_email_mand'];
	$arr["es_af_group"] 		= $data[0]['es_af_group'];
	$arr["es_af_group_mand"] 	= $data[0]['es_af_group_mand'];
	$arr["es_af_group_list"] 	= $data[0]['es_af_group_list'];
	return es_af_form_submuit::es_af_formdisplay($arr);
}

function  es_af_subbox( $id = "1" ) {
	$arr = array();
	$arr["id"] 	= $id;
	echo es_af_shortcode($arr);
}

class es_af_widget_register extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_text elp-widget', 'description' => __(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN), ES_AF_PLUGIN_NAME);
		parent::__construct(ES_AF_PLUGIN_NAME, __(ES_AF_PLUGIN_DISPLAY, ES_AF_TDOMAIN), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$es_af_title 		= apply_filters( 'widget_title', empty( $instance['es_af_title'] ) ? '' : $instance['es_af_title'], $instance, $this->id_base );
		$es_af_desc			= $instance['es_af_desc'];
		$es_af_name			= $instance['es_af_name'];
		$es_af_name_mand	= $instance['es_af_name_mand'];
		$es_af_email		= $instance['es_af_email'];
		$es_af_email_mand	= $instance['es_af_email_mand'];
		$es_af_group		= $instance['es_af_group'];
		$es_af_group_mand	= $instance['es_af_group_mand'];
		$es_af_group_list	= $instance['es_af_group_list'];

		echo $args['before_widget'];
		if ( !empty( $es_af_title ) ) {
			echo $args['before_title'] . $es_af_title . $args['after_title'];
		}

		$form_setting = array(
			'es_af_title' 		=> $es_af_title,
			'es_af_desc' 		=> $es_af_desc,
			'es_af_name' 		=> $es_af_name,
			'es_af_name_mand' 	=> $es_af_name_mand,
			'es_af_email' 		=> $es_af_email,
			'es_af_email_mand' 	=> $es_af_email_mand,
			'es_af_group' 		=> $es_af_group,
			'es_af_group_mand' 	=> $es_af_group_mand,
			'es_af_group_list' 	=> $es_af_group_list		
		);

		$es_af = es_af_form_submuit::es_af_formdisplay($form_setting);
		echo $es_af;

		echo $args['after_widget'];
	}
	
	function update( $new_instance, $old_instance ) {
		$instance 						= $old_instance;
		$instance['es_af_title'] 		= ( ! empty( $new_instance['es_af_title'] ) ) ? strip_tags( $new_instance['es_af_title'] ) : '';
		$instance['es_af_desc'] 		= ( ! empty( $new_instance['es_af_desc'] ) ) ? strip_tags( $new_instance['es_af_desc'] ) : '';
		$instance['es_af_name'] 		= ( ! empty( $new_instance['es_af_name'] ) ) ? strip_tags( $new_instance['es_af_name'] ) : '';
		$instance['es_af_name_mand'] 	= ( ! empty( $new_instance['es_af_name_mand'] ) ) ? strip_tags( $new_instance['es_af_name_mand'] ) : '';
		$instance['es_af_email'] 		= ( ! empty( $new_instance['es_af_email'] ) ) ? strip_tags( $new_instance['es_af_email'] ) : '';
		$instance['es_af_email_mand'] 	= ( ! empty( $new_instance['es_af_email_mand'] ) ) ? strip_tags( $new_instance['es_af_email_mand'] ) : '';
		$instance['es_af_group'] 		= ( ! empty( $new_instance['es_af_group'] ) ) ? strip_tags( $new_instance['es_af_group'] ) : '';
		$instance['es_af_group_mand'] 	= ( ! empty( $new_instance['es_af_group_mand'] ) ) ? strip_tags( $new_instance['es_af_group_mand'] ) : '';
		$instance['es_af_group_list'] 	= ( ! empty( $new_instance['es_af_group_list'] ) ) ? strip_tags( $new_instance['es_af_group_list'] ) : '';
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'es_af_title' 		=> '',
			'es_af_desc' 		=> '',
			'es_af_name' 		=> '',
			'es_af_name_mand' 	=> '',
			'es_af_email' 		=> '',
			'es_af_email_mand' 	=> '',
			'es_af_group' 		=> '',
			'es_af_group_mand' 	=> '',
			'es_af_group_list' 	=> ''
		);

		$instance 			= wp_parse_args( (array) $instance, $defaults);
		$es_af_title 		= $instance['es_af_title'];
		$es_af_desc 		= $instance['es_af_desc'];
		$es_af_name 		= $instance['es_af_name'];
		$es_af_name_mand 	= $instance['es_af_name_mand'];
		$es_af_email 		= $instance['es_af_email'];
		$es_af_email_mand 	= $instance['es_af_email_mand'];
		$es_af_group 		= $instance['es_af_group'];
		$es_af_group_mand 	= $instance['es_af_group_mand'];
		$es_af_group_list 	= $instance['es_af_group_list'];
		?>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_title'); ?>"><?php echo __( 'Widget Title', ES_AF_TDOMAIN ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_title'); ?>" name="<?php echo $this->get_field_name('es_af_title'); ?>" type="text" value="<?php echo $es_af_title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_desc'); ?>"><?php echo __( 'Short description about form', ES_AF_TDOMAIN ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_desc'); ?>" name="<?php echo $this->get_field_name('es_af_desc'); ?>" type="text" value="<?php echo $es_af_desc; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_name'); ?>"><?php echo __( 'Display NAME field?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_name'); ?>" name="<?php echo $this->get_field_name('es_af_name'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_name == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
				<option value="NO" <?php $this->es_af_selected($es_af_name == 'NO'); ?>><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_name_mand'); ?>"><?php echo __( 'Make NAME field Mandatory?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_name_mand'); ?>" name="<?php echo $this->get_field_name('es_af_name_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_name_mand == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
				<option value="NO" <?php $this->es_af_selected($es_af_name_mand == 'NO'); ?>><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_email'); ?>"><?php echo __( 'Display EMAIL field?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_email'); ?>" name="<?php echo $this->get_field_name('es_af_email'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_email == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_email_mand'); ?>"><?php echo __( 'Make EMAIL field Mandatory?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_email_mand'); ?>" name="<?php echo $this->get_field_name('es_af_email_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_email_mand == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group'); ?>"><?php echo __( 'Allow GROUP selection from form?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_group'); ?>" name="<?php echo $this->get_field_name('es_af_group'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_group == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
				<option value="NO" <?php $this->es_af_selected($es_af_group == 'NO'); ?>><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group_mand'); ?>"><?php echo __( 'Make GROUP selection Mandatory?', ES_AF_TDOMAIN ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id('es_af_group_mand'); ?>" name="<?php echo $this->get_field_name('es_af_group_mand'); ?>">
				<option value="YES" <?php $this->es_af_selected($es_af_group_mand == 'YES'); ?>><?php echo __( 'YES', ES_AF_TDOMAIN ); ?></option>
				<option value="NO" <?php $this->es_af_selected($es_af_group_mand == 'NO'); ?>><?php echo __( 'NO', ES_AF_TDOMAIN ); ?></option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('es_af_group_list'); ?>"><?php echo __( 'GROUP names to display (coma separated values)', ES_AF_TDOMAIN ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('es_af_group_list'); ?>" name="<?php echo $this->get_field_name('es_af_group_list'); ?>" type="text" value="<?php echo $es_af_group_list; ?>" />
			<?php
			if ( is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
				$groups = array();
				$groups = es_cls_dbquery::es_view_subscriber_group();
				if(count($groups) > 0) {
					$i = 1;
					foreach ($groups as $group) {
						if($i != 1) {
							echo ",";
						} else {
							echo __( "<br>Existing Groups : ", ES_AF_TDOMAIN );
						}
						echo $group["es_email_group"];
						$i = $i +1;
					}
				}
			}
			?>
		</p>
		<?php
	}

	function es_af_selected($var) {
		if ($var==1 || $var==true) {
			echo 'selected="selected"';
		}
	}

}

class es_af_intermediate {
	public static function es_af_advancedform() {
		$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
		switch($current_page) {
			case 'add':
				require_once(ES_AF_DIR.'includes/form'.DS.'es-af-add.php');
				break;
			case 'edit':
				require_once(ES_AF_DIR.'includes/form'.DS.'es-af-edit.php');
				break;
			default:
				require_once(ES_AF_DIR.'includes/form'.DS.'es-af-show.php');
				break;
		}
	}
}
