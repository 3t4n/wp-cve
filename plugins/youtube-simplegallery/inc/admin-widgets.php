<?php

// ADD WIDGET SUPPORT
add_action('init', 'youtube_simplegallery_multi_register');
function youtube_simplegallery_multi_register() {
	
	$prefix = 'ytsg-multi'; // $id prefix
	$name = __('YouTube SimpleGallery');
	$widget_ops = array('classname' => 'youtube_simplegallery_multi', 'description' => __('This is an example of widget,which you can add many times'));
	$control_ops = array('width' => 400, 'height' => 350, 'id_base' => $prefix);
	
	$options = get_option('youtube_simplegallery_multi');
	if(isset($options[0])) unset($options[0]);
	
	if(!empty($options)){
		foreach(array_keys($options) as $widget_number){
			wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'youtube_simplegallery_multi', $widget_ops, array( 'number' => $widget_number ));
			wp_register_widget_control($prefix.'-'.$widget_number, $name, 'youtube_simplegallery_multi_control', $control_ops, array( 'number' => $widget_number ));
		}
	} else{
		$options = array();
		$widget_number = 1;
		wp_register_sidebar_widget($prefix.'-'.$widget_number, $name, 'youtube_simplegallery_multi', $widget_ops, array( 'number' => $widget_number ));
		wp_register_widget_control($prefix.'-'.$widget_number, $name, 'youtube_simplegallery_multi_control', $control_ops, array( 'number' => $widget_number ));
	}
}

// OUTPUT WIDGET IN FRONT
function youtube_simplegallery_multi($args, $vars = array()) {
    extract($args);
    $widget_number = (int)str_replace('ytsg-multi-', '', @$widget_id);
    $options = get_option('youtube_simplegallery_multi');
    if(!empty($options[$widget_number])){
    	$vars = $options[$widget_number];
    }
    // widget open tags
		echo $before_widget;
		
		// print title from admin 
		if(!empty($vars['title'])){
			echo $before_title . $vars['title'] . $after_title;
		} 
		
		// print content and widget end tags
	$links = $options[$widget_number]['links'];
	$cols = $options[$widget_number]['cols'];
	$twidth = $options[$widget_number]['twidth'];
	require_once('output-widget.php');
	
	echo widget_youtubegallery($links, array('cols'=> $cols, 'thumbwidth' => $twidth));

    echo $after_widget;
}

// OUTPUT WIDGET IN ADMIN
function youtube_simplegallery_multi_control($args) {

	$prefix = 'ytsg-multi'; // $id prefix
	
	$options = get_option('youtube_simplegallery_multi');
	if(empty($options)) $options = array();
	if(isset($options[0])) unset($options[0]);
		
	// update options array
	if(!empty($_POST[$prefix]) && is_array($_POST)){
		foreach($_POST[$prefix] as $widget_number => $values){
			if(empty($values) && isset($options[$widget_number])) // user clicked cancel
				continue;
			
			if(!isset($options[$widget_number]) && $args['number'] == -1){
				$args['number'] = $widget_number;
				$options['last_number'] = $widget_number;
			}
			$options[$widget_number] = $values;
		}
		
		// update number
		if($args['number'] == -1 && !empty($options['last_number'])){
			$args['number'] = $options['last_number'];
		}

		// clear unused options and update options in DB. return actual options array
		$options = bf_smart_multiwidget_update($prefix, $options, $_POST[$prefix], $_POST['sidebar'], 'youtube_simplegallery_multi');
	}
	
	// $number - is dynamic number for multi widget, gived by WP
	// by default $number = -1 (if no widgets activated). In this case we should use %i% for inputs
	//   to allow WP generate number automatically
	$number = ($args['number'] == -1)? '%i%' : $args['number'];

	// now we can output control
	$opts = @$options[$number];
	
	$title = stripslashes(@$opts['title']);
	$links = stripslashes(@$opts['links']);
	$cols = @$opts['cols'];
	$twidth = @$opts['twidth'];
	 
	?>
	<p>
    Title<br />
		<input type="text" class="widefat" name="<?php echo $prefix; ?>[<?php echo $number; ?>][title]" value="<?php echo $title; ?>" />
	</p>
	<p>
    Links<br />
		<textarea cols="20" rows="16" class="widefat" name="<?php echo $prefix; ?>[<?php echo $number; ?>][links]"><?php echo $links; ?></textarea>
	<br />Add YouTube-links with linebreak for each. Add (optional) titles before and separate with | (pipe)<br />
	<pre><code>Star Size Comparison|</code>http://www.youtube.com/watch?v=HEheh1BH34Q</pre>
	</p>
	<p>
    Columns<br />
		<input type="text" class="" name="<?php echo $prefix; ?>[<?php echo $number; ?>][cols]" value="<?php echo $cols; ?>" />
	</p>
	<p>
    Thumbnail width<br />
		<input type="text" class="" name="<?php echo $prefix; ?>[<?php echo $number; ?>][twidth]" value="<?php echo $twidth; ?>" />px
	</p>
	<?php
}

// helper function can be defined in another plugin
if(!function_exists('bf_smart_multiwidget_update')){
	function bf_smart_multiwidget_update($id_prefix, $options, $post, $sidebar, $option_name = ''){
		global $wp_registered_widgets;
		static $updated = false;

		// get active sidebar
		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();
		
		// search unused options
		foreach ( $this_sidebar as $_widget_id ) {
			if(preg_match('/'.$id_prefix.'-([0-9]+)/i', $_widget_id, $match)){
				$widget_number = $match[1];
				
				// $_POST['widget-id'] contain current widgets set for current sidebar
				// $this_sidebar is not updated yet, so we can determine which was deleted
				if(!in_array($match[0], $_POST['widget-id'])){
					unset($options[$widget_number]);
				}
			}
		}
		
		// update database
		if(!empty($option_name)){
			update_option($option_name, $options);
			$updated = true;
		}
		
		// return updated array
		return $options;
	}
}
