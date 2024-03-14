<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewduwcfSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate WooCommerce Filters
 * @since 3.0.0
 */
class ewduwcfSettings {

	/**
	 * Default values for settings
	 * @since 3.0.0
	 */
	public $defaults = array();

	/**
	 * Stored values for settings
	 * @since 3.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );

		if ( isset( $_POST['ewd-uwcf-settings'] ) ) { add_action( 'init', array( $this, 'check_for_wc_color_taxonomy' ) ); }
		if ( isset( $_POST['ewd-uwcf-settings'] ) ) { add_action( 'init', array( $this, 'check_for_wc_size_taxonomy' ) ); }
	}

	/**
	 * Load the plugin's default settings
	 * @since 3.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'access-role'					=> 'manage_options',

			'color-filtering-display'		=> 'list',
			'size-filtering-display'		=> 'list',
			'category-filtering-display'	=> 'list',
			'tag-filtering-display'			=> 'list',
			'price-filtering-display'		=> 'text',

			'styling-color-filter-shape'	=> 'circle',

			'label-product-page-colors'		=> __( 'Colors', 'color-filters' ),
			'label-product-page-sizes'		=> __( 'Sizes', 'color-filters' ),
		);

		$this->defaults = apply_filters( 'ewd_uwcf_defaults', $this->defaults, $this );
	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 3.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-uwcf-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-uwcf-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( ! empty( $this->defaults[ $setting ] ) or isset( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-uwcf-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-uwcf-settings-' . $setting, null );
	}

	/**
	 * Reset the class settings to those in the database
	 * @since 3.0.9
	 */
	public function reset_to_database_settings() { 

		$this->settings = get_option( 'ewd-uwcf-settings' );
	}

	/**
	 * Set a setting to a particular value
	 * @since 3.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 3.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-uwcf-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 3.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {
		global $ewd_uwcf_controller;

		require_once( EWD_UWCF_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.6.13',
				'lib_url'       => EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/',
				'theme'			=> 'purple',
			)
		);

		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-uwcf-settings',
				'title'         => __( 'Settings', 'color-filters' ),
				'menu_title'    => __( 'Settings', 'color-filters' ),
				'parent_menu'	=> 'ewd-uwcf-dashboard',
				'description'   => '',
				'capability'    => $this->get_setting( 'access-role' ),
				'default_tab'   => 'ewd-uwcf-general-tab',
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-general-tab',
				'title'         => __( 'General', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-general',
				'title'         => __( 'General Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-general-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'toggle',
			array(
				'id'			=> 'table-format',
				'title'			=> __( 'Table Format', 'color-filters' ),
				'description'	=> __( 'Table Format lets you display your products in a table rather than a grid format, and adds the requested sorting and filtering options to the table. Once this option is enabled, you\'ll see a new TABLE FORMAT menu item, which is where you can configure the settings for this.', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'textarea',
			array(
				'id'			=> 'custom-css',
				'title'			=> __( 'Custom CSS', 'color-filters' ),
				'description'	=> __( 'You can add custom CSS styles in the box above.', 'color-filters' ),			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'select',
			array(
				'id'            => 'access-role',
				'title'         => __( 'Access Role', 'color-filters' ),
				'description'   => __( 'Who should have access to the \'WC Filters\' admin menu?', 'color-filters' ),
				'blank_option'	=> false,
				'options'       => array(
					'administrator'				=> __( 'Administrator', 'color-filters' ),
					'delete_others_pages'		=> __( 'Editor', 'color-filters' ),
					'delete_published_posts'	=> __( 'Author', 'color-filters' ),
					'delete_posts'				=> __( 'Contributor', 'color-filters' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-general',
			'toggle',
			array(
				'id'			=> 'reset-all-button',
				'title'			=> __( 'Reset All Button', 'color-filters' ),
				'description'	=> __( 'Should a \'Reset All\' button be added to the filters section?', 'color-filters' )
			)
		);

		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'premium' ) ) {
			$ewd_uwcf_premium_permissions = array(
				'disabled'		=> true,
				'disabled_image'=> '#',
				'purchase_link'	=> 'https://www.etoilewebdesign.com/plugins/woocommerce-filters/',
				'section' 		=> 'uwcf-filtering'
			);
		}
		else { $ewd_uwcf_premium_permissions = array(); }

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-filtering-tab',
				'title'         => __( 'Filtering', 'color-filters' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-color-filtering',
				'title'         => __( 'Color Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering',
			'warningtip',
			array(
				'id'			=> 'attributes-reminder',
				'title'			=> __( 'WOOCOMMERCE ATTRIBUTES FILTERING ISSUE:', 'color-filters' ),
				'placeholder'	=> __( 'WooCommerce is currently experiencing an issue with widget attribute filtering. More info can be found <a href="https://github.com/woocommerce/woocommerce/issues/27419" target="_blank">here</a>. If you are affected by the above WooCommerce issue, we suggest turning off attribute filtering for the time being.', 'color-filters' ),
				'type'			=> 'warning'
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering',
			'toggle',
			array(
				'id'			=> 'color-filtering',
				'title'			=> __( 'Enable Color Filtering', 'color-filters' ),
				'description'	=> __( 'Should the color filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-color-filtering-sub',
				'title'         => __( 'Color Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a color\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-show-color',
				'title'			=> __( 'Disable Color', 'color-filters' ),
				'description'	=> __( 'Should a color\'s color swatch be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which colors with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-sub',
			'toggle',
			array(
				'id'			=> 'color-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each color be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-color-filtering-premium',
					'title'         => __( 'Premium Color Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'radio',
			array(
				'id'			=> 'color-filtering-display',
				'title'			=> __( 'Color Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter colors?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'tiles'			=> __( 'Tiles', 'color-filters' ),
					'swatch'		=> __( 'Swatch', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['color-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-display-thumbnail-colors',
				'title'			=> __( 'Display Thumbnail Colors', 'color-filters' ),
				'description'	=> __( 'Should a list of available colors be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-product-page-display',
				'title'			=> __( 'Display on Product Page', 'color-filters' ),
				'description'	=> __( 'Should a product\'s color, if any, be displayed on the product page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-color-filtering-premium',
			'toggle',
			array(
				'id'			=> 'color-filtering-colors-for-variations',
				'title'			=> __( 'Use Color for Variations', 'color-filters' ),
				'description'	=> __( 'Should it be possible to use colors for variations? Save the product for new colors to be shown as options for variations.', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-size-filtering',
				'title'         => __( 'Size Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering',
			'toggle',
			array(
				'id'			=> 'size-filtering',
				'title'			=> __( 'Enable Size Filtering', 'color-filters' ),
				'description'	=> __( 'Should the size filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-size-filtering-sub',
				'title'         => __( 'Size Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a size\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which sizes with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-sub',
			'toggle',
			array(
				'id'			=> 'size-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each size be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-size-filtering-premium',
					'title'         => __( 'Premium Size Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'radio',
			array(
				'id'			=> 'size-filtering-display',
				'title'			=> __( 'Size Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter sizes?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['size-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-display-thumbnail-sizes',
				'title'			=> __( 'Display Thumbnail Sizes', 'color-filters' ),
				'description'	=> __( 'Should a list of available sizes be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-product-page-display',
				'title'			=> __( 'Display on Product Page', 'color-filters' ),
				'description'	=> __( 'Should a product\'s size, if any, be displayed on the product page?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-size-filtering-premium',
			'toggle',
			array(
				'id'			=> 'size-filtering-sizes-for-variations',
				'title'			=> __( 'Use Size for Variations', 'color-filters' ),
				'description'	=> __( 'Should it be possible to use sizes for variations? Save the product for new sizes to be shown as options for variations.', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-category-filtering',
				'title'         => __( 'Category Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering',
			'toggle',
			array(
				'id'			=> 'category-filtering',
				'title'			=> __( 'Enable Category Filtering', 'color-filters' ),
				'description'	=> __( 'Should the category filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-category-filtering-sub',
				'title'         => __( 'Category Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a category\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which categories with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-sub',
			'toggle',
			array(
				'id'			=> 'category-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each category be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-category-filtering-premium',
					'title'         => __( 'Premium Category Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-premium',
			'radio',
			array(
				'id'			=> 'category-filtering-display',
				'title'			=> __( 'Category Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter categories?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['category-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-category-filtering-premium',
			'toggle',
			array(
				'id'			=> 'category-filtering-display-thumbnail-cats',
				'title'			=> __( 'Display Thumbnail Categories', 'color-filters' ),
				'description'	=> __( 'Should a list of available categories be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-tag-filtering',
				'title'         => __( 'Tag Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering',
			'toggle',
			array(
				'id'			=> 'tag-filtering',
				'title'			=> __( 'Enable Tag Filtering', 'color-filters' ),
				'description'	=> __( 'Should the tag filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-tag-filtering-sub',
				'title'         => __( 'Tag Filtering Sub Options', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-disable-text',
				'title'			=> __( 'Disable Text', 'color-filters' ),
				'description'	=> __( 'Should a tag\'s name be hidden in the filtering box?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-hide-empty',
				'title'			=> __( 'Hide Empty', 'color-filters' ),
				'description'	=> __( 'Which tags with no associated products be hidden?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-sub',
			'toggle',
			array(
				'id'			=> 'tag-filtering-show-product-count',
				'title'			=> __( 'Show Product Count', 'color-filters' ),
				'description'	=> __( 'Should the number of products for each tag be displayed?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array_merge(
				array(
					'id'            => 'ewd-uwcf-tag-filtering-premium',
					'title'         => __( 'Premium Tag Filtering', 'color-filters' ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				),
				$ewd_uwcf_premium_permissions
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-premium',
			'radio',
			array(
				'id'			=> 'tag-filtering-display',
				'title'			=> __( 'Tag Filter Layout', 'color-filters' ),
				'description'	=> 'Which type of display should be used for filter tags?',
				'options'		=> array(
					'list'			=> __( 'List', 'color-filters' ),
					'checklist'		=> __( 'Checklist', 'color-filters' ),
					'dropdown'		=> __( 'Dropdown', 'color-filters' ),
				),
				'default'		=> $this->defaults['tag-filtering-display']
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-tag-filtering-premium',
			'toggle',
			array(
				'id'			=> 'tag-filtering-display-thumbnail-tags',
				'title'			=> __( 'Display Thumbnail Tags', 'color-filters' ),
				'description'	=> __( 'Should a list of available tags be shown under each product thumbnail on the shop page?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-text-search',
				'title'         => __( 'Text Search', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-text-search',
			'toggle',
			array(
				'id'			=> 'text-search',
				'title'			=> __( 'Enable Text Search', 'color-filters' ),
				'description'	=> __( 'Should a text search box be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-text-search',
			'toggle',
			array(
				'id'			=> 'text-search-autocomplete',
				'title'			=> __( 'Enable Product Title Autocomplete', 'color-filters' ),
				'description'	=> __( 'If text search is enabled, should a list of matching products be displayed when a user starts typing?', 'color-filters' ),
				'conditional_on'		=> 'text-search',
				'conditional_on_value'	=> true
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-price-filtering',
				'title'         => __( 'Price Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-price-filtering',
			'toggle',
			array(
				'id'			=> 'price-filtering',
				'title'			=> __( 'Enable Price Filtering', 'color-filters' ),
				'description'	=> __( 'Should visitors be able to filter products based on price?', 'color-filters' )
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-price-filtering',
			'radio',
			array(
				'id'			=> 'price-filtering-display',
				'title'			=> __( 'Price Filter Control', 'color-filters' ),
				'description'	=> 'Which type of control should be used for filtering products based on price?',
				'options'		=> array(
					'text'			=> __( 'Text', 'color-filters' ),
					'slider'		=> __( 'Slider', 'color-filters' ),
				),
				'default'		=> $this->defaults['price-filtering-display'],
				'conditional_on'		=> 'price-filtering',
				'conditional_on_value'	=> true
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-rating-filtering',
				'title'         => __( 'Ratings Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-rating-filtering',
			'toggle',
			array(
				'id'			=> 'rating-filtering',
				'title'			=> __( 'Enable Ratings Filtering', 'color-filters' ),
				'description'	=> __( 'Should a slider be added to filter products by rating?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-instock-filtering',
				'title'         => __( 'In-Stock Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-instock-filtering',
			'toggle',
			array(
				'id'			=> 'instock-filtering',
				'title'			=> __( 'Enable In-Stock Filtering', 'color-filters' ),
				'description'	=> __( 'Should an in-stock toggle be added to the filtering widget?', 'color-filters' )
			)
		);

		$sap->add_section(
			'ewd-uwcf-settings',
			array(
				'id'            => 'ewd-uwcf-onsale-filtering',
				'title'         => __( 'On-Sale Filtering', 'color-filters' ),
				'tab'	        => 'ewd-uwcf-filtering-tab',
			)
		);

		$sap->add_setting(
			'ewd-uwcf-settings',
			'ewd-uwcf-onsale-filtering',
			'toggle',
			array(
				'id'			=> 'onsale-filtering',
				'title'			=> __( 'Enable On-Sale Filtering', 'color-filters' ),
				'description'	=> __( 'Should an on-sale toggle be added to the filtering widget?', 'color-filters' )
			)
		);

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			if ( $attribute_taxonomy->attribute_name == 'ewd_uwcf_colors' or $attribute_taxonomy->attribute_name == 'ewd_uwcf_sizes' ) { continue; }

    		$sap->add_section(
				'ewd-uwcf-settings',
				array(
					'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering',
					'title'         => sprintf( __( '%s Attribute Filtering', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-filtering',
					'title'			=> sprintf( __( 'Enable %s Filtering', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'description'	=> sprintf( __( 'Should the %s filters be displayed when the plugin\'s widget or shortcode is used?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);

			$sap->add_section(
				'ewd-uwcf-settings',
				array(
					'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
					'title'         => sprintf( __( '%s Attribute Filtering Sub Options', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'tab'	        => 'ewd-uwcf-filtering-tab',
				)
			);
	
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-disable-text',
					'title'			=> __( 'Disable Text', 'color-filters' ),
					'description'	=> sprintf( __( 'Should a %s\'s name be hidden in the filtering box?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-hide-empty',
					'title'			=> __( 'Hide Empty', 'color-filters' ),
					'description'	=> sprintf(  __( 'Should %ss with no associated products be hidden?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-sub',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-show-product-count',
					'title'			=> __( 'Show Product Count', 'color-filters' ),
					'description'	=> sprintf( __( 'Should the number of products for each %s be displayed?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) )
				)
			);

			$sap->add_section(
				'ewd-uwcf-settings',
				array_merge(
					array(
						'id'            => 'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
						'title'         => sprintf( __( '%s Attribute Filtering Premium', 'color-filters' ), $attribute_taxonomy->attribute_label ),
						'tab'	        => 'ewd-uwcf-filtering-tab',
					),
					$ewd_uwcf_premium_permissions
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
				'radio',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-display',
					'title'			=> sprintf( __( '%s Filter Layout', 'color-filters' ), $attribute_taxonomy->attribute_label ),
					'description'	=> sprintf( __( 'Which type of display should be used for filter %ss?', 'color-filters' ), strtolower( $attribute_taxonomy->attribute_label ) ),
					'options'		=> array(
						'list'			=> __( 'List', 'color-filters' ),
						'checklist'		=> __( 'Checklist', 'color-filters' ),
						'dropdown'		=> __( 'Dropdown', 'color-filters' ),
					)
				)
			);
	
			$sap->add_setting(
				'ewd-uwcf-settings',
				'ewd-uwcf-' . $attribute_taxonomy->attribute_name . '-filtering-premium',
				'toggle',
				array(
					'id'			=> $attribute_taxonomy->attribute_name . '-display-thumbnail-terms',
					'title'			=> __( 'Display Thumbnail Terms', 'color-filters' ),
					'description'	=> __( 'Should a list of available terms be shown under each product thumbnail on the shop page?', 'color-filters' )
				)
			);
    	}

	    /**
	     * Premium options preview only
	     */

	    // "Scheduling" Tab
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'     => 'ewd-uwcf-scheduling-tab',
	        'title'  => __( 'Scheduling', 'color-filters' ),
	        'is_tab' => true,
	        'show_submit_button' => $this->show_submit_button( 'scheduling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'       => 'ewd-uwcf-scheduling-tab-body',
	        'tab'      => 'ewd-uwcf-scheduling-tab',
	        'callback' => $this->premium_info( 'scheduling' )
	      )
	    );
	
	    // "Labelling" Tab
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'     => 'ewd-uwcf-labelling-tab',
	        'title'  => __( 'Labelling', 'color-filters' ),
	        'is_tab' => true,
	        'show_submit_button' => $this->show_submit_button( 'labelling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'       => 'ewd-uwcf-labelling-tab-body',
	        'tab'      => 'ewd-uwcf-labelling-tab',
	        'callback' => $this->premium_info( 'labelling' )
	      )
	    );
	
	    // "Styling" Tab
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'     => 'ewd-uwcf-styling-tab',
	        'title'  => __( 'Styling', 'color-filters' ),
	        'is_tab' => true,
	        'show_submit_button' => $this->show_submit_button( 'styling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-uwcf-settings',
	      array(
	        'id'       => 'ewd-uwcf-styling-tab-body',
	        'tab'      => 'ewd-uwcf-styling-tab',
	        'callback' => $this->premium_info( 'styling' )
	      )
	    );

		$sap = apply_filters( 'ewd_uwcf_settings_page', $sap, $this );

		$sap->add_admin_menus();

	}

	public function show_submit_button( $permission_type = '' ) {
		global $ewd_uwcf_controller;

		if ( $ewd_uwcf_controller->permissions->check_permission( $permission_type ) ) {
			return true;
		}

		return false;
	}

	public function premium_info( $section_and_perm_type ) {
		global $ewd_uwcf_controller;

		$is_premium_user = $ewd_uwcf_controller->permissions->check_permission( $section_and_perm_type );
		$is_helper_installed = defined( 'EWDPH_PLUGIN_FNAME' ) && is_plugin_active( EWDPH_PLUGIN_FNAME );

		if ( $is_premium_user || $is_helper_installed ) {
			return false;
		}

		$content = '';

		$premium_features = '
			<p><strong>' . __( 'The premium version also gives you access to the following features:', 'color-filters' ) . '</strong></p>
			<ul class="ewd-uwcf-dashboard-new-footer-one-benefits">
				<li>' . __( 'Multiple Filter Layouts', 'color-filters' ) . '</li>
				<li>' . __( 'Attribute Variations', 'color-filters' ) . '</li>
				<li>' . __( 'Display Attributes on Product Page', 'color-filters' ) . '</li>
				<li>' . __( 'Advanced Styling Options', 'color-filters' ) . '</li>
				<li>' . __( 'Advanced Labelling Options', 'color-filters' ) . '</li>
				<li>' . __( 'Email Support', 'color-filters' ) . '</li>
			</ul>
			<div class="ewd-uwcf-dashboard-new-footer-one-buttons">
				<a class="ewd-uwcf-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1&utm_source=uwcf_settings&utm_content=' . $section_and_perm_type . '" target="_blank">' . __( 'UPGRADE NOW', 'color-filters' ) . '</a>
			</div>
		';

		switch ( $section_and_perm_type ) {

			case 'scheduling':

				$content = '
					<div class="ewd-uwcf-settings-preview">
						<h2>' . __( 'Scheduling', 'color-filters' ) . '<span>' . __( 'Premium', 'color-filters' ) . '</span></h2>
						<p>' . __( 'The scheduling options let you set hours when online ordering should be available. You can create weekly schedules, exceptions to that schedule, or toggle off ordering if you\'re overwhelemed.', 'color-filters' ) . '</p>
						<div class="ewd-uwcf-settings-preview-images">
							<img src="' . EWD_UWCF_PLUGIN_URL . '/assets/img/premium-screenshots/scheduling1.png" alt="UWCF scheduling screenshot one">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'labelling':

				$content = '
					<div class="ewd-uwcf-settings-preview">
						<h2>' . __( 'Labelling', 'color-filters' ) . '<span>' . __( 'Premium', 'color-filters' ) . '</span></h2>
						<p>' . __( 'The labelling options let you change the wording of the different labels that appear on the front end of the plugin. You can use this to translate them, customize the wording for your purpose, etc.', 'color-filters' ) . '</p>
						<div class="ewd-uwcf-settings-preview-images">
							<img src="' . EWD_UWCF_PLUGIN_URL . '/assets/img/premium-screenshots/labelling1.png" alt="UWCF labelling screenshot one">
							<img src="' . EWD_UWCF_PLUGIN_URL . '/assets/img/premium-screenshots/labelling2.png" alt="UWCF labelling screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'styling':

				$content = '
					<div class="ewd-uwcf-settings-preview">
						<h2>' . __( 'Styling', 'color-filters' ) . '<span>' . __( 'Premium', 'color-filters' ) . '</span></h2>
						<p>' . __( 'The styling options let you modify the shape and width used for the color filter swatch, as well as set the colors and font sizes for the various elements found in the filtering widget/sidebar.', 'color-filters' ) . '</p>
						<div class="ewd-uwcf-settings-preview-images">
							<img src="' . EWD_UWCF_PLUGIN_URL . '/assets/img/premium-screenshots/styling1.png" alt="UWCF styling screenshot one">
							<img src="' . EWD_UWCF_PLUGIN_URL . '/assets/img/premium-screenshots/styling2.png" alt="UWCF styling screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;
		}

		return function() use ( $content ) {

			echo wp_kses_post( $content );
		};
	}

	public function get_fields() {

		$fields = array(
			'name', 
			'image', 
			'price', 
			'rating', 
			'add_to_cart', 
			'colors', 
			'sizes'
		);

		foreach ( ewd_uwcf_get_woocommerce_taxonomies() as $attribute_taxonomy ) {

			$fields[] = $attribute_taxonomy->attribute_name;
		} 

		return $fields;
	}

	/**
	 * Check if the color attribute for WC has been created, and create it if not
	 * @since 3.0.0
	 */
	public function check_for_wc_color_taxonomy() {
		global $wpdb;

		$wc_attribute_table_name = $wpdb->prefix . 'woocommerce_attribute_taxonomies';

		if ( $wpdb->get_var( $wpdb->prepare( "SELECT attribute_name FROM $wc_attribute_table_name WHERE attribute_name=%s", 'ewd_uwcf_colors' ) ) ) { return; }

		if ( ! $this->get_setting( 'color-filtering' ) ) { return; }

    	$wpdb->insert(
    		$wc_attribute_table_name,
    		array(
    			'attribute_name' => 'ewd_uwcf_colors',
    			'attribute_label' => 'UWCF Colors',
    			'attribute_type' => 'select',
    			'attribute_orderby' => 'menu_order',
    			'attribute_public' => 0
    		)
    	);

    	$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM $wc_attribute_table_name order by attribute_name ASC;" );
		set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );
	}

	/**
	 * Check if the size attribute for WC has been created, and create it if not
	 * @since 3.0.0
	 */
	public function check_for_wc_size_taxonomy() {
		global $wpdb;

		$wc_attribute_table_name = $wpdb->prefix . 'woocommerce_attribute_taxonomies';

		if ( $wpdb->get_var( $wpdb->prepare( "SELECT attribute_name FROM $wc_attribute_table_name WHERE attribute_name=%s", 'ewd_uwcf_sizes' ) ) ) { return; }

		if ( ! $this->get_setting( 'size-filtering' ) ) { return; }

    	$wpdb->insert(
    		$wc_attribute_table_name,
    		array(
    			'attribute_name' => 'ewd_uwcf_sizes',
    			'attribute_label' => 'UWCF Sizes',
    			'attribute_type' => 'select',
    			'attribute_orderby' => 'menu_order',
    			'attribute_public' => 0
    		)
    	);

    	$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM $wc_attribute_table_name order by attribute_name ASC;" );
		set_transient( 'wc_attribute_taxonomies', $attribute_taxonomies );
	}
}
} // endif;
