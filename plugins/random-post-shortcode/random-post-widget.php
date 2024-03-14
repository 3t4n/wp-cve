<?php
/*
Keeping a separate file for the widgets for orgaization purposes
*/

class random_Post_Widget extends WP_Widget {
    /*
	function random_Post_Widget() {
		$widget_ops = array( 'classname' => 'random-post-widget', 'description' => 'Displays a random post excerpt on the sidebar' );
		$this->WP_Widget( 'post_random', 'Random Post Widget', $widget_ops );
	}*/
    
    function __construct() {
		parent::__construct(
			'random-post-widget', // Base ID
			'Random Post Widget', // Name
			array( 'description' => 'Displays a random post excerpt on the sidebar' ) // Args
		);
	}


	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		echo $before_widget;
		$title		= empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
		//$count		= empty($instance['count']) ? 1 : $instance['count'];
		$seemore	= empty($instance['seemore']) ? '...' : $instance['seemore'];

		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
			$args = array(
				'post_type'		=> 'post',
				'numberposts'	=> 1,
				'orderby'		=> 'rand',
				);
			$posts = get_posts( $args );

			foreach( $posts as $post ) :
				$text = wpautop( $post->post_content );
				echo '<h5 class="random-post-title">'.$post->post_title.'</h5>';
				echo wp_trim_words( $text, 15, null );
				echo '<p><a href="'.get_permalink($post->ID).'">'.$seemore.'</a></p>';
        	endforeach;
		wp_reset_query();
		echo $after_widget;
		?>

        <?php }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
    	$instance = $old_instance;
    	$instance['title']		= strip_tags($new_instance['title']);
    	$instance['seemore']	= strip_tags($new_instance['seemore']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array(
            'title'		=> 'Random Post',
            'seemore'	=> '...',
            ));
        $title		= strip_tags($instance['title']);
        $seemore	= strip_tags($instance['seemore']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('seemore'); ?>">"See More" text:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('seemore'); ?>" name="<?php echo $this->get_field_name('seemore'); ?>" type="text" value="<?php echo esc_attr($seemore); ?>" />
        </p>
	<?php }


} // class

add_action( 'widgets_init', 'random_post_register_widgets' );
 
function random_post_register_widgets() {
    register_widget( 'random_Post_Widget' );
}
