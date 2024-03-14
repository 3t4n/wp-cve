<?php
/*
Plugin Name: Today's Date Inserter
Plugin URI: http://www.mediawebsite.com/mwd/services_wordpress-plugin.php
Description: Easily add todays day date and or time to any page or post using a easy shortcode.  You can set a default format for all your posts, or format individual posts with their own format by adding a format string.
Author: Lou Osinski
Author URI: http://www.mediawebsite.com
Version: 1.2.1
*/


date_default_timezone_set(get_option('timezone_string'));
$options=get_option('lfo_todays_date_options');  //gets the 'lfo_todays_date_options' options from the database
$lfo_date_format_string=$options['text_string'];					//used the text_string element of the array
if (strlen($lfo_date_format_string)<1){
	$lfo_date_format_string="l, F jS Y";
}

//Create a shortcode hook so we can use this in out posts
add_shortcode('todaysdate', 'lfo_todays_date_shortcode');

//Create the Options Page and add it to the settings menu
add_action( 'admin_menu', 'lfo_todays_date_add_options_page');

//Register and define the settings
add_action('admin_init',  'lfo_todays_date_admin_init');

//use the widgets_init hook to register our widget
add_action('widgets_init', 'lfo_todays_date_register_widgets');

function lfo_todays_date_register_widgets(){
	register_widget('lfo_todays_date_widget');	
}

//set up the widget class
class lfo_todays_date_widget extends WP_widget {
	
	function lfo_todays_date_widget(){
		$widget_ops=array( 'classname'=> 'lfo_todays_date_widget_class','description'=>'Display Today\'s Date and Time');
		$this->WP_Widget('lfo_todays_date_widget','Today\'s Date',$widget_ops);
	}
	
	function form($instance){
		$defaults=array( 'title' =>"Today's Date"  , 'format' => 'l, F j S, Y' );
		$instance=wp_parse_args(  (array)$instance , $defaults );
		$title=$instance['title'];
		$format=$instance['format'];
		?>
		<p>Title: <input class="widefat" name="<?php echo $this->get_field_name('title');?> type="text" value="<?php echo esc_attr($title);?>" /></p>
		<p>Format String: <input class="widefat" name="<?php echo $this->get_field_name('format');?> type="text" value="<?php echo esc_attr($format);?>" /></p>
		<?php
	}
	
	function update($new_instance,$old_instance){
		$instance=$old_instance;
		$instance['title']=strip_tags($new_instance['title']);
		$instance['format']=strip_tags($new_instance['format']);
		return $instance;
		
	}
	
	function widget($args, $instance){
		extract ($args);
		echo $before_widget;
		$title=apply_filters('widget_title',$instance['title']);
		echo $before_title."Today's Date".$after_title;
		echo date($instance['format']);;
		echo $after_widget;	
		
	}
	
}


function lfo_todays_date_shortcode( $attr )
{
	global $lfo_date_format_string;
	$date_string=$lfo_date_format_string;
	if (strlen($attr['format'])>0){
		$date_string=$attr['format'];
	}
	return date($date_string);
}

//adds the Today's Date Options to the settings menu
function lfo_todays_date_add_options_page(){
	add_options_page( 'Todays Date Options','Todays Date','manage_options','lfo_todays_date','lfo_todays_date_options_page');	
	
}

//Ctreate the Options Page
function lfo_todays_date_options_page(){
	global $lfo_date_format_string;
	//draw the options page
	?>
	<div class='wrap'>
			<h2>Today's Date</h2>
			<form action="options.php" method="post">
			<?php settings_fields('lfo_todays_date_options'); ?>
			<?php do_settings_sections('lfo_todays_date'); ?>
			if no format string is entered, l, F j S Y will be used as the default.<p>
			<input type="submit" name="Submit" value ="Save Changes" />
			</form>
			<p>&nbsp;</p>
			<h3>How to Use</h3>
			Simply use the short code <b>[todaysdate]</b> in your text and when the post or page is displayed, <b>[todaysdate]</b> will be replaced by the current date and/or time that you defined in the format string below.
			<p>For example, if you placed the following text in your post and your format string below was set to <b>l, F jS Y</b><br>
			-> Thank you for visiting my site today [todaysdate].<p>
			It would be displayed as<br>
			-> Thank you for visiting my site today <?php echo date("l, F jS Y") ?>
			<p>You can also override the stored format string if you wish to use a different format for a specific post.
			<p>[todaysdate format="l"] -><strong>will display:</strong> <?php echo date("l") ?><br>
			[todaysdate format="F Y"] -> <strong>will display:</strong> <?php echo date("F Y") ?><br>
	</div>
	<?php
}


function lfo_todays_date_admin_init(){
	//register the options for storage in the options table
	register_setting( 'lfo_todays_date_options',	'lfo_todays_date_options', 'lfo_todays_date_validate_options');
	//add the settings section to the options page form
	add_settings_section( 'lfo_todays_date_main', 'Todays Date Settings', 'lfo_todays_date_section_text', 'lfo_todays_date');
	//add the settings fields to the options page form
	add_settings_field( 'lfo_todays_date_text_string', 'Enter the format string here', 'lfo_todays_date_setting_input', 'lfo_todays_date', 'lfo_todays_date_main');

}

//Make the section header
function lfo_todays_date_section_text(){
	global $lfo_date_format_string;
	echo"
	<table width='95%' cellpadding='5' cellspacing='0'>
	<tr style='vertical-align:top;'>
		<td>
			<strong>Date formatting options</strong><br>
			<p> You can format the displayed date string using any valid php date() function format options.<br>  
			For a complete list of Format string options, <a href='http://php.net/manual/en/function.date.php' target='_blank'>Click here</a>.  </p>
			<p>Some examples are: <p>
			l, F jS Y<br> <i>will display </i>".date("l, F jS Y")."<p>
			\T\o\d\a\y \i\s l, F, jS Y<br> <i>will display </i>".date("\T\o\d\a\y \i\s l, F, jS Y")."<p>
			<p><strong>Your string currently displays as:</strong> ".date($lfo_date_format_string)."<br>
			<strong>This plugin uses wordpress' timezone setting which is currently set to: </strong>".get_option('timezone_string')."
		</td>
		<td width='30%' style='text-align:right;'>
		";
			echo '
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="6WKCAA3R3CYY2">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>';
		echo "
		</td>
	</tr>
	</table>
	";	
}

//Display the form field
function lfo_todays_date_setting_input() {
	//get option 'text-string' value from the database
	$options=get_option('lfo_todays_date_options');
	$text_string=$options['text_string'];
	//echo the field
	echo "<input id='text_string' name='lfo_todays_date_options[text_string]' type='text' value='$text_string' />";
}

//Valudate the User's input
function lfo_todays_date_validate_options( $input ){
	$valid=array();
	$valid['text_string']=preg_replace('/[^a-zA-Z]/','',$input['text_string']);
	return $input;	
}



?>