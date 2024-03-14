<?php
	$headerLabel = __('Choose your project','iwp-text-domain');
	$selectProjectLabel = __('Select Project','iwp-text-domain');
?>
<div id="iwpAdminProjectSelection" class="iwp-admin-modalLogin-content iwp-admin-project-selection-container">
	<div class="iwp-admin-modalLogin-header">
		<?php echo($headerLabel); ?>
		<i id="iwpAdminLoginModalCloseProjectSelection" class="iwp-close-icon"></i>
	</div>
	<div class="iwp-admin-modalLogin-body">
		<div class="iwp-admin-form-group">
			<label for="iwpApplicationId"><?php echo($selectProjectLabel); ?></label>
			<div class="iwp-custom-select">
				<select name="iwpApplicationId" id="iwpApplicationId"></select>
			</div>
		</div>
	</div>
	<div class="iwp-admin-modalLogin-footer">
		<div class="iwp-admin-form-group-buttons iwp-select-service-button">
			<button id="selectService" class="iwp-btn iwp-btn-green" type="button">
				<?php echo($selectProjectLabel); ?>
			</button>
		</div>
	</div>
</div>