<?php

class NDUTodayDateWidget extends WP_Widget{
    // Main constructor
    public function __construct() {
        parent::__construct(
            'ndu_today_date_widget',
            __( 'Today Date in Nepali', 'text_domain' ),
            array(
                'customize_selective_refresh' => true,
            )
        );
    }

    // The widget form (for the backend )
    public function form( $instance ) {
        // Set widget defaults
        $defaults = array(
            'title'    => ''
        );

        // Parse current settings with defaults
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>
        <?php // Widget Title ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Widget Title', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php

    }

    // Update widget settings
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    // Display the widget
    public function widget( $args, $instance ) {
        extract( $args );

        // Check the widget options
        $title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';

        // WordPress core before_widget hook (always include )
        echo $before_widget;

        // Display widget title if defined
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        echo ndu_today_date();


        // WordPress core after_widget hook (always include )
        echo $after_widget;

    }
}

function my_register_ndu_today_date_widget() {
    register_widget( 'NDUTodayDateWidget' );
}
add_action( 'widgets_init', 'my_register_ndu_today_date_widget' );