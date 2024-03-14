<?php
/*
Plugin Name: Company Directory
Plugin Script: staff-directory.php
Plugin URI: https://goldplugins.com/our-plugins/company-directory/
Description: Create a directory of your staff members and show it on your website!
Version: 4.3
Author: Gold Plugins
Author URI: https://goldplugins.com/
*/
require_once('gold-framework/plugin-base.php');
require_once('gold-framework/staff-directory-plugin.settings.page.class.php');
require_once('include/global_funcs.php');
require_once('include/factory.php');
require_once('include/sd_kg.php');
require_once('include/GP_Spacely.php');
require_once('include/staff_list_widget.php');
require_once('include/single_staff_widget.php');
require_once('include/search_staff_widget.php');
require_once('include/lib/csv_importer.php');
require_once('include/lib/csv_exporter.php');
require_once('include/lib/GP_Media_Button/gold-plugins-media-button.class.php');
require_once('include/lib/GP_Janus/gp-janus.class.php');
require_once('include/lib/GP_Sajak/gp_sajak.class.php');
require_once('include/lib/GP_MegaSeptember/mega.september.class.php');
require_once('include/lib/GP_Aloha/gp_aloha.class.php');
require_once('include/tgmpa/init.php');
require_once('include/Company_Directory_Update_Notices.php');
require_once('include/Company_Directory_Upgrade_Reminder.class.php');

// blocks
require_once('blocks/single-staff.php');
require_once('blocks/staff-list.php');
require_once('blocks/search-staff.php');

class StaffDirectoryPlugin extends StaffDirectory_GoldPlugin
{
	var $plugin_title = 'Company Directory';
	var $prefix = 'staff_dir';
	var $proUser = false;
	var $postType;
	var $customFields;
	var $in_widget = false;
	var $search_atts = false;
	var $allowed_order_by_keys = array('first_name', 'last_name', 'title', 'phone', 'email', 'address', 'website', 'department', 'staff_category', 'menu_order', 'rand');
	
	function __construct()
	{	
		// add Factory
		$this->Factory = new Staff_Directory_Factory();
		
		$this->setup_post_type_metadata();
		$this->register_taxonomies();
		$this->add_hooks();
		$this->add_stylesheets_and_scripts();
		$this->SettingsPage = new StaffDirectoryPlugin_SettingsPage($this, $this->Factory);
		$this->Update_Notices = new Company_Directory_Update_Notices();

		// add media buttons. must run after bootstrap action
		add_action( 'init', array($this, 'add_media_buttons') );
		
		// check the reg key
		$this->verify_registration_key();
		
		//add Custom CSS
		add_action( 'wp_head', array($this,'output_custom_css'));
		
		// load Janus
		if (class_exists('GP_Janus')) {
			$company_directory_Janus = new GP_Janus();
		}
		
		if ( is_admin() ) {
			// load Aloha
			$plugin_title = 'Company Directory';
			$aloha_title = __('Welcome To') . ' ' . $plugin_title;
			$config = array(
				'menu_label' => __('About Plugin'),
				'page_title' => $aloha_title,
				'tagline' => $plugin_title . __(' is the easiest way to add Staff Bios and a Staff Directory to your website.'),
				'top_level_menu' => 'edit.php?post_type=staff-member',
			);
			$this->Aloha = new GP_Aloha($config);
			
			add_filter( 'gp_aloha_welcome_page_content_edit.php?post_type=staff-member', array($this, 'get_welcome_template') );			
		}

		add_action( 'activate_company-directory-pro/company-directory-pro.php', array($this, 'pro_activation_hook') );
		
		if ( !$this->proUser ) {
			$this->Upgrade_Reminder = new Company_Directory_Upgrade_Reminder();
		}
		
		parent::__construct();
	}

	function init_post_types()
	{
		$this->create_post_types();
		$this->add_meta_box_hooks();
	}

	function add_media_buttons()
	{
		if( $this->show_media_buttons() )
		{
			$media_buttons = array(
				array(
					'label' => 'Staff List',
					'shortcode' => 'staff_list',
					'class' => 'gp_staff_list_widget',
					'icon' => 'id-alt',
				),
				array(
					'label' => 'Single Staff Member',
					'shortcode' => 'staff_member',
					'class' => 'gp_single_staff_widget',
					'icon' => 'id-alt',
				),
				array(
					'label' => 'Search Staff Members',
					'shortcode' => 'search_staff_members',
					'class' => 'gp_search_staff_widget',
					'icon' => 'id-alt',
				)				
			);
			$media_buttons = apply_filters('company_directory_admin_media_buttons', $media_buttons);

			$this->MediaButton = new Company_Directory_Gold_Plugins_Media_Button('Staff', 'id-alt');
			foreach( $media_buttons as $media_button ) {
				$this->MediaButton->add_button(
					$media_button['label'],
					$media_button['shortcode'],
					$media_button['class'],
					$media_button['icon']
				);
			}
		}
	}	

	/*
	 * Determines whether to show our Media Buttons on the current page
	 */
	function show_media_buttons()
	{
		// only show media buttons on the admin side
		if ( ! is_admin() ) {
			return false;
		}
		
		// always show the media buttons on the shortcode editor page
		$is_sg_page = isset($_GET['page']) && ( 'company-directory-shortcode-generator' == $_GET['page'] );
		if ( $is_sg_page ) {
			return true;
		}
		
		// dont show it if our post type is in the URL
		// (we don't want to allow Staff Member shortcodes to be inserted into Staff Member bios)
		if( ! empty($_REQUEST['post_type']) && 'staff-member' == $_REQUEST['post_type'] ) {
			return false;
		}

		// detect our post type based on a post ID in the URL
		// (we don't want to allow Staff Member shortcodes to be inserted into Staff Member bios)
		$cur_post_id = isset($_GET['post'])
					   ? intval($_GET['post'])
					   : '';
		$cur_post_type = ! empty($cur_post_id)
						 ? get_post_type($cur_post_id)
						 : '';
		if( 'staff-member' == $cur_post_type ) {
			return false;
		}

		// all checks passed, its OK to show it.
		return true;
	}

	function get_welcome_template()
	{
		$base_path = plugin_dir_path( __FILE__ );
		$template_path = $base_path . '/include/content/welcome.php';
		$is_pro = $this->is_pro();
		$plugin_title = 'Company Directory';
		$content = file_exists($template_path)
				   ? include($template_path)
				   : '';
		return $content;
	}	
	
	function add_hooks()
	{
		add_action('init', array($this, 'remove_features_from_custom_post_type'));
		add_action('plugins_loaded', array($this, 'init_post_types'));
		add_filter( 'rest_prepare_staff-member', array($this, 'filter_staff_member_json'), 10, 3 );
		add_filter( 'get_search_form', array($this, 'maybe_replace_search_form'), 10, 2 );
		
		add_shortcode('single_staff', array($this, 'staff_member_shortcode'));
		add_shortcode('staff_member', array($this, 'staff_member_shortcode'));
		add_shortcode('staff_list', array($this, 'staff_list_shortcode'));
		add_shortcode('search_staff_members', array($this, 'search_staff_members_shortcode'));

		/* Private Mode Shortcodes */
		add_shortcode('cd_login_form', array($this, 'cd_login_form_shortcode'));
		add_shortcode('cd_logged_in_message', array($this, 'cd_logged_in_message_shortcode'));
		add_shortcode('cd_logged_out_message', array($this, 'cd_logged_out_message_shortcode'));

		if ( function_exists('register_block_type') ) {
			register_block_type( 'staff-directory-pro/single-staff', array(
				'editor_script' => 'single-staff-block-editor',
				'editor_style'  => 'single-staff-block-editor',
				'style'         => 'single-staff-block',
				'render_callback' => array($this, 'staff_member_shortcode'),
			) );

			register_block_type( 'staff-directory-pro/staff-list', array(
				'editor_script' => 'staff-list-block-editor',
				'editor_style'  => 'staff-list-block-editor',
				'style'         => 'staff-list-block',
				'render_callback' => array($this, 'staff_list_shortcode'),
			) );
			
			register_block_type( 'staff-directory-pro/search-staff', array(
				'editor_script' => 'search-staff-block-editor',
				'editor_style'  => 'search-staff-block-editor',
				'style'         => 'search-staff-block',
				'render_callback' => array($this, 'search_staff_members_shortcode'),
			) );
		}					

		add_filter( 'block_categories_all', array($this, 'add_gutenburg_block_category'), 10, 2 );

		/* Allow the user to override the_content template for single staff members */
		add_filter('the_content', array($this, 'single_staff_content_filter'));
		
		/* Keep the extra info we've added with the_content filter from appearing in the excerpt */
		add_filter('get_the_excerpt', array($this, 'fix_staff_member_excerpts'));
						
		/* Allow the user to override search form for staff members */
		add_filter('search_template', array($this, 'use_custom_search_template'));
				
		// add our custom meta boxes
		add_action( 'admin_menu', array($this, 'add_meta_boxes'));
		
		//flush rewrite rules - only do this once!
		register_activation_hook( __FILE__, array($this, 'activation_hook' ) );
		
		$plugin = plugin_basename(__FILE__);
		add_filter( "plugin_action_links_{$plugin}", array($this, 'add_settings_link_to_plugin_action_links') );
		add_filter( 'plugin_row_meta', array($this, 'add_custom_links_to_plugin_description'), 10, 2 );	

		add_action( 'save_post', array( &$this, 'update_name_fields' ), 1, 2 );
		
		//register sidebar widgets
		add_action( 'widgets_init', array( &$this, 'register_widgets') );
		
		//clean broken images from staff members, preventing occasional fatal errors on edit screens
		add_action( 'load-post.php', array($this,'fix_staff_member_featured_images') );
		
		//add our custom links for Settings and Support to various places on the Plugins page
		$plugin = plugin_basename(__FILE__);
		add_filter( "plugin_action_links_{$plugin}", array($this, 'add_settings_link_to_plugin_action_links') );
		add_filter( 'plugin_row_meta', array($this, 'add_custom_links_to_plugin_description'), 10, 2 );	
		
		// add menu order support
		if ( $this->get_sd_option('enable_manual_staff_order', true) ) {
			add_filter( 'admin_enqueue_scripts', array($this, 'enqueue_sortable_js') );
			add_filter( 'manage_edit-staff-member_columns', array($this, 'add_sortable_column') );
			add_action( 'manage_staff-member_posts_custom_column', array($this, 'staff_member_column_content'), 10, 2 );
			add_filter( 'manage_edit-staff-member_sortable_columns', array($this, 'disable_column_sorting_on_staff_members') );
			add_filter( 'wp_ajax_company_directory_save_new_menu_order', array($this, 'save_posted_menu_order') );
			add_action( 'pre_get_posts',  array($this, 'staff_members_force_sort_order') );
		}
	}
	
	function register_widgets()
	{
		register_widget( 'GP_Staff_List_Widget' );
		register_widget( 'GP_Single_Staff_Widget' );
		register_widget( 'GP_Search_Staff_Widget' );
	}
	
	function setup_post_type_metadata()
	{
		$options = get_option( 'sd_options' );		
		$exclude_from_search = ( isset($options['include_in_search']) && $options['include_in_search'] == 0 );		
	
		//optional definable single view slug
		//defaults to staff-members
		$single_view_slug = !empty( $options['single_view_slug'] ) ? $options['single_view_slug'] : 'staff-members';

		$this->postType = array(
			'name' => 'Staff Member',
			'plural' => 'Staff Members',
			'slug' => $single_view_slug, 
			'exclude_from_search' => $exclude_from_search,
		);
		
		$this->customFields = array();
		$this->customFields[] = array('name' => 'first_name', 'title' => 'First Name', 'description' => 'Steven, Anna', 'type' => 'text');	
		$this->customFields[] = array('name' => 'last_name', 'title' => 'Last Name', 'description' => 'Example: Smith, Goldstein', 'type' => 'text');	
		$this->customFields[] = array('name' => 'title', 'title' => 'Title', 'description' => 'Example: Director of Sales, Customer Service Team Member, Project Manager', 'type' => 'text');	
		$this->customFields[] = array('name' => 'phone', 'title' => 'Phone', 'description' => 'Best phone number to reach this person', 'type' => 'text');
		$this->customFields[] = array('name' => 'email', 'title' => 'Email', 'description' => 'Email address for this person', 'type' => 'text');		
		$this->customFields[] = array('name' => 'address', 'title' => 'Mailing Address', 'description' => 'Mailing address for this person', 'type' => 'textarea');		
		$this->customFields[] = array('name' => 'website', 'title' => 'Website', 'description' => 'Website URL for this person', 'type' => 'text');
	}
	
	function create_post_types()
	{
		$options = get_option( 'sd_options' );
		$use_classic_editor = ( ! isset( $options['use_classic_editor'] ) || ! empty($options['use_classic_editor']) );
		$this->customFields = apply_filters( 'company_directory_custom_fields', $this->customFields);
		$this->add_custom_post_type($this->postType, $this->customFields, false, $use_classic_editor);
		
		//adds single staff member shortcode to staff member list
		add_filter('manage_staff-member_posts_columns', array($this, 'column_head'), 10);  
		add_action('manage_staff-member_posts_custom_column', array($this, 'columns_content'), 10, 2); 
		
		// adds a "Bio" heading above the editor for add/edit Staff Member pages
		if ( $use_classic_editor ) {
			add_action( 'edit_form_after_title', array($this, 'add_content_before_editor'), 10, 1 );
		}
		
		//load list of current posts that have featured images	
		$supportedTypes = get_theme_support( 'post-thumbnails' );
		
		//none set, add them just to our type
		if( $supportedTypes === false ){
			add_theme_support( 'post-thumbnails', array( 'staff-member' ) );        
		}
		//specifics set, add our to the array
		elseif( is_array( $supportedTypes ) ){
			$supportedTypes[0][] = 'staff-member';
			add_theme_support( 'post-thumbnails', $supportedTypes[0] );
		}
	}
	
	function add_meta_box_hooks()
	{
		if ( !$this->proUser ) {
			add_action( 'gp_cpt_meta_box_start_staff-member', array($this, 'meta_box_start') );
			add_action( 'gp_cpt_meta_box_end_staff-member', array($this, 'meta_box_end') );
		}
	}
	
	function meta_box_start()
	{
		$upgrade_message = 'Reclaim your time. Import your Staff Members from a file, LDAP, or Active Directory server. ';
		$link_text = 'Find out why you should upgrade to Company Directory Pro';
		$link_url = 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=free_plugin&utm_term=import_export_wizard';
		$this->admin_message_box($upgrade_message, $link_text, $link_url);
	}
	
	function meta_box_end()
	{
		$upgrade_message = 'Do you want to modify these fields or add your own? You can with, Company Directory Pro.';
		$upgrade_text = 'Find out why you should upgrade to Company Directory Pro';
		$upgrade_url = 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=free_plugin&utm_term=custom_fields';
		$this->admin_message_box($upgrade_message, $upgrade_text, $upgrade_url);
	}
	
	function admin_message_box($message, $link_text, $link_url)
	{
		$div_style = 'line-height: 2.25; font-size: 15px; margin-top: 20px; padding: 10px 10px 10px 12px; border: 1px solid #ddd; border-left: 8px solid purple; background-color: lightyellow; background: rgb(255,255,255); background: linear-gradient(45deg, rgba(255,255,255,1) 70%, rgba(241,241,241,1) 100%);';
		$link = sprintf(
			'<a href="%s" target="_blank">%s</a>', 
			esc_url($link_url),
			esc_html($link_text)
		);
		printf(		
			'<div class="gp_upgrade_notification" style="%s">%s<br>%s</div>',
			esc_html($div_style),
			esc_html($message),
			$link
		);
	}
	
	function register_taxonomies()
	{
		$this->add_taxonomy('staff-member-category', 'staff-member', 'Department', 'Departments', array('show_in_rest' => true) );
		
		//adds staff members by category shortcode displayed
		add_filter('manage_edit-staff-member-category_columns', array($this, 'cat_column_head'), 10);  
		add_action('manage_staff-member-category_custom_column', array($this, 'cat_columns_content'), 10, 3);
	}

	function add_meta_boxes(){
		add_meta_box( 'staff_member_shortcode', 'Shortcodes', array($this,'display_shortcodes_meta_box'), 'staff-member', 'side', 'default' );
	}
	
	/* Disable some of the normal WordPress features on the Staff Member custom post type (the editor, author, comments, excerpt) */
	function remove_features_from_custom_post_type()
	{
		//remove_post_type_support( 'staff-member', 'editor' );
		remove_post_type_support( 'staff-member', 'excerpt' );
		remove_post_type_support( 'staff-member', 'comments' );
		remove_post_type_support( 'staff-member', 'author' );
	}

	function add_stylesheets_and_scripts()
	{
		$cssUrl = plugins_url( 'assets/css/staff-directory.css' , __FILE__ );
		$this->add_stylesheet('staff-directory-css',  $cssUrl);		
	}
	
	function single_staff_content_filter($content)
	{
		if ( empty($this->in_widget) && get_post_type() == 'staff-member' ) {
			global $staff_data;
			$staff_data = $this->get_staff_data_for_post();
			$staff_data['content'] = $content;			
			$staff_data['is_single_post'] = !empty( $staff_data['is_single_post'] )
											? $staff_data['is_single_post']
											: false;
			
			// name is hidden on single views by default, because its already output as the title of the page in most themes
			$single_view_options = apply_filters('company_directory_single_view_options', array('show_name' => 0), $staff_data);			
			$staff_data['options'] = $this->get_single_view_options( $single_view_options ) ; 
			
			if ( is_single() ) {
				// single views used to be the the only place the content was overriden, so the template
				// had a different name. Check for it first, for backwards compatibility
				$template_content = $this->get_template_content('single-staff-member-content.php');
				
				// if the old template name wasn't found, look for the template under its new name
				if (empty($template_content)) {
					$template_content = $this->get_template_content('content-staff-member.php');
				}
			}
			else {
				// look for a content template
				$template_content = $this->get_template_content('content-staff-member.php');
			}
			return $template_content;
			
		}
		return $content;
	}
	
	/* 
	 * Returns an array of all the show_X options for the single view,
	 * overriding the defaults with any attributes passed in.
	 *
	 * @param $atts array Any attributes which should be overriden. The default
	 *					  will be used for any unspecified keys.
	 *
	 * @return array The merged options array.
	 */
	function get_single_view_options($atts = array())
	{
		$defaults = array(
			'show_photo' => 'true',
			'show_name' => 'true',
			'show_title' => 'true',
			'show_bio' => 'true',
			'show_phone' => 'true',
			'show_email' => 'true',
			'show_address' => 'true',
			'show_website' => 'true',
			'show_photo' => 'true',
			'show_department' => 'true',
		);
		return shortcode_atts($defaults, $atts);
	}
	
	/**
	 * Enables the user to add a template to their theme, 
	 * search-staff-members.php, which will be used only for staff member 
	 * search results. They should base it on their themes search.php.
	 * Hooks into WP's search_template action
	 *
	 * @param string $original_template The file path of the current search 
	 *									template.
	 *
	 * @return string The file path of the search template to use instead.
	 */
	function use_custom_search_template($original_template)
	{
		if ( !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'staff-member' ) {
			$custom_template = locate_template('search-staff-members.php');
			return !empty($custom_template) ? $custom_template : $original_template;
		} else {
			return $original_template;
		}
	}	
	
	/* Shortcodes */
	
	/* output a list of all staff members */
	function staff_list_shortcode($atts, $content = '')
	{
		// check for private mode
		if ( $this->private_mode_enabled() && !is_user_logged_in() ) {
			return $this->get_private_mode_message();			
		}

		$options = get_option('sd_options');
		
		// merge any settings specified by the shortcode with our defaults
		$defaults = array(	
			'id' => '',
			'class' => '',
			'caption' => '', // TODO: deprecated?
			'show_photos' => true,
			'show_name' => true,
			'show_title' => true,
			'show_bio' => true,
			'show_photo' => true,
			'show_phone' => true,
			'show_email' => true,
			'show_address' => true,
			'show_website' => true,
			'show_department' => true,
			'style' => 'list',
			'category' => false,
			'group_by_category' => false,
			'category_order' 	=> 'ASC',
			'category_orderby' 	=> 'name',
			'category_heading_tag' 	=> 'h3',
			'count' => -1,
			'in_widget' => false,
			'order_by' => 'last_name',
			'order' => 'ASC',
			'per_page' => -1,			
		);
		$defaults['use_placeholder_photo'] = true;
		$defaults = apply_filters('company_directory_staff_list_defaults', $defaults, $atts);

		/* Merge supplied attributes with defaults */
		$atts = shortcode_atts($defaults, $atts);

		/* generate a GUID we can use for this instance */
		$atts['guid'] = $this->generate_guid();
		
		$this->in_widget = true;
		$vars['pagination_link_template'] = $this->get_pagination_link_template( 'staff_page');
		$vars['current_page'] = ! empty($_REQUEST['staff_page']) && intval($_REQUEST['staff_page']) > 0
								? intval($_REQUEST['staff_page'])
								: 1;
		$html = '';
		
		// add id. if one was not passed, add one representing the guid
		$atts['id'] = $this->get_staff_list_id($atts);

		// add classes representing options
		$atts['class'] = implode(' ', $this->get_staff_list_classes($atts) );

		$atts = apply_filters('company_directory_staff_list_attributes', $atts);
		
		// get a Custom Loop for the staff custom post type, and pass it to the template
		if (!$atts['group_by_category'])
		{
			// do not group by category (default)
			$vars['staff_loop'] = $this->get_staff_members_loop($atts['count'], $atts['category'], false, $atts['order_by'], $atts['order'], $atts['per_page']);
			$html = $this->render_staff_list($atts, $vars);
		}
		else
		{
			// group by category
			$all_cats = $this->get_all_staff_categories($atts['category_order'], $atts['category_orderby']);
			foreach($all_cats as $term) {
				
				// add heading
				$heading_template = sprintf('<%s class="staff_category_heading" id="staff-category-heading-%%d">%%s</%s>', $atts['category_heading_tag'], $atts['category_heading_tag']);
				$html .= sprintf($heading_template, $term->term_id, $term->name);
			
				// add loop html
				$vars['staff_loop'] = $this->get_staff_members_loop($atts['count'], $term->slug, false, $atts['order_by'], $atts['order'], $atts['per_page']);
				$html .= $this->render_staff_list($atts, $vars);
			}
		}
		
		// always reset in_widget to false
		$this->in_widget = false;
		
		return $html;
	}
	
	function get_login_form()
	{
		$login_form_options = array();
		$login_form_options = apply_filters('company_directory_login_form_options', $login_form_options);
		$login_form_options['echo'] = false; // this is the only option we cannot let them edit			
		$form = wp_login_form( $login_form_options );			
		return apply_filters('company_directory_login_form', $form, $login_form_options);
	}
	
	function get_private_mode_message()
	{
		$options = get_option('sd_options');
		$form = !empty($options['private_mode_show_login_form'])
				? $this->get_login_form()
				: '';
		
		$message = !empty($options['private_mode_login_message'])
				   ? $options['private_mode_login_message']
				   : '';
				   
		if ( !empty($message) && !empty($form) ) {
			$output = $message . "<br><br>" . $form;
		}
		else if ( !empty($message) && empty($form) ) {
			$output = $message;
		}
		else if ( empty($message) && !empty($form) ) {
			$output = $form;
		}
		else if ( empty($message) && empty($form) ) {
			$output = '';
		}

		return apply_filters('company_directory_private_mode_output', $output, $message, $form);		
	}
	

	/* 
	 * Output a grid of all staff members
	 */
	function staff_grid_shortcode($atts, $content = '')
	{
		// check for private mode
		if ( $this->private_mode_enabled() && !is_user_logged_in() ) {
			return $this->get_private_mode_message();			
		}
		
		$atts['style'] = 'grid';
		$html = $this->staff_list_shortcode($atts, $content);
		$html = apply_filters('company_directory_staff_grid_html', $html, $atts);
		return $html;
	}

	
	/* 
	 * Generates a CSS id based on the chosen attributes.
	 * If an ID parameter is passed, it will be used. Otherwise, 
	 * the GUID will be used.
	 *
	 * Applies the company_directory_staff_list_classes filter to the id
	 * just before its returned.
	 *
	 * @param array Shortcode attributes
	 *
	 * @return string CSS id to use for this staff list
	 */
	function get_staff_list_id($atts)
	{
		$id = !empty($atts['id'])
			  ? $atts['id']
			  : '';

		$id = empty($id) && !empty($atts['guid'])
		      ? $id = $atts['guid']
			  : '';
			  
		return apply_filters('company_directory_staff_list_id', $id, $atts);
	}
	
	/* 
	 * Generates a list of CSS classes based on the chosen attributes.
	 * For example, it add special classes like show_photo, hide_photo, 
	 * show_title, hide_title, etc. It also adds classes for category, styled,
	 * and the base class for the chosen style ('staff-list' or 'staff-grid')
	 *
	 * Applies the staff_directory_staff_list_classes filter to the final
	 * array of classes.
	 *
	 * @param array Shortcode attributes
	 *
	 * @return array List of CSS classes to be added.
	 */
	function get_staff_list_classes($atts)
	{
		$classes = array();
		
		// add the default class, staff-list
		$classes[] = 'staff-list';			
		
		// add in any classes that were passed in
		if ( !empty($atts['class']) ) {
			if ( is_array($atts['class']) ) {
				$classes += $atts['class'];
			} else {
				$classes += explode(' ', $atts['class']);	
			}
		}
		
		// add classes for all the show_X attributes
		// add show_X if its set, or hide_X if not
		$special_keys = array(
			'show_photo', 
			'show_name', 
			'show_title',
			'show_bio',
			'show_phone',
			'show_email',
			'show_address', 
			'show_website',
			'show_department',
		);
		
		foreach($special_keys as $index => $attr_key) {
			if ( !empty($atts[$attr_key]) ) {
				$classes[] = $attr_key;
			} else {
				$hide_key = str_replace('show', 'hide', $attr_key);
				$classes[] = $hide_key;
			}
		}
		
		// add class for style, if set
		if ( !empty($atts['style']) && strlen($atts['style']) > 0 ) {
			$classes[] = sprintf('style_%s', $atts['style'] );
		}
			
		// add class for category, if set
		if ( !empty($atts['category']) && strlen($atts['category']) > 0 ) {
			$classes[] = sprintf('staff_category_%s', $atts['category'] );
		}		 
		
		// apply a filter and return the list
		$classes = apply_filters('staff_directory_staff_list_classes', $classes, $atts); // old style, for backwards compatibility
		$classes = apply_filters('company_directory_staff_list_classes', $classes, $atts); // new style
		return $classes;
	}
	
	// Get a list of all staff-member-category terms
	// Pass $hide_empty = true to exclude empty categories
	function get_all_staff_categories($orderby = 'name', $order = 'ASC', $hide_empty = false)
	{
		$taxonomies = array( 
			'staff-member-category',
		);

		$args = array(
			'orderby'           => $orderby, 	// default: 'name'
			'order'             => $order,		// default: 'ASC'
			'hide_empty'        => $hide_empty, // default: false
		); 

		return get_terms($taxonomies, $args);
	}
	
	function render_staff_list($atts, $view_vars)
	{
		//only pro version of plugin can use styles other than List
		if(!$this->is_pro()){
			$atts['style'] = 'list';
		}
		
		// get template path
		$template_path = plugin_dir_path( __FILE__ ) . 'templates/staff-list.php';		
		$template_path = apply_filters('company_directory_staff_list_template_path', $template_path, $atts);

		// view vars
		$view_vars['options'] = $atts;
		$view_vars['id'] = $atts['id'];
		$view_vars = apply_filters('company_directory_staff_list_template_view_vars', $view_vars, $atts);
		
		// render the template with the view vars (can be overridden by a filter)
		$html = $this->render_template($template_path, $view_vars);
		return apply_filters('company_directory_staff_list_template_html', $html, $atts);
	}
		
	/* output a single staff members */
	function staff_member_shortcode($atts, $content = '')
	{
		// check for private mode
		if ( $this->private_mode_enabled() && !is_user_logged_in() ) {
			return $this->get_private_mode_message();			
		}
		
		// merge any settings specified by the shortcode with our defaults
		$defaults = array(	
			'caption' => '',
			'show_photo' => 'true',
			'show_name' => 'true',
			'show_title' => 'true',
			'show_bio' => 'true',
			'show_phone' => 'true',
			'show_email' => 'true',
			'show_website' => 'true',
			'show_department' => 'true',
			'show_photo' => 'true',
			'show_address' => 'true',
			'style' => 'list',
			'columns' => 'name,title,email,phone',
			'category' => false,
			'id' => false,
			'count' => -1
		);
						
		$atts = shortcode_atts($defaults, $atts);
		
		$html = '';
		
		if(!$atts['id']){
			//forgot to pass an ID!
			//do nothing!
		} else {		
			$atts['columns'] = array_map('trim', explode(',', $atts['columns']));
			
			//load up the staff data for this ID
			global $staff_data;
			$staff_data = $this->get_staff_data_for_this_post($atts['id']);
			$staff_data['options'] = $this->get_single_view_options($atts);
			$staff_data['is_single_post'] = true;
			
			//build html using loaded data
			$template_content = $this->get_template_content('single-staff-member-content.php');
				
			// if the old template name wasn't found, look for the template under its new name
			if (empty($template_content)) {
				$template_content = $this->get_template_content('content-staff-member.php');
			}
			
			$html = $template_content;
		}
		
		return $html;
	}		
	
	// returns a list of all staff members in the database, sorted by the title, ascending
	private function get_all_staff_members()
	{
		$conditions = array('post_type' => 'staff-member',
							'post_count' => -1,
							'orderby' => 'meta_value',
							'meta_key' => '_ikcf_last_name',
							'order' => 'ASC',
					);
		$all = get_posts($conditions);	
		return $all;
	}
	
	function normalize_truthy_value($input)
	{
		$input = strtolower($input);
		$truthy_values = array('yes', 'y', '1', 1, 'true', true);
		return in_array($input, $truthy_values);
	}
	
	function get_template_content($template_name, $default_content = '')
	{	
		$template_path = $this->get_template_path($template_name);
		if (file_exists($template_path)) {
			// load template by including it in an output buffer, so that variables and PHP will be run
			ob_start();
			include($template_path);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
		// couldn't find a matching template file, so return the default content instead
		return $default_content;
	}
	
	function get_template_path($template_name)
	{
		// checks if the file exists in the theme first,
		// otherwise serve the file from the plugin
		if ( $theme_file = locate_template( array ( $template_name ) ) ) {
			$template_path = $theme_file;
		} else {
			$template_path = plugin_dir_path( __FILE__ ) . 'templates/' . $template_name;
		}
		return $template_path;
	}
	
	/* Loads the meta data for a given staff member (name, phone, email, title, etc) and returns it as an array */
	function get_staff_metadata($post_id)
	{
		$ret = array(
			'ID' => '',
			'full_name' => '',
			'content' => '',
			'phone' => '',
			'email' => '',
			'title' => '',
			'address' => '',
			'website' => '',
			'department' => '',
			'first_name' => '',
			'last_name' => ''
		);
		
		$staff = get_post($post_id);
		
		if ( !empty($staff->ID) ) {
			$ret['ID'] = $staff->ID;
			$ret['full_name'] = $staff->post_title;
			$ret['content'] = $staff->post_content;
			$ret['phone'] = $this->get_option_value($staff->ID, 'phone','');
			$ret['email'] = $this->get_option_value($staff->ID, 'email','');
			$ret['title'] = $this->get_option_value($staff->ID, 'title','');
			$ret['address'] = $this->get_option_value($staff->ID, 'mailing_address','');
			$ret['website'] = $this->get_option_value($staff->ID, 'website','');
			$ret['department'] = $this->get_option_value($staff->ID, 'department','');
			$ret['first_name'] = $this->get_option_value($staff->ID, 'first_name','');
			$ret['last_name'] = $this->get_option_value($staff->ID, 'last_name','');
		}

		return $ret;
	}
	
	//loads staff data for a specific post, when already inside a loop (such as viewing a single staff member)
	function get_staff_data_for_post()
	{
		global $post;
		$staff_data = $this->get_staff_metadata($post->ID);
		//do anything to the data needed here, before returning to template
		return $staff_data;
	}
	
	//loads staff data for a specific post, when passed an ID for that post
	function get_staff_data_for_this_post($id = false)
	{
		$staff_data = $this->get_staff_metadata($id);
		//do anything to the data needed here, before returning to template
		return $staff_data;
	}

	// returns a list of all staff members in the database, sorted by the title, ascending
	// TBD: provide options to control how staff members are ordered
	private function get_staff_members_loop($count = -1, $taxonomy = false, $id = false, $order_by = 'last_name', $order = 'ASC', $per_page = -1)
	{
		// ensure $order_by is one of the allowed keys
		if ( !in_array( $order_by, $this->allowed_order_by_keys ) ) {
			$order_by ='last_name'; 
		}
		
		// ensure $order_by is one of the allowed keys
		if ( !in_array( $order, array('ASC', 'DESC') ) ) {
			$order ='ASC'; 
		}
		
		$meta_key = '_ikcf_' . $order_by;
		$nopaging = ($per_page <= 0);

		//setup conditions based upon parameters
		//no id, no taxonomy passed
		if (!$taxonomy && !$id) {
			
			if ( 'department' == $order_by ) {
				// include a taxonomy query so that Staff Members must belong to at least
				// one category to be included in this search. we could remove the $tax_query 
				// from  $conditions to remove this requirement
				$tax_query = $this->get_sort_by_department_tax_query();
				$conditions = array( 'post_type' => 'staff-member',
									 'post_count' => $count,
									 'tax_query' => $tax_query );
									
				// set a flag to sort the results after the query
				// (can't sort by taxonomy query directly in WP)
				$sort_by_dept = true;
			}
			else {
				// assume order_by is a meta key
				$conditions = array('post_type' => 'staff-member',
									'post_count' => $count,
									'orderby' => 'meta_value',
									'meta_key' => $meta_key,
									'order' => $order
				);
			}
		//no taxonomy passed
		//id passed
		} else if( !$taxonomy ) {
			$conditions = array('post_type' => 'staff-member',
								'p' => $id
			);
		//no id passed
		//category passed
		} else if( !$id ) {
			$conditions = array('post_type' => 'staff-member',
								'post_count' => $count,
								'orderby' => 'meta_value',
								'meta_key' => $meta_key,
								'order' => $order,
								'tax_query' => array(
									array(
										'taxonomy' => 'staff-member-category',
										'field'    => 'slug',
										'terms'    => $taxonomy,
									),
								),
			);
		}
		
		//if user has requested ordering by menu order
		//change our orderby parameter to menu_order
		if ( $order_by == 'menu_order' ) {
			unset($conditions['meta_key']);
			$conditions['orderby'] = 'menu_order';
		}		

		//RWG: if user has requested ordering by random order
		//	change our orderby parameter to "rand"
		//	and unset the meta_key ordering value
		if ( $order_by == 'rand' ) {
			unset($conditions['meta_key']);
			$conditions['orderby'] = 'rand';
		}		
		
		// handle paging
		$paged = ! empty($_REQUEST['staff_page']) && intval($_REQUEST['staff_page']) > 0
				 ? intval($_REQUEST['staff_page'])
				 : 1;
		if ($nopaging) {
			$conditions['nopaging'] = true;
		}
		else {
			// NOTE: if $nopaging is false, we can assume that per_page > 0
			$conditions['posts_per_page'] = $per_page;
			$conditions['paged'] = $paged;
		}
		
		$results = new WP_Query($conditions);		
		if ( $sort_by_dept ) {
			$this->department_sort_order = $order;
			usort( $results->posts, array($this, 'sort_by_department') );
			$this->department_sort_order = '';
		}
		return $results;
	}
	
	/*
 	 * Returns a taxonomy query that matches at least one department
	 *
	 * @returns array The tax_query
	 */
	function get_sort_by_department_tax_query()
	{
		$cat_terms = get_terms( array(
			'taxonomy' => 'staff-member-category',
			'fields' => 'slugs',
		) );
		$tax_query = array();
		$tax_query['relation']="OR";
		$tax_query[] = array(
			'taxonomy' => 'staff-member-category',
			'field'    => 'slug',
			'terms'    => $cat_terms,
		);
		return $tax_query;
	}
	
	function sort_by_department($post_a, $post_b)	
	{
		$dept_a = $this->get_first_department_name_by_id($post_a->ID);
		$dept_b = $this->get_first_department_name_by_id($post_b->ID);
		$direction = ( 'DESC' == strtoupper($this->department_sort_order) ) 
					 ? -1 // order = DESC
					 : 1; // order = ASC (default)
		return ($direction * (int) strcmp($dept_a, $dept_b) );
	}
	
	function get_first_department_name_by_id($post_id)
	{
		$terms = wp_get_post_terms($post_id, 'staff-member-category');
		if ( empty($terms) ) {
			return '';
		}		
		return $terms[0]->name;		
	}
	
	/* 
	 * Returns an URL template that can be passed as the 'base' param 
	 * to WP's paginate_links function
	 * 
	 * Note: This function is based on WordPress' get_pagenum_link. 
	 * It allows the query string argument to changed from 'paged'
	 */
	function get_pagination_link_template( $arg = 'staff_page' )
	{
		$request = remove_query_arg( $arg );
		
		$home_root = parse_url(home_url());
		$home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
		$home_root = preg_quote( $home_root, '|' );

		$request = preg_replace('|^'. $home_root . '|i', '', $request);
		$request = preg_replace('|^/+|', '', $request);

		$base = trailingslashit( get_bloginfo( 'url' ) );

		$result = add_query_arg( $arg, '%#%', $base . $request );
		$result = apply_filters( 'sd_get_pagination_link_template', $result );
		
		return esc_url_raw( $result );
	}	
	
	// check the reg key, and set $this->isPro to true/false reflecting whether the Pro version has been registered
	function verify_registration_key()
	{
        global $company_directory_config;
		$this->options = get_option( 'sd_options' );
		if (isset($this->options['api_key']) && 
			isset($this->options['registration_email'])) {
				
				// check the key
				$keychecker = new S_D_KeyChecker();
				$correct_key = $keychecker->computeKeyEJ($this->options['registration_email']);
				if (strcmp($this->options['api_key'], $correct_key) == 0) {
					$this->proUser = true;
				} else {
					$this->proUser = false;
				}
		
		} else {
			// keys not set, so can't be valid.
			$this->proUser = false;
			
		}
		
		// look for the Pro plugin - this is also a way to be validated
		$plugin = "company-directory-pro/company-directory-pro.php";
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );			
		if(is_plugin_active($plugin)){
			$this->proUser = true;
		}
		$company_directory_config['is_pro'] = $this->proUser;
	}
	
	function is_pro() {
		return $this->proUser;
	}

	//only do this once
	function rewrite_flush() {		
		$options = get_option( 'sd_options' );	
		
		//optional definable single view slug
		//defaults to staff-members
		$single_view_slug = !empty( $options['single_view_slug'] ) ? $options['single_view_slug'] : 'staff-members';

		$this->postType = array(
			'name' => 'Staff Member',
			'plural' => 'Staff Members',
			'slug' => $single_view_slug
		);
		
		//we need to manually create the CPT right now, so that we have something to flush the rewrite rules with!
		$gpcpt = new GoldPlugins_StaffDirectory_CustomPostType($this->postType, $this->customFields, false, true);
		$gpcpt->registerPostTypes();
		flush_rewrite_rules();
	}

	//only do this once
	function activation_hook() {
		// flush permalink rules
		$this->rewrite_flush();
					
		// make sure the welcome screen gets seen again
		if ( !empty($this->Aloha) ) {
			$this->Aloha->reset_welcome_screen();
		}		
	}
		
	/**
	  * Delete registered name field (no longer needed with Pro plugin installed)
	 */	 
	function pro_activation_hook()
	{
		$options = get_option( 'sd_options' );	
		if ( isset($options['registration_email']) ) {
			unset( $options['registration_email'] );
			update_option('sd_options', $options);
			$options = get_option( 'sd_options' );	
		}
	}	
	
	//this is the heading of the new column we're adding to the staff member posts list
	function column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the staff member post list
	function columns_content($column_name, $post_ID) {  
		if ($column_name == 'single_shortcode') {  
			printf( '<input type="text" value="[staff_member id=\'%d\']" />', intval($post_ID) );
		}  
	} 

	//this is the heading of the new column we're adding to the staff member category list
	function cat_column_head($defaults) {  
		$defaults = array_slice($defaults, 0, 2, true) +
		array("single_shortcode" => "Shortcode") +
		array_slice($defaults, 2, count($defaults)-2, true);
		return $defaults;  
	}  

	//this content is displayed in the staff member category list
	function cat_columns_content($value, $column_name, $tax_id) {  

		$category = get_term_by('id', $tax_id, 'staff-member-category');
		
		return "<input type=\"text\" value=\"[staff_list category='{$category->slug}']\" />"; 
	} 
	
	// Displays a meta box with the shortcodes to display the current Staff member
	function display_shortcodes_meta_box() {
		global $post;
		echo "Add this shortcode to any page where you'd like to <strong>display</strong> this Staff Member:<br />";
		printf( '<textarea>[staff_member id="%d"]</textarea>', intval($post->ID) );
	}//add Custom CSS
	
	function output_custom_css() {
		//use this to track if css has been output
		global $sd_footer_css_output;
		
		if($sd_footer_css_output){
			return;
		} else {
			$this->options = get_option( 'sd_options' );
			printf( '<style type="text/css" media="screen">%s</style>', esc_html($this->options['custom_css']) );
			$sd_footer_css_output = true;
		}
	}
	
	//add an inline link to the settings page, before the "deactivate" link
	function add_settings_link_to_plugin_action_links($links)
	{
		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url('admin.php?page=staff_dir-settings'), __('Settings') );
		array_unshift($links, $settings_link); 

		$docs_link = sprintf( '<a href="%s">%s</a>', 'https://goldplugins.com/documentation/company-directory-documentation/?utm_source=company_directory_free&utm_campaign=company_directory_docs', __('Documentation') );
		array_unshift($links, $docs_link); 
		
		if(!$this->is_pro()){
			$upgrade_url = 'http://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=company_directory_free_plugin&utm_campaign=upgrade_to_pro';
			$upgrade_link = sprintf( '<a href="%s" target="_blank" class="c_d_pro_link">%s</a>', $upgrade_url, __('Upgrade to Pro') );			
			array_unshift($links, $upgrade_link); 
		}

		if ( isset($links['edit']) ) {
			unset($links['edit']);
		}

		return $links; 

	}

	// add inline links to our plugin's description area on the Plugins page
	function add_custom_links_to_plugin_description($links, $file) {

		/** Get the plugin file name for reference */
		$plugin_file = plugin_basename( __FILE__ );
	 
		/** Check if $plugin_file matches the passed $file name */
		if ( $file == $plugin_file )
		{
			$new_links['settings_link'] = '<a href="admin.php?page=staff_dir-settings">Settings</a>';
			$new_links['support_link'] = '<a href="https://goldplugins.com/contact/?utm-source=plugin_menu&utm_campaign=support&utm_banner=company-directory-plugin-menu" target="_blank">Get Support</a>';
				
			if(!$this->is_pro()){
				$new_links['upgrade_to_pro'] = '<a href="https://goldplugins.com/our-plugins/company-directory-pro/upgrade-to-company-directory-pro/?utm_source=plugin_menu&utm_campaign=upgrade" target="_blank">Upgrade to Pro</a>';
			}
			
			$links = array_merge( $links, $new_links);
		}
		return $links; 
	}

	function render_search_form($atts)
	{
		// check for private mode
		if ( $this->private_mode_enabled() && !is_user_logged_in() ) {
			return $this->get_private_mode_message();			
		}
		
		$defaults = array(
			'mode' 		=> 'basic', // basic || advanced
			'order_by' 	=> 'last_name', // see $this->allowed_order_by_keys for allowed values
			'order' 	=> 'ASC', // ASC || DESC
			'search_fields'	=> '', // custom fields
			'form_title'	=> '', // heading to display before the search form
		);
		$atts = shortcode_atts($defaults, $atts);
		$atts['order'] = strtoupper($atts['order']);
		$search_html = '';
		
		// add form Title if present
		$form_title_html = '';
		if ( !empty($atts['form_title']) ) {
			$form_title_html = sprintf('<h3 class="company_directory_search_form_title">%s</h3>', $atts['form_title']);
		}
		$search_html .= apply_filters('company_directory_search_form_title', $form_title_html, $atts);
		
		do_action('company_directory_before_search_form', $atts);

		// Add our search params as hidden fields
		// NOTE: uses a member variable to send attributes to callback function
		$this->search_atts = $atts;
		add_filter('get_search_form', array($this, 'add_extra_fields_to_search_form'), 10);

		// run WordPress built in function to get the search form HTML,
		// which will be affected by the callbacks we just added
		remove_filter('get_search_form', array($this, 'maybe_replace_search_form'), 10, 2);
		$search_html .= get_search_form( false );
		add_filter('get_search_form', array($this, 'maybe_replace_search_form'), 10, 2);

		// clear out the member variable and filters, now that the callback has run
		$this->search_atts = false;
		remove_filter('get_search_form', array($this, 'add_extra_fields_to_search_form'));
		
		do_action('company_directory_after_search_form', $atts);
		
		return $search_html;
	}
	
	
	function search_staff_members_shortcode($atts, $content = '')
	{
		return $this->render_search_form($atts);
	}
	
	function maybe_replace_search_form($form, $args = [])
	{
		if ( ( ! empty($_GET['_search_directory']['search_mode']) ) && is_array($_GET['_search_directory']) ) {

			$mode = !empty( $_GET['_search_directory']['search_mode'] ) && 'advanced' == $_GET['_search_directory']['search_mode']
					? 'advanced'
					: 'basic';
			
			$order = !empty( $_GET['_search_directory']['order'] ) && in_array( $_GET['_search_directory']['order'], ['ASC', 'DESC'] )
					 ? sanitize_text_field($_GET['_search_directory']['order'])
					 : 'ASC';
			
			$order_by = !empty( $_GET['_search_directory']['order_by'] )
					? sanitize_text_field($_GET['_search_directory']['order_by'])
					: 'last_name';
			
			$search_fields = implode(',', $this->gather_search_fields_from_request());
			
			// recreate atts from query string
			$atts = compact('mode', 'order_by', 'order', 'search_fields');
			
			$form = $this->render_search_form($atts);
		}

		return $form;
	}
	
	function gather_search_fields_from_request()
	{
		$fields = [];
		$excluded = ['search_mode', 'order', 'order_by'];
		if ( ! empty( $_GET['_search_directory'] && is_array($_GET['_search_directory']) ) ) {
			foreach($_GET['_search_directory'] as $key => $val) {
				$key = sanitize_text_field($key);
				if ( in_array($key, $excluded) ) {
					continue;
				}
				else if ( 'staff_category' == $key ) {
					// correct 'staff_category' to 'department'
					$key = 'department';
				}
				$fields[] = sanitize_text_field($key);
			}			
		}
		return $fields;
	}
	
	function cd_login_form_shortcode($atts, $content = '')
	{
		return $this->get_login_form();
	}

	function cd_logged_in_message_shortcode($atts, $content = '')
	{
		if ( is_user_logged_in() ) {
			return $content;
		} else {
			return '';
		}
	}

	function cd_logged_out_message_shortcode($atts, $content = '')
	{
		if ( !is_user_logged_in() ) {
			return $content;
		} else {
			return '';
		}
	}
	
	function add_extra_fields_to_search_form($search_html)
	{
		// restrict_search_to_custom_post_type
		$post_type = 'staff-member';
		$replace_with = sprintf('<input type="hidden" name="post_type" value="%s">', $post_type);		

		$replace_with = apply_filters('company_directory_search_form_hidden_fields', $replace_with, $this->search_atts, $search_html);

		$replace_with = $replace_with . '</form>';
		$search_html = str_replace('</form>', $replace_with, $search_html);		
		return $search_html;
	}
	
	/* If the user did not specify a first and/or last name field, set those fields now */
	function update_name_fields($post_id, $post)
	{
		/* Only run on OUR custom post type */
		if ($post->post_type !== 'staff-member') {
			return;
		}
	
		/* Only run when the user actually clicks save, NOT on auto saves or ajax */
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
			 || (defined('DOING_AJAX') && DOING_AJAX)
			 || ($post->post_status === 'auto-draft')
		) {
			return;
		}
		
		$first_name = get_post_meta($post_id, '_ikcf_first_name', true);
		$last_name = get_post_meta($post_id, '_ikcf_last_name', true);
		$full_name = get_the_title($post_id);
		
		/* Bail if the post has no title */
		if (empty($full_name)) {
			return;
		}
		
		/* If no First Name is set, set it to the FIRST word in the post's title
		 * NOTE: If the title has no spaces, this field will not be set
		 */
		if (empty($first_name)) {
			$first_space_pos = strpos($full_name, ' ');
			$new_first_name = ($first_space_pos !== FALSE) ? substr($full_name, 0, $first_space_pos) : '';
			if (!empty($new_first_name)) {
				update_post_meta($post_id, '_ikcf_first_name', $new_first_name);
			}
		}

		/* If no Last Name is set, set it to the LAST word in the post's title		
		 * NOTE: If the title has no spaces, set Last Name to the full title
		 */
		if (empty($last_name)) {
			$last_space_pos = strrpos($full_name, ' ');			
			$new_last_name = ($last_space_pos !== FALSE) ? substr($full_name, $last_space_pos + 1) : $full_name;
			if (!empty($new_last_name)) {
				update_post_meta($post_id, '_ikcf_last_name', $new_last_name);
			}
		}
	}
	
	function fix_staff_member_excerpts($excerpt)
	{
		$post = get_post();
		if ( empty( $post ) || $post->post_type !== 'staff-member' ) {
			return $excerpt;
		}
		else {
			return wp_trim_excerpt($post->post_content);
		}
	}
	
	//checks for a WP_Error on this staff member's featured image
	//if there is an error, we unset the image to prevent edit screen from breaking
	function fix_staff_member_featured_images()
	{
		//if there is a post
		if ( !empty($_GET['post']) ){
			
			$cur_post_id = intval($_GET['post']);
			if ( empty($cur_post_id) ) {
				return;
			}
			
			// Get the post object
			$post = get_post($cur_post_id);
			
			//and its a staff member
			if( !empty($post->post_type) && "staff-member" == $post->post_type ) {
				// If the post has a bad featured image, remove the meta
				if ( is_wp_error( get_post_thumbnail_id($post->ID) ) ) {
					delete_post_meta($post->ID, '_thumbnail_id');
				}
			}
		}
	}
	
	function generate_guid()
	{		
		return sprintf( 'staff_list_%s', substr( md5( rand() ), 0, 20) );
	}
	
	function enqueue_sortable_js($hook = '')
	{
		if ( strpos($hook, 'edit.php') === false ) {
			return;	
		}
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-sortable');
		wp_register_script( 'company_directory_sortable_staff', 
							plugins_url('assets/js/sortable_staff.js', __FILE__),
							array('jquery', 'jquery-ui-sortable'),
							false,
							true );

		// Localize the script with new data
		$page_num = intval( get_query_var( 'paged', 0 ) );
		$per_page = intval( get_query_var( 'posts_per_page', 10 ) );
		$starting_index = !empty($page_num)
						  ? max(0, ( ($page_num - 1) * $per_page) )
						  : 0;			
		$translation_array = array(
			'starting_index' => $starting_index,
		);
		
		wp_localize_script( 'company_directory_sortable_staff', 'company_directory', $translation_array );
		wp_enqueue_script( 'company_directory_sortable_staff');
	}
	
	function add_sortable_column($columns) {
		$columns['menu_order'] =__('Reorder');
		return $columns;
	}
	
	function staff_member_column_content( $column_name, $post_id ) {
		if ( 'menu_order' != $column_name ) {
			return;
		}

		//Get number of slices from post meta
		$menu_order = get_post_field('menu_order', $post_id);
		printf( '<input type="text" value="%d" name="cd_menu_order[%d]" class="menu_order_input" data-post-id="%d" />', intval($menu_order), $post_id, $post_id );
		print ( '<span class="sortable_handle"></span>' ) ;
	}	

	function disable_column_sorting_on_staff_members( $columns ) {
		$columns = array();
		//$columns['menu_order'] = 'menu_order';
		return $columns;
	}
	
	function save_posted_menu_order()
	{
		if ( empty($_POST['menu_order']) || ! is_array($_POST['menu_order']) ) {
			echo "Err";
			wp_die();
		}
		
		$new_menu_order = array_map( 'intval', $_POST['menu_order'] );
		
		foreach($new_menu_order as $post_id => $menu_order) {
			$post_id = intval($post_id);
			if ( !current_user_can('edit_post', $post_id) ) {
				continue;
			}
			
			$my_post = array(
				'ID'           => intval($post_id),
				'menu_order'   => intval($menu_order)
			);
			wp_update_post($my_post);
		}
		
		echo "OK";
		wp_die();
	}

	function staff_members_force_sort_order( $query )
	{
		
		// only run when viewing the list of Staff Members in the admin area
		// ( typically admin_url(edit.php?post_type=staff_member) )		
		if ( is_admin()
			&& ( strpos($_SERVER['REQUEST_URI'], 'edit.php?') !== false
			&& $query->get('post_type')=='staff-member' )
		) {
			if ($query->get('orderby') == '' ) {
				$query->set('orderby', 'menu_order');
			}
			if ($query->get('order') == '' ) {
				$query->set('order', 'ASC');
			}
			$query->set('posts_per_page', '-1');
		}
	}
	
	function get_sd_option($key, $default_value = '')
	{
		$options = get_option( 'sd_options' );
		return isset($options[$key])
			   ?  $options[$key]
			   : $default_value;
	}
	
	function filter_staff_member_json( $data, $post, $context )
	{
		// add all post meta fields that start with the '_ikcf_' prefix
		$metadata = get_post_meta( $post->ID );
		if( !empty($metadata) ) {
			$meta_filtered = array();
			foreach( $metadata as $index => $val) {
				if (strpos($index, '_ikcf_') === 0) {
					$meta_filtered[ substr($index, 6)] = $val;
				}
			}
			$data->data['metadata'] = $meta_filtered;
		}
		
		// add featured image src
		$data->data['featured_image_src'] = get_the_post_thumbnail_url($post->ID);
		
		// add featured image src
		$data->data['bio'] = get_the_content($post->ID);
		
		return $data;
	}
	
	function private_mode_enabled()
	{
		$options = get_option( 'sd_options' );
		return !empty($options['private_mode_enabled']);
	}

	function add_gutenburg_block_category ( $categories, $context ) 
	{
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'company-directory',
					'title' => 'Company Directory',
				),
			)
		);
	}
	
	function add_content_before_editor($post)
	{
		// only show on add/edit Staff Member pages
		if( 'staff-member' != $post->post_type ) {
			return;
		}
		$css_style = 'margin-top: -12px;padding-bottom: 2px; padding-left: 0;font-size: 1.5em;z-index: 2001;position: relative;top: 16px;';
		printf( '<h2 style="%s">%s</h2>', $css_style, __('Bio:', 'company-directory') );
	}
	

}
$gp_sdp = new StaffDirectoryPlugin();

// Initialize any addons now
do_action('company_directory_bootstrap');
