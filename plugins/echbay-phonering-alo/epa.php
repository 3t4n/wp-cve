<?php
/**
* Plugin Name: EchBay Phonering Alo
* Description: A very simple yet very effective plugin that adds a Call Now button to your website for every device (mobile, table and desktop).
* Plugin URI: https://www.facebook.com/groups/wordpresseb
* Plugin Facebook page: https://www.facebook.com/webgiare.org
* Author: Dao Quoc Dai
* Author URI: https://www.facebook.com/ech.bay/
* Version: 1.3.0
* Text Domain: webgiareorg
* Domain Path: /languages/
* License: GPLv2 or later
*/
defined('ABSPATH') or die('Invalid request.');
define('EPA_DF_VERSION', '1.3.0');
define('EPA_THIS_PLUGIN_NAME', 'EchBay Phonering Alo');
if (!class_exists('EPA_Actions_Module')) {
class EPA_Actions_Module
{
public $optionName = '___epa___';
public $optionGroup = 'epa-options-group';
public $defaultOptions = [
'phone_number' => '',
'header_bg' => '#0084FF',
'html_template' => '',
'mobile_grid' => 'yes',
'messenger_url' => '',
'messenger_full_url' => '',
'zalo_url' => '',
'sms_bg' => '#ff6600',
'messenger_bg' => '#e60f1e',
'mobile_width' => 0,
'widget_position' => 'bl',
'dynamic_style' => '',
'custom_style' => '',
];
public $defaultNameOptions = [
'phone_number' => [
'name' => 'Phone number',
'description' => 'The phone number for custom click call to you. One phone number only!',
],
'header_bg' => [
'name' => 'Background Color',
'description' => '* Note: function choose color only runing in browser IE9++, Microsoft Edge, Chrome, Safari, Firefox or another browsers support HTML5.',
'type' => 'color',
],
'custom_icon' => [
'name' => 'Font Awesome Icon',
'description' => 'If your site using Font Awesome! You can enter the icon you want to use for replace default icon of this plugin. Example: fa fa-phone or fas fa-phone-volume',
],
'html_template' => [
'name' => 'Template',
'type' => 'select',
'option' => [
'' => 'Phonering alo only (default)',
'call_number' => 'Show phone number',
'list_icon' => 'List icon',
]
],
'mobile_grid' => [
'type' => 'checkbox',
'description' => 'Display with grid style in mobile.',
],
'messenger_url' => [
'description' => 'The Nickname or ID account Facebook, Messenger. How to get your Facebook nickname? <a href="https://youtu.be/gVt1ob_zeQ8" target="_blank" rel="nofollow">Click here!</a>!',
],
'messenger_full_url' => [
'name' => 'Custom messenger URL',
'description' => 'You can setup custom URL for tracking referrer of customer click to your link. Recommendations setup via https://m.me/ an shorlink of Facebook or shorlink https://goo.gl of Google. If using https://m.me/, recommendations using Facebook account ID or Page ID, Example: https://m.me/1234567890?ref=website.',
],
'zalo_url' => [
'description' => 'For Vietnam only! Nhập số điện thoại hoặc ID Zalo OA của bạn. Ví dụ: 0987654321 hoặc 2490999280987654321',
],
'sms_bg' => [
'name' => 'SMS Background',
'type' => 'color',
],
'messenger_bg' => [
'name' => 'Messenger Background',
'type' => 'color',
],
'widget_width' => [
'type' => 'number',
'description' => 'Width (pixel) of button phonering alo. Set to zero if want hide this plugin.',
],
'mobile_width' => [
'name' => 'Show in',
'type' => 'select',
'option' => [
'0' => 'All device (Desktop, Table, Mobile)',
'775' => 'Table and Mobile',
'455' => 'Mobile only',
]
],
'widget_position' => [
'name' => 'Position',
'type' => 'select',
'option' => [
'bl' => 'Bottom Left',
'br' => 'Bottom Right',
'tr' => 'Top Right',
'tl' => 'Top Left',
'cr' => 'Center Right',
'cl' => 'Center Left',
]
],
'widget_tracking' => [
'type' => 'checkbox',
'description' => 'Using Google analytics tracking for click event in call button.',
],
'dynamic_style' => [
'type' => 'textarea',
],
'custom_style' => [
'type' => 'textarea',
'description' => 'The custom CSS for development.',
],
];
public $my_settings = [];
public $plugin_page = 'echbay-phonering-alo';
public $plugin_path = '';
public function __construct()
{
$this->plugin_path = plugin_dir_path(__FILE__);
$this->my_settings = $this->get_my_options();
add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_action_links'), 10, 2);
add_action('admin_menu', array($this, 'admin_menu'));
add_action('admin_init', array($this, 'register_my_settings')); }
public function add_action_links($links)
{
if (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false) {
$settings_link = '<a href="' . admin_url('options-general.php?page=' . $this->plugin_page) . '" title="Settings">Settings</a>';
array_unshift($links, $settings_link); }
return $links; }
public function admin_menu()
{
add_options_page(
EPA_THIS_PLUGIN_NAME,
EPA_THIS_PLUGIN_NAME,
'manage_options',
$this->plugin_page,
array(
$this,
'main_page'
)
); }
public function register_my_settings()
{
register_setting($this->optionGroup, $this->optionName); }
public function get_my_options()
{
$a = get_option($this->optionName);
if (empty($a)) {
global $wpdb;
$pref = $this->optionName;
$sql = $wpdb->get_results("SELECT option_name, option_value
FROM
`" . $wpdb->options . "`
WHERE
option_name LIKE '{$pref}%'
ORDER BY
option_id", OBJECT);
if (!empty($sql)) {
$a = [];
foreach ($sql as $v) {
$a[str_replace($this->optionName, '', $v->option_name)] = $v->option_value; }
if (isset($a['html_template']) && in_array($a['html_template'], [
'call_mes',
'call_sms_mes',
'call_sms_zalo_mes',
])) {
$a['html_template'] = 'list_icon'; }
update_option($this->optionName, $a); }
}
$result = wp_parse_args($a, $this->defaultOptions);
foreach ($result as $k => $v) {
if ($v == '' && isset($this->defaultOptions[$k]) && $v != $this->defaultOptions[$k]) {
$result[$k] = $this->defaultOptions[$k]; }
}
return $result; }
public function main_page()
{
include __DIR__ . '/includes/main_page.php'; }
public function get_url_static_file($f)
{
return str_replace(ABSPATH, get_home_url() . '/', $this->plugin_path) . $f . '?v=' . filemtime($this->plugin_path . $f); }
public function get_tmp($v)
{
return file_get_contents(__DIR__ . '/' . $v); }
public function replace_tmp($a)
{
if ($this->my_settings['messenger_full_url'] != '') {
$a = str_replace('{{messenger_url}}', $this->my_settings['messenger_full_url'], $a);
$a = str_replace('{{m_me}}', '', $a); }
foreach ($this->my_settings as $k2 => $v2) {
$a = str_replace('{{' . $k2 . '}}', $v2, $a); }
$a = str_replace('{{epa_plugin_version}}', EPA_DF_VERSION, $a);
$a = str_replace('{{epa_plugin_url}}', str_replace(ABSPATH, get_home_url() . '/', $this->plugin_path), $a);
if ($this->my_settings['dynamic_style'] != '' || $this->my_settings['custom_style'] != '') {
$a = str_replace('{{epa_custom_css}}', '<style>' . trim($this->my_settings['dynamic_style'] . $this->my_settings['custom_style']) . '</style>', $a);
} else {
$a = str_replace('{{epa_custom_css}}', '', $a); }
$a = str_replace('{{m_me}}', 'https://m.me/', $a);
return $a; }
public function guest()
{
if ($this->my_settings['html_template'] == 'call_number') {
$v = 'guest_call_number.html';
} else if ($this->my_settings['html_template'] == 'list_icon') {
$v = 'guest_call_sms_mes.html';
} else {
$v = 'guest.html'; }
$a = $this->get_tmp($v);
$a = $this->replace_tmp($a);
echo $a; }
}
function EPA_show_facebook_messenger_box_in_site()
{
$EPA_func = new EPA_Actions_Module();
echo '<!-- ' . EPA_THIS_PLUGIN_NAME . ' -->';
$EPA_func->guest();
echo '<!-- END ' . EPA_THIS_PLUGIN_NAME . ' -->'; }
if (is_admin()) {
$EPA_func = new EPA_Actions_Module(); }
else {
add_action('wp_footer', 'EPA_show_facebook_messenger_box_in_site'); }
}