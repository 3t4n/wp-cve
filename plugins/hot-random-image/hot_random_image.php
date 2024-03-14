<?php
/**
 * Plugin Name: Hot Random Image
 * Plugin URI: https://www.hotjoomlatemplates.com/wordpress-plugins/random-image
 * Description: Hot Random Image is a basic widget that shows a randomly picked image from a selected folder where images are stored.
 * Version: 1.9.0
 * Author: Hot Themes
 * Author URI: https://www.hotjoomlatemplates.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'hot_random_image_load_widgets' );
add_action('admin_init', 'hot_random_image_textdomain');
/**
 * Register our widget.
 * 'Hotrandom_image' is the widget class used below.
 *
 * @since 0.1
 */
function hot_random_image_load_widgets() {
	register_widget( 'Hotrandom_image' );
}

function hot_random_image_textdomain() {
	load_plugin_textdomain('hot_random_image', false, dirname(plugin_basename(__FILE__) ) . '/languages');
}

/**
 * Shortcode [randomimage path="images/random" width="100%" height="auto" alt="Random image" link="https://www.hotjoomlatemplates.com/"]
 */

function randomimage_func( $atts ) {
	$a = shortcode_atts( array(
		'path' => 'images/random',
		'width' => '100%',
		'height' => 'auto',
		'alt' => '',
		'link' => ''
	), $atts );

	return randomimage_select_image($a['path'],$a['link'],$a['width'],$a['height'],$a['alt']);

}
add_shortcode( 'randomimage', 'randomimage_func' );

function randomimage_select_image( $path, $link, $width, $height, $alt ) {

	$images1 = glob($path.'/*.jpg');
	$images2 = glob($path.'/*.png');
	$images3 = glob($path.'/*.gif');
	$images4 = glob($path.'/*.jpeg');
	$images5 = glob($path.'/*.svg');
	$images6 = glob($path.'/*.JPG');
	$images7 = glob($path.'/*.PNG');
	$images8 = glob($path.'/*.GIF');
	$images9 = glob($path.'/*.JPEG');
	$images10 = glob($path.'/*.SVG');

	$images = array();
			
	if(!empty($images1))
		$images = array_merge($images,$images1);
	if(!empty($images2))
		$images = array_merge($images,$images2);
	if(!empty($images3))
		$images = array_merge($images,$images3);
	if(!empty($images4))
		$images = array_merge($images,$images4);
	if(!empty($images5))
		$images = array_merge($images,$images5);
	if(!empty($images6))
		$images = array_merge($images,$images6);
	if(!empty($images7))
		$images = array_merge($images,$images7);
	if(!empty($images8))
		$images = array_merge($images,$images8);
	if(!empty($images9))
		$images = array_merge($images,$images9);
	if(!empty($images10))
		$images = array_merge($images,$images10);
		
	$ind = wp_rand(1,count($images));
	if (isset($images[$ind - 1])) {
		$image = $images[$ind - 1];
	} else {
		$image = false;
	}

	$html = '';

	if($image){
		if($link){
			$html .= '<a href="'.esc_attr($link).'">';
		}
			$html .= '<img class="hot-random-image" style="width:'.esc_attr($width).'; height:'.esc_attr($height).';" src="'.esc_url(get_site_url().'/'.$image).'" alt="'.esc_attr($alt).'" />';

		if($link){
			$html .= '</a>';
		}
	}

	if (!$path) {
		return '<img class="hot-random-image" style="width:'.esc_attr($width).'; height:'.esc_attr($height).';" src="'.get_site_url().'/wp-content/plugins/hot-random-image/images/hot_random_image.png" alt="'.esc_attr($alt).'" />';
	}
	
	return $html;

}

/**
 * Hotrandom_image Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
 
class Hotrandom_image extends WP_Widget {
     
	/**
	 * Widget setup.
	 */
	 
	function __construct() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'Hot_random_image', 'description' => __('Hot Random Image', 'hot_random_image') );

		/* Widget control settings. */
		$control_ops = array(  'id_base' => 'hot-random_image' );

		/* Create the widget. */
		parent::__construct( 'hot-random_image', __('Hot Random Image', 'hot_random_image'), $widget_ops, $control_ops );
		
    }
	
	
	function GetDefaults() {
		return array(
			'title' => ''
			,'width' => '100%'
			,'height' => 'auto'
			,'folder' => ''
			,'alt' => 'Random image'
			,'link' => ''
			,'userinput' => ''
		);
	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Before widget (defined by themes). */
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo wp_kses_post($before_widget);

		if (!empty($title)) {
			echo wp_kses_post($before_title . $title . $after_title);
		}

        $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults );  
	    
		$input = str_replace("\n", ";",$instance["userinput"]);
		$input = str_replace("\r", ";",$instance["userinput"]);
		$input = str_replace(" ", "",$input); 
		$use_input =false;
		
		if ($input == '') {

			echo wp_kses_post( randomimage_select_image( $instance["folder"], $instance["link"], $instance["width"], $instance["height"], $instance["alt"] ) );
		
		} else {
			$loop = 0;
			$images = array();
			$links  = array();

			$arr = explode(';',$input);

			for($loop = 0; $loop < count($arr);$loop++){
				$il_val = explode('|',$arr[$loop]);

				$images[$loop] = $instance["folder"].'/'.$il_val[0];  
				$links[$loop]  = $il_val[1]; 
		   	}
		   
			$ind = wp_rand(1,count($images));
			$image = $images[$ind - 1]; 
			$link  = $links[$ind - 1];

			if($image){
				if($link){ ?>
					<a href="<?php echo esc_html( $link ); ?>">
			<?php } ?>
					<img id="random-image-<?php echo esc_attr( $this->number ); ?>" class="hot-random-image" src="<?php echo esc_url( get_site_url() . '/' . $image ); ?>" style="width:<?php echo esc_attr( $instance["width"] ); ?>; height:<?php echo esc_attr( $instance["height"] ); ?>;" alt="<?php echo esc_attr( $instance["alt"] ); ?>" />
		    <?php
				if($link){ ?>
					</a>
				<?php }
			}
		}
		
		/* After widget (defined by themes). */
		echo wp_kses_post($after_widget);
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    	
		foreach($new_instance as $key => $option)
		{
			$instance[$key]     = $new_instance[$key];
		} 
		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form( $instance ) {

		/* Set up some default widget settings. */
	    $defaults = $this->GetDefaults();
		$instance = wp_parse_args( (array) $instance, $defaults );  ?>

		<!-- Hot Random Image: Text Input -->

		<p><?php esc_html_e( 'Title:','hot_random_image' ); ?><br/>
		<input  type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'folder' ) ); ?>"><?php esc_html_e('Path to images:','hot_random_image'); ?></label>
			<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'folder' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'folder' ) ); ?>" value="<?php echo esc_attr( $instance['folder'] ); ?>" class="text" />
			<span style="font-size:12px; display: block;"><?php esc_html_e('Enter path relative to your WordPress installation. In example "wp-content/uploads/2014/12"','hot_random_image'); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>"><?php esc_html_e('Width:','hot_random_image'); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'width' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'width' ) ); ?>" value="<?php echo esc_attr( $instance['width'] ); ?>" size="5" />
			<span style="font-size:12px; display: block;"><?php esc_html_e('Enter dimension and unit (in example "200px" or "100%" or "auto")','hot_random_image'); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>"><?php esc_html_e('Height:','hot_random_image'); ?></label>
			<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'height' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'height' ) ); ?>" value="<?php echo esc_attr( $instance['height'] ); ?>" size="5" />
			<span style="font-size:12px; display: block;"><?php esc_html_e('Enter dimension and unit (in example "200px" or "100%" or "auto")','hot_random_image'); ?></span>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>"><?php esc_html_e('Alt text:','hot_random_image'); ?></label>
			<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'alt' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>" value="<?php echo esc_attr( $instance['alt'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>"><?php esc_html_e('Image link:','hot_random_image'); ?></label>
			<input class="widefat" type="text" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" value="<?php echo esc_attr( $instance['link'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'userinput' ) ); ?>"><?php esc_html_e('Select specific images (optional)','hot_random_image'); ?></label>
			<textarea class="widefat" rows="5" name="<?php echo esc_attr( $this->get_field_name( 'userinput' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'userinput' ) ); ?>" ><?php echo esc_attr( $instance['userinput'] ); ?></textarea>
			<span style="font-size:12px; display: block;"><?php esc_html_e('Leave this blank if want to rotate all images from the specified folder. If you want to rotate only selected images, specify them here. You can use this format: "image name|image link" for each image. For example:','hot_random_image'); ?></span>
			<span style="font-size:12px; display: block;"><?php esc_html_e('random_image1.jpg|https://google.com;','hot_random_image'); ?></span>
			<span style="font-size:12px; display: block;"><?php esc_html_e('random_image2.jpg|https://yahoo.com;','hot_random_image'); ?></span>
			<span style="font-size:12px; display: block;"><?php esc_html_e('random_image3.jpg|https://bing.com','hot_random_image'); ?></span>
		</p>

	<?php  
	}
}

function hot_random_image_block_render_callback($block_attributes, $content) {

    return randomimage_select_image($block_attributes['path'],$block_attributes['link'],$block_attributes['width'],$block_attributes['height'],$block_attributes['alt']);

}

add_action('init', 'hot_random_image_block_init');
function hot_random_image_block_init() {

	wp_register_script( 'hot_random_image_header', '', array(), '1.0', array('in_footer' => true) );
	wp_enqueue_script( 'hot_random_image_header' );
	wp_add_inline_script( 'hot_random_image_header', 'const hot_random_image_cover = "' . get_site_url() . '/wp-content/plugins/hot-random-image/images/hot_random_image.png";' );

	register_block_type(
        __DIR__ . '/build',
        array(
        	'render_callback' => 'hot_random_image_block_render_callback'
        )
    );
}

?>