<?php
/**
 * Installation related functions and actions.
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
 * AL_Install Class.
 */
class AL_Install {

    /**
     * Hook in tabs.
     */
    public function __construct () {
        add_filter ( "learndash_settings_fields", array( __CLASS__, "ld_settings_fields"  ), 100, 2 );
        add_action( 'save_post', array( __CLASS__, 'save_meta_box') );
    }

    /**
     * Adds Fields to the LearnDash Course Access MetaBox
     */
    public static function ld_settings_fields( $setting_option_fields, $settings_metabox_key ) {

        /**
         * Check for desired MetaBox
         */
        if ( 'learndash-course-access-settings' != $settings_metabox_key ) {
            return $setting_option_fields;
        }
        $courses_level_args = array (
            "posts_per_page"   =>  -1,
            "post_type"     =>  "sfwd-courses-levels",
            "post_status"   =>  "publish"
        );
        $courses_level = get_posts ( $courses_level_args );

        /**
         * New fields for course levels
         */
        $options = array ( "select_level" => "Select Level" );
        foreach ( $courses_level as $course_level ) {
            $options[$course_level->ID] = $course_level->post_title;
        }

        $c_level = get_post_meta( get_the_ID(), 'sfwd-courses_course_level', true );
        $course_meta = get_post_meta( get_the_ID(), '_sfwd_courses', true );
        if(empty($course_meta)) {
            $course_meta = array();
        }
        $course_meta['sfwd-courses_course_level'] = $c_level;
        
        $field_tool["name"] = "course_level";
        $field_tool["type"] = "select";
        $field_tool["options"] = $options;
        $field_tool["value"] = $c_level;
        $field_tool["help_text"] = "Associate a course level with this child course";
        $field_tool['parent_setting'] = 'course_prerequisite_enabled';
        $field_tool['label'] = 'Select Course Level';
       

        $fields = $setting_option_fields;
        $new_fields = array();
        foreach ( $fields as $key => $field ) {
            if ( $key == "course_prerequisite" ) {
                $new_fields[$key] = $field;
                $new_fields["course_level"] = $field_tool;

            } else {
                $new_fields[$key] = $field;
            }
        }

        $setting_option_fields = $new_fields;

        return $setting_option_fields;

    }

    /**
     * Saves the meta box post
     * @param $post_id post_id where metabox is to be saved
     */
    public static function save_meta_box( $post_id ) {

        $product_type = get_post_type( $post_id );
        $post_values = isset($_POST[ 'learndash-course-access-settings' ]) ? $_POST[ 'learndash-course-access-settings' ] : array();
        if(!empty($post_values)) {
            $meta_field_value = $post_values['course_level'];
            if (trim($product_type) == 'sfwd-courses') {
                update_post_meta($post_id, "sfwd-courses_course_level", intval($meta_field_value));
            }
        }
    }

}

return new AL_Install();