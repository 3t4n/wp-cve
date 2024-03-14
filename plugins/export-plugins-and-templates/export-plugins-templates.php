<?php
   /*
      Plugin Name: Export Plugins and Templates
      Plugin URI:  http://mokfie.com
      Description: This plugin for export your plugins and templates to zip file directly from Wordpress dashboard.
      Version: 1.1
      Author: Mohammad Okfie
      Author URI: http://facebook.com/okfie
   */

	define("THE_MAIN_PATH", dirname(__FILE__).'/', true);	
	
	add_action('plugins_loaded', 'lang_export_your_plugin');
	function lang_export_your_plugin() {
		load_plugin_textdomain('ep-plugins-templates', false, dirname(plugin_basename(__FILE__)) . '/lang/');
	}
	__('Export Plugins & Templates', 'ep-plugins-templates');
	__('This plugin for export your plugins and templates to zip file directly from Wordpress dashboard.', 'ep-plugins-templates');
	
	function ep_plugins_mokfie_admin_actions(){
		add_plugins_page(__('Select your plugin',"ep-plugins-templates"), __('Export Plugins',"ep-plugins-templates"), 'manage_options', 'export_plugins', 'page_menu_export_plugins');
	}
	
	function page_menu_export_plugins(){
		include_once THE_MAIN_PATH.'includes/export-plugin-page.php';
	}
	add_action('admin_menu', 'ep_plugins_mokfie_admin_actions');
	
	function ep_templates_mokfie_admin_actions(){
		add_theme_page(__('Select your template',"ep-plugins-templates"), __('Export Templates',"ep-plugins-templates"), 'manage_options', 'export_templates', 'page_menu_export_templates');
	}
	
	function page_menu_export_templates(){
		include_once THE_MAIN_PATH.'includes/export-template-page.php';
	}
	add_action('admin_menu', 'ep_templates_mokfie_admin_actions');
	
	
	function get_all_themes_options(){
		$root = substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes';
		$dirs = array();
		if(is_dir($root)){
			$dir = opendir($root);
		}
		while ($direc = readdir($dir)){
			if(is_dir($root.'/'.$direc) && $direc !== '..' && $direc !== '.'){
				array_push($dirs, '<option value="'.$direc.'">'.$direc);
			}
		}
		echo implode('</option>',$dirs).'</option>';		
	}
	
	function get_all_plugins_options(){
		$root = substr($_SERVER['SCRIPT_FILENAME'],0,-20).'wp-content/plugins';
		$dirs = array();
		if(is_dir($root)){
			$dir = opendir($root);
		}
		while ($direc = readdir($dir)){
			if(is_dir($root.'/'.$direc) && $direc !== '..' && $direc !== '.'){
				array_push($dirs, '<option value="'.$direc.'">'.$direc);
			}
		}
		echo implode('</option>',$dirs).'</option>';		
	}

	function rscandir($base='', &$data=array()) {  
		$array = array_diff(scandir($base), array('.', '..'));
		foreach($array as $value){
			if (is_dir($base.$value)){
				$data[] = $base.$value.'/'; 
				$data = rscandir($base.$value.'/', $data); 
			} elseif (is_file($base.$value)) { 
				$data[] = array($base,$value);
			}
		}      
		return $data;
}
	function ep_plugins_themes_mokfie_export_themes($themes){
		require_once THE_MAIN_PATH.'includes/zip_file_library.php';
		$export = new PclZip(THE_MAIN_PATH.'themes/'.$themes.'.zip');
		$themes_root = substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes/'.$themes.'/';
		$files_theme = rscandir($themes_root);
		foreach($files_theme as $file_theme){
			if($file_theme[0].$file_theme[1] !== '/v' && (is_file($file_theme[0].$file_theme[1]) or is_dir($file_theme[0].$file_theme[1]))){
				$add[] = $file_theme[0].$file_theme[1];
			}
		}
		$add = implode(',',$add);
		$make = $export->add($add, PCLZIP_OPT_REMOVE_PATH, substr($_SERVER['SCRIPT_FILENAME'],0,-19).'wp-content/themes');
		if ($make == 0) {
		    die("Error : ".$export->errorInfo(true));
		}

        $_SESSION['ep_themes_mokfie_buffer'] = true;
        wp_redirect(get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)).'/themes/'.$themes.'.zip', 301); exit;
	}
	
	
	function ep_plugins_themes_mokfie_export_plugins($plugins){
		require_once THE_MAIN_PATH.'includes/zip_file_library.php';
		$export = new PclZip(THE_MAIN_PATH.'plugins/'.$plugins.'.zip');
		$plugins_root = substr($_SERVER['SCRIPT_FILENAME'],0,-20).'wp-content/plugins/'.$plugins.'/';
		$files_plugin = rscandir($plugins_root);
		foreach($files_plugin as $file_plugin){
			if($file_plugin[0].$file_plugin[1] !== '/v' && (is_file($file_plugin[0].$file_plugin[1]) or is_dir($file_plugin[0].$file_plugin[1]))){
				$add[] = $file_plugin[0].$file_plugin[1];
			}
		}
		$add = implode(',',$add);
		$make = $export->add($add, PCLZIP_OPT_REMOVE_PATH, substr($_SERVER['SCRIPT_FILENAME'],0,-20).'wp-content/plugins');
		if ($make == 0) {
		    die("Error : ".$export->errorInfo(true));
		}

        $_SESSION['ep_plugins_mokfie_buffer'] = true;
        wp_redirect(get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__)).'/plugins/'.$plugins.'.zip', 301); exit;
	}

	
	add_action('init', 'mokfie_clean_output_buffer');
    function mokfie_clean_output_buffer() {
        ob_start();
    }

    add_action('init', 'mokfie_check_session_plugins_buffer');
    function mokfie_check_session_plugins_buffer(){
		 if(isset($_SESSION['ep_plugins_mokfie_buffer']) == true){
            $files = glob(THE_MAIN_PATH.'plugins/*');
            if (is_array($files) || is_object($files))
            {
           		 foreach($files as $file){
              	  if(is_file($file))
             	       unlink($file);
           	 	}
           	}
            $_SESSION['ep_plugins_mokfie_buffer'] = false;
        }
    }
    add_action('init', 'mokfie_check_session_themes_buffer');
    function mokfie_check_session_themes_buffer(){
        $files = glob(THE_MAIN_PATH.'themes/*');
        if(isset($_SESSION['ep_themes_mokfie_buffer']) == true){
           if (is_array($files) || is_object($files))
            {
	            foreach($files as $file){
	                if(is_file($file))
	                    unlink($file);
	            }
        	}
            $_SESSION['ep_themes_mokfie_buffer'] = false;
        }
    }

    add_action('init', 'mokfie_init_session', 1);
    function mokfie_init_session(){
        session_start();
    }
	
	function mokfie_end_session_plugins_themes_buffer() {
    session_destroy ();
}

?>
