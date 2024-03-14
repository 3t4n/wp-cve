<?php
/*
Plugin Name: Scroll UP
Plugin URI: http://rayhan.info/plugins/
Description: Scroll Up is a lightweight plugin that creates a full customizable "Scroll to top / Back to top" feature in your WordPres site
Version: 2.0
Author: King Rayhan
Author URI: http://rayhan.info/
License: GPL2
*/




define('SETTINGS_PAGE_TITLE','Scroll UP');


function scroll_to_up_pro_scripts(){
	$options = get_option('scroll_to_up_pro');
	if($options['scroll_to_up_method'] == 'fa_icon'){
		wp_register_style( 'scroll_to_up_fa', plugin_dir_url(__FILE__).'assets/css/font-awesome.min.css', '', '4.4.0', 'all' );
		wp_enqueue_style('scroll_to_up_fa');
	}
	
	wp_enqueue_script( 'scroll_to_up_pro_js',  plugin_dir_url(__FILE__).'assets/jquery.scrollUp.min.js', array('jquery'), '2.4.1', true );
}
add_action('wp_enqueue_scripts','scroll_to_up_pro_scripts');



require_once plugin_dir_path( __FILE__ ) .'lib/csf-pro/classes/setup.class.php';
require_once plugin_dir_path( __FILE__ ) .'lib/csf-pro/config/framework.config.php';

add_action('wp_head',function(){ ?>
	<style type="text/css">
		#scroll_to_up_pro {
		    padding: 10px 20px;
		}
	/*-------------------------------------------------------------
			Define Scrollup button position
	-------------------------------------------------------------*/
	<?php
	$btn_position = get_option('scroll_to_up_pro')['button_position'];
	
	if($btn_position == 'bottom_right'){ 
		echo '/*position : Bottom right*/#scroll_to_up_pro {bottom: 20px;right: 20px;}';
	}
	if($btn_position == 'bottom_left'){
		echo '/*position : Bottom left*/#scroll_to_up_pro {bottom: 20px;left: 20px;}';
	}
	if($btn_position == 'vertically_middle_left'){
		echo '/*position : Vertically middle left*/#scroll_to_up_pro {top: 45%;left: 20px;}';
	}
	if($btn_position == 'vertically_middle_right'){
		echo '/*position : Vertically middle right*/#scroll_to_up_pro {top: 45%;right: 20px;}';
	}
	
	echo "\n\n\n\n\n";
	
	$options = get_option('scroll_to_up_pro');
	?>
	/**
	  * Distance
	  */
	#scroll_to_up_pro{
		<?php 
			if($btn_position == 'bottom_right'){
				echo "right: $options[distance_right]px;";
				echo "bottom: $options[distance_bottom]px;";
			}else if($btn_position == 'bottom_left'){
				echo "left: $options[distance_left]px;";
				echo "bottom: $options[distance_bottom]px;";
			}else if($btn_position == 'vertically_middle_left'){
				echo "left: $options[distance_left]px;";
			}else if($btn_position == 'vertically_middle_right'){
				echo "right: $options[distance_right]px;";
			}
		?>
	}
	<?php
	
	/*-------------------------------------------------------------
			Simple Text Method
	-------------------------------------------------------------*/
	$options = get_option('scroll_to_up_pro');
	if($options['scroll_to_up_method'] == 'simple_txt'){
		echo '#scroll_to_up_pro {
				background-color: '.$options[simple_txt_bgcolor].';
				color: '.$options[simple_txt_color].';
				font-size: '.$options[simple_txt_font_size].'px;
				border-radius: '.$options[simple_txt_btn_border_radius].'px;
			}
			#scroll_to_up_pro:hover {
				background-color: '.$options[simple_txt_hover_bg_color].';
				color: '.$options[simple_txt_hovercolor].';
			}
			';
	}
	/*-------------------------------------------------------------
			Font Awesome icon Method
	-------------------------------------------------------------*/
	$options = get_option('scroll_to_up_pro');
	if($options['scroll_to_up_method'] == 'fa_icon'){
		echo '#scroll_to_up_pro {
				background-color: '.$options[fa_icon_bgcolor].';
				color: '.$options[fa_icon_color].';
				font-size: '.$options[fa_icon_icon_size].'px;
				border-radius: '.$options[fa_icon_border_radius].'px;
			}
			#scroll_to_up_pro:hover {
				background-color: '.$options[fa_icon_hover_bgcolor].';
				color: '.$options[fa_icon_hover_color].';
			}
			';
	}
	
	/*-------------------------------------------------------------
		  Upload Image Arrow Method
	-------------------------------------------------------------*/
	$options = get_option('scroll_to_up_pro');
	if( $options['scroll_to_up_method'] == 'own_image' ){
		echo '#scroll_to_up_pro{
			background-image: url("'.$options[uploaded_image].'");
			width: '.$options[uploaded_image_width].'px; 
			height: '.$options[uploaded_image_height].'px;
			background-size: 100% 100%;
			}';
	}
	
	/*-------------------------------------------------------------
		  Images arrow
	-------------------------------------------------------------*/
	$options = get_option('scroll_to_up_pro');
	if( $options['scroll_to_up_method'] == 'image_arrow' ){
		echo '#scroll_to_up_pro{
			background-image: url("'.plugin_dir_url(__FILE__).'assets/arrows/'.$options[image_arrows].'.png");
			width: '.$options[image_arrow_width].'px; 
			height: '.$options[image_arrow_height].'px;
			background-size: 100% 100%;
			}';
	}
	?>
	</style>
<?php });


function scroll_to_up_pro_activation(){ ?>
<script type="text/javascript">
<?php $options = get_option('scroll_to_up_pro');?>
jQuery(document).ready(function($) {
    $.scrollUp({
        scrollName: 'scroll_to_up_pro',      // Element ID
        scrollDistance: <?php echo $options['scroll_distance']; ?>,         // Distance from top/bottom before showing element (px)
        scrollFrom: 'top',           // 'top' or 'bottom'
        scrollSpeed: <?php echo $options['scroll_distance']; ?>,            // Speed back to top (ms)
        easingType: 'linear',        // Scroll to top easing (see http://easings.net/)
        animation: '<?php echo $options['button_animation']; ?>',           // Fade, slide, none
        animationSpeed: <?php echo $options['scroll_speed']; ?>,         // Animation speed (ms)
        scrollTrigger: false,        // Set a custom triggering element. Can be an HTML string or jQuery object
        scrollTarget: false,         // Set a custom target element for scrolling to. Can be element or number
        scrollText: "<?php  
        	if($options['scroll_to_up_method'] == 'fa_icon')
        		echo "<i class='".$options[fa_icon]."'></i>";
        	elseif($options['scroll_to_up_method'] == 'simple_txt')
        		echo $options['simple_txt_label'];
        
        ?>", // Text for element, can contain HTML
        scrollTitle: false,          // Set a custom <a> title if required.
        scrollImg: false,            // Set true to use image
        activeOverlay: false,        // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        zIndex: 2147483647           // Z-Index for the overlay
    });
});
</script>
<?php } add_action('wp_footer','scroll_to_up_pro_activation');