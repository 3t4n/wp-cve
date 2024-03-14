<?php

/**
 * Provide a admin area form view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    size-chart-for-woocommerce
 * @subpackage size-chart-for-woocommerce/admin/partials
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// Use get_post_meta to retrieve an existing value of chart from the database.
global $post;
$chart_attributes = scfw_size_chart_get_attributes( $post->ID );
?>
<div id="size-chart-meta-fields">
	<div id="assign-tag">
		<div class="field-item">
        <select name="chart-attributes[]" id="chart-attributes" multiple="multiple">
                <?php 
                    $all_attributes = wc_get_attribute_taxonomies();
                    if ( is_array( $all_attributes ) && ! empty( $all_attributes ) ) {
                        foreach ( $all_attributes as $attribute ) { ?>
                            <optgroup label="<?php echo esc_attr( $attribute->attribute_label ); ?>">
                                <?php
                                    // Get its value for currnt attribute
                                    $attribute_values = get_terms("pa_" . $attribute->attribute_name, array('hide_empty' => false));
                                    if ( ! empty( $attribute_values ) ) {
                                        foreach ( $attribute_values as $value ) { ?>
                                            <option value="<?php echo esc_attr($value->term_id); ?>" <?php selected( true, in_array( $value->term_id, $chart_attributes, true ), true ); ?>>
                                                <?php echo esc_html($value->name) ?>
                                            </option>
                                        <?php 
                                        }
                                    }
                                ?>
                            </optgroup>
                        <?php
                        }
                    }
                ?>
            </select>
		</div>
	</div>
</div>
