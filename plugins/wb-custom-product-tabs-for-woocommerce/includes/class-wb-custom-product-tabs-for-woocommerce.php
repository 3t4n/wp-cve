<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/webbuilder143/
 * @since      1.0.0
 *
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wb_Custom_Product_Tabs_For_Woocommerce
 * @subpackage Wb_Custom_Product_Tabs_For_Woocommerce/includes
 * @author     Web Builder 143 <webbuilder143@gmail.com>
 */
class Wb_Custom_Product_Tabs_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wb_Custom_Product_Tabs_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.1.12';
		}
		$this->plugin_name = 'wb-custom-product-tabs-for-woocommerce';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_i18n. Defines internationalization functionality.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Wb_Custom_Product_Tabs_For_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wb-custom-product-tabs-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wb-custom-product-tabs-for-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wb-custom-product-tabs-for-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wb-custom-product-tabs-for-woocommerce-public.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/classes/class-wb-custom-product-tabs-for-woocommerce-feedback.php';

		$this->loader = new Wb_Custom_Product_Tabs_For_Woocommerce_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wb_Custom_Product_Tabs_For_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wb_Custom_Product_Tabs_For_Woocommerce_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wb_Custom_Product_Tabs_For_Woocommerce_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/* product page tab */
		$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'product_data_tabs' );
		
		/* product page tab content */
		$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'product_data_panels' );
		
		/* save tab content */
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'process_product_meta',10,2);
		
		/**
		* @since 1.0.2 
		* register global tabs as custom post type 
		*/
		$this->loader->add_action( 'init', $plugin_admin, 'register_global_tabs',10,2);

		/**
		* @since 1.0.2 
		* register meta box for global tab custom post type
		*/
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_meta_box',10,2);

		/**
		* @since 1.0.2 
		* save meta box data for global tab custom post type
		*/
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_meta_box_data', 10, 2);

		/**
		* @since 1.0.9 
		* Global tabs, Add new product links on plugins page
		*/
		$this->loader->add_filter('plugin_action_links_'.plugin_basename(WB_TAB_PLUGIN_FILENAME), $plugin_admin, 'plugin_action_links');

		/**
		* @since 1.1.0 
		* Nickname column in global tabs listing page
		*/
		$this->loader->add_filter('manage_'.WB_TAB_POST_TYPE.'_posts_columns', $plugin_admin, 'add_nickname_column');
		$this->loader->add_action('manage_'.WB_TAB_POST_TYPE.'_posts_custom_column' , $plugin_admin, 'add_nickname_column_data', 10, 2);

		/**
		 * Product categories/tags columns in global tabs listing page
		 * 
		 * @since 1.1.3
		 */
		$this->loader->add_filter('manage_'.WB_TAB_POST_TYPE.'_posts_columns', $plugin_admin, 'add_product_cat_tag_column');
		$this->loader->add_action('manage_'.WB_TAB_POST_TYPE.'_posts_custom_column' , $plugin_admin, 'add_product_cat_tag_column_data', 10, 2);

		/**
		 * YouTube Embed option in tab content editor
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('media_buttons', $plugin_admin, 'add_youtube_embed_button');


		/**
		 * YouTube Embed popup HTML
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('in_admin_header', $plugin_admin, 'add_youtube_embed_popup');


		/**
		 * Change log in upgrade notice
		 * 
		 * @since 1.1.5
		 */
		$this->loader->add_action('in_plugin_update_message-wb-custom-product-tabs-for-woocommerce/wb-custom-product-tabs-for-woocommerce.php', $plugin_admin, 'changelog_in_upgrade_notice', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wb_Custom_Product_Tabs_For_Woocommerce_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter('woocommerce_product_tabs', $plugin_public, 'add_custom_tab');
		

		/**
		 * Shortcode to embed YouTube videos
		 * 
		 * @since 1.1.5
		 */
		add_shortcode('wb_cpt_youtube_embed_shortcode', array($plugin_public, 'add_youtube_embed_shortcode'));
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wb_Custom_Product_Tabs_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Get product term IDs array
	 *
	 * @since     1.0.2
	 * @param     int 	   	product id
	 * @param     string 	term name
	 * @return    array    term IDs array
	 */
	private static function _get_product_term_ids($product_id, $term)
	{
		$terms=get_the_terms($product_id, $term);
		$terms=($terms && is_array($terms) ? $terms : array());
		return array_column($terms, 'term_id');
	}


	/**
	 * Get custom tabs of a product
	 *
	 * @since     1.0.2
	 * @param     WC_Product object   	$product  
	 * @param     boolean   			$sort  			Sort the product based on tab position
	 * @return    array     			Product tabs
	 */
	public static function get_product_tabs($product, $sort=false)
	{
		/** 
		*	Taking custom tabs 
		*/
		$product_id=(method_exists($product, 'get_id')===true ? $product->get_id() : $product->ID);
		$wb_tabs=$product->get_meta('wb_custom_tabs', true);
		$wb_tabs=($wb_tabs ? maybe_unserialize($wb_tabs) : array());
		$wb_tabs=(is_array($wb_tabs) ? $wb_tabs : array());

		/* 
		*	Taking global tabs 
		*/
		/* Taking categories */
		$cat_id_arr=self::_get_product_category_ids($product_id);

		/* Taking tags */
		$tag_id_arr=self::_get_product_term_ids($product_id, 'product_tag');

		$query = new WP_Query(
			array(
				'post_type'=>WB_TAB_POST_TYPE,
				'tax_query'=>array(
					'relation'=>'OR',
			        array(
			            'taxonomy'=>'product_cat',
			            'field'=>'ID',
			            'terms'=>$cat_id_arr,
			            'include_children' => apply_filters('wb_cptb_include_child_category_tabs', true),
			        ),
			        array(
			            'taxonomy'=>'product_tag',
			            'field'=>'ID',
			            'terms'=>$tag_id_arr,
			        ),
			    )
			)
		 );

		if($query->have_posts())
		{
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id=get_the_ID();
				$tab_position=self::_get_global_tab_position($post_id);		
				$tab_nickname=self::_get_global_tab_nickname($post_id);		
				$wb_tabs[]=array('title'=>get_the_title(), 'content'=>get_the_content(), 'tab_type'=>'global', 'position'=>$tab_position, 'nickname'=>$tab_nickname, 'tab_id'=>$post_id);
			}
		}
		wp_reset_postdata();

		/* 
		*	If sort is true then sort the tabs based on Tabs position 
		*/
		if($sort)
		{
			$position_arr=array_column($wb_tabs, 'position');
			array_multisort($position_arr, SORT_ASC, $wb_tabs);
		}

		return $wb_tabs;
	}

	public static function _get_global_tab_position($id)
	{
		$tab_position=get_post_meta($id, '_wb_tab_position', true);
		return absint($tab_position==="" ? 20 : $tab_position);
	}

	public static function _get_global_tab_nickname($id)
	{
		$tab_nickname=get_post_meta($id, '_wb_tab_nickname', true);
		return $tab_nickname===false ? '' : $tab_nickname;
	}

	/**
	 * Get product category IDs array
	 *
	 * @since     1.1.9
	 * @param     int 	   	product id
	 * @return    int[]    	category IDs array
	 */
	private static function _get_product_category_ids($product_id)
	{
		$category_ids = array();
		if(apply_filters('wb_cptb_include_parent_category_tabs', true)) 
		{
			$category_ids = wc_get_product_cat_ids($product_id);
		}else
		{
			$category_ids = wc_get_product_term_ids($product_id, 'product_cat');
		}

		return $category_ids;
	}
}
