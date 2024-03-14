<?php
	global $wpdb;
	$acf_field_groups = get_posts(array(
		'post_type'	=> 'acf-field-group',
		'post_status' => array('publish', 'acf-disabled'),
		'posts_per_page'    => -1,
		'orderby' => 'title',
		'order' => 'ASC',
	));
	$tableNames = array();
	foreach ($acf_field_groups as $group){
		$acf_id = $group->ID;
		$custom_table_name = Acfct_utils::get_custom_table_name($acf_id);
		if($custom_table_name !== false){
			$table_exists = Acf_ct_database_manager::check_table_exists($custom_table_name);
			array_push($tableNames, array(
				'table_name'	    => $custom_table_name,
				'post_id'		    => $acf_id,
                'table_exists'      => $table_exists,
                'update_required'   => Acf_ct_database_manager::should_update_custom_table($acf_id)
			));
		}
	}
?>
<?php Acfct_utils::set_page_title('Custom Tables', false); ?>
<div class="metabox-holder acf-ct-columns-2">
	<div class="postbox acf-ct-column-1">
		<div>
			<?php
				if(empty($tableNames)){
					echo '<div class="pad-10">No custom Table configured</div>';
				}else {
			?>
				<table class="wp-list-table widefat fixed striped tags acf-ct-table acf-ct-label-list">
					<thead>
						<tr>
							<th width="70%">Custom Table Name</th>
							<th>Action</th>
						</tr>
					</thead>
                    <tbody id="the-list" data-wp-lists="list:tag">
					<?php
						foreach ($tableNames as $table) {
							$table_update_link = $urlBase."&type=sql-view&acf_ct_post_id=".$table['post_id'];
							$table_log_link = $urlBase."&type=log&acf_ct_post_id=".$table['post_id'];
							$acf_edit_link = admin_url('post.php?post='.$table['post_id'].'&action=edit');

							$buttonClass = '';
							$button_text = 'Manage Table';
							if($table['table_exists'] === false){
								$button_text = 'Create Table';
								$buttonClass = 'button-primary';
							}else if($table['update_required'] === true){
								$button_text = 'Update Table';
								$buttonClass = 'button-primary';
							}

							echo '<tr>';
								echo '<td><a class="row-title" href="'. esc_url($acf_edit_link) .'">'. esc_html($wpdb->prefix.$table['table_name']) .'</a></td>';
								echo '<td>
										<a class="button '.$buttonClass.'" href="'. esc_url($table_update_link) .'">'. esc_html($button_text) .'</a>';
										// show view log if table exists
										if($table['table_exists']){
											echo '<a class="view-log" href="'. esc_url($table_log_link) .'">View Logs</a>';
										}
									echo '</td>';
							echo '</tr>';
						}
					?>
					</tbody>
				</table>
			<?php } ?>
		</div>
	</div>
    <div class="acf-ct-column-2">
		<?php include_once 'partials/right-sidebar.php';?>
    </div>
</div>
