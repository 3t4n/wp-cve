<div data-bind="visible: show_by_selector">
	<table class="widefat web-fonts-font-selectors-by-selector-table">
		<thead>
			<tr class="noremove">
				<th scope="col"><?php _e('Selector'); ?></th>
				<th scope="col"><?php _e('Font'); ?></th>
				<th scope="col"><?php _e('Fallback Fontstack'); ?></th>
				<th scope="col" class="web-fonts-font-selectors-by-selector-selector-remove-container"><?php _e('Actions'); ?></th>
			</tr>
		</thead>
		<tbody data-bind="foreach: selectors">
			<tr valign="top">
				<td class="web-fonts-font-selectors-by-selector-table-selector-column">
					<code data-bind="text: tag, visible: !editing"></code>
					<input data-bind="value: tag, visible: editing" type="text" class="code regular-text" />
				</td>
				<td class="web-fonts-font-selectors-by-selector-table-font-column">
					<select data-bind="value: font, options: $root.fonts_for_selectors, optionsText: function(item) { return item.name; }, optionsCaption: '<?php _e('Select a font...'); ?>'"></select>
				</td>
				<td>
					<input data-bind="value: fallback" type="text" class="code regular-text" />
				</td>
				<td class="web-fonts-font-selectors-by-selector-selector-remove-container">
					<a data-bind="click: $parent.remove_selector" href="#"><?php _e('Remove'); ?></a>
				</td>
			</tr>
		</tbody>
	</table>
	
	<p class="submit">
		<input data-bind="enable: can_submit" type="submit" class="button button-primary" value="<?php _e('Apply Fonts'); ?>" />
		<input data-bind="click: new_selector" type="button" class="button" value="<?php _e('Add Selector'); ?>" />
	</p>
</div>