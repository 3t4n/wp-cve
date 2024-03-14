<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Reamaze Ajax
 *
 * @author      Reamaze
 * @category    Class
 * @package     Reamaze/Classes
 * @version     2.3.2
 */

include_once( 'lib/reamaze-parsedown/parsedown.php' );

class Reamaze_Ajax {
  public static function init() {
    $ajax_events = array(
      'convert_to_conversation'
    );

    foreach ($ajax_events as $ajax_event) {
      add_action( 'wp_ajax_reamaze_' . $ajax_event, array( __CLASS__, $ajax_event ) );
    }

  }

  public static function convert_to_conversation() {
    global $comment;

    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
      $comment = get_comment( sanitize_key( $_GET['comment_id'] ) );

      include_once( "admin/views/admin-ajax-convert-to-conversation.php" );
    } else if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
      $comment = get_comment( sanitize_key( $_POST['comment_id'] ) );

      $post = get_post( $comment->comment_post_ID );

      $conversationTitle = $post->post_title;
      $conversationTitle .= ' - ' . strtok( strip_tags( $comment->comment_content ), "\n" );

      $conversation = array(
        "subject" => $conversationTitle,
        "category" => sanitize_text_field( $_POST['category'] ), // TODO: let user choose
        "message" => array(
          "body" => $comment->comment_content
        ),
        "user" => array(
          "name" => $comment->comment_author,
          "email" => $comment->comment_author_email,
        ),
        "data" => array(
          "Comment Posted" => $comment->comment_date_gmt . " GMT",
          "Comment Link" => get_comment_link( $comment ),
          "In Response To" => $post->post_title . " - " . get_permalink( $post )
        )
      );

      $result = Reamaze\API\Conversation::create( array( "conversation" => $conversation ) );

      if ( $result && ! empty( $result['slug'] ) ) {
        update_comment_meta( $comment->comment_ID, 'reamaze-conversation', $result['slug'] );

        if ( ! empty( $_POST['include_reply'] ) && ! empty( $_POST['reply_message'] ) ) {
					$reply_message = sanitize_textarea_field( $_POST['reply_message'] );
          $message = Reamaze\API\Message::create( array( "conversation_slug" => $result['slug'], "body" => $reply_message, "visibility" => 0 ) );

          if ( !empty( $_POST['add_wp_reply'] ) ) {
            $parsedown = new ReamazeParsedown();
            $current_user = wp_get_current_user();
            $wpReplyID = wp_new_comment( array(
              'comment_post_ID' => $post->ID,
              'comment_author' => $current_user->display_name,
              'comment_author_email' => $current_user->user_email,
              'comment_author_url' => $current_user->user_url,
              'comment_content' => $parsedown->text($reply_message),
              'comment_parent' => $comment->comment_ID,
              'user_id' => $current_user->ID,
              'comment_date' => current_time( 'mysql' ),
              'comment_approved' => 1
            ) );
            update_comment_meta( $wpReplyID, 'reamaze-conversation', $result['slug'] );
          }
        }

        if ( ! empty( $_POST['add_note'] ) && ! empty( $_POST['note_message'] ) ) {
          $internal_note = Reamaze\API\Message::create( array( "conversation_slug" => $result['slug'], "body" => sanitize_textarea_field( $_POST['note_message'] ), "visibility" => 1 ) );
        }

        $result['admin_url'] = 'https://' . get_option( 'reamaze_account_id' ) . '.reamaze.com/admin/conversations/' . $result['slug'];
        $result['admin_path'] = '/admin/conversations/' . $result['slug'];

        print json_encode( $result );
      } else {
        throw new Exception( "Error creating conversation" );
      }
    }

    die();
  }
}
