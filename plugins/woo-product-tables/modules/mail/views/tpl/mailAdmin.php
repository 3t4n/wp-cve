<form id="wtbpMailTestForm">
	<label>
		<?php esc_html_e('Send test email to'); ?>
		<?php HtmlWtbp::text('test_email', array('value' => $this->testEmail)); ?>
	</label>
	<?php HtmlWtbp::hidden('mod', array('value' => 'mail')); ?>
	<?php HtmlWtbp::hidden('action', array('value' => 'testEmail')); ?>
	<button class="button button-primary">
		<i class="fa fa-paper-plane"></i>
		<?php esc_html_e('Send test', 'woo-product-tables'); ?>
	</button><br />
	<i><?php esc_html_e('This option allows you to check your server mail functionality', 'woo-product-tables'); ?></i>
</form>
<div id="wtbpMailTestResShell wtbpHidden">
	<?php esc_html_e('Did you receive test email?', 'woo-product-tables'); ?><br />
	<button class="wtbpMailTestResBtn button button-primary" data-res="1">
		<i class="fa fa-check-square-o"></i>
		<?php esc_html_e('Yes! It works!', 'woo-product-tables'); ?>
	</button>
	<button class="wtbpMailTestResBtn button button-primary" data-res="0">
		<i class="fa fa-exclamation-triangle"></i>
		<?php esc_html_e('No, I need to contact my hosting provider with mail function issue.', 'woo-product-tables'); ?>
	</button>
</div>
<div id="wtbpMailTestResSuccess wtbpHidden">
	<?php esc_html_e('Great! Mail function was tested and is working fine.', 'woo-product-tables'); ?>
</div>
<div id="wtbpMailTestResFail wtbpHidden">
	<?php esc_html_e('Bad, please contact your hosting provider and ask them to setup mail functionality on your server.', 'woo-product-tables'); ?>
</div>
<div class="woobewoo-clear"></div>
<form id="wtbpMailSettingsForm">
	<table class="form-table">
		<?php foreach ($this->options as $optKey => $opt) { ?>
			<?php
			$htmlType = isset($opt['html']) ? $opt['html'] : false;
			if (empty($htmlType)) {
				continue;
			}
			?>
			<tr>
				<th scope="row" class="col-w-30perc">
					<?php echo esc_html($opt['label']); ?>
					<?php if (!empty($opt['changed_on'])) { ?>
						<br />
						<span class="description">
							<?php 
							if ($opt['value']) {
								/* translators: %s: label */
								echo esc_html(sprintf(__('Turned On %s', 'woo-product-tables'), DateWtbp::_($opt['changed_on'])));
							} else {
								/* translators: %s: label */
								echo esc_html(sprintf(__('Turned Off %s', 'woo-product-tables'), DateWtbp::_($opt['changed_on'])));
							}
							?>
						</span>
					<?php } ?>
				</th>
				<td class="col-w-10perc">
					<i class="fa fa-question woobewoo-tooltip" title="<?php echo esc_attr($opt['desc']); ?>"></i>
				</td>
				<td class="col-w-1perc">
					<?php HtmlWtbp::$htmlType('opt_values[' . $optKey . ']', array('value' => $opt['value'], 'attrs' => 'data-optkey="' . esc_attr($optKey) . '"')); ?>
				</td>
				<td class="col-w-50perc">
					<div id="wtbpFormOptDetails_<?php echo esc_attr($optKey); ?>" class="wtbpOptDetailsShell"></div>
				</td>
			</tr>
		<?php } ?>
	</table>
	<?php HtmlWtbp::hidden('mod', array('value' => 'mail')); ?>
	<?php HtmlWtbp::hidden('action', array('value' => 'saveOptions')); ?>
	<button class="button button-primary">
		<i class="fa fa-fw fa-save"></i>
		<?php esc_html_e('Save', 'woo-product-tables'); ?>
	</button>
</form>
