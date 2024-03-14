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

$id = 'detail-log-' . mt_rand();
?><div class="row <?php echo $extensionName; ?>">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $extensionTitle . ': ' . __('Detailed log', 'wptsaf_security'); ?></h2>
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
						</tr>
						</thead>
						<tbody>
						<?php $columns = array_keys($header); ?>
						<?php foreach ($rows as $row) : ?>
							<?php $row['type'] = "<span class='label label-{$row['type']}'>" . __( wpToolsSAFHelperClass::getTypeLabel( $row['type'] ), 'wptsaf_security') . "</span>"; ?>
							<tr>
								<?php foreach ($columns as $column) : ?>
									<td class="<?php echo $column; ?>"><?php echo $row[$column]; ?></td>
								<?php endforeach; ?>
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

		$('#<?php echo $id; ?>').dataTable( {
			sDom: '<"top"<"dataTables_group_block"B><"dataTables_group_block"lp>>rt<"bottom"fip>',
			"pageLength": 50,
			"lengthMenu": [50, 100, 200],
			ordering: false,
			searching: false,
			responsive: true,
			buttons: [
				{
					text: '<?php esc_attr_e('Download log', 'wptsaf_security'); ?>',
					action: function () {
						window.open(wptsafSecurity.ajaxUrl + '?action=wptsaf_security&extension='+'sy'+'stem-log&method=logExport&nonce=' + wptsafSecurity.ajaxNonce,'_blank');
					}
				},
				{
					text: '<?php esc_attr_e('Clear log', 'wptsaf_security'); ?>',
					action: function () {
						wptsafDataAction.processAction(null, 'action=wptsaf_security&extension='+'sy'+'stem-log&method=logAskClear', null, false);
					}
				}
			]
		});
	})(jQuery);
</script>
