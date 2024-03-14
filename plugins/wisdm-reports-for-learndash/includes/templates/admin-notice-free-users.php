<?php
/**
 * Template to display the Learner activity onboarding notice.
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div class="notice notice-error wrld-la-main-container" style="padding:0px !important;">
	<a class="wrld-dismiss-notice-link" href="<?php echo esc_html( $dismiss_attribute ); ?>"><span class="dashicons dashicons-no-alt"></span></a>
	<div class="wrld-la-logo">
		<img class="wrld-la-logo-img" src='<?php echo esc_html( $wisdm_logo ); ?>'>
	</div>
	<div class="wrld-la-center">
		<div class="wrld-la-head-text2">
			Get in-depth insights with WISDM Report for LearnDash Pro
		</div>
		<div class="wrld-la-sub-text2">
			We have an <span>Amazing Offer</span> for you!
		</div>
		<div class="special-section">
			<div class="triangle-div">
				Upgrade to Pro at just <s>$120</s> $99
			</div>
			<div class="inverted-triangle"></div>
		</div>
		<button><a href="https://wisdmlabs.com/checkout/?edd_action=add_to_cart&download_id=707478&edd_options%5Bprice_id%5D=7&discount=upgradetopro&utm_source=wrld&utm_medium=wrld_update_banner&utm_campaign=wrld_in_plugin_settings_tab" target="_blank">Upgrade Now</a></button>
	</div>
</div>
