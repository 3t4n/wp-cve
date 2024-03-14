<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wc-szamlazz-settings-sidebar" data-nonce="<?php echo wp_create_nonce( 'wc-szamlazz-license-check' )?>">

	<?php if(!get_option( 'woocommerce_calc_taxes' ) && !$this->get_option('afakulcs') && get_option('wc_szamlazz_payment_method_options_v2')): ?>
		<div class="wc-szamlazz-settings-widget wc-szamlazz-settings-widget-vat">
			<h3><span class="dashicons dashicons-warning"></span> <?php _e('TAX rate setup', 'wc-szamlazz'); ?></h3>
			<p><?php _e('You are using the default VAT rate and taxes are disabled in WooCommerce, so the VAT rate will be 0% on the invoice.', 'wc-szamlazz'); ?></p>
			<p><?php _e("If you don't need to charge sales tax, you can set a fixed VAT rate in the settings, like AAM. If you need percentage-based VAT rates, or you have a more complicated situation, you need to configure the WooCommerce TAX settings properly. Here is a guide to do this:", 'wc-szamlazz'); ?></p>
			<p><a href="https://visztpeter.me/2020/05/13/afakulcsok-beallitasa-woocommerce-aruhazakban/" target="_blank"><?php _e('Setting up VAT/TAX rates in WooCommerce shops.', 'wc-szamlazz'); ?></a></p>
		</div>
	<?php endif; ?>


	<?php if(WC_Szamlazz_Pro::is_pro_enabled() || (!WC_Szamlazz_Pro::is_pro_enabled() && WC_Szamlazz_Pro::get_license_key())): ?>

		<div class="wc-szamlazz-settings-widget wc-szamlazz-settings-widget-pro wc-szamlazz-settings-widget-pro-active wc-szamlazz-settings-widget-pro-<?php if(WC_Szamlazz_Pro::is_pro_enabled()): ?>state-active<?php else: ?>state-expired<?php endif; ?>">

			<?php if(WC_Szamlazz_Pro::is_pro_enabled()): ?>
				<h3><span class="dashicons dashicons-yes-alt"></span> <?php _e('The PRO version is active', 'wc-szamlazz'); ?></h3>
				<p><?php _e('You have successfully activated the PRO version.', 'wc-szamlazz'); ?></p>
			<?php else: ?>
				<h3><span class="dashicons dashicons-warning"></span> <?php _e('The PRO version is expired', 'wc-szamlazz'); ?></h3>
				<p><?php _e('The following license key is expired.', 'wc-szamlazz'); ?></p>
			<?php endif; ?>

			<p>
				<span class="wc-szamlazz-settings-widget-pro-label"><?php _e('License key', 'wc-szamlazz'); ?></span><br>
				<?php echo esc_html(WC_Szamlazz_Pro::get_license_key()); ?>
			</p>

			<?php $license = WC_Szamlazz_Pro::get_license_key_meta(); ?>
			<?php if(isset($license['type'])): ?>
			<p class="single-license-info">
				<span class="wc-szamlazz-settings-widget-pro-label"><?php _e('License type', 'wc-szamlazz'); ?></span><br>
				<?php if ( $license['type'] == 'unlimited' ): ?>
					<?php _e( 'Unlimited', 'wc-szamlazz' ); ?>
				<?php else: ?>
					<?php _e( 'Subscription', 'wc-szamlazz' ); ?>
				<?php endif; ?>
			</p>
			<?php endif; ?>

			<?php if(isset($license['next_payment'])): ?>
			<p class="single-license-info">
				<span class="wc-szamlazz-settings-widget-pro-label"><?php _e('Next payment', 'wc-szamlazz'); ?></span><br>
				<?php echo esc_html($license['next_payment']); ?>
			</p>
			<?php endif; ?>

			<div class="wc-szamlazz-settings-widget-pro-deactivate">
				<p>
					<a class="button-secondary" id="wc_szamlazz_deactivate_pro"><?php esc_html_e( 'Deactivate license', 'wc-szamlazz' ); ?></a>
					<a class="button-secondary" id="wc_szamlazz_validate_pro"><?php esc_html_e( 'Reload license', 'wc-szamlazz' ); ?></a>
				</p>
				<p><small><?php esc_html_e( 'If you want to activate the license on another website, you must first deactivate it on this website.', 'wc-szamlazz' ); ?></small></p>
			</div>
		</div>

	<?php else: ?>

		<div class="wc-szamlazz-settings-widget wc-szamlazz-settings-widget-pro">
			<h3><?php esc_html_e( 'PRO version', 'wc-szamlazz' ); ?></h3>
			<p><?php esc_html_e( 'If you have already purchased the PRO version, enter the license key and the e-mail address used to purchase:', 'wc-szamlazz' ); ?></p>

			<div class="wc-szamlazz-settings-widget-pro-notice" style="display:none">
				<span class="dashicons dashicons-warning"></span>
				<p></p>
			</div>

			<fieldset>
				<input class="input-text regular-input" type="text" name="woocommerce_wc_szamlazz_pro_key" id="woocommerce_wc_szamlazz_pro_key" value="" placeholder="<?php esc_html_e( 'License key', 'wc-szamlazz' ); ?>"><br>
			</fieldset>
			<p>
				<button class="button-primary" type="button" id="wc_szamlazz_activate_pro"><?php _e('Activate', 'wc-szamlazz'); ?></button>
			</p>
			<h4><?php esc_html_e( 'Why should I use the PRO version?', 'wc-szamlazz' ); ?></h4>
			<ul>
				<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Automatic invoicing', 'wc-szamlazz' ); ?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Create automatic proforma invoices', 'wc-szamlazz' ); ?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Mark invoice as paid automatically', 'wc-szamlazz' ); ?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Generating receipts', 'wc-szamlazz' ); ?></li>
				<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Premium support and a lot more', 'wc-szamlazz' ); ?></li>
			</ul>
			<div class="wc-szamlazz-settings-widget-pro-cta">
				<a href="https://visztpeter.me/woocommerce-szamlazz-hu/"><span class="dashicons dashicons-cart"></span> <span><?php esc_html_e( 'Purchase PRO version', 'wc-szamlazz' ); ?></span></a>
				<span>
					<small><?php esc_html_e( 'net', 'wc-szamlazz' ); ?></small>
					<strong><?php esc_html_e( '30 € / year', 'wc-szamlazz' ); ?></strong>
				</span>
			</div>

		</div>

	<?php endif; ?>

	<?php $vp_woo_pont_slug = 'hungarian-pickup-points-for-woocommerce'; ?>
	<?php if(!file_exists( WP_PLUGIN_DIR . '/' . $vp_woo_pont_slug )): ?>
		<div class="wc-szamlazz-settings-widget wc-szamlazz-settings-widget-vp-pont">
			<h3><?php esc_html_e( 'Csomagpontok és címkegenerálás', 'wc-szamlazz' ); ?></h3>
			<p><?php esc_html_e( 'Próbáld ki az ingyenes csomagpontos bővítményt, ami támogatja az összes népszerű futárszolgálat átvételi helyeit. A PRO verzióval címkét is generálhatsz, házhozszállításra is, automata csomagkövetéssel.', 'wc-szamlazz' ); ?></p>
			<div class="wc-szamlazz-settings-widget-vp-pont-logos"></div>
			<div class="wc-szamlazz-settings-widget-vp-pont-cta">
				<?php
				$vp_woo_pont_install_url = wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $vp_woo_pont_slug
						),
						self_admin_url( 'update.php' )
					),
					'install-plugin_'.$vp_woo_pont_slug
				);
				?>
				<a class="button-primary" href="<?php echo esc_url($vp_woo_pont_install_url); ?>">Telepítés</a>
				<a class="button-secondary" href="https://visztpeter.me/woocommerce-csomagpont-integracio/" target="_blank"><?php esc_html_e( 'Bővebb információk', 'wc-szamlazz' ); ?></a>
			</div>
		</div>
	<?php endif; ?>

	<div class="wc-szamlazz-settings-widget">
		<h3><?php esc_html_e('Support', 'wc-szamlazz'); ?></h3>
		<p><?php esc_html_e('It is important to point out that this extension was not created by Számlázz.hu, so if you have any questions about the operation of the extension, please contact me at one of the following contacts:', 'wc-szamlazz'); ?></p>
		<ul>
			<li><a href="https://visztpeter.me/dokumentacio/" target="_blank"><?php esc_html_e('Documentation', 'wc-szamlazz'); ?></a></li>
			<li><a href="mailto:support@visztpeter.me"><?php esc_html_e('E-mail (support@visztpeter.me)', 'wc-szamlazz'); ?></a></li>
			<li><a href="https://wordpress.org/support/plugin/integration-for-szamlazzhu-woocommerce/" target="_blank"><?php esc_html_e('Forum thread on WordPress.org', 'wc-szamlazz'); ?></a></li>
		</ul>
	</div>

</div>
