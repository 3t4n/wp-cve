<?php
/**
 * Plugin Name:       LA-Studio Element Kit for Elementor
 * Description:       Additional widgets for Elementor page builder. It has 60 highly customizable widgets
 * Version:           1.3.7.5
 * Author:            LA-Studio
 * Author URI:        https://la-studioweb.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lastudio-kit
 * Domain Path:       /languages
 *
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 *
 * @package lastudio-kit
 * @author  LA-Studio
 * @license GPL-2.0+
 * @copyright  2024, LA-Studio
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if(!class_exists('LaStudio_Kit')){
    class LaStudio_Kit{
        /**
         * A reference to an instance of this class.
         *
         * @since  1.0.0
         * @access private
         * @var    object
         */
        private static $instance = null;

        /**
         * Holder for base plugin URL
         *
         * @since  1.0.0
         * @access private
         * @var    string
         */
        private $plugin_url = null;

        /**
         * Holder for base plugin path
         *
         * @since  1.0.0
         * @access private
         * @var    string
         */
        private $plugin_path = null;

        /**
         * Plugin version
         *
         * @var string
         */
        private $version = '1.3.7.5';

        /**
         * Framework component
         *
         * @since  1.0.0
         * @access public
         * @var    object
         */
        public $module_loader = null;


        /**
         * @var \LaStudioKitThemeBuilder\Modules\Modules_Manager $modules_manager
         */
        public $modules_manager;

        /**
         * @since  2.0.0
         * @access public
         * @var \LaStudioKitExtensions\Manager $extensions_manager
         */
        public $extensions_manager;

	    /**
	     * @var LaStudio_Kit_Ajax_Manager $ajax_manager;
	     */
        public $ajax_manager;

        /**
         * Holder for current Customizer module instance.
         *
         * @since 1.0.0
         * @var   CX_Customizer
         */
        public $customizer = null;


        /**
         * Sets up needed actions/filters for the plugin to initialize.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function __construct() {

            spl_autoload_register( [ $this, 'autoload' ] );

            // Load the CX Loader.
            add_action( 'after_setup_theme', array( $this, 'module_loader' ), -20 );

            // load includes
            add_action( 'after_setup_theme', array( $this, 'includes' ), 4 );

            // init customizer
            add_action( 'after_setup_theme', array( $this, 'init_customizer' ), 6 );

            // Internationalize the text strings used.
            add_action( 'init', array( $this, 'lang' ), -999 );

            // Load files.
            add_action( 'init', array( $this, 'init' ), -999 );

            // Dashboard Init
            add_action( 'init', array( $this, 'dashboard_init' ), -999 );

            // Add body class
            add_filter('body_class', array( $this, 'body_class' ), 0);

            add_action('elementor/init', [ $this, 'on_elementor_init' ] );

            add_action('admin_enqueue_scripts', [ $this, 'admin_enqueue'] );

            add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ], 0 );

            // Register handle ajax global

            // Register activation and deactivation hook.
            register_activation_hook( __FILE__, array( $this, 'activation' ) );
            register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

            // Load handle ajax
	        $this->ajax_manager = new LaStudio_Kit_Ajax_Manager();

            // WPML Integrations
            add_action( 'init', [ $this, 'solve_wpml_missing_st_addon' ], -10);
        }

        /**
         * Load the theme modules.
         *
         * @since  1.0.0
         */
        public function module_loader() {

            require $this->plugin_path( 'includes/framework/loader.php' );

            $this->module_loader = new LaStudio_Kit_CX_Loader(
                array(
                    $this->plugin_path( 'includes/framework/vue-ui/cherry-x-vue-ui.php' ),
                    $this->plugin_path( 'includes/framework/db-updater/cx-db-updater.php' ),
                    $this->plugin_path( 'includes/framework/dashboard/dashboard.php' ),
                    $this->plugin_path( 'includes/framework/interface-builder/interface-builder.php' ),
                    $this->plugin_path( 'includes/framework/post-meta/post-meta.php' ),
                    $this->plugin_path( 'includes/framework/term-meta/term-meta.php' ),
                    $this->plugin_path( 'includes/framework/customizer/customizer.php' ),
                    $this->plugin_path( 'includes/framework/fonts-manager/fonts-manager.php' ),
                    $this->plugin_path( 'includes/class-breadcrumbs.php' ),
                )
            );

            // Enable support for Post Formats
            add_theme_support( 'post-formats', array( 'standard', 'video', 'gallery', 'audio', 'quote', 'link' ) );
        }

        /**
         * Load the theme includes
         */
        public function includes(){
            require_once $this->plugin_path( 'includes/class-post-meta.php' );
            require_once $this->plugin_path( 'includes/class-term-meta.php' );
        }

        /**
         * Returns plugin version
         *
         * @return string
         */
        public function get_version( $basic = false ) {

        	if($basic){
		        return $this->version;
	        }

            if(defined('LA_DEBUG') && LA_DEBUG){
                return time();
            }
            return $this->version;
        }

        /**
         * Manually init required modules.
         *
         * @return void
         */
        public function init() {
            if ( ! $this->has_elementor() ) {
                add_action( 'admin_notices', array( $this, 'required_plugins_notice' ) );
                return;
            }

            $this->load_files();

            lastudio_kit_integration()->init();
            lastudio_kit_svg_manager()->init();

            //Init Rest Api
            new \LaStudioKit\Rest_Api();

            if ( is_admin() ) {

                //Init Settings Manager
                new \LaStudioKit\Settings();
                // include DB upgrader
                require $this->plugin_path( 'includes/class-db-upgrader.php' );
                // Init DB upgrader
                new LaStudio_Kit_DB_Upgrader();
            }

            $this->extensions_manager = new LaStudioKitExtensions\Manager();

            do_action( 'lastudio-kit/init', $this );
        }

        /**
         * [dashboard_init description]
         * @return [type] [description]
         */
        public function dashboard_init() {

            if ( is_admin() ) {

                $lastudio_kit_dashboard_module_data = $this->module_loader->get_included_module_data( 'dashboard.php' );

                $lastudio_kit_dashboard = \LaStudioKit_Dashboard\Dashboard::get_instance();

                $lastudio_kit_dashboard->init( array(
                    'path'           => $lastudio_kit_dashboard_module_data['path'],
                    'url'            => $lastudio_kit_dashboard_module_data['url'],
                    'cx_ui_instance' => array( $this, 'dashboard_ui_instance_init' ),
                    'plugin_data'    => array(
                        'slug'    => 'lastudio-kit',
                        'file'    => 'lastudio-element-kit/lastudio-element-kit.php',
                        'version' => $this->get_version(),
                        'plugin_links' => array(
                            array(
                                'label'  => esc_html__( 'Go to settings', 'lastudio-kit' ),
                                'url'    => add_query_arg( array( 'page' => 'lastudio-kit-dashboard-settings-page', 'subpage' => 'lastudio-kit-general-settings' ), admin_url( 'admin.php' ) ),
                                'target' => '_self',
                            ),
                        ),
                    ),
                ) );
            }
        }

        /**
         * [dashboard_ui_instance_init description]
         * @return [type] [description]
         */
        public function dashboard_ui_instance_init() {
            $cx_ui_module_data = $this->module_loader->get_included_module_data( 'cherry-x-vue-ui.php' );

            return new CX_Vue_UI( $cx_ui_module_data );
        }

        /**
         * Show recommended plugins notice.
         *
         * @return void
         */
        public function required_plugins_notice() {
            $screen = get_current_screen();

            if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
                return;
            }

            $plugin = 'elementor/elementor.php';

            $installed_plugins      = get_plugins();
            $is_elementor_installed = isset( $installed_plugins[ $plugin ] );

            if ( $is_elementor_installed ) {
                if ( ! current_user_can( 'activate_plugins' ) ) {
                    return;
                }

                $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

                $message = sprintf( '<p>%s</p>', esc_html__( 'LA-Studio Kit requires Elementor to be activated.', 'lastudio-kit' ) );
                $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $activation_url, esc_html__( 'Activate Elementor Now', 'lastudio-kit' ) );
            }
            else {
                if ( ! current_user_can( 'install_plugins' ) ) {
                    return;
                }

                $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

                $message = sprintf( '<p>%s</p>', esc_html__( 'LA-Studio Kit requires Elementor to be installed.', 'lastudio-kit' ) );
                $message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, esc_html__( 'Install Elementor Now', 'lastudio-kit' ) );
            }

            printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
        }

        /**
         * Check if theme has elementor
         *
         * @return boolean
         */
        public function has_elementor() {
            return defined( 'ELEMENTOR_VERSION' );
        }

        /**
         * Check if theme has elementor
         *
         * @return boolean
         */
        public function has_elementor_pro() {
            return defined( 'ELEMENTOR_PRO_VERSION' );
        }

        /**
         * Returns Elementor instance
         *
         * @return \Elementor\Plugin
         */
        public function elementor() {
            return \Elementor\Plugin::instance();
        }

        public function is_elementor_preview(){
            return (!empty( $_GET['elementor_library'] ) && !empty( $_GET['preview_id'] ) && !empty( $_GET['preview'] ));
        }

        /**
         * Load required files.
         *
         * @return void
         */
        public function load_files() {

            require_once $this->plugin_path( 'includes/class-helper.php' );
            require_once $this->plugin_path( 'includes/class-integration.php' );
            require_once $this->plugin_path( 'includes/class-settings.php' );
            require_once $this->plugin_path( 'includes/settings/manager.php' );
            require_once $this->plugin_path( 'includes/class-svg-manager.php' );

            require_once $this->plugin_path( 'includes/rest-api/template-helper.php' );
            require_once $this->plugin_path( 'includes/rest-api/rest-api.php' );
            require_once $this->plugin_path( 'includes/rest-api/endpoints/base.php' );
            require_once $this->plugin_path( 'includes/rest-api/endpoints/elementor-template.php' );
            require_once $this->plugin_path( 'includes/rest-api/endpoints/elementor-widget.php' );
            require_once $this->plugin_path( 'includes/rest-api/endpoints/plugin-settings.php' );
            require_once $this->plugin_path( 'includes/rest-api/endpoints/get-menu-items.php' );

            if(!defined('LASTUDIO_VERSION')){
                require_once $this->plugin_path( 'includes/integrations/override.php' );
                require_once $this->plugin_path( 'includes/integrations/advance.php' );
            }

        }

        /**
         * Returns path to file or dir inside plugin folder
         *
         * @param  string $path Path inside plugin dir.
         * @return string
         */
        public function plugin_path( $path = null ) {

            if ( ! $this->plugin_path ) {
                $this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
            }

            return $this->plugin_path . $path;
        }
        /**
         * Returns url to file or dir inside plugin folder
         *
         * @param  string $path Path inside plugin dir.
         * @return string
         */
        public function plugin_url( $path = null ) {

            if ( ! $this->plugin_url ) {
                $this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
            }

            return $this->plugin_url . $path;
        }

        /**
         * Loads the translation files.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function lang() {
            load_plugin_textdomain( 'lastudio-kit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }

        /**
         * Get the template path.
         *
         * @return string
         */
        public function template_path() {
            return apply_filters( 'lastudio-kit/template-path', 'lastudio-kit/' );
        }

        /**
         * Returns path to template file.
         *
         * @return string|bool
         */
        public function get_template( $name = null ) {

            $template = locate_template( $this->template_path() . $name );

            if ( ! $template ) {
                $template = $this->plugin_path( 'templates/' . $name );
            }

            if ( file_exists( $template ) ) {
                return $template;
            } else {
                return false;
            }
        }

        /**
         * Do some stuff on plugin activation
         *
         * @since  1.0.0
         * @return void
         */
        public function activation() {
        }

        /**
         * Do some stuff on plugin activation
         *
         * @since  1.0.0
         * @return void
         */
        public function deactivation() {
        }

        /**
         *
         * Add custom css class into body tag
         *
         * @param $classes
         * @return array
         */
        public function body_class( $classes ){
            if(is_rtl()){
                $classes[] = 'rtl';
            }
            else{
                $classes[] = 'ltr';
            }
            return $classes;
        }

        public function on_elementor_init(){
	        $this->modules_manager = new \LaStudioKitThemeBuilder\Modules\Modules_Manager();
        }

        public function admin_enqueue(){
	        wp_enqueue_style(
		        'lastudio-kit-admin-css',
		        $this->plugin_url( 'assets/css/lastudio-kit-admin.css' ),
		        false,
		        $this->get_version()
	        );
            wp_enqueue_script(
                'lastudio-kit-admin',
                $this->plugin_url('assets/js/lastudio-kit-admin.js'),
                array( 'jquery' ),
                $this->get_version()
            );
        }

        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function autoload( $class ) {

        	$mappings = [
        		'LaStudio_Kit_Ajax_Manager' => 'includes/modules/ajax/manager.php',
        		'LaStudioKitThemeBuilder_AdminApp' => 'includes/modules/admin-app/admin-app.php',
        		'Elementor\LaStudioKit_Base' => 'includes/base/class-widget-base.php',
	        ];

        	if( array_key_exists( $class, $mappings ) ){
		        if ( ! class_exists( $class ) ) {
			        $filename = $this->plugin_path($mappings[$class]);
			        if ( is_readable( $filename ) ) {
				        include( $filename );
			        }
		        }
		        return;
	        }

	        if ( 0 === strpos( $class, 'Elementor\LaStudioKit_' ) ) {
		        if ( ! class_exists( $class ) ) {
			        $class = str_replace('Elementor\LaStudioKit_', '', $class);
			        $file_addons = strtolower(
				        preg_replace(
					        [ '/^' . 'LaStudioKit_' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					        [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					        $class
				        )
			        );
					$_file_one = $this->plugin_path('includes/addons/' . $file_addons . '.php');
			        $_file_two = $this->plugin_path('includes/addons/vendor/' . $file_addons . '.php');
					if(is_readable( $_file_one )){
						include $_file_one;
					}
					elseif (is_readable( $_file_two )){
						include $_file_two;
					}
		        }
				return;
	        }

            if ( 0 === strpos( $class, 'LaStudioKitExtensions' ) ) {

                if ( ! class_exists( $class ) ) {
                    $filename_extends = strtolower(
                        preg_replace(
                            [ '/^' . 'LaStudioKitExtensions' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                            [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                            $class
                        )
                    );
                    $filename_extends = $this->plugin_path('includes/extensions/' . $filename_extends . '.php');

                    if ( is_readable( $filename_extends ) ) {
                        include( $filename_extends );
                    }
                }
				return;
            }

            if ( 0 === strpos( $class, 'LaStudioKitIntegrations' ) ) {

                if ( ! class_exists( $class ) ) {
                    $filename_extends = strtolower(
                        preg_replace(
                            [ '/^' . 'LaStudioKitIntegrations' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                            [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                            $class
                        )
                    );
                    $filename_extends = $this->plugin_path('includes/integrations/' . $filename_extends . '.php');

                    if ( is_readable( $filename_extends ) ) {
                        include( $filename_extends );
                    }
                }
				return;
            }

            if ( 0 !== strpos( $class, 'LaStudioKitThemeBuilder' ) ) {
                return;
            }

            if ( ! class_exists( $class ) ) {

                $filename = strtolower(
                    preg_replace(
                        [ '/^' . 'LaStudioKitThemeBuilder' . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
                        [ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
                        $class
                    )
                );

                $filename = $this->plugin_path('includes/' . $filename . '.php');

                if ( is_readable( $filename ) ) {
                    include( $filename );
                }
            }

        }

        public function get_theme_support( $prop = '', $default = null ) {
            $theme_support = get_theme_support( 'lastudio' );
            $theme_support = is_array( $theme_support ) ? $theme_support[0] : false;

            if ( ! $theme_support ) {
                return $default;
            }

            if ( $prop ) {
                $prop_stack = explode( '::', $prop );
                $prop_key   = array_shift( $prop_stack );

                if ( isset( $theme_support[ $prop_key ] ) ) {
                    $value = $theme_support[ $prop_key ];

                    if ( count( $prop_stack ) ) {
                        foreach ( $prop_stack as $prop_key ) {
                            if ( is_array( $value ) && isset( $value[ $prop_key ] ) ) {
                                $value = $value[ $prop_key ];
                            } else {
                                $value = $default;
                                break;
                            }
                        }
                    }
                } else {
                    $value = $default;
                }

                return $value;
            }

            return $theme_support;
        }

        public function init_customizer(){

            // Init CX_Customizer
            $customizer_options = [
                'prefix'         => 'lakit',
                'path'          => $this->plugin_path( 'includes/framework/customizer/' ),
                'capability'    => 'edit_theme_options',
                'type'          => 'theme_mod',
                'fonts_manager' => new \CX_Fonts_Manager( ['prefix' => 'lakit'] ),
                'options'       => []
            ];

            $this->customizer = new \CX_Customizer( apply_filters('lastudio-kit/theme/customizer/options', $customizer_options) );
        }

        public function plugins_loaded(){
            if( $this->has_elementor() && !$this->has_elementor_pro() ){
                new LaStudioKitThemeBuilder_AdminApp();
            }
        }

		public function is_optimized_css_mode(){
			$is_optimized_css_loading = $this->elementor()->experiments->is_feature_active( 'e_optimized_css_loading' );
			return $is_optimized_css_loading && ! $this->elementor()->preview->is_preview_mode();
		}

        public function solve_wpml_missing_st_addon(){
            new LaStudioKitIntegrations\WPML\Base();
            if( !defined('WPML_ST_VERSION') && class_exists('\WPML_Elementor_Translate_IDs_Factory')){
                $factory = new \WPML_Elementor_Translate_IDs_Factory();
                $integration = $factory->create();
                $integration->add_hooks();
            }
        }
    }
}

if(!function_exists('lastudio_kit')){
    /**
     * Returns instance of the plugin class.
     *
     * @since  1.0.0
     * @return LaStudio_Kit
     */
    function lastudio_kit(){
        return LaStudio_Kit::get_instance();
    }
}

lastudio_kit();

if(!function_exists('la_get_wc_script_data')){
    function la_get_wc_script_data( $handle ){
        if(!function_exists('WC')){
            return false;
        }
        switch ( $handle ) {
            case 'wc-add-to-cart-variation':
                $params = array(
                    'wc_ajax_url'                      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
                    'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'woocommerce' ),
                    'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'woocommerce' ),
                    'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'woocommerce' ),
                );
                break;
            default:
                $params = false;
        }

        return $params;

    }
}

if(!function_exists('la_get_polyfill_inline')){
    function la_get_polyfill_inline( $data = [] ) {
        $response_data = '';
        if(!empty($data)){
            foreach ($data as $handle => $polyfill){
                if(!empty($polyfill['condition']) && !empty($polyfill['src'])){
                    $src = $polyfill['src'];
                    if ( ! empty( $polyfill['version'] ) ) {
                        $src = add_query_arg( 'ver', $polyfill['version'], $src );
                    }
                    $src = esc_url( apply_filters( 'script_loader_src', $src, $handle ) );
                    if ( ! $src ) {
                        continue;
                    }
                    $response_data .= (
                        // Test presence of feature...
                        '( ' . $polyfill['condition'] . ' ) || ' .
                        /*
                         * ...appending polyfill on any failures. Cautious viewers may balk
                         * at the `document.write`. Its caveat of synchronous mid-stream
                         * blocking write is exactly the behavior we need though.
                         */
                        'document.write( \'<script src="' . $src . '"></scr\' + \'ipt>\' );'
                    );
                }
            }
        }
        return $response_data;
    }
}

if(!function_exists('lastudiokit__remove_object_class_filter')){
	/**
	 *
	 * Remove Class Filter Without Access to Class Object
	 *
	 * @sources
	 * - https://gist.github.com/tripflex/c6518efc1753cf2392559866b4bd1a53#gistcomment-2823505
	 *
	 * @param string    $tag            Filter to remove
	 * @param string    $class_name     Class name for the filter's callback
	 * @param string    $method_name    Method name for the filter's callback
	 * @param int       $priority       Priority of the filter (default 10)
	 *
	 * @return bool Whether the function is removed.
	 */
	function lastudiokit__remove_object_class_filter( $tag, $class_name = '', $method_name = '', $priority = 10 ) {
		if(empty($class_name) || empty($class_name) || empty($tag)){
			return false;
		}
		global $wp_filter;
		$is_hook_removed = false;
		if ( ! empty( $wp_filter[ $tag ]->callbacks[ $priority ] ) ) {
			$methods = array_filter(wp_list_pluck(
				$wp_filter[ $tag ]->callbacks[ $priority ],
				'function'
			), function ($method) {
				return is_string($method) || is_array($method);
			});
			$found_hooks = ! empty( $methods ) ? wp_list_filter( $methods, array( 1 => $method_name ) ) : array();
			foreach( $found_hooks as $hook_key => $hook ) {
				if ( ! empty( $hook[0] ) && is_object( $hook[0] ) && get_class( $hook[0] ) === $class_name ) {
					$wp_filter[ $tag ]->remove_filter( $tag, $hook, $priority );
					$is_hook_removed = true;
				}
			}
		}
		return $is_hook_removed;
	}
}

add_action('woocommerce_init', function (){
	if(is_admin() && (isset($_GET['action']) && $_GET['action'] === 'elementor')){
		lastudiokit__remove_object_class_filter('wp_print_scripts', 'Automattic\WooCommerce\Blocks\Payments\Api', 'verify_payment_methods_dependencies', 1);
	}
});