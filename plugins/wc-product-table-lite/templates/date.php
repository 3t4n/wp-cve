<?php
if (!defined('ABSPATH')) {
	exit;
}

// legacy
if (empty($format)) {
	$format = 'j M, Y';
}

if (empty($date_source)) {
	$date_source = 'publish_date';
}

$date = '';

// wp custom field
if ($date_source == 'wordpress_custom_field') {

	// missing custom field name
	if (empty($custom_field_name)) {
		return;
	}

	// missing value
	if (!$custom_field_value = get_post_meta($product->get_id(), $custom_field_name, true)) {
		return;
	}

	if (empty($custom_field_type)) {
		$custom_field_type = 'numeric';
	}

	if (in_array($custom_field_type, array('date', 'datetime'))) {
		$timestamp = strtotime($custom_field_value);
	} else {
		$timestamp = $custom_field_value;
	}

	$date = date($format, $timestamp);

	// acf custom field	
} else if ($date_source == 'acf_custom_field') {

	// missing acf field name
	if (empty($acf_field_name)) {
		return;
	}

	// ensure field type
	if (empty($acf_field_type)) {
		$acf_field_type = 'date_picker';
	}

	// missing value
	if (!$custom_field_value = get_post_meta($product->get_id(), $acf_field_name, true)) {
		return;
	}

	$date = date($format, strtotime($custom_field_value));

	// publish date
} else if ($date_source = 'publish_date') {
	$date = get_the_date($format);

}

if (!$date) {
	return;
}

echo '<span class="wcpt-date ' . $html_class . '">' . $date . '</span>';
