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
		        data-action="action=wptsaf_security&extension=google-captcha&method=setEnable&args[isEnabled]=<?php echo $isEnabled ? 0 : 1; ?>">
			<?php if($isEnabled) _e('Deactivate', 'wptsaf_security'); else _e('Activate', 'wptsaf_security'); ?>
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
			<th>
				<?php _e('Blog', 'wptsaf_security'); ?>
			</th>
			<th>
				<?php _e('Active', 'wptsaf_security'); ?>
			</th>
			<th>
				<?php _e('Actions', 'wptsaf_security'); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach ($rows as $row) : ?>
			<tr>
				<td><?php echo get_site_url($row['blog_id']); ?></td>
				<td><?php echo $row['is_active'] ? __('Yes', 'wptsaf_security') : __('No', 'wptsaf_security'); ?></td>
				<td>
					<button class="btn btn-success btn-xs"
					        data-action="action=wptsaf_security&extension=google-captcha&method=blogSettingsRowEdit&args[id]=<?php echo $row['id']; ?>">
						<?php _e('Settings', 'wptsaf_security'); ?>
					</button>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p>
		<button class="btn btn-info col-md-6 col-sm-6 col-md-offset-3" type="button"
		        data-action="action=wptsaf_security&extension=google-captcha&method=blogSettings">
			<?php _e('All blogs', 'wptsaf_security'); ?>
		</button>
	</p>
</div>
