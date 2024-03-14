<?php
/*
Plugin Name: Contact Form 7 widget
Plugin URI: http://blog.strategy11.com/contact-form-7-widget/
Description: Use your Contact Form 7 forms in a Widget.
Author: Stephanie Wells
Author URI: http://ionixdesign.com 
Version: 1.0
*/

class WP_Widget_Custom_CF7 extends WP_Widget {

	function WP_Widget_Custom_CF7() {
		$widget_ops = array( 'description' => __( "Display any form from Contact Forms 7") );
		$this->WP_Widget('custom_cf7', __('Contact Form 7'), $widget_ops);
	}

	function widget( $args, $instance ) {
        extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Contact Us') : $instance['title']);
        $widget_id = 'widget-' . $this->id_base . '-' . $this->number;
        
        if ($instance['bg'] or $instance['label'] or $instance['submitbg'] or $instance['submit'] or $instance['round'] ){
        ?>
            <style>
                #<?php echo $widget_id ?> #cf7_form_box{background-color:<?php echo $instance['bg'] ?>; color:<?php echo $instance['label'] ?>; padding:10px;}
            	#<?php echo $widget_id ?> .waiting, #<?php echo $widget_id ?> .success{background:#fff;}
            	#<?php echo $widget_id ?> textarea{
            		margin:5px 0;
            		height:80px;
            		width:95%;
            	}
            	#<?php echo $widget_id ?> input[type="submit"]{
            		background: <?php echo $instance['submitbg'] ?>;
            		color: <?php echo $instance['submit'] ?>; 
            		-moz-border-radius: <?php echo $instance['round'] ?>;
                    -webkit-border-radius: <?php echo $instance['round'] ?>;
                    -khtml-border-radius: <?php echo $instance['round'] ?>;
                    border--radius: <?php echo $instance['round'] ?>;
            	}
            	#<?php echo $widget_id ?> input[type="text"]{width:95%;}
            	#<?php echo $widget_id ?> .wpcf7-response-output, #<?php echo $widget_id ?> .wpcf7-not-valid-tip{display:none;}
            </style>
        <?php
        }
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
        
        if ( $instance['subheading'] )	
		    echo '<p class="cf7_widget_subheading">'. $instance['subheading'] .'</p>';
        
        ?>
    	<div id="cf7_form_box">
    	    <?php 
    	        $widget_text = empty($instance['form']) ? '[contact-form 1 "Contact form 1"]' : stripslashes($instance['form']);
    	        echo apply_filters('widget_text', $widget_text);
    	    ?>
    	    <div class="clear"></div>
    	</div>
        <?php
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) { 
        //Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => false, 'subheading' => false, 'form' => '[contact-form 1 "Contact form 1"]', 'bg' => false, 'label' => false, 'submitbg' => false, 'submit' => false, 'round' => false ) );
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('subheading'); ?>"><?php _e('Subheading:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('subheading'); ?>" name="<?php echo $this->get_field_name('subheading'); ?>" value="<?php echo esc_attr( $instance['subheading'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('form'); ?>"><?php _e('Contact Form 7 Tag:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('form'); ?>" name="<?php echo $this->get_field_name('form'); ?>" value="<?php echo esc_attr( $instance['form'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('bg'); ?>"><?php _e('Background Color:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('bg'); ?>" name="<?php echo $this->get_field_name('bg'); ?>" value="<?php echo esc_attr( $instance['bg'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('label'); ?>"><?php _e('Label Color:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('label'); ?>" name="<?php echo $this->get_field_name('label'); ?>" value="<?php echo esc_attr( $instance['label'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('submitbg'); ?>"><?php _e('Submit Button Background Color:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('submitbg'); ?>" name="<?php echo $this->get_field_name('submitbg'); ?>" value="<?php echo esc_attr( $instance['submitbg'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('submit'); ?>"><?php _e('Submit Button Text Color:') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="<?php echo esc_attr( $instance['submit'] ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('round'); ?>"><?php _e('Submit Rounded Corners (#px):') ?></label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('round'); ?>" name="<?php echo $this->get_field_name('round'); ?>" value="<?php echo esc_attr( $instance['round'] ); ?>" /></p>

<?php
	}
}

function register_cf7_widget(){
    register_widget('WP_Widget_Custom_CF7');
}

add_action("widgets_init", 'register_cf7_widget');
?>