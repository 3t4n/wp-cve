<?php

if (!defined('ABSPATH'))
    exit;

class zcfajaxcore {

    public static function zcfforms_ajax_events() {
        $ajax_actions = array(
            'zcfchooseplugin' => false,
            'zcfSaveCRMconfig' => false,
            'zcfsaveSyncValue' => 'false',
            'zcfsend_mapping_configuration' => 'false',
            'zcfnewlead_form' => 'false',
            'zcf_get_contactform_fields' => 'false',
            'zcf_map_contactform_fields' => 'false',
            'zcf_save_contact_form_title' => 'false',
            'zcf_send_mapped_config' => 'false',
            'zcf_delete_mapped_config' => 'false',
            'zcfcaptcha_info' => 'false',
            'zcf_save_usersync_option' => 'false',
            'zcf_change_menu_order' => 'false',
            'send_order_info' => 'false',
            'zcfmainFormsActions' => 'false',
        );
        foreach ($ajax_actions as $action => $value) {
            add_action('wp_ajax_' . $action, array(__CLASS__, $action));
        }
    }

    public static function zcfchooseplugin() {

        $selectedPlugin = 'crmformswpbuilder';
        update_option('ZCFFormBuilderPluginActivated', $selectedPlugin);
        require_once(ZCF_BASE_DIR_URI . "includes/form-zohocrmconfig.php");
        die;
    }

    public static function zcfnewlead_form() {
      zcf_validate_general_nonce();
      $action = sanitize_text_field($_REQUEST['action']);
      $user = wp_get_current_user();
      $allowed_roles = array( 'editor', 'administrator', 'author' );
      if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
      $onAction = sanitize_text_field($_REQUEST['Action']);
      $Module = sanitize_text_field($_REQUEST['Module']);
      $layoutname = sanitize_text_field($_REQUEST['LayoutName']);
      $formtitle = sanitize_text_field($_REQUEST['formTitle']);
      $layoutId =  sanitize_text_field($_REQUEST['layoutId']);
        if ($onAction == 'zcfCreateShortcode') {
            require_once(ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php");
            $zcfCreateShortcode = new zcfManageShortcodesActions();
            $value = $zcfCreateShortcode->zcfCreateShortcode($Module, $layoutname, $formtitle, $layoutId);
            $value['onAction'] = 'onCreate';
        } elseif ($onAction == 'Editshortcode') {
            $value = array();
            $value['shortcode'] = sanitize_text_field($_REQUEST['shortcode']);
            $value['module'] = sanitize_text_field($_REQUEST['Module']);
            $value['crmtype'] = sanitize_text_field($_REQUEST['plugin']);
            $value['onAction'] = 'onEditShortCode';
            $value['formTitle'] = sanitize_text_field($_REQUEST['formTitle']);
            require_once(ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php");
            $zcfCreateShortcode = new zcfManageShortcodesActions();
            $zcfCreateShortcode->zcfsynceditUploadField($Module, $layoutname, $formtitle, $layoutId, $value['shortcode']);
        } elseif ($onAction  == 'zcfupdateState') {
            $value = array();
            $value['formfieldIds'] = json_decode(stripslashes($_REQUEST['formfieldIds']));
            require_once(ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php");
            $zcfCreateShortcode = new zcfManageShortcodesActions();
            $zcfCreateShortcode->zcfupdateState($value, sanitize_text_field($_REQUEST['formfieldsLength']), sanitize_text_field($_REQUEST['shortcodename']));
        } elseif ($onAction  == 'zcfdeleteFieldsState') {
            $formfieldId = sanitize_text_field($_REQUEST['formfieldIds']);
            require_once(ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php");
            $zcfCreateShortcode = new zcfManageShortcodesActions();
            $zcfCreateShortcode->zcfdeleteFieldsState($formfieldId);
        } else {
            require_once(ZCF_BASE_DIR_URI . "includes/crmshortcodefunctions.php");
            $zcfDeleteShortcode = new zcfManageShortcodesActions();
            $zcfDeleteShortcode->zcfDeleteShortcode(sanitize_text_field($_REQUEST['shortcode']));
            $value = array();
        }
        $shortcodevalues = json_encode($value);
        print_r($shortcodevalues);
        }else{
          die( __( 'Security check', 'textdomain' ) );
        }
        die;
    }

    public static function zcfSaveCRMconfig() {
        require_once( ZCF_BASE_DIR_URI . "includes/zcfSaveCRMconfig.php" );
        die;
    }

    public static function zcfmainFormsActions() {
        zcf_validate_general_nonce();
        $action = sanitize_text_field($_REQUEST['action']);
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );
        if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        require_once( ZCF_BASE_DIR_URI . "includes/crmcustomfunctions.php" );
        $adminObj = new zcf_AjaxActionsClass();
        $admin = $adminObj->zcfmainFormsActions();
        die;
      }else{
        die( __( 'Security check', 'textdomain' ) );
      }

    }

    public static function zcfcaptcha_info() {
        zcf_validate_general_nonce();
        $action = sanitize_text_field($_REQUEST['action']);
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );
        if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        $final_captcha_array['recaptcha_public_key'] = sanitize_text_field($_REQUEST['recaptcha_public_key']);
        $final_captcha_array['recaptcha_private_key'] = sanitize_text_field($_REQUEST['recaptcha_private_key']);
        $final_captcha_array['crmforms_recaptcha'] = sanitize_text_field($_REQUEST['crmforms_recaptcha']);
        $final_captcha_array['email'] = sanitize_email($_REQUEST['email']);
        $final_captcha_array['emailcondition'] = sanitize_text_field($_REQUEST['emailcondition']);
        update_option("zcf_captcha_settings", $final_captcha_array);
        die;
      }else{
        die( __( 'Security check', 'textdomain' ) );
      }

    }

    public static function zcfmappingmoduleconf() {
        $map_module = sanitize_text_field($_REQUEST['postdata']);
        update_option('zohocrmbasemodule', $map_module);
        die;
    }

    public static function zcfsaveSyncValue() {
        $Sync_value = sanitize_text_field($_REQUEST['syncedvalue']);
        update_option('Sync_value_on_off', $Sync_value);
        die;
    }

    public static function zcfsend_mapping_configuration() {
        zcf_validate_general_nonce();
        $action = sanitize_text_field($_REQUEST['action']);
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );
        if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
        $module = sanitize_text_field($_REQUEST['thirdparty_module']);
        $thirdparty_form = sanitize_text_field($_REQUEST['thirdparty_plugin']);
        $mapping_ui_fields = new zcfcontactformfieldmapping();
        $mapping_ui_fields->zcfget_mapping_field_config($module, $thirdparty_form);
      }else{
          die( __( 'Security check', 'textdomain' ) );
      }
    }

    public static function zcf_get_contactform_fields() {
        require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
        $mapping_ui_fields = new zcfcontactformfieldmapping();
        $mapping_ui_fields->zcfget_contactform_fields();
    }

    public static function zcf_map_contactform_fields() {
        zcf_validate_general_nonce();
        $action = sanitize_text_field($_REQUEST['action']);
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );
        if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
        $mapping_ui_fields = new zcfcontactformfieldmapping();
        $mapping_ui_fields->zcfmaping_contactform_fields();
      }else{
          die( __( 'Security check', 'textdomain' ) );
      }
    }

    public static function zcf_save_contact_form_title() {
      zcf_validate_general_nonce();
      $action = sanitize_text_field($_REQUEST['action']);
      $user = wp_get_current_user();
      $allowed_roles = array( 'editor', 'administrator', 'author' );
      if ( isset( $_REQUEST['action'] )&& (wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles ))){
        $thirdparty_title_key = sanitize_text_field($_REQUEST['tp_title_key']);
        $thirdparty_title_value = sanitize_text_field($_REQUEST['tp_title_val']);
        update_option($thirdparty_title_key, $thirdparty_title_value);
        die;
      }else{
          die( __( 'Security check', 'textdomain' ) );
      }

    }

    public static function zcf_send_mapped_config() {
      zcf_validate_general_nonce();
      $action = sanitize_text_field($_REQUEST['action']);
      $user = wp_get_current_user();
      $allowed_roles = array( 'editor', 'administrator', 'author' );
      if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
        $mapping_ui_fields = new zcfcontactformfieldmapping();
        $mapping_ui_fields->zcf_mapped_fields_config();
      }else{
          die( __( 'Security check', 'textdomain' ) );
      }
    }

    public static function zcf_delete_mapped_config() {
        zcf_validate_general_nonce();
        $action = sanitize_text_field($_REQUEST['action']);
        $user = wp_get_current_user();
        $allowed_roles = array( 'editor', 'administrator', 'author' );
        if(wp_verify_nonce( $_POST['nonce'],$action.'_nonce' ) && array_intersect( $allowed_roles, $user->roles )){
        require_once( ZCF_BASE_DIR_URI . 'includes/crmcontactformfieldsmapping.php' );
        $mapping_ui_fields = new zcfcontactformfieldmapping();
        $mapping_ui_fields->zcf_delete_mappedfields_config();
      }else{
          die( __( 'Security check', 'textdomain' ) );
      }
    }

    public static function zcf_save_usersync_option() {
        $usersync_RR_value = sanitize_text_field($_REQUEST['user_rr_val']);
        update_option('usersync_rr_value', $usersync_RR_value);
        die;
    }

    public static function zcf_change_menu_order($menu_order) {
        return array(
            'index.php',
            'edit.php',
            'edit.php?post_type=page',
            'upload.php',
            'zoho-crm-form-builder/index.php',
        );
    }

}
