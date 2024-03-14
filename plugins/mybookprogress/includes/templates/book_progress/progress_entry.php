<div class="mbp-progress-inner">
	<div class="mbp-progress-content">
		<div class="mbp-progress-meta">
			<div class="mbp-progress-date">{{- this.format_date() }}</div>
			<div class="mbp-progress-phase">{{- this.format_phase() }}</div>
		</div>
		<div class="mbp-progress-display-container"></div>
		<div class="mbp-progress-edit">
			<div class="mbp-edit-progress-button"><?php _e('Edit', 'mybookprogress'); ?></div>
		</div>
		<div class="mbp-progress-extra"></div>
	</div>
	<div class="mbp-progress-sharing">
		<div class="mbp-progress-sharing-message"><?php _e('Congratulations! Let everyone know about your awesome progress!', 'mybookprogress'); ?></div>
		<textarea class="mbp-progress-notes">{{- this.model.get('notes').replace('\\n', '\n') }}</textarea>
		<div class="mbp-share-progress-buttons"></div>
	</div>
</div>