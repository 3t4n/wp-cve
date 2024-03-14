<?php
$ep_select_result_page = get_post_meta($args->event->em_id, 'ep_select_result_page', true);
if (!empty($ep_select_result_page)) {
    $ep_result_page_link = get_page_link($ep_select_result_page);
    if (!empty($ep_result_page_link)) {
        $show_event_result = 1;
        if (!empty($args->event->em_id)) {
            $ep_result_start_from_type = get_post_meta($args->event->em_id, 'ep_result_start_from_type', true);
            if (!empty($ep_result_start_from_type)) {
                if ($ep_result_start_from_type == 'custom_date') {
                    $ep_result_start_date = get_post_meta($args->event->em_id, 'ep_result_start_date', true);
                    $ep_result_start_time = get_post_meta($args->event->em_id, 'ep_result_start_time', true);
                    if (!empty($ep_result_start_date)) {
                        $start_date = $ep_result_start_date;
                        if (!empty($ep_result_start_time)) {
                            $start_date = ep_timestamp_to_date($ep_result_start_date);
                            $start_date .= ' ' . $ep_result_start_time;
                            // $start_date = ep_datetime_to_timestamp( $start_date );
                            $start_date = strtotime($start_date);
                        }
                        if ($start_date > ep_get_current_timestamp()) {
                            $show_event_result = 0;
                        }
                    }
                } else if ($ep_result_start_from_type == 'event_date') {
                    $ep_result_start_event_option = get_post_meta($args->event->em_id, 'ep_result_start_event_option', true);
                    $ep_result_start_event_option = $ep_result_start_event_option;
                    if ($ep_result_start_event_option == 'event_ends') {
                        $em_end_date = get_post_meta($args->event->em_id, 'em_end_date', true);
                        $em_end_time = get_post_meta($args->event->em_id, 'em_end_time', true);
                        $end_date = $em_end_date;
                        if (!empty($em_end_time)) {
                            $end_date = ep_timestamp_to_date($em_end_date);
                            $end_date .= ' ' . $em_end_time;
                            // $end_date = ep_datetime_to_timestamp( $end_date );
                            $end_date = strtotime($end_date);
                        }
                        if ($end_date > ep_get_current_timestamp()) {
                            $show_event_result = 0;
                        }
                    } else if ($ep_result_start_event_option == 'event_start') {
                        $em_start_date = get_post_meta($args->event->em_id, 'em_start_date', true);
                        $em_start_time = get_post_meta($args->event->em_id, 'em_start_time', true);
                        $start_date = $em_start_date;
                        if (!empty($em_start_time)) {
                            $start_date = ep_timestamp_to_date($em_start_date);
                            $start_date .= ' ' . $em_start_time;
                            // $start_date = ep_datetime_to_timestamp( $start_date );
                            $start_date = strtotime($start_date);
                        }
                        if ($start_date > ep_get_current_timestamp()) {
                            $show_event_result = 0;
                        }
                    }
                } else if ($ep_result_start_from_type == 'relative_date') {
                    $days = get_post_meta($args->event->em_id, 'ep_result_start_days', true);
                    $days_option = get_post_meta($args->event->em_id, 'ep_result_start_days_option', true);
                    $event_option = get_post_meta($args->event->em_id, 'ep_result_start_event_option', true);
                    $days_string = ' days';
                    if ($days == 1) {
                        $days_string = ' day';
                    }
                    // + or - days
                    $days_icon = '- ';
                    if ($days_option == 'after') {
                        $days_icon = '+ ';
                    }
                    if ($event_option == 'event_start') {
                        $em_start_date = get_post_meta($args->event->em_id, 'em_start_date', true);
                        $em_start_time = get_post_meta($args->event->em_id, 'em_start_time', true);
                        $start_date = ep_timestamp_to_date($em_start_date);
                        if (!empty($em_start_time)) {
                            $start_date .= ' ' . $em_start_time;
                        }
                        // $start_timestamp = ep_datetime_to_timestamp( $start_date );
                        $start_timestamp = strtotime($start_date);
                        $min_start = strtotime($days_icon . $days . $days_string, $start_timestamp);
                        if ($min_start < ep_get_current_timestamp()) {
                            $show_event_result = 0;
                        }
                    } else if ($event_option == 'event_ends') {
                        $em_end_date = get_post_meta($args->event->em_id, 'em_end_date', true);
                        $em_end_time = get_post_meta($args->event->em_id, 'em_end_time', true);
                        $book_start_date = ep_timestamp_to_date($em_end_date);
                        if (!empty($em_end_time)) {
                            $book_start_date .= ' ' . $em_end_time;
                        }
                        // $book_start_timestamp = ep_datetime_to_timestamp( $book_start_date );
                        $book_start_timestamp = strtotime($book_start_date);
                        $min_start = strtotime($days_icon . $days . $days_string, $book_start_timestamp);
                        if ($min_start < ep_get_current_timestamp()) {
                            $show_event_result = 0;
                        }
                    }
                } else {
                    $show_event_result = 1;
                }
            }
        }

        $event_detail_message_for_recap = ep_get_global_settings('event_detail_message_for_recap');
        if (empty($event_detail_message_for_recap)) {
            $event_detail_message_for_recap = 'Please click here to check results for this event';
        }
        // Result Note
        if ($show_event_result == 1) { ?>
            <div class="ep-box-col-12 ep-mb-3">
                <div class="ep-box-row ep-event-detail-result-container ep-p-2 ep-bg-light-green ep-bg-opacity-10 ep-rounded-1 ep-border-1 ep-border-green ep-align-items-center">
                    <div class="ep-box-col-10 ep-d-flex ep-flex-column ep-my-1">
                        <span class="ep-fw-bold ep-fs-6">
                            <?php esc_html_e('Results', 'eventprime-event-calendar-management'); ?>
                        </span>
                        <span>
                            <?php echo wp_kses_post($event_detail_message_for_recap); ?>
                        </span>
                    </div>
                    <div class="ep-box-col-2 ">
                        <a href="<?php echo esc_url($ep_result_page_link); ?>">
                            <div class="ep-btn ep-btn-green ep-box-w-100 ep-my-0 ep-py-2">
                                <span class="ep-fw-bold ep-text-small">
                                    <?php esc_html_e('View Results', 'eventprime-event-calendar-management'); ?>
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
} ?>