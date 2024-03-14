<div class="mbp-progress-display mbp-progress-numeric">
	<div class="mbp-progress-label">{{- progress_type.units }}:</div>
	<div class="mbp-progress-current">{{- utils.number_format(progress*target) }}</div>
	<span class="mbp-progress-of"><?php _e('of', 'mybookprogress'); ?></span>
	<div class="mbp-progress-target">{{- target }}</div>
</div>