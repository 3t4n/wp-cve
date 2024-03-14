<?php
/**
 * Plugin Name: Easy UTM tracking with contact form 7
 * Plugin URI: http://decorumsol.com
 * Description: This plugin adds UTM tracking code to your contact form 7 based on js cookies.
 * Version: 2.0.6
 * Author: Basir Mukhtar
 * Author URI: http://basirmukhtar.com
 */

// Check if Contact Form 7 is active, otherwise exit.
if ( ! function_exists( 'wpcf7' ) ) {
    add_action( 'admin_notices', 'easy_utm_contact_form_7_notice' );
    function easy_utm_contact_form_7_notice() {
        echo '<div class="error"><p>The <strong>Easy UTM tracking with contact form 7</strong> plugin requires <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> plugin to be installed and activated. Please install and activate <a href="https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a> first.</p></div>';
    }
    return;
}

/**
 * adding script to catch utm and store them to cookies
 */
function easy_utm_enqueue_script() {
    wp_enqueue_script('utm_contact_form7_scripts', plugin_dir_url(__FILE__) . 'js/ucf7_scripts.js',  array(), 'version', true);    
}
add_action('wp_enqueue_scripts', 'easy_utm_enqueue_script');

add_action('wpcf7_before_send_mail', 'wpcf7_update_email_body');

/**
 * Update email body with UTM parameters.
 *
 * @param object $contact_form Contact form object.
 */
function wpcf7_update_email_body($contact_form) {
    $submission = WPCF7_Submission::get_instance();
    if ($submission) {
        //getting utm variables from js cookie.
        $cookies = sanitize_text_field($_COOKIE['_deco_utmz']);
        $all_utm = explode("|", $cookies);
        $url = sanitize_text_field($_COOKIE['_deco_utmurl']);
        $referrer = sanitize_text_field($_COOKIE['_deco_utm_referrer']);
        
        $email_utm = '';
        if (isset($all_utm)) {
            $email_utm .= '<style type="text/css">tr:nth-child(even) { background-color: #eff0f1; }</style><table cellpadding="10" border="1" style="border-collapse:collapse; width:50%;">';
            
            $email_utm .= '<tr style="background-color: #eff0f1;"><td><strong>UTM Parameter:</strong></td><td><strong>Value</strong></td></tr>';
            $email_utm .= '<tr><td>utm_source:</td><td>'. $all_utm[0] .'</td></tr>';
            $email_utm .= '<tr style="background-color: #eff0f1;"><td>utm_medium:</td><td>'. $all_utm[1] .'</td></tr>';
            $email_utm .= '<tr><td>utm_term:</td><td>'. $all_utm[2] .'</td></tr>';
            $email_utm .= '<tr style="background-color: #eff0f1;"><td>utm_content:</td><td>'. $all_utm[4] .'</td></tr>';
            $email_utm .= '<tr><td>utm_campaign: </td><td>'. $all_utm[3] .'</td></tr>';
            $email_utm .= '<tr style="background-color: #eff0f1;"><td>Landing page URL: </td><td>' . $url .'</td></tr>';
            $email_utm .= '<tr style=""><td>Page Referrer: </td><td>' . $referrer .'</td></tr>';
            $email_utm .='</table>';
        }
        
        $mail = $contact_form->prop('mail');

        // Append utm parameters to email body and set HTML format
        $mail['body'] .= $email_utm;

        //forcing to use HTML instead of plain text. 
        $mail['use_html'] = 1;
        $contact_form->set_properties(array('mail' => $mail));
    }
}
