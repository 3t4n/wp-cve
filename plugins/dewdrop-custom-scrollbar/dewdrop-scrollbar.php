<?php 
/*
Plugin Name: Dewdrop Scrollbar
Plugin URI: http://plugins.staritsoft.com/dewdrop-custom-scrollbar/
Description: This is free version of Dewdrop Custom Scrollbar. This plugin give your wordpress site a customizable, stylish & responsive scrollbar. You can easily customize your scrollbar style from Settings > Dewdrop Scrollbar Options.
Author: ABDUR ROB (SOYON)
Version: 1.4
Author URI: http://plugins.staritsoft.com/
*/
 
/* Latest jQuery from Wordpress */
function dewdrop_scrollbar_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'dewdrop_scrollbar_latest_jquery');


/* Extra jQuery & CSS file include not for admin */
function my_scripts_method() {
	define('DEWDROP_SCROLLBAR_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

	wp_enqueue_script('dewdrop-scrollbar-main', DEWDROP_SCROLLBAR_WP . 'js/jquery.nicescroll.min.js', array('jquery'));
	wp_enqueue_style('dewdrop-scrollbar-css', DEWDROP_SCROLLBAR_WP . 'css/style.css');
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' );


function add_dewdrop_options()
{
	add_options_page('Dewdrop Scrollbar Options', 'Dewdrop Scrollbar Options', 'manage_options', 'dewdrop-settings', 'dewdrop_scrollbar_options');
}
add_action('admin_menu', 'add_dewdrop_options');

function color_picker_fucntion( $hook_suffix ) {
	// first check that $hook_suffix is appropriate for your admin page
	define('DEWDROP_SCROLLBAR_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	
	wp_enqueue_script( 'wp-color-picker' );
	// load the minified version of custom script
	wp_enqueue_script( 'my-color-field', plugins_url( 'js/javascript.js', __FILE__ ), array( 'jquery', 'wp-color-picker' ), false, true );
	wp_enqueue_style( 'wp-color-picker' );
}
add_action( 'admin_enqueue_scripts', 'color_picker_fucntion' );

// Default values
$scrollbar_options = array(
	'cursor_color' => '#02b2fd',
	'cursor_width' => '10px',
	'cursor_border_width' => '0px',
	'cursor_border_color' => '#1e1f23',
	'cursor_height' => '70',
	'border_radius' => '5px',
	'scroll_speed' => '60',
	'scroll_auto_hide_mode' => 'false'
);

if ( is_admin() ) : // Load only if we are viewing an admin page

function dewdrop_scrollbar_settings() {
	// Register settings and call sanitation function
	register_setting( 'scrollbar_p_options', 'scrollbar_options', 'scrollbar_validate_options' );
}

add_action( 'admin_init', 'dewdrop_scrollbar_settings' );

// Store layouts views in array
$scroll_auto_hide_mode = array(
	'auto_hide_yes' => array(
		'value' => 'true',
		'label' => 'Enable auto hide'
	),
	'auto_hide_no' => array(
		'value' => 'false',
		'label' => 'Disable auto hide'
	)
);


// Function to generate options page
function dewdrop_scrollbar_options() {
	global $scrollbar_options, $scroll_auto_hide_mode;
	
	if ( !isset( $_REQUEST['updated'] ) )
		$_REQUEST['updated'] = false; // This checks whether the form has just been submitted. ?>


	<div class="wrap">
	
	<strong>If you think my plugins works helped you some way, buy me a cup of coffee for inspiration ;).</strong>
	<p>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="UY54977Z8KSUW">
	<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
	<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	</p>
	<hr />
	
	<h2>Dewdrop: Scrollbar Options - <small><a href="https://goo.gl/ZYh1FL" target="_blank">Try Pro version</a></small></h2>
	
	<form method="post" action="options.php">
	
	<?php $settings = get_option( 'scrollbar_options', $scrollbar_options ); ?>
	
	<?php settings_fields( 'scrollbar_p_options' ); ?>
	
	<table class="form-table">
		<tr>
			<td align="center"><input type="submit" class="button-secondary" name="scrollbar_options[back_as_default]" value="Back as default" /></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Settings" /></td>
		</tr>
		
		<tr>
			<th scope="row">
				Ready Styles <br>
				<p class="description"><a href="https://goo.gl/ZYh1FL" target="_blank">Try Pro</a> version to enable this feature.</p>
			</th>
			<td>:</td>
			<td style="background-color:#E6E6E6;">
				<p class="description">Select a style for your scrollbar or you can use custom style. Default ready style is <strong>Style 1</strong></p>
				<table>
					<tr valigh="top">
						<td>
							<label for="style1">
								<img src="<?php echo plugins_url('ready-styles/style1.png', __FILE__ ); ?>" height="100%" alt="style1" title="Style 1" /><br />
								<input type="radio" disabled="disabled" checked="checked" /><br />
								1
							</label>
						</td>

						<td>
							<label for="style2">
								<img src="<?php echo plugins_url('ready-styles/style2.png', __FILE__ ); ?>" height="100%" alt="Style2" title="Style 2" /><br />
								<input type="radio" disabled="disabled" /><br />
								2
							</label>
						</td>

						<td>
							<label for="style3">
								<img src="<?php echo plugins_url('ready-styles/style3.png', __FILE__ ); ?>" height="100%" alt="style3" title="Style 3" /><br />
								<input type="radio" disabled="disabled" /><br />
								3
							</label>
						</td>

						<td>
							<label for="style4">
								<img src="<?php echo plugins_url('ready-styles/style4.png', __FILE__ ); ?>" height="100%" alt="style4" title="Style 4" /><br />
								<input type="radio" disabled="disabled" /><br />
								4
							</label>
						</td>

						<td>
							<label for="style5">
								<img src="<?php echo plugins_url('ready-styles/style5.png', __FILE__ ); ?>" height="100%" alt="style5" title="Style 5" /><br />
								<input type="radio" disabled="disabled" /><br />
								5
							</label>
						</td>

						<td>
							<label for="style6">
								<img src="<?php echo plugins_url('ready-styles/style6.png', __FILE__ ); ?>" height="100%" alt="style6" title="Style 6" /><br />
								<input type="radio" disabled="disabled" /><br />
								6
							</label>
						</td>

						<td>
							<label for="style7">
								<img src="<?php echo plugins_url('ready-styles/style7.png', __FILE__ ); ?>" height="100%" alt="style7" title="Style 7" /><br />
								<input type="radio" disabled="disabled" /><br />
								7
							</label>
						</td>

						<td>
							<label for="style8">
								<img src="<?php echo plugins_url('ready-styles/style8.png', __FILE__ ); ?>" height="100%" alt="style8" title="Style 8" /><br />
								<input type="radio" disabled="disabled" /><br />
								8
							</label>
						</td>

						<td>
							<label for="style9">
								<img src="<?php echo plugins_url('ready-styles/style9.png', __FILE__ ); ?>" height="100%" alt="style9" title="Style 9" /><br />
								<input type="radio" disabled="disabled" /><br />
								9
							</label>
						</td>

						<td>
							<label for="style10">
								<img src="<?php echo plugins_url('ready-styles/style10.png', __FILE__ ); ?>" height="100%" alt="style10" title="Style 10" /><br />
								<input type="radio" disabled="disabled" /><br />
								10
							</label>
						</td>

						<td>
							<label for="style11">
								<img src="<?php echo plugins_url('ready-styles/style11.png', __FILE__ ); ?>" height="100%" alt="style11" title="Style 11" /><br />
								<input type="radio" disabled="disabled" /><br />
								11
							</label>
						</td>

						<td>
							<label for="style12">
								<img src="<?php echo plugins_url('ready-styles/style12.png', __FILE__ ); ?>" height="100%" alt="style12" title="Style 12" /><br />
								<input type="radio" disabled="disabled" /><br />
								12
							</label>
						</td>

						<td>
							<label for="style13">
								<img src="<?php echo plugins_url('ready-styles/style13.png', __FILE__ ); ?>" height="100%" alt="style13" title="Style 13" /><br />
								<input type="radio" disabled="disabled" /><br />
								13
							</label>
						</td>

						<td>
							<label for="style14">
								<img src="<?php echo plugins_url('ready-styles/style14.png', __FILE__ ); ?>" height="100%" alt="style14" title="Style 14" /><br />
								<input type="radio" disabled="disabled" /><br />
								14
							</label>
						</td>

						<td>
							<label for="style15">
								<img src="<?php echo plugins_url('ready-styles/style15.png', __FILE__ ); ?>" height="100%" alt="style15" title="Style 15" /><br />
								<input type="radio" disabled="disabled" /><br />
								15
							</label>
						</td>

						<td>
							<label for="style16">
								<img src="<?php echo plugins_url('ready-styles/style16.png', __FILE__ ); ?>" height="100%" alt="style16" title="Style 16" /><br />
								<input type="radio" disabled="disabled" /><br />
								16
							</label>
						</td>

						<td>
							<label for="style17">
								<img src="<?php echo plugins_url('ready-styles/style17.png', __FILE__ ); ?>" height="100%" alt="style17" title="Style 17" /><br />
								<input type="radio" disabled="disabled" /><br />
								17
							</label>
						</td>

						<td>
							<label for="style18">
								<img src="<?php echo plugins_url('ready-styles/style18.png', __FILE__ ); ?>" height="100%" alt="style18" title="Style 18" /><br />
								<input type="radio" disabled="disabled" /><br />
								18
							</label>
						</td>

						<td>
							<label for="style19">
								<img src="<?php echo plugins_url('ready-styles/style19.png', __FILE__ ); ?>" height="100%" alt="style19" title="Style 19" /><br />
								<input type="radio" disabled="disabled" /><br />
								19
							</label>
						</td>

						<td>
							<label for="style20">
								<img src="<?php echo plugins_url('ready-styles/style20.png', __FILE__ ); ?>" height="100%" alt="style20" title="Style 20" /><br />
								<input type="radio" disabled="disabled" /><br />
								20
							</label>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row"><label for="cursor_color">Scrollbar color</label></th>
			<td>:</td>
			<td>
				<input  id='cursor_color' type="text" name="scrollbar_options[cursor_color]" value="<?php echo stripslashes($settings['cursor_color']); ?>" class="my-color-field" />
				<p class="description">Change your scrollbar color. You can also add html HEX color code. Default color is #02b2fd</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_width">Scrollbar width</label></th>
			<td>:</td>
			<td>
				<input id="cursor_width" type="text" name="scrollbar_options[cursor_width]" value="<?php echo stripslashes($settings['cursor_width']); ?>" />
				<p class="description">Enter your scrollbar width in pixel, default is 10 (pixel).</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_height">Scrollbar height</label></th>
			<td>:</td>
			<td>
				<input id="cursor_height" type="text" name="scrollbar_options[cursor_height]" value="<?php echo stripslashes($settings['cursor_height'] == '' ? '70' : $settings['cursor_height']); ?>" />
				<p class="description">Enter your scrollbar height in pixel, default is 70 (pixel).</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_border_width">Scrollbar border width</label></th>
			<td>:</td>
			<td>
				<input id="cursor_border_width" type="text" name="scrollbar_options[cursor_border_width]" value="<?php echo stripslashes($settings['cursor_border_width']); ?>" />
				<p class="description">Enter scrollbar border width. Default is 0 (pixel).</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row">
				<label for="cursor_border_type">Scrollber border type</label>
				<p class="description"><a href="https://goo.gl/ZYh1FL" target="_blank">Try Pro</a> version to enable this feature.</p>
			</th>
			<td>:</td>
			<td>
				<input type="radio" id="solid" disabled="disabled" checked="checked" />
				<label for="solid">Solid</label><br />
				
				<input type="radio" id="dotted" disabled="disabled" />
				<label for="dotted">Dotted</label><br />
				
				<input type="radio" id="dashed" disabled="disabled" />
				<label for="dashed">Dashed</label><br />
				
				<input type="radio" id="double" disabled="disabled" />
				<label for="double">Double</label><br />
				
				<input type="radio" id="inset" disabled="disabled" />
				<label for="inset">Inset</label><br />
				
				<input type="radio" id="outset" disabled="disabled" />
				<label for="outset">Outset</label><br />
				<p class="description">Select scrollber border type. You can chose any one of them. Default is Solid.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_border_color">Scrollbar border color</label></th>
			<td>:</td>
			<td>
				<input  id='cursor_border_color' type="text" name="scrollbar_options[cursor_border_color]" value="<?php echo stripslashes($settings['cursor_border_color']); ?>" class="my-color-field" />
				<p class="description">Change your scrollbar border color. Default is #1e1f23.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="border_radius">Scrollbar border radius</label></th>
			<td>:</td>
			<td>
				<input id="border_radius" type="text" name="scrollbar_options[border_radius]" value="<?php echo stripslashes($settings['border_radius']); ?>" />
				<p class="description">Enter your scrollbar border radius in pixel. Default is 5px.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="scroll_speed">Scrollbar speed</label></th>
			<td>:</td>
			<td>
				<input id="scroll_speed" type="text" name="scrollbar_options[scroll_speed]" value="<?php echo stripslashes($settings['scroll_speed']); ?>" />
				<p class="description">Enter your scrolling speed, default value is 60. Increase value make scrollbar speed slower &amp; decrease value make scrollbar speed faster.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="scroll_auto_hide_mode">Scrollbar Autohide settings</label></th>
			<td>:</td>
			<td>
				<?php foreach( $scroll_auto_hide_mode as $activate ) : ?>
				<input type="radio" id="hide_<?php echo $activate['value']; ?>" name="scrollbar_options[scroll_auto_hide_mode]" value="<?php esc_attr_e( $activate['value'] ); ?>" <?php checked( $settings['scroll_auto_hide_mode'], $activate['value'] ); ?> />
				<label for="hide_<?php echo $activate['value']; ?>"><?php echo $activate['label']; ?></label><br />
				<?php endforeach; ?>
			</td>
		</tr>

		<tr>
			<td align="center"><input type="submit" class="button-secondary" name="scrollbar_options[back_as_default]" value="Back as default" /></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Settings" /></td>
		</tr>
		
		<tr>
			<td scope="row" colspan="3">
				<h3>Pro Version Features:</h3>
				<p class="description">Only available for Pro version. <a href="https://goo.gl/ZYh1FL" target="_blank">Try Now</a> to enable this features.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_min_opacity">Scrollber Inactive state opacity</label></th>
			<td>:</td>
			<td>
				<input id="cursor_min_opacity" type="text" disabled="disabled" value="0" />
				<p class="description">Change opacity very cursor is inactive (scrollabar "hidden" state), range from 1 to 0, default is 0 (hidden).</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_max_opacity">Scrollber Active state opacity</label></th>
			<td>:</td>
			<td>
				<input id="cursor_max_opacity" type="text" disabled="disabled" value="1" />
				<p class="description">Change opacity very cursor is active (scrollabar "visible" state), range from 1 to 0, default is 1 (full opacity).</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="scrollber_bar_step">Scrollber ber step</label></th>
			<td>:</td>
			<td>
				<input id="scrollber_bar_step" type="text" disabled="disabled" value="40" />
				<p class="description">Enter your scrollber scrolling speed with mouse wheel, default value is 40 (pixel).</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="scroll_align_mode">Scrollber alignment settings</label></th>
			<td>:</td>
			<td>
				<input type="radio" disabled="disabled" />
				<label>Align Right</label><br />
				<input type="radio" disabled="disabled" />
				<label>Align Left</label><br />
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="touch_behavior_mode">Touch Behavior settings</label></th>
			<td>:</td>
			<td>
				<input type="radio" disabled="disabled" />
				<label>Disable Cursor-drag scrolling</label><br />
				<input type="radio" disabled="disabled" />
				<label>Enable Cursor-drag scrolling</label><br />
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="sensitive_rail_mode">Sensitive rail settings</label></th>
			<td>:</td>
			<td>
				<input type="radio" disabled="disabled" />
				<label>Enable</label><br />
				<input type="radio" disabled="disabled" />
				<label>Disable</label><br />
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="cursor_hide_delay">Scrollber hide delay time</label></th>
			<td>:</td>
			<td>
				<input id="cursor_hide_delay" type="text" disabled="disabled" value="500" />
				<p class="description">Cursor hide delay time in Milisecond. If you wnat to set 0.5 second, then set 500 here. Default is 500.</p>
			</td>
		</tr>
	
		<tr valign="top">
			<th scope="row"><label for="zindex_value">Z-index setting value</label></th>
			<td>:</td>
			<td>
				<input id="zindex_value" type="text" disabled="disabled" value="9999" />
				<p class="description">Change z-index for scrollbar div, default value is 9999.</p>
			</td>
		</tr>

		<tr>
			<td align="center"><input type="submit" class="button-secondary" name="scrollbar_options[back_as_default]" value="Back as default" /></td>
			<td colspan="2"><input type="submit" class="button-primary" value="Save Settings" /></td>
		</tr>
	</table>
	
	</form>
	
	</div>
	
	<?php
}

// Inputs validation, if fails validations replace by default values.
function scrollbar_validate_options( $input ) {
	global $scrollbar_options, $scroll_auto_hide_mode;
	
	$settings = get_option( 'scrollbar_options', $scrollbar_options );
	
	// We strip all tags from the text field, to avoid Vulnerabilities like XSS
	
	$input['cursor_color'] = isset( $input['back_as_default'] ) ? '#02b2fd' : wp_filter_post_kses( $input['cursor_color'] );
	$input['cursor_width'] = isset( $input['back_as_default'] ) ? '10px' : wp_filter_post_kses( $input['cursor_width'] );
	$input['cursor_border_width'] = isset( $input['back_as_default'] ) ? '0px' : wp_filter_post_kses( $input['cursor_border_width'] );
	$input['cursor_border_color'] = isset( $input['back_as_default'] ) ? '#1e1f23' : wp_filter_post_kses( $input['cursor_border_color'] );
	$input['cursor_height'] = isset( $input['back_as_default'] ) ? '70' : wp_filter_post_kses( $input['cursor_height'] );
	$input['border_radius'] = isset( $input['back_as_default'] ) ? '5px' : wp_filter_post_kses( $input['border_radius'] );
	$input['scroll_speed'] = isset( $input['back_to_default'] ) ? '60' : wp_filter_post_kses( $input['scroll_speed'] );
	$input['scroll_auto_hide_mode'] = isset( $input['back_to_default'] ) ? 'false' : wp_filter_post_kses( $input['scroll_auto_hide_mode'] );
	
	
	return $input;
}

endif;		// Endif is_admin()

function scroller_customizable_scrollbar_active() { ?>

<?php global $scrollbar_options; $scroller_settings = get_option( 'scrollbar_options', $scrollbar_options ); ?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var nice = $("html").niceScroll({
			cursorcolor: "<?php echo $scroller_settings['cursor_color']; ?>",
			cursorwidth: "<?php echo $scroller_settings['cursor_width']; ?>",
			cursorborder: "<?php echo $scroller_settings['cursor_border_width'].' solid '.$scroller_settings['cursor_border_color']; ?>",
			cursorborderradius: "<?php echo $scroller_settings['border_radius']; ?>",
			cursorfixedheight: "<?php echo $scroller_settings['cursor_height'] == '' ? '70' : $scroller_settings['cursor_height']; ?>",
			scrollspeed: <?php echo $scroller_settings['scroll_speed']; ?>,
			autohidemode: <?php echo $scroller_settings['scroll_auto_hide_mode']; ?>,
			bouncescroll: true
		});
	});
</script>

<?php
}
add_action('wp_head', 'scroller_customizable_scrollbar_active');