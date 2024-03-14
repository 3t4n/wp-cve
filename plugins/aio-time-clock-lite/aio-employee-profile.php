<?php
global $wordpress;
global $wpdb;
global $current_user;
$timeclock_button = null;
$count = 0;
$shift_total_time = "00:00";
if (is_user_logged_in() == true) {
    wp_get_current_user();
    $tc_page = $this->aio_check_tc_shortcode_lite();
    ?>
    <table>
    <?php 
    if ($tc_page != null){
        echo '<a class="button btn" href="' . esc_url(get_permalink($tc_page)) . '">' . esc_attr_x('Back to Time Clock', 'aio-time-clock-lite') . '</a>';
    }
    ?>
    <!--<input type="text" id="employeeProfileInput" onkeyup="employeProfileSearch()" placeholder="Filter shifts..">-->

    <table id="employeeProfileTable">
    <tr class="header">
        <th style="width:20%;"><?php echo esc_attr_x('Name', 'aio-time-clock-lite'); ?></th>
        <th style="width:30%;"><?php echo esc_attr_x('Clock In', 'aio-time-clock-lite'); ?></th>
        <th style="width:30%;"><?php echo esc_attr_x('Clock Out', 'aio-time-clock-lite'); ?></th>
        <th style="width:20%;"><?php echo esc_attr_x('Total', 'aio-time-clock-lite'); ?></th>
    </tr>
    <?php $loop = new WP_Query(array('post_type' => 'shift', 'author' => $current_user->ID, 'posts_per_page' => -1));?>
        <?php while ($loop->have_posts()): $loop->the_post(); ?>
                <?php
                    $custom = get_post_custom($loop->post->ID);
                    $employee_clock_in_time = isset($custom["employee_clock_in_time"][0]) ? sanitize_text_field($custom["employee_clock_in_time"][0]) : null;
                    $employee_clock_out_time = isset($custom["employee_clock_out_time"][0]) ? sanitize_text_field($custom["employee_clock_out_time"][0]) : null;
                    $shift_sum = '00:00';
                    $author_id = intval(get_the_author_meta('ID'));
                    ?>
                    <tr valign="top">
                        <td scope="row"><?php echo esc_attr($this->getEmployeeName($author_id)); ?></td>
                        <td>
                            <?php
                            if ($employee_clock_in_time != null) {
                                $newDate = $this->cleanDate($employee_clock_in_time);
                                echo esc_attr($newDate);
                            } else {
                                echo esc_attr_x('Clock In Empty', 'aio-time-clock-lite');
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if ($employee_clock_out_time != null){
                                    $outDate = $this->cleanDate($employee_clock_out_time);
                                        echo esc_attr($outDate);
                                } 
                                else {
                                        echo esc_attr_x('Clock Out Empty', 'aio-time-clock-lite');
                                }
                            ?>
                        </td>
                        <td>
                            <?php 
                                if ($employee_clock_in_time != null && $employee_clock_out_time != null) {
                                    $shift_sum = $this->secondsToTime($this->dateDifference($employee_clock_in_time, $employee_clock_out_time));
                                    $shift_total_time = $this->addTwoTimes($shift_total_time, $shift_sum);
                                    echo esc_attr($shift_sum);
                                }
                            ?>
                        </td>
                    </tr>
                <?php $count++;
            endwhile;
        ?>
        <tr><td></td><td></td><td><strong><?php echo esc_attr_x('Total Shift Time', 'aio-time-clock-lite'); ?>:</strong> </td><td><?php echo esc_attr($shift_total_time); ?></td></tr>
    </table>

    <style>
    #employeeProfileInput {
    background-image: url(<?php echo plugins_url( '/images/search.png', __FILE__ ); ?>); /* Add a search icon to input */
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
    }

    #employeeProfileTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 18px; /* Increase font-size */
    }

    #employeeProfileTable th, #employeeProfileTable td {
    text-align: left; /* Left-align text */
    padding: 12px; /* Add padding */
    }

    #employeeProfileTable tr {
    /* Add a bottom border to all table rows */
    border-bottom: 1px solid #ddd; 
    }

    #employeeProfileTable tr.header, #employeeProfileTable tr:hover {
    /* Add a grey background color to the table header and on hover */
    background-color: #f1f1f1;
    }
    </style>
    <?php 
}
else{
    echo esc_attr_x("You must be logged in to view the Employee Profile Page", 'aio-time-clock-lite');
}
?>