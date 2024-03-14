<?php
/**
 * This file show the search field
 *
 * @package YITH\Search\Views\CustomFields
 *
 * @var array $field
 * @var bool  $is_placeholder
 * @var int   $key
 */

$curr_id            = isset( $is_placeholder ) && $is_placeholder ? '{{data.id}}' : $key;
$search_field_types = ywcas_get_search_fields_type();
if ( ! in_array( $field['type'], array_keys( $search_field_types ) ) ) {
	return;
}
$disabled_options = apply_filters( 'ywcas_disable_search_input_options', array() );
?>

<tr class="ywcas-search-field">
    <td class="field">
		<span class="search-field-type">
		<?php
		yith_plugin_fw_get_field(
			array(
				'id'      => 'ywcas-search-field-type__' . $curr_id,
				'name'    => 'ywcas-search-fields[' . $curr_id . '][type]',
				'class'   => 'ywcas-search-field-type wc-enhanced-select',
				'type'    => 'select',
				'options' => $search_field_types,
				'value'   => $field['type'],
			),
			true,
			false
		);
		?>
			</span>
        <span class="search-field-type-condition" data-type="product_categories">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'               => 'ywcas-search-field-product_category-condition__' . $curr_id,
					'name'             => 'ywcas-search-fields[' . $curr_id . '][product_category_condition]',
					'class'            => 'ywcas-search-condition product_category-condition wc-enhanced-select',
					'type'             => 'select',
					'options'          => array(
						'all'     => _x( 'Enable all categories', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
						'include' => _x( 'Enable specific categories', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
						'exclude' => _x( 'Disable specific categories', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
					),
					'disabled_options' => $disabled_options,
					'default'          => 'all',
					'value'            => $field['product_category_condition'] ?? 'all',
				),
				true,
				false
			);
			?>
		</span>
        <span class="search-field-type-condition" data-type="product_tags">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'               => 'ywcas-search-field-product_tag-condition__' . $curr_id,
					'name'             => 'ywcas-search-fields[' . $curr_id . '][product_tag_condition]',
					'class'            => 'ywcas-search-condition product_tag-condition wc-enhanced-select',
					'type'             => 'select',
					'options'          => array(
						'all'     => _x( 'Enable all tags', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
						'include' => _x( 'Enable specific tags', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
						'exclude' => _x( 'Disable specific tags', '[admin]option to select', 'yith-woocommerce-ajax-search' ),
					),
					'disabled_options' => $disabled_options,
					'value'            => $field['product_tag_condition'] ?? 'all',
				),
				true,
				false
			);
			?>
		</span>
        <span class="search-field-type-list search-field-type-custom-field-list" data-type="custom_fields">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'ywcas-search-field-custom_field-list__' . $curr_id,
					'name'  => 'ywcas-search-fields[' . $curr_id . '][custom_field_list]',
					'class' => 'yith-term-search select-custom_field',
					'type'  => 'ajax-wcas-custom-fields',
					'value' => $field['custom_field_list'] ?? array(),
				),
				true,
				false
			);
			?>
		</span>
        <span class="search-field-type-list search-field-type-product-attribute-list" data-type="product_attributes">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'    => 'ywcas-search-field-product_attribute-list__' . $curr_id,
					'name'  => 'ywcas-search-fields[' . $curr_id . '][product_attribute_list]',
					'type'  => 'ajax-wcas-product-attributes',
					'class' => 'yith-term-search select-product_attribute',
					'value' => $field['product_attribute_list'] ?? array(),
				),
				true,
				false
			);
			?>
		</span>
		<?php do_action( 'ywcas_search_input_field_template_conditions', $curr_id, $field ); ?>
    </td>
    <td class="priority">
		<span class="search-field-priority">
		<?php
		yith_plugin_fw_get_field(
			array(
				'id'    => 'ywcas-search-field-priority__' . $curr_id,
				'name'  => 'ywcas-search-fields[' . $curr_id . '][priority]',
				'class' => 'ywcas-search-priority',
				'type'  => 'number',
				'min'   => 1,
				'max'   => 50,
				'step'  => 1,
				'value' => $field['priority'],
			),
			true,
			false
		);
		?>
		</span>
        <span class="search-field-type-list search-field-type-category-list " data-subtype="product_categories">
			<?php
			yith_plugin_fw_get_field(
				array(
					'id'       => 'ywcas-search-field-category-list_' . $curr_id,
					'name'     => 'ywcas-search-fields[' . $curr_id . '][category-list]',
					'type'     => 'ajax-terms',
					'class'    => 'yith-term-search select-product_categories',
					'data'     => array(
						'taxonomy' => 'product_cat',
					),
					'multiple' => true,
					'value'    => $field['category-list'] ?? array(),
				),
				true,
				false
			);
			?>
			</span>
        <span class="search-field-type-list search-field-type-tag-list" data-subtype="product_tags">
			<?php

			yith_plugin_fw_get_field(
				array(
					'id'       => 'ywcas-search-field-tag-list_' . $curr_id,
					'name'     => 'ywcas-search-fields[' . $curr_id . '][tag-list]',
					'class'    => 'yith-term-search select-product_tag',
					'type'     => 'ajax-terms',
					'data'     => array(
						'taxonomy'    => 'product_tag',
						'placeholder' => __( 'Search for a tag...', 'yith-woocommerce-ajax-search' ),
					),
					'multiple' => true,
					'value'    => $field['tag-list'] ?? array(),
				),
				true,
				false
			);
			?>
		</span>
		<?php do_action( 'ywcas_search_input_field_template_list', $curr_id, $field ); ?>
    </td>
    <td class="actions">
		<?php
		yith_plugin_fw_get_action_buttons(
			array(
				array(
					'type'   => 'action-button',
					'title'  => _x( 'Delete', 'Tip to delete the sender info', 'yith-woocommerce-ajax-search' ),
					'icon'   => 'trash',
					'url'    => '',
					'action' => 'delete',
					'class'  => 'action__trash',
				),
			),
			true
		);
		?>
    </td>
</tr>
