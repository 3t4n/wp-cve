<?php

/* 
	Base class for Gold Plugins. 
		
	Abilities:
		- create custom post types (IK Custom Post type)
		- create taxonomies
		- associate taxonimies with custom post types
		- provide Loops of custom post types
		- provide simple querying methods for custom post types
		
*/

require_once('gold-plugins-custom-post-type.php');

if (!class_exists('StaffDirectory_GoldPlugin')):

	class StaffDirectory_GoldPlugin
	{
		var $customPostTypes = array();
		var $taxonomies_to_register = array();
		var $css_to_register = array();
		var $scripts_to_register = array();
		var $settings_pages = array();
		
		function __construct()
		{
			$this->add_base_plugin_hooks();	
		}
				
		public function add_settings_page($page_title, $menu_label, $parent_menu = 'Settings')
		{
			$settingsPageData = array(	'page_title' => $page_title,
										'menu_label' => $menu_label,
										'menu_slug' => $this->slugify($menu_label . ' options'),
										'parent_menu' => $parent_menu, // TBD: support settings pages that have their own parent menu
								);
			$this->settings_pages[] = $settingsPageData;
		}
		
		public function add_custom_post_type($postType, $customFields, $removeDefaultCustomFields = false, $forceClassicEditor = false)
		{
			$this->customPostTypes[] = new GoldPlugins_StaffDirectory_CustomPostType($postType, $customFields, $removeDefaultCustomFields, $forceClassicEditor);
		}

		/* this is the function to call from other code, which creates *a single* taxonomy to be registered */
		public function add_taxonomy($slug, $post_types, $singular, $plural, $args = false)
		{
			// generate all of the labels from the $singular and $plural labels
			$labels = $this->generate_taxonomy_labels($singular, $plural);

			// allow any passed-in arguments to override our taxonomy defaults
			$merged_args = $this->merge_taxonomy_args($args, $labels);

			$newTaxonomy = array(
				'slug' => $slug,
				'post_types' => $post_types,
				'args' => $merged_args,
			);
			$this->taxonomies_to_register[] = $newTaxonomy;
		}
		
		public function add_stylesheet($handle, $file_url = '')
		{
			$this->css_to_register[$handle] = array('handle' => $handle,
													'file_url' => $file_url);
			
		}
		public function add_script($handle, $file_url = '', $deps = array(), $ver = false, $in_footer = false)
		{
			$this->scripts_to_register[$handle] = array('handle' => $handle,
														'file_url' => $file_url,
														'deps' => $deps,
														'ver' => $ver,
														'in_footer' => $in_footer,													
														);		
		}
		
		/* Merges the specified $args with our hard coded defaults for a taxonomy */
		private function merge_taxonomy_args($args, $labels)
		{
			// default arguments. Tag like taxonomy (like tags, not categories)
			$default_args = array(
				'labels'                     => $labels,
				'hierarchical'               => true,
				'public'                     => true,
				'show_ui'                    => true,
				'show_admin_column'          => true,
				'show_in_nav_menus'          => true,
				'show_tagcloud'              => true,
			);
			// override the default args with any that were passed in
			if (is_array($args)) {
				$merged_args = array_merge($default_args, $args);
			} else {
				$merged_args = $default_args;
			}
			return $merged_args;
		}
		
		/* Given the singular and plural names for a taxonomy, generates all of the needed labels and returns them as an array */
		private function generate_taxonomy_labels($singular, $plural, $text_domain = 'gold_plugins')
		{
			// generate all of the labels from the $singular and $plural labels
			$labels = array(
				'name'                       => _x( $plural, 'Taxonomy General Name', $text_domain ),
				'singular_name'              => _x( $singular, 'Taxonomy Singular Name', $text_domain ),
				'menu_name'                  => __( $plural, $text_domain ),
				'all_items'                  => __( 'All ' . $plural, $text_domain ),
				'parent_item'                => __( 'Parent ' . $singular, $text_domain ),
				'parent_item_colon'          => __( 'Parent ' . $singular . ':', $text_domain ),
				'new_item_name'              => __( 'New ' . $singular . ' Name', $text_domain ),
				'add_new_item'               => __( 'Add New ' . $singular, $text_domain ),
				'edit_item'                  => __( 'Edit ' . $singular, $text_domain ),
				'update_item'                => __( 'Update ' . $singular, $text_domain ),
				'separate_items_with_commas' => __( 'Separate ' . strtolower($plural) . ' with commas', $text_domain ),
				'search_items'               => __( 'Search ' . $plural, $text_domain ),
				'add_or_remove_items'        => __( 'Add or remove ' . strtolower($plural), $text_domain ),
				'choose_from_most_used'      => __( 'Choose from the most used ' . strtolower($plural), $text_domain ),
				'not_found'                  => __( 'Not Found', $text_domain ),
			);	
			return $labels;
		}

		function get_option_value($post_id, $key, $default = '', $single = true)
		{
			// add _ikcf_ prefix if needed
			if (strpos($key, '_ikcf_') !== 0) {
				$key = '_ikcf_' . $key;
			}
		
			$meta_val = get_post_meta($post_id, $key, $single);
			if ($meta_val !== '') {
				return $meta_val;
			} else {
				return $default;		
			}
		}
		
		/* this is the function to run on WordPress' init, that will register *all* of our taxonomies */
		function register_all_taxonomies()
		{
			foreach($this->taxonomies_to_register as $tax)
			{
				register_taxonomy( $tax['slug'], $tax['post_types'], $tax['args'] );		
			}		
		}

		/* this is the function to run on WordPress' init, that will register *all* of our settings pages */
		function register_settings_pages()
		{			
			foreach($this->settings_pages as $settings_page)
			{
				add_options_page($settings_page['page_title'], $settings_page['menu_label'], 'manage_options', $settings_page['menu_slug'], array($this, 'output_settings_page'));	
			}		
		}
		
		/* this is the function to run on WordPress' init, that will register *all* of our css files and javascript files */
		function register_all_styles_and_scripts()
		{
			foreach ( $this->css_to_register as $style_handle => $style ) {
				// register the stylesheet, then enqueue it
				 wp_register_style( $style['handle'], $style['file_url'] );
				 wp_enqueue_style( $style['handle'] );
			}
			
			foreach ($this->scripts_to_register as $script_handle => $script) {		
				// register the script, then enqueue it
				wp_register_script( $script['handle'], $script['file_url'], $script['deps'], $script['ver'], $script['in_footer'] );
				wp_enqueue_script( $script['handle'] );
			}
		}
		
		function render_template($templatePath, $vars = false)
		{
			$templateFile = basename($templatePath);

			// checks if the file exists in the theme first,
			// otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( $templateFile ) ) ) {
				$real_template_path = $theme_file;
			} else {
				$real_template_path = $templatePath;
			}

			if (is_array($vars)) {
				extract($vars);
			}

			$html = '' . $real_template_path;
			if (file_exists($real_template_path)) {
				ob_start(); 
				require ($real_template_path);
				$html = ob_get_clean();
			}
			return $html;		
		}

		function slugify($input)
		{
			// TBD: remove "stop words"
			return sanitize_title($input);
		}
		
		/* these functions are optionally to be overriden in a subclass */
		function add_base_plugin_hooks()
		{
			add_action( 'init', array($this, 'register_all_taxonomies'), 0 );
			add_action( 'wp_enqueue_scripts', array($this, 'register_all_styles_and_scripts') );
			add_action( 'admin_menu', array($this, 'register_settings_pages') );			
		}

		function create_post_types()
		{
		
		}
		function register_taxonomies()
		{
		
		}
		
		/* This function is intended to be overriden in the subclass. It outputs the plugin's settings page */
		function output_settings_page()
		{
			echo '<h3>Settings</h3>';
		}
	}

endif; // class_exists