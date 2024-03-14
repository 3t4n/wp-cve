<?php

class ContactListNotifications
{
    public function notifications_html()
    {
        
        if ( current_user_can( 'manage_options' ) ) {
            $screen = get_current_screen();
            
            if ( isset( $screen->id ) && $screen->id == 'edit-contact' ) {
                $how_to_show_notice = sanitize_text_field( get_option( 'contact_list_how_to_show_notice' ) );
                
                if ( $how_to_show_notice ) {
                    echo  '<div class="contact-list-notice-how-to-get-started">' ;
                    echo  '<div class="contact-list-notice-how-to-get-started-title">' ;
                    echo  '<div class="contact-list-notice-how-to-get-started-text">' ;
                    echo  '<h2>' . esc_html__( 'How to get started', 'contact-list' ) . '</h2>' ;
                    echo  '</div>' ;
                    echo  '<div class="contact-list-notice-how-to-get-started-close">' ;
                    echo  '<form method="GET" action="' . esc_url_raw( get_admin_url() . 'edit.php' ) . '">' ;
                    echo  '<input name="post_type" value="contact" type="hidden" />' ;
                    $user_id = intval( get_current_user_id() );
                    echo  wp_nonce_field(
                        'contact_list_ignore_how_to_notify',
                        '_contact_list_ignore_how_to_notify_' . intval( $user_id ),
                        true,
                        false
                    ) ;
                    echo  "<input type='submit' class='contact-list-notice-how-to-get-dismiss' value='&#10005;' />" ;
                    echo  '</form>' ;
                    echo  '</div>' ;
                    echo  '</div>' ;
                    echo  '<div class="contact-list-notice-how-to-get-started-content">' ;
                    echo  '<ol>' ;
                    echo  '<li>' . esc_html__( 'Add some contacts from the contact management below (click the Add New button)', 'contact-list' ) . '</li>' ;
                    echo  '<li>' ;
                    echo  esc_html__( 'Insert one of these shortcodes to any page or post on your site', 'contact-list' ) ;
                    echo  '<ul>' ;
                    echo  '<li>' ;
                    echo  '<h3>' . esc_html__( 'List of all contacts', 'contact-list' ) . '</h3>' ;
                    echo  '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-file contact-list-shortcode-info-1" title="[contact_list]">[contact_list]</span>' ;
                    echo  '<button class="contact-list-copy contact-list-copy-for-all contact-list-copy-admin-list" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-info-1">' . esc_html__( 'Copy', 'contact-list' ) . '</button>' ;
                    echo  '</li>' ;
                    echo  '<li>' ;
                    echo  '<h3>' . esc_html__( 'List of all contacts, simpler view', 'contact-list' ) . '</h3>' ;
                    echo  '<span class="contact-list-shortcode-admin-list contact-list-shortcode-admin-list-file contact-list-shortcode-info-2" title="[contact_list_simple]">[contact_list_simple]</span>' ;
                    echo  '<button class="contact-list-copy contact-list-copy-for-all contact-list-copy-admin-list" data-clipboard-action="copy" data-clipboard-target=".contact-list-shortcode-info-2">' . esc_html__( 'Copy', 'contact-list' ) . '</button>' ;
                    echo  '</li>' ;
                    echo  '</ul>' ;
                    echo  '</li>' ;
                    echo  '<li>' ;
                    $url = esc_url_raw( get_admin_url() . 'options-general.php?page=contact-list' );
                    echo  sprintf( wp_kses(
                        /* translators: %s: link to the plugin settings */
                        __( 'Check the <a href="%s">plugin settings</a> for some customization options', 'contact-list' ),
                        array(
                            'a' => array(
                            'href'   => array(),
                            'target' => array(),
                        ),
                        )
                    ), esc_url( $url ) ) ;
                    echo  '</li>' ;
                    echo  '<li>' ;
                    $url = esc_url_raw( get_admin_url() . 'edit.php?post_type=contact&page=contact-list-shortcodes' );
                    echo  sprintf( wp_kses(
                        /* translators: %s: link to the shortcodes page */
                        __( 'List of all available shortcodes <a href="%s">here</a>', 'contact-list' ),
                        array(
                            'a' => array(
                            'href'   => array(),
                            'target' => array(),
                        ),
                        )
                    ), esc_url( $url ) ) ;
                    echo  '</li>' ;
                    echo  '</ol>' ;
                    echo  '</div>' ;
                    echo  '</div>' ;
                }
            
            }
        
        }
    
    }
    
    public function process_notifications()
    {
        
        if ( current_user_can( 'manage_options' ) ) {
            $cl_nonce = '';
            $user_id = intval( get_current_user_id() );
            $nonce_field_name = '_contact_list_ignore_how_to_notify_' . $user_id;
            
            if ( isset( $_GET[$nonce_field_name] ) ) {
                $cl_nonce = sanitize_text_field( $_GET[$nonce_field_name] );
                if ( $cl_nonce && wp_verify_nonce( $cl_nonce, 'contact_list_ignore_how_to_notify' ) ) {
                    update_option( 'contact_list_how_to_show_notice', 0, false );
                }
            }
        
        }
    
    }
    
    public function cl_get_current_time()
    {
        $current_time = time();
        return $current_time;
    }

}