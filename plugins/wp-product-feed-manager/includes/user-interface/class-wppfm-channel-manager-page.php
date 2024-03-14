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

if ( ! class_exists( 'WPPFM_Channel_Manager_Page' ) ) :

	/**
	 * Feed List Form Class
	 *
	 * @since 3.2.0
	 */
	class WPPFM_Channel_Manager_Page {

		/**
		 * Generates the main part of the Channel Manager page
		 *
		 * @since 3.2.0
		 *
		 * @return string The html code for the main part of the Channel Manager page
		 */
		public function display( $updated ) {
			$html  = $this->channel_info_popup();
			$html .= $this->channel_manager_page( $updated );

			return $html;
		}

		private function channel_manager_page( $updated ) {
			$html  = '<div class="wppfm-page__title wppfm-center-page__title" id="wppfm-channel-manager-title"><h1>' . esc_html__( 'Feed Manager - Channel Manager', 'wp-product-feed-manager' ) . '</h1></div>';
			$html .= '</div>';

			// Feed Manager Channel Manager Table
			$html .= '<div class="wppfm-page-layout__main" id="wppfm-feed-manager-channel-manager-table">';
			$html .= '<div class="wppfm-channel-manager-wrapper wppfm-auto-center-page-wrapper">';
			$html .= $this->channel_manager_content( $updated );
			$html .= '</div>';
			$html .= '</div>';

			return $html;
		}

		private function channel_manager_content( $updated ) {
			$channels_class = new WPPFM_Channel();

			$response = $channels_class->get_channels_from_server();

			$html = '';

			if ( ! is_wp_error( $response ) ) {
				$available_channels = json_decode( $response['body'] );

				if ( $available_channels ) {

					$installed_channels_names = $channels_class->get_installed_channel_names();

					$channels_class->add_status_data_to_available_channels( $available_channels, $installed_channels_names, $updated );

					$channels_class->add_channel_info_links_to_channels( $available_channels );

					// Split the available channels into installed and uninstalled.
					$installed_channels = array_filter(
						$available_channels,
						function ( $channel ) {
							return ( 'installed' === $channel->status );
						}
					);

					$uninstalled_channels = array_filter(
						$available_channels,
						function ( $channel ) {
							return ( 'installed' !== $channel->status );
						}
					);

					$html .= '<h3>' . esc_html__( 'Installed Channels:', 'wp-product-feed-manager' ) . '</h3>';
					$html .= '<div class="wppfm-channels-tiles-wrapper--installed">';

					foreach ( $installed_channels as $channel ) {
						$html .= $this->installed_channel_tile( $channel );
					}

					$html .= '</div>';

					$html .= '<h3>' . esc_html__( 'Available Channels:', 'wp-product-feed-manager' ) . '</h3>';

					$html .= '<div class="wppfm-channels-tiles-wrapper--available">';

					foreach ( $uninstalled_channels as $channel ) {
						$html .= $this->uninstalled_channel_tile( $channel );
					}
				}

			} else {
				/* translators: %s: link to the support page */
				echo wppfm_handle_wp_errors_response( $response, sprintf( __( '2965 - Could not connect to the channel download server. Please try to refresh the page in a few minutes again. You can open a support ticket at %s if the issue persists.', 'wp-product-feed-manager' ), WPPFM_SUPPORT_PAGE_URL ) );
			}

			return $html;
		}

		private function installed_channel_tile( $channel ) {
			$latest_version = (float) $channel->installed_version >= (float) $channel->version;
			$remove_nonce = wp_create_nonce( 'delete-channel-nonce' );

			if ( $latest_version ) {
				$info_button = $this->channel_tile_info_button( $channel->short_name );
			} else {
				$update_nonce = wp_create_nonce( 'update-channel-nonce' );
				$info_button = '<a href="admin.php?page=wppfm-channel-manager-page&wppfm_action=update&wppfm_channel=' . $channel->short_name . '&wppfm_code=' . $channel->dir_code . '&wppfm_nonce=' . $update_nonce . '" class="wppfm-button wppfm-inline-button wppfm-green-button" 
				id="wppfm-install-' . $channel->short_name . '-channel-button">' . esc_html__( 'Update Available', 'wp-product-feed-manager' ) . '</a>';
			}

			return
			'<div class="wppfm-channel-tile-wrapper" id="wppfm-' . $channel->short_name . '-channel-tile-wrapper">
				<img class="wppfm-channel-tile__thumbnail" src="' . urldecode( $channel->image ) . '" alt="channel-logo">
					<h3>' . $channel->channel . '</h3>
				<div class="wppfm-inline-button-wrapper">
					<a href="admin.php?page=wppfm-channel-manager-page&wppfm_action=remove&wppfm_channel=' . $channel->short_name . '&wppfm_code=' . $channel->dir_code . '&wppfm_nonce=' . $remove_nonce .
					'" class="wppfm-button wppfm-inline-button wppfm-orange-button" id="wppfm-remove-' . $channel->short_name . '-channel-button"
					onclick="return confirm(\'' . esc_html__( 'Please confirm you want to remove this channel! Removing this channel will also remove all its feed files.', 'wp-product-feed-manager' ) . '\')">' . esc_html__( 'Remove', 'wp-product-feed-manager' ) . '</a>
					' . $info_button . '
				</div>
				' . $this->channel_data_storage_element( $channel ) . '
			</div>';
		}

		private function uninstalled_channel_tile( $channel ) {
			$install_nonce = wp_create_nonce( 'install-channel-nonce' );

			return
				'<div class="wppfm-channel-tile-wrapper" id="wppfm-' . $channel->short_name . '-channel-tile-wrapper">
				<img class="wppfm-channel-tile__thumbnail" src="' . urldecode( $channel->image ) . '" alt="channel-logo">
					<h3>' . $channel->channel . '</h3>
				<div class="wppfm-inline-button-wrapper">
					<a href="admin.php?page=wppfm-channel-manager-page&wppfm_action=install&wppfm_channel=' . $channel->short_name . '&wppfm_code=' . $channel->dir_code . '&wppfm_nonce=' . $install_nonce .
					'" class="wppfm-button wppfm-inline-button wppfm-green-button" id="wppfm-install-' . $channel->short_name . '-channel-button">
					' . esc_html__( 'Install', 'wp-product-feed-manager' ) . '</a>
					' . $this->channel_tile_info_button( $channel->short_name ) . '
				</div>
				' . $this->channel_data_storage_element( $channel ) . '
			</div>';
		}

		private function channel_tile_info_button( $channel_short_name ) {
			return '<a href="#" class="wppfm-button wppfm-inline-button wppfm-blue-button" id="wppfm-' . $channel_short_name . '-channel-info-button" onclick="wppfm_showChannelInfoPopup( \'' . $channel_short_name . '\' )">' . esc_html__( 'Channel Info', 'wp-product-feed-manager' ) . '</a>';
		}

		private function channel_data_storage_element( $channel ) {
			return
			'<div id="wppfm-' . $channel->short_name . '-channel-data" class="wppfm-data-storage-element"
				data-channel-name="' . $channel->channel . '" 
				data-short-name="' . $channel->short_name . '" 
				data-version="' . $channel->version . '" 
				data-dir-code="' . $channel->dir_code . '" 
				data-status="' . $channel->status . '" 
				data-installed-version="' . $channel->installed_version . '" 
				data-info-link="' . $channel->info_link . '" 
				data-specifications-link="' . $channel->specifications_link . '">
			</div>';
		}

		private function channel_info_popup() {
			return
			'<div id="wppfm-channel-info-popup" class="wppfm-popup" style="display:none">
				<div class="wppfm-popup__header">
					<h3 id="wppfm-channel-info-popup__name"></h3>
					<div class="wppfm-popup__close-button"><b>X</b></div>
				</div>
				<div class="wppfm-popup__content">
					<p class="wppfm-popup__content-item" id="wppfm-channel-info-popup__status"></p>
					<p class="wppfm-popup__content-item" id="wppfm-channel-info-popup__installed-version"></p>
					<p class="wppfm-popup__content-item" id="wppfm-channel-info-popup__latest-version"></p>
					<p class="wppfm-popup__content-item" id="wppfm-channel-info-popup__info-link" style="display: none"></p>
					<p class="wppfm-popup__content-item" id="wppfm-channel-info-popup__feed-specifications-link" style="display: none"></p>
				</div>
			</div>';
		}
	}

	// end of WPPFM_Channel_Manager_Page class

endif;
