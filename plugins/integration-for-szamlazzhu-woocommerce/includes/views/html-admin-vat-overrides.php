<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get saved values
$saved_values = get_option('wc_szamlazz_vat_overrides');

//Set valid documents for automation
$line_item_types = array(
	'product' => __('Line item(product)', 'wc-szamlazz'),
	'shipping' => __('Shipping', 'wc-szamlazz'),
	'discount' => __('Discount', 'wc-szamlazz'),
	'fee' => __('Fee', 'wc-szamlazz'),
	'refund' => __('Refund', 'wc-szamlazz')
);

//When to generate these documents
$vat_types = WC_Szamlazz_Helpers::get_vat_types();

//Apply filters
$conditions = WC_Szamlazz_Conditions::get_conditions('vat_overrides');

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings-vat-overrides">
			<?php if($saved_values): ?>
				<?php foreach ( $saved_values as $automation_id => $automation ): ?>
					<div class="wc-szamlazz-settings-vat-override wc-szamlazz-settings-repeat-item">
						<div class="wc-szamlazz-settings-vat-override-title">
							<span class="text"><?php esc_html_e('Set', 'wc-szamlazz'); ?></span>
							<div class="select-field">
								<label><span>-</span></label>
								<select class="wc-szamlazz-settings-vat-override-line-item wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_vat_overrides[X][line_item]">
									<?php foreach ($line_item_types as $value => $label): ?>
										<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['line_item'])) selected( $automation['line_item'], $value ); ?>><?php echo esc_html($label); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<span class="text"><?php esc_html_e('vat type to', 'wc-szamlazz'); ?></span>
							<div class="select-field">
								<label><span>-</span></label>
								<select class="wc-szamlazz-settings-vat-override-vat-type wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_vat_overrides[X][vat_type]">
									<?php foreach ($vat_types as $value => $label): ?>
										<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['vat_type'])) selected( $automation['vat_type'], $value ); ?>><?php echo esc_html($label); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<a href="#" class="delete-vat-override"><?php _e('delete', 'wc-szamlazz'); ?></a>
						</div>
						<div class="wc-szamlazz-settings-vat-override-if">
							<div class="wc-szamlazz-settings-vat-override-if-header">
								<label>
									<input type="checkbox" data-name="wc_szamlazz_vat_overrides[X][condition_enabled]" <?php checked( $automation['conditional'] ); ?> class="condition" value="yes">
									<span><?php _e('Based on the following conditions, if', 'wc-szamlazz'); ?></span>
								</label>
								<select data-name="wc_szamlazz_vat_overrides[X][logic]">
									<option value="and" <?php if(isset($automation['logic'])) selected( $automation['logic'], 'and' ); ?>><?php _e('All', 'wc-szamlazz'); ?></option>
									<option value="or" <?php if(isset($automation['logic'])) selected( $automation['logic'], 'or' ); ?>><?php _e('One', 'wc-szamlazz'); ?></option>
								</select>
								<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
							</div>
							<ul class="wc-szamlazz-settings-vat-override-if-options conditions" <?php if(!$automation['conditional']): ?>style="display:none"<?php endif; ?> <?php if(isset($automation['conditions'])): ?>data-options="<?php echo esc_attr(json_encode($automation['conditions'])); ?>"<?php endif; ?>></ul>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="wc-szamlazz-settings-vat-override-add">
			<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add new override', 'wc-szamlazz'); ?></span></a>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_vat_override_sample_row">
	<div class="wc-szamlazz-settings-vat-override wc-szamlazz-settings-repeat-item">
		<div class="wc-szamlazz-settings-vat-override-title">
			<span class="text"><?php esc_html_e('Set', 'wc-szamlazz'); ?></span>
			<div class="select-field">
				<label><span>-</span></label>
				<select class="wc-szamlazz-settings-vat-override-line-item wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_vat_overrides[X][line_item]">
					<?php foreach ($line_item_types as $value => $label): ?>
						<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<span class="text"><?php esc_html_e('vat type to', 'wc-szamlazz'); ?></span>
			<div class="select-field">
				<label><span>-</span></label>
				<select class="wc-szamlazz-settings-vat-override-vat-type wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_vat_overrides[X][vat_type]">
					<?php foreach ($vat_types as $value => $label): ?>
						<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<a href="#" class="delete-vat-override"><?php _e('delete', 'wc-szamlazz'); ?></a>
		</div>
		<div class="wc-szamlazz-settings-vat-override-if">
			<div class="wc-szamlazz-settings-vat-override-if-header">
				<label>
					<input type="checkbox" data-name="wc_szamlazz_vat_overrides[X][condition_enabled]" class="condition" value="yes">
					<span><?php _e('Based on the following conditions:', 'wc-szamlazz'); ?></span>
				</label>
				<select data-name="wc_szamlazz_vat_overrides[X][logic]">
					<option value="and"><?php _e('All', 'wc-szamlazz'); ?></option>
					<option value="or"><?php _e('One', 'wc-szamlazz'); ?></option>
				</select>
				<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
			</div>
			<ul class="wc-szamlazz-settings-vat-override-if-options conditions" style="display:none"></ul>
		</div>
	</div>
</script>

<?php echo WC_Szamlazz_Conditions::get_sample_row('vat_overrides'); ?>
