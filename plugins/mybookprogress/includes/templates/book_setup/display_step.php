<div class="mbp-book-setup-step-label"><?php _e('How will your progress be displayed?', 'mybookprogress'); ?></div>
<div class="mbp-section mbp-book-style-section">
	<div class="mbp-section-content">
		<div class="mbp-book-display-settings-container">
			<div class="mbp-setting mbp-setting-row mbp-book-bar-color-setting">
				<div class="mbp-setting-label"><label for="mbp-book-bar-color"><?php _e('Progress Bar Color', 'mybookprogress'); ?>:</label></div>
				<div class="mbp-setting-value"><input type="text" class="mbp-book-bar-color" id="mbp-book-bar-color" value="{{- this.model.get('display_bar_color') }}" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"></div>
			</div>
			<div class="mbp-setting mbp-setting-row mbp-book-cover-image-setting">
				<div class="mbp-setting-label"><?php _e('Cover Image', 'mybookprogress'); ?>:</div>
				<div class="mbp-setting-value"><div class="mbp-medium-button-container"><div class="mbp-book-cover-image-button"></div></div></div>
			</div>
		</div>
		<div class="mbp-book-preview-container">
			<div class="mbp-book-preview-title"><?php _e('Preview', 'mybookprogress'); ?></div>
			<div class="mbp-book-preview-outer">
				<div class="mbp-book-preview"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
</div
