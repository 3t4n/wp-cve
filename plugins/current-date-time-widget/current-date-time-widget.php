<?php

/*
Plugin Name: Current Date & Time Widget
Plugin URI: http://blog.realthemes.com/2008/06/current-date-time-widget/
Description: Provides a widget that shows the current date and time given a specified <a href="http://us3.php.net/timezones">timezone</a> and <a href="http://us.php.net/date">format</a>.
Version: 1.0.3
Author: Chris Jean
Author URI: http://realthemes.com
*/

/*
This plugin requires the use of PHP 5.1.0+ since versions of PHP
prior to this do not support the date_default_timezone_set(), the
function used here to get the date and time for the requested
timezone.
*/

/*
Installation

1. Download and unzip the latest release zip file
2. Upload the entire current-date-time-widget directory to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the 'Widgets' page and add the 'Current Date & Time' widget to the desired area
5. Click 'Edit' next to the widget to customize the options
*/

/*
Version History

1.0.1 - 2008-06-04
	Basic proof of concept
1.0.2 - 2008-06-12
	Contained plugin code inside a class to prevent namespace collisions
	Standardized code with coding style (http://comox.textdrive.com/pipermail/wp-hackers/2006-July/006930.html)
1.0.3 - 2008-07-01
	Added support for PHP 4
*/

/*
To Do

Make customizations options easier
Code the widget to support multiple versions (like the text widget)
Internationlize
*/

/*
Copyright 2008 Chris Jean (email: chris@realthemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General License for more details.

You should have received a copy of the GNU General License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( !class_exists( 'CurrentDateTimeWidget' ) ) {
	class CurrentDateTimeWidget {
		var $optionName = "widget_current_date_time";
		
		
		function CurrentDateTimeWidget() {
			add_action( 'widgets_init', array( $this, 'init' ) );
		}
		
		function init() {
			if ( !function_exists( 'register_sidebar_widget' ) )
				return;
			
			register_sidebar_widget(__( 'Current Date & Time' ), array( $this, 'render' ) );
			register_widget_control(__( 'Current Date & Time' ), array( $this, 'control' ) );
		}
		
		function render() {
			$options = get_option( $this->optionName );
			
			$title = attribute_escape( $options['title'] );
			$timezone = attribute_escape( $options['timezone'] );
			$format = attribute_escape( $options['format'] );
			
			if ( '' == $format )
				$format = 'l, F j, g:i a';
			
			if ( '' != $timezone )
				date_default_timezone_set( $timezone );
			
			$currentDate = date( $format );
			
			
			echo $before_widget;
			
			if ( '' != $title )
				echo $before_title . $title . $after_title;
			
			echo '<p id="current-date-time">' . $currentDate . '</p>';
			echo $after_widget;
		}
		
		function control() {
			global $_POST;
			
			
			$options = $newoptions = get_option( $this->optionName );
			
			if ( $_POST['current-date-time-submit'] ) {
				$newoptions['title'] = strip_tags( stripslashes( $_POST['current-date-time-title'] ) );
				$newoptions['timezone'] = strip_tags( stripslashes( $_POST['current-date-time-timezone'] ) );
				$newoptions['format'] = strip_tags( stripslashes( $_POST['current-date-time-format'] ) );
			}
			
			if ( $options != $newoptions ) {
				$options = $newoptions;
				update_option( $this->optionName, $options );
			}
			
			$title = attribute_escape( $options['title'] );
			$timezone = attribute_escape( $options['timezone'] );
			$format = attribute_escape( $options['format'] );
			
			if ( '' == $format )
				$format = 'l, F j, g:i a';
			
?>
	<p><label for="current-date-time-title"><?php _e('Title:') ?></label> <input type="text" class="widefat" id="current-date-time-title" name="current-date-time-title" value="<?php echo $title ?>" /></p>
	<p><label for="current-date-time-timezone"><?php _e('Timezone:') ?></label> <a href="http://us3.php.net/timezones" target="timezones">valid timezones</a> <input type="text" class="widefat" id="current-date-time-timezone" name="current-date-time-timezone" value="<?php echo $timezone ?>" /></p>
	<p><label for="current-date-time-format"><?php _e('Format:') ?></label> <a href="http://us.php.net/date" target="formats">date formats</a> <input type="text" class="widefat" id="current-date-time-format" name="current-date-time-format" value="<?php echo $format ?>" /></p>
	<input type="hidden" name="current-date-time-submit" id="current-date-time-submit" value="1" />
<?php
			
		}
	}
}

if ( class_exists( "CurrentDateTimeWidget" ) ) {
	$currentDateTimeWidget = new CurrentDateTimeWidget();
}

?>
