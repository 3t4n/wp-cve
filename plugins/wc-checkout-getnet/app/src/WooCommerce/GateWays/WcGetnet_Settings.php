<?php

namespace WcGetnet\WooCommerce\GateWays;

use WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Settings;
use WcGetnet\WooCommerce\GateWays\AdminSettingsFields\Privacy_Policy;

class WcGetnet_Settings {

	public static function getnet_admin_options() {
		$instance = new WcGetnet_Settings();
        add_submenu_page(
            'woocommerce',
            __( 'Getnet Settings' ),
			'Getnet',
            'manage_options',
            'getnet-settings',
            array( $instance, 'admin_options')
        );
	}
	
	public function admin_options() {
		?>
		<div class="gnt-settings">
		<?php
			$this->handleHeader();
			$this->handleContent();
		?>
		</div>
		<?php
	}

	public function handleHeader() {
		?> 
		<div class="gnt-header-container admin-config" >
			<div class="gnt-header-item">
				<img id="gnt-logo-settings" src="<?php echo \WcGetnet::core()->assets()->getAssetUrl('images/gntLogo.png'); ?>">
				<h3 class="settings-plugin-version"> <?php echo getPluginInfo()['Version'];	?> </h3>
				<h1 class="coffee-question"><?php echo __( 'Dúvidas?' ); ?>	</h1>
					<p class="subtitle">Veja a documentação no <a href="https://coffee-code.tech/getnet-para-woocommerce/" target="_blank">site da Coffee Code</a></p>
			</div>
	
			<div class="gnt-header-item">
				<h1 class="card-title"><?php echo __( 'Requisitos técnicos' ); ?>	</h1>
				<h3 class="technician-require-item">
					Plugin Brazilian Market <span> on WooCommerce </span>
					<?php echo "<img src='".((!class_exists( 'Extra_Checkout_Fields_For_Brazil' )) ? esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/multiply.png' ) ) : esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/check.png' ) ))."'>"; ?>
				</h3>
				<hr>
				<h3 class="technician-require-item">
					Plugin WooCommerce
					<?php echo (!class_exists( 'WooCommerce' )) ? "<img src='".\WcGetnet::core()->assets()->getAssetUrl( 'images/multiply.png' )."'>" : "<img src='".\WcGetnet::core()->assets()->getAssetUrl( 'images/check.png' )."'>"; ?>
				</h3>
				<hr>
				<h3 class="technician-require-item">
					Versão do PHP
					<?php echo "<img src='".(((float) phpversion() < 7.4) ? esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/multiply.png' ) ) : esc_url( \WcGetnet::core()->assets()->getAssetUrl( 'images/check.png' ) ))."'>"; ?>
				</h3>
			</div>

			<div class="gnt-header-item container">
				<div class="coffee-banner-item content">
					<h1 class="card-title"><?php echo __( 'Serviços!' ); ?>	</h1>
					<p class="subtitle">Conheça os outros serviços da <a href="https://coffee-code.tech/" target="_blank">Coffee Code</a></p>
				</div>
				<div class="coffee-banner-item logo">
					 <img class="coffee-logo" src="<?php echo \WcGetnet::core()->assets()->getAssetUrl('images/coffeeCodeLogo.png'); ?>">
				</div>
			</div>
		</div>
		<?php
	}

	public function handleContent() {
		?>
			<div class="gnt-form">
				<form method="post" action="options.php" class="hide <?php echo ( get_option( '_policy_privacy_accept' ) == 1 ) ? '' : 'hide'; ?>">
					<div class="gnt-container">
						<div class="gnt-group gnt-group-full">
							<p class="group-title">
								<b><?php echo __( 'Configurações de credenciais' ); ?></b>
							</p>
							<input type="hidden" name="action" value="getnet_settings_action">
							<?php settings_fields('getnet-settings'); ?>
							<?php do_settings_sections('getnet-settings'); ?>
						</div>
						<?php submit_button(); ?>
					</div>
				</form>
				<?php $this->HandlePrivacyPolicy(); ?>
			</div>
		<?php
	}

	public function HandlePrivacyPolicy() {
		\WcGetnet::render('partials/settings/privacy-policy');
	}
}
