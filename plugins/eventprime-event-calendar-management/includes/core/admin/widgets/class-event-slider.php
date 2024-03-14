<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class EventM_slider extends WP_Widget {

    function __construct() {
        parent::__construct('eventm_slider', __("EventPrime - Event Slider", 'eventprime-event-calendar-management'), array('description' => __("Event Slider to show all the events", 'eventprime-event-calendar-management'))
        );
    }

    public function widget($args, $instance) {
        wp_enqueue_style( 'ep-responsive-slides-css' );
        wp_enqueue_script( 'ep-responsive-slides-js' );

        wp_enqueue_script(
        'ep-widgets-scripts',
            EP_BASE_URL . '/includes/assets/js/ep-widgets-public.js',
            false, EVENTPRIME_VERSION
        );

        wp_enqueue_style(
        'ep-widgets-style',
            EP_BASE_URL . '/includes/assets/css/ep-widgets-style.css',
            false, EVENTPRIME_VERSION
        );

        $event_controller = new EventM_Event_Controller_List();
        $query = array(
            'meta_query' => array( 
                'relation' => 'AND',
                array(
                    array(
                        'key'     => 'em_start_date',
                        'value'   =>  current_time( 'timestamp' ),
                        'compare' => '>',
                        'type'=>'NUMERIC'
                    )
                )
            )
        );
        $events = $event_controller->get_events_post_data( $query ); 
        if( $events->posts ):?>
        <div class="emagic">
             <div id="ep_widget_container" class="ep-event-slide-container ep-position-relative">
                <ul class="ep_event_slides ep-event-slider-<?php echo esc_attr( $this->number ); ?> ep-m-0 ep-p-0">
                    <?php if( isset( $events->posts ) ): foreach ( $events->posts as $event ): ?>
                    <li class="ep-m-0 ep-p-0 ep-widget-event-slide">
                            <div class="ep-widget-slider-meta">
                                <?php
                                $event_date = ep_timestamp_to_date( $event->em_start_date );
                                ?>
                                <div class="ep-widget-slider-title ep-text-truncate ep-fw-bold"><?php echo esc_attr( $event->name ); ?></div>
                                <div class="ep-widget-slider-date"><?php echo esc_attr( $event_date ); ?></div>
                            </div>
                                <a target="_blank" href="<?php echo esc_url( $event->event_url ); ?>"><img src="<?php echo esc_url( $event->image_url ); ?>"> </a>
                        </li>
                    <?php endforeach;endif;?>
                </ul>  
                <div class="ep-event-widget-slider-nav-<?php echo esc_attr( $this->number ); ?> ep-event-widget-slider-nav" ></div>
            </div>
         </div>
        <?php endif;?>

        <script>
            window.onload = function() { 
                jQuery('.ep-event-slider-<?php echo esc_attr( $this->number ); ?>').responsiveSlides({
                    auto: true, 
                    speed: 500, 
                    timeout: 4000, 
                    pager: false, 
                    nav: true, 
                    random: false, 
                    pause: true, 
                    prevText: "<span class='material-icons-outlined'> arrow_back_ios </span>", 
                    nextText: "<span class='material-icons-outlined'> arrow_forward_ios</span>",
                    maxwidth: "", 
                    pauseControls: true, 
                    navContainer: ".ep-event-widget-slider-nav-<?php echo esc_attr( $this->number ); ?>", 
                    manualControls: "",
                    namespace: "ep-widget-rslides"
                });
            }
        </script><?php
    }

}

// Register and load the widget
function em_load_slider_widget() {
    register_widget('eventm_slider');
}

add_action('widgets_init', 'em_load_slider_widget');