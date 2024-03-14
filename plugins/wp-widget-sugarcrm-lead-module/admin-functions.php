<?php
if (isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
	exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

## Save custom label - START
add_action('wp_ajax_WP2SL_save_custom_label', 'WP2SL_save_custom_label_callback');
function WP2SL_save_custom_label_callback() {
	global $wpdb;
	wp_unslash($_POST);

	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'my_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	$upd = $wpdb->update(
		OEPL_TBL_MAP_FIELDS, 
		array('wp_custom_label' => sanitize_text_field($_POST['label'])), 
		array('pid' => (int)$_POST['pid'])
	);

	$response = array();
	if ($upd != false && $upd > 0) {
		$response['Message'] = __('Custom Label changed succesfully', 'WP2SL');
	} else {
		$response['Message'] = __('Error occured while saving custom label. Please try again', 'WP2SL');
	}
	wp_send_json($response);
}
## Save custom label - END

## Save custom order - START
add_action('wp_ajax_WP2SL_save_custom_order', 'WP2SL_save_custom_order_callback');
function WP2SL_save_custom_order_callback() {
	global $wpdb;
	wp_unslash($_POST);
	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'my_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	if (isset($_POST['pid']) && isset($_POST['label'])) {
		$upd = $wpdb->update(
			OEPL_TBL_MAP_FIELDS, 
			array('display_order' => (int)$_POST['label']), 
			array('pid' => (int)$_POST['pid'])
		);

		$response = array();
		if ($upd != false && $upd > 0) {
			$response['Message'] = __('Display order changed succesfully', 'WP2SL');
		} else {
			$response['Message'] = __('Error occured while saving display order value. Please try again', 'WP2SL');
		}
		wp_send_json($response);
	}
}
## Save custom order - END

## Change field status - START
add_action('wp_ajax_WP2SL_Grid_Ajax_Action', 'WP2SL_Grid_Ajax_Action_callback');
function WP2SL_Grid_Ajax_Action_callback() {
	global $wpdb;
	wp_unslash($_POST);
	if (isset($_POST['OEPL_Action']) && isset($_POST['pid'])) {
		$action = sanitize_text_field($_POST['OEPL_Action']);

		$updData = array();
		$flag = false;
		if ($action === 'OEPL_Change_Status') {
			$flag = true;
			$sql = $wpdb->prepare("SELECT is_show FROM " . OEPL_TBL_MAP_FIELDS . " WHERE pid='%d'", (int)$_POST['pid']);
			$status = $wpdb->get_row($sql, ARRAY_A);
			if (isset($status['is_show']) && !empty($status)) {
				$updData['is_show'] = ($status['is_show'] === 'Y') ? 'N' : 'Y';
			}
		} else if ($action === 'OEPL_Change_Hidden_Status') {
			$flag = true;
			$sql = $wpdb->prepare("SELECT hidden FROM " . OEPL_TBL_MAP_FIELDS . " WHERE pid='%d'", (int)$_POST['pid']);
			$status = $wpdb->get_row($sql, ARRAY_A);
			if (isset($status['hidden']) && !empty($status)) {
				$updData['hidden'] = ($status['hidden'] === 'Y') ? 'N' : 'Y';
			}
		} else if ($action === 'OEPL_Change_Required_Status') {
			$flag = true;
			$sql = $wpdb->prepare("SELECT required FROM " . OEPL_TBL_MAP_FIELDS . " WHERE pid='%d'", (int)$_POST['pid']);
			$status = $wpdb->get_row($sql, ARRAY_A);
			if (isset($status['required']) && !empty($status)) {
				$updData['required'] = ($status['required'] === 'Y') ? 'N' : 'Y';
			}
		} else if ($action === 'OEPL_Change_Hidden_Status_Val') {
			$flag = true;
			$updData['hidden_field_value'] = sanitize_text_field($_POST['hidden_field_value']);
		}
		$respone = array();
		if ($flag && isset($updData) && !empty($updData)) {
			$upd = $wpdb->update(OEPL_TBL_MAP_FIELDS, $updData, array('pid' => (int)$_POST['pid']));
			if ($upd != false && $upd > 0) {
				$respone['message'] = __('Field Updated Successfully', 'WP2SL');
			} else {
				$respone['message'] = __('Error While updating field. Please try again', 'WP2SL');
			}
		} else {
			$respone['message'] = __('Error While updating field. Please try again', 'WP2SL');
		}
		wp_send_json($respone);
	}
}
## Change field status - END

### Plugin Update database changes Logic START
add_action('plugins_loaded', 'WP2SL_plugin_update_function');
function WP2SL_plugin_update_function() {
	global $OEPL_update_version, $wpdb;
	$OEPL_current_version = get_option("OEPL_PLUGIN_VERSION");
	if ($OEPL_current_version != $OEPL_update_version) {
		$sql = 'SHOW COLUMNS FROM ' . OEPL_TBL_MAP_FIELDS;
		$rows = $wpdb->get_col($sql);
		if (!in_array('wp_custom_label', $rows)) {
			$wpdb->query('ALTER TABLE ' . OEPL_TBL_MAP_FIELDS . ' ADD `wp_custom_label` VARCHAR( 50 ) NOT NULL AFTER `wp_meta_label`');
		}
		if (!in_array('required', $rows)) {
			$wpdb->query("ALTER TABLE " . OEPL_TBL_MAP_FIELDS . " ADD `required` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N' AFTER `display_order`");
		}
		if (!in_array('hidden', $rows)) {
			$wpdb->query("ALTER TABLE " . OEPL_TBL_MAP_FIELDS . " ADD `hidden` ENUM('Y','N') NOT NULL DEFAULT 'N' AFTER `required`;");
		}
		if (!in_array('custom_field', $rows)) {
			$wpdb->query("ALTER TABLE " . OEPL_TBL_MAP_FIELDS . " ADD `custom_field` ENUM( 'Y', 'N' ) NOT NULL DEFAULT 'N';");
		}
		update_option('OEPL_PLUGIN_VERSION', $OEPL_update_version);
	}
}
### Plugin Update database changes Logic END

### Change Wordpress Default Upload dir START
function WP2SL_Change_Upload_Dir($upload) {
	$upload['subdir']	= OEPL_FILE_UPLOAD_FOLDER;
	$upload['path']		= $upload['basedir'] . $upload['subdir'];
	$upload['url']		= $upload['baseurl'] . $upload['subdir'];
	return $upload;
}
### Change Wordpress Default Upload dir END

## Front end Save Function
add_action('wp_ajax_WidgetForm', 'WP2SL_WidgetForm');
add_action('wp_ajax_nopriv_WidgetForm', 'WP2SL_WidgetForm');
function WP2SL_WidgetForm() {
	global $objSugar, $wpdb;
	wp_unslash($_POST);
	if (isset($_POST['_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['_nonce']), 'upload_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	$response = array();
	$captchaSettings = get_option('OEPL_Captcha_status');
	$successMsg = get_option('OEPL_SugarCRMSuccessMessage');
	$failureMsg = get_option('OEPL_SugarCRMFailureMessage');
	$EmailNotification	= get_option('OEPL_Email_Notification');
	$wp2sl_sel_captcha = get_option('OEPL_Select_Captcha');
	
	$flag = TRUE;
	if($captchaSettings === 'Y'){
		if($wp2sl_sel_captcha === 'google') {
			if((isset($_POST['g-recaptcha-response']) && empty($_POST['g-recaptcha-response']))){
				$response['message'] = get_option('OEPL_SugarCRMInvalidCaptchaMessage');
				$response['redirectStatus'] = 'N';
				$response['success'] = 'N';
				$flag = FALSE;
			}
		} else {
			$wp2sl_captcha = get_transient('wp2sl_captcha');

			if (isset($_POST['captcha']) && $_POST['captcha'] != $wp2sl_captcha){
				$response['message'] = get_option('OEPL_SugarCRMInvalidCaptchaMessage');
				$response['redirectStatus'] = 'N';
				$response['success'] = 'N';
				$flag = FALSE;
			}
		}
	}

	if($flag) {
		if (!function_exists('wp_handle_upload')) 
			require_once(ABSPATH . 'wp-admin/includes/file.php');

		$FileArray = array();
		if (count($_FILES) > 0) {
			add_filter('upload_dir', 'WP2SL_Change_Upload_Dir');
			foreach ($_FILES as $key => $file) {
				$upload_overrides = array('test_form' => false);
				$movefile = wp_handle_upload($file, $upload_overrides);
				$response['message'] = '';
				if ($movefile) {
					if (!empty($movefile['error']) && $movefile['error']) {
						$arr = array('br' => array());
						
						$response['message'] .= __("Sorry! ", "WP2SL") . $file['name'] . wp_kses(__(" could not be uploaded due to security reasons.<br>", "WP2SL"), $arr);

					} else {
						$movefile['name'] = $file['name'];
						$FileArray[] = $movefile;
					}
				} else {
					$arr = array('br' => array());
					$response['message'] .= wp_kses(__("Error occurred while uploading file. Please try again<br>", "WP2SL"), $arr);
				}
			}
			remove_filter('upload_dir', 'WP2SL_Change_Upload_Dir');
		}

		$InsertLeadToSugar = $objSugar->InsertLeadToSugar($FileArray);

		if ($InsertLeadToSugar != false) {
			if ($EmailNotification && $EmailNotification === "Y") {
				$emailTo = get_option('OEPL_Email_Notification_Receiver');
				$query = $wpdb->prepare("SELECT custom_field, wp_meta_key, wp_meta_label FROM " . OEPL_TBL_MAP_FIELDS . " WHERE is_show='%s' ORDER BY custom_field DESC,display_order", 'Y');
				$RS = $wpdb->get_results($query, ARRAY_A);
				$message = '<h3>Lead Description</h3><table border="0">';
				$is_attach_avail = 'N';
				foreach ($RS as $attr) {
					if ($attr['custom_field'] != 'Y' && isset($_POST[$attr['wp_meta_key']])) {
						$message .= '<tr><th>';
						$message .= $attr['wp_meta_label'] . " : </th><td>" . sanitize_text_field($_POST[$attr['wp_meta_key']]) . "<br />";
						$message .= '</td></tr>';
					} else {
						$is_attach_avail = 'Y';
					}
				}
				if ($is_attach_avail === 'Y') {
					$message .= '<tr><th>';
					$message .= "Attachments</th><td> : All attachments are available in your SugarCRM history/notes module";
					$message .= '</td></tr>';
				}
				$message .= "</table>";
				$Subject = "Lead generated from " . get_bloginfo('name') . "";
				add_filter('wp_mail_content_type', 'WP2SL_set_mail_contenttype');
				wp_mail($emailTo, $Subject, $message);
				remove_filter('wp_mail_content_type', 'WP2SL_set_mail_contenttype');
			}
			$redirectStatus	= get_option("OEPL_User_Redirect_Status");
			$redirectTo		= get_option("OEPL_User_Redirect_To");

			if ($redirectStatus === 'Y') {
				$response['redirectStatus'] = 'Y';
				$response['redirectTo']	= $redirectTo;
				$response['success'] = 'Y';
			} else {
				$response['redirectStatus'] = 'N';
				$response['message'] = $successMsg;
				$response['success'] = 'Y';
			}
		} else {
			$response['redirectStatus'] = 'N';
			$response['message'] = $failureMsg;
			$response['success'] = 'N';
		}
	}
	delete_transient('wp2sl_captcha');
	wp_send_json($response);
}
## Front end Save Function End

## Set mail content type - START
function WP2SL_set_mail_contenttype() {
	return "text/html";
}
## Set mail content type - END

## Save sugarCRM config START
add_action('wp_ajax_WP2SL_saveConfig', 'WP2SL_saveConfig');
function WP2SL_saveConfig() {
	wp_unslash($_POST);
	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'upload_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	$TestConn = new WP2SLSugarCRMClass;

	if (isset($_POST['SugarURL']) && isset($_POST['SugarUser']) && isset($_POST['SugarPass'])) {
		$TestConn->SugarURL  = sanitize_text_field($_POST['SugarURL']);
		$TestConn->SugarUser = sanitize_user($_POST['SugarUser']);
		$TestConn->SugarPass = sanitize_text_field(md5($_POST['SugarPass']));
		if (isset($_POST['isHtaccessProtected']) && $_POST['isHtaccessProtected'] === 'Y' && isset($_POST['HtaccessUser']) && isset($_POST['HtaccessPass'])) {
			$TestConn->isHtaccessProtected = TRUE;
			$TestConn->HtaccessAdminUser = sanitize_user($_POST['HtaccessUser']);
			$TestConn->HtaccessAdminPass = sanitize_text_field($_POST['HtaccessPass']);
		}
	}

	$t = $TestConn->LoginToSugar();
	if (strlen($t) > 10 && isset($_POST['SugarURL']) && isset($_POST['SugarUser']) && isset($_POST['SugarPass'])) {
		update_option('OEPL_SUGARCRM_URL', sanitize_text_field($_POST['SugarURL']));
		update_option('OEPL_SUGARCRM_ADMIN_USER', sanitize_user($_POST['SugarUser']));
		update_option('OEPL_SUGARCRM_ADMIN_PASS', sanitize_text_field(md5($_POST['SugarPass'])));

		if (isset($_POST['isHtaccessProtected']) && $_POST['isHtaccessProtected'] === 'Y' && isset($_POST['HtaccessUser']) && isset($_POST['HtaccessPass'])) {
			update_option('OEPL_is_SugarCRM_htaccess_Protected', 'Y');
			update_option('OEPL_SugarCRM_htaccess_Username', sanitize_user($_POST['HtaccessUser']));
			update_option('OEPL_SugarCRM_htaccess_Password', sanitize_text_field($_POST['HtaccessPass']));
		} else {
			update_option('OEPL_is_SugarCRM_htaccess_Protected', 'N');
			delete_option('OEPL_SugarCRM_htaccess_Username');
			delete_option('OEPL_SugarCRM_htaccess_Password');
		}

		$response['status'] = 'Y';
		$response['message'] = __('SugarCRM credentials saved successfully', 'WP2SL');
	} else {
		$response['status'] = 'N';
		$response['message'] = __('Invalid SugarCRM credentials. Please try again', 'WP2SL');
	}
	wp_send_json($response);
}
## Save sugarCRM config END

##Lead fileds Sync function START
add_action('wp_ajax_WP2SL_LeadFieldSync', 'WP2SL_LeadFieldSync');
function WP2SL_LeadFieldSync() {
	global $objSugar;
	$t = $objSugar->LoginToSugar();
	if (!strlen($t) > 10) {
		$response['status'] = 'N';
		$response['message'] = __('Error occured while synchronizing fields. Please try again.', 'WP2SL');
	} else {
		WP2SL_FieldSynchronize();
		$response['status'] = 'Y';
		$response['message'] = __('Fields synchronized successfully', 'WP2SL');
	}
	wp_send_json($response);
}
##Lead fileds Sync function END

##General message save function START
add_action('wp_ajax_WP2SL_GeneralMessagesSave', 'WP2SL_GeneralMessagesSave');
function WP2SL_GeneralMessagesSave() {
	wp_unslash($_POST);
	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'upload_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	if (!empty($_POST)) {
		update_option("OEPL_SugarCRMSuccessMessage", sanitize_text_field($_POST['SuccessMessage']));
		update_option("OEPL_SugarCRMFailureMessage", sanitize_text_field($_POST['FailureMessage']));
		update_option("OEPL_SugarCRMReqFieldsMessage", sanitize_text_field($_POST['ReqFieldsMessage']));
		update_option("OEPL_SugarCRMInvalidCaptchaMessage", sanitize_text_field($_POST['InvalidCaptchaMessage']));

		$response['status'] = 'Y';
		$response['message'] = __('General Messages saved successfully', 'WP2SL');
	} else {
		$response['status'] = 'N';
		$response['message'] = __('Error occured while saving General Messages. Please try again.', 'WP2SL');
	}
	wp_send_json($response);
}

##Custom css save function START
add_action("wp_ajax_WP2SL_save_custom_css", "WP2SL_save_custom_css");
function WP2SL_save_custom_css() {
	wp_unslash($_POST);
	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'upload_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	if (!empty($_POST)) {
		update_option("OEPL_Form_Custom_CSS", sanitize_text_field($_POST['css']));
		$response['status'] = 'Y';
		$response['message'] = __('Custom CSS saved successfully', 'WP2SL');
	} else {
		$response['status'] = 'N';
		$response['message'] = __('Error occured while saving Custom CSS. Please try again.', 'WP2SL');
	}
	wp_send_json($response);
}
##Custom css save function END

##General settings save function START
add_action('wp_ajax_WP2SL_GeneralSettingSave', 'WP2SL_GeneralSettingSave');
function WP2SL_GeneralSettingSave() {
	wp_unslash($_POST);
	if (isset($_POST['oepl_nonce']) && !wp_verify_nonce(sanitize_text_field($_POST['oepl_nonce']), 'upload_thumb')) {
		wp_die(esc_html__('Security check!', 'WP2SL'), 'Error', array('back_link' => true));
	}

	if (!empty($_POST)) {
		update_option("OEPL_auto_IP_addr_status", sanitize_text_field($_POST['IPaddrStatus']));
		update_option("OEPL_Email_Notification", sanitize_text_field($_POST['EmailNotification']));
		update_option("OEPL_Email_Notification_Receiver", sanitize_text_field($_POST['EmailReceiver']));
		update_option("OEPL_Captcha_status", sanitize_text_field($_POST['catpchaStatus']));
		update_option("OEPL_Select_Captcha", sanitize_text_field($_POST['selectcaptcha']));

		update_option("OEPL_User_Redirect_Status", sanitize_text_field($_POST['redirectStatus']));
		update_option("OEPL_User_Redirect_To", sanitize_text_field($_POST['redirectTo']));

		if (!empty($_POST['oepl_recaptcha_site_key']) && !empty($_POST['oepl_recaptcha_secret_key'])) {
			update_option('OEPL_RECAPTCHA_SITE_KEY', sanitize_text_field($_POST['oepl_recaptcha_site_key']));
			update_option('OEPL_RECAPTCHA_SECRET_KEY', sanitize_text_field($_POST['oepl_recaptcha_secret_key']));
		}

		$response['status'] = 'Y';
		$response['message'] = __('Plugin General Settings saved successfully', 'WP2SL');
	} else {
		$response['status'] = 'N';
		$response['message'] = __('Error occured while saving Plugin General Settings. Please try again.', 'WP2SL');
	}
	wp_send_json($response);
}
##General settings save function END

## Save Custom Browse field START
add_action("wp_ajax_WP2SL_Custom_Field_Save", "WP2SL_Custom_Field_Save");
function WP2SL_Custom_Field_Save() {
	global $wpdb;
	if (isset($_POST['Field_Name'])) {
		$FieldName = sanitize_text_field($_POST['Field_Name']);
		$FieldName = str_replace(' ', '_', sanitize_text_field($FieldName));
	}

	$query = $wpdb->prepare("SELECT pid FROM " . OEPL_TBL_MAP_FIELDS . " WHERE wp_meta_label='%s'", $FieldName);
	$RS = $wpdb->get_results($query);

	if (count($RS) > 0) {
		 esc_html_e("Duplicate field already exist. Please try with a different field name.", "WP2SL");
	} else {
		$insArray = array(
			'module'			=> 'OEPL',
			'field_type'		=> 'file',
			'data_type'			=> 'file',
			'wp_meta_label' 	=> $FieldName,
			'is_show'			=> 'Y',
			'custom_field' 		=> 'Y'
		);
		$insert = $wpdb->insert(OEPL_TBL_MAP_FIELDS, $insArray);
		if ($insert != false) {
			$where = array('pid' => $wpdb->insert_id);
			$updArray = array(
				'field_name' 	=> 'oepl_browse_' . $wpdb->insert_id,
				'wp_meta_key' 	=> 'oepl_browse_' . $wpdb->insert_id
			);
			$update = $wpdb->update(OEPL_TBL_MAP_FIELDS, $updArray, $where);
			if ($update != false) {
				echo esc_html__("Field added successfully", 'WP2SL');
			} else {
				echo esc_html__("Error occured. Please try again", 'WP2SL');
			}
		} else {
			echo esc_html__('Problem adding field. Please try again', 'WP2SL');
		}
	}
	wp_die();
}
## Save Custom Browse field END

## DELETE custom browse field START
add_action("wp_ajax_WP2SL_Custom_Field_Delete", "WP2SL_Custom_Field_Delete");
function WP2SL_Custom_Field_Delete() {
	global $wpdb;
	wp_unslash($_POST);
	if (isset($_POST['pid'])) {
		$pid = sanitize_text_field($_POST['pid']);
		$where = array('pid' => $pid);
		$delete = $wpdb->delete(OEPL_TBL_MAP_FIELDS, $where);
		if ($delete != false) {
			echo esc_html__("Field deleted successfully", 'WP2SL');
		} else {
			echo esc_html__("Error occured ! Please try again", 'WP2SL');
		}
	}
	wp_die();
}
## DELETE custom browse field END

##Submenu under SugarCRM Menu START
function WP2SL_SugarCRM_Submenu_function() {
	
?>
	<div class="wrap">
		<div class="wp2sl_browse_div_cls">
			<h1><?php esc_html_e("SugarCRM Lead module field list", "WP2SL"); ?></h1>
			<table class="OEPL_add_field_box">
				<tr height="25" class="OEPL_hide_panel" is_show="No">
					<td><img src="<?php echo OEPL_PLUGIN_URL . 'image/plus-icon.png' ?>" valign="center" /></td>
					<td colspan="4" class="cstm_browse_btn" valign="center"><?php esc_html_e("Add Custom Browse Field", "WP2SL"); ?></td>
				</tr>
				<tr class="OEPL_hidden_panel">
					<td></td>
					<td width="80"><?php esc_html_e("Field name :", "WP2SL"); ?> </td>
					<td width="80"><input type="text" id="OEPL_Custom_Field_Name" name="OEPL_Custom_Field_Name" /></td>
					<td align="left"><button class="button button-primary OEPL_Custom_Field_Add"><?php esc_html_e("Add Field", "WP2SL"); ?></button></td>
				</tr>
				<tr class="OEPL_hidden_panel">
					<td></td>
					<td colspan="4"><span class="description"><strong><?php esc_html_e("Note:", "WP2SL"); ?></strong> <?php esc_html_e("Uploaded files will be available in Notes module of SugarCRM and History subpanel of Lead module.", "WP2SL"); ?></span></td>
				</tr>
			</table>
		</div>
		<div class="OEPL_Vertical_Banner">

			<form id="OEPL-Leads_table" method="post">
			<?php
			require_once(OEPL_PLUGIN_DIR . 'Fields_map_table.php');
			$table = new Fields_Map_Table;
			echo "<input type='hidden' id='oepl_nonce' value='" . wp_create_nonce('my_thumb') . "' name='oepl_nonce' />";
			echo '<input type="hidden" name="page" value="mapping_table" />';
			$table->search_box('Search', 'LeadSearchID');
			$table->prepare_items();
			$table->display();
			echo '</form>';
			echo "</div>";
}

##Submenu under SugarCRM Menu END
function WP2SL_FieldSynchronize() {
	global $objSugar, $wpdb;

	if ($objSugar->SugarSessID === '') {
		$objSugar->LoginToSugar();
	}
	if (!strlen($objSugar->SugarSessID) > 10) {
		return false;
	}

	## Start - Set Module Fields in Table
	foreach ($objSugar->ModuleList as $key => $val) {
		$ModuleName = $val;
		$ModuleFileds = $objSugar->getLeadFieldsList();
		$SugarFlds = array();

		if (is_object($ModuleFileds->module_fields) && count((array)$ModuleFileds->module_fields) > 0) {
			foreach ($ModuleFileds->module_fields as $fkey => $fval) {
				$fType = $fval->type;
				$insAry = array();
				switch ($fType) {
					case 'enum':
						$insAry['field_type']  = 'select';
						$insAry['field_value'] = serialize($fval->options);
						break;
					case 'radioenum':
						$insAry['field_type']  = 'radio';
						$insAry['field_value'] = serialize($fval->options);
						break;
					case 'bool':
						$insAry['field_type']  = 'checkbox';
						$insAry['field_value'] = serialize($fval->options);
						break;
					case 'text':
						$insAry['field_type'] 	= 'textarea';
						$insAry['field_value'] 	= '';
						break;
					case 'file':
						$insAry['field_type'] 	= 'file';
						$insAry['field_value'] 	= '';
						break;
					default:
						$insAry['field_type']  = 'text';
						$insAry['field_value'] = '';
						break;
				}
				$insAry['module'] 		 = $ModuleName;
				$insAry['field_name'] 	 = $fkey;
				$insAry['wp_meta_key'] 	 = OEPL_METAKEY_EXT . strtolower($ModuleName) . '_' . $fkey;
				$insAry['wp_meta_label'] = $fval->label;
				$insAry['data_type'] 	 = $fval->type;
				$insAry['wp_meta_label'] = str_replace(':', '', trim($insAry['wp_meta_label']));

				$query = $wpdb->prepare("SELECT count(*) as tot FROM " . OEPL_TBL_MAP_FIELDS . " WHERE module='%s' AND field_name='%s'", $insAry['module'], $insAry['field_name']);
				$RecCount = $wpdb->get_results($query, ARRAY_A);

				if (!in_array($insAry['field_name'], $objSugar->ExcludeFields)) {
					$SugarFlds[] = $insAry['field_name'];
					if ($RecCount[0]['tot'] <= 0) {
						$sql = "INSERT INTO " . OEPL_TBL_MAP_FIELDS . " SET 
						module 		  = '" . $insAry['module'] . "' , 
						field_type 	  = '" . $insAry['field_type'] . "' , 
						data_type 	  = '" . $insAry['data_type'] . "' , 
						field_name 	  = '" . $insAry['field_name'] . "' , 
						field_value   = '" . $insAry['field_value'] . "' , 
						wp_meta_label = '" . $insAry['wp_meta_label'] . "' , 
						wp_meta_key   = '" . $insAry['wp_meta_key'] . "' ";
						$wpdb->query($sql);
					} else {
						$sql = "UPDATE " . OEPL_TBL_MAP_FIELDS . " SET 
							module 		  = '" . $insAry['module'] . "' , 
							field_type 	  = '" . $insAry['field_type'] . "' , 
							data_type 	  = '" . $insAry['data_type'] . "' , 
							field_name 	  = '" . $insAry['field_name'] . "' , 
							field_value   = '" . $insAry['field_value'] . "' , 
							wp_meta_label = '" . $insAry['wp_meta_label'] . "' , 
							wp_meta_key   = '" . $insAry['wp_meta_key'] . "' 
					WHERE module = '" . $insAry['module'] . "' AND field_name = '" . $insAry['field_name'] . "'";
						$wpdb->query($sql);
					}
				}
			}
		}

		$query = $wpdb->prepare("SELECT pid, field_name, wp_meta_key FROM " . OEPL_TBL_MAP_FIELDS . " WHERE module='%s'", $ModuleName);
		$WPFieldsRS = $wpdb->get_results($query, ARRAY_A);
		$fcnt = count($WPFieldsRS);
		for ($i = 0; $i < $fcnt; $i++) {
			if (!in_array($WPFieldsRS[$i]['field_name'], $SugarFlds)) {
				$delSql = $wpdb->prepare("DELETE FROM " . OEPL_TBL_MAP_FIELDS . " WHERE pid ='%d' AND module='%s'", $WPFieldsRS[$i]['pid'], $ModuleName);
				$wpdb->query($delSql);
			}
		}
	}
	## End - Set Module Fields in Table
}

## Test CRM connection - START
add_action('wp_ajax_WP2SL_TestSugarConn', 'WP2SL_TestSugarConn');
function WP2SL_TestSugarConn() {
	$TestConn = new WP2SLSugarCRMClass;
	if (isset($_POST['URL']) && isset($_POST['USER']) && isset($_POST['PASS'])) {
		$TestConn->SugarURL  = sanitize_text_field($_POST['URL']);
		$TestConn->SugarUser = sanitize_user($_POST['USER']);
		$TestConn->SugarPass = sanitize_text_field(md5($_POST['PASS']));

		if ($_POST['isHtaccessProtected'] === 'Y' && isset($_POST['HtaccessUser']) && isset($_POST['HtaccessPass'])) {
			$TestConn->isHtaccessProtected = TRUE;
			$TestConn->HtaccessAdminUser = sanitize_user($_POST['HtaccessUser']);
			$TestConn->HtaccessAdminPass = sanitize_text_field($_POST['HtaccessPass']);
		}
	}

	$t = $TestConn->LoginToSugar();
	if (strlen($t) > 10) {
		$response['status'] = 'Y';
		$response['message'] = __('Connection Established Successfully', 'WP2SL');
	} else {
		$response['status'] = 'N';
		$response['message'] = __('Cannot connect to your SugarCRM. Please try again with correct SugarCRM credentials', 'WP2SL');
	}
	wp_send_json($response);
}
## Test CRM connection - END