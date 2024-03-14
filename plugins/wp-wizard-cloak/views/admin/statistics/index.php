<?php 
/* @var $this PMLC_Admin_Statistics */
/* @var $list PMLC_Link_List */
?>

<?php if ($this->errors->get_error_codes()): ?>
	<?php $this->error() ?>
<?php endif ?>

<div class="wrap">
	<h2><?php _e('WP Wizard Cloak Statistics', 'pmlc_plugin') ?></h2>
	
	<?php 
	// define the columns to display, the syntax is 'internal name' => 'display name'
	$columns = array(
		'id'				=> __('ID', 'pmlc_plugin'),
		'name'				=> __('Name', 'pmlc_plugin'),
		'total_clicks'		=> __('All Time Total Clicks'),
		'unique_clicks'		=> __('All Time Unique Clicks'),
		'total_clicks_24'	=> __('24 Hours Total Clicks'),
		'unique_clicks_24'	=> __('24 Hours Unique Clicks'),
	);
	?>
	<div class="tablenav">
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
	<table class="widefat pmlc-admin-statistics">
		<thead>
		<tr>
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
			<?php echo $col_html; ?>
		</tr>
		</tfoot>
		<tbody id="the-pmlc-admin-statistics-list" class="list:pmlc-admin-statistics">
		<?php if ($list->isEmpty()): ?>
			<tr>
				<td colspan="<?php echo count($columns) ?>"><?php _e('No links found.', 'pmlc_plugin') ?></td>
			</tr>
		<?php else: ?>
			<?php $class = ''; ?>
			<?php foreach ($list as $item): ?>
				<?php $class = ('alternate' == $class) ? '' : 'alternate'; ?>
				<tr class="<?php echo $class; ?>" valign="middle">
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
							case 'name':
								?>
								<td>
									<strong>
										<?php if ('' != $item['name']): ?>
											<a href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-statistics', 'id' => $item['id']), admin_url('admin.php'))) ?>"><?php echo $item['name'] ?></a>
										<?php else: ?>
											<?php _e('no title', 'pmlc_plugin') ?>
										<?php endif ?>
									</strong>
									<div class="row-actions">
										<span class="stats"><a class="stats" href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-statistics', 'id' => $item['id']), admin_url('admin.php'))) ?>"><?php _e('View Stats', 'pmlc_plugin') ?></a></span>
									</div>
								</td>
								<?php
								break;
							default:
								?>
								<td>
									<strong><?php echo $item[$column_id] ?></strong>
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
		<div class="alignleft actions links">
			<?php _e('Download Click Data in CSV format', 'pmlc_plugin') ?>:
			<a href="<?php echo esc_url(add_query_arg(array('page' => 'pmlc-admin-statistics', 'action' => 'export'), admin_url('admin.php'))) ?>"><?php _e('All Data', 'pmlc_plugin') ?></a>
		</div>
		<?php if ($page_links): ?><div class="tablenav-pages"><?php echo $page_links_html ?></div><?php endif ?>
	</div>
	<div class="clear"></div>	
</div>