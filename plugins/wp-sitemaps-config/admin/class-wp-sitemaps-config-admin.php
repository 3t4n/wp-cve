<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WP_Sitemaps_Config
 * @subpackage WP_Sitemaps_Config/admin
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */

if ( ! class_exists( 'WP_Sitemaps_Config_Admin' ) ) {
class WP_Sitemaps_Config_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_version    The current version of this plugin.
	 */
	private $plugin_version;

	/**
	 * Unique identifier in the WP options table
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $settings_db_slug;

	/**
	 * Slug of the menu page on which to display the form sections
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $settings_section_slug;

	/**
	 * Group name of options
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string
	 */
	private $settings_fields_options;
	
	/**
	 * Structure of the form sections with headline, description and options
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array|null
	 */
	private $form_structure;

	/**
	 * Stored settings in an array
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array|null
	 */
	private $stored_settings;

	/**
	 * Label text of the Post Exclusion checkbox, once defined for multiple, but consistent usage
	 *
	 * @since    2.0.0
	 * @access   private
	 * @var      string
	 */
	private $exclude_post_label;

	/**
	 * Options for the change frequency
	 *
	 * @since    2.2.0
	 * @access   private
	 * @var      array
	 */
	private $changefreq_options;

	/**
	 * Key names of the change frequency options
	 *
	 * @since    2.2.0
	 * @access   private
	 * @var      array
	 */
	private $changefreq_keys;

	/**
	 * Options of the priority
	 *
	 * @since    2.2.0
	 * @access   private
	 * @var      array
	 */
	private $priority_options;

	/**
	 * Key names of the priority options
	 *
	 * @since    2.2.0
	 * @access   private
	 * @var      array
	 */
	private $priority_keys;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    array     $args    Parameters of this plugin
	 */
	public function __construct( $args ) {

		$this->plugin_name		= $args['name'];
		$this->plugin_slug		= $args['slug'];
		$this->plugin_version	= $args['plugin_version'];

		$this->settings_db_slug = WP_SITEMAPS_CONFIG_OPTION_NAME;
		$this->settings_section_slug = 'wp-sitemaps-config-options-page';
		$this->settings_fields_options = 'wp-sitemaps-config-options';
		
		$this->exclude_post_label = __( 'Exclude this post from the XML sitemap', 'wp-sitemaps-config' );
		
		$this->changefreq_options = array(
			'always'	=>	__( 'always', 'wp-sitemaps-config' ),
			'hourly'	=>	__( 'hourly', 'wp-sitemaps-config' ),
			'daily'		=>	__( 'daily', 'wp-sitemaps-config' ),
			'weekly' 	=>	__( 'weekly (Default)', 'wp-sitemaps-config' ),
			'monthly' 	=>	__( 'monthly', 'wp-sitemaps-config' ),
			'yearly' 	=>	__( 'yearly', 'wp-sitemaps-config' ),
			'never' 	=>	__( 'never', 'wp-sitemaps-config' ),
		);
		$this->changefreq_keys = array_keys( $this->changefreq_options );

		$this->priority_options = array(
			'1.0' => __( '1.0', 'wp-sitemaps-config' ),
			'0.9' => __( '0.9', 'wp-sitemaps-config' ),
			'0.8' => __( '0.8', 'wp-sitemaps-config' ),
			'0.7' => __( '0.7', 'wp-sitemaps-config' ),
			'0.6' => __( '0.6', 'wp-sitemaps-config' ),
			'0.5' => __( '0.5 (Default)', 'wp-sitemaps-config' ),
			'0.4' => __( '0.4', 'wp-sitemaps-config' ),
			'0.3' => __( '0.3', 'wp-sitemaps-config' ),
			'0.2' => __( '0.2', 'wp-sitemaps-config' ),
			'0.1' => __( '0.1', 'wp-sitemaps-config' ),
			'0.0' => __( '0.0', 'wp-sitemaps-config' ),
		);
		$this->priority_keys = array_keys( $this->priority_options );


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
		 * defined in WP_Sitemaps_Config_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Sitemaps_Config_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		// load CSS if the current page is not a settings page of this plugin
		$screen = get_current_screen();
		if ( 'settings_page_wp-sitemaps-config' === $screen->id ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-sitemaps-config-admin.css', array(), $this->plugin_version, 'all' );
		}
		
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Sitemaps_Config_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Sitemaps_Config_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-sitemaps-config-admin.js', array( 'jquery' ), $this->plugin_version, false );

	}

	/**
	 * Get current or default settings
	 *
	 * @since    1.0.0
	 */
	public function get_stored_settings() {
		// try to load current settings
		$settings = get_option( $this->settings_db_slug );
		
		// if proper settings, then return them
		if ( $settings && is_array( $settings ) ) {
			return $settings;
		}
		
		// if no settings, then return default values
		return array(
			'add_changefreq' => 0,
			'add_lastmod' => 0,
			'add_priority' => 0,
			'remove_all_sitemaps' => 0,
			'remove_provider_posts' => 0,
			'remove_provider_taxonomies' => 0,
			'remove_provider_users' => 0,
			'remove_sitemap_posts_page' => 0,
			'remove_sitemap_posts_post' => 0,
			'remove_sitemap_taxonomies_category' => 0,
			'remove_sitemap_taxonomies_post_format' => 0,
			'remove_sitemap_taxonomies_post_tag' => 0,
		);
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_menu_item_to_options_page() {

		$text_1 = 'Settings';
		$text_2 = 'XML Sitemap';
		// Add a settings page for this plugin to the Settings menu.
		$this->plugin_screen_hook_suffix = add_options_page(
			/* translators: 1: plugin name 2: 'Settings' translated*/
			sprintf( '%1$s %2$s', $this->plugin_name, __( $text_1 ) ),
			__( $text_2 ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_options_page' )
		);

	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		$text = 'Settings';
		return array_merge(
			array(
				'settings' => '<a href="' . esc_url( admin_url( 'options-general.php?page=' . $this->plugin_slug ) ) . '">' . esc_html__( $text ) . '</a>'
			),
			$links
		);

	}

	/**
	 * Print a message about the location of the plugin in the WP backend
	 * 
	 * @since    1.0.0
	 * @access   public
	 */
	public function display_activation_message () {

		$text_1 = 'Settings';
		$text_2 = 'XML Sitemap';
		
		if ( is_rtl() ) {
			$sep = '&lsaquo;';
			// set link
			$link = sprintf(
				'<a href="%s">%s %s %s</a>',
				esc_url( admin_url( sprintf( 'options-general.php?page=%s', $this->plugin_slug ) ) ),
				esc_html__( $text_2 ),
				$sep,
				esc_html__( $text_1 )
			);
		} else {
			$sep = '&rsaquo;';
			// set link #2
			$link = sprintf(
				'<a href="%s">%s %s %s</a>',
				esc_url( admin_url( sprintf( 'options-general.php?page=%s', $this->plugin_slug ) ) ),
				esc_html__( $text_1 ),
				$sep,
				esc_html__( $text_2 )
			);
		}
		
		// print the whole message
		printf(
			'<div class="updated notice is-dismissible"><p>%s</p></div>',
			sprintf( 
				/* translators: 1: plugin name 2: link to the options page */
				esc_html__( 'Welcome to %1$s! You can find the plugin at %2$s.', 'wp-sitemaps-config' ),
				$this->plugin_name,
				$link
			)
		);
		
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_options_page() {
		include_once( 'partials/wp-sitemaps-config-admin-display.php' );
	}

	/**
	 * Define and register the options
	 * Run on admin_init()
	 *
	 * @since    1.0.0
	 */
	public function register_options () {

		// translate once
		/* translators: public name of the post type or a taxonomy, mostly in the plural form */
		$remove_sitemap_label = esc_html__( 'Remove sitemap of %s', 'wp-sitemaps-config' );
		/* translators: public name of the sitemap provider, mostly in the plural form */
		$remove_provider_label = esc_html__( 'Remove all sitemaps of %s', 'wp-sitemaps-config' );
		
		/*
		 * Set all sitemap providers options
		 */
		$sitemap_providers = array (
			'remove_provider_posts'			=> esc_html__( 'Remove all sitemaps of posts', 'wp-sitemaps-config' ),
			'remove_provider_taxonomies'	=> esc_html__( 'Remove all sitemaps of taxonomies', 'wp-sitemaps-config' ),
			'remove_provider_users'			=> esc_html__( 'Remove all sitemaps of users', 'wp-sitemaps-config' ),
		);

		/*
		 * Set all public post types options
		 */
		$sitemap_post_types = array();
		// get all post types used by the WP Sitemaps
		$types = $this->get_posts_subtypes();
		$sitemap_post_types = array();
		foreach ( $types as $name => $data ) {
			//$sitemap_post_types[ $name ] = $data->label;
			$sitemap_post_types[ 'remove_sitemap_posts_' . $name ] = sprintf( $remove_sitemap_label, $data->label );
		}

		/*
		 * Set all public taxonomies options
		 */
		$sitemap_taxonomy_types = array();
		// get all taxonomy types used by the WP Sitemaps
		$types = $this->get_taxonomies_subtypes();
		$sitemap_taxonomy_types = array();
		foreach ( $types as $name => $data ) {
			//$sitemap_taxonomy_types[ $name ] = $data->label;
			$sitemap_taxonomy_types[ 'remove_sitemap_taxonomies_' . $name ] = sprintf( $remove_sitemap_label, $data->label );
			if ( 'post_format' === $name ) {
				//if ( current_theme_supports( 'post-formats' ) ) {
				$sitemap_taxonomy_types[ 'remove_sitemap_taxonomies_' . $name ] .= ' ' . esc_html__( '(if the current theme supports post formats)', 'wp-sitemaps-config' );
			}
		}
		
		/* maybe in future a more dynamic approach?
		$sitemap_providers = array();
		$sitemap_types = array();
		$providers = wp_get_sitemap_providers();
		foreach ( $providers as $key => $instance ) {
			$sitemap_providers[ 'remove_provider_' . $key ] = sprintf( $remove_provider_label, $key ); // better: $instance->name for $key, but it is a protected property
			if ( method_exists( $instance, 'get_object_subtypes' ) ) {
				$types = $instance->get_object_subtypes(); // caution: included hook can filter subtypes out!
				if ( is_array( $types ) ) {
					$sitemap_types[ $key ] = array();
					foreach ( $types as $name => $data ) {
						$sitemap_types[ $key ][ 'remove_sitemap_' . $key . '_' . $name ] = sprintf( $remove_sitemap_label, $data->label );
						if ( 'post_format' === $name ) {
							// if ( current_theme_supports( 'post-formats' ) ) {
							$sitemap_types[ $key ][ 'remove_sitemap_' . $key . '_' . $name ] .= ' ' . esc_html__( '(if the current theme supports post formats)', 'wp-sitemaps-config' );
						}
					}
				}
			}
		}

		will yield on a WP default installation:
		
		$sitemap_providers = array (
		  'remove_provider_posts' => 'Remove provider for posts',
		  'remove_provider_taxonomies' => 'Remove provider for taxonomies',
		  'remove_provider_users' => 'Remove provider for users',
		);

		$sitemap_types = array (
		  'posts' => array (
			'remove_sitemap_posts_post' => 'Remove sitemap of Posts',
			'remove_sitemap_posts_page' => 'Remove sitemap of Pages',
		  ),
		  'taxonomies' => array (
			'remove_sitemap_taxonomies_category' => 'Remove sitemap of Categories',
			'remove_sitemap_taxonomies_post_tag' => 'Remove sitemap of Tags',
			'remove_sitemap_taxonomies_post_format' => 'Remove sitemap of Formats (if the current theme supports post formats)',
		  ),
		  'users' => array (),
		);

		*/

		// define the form sections, order by appereance, with headlines, and options
		$this->form_structure = array(
			'1st_section' => array(
				'headline' => esc_html__( 'Configure The WordPress XML Sitemap', 'wp-sitemaps-config' ),
				'description' => esc_html__( 'With the following setting options you control the output of the sitemaps.', 'wp-sitemaps-config' ),
				'options' => array(
					'remove_all_sitemaps' => array(
						'type'    => 'checkbox',
						'title'   => esc_html__( 'Sitemaps in general', 'wp-sitemaps-config' ),
						'desc'    => esc_html__( 'Remove all sitemaps completely. If checked, all other options have no effect. Instead of the sitemaps, the 404 error page is displayed.', 'wp-sitemaps-config' ),
					),
					'remove_providers' => array(
						'type'    => 'checkboxes',
						'title'   => esc_html__( 'Sitemap types', 'wp-sitemaps-config' ),
						'desc'    => esc_html__( 'Select the type of sitemaps you want to remove completely. If checked, the respective options for the subtypes have no effect.', 'wp-sitemaps-config' ),
						'values'  => $sitemap_providers,
					),
					'remove_sitemaps_posts' => array(
						'type'    => 'checkboxes',
						'title'   => esc_html__( 'Sitemaps of posts', 'wp-sitemaps-config' ),
						'desc'    => esc_html__( 'Select the sitemaps you do not want to publish.', 'wp-sitemaps-config' ),
						'values'  => $sitemap_post_types,
					),
					'remove_sitemaps_taxonomies' => array(
						'type'    => 'checkboxes',
						'title'   => esc_html__( 'Sitemaps of taxonomies', 'wp-sitemaps-config' ),
						'desc'    => esc_html__( 'Select the sitemaps you do not want to publish.', 'wp-sitemaps-config' ),
						'values'  => $sitemap_taxonomy_types,
					),
					'additional_tags' => array(
						'type'    => 'checkboxes',
						'title'   => esc_html__( 'Additional tags to sitemap entries', 'wp-sitemaps-config' ),
						'desc'    => esc_html__( 'Select the optional tags you want to add in the sitemap entries. These tags are not typically consumed by search engines. Further tags are not currently supported by WordPress for the sitemap index.', 'wp-sitemaps-config' ),
						'values'  => array(
							'add_priority'		=> esc_html__( 'Add priority', 'wp-sitemaps-config' ),
							'add_changefreq'	=> esc_html__( 'Add change frequency', 'wp-sitemaps-config' ),
							'add_lastmod'		=> esc_html__( 'Add last modification date', 'wp-sitemaps-config' ),
						),
					),
				),
			),
		);

		// get current settings
		$this->stored_settings = $this->get_stored_settings();

		// build form with sections and options
		foreach ( $this->form_structure as $section_key => $section_values ) {
		
			// assign callback functions to form sections (options groups)
			add_settings_section(
				// 'id' attribute of tags
				$section_key, 
				// title of the section.
				$this->form_structure[ $section_key ][ 'headline' ],
				// callback function that fills the section with the desired content
				array( $this, 'print_section_' . $section_key ),
				// menu page on which to display this section
				$this->settings_section_slug
			); // end add_settings_section()
			
			// set labels and callback function names per option name
			foreach ( $section_values[ 'options' ] as $option_name => $option_values ) {
				// set default description
				$desc = '';
				if ( isset( $option_values[ 'desc' ] ) and '' != $option_values[ 'desc' ] ) {
					if ( 'checkbox' == $option_values[ 'type' ] ) {
						$desc =  $option_values[ 'desc' ];
					} else {
						$desc =  sprintf( '<p class="description">%s</p>', $option_values[ 'desc' ] );
					}
				}
				// build the form elements values
				switch ( $option_values[ 'type' ] ) {
					case 'checkboxes':
						$title = $option_values[ 'title' ];
						$html = sprintf( '<fieldset><legend class="screen-reader-text"><span>%s</span></legend>', $title );
						foreach ( $option_values[ 'values' ] as $value => $label ) {
							$stored_value = isset( $this->stored_settings[ $value ] ) ? esc_attr( $this->stored_settings[ $value ] ) : 0;
							$checked = $stored_value ? checked( 1, $stored_value, false ) : '';
							$html .= sprintf( '<label for="%s"><input name="%s[%s]" type="checkbox" id="%s" value="1"%s /> %s</label><br />' , $value, $this->settings_db_slug, $value, $value, $checked, $label 
							);
						}
						$html .= '</fieldset>';
						$html .= $desc;
						break;
					case 'checkbox':
						$title = $option_values[ 'title' ];
						$value = isset( $this->stored_settings[ $option_name ] ) ? esc_attr( $this->stored_settings[ $option_name ] ) : 0;
						$checked = $value ? checked( 1, $value, false ) : '';
						$html = sprintf( '<label for="%s"><input name="%s[%s]" type="checkbox" id="%s" value="1"%s /> %s</label>' , $option_name, $this->settings_db_slug, $option_name, $option_name, $checked, $desc );
						break;
					// else text field
					default:
						$title = sprintf( '<label for="%s">%s</label>', $option_name, $option_values[ 'title' ] );
						$value = isset( $this->stored_settings[ $option_name ] ) ? esc_attr( $this->stored_settings[ $option_name ] ) : '';
						$html = sprintf( '<input type="text" id="%s" name="%s[%s]" value="%s">', $option_name, $this->settings_db_slug, $option_name, $value );
						$html .= $desc;
				} // end switch()

				// register the option
				add_settings_field(
					// form field name for use in the 'id' attribute of tags
					$option_name,
					// title of the form field
					$title,
					// callback function to print the form field
					array( $this, 'print_option' ),
					// menu page on which to display this field for do_settings_section()
					$this->settings_section_slug,
					// section where the form field appears
					$section_key,
					// arguments passed to the callback function 
					array(
						'html' => $html,
					)
				); // end add_settings_field()

			} // end foreach( section_values )

		} // end foreach( section )

		// finally register all options. They will be stored in the database in the wp_options table under the options name $this->settings_db_slug.
		register_setting( 
			// group name in settings_fields()
			$this->settings_fields_options,
			// name of the option to sanitize and save in the db
			$this->settings_db_slug,
			// callback function that sanitizes the option's value.
			array( $this, 'sanitize_options' )
		); // end register_setting()
		
	} // end register_options()

	/**
	 * Check and return correct values for the settings
	 *
	 * @since    1.0.0
	 * @param   array    $input    Options and their values after submitting the form
	 * @return  array              Options and their sanatized values
	 */
	public function sanitize_options ( $input ) {
		foreach ( $this->form_structure as $section_name => $section_values ) {
			foreach ( $section_values[ 'options' ] as $option_name => $option_values ) {
				switch ( $option_values[ 'type' ] ) {
					// if checkbox is set assign 1, else 0
					case 'checkbox':
						$input[ $option_name ] = isset( $input[ $option_name ] ) ? $input[ $option_name ] : 0 ;
						break;
					// if checkbox of a group of checkboxes is set assign 1, else 0
					case 'checkboxes':
						foreach ( array_keys( $option_values[ 'values' ] ) as $option_name ) {
							$input[ $option_name ] = isset( $input[ $option_name ] ) ? $input[ $option_name ] : 0 ;
						}
						break;
					// clean all other form elements values
					default:
						$input[ $option_name ] = sanitize_text_field( $input[ $option_name ] );
				} // end switch()
			} // foreach( options )
		} // foreach( sections )

		return $input;
	} // end sanitize_options()

	/**
	 * Print the option
	 *
	 * @since    1.0.0
	 * @param   string    $args    HTML code of the option
	 */
	public function print_option ( $args ) {
		echo $args[ 'html' ];
	}

	/**
	 * Print the explanation for section 1
	 *
	 * @since    1.0.0
	 */
	public function print_section_1st_section () {
		printf( "<p>%s</p>\n", $this->form_structure[ '1st_section' ][ 'description' ] );
	}

	/** === All functions for the tab 'General' === */

	/**
	 * Print the content for the tab 'General'
	 *
	 * @since    2.0.0
	 */
	public function print_content_general () {
		echo '<form method="post" action="options.php">', "\n";
		settings_fields( $this->settings_fields_options );
		do_settings_sections( $this->settings_section_slug );
		submit_button();
		echo '</form>', "\n";
	}

	/** === All functions for the tab 'Posts' === */

	/**
	 * Print the content for the tab 'Posts'
	 *
	 * @since    2.0.0
	 */
	public function print_content_posts () {
		$intendation = '				';
		printf( "$intendation<h2>%s</h2>\n", esc_html__( 'List of excluded posts', 'wp-sitemaps-config' ) );
		
		global $wpdb;
		$results = $wpdb->get_results( $wpdb->prepare( "
			SELECT posts.ID, posts.post_type FROM $wpdb->postmeta AS meta
			LEFT JOIN $wpdb->posts AS posts ON posts.ID = meta.post_id
			WHERE meta.meta_key LIKE '%s'
			ORDER BY posts.post_type, posts.post_title;
		", WP_SITEMAPS_CONFIG_META_KEY ) );
		
		if ( $results ) {
			printf( "$intendation<p>%s</p>\n", esc_html__( 'You see a list of posts excluded from the WordPress XML sitemap, grouped by post type, sorted alphabetically by post title. Each link points to the edit page of the post and opens a new tab.', 'wp-sitemaps-config' ) );
			
			// pre-translate
			$translated = array();
			$text = '(opens in a new tab)';
			$translated[ 'link notice' ] = __( $text );
			$text = '(no title)';
			$translated[ 'no title' ] = __( $text );
			$translated[ 'unknown post type' ] = __( 'Unknown post type', 'wp-sitemaps-config' );

			// list excluded posts if any, grouped by post type, ordered alphabetically by list title
			$cached_post_type = '';
			$is_first_list = true;
			foreach ( $results as $result ) {
				if ( $result->post_type != $cached_post_type ) {
					// close previously opened list 
					if ( $is_first_list ) {
						$is_first_list = false;
					} else {
						echo "$intendation</ol>\n";
					}
					// get the post type obhect to use its label
					$post_type_object = get_post_type_object( $result->post_type );
					// if the is no object use a default label
					if ( null === $post_type_object ) {
						$label = $translated[ 'unknown post type' ];
					} else {
						$label = $post_type_object->label;
					}
					// print section headline and open a new ordered list
					printf(
						"$intendation<h3>%s</h3>\n$intendation<ol>\n",
						sprintf(
							esc_html__( 'Excluded: %s', 'wp-sitemaps-config' ),
							esc_html__( $label )
						)
					);
					// set this post type as "already processed"
					$cached_post_type = $result->post_type;
				}
				// list the post title with a link to its edit page
				$title = get_the_title( $result->ID );
				if ( ! $title ) {
					$title = sprintf(
						'ID: %d %s',
						$result->ID,
						$translated[ 'no title' ]
					);
				}
				printf(
					"%s\t<li><a href=\"%s\" target=\"_blank\">%s<span class=\"screen-reader-text\"> %s</span></a></li>\n", 
					$intendation,
					get_edit_post_link( $result->ID ), 
					$title,
					$translated[ 'link notice' ]
				);
			} // foreach ( $results as $result )
			echo "$intendation</ol>\n";
		} else {
			printf( "$intendation<p>%s</p>\n", esc_html__( 'Currently no post is excluded from any XML sitemap.', 'wp-sitemaps-config' ) );
		}

		printf( "$intendation<h2>%s</h2>\n", esc_html__( 'How to exclude posts and pages from the XML sitemap?', 'wp-sitemaps-config' ) );
		printf( "$intendation<p>%s</p>\n", sprintf( esc_html__( 'You can add a post you want to exclude from a WordPress XML sitemap easily. Go to the edit page of the post, check the checkbox %s and save the post. That is it.', 'wp-sitemaps-config' ), '<strong>' . $this->exclude_post_label . '</strong>' ) );
		printf(
			"$intendation<figure>%s<figcaption>%s</figcaption></figure>\n",
			sprintf(
				'<img src="%sadmin/images/meta-box.gif" alt="%s" width="266" height="193" />',
				WP_SITEMAPS_CONFIG_URL,
				esc_attr( __( 'Meta box on a post edit page', 'wp-sitemaps-config' ) )
			),
			esc_html__( 'With the meta box on a post edit page you can specify sitemap settings for each post and page.', 'wp-sitemaps-config' ) 
		);
		printf( "$intendation<p>%s</p>\n", sprintf( esc_html__( 'To re-include a post into the XML sitemap, follow the link to´the post edit page, deactivate the checkbox %s and save the post.', 'wp-sitemaps-config' ), '<strong>' . $this->exclude_post_label . '</strong>' ) );
	}
	
	/**
	 * Register the metabox for posts and pages (PRO: for all public post types)
	 *
	 * @since    2.0.0
	 *
	 * @param string $post_type The type of the post 
	 */
	public function add_posts_metabox( $post_type ) {
		// Limit meta box to certain post types.
		$post_types = array( 'post', 'page', 'product' );

		if ( in_array( $post_type, $post_types ) ) {
			$text = 'XML Sitemap';
			add_meta_box(
				'xml_sitemaps_posts_options', // meta box ID
				__( $text ), // title of the meta box
				array( $this, 'display_posts_metabox_content' ), // function that fills the box with the desired content
				$post_type, // the screen or screens on which to show the box
				'side' // context within the screen where the box should display. Post edit screen contexts: 'normal', 'side', and 'advanced'
			);
		}

	}

	/**
	 * Register the metabox content for posts and pages (PRO: for all public post types)
	 *
	 * @since    2.0.0
	 *
     * @param WP_Post $post The post object.
	 */
	public function display_posts_metabox_content( $post ) {
 
        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'wpxmlsitemap_post_box', 'wpxmlsitemap_post_box_nonce' );

        // Use get_post_meta to retrieve an existing value from the database.
        $meta = get_post_meta( $post->ID, WP_SITEMAPS_CONFIG_META_KEY );
		if ( $meta ) {
			$settings = $meta[ 0 ];
		/*} else {
			// if no settings, use defaults
				$settings = array(
					'excluded' => '0',
					'priority' => 0.5,
					'changefreq' => 'weekly',
				);
			}*/
		}
 
        // Display the form, using the current value.
		
		// option for the entry's visibility in the sitemap
		printf(
			'<p><input type="checkbox" id="wpxmlsitemap_excluded" name="wpxmlsitemap_excluded" value="1"%s />
			<label for="wpxmlsitemap_excluded">%s</label><br />
			<em>%s</em></p>',
			checked( isset( $settings[ 'excluded' ] ), true, false ),
			esc_html( $this->exclude_post_label ),
			esc_html__( 'If activated the link to this post is not listed on the XML sitemap. This does not mean that this post is excluded from search engines crawlers.', 'wp-sitemaps-config' )
		);
		
		$label_select = '&mdash; Select &mdash;';

		// option for the change frequency of the post
		if( ! isset( $settings[ 'changefreq' ] ) ) {
			$settings[ 'changefreq' ] = '';
		}
		// build the HTML code
		$html =	sprintf( '<div><label for="wpxmlsitemap_changefreq">%s</label></div><div><select id="wpxmlsitemap_changefreq" name="wpxmlsitemap_changefreq"><option value="">%s</option>',
			esc_html__( 'Change frequency', 'wp-sitemaps-config' ),
			esc_html__( $label_select )
		);

		foreach ( $this->changefreq_options as $value => $label ) {

            if( !empty( $settings[ 'changefreq' ] )) {

                $selected = selected( $value == $settings[ 'changefreq' ], true, false );

            }
            else {

                $selected = selected( 'weekly' == $value, true, false );

            }

            $html .= sprintf(
				'<option value="%s"%s>%s</option>',
				$value,
                $selected,
				esc_html( $label )
			);
		}
		$html .= '</select></div><p><em>' . sprintf( esc_html__( 'How frequently the page is likely to change. This value provides general information to search engines and may not correlate exactly to how often they crawl the page. The value "always" should be used to describe documents that change each time they are accessed. The value "never" should be used to describe archived URLs. Please note that the value of this property is considered a hint and not a command. If nothing is set, the default value of "%s" is used.', 'wp-sitemaps-config' ), esc_html( $this->changefreq_options['weekly'] ) ) . '</em></p>';
		echo $html;

		// option for the priority of the post
		if( ! isset( $settings[ 'priority' ] ) ) {
			$settings[ 'priority' ] = '';
		}
		// build the HTML code
		$html =	sprintf( '<div><label for="wpxmlsitemap_priority">%s</label></div><div><select id="wpxmlsitemap_priority" name="wpxmlsitemap_priority"><option value="">%s</option>',
			esc_html__( 'Priority', 'wp-sitemaps-config' ),
			esc_html__( $label_select )
		);
		foreach ( $this->priority_options as $value => $label ) {

            if( isset( $settings[ 'priority' ] ) && !empty( $settings[ 'priority' ] ) ) {

                $selected = selected( $value == $settings[ 'priority' ], true, false );

            }
            else {

                $selected = selected( 0.5 == $value, true, false );

            }

			$html .= sprintf(
				'<option value="%s"%s>%s</option>',
				$value,
				$selected,
				esc_html( $label )
			);
		}
		$html .= '</select></div><p><em>' . sprintf( esc_html__( 'The priority of this URL relative to other URLs on your site. Valid values range from 0.0 to 1.0. Search engines may use this information when selecting between URLs on the same site. If no value is specified, the default priority of %s is used.', 'wp-sitemaps-config' ), esc_html( $this->priority_options[ '0.5' ] ) ) . '</em></p>';
		echo $html;

    }

	/**
	 * Save the metabox settings when the post is saved
	 *
	 * @since    2.0.0
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save_posts_sitemap_settings( $post_id ) {
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['wpxmlsitemap_post_box_nonce'] ) ) {
            return $post_id;
        }
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['wpxmlsitemap_post_box_nonce'], 'wpxmlsitemap_post_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $settings = array();
		
		// setting for the entry's visibility in the sitemap
		if ( isset( $_POST[ 'wpxmlsitemap_excluded' ] ) && '1' === $_POST[ 'wpxmlsitemap_excluded' ] ) {
			$settings[ 'excluded' ] = '1';
		}

		// setting for the change frequency of the post
		if ( isset( $_POST[ 'wpxmlsitemap_changefreq' ] ) && in_array( $_POST[ 'wpxmlsitemap_changefreq' ], $this->changefreq_keys ) ) {
			$settings[ 'changefreq' ] = $_POST[ 'wpxmlsitemap_changefreq' ];
		}
 
		// setting for the priority of the post
		if ( isset( $_POST[ 'wpxmlsitemap_priority' ] ) ) {
			/*
			if ( 
				filter_var(
					$_POST[ 'wpxmlsitemap_priority' ],
					FILTER_VALIDATE_FLOAT,
					[
						'options' => [ // requires PHP 7.4.0 or above
							'min_range' => 0,
							'max_range' => 1
						]
					]
				) !== false 
			) {
				$value = floatval( $_POST[ 'wpxmlsitemap_priority' ] );
			}
			or:
			$value = filter_var(
				$_POST[ 'wpxmlsitemap_priority' ],
				FILTER_SANITIZE_NUMBER_FLOAT,
				FILTER_FLAG_ALLOW_FRACTION
			);
			*/
			$value = floatval( $_POST[ 'wpxmlsitemap_priority' ] );
			if ( 0 <= $value && 1 >= $value ) {
				$settings[ 'priority' ] = $value;
			}
		}
 
        // Update the meta field if there is data, else remove it
		if ( $settings ) {
			update_post_meta( $post_id, WP_SITEMAPS_CONFIG_META_KEY, $settings );
		} else {
			delete_post_meta( $post_id, WP_SITEMAPS_CONFIG_META_KEY );
		}
		
	}
	
	/**
	 * Returns the public post types, which excludes nav_items and similar types.
	 * Attachments are also excluded. This includes custom post types with public = true.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Post_Type[] Array of registered post type objects keyed by their name.
	 */
	private function get_posts_subtypes() {
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		unset( $post_types['attachment'] );

		return array_filter( $post_types, 'is_post_type_viewable' );

	}

	/**
	 * Returns all public, registered taxonomies.
	 *
	 * @since 1.0.0
	 *
	 * @return WP_Taxonomy[] Array of registered taxonomy objects keyed by their name.
	 */
	private function get_taxonomies_subtypes() {
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );

		return array_filter( $taxonomies, 'is_taxonomy_viewable' );
	}

}
}