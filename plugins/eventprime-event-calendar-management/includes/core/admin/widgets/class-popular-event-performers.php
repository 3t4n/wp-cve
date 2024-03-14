<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('EventM_Popular_Performer')){

    class EventM_Popular_Performer extends WP_Widget {

        function __construct() {
            parent::__construct('eventm_popular_performer', "EventPrime Popular Performers" , array( 'description' => 'Show list of popular performers.' )
            );
        }

        public function widget($args, $instance) {
            wp_enqueue_style(
            'ep-widgets-style',
                EP_BASE_URL . '/includes/assets/css/ep-widgets-style.css',
                false, EVENTPRIME_VERSION
            );
            $performers_text = ep_global_settings_button_title('Performers');
            $popular_event_performers =  sprintf( __( 'Popular Event %s', 'eventprime-event-calendar-management' ), $performers_text );
            $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : $popular_event_performers;
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
            $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }

            $html = '<div class="widget widget_featured_performers ep-widgets"><div class="widget-content">';
                $html .= '<h2 class="widget-title subheading heading-size-3">'.$title.'</h2>';
                $event_performers_controller = new EventM_Performer_Controller_List();
                $performers = $event_performers_controller->get_popular_event_performers( $number );
                //print_r($performers);
                
                if( ! empty( $performers->posts ) ){
                    $i = 0;
                    foreach ( $performers->posts as $performer ) {
                        if( isset( $performer->events ) && ! empty( $performer->events ) ){
                            $html .= '<div class="ep-popular-performer ep-mw-wrap ep-widget-block-wrap ep-d-flex ep-align-items-center ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1 ep-bg-white" >';
                                
                                $thumbnail_id = ( isset( $performer->_thumbnail_id ) && ! empty( $performer->_thumbnail_id ) ) ? $performer->_thumbnail_id : 0; 
                                $html .= '<div class="ep-fimage ep-di-flex">';
                                if ( ! empty( $thumbnail_id ) ):
                                    $html .= '<a href="'.esc_url( $performer->performer_url ).'"><img src="'.wp_get_attachment_image_src( $thumbnail_id, 'large' )[0].'" alt="'.__( 'Event Venue Image', 'eventprime-event-calendar-management' ).'"></a>';
                                else:
                                    $html .= '<a href="'.esc_url( $performer->performer_url ).'"><img src="'.EP_BASE_URL .'includes/assets/css/images/dummy_image.png" alt="'.__( 'Dummy Image', 'eventprime-event-calendar-management').'" ></a>';
                                endif;
                                $html .= '</div>';
                                $html .= '<div class="ep-fdata"><div class="ep-fname"><a href="'.esc_url( $performer->performer_url ).'">'.esc_attr( $performer->name ).'</a></div>';              
                                if( ! empty( $performer->em_role ) ){
                                    $html .= '<div class="ep-performer-role ep-text-small ep-text-muted">'.esc_attr( $performer->em_role ).'</div>';
                                } 
                                $html .= '</div>';
                            $html .= '</div>';
                            $i++;
                            if($number <= $i) break;
                        }
                        
                    }
                }else{
                    $html .= '<div class="ep-widgets-empty">'.esc_html__( 'No data found.', 'eventprime-event-calendar-management').'</div>';
                }
            $html .= '</div></div>';
            echo $html;
        }

        public function form($instance) {
            $title = !empty( $instance['title'] ) ? $instance['title'] : '';
            $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'eventprime-event-calendar-management' ); ?></label> 
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of performers to show:', 'eventprime-event-calendar-management' ); ?></label>
                <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
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
function em_load_popular_performer() {
    register_widget('eventm_popular_performer');
}

add_action('widgets_init', 'em_load_popular_performer');
