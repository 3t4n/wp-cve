<div class="mbp-progress-editor mbp-progress-editor-numeric">
	<div class="mbp-progress-label">{{- progress_type.units }}:</div>
	<input name="progress" type="number" value="{{- utils.number_format(progress*target) }}" min="1">
	<span class="mbp-progress-of"><?php _e('of', 'mybookprogress'); ?></span>
	<input name="target" type="number" value="{{- target }}" min="1">
</div>