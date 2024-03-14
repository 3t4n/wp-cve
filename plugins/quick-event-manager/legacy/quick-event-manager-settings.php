<?php

use  Quick_Event_Manager\Plugin\Control\Admin_Template_Loader ;
add_action( "init", "qem_settings_init" );
add_action( "admin_menu", "event_page_init" );
add_action( "save_post", "save_event_details" );
add_action( "add_meta_boxes", "action_add_meta_boxes", 0 );
add_action( "manage_posts_custom_column", "event_custom_columns" );
add_filter( "manage_event_posts_columns", "event_edit_columns" );
add_filter( "manage_edit-event_sortable_columns", "event_column_register_sortable" );
add_action(
    "plugin_row_meta",
    "qem_plugin_row_meta",
    10,
    2
);
add_action( "pre_get_posts", "manage_wp_posts_be_qe_pre_get_posts", 1 );
function manage_wp_posts_be_qe_pre_get_posts( $query )
{
    if ( $query->is_main_query() && ($orderby = $query->get( 'orderby' )) ) {
        switch ( $orderby ) {
            case 'event_date':
                $query->set( 'meta_key', 'event_date' );
                $query->set( 'orderby', 'meta_value_num' );
                break;
            case 'event_location':
                $query->set( 'meta_key', 'event_location' );
                $query->set( 'orderby', 'meta_value' );
                break;
            case 'event_time':
                $query->set( 'meta_key', 'event_time' );
                $query->set( 'orderby', 'meta_value' );
                break;
            case 'number_coming':
                $query->set( 'meta_key', 'number_coming' );
                $query->set( 'orderby', 'meta_value' );
                break;
            case 'categories':
                $query->set( 'meta_key', 'categories' );
                $query->set( 'orderby', 'meta_value' );
                break;
        }
    }
}

function qem_tabbed_page()
{
    global  $qem_fs ;
    echo  '<h1>' . esc_html__( 'Quick Event Manager', 'quick-event-manager' ) . '</h1>' ;
    
    if ( isset( $_GET['tab'] ) ) {
        qem_admin_tabs( $_GET['tab'] );
        $tab = sanitize_text_field( $_GET['tab'] );
    } else {
        qem_admin_tabs( 'setup' );
        $tab = 'setup';
    }
    
    switch ( $tab ) {
        case 'setup':
            qem_setup();
            break;
        case 'settings':
            qem_event_settings();
            break;
        case 'display':
            qem_display_page();
            break;
        case 'calendar':
            qem_calendar();
            break;
        case 'styles':
            qem_styles();
            break;
        case 'register':
            qem_register();
            break;
        case 'payment':
            qem_payment();
            break;
        case 'template':
            break;
        case 'coupon':
            break;
        case 'auto':
            qem_autoresponse_page();
            break;
        case 'incontext':
            break;
        case 'guest':
            break;
        case 'reports':
            break;
        case 'report':
            break;
        case 'person':
            break;
        case 'import':
            break;
    }
}

function qem_admin_tabs( $current = 'settings' )
{
    global  $qem_fs ;
    $qemkey = get_option( 'qem_freemius_state' );
    $tabs = array(
        'setup'    => esc_html__( 'Setup', 'quick-event-manager' ),
        'settings' => esc_html__( 'Settings', 'quick-event-manager' ),
        'display'  => esc_html__( 'Display', 'quick-event-manager' ),
        'styles'   => esc_html__( 'Styling', 'quick-event-manager' ),
        'calendar' => esc_html__( 'Calendar', 'quick-event-manager' ),
        'register' => esc_html__( 'Registration', 'quick-event-manager' ),
        'auto'     => esc_html__( 'Auto Responder', 'quick-event-manager' ),
        'payment'  => esc_html__( 'Event Payment', 'quick-event-manager' ),
    );
    echo  '<div id="icon-themes" class="icon32"><br></div>' ;
    echo  '<h2 class="nav-tab-wrapper">' ;
    foreach ( $tabs as $tab => $name ) {
        if ( empty($name) ) {
            continue;
        }
        $class = ( $tab == $current ? ' nav-tab-active' : '' );
        echo  '<a class="nav-tab' . esc_attr( $class ) . '" href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=' . esc_attr( $tab ) . '">' . esc_html( $name ) . '</a>' ;
    }
    echo  '</h2>' ;
}

function qem_setup()
{
    global  $qem_fs ;
    $support_text = sprintf(
        __( 'or visit the community support forum %1$shere%2$s, if you require urgent or personalised support please upgrade to %3$sa paid plan here%2$s', 'quick-event-manager' ),
        '<a href="https://wordpress.org/support/plugin/quick-event-manager/" target="_blank">',
        '</a>',
        '<a href="' . esc_url( $qem_fs->get_upgrade_url() ) . '">'
    );
    echo  '<div class="qem-settings"><div class="qem-options">
    <h2>' . esc_html__( 'Setting up and using the plugin', 'quick-event-manager' ) . '</h2>
    <p><span style="color:red; font-weight:bold;">' . esc_html__( 'Important!', 'quick-event-manager' ) . '</span> ' . esc_html__( 'If you get an error when trying to view events, resave your', 'quick-event-manager' ) . ' <a href="options-permalink.php">permalinks</a>.</p>
    <p>' . esc_html__( 'Create new events using the', 'quick-event-manager' ) . ' <a href="edit.php?post_type=event">Events</a> ' . esc_html__( 'link on your dashboard menu', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'To display a list of events on your posts or pages use the shortcode: [qem]', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'If you prefer to display your events as a calendar use the shortcode', 'quick-event-manager' ) . ': [qemcalendar].</p>
    <p>' . esc_html__( 'More shortcodes on the right', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'That&#39;s pretty much it. All you need to do now is', 'quick-event-manager' ) . ' <a href="edit.php?post_type=event">' . esc_html__( 'create some events', 'quick-event-manager' ) . '</a>.</p>
    
    <h2>' . esc_html__( 'Help and Support', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'Use the Knowledge base at', 'quick-event-manager' ) . ' <a href="https://fullworksplugins.com/docs/quick-event-manager/" target="_blank">fullworksplugins.com/docs/quick-event-manager</a><p>' . qem_wp_kses_post( $support_text ) . '</p></p>
    </div>
    <div class="qem-options">' ;
    
    if ( !$qem_fs->can_use_premium_code() ) {
        $template_loader = new Admin_Template_Loader();
        $template_loader->set_template_data( array(
            'template_loader' => $template_loader,
            'freemius'        => $qem_fs,
        ) );
        $template_loader->get_template_part( 'upgrade_cta' );
        echo  qem_wp_kses_post( $template_loader->get_output() ) ;
    }
    
    echo  '<h2>' . esc_html__( 'Event Manager Role', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'There is a user role called <em>Event Manager</em>. Users with this role only have access to events, they cannot edit posts or pages.', 'quick-event-manager' ) . '</p>
    <h2>' . esc_html__( 'Settings', 'quick-event-manager' ) . '</h2>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=settings">' . esc_html__( 'Settings', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Select which fields are displayed in the event list and event page. Change actions and style of each field', 'quick-event-manager' ) . '</p>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=display">' . esc_html__( 'Display', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Edit event messages and display options', 'quick-event-manager' ) . '</p>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=styles">' . esc_html__( 'Styling', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Styling options for the date icon and overall event layout', 'quick-event-manager' ) . '</p>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=calendar">' . esc_html__( 'Calendar', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Show events as a calendar. Some styling and display options', 'quick-event-manager' ) . '.</p>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=register">' . esc_html__( 'Registration', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Add a registration form and attendee reports to your events', 'quick-event-manager' ) . '.</p>
<h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=auto">' . esc_html__( 'Auto Responder', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Set up an email responder for event registrations', 'quick-event-manager' ) . '.</p>
    <h3><a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=payment">' . esc_html__( 'Payment', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'Configure event payments', 'quick-event-manager' ) . '</p>
    <h3><a href="?page=qem-registration">' . esc_html__( 'Registration Reports', 'quick-event-manager' ) . '</a></h3>
    <p>' . esc_html__( 'View, edit and download event registrations', 'quick-event-manager' ) . '. ' . esc_html__( 'Access using the', 'quick-event-manager' ) . ' <strong>' . esc_html__( 'Registration', 'quick-event-manager' ) . '</strong> ' . esc_html__( 'link on your dashboard menu', 'quick-event-manager' ) . '.</p>' ;
    echo  '<h2>' . esc_html__( 'Primary Shortcodes', 'quick-event-manager' ) . '</h2>
    <table>
    <tbody>
    <tr>
    <td>[qem]</td>
    <td>' . esc_html__( 'Standard event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>[qemcalendar]</td>
    <td>' . esc_html__( 'Calendar view', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>[qem posts=\'99\']</td>
    <td>' . esc_html__( 'Set the number of events to display', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>[qem id=\'archive\']</td>
    <td>' . esc_html__( 'Show old events', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>[qem category=\'name\']</td>
    <td>' . esc_html__( 'List events by category', 'quick-event-manager' ) . '</td>
    </tr>
    </tbody>
    </table>
    <p>' . esc_html__( 'There are loads more shortcode options listed on the', 'quick-event-manager' ) . ' <a href="https://fullworksplugins.com/docs/quick-event-manager/usage-quick-event-manager/all-the-shortcodes/" target="_blank">' . esc_html__( 'Plugin Website', 'quick-event-manager' ) . '</a> (' . esc_html__( 'link opens in a new tab', 'quick-event-manager' ) . ').' ;
    echo  '</div></div>' ;
}

function qem_event_settings()
{
    $active_buttons = array(
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10',
        'field11',
        'field12',
        'field13',
        'field14',
        'field17'
    );
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        foreach ( $active_buttons as $item ) {
            $event['active_buttons'][$item] = ( (isset( $_POST['event_settings_active_' . $item] ) and $_POST['event_settings_active_' . $item] == 'on') ? true : false );
            $event['summary'][$item] = isset( $_POST['summary_' . $item] );
            $event['bold'][$item] = isset( $_POST['bold_' . $item] );
            $event['italic'][$item] = isset( $_POST['italic_' . $item] );
            $event['colour'][$item] = filter_var( qem_get_element( $_POST, 'colour_' . $item ), FILTER_SANITIZE_STRING );
            $event['size'][$item] = filter_var( qem_get_element( $_POST, 'size_' . $item ), FILTER_SANITIZE_STRING );
            
            if ( isset( $_POST['label_' . $item] ) && !empty($_POST['label_' . $item]) ) {
                $event['label'][$item] = sanitize_text_field( stripslashes( $_POST['label_' . $item] ) );
                $event['label'][$item] = filter_var( $event['label'][$item], FILTER_SANITIZE_STRING );
            }
        
        }
        $option = array(
            'sort',
            'description_label',
            'address_label',
            'url_label',
            'cost_label',
            'category_label',
            'start_label',
            'finish_label',
            'location_label',
            'organiser_label',
            'facebook_label',
            'twitter_label',
            'ics_label',
            'show_telephone',
            'target_link',
            'publicationdate',
            'whoscomingmessage',
            'whoscoming',
            'whosavatar',
            'oneplacebefore',
            'placesbefore',
            'placesafter',
            'oneattendingbefore',
            'numberattendingbefore',
            'numberattendingafter',
            'iflessthan'
        );
        foreach ( $option as $item ) {
            $event[$item] = stripslashes( qem_get_element( $_POST, $item ) );
            $event[$item] = filter_var( $event[$item], FILTER_SANITIZE_STRING );
        }
        update_option( 'event_settings', $event );
        qem_admin_notice( esc_html__( 'The form settings have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'event_settings' );
        $event = event_get_stored_options();
        update_option( 'event_settings', $event );
        qem_admin_notice( esc_html__( 'The event settings have been reset', 'quick-event-manager' ) );
    }
    
    $event = event_get_stored_options();
    echo  '<script>
    jQuery(function() {var qem_sort = jQuery( "#qem_sort" ).sortable({axis: "y",update:function(e,ui) {var order = qem_sort.sortable("toArray").join();jQuery("#qem_settings_sort").val(order);}});});
    </script>
    <div class ="qem-options" style="width:98%">
    <form id="event_settings_form" method="post" action="">
    <p>' . esc_html__( 'Use the check boxes to select which fields to display in the event post and the event list', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'Drag and drop to change the order of the fields', 'quick-event-manager' ) . '.</p>
    <table id="sorting">
    <thead>
    <tr>
    <th width="14%">' . esc_html__( 'Show in event post', 'quick-event-manager' ) . '</th>
    <th width="8%">' . esc_html__( 'Show in event list', 'quick-event-manager' ) . '</th>
    <th width="12%">' . esc_html__( 'Colour', 'quick-event-manager' ) . '</th>
    <th width="7%">' . esc_html__( 'Font size', 'quick-event-manager' ) . '</th>
    <th width="12%">' . esc_html__( 'Font attributes', 'quick-event-manager' ) . '</th>
    <th>' . esc_html__( 'Caption and display options', 'quick-event-manager' ) . ':</th>
    </tr>
    </thead><tbody id="qem_sort">' ;
    foreach ( explode( ',', $event['sort'] ) as $name ) {
        $li_class = ( qem_get_element( $event, array( 'active_buttons', $name ) ) ? 'button_active' : 'button_inactive' );
        $a = checked( qem_get_element( $event, array( 'active_buttons', $name ) ), true, false );
        $b = qem_get_element( $event, array( 'active_buttons', $name ) );
        echo  '<tr class="ui-state-default ' . esc_attr( $li_class ) . '" id="' . esc_attr( $name ) . '">
        <td><input type="checkbox" class="button_activate" name="event_settings_active_' . esc_attr( $name ) . '" ' . checked( qem_get_element( $event, array( 'active_buttons', $name ) ), true, false ) . ' /><b>' . esc_html( qem_get_element( $event, array( 'label', $name ) ) ) . '</b></td>
        <td>' ;
        if ( $name != 'field12' ) {
            echo  '<input type="checkbox" name="summary_' . esc_attr( $name ) . '" ' . checked( qem_get_element( $event, array( 'summary', $name ) ), true, false ) . ' />' ;
        }
        echo  '</td>' ;
        $exclude = array( 'field8', 'field12', 'field14' );
        
        if ( !in_array( $name, $exclude ) ) {
            echo  '<td><input type="text" class="qem-color" name="colour_' . esc_attr( $name ) . '" value ="' . esc_attr( qem_get_element( $event, array( 'colour', $name ) ) ) . '" /></td>
        	<td><input type="text" style="width:3em;border: 1px solid #343838;" name="size_' . esc_attr( $name ) . '" value ="' . esc_attr( qem_get_element( $event, array( 'size', $name ) ) ) . '" />%</td>
        	<td><input type="checkbox" name="bold_' . esc_attr( $name ) . '" ' . checked( qem_get_element( $event, array( 'bold', $name ) ), true, false ) . ' /> ' . esc_html__( 'Bold', 'quick-event-manager' ) . ' <input type="checkbox" name="italic_' . esc_attr( $name ) . '" ' . checked( qem_get_element( $event, array( 'italic', $name ) ), true, false ) . ' /> ' . esc_html__( 'Italics', 'quick-event-manager' ) . '</td>
       		<td>' ;
            qem_options_e( $name, $event );
            echo  '</td>' ;
        } else {
            echo  '<td colspan="5">' ;
            qem_options_e( $name, $event );
            echo  '</td>' ;
        }
        
        echo  '</tr>' ;
    }
    echo  '</tbody></table>
    <h2>Publication Date</h2>
    <p><input type="checkbox" name="publicationdate" value="checked" ' . checked( qem_get_element( $event, 'publicationdate' ), 'checked', false ) . ' /></td><td> ' . esc_html__( 'Make publication date the same as the event date', 'quick-event-manager' ) . '</p>
    <input type="hidden" id="qem_settings_sort" name="sort" value="' . esc_attr( qem_get_element( $event, 'sort' ) ) . '" />
	<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \' ' . esc_html__( 'Are you sure you want to reset the display settings?', 'quick-event-manager' ) . '\' );"/></p>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    <h2>' . esc_html__( 'Shortcode and Widget Field Selection', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'If you want a custom layout for a specific list you can use the shortcode [qem fields=1,2,5].', 'quick-event-manager' ) . ' ' . esc_html__( 'On the', 'quick-event-manager' ) . '<a href="/wp-admin/widgets.php">' . esc_html__( 'widget', 'quick-event-manager' ) . '</a> ' . esc_html__( 'just enter the field numbers separated with commas.', 'quick-event-manager' ) . '<p>
    <p>' . esc_html__( 'The numbers correspond to the fields like this', 'quick-event-manager' ) . ': <p>
    <ol>
    <li>' . esc_html__( 'Short description', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Event Time', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Venue', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Address', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Website', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Cost', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Organiser', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Full description', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Places Taken', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Attendees', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Places Available', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Registration Form', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Category', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Sharing', 'quick-event-manager' ) . '</li>
    </ol>
    <p>' . esc_html__( 'The order of the fields and other options is set using the drag and drop selectors above', 'quick-event-manager' ) . '</p></div>' ;
}

function qem_options_e( $name, $event )
{
    switch ( $name ) {
        case 'field1':
            echo  '<input type="text" style="border:1px solid blue; width:10em;" name="description_label" . value ="' . esc_attr( qem_get_element( $event, 'description_label' ) ) . '" /> {' . esc_html__( 'description', 'quick-event-manager' ) . '}' ;
            break;
        case 'field2':
            echo  '<input type="text" style="width:10em;" name="start_label" . value ="' . esc_attr( qem_get_element( $event, 'start_label' ) ) . '" /> {' . esc_html__( 'start time', 'quick-event-manager' ) . '} <input type="text" style="border:1px solid blue; width:10em;" name="finish_label" . value ="' . esc_attr( qem_get_element( $event, 'finish_label' ) ) . '" /> {' . esc_html__( 'end time', 'quick-event-manager' ) . '}' ;
            break;
        case 'field3':
            echo  '<input type="text" style="width:6em;" name="location_label" . value ="' . esc_attr( qem_get_element( $event, 'location_label' ) ) . '" /> {' . esc_html__( 'venue', 'quick-event-manager' ) . '}' ;
            break;
        case 'field4':
            echo  '<input type="text" style="width:10em;" name="address_label" . value ="' . esc_attr( qem_get_element( $event, 'address_label' ) ) . '" /> {' . esc_html__( 'address', 'quick-event-manager' ) . '}' ;
            break;
        case 'field5':
            echo  '<input type="text" style="width:10em;" name="url_label" . value ="' . esc_attr( qem_get_element( $event, 'url_label' ) ) . '" /> {url}' ;
            break;
        case 'field6':
            echo  '<input type="text" style="width:8em;" name="cost_label" . value ="' . esc_attr( qem_get_element( $event, 'cost_label' ) ) . '" /> {' . esc_html__( 'cost', 'quick-event-manager' ) . '} (<input type="text" style="width:8em;" name="deposit_before_label" . value ="' . esc_attr( qem_get_element( $event, 'deposit_before_label' ) ) . '" /> {deposit} <input type="text" style="width:8em;" name="deposit_after_label" . value ="' . esc_attr( qem_get_element( $event, 'deposit_after_label' ) ) . '" />)' ;
            break;
        case 'field7':
            echo  '<input type="text" style="width:10em;" name="organiser_label" . value ="' . esc_attr( qem_get_element( $event, 'organiser_label' ) ) . '" /> {' . esc_html__( 'organiser', 'quick-event-manager' ) . '}&nbsp;<input type="checkbox" name="show_telephone"' . esc_attr( qem_get_element( $event, 'show_telephone' ) ) . ' value="checked" /> ' . esc_html__( 'Show organiser\'s contact details', 'quick-event-manager' ) . ' ' ;
            break;
        case 'field8':
            echo  esc_html__( 'The contents of the event detail editing box.', 'quick-event-manager' ) ;
            break;
        case 'field9':
            echo  '<input type="text" style="width:40%;" name="oneattendingbefore" value="' . esc_attr( qem_get_element( $event, 'oneattendingbefore' ) ) . '" /><br>
            <input type="text" style="width:40%; " name="numberattendingbefore" value="' . esc_attr( qem_get_element( $event, 'numberattendingbefore' ) ) . '" /> {number} <input type="text" style="width:40%; " name="numberattendingafter" value="' . esc_attr( qem_get_element( $event, 'numberattendingafter' ) ) . '" />' ;
            break;
        case 'field10':
            echo  '<input type="text" style="width:10em;" name="whoscomingmessage" value="' . esc_attr( qem_get_element( $event, 'whoscomingmessage' ) ) . '" />&nbsp;<input type="checkbox" name="whoscoming" ' . esc_attr( qem_get_element( $event, 'whoscoming' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Show names', 'quick-event-manager' ) . '&nbsp;<input type="checkbox" name="whosavatar" ' . esc_attr( qem_get_element( $event, 'whosavatar' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Show Avatar', 'quick-event-manager' ) . '' ;
            break;
        case 'field11':
            echo  '<input type="text" style="width:40%;" name="oneplacebefore" value="' . esc_attr( qem_get_element( $event, 'oneplacebefore' ) ) . '" /><br>
            <input type="text" style="width:40%;" name="placesbefore" value="' . esc_attr( qem_get_element( $event, 'placesbefore' ) ) . '" /> {number} <input type="text" style="width:40%;" name="placesafter" value="' . esc_attr( qem_get_element( $event, 'placesafter' ) ) . '" /><br>
            ' . esc_html__( 'Only show message if less than', 'quick-event-manager' ) . ' <input type="text" style="width:3em" name="iflessthan" value="' . esc_attr( qem_get_element( $event, 'iflessthan' ) ) . '" /> ' . esc_html__( 'places available', 'quick-event-manager' ) . '. <span class="description">Leave blank to show on all events</span>' ;
            break;
        case 'field12':
            echo  esc_html__( 'Enable the registration form', 'quick-event-manager' ) . '.&nbsp;<a style="color:blue;text-decoration:underline;" href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=register">' . esc_html__( 'Registration form settings', 'quick-event-manager' ) . '</a><br>
            <span class="description">' . esc_html__( 'To add a registration form to individual events use the event editor', 'quick-event-manager' ) . '.</span>' ;
            break;
        case 'field13':
            echo  '<input type="text" style="border:1px solid blue; width:10em;" name="category_label" . value ="' . esc_attr( qem_get_element( $event, 'category_label' ) ) . '" /> {' . esc_html__( 'category', 'quick-event-manager' ) . '}' ;
            break;
        case 'field14':
            echo  'Facebook: <input type="text" style="border:1px solid blue; width:10em;" name="facebook_label" . value ="' . esc_attr( qem_get_element( $event, 'facebook_label' ) ) . '" /> Twitter: <input type="text" style="border:1px solid blue; width:10em;" name="twitter_label" . value ="' . esc_attr( qem_get_element( $event, 'twitter_label' ) ) . '" /> Calendar Download: <input type="text" style="border:1px solid blue; width:10em;" name="ics_label" . value ="' . esc_attr( qem_get_element( $event, 'ics_label' ) ) . '" />' ;
            break;
    }
}

function qem_display_page()
{
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        $option = array(
            'show_end_date',
            'read_more',
            'noevent',
            'event_archive',
            'event_descending',
            'external_link',
            'external_link_target',
            'linkpopup',
            'recentposts',
            'event_image',
            'back_to_list',
            'back_to_list_caption',
            'back_to_url',
            'show_map',
            'map_width',
            'map_height',
            'map_in_list',
            'map_and_image',
            'map_and_image_size',
            'map_target',
            'event_image_width',
            'image_width',
            'usefeatured',
            'combined',
            'monthheading',
            'monthheadingorder',
            'useics',
            'uselistics',
            'useicsbutton',
            'usetimezone',
            'timezonebefore',
            'timezoneafter',
            'amalgamated',
            'vertical',
            'norepeat',
            'monthtype',
            'categorylocation',
            'showcategoryintitle',
            'readmorelink',
            'titlelink',
            'max_width',
            'loginlinks',
            'lightboxwidth',
            'fullpopup',
            'eventgrid',
            'eventmasonry',
            'eventgridborder',
            'fullevent',
            'categorydropdown',
            'categorydropdownlabel',
            'categorydropdownwidth',
            'linktocategories',
            'showuncategorised',
            'showkeyabove',
            'showkeybelow',
            'keycaption',
            'showcategory',
            'showcategorycaption',
            'catallevents',
            'catalleventscaption',
            'cat_border',
            'linktocategories',
            'showuncategorised',
            'catalinkslug',
            'catalinkurl',
            'apikey'
        );
        foreach ( $option as $item ) {
            $display[$item] = stripslashes( qem_get_element( $_POST, $item ) );
            $display[$item] = filter_var( qem_get_element( $display, $item ), FILTER_SANITIZE_STRING );
        }
        update_option( 'qem_display', $display );
        qem_admin_notice( esc_html__( 'The display settings have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_display' );
        qem_admin_notice( esc_html__( 'The display settings have been reset', 'quick-event-manager' ) );
    }
    
    $masonry = $traditional = $short = $full = $title = $date = $my = $ym = '';
    $display = event_get_stored_display();
    if ( isset( $display['show_end_date'] ) ) {
        ${$display['show_end_date']} = 'checked';
    }
    ${$display['localization']} = 'selected';
    ${$display['monthtype']} = 'checked';
    ${$display['monthheadingorder']} = 'checked';
    ${$display['categorylocation']} = 'checked';
    if ( isset( $display['eventmasonry'] ) ) {
        ${$display['eventmasonry']} = 'checked';
    }
    echo  '<div class="qem-settings">
    <div class="qem-options">
    <form id="event_settings_form" method="post" action="">
    <table>

    <tr>
    <td colspan="2"><h2>' . esc_html__( 'End Date Display', 'quick-event-manager' ) . '</h2></td>
    </tr>

    <tr>
    <td width="5%"><input type="checkbox" name="show_end_date" value="checked" ' . esc_attr( qem_get_element( $display, 'show_end_date' ) ) . ' /></td><td width="95%"> ' . esc_html__( 'Show end date in event list', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="combined" value="checked" ' . esc_attr( qem_get_element( $display, 'combined' ) ) . ' /></td><td> ' . esc_html__( 'Combine Start and End dates into one box', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="amalgamated" value="checked" ' . esc_attr( qem_get_element( $display, 'amalgamated' ) ) . ' /></td><td> ' . esc_html__( 'Show combined Start and End dates if in the same month', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="norepeat" value="checked" ' . esc_attr( qem_get_element( $display, 'norepeat' ) ) . ' /></td><td> ' . esc_html__( 'Only show icon on first event if more than one event on that day', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="vertical" value="checked" ' . esc_attr( qem_get_element( $display, 'vertical' ) ) . ' /></td><td> ' . esc_html__( 'Show start and end dates above one another', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Event Messages', 'quick-event-manager' ) . '</h2></td>
    </tr>

    <tr>
    <td colspan="2">' . esc_html__( 'Read more caption', 'quick-event-manager' ) . ': <input type="text" style="width:20em;" label="read_more" name="read_more" value="' . esc_attr( qem_get_element( $display, 'read_more' ) ) . '" /></td>
    </tr>

    <tr>
    <td colspan="2">' . esc_html__( 'No events message', 'quick-event-manager' ) . ': <input type="text" style="width:20em;" label="noevent" name="noevent" value="' . esc_attr( qem_get_element( $display, 'noevent' ) ) . '" /></td>
    </tr>

    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Event List Options', 'quick-event-manager' ) . '</h2></td>
    </tr>

    <tr>
    <td><input type="checkbox" name="fullevent" value="checked" ' . esc_attr( qem_get_element( $display, 'fullevent' ) ) . ' /></td>
    <td> ' . esc_html__( 'Show full event details in the event list ( includes registration form when enabled )', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="event_descending" value="checked" ' . esc_attr( qem_get_element( $display, 'event_descending' ) ) . ' /></td>
    <td> ' . esc_html__( 'List events in reverse order (from future to past)', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="event_archive" value="checked" ' . esc_attr( qem_get_element( $display, 'event_archive' ) ) . ' /></td>
    <td> ' . esc_html__( 'Show past events in the events list', 'quick-event-manager' ) . '<br><span class="description">' . esc_html__( 'If you only want to display past events use the shortcode: [qem id="archive"]', 'quick-event-manager' ) . '.</span></td>
    </tr>

    <tr>
    <td><input type="checkbox" name="monthheading" value="checked" ' . esc_attr( qem_get_element( $display, 'monthheading' ) ) . ' /></td>
    <td> ' . esc_html__( 'Split the list into month/year sections', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td></td>
    <td><input type="radio" name="monthtype" value="short" ' . esc_attr( $short ) . ' /> ' . esc_html__( 'Short (Aug)', 'quick-event-manager' ) . ' <input type="radio" name="monthtype" value="full" ' . esc_attr( $full ) . ' /> ' . esc_html__( 'Full (August)', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td></td>
    <td>' . esc_html__( 'Order', 'quick-event-manager' ) . ': <input type="radio" name="monthheadingorder" value="my" ' . esc_attr( $my ) . ' /> Month Year <input type="radio" name="monthheadingorder" value="ym" ' . esc_attr( $ym ) . ' /> Year Month</td>
    </tr>

    <tr>
    <td><input type="checkbox" name="recentposts"' . esc_attr( qem_get_element( $display, 'recentposts' ) ) . ' value="checked" /></td>
    <td>' . esc_html__( 'Show events in recent posts list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="showcategoryintitle" value="checked" ' . esc_attr( qem_get_element( $display, 'showcategoryintitle' ) ) . ' /></td>
    <td> ' . esc_html__( 'Show category', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td><input type="radio" name="categorylocation" value="title" ' . esc_attr( $title ) . ' /> ' . esc_html__( 'Next to title', 'quick-event-manager' ) . ' <input type="radio" name="categorylocation" value="date" ' . esc_attr( $date ) . ' /> ' . esc_html__( 'Next to date (if no icon styling)', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="eventgrid" value="checked" ' . esc_attr( qem_get_element( $display, 'eventgrid' ) ) . ' /></td>
    <td> ' . esc_html__( 'Show as grid', 'quick-event-manager' ) . '<br><span class="description">' . esc_html__( 'Using this option will disable the date icon styling, month and year sections, images and maps', 'quick-event-manager' ) . '</span>.</td>
    </tr>
    <tr>
    <td></td>
    <td><input type="radio" name="eventmasonry" value="traditional" ' . esc_attr( $traditional ) . ' /> ' . esc_html__( 'Traditional', 'quick-event-manager' ) . ' <input type="radio" name="eventmasonry" value="masonry" ' . esc_attr( $masonry ) . ' /> ' . esc_html__( 'Show as tiled (Pinterest type)', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Event Border:', 'quick-event-manager' ) . ' <input type="text" style="width:10em;" label="eventgridborder" name="eventgridborder" value="' . esc_attr( qem_get_element( $display, 'eventgridborder' ) ) . '" />&nbsp;eg: 1px solid red</td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Download to Calendar', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'This allows users to download the event as a calendar file', 'quick-event-manager' ) . '.</p></td>
    </tr>
    <tr>
    <td><input type="checkbox" name="useics" value="checked" ' . esc_attr( qem_get_element( $display, 'useics' ) ) . ' /></td>
    <td>' . esc_html__( 'Add download button to event', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="uselistics" value="checked" ' . esc_attr( qem_get_element( $display, 'uselistics' ) ) . ' /></td>
    <td> ' . esc_html__( 'Add download button to event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Button text:', 'quick-event-manager' ) . ' <input type="text" style="width:50%;" label="useicsbutton" name="useicsbutton" value="' . esc_attr( qem_get_element( $display, 'useicsbutton' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Event Linking Options', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td><input type="checkbox" name="external_link" value="checked" ' . esc_attr( qem_get_element( $display, 'external_link' ) ) . ' /></td>
    <td> ' . esc_html__( 'Link to external website from event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="external_link_target"' . esc_attr( qem_get_element( $display, 'external_link_target' ) ) . ' value="checked" /></td>
    <td>' . esc_html__( 'Open external links in new tab/page', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="linkpopup"' . esc_attr( qem_get_element( $display, 'linkpopup' ) ) . ' value="checked" /></td>
    <td>' . esc_html__( 'Open event in lightbox', 'quick-event-manager' ) . ' (' . esc_html__( 'Warning: doesn\'t always behave as expected on small screens', 'quick-event-manager' ) . ').</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Width:', 'quick-event-manager' ) . ' <input type="text" style="width:3em;" label="lightboxwidth" name="lightboxwidth" value="' . esc_attr( qem_get_element( $display, 'lightboxwidth' ) ) . '" />%</td>
    </tr>
    <tr>
    <td></td>
    <td><input type="checkbox" name="fullpopup" value="checked" ' . esc_attr( qem_get_element( $display, 'fullpopup' ) ) . ' />&nbsp;' . esc_html__( 'Show full event details in popup', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="titlelink" value="checked" ' . esc_attr( qem_get_element( $display, 'titlelink' ) ) . ' /></td>
    <td> ' . esc_html__( 'Remove link from event title and event image', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="readmorelink" value="checked" ' . esc_attr( qem_get_element( $display, 'readmorelink' ) ) . ' /></td>
    <td> ' . esc_html__( 'Hide Read More link', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="loginlinks" value="checked" ' . esc_attr( qem_get_element( $display, 'loginlinks' ) ) . ' /></td>
    <td> ' . esc_html__( 'Hide links to event if not logged in', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="back_to_list" value="checked" ' . esc_attr( qem_get_element( $display, 'back_to_list' ) ) . ' /></td>
    <td> ' . esc_html__( 'Add a link to events to go back to the event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Enter URL to link to a specific page. Leave blank to just go back one page', 'quick-event-manager' ) . ':<br>
    <input type="text" style="" label="back_to_url" name="back_to_url" value="' . esc_attr( qem_get_element( $display, 'back_to_url' ) ) . '" /></td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Link caption', 'quick-event-manager' ) . ': <input type="text" style="width:50%;" label="back_to_list_caption" name="back_to_list_caption" value="' . esc_attr( qem_get_element( $display, 'back_to_list_caption' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Maps and Images', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'If you have an event image it will display automatically on the event page', 'quick-event-manager' ) . '</p><td>
    </tr>
    <tr>
    <td><input type="checkbox" name="event_image" value="checked" ' . esc_attr( qem_get_element( $display, 'event_image' ) ) . ' /></td>
    <td>' . esc_html__( 'Show event image in event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="show_map" value="checked" ' . esc_attr( qem_get_element( $display, 'show_map' ) ) . ' /></td>
    <td>' . esc_html__( 'Show map on event page', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="map_in_list" value="checked" ' . esc_attr( qem_get_element( $display, 'map_in_list' ) ) . ' /></td>
    <td>' . esc_html__( 'Show map in event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="usefeatured" value="checked" ' . esc_attr( qem_get_element( $display, 'usefeatured' ) ) . ' /></td>
    <td>' . esc_html__( 'Use featured images', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="2"><b>' . esc_html__( 'Notes:', 'quick-event-manager' ) . '</b><br>1. ' . esc_html__( 'The map will only display if you have a valid address.', 'quick-event-manager' ) . '<br>2.
    ' . esc_html__( 'To display a Google Map you need a valid ', 'quick-event-manager' ) . ' <a href="https://support.google.com/cloud/answer/6158862" target="_blank">Google API Key</a>.</td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Google API Key:', 'quick-event-manager' ) . '<input type="text" label="apikey" name="apikey" value="' . esc_attr( qem_get_element( $display, 'apikey' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Image and Map Width', 'quick-event-manager' ) . ': <input type="text" style=" width:3em; padding: 1px; margin:0;" name="event_image_width" . value ="' . esc_attr( qem_get_element( $display, 'event_image_width' ) ) . '" /> px<br>
    <span class="description">' . esc_html__( 'This is the maximum width of Map and Image on large screens.', 'quick-event-manager' ) . '</span></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Max Width', 'quick-event-manager' ) . ': <input type="text" style=" width:3em; padding: 1px; margin:0;" name="max_width" . value ="' . esc_attr( qem_get_element( $display, 'max_width' ) ) . '" />%<br>
    <span class="description">' . esc_html__( 'This is the maximum width of Map and Image compared to the whole event on smaller screens.', 'quick-event-manager' ) . '</span></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Event list width', 'quick-event-manager' ) . ': <input type="text" style=" width:3em; padding: 1px; margin:0;" name="image_width" value ="' . esc_attr( qem_get_element( $display, 'image_width' ) ) . '" /> px</td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Map Height', 'quick-event-manager' ) . ': <input type="text" style=" width:3em; padding: 1px; margin:0;" name="map_height" . value ="' . esc_attr( qem_get_element( $display, 'map_height' ) ) . '" /> px</td>
    </tr>
    <tr>
    <td><input type="checkbox" name="map_target" value="checked" ' . esc_attr( qem_get_element( $display, 'map_target' ) ) . ' /></td>
    <td>' . esc_html__( 'Open map in new tab/window', 'quick-event-manager' ) . '</td>
    </tr>
    </table>
    <table>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Categories', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Display category dropdown', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="categorydropdown" ' . esc_attr( qem_get_element( $display, 'categorydropdown' ) ) . ' value="checked" /> ' . esc_html__( 'Displays a list of all categories', 'quick-event-manager' ) . '<br>
    Label: <input type="text" style="" label="text" name="categorydropdownlabel" value="' . esc_attr( qem_get_element( $display, 'categorydropdownlabel' ) ) . '" /><br>
    <input type="checkbox" name="categorydropdownwidth" ' . esc_attr( qem_get_element( $display, 'categorydropdownwidth' ) ) . ' value="checked" /> ' . esc_html__( 'Full width dropdown.', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Display category key', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="showkeyabove" ' . esc_attr( qem_get_element( $display, 'showkeyabove' ) ) . ' value="checked" /> ' . esc_html__( 'Show above event list', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="showkeybelow" ' . esc_attr( qem_get_element( $display, 'showkeybelow' ) ) . ' value="checked" /> ' . esc_html__( 'Show below event list', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Caption', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" label="text" name="keycaption" value="' . esc_attr( qem_get_element( $display, 'keycaption' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Add link back to all events', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="catallevents" ' . esc_attr( qem_get_element( $display, 'catallevents' ) ) . ' value="checked" /><br><span class="description">' . esc_html__( 'This uses the URL set on the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=display">' . esc_html__( 'Event List', 'quick-event-manager' ) . '</a> ' . esc_html__( 'page', 'quick-event-manager' ) . '.</span></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Caption', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" label="text" name="catalleventscaption" value="' . esc_attr( qem_get_element( $display, 'catalleventscaption' ) ) . '" /></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Category Colours', 'quick-event-manager' ) . '</td><td><input type="checkbox" name="cat_border"' . esc_attr( qem_get_element( $display, 'cat_border' ) ) . ' value="checked" /> ' . esc_html__( 'Use category colours for the event border', 'quick-event-manager' ) . '<br />
    <span class="description">' . esc_html__( 'Options are set on the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=calendar">' . esc_html__( 'Calendar Settings', 'quick-event-manager' ) . '</a> ' . esc_html__( 'page', 'quick-event-manager' ) . '.</span></td>
    </tr>
    <tr>
    <td width="30%"></td><td><input type="checkbox" name="showcategory" ' . esc_attr( qem_get_element( $display, 'showcategory' ) ) . ' value="checked" /> ' . esc_html__( 'Show name of current category', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%"></td>
    <td>' . esc_html__( 'Current category label', 'quick-event-manager' ) . ':<br><input type="text" style="" label="text" name="showcategorycaption" value="' . esc_attr( qem_get_element( $display, 'showcategorycaption' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Linking', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="linktocategories" ' . esc_attr( qem_get_element( $display, 'linktocategories' ) ) . ' value="checked" /> ' . esc_html__( 'Link keys to categories', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="showuncategorised" ' . esc_attr( qem_get_element( $display, 'showuncategorised' ) ) . ' value="checked" /> ' . esc_html__( 'Show uncategorised key', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Category Linking Option', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Use this option to link from the event list to a URL for a category.', 'quick-event-manager' ) . ' ' . esc_html__( 'Seperate using a comma for multiple catagories', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Category slug', 'quick-event-manager' ) . '</td>
    <td><input type="text" name="catalinkslug" value="' . esc_attr( qem_get_element( $display, 'catalinkslug' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'URL', 'quick-event-manager' ) . '</td>
    <td><input type="text" name="catalinkurl" value="' . esc_attr( qem_get_element( $display, 'catalinkurl' ) ) . '" /></td>
    </tr>
    </table>
    <table>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Timezones', 'quick-event-manager' ) . '</h2></td>
    </tr>

    <tr>
    <td><input type="checkbox" name="usetimezone"' . esc_attr( qem_get_element( $display, 'usetimezone' ) ) . ' value="checked" /></td>
    <td>' . esc_html__( 'Show timeszones on your events', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="2"><input type="text" style="width:40%;" name="timezonebefore" value="' . esc_attr( qem_get_element( $display, 'timezonebefore' ) ) . '" /> {' . esc_html__( 'timezone', 'quick-event-manager' ) . '} <input type="text" style="width:40%;" name="timezoneafter" value="' . esc_attr( qem_get_element( $display, 'timezoneafter' ) ) . '" /><br>
    <span class="description">' . esc_html__( 'This doesn\'t change the time of the event, it just shows the name of the local timeszone. Set the event timezone in the event editor.', 'quick-event-manager' ) . '</span></td>
    </tr>
    <tr>
    <td colspan="2"><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \' ' . esc_html__( 'Are you sure you want to reset the display settings?', 'quick-event-manager' ) . '\' );"/></td>
    </tr>
    </table>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    </div>
    <div class="qem-options   qem-preview">
    <h2>' . esc_html__( 'Event List Preview', 'quick-event-manager' ) . '</h2>' ;
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  qem_event_shortcode_esc function returns escaped outputs as used in multiple places shortcode, widget admin previews etc https://developer.wordpress.org/apis/security/escaping/#toc_4
    echo  qem_event_shortcode_esc( array(
        'posts' => '3',
    ), '' ) ;
    echo  '</div></div>' ;
}

function qem_styles()
{
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'font',
            'font-family',
            'font-size',
            'header-size',
            'header-colour',
            'width',
            'widthtype',
            'event_background',
            'event_backgroundhex',
            'date_colour',
            'date_background',
            'date_backgroundhex',
            'month_background',
            'month_backgroundhex',
            'month_colour',
            'use_custom',
            'custom',
            'date_bold',
            'date_italic',
            'date_border_width',
            'date_border_colour',
            'calender_size',
            'event_border',
            'icon_corners',
            'event_margin',
            'line_margin',
            'use_dayname',
            'use_dayname_inline',
            'iconorder',
            'vanilla',
            'vanillamonth',
            'vanilladay',
            'vanillaontop',
            'vanillawidget',
            'uselabels',
            'startlabel',
            'finishlabel'
        );
        foreach ( $options as $item ) {
            $style[$item] = stripslashes( qem_get_element( $_POST, $item ) );
            $style[$item] = filter_var( $style[$item], FILTER_SANITIZE_STRING );
        }
        $arr = array(
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j'
        );
        foreach ( $arr as $i ) {
            $style['cat' . $i] = sanitize_text_field( qem_get_element( $_POST, 'cat' . $i ) );
            $style['cat' . $i . 'back'] = sanitize_text_field( qem_get_element( $_POST, 'cat' . $i . 'back' ) );
            $style['cat' . $i . 'text'] = sanitize_text_field( qem_get_element( $_POST, 'cat' . $i . 'text' ) );
        }
        update_option( 'qem_style', $style );
        qem_admin_notice( esc_html__( 'The form styles have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_style' );
        qem_admin_notice( esc_html__( 'The style settings have been reset', 'quick-event-manager' ) );
    }
    
    $pixel = $percent = $plugin = $theme = $small = $medium = $large = $square = $rounded = $month = $year = $dm = $md = $grey = $red = $color = $category = '';
    $bgwhite = $bgtheme = $bgcolor = $colour = $mwhite = $php = $head = '';
    $style = qem_get_stored_style();
    ${$style['font']} = 'checked';
    ${$style['widthtype']} = 'checked';
    ${$style['background']} = 'checked';
    ${$style['event_background']} = 'checked';
    ${$style['date_background']} = 'checked';
    ${$style['month_background']} = 'checked';
    ${$style['icon_corners']} = 'checked';
    ${$style['iconorder']} = 'checked';
    ${$style['calender_size']} = 'checked';
    echo  '<div class="qem-settings">
    <div class="qem-options">
    <form method="post" action="">
    <table>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Event Width', 'quick-event-manager' ) . '</h2></td></tr>
    <tr>
    <td colspan="2"><input type="radio" name="widthtype" value="percent" ' . esc_attr( $percent ) . ' /> ' . esc_html__( '100% (fill the available space)', 'quick-event-manager' ) . '<br />
    <input type="radio" name="widthtype" value="pixel" ' . esc_attr( $pixel ) . ' /> ' . esc_html__( 'Pixel (fixed)', 'quick-event-manager' ) . '<br />
    ' . esc_html__( 'Enter the max-width ', 'quick-event-manager' ) . ': <input type="text" style="width:4em;" label="width" name="width" value="' . esc_attr( qem_get_element( $style, 'width' ) ) . '" />px ' . esc_html__( '(Just enter the value, no need to add \'px\')', 'quick-event-manager' ) . '.</td></tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Font Options', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td colspan="2"><input type="radio" name="font" value="theme" ' . esc_attr( $theme ) . ' /> ' . esc_html__( 'Use your theme font styles', 'quick-event-manager' ) . '<br />
	<input type="radio" name="font" value="plugin" ' . esc_attr( $plugin ) . ' /> ' . esc_html__( 'Use Plugin font styles (enter font family and size below)', 'quick-event-manager' ) . '</td></tr>
    <tr>
    <td>' . esc_html__( 'Font Family', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="" label="font-family" name="font-family" value="' . esc_attr( qem_get_element( $style, 'font-family' ) ) . '" /></td></tr>
    <tr>
    <td>' . esc_html__( 'Font Size', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="width:4em;" label="font-size" name="font-size" value="' . esc_attr( qem_get_element( $style, 'font-size' ) ) . '" /><br>
    <span class="description">This is the base font size, you can set the sizes of each part of the listing in the Settings.</span></td></tr>
    <tr>
    <td>' . esc_html__( 'Header Size', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="width:4em;" label="header-size" name="header-size" value="' . esc_attr( qem_get_element( $style, 'header-size' ) ) . '" /> ' . esc_html__( 'This the size of the title in the event list', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Header Colour', 'quick-event-manager' ) . ':</td>
    <td><input type="text" class="qem-color" label="header-colour" name="header-colour" value="' . esc_attr( qem_get_element( $style, 'header-colour' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Calender Icon', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Remove styles', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="vanilla"' . esc_attr( qem_get_element( $style, 'vanilla' ) ) . ' value="checked" /> ' . esc_html__( 'Do not style the calendar icon', 'quick-event-manager' ) . '<span class="description">(' . esc_html__( 'Also removes the event border', 'quick-event-manager' ) . ').</span><br>
    &emsp;&emsp;&emsp;&emsp;&emsp;<input type="checkbox" name="vanilladay" ' . esc_attr( qem_get_element( $style, 'vanilladay' ) ) . ' value="checked" /> ' . esc_html__( 'Show the full day name', 'quick-event-manager' ) . '
    <br>
    &emsp;&emsp;&emsp;&emsp;&emsp;<input type="checkbox" name="vanillamonth" ' . esc_attr( qem_get_element( $style, 'vanillamonth' ) ) . ' value="checked" /> ' . esc_html__( 'Show the full month name', 'quick-event-manager' ) . '
    <br>
    &emsp;&emsp;&emsp;&emsp;&emsp;<input type="checkbox" name="vanillaontop" ' . esc_attr( qem_get_element( $style, 'vanillaontop' ) ) . ' value="checked" /> ' . esc_html__( 'Show date above event name', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Size', 'quick-event-manager' ) . '</td>
    <td>
	<input type="radio" name="calender_size" value="small" ' . esc_attr( $small ) . ' /> ' . esc_html__( 'Small', 'quick-event-manager' ) . ' (40px)<br />
	<input type="radio" name="calender_size" value="medium" ' . esc_attr( $medium ) . ' /> ' . esc_html__( 'Medium', 'quick-event-manager' ) . ' (60px)<br />
	<input type="radio" name="calender_size" value="large" ' . esc_attr( $large ) . ' /> ' . esc_html__( 'Large', 'quick-event-manager' ) . '(80px)</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Corners', 'quick-event-manager' ) . '</td>
    <td>
    <input type="radio" name="icon_corners" value="square" ' . esc_attr( $square ) . ' /> ' . esc_html__( 'Square', 'quick-event-manager' ) . '&nbsp;
    <input type="radio" name="icon_corners" value="rounded" ' . esc_attr( $rounded ) . ' /> ' . esc_html__( 'Rounded', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Border Thickness', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:2em;" label="calendar border" name="date_border_width" value="' . esc_attr( qem_get_element( $style, 'date_border_width' ) ) . '" /> px</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Border Colour', 'quick-event-manager' ) . ':</td>
    <td><input type="text" class="qem-color" label="calendar border" name="date_border_colour" value="' . esc_attr( qem_get_element( $style, 'date_border_colour' ) ) . '" /><br><span class="description">' . esc_html__( 'There is an option below to use category colours for the icon border', 'quick-event-manager' ) . '.</span></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Calendar Icon Order', 'quick-event-manager' ) . '</td>
    <td>
    <input type="radio" name="iconorder" value="default" ' . esc_attr( $default ) . ' /> ' . esc_html__( 'DMY', 'quick-event-manager' ) . '&nbsp;<input type="radio" name="iconorder" value="month" ' . esc_attr( $month ) . ' /> ' . esc_html__( 'MDY', 'quick-event-manager' ) . '&nbsp;
    <input type="radio" name="iconorder" value="year" ' . esc_attr( $year ) . ' /> ' . esc_html__( 'YDM', 'quick-event-manager' ) . '&nbsp;
    <input type="radio" name="iconorder" value="dm" ' . esc_attr( $dm ) . ' /> ' . esc_html__( 'DM', 'quick-event-manager' ) . '&nbsp;<input type="radio" name="iconorder" value="md" ' . esc_attr( $md ) . ' /> ' . esc_html__( 'MD', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Start/Finish Labels', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="uselabels"' . esc_attr( qem_get_element( $style, 'uselabels' ) ) . ' value="checked" /> ' . esc_html__( 'Show start/finish labels', 'quick-event-manager' ) . '<br>
    ' . esc_html__( 'Start', 'quick-event-manager' ) . ': <input type="text" style="width:7em;" name="startlabel" value="' . esc_attr( qem_get_element( $style, 'startlabel' ) ) . '" /> ' . esc_html__( 'Finish', 'quick-event-manager' ) . ': <input type="text" style="width:7em;" name="finishlabel" value="' . esc_attr( qem_get_element( $style, 'finishlabel' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Day Name', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="use_dayname"' . esc_attr( qem_get_element( $style, 'use_dayname' ) ) . ' value="checked" /> ' . esc_html__( 'Show day name', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="use_dayname_inline"' . esc_attr( qem_get_element( $style, 'use_dayname_inline' ) ) . ' value="checked" /> ' . esc_html__( 'Show day name inline with date', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Date Background Colour', 'quick-event-manager' ) . '</td>
    <td>
	<input type="radio" name="date_background" value="grey" ' . esc_attr( $grey ) . ' /> ' . esc_html__( 'Grey', 'quick-event-manager' ) . '<br />
	<input type="radio" name="date_background" value="red" ' . esc_attr( $red ) . ' /> ' . esc_html__( 'Red', 'quick-event-manager' ) . '<br />
    <input type="radio" name="date_background" value="category" ' . esc_attr( $category ) . ' /> ' . esc_html__( 'Category Border', 'quick-event-manager' ) . '<br />
	<input type="radio" name="date_background" value="color" ' . esc_attr( $color ) . ' /> ' . esc_html__( 'Set your own', 'quick-event-manager' ) . '<br />
    <input type="text" class="qem-color" label="background" name="date_backgroundhex" value="' . esc_attr( qem_get_element( $style, 'date_backgroundhex' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Date Text Colour', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="date colour" name="date_colour" value="' . esc_attr( qem_get_element( $style, 'date_colour' ) ) . '" /></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Month Background Colour', 'quick-event-manager' ) . '</td>
    <td>
	<input type="radio" name="month_background" value="mwhite" ' . esc_attr( $mwhite ) . ' /> ' . esc_html__( 'White', 'quick-event-manager' ) . '<br />
	<input type="radio" name="month_background" value="colour" ' . esc_attr( $colour ) . ' /> ' . esc_html__( 'Set your own', 'quick-event-manager' ) . '<br />
    <input type="text" class="qem-color" name="month_backgroundhex" value="' . esc_attr( qem_get_element( $style, 'month_backgroundhex' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Month Text Colour', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="month colour" name="month_colour" value="' . esc_attr( qem_get_element( $style, 'month_colour' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Month Text Style', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="date_bold" value="checked" ' . esc_attr( qem_get_element( $style, 'date_bold' ) ) . ' /> ' . esc_html__( 'Bold', 'quick-event-manager' ) . '&nbsp;
	<input type="checkbox" name="date_italic" value="checked" ' . esc_attr( qem_get_element( $style, 'date_italic' ) ) . ' /> ' . esc_html__( 'Italic', 'quick-event-manager' ) . '</td>
    </tr>
	<tr>
    <td colspan="2"><h2>' . esc_html__( 'Event Content', 'quick-event-manager' ) . '</h2></td>
    </tr>
	<tr>
    <td style="vertical-align:top;">' . esc_html__( 'Event Border', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="event_border"' . esc_attr( qem_get_element( $style, 'event_border' ) ) . ' value="checked" /> ' . esc_html__( 'Add a border to the event post', 'quick-event-manager' ) . '<br /><span class="description">' . esc_html__( 'Thickness and colour will be the same as the calendar icon', 'quick-event-manager' ) . '.</span></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Event Background Colour', 'quick-event-manager' ) . '</td>
    <td><input type="radio" name="event_background" value="bgwhite" ' . esc_attr( $bgwhite ) . ' /> ' . esc_html__( 'White', 'quick-event-manager' ) . '<br />
	<input type="radio" name="event_background" value="bgtheme" ' . esc_attr( $bgtheme ) . ' /> ' . esc_html__( 'Use theme colours', 'quick-event-manager' ) . '<br />
	<input type="radio" name="event_background" value="bgcolor" ' . esc_attr( $bgcolor ) . ' /> ' . esc_html__( 'Set your own', 'quick-event-manager' ) . '<br />
	<input type="text" class="qem-color" label="background" name="event_backgroundhex" value="' . esc_attr( qem_get_element( $style, 'event_backgroundhex' ) ) . '" /></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Margins and Padding', 'quick-event-manager' ) . '</td>
    <td><span class="description">' . esc_html__( 'Set the margins and padding of each bit using CSS shortcodes', 'quick-event-manager' ) . ':</span><br><input type="text" label="line margin" name="line_margin" value="' . esc_attr( qem_get_element( $style, 'line_margin' ) ) . '" /></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Event Margin', 'quick-event-manager' ) . '</td>
    <td><span class="description">' . esc_html__( 'Set the margin or each event using CSS shortcodes', 'quick-event-manager' ) . ':</span><br>
    <input type="text" label="margin" name="event_margin" value="' . esc_attr( qem_get_element( $style, 'event_margin' ) ) . '" /></td>
    </tr>
    </table>
    <h2>' . esc_html__( 'Event Category Colours', 'quick-event-manager' ) . '</h2>
    <div class="qem-calcolor">
    <table>
    <tr>
    <th>' . esc_html__( 'Category', 'quick-event-manager' ) . '</th><th colspan="1">' . esc_html__( 'Background', 'quick-event-manager' ) . '</th><th colspan="1">' . esc_html__( 'Text', 'quick-event-manager' ) . '</th>
 <tr>' ;
    $arr = array(
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j'
    );
    foreach ( $arr as $i ) {
        echo  '<tr><td>' ;
        qem_categories_e( 'cat' . $i, qem_get_element( $style, 'cat' . $i ) );
        echo  '</td>
        <td><input type="text" class="qem-color" label="cat' . esc_attr( $i ) . 'back" name="cat' . esc_attr( $i ) . 'back" value="' . esc_attr( qem_get_element( $style, 'cat' . $i . 'back' ) ) . '" /></td>
        <td><input type="text" class="qem-color" 1Glabel="cat' . esc_attr( $i ) . 'text" name="cat' . esc_attr( $i ) . 'text" value="' . esc_attr( qem_get_element( $style, 'cat' . $i . 'text' ) ) . '" /></td></tr>' ;
    }
    echo  '</table></div>
	<p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \'' . esc_html__( 'Are you sure you want to reset the style settings?', 'quick-event-manager' ) . '\' );"/></p>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    </div>
    </div>
    <div class="qem-options qem-preview">
    <h2>' . esc_html__( 'Event List Preview', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'Check the event list in your site as the Wordpress Dashboard can do funny things with styles', 'quick-event-manager' ) . '</p>' ;
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  qem_event_shortcode_esc function returns escaped outputs as used in multiple places shortcode, widget admin previews etc https://developer.wordpress.org/apis/security/escaping/#toc_4
    echo  qem_event_shortcode_esc( array(
        'posts' => '3',
    ), '' ) ;
    echo  '</div>' ;
}

function qem_calendar()
{
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'calday',
            'caldaytext',
            'day',
            'eventday',
            'oldday',
            'eventhover',
            'eventdaytext',
            'eventlink',
            'connect',
            'calendar_text',
            'calendar_url',
            'eventlist_text',
            'eventlist_url',
            'startday',
            'eventlength',
            'archive',
            'archivelinks',
            'smallicon',
            'unicode',
            'eventbold',
            'eventitalic',
            'eventbackground',
            'eventtext',
            'eventtextsize',
            'trigger',
            'eventborder',
            'showmultiple',
            'keycaption',
            'showkeyabove',
            'showkeybelow',
            'prevmonth',
            'nextmonth',
            'navicon',
            'leftunicode',
            'rightunicode',
            'linktocategories',
            'showuncategorised',
            'cellspacing',
            'tdborder',
            'header',
            'headerorder',
            'headerstyle',
            'eventimage',
            'imagewidth',
            'usetooltip',
            'event_corner',
            'fixeventborder',
            'showmonthsabove',
            'showmonthsbelow',
            'monthscaption',
            'hidenavigation',
            'jumpto',
            'calallevent',
            'calalleventscaption',
            'fullpopup',
            'attendeeflag',
            'attendeeflagcontent',
            'catstyle'
        );
        foreach ( $options as $item ) {
            $cal[$item] = stripslashes( qem_get_element( $_POST, $item ) );
            $cal[$item] = filter_var( $cal[$item], FILTER_SANITIZE_STRING );
        }
        update_option( 'qem_calendar', $cal );
        qem_admin_notice( esc_html__( 'The calendar settings have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_calendar' );
        qem_admin_notice( esc_html__( 'The calendar settings have been reset', 'quick-event-manager' ) );
    }
    
    $linkpage = $colorAsBorder = $rounded = $my = $ym = $linkpopup = $none = $arrows = $unicodes = $h2 = $h3 = $h4 = $trim = $arrow = $box = $square = $asterix = $blank = $other = $sunday = $monday = '';
    $colorAsBG = 'checked';
    $calendar = qem_get_stored_calendar();
    ${$calendar['eventlink']} = 'checked';
    if ( isset( $calendar['catstyle'] ) ) {
        ${$calendar['catstyle']} = 'checked';
    }
    ${$calendar['startday']} = 'checked';
    ${$calendar['smallicon']} = 'checked';
    ${$calendar['navicon']} = 'checked';
    ${$calendar['header']} = 'checked';
    ${$calendar['event_corner']} = 'checked';
    ${$calendar['headerorder']} = 'checked';
    
    if ( $calendar['navicon'] == 'arrows' ) {
        $leftnavicon = '&#9668; ';
        $rightnavicon = ' &#9658;';
    }
    
    
    if ( $calendar['navicon'] == 'unicodes' ) {
        $leftnavicon = $cal['leftunicode'] . ' ';
        $rightnavicon = ' ' . $cal['rightunicode'];
    }
    
    echo  '<div class="qem-settings"><div class="qem-options">
    <h2>' . esc_html__( 'Using the Calendar', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'To add a calendar to your site use the shortcode: [qemcalendar]', 'quick-event-manager' ) . '.</p>
    <form method="post" action="">
    <table width="100%">
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'General Settings', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Linking to Events', 'quick-event-manager' ) . '</td>
    <td><input type="radio" name="eventlink" value="linkpage" ' . esc_attr( $linkpage ) . ' /> ' . esc_html__( 'Link opens event page', 'quick-event-manager' ) . '<br />
    <input type="radio" name="eventlink" value="linkpopup" ' . esc_attr( $linkpopup ) . ' /> ' . esc_html__( 'Link opens event summary in a popup', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="fullpopup" value="checked" ' . esc_attr( qem_get_element( $calendar, 'fullpopup' ) ) . ' />' . esc_html__( 'Show full event details in popup.', 'quick-event-manager' ) . '
    </td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Old Events', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="archive" ' . esc_attr( qem_get_element( $calendar, 'archive' ) ) . ' value="checked" /> ' . esc_html__( 'Show past events in the calendar', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Linking Calendar to the Event List', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="connect"' . esc_attr( qem_get_element( $calendar, 'connect' ) ) . ' value="checked" /> ' . esc_html__( 'Link Event List to Calendar Page', 'quick-event-manager' ) . '.<br>
    <span class="description">' . esc_html__( 'You will need to create pages for the calendar and the event list', 'quick-event-manager' ) . '.</span>
    </td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Calendar link text', 'quick-event-manager' ) . '</td><td><input type="text" style="" label="calendar_text" name="calendar_text" value="' . esc_attr( qem_get_element( $calendar, 'calendar_text' ) ) . '" /></td></tr>
    <tr><td width="30%">' . esc_html__( 'Calendar page URL', 'quick-event-manager' ) . '</td><td><input type="text" style="" label="calendar_url" name="calendar_url" value="' . esc_attr( qem_get_element( $calendar, 'calendar_url' ) ) . '" /></td></tr>
    <tr><td width="30%">' . esc_html__( 'Event list link text', 'quick-event-manager' ) . '</td><td><input type="text" style="" label="eventlist_text" name="eventlist_text" value="' . esc_attr( qem_get_element( $calendar, 'eventlist_text' ) ) . '" /></td></tr>
    <tr>
    <td width="30%">' . esc_html__( 'Event list page', 'quick-event-manager' ) . ' URL</td>
    <td><input type="text" style="" label="eventlist_url" name="eventlist_url" value="' . esc_attr( qem_get_element( $calendar, 'eventlist_url' ) ) . '" /></td></tr>
    <tr>
    <td width="30%">Navigation Labels</td>
    <td><input type="text" style="width:50%;" label="text" name="prevmonth" value="' . esc_attr( qem_get_element( $calendar, 'prevmonth' ) ) . '" /><input type="text" style="text-align:right;width:50%;" label="text" name="nextmonth" value="' . esc_attr( qem_get_element( $calendar, 'nextmonth' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Navigation Icons', 'quick-event-manager' ) . '</td>
    <td>
    <input type="radio" name="navicon" value="none" ' . esc_attr( $none ) . ' /> ' . esc_html__( 'None', 'quick-event-manager' ) . '
    <input type="radio" name="navicon" value="arrows" ' . esc_attr( $arrows ) . ' /> &#9668; &#9658;
    <input type="radio" name="navicon" value="unicodes" ' . esc_attr( $unicodes ) . ' />' . esc_html__( 'Other', 'quick-event-manager' ) . ' (' . esc_html__( 'enter', 'quick-event-manager' ) . ' <a href="http://character-code.com/arrows-html-codes.php" target="_blank">' . esc_html__( 'hex code', 'quick-event-manager' ) . '</a> ' . esc_html__( 'below', 'quick-event-manager' ) . ').<br />
    Left: <input type="text" style="width:6em;" label="text" name="leftunicode" value="' . esc_attr( qem_get_element( $calendar, 'leftunicode' ) ) . '" /> Right: <input type="text" style="width:6em;" label="text" name="rightunicode" value="' . esc_attr( qem_get_element( $calendar, 'rightunicode' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Jump to links', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="jumpto"' . esc_attr( qem_get_element( $calendar, 'jumpto' ) ) . ' value="checked" /> ' . esc_html__( 'Jump to the top of the calendar when linking to a new month.', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Calendar Options', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Month and Date Header', 'quick-event-manager' ) . ':</td>
    <td><input type="radio" name="header" value="h2" ' . esc_attr( $h2 ) . ' /> H2 <input type="radio" name="header" value="h3" ' . esc_attr( $h3 ) . ' /> H3 <input type="radio" name="header" value="h4" ' . esc_attr( $h4 ) . ' /> H4<br>
Header CSS:<br>
    <input type="text" style="" name="headerstyle" value="' . esc_attr( qem_get_element( $calendar, 'headerstyle' ) ) . '" /></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Order', 'quick-event-manager' ) . ':</td>
    <td><input type="radio" name="headerorder" value="my" ' . esc_attr( $my ) . ' /> ' . esc_html__( 'Month Year', 'quick-event-manager' ) . '<input type="radio" name="headerorder" value="ym" ' . esc_attr( $ym ) . ' /> ' . esc_html__( 'Year Month', 'quick-event-manager' ) . '</td>
    </tr>

    <tr>
    <td width="30%">' . esc_html__( 'Day Border', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:12em;" label="tdborder" name="tdborder" value="' . esc_attr( qem_get_element( $calendar, 'tdborder' ) ) . '" /> ' . esc_html__( 'Example', 'qiock-event-manager' ) . ': 1px solid red</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Cellspacing', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:2em;" label="cellspacing" name="cellspacing" value="' . esc_attr( qem_get_element( $calendar, 'cellspacing' ) ) . '" /> px</td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Months', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Display 12 month navigation', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="showmonthsabove" ' . esc_attr( qem_get_element( $calendar, 'showmonthsabove' ) ) . ' value="checked" /> ' . esc_html__( 'Show above calendar', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="showmonthsbelow" ' . esc_attr( qem_get_element( $calendar, 'showmonthsbelow' ) ) . ' value="checked" /> ' . esc_html__( 'Show below calendar', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Caption', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" label="text" name="monthscaption" value="' . esc_attr( qem_get_element( $calendar, 'monthscaption' ) ) . '" /></td>
    </tr>

    <tr>
    <td width="30%">' . esc_html__( 'Hide navigation', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="hidenavigation" ' . esc_attr( qem_get_element( $calendar, 'hidenavigation' ) ) . ' value="checked" /> ' . esc_html__( 'Remove Prev and Next links', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="2"><h2>' . esc_html__( 'Event Options', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Multi-day Events', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="showmultiple" ' . esc_attr( qem_get_element( $calendar, 'showmultiple' ) ) . ' value="checked" /> ' . esc_html__( 'Show event on all days', 'quick-event-manager' ) . '<span class="description"> Disables event image (if used)</span></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Event Border', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:12em;" label="eventborder" name="eventborder" value="' . esc_attr( qem_get_element( $calendar, 'eventborder' ) ) . '" /> ' . esc_html__( 'enter \'none\' to remove border', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%"></td>
    <td><input type="checkbox" name="fixeventborder" ' . esc_attr( qem_get_element( $calendar, 'fixeventborder' ) ) . ' value="checked" /> ' . esc_html__( 'Lock border colour (ignore category colours)', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Corners', 'quick-event-manager' ) . '</td>
    <td>
    <input type="radio" name="event_corner" value="square" ' . esc_attr( $square ) . ' /> ' . esc_html__( 'Square', 'quick-event-manager' ) . '&nbsp;
    <input type="radio" name="event_corner" value="rounded" ' . esc_attr( $rounded ) . ' /> ' . esc_html__( 'Rounded', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Text Styles', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="eventbold" ' . esc_attr( qem_get_element( $calendar, 'eventbold' ) ) . ' value="checked" /> ' . esc_html__( 'Bold', 'quick-event-manager' ) . '<input type="checkbox" name="eventitalic" ' . esc_attr( qem_get_element( $calendar, 'eventitalic' ) ) . ' value="checked" /> ' . esc_html__( 'Italic', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Event Image', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="eventimage" ' . esc_attr( qem_get_element( $calendar, 'eventimage' ) ) . ' value="checked" /> ' . esc_html__( 'Show event image on the calendar', 'quick-event-manager' ) . '<br>' . esc_html__( 'Image Width', 'quick-event-manager' ) . '<input type="text" style="width:3em;" label="text" name="imagewidth" value="' . esc_attr( qem_get_element( $calendar, 'imagewidth' ) ) . '" /> px</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Hover Message', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="usetooltip" ' . esc_attr( qem_get_element( $calendar, 'usetooltip' ) ) . ' value="checked" /> ' . esc_html__( 'Show full event title on hover', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Character Number', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:4em;" label="text" name="eventlength" value="' . esc_attr( qem_get_element( $calendar, 'eventlength' ) ) . '" /><span class="description"> Number of characters to display in event box</span></td>
    </tr>
    <tr>
    <td style="vertical-align:top;">' . esc_html__( 'Small Screens', 'quick-event-manager' ) . '</td>
    <td><span class="description">' . esc_html__( 'What to display on small screens', 'quick-event-manager' ) . ':</span><br>
    <input type="radio" name="smallicon" value="trim" ' . esc_attr( $trim ) . ' /> ' . esc_html__( 'Full message', 'quick-event-manager' ) . ' <input type="radio" name="smallicon" value="arrow" ' . esc_attr( $arrow ) . ' /> ' . '&#9654;' . ' <input type="radio" name="smallicon" value="box" ' . esc_attr( $box ) . ' /> ' . '&#9633;' . ' <input type="radio" name="smallicon" value="square" ' . esc_attr( $square ) . ' /> ' . '&#9632;' . ' <input type="radio" name="smallicon" value="asterix" ' . esc_attr( $asterix ) . ' /> ' . '&#9733;' . '
    <input type="radio" name="smallicon" value="blank" ' . esc_attr( $blank ) . ' /> ' . esc_html__( 'Blank', 'quick-event-manager' ) . '
    <input type="radio" name="smallicon" value="other" ' . esc_attr( $other ) . ' /> ' . esc_html__( 'Other', 'quick-event-manager' ) . ' (' . esc_html__( 'enter escaped', 'quick-event-manager' ) . ' <a href="http://www.fileformat.info/info/unicode/char/search.htm" target="blank">unicode</a> ' . esc_html__( 'or hex code below', 'quick-event-manager' ) . ').<br />
    <input type="text" style="width:6em;" label="text" name="unicode" value="' . esc_attr( qem_get_element( $calendar, 'unicode' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Small Screens', 'quick-event-manager' ) . ' ' . esc_html__( 'Text Size', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:3em;" name="eventtextsize" value="' . esc_attr( qem_get_element( $calendar, 'eventtextsize' ) ) . '" />&nbsp;' . esc_html__( 'Trigger Width', 'quick-event-manager' ) . ':&nbsp;<input type="text" style="width:5em;" name="trigger" value="' . esc_attr( qem_get_element( $calendar, 'trigger' ) ) . '" /><br>
    <span class="description">' . esc_html__( 'This is the text size when the screen is below the trigger width', 'quick-event-manager' ) . '</span></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Flag Attendees', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="attendeeflag" ' . esc_attr( qem_get_element( $calendar, 'attendeeflag' ) ) . ' value="checked" /> Label: <input type="text" style="width:10em;" name="attendeeflagcontent" value="' . esc_attr( qem_get_element( $calendar, 'attendeeflagcontent' ) ) . '" /><br>
    <span class="description">' . esc_html__( 'Adds a marker on the event if people have registered', 'quick-event-manager' ) . '. ' . esc_html__( 'Defaults to a dot if field is left blank', 'quick-event-manager' ) . '</span></td>
    </tr>
    </table>

    <h2>' . esc_html__( 'Calendar Colours', 'quick-event-manager' ) . '</h2>
    <div class="qem-calcolor">
    <table>
    <tr>
    <th>' . esc_html__( 'Category', 'quick-event-manager' ) . '</th>
    <th colspan="2">' . esc_html__( 'Background XX', 'quick-event-manager' ) . ' / ' . esc_html__( 'Text', 'quick-event-manager' ) . '</th>
    
   </tr>

    <tr>
    <td>' . esc_html__( 'Days of the Week', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="calday" value="' . esc_attr( qem_get_element( $calendar, 'calday' ) ) . '" /></td>
    <td><input type="text" class="qem-color" label="text" name="caldaytext" value="' . esc_attr( qem_get_element( $calendar, 'caldaytext' ) ) . '" /></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Normal Day', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="day" value="' . esc_attr( qem_get_element( $calendar, 'day' ) ) . '" /></td>
    <td></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Event Day', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="eventday" value="' . esc_attr( qem_get_element( $calendar, 'eventday' ) ) . '" /></td>
    <td></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Event', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="eventbackground" value="' . esc_attr( qem_get_element( $calendar, 'eventbackground' ) ) . '" /></td>
    <td><input type="text" class="qem-color" label="text" name="eventtext" value="' . esc_attr( qem_get_element( $calendar, 'eventtext' ) ) . '" /></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Event Hover', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="eventhover" value="' . esc_attr( qem_get_element( $calendar, 'eventhover' ) ) . '" /></td>
    <td></td>
    </tr>

    <tr>
    <td>' . esc_html__( 'Past Day', 'quick-event-manager' ) . '</td>
    <td><input type="text" class="qem-color" label="background" name="oldday" value="' . esc_attr( qem_get_element( $calendar, 'oldday' ) ) . '" /></td>
    <td></td>
    </tr>
    </table>
    </div>

    <h2>' . esc_html__( 'Categories', 'quick-event-manager' ) . '</h2>
    <table width="100%">
    <tr>
    <td width="30%">' . esc_html__( 'Display category key', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="showkeyabove" ' . esc_attr( qem_get_element( $calendar, 'showkeyabove' ) ) . ' value="checked" /> ' . esc_html__( 'Show above calendar', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="showkeybelow" ' . esc_attr( qem_get_element( $calendar, 'showkeybelow' ) ) . ' value="checked" /> ' . esc_html__( 'Show below calendar', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Caption:', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" label="text" name="keycaption" value="' . esc_attr( qem_get_element( $calendar, 'keycaption' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="30%"></td><td><input type="checkbox" name="linktocategories" ' . esc_attr( qem_get_element( $calendar, 'linktocategories' ) ) . ' value="checked" /> ' . esc_html__( 'Link keys to categories', 'quick-event-manager' ) . '<br>
    <input type="checkbox" name="showuncategorised" ' . esc_attr( qem_get_element( $calendar, 'showuncategorised' ) ) . ' value="checked" /> ' . esc_html__( 'Show uncategorised key', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Category link style', 'quick-event-manager' ) . '</td>
    <td><input type="radio" name="catstyle" value="colorAsBG" ' . esc_attr( $colorAsBG ) . ' /> ' . esc_html__( 'Use Category color as background color', 'quick-event-manager' ) . '<br />
    <input type="radio" name="catstyle" value="colorAsBorder" ' . esc_attr( $colorAsBorder ) . ' /> ' . esc_html__( 'Use Category color as border color', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Add link back to all categories', 'quick-event-manager' ) . '</td>
    <td><input type="checkbox" name="calallevents" ' . esc_attr( qem_get_element( $calendar, 'calallevents' ) ) . ' value="checked" /><br><span class="description">' . esc_html__( 'This uses the Calendar page URL set at the top of this page', 'quick-event-manager' ) . '.</span></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Caption', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" label="text" name="calalleventscaption" value="' . esc_attr( qem_get_element( $calendar, 'calalleventscaption' ) ) . '" /></td>
    </tr>
    </table>
    <h2>' . esc_html__( 'Start the Week', 'quick-event-manager' ) . '</h2>
    <p><input type="radio" name="startday" value="sunday" ' . esc_attr( $sunday ) . ' /> ' . esc_html__( 'On Sunday', 'quick-event-manager' ) . '<br />
    <input type="radio" name="startday" value="monday" ' . esc_attr( $monday ) . ' /> ' . esc_html__( 'On Monday', 'quick-event-manager' ) . '</p>
    <p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \'' . esc_html__( 'Are you sure you want to reset the calendar settings?', 'quick-event-manager' ) . '\' );"/></p>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    </div>
    <div class="qem-options">
    <h2>' . esc_html__( 'Calendar Preview', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'The', 'quick-event-manager' ) . ' <em>' . esc_html__( 'prev', 'quick-event-manager' ) . '</em> ' . esc_html__( 'and', 'quick-event-manager' ) . ' <em>' . esc_html__( 'next', 'quick-event-manager' ) . '</em> ' . esc_html__( 'buttons only work on your Posts and Pages - so don\'t click on them!', 'quick-event-manager' ) . '</p>' ;
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped --  qem_show_calendar_esc function returns escaped outputs as used in multiple places shortcode, widget admin previews etc https://developer.wordpress.org/apis/security/escaping/#toc_4
    echo  qem_show_calendar_esc( '' ) ;
    echo  '</div></div>' ;
}

function qem_categories_e( $catxxx, $thecat )
{
    $arr = get_categories();
    echo  '<select name="' . esc_attr( $catxxx ) . '" style="width:8em;">' ;
    echo  '<option value=""></option>' ;
    foreach ( $arr as $option ) {
        
        if ( $thecat == $option->slug ) {
            $selected = 'selected';
        } else {
            $selected = '';
        }
        
        echo  '<option value="' . esc_attr( $option->slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $option->name ) . '</option>' ;
    }
    echo  '</select>' ;
}

function qem_register()
{
    global  $qem_fs ;
    $corner = '';
    $none = '';
    $processpercent = $processfixed = $qem_apikey = $addtoall = $formwidth = $notarchive = false;
    
    if ( isset( $_POST['Settings'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'addtoall',
            'formwidth',
            'notarchive',
            'useqpp',
            'usename',
            'usemail',
            'usetelephone',
            'useplaces',
            'usemessage',
            'useattend',
            'usecaptcha',
            'useblank1',
            'useblank2',
            'usedropdown',
            'useselector',
            'usenumber1',
            'usechecks',
            'usechecksradio',
            'usedonation',
            'reqname',
            'reqmail',
            'reqtelephone',
            'reqmessage',
            'reqblank1',
            'reqblank2',
            'reqdropdown',
            'reqnumber1',
            'formborder',
            'sendemail',
            'qemmail',
            'subject',
            'subjecttitle',
            'subjectdate',
            'title',
            'blurb',
            'yourname',
            'youremail',
            'yourtelephone',
            'yourplaces',
            'placesposition',
            'yourmessage',
            'yourcaptcha',
            'yourattend',
            'yourblank1',
            'yourblank1textarea',
            'yourblank2',
            'yourblank2textarea',
            'yourdropdown',
            'yourselector',
            'yournumber1',
            'checkslabel',
            'donation',
            'checkslist',
            'useaddinfo',
            'useoptin',
            'addinfo',
            'captchalabel',
            'qemsubmit',
            'error',
            'replytitle',
            'replyblurb',
            'replydeferred',
            'eventfull',
            'eventfullmessage',
            'eventlist',
            'showuser',
            'linkback',
            'usecopy',
            'copyblurb',
            'optinblurb',
            'alreadyregistered',
            'useread_more',
            'read_more',
            'sort',
            'registeredusers',
            'allowmultiple',
            'nameremoved',
            'checkremoval',
            'useterms',
            'termslabel',
            'termsurl',
            'termstarget',
            'ontheright',
            'usemorenames',
            'morenames',
            'ignorepayment',
            'ignorepaymentlabel',
            'nonotifications',
            'waitinglist',
            'waitinglistreply',
            'waitinglistmessage',
            'moderate',
            'moderatereply',
            'moderateplaces',
            'placesavailable',
            'copychecked',
            'redirectionurl',
            'listnames',
            'listblurb',
            'hideform',
            'paypaladdinfo'
        );
        foreach ( $options as $item ) {
            $register[$item] = stripslashes( sanitize_text_field( qem_get_element( $_POST, $item, false ) ) );
            
            if ( in_array( $item, array( 'replyblurb' ) ) ) {
                //  textareas and allowed html
                $register[$item] = qem_wp_kses_post( $register[$item] );
            } else {
                $register[$item] = sanitize_text_field( $register[$item] );
            }
        
        }
        update_option( 'qem_register', $register );
        qem_admin_notice( esc_html__( 'The registration form settings have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_register' );
        qem_admin_notice( esc_html__( 'The registration form settings have been reset', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Styles'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'font-colour',
            'text-font-colour',
            'input-border',
            'input-required',
            'inputbackground',
            'inputfocus',
            'border',
            'form-width',
            'submit-background',
            'submit-hover-background',
            'submitwidth',
            'submitwidthset',
            'submitposition',
            'submit-border',
            'background',
            'backgroundhex',
            'corners',
            'form-border',
            'header-type',
            'header-size',
            'header-colour',
            'error-font-colour',
            'error-border',
            'line_margin',
            'nostyling'
        );
        foreach ( $options as $item ) {
            $style[$item] = stripslashes( qem_get_element( $_POST, $item ) );
            $style[$item] = filter_var( $style[$item], FILTER_SANITIZE_STRING );
        }
        
        if ( qem_get_element( $style, 'form-width', false ) ) {
            $formwidth = preg_split( '#(?<=\\d)(?=[a-z%])#i', qem_get_element( $style, 'form-width' ) );
            if ( false === $formwidth && !$formwidth[1] ) {
                $formwidth[1] = 'px';
            }
            $style['width'] = $formwidth[0] . $formwidth[1];
        }
        
        update_option( 'qem_register_style', $style );
        qem_admin_notice( "The form styles have been updated." );
    }
    
    
    if ( isset( $_POST['Resetstyles'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_register_style' );
        qem_admin_notice( "The form styles have been reset." );
    }
    
    $style = qem_get_register_style();
    $wpmail = 'checked';
    $right = $left = $yourblank1textarea = $yourblank2textarea = '';
    $plain = $rounded = $shadow = $roundshadow = '';
    $theme = $white = $color = '';
    $h3 = $h4 = $square = $round = '';
    $submitpercent = $submitrandom = $submitpixel = $submitleft = $submitright = '';
    if ( isset( $style['widthtype'] ) ) {
        ${$style['widthtype']} = 'checked';
    }
    ${$style['submitwidth']} = 'checked';
    ${$style['submitposition']} = 'checked';
    ${$style['border']} = 'checked';
    ${$style['background']} = 'checked';
    ${$style['corners']} = 'checked';
    ${$style['header-type']} = 'checked';
    if ( isset( $style['placesposition'] ) ) {
        ${$style['placesposition']} = 'checked';
    }
    $register = qem_get_stored_register();
    $qemkey = get_option( 'qem_freemius_state' );
    ${$register['qemmail']} = 'checked';
    ${$register['placesposition']} = 'checked';
    if ( isset( $register['yourblank1textarea'] ) ) {
        ${$register['yourblank1textarea']} = 'checked';
    }
    if ( isset( $register['yourblank2textarea'] ) ) {
        ${$register['yourblank2textarea']} = 'checked';
    }
    echo  '<div class="qem-settings"><div class="qem-options">
    <form id="" method="post" action="">
    <p>' . esc_html__( 'Use the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=settings">Settings</a> ' . esc_html__( 'Settings', 'quick-event-manager' ) . '</a> ' . esc_html__( 'to enable the registration form. You can then manage forms for individual events using the event editor', 'quick-event-manager' ) . '</p>
    <table width="100%">
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'General Settings', 'quick-event-manager' ) . '</h2></td></tr>
    <tr>
    <td width="5%"><input type="checkbox" name="addtoall"' . esc_attr( qem_get_element( $register, 'addtoall' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Add form to all new events', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="hideform"' . esc_attr( qem_get_element( $register, 'hideform' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Hide form until registration button is clicked', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="ontheright"' . esc_attr( qem_get_element( $register, 'ontheright' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Display the registration form on the right below the event image and map (if used)', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="notarchive" ' . esc_attr( qem_get_element( $register, 'notarchive' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Do not display registration form on old events', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="showuser" ' . esc_attr( qem_get_element( $register, 'showuser' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Pre-fill user name if logged in', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="registeredusers" ' . esc_attr( qem_get_element( $register, 'registeredusers' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Only users who have logged in can register', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="allowmultiple" ' . esc_attr( qem_get_element( $register, 'allowmultiple' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Allow a person to register more than once for each event ( note: if someone registers for a paid event but fails to complete payment they will still be able to re-register to complete payment)', 'quick-event-manager' ) . '.</td>
    </tr> 
    <tr>
    <td width="5%"><input type="checkbox" name="eventfull" ' . esc_attr( qem_get_element( $register, 'eventfull' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Hide registration form when event is full', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Message to display', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="" name="eventfullmessage" value="' . esc_attr( qem_get_element( $register, 'eventfullmessage' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><a href="#styling">' . esc_html__( 'Click Here for Form Styling', 'quick-event-manager' ) . '</a></td>
    </tr>' ;
    echo  '<tr><td colspan="3">' ;
    $fs = array_key_exists( 'fwantispam_fs', $GLOBALS );
    
    if ( $fs ) {
        global  $fwantispam_fs ;
        
        if ( $fwantispam_fs->can_use_premium_code() ) {
            echo  '<h2>' . esc_html__( 'Anti Spam Protection', 'quick-event-manager' ) . '</h2>
<div style="border: 1px solid black; padding: 10px; background-color: #90ee90;">
    <p>' . esc_html__( 'Brilliant - you are automatically protected from spam', 'quick-event-manager' ) . '</p> 
    <p>' . esc_html__( 'By', 'quick-event-manager' ) . ' <a href="' . esc_url( get_admin_url() ) . 'options-general.php?page=fullworks-anti-spam-settings" >' . esc_html__( 'Fullworks\' Anti Spam - see the settings here', 'quick-event-manager' ) . '</a> </p>
	</div>' ;
        } else {
            echo  '<h2>' . esc_html__( 'Anti Spam Protection', 'quick-event-manager' ) . '</h2>
<div style="border: 1px solid black; padding: 10px; background-color: #ffcccb;">
    <p>' . esc_html__( 'You have FULLWORKS ANTI SPAM installed but you need to enable the ANTI SPAM licence to get spam protection for Quick Event Manager ', 'quick-event-manager' ) . '</p> 
    <p>' . esc_html__( 'Get your licence', 'quick-event-manager' ) . ' <a href="' . esc_url( get_admin_url() ) . 'options-general.php?page=fullworks-anti-spam-settings-pricing" >' . esc_html__( 'Fullworks\' Anti Spam', 'quick-event-manager' ) . '</a> </p>
	</div>' ;
        }
    
    } else {
        echo  '<h2>' . esc_html__( 'Anti Spam Protection', 'quick-event-manager' ) . '</h2>
<div style="border: 1px solid black; padding: 10px; background-color: #ffcccb;">
    <p>' . esc_html__( 'Protect your forms from annoying spam', 'quick-event-manager' ) . '</p> 
    <p>' . esc_html__( 'Simply install', 'quick-event-manager' ) . ' <a href="https://fullworksplugins.com/products/anti-spam/" target="_blank">' . esc_html__( 'Fullworks\' Anti Spam', 'quick-event-manager' ) . '</a></p>
    <p>' . esc_html__( 'No Recaptcha, no annoying quizes or images, simply effective. Free trial available.', 'quick-event-manager' ) . '</p>
	</div>' ;
    }
    
    echo  '</td></tr><tr>
    <td colspan="3"><h2>' . esc_html__( 'Notifications', 'quick-event-manager' ) . '</h2></td>
    <tr>
    <td colspan="2">' . esc_html__( 'Primary Email Address', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="sendemail" value="' . esc_attr( qem_get_element( $register, 'sendemail' ) ) . '" /><br><span class="description">' . esc_html__( 'This is where registration notifications will be sent from and sent to, make sure it is a good email address', 'quick-event-manager' ) . '</span></td>
    </tr>' ;
    echo  '<td colspan="2">' . esc_html__( 'Copy notifications', 'quick-event-manager' ) . '</td>
    <td><span class="description">' . esc_html__( 'Upgrade to a premium version to enable multiple admin registration notification addreses', 'quick-event-manager' ) . '</span></td>
    </tr>' ;
    echo  '</tr><tr>
    <td width="5%"><input type="checkbox" name="nonotifications" ' . esc_attr( qem_get_element( $register, 'nonotifications' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Do not send notifications' ) . '</td>
    </tr>

    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Registration Form', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Form title', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="title" value="' . esc_attr( qem_get_element( $register, 'title' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Form blurb', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="blurb" value="' . esc_attr( qem_get_element( $register, 'blurb' ) ) . '" /></td>
    </tr>
    </table>
    <p>' . esc_html__( 'Check those fields you want to use. Drag and drop to change the order', 'quick-event-manager' ) . '.</p>
    <style>table#sorting{width:100%;}
    #sorting tbody tr{outline: 1px solid #888;background:#E0E0E0;}
    #sorting tbody td{padding: 2px;vertical-align:middle;}
    #sorting{border-collapse:separate;border-spacing:0 5px;}</style>
    <script>
    jQuery(function()
    {var qem_rsort = jQuery( "#qem_rsort" ).sortable(
    {axis: "y",cursor: "move",opacity:0.8,update:function(e,ui)
    {var order = qem_rsort.sortable("toArray").join();jQuery("#qem_register_sort").val(order);}});});
    </script>
    <table id="sorting">
    <thead>
    <tr>
    <th width="5%">U</th>
    <th width="5%">R</th>
    <th width="20%">' . esc_html__( 'Field', 'quick-event-manager' ) . '</th>
    <th>' . esc_html__( 'Label', 'quick-event-manager' ) . '</th>
    </tr>
    </thead><tbody id="qem_rsort">' ;
    $sort = explode( ",", $register['sort'] );
    foreach ( $sort as $name ) {
        echo  '<tr id="' . esc_attr( $name ) . '">' ;
        switch ( $name ) {
            case 'field1':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usename',
                    'reqname',
                    esc_html__( 'Name', 'quick-event-manager' ),
                    'yourname'
                );
                break;
            case 'field2':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usemail',
                    'reqmail',
                    esc_html__( 'Email', 'quick-event-manager' ),
                    'youremail'
                );
                break;
            case 'field3':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useattend',
                    '',
                    esc_html__( 'Not Attending', 'quick-event-manager' ),
                    'yourattend'
                );
                break;
            case 'field4':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usetelephone',
                    '',
                    esc_html__( 'Telephone', 'quick-event-manager' ),
                    'yourtelephone'
                );
                break;
            case 'field5':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useplaces',
                    '',
                    esc_html__( 'Places', 'quick-event-manager' ),
                    'yourplaces'
                );
                echo  '<input type="radio" name="placesposition" value="left" ' . esc_attr( $left ) . '> Before label <input type="radio" name="placesposition" value="right" ' . esc_attr( $right ) . '> After label' ;
                break;
            case 'field6':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usemessage',
                    'reqmessage',
                    esc_html__( 'Message', 'quick-event-manager' ),
                    'yourmessage'
                );
                break;
            case 'field7':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usecaptcha',
                    '',
                    esc_html__( 'Captcha', 'quick-event-manager' ),
                    'captchalabel'
                );
                esc_html_e( 'Adds a maths captcha to confuse the spammers.', 'quick-event-manager' );
                break;
            case 'field8':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usecopy',
                    '',
                    esc_html__( 'Copy Message', 'quick-event-manager' ),
                    'copyblurb'
                );
                break;
            case 'field9':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useblank1',
                    'reqblank1',
                    esc_html__( 'User defined 1', 'quick-event-manager' ),
                    'yourblank1'
                );
                echo  '<input type="checkbox" name="yourblank1textarea" value="yourblank1textarea" ' . esc_attr( $yourblank1textarea ) . '> Make Textarea' ;
                break;
            case 'field10':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useblank2',
                    'reqblank2',
                    esc_html__( 'User defined 2', 'quick-event-manager' ),
                    'yourblank2'
                );
                echo  '<input type="checkbox" name="yourblank2textarea" value="yourblank2textarea" ' . esc_attr( $yourblank2textarea ) . '> ' . esc_html__( 'Make Textarea', 'quick-event-manager' ) ;
                break;
            case 'field11':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usedropdown',
                    '',
                    esc_html__( 'Dropdown 1', 'quick-event-manager' ),
                    'yourdropdown'
                );
                break;
            case 'field12':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usenumber1',
                    'requmber1',
                    esc_html__( 'Number ( user defined input field for a number)', 'quick-event-manager' ),
                    'yournumber1'
                );
                break;
            case 'field13':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useaddinfo',
                    '',
                    esc_html__( 'Additional Info', 'quick-event-manager' ),
                    'addinfo'
                );
                esc_html_e( '(displays as plain text)', 'quick-event-manager' );
                echo  '<input type="checkbox" name="paypaladdinfo" value="checked" ' . esc_attr( qem_get_element( $register, 'paypaladdinfo' ) ) . '> ' ;
                esc_html_e( 'Only show if payment is required', 'quick-event-manager' );
                break;
            case 'field14':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useselector',
                    '',
                    esc_html__( 'Dropdown 2', 'quick-event-manager' ),
                    'yourselector'
                );
                break;
            case 'field15':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'useoptin',
                    '',
                    esc_html__( 'Optin Checkbox', 'quick-event-manager' ),
                    'optinblurb'
                );
                break;
            case 'field16':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usechecks',
                    '',
                    esc_html__( 'Options', 'quick-event-manager' ),
                    'checkslabel'
                );
                echo  '<input type="text" name="checkslist" value="' . esc_attr( qem_get_element( $register, 'checkslist' ) ) . '" >' . esc_html__( 'Display as radio buttons: ', 'quick-event-manager' ) . '<input type="checkbox" name="usechecksradio" value="checked" ' . esc_attr( qem_get_element( $register, 'usechecksradio' ) ) . '>' ;
                break;
            case 'field17':
                qem_reg_fields_td_e(
                    $name,
                    $register,
                    'usedonation',
                    '',
                    esc_html__( 'Donation', 'quick-event-manager' ),
                    'donation',
                    'disabled'
                );
                echo  '<a href="' . esc_url( $qem_fs->get_upgrade_url() ) . '" target="_blank">' . esc_html__( 'Upgrade for donation feature', 'quick-event-manager' ) . '</a>' ;
                break;
        }
        echo  '</td></tr>' ;
    }
    echo  '</tbody>
    </table>
    <input type="hidden" id="qem_register_sort" name="sort" value="' . esc_attr( qem_get_element( $register, 'sort' ) ) . '" />

    <table>
    <tr>
    <td colspan="2">' . esc_html__( 'Submit Button', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="qemsubmit" value="' . esc_attr( qem_get_element( $register, 'qemsubmit' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Show box for more names', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="usemorenames" ' . esc_attr( qem_get_element( $register, 'usemorenames' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Show box to add more names if number attending is greater than 1', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'More names label', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="morenames" value="' . esc_attr( qem_get_element( $register, 'morenames' ) ) . '" /></td>
    </tr>

    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Terms and Conditions', 'quick-event-manager', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="useterms" ' . esc_attr( qem_get_element( $register, 'useterms' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Include Terms and Conditions checkbox', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'T&C label', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="termslabel" value="' . esc_attr( qem_get_element( $register, 'termslabel' ) ) . '" /></td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'T&C URL', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="termsurl" value="' . esc_attr( qem_get_element( $register, 'termsurl' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="termstarget" ' . esc_attr( qem_get_element( $register, 'termstarget' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Open link in new Tab/Window', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Error and Thank-you messages', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Thank you message title', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="replytitle" value="' . esc_attr( qem_get_element( $register, 'replytitle' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Thank you message blurb ( some html e.g. links permitted here )', 'quick-event-manager' ) . '</td>
    <td><textarea style="width:100%;height:100px;" name="replyblurb">' . qem_wp_kses_post( qem_get_element( $register, 'replyblurb' ) ) . '</textarea></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Error Message', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="error" value="' . esc_attr( qem_get_element( $register, 'error' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="2">' . esc_html__( 'Already Registered', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="alreadyregistered" value="' . esc_attr( qem_get_element( $register, 'alreadyregistered' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Register without payment', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="ignorepayment" ' . esc_attr( qem_get_element( $register, 'ignorepayment' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Allow registration without payment' ) . '<br>
    <span class="description">' . esc_html__( 'Only displays if there is a cost and the transfer to paypal option is enabled on the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=payment">' . esc_html__( 'Payments Page', 'quick-event-manager' ) . '</a></span>.</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Label', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="ignorepaymentlabel" value="' . esc_attr( qem_get_element( $register, 'ignorepaymentlabel' ) ) . '" /></td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Pay later message', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="replydeferred" value="' . esc_attr( qem_get_element( $register, 'replydeferred' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Name Removal', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="checkremoval" ' . esc_attr( qem_get_element( $register, 'checkremoval' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Use \'Not Attending\' option to allow people to remove their names from the list', 'quick-event-manager' ) . '. ' . esc_html__( 'The plugin matches email addresses to identify the attendee', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Name Removed Message', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="nameremoved" value="' . esc_attr( qem_get_element( $register, 'nameremoved' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="useread_more" ' . esc_attr( qem_get_element( $register, 'useread_more' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Display a \'return to event\' message after registration', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Return to event message:', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="width:100%;" label="read_more" name="read_more" value="' . esc_attr( qem_get_element( $register, 'read_more' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Confirmation Email', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="copychecked"' . esc_attr( qem_get_element( $register, 'copychecked' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Set default \'Copy Message\' field to \'checked\'', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="3">You can reply to the sender using the <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=auto">Auto Responder</a>.</td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Redirection', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td colspan="3">' . esc_html__( 'This will redirect visitors to a URL instead of displaying the thank you message', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Redirection URL', 'quick-event-manager' ) . ':</td>
    <td><input type="text" name="redirectionurl" value="' . esc_attr( qem_get_element( $register, 'redirectionurl' ) ) . '" /></td>
    </tr>

    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Moderation', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="moderate" ' . esc_attr( qem_get_element( $register, 'moderate' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Moderate all registrations', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td></td>
    <td>' . esc_html__( 'Message to display after registration', 'quick-event-manager' ) . ':</td>
    <td><input type="text" name="moderatereply" value="' . esc_attr( qem_get_element( $register, 'moderatereply' ) ) . '" /></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="moderateplaces" ' . esc_attr( qem_get_element( $register, 'moderateplaces' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Update places available before moderation', 'quick-event-manager' ) . '.</td>
    </tr>

    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Waiting Lists', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="waitinglist" ' . esc_attr( qem_get_element( $register, 'waitinglist' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Allow people to register even if there are no places available', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td></td><td>' . esc_html__( 'Message to display', 'quick-event-manager' ) . ':</td>
    <td>' . esc_attr( qem_get_element( $register, 'numberattendingbefore' ) ) . ' 0 ' . esc_attr( qem_get_element( $register, 'numberattendingafter' ) ) . '<input type="text" name="waitinglistmessage" value="' . esc_attr( qem_get_element( $register, 'waitinglistmessage' ) ) . '" /></td>
    </tr>
    <tr>
    <td></td><td>' . esc_html__( 'Message to display after registration', 'quick-event-manager' ) . ':</td>
    <td><input type="text" name="waitinglistreply" value="' . esc_attr( qem_get_element( $register, 'waitinglistreply' ) ) . '" /></td>
    </tr>

    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Attendee Lists', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="5%"><input type="checkbox" name="listnames" ' . esc_attr( qem_get_element( $register, 'listnames' ) ) . ' value="checked" /></td>
    <td colspan="2">' . esc_html__( 'Show attendees as a list', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td width="5%"></td>
    <td colspan="2"><input type="text" name="listblurb" value="' . esc_attr( qem_get_element( $register, 'listblurb' ) ) . '" /><br>
    <span class="description">Shortcodes: [name],[email],[mailto],[places],[telephone],[user1],[user2]<br>' ;
    echo  'Upgrade to premium for: [dropdown1],[dropdown1],[options]<br>' ;
    /* translators: leave [website]] */
    echo  esc_html__( 'If you want a link to a website use first used defined field and the [website] shortcode.', 'quick-event-manager' ) ;
    echo  '</span>
    </tr>

    </table>
    <p><input type="submit" name="Settings" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Settings', 'quick-event-manager' ) . '" />
    <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \'' . esc_html__( 'Are you sure you want to reset the registration form settings?', 'quick-event-manager' ) . '\' );"/></p>
    <div id="styling"></div>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    <h2 style="color:blue;">' . esc_html__( 'Form Styling', 'quick-event-manager' ) . '</h2>' ;
    echo  '<h2>' . esc_html__( 'Form Styling', 'quick-event-manager' ) . '</h2>
        <p>' . esc_html__( 'Registration Form styling is only avaialble to Pro users.', 'quick-event-manager' ) . '</p>
        <p><a href="?' . esc_url( $qem_fs->get_upgrade_url() ) . '">' . esc_html__( 'Find out more', 'quick-event-manager' ) . '</a></p>' ;
    echo  '</div>
    <div class="qem-options qem-preview">
    <h2>' . esc_html__( 'Example form', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'This is an example of the form. When it appears on your site it will use your theme styles.', 'quick-event-manager' ) . '</p>' ;
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- qem_loop_esc function calls only data from escaped or core functions for legacy code structure not sensible to use kses here
    echo  qem_loop_esc() ;
    echo  '</div></div>' ;
}

function qem_reg_fields_td_e(
    $name,
    $register,
    $use,
    $req,
    $label,
    $input,
    $disabled = ''
)
{
    echo  '<td width="5%"><input type="checkbox" name="' . esc_attr( $use ) . '" ' . esc_attr( qem_get_element( $register, $use ) ) . ' ' . esc_attr( $disabled ) . ' value="checked" /></td>
        <td width="5%">' ;
    if ( $req ) {
        echo  '<input type="checkbox" name="' . esc_attr( $req ) . '" ' . esc_attr( qem_get_element( $register, $req ) ) . ' ' . esc_attr( $disabled ) . ' value="checked" />' ;
    }
    echo  '</td><td width="20%">' . esc_attr( $label ) . '</td><td>' ;
    if ( $name == 'field7' ) {
        echo  esc_html__( 'Adds a maths captcha to confuse the spammers.', 'quick-event-manager' ) ;
    }
    echo  '<input type="text" style="border: 1px solid #343838;" name="' . esc_attr( $input ) . '" value="' . esc_attr( qem_get_element( $register, $input ) ) . '" ' . esc_attr( $disabled ) . ' />' ;
}

function qem_autoresponse_page()
{
    global  $qem_fs ;
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'enable',
            'whenconfirm',
            'subject',
            'subjecttitle',
            'subjectdate',
            'message',
            'useeventdetails',
            'eventdetailsblurb',
            'useregistrationdetails',
            'registrationdetailsblurb',
            'sendcopy',
            'fromname',
            'fromemail',
            'permalink'
        );
        foreach ( $options as $item ) {
            
            if ( 'message' === $item ) {
                $auto[$item] = qem_wp_kses_post( stripslashes( qem_get_element( $_POST, $item ) ) );
            } else {
                $auto[$item] = sanitize_text_field( stripslashes( qem_get_element( $_POST, $item ) ) );
                $auto[$item] = filter_var( qem_get_element( $auto, $item ), FILTER_SANITIZE_STRING );
            }
        
        }
        update_option( 'qem_autoresponder', $auto );
        qem_admin_notice( "The autoresponder settings have been updated." );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_autoresponder' );
        qem_admin_notice( "The autoresponder settings have been reset." );
    }
    
    $auto = qem_get_stored_autoresponder();
    $aftersubmission = $afterpayment = '';
    ${$auto['whenconfirm']} = 'checked';
    $message = $auto['message'];
    $after_msg = sprintf( esc_html__( 'Send only after confirmed payment - for paid events which go direct to PayPal (set if %1$sPayPal IPN%2$s is active), this will also defer admin notifications until after payment. Free Events and Events that do not redirect to payment automatically will still be notified immediately.', 'quick-event-manager' ), '<a href="?page=' . QUICK_EVENT_MANAGER_PLUGIN_NAME . '&tab=payment">', '</a>' );
    echo  '<div class="qem-settings"><div class="qem-options" style="width:90%;">
	<h2 style="color:#B52C00">' . esc_html__( 'Auto responder settings', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'The Auto Responder will send an email to the Registrant if enabled of if they choose to recieve a copy of their details', 'quick-event-manager' ) . '.</p>
    <form method="post" action="">
    <p><input type="checkbox" name="enable"' . esc_attr( qem_get_element( $auto, 'enable' ) ) . ' value="checked" /> ' . esc_html__( 'Enable Auto Responder', 'quick-event-manager' ) . '.</p>
 <p><input style="width:20px; margin: 0; padding: 0; border: none;" type="radio" name="whenconfirm" value="aftersubmission" ' . esc_attr( $aftersubmission ) . ' /> Send immediately after registration<br>
    <input style="width:20px; margin: 0; padding: 0; border: none;" type="radio" name="whenconfirm" value="afterpayment" ' . esc_attr( $afterpayment ) . ' /> ' . esc_html( $after_msg ) . '</span></p>
    <p>' . esc_html__( 'From Name:', 'quick-event-manager' ) . ' (<span class="description">' . esc_html__( 'Defaults to your', 'k-event-manager' ) . '</a> ' . esc_html__( 'if left blank', 'quick-event-manager' ) . '.</span>):<br>
    <input type="text" style="width:50%" name="fromname" value="' . esc_attr( qem_get_element( $auto, 'fromname' ) ) . '" /></p>
    <p>' . esc_html__( 'From Email:', 'quick-event-manager' ) . ' (<span class="description">' . esc_html__( 'Defaults to the', 'quick-event-manager' ) . ' <a href="' . esc_attr( get_admin_url() ) . 'options-general.php">' . esc_html__( 'Admin Email', 'quick-event-manager' ) . '</a> ' . esc_html__( 'if left blank', 'quick-event-manager' ) . '.</span>):<br>
    <input type="text" style="width:50%" name="fromemail" value="' . esc_attr( qem_get_element( $auto, 'fromemail' ) ) . '" /></p>
    <p>' . esc_html__( 'Subject:', 'quick-event-manager' ) . '<br>
    <input style="width:100%" type="text" name="subject" value="' . esc_attr( qem_get_element( $auto, 'subject' ) ) . '"/></p>
    <p><input type="checkbox" name="subjecttitle"' . esc_attr( qem_get_element( $auto, 'subjecttitle' ) ) . ' value="checked" />&nbsp' . esc_html__( 'Show event title', 'quick-event-manager' ) . '&nbsp;
    <input type="checkbox" name="subjectdate"' . esc_attr( qem_get_element( $auto, 'subjectdate' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Show date', 'quick-event-manager' ) . '</p>
    <h2>' . esc_html__( 'Message Content', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'To create individual event messages use the \'Registration Confirmation Message\' option at the bottom of the', 'quick-event-manager' ) . ' <a href="post-new.php?post_type=event">' . esc_html__( 'Event Editor', 'quick-event-manager' ) . '</a>.</p>
    <p>' . esc_html__( 'If you are taking payments on some events you can create a message for those events on the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=payment">' . esc_html__( 'Payments settings page', 'quick-event-manager' ) . '</a>.</p>' ;
    wp_editor( $message, 'message', $settings = array(
        'textarea_rows' => '20',
        'wpautop'       => false,
        'media_buttons' => false,
    ) );
    echo  '<p>' . esc_html__( 'You can use the following shortcodes in the message body:', 'quick-event-manager' ) . '</p>
    <table>
    <tr><th>Shortcode</th><th>' . esc_html__( 'Replacement Text', 'quick-event-manager' ) . '</th></tr>
    <tr><td>[name]</td><td>' . esc_html__( 'The registrants name from the form', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[event]</td><td>' . esc_html__( 'Event title', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[date]</td><td>' . esc_html__( 'Date of the event', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[places]</td><td>' . esc_html__( 'Number of places required', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[location]</td><td>' . esc_html__( 'Event location (not the address)', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[start]</td><td>' . esc_html__( 'Start time', 'quick-event-manager' ) . '</td></tr>
    <tr><td>[finish]</td><td>' . esc_html__( 'Finish time', 'quick-event-manager' ) . '</td></tr>' ;
    echo  '<tr><td colspan="2"><a href="' . esc_url( $qem_fs->get_upgrade_url() ) . '"><h3>' . esc_html__( 'Upgrade to GOLD plan to use the following', 'quick-event-manager' ) . '</a></h3></td></tr>' ;
    echo  '<tr><td>[ticket_no]</td><td>' . esc_html__( 'Auto generated ticket number (Gold Plan)', 'quick-event-manager' ) . '</td></tr>' ;
    echo  '</table>' ;
    echo  '<p><input type="checkbox" name="useregistrationdetails"' . esc_attr( qem_get_element( $auto, 'useregistrationdetails' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Add registration details to the email', 'quick-event-manager' ) . '</p>
    <p>' . esc_html__( 'Registration details blurb', 'quick-event-manager' ) . '<br>
    <input type="text" style="" name="registrationdetailsblurb" value="' . esc_attr( qem_get_element( $auto, 'registrationdetailsblurb' ) ) . '" /></p>
    <p><input type="checkbox" name="useeventdetails"' . esc_attr( qem_get_element( $auto, 'useeventdetails' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Add event details to the email', 'quick-event-manager' ) . '</p>
    <p>' . esc_html__( 'Event details blurb', 'quick-event-manager' ) . '<br>
<input type="text" style="" name="eventdetailsblurb" value="' . esc_attr( qem_get_element( $auto, 'eventdetailsblurb' ) ) . '" /></p
    <p><input type="checkbox" name="permalink"' . esc_attr( qem_get_element( $auto, 'permalink' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Include link to event page', 'quick-event-manager' ) . '</td>
    <p><input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \'' . esc_html__( 'Are you sure you want to reset the auto responder settings?', 'quick-event-manager' ) . '\' );"/></p>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form>
    </div>
    </div>' ;
}

function qem_payment()
{
    global  $qem_fs ;
    
    if ( isset( $_POST['Submit'] ) && check_admin_referer( "save_qem" ) ) {
        $options = array(
            'qppform',
            'paypal',
            'paypalemail',
            'currency',
            'useprocess',
            'message',
            'processpercent',
            'processfixed',
            'waiting',
            'qempaypalsubmit',
            'ipn',
            'title',
            'paid',
            'ipnblock',
            'sandbox',
            'usecoupon',
            'couponcode',
            'attendeelabel',
            'totallabel',
            'itemlabel',
            'currencysymbol',
            'usependingcleardown',
            'pendingcleardownmsg'
        );
        foreach ( $options as $item ) {
            $field = stripslashes( qem_get_element( $_POST, $item ) );
            
            if ( in_array( $item, array( 'message', 'pendingcleardownmsg' ) ) ) {
                $payment[$item] = qem_wp_kses_post( $field );
            } else {
                $payment[$item] = sanitize_text_field( $field );
            }
        
        }
        update_option( 'qem_payment', $payment );
        qem_admin_notice( esc_html__( 'The payment form settings have been updated', 'quick-event-manager' ) );
    }
    
    
    if ( isset( $_POST['Reset'] ) && check_admin_referer( "save_qem" ) ) {
        delete_option( 'qem_payment' );
        qem_admin_notice( esc_html__( 'The payment form settings have been reset', 'quick-event-manager' ) );
    }
    
    $payment = qem_get_stored_payment();
    $message = qem_get_element( $payment, 'message' );
    $paypal = ( isset( $payment['paypal'] ) ? $payment['paypal'] : '' );
    echo  '<div class="qem-settings">
    <form id="" method="post" action="">
    <div class="qem-options">
    <h2>' . esc_html__( 'Event Payments', 'quick-event-manager' ) . '</h2>
    <p>' . sprintf(
        /* translators: leave place holder they apply emphasic mark up */
        esc_html__( 'This setting only works if you have a simple cost on your event. This means %1$sEntry $10%2$s will be OK but %1$s&pound;5 for adults and &pound;3 for children%2$s may cause problems.', 'quick-event-manager' ),
        '<em>',
        '</em>'
    ) . '</p>
    <table width="100%">
    <tr>
    <td colspan="3"><input type="checkbox" name="paypal"' . esc_attr( $paypal ) . ' value="checked" />&nbsp;' . esc_html__( 'Transfer to Payment Provider after registration', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="3"><p>' . esc_html__( 'After registration the plugin will link to a payment provider using the event title, cost and number of places for the payment details.', 'quick-event-manager' ) . ' <span class="description"> ' . esc_html__( 'You can also select payments on individual events using the', 'quick-event-manager' ) . ' <a href="edit.php?post_type=event">' . esc_html__( 'Event Editor', 'quick-event-manager' ) . '</a></span>.</p>
    <p><b>Note:</b>' . esc_html__( 'The free version of the plugin only permits payment using PayPal Standard. Upgrading to Pro gives you the option to use Stripe', 'quick-event-manager' ) . '.</td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'PayPal', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td width="30%">' . esc_html__( 'Your PayPal Email Address', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="paypalemail" value="' . esc_attr( qem_get_element( $payment, 'paypalemail' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Currency', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Currency code', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="width:3em" label="new_curr" name="currency" value="' . esc_attr( qem_get_element( $payment, 'currency' ) ) . '" />&nbsp;' . esc_html__( '(For example: GBP, USD, EUR)', 'quick-event-manager' ) . '</td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Variable Payments', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Title', 'quick-event-manager' ) . ':</td>
    <td><input type="text" name="attendeelabel" value="' . esc_attr( qem_get_element( $payment, 'attendeelabel' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Currency symbol', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="width:2em" label="new_curr" name="currencysymbol" value="' . esc_attr( qem_get_element( $payment, 'currencysymbol' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Item label', 'quick-event-manager' ) . ':</td>
    <td><input type="text" label="new_curr" name="itemlabel" value="' . esc_attr( qem_get_element( $payment, 'itemlabel' ) ) . '" /><br>
    <span class="description">Optional shorcodes: [label], [currency], [cost]</span></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Total label', 'quick-event-manager' ) . ':</td>
    <td><input type="text" style="width:8em" label="new_curr" name="totallabel" value="' . esc_attr( qem_get_element( $payment, 'totallabel' ) ) . '" /></td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Handling', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td><input type="checkbox" name="useprocess"' . esc_attr( qem_get_element( $payment, 'useprocess' ) ) . ' value="checked" /> ' . esc_html__( 'Add processing fee', 'quick-event-manager' ) . '</td>
    <td>' . esc_html__( 'Percentage of the total', 'quick-event-manager' ) . ': <input type="text" style="width:4em;padding:2px" label="processpercent" name="processpercent" value="' . esc_attr( qem_get_element( $payment, 'processpercent' ) ) . '" /> %<br>
    ' . esc_html__( 'Fixed amount', 'quick-event-manager' ) . ': <input type="text" style="width:4em;padding:2px" label="processfixed" name="processfixed" value="' . esc_attr( qem_get_element( $payment, 'processfixed' ) ) . '" /> ' . esc_html( qem_get_element( $payment, 'currency' ) ) . '</td>
    </tr>
    <tr>
    <td colspan="3"><h2>' . esc_html__( 'Captions and Messages', 'quick-event-manager' ) . '</h2></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Submit Label', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="qempaypalsubmit" value="' . esc_attr( qem_get_element( $payment, 'qempaypalsubmit' ) ) . '" /></td>
    </tr>
    <tr>
    <td>' . esc_html__( 'Waiting Message', 'quick-event-manager' ) . '</td>
    <td><input type="text" style="" name="waiting" value="' . esc_attr( qem_get_element( $payment, 'waiting' ) ) . '" /></td>
    </tr>
    </table>' ;
    echo  '<h2>' . esc_html__( 'Instant Payment Notification', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'IPN only works if you have a PayPal Business or Premier account and IPN has been set up on that account', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'See the', 'quick-event-manager' ) . ' <a target="_blank" href="https://developer.paypal.com/docs/api-basics/notifications/ipn/IPNSetup/ ">' . esc_html__( 'PayPal IPN Integration Guide', 'quick-event-manager' ) . '</a> ' . esc_html__( 'for more information on how to set up IPN', 'quick-event-manager' ) . '.</p>

    <p>' . esc_html__( 'The IPN listener URL you will need is', 'quick-event-manager' ) . ':<pre>' . esc_url( site_url( '/?qem_ipn' ) ) . '</pre></p>
    <p>' . esc_html__( 'To see the Payments Report click on the', 'quick-event-manager' ) . ' <b>' . esc_html__( 'Registration', 'quick-event-manager' ) . '</b> ' . esc_html__( 'link in your dashboard menu or', 'quick-event-manager' ) . ' <a href="?page=qem-registration">' . esc_html__( 'click here', 'quick-event-manager' ) . '</a>.</p>
    <p><input type="checkbox" name="ipn" ' . esc_attr( qem_get_element( $payment, 'ipn' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Enable IPN', 'quick-event-manager' ) . '.</p>
   <h2>' . esc_html__( 'Abandoned Cart Payment Processing', 'quick-event-manager' ) . '</h2>
    <p class="description">' . esc_html__( 'Pending payments, for events with a cost that that immediately redirect to payment can be cleared after a short while automatically.', 'quick-event-manager' ) . '</p>
    <p><input type="checkbox" name="usependingcleardown" ' . esc_attr( qem_get_element( $payment, 'usependingcleardown' ) ) . ' value="checked" /> ' . esc_html__( 'Enable auto clearing of pending payments', 'quick-event-manager' ) . '</a></p>
    <p><input type="checkbox" name="ipnblock"' . esc_attr( qem_get_element( $payment, 'ipnblock' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Hide registration details for pending payments ( Warning: if you limit number attending, hiding pending bookings can result in those limits being breached, so use with care with limited events )', 'quick-event-manager' ) . '.</p>
   <p class="description">' . esc_html__( 'Send a message when clearing down a pending payments (blank not to send).', 'quick-event-manager' ) . '</p>
    <p><textarea name="pendingcleardownmsg" rows ="5">' . qem_wp_kses_post( qem_get_element( $payment, 'pendingcleardownmsg' ) ) . '</textarea></p>
    <h2>' . esc_html__( 'Payment Report', 'quick-event-manager' ) . '</h2>
   
    <p>' . esc_html__( 'Payment Report Column header', 'quick-event-manager' ) . ':<br>
    <input type="text"  style="width:100%" name="title" value="' . esc_attr( qem_get_element( $payment, 'title' ) ) . '" /></p>
    <p>' . esc_html__( 'Payment Complete Label', 'quick-event-manager' ) . ':<br>
    <input type="text"  style="width:100%" name="paid" value="' . esc_attr( qem_get_element( $payment, 'paid' ) ) . '" /></p>
    
    <p><input type="hidden" name="qppform" value="' . esc_attr( qem_get_element( $payment, 'qppform' ) ) . '" />
    <h2>' . esc_html__( 'Payments Autoresponder', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'This is the message that will be sent for events linked to payments', 'quick-event-manager' ) . '. ' . esc_html__( 'See the', 'quick-event-manager' ) . ' <a href="?page=' . esc_attr( QUICK_EVENT_MANAGER_PLUGIN_NAME ) . '&tab=auto">' . esc_html__( 'Autoresponder settings page', 'quick-event-manager' ) . '</a> ' . esc_html__( 'for all other options', 'quick-event-manager' ) . '.</p>' ;
    wp_editor( $message, 'message', $settings = array(
        'textarea_rows' => '20',
        'wpautop'       => false,
        'media_buttons' => false,
    ) );
    echo  '<input type="submit" name="Submit" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Save Changes', 'quick-event-manager' ) . '" /> <input type="submit" name="Reset" class="button-primary" style="color: #FFF;" value="' . esc_html__( 'Reset', 'quick-event-manager' ) . '" onclick="return window.confirm( \'' . esc_html__( 'Are you sure you want to reset the settings?', 'quick-event-manager' ) . '\' );"/></p>
    <p><input type="checkbox" name="sandbox" ' . esc_attr( qem_get_element( $payment, 'sandbox' ) ) . ' value="checked" />&nbsp;' . esc_html__( 'Use Paypal sandbox (developer use only)', 'quick-event-manager' ) . '</p>' ;
    wp_nonce_field( "save_qem" );
    echo  '</form></div>
    <div class="qem-options" style="float:right;">
    <h2>' . esc_html__( 'IPN Simulation', 'quick-event-manager' ) . '</h2>
    <p>' . esc_html__( 'IPN can be blocked or restricted by your server settings, theme or other plugins', 'quick-event-manager' ) . '. ' . esc_html__( 'The good news is you can simulate the notifications to check if all is working', 'quick-event-manager' ) . '.</p>
    <p>' . esc_html__( 'To carry out a simulation', 'quick-event-manager' ) . ':</p>
    <ol>
    <li>' . esc_html__( 'Enable IPN and the PayPal Sandbox on the left', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Register for an event that has the link to paypal option selected', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Go to the', 'quick-event-manager' ) . ' <a href="?page=qem-registration">' . esc_html__( 'Registration Report', 'quick-event-manager' ) . '</a>, ' . esc_html__( 'find the event and copy the long number in the last column)', 'quick-event-manager' ) . '</li>

    <li>' . esc_html__( 'Go to the IPN simulation page', 'quick-event-manager' ) . ': <a href="https://developer.paypal.com/developer/ipnSimulator" target="_blank">https://developer.paypal.com/developer/ipnSimulator</a></li>
    <li>' . esc_html__( 'Login and enter the IPN listener URL', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Select \'Express Checkout\' from the drop down', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Scroll to the bottom of the page and enter the long number you copied at step 3 into the \'Custom\' field', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Click \'Send IPN\'. Scroll up the page and you should see an \'IPN Verified\' message', 'quick-event-manager' ) . '</li>
    <li>' . esc_html__( 'Go back to your Registration Report, you should now see the payment completed message on the event', 'quick-event-manager' ) . '.</li>
    </ol>
    </div></div>' ;
}

function qem_extend_notcoming()
{
    qem_extend_notcoming_report( null );
}

function qem_extend_registrations_setup__premoium_only()
{
    qem_extend_show_registrations();
}

function event_delete_options()
{
    delete_option( 'event_settings' );
    delete_option( 'qem_display' );
    delete_option( 'qem_style' );
    delete_option( 'qem_upgrade' );
    delete_option( 'widget_qem_widget' );
}

function qem_settings_init()
{
    qem_download_files();
    // has tobe called on init hook as sets headers
    qem_add_role_caps();
    return;
}

function qem_settings_scripts( $hook )
{
    wp_enqueue_media();
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script(
        'qem-media-script',
        plugins_url( 'quick-event-media.js', __FILE__ ),
        array( 'jquery', 'wp-color-picker' ),
        false,
        true
    );
    wp_enqueue_script( 'jquery-ui-datepicker' );
    wp_enqueue_style( 'datepicker-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'qem_settings', plugins_url( 'quick-event-manager-settings.css', __FILE__ ) );
    wp_enqueue_script( 'jquery-ui-sortable' );
    wp_enqueue_style( 'event_style', plugins_url( 'quick-event-manager.css', __FILE__ ), null );
    if ( 'settings_page_quick-event-manager/settings' == $hook ) {
        wp_enqueue_script( 'event_script', plugins_url( 'quick-event-manager.js', __FILE__ ) );
    }
}

add_action( 'admin_enqueue_scripts', 'qem_settings_scripts' );
function event_page_init()
{
    add_options_page(
        esc_html__( 'Event Manager', 'quick-event-manager' ),
        esc_html__( 'Event Manager', 'quick-event-manager' ),
        'manage_options',
        QUICK_EVENT_MANAGER_PLUGIN_NAME,
        'qem_tabbed_page'
    );
}

function qem_admin_notice( $message = '' )
{
    if ( !empty($message) ) {
        echo  '<div class="updated"><p>' . qem_wp_kses_post( $message ) . '</p></div>' ;
    }
}

function qem_plugin_row_meta( $links, $file = '' )
{
    
    if ( $file == QUICK_EVENT_MANAGER_PLUGIN_FILE ) {
        $new_links = array( '<a href="https://fullworksplugins.com/docs/quick-event-manager/"><strong>' . esc_html__( 'Documentation', 'quick-event-manager' ) . '</strong></a>' );
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}
