<?php
class CountdownShortcode {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'tecc_register_frontend_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'tecc_register_frontend_assets' ) ); // registers js and css for frontend.
		add_shortcode( 'events-calendar-countdown', array( $this, 'tecc_event_calendar_countdown_shortcodes' ) );// used to register shortcode handler.
	}

	function tecc_register_frontend_assets() {
		wp_register_script( 'countdown-js', TECC_JS_DIR . '/countdown.js', array( 'jquery' ), TECC_VERSION_CURRENT );
		wp_register_style( 'countdown-css', TECC_CSS_URL . '/countdown.css', array(), TECC_VERSION_CURRENT );
		global $post;
		if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'events-calendar-countdown' ) ) {
			wp_enqueue_style( 'countdown-css' );

		}
	}

	public function tecc_event_calendar_countdown_shortcodes( $atts ) {
		if ( ! function_exists( 'tribe_get_events' ) ) {
			return;
		}

		$tecc_shortcode = shortcode_atts(
			array(
				'id'                       => '',
				'backgroundcolor'          => '',
				'font-color'               => '',
				'show-seconds'             => '',
				'show-image'			   => '',
				'fontsize'                 => '',
				'textsize'                 => '',
				'size'                     => '',
				'event-end'                => '',
				'event-start'              => '',
				'autostart-next-countdown' => '',
				'future-events-list'       => '',
				'autostart-text'           => '',

			),
			$atts
		);

		wp_enqueue_script( 'countdown-js' );

		$c_output = '';
		$event    = '';
		$event_ID = '';

		$autostart        = isset( $atts['autostart-next-countdown'] ) && ! empty( $atts['autostart-next-countdown'] ) ? $atts['autostart-next-countdown'] : 'no';
		$autostart_future = isset( $atts['autostart-future-countdown'] ) && ! empty( $atts['autostart-future-countdown'] ) ? $atts['autostart-future-countdown'] : 'no';
		
		$feventsList      = tribe_get_events(
			array(
				'posts_per_page' => -1,
				'post_type'      => 'tribe_events',
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => '_EventStartDate',
						'value'   => current_time( 'Y-m-d H:i:s' ),
						'compare' => '>',
						'type'    => 'DATETIME',
					),
				),
			)
		);
		$evIDarry         = array();
		$ev_st_date       = array();
		if ( is_array( $feventsList ) && array_filter( $feventsList ) != null ) {
			foreach ( $feventsList as $event ) {
				$evIDarry[] = $event->ID;
			}
		}
		$event_list = '';
		if ( $autostart == 'yes' ) {

			if ( $autostart_future == 'yes' ) {
				$event_list = $evIDarry;
			} elseif ( $atts['future-events-list'] ) {
				$f_ev_list  = $atts['future-events-list'];
				$event_list = explode( ',', $f_ev_list );
			}
			if ( is_array( $event_list ) && !empty($event_list) ) {
				foreach ( $event_list as $key => $post_id ) {
					$ev_startdate           = tribe_get_start_date( $post_id, false, Tribe__Date_Utils::DBDATETIMEFORMAT );
					$ev_st_date[ $post_id ] = strtotime( $ev_startdate );
					asort( $ev_st_date );
				}
				$next_event = array();
				if ( is_array( $ev_st_date ) ) {
					foreach ( $ev_st_date as $ev_ID => $ev_date ) {
						$seconds = $ev_date - current_time( 'timestamp' );
						if ( $seconds > 0 ) {
							$next_event['id']         = $ev_ID;
							$next_event['start_date'] = $ev_date;
							$event                    = get_post( $next_event['id'] );
							$event_ID                 = $next_event['id'];
							break;
						}
					}
				}
			} else {
				if ( ! empty( $atts['id'] && is_numeric( $atts['id'] ) ) ) {
					$atts['event_ID'] = (int) $atts['id'];
					$event            = get_post( $atts['event_ID'] );
					$event_ID         = $atts['event_ID'];
				}
			}
		} else {
			if ( ! empty( $atts['id'] && is_numeric( $atts['id'] ) ) ) {
				$atts['event_ID'] = (int) $atts['id'];
				$event            = get_post( $atts['event_ID'] );
				$event_ID=$atts['event_ID'];

			}
		}

		if ( $event instanceof WP_Post ) {

			$c_output = tecc_get_output( $event, $atts, $event_ID, $autostart );
		} else {

			$c_output .= '<div class="tecc-no-event-msz">' . __( 'There is no upcoming event', 'tecc' ) . '</div>';
		}
		return $c_output;
	}


}
