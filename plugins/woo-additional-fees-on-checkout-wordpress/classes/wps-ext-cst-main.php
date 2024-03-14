<?php
class WPS_EXT_CST_Main
{
	public function __construct()
	{
		add_action('woocommerce_init', array('WPS_EXT_CST_Admin', 'init'));
		add_action('admin_enqueue_scripts',array($this, 'reg_script'));
	}
	public static function reg_script($hook){
		
		if( $hook  != 'woocommerce_page_wps-ext-cst-option')
			return;

		wp_register_script('WPS_EXT_CST_SELECT2_ADMIN_JS', WPS_EXT_CST_JS.'/wafc-select2.min.js', array('jquery'),'1.1', true);
		wp_enqueue_script('WPS_EXT_CST_SELECT2_ADMIN_JS');
		
		wp_register_script('WPS_EXT_CST_ADMIN_JS', WPS_EXT_CST_JS.'/wps-ext-cst.js', array('jquery'),'1.1', true);
		wp_enqueue_script('WPS_EXT_CST_ADMIN_JS');
	}

}new WPS_EXT_CST_Main();


?>