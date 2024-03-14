<?php

class ContactListHelpers
{
    public static function getMediaLibraryFileID( $filename )
    {
        $newest_file_id = 0;
        $args = array(
            'post_type'      => 'attachment',
            'post_status'    => 'inherit',
            'posts_per_page' => 1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => array( array(
            'key'     => '_wp_attached_file',
            'value'   => $filename,
            'compare' => 'LIKE',
        ) ),
        );
        $query = new WP_Query( $args );
        
        if ( $query->have_posts() ) {
            $query->the_post();
            $newest_file_id = get_the_ID();
        }
        
        wp_reset_postdata();
        return $newest_file_id;
    }
    
    public static function sanitize_attr_value( $str )
    {
        $str = sanitize_text_field( $str );
        $str = htmlspecialchars( $str );
        return $str;
    }
    
    public static function getSearchDropdownClass( $title = '', $message = '' )
    {
        $s = get_option( 'contact_list_settings' );
        $dd_class = 'cl_select_v2';
        return $dd_class;
    }
    
    public static function writeLog( $title = '', $message = '' )
    {
        global  $wpdb ;
        $wpdb->insert( $wpdb->prefix . 'contact_list_log', array(
            'title'   => sanitize_text_field( $title ),
            'message' => sanitize_textarea_field( $message ),
        ) );
    }
    
    public static function addFeaturedImage(
        $contact_id,
        $upload,
        $uploaded_type,
        $filename
    )
    {
        
        if ( $contact_id && $upload && $uploaded_type && $filename ) {
            $contact_id = intval( $contact_id );
            if ( !function_exists( 'wp_crop_image' ) ) {
                include ABSPATH . 'wp-admin/includes/image.php';
            }
            switch ( $uploaded_type ) {
                case 'image/jpeg':
                case 'image/png':
                case 'image/gif':
                    $image_url = $upload['file'];
                    // Prepare an array of post data for the attachment.
                    $attachment = array(
                        'guid'           => esc_url_raw( $image_url ),
                        'post_mime_type' => sanitize_text_field( $uploaded_type ),
                        'post_title'     => sanitize_text_field( $filename ),
                        'post_content'   => '',
                        'post_status'    => 'inherit',
                    );
                    $attach_id = wp_insert_attachment( $attachment, $image_url, $contact_id );
                    $attach_data = wp_generate_attachment_metadata( $attach_id, $image_url );
                    wp_update_attachment_metadata( $attach_id, $attach_data );
                    set_post_thumbnail( $contact_id, $attach_id );
                    break;
            }
        }
    
    }
    
    public static function getText( $text_id, $default_text )
    {
        $s = get_option( 'contact_list_settings' );
        $text = sanitize_text_field( $default_text );
        if ( isset( $s[$text_id] ) && $s[$text_id] ) {
            $text = sanitize_text_field( $s[$text_id] );
        }
        return $text;
    }
    
    public static function getTextV2( $text_id, $translatable_text )
    {
        $s = get_option( 'contact_list_settings' );
        $text = '';
        // Text defined in the settings
        
        if ( isset( $s[$text_id] ) && $s[$text_id] ) {
            $text = sanitize_text_field( $s[$text_id] );
            // Default text
        } else {
            $text = sanitize_text_field( __( $translatable_text, 'contact-list' ) );
        }
        
        return $text;
    }
    
    public static function proFeatureMarkup()
    {
        $html = '';
        $html .= '<div class="contact-list-pro-feature">';
        $html .= '<span>' . sanitize_text_field( __( 'This feature is available in the paid plans.', 'contact-list' ) ) . '</span>';
        $html .= '<a class="contact-list-admin-button-upgrade-link" href="' . esc_url_raw( get_admin_url() ) . 'options-general.php?page=contact-list-pricing">' . sanitize_text_field( __( 'Upgrade here', 'contact-list' ) ) . '</a>';
        $html .= '</div>';
        return $html;
    }
    
    public static function proFeatureMarkupV2( $text = '' )
    {
        $html = '';
        $html .= '<div class="contact-list-pro-feature">';
        $html .= '<span>' . sanitize_text_field( $text ) . '</span>';
        $html .= '<a href="' . esc_url_raw( get_admin_url() ) . 'options-general.php?page=contact-list-pricing">' . sanitize_text_field( __( 'Upgrade here', 'contact-list' ) ) . '</a>';
        $html .= '</div>';
        return $html;
    }
    
    public static function proFeatureSettingsMarkup()
    {
        $html = '';
        $html .= '<div class="contact-list-pro-feature">';
        $html .= '<span>' . sanitize_text_field( __( 'More settings available in the paid plans.', 'contact-list' ) ) . '</span>';
        $html .= '<a href="' . esc_url_raw( get_admin_url() ) . 'options-general.php?page=contact-list-pricing">' . sanitize_text_field( __( 'Upgrade here', 'contact-list' ) ) . '</a>';
        $html .= '</div>';
        return $html;
    }
    
    public static function modalSendMessageMarkup()
    {
        $s = get_option( 'contact_list_settings' );
        $input = esc_url_raw( get_site_url() );
        // in case scheme relative URI is passed, e.g., //www.google.com/
        $input = trim( $input, '/' );
        // If scheme not included, prepend it
        if ( !preg_match( '#^http(s)?://#', $input ) ) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url( $input );
        // remove www
        $site_url = preg_replace( '/^www\\./', '', $urlParts['host'] );
        $html = '';
        $html .= '<div class="cl-modal-container cl-modal-container-send-message">';
        $html .= '<div class="cl-modal-inner">';
        $html .= '<div class="cl-modal">';
        $html .= '<div class="close-modal-container">';
        $html .= '<a href="" class="cl-close-modal">&#10006;</a>';
        $html .= '</div>';
        $html .= ContactListPublicHooks::get_action_content( 'contact_list_send_message_modal_content_start' );
        $html .= '<h3>' . ContactListHelpers::getTextV2( 'text_send_message', 'Send message' ) . '</h3>';
        $html .= ContactListPublicHooks::get_action_content( 'contact_list_send_message_modal_after_title' );
        $html .= '<form class="contact-list-send-single">';
        $html .= '<label for="sender_name">' . sanitize_text_field( __( 'Sender name', 'contact-list' ) ) . '</label>';
        $html .= '<input class="contact-list-sender-name" name="sender_name" value="" placeholder="' . ContactListHelpers::sanitize_attr_value( __( 'Your name', 'contact-list' ) ) . '" />';
        $html .= '<label for="sender_email">' . sanitize_text_field( __( 'Sender email', 'contact-list' ) ) . '</label>';
        $html .= '<input class="contact-list-sender-email" name="sender_email" value="" placeholder="' . ContactListHelpers::sanitize_attr_value( __( 'Your email', 'contact-list' ) ) . '" />';
        $html .= '<label for="recipient">' . sanitize_text_field( __( 'Recipient', 'contact-list' ) ) . '</label>';
        $html .= '<span><span id="recipient" class="contact-list-recipient"></span></span>';
        $html .= '<label for="message">' . sanitize_text_field( __( 'Message', 'contact-list' ) ) . '</label>';
        $html .= '<textarea name="message" class="contact-list-message" placeholder=""></textarea>';
        $html .= '<div class="contact-list-message-error"></div>';
        $html .= '<input name="contact_id" type="hidden" value="" class="contact-list-contact-id" />';
        $html .= '<input name="site_url" type="hidden" value="' . esc_url_raw( $site_url ) . '" />';
        $html .= '<input name="txt_please_msg_first" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'Please write message first.', 'contact-list' ) ) . '" />';
        $html .= '<input name="txt_msg_sent_to" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'Message sent to recipient.', 'contact-list' ) ) . '" />';
        $html .= '<input name="txt_sending_please_wait" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'Please wait...', 'contact-list' ) ) . '" />';
        $html .= '<input name="txt_new_msg_from" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'New message from', 'contact-list' ) ) . '" />';
        $custom_subject = '';
        if ( isset( $s['email_custom_subject_contact_card'] ) && $s['email_custom_subject_contact_card'] ) {
            $custom_subject = sanitize_text_field( $s['email_custom_subject_contact_card'] );
        }
        $html .= '<input name="txt_custom_subject" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( $custom_subject ) . '" />';
        $html .= '<input name="txt_sent_by" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'sent by', 'contact-list' ) ) . '" />';
        $html .= '<input name="txt_please_sender_details_first" type="hidden" value="' . ContactListHelpers::sanitize_attr_value( __( 'Please enter sender information first (name and email).', 'contact-list' ) ) . '" />';
        $html .= ContactListPublicHooks::get_action_content( 'contact_list_send_message_modal_before_send_button' );
        $html .= '<input type="submit" name="send_message" class="contact-list-send-single-submit" value="' . ContactListHelpers::sanitize_attr_value( __( 'Send', 'contact-list' ) ) . '" />';
        $html .= '<div class="contact-list-sending-message"></div>';
        $html .= '</form>';
        $html .= ContactListPublicHooks::get_action_content( 'contact_list_send_message_modal_content_end' );
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }
    
    public static function getLayout( $s, $atts )
    {
        $layout = '';
        
        if ( isset( $atts['layout'] ) ) {
            $layout = sanitize_text_field( $atts['layout'] );
            
            if ( $layout == '2-columns' ) {
                $layout = '2-cards-on-the-same-row';
            } elseif ( $layout == '3-columns' ) {
                $layout = '3-cards-on-the-same-row';
            } elseif ( $layout == '4-columns' ) {
                $layout = '4-cards-on-the-same-row';
            }
        
        } elseif ( isset( $s['layout'] ) && $s['layout'] ) {
            $layout = sanitize_text_field( $s['layout'] );
        }
        
        return $layout;
    }
    
    public static function createElemClass()
    {
        $elem_class = 'contact-list-embed-' . uniqid();
        return $elem_class;
    }
    
    public static function isPremium()
    {
        $is_premium = 0;
        return $is_premium;
    }

}