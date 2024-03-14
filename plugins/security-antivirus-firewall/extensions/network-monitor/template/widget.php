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
?>
<div class="x_title">
	<h2>
		<?php wpToolsSAFHelperClass::getCheckIcon($isEnabled);  ?>
		<?php echo $title; ?>
		&nbsp;
		<button class="btn btn-xs <?php echo $isEnabled ? 'btn-default' : 'btn-success'; ?>" type="button"
		        data-action="action=wptsaf_security&extension=network-monitor&method=setEnable&args[isEnabled]=<?php echo $isEnabled ? 0 : 1; ?>">
			<?php 
				if($isEnabled){
					echo __('Deactivate', 'wptsaf_security');
				} else {
					echo __('Activate', 'wptsaf_security');
				}
			?>
		</button>
		<button class="btn btn-default btn-xs" type="button"
		        data-action="action=wptsaf_security&extension=network-monitor&method=settings">
			<?php echo __('Settings', 'wptsaf_security'); ?>
		</button>
	</h2>
	<div class="clearfix"></div>
</div>

<div class="x_content">
	<p>
		<?php echo $description; ?>
	</p>

	<table class="table table-hover">
		<thead>
		<tr>
			<?php foreach ($logHeader as $title) : ?>
				<th>
					<?php echo $title; ?>
				</th>
			<?php endforeach; ?>
			<th></th>
		</tr>
		</thead>
		<tbody>
		<?php $columns = array_keys($logHeader); ?>
		<?php foreach ($rows as $row) : ?>
			<tr class="row-<?php echo $row['id']; ?>">
				<?php foreach ($columns as $column) : ?>
					<td><?php 
						if( $column=='ip' )	wpToolsSAFHelperClass::getIpInfo( $row[$column] ); 
							else echo $row[$column]; ?>	
					</td>
				<?php endforeach; ?>

				<td>
					<button class="btn btn-success btn-xs"
					        data-action="action=wptsaf_security&extension=network-monitor&method=managerIpRow&args[id]=<?php echo $row['id']; ?>">
						<?php _e('Edit', 'wptsaf_security'); ?>
					</button>

					<?php if ($row['is_active']) : ?>
						<button class="btn btn-danger btn-xs"
						        data-action="action=wptsaf_security&extension=network-monitor&method=managerIpDisableBanById&args[id]=<?php echo $row['id']; ?>">
							<?php _e('Unban', 'wptsaf_security'); ?>
						</button>
					<?php else : ?>
						<button class="btn btn-danger btn-xs"
						        data-action="action=wptsaf_security&extension=network-monitor&method=managerIpBanById&args[id]=<?php echo $row['id']; ?>">
							<?php _e('Ban', 'wptsaf_security'); ?>
						</button>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div class="buttons">
		<button type="button" class="btn btn-info col-md-4 col-sm-4 col-md-offset-1"
		        data-action="action=wptsaf_security&extension=network-monitor&method=log">
			<?php _e('Detailed log', 'wptsaf_security'); ?>
		</button>
		<button type="button" class="btn btn-info col-md-4 col-sm-4 col-md-offset-1"
		        data-action="action=wptsaf_security&extension=network-monitor&method=managerIp">
		<?php _e('IP Ban/Deny Manager', 'wptsaf_security'); ?>
		</button>
	</div>
</div>
