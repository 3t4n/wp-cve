<?php
/*
Plugin Name: Widget Customizer for Wordpress - Free Version
Plugin URI: http://www.mihajlovicnenad.com/widget-customizer
Description: Plugin for easy widget styling! Get amazing presets and features for your widgets! - mihajlovicnenad.com
Author: Mihajlovic Nenad
Version: 1.0.0
Author URI: http://www.mihajlovicnenad.com
*/

$curr_path = dirname( __FILE__ );
$curr_name = basename( $curr_path );
$curr_url = plugins_url( "/$curr_name/" );

define('WDGTCSTMZR_URL', $curr_url);

/*
 * Widget Customizer Init
*/
add_action( 'widgets_init', create_function('', 'return register_widget("wdgtcstmzr");' ) );

/*
 * Widget Customizer Admin Init
*/
function wdgtcstmzr_admin() {
	wp_enqueue_style('wdgtcstmzr-admin', WDGTCSTMZR_URL .'/lib/wdgtcstmzr_admin.css');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_script('wp-color-picker');
	wp_enqueue_script('wdgtcstmzr-admin', WDGTCSTMZR_URL .'/lib/wdgtcstmzr_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-widget', 'jquery-ui-sortable'), '1.0', true);
}
add_action( 'load-widgets.php', 'wdgtcstmzr_admin');

/*
 * Widget Customizer
*/
class wdgtcstmzr extends WP_Widget {

	function wdgtcstmzr() {
		$widget_ops = array(
			'classname' => 'wdgtcstmzr',
			'description' => __( 'Widget Customizer! Colorize next widget.', 'wdgtcstmzr' )
		);
		$this->WP_Widget( 'wdgtcstmzr', '+ Widget Customizer - Free', $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$curr_wc = array (
			'id' => uniqid('wdgtcstmzr-'),
			'background' => $instance['background'],
			'wtitle' => $instance['wtitle'],
			'text' => $instance['text'],
			'link' => $instance['link'],
			'hover' => $instance['hover'],
			'border' => $instance['border'],
			'width' => intval($instance['width']),
			'radius' => intval($instance['radius'])
		);
		$wc_id = $curr_wc['id'];

		?>
			<style id="<?php echo $wc_id ?>" type="text/css">
		<?php
			printf('#%1$s+aside,#%1$s+div,#%1$s+section{%2$s %3$s %6$s %7$s %8$s}#%1$s+aside a,#%1$s+div a,#%1$s+section a{%4$s}#%1$s+aside a:hover,#%1$s+div a:hover,#%1$s+section a:hover{%5$s}#%1$s+aside h1,#%1$s+aside h2,#%1$s+aside h3,#%1$s+aside h4,#%1$s+aside h5,#%1$s+aside h6,#%1$s+div h1,#%1$s+div h2,#%1$s+div h3,#%1$s+div h4,#%1$s+div h5,#%1$s+div h6,#%1$s+section h1,#%1$s+section h2,#%1$s+section h3,#%1$s+section h4,#%1$s+section h5,#%1$s+section h6{%9$s}',
				$wc_id,
				( ( $curr_wc['background'] == '' || $curr_wc['background'] == 'transparent' ) ? '' : 'background-color:'.$curr_wc['background'].';' ),
				( $curr_wc['text'] == '' || $curr_wc['text'] == 'transparent' ? '' : 'color:'.$curr_wc['text'].';' ),
				( $curr_wc['link'] == '' || $curr_wc['link'] == 'transparent' ? '' : 'color:'.$curr_wc['link'].';' ),
				( $curr_wc['hover'] == '' || $curr_wc['hover'] == 'transparent' ? '' : 'color:'.$curr_wc['hover'].';' ),
				( $curr_wc['border'] == '' || $curr_wc['border'] == 'transparent' ? '' : 'border:' . ( $curr_wc['width'] == '' ? '0px' : $curr_wc['width'].'px' ) . ' solid '.$curr_wc['border'].';box-sizing:border-box;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;-o-box-sizing:border-box;' ),
				( ( $curr_wc['background'] == '' || $curr_wc['background'] == 'transparent' ) && ( $curr_wc['border'] == '' || $curr_wc['border'] == 'transparent' ) ? '' : 'padding:15px;' ),
				( $curr_wc['radius'] == '' ? '' : 'border-radius:'.$curr_wc['radius'].'px;-webkit-border-radius:'.$curr_wc['radius'].'px;-moz-border-radius:'.$curr_wc['radius'].'px;-ms-border-radius:'.$curr_wc['radius'].'px;-o-border-radius:'.$curr_wc['radius'].'px;' ),
				( $curr_wc['wtitle'] == '' || $curr_wc['wtitle'] == 'transparent' ? '' : 'color:'.$curr_wc['wtitle'].';' )
			);
		?>
			</style>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['background'] =  $new_instance['background'];
		$instance['text'] =  $new_instance['text'];
		$instance['wtitle'] =  $new_instance['wtitle'];
		$instance['link'] =  $new_instance['link'];
		$instance['hover'] =  $new_instance['hover'];
		$instance['border'] =  $new_instance['border'];
		$instance['width'] =  $new_instance['width'];
		$instance['radius'] =  $new_instance['radius'];
		return $instance;
	}

	function form( $instance ) {
		$vars = array( 'background' => '', 'wtitle' => '', 'text' => '', 'link' => '', 'hover' => '', 'border' => '', 'width' => '', 'radius' => '' );
		$instance = wp_parse_args( (array) $instance, $vars );
		$background = strip_tags($instance['background']);
		$text = strip_tags($instance['text']);
		$wtitle = strip_tags($instance['wtitle']);
		$link = strip_tags($instance['link']);
		$hover = strip_tags($instance['hover']);
		$border = strip_tags($instance['border']);
		$width = intval($instance['width']);
		$radius = intval($instance['radius']);
		$unique_id = uniqid('wdgtcstmzr-');
?>
		<script type='text/javascript'>
			(function($){
				$('#<?php echo $unique_id; ?> .wdgtcstmzr-color').wpColorPicker();
			})(jQuery);
		</script>
		<div id="<?php echo $unique_id; ?>">
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('wtitle'); ?>" class="wdgtcstmzr-label"><?php _e('Title:'); ?></label>
			<input name="<?php echo $this->get_field_name('wtitle'); ?>" id="<?php echo $this->get_field_id('wtitle'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($wtitle); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('text'); ?>" class="wdgtcstmzr-label"><?php _e('Text:'); ?></label>
			<input name="<?php echo $this->get_field_name('text'); ?>" id="<?php echo $this->get_field_id('text'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($text); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('link'); ?>" class="wdgtcstmzr-label"><?php _e('Link:'); ?></label>
			<input name="<?php echo $this->get_field_name('link'); ?>" id="<?php echo $this->get_field_id('link'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($link); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('hover'); ?>" class="wdgtcstmzr-label"><?php _e('Link Hover:'); ?></label>
			<input name="<?php echo $this->get_field_name('hover'); ?>" id="<?php echo $this->get_field_id('hover'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($hover); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('background'); ?>" class="wdgtcstmzr-label"><?php _e('Background:'); ?></label>
			<input name="<?php echo $this->get_field_name('background'); ?>" id="<?php echo $this->get_field_id('background'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($background); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-box">
			<label for="<?php echo $this->get_field_id('border'); ?>" class="wdgtcstmzr-label"><?php _e('Border:'); ?></label>
			<input name="<?php echo $this->get_field_name('border'); ?>" id="<?php echo $this->get_field_id('border'); ?>" class="wdgtcstmzr-color" type="text" value="<?php echo esc_attr($border); ?>" data-default-color="#ffffff"/>
			</p>
			<p class="wdgtcstmzr-input">
			<label for="<?php echo $this->get_field_id('width'); ?>" class="wdgtcstmzr-label"><?php _e('Border width:'); ?></label>
			<input name="<?php echo $this->get_field_name('width'); ?>" id="<?php echo $this->get_field_id('width'); ?>" type="text" value="<?php echo $width; ?>" />
			</p>
			<p class="wdgtcstmzr-input">
			<label for="<?php echo $this->get_field_id('radius'); ?>" class="wdgtcstmzr-label"><?php _e('Border radius:'); ?></label>
			<input name="<?php echo $this->get_field_name('radius'); ?>" id="<?php echo $this->get_field_id('radius'); ?>" type="text" value="<?php echo $radius; ?>" />
			</p>
			<p class="wdgtcstmzr-free">
				<?php echo 'Get <strong>Widget Customizer Premium</strong> version with more options, presets, google fonts, custom CSS and import and export functions @ this <a href="http://codecanyon.net/item/widget-customizer-for-wordpress/8340408?ref=dzeriho" target="_blank">LINK</a> - <em>mihajlovicnenad.com</em>'; ?>
			</p>
		</div>

<?php
	}
}


?>