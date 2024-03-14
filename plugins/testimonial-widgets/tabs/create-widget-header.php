<?php
defined('ABSPATH') or die('No script kiddies please!');
$current_step = isset($_GET['step']) ? intval(sanitize_text_field($_GET['step'])) : 1;
$page = sanitize_text_field($_GET['page']);
$id = isset($_GET['id']) ? intval(sanitize_text_field($_GET['id'])) : null;
$name_missing = isset($_GET['name-required']) ? true : false;
$selected = isset($_GET['selected']) ? true : null;
$style_id = isset($_GET['style_id']) ? intval(sanitize_text_field($_GET['style_id'])) : null;
$scss_set = isset($_GET['scss_set']) ? sanitize_text_field($_GET['scss_set']) : null;
$widget_setted_up = isset($_GET['setup_widget']) ? true : null;
wp_enqueue_style("trustindex-widget-preview-css", "https://cdn.trustindex.io/" . "assets/ti-preview-box.css");
global $trustindex_testimonials_pm;
$widget_name = null;
if ($scss_set)
{
$use_appearance = $scss_set;
}
else
{
$use_appearance = 'light-background';
}
$appearance = array(
'hide_stars' => false,
'hide_image' => false,
'display_type' => 'website',
'date_format' => 'j. F, Y.',
'hover-anim' => filter_var($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['hover-anim'], FILTER_VALIDATE_BOOLEAN),
'enable-font' => filter_var($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['enable-font'], FILTER_VALIDATE_BOOLEAN),
'nav' => $trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['nav'],
'dots' => $trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['dots'],
'text-align' => isset($style_id) && in_array($style_id, ['36','37','38','45','48']) ? 'center' : 'left',
'auto_height' => false,
'review-lines' =>filter_var($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['review-lines'], FILTER_VALIDATE_INT),
'box-background-color' => rest_parse_hex_color($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-background-color']),
'box-border-color' => rest_parse_hex_color($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-color']),
'box-padding' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-padding']), FILTER_VALIDATE_INT),
'box-border-top-width' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-top-width']), FILTER_VALIDATE_INT),
'box-border-left-width' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-left-width']), FILTER_VALIDATE_INT),
'box-border-right-width' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-right-width']), FILTER_VALIDATE_INT),
'box-border-bottom-width' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-bottom-width']), FILTER_VALIDATE_INT),
'box-border-radius' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['box-border-radius']), FILTER_VALIDATE_INT),
'profile-font-size' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['profile-font-size']), FILTER_VALIDATE_INT),
'profile-color' => rest_parse_hex_color($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['profile-color']),
'review-font-size' => filter_var(str_replace('px','',$trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['review-font-size']), FILTER_VALIDATE_INT),
'text-color' => rest_parse_hex_color($trustindex_testimonials_pm::$widget_styles[$use_appearance]['_vars']['text-color']),
);
$navigation = array(
'navigation_style' => 'arrow',
'slider_interval' => 6,
);
$widget = array(
'current' => 1,
'saved' => 0,
'1' => array(
'mode' => 'all',
'selected' => null,
'rating' => 'all',
),
'2' => null,
'3' => null,
'4' => array(
'general' => array(
'order' => 'newest',
),
'appearance' => $appearance,
'navigation' => $navigation,
'last_saved' => 'general'
),
);
$ti_command = isset($_REQUEST['command']) ? sanitize_text_field($_REQUEST['command']) : null;
$ti_command_list = [
'save-review-filter',
'save-style',
'save-set',
'save-order',
'save-display-options',
'save-box',
'save-font',
'save-slider-interval',
'save-review-lines',
'save-text-align',
'save-display_type',
'save-dateformat',
'save-navigation',
'setup-widget',
'save-arrow',
'save-dots'
];
if (!in_array($ti_command, $ti_command_list)) {
$ti_command = null;
}
if ($current_step === 1)
{
$categories = $trustindex_testimonials_pm->get_categories();
$reviews = $trustindex_testimonials_pm->get_reviews();
$next_widget_id = $trustindex_testimonials_pm->get_next_widget_id();
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
if ($name_missing == false)
{
$widget_name = $w->name;
}
$widget_value = unserialize($w->value);
$widget = $widget_value;
}
}
else
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget_name = $w->name;
$widget_value = unserialize($w->value);
$widget = $widget_value;
$reviews = [1,2];
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
if ($ti_command == 'save-review-filter') {
$success = true;
$widget['current'] = 2;
$widget_name = isset($_POST['widget-name']) ? sanitize_text_field($_POST['widget-name']) : null;
if (is_null($widget_name) || empty($widget_name))
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&id=' . esc_attr($id) . '&name-required"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&name-required"</script>';
exit;
}
}
$mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : null;
$widget['1']['mode'] = $_POST['mode'];
if ($mode === 'all')
{
$widget['1']['selected'] = null;
}
else if ($mode === 'category')
{
$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : null;
if ($category)
{
$widget['1']['selected'] = $category;
}
else
{
$success = false;
}
}
else if ($mode === 'manual_select')
{
if (isset($_POST['review']) && is_array($_POST['review']))
{
$rs = array_map( 'sanitize_text_field', $_POST['review']);
}
else
{
$rs = null;
}
if ($rs)
{
$selected_ids = array();
foreach($rs as $k => $v)
{
$r_id = intval(sanitize_text_field($v));
if ($r_id !== 0)
{
$selected_ids[] = $r_id;
}
}
$str_selected = implode(",", $selected_ids);
$widget['1']['selected'] = $str_selected;
}
else
{
$success = false;
}
}
else
{
$success = false;
}
if ($mode !== 'manual_select' && $success)
{
$rating = isset($_POST['rating']) ? sanitize_text_field($_POST['rating']) : null;
if ($rating)
{
$widget['1']['rating'] = $rating;
}
else
{
$success = false;
}
}
if ($success)
{
$widget['saved'] = 1;
$serialized_widget = serialize($widget);
if ($id)
{
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
$id = $trustindex_testimonials_pm->create_widget($widget_name, $serialized_widget);
}
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=2&id=' . esc_attr($id) . '&selected"</script>';
exit;
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($ti_command == 'save-style')
{
if ($id || $style_id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
if ($style_id == '17' || $style_id == '21')
{
$widget['current'] = 4;
$widget['saved'] = 3;
$widget['2'] = (string)$style_id;
$widget['3'] = 'light-background';
$widget['4']['appearance'] = $appearance;
$widget['4']['navigation'] = $navigation;
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=4&id=' . esc_attr($id) . '&selected&style_id=' . esc_attr($style_id) . '&scss_set=light-background"</script>';
exit;
}
else
{
$widget['current'] = 3;
$widget['2'] = (string)$style_id;
$widget['4']['appearance'] = $appearance;
$widget['4']['navigation'] = $navigation;
$widget['saved'] = 2;
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=2&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($ti_command == 'save-set')
{
if ($id || $style_id || $scss_set)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
if ($widget['saved'] < 4)
{
$widget['4']['appearance'] = $appearance;
$widget['4']['navigation'] = $navigation;
}
$widget['saved'] = 3;
$widget['3'] = $scss_set;
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
if ($id)
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=3&id=' . esc_attr($id) . '"</script>';
exit;
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
}
else if ($ti_command == 'save-order')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['general']['order'] = sanitize_text_field($_POST['order']);
$widget['4']['last_saved'] = 'general';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-display-options')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['hide_stars'] = sanitize_text_field($_POST['hide_stars']);
$widget['4']['appearance']['hide_image'] = sanitize_text_field($_POST['hide_image']);
$widget['4']['appearance']['auto_height'] = sanitize_text_field($_POST['auto_height']);
$widget['4']['appearance']['enable-font'] = sanitize_text_field($_POST['enable-font']);
$widget['4']['appearance']['hover-anim'] = sanitize_text_field($_POST['hover-anim']);
$widget['4']['appearance']['nav'] = sanitize_text_field($_POST['nav']);
$widget['4']['appearance']['dots'] = sanitize_text_field($_POST['dots']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-box')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['box-background-color'] = sanitize_hex_color($_POST['box-background-color']);
$widget['4']['appearance']['box-border-color'] = sanitize_hex_color($_POST['box-border-color']);
$widget['4']['appearance']['box-padding'] = sanitize_text_field($_POST['box-padding']);
$widget['4']['appearance']['box-border-top-width'] = sanitize_text_field($_POST['box-border-width']);
$widget['4']['appearance']['box-border-left-width'] = sanitize_text_field($_POST['box-border-width']);
$widget['4']['appearance']['box-border-right-width'] = sanitize_text_field($_POST['box-border-width']);
$widget['4']['appearance']['box-border-bottom-width'] = sanitize_text_field($_POST['box-border-width']);
$widget['4']['appearance']['box-border-radius'] = sanitize_text_field($_POST['box-border-radius']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-font')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['profile-font-size'] = sanitize_text_field($_POST['profile-font-size']);
$widget['4']['appearance']['profile-color'] = sanitize_hex_color($_POST['profile-color']);
$widget['4']['appearance']['review-font-size'] = sanitize_text_field($_POST['review-font-size']);
$widget['4']['appearance']['text-color'] = sanitize_hex_color($_POST['text-color']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-slider-interval')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['navigation']['slider_interval'] = sanitize_text_field($_POST['slider-interval']);
$widget['4']['last_saved'] = 'navigation';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-review-lines')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['review-lines'] = sanitize_text_field($_POST['review-lines']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-display_type')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['display_type'] = sanitize_text_field($_POST['display_type']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-text-align')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['text-align'] = sanitize_text_field($_POST['text_align']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-arrow')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['nav'] = sanitize_text_field($_POST['arrow']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-dots')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['dots'] = sanitize_text_field($_POST['dots']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-dateformat')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['appearance']['date_format'] = sanitize_text_field($_POST['date_format']);
$widget['4']['last_saved'] = 'appearance';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'save-navigation')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 4;
$widget['saved'] = 4;
$widget['4']['navigation']['navigation_style'] = sanitize_text_field($_POST['navigation_style']);
$widget['4']['last_saved'] = 'navigation';
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}
else if ($ti_command == 'setup-widget')
{
if ($id)
{
$w = $trustindex_testimonials_pm->get_widget($id);
$widget = unserialize($w->value);
$widget_name = $w->name;
$widget['current'] = 5;
$widget['saved'] = 5;
$serialized_widget = serialize($widget);
$trustindex_testimonials_pm->save_css($id, $widget);
$trustindex_testimonials_pm->update_widget($id, $widget_name, $serialized_widget);
}
else
{
echo '<script>location.href="admin.php?page=' . esc_attr($page) . '&step=1"</script>';
exit;
}
}