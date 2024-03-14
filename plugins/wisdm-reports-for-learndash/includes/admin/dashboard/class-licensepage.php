<?php

namespace WRLDAdmin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'LicensePage' ) ) {

	/**
	 * Class for showing tabs of WRLD.
	 */
	class LicensePage {

		public function __construct() {
			if (is_rtl()) {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.rtl.css', array(), WRLD_PLUGIN_VERSION );
			} else {
				wp_enqueue_style( 'wrld_admin_dashboard_contentainer_style', WRLD_REPORTS_SITE_URL . '/includes/admin/dashboard/assets/css/content-page.css', array(), WRLD_PLUGIN_VERSION );
			}
		}

		public static function render() {
			?>
			<div class='wrld-dashboard-page-container'>
				<?php
				self::content_main();
				self::content_sidebar();
				?>
			</div>
			<?php
		}

		public static function content_main() {
			?>
				<div class='wrld-dashboard-page-content licensing'>
					<div>
						<span class='wrld-dashboard-text license'> <?php esc_html_e( 'Enter and activate your License Key', 'learndash-reports-by-wisdmlabs' ); ?> </span>
						<br/><br/>
						<span class='wrld-dashboard-text'> <?php esc_html_e( 'Activating the License Key is essential to receive regular plugin updates as well as support from WisdmLabs.', 'learndash-reports-by-wisdmlabs' ); ?> </span>
						<br/>
						<?php
							self::get_licensing_section();
						?>
					</div>
				</div>
			<?php
		}

		public static function content_sidebar() {
			?>
				<div class='wrld-dashboard-page-sidebar licensing'>
					<?php self::sidebar_block_help(); ?>
				</div>
			<?php
		}

		public static function sidebar_block_help() {
			?>
			<div class='wrld-sidebar-block licensing'>
					<div class='wrld-sidebar-block-head'>
						<div class='wrld-sidebar-head-icon'>
							<span class='help-icon'></span>
						</div>
						<div class='wrld-sidebar-head-text'>
							<span><?php esc_html_e( 'Looking for help?', 'learndash-reports-by-wisdmlabs' ); ?></span>
						</div>
					</div>
					<div class='wrld-sidebar-body licensing'>
						<ul>
						<li><a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/where-to-find-the-license-key/" target='__blank'><?php esc_html_e( 'Where to find my license key?', 'learndash-reports-by-wisdmlabs' ); ?></a></li>	
							<li><a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/lr-getting-started/plugin-overview-installation-updates-4/" target='__blank'><?php esc_html_e( 'Plugin Installation & Activation', 'learndash-reports-by-wisdmlabs' ); ?></a></li>
							<li><?php esc_html_e( 'Talk to us: ', 'learndash-reports-by-wisdmlabs' ); ?><a href="mailto:helpdesk@wisdmlabs.com" target='__blank'>helpdesk@wisdmlabs.com</a></li>
						</ul>
					</div>
				</div>
			<?php
		}

		public static function get_add_menu_link_setting() {
			?>
				<label for='wrld-menu-config-setting'>
					<input type="checkbox" name="wrld-menu-config-setting" id="wrld-menu-config-setting">
					<span><?php esc_html_e( 'Add the link of the Reports Dashboard to the Header Menu', 'learndash-reports-by-wisdmlabs' ); ?></span>
				</label>
			<?php
		}

		public static function get_licensing_section() {
			$settings_data = get_option( 'wrld_settings', false );
			$status        = __( 'Not Active', 'learndash-reports-by-wisdmlabs' ); // get and save activation status
			$license_key   = trim( get_option( 'edd_learndash-reports-pro_license_key' ) );
			$key_data      = get_option( 'edd_learndash-reports-pro_' . $license_key . '_data', array() );

			if ( defined( 'LDRP_PLUGIN_VERSION' ) ) {
				global $ldrp_plugin_data;
				$ldrp_plugin_data = include_once LDRP_PLUGIN_DIR . 'license.config.php';
				require_once LDRP_PLUGIN_DIR . 'licensing/class-wdm-license.php';
				// $license     = \Licensing\WdmLicense::checkLicenseAvailiblity( 'learndash-reports-pro', false );
				// $validity    = \Licensing\WdmLicense::getCachedVersionInfo( 'wdm_learndash-reports-pro_license_trans' );
				$saved_status = get_option( 'edd_learndash-reports-pro_license_status', false );
				$status       = ! empty( $saved_status ) ? $saved_status : $status;
				$str          = get_home_url();
				$site_url     = preg_replace( '#^https?://#', '', $str );
			}
			$status_class = self::get_license_staus_class( $status );
			$action       = self::get_license_action_by( $status );
			$action_class = self::get_license_action_class_by( $status );
			?>
			<div class='wrld-dashboard-licence-tool-container' data-nonce='<?php echo esc_html( wp_create_nonce( 'edd_learndash-reports-pro_nonce' ) ); ?>'>
				<div class='wrld-license-header-section'>
					<div class='wrld-license-header key'><?php esc_html_e( 'WISDM Reports for LearnDash PRO', 'learndash-reports-by-wisdmlabs' ); ?>
					</div>
					<div class='wrld-license-header status'><?php esc_html_e( 'License Status', 'learndash-reports-by-wisdmlabs' ); ?>
					</div>
					<div class='wrld-license-header action'><?php esc_html_e( 'Action', 'learndash-reports-by-wisdmlabs' ); ?>
					</div>
				</div>
				<div class='wrld-license-body'>
					<div class='wrld-license-cell key'>
						<input type="text" name="wrld-pro-license-key" id="wrld-pro-license-key" value='<?php echo esc_html( $license_key ); ?>' placeholder='<?php esc_html_e( 'Enter license key', 'learndash-reports-by-wisdmlabs' ); ?>' <?php disabled( 'valid' == $status ); ?>>
					</div>
					<div class='wrld-license-cell status'>
						<span class='wrld-license-status <?php echo esc_attr( $status_class ); ?>'><span class='dashicons icon <?php echo esc_attr( $status_class ); ?>'></span><?php echo esc_attr( self::get_license_status_to_display( $status ) ); ?></span>
					</div>
					<div class='wrld-license-cell action'>
						<button class='wrld-license-action <?php echo esc_attr( $action_class ); ?>'><?php echo esc_attr( $action ); ?></button>
					</div>
				</div>
				<?php
				if ( 'invalid' == $status ) {
					?>
					<div class='wrld-license-invalid-key'>
						<span> <?php esc_html_e( 'License Key Activation Failed! Check the "Help Section" to move ahead.', 'learndash-reports-by-wisdmlabs' ); ?> </span>
					</div>
					<?php
				} elseif ( 'expired' == $status ) {
					$renew_link = ! empty( $key_data->renew_link ) ? $key_data->renew_link : 'https://wisdmlabs.com';
					?>
					<div class='wrld-license-invalid-key'>
						<span> 
							<?php esc_html_e( 'License Key Expired! Click the link to ', 'learndash-reports-by-wisdmlabs' ); ?> 
						</span>
						<a href="<?php echo esc_attr( $renew_link ); ?>" target="__blank">
						 <?php esc_html_e( 'Renew License', 'learndash-reports-by-wisdmlabs' ); ?>
						</a>
					</div>
					<?php
				} elseif ( 'valid' == $status && ! empty( $key_data ) ) {
					$expiration_date   = $key_data->expires;
					$activation_status = $key_data->site_count . ' ' . __( 'of', 'learndash-reports-by-wisdmlabs' ) . ' ' . $key_data->license_limit;
					?>
					<div class='wrld-license-details'>
					<?php
					if ( 'lifetime' !== $expiration_date ) {
						$expiration_date = strtotime( $expiration_date );
						$expiration_date = gmdate( 'F d, Y', $expiration_date );
						if ( ! empty( $expiration_date ) ) {
							?>
								<div class='wrld-license-expiration-status'>
									<span class='wrld-status-label'><?php esc_html_e( 'License Expires on:', 'learndash-reports-by-wisdmlabs' ); ?></span>
									<span class='wrld-status-value'><?php echo esc_html( $expiration_date ); ?></span>
								</div>
							<?php
						}
					} else {
						if ( ! empty( $expiration_date ) ) {
							?>
								<div class='wrld-license-expiration-status'>
									<span class='wrld-status-label'><?php esc_html_e( 'Lifetime License', 'learndash-reports-by-wisdmlabs' ); ?></span>
								</div>
							<?php
						}
					}
					?>
						<?php
						if ( isset( $activation_status ) ) {
							?>
									<div class='wrld-license-expiration-status'>
										<span class='wrld-status-label'><?php esc_html_e( 'Active Licenses:', 'learndash-reports-by-wisdmlabs' ); ?></span>
										<span class='wrld-status-value'><?php echo esc_html( $activation_status ); ?></span>
									</div>
								<?php
						}
						?>
					</div>
					<?php
				} elseif ( 'no_activations_left' == $status ) {
					?>
					<div class='wrld-license-invalid-key'>
						<span> <?php esc_html_e( 'License Key Activation Failed! No activations left, Active Sites : ', 'learndash-reports-by-wisdmlabs' ); ?> </span>
						<ul>
						<?php
						if ( ! empty( $key_data->sites ) && ! empty( $key_data->sites[0] ) ) {
							foreach ( $key_data->sites[0] as $site_address ) {
								?>
								<li class='wrld-license-active-sites'><?php echo esc_html( $site_address ); ?></li>
								<?php
							}
						}
						?>
						</ul>
					</div>
					<?php
				}
				?>
			</div>
			<div class='wrld-post-license-action'>
				<?php
				$config_link       = add_query_arg( array( 'page' => 'wrld-settings' ), admin_url( 'admin.php' ) );
				$action_text       = __( 'Configure Reports Dashboard', 'learndash-reports-by-wisdmlabs' );
				$is_visited        = get_option( 'wrld_onboarded_student_dashboard_introduction', false );
				$wrld_student_page = get_option( 'ldrp_student_page', false );
				$wrld_page         = get_option( 'ldrp_reporting_page', false );
				if ( ! $is_visited || ! $wrld_student_page || 'publish' !== get_post_status( $wrld_student_page ) ) {
					$config_link = add_query_arg( array( 'page' => 'wrld-dashboard-page' ), admin_url( 'admin.php' ) );
				} elseif ( ! $wrld_page || 'publish' !== get_post_status( $wrld_page ) ) {
					$config_link = add_query_arg( array( 'page' => 'wrld-dashboard-page' ), admin_url( 'admin.php' ) );
				}
				if ( 'valid' == $status ) {
					// $wrld_student_page = get_option( 'ldrp_student_page', false );
					if ( $wrld_page && $wrld_page > 0 && 'publish' === get_post_status( $wrld_page ) ) {
						$config_link = get_post_permalink( $wrld_page );
						$action_text = __( 'View dashboard', 'learndash-reports-by-wisdmlabs' );
					}
					if ( ! get_option( 'wrld_visited_dashboard', false ) ) {
						$config_link = 'admin.php?page=wrld-settings&subtab=data-upgrade';
					}
					?>
						<button data-link='<?php echo esc_attr( $config_link ); ?>' class='wrld-license-page-config-button'> <?php echo esc_html( $action_text ); ?> </button>
					<?php
				} elseif ( empty( $license_key ) || 'invalid' == $status ) {
					if ( ! get_option( 'wrld_visited_dashboard', false ) ) {
						$config_link = 'admin.php?page=wrld-settings&subtab=data-upgrade';
					}
					?>
						<button data-link='<?php echo esc_attr( $config_link ); ?>' class='wrld-license-page-skip-now-button'> <?php esc_html_e( 'Skip for now', 'learndash-reports-by-wisdmlabs' ); ?> </button>
						<?php
				}
				?>
			</div>
			<?php
		}


		public static function get_license_staus_class( $status = 'Not Active' ) {
			$status_class = 'license-warning';
			switch ( $status ) {
				case 'active':
				case 'valid':
					$status_class = 'license-active';
					break;
				case 'invalid':
				case 'expired':
					$status_class = 'license-error';
					break;
				default:
					$status_class = 'license-warning';
					break;
			}

			return $status_class;
		}

		public static function get_license_action_by( $status ) {
			$action = __( 'Activate', 'learndash-reports-by-wisdmlabs' );

			switch ( $status ) {
				case 'active':
				case 'valid':
						$action = __( 'Deactivate', 'learndash-reports-by-wisdmlabs' );
					break;

				default:
					// code...
					break;
			}

			return $action;
		}

		public static function get_license_status_to_display( $status = 'Not Active' ) {
			switch ( $status ) {
				case 'Not Active':
					$status = __( 'Not Active', 'learndash-reports-by-wisdmlabs' );
					break;
				case 'invalid':
				case 'no_activations_left':
					$status = __( 'Failed', 'learndash-reports-by-wisdmlabs' );
					break;
				case 'expired':
					$status = __( 'Expired', 'learndash-reports-by-wisdmlabs' );
					break;
				case 'valid':
					$status = __( 'Active', 'learndash-reports-by-wisdmlabs' );
					break;
				default:
					$status = __( 'Not Active', 'learndash-reports-by-wisdmlabs' );
					break;
			}

			return $status;
		}

		public static function get_license_action_class_by( $status = 'valid' ) {
			$action_class = 'valid' !== strtolower( $status ) ? 'action_activate' : 'action_deactivate';
			return $action_class;
		}

	}
}
