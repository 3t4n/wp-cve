<form id="fonts-com-project-settings-form" method="post" action="<?php esc_attr_e(admin_url('admin-ajax.php')); ?>">
	<?php include('_inc/notices.php'); ?>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="fonts-com-project-current"><?php _e('Current Project'); ?></label></th>
				<td>
					<select name="fonts-com-project[current]" id="fonts-com-project-current">
						<option value=""><?php _e('Create project'); ?></option>
						<?php foreach($projects as $project_data) { ?>
						<option <?php selected($active_project, $project_data->ProjectKey); ?> value="<?php esc_attr_e($project_data->ProjectKey); ?>"><?php esc_html_e($project_data->ProjectName); ?></option>
						<?php } ?>
					</select><br />
					<small><?php _e('The last project you save will become the active project for this site.'); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="fonts-com-project-name"><?php _e('Project Name'); ?></label></th>
				<td>
					<input type="text" class="code regular-text" name="fonts-com-project[name]" id="fonts-com-project-name" value="" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="fonts-com-project-domains"><?php _e('Project Domains'); ?></label></th>
				<td>
					<ul id="fonts-com-project-domains-list">
						<li id="fonts-com-project-domain-template">
							<input type="text" class="fonts-com-project-domain code regular-text" name="fonts-com-project[domains][]" value="" />
							<input type="hidden" class="fonts-com-project-domain-id code regular-text" name="fonts-com-project[domain-ids][]" value="" />
							<input type="button" class="fonts-com-project-domain-remove-button button button-secondary" value="<?php _e('Remove'); ?>" />
						</li>
					</ul>
					<ul>
						<li>
							<input type="button" class="fonts-com-project-domain-add-button button button-secondary" value="<?php _e('Add Another Domain'); ?>" />
						</li>
					</ul>
				</td>
			</tr>
		</tbody>
	</table>

	<p style="width: 550px;"><?php _e('Click Save Project will transfer any fonts and selectors you have set up on your currently active project over to your new active project. Depending on how much data you have, this may take a while.'); ?></p>	
	<p class="submit">
		<input type="hidden" name="action" value="web_fonts_fonts_com_save_project_settings" />
		<input type="submit" class="button button-primary" id="fonts-com-project-save" value="<?php _e('Set Project'); ?>" />
		
		<input <?php if(empty($active_project)) { ?> style="display: none;" <?php } ?> type="button" class="button button-secondary" id="fonts-com-clear-active-project" value="<?php _e('Remove Active Project'); ?>" />
	</p>
</form>