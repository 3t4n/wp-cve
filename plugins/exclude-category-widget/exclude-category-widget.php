<?php
/**
 * @package exclude-category-widget
 * @version 1.0
 */
/*
Plugin Name: Exclude Category Widget
Plugin URI: http://wordpress.org/plugins/exclude-category-widget
Description: The widget will use for showing category list. You can exclude unwanted categories from category list.
Author: Ravinder Singh
Version: 1.0
Author URI: http://ravindernegi.com
*/

class ExcludeCategory extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'Exclude Category Widget' );
	}

	function widget( $args, $instance ) {
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Categories' ) : $instance['title'], $instance, $this->id_base );
		
		echo $args['before_widget'];
		
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		echo "<ul>";
		
		$cat_args['title_li'] = '';
		$cat_args['exclude'] = trim($instance['text'],',');
		
		wp_list_categories( apply_filters( 'widget_categories_args', $cat_args ) );
		
		echo "</ul>";

		echo $args['after_widget'];
		
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text'] =  strip_tags(preg_replace("/[^0-9,.]/", "",trim($new_instance['text'])));
        return $instance;
	}

	function form( $instance ) {
		if (!is_array($instance)) $instance = array();
        $instance = array_merge(array('title'=>'', 'text'=>''), $instance);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>


            <label for="<?php echo $this->get_field_id('text'); ?>">
                Category Ids:
                <textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_html($instance['text']); ?></textarea>
            </label>
			<p>write category ids with (,) separate for excluding categories </p>
		<?php 
	}
}

function exclude_category_register_widgets() {
	register_widget( 'ExcludeCategory' );
}

add_action( 'widgets_init', 'exclude_category_register_widgets' );

?>
