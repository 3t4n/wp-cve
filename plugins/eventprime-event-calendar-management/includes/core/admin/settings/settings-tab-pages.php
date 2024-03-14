<?php 
$global_options = $options['global'];
$pages = $options['pages'];
?>
<div class="ep-page-tab-content">
    <h2><?php esc_html_e( 'Pages', 'eventprime-event-calendar-management' );?></h2>
    <div class="ep-setting-tab-desc"><?php esc_html_e('These pages are used to serve corresponding context-based views to the visitors/ users. Each page is essential for a unified user experience, without which the items they represent would not work properly. On first installation, these pages are created automatically with the required shortcodes pasted inside them. If a page is reassigned, make sure it has the required shortcode.','eventprime-event-calendar-management');?></div>
    <input type="hidden" name="em_setting_type" value="page_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_performers_page">
                    <?php esc_html_e( 'Performers Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="performers_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->performers_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_venues_page">
                    <?php esc_html_e( 'Venues Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="venues_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->venues_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_events_page">
                    <?php esc_html_e( 'Events Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="events_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->events_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_booking_page">
                    <?php esc_html_e( 'Bookings Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="booking_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->booking_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_profile_page">
                    <?php esc_html_e( 'Profile Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="profile_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->profile_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_event_types">
                    <?php esc_html_e( 'Event-Types Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="event_types" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->event_types == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_event_submit_form">
                    <?php esc_html_e( 'Submit Event Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="event_submit_form" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->event_submit_form == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="em_booking_details_page">
                    <?php esc_html_e( 'Booking Details Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="booking_details_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->booking_details_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="event_organizers">
                    <?php esc_html_e( 'Event Organizers Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="event_organizers" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->event_organizers == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="login_page">
                    <?php esc_html_e( 'Login Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="login_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->login_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="register_page">
                    <?php esc_html_e( 'Registration Page', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="register_page" required class="ep-form-control">
                    <?php 
                    if(count($pages)){
                        foreach($pages as $page_id => $page_title){
                            if($global_options->register_page == $page_id){
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>" selected><?php echo esc_html($page_title);?></option>
                                <?php
                            }else{
                                ?>
                                <option value="<?php echo esc_attr($page_id);?>"><?php echo esc_html($page_title);?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    
                </select>
            </td>
        </tr>
        <?php echo do_action('ep_pages_global_setting', $pages, $global_options);?>
    </tbody>
</table>