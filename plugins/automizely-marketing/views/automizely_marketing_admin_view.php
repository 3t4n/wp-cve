<?php
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if ($_GET['purge'] == 'yes') {
    update_option(AUTOMIZELY_SCRIPT_TAGS, []);
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$store_url = get_home_url();

$query = [
    'shop'=>$store_url,
    'utm_source' => 'wordpress_plugin',
    'utm_medium' => 'landingpage'
];

$debug = isset($_GET['debug']) ? $_GET['debug'] : 'no';

$go_to_dashboard_url = "https://accounts.aftership.com/oauth-session?callbackUrl=".urlencode("https://accounts.aftership.com/oauth/woocommerce-automizely-messages?signature=".base64_encode(json_encode($query)));
$go_to_visit_url = "https://www.automizely.com/marketing/?utm_source=wordpress_plugin&utm_medium=landingpage";

if ($debug === 'yes') {
    $go_to_dashboard_url = "https://accounts.aftership.io/oauth-session?callbackUrl=".urlencode("https://accounts.aftership.io/oauth/woocommerce-automizely-messages?signature=".base64_encode(json_encode($query)));
    $go_to_visit_url = "https://www.automizely.com/marketing/?utm_source=wordpress_plugin&utm_medium=landingpage";
}
?>

<!-- Main wrapper -->
<div class="automizely_overlay"></div>
<div class="automizely_wrap">
    <div class="automizely_content">
        <img alt="Automizely Marketing" class="automizely_logo" src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/aftership_email_logo.svg' ?>" />
        <br />
        <img alt="WELCOME" class="automizely_welcome" src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/welcome.svg' ?>" />
        <div class="automizely_desc">Automizely Marketing  is a tool for<br /> Email Marketing, Sales Popups, Bars & More!</div>
        <img src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/install.svg' ?>" />
        <a class="automizely_btn" href="<?php echo $go_to_dashboard_url; ?>" target="_blank">let’s get started</a>
        <div class="automizely_visit">Visit us at <a href="<?php echo $go_to_visit_url; ?>" target="_blank">Automizely.com</a></div>
        <div class="automizely_reviews">
            <div class="automizely_reviews_title">
                Help us improve by submitting a review
            </div>
            <table
              align="center"
              style="border-collapse: collapse; border-spacing: 0; border: 0; padding: 0"
            >
              <tbody style="vertical-align: middle; padding: 0">
                <tr>
                  <td
                    align="center"
                    style="
                      text-align: center;
                      border: 0;
                      vertical-align: middle;
                      padding: 0;
                    "
                  >
                    <a href="https://www.aftership.com/contact-us?utm_source=wordpress_plugin&utm_medium=landingpage_star1" target="_blank" style="display: inline-block"
                      ><img
                        style="border: 0px; display: block"
                        alt="#1 star icon"
                        src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/star.png' ?>"
                      /> </a
                    ><a href="https://www.aftership.com/contact-us?utm_source=wordpress_plugin&utm_medium=landingpage_star2" target="_blank" style="display: inline-block"
                      ><img
                        style="border: 0"
                        alt="#2 star icon"
                        src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/star.png' ?>"
                      /> </a
                    ><a href="https://www.aftership.com/contact-us?utm_source=wordpress_plugin&utm_medium=landingpage_star3" target="_blank" style="display: inline-block"
                      ><img
                        style="border: 0"
                        alt="#3 star icon"
                        src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/star.png' ?>"
                      /> </a
                    ><a href="https://wordpress.org/support/plugin/automizely-marketing/reviews/#new-post" target="_blank" style="display: inline-block"
                      ><img
                        style="border: 0"
                        alt="#4 star icon"
                        src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/star.png' ?>"
                      /> </a
                    ><a href="https://wordpress.org/support/plugin/automizely-marketing/reviews/#new-post" target="_blank" style="display: inline-block"
                      ><img
                        style="border: 0"
                        alt="#5 star icon"
                        src="<?php echo AUTOMIZELY_MARKETING_URL . '/assets/images/star.png' ?>"
                    /></a>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
    </div>
</div>
