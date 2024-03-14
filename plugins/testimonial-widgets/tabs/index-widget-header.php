<?php
defined('ABSPATH') or die('No script kiddies please!');
$search = null;
if(isset($_GET['search']))
{
$search = sanitize_text_field($_GET['search']);
}
if(isset($_GET['order_by']) && isset($_GET['order']))
{
$order_by = sanitize_text_field($_GET['order_by']);
$order = sanitize_text_field($_GET['order']);
if (($order_by == 'id' || $order_by == 'name') && ($order == 'asc' || $order == 'desc'))
{
$widgets = $trustindex_testimonials_pm->get_widgets($order_by, $order, $search);
$order_setting = array(
'id' => array(
'active' => $order_by == 'id' ? true : false,
'order' => ($order_by == 'id' && $order == 'asc') ? 'desc' : 'asc',
),
'name' => array(
'active' => $order_by == 'name' ? true : false,
'order' => ($order_by == 'name' && $order == 'asc') ? 'desc' : 'asc',
)
);
}
else
{
}
}
else
{
$widgets = $trustindex_testimonials_pm->get_widgets('id', 'desc', $search);
$order_setting = array(
'id' => array(
'active' => true,
'order' => 'asc',
),
'name' => array(
'active' => false,
'order' => 'asc',
)
);
}
if(isset($_GET['delete_nonce']) && wp_verify_nonce($_GET['delete_nonce'],'delete_widget') && is_user_logged_in() && isset($_GET['delete_widget']))
{
$id = sanitize_text_field($_GET['delete_widget']);
$success = $trustindex_testimonials_pm->delete_widget($id);
if ($success)
{
echo '<script>location.href="admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php"</script>';
exit;
}
else
{
}
}
if(isset($_GET['duplicate_widget']))
{
$id = sanitize_text_field($_GET['duplicate_widget']);
$success = $trustindex_testimonials_pm->duplicate_widget($id);
if ($success)
{
echo '<script>location.href="admin.php?page=' . esc_attr($trustindex_testimonials_pm->get_plugin_slug()) . '/tabs/index-widget.php"</script>';
exit;
}
else
{
}
}