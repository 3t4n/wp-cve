<div data-bind="visible: show_by_font">
	<div id="web-fonts-font-selectors-by-font-items" data-bind="foreach: fonts">
	
		<div class="web-fonts-font-selectors-by-font-item">
			<h2 data-bind="text: name"></h2>
			
			<p><em><?php _e('If you just added this font, it may take up to a minute before the preview below appears with the correct font face.'); ?></em></p>
			
			<div data-bind="style: style_properties">
				<div class="web-fonts-font-details-preview web-fonts-font-details-preview-light" data-bind="text: preview_text"></div>
				
				<div class="web-fonts-font-details-preview web-fonts-font-details-preview-dark" data-bind="text: preview_text"></div>
			</div>
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><?php _e('Selector'); ?></th>
						<td>
							<input data-bind="value: selector, valueUpdate: 'afterkeydown'" type="text" class="regular-text code" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e('Fallback Fontstack'); ?></th>
						<td>
							<input type="text" class="regular-text code" data-bind="value: fontstack" />
							
							<input data-bind="click: add_selector, enable: is_valid_selector" type="button" class="button button-secondary" value="<?php _e('Add'); ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			
			<table class="widefat">
				<thead>
					<tr>
						<th scope="col"><?php _e('Selector'); ?></th>
						<th scope="col"><?php _e('Fallback Fontstack'); ?></th>
						<th scope="col" class="web-fonts-font-selectors-by-selector-selector-remove-container"><?php _e('Actions'); ?></th>
					</tr>
				</thead>
				<tbody data-bind="foreach: selectors">
					<tr valign="top">
						<td><code data-bind="text: tag"></code></td>
						<td><input type="text" class="code regular-text" data-bind="value: fallback" /></td>
						<td class="web-fonts-font-selectors-by-selector-selector-remove-container"><a data-bind="click: $parent.remove_selector" href="#"><?php _e('Remove'); ?></a></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<p class="submit">
		<input data-bind="enable: can_submit" type="submit" class="button button-primary" value="<?php _e('Apply Selectors'); ?>" />
	</p>
</div>