<?php
/**
 * Template for displaying the heading field
 *
 * @var array $field The field.
 *
 * @package XT_Framework_Settings\Fields
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if(!empty($field['notice'])) {
    $variant = !empty($field['variant']) && in_array($field['variant'], array('info', 'warning', 'error')) ? $field['variant'] : 'info';
	echo '<div class="xtfw-settings-notice notice-'.esc_attr($variant).'">
	    <p>'.$field['notice'].'</p>
	</div>';
}
