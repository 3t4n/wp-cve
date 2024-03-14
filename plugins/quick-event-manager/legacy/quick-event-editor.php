<?php

use  Quick_Event_Manager\Plugin\Control\Admin_Template_Loader ;
function event_custom_columns( $column )
{
    $event = get_the_ID();
    $custom = get_post_custom();
    switch ( $column ) {
        case "event_date":
            $date = qem_get_element( qem_get_element( $custom, "event_date" ), 0 );
            echo  esc_html( date_i18n( "d M Y", $date ) ) ;
            
            if ( isset( $custom["event_end_date"][0] ) && !empty($custom["event_end_date"][0]) ) {
                $enddate = $custom["event_end_date"][0];
                echo  ' - ' . esc_html( date_i18n( "d M Y", $enddate ) ) ;
            }
            
            break;
        case "event_start":
            echo  esc_html( qem_get_element( qem_get_element( $custom, "event_start" ), 0 ) ) ;
            if ( isset( $custom["event_finish"][0] ) ) {
                echo  ' - ' . esc_html( $custom["event_finish"][0] ) ;
            }
            break;
        case "event_location":
            echo  esc_html( qem_get_element( qem_get_element( $custom, "event_location" ), 0 ) ) ;
            break;
        case "event_website":
            echo  esc_html( qem_get_element( qem_get_element( $custom, "event_link" ), 0 ) ) ;
            break;
        case "event_cost":
            echo  esc_html( qem_get_element( qem_get_element( $custom, "event_cost" ), 0 ) ) ;
            break;
        case "number_coming":
            echo  qem_wp_kses_post( qem_attending( $event ) ) ;
            break;
        case 'categories':
            $category = get_the_term_list(
                get_the_ID(),
                'category',
                '',
                ', ',
                ''
            );
            echo  esc_html( $category ) ;
            break;
        case 'author':
            echo  get_the_author() ;
            break;
        case 'date':
            echo  get_the_date() ;
            break;
    }
}

function qem_attending( $event )
{
    global  $post ;
    $number = get_post_meta( $post->ID, 'event_number', true );
    $on = $off = $str = '';
    $payment = qem_get_stored_payment();
    $str = qem_get_the_numbers( $event, $payment );
    
    if ( $number !== '' && $number <= $str ) {
        $on = '<span style="color:red">';
        $off = '</span>';
    }
    
    if ( !$number && !$str ) {
        return;
    }
    $places = ( $number ? '/' . $number : '' );
    $attending = ( $str ? $str : '0' );
    return $on . $attending . $places . $off;
}

function event_column_register_sortable( $columns )
{
    $columns['event_date'] = 'event_date';
    $columns['event_start'] = 'event_start';
    $columns['event_location'] = 'event_location';
    $columns['number_coming'] = 'number_coming';
    $columns['categories'] = 'category_name';
    $columns['author'] = 'author_name';
    $columns['date'] = 'date';
    return $columns;
}

function event_edit_columns( $columns )
{
    $columns = array(
        "cb"             => "<input type=\"checkbox\" />",
        "title"          => esc_html__( 'Event', 'quick-event-manager' ),
        "event_date"     => esc_html__( 'Event Date', 'quick-event-manager' ),
        "event_start"    => esc_html__( 'Event Time', 'quick-event-manager' ),
        "event_location" => esc_html__( 'Venue', 'quick-event-manager' ),
        "number_coming"  => esc_html__( 'Attending', 'quick-event-manager' ) . ' <br>/ ' . esc_html__( 'Places', 'quick-event-manager' ),
        "categories"     => esc_html__( 'Categories' ),
        "author"         => esc_html__( 'Author' ),
        "date"           => esc_html__( 'Date' ),
    );
    return $columns;
}

function event_details_meta()
{
    global  $post ;
    global  $qem_fs ;
    $perevent = $perperson = $localcutoffdate = $enddate = $output = '';
    $event = event_get_stored_options();
    $register = qem_get_stored_register();
    $payment = qem_get_stored_payment();
    $display = event_get_stored_display();
    $eventdate = qem_get_event_field( 'event_date' );
    if ( empty($eventdate) ) {
        $eventdate = time();
    }
    $date = gmdate( "d M Y", $eventdate );
    $localdate = date_i18n( "d M Y", $eventdate );
    $thedays = array(
        'Day',
        'Week',
        'Month',
        'Sunday',
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    );
    $thenumbers = array(
        'Every',
        'First',
        'Second',
        'Third',
        'Fourth'
    );
    $eventenddate = qem_get_event_field( 'event_end_date' );
    
    if ( $eventenddate ) {
        $enddate = gmdate( "d M Y", $eventenddate );
        $localenddate = date_i18n( "d M Y", $eventenddate );
    }
    
    $saved_cutoff_date = qem_get_event_field( 'event_cutoff_date' );
    $cutoffdate = '';
    $qem_cutoff_time = '';
    
    if ( $saved_cutoff_date ) {
        $utc_timestamp_converted = gmdate( 'Y-m-d H:i:s', $saved_cutoff_date );
        $cutoffdate = get_date_from_gmt( $utc_timestamp_converted, 'd M Y' );
        $qem_cutoff_time = get_date_from_gmt( $utc_timestamp_converted, 'H:i' );
    }
    
    $event_show_cutoff_blurb = qem_get_event_field( 'event_show_cutoff_blurb' );
    $qem_reg_closed_date_time_msg = qem_get_event_field( 'qem_reg_closed_date_time_msg' );
    
    if ( isset( $register['addtoall'] ) && $register['addtoall'] && !qem_get_event_field( 'event_date' ) ) {
        $useform = 'checked';
    } else {
        $useform = qem_get_event_field( "event_register" );
    }
    
    $usedonation = qem_get_event_field( "event_donation" );
    $usepaypal = '';
    $deposittype = ( qem_get_event_field( 'event_deposittype' ) ? qem_get_event_field( 'event_deposittype' ) : 'perperson' );
    ${$deposittype} = 'checked';
    ${$perevent} = 'checked';
    if ( qem_get_element( $payment, 'paypal', false ) && !qem_get_event_field( 'event_date' ) || qem_get_event_field( 'event_paypal' ) == 'checked' ) {
        $usepaypal = 'checked';
    }
    echo  '<p><em>' . esc_html__( 'Empty fields are not displayed', 'quick-event-manager' ) . ' ' . esc_html__( 'See the plugin', 'quick-event-manager' ) . ' <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '">' . esc_html__( 'settings', 'quick-event-manager' ) . '</a> ' . esc_html__( 'page for options', 'quick-event-manager' ) . '.</em></p>
    <p>' . esc_html__( 'Event ID:', 'quick-event-manager' ) . ' ' . (int) $post->ID . '</p>
    <table>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Date', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="width:30%;border:1px solid #415063;" id="qemdate" name="event_date" value="' . esc_attr( $date ) . '" /> <em>' . esc_html__( 'Local date', 'quick-event-manager' ) . ' (' . esc_html__( 'as it appears on your website', 'quick-event-manager' ) . '): ' . esc_html( $localdate ) . '</em>.</td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'End Date', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="width:30%;border:1px solid #415063;"  id="qemenddate" name="event_end_date" value="' . esc_attr( $enddate ) . '" /> <em>' . esc_html__( 'Leave blank for one day events', 'quick-event-manager' ) . '.</em>' ;
    if ( $eventenddate ) {
        echo  ' <em>' . esc_html__( 'Current end date', 'quick-event-manager' ) . ' (' . esc_html__( 'as it appears on your website', 'quick-event-manager' ) . '): ' . esc_html( $localenddate ) . '</em>' ;
    }
    echo  '</td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Short Description', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_desc" value="' . esc_attr( qem_get_event_field( "event_desc" ) ) . '" />
    </td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Time', 'quick-event-manager' ) . '</label></td>
    <td width="80%">' . esc_html( $event['start_label'] ) . ' <input type="text" class="qem_input" style="width:40%;border:1px solid #415063;"  name="event_start" value="' . esc_attr( qem_get_event_field( "event_start" ) ) . '" /> ' . esc_html( $event['finish_label'] ) . '&nbsp;<input type="text" style="overflow:hidden;border:1px solid #415063;"   name="event_finish" value="' . esc_attr( qem_get_event_field( "event_finish" ) ) . '" /><br>
    <span class="description">' . esc_html__( 'Start times in the format 8.23 am/pm, 8.23, 8:23 and 08:23 will be used to order events by time and date. All other formats will display but won\'t contribute to the event ordering', 'quick-event-manager' ) . '.</span> 
    </td>
    </tr>' ;
    
    if ( $display['usetimezone'] ) {
        $tz = qem_get_event_field( "selected_timezone" );
        echo  '<tr>
        <td width="20%"><label>' . esc_html__( 'Timezone', 'quick-event-manager' ) . ': </label></td>
        <td width="80%">' ;
        if ( qem_get_event_field( "event_timezone" ) ) {
            echo  '<strong>' . esc_html__( 'Current timezone:', 'quick-event-manager' ) . '</strong> ' . esc_html( qem_get_event_field( "event_timezone" ) ) . '.&nbsp;&nbsp;' ;
        }
        echo  esc_html__( 'Select a new timezone or enter your own:', 'quick-event-manager' ) . '<br>
        <select style="border:1px solid #415063;" name="event_timezone" id="event_timezone">
        <option value="">' . esc_html__( 'None', 'quick-event-manager' ) . '</option>
        <option ' . selected( $tz, 'Eni' ) . ' value="Eniwetok, Kwajalein">(GMT -12:00) Eniwetok, Kwajalein</option>       
        <option ' . selected( $tz, 'Mid' ) . ' value="Midway Island, Samoa">(GMT -11:00) Midway Island, Samoa</option>       
        <option ' . selected( $tz, 'Hwa' ) . ' value="Hawaii">(GMT -10:00) Hawaii</option>       
        <option ' . selected( $tz, 'Ala' ) . ' value="Alaska">(GMT -9:00) Alaska</option>       
        <option ' . selected( $tz, 'Pac' ) . ' value="Pacific Time (US &amp; Canada)">(GMT -8:00) Pacific Time (US &amp; Canada)</option>       
        <option ' . selected( $tz, 'Mou' ) . ' value="Mountain Time (US &amp; Canada)">(GMT -7:00) Mountain Time (US &amp; Canada)</option>       
        <option ' . selected( $tz, 'Cen' ) . ' value="Central Time (US &amp; Canada), Mexico City">(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>       
        <option ' . selected( $tz, 'Eas' ) . ' value="Eastern Time (US &amp; Canada), Bogota, Lima">(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>       
        <option ' . selected( $tz, 'Atl' ) . ' value="Atlantic Time (Canada), Caracas, La Paz">(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>       
        <option ' . selected( $tz, 'New' ) . ' value="Newfoundland">(GMT -3:30) Newfoundland</option>       
        <option ' . selected( $tz, 'Bra' ) . ' value="Brazil, Buenos Aires, Georgetown">(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>       
        <option ' . selected( $tz, 'Mia' ) . ' value="Mid-Atlantic">(GMT -2:00) Mid-Atlantic</option>       
        <option ' . selected( $tz, 'Azo' ) . ' value="Azores, Cape Verde Islands">(GMT -1:00 hour) Azores, Cape Verde Islands</option>       
        <option ' . selected( $tz, 'Wes' ) . ' value="Western Europe Time, London, Lisbon, Casablanca">(GMT) Western Europe Time, London, Lisbon, Casablanca</option>       
        <option ' . selected( $tz, 'Bru' ) . ' value="Brussels, Copenhagen, Madrid, Paris">(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>       
        <option ' . selected( $tz, 'Kal' ) . ' value="Kaliningrad, South Africa">(GMT +2:00) Kaliningrad, South Africa</option>       
        <option ' . selected( $tz, 'Bag' ) . ' value="Baghdad, Riyadh, Moscow, St. Petersburg">(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>       
        <option ' . selected( $tz, 'Teh' ) . ' value="Tehran">(GMT +3:30) Tehran</option>       
        <option ' . selected( $tz, 'Abu' ) . ' value="Abu Dhabi, Muscat, Baku, Tbilisi">(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>       
        <option ' . selected( $tz, 'Kab' ) . ' value="Kabul">(GMT +4:30) Kabul</option>       
        <option ' . selected( $tz, 'Eka' ) . ' value="Ekaterinburg, Islamabad, Karachi, Tashkent">(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>       
        <option ' . selected( $tz, 'Bom' ) . ' value="Bombay, Calcutta, Madras, New Delhi">(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>       
        <option ' . selected( $tz, 'Kat' ) . ' value="Kathmandu">(GMT +5:45) Kathmandu</option>       
        <option ' . selected( $tz, 'Alm' ) . ' value="Almaty, Dhaka, Colombo">(GMT +6:00) Almaty, Dhaka, Colombo</option>       
        <option ' . selected( $tz, 'Ban' ) . ' value="Bangkok, Hanoi, Jakarta">(GMT +7:00) Bangkok, Hanoi, Jakarta</option>       
        <option ' . selected( $tz, 'Bei' ) . ' value="Beijing, Perth, Singapore, Hong Kong">(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>       
        <option ' . selected( $tz, 'Tok' ) . ' value="Tokyo, Seoul, Osaka, Sapporo, Yakutsk">(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>       
        <option ' . selected( $tz, 'Ade' ) . ' value="Adelaide, Darwin">(GMT +9:30) Adelaide, Darwin</option>       
        <option ' . selected( $tz, 'Aus' ) . ' value="Eastern Australia, Guam, Vladivostok">(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>       
        <option ' . selected( $tz, 'Mag' ) . ' value="Magadan, Solomon Islands, New Caledonia">(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>       
        <option ' . selected( $tz, 'Auk' ) . ' value="Auckland, Wellington, Fiji, Kamchatka">(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option> 
        </select>
        <br><span class="description">The option to display timezones is set on the <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=display">Event Display</a> page.</span>
    </td>
    </tr>' ;
    }
    
    echo  '<tr>
    <td width="20%"><label>' . esc_html__( 'Venue', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;"  name="event_location" value="' . esc_attr( qem_get_event_field( "event_location" ) ) . '" /></td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_attr__( 'Address', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;"  name="event_address" value="' . esc_attr( qem_get_event_field( "event_address" ) ) . '" /></td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Website', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="width:40%;border:1px solid #415063;"  name="event_link" value="' . esc_attr( qem_get_event_field( "event_link" ) ) . '" /><label> ' . esc_html__( 'Display As', 'quick-event-manager' ) . ':</label>&nbsp;<input type="text" style="width:40%;overflow:hidden;border:1px solid #415063;"  name="event_anchor" value="' . esc_html( qem_get_event_field( "event_anchor" ) ) . '" /></td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Cost', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_cost" value="' . esc_attr( qem_get_event_field( "event_cost" ) ) . '" /></td>
    </tr>' ;
    echo  '<tr>
    <td width="20%"><label>' . esc_html__( 'Deposit', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="width:10em;border:1px solid #415063;" name="event_deposit" value="' . esc_attr( qem_get_event_field( "event_deposit" ) ) . '" />&nbsp;<input type="radio" name="event_deposittype" value="perperson" ' . esc_attr( $perperson ) . ' /> ' . esc_html__( 'Per person', 'quick-event-manager' ) . ' <input type="radio" name="event_deposittype" value="perevent" ' . esc_attr( $perevent ) . ' /> ' . esc_html__( 'Per Event', 'quick-event-manager' ) . '<br><span class="description">' . esc_html__( 'If you add a deposit this amount will be used for payments', 'quick-event-manager' ) . '</span></td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Organiser', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_organiser" value="' . esc_attr( qem_get_event_field( "event_organiser" ) ) . '" /></td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Organiser Contact Details', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_telephone" value="' . esc_attr( qem_get_event_field( "event_telephone" ) ) . '" /></td>
    </tr>
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Registration Form', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="checkbox" style="" name="event_register" value="checked" ' . esc_attr( $useform ) . '> ' . esc_html__( 'Add registration form to this event.', 'quick-event-manager' ) . ' <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=register">' . esc_html__( 'Registration form settings', 'quick-event-manager' ) . '</a></td>
    </tr>' ;
    echo  '<tr>
    <td></td>
    <td width="80%">' . esc_html__( 'Notes', 'quick-event-manager' ) . ':<br>
    1. ' . esc_html__( 'You can create a custom registration form for this event using the options at the bottom of this page', 'quick-event-manager' ) . '.<br>
    2. ' . sprintf( esc_html__( 'If you are using the %1$sautoresponder%2$s you can create a reply message for this event. See the \'Registration Confirmation Message\' at the bottom of this page.', 'quick-event-manager' ), '<a href="options-general.php?page=' . esc_html( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=auto">', '</a>' ) . '.</td>
    </tr>' ;
    echo  '<tr><td width="20%"><label>' . esc_html__( 'External Event / Registration', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input disabled type="text" class="qem_input" name="not_available"  value="' . 'https://' . esc_html__( 'your-external-event-link-here', 'quick-event-manager' ) . '"><em>' . esc_html__( 'Upgrade to be able to specify a link to a third party event detail / registration system, e.g. Eventbrite / Ticketmaster etc. No registration form wil be shown or processed by Quick Event Manager', 'quick-event-manager' ) . '</em></td>
    </tr>' ;
    echo  '<tr>
    <td width="20%"><label>' . esc_html__( 'Places Available', 'quick-event-manager' ) . ': </label></td>
    <td><input type="text" class="qem_input" style="width:3em;border:1px solid #415063;" name="event_number" value="' . esc_attr( qem_get_event_field( "event_number" ) ) . '" /></td>
    </tr>
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Maximum number of places per registration', 'quick-event-manager' ) . ': </label></td>
    <td><input type="text" class="qem_input" style="width:3em;border:1px solid #415063;" name="event_maxplaces" value="' . esc_attr( qem_get_event_field( "event_maxplaces" ) ) . '" /> <input type="checkbox" style="" name="event_requiredplaces" value="checked" ' . esc_attr( qem_get_event_field( "event_requiredplaces" ) ) . '> ' . esc_html__( 'Make this required number', 'quick-event-manager' ) . ' <input type="checkbox" style="" name="event_getnames" value="checked" ' . esc_attr( qem_get_event_field( "event_getnames" ) ) . '> ' . esc_html__( 'Tick if you want only a single name/email to reserve multi places', 'quick-event-manager' ) . ' <input type="checkbox" style="" name="event_getemails" value="checked" ' . esc_attr( qem_get_event_field( "event_getemails" ) ) . '> ' . esc_html__( 'Collect emails - if you have made emails mandatory on the registration form - you need to tick this otherwise users will not be able to submit the form', 'quick-event-manager' ) . '</td>
    </tr><tr><td></td><td><hr></td></tr>' ;
    echo  '<tr>
    <td width="20%"><label>' . esc_html__( 'Registration Start Date', 'quick-event-manager' ) . ': </label></td>
    <td><input type="text" class="qem_input" style="width:40%;" disabled id="qem_reg_start_date" name="qem_reg_start_date" value="" />
    <input type="time" name="qem_reg_start_time" value="00:00" disabled/><a href="' . esc_url( $qem_fs->get_upgrade_url() ) . '" target="_blank">' . esc_html__( 'Upgrade to set registration start', 'quick-event-manager' ) . '</a></td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" style="" name="qem_reg_start_date_time_show_msg" value="checked" disabled /> ' . esc_html__( 'Show registration not open on event page. Message to display before start date:', 'quick-event-manager' ) . '<br>
    <input type="text" class="qem_input" style="" name="qem_reg_start_date_time_msg" disabled value="" / />
    </td>
    </tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Registration Cutoff Date', 'quick-event-manager' ) . ': </label></td>
    <td><input type="text" class="qem_input" style="width:40%;" id="qemcutoffdate" name="event_cutoff_date" value="' . esc_attr( $cutoffdate ) . '" />
    <input type="time" name="qem_cutoff_time" value="00:00" disabled/><a href="' . esc_url( $qem_fs->get_upgrade_url() ) . '" target="_blank">' . esc_html__( 'Upgrade to set time', 'quick-event-manager' ) . '</a></td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" style="" name="event_show_cutoff" value="checked" ' . esc_attr( qem_get_event_field( "event_show_cutoff" ) ) . ' /> ' . esc_html__( 'Show cutoff date on event page. Message to display before cutoff date:', 'quick-event-manager' ) . '<br>
    <input type="text" class="qem_input" style="" name="event_show_cutoff_blurb" value="' . esc_attr( $event_show_cutoff_blurb ) . '" / />
    </td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" style="" name="qem_reg_closed_date_time_show_msg" value="checked" ' . checked( qem_get_event_field( "qem_reg_closed_date_time_show_msg" ), 'checked' ) . ' /> ' . esc_html__( 'Show message after cutoff. Message to display:', 'quick-event-manager' ) . '<br>
    <input type="text" class="qem_input" style="" name="qem_reg_closed_date_time_msg" value="' . esc_attr( $qem_reg_closed_date_time_msg ) . '" / />
    </td>
    </tr>' ;
    echo  '<tr><td></td><td><hr></td><tr>
    <tr>
    <td width="20%"><label>' . esc_html__( 'Payment', 'quick-event-manager' ) . ': </label></td>
    <td><input type="checkbox" name="event_paypal" value="checked" ' . esc_attr( $usepaypal ) . ' /> ' . esc_html__( 'Link to payment after registration', 'quick-event-manager' ) . '. <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=payment">' . esc_html__( 'Payment settings', 'quick-event-manager' ) . '</a>.</td>
    </tr>
    
    <tr>
    <td>Cost table</td>
    <td><input type="checkbox" name="event_products" value="checked" ' . esc_attr( qem_get_event_field( "event_products" ) ) . ' /> ' . esc_html__( 'Use variable prices', 'quick-event-manager' ) . '.</td>
    <tr>
    
    <tr>
    <td></td><td>' ;
    $productlist = qem_get_event_field( "event_productlist" );
    
    if ( null == $productlist ) {
        $product = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
    } else {
        $product = explode( ',', $productlist );
    }
    
    echo  '<table>
    <tr>
    <th style="text-align: left">' . esc_html__( 'Ticket Type Name/ Label', 'quick-event-manager' ) . '</th>
    <th style="text-align: left">' . esc_html__( 'Cost per ticket', 'quick-event-manager' ) . '</th>
    </tr>
    <tr>
    <td><input type="text" style="border:1px solid #415063;width:10em" name="product1" value="' . esc_attr( $product[1] ) . '" /></td>
    <td><input type="text" style="border:1px solid #415063;width:6em" name="product2" value="' . esc_attr( $product[2] ) . '" /></td>
    </tr>
    <tr>
    <td><input type="text" style="border:1px solid #415063;width:10em" name="product3" value="' . esc_attr( $product[3] ) . '" /></td>
    <td><input type="text" style="border:1px solid #415063;width:6em" name="product4" value="' . esc_attr( $product[4] ) . '" /></td>
    </tr>
    <tr>
    <td><input type="text" style="border:1px solid #415063;width:10em" name="product5" value="' . esc_attr( $product[5] ) . '" /></td>
    <td><input type="text" style="border:1px solid #415063;width:6em" name="product6" value="' . esc_attr( $product[6] ) . '" /></td>
    </tr>
    <tr>
    <td><input type="text" style="border:1px solid #415063;width:10em" name="product7" value="' . esc_attr( $product[7] ) . '" /></td>
    <td><input type="text" style="border:1px solid #415063;width:6em" name="product8" value="' . esc_attr( $product[8] ) . '" /></td>
    </tr>
 </table>' ;
    echo  '</td>
    </tr>
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Redirect to a URL after registration', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_redirect" value="' . esc_attr( qem_get_event_field( "event_redirect" ) ) . '" /><br>
    <input type="checkbox" style="" name="event_redirect_id" value="checked" ' . esc_attr( qem_get_event_field( "event_redirect_id" ) ) . ' /> ' . esc_html__( 'Add event ID to redirect URL', 'quick-event-manager' ) . '</td>
    </tr>
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Read More Label', 'quick-event-manager' ) . ': </label></td>
    <td><input type="text" class="qem_input" style="border:1px solid #415063;" name="event_readmore" value="' . esc_attr( qem_get_event_field( "event_readmore" ) ) . '" /></td>
    </tr>
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Password protection', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="checkbox" style="" name="event_password_details" value="checked" ' . esc_attr( qem_get_event_field( "event_password_details" ) ) . '> ' . esc_html__( 'Whole event ( also set Visibility: Password protected in Publish box)', 'quick-event-manager' ) . '</td>
    </tr>
    
    
    <tr>
    <td width="20%"><label>' . esc_html__( 'Hide Event', 'quick-event-manager' ) . ': </label></td>
    <td width="80%"><input type="checkbox" style="" name="hide_event" value="checked" ' . esc_attr( qem_get_event_field( "hide_event" ) ) . '> ' . esc_html__( 'Hide this event in the event list (only display on the calendar)', 'quick-event-manager' ) . '.</td>
    </tr>
    
    
    <tr>
    <td style="vertical-align:top;"><label>' . esc_html__( 'Event Image', 'quick-event-manager' ) . ': </label></td>' ;
    
    if ( qem_get_event_field( "event_image" ) ) {
        echo  '<td><img class="qem-image qem-no-image" rel="' . esc_url( QUICK_EVENT_MANAGER_PLUGIN_URL ) . 'legacy/images/no_image.png" alt="' . esc_url( QUICK_EVENT_MANAGER_PLUGIN_URL ) . 'legacy/images/image_error.png" src=' . esc_url( qem_get_event_field( "event_image" ) ) . '></td>' ;
    } else {
        echo  '<td><img class="qem-image qem-no-image" rel="' . esc_url( QUICK_EVENT_MANAGER_PLUGIN_URL ) . 'legacy/images/no_image.png" alt="' . esc_url( QUICK_EVENT_MANAGER_PLUGIN_URL ) . 'legacy/images/image_error.png" src="' . esc_url( QUICK_EVENT_MANAGER_PLUGIN_URL ) . 'legacy/images/no_image.png"></td>' ;
    }
    
    echo  '</tr>
    <tr>
    <td></td>
    <td><input id="event_image" type="hidden" name="event_image" value="' . esc_attr( qem_get_event_field( "event_image" ) ) . '" /><input id="upload_event_image" class="button" type="button" value="' . esc_html__( 'Upload Image', 'quick-event-manager' ) . '" /> &nbsp <input id="remove_event_image" class="button" type="button" value="' . esc_html__( 'Remove Image', 'quick-event-manager' ) . '" /></td>
    </tr>
    <tr>
    <td style="vertical-align:top"><label>' . esc_html__( 'Repeat Event', 'quick-event-manager' ) . ': </label></td>
    <td><span style="color:red;font-weight:bold;">' . esc_html__( 'Warning', 'quick-event-manager' ) . ':</span> ' . esc_html__( 'Only use once or you will get lots of duplicated events', 'quick-event-manager' ) . '<br />
    <p><input type="checkbox" name="event_repeat" value="checked"> ' . esc_html__( 'Repeat Event', 'quick-event-manager' ) . ' </p>' ;
    echo  '<div id="repeat">' ;
    echo  esc_html__( 'Repeat every', 'quick-event-manager' ) . ' <select style="width:7em;" name="thenumber">' ;
    for ( $i = 0 ;  $i < count( $thenumbers ) ;  ++$i ) {
        echo  '<option value="' . esc_attr( $thenumbers[$i] ) . '">' . esc_html( $thenumbers[$i] ) . '</option>' ;
    }
    echo  '</select>&nbsp;<select style="width:8em;" name="theday"></select> ' . esc_html__( 'for', 'quick-event-manager' ) . ' <input type="text" style="width:3em;" name="therepetitions" value="12"  onblur="if (this.value == \'\') {this.value = \'12\';}" onfocus="if (this.value == \'12\') {this.value = \'\';}">&nbsp;
        <select name="thewmy" style="width:7em;"></select>
        </div>
    </tr>' ;
    $event = get_the_ID();
    $title = get_the_title();
    $whoscoming = get_option( 'qem_messages_' . $event );
    
    if ( $whoscoming ) {
        $event_names = '';
        foreach ( $whoscoming as $item ) {
            $event_names .= $item['yourname'] . ', ';
        }
        $event_names = substr( $event_names, 0, -2 );
        echo  '<tr>
        <td>' . esc_html__( 'Attendees', 'quick-event-manager' ) . ' (' . esc_html__( 'names and emails collected from the', 'quick-event-manager' ) . ' <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=register">' . esc_html__( 'registration form', 'quick-event-manager' ) . '</a>)</td>
        <td>' . qem_wp_kses_post( $event_names ) . '</td>
        </tr>
        <tr>
        <td></td>
        <td><a href="admin.php?page=qem-registration&event=' . esc_attr( $event ) . '&title=' . esc_attr( $title ) . '">' . esc_html__( 'View Full Registration Details', 'quick-event-manager' ) . '</a></td>
        <tr>' ;
    }
    
    echo  '<tr>
        <td>' . esc_html__( 'Notes', 'quick-event-manager' ) . ':</td>
        <td><textarea style="width:100%;height:100px;" name="event_notes">' . qem_wp_kses_post( qem_get_event_field( "event_notes" ) ) . '</textarea></td>
        </tr>
        </table>' ;
    wp_nonce_field( 'qem_nonce', 'save_qem' );
    
    if ( !$qem_fs->can_use_premium_code() ) {
        $template_loader = new Admin_Template_Loader();
        $template_loader->set_template_data( array(
            'template_loader' => $template_loader,
            'freemius'        => $qem_fs,
        ) );
        $template_loader->get_template_part( 'upgrade_cta' );
        echo  qem_wp_kses_post( $template_loader->get_output() ) ;
    }

}

function event_details_reg()
{
    global  $post ;
    global  $qem_fs ;
    $disabled = 'disabled';
    $disabled_text = esc_html__( 'Upgrade to premium to enable changes', 'quick-event-manager' );
    $register = qem_get_stored_register();
    echo  '<p><input type="checkbox" style="" name="usecustomform" value="checked" ' . esc_attr( qem_get_event_field( "usecustomform" ) ) . '> ' . esc_html__( 'Use custom form settings', 'quick-event-manager' ) . '</p>
    <p><em>' . esc_html__( 'Check the fields you want to display on the form.', 'quick-event-manager' ) . ' ' . esc_html__( 'See the plugin', 'quick-event-manager' ) . ' <a href="options-general.php?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=register">' . esc_html__( 'Registration', 'quick-event-manager' ) . '</a> ' . esc_html__( 'page for options and to change the order of the fields.', 'quick-event-manager' ) . '.</em></p>
    <p>
    <input type="checkbox" name="usename" value="checked" ' . esc_attr( qem_get_event_field( "usename" ) ) . '> ' . esc_html__( 'Name', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usemail" value="checked" ' . esc_attr( qem_get_event_field( "usemail" ) ) . '> ' . esc_html__( 'Email', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usetelephone" value="checked" ' . esc_attr( qem_get_event_field( "usetelephone" ) ) . '> ' . esc_html__( 'Telephone', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="useplaces" value="checked" ' . esc_attr( qem_get_event_field( "useplaces" ) ) . '> ' . esc_html__( 'Places', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usemessage" value="checked" ' . esc_attr( qem_get_event_field( "usemessage" ) ) . '> ' . esc_html__( 'Message', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="useattend" value="checked" ' . esc_attr( qem_get_event_field( "useattend" ) ) . '> ' . esc_html__( 'Not Attending', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="useblank1" value="checked" ' . esc_attr( qem_get_event_field( "useblank1" ) ) . '> ' . esc_html__( 'User defined', 'quick-event-manager' ) . ' 1<br>
    <input type="checkbox" name="useblank2" value="checked" ' . esc_attr( qem_get_event_field( "useblank2" ) ) . '> ' . esc_html__( 'User defined', 'quick-event-manager' ) . ' 2<br>
    <input type="checkbox" name="usedropdown" value="checked" ' . esc_attr( qem_get_event_field( "usedropdown" ) ) . '> ' . esc_html__( 'Dropdown 1', 'quick-event-manager' ) . ' - ' . esc_html__( 'Values', 'quick-event-manager' ) . ': <input type="text" name="yourdropdown" value="' . esc_attr( qem_get_dropdown( "yourdropdown", $register['yourdropdown'] ) ) . '"' . esc_attr( $disabled ) . '>' . esc_attr( $disabled_text ) . '<br>
    <input type="checkbox" name="useselector" value="checked" ' . esc_attr( qem_get_event_field( "useselector" ) ) . '> ' . esc_html__( 'Dropdown 2', 'quick-event-manager' ) . ' - ' . esc_html__( 'Values', 'quick-event-manager' ) . ': <input type="text" name="yourselector" value="' . esc_attr( qem_get_dropdown( "yourselector", $register['yourselector'] ) ) . '"' . esc_attr( $disabled ) . '>' . esc_attr( $disabled_text ) . '<br>

    <input type="checkbox" name="usenumber1" value="checked" ' . esc_attr( qem_get_event_field( "usenumber1" ) ) . '> ' . esc_html__( 'Number', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usechecks" value="checked" ' . esc_attr( qem_get_event_field( "usechecks" ) ) . '> ' . esc_html__( 'Options', 'quick-event-manager' ) . ' - ' . esc_html__( 'Values', 'quick-event-manager' ) . ': <input type="text" name="checkslist" value="' . esc_attr( qem_get_dropdown( "checkslist", $register['checkslist'] ) ) . '"' . esc_attr( $disabled ) . '>' . esc_attr( $disabled_text ) . '<br>

    <input type="checkbox" name="useaddinfo" value="checked" ' . esc_attr( qem_get_event_field( "useaddinfo" ) ) . '> ' . esc_html__( 'Additional Info (displays as plain text)', 'quick-event-manager' ) . '<br>
    <input type="text" name= "addinfo" class="qem_input" value="' . esc_attr( qem_get_event_field( "addinfo" ) ) . '"></br>
    <input type="checkbox" name="usemorenames" value="checked" ' . esc_attr( qem_get_event_field( "usemorenames" ) ) . '> ' . esc_html__( 'Show box to add more names if number attending is greater than 1', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="moreemails" value="checked" ' . esc_attr( qem_get_event_field( "moreemails" ) ) . '> ' . esc_html__( 'Collect email addresses for all attendees', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usecopy" value="checked" ' . esc_attr( qem_get_event_field( "usecopy" ) ) . '>' . esc_html__( 'Copy Message', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="useterms" value="checked" ' . esc_attr( qem_get_event_field( "useterms" ) ) . '> ' . esc_html__( 'Include Terms and Conditions checkbox', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="usecaptcha" value="checked" ' . esc_attr( qem_get_event_field( "usecaptcha" ) ) . '>' . esc_html__( 'Captcha', 'quick-event-manager' ) . '<br>' ;
    wp_nonce_field( 'qem_nonce', 'save_qem' );
}

function qem_get_dropdown( $event_field, $register )
{
    $dropdown = qem_get_event_field( $event_field );
    if ( '' === $dropdown && !empty($register) ) {
        $dropdown = $register;
    }
    return $dropdown;
}

/**
 * Gets post meta
 *
 * @param $event_field
 *
 * @return mixed   an unset post meta returns an empty string and invalid post id returns false
 */
function qem_get_event_field( $event_field )
{
    global  $post ;
    // note an unset post meta returns an empty string
    return get_post_meta( $post->ID, $event_field, true );
}

function save_event_details()
{
    global  $post ;
    global  $qem_fs ;
    $eventdetails = event_get_stored_options();
    $event = get_the_ID();
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( !isset( $_POST['save_qem'] ) || !wp_verify_nonce( $_POST['save_qem'], 'qem_nonce' ) ) {
        return;
    }
    
    if ( isset( $_POST["event_date"] ) ) {
        $startdate = strtotime( sanitize_text_field( $_POST["event_date"] ) );
        $starttime = qem_time( sanitize_text_field( $_POST["event_start"] ) );
        if ( !$startdate ) {
            $startdate = time();
        }
        $newdate = $startdate + $starttime;
        update_post_meta( $post->ID, "event_date", $newdate );
    }
    
    
    if ( isset( $_POST["event_end_date"] ) ) {
        if ( empty($_POST["event_end_date"]) ) {
            delete_post_meta( $post->ID, "event_end_date" );
        }
        $enddate = strtotime( sanitize_text_field( $_POST["event_end_date"] ) );
        $endtime = qem_time( sanitize_text_field( $_POST["event_finish"] ) );
        $newenddate = ( $enddate ? $enddate + $endtime : '' );
        update_post_meta( $post->ID, "event_end_date", $newenddate );
    }
    
    if ( isset( $_POST["event_cutoff_date"] ) ) {
        
        if ( empty($_POST["event_cutoff_date"]) ) {
            delete_post_meta( $post->ID, "event_cutoff_date" );
        } else {
            $date = sanitize_text_field( $_POST["event_cutoff_date"] );
            $time = ( isset( $_POST['qem_cutoff_time'] ) ? sanitize_text_field( $_POST['qem_cutoff_time'] ) : '' );
            $timestamp = strtotime( $date . ' ' . $time );
            
            if ( $timestamp !== false ) {
                $timestamp_converted = gmdate( 'Y-m-d H:i:s', $timestamp );
                $timestamp = (int) get_gmt_from_date( $timestamp_converted, 'U' );
            }
            
            update_post_meta( $post->ID, "event_cutoff_date", $timestamp );
        }
    
    }
    save_event_field( "event_desc", 'qem_wp_kses_post' );
    save_event_field( "event_start", 'sanitize_text_field' );
    save_event_field( "event_finish", 'sanitize_text_field' );
    save_event_field( "event_timezone", 'sanitize_text_field' );
    
    if ( isset( $_POST["event_timezone"] ) ) {
        
        if ( sanitize_text_field( $_POST["event_timezone"] ) == "Eastern Australia, Guam, Vladivostok" ) {
            $sel = "Aus";
        } elseif ( sanitize_text_field( $_POST["event_timezone"] ) == "Mid-Atlantic" ) {
            $sel = "Mia";
        } else {
            $sel = substr( sanitize_text_field( $_POST["event_timezone"] ), 0, 3 );
        }
        
        update_post_meta( $post->ID, "selected_timezone", $sel );
    }
    
    save_event_field( "event_custom_timezone", 'sanitize_text_field' );
    save_event_field( "event_location", 'qem_wp_kses_post' );
    save_event_field( "event_address", 'qem_wp_kses_post' );
    save_event_field( "event_link", 'sanitize_url' );
    save_event_field( "event_anchor", 'sanitize_text_field' );
    save_event_field( "event_cost", 'sanitize_text_field' );
    save_event_field( "event_deposit", 'sanitize_text_field' );
    save_event_field( "event_deposittype", 'sanitize_text_field' );
    save_event_field( "event_organiser", 'qem_wp_kses_post' );
    save_event_field( "event_telephone", 'qem_wp_kses_post' );
    save_event_field( "event_image", 'sanitize_url' );
    save_event_field( "event_redirect", 'sanitize_url' );
    save_event_field( "event_registration_message", 'qem_wp_kses_post' );
    save_event_field( "event_show_cutoff_blurb", 'qem_wp_kses_post' );
    save_event_field( "qem_reg_closed_date_time_msg", 'qem_wp_kses_post' );
    save_event_field( "event_maxplaces", 'sanitize_text_field' );
    save_event_field( "event_notes", 'qem_wp_kses_post' );
    save_event_field( "event_readmore", 'qem_wp_kses_post' );
    $products = ',';
    // some legacy rubbish here - starts with a comma
    for ( $i = 1 ;  $i <= 8 ;  $i++ ) {
        
        if ( $i & 1 ) {
            //odd
            $val = qem_wp_kses_post( qem_get_element( $_POST, "product" . $i ) );
        } else {
            //even
            $val = sanitize_text_field( qem_get_element( $_POST, "product" . $i ) );
        }
        
        $products = $products . $val . ',';
    }
    update_post_meta( $post->ID, "event_productlist", $products );
    
    if ( qem_get_element( $eventdetails, 'publicationdate', false ) && $newdate ) {
        // @TODO   post gmt date - local date this doesn't work well
        remove_action( 'save_post', 'save_event_details' );
        $updatestart = date_i18n( 'Y-m-d H:i:s', $newdate );
        wp_update_post( array(
            'ID'        => $event,
            'post_date' => $updatestart,
        ) );
        add_action( 'save_post', 'save_event_details' );
    }
    
    global  $qem_fs ;
    // not 100% sure of why  but these dont have any saved meta if blank, I assume it is used later in logic - but  the logic of missing meta is blank anyway
    // they appear to be all  either checkboxes or numbers
    $arr = array(
        'hide_event',
        'event_number',
        'event_register',
        'event_donation',
        'event_counter',
        'event_paypal',
        'event_redirect_id',
        'usecustomform',
        'usename',
        'usemail',
        'usetelephone',
        'useplaces',
        'usemessage',
        'useattend',
        'useblank1',
        'useblank2',
        'usedropdown',
        'useselector',
        'usenumber1',
        'usechecks',
        'useaddinfo',
        'addinfo',
        'usemorenames',
        'moreemails',
        'usecopy',
        'useterms',
        'usecaptcha',
        'usecoupon',
        'event_requiredplaces',
        'event_getemails',
        'event_getnames',
        'event_show_cutoff',
        'qem_reg_closed_date_time_show_msg',
        'qem_reg_start_date_time_show_msg',
        'event_password_details',
        'event_password_registration',
        'event_products'
    );
    foreach ( $arr as $item ) {
        $old = qem_get_event_field( $item );
        $new = trim( sanitize_text_field( qem_get_element( $_POST, $item ) ) );
        
        if ( '' !== $new && $new != $old ) {
            update_post_meta( $post->ID, $item, $new );
        } elseif ( '' === $new && '' !== $old ) {
            delete_post_meta( $post->ID, $item, $old );
        }
    
    }
    
    if ( qem_get_element( $_POST, "event_repeat", false ) ) {
        $_POST["event_repeat"] = '';
        qem_duplicate_new_post( $_POST, $event, 'publish' );
    }

}

function save_event_field( $event_field, $sanitize_function )
{
    global  $post ;
    // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce validatioon performed in calling function
    if ( isset( $_POST[$event_field] ) ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce validatioon performed in calling function
        update_post_meta( $post->ID, $event_field, $sanitize_function( $_POST[$event_field] ) );
    }
}

function action_add_meta_boxes()
{
    add_meta_box(
        'event_details',
        esc_html__( 'Event Details', 'quick-event-manager' ),
        'event_details_meta',
        'event',
        'normal',
        'high'
    );
    add_meta_box(
        'event_registration',
        esc_html__( 'Event Registration Form', 'quick-event-manager' ),
        'event_details_reg',
        'event'
    );
    add_meta_box(
        'registration_confirmation',
        esc_html__( 'Registration Confirmation Message', 'quick-event-manager' ),
        'rcm_meta_box',
        'event'
    );
    global  $_wp_post_type_features ;
    
    if ( isset( $_wp_post_type_features['event']['editor'] ) && $_wp_post_type_features['event']['editor'] ) {
        unset( $_wp_post_type_features['event']['editor'] );
        add_meta_box(
            'description_section',
            esc_html__( 'Event Description', 'quick-event-manager' ),
            'inner_custom_box',
            'event',
            'normal',
            'high'
        );
    }

}

function qem_build_email_list(
    $register,
    $message,
    $report,
    $pid
)
{
    $span = $charles = $content = '';
    $delete = array();
    $i = 0;
    $sort = explode( ',', $register['sort'] );
    $dashboard = '<table cellspacing="0">
    <tr>';
    foreach ( $sort as $name ) {
        switch ( $name ) {
            case 'field1':
                if ( $register['usename'] ) {
                    $dashboard .= '<th>' . $register['yourname'] . '</th>';
                }
                break;
            case 'field2':
                if ( $register['usemail'] ) {
                    $dashboard .= '<th>' . $register['youremail'] . '</th>';
                }
                break;
            case 'field4':
                if ( $register['usetelephone'] ) {
                    $dashboard .= '<th>' . $register['yourtelephone'] . '</th>';
                }
                break;
            case 'field5':
                if ( $register['useplaces'] ) {
                    $dashboard .= '<th>Places</th>';
                }
                break;
        }
    }
    $dashboard .= '<th>Select</th>';
    $dashboard .= '</tr>';
    foreach ( $message as $value ) {
        $span = '';
        $content .= '<tr>';
        foreach ( $sort as $name ) {
            switch ( $name ) {
                case 'field1':
                    if ( $register['usename'] ) {
                        $content .= '<td>' . $value['yourname'] . '</td>';
                    }
                    break;
                case 'field2':
                    if ( $register['usemail'] ) {
                        $content .= '<td>' . $value['youremail'] . '</td>';
                    }
                    break;
                case 'field4':
                    if ( $register['usetelephone'] ) {
                        $content .= '<td>' . $value['yourtelephone'] . '</td>';
                    }
                    break;
                case 'field5':
                    if ( $register['useplaces'] && empty($value['notattend']) ) {
                        $content .= '<td>' . $value['yourplaces'] . '</td>';
                    }
                    break;
            }
        }
        if ( $value['yourname'] ) {
            $charles = 'messages';
        }
        $content .= '<td><input type="checkbox" name="' . $i . '" value="checked" /></td>';
        $content .= '</tr>';
        $i++;
    }
    $dashboard .= $content . '</table>';
    if ( $charles ) {
        return $dashboard;
    }
}

function inner_custom_box( $post )
{
    $settings = array(
        'wpautop' => false,
    );
    wp_editor( $post->post_content, 'post_content', $settings );
}

function rcm_meta_box( $post )
{
    $settings = array(
        'wpautop' => false,
    );
    $field_value = get_post_meta( $post->ID, 'event_registration_message', false );
    wp_editor( qem_get_element( $field_value, 0, '' ), 'event_registration_message', $settings );
}

function qem_duplicate()
{
    if ( !isset( $_GET['_wpnonce'] ) || !wp_verify_nonce( $_GET['_wpnonce'], 'qem-duplicate-event' ) ) {
        wp_die( esc_html__( 'Invalid Nonce, sorry something went wrong', 'quick-event-manager' ) );
    }
    if ( !current_user_can( 'edit_events' ) ) {
        wp_die( esc_html__( 'Incorrect access level, sorry something went wrong', 'quick-event-manager' ) );
    }
    global  $wpdb ;
    if ( !(isset( $_GET['post'] ) || isset( $_POST['post'] ) || isset( $_REQUEST['action'] ) && 'qem_duplicate_post' == $_REQUEST['action']) ) {
        wp_die( esc_html__( 'No post to duplicate has been supplied!', 'quick-event-manager' ) );
    }
    $post_id = ( isset( $_GET['post'] ) ? (int) $_GET['post'] : (int) $_POST['post'] );
    $custom = get_post_custom( $post_id );
    $new_post_id = qem_create_post(
        $custom['event_date'][0],
        $post_id,
        'draft',
        true
    );
    wp_safe_redirect( admin_url( 'post.php?post=' . $new_post_id . '&action=edit' ) );
    exit;
}

add_action( 'admin_action_qem_duplicate', 'qem_duplicate' );
function duplicate_post( $actions, $post )
{
    
    if ( current_user_can( 'edit_events' ) && 'event' == get_post_type() ) {
        $url = wp_nonce_url( 'admin.php?action=qem_duplicate&amp;post=' . $post->ID, 'qem-duplicate-event' );
        $actions['duplicateevent'] = '<a href="' . $url . '" title="Duplicate this event" rel="permalink">' . esc_html__( 'Duplicate', 'quick-event-manager' ) . '</a>';
    }
    
    return $actions;
}

function qem_attendees( $actions, $post )
{
    
    if ( current_user_can( 'edit_events' ) && 'event' == get_post_type() ) {
        global  $post ;
        $title = get_the_title();
        $actions['attendees'] = '<a href="admin.php?page=qem-registration&event=' . $post->ID . '&title=' . $title . '">' . esc_html__( 'Registrations', 'quick-event-manager' ) . '</a>';
    }
    
    return $actions;
}

add_filter(
    'post_row_actions',
    'duplicate_post',
    10,
    2
);
add_filter(
    'post_row_actions',
    'qem_attendees',
    10,
    2
);