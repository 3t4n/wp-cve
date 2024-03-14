<?php
/**
 * Team Metabox
 *
 * @class       TP_Education_Team_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Team_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_options_save' ) );
    }

    public function tp_education_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-team' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-team-options', __( 'Team Options', 'tp-education' ), array( $this, 'tp_education_options' ), $post_type, 'normal', 'high' );
        endif;
    }

    public function tp_education_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_options_nonce', 'team_designation_nonce' );
        $tp_education_team_designation = get_post_meta( $post->ID, 'tp_team_designation_value', true );
        $team_designation = ! empty( $tp_education_team_designation ) ? $tp_education_team_designation : '';
        $tp_education_team_email = get_post_meta( $post->ID, 'tp_team_email_value', true );
        $team_email = ! empty( $tp_education_team_email ) ? $tp_education_team_email : '';
        $tp_education_team_phone = get_post_meta( $post->ID, 'tp_team_phone_value', true );
        $team_phone = ! empty( $tp_education_team_phone ) ? $tp_education_team_phone : '';
        $tp_education_team_skype = get_post_meta( $post->ID, 'tp_team_skype_value', true );
        $team_skype = ! empty( $tp_education_team_skype ) ? $tp_education_team_skype : '';
        $tp_education_team_website = get_post_meta( $post->ID, 'tp_team_website_value', true );
        $team_website = ! empty( $tp_education_team_website ) ? $tp_education_team_website : '';
        $tp_education_team_courses = get_post_meta( $post->ID, 'tp_team_courses_value', true );
        $team_cources = ! empty( $tp_education_team_courses ) ? $tp_education_team_courses : '';
        ?>
        <label class="tp-label" for="tp_team_designation_value"><?php _e( 'Designation', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_team_designation_value" id="team_designation_id" placeholder="<?php _e( 'Principal', 'tp-education' ); ?>" value="<?php echo esc_attr( $team_designation ); ?>" />
        <p><?php _e( 'Please insert the position of the personel.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_team_email_value"><?php _e( 'Email', 'tp-education' ); ?>: </label><br>
        <input type="email" name="tp_team_email_value" id="team_email_id" placeholder="andy@gmail.com" value="<?php echo esc_attr( $team_email ); ?>" />
        <p><?php _e( 'Please input the email id of the personel.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_team_phone_value"><?php _e( 'Phone Number', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_team_phone_value" id="team_phone_id" placeholder="<?php _e( '+ 4875213654', 'tp-education' ); ?>" value="<?php echo esc_attr( $team_phone ); ?>" />
        <p><?php _e( 'Please input the phone number of the personel.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_team_skype_value"><?php _e( 'Skype Username', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_team_skype_value" id="team_skype_id" placeholder="andy.jones" value="<?php echo esc_attr( $team_skype ); ?>" />
        <p><?php _e( 'Please input the skype username of the personel.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_team_website_value"><?php _e( 'Website', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_team_website_value" id="team_website_id" placeholder="www.andyjones.com" value="<?php echo esc_attr( $team_website ); ?>" />
        <p><?php _e( 'Please input the personal website of the personel.', 'tp-education' ); ?></p>

        <hr>

         <label class="tp-label" for="tp_team_courses_value"><?php _e( 'Courses', 'tp-education' ); ?>: </label><br>
        <select name="tp_team_courses_value[]" id="team_courses_id" multiple>
            <option value=""><?php _e( 'None', 'tp-education' ); ?></option>
            <?php
            $args = array(
                'post_type' => 'tp-course',
                'orderby'   => 'title',
                'order'     => 'ASC',
                'posts_per_page' => -1
                );
            $posts = get_posts( $args );
            foreach ( $posts as $post ) {
                $selected = in_array( absint( $post->ID ), $team_cources ) ? 'selected' : '';
                echo '<option value="' . absint( $post->ID ) . '" ' . $selected . '>' . esc_html( $post->post_title ) . '</option>';
            }
            ?>
            
        </select>
        <p><?php _e( 'Please select courses entitled. Press Ctrl and select multiple courses.', 'tp-education' ); ?></p>
        
        <?php    
    }


    public function tp_education_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['team_designation_nonce'] ) || !wp_verify_nonce( $_POST['team_designation_nonce'], 'tp_education_options_nonce' ) ) return;

         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_designation_value'] ) ) :
            $value = ! empty( $_POST['tp_team_designation_value'] ) ? $_POST['tp_team_designation_value'] : '';
            update_post_meta( $post_id, 'tp_team_designation_value', sanitize_text_field( $value ) );   
        endif;      

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_email_value'] ) ) :
            $value = ! empty( $_POST['tp_team_email_value'] ) ? $_POST['tp_team_email_value'] : '';
            update_post_meta( $post_id, 'tp_team_email_value', sanitize_email( $value ) );   
        endif;  

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_phone_value'] ) ) :
            $value = ! empty( $_POST['tp_team_phone_value'] ) ? $_POST['tp_team_phone_value'] : '';
            update_post_meta( $post_id, 'tp_team_phone_value', sanitize_text_field( $value ) );   
        endif;

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_skype_value'] ) ) :
            $value = ! empty( $_POST['tp_team_skype_value'] ) ? $_POST['tp_team_skype_value'] : '';
            update_post_meta( $post_id, 'tp_team_skype_value', sanitize_text_field( $value ) ); 
        endif;

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_website_value'] ) ) :
            $value = ! empty( $_POST['tp_team_website_value'] ) ? $_POST['tp_team_website_value'] : '';
            update_post_meta( $post_id, 'tp_team_website_value', sanitize_text_field( $value ) ); 
        endif;

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_team_courses_value'] ) ) :
            $value = ! empty( $_POST['tp_team_courses_value'] ) ? (array) $_POST['tp_team_courses_value'] : array();
            update_post_meta( $post_id, 'tp_team_courses_value', array_map( 'absint', $value ) ); 
        endif;
    }

}

new TP_Education_Team_Metabox();