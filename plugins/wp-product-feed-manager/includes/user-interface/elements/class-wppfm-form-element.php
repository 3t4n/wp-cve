<?php

/**
 * WPPFM Form Element Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @since 2.4.2
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Form_Element' ) ) :

	/**
	 * WPPFM Category Selector Element Class
	 *
	 * Contains the html elements code for the forms
	 */
	class WPPFM_Form_Element {

		/**
		 * Returns the code for the tabs in all main forms
		 *
		 * @return string html code for the tabs
		 * @since 2.37.0. Added the page identifier as an id in order to support E2E tests
		 */
		public static function main_form_tabs() { // REM_BLUE

			// Get the WPPFM_Tab objects
			$tabs = $GLOBALS['wppfm_tab_data'];

			$html_code = '<h2 class="nav-tab-wrapper">';

			// Html for the tabs
			foreach ( $tabs as $tab ) {
				$html_code .= '<a href="admin.php?' . $tab->get_page_tab_url() . '"';
				$html_code .= 'class="nav-tab' . $tab->tab_selected_string() . '" id="wppfm-' . $tab->get_page_identifier() . '-tab">' . $tab->get_tab_title() . '</a>';
			}

			$html_code .= '</h2>';

			return $html_code;
		}

		/**
		 * Returns the code that stores the feeds url
		 *
		 * @return string with var code containing the feeds url
		 */
//		public static function feed_url_holder() { // REM_BLUE
//			$query_class   = new WPPFM_Queries();
//			$feed_file_url = array_key_exists( 'id', $_GET ) && $_GET['id'] ? $query_class->get_file_url_from_feed( $_GET['id'] ) : '';
//
//			return '<var id="wppfm-feed-url" style="display:none;" >' . $feed_file_url . '</var>';
//		}

//		public static function used_feed_names() { // REM_BLUE
//			$query_class = new WPPFM_Queries();
//			$feed_names  = $query_class->get_all_feed_names();
//			$used_names  = array();
//
//			foreach ( $feed_names as $name ) {
//				$used_names[] = $name->title;
//			}
//
//			return '<var id="wppfm-all-feed-names" style="display:none;" >' . wp_json_encode( $used_names ) . '</var>';
//		}

		public static function feed_editor_sub_title( $header_sub_title ) {
			return '<div class="wppfm-page__sub-title-wrapper"><p class="wppfm-tab-page-sub-title" id="wppfm-tab-page-sub-title">' . $header_sub_title . '<br><a href="#" target="_blank"></a></p></div>';
		}

		public static function wppfm_channel_versions() {
			$channel_class      = new WPPFM_Channel();
			$queries_class      = new WPPFM_Queries();
			$installed_channels = $queries_class->read_installed_channels();
			$response           = $channel_class->get_channels_from_server();
			$channels_info      = array();

			if ( ! is_wp_error( $response ) ) {
				$available_channels = json_decode( $response['body'] );

				foreach ( $installed_channels as $channel ) {
					// find the correct channel in the available channels array
					$available_channel = array_filter(
						$available_channels,
						function ( $available_channel ) use ( $channel ) {
							return $available_channel->short_name === $channel['short'];
						}
					);

					sort( $available_channel ); // sort the array to get the first element

					$channel_info                       = array();
					$channel_info['channel_short_name'] = $channel['short'];
					$channel_info['channel_name']       = $channel['name'];
					$channel_info['channel_id']         = $channel['channel_id'];
					$channel_info['installed_version']  = $channel_class->get_channel_file_version( $channel['short'], 0, true );
					$channel_info['available_version']  = $available_channel ? $available_channel[0]->version : 'unknown';

					$channels_info[] = $channel_info;
				}
			}

			return '<var id="wppfm-installed-channel-versions" style="display:none;" >' . wp_json_encode( $channels_info ) . '</var>';
		}

		/**
		 * Returns the code for both Save & Generate and Save buttons.
		 *
		 * @param   string  $button_section_class   Class name for whole button section
		 * @param   string  $button_section_id      ID for whole button section
		 * @param   string  $generate_button_id     ID for the Save & Generate button
		 * @param   string  $save_button_id         ID for the Save button
		 * @param   string  $open_feed_button_id    ID for the Open Feed button
		 * @param   string  $initial_display        sets the initial display to any of the display style options (default none)
		 *
		 * @return string
		 */
		public static function feed_generation_buttons( $button_section_class, $button_section_id, $generate_button_id, $save_button_id, $open_feed_button_id, $initial_display = 'none' ) {
			return '<section class="' . $button_section_class . '" id="' . $button_section_id . '" style="display:' . $initial_display . ';">
				<div class="wppfm-inline-button-wrapper">
				<a href="#" class="wppfm-button wppfm-blue-button wppfm-disabled-button" id="' . $generate_button_id . '">' . esc_html__( 'Save & Generate Feed', 'wp-product-feed-manager' ) . '</a>
				</div>
				<div class="wppfm-inline-button-wrapper">
				<a href="#" class="wppfm-button wppfm-blue-button wppfm-disabled-button" id="' . $save_button_id . '">' . esc_html__( 'Save Feed', 'wp-product-feed-manager' ) . '</a>
				</div>
				<div class="wppfm-inline-button-wrapper">
				<a href="#" class="wppfm-button wppfm-blue-button wppfm-disabled-button" id="' . $open_feed_button_id . '">' . esc_html__( 'View Feed', 'wp-product-feed-manager' ) . '</a>
				</div>
				</section>';
		}

		/**
		 * Returns the code for the Open Feed List button.
		 *
		 * @return string
		 */
		public static function open_feed_list_button() {
			return '<section class="wppfm-bottom-buttons-wrapper" id="page-bottom-buttons" style="display:none;"><input class="button-primary" type="button" ' .
				'onclick="parent.location=\'admin.php?page=wp-product-feed-manager\'" name="new" value="' .
				__( 'Open Feed List', 'wp-product-feed-manager' ) . '" id="add-new-feed-button" /></section>';
		}
	}

	// end of WPPFM_Form_Element class

endif;
