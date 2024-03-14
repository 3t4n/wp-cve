<?php

//$this->print_array($_COOKIE);
$form_template = (!empty($atts['template'])) ? $atts['template'] : $form_details['layout']['template'];
$lock_content = $form_details['general']['lock_content'];
$unlock_check = (isset($_COOKIE['stul_unlock_key'], $_COOKIE['stul_unlock_check']) && $this->check_if_already_subscribed($_COOKIE['stul_unlock_key'])) ? true : false;
$unlock_check = (!empty($form_details['general']['test_mode']) && is_user_logged_in()) ? false : $unlock_check;
if ($unlock_check) {
    if (!empty($content)) {
        echo do_shortcode($content);
    } else {
        echo do_shortcode($this->sanitize_html($lock_content));
    }
} else {
    $alias_class = 'stul-lite-alias';
    $heading_show = (!empty($form_details['form']['heading']['show'])) ? true : false;
    $heading_text = $form_details['form']['heading']['text'];
    $sub_heading_show = (!empty($form_details['form']['sub_heading']['show'])) ? true : false;
    $sub_heading_text = $form_details['form']['sub_heading']['text'];
    $name_show = (!empty($form_details['form']['name']['show'])) ? true : false;
    $name_label = $form_details['form']['name']['label'];
    $email_label = $form_details['form']['email']['label'];
    $terms_agreement_show = (!empty($form_details['form']['terms_agreement']['show'])) ? true : false;
    $terms_agreement_text = $form_details['form']['terms_agreement']['agreement_text'];
    $subscribe_button_text = $form_details['form']['subscribe_button']['button_text'];
    $footer_show = (!empty($form_details['form']['footer']['show'])) ? true : false;
    $footer_text = $form_details['form']['footer']['footer_text'];
    $lock_mode = (!empty($form_details['general']['lock_mode'])) ? $form_details['general']['lock_mode'] : 'soft';
    $lock_mode_class = 'stul-' . $lock_mode . '-mode';
    include(STUL_PATH . 'inc/views/frontend/form-template.php');
}
