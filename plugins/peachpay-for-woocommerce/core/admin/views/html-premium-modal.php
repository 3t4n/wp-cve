<?php
/**
 * PeachPay Admin settings premium modal.
 *
 * @package PeachPay
 */

defined( 'PEACHPAY_ABSPATH' ) || exit;

/**
 * Generate the full premium upgrade modal with all functionality. Only the button needs to be
 * added just before calling this.
 */
$peachpay_premium_modal = function () {
	$peachpay_premium_config = PeachPay_Capabilities::get( 'woocommerce_premium', 'config' );
	?>
		<div id="pp-premium-modal">
			<div class="premium-modal-content">
				<span id="premium-modal-close" class="premium-modal-close">&times;</span>

				<div id="pp-premium-activation-page">
					<p class="premium-modal-header">
						<?php echo isset( $peachpay_premium_config['canceled'] ) ? esc_html_e( 'Get PeachPay', 'peachpay-for-woocommerce' ) : esc_html_e( 'Get started with PeachPay', 'peachpay-for-woocommerce' ); ?>
						<?php require PeachPay::get_plugin_path() . '/public/img/crown-icon.svg'; ?>
						<span style="color: #FF876C">Premium!</span>
					</p>

					<div class="feature-list">
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Express Checkout
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Address Autocomplete
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Field Editor
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Currency Switcher
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Product Recommendations
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Priority support
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							Remove PeachPay branding
						</p>
						<p class="feature-element">
							<?php require PeachPay::get_plugin_path() . '/public/img/checkmark-green.svg'; ?>
							<strong>No service fee</strong>
						</p>
					</div>

					<div class="premium-modal-actions" style="flex-direction: row;padding-top: 12px;">
						<form id="premium-monthly-form" action="<?php echo esc_url_raw( peachpay_api_url( 'prod' ) . 'api/v1/premium/checkoutPage?type=monthly' ); ?>" method="post" style="flex-grow: 1;">
							<input type="text" name="merchant_id" value="<?php echo esc_html( peachpay_plugin_merchant_id() ); ?>" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px;" />
							<input type="text" name="return_url" value="<?php echo esc_url_raw( Peachpay_Admin::admin_settings_url( 'peachpay', 'payment' ) ); ?>" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px;" />
							<button type="submit" class="button pp-button-secondary" style="width: 100%;">
								<p class="premium-modal-actions-text">
									<?php echo esc_html_e( '$9.99 monthly', 'peachpay-for-woocommerce' ); ?>
								</p>
							</button>
						</form>

						<form id="premium-yearly-form" action="<?php echo esc_url_raw( peachpay_api_url( 'prod' ) . 'api/v1/premium/checkoutPage?type=yearly' ); ?>" method="post" style="flex-grow: 1;">
							<input type="text" name="merchant_id" value="<?php echo esc_html( peachpay_plugin_merchant_id() ); ?>" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px;" />
							<input type="text" name="return_url" value="<?php echo esc_url_raw( Peachpay_Admin::admin_settings_url( 'peachpay', 'payment' ) ); ?>" style="visibility: hidden; position: absolute; top: -1000px; left: -1000px;" />
							<button type="submit" class="button pp-button-primary" style="width: 100%;">
								<p class="premium-modal-actions-text">
									<?php echo esc_html_e( '$99 annually', 'peachpay-for-woocommerce' ); ?>
								</p>
							</button>
						</form>
					</div>
					<div style="text-align:center;">
						<p><?php esc_html_e( 'Have a coupon code?', 'peachpay-for-woocommerce' ); ?> <a href="#" id="pp-show-coupon-activation-page"><?php esc_html_e( 'Continue here', 'peachpay-for-woocommerce' ); ?></a></p>
					</div>
				</div>

				<!-- Coupon activation -->
				<div id="pp-coupon-activation-page" class="hide">
					<p class="premium-modal-header" style="margin-left: 0;">
						<?php esc_html_e( 'Enter coupon code', 'peachpay-for-woocommerce' ); ?>
					</p>
					<form id="pp-coupon-activation-form" style="display:flex;flex-direction:row;gap: 8px;">

						<input type="hidden" name="merchant_id" value="<?php echo esc_html( peachpay_plugin_merchant_id() ); ?>" />

						<input type="text" name="coupon_code" value="" placeholder="xxxx-xxxx-xxxx-xxxx" style="line-height: 26px; padding: 8px 10px;margin: 0;font-size: 13px;flex-grow: 1;" required/>

						
						<button type="submit" class="button pp-button-primary" id="pp-submit-coupon-activation" style="min-width: 110px;padding: 8px 10px;">
							<img class="loading-spinner hide" style="max-height: 2em;" src="<?php peachpay_version_url( 'public/img/spinner.svg' ); ?>" alt="Throbber">
							<p class="text "style="font-size: 16px;font-family: Inter, sans-serif;line-height: 26px;margin: 0;padding: 0;">
								<?php echo esc_html_e( 'Submit', 'peachpay-for-woocommerce' ); ?>
							</p>
						</button>
					</form>
					<span id="pp-coupon-activation-error" style="font-size: smaller;color: red;"></span>
					<div style="text-align:center;">
						<p><?php esc_html_e( "Don't have a coupon code?", 'peachpay-for-woocommerce' ); ?> <a href="#" id="pp-hide-coupon-activation-page"><?php esc_html_e( 'Continue here', 'peachpay-for-woocommerce' ); ?></a></p>
					</div>
				</div>

				
			</div>
		</div>
	<?php
}
?>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		document.querySelector('body').insertAdjacentHTML('beforeend', `
			<?php echo esc_html( $peachpay_premium_modal() ); ?>
		`);
		const $premiumModal = document.querySelector('#pp-premium-modal');

		document.querySelectorAll('.pp-button-continue-premium').forEach((element) => {
			element.addEventListener('click', (event) => {
				event.preventDefault();
				$premiumModal.style.display = 'block';
			});
		});

		document.querySelector('#premium-modal-close').addEventListener('click', () => {
			$premiumModal.style.display = 'none';
		});

		document.querySelector("#pp-show-coupon-activation-page").addEventListener('click', (e) => { 
			e.preventDefault();

			document.querySelector("#pp-coupon-activation-page")?.classList.remove("hide");
			document.querySelector("#pp-premium-activation-page")?.classList.add("hide");
		});

		document.querySelector("#pp-hide-coupon-activation-page").addEventListener('click', (e) => { 
			e.preventDefault();

			document.querySelector("#pp-coupon-activation-page")?.classList.add("hide");
			document.querySelector("#pp-premium-activation-page")?.classList.remove("hide");
		});

		document.querySelector("#pp-coupon-activation-form").addEventListener('submit', async (e) => {
			e.preventDefault();

			const startLoading = () => {
				document.querySelector("#pp-coupon-activation-error").innerText = "";
				document.querySelector("#pp-submit-coupon-activation").disabled = true;
				document.querySelector("#pp-submit-coupon-activation .loading-spinner").classList.remove("hide");
				document.querySelector("#pp-submit-coupon-activation .text").classList.add("hide");
			};

			const stopLoading = (message) => {
				document.querySelector("#pp-submit-coupon-activation").disabled = false;
				document.querySelector("#pp-submit-coupon-activation .loading-spinner").classList.add("hide");
				document.querySelector("#pp-submit-coupon-activation .text").classList.remove("hide");
				document.querySelector("#pp-coupon-activation-error").innerText = message;
			};

			const formData = new FormData(e.target);
			const code = formData.get('coupon_code');
			const merchantId = formData.get('merchant_id');

			try{
				if(document.querySelector("#pp-submit-coupon-activation").disabled) {
					return;
				}

				startLoading();
				
				const response = await fetch("<?php echo esc_url_raw( peachpay_api_url( 'prod' ) . 'api/v1/coupon/verification' ); ?>", {
					method: "POST",
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify({
						code: code,
						merchantId: merchantId
					})
				});

				const result = await response.json();

				if(result.success) {
					window.location.href = "<?php echo esc_url_raw( Peachpay_Admin::admin_settings_url( 'peachpay', 'payment' ) ); ?>";
				} else {
					stopLoading(result.message);
				}
			} catch (e) {
				stopLoading("Something went wrong. Please try again later.");
			}
		});

		window.addEventListener('click', (event) => {
			if(event.target === $premiumModal) {
				$premiumModal.style.display = 'none';
			}
		});
	});
</script>
