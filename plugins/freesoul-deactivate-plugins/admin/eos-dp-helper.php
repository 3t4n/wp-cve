<?php
/**
 * It includes all the helper functions for the backend.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Return sortable pages.
function eos_dp_sortable_pages() {
	return array(
		'eos_dp_menu',
		'eos_dp_by_archives',
		'eos_dp_by_terms_archives',
		'eos_dp_url',
		'eos_dp_admin_url',
		'eos_dp_firing_order',
		'eos_dp_ajax',
		'eos_dp_logged',
		'eos_dp_report',
		'eos_dp_by_post_requests',
	);
}
// Enqueue scripts for back-end.
function eos_dp_scripts() {
	$rtl                     = is_rtl() ? '-rtl' : '';
	$active_plugins          = eos_dp_active_plugins();
	$params                  = array( 'is_rtl' => is_rtl() );
	$params['ajaxurl']       = admin_url( 'admin-ajax.php' );
	$params['html_url']      = EOS_DP_PLUGIN_URL . '/inc/html/';
	$params['home_url']      = get_home_url();
	$params['main_style']    = EOS_DP_MAIN_STYLESHEET . $rtl . '.css';
	$params['last_save']     = eos_dp_get_option( 'fdp_last_save' );
	$params['dashicons_url'] = includes_url( 'fonts/dashicons.eot' );
	$params['headers']       = wp_json_encode( getallheaders() );
	$params['plugins_step']  = 4;
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	if ( isset( $_GET['page'] ) ) {
		if ( in_array( $_GET['page'], eos_dp_sortable_pages() ) ) {
			wp_enqueue_script( 'jquery-ui-draggable', array( 'jquery', 'jquery-ui-sortable' ) );
		}
		$params['page']      = esc_js( esc_attr( sanitize_text_field( $_GET['page'] ) ) );
		$params['plugins_n'] = count( $active_plugins );
		$dependencies        = eos_dp_plugins_dependencies();
		if ( ! empty( $dependencies ) ) {
			$opts = eos_dp_get_option( 'eos_dp_pro_main' );
			if ( ! isset( $opts['dependencies'] ) || '1' === $opts['dependencies'] ) {
				$params['dependencies'] = wp_json_encode( $dependencies );
			}
		}
		if( isset( $_GET['reopen_pointer'] ) && 'true' === $_GET['reopen_pointer'] ) {
			$pointers = apply_filters( 'fdp_admin_pointers-' . sanitize_text_field( $_GET['page'] ), array() );
			if ( $pointers && is_array( $pointers ) ) {
				$user_meta      = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
				$dismissed      = isset( $_GET['reopen_pointer'] ) || ! is_string( $user_meta ) ? array() : explode( ',', $user_meta );
				$valid_pointers = $valid_pointers_keys = array();
				foreach ( $pointers as $pointer_id => $pointer ) {
					if ( in_array( $pointer_id, $dismissed ) ) {
						continue;
					}
					$pointer['pointer_id']        = $pointer_id;
					$valid_pointers['pointers'][] = $pointer;
					$valid_pointers_keys[]        = $pointer_id;
				}
				if ( ! empty( $valid_pointers ) ) {
					$all_dismissed = $user_meta;
					if ( $all_dismissed ) {
						if ( is_string( $all_dismissed ) ) {
							$all_dismissed = explode( ',', $all_dismissed );
						}
						if ( is_array( $all_dismissed ) ) {
							$all_dismissed = explode( ',', implode( ',', $all_dismissed ) );
							foreach ( $valid_pointers_keys as $valid_pointer ) {
								if ( in_array( $valid_pointer, $all_dismissed ) ) {
									unset( $all_dismissed[ array_search( $valid_pointer, $all_dismissed ) ] );
								}
							}
						}
					}
					add_filter( 'fdp_cleanup_backend_scripts', '__return_false' );
					add_filter( 'fdp_cleanup_backend_styles', '__return_false' );
					$all_dismissed = is_array( $all_dismissed ) ? $all_dismissed : array();
					update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', implode( ',', $all_dismissed ) );
					wp_enqueue_style( 'wp-pointer' );
					wp_localize_script( 'wp-pointer', 'fdpWpPointer', array( 'dismiss_text' => esc_html__( "Don't show again", 'freesoul-deactivate-plugins' ) ) );
					wp_enqueue_script( 'fdp-pointer', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-pointers.js', array( 'wp-pointer' ) );
					wp_localize_script( 'fdp-pointer', 'fdpPointer', $valid_pointers );
					$rtl = is_rtl() ? '-rtl' : '';
					wp_enqueue_style( 'pointer-css', includes_url() . 'css/wp-pointer' . $rtl . '.min.css' );
				}
			}
		}
	}
	$deps = array( 'jquery' );
	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], eos_dp_sortable_pages() ) ) {
		$deps[] = 'jquery-ui-draggable';
		$deps[] = 'jquery-ui-sortable';
	}
	wp_enqueue_script( 'eos-dp-backend', EOS_DP_MAIN_JS . '.js', $deps, false, true );
	wp_localize_script( 'eos-dp-backend', 'eos_dp_js', $params );
}

// Plugins dependencies.
function eos_dp_plugins_dependencies() {
	$dependencies = apply_filters( 'fdp_plugin_dependency', array() );
	if ( class_exists( 'WooCommerce' ) ) {
		$dependencies['woocommerce/woocommerce.php'] = array(
			'strings' => array( 'woo', 'woocommerce' ),
		);
	}
	if ( class_exists( 'Ai1wm_Main_Controller' ) ) {
		$dependencies['all-in-one-wp-migration/all-in-one-wp-migration.php'] = array(
			'strings' => array( 'all-in-one-wp-migration' ),
		);
	}
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		$dependencies['elementor/elementor.php'] = array(
			'strings' => array( 'elementor' ),
		);
	}
	return $dependencies;
}

// Print style.
function eos_dp_link_style( $handle, $url, $media = 'all' ) {
	?>
	<link id="<?php echo esc_attr( $handle ); ?>-css" rel="stylesheet" type="text/css" href="<?php echo esc_url( $url ); ?>" media="<?php echo esc_attr( $media ); ?>" />
	<?php
}
// Print script.
function eos_dp_add_inline_script( $handle, $url ) {
	?>
	<script id="<?php echo esc_attr( $handle ); ?>-js" src="<?php echo esc_url( $url ); ?>"></script>
	<?php
}

if ( ! function_exists( 'eos_dp_get_option' ) ) {
	// Get options in case of single or multisite installation.
	function eos_dp_get_option( $option ) {
		if ( ! is_multisite() ) {
			return get_option( $option );
		} else {
			return get_blog_option( get_current_blog_id(), $option );
		}
	}
}
// It adds a settings link to the action links in the plugins page.
function eos_dp_plugin_add_settings_link( $links ) {
	$fdp_links = array(
		'<a class="eos-dp-setts" href="' . admin_url( 'admin.php?page=eos_dp_menu' ) . '">' . esc_html__( 'Settings', 'freesoul-deactivate-plugins' ) . '</a>',
		'<a class="eos-dp-help" href="' . EOS_DP_DOCUMENTATION_URL . '" target="_blank" rel="noopener">' . esc_html__( 'Documentation', 'freesoul-deactivate-plugins' ) . '</a>'
	);
	if( defined( 'FDP_PRO_ACTIVE' ) && FDP_PRO_ACTIVE ) {
		$fdp_links[] = '<a href="https://support.freesoul-deactivate-plugins.com/" target="_fdp_premium_support" rel="noopener">' . esc_html( 'Support', 'freesoul-deactivate-plugins' ) . '</a>';

	}
	else {
		$fdp_links[] = '<a class="eos-dp-help" href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/" target="_fdp_free_support" rel="noopener">' . esc_html__( 'Support', 'freesoul-deactivate-plugins' ) . '</a>';
		$fdp_links[] = '<a class="eos-dp-help" href="https://shop.freesoul-deactivate-plugins.com/" target="_fdp_pro" rel="noopener" style="color:#B07700;font-weight:bold;text-wrap:nowrap">' . esc_html__( 'Upgrade', 'freesoul-deactivate-plugins' ) . ' <span style="position:relative;top:-10px;' . ( is_rtl() ? 'right' : 'left' ) . ':-6px;display:inline-block">👑</span></a>';
	}
	return array_merge( $links, $fdp_links );
}

// It redirects to the plugin settings page on successfully plugin activation.
function eos_dp_redirect_to_settings() {
	if ( get_transient( 'freesoul-dp-notice-succ' ) ) {
		delete_transient( 'freesoul-dp-notice-succ' );
		if ( ! get_transient( 'freesoul-dp-updating-mu' ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=eos_dp_menu' ) );
		}
	}
	if ( isset( $_REQUEST['eos_dp_activated_from'] ) ) {
		wp_safe_redirect( esc_url( add_query_arg( 'page', esc_attr( sanitize_text_field( $_REQUEST['eos_dp_activated_from'] ) ), admin_url( 'admin.php' ) ) ) );
	}
	$previous_version = eos_dp_get_option( 'eos_dp_version' );
	$version_compare  = version_compare( $previous_version, EOS_DP_VERSION, '<' );
	if ( $version_compare && EOS_DP_NEED_UPDATE_MU ) {
		// if the plugin was updated and we need to update also the mu-plugin.
		define( 'EOS_DP_DOING_MU_UPDATE', true );
		if ( file_exists( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' ) ) {
			unlink( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' );
		}
		require EOS_DP_PLUGIN_DIR . '/plugin-activation.php';
		eos_dp_update_option( 'eos_dp_version', EOS_DP_VERSION );
		set_transient( 'freesoul-dp-updating-mu', 5 );
	}
}

// It creates the transient needed for displaing plugin notices after activation.
function eos_dp_admin_notices() {
	do_action( 'fdp_admin_notices' );
	// It creates the transient needed for displaing plugin notices after activation.
	if ( get_transient( 'freesoul-dp-notice-fail' ) ) {
		delete_transient( 'freesoul-dp-notice-fail' );
		?>
	<div class="fdp-wrp notice notice-error is-dismissible">
		<p><?php esc_html_e( 'You have no direct write access, Freesoul Deactivate Plugins was not able to create the necessary mu-plugin and will not work.', 'freesoul-deactivate-plugins' ); ?></p>
	</div>
		<?php
	}
	$mu_exists = file_exists( WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php' );
	$message   = $class = '';
	if ( eos_dp_is_fdp_page() ) {
		?>
		<div class="fdp-wrp fdp-no-jquery notice notice-error is-dismissible">
			<p><?php esc_html_e( 'The jQuery library is not loaded or it is loaded from an external domain.', 'freesoul-deactivate-plugins' ); ?></p>
			<p><?php esc_html_e( 'If you are loading jQuery from an external domain uncheck the FDP Content Security Policy from your profile page.', 'freesoul-deactivate-plugins' ); ?></p>
			<p><a class="button" title="<?php esc_html_e( 'Go to your profile page.', 'freesoul-deactivate-plugins' ); ?>" href="<?php echo esc_url( admin_url( '/profile.php#fdp-user-csp' ) ); ?>" target="_fdp_user_profile"><?php esc_html_e( 'Go to your profile page.', 'freesoul-deactivate-plugins' ); ?></a></p>
		</div>
		<?php
		if ( ! defined( 'EOS_DP_MU_VERSION' ) || EOS_DP_MU_VERSION !== EOS_DP_VERSION || ! $mu_exists ) {
			if ( ! $mu_exists ) {
				$class   = 'error';
				$message = '<p><h1>' . sprintf( esc_html__( 'Very important file missing. First, refresh this page, if you still see this message, disable Freesoul Deactivate Plugins and activate it again. If nothing helps, copy the file %1$s and put it into the directory %2$s', 'freesoul-deactivate-plugins' ), '/wp-content/plugins/freesoul-deactivate-plugins/mu-plugins/eos-deactivate-plugins.php', 'wp-content/mu-plugins/' ) . '</h1></p>';
			} elseif ( $mu_exists && ! defined( 'EOS_DP_MU_VERSION' ) ) {
				$class   = 'error';
				$message = '<p><h1>' . sprintf( esc_html__( 'Issue detected. It looks the file %s has been modified.', 'freesoul-deactivate-plugins' ), WPMU_PLUGIN_DIR . '/eos-deactivate-plugins.php', WPMU_PLUGIN_DIR ) . '</h1></p>';
			} elseif ( defined( 'EOS_DP_MU_VERSION' ) && EOS_DP_MU_VERSION !== EOS_DP_VERSION ) {
				$class    = 'warning';
				$message  = '<p>' . esc_html__( 'Issue detected. Refresh this page. If you still see this message disable Freesoul Deactivate Plugins (only disable NOT DELETING it, or you will lose all the options), then activate it and refresh again this page.', 'freesoul-deactivate-plugins' ) . '</p>';
				$message .= '<p>' . sprintf( esc_html__( 'If you still see this message after disabling and reactivating Freesoul Deactivate Plugins and after refreshing this page, open a thread on the %1$sPlugin Support Forum%2$s', 'freesoul-deactivate-plugins' ) . '</p>', '<a href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/" target="_blank" rel="noopener">', '</a>' ) . '</p>';
			}
			?>
			<div class="fdp-wrp notice notice-<?php echo esc_attr( $class ); ?> is-dismissible" style="line-height:1.5;display:block !important">
				<?php
				echo wp_kses(
					$message,
					array(
						'h1' => array(),
						'p'  => array(),
						'a'  => array(
							'href'   => array(),
							'rel'    => array(),
							'target' => array(),
						),
					)
				);
				?>
			</div>
			<?php
		}
	}
}

// It display the message of an admin notice.
function eos_dp_display_admin_notice( $name, $title, $msg, $type, $after_notice = '' ) {
	static $counter = 0;
	++$counter;
	?>
	<p>
		<span class="dashicons dashicons-warning" style="color:#d63638"></span> <?php echo esc_html( $title ); ?>
		<a class="hover" title="<?php esc_attr_e( 'Show details', 'freesoul-deactivate-plugins' ); ?>" onclick="document.getElementById('fdp-<?php echo sanitize_key( $name ); ?>-notice').style.display='block';"><?php esc_html_e( 'Show details', 'freesoul-deactivate-plugins' ); ?></a>
	</p>
	<div id="fdp-<?php echo sanitize_key( $name ); ?>-notice" class="eos-hidden eos-dp-notice fdp-draggable fdp-wrp notice notice-<?php esc_attr( $type ); ?> is-dismissible" style="width:80vw;max-width:800px;line-height:1.5;padding:30px;z-index:99999999999999999;position:fixed;left:50%;top:50%;text-align:<?php echo is_rtl() ? 'right' : 'left'; ?>;-o-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);-moz-transform:translate(-50%,-50%);-webkit-transform:translate(-50%,-50%);transform:translate(-50%,-50%)">
		<div id="fdp-<?php echo sanitize_key( $name ); ?>-notice-content"><?php echo wp_kses_post( wpautop( $msg ) ); ?></div>
		<p><?php echo wp_kses_post( $after_notice ); ?></p>
		<div style="margin-top:16px" class="right">
			<button class="button" onclick="eos_cbi_copy_to_clipboard(document.getElementById('fdp-<?php echo sanitize_key( $name ); ?>-notice-content').innerText);"><?php esc_html_e( 'Copy' ); ?></button>
			<button class="button" onclick="document.getElementById('fdp-<?php echo sanitize_key( $name ); ?>-notice').style.display='none';"><?php esc_html_e( 'Close' ); ?></button>
			<button class="button fdp-dismiss-notice" onclick="document.getElementById('fdp-<?php echo sanitize_key( $name ); ?>-notice').style.display='none';eos_dp_call_ajax(this);" data-nonce="<?php echo esc_attr( wp_create_nonce( 'eos_dp_dismiss_notice' ) ); ?> " data-action="eos_dp_dismiss_notice" data-data="<?php echo esc_attr( wp_json_encode( array( 'key' => sanitize_key( $name ) ) ) ); ?>"><?php esc_html_e( 'Dismiss' ); ?></button>
		</div>
	</div>
	<script>document.getElementById('fdp-notifications-count').innerText = <?php echo absint( $counter ); ?>;</script>
	<?php
}

// It adds the plugin setting page under plugins menu.
function eos_dp_options_page() {
	if ( ! apply_filters( 'eos_dp_user_can_settings', true ) ) {
		return;
	}
	$capability = current_user_can( 'fdp_plugins_viewer' ) ? 'read' : 'activate_plugins';
	$capability = apply_filters( 'eos_dp_settings_capability', $capability );

	add_menu_page( esc_html__( 'Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ), esc_html__( 'Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ), $capability, 'fdp_hidden_menu', '__return_false', 'dashicons-plugins-checked', 999999 );
	add_menu_page( esc_html__( 'Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ), esc_html__( 'Freesoul Deactivate Plugins', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_menu', 'eos_dp_options_page_callback', 'dashicons-plugins-checked', 65 );
	add_plugins_page( esc_html__( 'Create new', 'freesoul-deactivate-plugins' ), esc_html__( 'Create new', 'freesoul-deactivate-plugins' ), 'install_plugins', 'eos_dp_create_plugin', 'eos_dp_create_plugin_callback', 20 );
	add_plugins_page( esc_html__( 'Plugins Manager', 'freesoul-deactivate-plugins' ), esc_html__( 'Plugins Manager', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_menu', 'eos_dp_options_page_callback', 20 );
	
	$menu_pages = array(
		array( 'eos_dp_menu', __( 'Plugins Manager', 'freesoul-deactivate-plugins' ), __( 'Plugins Manager', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_menu', 'eos_dp_options_page_callback', 20 ),
		array( 'eos_dp_menu', __( 'Tools', 'freesoul-deactivate-plugins' ), __( 'Tools', 'freesoul-deactivate-plugins' ), 'install_plugins', 'eos_dp_create_plugin', 'eos_dp_create_plugin_callback', 140 ),
		array( 'eos_dp_menu', __( 'Testing', 'freesoul-deactivate-plugins' ), __( 'Testing', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_smoke_tests', 'eos_dp_smoke_tests_callback', 220 )
	);

	foreach( $menu_pages as $menu_page ) {
		add_submenu_page( 
			esc_attr( $menu_page[0] ),
			esc_html( $menu_page[1] ),
			esc_html( $menu_page[2] ),
			esc_attr( $menu_page[3] ),
			esc_attr( $menu_page[4] ),
			esc_attr( isset( $_GET['page'] ) && $menu_page[4] === sanitize_text_field( $_GET['page'] ) ? $menu_page[5] : '__return_false' ),
			absint( $menu_page[6] ) );
	}

	do_action( 'eos_dp_admin_menu_items' );
	

	$menu_pages = apply_filters( 'fdp_menu_pages', array(
		array( 'eos_dp_menu', __( 'Experiments', 'freesoul-deactivate-plugins' ), __( 'Experiments', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_experiments', 'eos_dp_experiments_callback', 250 ),
		array( 'eos_dp_menu', __( 'Help', 'freesoul-deactivate-plugins' ), __( 'Help', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_help', 'eos_dp_help_callback', 250 ),
		array( 'eos_dp_menu', __( 'Add-ons', 'freesoul-deactivate-plugins' ), __( 'Add-ons', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_addons', 'eos_dp_addons_callback', 250 ),
		array( 'fdp_hidden_menu', __( 'Deactivate by post type', 'freesoul-deactivate-plugins' ), __( 'Post Types', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_by_post_type', 'eos_dp_by_post_type_callback', 20 ),
		array( 'fdp_hidden_menu', __( 'Deactivate by Archive', 'freesoul-deactivate-plugins' ), __( 'Archives', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_by_archive', 'eos_dp_by_archive_callback', 30 ),
		array( 'fdp_hidden_menu', __( 'Deactivate by Term Archive', 'freesoul-deactivate-plugins' ), __( 'Term Archives', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_by_term_archive', 'eos_dp_by_term_archive_callback', 40 ),
		array( 'fdp_hidden_menu', __( 'Deactivate on mobile devices', 'freesoul-deactivate-plugins' ), __( 'Mobile', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_mobile', 'eos_dp_mobile_callback', 50 ),
		array( 'fdp_hidden_menu', __( 'Deactivate on search resutls page', 'freesoul-deactivate-plugins' ), __( 'Search', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_search', 'eos_dp_search_callback', 60 ),
		array( 'fdp_hidden_menu', __( 'Activate only on specific URLs', 'freesoul-deactivate-plugins' ), __( 'By URL', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_one_place', 'eos_dp_one_place_callback', 60 ),
		array( 'fdp_hidden_menu', __( 'Deactivate by User Agent', 'freesoul-deactivate-plugins' ), __( 'Browser', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_browser', 'eos_dp_browser_callback', 70 ),
		array( 'fdp_hidden_menu', __( 'Deactivate by URL', 'freesoul-deactivate-plugins' ), __( 'Custom URLs', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_url', 'eos_dp_by_url_callback', 80 ),
		array( 'fdp_hidden_menu', __( 'Deactivate in Administration Pages by custom URLs', 'freesoul-deactivate-plugins' ), __( 'Backend URLs', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_admin_url', 'eos_dp_by_admin_url_callback', 90 ),
		array( 'fdp_hidden_menu', __( 'Deactivate in Administration Pages', 'freesoul-deactivate-plugins' ), __( 'Backend', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_admin', 'eos_dp_admin_callback', 100 ),
		array( 'fdp_hidden_menu', __( 'Deactivate depending on third party plugin action', 'freesoul-deactivate-plugins' ), __( 'Actions', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_integration', 'eos_dp_integration_callback', 110 ),
		array( 'fdp_hidden_menu', __( 'Firing Order', 'freesoul-deactivate-plugins' ), __( 'Firing Order', 'freesoul-deactivate-plugins' ), 'activate_plugins', 'eos_dp_firing_order', 'eos_dp_firing_order_callback', 120 ),
		array( 'fdp_hidden_menu', __( 'Roles Manager', 'freesoul-deactivate-plugins' ), __( 'Roles Manager', 'freesoul-deactivate-plugins' ), 'manage_options', 'eos_dp_roles_manager', 'eos_dp_pro_roles_manager_callback', 130 ),
		array( 'fdp_hidden_menu', __( 'Favorite plugins', 'freesoul-deactivate-plugins' ), __( 'Favorite plugins', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_favorite_plugins', 'eos_dp_favorite_plugins_callback', 140 ),
		array( 'fdp_hidden_menu', __( 'Reset Settings', 'freesoul-deactivate-plugins' ), __( 'Reset Settings', 'freesoul-deactivate-plugins' ), $capability, 'eos_dp_reset_settings', 'eos_dp_reset_settings_callback', 150 ),
		
	) );
	foreach( $menu_pages as $menu_page ) {
		add_submenu_page( 
			esc_attr( $menu_page[0] ),
			esc_html( $menu_page[1] ),
			esc_html( $menu_page[2] ),
			esc_attr( $menu_page[3] ),
			esc_attr( $menu_page[4] ),
			esc_attr( isset( $_GET['page'] ) && $menu_page[4] === sanitize_text_field( $_GET['page'] ) ? $menu_page[5] : '__return_false' ),
			absint( $menu_page[6] ) );
	}
	if( !is_multisite() && ! defined( 'EOS_DP_PRO_VERSION' ) ) {
		add_submenu_page( 
			'eos_dp_menu',
			esc_html__( 'Hire us', 'freesoul-deactivate-plugins' ),
			esc_html__( 'Hire us', 'freesoul-deactivate-plugins' ),
			$capability,
			'eos_dp_hireus',
			isset( $_GET['page'] ) && 'eos_dp_hireus' === sanitize_text_field( $_GET['page'] ) ? 'eos_dp_hireus_callback' : '__return_false',
			250 
		);
	}
	if( ( ! defined( 'FDP_PRO_ACTIVE' ) || true !== FDP_PRO_ACTIVE ) && isset( $GLOBALS['submenu'] ) ) {
		$GLOBALS['submenu']['eos_dp_menu'][] = array( esc_html( 'Upgrade', 'freesoul-deactivate-plugins' ), $capability, FDP_STORE_URL );
	}
	if ( 'eos_dp_admin' === eos_dp_current_fdp_page() && isset( $GLOBALS['menu'] ) && isset( $GLOBALS['submenu'] ) ) {
		$GLOBALS['fdp_admin_menu']    = $GLOBALS['menu'];
		$GLOBALS['fdp_admin_submenu'] = $GLOBALS['submenu'];
	}
}

add_filter( 'submenu_file', function( $submenu_file ) {
	// Remove FDP hidden menu item.
    remove_menu_page( 'fdp_hidden_menu' );
    return $submenu_file;
} );


// It displays the ajax loader gif.
function eos_dp_ajax_loader_img() {
	?>
	<img alt="<?php esc_html_e( 'Ajax loader', 'freesoul-deactivate-plugins' ); ?>" class="ajax-loader-img eos-not-visible" width="30" height="30" src="<?php echo esc_url( EOS_DP_PLUGIN_URL ); ?>/admin/assets/img/ajax-loader.gif" />
	<?php
}
// Alert plain permalink.
function eos_dp_alert_plain_permalink() {
	$permalink_structure = basename( get_option( 'permalink_structure' ) );
	if ( false === strpos( $permalink_structure, '%postname%' ) ) {
		$permalinks_label = esc_html__( 'the actual permalinks structure is not supported', 'freesoul-deactivate-plugins' );
		if ( '' === $permalink_structure ) {
			$permalinks_label = esc_html__( 'the permalinks are set as plain', 'freesoul-deactivate-plugins' );
		} elseif ( '/archives/%post_id%' === $permalink_structure ) {
			$permalinks_label = esc_html__( 'the permalinks are set as numeric', 'freesoul-deactivate-plugins' );
		}
		?>
	<div id="eos-dp-plain-permalink-wrg" style="line-height:1;margin:20px 0;padding:10px;color:#23282d;background:#fff;border-<?php echo is_rtl() ? 'right' : 'left'; ?>:4px solid  #dc3232">
		<div>
			<h1><?php printf( esc_html__( 'No plugins will be permanently deactivated because %s.', 'freesoul-deactivate-plugins' ), esc_html( $permalinks_label ) ); ?></h1>
			<h1><?php esc_html_e( 'Only the permalinks structures "Day and name", "Month and name", "Post name"  and the custom ones ending with "%postname%" are supported (they are also better for SEO).', 'freesoul-deactivate-plugins' ); ?></h1>
		</div>
		<div>
			<a class="button" target="_blank" href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>"><?php esc_html_e( 'Change Permalinks Structure', 'freesoul-deactivate-plugins' ); ?></a>
		</div>
	</div>
		<?php
	}
}

// It gets the plugins that are active/deactive for each post type.
function eos_dp_post_types_empty() {
	if ( isset( $_POST['eos_dp_setts'] ) ) {
		$setts = $_POST['eos_dp_setts']; //@codingStandardsIgnoreLine.
		// Sanitizatin on $setts['eos_dp_post_types'], nothing else used from $_POST.
		if ( isset( $setts['eos_dp_post_types'] ) && $setts['eos_dp_post_types'] && ! empty( $setts['eos_dp_post_types'] && '[]' !== $setts['eos_dp_post_types'] ) ) {
			$post_types = json_decode( stripslashes( sanitize_text_field( $setts['eos_dp_post_types'] ) ), true );
		} else {
			$post_types = get_post_types(
				array(
					'publicly_queryable' => true,
					'public'             => true,
				),
				'names',
				'or'
			);
		}
		$post_types = array_map( 'esc_attr', $post_types );
	} else {
		$post_types = get_post_types(
			array(
				'publicly_queryable' => true,
				'public'             => true,
			),
			'names',
			'or'
		);

	}
	return array_fill_keys(
		$post_types,
		array(
			1,
			implode( ',', array_fill( 0, count( array_unique( apply_filters( 'eos_dp_post_types_empty', get_option( 'active_plugins', array() ) ) ) ), '' ) ),
		)
	);
}
// It returns the active plugins excluding Freesoul Deactivate Plugins.
function eos_dp_active_plugins() {
	$active = isset( $GLOBALS['fdp_all_plugins'] ) && is_array( $GLOBALS['fdp_all_plugins'] ) ? array_unique( $GLOBALS['fdp_all_plugins'] ) : array_unique( get_option( 'active_plugins', array() ) );
	unset( $active[ array_search( EOS_DP_PLUGIN_BASE_NAME, $active ) ] );
	if ( defined( 'EOS_DP_PRO_PLUGIN_BASE_NAME' ) && isset( $active[EOS_DP_PRO_PLUGIN_BASE_NAME] ) ) {
		unset( $active[ array_search( EOS_DP_PRO_PLUGIN_BASE_NAME, $active ) ] );
	}
	$active = array_filter( array_values( $active ) );
	$n      = 0;
	foreach ( $active as $v ) {
		if ( false === strpos( $v, '/' ) || ! file_exists( WP_PLUGIN_DIR . '/' . $v ) ) {
			unset( $active[ $n ] );
		}
		++$n;
	}
	return apply_filters( 'eos_dp_active_plugins', array_values( $active ) );
}

// Get plugins.
function eos_dp_get_plugins() {
	$plugin_root = WP_PLUGIN_DIR;
	// Files in wp-content/plugins directory.
	$plugins_dir  = @ opendir( $plugin_root );
	$plugin_files = array();
	if ( $plugins_dir ) {
		while ( ( $file = readdir( $plugins_dir ) ) !== false ) {
			if ( substr( $file, 0, 1 ) == '.' || strpos( '_' . $file, 'freesoul-deactivate-plugins' ) > 0 ) {
				continue;
			}
			if ( is_dir( $plugin_root . '/' . $file ) ) {
				$plugins_subdir = @ opendir( $plugin_root . '/' . $file );
				if ( $plugins_subdir ) {
					while ( ( $subfile = readdir( $plugins_subdir ) ) !== false ) {
						if ( substr( $subfile, 0, 1 ) == '.' ) {
								continue;
						}
						if ( substr( $subfile, -4 ) == '.php' ) {
								$plugin_files[] = "$file/$subfile";
						}
					}
					closedir( $plugins_subdir );
				}
			} else {
				if ( substr( $file, -4 ) == '.php' ) {
					$plugin_files[] = $file;
				}
			}
		}
		closedir( $plugins_dir );
	}
	if ( empty( $plugin_files ) ) {
		return array();
	}
	foreach ( $plugin_files as $plugin_file ) {
		if ( ! is_readable( "$plugin_root/$plugin_file" ) ) {
			continue;
		}
		$plugins[ plugin_basename( $plugin_file ) ] = 1;
	}
	uasort( $plugins, 'eos_dp_sort_uname_callback' );
	return apply_filters( 'eos_dp_get_plugins', $plugins );
}

// Callback to sort array by a 'Name' key.
function eos_dp_sort_uname_callback( $a, $b ) {
	if ( ! is_array( $a ) || ! is_array( $b ) ) {
		return 0;
	}
	return strnatcasecmp( $a['Name'], $b['Name'] );
}

// It returns the updated plugins table after a third plugin activation.
function eos_dp_get_updated_plugins_table() {
	$plugins_table = eos_dp_get_option( 'eos_post_types_plugins' );
	if ( ! $plugins_table || ! is_array( $plugins_table ) || empty( $plugins_table ) ) {
		return eos_dp_post_types_empty();
	}
	if ( 'activated' !== eos_dp_get_option( 'eos_dp_new_plugin_activated' ) ) {
		$plugins_table = ! empty( $plugins_table ) ? $plugins_table : eos_dp_post_types_empty();
	} else {
		$old_post_types = array_keys( $plugins_table );
		$new_post_types = get_post_types( array( 'publicly_queryable' => true ) );
		if ( isset( $new_post_types['attachment'] ) ) {
			unset( $new_post_types['attachment'] );
		}
		$new_post_types = array_keys( array_merge( array( 'page' => 'page' ), $new_post_types ) );
		if ( $old_post_types !== $new_post_types ) {
			foreach ( $new_post_types as $key ) {
				if ( ! isset( $plugins_table[ $key ] ) ) {
					$plugins_table[ $key ] = array(
						1,
						implode( ',', array_fill( 0, count( array_unique( get_option( 'active_plugins', array() ) ) ), '' ) ),
					);
				}
			}
		}
	}
	return $plugins_table;
}

// It returns the important pages.
function eos_dp_important_pages() {
	$menus = wp_get_nav_menus();
	$ids   = $woo_ids = $nav_ids = array();
	foreach ( $menus as $menu ) {
		$menuItems = wp_get_nav_menu_items( $menu );
		if ( is_array( $menuItems ) ) {
			foreach ( $menuItems as $page ) {
				$ids[]     = $page->object_id;
				$nav_ids[] = $page->object_id;
			}
		}
	}
	$keys = array(
		'comingsoon_input_page',
		'woocommerce_shop_page_id',
		'woocommerce_cart_page_id',
		'woocommerce_checkout_page_id',
		'woocommerce_pay_page_id',
		'woocommerce_thanks_page_id',
		'woocommerce_myaccount_page_id',
		'woocommerce_edit_address_page_id',
		'woocommerce_view_order_page_id',
		'woocommerce_terms_page_id',
		'wp_page_for_privacy_policy',
	);
	if ( 'page' === eos_dp_get_option( 'show_on_front' ) ) {
		$keys[] = 'page_for_posts';
	} elseif ( 'posts' === eos_dp_get_option( 'show_on_front' ) ) {
		$keys[] = 'page_on_front';
	}
	foreach ( $keys as $opt_key ) {
		$id = eos_dp_get_option( $opt_key );
		if ( $id ) {
			$ids[] = $id;
		}
		if ( false !== strpos( $opt_key, 'woocommerce' ) ) {
			$woo_ids[] = $id;
		}
	}
	$sticky_posts = eos_dp_get_option( 'sticky_posts' );
	if ( $sticky_posts ) {
		$ids = array_merge( $ids, $sticky_posts );
	}
	return array(
		'ids'     => array_values( array_unique( $ids ) ),
		'woo_ids' => array_values( array_unique( $woo_ids ) ),
		'nav_ids' => array_values( array_unique( $nav_ids ) ),
	);
}

// Check if it's a major release and return the upgrade notice.
function eos_dp_get_update_notice() {
	$transient_name = 'eos_dp_changelog_version';
	$upgrade_notice = get_transient( $transient_name );
	if ( false === $upgrade_notice || '' === $upgrade_notice ) {
		$response = wp_safe_remote_get( 'https://plugins.svn.wordpress.org/freesoul-deactivate-plugins/trunk/readme.txt' );
		if ( ! is_wp_error( $response ) && ! empty( $response['body'] ) ) {
			$upgrade_notice = substr( $response['body'], strpos( $response['body'], '== Changelog ==' ) + 15 );
			$arr            = explode( '==', $upgrade_notice );
			$upgrade_notice = $arr[0];
			$upgrade_notice = wp_kses_post( $upgrade_notice );
			set_transient( $transient_name, $upgrade_notice, 3600 * 12 );
		}
	}
	$warning  = 'Make always a backup before updating any plugin.';
	$warning .= '<br/>';
	$warning .= '<br/>';
	$warning .= sprintf( 'If you have any issues, don\'t hesitate to open a thread on the %sSupport Forum%s', '<a href="https://wordpress.org/support/plugin/freesoul-deactivate-plugins/" target="_blan" rel="noopener">', '</a>' );
	$warning .= '<br/>';

	echo '<div class="eos_dp_plugin_upgrade_notice"><br/>' . $warning . '<br/><b>Last changes:</b><br/>' . wp_kses_post( str_replace( '*', '</br>', $upgrade_notice ) ) . '</div>'; //phpcs:ignore WordPress.Security.EscapeOutput -- No need to escape an hardcoded value.
}

if ( isset( $_GET['eos_dp_info'] ) && 'true' === $_GET['eos_dp_info'] ) {
	add_action( 'install_plugins_pre_plugin-information', 'eos_dp_plugin_information' );
}
// Add plugin information if it's not on the repository.
function eos_dp_plugin_information() {
	if ( isset( $_GET['eos_dp'] ) && isset( $_REQUEST['plugin'] ) ) {
		$api = plugins_api(
			'plugin_information',
			array(
				'slug' => wp_unslash( sanitize_text_field( $_REQUEST['plugin'] ) ),
			)
		);
		if ( is_wp_error( $api ) ) {
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . sanitize_text_field( $_GET['eos_dp'] ) );
			if ( $plugin_data ) {
				global $plugin_data;
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'network_admin_notices' );
				remove_all_actions( 'all_admin_notices' );
				remove_all_actions( 'user_admin_notices' );
				require_once ABSPATH . '/wp-admin/admin-header.php';
				add_action( 'admin_footer', 'eos_dp_plugin_page_content' );

			}
		} else {
			add_action( 'admin_footer', 'eos_dp_plugin_badge' );
		}
	}
}
// Plugin page content.
function eos_dp_plugin_page_content() {
	global $plugin_data;
	?>
	</div>
	<div id="plugin-information" style="position:fixed;width:100vw;left:0;right:0;background:#fff;z-index:9999;height:100vh">
		<div id="plugin-information-title" class="with-banner">
			<div class="vignette"></div>
			<h2><?php echo esc_html( $plugin_data['Name'] ); ?></h2>
		</div>
		<div id="plugin-information-tabs" class="with-banner">
			<a class="current" href="#"><?php esc_html_e( 'Description', 'freesoul-deactivate-plugins' ); ?></a>
		</div>
		<div class="fyi" style="min-width:240px">
			<ul>
				<?php
				foreach ( $plugin_data as $key => $value ) {
					if ( ! $value || '' === $value || in_array( $key, array( 'Name', 'Description', 'PluginURI', 'TextDomain' ) ) ) {
						continue;
					}
					?>
				<li><strong><?php echo esc_html( $key ); ?>:</strong> <?php echo wp_kses( $value, array( 'a' => array( 'href' => array() ) ) ); ?></li>
				<?php } ?>
			</ul>
		</div>
		<div id="section-holder">
			<div id="section-description" style="padding:20px" class="section">
				<?php if ( isset( $plugin_data['Author'] ) ) { ?>
				<div style="margin-top:64px">
					<p><?php printf( esc_html__( 'By %s', 'freesoul-deactivate-plugins' ), wp_kses( $plugin_data['Author'], array( 'a' => array( 'href' => array() ) ) ) ); ?></p>
				</div>
				<?php } ?>
				<?php if ( isset( $plugin_data['Description'] ) ) { ?>
				<div style="margin-top:64px">
					<p><?php echo wp_kses( $plugin_data['Description'], array( 'a' => array( 'href' => array() ) ) ); ?></p>
				</div>
					<?php
				}
				if ( isset( $plugin_data['PluginURI'] ) ) {
					?>
				<div style="margin-top:32px">
					<p><?php printf( esc_html__( 'More info at %s', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '">' . esc_url( $plugin_data['PluginURI'] ) . '</a>' ); ?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="hidden">
					<?php
				}
}

// Plugin badge.
function eos_dp_plugin_badge() {
	?>
	</div>
	<div id="eos-dp-plugin-badge" style="position:fixed;top:10px;padding:10px;z-index:99999">
		<p>
			<a target="_blank" rel="noopener" href="https://plugintests.com/plugins/<?php echo esc_attr( sanitize_text_field( $_GET['plugin'] ) ); // @codingStandardsIgnoreLine. ?>/latest"><img src="https://plugintests.com/plugins/<?php echo esc_attr( sanitize_text_field( $_GET['plugin'] ) ); // @codingStandardsIgnoreLine. ?>/php-badge.svg"></a>
		</p>
		<p>
			<a class="button" target="_blank" rel="noopener" href="https://plugintests.com/plugins/<?php echo esc_attr( sanitize_text_field( $_GET['plugin'] ) ); // @codingStandardsIgnoreLine. ?>/latest"><?php esc_html_e( 'Go to the last plugin test results', 'freesoul-deactivate-plugins' ); ?></a>
		</p>
	</div>
	<div class="hidden">
	<?php
}
// Return list of installed themes.
function eos_dp_active_themes_list( $dummy_html = true ) {
	$active_themes = wp_get_themes();
	if ( count( $active_themes ) < 1 ) {
		return false;
	}
	$output  = '<select class="eos-dp-themes-list">';
	$output .= '<option value="false">' . esc_html__( 'Current Theme', 'freesoul-deactivate-plugins' ) . '</option>';
	foreach ( $active_themes as $theme => $v ) {
		$output .= '<option value="' . esc_attr( $theme ) . '">' . esc_html( $theme ) . '</option>';
	}
	$output .= '<option value="empty_theme">' . esc_html__( 'Empty Theme', 'freesoul-deactivate-plugins' ) . '</option>';
	$output .= '<option value="fdp_naked">' . esc_html__( 'Naked Theme', 'freesoul-deactivate-plugins' ) . '</option>';
	if ( $dummy_html ) {
		$output .= '<option value="dummy_html">' . esc_html__( 'Dummy HTML file', 'freesoul-deactivate-plugins' ) . '</option>';
	}
	$output .= '</select>';
	return $output;
}

// Return true if it's a FDP settings page, or false if not so.
function eos_dp_is_fdp_page() {
	return isset( $_GET['page'] )
	&& ( in_array(
		$_GET['page'],
		apply_filters(
			'fdp_pages',
			array(
				'eos_dp_admin_url',
				'eos_dp_admin',
				'eos_dp_advanced_support',
				'eos_dp_ajax',
				'eos_dp_by_archive',
				'eos_dp_by_post_type',
				'eos_dp_by_post_request',
				'eos_dp_by_term_archive',
				'eos_dp_create_plugin',
				'eos_dp_critical_css',
				'eos_dp_experiments',
				'eos_dp_favorite_plugins',
				'eos_dp_firing_order',
				'eos_dp_help',
				'eos_dp_hireus',
				'eos_dp_addons',
				'eos_dp_hooks',
				'eos_dp_reset_settings',
				'eos_dp_integration',
				'eos_dp_logged',
				'eos_dp_menu',
				'eos_dp_mobile',
				'eos_dp_desktop',
				'eos_dp_one_place',
				'eos_dp_browser',
				'eos_dp_404',
				'eos_dp_pro_bulk_actions',
				'eos_dp_pro_hooks_recorder',
				'eos_dp_pro_import_export',
				'eos_dp_pro_installations',
				'eos_dp_pro_license',
				'eos_dp_pro_plugins',
				'eos_dp_pro_settings',
				'eos_dp_report',
				'eos_dp_roles_manager',
				'eos_dp_search',
				'eos_dp_smoke_tests',
				'eos_dp_status',
				'eos_dp_testing',
				'eos_dp_url',
				'eos_dp_pro_removed_hooks',
				'eos_dp_pro_assets',
				'eos_dp_pro_general_bloat',
				'eos_dp_pro_autoload',
				'eos_dp_pro_hooks_recorder',
				'eos_dp_backend_everywhere',
				'eos_dp_by_post_requests',
				'eos_dp_pro_whois',
				'eos_dp_translation_urls'
			)
		)
	)
		|| ( isset( $_GET['fdp_page'] ) && 'true' === $_GET['fdp_page'] )
	);
}

// Return the slug of the current FDP page.
function eos_dp_current_fdp_page() {
	if ( ! eos_dp_is_fdp_page() ) {
		return false;
	}
	return sanitize_key( $_GET['page'] ); // @codingStandardsIgnoreLine. Var already checked before calling the function.
}

// Return option as array.
function eos_dp_get_option_array( $option ) {
	$opts = eos_dp_get_option( 'eos_dp_general_setts' );
	if ( ! $opts || ! is_array( $opts ) ) {
		$opts = array();
	}
	return $opts;
}

// Check if the input is a plugin pathinfo.
function eos_dp_is_not_empty_string( $string ) {
	return '' !== $string ? '0' : '1';
}

// Return $plugins_table.
function eos_dp_plugins_table() {
	$plugins_table = eos_dp_get_updated_plugins_table();
	$plugins_table = is_array( $plugins_table ) && ! empty( $plugins_table ) ? $plugins_table : eos_dp_post_types_empty();
	return $plugins_table;
}


// Returns array with information about third plugins if a plugin for integration is active, false if no plugiin is found.
function eos_dp_plugins_integration() {
	$plugins = array(
		'wordpress-core'          => array(
			'is_active'    => true,
			'ajax_actions' => array(
				'custom-background-add'                  => array( 'description' => esc_html__( 'Add custom background', 'freesoul-deactivate-plugins' ) ),
				'set-background-image'                   => array( 'description' => esc_html__( 'Set background image', 'freesoul-deactivate-plugins' ) ),
				'custom-header-crop'                     => array( 'description' => esc_html__( 'Crop custom header', 'freesoul-deactivate-plugins' ) ),
				'custom-header-add'                      => array( 'description' => esc_html__( 'Add custom header', 'freesoul-deactivate-plugins' ) ),
				'custom-header-remove'                   => array( 'description' => esc_html__( 'Remove custom header', 'freesoul-deactivate-plugins' ) ),
				'customize_save'                         => array( 'description' => esc_html__( 'Save customize', 'freesoul-deactivate-plugins' ) ),
				'customize_trash'                        => array( 'description' => esc_html__( 'Trash customize', 'freesoul-deactivate-plugins' ) ),
				'customize_refresh_nonces'               => array( 'description' => esc_html__( 'Refresh nonces customize', 'freesoul-deactivate-plugins' ) ),
				'customize_load_themes'                  => array( 'description' => esc_html__( 'Load themes customize', 'freesoul-deactivate-plugins' ) ),
				'customize_override_changeset_lock'      => array( 'description' => esc_html__( 'Override changeset lock customize', 'freesoul-deactivate-plugins' ) ),
				'customize_dismiss_autosave_or_lock'     => array( 'description' => esc_html__( 'Dismiss autosave or lock customize', 'freesoul-deactivate-plugins' ) ),
				'load-available-menu-items-customizer'   => array( 'description' => esc_html__( 'Load available menu items customize', 'freesoul-deactivate-plugins' ) ),
				'search-available-menu-items-customizer' => array( 'description' => esc_html__( 'Search available menu items customize', 'freesoul-deactivate-plugins' ) ),
				'customize-nav-menus-insert-auto-draft'  => array( 'description' => esc_html__( 'Nav menus insert auto-draft customize', 'freesoul-deactivate-plugins' ) ),
				'upload-attachment'                      => array( 'description' => esc_html__( 'Upload attachment', 'freesoul-deactivate-plugins' ) ),
				'query-attachments'                      => array( 'description' => esc_html__( 'Query attachments', 'freesoul-deactivate-plugins' ) ),
			),
		)
	);
	$active_plugins = isset( $GLOBALS['fdp_all_plugins'] ) ? $GLOBALS['fdp_all_plugins'] : false;
	if( $active_plugins && is_array( $active_plugins ) && !empty( $active_plugins ) ){
		foreach ( array_unique( $active_plugins ) as $active_plugin ) {
			if ( file_exists( EOS_DP_PLUGIN_DIR . '/integrations/actions-integrations/actions-' . dirname( $active_plugin ) . '.php' ) ) {
				require EOS_DP_PLUGIN_DIR . '/integrations/actions-integrations/actions-' . dirname( $active_plugin ) . '.php';
				if( isset( $actions ) ){
					$plugins[ esc_attr( dirname( $active_plugin ) ) ] = $actions;
				}
				$actions = null;
			}
		}
	}
	$plugins = apply_filters( 'eos_dp_integration_action_plugins', $plugins );
	if ( $plugins && is_array( $plugins ) ) {
		foreach ( $plugins as $plugin_slug => &$arr ) {
			if ( ! isset( $args['is_active'] ) || ! $arr['is_active'] ) {
				unset( $arr[ $plugin_slug ] );
			}
		}
	}
	return $plugins;
}

// Saved options preview action button.
function eos_dp_saved_preview_button( $path, $target = '_blank' ) {
	$remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) : '';
	?>
	<a class="eos-dp-view fdp-has-tooltip fdp-right-tooltip" href="<?php echo esc_url( add_query_arg( 'show_disabled_plugins', md5( $remote_addr . ( absint( time() / 1000 ) ) ), $path ) ); ?>" target="eos-dp-view-<?php echo esc_attr( $target ); ?>"><span class="dashicons dashicons-visibility"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'View page loading plugins according the saved options', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php
}
// FDP debug button.
function eos_dp_debug_button( $path, $target = '_blank' ) {
	?>
	<a class="eos-dp-debug fdp-has-tooltip fdp-right-tooltip" href="#" data-url="<?php echo esc_attr( esc_url( $path ) ); ?>" onclick="eos_dp_debug_options(this);return false;"><span class="dashicons dashicons-editor-help"></span>
		<div class="fdp-tooltip"><?php esc_html_e( 'Check if the plugins are really disabled according to the saved options.', 'freesoul-deactivate-plugins' ); ?></div>
	</a>
	<?php
	wp_nonce_field( 'eos_dp_debug_options', 'eos_dp_debug_options' );
}

// Return true if it's a plugins deactivation settings page.
function eos_dp_is_deactivation_page() {
	return isset( $_GET['page'] ) && in_array( $_GET['page'], eos_dp_deactivation_pages() );
}
// Return array of plugins deactivation settings Pages.
function eos_dp_deactivation_pages() {
	return apply_filters( 'eos_dp_deactivation_pages', array( 'eos_dp_menu', 'eos_dp_by_post_type', 'eos_dp_by_archive', 'eos_dp_by_term_archive', 'eos_dp_mobile', 'eos_dp_desktop', 'eos_dp_search', 'eos_dp_url', 'eos_dp_admin_url', 'eos_dp_admin', 'eos_dp_integration', 'eos_dp_hooks', 'eos_dp_pro_general_bloat' ) );
}
// It saves multiple metadata given the $meta_key and an associative array of post IDs  and values.
function eos_dp_save_multiple_metadata( $meta_key, $arr ) {
	if ( empty( $arr ) || '' === $meta_key ) {
		return false;
	}
	global $wpdb;
	$meta_key = esc_sql( $meta_key );
	$arr      = json_decode( sanitize_text_field( json_encode( $arr ) ), true );
	$post_ids = esc_sql( implode( ',', array_map( 'absint', array_keys( $arr ) ) ) );
	$values   = '';
	foreach ( $arr as $id => $v ) {
		$v       = esc_sql( $v );
		$id      = esc_sql( $id );
		$values .= '(' . $id . ',\'' . $v . '\'),';
	}
	$values = rtrim( $values, ',' );
	$result = $wpdb->update(
		$wpdb->postmeta,
		array(
			'post_id'    => $post_ids,
			'meta_key'   => $meta_key,
			'meta_value' => $values,
		),
		$post_ids
	);
	return $result;
}

// It retrieves multiple metadata given the $meta_key and the array of post IDs.
function eos_dp_get_multiple_metadata( $meta_key, $ids ) {
	if ( empty( $ids ) || '' === $meta_key ) {
		return false;
	}
	global $wpdb;
	if ( is_array( $ids ) ) {
		$ids = implode( ',', array_map( 'absint', $ids ) );
	} elseif ( is_string( $ids ) ) {
		$ids = implode( ',', array_map( 'absint', explode( ',', $ids ) ) );
	}
	$ids      = esc_sql( $ids );
	$meta_key = esc_sql( $meta_key );
	$sql      = "SELECT post_id,meta_value FROM $wpdb->postmeta WHERE post_id IN ($ids) AND meta_key='$meta_key';";
	return $wpdb->get_results( $sql, OBJECT );
}

// It returns the plugin name by its paths.
function eos_dp_name_by_path( $path ) {
	return ucwords( str_replace( '-', ' ', dirname( $path ) ) );
}

// Check the privilegs what the user can do with FDP.
function eos_dp_user_capabilities( $user = false ) {
	if ( ! $user ) {
		if ( function_exists( 'wp_get_current_user' ) ) {
			$user = wp_get_current_user();
		} else {
			return false;
		}
	}
	$opts  = eos_dp_get_option( 'eos_dp_pro_main' );
	$opts  = isset( $opts['eos_dp_roles_manager'] ) ? $opts['eos_dp_roles_manager'] : false;
	$value = '';
	$roles = $other_admins = false;
	if ( $opts && isset( $opts['fdp-roles-manager'] ) ) {
		$opts = $opts['fdp-roles-manager'];
		if ( '' !== $opts && isset( $user->roles ) && is_array( $user->roles ) && ! empty( $user->roles ) ) {
			$user_roles   = $user->roles;
			$user_role    = $user_roles[0];
			$value        = $opts;
			$opts         = json_decode( str_replace( '\\', '', sanitize_text_field( $opts ) ), true );
			$roles        = $opts['roles'];
			$other_admins = $opts['other_admins'];
			if ( isset( $roles[ $user_role ] ) && 'administrator' !== $user_role ) {
				$values = $roles[ $user_role ];
				return array(
					'global_settings' => $values[0] ? true : false,
					'single_settings' => $values[1] ? true : false,
					'see_plugin'      => $values[2] ? true : false,
				);
			} elseif ( 'administrator' === $user_role && in_array( strtolower( $user->user_login ), array_keys( $other_admins ) ) ) {
				$admin_email = eos_dp_get_option( 'admin_email' );
				$main_admin  = $user->user_email === $admin_email;
				$values      = ! $main_admin && isset( $other_admins[ strtolower( $user->user_login ) ] ) ? $other_admins[ sanitize_key( strtolower( $user->user_login ) ) ] : array( true, true, true );
				return array(
					'global_settings' => $values[0] ? true : false,
					'single_settings' => $values[1] ? true : false,
					'see_plugin'      => $values[2] ? true : false,
				);
			}
		}
	}
	if ( function_exists( 'current_user_can' ) ) {
		return current_user_can( 'activate_plugins' ) ? array(
			'global_settings' => true,
			'single_settings' => true,
			'see_plugin'      => true,
		) : array(
			'global_settings' => false,
			'single_settings' => false,
			'see_plugin'      => false,
		);
	}
	return false;
}

// Updte line of code in file_exists.
function eos_dp_update_file_line( $file, $search, $replace ) {
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		$updated = false;
		$creds   = request_filesystem_credentials( admin_url(), '', false, false, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			return false;
		}
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$file_content = $wp_filesystem->get_contents( $file );
		if ( false === strpos( $file_content, $search ) ) {
			return;
		}
		$upload_dirs = wp_upload_dir();
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP' );
		}
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP/backups' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP/backups' );
		}
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP/backups/temp' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP/backups/temp' );
		}
		$file_name = pathinfo( $file, PATHINFO_FILENAME );
		$dir_name  = pathinfo( $file, PATHINFO_DIRNAME );
		$extension = pathinfo( $file, PATHINFO_EXTENSION );
		if ( $file_content ) {
			$backup_file = $upload_dirs['basedir'] . '/FDP/backups/temp/' . $file_name . '-' . time() . '.' . $extension;
			$copied      = $wp_filesystem->copy( $file, $backup_file, true );
			if ( $copied ) {
				$updated = $wp_filesystem->put_contents(
					$file,
					str_replace( $search, $replace, $file_content ),
					FS_CHMOD_FILE
				);
				if ( $updated ) {
					$wp_filesystem->delete( $backup_file );
				}
			}
		}
	}
}
// Updte options by URL.
function eos_dp_update_url_options( $path, $post_id, $plugins, $post_type, $post_status = 'public' ) {
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		$updated = false;
		$creds   = request_filesystem_credentials( admin_url(), '', false, false, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			return false;
		}
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$upload_dirs = wp_upload_dir();
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP' );
		}
		if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP/fdp-single-options' ) ) {
			$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP/fdp-single-options' );
			$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/fdp-single-options/index.html', '', FS_CHMOD_FILE );
		}
		if ( ! file_exists( $upload_dirs['basedir'] . '/FDP/fdp-single-options/index.html' ) ) {
			$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/fdp-single-options/index.html', '', FS_CHMOD_FILE );
		}
		$path  = ltrim( rtrim( $path, '/' ), '/' );
		$parts = explode( '/', $path );
		$path  = $upload_dirs['basedir'] . '/FDP/fdp-single-options';
		foreach ( $parts as $part ) {
			$path .= function_exists( 'eos_dp_sanitize_file_name' ) ? '/' . substr( md5( eos_dp_sanitize_file_name( $part ) ), 0, 8 ) : '/' . substr( md5( sanitize_file_name( $part ) ), 0, 8 );
			if ( ! $wp_filesystem->is_dir( $path ) ) {
				$wp_filesystem->mkdir( $path );
				$wp_filesystem->put_contents( $path . '/index.html', '', FS_CHMOD_FILE );
			}
		}
		$arr 						    = array(
											'post_id'   => sanitize_text_field( $post_id ),
											'post_type' => sanitize_text_field( $post_type ),
											'plugins'   => sanitize_text_field( $plugins ),
		);
		if( 'public' !== $post_status ) {
			$arr['post_status'] = sanitize_key( $post_status );
		}
		$json                           = wp_json_encode( $arr );
		$updated                        = $wp_filesystem->put_contents(
			$path . '/opts.json',
			$json,
			FS_CHMOD_FILE
		);
		$opts                           = eos_dp_get_option( 'eos_dp_opts' );
		$opts['filesystem_last_status'] = ! file_exists( $path . '/opts.json' ) ? 'fail' : 'ok';
		eos_dp_update_option( 'eos_dp_opts', $opts );
	}
}
// Updte FDP cache.
function eos_dp_update_fdp_cache( $slug, $html, $delete = false ) {
	if ( ! function_exists( 'get_filesystem_method' ) ) {
		return;
	}
	$access_type = get_filesystem_method();
	if ( $access_type === 'direct' ) {
		$updated = false;
		$creds   = request_filesystem_credentials( admin_url(), '', false, false, array() );
		if ( ! WP_Filesystem( $creds ) ) {
			return false;
		}
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}
		$upload_dirs = wp_upload_dir();
		if ( $delete ) {
			$arrFiles = glob( $upload_dirs['basedir'] . '/FDP/cache/fdp-' . sanitize_key( $slug ) . '-*.json' );
			if ( $arrFiles && is_array( $arrFiles ) && ! empty( $arrFiles ) ) {
				foreach ( $arrFiles as $file ) {
					if ( file_exists( $file ) ) {
						wp_delete_file( $file );
					}
				}
			}
		} else {
			if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP' ) ) {
				$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP' );
			}
			if ( ! $wp_filesystem->is_dir( $upload_dirs['basedir'] . '/FDP/cache' ) ) {
				$wp_filesystem->mkdir( $upload_dirs['basedir'] . '/FDP/cache' );
				$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/cache/index.html', '', FS_CHMOD_FILE );
			}
			if ( ! file_exists( $upload_dirs['basedir'] . '/FDP/cache/index.html' ) ) {
				$wp_filesystem->put_contents( $upload_dirs['basedir'] . '/FDP/cache/index.html', '', FS_CHMOD_FILE );
			}
			$arr                          = array();
			$arr[ sanitize_key( $slug ) ] = wp_kses_post( $html );
			return $wp_filesystem->put_contents(
				$upload_dirs['basedir'] . '/FDP/cache/fdp-' . sanitize_key( $slug ) . '-' . substr( md5( time() ), 0, 10 ) . '.json',
				wp_json_encode( $arr ),
				FS_CHMOD_FILE
			);
		}
	}
}

// Delete options folder.
function eos_dp_delete_folder( $dirPath ) {
	if ( is_dir( $dirPath ) ) {
		$files = array();
		$n     = 1;
		do {
			$files_n = glob( $dirPath . implode( '', array_fill( 0, $n, '/*' ) ) );
			if ( ! empty( $files_n ) ) {
				$files[ $n - 1 ] = $files_n;
			}
			++$n;
		} while ( ! empty( $files_n ) );
		$files = array_reverse( $files );
		foreach ( $files as $k => $values ) {
			foreach ( $values as $value ) {
				if ( is_dir( $value ) ) {
					rmdir( $value );
				} else {
					unlink( $value );
				}
			}
		}
		rmdir( $dirPath );
	}
}

// Get current page URL.
function eos_dp_get_current_page_url() {
	if ( isset( $_SERVER['HTTP_HOST'] ) && isset( $_SERVER['REQUEST_URI'] ) ) {
		$url  = isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
		$url .= sanitize_text_field( $_SERVER['HTTP_HOST'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );
		if ( isset( $_SERVER['QUERY_STRING'] ) ) {
			$url .= '?' . sanitize_text_field( $_SERVER['QUERY_STRING'] );
		}
		return esc_url( $url );
	}
	return false;
}

// User headers.
function eos_dp_user_headers( $args, $admin = true ) {
	$cookies = array();
	if ( $admin ) {
		foreach ( $_COOKIE as $name => $value ) {
			$cookies[ (string) sanitize_key( $name ) ] = sanitize_text_field( $value );
		}
	}
	$headers = false;
	if ( isset( $_POST['headers'] ) && ! empty( $_POST['headers'] ) ) {
		$headers = json_decode( sanitize_text_field( stripslashes( $_POST['headers'] ) ), true ); //@codingStandardsIgnoreLine.
		// Sanitization applied after stripslashes.
	}
	if ( $headers ) {
		$args['headers'] = $headers;
	} else {
		if ( isset( $_SERVER['HTTP_AUTHORIZATION'] ) && ! empty( $_SERVER['HTTP_AUTHORIZATION'] ) ) {
			$args['headers'] = array(
				'Authorization' => sanitize_text_field( $_SERVER['HTTP_AUTHORIZATION'] ),
			);
		} elseif ( isset( $_SERVER['PHP_AUTH_USER'] ) && isset( $_SERVER['PHP_AUTH_PW'] ) && ! empty( $_SERVER['PHP_AUTH_USER'] ) ) {
			$credentials     = base64_encode( $_SERVER['PHP_AUTH_USER'] . ':' . $_SERVER['PHP_AUTH_PW'] ); //@codingStandardsIgnoreLine.
			// Sanitization applied on $credentials.
			$args['headers'] = array(
				'Authorization' => sanitize_text_field( 'Basic ' . $credentials ),
			);
		}
	}
	$args['headers']['Accept-Encoding'] = 'gzip, deflate';
	$args['cookies']                    = $cookies;
	return $args;
}
// Function strpos using an array of needles.
function eos_dp_strposA( $haystack, $needle, $offset = 0 ) {
	if ( ! is_array( $needle ) ) {
		$needle = array( $needle );
	}
	foreach ( $needle as $query ) {
		if ( strpos( $haystack, $query, $offset ) !== false ) {
			return true;
		}
	}
	return false;
}

// Slide to scroll plugins on the table.
function eos_dp_plugins_slider() {
	?>
	<div class="fdp-plugins-slider-wrp">
		<input class="fdp-plugins-slider hover" style="margin:10px 0 0 0" type="range" min="0" max="<?php echo esc_attr( $GLOBALS['fdp_plugins_count'] ); ?>" value="0">
	</div>
	<?php
}

// Plugins slider row.
function eos_dp_plugins_slider_row( $class_name = '' ) {
	?>
	<tr class="fdp-slide-row<?php echo '' !== $class_name ? ' ' . esc_attr( $class_name ) : ''; ?>" style="border:none;box-shadow:none"><td style="border:none;box-shadow:none"><?php eos_dp_plugins_slider(); ?></td><td style="border:none;box-shadow:none" colspan="<?php echo esc_attr( $GLOBALS['fdp_plugins_count'] ); ?>"></td></tr>
	<?php
}

// Language switcher.
function eos_dp_wpml_switcher() {
	$get = $_GET;
	if ( isset( $_GET['lang'] ) ) {
		unset( $get['lang'] );
	}
	$switcher_html  = '<div id="fdp-lang-switcher" class="center" style="margin-top:16px">';
	$switcher_html .= '<a title="' . esc_attr__( 'All languages', 'freesoul-deactivate-plugins' ) . '" class="button" href="' . esc_url( add_query_arg( array_unique( array_merge( array( 'lang' => 'all' ), $get ) ), admin_url( '?admin.php' ) ) ) . '">' . esc_html__( 'All languages', 'freesoul-deactivate-plugins' ) . '</a>';

	if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		$fdp_wpml = eos_dp_get_option( 'icl_sitepress_settings' );
		if ( isset( $fdp_wpml['active_languages'] ) ) {
			$langs = $fdp_wpml['active_languages'];
			foreach ( $langs as $lang ) {
				$lang           = esc_attr( $lang );
				$switcher_html .= '<a title="' . esc_attr( $lang ) . '" class="button" style="border:none" href="' . esc_url( add_query_arg( array_unique( array_merge( array( 'lang' => $lang ), $get ) ), admin_url( '?admin.php' ) ) ) . '">';
				$switcher_html .= defined( 'ICL_PLUGIN_URL' ) && defined( 'WPML_PLUGIN_PATH' ) && file_exists( WPML_PLUGIN_PATH . '/res/flags/' . $lang . '.png' ) ? '<img src="' . esc_url( ICL_PLUGIN_URL . '/res/flags/' . $lang . '.png' ) . '" /> <span>' . strtoupper( $lang ) . '</span>' : esc_html( strtoupper( $lang ) );
				$switcher_html .= '</a>';
			}
		}
	} elseif ( function_exists( 'pll_languages_list' ) ) {
		$langs = pll_languages_list();
		foreach ( $langs as $lang ) {
			$lang           = esc_attr( $lang );
			$switcher_html .= '<a title="' . $lang . '" class="button" style="border:none" href="' . esc_url( add_query_arg( array_unique( array_merge( array( 'lang' => $lang ), $get ) ), admin_url( '?admin.php' ) ) ) . '">';
			$switcher_html .= defined( 'POLYLANG_FILE' ) && defined( 'POLYLANG_DIR' ) && file_exists( POLYLANG_DIR . '/flags/' . str_replace( 'en.png', 'england.png', $lang . '.png' ) ) ? '<img src="' . esc_url( str_replace( ABSPATH, get_home_url() . '/', POLYLANG_DIR ) . '/flags/' . str_replace( 'en.png', 'england.png', $lang . '.png' ) ) . '" /> <span>' . esc_html( strtoupper( $lang ) ) . '</span>' : esc_html( strtoupper( $lang ) );
			$switcher_html .= '</a>';
		}
	}
	$switcher_html .= '</div>';
	return $switcher_html;
}

// Default site language
function eos_dp_default_language() {
	if ( function_exists( 'pll_default_language' ) ) {
		return pll_default_language();
	} elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		return apply_filters( 'wpml_default_language', null );
	}
	return get_bloginfo( 'language' );
}

add_action(
	'admin_init',
	function() {
		// Settings pages init.
		if ( isset( $_GET['page'] ) && eos_dp_is_fdp_page() || isset( $_GET['fdp_add_favorites'] ) || ( isset( $_GET['page'] ) && 'eos_dp_code_browser' === $_GET['page'] ) ) {
			$active_plugins  = eos_dp_active_plugins();
			$plugins_by_dirs = eos_dp_get_plugins();
			$k               = 0;
			foreach ( $active_plugins as $plugin ) {
				if ( $plugins_by_dirs && is_array( $plugins_by_dirs ) && in_array( $plugin, array_keys( $plugins_by_dirs ) ) ) {
					++$k;
				}
			}
			$GLOBALS['fdp_plugins_count'] = $k;
			remove_all_actions( 'current_screen' );
			if( isset( $_GET['reopen_pointer'] ) && 'true' === $_GET['reopen_pointer'] ) {
				require EOS_DP_PLUGIN_DIR . '/admin/pointers/fdp-pointer.php';
			}
		}
	}
);

// It stores the information needed to rebuild the admin menu.
function eos_dp_update_fdp_admin_menu( $args ) {
	static $called = false;
	if( $called ) return;
	$called = true; // Prevent other plugins from calling this function multiple times.
	$nonce = uniqid();
	$id    = uniqid();
	set_site_transient( 'fdp_query_menu_nonce_' . sanitize_text_field( $id ), sanitize_text_field( $nonce ), 30 );
	// Call the Dashboard page with all the plugins active to get the global variables $menu, §submenu, $admin_page_hooks, and an array of menu hooks names.
	$response = wp_remote_get(
		esc_url_raw(
			add_query_arg(
				array(
					'fdp_query_menu' => 'true',
					'nonce'          => $nonce,
					'id'             => $id,
					'action'         => 'deactivate',
					'plugin'         => 'none',
				),
				admin_url()
			)
		),
		$args
	);
	if ( ! is_wp_error( $response ) ) {
		$body = wp_remote_retrieve_body( $response );
		if ( $body && ! empty( $body ) ) {
			$menus = json_decode( $body, true );
			if ( $menus && is_array( $menus ) && isset( $menus['menu'] ) && isset( $menus['submenu'] ) && isset( $menus['admin_page_hooks'] ) && isset( $menus['parent_plugin_pages'] ) ) {
				foreach ( $menus['menu'] as $k => $arr ) {
					foreach ( $arr as $k2 => $value ) {
						$arr[ sanitize_key( $k2 ) ] = sanitize_text_field( $value );
					}
					$menus['menu'][ sanitize_key( $k ) ] = $arr;
				}
				foreach ( $menus['submenu'] as $parent_page => $arrs ) {
					foreach ( $arrs as $ks => $arr2s ) {
						foreach ( $arr2s as $k2s => $values ) {
							$arr2a[ sanitize_key( $k2s ) ] = sanitize_text_field( $values );
						}
						$arrs[ sanitize_key( $ks ) ] = $arr2s;
					}
					$menus['submenu'][ sanitize_key( $parent_page ) ] = $arrs;
				}
				eos_dp_update_option( 'eos_dp_admin_menu', wp_json_encode( $menus['menu'] ) );
				eos_dp_update_option( 'eos_dp_admin_submenu', wp_json_encode( $menus['submenu'] ) );
				eos_dp_update_option( 'eos_dp_admin_page_hooks', sanitize_text_field( stripslashes( wp_json_encode( $menus['admin_page_hooks'] ) ) ) );
				eos_dp_update_option( 'eos_dp_parent_plugin_pages', sanitize_text_field( stripslashes( wp_json_encode( $menus['parent_plugin_pages'] ) ) ) );
			}
		}
	}
}

add_action( 'fdp_after_theme_activation', 'eos_dp_rebuild_rewrite_rules_and_menu', 90 );
add_action( 'activated_plugin', 'eos_dp_rebuild_rewrite_rules_and_menu', PHP_INT_MAX );
add_action( 'deactivated_plugin', 'eos_dp_rebuild_rewrite_rules_and_menu', PHP_INT_MAX );
add_action( 'upgrader_process_complete', 'eos_dp_rebuild_rewrite_rules_and_menu', PHP_INT_MAX );
add_action( 'core_upgrade_preamble', 'eos_dp_rebuild_rewrite_rules_and_menu', PHP_INT_MAX );
add_action( 'update_option_WPLANG', 'eos_dp_rebuild_rewrite_rules_and_menu', PHP_INT_MAX );

// Check the rewrite rules. If empty remotely call the homepage loading all the plugins to rebuilt hhem without issues.
function eos_dp_rebuild_rewrite_rules_and_menu() {
	eos_dp_update_fdp_admin_menu( getallheaders() );
	$rewrite_rules = eos_dp_get_option( 'rewrite_rules' );
	if ( empty( $rewrite_rules ) ) {
		// Prevent saving the rewrite rules with some deactivated plugins.
		$response = wp_remote_get(
			esc_url(
				add_query_arg(
					array(
						'action' => 'deactivate',
						'plugin' => 'none',
						't'      => time(),
					),
					home_url()
				)
			),
			array( 'sslverify' => false )
		);
	}
}

if( !function_exists( 'getallheaders' ) ) {
	// Define getallheaders if it doesn't exist (e.g. WP CLI, FastCGI).
    function getallheaders() {
		if( isset( $_SERVER ) && !empty( $_SERVER ) ) {
			$headers = array();
			foreach ( $_SERVER as $name => $value ) {
				if ( substr( $name, 0, 5 ) == 'HTTP_' ) {
					$headers[ str_replace(' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5) ) ) ) ) ] = $value;
				}
			}
			return $headers;
		}
		return array();
    }
}

add_action(
	'deactivated_plugin',
	function( $plugin ) {
		// Delete transient storing the last plugin that triggered a fatal error.
		delete_site_transient( 'fdp_plugin_disabledd_fatal_error' );
		$fdp_json =  WP_PLUGIN_DIR . '/' . dirname( sanitize_text_field( $plugin ) ) . '/fdp.json';
		if( file_exists( $fdp_json ) ) {
			// If it's an FDP add-on, it has to be removed fromm the active FDP add-ons.
			$fdp_addons = eos_dp_get_option( 'fdp_addons', array() );
			if( $fdp_addons && ! empty( $fdp_addons ) ) {
				$fdp_addons = array_unique( $fdp_addons );
			}
			if( in_array( sanitize_text_field( $plugin ), $fdp_addons ) ) {
				unset( $fdp_addons[ array_search( sanitize_text_field( $plugin ), $fdp_addons ) ] );
				eos_dp_update_option( 'fdp_addons', $fdp_addons );
			}
		}
	}
);

add_action( 'update_option_stylesheet', 'eos_dp_add_fdp_theme_activation_hook', 999999, 3 );
add_action( 'update_site_option_stylesheet', 'eos_dp_add_fdp_theme_activation_hook', 999999, 3 );

// It adds an action hook after theme activation, no matter if the old theme still exists.
function eos_dp_add_fdp_theme_activation_hook( $old_value, $value, $option ) {
	do_action( 'fdp_after_theme_activation' );
}

// Add FDP submenu item.
function fdp_add_submenu_page( $fdp_parent_item_slug, $fdp_subitem_slug, $fdp_submenu_title, $capability, $callback, $priority = 10 ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-submenu-item.php';
	$submenu_item = new FDP_Submenu_Item( $fdp_parent_item_slug, $fdp_subitem_slug, $fdp_submenu_title, $capability, $callback, $priority );
}

// Add FDP settings page.
function fdp_add_settings_page( $page_slug, $args, $parent_menu_slug, $title, $description, $autoload = false, $capability = false, $dashicon = false, $save_button = true ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-settings-page.php';
	$settings_page = new FDP_Settings_Page( $page_slug, $args, $parent_menu_slug, $title, $description, $autoload, $capability, $dashicon, $save_button );
}

// Add plugins settings page.
function fdp_add_plugins_settings_page( $slug, $title, $dashicon = '' ) {
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-one-column-page.php';
	$page = new Eos_Fdp_One_Column_Page( $slug, $title, $dashicon );
	return;
}

// Return option size by option name.
function eos_dp_get_option_size( $option_name ) {
	global $wpdb;
	$option_name = sanitize_key( $option_name );
	$results     = $wpdb->get_results( "SELECT length(option_value) AS option_value_length FROM $wpdb->options WHERE option_name = '$option_name'" );
	if ( $results && isset( $results[0] ) && isset( $results[0]->option_value_length ) ) {
		return absint( 10 * $results[0]->option_value_length ) / 10000;
	}
	return false;
}

// Return true if site was migrated from a different home URL.
function eos_dp_is_migrated() {
	$saved_site_id = eos_dp_get_option( 'fdp_site_id' );
	return $saved_site_id && ! empty( $saved_site_id ) && $saved_site_id !== fdp_site_id();
}

// Return unique ID depending on the home URL.
function fdp_site_id() {
	return substr( md5( str_replace( array( 'wwww.', 'http://' ), array( '.', '' ), get_home_url( null, '', 'http' ) ) ), 0, 8 );
}

// Return FDP menu items.
function eos_dp_menu_items(){
	$menu_file  = EOS_DP_PLUGIN_DIR . '/admin/templates/partials/nav-menu-items/menu-item-';
	return apply_filters(
		'fdp_main_nav_menu_items',
		array(
			'singles'      => array(
				'title'     => __( 'Singles', 'freesoul-deactivate-plugins' ),
				'section'   => 'control-panel-section',
				'active_if' => array( 'eos_dp_menu' ),
				'subitems'  => array( 'eos_dp_menu' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_menu' ),
				'file'      => $menu_file . 'singles.php',
			),
			'post-types'   => array(
				'title'     => __( 'Post Types', 'freesoul-deactivate-plugins' ),
				'section'   => 'by-posts',
				'active_if' => array( 'eos_dp_by_post_type' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_by_post_type' ),
			),
			'archives'     => array(
				'title'     => __( 'Archives', 'freesoul-deactivate-plugins' ),
				'section'   => 'archives',
				'active_if' => array( 'eos_dp_by_archive', 'eos_dp_by_term_archive' ),
				'subitems'  => array( 'eos_dp_by_term_archive' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_by_archive' ),
				'file'      => $menu_file . 'archives.php',
			),
			'device'       => array(
				'title'     => __( 'Device', 'freesoul-deactivate-plugins' ),
				'section'   => 'device',
				'active_if' => array( 'eos_dp_mobile', 'eos_dp_desktop' ),
				'subitems'  => array( 'eos_dp_mobile', 'eos_dp_desktop' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_mobile' ),
				'file'      => $menu_file . 'device.php',
			),
			'miscellaneus' => array(
				'title'     => __( 'Miscellaneus', 'freesoul-deactivate-plugins' ),
				'section'   => 'miscellaneus',
				'active_if' => array( 'eos_dp_search' ),
				'subitems'  => array( 'eos_dp_search' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_search' ),
				'file'      => $menu_file . 'miscellaneus.php',
			),
			'url'          => array(
				'title'     => __( 'Custom URLs', 'freesoul-deactivate-plugins' ),
				'section'   => 'url',
				'active_if' => array( 'eos_dp_url', 'eos_dp_admin_url', 'eos_dp_translation_urls' ),
				'subitems'  => array( 'eos_dp_url', 'eos_dp_admin_url', 'eos_dp_translation_urls' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_url' ),
				'file'      => $menu_file . 'frontend-urls.php',
			),
			'backend'      => array(
				'title'     => __( 'Backend', 'freesoul-deactivate-plugins' ),
				'section'   => 'admin',
				'active_if' => array( 'eos_dp_admin', 'eos_dp_admin_url', 'eos_dp_backend_everywhere' ),
				'subitems'  => array( 'eos_dp_admin', 'eos_dp_admin_url', 'eos_dp_backend_everywhere' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_admin' ),
				'file'      => $menu_file . 'backend.php',
			),
			'integration'  => array(
				'title'     => __( 'Actions', 'freesoul-deactivate-plugins' ),
				'section'   => 'integration',
				'active_if' => array( 'eos_dp_integration', 'eos_dp_ajax', 'eos_dp_by_post_requests' ),
				'subitems'  => array( 'eos_dp_integration', 'eos_dp_ajax', 'eos_dp_by_post_requests' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_integration&int_plugin=wordpress-core' ),
				'file'      => $menu_file . 'integration.php',
			),
			'users'        => array(
				'title'     => __( 'Users', 'freesoul-deactivate-plugins' ),
				'section'   => 'logged',
				'active_if' => array( 'eos_dp_logged' ),
				'subitems'  => array( 'eos_dp_logged', 'eos_dp_unlogged' ),
				'href'      => admin_url( 'admin.php?page=eos_dp_logged' ),
				'file'      => $menu_file . 'users.php',
				'pro_docu'  => EOS_DP_DOCUMENTATION_URL . '/users/',
			),
		)
	);
}
