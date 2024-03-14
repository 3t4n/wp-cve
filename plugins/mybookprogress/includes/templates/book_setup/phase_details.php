<div class="mbp-section-header">
	{{= this.model.get('name') ? this.model.get('name') : '<span class="placeholder">(<?php _e('no name'); ?>)</span>' }}
	<div class="mbp-phase-complete-container">
		<input type="checkbox" class="mbp-phase-complete" id="mbp_phase_complete"> <label class="mbp-phase-complete-label" for="mbp_phase_complete"><?php _e('Phase Complete'); ?></label>
	</div>
</div>
<div class="mbp-section-content">
	<div class="mbp-setting mbp-phase-deadline-setting">
		<div class="mbp-setting-row">
			<div class="mbp-setting-label"><?php _e('Deadline'); ?>:</div>
			<div class="mbp-setting-value">
				<input type="text" class="mbp-phase-deadline" value="">
				<div class="mbp-setting-error"></div>
			</div>
		</div>
	</div>
	<div class="mbp-setting mbp-phase-progress-type-setting">
		<div class="mbp-setting-row">
			<div class="mbp-setting-label"><?php _e('Progress Type'); ?>:</div>
			<div class="mbp-setting-value"><div class="mbp-phase-progress-types"></div></div>
		</div>
		<div class="mbp-setting-desc"><?php _e('How would you like to track your progress?'); ?></div>
	</div>
	<div class="mbp-setting mbp-phase-progress-target-setting">
		<div class="mbp-setting-row">
			<div class="mbp-setting-label"><?php _e('Progress Target'); ?>:</div>
			<div class="mbp-setting-value"><input type="number" class="mbp-phase-progress-target" min="1"><span class="mbp-phase-progress-target-units"></span></div>
		</div>
		<div class="mbp-setting-desc"><?php _e('How much total progress do you want to make?'); ?></div>
	</div>
</div>