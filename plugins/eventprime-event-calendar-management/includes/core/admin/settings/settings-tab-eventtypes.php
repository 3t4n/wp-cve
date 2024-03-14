<div class="ep-setting-tab-content">
    <?php 
        $event_types_text = ep_global_settings_button_title('Event-Types');
        $event_type_text = ep_global_settings_button_title('Event-Type');
    ?>
    <h2><?php echo esc_html( $event_type_text ).' '.esc_html__( ' Listing View Settings', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="event_type_settings">
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="type_display_view">
                    <?php esc_html_e( 'Style', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="type_display_view" id="ep_settings_front_display_view" class="ep-form-control">
                    <?php 
                    foreach( $sub_options['front_view_list_styles'] as $key => $view_name ){ ?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->type_display_view == $key ) { echo 'selected="selected"'; } ?>>
                            <?php echo esc_html( $view_name );?>
                        </option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Event-types can be listed in different styles. Choose what suits you best.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top" id="ep_settings_card_background" <?php if( $options['global']->type_display_view != 'box' && $options['global']->type_display_view != 'colored_grid' ) { echo 'style="display:none;"'; } ?>>
            <th scope="row" class="titledesc">
                <label for="type_box_color">
                    <?php esc_html_e( 'Background Colors', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <?php foreach( $options['global']->type_box_color as $key => $color ){?>
                    <input type="text" data-jscolor="{}" name="type_box_color[<?php echo $key;?>]" id="performer_box_color_<?php echo $key;?>" value="<?php echo esc_attr( $options['global']->type_box_color[$key] );?>"><?php
                }?>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'In this view 4 background colors repeat themselves to create a colorful grid of event type cards. Here, you can set those 4 colors which fit inside your frontend theme.', 'eventprime-event-calendar-management' );?></div>    
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="type_limit">
                    <?php esc_html_e( 'No. of Items to Fetch', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="number" min="0" name="type_limit" class="regular-text" id="type_limit" value="<?php echo esc_attr( $options['global']->type_limit );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'The number of items to fetch before \'Load More\' or pagination appears.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="type_no_of_columns">
                    <?php esc_html_e( 'Grid Columns', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="type_no_of_columns" id="type_no_of_columns" class="ep-form-control">
                    <option value="1" <?php if( $options['global']->type_no_of_columns == 1 ){ echo 'selected'; } ?> >1</option>
                    <option value="2" <?php if( $options['global']->type_no_of_columns == 2 ){ echo 'selected'; } ?> >2</option>
                    <option value="3" <?php if( $options['global']->type_no_of_columns == 3 ){ echo 'selected'; } ?> >3</option>
                    <option value="4" <?php if( $options['global']->type_no_of_columns == 4 ){ echo 'selected'; } ?>>4</option>
                </select>  
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Define the number of columns in grid views. If your theme offers narrow content area, please choose 1 or 2, or the view may appear too cramped.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="type_load_more">
                    <?php esc_html_e( '\'Load More\' Button', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="type_load_more"  id="type_load_more" <?php if( $options['global']->type_load_more == 1 ){ echo 'checked="checked"'; }?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, replaces pagination with a \'Load More\' button. The button uses AJAX to fetch items without refreshing the page.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="type_search">
                       <?php esc_html_e( 'Allow Searching', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="type_search" " id="type_search" <?php if( $options['global']->type_search == 1 ){ echo 'checked="checked"'; }?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, a search box will appear above the listings. Users can perform keyword based search using it.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th colspan="2">
            <em>Shortcode: </em><code>[em_event_types display_style="grid/colored_grid/rows" limit="{NUMBER}" cols="{NUMBER}" load_more="0 or 1" search="0 or 1" featured="0 or 1" popular="0 or 1"]</code>
            </th>
        </tr>
    </tbody>
</table>
<div class="ep-setting-tab-content">
    <h2><?php echo esc_html__( 'Single', 'eventprime-event-calendar-management' ) .' '. esc_html( $event_type_text ). ' '.esc_html__( 'View Settings', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_show_events">
                    <?php esc_html_e( 'Show Related Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="single_type_show_events" onclick="ep_frontend_view_child_hide_show(this,'ep_frontend_view_child')" id="single_type_show_events" <?php if( esc_attr( $options['global']->single_type_show_events == 1 ) ){ echo 'checked="checked"'; }?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, events related to this Event-type will be displayed below the details.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
    </tbody>
</table>
<table id="ep_frontend_view_child" class="form-table" style="<?php if( esc_attr( $options['global']->single_type_show_events == 1 ) ){ echo 'display:block;"'; }else{ echo 'display:none;';}?>">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_display_view">
                    <?php esc_html_e( 'Related Events View', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="single_type_event_display_view" class="ep-form-control">
                    <?php 
                    foreach( $sub_options['front_view_event_styles'] as $key => $view_name ){ ?>
                        <option value="<?php echo esc_attr( $key );?>" <?php if( $options['global']->single_type_event_display_view == $key ) { echo 'selected="selected"'; } ?>>
                            <?php echo esc_html( $view_name );?>
                        </option><?php
                    }?>
                </select>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Choose how you wish to display the events in related events section.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_limit">
                    <?php esc_html_e( 'Limit', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <input type="number" min="0" name="single_type_event_limit" class="regular-text" id="single_type_event_limit" value="<?php echo esc_attr( $options['global']->single_type_event_limit );?>">
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Number of events to display in this section.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_column">
                    <?php esc_html_e( 'Columns', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="single_type_event_column" id="single_type_event_column" class="ep-form-control">
                    <option value="1" <?php if( $options['global']->single_type_event_column == 1 ){ echo 'selected'; } ?> >1</option>
                    <option value="2" <?php if( $options['global']->single_type_event_column == 2 ){ echo 'selected'; } ?> >2</option>
                    <option value="3" <?php if( $options['global']->single_type_event_column == 3 ){ echo 'selected'; } ?> >3</option>
                    <option value="4" <?php if( $options['global']->single_type_event_column == 4 ){ echo 'selected'; } ?>>4</option>
                </select> 
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Number of columns in Square Grid view.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_order">
                    <?php esc_html_e( 'Order', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="single_type_event_order" id="single_type_event_order" class="ep-form-control">
                    <option value="asc" <?php if( $options['global']->single_type_event_order == 'asc' ){ echo 'selected'; } ?> ><?php esc_html_e('Ascending','eventprime-event-calendar-management');?></option>
                    <option value="desc" <?php if( $options['global']->single_type_event_order == 'desc' ){ echo 'selected'; } ?> ><?php esc_html_e('Descending','eventprime-event-calendar-management');?></option>
                </select> 
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Number of columns in Square Grid view.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_orderby">
                    <?php esc_html_e( 'OrderBy', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <select name="single_type_event_orderby" id="single_type_event_orderby" class="ep-form-control">
                    <option value="em_start_date_time" <?php if( $options['global']->single_type_event_orderby == 'em_start_date_time' ){ echo 'selected'; } ?> ><?php esc_html_e('Event Start Date','eventprime-event-calendar-management');?></option>
                    <option value="em_end_date_time" <?php if( $options['global']->single_type_event_orderby == 'em_end_date_time' ){ echo 'selected'; } ?> ><?php esc_html_e('Event End Date','eventprime-event-calendar-management');?></option>
                    <option value="ID" <?php if( $options['global']->single_type_event_orderby == 'ID' ){ echo 'selected'; } ?> ><?php esc_html_e('Event ID','eventprime-event-calendar-management');?></option>
                    <option value="title" <?php if( $options['global']->single_type_event_orderby == 'title' ){ echo 'selected'; } ?>><?php esc_html_e('Title','eventprime-event-calendar-management');?></option>
                </select> 
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'Number of columns in Square Grid view.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_event_load_more">
                    <?php esc_html_e( 'Load More', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="single_type_event_load_more"  id="single_type_event_load_more" <?php if( $options['global']->single_type_event_load_more == 1 ){ echo 'checked="checked"'; }?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, displays a \'Load More\' button below the related events section, clicking on which will fetch more related events using AJAX.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label for="single_type_hide_past_events">
                    <?php esc_html_e( 'Hide Past Events', 'eventprime-event-calendar-management' );?>
                </label>
            </th>
            <td class="forminp forminp-text">
                <label class="ep-toggle-btn">
                    <input type="checkbox" name="single_type_hide_past_events"  id="single_type_hide_past_events" <?php if( $options['global']->single_type_hide_past_events == 1 ){ echo 'checked="checked"'; }?>>
                    <span class="ep-toogle-slider round"></span>
                </label>
                <div class="ep-help-tip-info ep-my-2 ep-text-muted"><?php esc_html_e( 'If enabled, the related events section will not display past events.', 'eventprime-event-calendar-management' );?></div>
            </td>
        </tr>
        <tr valign="top">
            <th colspan="2">
                <em>Shortcode: </em><code>[em_event_type id="{EVENT_TYPE_ID}" event_style="grid/rows/plain_list" event_limit="{NUMBER}" event_cols="{NUMBER}" load_more="0 or 1" hide_past_events="0 or 1"]</code>
            </th>
        </tr>
    </tbody>
</table>