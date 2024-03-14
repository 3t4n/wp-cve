<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.rosendo.pt
 * @since      1.0.0
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/public
 * @author     David Rosendo <david@rosendo.pt>
 */
class Smart_Variations_Images_Public
{
    /**
     * Contains an array of script handles registered by WC.
     *
     * @var array
     */
    public  $scripts = array() ;
    /**
     * Contains an array of script handles registered by WC.
     *
     * @var array
     */
    public  $styles = array() ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * The plugin options.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $wpsfsviOptions    The current plugin options.
     */
    private  $options ;
    protected  $runSitePress ;
    protected  $pid ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version, $wpsfsviOptions )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = $wpsfsviOptions;
        $this->options->template = wp_get_theme()->template;
        $this->pid = false;
        $this->runSitePress = false;
        //remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
    }
    
    function handleLoadCondition()
    {
        global  $post ;
        if ( $post ) {
            if ( function_exists( 'is_product' ) && is_product() || is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'product_page' ) || is_woocommerce() || has_shortcode( $post->post_content, 'product_category' ) || has_shortcode( $post->post_content, 'dt_products_carousel' ) ) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function load_scripts()
    {
        self::register_scripts();
        self::register_styles();
        self::enqueue_script( 'imagesloaded' );
        if ( !$this->handleLoadCondition() ) {
            return;
        }
        
        if ( property_exists( $this->options, 'slider' ) && ($this->options->slider || property_exists( $this->options, 'lightbox_thumbnails' ) && $this->options->lightbox_thumbnails && property_exists( $this->options, 'lightbox' ) && $this->options->lightbox) ) {
            self::enqueue_script( $this->plugin_name . '-swiper' );
            self::enqueue_style( $this->plugin_name . '-swiper' );
        }
        
        if ( $this->options->lens ) {
            self::enqueue_script( 'ezplus' );
        }
        
        if ( $this->options->lightbox ) {
            $handle = 'photoswipe' . SMART_SCRIPT_DEBUG . '.js';
            $list = 'enqueued';
            
            if ( !wp_script_is( $handle, $list ) ) {
                //JS
                self::enqueue_script( $this->plugin_name . '-photoswipe' );
                self::enqueue_script( $this->plugin_name . '-photoswipe-ui-default' );
                //STYLES
                self::enqueue_style( $this->plugin_name . '-photoswipe' );
                self::enqueue_style( $this->plugin_name . '-photoswipe-default-skin' );
            }
        
        }
        
        $this->loadMainFiles();
    }
    
    public function loadMainFiles()
    {
        self::enqueue_script( $this->plugin_name . '-manifest' );
        self::enqueue_script( $this->plugin_name . '-vendor' );
        self::enqueue_script( $this->plugin_name );
        self::enqueue_style( $this->plugin_name );
        wp_localize_script( $this->plugin_name, 'wcsvi', array(
            'prod'    => ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? false : true ),
            'options' => $this->options,
            'call'    => admin_url( 'admin-ajax.php' ),
            'version' => $this->version,
        ) );
    }
    
    /**
     * Register a script for use.
     *
     * @uses   wp_register_script()
     * @param  string   $handle    Name of the script. Should be unique.
     * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
     * @param  string[] $deps      An array of registered script handles this script depends on.
     * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
     * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
     */
    public function register_script(
        $handle,
        $path,
        $deps = array( 'jquery' ),
        $version = '',
        $in_footer = true
    )
    {
        $this->scripts[] = $handle;
        wp_register_script(
            $handle,
            $path,
            $deps,
            $version,
            $in_footer
        );
    }
    
    /**
     * Register and enqueue a script for use.
     *
     * @uses   wp_enqueue_script()
     * @param  string   $handle    Name of the script. Should be unique.
     * @param  string   $path      Full URL of the script, or path of the script relative to the WordPress root directory.
     * @param  string[] $deps      An array of registered script handles this script depends on.
     * @param  string   $version   String specifying script version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
     * @param  boolean  $in_footer Whether to enqueue the script before </body> instead of in the <head>. Default 'false'.
     */
    public function enqueue_script(
        $handle,
        $path = '',
        $deps = array( 'jquery' ),
        $version = '',
        $in_footer = true
    )
    {
        if ( !in_array( $handle, $this->scripts, true ) && $path ) {
            self::register_script(
                $handle,
                $path,
                $deps,
                $version,
                $in_footer
            );
        }
        wp_enqueue_script( $handle );
    }
    
    /**
     * Register a style for use.
     *
     * @uses   wp_register_style()
     * @param  string   $handle  Name of the stylesheet. Should be unique.
     * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
     * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
     * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
     * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
     * @param  boolean  $has_rtl If has RTL version to load too.
     */
    public function register_style(
        $handle,
        $path,
        $deps = array(),
        $version = '',
        $media = 'all',
        $has_rtl = false
    )
    {
        $this->styles[] = $handle;
        wp_register_style(
            $handle,
            $path,
            $deps,
            $version,
            $media
        );
        if ( $has_rtl ) {
            wp_style_add_data( $handle, 'rtl', 'replace' );
        }
    }
    
    /**
     * Register and enqueue a styles for use.
     *
     * @uses   wp_enqueue_style()
     * @param  string   $handle  Name of the stylesheet. Should be unique.
     * @param  string   $path    Full URL of the stylesheet, or path of the stylesheet relative to the WordPress root directory.
     * @param  string[] $deps    An array of registered stylesheet handles this stylesheet depends on.
     * @param  string   $version String specifying stylesheet version number, if it has one, which is added to the URL as a query string for cache busting purposes. If version is set to false, a version number is automatically added equal to current installed WordPress version. If set to null, no version is added.
     * @param  string   $media   The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.
     * @param  boolean  $has_rtl If has RTL version to load too.
     */
    public function enqueue_style(
        $handle,
        $path = '',
        $deps = array(),
        $version = '',
        $media = 'all',
        $has_rtl = false
    )
    {
        if ( !in_array( $handle, $this->styles, true ) && $path ) {
            self::register_style(
                $handle,
                $path,
                $deps,
                $version,
                $media,
                $has_rtl
            );
        }
        wp_enqueue_style( $handle );
    }
    
    /**
     * Register all WC scripts.
     */
    public function register_scripts()
    {
        $version = $this->version;
        $register_scripts = array(
            'imagesloaded'                                => array(
            'src'     => '//unpkg.com/imagesloaded@4/imagesloaded.pkgd' . SMART_SCRIPT_DEBUG . '.js',
            'deps'    => array(),
            'version' => $version,
        ),
            $this->plugin_name . '-swiper'                => array(
            'src'     => '//cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
            'deps'    => array(),
            'version' => null,
        ),
            'ezplus'                                      => array(
            'src'     => self::get_asset_url( 'js/jquery.ez-plus' . SMART_SCRIPT_DEBUG . '.js' ),
            'deps'    => array( 'jquery' ),
            'version' => $version,
        ),
            $this->plugin_name . '-photoswipe'            => array(
            'src'     => '//cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe' . SMART_SCRIPT_DEBUG . '.js',
            'deps'    => array(),
            'version' => '4.1.3',
        ),
            $this->plugin_name . '-photoswipe-ui-default' => array(
            'src'     => '//cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe-ui-default' . SMART_SCRIPT_DEBUG . '.js',
            'deps'    => array( $this->plugin_name . '-photoswipe' ),
            'version' => '4.1.3',
        ),
            $this->plugin_name . '-manifest'              => array(
            'src'     => self::get_asset_url( 'js/manifest' . SMART_SCRIPT_DEBUG . '.js' ),
            'deps'    => array( 'jquery' ),
            'version' => $version,
        ),
            $this->plugin_name . '-vendor'                => array(
            'src'     => self::get_asset_url( 'js/vendor' . SMART_SCRIPT_DEBUG . '.js' ),
            'deps'    => array( $this->plugin_name . '-manifest' ),
            'version' => $version,
        ),
        );
        $register_scripts[$this->plugin_name] = array(
            'src'     => self::get_asset_url( 'js/smart-variations-images-public' . SMART_SCRIPT_DEBUG . '.js' ),
            'deps'    => array( 'jquery', $this->plugin_name . '-vendor', 'imagesloaded' ),
            'version' => $version,
        );
        
        if ( $this->options->lightbox ) {
            $handle = 'photoswipe' . SMART_SCRIPT_DEBUG . '.js';
            $list = 'enqueued';
            $register_scripts[$this->plugin_name] = array(
                'src'     => self::get_asset_url( 'js/smart-variations-images-public' . SMART_SCRIPT_DEBUG . '.js' ),
                'deps'    => array(
                'jquery',
                $this->plugin_name . '-vendor',
                'imagesloaded',
                'photoswipe',
                'photoswipe-ui-default'
            ),
                'version' => $version,
            );
            if ( !wp_script_is( $handle, $list ) ) {
                $register_scripts[$this->plugin_name] = array(
                    'src'     => self::get_asset_url( 'js/smart-variations-images-public' . SMART_SCRIPT_DEBUG . '.js' ),
                    'deps'    => array(
                    'jquery',
                    $this->plugin_name . '-vendor',
                    'imagesloaded',
                    $this->plugin_name . '-photoswipe',
                    $this->plugin_name . '-photoswipe-ui-default'
                ),
                    'version' => $version,
                );
            }
        }
        
        foreach ( $register_scripts as $name => $props ) {
            self::register_script(
                $name,
                $props['src'],
                $props['deps'],
                $props['version']
            );
        }
    }
    
    /**
     * Register all WC sty;es.
     */
    public function register_styles()
    {
        $version = $this->version;
        $register_styles = array(
            $this->plugin_name                              => array(
            'src'     => self::get_asset_url( 'css/smart-variations-images-public' . SMART_SCRIPT_DEBUG . '.css' ),
            'deps'    => array(),
            'version' => $version,
            'has_rtl' => false,
        ),
            $this->plugin_name . '-swiper'                  => array(
            'src'     => '//cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
            'deps'    => array(),
            'version' => null,
            'has_rtl' => false,
        ),
            $this->plugin_name . '-photoswipe'              => array(
            'src'     => '//cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe' . SMART_SCRIPT_DEBUG . '.css',
            'deps'    => array(),
            'version' => $version,
            'has_rtl' => false,
        ),
            $this->plugin_name . '-photoswipe-default-skin' => array(
            'src'     => '//cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/default-skin/default-skin' . SMART_SCRIPT_DEBUG . '.css',
            'deps'    => array( $this->plugin_name . '-photoswipe' ),
            'version' => $version,
            'has_rtl' => false,
        ),
        );
        foreach ( $register_styles as $name => $props ) {
            self::register_style(
                $name,
                $props['src'],
                $props['deps'],
                $props['version'],
                'all',
                $props['has_rtl']
            );
        }
    }
    
    /**
     * Return asset URL.
     *
     * @param string $path Assets path.
     * @return string
     */
    private static function get_asset_url( $path )
    {
        return plugins_url( $path, __FILE__ );
    }
    
    /**
     * Remove hooks for plugin to work properly
     *
     * @since 1.1.1
     * @return instance object
     */
    public function remove_hooks()
    {
        global  $product ;
        $run = true;
        if ( is_object( $product ) ) {
            $run = $this->validate_run( $product );
        }
        if ( !$run ) {
            return;
        }
        //if ($this->runsvi) {
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
        remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
        // Mr. Tailor
        remove_action( 'woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_images', 20 );
        remove_action( 'woocommerce_product_summary_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
        //WOWMALL
        //remove_action('woocommerce_before_single_product_summary', 'wowmall_woocommerce_show_product_images', 20);
        //wp_deregister_script('wowmall-wc-single-product-gallery');
        //wp_deregister_script('single-product-lightbox');
        //Electro support
        remove_action( 'woocommerce_before_single_product_summary', 'electro_show_product_images', 20 );
        //AURUM support
        remove_action( 'woocommerce_before_single_product_summary', 'aurum_woocommerce_show_product_images', 25 );
        // Remove images from Bazar theme
        
        if ( class_exists( 'YITH_WCMG' ) ) {
            $this->remove_filters_for_anonymous_class(
                'woocommerce_before_single_product_summary',
                'YITH_WCMG_Frontend',
                'show_product_images',
                20
            );
            $this->remove_filters_for_anonymous_class(
                'woocommerce_product_thumbnails',
                'YITH_WCMG_Frontend',
                'show_product_thumbnails',
                20
            );
        }
    
    }
    
    /**
     * Remove hooks/filters after theme setup for plugin to work properly
     *
     * @since 1.1.1
     * @return instance object
     */
    public function after_setup_theme()
    {
        if ( class_exists( 'Razzi\\Theme' ) ) {
            add_filter( 'razzi_product_gallery_is_slider', function ( $data ) {
                return 0;
            }, 9999 );
        }
    }
    
    /**
     * Render frontend for Builders
     */
    public function filter_wc_get_template(
        $located,
        $template_name,
        $args,
        $template_path,
        $default_path
    )
    {
        // make filter magic happen here...
        global  $product ;
        if ( !is_object( $product ) || defined( 'DOING_AJAX' ) && !$this->options->quick_view ) {
            return $located;
        }
        $run = true;
        if ( is_object( $product ) ) {
            $run = $this->validate_run( $product );
        }
        if ( !$run ) {
            return $located;
        }
        $theme_file = 'single-product/product-image.php';
        //var_dump($theme_file);
        
        if ( $this->options->template == 'flatsome' ) {
            add_filter(
                'woocommerce_single_product_image_thumbnail_html',
                '__return_empty_string',
                10,
                2
            );
            $theme_file = 'woocommerce/single-product/product-gallery-thumbnails.php';
        }
        
        
        if ( $this->options->template == 'porto' ) {
            add_filter(
                'woocommerce_single_product_image_thumbnail_html',
                '__return_empty_string',
                10,
                2
            );
            add_filter(
                'woocommerce_single_product_image_html',
                '__return_empty_string',
                10,
                2
            );
            $theme_file = 'single-product/product-thumbnails.php';
        }
        
        
        if ( $template_name == $theme_file ) {
            ob_start();
            include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/smart-variations-images-public-display.php';
            $return = ob_get_clean();
            echo  $return ;
        } else {
            return $located;
        }
    
    }
    
    /**
     * Render frontend Shortcode for gallery display
     */
    public function render_sc_frontend()
    {
        ob_start();
        include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/smart-variations-images-public-display.php';
        $output_string = ob_get_contents();
        ob_end_clean();
        return $output_string;
    }
    
    /**
     * Render frontend app
     */
    public function render_frontend()
    {
        global  $product ;
        if ( $this->options->template == 'Divi' && $this->validate_runningDivi( $product ) && !wp_doing_ajax() ) {
            return;
        }
        $run = true;
        if ( is_object( $product ) ) {
            $run = $this->validate_run( $product );
        }
        if ( !$run ) {
            return;
        }
        include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/smart-variations-images-public-display.php';
    }
    
    /**
     * Render frontend quick_view
     */
    public function render_quick_view_frontend()
    {
        global  $product ;
        $product = wc_get_product( intval( $_POST['id'] ) );
        ob_start();
        include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/smart-variations-images-public-display.php';
        $return = ob_get_clean();
        header( "Content-type: text/html" );
        echo  $return ;
        die;
    }
    
    /**
     * Render Showcase Variations under attributes/swatches
     */
    public function render_before_add_to_cart_button()
    {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/smart-variations-images-public-display-drop.php';
    }
    
    /**
     * Check that slug data is not corrupt or missing
     *
     * @since 1.1.1
     * @return array
     */
    public function validateSlugs(
        $woosvi_slug,
        $product,
        $pid,
        $theslugs
    )
    {
        if ( $product->is_type( 'variable' ) ) {
            foreach ( $woosvi_slug as $k => $v ) {
                if ( array_key_exists( 'slugs', $v ) ) {
                    foreach ( $v['slugs'] as $k2 => $slug ) {
                        
                        if ( !array_key_exists( $slug, $theslugs ) ) {
                            $bigger = 95;
                            foreach ( $theslugs as $extra => $check ) {
                                $sim = similar_text( $extra, $slug, $perc );
                                
                                if ( $perc > $bigger ) {
                                    $bigger = $perc;
                                    $woosvi_slug[$k]['slugs'][$k2] = trim( $extra );
                                    update_post_meta( $pid, 'woosvi_slug', $woosvi_slug );
                                }
                            
                            }
                        }
                    
                    }
                }
            }
        }
        return $woosvi_slug;
    }
    
    /**
     * Return the product information to be displayed via AJAX
     *
     * @since 1.1.1
     * @return instance object
     */
    public function loadProductAjax()
    {
        header( "Content-type: application/json" );
        echo  json_encode( $this->loadProduct( $_POST['id'] ) ) ;
        die;
    }
    
    /**
     * Return the product information to be displayed
     *
     * @since 1.1.1
     * @return instance object
     */
    public function loadProduct( $pid = false, $translateSlugs = false )
    {
        $return = array();
        
        if ( $pid ) {
            $this->pid = $pid;
        } else {
            $data = json_decode( file_get_contents( "php://input" ), true );
            $this->pid = intval( $data['id'] );
        }
        
        $original_pid = $this->pid;
        if ( class_exists( 'SitePress' ) && !$this->runSitePress ) {
            $this->pid = $this->wpml_original( $this->pid );
        }
        $product = wc_get_product( $this->pid );
        $default_img = $product->get_image_id();
        $attachment_ids = array( $default_img );
        $woosvi_slug = get_post_meta( $this->pid, 'woosvi_slug', true );
        
        if ( empty($woosvi_slug) ) {
            $this->fallback();
            $woosvi_slug = get_post_meta( $this->pid, 'woosvi_slug', true );
        }
        
        
        if ( !is_array( $woosvi_slug ) ) {
            $woosvi_slug = [];
        } else {
            $attributes = get_post_meta( $this->pid, '_product_attributes' );
            // GET CURRENT PRODUCT ATTRIBUTES
            $theslugs = $this->getAttributes( $attributes, $this->pid );
            $return['slugs'] = $theslugs;
            $woosvi_slug = $this->validateSlugs(
                $woosvi_slug,
                $product,
                $this->pid,
                $theslugs
            );
            
            if ( class_exists( 'SitePress' ) && !$this->runSitePress && $product->is_type( 'variable' ) && $original_pid != $this->pid ) {
                $return['slugs'] = $this->wpml( $original_pid, $product, $this->pid );
                if ( $translateSlugs ) {
                    $woosvi_slug = $this->translateSlugs( $woosvi_slug, $return['slugs'] );
                }
            }
        
        }
        
        $attachment_ids = array_merge( $attachment_ids, $product->get_gallery_image_ids() );
        
        if ( !$product->is_type( 'variable' ) || empty($woosvi_slug) ) {
            $attachment_ids = array_unique( $attachment_ids );
            $attachment_ids = array_values( array_filter( $attachment_ids ) );
        } else {
            $attachment_ids = array_merge( $attachment_ids, $this->get_svigallery_image_ids( $woosvi_slug ) );
        }
        
        $attachment_ids = array_filter( $attachment_ids );
        foreach ( $woosvi_slug as $k => $v ) {
            
            if ( array_key_exists( 'slugs', $v ) ) {
                if ( $v['slugs'][0] == 'svidefault' && $default_img ) {
                    array_unshift( $woosvi_slug[$k]['imgs'], $default_img );
                }
                $woosvi_slug[$k]['slugs'] = array_map( 'strtolower', $woosvi_slug[$k]['slugs'] );
            }
        
        }
        $found_main = false;
        if ( $attachment_ids ) {
            foreach ( $attachment_ids as $k => $attachment_id ) {
                $video = '';
                if ( is_array( $attachment_id ) ) {
                    $attachment_id = $attachment_id['id'];
                }
                $attachment_id = explode( 'k', $attachment_id );
                
                if ( count( $attachment_id ) > 1 ) {
                    $thek = $attachment_id[1];
                    $attachment_id = $attachment_id[0];
                } else {
                    $thek = false;
                    $attachment_id = $attachment_id[0];
                }
                
                
                if ( $product->is_type( 'variable' ) ) {
                    $gotvideo = '';
                    $img_data = array_merge( array(
                        'id'          => intval( $attachment_id ),
                        'idk'         => $thek,
                        'video'       => $gotvideo,
                        'product_img' => ( $default_img == $attachment_id ? true : false ),
                    ), $this->getMainImage( $attachment_id ) );
                } else {
                    $gotvideo = '';
                    $img_data = array_merge( array(
                        'id'          => intval( $attachment_id ),
                        'idk'         => $thek,
                        'video'       => $gotvideo,
                        'product_img' => ( $default_img == $attachment_id ? true : false ),
                    ), $this->getMainImage( $attachment_id ) );
                }
                
                $return['images'][] = apply_filters( 'svi_image', $img_data );
            }
        }
        $return['svi'] = $woosvi_slug;
        if ( $pid ) {
            return $return;
        }
        header( "Content-type: application/json" );
        echo  json_encode( $return ) ;
        die;
    }
    
    /**
     * Returns the images id in the orders they exist in Product
     *
     *
     * @since 1.0.0
     * @return
     */
    public function get_svigallery_image_ids( $woosvi_slug )
    {
        $imgs = array();
        foreach ( $woosvi_slug as $k => $v ) {
            foreach ( $v['imgs'] as $imgID ) {
                if ( is_array( $imgID ) ) {
                    continue;
                }
                
                if ( array_key_exists( 'video', $v ) && array_key_exists( $imgID, $v['video'] ) ) {
                    array_push( $imgs, [
                        'id'    => $imgID . 'k' . $k,
                        'video' => $v['video'][$imgID],
                    ] );
                } else {
                    
                    if ( array_key_exists( 'video', $v ) ) {
                        if ( !array_key_exists( 'wc_svimainvideo', $v['video'] ) ) {
                            array_push( $imgs, $imgID . 'k' . $k );
                        }
                    } else {
                        array_push( $imgs, $imgID . 'k' . $k );
                    }
                
                }
            
            }
        }
        return $imgs;
    }
    
    /**
     * Runs the fallback and saves the data
     *
     *
     * @since 1.0.0
     * @return
     */
    public function fallback()
    {
        $product_image_gallery = array();
        
        if ( metadata_exists( 'post', $this->pid, '_product_image_gallery' ) ) {
            $product_image_gallery = explode( ',', get_post_meta( $this->pid, '_product_image_gallery', true ) );
        } else {
            // Backwards compat
            $attachment_ids = get_posts( 'post_parent=' . $this->pid . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0' );
            $attachment_ids = array_diff( $attachment_ids, array( get_post_thumbnail_id() ) );
            if ( is_array( $attachment_ids ) && count( $attachment_ids ) > 0 ) {
                $product_image_gallery = $attachment_ids;
            }
        }
        
        if ( !is_array( $product_image_gallery ) || count( $product_image_gallery ) < 1 ) {
            return;
        }
        $product_image_gallery = array_filter( $product_image_gallery );
        $order = array();
        foreach ( $product_image_gallery as $key => $value ) {
            $woosvi_slug = get_post_meta( $value, 'woosvi_slug_' . $this->pid, true );
            
            if ( is_array( $woosvi_slug ) ) {
                $data = array();
                foreach ( $woosvi_slug as $k => $v ) {
                    
                    if ( count( $v ) > 1 ) {
                        $data[] = strtolower( implode( '_svipro_', $v ) );
                    } else {
                        $data[] = strtolower( $v );
                    }
                
                }
                $woosvi_slug = $data;
            }
            
            if ( !$woosvi_slug ) {
                $woosvi_slug = get_post_meta( $value, 'woosvi_slug', true );
            }
            if ( !$woosvi_slug ) {
                $woosvi_slug = 'nullsvi';
            }
            
            if ( is_array( $woosvi_slug ) ) {
                foreach ( $woosvi_slug as $k => $v ) {
                    
                    if ( is_array( $v ) ) {
                        $order[$v[0]][] = $value;
                    } else {
                        $order[$v][] = $value;
                    }
                
                }
            } else {
                $order[$woosvi_slug][] = $value;
            }
        
        }
        unset( $order['nullsvi'] );
        $ordered = array();
        foreach ( $order as $k => $v ) {
            $arr = array(
                'slugs' => explode( '_svipro_', $k ),
                'imgs'  => $v,
            );
            array_push( $ordered, $arr );
        }
        update_post_meta( $this->pid, 'woosvi_slug', $ordered );
    }
    
    /**
     * Returns the specific image data
     *
     *
     * @since 1.0.0
     * @return array
     */
    public function getMainImage( $attachment_id )
    {
        $full_size = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
        $thumb_size = apply_filters( 'woocommerce_gallery_thumbnail_size', apply_filters( 'woocommerce_thumbnail_size', 'shop_thumbnail' ) );
        $image_size = apply_filters( 'woocommerce_gallery_image_size', ( $this->options->main_imagesize ? $this->options->main_imagesize : $full_size ) );
        $image_thumbsize = apply_filters( 'woocommerce_gallery_thumbnail_size', ( $this->options->thumb_imagesize ? $this->options->thumb_imagesize : $thumb_size ) );
        $thumbnail_src = wp_get_attachment_image_src( $attachment_id, $this->options->thumb_imagesize );
        $large_image = wp_get_attachment_image_src( $attachment_id, $image_size );
        $image_src = wp_get_attachment_image_src( $attachment_id, $full_size );
        $image_full = wp_get_attachment_image_src( $attachment_id, $full_size );
        $thumb_info = $this->imgtagger( wp_get_attachment_image( $attachment_id, $image_thumbsize, false ) );
        $img = $this->imgtagger( wp_get_attachment_image(
            $attachment_id,
            $image_size,
            false,
            array(
            'title'                   => get_the_title( $attachment_id ),
            'data-caption'            => wp_get_attachment_caption( $attachment_id ),
            'data-src'                => $image_src[0],
            'data-large_image'        => $large_image[0],
            'data-large_image_width'  => $large_image[1],
            'data-large_image_height' => $large_image[2],
            'data-thumb_image'        => $thumbnail_src[0],
            'data-thumb_image_width'  => $thumbnail_src[1],
            'data-thumb_image_height' => $thumbnail_src[2],
        )
        ) );
        $full_img_sizes = array(
            'full_image'        => $image_full[0],
            'full_image_width'  => $image_full[1],
            'full_image_height' => $image_full[2],
            'thumb_class'       => ( array_key_exists( 'class', $thumb_info ) ? $thumb_info['class'] : 'size-' . $image_thumbsize ),
        );
        $img = array_merge( $img, $full_img_sizes );
        return $img;
    }
    
    /**
     * Returns the specific image to be displayed
     *
     *
     * @since 1.0.0
     * @return HTML
     */
    public function returnImage( $attachment_id )
    {
        $full_size = apply_filters( 'woocommerce_gallery_full_size', ( property_exists( $this->options, 'showcase_imagesize' ) && $this->options->showcase_imagesize ? $this->options->showcase_imagesize : (( $this->options->main_imagesize ? $this->options->main_imagesize : $this->options->thumb_imagesize )) ) );
        $image_size = apply_filters( 'woocommerce_gallery_image_size', ( $this->options->main_imagesize ? $this->options->main_imagesize : $full_size ) );
        $thumbnail_src = wp_get_attachment_image_src( $attachment_id, $this->options->thumb_imagesize );
        $large_image = wp_get_attachment_image_src( $attachment_id, $image_size );
        $image_src = wp_get_attachment_image_src( $attachment_id, $full_size );
        return wp_get_attachment_image(
            $attachment_id,
            $full_size,
            false,
            array(
            'title'                   => get_the_title( $attachment_id ),
            'data-caption'            => wp_get_attachment_caption( $attachment_id ),
            'data-src'                => $image_src[0],
            'data-large_image'        => $large_image[0],
            'data-large_image_width'  => $large_image[1],
            'data-large_image_height' => $large_image[2],
            'data-thumb_image'        => $thumbnail_src[0],
            'data-thumb_image_width'  => $thumbnail_src[1],
            'data-thumb_image_height' => $thumbnail_src[2],
            'class'                   => 'svitn_img attachment-svi-icon size-svi-icon',
        )
        );
    }
    
    /**
     * Break images tags to array to be used
     *
     * @since 1.0.0
     * @return array
     */
    public function imgtagger( $fullimg_tag )
    {
        preg_match_all( '/(alt|title|src|caption|woosvislug|svizoom-image|srcset|title|sizes|width|height|class|thumb_image|thumb_image_width|thumb_image_height|large_image|large_image_width|large_image_height)=("[^"]*")/i', $fullimg_tag, $fullimg_split );
        foreach ( $fullimg_split[2] as $key => $value ) {
            
            if ( $value == '""' ) {
                $fullimg_split[2][$key] = "";
            } else {
                $fullimg_split[2][$key] = str_replace( '"', "", $value );
            }
        
        }
        return array_combine( $fullimg_split[1], $fullimg_split[2] );
    }
    
    /**
     * Builds Product loop to display SVI galleries
     *
     * @since 1.0.0
     * @return array
     */
    public function svi_product_tn_images()
    {
        global  $product ;
        if ( !is_object( $product ) ) {
            return;
        }
        $this->loadMainFiles();
        $data = $this->loadProduct( $product->get_id(), true )['svi'];
        //svi_pre($data);
        if ( svi_fs()->is_free_plan() ) {
            $data = array_slice( $data, 0, 2 );
        }
        $get = [];
        foreach ( $data as $variation => $img ) {
            $get[] = $img['imgs'][0];
        }
        $get = array_unique( $get );
        
        if ( !empty($get) ) {
            echo  ' <div class="svitn_wrapper">' ;
            foreach ( $get as $k => $img ) {
                echo  $this->returnImage( $img ) ;
            }
            echo  ' </div>' ;
        }
    
    }
    
    /**
     * Return SVI galleries autorized to be displayed
     *
     * @since 1.0.0
     * @return array
     */
    public function getAvailableLoopGalleries( $data )
    {
        $gals = array();
        foreach ( $data as $k => $v ) {
            if ( array_key_exists( 'loop_hidden', $v ) && !$v['loop_hidden'] ) {
                $gals[] = $v;
            }
        }
        return $gals;
    }
    
    function strposX( $haystack, $needle, $number )
    {
        preg_match_all(
            "/{$needle}/",
            utf8_decode( $haystack ),
            $matches,
            PREG_OFFSET_CAPTURE
        );
        return $matches[0][$number - 1][1];
    }
    
    /**
     * Returns the most probable image
     *
     * @since 1.0.0
     * @return array
     */
    public function findSimilar(
        $img,
        $slugs_confirm,
        $data,
        $product,
        $html = false
    )
    {
        $found = false;
        
        if ( is_array( $slugs_confirm ) || is_object( $slugs_confirm ) ) {
            foreach ( $slugs_confirm as $index => $slug ) {
                
                if ( array_key_exists( $slug, $data ) ) {
                    $img_id = filter_var( $data[$slug], FILTER_SANITIZE_NUMBER_INT );
                    
                    if ( $html ) {
                        $dom = new \DOMDocument();
                        $dom->loadHTML( $img );
                        $img = '<div style="margin-bottom: 5px"><img src="' . (( $img_id ? current( wp_get_attachment_image_src( $img_id, 'thumbnail' ) ) : wc_placeholder_img_src() )) . '" alt="' . esc_attr__( 'Product image', 'woocommerce' ) . '" height="' . esc_attr( $dom->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'height' ) ) . '" width="' . esc_attr( $dom->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'width' ) ) . '" style="vertical-align:middle; margin-' . (( is_rtl() ? 'left' : 'right' )) . ': 10px;" /></div>';
                    } else {
                        $image_title = $product->get_title();
                        $img = wp_get_attachment_image(
                            $img_id,
                            apply_filters( 'single_product_small_thumbnail_size', $this->options->thumb_imagesize ),
                            0,
                            $attr = array(
                            'title' => $image_title,
                            'alt'   => $image_title,
                        )
                        );
                    }
                    
                    $found = true;
                    break;
                }
            
            }
            
            if ( !$found ) {
                $bigger = 0;
                foreach ( $slugs_confirm as $index => $slug ) {
                    foreach ( $data as $key => $img_k ) {
                        $sim = similar_text( $slug, $key, $perc );
                        
                        if ( $perc > $bigger && $perc > 70 ) {
                            $bigger = $perc;
                            $img_id = filter_var( $img_k, FILTER_SANITIZE_NUMBER_INT );
                            
                            if ( $html ) {
                                $dom = new \DOMDocument();
                                $dom->loadHTML( $img );
                                $img = '<div style="margin-bottom: 5px"><img src="' . (( $img_id ? current( wp_get_attachment_image_src( $img_id, 'thumbnail' ) ) : wc_placeholder_img_src() )) . '" alt="' . esc_attr__( 'Product image', 'woocommerce' ) . '" height="' . esc_attr( $dom->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'height' ) ) . '" width="' . esc_attr( $dom->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'width' ) ) . '" style="vertical-align:middle; margin-' . (( is_rtl() ? 'left' : 'right' )) . ': 10px;" /></div>';
                            } else {
                                $image_title = $product->get_title();
                                $img = wp_get_attachment_image(
                                    $img_id,
                                    apply_filters( 'single_product_small_thumbnail_size', $this->options->thumb_imagesize ),
                                    0,
                                    $attr = array(
                                    'title' => $image_title,
                                    'alt'   => $image_title,
                                )
                                );
                            }
                            
                            break;
                        }
                    
                    }
                }
            }
        
        }
        
        return $img;
    }
    
    /**
     * Check if product is autorized to load SVI
     *
     * @since 1.0.0
     * @return bool
     */
    public function validate_run( $product )
    {
        $woosvi_slug = get_post_meta( $product->get_id(), 'woosvi_slug', true );
        $run = get_post_meta( $product->get_id(), '_checkbox_svipro_enabled', true );
        if ( $run == 'yes' ) {
            return false;
        }
        if ( property_exists( $this->options, 'svi_disabled_woosvislug' ) && $this->options->svi_disabled_woosvislug && empty($woosvi_slug) ) {
            return false;
        }
        return true;
    }
    
    /**
     * Check if page is running DIVI or DIVI template
     *
     * @since 1.0.0
     * @return bool
     */
    public function validate_runningDivi( $product )
    {
        $post_content = get_post( $product->get_id() );
        $content = $post_content->post_content;
        $pos = strpos( $content, 'et_pb_wc_images' );
        $pos2 = strpos( $content, 'et_pb_wc_gallery' );
        if ( $pos !== false || $pos2 !== false ) {
            return true;
        }
        
        if ( class_exists( 'ET_Theme_Builder_Request' ) ) {
            $tb_layouts = et_theme_builder_get_template_layouts( ET_Theme_Builder_Request::from_post( $product->get_id() ) );
            
            if ( !empty($tb_layouts) && $tb_layouts[ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE]['override'] ) {
                $templateContent = get_the_content( null, false, $tb_layouts[ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE]['id'] );
                $pos = strpos( $templateContent, 'et_pb_wc_images' );
                $pos2 = strpos( $templateContent, 'et_pb_wc_gallery' );
                if ( $pos !== false || $pos2 !== false ) {
                    return true;
                }
            }
        
        }
        
        return false;
    }
    
    /**
     * Sanitize text
     *
     * @since 1.0.0
     * @return json
     */
    public function woosvi_slugify()
    {
        header( "Content-type: application/json" );
        echo  json_encode( sanitize_title( strtolower( $_POST['data'] ) ) ) ;
        die;
    }
    
    /**
     * Returns an array with available attributes
     *
     *
     * @since 1.0.0
     * @return array
     */
    public function getAttributes( $attributes, $pid )
    {
        $data = array();
        if ( count( $attributes ) > 0 ) {
            foreach ( $attributes[0] as $att => $attribute ) {
                
                if ( $attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    $terms = wp_get_post_terms( $pid, urldecode( $att ), 'all' );
                    if ( !empty($terms) ) {
                        foreach ( $terms as $tr => $term ) {
                            $data[strtolower( esc_attr( $term->slug ) )] = trim( esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name ) ) );
                        }
                    }
                } elseif ( !$attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    $terms = explode( '|', $attribute['value'] );
                    foreach ( $terms as $tr => $term ) {
                        $data[sanitize_title( $term )] = trim( esc_html( apply_filters( 'woocommerce_variation_option_name', $term ) ) );
                    }
                }
            
            }
        }
        return array_filter( $data );
    }
    
    /**
     * Get original language ID
     *
     * @since 1.0.0
     * @return int
     */
    public function wpml_original( $id )
    {
        global  $wpdb ;
        $orig_lang_id = $wpdb->get_var( "SELECT trans2.element_id FROM {$wpdb->prefix}icl_translations AS trans1 INNER JOIN {$wpdb->prefix}icl_translations AS trans2 ON trans2.trid = trans1.trid WHERE trans1.element_id = " . $id . " AND trans2.source_language_code IS NULL" );
        
        if ( is_null( $orig_lang_id ) ) {
            return $id;
        } else {
            return $orig_lang_id;
        }
    
    }
    
    /**
     * Get translated Slugs
     *
     * @since 1.0.0
     * @return array
     */
    public function wpml( $pid, $product, $original )
    {
        global  $sitepress ;
        if ( $product instanceof WC_Product && !$product->is_type( 'variable' ) ) {
            return false;
        }
        $slugs = array();
        $attributes = get_post_meta( $pid, '_product_attributes' );
        if ( !empty($attributes) ) {
            foreach ( $attributes[0] as $att => $attribute ) {
                
                if ( $attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    $valid_attr = esc_attr( $att );
                    $terms = wp_get_post_terms( $pid, $valid_attr, 'all' );
                    
                    if ( is_wp_error( $terms ) ) {
                        $valid_attr = esc_attr( $attribute['name'] );
                        $terms = wp_get_post_terms( $pid, $valid_attr, 'all' );
                    }
                    
                    foreach ( $terms as $tr => $term ) {
                        remove_filter(
                            'get_term',
                            array( $sitepress, 'get_term_adjust_id' ),
                            1,
                            1
                        );
                        $gtb = get_term( icl_object_id(
                            $term->term_id,
                            $valid_attr,
                            true,
                            $sitepress->get_default_language()
                        ) );
                        $slugs[strtolower( esc_attr( $gtb->slug ) )] = esc_attr( $term->slug );
                        add_filter(
                            'get_term',
                            array( $sitepress, 'get_term_adjust_id' ),
                            1,
                            1
                        );
                    }
                }
            
            }
        }
        $attributes_original = get_post_meta( $original, '_product_attributes' );
        if ( !empty($attributes_original) ) {
            foreach ( $attributes_original[0] as $att => $attribute ) {
                if ( !$attribute['is_taxonomy'] && $attribute['is_variation'] ) {
                    
                    if ( array_key_exists( $att, $attributes[0] ) ) {
                        $values = $attributes[0][$att]['value'];
                        
                        if ( !empty($values) ) {
                            $terms = explode( '|', $values );
                            $terms_original = explode( '|', $attribute['value'] );
                            foreach ( $terms_original as $tr => $term ) {
                                $slugs[sanitize_title( $term )] = trim( esc_attr( $terms[$tr] ) );
                            }
                        }
                    
                    }
                
                }
            }
        }
        return $slugs;
    }
    
    /**
     * Get translated ID
     *
     * @since 1.0.0
     * @return array
     */
    public function wpml_ids( $id )
    {
        global  $wpdb ;
        $translations = false;
        $trid = $wpdb->get_var( "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = " . $id . " AND source_language_code IS NULL" );
        
        if ( $trid > 0 ) {
            $translations = $wpdb->get_results( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = " . $trid . " AND source_language_code IS NOT NULL" );
        } else {
            return false;
        }
        
        
        if ( $translations ) {
            $ids = [];
            foreach ( $translations as $k => $v ) {
                $ids[] = $v->element_id;
            }
            return $ids;
        } else {
            return false;
        }
    
    }
    
    /**
     * Get translated  strings
     *
     * @since 1.0.0
     * @return array
     */
    public function translateSlugs( $woosvi_slug, $wpml_slugs )
    {
        foreach ( $woosvi_slug as $k => $v ) {
            if ( array_key_exists( 'slugs', $v ) ) {
                foreach ( $v['slugs'] as $k2 => $slug ) {
                    if ( array_key_exists( $slug, $wpml_slugs ) ) {
                        $woosvi_slug[$k]['slugs'][$k2] = trim( $wpml_slugs[$slug] );
                    }
                }
            }
        }
        return $woosvi_slug;
    }
    
    public function run_integrations()
    {
        $this->svi_Yith_Badge();
    }
    
    public function svi_Yith_Badge()
    {
        if ( !function_exists( 'YITH_WCBM_Frontend' ) ) {
            return;
        }
        global  $product ;
        $yith_badge = YITH_WCBM_Frontend();
        echo  $yith_badge->show_badge_on_product( ' ' ) ;
    }

}