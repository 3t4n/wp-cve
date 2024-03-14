<?php

namespace MyCustomizer\WooCommerce\Connector;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;
use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSecurity;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrPlugin {

	public function init() {
		$plugin = basename( plugin_dir_path( dirname( __FILE__ ) ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'addCustomScripts' ) );
		add_filter( "plugin_action_links_$plugin/mycustomizer-woocommerce-connector.php", array( $this, 'addDashboardLink' ) );
	}

	public function onActivate() {
		$security = new MczrSecurity();
		$settings = new MczrSettings();

		if ( empty( $settings->get( 'authorizationKey' ) ) ) {
			$settings->updateOne( 'authorizationKey', $security->generateKey() );
		}

		if ( empty( $settings->get( 'iframeHook' ) ) ) {
			$settings->updateOne( 'iframeHook', 'woocommerce_after_single_product_summary' );
		}

		if ( empty( $settings->get( 'iframeHookPriority' ) ) ) {
			$settings->updateOne( 'iframeHookPriority', 0 );
		}

		if ( empty( $settings->get( 'iframeWidth' ) ) ) {
			$settings->updateOne( 'iframeWidth', '100%' );
		}

		if ( empty( $settings->get( 'iframeHeight' ) ) ) {
			$settings->updateOne( 'iframeHeight', '700px' );
		}

		if ( empty( $settings->get( 'productCss' ) ) ) {
			$settings->updateOne( 'productCss', '#primary{width:100%;} #mczrMainIframe{border: none;}' );
		}

		$brand = $settings->get( 'brand' );

		if ( ! empty( $brand ) ) {
			$mczrConnect = new MczrConnect();
			$mczrConnect->connect( $brand );
		}
	}

	public function addCustomScripts() {
		wp_enqueue_script( 'js', \plugin_dir_url( __FILE__ ) . 'Resources/public/js/mczr.js', array(), 1 );
		wp_localize_script( 'js', 'ajaxFrontObj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

	public function addDashboardLink( $links ) {
		$settings = new MczrSettings();

		$brand = $settings->get( 'brand' );

		if ( empty( $brand ) ) {
			$href = MczrConfig::getInstance()['registerUrl'] . '?eCommerce=woocommerce&shop=' . get_site_url() . '&woocommerceToken=' . $settings->get( 'authorizationKey' );
		} else {
			$href = str_replace( '{{brand}}', $brand, MczrConfig::getInstance()['dashboardUrlPattern'] );
		}

		$settings_link = '<a href="' . $href . '" target="blank">Dashboard</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}
}
