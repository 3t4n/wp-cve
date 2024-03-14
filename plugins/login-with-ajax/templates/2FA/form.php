<?php
/**
 * 2FA Verification Template, which includes inner modal HTM and is shown when a user selects a verification method and verifies it.
 *
 * @version 1.0.0
 * @package Login With Ajax
 */
$methods = \Login_With_AJAX\TwoFA::get_available_methods();
$site_icon = get_site_icon_url();
?>
<div class="lwa-2FA <?php if ( count($methods) === 1 ) echo 'single-method'; ?>" id="lwa-2FA">
	<?php if ( $site_icon ) : ?>
		<div class="site-icon"><img src="<?php echo esc_url($site_icon); ?>"></div>
	<?php endif; ?>
	<h3><?php esc_html_e('Verify Your Identity', 'login-with-ajax'); ?></h3>
	<?php do_action('lwa_2FA_form_top'); ?>
	<div class="lwa-2FA-method-forms">
		<?php
			foreach( $methods as $method ){
				echo $method::get_form();
			}
		?>
	</div>
	<?php if( count($methods) > 1 ): ?>
		<form class="lwa-2FA-method-selection">
			<p><?php esc_html_e('Please enable one or more of the options below:', 'login-with-ajax-pro'); ?></p>
			<div class="lwa-2FA-methods">
				<?php foreach( $methods as $type => $method ) : ?>
					<label class="lwa-2FA-method lwa-2FA-method-<?php echo esc_attr($type); ?>" data-method="<?php echo esc_attr($type); ?>" id="lwa-2FA-method-<?php echo esc_attr($type); ?>" type="button" style="<?php if( $method::$svg_icon ) echo "--2FA-icon: url('data:image/svg+xml;base64," . base64_encode($method::$svg_icon) . "');"; ?>">
						<input type="radio" name="2FA" value="<?php echo esc_attr($type); ?>" id="lwa-2FA-method-select-<?php echo esc_attr($type); ?>">
						<div class="lwa-2FA-method-title">
							<?php echo $method::get_name(); ?>
						</div>
						<div class="lwa-2FA-method-desc">
							<!-- ex: Receive an email at x@yz.com -->
						</div>
					</label>
				<?php endforeach; ?>
				<input type="hidden" name="2FA_request" value="request">
				<input type="hidden" name="login-with-ajax" value="2FA">
			
			</div>
		</form>
		<p class="lwa-2FA-footer-links">
			<a class="lwa-2FA-select-method" href="#"><?php esc_html_e('Try Another Verification Method', 'login-with-ajax-pro'); ?></a>
		</p>
	<?php endif; ?>
	<?php do_action('lwa_2FA_form_bottom'); ?>
</div>