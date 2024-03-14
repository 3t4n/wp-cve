<?php
/**
 * Product category edit metabox html
 *
 * @var string $weight_formula Weight formula for current product category.
 * @var string $width_formula  Width formula for current product category.
 * @var string $length_formula Length formula for current product category.
 * @var string $height_formula Height formula for current product category.
 *
 * @package NovaPosta\Templates\Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>
<tr class="form-field shipping-nova-poshta-for-woocommerce-form-field">
	<th scope="row" valign="top">
		<label for="weight_formula">
			<?php esc_attr_e( 'Weight formula', 'shipping-nova-poshta-for-woocommerce' ); ?>
			<span class="shipping-nova-poshta-for-woocommerce-pro"></span>
		</label>
	</th>
	<td>
		<span class="with-help-tip">
			<input
				type="text"
				name="weight_formula"
				id="weight_formula"
				placeholder="[qty] * 0.5"
				value=""
				disabled="disabled"
			/>
			<span
				class="help-tip"
				data-tip="<?php esc_attr_e( 'Formula cost calculation. The numbers are indicated in kilograms. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ); ?>"
			></span>
		</span>
	</td>
</tr>
<tr class="form-field shipping-nova-poshta-for-woocommerce-form-field">
	<th scope="row" valign="top">
		<label for="width_formula">
			<?php esc_attr_e( 'Width formula', 'shipping-nova-poshta-for-woocommerce' ); ?>
			<span class="shipping-nova-poshta-for-woocommerce-pro"></span>
		</label>
	</th>
	<td>
		<span class="with-help-tip">
			<input
				type="text"
				name="width_formula"
				id="width_formula"
				placeholder="[qty] * 0.26"
				value=""
				disabled="disabled"
			/>
			<span
				class="help-tip"
				data-tip="<?php esc_attr_e( 'Formula cost calculation. The numbers are indicated in kilograms. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ); ?>"
			></span>
		</span>
	</td>
</tr>
<tr class="form-field shipping-nova-poshta-for-woocommerce-form-field">
	<th scope="row" valign="top">
		<label for="length_formula">
			<?php esc_attr_e( 'Length formula', 'shipping-nova-poshta-for-woocommerce' ); ?>
			<span class="shipping-nova-poshta-for-woocommerce-pro"></span>
		</label>
	</th>
	<td>
		<span class="with-help-tip">
			<input
				type="text"
				name="length_formula"
				id="length_formula"
				placeholder="[qty] * 0.145"
				value=""
				disabled="disabled"
			/>
			<span
				class="help-tip"
				data-tip="<?php esc_attr_e( 'Formula cost calculation. The numbers are indicated in kilograms. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ); ?>"
			></span>
		</span>
	</td>
</tr>
<tr class="form-field shipping-nova-poshta-for-woocommerce-form-field">
	<th scope="row" valign="top">
		<label for="height_formula">
			<?php esc_attr_e( 'Height formula', 'shipping-nova-poshta-for-woocommerce' ); ?>
			<span class="shipping-nova-poshta-for-woocommerce-pro"></span>
		</label>
	</th>
	<td>
		<span class="with-help-tip">
			<input
				type="text"
				name="height_formula"
				id="height_formula"
				placeholder="[qty] * 0.1"
				value=""
				disabled="disabled"
			/>
			<span
				class="help-tip"
				data-tip="<?php esc_attr_e( 'Formula cost calculation. The numbers are indicated in kilograms. You can use the [qty] shortcode to indicate the number of products.', 'shipping-nova-poshta-for-woocommerce' ); ?>"
			></span>
		</span>
	</td>
</tr>
