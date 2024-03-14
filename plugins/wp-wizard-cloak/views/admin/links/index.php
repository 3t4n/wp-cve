<?php 
/* @var $this PMLC_Admin_Links */
/* @var $list PMLC_Link_List */
?>

<?php if ($this->errors->get_error_codes()): ?>
	<?php $this->error() ?>
<?php endif ?>

<div class="wrap">
	<h2>
		<?php _e('Manage Cloaked Links', 'pmlc_plugin') ?>
		&nbsp;
		<a href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-add'), admin_url('admin.php'))) ?>" class="add-new"><?php echo esc_html_x('Add New', 'pmlc_plugin'); ?></a>
		<?php if ('' != $s): ?>
			<span class="subtitle"><?php printf(__('Search results for &#8220;%s&#8221;'), $s) ?></span>
		<?php endif ?>
	</h2>

	<?php 
	// define the columns to display, the syntax is 'internal name' => 'display name'
	$columns = array(
		'id'	=> __('ID', 'pmlc_plugin'),
		'name'	=> __('Name', 'pmlc_plugin'),
		'slug'	=> __('URL', 'pmlc_plugin'),
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
		<?php if (($count_expired = $list->countBy(array('expire_on <' => date('Y-m-d'), 'expire_on !=' => '0000-00-00', 'is_trashed' => '0'))) > 0): ?>
			<li>
				|
				<a href="<?php echo add_query_arg('type', 'expired', $baseTypeUrl) ?>" class="<?php echo 'expired' == $type ? 'current' : '' ?>">
					<?php _e('Expired', 'pmlc_plugin') ?>
					<span class="count">(<?php echo $count_expired ?>)</span>
				</a>
			</li>
		<?php endif ?>
		<?php if (($count_draft = $list->countBy(array('preset' => '_temp', 'is_trashed' => '0'))) > 0): ?>
			<li>
				|
				<a href="<?php echo add_query_arg('type', 'draft', $baseTypeUrl) ?>" class="<?php echo 'draft' == $type ? 'current' : '' ?>">
					<?php _e('Draft', 'pmlc_plugin') ?>
					<span class="count">(<?php echo $count_draft ?>)</span>
				</a>
			</li>
		<?php endif ?>
		<?php if (($count_preset = $list->countBy(array('preset NOT IN' => array('_temp', ''), 'is_trashed' => '0'))) > 0): ?>
			<li>
				|
				<a href="<?php echo add_query_arg('type', 'preset', $baseTypeUrl) ?>" class="<?php echo 'preset' == $type ? 'current' : '' ?>">
					<?php _e('Preset', 'pmlc_plugin') ?>
					<span class="count">(<?php echo $count_preset ?>)</span>
				</a>
			</li>
		<?php endif ?>
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
		<label for="link-search-input" class="screen-reader-text"><?php _e('Search Links', 'pmlc_plugin') ?>:</label>
		<input id="link-search-input" type="text" name="s" value="<?php echo esc_attr($s) ?>" />
		<input type="submit" class="button" value="<?php _e('Search Links', 'pmlc_plugin') ?>">
	</p>
	</form>
	
	<form method="post" id="link-list">
	<input type="hidden" name="action" value="bulk" />
	<?php wp_nonce_field('bulk-links', '_wpnonce_bulk-links') ?>
	
	<div class="tablenav">
		<div class="alignleft actions">
			<select name="bulk-action">
				<option value="" selected="selected"><?php _e('Bulk Actions', 'pmlc_plugin') ?></option>
				<?php if ('trash' != $type): ?>
					<option value="delete"><?php _e('Move to Trash', 'pmlc_plugin') ?></option>
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
	<table class="widefat pmlc-admin-links">
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
		<tbody id="the-pmlc-admin-link-list" class="list:pmlc-admin-links">
		<?php if ($list->isEmpty()): ?>
			<tr>
				<td colspan="<?php echo count($columns) + 1 ?>"><?php _e('No links found.', 'pmlc_plugin') ?></td>
			</tr>
		<?php else: ?>
			<?php $class = ''; ?>
			<?php foreach ($list as $item): ?>
				<?php $class = ('alternate' == $class) ? '' : 'alternate'; ?>
				<tr class="<?php echo $class; ?>" valign="middle">
					<th scope="row" class="check-column">
						<input type="checkbox" id="link_<?php echo $item['id'] ?>" name="links[]" value="<?php echo esc_attr($item['id']) ?>" />
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
							case 'slug':
								?>
								<td>
									<input class="selectable widefat code" type="text" readonly="readonly" value="<?php echo esc_attr($item->getUrl()) ?>" <?php echo $item['preset'] != '' || $item['is_trashed'] ? 'disabled="disabled"' : '' ?>/>
								</td>
								<?php
								break;
							case 'name':
								?>
								<td>
									<strong>
										<?php if ('trash' == $type): ?>
											<span class="row-title"><?php echo '' != $item['name'] ? $item['name'] : __('no title', 'pmlc_plugin') ?></span>
										<?php elseif ('' != $item['name']): ?>
											<a href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $item['id']), $this->baseUrl)) ?>" class="row-title"><?php echo $item['name'] ?></a>
										<?php else: ?>
											<span class="row-title"><?php _e('no title', 'pmlc_plugin') ?></span>
										<?php endif ?>
											
										<?php if ('_temp' == $item['preset']): ?>
											- <span><?php _e('Draft', 'pmlc_plugin') ?></span>
										<?php elseif ('' != $item['preset']): ?>
											- <span><?php echo $item['preset'] ?> <?php _e('Preset', 'pmlc_plugin') ?></span>
										<?php elseif ('0000-00-00' != $item['expire_on'] and $item['expire_on'] < date('Y-m-d')): ?>
											- <span><?php _e('Expired', 'pmlc_plugin') ?></span>
										<?php endif ?>
									</strong>
									<div class="row-actions">
										<?php if ('trash' != $type): ?>
											<span class="edit"><a class="edit" href="<?php echo esc_url(add_query_arg(array('action' => 'edit', 'id' => $item['id']), $this->baseUrl)) ?>"><?php _e('Edit', 'pmlc_plugin') ?></a></span> |
											<?php if ('' == $item['preset']): ?>
												<span class="stats"><a class="stats" href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-statistics', 'id' => $item['id']), admin_url('admin.php'))) ?>"><?php _e('View Stats', 'pmlc_plugin') ?></a></span> |
											<?php endif ?>
											<span class="delete"><a class="delete" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'delete'), $this->baseUrl), 'delete-link')) ?>"><?php _e('Trash', 'pmlc_plugin') ?></a></span>
										<?php else: ?>
											<span class="restore"><a class="restore" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'restore'), $this->baseUrl), 'restore-link')) ?>"><?php _e('Restore', 'pmlc_plugin') ?></a></span> |
											<span class="delete"><a class="delete" href="<?php echo esc_url(wp_nonce_url(add_query_arg(array('id' => $item['id'], 'action' => 'delete'), $this->baseUrl), 'delete-link')) ?>"><?php _e('Delete Permanently', 'pmlc_plugin') ?></a></span>
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
					<option value="delete"><?php _e('Move to Trash', 'pmlc_plugin') ?></option>
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