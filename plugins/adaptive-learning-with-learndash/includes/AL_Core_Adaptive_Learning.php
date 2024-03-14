<?php
/**
 * Assign child course based on parent course performance/adaptive learning implementation
 *
 * @author   WooNinjas
 * @category Admin
 * @package  AdaptiveLearningWithLearnDash/Classes
 * @version  1.4
 */

if ( ! defined( "ABSPATH" ) ) {
    exit;
}

/**
 * AL_Core_Adaptive_Learning Class.
 */
class AL_Core_Adaptive_Learning {

    static protected $user;
    static protected $user_id;
    static protected $parent_course;
    static protected $parent_course_id;
    static protected $child_course;
    static protected $child_course_id;
    static protected $level;
    static protected $from_perc;
    static protected $to_perc;
    static protected $avg_perc;
    static protected $compare_field = "sfwd-courses_course_prerequisite_compare";

    public function __construct () {
        add_action( "init", array ( __CLASS__, "set_user_id" ) );
        add_action( "learndash_course_completed", array ( __CLASS__, "course_completed" ), 10, 1 );
        add_action( "show_user_profile", array( __CLASS__, "al_stats_fields" ) );
        add_action( "edit_user_profile", array( __CLASS__, "al_stats_fields" ) );
        add_action( "edit_user_profile_update", array( __CLASS__, "admin_user_update" ) );
        add_action( "admin_init", array( __CLASS__, "dismiss_branding_notification" ) );
        add_action( "admin_notices", array( __CLASS__, "furthur_assistance_review" ) );
        add_action( "wp_footer", array( __CLASS__, "course_assign_notification_initiate" ) );
        add_filter( 'ld_after_course_status_template_container', array ( __CLASS__, 'add_content_after_course_status' ), 10, 4 );
    }

    /**
     * Display course assigning Notification
     *
     * @return bool
     */
    public static function course_assign_notification_initiate() {

        if( ! is_singular( learndash_get_post_type_slug('course') ) ) {
            return false;
        }

        if( ! is_user_logged_in() ) {
            return false;
        }

        $user_id = get_current_user_id();

        $transient_key = "ld_al_assigned_courses_{$user_id}";
        $assigned_course_ids = get_transient($transient_key);
        if(!empty($assigned_course_ids)) { ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    <?php foreach ($assigned_course_ids as $assigned_course_id): ?>
                    toastr.success('<?php echo get_the_title($assigned_course_id); ?>', '<?php echo apply_filters('ld_al_notify_assign_course_title', __('You have been assigned a course', 'ld-adaptive-learning'), $assigned_course_id); ?>', {timeOut: 5000, "newestOnTop": false,"progressBar": true});
                    <?php endforeach; ?>
                });
            </script>

        <?php

            delete_transient($transient_key);
        }
    }

    /**
     * Show user stats as a table
    */
    public static function al_stats_fields () {
        $stats = get_user_meta( self::$user_id, "ld_adaptive_learning_stats", 1 );
        
        if( !$stats ) {
            return false;
        }

        echo "<h2>" . __( "Adaptive Learning Stats:", "ld-adaptive-learning" ) . "</h2> <hr />";

        foreach ( $stats as $parent_course_id => $child_courses ) {
            ?>
            <h3><?php
                $parent_course_id_exists = learndash_get_course_id($parent_course_id);
                if(!empty($parent_course_id_exists)) {
                    echo get_the_title($parent_course_id);
                } else {
                    echo $parent_course_id;
                }

                ?></h3>
                <?php foreach ( $child_courses as $child_course ) { ?>
                <div class="stats_wrapper">
                    <p><strong><?php _e("Child Course", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["child"]); ?></p>
                    <p><strong><?php _e("Child Level", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["level"]); ?></p>
                    <p><strong><?php _e("From Percentage", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["from_perc"]); ?></p>
                    <p><strong><?php _e("To Percentage", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["to_perc"]); ?></p>
                    <p><strong><?php _e("Avg. Course Percentage", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["avg_perc"]); ?></p>
                    <p><strong><?php _e("Course Assignment Time", "ld-adaptive-learning"); ?>
                            :</strong> <?php _e($child_course["time"]); ?></p>
                </div>

                <?php
            }
        }
    }

    /**
     * Sets user ID variable on init
     */
    public static function set_user_id () {

        /**
         * If course completed from admin user_id from GET var
         */
        if ( isset ( $_POST["user_id"] ) ) {
            self::$user_id = intval( $_POST["user_id"] );
        } elseif ( isset ( $_GET["user_id"] ) ) {
            self::$user_id = intval ( $_GET["user_id"] );
        }

        if( !self::$user_id ) {
            self::$user = wp_get_current_user();
            self::$user_id = get_current_user_id();
        }

        return self::$user_id;
    }

    /**
     * Implements the LD `course_completed` hook
     *
     * @param course data $data
     * @return null
     */

    public static function course_completed ( $data ) {

        self::$user_id = $data['user']->ID;

        // If not parent no need to proceed with calculations
        $is_parent_course = is_parent_course ( $data["course"] );

        $course = $data["course"] ;

        if( !self::$user_id ) {
            self::$user_id = get_current_user_id();
        }
        if ( !self::$user_id || !$is_parent_course ) {
            return false;
        }

        //quiz ids for this course
        $quiz_ids = array();
        $course = get_post( $course->ID );
        $quiz_ids = get_quiz_ids_for_course ( $course->ID );
        $quiz_data = self::get_quiz_data ( self::$user_id );

        if ( !$quiz_data ) {
            return false;
        }

        foreach ( $quiz_data as $key => $single_quiz ) {
            if ( !in_array ( $single_quiz["quiz"], $quiz_ids ) ) {
                unset ( $quiz_data[$key] );
            }
        }

        if ( !$quiz_data ) {
            return false;
        }

        // save parent course ID and Name 
        self::$parent_course_id = $course->ID;
        self::$parent_course = $course->post_title;

        $course_level = self::get_course_level ( $quiz_data, self::$parent_course_id );

        $level_id = self::get_course_level_id ( $course_level );

        self::assigned_course ( $level_id );

        if ( !self::$child_course ) {
            return false;
        }
    }

    public static function update_stats() {

        $stats = get_user_meta ( self::$user_id, "ld_adaptive_learning_stats", 1 );

        if( empty($stats) ) return false;

        $new_stats_update_key = 'ld_al_user_stats_updated';
        $new_stats_updated = get_user_meta ( self::$user_id, $new_stats_update_key, 1 );

        if( !empty($stats) && is_array($stats) && empty($new_stats_updated) ) {

            $new_stats = array();

            foreach ($stats as $stat_key => $stat_value) {

                if( !is_numeric($stat_key) && absint($stat_key) == 0 ) {
                    $course = get_page_by_title( $stat_key, ARRAY_A, learndash_get_post_type_slug('course') );
                    $child_course = get_page_by_title( $stat_value['child'], ARRAY_A, learndash_get_post_type_slug('course') );
                    $course_id = (isset($course['ID']) && !empty($course['ID'])) ? $course['ID'] : $stat_key;
                    $child_course_id = (isset($child_course['ID']) && !empty($child_course['ID'])) ? $child_course['ID'] : $stat_value['child'];
                    $new_stats[$course_id][$child_course_id] = $stat_value;
                }
            }

            if(!empty($new_stats)) {
                update_user_meta(self::$user_id, $new_stats_update_key, 1);
                update_user_meta(self::$user_id, "ld_adaptive_learning_stats", $new_stats);
            }
        }
    }

    /**
     * Display Associated Course
     *
     * @param $output
     * @param $course_status
     * @param $course_id
     * @param $user_id
     * @return bool|string
     */
    public static function add_content_after_course_status( $output, $course_status, $course_id, $user_id ) {

        if( ! is_singular( 'sfwd-courses' ) ) {
            return false;
        }

        if( ! is_user_logged_in() ) {
            return false;
        }

        if( !$course_id || get_post_type( $course_id ) != 'sfwd-courses' ) {
            return false;
        }

        if( 'complete' != strtolower( $course_status ) ) {
            return false;
        }

        if ( is_parent_course ( get_post( $course_id ) ) && ld_course_check_user_access($course_id, $user_id) ) {

            self::update_stats();

            $ld_al_stats = get_user_meta( $user_id, 'ld_adaptive_learning_stats', true );

            if( !empty($ld_al_stats) && is_array($ld_al_stats) ) {

                /**
                 * Filter
                 * @param $course_id int Parent Course ID
                 * @param $user_id int Current logged-in User ID
                 */
                $output .= apply_filters('ld_al_associated_courses_text', __('<br/><strong>Associated Course(s):</strong>', 'ld-adaptive-learning'), $course_id, $user_id);

                $child_courses = array();
                if(isset($ld_al_stats[$course_id])) {
                    foreach ($ld_al_stats[$course_id] as $child_course_id => $child_course) {
                        $child_courses[$child_course_id] = $child_course['child'];
                    }
                }

                //Loop through all user completed child courses and check if current parent course exists in any child course, then display it
                foreach ($ld_al_stats as $ld_al_stat) {
                    foreach ($ld_al_stat as $child_course_id => $child_course) {

                        if( in_array($course_id, learndash_get_course_prerequisite($child_course_id)) ) {
                            $child_courses[$child_course_id] = $child_course['child'];
                        }
                    }
                }

                /**
                 * Filter
                 * @param $child_courses array Child courses array
                 * @param $course_id int Parent Course ID
                 * @param $user_id int Current logged-in User ID
                 */
                $child_courses = apply_filters('ld_al_associated_child_course', $child_courses, $course_id, $user_id);

                foreach ($child_courses as $child_course_id => $child_course_title) {
                    $output .= sprintf('<a href="%s"> %s</a>', get_permalink($child_course_id), $child_course_title);
                }
            }
        }

        return $output;
    }
    
    /**
     * Uses the code from `learndash_profile` shortcode in ld-course-info-widget.php
     *
     * @param $user_id
     * @return $quiz_attempts (Quiz datas)
     */
    public static function get_quiz_data ( $user_id ) {
        $atts["user_id"]= $user_id;

        $usermeta = get_user_meta ( $atts["user_id"], "_sfwd-quizzes", true );
        $quiz_attempts_meta = empty ( $usermeta ) ? false : $usermeta;
        $quiz_attempts = array();

        if ( ! empty( $quiz_attempts_meta ) ) {
            foreach ( $quiz_attempts_meta as $quiz_attempt ) {
                $c = learndash_certificate_details ( $quiz_attempt["quiz"], $atts["user_id"] );
                $quiz_attempt["post"] = get_post ( $quiz_attempt["quiz"] );
                $quiz_attempt["percentage"] = !empty ( $quiz_attempt["percentage"] ) ? $quiz_attempt["percentage"] : ( !empty ( $quiz_attempt["count"] ) ? $quiz_attempt["score"] * 100 / $quiz_attempt["count"] : 0 );
                
                if ( $atts["user_id"] == self::$user_id && !empty( $c["certificateLink"] ) && ( ( isset ( $quiz_attempt["percentage"] ) && $quiz_attempt["percentage"] >= $c["certificate_threshold"] * 100 ) ) ) {
                    $quiz_attempt["certificate"] = $c;
                }

                $quiz_attempts[] = $quiz_attempt;
            }
        }
        return $quiz_attempts;
    }

    /**
     * Evaluate Course level from Quiz Percentages
     *
     * @param $quiz_data
     * @return course level
     */
    public static function get_course_level ( $quiz_data, $p_course_id ) {
        if ( !$quiz_data || !is_array ( $quiz_data ) ) {
            return false;
        }
        $perc = 0;
        $total_quiz = count ( $quiz_data );

        // Calculates the avg perc for perc of all quiuzzes
        foreach ( $quiz_data as $data ) {
            $perc = $perc + $data["percentage"];
        }
        $avg_perc = $perc / $total_quiz;
        
        // save avg_perc
        self::$avg_perc = $avg_perc;
        
        $arr = get_user_meta( self::$user_id, "ld_al_pre_req_course_perc", 1 );

        if(!is_array($arr)) {
            $arr = array();
        }
        $arr[$p_course_id] = self::$avg_perc;

        // save avg perc for the course
        update_user_meta( self::$user_id , "ld_al_pre_req_course_perc", $arr );
        return $avg_perc;
    }

    /**
     * Evaluate Course level for All Pre-Reqiuisite course quizzes
     *
     * @param $quiz_data
     * @return course level
     */
    public static function get_all_course_level ( $arr, $total_courses ) {

        // Calculates the avg perc for perc of all quiuzzes
        $total_perc = 0;
        if ( is_array( $arr ) && count( $arr ) > 0 ) {
            foreach ( $arr as $perc ) {
                $total_perc = $total_perc + $perc;
            }
        }

        $avg_perc = $total_perc / $total_courses;
        
        // save avg_perc
        self::$avg_perc = $avg_perc;

        return $avg_perc;
    }

    /**
     * Get course level id
     *
     * @param $course_level
     * @return bool
     */
    public static function get_course_level_id ( $course_level ) {
        $course_level = round( $course_level );

        if( !$course_level ) {
            if( $course_level != 0 ) {
            return false;
            }
        }
        $courses_level_args = array (
            "posts_per_page"   =>  -1,
            "post_type"     =>  "sfwd-courses-levels",
            "post_status"   =>  "publish"
        );
        $courses_levels = get_posts ( $courses_level_args );

        foreach ( $courses_levels as $courses_level ) {
            $level_meta = get_post_meta ( $courses_level->ID, "_sfwd-courses-levels", true );
            $from = $level_meta["sfwd-courses-levels_from_percentage"];
            $to = $level_meta["sfwd-courses-levels_to_percentage"];
            if ( $from <= $course_level && $to >= $course_level ) {
                // save level name
                self::$level = $courses_level->post_title;

                // save from perc
                self::$from_perc = $from;

                // save to perc
                self::$to_perc = $to;

                return $courses_level->ID;
            } else {
                continue;
            }
        }

        return $course_level;
    }

    /**
     * Assign child course to user
     *
     * @param $level_id
     * @return bool
     */
    public static function assigned_course ( $level_id ) {
        if ( !$level_id ) {
            return false;
        }

        $courses_args = array (
            "posts_per_page"   =>  -1,
            "post_type"     =>  "sfwd-courses",
            "post_status"   =>  "publish",
            "post__not_in" => ld_get_mycourses(self::$user_id)
        );

        $courses = get_posts ( $courses_args );

        foreach ( $courses as $course ) {
            $is_parent_course = is_parent_course ($course);

            // If not parent no need to proceed with the loop
            if ( $is_parent_course ) {
                continue;
            }

            $course_meta = get_post_meta ( $course->ID, "_sfwd-courses", true );
            $course_prereq = $course_meta["sfwd-courses_course_prerequisite"];


            if ( ! is_array( $course_prereq ) ) {
                $p_course_id = $course_prereq;

                // If not the child of the completed course
                if ( $p_course_id != self::$parent_course_id ) {
                    continue;
                }
            } else {
                $p_course_id_arr = $course_prereq;
                
                // If not the child of the completed course
                if ( ! in_array( self::$parent_course_id, $p_course_id_arr ) ) {
                    continue;
                } 
            }

            $compare_all_courses = isset ( $course_meta[ self::$compare_field ] ) && $course_meta[ self::$compare_field ] == "ALL";

            if ( $compare_all_courses ) {
                $total_prereq = count($course_prereq);
                $arr = get_user_meta( self::$user_id, "ld_al_pre_req_course_perc", 1 );

                $saved_prereq = 0;
                if( is_array( $arr ) ) {
                    $saved_prereq = count($arr);
                }

                // All Prereqs are completed
                if ( $saved_prereq ==  $total_prereq ) {
                    $course_level = self::get_all_course_level( $arr, $saved_prereq );
                    $level_id = self::get_course_level_id( $course_level );
                } else {
                    return false;
                }

            }

            $meta_level_id = ( int ) get_post_meta ( $course->ID, "sfwd-courses_course_level", true );

            if( $meta_level_id === $level_id ) {
                // save child course name
                self::$child_course = $course->post_title;
                self::$child_course_id = $course->ID;
                $user_id = self::$user_id;

                $stats = get_user_meta ( self::$user_id, "ld_adaptive_learning_stats", 1 );

                if( ! isset( $stats ) || !is_array( $stats ) ) {
                    $stats = [];
                }

                if( !isset( $stats[self::$parent_course_id] ) || !is_array( $stats[self::$parent_course_id] )  ) {
                    $stats[self::$parent_course_id] = [];
                }

                $child = array();

                $child["child"] = self::$child_course;
                $child["level"] = self::$level;
                $child["from_perc"] = self::$from_perc;
                $child["to_perc"] = self::$to_perc;
                $child["avg_perc"] = self::$avg_perc;
                $child["time"] = date ( "d M Y H:i:s", current_time ( "timestamp", 0 ) );

                $stats[self::$parent_course_id][self::$child_course_id] = $child;

                $stats = apply_filters ( "ld_al_stats_array", $stats );

                /**
                 * Save Stats Info in User Meta
                 */
                update_user_meta ( self::$user_id, "ld_adaptive_learning_stats", $stats );

                $transient_key = "ld_al_assigned_courses_{$user_id}";
                $assigned_course_ids = get_transient($transient_key);
                if( !is_array($assigned_course_ids) ) {
                    $assigned_course_ids = array();
                    $assigned_course_ids[] = $course->ID;
                } else{
                    $assigned_course_ids[] = $course->ID;
                }

                set_transient($transient_key, $assigned_course_ids, MONTH_IN_SECONDS);

                do_action ( "ld_al_before_child_course_assign", self::$user_id, $course->ID );
                ld_update_course_access ( self::$user_id, $course->ID );
            }
        }
    }

    /**
     * Executes on user update on backend, checks if user data is deleted
    */
    public static function admin_user_update( $user_id ) {
        $logged_user_id = get_current_user_id ();
        
        if ( !current_user_can ( 'edit_user', $logged_user_id ) ) {
            return;
        }

        if ( ! learndash_is_admin_user () ) {
            return;
        }

        if ( ! empty( $user_id ) && ! empty( $_POST['learndash_delete_user_data'] ) && $user_id == $_POST['learndash_delete_user_data'] ) {
            // Remove stats if user data removed
            delete_user_meta ( $user_id, "ld_adaptive_learning_stats" );
            delete_user_meta ( $user_id, "ld_al_pre_req_course_perc" );
        }
    }

    /**
     * Branding notification
     */
    public static function furthur_assistance_review() {
        $user_data = get_userdata( get_current_user_id() );
        $user_branding_meta = get_user_meta( get_current_user_id(), "rating_action", true );
        if( "confirmed" != $user_branding_meta && "temp_hide" != get_transient( "al-branding" ) ) {
            ?>
            <div class="notice notice-success" style="margin-top:20px;">
                <?php
                $text = sprintf(__('Hi <strong>%s</strong>, if you like our plugin then please take some time to leave a review and a rating for us <a href="https://wordpress.org/support/plugin/adaptive-learning-with-learndash/reviews/#new-post" target="_blank" ><strong>here</strong></a>. For any support or assistance reach us at: <br> <a href="https://wooninjas.com/contact/" target="_blank" class="al_support" >WooNinjas</a>', 'ld-adaptive-learning'), $user_data->user_login);
                $text .= '&nbsp;&nbsp;';
                $text .= sprintf('<form class="dismiss-form" method="post" action=""><input type="hidden" name="rating_action" value="dismiss" /><input type="hidden" name="user_id" value="%s" /><input type="submit" class="al_no_thanks" value="%s" /></form>', get_current_user_id(), __('No Thanks', 'ld-adaptive-learning'));
                ?>

                <div class="dismiss-wrapper"><?php echo $text; ?></div>
            </div>
            <?php
            delete_transient( "al-branding" );
        }
    }

    /**
     * Dismiss branding notification
     */
    public static function dismiss_branding_notification() {
        if( isset( $_POST ) ) {
            if( isset( $_POST["rating_action"] ) && $_POST["rating_action"] == "dismiss" ) {
                if( isset( $_POST["user_id"] ) ) {
                    $user_branding_meta = get_user_meta( $_POST["user_id"], "rating_action", true );
                    if( empty( $user_branding_meta ) ) {
                        update_user_meta( $_POST["user_id"], "rating_action", "temp" );
                        if( "temp_hide" != get_transient( "al-branding" ) ) {
                            set_transient( "al-branding", "temp_hide", 604800 );
                        }
                    } elseif( "temp" ) {
                        update_user_meta( $_POST["user_id"], "rating_action", "confirmed" );
                    }
                }
            }
        }
    }
}

return new AL_Core_Adaptive_Learning();