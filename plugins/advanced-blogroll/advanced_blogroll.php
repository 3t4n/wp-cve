<?php
/*
Plugin Name: Advanced Blogroll
Plugin URI: http://www.yakupgovler.com/?p=592
Description: Displays your bookmarks as you want.
Author: Yakup GÖVLER
Version: 1.4
Author URI: http://www.yakupgovler.com/
*/


function yg_adv_blogroll( $args = '' ) {
	$defaults = array(
		'category' => '', 'showform' => 0, 'width' => 30, 'height' => 30,
		'num' => -1, 'nofollow' => false, 'orderby'=>'name', 'order' => 'ASC'
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract($args, EXTR_SKIP);
	$category = ((int) $category == 0) ? '' : (int) $category;
	$num = ($num == 0) ? $num = -1 : (int) $num;
	$orderby = htmlspecialchars($orderby);
	$order = htmlspecialchars($order);
	$r = array(
			'orderby' => $orderby, 'order' => $order,
			'limit' => $num, 'category' =>  $category,
			'category_name' => '', 'hide_invisible' => 1,
			'show_updated' => 0, 'include' => '',
			'exclude' => '', 'search' => ''
		);

        $bookmarks = get_bookmarks($r);
	 
		$output = ''; // Blank string to start with.
		$output .= ($showform != 1) ? '<ul class="ab_bookmarks">' : '<div class="ab_images">';
		foreach ( (array) $bookmarks as $bookmark ) {
			$the_link = '#';
			if ( !empty($bookmark->link_url) )
				$the_link = clean_url($bookmark->link_url);

			$rel = $bookmark->link_rel;
			if ($nofollow) $rel = 'nofollow';
			if ( '' != $rel )
				$rel = ' rel="' . $rel . '"';

			$name = attribute_escape(sanitize_bookmark_field('link_name', $bookmark->link_name, $bookmark->link_id, 'display'));
			$description = attribute_escape(sanitize_bookmark_field('link_description', $bookmark->link_description, $bookmark->link_id, 'display'));			
	 		$title_v = $description;

			if ( '' != $title_v ) {
			  $title = ' title="' . $title_v . '"';
			  $alt = ' alt="' . $title_v . '"';
			} else {
 			  $title = ' title="' . $name . '"';
			  $alt = ' alt="' . $name . '"';
			}
			 
			

			$target = $bookmark->link_target;
			if ( '' != $target )
				$target = ' target="' . $target . '"';

			if ($showform == 0) {
			  	$output .= '<li><a href="' . $the_link . '"' . $rel . $title . $target. '>'.$name.'</a></li>';    			 
			}
			
			if ( ($bookmark->link_image != null) && ($showform == 1)) {
			  	$output .= '<a href="' . $the_link . '"' . $rel . $title . $target. '>';    
				
				if ( strpos($bookmark->link_image, 'http') !== false )
					$output .= "<img src=\"$bookmark->link_image\" height=\"$height\" width=\"$width\" $alt $title /></a>\n";
				else // If it's a relative path
					$output .= "<img height=\"$height\" width=\"$width\" src=\"" . get_option('siteurl') . "$bookmark->link_image\" $alt $title /></a>\n";
			} 
			
			if ($showform == 2) {
				if (strlen($bookmark->link_image)>2) {
				  $output .= "<li>";
			      $output .= '<span class="linkimg">';				
				  $image = "<img src=\"$bookmark->link_image\" height=\"$height\" width=\"$width\" $alt $title />";
				  $output .= '<a href="' . $the_link . '"' . $rel . $title . $target. '>'.$image.$name.'</a>';
				  $output .= '</span>';
				  $output .= "</li>\n";
				}
			}
			

		
		} // end while
		$output .= ($showform != 1) ? '</ul>' : '</div>';		
	echo $output;
}

function yg_adv_blogroll_widget( $args, $widget_args = 1 ) {
	extract( $args, EXTR_SKIP );
	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );
	
	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('yg_adv_blogroll');
	if ( !isset($options[$number]) )
		return;

	$title = htmlspecialchars($options[$number]['title']);
	$category = (int) $options[$number]['cat'];
	$showform = (int) $options[$number]['showform'];
	$orderby = htmlspecialchars($options[$number]['orderby']);
	$order = htmlspecialchars($options[$number]['order']);
	$width = (int) $options[$number]['width'];
	$height = (int) $options[$number]['height'];
	$num = (int) $options[$number]['num'];
	$nofollow = (bool) $options[$number]['nofollow'];
	$parameters = array(
		  'category' => $category,
		  'showform' => $showform,
		  'orderby' => $orderby,
		  'width' => $width,
		  'height' => $height,
		  'num' => $num,
		  'nofollow' => $nofollow,
		  'orderby' => $orderby,
		  'order' => $order
		);		

	
	echo $before_widget.$before_title.$title.$after_title;
		yg_adv_blogroll( $parameters );
	echo $after_widget;
}

// Displays form for a particular instance of the widget.  Also updates the data after a POST submit
// $widget_args: number
//    number: which of the several widgets of this type do we mean
function yg_adv_blogroll_control( $widget_args = 1 ) {
	global $wp_registered_widgets;
	static $updated = false; // Whether or not we have already updated the data after a POST submit

	if ( is_numeric($widget_args) )
		$widget_args = array( 'number' => $widget_args );
	$widget_args = wp_parse_args( $widget_args, array( 'number' => -1 ) );
	extract( $widget_args, EXTR_SKIP );

	// Data should be stored as array:  array( number => data for that instance of the widget, ... )
	$options = get_option('yg_adv_blogroll');
	if ( !is_array($options) )
		$options = array();

	// We need to update the data
	if ( !$updated && !empty($_POST['sidebar']) ) {
		// Tells us what sidebar to put the data in
		$sidebar = (string) $_POST['sidebar'];

		$sidebars_widgets = wp_get_sidebars_widgets();
		if ( isset($sidebars_widgets[$sidebar]) )
			$this_sidebar =& $sidebars_widgets[$sidebar];
		else
			$this_sidebar = array();

		foreach ( $this_sidebar as $_widget_id ) {
			// Remove all widgets of this type from the sidebar.  We'll add the new data in a second.  This makes sure we don't get any duplicate data
			// since widget ids aren't necessarily persistent across multiple updates
			if ( 'yg_adv_blogroll_widget' == $wp_registered_widgets[$_widget_id]['callback'] && isset($wp_registered_widgets[$_widget_id]['params'][0]['number']) ) {
				$widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
				if ( !in_array( "adv-blogroll-$widget_number", $_POST['widget-id'] ) ) // the widget has been removed. "many-$widget_number" is "{id_base}-{widget_number}
					unset($options[$widget_number]);
			}
		}
		
		foreach ( (array) $_POST['adv-blogroll'] as $widget_number => $adv_blogroll_instance ) {
			// compile data from $widget_many_instance
			$title = wp_specialchars( $adv_blogroll_instance['title'] );
			$orderby = wp_specialchars( $adv_blogroll_instance['orderby'] );
			$order = wp_specialchars( $adv_blogroll_instance['order'] );
			$options[$widget_number] = array( 'title' => $title, 'cat' => (int) $adv_blogroll_instance['cat'], 'showform' => (int) $adv_blogroll_instance['showform'], 'orderby' => $orderby, 'order' => $order, 'width' => (int) $adv_blogroll_instance['width'], 'height' => (int) $adv_blogroll_instance['height'], 'num' => (int) $adv_blogroll_instance['num'], 'nofollow' => (bool) $adv_blogroll_instance['nofollow']  );
		}
		
		update_option('yg_adv_blogroll', $options);
		
		$updated = true; // So that we don't go through this more than once
	}

	// Here we echo out the form
	if ( -1 == $number ) { // We echo out a template for a form which can be converted to a specific form later via JS
		$title = __('Blogroll', 'advanced_blogroll');
		$cat = 0;
		$showform = 0;
		$orderby = 'name';
		$order = 'ASC';
		$width = 30;
		$height = 30;
		$num = 0;
		$nofollow = 0;
		$number = '%i%';
	} else {
		$title = attribute_escape($options[$number]['title']);
		$cat = (int) $options[$number]['cat'];
		$showform = ((int) $options[$number]['showform'] > 2 ) ? 0 : (int) $options[$number]['showform'];
		$orderby = attribute_escape($options[$number]['orderby']);
		$order = attribute_escape($options[$number]['order']);
		$width = ((int) $options[$number]['width'] < 16 ) ? 16 : (int) $options[$number]['width'];
		$height = ((int) $options[$number]['height'] < 16 ) ? 16 : (int) $options[$number]['height'];
		$num = ((int) $options[$number]['num'] < 0 ) ? 0 : (int) $options[$number]['num'];
		$nofollow = (int) $options[$number]['nofollow'];
	}
	// The form has inputs with names like widget-many[$number][something] so that all data for that instance of
	// the widget are stored in one $_POST variable: $_POST['widget-many'][$number]
?>
		<p>
		 <label for="adv-blogroll-title-<?php echo $number; ?>">
		  <?php _e( 'Title:' );?>
		  
		  <input class="widefat" id="adv-blogroll-title-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][title]" type="text" value="<?php echo $title; ?>" />
		 </label>
		</p>
		
		<p>
		 <label for="adv-blogroll-cat-<?php echo $number; ?>">
				<?php _e( 'Category:' ); ?><br />
		  <select id="adv-blogroll-cat-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][cat]">
				<?php 
				  dropdown_links_cats($cat);
				?>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-orderby-<?php echo $number; ?>"><?php _e( 'Order By:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-orderby-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][orderby]">
			<option value="name" <?php echo ( $orderby == 'name' ) ? 'selected' : ''?>><?php _e('Name', 'advanced_blogroll'); ?></option>	
			<option value="id" <?php echo ( $orderby == 'id' ) ? 'selected' : ''?>><?php _e('ID', 'advanced_blogroll'); ?></option>
			<option value="url" <?php echo ( $orderby == 'url' ) ? 'selected' : ''?>><?php _e('URI', 'advanced_blogroll'); ?></option>
			<option value="rating" <?php echo ( $orderby == 'rating' ) ? 'selected' : ''?>><?php _e('Rating', 'advanced_blogroll'); ?></option>			
			<option value="rand" <?php echo ( $orderby == 'rand' ) ? 'selected' : ''?>><?php _e('Random', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-order-<?php echo $number; ?>"><?php _e( 'Order:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-order-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][order]">
			<option value="ASC" <?php echo ( $order == 'ASC' ) ? 'selected' : ''?>><?php _e('Ascending', 'advanced_blogroll'); ?></option>	
			<option value="DESC" <?php echo ( $order == 'DESC' ) ? 'selected' : ''?>><?php _e('Descending', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>		
		<p>
		 <label for="adv-blogroll-showform-<?php echo $number; ?>"><?php _e( 'Display Form:', 'advanced_blogroll' ); ?><br />
		  <select id="adv-blogroll-showform-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][showform]">
			<option value="0" <?php echo ( $showform == 0 ) ? 'selected' : ''?>><?php _e('Only Names', 'advanced_blogroll'); ?></option>	
			<option value="1" <?php echo ( $showform == 1 ) ? 'selected' : ''?>><?php _e('Only Images', 'advanced_blogroll'); ?></option>
			<option value="2" <?php echo ( $showform == 2 ) ? 'selected' : ''?>><?php _e('Images with Names', 'advanced_blogroll'); ?></option>
		  </select>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-width-<?php echo $number; ?>"><?php _e('Image Width: ', 'advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-width-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][width]" type="text" value="<?php echo $width ?>" />px
		  <br /><small><?php _e('(at least 16px)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
		<p>
		 <label for="adv-blogroll-height-<?php echo $number; ?>"><?php _e('Image Height: ','advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-height-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][height]" type="text" value="<?php echo $height; ?>" />px
		  <br /><small><?php _e('(at least 16px)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
	
		<p>
		 <label for="adv-blogroll-numbookmarks-<?php echo $number; ?>"><?php _e('Number of Bookmarks to Show: ','advanced_blogroll'); ?>
		  <input style="width: 15%; text-align:center; padding: 3px;" id="adv-blogroll-numbookmarks-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][num]" type="text" value="<?php echo $num; ?>" />
		  <br /><small><?php _e('( 0 - All Bookmarks)', 'advanced_blogroll'); ?></small>
		 </label>
		</p>
		
		<p>
		 <label for="adv-blogroll-nofollow-<?php echo $number; ?>">
		  <input type="checkbox" class="checkbox" id="adv-blogroll-nofollow-<?php echo $number; ?>" name="adv-blogroll[<?php echo $number; ?>][nofollow]"<?php checked( (bool) $nofollow, true ); ?> />
		  <?php _e( 'Add rel = "nofollow" to bookmarks', 'advanced_blogroll'); ?>
		 </label>
		</p>
		
		<!--<input type="hidden" name="cat-posts[<?php echo $number; ?>][submit]" value="1" />-->
<?php

}
function dropdown_links_cats($cat) {
 //$selected = (int) $selected;
// $number = $number;
 
	$categories = get_terms('link_category', 'orderby=name&hide_empty=0');

	if ( empty($categories) )
		return;
    echo "<option value='0'";
	echo ($cat == 0) ? ' selected' : '';
	echo ">".__("All Categories")."</option>";
	foreach ( $categories as $category ) {
		$cat_id = $category->term_id;
		$name = wp_specialchars( apply_filters('the_category', $category->name));
		//echo "<option value='$cat_id'" . $cat_id==$selected ? " selected = 'selected'" : '' .">$name</option>";
		if ($cat_id != $cat)
		 echo "<option value='".$cat_id."'>".$name."</option>";
		else
		 echo "<option value='".$cat_id."' selected>".$name."</option>";		 
	}

}
// Registers each instance of our widget on startup
function yg_adv_blogroll_register() {
	if ( !$options = get_option('yg_adv_blogroll') )
		$options = array();

	$widget_ops = array('classname' => 'adv-blogroll', 'description' => __('Widget that shows your bookmarks as you want.'));
	$control_ops = array('id_base' => 'adv-blogroll');
	$name = __('Advanced Blogroll', 'advanced_blogroll');

	$registered = false;
	foreach ( array_keys($options) as $o ) {
		// Old widgets can have null values for some reason
		if ( !isset($options[$o]['cat']) ) // we used 'something' above in our exampple.  Replace with with whatever your real data are.
			continue;

		// $id should look like {$id_base}-{$o}
		$id = "adv-blogroll-$o"; // Never never never translate an id
		$registered = true;
		wp_register_sidebar_widget( $id, $name, 'yg_adv_blogroll_widget', $widget_ops, array( 'number' => $o ) );
		wp_register_widget_control( $id, $name, 'yg_adv_blogroll_control', $control_ops, array( 'number' => $o ) );
	}

	// If there are none, we register the widget's existance with a generic template
	if ( !$registered ) {
		wp_register_sidebar_widget( 'adv-blogroll-1', $name, 'yg_adv_blogroll_widget', $widget_ops, array( 'number' => -1 ) );
		wp_register_widget_control( 'adv-blogroll-1', $name, 'yg_adv_blogroll_control', $control_ops, array( 'number' => -1 ) );
	}
}

add_action('plugins_loaded', 'yg_adv_blogroll_loadlang');
function yg_adv_blogroll_loadlang() {
	load_plugin_textdomain('advanced_blogroll', 'wp-content/plugins/advanced-blogroll');
}

// This is important
add_action( 'widgets_init', 'yg_adv_blogroll_register' );

?>