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

$linkInfoWindow = 'action=wptsaf_security&extension=404-detection&method=dialog&args[name]=pro';
?>
<div class="row">
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
						<label for="log_rotation" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Log rotation(day)', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['log_rotation'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['log_rotation']; ?></li>
								</ul>
							<?php endif; ?>
							<input type="text" id="log_rotation" class="form-control"
							       	name="log_rotation"
							       	value="<?php echo $settings['log_rotation'] ?>"
									>
							<p class="field-description">
								&nbsp;0 - <?php _e('Never clean log', 'wptsaf_security'); ?>
								<br />
								-1 - <?php _e('Use value from global settings', 'wptsaf_security'); ?>
							</p>
						</div>
					</div>

					<h4><?php _e('First ban', 'wptsaf_security'); ?></h4><hr>

					<div class="form-group">
						<label for="count_failed_login_first" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Count failed login', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['count_failed_login_first'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['count_failed_login_first']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="count_failed_login_first" class="form-control"
							       name="count_failed_login_first"
							       value="<?php echo $settings['count_failed_login_first'] ?>"
									<?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
							       >
							    <?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('times', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="time_counting_login_first" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Time counting login', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['time_counting_login_first'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['time_counting_login_first']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="time_counting_login_first" class="form-control"
							       name="time_counting_login_first"
							       value="<?php echo $settings['time_counting_login_first'] ?>"
							       <?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
							       >
							    <?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('min', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="lock_time_first" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Lock time', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['lock_time_first'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['lock_time_first']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="lock_time_first" class="form-control"
							       	name="lock_time_first"
							       	value="<?php echo $settings['lock_time_first'] ?>"
							       	<?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
							      	>
							    <?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('min', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="is_notify_admin_first" class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Notify Admin', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12"  <?php if( !WPTSAF_PRO )  echo ' data-action="'.$linkInfoWindow.'"';?> >
							<div>
								<label>
									<input type="checkbox" id="is_notify_admin_first" class="js-switch"
									       name="is_notify_admin_first" value="1"
										<?php echo $settings['is_notify_admin_first'] ? 'checked' : ''; ?> 
										<?php if( !WPTSAF_PRO ) echo 'readonly'; ?>
										>
								</label>
								<?php if( !WPTSAF_PRO ){ ?>
									&nbsp;
									<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
								<?php } ?>
							</div>
						</div>
					</div>

					<h4><?php _e('Recidive ban', 'wptsaf_security'); ?></h4><hr>

					<div class="form-group">
						<label for="count_failed_login_second" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Count failed login', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['count_failed_login_second'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['count_failed_login_second']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="count_failed_login_second" class="form-control"
							       name="count_failed_login_second"
							       value="<?php echo $settings['count_failed_login_second'] ?>"
							       <?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
							       >
								 <?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('times', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="time_counting_login_second" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Time counting login', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['time_counting_login_second'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['time_counting_login_second']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="time_counting_login_second" class="form-control"
							       	name="time_counting_login_second"
							       	value="<?php echo $settings['time_counting_login_second'] ?>"
							       	<?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
							     	>
							    <?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('min', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="lock_time_second" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Lock time', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['lock_time_second'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['lock_time_second']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="lock_time_second" class="form-control"
								       name="lock_time_second"
								       value="<?php echo $settings['lock_time_second'] ?>"
								       	<?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>
								   		>
								<?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('min', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="is_notify_admin_second" class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Notify Admin', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div>
								<label>
									<input type="checkbox" id="is_notify_admin_second" class="js-switch"
									     name="is_notify_admin_second" value="1"
										<?php echo $settings['is_notify_admin_second'] ? 'checked' : ''; ?>
										<?php if( !WPTSAF_PRO ) echo 'readonly'; ?>
										>
								</label>
								<?php if( !WPTSAF_PRO ){ ?>
									&nbsp;
									<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
								<?php } ?>
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
							        data-action="action=wptsaf_security&extension=login-brute-force&method=settingsSave">
							<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
