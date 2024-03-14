<div class="emagic">
    <div class="wrap ep-box-wrap">
        <div id="poststuff">
            <div id="postbox-container" class="postbox-container">
                <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                    <div class="postbox ep-border ep-border-bottom-0 ep-m-0" id="test2">
                        <div class="inside">
                            <div class="ep-report-forms">
                                <div class="ep-box-row ep-mt-3">
                                    <div class="ep-box-col-3">
                                        <div class="ep-report-filter-attr ep-d-flex ep-align-items-center" id="ep-reports-datepicker-div">
                                            <label class="ep-form-label"> <?php esc_html_e( 'Date', 'eventprime-event-calendar-management' ); ?></label> 
                                            <div class="ep-form-input ep-form-control"><input class="ep-form-control" id="ep-reports-datepicker" type="text"/> </div> 
                                        </div>
                                    </div>
                                    <div class="ep-box-col-3">
                                        <div class="ep-report-filter-attr ep-d-flex ep-align-items-center">
                                            <label> <?php esc_html_e( 'Event', 'eventprime-event-calendar-management' ); ?></label>
                                            <div class=" ep-form-input ep-form-control">
                                                <select id="ep_event_id" class="ep-form-control" name="event" >
                                                    <option value="all"><?php esc_html_e( 'All Event', 'eventprime-event-calendar-management' ); ?></option>
                                                    <?php 
                                                    if( ! empty( $events_lists ) ) {
                                                        foreach( $events_lists as $event ) {?>
                                                            <option value="<?php echo absint( $event['id'] );?>"><?php echo esc_html( $event['name'] );?></option><?php
                                                        }
                                                    }?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ep-box-col-2 ep-d-flex ep-align-items-center">
                                        <div class="ep-report-filter-attr ">
                                            <button type="button" id="ep_booking_filter" class="button-primary ep-btn ep-ar-btn-primary"><?php esc_html_e( 'Filter', 'eventprime-event-calendar-management' ); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="postbox ep-border" id="test1">
                        <div class="inside chart-with-sidebar">
                            <div class="ep-box-row ep-border-bottom ep-pb-2">
                                <div class="ep-box-col-12">
                                    <div class="chart-sidebar" id="ep_booking_stat_container">
                                        <?php echo do_action('ep_bookings_report_stat', $bookings_data );?>
                                    </div>
                                </div>
                            </div>
                            <div class="ep-box-row">
                                <div class="ep-box-col-12">
                                    <div class="ep-box-row">
                                        <div class="ep-box-col-12">
                                            <div class="ep-box-w-100 ep-border ep-mt-3" id="ep_bookings_chart"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="ep-report-booking-list">
        <?php echo do_action('ep_bookings_report_bookings_list', $bookings_data);?>
    </div>

</div>
<script>
    document.addEventListener( "DOMContentLoaded", function(event) {
        google.load('visualization', '1', {packages: ['corechart']});
        google.charts.setOnLoadCallback( function() { 
            drawBookingsChart(<?php echo json_encode( $bookings_data->chart );?>);
        });
    });
</script>

<style>
    .daterangepicker{
        margin-left:40px;
    }
</style>