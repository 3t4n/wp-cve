<?php 
class IQTWidget extends WP_Widget {
 /**
  * Declares the IQTWidget class.
  *
  */
	function IQTWidget(){
		$widget_ops = array('classname' => 'iq-testimonials', 'description' => __( "IQ Testimonials Widget!") );
		$control_ops = array('width' => 200, 'height' => 300);
		$this->WP_Widget('iqtwidget', __('IQ Testimonials'), $widget_ops, $control_ops);
	}

  /**
    * Displays the Widget
    *
    */
	function widget($args, $instance){
		
		// Getting theme arguments
		extract($args);

		// Applying before widget code, set by theme.
		echo $before_widget;
		
		$title = apply_filters('widget_title', empty($instance['title']) ? 'IQ Testimonials' : $instance['title']);
		
		# The title
		if ($title) {
			echo $before_title . $title . $after_title;
		}
		
		// Run the IQ Testimonials Function
echo iq_testimonials('', false);
		
		// Applying after widget code, set by theme.
		echo $after_widget;
	}

  /**
    * Saves the widgets settings.
    *
    */
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));		
		return $instance;
	}

  /**
    * Creates the edit form for the widget.
    *
    */
	function form($instance){
		//Defaults
		$instance = wp_parse_args((array) $instance, array('title'=>''));
		
		$title = htmlspecialchars($instance['title']);
		
		# Output the options
		echo '<p style="text-align:right;"><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
	}

} // END class

?>