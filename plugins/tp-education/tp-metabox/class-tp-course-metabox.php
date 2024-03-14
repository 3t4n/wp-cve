<?php
/**
 * Course Options Metabox
 *
 * @class       TP_Education_Course_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Course_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_course_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_course_options_save' ) );
    }

    public function tp_education_course_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-course' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-class-options', __( 'Course Options', 'tp-education' ), array( $this, 'tp_education_course_options' ), $post_types, 'normal', 'high' );
        endif;
    }

    public function tp_education_course_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_course_options_nonce', 'course_options_nonce' );
        $tp_education_course_type = get_post_meta( $post->ID, 'tp_course_type_value', true );
        $course_type = ! empty( $tp_education_course_type ) ? $tp_education_course_type : '';
        $tp_education_course_duration = get_post_meta( $post->ID, 'tp_course_duration_value', true );
        $course_duration = ! empty( $tp_education_course_duration ) ? $tp_education_course_duration : '';
        $tp_education_course_starting_date = get_post_meta( $post->ID, 'tp_course_starting_date_value', true );
        $course_starting_date = ! empty( $tp_education_course_starting_date ) ? $tp_education_course_starting_date : '';
        $tp_education_course_price = get_post_meta( $post->ID, 'tp_course_price_value', true );
        $course_price = ! empty( $tp_education_course_price ) ? $tp_education_course_price : '';
        $tp_education_course_students = get_post_meta( $post->ID, 'tp_course_students_value', true );
        $course_students = ! empty( $tp_education_course_students ) ? $tp_education_course_students : '';
        $tp_education_course_language = get_post_meta( $post->ID, 'tp_course_language_value', true );
        $course_language = ! empty( $tp_education_course_language ) ? $tp_education_course_language : '';
        $tp_education_course_assessment = get_post_meta( $post->ID, 'tp_course_assessment_value', true );
        $course_assessment = ! empty( $tp_education_course_assessment ) ? $tp_education_course_assessment : '';
        $tp_education_course_skills = get_post_meta( $post->ID, 'tp_course_skills_value', true );
        $course_skills = ! empty( $tp_education_course_skills ) ? $tp_education_course_skills : '';
        $tp_education_course_professor = get_post_meta( $post->ID, 'tp_course_professor_value', true );
        $course_professor = ! empty( $tp_education_course_professor ) ? $tp_education_course_professor : '';
        $tp_education_course_counselors = get_post_meta( $post->ID, 'tp_course_counselors_value', true );
        $course_counselors = ! empty( $tp_education_course_counselors ) ? $tp_education_course_counselors : '';
        ?>

        <label class="tp-label" for="tp_course_type_value"><?php _e( 'Course Type', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_type_value" id="course_type_id" placeholder="<?php _e( 'Academic Course', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_type ); ?>" />
        <p><?php _e( 'Please insert the type of course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_duration_value"><?php _e( 'Starting Date', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_starting_date_value" id="course_starting_date_id" placeholder="<?php _e( 'Jan 21 2020', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_starting_date ); ?>" />
        <p><?php _e( 'Please insert the course starting date.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_duration_value"><?php _e( 'Duration', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_duration_value" id="course_duration_id" placeholder="<?php _e( '3 Month', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_duration ); ?>" />
        <p><?php _e( 'Please insert the designated duration for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_price_value"><?php _e( 'Price', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_price_value" id="course_price_id" placeholder="<?php _e( '$124', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_price ); ?>" />
        <p><?php _e( 'Please insert the price for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_students_value"><?php _e( 'No of students allocated', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_students_value" id="course_price_id" placeholder="<?php _e( '115', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_students ); ?>" />
        <p><?php _e( 'Please insert the number of students for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_language_value"><?php _e( 'Language', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_language_value" id="course_language_id" placeholder="<?php _e( 'English', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_language ); ?>" />
        <p><?php _e( 'Please insert the language for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_assessment_value"><?php _e( 'Assessment', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_assessment_value" id="course_assessment_id" placeholder="<?php _e( 'Self', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_assessment ); ?>" />
        <p><?php _e( 'Please insert the assessment for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_skills_value"><?php _e( 'Skills', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_course_skills_value" id="course_skills_id" placeholder="<?php _e( 'Expert', 'tp-education' ); ?>" value="<?php echo esc_attr( $course_skills ); ?>" />
        <p><?php _e( 'Please insert the skills for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_professor_value"><?php _e( 'Professor', 'tp-education' ); ?>: </label><br>
        <select name="tp_course_professor_value" id="course_skills_id">
            <option value=""><?php _e( 'None', 'tp-education' ); ?></option>
            <?php
            $args = array(
                'post_type' => 'tp-team',
                'orderby'   => 'title',
                'order'     => 'ASC',
                'posts_per_page' => -1
                );
            $posts = get_posts( $args );
            foreach ( $posts as $post ) {
                echo '<option value="' . absint( $post->ID ) . '"  ' . selected( $course_professor, absint( $post->ID ), false ) . '>' . esc_html( $post->post_title ) . '</option>';
            }
            ?>
            
        </select>
        <p><?php _e( 'Please select professor for this course.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_course_counselors_value"><?php _e( 'Counselors', 'tp-education' ); ?>: </label><br>
        <select name="tp_course_counselors_value[]" id="course_counselors_id" multiple>
            <option value=""><?php _e( 'None', 'tp-education' ); ?></option>
            <?php
            $args = array(
                'post_type' => 'tp-team',
                'orderby'   => 'title',
                'order'     => 'ASC',
                'posts_per_page' => -1
                );
            $posts = get_posts( $args );
            foreach ( $posts as $post ) {
                $selected = in_array( absint( $post->ID ), $course_counselors ) ? 'selected' : '';
                echo '<option value="' . absint( $post->ID ) . '" ' . $selected . '>' . esc_html( $post->post_title ) . '</option>';
            }
            ?>
            
        </select>
        <p><?php _e( 'Please select Counselors for this course. Press Ctrl and select multiple counselors.', 'tp-education' ); ?></p>


        <?php    
    }


    public function tp_education_course_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['course_options_nonce'] ) || !wp_verify_nonce( $_POST['course_options_nonce'], 'tp_education_course_options_nonce' ) ) return;
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_type_value'] ) ) :
            $value = ! empty( $_POST['tp_course_type_value'] ) ? $_POST['tp_course_type_value'] : '';
            update_post_meta( $post_id, 'tp_course_type_value', sanitize_text_field( $value ) );   
        endif; 
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_duration_value'] ) ) :
            $value = ! empty( $_POST['tp_course_duration_value'] ) ? $_POST['tp_course_duration_value'] : '';
            update_post_meta( $post_id, 'tp_course_duration_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_starting_date_value'] ) ) :
            $value = ! empty( $_POST['tp_course_starting_date_value'] ) ? $_POST['tp_course_starting_date_value'] : '';
            update_post_meta( $post_id, 'tp_course_starting_date_value', sanitize_text_field( $value ) );   
        endif;   


        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_price_value'] ) ) :
            $value = ! empty( $_POST['tp_course_price_value'] ) ? $_POST['tp_course_price_value'] : '';
            update_post_meta( $post_id, 'tp_course_price_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_students_value'] ) ) :
            $value = ! empty( $_POST['tp_course_students_value'] ) ? $_POST['tp_course_students_value'] : '';
            update_post_meta( $post_id, 'tp_course_students_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_language_value'] ) ) :
            $value = ! empty( $_POST['tp_course_language_value'] ) ? $_POST['tp_course_language_value'] : '';
            update_post_meta( $post_id, 'tp_course_language_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_assessment_value'] ) ) :
            $value = ! empty( $_POST['tp_course_assessment_value'] ) ? $_POST['tp_course_assessment_value'] : '';
            update_post_meta( $post_id, 'tp_course_assessment_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_skills_value'] ) ) :
            $value = ! empty( $_POST['tp_course_skills_value'] ) ? $_POST['tp_course_skills_value'] : '';
            update_post_meta( $post_id, 'tp_course_skills_value', sanitize_text_field( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_professor_value'] ) ) :
            $value = ! empty( $_POST['tp_course_professor_value'] ) ? $_POST['tp_course_professor_value'] : '';
            update_post_meta( $post_id, 'tp_course_professor_value', absint( $value ) );   
        endif;

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_course_counselors_value'] ) ) :
            $value = ! empty( $_POST['tp_course_counselors_value'] ) ? (array) $_POST['tp_course_counselors_value'] : array();
            update_post_meta( $post_id, 'tp_course_counselors_value', array_map( 'absint', $value ) );   
        endif;

    }

}

new TP_Education_Course_Metabox();