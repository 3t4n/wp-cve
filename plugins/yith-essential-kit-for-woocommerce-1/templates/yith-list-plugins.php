<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package YITH Essential Kit for Woocommerce #1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! current_user_can( 'activate_plugins' ) ) {
	?>
	<div id="message" class="updated notice is-dismissible">
		<p><?php esc_attr_e( 'Sorry, you don\'t have sufficient permission to access to this page.', 'yith-essential-kit-for-woocommerce-1' ); ?></p>
	</div>
	<?php
	return;
}

// --- read module list -----------------------------
global $yith_jetpack_1;
global $pagenow;
$modules              = array();
$modules              = apply_filters( $this->_plugin_list_filter_module_name, $yith_jetpack_1->modules );
$active_modules       = array();
$module_inserted_list = array();

$count_all      = count( $modules );
$count_active   = count( $active_modules );
$count_inactive = $count_all - $count_active;

$plugin_filter_status = ! isset( $_GET['plugin_status'] ) ? 'all' : wp_unslash( $_GET['plugin_status'] );
$date_format          = __( 'M j, Y @ H:i', 'yith-essential-kit-for-woocommerce-1' );

// --------------------------------------------------
?>

<div class="wrap">
	<h1><?php echo $this->_menu_title; ?></h1>

	<p class="yith-essential-kit-intro-text"><?php esc_html_e( 'Here you can activate or deactive some of our plugins to enhance your e-commerce site.', 'yith-essential-kit-for-woocommerce-1' ); ?></p>
	<div class="yith-jetpack-message"><?php esc_html_e( 'Plugin enabled', 'yith-essential-kit-for-woocommerce-1' ); ?></div>
	<div class="wp-list-table widefat plugin-install-network yith-jetpack">

		<div id="the-list">
			<?php
			$new_data     = false;
			$modules_info = get_site_transient( 'yith_essential_kit_modules_info' );
			foreach ( $modules as $module ) :
				?>
				<?php
				$details_link = network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $module['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550' );

				if ( ! function_exists( 'plugins_api' ) ) {
					include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
				}
				if ( ! isset( $modules_info[ $module['slug'] ] ) ) {
					// transient is empty.
					$info_to_store = plugins_api(
						'plugin_information',
						array(
							'slug'   => $module['slug'],
							'fields' => array(
								'short_description',
								'active_installs',
								'rating',
								'downloaded',
							),
						)
					);
					if ( ! is_wp_error( $info_to_store ) ) {

						$module_info                     = array(
							'module_name'              => $info_to_store->name,
							'module_version'           => $info_to_store->version,
							'module_rating'            => $info_to_store->rating,
							'module_num_ratings'       => $info_to_store->num_ratings,
							'module_downloads'         => $info_to_store->downloaded,
							'module_updated'           => $info_to_store->last_updated,
							'module_homepage'          => $info_to_store->homepage,
							'module_short_description' => $info_to_store->short_description,
							'module_active_installs'   => $info_to_store->active_installs,
						);
						$modules_info[ $module['slug'] ] = $module_info;
						$new_data                        = true;
					} else {
						$module_info = array(
							'module_name'    => $module['name'],
							'module_version' => $module['version'],
						);
					}
				} else {
					// transient is ok.
					$module_info = $modules_info[ $module['slug'] ];
				}

				// set variables for each value of the array.
				extract( $module_info );

				$is_module_installed = $yith_jetpack_1->is_plugin_installed( $module['slug'] );
				$is_module_active    = $yith_jetpack_1->is_plugin_active( $module['slug'] );

				$is_premium_installed = $yith_jetpack_1->is_premium_installed( $module['slug'] );
				$is_premium_active    = $yith_jetpack_1->is_premium_active( $module['slug'] );
				$init                 = isset( $module['init'] ) ? '/' . $module['init'] : '/init.php';

				$active_module_version = $is_module_installed ? get_plugin_data( WP_PLUGIN_DIR . '/' . $module['slug'] . $init ) : false;

				/** ACTION LINKS */
				$more_links = array();

				$action_links = $yith_jetpack_1->print_action_buttons( $module['slug'], $is_module_active );

				/**
				 * MORE DETAILS LINK
				 */
				$details_link = network_admin_url(
					'plugin-install.php?tab=plugin-information&amp;plugin=' . $module['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550'
				);

				$more_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf( esc_html__( 'More information about %s', 'yith-essential-kit-for-woocommerce-1' ), $module_name ) ) . '" data-title="' . esc_attr( $module_name ) . '">' . esc_html__( 'More Details', 'yith-essential-kit-for-woocommerce-1' ) . '</a>';

				/**
				 * ICONS
				 */
				$is_new         = isset( $module['new'] ) ? $module['new'] : false;
				$is_recommended = isset( $module['recommended'] ) ? $module['recommended'] : false;

				/**
				 * BUY/ACTIVATE PREMIUM MODULE
				 */
				if ( $is_premium_active ) {
					$premium_url = '#';
					$btn_class   = 'btn-premium installed';
					$btn_title   = esc_html__( 'Premium Activated', 'yith-essential-kit-for-woocommerce-1' );
					$new_tab     = '';
				} elseif ( $is_premium_installed ) {
					$premium_dir = isset( $module['premium-dir'] ) ? $module['premium-dir'] : $module['slug'] . '-premium';
					$premium_url = wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'activate',
								'plugin' => $premium_dir . $init,
							),
							admin_url( 'plugins.php' )
						),
						'activate-plugin_' . $premium_dir . $init
					);
					$btn_class   = 'btn-premium toactive';
					$btn_title   = esc_html__( 'Activate Premium', 'yith-essential-kit-for-woocommerce-1' );
					$new_tab     = '';
				} else {
					$premium_url = 'https://yithemes.com/themes/plugins/' . ( isset( $module['premium-slug'] ) ? $module['premium-slug'] : $module['slug'] );
					$btn_class   = 'btn-premium tobuy';
					$btn_title   = esc_html__( 'Buy Premium Version', 'yith-essential-kit-for-woocommerce-1' );
					$new_tab     = 'target = "_blank"';
				}

				$more_links[] = '<a class="' . $btn_class . '" ' . $new_tab . ' href="' . $premium_url . '" data-title="' . esc_attr( $module_name ) . '">' . $btn_title . '</a>';

				?>
				<div class="plugin-card <?php echo esc_attr( $module['slug'] ); ?>">
					<div class="plugin-card-top">
						<?php if ( $is_new ) : ?>
							<span class="product-icon"><img
										src="<?php echo esc_url( YJP_ASSETS_URL ) . '/images/badge-new.png'; ?>"
										alt="New Icon"></span>
						<?php elseif ( $is_recommended ) : ?>
							<span class="product-icon"><img
										src="<?php echo esc_url( YJP_ASSETS_URL ) . '/images/badge-recommended.png'; ?>"
										alt="New Icon"></span>
						<?php endif ?>
						<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox">
							<img src="https://ps.w.org/<?php echo esc_attr( $module['slug'] ); ?>/assets/icon-128x128.jpg?ver=latest" class="plugin-icon" alt="">
						</a>

						<div class="name column-name">
							<h3>
								<a class="thickbox" href="<?php echo esc_url( $details_link ); ?>">
									<?php echo esc_html( $module_name ); ?>

								</a>
							</h3>
							<span>
									<?php
									echo esc_attr( isset( $active_module_version['Version'] ) ? $active_module_version['Version'] : $module_version );
									if ( isset( $active_module_version['Version'] ) && ( $module_version != $active_module_version['Version'] ) ) {
										echo ' ' . esc_html__( sprintf( '(Version %s available)', $module_version ), 'yith-essential-kit-for-woocommerce-1' );
									}
									?>
								</span>
						</div>
						<div class="action-links">
							<?php
							if ( $action_links ) {
								echo '<div class="plugin-action-buttons">' . $action_links . '</div>';
							}
							?>

							<?php
							if ( $more_links ) {
								echo '<ul class="plugin-action-buttons"><li>' . implode( '</li><li>', $more_links ) . '</li></ul>';
							}
							?>
						</div>
						<div class="desc column-description">
							<p><?php echo esc_html( $module_short_description ); ?></p>
							<p class="authors"><cite>By <a href="https://yithemes.com" target="_blank" title="plugin author YITH">YITH</a></cite></p>
						</div>
					</div>

				</div>
				<?php
			endforeach;
			if ( $new_data ) {
				set_site_transient( 'yith_essential_kit_modules_info', $modules_info, 12 * HOUR_IN_SECONDS );
			}
			?>
		</div>
	</div>
</div>
