<?php

/**
 * WPPFM Product Feed Manager Page Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Settings_Page' ) ) :

	/**
	 * Settings Form Class
	 *
	 * @since        3.2.0
	 */
	class WPPFM_Settings_Page {

		/**
		 * Generates the main part of the Settings page
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the main part of the Settings page
		 */
		public function display() {
			$html  = $this->add_data_storage();
			$html .= $this->settings_page();

			return $html;
		}

		private function settings_page() {
			$html  = '<div class="wppfm-page__title wppfm-center-page__title" id="wppfm-settings-title"><h1>' . esc_html__( 'Feed Manager - Settings', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= '</div>';

			// Feed Manager Settings Table
			$html .= '<div class="wppfm-page-layout__main" id="wppfm-feed-manager-settings-table">';
			$html .= '<div class="wppfm-settings-wrapper wppfm-auto-center-page-wrapper">';
			$html .= '<table class="form-table"><tbody>';
			$html .= $this->settings_content();
			$html .= '</tbody></table>';
			$html .= '</div></div>';

			return $html;
		}

		/**
		 * Generates html code for the Setting page.
		 *
		 * @since 1.5.0
		 * @since 1.7.0 Added the backups table.
		 * @since 1.8.0 Added the third party attributes text field.
		 * @since 1.9.0 Added the Re-initialize button.
		 * @since 2.3.0 Added the Notice option.
		 * @since 2.10.0 Added the show product identifiers option.
		 * @since 2.15.0 Added ths wpml full resolution url option.
		 * @since 3.2.0 Moved the Clear feed process button two steps up to prevent unintended clicking of the Re-initiate plugin button.
		 */
		private function settings_content() {
			$html = '';

			$auto_fix_feed_option            = get_option( 'wppfm_auto_feed_fix', false );
			$auto_feed_fix_checked           = true === $auto_fix_feed_option || 'true' === $auto_fix_feed_option ? ' checked ' : '';
			$background_processing_option    = get_option( 'wppfm_disabled_background_mode', 'false' );
			$background_processing_unchecked = true === $background_processing_option || 'true' === $background_processing_option ? ' checked ' : '';
			$process_logging_option          = get_option( 'wppfm_process_logger_status', 'false' );
			$process_logging_unchecked       = true === $process_logging_option || 'true' === $process_logging_option ? ' checked ' : '';
			$product_identifiers_option      = get_option( 'wppfm_show_product_identifiers', 'false' );
			$show_product_identifiers        = true === $product_identifiers_option || 'true' === $product_identifiers_option ? ' checked ' : '';
			$use_full_resolution_option      = get_option( 'wppfm_use_full_url_resolution', 'false' );
			$wpml_use_full_resolution_urls   = true === $use_full_resolution_option || 'true' === $use_full_resolution_option ? ' checked ' : '';

			$third_party_attribute_keywords = get_option( 'wppfm_third_party_attribute_keywords', '%wpmr%,%cpf%,%unit%,%bto%,%yoast%' );
			$notice_mailaddress             = get_option( 'wppfm_notice_mailaddress' ) ? get_option( 'wppfm_notice_mailaddress' ) : get_bloginfo( 'admin_email' );

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Auto feed fix', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-auto-feed-fix-mode" id="wppfm-auto-feed-fix-mode" type="checkbox" class="" value="1"' . $auto_feed_fix_checked . '> ';
			$html .= '<label for="wppfm-auto-feed-fix-mode">';
			$html .= esc_html__( 'Automatically try regenerating feeds that are failed (default off).', 'wp-product-feed-manager' ) . '</label></fieldset>';
			$html .= '<p><i>' . esc_html__( 'Leaving this option on can put extra strain on your server when feeds keep failing.', 'wp-product-feed-manager' ) . '</p></i>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Disable background processing', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-background-processing-mode" id="wppfm-background-processing-mode" type="checkbox" class="" value="1"' . $background_processing_unchecked . '> ';
			$html .= '<label for="wppfm-background-processing-mode">';
			$html .= esc_html__( 'Process feeds directly instead of in the background (default off). Try this option when feeds keep getting stuck in processing. ', 'wp-product-feed-manager' ) . '</label>';
			$html .= '<p><i>' . esc_html__( 'WARNING: When this option is selected the system can only update one feed at a time. Make sure to de-conflict your feeds auto-update schedules to prevent more than one feed auto-updates at a time.', 'wp-product-feed-manager' ) . '</i></p></fieldset>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Feed process logger', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-process-logging-mode" id="wppfm-process-logging-mode" type="checkbox" class="" value="1"' . $process_logging_unchecked . '> ';
			$html .= '<label for="wppfm-process-logging-mode">';
			$html .= esc_html__( 'When switched on, generates an extensive log of the feed process (default off).', 'wp-product-feed-manager' ) . '</label>';
			$html .= '<p><i>' . esc_html__( 'Switch this option only on request of the help desk. ', 'wp-product-feed-manager' ) . '</i></p></fieldset>';
			$html .= '</td></tr>';

			// @since 2.10.0.
			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Show product identifiers', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-product-identifiers-on" id="wppfm-product-identifiers" type="checkbox" class="" value="1"' . $show_product_identifiers . '> ';
			$html .= '<label for="wppfm-product-identifiers">';
			$html .= esc_html__( 'When switched on, adds Brand, GTIN and MPN product identifiers to the products (default off).', 'wp-product-feed-manager' ) . '</label>';
			$html .= '<p><i>' . esc_html__( 'This option will add product identifier input fields to the Inventory card of your products. The MPN identifier is also added to the product variations.', 'wp-product-feed-manager' ) . '</i></p></fieldset>';
			$html .= '</td></tr>';

			// @since 2.15.0.
			if ( has_filter( 'wppfm_get_wpml_permalink' ) )
			{
				$html .= '<tr vertical-align="top" class="">';
				$html .= '<th scope="row" class="titledesc">' . esc_html__('WPML: Use full resolution URLs', 'wp-product-feed-manager') . '</th>';
				$html .= '<td class="forminp forminp-checkbox">';
				$html .= '<fieldset>';
				$html .= '<input name="wppfm-wpml-use-full-resolution-urls" id="wppfm-wpml-use-full-resolution-urls" type="checkbox" class="" value="0"' . $wpml_use_full_resolution_urls . '> ';
				$html .= '<label for="wppfm-wpml-use-full-resolution-urls">';
				$html .= esc_html__('Enables full conversion of hard-coded URLs (default off).', 'wp-product-feed-manager') . '</label>';
				$html .= '<p><i>' . esc_html__('Use this option if you\'re using WPML and are getting incorrect URLs in your feed. This option will slightly increase the load on the database when processing a feed.', 'wp-product-feed-manager') . '</i></p></fieldset>';
				$html .= '</td></tr>';
			}

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Clear feed process', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<div class="wppfm-inline-button-wrapper">';
			$html .= '<a href="#" class="wppfm-button wppfm-blue-button" id="wppfm-clear-feed-process-button">' . esc_html__( 'Clear Feed Process', 'wp-product-feed-manager' ) . '</a>';
			$html .= '</div>';
			$html .= '<label for="clear">';
			$html .= esc_html__( 'Use this option when feeds get stuck processing - does not delete your current feeds or settings.', 'wp-product-feed-manager' ) . '</label></fieldset>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Third party attributes', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-input">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-third-party-attr-keys" id="wppfm-third-party-attr-keys" type="text" class="wppfm-wide-text-input-field" value="' . $third_party_attribute_keywords . '"> ';
			$html .= '<label for="wppfm-third-party-attr-keys">';
			$html .= esc_html__( 'Enter comma separated keywords and wildcards to use third party attributes.', 'wp-product-feed-manager' ) . '</label></fieldset>';
			$html .= '<p><i>' . esc_html__('Use specific wildcards. Do not use to broad wildcards like %_% because that will include default WooCommerce attributes and can sometimes result in incorrect feed outputs.', 'wp-product-feed-manager') . '</i></p></fieldset>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Notice recipient', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-input">';
			$html .= '<fieldset>';
			$html .= '<input name="wppfm-notice-mailaddress" id="wppfm-notice-mailaddress" type="email" class="wppfm-wide-text-input-field" value="' . $notice_mailaddress . '"> ';
			$html .= '<label for="wppfm-notice-mailaddress">';
			$html .= esc_html__( 'Email address of the feed manager.', 'wp-product-feed-manager' ) . '</label></fieldset>';
			$html .= '<p><i>' . esc_html__('Enter the email address of the person you want to be notified when a feed fails during an automatic feed update. This option requires an SMTP server for WordPress to be installed on your server.', 'wp-product-feed-manager') . '</i></p></fieldset>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Re-initialize', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td class="forminp forminp-checkbox">';
			$html .= '<fieldset>';
			$html .= '<div class="wppfm-inline-button-wrapper">';
			$html .= '<a href="#" class="wppfm-button wppfm-orange-button" id="wppfm-reinitiate-plugin-button">' . esc_html__( 'Re-initiate Plugin', 'wp-product-feed-manager' ) . '</a>';
			$html .= '</div>';
			$html .= '<label for="reinitiate">';
			$html .= esc_html__( 'Resets and updates the plugin.', 'wp-product-feed-manager' ) . '</label></fieldset>';
			$html .= '<p><i>' . esc_html__('Updates and cleans the tables if required, re-initiates the cron events and resets the stored license - does not delete your current feeds or settings. You need to re-enter your license after this action.', 'wp-product-feed-manager') . '</i></p></fieldset>';
			$html .= '</td></tr>';

			$html .= '<tr vertical-align="top" class="">';
			$html .= '<th scope="row" class="titledesc">' . esc_html__( 'Backups', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<td id="wppfm-backups-table-holder">';

			$html .= '<table id="wppfm-backups" class="wppfm-table wppfm-smallfat">';
			$html .= '<thead>';
			$html .= '<tr><th class="wppfm-manage-column wppfm-column-name">' . esc_html__( 'File Name', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<th class="wppfm-manage-column wppfm-column-name">' . esc_html__( 'Backup Date', 'wp-product-feed-manager' ) . '</th>';
			$html .= '<th class="wppfm-manage-column wppfm-column-name">' . esc_html__( 'Actions', 'wp-product-feed-manager' ) . '</th></tr>';
			$html .= '</thead>';
			$html .= '<tbody id="wppfm-backups-list"></tbody>';
			$html .= '</table>';

			$html .= '<div class="wppfm-inline-button-wrapper">';
			$html .= '<a href="#" class="wppfm-button wppfm-blue-button" id="wppfm-prepare-backup"><i class="wppfm-button-icon wppfm-icon-plus"></i>' . esc_html__( 'Add New Backup', 'wp-product-feed-manager' ) . '</a>';
			$html .= '</div>';
			$html .= '</td></tr>';
			$html .= '<tr style="display:none;" id="wppfm-backup-wrapper"><th>&nbsp</th><td>';
			$html .= '<input type="text" class="regular-text" id="wppfm-backup-file-name" placeholder="Enter a file name">';
			$html .= '<span class="button-secondary" id="wppfm-make-backup-button" disabled>' . esc_html__( 'Backup current feeds', 'wp-product-feed-manager' ) . '</span>';
			$html .= '<span class="button-secondary" id="wppfm-cancel-backup-button">' . esc_html__( 'Cancel backup', 'wp-product-feed-manager' ) . '</span>';

			$html .= '</td></tr>';

			return $html;
		}

		/**
		 * Stores data in the DOM for the Feed Manager Settings page
		 *
		 * @return string The html code for the data storage
		 */
		private function add_data_storage() {
			return
			'<div id="wppfm-settings-page-data-storage" class="wppfm-data-storage-element" 
				data-wppfm-wp-uploads-url="' . WPPFM_UPLOADS_URL . '"
				data-wppfm-plugin-version-id="' . WPPFM_PLUGIN_VERSION_ID . '" 
				data-wppfm-plugin-version-nr="' . WPPFM_VERSION_NUM . '">
			</div>';
		}
	}

	// end of WPPFM_Settings_Page class

endif;
