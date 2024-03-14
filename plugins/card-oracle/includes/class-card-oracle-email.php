<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://chillichalli.com
 * @since      1.0.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.1.2
 * @package    Card_Oracle
 * @subpackage Card_Oracle/includes
 * @author     Christopher Graham <support@chillichalli.com>
 */
class CardOracleEmail
{
    /**
     * Set up the Card Oracle Logging Class.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
    }
    
    /**
     * Add the email button to the reading.
     *
     * @since 1.1.3
     *
     * @param string $email_content The reading html to send to the user.
     * @param string $reading_id The id of the reading.
     */
    public function add_email_button( $email_content, $reading_id )
    {
        $html = '';
        // Add email button to page if option enabled.
        
        if ( get_option( 'card_oracle_allow_email' ) ) {
            $styles = file_get_contents( CARD_ORACLE_CSS );
            $email_text = card_oracle_create_email( esc_html__( 'Your Reading', 'card-oracle' ), $styles, $email_content );
            $form_text = get_option( 'card_oracle_email_text', __( 'Email this Reading to:', 'card-oracle' ) );
            $html .= '<div class="card-oracle-email">';
            $html .= '<p>' . $form_text . '</p>';
            $html .= wp_nonce_field( 'card-oracle-send-reader-email' );
            $html .= '<input type="text" name="emailaddress" placeholder="' . esc_attr__( 'Email Address', 'card-oracle' ) . '" id="emailaddress" />';
            $html .= '<input type="submit" name="reading-send" value="' . __( 'Send', 'card-oracle' ) . '" id="reading-send" />';
            $html .= '<input type="hidden" id="ajax_url" name="ajax_url" value="' . admin_url( 'admin-ajax.php' ) . '">';
            $html .= '<input type="hidden" id="emailcontent" name="emailcontent" value="' . rawurlencode( $email_text ) . '">';
            
            if ( get_option( 'card_oracle_subscribe' ) ) {
                $html .= '<input type="hidden" id="readingid" name="readingid" value="' . $reading_id . '">';
                $html .= '<br /><div class="card-oracle-subscribe">';
                $html .= '<input type="checkbox" id="card-oracle-subscribe" name="card-oracle-subscribe" />';
                $text = ( get_option( 'card_oracle_email_consent_text' ) ? get_option( 'card_oracle_email_consent_text' ) : __( 'Subscribe to our list to keep up to date with our latest news.', 'card-oracle' ) );
                $html .= '<label for="card-oracle-subscribe">' . esc_html( $text ) . '</label>';
                $html .= '</div>';
            }
            
            $html .= '<p class="card-oracle-response"></p>';
            $html .= '</div>';
        }
        
        return $html;
    }

}