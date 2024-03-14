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
?>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $extensionTitle . ': ' . sprintf(__('Log row #%d', 'wptsaf_security'), $row['id']); ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="wrrapper-log">
					<table class="log table table-striped table-bordered">
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
						<tr>
							<?php foreach ($columns as $column) : ?>
								<td><?php echo $row[$column]; ?></td>
							<?php endforeach; ?>
						</tr>
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
