<?php

/**

Plugin Name: Press Release Distribution 
Plugin URI: https://prwirepro.com/press-release-distribution-wordpress-plugin/ 
Description: Creates a new section for press releases within your wordpress dashboard right next to your post and pages section. Publish your press releases from their own section and keep them separate from all of your post and pages. 
Version: 1.1.0
Author: PR Wire Pro 
Author URI: https://prwirepro.com 
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Creates a new section for press releases within your wordpress dashboard right next to your post and pages section. Publish your press releases from their own section and keep them separate from all of your post and pages.  

**/

# Exit if accessed directly
if (!defined("ABSPATH"))
{
	exit;
}

# Constant

/**
 * Exec Mode
 **/
define("PRWIREPRO_EXEC",true);

/**
 * Plugin Base File
 **/
define("PRWIREPRO_PATH",dirname(__FILE__));

/**
 * Plugin Base Directory
 **/
define("PRWIREPRO_DIR",basename(PRWIREPRO_PATH));

/**
 * Plugin Base URL
 **/
define("PRWIREPRO_URL",plugins_url("/",__FILE__));

/**
 * Plugin Version
 **/
define("PRWIREPRO_VERSION","1.1"); 

/**
 * Debug Mode
 **/
define("PRWIREPRO_DEBUG",false);  //change false for distribution



/**
 * Base Class Plugin
 * @author PR Wire Pro
 *
 * @access public
 * @version 1.1.0
 * @package Press Release Distribution
 *
 **/

class PressReleaseDistribution
{

	/**
	 * Instance of a class
	 * @access public
	 * @return void
	 **/

	function __construct()
	{
		add_action("plugins_loaded", array($this, "prwirepro_textdomain")); //load language/textdomain
		add_action("wp_enqueue_scripts",array($this,"prwirepro_enqueue_scripts")); //add js
		add_action("wp_enqueue_scripts",array($this,"prwirepro_enqueue_styles")); //add css
		add_action("init", array($this, "prwirepro_post_type_press_release_init")); // register a press_release post type.
		add_filter("the_content", array($this, "prwirepro_post_type_press_release_the_content")); // modif page for press_release
		add_action("after_setup_theme", array($this, "prwirepro_image_size")); // register image size.
		add_filter("image_size_names_choose", array($this, "prwirepro_image_sizes_choose")); // image size choose.
		add_action("init", array($this, "prwirepro_register_taxonomy")); // register register_taxonomy.
		add_action("wp_head",array($this,"prwirepro_dinamic_js"),1); //load dinamic js
		if(is_admin()){
			add_action("admin_enqueue_scripts",array($this,"prwirepro_admin_enqueue_scripts")); //add js for admin
			add_action("admin_enqueue_scripts",array($this,"prwirepro_admin_enqueue_styles")); //add css for admin
		}
	}


	/**
	 * Loads the plugin's translated strings
	 * @link http://codex.wordpress.org/Function_Reference/load_plugin_textdomain
	 * @access public
	 * @return void
	 **/
	public function prwirepro_textdomain()
	{
		load_plugin_textdomain("press-release-distribution", false, PRWIREPRO_DIR . "/languages");
	}


	/**
	 * Insert javascripts for back-end
	 * 
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * @param object $hooks
	 * @access public
	 * @return void
	 **/
	public function prwirepro_admin_enqueue_scripts($hooks)
	{
		if (function_exists("get_current_screen")) {
			$screen = get_current_screen();
		}else{
			$screen = $hooks;
		}
	}


	/**
	 * Insert javascripts for front-end
	 * 
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 * @param object $hooks
	 * @access public
	 * @return void
	 **/
	public function prwirepro_enqueue_scripts($hooks)
	{
			wp_enqueue_script("prwirepro_main", PRWIREPRO_URL . "assets/js/prwirepro_main.js", array("jquery"),"1.1",true );
	}


	/**
	 * Insert CSS for back-end
	 * 
	 * @link http://codex.wordpress.org/Function_Reference/wp_register_style
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	 * @param object $hooks
	 * @access public
	 * @return void
	 **/
	public function prwirepro_admin_enqueue_styles($hooks)
	{
		if (function_exists("get_current_screen")) {
			$screen = get_current_screen();
		}else{
			$screen = $hooks;
		}
	}


	/**
	 * Insert CSS for front-end
	 * 
	 * @link http://codex.wordpress.org/Function_Reference/wp_register_style
	 * @link http://codex.wordpress.org/Function_Reference/wp_enqueue_style
	 * @param object $hooks
	 * @access public
	 * @return void
	 **/
	public function prwirepro_enqueue_styles($hooks)
	{
		// register css
		wp_register_style("prwirepro_main", PRWIREPRO_URL . "assets/css/prwirepro_main.css",array(),"1.1" );
			wp_enqueue_style("prwirepro_main");
	}


	/**
	 * Register custom post types (press_release)
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 * @access public
	 * @return void
	 **/

	public function prwirepro_post_type_press_release_init()
	{

		$labels = array(
			'name' => _x('Press Releases', 'post type general name', 'press-release-distribution'),
			'singular_name' => _x('Press Release', 'post type singular name', 'press-release-distribution'),
			'menu_name' => _x('Press Releases', 'admin menu', 'press-release-distribution'),
			'name_admin_bar' => _x('Press Releases', 'add new on admin bar', 'press-release-distribution'),
			'add_new' => _x('Add New', 'book', 'press-release-distribution'),
			'add_new_item' => __('Add New Press Release', 'press-release-distribution'),
			'new_item' => __('New Press Release', 'press-release-distribution'),
			'edit_item' => __('Edit Press Release', 'press-release-distribution'),
			'view_item' => __('View Press Release', 'press-release-distribution'),
			'all_items' => __('All Press Releases ', 'press-release-distribution'),
			'search_items' => __('Search Press Releases', 'press-release-distribution'),
			'parent_item_colon' => __('Parent Press Releases', 'press-release-distribution'),
			'not_found' => __('No Press Releases Found', 'press-release-distribution'),
			'not_found_in_trash' => __('No Press Releases Found In Trash', 'press-release-distribution'));

			$supports = array('title','editor','author','custom-fields','trackbacks','thumbnail','comments','revisions','post-formats','page-attributes');

			$args = array(
				'labels' => $labels,
				'description' => __('Displays all press releases', 'press-release-distribution'),
				'public' => true,
				'menu_icon' => 'dashicons-editor-table',
				'publicly_queryable' => true,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => array('slug' => 'press_release'),
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => true,
				'menu_position' => null,
				'taxonomies' => array(), // array('category', 'post_tag','page-category'),
				'supports' => $supports);

			register_post_type('press_release', $args);


	}


	/**
	 * Retrieved data custom post-types (press_release)
	 *
	 * @access public
	 * @param mixed $content
	 * @return void
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/the_content
	 **/

	public function prwirepro_post_type_press_release_the_content($content)
	{

		$new_content = $content ;
		if(is_singular("press_release")){
			if(file_exists(PRWIREPRO_PATH . "/includes/post_type.press_release.inc.php")){
				require_once(PRWIREPRO_PATH . "/includes/post_type.press_release.inc.php");
				$press_release_content = new PressRelease_TheContent();
				$new_content = $press_release_content->Markup($content);
				wp_reset_postdata();
			}
		}

		return $new_content ;

	}


	/**
	 * Register a new image size.
	 * @link http://codex.wordpress.org/Function_Reference/add_image_size
	 * @access public
	 * @return void
	 **/
	public function prwirepro_image_size()
	{
	}


	/**
	 * Choose a image size.
	 * @access public
	 * @param mixed $sizes
	 * @return void
	 **/
	public function prwirepro_image_sizes_choose($sizes)
	{
		$custom_sizes = array(
		);
		return array_merge($sizes,$custom_sizes);
	}


	/**
	 * Register Taxonomies
	 * @https://codex.wordpress.org/Taxonomies
	 * @access public
	 * @return void
	 **/
	public function prwirepro_register_taxonomy()
	{
	}


	/**
	 * Insert Dinamic JS
	 * @param object $hooks
	 * @access public
	 * @return void
	 **/
	public function prwirepro_dinamic_js($hooks)
	{
		_e("<script type=\"text/javascript\">");
		_e("</script>");
	}
}


new PressReleaseDistribution();
