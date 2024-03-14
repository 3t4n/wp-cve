<?php
class TrustindexTestimonialsPlugin
{
private $plugin_file_path;
private $plugin_name;
private $platform_name;
public $shortname;
private $version;
public function __construct($shortname, $plugin_file_path, $version, $plugin_name)
{
$this->shortname = $shortname;
$this->plugin_file_path = $plugin_file_path;
$this->version = $version;
$this->plugin_name = $plugin_name;
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once plugin_dir_path(__FILE__) . 'functions.php';
require_once plugin_dir_path(__FILE__) . 'functions-rating.php';
require_once plugin_dir_path(__FILE__) . 'post-types.php';
require_once plugin_dir_path(__FILE__) . 'post-editor.class.php';
}
public function getShortName()
{
return $this->shortname;
}
public function get_plugin_dir()
{
return plugin_dir_path($this->plugin_file_path);
}
public function get_plugin_file_url($file, $add_versioning = true)
{
$url = plugins_url($file, $this->plugin_file_path);
if ($add_versioning) {
$append_mark = strpos($url, "?") === FALSE ? "?" : "&";
$url .= $append_mark . 'ver=' . $this->version;
}
return $url;
}
public function get_plugin_slug()
{
return basename($this->get_plugin_dir());
}
public function get_full_star()
{
return $this->get_plugin_file_url('static/svg/f.svg');
}
public function get_empty_star()
{
return $this->get_plugin_file_url('static/svg/e.svg');
}
public static $thumbnail_sizes = array(
60,
80,
100,
120,
140,
200,
360,
390,
460,
554
);
public function set_testimonial_image()
{
add_theme_support( 'post-thumbnails');
foreach (TrustindexTestimonialsPlugin::$thumbnail_sizes as $thumbnail_size)
{
add_image_size('wpt-testimonial-thumbnail-' . $thumbnail_size, $thumbnail_size, $thumbnail_size, true);
}
}

public function get_menu_data()
{
$menu_data = array(
"menu" => array(
TrustindexTestimonialsPlugin::___('Setup Guide') => array(
"url" => $this->get_plugin_slug() . "/tabs/setup-guide.php",
"name" => "setup-guide",
"title" => "WP Testimonials - Setup Guide",
"is_custom" => true,
"hide" => false,
),
TrustindexTestimonialsPlugin::___('My Testimonials') => array(
"url" => "edit.php?post_type=wpt-testimonial",
"title" => "WP Testimonials - My Testimonials",
"name" => "my-reviews",
"is_custom" => false,
"hide" => false,
),
TrustindexTestimonialsPlugin::___('Create Widgets') => array(
"url" => $this->get_plugin_slug() . "/tabs/index-widget.php",
"title" => "WP Testimonials - Create Widgets",
"name" => "index-widget",
"is_custom" => true,
"hide" => false,
),
TrustindexTestimonialsPlugin::___('hidden Create Widget') => array(
"url" => $this->get_plugin_slug() . "/tabs/create-widget.php",
"title" => "WP Testimonials - Create Widgets",
"name" => "create-widget",
"is_custom" => true,
"hide" => true,
"parent" => "index-widget",
),
TrustindexTestimonialsPlugin::___('Categories') => array(
"url" => "edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial",
"name" => "categories",
"title" => "WP Testimonials - Categories",
"is_custom" => false,
"hide" => false,
),
TrustindexTestimonialsPlugin::___('Rate Us') => array(
"url" => $this->get_plugin_slug() . "/tabs/rate-us.php",
"name" => "rate-us",
"title" => "WP Testimonials - Rate Us",
"is_custom" => true,
"hide" => false,
)
),
"home_page" => $this->get_plugin_slug() . "/tabs/setup-guide.php",
"default_tab" => $this->get_plugin_slug() . "/tabs/setup-guide.php",
);
return $menu_data;
}
public function add_setting_menu()
{
add_menu_page(
$this->plugin_name,
$this->plugin_name,
'manage_options',
$this->get_plugin_slug() . "/tabs/setup-guide.php",
'',
$this->get_plugin_file_url('static/img/trustindex-sign-logo.png')
);
$permission = 'manage_options';
$menu = $this->get_menu_data();
foreach ($menu["menu"] as $k => $v) {
add_submenu_page(
$v["hide"] ? null : $menu["home_page"],
$v["title"],
$k,
$permission,
$v["url"]
);
}
}
public function menu_highlight($parent_file)
{
global $plugin_page, $submenu_file, $post_type, $taxonomy, $pagenow;
if ('wpt-testimonial' == $post_type) {
if ($taxonomy == 'wpt-testimonial-category') {
$plugin_page = 'edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial';
$submenu_file = 'edit-tags.php?taxonomy=wpt-testimonial-category&post_type=wpt-testimonial';
}
else if ($pagenow === 'post.php' || $pagenow === 'post-new.php')
{
$plugin_page = 'edit.php?post_type=wpt-testimonial';
$submenu_file = 'edit.php?post_type=wpt-testimonial';
}
else if (isset($_GET['page']))
{
$page = sanitize_text_field($_GET['page']);
$selected_tab = substr($page, strrpos($page, '/') + 1);
$selected_tab = str_replace(".php", "", $selected_tab);
if ($selected_tab === 'create-widget')
{
$plugin_page = $this->get_plugin_slug() . "/tabs/index-widget.php";
$submenu_file = $this->get_plugin_slug() . "/tabs/index-widget.php";
}
}
}
return $parent_file;
}
public function generate_cpt_page_menu()
{
$screen = get_current_screen();
if ('edit-wpt-testimonial' === $screen->id || 'wpt-testimonial' === $screen->id)
{
add_action( 'all_admin_notices', function(){
$menu = $this->get_menu_data();
$selected_tab = 'my-reviews';
ob_start(); ?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo esc_attr($this->plugin_name); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right"><img src="<?php echo esc_url($this->get_plugin_file_url('static/img/trustindex.svg')); ?>" /></a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tab_name => $tab) : ?>
<?php $is_active = $selected_tab == $tab["name"];
$action = $tab["name"];
$is_right = $tab["name"] == "rate-us";
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($is_active) : ?> nav-tab-active<?php endif; ?> <?php if ($is_right) : ?> nav-tab-right<?php endif; ?>" href="<?php echo $tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]; ?>"><?php echo esc_html($tab_name); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowed_html = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowed_html);
});
}
else if ('edit-wpt-testimonial-category' === $screen->id)
{
add_action( 'all_admin_notices', function(){
$menu = $this->get_menu_data();
$selected_tab = 'categories';
ob_start(); ?>
<div id="testimonial-widgets-plugin-settings-page" class="ti-toggle-opacity">
<h1 class="ti-free-title">
<?php echo esc_attr($this->plugin_name); ?>
<a href="https://www.trustindex.io" target="_blank" title="Trustindex" class="ti-pull-right"><img src="<?php echo esc_url($this->get_plugin_file_url('static/img/trustindex.svg')); ?>" /></a>
</h1>
<div class="container_wrapper">
<div class="container_cell" id="container-main">
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tab_name => $tab) : ?>
<?php $is_active = $selected_tab == $tab["name"];
$action = $tab["name"];
$is_right = $tab["name"] == "rate-us";
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($is_active) : ?> nav-tab-active<?php endif; ?> <?php if ($is_right) : ?> nav-tab-right<?php endif; ?>" href="<?php echo $tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]; ?>"><?php echo esc_html($tab_name); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowed_html = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowed_html);
});
}
}
public function generate_page_menu()
{
$menu = $this->get_menu_data();
$default_tab = $menu['default_tab'];
$page = sanitize_text_field($_GET['page']);
$selected_tab = substr($page, strrpos($page, '/') + 1);
$selected_tab = str_replace(".php", "", $selected_tab);
$found = false;
foreach ($menu["menu"] as $tab) {
if ($tab["hide"] && $selected_tab == $tab["name"]) {
$selected_tab = $tab["parent"];
$found = true;
break;
}
if ($selected_tab == $tab["name"]) {
$found = true;
break;
}
}
if (!$found) {
$selected_tab = $default_tab;
}
ob_start(); ?>
<div class="nav-tab-wrapper">
<?php foreach ($menu["menu"] as $tab_name => $tab) : ?>
<?php
$is_active = $selected_tab == $tab["name"];
$is_right = $tab["name"] == "rate-us";
$action = $tab["name"];
?>
<?php if ($tab["hide"] == false) : ?>
<a id="link-tab-<?php echo esc_attr($action); ?>" class="nav-tab<?php if ($is_active) : ?> nav-tab-active<?php endif; ?> <?php if ($is_right) : ?> nav-tab-right<?php endif; ?>" href="<?php echo $tab["is_custom"] ? admin_url('admin.php?page=' . $tab["url"]) : $tab["url"]; ?>"><?php echo esc_html($tab_name); ?></a>
<?php endif; ?>
<?php endforeach; ?>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$allowed_html = array(
'div' => array(
'id' => array(),
'class' => array()
),
'h1' => array(
'class' => array()
),
'a' => array(
'id' => array(),
'class' => array(),
'href' => array(),
),
'img' => array(
'src' => array()
)
);
echo wp_kses($html, $allowed_html);
}

public function add_plugin_action_links($links, $file)
{
$plugin_file = basename($this->plugin_file_path);
if (basename($file) == $plugin_file) {
$new_item2 = '<a target="_blank" href="https://www.trustindex.io" target="_blank">by <span style="background-color: #4067af; color: white; font-weight: bold; padding: 1px 8px;">Trustindex.io</span></a>';
$new_item1 = '<a href="' . admin_url('admin.php?page=' . $this->get_plugin_slug() . '/tabs/setup-guide.php') . '">' . TrustindexTestimonialsPlugin::___('Settings') . '</a>';
array_unshift($links, $new_item2, $new_item1);
}
return $links;
}
public function add_plugin_meta_links($meta, $file)
{
$plugin_file = basename($this->plugin_file_path);
if (basename($file) == $plugin_file) {
$meta[] = "<a href='http://wordpress.org/support/view/plugin-reviews/" . $this->get_plugin_slug() . "' target='_blank' rel='noopener noreferrer' title='" . TrustindexTestimonialsPlugin::___('Rate our plugin') . ': ' . $this->plugin_name . "'>" . TrustindexTestimonialsPlugin::___('Rate our plugin') . '</a>';
}
return $meta;
}

/* I18N
* make sure you do not use any translatable string function calls before the call to our ‘load_plugin_textdomain’
*/
public function loadI18N()
{
load_plugin_textdomain('testimonial-widgets', false, $this->get_plugin_slug() . '/languages');
}
public static function ___($text, $params = null)
{
if (!is_array($params)) {
$params = func_get_args();
$params = array_slice($params, 1);
}
return vsprintf(__($text, 'testimonial-widgets'), $params);
}

public function trustindex_testimonials_add_scripts($hook)
{
$wp_page = false;
$post_type = isset($_GET['post_type']) ? sanitize_text_field($_GET['post_type']) : null;
$taxonomy = isset($_GET['taxonomy']) ? sanitize_text_field($_GET['taxonomy']) : null;
$page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;
global $pagenow;
if ($pagenow === 'post.php' || $pagenow === 'post-new.php' || $pagenow === 'edit.php' || $pagenow === 'edit-tags.php' || $pagenow === 'term.php')
{
if (file_exists($this->get_plugin_dir() . 'static/js/admin-testimonial-edit.js')) {
wp_enqueue_script('trustindex_testimonials_edit_script' . $this->shortname, $this->get_plugin_file_url('static/js/admin-testimonial-edit.js'));
}
}
if ($post_type && $post_type == "wpt-testimonial") {
if ($pagenow === 'post-new.php' || $pagenow === 'post.php')
{
if (file_exists($this->get_plugin_dir() . 'static/css/admin-page-menu-for-post.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->get_plugin_file_url('static/css/admin-page-menu-for-post.css'));
}
}
else if ($pagenow === 'edit.php')
{
$wp_page = true;
if (file_exists($this->get_plugin_dir() . 'static/js/admin-testimonial-index.js')) {
wp_enqueue_script('trustindex_testimonials_index_script' . $this->shortname, $this->get_plugin_file_url('static/js/admin-testimonial-index.js'));
}
if (file_exists($this->get_plugin_dir() . 'static/css/admin-page-default-wp-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname . '_default_wp', $this->get_plugin_file_url('static/css/admin-page-default-wp-settings.css'));
}
}
else if ($taxonomy && (($pagenow === 'edit-tags.php' && $taxonomy === "wpt-testimonial-category") || ($pagenow === 'term.php' && $taxonomy === "wpt-testimonial-category")))
{
$wp_page = true;
if (file_exists($this->get_plugin_dir() . 'static/css/admin-page-default-wp-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname . '_default_wp', $this->get_plugin_file_url('static/css/admin-page-default-wp-settings.css'));
}
}
}
else if ($pagenow === 'post.php') {
if (file_exists($this->get_plugin_dir() . 'static/css/admin-page-menu-for-post.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->get_plugin_file_url('static/css/admin-page-menu-for-post.css'));
}
}
if ($wp_page == false)
{
$tmp = explode('/', $this->plugin_file_path);
$plugin_slug = preg_replace('/\.php$/', '', array_pop($tmp));
$tmp = explode('/', $hook);
$current_slug = array_shift($tmp);
}
if ($wp_page || $plugin_slug == $current_slug) {
if (file_exists($this->get_plugin_dir() . 'static/css/admin-page-settings.css')) {
wp_enqueue_style('trustindex_testimonials_settings_style_' . $this->shortname, $this->get_plugin_file_url('static/css/admin-page-settings.css'));
}
if (file_exists($this->get_plugin_dir() . 'static/js/admin-page-settings-common.js')) {
wp_enqueue_script('trustindex_testimonials_settings_script_common_' . $this->shortname, $this->get_plugin_file_url('static/js/admin-page-settings-common.js'));
}
}
if ($page)
{
$selected_tab = substr($page, strrpos($page, '/') + 1);
$selected_tab = str_replace(".php", "", $selected_tab);
if ($selected_tab === 'create-widget')
{
if (file_exists($this->get_plugin_dir() . 'static/js/admin-page-create-widget.js')) {
wp_enqueue_script('trustindex_testimonials_create_widget_script_' . $this->shortname, $this->get_plugin_file_url('static/js/admin-page-create-widget.js'));
}
}
if ($selected_tab === 'setup-guide')
{
if (file_exists($this->get_plugin_dir() . 'static/css/setup-guide.css')) {
wp_enqueue_style('trustindex_testimonials_setup-guide_style_' . $this->shortname, $this->get_plugin_file_url('static/css/setup-guide.css'));
}
}
}

wp_register_style('wpttst-post-editor', $this->get_plugin_file_url('static/css/post-editor.css'));
wp_enqueue_style('wpttst-post-editor');

wp_register_style('wpttst-rating-display', $this->get_plugin_file_url('static/css/rating-display.css'));
wp_register_style('wpttst-rating-form', $this->get_plugin_file_url('static/css/rating-form.css'));
wp_register_script('wpttst-rating-script', $this->get_plugin_file_url('static/js/rating-edit.js'), array('jquery'), null, true);
wp_enqueue_style('wpttst-rating-form');
wp_enqueue_style('wpttst-rating-display');
wp_enqueue_script('wpttst-rating-script');
}

private $preview_content = null;
private $template_cache = null;
public function get_noreg_list_reviews(int $widget_id, $list_all = false, $default_style_id = 4, $default_set_id = 'light-background', $only_preview = false)
{
$w = $this->get_widget($widget_id);
$content = $w->review_content;
$widget = unserialize($w->value);
$filter = array();
$filter['mode'] = $widget['1']['mode'];
$filter['rating'] = $widget['1']['rating'];
if ($filter['mode'] === 'category')
{
$filter['category'] = $widget['1']['selected'];
}
else if ($filter['mode'] === 'manual_select')
{
$filter['ids'] = explode(',', $widget['1']['selected']);
$filter['rating'] = 'all';
}
$filter['order'] = $widget['4']['general']['order'];
$reviews = $this->get_reviews($filter);
if (count($reviews) == 0)
{
$html = $this->get_alertbox('warning', '<br />' . TrustindexTestimonialsPlugin::___('You do not have reviews with the current filters. <br /> Change your filters if you would like to display reviews on your page!'));
$allowed_html = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowed_html);
}
$settings = array(
'avatar_url' => 'https://cdn.trustindex.io/' . 'companies/default_avatar.jpg',
'rating_number' => 3,
'rating_score' => rand(4, 5),
'style_id' => isset($widget['2']) ? $widget['2'] : $default_style_id,
'set_id' => isset($widget['3']) ? $widget['3'] : $default_set_id,
'display_type' => $widget['4']['appearance']['display_type'],
'date_format' => $widget['4']['appearance']['date_format'],
'show_logos' => 0,
'show_stars' => $widget['4']['appearance']['hide_stars'],
'show_reviewers_photo' => !$widget['4']['appearance']['hide_image'],
'auto_height' => $widget['4']['appearance']['auto_height'],
'slider_interval' => isset($widget['4']['navigation']['slider_interval']) ? $widget['4']['navigation']['slider_interval'] : 6,
'no_rating_text' => 1,
'language' => 'en',
);
$need_to_parse = true;
if ($only_preview) {
$content = false;
$settings['style_id'] = $default_style_id;
$settings['set_id'] = $default_set_id;
$settings['show_logos'] = 0;
$settings['show_stars'] = 0;
$settings['show_reviewers_photo'] = 1;
$settings['auto_height'] = 0;
$settings['slider_interval'] = 6;
}
if (is_null($settings['no_rating_text']))
{
$settings['no_rating_text'] = in_array($settings['style_id'], [ 15, 19, 36, 38, 39, 44 ]) ? 1 : 0;
if(self::$widget_styles[$settings['set_id']]['_vars']['dots'] === 'true')
{
$settings['no_rating_text'] = 1;
}
}
$script_name = 'trustindex-js';
if (!wp_script_is($script_name, 'enqueued')) {
wp_enqueue_script($script_name, 'https://cdn.trustindex.io/' . 'loader.js', [], false, true);
}
$scripts = wp_scripts();
if (isset($scripts->registered[$script_name]) && !isset($scripts->registered[$script_name]->extra['after'])) {
wp_add_inline_script($script_name, '(function ti_init() {
if(typeof Trustindex == "undefined"){setTimeout(ti_init, 1985);return false;}
if(typeof Trustindex.pager_inited != "undefined"){return false;}
Trustindex.init_pager(document.querySelectorAll(".ti-widget"));
})();');
}
$start = strpos($w->review_content, 'data-layout-id="');
$length = strpos($w->review_content, '" data-set-id') - $start;
$layout_id = substr($w->review_content, $start, $length);
if ($content === false || empty($content) || $layout_id != $settings['style_id'] || (strpos($content, '<!-- R-LIST -->') === false && $need_to_parse)) {
if (!$this->template_cache) {
add_action('http_api_curl', function ($handle) {
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
}, 10);
$response = wp_remote_get("https://cdn.trustindex.io/" . "widget-assets/template/{$settings['language']}.json");
if (is_wp_error($response)) {
$html = $this->get_alertbox('error', '<br />' . $this->___('Could not download the template for the widget.<br />Please reload the page.<br />If the problem persists, please write an e-mail to support@trustindex.io.'));
$allowed_html = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowed_html);
die;
}
$this->template_cache = json_decode($response['body'], true);
}
$content = $this->template_cache[$settings['style_id']];
$this->update_widget_review_content($widget_id, $content);
}
if ($need_to_parse) {
$content = $this->parse_noreg_list_reviews([
'content' => $content,
'reviews' => $reviews,
'settings' => $settings,
]);
$this->preview_content = [
'id' => $settings['style_id'],
'content' => $content
];
}
if ($settings['slider_interval']) {
$content = preg_replace('/data-pager-autoplay-timeout=[\'"][^\'"]*[\'"]/m', 'data-pager-autoplay-timeout="' . $settings['slider_interval'] . '"', $content);
}
$content = preg_replace('/data-set[_-]id=[\'"][^\'"]*[\'"]/m', 'data-set-id="' . $settings['set_id'] . '"', $content);
$class_appends = ['ti-wp-testimonial-' . $w->id, 'ti-no-logo'];
if ($settings['show_logos']) {
array_push($class_appends, 'ti-no-logo');
}
if ($settings['show_stars']) {
array_push($class_appends, 'ti-no-stars');
}
if ($settings['auto_height']) {
array_push($class_appends, 'ti-auto-height');
}
if ($w->css && !$only_preview)
{
wp_register_style("testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], false );
wp_enqueue_style( "testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'] );
wp_add_inline_style("testimonial-widgets-css-live-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $w->css );
}
else
{
wp_enqueue_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], "https://cdn.trustindex.io/" . "assets/widget-presetted-css/" . $settings['style_id'] . "-" . $settings['set_id'] . ".css");
if (in_array($settings['style_id'], array(36,37,38,39)))
{
$content = str_replace('class="ti-widget"', 'id="override-preset-'. $settings['style_id'] .'" class="ti-widget"', $content);
$inline_style = "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img img {
width: 120px !important;
height: 120px !important;
border-radius: 60px;
}";
if ($settings['style_id'] == 36 || $settings['style_id'] == 37 || $settings['style_id'] == 38)
{
$inline_style = $inline_style . "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin: 0 !important;
margin-top: -75px !important;
margin-bottom: 15px !important;
align-self: center;
}";
if ($settings['style_id'] == 38)
{
$inline_style = $inline_style . "#override-preset-". $settings['style_id'] ." .ti-review-item {
padding-top: 75px;
}";
}
else
{
$inline_style = $inline_style . "#override-preset-". $settings['style_id'] ." .ti-reviews-container-wrapper {
padding-top: 75px;
}";
}
}
else if ($settings['style_id'] == 39)
{
$inline_style = $inline_style . "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin: 20px 0 0 !important;
margin-bottom: 10px !important;
align-self: center;
}
#override-preset .ti-reviews-container-wrapper {
padding-top: 8px;
}";
}
wp_add_inline_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $inline_style);
}
else if ($settings['style_id'] == 44)
{
$content = str_replace('class="ti-widget"', 'id="override-preset-'. $settings['style_id'] .'" class="ti-widget"', $content);
$inline_style = "#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img img {
width: 100px !important;
height: 100px !important;
border-radius: 50px;
}
#override-preset-". $settings['style_id'] ." .ti-review-header .ti-profile-img {
margin-left: -65px !important;
}
#override-preset-". $settings['style_id'] ." .ti-review-item>.ti-inner {
padding-right: 40px !important;
padding-bottom: 40px !important;
}
#override-preset-". $settings['style_id'] ." .ti-review-item {
padding-left: 58px;
}
";
wp_add_inline_style("testimonial-widgets-css-" . $w->id . "-" . $settings['style_id'] . "-" . $settings['set_id'], $inline_style);
}
}
if ($class_appends) {
$content = str_replace('class="ti-widget" data-layout-id=', 'class="ti-widget ' . implode(' ', $class_appends) . '" data-layout-id=', $content);
}
return $content;
}
public function parse_noreg_list_reviews($array = [])
{
preg_match('/<!-- R-LIST -->(.*)<!-- R-LIST -->/', $array['content'], $matches);
if (isset($matches[1])) {
$reviewContent = "";
if ($array['reviews'] && count($array['reviews'])) foreach ($array['reviews'] as $r) {
$date = "&nbsp;";
if ($r['date'] && $r['date'] != '0000-00-00') {
$date = str_replace(self::$widget_month_names['en'], self::$widget_month_names[$array['settings']['language']], date($array['settings']['date_format'], strtotime($r['date'])));
}
if ($r['company_website'])
{
$input = trim($r['company_website'], '/');
if (!preg_match('#^http(s)?://#', $input)) {
$input = 'http://' . $input;
}
$urlParts = parse_url($input);
$domain = preg_replace('/^www\./', '', $urlParts['host']);
if ($r['company_name'])
{
$info = '<a href="http://www.' . $domain . '" target="_blank">' . $r['company_name'] . '</a>';
}
else
{
$info = '<a href="http://www.' . $domain . '" target="_blank">' . $domain . '</a>';
}
}
else if ($r['company_name'])
{
$info = $r['company_name'];
}
else
{
$info = $date;
}
$rating_content = $this->get_rating_stars($r['star_rating']);
$platform_name = ucfirst($this->getShortName());
if (!$array['settings']['show_reviewers_photo']) {
if (in_array($array['settings']['style_id'], [45, 46, 47, 48]))
{
$matches[1] = str_replace('<div class="ti-profile-img-square"> <img src="%reviewer_photo%" alt="%reviewer_name%" /> </div>', '', $matches[1]);
}
else
{
$matches[1] = str_replace('<div class="ti-profile-img"> <img src="%reviewer_photo%" alt="%reviewer_name%" /> </div>', '', $matches[1]);
}
}
if ($r['photo'][TrustindexTestimonialsPlugin::$widget_templates['templates'][$array['settings']['style_id']]['image']])
{
$reviewer_photo = $r['photo'][TrustindexTestimonialsPlugin::$widget_templates['templates'][$array['settings']['style_id']]['image']];
}
else
{
$reviewer_photo = 'https://cdn.trustindex.io/' . 'assets/default-avatar/noprofile-06.svg';
}
$reviewContent .= str_replace([
'%platform%',
'%reviewer_photo%',
'%reviewer_name%',
'%created_at%',
'%text%',
'<span class="ti-star f"></span><span class="ti-star f"></span><span class="ti-star f"></span><span class="ti-star f"></span><span class="ti-star f"></span>'
], [
$platform_name,
$reviewer_photo,
$r['client_name'],
$info,
preg_replace('/\r\n|\r|\n/', "\n", html_entity_decode($r['content'], ENT_HTML5 | ENT_QUOTES)),
$rating_content
], $matches[1]);
$reviewContent = str_replace('<div></div>', '', $reviewContent);
}
$array['content'] = str_replace($matches[0], $reviewContent, $array['content']);
}
if ($array['settings']['no_rating_text']) {
if (in_array($array['settings']['style_id'], [6, 7])) {
$array['content'] = preg_replace('/<div class="ti-footer">.*<\/div>/mU', '<div class="ti-footer"></div>', $array['content']);
} else if (in_array($array['settings']['style_id'], [31, 33])) {
$array['content'] = preg_replace('/<div class="ti-header source-.*<\/div>\s?<div class="ti-reviews-container">/mU', '<div class="ti-reviews-container">', $array['content']);
} else if (in_array($array['settings']['style_id'], [10])) {
$array['content'] = preg_replace('/<div class="ti-header source-.*<\/div>\s?<div class="ti-reviews-container-wrapper">/mU', '<div class="ti-reviews-container">', $array['content']);
}
else if ($array['settings']['style_id'] == 11) {
$array['content'] = preg_replace('/<div class="ti-text">.*<\/div>/mU', '', $array['content']);
} else {
$array['content'] = preg_replace('/<div class="ti-rating-text">.*<\/div>/mU', '', $array['content']);
}
}
if ($array['settings']['auto_height'] && $array['settings']['style_id'] == 33) {
$array['content'] = preg_replace('/<a href="" class="ti-read-more".*<\/a>/mU', '', $array['content']);
}
preg_match('/src="([^"]+logo[^\.]*\.svg)"/m', $array['content'], $matches);
if (isset($matches[1]) && !empty($matches[1])) {
$array['content'] = str_replace($matches[0], $matches[0] . ' width="150" height="25"', $array['content']);
}
return $array['content'];
}
public function get_shortcode_name()
{
return 'wp-testimonials';
}
public function init_shortcode()
{
if (!shortcode_exists($this->get_shortcode_name())) {
add_shortcode($this->get_shortcode_name(), [$this, 'shortcode_func']);
}
}
public function shortcode_func($atts)
{
$atts = shortcode_atts(
array(
'widget-id' => null
),
$atts
);
if (isset($atts["widget-id"]) && $atts["widget-id"]) {
$widget = $this->get_widget($atts["widget-id"]);
if ($widget)
{
$val = unserialize($widget->value);
return $this->get_noreg_list_reviews($widget->id, true, $val['2'], $val['3']);
}
else
{
return self::get_alertbox(
"error",
" @ <strong>" . TrustindexTestimonialsPlugin::___('%s plugin', [esc_attr($this->plugin_name)]) . "</strong><br /><br />"
. TrustindexTestimonialsPlugin::___('Widget ID %s does not exists!', [$atts["widget-id"]]),
false
);
}
}
else
{
return self::get_alertbox(
"error",
" @ <strong>" . TrustindexTestimonialsPlugin::___('%s plugin', [esc_attr($this->plugin_name)]) . "</strong><br /><br />"
. TrustindexTestimonialsPlugin::___('Your shortcode is deficient: %s ID is empty! Example: ', [esc_attr($this->plugin_name)]) . '<br /><code>[' . $this->get_shortcode_name() . ' widget-id="12"]</code>',
false
);
}
}
public static function get_alertbox($type, $content, $newline_content = true)
{
$types = array(
"warning" => array(
"css" => "color: #856404; background-color: #fff3cd; border-color: #ffeeba;",
"icon" => "dashicons-warning"
),
"info" => array(
"css" => "color: #0c5460; background-color: #d1ecf1; border-color: #bee5eb;",
"icon" => "dashicons-info"
),
"error" => array(
"css" => "color: #721c24; background-color: #f8d7da; border-color: #f5c6cb;",
"icon" => "dashicons-info"
)
);
return "<div style='margin:20px 0px; padding:10px; " . $types[$type]['css'] . " border-radius: 5px;'>"
. "<span class='dashicons " . $types[$type]['icon'] . "'></span> <strong>" . strtoupper(TrustindexTestimonialsPlugin::___($type)) . "</strong>"
. ($newline_content ? "<br />" : "")
. $content
. "</div>";
}
public function get_rating_stars($rating_score)
{
$text = "";
if (!is_numeric($rating_score)) {
return $text;
}
for ($si = 1; $si <= $rating_score; $si++) {
$text .= '<span class="ti-star f"></span>';
}
$fractional = $rating_score - floor($rating_score);
if (0.25 <= $fractional) {
if ($fractional < 0.75) {
$text .= '<span class="ti-star h"></span>';
} else {
$text .= '<span class="ti-star f"></span>';
}
$si++;
}
for (; $si <= 5; $si++) {
$text .= '<span class="ti-star e"></span>';
}
return $text;
}
public function save_css($id, $widget, $return = false)
{
$style_id = $widget['2'];
$set_id = $widget['3'];
add_filter('https_ssl_verify', '__return_false');
add_filter('block_local_requests', '__return_false');
$params = [
'platform' => 'google',
'layout_id' => $style_id,
'set_id' => $set_id
];
foreach ($widget['4']['appearance'] as $name => $value)
{
if ($name == 'text-align')
{
if(in_array($style_id, [ 36, 37, 38, 39, 45, 48 ]))
{
$name = 'content-align';
}
}
if (isset($value) && $value != "")
{
$px_array = array('profile-font-size','review-font-size','box-border-top-width','box-border-bottom-width','box-border-left-width','box-border-right-width','box-padding','box-border-radius');
if (in_array($name, $px_array))
{
$value = $value . 'px';
}
$params['overrides'][$name] = $value;
}
}
if (TrustindexTestimonialsPlugin::$widget_templates['templates'][$style_id]['generate'])
{
$params['overrides']['ti-profile-img-size'] = TrustindexTestimonialsPlugin::$widget_templates['templates'][$style_id]['image'];
}
$url = 'https://admin.trustindex.io/' . 'api/getLayoutScss?' . http_build_query($params);
$server_output = $this->post_request($url, [
'timeout' => '20',
'redirection' => '5',
'blocking' => true
]);
if ($server_output[0] !== '[' && $server_output[0] !== '{') {
$server_output = substr($server_output, strpos($server_output, '('));
$server_output = trim($server_output, '();');
}
$server_output = json_decode($server_output, true);
if ($server_output['css']) {
if ($style_id == 17 || $style_id == 21) {
$server_output['css'] .= '.ti-preview-box { position: initial !important }';
}
$server_output['css'] = str_replace('.source-Google', '.source-' . ucfirst($this->getShortName()), $server_output['css']);
if ($return)
{
$server_output['css'] = str_replace('.ti-widget.', '.ti-widget[data-layout-id="' . $style_id . '"][data-set-id="' . $set_id . '"].', $server_output['css']);
$server_output['css'] = str_replace('.ti-goog', '', $server_output['css']);
return $server_output['css'];
}
else
{
$server_output['css'] = str_replace('.ti-goog', '.ti-wp-testimonial-' . $id, $server_output['css']);
$server_output['css'] .= $this->get_additional_widget_style($id, $style_id);
$this->update_widget_css($id, $server_output['css']);
}
}
}
private function get_additional_widget_style($id, $style_id)
{
$style = ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.f{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.e{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . ".ti-no-stars .ti-review-item .ti-stars .ti-star.h{display: none;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-widget-container .ti-review-item .ti-profile-details .ti-date a {text-decoration: none !important; font-size: 12px;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . " .ti-widget-container .ti-review-item .ti-profile-details .ti-date a:hover{text-decoration: underline !important; font-size: 12px;} ";
if ($style_id == 4 || $style_id == 16 || $style_id == 19 || $style_id == 36 || $style_id == 37 || $style_id == 38 || $style_id == 45 || $style_id == 46 || $style_id == 47 || $style_id == 48)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content {height: auto !important;} ";
}
else if ($style_id == 6)
{
$style .= ".ti-review-content .inner .ti-review-text, .ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner .ti-review-text {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner {height: auto !important;} ";
}
else if ($style_id == 7)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner {height: auto !important;} ";
}
else if ($style_id == 15)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-review-text {height: auto !important;} ";
}
else if ($style_id == 18)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important;} ";
}
else if ($style_id == 19)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content [class$=inner] .ti-review-text {max-height: unset !important;} ";
}
else if ($style_id == 31)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-item .ti-read-more {display: none !important;} ";
}
else if ($style_id == 33)
{
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-content .ti-inner {max-height: unset !important; -webkit-line-clamp: unset !important;} ";
$style .= ".ti-widget.ti-wp-testimonial-" . $id . "[data-layout-id='" . $style_id . "'].ti-auto-height .ti-review-item .ti-read-more {display: none;} ";
}
return $style;
}
private function post_request($url, $args)
{
$response = wp_remote_post($url, $args);
if (is_wp_error($response)) {
$html = $this->get_alertbox('error', '<br />Error with wp_remote_post, error message: <br /><b>' . $response->get_error_message() . '</b>');
$allowed_html = array(
'div' => array(
'style' => array()
),
'span' => array(
'class' => array()
),
'br' => array()
);
echo wp_kses($html, $allowed_html);
die;
}
return wp_remote_retrieve_body($response);
}

static function plugin_activation()
{
wpttst_register_cpt();
update_option('wpttst_custom_forms', self::update_custom_forms());
if (get_option('wp-testimonials-visited-get-reviews') === false)
{
add_option( 'wp-testimonials-visited-get-reviews', 'no');
}
flush_rewrite_rules();
}
static function plugin_deactivation()
{
delete_option('wpttst_custom_forms');
delete_option('wp-testimonials-visited-get-reviews');
flush_rewrite_rules();
}
function plugin_uninstall()
{
$this->remove_widgets_table();
$this->remove_testimonials();
$this->remove_testimonial_categories();
}
public function plugin_loaded()
{
$this->loadI18N();
}

public static function edit_columns( $columns ) {
$fields = wpttst_get_all_fields();
unset( $columns['testimonial-widgets_thumbnail'], $columns['date'] );
$key = 'title';
$offset = array_search( $key, array_keys( $columns ) ) + 1;
foreach ( $fields as $key => $field ) {
if ( $field['admin_table'] ) {
if ( 'post_title' == $field['name'] ) {
continue;
} elseif ( 'featured_image' == $field['name'] ) {
$fields_to_add['testimonial-widgets_thumbnail'] = TrustindexTestimonialsPlugin::___( 'Thumbnail' );
} elseif ( 'rating' == $field['input_type'] ) {
$fields_to_add[ $field['name'] ] = TrustindexTestimonialsPlugin::___( 'Rating' );
} else {
$fields_to_add[ $field['name'] ] = apply_filters( 'wpttst_l10n', $field['label'], 'testimonial-widgets-form-fields', $field['name'] . ' : label' );
}
}
}
if ( count( get_terms( 'wpt-testimonial-category', array( 'hide_empty' => false ) ) )) {
$fields_to_add['category'] = TrustindexTestimonialsPlugin::___( 'Categories' );
}
$fields_to_add['date'] = TrustindexTestimonialsPlugin::___( 'Date' );
$columns = array_merge(
array_slice( $columns, 0, $offset ),
$fields_to_add,
array_slice( $columns, $offset, null )
);
return $columns;
}
public static function custom_columns( $column ) {
global $post;
switch ( $column ) {
case 'post_id' :
echo absint( esc_attr($post->ID) );
break;
case 'post_content' :
echo substr( esc_attr($post->post_content), 0, 100 ) . '&hellip;';
break;
case 'testimonial-widgets_thumbnail' :
echo wpttst_get_thumbnail( array( 60, 60 ) );
break;
case 'category' :
$categories = get_the_terms( 0, 'wpt-testimonial-category' );
if ( $categories && ! is_wp_error( $categories ) ) {
$list = array();
foreach ( $categories as $cat ) {
$list[] = $cat->name;
}
echo esc_attr(implode( ", ", $list ));
}
break;
default :
$custom = get_post_custom();
$fields = wpttst_get_custom_fields();
if ( isset( $custom[ $column ] ) && $custom[ $column ][0] ) {
if ( isset( $fields[ $column ] ) ) {
switch ( $fields[ $column ]['input_type'] ) {
case 'rating' :
wpttst_star_rating_display( $custom[ $column ][0], 'in-table-list' );
break;
case 'checkbox' :
echo $custom[ $column ][0] ? 'yes' : 'no';
break;
default :
echo esc_attr($custom[ $column ][0]);
}
}
} else {
if ( isset( $fields[ $column ] ) ) {
if ( 'checkbox' == $fields[ $column ]['input_type'] ) {
echo 'no';
} else {
}
}
}
}
}
public static function update_custom_forms()
{
$custom_forms = get_option('wpttst_custom_forms');
if (!$custom_forms) {
return self::get_custom_forms();
}
foreach ($custom_forms as $form_id => $form_properties) {
foreach ($form_properties['fields'] as $key => $form_field) {
/*
 * Convert categories to category-selector.
 * @since 2.17.0
 */
if ('categories' == $form_field['input_type']) {
$custom_forms[$form_id]['fields'][$key]['input_type'] = 'category-selector';
}
/*
 * Unset `show_default_options` for rating field. Going from 0 to 1.
 * @since 2.21.0
 */
if ('rating' == $form_field['input_type']) {
unset($form_field['show_default_options']);
}
/*
 * Add `show_required_option` to shortcode field. Initial value is false.
 * @since 2.22.0
 */
if ('shortcode' == $form_field['input_type']) {
$form_field['show_required_option'] = false;
}
/*
 * Add `show_default_options` to checkbox field.
 *
 * @since 2.27.0
 */
if ('checkbox' == $form_field['input_type']) {
$form_field['show_default_options'] = 1;
}
/*
 * Merge in new default.
 * Custom fields are in display order (not associative) so we must find them by `input_type`.
 * @since 2.21.0 Using default fields instead of default form as source
 */
$new_default = array();
$fields = get_option('wpttst_fields', array());
foreach ($fields['field_types'] as $field_type_group_key => $field_type_group) {
foreach ($field_type_group as $field_type_key => $field_type_field) {
if ($field_type_field['input_type'] == $form_field['input_type']) {
$new_default = $field_type_field;
break;
}
}
}
if ($new_default) {
$custom_forms[$form_id]['fields'][$key] = array_merge($new_default, $form_field);
}
}
}
return $custom_forms;
}
public static function get_custom_forms()
{
$base_forms = self::get_base_forms();
$forms[1] = array(
'name' => 'custom',
'label' => TrustindexTestimonialsPlugin::___('Custom Form'),
'readonly' => 0,
'fields' => $base_forms['default']['fields'],
);
return apply_filters('wpttst_update_custom_form', $forms);
}
public static function get_base_forms()
{
$default_fields = self::get_fields();
$forms = array(
'default' => array(
'name' => 'default',
'label' => TrustindexTestimonialsPlugin::___('Default Form'),
'readonly' => 1,
'fields' => array(
0 => array(
'record_type' => 'custom',
'name' => 'client_name',
'label' => TrustindexTestimonialsPlugin::___('Full Name'),
'input_type' => 'text',
'required' => 1,
'after' => TrustindexTestimonialsPlugin::___('What is your full name?'),
'admin_table' => 1,
),
1 => array(
'record_type' => 'custom',
'name' => 'company_name',
'label' => TrustindexTestimonialsPlugin::___('Company Name'),
'input_type' => 'text',
'after' => TrustindexTestimonialsPlugin::___('What is your company name?'),
),
2 => array(
'record_type' => 'custom',
'name' => 'company_website',
'label' => TrustindexTestimonialsPlugin::___('Company Website'),
'input_type' => 'text',
'after' => TrustindexTestimonialsPlugin::___('Does your company have a website?'),
),
5 => array(
'record_type' => 'post',
'name' => 'post_title',
'label' => TrustindexTestimonialsPlugin::___('Heading'),
'input_type' => 'text',
'required' => 0,
'after' => TrustindexTestimonialsPlugin::___('A headline for your testimonial.'),
'max_length' => ''
),
6 => array(
'record_type' => 'post',
'name' => 'post_content',
'label' => TrustindexTestimonialsPlugin::___('Testimonial'),
'input_type' => 'textarea',
'required' => 1,
'after' => TrustindexTestimonialsPlugin::___('What do you think about us?'),
'max_length' => ''
),
7 => array(
'record_type' => 'post',
'name' => 'featured_image',
'label' => TrustindexTestimonialsPlugin::___('Photo'),
'input_type' => 'file',
'after' => TrustindexTestimonialsPlugin::___('Would you like to include a photo?'),
'admin_table' => 1,
),
8 => array(
'record_type' => 'optional',
'name' => 'star_rating',
'label' => TrustindexTestimonialsPlugin::___('Star rating'),
'input_type' => 'rating',
'required' => 0,
'after' => TrustindexTestimonialsPlugin::___('Would you like to include star rating?')
),
),
),
);
$forms['minimal'] = array(
'name' => 'minimal',
'label' => TrustindexTestimonialsPlugin::___('Minimal Form'),
'readonly' => 1,
'fields' => array(
0 => array(
'record_type' => 'custom',
'name' => 'client_name',
'label' => TrustindexTestimonialsPlugin::___('Name'),
'input_type' => 'text',
'required' => 1,
'after' => '',
'admin_table' => 1,
),
1 => array(
'record_type' => 'post',
'name' => 'post_content',
'label' => TrustindexTestimonialsPlugin::___('Testimonial'),
'input_type' => 'textarea',
'required' => 1,
'after' => '',
),
),
);
foreach ($forms as $form_name => $form) {
foreach ($form['fields'] as $key => $array) {
if ('post' == $array['record_type']) {
$forms[$form_name]['fields'][$key] = array_merge($default_fields['field_types']['post'][$array['name']], $array);
} elseif ('custom' == $array['record_type']) {
$forms[$form_name]['fields'][$key] = array_merge($default_fields['field_types']['custom'][$array['input_type']], $array);
} else {
$forms[$form_name]['fields'][$key] = array_merge($default_fields['field_types']['optional'][$array['input_type']], $array);
}
}
}
return $forms;
}
public static function get_fields()
{
$field_base = self::get_field_base();
$field_types = array();
/*
 * Assemble post field types
 */
$field_types['post'] = array(
'post_title' => array(
'input_type' => 'text',
'option_label' => TrustindexTestimonialsPlugin::___('Testimonial Title'),
'map' => 'post_title',
'show_default_options' => 0,
'admin_table' => 1,
'admin_table_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
'show_length_option' => 1
),
'post_content' => array(
'input_type' => 'textarea',
'option_label' => TrustindexTestimonialsPlugin::___('Testimonial Content'),
'map' => 'post_content',
'required' => 1,
'show_default_options' => 0,
'core' => 0,
'admin_table' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
'show_length_option' => 1
),
'featured_image' => array(
'input_type' => 'file',
'option_label' => TrustindexTestimonialsPlugin::___('Reviewer\'s photo'),
'map' => 'featured_image',
'show_default_options' => 0,
'show_placeholder_option' => 0,
'admin_table' => 0,
'name_mutable' => 0,
)
);
foreach ($field_types['post'] as $key => $array) {
$field_types['post'][$key] = array_merge($field_base, $array);
}
/*
 * Assemble custom field types
 */
$field_types['custom'] = array(
'text' => array(
'input_type' => 'text',
'option_label' => TrustindexTestimonialsPlugin::___('text'),
),
'url' => array(
'input_type' => 'url',
'option_label' => TrustindexTestimonialsPlugin::___('URL'),
'show_default_options' => 0,
),
'checkbox' => array(
'input_type' => 'checkbox',
'option_label' => TrustindexTestimonialsPlugin::___('checkbox'),
'show_text_option' => 1,
'show_placeholder_option' => 0,
),
);
foreach ($field_types['custom'] as $key => $array) {
$field_types['custom'][$key] = array_merge($field_base, $array);
}
/*
 * Assemble special field types (FKA Optional)
 *
 * @since 1.18
 * @since 2.2.2 Fix bug caused by localizing 'categories'
 */
$field_types['optional'] = array(
'category-selector' => array(
'input_type' => 'category-selector',
'option_label' => TrustindexTestimonialsPlugin::___('category selector'),
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
),
'category-checklist' => array(
'input_type' => 'category-checklist',
'option_label' => TrustindexTestimonialsPlugin::___('category checklist'),
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'name_mutable' => 0,
),
'shortcode' => array(
'input_type' => 'shortcode',
'option_label' => TrustindexTestimonialsPlugin::___('shortcode'),
'show_label' => 0,
'required' => 0,
'show_required_option' => 0,
'show_default_options' => 0,
'show_placeholder_option' => 0,
'show_admin_table_option' => 0,
'show_shortcode_options' => 1,
),
'rating' => array(
'input_type' => 'rating',
'option_label' => TrustindexTestimonialsPlugin::___('star rating'),
'show_placeholder_option' => 0,
'admin_table' => 1,
'admin_table_option' => 1,
'show_admin_table_option' => 1,
),
);
/*
 * Merge each one onto base field
 */
foreach ($field_types['optional'] as $key => $array) {
$field_types['optional'][$key] = array_merge($field_base, $array);
}
/*
 * Assemble all fields
 */
$default_fields = array(
'field_base' => $field_base,
'field_types' => $field_types,
);
return apply_filters('wpttst_default_fields', $default_fields);
}
public static function get_field_base()
{
return apply_filters('wpttst_field_base', array(
'name' => '',
'name_mutable' => 1,
'label' => '',
'show_label' => 1,
'input_type' => '',
'action_input' => '',
'action_output' => '',
'text' => '',
'show_text_option' => 0,
'required' => 0,
'show_required_option' => 1,
'default_form_value' => '',
'default_display_value' => '',
'show_default_options' => 1,
'error' => TrustindexTestimonialsPlugin::___('This field is required.'),
'placeholder' => '',
'show_placeholder_option' => 1,
'before' => '',
'after' => '',
'admin_table' => 0,
'admin_table_option' => 1,
'show_admin_table_option' => 1,
'shortcode_on_form' => '',
'shortcode_on_display' => '',
'show_shortcode_options' => 0,
'show_length_option' => 0,
'max_length' => ''
));
}

public static $widget_templates = array(
'categories' =>
array(
'slider' => '4,5,13,15,19,34,36,37,39,44,45,46,47',
'sidebar' => '6,7,8,9,10,18',
'list' => '33',
'grid' => '16,31,38,48',
'floating' => '17,21',
),
'templates' =>
array(
4 =>
array(
'name' => 'Slider I.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
15 =>
array(
'name' => 'Slider II.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
36 =>
array(
'name' => 'Slider I. - centered',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
39 =>
array(
'name' => 'Slider II. - centered',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
45 =>
array(
'name' => 'Slider I. - Large picture',
'type' => 'slider',
'image' => 460,
'generate' => false,
),
46 =>
array(
'name' => 'Slider II. - Large picture',
'type' => 'slider',
'image' => 554,
'generate' => false,
),
44 =>
array(
'name' => 'Slider VI.',
'type' => 'slider',
'image' => 100,
'generate' => true,
),
47 =>
array(
'name' => 'Slider III. - Large picture',
'type' => 'slider',
'image' => 554,
'generate' => false,
),
37 =>
array(
'name' => 'Slider V.',
'type' => 'slider',
'image' => 120,
'generate' => true,
),
19 =>
array(
'name' => 'Slider IV.',
'type' => 'slider',
'image' => 60,
'generate' => false,
),
38 =>
array(
'name' => 'Grid II.',
'type' => 'grid',
'image' => 120,
'generate' => true,
),
48 =>
array(
'name' => 'Grid I. - Large picture',
'type' => 'grid',
'image' => 554,
'generate' => false,
),
16 =>
array(
'name' => 'Grid',
'type' => 'grid',
'image' => 60,
'generate' => false,
),
33 =>
array(
'name' => 'List I.',
'type' => 'list',
'image' => 60,
'generate' => false,
),
31 =>
array(
'name' => 'Mansonry grid',
'type' => 'grid',
'image' => 60,
'generate' => false,
),
6 =>
array(
'name' => 'Sidebar slider I.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
7 =>
array(
'name' => 'Sidebar slider II.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
18 =>
array(
'name' => 'Full sidebar I. - without header',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
10 =>
array(
'name' => 'Full sidebar III.',
'type' => 'sidebar',
'image' => 60,
'generate' => false,
),
17 =>
array(
'name' => 'Floating',
'type' => 'floating',
'image' => 60,
'generate' => false,
),
21 =>
array(
'name' => 'Floating II.',
'type' => 'floating',
'image' => 60,
'generate' => false,
),
),
);
public static $widget_styles = array(
'light-background' =>
array(
'id' => 'light-background',
'name' => 'Light background',
'position' => 0,
'select-position' => 0,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#efefef',
'box-border-color' => '#efefef',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-large' =>
array(
'id' => 'light-background-large',
'name' => 'Light background - large',
'position' => 0,
'select-position' => 0,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#f8f9f9',
'box-border-color' => '#f8f9f9',
'box-border-radius' => '12px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#c3c3c3',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border' =>
array(
'id' => 'ligth-border',
'name' => 'Light border',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#e5e5e5',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-3d-large' =>
array(
'id' => 'ligth-border-3d-large',
'name' => 'Light border - 3D - large',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => false,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border-3d-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#efefef',
'box-border-radius' => '10px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#b4b4b4',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '4px',
'box-border-left-width' => '1px',
'box-border-right-width' => '4px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-large' =>
array(
'id' => 'ligth-border-large',
'name' => 'Light border - large',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => true,
'hide-stars' => true,
'_vars' =>
array(
'style_id' => '"light-border-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#e2e2e2',
'box-border-radius' => '4px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#cccccc',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'ligth-border-large-red' =>
array(
'id' => 'ligth-border-large-red',
'name' => 'Light border - large - red',
'position' => 0,
'select-position' => 1,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-border-large-red"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#d93623',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#8d8d8d',
'arrow-color' => '#8d8d8d',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '3px',
'box-border-bottom-width' => '3px',
'box-border-left-width' => '3px',
'box-border-right-width' => '3px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'drop-shadow' =>
array(
'id' => 'drop-shadow',
'name' => 'Drop shadow',
'position' => 0,
'select-position' => 2,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"drop-shadow"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '5px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'drop-shadow-large' =>
array(
'id' => 'drop-shadow-large',
'name' => 'Drop shadow - large',
'position' => 0,
'select-position' => 2,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"drop-shadow-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#444444',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '12px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#939393',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.1',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-minimal' =>
array(
'id' => 'light-minimal',
'name' => 'Minimal',
'position' => 0,
'select-position' => 3,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-minimal"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#efefef',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-minimal-large' =>
array(
'id' => 'light-minimal-large',
'name' => 'Minimal - large',
'position' => 0,
'select-position' => 3,
'reviewer-photo' => false,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-minimal-large"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '0px',
'box-padding' => '20px',
'scroll' => 'false',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'soft' =>
array(
'id' => 'soft',
'name' => 'Soft',
'position' => 1,
'select-position' => 4,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"soft"',
'bg-color' => '#e4e4e4',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#b7b7b7',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-clean' =>
array(
'id' => 'light-clean',
'name' => 'Light clean',
'position' => 0,
'select-position' => 5,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-clean"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '13px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#dddddd',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-square' =>
array(
'id' => 'light-square',
'name' => 'Clean dark',
'position' => 0,
'select-position' => 6,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-square"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#f3f3f3',
'box-border-color' => '#dddddd',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '3px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-border' =>
array(
'id' => 'light-background-border',
'name' => 'Light background border',
'position' => 0,
'select-position' => 7,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-border"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#efefef',
'box-border-color' => '#cccccc',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'blue' =>
array(
'id' => 'blue',
'name' => 'Blue',
'position' => 0,
'select-position' => 8,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"blue"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#365899',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '15px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#dddfe2',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '1px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '1px',
'box-border-right-width' => '1px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-large-purple' =>
array(
'id' => 'light-background-large-purple',
'name' => 'Light background - large - purple',
'position' => 0,
'select-position' => 9,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-large-purple"',
'bg-color' => '#ffffff',
'text-color' => '#593072',
'outside-text-color' => '#593072',
'profile-color' => '#593072',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#f6f1f9',
'box-border-color' => '#fbf9fc',
'box-border-radius' => '15px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#593072',
'arrow-color' => '#593072',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '5px',
'box-border-bottom-width' => '5px',
'box-border-left-width' => '5px',
'box-border-right-width' => '5px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-background-image' =>
array(
'id' => 'light-background-image',
'name' => 'Light background image',
'position' => 0,
'select-position' => 9,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-background-image"',
'bg-color' => '#ffffff',
'text-color' => '#000000',
'outside-text-color' => '#000000',
'profile-color' => '#000000',
'profile-font-size' => '14px',
'review-font-size' => '14px',
'rating-text' => '15px',
'company-font-size' => '18px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '8px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#999999',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.05',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0.3',
'box-backdrop-blur' => '5px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '15px',
),
),
'light-contrast' =>
array(
'id' => 'light-contrast',
'name' => 'Light contrast',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-contrast"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#000000',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#222222',
'box-border-color' => '#222222',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#555555',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-contrast-large' =>
array(
'id' => 'light-contrast-large',
'name' => 'Light contrast - large',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => false,
'verified-icon' => false,
'hide-logos' => true,
'hide-stars' => true,
'_vars' =>
array(
'style_id' => '"light-contrast-large"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#252c44',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#252c44',
'box-border-color' => '#252c44',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#ffffff',
'arrow-color' => '#252c44',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#252c44',
'box-shadow-opacity' => '0.45',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'light-contrast-large-blue' =>
array(
'id' => 'light-contrast-large-blue',
'name' => 'Light contrast - large - blue',
'position' => 0,
'select-position' => 10,
'reviewer-photo' => true,
'verified-icon' => false,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"light-contrast-large-blue"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#252c44',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '16px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '5',
'box-background-color' => '#242f62',
'box-border-color' => '#2aa8d7',
'box-border-radius' => '0px',
'box-padding' => '25px',
'scroll' => 'true',
'scroll-color' => '#ffffff',
'arrow-color' => '#242f62',
'float-widget-align' => 'left',
'nav' => 'false',
'dots' => 'true',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#252c44',
'box-shadow-opacity' => '0.45',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '10px',
'box-border-right-width' => '0px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-background' =>
array(
'id' => 'dark-background',
'name' => 'Dark background',
'position' => 1,
'select-position' => 11,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-background"',
'bg-color' => '#222222',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#303030',
'box-border-color' => '#303030',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#666666',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-minimal' =>
array(
'id' => 'dark-minimal',
'name' => 'Minimal dark',
'position' => 0,
'select-position' => 11,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-minimal"',
'bg-color' => '#000000',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#444444',
'box-border-radius' => '0px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '1px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-border' =>
array(
'id' => 'dark-border',
'name' => 'Dark border',
'position' => 1,
'select-position' => 12,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-border"',
'bg-color' => '#222222',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#222222',
'box-border-color' => '#444444',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#444444',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-contrast' =>
array(
'id' => 'dark-contrast',
'name' => 'Dark contrast',
'position' => 1,
'select-position' => 14,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-contrast"',
'bg-color' => '#222222',
'text-color' => '#000000',
'outside-text-color' => '#ffffff',
'profile-color' => '#000000',
'profile-font-size' => '15px',
'review-font-size' => '14px',
'rating-text' => '14px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#ffffff',
'box-border-color' => '#ffffff',
'box-border-radius' => '4px',
'box-padding' => '15px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#ffffff',
'float-widget-align' => 'left',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'true',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'false',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.15',
'box-border-top-width' => '2px',
'box-border-bottom-width' => '2px',
'box-border-left-width' => '2px',
'box-border-right-width' => '2px',
'box-background-opacity' => '1',
'box-backdrop-blur' => '0px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '14px',
),
),
'dark-background-image' =>
array(
'id' => 'dark-background-image',
'name' => 'Dark background image',
'position' => 0,
'select-position' => 15,
'reviewer-photo' => true,
'verified-icon' => true,
'hide-logos' => false,
'hide-stars' => false,
'_vars' =>
array(
'style_id' => '"dark-background-image"',
'bg-color' => '#ffffff',
'text-color' => '#ffffff',
'outside-text-color' => '#ffffff',
'profile-color' => '#ffffff',
'profile-font-size' => '15px',
'review-font-size' => '15px',
'rating-text' => '15px',
'company-font-size' => '16px',
'review-lines' => '4',
'box-background-color' => '#000000',
'box-border-color' => '#000000',
'box-border-radius' => '5px',
'box-padding' => '20px',
'scroll' => 'true',
'scroll-color' => '#555555',
'arrow-color' => '#cccccc',
'float-widget-align' => 'right',
'nav' => 'true',
'dots' => 'mobile',
'hover-anim' => 'false',
'review-italic' => 'false',
'enable-font' => 'true',
'align-mini' => 'center',
'readmore' => 'true',
'popup-background' => '#ffffff',
'popup-company-color' => '#333333',
'popup-company-size' => '16px',
'popup-profile-color' => '#333333',
'popup-profile-size' => '15px',
'popup-review-color' => '#333333',
'popup-review-size' => '14px',
'popup-separator-color' => '#dedede',
'popup-separator-width' => '1px',
'box-shadow' => 'true',
'box-shadow-color' => '#000000',
'box-shadow-opacity' => '0.20',
'box-border-top-width' => '0px',
'box-border-bottom-width' => '0px',
'box-border-left-width' => '0px',
'box-border-right-width' => '0px',
'box-background-opacity' => '0.3',
'box-backdrop-blur' => '5px',
'highlight-color' => '#fbe049',
'highlight-size' => '19px',
'review-title' => 'normal',
'content-align' => 'center',
'text-align' => 'left',
'original-rating-text' => '15px',
),
),
);
public static $widget_languages = [
'ar' => "العربية",
'zh' => "汉语",
'cs' => "Čeština",
'da' => "Dansk",
'nl' => "Nederlands",
'en' => "English",
'et' => "Eestlane",
'fi' => "Suomi",
'fr' => "Français",
'de' => "Deutsch",
'el' => "Ελληνικά",
'hi' => "हिन्दी",
'hu' => "Magyar",
'it' => "Italiano",
'no' => "Norsk",
'pl' => "Polski",
'pt' => "Português",
'ro' => "Română",
'ru' => "Русский",
'sk' => "Slovenčina",
'es' => "Español",
'sv' => "Svenska",
'tr' => "Türkçe",
'gd' => 'Gàidhlig na h-Alba',
'hr' => 'Hrvatski',
'id' => 'Bahasa Indonesia',
'is' => 'Íslensku',
'he' => 'עִברִית',
'ja' => '日本',
'ko' => '한국어',
'lt' => 'Lietuvių',
'ms' => 'Bahasa Melayu',
'sl' => 'Slovenščina',
'sr' => 'Српски',
'th' => 'ไทย',
'uk' => 'Українська',
'vi' => 'Tiếng Việt',
'mk' => 'Македонски',
'bg' => 'български',
'sq' => 'Shqip',
'af' => 'Afrikaans',
'az' => 'Azərbaycan dili',
'bn' => 'বাংলা',
'bs' => 'Bosanski',
'cy' => 'Cymraeg',
'fa' => 'فارسی'
];
public static $widget_dateformats = ['j. F, Y.', 'F j, Y.', 'Y.m.d.', 'Y-m-d', 'd/m/Y'];
private static $widget_month_names = array(
'en' =>
array(
0 => 'January',
1 => 'February',
2 => 'March',
3 => 'April',
4 => 'May',
5 => 'June',
6 => 'July',
7 => 'August',
8 => 'September',
9 => 'October',
10 => 'November',
11 => 'December',
),
'et' =>
array(
0 => 'jaanuar',
1 => 'veebruar',
2 => 'märts',
3 => 'aprill',
4 => 'mai',
5 => 'juuni',
6 => 'juuli',
7 => 'august',
8 => 'september',
9 => 'oktoober',
10 => 'november',
11 => 'detsember',
),
'ar' =>
array(
0 => 'يناير',
1 => 'فبراير',
2 => 'مارس',
3 => 'أبريل',
4 => 'مايو',
5 => 'يونيو',
6 => 'يوليه',
7 => 'أغسطس',
8 => 'سبتمبر',
9 => 'أكتوبر',
10 => 'نوفمبر',
11 => 'ديسمبر',
),
'zh' =>
array(
0 => '一月',
1 => '二月',
2 => '三月',
3 => '四月',
4 => '五月',
5 => '六月',
6 => '七月',
7 => '八月',
8 => '九月',
9 => '十月',
10 => '十一月',
11 => '十二月',
),
'cs' =>
array(
0 => 'Leden',
1 => 'Únor',
2 => 'Březen',
3 => 'Duben',
4 => 'Květen',
5 => 'Červen',
6 => 'Červenec',
7 => 'Srpen',
8 => 'Září',
9 => 'Říjen',
10 => 'Listopad',
11 => 'Prosinec',
),
'da' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Marts',
3 => 'April',
4 => 'Maj',
5 => 'Juni',
6 => 'Juli',
7 => 'August',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'nl' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Maart',
3 => 'April',
4 => 'Mei',
5 => 'Juni',
6 => 'Juli',
7 => 'Augustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'fi' =>
array(
0 => 'Tammikuu',
1 => 'Helmikuu',
2 => 'Maaliskuu',
3 => 'Huhtikuu',
4 => 'Toukokuu',
5 => 'Kesäkuu',
6 => 'Heinäkuu',
7 => 'Elokuu',
8 => 'Syyskuu',
9 => 'Lokakuu',
10 => 'Marraskuu',
11 => 'Joulukuu',
),
'fr' =>
array(
0 => 'Janvier',
1 => 'Février',
2 => 'Mars',
3 => 'Avril',
4 => 'Mai',
5 => 'Juin',
6 => 'Juillet',
7 => 'Août',
8 => 'Septembre',
9 => 'Octobre',
10 => 'Novembre',
11 => 'Décembre',
),
'de' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'März',
3 => 'April',
4 => 'Mai',
5 => 'Juni',
6 => 'Juli',
7 => 'August',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Dezember',
),
'el' =>
array(
0 => 'Iανουάριος',
1 => 'Φεβρουάριος',
2 => 'Μάρτιος',
3 => 'Aρίλιος',
4 => 'Μάιος',
5 => 'Iούνιος',
6 => 'Iούλιος',
7 => 'Αύγουστος',
8 => 'Σεπτέμβριος',
9 => 'Oκτώβριος',
10 => 'Νοέμβριος',
11 => 'Δεκέμβριος',
),
'he' =>
array(
0 => 'ינואר',
1 => 'פברואר',
2 => 'מרץ',
3 => 'אפריל',
4 => 'מאי',
5 => 'יוני',
6 => 'יולי',
7 => 'אוגוסט',
8 => 'ספטמבר',
9 => 'אוקטובר',
10 => 'נובמבר',
11 => 'דצמבר',
),
'hi' =>
array(
0 => 'जनवरी',
1 => 'फ़रवरी',
2 => 'मार्च',
3 => 'अप्रैल',
4 => 'मई',
5 => 'जून',
6 => 'जुलाई',
7 => 'अगस्त',
8 => 'सितंबर',
9 => 'अक्टूबर',
10 => 'नवंबर',
11 => 'दिसंबर',
),
'hu' =>
array(
0 => 'Január',
1 => 'Február',
2 => 'Március',
3 => 'Április',
4 => 'Május',
5 => 'Június',
6 => 'Július',
7 => 'Augusztus',
8 => 'Szeptember',
9 => 'Október',
10 => 'November',
11 => 'December',
),
'it' =>
array(
0 => 'Gennaio',
1 => 'Febbraio',
2 => 'Marzo',
3 => 'Aprile',
4 => 'Maggio',
5 => 'Giugno',
6 => 'Luglio',
7 => 'Agosto',
8 => 'Settembre',
9 => 'Ottobre',
10 => 'Novembre',
11 => 'Dicembre',
),
'ja' =>
array(
0 => '1月',
1 => '2月',
2 => '3月',
3 => '4月',
4 => '5月',
5 => '6月',
6 => '7月',
7 => '8月',
8 => '9月',
9 => '10月',
10 => '11月',
11 => '12月',
),
'no' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Mars',
3 => 'April',
4 => 'Mai',
5 => 'Juni',
6 => 'Juli',
7 => 'August',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'pl' =>
array(
0 => 'Styczeń',
1 => 'Luty',
2 => 'Marzec',
3 => 'Kwiecień',
4 => 'Maj',
5 => 'Czerwiec',
6 => 'Lipiec',
7 => 'Sierpień',
8 => 'Wrzesień',
9 => 'Październik',
10 => 'Listopad',
11 => 'Grudzień',
),
'pt' =>
array(
0 => 'Janeiro',
1 => 'Fevereiro',
2 => 'Março',
3 => 'Abril',
4 => 'Maio',
5 => 'Junho',
6 => 'Julho',
7 => 'Agosto',
8 => 'Setembro',
9 => 'Outubro',
10 => 'Novembro',
11 => 'Dezembro',
),
'ro' =>
array(
0 => 'Ianuarie',
1 => 'Februarie',
2 => 'Martie',
3 => 'Aprilie',
4 => 'Mai',
5 => 'Iunie',
6 => 'Iulie',
7 => 'August',
8 => 'Septembrie',
9 => 'Octombrie',
10 => 'Noiembrie',
11 => 'Decembrie',
),
'ru' =>
array(
0 => 'январь',
1 => 'февраль',
2 => 'март',
3 => 'апрель',
4 => 'май',
5 => 'июнь',
6 => 'июль',
7 => 'август',
8 => 'сентябрь',
9 => 'октябрь',
10 => 'ноябрь',
11 => 'декабрь',
),
'sk' =>
array(
0 => 'Január',
1 => 'Február',
2 => 'Marec',
3 => 'Apríl',
4 => 'Máj',
5 => 'Jún',
6 => 'Júl',
7 => 'August',
8 => 'September',
9 => 'Október',
10 => 'November',
11 => 'December',
),
'sl' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Marec',
3 => 'April',
4 => 'Maj',
5 => 'Junij',
6 => 'Julij',
7 => 'Avgust',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'es' =>
array(
0 => 'Enero',
1 => 'Febrero',
2 => 'Marzo',
3 => 'Abril',
4 => 'Mayo',
5 => 'Junio',
6 => 'Julio',
7 => 'Agosto',
8 => 'Septiembre',
9 => 'Octubre',
10 => 'Noviembre',
11 => 'Diciembre',
),
'sv' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Mars',
3 => 'April',
4 => 'Maj',
5 => 'Juni',
6 => 'Juli',
7 => 'Augusti',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'December',
),
'tr' =>
array(
0 => 'Ocak',
1 => 'Şubat',
2 => 'Mart',
3 => 'Nisan',
4 => 'Mayis',
5 => 'Haziran',
6 => 'Temmuz',
7 => 'Ağustos',
8 => 'Eylül',
9 => 'Ekim',
10 => 'Kasım',
11 => 'Aralık',
),
'uk' =>
array(
0 => 'Січня',
1 => 'Лютий',
2 => 'Березень',
3 => 'квітень',
4 => 'травень',
5 => 'червень',
6 => 'липень',
7 => 'серпень',
8 => 'вересень',
9 => 'жовтень',
10 => 'листопад',
11 => 'грудень',
),
'gd' =>
array(
0 => 'am Faoilleach',
1 => 'an Gearran',
2 => 'am Màrt',
3 => 'an Giblean',
4 => 'an Cèitean',
5 => 'an t-Ògmhios',
6 => 'an t-luchar',
7 => 'an Lùnastal',
8 => 'an t-Sultain',
9 => 'an Dàmhair',
10 => 'an t-Samhain',
11 => 'an Dùbhlachd',
),
'hr' =>
array(
0 => 'Siječanj',
1 => 'Veljača',
2 => 'Ožujak',
3 => 'Travanj',
4 => 'Svibanj',
5 => 'Lipanj',
6 => 'Srpanj',
7 => 'Kolovoz',
8 => 'Rujan',
9 => 'Listopad',
10 => 'Studeni',
11 => 'Prosinac',
),
'id' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Maret',
3 => 'April',
4 => 'Mei',
5 => 'Juni',
6 => 'Juli',
7 => 'Agustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'is' =>
array(
0 => 'Janúar',
1 => 'Febrúar',
2 => 'Mars',
3 => 'April',
4 => 'Maí',
5 => 'Júní',
6 => 'Júlí',
7 => 'Ágúst',
8 => 'September',
9 => 'Október',
10 => 'Nóvember',
11 => 'Desember',
),
'ko' =>
array(
0 => '일월',
1 => '이월',
2 => '삼월',
3 => '사월',
4 => '오월',
5 => '유월',
6 => '칠월',
7 => '팔월',
8 => '구월',
9 => '시월',
10 => '십일월',
11 => '십이월',
),
'lt' =>
array(
0 => 'Sausis',
1 => 'Vasaris',
2 => 'Kovas',
3 => 'Balandis',
4 => 'Gegužė',
5 => 'Birželis',
6 => 'Liepa',
7 => 'Rugpjūtis',
8 => 'Rugsėjis',
9 => 'Spalis',
10 => 'Lapkritis',
11 => 'Gruodis',
),
'ms' =>
array(
0 => 'Januari',
1 => 'Februari',
2 => 'Mac',
3 => 'April',
4 => 'Mei',
5 => 'Jun',
6 => 'Julai',
7 => 'Ogos',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Disember',
),
'sr' =>
array(
0 => 'Јануар',
1 => 'Фебруар',
2 => 'Март',
3 => 'Април',
4 => 'Mај',
5 => 'Јуни',
6 => 'Јул',
7 => 'Август',
8 => 'Cептембар',
9 => 'Октобар',
10 => 'Новембар',
11 => 'Децембар',
),
'th' =>
array(
0 => 'มกราคม',
1 => 'กุมภาพันธ์',
2 => 'มีนาคม',
3 => 'เมษายน',
4 => 'พฤษภาคม',
5 => 'มิถุนายน',
6 => 'กรกฎาคม',
7 => 'สิงหาคม',
8 => 'กันยายน',
9 => 'ตุลาคม',
10 => 'พฤศจิกายน',
11 => 'ธันวาคม',
),
'vi' =>
array(
0 => 'tháng một',
1 => 'tháng hai',
2 => 'tháng ba',
3 => 'tháng tư',
4 => 'tháng năm',
5 => 'tháng sáu',
6 => 'tháng bảy',
7 => 'tháng tám',
8 => 'tháng chín',
9 => 'tháng mười',
10 => 'tháng mười một',
11 => 'tháng mười hai',
),
'mk' =>
array(
0 => 'Jануари',
1 => 'февруари',
2 => 'март',
3 => 'април',
4 => 'мај',
5 => 'јуни',
6 => 'јули',
7 => 'август',
8 => 'септември',
9 => 'октомври',
10 => 'ноември',
11 => 'декември',
),
'bg' =>
array(
0 => 'Януари',
1 => 'февруари',
2 => 'Март',
3 => 'Aприл',
4 => 'май',
5 => 'юни',
6 => 'юли',
7 => 'Август',
8 => 'Септември',
9 => 'Октомври',
10 => 'Ноември',
11 => 'Декември',
),
'sq' =>
array(
0 => 'Janar',
1 => 'Shkurt',
2 => 'Mars',
3 => 'Prill',
4 => 'Maj',
5 => 'Qershor',
6 => 'Korrik',
7 => 'Gusht',
8 => 'Shtator',
9 => 'Tetor',
10 => 'Nëntor',
11 => 'Dhjetor',
),
'af' =>
array(
0 => 'Januarie',
1 => 'Februarie',
2 => 'Maart',
3 => 'April',
4 => 'Mei',
5 => 'Junie',
6 => 'Julie',
7 => 'Augustus',
8 => 'September',
9 => 'Oktober',
10 => 'November',
11 => 'Desember',
),
'az' =>
array(
0 => 'Yanvar',
1 => 'Fevral',
2 => 'Mart',
3 => 'Aprel',
4 => 'May',
5 => 'İyun',
6 => 'İyul',
7 => 'Avqust',
8 => 'Sentyabr',
9 => 'Oktyabr',
10 => 'Noyabr',
11 => 'Dekabr',
),
'bn' =>
array(
0 => 'জানুয়ারি',
1 => 'ফেব্রুয়ারি',
2 => 'মার্চ',
3 => 'এপ্রিল',
4 => 'মে',
5 => 'জুন',
6 => 'জুলাই',
7 => 'আগস্ট',
8 => 'সেপ্টেম্বর',
9 => 'অক্টোবর',
10 => 'নভেম্বর',
11 => 'ডিসেম্বর',
),
'bs' =>
array(
0 => 'Januar',
1 => 'Februar',
2 => 'Mart',
3 => 'April',
4 => 'Maj',
5 => 'Jun',
6 => 'Jul',
7 => 'Avgust',
8 => 'Septembar',
9 => 'Oktobar',
10 => 'Novembar',
11 => 'Decembar',
),
'cy' =>
array(
0 => 'Ionawr',
1 => 'Chwefror',
2 => 'Mawrth',
3 => 'Ebrill',
4 => 'Mai',
5 => 'Mehefin',
6 => 'Gorffennaf',
7 => 'Awst',
8 => 'Medi',
9 => 'Hydref',
10 => 'Tachwedd',
11 => 'Rhagfyr',
),
'fa' =>
array(
0 => 'ژانویه',
1 => 'فوریه',
2 => 'مارس',
3 => 'آوریل',
4 => 'ممکن است',
5 => 'ژوئن',
6 => 'جولای',
7 => 'اوت',
8 => 'سپتامبر',
9 => 'اکتبر',
10 => 'نوامبر',
11 => 'دسامبر',
),
);

public static function get_widget_tablename()
{
global $wpdb;
return $wpdb->prefix . "trustindex_testimonials_widgets";
}
public function create_widgets_table()
{
$dbtable = $this->get_widget_tablename();
$sql = "CREATE TABLE IF NOT EXISTS $dbtable (
id TINYINT(2) NOT NULL AUTO_INCREMENT,
name VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
value TEXT CHARACTER SET utf8 COLLATE utf8_general_ci,
review_content TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
css TEXT CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
created_at DATETIME DEFAULT NULL,
updated_at DATETIME DEFAULT NULL,
PRIMARY KEY (id)
);";
dbDelta($sql);
}
static function remove_widgets_table()
{
global $wpdb;
$dbtable = TrustindexTestimonialsPlugin::get_widget_tablename();
$sql = "DROP TABLE IF EXISTS $dbtable";
$wpdb->query($sql);
}
public function create_widget($name, $widget_settings)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->insert(
$dbtable,
array(
'name' => $name,
'value' => $widget_settings,
'review_content' => null,
'css' => null,
'created_at' => date('Y-m-d H:i:s'),
'updated_at' => date('Y-m-d H:i:s'),
),
array('%s', '%s', '%s', '%s', '%s')
);
if ($result)
{
return $wpdb->insert_id;
}
else
{
return false;
}
}
public function update_widget($id, $name, $widget_settings)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'name' => $name,
'value' => $widget_settings,
'updated_at' => date('Y-m-d H:i:s'),
),
array('id' => $id),
array('%s', '%s', '%s'),
array('%d')
);
return $result;
}
public function update_widget_review_content($id, $review_content)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'review_content' => $review_content,
),
array('id' => $id),
array('%s'),
array('%d')
);
return $result;
}
public function update_widget_css($id, $css)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->update(
$dbtable,
array(
'css' => $css,
),
array('id' => $id),
array('%s'),
array('%d')
);
return $result;
}
public function get_next_widget_id()
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$sql = "SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '{$dbtable}'";
$result = $wpdb->get_results($sql);
return $result[0]->auto_increment;
}
public function get_widget($id)
{
$widget_id = intval($id);
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM $dbtable WHERE id = %d", [$widget_id]));
return $result;
}
public function get_widgets($order_by = "id", $order = "asc", $search = null)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$sql = "SELECT * FROM $dbtable";
if ($search)
{
$sql .= " WHERE name LIKE '%{$search}%'";
}
$sql .= " ORDER BY $order_by $order";
$results = $wpdb->get_results($sql);
return $results;
}
public function delete_widget($id)
{
$dbtable = $this->get_widget_tablename();
global $wpdb;
$result = $wpdb->delete($dbtable, array('id' => $id), array( '%d' ));
return $result;
}
public function duplicate_widget($id)
{
$widget_id = intval($id);
$dbtable = $this->get_widget_tablename();
global $wpdb;
$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $dbtable WHERE id = %d", [$widget_id]));
$name = $row->name . '-' . TrustindexTestimonialsPlugin::___('copy');
$result = $wpdb->insert(
$dbtable,
array(
'name' => $name,
'value' => $row->value,
'review_content' => $row->review_content,
'created_at' => date('Y-m-d H:i:s'),
'updated_at' => $row->updated_at
),
array('%s', '%s', '%s', '%s', '%s')
);
$new_id = $wpdb->insert_id;
$css = $this->copy_css($row->css, $id, $new_id);
$this->update_widget_css($new_id, $css);
return $result;
}
private function copy_css($css, $from_id, $to_id)
{
return str_replace('.ti-wp-testimonial-'.$from_id, '.ti-wp-testimonial-' . $to_id, $css);
}
public function get_categories()
{
$result = array();
$terms = get_terms('wpt-testimonial-category');
if ($terms && !is_wp_error($terms))
{
foreach($terms as $term)
{
$result[] = array(
'term_id' => $term->term_id,
'name' => $term->name
);
}
}
return $result;
}
public function get_reviews($filter = null)
{
$result = array();
if (isset($filter) && $filter['mode'] == 'manual_select')
{
foreach ($filter['ids'] as $id)
$result[] = $this->get_review_data($id);
if (isset($filter['order']) && $filter['order'] == 'newest')
{
usort($result, function ($a, $b) { return (strtotime($b["date"]) - strtotime($a["date"])); });
}
else if (isset($filter['order']) && $filter['order'] == 'oldest')
{
usort($result, function ($a, $b) { return (strtotime($a["date"]) - strtotime($b["date"])); });
}
else if (isset($filter['order']) && $filter['order'] == 'random')
{
shuffle($result);
}
}
else
{
$args = array(
'post_type' => 'wpt-testimonial',
'post_status' => 'publish',
'numberposts' => -1,
);
if (isset($filter) && $filter['mode'] == 'category')
{
$args['tax_query'] = array(
array(
'taxonomy' => 'wpt-testimonial-category',
'field' => 'term_id',
'terms' => intval($filter['category'])
)
);
}
if (isset($filter) && isset($filter['order']))
{
if ($filter['order'] == 'newest')
{
$args['orderby'] = 'date';
$args['order'] = 'DESC';
}
else if ($filter['order'] == 'oldest')
{
$args['orderby'] = 'date';
$args['order'] = 'ASC';
}
else if ($filter['order'] == 'random')
{
$args['orderby'] = 'rand';
}
}
if (isset($filter) && $filter['rating'] !== 'all')
{
$rs = $this->get_reviews_data($args);
$accepted_ratings = null;
if ($filter['rating'] === 'only_5')
{
$accepted_ratings = [5];
}
else if ($filter['rating'] === 'min_4')
{
$accepted_ratings = [4,5];
}
else if ($filter['rating'] === 'min_3')
{
$accepted_ratings = [3,4,5];
}
else if ($filter['rating'] === 'max_3')
{
$accepted_ratings = [1,2,3];
}
foreach ($rs as $r)
{
if (in_array($r['star_rating'], $accepted_ratings))
{
$result[] = $r;
}
}
}
else
{
$result = $this->get_reviews_data($args);
}
}
return $result;
}
private function get_reviews_data($args)
{
$result = array();
$meta_keys = array(
'client_name',
'company_name',
'company_website',
'star_rating'
);
$posts = get_posts($args);
if ($posts && !is_wp_error($posts))
{
foreach($posts as $post)
{
$tmp = array();
$tmp['id'] = $post->ID;
if(!empty($post->post_date))
{
$date = strtotime($post->post_date);
$tmp['date'] = date('Y-m-d', $date);
}
else
{
$tmp['date'] = null;
}
$tmp['content'] = !empty($post->post_content) ? $post->post_content : null;
$tmp['title'] = !empty($post->post_title) ? $post->post_title : null;
foreach($meta_keys as $key)
{
$value = $this->get_post_meta_value($post->ID, $key);
if (!empty($value))
{
$tmp[$key] = $this->get_post_meta_value($post->ID, $key);
}
else
{
$tmp[$key] = null;
}
}
$tmp['photo'] = array();
foreach (TrustindexTestimonialsPlugin::$thumbnail_sizes as $thumbnail_size)
{
$tmp['photo'][$thumbnail_size] = get_the_post_thumbnail_url($post->ID, 'wpt-testimonial-thumbnail-' . $thumbnail_size);
}
$result[] = $tmp;
}
}
return $result;
}
private function get_review_data($id)
{
$result = array();
$meta_keys = array(
'client_name',
'company_name',
'company_website',
'star_rating'
);
$post = get_post($id);
if ($post && !is_wp_error($post))
{
$result['id'] = $post->ID;
if(!empty($post->post_date))
{
$date = strtotime($post->post_date);
$result['date'] = date('Y-m-d', $date);
}
else
{
$result['date'] = null;
}
$result['content'] = !empty($post->post_content) ? $post->post_content : null;
$result['title'] = !empty($post->post_title) ? $post->post_title : null;
foreach($meta_keys as $key)
{
$value = $this->get_post_meta_value($post->ID, $key);
if (!empty($value))
{
$result[$key] = $this->get_post_meta_value($post->ID, $key);
}
else
{
$result[$key] = null;
}
}
$result['photo'] = array();
foreach (TrustindexTestimonialsPlugin::$thumbnail_sizes as $thumbnail_size)
{
$result['photo'][$thumbnail_size] = get_the_post_thumbnail_url($post->ID, 'wpt-testimonial-thumbnail-' . $thumbnail_size);
}
}
return $result;
}
private function get_post_meta_value($id, $key)
{
return get_post_meta($id, $key, true);
}
static function remove_testimonials()
{
$testimonials = get_posts(
array(
'numberposts' => -1,
'post_type' => 'wpt-testimonial',
'post_status' => get_post_types(),
)
);
if ($testimonials && !is_wp_error($testimonials))
{
foreach ( $testimonials as $t )
{
wp_delete_post( $t->ID, true);
}
}
}
public function remove_testimonial_categories()
{
global $wpdb;
$sql = "SELECT * FROM $wpdb->term_taxonomy WHERE taxonomy = 'wpt-testimonial-category'";
$results = $wpdb->get_results($sql);
$term_ids = array();
$term_taxonomy_ids = array();
foreach ($results as $result)
{
$term_taxonomy_ids[] = $result->term_taxonomy_id;
$term_ids[] = $result->term_id;
}
if ( is_array( $term_ids ) && !empty( $term_ids ) )
{
$id_in = implode( ',', $term_ids );
$sql = $wpdb->prepare( "DELETE FROM $wpdb->terms WHERE term_id IN ( $id_in )" );
$sql_meta = $wpdb->prepare( "DELETE FROM $wpdb->term_taxonomy WHERE term_id IN ( $id_in )" );
$wpdb->query( $sql );
$wpdb->query( $sql_meta );
}
if ( is_array( $term_taxonomy_ids ) && !empty( $term_taxonomy_ids ) )
{
$id_in = implode( ',', $term_taxonomy_ids );
$sql_relation = $wpdb->prepare( "DELETE FROM $wpdb->term_relationships WHERE term_taxonomy_id IN ( $id_in )" );
$wpdb->query( $sql_relation );
}
}

public function get_category_tablename()
{
global $wpdb;
return $wpdb->prefix . "terms";
}
}
