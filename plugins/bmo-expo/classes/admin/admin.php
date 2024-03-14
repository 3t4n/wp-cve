<?php
/*
The Admin Interface
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
require_once (BMO_EXPO_CLASSPATH . '/admin/admin_page_general.php');
require_once (BMO_EXPO_CLASSPATH . '/admin/admin_page_options.php');
require_once (BMO_EXPO_CLASSPATH . '/admin/tinyMCEButton.php');

class bmoExpoAdmin {
	
	private $theExpo_Objcet = "";
	private $hasNGG = false; //check if NextGEN is activated
	private $obj_bmoExpoAdmin_general_page;
	private $obj_bmoExpoAdmin_options_page;
	
	function __construct($theExpo_Objcet) {
     	$this->theExpo_Objcet=$theExpo_Objcet;
  	}
	 
	
	//admin menu
	public function BMo_Expo_admin_menu() { 
		$this->obj_bmoExpoAdmin_general_page = new bmoExpoAdmin_general_page($this);
		add_menu_page('BMo Expo Admin', 'BMo Expo', 'manage_options', BMO_EXPO_PLUGINNAME."-admin", array($this->obj_bmoExpoAdmin_general_page, 'BMo_Expo_Admin_show_page'),BMO_EXPO_URL."/css/imgs/BMoExpoIcon_16.png"); // add Admin Menu
	    add_submenu_page(  BMO_EXPO_PLUGINNAME."-admin", __('BMo Expo Admin','bmo-expo'), __('Features & Usage','bmo-expo'), 'manage_options', BMO_EXPO_PLUGINNAME."-admin", array($this->obj_bmoExpoAdmin_general_page, 'BMo_Expo_Admin_show_page')); // add Admin Menu Usage Page
		
		$this->obj_bmoExpoAdmin_options_page = new bmoExpoAdmin_options_page($this);
		add_submenu_page(  BMO_EXPO_PLUGINNAME."-admin", __('BMo Expo Options','bmo-expo'), __('Options','bmo-expo'), 'manage_options', BMO_EXPO_PLUGINNAME."-admin-options", array($this->obj_bmoExpoAdmin_options_page, 'BMo_Expo_Admin_show_page')); // add Admin Menu Usage Page

	}
	
	public function BMo_Expo_admin_init() {
		global $pagenow;
		//check if NextGEN is activated
		if ( defined('NGGALLERY_ABSPATH')||defined('NEXTGEN_GALLERY_PLUGIN') ) {//version 2.0 is NEXTGEN_GALLERY_PLUGIN
			$this->hasNGG = true;
		}
		
		//plugin notices
		add_action('admin_notices', array($this, 'admin_notices'));
		
		//admin Header  
	   	wp_deregister_script(array('plusone'));
	   	wp_register_script( 'plusone', 'https://apis.google.com/js/plusone.js');
	   	if (function_exists('wp_enqueue_script')) {
			wp_enqueue_script('plusone');
		}
		
			
		if ($pagenow=="admin.php" && ($_GET['page']== BMO_EXPO_PLUGINNAME."-admin"||$_GET['page']== BMO_EXPO_PLUGINNAME."-admin-options")||$pagenow =="options.php") {//check if is my plugin page
			wp_register_style( 'bmo_admin_css', BMO_EXPO_URL.'/css/admin/bootstrap.css', array(), BMO_EXPO_VERSION ,'all');
			wp_register_style( 'bmo_admin_style_css', BMO_EXPO_URL.'/css/admin/bmo_admin_style.css', array(), BMO_EXPO_VERSION ,'all');
			wp_register_script( 'bmo_admin_js', BMO_EXPO_URL.'/js/admin/bootstrap.js', array(), BMO_EXPO_VERSION ,'all');
			if (function_exists('wp_enqueue_script')) {
				wp_enqueue_style('bmo_admin_css');
				wp_enqueue_style('bmo_admin_style_css');
				wp_enqueue_script('jquery');
				wp_enqueue_script('bmo_admin_js');
			}
			
			$this->BMo_Expo_registerPageComponents(); //globale Elemente für alle Seitentypen
		} 
		
		if ($pagenow=="admin.php" && $_GET['page']== BMO_EXPO_PLUGINNAME."-admin") {//check 
			$this->obj_bmoExpoAdmin_general_page->BMo_Expo_registerPageComponents(); //spezielle Komponenten
		}
		if ($pagenow=="admin.php" && $_GET['page']== BMO_EXPO_PLUGINNAME."-admin-options") {//
			$this->obj_bmoExpoAdmin_options_page->BMo_Expo_registerOptionSettings(); //init Option Settings 
			$this->obj_bmoExpoAdmin_options_page->BMo_Expo_registerPageComponents(); //spezielle Komponenten
		}
		if($pagenow =="options.php"){
			$this->obj_bmoExpoAdmin_options_page->BMo_Expo_registerOptionSettings(); //init Option Settings
			//(! Optionen auf verschiedene Seiten aufuteilen geht nicht, es müssen immer alle optionen an options.php gesendet werden! )
		}
	
		
		if ( $this->hasNGG ){//add tiny mce button in Editor, falls NGG installiert ist
			$tinyMCEButton = new bmoExpo_tinyMCEButton($this);
		}
	 }
	
	
	 public function admin_notices() {
		global $pagenow;
		if ( $this->hasNGG==false && $pagenow=="admin.php" && $_GET['page']== BMO_EXPO_PLUGINNAME."-admin") {//check if NextGEN is activated
			$this->show_message(__("We recommend to use the plugin together with the ",'bmo-expo')."<strong><a href='http://wordpress.org/extend/plugins/nextgen-gallery/' target='_blank'>NextGEN Gallery</a></strong>".__('plugin','bmo-expo').".");
		}
		
		$options = get_option(BMO_EXPO_OPTIONS);
		if(empty($options)){
			$this->show_message(__("Error, please reinstall the <strong>BMo Expo - a Wordpress and NextGEN Gallery Exhibition Plugin</strong>!",'bmo-expo'));
		}
	 }
	 public function show_error($message) {
		echo '<div class="wrap"><h2></h2><div class="error" id="error"><p>' . $message . '</p></div></div>';
	 }
	 public function show_message($message) {
		echo '<div class="wrap"><h2></h2><div class="updated fade" id="message"><p>' . $message . '</p></div></div>';
	 }
	 
	 public function BMo_Expo_registerPageComponents(){
		//register all page Components:
		
		//meta boxes - more infos http://www.wproots.com/ultimate-guide-to-meta-boxes-in-wordpress/
		//--
		
		//advanced
		//--

		//side
		add_meta_box('BMo_Exp_meta_box', __('Do you like this Plugin?','bmo-expo'), array($this, 'BMo_Expo_like_MetaBox'), BMO_EXPO_PLUGINNAME, 'side', 'core');
		#add_meta_box('BMo_Exp_pro_box', 'Buy more features', array($this, 'BMo_Expo_pro_MetaBox'), BMO_EXPO_PLUGINNAME, 'side', 'default');
	 }
	
	 public function BMo_Expo_like_MetaBox(){
	 	 echo '<p>'.__('This plugin is developed by','bmo-expo').' <br/><a href="http://www.BMo-Design.de" target="_blank">Benedikt Morschheuser</a>.<br/>'.__('Any kind of contribution would be highly appreciated. Thank you!','bmo-expo').'</p>
		 <ul>
		 	<li>'.__('If you like my plugin, please...','bmo-expo').'</li>
		 	<li><a href="http://wordpress.org/extend/plugins/bmo-expo/" target="_blank">'.__('rate it at wordpress.org','bmo-expo').'</a> &diams;</li>
			<li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4AWSR2J4DK2FU" target="_blank">'.__('donate my work','bmo-expo').'</a> &hearts;</li>
			<li><a href="http://bmo-design.de" target="_blank">'.__('set a link to my website','bmo-expo').'</a> &rarr;</li>
			<li>or give me a <g:plusone size="small"  href="http://software.bmo-design.de/wordpress-plugin-bmo-exhibition.html"></g:plusone></li>
			<li>&nbsp;</li>
			<li>'.__('If you are a stylesheet-designer, send me your','bmo-expo').'<a href="http://bmo-design.de/kontakt/" target="_blank">'.__('custom gallery css','bmo-expo').'</a> &raquo;</li>
			<li>&nbsp;</li>
		</ul>';
	 }
	
	 public function BMo_Expo_RegisterPluginLinks($links, $file) { 
		if ($file == BMO_EXPO_BASE_FILE) {
			return array_merge(
				$links,
				array( sprintf( '<a href="admin.php?page=%s">%s</a>', BMO_EXPO_PLUGINNAME."-admin-options", __('Settings') ), '<g:plusone size="small"  href="http://software.bmo-design.de/wordpress-plugin-bmo-exhibition.html"></g:plusone>' )
			);
		}
		return $links;
	 } 
	
	public function hasNGG(){
		return $this->hasNGG;
	}
	
	public function BMo_Expo_get_theExpo_Objcet(){
		return  $this->theExpo_Objcet;
	}
}

?>