<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

$table_data = wcpt_get_table_data();

$html_class = trim(apply_filters('wcpt_container_html_class', 'wcpt wcpt-' . $table_data['id'] . ' ' . trim($this->attributes['class']) . ' ' . trim($this->attributes['html_class'])));

ob_start();
?>
data-wcpt-table-id="
<?php echo $table_data['id']; ?>"
data-wcpt-query-string="
<?php echo esc_attr(wcpt_get_table_query_string()); ?>"
data-wcpt-sc-attrs="
<?php echo esc_attr(json_encode($table_data['query']['sc_attrs'])); ?>"
<?php
$attributes = apply_filters('wcpt_container_html_attributes', ob_get_clean());
?>
<div id="wcpt-<?php echo $table_data['id']; ?>" class="<?php echo $html_class; ?>" <?php echo $attributes; ?>>