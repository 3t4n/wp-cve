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
				<form class="form-horizontal form-label-left">
					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Enable', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" class="js-switch" name="is_enabled" value="1"
										<?php echo $settings['is_enabled'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php _e('Frequency:', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select class="form-control" name="frequency_running">
								<?php foreach ($frequencyRunning as $value => $title) : ?>
									<option value="<?php echo $value; ?>"
										<?php echo $settings['frequency_running'] == $value ? 'selected' : ''; ?> >
										<?php echo $title; ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php _e('Schedule time:', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<select name="time_running[h]">
								<?php for ($h = 0; $h <= 23; $h++) : ?>
									<option value="<?php echo $h; ?>"
										<?php echo $settings['time_running']['h'] == $h ? 'selected' : ''; ?> >
										<?php printf('%02d', $h); ?>
									</option>
								<?php endfor; ?>
							</select>
							<select name="time_running[m]">
								<?php for ($m = 0; $m <= 60; $m++) : ?>
									<option value="<?php echo $m; ?>"
										<?php echo $settings['time_running']['m'] == $m ? 'selected' : ''; ?> >
										<?php printf('%02d', $m); ?>
									</option>
								<?php endfor; ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="letter_title" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Report subject', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['letter_title'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['letter_title']; ?></li>
								</ul>
							<?php endif; ?>
							<input id="letter_title" class="form-control"
							       name="letter_title"
							       value="<?php echo $settings['letter_title']; ?>">
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
							<button class="btn btn-default pull-right btn-popup-close">
								<?php _e('Close', 'wptsaf_security'); ?>
							</button>

							<button class="btn btn-success pull-right" type="submit"
							        data-action="action=wptsaf_security&extension=report&method=settingsSave">
							<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
