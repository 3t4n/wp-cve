<?php
/*
Plugin Name: Simple Image Link
Plugin URI: http://blog-well.com/downloads/wordpress-image-link-plugin/
Description: A simple way to add an image with a link to your sidebar
Author: BlogWell
Version: 2.2.2
Author URI: http://blog-well.com/
*/

/*
Copyright (C) 2008-2009 blog-well.com (mad AT blog-well DOT com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// SIMPLEIMAGELINK_VERSION
// Version number of the properties saved - used to set meaningful defaults when new properties added, 
// or existing ones changed.
//
//		Version 2 - Display added
//		Version 3 - User roles & more considered when displaying image
//
define('SIMPLEIMAGELINK_VERSION', 3);	

if (!function_exists(widget_simpleimagelink_header))
{
	function widget_simpleimagelink_header($args)
	{	
		// Pre-2.6 compatibility
		if (!defined('WP_CONTENT_URL'))
		  define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
		if (!defined('WP_CONTENT_DIR'))
		  define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
		if (!defined('WP_PLUGIN_URL'))
		  define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
		if (!defined('WP_PLUGIN_DIR'))
		  define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
		$simpleimagelinkhead = "<!-- ImageLink widget --><link rel='stylesheet' href='" . WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . "/simple-image-link.css' type='text/css' media='screen' />";
		print($simpleimagelinkhead);
	}
}

class SimpleImageLink_Widget extends WP_Widget 
{
	function SimpleImageLink_Widget() 
	{
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'simpleimagelink', 
		                     'description' => __('Displays an image which has a link attached to it.', 'simple-image-link') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 350, 
		                      'height' => 350, 
							  'id_base' => 'simpleimagelink' );

		/* Create the widget. */
		$this->WP_Widget( 'simpleimagelink', __('Simple Image Link', 'simple-image-link'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) 
	{
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$showTitle = $instance['showTitle'];
		$version = $instance['version'];
		$title = $instance['title'];
		$showTitle = $instance['showTitle'];
		$alt = $instance['alt'];
		$showPopup = $instance['showPopup'];
		$img = $instance['img'];
		$imgWidth = $instance['imgWidth'];
		$imgHeight = $instance['imgHeight'];
		$url = $instance['url'];
		$urlTarget = $instance['urlTarget'];
		$noFollow = $instance['noFollow'];
		$paddingTop = $instance['paddingTop'];
		$paddingBottom = $instance['paddingBottom'];
		$alignment = $instance['alignment'];
		$text = $instance['text'];
		$break = $instance['break'];
		$shadowBox = $instance['shadowBox'];
		$role = $instance['role'];	
		$roleMethod = $instance['roleMethod'];	

		if (empty($alignment)) 
			$alignment = "center";
		
		if (false && ((null == $role) || (null == $roleMethod)))
		{
			$display = $instance['display'];
			$role = $display == 0 ? 'do-not-display' : '';
			$roleMethod = 0;
		}
		
		$role = strtolower($role);
		$display = strcmp($role, 'do-not-display') != 0;

		if (!is_admin())
		{
			if (!empty($role))
			{
				if (!$display)
					return;
				if ($roleMethod == 0)
				{
					$roleLookup = array('administrator' => 'level_8', 'editor' => 'level_3', 'author' => 'level_2', 'contributor' => 'level_1', 'subscriber' => 'level_0');
					$level = $roleLookup[$role];
				}
				else
					$level = strtolower($role);

				if (!current_user_can($level))
					return;
			}
			else
			{
				if ($roleMethod != 0)
				{
					if (is_user_logged_in())
						return;
				}
			}
		}

		$aAttribs = '';
		$imgStyle = '';
		$divStyle = '';
		$imgAttribs = '';
		$divAttribs = '';
		$relTags = '';

		if ($noFollow == 1)         { if (!empty($relTags)) $relTags .= ' '; $relTags .= 'nofollow'; }
		if ($shadowBox == 1)        { if (!empty($relTags)) $relTags .= ' '; $relTags .= 'shadowBox'; }

		if (!empty($imgWidth))      { $imgStyle .= ' width:' . $imgWidth . ';'; }
		if (!empty($imgHeight))     { $imgStyle .= ' height:' . $imgHeight . ';'; }
		if (!empty($paddingTop))    { $divStyle .= ' padding-top:' . $paddingTop . ';'; }
		if (!empty($paddingBottom)) { $divStyle .= ' padding-bottom:' . $paddingBottom . ';'; }

		if (!empty($imgStyle))      { $imgAttribs .= ' style="' . $imgStyle . '"'; }
		if (!empty($divStyle))      { $divAttribs .= ' style="' . $divStyle . '"'; }

		if (!empty($urlTarget))     { $aAttribs .= ' target="' . $urlTarget . '"'; }
		if (!empty($relTags))       { $aAttribs .= ' rel="' . $relTags . '"'; }

		if (!empty($alt))
		  $imgAttribs .= ' alt="' . $alt . '"';
		if ($showPopup == 1)
			if (!empty($url))
				$aAttribs .= ' title="' . $alt . '"';
			  else
				$imgAttribs .= ' title="' . $alt . '"';

		/* Before widget (defined by themes). */
		echo $before_widget;

		echo '<div class="widget_simpleimagelink_container" ' . $divAttribs .'>';

		// Highlight the fact that the image is not defined by enclosing the title within those << & >> characters.
		if (empty($img))
			$title = '&laquo;' . $title . '&raquo;';
			
		// Highlight that the image is not to be displayed
		if (!$display)
			$title .= '&#8855;';

		if (!empty($title) && (1 == $showTitle))
			echo $before_title . $title . $after_title;

		if (!empty($img))
		{
			echo '<div class="widget_simpleimagelink" style="text-align:' . $alignment . '">';
			if (!empty($url)) echo '<a href="' . $url . '" ' . $aAttribs . '>';
			echo '<img src="' . $img .'" ' . $imgAttribs . ' />';
			if (!empty($text))
			{
				echo $break ? '<br />' : '&nbsp;';
				echo $text;
			}
			if (!empty($url)) echo '</a>';
			echo '</div>';
		}

		echo '</div>';

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) 
	{
		$instance = $old_instance;
		
		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['version'] = strip_tags( $new_instance['version'] );
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['showTitle'] = strip_tags( $new_instance['showTitle'] );
		$instance['alt'] = strip_tags( $new_instance['alt'] );
		$instance['showPopup'] = strip_tags( $new_instance['showPopup'] );
		$instance['img'] = strip_tags( $new_instance['img'] );
		$instance['imgWidth'] = strip_tags( $new_instance['imgWidth'] );
		$instance['imgHeight'] = strip_tags( $new_instance['imgHeight'] );
		$instance['url'] = strip_tags( $new_instance['url'] );
		$instance['urlTarget'] = strip_tags( $new_instance['urlTarget'] );
		$instance['noFollow'] = strip_tags( $new_instance['noFollow'] );
		$instance['paddingTop'] = strip_tags( $new_instance['paddingTop'] );
		$instance['paddingBottom'] = strip_tags( $new_instance['paddingBottom'] );
		$instance['alignment'] = strip_tags( $new_instance['alignment'] );
		$instance['text'] = strip_tags( $new_instance['text'] );
		$instance['break'] = strip_tags( $new_instance['break'] );
		$instance['shadowBox'] = strip_tags( $new_instance['shadowBox'] );
		$instance['role'] = strip_tags( $new_instance['role'] );
		$instance['roleMethod'] = strip_tags( $new_instance['roleMethod'] );

		return $instance;
	}

	function form( $instance ) 
	{

		/* Set up some default widget settings. */
		$defaults = array( 'version' => SIMPLEIMAGELINK_VERSION,
						   'title' => __('Simple Image Link', 'simple-image-link'), 
						   'showTitle' => false, 
						   'alt' => '',
						   'showPopup' => 0,
						   'img' => '',
						   'imgWidth' => '',
						   'imgHeight' => '',
						   'url' => '',
						   'urlTarget' => '',
						   'noFollow' => 1,
						   'paddingTop' => '',
						   'paddingBottom' => '',
						   'alignment' => 'center',
						   'text' => '',
						   'break' => 0,
						   'shadowBox' => 0,
						   'role' => '',
						   'roleMethod' => 0);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$version = strip_tags( $instance['version'] );
		$title = strip_tags( $instance['title'] );
		$showTitle = strip_tags( $instance['showTitle'] );
		$alt = strip_tags( $instance['alt'] );
		$showPopup = strip_tags( $instance['showPopup'] );
		$img = strip_tags( $instance['img'] );
		$imgWidth = strip_tags( $instance['imgWidth'] );
		$imgHeight = strip_tags( $instance['imgHeight'] );
		$url = strip_tags( $instance['url'] );
		$urlTarget = strip_tags( $instance['urlTarget'] );
		$noFollow = strip_tags( $instance['noFollow'] );
		$paddingTop = strip_tags( $instance['paddingTop'] );
		$paddingBottom = strip_tags( $instance['paddingBottom'] );
		$alignment = strip_tags( $instance['alignment'] );
		$text = strip_tags( $instance['text'] );
		$break = strip_tags( $instance['break'] );
		$shadowBox = strip_tags( $instance['shadowBox'] );
		$role = strip_tags( $instance['role'] );
		$roleMethod = strip_tags( $instance['roleMethod'] );

		if (empty($alignment)) 
			$alignment = 'center';
		
		if (false && ((null == $role) || (null == $roleMethod)))
		{
		   $display = strip_tags( $instance['display'] );
		   $role = ($display == 0) ? 'do-not-display' : '';
		   $roleMethod = 0;
		}
		$display = strcmp($role, 'do-not-display') != 0;
		
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" style="width:100%;" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'showTitle' ); ?>">
			<input id="<?php echo $this->get_field_id( 'showTitle' ); ?>" name="<?php echo $this->get_field_name( 'showTitle' ); ?>" type="checkbox" value="1" <?php if ($showTitle == 1) { echo " checked='yes'"; } ?>/> <?php echo(__("Display title", 'simple-image-link')); ?></label>
		</p>
		<p>
			<?php echo(__("Display image for the following users", 'simple-image-link')); ?>:<br /><select id="<?php echo $this->get_field_id( 'role' ); ?>" name="<?php echo $this->get_field_name( 'role' ); ?>">
				<?php if (!$display) { ?>
					<option value="do-not-display" selected="selected"><?php echo(__("Do not display", 'simple-image-link')); ?></option>
				<?php } ?>
				<?php if ($role == '') { ?>
					<option value="" selected="selected"><?php echo(__("Not logged in", 'simple-image-link')); ?></option>
				<?php } ?>
			  <?php wp_dropdown_roles($role); ?>
				<?php if ($role != '') { ?>
					<option value=""><?php echo(__("Not logged in", 'simple-image-link')); ?></option>
				<?php } ?>
				<?php if ($display) { ?>
					<option value="do-not-display"><?php echo(__("Do not display", 'simple-image-link')); ?></option>
				<?php } ?>
			</select>
			<select id="<?php echo $this->get_field_id( 'roleMethod' ); ?>" name="<?php echo $this->get_field_name( 'roleMethod' ); ?>">
			<option value ="0" <?php if ($roleMethod == 0) echo 'selected="selected"'; ?>><?php echo(__("At least this role", 'simple-image-link')); ?></option>
			<option value ="1" <?php if ($roleMethod != 0) echo 'selected="selected"'; ?>><?php echo(__("Only this role", 'simple-image-link')); ?></option>
			</select>
		</p>
		<p>
			<?php echo(__("Image location (required)", 'simple-image-link')); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'img' ); ?>" name="<?php echo $this->get_field_name( 'img' ); ?>" type="text" value="<?php echo $img; ?>" />
		</p>
		<p>
			<?php echo(__("Image dimensions - e.g. 123px or 50% (optional)", 'simple-image-link')); ?><br />
			<?php echo(__("Width", 'simple-image-link')); ?>: <input class="simple-image-link-units" id="<?php echo $this->get_field_id( 'imgWidth' ); ?>" name="<?php echo $this->get_field_name( 'imgWidth' ); ?>" type="text" value="<?php echo $imgWidth; ?>" />
			&nbsp;&nbsp;&nbsp;<?php echo(__("Height", 'simple-image-link')); ?>: <input class="simple-image-link-units" id="<?php echo $this->get_field_id( 'imgHeight' ); ?>" name="<?php echo $this->get_field_name( 'imgHeight' ); ?>" type="text" value="<?php echo $imgHeight; ?>" />
		</p>
		<p>
			<?php echo(__("Image alt text (optional)", 'simple-image-link')); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'alt' ); ?>" name="<?php echo $this->get_field_name( 'alt' ); ?>" type="text" value="<?php echo $alt; ?>" />
		</p>
		<p>
			<?php echo(__("Text to display after image (optional)", 'simple-image-link')); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" type="text" value="<?php echo $text; ?>" />
		</p>
		<p>
			<label for="widget-simpleimagelink-break-<?php echo $number; ?>"><input id="<?php echo $this->get_field_id( 'break' ); ?>" name="<?php echo $this->get_field_name( 'break' ); ?>" type="checkbox" value="1" <?php if ($break == 1) { echo " checked='yes'"; } ?>/> <?php echo(__("Break between image and text", 'simple-image-link')); ?></label>
		</p>
		<p>
			<?php echo(__("Alignment", 'simple-image-link')); ?>: <select class="widefat" id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>">
			<option value="left" <?php if ($alignment == 'left') echo 'selected="selected"'; ?>><?php echo(__("Left", 'simple-image-link')); ?></option>
			<option value="center" <?php if ($alignment == 'center') echo 'selected="selected"'; ?>><?php echo(__("Center", 'simple-image-link')); ?></option>
			<option value="right" <?php if ($alignment == 'right') echo 'selected="selected"'; ?>><?php echo(__("Right", 'simple-image-link')); ?></option>
			</select>
		</p>
		<p>
			<?php echo(__("Link location (optional)", 'simple-image-link')); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo $url; ?>" />
		</p>
		<p>
			<?php echo(__("Link target", 'simple-image-link')); ?>: <select class="widefat" id="<?php echo $this->get_field_id( 'urlTarget' ); ?>" name="<?php echo $this->get_field_name( 'urlTarget' ); ?>">
			<option value ="" <?php if ($urlTarget != '_blank') echo 'selected="selected"'; ?>><?php echo(__("Open link in the same window", 'simple-image-link')); ?></option>
			<option value ="_blank" <?php if ($urlTarget == '_blank') echo 'selected="selected"'; ?>><?php echo(__("Open link in a new window", 'simple-image-link')); ?></option>
			</select><br />
			<label for="<?php echo $this->get_field_id( 'noFollow' ); ?>"><input id="<?php echo $this->get_field_id( 'noFollow' ); ?>" name="<?php echo $this->get_field_name( 'noFollow' ); ?>" type="checkbox" value="1" <?php if ($noFollow == 1) { echo " checked='yes'"; } ?>/> <?php echo(__("Use nofollow on link", 'simple-image-link')); ?></label>
			&nbsp;&nbsp;&nbsp;<label for="<?php echo $this->get_field_id( 'shadowBox' ); ?>"><input id="<?php echo $this->get_field_id( 'shadowBox' ); ?>" name="<?php echo $this->get_field_name( 'shadowBox' ); ?>" type="checkbox" value="1" <?php if ($shadowBox == 1) { echo " checked='yes'"; } ?>/> <?php echo(__("Use shadowbox on link", 'simple-image-link')); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'showPopup' ); ?>"><input id="<?php echo $this->get_field_id( 'showPopup' ); ?>" name="<?php echo $this->get_field_name( 'showPopup' ); ?>" type="checkbox" value="1" <?php if ($showPopup == 1) { echo " checked='yes'"; } ?>/> <?php echo(__("Display image alt text as link popup (i.e. anchor title)", 'simple-image-link')); ?></label>
		</p>
		<p>
			<?php echo(__("Additional padding - e.g. 10px or 1.0em (optional)", 'simple-image-link')); ?><br />
			<?php echo(__("Above", 'simple-image-link')); ?>: <input class="simple-image-link-units" id="<?php echo $this->get_field_id( 'paddingTop' ); ?>" name="<?php echo $this->get_field_name( 'paddingTop' ); ?>" type="text" value="<?php echo $paddingTop; ?>" />
			&nbsp;&nbsp;&nbsp;<?php echo(__("Below", 'simple-image-link')); ?>: <input class="simple-image-link-units" id="<?php echo $this->get_field_id( 'paddingBottom' ); ?>" name="<?php echo $this->get_field_name( 'paddingBottom' ); ?>" type="text" value="<?php echo $paddingBottom; ?>" />
		</p>

	<?php
	}
}

if (!function_exists(widget_simpleimagelink_header))
{
	function widget_simpleimagelink_header($args)
	{	
		// Pre-2.6 compatibility
		if (!defined('WP_CONTENT_URL'))
		  define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
		if (!defined('WP_CONTENT_DIR'))
		  define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
		if (!defined('WP_PLUGIN_URL'))
		  define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
		if (!defined('WP_PLUGIN_DIR'))
		  define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
		$simpleimagelinkhead = "<!-- ImageLink widget --><link rel='stylesheet' href='" . WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) . "/simple-image-link.css' type='text/css' media='screen' />";
		print($simpleimagelinkhead);
	}
}

function widget_simpleimagelink_load_widgets() 
{
	register_widget( 'SimpleImageLink_Widget' );
}

add_action( 'widgets_init', 'widget_simpleimagelink_load_widgets' );

add_action("wp_head", "widget_simpleimagelink_header");
add_action("admin_head", "widget_simpleimagelink_header");

if (function_exists('load_plugin_textdomain')) 
{
	load_plugin_textdomain('simple-image-link', false, dirname(plugin_basename(__FILE__)));
}

?>
