<?php
/**
 * Abort if this file is accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class CS_LD_HELPER {

    /**
     *  Constructor
     */
    public function __construct() {
        add_action ( 'admin_enqueue_scripts', array( $this, 'ldccPluginScripts' ) );
        add_action ( 'admin_menu', array( $this, 'ldccAdminMenu' ), 1001 );
        add_action ( 'wp_ajax_course_reschedule', array( $this, 'courseReschedule' ) );
        add_action ( 'wp_ajax_shift_course_schedule', array( $this, 'shiftCourseSchedule' ) );
        add_action ( 'wp_ajax_add_course_schedule', array( $this, 'addCourseSchedule' ) );
        add_action ( 'wp_ajax_remove_course_schedule', array( $this, 'removeCourseSchedule' ) );
        add_action ( 'wp_ajax_getEvents', array( $this, 'getEvents' ) );
        add_action ( 'pre_get_posts', array( $this, 'alter_query' ) );
        add_action ( 'current_screen', array( $this, 'add_help_tab' ) );
        add_action ( 'admin_notices', array( $this, 'add_help_notification' ) );

        add_action ( 'wp_ajax_cs_ld_search_courses', array( $this, 'search_courses_cb' ) );
        add_filter ( 'admin_footer_text', array( $this, 'remove_footer_admin' ) );
        add_shortcode ( 'cs_scheduled_dates', array( $this, 'get_specified_dates' ) );

        add_action('init', array($this, 'db_upgrade'));
    }

    public function db_upgrade() {
        
        $general_settings = get_option( 'wn_course_schedular_general_settings' );
        if( !isset( $general_settings ) || empty( $general_settings ) ) {
            $course_scheduler_ld_addon_setting = get_option( 'course_scheduler_ld_addon_setting' );
            if( !empty( $course_scheduler_ld_addon_setting ) ) {
                update_option( 'wn_course_schedular_general_settings', [ 'show_courses' => $course_scheduler_ld_addon_setting ] );
            } else {
                update_option( 'wn_course_schedular_general_settings', [ 'show_courses' => 1 ] );
            }
        }

        $course_settings = get_option( 'wn_course_schedular_course_settings' );
        if( !isset( $course_settings ) || empty( $course_settings) ) {
            
            $show_message = get_option( 'cs_ld_addon_show_course_message' );
            if( empty( $show_message ) ) {
                $show_message = __("This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }

            $hide_message = get_option( 'cs_ld_addon_hide_course_message' );
            if( empty( $hide_message ) ) {
                $hide_message = __("This " . LearnDash_Custom_Label::get_label( 'course' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }
            
            update_option( 'wn_course_schedular_course_settings', [ 
                'show_message' => $show_message,
                'hide_message' => $hide_message,
            ] );
        }

        $lesson_settings = get_option( 'wn_course_schedular_lesson_settings' );
        if( !isset( $lesson_settings ) || empty( $lesson_settings ) ) {
            
            $show_message = get_option( 'cs_ld_addon_show_lesson_message' );
            if( empty( $show_message ) ) {
                $show_message = __("This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }

            $hide_message = get_option( 'cs_ld_addon_hide_lesson_message' );
            if( empty( $hide_message ) ) {
                $hide_message = __("This " . LearnDash_Custom_Label::get_label( 'lesson' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }
            
            update_option( 'wn_course_schedular_lesson_settings', [ 
                'show_message' => $show_message,
                'hide_message' => $hide_message,
            ] );
        }
        
        $quiz_settings = get_option( 'wn_course_schedular_quiz_settings' );
        if( !isset( $quiz_settings ) || empty( $quiz_settings ) ) {
            
            $show_message = get_option( 'cs_ld_addon_show_quiz_message' );
            if( empty( $show_message ) ) {
                $show_message = __("This " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }

            $hide_message = get_option( 'cs_ld_addon_hide_quiz_message' );
            if( empty( $hide_message ) ) {
                $hide_message = __("This " . LearnDash_Custom_Label::get_label( 'quiz' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }
            
            update_option( 'wn_course_schedular_quiz_settings', [ 
                'show_message' => $show_message,
                'hide_message' => $hide_message,
            ] );
        }
                 
        $topic_settings = get_option( 'wn_course_schedular_topic_settings' );
        if( !isset( $topic_settings ) || empty( $topic_settings ) ) {
            
            $show_message = get_option( 'cs_ld_addon_show_topic_message' );
            if( empty( $show_message ) ) {
                $show_message = __("This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be available on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }

            $hide_message = get_option( 'cs_ld_addon_hide_topic_message' );
            if( empty( $hide_message ) ) {
                $hide_message = __("This " . LearnDash_Custom_Label::get_label( 'topic' ) . " is scheduled to be unavailable on following date(s): [cs_scheduled_dates]", "cs_ld_addon");
            }
            
            update_option( 'wn_course_schedular_topic_settings', [ 
                'show_message' => $show_message,
                'hide_message' => $hide_message,
            ] );
        }
    }

    /**
     * Alter global query to show courses
     *
     * @param $query
     * @return mixed
     */
    public function alter_query( $query ) {

        if( is_admin() )
            return $query;

        if ( ! $query->is_main_query(  ) )
            return $query;

        if ( isset( $query->query_vars['post_type'] ) &&
            ( 'sfwd-courses' == $query->query_vars['post_type'] ||
                'sfwd-lessons' == $query->query_vars['post_type'] ||
                'sfwd-topic' == $query->query_vars['post_type'] ||
                'sfwd-quiz' == $query->query_vars['post_type'] ) ) {

            $courses_args = array( 'posts_per_page' => -1, 'post_type' => 'sfwd-courses', 'post_status'   => 'publish' );
            $courses = get_posts( $courses_args );

            $post_in = array();
            $general_settings = get_option( 'wn_course_schedular_general_settings' );
            $show_courses = ! empty( $general_settings['show_courses'] ) ? $general_settings['show_courses'] : 0 ;

            if( is_array( $courses ) ) {

                foreach( $courses as $course ) {
                    $data = get_post_meta( $course->ID, 'course_schedule', true );
                    if( is_array( $data ) ) {
                        if( empty( $data ) ) {
                            $post_in[] = $course->ID;
                        }

                        foreach( $data as $arr ) {
                            if( '1' == $show_courses ) {
                                if( date( 'Y-m-d' ) == $arr ) {
                                    $post_in[] = $course->ID;
                                }
                            }
                        }
                    }
                }

                if( ! is_single(  ) ) {

                    if( "1" == $show_courses ) {
                        $query->set( "post__in", $post_in );
                    } else {
                        if( sizeof( $courses ) != sizeof($post_in) ) {
                            $query->set( "post__not_in", $post_in );
                        }
                    }
                }
            }

            if( is_single(  ) ) {

                $post_type = $query->query_vars['post_type'];

                $slug = $query->query[$post_type];
                $post_obj = get_page_by_path( $slug , OBJECT, $post_type );

                if( !isset( $post_obj->ID ) ) {
                    return $query;
                }

                $post_id = $post_obj->ID;

                if( 'sfwd-courses' == $post_type ) {

                    $this->ldccShowHide( $post_id, $show_courses, $post_in, "Course" );

                } elseif( 'sfwd-lessons' == $post_type ) {

                    $lesson_id = $post_id;
                    $course_dates = get_post_meta( $lesson_id, "_sfwd-lessons", true );
                    $course_id = $course_dates["sfwd-lessons_course"];
                    $this->ldccShowHide( $course_id, $show_courses, $post_in, "Lesson" );

                } elseif( 'sfwd-quiz' == $post_type ) {

                    $lesson_id = $post_id;
                    $course_dates = get_post_meta( $lesson_id, "_sfwd-quiz", true );

                    $course_id = $course_dates["sfwd-quiz_course"];
                    $this->ldccShowHide( $course_id, $show_courses, $post_in, "Quiz" );

                } elseif( 'sfwd-topic' == $post_type ) {

                    $lesson_id = $post_id;
                    $course_dates = get_post_meta( $lesson_id, "_sfwd-topic", true );
                    $course_id = $course_dates["sfwd-topic_course"];
                    $this->ldccShowHide( $course_id, $show_courses, $post_in, "Topic" );

                }
            }
            return $query;
        }
        return $query;
    }

    /**
     * Show/Hide posts
     *
     * @param $course_id
     * @param $show_courses
     * @param $post_in
     * @param $post_type
     */
    public function ldccShowHide( $course_id, $show_courses, $post_in, $post_type ) {

        global $type;
        $type = $post_type;

        $date_today = date( 'Y-m-d' );

        //Remove courses scheduled on old dates first
        $course_dates = get_post_meta( $course_id, 'course_schedule', true );

        if( $course_dates ) {
            foreach ($course_dates as $key => $course_date) {
                if ( $date_today > $course_date['start_date'] && $date_today < $course_date['end_date']) {
                    unset( $course_dates[$key] );
                }
            }

            update_post_meta( $course_id, "course_schedule", $course_dates );
        }

        $course_dates = get_post_meta( $course_id, 'course_schedule', true );

        if( '1' != $show_courses ) {

            if( !empty($course_dates) ) {
                foreach ($course_dates as $key =>$course_date){
                    if(!empty($course_date['end_date'])){
                        if($date_today >= $course_date['start_date'] &&  $date_today <= $course_date['end_date']){
                            add_filter('learndash_content', function () {
                                global $type;

                                $message = get_option("wn_course_schedular_" . strtolower($type) . "_settings");
                                $hide_message = '[cs_scheduled_dates]';
                                if( isset( $message['hide_message'] ) &&  !empty( $message['hide_message'] ) ) {
                                    $hide_message = $message['hide_message'];
                                }
                                
                                return do_shortcode($hide_message);
                            });
                        }
                    }else{
                        if($date_today == $course_date['start_date']){
                            add_filter('learndash_content', function () {
                                global $type;

                                $message = get_option("wn_course_schedular_" . strtolower($type) . "_settings");
                            
                                $hide_message = '[cs_scheduled_dates]';
                                if( isset( $message['hide_message'] ) &&  !empty( $message['hide_message'] ) ) {
                                    $hide_message = $message['hide_message'];
                                }
                                
                                return do_shortcode($hide_message);
                            });
                        }
                    }

                }
            }

        } else {

            $data = get_post_meta( $course_id, 'course_schedule', true );
            $to_show = true;
            if( is_array( $data ) ) {
                foreach( $data as $key => $arr ) {
                    if(!empty($course_date['end_date'])){
                        if($date_today >= $course_date['start_date'] &&  $date_today <= $course_date['end_date']){

                            $to_show = false;

                        }else{

                            add_filter('learndash_content', function () {
                                global $type;

                                $message = get_option("wn_course_schedular_" . strtolower($type) . "_settings");
                                $show_message = '[cs_scheduled_dates]';
                                if( isset( $message['show_message'] ) &&  !empty( $message['show_message'] ) ) {
                                    $show_message = $message['show_message'];
                                }

                                return do_shortcode($show_message);
                            });

                        }
                    }else{
                        if($date_today == $course_date['start_date']){

                            $to_show = false;

                        }else{

                            add_filter('learndash_content', function () {
                                global $type;

                                $message = get_option("wn_course_schedular_" . strtolower($type) . "_settings");
                                $show_message = '[cs_scheduled_dates]';
                                if( isset( $message['show_message'] ) &&  !empty( $message['show_message'] ) ) {
                                    $show_message = $message['show_message'];
                                }

                                return do_shortcode($show_message);
                            });

                        }
                    }
                }
            }

            /*
             * $to_show = false;
             * if ( !empty ( $data ) ) {
                if ( $to_show ) {
                    add_filter('learndash_content', function() {
                        global $type;
                        return do_shortcode( esc_textarea( $show_message ) );
                    });
                }
            }*/
        }
    }

    /**
     * Enqueue scritps for plugin
     */
    public function ldccPluginScripts() {
        $screen = get_current_screen();
        
        $obj = new WN_DASHBOARD_Page();
        $obj->enquey_scripts();
        

        if( isset($screen->id) &&  ( "wooninjas_page_wn-dashboard-setting"  == $screen->id || "wooninjas_page_calendar_course" == $screen->id || 'wooninjas_page_calendar_course_settings' == $screen->id || "toplevel_page_calendar_course" == $screen->id || strtolower(LearnDash_Custom_Label::get_label( 'course' )) . "-scheduler_page_calendar_course_settings" == $screen->id ) ) {
            wp_enqueue_style( 'wn-dashboard-fontawesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
            wp_enqueue_script( 'moment', CS_LD_PLUGIN_URL . "/assets/js/moment.min.js", array( 'jquery' ), false );
            wp_enqueue_script( 'fullcalendar-js', CS_LD_PLUGIN_URL . "/assets/js/main.js", array( 'moment' ), false );
            wp_enqueue_script( 'fullcalendar-pooper', CS_LD_PLUGIN_URL . "/assets/js/popper.min.js", array( 'moment' ), false );
            //wp_enqueue_script( 'fullcalendar-bootstrap-js', "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js", array( 'moment' ), false );
            wp_enqueue_script( 'fullcalendar-tooltip', CS_LD_PLUGIN_URL . "/assets/js/tooltip.min.js", array( 'moment' ), false );
            wp_enqueue_script( 'fullcalendar-init', CS_LD_PLUGIN_URL . "/assets/js/fullcalendar-init.js", array( 'moment' ), false );
            wp_enqueue_script( 'calendar-color', CS_LD_PLUGIN_URL . "/assets/js/calendar-color.js", array( 'fullcalendar-js', 'jquery-ui-tabs', 'jquery-ui-draggable', 'jquery-ui-droppable'  ), false );

            wp_localize_script( 'calendar-color', 'LDCSAdminVars', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'check_nonce' => wp_create_nonce('ld-cms-nonce') ) );
            wp_enqueue_style( 'jquery-ui', CS_LD_PLUGIN_URL . "/assets/css/jquery-ui.css", array() );
            wp_enqueue_style( 'fullcalendar', CS_LD_PLUGIN_URL . "/assets/css/main.css", array() );
            //wp_enqueue_style( 'fullcalendar-bootstrap', "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css", array() );
            wp_enqueue_style( 'fullcalendar-plugin-style', CS_LD_PLUGIN_URL . "/assets/css/fullcalendar-plugin-style.css", array() );
        }
    }

    /**
     * Rendered added events on calender
     */
    public function getEvents() {
        $date_year = sanitize_text_field(  $_POST['year'] );
        $args = array( 'posts_per_page' => -1, 'post_type' => 'sfwd-courses', 'post_status'   => 'publish' );
        $courses = get_posts( $args );
        $loop_num = 0;
        $course_schedules = array();
        foreach( $courses as $course )  {
            $data = get_post_meta( $course->ID, 'course_schedule', true );
            if(is_array($data)){
                foreach( $data as $arr )  {
                $event_id = $this->uniq_event_id(0,120,1);
                $schd_date = explode( "-", $arr['start_date'] );
                    if( $date_year === $schd_date[0] ) {
                        $course_schedules[$loop_num]['id'] = $event_id[0];
                        $course_schedules[$loop_num]['course_id'] = $course->ID;
                        $course_schedules[$loop_num]['title'] = $course->post_title;
                        $course_schedules[$loop_num]['start'] = $arr['start_date'];
                        $course_schedules[$loop_num]['end'] = $arr['end_date'];
                        $loop_num++;
                    } else {
                        continue;
                    }
                }
            }
        }

        $return = $course_schedules;
        wp_send_json( $return );
        exit( "true" );
    }

    /**
     * Reschedule the courses on resize fullcalendar
     */
    public function courseReschedule()
    {
	    $start_date = sanitize_text_field($_POST['date']);
	    $end_date = sanitize_text_field($_POST["end_date"]);

        $start_date_array = explode( "-", $start_date );
        $date_validated = wp_checkdate( $start_date_array[1], $start_date_array[2], $start_date_array[0], "Y-m-d" );
        if ( !$date_validated ) {
            exit( 'Invalid start date' );
        }


        if( date( "Y-m-d" ) > $start_date ) {
            exit( "false" );
        }

        if(!empty($end_date)) {
	        $end_date_array = explode("-", $end_date);
	        $end_date_validated = wp_checkdate($end_date_array[1], $end_date_array[2], $end_date_array[0], "Y-m-d");
            if ( !$end_date_validated ) {
	            exit( 'Invalid end date' );
            }
        }

        $course_id = learndash_get_course_id( absint($_POST['course_id']) );
        if( empty($course_id) ) {
	        exit( 'Invalid course ID' );
        }

        $ldcs_data = get_post_meta( $course_id, 'course_schedule', true );

        foreach ($ldcs_data as $key => $data){
           $start_key = $data['start_date'];
            $end_key = $data['end_date'];
            if($start_key > $start_date && $start_key < $end_date){

                unset( $ldcs_data[$key] );

            }elseif ($start_key == $start_date)
            {

                $ldcs_data[$key]['start_date'] = $start_date;
                $ldcs_data[$key]['end_date'] = $end_date;

            }elseif($start_key > $end_date){

                $ldcs_data[$key]['start_date'] = $start_key;
                $ldcs_data[$key]['end_date'] = $end_key;

            }
        }

        update_post_meta( $course_id, 'course_schedule', array_values($ldcs_data) );
        exit( "true" );
    }

    /**
     * Shift the courses on event drag
     */
    public function shiftCourseSchedule()
    {

        $ldcs_start_date = sanitize_text_field($_POST['newdatestart']);
        $date_start_check = explode( "-", $ldcs_start_date);
        $start_date_validated = wp_checkdate( $date_start_check[1], $date_start_check[2], $date_start_check[0], "Y-m-d" );
        if ( !$start_date_validated ) { //valid format check course start date
	        exit('Invalid start date');
        }

        if( date( "Y-m-d" ) > $ldcs_start_date ) { //past date check course start date
            exit( "false" );
        }

	    $ldcs_end_date = sanitize_text_field($_POST['newdateend']);
        if(!empty($ldcs_end_date)) {
            $date_end_check = explode("-", $ldcs_end_date);
            $end_date_validated = wp_checkdate($date_end_check[1], $date_end_check[2], $date_end_check[0], "Y-m-d");
            if (!$end_date_validated) { //valid format check course end date
                exit('Invalid end date');
            }
        }else{
            $ldcs_end_date="";
        }

       $ldcs_pstart_date = sanitize_text_field($_POST['prev_date']);
        $ldcs_pend_date = sanitize_text_field($_POST['prev_end_date']);
        if(!empty($ldcs_pend_date)) {
            $ldcs_pend_date="";
        }

        $course_id = learndash_get_course_id(absint($_POST['course_id']));
        if(empty($course_id)) {
	        exit('Invalid course ID');
        }

        $ldcs_data = get_post_meta( $course_id, 'course_schedule', true );
        $target_key = array_search($ldcs_pstart_date, array_column($ldcs_data, 'start_date'));

        $ldcs_data[$target_key]['start_date'] = $ldcs_start_date;
        $ldcs_data[$target_key]['end_date'] = $ldcs_end_date;
        update_post_meta( $course_id, 'course_schedule', $ldcs_data );
        exit( "true" );
    }
        /**
        }
     * Schedule the courses
     */
    public function addCourseSchedule() {

        $post_date = sanitize_text_field($_POST['date']);
        $date_v = explode( "-", $post_date );
        $date_validated = wp_checkdate( $date_v[1], $date_v[2], $date_v[0], "Y-m-d" );
        $data_meta = array();
        if ( !$date_validated ) {
            exit( 'Invalid date format' );
        }

        $date = $post_date;
        if( date( "Y-m-d" ) > $date ) {
            exit( "false" );
        }

	    $course_id = learndash_get_course_id(absint($_POST['course_id']));
	    if(empty($course_id)) {
		    exit('Invalid course ID');
	    }

        $data = get_post_meta( $course_id, 'course_schedule', true );

        if( ''  == $data ) {
            $data = array();
            $data['start_date']  = $date;
            $data['end_date']  = '';
            array_push($data_meta,  $data);

        }else{
            $ldcs_date_key = array_search($date ,$data);
            if(empty($ldcs_date_key)){
                $ldcs_new_schedule['start_date']  = $date;
                $ldcs_new_schedule['end_date']  = '';
                array_push($data,  $ldcs_new_schedule);
                $data_meta = $data;
            }
        }
        update_post_meta( $course_id, 'course_schedule', $data_meta );
        exit( "true" );
    }

    /**
     * Remove courses from schedule
     */
    public function removeCourseSchedule() {
	    $date = sanitize_text_field($_POST['date']);

        $date_v = explode( "-", $date );
        $date_validated = wp_checkdate( $date_v[1], $date_v[2], $date_v[0], "Y-m-d" );
        if ( !$date_validated ) {
            exit( "false" );
        }

        $course_id = learndash_get_course_id(absint($_POST['course_id']));
	    if(empty($course_id)) {
		    exit('Invalid course ID');
	    }

        $ldcs_data = get_post_meta( $course_id, 'course_schedule', true );

        foreach ($ldcs_data as $key => $data){
            if($date == $data['start_date']){
                unset( $ldcs_data[$key] );
            }

        }
        /*$key = array_search( $date, $data );
        if( $key !== false ) {
            unset( $data[$key] );
        }*/

        update_post_meta( $course_id, 'course_schedule', $ldcs_data );
        exit( "true" );
    }

    /**
     * Adds Reporting Chart menu page
     */
    public function ldccAdminMenu() {

        if( ! class_exists( 'Wn_Plugin_Settings_API' ) ){
            require_once CS_LD_INCLUDES_DIR . 'settings/class.settings-api.php';
            require_once CS_LD_INCLUDES_DIR . 'settings/settings.php';
        }

        
        if( ! class_exists( 'WN_DASHBOARD_Page' ) )
            require_once CS_LD_INCLUDES_DIR . 'wn-dashboard.php';

        // Create main menu
        global $menu,  $submenu;
		$menuExist = false;
		$setting_menuExist = false;
        foreach($menu as $item) {
            if(strtolower($item[2]) == 'course-schedular') {
                $menuExist = true;
			}
		}
        
        if (isset( $submenu[ 'course-schedular' ] ) && in_array( 'wooninjas-dashboard-setting', wp_list_pluck( $submenu[ 'course-schedular' ], 2 ) ) ) {
			$setting_menuExist = true;
		} else {
			$setting_menuExist = false;
		}
        add_menu_page( __( 'Course Scheduler', 'cs_ld_addon' ), __( 'Course Scheduler', 'cs_ld_addon' ), 'manage_options', 'calendar_course',  array( $this, ( 'csView' ) ), 'dashicons-calendar-alt' );

        /**
         * Add Setting Page
         */
        add_submenu_page(
            'calendar_course',
            'Calendar',
            'Calendar',
            'manage_options',
            'calendar_course',
            array( $this, ( 'csView' ) ),
            1
        );

        if( ! $menuExist ) {
            if( class_exists( 'WN_DASHBOARD_Page' ) ) {
                $obj_dashboard = new WN_DASHBOARD_Page();
                // add_submenu_page( 'course-schedular', __( 'Dashboard', 'cs_ld_addon' ), __( 'Dashboard', 'cs_ld_addon' ), 'manage_options', 'wooninjas-dashboard', [ $obj_dashboard, 'dashboard_page' ] );
            }

            if( class_exists( 'WN_DASHBOARD_SETTINGS' ) ) {
                $obj_settings = new WN_DASHBOARD_SETTINGS();
                add_submenu_page( 'calendar_course', __( 'Settings', 'cs_ld_addon' ), __( 'Settings', 'cs_ld_addon' ), 'manage_options', 'wooninjas-dashboard-setting', [ $obj_settings, 'settings_page' ] );
            }
        } elseif( ! $setting_menuExist ) {
            if( class_exists( 'WN_DASHBOARD_SETTINGS' ) ) {
                $obj_settings = new WN_DASHBOARD_SETTINGS();
                add_submenu_page( 'calendar_course', __( 'Settings', 'wn-plugin-boilerplate' ), __( 'Settings', 'wn-plugin-boilerplate' ), 'manage_options', 'wooninjas-dashboard-setting', [ $obj_settings, 'settings_page' ] );
            }
		}
      
    }

    /**
     * Loads calender view
     */
    public function csView() {
        $args = array( 'posts_per_page' => -1, 'post_type' => 'sfwd-courses', 'post_status'   => 'publish' );
        $courses = get_posts( $args );
        if( file_exists( dirname(__FILE__) . '/views/calendar_view.php' ) ) {
            require_once( dirname(__FILE__) . '/views/calendar_view.php' );
        }
    }

    /**
     * Add Help Tab
     */
    public static function add_help_tab() {
        $screen = get_current_screen();
        if( $screen->base !== "toplevel_page_calendar_course" )
            return;

        $screen->add_help_tab( array(
            "id"	    => "cs-ld-course-scheduler-details",
            "title"	    => __( LearnDash_Custom_Label::get_label( 'course' ) . " Scheduler", "cs_ld_addon" ),
            "content"	=>
                "<p>" . __( "Drag published " . LearnDash_Custom_Label::get_label( 'courses' ) . " on left to the calender to schedule them on specific dates or show them on all dates except the ones added on the calendar, this is based on the settings saved for the addon.", "cs_ld_addon" ) . "</p>" .
                "<p>" . __( "Each ". LearnDash_Custom_Label::get_label( 'course' ) ." can be dragged and dropped on the calendar multiple times and multiple " . LearnDash_Custom_Label::get_label( 'courses' ) . " can be scheduled for the same date.", "cs_ld_addon" ) . "</p>" .
                "<p>" . __( "Click the cross icon to remove the ". LearnDash_Custom_Label::get_label( 'course' ) ." from calendar, you also have the option to move the dropped ". LearnDash_Custom_Label::get_label( 'courses' ) ." to other dates on the calendar by simply drag and drop from the previous date to the new date" ) . "</p>".
                "<p>" . __( "Already dropped ". LearnDash_Custom_Label::get_label( 'courses' ) ." would always show up on the calendar", "cs_ld_addon" ) . "</p>"
        ) );
    }

    /**
     * Add footer branding
     *
     * @param $footer_text
     * @return mixed
     */
    function remove_footer_admin ( $footer_text ) {
	    if( isset($_GET['page']) ) {

	        $page = sanitize_text_field($_GET['page']);

	        if( $page === 'calendar_course_settings' || $page === 'calendar_course' ) {
		        $footer_text = __('Fueled by <a href="http://www.wordpress.org" target="_blank">WordPress</a> | developed and designed by <a href="https://wooninjas.com" target="_blank">The WooNinjas</a></p>', 'cs_ld_addon');
            }

            return $footer_text;
        }
    }

    /**
     * Show info notification on calender page
     */
    public static function add_help_notification () {
        $screen = get_current_screen();

        if( isset( $_GET['csld_dismiss_notice'] ) && absint($_GET['csld_dismiss_notice']) === 1 ) {
            set_transient('csld_review_dismissed', 1, DAY_IN_SECONDS);
        }

        if( isset($screen->id) && $screen->id === "toplevel_page_calendar_course" ) {
            ?>
            <div class="notice notice-info" style="margin-top:50px;">
                <p><?php _e( 'Drag and Drop ' . LearnDash_Custom_Label::get_label( 'course' ) . ' from the left on the calendar to assign it <strong>on specific dates</strong> OR <strong>except the specific dates</strong> depending on your <a href="'.admin_url().'admin.php?page=calendar_course_settings">settings</a> here', "cs_ld_addon" ); ?></p>
            </div>
            <div class="notice notice-error invalid-date" style="display:none;margin-top:20px;">
                <p><?php _e( 'You cannot schedule a '. LearnDash_Custom_Label::get_label( 'course' ) .' for a past date.', "cs_ld_addon" ); ?></p>
            </div>
            <?php
        } else {
            $user_data = get_userdata(get_current_user_id());
            $csld_review_dismissed = get_transient('csld_review_dismissed');
            $dismiss_url = add_query_arg( 'csld_dismiss_notice', 1 );

            if ( ! $csld_review_dismissed ) {
                ?>
                <div class="notice notice-info">
                    <?php _e('<p>Hi <strong>' . $user_data->user_nicename . '</strong>, thank you for using <strong>Course Scheduler for LearnDash</strong>. If you find our plugin useful kindly take some time to leave a review and rating for us <a href="https://wordpress.org/plugins/course-scheduler-for-learndash/" target="_blank" ><strong>here</strong></a>. </p><p><a href="'.esc_attr($dismiss_url).'">Dismiss</a></p>', 'cs_ld_addon'); ?>
                </div>
                <?php
            }
        }
    }

    /**
     * Return specified dates for
     * Courses, Lessons, Quizzes, Topics
     *
     * @return bool
     */
    public function get_specified_dates() {

        global $post;
        $post_id = $post->ID;
        $course_dates = "";

        if( $post->post_type == "sfwd-courses" ) {

            $course_dates = get_post_meta( $post_id, "course_schedule", true );

        } elseif( $post->post_type == "sfwd-lessons" ) {

            $lesson_details = get_post_meta( $post_id, "_sfwd-lessons", true );
            $course_id = $lesson_details["sfwd-lessons_course"];
            $course_dates = get_post_meta( $course_id, "course_schedule", true );

        } elseif( $post->post_type == "sfwd-topic" ) {

            $topic_details = get_post_meta( $post_id, "_sfwd-topic", true );
            $course_id = $topic_details["sfwd-topic_course"];
            $course_dates = get_post_meta( $course_id, "course_schedule", true );

        } elseif( $post->post_type == "sfwd-quiz" ) {

            $quiz_details = get_post_meta( $post_id, "_sfwd-quiz", true );
            $course_id = $quiz_details["sfwd-quiz_course"];
            $course_dates = get_post_meta( $course_id, "course_schedule", true );

        }

        // No Course Dates Set
        if( ! $course_dates ) {
            return false;
        }

        $available_dates = '<ul>';
       $format = get_option( 'date_format' );

        foreach( $course_dates as $key => $course_date ) {

           if( date( 'Y-m-d' ) <= $course_date['start_date'] ) {
                   $new_start_date = date($format, strtotime($course_date['start_date']));
                   $new_end_date = date($format, strtotime($course_date['end_date']));
                     if(!empty($course_date['end_date'])) {
                         $available_dates .= "<li>" . $new_start_date . "-" . $new_end_date . "</li>";
                     }else{
                         $available_dates .= "<li>" . $new_start_date . "</li>";
                     }

           }

        }
        $available_dates .= '</ul>';
        $available_course_date = rtrim( $available_dates, " ," );
        return $available_course_date;
    }

    public function search_courses_cb() {

        if( !wp_doing_ajax() ) {
            return;
        }

        if( !check_ajax_referer('ld-cms-nonce', 'security')) {
            return;
        }

        if(isset($_POST['ld_cms_course_list_page_num'])) {
            $ld_cms_page = absint($_POST['ld_cms_course_list_page_num']);
        }

        $per_page_count = 5;

        if(empty($ld_cms_page)) {
            $ld_cms_page = 1;
        }

        $args = array(
            'posts_per_page' => $per_page_count,
            'post_type' => learndash_get_post_type_slug('course'),
            'post_status' => 'publish',
            'paged' => $ld_cms_page
        );

        if(isset($_POST['search_text'])) {
            $args['s'] = sanitize_text_field($_POST['search_text']);
        }

        $course_query = new WP_Query( $args ); //Used inside template below
        $max_num_pages = $course_query->max_num_pages;

        ob_start();
        include_once dirname(__FILE__) . '/views/calendar_view_ajax.php';
        $content = ob_get_clean();
        $response = array('content' => $content);


        if($ld_cms_page < $max_num_pages) {
            $response['next_page'] = $ld_cms_page + 1;
        } else {
            $response['next_page'] = 0;
        }

        wp_send_json( $response );
    }

    public function uniq_event_id($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

}
return new CS_LD_HELPER();