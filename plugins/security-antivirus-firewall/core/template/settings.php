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

?><form method="post" class="form-horizontal form-label-left">
	<div class="form-group">
		<label for="log_rotation" class="control-label col-md-3 col-sm-3 col-xs-12">
			<?php echo __('Log rotation(day)', 'security-antivirus-firewall'); ?>
		</label>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<?php if (!empty($errors['log_rotation'])) : ?>
				<ul class="parsley-errors-list filled">
					<li><?php echo $errors['log_rotation']; ?></li>
				</ul>
			<?php endif; ?>
			<input type="text" id="log_rotation" class="form-control"
			       name="log_rotation"
			       value="<?php echo $settings['log_rotation'] ?>">
			<p class="field-description">
				&nbsp;0 - <?php _e('Never clean log', 'security-antivirus-firewall'); ?>
			</p>
		</div>
	</div>

	<div class="form-group">
		<label for="notification_emails" class="control-label col-md-3 col-sm-3 col-xs-12">
			<?php echo __('Notification emails', 'security-antivirus-firewall'); ?>
		</label>
		<div class="col-md-9 col-sm-9 col-xs-12">
			<?php if (!empty($errors['notification_emails'])) : ?>
				<ul class="parsley-errors-list filled">
					<li><?php echo $errors['notification_emails']; ?></li>
				</ul>
			<?php endif; ?>
			<textarea id="notification_emails" class="form-control"
			          cols="30" rows="8"
			          name="notification_emails"
			><?php echo implode("\n", $settings['notification_emails']); ?></textarea>
			<p class="field-description">
				<?php _e('Each email in new line', 'security-antivirus-firewall'); ?>
			</p>
		</div>
	</div>

	<div class="ln_solid"></div>
	<div class="form-group">
		<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
			<button type="submit" class="btn btn-success pull-right">Save</button>
		</div>
	</div>
</form>