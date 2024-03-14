<?php
defined('ABSPATH') || exit;
?>
<div id="imagelinks-modal-{{ modalData.id }}" class="imagelinks-modal" tabindex="-1">
	<div class="imagelinks-modal-dialog">
		<div class="imagelinks-modal-header">
			<div class="imagelinks-modal-close" al-on.click="modalData.deferred.resolve('close');">&times;</div>
			<div class="imagelinks-modal-title"><?php esc_html_e('Select a marker template', 'imagelinks'); ?></div>
		</div>
		<div class="imagelinks-modal-data imagelinks-modal-loading">
			<div class="imagelinks-modal-group">
				<div class="imagelinks-modal-marker-templates">
					<div class="imagelinks-marker-template-wrap" al-repeat="template in modalData.templates" al-value="template" al-on.click="modalData.fn.onClick(modalData, $event, $element, $value)" al-on.dblclick="modalData.fn.onDblClick(modalData)" title="{{(template.name ? template.name : '<?php esc_html_e('default', 'imagelinks'); ?>')}}">
						<div class="imagelinks-marker-template">
							<div class="imagelinks-marker-wrap">
								<div class="imagelinks-marker-pulse" al-attr.class.imagelinks-active="template.data.view.pulse.active">
								</div>
								<div class="imagelinks-marker"
									 al-style.width="modalData.appData.fn.getMarkerStyle(modalData.appData, template.data, 'width')"
									 al-style.height="modalData.appData.fn.getMarkerStyle(modalData.appData, template.data, 'height')"
									 al-init="modalData.fn.initMarker(modalData, template, $element)"
								>
									<div class="imagelinks-marker-icon-wrap"
										 al-style.color="modalData.appData.fn.getIconStyle(modalData.appData, template.data.view.icon, 'color')"
										 al-style.font-size="modalData.appData.fn.getIconStyle(modalData.appData, template.data.view.icon, 'font-size')"
									>
										<div class="imagelinks-marker-icon" al-if="template.data.view.icon.name"><i class="fa {{template.data.view.icon.name}}"></i></div>
										<div class="imagelinks-marker-icon-label" al-if="template.data.view.icon.label">{{template.data.view.icon.label}}</div>
									</div>
								</div>
							</div>
						</div>
						<div class="imagelinks-marker-template-name">{{(template.name ? template.name : '<?php esc_html_e('default', 'imagelinks'); ?>')}}</div>
						<div class="imagelinks-marker-template-close" al-on.click="modalData.fn.deleteTemplate(modalData, template)" al-if="!template.predefined"><i class="imagelinks-icon imagelinks-icon-cross"></i></div>
					</div>
				</div>
			</div>
		</div>
		<div class="imagelinks-modal-footer">
			<div class="imagelinks-modal-text"><?php esc_html_e('Selected template:', 'imagelinks'); ?> <b al-if="modalData.selectedTemplate">{{modalData.selectedTemplate.name}}</b></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-close" al-on.click="modalData.deferred.resolve('close');"><?php esc_html_e('Close', 'imagelinks'); ?></div>
			<div class="imagelinks-modal-btn imagelinks-modal-btn-create" al-on.click="modalData.deferred.resolve(true);"><?php esc_html_e('Ok', 'imagelinks'); ?></div>
		</div>
	</div>
</div>