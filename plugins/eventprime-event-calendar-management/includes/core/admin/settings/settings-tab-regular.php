<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Setup', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="regular_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="time_format">
                    <?php esc_html_e( 'Frontend Time Format', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="time_format" id="time_format" class="ep-form-control">
                    <?php 
                    foreach($sub_options['time_format'] as $key => $value){
                        if($key == $global_options->time_format){?>
                            <option value="<?php echo esc_attr($key);?>" selected><?php echo esc_html__($value);?></option><?php
                        }else{?>
                            <option value="<?php echo esc_attr($key);?>"><?php echo esc_html__($value);?></option><?php
                        }
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Time format for frontend event lists and single event pages. Admin area time format is based on your WordPress settings.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="required_booking_attendee_name">
                    <?php esc_html_e( 'Attendee Names Required', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="required_booking_attendee_name" id="required_booking_attendee_name" type="checkbox" value="1" <?php echo isset($global_options->required_booking_attendee_name ) && $global_options->required_booking_attendee_name == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will be asked to enter attendee names for each ticket they have added, during the checkout. This applies to all events. You can also manage attendee fields separately for individual events while editing them.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_0_price_from_frontend">
                    <?php esc_html_e( 'Hide Price For Free Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_0_price_from_frontend" id="hide_0_price_from_frontend" type="checkbox" value="1" <?php echo isset($global_options->hide_0_price_from_frontend ) && $global_options->hide_0_price_from_frontend == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e(sprintf("If enabled, events where all tickets have %s0 fee will be labeled 'Free' in event lists.",ep_currency_symbol()), 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="datepicker_format">
                    <?php esc_html_e( 'User Input Date Format', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="datepicker_format" id="datepicker_format" class="ep-form-control">
                    <option value=""><?php esc_html_e( 'Select Option', 'eventprime-event-calendar-management' );?></option>
                    <?php 
                    foreach( $sub_options['datepicker_format'] as $key => $value) {
                        if( $key == $global_options->datepicker_format ) {?>
                            <option value="<?php echo esc_attr( $key );?>" selected><?php echo esc_html( $value );?></option><?php
                        }else{?>
                            <option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $value );?></option><?php
                        }
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Format of date fields on the frontend where user can input custom values. For example, searching for events within selected dates.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="show_qr_code_on_ticket">
                    <?php esc_html_e( 'Print QR Code On Tickets', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="show_qr_code_on_ticket" id="show_qr_code_on_ticket" type="checkbox" value="1" <?php echo isset($global_options->show_qr_code_on_ticket ) && $global_options->show_qr_code_on_ticket == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, ticket PDF files will have a QR code. Scanning this QR code using a compatible mobile device will take the user directly to their bookings page on this website.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>	

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="checkout_page_timer">
                    <?php esc_html_e( 'Checkout Page Timeout', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <input class="regular-text" name="checkout_page_timer" id="checkout_page_timer" type="number" min="1" value="<?php echo esc_html( $global_options->checkout_page_timer );?>">
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Sets the timer (in minutes) for how long the tickets will be kept on hold for the current user while checkout is in progress. Once the timer ends, tickets are released and the current user will be requested to restart the checkout process and taken back to the event page. Timer, along with depleting bar, is displayed to the users on top of the checkout page during the checkout.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="checkout_page_timer">
                    <?php esc_html_e( 'Font Size', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <input class="regular-text" name="ep_frontend_font_size" id="ep_frontend_font_size" type="number" min="14" value="<?php echo esc_html( $global_options->ep_frontend_font_size );?>">
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Font size for frontend.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="hide_wishlist_icon">
                    <?php esc_html_e( 'Hide wishlist icon', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="hide_wishlist_icon" id="hide_wishlist_icon" type="checkbox" value="1" <?php echo isset($global_options->hide_wishlist_icon ) && $global_options->hide_wishlist_icon == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it will hide the wishlist icon from front end.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="enable_dark_mode">
                    <?php esc_html_e( 'Enable Dark Mode', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="enable_dark_mode" id="enable_dark_mode" type="checkbox" value="1" <?php echo isset($global_options->enable_dark_mode ) && $global_options->enable_dark_mode == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Enabling it to switch to the dark mode.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        
    </tbody>
</table>
