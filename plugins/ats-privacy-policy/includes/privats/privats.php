<?php

$all_options = get_option('atss_options');
/*THE COMMENTING FORM - PRIVACY POLICY*/

$option_ats = get_option('atss_options');
    if ($option_ats['checkesform']) {
    add_action('comment_form', 'ats_privacy_policy_syte_comm');              // for the usual custom comment form

$optionatsform = $all_options['atsy_text_forms'];
    add_action( $optionatsform , 'ats_privacy_policy_syte_comm');            // for custom comment form
    //add_action('mihalica_comment_form', 'ats_privacy_policy_syte_comm');     // for custom comment form
}

function ats_privacy_policy_syte_comm($id) {
$all_options = get_option('atss_options');

$option_ats = get_option('atss_options');
    if ($option_ats['atscheckes']) { // <span class="required-field-ats">*&nbsp;</span>
$checkcs = 'checked="checked"';
}
    if (!is_user_logged_in()) :
    print '<div style="' . $all_options['atsy_textsssy'] . '"><span class="refe"><input type="checkbox" name="submit-privacy" ' . $checkcs . ' /></span>&nbsp;<span class="ats-privacy-policy">' . $all_options['atsy_text_tex'] . '<a title="' . $all_options['atsy_text_links'] . '" href="/' . $all_options['atsy_text'] . '" rel="nofollow noopener noreferrer" onclick="return !window.open(this.href)">' . $all_options['atsy_text_links'] . '</a>&nbsp;</span></div>';
    endif;
}

/**/
    add_action('comment_form', 'ats_privacy_policy_po_s');                   // for the usual custom comment form
$optionatsform = $all_options['atsy_text_forms'];
    add_action( $optionatsform , 'ats_privacy_policy_po_s');                 // for custom comment form
    //add_action('mihalica_comment_form', 'ats_privacy_policy_po_s');          // for custom comment form
    
function ats_privacy_policy_po_s($id) {
$all_options = get_option('atss_options');                                   // this is the configuration array

$option_ats = get_option('atss_options');
    if (! $option_ats['checkesformlincs']) {
$atsssdds = '<br /><span class="ats-privacys">доступен плагин <a title="плагин ATs Privacy Policy - политика конфиденциальности" href="https://mihalica.ru/product/plagin-privacy-policy-wordpress/" rel="nofollow noopener noreferrer" onclick="return !window.open(this.href)">ATs Privacy Policy </a><b>©</b></span>';
}
    if (!is_user_logged_in()) :
    print '<div class="ats-privacy">'. $all_options['atsy_textsssy_non'] . '</div> '. $atsssdds . '';
    endif;
}

//<span style="font-size:12px;"></span>
/**/
    add_action('comment_post', 'ats_privacy_policy_sytes');
function ats_privacy_policy_sytes($id) {
    if (!is_user_logged_in()) :
    if (!$_POST['submit-privacy']) :
$updated_status = 'trash';
    wp_set_comment_status($id, $updated_status);
    wp_die('Вы не приняли правила конфиденциальности: вернитесь и подтвердите согласие... Ваш набранный текст в форме замечательно сохранён! You did not accept the privacy rules: go back and accept... Your typed text in the form of a wonderfully saved!<p><a href="javascript:history.back();">&larr;Назад | Back</a></p>');
    endif; endif;
}

/*THE COMMENTING FORM - PRIVACY POLICY style="width:auto;margin:5px;"*/
/*start added to the ADMIN bar menu*
add_action('admin_bar_menu', 'add_miha_ats_admin_bar_link',39);
function add_miha_ats_admin_bar_link() {
global $wp_admin_bar;
if ( !is_super_admin() || !is_admin_bar_showing() )
return;
$wp_admin_bar->add_menu( array(
    'id' => 'add_my_atsy',                      // Can be any value and must be unique
    'title' => __( 'МЕНЮ-КОНСОЛИ'),             // The display title in the Menu
    'href' => __('/wp-admin/'),
));
// To add submenu links to similar
$wp_admin_bar->add_menu( array(
    'parent' => 'add_my_atsy',                 // Can be any value and must be unique
    'id'     => 'views_ats_plag',              // Unique identifier of the parent menu
    'title' => __( 'смотреть ATs плагины'),    // The display title in the Menu
    'href' => __('/wp-admin/plugin-install.php?s=+ATs.M&tab=search&type=term'),
));
// To add submenu links to similar
$wp_admin_bar->add_menu( array(
    'parent' => 'add_my_atsy',                 // Can be any value and must be unique
    'id'     => 'views_ats_plagy',
    'title' => __( 'плагины'),
    'href' => __('/wp-admin/plugins.php'),
));
$wp_admin_bar->add_menu( array(
    'parent' => 'add_my_atsy',                 // Unique identifier of the parent menu
    'id'     => 'views_ats_ops',
    'title' => __( 'ОПЦИИ WP'),
    'href' => __('/wp-admin/options.php'),
));
}
/*fin added to the ADMIN bar menu*/