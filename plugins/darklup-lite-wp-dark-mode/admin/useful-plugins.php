<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit();

wp_enqueue_style( 'wp-jquery-ui-dialog' );
wp_enqueue_script( 'jquery-ui-dialog' );

// You may comment this out IF you're sure the function exists.
require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
remove_all_filters( 'plugins_api' );

$single_plugins_allowedtags = [
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

$recommended_plugins_slug = [
	'guidant',
	'wceazy',
];

/**
 * Get plugin information from WordPress.org.
 *
 * @param string $slug Plugin slug.
 * @return object|bool
 */
function callback_recommended_plugin( $slug ) {
	$args = [
		'slug'   => $slug,
		'fields' => [
			'short_description' => true,
			'icons'             => true,
			'reviews'           => false, // Excludes all reviews.
		],
	];

	$data = plugins_api( 'plugin_information', $args );

	if ( $data && ! is_wp_error( $data ) ) {
		return $data;
	}

	return false;
}

$recommended_plugins = array_map('callback_recommended_plugin', $recommended_plugins_slug);
?>

<div class="wrap mystickyelement-wrap recommended-plugins">
	<h2>
		<?php esc_html_e( 'Try out more useful plugins by WPCommerz', 'darklup-lite' ); ?>
		
	</h2>
</div>

<div class="wrap recommended-plugins">
	<div class="wp-list-table widefat plugin-install">
		<div class="the-list">
			<?php
			foreach ( $recommended_plugins as $single_plugin ) {
				if ( is_object( $single_plugin ) ) {
					$single_plugin = (array) $single_plugin;
				}

				// Display the group heading if there is one.
				if ( isset( $single_plugin['group'] ) && $single_plugin['group'] !== $group ) {
					if ( isset( $this->groups[ $single_plugin['group'] ] ) ) {
						$group_name = $this->groups[ $single_plugin['group'] ];
						if ( isset( $single_plugins_group_titles[ $group_name ] ) ) {
							$group_name = $single_plugins_group_titles[ $group_name ];
						}
					} else {
						$group_name = $single_plugin['group'];
					}

					// Starting a new group, close off the divs of the last one.
					if ( ! empty( $group ) ) {
						echo '</div></div>';
					}

					echo '<div class="plugin-group"><h3>' . esc_html( $group_name ) . '</h3>';
					// Needs an extra wrapping div for nth-child selectors to work.
					echo '<div class="plugin-items">';

					$group = $single_plugin['group'];
				}

				$plugin_title = wp_kses( $single_plugin['name'], $single_plugins_allowedtags );

				// Remove any HTML from the description.
				$description = wp_strip_all_tags( $single_plugin['short_description'] );

				$version = wp_kses( $single_plugin['version'], $single_plugins_allowedtags );

				$name = wp_strip_all_tags( $plugin_title . ' ' . $version );

				$author = wp_kses( $single_plugin['author'], $single_plugins_allowedtags );

				if ( ! empty( $author ) ) {
					/* translators: %s: Plugin author. */
					$author = ' <cite>' . sprintf( __( 'By %s', 'darklup-lite' ), $author ) . '</cite>';
				}

				$requires_php = isset( $single_plugin['requires_php'] ) ? $single_plugin['requires_php'] : null;
				$requires_wp  = isset( $single_plugin['requires'] ) ? $single_plugin['requires'] : null;

				$compatible_php = is_php_version_compatible( $requires_php );
				$compatible_wp  = is_wp_version_compatible( $requires_wp );

				$tested_wp = ( empty( $single_plugin['tested'] ) || version_compare( get_bloginfo( 'version' ), $single_plugin['tested'], '<=' ) );

				$action_links = [];

				if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
					$plugin_status = install_plugin_install_status( $single_plugin );

					switch ( $plugin_status['status'] ) {
						case 'install':
							if ( $plugin_status['url'] ) {
								if ( $compatible_php && $compatible_wp ) {
									$action_links[] = sprintf(
										'<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
										esc_attr( $single_plugin['slug'] ),
										esc_url( $plugin_status['url'] ),
										/* translators: %s: Plugin name and version. */
										esc_attr( sprintf( _x( 'Install %s now', 'plugin', 'darklup-lite' ), $name ) ),
										esc_attr( $name ),
										__( 'Install Now', 'darklup-lite' )
									);
								} else {
									$action_links[] = sprintf(
										'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
										_x( 'Cannot Install', 'plugin', 'darklup-lite' )
									);
								}
							}
							break;

						case 'update_available':
							if ( $plugin_status['url'] ) {
								if ( $compatible_php && $compatible_wp ) {
									$action_links[] = sprintf(
										'<a class="update-now button aria-button-if-js" data-plugin="%s" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
										esc_attr( $plugin_status['file'] ),
										esc_attr( $single_plugin['slug'] ),
										esc_url( $plugin_status['url'] ),
										/* translators: %s: Plugin name and version. */
										esc_attr( sprintf( _x( 'Update %s now', 'plugin', 'darklup-lite' ), $name ) ),
										esc_attr( $name ),
										__( 'Update Now', 'darklup-lite' )
									);
								} else {
									$action_links[] = sprintf(
										'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
										_x( 'Cannot Update', 'plugin', 'darklup-lite' )
									);
								}
							}
							break;

						case 'latest_installed':
						case 'newer_installed':
							if ( is_plugin_active( $plugin_status['file'] ) ) {
								$action_links[] = sprintf(
									'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
									_x( 'Active', 'plugin', 'darklup-lite' )
								);
							} elseif ( current_user_can( 'activate_plugin', $plugin_status['file'] ) ) {
								$button_text = __( 'Activate', 'darklup-lite' );
								/* translators: %s: Plugin name. */
								$button_label = _x( 'Activate %s', 'plugin', 'darklup-lite' );
								$activate_url = add_query_arg(
									[
										'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $plugin_status['file'] ),
										'action'   => 'activate',
										'plugin'   => $plugin_status['file'],
									]
								);

								$action_links[] = sprintf(
									'<a href="%1$s" class="button activate-now" aria-label="%2$s">%3$s</a>',
									esc_url( $activate_url ),
									esc_attr( sprintf( $button_label, $single_plugin['name'] ) ),
									$button_text
								);
							} else {
								$action_links[] = sprintf(
									'<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
									_x( 'Installed', 'plugin', 'darklup-lite' )
								);
							}
							break;
					}
				}

				$details_link = self_admin_url(
					'plugin-install.php?tab=plugin-information&amp;plugin=' . $single_plugin['slug'] .
					'&amp;TB_iframe=true&amp;width=600&amp;height=550'
				);

				$action_links[] = wp_sprintf(
					'<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
					esc_url( $details_link ),
					/* translators: %s: Plugin name and version. */
					esc_attr( wp_sprintf( __( 'More information about %s', 'darklup-lite' ), $name ) ),
					esc_attr( $name ),
					__( 'More Details', 'darklup-lite' )
				);

				if ( ! empty( $single_plugin['icons']['svg'] ) ) {
					$single_plugin_icon_url = $single_plugin['icons']['svg'];
				} elseif ( ! empty( $single_plugin['icons']['2x'] ) ) {
					$single_plugin_icon_url = $single_plugin['icons']['2x'];
				} elseif ( ! empty( $single_plugin['icons']['1x'] ) ) {
					$single_plugin_icon_url = $single_plugin['icons']['1x'];
				} else {
					$single_plugin_icon_url = $single_plugin['icons']['default'];
				}

				/**
				 * Filters the install action links for a plugin.
				 *
				 * @since 2.7.0
				 *
				 * @param string[] $action_links An array of plugin action links. Defaults are links to Details and Install Now.
				 * @param array $single_plugin The plugin currently being listed.
				 */
				$action_links = apply_filters( 'plugin_install_action_links', $action_links, $single_plugin );

				$last_updated_timestamp = strtotime( $single_plugin['last_updated'] );
				?>
				<div class="plugin-card plugin-card-<?php echo esc_attr( $single_plugin['slug'] ); ?>">
					<?php
					if ( ! $compatible_php || ! $compatible_wp ) {
						echo '<div class="notice inline notice-error notice-alt"><p>';
						if ( ! $compatible_php && ! $compatible_wp ) {
							echo esc_html__( 'This plugin doesn&#8217;t work with your versions of WordPress and PHP.', 'darklup-lite' );
							if ( current_user_can( 'update_core' ) && current_user_can( 'update_php' ) ) {
								wp_sprintf(
								/* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
									' ' . wp_kses_post( '<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.' ),
									self_admin_url( 'update-core.php' ),
									esc_url( wp_get_update_php_url() )
								);
								wp_update_php_annotation( '</p><p><em>', '</em>' );
							} elseif ( current_user_can( 'update_core' ) ) {
								wp_sprintf(
								/* translators: %s: URL to WordPress Updates screen. */
									' ' . wp_kses_post( '<a href="%s">Please update WordPress</a>.' ),
									self_admin_url( 'update-core.php' )
								);
							} elseif ( current_user_can( 'update_php' ) ) {
								wp_sprintf(
								/* translators: %s: URL to Update PHP page. */
									' ' . wp_kses_post( '<a href="%s">Learn more about updating PHP</a>.' ),
									esc_url( wp_get_update_php_url() )
								);
								wp_update_php_annotation( '</p><p><em>', '</em>' );
							}
						} elseif ( ! $compatible_wp ) {
							esc_html__( 'This plugin doesn&#8217;t work with your version of WordPress.', 'darklup-lite' );
							if ( current_user_can( 'update_core' ) ) {
								wp_sprintf(
								/* translators: %s: URL to WordPress Updates screen. */
									' ' . wp_kses_post( '<a href="%s">Please update WordPress</a>.' ),
									self_admin_url( 'update-core.php' )
								);
							}
						} elseif ( ! $compatible_php ) {
							esc_html__( 'This plugin doesn&#8217;t work with your version of PHP.', 'darklup-lite' );
							if ( current_user_can( 'update_php' ) ) {
								wp_sprintf(
								/* translators: %s: URL to Update PHP page. */
									' ' . wp_kses_post( '<a href="%s">Learn more about updating PHP</a>.' ),
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
									<?php echo esc_html( $plugin_title ); ?>
									<img src="<?php echo esc_attr( $single_plugin_icon_url ); ?>" class="plugin-icon" alt="" />
								</a>
							</h3>
						</div>
						<div class="action-links">
							<?php
							if ( $action_links ) {
								echo '<ul class="plugin-action-buttons"><li>' . wp_kses_post( implode( '</li><li>', $action_links ) ) . '</li></ul>';
							}
							?>
						</div>
						<div class="desc column-description">
							<p><?php echo wp_kses_post( $description ); ?></p>
							<p class="authors"><?php echo wp_kses_post( $author ); ?></p>
						</div>
					</div>
					<div class="plugin-card-bottom">
						<div class="vers column-rating">
							<?php
							wp_star_rating(
								[
									'rating' => $single_plugin['rating'],
									'type'   => 'percent',
									'number' => $single_plugin['num_ratings'],
								]
							);
							?>
							<span class="num-ratings" aria-hidden="true">(<?php echo esc_html( $single_plugin['num_ratings'] ); ?>)</span>
						</div>
						<div class="column-updated">
							<strong><?php esc_html_e( 'Last Updated:', 'darklup-lite' ); ?></strong>
							<?php
							/* translators: %s: Human-readable time difference. */
							printf( esc_html__( '%s ago', 'darklup-lite' ), human_time_diff( $last_updated_timestamp ) );
							?>
						</div>
						<div class="column-downloaded">
							<?php
							if ( $single_plugin['active_installs'] >= 1000000 ) {
								$active_installs_millions = floor( $single_plugin['active_installs'] / 1000000 );
								$active_installs_text     = printf(
								/* translators: %s: Number of millions. */
									_nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'Active plugin installations', 'darklup-lite' ),
									number_format_i18n( $active_installs_millions )
								);
							} elseif ( 0 === intval( $single_plugin['active_installs'] ) ) {
								$active_installs_text = _x( 'Less Than 10', 'Active plugin installations', 'darklup-lite' );
							} else {
								$active_installs_text = number_format_i18n( $single_plugin['active_installs'] ) . '+';
							}
							/* translators: %s: Number of installations. */
							printf( esc_html__( '%s Active Installations', 'darklup-lite' ), $active_installs_text );
							?>
						</div>
						<div class="column-compatibility">
							<?php
							if ( ! $tested_wp ) {
								echo '<span class="compatibility-untested">' . wp_kses_post( 'Untested with your version of WordPress' ) . '</span>';
							} elseif ( ! $compatible_wp ) {
								echo '<span class="compatibility-incompatible">' . wp_kses_post( '<strong>Incompatible</strong> with your version of WordPress' ) . '</span>';
							} else {
								echo '<span class="compatibility-compatible">' . wp_kses_post( '<strong>Compatible</strong> with your version of WordPress' ) . '</span>';
							}
							?>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
	
</div>

