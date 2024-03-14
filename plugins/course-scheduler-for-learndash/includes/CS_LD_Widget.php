<?php
/**
 * Course Scheduler Widget
 *
 * This widget will display list of courses/lessons/topics/quizzes with their defined scheduled dates in an accordion.
 */

/**
 * Adds CS_LD_Widget widget.
 */
class CS_LD_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
            'cs_ld_widget', // Base ID
            esc_html__( 'Scheduled Courses', CS_LD_TEXT_DOMAIN ), // Name
            array( 'description' => esc_html__( 'Display scheduled '. LearnDash_Custom_Label::get_label( 'courses' ) .', '.LearnDash_Custom_Label::get_label( 'lessons' ).', '.LearnDash_Custom_Label::get_label( 'topics' ).' and '.LearnDash_Custom_Label::get_label( 'quizzes' ).'. Visible only to logged in users.', CS_LD_TEXT_DOMAIN ), ) // Args
        );

        add_action('wp_enqueue_scripts', [$this, 'wn_ld_cs_enqueue_scripts']);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $listing_data = $this->getEvents();
        if( empty($listing_data) ) {
            return;
        }

        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        $this->output($instance, $listing_data);
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Scheduled ' . LearnDash_Custom_Label::get_label( 'courses' ), CS_LD_TEXT_DOMAIN );
        $available_message = ! empty( $instance['available_message'] ) ? $instance['available_message'] : esc_html__( 'Your enrolled '.LearnDash_Custom_Label::get_label( 'courses' ).'/'.LearnDash_Custom_Label::get_label( 'lessons' ).'/'.LearnDash_Custom_Label::get_label( 'topics' ).' and '.LearnDash_Custom_Label::get_label( 'quizzes' ).' are scheduled to available on following dates.', CS_LD_TEXT_DOMAIN );
        $unavailable_message = ! empty( $instance['unavailable_message'] ) ? $instance['unavailable_message'] : esc_html__( 'Your enrolled '.LearnDash_Custom_Label::get_label( 'courses' ).'/'.LearnDash_Custom_Label::get_label( 'lessons' ).'/'.LearnDash_Custom_Label::get_label( 'topics' ).' and '.LearnDash_Custom_Label::get_label( 'quizzes' ).' are scheduled to be unavailable on following dates.', CS_LD_TEXT_DOMAIN );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', CS_LD_TEXT_DOMAIN ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'available_message' ) ); ?>"><?php esc_attr_e( 'Available Message:', CS_LD_TEXT_DOMAIN ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'available_message' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'available_message' ) ); ?>"><?php echo esc_attr( $available_message ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'unavailable_message' ) ); ?>"><?php esc_attr_e( 'Unavailable Message:', CS_LD_TEXT_DOMAIN ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'unavailable_message' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'unavailable_message' ) ); ?>"><?php echo esc_attr( $unavailable_message ); ?></textarea>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['available_message'] = ( ! empty( $new_instance['available_message'] ) ) ? sanitize_text_field( $new_instance['available_message'] ) : '';
        $instance['unavailable_message'] = ( ! empty( $new_instance['unavailable_message'] ) ) ? sanitize_text_field( $new_instance['unavailable_message'] ) : '';

        return $instance;
    }

    /**
     * Rendered added events on calender
     */
    public function getEvents() {

        $user_id = get_current_user_id();
        $course_ids = ld_get_mycourses($user_id);
        $user_completed_activity_ids = $this->get_user_completed_activities($user_id);
        $listing_data = array();

        foreach ( $course_ids as $course_id ) {

            if( in_array($course_id, $user_completed_activity_ids) )
                continue;

            $scheduled_dates = $this->get_scheduled_dates($course_id);

            if( !empty($scheduled_dates) ) {

                $lesson_ids = learndash_course_get_steps_by_type($course_id, 'sfwd-lessons');
                $topic_ids = learndash_course_get_steps_by_type($course_id, 'sfwd-topic');
                $quiz_ids = learndash_course_get_steps_by_type($course_id, 'sfwd-quiz');

                $listing_data['scheduled_dates'][$course_id] = $scheduled_dates;

                $listing_data['courses'][$course_id] = get_the_title($course_id);

                //Lessons
                foreach ($lesson_ids as $lesson_id) {
                    if( !in_array($lesson_id, $user_completed_activity_ids) )
                    $listing_data['lessons'][$lesson_id] = array('course_id' => $course_id, 'title' => get_the_title($lesson_id));
                }

                //Topics
                foreach ($topic_ids as $topic_id) {
                    if( !in_array($topic_id, $user_completed_activity_ids) )
                    $listing_data['topics'][$topic_id] = array('course_id' => $course_id, 'title' => get_the_title($topic_id));
                }

                //Quizzes
                foreach ($quiz_ids as $quiz_id) {
                    if( !in_array($quiz_id, $user_completed_activity_ids) )
                    $listing_data['quizzes'][$quiz_id] = array('course_id' => $course_id, 'title' => get_the_title($quiz_id));
                }
            }
        }

        return $listing_data;
    }


    /**
     * Prepare widget HTML with listing data
     *
     * @param $instance
     * @param $listing_data
     */
    private function output($instance, $listing_data) {

        $available_message = ! empty( $instance['available_message'] ) ? $instance['available_message'] : esc_html__( 'Your enrolled '.LearnDash_Custom_Label::get_label( 'courses' ).'/'.LearnDash_Custom_Label::get_label( 'lessons' ).'/'.LearnDash_Custom_Label::get_label( 'topics' ).'and '.LearnDash_Custom_Label::get_label( 'quizzes' ).' are scheduled to available on following dates.', CS_LD_TEXT_DOMAIN );
        $unavailable_message = ! empty( $instance['unavailable_message'] ) ? $instance['unavailable_message'] : esc_html__( 'Your enrolled '.LearnDash_Custom_Label::get_label( 'courses' ).'/'.LearnDash_Custom_Label::get_label( 'lessons' ).'/'.LearnDash_Custom_Label::get_label( 'topics' ).' and '.LearnDash_Custom_Label::get_label( 'quizzes' ).' are scheduled to be unavailable on following dates.', CS_LD_TEXT_DOMAIN );
        
        $general_settings = get_option( 'wn_course_schedular_general_settings' );
        $is_available = ! empty( $general_settings['show_courses'] ) ? $general_settings['show_courses'] : 0 ;

        if( !$is_available ) {
            $message = $available_message;
        } else {
            $message = $unavailable_message;
        }

        ?>
        <p><?php _e( $message, CS_LD_TEXT_DOMAIN ); ?></p>
        <div id="ld_cs_listing">
            <?php

            foreach ($listing_data as $content_type => $contents) {

                if($content_type == 'scheduled_dates') {
                    $scheduled_dates = $contents;
                }

                echo $this->schedule_block_html($content_type, $contents, $scheduled_dates);
            }
            ?>
        </div>
        <?php
    }

    /**
     * @param $content_type
     * @param $contents
     * @param $scheduled_dates
     * @return string|void
     */
    private function schedule_block_html($content_type, $contents, $scheduled_dates) {

        if($content_type == 'scheduled_dates') {
            return;
        }

        $col_name = ucwords($content_type);
        $title_text = esc_html__('Title', CS_LD_TEXT_DOMAIN );
        $date_text = esc_html__('Date', CS_LD_TEXT_DOMAIN );

        $html = "";

        $html .= "<h3>{$col_name}</h3>\n";
        $html .= "<div class=\"accordion_item\">\n";
        $html .= "<table>\n<tr>\n<th width=\"60%\">{$title_text}</th>\n<th>{$date_text}</th>\n</tr>\n";

        foreach ($contents as $content_id => $content) {

            $permalink = get_permalink($content_id);

            if( $content_type == 'courses' ) { //Courses

                $course_id = $content_id;
                $title = $content;

            } else { //Lessons/Topics/Quizzes

                $course_id = $content['course_id'];
                $title = $content['title'];
            }

            $scheduled_dates_string = implode(' / ', $scheduled_dates[$course_id]);

            $html .= "<tr>\n";
            $html .= "<td width=\"60%\"><a href=\"{$permalink}\">{$title}</a></td>\n";
            $html .= "<td>{$scheduled_dates_string}</td>\n";
            $html .= "</tr>\n";
        }

        $html .= "</table>\n";
        $html .= "</div>\n";

        return $html;
    }

    private function get_scheduled_dates($course_id) {

        $scheduled_dates = get_post_meta($course_id, 'course_schedule', true);

        if( !empty($scheduled_dates) ) {

            //Return today or future dates only
            $scheduled_dates = array_filter( array_map( function($date) {

                $start_date=$date['start_date'];
                if( $start_date >= date('Y-m-d') ) {
                    return date('d M, Y', strtotime($start_date) );
                }
            }, $scheduled_dates) );

            //Sort dates
            usort($scheduled_dates, function ($date1, $date2) {
                return strtotime($date1) - strtotime($date2);
            });
        }

        //TODO: If dates are set in continuity, return that date range

        return $scheduled_dates;
    }

    private function get_user_completed_activities($user_id) {
        global $wpdb;

        $query = "SELECT DISTINCT LUA.`post_id` FROM wp_learndash_user_activity AS LUA WHERE LUA.`user_id`= %d AND LUA.`activity_status` = %d ORDER BY LUA.`course_id` ASC";
        $user_ld_activities = $wpdb->get_col($wpdb->prepare($query, $user_id, 1));

        return $user_ld_activities;
    }

    /**
     * Enqueue JQuery UI and accordion scripts
     */
    function wn_ld_cs_enqueue_scripts() {
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script( 'ld-cs-widget', CS_LD_PLUGIN_URL . "/assets/js/widget.js", array( 'jquery' ), false );
        wp_enqueue_style( 'jquery-ui', CS_LD_PLUGIN_URL . "/assets/css/jquery-ui.css", array() );
        wp_enqueue_style( 'ld-cs-widget', CS_LD_PLUGIN_URL . "/assets/css/widget.css", array() );
    }

} // class CS_LD_Widget

// register CS_LD_Widget widget
function register_cs_ld_widget() {
    if( is_user_logged_in() ) {
        register_widget('CS_LD_Widget');
    }
}
add_action( 'widgets_init', 'register_cs_ld_widget' );