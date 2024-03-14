<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings      = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
$tracking_code = 'customize_preview';
$track_service = $settings->get_params( 'service_carrier_type' );
$sort_event    = $settings->get_params( 'timeline_track_info_sort_event' );

$title       = $settings->get_params( 'timeline_track_info_title' );
$date_format = $settings->get_params( 'timeline_track_info_date_format' );
$time_format = $settings->get_params( 'timeline_track_info_time_format' );
$shorcode    = array(
	'carrier_name' => 'Carrier Name',
	'tracking'     => array(
		array(
			'description' => esc_html__( 'Description for event (status: Delivered)', 'woo-orders-tracking' ),
			'location'    => esc_html__( 'Thai Nguyen, VietNam', 'woo-orders-tracking' ),
			'status'      => 'delivered',
			'time'        => '2020-01-11 9:50:12',
		),
		array(
			'description' => esc_html__( 'Description for event (status: In transit)', 'woo-orders-tracking' ),
			'location'    => esc_html__( 'Thai Nguyen, VietNam', 'woo-orders-tracking' ),
			'status'      => 'transit',
			'time'        => '2020-01-03 22:11:33',
		),
		array(
			'description' => esc_html__( 'Description for event (status: In transit)', 'woo-orders-tracking' ),
			'location'    => esc_html__( 'Thai Nguyen, VietNam', 'woo-orders-tracking' ),
			'status'      => 'transit',
			'time'        => '2020-01-03  2:15:30',
		),
		array(
			'description' => esc_html__( 'Description for event (status: Pickup)', 'woo-orders-tracking' ),
			'location'    => esc_html__( 'Thai Nguyen, VietNam', 'woo-orders-tracking' ),
			'status'      => 'pickup',
			'time'        => '2020-01-01 1:22:34',
		),
		array(
			'description' => esc_html__( 'Description for event (status: Pending)', 'woo-orders-tracking' ),
			'location'    => esc_html__( 'Thai Nguyen, VietNam', 'woo-orders-tracking' ),
			'status'      => '',
			'time'        => '2020-01-01 00:00:00',
		),
	),
);

$title = str_replace(
	array(
		'{carrier_name}',
		'{tracking_number}',
	),
	array(
		$shorcode['carrier_name'],
		strtoupper( $tracking_code )
	),
	$title
);
?>
<div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array( 'preview-shortcode-template-one' ) ) ) ?>">
    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
		'shortcode-timeline-wrap-template-one',
		'shortcode-timeline-wrap',
		'most-recent-to-oldest'
	) ) ) ?> <?php echo esc_attr( $sort_event === 'most_recent_to_oldest' ? '' : ' woo-orders-tracking-shortcode-hidden' ) ?>">
        <h2 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-title' ) ) ?>">
			<?php echo esc_html( $title ) ?>
        </h2>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-delivered'
		) ) ) ?>">
			<?php esc_html_e( 'Delivered', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pickup',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pickup', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-transit',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'In Transit', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pending',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pending', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-alert',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Alert', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			$track_info       = $shorcode['tracking'];
			$track_info_count = count( $track_info );
			$event_no         = $track_info_count;
			for ( $i = 0; $i < $track_info_count; $i ++ ) {
				?>
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $track_info[ $i ]['status']
					) ) ) ?>">
						<?php
						echo wp_kses_post( $this->get_icon_status( $track_info[ $i ]['status'], '1' ) );
						?>
                    </div>
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content' ) ) ?>">
                            <h4 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-des' ) ) ?>">
								<?php echo esc_html( "{$event_no}. {$track_info[ $i ]['description']}" ) ?>
                            </h4>
                            <p>
                                <span class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-location' ) ) ?>">
                                    <?php echo esc_html( $track_info[ $i ]['location'] ) ?>
                                </span>
                                <span class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-time' ) ) ?>">
                                    <?php
                                    echo esc_html( date_format( date_create( $track_info[ $i ]['time'] ), $date_format . ' ' . $time_format ) );
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
				<?php
				$event_no --;
			}
			?>
        </div>
    </div>
    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
		'shortcode-timeline-wrap-template-one',
		'shortcode-timeline-wrap',
		'oldest-to-most-recent'
	) ) ) ?> <?php echo esc_attr( $sort_event === 'oldest_to_most_recent' ? '' : ' woo-orders-tracking-shortcode-hidden' ) ?>">
        <h2 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-title' ) ) ?>">
			<?php echo esc_html( $title ) ?>
        </h2>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-delivered'
		) ) ) ?>">
			<?php esc_html_e( 'Delivered', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pickup',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pickup', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-transit',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'In Transit', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pending',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pending', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-alert',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Alert', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			$event_no = 1;
			for ( $i = $track_info_count - 1; $i >= 0; $i -- ) {
				?>
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $track_info[ $i ]['status']
					) ) ) ?>">
						<?php
						echo wp_kses_post( $this->get_icon_status( $track_info[ $i ]['status'], '1' ) );
						?>
                    </div>
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content' ) ) ?>">
                            <h4 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-des' ) ) ?>">
								<?php echo esc_html( "{$event_no}. {$track_info[ $i ]['description']}" ) ?>
                            </h4>
                            <p>
                                <span class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-location' ) ) ?>">
                                    <?php echo esc_html( $track_info[ $i ]['location'] ) ?>
                                </span>
                                <span class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-time' ) ) ?>">
                                    <?php
                                    echo esc_html( date_format( date_create( $track_info[ $i ]['time'] ), $date_format . ' ' . $time_format ) );
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
				<?php
				$event_no ++;
			}
			?>
        </div>
    </div>
</div>
<div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array( 'preview-shortcode-template-two' ) ) ) ?>">
    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
		'shortcode-timeline-wrap-template-two',
		'shortcode-timeline-wrap-most_recent_to_oldest',
		'shortcode-timeline-wrap',
		'most-recent-to-oldest'
	) ) ) ?> <?php echo esc_attr( $sort_event === 'most_recent_to_oldest' ? '' : ' woo-orders-tracking-shortcode-hidden' ) ?>">
        <h2 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-title' ) ) ?>">
			<?php echo esc_html( $title ) ?>
        </h2>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-delivered'
		) ) ) ?>">
			<?php esc_html_e( 'Delivered', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pickup',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pickup', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-transit',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'In Transit', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pending',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pending', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-alert',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Alert', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			$group_event = '';
			for ( $i = 0; $i < $track_info_count; $i ++ ) {
				ob_start();
				?>
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $track_info[ $i ]['status']
					) ) ) ?>">
                    </div>
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-date' ) ) ?>">
							<?php
							echo esc_html( date_format( date_create( $track_info[ $i ]['time'] ), $date_format . ' ' . $time_format ) );
							?>
                        </div>
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-des-wrap' ) ) ?>">
                            <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-des' ) ) ?>">
								<?php echo esc_html( $track_info[ $i ]['description'] ) ?>
                            </div>
                            <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-location' ) ) ?>">
								<?php echo esc_html( trim( $track_info[ $i ]['location'], ' ' ) ) ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				$group_event .= ob_get_clean();
				if ( $i < $track_info_count - 1 ) {
					if ( strtotime( date( 'Y-m-d', strtotime( $track_info[ $i ]['time'] ) ) ) !== strtotime( date( 'Y-m-d', strtotime( $track_info[ $i + 1 ]['time'] ) ) ) ) {
						?>
                        <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
						<?php
						$group_event = '';
					}
				} else {
					?>
                    <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
					<?php
					$group_event = '';
				}
			}
			?>
        </div>
    </div>
    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
		'shortcode-timeline-wrap-template-two',
		'shortcode-timeline-wrap-oldest_to_most_recent',
		'shortcode-timeline-wrap',
		'oldest-to-most-recent'
	) ) ) ?> <?php echo esc_attr( $sort_event === 'oldest_to_most_recent' ? '' : ' woo-orders-tracking-shortcode-hidden' ) ?>">
        <h2 class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-title' ) ) ?>">
			<?php echo esc_html( $title ) ?>
        </h2>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-delivered'
		) ) ) ?>">
			<?php esc_html_e( 'Delivered', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pickup',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pickup', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-transit',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'In Transit', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-pending',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Pending', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
			'shortcode-timeline-status-wrap',
			'shortcode-timeline-status-alert',
			'shortcode-hidden'
		) ) ) ?>">
			<?php esc_html_e( 'Alert', 'woo-orders-tracking' ) ?>
        </div>
        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-events-wrap' ) ); ?>">
			<?php
			$group_event = '';
			for ( $i = $track_info_count - 1; $i >= 0; $i -- ) {
				ob_start();
				?>
                <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event' ) ) ?>">
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( array(
						'shortcode-timeline-icon',
						'shortcode-timeline-icon-' . $track_info[ $i ]['status']
					) ) ) ?>">
                    </div>
                    <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-wrap' ) ) ?>">
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-date' ) ) ?>">
							<?php
							echo esc_html( date_format( date_create( $track_info[ $i ]['time'] ), $date_format . ' ' . $time_format ) );
							?>
                        </div>
                        <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-des-wrap' ) ) ?>">
                            <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-content-des' ) ) ?>">
								<?php echo esc_html( $track_info[ $i ]['description'] ) ?>
                            </div>
                            <div class="<?php echo esc_attr( VI_WOO_ORDERS_TRACKING_DATA::set( 'shortcode-timeline-event-location' ) ) ?>">
								<?php echo esc_html( trim( $track_info[ $i ]['location'], ' ' ) ) ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
				$group_event .= ob_get_clean();
				if ( $i < $track_info_count - 1 ) {
					if ( strtotime( date( 'Y-m-d', strtotime( $track_info[ $i ]['time'] ) ) ) !== strtotime( date( 'Y-m-d', strtotime( $track_info[ $i + 1 ]['time'] ) ) ) ) {
						?>
                        <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
						<?php
						$group_event = '';
					}
				} else {
					?>
                    <div class="woo-orders-tracking-shortcode-timeline-events-group"><?php echo wp_kses_post( $group_event ) ?></div>
					<?php
					$group_event = '';
				}
			}
			?>
        </div>
    </div>
</div>
