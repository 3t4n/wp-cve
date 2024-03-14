<?php
defined( 'ABSPATH' ) || exit;
$yay_swatches_product_attributes = array(
	'pa_size'  => array(
		'attribute_label' => __( 'Size', 'yay-swatches' ),
		'type'            => 'default',
	),
	'pa_color' => array(
		'attribute_label' => __( 'Color', 'yay-swatches' ),
		'type'            => 'custom',
	),
);
foreach ( $yay_swatches_product_attributes as $attr_id_or_slug => $yay_swatches_attribute ) : ?>
	<div class="yay-swatches-product-attribute-wrapper wc-metabox closed">
		<h3 class="yay-swatches-product-attribute-header">
			<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'yay-swatches' ); ?>"></div>
			<strong class="attribute_name"><?php echo esc_html( $yay_swatches_attribute['attribute_label'] ); ?></strong>
			<div class="yay-swatches-product-attribute-type-wrapper">
				<strong><?php esc_html_e( 'Type', 'yay-swatches' ); ?></strong>
				<select class="yay-swatches-attribute-type-select">
					<option value="default" <?php selected( $yay_swatches_attribute['type'], 'default' ); ?>><?php echo esc_html__( 'Default', 'yay-swatches' ); ?></option>
					<option value="custom" <?php selected( $yay_swatches_attribute['type'], 'custom' ); ?>><?php echo esc_html__( 'Color or Custom Image swatch', 'yay-swatches' ); ?></option>
				</select>
			</div>
		</h3>
		<div class="yay-swatches-product-attribute-content wc-metabox-content" style="display: none">
			<table cellpadding="0" cellspacing="0" class="yay-swatches-product-attribute-table">
				<tbody class="yay-swatches-product-attribute-tbody">
					<tr class="yay-swatches-product-attribute-tr">
						<td class="yay-swatches-product-attribute-td yay-swatches-is_show_archive_page-td">
							<strong><?php esc_html_e( 'Display on Shop / Categories page', 'yay-swatches' ); ?></strong>
							<select style="width: 220px !important;" class="yay-swatches-is_show_archive_page-select">
								<option value="default"><?php echo esc_html__( 'Default', 'yay-swatches' ); ?></option>
							</select>
						</td>
						<td class="yay-swatches-is_show_archive_page-td"></td>
					</tr>				
						
				</tbody>
			</table>
		</div>
	</div>
<?php endforeach; ?>
