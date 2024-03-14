<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Reset settings.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();
?>

<form x-data="form" x-bind="form( true )">

	<?php wp_nonce_field( 'reset-settings', 'nonce' ); ?>

	<input name="action" type="hidden" value="darkmodetg-reset">

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1">
		<div class="dmt-max-w-sm">

			<div class="dmt-alert dmt-alert-warning dmt-shadow dmt-text-base dmt-mb-3 sm:!dmt-flex-row">
				<div class="dmt-items-center">
					<svg xmlns="http://www.w3.org/2000/svg" class="dmt-stroke-current dmt-flex-shrink-0 dmt-h-6 dmt-w-6 dmt-mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
					<span class="dmt-font-normal"><?php esc_html_e( 'Reset the plugin settings to the default state.', 'dark-mode-toggle' ); ?></span>
				</div>
			</div>

			<?php do_action( 'darkmodetg_fields_settings_reset' ); ?>

		</div>
	</div>

	<?php do_action( 'darkmodetg_fields_settings_reset_before_btn' ); ?>

	<div class="md:dmt-grid md:dmt-grid-cols-2 xl:dmt-grid-cols-3 md:dmt-gap-x-10 lg:dmt-gap-x-12 dmt-px-1">
		<div class="dmt-max-w-sm">

			<button class="dmt-btn dmt-btn-secondary dmt-btn-block dmt-mt-3" x-bind="btn"><?php esc_html_e( 'Reset Settings', 'dark-mode-toggle' ); ?></button>

			<?php darkmodetg( 'settings' )->feedback_html(); ?>

			<template x-if="showInfo">
				<div class="dmt-alert dmt-alert-info dmt-shadow dmt-text-base dmt-mt-3 sm:!dmt-flex-row">
					<div class="dmt-items-center">
						<svg xmlns="http://www.w3.org/2000/svg" class="dmt-stroke-current dmt-flex-shrink-0 dmt-h-6 dmt-w-6 dmt-mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
						<span class="dmt-font-normal"><?php esc_html_e( 'Reload to view default state.', 'dark-mode-toggle' ); ?></span>
					</div>

					<div class="dmt-items-center dmt-flex-none sm:!dmt-mt-0">
						<a href="<?php menu_page_url( 'darkmodetg-settings' ); ?>" class="dmt-btn dmt-btn-sm dmt-btn-ghost dmt-ml-2"><?php esc_html_e( 'Reload', 'dark-mode-toggle' ); ?></a>
					</div>
				</div>
			</template>

		</div>
	</div>

	<div class="dmt-modal" :class="openConfirm && 'dmt-modal-open'">
		<div class="dmt-modal-box" x-bind="confirmModal">
			<h3 class="dmt-font-bold"><?php esc_html_e( 'Confirm Reset', 'dark-mode-toggle' ); ?></h3>
			<p class="dmt-text-base dmt-py-4"><?php esc_html_e( 'Are you sure to reset the plugin settings to the default state?', 'dark-mode-toggle' ); ?></p>
			<div class="dmt-modal-action">
				<button class="dmt-btn dmt-btn-ghost" x-bind="action"><?php esc_html_e( 'Cancel', 'dark-mode-toggle' ); ?></button>
				<button class="dmt-btn dmt-btn-secondary" x-bind="resetAction"><?php esc_html_e( 'Reset Settings', 'dark-mode-toggle' ); ?></button>
			</div>
		</div>
	</div>

</form>
