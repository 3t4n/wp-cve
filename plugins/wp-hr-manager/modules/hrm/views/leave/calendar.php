<?php
$filter_active = ( isset( $_GET['department'] ) && sanitize_text_field( $_GET['department'] ) != '-1' ) || ( isset( $_GET['designation'] ) && $_GET['designation'] != '-1' ) ? $_GET : false;

$leave_requests = wphr_hr_get_calendar_leave_events( $filter_active, false, true );
$events = [];

foreach ( $leave_requests as $key => $leave_request ) {
    $events[] = array(
        'id'        => $leave_request->id,
        'title'     => $leave_request->display_name,
        'start'     => $leave_request->start_date,
        'end'       => $leave_request->end_date,
        'url'       => wphr_hr_url_single_employee( $leave_request->user_id ),
        'color'     => $leave_request->color,
        'img'       => get_avatar( $leave_request->user_id, 16 )
    );
}
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
        top: -4px;
    }
</style>
<div class="wrap wphr-hr-calendar-wrap">

    <h1><?php _e( 'Calendar', 'wphr' ); ?></h1>

    <div class="tablenav top wphr-calendar-filter">
        <form method="post" action="">
             <?php
                wphr_html_form_input( array(
                    'name'        => 'department',
                    'value'       =>  isset( $_GET['department'] ) ? sanitize_text_field($_GET['department']) : '',
                    'class'       => 'wphr-hrm-select2-add-more wphr-hr-dept-drop-down',
                    'custom_attr' => array( 'data-id' => 'wphr-new-dept' ),
                    'type'        => 'select',
                    'options'     => wphr_hr_get_departments_dropdown_raw()
                ) );

                wphr_html_form_input( array(
                    'name'        => 'designation',
                    'value'       => isset( $_GET['designation'] ) ? sanitize_text_field($_GET['designation']) : '',
                    'class'       => 'wphr-hrm-select2-add-more wphr-hr-desi-drop-down',
                    'custom_attr' => array( 'data-id' => 'wphr-new-designation' ),
                    'type'        => 'select',
                    'options'     => wphr_hr_get_designation_dropdown_raw()
                ) );
            ?>
            <input type="submit" class="button" name="wphr_leave_calendar_filter" value="<?php _e( 'Filter', 'wphr' ); ?>">
        </form>
    </div>

    <div id="wphr-hr-calendar"></div>
</div>

<script>
    ;jQuery(document).ready(function($) {
        $('#wphr-hr-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            editable: false,
            eventLimit: 4, // allow "more" link when too many events
            events: <?php echo json_encode( $events ); ?>,
            eventRender: function( event, element, calEvent ) {
                if( event.img != 'undefined' ) {
                    element.find('.fc-content').find('.fc-title').before( $("<span class=\"fc-event-icons wphr-leave-avatar\">"+event.img+"</span>") );
                }
            },
        });
    });

</script>
