<?php
defined('ABSPATH') or die('Invalid request.');
if (!class_exists('EPA_Actions_Module')) {
class EPA_Actions_Module
{
var $default_setting = array(
'license' => '',
'hide_powered' => 1,
'widget_width' => 45,
'mobile_width' => 0,
'header_bg' => '#0084FF',
'circle_ph' => '',
'circle_fill' => '',
'sms_bg' => '#ff6600',
'messenger_bg' => '#e60f1e',
'widget_position' => 'bl',
'custom_style' => '/* Custom CSS */',
'custom_icon' => '',
'html_template' => '',
'messenger_url' => '',
'messenger_full_url' => '',
'widget_tracking' => 'yes',
'phone_number' => ''
);
var $custom_setting = array();
var $eb_plugin_media_version = EPA_DF_VERSION;
var $gio_server = 0;
var $eb_plugin_prefix_option = '___epa___';
var $eb_plugin_root_dir = '';
var $eb_plugin_url = '';
var $eb_plugin_nonce = '';
var $eb_plugin_admin_dir = 'wp-admin';
var $web_link = '';
function load()
{
$this->eb_plugin_root_dir = basename(EPA_DF_DIR);
$this->eb_plugin_media_version = filemtime(EPA_DF_DIR . 'style.css');
$this->eb_plugin_url = plugins_url() . '/' . $this->eb_plugin_root_dir . '/';
$this->eb_plugin_nonce = $this->eb_plugin_root_dir . EPA_DF_VERSION;
if (defined('WP_ADMIN_DIR')) {
$this->eb_plugin_admin_dir = WP_ADMIN_DIR; }
$this->gio_server = current_time('timestamp');
$this->get_op(); }
function get_op()
{
global $wpdb;
$pref = $this->eb_plugin_prefix_option;
$sql = $wpdb->get_results("SELECT option_name, option_value
FROM
`" . $wpdb->options . "`
WHERE
option_name LIKE '{$pref}%'
ORDER BY
option_id", OBJECT);
print_r($sql);
foreach ($sql as $v) {
$this->custom_setting[str_replace($this->eb_plugin_prefix_option, '', $v->option_name)] = $v->option_value; }
print_r($this->custom_setting);
foreach ($this->default_setting as $k => $v) {
if (
!isset($this->custom_setting[$k]) ||
$this->custom_setting[$k] == ''
) {
$this->custom_setting[$k] = $v; }
}
foreach ($this->custom_setting as $k => $v) {
if ($k == 'custom_style') {
$v = esc_textarea($v);
} else {
$v = esc_html($v); }
$this->custom_setting[$k] = $v; }
}
function ck($v1, $v2, $e = ' checked')
{
if ($v1 == $v2) {
return $e; }
return ''; }
function get_web_link()
{
if ($this->web_link != '') {
return $this->web_link; }
$this->web_link = get_option('siteurl');
$this->web_link = explode('/', $this->web_link);
$this->web_link[2] = $_SERVER['HTTP_HOST'];
$this->web_link = implode('/', $this->web_link);
if (substr($this->web_link, -1) == '/') {
$this->web_link = substr($this->web_link, 0, -1); }
return $this->web_link; }
function update()
{
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['_ebnonce'])) {
if (!wp_verify_nonce($_POST['_ebnonce'], $this->eb_plugin_nonce)) {
wp_die('404 not found!'); }
if (!isset($_POST['_epa_widget_tracking'])) {
$_POST['_epa_widget_tracking'] = 'no'; }
foreach ($_POST as $k => $v) {
if (substr($k, 0, 5) == '_epa_') {
$key = $this->eb_plugin_prefix_option . substr($k, 5);
if (
$k == '_epa_widget_width' ||
$k == '_epa_mobile_width'
) {
$v = (int)$v; }
else {
$v = stripslashes(stripslashes(stripslashes($v)));
if ($k != '_epa_phone_number') {
$v = strip_tags($v);
$v = sanitize_text_field($v);
} else {
$v = urlencode($v); }
}
delete_option($key);
add_option($key, $v, '', 'no'); }
}
$file_path_cache = $this->cache();
if (file_exists($file_path_cache)) {
if (!unlink($file_path_cache)) {
echo 'Can\'t remove cache file!<br>'; }
echo 'Remove cache file!<br>'; }
die('<script type="text/javascript">
try {
if ( top != self && typeof top.a_lert == "function" ) {
top.a_lert("Update done!"); }
else {
alert("Update done!"); }
} catch (e) {
alert("Update done!"); }
</script>'); }
}
function admin()
{
$arr_position = array(
"tr" => 'Top Right',
"tl" => 'Top Left',
"cr" => 'Center Right',
"cl" => 'Center Left',
"br" => 'Bottom Right',
"bl" => 'Bottom Left'
);
$str_position = '';
foreach ($arr_position as $k => $v) {
$str_position .= '<option value="' . $k . '"' . $this->ck($this->custom_setting['widget_position'], $k, ' selected') . '>' . $v . '</option>'; }
$str_position = '<select name="_epa_widget_position" id="widget_position" class="postform">' . $str_position . '</select>';
$arr_mobile_width = array(
"0" => 'All device (Desktop, Table, Mobile)',
"775" => 'Table and Mobile',
"455" => 'Mobile'
);
$str_mobile_width = '';
foreach ($arr_mobile_width as $k => $v) {
$str_mobile_width .= '<option value="' . $k . '"' . $this->ck($this->custom_setting['mobile_width'], $k, ' selected') . '>' . $v . '</option>'; }
$str_mobile_width = '<select name="_epa_mobile_width" id="mobile_width" class="postform">' . $str_mobile_width . '</select>';
$arr_html_template = array(
'' => 'Phonering alo only (default)',
'call_number' => 'Phone show number',
'call_mes' => 'Phone and Messenger',
'call_sms_mes' => 'Phone, SMS and Messenger',
'call_sms_zalo_mes' => 'Phone, SMS, Messenger and Zalo'
);
$str_html_template = '';
foreach ($arr_html_template as $k => $v) {
$str_html_template .= '<option value="' . $k . '"' . $this->ck($this->custom_setting['html_template'], $k, ' selected') . '>' . $v . '</option>'; }
$str_html_template = '<select name="_epa_html_template" id="html_template" class="postform show-preview-after-change">' . $str_html_template . '</select>';
$this->eb_plugin_media_version = date('ymd.Hi', $this->gio_server);
$this->get_web_link();
$main = file_get_contents(EPA_DF_DIR . 'admin.html', 1);
if ($this->custom_setting['phone_number'] == '') {
$cf_dienthoai = get_option('_eb_cf_dienthoai', '');
if ($cf_dienthoai != '') {
$cf_dienthoai = explode("\n", $cf_dienthoai);
$this->custom_setting['phone_number'] = trim($cf_dienthoai[0]); }
}
if ($this->custom_setting['phone_number'] != '') {
$this->custom_setting['phone_number'] = urldecode($this->custom_setting['phone_number']);
$this->custom_setting['phone_number'] = esc_html($this->custom_setting['phone_number']); }
$main = $this->template($main, $this->custom_setting);
$main = $this->template($main, $this->default_setting, 'aaa');
$main = $this->template($main, array(
'_ebnonce' => wp_create_nonce($this->eb_plugin_nonce),
'epa_custom_css' => '<style>' . $this->css() . $this->css2() . '</style>',
'str_position' => $str_position,
'str_mobile_width' => $str_mobile_width,
'str_html_template' => $str_html_template,
'check_widget_tracking' => $this->ck($this->custom_setting['widget_tracking'], 'yes'),
'epa_plugin_url' => $this->eb_plugin_url,
'epa_plugin_version' => $this->eb_plugin_media_version,
));
echo $main;
echo '<p>* Other <a href="' . $this->web_link . '/' . $this->eb_plugin_admin_dir . '/plugin-install.php?s=itvn9online&tab=search&type=author" target="_blank">WordPress Plugins</a> written by the same author. Thanks for choose us!</p>'; }
function deline($str, $reg = "/\r\n|\n\r|\n|\r|\t/i", $re = "")
{
$a = explode("\n", $str);
$str = '';
foreach ($a as $v) {
$v = trim($v);
if ($v != '') {
if (strstr($v, '//') == true) {
$v .= "\n"; }
$str .= $v; }
}
return $str;
return preg_replace($reg, $re, $str); }
function guest()
{
if ($this->custom_setting['widget_width'] <= 0) {
echo '<!-- Plugin not show because widget_width has been set Zero -->';
return false; }
$dynamic_url = explode('/', $this->eb_plugin_url);
$dynamic_url[0] = '';
$dynamic_url[2] = $_SERVER['HTTP_HOST'];
$dynamic_url = implode('/', $dynamic_url);
echo '<link rel="stylesheet" href="' . $dynamic_url . 'style.css?v=' . $this->eb_plugin_media_version . '" type="text/css" />';
$file_path_cache = $this->cache();
if (
$file_path_cache != false
&&
$this->gio_server - $this->ftime($file_path_cache) + rand(0, 60) < 600
) {
if (function_exists('file_get_contents')) {
echo file_get_contents($file_path_cache, 1); }
else {
$handle = fopen($file_path_cache, "r");
if ($handle) {
echo fread($handle, filesize($file_path_cache));
fclose($handle); }
}
return true; }
if ($this->custom_setting['messenger_full_url'] != '') {
$this->custom_setting['messenger_url'] = $this->custom_setting['messenger_full_url'];
} else if ($this->custom_setting['messenger_url'] != '') {
$this->custom_setting['messenger_url'] = 'https://m.me/' . $this->custom_setting['messenger_url'];
$this->custom_setting['messenger_url'] .= '?ref=website&messaging_source=' . urlencode('source:pages:' . EPA_THIS_PLUGIN_NAME);
} else {
$this->custom_setting['messenger_url'] = 'javascript:;'; }
$this->custom_setting['phone_text'] = '';
if ($this->custom_setting['phone_number'] == '') {
$cf_dienthoai = get_option('_eb_cf_dienthoai', '');
if ($cf_dienthoai != '') {
$cf_dienthoai = explode("\n", $cf_dienthoai);
$this->custom_setting['phone_number'] = trim($cf_dienthoai[0]); }
}
if ($this->custom_setting['phone_number'] == '') {
$this->custom_setting['phone_number'] = 'javascript:;';
} else {
$this->custom_setting['phone_text'] = $this->custom_setting['phone_number'];
$this->custom_setting['phone_number'] = 'tel:' . str_replace(' ', '', trim(preg_replace('/[^0-9|\+]+/', '', strip_tags(urldecode($this->custom_setting['phone_number']))))); }
if (
$this->custom_setting['html_template'] == 'call_mes' ||
$this->custom_setting['html_template'] == 'call_sms_mes' ||
$this->custom_setting['html_template'] == 'call_sms_zalo_mes'
) {
$main = file_get_contents(EPA_DF_DIR . 'guest_call_sms_mes.html', 1);
$this->custom_setting['sms_number'] = str_replace('tel:', 'sms:', $this->custom_setting['phone_number']);
$epa_custom_css = $this->css2();
if ($this->custom_setting['html_template'] == 'call_sms_zalo_mes') {
$this->custom_setting['sms_zalo'] = str_replace('tel:', 'https://zalo.me/', $this->custom_setting['phone_number']);
$epa_custom_css .= '.echbay-sms-messenger div.phonering-alo-zalo{display:block}';
} else {
$this->custom_setting['sms_zalo'] = 'javascript:;';
if ($this->custom_setting['html_template'] == 'call_mes') {
$epa_custom_css .= '.echbay-sms-messenger div.phonering-alo-sms{display:none}'; }
}
} else if ($this->custom_setting['html_template'] == 'call_number') {
$this->custom_setting['phone_only_number'] = str_replace('tel:', '', $this->custom_setting['phone_number']);
$main = file_get_contents(EPA_DF_DIR . 'guest_call_number.html', 1);
$epa_custom_css = $this->css3(); }
else {
$main = file_get_contents(EPA_DF_DIR . 'guest.html', 1);
$epa_custom_css = $this->css(); }
$epa_custom_css = str_replace(';}', '}', $this->deline(trim($epa_custom_css)));
$epa_custom_css .= trim($this->custom_setting['custom_style']);
$main = $this->template($main, $this->custom_setting + array(
'bloginfo_name' => get_bloginfo('name'),
'epa_custom_css' => '<style type="text/css">' . $epa_custom_css . '</style>',
'epa_plugin_url' => $this->eb_plugin_url,
'epa_plugin_version' => $this->eb_plugin_media_version
));
if ($this->custom_setting['widget_tracking'] == 'yes') {
$main .= '<script type="text/javascript" src="' . $dynamic_url . 'js.js?v=' . $this->eb_plugin_media_version . '" defer></script>'; }
echo $main;
if (!file_exists($file_path_cache)) {
$filew = fopen($file_path_cache, 'x+');
if (!$filew) {
echo '<!-- ERROR create file cache: ' . $file_path_cache . ' -->';
return false; }
fclose($filew);
if (!chmod($file_path_cache, 0777)) {
echo '<!-- ERROR chmod file: ' . $file_path_cache . ' -->';
return false; }
}
file_put_contents($file_path_cache, '<!-- ' . $this->gio_server . ' | ' . date('r', $this->gio_server) . ' | ' . date('r', time()) . ' (Using ' . EPA_THIS_PLUGIN_NAME . ' in cache) -->' . $main);
echo '<!-- Create file cache: ' . $file_path_cache . ' (Time server: ' . date('r', $this->gio_server) . ' - Time: ' . date('r', time()) . ') -->'; }
function css2()
{
$epa_custom_css = '
.echbay-sms-messenger div.phonering-alo-zalo,
.echbay-sms-messenger div.phonering-alo-alo{background-color: ' . $this->custom_setting['header_bg'] . '}
.echbay-sms-messenger div.phonering-alo-sms{background-color: ' . $this->custom_setting['sms_bg'] . '}
.echbay-sms-messenger div.phonering-alo-messenger{background-color: ' . $this->custom_setting['messenger_bg'] . '}
.echbay-sms-messenger{width:' . $this->custom_setting['widget_width'] . 'px}
.echbay-sms-messenger a{line-height:' . $this->custom_setting['widget_width'] . 'px}
';
if ($this->custom_setting['mobile_width'] > 0) {
$epa_custom_css .= '
@media screen and (max-width:' . $this->custom_setting['mobile_width'] . 'px) {
.echbay-sms-messenger{display:block}
}
'; }
else {
$epa_custom_css .= '.echbay-sms-messenger{display:block}'; }
return $epa_custom_css; }
function css()
{
$max_center = $this->custom_setting['widget_width'];
$max_width_btn = $max_center * 3;
$medium_width_btn = $max_center * 2;
$medium_center = $max_center / 2;
$epa_custom_css = '
.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-circle{border-color: ' . $this->custom_setting['header_bg'] . '}
.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-circle-fill,
.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-img-circle{background-color: ' . $this->custom_setting['header_bg'] . '}
/*
.phonering-alo-phone.phonering-alo-green .phonering-alo-ph-img-circle a{color: ' . $this->custom_setting['header_bg'] . '}
*/
.phonering-alo-ph-img-circle {
width: ' . $this->custom_setting['widget_width'] . 'px;
height: ' . $this->custom_setting['widget_width'] . 'px;
top: ' . $max_center . 'px;
left: ' . $max_center . 'px; }
.phonering-alo-ph-img-circle a {
width: ' . $this->custom_setting['widget_width'] . 'px;
line-height: ' . $this->custom_setting['widget_width'] . 'px; }
.phonering-alo-ph-circle-fill {
width: ' . $medium_width_btn . 'px;
height: ' . $medium_width_btn . 'px;
top: ' . $medium_center . 'px;
left: ' . $medium_center . 'px; }
.echbay-alo-phone,
.phonering-alo-ph-circle {
width: ' . $max_width_btn . 'px;
height: ' . $max_width_btn . 'px; }
.style-for-position-cr,
.style-for-position-cl { margin-top: -' . ($max_width_btn / 2) . 'px; }
/* for mobile */
@media screen and (max-width:' . $this->custom_setting['mobile_width'] . 'px) {
.style-for-position-bl {
left: -' . $max_center . 'px;
bottom: -' . $max_center . 'px; }
.style-for-position-br {
right: -' . $max_center . 'px;
bottom: -' . $max_center . 'px; }
.style-for-position-cl { left: -' . $max_center . 'px; }
.style-for-position-cr { right: -' . $max_center . 'px; }
.style-for-position-tl {
top: -' . $max_center . 'px;
left: -' . $max_center . 'px; }
.style-for-position-tr {
top: -' . $max_center . 'px;
right: -' . $max_center . 'px; }
}
';
if ($this->custom_setting['mobile_width'] > 0) {
$epa_custom_css .= '
@media screen and (max-width:' . $this->custom_setting['mobile_width'] . 'px) {
.echbay-alo-phone{display:block}
}
'; }
else {
$epa_custom_css .= '.echbay-alo-phone{display:block}'; }
if ($this->custom_setting['custom_icon'] != '') {
$epa_custom_css .= '
.phonering-alo-ph-img-circle{background-image:none !important}
.phonering-alo-ph-img-circle a {
text-indent: 0;
text-align: center;
font-size: 1px; }
'; }
return $epa_custom_css; }
function css3()
{
$max_center = $this->custom_setting['widget_width'];
$max_width_btn = $max_center * 3;
$medium_width_btn = $max_center * 2;
$medium_center = $max_center / 2;
$epa_custom_css = '
.echbay-phone-number a,
.echbay-phone-number a span{background-color: ' . $this->custom_setting['header_bg'] . '}
';
if ($this->custom_setting['mobile_width'] > 0) {
$epa_custom_css .= '
@media screen and (max-width:' . $this->custom_setting['mobile_width'] . 'px) {
.echbay-alo-phone{display:block}
}
'; }
else {
$epa_custom_css .= '.echbay-alo-phone{display:block}'; }
return $epa_custom_css; }
function cache()
{
$arr = wp_upload_dir();
$base_dir = $arr['basedir'];
$cache_dir = $base_dir . '/ebcache';
$cache_file_name = str_replace(' ', '-', EPA_THIS_PLUGIN_NAME) . '.txt';
$cache_file_path = $cache_dir . '/' . $cache_file_name;
if (!is_dir($cache_dir)) {
if (!mkdir($cache_dir, 0777)) {
echo '<!-- ERROR create dir cache: ' . $cache_dir . ' -->';
return false; }
if (!chmod($cache_dir, 0777)) {
echo 'ERROR chmod dir: ' . $cache_dir;
return false; }
}
return $cache_file_path; }
function ftime($f)
{
if (!file_exists($f)) {
return 0; }
$c = file_get_contents($f, 1);
$c = explode('|', $c);
$c = trim(str_replace('<!--', '', str_replace('/*', '', $c[0])));
return $c; }
function template($temp, $val = array(), $tmp = 'tmp')
{
foreach ($val as $k => $v) {
$temp = str_replace('{' . $tmp . '.' . $k . '}', $v, $temp); }
return $temp; }
}
}
function EPA_show_setting_form_in_admin()
{
global $EPA_func;
$EPA_func->update();
$EPA_func->admin(); }
function EPA_add_menu_setting_to_admin_menu()
{
if (!current_user_can('manage_options')) {
return false; }
$a = EPA_THIS_PLUGIN_NAME;
if (EPA_ADD_TO_SUB_MENU == false) {
add_menu_page($a, EBP_GLOBAL_PLUGINS_MENU_NAME, 'manage_options', EBP_GLOBAL_PLUGINS_SLUG_NAME, 'EPA_show_setting_form_in_admin', NULL, 99); }
add_submenu_page(EBP_GLOBAL_PLUGINS_SLUG_NAME, $a, trim(str_replace('EchBay', '', $a)), 'manage_options', strtolower(str_replace(' ', '-', $a)), 'EPA_show_setting_form_in_admin'); }
function EPA_show_facebook_messenger_box_in_site()
{
global $EPA_func;
echo '<!-- ' . EPA_THIS_PLUGIN_NAME . ' -->';
$EPA_func->guest();
echo '<!-- END ' . EPA_THIS_PLUGIN_NAME . ' -->'; }
function EPA_plugin_settings_link($links)
{
$settings_link = '<a href="admin.php?page=' . strtolower(str_replace(' ', '-', EPA_THIS_PLUGIN_NAME)) . '">Settings</a>';
array_unshift($links, $settings_link);
return $links; }
$EPA_func = new EPA_Actions_Module();
$EPA_func->load();
if (is_admin()) {
add_action('admin_menu', 'EPA_add_menu_setting_to_admin_menu');
if (strpos($_SERVER['REQUEST_URI'], '/plugins.php') !== false) {
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'EPA_plugin_settings_link'); }
}
else {
add_action('wp_footer', 'EPA_show_facebook_messenger_box_in_site'); }