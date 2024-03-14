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
				<div class="alert alert-success alert-dismissible">
                    <?php _e("Please make sure that you configure options below really carefully and pay attention to every value. Our tools are implemented with smart algorithms which make alot of job automatically to simplify everything. If you have any questions or you not sure about some of the features feel free to contact our support team for professional advise. All your decisions it's a key to your safety!", 'wptsaf_security'); ?>
                </div>

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
							       value="<?php echo $settings['log_rotation'] ?>">
							<p class="field-description">
								&nbsp;0 - <?php _e('Never clean log', 'wptsaf_security'); ?>
								<br />
								-1 - <?php _e('Use value from global settings', 'wptsaf_security'); ?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label for="lock_duration" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Lock duration(min)', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['lock_duration'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['lock_duration']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="lock_duration" class="form-control"
							       	name="lock_duration"
							       	value="<?php echo $settings['lock_duration'] ?>" 
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
						<label for="lock_duration_second" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Second lock duration (min)', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<?php if (!empty($errors['lock_duration_second'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['lock_duration_second']; ?></li>
								</ul>
							<?php endif; ?>
							<div class="input-group">
								<input type="text" id="lock_duration_second" class="form-control" name="lock_duration_second" value="<?php echo $settings['lock_duration_second'] ?>" <?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>>								
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

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
							<button class="btn btn-default pull-right btn-popup-close">
								<?php _e('Cancel', 'wptsaf_security'); ?>
							</button>
							
							<button class="btn btn-success pull-right" type="submit"
							        data-action="action=wptsaf_security&extension=network-monitor&method=settingsSave">
								<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
