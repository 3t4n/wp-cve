<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

$id = 'detail-log-' . mt_rand();?>

<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $extensionTitle . ': ' . __('IP Ban/Deny Manager', 'wptsaf_security'); ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<?php if ($limitMessage) : ?>
					<div class="limit-message alert alert-warning">
						<?php echo $limitMessage; ?>
					</div>
				<?php endif; ?>

				<div class="wrapper-log">
					<table id="<?php echo $id; ?>" class="log table table-striped table-bordered">
						<thead>
						<tr>
							<?php foreach ($header as $title) : ?>
								<th>
									<?php echo __($title, 'wptsaf_security'); ?>
								</th>
							<?php endforeach; ?>
							<th><?php _e('Actions', 'wptsaf_security'); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php $columns = array_keys($header); ?>
						<?php foreach ($rows as $row) : ?>
							<tr id="<?php echo $row['id']; ?>">
								<?php foreach ($columns as $column) : ?>
									<td>
										<?php echo $row[$column]; ?>
									</td>
								<?php endforeach; ?>

								<td class="actions"><?php echo $row['id']; ?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="clear"></div>

				<div class="ln_solid"></div>
				<div class="buttons">
					<button class="btn btn-default pull-right btn-popup-close">
						<?php _e('Close', 'wptsaf_security'); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function ($) {
		var $dataTableLog = $('#<?php echo $id; ?>').dataTable({
			sDom: '<"top"<"dataTables_group_block"B><"dataTables_group_block"lp>>rt<"bottom"ip>',
			"pageLength": 50,
			"lengthMenu": [50, 100, 200],
			ordering: false,
			searching: true,
			responsive: true,
			buttons: [
				{
					text: '<?php _e('Download log', 'wptsaf_security'); ?>',
					action: function () {
						window.open(wptsafSecurity.ajaxUrl + '?action=wptsaf_security&extension=network-monitor&method=managerIpExportLog&nonce=' + wptsafSecurity.ajaxNonce,'_blank');
					}
				},
				{
					text: '<?php _e('Add IP address', 'wptsaf_security'); ?>',
					className: "btn-info",
					action: function () {
						wptsafDataAction.processAction(null, 'action=wptsaf_security&extension=network-monitor&method=managerIpRow');
					}
				}
			],
			columns: [
				{
					data: 'ip'
				},
				{
					data: 'permanent',
					render: function (data, type, row ) {
						if(type!='display') return data;
						var retHtml = '';
						if(data==1) retHtml = '<span class="label label-info"><?php _e('Permanent', 'wptsaf_security');?></span>';
						return retHtml;
					}
				},
				{
					data: 'is_active',
					render: function (data, type, row ) {
						if (typeof data == 'boolean') {
							return data ? 1 : 0;
						}
						return parseInt(data);
					},
					render: function (data, type, row ) {
						if(type!='display') return data;
						var retHtml = '';
						if(data==1) retHtml = '<span class="label label-info"><?php _e('Active', 'wptsaf_security');?></span>';
						return retHtml;
					}
				},
				{
					data: 'description'
				},
				{
					data: 'actions',
					render: function (data, type, row ) {
						var id = parseInt(data);

						return '<button class="btn btn-success btn-xs"'
							+ 'data-action="action=wptsaf_security&extension=network-monitor&method=managerIpRow&args[id]=' + id + '"'
							+ '>'
							+ '<?php _e('Edit', 'wptsaf_security'); ?>'
							+ '</button>';
					}
				}
			],
			initComplete: function () {
				var $this = $(this),
					$select = $('<select class="lock_type">'
						+ '<option value="all" selected><?php _e('All', 'wptsaf_security'); ?></option>'
						+ '<option value="permanent"><?php _e('Permanent', 'wptsaf_security'); ?></option>'
						+ '<option value="active"><?php _e('Active', 'wptsaf_security'); ?></option>'
						+ '</select>');

				var $tr = $('<tr>');
				$tr.append('<th><input type="text" class="ip" placeholder="<?php _e('Search IP', 'wptsaf_security'); ?>" /></th>'); // ip
				$tr.append('<th>'
					+ '<select class="permanent">'
						+ '<option value="" selected><?php _e('All', 'wptsaf_security'); ?></option>'
						+ '<option value="1"><?php _e('Permanent', 'wptsaf_security'); ?></option>'
						+ '<option value="0"><?php _e('Temporary', 'wptsaf_security'); ?></option>'
					+ '</select>'
					+ '</th>');
				$tr.append('<th>'
					+ '<select class="is_active">'
					+ '<option value="" selected><?php _e('All', 'wptsaf_security'); ?></option>'
					+ '<option value="1"><?php _e('Active', 'wptsaf_security'); ?></option>'
					+ '<option value="0"><?php _e('Inactive', 'wptsaf_security'); ?></option>'
					+ '</select>'
					+ '</th>');
				$tr.append('<th>'); // description
				$tr.append('<th>'); // actions
				$this.find('thead').append($tr);

				$this.on('keyup change', 'input.ip', function () {
					var $this = $(this);
					$dataTableLog.dataTable().api().columns(0).search($this.val()).draw();
				});
				$this.on('change', 'select.permanent', function () {
					var $this = $(this);
					$dataTableLog.dataTable().api().columns(1).search($this.val()).draw();
				});
				$this.on('change', 'select.is_active', function () {
					var $this = $(this);
					$dataTableLog.dataTable().api().columns(2).search($this.val()).draw();
				});
			}
		});
	})(jQuery);
</script>
