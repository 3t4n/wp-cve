<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$saved_values = get_option('wc_szamlazz_accounting_details');
$categories = get_terms(array(
	'taxonomy' => 'product_cat',
	'parent' => 0
));

//Add default category
array_unshift($categories, (object) array("term_id" => "default", "name" => __('Default values', 'wc-szamlazz')));

//Add shipping options
$shipping_methods = WC()->shipping->get_shipping_methods();
foreach ($shipping_methods as $key => $method) {
	array_push($categories, (object) array("term_id" => $key, "name" => $method->method_title));
}
?>
<tr valign="top" id="wc_szamlazz_accounting_details_table">
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>" colspan="2" style="padding:0">

		<div class="wc-szamlazz-settings–inline-table-scroll wc-szamlazz-settings–inline-table-scroll-accounting">
			<table class="wc-szamlazz-settings–inline-table wc-szamlazz-settings–inline-table-accounting">
				<thead>
					<tr>
						<th></th>
						<th colspan="4" class="border-right"><?php _e('For Hungarian billing address', 'wc-szamlazz'); ?></th>
						<th colspan="4"><?php _e('For international billing address', 'wc-szamlazz'); ?></th>
					</tr>
				</thead>
				<thead>
					<tr>
						<th></th>
						<th><?php esc_html_e( 'Ledger number', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'Income ledger number', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'Economic event', 'wc-szamlazz' ); ?></th>
						<th class="border-right"><?php esc_html_e( 'VAT Economic event', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'Ledger number', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'Income ledger number', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'Economic event', 'wc-szamlazz' ); ?></th>
						<th><?php esc_html_e( 'VAT Economic event', 'wc-szamlazz' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($categories as $category): ?>
						<?php
						if($saved_values && isset($saved_values[esc_attr( $category->term_id )])) {
							$value_afa_fokonyvi_szam_hu = esc_attr( $saved_values[esc_attr( $category->term_id )]['afa_fokonyvi_szam_hu']);
							$value_fokonyvi_szam_hu = esc_attr( $saved_values[esc_attr( $category->term_id )]['fokonyvi_szam_hu']);
							$value_gazd_esem_hu = esc_attr( $saved_values[esc_attr( $category->term_id )]['gazd_esem_hu']);
							$value_afa_gazd_esem_hu = esc_attr( $saved_values[esc_attr( $category->term_id )]['afa_gazd_esem_hu']);
							$value_afa_fokonyvi_szam_kulfold = esc_attr( $saved_values[esc_attr( $category->term_id )]['afa_fokonyvi_szam_kulfold']);
							$value_fokonyvi_szam_kulfold = esc_attr( $saved_values[esc_attr( $category->term_id )]['fokonyvi_szam_kulfold']);
							$value_gazd_esem_kulfold = esc_attr( $saved_values[esc_attr( $category->term_id )]['gazd_esem_kulfold']);
							$value_afa_gazd_esem_kulfold = esc_attr( $saved_values[esc_attr( $category->term_id )]['afa_gazd_esem_kulfold']);
						} else {
							$value_afa_fokonyvi_szam_hu = '';
							$value_fokonyvi_szam_hu = '';
							$value_gazd_esem_hu = '';
							$value_afa_gazd_esem_hu = '';
							$value_afa_fokonyvi_szam_kulfold = '';
							$value_fokonyvi_szam_kulfold = '';
							$value_gazd_esem_kulfold = '';
							$value_afa_gazd_esem_kulfold = '';
						}
						?>
						<tr>
							<td><strong style="padding-left:10px;"><?php echo $category->name; ?></strong></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][afa_fokonyvi_szam_hu]" value="<?php echo $value_afa_fokonyvi_szam_hu; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][fokonyvi_szam_hu]" value="<?php echo $value_fokonyvi_szam_hu; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][gazd_esem_hu]" value="<?php echo $value_gazd_esem_hu; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][afa_gazd_esem_hu]" value="<?php echo $value_afa_gazd_esem_hu; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][afa_fokonyvi_szam_kulfold]" value="<?php echo $value_afa_fokonyvi_szam_kulfold; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][fokonyvi_szam_kulfold]" value="<?php echo $value_fokonyvi_szam_kulfold; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][gazd_esem_kulfold]" value="<?php echo $value_gazd_esem_kulfold; ?>" /></td>
							<td><input type="text" name="wc_szamlazz_accounting_details[<?php echo esc_attr( $category->term_id ); ?>][afa_gazd_esem_kulfold]" value="<?php echo $value_afa_gazd_esem_kulfold; ?>" /></td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</td>
</tr>
