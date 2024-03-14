<div class="mbp-nudge-delete"></div>
<div class="mbp-nudge-meta">
	<div class="mbp-nudge-date">{{- this.format_date() }}</div>
	<div class="mbp-nudge-new-message"><?php _e('New!', 'mybookprogress'); ?></div>
</div>
<div class="mbp-nudge-content">
	<div class="mbp-nudge-text">{{- this.model.get('text') }}</div>
	<div class="mbp-nudge-author">{{= this.format_name() }}</div>
</div>
<div style="clear:both"></div>