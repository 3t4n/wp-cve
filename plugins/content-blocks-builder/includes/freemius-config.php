<?php
/**
 * Freemius utilities
 *
 * @package   BoldBlocks
 * @author    Phi Phan <mrphipv@gmail.com>
 * @copyright Copyright (c) 2024, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( FreemiusConfig::class ) ) :
	/**
	 * The FreemiusConfig class.
	 */
	class FreemiusConfig extends CoreComponent {
		/**
		 * The premium title
		 *
		 * @var string
		 */
		private $premium_title;

		/**
		 * The constructor
		 */
		public function __construct( $the_plugin_instance ) {
			parent::__construct( $the_plugin_instance );

			// Set value for labels.
			$this->premium_title = __( 'Go Premium', 'content-blocks-builder' );
		}

		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Add header left links.
			add_filter( 'content_blocks_builder_get_header_left_links', [ $this, 'header_links' ] );

			// Add the settings page link to plugin list screen.
			add_filter( 'plugin_action_links_' . plugin_basename( BOLDBLOCKS_CBB_ROOT_FILE ), [ $this, 'plugin_settings_links' ] );

			// Redirect to the getting started page.
			add_action( 'admin_init', [ $this, 'boldblocks_activation_redirect' ] );
		}

		/**
		 * Add freemius pages
		 *
		 * @param array $links
		 * @return array
		 */
		public function header_links( $links ) {
			if ( cbb_fs()->is_not_paying() ) {
				$links[] = [
					'url'    => 'https://contentblocksbuilder.com/pro/?utm_source=CBB+Free&utm_campaign=CBB+Upgrade&utm_medium=link&utm_content=header',
					'title'  => $this->premium_title,
					'target' => '_blank',
					'icon'   => '<span class="dashicons dashicons-superhero-alt"></span> ',
					'class'  => 'go-premium',
				];
			} else {
				$links[] = [
					'url'    => 'https://contentblocksbuilder.com/?utm_source=CBB+Pro&utm_campaign=CBB+visit+site&utm_medium=link&utm_content=header',
					'title'  => __( 'Visit site', 'content-blocks-builder' ),
					'target' => '_blank',
					'icon'   => '<span class="dashicons dashicons-external"></span> ',
					'class'  => 'go-premium go-site',
				];
			}

			return $links;
		}

		/**
		 * Add the premium link to the plugin admin screen.
		 *
		 * @param array $links
		 * @return array
		 */
		public function plugin_settings_links( $links ) {
			$label = esc_html__( 'Settings', 'content-blocks-builder' );
			$slug  = 'cbb-settings';

			array_unshift( $links, "<a href='edit.php?post_type=boldblocks_block&page={$slug}'>{$label}</a>" );

			if ( cbb_fs()->is_not_paying() ) {
				$links[] = sprintf( '<a href="%1$s" target="_blank" style="color: #d20962">%2$s</a>', 'https://contentblocksbuilder.com/pro/?utm_source=CBB+Free&utm_campaign=CBB+Upgrade&utm_medium=link&utm_content=action-link', $this->premium_title );
			}

			return $links;
		}

			/**
			 * Redirect to the getting started page.
			 *
			 * @return void
			 */
		public function boldblocks_activation_redirect() {
			if ( ! cbb_fs()->is_activation_mode() ) {
				// Make sure it's the correct user.
				if ( ! wp_doing_ajax() && wp_get_current_user()->ID > 0 && intval( get_option( 'boldblocks_activation_redirect', false ) ) === wp_get_current_user()->ID ) {
					// Make sure we don't redirect again after this one.
					delete_option( 'boldblocks_activation_redirect' );
					if ( ! is_network_admin() ) {
						wp_safe_redirect( admin_url( '/edit.php?post_type=boldblocks_block&page=cbb-settings&tab=getting-started' ) );
						exit;
					}
				}
			}
		}
	}
endif;
