<?php
/**
 * Defines the core plugin class
 * 
 * Handles the internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite' ) ) :

    class Wpmagazine_Modules_Lite {
        /**
         * The unique identifier of this plugin.
         * @access   protected
         */
        protected $plugin_name;

        /**
         * The current version of the plugin.
         * @access   protected
         */
        protected $version;

        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpmagazine_Modules_Lite The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpmagazine_Modules_Lite An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         */
        public function __construct() {
            if ( defined( 'WPMAGAZINE_MODULES_LITE_VERSION' ) ) {
                $this->version = WPMAGAZINE_MODULES_LITE_VERSION;
            } else {
                $this->version = '1.0.8';
            }

            $this->plugin_name = 'wp-magazine-modules-lite';
            define( 'WPMAGAZINE_MODULES_LITE_DEFAULT_IMAGE', plugins_url( '/assets/images/default-image.png', __FILE__ ) );
            $this->load_dependencies();
            $this->set_locale();
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'after_setup_theme', array( $this, 'wpmagazine_modules_lite_layout_image_sizes' ) );
            add_action( 'wp_ajax_wpmagazine_modules_lite_post_filter_load_new_posts', array( $this, 'post_filter_load_new_posts' ) );
            add_action( 'wp_ajax_nopriv_wpmagazine_modules_lite_post_filter_load_new_posts', array( $this, 'post_filter_load_new_posts' ) );
        }

        /**
         * Define the locale for this plugin for internationalization.
         *
         * Uses the Wpmagazine_modules_Lite_i18n class in order to set the domain and to register the hook
         * with WordPress.
         *
         * @since    1.0.0
         * @access   private
         */
        private function set_locale() {

            $i18n = new Wpmagazine_modules_Lite_i18n();
            add_action( 'plugins_loaded', array( $i18n, 'load_plugin_textdomain' ) );
        }
    
        /**
         * Load dependencies
         */
        public function load_dependencies() {
            require_once plugin_dir_path( __FILE__ ) . '/i18n.php';
            require_once plugin_dir_path( __FILE__ ) . '/dynamic-allcss.php';
            require_once plugin_dir_path( __FILE__ ) . '/gutenberg.php';
            require_once plugin_dir_path( __FILE__ ) . '/elementor.php';
        }

        /**
         * load scripts.
         */
        public function enqueue_scripts() {
            $cvmm_fonts_url = $this->get_google_fonts_url();
            wp_enqueue_style( 'wpmagazine-modules-lite-google-fonts', esc_url( $cvmm_fonts_url ), array(), null );
            wp_enqueue_style( 'fontawesome', plugins_url( 'assets/library/fontawesome/css/all.min.css', __FILE__ ), array(), '5.12.1', 'all' );
            wp_enqueue_style( 'wpmagazine-modules-lite-frontend', plugins_url( 'assets/css/build.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );
            
            $dynamic_allcss_class = new Wpmagazine_Modules_Lite_Dynamic_AllCss();
            $dynamic_css = $dynamic_allcss_class->category_parsed_css();
            wp_add_inline_style( 'wpmagazine-modules-lite-frontend', wp_strip_all_tags( $dynamic_css ) );

            wp_enqueue_style( 'slick-slider', plugins_url( 'assets/library/slick-slider/css/slick.css', __FILE__ ), array(), '1.8.0', 'all' );

            wp_enqueue_style( 'slick-slider-theme', plugins_url( 'assets/library/slick-slider/css/slick-theme.css', __FILE__ ), array(), '1.8.0', 'all' );
            
            wp_enqueue_script( 'wpmagazine-modules-lite-public-script', plugins_url( '/assets/js/frontend.js', __FILE__ ), array( 'jquery' ), WPMAGAZINE_MODULES_LITE_VERSION, true );

            wp_enqueue_script( 'slick-slider', plugins_url( '/assets/library/slick-slider/js/slick.min.js', __FILE__ ), array( 'jquery' ), '1.8.0', true );

            wp_enqueue_script( 'imagesloaded' );
            wp_enqueue_script( 'masonry' );

            wp_enqueue_script( 'jquery-marquee', plugins_url( '/assets/library/jQuery.Marquee/jquery.marquee.min.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );

            wp_localize_script( 'wpmagazine-modules-lite-public-script', 'wpmagazineModulesObject', array(
                'ajax_url'  => admin_url( 'admin-ajax.php' ),
                '_wpnonce'  => wp_create_nonce( 'wpmagazine_modules_lite_public_nonce' )
            ));
        }


        /**
         * Add "block" image sizes
         * 
         */
        function wpmagazine_modules_lite_layout_image_sizes() {
            add_image_size( 'cvmm-medium', 300, 300, true );
            add_image_size( 'cvmm-medium-plus', 305, 207, true );
            add_image_size( 'cvmm-portrait', 400, 600, true );
            add_image_size( 'cvmm-medium-square', 600, 600, true );
            add_image_size( 'cvmm-large', 1024, 1024, true );
            add_image_size( 'cvmm-small', 130, 95, true );
        }

        /**
         * Ajax filter function for "Block Post Filter"
         * 
         */
        function post_filter_load_new_posts() {
            if ( isset( $_POST['_wpnonce'] ) && !wp_verify_nonce( $_POST['_wpnonce'], 'wpmagazine_modules_lite_public_nonce' ) ) {
                esc_html_e( 'No kiddies', 'wp-magazine-modules-lite' );
            }
            $attributes = $_POST['attributes'];
            $term_id = absint( sanitize_text_field( $_POST['term_id'] ) );
            extract( $attributes );
            $taxonomies = get_taxonomies( array( 'object_type' => array( 'post' ) ) );
            foreach( $taxonomies as $taxonomy ) {
                $taxonomy_name = $taxonomy;
                break;
            }
            $buttonOption   = ( $buttonOption === 'true' || $buttonOption === 'show' );
            $postButtonIcon = ( $postButtonIcon === 'true' || $postButtonIcon === 'show' );
            $contentOption  = ( $contentOption === 'true' || $contentOption === 'show' );
            $commentOption  = ( $commentOption === 'true' || $commentOption === 'show' );
            $tagsOption     = ( $tagsOption === 'true' || $tagsOption === 'show' );
            $categoryOption = ( $categoryOption === 'true' || $categoryOption === 'show' );
            $authorOption   = ( $authorOption === 'true' || $authorOption === 'show' );
            $dateOption     = ( $dateOption === 'true' || $dateOption === 'show' );
            $titleOption    = ( $titleOption === 'true' || $titleOption === 'show' );
            $thumbOption    = ( $thumbOption === 'true' || $thumbOption === 'show' );
            $postFormatIcon = ( $postFormatIcon === 'true' || $postFormatIcon === 'show' );
            $postMetaIcon   = ( $postMetaIcon === 'true' || $postMetaIcon === 'show' );
            include( plugin_dir_path( __FILE__ ) . 'src/post-filter/' . esc_html( $blockLayout ) . '/template.php' );
            wp_die();
        }

        /**
         * Register google fonts for frontend
         */
        public function get_google_fonts_url() {
            $fonts_url = '';
            $font_families = array();

            /**
             * Roboto
             * 
             */
            if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Roboto:400,100,300,400,500,700,900';
            }

            /**
             * Yanone Kaffeesatz
             */
            if ( 'off' !== _x( 'on', 'Yanone Kaffeesatz font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Yanone Kaffeesatz:200,300,400,500,600,700';
            }

            /**
             * Open Sans
             */
            if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Open Sans:300,400,600,700,800';
            }

            /**
             * Roboto Slab
             */
            if ( 'off' !== _x( 'on', 'Roboto Slab font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Roboto Slab:100,200,300,400,500,600,700,800,900';
            }

            /**
             * Poppins
             */
            if ( 'off' !== _x( 'on', 'Poppins font: on or off', 'wp-magazine-modules-lite' ) ) {
                $font_families[] = 'Poppins:100,200,300,400,500,600,700,800,900';
            }

            if ( $font_families ) {
                $query_args = array(
                    'family' => urlencode( implode( '|', $font_families ) ),
                    'subset' => urlencode( 'latin,latin-ext' ),
                );

                $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
            }

            return $fonts_url;
        }
    }

endif;