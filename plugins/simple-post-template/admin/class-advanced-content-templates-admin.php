<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/admin
 * @author     Clifton Griffin <clif@cgd.io>
 */
class Simple_Content_Templates_Admin {

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
	 * Main instance of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin ) {

		$this->plugin_name  = $plugin_name;
		$this->version = $version;
		$this->plugin = $plugin;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Content_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Content_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->plugin_name , plugin_dir_url( __FILE__ ) . 'css/advanced-content-templates-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Simple_Content_Templates_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Simple_Content_Templates_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->plugin_name , plugin_dir_url( __FILE__ ) . 'js/advanced-content-templates-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Register the admin menus.
	 *
	 * @access public
	 * @return void
	 */
	function admin_menus() {
		global $wp_tabbed_navigation;

		add_submenu_page( "edit.php?post_type={$this->plugin->post_type}", "Simple Content Templates Settings", "Settings", "manage_options", "sct-settings", array($this, 'admin_settings_page') );
		add_submenu_page("edit.php?post_type={$this->plugin->post_type}", "Simple Content Templates Upgrade", "Upgrade", "manage_options", "sct-upgrade", array($this, 'admin_upgrade_page') );

		$wp_tabbed_navigation = new WP_Tabbed_Navigation('Simple Content Templates Settings');

		$wp_tabbed_navigation->add_tab('Settings', menu_page_url('sct-settings', false) );
		$wp_tabbed_navigation->add_tab('Upgrade', menu_page_url('sct-upgrade', false ) );
	}

	/**
	 * admin_settings_page function.
	 *
	 * @access public
	 * @return void
	 */
	function admin_settings_page() {
		include_once('partials/advanced-content-templates-admin-display.php');
	}

	/**
	 * admin_upgrade_page function
	 * @return void
	 */
	function admin_upgrade_page() {
		include_once('partials/advanced-content-templates-admin-upgrade.php');
	}

	/**
	 * Add template loader metaboxes to editor of correct post types
	 *
	 * @access public
	 * @return void
	 */
	function boxes() {
		$settings = $this->plugin->get_setting('act_post_type_settings');

		if ( empty($settings) || ! is_array($settings) ) return;

		foreach($settings as $post_type => $s) {
			if( isset($s['show_ui']) && $s['show_ui'] == "true") {
				add_meta_box( 'act_side_car_' . $post_type, 'Simple Content Templates', array($this, 'render_side_car'), $post_type, 'side', 'high');
			}
		}
	}

	/**
	 * render_side_car function.
	 *
	 * @access public
	 * @return void
	 */
	function render_side_car() {
		include_once('partials/advanced-content-templates-admin-sidecar.php');
	}

	/**
	 * Register Page Template Metabox.
	 *
	 * @access public
	 * @return void
	 */
	function page_template_box()  {
		global $post;

		if($post->post_type == $this->plugin->post_type)
			add_meta_box( 'act_template_side_car', 'Template', array($this, 'render_template_side_car'), $this->plugin->post_type, 'side', 'high');
	}

	/**
	 * render_template_side_car function.
	 *
	 * @access public
	 * @return void
	 */
	function render_template_side_car() {
		global $post;

		if ( 0 != count( get_page_templates() ) ):
	    	$template = get_post_meta($post->ID, '_wp_page_template', true);
	    	if( empty($template) ) $template = false;
	    	?>

			<select name="page_template" id="page_template">
				<option value='default'><?php _e('Default Template'); ?></option>
				<?php page_template_dropdown($template); ?>
			</select>

		<?php endif; ?>
		<?php
	}


	/**
	 * Save page template setting on ACT template save.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @return void
	 */
	function save_template($post_id) {
		if ($parent_id = wp_is_post_revision($post_id))
			$post_id = $parent_id;

		$template = get_post($parent_id);

		if ( !is_object($template) ) return;

		if( $template->post_type == $this->plugin->post_type && isset($_REQUEST['page_template']) ) {

			if($_REQUEST['page_template'] !== 'default' ) {
				update_post_meta($parent_id, '_wp_page_template', $_REQUEST['page_template']);
			} else {
				delete_post_meta($parent_id, '_wp_page_template');
			}
		}
	}


	/**
	 * On first activate, redirect to settings.
	 *
	 * @access public
	 * @return void
	 */
	function redirect_on_first_activate() {
		if( $this->plugin->get_setting('act_first_activate', false) ) {
			$this->plugin->delete_setting('act_first_activate');
			wp_redirect( admin_url("edit.php?post_type={$this->plugin->post_type}&page=sct-settings") );
			exit;
		}
	}


	/**
	 * Load a template
	 *
	 * @access public
	 * @param mixed $excerpt
	 * @param mixed $post
	 * @return void
	 */
	function template_load($excerpt, $post) {
		global $editing;
		if( ! current_user_can('edit_posts') || $editing !== true ) return $excerpt;

		$post_type = $post->post_type;
		$settings = $this->plugin->get_setting('act_post_type_settings');

		// If there are no configured post types, bail
		if ( empty($settings) ) return;

		// Load the template
		$template = false;
		if( ! empty($settings[$post_type]['auto_load']) && is_numeric($settings[$post_type]['auto_load']) ) {
			$template = get_post( $settings[$post_type]['auto_load'] );
		} elseif( isset($_REQUEST['act_template_load']) && is_numeric($_REQUEST['act_template_load']) ) {
			$template = get_post( $_REQUEST['act_template_load'] );
		}

		// Only proceed if we have a template
		if( $template !== false) {
			ob_start();

			if( isset($_REQUEST['post_id']) ) {
				$post_id = $_REQUEST['post_id'];
			} else $post_id = $post->ID;

			if ( $post_id > 0 ) {
				$target_post = get_post($post_id);
			}

			$properties = get_object_vars($template);
			$excluded_properties = array(
				'ID',
				'post_author',
				'post_modified',
				'post_modified_gmt',
				'post_name',
				'guid',
				'post_status',
				'post_date',
				'post_date_gmt',
				'post_type',
				'post_status',
			);

			$temp_post = array();
			$temp_post['ID'] = $post_id;
			$temp_post['post_status'] = "draft";
			$empty_only_properties = apply_filters('act_protected_properties', array() );

			foreach($properties as $property => $value) {
				if ( ! in_array($property, $excluded_properties) ) {
					// Exclude post_title, if title already set on target post
					if ( ( in_array($property, $empty_only_properties) && ! empty($target_post) && ! empty($target_post->$property) ) && $target_post->$property != "Auto Draft" ) continue;

					/**
					 * act_template_property
					 *
					 * Filter template property before it is loaded.
					 *
					 * @since 2.0.0
					 *
					 * @param string $value The template property value
					 * @param string $property The template property being filtered
					 */
					$value = apply_filters('act_template_property', $value, $property);

					$temp_post[$property] = iconv( mb_detect_encoding($value), "UTF-8//IGNORE", $value );
				}
			}

			// Dynamic Fields
			if( $this->plugin->get_php_enabled() == "true" ) {
				$dynamic_fields = array('post_title','post_content','post_excerpt');

				foreach($dynamic_fields as $df) {
					// Exclude post_title, if title already set on target post
					if ( ( in_array($df, $empty_only_properties) && ! empty($target_post) && ! empty($target_post->$df) ) && $target_post->$df != "Auto Draft" ) continue;

					ob_start();
					eval('?>'. html_entity_decode($temp_post[$df]));
					$new =  ob_get_clean();
					$temp_post[$df] = iconv( mb_detect_encoding($new), "UTF-8//IGNORE", $new );
				}
			}

			$temp_post = apply_filters('act_load_template', $temp_post);

			wp_update_post($temp_post);

			ob_end_clean();

			do_action_ref_array('act_template_loaded', $post_id);

			wp_redirect( get_edit_post_link($post_id, '') );
			exit();
		}

		return $excerpt;
	}

	function get_templates() {
		return get_posts( array(
			'posts_per_page' => -1,
			'post_type'      => $this->plugin->post_type,
			'orderby'        => 'post_title',
			'order'          => 'ASC',
			'post_status'    => array(
					'publish',
					'pending',
					'future',
					'private',
				)
			)
		);
	}
}
