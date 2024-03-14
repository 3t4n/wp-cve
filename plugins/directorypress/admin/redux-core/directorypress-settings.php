<?php
    /**
     * ReduxFramework Barebones Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "directorypress_admin_settings";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $plugin_theme = 'DirectoryPress'; // For use with some settings. Not necessary.
	
	$page_parent = 'directorypress-admin-panel';
    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => $plugin_theme,
        // Name that appears at the top of your panel
        'display_version'      => DIRECTORYPRESS_VERSION,
        // Version that appears at the top of your panel
        'menu_type'            => 'submenu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'Settings', 'redux-framework-demo' ),
        'page_title'           => __( 'Settings', 'redux-framework-demo' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => true,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-portfolio',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => '',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => false,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => false,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => $page_parent,
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => '',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => 'directorypress_settings',
        // Page slug used to denote the panel
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => true,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

        'use_cdn'              => true,
        // If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

        //'compiler'             => true,

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'light',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click',
                ),
            ),
        )
    );
    Redux::setArgs( $opt_name, $args );
	
	if(!function_exists('removeDemoModeLink2')){
		function removeDemoModeLink2() { // Be sure to rename this function to something more unique
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
			}
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
			}
		}
	}
add_action('init', 'removeDemoModeLink2');

/** remove redux menu under the tools **/
add_action( 'admin_menu', 'remove_redux_menu2',12 );
if(!function_exists('remove_redux_menu2')){
	
	function remove_redux_menu2() {
		remove_submenu_page('tools.php','redux-about');
	}
}


    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'redux-help-tab-1',
            'title'   => __( 'Theme Information 1', 'DIRECTORYPRESS' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'DIRECTORYPRESS' )
        ),
        array(
            'id'      => 'redux-help-tab-2',
            'title'   => __( 'Theme Information 2', 'DIRECTORYPRESS' ),
            'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'DIRECTORYPRESS' )
        )
    );
    Redux::set_help_tab( $opt_name, $tabs );

	// Set the help sidebar.
	$content = '<p>' . esc_html__( 'This is the sidebar content, HTML is allowed.', 'DIRECTORYPRESS' ) . '</p>';
	Redux::set_help_sidebar( $opt_name, $content );
	

    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    /*

        As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


     */

    // -> START Basic Fields
	global $directorypress_object, $directorypress_social_services, $sitepress, $DIRECTORYPRESS_ADIMN_SETTINGS;
	
	$ordering_items = directorypress_sorting_options();
	
	$listings_tabs = array(
		array('value' => 'addresses-tab', 'label' => __('Addresses tab', 'DIRECTORYPRESS')),
		array('value' => 'comments-tab', 'label' => __('Comments tab', 'DIRECTORYPRESS')),
		array('value' => 'videos-tab', 'label' => __('Videos tab', 'DIRECTORYPRESS'))
	);
	
	if($directorypress_object === null){
		foreach ($directorypress_object->fields->fields_groups_array AS $fields_group){
			if ($fields_group->on_tab){
				$listings_tabs[] = array('value' => 'field-group-tab-'.$fields_group->id, 'label' => $fields_group->name);
			}
		}
	}
	$new_listing_tabs = array();
	foreach($listings_tabs as $listItem) {
		$new_listing_tabs[$listItem['value']] = $listItem['label'];
	}
	
	$pages = get_pages();
	$all_pages[] = '0' .'=>'. __('- Select page -', 'DIRECTORYPRESS');
	foreach ($pages AS $page){
		$all_pages[] = $page->ID .'=>'. $page->post_title;	
	}
	
	$directorypress_social_services = array(
		'facebook' => 'Facebook',
		'twitter' => 'Twitter',
		'google' => 'Google+',
		'linkedin' => 'LinkedIn',
		'digg' => 'Digg',
		'reddit' => 'Reddit',
		'pinterest' => 'Pinterest',
		'tumblr' => 'Tumblr',
		'stumbleupon' => 'StumbleUpon',
		'email' => 'Email'
	);
		//$map_stylesfinal = array_merge($map_styles,$map_styles2);
	
    Redux::setSection( $opt_name, array(
        'title' => __( 'General', 'DIRECTORYPRESS' ),
        'id'    => 'general_section',
        'icon'  => 'fas fa-cog',
    ) );
	Redux::setSection( $opt_name, array(
        'title' => __( 'User Verification', 'DIRECTORYPRESS' ),
        'desc'  => '',
        'id' => 'directorypress_user_verification_section',
		'subsection' => true,
        'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_is_email_verification_required',
				'title' => __('Email Verification', 'DIRECTORYPRESS'),
				'desc' => __('Email verification required for frontend listing', 'DIRECTORYPRESS'),
				"default" => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_is_phone_verification_required',
				'title' => __('Phone Verification', 'DIRECTORYPRESS'),
				'desc' => __('Phone verification required for frontend listing, ( make sure DirectoryPress Twilio addon plugin is installed)', 'DIRECTORYPRESS'),
				"default" => false,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'title' => __( 'Skin', 'DIRECTORYPRESS' ),
        'desc'  => '',
        'id' => 'directorypress_skin_section',
		'subsection' => true,
        'fields' => array(
			array(
				'id' => 'directorypress_primary_color',
				'type' => 'color',
				'title' => esc_html__('Primary Color', 'DIRECTORYPRESS'),
				'default' => '',
				'validate' => 'color',
			),
			array(
				'id' => 'directorypress_secondary_color',
				'type' => 'color',
				'title' => esc_html__('Secondary  Color', 'DIRECTORYPRESS'),
				'default' => '',
				'validate' => 'color',
			)
		),
	) );
	
	do_action('directorypress_after_general_settings', new Redux, $opt_name);

	Redux::setSection( $opt_name, array(
        'title' => __( 'Listing Settings', 'DIRECTORYPRESS' ),
        'id'    => 'listing_settings_section',
        'icon'  => 'fas fa-ad'
    ) );
	Redux::setSection( $opt_name, array(
      'id' => 'listings',
		'title' => __('Grid & List View', 'DIRECTORYPRESS'),   
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'select',
				'id' => 'directorypress_listing_post_style',
				'title' => __('Grid Style', 'DIRECTORYPRESS'),
				'options' => apply_filters("directorypress_listing_grid_styles" , "directorypress_listing_grid_styles_fuction"),
				'default' => 'default',
			),
			apply_filters('directorypress_after_listing_post_style_settings', 'directorypress_after_listing_post_style_function'),
			array(
				'type' => 'select',
				'id' => 'directorypress_listing_listview_post_style',
				'title' => __('List Style', 'DIRECTORYPRESS'),
				'options' => apply_filters("directorypress_listing_list_styles" , "directorypress_listing_list_styles_fuction"),
				'default' => '1',
			),
			array(
				'type' => 'select',
				'id' => 'view_switther_panel_style',
				'title' => __('View Switcher and Sorting Panel Style', 'DIRECTORYPRESS'),
				'options' => apply_filters("directorypress_listing_sorting_style_option" , "directorypress_listing_sorting_styles"),
				'default' => '1',
			),
			array(
				'type' => 'switch',
				'title' => __('Default Pagination', 'DIRECTORYPRESS'),
				'id' => 'directorypress_show_more_button',
				'desc' => __('Display "Show More Listings" button instead of default paginator', 'DIRECTORYPRESS'),
				"default" => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_ratings_addon',
				'title' => __('Ratings', 'DIRECTORYPRESS'),
				'desc' => __('Turn on Ratting and Review', 'DIRECTORYPRESS'),
				"default" => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_grid_masonry_display',
				'title' => __('Turn on Masonry layout for grid styles', 'DIRECTORYPRESS'),
				'desc' => '',
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_listing_responsive_grid',
				'title' => __('Responsive listing grid 2 column', 'DIRECTORYPRESS'),
				'desc' => '',
				'default' => true,
			),
			array(
				'type' => 'select',
				'id' => 'cat_icon_type_on_listing',
				'title' => __('Categories Icon Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('Font Icon', 'DIRECTORYPRESS'),
					'2' => __('Image Icon', 'DIRECTORYPRESS'),
				),
				'default' => 2,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_grid_padding',
				'title' => __('Padding Between grid items', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 50,
				'default' => 15,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_grid_margin_bottom',
				'title' => __('Grid items Margin Bottom', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 50,
				'default' => 30,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_listings_on_index',
				'title' => __('Show listings on Main Directory Page', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_listings_number_index',
				'title' => __('Number Of Listings On Main Directory Page', 'DIRECTORYPRESS'),
				'default' => 6,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_listings_number_excerpt',
				'title' => __('Number Of Listings On Archive Pages (categories, locations, tags, search results)', 'DIRECTORYPRESS'),
				'default' => 6,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_listing_nofollow_link',
				'title' => __('Turn On nofollow attribute in listing permalinks', 'DIRECTORYPRESS'),
				'desc' => '',
				'default' => true,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'social_sharing_section',
		'subsection' => true,
		'title' => __('Social Sharing', 'DIRECTORYPRESS'),
		'fields' => array(
			array(
				'type' => 'sorter',
				'id' => 'directorypress_share_buttons',
				'title' => __('Enable and order Share buttons', 'DIRECTORYPRESS'),
				'options' => array(
					'enabled' => array(
						'facebook' => __('Facebook', 'DIRECTORYPRESS'),
						'twitter' => __('Twitter', 'DIRECTORYPRESS'),
						'linkedin' => __('LinkedIn', 'DIRECTORYPRESS'),
						'digg' => __('Digg', 'DIRECTORYPRESS'),
						'reddit' => __('Reddit', 'DIRECTORYPRESS'),
						'pinterest' => __('Pinterest', 'DIRECTORYPRESS'),
						'tumblr' => __('Tumblr', 'DIRECTORYPRESS'),
						'stumbleupon' => __('StumbleUpon', 'DIRECTORYPRESS'),
						'email' => __('Email', 'DIRECTORYPRESS'),
						'whatsapp' => __('Whatsapp', 'DIRECTORYPRESS'),
						'telegram' => __('Telegram', 'DIRECTORYPRESS')
					),
					'disabled' => array(
						'vk' => __('VK', 'DIRECTORYPRESS'),
					)
				)
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'breadcrumbs',
		'subsection' => true,
		'title' => __('Breadcrumbs', 'DIRECTORYPRESS'),
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_breadcrumbs',
				'title' => __('Enable breadcrumbs', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_hide_home_link_breadcrumb',
				'title' => __('Hide home link in breadcrumbs', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_breadcrumbs_mode',
				'title' => __('Breadcrumbs mode on listing single page', 'DIRECTORYPRESS'),
				'default' => 'title',
				'options' => array(
					'title' => __('%listing title%', 'DIRECTORYPRESS'),	
					'category' => __('%category% » %listing title%', 'DIRECTORYPRESS'),	
					'location' => __('%location% » %listing title%', 'DIRECTORYPRESS'),	
				),
			),
		),
	) );
	
	Redux::setSection( $opt_name, array(
        'id' => 'author_page',
		'title' => __('Author Page', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'slider',
				'id' => 'authorpage_ads_limit',
				'title' => __('number of ads per page', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 20,
				'default' => 4,
			),
			array(
				'type' => 'slider',
				'id' => 'authorpage_grid_col',
				'title' => __('Number of Grid Columns', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 4,
				'default' => 2,
			),
			array(
				'type' => 'select',
				'id' => 'authorpage_view_type',
				'title' => __('Listing view Grid/List', 'DIRECTORYPRESS'),
				'options' => array(
					'list' => __('List', 'DIRECTORYPRESS'),
					'grid' => __('Grid ', 'DIRECTORYPRESS'),
				),
				'default' => 'list',
			),
			array(
				'type' => 'select',
				'id' => 'author_page_listing_order',
				'title' => __('Listing Order', 'DIRECTORYPRESS'),
				'options' => array(
					'ASC' => __('Ascending', 'DIRECTORYPRESS'),
					'DESC' => __('Descending', 'DIRECTORYPRESS'),
				),
				'default' => 'DESC',
			),
			array(
				'type' => 'switch',
				'id' => 'author_contact_hide_from_anonymous',
				'title' => __('Hide Author Conract From Anonymous user', 'DIRECTORYPRESS'),
				'default' => false,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'related_listings',
		'title' => __('Related Listings', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'single_listing_related',
				'title' => __('Enable Related Listings', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_related_limit',
				'title' => __('Number of Related Listings', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 20,
				'default' => 2,
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_related_grid_col',
				'title' => __('Related Listings Grid Columns', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 4,
				'default' => 2,
			),
			array(
				'type' => 'select',
				'id' => 'single_listing_related_view_type',
				'title' => __('Related Listings View', 'DIRECTORYPRESS'),
				'options' => array(
					'list' => __('List', 'DIRECTORYPRESS'),
					'grid' => __('Grid ', 'DIRECTORYPRESS'),
				),
				'default' => 'grid',
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'logos',
		'title' => __('Thumbnails', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_nologo',
				'title' => __('Enable default logo image', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'media',
				'id' => 'directorypress_nologo_url',
				'url' => false,
				'title' => __('Default logo image', 'DIRECTORYPRESS'),
				'desc' => __('This image will appear when listing owner did not upload own logo.', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'select',
				'id' => 'listing_image_width_height',
				'title' => __('Listing Thumbnail Width Default/Custom', 'DIRECTORYPRESS'),
				'desc' => __('Below custom width and height will work only if custom option is selected, Otherwise post pre-defined values will be set to the listing thumbnail'),
				'options' => array(
					'1' => __('Default', 'DIRECTORYPRESS'),
					'2' => __('Custom ', 'DIRECTORYPRESS'),
					'3' => __('Auto Responsive', 'DIRECTORYPRESS'),
				),
				'default' => 1,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_logo_width',
				'min' => 100,
				'max' => 800,
				'default' => 270,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_logo_height',
				'title' => __('Images height', 'DIRECTORYPRESS'),
				'min' => 100,
				'max' => 800,
				'default' => 220,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_logo_width_listview',
				'title' => __('Images width on List View', 'DIRECTORYPRESS'),
				'min' => 100,
				'max' => 800,
				'default' => 250,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_logo_height_listview',
				'title' => __('Images height on List View', 'DIRECTORYPRESS'),
				'min' => 100,
				'max' => 800,
				'default' => 224,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
		'id' => 'single_listing_page',
		'title' => __('Details Page', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'select',
				'id' => 'directorypress_single_listing_style',
				'title' => __('Single Listing Page Style', 'DIRECTORYPRESS'),
				'options' => apply_filters("directorypress_listing_single_style_option", "directorypress_listing_single_styles"),
				'default' => 'default',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_listing_contact',
				'title' => __('Enable contact option on front-end', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'select',
				'id' => 'message_system',
				'title' => __('Single Listing Contact form Type', 'DIRECTORYPRESS'),
				'required' => array('directorypress_listing_contact', '!=', false),
				'options' => array(
					'instant_messages' => __('Instant Messaging System', 'DIRECTORYPRESS'),
					'email_messages' => __('Email contact Form', 'DIRECTORYPRESS'),
				),
				'default' => 'instant_messages',
			),
			array(
				'type' => 'text',
				'id' => directorypress_wpml_supported_option_id('directorypress_listing_contact_form_7'),
				'title' => __('Contact Form 7 shortcode', 'DIRECTORYPRESS'),
				'desc' => __('This will work only when Contact Form 7 plugin enabled, otherwise standard contact form will be displayed.', 'DIRECTORYPRESS') .  directorypress_wpml_supported_settings_description(),
				'default' => '',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_custom_contact_email',
				'required' => array('directorypress_listing_contact', '!=', false),
				'title' => __('Allow custom contact emails', 'DIRECTORYPRESS'),
				'desc' => __('When enabled users may set up custom contact emails, otherwise messages will be sent directly to authors emails', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_listing_bidding',
				'title' => __('Enable offer option on single', 'DIRECTORYPRESS'),
				'desc' => __('Turn Offers form on/Off at single listing page', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'single_listing_tab',
				'title' => __('Turn on Tabs at single listing page', 'DIRECTORYPRESS'),
				'desc' => __('Turn Tabs on/Off at single listing page', 'DIRECTORYPRESS'),
				'default' => 1,
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_listings_comments_mode',
				'title' => __('Turn On/Off Reviews', 'DIRECTORYPRESS'),
				'default' => 'disabled',
				'options' => array(
					'enabled' => __('Always enabled', 'DIRECTORYPRESS'),
					'disabled' => __('Always disabled', 'DIRECTORYPRESS'),
					'wp_settings' => __('As configured in WP settings', 'DIRECTORYPRESS'),	
				),
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_listings_comments_position',
				'title' => __('Reviews Position', 'DIRECTORYPRESS'),
				'default' => 'intab',
				'options' => array(
					'intab' => __('In Tabs', 'DIRECTORYPRESS'),
					'notab' => __('Out Side of Tabs', 'DIRECTORYPRESS'),
				),
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_listings_video_position',
				'title' => __('Listings Video Position', 'DIRECTORYPRESS'),
				'default' => 'intab',
				'options' => array(
					'intab' => __('In Tabs', 'DIRECTORYPRESS'),
					'notab' => __('Out Side of Tabs', 'DIRECTORYPRESS'),
				),
			),
										
			array(
				'type' => 'sorter',
				'id' => 'directorypress-listings-tabs-order', // directorypress-listings-tabs-order converted from directorypress_listings_tabs_order
				'title' => __('Priority of opening of listing tabs', 'DIRECTORYPRESS'),
					'options' => array(
					'enabled' => $new_listing_tabs,
					'disabled' => array(
					'' =>  __("empty option(don't remove)", "DIRECTORYPRESS"),
					)
				),
				'desc' => __('Set up priority of tabs those are opened by default. If any listing does not have any tab - next tab in the order will be opened by default.', 'DIRECTORYPRESS'),
			),
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'listing-buttons',
		'title' => __('Buttons', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_print_button',
				'title' => __('Show print listing button', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_pdf_button',
				'title' => __('Show listing PDF button', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_report_button',
				'title' => __('Show listing Report button', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_favourites_list',
				'title' => __('Enable bookmarks', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'author_profile_view',
				'title' => __('Author profile view link in sidebar widget', 'DIRECTORYPRESS'),
				"default" => true,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'listing-metas',
		'title' => __('Meta Links & Metaboxes', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_single_listing_publish_date',
				'title' => __('Show published date on single listing', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_single_listing_views',
				'title' => __('Show views on single listing', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_single_listing_id',
				'title' => __('Show ID on single listing', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
					'type' => 'switch',
					'id' => 'directorypress_enable_tags',
					'title' => __('Enable listings tags input at the frontend', 'DIRECTORYPRESS'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_enable_business_logo',
					'title' => __('Enable listings Business Logo', 'DIRECTORYPRESS'),
					'desc' => __('Only works with single listing directory layout', 'DIRECTORYPRESS'),
					'default' => false,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_enable_business_cover',
					'title' => __('Enable listings Business Cover', 'DIRECTORYPRESS'),
					'desc' => __('Only works with single listing directory layout', 'DIRECTORYPRESS'),
					'default' => false,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_enable_status_field',
					'title' => __('Enable listings status field at the frontend', 'DIRECTORYPRESS'),
					'default' => true,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_listing_resurva_booking',
					'title' => __('Enable Resurva Booking', 'DIRECTORYPRESS'),
					'default' => false,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_enable_social_links',
					'title' => __('Enable listings Social Links MetaBox frontend', 'DIRECTORYPRESS'),
					'desc' => __('Only works with single listing directory layout', 'DIRECTORYPRESS'),
					'default' => false,
				),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'single_listing_slider',
		'title' => __('Details Page Slider', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_lighbox_gallery',
				'title' => __('Enable lightbox on images gallery', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_100_single_logo_width',
				'title' => __('100% width of images gallery on single listing page', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_single_logo_width',
				'title' => __('Images gallery width on single listing page (in pixels)', 'DIRECTORYPRESS'),
				'desc' => __('This option needed only when 100% width of images gallery is switched off'),
				'min' => 100,
				'max' => 2550,
				'default' => 775,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_single_logo_height',
				'title' => __('Images gallery Height on single listing page (in pixels)', 'DIRECTORYPRESS'),
				'desc' => __('This option needed only when 100% width of images gallery is switched off'),
				'min' => 100,
				'max' => 800,
				'default' => 480,
			),
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'excerpts',
		'title' => __('Description & Excerpt', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'text',
				'id' => 'directorypress_listing_title_font',
				'title' => __('Listing Title font size', 'DIRECTORYPRESS'),
				'default' => 16,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_description',
				'title' => __('Enable description field', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_summary',
				'title' => __('Enable summary field', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_excerpt_length',
				'title' => __('Excerpt max length', 'DIRECTORYPRESS'),
				'desc' => __('Insert the number of letters you want to show in the listings excerpts', 'DIRECTORYPRESS'),
				'default' => 80,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'select',
				'id' => 'directorypress_exert_type',
				'title' => __('Listing Title Limit Type', 'DIRECTORYPRESS'),
				'options' => array(
					'letters' => __('Letters', 'DIRECTORYPRESS'),
					'words' => __('Words', 'DIRECTORYPRESS'),
				),
				'default' => 'letters',
			),
			array(
				'type' => 'text',
				'id' => 'max_title_length',
				'title' => __('Listing Title max length', 'DIRECTORYPRESS'),
				'desc' => __('Insert the number of letters you want to show in the listings excerpts', 'DIRECTORYPRESS'),
				'default' => 20,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_cropped_content_as_excerpt',
				'title' => __('Use cropped content as excerpt', 'DIRECTORYPRESS'),
				'desc' => __('When excerpt field is empty - use cropped main content', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_strip_excerpt',
				'title' => __('Strip HTML from excerpt', 'DIRECTORYPRESS'),
				'desc' => __('Check the box if you want to strip HTML from the excerpt content only', 'DIRECTORYPRESS'),
				'default' => true,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'lising-skin',
		'title' => __('Styling', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'listing_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Title', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => 16,
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'listing_cat_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Category Field Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'listing_price_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Price Field Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'listing_meta_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Meta Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'has_featured_tag_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Featured Tag Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			
			array(
				'id' => 'listing_title_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Listing Title Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'listing_price_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Listing Price Field Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'listing_cat_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Listing Category Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'listing_meta_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Listing Meta Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'has_featured_tag_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Listing Featured Tag Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'listing_title_color',
				'type' => 'link_color',
				'title' => esc_html__('Listing title color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'listing_price_color',
				'type' => 'link_color',
				'title' => esc_html__('Listing price color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'listing_cat_color',
				'type' => 'link_color',
				'title' => esc_html__('Listing Category color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'listing_meta_color',
				'type' => 'link_color',
				'title' => esc_html__('Listing Meta color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'listing_wrapper_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Wrapper background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_wrapper_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Wrapper background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_content_wrapper_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Content Wrapper background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_content_wrapper_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Content Wrapper background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_wrapper_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Wrapper Border color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_wrapper_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Wrapper Border color on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_cat_icon_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Category font icon color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_wrapper_shadow',
				'type' => 'text',
				'title' => esc_html__('Listing Wrapper Shadow', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_wrapper_shadow_hover',
				'type' => 'text',
				'title' => esc_html__('Listing Wrapper Shadow on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_wrapper_border_width',
				'title' => __('Listing Wrapper Border Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'id'             => 'listing_wrapper_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Listing box border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For Pricing Plan box', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'listing_content_wrapper_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Listing content arapper border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For listing content wrapper', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'slider',
				'id' => 'listing_price_tag_width',
				'title' => __('Listing Price tag min Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 200,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_price_tag_height',
				'title' => __('Listing Price tag min Height', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 200,
				'default' => '',
			),
			array(
				'id' => 'listing_price_tag_color',
				'type' => 'link_color',
				'title' => esc_html__('Listing price tag color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'listing_price_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Price tag background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'listing_price_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Listing Price tag background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_price_tag_padding_top_bottom',
				'title' => __('Listing Price tag top + bottom padding', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_price_tag_padding_left_right',
				'title' => __('Listing Price tag left + right padding', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_price_tag_padding_top_bottom',
				'title' => __('Listing Price tag top + bottom padding', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'id'             => 'listing_price_tag_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Listing price tag border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For Listing price tag', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'price_tag_position',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Listing price tag Position', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set position For Listing price tag', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'select',
				'id' => 'listing_has_featured_tag_style',
				'title' => __('Listing has_featured tag Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('style 1', 'DIRECTORYPRESS'),
					'2' => __('style 2', 'DIRECTORYPRESS'),
					'3' => __('style 3', 'DIRECTORYPRESS'),
					'4' => __('style 4', 'DIRECTORYPRESS'),
					'5' => __('style 5', 'DIRECTORYPRESS'),
					'6' => __('style 6', 'DIRECTORYPRESS'),
					'7' => __('style 7', 'DIRECTORYPRESS'),
					'8' => __('style 8', 'DIRECTORYPRESS'),
					'9' => __('style 9', 'DIRECTORYPRESS'),
					'10' => __('style 10', 'DIRECTORYPRESS'),
					'11' => __('style 11', 'DIRECTORYPRESS'),
					'12' => __('style 12', 'DIRECTORYPRESS'),
					'13' => __('style 13 Zoco', 'DIRECTORYPRESS'),
					'14' => __('style 14', 'DIRECTORYPRESS'),
					'15' => __('style 15', 'DIRECTORYPRESS'),
					'15' => __('style 16', 'DIRECTORYPRESS'),
					'17' => __('style 17', 'DIRECTORYPRESS'),
				),
				'default' => '',
			),
			array(
				'id' => 'has_featured_tag_color',
				'type' => 'link_color',
				'title' => esc_html__('Featured Tag text color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'has_featured_tag_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Featured Tag background', 'DIRECTORYPRESS'),
			),
			array(
				'id' => 'has_featured_tag_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Featured Tag background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			
			array(
				'type' => 'slider',
				'id' => 'listing_has_featured_tag_width',
				'title' => __('Listing has_featured tag min Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 200,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_has_featured_tag_height',
				'title' => __('Listing has_featured tag min Height', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 200,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_has_featured_tag_padding_top_bottom',
				'title' => __('Listing has_featured tag top + bottom padding', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'listing_has_featured_tag_padding_left_right',
				'title' => __('Listing has_featured tag left + right padding', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'id'             => 'listing_has_featured_tag_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Listing has_featured tag border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For Listing has_featured tag', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'text',
				'id' => 'listing_has_featured_tag_position_top',
				'title' => __('Listing has_featured tag Position Top', 'DIRECTORYPRESS'),
				'default' => ''
			),
			array(
				'type' => 'text',
				'id' => 'listing_has_featured_tag_position_bottom',
				'title' => __('Listing has_featured tag Position Bottom', 'DIRECTORYPRESS'),
				'default' => ''
			),
			array(
				'type' => 'text',
				'id' => 'listing_has_featured_tag_position_left',
				'title' => __('Listing has_featured tag Position Left', 'DIRECTORYPRESS'),
				'default' => ''
			),
			array(
				'type' => 'text',
				'id' => 'listing_has_featured_tag_position_right',
				'title' => __('Listing has_featured tag Position Right', 'DIRECTORYPRESS'),
				'default' => ''
			),
			
			array(
				'id' => 'loadmore_btn_typo',
				'type' => 'typography',
				'title' => esc_html__('Listing Load More Button Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'loadmore_btn_text_transform',
				'type' => 'button_set',
				'title' => esc_html__('Loadmore Button Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id'       => 'loadmore_button_width',
				'type'     => 'dimensions',
				'units'    => array('px', '%'),
				'height' => false,
				'title'    => esc_html__('button Width', 'DIRECTORYPRESS'),
				'default'  => array(
					//'units' => 'px',
					'width'   => '270', 
					//'height'  => '40'
				),
			),
			array(
				'id'             => 'loadmore_btn_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Loadmore button radius', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'loadmore_btn_padding',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Loadmore button Padding', 'DIRECTORYPRESS'),
				'desc' => __('you can set padding for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'loadmore_btn_border',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Loadmore button Border', 'DIRECTORYPRESS'),
				'desc' => __('you can set border for each corner separately e.g (top, right, bottom, left)', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'loadmore_color',
				'type' => 'nav_color',
				'title' => esc_html__('Loadmore Button colors', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '',
					'hover' => '',
					'bg' => '',
					'bg-hover' => '',
				)
			),
			array(
				'id' => 'loadmore_border_color',
				'type' => 'nav_color',
				'title' => esc_html__('Loadmore Button Border colors', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id'       => 'loadmore_button_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'button box shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id'       => 'loadmore_button_box_shadow_hover',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'button box shadow (hover)', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
		),
	) );
	 Redux::setSection( $opt_name, array(
        'title' => __( 'Permalinks', 'DIRECTORYPRESS' ),
        'id'    => 'listing_permalinks_section',
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'text',
				'id' => directorypress_wpml_supported_option_id('directorypress_directory_title'),
				'title' => __('Listing title', 'DIRECTORYPRESS'),
				'desc' =>  directorypress_wpml_supported_settings_description(),
				'default' => 'Listings',  // adapted for WPML
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_permalinks_structure',
				'title' => __('Listings permalinks structure', 'DIRECTORYPRESS'),
				'desc' => __('<b>/%postname%/</b> works only when directory page is not front page.<br /><b>/%post_id%/%postname%/</b> will not work when the same structure was enabled for native WP posts.', 'DIRECTORYPRESS'),
				'default' => 'postname',
				'options' => array(
					'postname' => __('/%postname%/', 'DIRECTORYPRESS'),	
					'post_id' => __('/%post_id%/%postname%/', 'DIRECTORYPRESS'),	
					'listing_slug' => __('/%listing_slug%/%postname%/', 'DIRECTORYPRESS'),	
					'category_slug' => __('/%listing_slug%/%category%/%postname%/', 'DIRECTORYPRESS'),	
					'location_slug' => __('/%listing_slug%/%location%/%postname%/', 'DIRECTORYPRESS'),	
					'tag_slug' => __('/%listing_slug%/%tag%/%postname%/', 'DIRECTORYPRESS'),	
				),
			),
		),
    ) );
	do_action('directorypress_after_listing_settings', new Redux, $opt_name);
	Redux::setSection( $opt_name, array(
		'id' => 'sorting_panel_settings',
		'title' => __('Sorting Panel Settings', 'DIRECTORYPRESS'),
		'icon'  => 'fas fa-folder'
	));
	Redux::setSection( $opt_name, array(
        'id' => 'sorting_general',
		'title' => __('General', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_views_switcher',
				'title' => __('Enable Sorting Panel', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'id' => 'sorting_panel_background',
				'type' => 'color_rgba',
				'title' => esc_html__('Panel background', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#ffffff',
					'alpha'     => 1,
					'rgba'  => '',
				),
			),
			array(
				'id'             => 'sorting_panel_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Panel padding', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => 0, 
					'padding-right'   => 0, 
					'padding-bottom'  => 0, 
					'padding-left'    => 30,
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'sorting_panel_radius',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Panel Border Radius', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'       => 'sorting_panel_border',
				'type'     => 'border',
				'title' => __('Sorting Panel border', 'DIRECTORYPRESS'),
				'default'  => array(
					'border-color'  => '#eeeeee', 
					'border-style'  => 'solid', 
					'border-top'    => '1', 
					'border-right'  => '1', 
					'border-bottom' => '1', 
					'border-left'   => '1'
				)
			),
			array(
				'id'       => 'sorting_panel_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id' => 'result_count_typo',
				'type' => 'typography',
				'title' => esc_html__('Result count text Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => true,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '16',
					'font-weight' => '',
					'line-height' => '70',
					'color' => '#777777',
				),
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'sorting_order_dropbox',
		'title' => __('Sorting Dropbox', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'id'             => 'sorting_selectbox_wrapper_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Dropbox Wrapper Padding', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '15', 
					'padding-right'   => '15', 
					'padding-bottom'  => '15', 
					'padding-left'    => '15',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'sorting_selectbox_wrapper_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Dropbox Wrapper Background', 'DIRECTORYPRESS'),
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_orderby_links',
				'title' => __('Show order by links block', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_date',
				'title' => __('Allow sorting by date', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_title',
				'title' => __('Allow sorting by title', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_title_az_za',
				'title' => __('Show Tile ordering as A-Z and Z-A', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_distance',
				'title' => __('Allow sorting by distance when search by radius', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'select',
				'id' => 'directorypress_default_orderby',
				'title' => __('Default order by', 'DIRECTORYPRESS'),
				'options' => $ordering_items,
				'default' => 'post_date',
			),
			array(
				'type' => 'select',
				'id' => 'directorypress_default_order',
				'title' => __('Default order direction', 'DIRECTORYPRESS'),
				'options' => array(
					'ASC' => __('Ascending', 'DIRECTORYPRESS'),
					'DESC' => __('Descending', 'DIRECTORYPRESS'),
				),
				'default' => 'DESC',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_exclude_null',
				'title' => __('Exclude listings with empty values from sorted results', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_orderby_has_sticky_has_featured',
				'title' => __('Sticky and featured listings always will be on top', 'DIRECTORYPRESS'),
				'desc' => __('When switched off - sticky and featured listings will be on top only when listings were sorted by date.', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'id'       => 'sorting_selectbox_dimensions',
				'type'     => 'dimensions',
				'units'    => array('px'),
				'title'    => esc_html__('Dimensions (Width/Height)', 'DIRECTORYPRESS'),
				'default'  => array(
					'width'   => '200', 
					'height'  => '40'
				),
			),
			array(
				'id'             => 'sorting_selectbox_border_radius',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Dropbox Border Radius', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'sorting_selectbox_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('dropbox background', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#f5f5f5',
					'alpha'     => 1
				),
			),
			array(
				'id' => 'sorting_selectbox_bg_focus',
				'type' => 'color_rgba',
				'title' => esc_html__('dropbox background (focus)', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#f0f0f0',
					'alpha'     => 1
				),
			),
			array(
				'id'       => 'sorting_selectbox_border',
				'type'     => 'border',
				'title' => __('Sorting dropbox border', 'DIRECTORYPRESS'),
				'color' => false,
				'default'  => array(
					//'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '1', 
					'border-right'  => '1', 
					'border-bottom' => '1', 
					'border-left'   => '1'
				)
			),
			array(
				'id' => 'sorting_selectbox_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('border color', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#dfe1e0',
					'alpha'     => 1
				),
			),
			array(
				'id' => 'sorting_selectbox_border_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('border color (hover)', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#dfe1e0',
					'alpha'     => 1
				),
			),
			array(
				'id'       => 'sorting_selectbox_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id' => 'sorting_selectbox_icon_color',
				'type' => 'nav_color',
				'title' => esc_html__('Dropbox icon color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'bg-active' => false,
				'default' => array(
					'regular' => '#bdbcbc',
					'hover' => '#bdbcbc',
				)
			),
			array(
				'id' => 'sorting_selectbox_icon_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('icon background', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#f5f5f5',
					'alpha'     => 1
				),
			),
			array(
				'id' => 'sorting_selectbox_icon_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('icon background (hover)', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#f5f5f5',
					'alpha'     => 1
				),
			),
			array(
				'id'       => 'sorting_selectbox_icon_border',
				'type'     => 'border',
				'title' => __('Dropbox icon border', 'DIRECTORYPRESS'),
				'color'  => false, 
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '1', 
					'border-right'  => '1', 
					'border-bottom' => '1', 
					'border-left'   => '1'
				)
			),
			array(
				'id' => 'sorting_selectbox_icon_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('icon border color', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#dfe1e0',
					'alpha'     => 1
				),
			),
			array(
				'id' => 'sorting_selectbox_icon_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('icon border color (hover)', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#dfe1e0',
					'alpha'     => 1
				),
			),
			array(
				'id'             => 'sorting_selectbox_icon_border_radius',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Icon Border Radius', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'sorting_switcher_buttons',
		'title' => __('View Switcher', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'id'             => 'sorting_panel_switch_button_wrapper_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Buttons Wrapper Padding', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'sorting_panel_switch_button_wrapper_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Buttons Wrapper Background', 'DIRECTORYPRESS'),
				'default'   => array(
					'color'     => '#f5f5f5',
					'alpha'     => 1
				),
			),
			array(
				'id'       => 'sorting_panel_switcher_button_dimensions',
				'type'     => 'dimensions',
				'units'    => array('px'),
				'title'    => esc_html__('Dimensions (Width/Height)', 'DIRECTORYPRESS'),
				'default'  => array(
					'width'   => '72', 
					'height'  => '72'
				),
			),
			array(
				'id'        => 'sorting_panel_switcher_button_spacing',
				'type'      => 'slider',
				'title'     => esc_html__('Spacing', 'DIRECTORYPRESS'),
				"default"   => 0,
				"min"       => 0,
				"step"      => 1,
				"max"       => 50,
				'display_value' => 'label'
			),
			array(
				'id'       => 'sorting_panel_switcher_button_grid_icon',
				'type'     => 'text',
				//'units'    => array('px'),
				'title'    => esc_html__('Grid Button Icon (add class e.g fas fa-th-large )', 'DIRECTORYPRESS'),
				'default'  => 'pacz-fic3-3x3-grid'
			),
			array(
				'id'       => 'sorting_panel_switcher_button_list_icon',
				'type'     => 'text',
				//'units'    => array('px'),
				'title'    => esc_html__('List Button Icon (add class e.g fas fa-bars )', 'DIRECTORYPRESS'),
				'default'  => 'pacz-fic3-list-2'
			),
			array(
				'id'        => 'sorting_panel_switcher_button_icon_size',
				'type'      => 'slider',
				'title'     => esc_html__('Icon Size', 'DIRECTORYPRESS'),
				"default"   => 16,
				"min"       => 1,
				"step"      => 1,
				"max"       => 100,
				'display_value' => 'label'
			),
			array(
				'id' => 'sorting_panel_switcher_button_color',
				'type' => 'nav_color',
				'title' => esc_html__('Stwitcher button color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'bg-active' => false,
				'default' => array(
					'regular' => '#cdcfd7',
					'hover' => '#f31c28',
				)
			),
			array(
				'id' => 'sorting_panel_switcher_button_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('background color', 'DIRECTORYPRESS'),
			),
			array(
				'id' => 'sorting_panel_switcher_button_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('background color (hover)', 'DIRECTORYPRESS'),
			),
			array(
				'id'       => 'sorting_panel_switcher_button_border',
				'type'     => 'border',
				'title' => __('Stwitcher button border', 'DIRECTORYPRESS'),
				'color' => false,
				'default'  => array(
					//'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '1', 
					'border-right'  => '1', 
					'border-bottom' => '1', 
					'border-left'   => '1'
				)
			),
			array(
				'id' => 'sorting_panel_switcher_button_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('border color', 'DIRECTORYPRESS'),
			),
			array(
				'id' => 'sorting_panel_switcher_button_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('border color (hover)', 'DIRECTORYPRESS'),
			),
			array(
				'id'             => 'sorting_panel_switch_button_radius',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Switcher Button Radius', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'       => 'sorting_panel_switch_button_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'Box Shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
		),
	) );
	Redux::setSection( $opt_name, array(
	'id' => 'category_settings',
	'title' => __('Category Settings', 'DIRECTORYPRESS'),
	'icon'  => 'fas fa-folder'
	));
	Redux::setSection( $opt_name, array(
        'id' => 'categories',
		'title' => __('Categories settings', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'select',
				'id' => 'directorypress_categories_style',
				'title' => __('Categories Default Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('style 1', 'DIRECTORYPRESS'),
					'2' => __('style 2', 'DIRECTORYPRESS'),
					'3' => __('style 3', 'DIRECTORYPRESS'),
					'4' => __('style 4', 'DIRECTORYPRESS'),
					'5' => __('style 5', 'DIRECTORYPRESS'),
					'6' => __('style 6', 'DIRECTORYPRESS'),
					'7' => __('style 7', 'DIRECTORYPRESS'),
					'8' => __('style 8', 'DIRECTORYPRESS'),
					'9' => __('style 9', 'DIRECTORYPRESS'),
					'10' => __('style 10', 'DIRECTORYPRESS'),
				),
				'default' => '3',
			),
			array(
				'type' => 'select',
				'id' => 'cat_icon_type',
				'title' => __('Categories Icon Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('Font Icon', 'DIRECTORYPRESS'),
					'2' => __('Image Icon', 'DIRECTORYPRESS'),
				),
				'default' => 2,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_parent_category__default_icon',
				'title' => __('Show Parent Category Default Icon', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_child_category_icons',
				'title' => __('Show Child Category Icons', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_categories_index',
				'title' => __('Show categories list on index and excerpt pages?', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_categories_depth',
				'title' => __('Categories nesting package', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 2,
				'default' => 1,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_categories_columns',
				'title' => __('Categories columns number', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 4,
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_subcategories_items',
				'title' => __('Show subcategories items number', 'DIRECTORYPRESS'),
				'desc' => __('Leave 0 to show all subcategories', 'DIRECTORYPRESS'),
				'default' => 0,
				'validation' => 'numeric',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_category_count',
				'title' => __('Show category listings count?', 'DIRECTORYPRESS'),
				'default' => false,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'category-skin',
		'title' => __('Category Styling', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'category_typo',
				'type' => 'typography',
				'title' => esc_html__('Category Title Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'childcategory_typo',
				'type' => 'typography',
				'title' => esc_html__('Child Category Title Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'category_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Category Title Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'childcategory_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Child Category Title Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'parent_cat_title_color',
				'type' => 'link_color',
				'title' => esc_html__('Parent category title color', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('Background color will effect category style 6', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => true,
				'bg-hover' => true,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'subcategory_title_color',
				'type' => 'link_color',
				'title' => esc_html__('Child category title color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'cat_bg_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Category Wrapper background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'cat_bg_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Category Wrapper background on Hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'cat_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Category Wrapper Border Color', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('will effect category style 6', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'cat_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Category Wrapper Border Color on hover', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('will effect category style 6', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id'             => 'cat_border_radius',
				'type'           => 'spacing',
				//'output'         => '',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Category box border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For category box', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
		),
	) );
	
	do_action('directorypress_after_categories_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
        'title' => __( 'Page and views', 'DIRECTORYPRESS' ),
        'id'    => 'page_views_settings_section',
        'icon'  => 'fas fa-copy'
    ) );
	Redux::setSection( $opt_name, array(
        'id' => 'excerpt_views',
		'title' => __('Archive pages', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'select',
				'id' => 'archive_page_style',
				'title' => __('Archive page Style', 'DIRECTORYPRESS'),
				'options' => apply_filters("directorypress_archive_page_style_option" , "directorypress_archive_page_styles"),
				'default' => 1,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_content_area_location_margin_top',
				'title' => __('Archive page content area locations margin top', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 200,
				'default' => 70,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_content_area_category_margin_top',
				'title' => __('Archive page content area categories margin top', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 200,
				'default' => 70,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_content_area_listings_margin_top',
				'title' => __('Archive page content area listings margin top', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 200,
				'default' => 70,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_content_area_width',
				'title' => __('Archive page content area width on side layout', 'DIRECTORYPRESS'),
				'min' => 10,
				'max' => 100,
				'default' => 67,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_side_area_width',
				'title' => __('Archive page side area width on side layout', 'DIRECTORYPRESS'),
				'min' => 10,
				'max' => 100,
				'default' => 33,
			),
			array(
				'type' => 'slider',
				'id' => 'archive_side_area_padding',
				'title' => __('Archive page side area padding', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'default' => 15,
			),
			array(
				'type' => 'select',
				'id' => 'archive_side_area_position',
				'title' => __('Archive page side layout', 'DIRECTORYPRESS'),
				'options' => array(
					'right' => __('Right Sidebar', 'DIRECTORYPRESS'),
					'left' => __('Left Sidebar', 'DIRECTORYPRESS'),
				),
				'default' => 'right',
			),
			array(
				'type' => 'select',
				'id' => 'archive_map_position',
				'title' => __('Archive page Map Position', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('Map on Top', 'DIRECTORYPRESS'),
					'2' => __('Map in content area', 'DIRECTORYPRESS'),
				),
				'default' => '1',
			),
			array(
				'type' => 'switch',
				'id' => 'archive_top_map_width',
				'title' => __('Turn on Boxed Map', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_views_switcher_default',
				'title' => __('Listings view by default', 'DIRECTORYPRESS'),
				'desc' => __('Do not forget that selected view will be stored in cookies', 'DIRECTORYPRESS'),
				'default' => 'grid',
				'options' => array(
					'list' => __('List view', 'DIRECTORYPRESS'),
					'grid' => __('Grid view', 'DIRECTORYPRESS'),
				),
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_views_switcher_grid_columns',
				'title' => __('Number of columns for listings Grid View', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 6,
				'default' => 4,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
	'id' => 'misc_settings',
	'subsection' => true,
	'title' => __('Misc', 'DIRECTORYPRESS'),
	'fields' => array(
		array(
			'type' => 'switch',
			'id' => 'directorypress_overwrite_page_title',
			'title' => __('Replace Main Directory Menu With Page Title', 'DIRECTORYPRESS'),
			'default' => false,
		),
		array(
			'type' => 'text',
			'id' => 'directorypress_admin_email',
			'title' => __('Change Admin email', 'DIRECTORYPRESS'),
			'default' => get_option( 'admin_email' ),
		),
		array(
			'type' => 'text',
			'id' => 'max_attchment_size',
			'title' => __('Max attachment file size (Kilobytes)', 'DIRECTORYPRESS'),
			'desc' => __('Setting will effect frontend galley images, business logo, business cover and attachment field', 'DIRECTORYPRESS'),
			'default' => 5120,
		)
	)
	));
	
	do_action('directorypress_after_pageview_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
	'id' => 'locations_settings',
	'title' => __('Locations settings', 'DIRECTORYPRESS'),
	'icon' => 'fas fa-map-marked',
	
	));
	Redux::setSection( $opt_name, array(
        'id' => 'locations',
			'title' => __('General settings', 'DIRECTORYPRESS'),
			'subsection' => true,
			'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_locations_index',
				'title' => __('Show locations list on index and excerpt pages?', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'type' => 'select',
				'id' => 'directorypress_location_style',
				'title' => __('Locations Style For Archive Pages', 'DIRECTORYPRESS'),
				'options' => array(
					'default' => __('Default', 'DIRECTORYPRESS'), //styles replaced, needs database update
					'2' => __('Style 2', 'DIRECTORYPRESS'),
				),
				'default' => 'default',
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_location_padding',
				'title' => __('Locations Column Padding For Archive Pages', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'default' => 15,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_locations_depth',
				'title' => __('Locations nesting package', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 2,
				'default' => 1,
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_locations_columns',
				'title' => __('Locations columns number', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 4,
				'default' => 2,
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_sublocations_items',
				'title' => __('Show sub-locations items number', 'DIRECTORYPRESS'),
				'desc' => __('Leave 0 to show all sublocations', 'DIRECTORYPRESS'),
				'default' => 0,
				'validation' => 'numeric',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_location_count',
				'title' => __('Show location listings count?', 'DIRECTORYPRESS'),
				'default' => false,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'location-skin',
		'title' => __('Location Styling', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'loccation_typo',
				'type' => 'typography',
				'title' => esc_html__('Location Title Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'childlocation_typo',
				'type' => 'typography',
				'title' => esc_html__('Child Location Title Typography', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'subtitle' => '',
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '',
					'font-weight' => '',
					'line-height' => '',
				),
			),
			array(
				'id' => 'location_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Location Title Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'childlocation_typo_transform',
				'type' => 'button_set',
				'title' => esc_html__('Child Location Title Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'capitalize',
			),
			array(
				'id' => 'parent_loc_title_color',
				'type' => 'link_color',
				'title' => esc_html__('Parent Location title color', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('Background color will effect category style 6', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'subloc_title_color',
				'type' => 'link_color',
				'title' => esc_html__('Child Location title color', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '',
					'hover' => '',
				)
			),
			array(
				'id' => 'parent_loc_icon_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Icon  background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'parent_loc_icon_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Icon background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'loc_bg_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Wrapper background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'loc_bg_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Wrapper background on Hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'loc_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Wrapper Border Color', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('will effect Location style 4', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'loct_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Location Wrapper Border Color on hover', 'DIRECTORYPRESS'),
				'subtitle' => esc_html__('will effect Location style 4', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id'             => 'loc_border_radius',
				'type'           => 'spacing',
				//'output'         => array(''),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Location box border radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius For Location box', 'DIRECTORYPRESS'),
				'desc' => __('you can set radius for each corner separately e.g (top =  top left, right = top right, bottom =  bottom right, left = bottom left )', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'slider',
				'id' => 'parent_loc_icon_border_radius',
				'title' => __('Location Icon Border Radius', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 100,
				'default' => '',
			),
		),
	) );
	
	do_action('directorypress_after_locations_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
		'title' => __('Search settings', 'DIRECTORYPRESS'),
		'icon'  => 'el el-filter',
	));
	Redux::setSection( $opt_name, array(
        'id' => 'search',
		'title' => __('Main Search Box', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'select',
				'id' => 'search-form-style',
				'title' => __('Search Form Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1' => __('Style1', 'DIRECTORYPRESS'),
					'2' => __('Style2', 'DIRECTORYPRESS'),
				),
				'default' => 1,
			),
			array(
				'id' => 'main_searchbar_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Min Search Form box Background', 'DIRECTORYPRESS'),
				'default' => '',
			),	
			array(
				'id'             => 'search_form_box_padding',
				'type'           => 'spacing',
				//'output'         => array('.directorypress-search-holder'),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Search Form Box Padding', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Padding for search form main box', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '15', 
					'padding-right'   => '15', 
					'padding-bottom'  => '5', 
					'padding-left'    => '15',
					'units'          => 'px', 
				)
			),
			array(
				'id'       => 'vertical_search_form_box_border',
				'type'     => 'border',
				'title' => __('Main Search Box Border (Vertical form only)', 'DIRECTORYPRESS'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => '', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id'             => 'search_form_box_border_radius',
				'type'           => 'spacing',
				//'output'         => array(),
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Search Form Box Border Radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('values work as top-left, top-right, bottom-right, bottom-left', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_search_form_margin_top',
				'title' => __('Search Margin Top', 'DIRECTORYPRESS'),
				'min' => '-300',
				'max' => 300,
				'default' => '0',
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_main_search',
				'title' => __('Display search block in main part of page?', 'DIRECTORYPRESS'),
				'desc' => __('Note, that search widget is independent from this setting and this widget renders on each page where main search block was hidden', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'advanced_search_opean_on_archive',
				'title' => __('Advanced search always opened on archive pages', 'DIRECTORYPRESS'),
				'desc' => __('this option will effect search page, index page, category and location archive pages', 'DIRECTORYPRESS'),
				'default' => false,
			),
			array(
				'id' => 'search_advanced_fiter_button_bg_hover',
				'type' => 'color',
				'title' => esc_html__('Avcanced Filters Button Background Hover Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_advanced_fiter_button_bg',
				'type' => 'color',
				'title' => esc_html__('Avcanced Filters Button Background Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_advanced_fiter_button_color',
				'type' => 'link_color',
				'title' => esc_html__('Avcanced Filters Button Text Color', 'DIRECTORYPRESS'),
				'active' => false,
				'default' => array(
					'regular' => '#333',
					'hover' => '',
				)
			),
			array(
				'type' => 'switch',
				'id' => 'advanced_open_widget',
				'title' => __('Turn On Advanced Open by Default in sidebar Widget', 'DIRECTORYPRESS'),
				'default' => false,
			),
		),
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'search-fields-section',
		'title' => __('Fields General', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			array(
				'type' => 'slider',
				'id' => 'search_form_field_height',
				'title' => __('Fields Height', 'DIRECTORYPRESS'),
				'min' => 20,
				'max' => 100,
				'default' => 44,
			),
			array(
				'type' => 'slider',
				'id' => 'search_form_field_radius',
				'title' => __('Field Border Radius', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'gap_in_fields',
				'title' => __('Gap between Search Fields', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 100,
				'default' => 10,
			),
			array(
				'type' => 'slider',
				'id' => 'vertical_field_margin_bottom',
				'title' => __('Field margin bottom  (vertical form only)', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'default' => 0,
			),
			array(
				'id'             => 'vertical_field_box_radius',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Field Box Radius (vertical form only)', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'vertical_default_field_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Default Fields Padding (vertical form only)', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '5', 
					'padding-right'   => '0', 
					'padding-bottom'  => '5', 
					'padding-left'    => '0',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'search_form_field_label_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Field Label Padding (vertical form only)', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '15', 
					'padding-right'   => '15', 
					'padding-bottom'  => '15', 
					'padding-left'    => '15',
					'units'          => 'px', 
				)
			),
			array(
				'id'             => 'search_form_field_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('Field Content Padding (vertical form only)', 'DIRECTORYPRESS'),
				'subtitle'       => __('padding applies to input/select box, checkboxes container, radrio container etc', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => 'px', 
				)
			),	
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'search-fields-section-default',
		'title' => __('Default Fields', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			// KeyWord Field
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_keywords_search',
				'title' => __('Show keywords search?', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'keywords_ajax_search',
				'title' => __('Ajax KeyWord Search?', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'text',
				'id' => 'keywords_search_examples',
				'title' => __('Example Search KeyWords', 'DIRECTORYPRESS'),
				'default' => ''
			),
			array(
				'type' => 'slider',
				'id' => 'keyword_field_width',
				'title' => __('Search Form KeyWordField Width', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'default' => 25,
			),
			// categories field
			array(
				'type' => 'switch',
				'id' => 'show_keywords_category_combo',
				'title' => __('Show categories and Keywords combo field', 'DIRECTORYPRESS'),
				'desc' => __('if turned off keyword and category fields will render separately', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_categories_search',
				'title' => __('Show categories search', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'select',
				'id' => 'search_cat_icon_type',
				'title' => __('Search Category Icon Type', 'DIRECTORYPRESS'),
				'options' => array(
					'font' => __('Font', 'DIRECTORYPRESS'),
					'img' => __('Image', 'DIRECTORYPRESS'),
				),
				'default' => 'img',
			),
			array(
				'type' => 'slider',
				'id' => 'directorypress_show_categories_search_depth',
				'title' => __('Show Category Search Level', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 3,
				'default' => 1,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_category_count_in_search',
				'title' => __('Show listings counts in categories search dropboxes?', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_hide_empty_categories',
				'title' => __('Hide empty categories in search Form?', 'DIRECTORYPRESS'),
				'default' => true,
			),
			// location search
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_locations_search',
				'title' => __('Show locations search?', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'slider',
				'id' => 'locations_search_depth',
				'title' => __('Show Location Search Level', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 3,
				'default' => 1,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_address_search',
				'title' => __('Show address search?', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_location_count_in_search',
				'title' => __('Show listings counts in locations search dropboxes?', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'slider',
				'id' => 'location_field_width',
				'title' => __('Search Form Location Field Width', 'DIRECTORYPRESS'),
									 		'min' => '0',
									 		'max' => 100,
				'default' => 25,
			),
			// radius search field
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_radius_search',
				'title' => __('Show locations radius search?', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'slider',
				'id' => 'radius_field_width',
				'title' => __('Search Form Radius Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 100,
				'default' => 25,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_show_radius_tooltip',
				'title' => __('Show locations radius Tooltip Rather than label?', 'DIRECTORYPRESS'),
				'desc' => '',
				'default' => false,
			),
			array(
				'type' => 'radio',
				'id' => 'directorypress_miles_kilometers_in_search',
				'title' => __('Dimension in radius search', 'DIRECTORYPRESS'),
				'desc' => __('This setting is actual for both: main search block and widget', 'DIRECTORYPRESS'),
				'options' => array(
					'miles' => __('miles', 'DIRECTORYPRESS'),
					'kilometers' => __('kilometers', 'DIRECTORYPRESS'),
				),
				'default' => 'miles',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_radius_search_min',
				'title' => __('Minimum radius search', 'DIRECTORYPRESS'),
				'default' => 0,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_radius_search_max',
				'title' => __('Maximum radius search', 'DIRECTORYPRESS'),
				'default' => 10,
				'validation' => 'required|numeric',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_radius_search_default',
				'title' => __('Default radius search', 'DIRECTORYPRESS'),
				'default' => 0,
				'validation' => 'required|numeric',
			),
			array(
				'id' => 'search_radius_slider_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider Background Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_rage_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider Range Background Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_handle_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider Handle Background Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider Border Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_handle_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider Handle Border Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_border_width',
				'title' => esc_html__('Radius Slider Border Width', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 20,
				'default' => 1,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_handle_border_width',
				'title' => esc_html__('Radius Slider Handle Border Width', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 20,
				'units' => 'px',
				'default' => 6,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_height',
				'title' => esc_html__('Radius Slider height', 'DIRECTORYPRESS'),
				'min' => 1,
				'max' => 50,
				'units' => 'px',
				'default' => 4,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_range_height',
				'title' => esc_html__('Radius Slider Range Height', 'DIRECTORYPRESS'),
				'min' => 2,
				'max' => 50,
				'units' => 'px',
				'default' => 4,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_range_top',
				'title' => esc_html__('Radius Slider Range top/left margin', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 50,
				'units' => 'px',
				'default' => 0,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_handle_height',
				'title' => esc_html__('Radius Slider Handle height', 'DIRECTORYPRESS'),
				'min' => 20,
				'max' => 70,
				'units' => 'px',
				'default' => 20,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_handle_top',
				'title' => esc_html__('Radius Slider Hnadle Margin Top', 'DIRECTORYPRESS'),
				'min' => 8,
				'max' => 70,
				'units' => 'px',
				'default' => 8,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_handle_width',
				'title' => esc_html__('Radius Slider Handle Width', 'DIRECTORYPRESS'),
				'min' => 20,
				'max' => 100,
				'units' => 'px',
				'default' => 20,
			),
			array(
				'id' => 'search_radius_slider_radius',
				'type'           => 'spacing',
				//'output'         => '',
				'mode'           => 'padding',
				'units'          => array('px', '%'),
				'units_extended' => 'false',
				'title' => esc_html__('Radius Slider Border Radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius for Search Radius Slider', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'search_radius_slider_range_radius',
				'type'           => 'spacing',
				//'output'         => '',
				'mode'           => 'padding',
				'units'          => array('px', '%'),
				'units_extended' => 'false',
				'title' => esc_html__('Radius Slider Range Border Radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius for Search Radius Slider Range', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'id' => 'search_radius_slider_handle_radius',
				'type'           => 'spacing',
				//'output'         => '',
				'mode'           => 'padding',
				'units'          => array('px', '%'),
				'units_extended' => 'false',
				'title' => esc_html__('Radius Slider Handle Border Radius', 'DIRECTORYPRESS'),
				'subtitle'       => __('Set Border Radius for Search Radius Slider Handle', 'DIRECTORYPRESS'),
				'default'            => array(
					'margin-top'     => '', 
					'margin-right'   => '', 
					'margin-bottom'  => '', 
					'margin-left'    => '',
					'units'          => 'px', 
				)
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_tooltip_width',
				'title' => esc_html__('Radius Slider ToolTip Width', 'DIRECTORYPRESS'),
				'min' => 52,
				'max' => 100,
				'units' => 'px',
				'default' => 52,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_tooltip_left',
				'title' => esc_html__('Radius Slider ToolTip margin left in (-)', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'units' => 'px',
				'default' => 20,
			),
			array(
				'type' => 'slider',
				'id' => 'search_radius_slider_tooltip_top',
				'title' => esc_html__('Radius Slider ToolTip margin top in (-)', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 100,
				'units' => 'px',
				'default' => 42,
			),
			array(
				'id' => 'search_radius_slider_tooltip_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider ToolTip Background Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_tooltip_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Radius Slider ToolTip Border Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_radius_slider_tooltip_text_color',
				'type' => 'color',
				'title' => esc_html__('Radius Slider ToolTip text Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'search-fields-section-labels',
		'title' => __('Field Labels', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			// field labels
			array(
				'type' => 'switch',
				'id' => 'show_default_filed_label',
				'title' => __('Show Default fields Label? (horizontal forms only)', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'id' => 'search_form_field_label',
				'type' => 'typography',
				'title' => esc_html__('field label', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '13px',
					'font-weight' => '400',
					'line-height' => '44px',
				),
			),
			array(
				'id' => 'search_field_label_transform',
				'type' => 'button_set',
				'title' => esc_html__('Field Label Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'uppercase',
			),
			array(
				'id' => 'search_form_field_label_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Field Label Background (vertical form only)', 'DIRECTORYPRESS'),
				'default' => '#f9f9f9',
			),
			array(
				'id'       => 'vertical_form_field_label_border',
				'type'     => 'border',
				'title' => __('Fields Label Border (Vertical form only)', 'DIRECTORYPRESS'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => '', 
					'border-top'    => '', 
					'border-right'  => '', 
					'border-bottom' => '', 
					'border-left'   => ''
				)
			),
			array(
				'id' => 'search_box_input_label_color',
				'type' => 'color',
				'title' => esc_html__('Field Label Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			//end label
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'search-fields-section-placeholder',
		'title' => __('Field Placeholders', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			//field placeholder
			array(
				'id' => 'search_form_field_text',
				'type' => 'typography',
				'title' => esc_html__('field placeholder', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '13px',
					'font-weight' => '400',
					'line-height' => '44px',
				),
			),
			array(
				'id' => 'search_field_text_transform',
				'type' => 'button_set',
				'title' => esc_html__('Main Search Form Field placeholder Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'uppercase',
			),
			array(
				'id' => 'search_box_input_placeholer_color',
				'type' => 'color',
				'title' => esc_html__('Placeholder Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			// end placeholder
		)
	) );
	Redux::setSection( $opt_name, array(
        'id' => 'search-fields-colors',
		'title' => __('Fields Colors', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'search_form_field_content_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Field content Background (vertical form only)', 'DIRECTORYPRESS'),
				'default' => '#ffffff',
			),
			array(
				'id'       => 'vertical_field_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'Field container Box Shadow (vertical form only)', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id'       => 'search_form_field_border_width',
				'type'     => 'border',
				'title' => __('Search Form Fields Border', 'DIRECTORYPRESS'),
				'default'  => array(
					'border-color'  => '', 
					'border-style'  => 'solid', 
					'border-top'    => '1', 
					'border-right'  => '1', 
					'border-bottom' => '1', 
					'border-left'   => '1'
				)
			),
			array(
				'id'       => 'search_field_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'Field input/select Box Shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id' => 'search_box_input_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Input/select Background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_box_input_text_color',
				'type' => 'color',
				'title' => esc_html__('Input text Color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			
			array(
				'id' => 'search_selectbox_selector_icon_bg',
				'type' => 'color',
				'title' => esc_html__('Search Select Box and Geolocation selector icon background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_selectbox_selector_icon_border',
				'type' => 'color',
				'title' => esc_html__('Search Select Box and Geolocation selector icon border', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_selectbox_selector_icon_color',
				'type' => 'color',
				'title' => esc_html__('Search Select Box and Geolocation selector icon color', 'DIRECTORYPRESS'),
				'default' => '#555',
			),
		)
	));
	Redux::setSection( $opt_name, array(
        'id' => 'search-button-section',
		'title' => __('Search Button', 'DIRECTORYPRESS'),
		'icon'  => 'directorypress-fic1-search-1',
		'subsection' => true,
		'fields' => array(
			array(
				'id' => 'search_form_button_text',
				'type' => 'typography',
				'title' => esc_html__('Form button', 'DIRECTORYPRESS'),
				'compiler' => false, // Use if you want to hook in your own CSS compiler
				'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
				'font-backup' => false, // Select a backup non-google font in addition to a google font
				'font-style' => true, // Includes font-style and weight. Can use font-style or font-weight to declare
				'subsets' => false, // Only appears if google is true and subsets not set to false
				'font-size' => true,
				'line-height' => true,
				'color' => false,
				'preview' => false, // Disable the previewer
				'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
				'units' => 'px', // Defaults to px
				'default' => array(
					'font-family' => '',
					'google' => true,
					'font-size' => '13px',
					'font-weight' => '400',
					'line-height' => '34px',
				),
			),
			array(
				'id' => 'search_button_text_transform',
				'type' => 'button_set',
				'title' => esc_html__('Main Search Form Button Text Transform', 'DIRECTORYPRESS'),
				'options' => array('uppercase' => 'Uppercase', 'capitalize' => 'Capitalize', 'lowercase' => 'Lower Case'), 
				'default' => 'uppercase',
			),
			array(
				'type' => 'slider',
				'id' => 'search_form_button_height',
				'title' => __('Button Height', 'DIRECTORYPRESS'),
				'min' => 20,
				'max' => 100,
				'default' => 44,
			),
			array(
				'id'             => 'vertical_search_button_wrapper_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('button wrapper padding (vertical form only)', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => '', 
				)
			),
			array(
				'id'             => 'search_button_padding',
				'type'           => 'spacing',
				'mode'           => 'padding',
				'units'          => array('px'),
				'units_extended' => 'false',
				'title'          => __('button padding', 'DIRECTORYPRESS'),
				'default'            => array(
					'padding-top'     => '', 
					'padding-right'   => '', 
					'padding-bottom'  => '', 
					'padding-left'    => '',
					'units'          => '', 
				)
			),
			array(
				'type' => 'slider',
				'id' => 'search_form_button_border_radius',
				'title' => __('Button Border Radius', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 25,
				'default' => '',
			),
			
			array(
				'type' => 'slider',
				'id' => 'search_form_button_border_width',
				'title' => __('Search Form Button Border Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 10,
				'default' => '1',
			),
			array(
				'type' => 'select',
				'id' => 'search_button_type',
				'title' => __('Search Form Button Style', 'DIRECTORYPRESS'),
				'options' => array(
					'1'  => __( 'Text + Icon Left', 'DIRECTORYPRESS' ),
					'2'  => __( 'Text + Icon Right', 'DIRECTORYPRESS' ),
					'3'  => __( 'Text Only', 'DIRECTORYPRESS' ),
					'4'  => __( 'Icon Only', 'DIRECTORYPRESS' ),
				),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'search_button_icon',
				'title' => __('Search Form Button Icon', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'switch',
				'id' => 'hide_search_button',
				'title' => __('Hide Search Button to work with ajax?', 'DIRECTORYPRESS'),
				'default' => false,
			),
			
			
			array(
				'type' => 'slider',
				'id' => 'button_field_width',
				'title' => __('Search Form Button Width', 'DIRECTORYPRESS'),
				'min' => '0',
				'max' => 100,
				'default' => 25,
			),
			array(
				'type' => 'slider',
				'id' => 'button_field_margin_top',
				'title' => __('Search Form Button Margin Top', 'DIRECTORYPRESS'),
				'desc' => __('Search Form Button Margin Top in PX (May be required if button is not in first row)', 'DIRECTORYPRESS'),
				'min' => 0,
				'max' => 50,
				'default' => 0,
			),
			array(
				'id' => 'search_form_btn_color',
				'type' => 'link_color',
				'title' => esc_html__('Main Search Form Button Colors', 'DIRECTORYPRESS'),
				'regular' => true,
				'hover' => true,
				'bg' => false,
				'bg-hover' => false,
				'default' => array(
					'regular' => '#ffffff',
					'hover' => '#fff',
				)
			),
			array(
				'id' => 'search_form_btn_color_bg',
				'type' => 'color_rgba',
				'title' => esc_html__('Main search bar button background', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_form_btn_color_bg_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Main search bar button background on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id'       => 'search_button_box_shadow',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'button box shadow', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id'       => 'search_button_box_shadow_hover',
				'type'     => 'box_shadow',
				'inset-shadow' => false,
				'title'       => esc_html__( 'button box shadow (hover)', 'DIRECTORYPRESS' ),
				'default' => array(
					'drop-shadow'  => array(
						'checked'    => true,
						'color'      => '',
						'horizontal' => 0,
						'vertical'   => 0,
						'blur'       => 0,
						'spread'     => 0,
					)
				),
			),
			array(
				'id' => 'search_form_btn_border_color',
				'type' => 'color_rgba',
				'title' => esc_html__('Main search bar button border color', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'id' => 'search_form_btn_border_color_hover',
				'type' => 'color_rgba',
				'title' => esc_html__('Main search bar button border color on hover', 'DIRECTORYPRESS'),
				'default' => '',
			),
		)
	));
	
	do_action('directorypress_after_search_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
        'id' => 'addresses',
		'title' => __('Address Settings', 'DIRECTORYPRESS'),
		'icon' => 'el el-address-book',
		'fields' => array(
			array(
				'type' => 'text',
				'id' => 'directorypress_default_geocoding_location',
				'title' => __('Default Location', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'sorter',
				'id' => 'directorypress-addresses-order',
				'title' => __('Order of address lines on single listing page', 'DIRECTORYPRESS'),
				'options' => array(
					'enabled' => array(
						'line-1' => __('Address Line 1', 'DIRECTORYPRESS'),
						'comma1' => __('-- Comma (,) --', 'DIRECTORYPRESS'),
						'space1' => __('-- Space ( ) --', 'DIRECTORYPRESS'),
						'line-2' => __('Address Line 2', 'DIRECTORYPRESS'),
						'comma2' => __('-- Comma (,) --', 'DIRECTORYPRESS'),
						'space2' => __('-- Space ( ) --', 'DIRECTORYPRESS'),
						'location' => __('Selected location', 'DIRECTORYPRESS'),
						'comma3' => __('-- Comma (,) --', 'DIRECTORYPRESS'),
						'space3' => __('-- Space ( ) --', 'DIRECTORYPRESS'),
						'zip' => __('Zip code or postal index', 'DIRECTORYPRESS'),
						'break1' => __('-- Line Break --', 'DIRECTORYPRESS'),
						'break2' => __('-- Line Break --', 'DIRECTORYPRESS'),
					),
					'disabled' => array()
				),
				'desc' => __('Order address elements as you wish, commas and spaces help to build address line.'),
											//'default' => get_option('directorypress_addresses_order'),
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_address_line_1',
				'title' => __('Enable address line 1 field', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_address_line_2',
				'title' => __('Enable address line 2 field', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_postal_index',
				'title' => __('Enable zip code', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_additional_info',
				'title' => __('Enable additional info field', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_manual_coords',
				'title' => __('Enable manual coordinates fields', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_address_autocomplete',
				'title' => __('Enable autocomplete on addresses fields', 'DIRECTORYPRESS'),
				'default' => true,
			),
			array(
				'type' => 'switch',
				'id' => 'directorypress_address_geocode',
				'title' => __('Enable "Get my location" button on addresses fields (Directorypress Maps Addon required)', 'DIRECTORYPRESS'),
				'default' => false,
			),
		),
	) );
	
	do_action('directorypress_after_address_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
		'id' => 'icon_settings',
		'title' => __('Icon Settings', 'DIRECTORYPRESS'),
		'icon'  => 'fas fa-folder'
	));
	Redux::setSection( $opt_name, array(
        'id' => 'single_listing_icon_setting',
		'title' => __('Single Listing Meta Icons', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			array(
			   'id' => 'section_single_listing_meta_date_start',
			   'type' => 'section',
			   'title' => esc_html__('Meta Date Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_meta_date_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_date_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_date_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_date_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_meta_date_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_meta_date_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_meta_date_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_date_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_meta_date_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// meta views
			array(
			   'id' => 'section_single_listing_meta_views_start',
			   'type' => 'section',
			   'title' => esc_html__('Meta Views Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_meta_views_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('Icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_views_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_views_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_views_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_meta_views_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_meta_views_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_meta_views_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_views_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_meta_views_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// meta ID
			array(
			   'id' => 'section_single_listing_meta_id_start',
			   'type' => 'section',
			   'title' => esc_html__('Meta ID Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_meta_id_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('Icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_id_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_id_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_meta_id_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_meta_id_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_meta_id_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_meta_id_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_meta_id_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_meta_id_end',
			   'type' => 'section',
			   'indent' => false 
			),
		)
	));
	Redux::setSection( $opt_name, array(
        'id' => 'single_listing_buttons_icon_setting',
		'title' => __('Single Listing Buttons Icons', 'DIRECTORYPRESS'),
		'subsection' => true,
		'fields' => array(
			// report button
			array(
			   'id' => 'section_single_listing_button_report_start',
			   'type' => 'section',
			   'title' => esc_html__('Report Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_report_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_report_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_report_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_report_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_report_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_report_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_report_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_report_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_report_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// download button
			array(
			   'id' => 'section_single_listing_button_download_start',
			   'type' => 'section',
			   'title' => esc_html__('Download Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_download_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_download_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_download_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_download_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_download_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_download_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_download_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_download_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_download_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// Print button
			array(
			   'id' => 'section_single_listing_button_print_start',
			   'type' => 'section',
			   'title' => esc_html__('Print Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_print_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_print_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_print_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_print_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_print_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_print_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_print_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_print_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_print_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// Bookmark button
			array(
			   'id' => 'section_single_listing_button_bookmark_start',
			   'type' => 'section',
			   'title' => esc_html__('Bookmark Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_bookmark_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_bookmark_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_bookmark_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_bookmark_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_bookmark_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_bookmark_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_bookmark_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_bookmark_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_bookmark_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// Share button
			array(
			   'id' => 'section_single_listing_button_share_start',
			   'type' => 'section',
			   'title' => esc_html__('Share Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_share_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_share_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_share_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_share_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_share_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_share_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_share_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_share_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_share_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// Claim button
			array(
			   'id' => 'section_single_listing_button_claim_start',
			   'type' => 'section',
			   'title' => esc_html__('Claim Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_claim_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_claim_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_claim_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_claim_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_claim_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_claim_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_claim_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_claim_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_claim_end',
			   'type' => 'section',
			   'indent' => false 
			),
			// Edit button
			array(
			   'id' => 'section_single_listing_button_edit_start',
			   'type' => 'section',
			   'title' => esc_html__('Edit Button Icon', 'DIRECTORYPRESS'),
			   'indent' => true 
			),
			array(
				'id'       => 'single_listing_button_edit_icon_type',
				'type'     => 'select',
				'title'    => esc_html__('icon type', 'DIRECTORYPRESS'), 
				'options'  => array(
					'font' => esc_html__('Font', 'DIRECTORYPRESS'), 
					'img' => esc_html__('Image', 'DIRECTORYPRESS'), 
				),
				'default'  => 'font',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_edit_icon',
				'title'    => esc_html__('Icon class', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_edit_icon_size',
				'title'    => esc_html__('Icon Size (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'single_listing_button_edit_icon_line_height',
				'title'    => esc_html__('Icon line height (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'id' => 'single_listing_button_edit_icon_color',
				'type' => 'color',
				'title' => esc_html__('Icon colour', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'font' ),
				'default' => '',
			),
			array(
				'type' => 'media',
				'id' => 'single_listing_button_edit_icon_url',
				'url' => false,
				'title' => __('Icon image', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'img' ),
				'default' => '',
			),
			array(
				'type' => 'slider',
				'id' => 'single_listing_button_edit_icon_width',
				'title' => __('Icon image width (px)', 'DIRECTORYPRESS'),
				'required' => array( 'single_listing_button_edit_icon_type', '=', 'img' ),
				'min' => 10,
				'max' => 100,
				'default' => '',
			),
			array(
			   'id' => 'section_single_listing_button_edit_end',
			   'type' => 'section',
			   'indent' => false 
			),
		)
	));
	
	Redux::setSection( $opt_name, array(
        'id' => 'notifications',
		'title' => __('Email notifications', 'DIRECTORYPRESS'),
		'icon'  => 'el el-envelope',
		'fields' => array(
			array(
				'type' => 'text',
				'id' => 'directorypress_send_expiration_notification_days',
				'title' => __('Days before pre-expiration notification will be sent', 'DIRECTORYPRESS'),
				'default' => 1,
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_admin_notifications_email',
				'title' => __('This email will be used for notifications to admin and in "From" field. Required to send emails.', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_admin_notifications_phone_number',
				'title' => __('This Phone Number will be used for notifications to Admin on Mobile.', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_preexpiration_notification',
				'title' => __('Pre-expiration notification', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[listing], [days], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_expiration_notification',
				'title' => __('Expiration notification', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_newuser_notification',
				'title' => __('Registration of new user notification', 'DIRECTORYPRESS'),
				'desc' => __('You can use following parameters, #password_set_link, #username, #blogname', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_newlisting_admin_notification',
				'title' => __('Notification to admin about new listing creation', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[user], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_editlisting_admin_notification',
				'title' => __('Notification to admin about listing modification and pending status', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[user], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_listing_submission_notification',
				'title' => __('Notification to author about new listing Submission', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[author], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_pending_approval_notification',
				'title' => __('Notification to author about listing pending approval', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[author], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_approval_notification',
				'title' => __('Notification to author about successful listing approval', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[author], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_claim_notification',
				'title' => __('Notification of claim to current listing owner', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[author], [listing], [claimer], [link], [message]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_claim_approval_notification',
				'title' => __('Notification of successful approval of claim', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[claimer], [listing], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_claim_decline_notification',
				'title' => __('Notification of claim decline', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[claimer], [listing]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_invoice_create_notification',
				'title' => __('Notification of new invoice', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[invoice], [id], [billing], [item], [price], [link]',
				'default' => '',
			),
			array(
				'type' => 'textarea',
				'id' => 'directorypress_invoice_paid_notification',
				'title' => __('Notification of paid invoice', 'DIRECTORYPRESS'),
				'desc' => __('Tags allowed: ', 'DIRECTORYPRESS') . '[author], [invoice], [price]',
				'default' => '',
			),
		),
	) );
	
	do_action('directorypress_after_notifications_settings', new Redux, $opt_name);
	
	Redux::setSection( $opt_name, array(
		'id' => 'recaptcha',
		'title' => __('reCaptcha settings', 'DIRECTORYPRESS'),
		'icon' => 'el el-refresh',
		'fields' => array(
			array(
				'type' => 'switch',
				'id' => 'directorypress_enable_recaptcha',
				'title' => __('Enable reCaptcha', 'DIRECTORYPRESS'),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_has_recaptcha_public_key',
				'title' => __('reCaptcha public key', 'DIRECTORYPRESS'),
				'desc' => sprintf(__('get your reCAPTCHA API Keys <a href="%s" target="_blank">here</a>', 'DIRECTORYPRESS'), 'http://www.google.com/recaptcha'),
				'default' => '',
			),
			array(
				'type' => 'text',
				'id' => 'directorypress_has_recaptcha_private_key',
				'title' => __('reCaptcha private key', 'DIRECTORYPRESS'),
				'default' => '',
			),
		),
	) );
	
	global $sitepress;
	if (function_exists('wpml_object_id_filter') && $sitepress) {
		Redux::setSection( $opt_name, array(
			'id' => 'wpml-selction',
			'title' => __('WPML Option', 'DIRECTORYPRESS'),
			'icon' => 'directorypress-li-refresh',
			'fields' => array(
				array(
					'type' => 'switch',
					'id' => 'directorypress_map_language_from_wpml',
					'title' => __('Force WPML language on maps', 'W2GM'),
					'desc' => __("Ignore the browser's language setting and force it to display information in a particular WPML language", 'DIRECTORYPRESS'),
					'default' => false,
				),
				array(
					'type' => 'switch',
					'id' => 'directorypress_enable_frontend_translations',
					'title' => __('Enable frontend translations', 'DIRECTORYPRESS'),
					'default' => false,
				)
			),
		) );
	}
	
	do_action('directorypress_after_settings', new Redux, $opt_name);