<div class="wrap aio_admin_wrapper">
    <?php $logo = plugins_url('/images/logo.png', __FILE__); ?>
    <a href="https://codebangers.com" target="_blank"><img src="<?php echo esc_url($logo); ?>" style="width:15%;"></a>
    <h1>All in One Time Clock Lite</h1>
    <div class="about-text">
        <?php echo esc_attr_x('Tracking Employee Time Has Never Been Easier', 'aio-time-clock-lite'); ?>
    </div>
    <h2 class="nav-tab-wrapper">
        <?php settings_errors(); ?>
        <?php
            $new_eprofile_id = null;
            $job = isset($_GET["job"]) ? sanitize_text_field($_GET["job"]) : null;
            $current_user = wp_get_current_user();        
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : "general_settings";
        ?>
        <a href="?page=aio-tc-lite&tab=general_settings" class="nav-tab<?php if ($tab == "general_settings") {
            echo " nav-tab-active";
        } ?>">
            <i class="dashicons dashicons-admin-generic"></i>
            <?php echo esc_attr_x('Settings', 'aio-time-clock-lite'); ?>
        </a>
        <a href="?page=aio-tc-lite&tab=help" class="nav-tab<?php if ($tab == "help") {
            echo " nav-tab-active";
        } ?>">
            <i class="dashicons dashicons-phone"></i>
            <?php echo esc_attr_x('Help', 'aio-time-clock-lite'); ?>
        </a>
        <a href="?page=aio-tc-lite&tab=news" class="nav-tab<?php if ($tab == "news") {
            echo " nav-tab-active";
        } ?>">
            <i class="dashicons dashicons-welcome-widgets-menus"></i>
            <?php echo esc_attr_x('News', 'aio-time-clock-lite'); ?>
        </a>
        <a href="?page=aio-tc-lite&tab=get_pro" class="nav-tab<?php if ($tab == "get_pro") {
            echo " nav-tab-active";
        } ?>">
            <i class="dashicons dashicons-yes"></i>
            <?php echo esc_attr_x('Get Pro', 'aio-time-clock-lite'); ?>
        </a>
    </h2>
    <!--Handle the Tabs-->
    <?php if ($tab == "general_settings") {
        if (get_option('permalink_structure')) {
            //echo 'Permalinks enabled';
        } else {
            ?><div id="setting-error-settings_updated" class="updated settings-error">
            <?php echo esc_attr_x('WARNING!! Permalinks have to be set to anything other than default for the Timeclock to work properly.  We recommend you user the Post Name setting', 'aio-time-clock-lite'); ?>
            <br /><a class="button" href="<?php echo esc_url(get_site_url()); ?>/wp-admin/options-permalink.php">
            <?php echo esc_attr_x('Configure Permalinks', 'aio-time-clock-lite'); ?>
            </a></div>
            <?php 
        }
        if (isset($job)){
            if ($job == "create_timeclock_page") {
                $tc_page = $this->aio_check_tc_shortcode_lite();
                if ($tc_page == null) {
                    $my_post = array(
                        'post_type' => 'page',
                        'post_title' => 'Time Clock',
                        'post_status' => 'publish',
                        'post_content' => '[show_aio_time_clock_lite]',
                        'comment_status' => 'closed',
                        'post_author' => $current_user->ID
                    );
                    // Insert the post into the database
                    $new_post_id = wp_insert_post($my_post);
                }
                ?>
                <div id="setting-error-settings_updated" class="updated settings-error aio-tc-alert">
                    <?php 
                    if ($new_post_id != null) {
                        echo esc_attr_x('TimeClock Page Created Sucessfully', 'aio-time-clock-lite'); ?>
                        <a href="<?php echo esc_url(get_permalink($new_post_id)); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i><?php echo esc_attr_x('View Page', 'aio-time-clock-lite'); ?></a>
                    <?php } else {
                        echo esc_attr_x('Something went wrong.  Timeclock was not created successfully', 'aio-time-clock-lite');
                        if ($tc_page != null) {
                            echo esc_attr_x('You already have a TimeClock page created', 'aio-time-clock-lite'); ?>
                            <a href="<?php echo esc_url(get_permalink($tc_page)); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i><?php echo esc_attr_x('View Page', 'aio-time-clock-lite'); ?></a>
                            <?php 
                        }
                    }
                    ?>
                </div>
                <?php 
            }
            if ($job == "create_eprofile_page") {
                $eprofile_page = $this->check_eprofile_shortcode_lite();
                if ($eprofile_page == null) {
                    $my_post = array(
                        'post_type' => 'page',
                        'post_title' => 'Employee Profile',
                        'post_status' => 'publish',
                        'post_content' => '[show_aio_employee_profile_lite]',
                        'comment_status' => 'closed',
                        'post_author' => 1
                    );
                    // Insert the post into the database
                    $new_eprofile_id = wp_insert_post($my_post);
                }
                ?>
                <div id="setting-error-settings_updated" class="updated settings-error aio-tc-alert">
                    <?php 
                    if ($new_eprofile_id != null) {
                        echo esc_attr_x('Employee Profile Page Created Sucessfully', 'aio-time-clock-lite'); ?>
                        <a href="<?php echo esc_url(get_permalink($new_eprofile_id)); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i> <?php echo esc_attr_x('View Profile', 'aio-time-clock-lite'); ?></a>
                        <?php 
                    } else {
                        echo esc_attr_x('Something went wrong.  Employee Profile Page was not created successfully', 'aio-time-clock-lite');
                        if ($eprofile_page != null) {
                            echo esc_attr_x('You already have a Employee Profile page created', 'aio-time-clock-lite'); ?>
                            <a href="<?php echo esc_url(get_permalink($eprofile_page)); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i> <?php echo esc_attr_x('View Profile', 'aio-time-clock-lite'); ?></a>
                            <?php 
                        }
                    }
                    ?>
                </div>
                <?php 
            }
        }
        ?>
        <h3><?php echo esc_attr_x('General Settings', 'aio-time-clock-lite'); ?></h3>
        <form method="post" action="options.php">
            <?php settings_fields('nertworks-timeclock-settings-group'); ?>
            <?php do_settings_sections('nertworks-timeclock-settings-group');
            $options = get_option('nertworks-timeclock-settings-group');
            ?>
            <table class="widefat fixed" cellspacing="0">
                <tr class="alternate">
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Company Name', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <input type="text" name="aio_company_name" value="<?php echo esc_attr(get_option('aio_company_name')); ?>" placeholder="<?php echo esc_attr_x('Enter Company Name', 'aio-time-clock-lite'); ?>"/>
                    </td>
                    <td>
                        <i><?php echo esc_attr_x('The company name associated with this Account', 'aio-time-clock-lite'); ?></i>
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Enable Employee Wage Management', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <input type="radio" name="aio_wage_manage" value="enabled" <?php if (get_option('aio_wage_manage') == "enabled") { echo "checked"; } ?> /><?php echo esc_attr_x('Enabled', 'aio-time-clock-lite'); ?>
                        <input type="radio" name="aio_wage_manage" value="disabled" <?php if (get_option('aio_wage_manage') == "disabled" || get_option('aio_wage_manage') == "") { echo "checked"; } ?>/><?php echo esc_attr_x('Disabled', 'aio-time-clock-lite'); ?>
                    </td>
                    <td>
                        <i><?php echo esc_attr_x('This allows you to track wages as well as time. Making your reports and graphs much more valuable', 'aio-time-clock-lite'); ?></i>
                    </td>
                </tr>
                <tr class="alternate">
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Time Clock', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <?php
                        $tc_page = $this->aio_check_tc_shortcode_lite();
                        if ($tc_page != null) { ?>
                            <a href="<?php echo esc_url(get_permalink($tc_page)); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i> <?php echo esc_attr_x('View Page', 'aio-time-clock-lite'); ?></a>
                            <a href="/wp-admin/post.php?post=<?php echo esc_attr($tc_page); ?>&action=edit" class="button small_button" target="_blank"><i class="dashicons dashicons-edit"></i><?php echo esc_attr_x('Edit Page', 'aio-time-clock-lite'); ?></a>
                        <?php } else { ?>
                            <?php echo esc_attr_x('Time Clock page not found. Would you like to create one', 'aio-time-clock-lite'); ?>?<a href="<?php echo esc_url(admin_url('?page=aio-tc-lite&tab=general_settings&job=create_timeclock_page')); ?>" class="button small_button"><span class="dashicons dashicons-plus vmiddle"></span></a>
                            <?php 
                        }
                        ?>
                    </td>
                    <td>
                        <i><?php echo esc_attr_x('Where employees can clock in and out of their shifts', 'aio-time-clock-lite'); ?>.</i>
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Employee Profile', 'aio-time-clock-lite');?>: </strong></th>
                    <td>
                        <?php
                        $eprofile_page = $this->check_eprofile_shortcode_lite();
                        if ($eprofile_page != null) { ?>
                            <a href="<?php echo esc_url(get_permalink(intval($eprofile_page))); ?>" class="button small_button" target="_blank"><i class="dashicons dashicons-search"></i> <?php echo esc_attr_x('View Page', 'aio-time-clock-lite'); ?></a>
                            <a href="/wp-admin/post.php?post=<?php echo intval($eprofile_page); ?>&action=edit" class="button small_button" target="_blank"><i class="dashicons dashicons-edit"></i> <?php echo esc_attr_x('Edit Page', 'aio-time-clock-lite'); ?></a>
                        <?php 
                        } else { 
                            ?>
                            <?php echo esc_attr_x('Employee Profile page not found. Would you like to create one', 'aio-time-clock-lite'); ?>? <a href="<?php echo esc_url(admin_url('?page=aio-tc-lite&tab=general_settings&job=create_eprofile_page')); ?>" class="button small_button"><span class="dashicons dashicons-plus vmiddle"></span></a>                    
                            <?php 
                        }
                        ?>
                    </td>
                    <td>
                        <i><?php echo esc_attr_x('Profile where employees can access their shifts.  Shortcode', 'aio-time-clock-lite'); ?>: [show_aio_employee_profile]</i>
                    </td>
                </tr>
                <tr class="alternate">
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Quick Pick Time Clock', 'aio-time-clock-lite');?>: </strong></th>
                    <td>
                        <?php echo esc_attr_x('Available in', 'aio-time-clock-lite').' <a href="https://codebangers.com/product/all-in-one-time-clock/" target="_blank" class="button">' . esc_attr_x('Pro', 'aio-time-clock-lite') . '</a>'; ?>
                    </td>
                    <td>
                        <i><?php echo esc_attr_x('List style employees list page that allows users to quickly clock in and out of shifts using a pin number', 'aio-time-clock-lite');?></i>
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Redirect Employees to Time Clock Page', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <input type="radio" name="aio_timeclock_redirect_employees" value="enabled" <?php if (get_option('aio_timeclock_redirect_employees') == "enabled") { echo "checked"; } ?> /><?php echo esc_attr_x('Enabled', 'aio-time-clock-lite'); ?>
                        <input type="radio" name="aio_timeclock_redirect_employees" value="disabled" <?php if (get_option('aio_timeclock_redirect_employees') == "disabled" || get_option('aio_timeclock_redirect_employees') == "") { echo "checked"; } ?>/><?php echo esc_attr_x('Disabled', 'aio-time-clock-lite'); ?>
                    </td>
                    <td>
                        <i>
                            <?php echo esc_attr_x('If a user with the role \'Employee\' logs in. They will be redirected to the time clock page', 'aio-time-clock-lite'); ?>
                        </i>
                    </td>
                </tr>            
                <tr class="alternate">
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Show Employee Avatar', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <?php echo esc_attr_x('Available in', 'aio-time-clock-lite').' <a href="https://codebangers.com/product/all-in-one-time-clock/" target="_blank" class="button">' . esc_attr_x('Pro', 'aio-time-clock-lite') . '</a>'; ?>
                    </td>
                    <td>
                        <i>
                            <?php echo esc_attr_x('When enabled, avatar will display on time clock page', 'aio-time-clock-lite'); ?>
                        </i>
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('TimeZone', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <select name="aio_timeclock_time_zone">                            
                            <?php
                            $tzlist = $this->getTimeZoneListLite();
                            foreach ($tzlist as $tz => $label) {
                                $select = '';
                                if (get_option("aio_timeclock_time_zone") == $label) {
                                    $select = "selected";
                                }
                                ?>
                                <option value="<?php echo esc_attr($label); ?>" <?php echo esc_attr($select); ?>><?php echo esc_attr($label); ?></option>
                                <?php 
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <i>
                            <?php echo esc_attr_x('This allows you to track wages as well as time. Making your reports and graphs much more valuable', 'aio-time-clock-lite'); ?>
                        </i>
                    </td>
                </tr>
                <tr class="alternate">
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Enable Location', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <?php echo esc_attr_x('Available in', 'aio-time-clock-lite').' <a href="https://codebangers.com/product/all-in-one-time-clock/" target="_blank" class="button">' . esc_attr_x('Pro', 'aio-time-clock-lite') . '</a>'; ?>
                    </td>
                    <td>
                        <i>
                            <?php echo esc_attr_x('When enabled, employees can select the location they are clocking in at', 'aio-time-clock-lite'); ?>.
                        </i>
                    </td>
                </tr>
                <tr>
                    <th scope="col" class="manage-column column-columnname"><strong><?php echo esc_attr_x('Custom Roles', 'aio-time-clock-lite'); ?>: </strong></th>
                    <td>
                        <?php echo esc_attr_x('Available in', 'aio-time-clock-lite').' <a href="https://codebangers.com/product/all-in-one-time-clock/" target="_blank" class="button">' . esc_attr_x('Pro', 'aio-time-clock-lite') . '</a>'; ?>
                    </td>
                    <td>
                        <i>
                            <?php echo esc_attr_x('Add your own custom roles to have access to the time clock.', 'aio-time-clock-lite'); ?>
                        </i>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
        <?php
    }
    if ($tab == "help") {
        ?>
            <h2><?php echo esc_attr_x('Need Support', 'aio-time-clock-lite'); ?>?</h2>
            <hr>
            <img src="<?php echo plugins_url('/images/support.jpg', __FILE__); ?>">
            <p><?php echo esc_attr_x('We got you.  Visit the link below and we will get you on your way', 'aio-time-clock-lite'); ?>  </p>
            <hr>
            <p><a href="https://codebangers.com/support/" class="button-primary" target="_blank"><?php echo esc_attr_x('Get Support', 'aio-time-clock-lite'); ?></a></p>
        <?php 
    }
    if ($tab == "news") {
        include("aio-news.php");
    }
    if ($tab == "get_pro") {        
        require_once("aio-get-pro.php");
    }
    ?>
</div>