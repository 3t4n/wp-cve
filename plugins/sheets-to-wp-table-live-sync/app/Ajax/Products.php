<?php
/**
 * Responsible for managing ajax endpoints.
 *
 * @since 2.12.15
 * @package SWPTLS
 */

namespace SWPTLS\Ajax;

// If direct access than exit the file.
defined( 'ABSPATH' ) || exit;

/**
 * Responsible for fetching products.
 *
 * @since 2.12.15
 * @package SWPTLS
 */
class Products {

	/**
	 * Class constructor.
	 *
	 * @since 2.12.15
	 */
	public function __construct() {
		add_action( 'wp_ajax_gswpts_product_fetch', [ $this, 'fetch_all' ] );
		add_action( 'wp_ajax_nopriv_gswpts_product_fetch', [ $this, 'fetch_all' ] );
	}

	/**
	 * Fetch products ajax endpoint.
	 *
	 * @since 2.12.15
	 */
	public function fetch_all() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swptls-admin-app-nonce-action' ) ) {
			wp_send_json_error([
				'message' => __( 'Invalid action', 'sheetstowptable' ),
			]);
		}

		// $this->get_other_products();
		// wp_die();

		ob_start();
		$this->get_other_products();
		$plugin_cards_html = ob_get_clean();

		// Return the HTML content within the JSON response.
		wp_send_json_success([
			'plugin_cards_html' => $plugin_cards_html,
		]);
		wp_die();
	}

	/**
	 * Get products from plugins api.
	 *
	 * @since 2.12.15
	 */
	public static function get_other_products() {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		remove_all_filters( 'plugins_api' );

		$plugins_allowedtags = [
			'a'       => [
				'href'   => [],
				'title'  => [],
				'target' => [],
			],
			'abbr'    => [ 'title' => [] ],
			'acronym' => [ 'title' => [] ],
			'code'    => [],
			'pre'     => [],
			'em'      => [],
			'strong'  => [],
			'ul'      => [],
			'ol'      => [],
			'li'      => [],
			'p'       => [],
			'br'      => [],
		];

		$recommended_plugins = [];

		/* stock-sync-with-google-sheet-for-woocommerce Plugin */
		$args = [
			'slug'   => 'stock-sync-with-google-sheet-for-woocommerce',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );

		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['stock-sync-with-google-sheet-for-woocommerce']                    = $data;
			$recommended_plugins['stock-sync-with-google-sheet-for-woocommerce']->name              = __( 'Stock Sync for WooCommerce with Google Sheet – Easy Stock Management and Inventory Management System for WooCommerce', 'sheetstowptable' );
			$recommended_plugins['stock-sync-with-google-sheet-for-woocommerce']->short_description = esc_html__( 'Auto-sync WooCommerce products from Google Sheets. An easy and powerful solution for WooCommerce inventory management.', 'sheetstowptable' );
		}

		/* WP Dark Mode Plugin */
		$args = [
			'slug'   => 'wp-dark-mode',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );

		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['wp-dark-mode']                    = $data;
			$recommended_plugins['wp-dark-mode']->name              = __( 'WP Dark Mode', 'sheetstowptable' );
			$recommended_plugins['wp-dark-mode']->short_description = esc_html__( 'Help your website visitors spend more time and 
			an eye-pleasing reading experience. Personal preference rules always king. WP Dark Mode can be a game-changer for your website.', 'sheetstowptable' );
		}

		/* Jitsi meet Plugin. */
		$args = [
			'slug'   => 'webinar-and-video-conference-with-jitsi-meet',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );

		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['webinar-and-video-conference-with-jitsi-meet']                    = $data;
			$recommended_plugins['webinar-and-video-conference-with-jitsi-meet']->name              = __( 'Webinar and Video Conference with Jitsi Meet', 'sheetstowptable' );
			$recommended_plugins['webinar-and-video-conference-with-jitsi-meet']->short_description = esc_html__( 'The best WordPress webinar plugin with branded meetings. Add Jitsi meetings, host webinars and video conferences on your website.', 'sheetstowptable' );
		}

		/* FormToChat – Connect Contact Form to Chat Apps with Contact Form 7 Integration Plugin. */
		$args = [
			'slug'   => 'social-contact-form',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );

		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['social-contact-form']                    = $data;
			$recommended_plugins['social-contact-form']->name              = __( 'FormToChat – Connect Contact Form to Chat Apps with Contact Form 7 Integration', 'sheetstowptable' );
			$recommended_plugins['social-contact-form']->short_description = esc_html__( 'WhatsApp Chat for WordPress🔥. Connect contact forms to WhatsApp. A WhatsApp notifications plugin with Contact Form 7 integration.', 'sheetstowptable' );
		}

		/* easy-video-reviews Plugin */
		$args = [
			'slug'   => 'easy-video-reviews',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );
		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['easy-video-reviews']                    = $data;
			$recommended_plugins['easy-video-reviews']->name              = __( 'Easy Video Reviews', 'sheetstowptable' );
			$recommended_plugins['easy-video-reviews']->short_description = esc_html__( 'Easy Video Reviews is the best 
			and easiest video review plugin for WordPress, fully compatible with WooCommerce and Easy Digital Downloads plugins.', 'sheetstowptable' );
		}

		/* Zero BS Accounting Plugin */
		$args = [
			'slug'   => 'zero-bs-accounting',
			'fields' => [
				'short_description' => true,
				'icons'             => true,
				'reviews'           => false, // Excludes all reviews.
			],
		];

		$data = plugins_api( 'plugin_information', $args );

		if ( $data && ! is_wp_error( $data ) ) {
			$recommended_plugins['zero-bs-accounting']                    = $data;
			$recommended_plugins['zero-bs-accounting']->name              = __( 'Zero BS Accounting', 'sheetstowptable' );
			$recommended_plugins['zero-bs-accounting']->short_description = esc_html__( 'WordPress accounting Plugin for people with e zero accounting knowledge. Track your income and expenses from the WordPress dashboard.', 'sheetstowptable' );
		}

		// END Plugin list .

		foreach ( (array) $recommended_plugins as $plugin ) {
			if ( is_object( $plugin ) ) {
				$plugin = (array) $plugin;
			}

			// Display the group heading if there is one.
			if ( isset( $plugin['group'] ) && $plugin['group'] !== $group ) {

				$group_name = $plugin['group'];

				// Starting a new group, close off the divs of the last one.
				if ( ! empty( $group ) ) {
					echo '</div>';
				}

				echo '<div class="plugin-group"><h3>' . esc_html( $group_name ) . '</h3>';
				// Needs an extra wrapping div for nth-child selectors to work.
				echo '<div class="plugin-items">';

				$group = $plugin['group'];
			}
			$title = wp_kses( $plugin['name'], $plugins_allowedtags );

			// Remove any HTML from the description.
			$description = wp_strip_all_tags( $plugin['short_description'] );
			$version     = wp_kses( $plugin['version'], $plugins_allowedtags );

			$name = wp_strip_all_tags( $title . ' ' . $version );

			$author = wp_kses( $plugin['author'], $plugins_allowedtags );
			if ( ! empty( $author ) ) {
				/* translators: %s: Plugin author. */
				$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
			}

			$requires_php = isset( $plugin['requires_php'] ) ? $plugin['requires_php'] : null;
			$requires_wp  = isset( $plugin['requires'] ) ? $plugin['requires'] : null;

			$compatible_php = is_php_version_compatible( $requires_php );
			$compatible_wp  = is_wp_version_compatible( $requires_wp );
			$tested_wp      = ( empty( $plugin['tested'] ) || version_compare( get_bloginfo( 'version' ), $plugin['tested'], '<=' ) );

			$action_links = [];

			if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
				$status = install_plugin_install_status( $plugin );

				switch ( $status['status'] ) {
					case 'install':
						if ( $status['url'] ) {
							if ( $compatible_php && $compatible_wp ) {
								$action_links[] = sprintf(
									'<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
									esc_attr( $plugin['slug'] ),
									esc_url( $status['url'] ),
									/* translators: %s: Plugin name and version. */
									esc_attr( sprintf( _x( 'Install %s now', 'plugin' ), $name ) ),
									esc_attr( $name ),
									__( 'Install Now' )
								);
							} else {
								$action_links[] = sprintf(
									'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
									_x( 'Cannot Install', 'plugin' )
								);
							}
						}
						break;

					case 'update_available':
						if ( $status['url'] ) {
							if ( $compatible_php && $compatible_wp ) {
								$action_links[] = sprintf(
									'<a class="update-now button aria-button-if-js" data-plugin="%s" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
									esc_attr( $status['file'] ),
									esc_attr( $plugin['slug'] ),
									esc_url( $status['url'] ),
									/* translators: %s: Plugin name and version. */
									esc_attr( sprintf( _x( 'Update %s now', 'plugin' ), $name ) ),
									esc_attr( $name ),
									__( 'Update Now' )
								);
							} else {
								$action_links[] = sprintf(
									'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
									_x( 'Cannot Update', 'plugin' )
								);
							}
						}
						break;

					case 'latest_installed':
					case 'newer_installed':
						if ( is_plugin_active( $status['file'] ) ) {
							$action_links[] = sprintf(
								'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
								_x( 'Active', 'plugin' )
							);
						} elseif ( current_user_can( 'activate_plugin', $status['file'] ) ) {
							$button_text = esc_html__( 'Activate', 'sheetstowptable' );
							/* translators: %s: Plugin name. */
							$button_label = _x( 'Activate %s', 'plugin' );
							$activate_url = add_query_arg(
								[
									'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $status['file'] ),
									'action'   => 'activate',
									'plugin'   => $status['file'],
								],
								network_admin_url( 'plugins.php' )
							);

							if ( is_network_admin() ) {
								$button_text = __( 'Network Activate' );
								/* translators: %s: Plugin name. */
								$button_label = _x( 'Network Activate %s', 'plugin' );
								$activate_url = add_query_arg( [ 'networkwide' => 1 ], $activate_url );
							}

							$action_links[] = sprintf(
								'<a href="%1$s" class="button activate-now" aria-label="%2$s">%3$s</a>',
								esc_url( $activate_url ),
								esc_attr( sprintf( $button_label, $plugin['name'] ) ),
								$button_text
							);
						} else {
							$action_links[] = sprintf(
								'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
								_x( 'Installed', 'plugin' )
							);
						}
						break;
				}
			}

			$details_link = esc_url( self_admin_url(
				'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .
				'&amp;TB_iframe=true&amp;width=600&amp;height=550'
			) );

			$action_links[] = sprintf(
				'<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
				esc_url( $details_link ),
				/* translators: %s: Plugin name and version. */
				esc_attr( sprintf( __( 'More information about %s' ), $name ) ),
				esc_attr( $name ),
				__( 'More Details' )
			);

			if ( ! empty( $plugin['icons']['svg'] ) ) {
				$plugin_icon_url = $plugin['icons']['svg'];
			} elseif ( ! empty( $plugin['icons']['2x'] ) ) {
				$plugin_icon_url = $plugin['icons']['2x'];
			} elseif ( ! empty( $plugin['icons']['1x'] ) ) {
				$plugin_icon_url = $plugin['icons']['1x'];
			} else {
				$plugin_icon_url = $plugin['icons']['default'];
			}

			/**
			 * Filters the install action links for a plugin.
			 *
			 * @param mixed $action_links An array of plugin action links. Defaults are links to Details and Install Now.
			 * @param array    $plugin       The plugin currently being listed.
			 */
			$action_links = apply_filters( 'plugin_install_action_links', $action_links, $plugin );

			$last_updated_timestamp = strtotime( $plugin['last_updated'] );
			?>
<div class="plugin-card plugin-card-<?php echo sanitize_html_class( $plugin['slug'] ); ?>">
			<?php
			if ( ! $compatible_php || ! $compatible_wp ) {
				echo '<div class="notice inline notice-error notice-alt"><p>';
				if ( ! $compatible_php && ! $compatible_wp ) {
					esc_html_e( 'This plugin doesn&#8217;t work with your versions of WordPress and PHP.' );
					if ( current_user_can( 'update_core' ) && current_user_can( 'update_php' ) ) {
						printf(
							/* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
							' ' . wp_kses( '<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.', [ 'a' => [ 'href' => '' ] ] ),
							esc_url( self_admin_url( 'update-core.php' ) ),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					} elseif ( current_user_can( 'update_core' ) ) {
						printf(
							/* translators: %s: URL to WordPress Updates screen. */
							' ' . wp_kses(
								'<a href="%s">Please update WordPress</a>.',
								[ 'a' => [ 'href' => '' ] ]
							),
							esc_url( self_admin_url( 'update-core.php' ) )
						);
					} elseif ( current_user_can( 'update_php' ) ) {
						printf(
							/* translators: %s: URL to Update PHP page. */
							' ' . wp_kses(
								'<a href="%s">Learn more about updating PHP</a>.',
								[ 'a' => [ 'href' => '' ] ]
							),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					}
				} elseif ( ! $compatible_wp ) {
					esc_html_e( 'This plugin doesn&#8217;t work with your version of WordPress.' );
					if ( current_user_can( 'update_core' ) ) {
						printf(
							/* translators: %s: URL to WordPress Updates screen. */
							' ' . wp_kses( '<a href="%s">Please update WordPress</a>.',
								[ 'a' => [ 'href' => '' ] ]
							),
							esc_url( self_admin_url( 'update-core.php' ) )
						);
					}
				} elseif ( ! $compatible_php ) {
					esc_html_e( 'This plugin doesn&#8217;t work with your version of PHP.' );
					if ( current_user_can( 'update_php' ) ) {
						printf(
							/* translators: %s: URL to Update PHP page. */
							' ' . wp_kses(
								'<a href="%s">Learn more about updating PHP</a>.',
								[ 'a' => [ 'href' => '' ] ]
							),
							esc_url( wp_get_update_php_url() )
						);
						wp_update_php_annotation( '</p><p><em>', '</em>' );
					}
				}
				echo '</p></div>';
			}
			?>
	<div class="plugin-card-top">
		<div class="name column-name">
			<h3>
				<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal">
					<?php echo esc_attr( $title ); ?>
					<img src="<?php echo esc_attr( $plugin_icon_url ); ?>" class="plugin-icon" alt="" />
				</a>
			</h3>
		</div>
		<div class="action-links">
			<?php
			if ( $action_links ) {
				echo '<ul class="plugin-action-buttons">';
				foreach ( $action_links as $link ) {
					echo wp_kses_post( $link ) . '</br>';
				}
				echo '</ul>';
			}
			?>
		</div>
		<div class="desc column-description">
			<p><?php echo esc_html( $description ); ?></p>
            <p class="authors"><?php echo $author; //phpcs:ignore ?></p>
		</div>
	</div>
	<div class="plugin-card-bottom">
		<div class="vers column-rating">
			<?php
				wp_star_rating([
					'rating' => $plugin['rating'],
					'type'   => 'percent',
					'number' => $plugin['num_ratings'],
				]);
			?>
			<span class="num-ratings"
				aria-hidden="true">(<?php echo esc_attr( number_format_i18n( $plugin['num_ratings'] ) ); ?>)</span>
		</div>
		<div class="column-updated">
			<strong><?php esc_attr_e( 'Last Updated:' ); ?></strong>
			<?php
				/* translators: %s: Human-readable time difference. */
				printf( esc_html( __( '%s ago' ) ), esc_html( human_time_diff( $last_updated_timestamp ) ) );
			?>
		</div>
		<div class="column-downloaded">
			<?php
			if ( $plugin['active_installs'] >= 1000000 ) {
				$active_installs_millions = floor( $plugin['active_installs'] / 1000000 );
				$active_installs_text     = sprintf(
					/* translators: %s: Number of millions. */
					_nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'Active plugin installations' ),
					number_format_i18n( $active_installs_millions )
				);
			} elseif ( 0 === $plugin['active_installs'] ) {
				$active_installs_text = _x( 'Less Than 10', 'Active plugin installations' );
			} else {
				$active_installs_text = number_format_i18n( $plugin['active_installs'] ) . '+';
			}
			/* translators: %s: Number of installations. */
			printf( esc_html( __( '%s Active Installations' ) ), esc_html( $active_installs_text ) );
			?>
		</div>
		<div class="column-compatibility">
			<?php
			if ( ! $tested_wp ) {
				echo '<span class="compatibility-untested">' . esc_html( __( 'Untested with your version of WordPress' ) ) . '</span>';
			} elseif ( ! $compatible_wp ) {
				echo '<span class="compatibility-incompatible"><strong>Incompatible</strong> with your version of WordPress</span>';
			} else {
				echo '<span class="compatibility-compatible"><strong>Compatible</strong> with your version of WordPress</span>';
			}
			?>
		</div>
	</div>
</div>
<?php } ?>
		<?php
	}
}
