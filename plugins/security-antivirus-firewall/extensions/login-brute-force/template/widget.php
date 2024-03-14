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

?><div class="x_title">
	<h2>
		<?php wpToolsSAFHelperClass::getCheckIcon($isEnabled);  ?>
		<?php echo $title; ?>
		&nbsp;
		<button class="btn btn-xs <?php echo $isEnabled ? 'btn-default' : 'btn-success'; ?>" type="button"
		        data-action="action=wptsaf_security&extension=login-brute-force&method=setEnable&args[isEnabled]=<?php echo $isEnabled ? 0 : 1; ?>">
			<?php 
				if($isEnabled){
					echo __('Deactivate', 'wptsaf_security');
				} else {
					echo __('Activate', 'wptsaf_security');
				}
			?>
		</button>
		<button class="btn btn-default btn-xs" type="button"
		        data-action="action=wptsaf_security&extension=login-brute-force&method=settings">
		<?php _e('Settings', 'wptsaf_security'); ?>
		</button>
	</h2>
	<div class="clearfix"></div>
</div>

<div class="x_content">
	<p><?php echo $description; ?></p>

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
				<tr >
					<?php foreach ($columns as $column) : ?>
						<td <?php
							if( $column!='ip'  || $row[$column]=='127.0.0.1' ) echo ' data-action="action=wptsaf_security&extension=login-brute-force&method=logRow&args[id]='.$row['id'].'"';
						?>>
							<?php if($column=='status'){ ?>
								<span class="label label-<?php echo $row[$column]=='failed'?'warning':'success'; ?>">
									<?php echo $row[$column]; ?>
								</span>
							<?php }else if($column=='ip') {
									wpToolsSAFHelperClass::getIpInfo( $row[$column] ); 
								}else echo $row[$column];  ?>
						</td>
					<?php endforeach; ?>
					<td>
					<?php if( $row['status'] == 'failed' ){ ?>
						<button class="btn btn-danger btn-xs" data-action="<?php
							if( WPTSAF_PRO ){
						 		echo wpToolsSAFPro::getButton('login-brute-force', array( 'ip'=> $row['ip'] ) );
						 	} else {
						 		echo 'action=wptsaf_security&extension=404-detection&method=dialog&args[name]=pro';
						 	}
						 	?>">
							<?php _e('Ban', '');?>	

						</button><?php
							if( !WPTSAF_PRO ){
							echo '<span class="label label-info saf-label-button">'.__('pro', 'wptsaf_security').'</span>';
						} ?>
					<?php } ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<p>
		<button class="btn btn-info col-md-6 col-sm-6 col-md-offset-3" type="button" data-action="action=wptsaf_security&extension=login-brute-force&method=log">
			<?php _e('Detailed log', 'wptsaf_security'); ?>
		</button>
	</p>
</div>
