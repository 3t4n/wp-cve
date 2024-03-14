<?php

if (!defined('ABSPATH')) {
    exit; // disable direct access
}

class myCRED_Learndash_Badges {

    /**
     * Construct
     */

    public $refrences            = array();  
    public $assignment_refrences = array();  

    function __construct() {

        $this->refrences = array( 
            'learndash_course_complete'    => 'sfwd-courses', 
            'learndash_lesson_complete'    => 'sfwd-lessons', 
            'learndash_topic_complete'     => 'sfwd-topics', 
            'learndash_quiz_complete'      => 'sfwd-quiz', 
            'learndash_quiz_failed'         => 'sfwd-quiz',
            'learndash_join_group'         => 'groups',
            'learndash_course_enrollment'    => 'sfwd-courses',
            'learndash_complete_quiz_max_percent_grade'      => 'sfwd-quiz', 
            'learndash_quiz_range_percent_grade'      => 'sfwd-quiz'
        );

        $this->assignment_refrences = array( 
            'uploaded_assignment' => 'sfwd-assignment',
            'approved_assignment' => 'sfwd-assignment'
        );

        add_filter( 'mycred_badge_requirement',                   array( $this, 'mycred_learndash_badge_requirement' ), 10, 5 );
        add_filter( 'mycred_badge_requirement_specific_template', array( $this, 'mycred_learndash_badge_template' ), 10, 5 );
        add_action( 'admin_head',                                 array( $this, 'mycred_learndash_admin_header' ),999 );

      
        add_action('add_meta_boxes', array($this, 'mycred_override_badges_course'));
        // Add custom fileds in Lesson for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'mycred_override_badges_lesson'));
        // Add custom fileds in Topic for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'mycred_override_badges_topic'));
        // Add custom fileds in Quiz for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'mycred_override_badges_quiz'));

        /* Save overide data of Mycred */
        add_action('save_post', array($this, 'mycred_override_badge_data'));

    }


    // Add custom fileds in Course to assign badge
    public function mycred_override_badges_course() {
        add_meta_box('mycred_override_badges_course', __('myCred course badge', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-courses', 'normal', 'low');
    }

    public function mycred_override_badges_lesson() {
        add_meta_box('mycred_override_badges_lesson', __('myCred lesson badge', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-lessons', 'normal', 'low');
    }

   

    // Add custom fileds in Topic to assign badge
    public function mycred_override_badges_topic() {
        add_meta_box('mycred_override_badges_topic', __('myCred Topic badge', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-topic', 'normal', 'low');
    }

    // Add custom fileds in Quiz to assign badge
    public function mycred_override_badges_quiz() {
        add_meta_box('mycred_override_badges_quiz', __('myCred quiz badge', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-quiz', 'normal', 'low');
    }

    public function my_cred_override_data($post) {
        $badge = get_post_meta($post->ID, 'myCred_badges_override', true);
        include 'views/mycred_badges_override.php';
    }

    public function mycred_override_badge_data($post_id) {
        // Save logic goes here. Don't forget to include nonce checks!
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (isset($_POST['myCred_badges_override']) && $_POST['myCred_badges_override'] != "") {
            // Update myCred_point
            update_post_meta($post_id, 'myCred_badges_override', sanitize_text_field($_POST['myCred_badges_override']));
        } else {
            // delete myCred_point
            delete_post_meta($post_id, 'myCred_badges_override');
        }
    }


    public function mycred_learndash_badge_requirement( $query, $requirement_id, $requirement, $having, $user_id ){
        
        global $wpdb, $mycred_log_table;


        if( 
            array_key_exists( $requirement['reference'], $this->refrences ) && 
            ! empty( $requirement['specific'] ) && 
            $requirement['specific'] != 'Any' 
        ) 
        {
            $query = $wpdb->get_var( $wpdb->prepare( "SELECT {$having} FROM {$mycred_log_table} WHERE ctype = %s AND ref = %s AND ref_id = %d AND user_id = %d;", $requirement['type'], $requirement['reference'], $requirement['specific'], $user_id ) );
        }
        elseif ( 
            array_key_exists( $requirement['reference'], $this->assignment_refrences ) && 
            ! empty( $requirement['specific'] ) && 
            $requirement['specific'] != 'Any' 
        ) 
        {
            $requirement_type = get_post_type( $requirement['specific'] ); 

            $search_like = ( ( $requirement_type == 'sfwd-courses' ) ? 'badge_course_' : 'badge_lesson_' ) ;
            $search_like = '%'.$search_like.$requirement['specific'].'%';

            $query = $wpdb->get_var( $wpdb->prepare( "SELECT {$having} FROM {$mycred_log_table} WHERE ctype = %s AND ref = %s AND data like %s AND user_id = %d;", $requirement['type'], $requirement['reference'], $search_like, $user_id ) );
        }
        return $query;
    }

    public function mycred_learndash_badge_template( $data, $requirement_id, $requirement, $badge, $level ){

      
        if( 
            array_key_exists( $requirement['reference'], $this->refrences ) &&
            !empty($requirement['specific']) 
        ) 
        { 

            $learndash_courses = $this->get_all_posts( $this->refrences[ $requirement['reference'] ] );
            $learndash_courses_options = '<option>Any</option>';

            foreach ( $learndash_courses as $post ) {
                $learndash_courses_options .= '<option value="'.$post->ID.'" '.selected( $requirement['specific'], $post->ID, false ).' >'. htmlentities( $post->post_title, ENT_QUOTES ) .'</option>';
            }

            $data = '<div class="form-group"><select name="mycred_badge[levels]['.$level.'][requires]['.$requirement_id.'][specific]" class="form-control specific" data-row="'.$requirement_id.'" >'.$learndash_courses_options.'</select></div>';

        }
        elseif ( 
            array_key_exists( $requirement['reference'], $this->assignment_refrences ) &&
            !empty($requirement['specific']) 
        ) 
        { 


            $courses = $this->get_all_posts( $this->refrences['learndash_course_complete'] );
            $lessons = $this->get_all_posts( $this->refrences['learndash_lesson_complete'] );
            $topics  = $this->get_all_posts( $this->refrences['learndash_topic_complete'] );

            $learndash_assignment_options = '<option>Any</option>';

            if ( ! empty( $courses ) ) {

                $learndash_assignment_options .= '<optgroup label="Courses">';

                foreach ( $courses as $course ) {
                    $learndash_assignment_options .= '<option value="'.$course->ID.'" '.selected( $requirement['specific'], $course->ID, false ).' >'.  $course->post_title.'</option>';
                }

                $learndash_assignment_options .= '</optgroup>';

            }

            if ( ! empty( $lessons ) ) {

                $learndash_assignment_options .= '<optgroup label="Lessons">';

                foreach ( $lessons as $lesson ) {
                    $learndash_assignment_options .= '<option value="'.$lesson->ID.'" '.selected( $requirement['specific'], $lesson->ID, false ).' >'. htmlentities( $lesson->post_title, ENT_QUOTES ) .'</option>';
                }

                $learndash_assignment_options .= '</optgroup>';

            }

            if ( ! empty( $topics ) ) {

                $learndash_assignment_options .= '<optgroup label="Topics">';

                foreach ( $topics as $topic ) {
                    $learndash_assignment_options .= '<option value="'.$topic->ID.'" '.selected( $requirement['specific'], $topic->ID, false ).' >'. htmlentities( $topic->post_title, ENT_QUOTES ) .'</option>';
                }

                $learndash_assignment_options .= '</optgroup>';

            }

            $data = '<div class="form-group"><select name="mycred_badge[levels]['.$level.'][requires]['.$requirement_id.'][specific]" class="form-control specific" data-row="'.$requirement_id.'" >'.$learndash_assignment_options.'</select></div>';

        }

        return $data;
    }

    public function mycred_learndash_admin_header(){
        $screen = get_current_screen();

        if ( $screen->id == MYCRED_BADGE_KEY ):?>
        <script type="text/javascript">
        <?php

            foreach ( $this->refrences as $key => $value ) {

                $learndash_courses = $this->get_all_posts( $value );
                $learndash_courses_options = '<option>Any</option>';

                foreach ( $learndash_courses as $post ) {
                    $learndash_courses_options .= '<option value="'.$post->ID.'">'.  $post->post_title  .'</option>';
                }
                $data = '<div class="form-group"><select name="{{element_name}}" class="form-control specific" data-row="{{reqlevel}}" >'.$learndash_courses_options.'</select></div>';
                echo "var  mycred_badge_".$key ." = '".$data."';";


            }


            foreach ( $this->assignment_refrences as $key => $value ) {
            
                $courses = $this->get_all_posts( $this->refrences['learndash_course_complete'] );
                $lessons = $this->get_all_posts( $this->refrences['learndash_lesson_complete'] );
                $topics  = $this->get_all_posts( $this->refrences['learndash_topic_complete'] );

                $learndash_assignment_options = '<option>Any</option>';

                if ( ! empty( $courses ) ) {
                    $learndash_assignment_options .= '<optgroup label="Courses">';

                    foreach ( $courses as $course ) {
                        $learndash_assignment_options .= '<option value="'.$course->ID.'">'. htmlentities( $course->post_title, ENT_QUOTES ) .'</option>';
                    }

                    $learndash_assignment_options .= '</optgroup>';
                }

                if ( ! empty( $lessons ) ) {
                    $learndash_assignment_options .= '<optgroup label="Lessons">';

                    foreach ( $lessons as $lesson ) {
                        $learndash_assignment_options .= '<option value="'.$lesson->ID.'">'. htmlentities( $lesson->post_title, ENT_QUOTES ) .'</option>';
                    }

                    $learndash_assignment_options .= '</optgroup>';
                }

                if ( ! empty( $topics ) ) {
                    $learndash_assignment_options .= '<optgroup label="Lessons">';

                    foreach ( $topics as $topic ) {
                        $learndash_assignment_options .= '<option value="'.$topic->ID.'">'. htmlentities( $topic->post_title, ENT_QUOTES ) .'</option>';
                    }

                    $learndash_assignment_options .= '</optgroup>';
                }

                $assignment_data = '<div class="form-group"><select name="{{element_name}}" class="form-control specific" data-row="{{reqlevel}}" >'.$learndash_assignment_options.'</select></div>';
                echo "var  mycred_badge_".$key ." = '".$assignment_data."';";

                
            }

        ?>
        </script>
        <?php endif;
    }

    public function get_all_posts( $post_type ) {

        $args = array( 
            'numberposts' => -1, 
            'post_type'   => $post_type, 
            'post_status' => 'publish' 
        );
        
        return get_posts( $args );
    }

}
