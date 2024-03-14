<ul class="ep-chart-legend ep-mt-3 ep-d-flex">
    <li>
        <div class="ep-chart-legend-stat ep-fs-2"><?php echo esc_html( ep_price_with_position( $bookings_data->stat->total_revenue ) ); ?></div>
        <div class="ep-chart-legend-text ep-text-muted ep-text-small"><?php esc_html_e( 'gross sales in this period', 'eventprime-event-calendar-management' ); ?></div>
    </li>
    <li>
        <div class="ep-chart-legend-stat ep-fs-2"><?php
            if ( ! empty( $bookings_data->stat->total_revenue ) ) {
                echo esc_html( ep_price_with_position( $bookings_data->stat->total_revenue / $bookings_data->stat->days_count ) );
            } else {
                echo esc_html( ep_price_with_position(0) );
            }?>
        </div>
        <div class="ep-chart-legend-text ep-text-muted ep-text-small"><?php esc_html_e( 'Average gross daily sales', 'eventprime-event-calendar-management' ); ?></div>
    </li>
    <li>
        <div class="ep-chart-legend-stat ep-fs-2"><?php echo esc_html( $bookings_data->stat->total_booking ); ?></div>
        <div class="ep-chart-legend-text ep-text-muted ep-text-small"><?php esc_html_e( 'Booking placed', 'eventprime-event-calendar-management' ); ?></div>
    </li>
    <li>
        <div class="ep-chart-legend-stat ep-fs-2"><?php echo esc_html( $bookings_data->stat->total_attendees ); ?></div>
        <div class="ep-chart-legend-text ep-text-muted ep-text-small"><?php esc_html_e( 'Tickets purchased', 'eventprime-event-calendar-management' ); ?></div>
    </li>
    <li>
        <div class="ep-chart-legend-stat ep-fs-2"><?php echo esc_html( ep_price_with_position( $bookings_data->stat->coupon_discount ) ); ?></div>
        <div class="ep-chart-legend-text ep-text-muted ep-text-small"><?php esc_html_e( 'Coupon Used', 'eventprime-event-calendar-management' ); ?></div>
    </li>
</ul>