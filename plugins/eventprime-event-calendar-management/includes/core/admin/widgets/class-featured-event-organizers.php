<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('EventM_Featured_Organizer')){

    class EventM_Featured_Organizer extends WP_Widget {

        function __construct() {
            parent::__construct('eventm_featured_organizer', "EventPrime Featured Organizers" , array( 'description' => 'Show list of featured organizers.' )
            );
        }

        public function widget( $args, $instance ) {
            wp_enqueue_style(
            'ep-widgets-style',
                EP_BASE_URL . '/includes/assets/css/ep-widgets-style.css',
                false, EVENTPRIME_VERSION
            );
            $organizers_text = ep_global_settings_button_title('Organizers');
            $featured_event_organizers =  sprintf( __( 'Featured Event %s', 'eventprime-event-calendar-management' ), $organizers_text );
            $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $featured_event_organizers;
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }

            $html = '<div class="widget widget_featured_orgainzers ep-widgets"><div class="widget-content">';
                $html .= '<h2 class="widget-title subheading heading-size-3">'.esc_attr( $title ).'</h2>';
                $event_organizers_controller = new EventM_Organizer_Controller_List();
                $organizers = $event_organizers_controller->get_featured_event_organizers( $number );
          
                if( ! empty( $organizers->terms ) ){
                    $i = 0;
                    foreach ( $organizers->terms as $organizer ) {
                        $html .= '<div class="ep-featured-organizers ep-mw-wrap ep-widget-block-wrap ep-d-flex ep-align-items-center ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1 ep-bg-white">';
                            $thumbnail_id = ( isset( $organizer->em_image_id ) && ! empty( $organizer->em_image_id ) ) ? $organizer->em_image_id : 0; 
                            $html .= '<div class="ep-fimage ep-di-flex">';
                            if ( ! empty( $thumbnail_id ) ):
                                $html .= '<a href="'.esc_url( $organizer->organizer_url ).'"><img src="'.wp_get_attachment_image_src( $thumbnail_id, 'large' )[0].'" alt="'.__( 'Event Organizer Image', 'eventprime-event-calendar-management' ).'"></a>';
                            else:
                                $html .= '<a href="'.esc_url( $organizer->organizer_url ).'"><img src="'.EP_BASE_URL .'includes/assets/css/images/dummy_image.png" alt="'.__('Dummy Image','eventprime-event-calendar-management').'" ></a>';
                            endif;
                            $html .= '</div>';
                            $html .= '<div class="ep-fdata"><div class="ep-fname"><a href="'.esc_url( $organizer->organizer_url ).'">'.esc_attr( $organizer->name ).'</a></div>';              
                            $html .= '</div>';
                        $html .= '</div>';
                    }
                }else{
                    $html .= '<div class="ep-widgets-empty">'.esc_html__( 'No data found.', 'eventprime-event-calendar-management' ).'</div>';
                }
            $html .= '</div></div>';
            echo $html;
        }

        public function form($instance) {
            $title = !empty( $instance['title'] ) ? $instance['title'] : '';
            $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'eventprime-event-calendar-management' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of organizers to show:', 'eventprime-event-calendar-management' ); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
            </p>
            <?php 
        }

        // Updating widget replacing old instances with new
        public function update($new_instance, $old_instance) {

            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['number'] = (int) $new_instance['number'];
            return $instance;
        }

    }

}

// Register and load the widget
function em_load_featured_organizer() {
    register_widget('eventm_featured_organizer');
}

add_action('widgets_init', 'em_load_featured_organizer');
