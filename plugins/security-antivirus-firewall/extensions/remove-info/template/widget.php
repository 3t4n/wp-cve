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
		<?php wpToolsSAFHelperClass::getCheckIcon(1);  ?>
		<?php echo $title; ?>
		&nbsp;
		<button class="btn btn-xs btn-default" type="button"
		        data-action="action=wptsaf_security&extension=remove-info&method=settings">
			<?php echo __('Settings', 'wptsaf_security'); ?>
		</button>
	</h2>
	<div class="clearfix"></div>
</div>

<div class="x_content">
	<p>
		<?php echo $description; ?>
	</p>

	<table class="table borderless">
		<tbody>
			<tr>
				<td>
					<?php _e('Hide Wordpress Info', 'wptsaf_security'); ?>
				</td>
				<td>
					<span class="label label-<?php echo $settings['is_remove_info'] ? 'success' : 'warning'; ?>">
						<?php echo $settings['is_remove_info'] ? __('Enabled', 'wptsaf_security') : __('Disabled', 'wptsaf_security'); ?>
					</span>
				</td>
			</tr>
		</tbody>
	</table>
</div>
