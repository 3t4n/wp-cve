<?php
/**
 * Class Options Metabox
 *
 * @class       TP_Education_Class_Metabox
 * @since       1.0
 * @package     TP Education
 * @category    Class
 * @author      Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class TP_Education_Class_Metabox {

    public function __construct()
    {
        add_action( 'add_meta_boxes', array( $this, 'tp_education_class_options_meta') );
        add_action( 'save_post', array( $this, 'tp_education_class_options_save' ) );
    }

    public function tp_education_class_options_meta( $post_type )
    {
        /**
         * Add meta box
         */
        $post_types = array( 'tp-class' );
        if ( in_array( $post_type, $post_types ) ) :
            add_meta_box( 'tp-education-class-options', __( 'Class Options', 'tp-education' ), array( $this, 'tp_education_class_options' ), $post_types, 'normal', 'high' );
        endif;
    }

    public function tp_education_class_period_values() {
        $period_values = array( 
            'per-hr' => __( 'Per/Hour', 'tp-education' ), 
            'per-class' => __( 'Per/Class', 'tp-education' ), 
            'per-day' => __( 'Per/Day', 'tp-education' ), 
            'per-week' => __( 'Per/Week', 'tp-education' ), 
            'per-month' => __( 'Per/Month', 'tp-education' ), 
            'per-sem' => __( 'Per/Semester', 'tp-education' ), 
            'per-year' => __( 'Per/Year', 'tp-education' ),
            );
        $output = apply_filters( 'tp_education_class_period_values_filter', $period_values );
        return $output;

    }

    public function tp_education_class_options( $post )
    {
        /**
         * Outputs the content of the meta options
         */
        wp_nonce_field( 'tp_education_class_options_nonce', 'class_options_nonce' );
        $tp_education_class_cost = get_post_meta( $post->ID, 'tp_class_cost_value', true );
        $class_cost = ! empty( $tp_education_class_cost ) ? $tp_education_class_cost : '';
        $tp_education_period = get_post_meta( $post->ID, 'tp_class_period_value', true );
        $period = ! empty( $tp_education_period ) ? $tp_education_period : '';
        $tp_education_class_size = get_post_meta( $post->ID, 'tp_class_size_value', true );
        $class_size = ! empty( $tp_education_class_size ) ? $tp_education_class_size : '';
        $tp_education_age_group = get_post_meta( $post->ID, 'tp_class_age_group_value', true );
        $age_group = ! empty( $tp_education_age_group ) ? $tp_education_age_group : '';

        $period_values = $this->tp_education_class_period_values();
        ?>

        <strong><?php _e( 'Class Cost', 'tp-education' );?></strong><br />

        <label class="tp-label" for="tp_class_cost_value"><?php _e( 'Cost', 'tp-education' ); ?>: </label>
        <input type="text" name="tp_class_cost_value" id="class_cost_id" placeholder="<?php _e( '$124', 'tp-education' ); ?>" value="<?php echo esc_attr( $class_cost ); ?>" />

        <label class="tp-label" for="tp_class_period_value"><?php _e( 'Period', 'tp-education' ); ?>: </label>
        <select name="tp_class_period_value" id="class_period_id" >
            <?php foreach ( $period_values as $period_value => $value ) : ?>
                <option <?php echo ( $period == $value ) ? 'selected' : ''; ?>><?php echo esc_html( $value ); ?></option>
            <?php endforeach; ?>
        </select>
        <p><?php _e( 'Please insert the designated cost for this class.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_class_size_value"><?php _e( 'Class Size', 'tp-education' ); ?>: </label><br>
        <input type="number" name="tp_class_size_value" id="class_size_id" max="200" style="width:100px;" placeholder="<?php _e( '16', 'tp-education' ); ?>" value="<?php echo absint( $class_size ); ?>" />
        <p><?php _e( 'Please insert the designated maximum number of students for this class.', 'tp-education' ); ?></p>

        <hr>

        <label class="tp-label" for="tp_class_age_group_value"><?php _e( 'Age Groups', 'tp-education' ); ?>: </label><br>
        <input type="text" name="tp_class_age_group_value" id="age_group_id" placeholder="<?php _e( '2-5', 'tp-education' ); ?>" value="<?php echo esc_attr( $age_group ); ?>" />
        <p><?php _e( 'Please insert the designated age group of students for this class.', 'tp-education' ); ?></p>
        
        <?php    
    }


    public function tp_education_class_options_save( $post_id )
    {
        /**
         * Saves the mata input value
         */
        // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
         
        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['class_options_nonce'] ) || !wp_verify_nonce( $_POST['class_options_nonce'], 'tp_education_class_options_nonce' ) ) return;
         
        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_class_cost_value'] ) ) :
            $value = ! empty($_POST['tp_class_cost_value']) ? $_POST['tp_class_cost_value'] : '';
            update_post_meta( $post_id, 'tp_class_cost_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_class_period_value'] ) ) :
            $value = ! empty($_POST['tp_class_period_value']) ? $_POST['tp_class_period_value'] : '';
            update_post_meta( $post_id, 'tp_class_period_value', sanitize_text_field( $value ) );   
        endif;   

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_class_size_value'] ) ) :
            $value = ! empty($_POST['tp_class_size_value']) ? $_POST['tp_class_size_value'] : '';
            update_post_meta( $post_id, 'tp_class_size_value', absint( $value ) );   
        endif; 

        // Make sure your data is set before trying to save it
        if( isset( $_POST['tp_class_age_group_value'] ) ) :
            $value = ! empty($_POST['tp_class_age_group_value']) ? $_POST['tp_class_age_group_value'] : '';
            update_post_meta( $post_id, 'tp_class_age_group_value', sanitize_text_field( $value ) );   
        endif;    
    }

}

new TP_Education_Class_Metabox();