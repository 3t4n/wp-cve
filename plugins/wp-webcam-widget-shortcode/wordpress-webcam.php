<?php
/**
* Plugin Name: Wordpress Live Webcam Widget and Shortcode
* Description: You'll include a live webcams by using a remote stream. You can use JPEG, MJPEG or CGI streamed in a widget or in a post/page by using the <code>[webcam]</code> shortcode.
* Version: 1.0.2
* Text Domain: wpwws
* Domain Path: /languages
* Author: Michelangelo Scotto di Gregorio
* Author URI: https://www.procidameteo.it
*/

# Protezione
defined('ABSPATH') or die('No script kiddies please!');

# Aggiunta info link
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'wpwws_info');

function wpwws_info($links){
	$mylinks = array('<a href="'.admin_url('admin.php?page=wpw').'">'.__('How to use the shortcode','wpwws').'</a>');
	return array_merge($links, $mylinks);
}

# i18n
function wpwws_multilang(){
	load_plugin_textdomain('wpw', FALSE, basename(dirname(__FILE__)).'/languages/');
}
add_action('plugins_loaded', 'wpwws_multilang');

# Aggiunta pagina info
add_action('admin_menu', 'wpwws_pag');
function wpwws_pag(){
	add_submenu_page('miksco.php', 'Wordpress Webcam Widget and Shortcode Info', 'WP Webcam Shortcode', 'manage_options', 'wpw', 'wpwws_page');
}

function wpwws_page(){
	define('WPW', true);
	include(dirname(__FILE__).'/shortcode_info.php');
}

# Include widget
class wpwws_widget extends WP_Widget{
	
	function wpwws_widget(){
		parent::WP_Widget(false, $name = 'WP Live WebCam');
	}
	
	function widget($args, $instance){ global $meteolang;
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);
		$webcamurl = esc_attr($instance['webcamurl']);
		$webcamlnk = esc_attr($instance['webcamlink']);
		$webcamexp = esc_attr($instance['webcamexpire']);
		
		echo $before_widget;
		echo $title ? $before_title.$title.$after_title : '';
		
		if(!$webcamurl || filter_var($webcamurl, FILTER_VALIDATE_URL) === false){
			exit(__('<strong>Error:</strong> invalid URL!','wpwws'));
		}
		
		$myHashID = md5($webcamurl.time().rand(0, 100));
		$htmlwebcam = '<p style="margin:0; padding:0; text-align:center"><img id="'.$myHashID.'" src="'.$webcamurl.'" alt="'.$title.'"></p>';
		?>
	<script>
	jQuery(document).ready(function() {
       window.setInterval("upd_<?php echo $myHashID; ?>();", <?php echo $webcamexp ? $webcamexp : '5'; ?>000);
    });

    function upd_<?php echo $myHashID; ?>(){
		jQuery('#<?php echo $myHashID; ?>').attr('src', '<?php echo $webcamurl; ?>'+'?nocache='+Math.random());
	}
	</script>
	<?php 
	
	if($webcamlnk){ ?>
	<a href="<?php echo $webcamlnk; ?>" target="_blank">
		<?php echo $htmlwebcam; ?>
	</a>
	<?php }else{
		echo $htmlwebcam;
	} ?>

		<?php
		echo $after_widget;
	}
	
	function form($instance){
		$title = esc_attr($instance['title']);
		$webcamurl = esc_attr($instance['webcamurl']);
		$webcamlnk = esc_attr($instance['webcamlink']);
		$webcamexp = esc_attr($instance['webcamexpire']);
		?>
		
	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('webcamurl'); ?>" ><?php _e('Webcam URL:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('webcamurl'); ?>" name="<?php echo $this->get_field_name('webcamurl'); ?>" type="text" value="<?php echo $webcamurl; ?>">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('webcamlink'); ?>" ><?php _e('Webcam link:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('webcamlink'); ?>" name="<?php echo $this->get_field_name('webcamlink'); ?>" type="text" value="<?php echo $webcamlnk; ?>">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('webcamexpire'); ?>" ><?php _e('Refresh every (seconds):'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('webcamexpire'); ?>" name="<?php echo $this->get_field_name('webcamexpire'); ?>" type="text" value="<?php echo $webcamexp ? $webcamexp : '5'; ?>">
	</p>
	
		<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("wpwws_widget");'));


# Shortcode
function wpwws_shortcode($atts){
	shortcode_atts(array("url" => false, "refresh" => 5, "alt" => "Live Webcam"), $atts, 'webcam');
	$myHashID = md5($webcamurl.time().rand(0, 100));
	
	if($atts['url']){ ?>
	<script>
	jQuery(document).ready(function() {
       window.setInterval("upd_<?php echo $myHashID; ?>();",  <?php echo $atts['refresh'] ? $atts['refresh'] : 5; ?>000);
    });

    function upd_<?php echo $myHashID; ?>(){
		jQuery('#liveWebcam').attr('src', '<?php echo $atts['url']; ?>'+'?nocache='+Math.random());
	}
	</script>
<?php
		$data = '<div style="margin:0 auto; text-align:center"><img id="'.$myHashID.'" src="'.$atts['url'].'" alt="'.$atts['alt'].'"></div>';
	}else{
		$data = '<div class="base-box news-box mom_box_sc_error mom_box_sc clear">'.__('Invalid shortcode','wpwws').'</div>';
	}
	
		return $data;
}
add_shortcode('webcam', 'wpwws_shortcode');

?>