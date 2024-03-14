<?php
/**
 * Handles the admin-specific hooks, and
 * public-facing site hooks.
 * 
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 * 
 */
if ( !class_exists( 'Wpmagazine_Modules_Lite_Elements' ) ) :

    class Wpmagazine_Modules_Lite_Elements {
        /**
         * Instance
         *
         * @access private
         * @static
         *
         * @var Wpmagazine_Modules_Lite_Elements The single instance of the class.
         */
        private static $_instance = null;

        /**
         * Ensures only one instance of the class is loaded or can be loaded.
         *
         * @access public
         * @static
         *
         * @return Wpmagazine_Modules_Lite_Elements An instance of the class.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Load the dependencies and set the hooks for the admin area and
         * the public-facing side of the element.
         */
        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'init' ), 99 );
        }
        
        /**
         * Initialize the dependencies necessary hooks.
         */
        public function init() {
            if ( !WPMAGAZINE_MODULES_LITE_ELEMENTOR ) {
                return;
            }

            add_action( 'elementor/elements/categories_registered', array( $this, 'add_elements_categories' ), 10 );
            
            //Register custom control
            add_action( 'elementor/controls/controls_registered', array( $this, 'register_control' ) );

            // Register elements
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );

            add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'elementor_enqueue_scripts' ) );

            add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'elementor_editor_scripts' ) );
        }

        /**
         * Register new control
         */
        public function register_control() {
            // Include control files.
            require_once( __DIR__ . '/src/block-base/controls/elements-radio-image-control/radio-image.php' );
            require_once( __DIR__ . '/src/block-base/controls/elements-multicheckbox-control/multicheckbox.php' );
            
            //Register control
            $controls_manager = \Elementor\Plugin::$instance;
            $controls_manager->controls_manager->register_control( 'RADIOIMAGE', new Wpmagazine_Modules_Lite_Radio_Image_Control() );
            $controls_manager->controls_manager->register_control( 'MULTICHECKBOX', new Wpmagazine_Modules_Lite_Multicheckbox_Control() );
        }

        /**
         * Initialize the widgets in elementor
         * 
         */
        public function init_widgets() {
            // Include Widget files
            require_once( __DIR__ . '/src/banner/element.php' );
            require_once( __DIR__ . '/src/post-grid/element.php' );
            require_once( __DIR__ . '/src/post-list/element.php' );
            require_once( __DIR__ . '/src/post-masonry/element.php' );
            require_once( __DIR__ . '/src/post-tiles/element.php' );
            require_once( __DIR__ . '/src/ticker/element.php' );
            require_once( __DIR__ . '/src/post-carousel/element.php' );
            require_once( __DIR__ . '/src/post-slider/element.php' );
            require_once( __DIR__ . '/src/post-block/element.php' );
            require_once( __DIR__ . '/src/category-collection/element.php' );
            require_once( __DIR__ . '/src/timeline/element.php' );
            require_once( __DIR__ . '/src/post-filter/element.php' );

            // Register widget
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Banner_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Grid_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_List_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Masonry_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Carousel_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Tiles_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Ticker_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Slider_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Block_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Category_Collection_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Timeline_Element() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Wpmagazine_Modules_Lite_Post_Filter_Element() );
        }

        /**
         * Enqueue elements scripts.
         */
        public function elementor_enqueue_scripts() {

            wp_enqueue_script( 'wpmagazine-modules-lite-elementor-public-script', plugins_url( '/assets/js/elementor-frontend.js', __FILE__ ), array( 'jquery' ), WPMAGAZINE_MODULES_LITE_VERSION, true );

            wp_localize_script( 'wpmagazine-modules-lite-elementor-public-script', 'wpmagazineModulesElementorObject', array(
                'ajax_url'  => admin_url( 'admin-ajax.php' ),
                '_wpnonce'  => wp_create_nonce( 'wpmagazine_modules_lite_public_nonce' )
            ));
        }

        /**
         * Enqueue elements admin scripts.
         */
        public function elementor_editor_scripts() {

            wp_enqueue_style( 'wpmagazine-modules-lite-elementor-icon-style', plugins_url( 'assets/cvmm-icons/style.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );

            wp_enqueue_style( 'wpmagazine-modules-lite-elementor-editor-style', plugins_url( 'assets/css/elementor-editor.css', __FILE__ ), array(), WPMAGAZINE_MODULES_LITE_VERSION, 'all' );
        }

        /**
         * Init Widgets categories
         *
         * @since 1.0.0
         *
         * @access public
         */
        public function add_elements_categories( $elements_manager ) {
            $elements_manager->add_category(
                'wpmagazine-modules-lite',
                [
                    'title' => esc_html__( 'WP Magazine Modules Lite', 'wp-magazine-modules-lite' )
                ]
            );
        }
    }
    
    Wpmagazine_Modules_Lite_Elements::instance();

endif;