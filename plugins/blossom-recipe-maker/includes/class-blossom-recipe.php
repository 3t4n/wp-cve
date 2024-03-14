<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link  test.com
 * @since 1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
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
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Blossom_Recipe_Maker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * A reference to the meta box.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Blossom_Recipe_Meta_Box    $meta_box    A reference to the meta box for the plugin.
	 */
	private $meta_box;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		if ( defined( 'BLOSSOM_RECIPE_MAKER_VERSION' ) ) {
			$this->version = BLOSSOM_RECIPE_MAKER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'blossom-recipe-maker';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->meta_box = new Blossom_Recipe_Maker_Meta_Box();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Blossom_Recipe_Maker_Loader. Orchestrates the hooks of the plugin.
	 * - Blossom_Recipe_Maker_i18n. Defines internationalization functionality.
	 * - Blossom_Recipe_Maker_Admin. Defines all hooks for the admin area.
	 * - Blossom_Recipe_Maker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function load_dependencies() {

		/**
		 * Core helper and utility functions.
		 *
		 * @since 1.0.8
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-blossom-recipe-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-blossom-recipe-public.php';

		/**
		 * The class responsible for handling blossom-recipe post type single post and archieve templates.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-templates.php';

		/**
		 * The class responsible for defining filter functions in the admin-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-functions.php';

		/**
		 * The class responsible for defining all hooks that display meta data in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-hook-functions.php';

		/**
		 * The class responsible for defining all hooks that display print ready template in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-print-functions.php';

		/**
		 * The class responsible for defining all hooks that display meta data in the archive section of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-archive-hook-functions.php';

		/**
		 * The class responsible for displaying recipe shortcode in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/frontend/recipe-shortcode.php';

		/**
		 * The class responsible for displaying recipe search bar in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-search-template.php';

		/**
		 * The class responsible for displaying recipe search results in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/recipe-search-results.php';

		/**
		 * The class responsible for displaying recipe search shortcode in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/frontend/recipe-search-shortcode.php';

		/**
		 * The class responsible for displaying recipe taxonomy thumbnails in the admin
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-taxonomy-thumbnail.php';

		/**
		 * The class responsible for displaying recipe rich data in the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-blossom-recipe-seo.php';

		/**
		 * Popular Recipe Widget.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-popular-recipe.php';

		/**
		 * Recent Recipe Widget.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-recent-recipe.php';

		/**
		 * Recipe Categories Widget.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-recipe-categories.php';

		/**
		 * Recipes Category Slider Widget.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/widgets/widget-recipes-category-slider.php';

		/**
		 * The class responsible for displaying recent recipes shortcode in the public-facing
		 * side of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/frontend/recent-recipe-shortcode.php';

		/**
		 * The class responsible for recipe permalinks of the site.
		 */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-blossom-recipe-permalinks.php';

		$this->loader = new Blossom_Recipe_Maker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Blossom_Recipe_Maker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function set_locale() {

		$plugin_i18n = new Blossom_Recipe_Maker_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Blossom_Recipe_Maker_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_register_recipe_post_types' );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_create_categories_type_taxonomies', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_create_cuisine_type_taxonomies', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_create_cooking_method_type_taxonomies', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_create_cooking_method_type_taxonomies', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'brm_create_recipe_tags_taxonomies', 0 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'brm_register_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'brm_register_settings' );

		$this->loader->add_filter( 'manage_blossom-recipe_posts_columns', $plugin_admin, 'set_blossom_recipe_columns' );
		$this->loader->add_action( 'manage_blossom-recipe_posts_custom_column', $plugin_admin, 'set_blossom_recipe_columns_content', 10, 2 );

		$this->loader->add_filter( 'page_template', $plugin_admin, 'brm_recipe_listing_template' );
		$this->loader->add_filter( 'theme_page_templates', $plugin_admin, 'brm_recipe_admin_page_templates' );

		$this->loader->add_filter( 'manage_edit-recipe-category_columns', $plugin_admin, 'blossom_recipe_taxonomy_columns', 10, 2 );
		$this->loader->add_action( 'manage_recipe-category_custom_column', $plugin_admin, 'blossom_recipe_taxonomy_columns_content', 10, 3 );

		$this->loader->add_filter( 'manage_edit-recipe-cuisine_columns', $plugin_admin, 'blossom_recipe_taxonomy_columns', 10, 2 );
		$this->loader->add_action( 'manage_recipe-cuisine_custom_column', $plugin_admin, 'blossom_recipe_taxonomy_columns_content', 10, 3 );

		$this->loader->add_filter( 'manage_edit-recipe-cooking-method_columns', $plugin_admin, 'blossom_recipe_taxonomy_columns', 10, 2 );
		$this->loader->add_action( 'manage_recipe-cooking-method_custom_column', $plugin_admin, 'blossom_recipe_taxonomy_columns_content', 10, 3 );

		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'filter_recipes_by_taxonomies', 10, 2 );

		$this->loader->add_action( 'wp_ajax_brm_recipe_tax_terms', $plugin_admin, 'brm_recipe_tax_terms' );
		$this->loader->add_action( 'wp_ajax_brm_recipe_slider_tax_terms', $plugin_admin, 'brm_recipe_slider_tax_terms' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 */
	private function define_public_hooks() {

		$plugin_public = new Blossom_Recipe_Maker_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'post_types_author_archives' );
		$this->loader->add_filter( 'pre_get_posts', $plugin_public, 'blossom_recipe_archive_posts_per_page' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since  1.0.0
	 * @return string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since  1.0.0
	 * @return Blossom_Recipe_Maker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since  1.0.0
	 * @return string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
