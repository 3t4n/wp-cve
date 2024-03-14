<?php

namespace BetterWidgets;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.0.0
 */
class Plugin
{
    /**
     * Instance
     *
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Plugin The single instance of the class.
     */
    private static  $_instance = null ;
    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     *
     * @return Plugin An instance of the class.
     */
    public static function instance()
    {
        
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->includes();
        }
        
        return self::$_instance;
    }
    
    public  $basic_elements = array(
        'countdown',
        'image-box',
        'featured',
        'price',
        'clients',
        'team',
        'testimonial',
        'testimonial-carousel',
        'heading',
        'portfolio',
        'comparison',
        'menu-list',
        'blog',
        'slider',
        'slider-parallax',
        'fancy',
        'info-box',
        'about',
        'services',
        'nav',
        'contact',
        'map',
        'logo',
        'cart',
        'button',
        'image',
        'product',
        'counter',
        'breadcrumbs',
        'menu',
        'shadow',
        'showcase',
        'search-widget',
        'woo',
        'header-search',
        'image-box-slider',
        'bottom-shape',
        'top-shape',
        'insta',
        'video-box',
        'post-title',
        'post-author',
        'post-comments',
        'post-featured-image',
        'post-date',
        'animated-heading',
        'gallery'
    ) ;
    public  $pro_elements = array(
        'fancy-box',
        'advanced-heading',
        'advanced-icon',
        'advanced-slider'
    ) ;
    /**
     * widget_scripts
     *
     * Load required plugin core files. 
     *
     * @since 1.0.0
     * @access public
     */
    public function widget_scripts()
    {
        wp_register_script(
            'better-countdown',
            plugins_url( 'assets/js/jquery.countdown.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-slick',
            plugins_url( 'assets/js/slick.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'youtubepopup-js',
            plugins_url( 'assets/js/youtubepopup.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-testimonial',
            plugins_url( 'assets/js/testimonial.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-portfolio',
            plugins_url( 'assets/js/portfolio.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-portfolio-full',
            plugins_url( 'assets/js/portfolio-full.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-menu-list',
            plugins_url( 'assets/js/menu-list.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-header-search',
            plugins_url( 'assets/js/header-search.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-slider',
            plugins_url( 'assets/js/slider.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'slider-parallax',
            plugins_url( 'assets/js/slider-parallax.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'nice-select',
            plugins_url( 'assets/js/jquery.nice-select.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'jquery.event.move',
            plugins_url( 'assets/js/jquery.event.move.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'jquery.twentytwenty',
            plugins_url( 'assets/js/jquery.twentytwenty.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'imageLoaded',
            plugins_url( 'assets/js/imageLoaded.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'swiper',
            plugins_url( 'assets/js/swiper.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'wow',
            plugins_url( 'assets/js/wow.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'counterup',
            plugins_url( 'assets/js/counterup.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'scrollit',
            plugins_url( 'assets/js/scrollit.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'justifiedgallery',
            plugins_url( 'assets/js/justifiedGallery.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'parallaxie',
            plugins_url( 'assets/js/parallaxie.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'simpleParallax',
            plugins_url( 'assets/js/simpleParallax.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'isotope',
            plugins_url( 'assets/js/isotope.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-imgbox-slider',
            plugins_url( 'assets/js/imgbox-slider.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'splitting',
            plugins_url( 'assets/js/splitting.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'grouploop',
            plugins_url( 'assets/js/grouploop.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'heading-loop',
            plugins_url( 'assets/js/heading-loop.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'circle-progress',
            plugins_url( 'assets/js/circle-progress.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'bootstrap-js',
            plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'imagesloaded-pkgd',
            plugins_url( 'assets/js/imagesloaded.pkgd.min.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-lib',
            plugins_url( 'assets/js/plugins.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-nav',
            plugins_url( 'assets/js/navbar.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-animated-headline',
            plugins_url( 'assets/js/animated.headline.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-showcase',
            plugins_url( 'assets/js/showcase.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
        wp_register_script(
            'better-el-addons',
            plugins_url( 'assets/js/scripts.js', __FILE__ ),
            [ 'jquery' ],
            false,
            true
        );
    }
    
    public function widget_styles()
    {
        wp_enqueue_style(
            'better-Pinyon-script',
            '//fonts.googleapis.com/css2?family=Pinyon+Script&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-amatic',
            '//fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-barlow',
            '//fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-barlow-condensed',
            '//fonts.googleapis.com/css2?family=Barlow+Condensed:wght@200;300;400;500;600;700&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-poppins',
            '//fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-teko',
            '//fonts.googleapis.com/css2?family=Teko:wght@300;400;500;600;700&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'better-jost',
            '//fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap',
            array(),
            '20451215'
        );
        wp_enqueue_style(
            'pe-icon',
            plugin_dir_url( __FILE__ ) . 'assets/css/pe-icon-7-stroke.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'fontawesome',
            plugin_dir_url( __FILE__ ) . 'assets/css/fontawesome.min.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'bootstrap',
            plugin_dir_url( __FILE__ ) . 'assets/css/bootstrap.min.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'twentytwenty',
            plugin_dir_url( __FILE__ ) . 'assets/css/twentytwenty.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'animatecss',
            plugin_dir_url( __FILE__ ) . 'assets/css/animate.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'youtubepopup',
            plugin_dir_url( __FILE__ ) . 'assets/css/youtubepopup.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'niceselect',
            plugin_dir_url( __FILE__ ) . 'assets/css/nice-select.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'justifiedgallery',
            plugin_dir_url( __FILE__ ) . 'assets/css/justifiedgallery.min.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'slick-theme',
            plugin_dir_url( __FILE__ ) . 'assets/css/slick-theme.css',
            array(),
            '20200508'
        );
        wp_enqueue_style(
            'better-style',
            plugin_dir_url( __FILE__ ) . 'assets/style.css',
            array(),
            '20200508'
        );
    }
    
    /**
     * Include required files
     *
     * @since 1.3.0
     * @access private
     */
    private function includes()
    {
        require_once BEA_PLUGIN_DIR . 'inc/helper-functions.php';
        if ( is_admin() ) {
            require_once __DIR__ . '/admin/admin-init.php';
        }
    }
    
    /** 
     * Include Widgets files 
     *
     * Load widgets files
     *
     * @since 1.0.0
     * @access private
     */
    private function include_widgets_files()
    {
        foreach ( $this->basic_elements as $element_name ) {
            $element_name__ = str_replace( '-', '_', $element_name );
            ${'deactivate_element_' . $element_name__} = bea_get_option( 'bea_deactivate_element_' . $element_name__, false );
            if ( !${'deactivate_element_' . $element_name__} ) {
                require_once __DIR__ . '/widgets/' . $element_name . '/' . $element_name . '.php';
            }
        }
    }
    
    /**
     * Register Widgets
     *
     * Register new Elementor widgets.
     *
     * @since 1.0.0
     * @access public
     */
    public function register_widgets()
    {
        // Its is now safe to include Widgets files
        $this->include_widgets_files();
        // Register Widgets
        foreach ( $this->basic_elements as $element_name ) {
            $element_name__ = str_replace( '-', '_', $element_name );
            ${'deactivate_element_' . $element_name__} = bea_get_option( 'bea_deactivate_element_' . $element_name__, false );
            
            if ( !${'deactivate_element_' . $element_name__} ) {
                $class_name = str_replace( '_', ' ', $element_name__ );
                $class_name = ucwords( strtolower( $class_name ) );
                $class_name = str_replace( ' ', '_', $class_name );
                $class_name = 'BetterWidgets\\Widgets\\Better_' . $class_name;
                \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $class_name() );
            }
        
        }
    }
    
    /**
     *  Plugin class constructor
     *
     * Register plugin action hooks and filters
     *
     * @since 1.0.0
     * @access public
     */
    public function __construct()
    {
        // Register widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
        // Register Widget Styles
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
        // Register widgets
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
    }

}
// Instantiate Plugin Class
Plugin::instance();