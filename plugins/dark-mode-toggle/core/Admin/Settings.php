<?php // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Admin\Settings service class.
 *
 * @package Dark_Mode_Toggle
 */

namespace DarkModeToggle\Admin;

use DarkModeToggle\Base;
use DarkModeToggle\Service;

defined( 'ABSPATH' ) || die();

/**
 * Save settings.
 */
class Settings extends Base implements Service {
	/**
	 * Initialize service.
	 */
	public function init() {
		add_action( 'wp_ajax_darkmodetg-front-settings', array( $this, 'save_front' ) );
		add_action( 'wp_ajax_darkmodetg-advanced-settings', array( $this, 'save_advanced' ) );
		add_action( 'wp_ajax_darkmodetg-reset', array( $this, 'reset' ) );
	}

	/**
	 * Save front form settings.
	 */
	public function save_front() {
		$nonce = array_key_exists( 'nonce', $_POST ) ? sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'front-settings' ) ) {
			die();
		}

		if ( ! current_user_can( darkmodetg( 'options' )->cap_main() ) ) {
			die();
		}

		$default = darkmodetg( 'options' )->get_default_main( 'front' );

		$enable         = array_key_exists( 'enable', $_POST ) ? ( (bool) $_POST['enable'] ) : false;
		$pos            = array_key_exists( 'pos', $_POST ) ? sanitize_text_field( \wp_unslash( $_POST['pos'] ) ) : $default['pos'];
		$pos            = darkmodetg( 'options' )->sanitize_pos( $pos );
		$pos_b          = array_key_exists( 'pos_b', $_POST ) ? ( (int) $_POST['pos_b'] ) : $default['pos_b'];
		$pos_t          = array_key_exists( 'pos_t', $_POST ) ? ( (int) $_POST['pos_t'] ) : $default['pos_t'];
		$pos_l          = array_key_exists( 'pos_l', $_POST ) ? ( (int) $_POST['pos_l'] ) : $default['pos_l'];
		$pos_r          = array_key_exists( 'pos_r', $_POST ) ? ( (int) $_POST['pos_r'] ) : $default['pos_r'];
		$width          = array_key_exists( 'width', $_POST ) ? absint( $_POST['width'] ) : $default['width'];
		$height         = array_key_exists( 'height', $_POST ) ? absint( $_POST['height'] ) : $default['height'];
		$border_r       = array_key_exists( 'border_r', $_POST ) ? absint( $_POST['border_r'] ) : $default['border_r'];
		$in_head        = array_key_exists( 'in_head', $_POST ) ? ( (bool) $_POST['in_head'] ) : false;
		$fix_flick      = array_key_exists( 'fix_flick', $_POST ) ? ( (bool) $_POST['fix_flick'] ) : false;
		$flick_bg_color = array_key_exists( 'flick_bg_color', $_POST ) ? sanitize_hex_color( \wp_unslash( $_POST['flick_bg_color'] ) ) : $default['flick_bg_color'];
		$skip_bg_img    = array_key_exists( 'skip_bg_img', $_POST ) ? ( (bool) $_POST['skip_bg_img'] ) : false;

		$value = array(
			'enable'         => $enable,
			'pos'            => $pos,
			'pos_b'          => $pos_b,
			'pos_t'          => $pos_t,
			'pos_l'          => $pos_l,
			'pos_r'          => $pos_r,
			'width'          => $width,
			'height'         => $height,
			'border_r'       => $border_r,
			'in_head'        => $in_head,
			'fix_flick'      => $fix_flick,
			'flick_bg_color' => $flick_bg_color,
			'skip_bg_img'    => $skip_bg_img,
		);

		do_action( 'darkmodetg_before_save_settings_front', $value );

		darkmodetg( 'options' )->save_main( 'front', $value );

		wp_send_json_success(
			array(
				'msg' => esc_html__( 'Settings saved successfully!', 'dark-mode-toggle' ),
			)
		);
	}

	/**
	 * Save advanced form settings.
	 */
	public function save_advanced() {
		$nonce = array_key_exists( 'nonce', $_POST ) ? sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'advanced-settings' ) ) {
			die();
		}

		if ( ! current_user_can( darkmodetg( 'options' )->cap_main() ) ) {
			die();
		}

		$default = darkmodetg( 'options' )->get_default_main( 'advanced' );

		$time   = array_key_exists( 'time', $_POST ) ? abs( (float) $_POST['time'] ) : $default['time'];
		$hide_m = array_key_exists( 'hide_m', $_POST ) ? ( (bool) $_POST['hide_m'] ) : false;
		$save   = array_key_exists( 'save', $_POST ) ? ( (bool) $_POST['save'] ) : false;

		$front_override = array_key_exists( 'front_override', $_POST ) ? ( (bool) $_POST['front_override'] ) : false;

		$value = array(
			'time'   => $time,
			'hide_m' => $hide_m,
			'save'   => $save,
			'front'  => array(
				'override' => $front_override,
			),
		);

		do_action( 'darkmodetg_before_save_settings_advanced', $value );

		darkmodetg( 'options' )->save_main( 'advanced', $value );

		wp_send_json_success(
			array(
				'msg' => esc_html__( 'Settings saved successfully!', 'dark-mode-toggle' ),
			)
		);
	}

	/**
	 * Feeback alert HTML.
	 */
	public function feedback_html() {
		?>
		<template x-if="msg">
			<div class="dmt-alert dmt-shadow dmt-text-base dmt-mt-3 sm:!dmt-flex-row" :class="msgSuccess ? 'dmt-alert-success' : 'dmt-alert-error'">
				<div class="dmt-items-center">
					<svg xmlns="http://www.w3.org/2000/svg" class="dmt-stroke-current dmt-flex-shrink-0 dmt-h-6 dmt-w-6 dmt-mr-2" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="msgSuccess ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'" /></svg>
					<span x-text="msg"></span>
				</div>

				<div class="dmt-items-center dmt-flex-none sm:!dmt-mt-0">
					<button class="dmt-btn dmt-btn-sm dmt-btn-ghost dmt-ml-2" @click.prevent="msg = ''">
						<?php echo wp_kses( __( 'Dismiss <span class="dmt-sr-only">feedback alert.</span>', 'dark-mode-toggle' ), array( 'span' => array( 'class' => array() ) ) ); ?>
					</button>
				</div>
			</div>
		</template>
		<?php
	}

	/**
	 * Reset settings.
	 */
	public function reset() {
		$nonce = array_key_exists( 'nonce', $_POST ) ? sanitize_text_field( \wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, 'reset-settings' ) ) {
			die();
		}

		if ( ! current_user_can( darkmodetg( 'options' )->cap_main() ) ) {
			die();
		}

		darkmodetg( 'options' )->delete( darkmodetg( 'options' )->get_key_main() );

		delete_option( 'darkmodetg_welcome' );

		do_action( 'darkmodetg_before_reset_settings' );

		wp_send_json_success(
			array(
				'msg' => esc_html__( 'Settings reset successfully!', 'dark-mode-toggle' ),
			)
		);
	}
}
