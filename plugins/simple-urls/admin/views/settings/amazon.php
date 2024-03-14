<?php
/**
 * URL links
 *
 * @package Lasso URL links
 */

use LassoLite\Classes\Amazon_Api;
use LassoLite\Classes\Config;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Setting;


$lasso_options       = Setting::get_settings();

$amazon_default_tracking_country = ! empty( $lasso_options['amazon_default_tracking_country'] )
	? $lasso_options['amazon_default_tracking_country'] : '1';
$countries_dd = Helper::get_countries_dd( $amazon_default_tracking_country );

$amazon_tracking_id   = $lasso_options['amazon_tracking_id'] ?? '';
$amazon_access_key_id = $lasso_options['amazon_access_key_id'] ?? '';
$amazon_secret_key    = $lasso_options['amazon_secret_key'] ?? '';

$is_valid_tracking_id = empty( $amazon_tracking_id ) ? true : Amazon_Api::validate_tracking_id( $amazon_tracking_id );
$tracking_id_class = $is_valid_tracking_id ? '' : ' invalid-field';
$tracking_id_invalid_class = $is_valid_tracking_id ? ' d-none' : '';

$amazon_pricing_daily = $lasso_options['amazon_pricing_daily'] ?? true;
$update_price_checked = $amazon_pricing_daily ? 'checked' : '';
?>

<?php Config::get_header(); ?>

<!-- GENERAL SETTINGS -->
<section class="px-3 py-5">
	<div class="container lite-container">
		<!-- HEADER -->
		<?php require_once 'header.php'; ?>

        <form class="lasso-admin-settings-form" autocomplete="off" action="">
			<!-- AMAZON -->
			<div class="row mb-5">
				<div class="col-lg">

					<div class="white-bg rounded shadow p-4 mb-4">
						<!-- AMAZON TRACKING ID -->
						<section>
							<h3>Amazon Associates Accounts</h3>
							<p>Enter your primary tracking ID and make sure your international accounts are connected with OneLink. It'll automatically send visitors to their local store.</p>

							<div class="form-group mb-4">
								<label><strong>Tracking ID for This Site</strong></label>
								<input type="text" name="amazon_tracking_id" id="amazon_tracking_id" class="form-control<?php echo $tracking_id_class; ?>" value="<?php echo esc_html( $amazon_tracking_id ) ?>" placeholder="tracking-20">
								<div id="tracking-id-invalid-msg" class="red<?php echo $tracking_id_invalid_class; ?>">This is an invalid Tracking ID</div>
							</div>
							<div class="form-group">
								<label class="toggle m-0 mr-1">
									<input type="checkbox" name="amazon_pricing_daily" id="amazon_pricing_daily" <?php echo $update_price_checked; ?>>
									<span class="slider"></span>
								</label>
								<label class="m-0">Update Amazon pricing daily</label>
							</div>
						</section>
					</div>


					<!-- AUTO MONETIZE AMAZON -->
					<div class="white-bg rounded shadow p-4">
						<section>
							<h3 class="lasso-lite-disabled no-hint">Auto-Monetize Amazon Links</h3>
							<p class="lasso-lite-disabled no-hint">Automatically monetize all current and future Amazon links with your Tracking ID and and added to your affiliate dashboard.</p>

							<p>
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" disabled="disabled">
									<span class="slider"></span>
								</label>
								<label class="m-0 lasso-lite-disabled no-hint">Enable Amazon Auto-Monetization</label>
							</p>
							<p class="text-danger amazon-error"></p>
						</section>
						<section>
							<p>
								<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
									<input type="checkbox" disabled="disabled">
									<span class="slider"></span>
								</label>
								<label class="m-0 lasso-lite-disabled no-hint">Allow Multiple Tracking IDs</label>
							</p>
							<div class="form-group mb-4 lasso-lite-disabled">
								<label><strong>Tracking ID Whitelist</strong></label>
								<input type="text" name="lasso-tracking-id-whitelist" class="form-control" value="" disabled="disabled" placeholder="Select Tracking IDs">
							</div>
						</section>
					</div>
				</div>

				<div class="col-lg">
					<div class="white-bg rounded shadow p-4 mb-lg-0 mb-5">
						<!-- PRODUCT API -->
						<section>
							<h3>Amazon Product API</h3>
							<p>If you want to use the Amazon API for product data, here's how to get your <a href="https://support.getlasso.co/en/articles/3182308-how-to-get-your-amazon-product-api-keys" target="_blank" class="purple underline">API keys from Amazon</a>.</p>

							<div class="form-group">
								<label data-tooltip="Select your Amazon Associates locale."><strong>Default Tracking ID</strong> <i class="far fa-info-circle light-purple"></i></label>
								<?php echo $countries_dd; ?>
							</div>

							<div class="form-group mb-4">
								<label><strong>Access Key ID</strong></label>
								<input type="text" name="amazon_access_key_id" id="amazon_access_key_id" class="form-control" value="<?php echo esc_html( $amazon_access_key_id ) ?>" placeholder="Access Key ID">
							</div>

							<div class="form-group mb-4">
								<label><strong>Secret Key</strong></label>
								<input type="text" name="amazon_secret_key" id="amazon_secret_key" class="form-control" value="<?php echo esc_html( $amazon_secret_key ) ?>" placeholder="Secret Key">
							</div>
						</section>

						<div class="form-group">
							<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
								<input disabled type="checkbox" checked>
								<span class="slider"></span>
							</label>
							<label class="m-0 lasso-lite-disabled no-hint">Show Prime Logo In Displays</label>
						</div>

						<div class="form-group">
							<label class="toggle m-0 mr-1 lasso-lite-disabled no-hint">
								<input type="checkbox" disabled="disabled">
								<span class="slider"></span>
							</label>
							<label class="m-0 lasso-lite-disabled no-hint">Show Discount Pricing</label>
						</div>
					</div>
				</div>

			</div>

			<!-- SAVE CHANGES -->
			<div class="row align-items-center">
				<div class="col-lg text-lg-right text-center">
					<button type="submit" class="btn btn-save-settings-amazon" >Save Changes</button>
				</div>
			</div>
		</form>
	</div>
</section>
<?php echo Helper::wrapper_js_render( 'default-template-notification-amz', Helper::get_path_views_folder() . '/notifications/default-template-jsrender.html' )?>
<?php Config::get_footer(); ?>
