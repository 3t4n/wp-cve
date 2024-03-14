<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

if ( ! function_exists( 'safelayout_preloader_set_style' ) ) {

	function safelayout_preloader_set_style( $options ) {
		?>
		#sl-preloader {
			height: 100vh;
			left: 0;
			max-height: 100%;
			max-width: 100%;
			pointer-events: none;
			position: fixed;
			top: 0;
			width: 100vw;
			z-index: 9999999;
		}
		.sl-pl-close-icon {
			border: 1px solid blue;
			cursor: pointer;
			fill: red;
			height: 30px;
			stroke-linecap: round;
			stroke-width: 0.5px;
			stroke: blue;
			width: 30px;
		}
		#sl-pl-close-button {
			display: none;
			pointer-events: auto;
			position: absolute;
			right: 10px;
			top: 10px;
			z-index: 999;
		}
		.sl-pl-loaded #sl-pl-counter,
		.sl-pl-loaded #sl-pl-close-button,
		.sl-pl-loaded .sl-pl-bar-container,
		.sl-pl-loaded .sl-pl-spin-container {
			opacity: 0;
			transition: opacity 0.5s ease-out 0s;
		}
		<?php
		// set background css ( opacity, background-color )
		if ( $options['background_anim'] != 'none' ) {
			require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-background.php';
			safelayout_preloader_set_background( $options['background_anim'] );
		}

		// set icon css ( animation, ... )
		if ( $options['icon'] != 'none' ) {
			$group1 = array( 'crawl', '3d-plate', 'wheel', 'spinner', 'turn', 'turn1', 'jump', 'infinite',
				'blade-vertical', 'blade-vertical1', 'flight', '3d-square', 'fold', 'triple-spinner', );
			$group2 = array( 'balloons', '3d-bar', 'gear', 'trail', 'bubble', 'bubble1', 'blade-horizontal',
				'blade-horizontal1', 'dive', 'circle', );

			if ( in_array( $options['icon'], $group1 ) ) {
				require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-icon-group1.php';
				safelayout_preloader_set_icon_group1( $options['icon'] );
			} else if ( in_array( $options['icon'], $group2 ) ) {
				require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-icon-group2.php';
				safelayout_preloader_set_icon_group2( $options['icon'] );
			} else {
				require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-icon-group3.php';
				safelayout_preloader_set_icon_group3( $options['icon'] );
			}
		}

		// set text css ( animation, ... )
		if ( trim( $options['text'] ) !== '' ) {
			require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-text.php';
			safelayout_preloader_set_text( $options['text_anim'] );
		}

		// set brand image css ( animation, ... )
		if ( trim( $options['brand_url'] ) !== '' ) {
			require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-brand.php';
			safelayout_preloader_set_brand( $options['brand_anim'] );
		}

		// set progress bar css ( animation, ... )
		if ( $options['bar_shape'] != 'No' ) {
			require_once SAFELAYOUT_PRELOADER_PATH . 'inc/safelayout-preloader-set-bar.php';
			safelayout_preloader_set_bar( $options );
		}

		// set counter css
		if ( $options['counter'] === 'enable') {
		?>
		#sl-pl-counter {
			position: relative;
			line-height: normal;
			white-space: nowrap;
			width: 100%;
		}
		#sl-pl-bar-middle-container {
			position: relative;
		}
		<?php
		}
	}
}