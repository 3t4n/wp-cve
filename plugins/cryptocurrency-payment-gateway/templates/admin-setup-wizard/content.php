<?php
/**
 * Setup wizard content template.
 * Based on: https://levelup.gitconnected.com/create-a-multi-step-form-using-html-css-and-javascript-30aca5c062fc
 *
 * @package    CryptoWoo
 * @subpackage CryptoWoo\Admin\SetupWizard
 */

defined( 'ABSPATH' ) || exit;

$logo_url          = plugins_url( 'cryptocurrency-payment-gateway/assets/images/cryptowoo-logo-260x40.png' );
$enabled_coins     = cw_get_enabled_currencies( false, false );
$btc_is_enabled    = array_key_exists( 'BTC', $enabled_coins );
$bch_is_enabled    = array_key_exists( 'BCH', $enabled_coins );
$ltc_is_enabled    = array_key_exists( 'LTC', $enabled_coins );
$doge_is_enabled   = array_key_exists( 'DOGE', $enabled_coins );
$address_list_btc  = implode( "\r\n", array_keys( CW_AddressList::get_address_list( 'BTC', true ) ) );
$address_list_bch  = implode( "\r\n", array_keys( CW_AddressList::get_address_list( 'BCH', true ) ) );
$address_list_ltc  = implode( "\r\n", array_keys( CW_AddressList::get_address_list( 'LTC', true ) ) );
$address_list_doge = implode( "\r\n", array_keys( CW_AddressList::get_address_list( 'DOGE', true ) ) );

?>
<div>
	<div id="cw-setup-wizard-header">
		<h1>Cryptocurrency Payment Gateway Setup Wizard</h1>
		<div id="cw-setup-wizard-header-logo">
			<p>by</p>
			<img src="<?php echo esc_url_raw( $logo_url ); ?>"></image>
		</div>
	</div>
	<div id="multi-step-form-container">
		<!-- Form Steps / Progress Bar -->
		<ul class="form-stepper form-stepper-horizontal text-center mx-auto pl-0">
			<!-- Step 1 -->
			<li class="form-stepper-active text-center form-stepper-list" step="1">
				<a class="mx-1">
                    <span class="form-stepper-circle">
                        <span>1</span>
                    </span>
					<div class="label">Cryptocurrencies</div>
				</a>
			</li>
			<!-- Step 2 -->
			<li class="form-stepper-unfinished text-center form-stepper-list" step="2">
				<a class="mx-2">
                    <span class="form-stepper-circle text-muted">
                        <span>2</span>
                    </span>
					<div class="label text-muted">Get a wallet</div>
				</a>
			</li>
			<!-- Step 3 -->
			<li class="form-stepper-unfinished text-center form-stepper-list" step="3">
				<a class="mx-3">
                    <span class="form-stepper-circle text-muted">
                        <span>3</span>
                    </span>
					<div class="label text-muted">Wallet Addresses</div>
				</a>
			</li>
		</ul>
		<!-- Step Wise Form Content -->
		<form id="cw-setup-wizard-form" name="cw-setup-wizard-form" enctype="multipart/form-data" method="POST" >
			<!-- Step 1 Content -->
			<section id="step-1" class="form-step">
				<h2 class="font-normal">Cryptocurrencies</h2>
				<!-- Step 1 input fields -->
				<div class="mt-3">
					<fieldset>
						<legend>Which cryptocurrencies do you want to accept in your store?</legend>
						<br>
						<div><input class="coin-selection" id="coin-selection-btc" type="checkbox" name="coins[]" value="bitcoin" <?php echo ( $btc_is_enabled ? 'checked' : '' ); ?>><label for="coin-selection-btc">Bitcoin</label><span class="popular">popular</span></div>
						<div><input class="coin-selection" id="coin-selection-bch" type="checkbox" name="coins[]" value="bitcoin_cash" <?php echo ( $bch_is_enabled ? 'checked' : '' ); ?>><label for="coin-selection-bch">Bitcoin Cash</label><span class="recommended">recommended</span></div>
						<div><input class="coin-selection" id="coin-selection-ltc" type="checkbox" name="coins[]" value="litecoin" <?php echo ( $ltc_is_enabled ? 'checked' : '' ); ?>><label for="coin-selection-ltc">Litecoin</label></div>
						<div><input class="coin-selection" id="coin-selection-doge" type="checkbox" name="coins[]" value="dogecoin" <?php echo ( $doge_is_enabled ? 'checked' : '' ); ?>><label for="coin-selection-doge">Dogecoin</label></div>
						<p id="setup-wizard-add-on-description">
							Additional cryptocurrencies such as Ethereum, Dash, Monero, and more, can be accepted with the <a href="https://www.cryptowoo.com/shop/" target="_blank">add-ons</a> on our website. You can re-run this Setup Wizard at any time.
						</p>
					</fieldset>
				</div>
				<div class="mt-3 d-none form-validation-error-message">
					<p>The form validation failed.</p>
				</div>
				<div class="mt-3">
					<button class="button btn-navigate-form-step" type="button" step_number="2">Next</button>
					<button class="button btn-skip-form" id="cw-skip-setup-wizard-button" type="submit" name="btnExitSetup">Exit</button>
				</div>
			</section>
			<!-- Step 2 Content, default hidden on page load. -->
			<section id="step-2" class="form-step d-none">
				<h2 class="font-normal">Get a wallet</h2>
				<!-- Step 2 input fields -->
				<div class="mt-3">
					<p>You must get a cryptocurrency wallet to receive payments if you do not already have a wallet. You may select one of the recommended wallets below or use any other wallet of your choice.</p>
				</div>
				<div class="mt-3 wallet-recommendation" id="wallet-recommendation-bitcoin-com">
					<h3 class="font-normal">Bitcoin.com wallet</h3>
					<p>Bitcoin.com wallet for Android and iOS is recommended for Bitcoin, Bitcoin Cash, and Ethereum.</p>
					<a href="https://wallet.bitcoin.com/" class="button" target="_blank">Get Bitcoin.com wallet</a>
				</div>
					<div class="mt-3 wallet-recommendation" id="wallet-recommendation-coinomi">
					<h3 class="font-normal">Coinomi</h3>
					<p>The coinomi wallet for Android and iOS is recommended for multiple cryptocurrencies. It supports Bitcoin, Bitcoin Cash, Litecoin, Dogecoin, Monero, and many more.</p>
					<a href="https://www.coinomi.com/" class="button" target="_blank">Get Coinomi wallet</a>
				</div>
				<br>
				<div class="mt-3">
					<p>Proceed to the next step when you have downloaded and set up a wallet.</p>
				</div>
				<div class="mt-3 d-none form-validation-error-message">
					<p>The form validation failed.</p>
				</div>
				<div class="mt-3">
					<button class="button btn-navigate-form-step" type="button" step_number="1">Prev</button>
					<button class="button btn-navigate-form-step" type="button" step_number="3">Next</button>
					<button class="button btn-skip-form" id="cw-skip-setup-wizard-button" type="submit" name="btnExitSetup">Exit</button>
				</div>
			</section>
			<!-- Step 3 Content, default hidden on page load. -->
			<section id="step-3" class="form-step d-none">
				<h2 class="font-normal">Wallet Addresses</h2>
				<!-- Step 3 input fields -->
				<div class="mt-3">
					<p>Enter your wallet addresses to receive payments. One unique address will be used per order to easily identify the customer payments. Enter the addresses from your wallet.</p>
					<a href="https://cryptowoo.com/how-to-get-wallet-addresses" class="button" target="_blank">Learn how to get my addresses</a>
				</div>
				<div class="mt-3" id="bitcoin-addresses">
					<h3 class="font-normal">Bitcoin Addresses</h3>
					<p>Enter your wallet addresses to receive payments. Add up to 20 addresses, one per line.</p>
					<textarea name="bitcoin-addresses" rows="5"><?php echo esc_textarea( $address_list_btc ); ?></textarea>
				</div>
				<div class="mt-3" id="bitcoin-cash-addresses">
					<h3 class="font-normal">Bitcoin Cash Addresses</h3>
					<p>Enter your wallet addresses to receive payments. Add up to 20 addresses, one per line.</p>
					<textarea name="bitcoin-cash-addresses" rows="5"><?php echo esc_textarea( $address_list_bch ); ?></textarea>
				</div>
				<div class="mt-3" id="litecoin-addresses">
					<h3 class="font-normal">Litecoin Addresses</h3>
					<p>Enter your wallet addresses to receive payments. Add up to 20 addresses, one per line.</p>
					<textarea name="litecoin-addresses" rows="5"><?php echo esc_textarea( $address_list_ltc ); ?></textarea>
				</div>
				<div class="mt-3" id="dogecoin-addresses">
					<h3 class="font-normal">Dogecoin Addresses</h3>
					<p>Enter your wallet addresses to receive payments. Add up to 20 addresses, one per line.</p>
					<textarea name="dogecoin-addresses" rows="5"><?php echo esc_textarea( $address_list_doge ); ?></textarea>
				</div>
				<div class="mt-3">
					<p>Does your business have or do you expect large order volumes? You can automatically generate addresses with our paid <a href="https://www.cryptowoo.com/shop/cryptowoo-hd-wallet-addon/" target="_blank">HD Wallet Add-on</a>. You can re-run this Setup Wizard at any time.</p>
				</div>
				<div class="mt-3 d-none form-validation-error-message">
					<p>The form validation failed.</p>
				</div>
				<div class="mt-3">
					<button class="button btn-navigate-form-step" type="button" step_number="2">Prev</button>
					<button class="button submit-btn" type="submit" name="btnSave" step_number="3">Save</button>
					<button class="button btn-skip-form" id="cw-skip-setup-wizard-button" type="submit" name="btnExitSetup">Exit</button>
				</div>
			</section>
			<?php echo wp_kses_post( wp_nonce_field( 'cw_setup_wizard_form', 'nonce' ) ); ?>
		</form>
	</div>
</div>
