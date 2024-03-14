<?php
function ultimate_subscribe_default_options(){

$options = array(
        'opt_in_process' => 'single',
        'form_messages'   => array(
            'success' => __('Thank you! We will be back with the quote.', 'ultimate-subscribe'),
            'success_double' => __('Thank you, confirmation link has sent to your Email Address', 'ultimate-subscribe'),
            'already_subscribed' => __('You have already subscribed.', 'ultimate-subscribe'),
            'confirm' => __('Thank You, You have been successfully subscribed to our newsletter.', 'ultimate-subscribe'),
            'already_confirm' => __('Your subscription is already active.', 'ultimate-subscribe'),
            'invalid_details'=>__('Error: Invalid subscription details.', 'ultimate-subscribe'),
            'error' => __('Error: some unexpected error occurred please try again.', 'ultimate-subscribe'),
        ),
        'from_name' => 'Admin',
        'from_email' => get_bloginfo('admin_email'),
        'send_mail_type' => 'html',
        'confirm_mail_subject' =>  sprintf(__('%s confirm subscription', 'ultimate-subscribe'), get_bloginfo('name')),
        'confirm_mail_content' => 'Hi ###NAME###,

A newsletter subscription request for this email address was received. Please confirm it by <a href="###LINK###">clicking here</a>. 

If you still cannot subscribe, please click this link : 
###LINK### 

Thank You
'.get_bloginfo('name'),
        'is_send_welcome_mail' => 'yes',
        'welcome_mail_subject' => sprintf(__('%s Welcome to our newsletter', 'ultimate-subscribe'), get_bloginfo('name')),
        'welcome_mail_content' => 'Hi ###NAME###, 

We have received a request to subscribe this email address to receive newsletter from our website. 

Thank You
'.get_bloginfo('name'),

        'is_send_admin_mail' => 'yes',
        'admin_email_address' => get_bloginfo('admin_email'),
        'admin_mail_subject' => sprintf(__('%s New subscriber', 'ultimate-subscribe'), get_bloginfo('name')),
        'admin_mail_content' => 'Hi Admin, 

We have received a request to subscribe new email address to receive emails from our website. 

Email: ###EMAIL### 
Name : ###NAME### 

Thank You
'.get_bloginfo('name'),
        'popup_enable' => 1,
        'overlay_hide' => 1,
        'overlay_color' => 'rgba(25, 23, 23, 0.86)',
        'popup_front_page' => 1,
        'popup_single_page' => 1,
        'popup_archive_page' => 1,
        'popup_search_page' => 1,
        'popup_404_page' => 1,
        'cookie_expire_time' => 0,


        'social_enable' => 1,
        'social_new_tab' => 1,
        'socials'       => array(
            'facebook' => array(
                    'icon' => 'fa fa-facebook',
                    'url'   => '',
                ),
            'twitter' => array(
                    'icon' => 'fa fa-twitter',
                    'url'   => '',
                ),
            'google' => array(
                    'icon' => 'fa fa-google-plus',
                    'url'   => '',
                ),
            'instagram' => array(
                    'icon' => 'fa fa-instagram',
                    'url'   => '',
                ),
            'youtube' => array(
                    'icon' => 'fa fa-youtube',
                    'url'   => '',
                ),
        ),

    );

return $options;
}

function ultimate_subscribe_get_options(){
$options                    = get_option('ultimate_subscribe_options');
$newoptions = array(
        'opt_in_process' => (isset($options['opt_in_process']))?$options['opt_in_process']:'single',
        'form_messages'   => array(
            'success' => (isset($options['form_messages']['success']))?$options['form_messages']['success']:__('Thank You, You have been successfully subscribed to our newsletter.', 'ultimate-subscribe'),
            'success_double' => (isset($options['form_messages']['success_double']))?$options['form_messages']['success_double']:__('Thank you, confirmation link has sent to your Email Address', 'ultimate-subscribe'),
            'already_subscribed' => (isset($options['form_messages']['already_subscribed']))?$options['form_messages']['already_subscribed']:__('You have already subscribed.', 'ultimate-subscribe'),
            'confirm' => (isset($options['form_messages']['confirm']))?$options['form_messages']['confirm']:__('Thank You, You have been successfully subscribed to our newsletter.', 'ultimate-subscribe'),
            'already_confirm' => (isset($options['form_messages']['already_confirm']))?$options['form_messages']['already_confirm']:__('Your subscription is already active.', 'ultimate-subscribe'),
            'invalid_details'=> (isset($options['form_messages']['invalid_details']))?$options['form_messages']['invalid_details']:__('Error: Invalid subscription details.', 'ultimate-subscribe'),
            'error' => (isset($options['form_messages']['error']))?$options['form_messages']['error']:__('Error: some unexpected error occurred please try again.', 'ultimate-subscribe'),
        ),
        'from_name' => isset($options['from_name'])? $options['from_name']:'Admin',
        'from_email' => isset($options['from_email'])? $options['from_email']:get_bloginfo('admin_email'),
        'send_mail_type' => isset($options['send_mail_type'])? $options['send_mail_type']:'html',
        'confirm_mail_subject' => isset($options['confirm_mail_subject'])? $options['confirm_mail_subject']: sprintf(__('%s confirm subscription', 'ultimate-subscribe'), get_bloginfo('name')),
        'confirm_mail_content' => isset($options['confirm_mail_content'])? $options['confirm_mail_content']:'Hi ###NAME###,

A newsletter subscription request for this email address was received. Please confirm it by <a href="###LINK###">clicking here</a>. 

If you still cannot subscribe, please click this link : 
###LINK### 

Thank You
Plugin Dev',
        'is_send_welcome_mail' => isset($options['is_send_welcome_mail'])? $options['is_send_welcome_mail']:'yes',
        'welcome_mail_subject' => isset($options['welcome_mail_subject'])? $options['welcome_mail_subject']:sprintf(__('%s Welcome to our newsletter', 'ultimate-subscribe'), get_bloginfo('name')),
        'welcome_mail_content' => isset($options['welcome_mail_content'])? $options['welcome_mail_content']:'Hi ###NAME###, 

We have received a request to subscribe this email address to receive newsletter from our website. 

Thank You
Plugin Dev',

        'is_send_admin_mail' => $is_send_admin_mail     = isset($options['is_send_admin_mail'])? $options['is_send_admin_mail']:'yes',
        'admin_email_address' => $admin_email_address    = isset($options['admin_email_address'])? $options['admin_email_address']:get_bloginfo('admin_email'),
        'admin_mail_subject' => $admin_mail_subject     = isset($options['admin_mail_subject'])? $options['admin_mail_subject']:sprintf(__('%s New subscriber', 'ultimate-subscribe'), get_bloginfo('name')),
        'admin_mail_content' => $admin_mail_content     = isset($options['admin_mail_content'])? $options['admin_mail_content']:'Hi Admin, 

We have received a request to subscribe new email address to receive emails from our website. 

Email: ###EMAIL### 
Name : ###NAME### 

Thank You
Plugin Dev',
        'popup_enable' => isset($options['popup_enable'])?$options['popup_enable']:0,
        'overlay_hide' => isset($options['overlay_hide'])?$options['overlay_hide']:0,
        'overlay_color' => isset($options['overlay_color'])?$options['overlay_color']:'rgba(25, 23, 23, 0.86)',
        'popup_front_page' => isset($options['popup_front_page'])?$options['popup_front_page']:0,
        'popup_single_page' => isset($options['popup_single_page'])?$options['popup_single_page']:0,
        'popup_archive_page' => isset($options['popup_archive_page'])?$options['popup_archive_page']:0,
        'popup_search_page' => isset($options['popup_search_page'])?$options['popup_search_page']:0,
        'popup_404_page' => isset($options['popup_404_page'])?$options['popup_404_page']:0,
        'cookie_expire_time' => isset($options['cookie_expire_time'])?$options['cookie_expire_time']:0,


        'social_enable' => isset($options['social_enable'])?$options['social_enable']:0,
        'social_new_tab' => isset($options['social_new_tab'])?$options['social_new_tab']:0,
        'socials'       => array(
            'facebook' => array(
                    'icon' => isset($options['socials']['facebook']['icon'])?$options['socials']['facebook']['icon']:'fa fa-facebook',
                    'url'   => isset($options['socials']['facebook']['url'])?$options['socials']['facebook']['url']:'',
                ),
            'twitter' => array(
                    'icon' => isset($options['socials']['twitter']['icon'])?$options['socials']['twitter']['icon']:'fa fa-twitter',
                    'url'   => isset($options['socials']['twitter']['url'])?$options['socials']['twitter']['url']:'',
                ),
            'google' => array(
                    'icon' => isset($options['socials']['google']['icon'])?$options['socials']['google']['icon']:'fa fa-google-plus',
                    'url'   => isset($options['socials']['google']['url'])?$options['socials']['google']['url']:'',
                ),
            'instagram' => array(
                    'icon' => isset($options['socials']['instagram']['icon'])?$options['socials']['instagram']['icon']:'fa fa-instagram',
                    'url'   => isset($options['socials']['instagram']['url'])?$options['socials']['instagram']['url']:'',
                ),
            'youtube' => array(
                    'icon' => isset($options['socials']['youtube']['icon'])?$options['socials']['youtube']['icon']:'fa fa-youtube',
                    'url'   => isset($options['socials']['youtube']['url'])?$options['socials']['youtube']['url']:'',
                ),            
        ),
        'mailchimp_api' => isset($options['mailchimp_api'])?$options['mailchimp_api']:'',
    );

return $newoptions;
}