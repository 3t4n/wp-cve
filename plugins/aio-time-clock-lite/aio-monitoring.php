<div class="wrap aio_admin_wrapper">
    <?php $logo = plugins_url('/images/logo.png', __FILE__);?>
    <a href="https://codebangers.com" target="_blank"><img src="<?php echo esc_url($logo); ?>" style="width:15%;"></a>
    <hr>
    <div >
    <h1 style="padding-left: 10px;"><?php echo esc_attr_x('Employees Currently Working', 'aio-time-clock-lite'); ?></h1>
    <hr>
    </div>
    <table class="widefat fixed aio_datatable display" cellspacing="0">
        <thead>
            <tr>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Employee Name', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Department', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Clock In Time', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('IP Address', 'aio-time-clock-lite'); ?></strong></th>
                <?php do_action("aio_new_report_column_heading"); ?>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Status', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Options', 'aio-time-clock-lite'); ?></strong></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Employee Name', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Department', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Clock In Time', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('IP Address', 'aio-time-clock-lite'); ?></strong></th>
                <?php do_action("aio_new_report_column_heading"); ?>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Status', 'aio-time-clock-lite'); ?></strong></th>
                <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Options', 'aio-time-clock-lite'); ?></strong></th>
            </tr>
            
        </tfoot>
        <tbody>
        <?php $count = 0; ?>
        <?php $loop = new WP_Query(array('post_type' => 'shift', 'posts_per_page' => -1)); ?>

        <?php while ($loop->have_posts()) : $loop->the_post(); ?>
            <?php
            $author_id = get_the_author_meta('ID');
            $custom = get_post_custom($loop->post->ID);
            $employee_clock_in_time = isset($custom["employee_clock_in_time"][0]) ? sanitize_text_field($custom["employee_clock_in_time"][0]) : null;
            $employee_clock_out_time = isset($custom["employee_clock_out_time"][0]) ? sanitize_text_field($custom["employee_clock_out_time"][0]) : null; 
            $ip_address_in = isset($custom["ip_address_in"][0]) ? sanitize_text_field($custom["ip_address_in"][0]) : null; 
            $alternate_class="";            
            if ($count % 2 == 0) {
                $alternate_class = "alternate";
            }
            if ($employee_clock_out_time == null || $employee_clock_out_time == ''){
                ?>
                <tr class="<?php echo esc_attr($alternate_class); ?>">
                    <td><?php echo esc_attr($this->getEmployeeName($author_id)); ?></td>
                    <td scope="row">
                        <?php 
                        echo esc_attr($this->getDepartmentColumn($author_id));
                        ?>
                    </td>
                    <td>
                        <?php 
                            if ($employee_clock_in_time != null && $this->isValidDate($employee_clock_in_time)) {
                                $newDate = date($this->getDateTimeFormat(), strtotime($employee_clock_in_time));                            
                                echo esc_attr($newDate);
                            }
                        ?>
                    </td>
                    <td>
                        <?php echo esc_attr($ip_address_in); ?>
                    </td>                
                    <?php do_action("aio_new_report_column"); ?>
                    <td>            
                        <?php echo $this->getMonitoringShiftColumn($author_id); ?>
                    </td>
                    <td>
                        <a href="post.php?post=<?php echo intval($loop->post->ID); ?>&action=edit" class="button" title="<?php echo esc_attr_x('Edit Shift', 'aio-time-clock-lite'); ?>"><span class="dashicons dashicons-admin-generic vmiddle"></span></a>
                    </td>
                </tr>
                <?php $count++; 
            }
            ?>
        <?php endwhile;
            wp_reset_query(); 
        ?>
    </tbody>
    </table>
    <div class="totalRowDiv">
        <hr>
        <strong><?php echo esc_attr_x('Total Clocked In', 'aio-time-clock-lite'); ?>: </strong><?php echo esc_attr($count); ?>
        <hr>
    </div>
</div>