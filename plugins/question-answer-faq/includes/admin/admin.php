<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// --------------------Admin panel-----------------------------

add_action( 'admin_init', 'register_mideal_faq_settings' );
function register_mideal_faq_settings() {
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_email' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_email2' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_avatar_smallsize' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_dont_show_label' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_dont_connect_bootstrap' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha_key' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_recaptcha_key_secret' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_name' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_pagination_number' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_image' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_question_background' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_question_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_background' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_answer_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_button_color_text' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_button_background' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_button_big_size' );
    register_setting( 'mideal-faq-settings-group', 'mideal_faq_setting_pagination_color' );
}





// ------------------------ Answer colum ----------------

add_filter( 'manage_mideal_faq_posts_columns', 'set_custom_edit_faq_columns' );
add_action( 'manage_mideal_faq_posts_custom_column' , 'custom_faq_column', 10, 2 );

function set_custom_edit_faq_columns( $columns ) {

    $num = 2;

    $new_columns = array(
        'faq_answer' => __("Answer", "question-answer-faq"),
    );
    return array_slice( $columns, 0, 2 ) + $new_columns + array_slice( $columns, $num );
}

function custom_faq_column( $column, $post_id ) {
    switch ( $column ) {

        case 'faq_answer' :
            echo get_post_meta( $post_id, 'mideal_faq_answer', true );
            break;
    }
}

// ------------------------------- add sort colum -------------------------------
add_filter( 'manage_edit-mideal_faq_sortable_columns', 'mideal_faq_add_views_sortable_column' );
function mideal_faq_add_views_sortable_column( $sortable_columns ){
    $sortable_columns['faq_answer'] = __( "Answer", "question-answer-faq" );
    return $sortable_columns;
}

//------------------------------- add custom fields in FAQ--------------------------------------------


add_action( 'add_meta_boxes', 'mideal_faq_add_fields' );

function mideal_faq_add_fields() {
    add_meta_box( 'mideal_faq_fields', __("Answer a question", "question-answer-faq"), 'mideal_faq_add_field_func', 'mideal_faq', 'normal', 'high'  );
}


function mideal_faq_add_field_func( $faq_item ){
    $faq_answer = get_post_meta( $faq_item->ID, 'mideal_faq_answer', true );
    $faq_email = get_post_meta( $faq_item->ID, 'mideal_faq_email', true );
    wp_editor( $faq_answer,'faq_add_answer', array( 'textarea_name' => 'mideal_faq_answer' ));
    echo '<br />';
    echo '<br />';
    echo __( "User Email", "question-answer-faq" ).': <input type="text" name="mideal_faq_email" value="'.$faq_email.'" size="25" />';
    wp_nonce_field( plugin_basename(__FILE__), 'mideal_faq_noncename' );
}



// update fields after save
add_action( 'save_post', 'mideal_faq_update' );

function mideal_faq_update( $post_id ){

    if ( ! wp_verify_nonce( $_POST['mideal_faq_noncename'], plugin_basename(__FILE__) ) ) return $post_id;; 
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id; // если это автосохранение
    if ( 'page' == $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
          return $post_id;
    } elseif( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    if ( ! isset( $_POST['mideal_faq_answer'] ) ) return $post_id;
    
    $my_data = sanitize_textarea_field($_POST['mideal_faq_answer']);
    $my_data2 = sanitize_email( $_POST['mideal_faq_email'] );

    update_post_meta( $post_id, 'mideal_faq_answer', $my_data );
    update_post_meta( $post_id, 'mideal_faq_email', $my_data2 );
}