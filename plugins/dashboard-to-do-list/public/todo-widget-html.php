<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'ardtdw_widget_html' ) ) {
function ardtdw_widget_html() {
$ardtdw_TextArea = stripslashes(get_option('ardtdw-textarea'));
$ardtdw_Position = esc_html(get_option('ardtdw-position'));

$output = '<div id="ardtdw-sitewidget" class="ardtdw-sitewidget ardtdw-'.$ardtdw_Position.'">';
$output .= '<div class="ardtdw-sitewidget-inner">';
$output .= '<div class="ardtdw-sitewidget-head">';
$output .= '<p>'. __('To-Do List','dashboard-to-do-list') . '</p>';
$output .= '<div class="ardtdw-head-icons">';
$output .= '<a href="' . site_url() . '/wp-admin/" target="_blank" title="'. __('Add Job','dashboard-to-do-list') . '">+</a>';
$output .= '</div>';
$output .= '</div>';
$output .= '<div class="ardtdw-sitewidget-list">';
$output .= '<ul><li>' . str_replace(PHP_EOL,"</li><li>", stripslashes($ardtdw_TextArea)) . '</li></ul>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo $output;
}
}
?>
