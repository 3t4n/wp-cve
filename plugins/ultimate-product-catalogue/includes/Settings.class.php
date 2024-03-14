<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'ewdupcpSettings' ) ) {
/**
 * Class to handle configurable settings for Ultimate Product Catalog
 * @since 5.0.0
 */
class ewdupcpSettings {

	/**
	 * Default values for settings
	 * @since 5.0.0
	 */
	public $defaults = array();

	public $email_options = array();

	/**
	 * Stored values for settings
	 * @since 5.0.0
	 */
	public $settings = array();

	public function __construct() {

		add_action( 'init', array( $this, 'set_defaults' ) );

		add_action( 'init', array( $this, 'set_field_options' ) );

		add_action( 'init', array( $this, 'load_settings_panel' ) );

		if ( ! empty( $_POST['ewd-upcp-settings']['product-inquiry-form'] ) or ! empty( $_POST['ewd-upcp-settings']['product-inquiry-cart'] ) ) { 
			
			add_action( 'init', array( $this, 'create_product_inquiry_form' ), 11 );
		}

		if ( ! empty( $_POST['ewd-upcp-settings']['access-role'] ) ) { 
			
			add_action( 'init', array( $this, 'manage_user_capabilities' ), 11 );
		}
	}

	/**
	 * Load the plugin's default settings
	 * @since 5.0.0
	 */
	public function set_defaults() {

		$this->defaults = array(

			'currency-symbol-location'			=> 'before',
			'sale-mode'							=> 'individual',
			'color-scheme'						=> 'black',
			'sidebar-layout'					=> 'normal',
			'tag-logic'							=> 'or',
			'show-catalog-information'			=> array(),
			'overview-mode'						=> 'none',
			'access-role'						=> 'manage_options',
			'social-media-links'				=> array(),
			'display-category-image'			=> array(),
			'breadcrumbs'						=> array(),
			'extra-elements'					=> array(),

			'product-page'						=> 'default',
			'product-image-lightbox'			=> 'no',
			'related-products'					=> 'none',
			'next-previous-products'			=> 'none',
			'pagination-location'				=> 'top',
			'product-inquiry-plugin'			=> 'wpforms',
			'products-per-page'					=> 100,
			'product-search'					=> array(),

			'woocommerce-cart-page'				=> 'cart',

			'seo-plugin'						=> 'none',
			'seo-integration'					=> 'add',
			'seo-title'							=> '[page-title] | [product-name]',
			'permalink-base'					=> 'product',
			'product-page-permalink-base'		=> 'upcp-product',

			'label-back-to-catalog'				=> __( 'Back to Catalog', 'ultimate-product-catalogue' ),
			'label-updating-results'			=> __( 'Updating Results...', 'ultimate-product-catalogue' ),
			'label-no-results-found'			=> __( 'No Results Found', 'ultimate-product-catalogue' ),
			'label-compare'						=> __( 'Compare', 'ultimate-product-catalogue' ),
			'label-side-by-side'				=> __( 'side by side', 'ultimate-product-catalogue' ),

			'styling-catalog-skin'							=> 'default',
			'styling-category-heading-style'				=> 'normal',
			'styling-number-of-columns'						=> 'four',
			'styling-list-view-click-action'				=> 'product',
			'styling-sidebar-title-hover'					=> 'none',
			'styling-sidebar-checkbox-style'				=> 'none',
			'styling-sidebar-categories-control-type'		=> 'checkbox',
			'styling-sidebar-subcategories-control-type'	=> 'checkbox',
			'styling-sidebar-tags-control-type'				=> 'checkbox',

			'styling-sidebar-items-order'		=> json_encode( 
				array(
					'sort'							=> 'Sort By',
					'search'						=> 'Product Search',
					'price_filter'					=> 'Price Filtering',
					'categories'					=> 'Categories',
					'subcategories'					=> 'Sub-Categories',
					'tags'							=> 'Tags',
					'custom_fields'					=> 'Custom Fields',
				)
			),
		);

		$this->defaults = apply_filters( 'ewd_upcp_defaults', $this->defaults, $this );
	}

	/**
	 * Put all of the available possible select options into key => value arrays
	 * @since 5.0.0
	 */
	public function set_field_options() {
		global $ewd_upcp_controller;


	}

	/**
	 * Get a setting's value or fallback to a default if one exists
	 * @since 5.0.0
	 */
	public function get_setting( $setting ) { 

		if ( empty( $this->settings ) ) {
			$this->settings = get_option( 'ewd-upcp-settings' );
		}
		
		if ( ! empty( $this->settings[ $setting ] ) ) {
			return apply_filters( 'ewd-upcp-settings-' . $setting, $this->settings[ $setting ] );
		}

		if ( isset( $this->defaults[ $setting ] ) ) { 
			return apply_filters( 'ewd-upcp-settings-' . $setting, $this->defaults[ $setting ] );
		}

		return apply_filters( 'ewd-upcp-settings-' . $setting, null );
	}

	/**
	 * Set a setting to a particular value
	 * @since 5.0.0
	 */
	public function set_setting( $setting, $value ) {

		$this->settings[ $setting ] = $value;
	}

	/**
	 * Save all settings, to be used with set_setting
	 * @since 5.0.0
	 */
	public function save_settings() {
		
		update_option( 'ewd-upcp-settings', $this->settings );
	}

	/**
	 * Load the admin settings page
	 * @since 5.0.0
	 * @sa https://github.com/NateWr/simple-admin-pages
	 */
	public function load_settings_panel() {

		global $ewd_upcp_controller;

		require_once( EWD_UPCP_PLUGIN_DIR . '/lib/simple-admin-pages/simple-admin-pages.php' );
		$sap = sap_initialize_library(
			$args = array(
				'version'       => '2.6.13',
				'lib_url'       => EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/',
				'theme'			=> 'purple',
			)
		);
		
		$sap->add_page(
			'submenu',
			array(
				'id'            => 'ewd-upcp-settings',
				'title'         => __( 'Settings', 'ultimate-product-catalogue' ),
				'menu_title'    => __( 'Settings', 'ultimate-product-catalogue' ),
				'parent_menu'	=> 'edit.php?post_type=upcp_product',
				'description'   => '',
				'capability'    => $this->get_setting( 'access-role' ),
				'default_tab'   => 'ewd-upcp-basic-tab',
			)
		);

		$sap->add_section(
			'ewd-upcp-settings',
			array(
				'id'            => 'ewd-upcp-basic-tab',
				'title'         => __( 'Basic', 'ultimate-product-catalogue' ),
				'is_tab'		=> true,
				'tutorial_yt_id'	=> '4OCSpwLbmWU'
			)
		);

		$sap->add_section(
			'ewd-upcp-settings',
			array(
				'id'            => 'ewd-upcp-general',
				'title'         => __( 'General', 'ultimate-product-catalogue' ),
				'tab'	        => 'ewd-upcp-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'text',
			array(
				'id'            => 'currency-symbol',
				'title'         => __( 'Currency Symbol', 'ultimate-product-catalogue' ),
				'description'	=> __( 'What currency symbol, if any, should be displayed before or after the price? Leave blank for none.', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'radio',
			array(
				'id'			=> 'currency-symbol-location',
				'title'			=> __( 'Currency Symbol Location', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the currency symbol, if selected, be displayed before or after the price?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'before'		=> __( 'Before', 'ultimate-product-catalogue' ),
					'after'			=> __( 'After', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['currency-symbol-location']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'radio',
			array(
				'id'			=> 'sale-mode',
				'title'			=> __( 'Sale Mode', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should all products be put on sale (\'All\'), no products be on sale (\'None\'), or sale prices be shown only for selected products (\'Individual\')?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'all'			=> __( 'All', 'ultimate-product-catalogue' ),
					'individual'	=> __( 'Individual', 'ultimate-product-catalogue' ),
					'none'			=> __( 'None', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['sale-mode']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'toggle',
			array(
				'id'			=> 'thumbnail-support',
				'title'			=> __( 'Thumbnail Support', 'ultimate-product-catalogue' ),
				'description'	=> __( 'If available, should thumbnail version of images be used on the main catalog pages?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'toggle',
			array(
				'id'			=> 'maintain-filtering',
				'title'			=> __( 'Maintain Filtering', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should filtering be maintained when clicking the back button after viewing a product page? (May cause redirect issues if catalog is placed on homepage.)', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'checkbox',
			array(
				'id'            => 'social-media-links',
				'title'         => __( 'Social Media Options', 'ultimate-product-catalogue' ),
				'description'   => __( 'Which social media links should be displayed on the product page?', 'ultimate-product-catalogue' ), 
				'options'       => array(
					'facebook'		=> __( 'Facebook', 'ultimate-product-catalogue' ),
					'twitter'		=> __( 'Twitter', 'ultimate-product-catalogue' ),
					'linkedin'		=> __( 'Linkedin', 'ultimate-product-catalogue' ),
					'pinterest'		=> __( 'Pinterest', 'ultimate-product-catalogue' ),
					'email'			=> __( 'Email', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-general',
			'select',
			array(
				'id'            => 'access-role',
				'title'         => __( 'Set Access Role', 'ultimate-product-catalogue' ),
				'description'   => __( 'Who should have access to the "Ultimate Product Catalog" admin menu? (Roles of contributor or higher will still be able to see the Products/Catalogs/Categories/Tags menus, but will not be able to edit the items [similar to how it works for the default post types in WordPress]).', 'ultimate-product-catalogue' ), 
				'blank_option'	=> false,
				'options'       => array(
					'administrator'				=> __( 'Administrator', 'ultimate-product-catalogue' ),
					'delete_others_pages'		=> __( 'Editor', 'ultimate-product-catalogue' ),
					'delete_published_posts'	=> __( 'Author', 'ultimate-product-catalogue' ),
					'delete_posts'				=> __( 'Contributor', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_section(
			'ewd-upcp-settings',
			array(
				'id'            => 'ewd-upcp-basic-catalog-page',
				'title'         => __( 'Catalog Page Display', 'ultimate-product-catalogue' ),
				'tab'	        => 'ewd-upcp-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'radio',
			array(
				'id'			=> 'color-scheme',
				'title'			=> __( 'Catalog Color', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Set the color of the image and border elements', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'blue'			=> __( 'Blue', 'ultimate-product-catalogue' ),
					'black'			=> __( 'Black', 'ultimate-product-catalogue' ),
					'grey'			=> __( 'Grey', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['color-scheme']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'disable-thumbnail-auto-adjust',
				'title'			=> __( 'Disable Auto-Adjust Thumbnail Heights', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the auto-adjust of the product thumbnails heights to the height of the longest product be disabled? This prevents lines with odd numbers of products, products not starting on the left, etc.', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'radio',
			array(
				'id'			=> 'sidebar-layout',
				'title'			=> __( 'Sub-Category Style', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should categories and sub-categories be arranged hierarchically or be grouped?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'normal'		=> __( 'Normal', 'ultimate-product-catalogue' ),
					'hierarchical'	=> __( 'Hierarchical', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['sidebar-layout']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'details-read-more',
				'title'			=> __( 'Read More', 'ultimate-product-catalogue' ),
				'description'	=> __( 'In the \'Details\' layout, should the product description be cutoff if it\'s long?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'text',
			array(
				'id'            => 'details-description-characters',
				'title'         => __( 'Characters in Details Description', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Set maximum number of characters in product description in the \'Details\' layout', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'checkbox',
			array(
				'id'			=> 'show-catalog-information',
				'title'			=> __( 'Show Catalog Information', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the name or description of the catalog be shown above the catalog?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'name'			=> __( 'Name', 'ultimate-product-catalogue' ),
					'description'	=> __( 'Description', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'show-category-descriptions',
				'title'			=> __( 'Show Category Descriptions', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the descriptions of product categories be shown below them?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'checkbox',
			array(
				'id'			=> 'display-category-image',
				'title'			=> __( 'Display Category Image', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the category image be displayed on the main catalog page?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'sidebar'		=> __( 'Sidebar', 'ultimate-product-catalogue' ),
					'main'			=> __( 'Main', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'display-subcategory-image',
				'title'			=> __( 'Display Sub-Category Image', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the sub-category image be displayed in the sidebar on the main catalog page?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'display-categories-in-product-thumbnail',
				'title'			=> __( 'Display Categories in Thumbnails', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the category and sub-category associated with a product be displayed in the product listing on the catalog page?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-catalog-page',
			'toggle',
			array(
				'id'			=> 'display-tags-in-product-thumbnail',
				'title'			=> __( 'Display Tags in Thumbnails', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should the tags associated with a product be displayed in the product listing on the catalog page?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_section(
			'ewd-upcp-settings',
			array(
				'id'            => 'ewd-upcp-catalog-page-functionality',
				'title'         => __( 'Catalog Page Functionality', 'ultimate-product-catalogue' ),
				'tab'	        => 'ewd-upcp-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'product-links',
				'title'			=> __( 'Product Links', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should external product links open in a new window?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'radio',
			array(
				'id'			=> 'tag-logic',
				'title'			=> __( 'Tag Logic', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Gives users the option to use multiple tags at the same time in filtering (\'OR\' option)', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'and'			=> __( 'AND', 'ultimate-product-catalogue' ),
					'or'			=> __( 'OR', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['tag-logic']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'disable-price-filter',
				'title'			=> __( 'Disable Price Filtering', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should price filtering be hidden from the catalog sidebar?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'disable-slider-filter-text-inputs',
				'title'			=> __( 'Disable Slider Filter Text Inputs', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should slider filter text inputs be disabled, preventing users from adjusting the min/max values by text?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'radio',
			array(
				'id'			=> 'overview-mode',
				'title'			=> __( 'Catalog Overview Mode', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should visitors see an overview of the categories instead of all products when the page first loads?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'full'			=> __( 'Categories and Sub-Categories', 'ultimate-product-catalogue' ),
					'cats'			=> __( 'Categories Only', 'ultimate-product-catalogue' ),
					'none'			=> __( 'None', 'ultimate-product-catalogue' ),
				),
				'default'		=> $this->defaults['overview-mode']
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'checkbox',
			array(
				'id'			=> 'product-search',
				'title'			=> __( 'Product Search', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Select which portions of a product should be searched when using the text search box? Custom fields search can take significantly longer to return results.', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'name'			=> __( 'Name', 'ultimate-product-catalogue' ),
					'description'	=> __( 'Description', 'ultimate-product-catalogue' ),
					'custom_fields'	=> __( 'Custom Fields', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'product-search-without-accents',
				'title'			=> __( 'Search without Accents', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Lets you search without having to include accents on letters (e.g. rose will also return rosé).', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'clear-all-filtering',
				'title'			=> __( '\'Clear All\' Option', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should an option be added to the top of sidebar to clear all filtering options?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-catalog-page-functionality',
			'toggle',
			array(
				'id'			=> 'hide-empty-options-filtering',
				'title'			=> __( 'Hide Empty Filtering Options', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should filtering options that would no longer display any results be hidden?', 'ultimate-product-catalogue' )
			)
		);

		$sap->add_section(
			'ewd-upcp-settings',
			array(
				'id'            => 'ewd-upcp-basic-product-page',
				'title'         => __( 'Product Page', 'ultimate-product-catalogue' ),
				'tab'	        => 'ewd-upcp-basic-tab',
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-product-page',
			'checkbox',
			array(
				'id'			=> 'breadcrumbs',
				'title'			=> __( 'Breadcrumbs', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Which breadcrumbs, if any, should display on the product page?', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'catalog'		=> __( 'Catalog', 'ultimate-product-catalogue' ),
					'categories'	=> __( 'Categories', 'ultimate-product-catalogue' ),
					'subcategories'	=> __( 'Sub-Categories', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-product-page',
			'checkbox',
			array(
				'id'			=> 'extra-elements',
				'title'			=> __( 'Extra Elements', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Select elements to be displayed on each product page.', 'ultimate-product-catalogue' ),
				'options'		=> array(
					'category'		=> __( 'Category Name(s)', 'ultimate-product-catalogue' ),
					'subcategory'	=> __( 'Sub-Category Name(s)', 'ultimate-product-catalogue' ),
					'tags'			=> __( 'Tags', 'ultimate-product-catalogue' ),
					'customfields'	=> __( 'Custom Fields', 'ultimate-product-catalogue' ),
					'videos'		=> __( 'Videos', 'ultimate-product-catalogue' ),
				)
			)
		);

		$sap->add_setting(
			'ewd-upcp-settings',
			'ewd-upcp-basic-product-page',
			'toggle',
			array(
				'id'			=> 'disable-product-page-price',
				'title'			=> __( 'Disable Product Page Price', 'ultimate-product-catalogue' ),
				'description'	=> __( 'Should a product\'s price be hidden on the product pages?', 'ultimate-product-catalogue' )
			)
		);		

		/**
	     * Premium options preview only
	     */
	    // "Premium" Tab
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'     => 'ewd-upcp-premium-tab',
	        'title'  => __( 'Premium', 'ultimate-product-catalogue' ),
	        'is_tab' => true,
			'tutorial_yt_id'	=> '4WpT_62jK6g',
	        'show_submit_button' => $this->show_submit_button( 'premium' )
	      )
	    );
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'       => 'ewd-upcp-premium-tab-body',
	        'tab'      => 'ewd-upcp-premium-tab',
	        'callback' => $this->premium_info( 'premium' )
	      )
	    );
	
	    // "WooCommerce" Tab
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'     => 'ewd-upcp-woocommerce-tab',
	        'title'  => __( 'WooCommerce', 'ultimate-product-catalogue' ),
	        'is_tab' => true,
			'tutorial_yt_id'	=> 'EbO_8lWApJk',
	        'show_submit_button' => $this->show_submit_button( 'woocommerce' )
	      )
	    );
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'       => 'ewd-upcp-woocommerce-tab-body',
	        'tab'      => 'ewd-upcp-woocommerce-tab',
	        'callback' => $this->premium_info( 'woocommerce' )
	      )
	    );
	
	    // "WooCommerce" Tab
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'     => 'ewd-upcp-seo-tab',
	        'title'  => __( 'SEO', 'ultimate-product-catalogue' ),
	        'is_tab' => true,
			'tutorial_yt_id'	=> 'HnFkn3SsxPs',
	        'show_submit_button' => $this->show_submit_button( 'seo' )
	      )
	    );
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'       => 'ewd-upcp-seo-tab-body',
	        'tab'      => 'ewd-upcp-seo-tab',
	        'callback' => $this->premium_info( 'seo' )
	      )
	    );
	
	    // "Labelling" Tab
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'     => 'ewd-upcp-labelling-tab',
	        'title'  => __( 'Labelling', 'ultimate-product-catalogue' ),
	        'is_tab' => true,
			'tutorial_yt_id'	=> '9EPwmF_TtvI',
	        'show_submit_button' => $this->show_submit_button( 'labelling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'       => 'ewd-upcp-labelling-tab-body',
	        'tab'      => 'ewd-upcp-labelling-tab',
	        'callback' => $this->premium_info( 'labelling' )
	      )
	    );
	
	    // "Styling" Tab
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'     => 'ewd-upcp-styling-tab',
	        'title'  => __( 'Styling', 'ultimate-product-catalogue' ),
	        'is_tab' => true,
			'tutorial_yt_id'	=> 'lEljqjzWDFA',
	        'show_submit_button' => $this->show_submit_button( 'styling' )
	      )
	    );
	    $sap->add_section(
	      'ewd-upcp-settings',
	      array(
	        'id'       => 'ewd-upcp-styling-tab-body',
	        'tab'      => 'ewd-upcp-styling-tab',
	        'callback' => $this->premium_info( 'styling' )
	      )
	    );

		$sap = apply_filters( 'ewd_upcp_settings_page', $sap, $this );

		$sap->add_admin_menus();

	}

	/**
	 * Return existing custom fields
	 * @since 5.0.0
	 */
	public function get_custom_fields() {

		if ( ! isset( $this->custom_fields ) ) {

			$this->custom_fields = is_array( get_option( 'ewd-upcp-custom-fields' ) ) ? get_option( 'ewd-upcp-custom-fields' ) : array();
		}

		return $this->custom_fields;
	}

	/**
	 * Sets new value for the custom fields option
	 * @since 5.0.0
	 */
	public function update_custom_fields( $custom_fields ) {

		$custom_fields = is_array( $custom_fields ) ? $custom_fields : array();

		$this->custom_fields = $custom_fields;

		update_option( 'ewd-upcp-custom-fields', $custom_fields );
	}

	/**
	 * Adds/removes the product editing capabilities as necessary
	 * @since 5.0.0
	 */
	public function manage_user_capabilities() {
		global $ewd_upcp_controller;

		$manage_products_roles = array(
			'administrator',
		);

		$remove_product_roles = array();

		if ( $this->get_setting( 'access-role' ) == 'administrator' ) {

			$remove_product_roles[] = 'editor';
			$remove_product_roles[] = 'author';
			$remove_product_roles[] = 'contributor';
		}
		elseif ( $this->get_setting( 'access-role' ) == 'delete_others_pages' ) {

			$manage_products_roles[] = 'editor';

			$remove_product_roles[] = 'author';
			$remove_product_roles[] = 'contributor';
		}
		elseif ( $this->get_setting( 'access-role' ) == 'delete_published_posts' ) {

			$manage_products_roles[] = 'editor';
			$manage_products_roles[] = 'author';

			$remove_product_roles[] = 'contributor';
		}
		elseif ( $this->get_setting( 'access-role' ) == 'delete_posts' ) {

			$manage_products_roles[] = 'editor';
			$manage_products_roles[] = 'author';
			$manage_products_roles[] = 'contributor';
		}

		$capabilities = array(
			'edit_upcp_product',
			'read_upcp_product',
			'delete_upcp_product',
			'delete_upcp_products',
			'delete_private_upcp_products',
			'delete_published_upcp_products',
			'delete_others_upcp_products',
			'edit_upcp_products',
			'edit_private_upcp_products',
			'edit_published_upcp_products',
			'edit_others_upcp_products',
			'publish_upcp_products',
			'read_private_upcp_products',
		);

		$args = array(
			'post_type' 	=> EWD_UPCP_PRODUCT_POST_TYPE,
			'numberposts'	=> 100,
		);

		$products = get_posts( $args );

		if ( $ewd_upcp_controller->permissions->check_permission( 'premium' ) or sizeof( $products ) < 100 ) { 

			$capabilities[] = 'create_upcp_products';
		}
		
		foreach ( $manage_products_roles as $role ) {

			$role_object = get_role( $role );

			foreach ( $capabilities as $capability ) {

				$role_object->add_cap( $role, $capability );
			}
		}

		foreach ( $remove_product_roles as $role ) {

			$role_object = get_role( $role );

			foreach ( $capabilities as $capability ) {

				$role_object->remove_cap( $role, $capability );
			}
		}
	}

	public function show_submit_button( $permission_type = '' ) {
		global $ewd_upcp_controller;

		if ( $ewd_upcp_controller->permissions->check_permission( $permission_type ) ) {
			return true;
		}

		return false;
	}

	public function premium_info( $section_and_perm_type ) {
		global $ewd_upcp_controller;

		$is_premium_user = $ewd_upcp_controller->permissions->check_permission( $section_and_perm_type );
		$is_helper_installed = defined( 'EWDPH_PLUGIN_FNAME' ) && is_plugin_active( EWDPH_PLUGIN_FNAME );

		if ( $is_premium_user || $is_helper_installed ) {
			return false;
		}

		$content = '';

		$premium_features = '
			<p><strong>' . __( 'The premium version also gives you access to the following features:', 'ultimate-product-catalogue' ) . '</strong></p>
			<ul class="ewd-upcp-dashboard-new-footer-one-benefits">
				<li>' . __( 'Unlimited Products', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Custom Fields', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'WooCommerce Sync and Checkout', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Advanced Product Page Layouts', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Advanced Display and Styling Options', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Import/Export Products', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Product Page SEO Options', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Inquiry Form and Inquiry Cart', 'ultimate-product-catalogue' ) . '</li>
				<li>' . __( 'Email Support', 'ultimate-product-catalogue' ) . '</li>
			</ul>
			<div class="ewd-upcp-dashboard-new-footer-one-buttons">
				<a class="ewd-upcp-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=upcp_settings&utm_content=' . $section_and_perm_type . '" target="_blank">' . __( 'UPGRADE NOW', 'ultimate-product-catalogue' ) . '</a>
			</div>
		';

		switch ( $section_and_perm_type ) {

			case 'premium':

				$content = '
					<div class="ewd-upcp-settings-preview">
						<h2>' . __( 'Premium', 'ultimate-product-catalogue' ) . '<span>' . __( 'Premium', 'ultimate-product-catalogue' ) . '</span></h2>
						<p>' . __( 'The premium options give you access to advanced product page layouts and let you add product sorting, paginate your catalog, configure the related products, enable a product inquiry form, product reviews and product FAQs, and much more.', 'ultimate-product-catalogue' ) . '</p>
						<div class="ewd-upcp-settings-preview-images">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/premium1.png" alt="UPCP premium screenshot one">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/premium2.png" alt="UPCP premium screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'woocommerce':

				$content = '
					<div class="ewd-upcp-settings-preview">
						<h2>' . __( 'WooCommerce', 'ultimate-product-catalogue' ) . '<span>' . __( 'Premium', 'ultimate-product-catalogue' ) . '</span></h2>
						<p>' . __( 'The WooCommerce options are where you can enable and configure the sync between your catalog products and your WooCommerce products, as well as WooCommerce checkout, to let your customers purchase directly from your catalog.', 'ultimate-product-catalogue' ) . '</p>
						<div class="ewd-upcp-settings-preview-images">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/woocommerce.png" alt="UPCP woocommerce screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'seo':

				$content = '
					<div class="ewd-upcp-settings-preview">
						<h2>' . __( 'SEO', 'ultimate-product-catalogue' ) . '<span>' . __( 'Premium', 'ultimate-product-catalogue' ) . '</span></h2>
						<p>' . __( 'In the SEO options, you can enable pretty permalinks (SEO-friendly product URLs) and Yoast integration, as well as change the permalink base for your product pages.', 'ultimate-product-catalogue' ) . '</p>
						<div class="ewd-upcp-settings-preview-images">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/seo.png" alt="UPCP seo screenshot">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'labelling':

				$content = '
					<div class="ewd-upcp-settings-preview">
						<h2>' . __( 'Labelling', 'ultimate-product-catalogue' ) . '<span>' . __( 'Premium', 'ultimate-product-catalogue' ) . '</span></h2>
						<p>' . __( 'The labelling options let you change the wording of the different labels that appear on the front end of the plugin. You can use this to translate them, customize the wording for your purpose, etc.', 'ultimate-product-catalogue' ) . '</p>
						<div class="ewd-upcp-settings-preview-images">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/labelling1.png" alt="UPCP labelling screenshot one">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/labelling2.png" alt="UPCP labelling screenshot two">
						</div>
						' . $premium_features . '
					</div>
				';

				break;

			case 'styling':

				$content = '
					<div class="ewd-upcp-settings-preview">
						<h2>' . __( 'Styling', 'ultimate-product-catalogue' ) . '<span>' . __( 'Premium', 'ultimate-product-catalogue' ) . '</span></h2>
						<p>' . __( 'The styling options let you customize the look and formatting of your catalog. Here you can choose a catalog style, enable a fixed thumbnail size, change the number of columns in your catalog, set the control types (checkbox, dropdown, etc.) for your sidebar, as well as modify the color, font size, font family, border, margin and padding of the various elements found in your catalog, and more!', 'ultimate-product-catalogue' ) . '</p>
						<div class="ewd-upcp-settings-preview-images">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/styling1.png" alt="UPCP styling screenshot one">
							<img src="' . EWD_UPCP_PLUGIN_URL . '/assets/img/premium-screenshots/styling2.png" alt="UPCP styling screenshot two">
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

	/**
	 * Creates the product inquiry form using the selected plugin
	 * @since 5.0.0
	 */
	public function create_product_inquiry_form() {

		if ( empty( $this->get_setting( 'product-inquiry-form' ) ) and empty( $this->get_setting( 'product-inquiry-cart' ) ) ) { return; }

		if ( $this->get_setting( 'product-inquiry-plugin' ) == 'cf7' ) {

			$this->create_cf7_product_inquiry_form();
		}
		else {

			$this->create_wp_forms_product_inquiry_form();
		}
	}

	/**
	 * Creates the product inquiry for Contact Form 7
	 * @since 5.0.0
	 */
	public function create_cf7_product_inquiry_form() {
		
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) { return; }

		$admin_email = get_option( 'admin_email' );
		$blogname = get_option( 'blogname' );
		$site_url = get_bloginfo( 'siteurl' );

		$product_inquiry_form = get_page_by_path( 'upcp-product-inquiry-form', OBJECT, 'wpcf7_contact_form' );

		if ( $product_inquiry_form ) { return; }

		$post = array(
			'post_name' => 'upcp-product-inquiry-form',
			'post_title' => 'UPCP Inquiry Form',
			'post_type' => 'wpcf7_contact_form',
			'post_content' => 
'<p>Your Name (required)<br />
    [text* your-name] </p>
				
<p>Your Email (required)<br />
    [email* your-email] </p>

<p>Inquiry Product Name<br />
    [text product-name "%PRODUCT_NAME%"] </p>

<p>Your Message<br />
    [textarea your-message] </p>

<p>[submit "Send"]</p>
Product Inquiry E-mail
[your-name] <' . $admin_email . '>
From: [your-name] <[your-email]>
Interested Product: [product-name]

Message Body:
[your-message]

--
This e-mail was sent from a contact form on ' . $blogname . ' (' . $site_url . ')
' . $admin_email . '
Reply-To: [your-email]

0
0

[your-subject]
' . $blogname . ' <' . $admin_email . '>
Message Body:
[your-message]

--
This e-mail was sent from a contact form on ' . $blogname . ' (' . $site_url . ')
[your-email]
Reply-To: ' . $admin_email . '

0
0
Your message was sent successfully. Thanks.
Failed to send your message. Please try later or contact the administrator by another method.
Validation errors occurred. Please confirm the fields and submit it again.
Failed to send your message. Please try later or contact the administrator by another method.
Please accept the terms to proceed.
Please fill in the required field.
This input is too long.
This input is too short.
			');
		
		$post_id = wp_insert_post( $post );

		if ( $post_id ) {
				$mail_array = array(
				'subject' => 'Product Inquiry E-mail',
				'sender' => $blogname . ' <' . $admin_email . '>',
				'body' => 'From: [your-name] <[your-email]>
Interested Product: [product-name]

Message Body:
[your-message]

--
This e-mail was sent from a contact form on ' . $blogname . ' (' . $site_url . ')',
				'recipient' => $admin_email,
				'additional_headers' => 'Reply-To: [your-email]',
				'attachments' => '',
				'use_html' => 0,
				'exclude_blank' => 0
			);

			add_post_meta( $post_id, "_mail", $mail_array );
			add_post_meta( $post_id, "_form", 
'<p>Your Name (required)<br />
    [text* your-name] </p>
				
<p>Your Email (required)<br />
    [email* your-email] </p>

<p>Inquiry Product Name<br />
    [text product-name "%PRODUCT_NAME%"] </p>

<p>Your Message<br />
    [textarea your-message] </p>

<p>[submit "Send"]</p>
			');
			add_post_meta( $post_id, "_mail_2", $mail_array );
			add_post_meta( $post_id, "_messages", array(
				"mail_sent_ok",
				"Your message was sent successfully. Thanks.",
				"mail_sent_ng",
				"Failed to send your message. Please try later or contact the administrator by another method.",
				"validation_error",
				"Validation errors occurred. Please confirm the fields and submit it again.",
				"spam",
				"Failed to send your message. Please try later or contact the administrator by another method.",
				"accept_terms",
				"Please accept the terms to proceed.",
				"invalid_required",
				"Please fill in the required field.",
				"invalid_too_long",
				"This input is too long.",
				"invalid_too_short",
				"This input is too short."
				)
			);

			add_post_meta( $post_id, "_additional_settings", '' );
			add_post_meta( $post_id, "_locale", 'en_US' );
		}
	}

	/**
	 * Creates the product inquiry for WP Forms
	 * @since 5.0.0
	 */
	public function create_wp_forms_product_inquiry_form() {

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		if ( ! is_plugin_active( 'wpforms/wpforms.php' ) and ! is_plugin_active( 'wpforms-lite/wpforms.php' ) ) { return; }

		$product_inquiry_form = get_page_by_path( 'upcp-wp-forms-product-inquiry-form', OBJECT, 'wpforms' );

		if ( $product_inquiry_form ) { return; }

		$post = array(
			'post_name' 	=> 'upcp-wp-forms-product-inquiry-form',
			'post_title' 	=> 'UPCP Inquiry Form',
			'post_type' 	=> 'wpforms',
			'post_status' 	=> 'publish',
			'post_content' 	=> 'placeholder'
		);

		$post_id = wp_insert_post($post);
		
		if ( $post_id ) {

			$update = array(
				'ID' 			=> $post_id,
				'post_content' 	=> '{"id":"' . $post_id . '","field_id":5,"fields":{"1":{"id":"1","type":"text","label":"Your Name","description":"","required":"1","size":"medium","placeholder":"","default_value":"","css":"","input_mask":""},"3":{"id":"3","type":"email","label":"Your Email","description":"","required":"1","size":"medium","placeholder":"","confirmation_placeholder":"","default_value":"","css":""},"2":{"id":"2","type":"text","label":"Inquiry Product Name","description":"","size":"medium","placeholder":"","default_value":"%PRODUCT_NAME%","css":"","input_mask":""},"4":{"id":"4","type":"textarea","label":"Your Message","description":"","size":"medium","placeholder":"","css":""}},"settings":{"form_title":"Product Inquiry E-mail","form_desc":"","form_class":"","submit_text":"Send","submit_text_processing":"Sending...","submit_class":"","honeypot":"1","notification_enable":"1","notifications":{"1":{"notification_name":"Default Notification","email":"{admin_email}","subject":"New Blank Form Entry","sender_name":"Demo Theme Test Setup","sender_address":"{admin_email}","replyto":"","message":"{all_fields}"}},"confirmation_type":"message","confirmation_message":"Thanks for inquiring! We will be in touch with you shortly.","confirmation_message_scroll":"1","confirmation_page":"11573","confirmation_redirect":""},"meta":{"template":"blank"}}'
			);

			wp_update_post( $update );
		}
	}
}
} // endif;
