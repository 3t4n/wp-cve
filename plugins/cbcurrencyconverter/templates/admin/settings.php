<?php
/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://codeboxr.com
 * @since      1.0.0
 *
 * @package    cbcurrencyconverter
 * @subpackage cbcurrencyconverter/templates/admin
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="wrap cbx-chota cbcurrencyconverter-page-wrapper cbcurrencyconverter-setting-wrapper" id="cbcurrencyconverter-setting">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2></h2>
				<?php
				//not needed as it's using page options-general.php
				//settings_errors();
				?>
				<?php do_action( 'cbcurrencyconverter_wpheading_wrap_before', 'settings' ); ?>
                <div class="wp-heading-wrap">
                    <div class="wp-heading-wrap-left pull-left">
						<?php do_action( 'cbcurrencyconverter_wpheading_wrap_left_before', 'settings' ); ?>
                        <h1 class="wp-heading-inline wp-heading-inline-cbx wp-heading-inline-cbcurrencyconverter ">
							<?php esc_html_e( 'Currency Converter Settings', 'cbcurrencyconverter' ); ?>
                        </h1>
						<?php do_action( 'cbcurrencyconverter_wpheading_wrap_left_after', 'settings' ); ?>
                    </div>
                    <div class="wp-heading-wrap-right  pull-right">
						<?php do_action( 'cbcurrencyconverter_wpheading_wrap_right_before', 'settings' ); ?>
                        <a href="<?php echo admin_url( 'options-general.php?page=cbcurrencyconverter&doc=1' ); ?>" class="button outline primary"><?php esc_html_e( 'Support & Docs', 'cbcurrencyconverter' ); ?></a>
                        <a href="#" id="save_settings" class="button primary icon mr-5"><?php esc_html_e( 'Save Settings', 'cbcurrencyconverter' ); ?><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAA+0lEQVRIie2UMUpEMRRFT9RGCyO4BBXcgli5AHEBLkF0C84ipnJaWwttBEGsXYJ7sHBmA8dC//An5OdP5Iugni4vyb03vPDgnxrULfVanaqjz1oXzf5YXe3SXEnWY+AU2KzIdQbcqOvLGBxXCLc5AR7V7T6DmuQpB8CzutcurhUuNGaXwEZm/0GNSW0HuAd2s4pJE1/VQzV9ZXM2qpNc99vnQmpQeNHShBDmutl0Q/KjBldADD0AEZh0iZR6EIF94Kgn5BPwArzNRVs9WCD9CeqoMCrSkZH9Rb+7yd9iMBtAc1oyuBvAYEEjHXbngHyM31pmwC1w8bVcf5Z3dIDGLQz4Au0AAAAASUVORK5CYII="/></a>
						<?php do_action( 'cbcurrencyconverter_wpheading_wrap_right_after', 'settings' ); ?>
                    </div>
                </div>
				<?php do_action( 'cbcurrencyconverter_wpheading_wrap_after', 'settings' ); ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
				<?php do_action( 'cbcurrencyconverter_settings_form_before', 'settings' ); ?>
                <div class="postbox">
                    <div class="clear clearfix"></div>
                    <div class="inside setting-form-wrap">
                        <div class="clear clearfix"></div>
						<?php do_action( 'cbcurrencyconverter_settings_form_start', 'settings' ); ?>
						<?php
						//settings_errors();
						$settings->show_navigation();

						$settings->show_forms();
						?>
						<?php do_action( 'cbcurrencyconverter_settings_form_end', 'settings' ); ?>
                        <div class="clear clearfix"></div>
                    </div>
                    <div class="clear clearfix"></div>
                </div>
				<?php do_action( 'cbcurrencyconverter_settings_form_after', 'settings' ); ?>
            </div>
        </div>
    </div>
</div>