<?php
/**
 * Admin Settings class
 *
 * @since   1.1.0
 * @package Hootkit
 */

namespace HootKit\Admin;
use \HootKit\Inc\Helper_Assets;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( '\HootKit\Admin\Settings' ) ) :

	class Settings {

		/**
		 * Class Instance
		 */
		private static $instance;

		/**
		 * Setup Admin Settings
		 */
		public function __construct() {

			// Add action links on Plugin Page
			add_action( 'plugin_action_links_' . hootkit()->plugin_basename, array( $this, 'plugin_action_links' ), 10, 4 );

			// Add settings page
			add_action( 'admin_menu', array( $this, 'add_page' ), 5 );

			// Load settings page assets
			Helper_Assets::add_adminasset(
				'adminsettings',                            // slug
				array( 'settings_page_' . hootkit()->slug ) // screen hooks
			);
			add_action( 'admin_enqueue_scripts', array( $this, 'localize_script' ), 11 );

			// Add ajax callback
			add_action( 'wp_ajax_hootkitsettings', array( $this, 'admin_ajax_settings_handler' ) );

		}

		/**
		 * Add action links
		 * @param string[] $actions     An array of plugin action links. By default this can include 'activate',
		 *                              'deactivate', and 'delete'. With Multisite active this can also include
		 *                              'network_active' and 'network_only' items.
		 * @param string   $plugin_file Path to the plugin file relative to the plugins directory.
		 * @param array    $plugin_data An array of plugin data. See `get_plugin_data()`.
		 * @param string   $context     The plugin context. By default this can include 'all', 'active', 'inactive',
		 *                              'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			$actions['manage'] = '<a href="' . admin_url('options-general.php?page=' . hootkit()->slug ) . '">' . __( 'Settings', 'hootkit' ) . '</a>'; // options-general.php
			return $actions;
		}

		/**
		 * Add Settings Page
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function add_page(){
			add_submenu_page( //# add_menu_page
				'options-general.php', //#
				__( 'HootKit Modules Settings', 'hootkit' ),
				__( 'HootKit', 'hootkit' ),
				'manage_options',
				hootkit()->slug,
				array( $this, 'render_admin' ) //# , ''
			);
		}

		/**
		 * Pass script data
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function localize_script() {
			wp_localize_script(
				hootkit()->slug . '-adminsettings',
				'hootkitSettingsData',
				array(
					'strings' => array(
						'success' => __( 'Settings Saved', 'hootkit' ),
						'error'   => __( 'Some Error Occurred', 'hootkit' )
					),
					'ajaxurl' => wp_nonce_url( admin_url('admin-ajax.php?action=hootkitsettings'), 'hootkitadmin-settings-nonce' )
				)
			);
		}

		/**
		 * Ajax handler for handling settings
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function admin_ajax_settings_handler() {
			// Check nonce and permissions
			if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'hootkitadmin-settings-nonce' ) ) {
				wp_send_json( array( 'setactivemods' => false, 'msg' => __( 'Invalid request.', 'hootkit' ) ) );
				exit;
			}
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json( array( 'setactivemods' => false, 'msg' => __( 'Insufficient permissions.', 'hootkit' ) ) );
				exit;
			}

			// Set Handle and Response
			$handle = ( !empty( $_POST['handle'] ) ) ? $_POST['handle'] : '';
			$response = array();

			// Handle Set active Mods request
			if ( $handle == 'setactivemods' ) {
				$values = $store = array();
				parse_str( $_POST['values'], $values );
				$values = wp_parse_args( $values, array(
					'widget' => array(),
					'block' => array(),
					'misc' => array(),
					'disabled' => array(),
				) );
				foreach ( array( 'widget', 'block', 'misc' ) as $type ) {
					foreach ( hootkit()->get_config( 'modules', $type ) as $check ) {
						$store[ $type ][ $check ] = ( \in_array( $check, $values[ $type ] ) ) ? 'yes' : 'no';
					}
				}
				$store['disabled'] = $values['disabled'];
				update_option( 'hootkit-activemods', $store );
				$response['setactivemods'] = true;
			}

			// Send response.
			wp_send_json( $response );
			exit;
		}

		/**
		 * Render Page
		 *
		 * @since  1.1.0
		 * @access public
		 * @return void
		 */
		public function render_admin(){

			$modules = hootkit()->get_mods('modules');
			$premium = hootkit()->get_config( 'premium' );
			$disabled = hootkit()->get_config( 'disabledmodtypes' );
			$supportscgen = apply_filters( 'hootkit_show_scgen', false );

			$thememods = hootkit()->get_config( 'modules' );
			$activemods = hootkit()->get_config( 'activemods' );
			$premiummods = array();
			foreach ( $premium as $pmod ) {
				if ( !empty( $modules[ $pmod ]['types'] ) )
					foreach ( $modules[ $pmod ]['types'] as $type ) {
						$premiummods[ $type ][] = $pmod;
					}
			}
			$wcinactivemods = hootkit()->get_config( 'wc-inactive' );
			// $wc = class_exists( 'WooCommerce' );

			$currentscreen = ( !empty( $_GET['view'] ) ) ? $_GET['view'] : 'undefined';
			$validscreens = array();
			foreach ( $thememods as $check => $checkarray )
				if ( !empty( $checkarray ) ) $validscreens[] = $check;
			if ( !empty( $supportscgen ) ) $validscreens[] = 'scgen';
			if ( !\in_array( $currentscreen, $validscreens ) ) $currentscreen = ( !empty( $validscreens[0] ) ) ? $validscreens[0] : 'undefined';

			$skip = true;
			foreach( $thememods as $modtype => $modsarray )
				if ( !empty( $modsarray ) ) $skip = false;
			if ( !empty( $supportscgen ) ) $skip = false;

			?>
			<div class="hootkit-wrap wrap">

				<div class="hootkit-header">
					<div class="hk-gridbox">
						<h4><?php printf( esc_html__( 'Version: %1$s', 'hootkit' ), hootkit()->version ); ?></h4>
						<h3><?php esc_html_e( 'HootKit Settings', 'hootkit' ); ?></h3>
					</div>
				</div><!-- .hootkit-header -->

				<div class="hootkit-subheader">
					<div class="hk-gridbox"><h1></h1>
						<div class="hootkit-nav"><?php
							if ( $skip ) :
								esc_html_e( 'Nothing to show here!', 'hootkit' );
							else:
								foreach ( $thememods as $modtype => $modsarray ) {
									if ( !empty( $modsarray ) ) {
										echo '<a';
											echo ' class="hk-navitem';
												if ( $currentscreen == 'scgen' ) echo ' reload-href';
												if ( $currentscreen == $modtype ) echo ' hk-currentnav';
												echo '"';
											echo ' data-view="' . esc_attr( $modtype ) . '"';
											echo ' href="' . admin_url('options-general.php?page=' . hootkit()->slug . '&view=' . esc_attr( $modtype ) ) . '"';
										echo '>' . hootkit()->get_string( 'setting-' . $modtype ) . '</a>';
									}
								}
								if ( $supportscgen ) {
									echo '<a';
										echo ' class="hk-navitem';
											if ( $currentscreen == 'scgen' ) echo ' hk-currentnav';
											else echo ' reload-href';
											echo '"';
										echo ' href="' . admin_url('options-general.php?page=' . hootkit()->slug . '&view=scgen' ) . '"';
									echo '>' . hootkit()->get_string( 'setting-scgen' ) . '</a>';
								};
							endif;
						?></div><!-- .hootkit-nav -->
					</div>
				</div><!-- .hootkit-subheader -->

				<?php if ( !$skip ) : ?>
				<form id="hootkit-settings" class="hootkit-settings">
					<div id="hootkit-container" class="hootkit-container hk-gridbox"><?php

						foreach ( $thememods as $modtype => $modsarray ) {
							if ( !empty( $modsarray ) ) {

								/**
								 * If modtype disabled, activemods would have been set to empty.
								 * User enables them on screen now => all display turned off.
								 * Instead they should show all on by default || or use values stored in db
								 */
								if ( \in_array( $modtype, $disabled ) ) {
									// $activemods[$modtype] = $modsarray;
									$dbvalue = get_option( 'hootkit-activemods', false );
									if ( \is_array( $dbvalue ) && !empty( $dbvalue[$modtype] ) ) {
										$activemods[$modtype] = array();
										foreach ( $dbvalue[$modtype] as $check => $active ) {
											if ( $active == 'yes' ) $activemods[$modtype][] = $check;
										}
									} else $activemods[$modtype] =  $modsarray; // This condition should never occur!
								}
								?>

								<div id="hk-<?php echo $modtype ?>" class="hk-box<?php
									echo ' hk-' . $modtype;
									if ( $modtype == $currentscreen ) echo ' hk-box-current';
									if ( \in_array( $modtype, $disabled ) ) echo ' hk-box-disabled';
									?>">

									<?php
									// if ( $modtype == 'widget' || $modtype == 'block' )
										echo '<div class="hk-box-notice">' . sprintf( __( 'Enable/Disable HootKit %s throughout the site.', 'hootkit' ), hootkit()->get_string( 'setting-' . $modtype ) ) . '</div>';
									?>

									<div class="hk-box-inner">

										<?php /* Box Navigation */ ?>
										<div class="hk-box-nav">
											<div class="hk-boxnav-title"><?php echo hootkit()->get_string( 'setting-' . $modtype ); ?></div>
											<div class="hk-modtype-toggle">
												<span class="hk-modtype-enable"><?php _e( 'Enable', 'hootkit' ) ?></span> | 
												<span class="hk-modtype-disable"><?php _e( 'Disable', 'hootkit' ) ?></span>
												<input name="disabled[]" type="checkbox" value="<?php echo esc_attr( $modtype ) ?>" <?php if ( \in_array( $modtype, $disabled ) ) echo 'checked="checked"'; ?> />
											</div>
											<?php
											$displaysets = array();
											foreach ( $modsarray as $amod )
												if ( !empty( $modules[ $amod ]['displaysets'] ) )
													$displaysets = array_merge( $displaysets, $modules[ $amod ]['displaysets'] );
											$displaysets = array_unique( $displaysets );
											sort( $displaysets );
											if ( count( $displaysets ) > 1 ) : ?>
												<div class="hk-boxnav-filters">
													<div class="hk-boxnav-filter hk-currentfilter" data-displayset="all"><?php _e( 'View All', 'hootkit' ) ?></div>
													<?php foreach ( $displaysets as $filter ) {
														echo '<div class="hk-boxnav-filter" data-displayset="' . esc_attr( $filter ) . '">' . ucwords( $filter ) . '</div>';
													} ?>
												</div>
											<?php endif; ?>
										</div>

										<?php /* Box Modules */ ?>
										<div class="hk-box-modules">
											<div class="hk-modules-disabled"><?php esc_html_e( "Click 'Enable' on left to show available options.", 'hootkit' ); ?></div>
											<div class="hk-modules">

												<?php foreach ( $modsarray as $modslug ) { ?>
													<div class="hk-module<?php
															echo ' hk-mod-' . esc_attr( $modslug );
															if ( !empty( $modules[ $modslug ]['requires'] ) && \in_array( 'woocommerce', $modules[ $modslug ]['requires'] ) ) echo ' hk-wcmod';
															if ( !empty( $modules[ $modslug ]['displaysets'] ) )
																foreach ( $modules[ $modslug ]['displaysets'] as $dset )
																	echo ' hk-set-' . esc_attr( $dset );
															?> hk-set-all">
														<div class="hk-mod-name"><?php
															echo '<span>' . hootkit()->get_string( $modslug ) . '</span>';
															if ( !empty( $modules[ $modslug ]['desc'] ) ) :
																?><div class="hk-mod-descbox">
																	<div class="hk-mod-descicon"></div>
																	<div class="hk-mod-desc"><?php esc_html_e( $modules[ $modslug ]['desc'] ) ?></div>
																</div><?php
															endif;
															?></div>
														<div class="hk-toggle-box">
															<input name="<?php echo esc_attr( $modtype ) . '[]'; ?>" type="checkbox" value="<?php echo esc_attr( $modslug ) ?>" <?php if ( \in_array( $modslug, $activemods[ $modtype ] ) ) echo 'checked="checked"'; ?> />
															<span class="hk-toggle"></span>
														</div>
													</div><!-- .hk-module -->
												<?php } ?>

												<?php foreach ( array( 'wcinactivemods', 'premiummods' ) as $inactive ) {
													$checkinactive = $$inactive;
													if ( !empty( $checkinactive[ $modtype ] ) ) { foreach ( $checkinactive[ $modtype ] as $modslug ) { ?>
													<div class="hk-module hk-mod-inactive<?php
															echo ' hk-mod-' . esc_attr( $modslug );
															if ( $inactive == 'wcinactivemods' ) echo ' hk-wcmod';
															elseif ( !empty( $modules[ $modslug ]['requires'] ) && \in_array( 'woocommerce', $modules[ $modslug ]['requires'] ) ) echo ' hk-wcmod';
															if ( !empty( $modules[ $modslug ]['displaysets'] ) )
																foreach ( $modules[ $modslug ]['displaysets'] as $dset )
																	echo ' hk-set-' . esc_attr( $dset );
															?>  hk-set-all">
														<div class="hk-modhover-msg"><?php
															if ( $inactive == 'wcinactivemods' ) esc_html_e( 'This module requires WooCommerce - for Online Shops', 'hootkit' );
															if ( $inactive == 'premiummods' ) esc_html_e( 'Premium Theme Feature', 'hootkit' );
															?></div>
														<div class="hk-mod-name"><?php
															echo '<span>' . hootkit()->get_string( $modslug ) . '</span>';
															if ( !empty( $modules[ $modslug ]['desc'] ) ) :
																?><div class="hk-mod-descbox">
																	<div class="hk-mod-descicon"></div>
																</div><?php
																endif;
															?></div>
														<div class="hk-toggle-box hk-toggle-box-inactive"><span class="hk-toggle"></span></div>
													</div><!-- .hk-module -->
													<?php
													} }
												} ?>

											</div><!-- .hk-modules -->
										</div><!-- .hk-box-modules -->

									</div><!-- .hk-box-inner -->
								</div><!-- .hk-box --><?php

							} // endif
						} //endforeach
						?>

					</div><!-- .hootkit-container -->

					<?php if ( $currentscreen != 'scgen' ) : ?>
						<div class="hk-actions">
							<div class="hk-gridbox">
								<div class="hk-save">
									<div id="hkfeedback" class="hkfeedback"></div>
									<a href="#" id="hk-submit" class="button button-primary hk-submit"><?php _e( 'Save Changes', 'hootkit' ); ?></a>
									<?php // submit_button( __( 'Save', 'hootkit' ) ); ?>
								</div>
							</div>
						</div>
					<?php endif; ?>

				</form>
				<?php endif; ?>
			
			</div><!-- .hootkit-wrap -->

			<?php
		}

		/**
		 * Returns the instance
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

	}

	Settings::get_instance();

endif;