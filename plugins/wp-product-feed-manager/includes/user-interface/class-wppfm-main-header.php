<?php

/**
 * WP Product Feed Manager Main Header Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Main_Header' ) ) :

	class WPPFM_Main_Header {
		public function show( $page = '' ) {
			$html = '<div class="wppfm-page-layout__header">';

			$html .= $this->logo();

			$html .= $this->navigation( $page );

			$html .= $this->working_spinner(); // Hidden container for the working spinner.

			$html .= '</div>';

			$html .= $this->alert_fields(); // Hidden container for the alert fields.

			$html .= $this->message_fields(); // Hidden container for the message fields.

			return $html;
		}

		/**
		 * Generates the logo part of the header. The logo also contains a link to the main page of the plugin.
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the logo part of the header
		 */
		private function logo() {
			return
			'<div class="wppfm-page-layout__header__logo">
			   <a href="admin.php?page=wp-product-feed-manager" class="wppfm-logo">
			      <img src="' . WPPFM_PLUGIN_URL . '/images/email-logo-wpmr-color.png" alt="WP Marketing Robot" class="wppfm-page-layout__header__nav-wrapper__logo">
			   </a>
			</div>';
		}

		/**
		 * Generates the navigation part of the header. The navigation contains links to the different pages of the plugin.
		 *
		 * @since 3.2.0
		 *
		 * @param string $page The page that is currently active
		 *
		 * @return string The html code for the navigation part of the header
		 */
		private function navigation( $page ) {
			$html  = '<div class="wppfm-page-layout__header__nav-wrapper">';
			$html .=    '<div class="wppfm-nav-wrapper">';
			$html .=       '<ul class="wppfm-nav__feed-selectors">';
			$html .=          '<li class="wppfm-nav__selector" id="wppfm-feed-list-page-selector"><a href="admin.php?page=wp-product-feed-manager" class="wppfm-nav-link';
			if ( 'feed-list-page' === $page ) { $html .= ' wppfm-nav-link--selected'; }
			$html .=          '">' . esc_html__( 'Feed List', 'wp-product-feed-manager' ) . '</a></li>';
			$html .=          '<li class="wppfm-nav__selector" id="wppfm-feed-editor-page-selector"><a href="admin.php?page=wppfm-feed-editor-page" class="wppfm-nav-link';
			if ( 'feed-editor-page' === $page ) { $html .= ' wppfm-nav-link--selected'; }
			$html .=          '">' . esc_html__( 'Feed Editor', 'wp-product-feed-manager' ) . '</a></li>';
			$html .=       '</ul>';
			$html .=       '<ul class="wppfm-nav__support-selectors">';

			if ( 'full' === WPPFM_PLUGIN_VERSION_ID ) { // only show the Channel Manager button in the full version
				$html .= '<li class="wppfm-nav__selector" id="wppfm-channel-manager-page-selector"><a href="admin.php?page=wppfm-channel-manager-page" class="wppfm-nav-link';
				if ( 'channel-manager-page' === $page ) {
					$html .= ' wppfm-nav-link--selected';
				}
				$html .= '">' . esc_html__( 'Channel Manager', 'wp-product-feed-manager' ) . '</a></li>';
			}

			$html .=          '<li class="wppfm-nav__selector" id="wppfm-settings-page-selector"><a href="admin.php?page=wppfm-settings-page" class="wppfm-nav-link';
			if ( 'settings-page' === $page ) { $html .= ' wppfm-nav-link--selected'; }
			$html .=          '">' . esc_html__( 'Settings', 'wp-product-feed-manager' ) . '</a></li>';
			$html .=          '<li class="wppfm-nav__selector" id="wppfm-support-page-selector"><a href="admin.php?page=wppfm-support-page" class="wppfm-nav-link';
			if ( 'support-page' === $page ) { $html .= ' wppfm-nav-link--selected'; }
			$html .=          '">' . esc_html__( 'Support', 'wp-product-feed-manager' ) . '</a></li>';
			$html .=       '</ul>';
			$html .=    '</div>';
			$html .= '</div>';

			return $html;
		}

		/**
		 * Generates the spinner part of the header. The working spinner is used to indicate that a specific action is in progress.
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the feed spinner part of the header
		 */
		private function working_spinner() {
			return
			'<div class="wppfm-working-spinner" id="wppfm-working-spinner" style="display:none;">
				<img id="img-spinner" src="' . WPPFM_PLUGIN_URL . '/images/ajax-loader.gif" alt="Working" />
			</div>';
		}

		/**
		 * Generates the alert fields part of the header. The alert fields are used to display error, success and warning messages.
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the alert fields part of the header
		 */
		private function alert_fields() {
			return
			'<div class="wppfm-message-field notice notice-error" id="wppfm-error-message" style="display:none;"></div>
			 <div class="wppfm-message-field notice notice-success" id="wppfm-success-message" style="display:none;"></div>
			 <div class="wppfm-message-field notice notice-warning" id="wppfm-warning-message" style="display:none;"></div>';
		}

		private function message_fields() {
			return '';
		}
	}

	// end of WPPFM_Main_Header class

endif;
