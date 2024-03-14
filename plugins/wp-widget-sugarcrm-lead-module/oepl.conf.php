<?php
if ( isset($_SERVER['SCRIPT_FILENAME']) && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit(esc_html__('Please don\'t access this file directly.', 'WP2SL'));
}

/* define the plugin folder url */
define('OEPL_PLUGIN_URL', plugin_dir_url(__FILE__));

/* define the plugin folder dir */
define ('OEPL_PLUGIN_DIR', plugin_dir_path(__FILE__));

define ('OEPL_METAKEY_EXT', 'oepl_');

ini_set("soap.wsdl_cache_enabled","0");

define('OEPL_SUGAR_USER_ID_KEY', 'oepl_sugar_contact_id');

define('OEPL_SUGARCRM_URL'			, get_option('OEPL_SUGARCRM_URL') );
define('OEPL_SUGARCRM_ADMIN_USER'	, get_option('OEPL_SUGARCRM_ADMIN_USER'));
define('OEPL_SUGARCRM_ADMIN_PASS'	, get_option('OEPL_SUGARCRM_ADMIN_PASS'));
# Table list
define('OEPL_TBL_MAP_FIELDS'		, 'oepl_crm_map_fields');
define( 'OEPL_FILE_UPLOAD_FOLDER' 	, '/OEPL');
$OEPL_update_version = '4.0';

require_once(OEPL_PLUGIN_DIR. "oepl.crm.cls.php");
$objSugar = new WP2SLSugarCRMClass;
$objSugar->SugarURL  = OEPL_SUGARCRM_URL;
$objSugar->SugarUser = OEPL_SUGARCRM_ADMIN_USER; 
$objSugar->SugarPass = OEPL_SUGARCRM_ADMIN_PASS; 

$htaccessProtected = get_option('OEPL_is_SugarCRM_htaccess_Protected');
$htaccessUsername  = get_option('OEPL_SugarCRM_htaccess_Username');
$htaccessPassword  = get_option('OEPL_SugarCRM_htaccess_Password');

if ($htaccessProtected === 'Y'){
	$objSugar->isHtaccessProtected = TRUE; 
	$objSugar->HtaccessAdminUser = $htaccessUsername;
	$objSugar->HtaccessAdminPass = $htaccessPassword;	
}

require_once(OEPL_PLUGIN_DIR . "OEPL-Widget.php");
require_once(OEPL_PLUGIN_DIR . "admin-functions.php");
require_once(OEPL_PLUGIN_DIR . "Common-functions.php");

## Load CSS and JS
add_action( 'wp_enqueue_scripts', 'WP2SL_frontend_script_load' );
function WP2SL_frontend_script_load() {
    wp_enqueue_script( 'jquery-form', array( 'jquery' ) );
    wp_enqueue_style( 'Date_picker_css', OEPL_PLUGIN_URL . "style/jquery.datetimepicker.css", array(), '1.0', 'all' );
    wp_enqueue_script( 'admin_js', OEPL_PLUGIN_URL . 'js/admin.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'Date_picker_js', OEPL_PLUGIN_URL . 'js/jquery.datetimepicker.js', array(), false, true );

    wp_localize_script( 'admin_js', 'objwp2sl',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'pluginurl' => OEPL_PLUGIN_URL,
            'ReqFieldMsg' => get_option( 'OEPL_SugarCRMReqFieldsMessage' ),
            'ReqCatchaMsg' => get_option( 'OEPL_SugarCRMInvalidCaptchaMessage' ),
        )
    );
}

## Load CSS and JS in admin side
add_action( 'admin_init', 'WP2SL_PluginStyleJS' );
function WP2SL_PluginStyleJS() {
    wp_enqueue_script( 'jquery-form', array( 'jquery' ) );
    wp_enqueue_style( 'Date_picker_css', OEPL_PLUGIN_URL . "style/jquery.datetimepicker.css", array(), '1.0', 'all' );
    wp_enqueue_style( 'OpelStyle', OEPL_PLUGIN_URL . "style/style.css", array(), '1.0', 'all' );
    wp_enqueue_script( 'OpelJS', OEPL_PLUGIN_URL . 'js/admin.js', array( 'jquery' ), false, true );
    wp_enqueue_script( 'Date_picker_js', OEPL_PLUGIN_URL . 'js/jquery.datetimepicker.js', array(), false, true );

    if ( isset( $_GET['page'] ) && $_GET['page'] === 'mapping_table' ) {
        wp_enqueue_style( 'OEPL-Switchbox-css', OEPL_PLUGIN_URL . "style/jquery.switchButton.css", array(), '1.0', 'all' );
        wp_enqueue_script( 'OEPL-field-list', OEPL_PLUGIN_URL . 'js/field-list.js', array(), false, true );
    }

    wp_localize_script( 'OpelJS', 'objwp2sl',
        array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'pluginurl' => OEPL_PLUGIN_URL,
        )
    );
}

## Create menu
function WP2SL_CreateMenu() {
    global $objSugar;
	if($objSugar->IsUserAdministrator() === true) {
	    add_menu_page( 'SugarSetting', 'Your SugarCRM', 'administrator', 'SugarSetting', 'WP2SL_SugarSettings', OEPL_PLUGIN_URL.'image/OEPL_plugin_logo.png', 98 );
		
		add_submenu_page( 'SugarSetting', 'Lead Module', 'Lead Module', 'manage_options', 'mapping_table', 'WP2SL_SugarCRM_Submenu_function');
	}
}


## Sugar Setting page display
function WP2SL_SugarSettings() {
	require_once(OEPL_PLUGIN_DIR . 'SugarSettings.php');
}

## Loader add in admin footer
add_action( 'admin_footer', 'WP2SL_HaleFooterHTML' );
function WP2SL_HaleFooterHTML() {
	$arr = array( 
			'br' => array('clear' => array("all")), 
			'section' => array('class' => array("oe-loader-section")), 
			'div' => array('class' => array("oe-loading-section-title", "oe-loader-icon")),
			'img' => array(
				'src' => OEPL_PLUGIN_URL.'image/oe-loader.svg',
				'alt' => "Offshore Evolution loader"
			),
	);

	$str = '<br clear="all" />
		<section class="oe-loader-section">
			<div class="oe-loading-section-title">
				<div class="oe-loader-icon">
					<img src="'.OEPL_PLUGIN_URL.'image/loader.svg" alt="Offshore Evolution loader">
				</div>
			</div>
		</section>';
	echo wp_kses( $str, $arr );
}