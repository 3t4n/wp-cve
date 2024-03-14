<?php
namespace Auxin\Plugin\Portfolio\Elementor;

/**
 * Auxin Elementor Elements
 *
 * Custom Elementor extension.
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Main Auxin Elementor Elements Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Elements {


    /**
     * Default elementor dit path
     *
     * @since 1.0.0
     *
     * @var string The defualt path to elementor dir on this plugin.
     */
    private $dir_path = '';


    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var Auxin_Elementor_Core_Elements The single instance of the class.
    */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     * @return Auxin_Elementor_Core_Elements An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
          self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
    */
    public function init() {

        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            return;
        }

        // Define elementor dir path
        $this->dir_path = AUXPFO_INC_DIR . '/elements/elementor';

        // Include core files
        $this->includes();

        // Add required hooks
        $this->hooks();
    }

    /**
     * Include Files
     *
     * Load required core files.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function includes() {

    }

    /**
     * Add hooks
     *
     * Add required hooks for extending the Elementor.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function hooks() {

        // Register controls, widgets, and categories
        add_action( 'auxin/core_elements/elementor/widgets_list', array( $this, 'register_widgets' ) );

        // Register Elementor for portfolio post type
        add_action( 'auxin_plugin_updated',  array( $this, 'aux_add_portfolio_support' ) );

        // let Elementor pro override single portfolio template
        add_filter( 'elementor/theme/need_override_location', array( $this, 'aux_elementor_pro_override_templates' ) );

        // Register Widget Styles
        // add_action( 'elementor/frontend/after_enqueue_styles'   , array( $this, 'widget_styles' ) );

        // Register Widget Scripts
        // add_action( 'elementor/frontend/after_register_scripts' , array( $this, 'widget_scripts' ) );

        // Register Admin Scripts
        // add_action( 'elementor/editor/before_enqueue_scripts'   , array( $this, 'editor_scripts' ) );

        // Register dynamic tags
        add_action( 'elementor/dynamic_tags/register', [ $this, 'register_tag' ] );
    }

    /**
     * Register widgets
     *
     * Register all widgets which are in widgets list.
     *
     * @access public
     */
    public function register_widgets( $widgets ) {
        $widgets['310'] = array(
            'file'  => $this->dir_path . '/recent-portfolios-grid.php',
            'class' => __NAMESPACE__ . '\Elements\Recent_Portfolios_Grid'
        );

        $widgets['320'] = array(
            'file'  => $this->dir_path . '/recent-portfolios-masonry.php',
            'class' => __NAMESPACE__ . '\Elements\Recent_Portfolios_Masonry'
        );

        $widgets['330'] = array(
            'file'  => $this->dir_path . '/recent-portfolios-tile.php',
            'class' => __NAMESPACE__ . '\Elements\Recent_Portfolios_Tile'
        );

        $widgets['340'] = array(
            'file'  => $this->dir_path . '/recent-portfolios-tile-carousel.php',
            'class' => __NAMESPACE__ . '\Elements\Recent_Portfolios_Tile_Carousel_Carousel'
        );
        $widgets['350'] = array(
            'file'  => $this->dir_path . '/recent-portfolios-grid-carousel.php',
            'class' => __NAMESPACE__ . '\Elements\Recent_Portfolios_Grid_Carousel'
        );

        return $widgets;
    }

    /**
     * Register Post type
     *
     * Register Elementor for portfolio post type
     *
     * @access public
     */
    public function aux_add_portfolio_support() {
        $post_types = get_option( 'elementor_cpt_support' );
        
        if( ! $post_types ) {
            $post_types = [ 'page', 'post', 'portfolio' ]; 
            update_option( 'elementor_cpt_support', $post_types ); 
        } else if( ! in_array( 'portfolio', $post_types ) ) {
            $post_types[] = 'portfolio'; //append to array
            update_option( 'elementor_cpt_support', $post_types ); 
        }
         
    }


    /**
     * Enqueue styles.
     *
     * Enqueue all the frontend styles.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_styles() {

    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the frontend scripts.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function widget_scripts() {

    }

    /**
     * Enqueue scripts.
     *
     * Enqueue all the backend scripts.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function editor_scripts() {
        // Elementor Custom Style
    }

    /**
     * Override portfolio templates
     *
     * Let Elementor Pro override portfolio templates
     *
     * @access public
     */
    public function aux_elementor_pro_override_templates( $need_override_location ) {
        
        return ( ( ( is_single() && get_post_type() == 'portfolio' ) || is_post_type_archive( 'portfolio' ) ) || $need_override_location ) ;
    }

    /**
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
	 */
	public function register_tag( $dynamic_tags ) {

        $tags = array(
			'aux-portfolios-url' => array(
                'file'  => AUXPFO_INC_DIR . '/elements/elementor/dynamic-tags/portfolios-url.php',
				'class' => 'DynamicTags\Auxin_Portfolios_Url',
				'group' => 'URL',
				'title' => 'URL',
			)
        );

        foreach ( $tags as $tags_type => $tags_info ) {
            if( ! empty( $tags_info['file'] ) && ! empty( $tags_info['class'] ) ){
				// In our Dynamic Tag we use a group named request-variables so we need
				// To register that group as well before the tag
				\Elementor\Plugin::instance()->dynamic_tags->register_group( $tags_info['group'] , [
					'title' => $tags_info['title']
				] );

                include_once( $tags_info['file'] );
                if( class_exists( $tags_info['class'] ) ){
                    $class_name = $tags_info['class'];
                } elseif( class_exists( __NAMESPACE__ . '\\' . $tags_info['class'] ) ){
                    $class_name = __NAMESPACE__ . '\\' . $tags_info['class'];
                }
				$dynamic_tags->register( new $class_name() );
            }
        }
	}
}

Elements::instance();
