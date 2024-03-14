<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Pro version.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();
?>

<div class="dmt-flex dmt-flex-wrap dmt-items-center">
	<a href="<?php echo esc_url( darkmodetg( 'utility' )->pro_buy() ); ?>" target="_blank" class="dmt-inline-block dmt-mr-4 lg:dmt-mr-6">
		<img src="<?php echo esc_url( $this->plugin_url . 'assets/images/Dark-Mode-Toggle-Pro.png' ); ?>" alt="<?php esc_attr_e( 'Upgrade to Dark Mode Toggle Pro', 'dark-mode-toggle' ); ?>" class="dmt-block dmt-max-w-full dmt-max-h-60">
	</a>
	<div class="dmt-my-3">
		<div class="dmt-text-xl lg:dmt-text-2xl dmt-font-medium dmt-leading-tight dmt-uppercase dmt-mx-1 dmt-my-3"><?php esc_html_e( 'Upgrade to Pro Version', 'dark-mode-toggle' ); ?></div>
		<a href="<?php echo esc_url( darkmodetg( 'utility' )->pro_buy() ); ?>" target="_blank" class="dmt-btn dmt-btn-secondary dmt-mx-1 dmt-my-1"><?php esc_html_e( 'Buy Now', 'dark-mode-toggle' ); ?></a>
		<a href="<?php echo esc_url( darkmodetg( 'utility' )->pro_detail() ); ?>" target="_blank" class="dmt-btn dmt-btn-outline dmt-btn-secondary dmt-mx-1 dmt-my-1"><?php esc_html_e( 'More Detail', 'dark-mode-toggle' ); ?></a>
	</div>
</div>
