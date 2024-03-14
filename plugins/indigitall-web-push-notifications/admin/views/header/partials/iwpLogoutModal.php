<div id="iwpLogoutModal" class="iwp-modal-backdrop iwp-hide">
	<div class="iwp-modal">
		<div class="iwp-modal-header">
			<i id="iwpTimesLogoutModal" class="iwp-close-icon"></i>
		</div>
		<div class="iwp-modal-body">
			<div class="iwp-modal-body-title">
				<?php _e('Disconnect','iwp-text-domain') ?>
			</div>
			<div class="iwp-modal-body-question">
				<?php _e('Are you sure you want to disconnect the current app?','iwp-text-domain') ?>
			</div>
			<div class="iwp-modal-body-tip">
				<?php _e('If you disconnect, remember that you will have to log in again.','iwp-text-domain') ?>
			</div>
		</div>
		<div class="iwp-modal-footer">
			<div class="iwp-admin-form-group-buttons">
				<button id="iwpCancelLogoutModal" class="iwp-btn iwp-btn-transparent" type="button">
					<?php echo __('Cancel','iwp-text-domain'); ?>
				</button>
				<button id="iwpLogoutModalDisconnect" class="iwp-logout-modal-disconnect iwp-btn iwp-btn-red" type="button">
					<?php echo strtoupper(__('Disconnect','iwp-text-domain')); ?>
				</button>
			</div>
		</div>
	</div>
</div>
