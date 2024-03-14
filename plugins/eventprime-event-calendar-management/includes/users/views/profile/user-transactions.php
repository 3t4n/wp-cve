<div class="ep-tab-content ep-item-hide" id="ep-list-transactions" role="tabpanel" aria-labelledby="#ep-list-transactions">
    <?php if( ! empty( $args->all_bookings ) && count( $args->all_bookings ) > 0 ) {?>
        <div class="ep-box-row ep-mb-4">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="ep-text-uppercase ep-fw-bold ep-text-smal">
                    <?php esc_html_e( 'My Transactions', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <!-- <div class="ep-box-row ep-mb-3 ep-border ep-rounded ep-p-2">
            <div class="ep-box-col-auto ep-p-0">
                <select class="ep-form-select ep-form-select-sm" aria-label="Default select example">
                    <option selected="">All Transactions</option>
                    <option value="1">Completed</option>
                    <option value="2">Pending</option>
                    <option value="2">Refunded</option>
                </select>
            </div>
            <div class="ep-box-col-auto">
                <input type="date" class="ep-form-control ep-form-control-sm">
            </div>
            <div class="ep-box-col-auto">
                <input type="date" class="ep-form-control ep-form-control-sm">
            </div>
        </div> -->
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-small ep-mb-2 ep-text-end">
                <span class="ep-fw-bold"><?php echo count( $args->all_bookings );?></span>
                <span class="">
                    <?php esc_html_e( 'records found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div>
        <div class="ep-box-row">
            <div class="ep-box-col-12">
                <table class="ep-table ep-text-small ep-text-start">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col"><?php esc_html_e( 'Event', 'eventprime-event-calendar-management');?></th>
                            <th scope="col"><?php esc_html_e( 'Transaction Date', 'eventprime-event-calendar-management');?></th>
                            <th scope="col"><?php esc_html_e( 'Amount', 'eventprime-event-calendar-management');?></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $num = 1; foreach( $args->all_bookings as $booking ) {
                            if( ! empty( $booking->em_id ) && ! empty( $booking->event_data ) && ! empty( $booking->event_data->em_id ) ) {?>
                                <tr>
                                    <th scope="row"><?php echo absint( $num );?></th>
                                    <td><?php echo esc_html( $booking->event_data->name );?></td>
                                    <td>
                                        <?php echo esc_html( ep_timestamp_to_datetime( $booking->em_date, 'd M, Y H:iA' ) );?>
                                    </td>
                                    <td><?php if( isset( $booking->em_order_info ) && ! empty( $booking->em_order_info ) ) {
                                        echo ep_price_with_position( $booking->em_order_info['booking_total'] );
                                    }?> </td>
                                    <td>
                                        <a href="<?php echo esc_url( $booking->booking_detail_url );?>" target="_blank">
                                            <?php esc_html_e( 'Details', 'eventprime-event-calendar-management' ); ?>
                                        </a>
                                    </td>
                                </tr><?php
                                $num++;
                            }
                        }?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <div class="ep-box-row">
            <div class="ep-box-col-12 ep-mb-3 ep-text-center">
                <button type="button" class="ep-btn ep-btn-outline-dark ep-btn-sm">Load More</button>
            </div>
        </div> --><?php
    } else{?>
        <div class="ep-box-row">
            <div class="ep-box-col-12 ep-border-left ep-border-3 ep-ps-3 ep-border-warning ep-mb-4">
                <span class="text-uppercase fw-bold small">
                    <?php esc_html_e( 'No transactions found', 'eventprime-event-calendar-management');?>
                </span>
            </div>
        </div><?php
    }?>
</div>