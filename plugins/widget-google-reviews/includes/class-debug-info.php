<?php

namespace WP_Rplg_Google_Reviews\Includes;

use WP_Rplg_Google_Reviews\Includes\Core\Database;

class Debug_Info {

    private $activator;
    private $feed_deserializer;

    public function __construct(Activator $activator, Feed_Deserializer $feed_deserializer) {
        $this->activator = $activator;
        $this->feed_deserializer = $feed_deserializer;
    }

    public function render() {
        global $wpdb;
        global $wp_version;

        ?>

URL: <?php echo esc_url(get_option('siteurl')); ?>

PHP Version: <?php echo esc_html(phpversion()); ?>

WP Version: <?php echo esc_html($wp_version); ?>

WP Language: <?php echo get_locale(); ?>

Active Theme:
<?php
if (!function_exists('wp_get_theme')) {
    $theme = get_theme(get_current_theme());
    echo esc_html($theme['Name'] . ' ' . $theme['Version']);
} else {
    $theme = wp_get_theme();
    echo esc_html($theme->Name . ' ' . $theme->Version);
}
?>

Outgoing HTTPS requests: <?php
$res = wp_remote_get('https://app.richplugins.com/checkconn?key=' . md5(get_option('grw_auth_code')));
$body = wp_remote_retrieve_body($res);
if (strpos($body, 'success:') !== false) {
    $body_split = explode(':', $body);
    $rand_key = $body_split[1];
    echo 'Outgoing HTTPS requests are open';
} else {
    echo 'Outgoing HTTPS requests are closed';
}
?>

Plugin Version: <?php echo esc_html(GRW_VERSION); ?>

Settings:
<?php foreach ($this->activator->options() as $opt) {
    $val = get_option($opt);
    if ($opt == 'grw_google_api_key' && $val && isset($rand_key)) {
        echo esc_html($opt . ': encrypted(' . $this->encrypt($val, $rand_key) . ")\n");
    } else {
        if ($opt == 'grw_auth_code') {
            $val = md5($val);
        }
        echo esc_html($opt . ': ' . $val . "\n");
    }
}
?>

Widgets: <?php $widget = get_option('widget_grw_widget'); echo ($widget ? print_r($widget) : '')."\n"; ?>

Plugins:
<?php
foreach (get_plugins() as $key => $plugin) {
    $isactive = "";
    if (is_plugin_active($key)) {
        $isactive = "(active)";
    }
    echo esc_html($plugin['Name'].' '.$plugin['Version'].' '.$isactive."\n");
}
?>

------------ Feeds ------------

<?php
$feeds = $this->feed_deserializer->get_all_feeds();
if (is_array($feeds) || is_object($feeds)) {
    foreach ($feeds as $feed) {
        echo $feed->ID . " " . $feed->post_title . ": " . $feed->post_content . "\r\n\r\n";
    }
}
?>

<?php
$places        = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . Database::BUSINESS_TABLE);
$places_error  = $wpdb->last_error;
$reviews       = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE);
$reviews_error = $wpdb->last_error;
$stats         = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . Database::STATS_TABLE);
$stats_error   = $wpdb->last_error; ?>

------------ Places ------------

<?php if (isset($places_error) && strlen($places_error) > 0) { echo 'DB Places error: ' . $places_error; } ?>

<?php echo print_r($places); ?>


------------ Reviews ------------

<?php if (isset($reviews_error) && strlen($reviews_error) > 0) { echo 'DB Reviews error: ' . $reviews_error; } ?>

<?php echo print_r($reviews); ?>

------------ Stats ------------

<?php if (isset($stats_error) && strlen($stats_error) > 0) { echo 'DB Stats error: ' . $stats_error; } ?>

<?php echo print_r($stats);

    }

    private function encrypt($text, $key) {
        $cipher = 'aes-256-cbc';
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ivlen = openssl_cipher_iv_length($cipher);
            $iv = openssl_random_pseudo_bytes($ivlen);
            $encrypted = openssl_encrypt($text, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
            $ciphertext = base64_encode($iv.$encrypted);
            if (!empty($ciphertext)) {
                $ciphertext = str_replace("+", "%2b", $ciphertext);
            }
            return $ciphertext;
        }
    }

}
