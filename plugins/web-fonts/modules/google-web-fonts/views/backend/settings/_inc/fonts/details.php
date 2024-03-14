<div id="google-web-fonts-font-details" data-bind="with: selected_font">
	<div id="google-web-fonts-font-details-inner">
		<h3 data-bind="text: family_name" class="google-web-fonts-font-details-name"></h3>
		
		<ul class="google-web-fonts-font-details-attributes">
			<li><?php _e('Style: '); ?> <span data-bind="text: style_string"></span></li>
			<li><?php _e('Weight: '); ?> <span data-bind="text: weight_string"></span></li>
		</ul>

		
		<div data-bind="style: { 'font-family': family(), 'font-weight': weight(), 'font-style': style() }" class="web-fonts-font-details-preview web-fonts-font-details-preview-light">Quick Brown Fox Jumped Over The Lazy Dog</div>
		<div data-bind="style: { 'font-family': family(), 'font-weight': weight(), 'font-style': style() }" class="web-fonts-font-details-preview web-fonts-font-details-preview-dark">Quick Brown Fox Jumped Over The Lazy Dog</div>
		
		<input data-bind="click: $root.set_font_status.bind($data, 1), visible: is_enabled() !== true" type="button" class="button button-primary google-web-fonts-font-item-button google-web-fonts-close-thickbox" value="<?php _e('Enable'); ?>" />
		<input data-bind="click: $root.set_font_status.bind($data, 0), visible: is_enabled() === true" type="button" class="button button-secondary google-web-fonts-font-item-button google-web-fonts-close-thickbox" value="<?php _e('Disable'); ?>" />
		<input type="button" class="button google-web-fonts-close-thickbox" value="<?php _e('Close'); ?>" />
	</div>
</div>