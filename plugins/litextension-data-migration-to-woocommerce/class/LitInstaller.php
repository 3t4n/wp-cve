<?php

namespace LitExtension;

/**
 * Class LitInstaller
 * Function: litActivate, litDeactive, litUninstall
 */
class LitInstaller
{
	public static function litActivate(){
		add_option('_lit_litextension_version', LIT_VERSION, '', 'yes');
	}

	public static function litDeactivate(){
		global $wpdb;
	    $table_name = $wpdb->prefix . 'options';
	    $wpdb->update(
	        $table_name,
	        array('autoload' => 'no'),
	        array('option_name' => '_lit_litextension_version'),
	        array('%s'),
	        array('%s')
	    );
	}

	public static function litUninstall(){
    	delete_option('_lit_litextension_version');
	}
}