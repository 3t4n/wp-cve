<?php
/*
 *  Shortcode :  [swiftsign method="GET/POST" action="form action" swift_form_id="" fullpagemode=""].....[/swiftsign]
 *  Generates a form.
 *      method : form method GET or POST. Default is POST
 *      action : gives action to form for submit. Default is blank.
 *      swift_form_id : form id of swift form. for ex: 266,261 etc..
 *      fullpagemode: On/Off
 */
ob_start();
if ((function_exists('session_status') && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_start();
}

function swiftsign_shortcode($atts, $content = null) {
    wp_enqueue_style('ss-checkbox-radio-style', plugins_url('/css/checkbox_radio.css', dirname(__FILE__)), '', '', '');
    wp_enqueue_style('swiftcloud-fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', '', '', '');
    wp_enqueue_script('jquery-effects-core');
    wp_enqueue_script('jquery-effects-shake');

    wp_enqueue_script('jquery-ui-tooltip');
    wp_enqueue_style('swift-jquery-ui-style', plugins_url('/css/jquery-ui.css', dirname(__FILE__)), '', '', '');
//    wp_enqueue_script('swift-form-jstz', SWIFTSIGN_PLUGIN_URL . "js/jstz.min.js", '', '', true);

    $output = "";
    $a = shortcode_atts(
            array(
        'method' => '',
        'action' => '',
        'swift_form_id' => '',
        'fullpagemode' => ''
            ), $atts);
    extract($a);

    $upgradeNoticeFlag = true; // true/false to show upgrade notice
    $output .= ($upgradeNoticeFlag) ? upgradeNotice() : "";

    return $output;
}

/*
 *  shortcode : [swiftsign_affiliate_name required]
 *        Generate hidden field for Affiliate / Source Name.
 *        This will only work with Swift BloodHound plugin
 */

add_shortcode('swiftsign_affiliate_name', 'swiftsign_affiliate_name_shortcode');

function swiftsign_affiliate_name_shortcode() {
    if (isset($_COOKIE['agent_id'])) {
        if ($agent_meta = get_user_by('id', $_COOKIE['agent_id'])) {
            return '<input type="hidden" name="extra_affiliate_name" id="extra_affiliate_name" value="' . $agent_meta->display_name . '" />';
        }
    }
}

/*
 *  shortcode : [swift_thanksurl url='']
 *  Generate hidden field for vThanksURL.
 */

add_shortcode('swift_thanksurl', 'swift_thanksurl_shortcode');

function swift_thanksurl_shortcode($attr) {
    $output = $url = "";
    $a = shortcode_atts(
            array(
        'url' => '',
            ), $attr);
    extract($a);

    if (isset($url) && !empty($url)) {
        $output = '
                    <script type="text/javascript">
                         window.addEventListener("load", function() {
                             jQuery("#ssign-hidden-fields").append("<input type=\"hidden\" name=\"vThanksRedirect\" value=\"' . $url . '\" />");
                         });
                    </script>';
    }
    return $output;
}

function upgradeNotice() {
    $notice = '<div class="ss_alert_notice bg_danger">
                    <h4 class="text-center"><i class="fa fa-exclamation-triangle"></i> Moved</h4>
                    <span>
                        <a href="https://swiftcloud.ai/products/electronic-signature" target="_blank">Electronic Signature</a> on Wordpress has been disabled. For the owner of this website, please migrate your doc(s) as per <a href="https://swiftcloud.ai/support/wordpress-e-signature-plugin-migration" target="_blank">https://swiftcloud.ai/support/wordpress-e-signature-plugin-migration</a>.
                    </span>
                </div>';
    return $notice;
}
