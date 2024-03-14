<?php
/**
 *
 * Plugin Name:       Swifty Image Widget by WPGens
 * Plugin URI:        https://www.wpgens.com
 * Description:       Simple yet powerful Widget for Images, Testimonials and Advertising Banners.
 * Version:           1.1.1
 * Author:            Goran Jakovljevic
 * Author URI:        https://www.wpgens.com/
 * Text Domain:       swifty-img-widget
 * Domain Path:       /lang
 */
 
 // Prevent direct file access
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}


class Swifty_Img_Widget extends WP_Widget {

    protected $widget_slug = 'swifty-img-widget';

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		// load plugin text domain
		add_action( 'init', array( $this, 'widget_textdomain' ) );

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Swifty Image Widget', $this->get_widget_slug() ),
			array(
				'classname'  => $this->get_widget_slug().'-class',
				'description' => __( 'Easly place one or multiple images as widgets.', $this->get_widget_slug() )
			)
		);

		// Register admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

	} // end constructor


    /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
    public function get_widget_slug() {
        return $this->widget_slug;
    }

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {

		
		// Check if there is a cached output
		$cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];
		
		// Off with our widget logic

		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'] );

		$widget_string = $before_widget;
		// Widget front end
		ob_start();
		include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

		print $widget_string;

	} // end widget
	
	
	public function flush_widget_cache() 
	{
    	wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// Updating old values with new ones
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['size'] = strip_tags( $new_instance['size'] );
		$instance['align'] = strip_tags( $new_instance['align'] );
		$instance['img_open_in'] = strip_tags( $new_instance['img_open_in'] );
		$instance['rel'] = strip_tags( $new_instance['rel'] );
		$instance['img_width'] = strip_tags( $new_instance['img_width'] );
		$instance['img_height'] = strip_tags( $new_instance['img_height'] );
		$instance['imgs'] = array();

		if(!empty($new_instance['img_id'])){
			for($i=1; $i < (count($new_instance['img_id']) ); $i++){
				if(!empty($new_instance['img_id'][$i])){
					$img = array();
					$img['img'] = esc_attr($new_instance['img_id'][$i]);
					$img['link'] = esc_url($new_instance['img_link'][$i]);
					$img['caption'] = wp_kses_post($new_instance['img_caption'][$i]);
					$instance['imgs'][] = $img;
				}
			}	
		}

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$default_settings = array(
	      'title' 	 => '',
	      'size' => '',
	      'align' => '',
	      'img_open_in' =>'',
	      'rel' =>'',
	      'img_width'=>'',
	      'img_height' 	 => '',
	      'imgs'	=> array()
	    );

		// Define default values for our variables
		$instance = wp_parse_args(
			(array) $instance,
			$default_settings
		);

		// Store the values of the widget in their own variable
		$title = $instance['title'];
		$size = $instance['size'];
		$align = $instance['align'];
		$img_open_in = $instance['img_open_in'];
		$rel = $instance['rel'];
		$img_width = $instance['img_width'];
		$img_height = $instance['img_height'];
		$imgs = $instance['imgs'];
		// Display the admin form
		include( plugin_dir_path(__FILE__) . 'views/admin.php' );

	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	public function widget_textdomain() {

		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain
	
	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );

	} // end register_widget_styles

} // end class

add_action( 'widgets_init', function() { register_widget( "Swifty_Img_Widget" ); } );