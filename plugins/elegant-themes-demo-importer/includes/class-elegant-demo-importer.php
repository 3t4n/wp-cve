<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       elegantblogthemes.com
 * @since      1.0.0
 *
 * @package    elegant_Demo_Importer
 * @subpackage elegant_Demo_Importer/includes
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
 * @package    elegant_Demo_Importer
 * @subpackage elegant_Demo_Importer/includes
 * @author     Elegant Blog Themes <info@elegantblogthemes.com>
 */
class elegant_Demo_Importer {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      elegant_Demo_Importer_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'elegant_DEMO_IMPORTER_VERSION' ) ) {
			$this->version = elegant_DEMO_IMPORTER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'elegant-demo-importer';

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
	 * - elegant_Demo_Importer_Loader. Orchestrates the hooks of the plugin.
	 * - elegant_Demo_Importer_i18n. Defines internationalization functionality.
	 * - elegant_Demo_Importer_Admin. Defines all hooks for the admin area.
	 * - elegant_Demo_Importer_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-elegant-demo-importer-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-elegant-demo-importer-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-elegant-demo-importer-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elegant-demo-importer-public.php';

		$this->loader = new elegant_Demo_Importer_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the elegant_Demo_Importer_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new elegant_Demo_Importer_i18n();

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

		$plugin_admin = new elegant_Demo_Importer_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new elegant_Demo_Importer_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    elegant_Demo_Importer_Loader    Orchestrates the hooks of the plugin.
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

}




if (!function_exists('elegantthemes_importer_import_files')) {

    function elegantthemes_importer_import_files()
    {
	function elegant_ocdi_plugin_intro_text( $default_text ) {
	    $default_text .= '<div class="ocdi__intro-text"><h2>Elegant Blog Themes One Click Demo Import<h2></div>';

	    return $default_text;
	}
	add_filter( 'pt-ocdi/plugin_intro_text', 'elegant_ocdi_plugin_intro_text' );
        return array(
        	array(
                'import_file_name' => esc_html__('Elegant Recipe Blog', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/recipe/recipe.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/recipe/recipe.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/recipe/recipe.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/recipe.png',
                'import_notice' => esc_html__('Make Sure you are using the Elegant Recipe Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/elegant-recipe-blog/'),
            ),
			array(
                'import_file_name' => esc_html__('Food Travel Blog Theme (Travel Demo Version)', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/travel.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/travel.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/travel.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/travel.png',
                'import_notice' => esc_html__('Make Sure you are using the Food Travel Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/travelblog/'),
            ),

			array(
                'import_file_name' => esc_html__('Food Travel Blog Theme (Food Demo Version)', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/food.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/food.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/foodtravel/food.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/food.png',
                'import_notice' => esc_html__('Make Sure you are using the Food Travel Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/foodblog/'),
            ),
			array(
                'import_file_name' => esc_html__('Royal News Magazine', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/royal/royal.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/royal/royal.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/royal/royal.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/royal.png',
                'import_notice' => esc_html__('Make Sure you are using the Royal News Magazine Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/royal-news-magazine'),
            ),
			array(
                'import_file_name' => esc_html__('Vinyl News Mag', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/vinyl/vinyl.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/vinyl/vinyl.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/vinyl/vinyl.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/vinyl.png',
                'import_notice' => esc_html__('Make Sure you are using the Vinyl News Mag Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/vinylnewsmag/'),
            ),
			array(
                'import_file_name' => esc_html__('Messina Blog', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/messina/messinac.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/messina/messinac.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/messina/messinac.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/messina.png',
                'import_notice' => esc_html__('Make Sure you are using the Messina Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/messina-blog/'),
            ),
			array(
                'import_file_name' => esc_html__('Feminine Blog', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/feminine/feminine.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/feminine/feminine.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/feminine/feminine.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/feminine.png',
                'import_notice' => esc_html__('Make Sure you are using the Feminine Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/feminine-blog-free/'),
            ),
			array(
                'import_file_name' => esc_html__('Zion Blog', 'elegant-demo-importer'),
                'import_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/zion/zion.xml',
                'import_widget_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/zion/zion.wie',
                'import_customizer_file_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/demo/zion/zion.dat',
				'import_preview_image_url' => trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) . 'public/img/zion.png',
                'import_notice' => esc_html__('Make Sure you are using the Zion Blog Theme (Free Version)', 'elegant-demo-importer'),
                'preview_url' => ('https://demo.elegantblogthemes.com/zion-blog-free/'),
            )
            
        );
    }
}
if (in_array('one-click-demo-import/one-click-demo-import.php', apply_filters('active_plugins', get_option('active_plugins')))) {

    add_filter('pt-ocdi/import_files', 'elegantthemes_importer_import_files');
    add_action( 'pt-ocdi/after_import', 'elegantthemes_importer_after_import_setup' );
}
else{
	function sample_admin_notice__error() {
	    $class = 'notice notice-error';
	    $message = __( 'You have not installed or activated the One Click Demo Import Plugin', 'elegant-demo-importer' );
	 
	    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	add_action( 'admin_notices', 'sample_admin_notice__error' );
}



function elegantthemes_importer_after_import_setup() {
    // Assign menus to their locations.
    $main_menu = get_term_by( 'name', 'Primary Menu', 'nav_menu' );

    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );
    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

}