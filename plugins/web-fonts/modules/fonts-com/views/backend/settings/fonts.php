<?php 
foreach($projects as $project_data) {
	if($active_project == $project_data->ProjectKey) {
		$active_project_name = $project_data->ProjectName;
		break;
	}
}
?>

<?php include('_inc/fonts/item.php'); ?>

<?php include('_inc/fonts/details.php'); ?>

<?php include('_inc/fonts/project.php'); ?>

<form method="post" id="fonts-com-fonts-filtering-form">
	<?php include('_inc/notices.php'); ?>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="fonts-com-fonts-current-project"><?php _e('Current Project'); ?></label></th>
				<td>
					<input type="hidden" name="fonts-com-fonts[current-project]" id="fonts-com-fonts-current-project" value="<?php esc_attr_e($active_project); ?>" />
					<strong><?php esc_html_e($active_project_name); ?>
					</strong>
					- <a id="fonts-com-show-project-fonts-link" title="<?php printf(__('%s Fonts'), esc_attr_e($active_project_name)); ?>" href="#TB_inline?inlineId=fonts-com-project-fonts-list"><?php _e('See Project Fonts'); ?></a>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="fonts-com-fonts-filters-keyword"><?php _e('Search'); ?></label></th>
				<td>
					<input rel="Keywords" type="text" class="fonts-com-fonts-filter" name="fonts-com-fonts[filters][keyword]" id="fonts-com-fonts-filters-keyword" id="fonts-com-fonts-filters-keyword" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php _e('Filters'); ?></th>
				<td>
					<?php foreach((array)$available_filters as $filter_name => $filter_values) { ?>
					<select rel="<?php esc_attr_e($filter_name); ?>" class="fonts-com-fonts-filter" name="fonts-com-fonts[filters][<?php esc_attr_e($filter_name); ?>]">
						<?php if($filter_name != 'FreeOrPaid') { ?>
						<option value=""><?php printf(__('Select %s'), self::get_nice_filter_type_text($filter_name)); ?></option>
						<?php } ?>
						
						<?php foreach((array)$filter_values as $filter_value_key => $filter_value_name) { ?>
						<option value="<?php esc_attr_e($filter_value_key); ?>"><?php esc_html_e($filter_value_name); ?></option>
						<?php } ?>
					</select>
					<?php } ?>
				</td>
			</tr>
			<tr valign="top">
				<th colspan="2">
					<a class="button button-primary" id="fonts-com-fonts-reset-filters"><?php _e('Reset Filters'); ?></a>
				</th>
			</tr>
		</tbody>
	</table>
</form>

<div id="fonts-com-font-items-wrapper-container">
	
	<div class="tablenav top">
		<div class="tablenav-pages tablenav-fonts-pagination">
			
		</div>		
	</div>
	
	<div id="fonts-com-font-items-wrapper">
		
	</div>
		
	<div class="tablenav bottom">
		<div class="tablenav-pages tablenav-fonts-pagination">
			
		</div>		
	</div>

</div>