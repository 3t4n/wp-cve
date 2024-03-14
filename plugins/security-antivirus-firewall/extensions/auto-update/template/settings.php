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

?><div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $extensionTitle . ': ' . __('Settings', 'wptsaf_security'); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<p>
				<?php _e("Keep your site up to date to stay safe and secured. Auto update module provide you easy tool to enable updates of your wordpress update core files, plugins and themes installed on your website. All scripts, even wordpress core files, third-party plugins and themes may potentially be vulnerable to different type of attacks. Making sure you always have the newest versions of Wordpress, all plugins and themes installed on your website minimizes the risk to be hacked. Keep everything up to date to protect your website and your information. ", 'wptsaf_security'); ?>
				</p>
				<form class="form-horizontal form-label-left">
					<div class="form-group">
						<label for="is_update_core" class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Core Auto Update', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="is_update_core" class="js-switch" name="is_update_core" value="1"
									       <?php echo $settings['is_update_core'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="is_update_plugins" class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Plugins Auto Update', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="is_update_plugins" class="js-switch" name="is_update_plugins" value="1"
										<?php echo $settings['is_update_plugins'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="is_update_themes" class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Themes Auto Update', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="is_update_themes" class="js-switch" name="is_update_themes" value="1"
										<?php echo $settings['is_update_themes'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
							<button class="btn btn-default pull-right btn-popup-close">
								<?php _e('Close', 'wptsaf_security'); ?>
							</button>

							<button class="btn btn-success pull-right" type="submit"
							        data-action="action=wptsaf_security&extension=auto-update&method=settingsSave">
							<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
