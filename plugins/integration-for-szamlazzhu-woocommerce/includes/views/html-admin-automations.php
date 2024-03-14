<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Get saved values
$saved_values = get_option('wc_szamlazz_automations');

//Set valid documents for automation
$document_types = array(
	'invoice' => __('Invoice', 'wc-szamlazz'),
	'proform' => __('Proforma invoice', 'wc-szamlazz'),
	'deposit' => __('Deposit invoice', 'wc-szamlazz'),
	'void' => __('Reverse invoice', 'wc-szamlazz'),
	'delivery' => __('Delivery note', 'wc-szamlazz'),
	'paid' => __('Mark as paid', 'wc-szamlazz')
);

//When to generate these documents
$trigger_types = array(
	'order_created' => _x('When order created', 'Automation trigger', 'wc-szamlazz'),
	'payment_complete' => _x('On successful payment', 'Automation trigger', 'wc-szamlazz')
);

//Merge with order status settings
foreach ($this->get_order_statuses() as $key => $label) {
	$trigger_types[$key] = sprintf( esc_html__( 'after %1$s status', 'wc-szamlazz' ), $label);
}

//Set custom completion dates
$complete_date_types = array(
	'order_created' => _x('Order created', 'Invoice complete date type', 'wc-szamlazz'),
	'payment_complete' => _x('Payment complete', 'Invoice complete date type', 'wc-szamlazz'),
	'now' => _x('Document created', 'Invoice complete date type', 'wc-szamlazz'),
);

//Set custom payment deadline dates
$deadline_date_types = array(
	'completion' => _x('Completion date', 'Invoice complete date type', 'wc-szamlazz'),
	'order_created' => _x('Order created', 'Invoice complete date type', 'wc-szamlazz'),
	'payment_complete' => _x('Payment complete', 'Invoice complete date type', 'wc-szamlazz'),
	'now' => _x('Document created', 'Invoice complete date type', 'wc-szamlazz'),
);

//Apply filters
$conditions = WC_Szamlazz_Conditions::get_conditions('automations');

?>

<tr valign="top">
	<th scope="row" class="titledesc"><?php echo esc_html( $data['title'] ); ?></th>
	<td class="forminp <?php echo esc_attr( $data['class'] ); ?>">
		<div class="wc-szamlazz-settings-automations">
			<?php if($saved_values): ?>
				<?php foreach ( $saved_values as $automation_id => $automation ): ?>

					<div class="wc-szamlazz-settings-automation wc-szamlazz-settings-repeat-item">
						<div class="wc-szamlazz-settings-automation-title">
							<div class="select-field">
								<label>
									<i class="icon"></i>
									<span>-</span>
								</label>
								<select class="wc-szamlazz-settings-automation-document wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_automations[X][document]">
									<?php foreach ($document_types as $value => $label): ?>
										<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['document'])) selected( $automation['document'], $value ); ?>><?php echo esc_html($label); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<span class="text"><?php esc_html_e('creation', 'wc-szamlazz'); ?></span>
							<div class="select-field">
								<label>
									<span>-</span>
								</label>
								<select class="wc-szamlazz-settings-automation-trigger wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_automations[X][trigger]">
									<?php foreach ($trigger_types as $value => $label): ?>
										<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['trigger'])) selected( $automation['trigger'], $value ); ?>><?php echo esc_html($label); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<label class="conditional-toggle">
								<input type="checkbox" data-name="wc_szamlazz_automations[X][condition_enabled]" <?php checked( $automation['conditional'] ); ?> class="condition" value="yes">
								<span><?php esc_html_e('Conditional logic', 'wc-szamlazz'); ?></span>
							</label>
							<a href="#" class="delete-automation"><?php _e('delete', 'wc-szamlazz'); ?></a>
						</div>
						<div class="wc-szamlazz-settings-automation-options">
							<div class="wc-szamlazz-settings-automation-option">
								<label><?php esc_html_e('Completion date','wc-szamlazz'); ?></label>
								<div class="wc-szamlazz-settings-automation-option-complete">
									<select data-name="wc_szamlazz_automations[X][complete]">
										<?php foreach ($complete_date_types as $value => $label): ?>
											<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['complete'])) selected( $automation['complete'], $value ); ?>><?php echo esc_html($label); ?></option>
										<?php endforeach; ?>
									</select>
									<input type="text" data-name="wc_szamlazz_automations[X][complete_delay]" value="<?php echo esc_attr($automation['complete_delay']); ?>">
									<small><?php esc_html_e('± day', 'wc-szamlazz'); ?></small>
								</div>
							</div>

							<div class="wc-szamlazz-settings-automation-option">
								<label><?php esc_html_e('Payment deadline','wc-szamlazz'); ?></label>
								<div class="wc-szamlazz-settings-automation-option-complete">
									<select data-name="wc_szamlazz_automations[X][deadline_start]">
										<?php foreach ($deadline_date_types as $value => $label): ?>
											<option value="<?php echo esc_attr($value); ?>" <?php if(isset($automation['deadline_start'])) selected( $automation['deadline_start'], $value ); ?>><?php echo esc_html($label); ?></option>
										<?php endforeach; ?>
									</select>
									<input type="text" data-name="wc_szamlazz_automations[X][deadline]" value="<?php echo esc_attr($automation['deadline']); ?>">
									<small><?php esc_html_e('± day', 'wc-szamlazz'); ?></small>
								</div>
							</div>
							<div class="wc-szamlazz-settings-automation-option">
								<label><?php esc_html_e( 'Mark as paid', 'wc-szamlazz' ); ?></label>
								<input type="checkbox" value="yes" data-name="wc_szamlazz_automations[X][paid]" <?php checked( $automation['paid'] ); ?>>
							</div>
							<div class="wc-szamlazz-settings-automation-option">
								<label><?php esc_html_e( 'Unique ID', 'wc-szamlazz' ); ?></label>
								<input type="text" data-name="wc_szamlazz_automations[X][id]" value="<?php echo esc_attr($automation['id']); ?>">
							</div>
						</div>
						<div class="wc-szamlazz-settings-automation-if" <?php if(!$automation['conditional']): ?>style="display:none"<?php endif; ?>>
							<div class="wc-szamlazz-settings-automation-if-header">
								<span><?php _e('Run this automation, if', 'wc-szamlazz'); ?></span>
								<select data-name="wc_szamlazz_automations[X][logic]">
									<option value="and" <?php if(isset($automation['logic'])) selected( $automation['logic'], 'and' ); ?>><?php _e('All', 'wc-szamlazz'); ?></option>
									<option value="or" <?php if(isset($automation['logic'])) selected( $automation['logic'], 'or' ); ?>><?php _e('One', 'wc-szamlazz'); ?></option>
								</select>
								<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
							</div>
							<ul class="wc-szamlazz-settings-automation-if-options conditions" <?php if(isset($automation['conditions'])): ?>data-options="<?php echo esc_attr(json_encode($automation['conditions'])); ?>"<?php endif; ?>></ul>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="wc-szamlazz-settings-automation-add">
			<a href="#"><span class="dashicons dashicons-plus-alt"></span> <span><?php _e('Add new automation', 'wc-szamlazz'); ?></span></a>
		</div>
		<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
	</td>
</tr>

<script type="text/html" id="wc_szamlazz_automation_sample_row">
	<div class="wc-szamlazz-settings-automations">
		<div class="wc-szamlazz-settings-automation wc-szamlazz-settings-repeat-item">
			<div class="wc-szamlazz-settings-automation-title">
				<div class="select-field">
					<label>
						<i class="icon"></i>
						<span>-</span>
					</label>
					<select class="wc-szamlazz-settings-automation-document wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_automations[X][document]">
						<?php foreach ($document_types as $value => $label): ?>
							<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<span class="text"><?php esc_html_e('creation', 'wc-szamlazz'); ?></span>
				<div class="select-field">
					<label>
						<span>-</span>
					</label>
					<select class="wc-szamlazz-settings-automation-trigger wc-szamlazz-settings-repeat-select" data-name="wc_szamlazz_automations[X][trigger]">
						<?php foreach ($trigger_types as $value => $label): ?>
							<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<label class="conditional-toggle">
					<input type="checkbox" data-name="wc_szamlazz_automations[X][condition_enabled]" class="condition" value="yes">
					<span><?php esc_html_e('Conditional logic', 'wc-szamlazz'); ?></span>
				</label>
				<a href="#" class="delete-automation"><?php _e('delete', 'wc-szamlazz'); ?></a>
			</div>
			<div class="wc-szamlazz-settings-automation-options">
				<div class="wc-szamlazz-settings-automation-option">
					<label><?php esc_html_e('Completion date','wc-szamlazz'); ?></label>
					<div class="wc-szamlazz-settings-automation-option-complete">
						<select data-name="wc_szamlazz_automations[X][complete]">
							<?php foreach ($complete_date_types as $value => $label): ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
							<?php endforeach; ?>
						</select>
						<input type="text" value="0" data-name="wc_szamlazz_automations[X][complete_delay]">
						<small><?php esc_html_e('± day', 'wc-szamlazz'); ?></small>
					</div>
				</div>
				<div class="wc-szamlazz-settings-automation-option">
					<label><?php esc_html_e('Payment deadline','wc-szamlazz'); ?></label>
					<div class="wc-szamlazz-settings-automation-option-complete">
						<select data-name="wc_szamlazz_automations[X][deadline_start]">
							<?php foreach ($deadline_date_types as $value => $label): ?>
								<option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($label); ?></option>
							<?php endforeach; ?>
						</select>
						<input type="text" value="0" data-name="wc_szamlazz_automations[X][deadline]">
						<small><?php esc_html_e('± day', 'wc-szamlazz'); ?></small>
					</div>
				</div>
				<div class="wc-szamlazz-settings-automation-option">
					<label><?php esc_html_e( 'Mark as paid', 'wc-szamlazz' ); ?></label>
					<input type="checkbox" value="yes" data-name="wc_szamlazz_automations[X][paid]">
				</div>
				<div class="wc-szamlazz-settings-automation-option">
					<label><?php esc_html_e( 'Unique ID', 'wc-szamlazz' ); ?></label>
					<input type="text" data-name="wc_szamlazz_automations[X][id]">
				</div>
			</div>
			<div class="wc-szamlazz-settings-automation-if" style="display:none">
				<div class="wc-szamlazz-settings-automation-if-header">
					<span><?php _e('Run this automation, if', 'wc-szamlazz'); ?></span>
					<select data-name="wc_szamlazz_automations[X][logic]">
						<option value="and"><?php _e('All', 'wc-szamlazz'); ?></option>
						<option value="or"><?php _e('One', 'wc-szamlazz'); ?></option>
					</select>
					<span><?php _e('of the following match', 'wc-szamlazz'); ?></span>
				</div>
				<ul class="wc-szamlazz-settings-automation-if-options conditions"></ul>
			</div>
		</div>
	</div>
</script>

<?php echo WC_Szamlazz_Conditions::get_sample_row('automations'); ?>
