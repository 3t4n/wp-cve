<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Save the marker as a template', 'imagelinks'); ?></div>
		</div>
		<div class="imagelinks-modal-data">
			<div class="imagelinks-control">
				<div class="imagelinks-helper" title="<?php esc_html_e('Sets a marker template name', 'imagelinks'); ?>"></div>
				<div class="imagelinks-label"><?php esc_html_e('Template name', 'imagelinks'); ?></div>
				<input class="imagelinks-text imagelinks-long" type="text" al-text="modalData.templateName">
			</div>
		</div>
		<div class="imagelinks-modal-footer">
			<div class="imagelinks-modal-btn imagelinks-modal-btn-close" al-on.click="modalData.deferred.resolve('close');"><?php esc_html_e('Close', 'imagelinks'); ?></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-create" al-on.click="modalData.deferred.resolve(true);"><?php esc_html_e('Save', 'imagelinks'); ?></div>
		</div>
	</div>
</div>