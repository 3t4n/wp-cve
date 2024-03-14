<?php
/*
Plugin Name: Milestone
Plugin URI: http://andrewferguson.net/wordpress-plugins/milestone/
Description: Milestone clone for self hosted WordPress installations. Counts down to a big event and then displays a message!
Version: 1.0
Author: Andrew Ferguson
Author URI: http://www.andrewferguson.net

Milestone for Self Hosted - Milestone clone for self hosted WordPress installations. Counts down to a big event and then displays a message!
Copyright (c) 2012 Andrew Ferguson

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.


@package Fergcorp_Milestone
@author Andrew Ferguson
@since
@access private
{@internal Missing}
@param		type		$varname	Description
@return		type					Description
@todo		

*/

//Via Alex King, http://alexking.org/blog/2011/12/15/wordpress-plugins-and-symlinks

$my_plugin_file = __FILE__;

if (isset($plugin)) {
	$my_plugin_file = $plugin;
}
else if (isset($mu_plugin)) {
	$my_plugin_file = $mu_plugin;
}
else if (isset($network_plugin)) {
	$my_plugin_file = $network_plugin;
}

define('MY_PLUGIN_FILE', $my_plugin_file);
define('MY_PLUGIN_PATH', WP_PLUGIN_DIR.'/'.basename(dirname($my_plugin_file)));

load_plugin_textdomain('fergcorp_milestone', false, basename( dirname( MY_PLUGIN_FILE ) ) . '/lang' );


/**
 * Widget class for Milestone
 * 
 * @since 1.0
 * @access public
 * @author Andrew Ferguson
 */
class Fergcorp_Milestone_Widget extends WP_Widget{
	
	public function __construct(){
		parent::__construct(
					'fergcorp_milestone', // Base ID
					'Milestone', // Name
					array( 'description' => __('A simple way to create a countdown to a given date', 'fergcorp_milestone' ), ) // Args
		);
	}
	
	public function form( $instance){
		if ( $instance ) {
			$title = esc_attr( $instance['title'] );
			$event = $instance['event'];
			$month = $instance['month'];
			$day = $instance['day'];
			$year = $instance['year'];
			$hour = $instance['hour'];
			$minute = $instance['minute'];
			$message = $instance['message'];
		}
		else {
			$title = "";
			$event = __( 'The Big Day', 'fergcorp_milestone');
			$month = date("n");
			$day = date("j");
			$year = date("Y");
			$hour = date("G");
			$minute = date("i");
			$message = __( 'The big day is here!', 'fergcorp_milestone');
		}
		
		?>
		<div class="milestone-widget">
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'fergcorp_milestone'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'event' ); ?>"><?php _e( 'Event:' , 'fergcorp_milestone'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'event' ); ?>" name="<?php echo $this->get_field_name( 'event' ); ?>" type="text" value="<?php echo $event; ?>" />
			</p>
			
			<fieldset>
				<legend><?php _e('Date and Time', 'fergcorp_milestone'); ?></legend>
		
				<label for="<?php echo $this->get_field_id( 'month' ); ?>" class="assistive-text"><?php _e('Month', 'fergcorp_milestone'); ?></label>
				<select class="month" id="<?php echo $this->get_field_id( 'month' ); ?>" name="<?php echo $this->get_field_name( 'month' ); ?>">
					<?php
						for($i=1; $i<=12; $i++){	
							echo "<option " . ( $i == $month ? "selected='selected'" : '') . " value='$i' >".date("m-M", mktime(0, 0, 0, $i, 10))."</option>";
						}
					?>
				</select>
				
				<label for="<?php echo $this->get_field_id( 'day' ); ?>" class="assistive-text"><?php _e('Day', 'fergcorp_milestone'); ?></label>
				<input class="day" id="<?php echo $this->get_field_id( 'day' ); ?>" name="<?php echo $this->get_field_name( 'day' ); ?>" type="text" value="<?php echo $day; ?>" />,
				
				<label for="<?php echo $this->get_field_id( 'year' ); ?>" class="assistive-text"><?php _e('Year', 'fergcorp_milestone'); ?></label>
				<input class="year" id="<?php echo $this->get_field_id( 'year' ); ?>" name="<?php echo $this->get_field_name( 'year' ); ?>" type="text" value="<?php echo $year; ?>" /> @
				
				<label for="<?php echo $this->get_field_id( 'hour' ); ?>" class="assistive-text"><?php _e('Hour', 'fergcorp_milestone'); ?></label>
				<input class="hour" id="<?php echo $this->get_field_id( 'hour' ); ?>" name="<?php echo $this->get_field_name( 'hour' ); ?>" type="text" value="<?php echo $hour; ?>" /> : 
				
				<label for="<?php echo $this->get_field_id( 'minute' ); ?>" class="assistive-text"><?php _e('Minute', 'fergcorp_milestone'); ?></label>
				<input class="minute" id="<?php echo $this->get_field_id( 'minute' ); ?>" name="<?php echo $this->get_field_name( 'minute' ); ?>" type="text" value="<?php echo $minute; ?>" />
			
			</fieldset>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'message' ); ?>"><?php _e( 'Message' , 'fergcorp_milestone'); ?></label>
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'message' ); ?>" name="<?php echo $this->get_field_name( 'message' ); ?>"><?php echo $message; ?></textarea>
			</p>
		
		</div>
						<?php
	}
	
	public function update( $new_instance, $old_instance ){
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['event'] = strip_tags( $new_instance['event'] );
		$instance['month'] = intval( $new_instance['month'] );
		$instance['day'] = intval( $new_instance['day'] );
		$instance['year'] = intval( $new_instance['year'] );
		$instance['hour'] = intval( $new_instance['hour'] );
		$instance['minute'] = intval( $new_instance['minute'] );
		$instance['message'] = strip_tags( $new_instance['message'] );
		
		return $instance;		
	}
	
	public function calculate_units($eventDiff, $eventDate){
			
		if($eventDiff >= 31536000+date("L", $eventDate)*86400){
			$value = floor($eventDiff/(31536000+date("L", $eventDate)*86400));
			$unit = _n("year", "years",  $value, "fergcorp_milestone");
		}
		elseif($eventDiff >= 86400*date("t", $eventDate) ){
			$value = floor($eventDiff / ( 86400 * 30 ) );
			$unit = _n("month", "months",  $value, "fergcorp_milestone");
		}
		elseif($eventDiff >= 86400){
			$value = floor($eventDiff/86400);
			$unit = _n("day", "days",  $value, "fergcorp_milestone");
		}
		elseif($eventDiff >= 3600){
			$value = floor($eventDiff/3600);
			$unit = _n("hour", "hours",  $value, "fergcorp_milestone");;
		}
		elseif($eventDiff >= 60){	
			$value =  floor($eventDiff/60);
			$unit = _n("minute", "minutes",  $value, "fergcorp_milestone");;
		}
		else{
			$value = $eventDiff;
			$unit = _n("second", "seconds",  $value, "fergcorp_milestone");
		}
		
		return array(	"value" => $value,
						"unit" => $unit,
					);
			
	}
		
	public function widget( $args, $instance ){
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( $instance['title'] ); //$instance['title']
			echo $args['after_title'];
		}
		
		$eventDate = mktime($instance['hour'], $instance['minute'], 0, $instance['month'], $instance['day'], $instance['year']);
		
		echo '<div class="milestone-content">';
		echo '	<div class="milestone-header">';
		echo '		<strong class="event">' . $instance['event'] . '</strong>';
		echo '		<span class="date">' . date_i18n( get_option('date_format'), $eventDate) . '</span>';
		echo '	</div>';
		
		$eventDiff = $eventDate - time();
		
		if($eventDiff > 0){
			echo '<div class="milestone-countdown">';
			
			$calculate_units = $this->calculate_units($eventDiff, $eventDate);

			echo '<span class="difference">' . $calculate_units["value"] . '</span> ';
			echo '<span class="label">' . $calculate_units["unit"] . '</span> ';
			echo __('to go.', 'fergcorp_milestone');
			echo '</div>';
			echo '</div><!--milestone-content-->';
		}
		else{
			echo '	<div class="milestone-message">' . $instance['message'] . '</div>';
			echo '</div><!--milestone-content-->';
		}
		echo $args['after_widget'];
	}
}

function fergcorp_milestone_script()
{
	wp_register_style( 'fergcorp-milestone-style', plugins_url( '/css/fergcorp_milestone-style.css', MY_PLUGIN_FILE), array(), '20120208', 'all' );
	wp_enqueue_style( 'fergcorp-milestone-style' );
}

function fergcorp_milestone_register_widgets() {
	register_widget( 'Fergcorp_Milestone_Widget' );
}
add_action( 'widgets_init', 'fergcorp_milestone_register_widgets' );
add_action( 'wp_enqueue_scripts', 'fergcorp_milestone_script' );
add_action( 'admin_head', 'fergcorp_milestone_script' );

?>