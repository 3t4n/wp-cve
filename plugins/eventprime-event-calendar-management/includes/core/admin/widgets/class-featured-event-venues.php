<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('EventM_Featured_Venue')){

    class EventM_Featured_Venue extends WP_Widget {

        function __construct() {
            parent::__construct('eventm_featured_venue', "EventPrime Featured Venues" , array( 'description' => 'Show list of featured event venues.' )
            );
        }

        public function widget( $args, $instance ) {
            wp_enqueue_style(
            'ep-widgets-style',
                EP_BASE_URL . '/includes/assets/css/ep-widgets-style.css',
                false, EVENTPRIME_VERSION
            );
            $venues_text = ep_global_settings_button_title('Venues');
            $featured_event_venues =  sprintf( __( 'Featured Event %s', 'eventprime-event-calendar-management' ), $venues_text );
            $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $featured_event_venues;
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }

            $html = '<div class="widget widget_featured_venues ep-widgets"><div class="widget-content">';
                $html .= '<h2 class="widget-title subheading heading-size-3">'.esc_attr( $title ).'</h2>';
                $event_types_controller = new EventM_Venue_Controller_List();
                $venues = $event_types_controller->get_featured_event_venues( $number );
             
                if( ! empty( $venues->terms ) ){
                    $i = 0;
                    foreach ( $venues->terms as $venue ) {
                        $html .= '<div class="ep-featured-venues ep-mw-wrap ep-widget-block-wrap ep-d-flex ep-align-items-center ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1 ep-bg-white">';
                            $title = $venue->name;
                            $thumbnail_id = ( isset( $venue->em_gallery_images ) && ! empty( $venue->em_gallery_images ) ) ? $venue->em_gallery_images : array(); 
                            $html .= '<div class="ep-fimage ep-di-flex">';
                            if ( isset( $thumbnail_id[0] ) && ! empty( $thumbnail_id[0] ) ) {
                                $html .= '<a href="'.esc_url( $venue->venue_url ).'"><img src="'.wp_get_attachment_image_src( $thumbnail_id[0], 'large' )[0].'" alt="'.esc_html__( 'Event Venue Image', 'eventprime-event-calendar-management' ).'"></a>';
                            } else{
                                $html .= '<a href="'.esc_url( $venue->venue_url ).'"><img src="'.EP_BASE_URL .'includes/assets/css/images/dummy_image.png" alt="'.esc_html__( 'Dummy Image', 'eventprime-event-calendar-management' ).'" ></a>';
                            }
                            $html .= '</div>';
                            $html .= '<div class="ep-fdata"><div class="ep-fname"><a href="'.esc_url( $venue->venue_url ).'">'.esc_attr( $venue->name ).'</a></div>';              
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
            $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
            $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'eventprime-event-calendar-management' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of event venues to show:', 'eventprime-event-calendar-management' ); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo absint( $number ); ?>" size="3" />
            </p><?php 
        }

        // Updating widget replacing old instances with new
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['number'] = (int)$new_instance['number'];
            return $instance;
        }

    }

}

// Register and load the widget
function em_load_featured_venue() {
    register_widget('eventm_featured_venue');
}

add_action('widgets_init', 'em_load_featured_venue');
