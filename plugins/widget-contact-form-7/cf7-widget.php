<?php
/*
Plugin Name: Widget for Contact Form 7
Description: Makes Contact Form 7 easier to use by adding a widget so no shortcodes are needed
Version: 1.0
Author: DigitalCourt
Author URI: http://DigitalSafari.co
Plugin URI: http://wordpress.org/plugins/widget-contact-form-7
License: GPL
*/

class Widget_CF7 extends WP_Widget {


	function Widget_CF7() {
		$widget_ops = array( 'description' => __( "Widget for Contact Form 7") );
		$this->WP_Widget('custom_cf7', __('Contact Form 7'), $widget_ops);
	}

	function widget( $args, $instance ) {
        extract($args);
		$title = $instance['title'];
        $widget_id = 'widget-' . $this->id_base . '-' . $this->number;
        
     
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
               
        ?>
    	<div class="clearfix">
    	    <?php 
    	        $widget_text = empty($instance['form']) ? '' : stripslashes($instance['form']);
    	        echo apply_filters('widget_text','[contact-form-7 id="' . $widget_text . '"]');
    	    ?>
    	</div>
        <?php
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) { 
        //Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => false ));
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
		
	<p><label for="<?php echo $this->get_field_id('form'); ?>"><?php _e('Form:') ?></label>
<?php
		$cf7posts = new WP_Query( array( 'post_type' => 'wpcf7_contact_form' ));

		if ( $cf7posts->have_posts() ) {	
			?>
			<select class="widefat" name="<?php echo $this->get_field_name('form'); ?>" id="<?php echo $this->get_field_id('form'); ?>">
			<?php
			while( $cf7posts->have_posts() ) : $cf7posts->the_post();
				?><option value="<?php the_id(); ?>"<?php selected(get_the_id(), $instance['form']); ?>><?php the_title(); ?></option>

				<?php
			endwhile;
		}
		else //no posts disable form
		{	?>
			<select class="widefat" disabled>
			<option disabled="disabled">No Forms Found</option> <?php
		}
		?>		
		</select></p> 
		<?php
	}
}

function widget_cf7_reg(){
    register_widget('Widget_CF7');
}

add_action("widgets_init", 'widget_cf7_reg');
?>