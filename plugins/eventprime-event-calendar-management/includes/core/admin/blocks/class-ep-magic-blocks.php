<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function create_block_ep_blocks_block_init() {
	register_block_type( __DIR__ . '/build/ep-login-block/', array(
		'render_callback' => 'event_prime_login_block'
	));

	register_block_type( __DIR__ . '/build/ep-register-block/', array(
		'render_callback' => 'event_prime_register_block'
	));
	register_block_type( __DIR__ . '/build/ep-square-cards-block/', array(
		'render_callback' => 'event_prime_square_card_block'
	));
	register_block_type( __DIR__ . '/build/ep-booking-details-block/', array(
		'render_callback' => 'event_prime_booking_details_block'
	));

}
add_action( 'init', 'create_block_ep_blocks_block_init' );


function event_prime_login_block($attributes, $content){
	$attributes['block_login_button'] = $content;
	$users = EventM_Factory_Service::ep_get_instance( 'EventM_User_Controller' );
    return $users->render_login_template( $attributes );
}
function event_prime_register_block($attributes, $content){
	$attributes['block_register_button'] = $content;
	$users = EventM_Factory_Service::ep_get_instance( 'EventM_User_Controller' );
	return $users->render_register_template( $attributes );
}
function event_prime_square_card_block($atts){
	$events = EventM_Factory_Service::ep_get_instance( 'EventM_Event_Controller_List' );
		$atts = array_change_key_case( (array) $atts, CASE_LOWER );
		return $events->render_template( $atts );
}
function event_prime_booking_details_block($atts){
	$bookings = EventM_Factory_Service::ep_get_instance( 'EventM_Booking_Controller_List' );
    return $bookings->render_booking_detail_template( $atts );
}

if ( class_exists('EventPrime') ) {

    class EventM_Magic_Blocks {
        private $event_prime;
	    private $version;
        public $ep_events;

        public function __construct(){
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

		public function enqueue_scripts() {
            wp_enqueue_script( 'eventprime-admin-blocks-js' );
			if (has_blocks()) {
				wp_enqueue_style('ep-admin-blocks-style');
			}
        }

        public function ep_register_rest_route() {
            register_rest_route(
                'eventprime/v1',
                '/events',
                array(
                    'method'              => 'GET',
                    'callback'            => array( $this, 'ep_load_events' ),
                    'permission_callback' => array( $this, 'ep_get_private_data_permissions_check' ),
                )
            );
        }

        public function ep_get_private_data_permissions_check() {
            return true;
        }

        public function ep_events_list() {
            $results = array();
            $event_controller = new EventM_Event_Controller_List();
            $query = array(
                'meta_query'  => array(
                    'relation' => 'AND',
                    array(
                        array(
                            'key'     => 'em_start_date_time',
                            'value'   =>  ep_get_current_timestamp(),
                            'compare' => '>',
                            'type'    => 'NUMERIC'
                        )
                    )
                )
            );
            $events = $event_controller->get_events_field_data( array( 'id', 'name' ), $query );
            $results = isset( $events ) ? (array)$events : array();
            if( $results ){
                return $results;
            } else {
                return array();
            }
        }

        public function ep_load_events() {
            if( ! empty( $this->ep_events ) ) {
                return $this->ep_events;
            } else{
                $return = array();
                $default = array(
                    'post_status' => 'publish',
                    'order'       => 'ASC',
                    'post_type'   => EM_EVENT_POST_TYPE,
                    'numberposts' => -1,
                    'offset'      => 0,
                    'meta_query'  => array(
                        'key'     => 'em_start_date_time',
                        'value'   => ep_get_current_timestamp(),
                        'compare' => '>',
                        'type'    => 'NUMERIC'
                    ),
                    'meta_key' => 'em_start_date_time',
                    'orderby'     => 'meta_value',
                );
                $posts = get_posts( $default );
                foreach ( $posts as $event ) {
                    $res = array();
                    if( empty( $event ) ) return;
                    if ( $event->ID ) {
                        $res['value'] = $event->ID;
                    }
                    if ($event->post_title ) {
                        $res['label'] = $event->post_title;
                    }
                    $return[] = $res;
                }
                $this->ep_events = $return;
                return rest_ensure_response( $return );
            }
        }

        public function eventprime_block_register() {
            global $pagenow;
			// Skip block registration if Gutenberg is not enabled/merged.
			$ep_events_data = $this->ep_events_list();
            if ( isset( $ep_events_data[0]['id'] ) ) {
                $eid = $ep_events_data[0]['id'];
            } else {
                $eid = '';
            }

            if ( ! function_exists( 'register_block_type' ) ) {
                return;
            }
            $dir = EP_BASE_URL . 'includes/assets/js/blocks';
            $index_js = 'index.js';
            if ( $pagenow !== 'widgets.php' ) {
                wp_enqueue_script( 'eventprime-admin-blocks-js' );
            } else {
                wp_register_script(
                    'eventprime-admin-blocks-js',
                    EP_BASE_URL . '/includes/assets/js/blocks/index.js',
                    array(
                        'wp-blocks',
                        'wp-edit-widgets',
                        'wp-i18n',
                        'wp-element',
                        'wp-components',
                    ),
                    EVENTPRIME_VERSION,false
                );
            }
            wp_enqueue_script( 'eventprime-admin-blocks-js' );
            // register event calendar block
            register_block_type(
                'eventprime-blocks/event-calendar',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_event_calendar_block_handler' ),
                )
            );

            // register event countdown
            register_block_type(
                'eventprime-blocks/event-countdown',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_event_countdown_block_handler' ),
                    'attributes'      => array(
                        'eid' => array(
                            'default' => $eid,
                            'type'    => 'string',
                        ),
                    ),
                )
            );

            // register event slider block
            register_block_type(
                'eventprime-blocks/event-slider',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_event_slider_block_handler' ),
                )
            );
            // register featured event organizers
            $organizers_text = ep_global_settings_button_title('Organizers');
            $featured_event_organizers =  sprintf( __( 'Featured Event %s', 'eventprime-event-calendar-management' ), $organizers_text );
            register_block_type(
                'eventprime-blocks/featured-event-organizers',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_featured_event_organizers_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $featured_event_organizers,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),
                    ),
                )
            );

            // register featured event performers
            $performers_text = ep_global_settings_button_title('Performers');
            $featured_event_performers =  sprintf( __( 'Featured Event %s', 'eventprime-event-calendar-management' ), $performers_text );
            register_block_type(
                'eventprime-blocks/featured-event-performers',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_featured_event_performers_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $featured_event_performers,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),

                    ),
                )
            );

            // register featured event types
            $event_types_text = ep_global_settings_button_title('Event Types');
            $featured_event_types =  sprintf( __( 'Featured %s', 'eventprime-event-calendar-management' ), $event_types_text );
            register_block_type(
                'eventprime-blocks/featured-event-types',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_featured_event_types_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $featured_event_types,
                            'type'    => 'string',
                        ),
                        'number'           => array(
                            'default' => 5,
                            'type'    => 'string',
                        ),
                    ),
                )
            );

            // register featured event venues
            $venues_text = ep_global_settings_button_title('Venues');
            $featured_event_venues =  sprintf( __( 'Featured Event %s', 'eventprime-event-calendar-management' ), $venues_text );
            register_block_type(
                'eventprime-blocks/featured-event-venues',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_featured_event_venues_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' =>  $featured_event_venues,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),
                    ),
                )
            );

            // register popular event organizers
            $organizers_text = ep_global_settings_button_title('Organizers');
            $popular_event_organizers =  sprintf( __( 'Popular Event %s', 'eventprime-event-calendar-management' ), $organizers_text );
            register_block_type(
                'eventprime-blocks/popular-event-organizers',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_popular_event_organizers_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $popular_event_organizers,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),
                    ),
                )
            );

            // register popular event performers
            $performers_text = ep_global_settings_button_title('Performers');
            $popular_event_performers =  sprintf( __( 'Popular Event %s', 'eventprime-event-calendar-management' ), $performers_text );
            register_block_type(
                'eventprime-blocks/popular-event-performers',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_popular_event_performers_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $popular_event_performers,
                            'type'    => 'string',
                        ),
                        'number'           => array(
                            'default' => 5,
                            'type'    => 'string',
                        ),
                    ),
                )
            );

            // register popular event types
            $event_types_text = ep_global_settings_button_title('Event Types');
            $popular_event_types =  sprintf( __( 'Popular %s', 'eventprime-event-calendar-management' ), $event_types_text );
            register_block_type(
                'eventprime-blocks/popular-event-types',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_popular_event_types_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $popular_event_types,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),
                    ),
                )
            );

            // register popular event venues
            $venues_text = ep_global_settings_button_title('Venues');
            $popular_event_venues =  sprintf( __( 'Popular Event %s', 'eventprime-event-calendar-management' ), $venues_text );
            register_block_type(
                'eventprime-blocks/popular-event-venues',
                array(
                    'editor_script'   => 'eventprime-admin-blocks-js',
                    'render_callback' => array( $this, 'eventprime_blocks_popular_event_venues_block_handler' ),
                    'attributes'      => array(
                        'title' => array(
                            'default' => $popular_event_venues,
                            'type'    => 'string',
                        ),
						'number'           => array(
							'default' => 5,
							'type'    => 'string',
						),
                    ),
                )
            );
        }

        public function eventprime_blocks_event_calendar_block_handler() {
           return $this->eventprime_blocks_event_calendar_block();
        }

        public function eventprime_blocks_event_calendar_block(){
            wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
            $events_page_id = ep_get_global_settings("events_page");
            wp_enqueue_style(
                'em-front-jquery-ui',
                EP_BASE_URL . '/includes/assets/css/jquery-ui.min.css',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_script(
                'ep-blocks-scripts',
                EP_BASE_URL . '/includes/assets/js/ep-blocks-public.js',
                false, EVENTPRIME_VERSION
            );
            $html = '';
            ob_start();
            ?>
            <div class="ep_widget_container">
                <a></a>
                <div id="ep_calendar_block"></div>
                <form name="em_calendar_event_form" method="get" action="<?php echo get_permalink( $events_page_id ); ?>">
                    <input type="hidden" name="ep-search" value="1" />
                    <input type="hidden" name="date" id="em_start_date" value="" />
                    <div class="ep_upcoming_events">
                        <div class="ep_calendar_widget-events-title"><?php esc_html_e( 'Upcoming Events', 'eventprime-event-calendar-management' ) ?></div>
                        <?php
                        $event_controller = new EventM_Event_Controller_List();
                        $query = array('meta_query'  => array(
                            'relation' => 'AND',
                                array(
                                    array(
                                        'key'     => 'em_start_date_time',
                                        'value'   =>  current_time( 'timestamp' ),
                                        'compare' => '>',
                                        'type'=>'NUMERIC'
                                    )
                                )
                            )
                        );
                        $events = $event_controller->get_events_post_data( $query );
                        $today = current_time('timestamp');
                        if ( ! empty( $events ) ) {
                            for ( $i = 0; $i < min( 5, count( $events->posts ) ); $i++ ) {
                                $event= $events->posts[$i];?>
                                <div class="ep-upcoming-event ep-box-w-100">
                                    <a href="<?php echo esc_url( $event->event_url ); ?>"><?php echo esc_attr( $event->name ); ?></a>
                                    <?php if ( $today > $event->em_start_date && $today <$event->em_end_date ){ ?>
                                        <span class="ep-live-event"><?php esc_html_e( 'Live', 'eventprime-event-calendar-management' ); ?></span>
                                    <?php } ?>
                                </div><?php
                            }
                        }?>
                    </div>
                </form>
            </div>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_event_countdown_block_handler( $atts ){
            return $this->eventprime_blocks_event_countdown_block( $atts );
        }

        public function eventprime_blocks_event_countdown_block( $atts ){
            wp_enqueue_script( 'jquery' );
            wp_enqueue_style(
                'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );
            $event_id = isset( $atts['eid'] ) ? $atts['eid'] : 0;
            $event_controller = new EventM_Event_Controller_List();
            $event = $event_controller->get_single_event( $event_id );
            if ( ! empty( $event->id ) ){
                $html = '';
                ob_start();
                if ( $event->em_start_date > current_time('timestamp') ){ ?>
                    <div class="event_title dbfl"><a href="<?php echo esc_url( $event->event_url ); ?>"><?php echo esc_html( $event->name ); ?></a></div>
                    <?php $start_date = ep_timestamp_to_date( $event->em_start_date, 'Y-m-d' );
                    $start_time = isset( $event->em_start_time ) && ! empty( $event->em_start_time ) ? $event->em_start_time : '00:00';
                    $formate = 'Y-m-d h:i';
                    $start_date_time = ep_datetime_to_timestamp($start_date.' '.$start_time, $formate);
                    $start_date = ep_timestamp_to_datetime($start_date_time);
                    wp_enqueue_script("em_countdown_jquery", EP_BASE_URL . '/includes/assets/js/jquery.countdown.min.js', false, EVENTPRIME_VERSION );
                    ?>
                    <div class="ep_block_container">
                        <div class="ep_countdown_timer dbfl" id="ep_widget_event_countdown_<?php echo $event_id; ?>">
                            <span class="days ep_color" id="ep_countdown_days_<?php echo esc_attr( $event_id ); ?>"></span>
                            <span class="hours ep_color" id="ep_countdown_hours_<?php echo esc_attr( $event_id ); ?>"></span>
                            <span class="minutes ep_color" id="ep_countdown_minutes_<?php echo esc_attr( $event_id ); ?>"></span>
                            <span class="seconds ep_color" id="ep_countdown_seconds_<?php echo esc_attr( $event_id ); ?>"></span>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function () {
                            $ = jQuery;
                            var date = new Date("<?php echo esc_attr( $start_date ); ?>");
                            $( '#ep_widget_event_countdown_<?php echo esc_attr( $event_id ); ?>' ).countdown( date, function (event) {
                                $("#ep_countdown_days_<?php echo esc_attr( $event_id ); ?>").html( event.strftime('%D') );
                                $("#ep_countdown_hours_<?php echo esc_attr( $event_id ); ?>").html( event.strftime('%H') );
                                $("#ep_countdown_minutes_<?php echo esc_attr( $event_id ); ?>").html( event.strftime('%M') );
                                $("#ep_countdown_seconds_<?php echo esc_attr( $event_id ); ?>").html( event.strftime('%S') );
                            });
                        });
                    </script><?php
                }
                $html = ob_get_contents();
                ob_end_clean();
                return $html;
            }
        }

        public function eventprime_blocks_event_slider_block_handler() {
            return $this->eventprime_blocks_event_slider_block();
        }

        public function eventprime_blocks_event_slider_block(){
            wp_enqueue_style( 'ep-responsive-slides-css' );
            wp_enqueue_script( 'ep-responsive-slides-js' );

            wp_enqueue_script(
                'ep-blocks-scripts',
                EP_BASE_URL . '/includes/assets/js/ep-blocks-public.js',
                false, EVENTPRIME_VERSION
            );
            wp_enqueue_style(
                'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $event_controller = new EventM_Event_Controller_List();
            $query = array('meta_query'  => array(
                'relation' => 'AND',
                    array(
                        array(
                            'key'     => 'em_start_date_time',
                            'value'   =>  current_time( 'timestamp' ),
                            'compare' => '>',
                            'type'=>'NUMERIC'
                        )
                    )
                )
            );
            $events = $event_controller->get_events_post_data($query);
            $number = 1;
            $html = '';
            ob_start();
            if( ! empty( $events ) && ! empty( $events->posts ) ) {?>
                <div id="ep_block_container" class="ep-event-slide-container ep-position-relative">
                    <ul class="ep_event_slides ep-event-slider-<?php echo esc_attr( $number ); ?> ep-m-0 ep-p-0">
                        <?php foreach ( $events->posts as $event ){ ?>
                            <li class="ep-m-0 ep-p-0 ep-block-event-slide">
                                <div class="ep-block-slider-meta">
                                    <?php $event_date = ep_timestamp_to_date( $event->em_start_date );?>
                                    <div class="ep-block-slider-title ep-text-truncate ep-fw-bold"><?php echo esc_html( $event->name ); ?></div>
                                    <div class="ep-block-slider-date"><?php echo esc_attr( $event_date ); ?></div>
                                </div>
                                <a target="_blank" href="<?php echo esc_url( $event->event_url ); ?>"><img src="<?php echo esc_url( $event->image_url ); ?>"> </a>
                            </li>
                        <?php }?>
                    </ul>
                    <div class="ep-event-block-slider-nav-<?php echo esc_attr( $number ); ?> ep-event-block-slider-nav" ></div>
                </div>
                <script>
                    window.onload = function() {
                        let slide_duration = eventprime.global_settings.event_detail_image_slider_duration;
                        jQuery('.ep-event-slider-<?php echo esc_attr( $number ); ?>').responsiveSlides({
                            auto: eventprime.global_settings.event_detail_image_auto_scroll,
                            speed: 500,
                            timeout: ( slide_duration * 1000 ),
                            pager: false,
                            nav: true,
                            random: false,
                            pause: true,
                            prevText: "<span class='material-icons-outlined'> arrow_back_ios </span>",
                            nextText: "<span class='material-icons-outlined'> arrow_forward_ios</span>",
                            maxwidth: "",
                            pauseControls: true,
                            navContainer: ".ep-event-block-slider-nav-<?php echo esc_attr( $number ); ?>",
                            manualControls: "",
                            namespace: "ep-block-rslides"
                        });
                    }
                </script> <?php
            }
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_featured_event_organizers_block_handler( $atts ) {
            return $this->eventprime_blocks_featured_event_organizers_block( $atts );
        }

        public function eventprime_blocks_featured_event_organizers_block( $atts ){
            wp_enqueue_style(
                'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );
            $title = isset( $atts['title'] )  ? $atts['title']  : 'Featured Event Organizers';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }

            $html = '';
            ob_start();?>

            <div class="block block_featured_orgainzers ep-blocks">
                <div class="block-content">
                    <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2>
                    <?php
                    $event_organizers_controller = new EventM_Organizer_Controller_List();
                    $organizers = $event_organizers_controller->get_featured_event_organizers( $number );
                    if( ! empty( $organizers->terms ) ){
                        $i = 0;
                        foreach ( $organizers->terms as $organizer ) { ?>
                            <div id="ep-featured-organizers"  class="ep-mw-wrap ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1">
                                <?php  $thumbnail_id = ( isset( $organizer->em_image_id ) && ! empty( $organizer->em_image_id ) ) ? $organizer->em_image_id : 0; ?>
                                <div class="ep-fimage">
                                    <?php  if ( ! empty( $thumbnail_id ) ){ ?>
                                        <a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Organizer Image', 'eventprime-event-calendar-management' );?>"></a>
                                    <?php  }else{ ?>
                                        <a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' );?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' );?>" ></a>
                                    <?php  } ?>
                                </div>
                                <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><?php echo esc_attr( $organizer->name ); ?></a></div></div>
                            </div><?php
                        }
                    } ?>
                </div>
            </div>
          <?php
          $html = ob_get_contents();
          ob_end_clean();
          return $html;
        }

        public function eventprime_blocks_featured_event_performers_block_handler( $atts ) {
           return $this->eventprime_blocks_featured_event_performers_block( $atts );
        }

        public function eventprime_blocks_featured_event_performers_block( $atts ){
            wp_enqueue_style(
            'ep-block-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Featured Event Performers';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start();?>
            <div class="block block_featured_performers ep-blocks"><div class="block-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2>
                <?php
                $event_performers_controller = new EventM_Performer_Controller_List();
                $performers = $event_performers_controller->get_featured_event_performers( array(), $number );
                if( ! empty( $performers->posts ) ){
                    $i = 0;
                    foreach ( $performers->posts as $performer ) { ?>
                        <div class="ep-popular-performer ep-fh ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1">
                            <?php $thumbnail_id = ( isset( $performer->_thumbnail_id ) && ! empty( $performer->_thumbnail_id ) ) ? $performer->_thumbnail_id : 0; ?>
                            <div class="ep-fimage">
                                <?php
                                if ( ! empty( $thumbnail_id ) ){ ?>
                                    <a href="<?php echo esc_url( $performer->performer_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Performer Image', 'eventprime-event-calendar-management' ); ?>"></a>
                                <?php
                                }else{ ?>
                                    <a href="<?php echo esc_url( $performer->performer_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' ); ?>" alt="<?php esc_html_e('Dummy Image','eventprime-event-calendar-management'); ?>" ></a>
                                <?php
                                } ?>
                            </div>
                            <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $performer->performer_url ); ?>"><?php echo esc_attr( $performer->name ); ?></a></div>
                            <?php
                            if( ! empty( $performer->em_role ) ){ ?>
                                <div class="ep-featured-performer-role ep-fs-6 ep-text-muted"><?php echo esc_attr( $performer->em_role ); ?></div>
                            <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_featured_event_types_block_handler( $atts ) {
            return $this->eventprime_blocks_featured_event_types_block( $atts );
        }

        public function eventprime_blocks_featured_event_types_block( $atts ){
            wp_enqueue_style(
            'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Featured Event-Types';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start();?>
            <div class="block block_featured_events ep-blocks"><div class="block-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2>
                <?php
                $event_types_controller = new EventM_Event_Type_Controller_List();
                $types = $event_types_controller->get_featured_event_types( $number );

                if( ! empty( $types->terms ) ){
                    $i = 0;
                    foreach ( $types->terms as $type ) { ?>
                        <div class="ep-featured-events-type ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1"><?php
                            $title = $type->name;
                            $thumbnail_id = ( isset( $type->em_image_id ) && ! empty( $type->em_image_id ) ) ? $type->em_image_id : 0; ?>
                            <div class="ep-fimage">
                            <?php if ( ! empty( $thumbnail_id ) ){ ?>
                                <a href="<?php echo esc_url( $type->event_type_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Type Image', 'eventprime-event-calendar-management' ); ?>"></a>
                            <?php }else{ ?>
                                <a href="<?php echo esc_url( $type->event_type_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" ></a>
                            <?php } ?>
                            </div>
                            <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $type->event_type_url ); ?>"><?php echo esc_attr( $type->name ); ?></a></div>
                            </div>
                        </div><?php
                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_featured_event_venues_block_handler( $atts ) {
            return $this->eventprime_blocks_featured_event_venues_block( $atts );
        }

        public function eventprime_blocks_featured_event_venues_block( $atts ){
            wp_enqueue_style(
            'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Featured Event Venues';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start();?>
            <div class="block block_featured_venues ep-blocks"><div class="block-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2>
                <?php
                $event_types_controller = new EventM_Venue_Controller_List();
                $venues = $event_types_controller->get_featured_event_venues( $number );

                if( ! empty( $venues->terms ) ){
                    $i = 0;
                    foreach ( $venues->terms as $venue ) { ?>
                        <div  class="ep-featured-event-venues ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1"><?php
                            $title = $venue->name;
                            $thumbnail_id = ( isset( $venue->em_gallery_images[0] ) && ! empty( $venue->em_gallery_images[0] ) ) ? $venue->em_gallery_images[0] : 0;  ?>
                            <div class="ep-fimage">
                            <?php
                            if ( ! empty( $thumbnail_id ) ) { ?>
                                <a href="<?php echo esc_url( $venue->venue_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Venue Image', 'eventprime-event-calendar-management' ); ?>"></a>
                            <?php }else{ ?>
                                <a href="<?php echo esc_url( $venue->venue_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" ></a>
                            <?php } ?>
                            </div>
                            <div class="ep-fdata"><div class="ep-fname"><a href="<?php echo esc_url( $venue->venue_url ); ?>"><?php echo esc_attr( $venue->name ); ?></a></div>
                            </div>
                        </div><?php
                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_popular_event_organizers_block_handler( $atts ) {
            return $this->eventprime_blocks_popular_event_organizers_block( $atts );
        }

        public function eventprime_blocks_popular_event_organizers_block( $atts ){
            wp_enqueue_style(
            'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Popular Event Organizers';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start();
            ?>
            <div class="block block_popular_orgainzers ep-blocks"><div class="widget-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2><?php
                $event_organizers_controller = new EventM_Organizer_Controller_List();
                $organizers = $event_organizers_controller->get_popular_event_organizers( $number );

                if( ! empty( $organizers->terms ) ){
                    $i = 0;
                    foreach ( $organizers->terms as $organizer ) { ?>
                        <div class="ep-popular-organizer ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1">
                          <?php
                            $thumbnail_id = ( isset( $organizer->em_image_id ) && ! empty( $organizer->em_image_id ) ) ? $organizer->em_image_id : 0;
                            ?><div class="ep-fimage">
                            <?php if ( ! empty( $thumbnail_id ) ){ ?>
                                <a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Organizer Image', 'eventprime-event-calendar-management' ); ?>"></a>
                            <?php }else{ ?>
                                <a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" ></a>
                            <?php } ?>
                            </div>
                            <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $organizer->organizer_url ); ?>"><?php echo esc_attr( $organizer->name ); ?></a></div>
                            </div>
                       </div><?php
                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_popular_event_performers_block_handler( $atts ) {
            return $this->eventprime_blocks_popular_event_performers_block( $atts );
        }

        public function eventprime_blocks_popular_event_performers_block( $atts ){
            wp_enqueue_style(
            'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Popular Event Performers';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start();?>

            <div class="block block_featured_performers ep-blocks"><div class="block-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2><?php
                $event_performers_controller = new EventM_Performer_Controller_List();
                $performers = $event_performers_controller->get_popular_event_performers( $number );

                if( ! empty( $performers->posts ) ){
                    $i = 0;
                    foreach ( $performers->posts as $performer ) {
                        if( isset( $performer->events ) && ! empty( $performer->events ) ){ ?>
                            <div class="ep-featured-performer ep-fh ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1">
                                <?php
                                $thumbnail_id = ( isset( $performer->_thumbnail_id ) && ! empty( $performer->_thumbnail_id ) ) ? $performer->_thumbnail_id : 0; ?>
                                <div class="ep-fimage">
                                <?php
                                if ( ! empty( $thumbnail_id ) ){ ?>
                                    <a href="<?php echo esc_url( $performer->performer_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Venue Image', 'eventprime-event-calendar-management' ); ?>"></a>
                                <?php }else{ ?>
                                    <a href="<?php echo esc_url( $performer->performer_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' ); ?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' ); ?>" ></a>
                               <?php } ?>
                               </div>
                                <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $performer->performer_url ); ?>"><?php echo esc_attr( $performer->name );?></a></div>
                                <?php
                                if( ! empty( $performer->em_role ) ){ ?>
                                    <div class="ep-featured-performer-role ep-fs-6 ep-text-muted"><?php echo esc_attr( $performer->em_role ); ?></div>
                                <?php } ?>
                                </div>
                            </div>
                            <?php
                            $i++;
                            if( $number <= $i ) break;
                        }

                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_popular_event_types_block_handler( $atts ) {
            return $this->eventprime_blocks_popular_event_types_block( $atts );
        }

        public function eventprime_blocks_popular_event_types_block( $atts ){
            wp_enqueue_style(
            'ep-blocks-style',
                EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                false, EVENTPRIME_VERSION
            );

            $title = isset( $atts['title'] )  ? $atts['title']  : 'Popular Event-Types';
            $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
            if ( !$number ) {
                $number = 5;
            }
            $html = '';
            ob_start(); ?>

            <div class="block block_popular_events ep-blocks"><div class="block-content">
                <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2><?php
                $event_venues_controller = new EventM_Event_Type_Controller_List();
                $types = $event_venues_controller->get_popular_event_types( $number );

                if( ! empty( $types->terms ) ){
                    $i = 0;
                    foreach ( $types->terms as $type ) { ?>
                        <div class="ep-popular-events-type ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1"><?php
                            $title = $type->name;
                            $thumbnail_id = ( isset( $type->em_image_id ) && ! empty( $type->em_image_id ) ) ? $type->em_image_id : 0; ?>
                            <div class="ep-fimage">
                            <?php
                            if ( ! empty( $thumbnail_id ) ){ ?>
                                <a href="<?php echo esc_url( $type->event_type_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id, 'large' )[0];?>" alt="<?php esc_html_e( 'Event Type Image', 'eventprime-event-calendar-management' );?>" width="80px" height="80px"></a>
                            <?php }else{ ?>
                                <a href="<?php echo esc_url( $type->event_type_url ); ?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' );?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' );?>" ></a>
                            <?php } ?>
                            </div>
                            <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $type->event_type_url );?>"><?php echo esc_attr( $type->name );?></a></div>
                            </div>
                        </div><?php
                    }
                } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }

        public function eventprime_blocks_popular_event_venues_block_handler( $atts ) {
            return $this->eventprime_blocks_popular_event_venues_block( $atts );
        }

        public function eventprime_blocks_popular_event_venues_block( $atts ){
            wp_enqueue_style(
                'ep-blocks-style',
                    EP_BASE_URL . '/includes/assets/css/ep-blocks-style.css',
                    false, EVENTPRIME_VERSION
                );

                $title = isset( $atts['title'] )  ? $atts['title']  : 'Featured Event Venues';
                $number = isset( $atts['number'] ) ? absint( $atts['number'] ) : 5;
                if ( !$number ) {
                    $number = 5;
                }
                $html = '';
                ob_start(); ?>

                <div class="block block_popular_venues ep-blocks"><div class="block-content">
                    <h2 class="block-title subheading heading-size-3"><?php echo esc_attr( $title );?></h2><?php
                    $event_venues_controller = new EventM_Venue_Controller_List();
                    $venues = $event_venues_controller->get_popular_event_venues( $number );

                    if( ! empty( $venues->terms ) ){
                        $i = 0;
                        foreach ( $venues->terms as $venue ) { ?>
                            <div class="ep-popular-event-venue ep-blocks-block-wrap ep-d-flex ep-p-2 ep-my-3 ep-shadow-sm ep-border ep-rounded-1"><?php
                                $title = $venue->name;
                                $thumbnail_id = ( isset( $venue->em_image_id ) && ! empty( $venue->em_image_id ) ) ? $venue->em_image_id : 0; ?>
                                <div class="ep-fimage">
                                <?php
                                if ( ! empty( $thumbnail_id ) ){ ?>
                                    <a href="<?php echo esc_url( $venue->venue_url ); ?>"><img src="<?php echo wp_get_attachment_image_src( $thumbnail_id[0], 'large' )[0]; ?>" alt="<?php esc_html_e( 'Event Venue Image', 'eventprime-event-calendar-management' ); ?>"></a>
                                <?php }else { ?>
                                    <a href="<?php echo esc_url( $venue->venue_url );?>"><img src="<?php echo esc_url( EP_BASE_URL .'includes/assets/css/images/dummy_image.png' );?>" alt="<?php esc_html_e( 'Dummy Image', 'eventprime-event-calendar-management' );?>" ></a>
                                <?php } ?>
                                </div>
                                <div class="ep-fdata"><div class="ep-fname ep-mt-2"><a href="<?php echo esc_url( $venue->venue_url ); ?>"><?php echo esc_attr( $venue->name );?></a></div>
                                </div>
                            </div><?php
                        }
                    } ?>
            </div></div><?php
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        }
    }

}
add_filter( 'block_categories_all', __NAMESPACE__ . '\register_event_prime_block_category', 10, 2 );
/**
 * Registers the Event Prime category for blocks.
 *
 * @since 2.0
 * @param array                    $block_categories
 * @param \WP_Block_Editor_Context $editor_context
 * @return array
 */
function register_event_prime_block_category( $block_categories, $editor_context ) {
	$block_categories[] = array(
		'slug'  => 'event-prime',
		'title' => __( 'Event Prime - Modern Events', 'event-prime' ),
		'icon'  => 'crown',
	);

	return $block_categories;
}
