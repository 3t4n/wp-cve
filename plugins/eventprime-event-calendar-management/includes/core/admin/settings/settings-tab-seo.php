<?php $global_options = $options['global'];
$seo_urls_options = (array)$global_options->seo_urls;
$event_page_type_url = ( ! empty( $seo_urls_options['event_page_type_url'] ) ? $seo_urls_options['event_page_type_url'] : '' );
$performer_page_type_url = ( ! empty( $seo_urls_options['performer_page_type_url'] ) ? $seo_urls_options['performer_page_type_url'] : '' );
$organizer_page_type_url = ( ! empty( $seo_urls_options['organizer_page_type_url'] ) ? $seo_urls_options['organizer_page_type_url'] : '' );
$venues_page_type_url = ( ! empty( $seo_urls_options['venues_page_type_url'] ) ? $seo_urls_options['venues_page_type_url'] : '' );
$types_page_type_url = ( ! empty( $seo_urls_options['types_page_type_url'] ) ? $seo_urls_options['types_page_type_url'] : '' );
$sponsor_page_type_url = ( ! empty( $seo_urls_options['sponsor_page_type_url'] ) ? $seo_urls_options['sponsor_page_type_url'] : '' );
$ext_list = ep_list_all_exts();
?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'SEO', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="seo_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="enable_seo_urls">
                    <?php esc_html_e( 'Enable Pretty URLs', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input name="enable_seo_urls" id="enable_seo_urls" onclick="hide_show_google_share_setting(this,'ep_enable_seo_urls_child')" type="checkbox" value="1" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'checked' : '';?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Turning this on will replace current ID based event URLs with custom subdirectory and slug structure. You may wish to redirect your old URLs to new ones if they are already cached by search engines.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="event_page_type_url">
                    <?php esc_html_e( 'Events Subdirectory', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="event_page_type_url" id="event_page_type_url" class="regular-text" type="text" value="<?php echo esc_attr( $event_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single event pages. The title of the event will be automatically added to this to form the complete URL to your event page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="performer_page_type_url">
                    <?php esc_html_e( 'Performers Subdirectory', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="performer_page_type_url" id="performer_page_type_url" class="regular-text" type="text" value="<?php echo esc_attr( $performer_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single performer pages. The name of the performer will be automatically added to this to form the complete URL to your performer page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="organizer_page_type_url">
                    <?php esc_html_e( 'Organizers Subdirectory', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="organizer_page_type_url" id="organizer_page_type_url" class="regular-text" type="text" value="<?php echo esc_attr( $organizer_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single organizer pages. The name of the organizer will be automatically added to this to form the complete URL to your organizer page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="venues_page_type_url">
                    <?php esc_html_e( 'Event Sites Subdirectory', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="venues_page_type_url" id="venues_page_type_url" class="regular-text" type="text" value="<?php echo esc_attr( $venues_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single sites/ venues pages. The name of the site will be automatically added to this to form the complete URL to your site page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
            <th scope="row" class="titledesc">
                <label for="types_page_type_url">
                    <?php esc_html_e( 'Event Types Subdirectory', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input name="types_page_type_url" id="types_page_type_url" class="regular-text" type="text" value="<?php echo esc_attr( $types_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single organizer pages. The name of the organizer will be automatically added to this to form the complete URL to your organizer page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>

        <?php if( in_array( "Event Sponsors", $ext_list ) ) { 
            $ext_details = em_get_more_extension_data( 'Event Sponsors' ); 
            if( $ext_details['is_activate'] ){ ?>
                <!-- sponsors start -->
                <tr valign="top" class="ep_enable_seo_urls_child" style="<?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? '' : 'display:none;';?>">
                    <th scope="row" class="titledesc">
                        <label for="sponsor_page_type_url">
                            <?php esc_html_e( 'Sponsors Subdirectory', 'eventprime-event-calendar-management' );?>
                        </label>
                    </th>
                    <td class="forminp forminp-text">
                        <input name="sponsor_page_type_url" id="sponsor_page_type_url" class="regular-text"  type="text" value="<?php echo esc_attr( $sponsor_page_type_url );?>" <?php echo isset($global_options->enable_seo_urls ) && $global_options->enable_seo_urls == 1 ? 'required' : '';?>>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define subdirectory for single sponsor pages. The name of the sponsor will be automatically added to this to form the complete URL to your sponsor page.', 'eventprime-event-calendar-management' );?></div>
                    </td>
                </tr>
                <!-- sponsors end -->
                <?php 
            } 
        } ?>
    </tbody>
</table>