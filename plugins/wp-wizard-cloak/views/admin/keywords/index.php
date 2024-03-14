<?php 
/* @var $this PMLC_Admin_Links */
/* @var $list PMLC_Link_List */
?>

<?php if ($this->errors->get_error_codes()): ?>
	<?php $this->error() ?>
<?php endif ?>

<div class="wrap">
	<h2>
		<?php _e('Manage Auto-Linked Keyword Groupings', 'pmlc_plugin') ?>
		&nbsp;
		<a href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-keywords', 'action' => 'edit'), admin_url('admin.php'))) ?>" class="add-new"><?php echo esc_html_x('Add New', 'pmlc_plugin'); ?></a>
		<?php if ('' != $s): ?>
			<span class="subtitle"><?php printf(__('Search results for &#8220;%s&#8221;'), $s) ?></span>
		<?php endif ?>
	</h2>

	<?php 
	// define the columns to display, the syntax is 'internal name' => 'display name'
	$columns = array(
		'id'			=> __('ID', 'pmlc_plugin'),
		'keywords'		=> __('Keywords', 'pmlc_plugin'),
		'url'			=> __('URL', 'pmlc_plugin'),
	);
	?>
	<ul class="subsubsub">
		<?php $baseTypeUrl = remove_query_arg(array('type', 'pagenum', 's'), $this->baseUrl) ?>
		<?php $count_all = $list->countBy(array('is_trashed' => '0')) ?>
		<li>
			<a href="<?php echo $baseTypeUrl ?>" class="<?php echo '' == $type ? 'current' : '' ?>">
				<?php _e('All', 'pmlc_plugin') ?>
				<span class="count">(<?php echo $count_all ?>)</span>
			</a>
		</li>
		<?php if (($count_trash = $list->countBy('is_trashed', 1)) > 0): ?>
			<li>
				|
				<a href="<?php echo add_query_arg('type', 'trash', $baseTypeUrl) ?>" class="<?php echo 'trash' == $type ? 'current' : '' ?>">
					<?php _e('Trash', 'pmlc_plugin') ?>
					<span class="count">(<?php echo $count_trash ?>)</span>
				</a>
			</li>
		<?php endif ?>
	</ul>
	
	<form method="get">
	<input type="hidden" name="page" value="<?php echo esc_attr($this->input->get('page')) ?>" />
	<?php if ('' != $type): ?>
		<input type="hidden" name="type" value="<?php echo esc_attr($type) ?>" />
	<?php endif ?>
	<p class="search-box">
		<label for="link-search-input" class="screen-reader-text"><?php _e('Search Keywords', 'pmlc_plugin') ?>:</label>
		<input id="link-search-input" type="text" name="s" value="<?php echo esc_attr($s) ?>" />
		<input type="submit" class="button" value="<?php _e('Search Keywords', 'pmlc_plugin') ?>">
	</p>
	</form>
	
	<form method="post" id="link-list">
	<input type="hidden" name="action" value="bulk" />
	<?php wp_nonce_field('bulk-keywords', '_wpnonce_bulk-keywords') ?>
	
	<div class="tablenav">
		<div class="alignleft actions">
			<select name="bulk-action">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'pmlc_plugin') ?></option>
				<?php if ('trash' != $type): ?>
					<option value="delete"><?php _e('Delete', 'pmlc_plugin') ?></option>
				<?php else: ?>
					<option value="restore"><?php _e('Restore', 'pmlc_plugin')?></option>
					<option value="delete"><?php _e('Delete Permanently', 'pmlc_plugin')?></option>
				<?php endif ?>
			</select>
			<input type="submit" value="<?php esc_attr_e('Apply', 'pmlc_plugin') ?>" name="doaction" id="doaction" class="button-secondary action" />
		</div>

		<?php if ($page_links): ?>
			<div class="tablenav-pages">
				<?php echo $page_links_html = sprintf(
					'<span class="displaying-num">' . __('Displaying %s&#8211;%s of %s', 'pmlc_plugin') . '</span>%s',
					number_format_i18n(($pagenum - 1) * $perPage + 1),
					number_format_i18n(min($pagenum * $perPage, $list->total())),
					number_format_i18n($list->total()),
					$page_links
				) ?>
			</div>
		<?php endif ?>
	</div>
	<div class="clear"></div>
	<table class="widefat pmlc-admin-keywords">
		<thead>
		<tr>
			<th class="manage-column column-cb check-column" scope="col">
				<input type="checkbox" />
			</th>
			<?php
			$col_html = '';
			foreach ($columns as $column_id => $column_display_name) {
				$column_link = "<a href='";
				$order2 = 'ASC';
				if ($order_by == $column_id)
					$order2 = ($order == 'DESC') ? 'ASC' : 'DESC';
	
				$column_link .= esc_url(add_query_arg(array('order' => $order2, 'order_by' => $column_id), $this->baseUrl));
				$column_link .= "'>{$column_display_name}</a>";
				$col_html .= '<th scope="col" class="column-' . $column_id . ' ' . ($order_by == $column_id ? $order : '') . '">' . $column_link . '</th>';
			}
			echo $col_html;
			?>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th class="manage-column column-cb check-column" scope="col">
				<input type="checkbox" />
			</th>
			<?php echo $col_html; ?>
		</tr>
		</tfoot>
		<tbody id="the-pmlc-admin-link-list" class="list:pmlc-admin-keywords">
		<?php if ($list->isEmpty()): ?>
			<tr>
				<td colspan="<?php echo count($columns) + 1 ?>"><?php _e('No keywords found.', 'pmlc_plugin') ?></td>
			</tr>
		<?php else: ?>
			<?php $class = ''; ?>
			<?php foreach ($list as $item): ?>
				<?php $class = ('alternate' == $class) ? '' : 'alternate'; ?>
				<tr class="<?php echo $class; ?>" valign="middle">
					<th scope="row" class="check-column">
						<input type="checkbox" id="link_<?php echo $item['id'] ?>" name="keywords[]" value="<?php echo esc_attr($item['id']) ?>" />
					</th>
					<?php foreach ($columns as $column_id => $column_display_name): ?>
						<?php
						switch ($column_id):
							case 'id':
								?>
								<th valign="top" scope="row">
									<?php echo $item['id'] ?>
								</th>
								<?php
								break;
							case 'keywords':
								?>
								<td>
									<strong>
										<span class="row-title"><?php echo $item['keywords'] ?></span>
										<?php if ($item['match_case']): ?>
											- <?php _e('Case Sensitive', 'pmlc_plugin') ?>
										<?php endif ?>
									</strong>
									<div class="row-actions">
										<?php if ('trash' != $type): ?>
											<span class="edit"><a class="edit" href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $item['id']), $this->baseUrl)) ?>"><?php _e('Edit', 'pmlc_plugin') ?></a></span> |
											<span class="delete"><a class="delete" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'delete'), $this->baseUrl), 'delete-keyword')) ?>"><?php _e('Trash', 'pmlc_plugin') ?></a></span>
										<?php else: ?>
											<span class="restore"><a class="restore" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'restore'), $this->baseUrl), 'restore-keyword')) ?>"><?php _e('Restore', 'pmlc_plugin') ?></a></span> |
											<span class="delete"><a class="delete" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'delete'), $this->baseUrl), 'delete-keyword')) ?>"><?php _e('Delete Permanently', 'pmlc_plugin') ?></a></span>
										<?php endif ?>
									</div>
								</td>
								<?php
								break;
							default:
								?>
								<td>
									<?php echo $item[$column_id] ?>
								</td>
								<?php
								break;
						endswitch;
						?>
					<?php endforeach; ?>
				</tr>
			<?php endforeach; ?>
		<?php endif ?>
		</tbody>
	</table>
	<div class="tablenav">
		<?php if ($page_links): ?><div class="tablenav-pages"><?php echo $page_links_html ?></div><?php endif ?>
		
		<div class="alignleft actions">
			<select name="bulk-action2">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'pmlc_plugin') ?></option>
				<?php if ('trash' != $type): ?>
					<option value="delete"><?php _e('Delete', 'pmlc_plugin') ?></option>
				<?php else: ?>
					<option value="restore"><?php _e('Restore', 'pmlc_plugin')?></option>
					<option value="delete"><?php _e('Delete Permanently', 'pmlc_plugin')?></option>
				<?php endif ?>
			</select>
			<input type="submit" value="<?php esc_attr_e('Apply', 'pmlc_plugin') ?>" name="doaction2" id="doaction2" class="button-secondary action" />
		</div>
	</div>
	<div class="clear"></div>
	</form>
</div>