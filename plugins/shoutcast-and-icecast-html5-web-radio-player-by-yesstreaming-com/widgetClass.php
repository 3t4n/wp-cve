<?php
// Widget
class yes_html5_player_lite_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'yes_html5_player_lite', // Base ID
			'YEStreaming Radio Player', // Name

			array( 'description' => __( 'A widget to display a YEStreaming Radio Player', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$player = '[yesstreaming_html5_player_lite id="' . $instance['yesstreaming_radio_player'] . '"]';
		
		echo $before_widget;
		if ( $title ) {
		    echo $before_title . $title . $after_title;
	    }
        echo do_shortcode($player);
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['yesstreaming_radio_player'] = strip_tags( $new_instance['yesstreaming_radio_player'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	    if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        }
        else {
            $title = __( 'Now Playing', 'wpb_widget_domain' );
        }
        $yesstreaming_radio_player = $instance['yesstreaming_radio_player'];
        $case_study_args = array(
          'post_type' => 'yeshtml5_player_lite',
          'posts_per_page' => '-1'
        );
        $case_studies = new WP_Query( $case_study_args );
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
		<p>
        <fieldset>
        <label for="<?php echo $this->get_field_id( 'yesstreaming_radio_player' ); ?>">Select a Player:</label>
        <select id="<?php echo $this->get_field_id( 'yesstreaming_radio_player' ); ?>" name="<?php echo $this->get_field_name('yesstreaming_radio_player');?> ">
            <?php if ($case_studies->have_posts()) : while ($case_studies->have_posts()) : $case_studies->the_post();?>
            <option value="<?php the_ID(); ?>" <?php selected( $yesstreaming_radio_player, get_the_ID()); ?>><?php the_title();?></option>
            <?php endwhile; endif; ?>
        </select>
        </fieldset>
		</p>
		<?php
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'yes_html5_player_lite_widget' );
});
?>