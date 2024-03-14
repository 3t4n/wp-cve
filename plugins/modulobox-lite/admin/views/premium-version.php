<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$allowed_html = array(
	'a' => array(
		'href'   => array(),
		'target' => array()
	)
);

$purchase_url  = 'https://codecanyon.net/item/modulobox-nextgen-lightbox-plugin-for-wordpress/20014614?ref=Theme-one';
$product_page  = 'https://codecanyon.net/item/modulobox-nextgen-lightbox-plugin-for-wordpress/20014614/comments?ref=Theme-one';
$documentation = 'https://help.market.envato.com/hc/en-us/articles/202501014-How-To-Download-Your-Items';

echo '<div class="mobx-tab-content mobx-premium-version-content">';

	echo '<h2>' . esc_html__( 'Premium Version', 'modulobox' ) . '</h2>';

	echo '<p>';
		esc_html_e( 'You are currently running a Lite version of ModuloBox. This version is fully functional but restricted.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'With the Lite version you can create lightbox of single or gallery images from WordPress media library.', 'modulobox' );
		echo '<br>';
		esc_html_e( 'The aim of the Lite version is to show off how ModuloBox works and what you can expect from the premium version.', 'modulobox' );
		echo '<br><br>';
		esc_html_e( 'The premium version of ModuloBox includes all features and 6 months of premium support (from a dedicated ticket system).', 'modulobox' );
		echo '<br>';
		esc_html_e( 'The support response time is usually 1 business day from the GMT timezone (+2).', 'modulobox' );
	echo '</p>';

	echo '<a class="mobx-button mobx-purchase-button" target="_blank" href="' . esc_url( $purchase_url ) . '"><span>$20</span>' . esc_html__( 'Purchase ModuloBox', 'modulobox' ) . '</a>';

	echo '<h3>' . esc_html__( 'How to upgrade to Premium Version', 'modulobox' ) . '</h3>';

	echo '<p>';
		printf( wp_kses( __( 'Once you have purchased the premium version, you should be able to download a .zip file on <a target="_blank" href="%s">CodeCanyon</a> under download section.', 'modulobox' ), $allowed_html) , esc_url( $documentation ) );
		echo '<br>';
		esc_html_e( 'Here the following steps to switch from Lite to Premium version of ModuloBox:', 'modulobox' );
	echo '</p>';

	echo '<ol class="mobx-list">';
		echo '<li>' . esc_html__( 'Deactivate and uninstall your Lite version from WordPress', 'modulobox' ) . '</li>';
		echo '<li>' . esc_html__( 'Upload the Premium version in your WordPress admin dashboard under plugin section', 'modulobox' ) . '</li>';
		echo '<li>' . esc_html__( 'Activate your Premium version', 'modulobox' ) . '</li>';
		echo '<li>' . esc_html__( 'That\'s it! All your current settings will be preserved', 'modulobox' ) . '</li>';
	echo '</ol>';

	echo '<br>';

	echo '<a class="mobx-button mobx-info-button" target="_blank" href="' . esc_url( $product_page ) . '">' . esc_html__( 'Do you need further information ?', 'modulobox' ) . '</a>';

echo '</div>';
