<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2020 www.BillMinozzi.com
 * @ Modified time: 2021-03-02 17:19:27
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
/*
<h2>Cookies</h2>We use cookies to improve the user experience and personalize content. If you disagree, please, press BACK on your browser.
*/
$allowed_atts = array(
    'align'      => array(),
    'class'      => array(),
    'type'       => array(),
    'id'         => array(),
    'dir'        => array(),
    'lang'       => array(),
    'style'      => array(),
    'xml:lang'   => array(),
    'src'        => array(),
    'alt'        => array(),
    'href'       => array(),
    'rel'        => array(),
    'rev'        => array(),
    'target'     => array(),
    'novalidate' => array(),
    'type'       => array(),
    'value'      => array(),
    'name'       => array(),
    'tabindex'   => array(),
    'action'     => array(),
    'method'     => array(),
    'for'        => array(),
    'width'      => array(),
    'height'     => array(),
    'data'       => array(),
    'title'      => array(),
    'checked' => array(),
    'selected' => array(),
);
$my_allowed['strong']   = $allowed_atts;
$my_allowed['small']    = $allowed_atts;
$my_allowed['h1']       = $allowed_atts;
$my_allowed['h2']       = $allowed_atts;
$my_allowed['h3']       = $allowed_atts;
$my_allowed['h4']       = $allowed_atts;
$my_allowed['h5']       = $allowed_atts;
$my_allowed['h6']       = $allowed_atts;
$my_allowed['hr']       = $allowed_atts;
$my_allowed['i']        = $allowed_atts;
$recaptcha_for_all_default_message = "<h2>Cookies</h2>";
$recaptcha_for_all_default_message .= esc_attr__("We use cookies and javascript to improve the user experience and personalise content and ads, to provide social media features and to analyze our traffic.","recaptcha-for-all");
$recaptcha_for_all_default_message .= esc_attr__("We also share information about your use of our site with our social media, advertising and analytics partners who may combine it with other information that you’ve provided to them or that they’ve collected from your use of their services.","recaptcha-for-all");
$recaptcha_for_all_default_message .= esc_attr__("If you disagree, please, press BACK on your browser.","recaptcha-for-all");
$recaptcha_for_all_default_message = esc_attr($recaptcha_for_all_default_message ,"recaptcha-for-all");
if (isset($_GET['page']) && $_GET['page'] == 'recaptcha_for_all_admin_page') {
    if (isset($_POST['process']) && $_POST['process'] == 'recaptcha_for_all_admin_message') {
        $recaptcha_for_all_updated = false;
        if (isset($_POST['message'])) {
            // $recaptcha_for_all_message = sanitize_text_field($_POST['message']);
            $recaptcha_for_all_message = trim(wp_kses($_POST['message'], $my_allowed));
            if (!empty($recaptcha_for_all_message)) {
                update_option('recaptcha_for_all_message', $recaptcha_for_all_message);
                $recaptcha_for_all_updated = true;
            } else {
                update_option('recaptcha_for_all_message', trim(wp_kses($recaptcha_for_all_default_message, $my_allowed)));
                $recaptcha_for_all_updated = true;
            }
            if (isset($_POST['button'])) {
                $recaptcha_for_all_button = sanitize_text_field($_POST['button']);
                if (!empty($recaptcha_for_all_button)) {
                    update_option('recaptcha_for_all_button', $recaptcha_for_all_button);
                    $recaptcha_for_all_updated = true;
                } else {
                    update_option('recaptcha_for_all_button', 'I Agree');
                    $recaptcha_for_all_updated = true;
                }
            }
            if ($recaptcha_for_all_updated)
                recaptcha_for_all_updated_message();
        }
    }
}
// Escaped below...
$recaptcha_for_all_message = trim(wp_kses(get_option('recaptcha_for_all_message', ''), $my_allowed));
$recaptcha_for_all_text_button = trim(sanitize_text_field(get_option('recaptcha_for_all_button', '')));
if (empty($recaptcha_for_all_text_button))
    $recaptcha_for_all_text_button = esc_attr__('I Agree',"recaptcha-for-all");
if (empty($recaptcha_for_all_message)) {
    $recaptcha_for_all_message = $recaptcha_for_all_default_message;
}
echo '<div class="wrap-recaptcha ">' . "\n";
echo '<h2 class="title">'.esc_attr__("Manage Message and Button", "recaptcha-for-all").'</h2>' . "\n";
?>
<p class="description"> <?php esc_attr_e("You can create and edit the message and the button will show up when the user visit your site for the first time, change device or cookie expire (60 days).","recaptcha-for-all"); ?>
    <br>
    <b>  <?php esc_attr_e("HTML allow:","recaptcha-for-all"); echo htmlentities('<h1> to <h6>, <hr> <i> <small> <strong>'); ?>
    </b>
    <br> <?php esc_attr_e("Leave the field empty to show up the default value.", "recaptcha-for-all"); ?>
    <br>
    <?php esc_attr_e('You can also use this kind of message: "Are You Human?" with the button "Yes".',"recaptcha-for-all"); ?>
    <br>
    <?php esc_attr_e("It is up to you.", "recaptcha-for-all"); ?>
    <br><br>
<form class="recaptcha_for_all-form" method="post" action="admin.php?page=recaptcha_for_all_admin_page&tab=message">
    <input type="hidden" name="process" value="recaptcha_for_all_admin_message" />
    <label for="message">  <?php esc_attr_e("Message", "recaptcha-for-all"); ?>:</label>
    <br>
    <textarea id="message" name="message" rows="6" cols="50"><?php echo esc_html($recaptcha_for_all_message); ?></textarea>
    <br><br>
    <label for="sitekey"><?php esc_attr_e("Text Button", "recaptcha-for-all"); ?>:</label>
    <br>
    <input type="text" id="button" name="button" size="15" value="<?php echo esc_html($recaptcha_for_all_text_button); ?>"><br><br>
    <?php
    echo '<br />';
    // echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="Update" />';
    echo '<input class="recaptcha_for_all-submit button-primary" type="submit" value="'.esc_attr__("Update", "recaptcha-for-all").'" />';
    echo '</form>' . "\n";
    echo '</div>';
    function recaptcha_for_all_updated_message()
    {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<br /><b>';
        esc_attr_e('Database Updated!', "recaptcha-for-all");
        echo '<br /><br /></div>';
    }
