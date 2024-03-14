<?php
/**
 * Plugin Name: Scroll Bar With Back To Top
 * Plugin URI: http://shafiqul.info
 * Description: Scroll Bar With Back To Top is a Easily Customization  Plugin and Very User Friendly Plugins settings option.
 * Author: Shafiqul Islam
 * Author URI: http://shafiqul.info
 * Version: 1.0
 */

function gcz_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}
function add_styles_scripts(){
	// Load the Scroll CSS
	wp_enqueue_style( 'fontello', plugin_dir_url(__FILE__) . 'assets/fontello.css');
	
	//Load our custom Javascript file
	wp_enqueue_script( 'jquery.nicescroll.min', plugin_dir_url(__FILE__) . 'assets/jquery.nicescroll.min.js' );

}
add_action( 'wp_footer', 'add_styles_scripts' );
function add_script_footer(){
	//$scroll_bar = get_option('scroll_bar'); 
	//$scroll_top = get_option('scroll_top');
	$scroll_bar_active = gcz_get_option('scroll_bar_active', 'scroll_bar', 'yes');
	$scroll_bar_color = gcz_get_option('scroll_bar_color', 'scroll_bar', '#36c6f4');
	$scroll_bar_width = gcz_get_option('scroll_bar_width', 'scroll_bar', '10');
	$scroll_bar_speed = gcz_get_option('scroll_bar_speed', 'scroll_bar', '100');
	$scroll_bar_opacity = gcz_get_option('scroll_bar_opacity', 'scroll_bar', '0.3');
	$scroll_bar_mousescrollstep = gcz_get_option('scroll_bar_mousescrollstep', 'scroll_bar', '45');
	$scroll_bar_borderradius = gcz_get_option('scroll_bar_borderradius', 'scroll_bar', '10');
	$scroll_bar_border = gcz_get_option('scroll_bar_border', 'scroll_bar', '0px solid #000');
	$scroll_bar_hidecursordelay = gcz_get_option('scroll_bar_hidecursordelay', 'scroll_bar', '150');
	$smooth_scroll = gcz_get_option('smooth_scroll', 'scroll_bar', 'true');
	
	
	$scroll_top_color = gcz_get_option('scroll_top_color', 'scroll_top', '#36c6f4');
	$scroll_top_icon_font_size = gcz_get_option('scroll_top_icon_font_size', 'scroll_top', '20px');
	$scroll_top_icon_color = gcz_get_option('scroll_top_icon_color', 'scroll_top', '#ffffff');
	$scroll_top_border_radius = gcz_get_option('scroll_top_border_radius', 'scroll_top', '3px');
	$scroll_top_smooth = gcz_get_option('scroll_top_smooth', 'scroll_top', '500');
	$scroll_top_show_time = gcz_get_option('scroll_top_show_time', 'scroll_top', '100');
	
	?>
	<style type="text/css">
	#back-top {
	  bottom: 30px;
	  position: fixed;
	  right: 40px;
	}
	#back-top a {
	  color: #bbb;
	  display: block;
	  font: 11px/100% Arial,Helvetica,sans-serif;
	  text-align: center;
	  text-decoration: none;
	  text-transform: uppercase;
	  transition: all 1s ease 0s;
	}
	#back-top [class^="icon-"] {
	  background: <?php echo $scroll_top_color;?>;
	  border-radius: <?php echo $scroll_top_border_radius;?>;
	  color: <?php echo $scroll_top_icon_color;?>;
	  font-size: <?php echo $scroll_top_icon_font_size;?>;
	  padding: 10px;
	  height: auto;
	  width: auto;
	}
	</style>
	<script type="text/javascript">
	<?php if($scroll_bar_active == 'yes'){
	?>
	jQuery(document).ready(function() {
		jQuery("html").niceScroll({
			styler:"fb",
			cursorcolor :"<?php echo $scroll_bar_color;?>",
			cursorborder : "<?php echo $scroll_bar_border;?>",
			cursoropacitymin : <?php echo $scroll_bar_opacity;?>,
			//bouncescroll : true,
			spacebarenabled : true,
			scrollspeed : <?php echo $scroll_bar_speed;?>,
			mousescrollstep : <?php echo $scroll_bar_mousescrollstep;?>,
			zindex : 99999,
			cursorborderradius : <?php echo $scroll_bar_borderradius;?>,
			cursorwidth : <?php echo $scroll_bar_width;?>,
			enabletranslate3d : false,
			smoothscroll : <?php echo $smooth_scroll;?>,
			hidecursordelay : <?php echo $scroll_bar_hidecursordelay;?>
		});
	  }
	);
	<?php
	}
	?>
	jQuery(document).ready(function() {
		jQuery("#back-top").hide();
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > <?php echo $scroll_top_show_time;?>) {
				jQuery('#back-top').fadeIn();
			} else {
				jQuery('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('#back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, <?php echo $scroll_top_smooth;?>);
			return false;
		});
		
		}
	);	
	</script>
	<?php
}
add_action('wp_head', 'add_script_footer');

function  gcz_html_structure(){
  $scroll_top_active = gcz_get_option('scroll_top_active', 'scroll_top', 'yes');
  $scroll_top_icon = gcz_get_option('scroll_top_icon', 'scroll_top', 'icon-up-open');
  if($scroll_top_active == 'yes'){
  ?>
	<div id="back-top"><a href="#top"><i class="<?php echo $scroll_top_icon;?>"></i></a></div>
  <?php
  // include 'scr_style.css';
}
}
add_action('wp_footer','gcz_html_structure');

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'gcz_plugin_action_links' );
function gcz_plugin_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=scroll_settings') ) .'">Settings</a>';
   return $links;
}
require_once dirname( __FILE__ ) . '/inc/class.settings-api.php';
require_once dirname( __FILE__ ) . '/scroll-option.php';

new gcz_Scroll_Setting();

