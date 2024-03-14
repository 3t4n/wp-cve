<div class="mbp-book-phases-container"><div class="mbp-book-phases"></div></div>
<div class="mbp-book-phase-indicator"></div>
<div class="mbp-section mbp-create-progress-section">
	<div class="mbp-section-header">
		<?php _e('Update Progress', 'mybookprogress'); ?>
		<div class="mbp-progress-today-container">
			<input type="checkbox" class="mbp-progress-today" id="mbp_progress_today" checked="checked">
			<label for="mbp_progress_today"><?php _e('I made this progress today.', 'mybookprogress'); ?></label>
		</div>
		<div class="mbp-phase-complete-button-container">
			<div class="mbp-phase-complete-button"><?php _e('Complete', 'mybookprogress'); ?></div>
		</div>
	</div>
	<div class="mbp-section-content">
		<div class="mbp-create-progress-date-container">
			<div class="mbp-create-progress-date-message"><?php _e('When did you make this progress?', 'mybookprogress'); ?></div>
			<input class="mbp-create-progress-date" type="text" value="">
			<div class="mbp-create-progress-date-error"></div>
		</div>
		<div class="mbp-progress-editor-container"></div>
		<div class="mbp-phase-complete-container">
			<div class="mbp-phase-complete-message"><?php _e('Does this mean you are done with this phase?', 'mybookprogress'); ?></div>
			<input type="checkbox" class="mbp-phase-complete" id="mbp_phase_complete" checked="checked"> <label class="mbp-phase-complete-label" for="mbp_phase_complete"><?php _e('Yes', 'mybookprogress'); ?>!</label>
		</div>
		<div class="mbp-reduce-progress-container">
			<div class="mbp-reduce-progress-message"><?php _e('Are you sure you want to reduce your overall progress?', 'mybookprogress'); ?></div>
			<input type="checkbox" class="mbp-reduce-progress" id="mbp_reduce_progress" checked="checked"> <label class="mbp-reduce-progress-label" for="mbp_reduce_progress"><?php _e('Yes', 'mybookprogress'); ?></label>
		</div>
		<div class="mbp-create-progress-errors"></div>
		<div class="mbp-create-progress-button-container">
			<div class="mbp-create-progress-button"><?php _e('Save Progress', 'mybookprogress'); ?></div>
		</div>
	</div>
</div>
