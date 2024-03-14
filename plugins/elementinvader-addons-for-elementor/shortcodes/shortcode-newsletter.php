<?php

/*
* Widget [eli-newsletter], show latest listings in list view
* atts list:
*
* custom_class (string) - custom css class
* disable_mail_send (string) yes/'' - no send email
* mail_data_subject (string) - Subject of mail
* mail_data_from_email (string)
* mail_data_from_name (string)
* mail_data_to_email (string)

*
* Layout path : 
* get_template_directory().'/elementor-elementinvader_addons_for_elementor/shortcodes/views/shortcode-newsletter.php'
* WPDIRECTORYKIT_PATH.'shortcodes/views/shortcode-newsletter.php'
*/

add_shortcode('eli-newsletter', 'eli_shortcode_newsletter');
function eli_shortcode_newsletter($atts, $content){
    $atts = shortcode_atts(array(
        'id'=>NULL,
        'custom_class'=>'',
        'disable_mail_send'=>'',
        'mail_data_subject'=>esc_html__('Newsletter', 'elementinvader-addons-for-elementor'),
        'mail_data_from_email'=>get_bloginfo('admin_email'),
        'mail_data_from_name'=>get_bloginfo('admin_email'),
        'mail_data_to_email'=>get_bloginfo('admin_email'),
        'recaptcha_site_key'=>'',
        'recaptcha_secret_key'=>'',
        'section_send_action_mailchimp_api_key'=>'',
        'section_send_action_mailchimp_list_id'=>'',
        'send_action_type'=>'mail_base',
    ), $atts);
    $data = array();

    /* settings from atts */
    $data['settings'] = $atts;
    $data['id_element'] = '';

    /* load css/js */

    return eli_shortcodes_view('shortcode-newsletter', $data);
}

?>