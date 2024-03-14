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
				<h2><?php echo $extensionTitle . ': ' . sprintf(__('Log row #%d', 'wptsaf_security'), $row['id']); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div>
					<?php require(WPTSAF_DIR . wptsafExtension404Detection::getInstance()->getExtensionDir() . 'dialogs/log-row.php'); ?>
				</div>
				<br>
				<div class="clear"></div>

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
						<tr>
							<?php foreach ($columns as $column) : ?>
								<td class="<?php echo $column; ?>">
									<?php 
										if( $column=='ip' ){
											wpToolsSAFHelperClass::getIpInfo( $row['ip'] );
										} else echo $row[$column]; 
									?>
								</td>
							<?php endforeach; ?>
						</tr>
					</tbody>
				</table>

				<div class="ln_solid"></div>
				<div class="buttons">

					<button class="btn btn-default pull-right btn-popup-close">
						<?php _e('Close', 'wptsaf_security'); ?>
					</button>
					<button class="btn btn-danger pull-right" data-action="<?php
					if( WPTSAF_PRO ){ 
						echo wpToolsSAFPro::getButton('404-detection', array( 'ip'=> $row['ip'] ) );
					} else {
						echo 'action=wptsaf_security&extension=404-detection&method=dialog&args[name]=pro';
					}
					?>">
						<?php _e('Ban', 'wptsaf_security'); ?>
						<?php if( !WPTSAF_PRO ){ ?>
							<span class="badge"><?php _e('pro feature', 'wptsaf_security'); ?></span>
						<?php } ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
