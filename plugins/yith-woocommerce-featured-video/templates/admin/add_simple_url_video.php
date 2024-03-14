<?php // phpcs:ignore WordPress.Files.FileName
/**
 * This is the field to set the video url in admin
 *
 * @package YITH WooCommerce Featured Video Audio Video Content\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<div class="options_group">';

woocommerce_wp_text_input(
	array(
		'id'          => $id,
		'label'       => $label,
		'placeholder' => $placeholder,
		'desc_tip'    => $desc_tip,
		'description' => $description,
	)
);

echo '</div>';
