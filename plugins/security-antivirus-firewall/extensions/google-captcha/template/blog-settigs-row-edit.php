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

$id = md5(mt_rand());
$blogDomain = get_site_url($row['blog_id']);
?>
<div class="row" id="<?php echo $id; ?>">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $extensionTitle . ': ' . sprintf(__('Blog Settings #%d', 'wptsaf_security'), $row['id']); ?>
				</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<form class="form-horizontal form-label-left">
					<input type="hidden" id="id" class="form-control" name="id" value="<?php echo $row['id'] ?>">

					<div class="form-group">
						<?php printf(
							__('More information how to get key by link %s', 'wptsaf_security'),
							'<a href="https://www.google.com/recaptcha/intro/index.html" target="_blank">https://www.google.com/recaptcha/intro/index.html</a>'
						); ?>
					</div>
					
					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Blog', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<span class="form-control borderless shadowless">
								<?php echo $blogDomain; ?>
							</span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-3 col-sm-3 col-xs-12 control-label">
							<?php _e('Active', 'wptsaf_security'); ?>
						</label>

						<div class="col-md-9 col-sm-9 col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" id="is_active" class="js-switch" name="is_active" value="1"
										<?php echo $row['is_active'] ? 'checked' : ''; ?>>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label for="key" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Public Key', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="key" class="form-control"
							       name="key"
							       value="<?php echo $row['key'] ?>">
							<?php if (!empty($errors['key'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['key']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<label for="secret_key" class="control-label col-md-3 col-sm-3 col-xs-12">
							<?php echo __('Secret Key', 'wptsaf_security'); ?>
						</label>
						<div class="col-md-9 col-sm-9 col-xs-12">
							<input type="text" id="secret_key" class="form-control"
							       name="secret_key"
							       value="<?php echo $row['secret_key'] ?>">
							<?php if (!empty($errors['secret_key'])) : ?>
								<ul class="parsley-errors-list filled">
									<li><?php echo $errors['secret_key']; ?></li>
								</ul>
							<?php endif; ?>
						</div>
					</div>

					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
							<button class="btn btn-default pull-right btn-popup-close">
								<?php _e('Close', 'wptsaf_security'); ?>
							</button>

							<button class="btn btn-success pull-right" type="submit"
							        data-action="action=wptsaf_security&extension=google-captcha&method=blogSettingsRowSave">
								<?php _e('Submit', 'wptsaf_security'); ?>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	(function ($) {
		var $row = $('#<?php echo $id; ?>'),
			$activate = $('#is_active');
			isLock = false;
			isProcessed = false;

		$row.on('change keyup paste', '#key', function () {
			if ($activate.prop("checked")) {
				isLock = true;
				isProcessed = false;
				$activate.removeAttr('data-verify');
				$activate.trigger('click');
				isLock = false;
			}
		});

		$row.on('change', '#is_active', function () {
			if (isLock) {
				return;
			}

			if (isProcessed) {
				isProcessed = false;
				return;
			}
			isProcessed = true;

			if (!$activate.prop('checked') && (!$('#key').val() || !$('#secret_key').val())) {
				isProcessed = false;
				$activate.trigger('click');
				wptsafDataAction.processAction(
					$row,
					'action=wptsaf_security&extension=google-captcha&method=dialog&args[name]=fill-keys',
					null,
					true
				);
				return;
			}

			if (!$activate.prop('checked') && !$activate.attr('data-verify')) {
				isProcessed = false;
				$activate.trigger('click');
				wptsafDataAction.processAction(
					$row,
					'action=wptsaf_security&extension=google-captcha&method=testCaptcha&args[domain]=<?php echo $blogDomain; ?>&args[key]=' + $('#key').val(),
					null,
					true
				);
			}
		});
	})(jQuery);
</script>

