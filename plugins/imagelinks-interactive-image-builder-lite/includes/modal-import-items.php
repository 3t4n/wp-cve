<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Select & import an item', 'imagelinks'); ?></div>
		</div>
		<div class="imagelinks-modal-data imagelinks-modal-loading">
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-import-items">
					<p><?php esc_html_e('Select & import an item from the old version of the plugin.', 'imagelinks'); ?></p>
					<div class="imagelinks-import-item" al-repeat="item in modalData.importItems" al-value="item" al-on.click="modalData.fn.onClickItem(modalData, $event, $element, $value)" al-on.dblclick="modalData.fn.onDblClickItem(modalData)">
						<i class="fa fa-file-text-o"></i><span class="imagelinks-import-item-title">{{item.title}}</span><span class="imagelinks-import-item-date">{{item.modified}}</span>
					</div>
				</div>
			</div>
		</div>
		<div class="imagelinks-modal-footer">
			<div class="imagelinks-modal-text" al-if="modalData.selectedItem"><?php esc_html_e('Selected item:', 'imagelinks'); ?> <b>{{modalData.selectedItem.title}}</b></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-close" al-on.click="modalData.deferred.resolve('close');"><?php esc_html_e('Close', 'imagelinks'); ?></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-create" al-on.click="modalData.deferred.resolve(true);"><?php esc_html_e('OK', 'imagelinks'); ?></div>
		</div>
	</div>
</div>