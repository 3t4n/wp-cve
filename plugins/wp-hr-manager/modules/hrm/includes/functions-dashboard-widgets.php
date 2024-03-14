<?php

/** Callbacks *****************************/          
function wphr_hr_dashboard_widget_birthday_callback() 
{
    $wed_birthday_option = wphr_get_option('birthday_id', 'wphr_settings_widget', 1 );
    $wed_work_option = wphr_get_option('work_id', 'wphr_settings_widget', 1 );
    $wed_inout_option = wphr_get_option('office_id', 'wphr_settings_widget', 1 );
    
if($wed_birthday_option=="yes" && $wed_work_option=="yes" && $wed_inout_option=="yes")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-birthday-cake"></i> Birthday Buddies', 'wphr' ), 'wphr_hr_dashboard_widget_birthday' ); 
    wphr_admin_dash_metabox( __( '<i class="fa fa-user-circle-o"></i> Work Anniversary', 'wphr' ), 'wphr_hr_dashboard_widget_anniversary');
     wphr_admin_dash_metabox( __( '<i class="fa fa-paper-plane"></i> Who is out', 'wphr' ), 'wphr_hr_dashboard_widget_whoisout' );
}
elseif($wed_birthday_option=="yes" && $wed_work_option=="yes" && $wed_inout_option=="no")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-birthday-cake"></i> Birthday Buddies', 'wphr' ), 'wphr_hr_dashboard_widget_birthday' ); 
    wphr_admin_dash_metabox( __( '<i class="fa fa-user-circle-o"></i> Work Anniversary', 'wphr' ), 'wphr_hr_dashboard_widget_anniversary'); 
}
elseif($wed_birthday_option=="yes" && $wed_work_option=="no" && $wed_inout_option=="yes")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-birthday-cake"></i> Birthday Buddies', 'wphr' ), 'wphr_hr_dashboard_widget_birthday' ); 
    wphr_admin_dash_metabox( __( '<i class="fa fa-paper-plane"></i> Who is out', 'wphr' ), 'wphr_hr_dashboard_widget_whoisout' );
}
elseif($wed_birthday_option=="no" && $wed_work_option=="yes" && $wed_inout_option=="yes")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-user-circle-o"></i> Work Anniversary', 'wphr' ), 'wphr_hr_dashboard_widget_anniversary');
    wphr_admin_dash_metabox( __( '<i class="fa fa-paper-plane"></i> Who is out', 'wphr' ), 'wphr_hr_dashboard_widget_whoisout' );
}
elseif($wed_birthday_option=="yes")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-birthday-cake"></i> Birthday Buddies', 'wphr' ), 'wphr_hr_dashboard_widget_birthday' ); 
 
}

elseif($wed_work_option=="yes")
{
    wphr_admin_dash_metabox( __( '<i class="fa fa-user-circle-o"></i> Work Anniversary', 'wphr' ), 'wphr_hr_dashboard_widget_anniversary');
 
}
elseif ($wed_inout_option=="yes") 
    {

    wphr_admin_dash_metabox( __( '<i class="fa fa-paper-plane"></i> Who is out', 'wphr' ), 'wphr_hr_dashboard_widget_whoisout' );
   }
          
}


function wphr_hr_dashboard_widget_announcement_callback() {
    wphr_admin_dash_metabox( __( '<i class="fa fa-microphone"></i> Latest Announcement', 'wphr' ), 'wphr_hr_dashboard_widget_latest_announcement' );
    wphr_admin_dash_metabox( __( '<i class="fa fa-calendar-o"></i> My Leave Calendar', 'wphr' ), 'wphr_hr_dashboard_widget_leave_calendar' );
}

function wphr_hr_dashboard_widget_calendar_callback(){
    if( wphr_get_option( 'employee_leave_public', 'wphr_settings_general', 0 ) ):
        wphr_admin_dash_metabox( __( '<i class="fa fa-calendar-o"></i> My Leave Calendar', 'wphr' ), 'wphr_hr_dashboard_widget_leave_calendar' );   
    endif;
}

add_action( 'wphr_hr_dashboard_widgets_right', 'wphr_hr_dashboard_widget_birthday_callback' );
add_action( 'wphr_hr_dashboard_widgets_left', 'wphr_hr_dashboard_widget_announcement_callback' );
//add_action( 'wphr_hr_dashboard_widgets_full', 'wphr_hr_dashboard_widget_calendar_callback' );

/** Widgets *****************************/

/**
 * Birthday widget
 *
 * @return void
 */
function wphr_hr_dashboard_widget_birthday() {
    $todays_birthday  = wphr_hr_get_todays_birthday();
    $upcoming_birtday = wphr_hr_get_next_seven_days_birthday();
    ?>
    <?php if ( $todays_birthday ) { ?>

        <h4><?php _e( 'Today\'s Birthday', 'wphr' ); ?></h4>

        <ul class="wphr-list list-inline">
            <?php
            foreach ( $todays_birthday as $key => $user ) {
                $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user->user_id ) );
                ?>
                <li><a href="<?php echo $employee->get_details_url(); ?>" class="wphr-tips" title="<?php echo $employee->get_full_name(); ?>"><?php echo $employee->get_avatar( 32 ); ?></a></li>
            <?php } ?>
        </ul>

        <?php
    }
    ?>

    <?php if ( $upcoming_birtday ) { ?>

        <h4><?php _e( 'Upcoming Birthdays', 'wphr' ); ?></h4>

        <ul class="wphr-list list-two-side list-sep">

            <?php foreach ( $upcoming_birtday as $key => $user ): ?>

                <?php $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user->user_id ) ); ?>

                <li>
                    <a href="<?php echo $employee->get_details_url(); ?>"><?php echo $employee->get_full_name(); ?></a>
                    <span><?php echo wphr_format_date( $user->date_of_birth, 'M, d' ); ?></span>
                </li>

            <?php endforeach; ?>

        </ul>
        <?php
    }

    if ( ! $todays_birthday && ! $upcoming_birtday ) {
        _e( 'No one has birthdays this week!', 'wphr' );
    }
}
function wphr_hr_dashboard_widget_anniversary() {
    $todays_anniversary  = wphr_hr_get_todays_anniversary();
    $upcoming_anniversary = wphr_hr_get_next_seven_days_anniversary();
    ?>
    <?php if ( $todays_anniversary ) { ?>

        <h4><?php _e( 'Today\'s Work Anniversary', 'wphr' ); ?></h4>

        <ul class="wphr-list list-inline">
            <?php
            foreach ( $todays_anniversary as $key => $user ) {
                $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user->user_id ) );
                ?>
                <li><a href="<?php echo $employee->get_details_url(); ?>" class="wphr-tips" title="<?php echo $employee->get_full_name(); ?>"><?php echo $employee->get_avatar( 32 ); ?></a></li>
            <?php } ?>
        </ul>

        <?php
    }
    ?>

    <?php if ( $upcoming_anniversary ) { ?>

        <h4><?php _e( 'Upcoming Work Anniversary', 'wphr' ); ?></h4>

        <ul class="wphr-list list-two-side list-sep">

            <?php foreach ( $upcoming_anniversary as $key => $user ): ?>

                <?php $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user->user_id ) ); ?>

                <li>
                    <a href="<?php echo $employee->get_details_url(); ?>"><?php echo $employee->get_full_name(); ?></a>
                    <span><?php echo wphr_format_date( $user->date_of_birth, 'M, d' ); ?></span>
                </li>

            <?php endforeach; ?>

        </ul>
        <?php
    }

    if ( ! $todays_anniversary && ! $upcoming_anniversary ) {
        _e( 'No one has work anniversary this week!', 'wphr' );
    }
}

/**
 * Latest Announcement Widget
 *
 * @since 0.1
 *
 * @return void
 */
function wphr_hr_dashboard_widget_latest_announcement() {

    //if user is admin then show latest 5 announcements
    if ( current_user_can( wphr_hr_get_manager_role() ) ) {
        $query = new WP_Query( array(
            'post_type'      => 'wphr_hr_announcement',
            'posts_per_page' => '5',
            'order'          => 'DESC'
        ) );
        $announcements = $query->get_posts();
    } else {
        $announcements = wphr_hr_employee_dashboard_announcement( get_current_user_id() );
    }

    if ( $announcements ) {
    ?>
    <ul class="wphr-list wphr-dashboard-announcement">
        <?php
        $i = 0;
        foreach ( $announcements as $key => $announcement ): ?>
            <li class="<?php echo ($announcement->status !== 'read') ? 'unread' : 'read'; ?>">
                <div class="announcement-title">
                    <a href="#" <?php echo ( $announcement->status == 'read' ) ? 'class="read"' : ''; ?>>
                        <?php echo $announcement->post_title; ?>
                    </a> | <span class="announcement-date"><?php echo wphr_format_date( $announcement->post_date ); ?></span>
                </div >

                <?php echo ( 0 == $i ) ? '<p>' . wp_trim_words( $announcement->post_content, 50 ) . '</p>' : ''; ?>

                <div class="announcement-row-actions">
                    <?php if ( ! current_user_can( wphr_hr_get_manager_role() ) ): ?>
                        <a href="#" class="mark-read wphr-tips <?php echo ( $announcement->status == 'read' ) ? 'wphr-hide' : ''; ?>" title="<?php _e( 'Mark as Read', 'wphr' ); ?>" data-row_id="<?php echo $announcement->id; ?>"><i class="dashicons dashicons-yes"></i></a>
                    <?php endif; ?>
                    <a href="#" class="view-full wphr-tips" title="<?php _e( 'View full announcement', 'wphr' ); ?>" data-row_id="<?php echo $announcement->ID; ?>"><i class="dashicons dashicons-editor-expand"></i></a>
                </div>
            </li>
        <?php $i++;
        endforeach ?>
    </ul>
    <?php
    } else {
        _e( 'No announcement found', 'wphr' );
    }
}

/**
 * wphr dashboard who is out widget
 *
 * @since 0.1
 *
 * @return void
 */
function wphr_hr_dashboard_widget_whoisout() {
    $leave_requests           = wphr_hr_get_current_month_leave_list();
    $leave_requests_nextmonth = wphr_hr_get_next_month_leave_list();
    $month = strtolower( date('F_Y') );
    ?>
    <div class="<?php echo $month; ?>_month_leave_list month_leave_list">
    <?php if ( $leave_requests ) { ?>

        <h4><?php _e( 'This Month', 'wphr' ); ?></h4>

        <ul class="wphr-list list-two-side list-sep">
            <?php foreach ( $leave_requests as $key => $leave ): ?>
                <?php $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $leave->user_id ) ); ?>
                <?php
                    $start_date = wphr_format_date( $leave->start_date, 'M d' );
                    $end_date = wphr_format_date( $leave->end_date, 'M d' );
                    $seperator = ' - ';
                    if( $start_date == $end_date ):
                        if( wphr_format_date( $leave->start_date, 'g:i A' ) != '12:00 AM' ):
                            $seperator = ' ';
                            $end_date = wphr_format_date( $leave->start_date, 'g:i A' );
                        endif;
                    endif;
                ?>
                <li>
                    <a href="<?php echo $employee->get_details_url(); ?>"><?php echo $employee->get_full_name(); ?></a>
                    <span><i class="fa fa-calendar"></i> <?php echo $start_date . $seperator . $end_date; ?></span>
                </li>
            <?php endforeach ?>
        </ul>
    <?php } ?>

    <?php if ( $leave_requests_nextmonth ) { ?>
        <h4><?php _e( 'Next Month', 'wphr' ); ?></h4>

        <ul class="wphr-list list-two-side list-sep">
            <?php foreach ( $leave_requests_nextmonth as $key => $leave ): ?>
                <?php $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $leave->user_id ) ); ?>
                <?php
                    $start_date = wphr_format_date( $leave->start_date, 'M d' );
                    $end_date = wphr_format_date( $leave->end_date, 'M d' );
                    $seperator = ' - ';
                    if( $start_date == $end_date ):
                        if( wphr_format_date( $leave->start_date, 'g:i A' ) != '12:00 AM' ):
                            $seperator = ' ';
                            $end_date = wphr_format_date( $leave->start_date, 'g:i A' );
                        endif;
                    endif;
                ?>
                <li>
                    <a href="<?php echo $employee->get_details_url(); ?>"><?php echo $employee->get_full_name(); ?></a>
                    <span><i class="fa fa-calendar"></i> <?php echo $start_date . $seperator . $end_date; ?></span>
                </li>
            <?php endforeach ?>
        </ul>

    <?php } ?>
    </div>
    <div class="zero_leave_list">
    <?php //if ( ! $leave_requests && ! $leave_requests_nextmonth ) { ?>

        <?php _e( 'No one is on vacation on this or next month', 'wphr' ); ?>

    <?php //} ?>
    </div>
    <?php
}

/**
 * wphr dashboard leave calendar widget
 *
 * @since 0.1
 *
 * @return void
 */
function wphr_hr_dashboard_widget_leave_calendar() {

    $user_id        = get_current_user_id();

    $leave_requests = wphr_hr_get_calendar_leave_events( false, $user_id, false );
    
    $holidays       = wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday::select('*')->where( 'location_id' , 0 )->get()->toArray() );

    $location_id = \WPHR\HR_MANAGER\HRM\Models\Employee::select('location')->where( 'user_id' , $user_id )->get()->toArray();
    
    if( count( $location_id ) ){
        $location_id = $location_id[0]['location'];
    }else{
        $location_id = 0;
    }
    $display_leave_publicaly = wphr_get_option( 'employee_leave_public', 'wphr_settings_general', 0 );
    $show_leaves_to_manager = wphr_get_option( 'line_manager_show_leaves', 'wphr_settings_general', 1 );

    /*
    $country_val = WPHR\HR_MANAGER\Admin\Models\Company_Locations::select('country')->from('wp_wphr_company_locations')->where('id',$location_id)->get()->toArray();
    if( count( $country_val ) ){
        $country_val = $country_val[0]['country'];
    }else{
        $country_val = '';
    }*/
    if( $display_leave_publicaly ):
        $leave_requests = wphr_hr_get_calendar_leave_events( false, false, true );
    else:
        if( $show_leaves_to_manager ):
            $users = get_users_under_line_manager( $user_id );
            if( is_array( $users ) && count( $users ) ):
                $user_leave_requests = wphr_hr_get_calendar_leave_events( false, $user_id, false );;
                foreach ($users as $user_value):
                    $leave_requests = wphr_hr_get_calendar_leave_events( false, $user_value, false );
                    $user_leave_requests = array_merge( $user_leave_requests, (array) $leave_requests ); 
                endforeach;
                $leave_requests = (object) $user_leave_requests; 
            else:
                $leave_requests = wphr_hr_get_calendar_leave_events( false, $user_id, false );
            endif;
        else:
            $leave_requests = wphr_hr_get_calendar_leave_events( false, $user_id, false );
        endif;
    endif;
    
    if( $location_id ){
        $holidays_byLocation       = wphr_array_to_object( \WPHR\HR_MANAGER\HRM\Models\Leave_Holiday::select('*')->where( 'location_id' , $location_id )->get()->toArray() );
    }
    $events         = [];
    $holiday_events = [];
    $event_data     = [];


    // To Get holidays list By Location as well as global
    if(!empty($holidays_byLocation))
    {
        $holidays = array_merge( $holidays, $holidays_byLocation );
    }
    
    foreach ( $leave_requests as $key => $leave_request ) {
        //if status pending
        $policy = wphr_hr_leave_get_policy( $leave_request->policy_id );
        $event_label = $policy->name;
        if ( 2 == $leave_request->status ) {
            $policy = wphr_hr_leave_get_policy( $leave_request->policy_id );
            $event_label .= sprintf( ' ( %s ) ', __( 'Pending', 'wphr' ) );
        }
        $list = $url = '';
        $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $leave_request->user_id ) );
        $event_label = $employee->get_full_name();

        $time = date( 'g:i A', strtotime( $leave_request->start_date ) );
        if( $time != '12:00 AM' ):
            $event_label = $time .' '. $event_label;
        endif;

        $employee = new \WPHR\HR_MANAGER\HRM\Employee( intval( $leave_request->user_id ) );
        $start_date = wphr_format_date( $leave_request->start_date, 'M d' );
        $end_date = wphr_format_date( $leave_request->end_date, 'M d' );
        $seperator = ' - ';
        if( $start_date == $end_date ):
            if( wphr_format_date( $leave_request->start_date, 'g:i A' ) != '12:00 AM' ):
                $seperator = ' ';
                $end_date = wphr_format_date( $leave_request->start_date, 'g:i A' );
            endif;
        endif;
        $list = sprintf( '<li><a href="%s">%s</a><span><i class="fa fa-calendar"></i> %s</span></li>', $employee->get_details_url(), $employee->get_full_name(), $start_date . $seperator . $end_date );
        if( $display_leave_publicaly ):
            $url = $employee->get_details_url();
        else:
            $url = wphr_hr_url_single_employee( $leave_request->user_id, 'leave' );
        endif;
        $month = date('F_Y', strtotime( $leave_request->start_date ));
        $month_title = str_replace('_', ' ', $month );
        $events[] = array(
            'id'        => $leave_request->id,
            'title'     => $event_label,
            'start'     => $leave_request->start_date,
            'end'       => $leave_request->end_date,
            'url'       => $url,
            'color'     => $leave_request->color,
            'list'      => $list,
            'month'     => strtolower( $month ),
            'month_title' => $month_title
        );
    }

    foreach ( $holidays as $key => $holiday ) {
        $holiday_events[] = [
            'id'        => $holiday->id,
            'title'     => $holiday->title,
            'start'     => $holiday->start,
            'end'       => $holiday->end,
            'color'     => '#FF5354',
            'img'       => '',
            'holiday'   => true
        ];
    }
    $event_data = array_merge( $events, $holiday_events );
    ?>
    <style>
        .fc-time {
            display:none;
        }
        .wphr-leave-avatar img {
            border-radius: 50%;
            margin: 3px 7px 0 0;

        }
        .wphr-calendar-filter {
            margin: 15px 0px;
        }
        .fc-title {
            position: relative;
        }
        #wphr-hr-calendar table{
            margin:0;
        }
    </style>

    <?php if ( wphr_hr_get_assign_policy_from_entitlement( $user_id ) ): ?>
        <div class="wphr-hr-new-leave-request-wrap">
            <a href="#" class="button button-primary" id="wphr-hr-new-leave-req"><?php _e( 'Take a Leave', 'wphr' ); ?></a>
        </div>
    <?php endif ?>

    <div id="wphr-hr-calendar"></div>
	<?php
	global $wphr_calendar_event_data;
	$wphr_calendar_event_data = $event_data;
	add_action( 'wp_footer', 'wphr_hr_footer_calendar_script', 99 );
	add_action( 'admin_footer', 'wphr_hr_footer_calendar_script', 99 );
}

/**
 * Employee list url
 *
 * @since  1.1.10
 *
 * @return  string
 */
function wphr_hr_employee_list_url() {
    $args = [
        'page' => 'wphr-hr-employee'
    ];

    $url = add_query_arg( $args, admin_url( 'admin.php' ) );
    $url = apply_filters( 'wphr_hr_employee_list_url', $url, $args );

    return $url;
}

function wphr_hr_footer_calendar_script(){
	global $wphr_calendar_event_data;
	$event_data = $wphr_calendar_event_data;
	?>
    <script>
    ;jQuery(document).ready(function($) {
        var current_month_leave_list = [];
        var current_month_title = [];
        $('#wphr-hr-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            editable: false,
            eventLimit: true,
            events: <?php echo json_encode( $event_data ); ?>,
            eventRender: function(event, element, calEvent) {
                if( !event.month ){
                    return;
                }
                    console.log( event );
                current_month = event.month;
                if( current_month_leave_list[ current_month ] != undefined ){
                    current_month_leave_list[ current_month ] += event.list;
                }else{
                    current_month_leave_list[ current_month ] = event.list;
                }
                current_month_title[ current_month ] = event.month_title;
                if ( event.holiday ) {
                    element.find('.fc-content').find('.fc-title').css({ 'top':'0px', 'left' : '3px', 'fontSize' : '13px', 'padding':'2px' });
                };
            },
            eventAfterAllRender:function( view ){
                for( current_month in current_month_leave_list ){
                    current_month_list = current_month_leave_list[ current_month ];
                    if( ! $( '.'+ current_month + '_month_leave_list').length ){
                        $('.month_leave_list').parent().append( '<div class="' + current_month + '_month_leave_list month_leave_list"><h4>' + current_month_title[ current_month ] + '</h4><ul class="wphr-list list-two-side list-sep">' + current_month_list + '</ul></div>' );
                    }    
                }
                current_month = view.title.trim().replace(' ', '_').toLowerCase();
                $('.month_leave_list').hide();
                if( $( '.'+ current_month + '_month_leave_list').length && $( '.'+ current_month + '_month_leave_list').html().trim() != '' ){
                    $('.zero_leave_list').hide();
                    $( '.'+ current_month + '_month_leave_list').show();
                }else{
                    $('.zero_leave_list').show();
                }
            }
        });
    });

    </script>
    <?php	
}


