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
				<h2><?php echo $extensionTitle . ': ' . sprintf(__('Log row #%d', 'wptsaf_security'), $row['id']); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form class="form-horizontal form-label-left">
					<input type="hidden" id="id" class="form-control" name="id" value="<?php echo $row['id'] ?>">

					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Active', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" class="js-switch" name="is_active" value="1"
										<?php echo $row['is_active'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="lock_count" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Lock count', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<span class="form-control borderless shadowless">
								<?php echo $row['lock_count']; ?>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label for="ip" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('IP address', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="ip" class="form-control"
							       name="ip"
							       value="<?php echo $row['ip'] ?>">
							<?php if (!empty($errors['ip'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['ip']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label for="date_gmt" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Ban date', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="date_gmt" class="form-control daterangepicker form-control has-feedback-left"
							       name="date_gmt"
							       value="<?php echo $row['date_gmt'] ?>">
							<span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
							<?php if (!empty($errors['date_gmt'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['date_gmt']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label for="duration" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Ban time(min)', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12 control-group">
							<div class="input-group">
								<input type="text" id="duration" class="form-control"
							       name="duration"
							       value="<?php echo $row['duration'] ?>"
							       <?php if( !WPTSAF_PRO ) echo 'readonly  data-action="'.$linkInfoWindow.'"'; ?>>
	
								<?php if( !WPTSAF_PRO ){ ?>
									<span class="input-group-btn">
										<button class="btn btn-danger" data-action="<?php echo $linkInfoWindow;?>"><?php _e('pro feature', 'wptsaf_security'); ?></button>
									</span>
								<?php } else { ?>
									<span class="input-group-addon"><?php _e('min', 'wptsaf_security'); ?></span>
								<?php }?>
							</div>
							<?php if (!empty($errors['duration'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['duration']; ?></li>
								</ul>
							<?php endif; ?>
							<p class="field-description">
								&nbsp;0 - <?php _e('Forever ban ip', 'wptsaf_security'); ?>
							</p>
						</div>
					</div>

					<div class="form-group">
						<label for="description" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Description', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<textarea id="description" class="form-control" name="description"><?php echo $row['description'] ?></textarea>
							<?php if (!empty($errors['description'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['description']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label for="change_log_comment" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Comment for change log', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<textarea id="change_log_comment" class="form-control" name="change_log_comment" ><?php echo $row['change_log_comment']; ?></textarea>
							<?php if (!empty($errors['change_log_comment'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['change_log_comment']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<?php if ($row['id']) : ?>
						<div class="form-group">
							<label for="change_log_comment" class="control-label col-md-3 col-sm-3 col-xs-12">
							</label>
							<div class="col-md-9 col-sm-9 col-xs-12">
								<div data-action="action=wptsaf_security&extension=network-monitor&method=managerIpChangeLog&args[id]=<?php echo $row['id']; ?>">
									<span class="btn btn-info"><?php _e('View change log', 'wptsaf_security'); ?></span>
								</div>
							</div>
						</div>
					<?php endif; ?>


					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
							<button class="btn btn-default pull-right btn-popup-close">
								<?php _e('Cancel', 'wptsaf_security'); ?>
							</button>

							<button class="btn btn-success pull-right" type="submit"
							        data-action="action=wptsaf_security&extension=network-monitor&method=managerIpRowSave">
								<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
