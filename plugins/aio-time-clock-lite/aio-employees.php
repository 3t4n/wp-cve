<div class="wrap aio_admin_wrapper">
    <?php $logo = plugins_url('/images/logo.png', __FILE__);?>
    <a href="https://codebangers.com" target="_blank"><img src="<?php echo esc_url($logo); ?>" style="width:15%;"></a>
    <hr>
    <div>
        <h1 style="padding-left: 10px;"><?php echo esc_attr_x('Employees', 'aio-time-clock-lite'); ?></h1>
        <hr>
        <div style="padding-top:10px;padding-bottom:10px;padding-left: 10px;">
        <a href="<?php echo esc_url(get_admin_url()); ?>edit-tags.php?taxonomy=department" class="button small_button"><span class="dashicons dashicons-admin-generic vmiddle"></span> <?php echo esc_attr_x('Manage Departments', 'aio-time-clock-lite'); ?></a>
        <a href="<?php echo esc_url(get_admin_url()); ?>user-new.php" class="button small_button"><span class="dashicons dashicons-plus-alt vmiddle"></span> <?php echo esc_attr_x('Add Employee', 'aio-time-clock-lite'); ?></a>
            </div>
        <hr>
    </div>
    <table class="widefat fixed aio_datatable display" cellspacing="0">
    <thead>
    
        <tr >
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Name', 'aio-time-clock-lite'); ?></strong></th>
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Department', 'aio-time-clock-lite'); ?></strong></th>
            <?php
            if (get_option('aio_wage_manage') == "enabled") {
                ?><th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Wage', 'aio-time-clock-lite'); ?></strong></th><?php 
            }
            else{
                ?><th class="manage-column column-columnname" scope="col"></th><?php 
            }
            ?>
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Options', 'aio-time-clock-lite'); ?></strong></th>
        </tr>
    </thead>
    <tfoot>
        <tr >
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Name', 'aio-time-clock-lite'); ?></strong></th>
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Department', 'aio-time-clock-lite'); ?></strong></th>
            <?php
            if (get_option('aio_wage_manage') == "enabled") {
                ?><th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Wage', 'aio-time-clock-lite'); ?></strong></th><?php 
            }
            else{
                ?><th class="manage-column column-columnname" scope="col"></th><?php 
            }
            ?>
            <th class="manage-column column-columnname" scope="col"><strong><?php echo esc_attr_x('Options', 'aio-time-clock-lite'); ?></strong></th>
        </tr>
    </tfoot>
    <tbody>    
        <?php
        $count = 0;
        $users = $this->getUsers();
        foreach($users as $user){
            $employee_id = isset($user["employee_id"]) ? intval($user["employee_id"]) : 0;
            $alternate_class="";
            if ($count % 2 == 0) {
                $alternate_class = "alternate";
            }
            ?>
            <tr class="<?php echo esc_attr($alternate_class); ?>">
                <th scope="row"><?php echo esc_attr($this->getEmployeeName($employee_id)); ?></th>
                <td class="column-columnname">
                    <?php 
                        echo esc_attr($this->getDepartment($employee_id));
                    ?>
                </td>
                <?php 
                if (get_option('aio_wage_manage') == "enabled") {
                    ?>
                    <td class="column-columnname">
                        <?php 
                        $wage = sanitize_text_field(get_the_author_meta('employee_wage', $employee_id));
                            if (isset($wage)) {
                                echo esc_attr($wage);
                            } 
                        ?>
                    </td>
                    <?php 
                }
                else{
                    ?><td></td><?php 
                }
                ?>
                <td class="column-columnname">
                        <a href="<?php echo esc_url(get_edit_user_link($employee_id)); ?>" class="button" title="<?php echo esc_attr_x('Edit Employee', 'aio-time-clock-lite'); ?>"><span class="dashicons dashicons-admin-generic vmiddle"></span></a>
                    </td>
            </tr>
            <?php 
            $count++;
        }
        ?>
        </tbody>
    </table>
    <div class="totalRowDiv">
        <hr>
        <strong style="padding-left: 10px;"><?php echo esc_attr_x('Total Employees', 'aio-time-clock-lite');?>: </strong>
        <?php echo esc_attr($count); ?> 
        <hr>         
    </div>
</div>