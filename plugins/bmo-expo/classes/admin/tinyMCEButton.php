<?php
/*
The Admin Interface - Tiny MCE Button integration
BMo Expo - a  Wordpress and NextGEN Gallery Plugin by B. Morschheuser
Copyright 2012-2013 by Benedikt Morschheuser (http://bmo-design.de/kontakt/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

http://wordpress.org/about/gpl/
#################################################################
*/

class bmoExpo_tinyMCEButton {
	
	private $theExpo_AdminObjcet = "";
	private $obj_bmoExpoAdmin_options_page;
	private $js_pluginname = "BMoExpo";
	private $internalVersion = 200;
	 
	function __construct($theExpo_AdminObjcet) {
     	$this->theExpo_AdminObjcet=$theExpo_AdminObjcet;
		
		//add the admin ajax for tiny mce
		add_action('wp_ajax_BMoExpo_tinymce_window', array ($this, 'BMo_Expo_ajax_mce_window') );
		add_action('wp_ajax_BMoExpo_tinymce_options', array ($this, 'BMo_Expo_ajax_mce_options') );
	
		// Modify the version when tinyMCE plugins are changed.
		add_filter('tiny_mce_version', array ($this, 'BMo_Expo_change_tinymce_version') );
			
		// user lacks permissions
		if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) 
			return;

		// Check for NextGEN capability
		if ( !current_user_can('NextGEN Use TinyMCE') ) 
			return;
		
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
		 
			// add the button 
			add_filter("mce_external_plugins", array ($this, 'BMo_Expo_add_tinymce_plugin' ), 5);
			add_filter('mce_buttons', array ($this, 'BMo_Expo_register_button' ), 5);
		}
  	}

	//register button
	function BMo_Expo_register_button($buttons) {
		array_push($buttons, 'separator', $this->js_pluginname );
		return $buttons;
	}
	
	//Load the TinyMCE plugin : editor_plugin.js
	function BMo_Expo_add_tinymce_plugin($plugin_array) {    
		$plugin_array[$this->js_pluginname] =  BMO_EXPO_URL.'/js/admin/tinyMCEButton/bmo_editor_plugin.js';
		return $plugin_array;
	}
	
	//A different version will rebuild the cache
	function BMo_Expo_change_tinymce_version($version) {
		$version = $version + $this->internalVersion;
		return $version;
	}
	
	//register ajax event Call TinyMCE window content via admin-ajax, liefert html zurück, das in das iframe geladen wird
	function BMo_Expo_ajax_mce_window() {
		if(!isset($_GET['action']))
			die();
		 
		$action = $_GET['action'];
		
		if($action=="BMoExpo_tinymce_window"){
		    // check for rights
		    if ( !current_user_can('edit_pages') && !current_user_can('edit_posts') )
		    	die(__("You are not allowed to be here"));
			
			//hole den inhalt, der in das iframe ausgegeben wird
		   	include_once( BMO_EXPO_CLASSPATH . '/admin/tinyMCEWindow.php');

		    throw new E_Clean_Exit();
		}else{
			die();
		}
	 }
	
	function BMo_Expo_ajax_mce_options() {
		   if(!isset($_GET['action']))
				die();

			$action = $_GET['action'];
			$type = $_GET['type'];
			$parameter = $_GET['parameter'];

			if($action=="BMoExpo_tinymce_options"&&isset($type)){
				
				$out = array();
				$html_output ="";
				
				$this->theExpo_AdminObjcet->BMo_Expo_registerPageComponents();//register all needed components
				
				$this->obj_bmoExpoAdmin_options_page = new bmoExpoAdmin_options_page($this->theExpo_AdminObjcet);
				$this->obj_bmoExpoAdmin_options_page->BMo_Expo_registerOptionSettings(true); //spezielle Komponenten für options
				
				//save echos from do_settings_sections() in variable
				ob_start(); //Start output buffer
				
				do_settings_sections('BMo_Expo_options_section_common_el');
				
				if($type=="scrollGallery"){
					do_settings_sections('BMo_Expo_options_section_sG_el');
				}
				if($type=="scrollLightboxGallery"){
					do_settings_sections('BMo_Expo_options_section_slG_el');
				}
				
				$html_output = ob_get_contents(); //Grab output from echo in variable
				ob_end_clean(); //Discard output buffer
				
				array_push($out,array('type' => $type,'html' => $html_output, 'parameter' => $parameter));
					
				if(!empty($out)&&!empty($html_output)){
					// generate the response
					$response = json_encode( $out );

					// response output
					header( "Content-Type: application/json" );
					echo $response;
				}
			}

			// IMPORTANT: don't forget to "exit"
			exit;
		}
	
}

?>