<?php

/**
 * Get Send Message Button Url.
 */
function youzify_get_send_private_message_url( $user_id = false ) {

    if ( ! is_user_logged_in() ) {
        return false;
    }

    return apply_filters(
        'youzify_get_send_private_message_url',
        wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_members_get_user_slug( $user_id ) )
    );
}

/**
 * Get Send Message Button
 */
function youzify_get_send_private_message_button( $user_id = false, $title = null ) {

    // Get The User Id To Whom We Are Sending The Message
    $user_id = $user_id ? $user_id : youzify_get_context_user_id();
    // Don't show the button if the user id is not present or the user id is same as logged in user id
    if ( ! $user_id || $user_id == bp_loggedin_user_id() ) {
        return;
    }

    $title = ! empty( $title ) ? $title : __( 'Message', 'youzify' );

    $defaults = array(
        'block_self'        => true,
        'must_be_logged_in' => true,
        'link_text'         => $title,
        'component'         => 'messages',
        'wrapper_class'     =>'message-button',
        'link_class'        => 'youzify-send-message',
        'id'                => 'private_message-'.$user_id,
        'wrapper_id'        => 'send-private-message-'.$user_id,
        'link_href'         => youzify_get_send_private_message_url( $user_id ),
        'link_title'        => __( 'Send a private message to this user.', 'youzify' ),
    );

    // Get Button Html Code.
    return apply_filters( 'youzify_get_send_private_message_button', bp_get_button( apply_filters( 'youzify_get_send_message_button', $defaults ) ), $user_id );
}

/**
 * Print Send Message Code.
 */
function youzify_send_private_message_button( $user_id = false, $title = null ) {
    if ( ! bp_is_active( 'messages' ) ) {
        return false;
    }
    echo youzify_get_send_private_message_button( $user_id, $title );
}

add_action( 'bp_directory_members_actions', 'youzify_send_private_message_button', 30 );
add_action( 'bp_group_members_list_item_action', 'youzify_send_private_message_button', 30 );

/**
 * Notices Action Activate/Deactivate
 */
function youzify_get_message_activate_deactivate_text() {
    global $messages_template;

    if ( 1 === (int) $messages_template->thread->is_active  ) {
        $text = '<span class="dashicons dashicons-hidden deactivate-notice"></span>';
    } else {
        $text = '<span class="dashicons dashicons-visibility activate-notice"></span>';
    }

    return $text;
}


/**
 * Get Message Recipients Avatar.
 */
function youzify_get_thread_recipients( $thread_id = 0 ) {

    // Init Vars
    $recipients = BP_Messages_Thread::get_recipients_for_thread( $thread_id );
    $more_recipients = count( $recipients ) - 3;

    foreach ( $recipients as $recipient ) {

        // Get User ID.
        $user_id = $recipient->user_id;

        // Hide Deleted Users.
        if ( ! youzify_is_user_exist( $user_id ) ) {
            continue;
        }

        // Get User Avatar.
        $user_avatar =  bp_core_fetch_avatar(
            array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 35, 'height' => 35 )
        );

        // Get User Profile Url.
        $profile_url = bp_members_get_user_url( $user_id );

        // Get User Username.
        $username = bp_core_get_user_displayname( $user_id );

        // Print Avatar.
        echo '<a class="tooltip" data-youzify-tooltip="' . $username . '" href="' . $profile_url . '">' . $user_avatar . '</a>';
    }

    if ( $more_recipients > 3 ) {
        // Get Thread Url.
        $thread_url = bp_get_message_thread_view_link( $thread_id, bp_displayed_user_id() );

        // Print View More Button.
        echo '<a href="' . $thread_url . '" class="youzify-more-recipients">+' . $more_recipients . '</a>';
    }

}

/**
 * Edit Notifications Delete Button.
 */
function youzify_edit_notification_delete_button( $retval, $user_id = 0 ) {
    // New Delete Link.
    return sprintf(
        '<a href="%1$s" class="delete secondary confirm">%2$s</a>',
        esc_url( bp_get_the_notification_delete_url( $user_id ) ),
        '<span class="dashicons dashicons-trash"></span>'
    );
}

add_filter( 'bp_get_the_notification_delete_link' , 'youzify_edit_notification_delete_button' );

/**
 * Get Notifications Read Url.
 */
function youzify_edit_notification_read_button( $retval, $user_id = 0 ) {
    // New Read Link.
    return sprintf(
        '<a href="%1$s" data-youzify-tooltip="%2$s" class="mark-read primary">%3$s</a>',
        esc_url( bp_get_the_notification_mark_read_url( $user_id ) ),
        __( 'Mark as Read', 'youzify' ),
        '<span class="dashicons dashicons-visibility"></span>'
    );
}

add_filter( 'bp_get_the_notification_mark_read_link' , 'youzify_edit_notification_read_button' );

/**
 * Get Notifications UnRead Url.
 */
function youzify_edit_notification_unread_button( $retval, $user_id = 0 ) {
    // Get Unread Link.
    return sprintf(
        '<a href="%1$s" data-youzify-tooltip="%2$s" class="mark-unread primary">%3$s</a>',
        esc_url( bp_get_the_notification_mark_unread_url( $user_id ) ),
        __( 'Mark as Unread', 'youzify' ),
        '<span class="dashicons dashicons-hidden"></span>'
    );
}

add_filter( 'bp_get_the_notification_mark_unread_link' , 'youzify_edit_notification_unread_button' );

/**
 * Get the User Id in the current context
 */
function youzify_get_context_user_id( $user_id = false ) {

    if ( ! is_user_logged_in() ) {
        return false;
    }

    if ( ! $user_id ) {

        // For members loop.
        $user_id = bp_get_member_user_id();

        // For user profile.
        if ( ! $user_id && bp_is_user() ) {
            $user_id = bp_displayed_user_id();
        }

    }

    return apply_filters( 'youzify_get_context_user_id', $user_id );

}

/**
 * Get Activity Attachments.
 */
function youzify_get_message_attachments( $message_id = null, $field = 'media_id' ) {

    if ( empty( $message_id ) ) {
        return;
    }

    global $wpdb, $Youzify_media_table;

    // Prepare Sql
    $sql = $wpdb->prepare( "SELECT $field FROM $Youzify_media_table WHERE item_id = %d AND component = 'message'", $message_id );

    $result = $wpdb->get_row( $sql , ARRAY_A );

    if ( ! empty( $result ) ) {
        $result =  maybe_unserialize( $result[ $field ] );
    }

    return $result;

}

/**
 * Allow Empty Messages That contains Attachments.
 */
function youzify_allow_messages_without_content( $content ) {
    return str_replace( '{{{youzify_message_attachment}}}', '', $content );
}

add_filter( 'messages_message_content_before_save', 'youzify_allow_messages_without_content' );

/**
 * Get Message Attachment.
 */
function youzify_add_message_attachments( $content ) {

    $message_id = bp_get_the_thread_message_id();

    $attachments = bp_messages_get_meta( $message_id, 'youzify_attachments' );

    if ( empty( $attachments ) ) {
        return $content;
    }

    foreach ( $attachments as $media_id => $data ) {

    $attachment_url = wp_get_attachment_url( $media_id );

    // Get File Type.
    switch ( youzify_get_file_type( $attachment_url ) ) {

        case 'image':
            $attachment = '<a href="' .  $attachment_url .'" rel="nofollow" data-youzify-lightbox="youzify-post-'. $message_id . '"><img loading="lazy" ' . youzify_get_image_attributes( $media_id, 'youzify-message', 'message' ) . ' alt=""></a>';
            break;

        case 'audio':
            $attachment = '<audio controls><source src="' . $attachment_url . '" type="audio/mpeg">' . __( 'Your browser does not support the audio element.', 'youzify' ) . '</audio>';
            break;

        case 'video':
            $attachment = '<video width="100%" controls preload="metadata"><source src="' . $attachment_url . '" type="video/mp4">' . __( 'Your browser does not support the video tag.', 'youzify' ) . '</video>';
            break;

        case 'file':

            $attachment = '<a class="youzify-message-file" rel="nofollow" href="' . $attachment_url .'"><span class="youzify-file-icon"><i class="fas fa-download youzify-attachment-file-icon"></i></span><span class="youzify-wall-file-details"><span class="youzify-wall-file-title" title="'. $data['real_name']. '">' . youzify_get_filename_excerpt( $data['real_name'], 45 ) . '</span><span class="youzify-wall-file-size">' . youzify_file_format_size( $data['file_size'] ) . '</span></span></a>';
            break;

        default:
            $attachment = '';
            break;
    }

    }

    return $content . '<div class="youzify-message-attachment">' . $attachment . '</div>';
}

add_filter( 'bp_get_the_thread_message_content', 'youzify_add_message_attachments' );