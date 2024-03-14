<?php

namespace Category_Import_Reloaded\Inc\Admin;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://www.niroma.net
 * @since      1.0.0
 *
 * @author    Niroma
 */
class Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The text domain of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_text_domain    The text domain of this plugin.
	 */
	private $plugin_text_domain;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since       1.0.0
	 * @param       string $plugin_name        The name of this plugin.
	 * @param       string $version            The version of this plugin.
	 * @param       string $plugin_text_domain The text domain of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_text_domain ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->plugin_text_domain = $plugin_text_domain;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/category-import-reloaded-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/*
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/category-import-reloaded-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function display_plugin_setup_page() {
		include_once( 'views/html-category-import-reloaded-admin-display.php' );
	}

	public function add_plugin_admin_menu() {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     */
		add_menu_page(	'Category Import Reloaded', 'Category Import Reloaded', 'manage_categories', $this->plugin_name );
		add_submenu_page( $this->plugin_name, 'Category Import Reloaded', 'Category Import', 'manage_categories', $this->plugin_name, array($this, 'display_plugin_setup_page') );
	}


	private function termAlreadyExists($array, $key, $val, $return) {
		foreach ($array as $item) {
			if (isset($item[$key])) {
				$compare = sanitize_text_field($item[$key]);
				if ($compare == $val) return $item[$return];
			}
		}
		return false;
	}


	public function check_for_event_submissions(){
			if ( isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], $this->plugin_name.'submit-taxonomies') ){
				$admin_notice = '';
				$messageLog = '';
				$taxonomyActive = $_POST[$this->plugin_name.'-taxonomy'] ? $_POST[$this->plugin_name.'-taxonomy'] : 'category';
				$delimiter = ( strlen(sanitize_text_field(trim($_POST[$this->plugin_name.'-delimiter']))) != 0 ) ? $_POST[$this->plugin_name.'-delimiter']:"$";
				if ( strlen($delimiter) > 2 ) $delimiter = "$";
				$lines = explode(PHP_EOL, $_POST[$this->plugin_name.'-bulkCategoryList']);
				$countSuccess = 0;
				$countErrors = 0;
				$parent_id = '';
				$rootCategories = array();
				$rootTerms = get_terms( array( 'taxonomy' => $taxonomyActive, 'parent' => 0, 'hide_empty' => false ) );
				//use wp_list_pluck for cleaner code.
				$rootCategories = wp_list_pluck($rootTerms, 'term_id','name');

				foreach($lines as $line){
					//use str_getcsv to enable eclosure with quotes.
					$split_line = str_getcsv(stripslashes($line), '/','"');
					// $split_line = explode('/', $line);
					$l =  count($split_line);
					for ($i = 0; $i < $l; $i++) {
						$new_line = $split_line[$i];
						$prev_line = '';

						if (strlen(trim($new_line)) == 0) break;
						$new_line = sanitize_text_field(trim($new_line));

						if(strpos($new_line, $delimiter) !== false){
							$cat_name_slug = explode($delimiter,$new_line);
							$cat_name =  sanitize_text_field(trim($cat_name_slug[0]));
							$cat_slug =  sanitize_text_field(trim($cat_name_slug[1]));
						} else {
							$cat_name = $new_line;
							$cat_slug = $new_line;
						}

						if ($i == 0) {
							//use isset rather than separate method, simpler.
							if ( isset($rootCategories[$cat_name]) ) {
								$parent_id = $rootCategories[$cat_name];
								$countErrors++;
							} else {
								$result = wp_insert_term( $cat_name, $taxonomyActive, array('slug' => $cat_slug) );
								if ( ! is_wp_error( $result ) ) {
									$parent_id = isset( $result['term_id'] ) ? $result['term_id'] : '';
									$rootCategories[$cat_name] = $parent_id;
									$countSuccess++;
								} else $countErrors++;
							}
						} else {
							if (!empty($parent_id)) {
								$siblingsCategories = array();
								$parentChildren = get_terms( array('taxonomy' => $taxonomyActive, 'parent' => $parent_id, 'hide_empty' => false ) );
                $siblingsCategories = wp_list_pluck($parentChildren, 'term_id','name');
								//using isset for simpler code.
								if ( isset($siblingsCategories[$cat_name]) ) {
									$parent_id = $siblingsCategories[$cat_name];
									$countErrors++;
								} else {
									$result = wp_insert_term( $cat_name, $taxonomyActive, array('parent' => $parent_id, 'slug' => $cat_slug) );
									if ( ! is_wp_error( $result ) ) {
										$parent_id = isset( $result['term_id'] ) ? $result['term_id'] : '';
										$countSuccess++;
									} else $countErrors++;
								}
							} else $countErrors++;
						}
					}
				}
				if ($countErrors > 0 ) {
					$admin_notice = "error";
					$messageLog .= $countErrors .' categories already in database ';
				}
				if ($countSuccess > 0 ) {
					$admin_notice = "success";
					$messageLog .= $countSuccess .' categories successully added ! ';
				}

				$this->custom_redirect( $admin_notice, $messageLog);
				die();
			}  else {
				wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
						'response' 	=> 403,
						'back_link' => 'admin.php?page=' . $this->plugin_name,
				) );
			}
	}

	public function custom_redirect( $admin_notice, $response ) {
		wp_redirect( esc_url_raw( add_query_arg( array(
									'cir_admin_add_notice' => $admin_notice,
									'cir_response' => $response,
									),
							admin_url('admin.php?page='. $this->plugin_name )
					) ) );

	}

	public function print_plugin_admin_notices() {
		  if ( isset( $_REQUEST['cir_admin_add_notice'] ) ) {
			if( $_REQUEST['cir_admin_add_notice'] === "success") {
				$html =	'<div class="notice notice-success is-dismissible">
							<p><strong>' . htmlspecialchars( print_r( $_REQUEST['cir_response'], true) ) . '</strong></p></div>';
				echo $html;
			}
			if( $_REQUEST['cir_admin_add_notice'] === "error") {
				$html =	'<div class="notice notice-error is-dismissible">
							<p><strong>' . htmlspecialchars( print_r( $_REQUEST['cir_response'], true) ) . '</strong></p></div>';
				echo $html;
			}
		  } else {
			  return;
		  }

	}

}
