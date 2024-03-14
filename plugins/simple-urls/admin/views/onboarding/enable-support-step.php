<?php

use LassoLite\Classes\Enum;

$is_subscribe_setting         = $lasso_options[Enum::IS_SUBSCRIBE] ?? '';
$is_subscribe_setting_checked = 'true' === $is_subscribe_setting || empty( $lasso_options[Enum::IS_SUBSCRIBE] ) ? 'checked' : '';
$email_support                = ! empty( $lasso_options[Enum::EMAIL_SUPPORT] ) ? $lasso_options[Enum::EMAIL_SUPPORT] : get_option( 'admin_email' );
?>

<div class="tab-item d-none" data-step="enable-support">
	<div class="progressbar_container">
		<ul class="progressbar">
			<li class="step-get-started complete">Welcome</li>
			<li class="step-display-design complete" data-step="display">Display Designer</li>
			<li class="step-amazon-info complete" data-step="amazon">Amazon Associates</li>
			<li class="step-enable-support active">Enable Support</li>
			<?php if ( $should_show_import_step ) : ?>
				<li class="step-import">Imports</li>
			<?php endif; ?>
			<li class="step-get-started">Done</li>
		</ul>
	</div>

	<div class="onboarding_header text-center">
		<h1 class="font-weight-bold">Enable Lasso Support</h1>
		&nbsp;<a href="https://support.getlasso.co/en/articles/6425910-enabling-support-for-lasso-lite" target="_blank" class="btn btn-sm learn-btn">
			<i class="far fa-info-circle"></i> Learn
		</a>
	</div>

	<form method="post" onsubmit="event.preventDefault()">
		<div id="enable-support-wrapper">
			<p class="large">We want to help you grow your revenue.</p>
			<p>What is the best email to reach you at?</p>
			<div class="form-group">
				<input type="text" name="email" id="email" required class="form-control" value="<?php echo $email_support; ?>" placeholder="Email">
				<p class="js-error text-danger mt-1 mb-1"></p>
			</div>
			<div class="form-check mb-3">
				<input id="subscribe" <?php echo $is_subscribe_setting_checked ?> type="checkbox">
				<label class="form-check-label" for="subscribe">
					<small>Subscribe and learn affiliate marketing in just 3 minutes per week</small>
					<span></span>
				</label>
			</div>
			<div class="text-center">
				<p><button id="btn-save-support" class="btn">Enable Support</button></p>
				<div class="clearfix"></div>
				<div class="clearfix"></div>
				<small class="mt-4 dismiss next-step">No thanks, I don't want support</small>
			</div>
		</div>
	</form>
</div>
