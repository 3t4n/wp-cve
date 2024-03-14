<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get saved values
$saved_values = get_option('wc_szamlazz_advanced_options');

//Set valid documents for automation
$line_item_types = array(
	'bank_name' => __('Bank name', 'wc-szamlazz'),
	'bank_number' => __('Bank account number', 'wc-szamlazz'),
	'prefix' => __('Invoice prefix', 'wc-szamlazz'),
	'language' => __('Language', 'wc-szamlazz'),
);

//Setup bank accounts
$languages = WC_Szamlazz_Helpers::get_supported_languages();

$line_item_options = array(
	'bank_name' => '',
	'bank_number' => '',
	'prefix' => '',
	'language' => $languages
);

//Apply filters
$conditions = WC_Szamlazz_Conditions::get_conditions('advanced_options');

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings-advanced-options">
			<?php if($saved_values): ?>
				<?php foreach ( $saved_values as $automation_id => $automation ): ?>
					<div class="wc-szamlazz-settings-advanced-option wc-szamlazz-settings-repeat-item">
						<div class="wc-szamlazz-settings-advanced-option-title">
							<span class="text"><?php echo esc_html_x('Set', 'advanced-options', 'wc-szamlazz'); ?></span>
							<div class="select-field">
								<label><span>-</span></label>
								<select class="wc-szamlazz-settings-advanced-option-property wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_advanced_options[X][property]">
									<?php foreach ($line_item_types as $value => $label): ?>
										<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['property'])) selected( $automation['property'], $value ); ?>><?php echo esc_html($label); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<span class="text"><?php echo esc_html_x('to', 'advanced-options', 'wc-szamlazz'); ?></span>
							<a href="#" class="delete-advanced-option"><?php _e('delete', 'wc-szamlazz'); ?></a>
						</div>
						<div class="wc-szamlazz-settings-advanced-option-options">
							<?php foreach ($line_item_options as $line_item_id => $line_item_value): ?>
								<div class="wc-szamlazz-settings-advanced-option-option option-<?php echo $line_item_id; ?>">
									<?php if($line_item_value == ''): ?>
										<input type="text" class="property-value" data-name="wc_szamlazz_advanced_options[X][value]" value="<?php echo esc_attr($automation['value']); ?>">
									<?php else: ?>
										<select class="property-value" data-name="wc_szamlazz_advanced_options[X][value]">
											<option value="">-</option>
											<?php foreach ($line_item_value as $value => $label): ?>
												<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['value'])) selected( $automation['value'], $value ); ?>><?php echo esc_html($label); ?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="wc-szamlazz-settings-advanced-option-if">
							<div class="wc-szamlazz-settings-advanced-option-if-header">
								<label>
									<input type="checkbox" data-name="wc_szamlazz_advanced_options[X][condition_enabled]" class="condition" value="yes" checked style="display:none;">
									<span><?php _e('Based on the following conditions, if', 'wc-szamlazz'); ?></span>
								</label>
								<select data-name="wc_szamlazz_advanced_options[X][logic]">
									<option value="and" <?php if(isset($note['logic'])) selected( $note['logic'], 'and' ); ?>><?php _e('All', 'wc-szamlazz'); ?></option>
									<option value="or" <?php if(isset($note['logic'])) selected( $note['logic'], 'or' ); ?>><?php _e('One', 'wc-szamlazz'); ?></option>
								</select>
								<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
							</div>
							<ul class="wc-szamlazz-settings-advanced-option-if-options conditions" <?php if(isset($automation['conditions'])): ?>data-options="<?php echo esc_attr(json_encode($automation['conditions'])); ?>"<?php endif; ?>></ul>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="wc-szamlazz-settings-advanced-option-add">
			<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add new', 'wc-szamlazz'); ?></span></a>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_advanced_option_sample_row">
	<div class="wc-szamlazz-settings-advanced-option wc-szamlazz-settings-repeat-item">
		<div class="wc-szamlazz-settings-advanced-option-title">
			<span class="text"><?php echo esc_html_x('Set', 'advanced-options', 'wc-szamlazz'); ?></span>
			<div class="select-field">
				<label><span>-</span></label>
				<select class="wc-szamlazz-settings-advanced-option-property wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_advanced_options[X][property]">
					<?php foreach ($line_item_types as $value => $label): ?>
						<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<span class="text"><?php echo esc_html_x('to', 'advanced-options', 'wc-szamlazz'); ?></span>
			<a href="#" class="delete-advanced-option"><?php _e('delete', 'wc-szamlazz'); ?></a>
		</div>
		<div class="wc-szamlazz-settings-advanced-option-options">
			<?php foreach ($line_item_options as $line_item_id => $line_item_value): ?>
				<div class="wc-szamlazz-settings-advanced-option-option option-<?php echo $line_item_id; ?>">
					<?php if($line_item_value == ''): ?>
						<input type="text" class="property-value" data-name="wc_szamlazz_advanced_options[X][value]" value="">
					<?php else: ?>
						<select class="property-value" data-name="wc_szamlazz_advanced_options[X][value]">
							<?php foreach ($line_item_value as $value => $label): ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="wc-szamlazz-settings-advanced-option-if">
			<div class="wc-szamlazz-settings-advanced-option-if-header">
				<label>
					<input type="checkbox" data-name="wc_szamlazz_advanced_options[X][condition_enabled]" class="condition" value="yes" checked style="display:none;">
					<span><?php _e('Based on the following conditions, if', 'wc-szamlazz'); ?></span>
				</label>
				<select data-name="wc_szamlazz_advanced_options[X][logic]">
					<option value="and"><?php _e('All', 'wc-szamlazz'); ?></option>
					<option value="or"><?php _e('One', 'wc-szamlazz'); ?></option>
				</select>
				<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
			</div>
			<ul class="wc-szamlazz-settings-advanced-option-if-options conditions"></ul>
		</div>
	</div>
</script>

<?php echo WC_Szamlazz_Conditions::get_sample_row('advanced_options'); ?>
