<?php
	$whStep1View = isset($whStep1View) ? $whStep1View : '';
	$whStep2View = isset($whStep2View) ? $whStep2View : '';
	$whStep3View = isset($whStep3View) ? $whStep3View : '';


	$status = isset($status) ? $status : '';
	$statusValue = ($status === 'enabled') ? '1' : '0';
	$enabled = strtoupper(__('Activated', 'iwp-text-domain'));
	$disabled = strtoupper(__('Deactivated', 'iwp-text-domain'));
//	$whatsAppChatTitle = __('Customizing <b>WhatsApp Chat</b>', 'iwp-text-domain');
	$whatsAppChatTitle = __('<b>WhatsApp Chat</b>', 'iwp-text-domain');

	$errorIcon  = IWP_ADMIN_URL . 'images/exclamation-red-icon.svg';
	$errorIconHtml = "<img src='{$errorIcon}' alt=''>";
	$hideErrorLabel = __('There are errors in the advanced settings', 'iwp-text-domain');
?>

<div class="iwp-admin-whatsAppChat">
	<div class="iwp-admin-whatsAppChat-header">
		<div class="iwp-admin-whatsAppChat-title" style="color: var(--iwp-color-green);"><?php echo($whatsAppChatTitle); ?></div>
		<div class="iwp-admin-whatsAppChat-status">
			<div id="iwpWhatsAppChatStatusSwitch" class="iwp-admin-switch-container <?php echo($status); ?>">
				<input type="hidden" class="iwp-admin-switch-value" value="<?php echo($statusValue); ?>">
				<div class="iwp-admin-switch">
					<div class="iwp-admin-switch-ball"></div>
				</div>
				<div class="iwp-admin-switch-label">
					<div class="iwp-admin-switch-label-disabled"><?php echo($disabled); ?></div>
					<div class="iwp-admin-switch-label-enabled"><?php echo($enabled); ?></div>
				</div>
			</div>
		</div>
		<div id="adminWhHideError" class="iwp-admin-whatsAppChat-tiny-error iwp-hide"><?php echo($errorIconHtml.$hideErrorLabel); ?></div>
	</div>

	<div id="iwp-admin-error-box" class="iwp-admin-error-box iwp-hide"></div>
	<div id="iwp-admin-success-box" class="iwp-admin-success-box iwp-hide"></div>

	<div class="iwp-admin-whatsAppChat-steps-container">
	<?php
		echo($whStep1View);
	?>
		<div id="iwpWhatsAppChatAdvanceSettings" class="iwp-admin-whatsAppChat-advance-settings">
		<?php
			echo($whStep2View);
			echo($whStep3View);
		?>
		</div>
		<div class="iwp-admin-whatsAppChat-save-button-row">
			<div class="iwp-admin-form-group-buttons">
				<button id="iwpAdminWhatsAppChatSave" class="iwp-btn iwp-btn-green" type="button">
					<?php _e('Save','iwp-text-domain'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
