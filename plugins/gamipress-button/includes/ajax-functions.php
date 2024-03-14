<?php
/**
 * Ajax Functions
 *
 * @package     GamiPress\Button\Ajax_Functions
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Button click listener
 */
function gamipress_ajax_button_click() {
    // Security check, forces to die if not security passed
    check_ajax_referer( 'gamipress_button', 'nonce' );

    $events_triggered = array();

    // Setup var
    $user_id    = get_current_user_id();
    $type       = isset( $_POST['type'] )   ? sanitize_text_field( $_POST['type'] ) : '';
    $id         = isset( $_POST['id'] )     ? sanitize_text_field( $_POST['id'] ) : '';
    $class      = isset( $_POST['class'] )  ? sanitize_text_field( $_POST['class'] ) : '';
    $form       = isset( $_POST['form'] )   ? sanitize_text_field( $_POST['form'] ) : '';
    $name       = isset( $_POST['name'] )   ? sanitize_text_field( $_POST['name'] ) : '';
    $value      = isset( $_POST['value'] )  ? sanitize_text_field( $_POST['value'] ) : '';
    $post_id    = isset( $_POST['post'] )   ? absint( $_POST['post'] ) : 0;
    $comment_id = isset( $_POST['comment'] ) ? absint( $_POST['comment'] ) : 0;

    // Trigger button click action
    do_action( 'gamipress_button_click', $user_id, $type, $id, $class, $form, $name, $value );
    $events_triggered['gamipress_button_click'] = array( $user_id, $type, $id, $class, $form, $name, $value );

    // Trigger specific id button click action
    if( ! empty( $id ) ) {
        do_action( 'gamipress_specific_id_button_click', $user_id, $type, $id, $class, $form, $name, $value );
        $events_triggered['gamipress_specific_id_button_click'] = array( $user_id, $type, $id, $class, $form, $name, $value );
    }

    // Trigger specific class button click action
    if( ! empty( $class ) ) {
        do_action( 'gamipress_specific_class_button_click', $user_id, $type, $id, $class, $form, $name, $value );
        $events_triggered['gamipress_specific_class_button_click'] = array( $user_id, $type, $id, $class, $form, $name, $value );
    }

    // If we are in a comment, award the author for receive clicks
    $comment = get_comment( $comment_id );

    // If we are in a post/page, award the author for receive clicks
    $post = get_post( $post_id );

    $author_id = 0;

    if( $comment && absint( $comment->user_id ) !== 0 ) {
        // Award to the comment author
        $author_id = absint( $comment->user_id );
    } else if( $post && absint( $post->post_author ) !== 0 ) {
        // Award to the post author
        $author_id = absint( $post->post_author );
    }

    if( $author_id !== 0 ) {

        // Trigger button click action to the post/comment author
        do_action( 'gamipress_user_button_click', $author_id, $type, $id, $class, $user_id, $form, $name, $value );
        $events_triggered['gamipress_user_button_click'] = array( $author_id, $type, $id, $class, $user_id, $form, $name, $value );

        // Trigger specific id button click action to the post/comment author
        if( ! empty( $id ) ) {
            do_action( 'gamipress_user_specific_id_button_click', $author_id, $type, $id, $class, $user_id, $form, $name, $value );
            $events_triggered['gamipress_user_specific_id_button_click'] = array( $author_id, $type, $id, $class, $user_id, $form, $name, $value );
        }

        // Trigger specific class button click action to the post/comment author
        if( ! empty( $class ) ) {
            do_action( 'gamipress_user_specific_class_button_click', $author_id, $type, $id, $class, $user_id, $form, $name, $value );
            $events_triggered['gamipress_user_specific_class_button_click'] = array( $author_id, $type, $id, $class, $user_id, $form, $name, $value );
        }

    }

    wp_send_json_success( $events_triggered );
    exit;
}
add_action( 'wp_ajax_gamipress_button_click', 'gamipress_ajax_button_click' );
add_action( 'wp_ajax_nopriv_gamipress_button_click', 'gamipress_ajax_button_click' );