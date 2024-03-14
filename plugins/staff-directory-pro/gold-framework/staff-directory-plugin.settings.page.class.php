<?php
class StaffDirectoryPlugin_SettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;
	private $plugin_title;
	private $root;
	private $settings;
	private $registered_sections = array();

	// message storage
	private $messages;

    /**
     * Start up
     */
    public function __construct($root, $factory)
    {
		$this->root = $root;
		$this->Factory = $factory;
		$this->plugin_title = $root->plugin_title;
        add_action( 'admin_init', array( $this, 'create_settings' ) );
		add_action( 'admin_menu', array( $this, 'add_submenus' ), 10 );
		add_action( 'admin_menu', array($this, 'add_upgrade_to_pro_link'), 20 ); // add late, to end of list
		add_action( 'admin_init', array($this, 'add_extra_classes_to_admin_menu') );

		// add scripts and stylesheets for admin
		add_action( 'admin_init', array($this,'register_admin_scripts') );
		add_action( 'admin_enqueue_scripts',  array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'customize_controls_enqueue_scripts',  array( $this, 'enqueue_admin_scripts' ) );

		$this->Importer = $this->Factory->get('GP_Vandelay_Importer');

		add_action( 'in_admin_header', array($this, 'company_directory_admin_header') );
		add_action( 'wp_ajax_company_directory_notice_bar_dismiss', array( $this, 'ajax_notice_bar_dismiss' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_header_body_class') );
		//instantiate Sajak so we get our JS and CSS enqueued
		new GP_Sajak();
    }

	public function add_submenus()
	{
		global $submenu;
		$top_level_slug = 'edit.php?post_type=staff-member';

		$submenu_pages = array();

		if ( !$this->root->is_pro() ) {
			$submenu_pages[] = array(
				'label' => 'Import &amp; Export',
				'page_title' => 'Import &amp; Export',
				'role' => 'administrator',
				'slug' => 'company-directory-import-export',
				'callback' => array($this, 'import_export_upgrade_page')
			);
		}

		$submenu_pages[] = array(
			'label' => 'Shortcode Generator',
			'page_title' => 'Shortcode Generator',
			'role' => 'administrator',
			'slug' => 'company-directory-shortcode-generator',
			'callback' => array($this, 'shortcode_generator_page')
		);

		if ( !$this->root->is_pro() ) {
			$submenu_pages[] = array(
				'label' => 'Custom Fields',
				'page_title' => 'Custom Fields',
				'role' => 'administrator',
				'slug' => 'company-directory-custom-fields',
				'callback' => array($this, 'custom_fields_upgrade_page')
			);
		}

		$submenu_pages[] = array(
			'label' => 'Settings',
			'page_title' => 'Settings',
			'role' => 'manage_options',
			'slug' => 'staff_dir-settings',
			'callback' => array($this, 'output_settings_page')
		);

		$submenu_pages[] = array(
			'label' => 'Help & Instructions',
			'page_title' => 'Help & Instructions',
			'role' => 'administrator',
			'slug' => 'company-directory-help',
			'callback' => array($this, 'help_settings_page')
		);

		// allow addons to add menus now
		$submenu_pages = apply_filters('company_directory_admin_submenu_pages', $submenu_pages, $top_level_slug);
		$submenu_page_defaults = array(
			'label' => '',
			'page_title' => '',
			'role' => '',
			'slug' => '',
			'callback' => '',
		);

		// now add each submenu from our array
		foreach ($submenu_pages as $submenu_page) {
			$submenu_page = array_merge($submenu_page_defaults, $submenu_page);
			add_submenu_page(
				$top_level_slug,
				$submenu_page['label'],
				$submenu_page['page_title'],
				$submenu_page['role'],
				$submenu_page['slug'],
				$submenu_page['callback']
			);
		}

		// Rename "Staff Members" (top level menu) to "Company Directory"
		$this->rename_admin_menu('Staff Members', 'Company Directory');

		// Rename "Staff Members" (sub menu) to "All Staff Members"
		$new_submenu_label = __('All', 'company-directory') . ' Staff Members';
		$this->rename_admin_submenu('Staff Members', $new_submenu_label);
	}


	function add_upgrade_to_pro_link()
	{
		$top_level_slug = 'edit.php?post_type=staff-member';
		if ( !$this->root->is_pro() ) {
			add_submenu_page(
				$top_level_slug,
				__('Upgrade To Pro'),
				__('Upgrade To Pro'),
				'administrator',
				'company-directory-upgrade-to-pro',
				array($this, 'render_upgrade_page')
			);
		}
	}


    public function add_settings_group($group, $key, $display, $type = 'text')
	{

	}

    /**
     * Register and add settings
     */
    public function create_settings()
    {
		// Generic setting. We need this for some reason so that we have a chance to save everything else.
        register_setting(
            'sd_option_group', // Option group
            'sd_options', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//general settings
		 add_settings_section(
            'general', // ID
            'Basic Options', // Title
            array( $this, 'print_general_section_info' ), // Callback
            'sd_general_settings' // Page
        );

		//need to do this so these are output after the registration info
		$this->registered_sections[] = 'sd_general_settings';

		//we don't need to add this to the registered sections array as these options are directly called
		//registration settings
		 add_settings_section(
            'registration', // ID
            'Pro Registration', // Title
            array( $this, 'print_registration_section_info' ), // Callback
            'sd_registration_settings' // Page
        );

        add_settings_field(
            'sd_custom_css', // ID
            'Custom CSS', // Title
            array( $this, 'custom_css_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

		add_settings_field(
            'sd_include_in_search', // ID
            'Include Staff Members In Search Results', // Title
            array( $this, 'include_in_search_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

		add_settings_field(
            'use_classic_editor', // ID
            'Use Classic Editor', // Title
            array( $this, 'use_classic_editor_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

		add_settings_field(
            'sd_templates_detected', // ID
            'Custom Templates', // Title
            array( $this, 'custom_templates_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

        add_settings_field(
            'sd_single_view_slug', // ID
            'Single View Slug', // Title
            array( $this, 'single_view_slug_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

		add_settings_field(
            'sd_enable_manual_staff_order', // ID
            'Reorder Staff Members', // Title
            array( $this, 'sort_staff_members_callback' ), // Callback
            'sd_general_settings', // Page
            'general' // Section
        );

		// Privacy Options
		add_settings_section(
            'privacy', // ID
            'Private Mode Options', // Title
            array( $this, 'print_privacy_section_info' ), // Callback
            'sd_privacy_settings' // Page
        );

		//need to do this so these are output after the registration info
		$this->registered_sections[] = 'sd_privacy_settings';

		add_settings_field(
            'sd_private_mode_enabled', // ID
            'Private Mode', // Title
            array( $this, 'private_mode_enabled_callback' ), // Callback
            'sd_privacy_settings', // Page
            'privacy' // Section
        );

		add_settings_field(
            'sd_private_mode_login_message', // ID
            'Login Prompt', // Title
            array( $this, 'private_mode_login_message_callback' ), // Callback
            'sd_privacy_settings', // Page
            'privacy' // Section
        );

		// Placeholder Image Options
		add_settings_section(
            'placeholder_images', // ID
            __('Placeholder Images', 'staff-directory-pro'), // Title
            array( $this, 'print_placeholder_images_info' ), // Callback
            'sd_placeholder_image_settings' // Page
        );

		//need to do this so these are output after the registration info
		$this->registered_sections[] = 'sd_placeholder_image_settings';

		add_settings_field(
            'sd_enable_placeholder_images', // ID
            __('Enable Placeholder Images', 'staff-directory-pro'), // Title
            array( $this, 'enable_placeholder_images_callback' ), // Callback
            'sd_placeholder_image_settings', // Page
            'placeholder_images' // Section
        );

		add_settings_field(
            'sd_placeholder_image_path', // ID
            __('Custom Placeholder Image URL', 'staff-directory-pro'), // Title
            array( $this, 'placeholder_image_path_callback' ), // Callback
            'sd_placeholder_image_settings', // Page
            'placeholder_images' // Section
        );

		add_settings_field(
            'sd_private_mode_show_login_form', // ID
            'Private Mode', // Title
            array( $this, 'private_mode_show_login_form_callback' ), // Callback
            'sd_privacy_settings', // Page
            'privacy' // Section
        );

    }

	/*
	 * Adds a new plugin settings section.
	 */
    public function create_settings_section($section, $title, $description = '')
	{
		$page_key = $this->root->prefix . $section . '_settings';

		// Register the $section if we haven't seen it before
		if ( !in_array($page_key, $this->registered_sections) )
		{
			add_settings_section(
				$section, // ID
				$title, // Title
				array( $this, 'print_section_description' ), // Callback
				$page_key // Page
			);
			$this->section_metadata[$section] = array('title' => $title,
													  'description' => $description);
			$this->registered_sections[] = $page_key;
		}
	}

	/*
	 * Adds a new plugin setting.
	 * Note: From here, the setting is expected to "just work", meaning the framework will handle everything else (e.g., providing inputs on the settings screen)
	 */
    public function add_setting($section, $id, $title, $type = 'text', $extras = array())
	{
		$id= $this->root->prefix . '_' . $id;
		// Prepare an array of params to pass to the callback function
		$args = $extras;
		$args['id']= $id;
		$args['title']= $title;
		$args['type']= $type;
		$args['value']= ''; // TODO: should this be a default? the current value (as pulled from the database?)

		// Register the setting with WordPress
        add_settings_field(
            $id, // ID :: This is specified by $id param
            $title, // Title :: This is specified by $title param
            array( $this, 'output_setting_field' ), // Callback, a generic function
            $this->root->prefix . $section . '_settings', // Page:: Will probably be the same for all settings. Maybe optional? Either way, use a $root->prefix instead of b_a_
            $section,
			$args
        );

		/** The Plan
		 *
		 *  1) Replace "Callback" (3rd param) with a generic function, output_setting_field
		 *  2) Output_setting_field would look up the type of field, and any other meta, by the $key (hoping we can glean this from what is passed from the WP hook)
		       Note: we will store any metadata we need to in the private variables, as we cannot pass anything directly
			3)
		 */
	}

	function output_setting_field($args)
	{
		$defaults = array('id' => '',
						  'value' => '',
						  'class' => '',
						  'options' => array(),
						);
		$args = array_merge($defaults, $args);

		switch($args['type'])
		{

			default:
			case 'text':
				printf( '<input id="%s" value="%s" class="regular-text %s" />', 
						esc_attr($args['id']),
						esc_attr($args['value']),
						esc_attr($args['class']) );
				break;

			case 'textarea':
				printf( '<textarea id="%s" class="large-text %s" />%s</textarea>',
						esc_attr($args['id']),
						esc_attr($args['class']),
						wp_kses($args['value'], 'data') );
				break;

			case 'select':
				//$output = '<select id="' . $args['id'] . '" class="' . $args['class'] . '">' . htmlentities($args['value']);
				printf( '<select id="%s" class="%s">',
						esc_attr($args['id']),
						esc_attr($args['class']) );

				foreach($args['options'] as $option_value => $display) {
					$selected = strlen($args['value']) > 0 && strcmp($args['value'], $option_value) == 0
								? ' selected="selected"'
								: '';
					printf( '<option value="%s" %s>%s</option>',
							esc_attr($option_value),
							esc_attr($selected),
							wp_kses($display, 'strip') );
				}
				print ('</select>');
				break;

			case 'checkbox':
				/* TODO: checkboxes */
				break;

			case 'radio':
				/* TODO: radio buttons */
				break;

			case 'font':
				/* TODO: font inputs */
				break;
		}
	}

    /**
     * Options page callback
     */
    public function output_settings_page()
    {
		$tabs_list = array();

		$this->settings_page_top();

		$tabs_list[] = array(
			'id' => 'basic-settings', // section id, used in url fragment
			'label' => 'Basic Options', // section label
			'callback' => array($this, 'output_basic_options'), // display callback
			'options' => array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'cog' // icons here: http://fontawesome.io/icons/
			)
		);

		$tabs_list[] = array(
			'id' => 'placeholder-image-settings', // section id, used in url fragment
			'label' => 'Placeholder Images', // section label
			'callback' => array($this, 'output_placeholder_image_options'), // display callback
			'options' => array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'cog' // icons here: http://fontawesome.io/icons/
			)
		);

		$tabs_list[] = array(
			'id' => 'privacy-settings', // section id, used in url fragment
			'label' => 'Private Mode', // section label
			'callback' => array($this, 'output_privacy_options'), // display callback
			'options' => array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'cog' // icons here: http://fontawesome.io/icons/
			)
		);

		$tabs_list = apply_filters('company_directory_admin_settings_tabs', $tabs_list);

		//instantiate tabs object for output basic settings page tabs
		$tabs = new GP_Sajak( array(
			'header_label' => 'Basic Options',
			'settings_field_key' => 'sd_option_group', // can be an array
			'show_save_button' => true // hide save buttons for all panels
		) );

		foreach( $tabs_list as $tab ) {
			$tabs->add_tab(
				$tab['id'],
				$tab['label'],
				$tab['callback'],
				$tab['options']
			);
		}

		$tabs->display();

		$this->settings_page_bottom();

		$this->root->rewrite_flush();
    }

	/**
	 * Display Basic Options
	 */
	function output_basic_options(){
        $this->options = get_option( 'sd_options' );
		do_settings_sections( 'sd_general_settings' );
	}


	/**
	 * Display Placeholder Image Options
	 */
	function output_placeholder_image_options(){
        $this->options = get_option( 'sd_options' );
		do_settings_sections( 'sd_placeholder_image_settings' );
	}

	/**
	 * Display Privacy Options
	 */
	function output_privacy_options(){
        $this->options = get_option( 'sd_options' );
		do_settings_sections( 'sd_privacy_settings' );
	}

	//shortcode generator
	function shortcode_generator_page()
	{
		$this->settings_page_top( false );
		$this->shortcode_generator_page_tabs();
		$this->settings_page_bottom();
	}

	// import export upgrade page
	function import_export_upgrade_page()
	{
		//instantiate tabs object for output basic settings page tabs
		$tabs = new GP_Sajak( array(
			'header_label' => 'Import & Export',
			'settings_field_key' => 'cd_pro_option_group', // can be an array
		) );

		$tabs->add_tab(
			'import-staff', // section id, used in url fragment
			'Import from File', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'file-o', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$tabs->add_tab(
			'import-staff-from-clipboard', // section id, used in url fragment
			'Import from Clipboard', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'clipboard', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$tabs->add_tab(
			'import-staff-from-ldap', // section id, used in url fragment
			'Import from LDAP / Active&nbsp;Directory', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'user-plus', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$tabs->add_tab(
			'import-staff-scheduler', // section id, used in url fragment
			'Scheduled Import Jobs', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'calendar', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$tabs->add_tab(
			'export-staff', // section id, used in url fragment
			'Export', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'arrow-right', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$tabs->add_tab(
			'import-export-settings', // section id, used in url fragment
			'Settings', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'gear', // icons here: http://fontawesome.io/icons/
				'show_save_button' => true
			)
		);

		$tabs->add_tab(
			'import-staff-history', // section id, used in url fragment
			'History', // section label
			array($this, 'import_export_demo_screen'), // display callback
			array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'clock-o', // icons here: http://fontawesome.io/icons/
				'show_save_button' => false
			)
		);

		$this->settings_page_top();
		echo '<div class="company_directory_demo_wrap">';
		echo '<div class="company_directory_demo_wrap_inner">';
		$tabs->display();
		$this->settings_page_bottom();
		echo '</div>';
		echo '</div>';
		$this->import_export_demo_modal();

	}

	// import export upgrade page
	function custom_fields_upgrade_page()
	{
		$this->settings_page_top('Company Directory - Custom Fields');
		echo '<div class="company_directory_demo_wrap">';
		echo '<div class="company_directory_demo_wrap_inner">';
		printf( '<img src="%s" alt="Custom Fields Editor showing 2 fields" />',
			    plugins_url( '../assets/img/upgrade/custom-fields-editor-small.png', __FILE__ )
		);
		$this->settings_page_bottom();
		echo '</div>';
		echo '</div>';
		$this->custom_fields_demo_modal();

	}

	function check_icon()
	{
		print( '<div class="company_directory_checkbox"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg></div>' );
	}

	function custom_fields_demo_modal()
	{
		?>
		<div class="company_directory_demo_modal">
			<div class="company_directory_demo_modal_top">
				<h2>Customize The Information Stored For Each Staff Member</h2>
				<p><strong>The free version of Company Directory does not support Custom Fields.</strong></p>
				<p>Once you upgrade to Company Directory Pro, you will be able to change which fields are stored with each of your Staff Members.</p>
				<!--<p></p>-->
				<ul class="company_directory_feature_list company_directory_feature_list_left">
					<li><?php $this->check_icon(); ?> Supports unlimited fields</li>
					<li><?php $this->check_icon(); ?> Drag-and-drop interface</li>
					<li><?php $this->check_icon(); ?> Works with Import/Export</li>
					<li><?php $this->check_icon(); ?> Works with Advanced Search</li>
					<li><?php $this->check_icon(); ?> Available in Custom Templates</li>
					<li><?php $this->check_icon(); ?> Won't affect existing data</li>
				</ul>
				<ul class="company_directory_feature_list company_directory_feature_list_right">
					<li><?php $this->check_icon(); ?> Text Fields</li>
					<li><?php $this->check_icon(); ?> Checkboxes</li>
					<li><?php $this->check_icon(); ?> Radio Buttons</li>
					<li><?php $this->check_icon(); ?> Dropdown Menus</li>
					<li><?php $this->check_icon(); ?> Multiple Choice Fields</li>
					<li><?php $this->check_icon(); ?> Email / Phone / Date Fields</li>
				</ul>
				<div style="clear:both"></div>
			</div>
			<div class="company_directory_demo_modal_bottom">
				<a class="company_directory_btn" href="<?php echo esc_url( $this->get_upgrade_url('placeholders', 'custom_fields') ); ?>" target="_blank">Upgrade To Company Directory Pro Now</a>
				<p class="company_directory_after_button_text"><em>and start customizing your Custom Fields!</em></p>
			</div>
		</div>
		<?php
	}

	function import_export_demo_modal()
	{
		?>
		<div class="company_directory_demo_modal">
			<div class="company_directory_demo_modal_top">
				<h2>Import &amp; Export Your Staff From Any File, LDAP, or Active Directory Server</h2>
				<p><strong>The free version of Company Directory does not support Import and Export.</strong></p>
				<p>Once you upgrade to Company Directory Pro, you will be able to Import and Export your staff members, including those you've already entered.</p>
				<!--<p></p>-->
				<ul class="company_directory_feature_list company_directory_feature_list_left">
					<li><?php $this->check_icon(); ?> Import from 200+ file types</li>
					<li><?php $this->check_icon(); ?> Import from CSV &amp; Excel files</li>
					<li><?php $this->check_icon(); ?> Import from LDAP servers</li>
					<li><?php $this->check_icon(); ?> Import from Active Directory</li>
				</ul>
				<ul class="company_directory_feature_list company_directory_feature_list_right">
					<li><?php $this->check_icon(); ?> Easy-To-Use Import Wizard</li>
					<li><?php $this->check_icon(); ?> Scheduled Imports</li>
					<li><?php $this->check_icon(); ?> Column Mapping</li>
					<li><?php $this->check_icon(); ?> Background Processing</li>
				</ul>
				<div style="clear:both"></div>
			</div>
			<div class="company_directory_demo_modal_bottom">
				<a class="company_directory_btn" href="<?php echo esc_url( $this->get_upgrade_url('placeholders', 'import_export') ); ?>" target="_blank">Upgrade To Company Directory Pro Now</a>
				<p class="company_directory_after_button_text"><em>and import your entire staff in moments!</em></p>
			</div>
		</div>
		<?php
	}

	function get_upgrade_url($campaign = 'freeplugin', $medium = 'general')
	{
		global $wp_version;
		$base_url = 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?';

		$params = array(
			'utm_source' => 'WordPress',
			'utm_campaign' => $campaign,
			'utm_medium' => $medium,
			'wp_version' => $wp_version,
			'plugin_version' => $this->get_plugin_version(),
		);
		// TODO: add days since install (buckets?)

		return $base_url . http_build_query($params);
	}

	function get_plugin_version()
	{
		$cached_val = wp_cache_get( 'company_directory_free_version' );
		if ( !empty($cached_val) ) {
			return $cached_val;
		}

		$all = get_plugins();
		if ( empty($all['staff-directory-pro/staff-directory.php']) ) {
			return '';
		}

		if ( empty($all['staff-directory-pro/staff-directory.php']['Version']) ) {
			return '';
		}

		$version = $all['staff-directory-pro/staff-directory.php']['Version'];
		wp_cache_set( 'company_directory_free_version', $version );
		return $version;
	}



	function import_export_demo_screen()
	{
		echo "<h2>Import & Export</h2>";
	}

	function shortcode_generator_page_tabs()
	{
		//add upgrade button if free version
		$extra_buttons = array();
		if(!$this->root->is_pro()){
			$extra_buttons = array(
				array(
					'class' => 'btn-purple',
					'label' => 'Upgrade To Pro',
					'url' => 'https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/'
				)
			);
		}

		$tabs = new GP_Sajak( array(
			'header_label' => 'Shortcode Generator',
			'settings_field_key' => 'sd_sc_gen_option_group', // shortcode generator has no settings
			'show_save_button' => false, // hide save buttons for all panels
			'extra_buttons_header' => $extra_buttons,
			'extra_buttons_footer' => $extra_buttons
		) );

		$tabs->add_tab(
			'shortcode_generator', // section id, used in url fragment
			'Shortcode Generator', // section label
			array($this, 'output_shortcode_generator'), // display callback
			array(
				'icon' => 'gear' // icons here: http://fontawesome.io/icons/
			)
		);

		$tabs->display();
	}

	function output_shortcode_generator()
	{
		?>
		<div id="gold_plugins_shortcode_generator wp-content-wrap">
			<h3>Shortcode Generator</h3>

			<p>Using the buttons below, select your desired method and options for displaying Staff.</p>
			<p>Instructions:</p>
			<ol>
				<li>Click the Staff button, below,</li>
				<li>Pick from the available display methods listed, such as Staff List,</li>
				<li>Set the options for your desired method of display,</li>
				<li>Click "Insert Now" to generate the shortcode.</li>
				<li>The generated shortcode will appear in the textarea below - simply copy and paste this into the Page or Post where you would like Staff to appear!</li>
			</ol>

			<div id="company-directory-shortcode-generator">
			<?php
				$content = "";//initial content displayed in the editor_id
				$editor_id = "company_directory_shortcode_generator";//HTML id attribute for the textarea NOTE hyphens will break it
				$settings = array(
					//'tinymce' => false,//don't display tinymce
					'quicktags' => false,
				);
				wp_editor($content, $editor_id, $settings);
			?>
			</div>
		</div><!-- end #gold_plugins_shortcode_generator -->
		<?php
	}

	//help page / documentation
	function help_settings_page()
	{
		$tabs_list = array();

		$this->settings_page_top();

		$tabs_list[] = array(
			'id' => 'help-center-settings', // section id, used in url fragment
			'label' => 'Help Center', // section label
			'callback' => array($this, 'output_help_center'), // display callback
			'options' => array(
				'class' => 'extra_li_class', // extra classes to add sidebar menu <li> and tab wrapper <div>
				'icon' => 'life-buoy' // icons here: http://fontawesome.io/icons/
			)
		);

		$tabs_list = apply_filters('company_directory_admin_help_tabs', $tabs_list);

		//instantiate tabs object for output basic settings page tabs
		$tabs = new GP_Sajak( array(
			'header_label' => 'Help & Instructions',
			'settings_field_key' => 'sd_option_group', // can be an array
			'show_save_button' => false
		) );

		foreach( $tabs_list as $tab ) {
			$tabs->add_tab(
				$tab['id'],
				$tab['label'],
				$tab['callback'],
				$tab['options']
			);
		}

		$tabs->display();

		$this->settings_page_bottom();

		$this->root->rewrite_flush();
	}

	function output_help_center(){
		?>
		<h3>Help Center</h3>
		<div class="help_box">
			<h4>Have a Question?  Check out our FAQs!</h4>
			<p>Our FAQs contain answers to our most frequently asked questions.  This is a great place to start!</p>
			<p><a class="company_directory_pro_support_button" target="_blank" href="https://goldplugins.com/documentation/company-directory-documentation/company-directory-faqs/?utm_source=help_page">Click Here To Read FAQs</a></p>
		</div>
		<div class="help_box">
			<h4>Looking for Instructions? Check out our Documentation!</h4>
			<p>For a good start to finish explanation of how to add Staff members and then display them on your site, check out our Documentation!</p>
			<p><a class="company_directory_pro_support_button" target="_blank" href="https://goldplugins.com/documentation/company-directory-documentation/?utm_source=help_page">Click Here To Read Our Docs</a></p>
		</div>
		<?php
	}

	function output_contact_support(){
		if($this->root->is_pro()){
			//load all plugins on site
			$all_plugins = get_plugins();
			//load current theme object
			$the_theme = wp_get_theme();
			//load current easy t options
			$the_options = '';//$this->load_all_options();
			//load wordpress area
			global $wp_version;

			$site_data = array(
				'plugins'	=> $all_plugins,
				'theme'		=> $the_theme,
				'wordpress'	=> $wp_version,
				'options'	=> $the_options
			);

			$current_user = wp_get_current_user();


			$options = get_option( 'sd_options' );
			?>
			<h3>Contact Support</h3>
			<p>Would you like personalized support? Use the form below to submit a request!</p>
			<p>If you aren't able to find a helpful answer in our Help Center, go ahead and send us a support request!</p>
			<p>Please be as detailed as possible, including links to example pages with the issue present and what steps you've taken so far.  If relevant, include any shortcodes or functions you are using.</p>
			<p>Thanks!</p>
			<div class="gp_support_form_wrapper">
				<div class="gp_ajax_contact_form_message"></div>

				<div data-gp-ajax-form="1" data-ajax-submit="1" class="gp-ajax-form" method="post" action="https://goldplugins.com/tickets/galahad/catch.php">
					<div style="display: none;">
						<textarea name="your-details" class="gp_galahad_site_details">
							<?php
								echo htmlentities(json_encode($site_data));
							?>
						</textarea>

					</div>
					<div class="form_field">
						<label>Your Name (required)</label>
						<input type="text" aria-invalid="false" aria-required="true" size="40" value="<?php echo (!empty($current_user->display_name) ?  $current_user->display_name : ''); ?>" name="your_name">
					</div>
					<div class="form_field">
						<label>Your Email (required)</label>
						<input type="email" aria-invalid="false" aria-required="true" size="40" value="<?php echo (!empty($current_user->user_email) ?  $current_user->user_email : ''); ?>" name="your_email"></span>
					</div>
					<div class="form_field">
						<label>URL where problem can be seen:</label>
						<input type="text" aria-invalid="false" aria-required="false" size="40" value="" name="example_url">
					</div>
					<div class="form_field">
						<label>Your Message</label>
						<textarea aria-invalid="false" rows="10" cols="40" name="your_message"></textarea>
					</div>
					<div class="form_field">
						<input type="hidden" name="include_wp_info" value="0" />
						<label for="include_wp_info">
							<input type="checkbox" id="include_wp_info" name="include_wp_info" value="1" />Include information about my WordPress environment (server information, installed plugins, theme, and current version)
						</label>
					</div>
					<p><em>Sending this data will allow the Gold Plugins can you help much more quickly. We strongly encourage you to include it.</em></p>
					<input type="hidden" name="registered_email" value="<?php echo htmlentities($options['registration_email']); ?>" />
					<input type="hidden" name="site_url" value="<?php echo htmlentities(site_url()); ?>" />
					<input type="hidden" name="challenge" value="<?php echo substr(md5(sha1('bananaphone' . $options['api_key'] )), 0, 10); ?>" />
					<div class="submit_wrapper">
						<input type="submit" class="button submit" value="Send">
					</div>
				</div>
			</div>
			<?php
		} else {
			?>
			<h3>Contact Support</h3>
			<p>Would you like personalized support? Upgrade to Pro today to receive hands on support and access to all of our Pro features!</p>
			<p><a class="button upgrade" href="https://goldplugins.com/our-plugins/company-directory-pro/?utm_source=company_directory_free&utm_campaign=upgrade&utm_banner=learn_more_gallahad">Click Here To Learn More</a></p>
			<?php
		}
	}

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
		foreach($input as $key => $value)
		{
			switch($key)
			{
				// integers only
				case 'id_number':
					$new_input['id_number'] = absint( $input['id_number'] );
				break;

				// text inputs (no HTML allowed)
				case 'email':
				case 'subject':
				case 'email_body':
				case 'api_key':
				case 'registration_url':
				case 'registration_email':
				case 'single_view_slug':
				case 'enable_placeholder_images':
				case 'placeholder_image_path':
					$new_input[$key] = sanitize_text_field( $input[$key] );
				break;

				// these fields are unfiltered
				case 'private_mode_login_message':
				case 'custom_css':
					$new_input[$key] = $input[$key];
				break;

				// checkboxes
				case 'include_in_search':
				case 'private_mode_enabled':
				case 'enable_manual_staff_order':
				case 'private_mode_show_login_form':
				case 'use_classic_editor':
					$new_input[$key] = ($input[$key] ? 1 : 0);
				break;

				default: // don't let any settings through unless they were whitelisted. (skip unknown settings)
					// do nothing
				break;
			}
		}

        return $new_input;
    }

    /**
     * Print the description for the given section
     */
    public function print_section_description($args)
    {
		$section = $args['id'];
		$meta = isset($this->section_metadata[$section]) ? $this->section_metadata[$section] : array();
		$desc = isset($meta['description']) ? $meta['description'] : '';
		echo wp_kses($desc, 'post');
    }

    /**
     * Print the Section text
     */
    public function print_general_section_info()
    {
		printf ( '<p>%s</p>', __('These options allow you to control the display of your Staff Members.', 'staff-directory-pro') );
    }

    /**
     * Print the Section text
     */
    public function print_privacy_section_info()
    {
		printf ( '<p>%s</p>', __('Use these options to enable or disable private mode.', 'staff-directory-pro') );
    }

    /**
     * Print the Section text
     */
    public function print_placeholder_images_info()
    {
		printf ( '<p>%s</p>', __('These options apply when a Staff Member does not have a photo.', 'staff-directory-pro') );
    }

    public function custom_css_callback()
    {
        printf(
            '<textarea id="custom_css" name="sd_options[custom_css]" style="width:450px" />%s</textarea>',
            isset( $this->options['custom_css'] ) ? esc_attr( $this->options['custom_css']) : ''
        );
    }

	public function include_in_search_callback()
    {
		$checked =  isset( $this->options['include_in_search'] ) && $this->options['include_in_search'] == '0' ? '' : 'checked="checked"'; // defaults to checked
		$input_html = sprintf('<input type="checkbox" id="sd_options_include_in_search" name="sd_options[include_in_search]" value="1" %s />', $checked);
		$tmpl =
			'<label for="sd_options_include_in_search">' .
				'<input type="hidden" name="sd_options[include_in_search]" value="0" />' .
				$input_html .
				'Include Staff Members in normal search results' .
            '</label>';

        printf(
			$tmpl,
            isset( $this->options['include_in_search'] ) ? esc_attr( $this->options['include_in_search']) : ''
        );
    }

	public function sort_staff_members_callback()
    {
		$checked =  isset( $this->options['enable_manual_staff_order'] ) && $this->options['enable_manual_staff_order'] == '0' ? '' : 'checked="checked"'; // defaults to checked
		$input_html = sprintf('<input type="checkbox" id="sd_options_enable_manual_staff_order" name="sd_options[enable_manual_staff_order]" value="1" %s />', $checked);
		$tmpl =
			'<label for="sd_options_enable_manual_staff_order">' .
				'<input type="hidden" name="sd_options[enable_manual_staff_order]" value="0" />' .
				$input_html .
				'Enable controls to manually reorder the Staff Members' .
            '</label>';

        printf(
			$tmpl,
            isset( $this->options['enable_manual_staff_order'] ) ? esc_attr( $this->options['enable_manual_staff_order']) : ''
        );
    }

    public function custom_templates_callback()
    {
		$tpl_path = locate_template('single-staff-member-content.php');
		if (strlen($tpl_path) > 1) {
			printf(
				'<p><strong>Single Staff Member: Custom template detected!</strong></p><p>The template file single-staff-member-content.php, located in your current theme\'s folder, will be used to display each staff member\'s single view.</p>'
				);

		}
		else {
			printf(
				'No custom templates detected.'
				);
		}
    }

	public function single_view_slug_callback()
    {
        printf(
            '<input type="text" id="single_view_slug" name="sd_options[single_view_slug]" value="%s" style="width:450px" />',
            isset( $this->options['single_view_slug'] ) ? esc_attr( $this->options['single_view_slug']) : 'staff-members'
        );
    }

	public function use_classic_editor_callback()
    {
		$options = get_option( 'sd_options' );
		$checked =  isset( $options['use_classic_editor'] ) && $options['use_classic_editor'] == '0' ? '' : 'checked="checked"'; // defaults to checked
		$input_html = sprintf('<input type="checkbox" id="sd_options_use_classic_editor" name="sd_options[use_classic_editor]" value="1" %s />', $checked);
		$tmpl =
			'<label for="sd_options_use_classic_editor">' .
				'<input type="hidden" name="sd_options[use_classic_editor]" value="0" />' .
				$input_html .
				__('Use Classic Editor for Staff Members', 'company-directory') .
            '</label>';

        printf(
			$tmpl,
            isset( $this->options['use_classic_editor'] ) ? esc_attr( $this->options['use_classic_editor']) : ''
        );
    }

	public function private_mode_login_message_callback()
    {
		$default_message = __('Please login to continue.', 'staff-directory-pro');
        printf(
            '<textarea id="private_mode_login_message" name="sd_options[private_mode_login_message]" style="width:450px">%s</textarea>',
            isset( $this->options['private_mode_login_message'] ) ? esc_attr( $this->options['private_mode_login_message']) : $default_message
        );
		print "<p>This message will be displayed to visitors who are not logged in, before the login form. HTML is allowed.</p>";
    }

    public function private_mode_enabled_callback()
	{
		$checked =  isset( $this->options['private_mode_enabled'] ) && $this->options['private_mode_enabled'] == '1' ? 'checked="checked"' : ''; // defaults to unchecked
		$input_html = sprintf('<input type="checkbox" id="sd_options_private_mode_enabled" name="sd_options[private_mode_enabled]" value="1" %s />', $checked);
		$tmpl =
			'<label for="sd_options_private_mode_enabled">' .
				'<input type="hidden" name="sd_options[private_mode_enabled]" value="0" />' .
				$input_html .
				'Only allow logged in users to view the directory or single staff members' .
            '</label>';

        printf(
			$tmpl,
            isset( $this->options['private_mode_enabled'] ) ? esc_attr( $this->options['private_mode_enabled']) : ''
        );
	}

	public function private_mode_show_login_form_callback()
	{
		$checked =  isset( $this->options['private_mode_show_login_form'] ) && $this->options['private_mode_show_login_form'] == '0' ? : 'checked="checked"'; // defaults to checked
		$input_html = sprintf('<input type="checkbox" id="sd_options_private_mode_show_login_form" name="sd_options[private_mode_show_login_form]" value="1" %s />', $checked);
		$tmpl =
			'<label for="sd_options_private_mode_show_login_form">' .
				'<input type="hidden" name="sd_options[private_mode_show_login_form]" value="0" />' .
				$input_html .
				'Show a login form on the page' .
            '</label>';

        printf(
			$tmpl,
            isset( $this->options['private_mode_show_login_form'] ) ? esc_attr( $this->options['private_mode_show_login_form']) : ''
        );
	}

	public function enable_placeholder_images_callback()
	{
		$radios_html = '';
		$tmpl = '<label for="sd_options_enable_placeholder_images_%s" style="padding: 4px 0; display: block;">
					<input type="radio" name="sd_options[enable_placeholder_images]" id="sd_options_enable_placeholder_images_%s" value="%s" %s>
				  %s
				</label>';

		// get current value; default to off
		$current_option = isset( $this->options['enable_placeholder_images'] )
						  ? $this->options['enable_placeholder_images']
						  : 'off';

		// add 'off' option (No placeholder images)
		$checked =  'off' == $current_option ? 'checked="checked"' : '';
		$radios_html .= sprintf($tmpl, 'off', 'off', 'off', $checked, __('Do not use placeholder images', 'staff-directory-pro') );

		// add 'default' option (use default placeholder images)
		$checked =  'default' == $current_option ? 'checked="checked"' : '';
		$radios_html .= sprintf($tmpl, 'default', 'default', 'default', $checked, __('Use the default placeholder image', 'staff-directory-pro') );

		// add 'custom' option (use specified placeholder images)
		$checked =  'custom' == $current_option ? 'checked="checked"' : '';
		$radios_html .= sprintf($tmpl, 'custom', 'custom', 'custom', $checked, __('Use your own custom placeholder image (specify below)', 'staff-directory-pro') );

        print $radios_html;
	}

	public function placeholder_image_path_callback()
    {
        printf(
            '<input type="text" id="placeholder_image_path_callback" name="sd_options[placeholder_image_path]" value="%s" style="width:450px" /><br><p class="description">%s</p>',
            isset( $this->options['placeholder_image_path'] ) ? esc_attr( $this->options['placeholder_image_path']) : '',
			__('Only required if the custom placeholder image option is chosen above.', 'staff-directory-pro')
        );
    }

    public function print_registration_section_info()
    {
		echo '<p>Fill out the fields below, if you have purchased the pro version of the plugin, to activate additional features such as the Table or Grid layouts.</p>';
    }

    public function api_key_callback()
    {
        printf(
            '<input type="text" id="api_key" name="sd_options[api_key]" value="%s" style="width:450px" />',
            isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
        );
    }
    public function registration_email_callback()
    {
        printf(
            '<input type="text" id="registration_email" name="sd_options[registration_email]" value="%s" style="width:450px" />',
            isset( $this->options['registration_email'] ) ? esc_attr( $this->options['registration_email']) : ''
        );
    }
    public function registration_url_callback()
    {
        printf(
            '<input type="text" id="registration_url" name="sd_options[registration_url]" value="%s" style="width:450px" />',
            isset( $this->options['registration_url'] ) ? esc_attr( $this->options['registration_url']) : ''
        );
    }

	function output_hidden_registration_fields()
	{
		$fields = array('api_key', 'registration_url', 'registration_email');
		foreach($fields as $field) {
			$val = isset( $this->options[$field] ) ? esc_attr( $this->options[$field]) : '';
			printf(
				'<input type="hidden" name="sd_options[' . $field . ']" value="%s" />',
				$val
			);
		}
	}

	function settings_page_top($title = "Company Directory Settings")
	{
		if ( isset($_GET['settings-updated']) ) {
			$settings_updated = sanitize_text_field($_GET['settings-updated']);
			if ( 'true' == $settings_updated ) {
				$this->messages[] = "Company Directory Settings Updated.";
			}
		}

		global $pagenow;
		?>
		<div class="wrap staff_directory_admin_wrap is_pro">
		<h2><?php echo wp_kses($title, 'strip'); ?></h2>
		<?php
			if( !empty($this->messages) ){
				foreach($this->messages as $message){
					echo '<div id="messages" class="gp_updated fade">';
					echo '<p>' . $message . '</p>';
					echo '</div>';
				}

				$this->messages = array();
			}
	}

	function settings_page_bottom()
	{
	}

	/*
	 * Output the upgrade page
	 */
	function render_upgrade_page()
	{
		//setup coupon box
		$upgrade_page_settings = array(
			'plugin_name' 		=> 'Company Directory Pro',
			'pitch' 			=> "When you upgrade, you'll instantly unlock advanced features including Advanced Search, Grid and Table Views, Import & Export, and more!",
			'learn_more_url' 	=> 'https://goldplugins.com/our-plugins/company-directory-pro/?utm_source=cpn_box&utm_campaign=upgrade&utm_banner=learn_more',
			'upgrade_url' 		=> 'https://goldplugins.com/our-plugins/company-directory-pro/upgrade-to-company-directory-pro/?utm_source=plugin_menu&utm_campaign=upgrade',
			'upgrade_url_promo' => 'https://goldplugins.com/purchase/company-directory-pro/single?promo=newsub10',
			'text_domain' => 'staff-directory',
			'testimonial' => array(
				'title' => 'Good, responsive support',
				'body' => 'I highly recommend this. The plug-in makers offer good, responsive support.',
				'name' => 'Carlton Smith<br>Flagstone Search Marketing',
			)
		);
		$img_base_url = plugins_url('../assets/img/upgrade/', __FILE__);
		?>
		<div class="company_directory_admin_wrap">
			<div class="gp_upgrade">
				<h1 class="gp_upgrade_header">Upgrade To Company Directory Pro</h1>
				<div class="gp_upgrade_body">

					<div class="header_wrapper">
						<div class="gp_slideshow">
							<ul>
								<li class="slide"><img src="<?php echo esc_url($img_base_url) . 'staff-grid.png'; ?>" alt="Screenshot of Staff Grid Widget" /><div class="caption">Staff Grid Widget</div></li>
								<li class="slide"><img src="<?php echo esc_url($img_base_url) . 'staff-table.png'; ?>" alt="Screenshot of Staff Table Widget" /><div class="caption">Staff Table Widget</div></li>
								<li class="slide"><img src="<?php echo esc_url($img_base_url) . 'import-export.png'; ?>" alt="Screenshot of Import &amp; Export Wizard" /><div class="caption">Import &amp; Export - Supports 200+ File Types!</div></li>
								<li class="slide"><img src="<?php echo esc_url($img_base_url) . 'advanced-search.png'; ?>" alt="Screenshot of Advanced Search Widget" /><div class="caption">Advanced Search Widget</div></li>
							</ul>
							<a href="#" class="control_next">></a>
							<a href="#" class="control_prev"><</a>
						</div>

						<script type="text/javascript">
							jQuery(function () {
								if (typeof(gold_plugins_init_upgrade_slideshow) == 'function') {
									gold_plugins_init_upgrade_slideshow();
								}
							});
						</script>
						<div class="customer_testimonial">
								<div class="stars">
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
									<span class="dashicons dashicons-star-filled"></span>
								</div>
								<p class="customer_testimonial_title"><strong><?php echo wp_kses($upgrade_page_settings['testimonial']['title'], 'post'); ?></strong></p>
								“<?php echo wp_kses($upgrade_page_settings['testimonial']['body'], 'post'); ?>”
								<p class="author">— <?php echo wp_kses($upgrade_page_settings['testimonial']['name'], 'strip'); ?></p>
						</div>
					</div>
					<div style="clear:both;"></div>
						<p class="upgrade_intro">Company Directory Pro is the professional edition of Company Directory, built from the ground-up to accomodate a larger staff. Its a great choice for any organization with more than 20 people, but easily handles thousands.</p>					<div class="upgrade_left_col">
						<div class="upgrade_left_col_inner">
							<h3>Company Directory Pro Adds Powerful New Features, Including:</h3>
							<ul>
								<li>Import from any Excel-compatible file (over 200 formats supported)</li>
								<li>Import from LDAP and Active Directory Servers</li>
								<li>Export your Staff Members to CSV files that work with any system</li>
								<li>Scheduled Import Jobs - Automatically import new Staff Members on a recurring basis</li>
								<li>Custom Fields - Customize the fields that are available for each Staff Member</li>
								<li>The Staff Table Widget &amp; Shortcode</li>
								<li>The Staff Grid Widget &amp; Shortcode</li>
								<li>Advanced Search - Search by name, department, title, or any other field</li>
								<li>Outstanding support from our developers</li>
								<li>A full year of technical support & automatic updates</li>
							</ul>

							<p class="all_features_link">And many more! <a href="https://goldplugins.com/downloads/company-directory-pro/?utm_source=company_d_upgrade_page_plugin&amp;utm_campaign=see_all_features">Click here for a full list of features included in Company Directory Pro</a>.</p>
							<p class="upgrade_button"><a href="https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=company_d_free_plugin&utm_campaign=upgrade_page_button">Learn More</a></p>
						</div>
					</div>
					<div class="bottom_cols">
						<div class="how_to_upgrade">
							<h4>How To Upgrade:</h4>
							<ol>
								<li><a href="https://goldplugins.com/special-offers/upgrade-to-company-directory-pro/?utm_source=company_d_free_plugin&utm_campaign=how_to_upgrade_steps">Purchase an API Key from GoldPlugins.com</a></li>
								<li>Install and Activate the Company Directory Pro plugin.</li>
								<li>Go to Company Directory Settings &raquo; License Options menu, enter your API key, and click Activate.</li>
							</ol>
							<p class="upgrade_more">That's all! Upgrading takes just a few moments, and won't affect your data.</p>
						</div>
						<div class="questions">	<h4>Have Questions?</h4>
							<p class="questions_text">We can help. <a href="https://goldplugins.com/contact/">Click here to Contact Us</a>.</p>
							<p class="all_plans_include_support">All plans include a full year of technical support.</p>
						</div>
					</div>
				</div>

				<div id="signup_wrapper" class="upgrade_sidebar">
					<div id="mc_embed_signup">
						<div class="save_now">
							<h3>Save 10% Now!</h3>
							<p class="pitch">Subscribe to our newsletter now, and we’ll send you a coupon for 10% off your upgrade to the Pro version.</p>
						</div>
						<form action="https://goldplugins.com/atm/atm.php?u=403e206455845b3b4bd0c08dc&amp;id=a70177def0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate="">
							<div class="fields_wrapper">
								<label for="mce-NAME">Your Name (optional)</label>
								<input value="golden" name="NAME" class="name" id="mce-NAME" placeholder="Your Name" type="text">
								<label for="mce-EMAIL">Your Email</label>
								<input value="services@illuminatikarate.com" name="EMAIL" class="email" id="mce-EMAIL" placeholder="email address" required="" type="email">
								<!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								<div style="position: absolute; left: -5000px;"><input name="b_403e206455845b3b4bd0c08dc_6ad78db648" tabindex="-1" value="" type="text"></div>
							</div>
							<div class="clear"><input value="Send My Coupon" name="subscribe" id="mc-embedded-subscribe" class="whiteButton" type="submit"></div>
							<p class="secure"><img src="/wp-content/plugins/staff-directory-pro/assets/img/lock.png" alt="Lock" width="16px" height="16px">We respect your privacy.</p>

							<input id="mc-upgrade-plugin-name" name="mc-upgrade-plugin-name" value="<?php echo htmlentities($upgrade_page_settings['plugin_name']); ?>" type="hidden">
							<input id="mc-upgrade-link-per" name="mc-upgrade-link-per" value="<?php echo esc_url($upgrade_page_settings['upgrade_url_promo']); ?>" type="hidden">
							<input id="mc-upgrade-link-biz" name="mc-upgrade-link-biz" value="<?php echo esc_url($upgrade_page_settings['upgrade_url_promo']); ?>" type="hidden">
							<input id="mc-upgrade-link-dev" name="mc-upgrade-link-dev" value="<?php echo esc_url($upgrade_page_settings['upgrade_url_promo']); ?>" type="hidden">
							<input id="gold_plugins_already_subscribed" name="gold_plugins_already_subscribed" value="0" type="hidden">
						</form>
					</div>

				</div>
			</div>
		</div>
		<script type="text/javascript">
		jQuery(function () {
			if (typeof(cd_gold_plugins_init_coupon_box) == 'function') {
				cd_gold_plugins_init_coupon_box();
			}
		});
		</script>
		<?php
	}

	function register_admin_scripts()
	{

		wp_register_style( 'staff_directory_admin_stylesheet',
			plugins_url('../assets/css/admin_style.css', __FILE__)
		);

		wp_register_script(
			'company-directory-admin',
			plugins_url('../assets/js/staff-directory-admin.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);

		wp_register_script(
			'gp-admin_v2',
			plugins_url('../assets/js/gp-admin_v2.js', __FILE__),
			array( 'jquery' ),
			false,
			true
		);
	}

	function is_our_admin_page()
	{
		$screen = get_current_screen();
		if  ( !empty($screen->post_type) && 'staff-member' == $screen->post_type ) {
			return true;
		}

		if  ( !empty($screen->base) && 'toplevel_page_staff_dir-settings' == $screen->base ) {
			return true;
		}

		if  ( !empty($screen->base) && false !== strpos($screen->base, 'company-directory-settings') ) {
			return true;
		}

		return false;
	}

	function enqueue_admin_scripts($hook = '')
	{
		$this->is_our_admin_page();
		// only enqueue on our settings pages, the widgets screen, and the customizer
		$screen = get_current_screen();
		$is_customizer = ( function_exists('is_customize_preview') && is_customize_preview() );
		if ( (strpos($hook, 'company-directory') === false)
			  && (strpos($hook, 'staff_dir') === false)
			  && $screen->id !== "widgets"
			  && !$is_customizer
			  && !$this->is_our_admin_page()
			) {
				return;
		}

		wp_enqueue_style( 'staff_directory_admin_stylesheet' );
		wp_enqueue_script( 'company-directory-admin' );
		wp_enqueue_script( 'gp-admin_v2' );
	}

	function add_extra_classes_to_admin_menu()
	{
		global $menu;

		if ( empty($menu) || !is_array($menu) ) {
			return;
		}

		foreach( $menu as $key => $value ) {
			$extra_classes = 'company_directory_admin_menu';
			$extra_classes .= $this->root->is_pro()
							  ? ' company_directory_pro_admin_menu'
							  : ' company_directory_free_admin_menu';
			if( 'Company Directory' == $value[0] ) {
				$menu[$key][4] .= ' ' . $extra_classes;
			}
		}
	}

	/**
	 * Ajax handler for dismissing notices.
	 *
	 */
	public function ajax_notice_bar_dismiss() {

		// Run a security check.
		check_ajax_referer( 'company_directory_dismiss_notice' );

		$current_user = wp_get_current_user();
		$dismissed    = get_user_meta( $current_user->ID, 'company_directory_dismissed', true );

		if ( empty( $dismissed ) ) {
			$dismissed = array();
		}

		$dismissed['free-notice-bar'] = time();
		update_user_meta( $current_user->ID, 'company_directory_dismissed', $dismissed );
		wp_send_json_success();
	}

	function should_show_admin_bar()
	{
		if ( $this->root->is_pro() ) {
			return false;
		}

		if ( !$this->is_our_admin_page() ) {
			return false;
		}

		$current_user = wp_get_current_user();
		$dismissed    = get_user_meta( $current_user->ID, 'company_directory_dismissed', true );

		if ( !empty( $dismissed['free-notice-bar'] ) ) {
			return false;
		}

		return true;
	}

	function admin_header_body_class( $classes )
	{
		if ( $this->should_show_admin_bar() ) {
			$classes .= 'company_directory_notice_bar_show';
		}
		return $classes;
	}


	function company_directory_admin_header()
	{
		if ( !$this->should_show_admin_bar() ) {
			return false;
		}
?>
		<div id="company_directory_notice_bar_spacer"></div>
		<div id="company_directory_notice_bar">
			<span class="company_directory_notice_bar_message"><?php _e("You're using the free version of"); ?> Company Directory. <?php _e('To unlock more features consider'); ?> <a href="<?php echo esc_url($this->get_upgrade_url('reminders', 'adminbar')); ?>" target="_blank" rel="noopener noreferrer"><?php _e('upgrading to Pro'); ?></a>.</span>
			<button type="button" class="dismiss" title="Dismiss this message." data-nonce="<?php echo wp_create_nonce('company_directory_dismiss_notice');?>"></button>
		</div>
<?php
	}

	function array_put_to_position(&$array, $object, $position, $name = null)
	{
			$count = 0;
			$return = array();
			foreach ($array as $k => $v)
			{
					// insert new object
					if ($count == $position)
					{
							if (!$name) $name = $count;
							$return[$name] = $object;
							$inserted = true;
					}
					// insert old object
					$return[$k] = $v;
					$count++;
			}
			if (!$name) $name = $count;
			if (!$inserted) $return[$name];
			$array = $return;
			return $array;
	}

	function array_put_to_position_numeric(&$array, $object, $position)
	{
			$count = 0;
			$return = array();
			foreach ($array as $k => $v)
			{
					// insert new object
					if ($count == $position)
					{
						$return[] = $object;
						$inserted = true;
						$count++;
					}
					// insert old object
					$return[] = $v;
					$count++;
			}
			$array = $return;
			return $array;
	}

	function rename_admin_menu($old_label, $new_label)
	{
		global $menu;
		end ($menu);
		while (prev($menu)){
			$value = $menu[key($menu)][0];
			if( strcmp($value, $old_label ) == 0 ) {
				// NOTE: $menu[key($menu)] is an array. str_replace will replace
				// inside every value in the array and then return a new array
				$menu[key($menu)] = str_replace($old_label, $new_label, $menu[key($menu)]);
				break;
			}
		}
	}

	function rename_admin_submenu($old_label, $new_label)
	{
		global $submenu;
		$top_level_slug = 'edit.php?post_type=staff-member';
		$found_index = $this->find_submenu_pos_by_label($old_label);
		if ( $found_index >= 0 ) {
			$submenu[$top_level_slug][$found_index][0] = $new_label;
			$submenu[$top_level_slug][$found_index][3] = $new_label;
		}
	}

	function find_submenu_pos_by_label($label)
	{
		global $submenu;
		$top_level_slug = 'edit.php?post_type=staff-member';
		$found_index = -1;
		foreach( $submenu[$top_level_slug] as $index => $sm ) {
			if ( $label == $sm[0] ) {
				return $index;
			}
		}
		return -1;
	}
}