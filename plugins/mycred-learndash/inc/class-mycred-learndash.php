<?php

if (!defined('ABSPATH')) {
    exit; // disable direct access
}

class myCRED_Learndash {

    /**
     * Construct
     */
    function __construct() {
        // Add custom fileds in Course for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'my_cred_override_course'));
        // Add custom fileds in Lesson for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'my_cred_override_lesson'));
        // Add custom fileds in Topic for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'my_cred_override_topic'));
        // Add custom fileds in Quiz for override myCred option and number of new points
        add_action('add_meta_boxes', array($this, 'my_cred_override_quiz'));
        /* Save overide data of Mycred */
        add_action('save_post', array($this, 'my_cred_override_data_course_meta_box'));
        // Add custom fileds in Quiz for percentage
        // add_action('add_meta_boxes', array($this, 'my_cred_override_min_percentage'));
        /* Save overide data of Percentage */
        add_action('save_post', array($this, 'my_cred_override_percentage_quiz_meta_box'));
        add_action( 'wp_ajax_mycred_specific_course_for_users', 'mycred_specific_course_for_users' );

        add_action( 'wp_ajax_nopriv_mycred_specific_course_for_users', 'mycred_specific_course_for_users' );

        add_action('save_post', array($this, 'my_cred_min_percentage_quiz_meta_box'));

        add_action('save_post', array($this, 'my_cred_earn_points_quiz_meta_box'));

        add_action('save_post', array($this, 'my_cred_times_percentage_quiz_meta_box'));

        add_action('save_post', array($this, 'my_cred_max_percentage_quiz_meta_box'));

        add_action('save_post', array($this, 'my_cred_override_max_percentage_quiz_meta_box'));

        add_filter('mycred_all_references', array($this, 'add_references')); 
    }

    // Add custom fileds in Course for override myCred option and number of new points
    public function my_cred_override_course() {
        add_meta_box('my_cred_override_course', __('myCred Learndash settings', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-courses', 'normal', 'low');
    }

    // Add custom fileds in Lesson for override myCred option and number of new points
    public function my_cred_override_lesson() {
        add_meta_box('my_cred_override_lesson', __('myCred Learndash settings', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-lessons', 'normal', 'low');
    }

    // Add custom fileds in Topic for override myCred option and number of new points
    public function my_cred_override_topic() {
        add_meta_box('my_cred_override_topic', __('myCred Learndash settings', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-topic', 'normal', 'low');
    }

    // Add custom fileds in Quiz for override myCred option and number of new points
    public function my_cred_override_quiz() {
        add_meta_box('my_cred_override_quiz', __('myCred Learndash settings', "mycred-learndash"), array($this, 'my_cred_override_data'), 'sfwd-quiz', 'normal', 'low');
    }

    //View of Custom Fields
    public function my_cred_override_data($post) {
        $point = get_post_meta($post->ID, 'myCred_point', true);
        $override_checked = get_post_meta($post->ID, 'myCred_override_hook', true);
        $point_type = get_post_meta($post->ID, 'myCred_point_type', true);
        include 'views/my_cred_overide.php';
    }

    /* Save overide data of Mycred */

    public function my_cred_override_data_course_meta_box($post_id) {
        // Save logic goes here. Don't forget to include nonce checks!
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (isset($_POST['myCred_override_hook'])) {
            if (!isset($_POST['myCred_point']) || !isset($_POST['myCred_point_type']))
                return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (get_post_type($post_id) == 'sfwd-quiz') {
            if (isset($_POST['myCred_quiz_point_fail']) && $_POST['myCred_quiz_point_fail'] != "") {
                update_post_meta($post_id, 'myCred_quiz_point_fail', sanitize_text_field(wp_unslash($_POST['myCred_quiz_point_fail'])));
            } else {
                delete_post_meta($post_id, 'myCred_quiz_point_fail');
            }
        }

        if (isset($_POST['myCred_override_hook']) && $_POST['myCred_override_hook'] != "") {
            update_post_meta($post_id, 'myCred_override_hook', sanitize_text_field(wp_unslash($_POST['myCred_override_hook'])));
        } else {
            delete_post_meta($post_id, 'myCred_override_hook');
            delete_post_meta($post_id, 'myCred_point');
            delete_post_meta($post_id, 'myCred_point_type');
            delete_post_meta($post_id, 'myCred_quiz_point_fail');
            return;
        }

        if (isset($_POST['myCred_point']) && $_POST['myCred_point'] != "") {
            // Update myCred_point
            update_post_meta($post_id, 'myCred_point', sanitize_text_field(wp_unslash($_POST['myCred_point'])));
        } else {
            // delete myCred_point
            delete_post_meta($post_id, 'myCred_point');
        }
        if (isset($_POST['myCred_point_type']) && $_POST['myCred_point_type'] != "") {
            // Update myCred_point
            update_post_meta($post_id, 'myCred_point_type', sanitize_text_field(wp_unslash($_POST['myCred_point_type'])));
        } else {
            // delete myCred_point
            delete_post_meta($post_id, 'myCred_point_type');
        }
    }

    // Save score minimum Percentage
    public function my_cred_override_percentage_quiz_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['min_percentage']) && $_POST['min_percentage'] != "") {
            update_post_meta($post_id, 'min_percentage', sanitize_text_field(wp_unslash($_POST['min_percentage'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'min_percentage');
        }
    }


     public function my_cred_min_percentage_quiz_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['min_percentage_range']) && $_POST['min_percentage_range'] != "") {
            update_post_meta($post_id, 'min_percentage_range', sanitize_text_field(wp_unslash($_POST['min_percentage_range'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'min_percentage_range');
        }
    }

    

    public function my_cred_earn_points_quiz_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['earn_points']) && $_POST['earn_points'] != "") {
            update_post_meta($post_id, 'earn_points', sanitize_text_field(wp_unslash($_POST['earn_points'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'earn_points');
        }
    }

   
    

    public function my_cred_times_percentage_quiz_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['times']) && $_POST['times'] != "") {
            update_post_meta($post_id, 'times', sanitize_text_field(wp_unslash($_POST['times'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'times');
        }
    }

    

    
     public function my_cred_max_percentage_quiz_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['max_percentage']) && $_POST['max_percentage'] != "") {
            update_post_meta($post_id, 'max_percentage', sanitize_text_field(wp_unslash($_POST['max_percentage'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'max_percentage');
        }
    }


    /**
    * Register Custom myCRED References
    */
            
    public function add_references( $references ) {

        $references['learndash_quiz_complete']         = 'Learndash Completed Quiz';
        $references['learndash_quiz_failed']            = 'Learndash Failed Quiz';
        $references['learndash_complete_quiz_max_percent_grade']  = 'Learndash Complete Quiz with Maximum Percent Grade';
        $references['learndash_quiz_range_percent_grade']  = 'Learndash Complete Quiz on a Range of Percent Grade';

        $references['learndash_course_complete']  = 'Learndash Course Complete';

        $references['learndash_topic_complete']  = 'Learndash Topic Complete';

        $references['learndash_lesson_complete']  = 'Learndash Lesson Complete';

        $references['learndash_course_enrollment']  = 'Learndash Enrolled in Course';

        $references['learndash_join_group']  = 'Learndash Join Group';

        return $references;

    }

    public function my_cred_override_max_percentage_quiz_meta_box($post_id) {

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['max_percentage']) && $_POST['max_percentage'] != "") {
            update_post_meta($post_id, 'max_percentage', sanitize_text_field(wp_unslash($_POST['max_percentage'])));
        } else {
            // delete data
            delete_post_meta($post_id, 'max_percentage');
        }

    }
    
}
