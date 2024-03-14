<?php include('_inc/fonts/details.php'); ?>

<?php include('_inc/fonts/enabled.php'); ?>

<form method="post" id="google-web-fonts-fonts-filtering-form">
	<?php include('_inc/notices.php'); ?>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="google-web-fonts-fonts-current-project"><?php _e('Enabled Fonts'); ?></label></th>
				<td>
					<a data-bind="click: show_enabled_fonts" href="#" title="<?php _e('Enabled Fonts'); ?>"><?php _e('See Enabled Fonts'); ?></a>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="google-web-fonts-font-search-keyword"><?php _e('Search'); ?></label></th>
				<td>
					<input type="text" class="code regular-text" data-bind="value: font_search_keyword" id="google-web-fonts-font-search-keyword" value="" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="google-web-fonts-font-search-sort"><?php _e('Sort'); ?></label></th>
				<td>
					<select data-bind="value: font_search_sort" class="code" id="google-web-fonts-font-search-sort">
						<option selected="selected" value="alpha"><?php _e('Alphabetical'); ?></option>
						<option value="popularity"><?php _e('Popularity'); ?></option>
						<option value="date"><?php _e('Recently Updated'); ?></option>
						<option value="style"><?php _e('Style'); ?></option>
						<option value="trending"><?php _e('Trending'); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th colspan="2">
					<a data-bind="click: reset_search" class="button button-primary"><?php _e('Reset Search'); ?></a>
				</th>
			</tr>
		</tbody>
	</table>
</form>

<div id="google-web-fonts-font-items-wrapper-container">
	
	<div class="tablenav top">
		<div class="tablenav-pages tablenav-fonts-pagination">
			
		</div>		
	</div>
	
	<div data-bind="foreach: fonts" id="google-web-fonts-font-items-wrapper">
		<div class="google-web-fonts-font-item">
			<div data-bind="style: { fontFamily: family(), fontWeight: weight(), fontStyle: style() }" class="google-web-fonts-font-item-sample">AaGg</div>
			<div data-bind="text: family_name" class="google-web-fonts-font-item-name"></div>
			<div class="google-web-fonts-font-item-charset"></div>
		
			<a data-bind="click: $root.set_font_status.bind($data, 1), visible: is_enabled() !== true" class="button button-primary google-web-fonts-font-item-button"><?php _e('Enable'); ?></a>
			<a data-bind="click: $root.set_font_status.bind($data, 0), visible: is_enabled() === true" class="button button-secondary google-web-fonts-font-item-button"><?php _e('Disable'); ?></a>
			
			<div class="google-web-fonts-font-item-more-info">
				<div class="google-web-fonts-font-item-details"><a data-bind="click: $root.select_font" href="#"><?php _e('More Details'); ?></a></div>
			</div>
		</div>
	</div>
		
	<div class="tablenav bottom">
		<div class="tablenav-pages tablenav-fonts-pagination">
			
		</div>		
	</div>

</div>