<?php
/**
 * Dashboard Page
 *
 * @package Timeline Event History
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Call Plugin API
if ( ! function_exists( 'plugins_api' ) ) {
	require_once ABSPATH . '/wp-admin/includes/plugin-install.php';
}

// Taking some data
$plugins_allowedtags = array(
	'a'		=> array(
		'href'		=> array(),
		'title'		=> array(),
		'target'	=> array(),
	),
	'abbr'		=> array( 'title' => array() ),
	'acronym'	=> array( 'title' => array() ),
	'code'		=> array(),
	'pre'		=> array(),
	'em'		=> array(),
	'strong'	=> array(),
	'ul'		=> array(),
	'ol'		=> array(),
	'li'		=> array(),
	'p'			=> array(),
	'br'		=> array(),
);

$plugins_data	= teh_get_plugin_data();
$plugins_filter = teh_plugins_filter();

// Check Plugin Install Permission
if( ! current_user_can('install_plugins') ) {
	echo '<div class="error">
			<p>'. esc_html__( "Sorry, It looks like that you do not have permission to install the plugin.", timeline_wp) .'</p>
			<p>'. esc_html__("You can take a look at our all plugins at", timeline_wp) .' <a href="https://profiles.wordpress.org/wpdiscover#content-plugins" target="_blank">'. esc_html__("here", timeline_wp) . '</a>.</p>
		 </div>';
	return;
}
?>
<script type="text/javascript">
	var pagenow = 'plugin-install';
</script>
<style type="text/css">
	.plugin-card{
		clear: none !important;
	}
</style>
<div class="wrap teh-settings">
	<div class="teh-dashboard-wrap">

		<?php if( ! empty( $plugins_data->plugins ) ) { ?>
		<form id="plugin-filter" method="post">
			<div class="teh-plugin-list-wrap">
				<div class="widefat teh-plugin-list teh-clearfix row" id="the-list">

					<?php foreach ($plugins_data->plugins as $plugin_key => $plugin_data) {

						if ( is_object( $plugin_data ) ) {
							$plugin_data = (array) $plugin_data;
						}

						// Taking some data
						$title					= wp_kses( $plugin_data['name'], $plugins_allowedtags );
						$version				= wp_kses( $plugin_data['version'], $plugins_allowedtags );
						$name					= strip_tags( $title . ' ' . $version );
						$description			= strip_tags( $plugin_data['short_description'] );
						$last_updated_timestamp = strtotime( $plugin_data['last_updated'] );
						$author					= wp_kses( $plugin_data['author'], $plugins_allowedtags );
						$author					= str_replace( "href=", 'target="_blank" href=', $author );
						$requires_php			= isset( $plugin['requires_php'] ) ? $plugin['requires_php'] : null;
						$requires_wp			= isset( $plugin_data['requires'] ) ? $plugin_data['requires'] : null;
						$compatible_php			= is_php_version_compatible( $requires_php );
						$compatible_wp			= is_wp_version_compatible( $requires_wp );
						$tested_wp      		= ( empty( $plugin_data['tested'] ) || version_compare( get_bloginfo( 'version' ), $plugin_data['tested'], '<=' ) );
						$details_link			= self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin_data['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );
						$extra_class			= ( ! empty( $plugins_filter[ $plugin_data['slug'] ]['class'] ) ) ? $plugins_filter[ $plugin_data['slug'] ]['class'] : '';
						$plugin_tags			= ( ! empty( $plugins_filter[ $plugin_data['slug'] ]['tags'] ) ) ? $plugins_filter[ $plugin_data['slug'] ]['tags'] : '';

						// Author String
						if ( ! empty( $author ) ) {
							/* translators: %s: Plugin author. */
							$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
						}

						// Plugin Icon
						if ( ! empty( $plugin_data['icons']['svg'] ) ) {
							$plugin_icon_url = $plugin_data['icons']['svg'];
						} elseif ( ! empty( $plugin_data['icons']['2x'] ) ) {
							$plugin_icon_url = $plugin_data['icons']['2x'];
						} elseif ( ! empty( $plugin_data['icons']['1x'] ) ) {
							$plugin_icon_url = $plugin_data['icons']['1x'];
						} else {
							$plugin_icon_url = $plugin_data['icons']['default'];
						}

						// Plugin Action Links
						$action_links = array();

						if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
							$status = install_plugin_install_status( $plugin_data );

							switch ( $status['status'] ) {
								case 'install':
									if ( $status['url'] ) {
										if ( $compatible_php && $compatible_wp ) {
											$action_links[] = sprintf(
												'<a class="install-now button" data-slug="%s" href="%s" aria-label="%s" data-name="%s">%s</a>',
												esc_attr( $plugin_data['slug'] ),
												esc_url( $status['url'] ),
												/* translators: %s: Plugin name and version. */
												esc_attr( sprintf( __( 'Install %s now' ), $name ) ),
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
												esc_attr( $plugin_data['slug'] ),
												esc_url( $status['url'] ),
												/* translators: %s: Plugin name and version. */
												esc_attr( sprintf( __( 'Update %s now' ), $name ) ),
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
										$button_text = __( 'Activate' );
										/* translators: %s: Plugin name. */
										$button_label = _x( 'Activate %s', 'plugin' );
										$activate_url = add_query_arg(
											array(
												'_wpnonce' => wp_create_nonce( 'activate-plugin_' . $status['file'] ),
												'action'   => 'activate',
												'plugin'   => $status['file'],
											),
											network_admin_url( 'plugins.php' )
										);

										if ( is_network_admin() ) {
											$button_text = __( 'Network Activate' );
											/* translators: %s: Plugin name. */
											$button_label = _x( 'Network Activate %s', 'plugin' );
											$activate_url = add_query_arg( array( 'networkwide' => 1 ), $activate_url );
										}

										$action_links[] = sprintf(
											'<a href="%1$s" class="button activate-now" aria-label="%2$s">%3$s</a>',
											esc_url( $activate_url ),
											esc_attr( sprintf( $button_label, $plugin_data['name'] ) ),
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

						$action_links[] = sprintf(
							'<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
							esc_url( $details_link ),
							/* translators: %s: Plugin name and version. */
							esc_attr( sprintf( __( 'More information about %s' ), $name ) ),
							esc_attr( $name ),
							__( 'More Details' )
						);
					?>

					<div class="teh-plugin-card-wrap <?php echo $extra_class; ?> col-md-6" data-tags="<?php echo esc_attr( $plugin_tags ); ?>">
						<div class="plugin-card plugin-card-<?php echo sanitize_html_class( $plugin_data['slug'] ); ?>">
							<div class="plugin-card-top">
								<div class="name column-name">
									<h3>
										<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox open-plugin-details-modal">
											<span class="teh-plugin-name"><?php echo $title; ?></span>
											<img src="<?php echo esc_url( $plugin_icon_url ); ?>" class="plugin-icon" alt="" />
										</a>
									</h3>
								</div>

								<div class="action-links">
									<?php
									if ( $action_links ) {
										echo '<ul class="plugin-action-buttons"><li>' . implode( '</li><li>', $action_links ) . '</li></ul>';
									}
									?>
								</div>

								<div class="desc column-description">
									<p><?php echo $description; ?></p>
									<p class="authors"><?php echo $author; ?></p>
								</div>
							</div><!-- end .plugin-card-top -->

							<div class="plugin-card-bottom">
								<div class="vers column-rating">
									<?php
									wp_star_rating(
										array(
											'rating' => $plugin_data['rating'],
											'type'   => 'percent',
											'number' => $plugin_data['num_ratings'],
										)
									);
									?>
									<span class="num-ratings" aria-hidden="true">(<?php echo number_format_i18n( $plugin_data['num_ratings'] ); ?>)</span>
								</div>

								<div class="column-updated">
									<strong><?php _e( 'Last Updated:' ); ?></strong>
									<?php
										/* translators: %s: Human-readable time difference. */
										printf( __( '%s ago' ), human_time_diff( $last_updated_timestamp ) );
									?>
								</div>

								<div class="column-downloaded">
									<?php
									if ( $plugin_data['active_installs'] >= 1000000 ) {
										$active_installs_millions = floor( $plugin_data['active_installs'] / 1000000 );
										$active_installs_text     = sprintf(
											/* translators: %s: Number of millions. */
											_nx( '%s+ Million', '%s+ Million', $active_installs_millions, 'Active plugin installations' ),
											number_format_i18n( $active_installs_millions )
										);
									} elseif ( 0 == $plugin_data['active_installs'] ) {
										$active_installs_text = _x( 'Less Than 10', 'Active plugin installations' );
									} else {
										$active_installs_text = number_format_i18n( $plugin_data['active_installs'] ) . '+';
									}
									/* translators: %s: Number of installations. */
									printf( __( '%s Active Installations' ), $active_installs_text );
									?>
								</div>

								<div class="column-compatibility">
									<?php
									if ( ! $tested_wp ) {
										echo '<span class="compatibility-untested">' . __( 'Untested with your version of WordPress' ) . '</span>';
									} elseif ( ! $compatible_wp ) {
										echo '<span class="compatibility-incompatible">' . __( '<strong>Incompatible</strong> with your version of WordPress' ) . '</span>';
									} else {
										echo '<span class="compatibility-compatible">' . __( '<strong>Compatible</strong> with your version of WordPress' ) . '</span>';
									}
									?>
								</div>
							</div><!-- end .plugin-card-bottom -->
						</div><!-- end .plugin-card -->
					</div><!-- end .teh-plugin-card-wrap -->

					<?php } ?>

				</div>
				
			</div><!-- end .teh-plugin-list-wrap -->			
		</form>
		<?php } else { ?>

				<div class="teh-no-result">
					<p><?php esc_html_e('Sorry, Something happened wrong.', 'espbw'); ?></p>
					<p><?php esc_html_e('You can take a look at our all plugins at', 'espbw'); ?> <a href="https://profiles.wordpress.org/wpdiscover#content-plugins" target="_blank"><?php esc_html_e('here', 'espbw'); ?></a>.</p>
				</div>

			<?php }
		?>
	</div>
</div><!-- end .wrap -->