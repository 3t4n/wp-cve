<?php

/**
 * Mailchimp Widget 
 */
class pmc_widget_wrapper extends WP_Widget {


    /** constructor */
    function pmc_widget_wrapper() {
        parent::WP_Widget(false, $name = __('Mailchimp Signup', 'pmc'));	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $list_id = strip_tags($instance['list_id']);
        $message = esc_attr($instance['message']);
        
		  global $post;        
        
        echo $before_widget;
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        echo pmc_mc_form('', $list_id, $message);
        echo $after_widget;
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['list_id'] = strip_tags($new_instance['list_id']);
		$instance['message'] = esc_attr($new_instance['message']);
      return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {	
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $list_id = isset($instance['list_id']) ? esc_attr($instance['list_id']) : '';
        $message = isset($instance['message']) ? esc_attr($instance['message']) : '';
        ?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Form Title:', 'pmc'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('list_id'); ?>"><?php _e('Select a List', 'pmc'); ?></label> 
				<select name="<?php echo $this->get_field_name('list_id'); ?>" id="<?php echo $this->get_field_id('list_id'); ?>" class="widefat">
				<?php
					$lists = pmc_get_lists();
					foreach ($lists as $id => $list) {
						echo '<option value="' . $id . '"' . selected($list_id, $id, false) . '>' . $list . '</option>';
					}
				?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('message'); ?>"><?php _e('Success Message:', 'pmc'); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id('message'); ?>" name="<?php echo $this->get_field_name('message'); ?>" type="text" value="<?php echo $message; ?>" />
			</p>

        <?php 
    }
}
add_action('widgets_init', create_function('', 'return register_widget("pmc_widget_wrapper");'));
?>