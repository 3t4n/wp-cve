<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Settings menu page.
 *
 * @package Dark_Mode_Toggle
 */

defined( 'ABSPATH' ) || die();
?>

<div class="dmtg">
	<div class="dmt-container dmt-min-h-screen dmt-px-4 md:dmt-px-6 dmt-py-6">

		<?php require $this->plugin_path . 'inc/pro.php'; ?>

		<div class="dmt-divider xl:dmt-pt-6"></div>

		<h1 class="dmt-font-medium dmt-leading-tight"><?php esc_html_e( 'Dark Mode Toggle', 'dark-mode-toggle' ); ?></h1>

		<div class="dmt-divider"></div>

		<div x-data="tabs( 'dmt-tab-front' )">
			<ul class="dmt-menu dmt-bg-base-100 dmt-shadow dmt-menu-horizontal dmt-flex-wrap dmt-mb-6">
				<li><a href="#dmt-tab-front" class="!dmt-px-4" x-bind="tabLink( 'dmt-tab-front' )"><?php esc_html_e( 'Front Settings', 'dark-mode-toggle' ); ?></a></li>
				<li><a href="#dmt-tab-advanced" class="!dmt-px-4" x-bind="tabLink( 'dmt-tab-advanced' )"><?php esc_html_e( 'Advanced Settings', 'dark-mode-toggle' ); ?></a></li>
				<li><a href="#dmt-tab-reset" class="!dmt-px-4" x-bind="tabLink( 'dmt-tab-reset' )"><?php esc_html_e( 'Reset Settings', 'dark-mode-toggle' ); ?></a></li>
			</ul>

			<div id="dmt-tab-front" class="dmt-tab-front" x-bind="tabContent( 'dmt-tab-front' )">
				<?php require $this->plugin_path . 'inc/settings/front.php'; ?>
			</div>

			<div id="dmt-tab-advanced" class="dmt-tab-advanced" x-bind="tabContent( 'dmt-tab-advanced' )">
				<?php require $this->plugin_path . 'inc/settings/advanced.php'; ?>
			</div>

			<div id="dmt-tab-reset" class="dmt-tab-reset" x-bind="tabContent( 'dmt-tab-reset' )">
				<?php require $this->plugin_path . 'inc/settings/reset.php'; ?>
			</div>
		</div>

		<?php require $this->plugin_path . 'inc/feedback.php'; ?>

		<div class="dmt-divider dmt-pb-4 xl:dmt-pb-6"></div>

		<?php require $this->plugin_path . 'inc/pro.php'; ?>

	</div>
</div>
