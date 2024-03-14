<?php $global_options = $options['global'];?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Third-Party', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="external_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="gmap_api_key">
                    <?php esc_html_e( 'Google Maps API Key', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label>
                    <input name="gmap_api_key" id="gmap_api_key" class="regular-text" type="text" value="<?php echo isset($global_options->gmap_api_key ) ? $global_options->gmap_api_key : '';?>">    
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php _e( sprintf('EventPrime can display venue location, weather forecast, directions etc. to the visitors if Google Maps API key is configured. You can generate one from your Google Maps account.<a href="%s" target="__">Learn more about Google Maps API keys</a>','https://developers.google.com/maps/documentation/javascript/get-api-key'), 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="gmap_api_key">
                    <?php esc_html_e( 'Allow Sharing Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="social_sharing" id="social_sharing" type="checkbox" value="1" <?php echo isset($global_options->social_sharing ) && $global_options->social_sharing == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, a new share icon will appear on the single event page. Clicking it will allow users to share events on social platforms like Facebook, Twitter etc.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <!-- <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="gcal_sharing">
                    <?php esc_html_e( 'Allow Adding to Google Calendar', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="gcal_sharing" id="gcal_sharing" onclick="hide_show_google_share_setting(this,'ep_gcal_sharing_child')" type="checkbox" value="1" <?php echo isset($global_options->gcal_sharing ) && $global_options->gcal_sharing == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, users will be able to add events to their Google Calendars from single event and bookings page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr> -->
        <tr valign="top" class="ep_gcal_sharing_child" style="<?php echo isset($global_options->gcal_sharing ) && $global_options->gcal_sharing == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="google_cal_client_id">
                    <?php esc_html_e( 'Google Calendar Client ID*', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="google_cal_client_id" id="google_cal_client_id" type="text" value="<?php echo isset($global_options->google_cal_client_id) ? $global_options->google_cal_client_id : '';?>" <?php echo isset($global_options->google_cal_client_id ) && $global_options->google_cal_client_id == 1 ? 'required' : '';?>>
            </td>
        </tr>
        <tr valign="top" class="ep_gcal_sharing_child" style="<?php echo isset($global_options->gcal_sharing ) && $global_options->gcal_sharing == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="google_cal_api_key">
                    <?php esc_html_e( 'Google Calendar API Key*', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="google_cal_api_key" id="google_cal_api_key" type="text" value="<?php echo isset($global_options->google_cal_api_key) ? $global_options->google_cal_api_key : '';?>" <?php echo isset($global_options->google_cal_api_key ) && $global_options->google_cal_api_key == 1 ? 'required' : '';?>>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="gcal_sharing">
                    <?php esc_html_e( 'Enable Google reCAPTCHA', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="google_recaptcha" id="google_recaptcha" onclick="hide_show_google_share_setting(this,'ep_google_recaptcha_child')" type="checkbox" value="1" <?php echo isset($global_options->google_recaptcha ) && $global_options->google_recaptcha == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php _e( sprintf('If enabled, users will be asked to verify reCAPTCHA while filling login and registration forms. <a href="%s" target="__">Generate reCAPTCHA key</a>','https://www.google.com/recaptcha/admin/create'), 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_google_recaptcha_child" style="<?php echo isset($global_options->google_recaptcha ) && $global_options->google_recaptcha == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="google_recaptcha_site_key">
                    <?php esc_html_e( 'reCAPTCHA Site Key*', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="google_recaptcha_site_key" class="regular-text" id="google_recaptcha_site_key" type="text" value="<?php echo isset($global_options->google_recaptcha_site_key) ? $global_options->google_recaptcha_site_key : '';?>" <?php echo isset($global_options->google_recaptcha_site_key ) && $global_options->google_recaptcha_site_key == 1 ? 'required' : '';?>>
            </td>
        </tr>
        <tr valign="top" class="ep_google_recaptcha_child" style="<?php echo isset($global_options->google_recaptcha ) && $global_options->google_recaptcha == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="google_recaptcha_secret_key">
                    <?php esc_html_e( 'reCAPTCHA Secret Key*', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="google_recaptcha_secret_key" class="regular-text" id="google_recaptcha_secret_key" type="text" value="<?php echo isset($global_options->google_recaptcha_secret_key) ? $global_options->google_recaptcha_secret_key : '';?>" <?php echo isset($global_options->google_recaptcha_secret_key ) && $global_options->google_recaptcha_secret_key == 1 ? 'required' : '';?>>
            </td>
        </tr>
    </tbody>
</table>