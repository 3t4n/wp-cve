<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPXtension_Menu' ) ) {
	class WPXtension_Menu {

		protected static $_instance = null;

		public static function instance() {
	        if ( is_null( self::$_instance ) ) {
	            self::$_instance = new self();
	        }

	        return self::$_instance;
	    }

	    protected static $_plugins = array(
			'variation-price-display'             => array(
				'name' => 'Variation Price Display Range for WooCommerce',
				'slug' => 'variation-price-display',
				'file' => 'variation-price-display.php'
			),
			'product-variant-table-for-woocommerce'         => array(
				'name' => 'Product Variation Table for WooCommerce',
				'slug' => 'product-variant-table-for-woocommerce',
				'file' => 'product-variant-table-for-woocommerce.php'
			),
			'fast-cart'            => array(
				'name' => 'Fast Cart for WooCommerce',
				'slug' => 'fast-cart',
				'file' => 'fast-cart.php'
			),
			'product-share'            => array(
				'name' => 'Product Share for WooCommerce',
				'slug' => 'product-share',
				'file' => 'product-share.php'
			),
		);

		function __construct() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_menu', array( $this, 'useful_plugins_menu' ), 100 );
		}

		function admin_menu() {

			$wpx_icon = 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgZmlsbD0iY3VycmVudENvbG9yIiBjbGFzcz0iYmkgYmktcGx1Zy1maWxsIiB2aWV3Qm94PSIwIDAgMTYgMTYiPgogIDxwYXRoIGQ9Ik02IDBhLjUuNSAwIDAgMSAuNS41VjNoM1YuNWEuNS41IDAgMCAxIDEgMFYzaDFhLjUuNSAwIDAgMSAuNS41djNBMy41IDMuNSAwIDAgMSA4LjUgMTBjLS4wMDIuNDM0LS4wMS44NDUtLjA0IDEuMjItLjA0MS41MTQtLjEyNiAxLjAwMy0uMzE3IDEuNDI0YTIuMDgzIDIuMDgzIDAgMCAxLS45NyAxLjAyOEM2LjcyNSAxMy45IDYuMTY5IDE0IDUuNSAxNGMtLjk5OCAwLTEuNjEuMzMtMS45NzQuNzE4QTEuOTIyIDEuOTIyIDAgMCAwIDMgMTZIMmMwLS42MTYuMjMyLTEuMzY3Ljc5Ny0xLjk2OEMzLjM3NCAxMy40MiA0LjI2MSAxMyA1LjUgMTNjLjU4MSAwIC45NjItLjA4OCAxLjIxOC0uMjE5LjI0MS0uMTIzLjQtLjMuNTE0LS41NS4xMjEtLjI2Ni4xOTMtLjYyMS4yMy0xLjA5LjAyNy0uMzQuMDM1LS43MTguMDM3LTEuMTQxQTMuNSAzLjUgMCAwIDEgNCA2LjV2LTNhLjUuNSAwIDAgMSAuNS0uNWgxVi41QS41LjUgMCAwIDEgNiAweiIvPgo8L3N2Zz4=';

			$hook = add_menu_page(
				'WPXtension',
				'WPXtension',
				'manage_options',
				'wpxtension',
				array( $this, 'welcome_content' ),
				$wpx_icon,
				26
			);
			add_submenu_page( 'wpxtension', 'WPX About', 'Useful Plugins', 'manage_options', 'wpxtension' );
			// remove the "main" submenue page
		    remove_submenu_page('wpxtension', 'wpxtension');
		    // tell `_wp_menu_output` not to use the submenu item as its link
		    add_filter("submenu_as_parent_{$hook}", '__return_false');
		}

		function useful_plugins_menu() {

			$search = array(
                's'    	=> 'wpxteam',
                'tab'   => 'search',
                'type'	=> 'author'
            );

            $permalink = add_query_arg( $search, admin_url( 'plugin-install.php' ) );

			add_submenu_page( 'wpxtension', 'Useful Plugins', 'Useful Plugins', 'manage_options', $permalink );
		}

		function welcome_content() {
			?>
            <div class="wpxtension_come wpxtension_comcome_page wrap">
                <h1>Awesome useful plugins by <b>WPXtension</b>.</h1>
                
                <div class="wpxtension_page wpxtension_useful_plugin_page wrap">
                	<div class="wp-list-table widefat plugin-install-network">
						<?php
						if ( ! function_exists( 'plugins_api' ) ) {
							include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
						}

						if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'activate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'activate-plugin_' . $_GET['plugin'] ) ) {
							activate_plugin( $_GET['plugin'], '', false, true );
						}

						if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'deactivate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'deactivate-plugin_' . $_GET['plugin'] ) ) {
							deactivate_plugins( $_GET['plugin'], '', false, true );
						}

						$updated      = false;
						$plugins_info = get_site_transient( 'wpxtension_plugins_info' );

						foreach ( self::$_plugins as $_plugin ) {
							if ( ! isset( $plugins_info[ $_plugin['slug'] ] ) ) {
								$_plugin_info = plugins_api(
									'plugin_information',
									array(
										'slug'   => $_plugin['slug'],
										'fields' => array(
											'short_description',
											'version',
											'active_installs',
											'downloaded',
											'icons'
										),
									)
								);

								if ( ! is_wp_error( $_plugin_info ) ) {
									$plugin_info                      = array(
										'name'              => $_plugin_info->name,
										'slug'              => $_plugin_info->slug,
										'version'           => $_plugin_info->version,
										'rating'            => $_plugin_info->rating,
										'num_ratings'       => $_plugin_info->num_ratings,
										'downloads'         => $_plugin_info->downloaded,
										'last_updated'      => $_plugin_info->last_updated,
										'homepage'          => $_plugin_info->homepage,
										'short_description' => $_plugin_info->short_description,
										'active_installs'   => $_plugin_info->active_installs,
										'icons'   			=> $_plugin_info->icons,
									);
									$plugins_info[ $_plugin['slug'] ] = $plugin_info;
									$updated                          = true;
								} else {
									$plugin_info = array(
										'name' => $_plugin['name'],
										'slug' => $_plugin['slug']
									);
								}
							} else {
								$plugin_info = $plugins_info[ $_plugin['slug'] ];
							}

							$details_link = network_admin_url(
								'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin_info['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550'
							);
							// print_r($plugin_info['icons']);
							?>
	                        <div class="plugin-card <?php echo esc_attr( $_plugin['slug'] ); ?>"
	                             id="<?php echo esc_attr( $_plugin['slug'] ); ?>">
	                            <div class="plugin-card-top">
	                                <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox">
	                                    <img src="<?php echo esc_url( $plugin_info['icons']['1x'] ); ?>"
	                                         class="plugin-icon" alt=""/>
	                                </a>
	                                <div class="name column-name">
	                                    <h3>
	                                        <a class="thickbox" href="<?php echo esc_url( $details_link ); ?>">
												<?php echo esc_html( $plugin_info['name'] ); ?>
	                                        </a>
	                                    </h3>
	                                </div>
	                                <div class="action-links">
	                                    <ul class="plugin-action-buttons">
	                                        <li>
												<?php if ( $this->is_plugin_installed( $_plugin ) ) {
													if ( $this->is_plugin_active( $_plugin ) ) {
														?>
	                                                    <a href="<?php echo esc_url( $this->deactivate_plugin_link( $_plugin ) ); ?>"
	                                                       class="button deactivate-now">
															<?php esc_html_e( 'Deactivate', 'wpxtension' ); ?>
	                                                    </a>
														<?php
													} else {
														?>
	                                                    <a href="<?php echo esc_url( $this->activate_plugin_link( $_plugin ) ); ?>"
	                                                       class="button activate-now">
															<?php esc_html_e( 'Activate', 'wpxtension' ); ?>
	                                                    </a>
														<?php
													}
												} else { ?>
	                                                <a href="<?php echo esc_url( $this->install_plugin_link( $_plugin ) ); ?>"
	                                                   class="install-now button wpxtension-install-now">
														<?php esc_html_e( 'Install Now', 'wpxtension' ); ?>
	                                                </a>
												<?php } ?>
	                                        </li>
	                                        <li>
	                                            <a href="<?php echo esc_url( $details_link ); ?>"
	                                               class="thickbox open-plugin-details-modal"
	                                               aria-label="<?php echo esc_attr( sprintf( esc_html__( 'More information about %s', 'wpxtension' ), $plugin_info['name'] ) ); ?>"
	                                               data-title="<?php echo esc_attr( $plugin_info['name'] ); ?>">
													<?php esc_html_e( 'More Details', 'wpxtension' ); ?>
	                                            </a>
	                                        </li>
	                                    </ul>
	                                </div>
	                                <div class="desc column-description">
	                                    <p><?php echo esc_html( $plugin_info['short_description'] ); ?></p>
	                                </div>
	                            </div>
								<?php if ( $this->is_plugin_installed( $_plugin, true ) ) {
									?>
	                                <div class="plugin-card-bottom premium">
	                                    <div class="text">
	                                        <strong>✓ Premium version was installed.</strong><br/>
	                                        Please deactivate the free version when using the premium version.
	                                    </div>
	                                    <div class="btn">
											<?php
											if ( $this->is_plugin_active( $_plugin, true ) ) {
												?>
	                                            <a href="<?php echo esc_url( $this->deactivate_plugin_link( $_plugin, true ) ); ?>"
	                                               class="button deactivate-now">
													<?php esc_html_e( 'Deactivate', 'wpxtension' ); ?>
	                                            </a>
												<?php
											} else {
												?>
	                                            <a href="<?php echo esc_url( $this->activate_plugin_link( $_plugin, true ) ); ?>"
	                                               class="button activate-now">
													<?php esc_html_e( 'Activate', 'wpxtension' ); ?>
	                                            </a>
												<?php
											}
											?>
	                                    </div>
	                                </div>
									<?php
								} else {
									echo '<div class="plugin-card-bottom">';

									if ( isset( $plugin_info['rating'], $plugin_info['num_ratings'] ) ) { ?>
	                                    <div class="vers column-rating">
											<?php
											wp_star_rating(
												array(
													'rating' => $plugin_info['rating'],
													'type'   => 'percent',
													'number' => $plugin_info['num_ratings'],
												)
											);
											?>
	                                        <span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin_info['num_ratings'] ) ); ?>)</span>
	                                    </div>
									<?php }

									if ( isset( $plugin_info['version'] ) ) { ?>
	                                    <div class="column-updated">
											<?php echo esc_html__( 'Version', 'wpxtension' ) . ' ' . $plugin_info['version']; ?>
	                                    </div>
									<?php }

									if ( isset( $plugin_info['active_installs'] ) ) { ?>
	                                    <div class="column-downloaded">
											<?php echo number_format_i18n( $plugin_info['active_installs'] ) . esc_html__( '+ Active Installations', 'wpxtension' ); ?>
	                                    </div>
									<?php }

									if ( isset( $plugin_info['last_updated'] ) ) { ?>
	                                    <div class="column-compatibility">
	                                        <strong><?php esc_html_e( 'Last Updated:', 'wpxtension' ); ?></strong>
	                                        <span><?php printf( esc_html__( '%s ago', 'wpxtension' ), esc_html( human_time_diff( strtotime( $plugin_info['last_updated'] ) ) ) ); ?></span>
	                                    </div>
									<?php }

									echo '</div>';
								} ?>
	                        </div>
							<?php
						}

						if ( $updated ) {
							set_site_transient( 'wpxtension_comgins_info', $plugins_info, 24 * HOUR_IN_SECONDS );
						}
						?>
	                </div>
            	</div>

            </div>
			<?php
		}

		public function is_plugin_installed( $plugin, $premium = false ) {
			if ( $premium ) {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function is_plugin_active( $plugin, $premium = false ) {
			if ( $premium ) {
				return is_plugin_active( $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return is_plugin_active( $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function install_plugin_link( $plugin ) {
			return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin['slug'] ), 'install-plugin_' . $plugin['slug'] );
		}

		public function activate_plugin_link( $plugin, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpxtension&action=activate&plugin=' . $plugin['slug'] . '-premium/' . $plugin['file'] . '#' . $plugin['slug'] ), 'activate-plugin_' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpxtension&action=activate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'activate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}

		public function deactivate_plugin_link( $plugin, $premium = false ) {
			if ( $premium ) {
				return wp_nonce_url( admin_url( 'admin.php?page=wpxtension&action=deactivate&plugin=' . $plugin['slug'] . '-premium/' . $plugin['file'] . '#' . $plugin['slug'] ), 'deactivate-plugin_' . $plugin['slug'] . '-premium/' . $plugin['file'] );
			} else {
				return wp_nonce_url( admin_url( 'admin.php?page=wpxtension&action=deactivate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'deactivate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
			}
		}
	}
}