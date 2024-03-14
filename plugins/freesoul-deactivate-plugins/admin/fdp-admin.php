<?php
/**
 * It includes the code for the backend.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$plugin = EOS_DP_PLUGIN_BASE_NAME;
define( 'EOS_DP_DOCUMENTATION_URL', 'https://freesoul-deactivate-plugins.com/how-deactivate-plugiins-on-specific-pages/' );
define( 'FDP_STORE_URL', 'https://shop.freesoul-deactivate-plugins.com/' );

if ( isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( $_REQUEST['action'] ), array( 'edit', 'editpost' ) ) ) {
	add_action(
		'admin_init',
		function() {
			$user        = wp_get_current_user();
			$fdp_metabox = get_user_meta( $user->ID, 'fdp_metabox', true );
			if ( ! $fdp_metabox || 'true' === $fdp_metabox ) {
				require EOS_DP_PLUGIN_DIR . '/inc/fdp-metaboxes.php';
			}
		}
	);
}
if ( eos_dp_is_fdp_page() ) {
	remove_all_actions( 'parse_request' );
	add_action( 'admin_init', 'eos_dp_remove_other_admin_notices' );
	if ( ! isset( $_GET['load_all_assets'] ) ) {
		  add_action( 'admin_enqueue_scripts', 'eos_dp_scripts', 999999 );
	}
	add_filter(
		'print_styles_array',
		function( $arr ) {
			if( !apply_filters( 'fdp_cleanup_backend_styles', true ) ) {
				return $arr;
			}
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'eos_dp_favorite_plugins' ) ) ) {
				return $arr;
			}
			return apply_filters( 'fdp_allowed_backend_styles', array( 'dashicons' ) );
		}
	);
	add_filter(
		'print_scripts_array',
		function( $arr ) {
			if( !apply_filters( 'fdp_cleanup_backend_scripts', true ) ) {
				return $arr;
			}
			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'eos_dp_favorite_plugins' ) ) ) {
				return $arr;
			}
			$allowed = eos_dp_allowed_backend_scripts();
			if ( isset( $_GET['eos_dp_ajax'] ) ) {
				$allowed[] = 'select2';
			}
			foreach ( $allowed as $handle ) {
				if ( ! is_array( $arr ) || ! in_array( $handle, $arr ) ) {
					unset( $allowed[ array_search( $handle, $allowed ) ] );
				}
			}
			if( !in_array( 'jquery', $allowed ) ){
				$allowed[] = 'jquery';
			}
			if( isset( $_GET['page'] ) && in_array( $_GET['page'], eos_dp_sortable_pages() ) ){
				if( !in_array( 'jquery-ui-sortable', $allowed ) ){
					$allowed[] = 'jquery-ui-sortable';
				}
				if( !in_array( 'jquery-ui-draggable', $allowed ) ){
					$allowed[] = 'jquery-ui-draggabe';
				}
			}
			return apply_filters( 'fdp_allowed_backend_scripts', $allowed );
		}
	);

	if ( 'eos_dp_favorite_plugins' !== eos_dp_current_fdp_page() ) {
		add_filter(
			'nocache_headers',
			function( $headers ) {
				$scheme = is_ssl() ? 'https' : parse_url( get_home_url(), PHP_URL_SCHEME );
				$domain = str_replace( $scheme . '://', '', get_home_url() );
				$domain = false !== strpos( $domain, '/' ) ? stristr( $domain, '/', true ) : $domain;
				// Only scripts from the same domain are allowed.
				if( apply_filters( 'fdp_security_policy_active', true ) ){
					$user = wp_get_current_user();
					$fdp_csp   = get_user_meta( $user->ID, 'fdp_csp', true );
					$fdp_csp   = ! $fdp_csp ? true : 'true' === $fdp_csp;
					if( $fdp_csp ) {
						$headers['Content-Security-Policy'] = 'script-src ' . esc_attr( $domain ) . " 'unsafe-inline'";
					}
				}
				return $headers;
			}
		);
	}
	add_action( 'current_screen', 'eos_dp_remove_help_tabs', 999999 );
	$dir = EOS_DP_PLUGIN_DIR . '/admin/templates/';
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/abstracts/class-eos-fdp-plugins-manager-page.php';
	require_once EOS_DP_PLUGIN_DIR . '/admin/classes/abstracts/class-eos-fdp-matrix-page.php';
	require_once $dir . 'partials/eos-dp-navigation.php';
	require_once $dir . 'partials/eos-dp-table-head.php';
	require_once $dir . 'partials/eos-dp-footer.php';
	foreach ( array(
		array( array( 'eos_dp_home', 'eos_dp_menu' ), 'pages/eos-dp-singles.php' ),
		array( array( 'eos_dp_by_post_type' ), 'pages/eos-dp-post-type.php' ),
		array( array( 'eos_dp_by_archive' ), 'pages/eos-dp-archive.php' ),
		array( array( 'eos_dp_by_term_archive' ), 'pages/eos-dp-terms-archive.php' ),
		array( array( 'eos_dp_mobile' ), 'pages/eos-dp-mobile.php' ),
		array( array( 'eos_dp_search' ), 'pages/eos-dp-search.php' ),
		array( array( 'eos_dp_one_place' ), 'pages/eos-dp-one-place.php' ),
		array( array( 'eos_dp_browser' ), 'pages/eos-dp-browser.php' ),
		array( array( 'eos_dp_url' ), 'pages/eos-dp-url.php' ),
		array( array( 'eos_dp_admin_url' ), 'pages/eos-dp-backend-url.php' ),
		array( array( 'eos_dp_integration' ), 'pages/eos-dp-integration.php' ),
		array( array( 'eos_dp_admin' ), 'pages/eos-dp-backend.php' ),
		array( array( 'eos_dp_smoke_tests' ), 'pages/eos-dp-smoke-tests.php' ),
		array( array( 'eos_dp_firing_order' ), 'pages/eos-dp-firing-order.php' ),
		array( array( 'eos_dp_reset_settings' ), 'pages/eos-dp-reset.php' ),
		array( array( 'eos_dp_experiments' ), 'pages/eos-dp-experiments.php' ),
		array( array( 'eos_dp_help' ), 'pages/eos-dp-help.php' ),
		array( array( 'eos_dp_hireus' ), 'pages/eos-dp-hireus.php' ),
		array( array( 'eos_dp_addons' ), 'pages/eos-dp-addons.php' ),
		array( array( 'eos_dp_create_plugin' ), 'pages/eos-dp-create-plugin.php' ),
		array( array( 'eos_dp_favorite_plugins' ), 'pages/eos-dp-favorite-plugins.php' ),
		array( array( 'eos_dp_roles_manager' ), 'pages/eos-dp-roles-manager.php' ),
	) as $arr ) {
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $arr[0] ) ) {
			require_once $dir . $arr[1];
		}
	}
	add_filter( 'wpml_show_admin_language_switcher', '__return_false' );
	add_filter( 'pll_admin_languages_filter', '__return_empty_array' );
}

// Remove help tab in the settings pages.
function eos_dp_remove_help_tabs() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}
// It adds a settings link to the action links in the plugins page.
add_filter( "plugin_action_links_$plugin", 'eos_dp_plugin_add_settings_link' );

// It redirects to the plugin settings page on successfully plugin activation.
add_action( 'admin_init', 'eos_dp_redirect_to_settings' );

// It displays the admin notices.
add_action( 'admin_notices', 'eos_dp_admin_notices', 999999 );

add_filter( 'admin_title', 'eos_dp_admin_page_title', 99, 2 );
// It set the browser tab title depending the options page.
function eos_dp_admin_page_title( $title, $sep ) {
	$labels = array(
		'scripts' => esc_html__( 'Scripts', 'freesoul-deactivate-plugins' ),
		'styles'  => esc_html__( 'Styles', 'freesoul-deactivate-plugins' ),
	);
	$titles = apply_filters( 'fdp_page_titles', array(
		'common_issues'             => esc_attr__( 'Common issues', 'freesoul-deactivate-plugins' ),
		'shortcuts'                 => esc_attr__( 'Shortcuts', 'freesoul-deactivate-plugins' ),
		'eos_dp_admin_url'          => esc_attr__( 'Backend URLs', 'freesoul-deactivate-plugins' ),
		'eos_dp_admin'              => esc_attr__( 'Backend Singles', 'freesoul-deactivate-plugins' ),
		'eos_dp_ajax'               => esc_attr__( 'Custom Ajax Actions', 'freesoul-deactivate-plugins' ),
		'eos_dp_cron'               => esc_attr__( 'Cron Jobs', 'freesoul-deactivate-plugins' ),
		'eos_dp_rest_api'           => esc_attr__( 'Rest API', 'freesoul-deactivate-plugins' ),
		'eos_dp_by_post_requests'   => esc_attr__( 'Post Requests', 'freesoul-deactivate-plugins' ),
		'eos_dp_translation_urls'   => esc_attr__( 'Translation URLs', 'freesoul-deactivate-plugins' ),
		'eos_dp_by_archive'         => esc_attr__( 'Archives', 'freesoul-deactivate-plugins' ),
		'eos_dp_by_post_type'       => esc_attr__( 'Post Types', 'freesoul-deactivate-plugins' ),
		'eos_dp_by_term_archive'    => esc_attr__( 'Terms Archives', 'freesoul-deactivate-plugins' ),
		'eos_dp_create_plugin'      => esc_attr__( 'New Plugin', 'freesoul-deactivate-plugins' ),
		'eos_dp_documentation'      => esc_attr__( 'Documentation', 'freesoul-deactivate-plugins' ),
		'eos_dp_favorite_plugins'   => esc_attr__( 'Favorite Plugins', 'freesoul-deactivate-plugins' ),
		'eos_dp_firing_order'       => esc_attr__( 'Firing Order', 'freesoul-deactivate-plugins' ),
		'eos_dp_help'               => esc_attr__( 'Help', 'freesoul-deactivate-plugins' ),
		'eos_dp_menu'               => esc_attr__( 'Singles', 'freesoul-deactivate-plugins' ),
		'eos_dp_mobile'             => esc_attr__( 'Mobile', 'freesoul-deactivate-plugins' ),
		'eos_dp_desktop'            => esc_attr__( 'Desktop', 'freesoul-deactivate-plugins' ),
		'eos_dp_one_place'          => esc_attr__( 'By URL', 'freesoul-deactivate-plugins' ),
		'eos_dp_browser'        	=> esc_attr__( 'User Agent', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_bulk_actions'   => esc_attr__( 'Bulk Actions', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_general_bloat'  => esc_attr__( 'General bloat', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_import_export'  => esc_attr__( 'Settings Import/Export', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_hooks_recorder' => esc_attr__( 'Hooks Recorder', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_plugins'        => esc_attr__( 'Plugin Settings', 'freesoul-deactivate-plugins' ),
		'eos_dp_pro_settings'       => esc_attr__( 'Events', 'freesoul-deactivate-plugins' ),
		'eos_dp_report'             => esc_attr__( 'Reports', 'freesoul-deactivate-plugins' ),
		'eos_dp_roles_manager'      => esc_attr__( 'Role Manager', 'freesoul-deactivate-plugins' ),
		'eos_dp_search'             => esc_attr__( 'Search', 'freesoul-deactivate-plugins' ),
		'eos_dp_smoke_tests'        => esc_attr__( 'Plugin Tests', 'freesoul-deactivate-plugins' ),
		'eos_dp_testing'            => esc_attr__( 'Testing Settings', 'freesoul-deactivate-plugins' ),
		'eos_dp_url'                => esc_attr__( 'Custom URLs', 'freesoul-deactivate-plugins' ),
		'flowchart'                 => esc_attr__( 'Options priorities', 'freesoul-deactivate-plugins' ),
		'eos_dp_by_plugin'          => esc_attr__( 'By Plugin', 'freesoul-deactivate-plugins' ),
		'eos_dp_plugin_conflicts'   => esc_attr__( 'Plugin Conflicts', 'freesoul-deactivate-plugins' ),
	) );
	$label = '';
	if( isset( $_GET['eos_dp_post_type' ] ) ){
		$post_type = get_post_type_object( sanitize_text_field( $_GET['eos_dp_post_type' ] ) );
		if( $post_type ){
			$label = ' | ' . $post_type->labels->name;
		}
	}
	elseif( isset( $_GET['eos_dp_tax' ] ) && isset( $_GET['tpt'] ) ){
		$tax = get_taxonomy( sanitize_text_field( $_GET['eos_dp_tax' ] ) );
		if( $tax ){
			$label = ' | ' . $tax->label;
			$post_type = get_post_type_object( sanitize_text_field( $_GET['tpt' ] ) );
			if( $post_type ){
				$label .= ' (' . $post_type->labels->name . ')';
			}
		}
	}
	if ( isset( $_GET['asset_type'] ) && in_array( $_GET['asset_type'], array_keys( $labels ) ) ) {
		$titles['eos_dp_pro_assets'] = sprintf( esc_attr__( 'Assets | %s', 'freesoul-deactivate-plugins' ), esc_html( $labels[ sanitize_text_field( $_GET['asset_type'] ) ] ) );
	}
	if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array_keys( $titles ) ) ) {
		if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
			return '&#128268; ' . esc_attr__( 'Homepage', 'freesoul-deactivate-plugins' ) . esc_attr( apply_filters( 'fdp_after_admin_title', $label ) );
		}
		if ( isset( $_GET['tab'] ) ) {
			return '&#128268; ' . esc_html( sprintf( esc_attr__( '%1$s | %2$s', 'freesoul-deactivate-plugins' ), $titles[ sanitize_text_field( $_GET['page'] ) ], $titles[ sanitize_text_field( $_GET['tab'] ) ] ) . apply_filters( 'fdp_after_admin_title', $label ) );
		}
		return '&#128268; ' . esc_html( $titles[ sanitize_text_field( $_GET['page'] ) ] . apply_filters( 'fdp_after_admin_title', $label ) );
	}
	if ( isset( $_GET['eos_dp_info'] ) && 'true' == $_GET['eos_dp_info'] ) {
		if ( isset( $_GET['plugin'] ) ) {
			return esc_attr__( 'Plugin Details', 'freesoul-deactivate-plugins' );
		}
	}
	return $title;
}

// Remove other admin notices on the settings pages.
function eos_dp_remove_other_admin_notices() {
	remove_all_actions( 'admin_notices' );
	remove_all_actions( 'network_admin_notices' );
	remove_all_actions( 'all_admin_notices' );
	remove_all_actions( 'user_admin_notices' );
	add_action( 'admin_notices', 'eos_dp_admin_notices' );
}

if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'eos_dp_admin', 'eos_dp_ajax', 'eos_dp_by_post_requests', 'eos_dp_rest_api', 'eos_dp_integration' ) ) ) {
	add_action( 'eos_dp_after_table_head_columns', 'eos_dp_add_theme_to_table_head' );
}
// It adds the theme column in the table header.
function eos_dp_add_theme_to_table_head() {
	$theme = wp_get_theme();
	if ( ! is_object( $theme ) ) {
		return;
	}
	$theme_name       = strtoupper( $theme->get( 'Name' ) );
	$theme_name_short = substr( $theme_name, 0, 28 );
	$theme_name_short = $theme_name === $theme_name_short ? $theme_name : strtoupper( $theme_name_short ) . ' ...';
	?>
	<th class="eos-dp-name-th eos-dp-name-th-theme">
		<div>
			<div id="eos-dp-theme-name" class="eos-dp-theme-name" data-theme="<?php echo esc_attr( $theme->get( 'TextDomain' ) ); ?>" title="<?php echo esc_attr( $theme_name ); ?>" data-path="<?php echo esc_attr( get_stylesheet_directory_uri() ); ?>">
				<span><?php echo esc_html( $theme_name_short ); ?></span>
			</div>
			<div id="eos-dp-global-chk-col-wrp" class="eos-dp-global-chk-col-wrp">
				<div class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate %s everywhere', 'freesoul-deactivate-plugins' ), esc_attr( $theme_name ) ); ?>" data-col="theme" class="eos-dp-global-chk-col" type="checkbox" /></div>
			</div>
			<div class="fdp-p-n">1</div>
		</div>
	</th>
	<?php
}

add_action( 'eos_dp_pre_table_head', 'eos_dp_pro_nonces' );
// It displays the auto settings button and related messages.
function eos_dp_pro_nonces() {
	wp_nonce_field( 'eos_dp_pro_auto_settings', 'eos_dp_pro_auto_settings' );
	wp_nonce_field( 'eos_dp_plugins_contributions', 'eos_dp_plugins_contributions' );
	wp_nonce_field( 'eos_dp_pro_errors_check', 'eos_dp_pro_errors_check' );
	wp_nonce_field( 'eos_dp_pro_gt_metrix_test', 'eos_dp_pro_gt_metrix_test' );
	wp_nonce_field( 'eos_dp_pro_gpsi_test', 'eos_dp_pro_gpsi_test' );

}

add_action( 'eos_dp_action_buttons', 'eos_dp_home_autosuggest_action_buttons', 10 );

// It adds premium action buttons.
function eos_dp_home_autosuggest_action_buttons() {
	if ( isset( $_GET['eos_dp_home'] ) ) :
		?>
	<a href="#" class="eos-dp-pro-autosettings" title="<?php esc_attr_e( 'Suggest plugins', 'freesoul-deactivate-plugins' ); ?>"><span class="dashicons dashicons-plugins-checked"></span></a>
		<?php
	endif;
}

add_filter( 'admin_body_class', 'eos_dp_admin_body_class' );
// Adds the class to the body tag in the dashboard according the options page.
function eos_dp_admin_body_class( $classes ) {
	if ( isset( $_GET['page'] ) && eos_dp_is_fdp_page() || isset( $_GET['fdp_add_favorites'] ) || ( isset( $_GET['page'] ) && 'eos_dp_code_browser' === $_GET['page'] ) ) {
		global $fdp_plugins_count;
		$classes .= isset( $_GET['page'] ) ? ' eos-dp-' . esc_attr( sanitize_text_field( $_GET['page'] ) ) : '';
		$classes .= ' fdp';
		$classes .= defined( 'EOS_DP_PRO_VERSION' ) ? ' fdp-pro fdp-pro-' . esc_attr( str_replace( '.', '-', EOS_DP_PRO_VERSION ) ) : ' fdp-free fdp-free-' . esc_attr( str_replace( '.', '-', EOS_DP_VERSION ) );
		if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
			$classes .= ' eos-dp-homepage';
		}
		if ( ( isset( $_GET['full-screen'] ) && 'true' === $_GET['full-screen'] ) || ( isset( $_COOKIE['fdp-full-screen'] ) && 'true' === $_COOKIE['fdp-full-screen'] ) ) {
			$classes .= ' fdp-full-screen';
		}
		if ( $fdp_plugins_count > 15 ) {
			$classes .= ' eos-dp-more-than-15-plugins';
			if ( $fdp_plugins_count > 25 ) {
				$classes .= ' fdp-more-than-25-plugins';
				if ( false === strpos( $classes, ' folded' ) ) {
					$classes .= ' folded';
				}
			}
		} else {
			$classes .= ' eos-dp-less-than-15-plugins';
		}
		$classes .= isset( $_GET['page'] ) ? ' fdp-' . esc_attr( sanitize_text_field( $_GET['page'] ) ) : '';
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'eos_dp_one_place', 'eos_dp_browser', 'eos_dp_mobile', 'eos_dp_desktop', 'eos_dp_search' ) ) ) {
			$classes .= ' fdp-one-column';
		}
		if ( isset( $_GET['page'] ) && in_array( $_GET['page'], eos_dp_sortable_pages() ) ) {
			$classes .= ' fdp-sortable-page';
		}
		$main_opts = eos_dp_get_option( 'eos_dp_pro_main' );
		$suff      = substr( sanitize_key( md5( ABSPATH ) ), 0, 4 );
		if ( $main_opts && isset( $main_opts[ 'license_validity_' . $suff ] ) && 'not_valid' === $main_opts[ 'license_validity_' . $suff ] ) {
			$classes .= ' fdp-pro-unvalid-' . $suff;
		}
	}
	return $classes;
}

if ( isset( $_GET['eos_dp_preview'] ) && isset( $_GET['js'] ) && 'off' === $_GET['js'] ) {
	add_action( 'admin_head', 'eos_dp_disable_javascript', 10 );
}

add_action( 'admin_init', 'eos_dp_redirect_home_settings' );
function eos_dp_redirect_home_settings() {
	// Redirect to homepage settings.
	if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
		$show_on_front = eos_dp_get_option( 'show_on_front' );
		if ( isset( $_GET['page'] ) && 'eos_dp_menu' === $_GET['page'] && 'posts' === $show_on_front ) {
			wp_safe_redirect( admin_url( 'admin.php?page=eos_dp_by_archive&eos_dp_home=true' ) );
			exit;
		}
		if ( isset( $_GET['page'] ) && 'eos_dp_by_archive' === $_GET['page'] && 'page' === $show_on_front ) {
			if ( absint( eos_dp_get_option( 'page_on_front' ) ) > 0 ) {
				wp_safe_redirect( admin_url( 'admin.php?page=eos_dp_menu&eos_dp_home=true' ) );
				exit;
			}
		}
	}
}

add_filter( 'bulk_actions-edit-post', 'eos_dp_my_bulk_actions' );
add_filter( 'bulk_actions-edit-page', 'eos_dp_my_bulk_actions' );
add_filter( 'bulk_actions-edit-product', 'eos_dp_my_bulk_actions' );
 // Add bulk action to disable unused plugins on posts, pages, and products if any.
function eos_dp_my_bulk_actions( $actions ) {
	$actions['eos_dp_disable_plugins'] = esc_attr__( 'Set unused plugins', 'freesoul-deactivate-plugins' );
	return $actions;

}

add_action( 'handle_bulk_actions-edit-post', 'eos_dp_bulk_action_handler', 10, 3 );
add_action( 'handle_bulk_actions-edit-page', 'eos_dp_bulk_action_handler', 10, 3 );
add_action( 'handle_bulk_actions-edit-product', 'eos_dp_bulk_action_handler', 10, 3 );
// Handle bulk action to disable plugins on posts, pages, and products if any.
function eos_dp_bulk_action_handler( $redirect, $action, $ids ) {
	if ( 'eos_dp_disable_plugins' === $action && ! empty( $ids ) ) {
		$post_type = get_post_type( $ids[0] );
		$redirect  = add_query_arg(
			array(
				'eos_dp_post_type' => $post_type,
				'eos_dp_post_in'   => implode( '-', $ids ),
				'posts_per_page'   => count( $ids ),
			),
			admin_url( 'admin.php?page=eos_dp_menu' )
		);
		wp_redirect( $redirect );
		exit;
	}
	return $redirect;
}

add_filter( 'eos_dp_user_can_metabox', 'eos_dp_pro_can_metabox' );
// Return if current user can see the FDP section in single post_status.
function eos_dp_pro_can_metabox( $can ) {
	$fdp_caps = eos_dp_user_capabilities();
	if ( $fdp_caps && is_array( $fdp_caps ) && in_array( 'single_settings', array_keys( $fdp_caps ) ) && ! $fdp_caps['single_settings'] ) {
		return false;
	}
	return $can;
}

add_action( 'admin_menu', 'eos_dp_pro_admin_menu_filters' );
// Fire filters in admmin_menu actions.
function eos_dp_pro_admin_menu_filters() {
	add_filter( 'eos_dp_user_can_settings', 'eos_dp_pro_can_settings' );
}

// Return if current user can see the FDP settings.
function eos_dp_pro_can_settings( $can ) {
	$fdp_caps = eos_dp_user_capabilities();
	if ( $fdp_caps && is_array( $fdp_caps ) && in_array( 'global_settings', $fdp_caps ) && ! $fdp_caps['global_settings'] ) {
		return false;
	}
	return $can;
}

add_filter( 'all_plugins', 'eos_dp_plugins_in_list' );
// Remove plugins from plugins table in the page wp-admin/plugins.php according to the FDP Settings.
function eos_dp_plugins_in_list( $plugins ) {
	$fdp_caps = eos_dp_user_capabilities();
	if ( $fdp_caps && is_array( $fdp_caps ) && in_array( 'see_plugin', $fdp_caps ) && ! $fdp_caps['see_plugin'] ) {
		if ( in_array( EOS_DP_PLUGIN_BASE_NAME, array_keys( $plugins ) ) ) {
			unset( $plugins[ EOS_DP_PLUGIN_BASE_NAME ] );
		}
		if ( in_array( EOS_DP_PRO_PLUGIN_BASE_NAME, array_keys( $plugins ) ) ) {
			unset( $plugins[ EOS_DP_PRO_PLUGIN_BASE_NAME ] );
		}
	}
	return $plugins;
}

add_action( 'admin_menu', 'eos_dp_remove_menu_items' );
// Remove menu items for the Plugins manager.
function eos_dp_remove_menu_items() {
	$current_user = wp_get_current_user();
	if ( in_array( 'fdp_plugins_manager', array_keys( $current_user->caps ) ) ) {
		remove_menu_page( 'plugins.php' );
		remove_menu_page( 'options-general.php' );
		if ( ( isset( $GLOBALS['pagenow'] ) && 'plugins.php' === $GLOBALS['pagenow'] ) || ( isset( $_GET['page'] ) && 'eos_dp_create_plugin' === $_GET['page'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=eos_dp_by_post_type' ) );
			exit;
		}
	}
}

if ( isset( $_GET['fdp_add_favorites'] ) && 'true' === $_GET['fdp_add_favorites'] ) {
	// Actions and filters to clean the page of plugins.
	add_filter( 'admin_body_class', 'eos_dp_favorite_plugins_add_admin_body_class' );
	add_action( 'admin_menu', 'eos_dp_clean_plugins_page', 99999999 );
	add_action( 'admin_bar_menu', 'eos_dp_clean_top_bar', 999999 );
	add_action( 'install_plugins_pre_upload', 'eos_dp_favorite_plugins' );
}
if ( isset( $_GET['page'] ) && 'eos_dp_firing_order' === $_GET['page'] ) {
	// Inline style for the firing order page.
	add_action( 'admin_head', 'eos_dp_firing_order_inline' );
}
if ( isset( $_GET['page'] ) && false !== strpos( sanitize_text_field( $_GET['page'] ), 'eos_dp_' ) || ( isset( $_GET['fdp_add_favorites'] ) ) ) {
	// Clean FDP backend pages and add inline style.
	add_action(
		'admin_print_scripts',
		function() {
			remove_all_actions( 'admin_head' );
			do_action( 'fdp_after_admin_head_removed' );
			remove_all_actions( 'admin_footer' );
			if ( function_exists( 'eos_dp_pro_scripts' ) ) {
				add_action( 'admin_footer', 'eos_dp_pro_scripts', 9999 );
			}
			do_action( 'fdp_after_admin_footer_removed' );
			add_action( 'admin_head', 'eos_dp_admin_head' );
		}
	);
}
// General inline style for the backend and admin head metas.
function eos_dp_admin_head() {
	$rtl = is_rtl() ? '-rtl' : '';
	?>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0" />
	<?php
	// Enqueue style for backend.
	eos_dp_link_style( 'fdp-admin-style', EOS_DP_MAIN_STYLESHEET . $rtl . '.css', 'all' );
	add_action(
		'admin_footer',
		function() {
			$rtl = is_rtl() ? '-rtl' : '';
			eos_dp_link_style( 'fdp-admin-topbar', EOS_DP_PLUGIN_URL . '/admin/assets/css/fdp-adminbar' . $rtl . '.css', 'all' );
		}
	);
	?>
	<style id="fdp-plugin-filter-css" type="text/css"></style>
	<style id="fdp-inline-backend-css" type="text/css">
	ul#wf-onboarding-banner{display: none !important}
	.fdp-plugins-filter-list li:hover{opacity:0.7}
	.fdp-no-jquery #wpwrap #wpcontent #wpbody #wpbody-content .notice.fdp-no-jquery{display:block !important}
	.fdp #adminmenumain [href="#wpbody-content"]{position:absolute;top:-1000em}
	#wp-auth-check-form.loading:before{content:"";display:block;width:20px;height:20px;position:absolute;left:50%;top:50%;margin:-10px 0 0 -10px;background:url(<?php echo esc_url( includes_url( '/images/spinner.gif' ) ); ?>) no-repeat center;background-size:20px 20px;transform:translateZ(0);}
	@media print,(-webkit-min-device-pixel-ratio:1.25),(min-resolution:120dpi){
		#wp-auth-check-form.loading:before{background-image:url(<?php echo esc_url( includes_url( '/images/spinner-2x.gif' ) ); ?>)}
	}
	<?php
	ob_start();
	require_once EOS_DP_PLUGIN_DIR . '/admin/assets/css/fdp-admin-dynamic-css' . $rtl . '.php';
	echo sanitize_text_field( ob_get_clean() ); //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on the CSS.
	do_action( 'fdp_after_general_inline_style' );
	?>
	</style>
	<?php
	eos_dp_add_admin_inline_style();
}
// Inline style for the firing order page.
function eos_dp_firing_order_inline() {
	?>
	<style id="fdp-firing-order" type="text/css">.eos-dp-firing-order .eos-dp-plugin{height:32px;padding:6px 0;border:none;margin:3px 0;max-width:600px}</style>
	<?php
}
// Favorite plugins input field.
function eos_dp_favorite_plugins() {
	wp_create_nonce( 'eos_dp_export_favorites_list', 'eos_dp_export_favorites_list' );
	?>
	<input type="hidden" id="fdp_favorites_list" name="fdp_favorites_list" />
	<?php
}
// Add inline style.
function eos_dp_favorite_plugins_inline() {
	eos_dp_add_inline_script( 'fdp-favorite-plugins', EOS_DP_PLUGIN_URL . '/admin/assets/js/fdp-favorites.js' );
	$extra_style    = '';
	$active_plugins = eos_dp_active_plugins();
	foreach ( $active_plugins as $plugin ) {
		$extra_style .= '.fdp-favorite-plugins .plugin-card-' . esc_attr( dirname( $plugin ) ) . ',';
	}
	$extra_style = rtrim( $extra_style, ',' );
	if ( '' !== $extra_style ) {
		$extra_style .= '{opacity:0.3;pointer-events:none}';
	}
	?>
	<style id="fdp-favorite-plugins-css" type="text/css">
	.fdp-favorite-plugins .plugin-card,.fdp-favorite-plugins .plugin-card.fdp-added-to-favorites:hover{cursor:pointer;opacity:0.4}
	.fdp-favorite-plugins .plugin-card a{pointer-events:none;font-size:14px}
	.fdp-favorite-plugins .plugin-card:hover,.fdp-favorite-plugins .fdp-added-to-favorites{opacity:1}
	.fdp-favorite-plugins h1.wp-heading-inline,.fdp-favorite-plugins .filter-links,.fdp-favorite-plugins .tablenav,.fdp-favorite-plugins .desc p:not(.authors),.fdp-favorite-plugins .action-links,.fdp-favorite-plugins .plugin-card-bottom,.fdp-favorite-plugins #wpfooter,.fdp-favorite-plugins .upload-view-toggle,.fdp-favorite-plugins #contextual-help-link,.fdp-favorite-plugins #wpadminbar,.fdp-favorite-plugins #adminmenumain,.fdp-favorite-plugins #wpadminbar{display:none !important}
	.fdp-favorite-plugins .plugin-icon{position:absolute;display:block;width:64px;height:64px;margin: auto auto;left: 50%;margin-left:-32px;bottom:0}
	.fdp-favorite-plugins .plugin-card .name,.fdp-favorite-plugins .plugin-card .desc{margin-left:0;margin-right:0}
	.fdp-favorite-plugins .plugin-card{width:24%}
	.fdp-favorite-plugins .plugin-card-top{min-height:180px}
	.fdp-favorite-plugins .plugin-card{clear:none !important}
	.fdp-favorite-plugins p.authors, .fdp-favorite-plugins p.authors a{font-size:10px}
	.fdp-favorite-plugins .plugin-card h3{min-height:150px}
	.fdp-favorite-plugins form#plugin-filter{margin-top:16px}
	.fdp-favorite-plugins #wpcontent{margin-left: auto}
	.fdp-favorite-plugins .wp-filter{position:fixed;z-index:999999;margin-top:0}
	.fdp-favorite-plugins #the-list{margin-top:75px;text-align:center}
	<?php
	echo $extra_style; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while filling $extra_style.
	?>
	</style>
	<?php
}
// Add download link to admin top  bar.
function eos_dp_clean_top_bar( $wp_admin_bar ) {
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();
	foreach ( $all_toolbar_nodes as $node ) {
		$wp_admin_bar->remove_node( $node->id );
	}
	return $wp_admin_bar;
}

// Clean page of plugins.
function eos_dp_clean_plugins_page() {
	remove_all_actions( 'admin_menu' );
	remove_all_actions( 'admin_notices' );
	remove_all_actions( 'network_admin_notices' );
	remove_all_actions( 'all_admin_notices' );
	remove_all_actions( 'user_admin_notices' );
	remove_all_actions( 'admin_footer' );
}

// Add admin body class in the page of plugins.
function eos_dp_favorite_plugins_add_admin_body_class( $classes ) {
	$classes .= ' fdp-favorite-plugins';
	return $classes;
}

// Return iframe to search plugins.
function eos_dp_get_plugins_iframe() {
	return '<iframe style="width:100%;min-height:800px" src="' . esc_url( admin_url( 'plugin-install.php?tab=search&type=term&fdp_add_favorites=true' ) ) . '"></iframe>';
}

add_filter( 'plugin_install_action_links', 'eos_dp_plugin_action_links', 10, 2 );
// Add useful links in the plugins pages.
function eos_dp_plugin_action_links( $action_links, $plugin ) {
	$action_links[] = '<a href="' . add_query_arg( 'text', $plugin['slug'], 'https://wpscan.com/search' ) . '" target="_blank" rel="noopener">' . esc_html__( 'Vulnerabilities', 'freesoul-deactivate-plugins' ) . '</a>';
	$action_links[] = '<a href="https://plugintests.com/plugins/wporg/' . $plugin['slug'] . '/latest" target="_blank" rel="noopener">' . esc_html__( 'Smoke tests', 'freesoul-deactivate-plugins' ) . '</a>';
	return $action_links;
}

register_activation_hook(
	EOS_DP_PLUGIN_BASE_NAME,
	function() {
		// It sends an ID to the FDP site to update the active number of installations. Thanks to the md5 function the FDP server will not be able to guess the home url, but it understands the plugin was deactivated on an anonymus site.
		$args     = array(
			'headers' => array( 'site_id' => md5( get_home_url() ) ),
			'timeout' => 3,
		);
		$response = wp_remote_get( 'https://shop.freesoul-deactivate-plugins.com/stats/activated/', $args );
	}
);

register_deactivation_hook(
	EOS_DP_PLUGIN_BASE_NAME,
	function() {
		// It sends an ID to the FDP site to update the active number of installations. Thanks to the md5 function the FDP server will not be able to guess the home url, but it understands the plugin was deactivated on an anonymus site.
		$args     = array(
			'headers' => array( 'site_id' => md5( get_home_url() ) ),
			'timeout' => 3,
		);
		$response = wp_remote_get( 'https://shop.freesoul-deactivate-plugins.com/stats/deactivated/', $args );
	}
);

add_action(
	'plugins_loaded',
	function() {
		// Prevent the theme is disabled in the FDP settings pages (e.g. Oxygen).
		if ( ! eos_dp_is_fdp_page() ) {
			return;
		}
		remove_all_filters( 'template_directory' );
		remove_all_filters( 'stylesheet_directory' );
		remove_all_filters( 'template' );
		remove_all_filters( 'template_include' );
	}
);

add_action( 'admin_init', 'eos_dp_clean_settings_pages' );
// Clean FDP settings pages.
function eos_dp_clean_settings_pages() {
	if ( ! eos_dp_is_fdp_page() ) {
		return;
	}
	remove_all_actions( 'current_screen' );
}

add_filter( 'update_footer', 'eos_dp_admin_footer', 20 );
// Add plugin name and version to admin footer.
function eos_dp_admin_footer( $text ) {
	if ( eos_dp_is_fdp_page() ) {
		return;
	}
	return $text;
}

add_action(
	'admin_init',
	function() {
		// Send headers to preload assets.
		if ( eos_dp_is_fdp_page() ) {
			$rtl  = is_rtl() ? '-rtl' : '';
			$urls = array(
				EOS_DP_PLUGIN_URL . '/admin/assets/img/wordpress-deactivate-plugins.png',
			);
			if ( isset( $_GET['page'] ) && 'eos_dp_menu' === $_GET['page'] ) {
				$urls[] = EOS_DP_PLUGIN_URL . '/admin/assets/img/switch.svg';
			}
			$headers = '';
			foreach ( $urls as $url ) {
				$headers .= 'Link: <' . esc_url( $url ) . '> rel=preload as=image;';
			}
			$urls = array(
				EOS_DP_MAIN_STYLESHEET . $rtl . '.css',
			);
			foreach ( $urls as $url ) {
				$headers .= 'Link: <' . esc_url( $url ) . '> rel=preload as=style;';
			}
			$headers .= 'Accept-Encoding: gzip, compress, br;';
			header( apply_filters( 'fdp_admin_headers', $headers ) );
			add_filter( 'admin_footer_text', '__return_false' );
		}
	},
	100
);

// Noice about the incoming PRO version.
function eos_dp_pro_version_notice( $position = 'fixed' ) {
	if ( ! defined( 'FDP_PRO_ACTIVE' ) ) {
		$user_meta = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
		if ( is_array( $user_meta ) ) {
			$dismissed = $user_meta;
		} elseif ( is_string( $user_meta ) ) {
			$dismissed = explode( ',', $user_meta );
		} else {
			$dismissed = array();
		}
		$installation_info = get_option( 'eos_dp_activation_info' );
		if ( $installation_info && isset( $installation_info['time'] ) ) {
			$start = '02 December 2021 00:00';
			$end   = '12 December 2021 00:00';
			$from  = strtotime( $start );
			$until = strtotime( $end );
			$now   = current_time( 'timestamp' );
			if ( $now >= $from && $now <= $until ) {
				$dismissed = is_array( $user_meta ) ? $user_meta : explode( ',', $user_meta );
				if ( ! in_array( 'fdp-pro-ready', $dismissed ) ) {
					?>
				<div id="fdp-pro-ready" class="fdp-pro-notice" style="position:<?php echo 'fixed' === $position ? 'fixed;bottom:-100%;' : 'relative'; ?>;transition:bottom 2s linear;z-index:999999999;margin-left:0;margin-right:0;margin-top:32px;background-color:#3e6d7c;padding:20px;font-size:16px;display:inline-block !important;line-height:1.5">
					<a href="#" class="dashicons dashicons-no-alt" title="Close" style="cursor:pointer;position:absolute;top:2px;<?php echo is_rtl() ? 'left' : 'right'; ?>:8px;font-size:27px;color:#fff;text-decoration:none" onclick="fdp_notice = document.getElementById('fdp-pro-ready');fdp_notice.style.bottom = '-100%';fdp_notice.style.position='fixed';return false;"></a>
					<p style="color:#fff !important;font-size:18px">The PRO version is ready! Use the coupon code <strong>optimizelikeapro</strong> before December 12 to get access to the premium features with a 30% discount.</p>
					<p style="text-align:<?php echo is_rtl() ? 'left' : 'right'; ?>">
						<a class="button" style="background-color:#a28754;color:#fff;border-color:transparent;font-size:14px;text-transform:uppercase" href="https://shop.freesoul-deactivate-plugins.com" rel="noopener" target="_blank">Get the PRO version</a>
					</p>
					<p style="color:#fff !important" class="right">
						<a class="fdp-dismiss-pro-notice" title="Close and don't show it again" style="color:#fff" href="#" data-pointer-id="fdp-pro-ready">Don't show again</a>
					</p>
				</div>
					<?php if ( 'fixed' === $position ) { ?>
				<script>setTimeout(function(){document.getElementById('fdp-pro-ready').style.bottom = '0';},6000);</script>
				<?php } ?>
					<?php
				}
			}
		}
	}
}

add_action( 'set_site_transient_update_plugins', 'eos_dp_check_license_on_update_plugins' );
add_action( 'set_transient_update_plugins', 'eos_dp_check_license_on_update_plugins' );
// Check FDP PRO license validity.
function eos_dp_check_license_on_update_plugins( $transient ) {
	if ( defined( 'EOS_DP_PRO_PLUGIN_DIR' ) && ! wp_doing_ajax() ) {
		if ( defined( 'FDP_PRO_LICENSE_EDD' ) && FDP_PRO_LICENSE_EDD ) {
			$licenseCode = get_option( 'eos_dp_pro_edd_license_key' );
			if ( $licenseCode ) {
				define( 'EDD_SAMPLE_STORE_URL', 'https://shop.freesoul-deactivate-olugins.com/edd?fdp-edd-lic=true' );
				define( 'EDD_SAMPLE_ITEM_ID', 2672 );
				require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-license-manager-edd.php';
				$edd_updater = new FDP_EDD_SL_Plugin_Updater(
					EDD_SAMPLE_STORE_URL,
					EOS_DP_PRO_PLUGIN_FILE,
					array(
						'version' => EOS_DP_PRO_VERSION,
						'license' => $licenseCode,
						'item_id' => EDD_SAMPLE_ITEM_ID,
						'author'  => 'Jose Mortellaro',
						'beta'    => false,
					)
				);
			} else {
				add_action( 'admin_notices', 'eos_dp_license_not_valid' );
			}
		} else {
			$main_opts    = eos_dp_get_option( 'eos_dp_pro_main' );
			$licenseA     = isset( $main_opts['eos_dp_license'] ) ? $main_opts['eos_dp_license'] : false;
			$licenseCode  = $licenseA && isset( $licenseA['fdp-license-key'] ) ? esc_attr( $licenseA['fdp-license-key'] ) : '';
			$licenseEmail = $licenseA && isset( $licenseA['fdp-license-email'] ) ? sanitize_email( $licenseA['fdp-license-email'] ) : '';
			if ( '' !== $licenseCode && '' !== $licenseEmail ) {
				require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-license-manager.php';
				FDPProLicenseManager::addOnDelete(
					function() {
						delete_option( 'FreesoulDeactivatePluginsPRO_lic_Key' );
					}
				);
				if ( FDPProLicenseManager::CheckWPPlugin( $licenseCode, $licenseEmail, $error, $responseObj, EOS_DP_PRO_PLUGIN_FILE ) ) {
					if ( ! isset( $responseObj->is_valid ) || ! $responseObj->is_valid ) {
						add_action( 'admin_notices', 'eos_dp_license_not_valid' );
					} elseif ( isset( $responseObj->is_valid ) && $responseObj->is_valid && isset( $responseObj->expire_date ) ) {
						if ( 'No expiry' !== $responseObj->expire_date && time() > strtotime( $responseObj->expire_date ) ) {
							add_action( 'admin_notices', 'eos_dp_license_expired' );
						}
					}
					if ( isset( $responseObj->is_valid ) && $responseObj->is_valid ) {
						$suff                                     = substr( sanitize_key( md5( ABSPATH ) ), 0, 4 );
						$main_opts[ 'license_validity_' . $suff ] = 'valid';
						eos_dp_update_option( 'eos_dp_pro_main', $main_opts );
					}
				}
			} else {
				add_action( 'admin_notices', 'eos_dp_license_not_valid' );
			}
		}

	}
}

// License not valid notifice.
function eos_dp_license_not_valid() {
	$main_opts     = eos_dp_get_option( 'eos_dp_pro_main' );
	$licenseA      = isset( $main_opts['eos_dp_license'] ) ? $main_opts['eos_dp_license'] : false;
	$licenseCode   = $licenseA && isset( $licenseA['fdp-license-key'] ) ? esc_attr( $licenseA['fdp-license-key'] ) : '';
	$orders_link   = 'https://shop.freesoul-deactivate-plugins.com/my-account/orders/';
	$shop_link     = 'https://shop.freesoul-deactivate-plugins.com/pricing/';
	$ksesArgs      = array(
		'a' => array(
			'href'   => array(),
			'class'  => array(),
			'rel'    => array(),
			'target' => array(),
		),
	);
	$lic_setts_url = admin_url( 'admin.php?page=eos_dp_pro_license' );
	?>
	<div class="notice notice-error">
		<?php if ( $licenseCode && '' !== $licenseCode ) { ?>
		<p><?php esc_html_e( 'The license of Freesoul Deactivate Plugins PRO is not valid.', 'eos-dp-pro' ); ?></p>
		<p><a href="<?php echo esc_url( $lic_setts_url ); ?>"><?php esc_html_e( 'Check license settings', 'eos-dp-pro' ); ?></a></p>
		<?php } else { ?>
			<p><?php echo wp_kses( sprintf( __( 'To get full access to the premium features and updates of Freesoul Deactivate Plugins PRO you should %1$sactivate the license%2$s.', 'eos-dp-pro' ), '<a href="' . esc_url( $lic_setts_url ) . '">', '</a>' ), $ksesArgs ); ?></p>
			<p><?php echo wp_kses( sprintf( __( 'If you have lost it, have a look at %1$syour orders%2$s.', 'eos-dp-pro' ), '<a href="' . esc_url( $orders_link ) . '" target="_blank" rel="noopener">', '</a>' ), $ksesArgs ); ?></p>
			<?php
			$main_opts                                = eos_dp_get_option( 'eos_dp_pro_main' );
			$suff                                     = substr( sanitize_key( md5( ABSPATH ) ), 0, 4 );
			$main_opts[ 'license_validity_' . $suff ] = 'not_valid';
			eos_dp_update_option( 'eos_dp_pro_main', $main_opts );
		}
		?>
	</div>
	<?php
}

// License not valid notifice.
function eos_dp_license_expired() {
	$main_opts   = eos_dp_get_option( 'eos_dp_pro_main' );
	$licenseA    = $main_opts['eos_dp_license'];
	$licenseCode = $licenseA && isset( $licenseA['fdp-license-key'] ) ? esc_attr( $licenseA['fdp-license-key'] ) : '';
	$renew_link  = add_query_arg(
		array(
			'lic'   => esc_attr( $licenseCode ),
			'type'  => 'l',
			'renew' => 'true',
		),
		'https://shop.freesoul-deactivate-plugins.com/'
	);
	?>
	<div class="notice notice-error">
		<p><?php esc_html_e( 'The license of Freesoul Deactivate Plugins PRO is expired.', 'eos-dp-pro' ); ?></p>
		<p><?php printf( esc_html__( 'For having access to the plugin updates you would need to renew the license. %1$sRenew now%2$s', 'eos-dp-pro' ), '<a class="button" href="' . esc_url( $renew_link ) . '" target="_blank" rel="noopener">', '</a>' ); ?></p>
	</div>
	<?php
}

if ( defined( 'CODE_PROFILER_MU_ON' ) || defined( 'CODE_PROFILER_PRO_MU_ON' ) ) {
	require EOS_DP_PLUGIN_DIR . '/integrations/code-profiler.php';
}

if ( eos_dp_is_fdp_page() && false !== strpos( get_home_url(), '.wpdemo.org' ) ) {
	$active_plugins = eos_dp_active_plugins();
	if ( $active_plugins && 1 === count( $active_plugins ) ) {
		add_filter( 'eos_dp_active_plugins', 'eos_dp_test_get_active_plugins_simulation' );
		add_filter( 'eos_dp_get_plugins', 'eos_dp_test_get_active_plugins_simulation' );
		add_filter( 'eos_dp_post_types_empty', 'eos_dp_test_get_active_plugins_simulation' );
		add_filter( 'eos_dp_get_updated_plugins_table', 'eos_dp_test_get_active_plugins_simulation' );
		function eos_dp_test_get_active_plugins_simulation( $plugins ) {
			$plugins = array();
			for ( $n = 1;$n < 26;++ $n ) {
				$plugins[] = 'dummy-plugin-' . $n . '/dummy-plugin-' . $n . '.php';
			}
			return $plugins;
		}
		add_filter(
			'eos_dp_plugins_table',
			function( $plugins_table ) {
				return eos_dp_post_types_empty();
			}
		);
	}
}

add_filter(
	'loco_plugins_data',
	function( $data ) {
		// Solve conflicts with Loco Translate.
		if ( isset( $data['eos-deactivate-plugins.php'] ) ) {
			unset( $data['eos-deactivate-plugins.php'] );
		}
		return $data;
	}
);

add_action(
	'admin_init',
	function() {
		if ( isset( $_REQUEST['fdp_query_menu'] ) && 'true' === $_REQUEST['fdp_query_menu'] && isset( $_REQUEST['nonce'] ) && isset( $_REQUEST['id'] ) ) {
			$transient_nonce = get_site_transient( 'fdp_query_menu_nonce_' . sanitize_text_field( $_REQUEST['id'] ) );
			if ( $transient_nonce && sanitize_text_field( $transient_nonce ) === sanitize_text_field( $_REQUEST['nonce'] ) ) {
				global $menu,$submenu,$eos_dp_paths,$admin_page_hooks;
				$parent_plugin_pages = array();
				foreach ( $submenu as $parent_page => $arr ) {
					foreach ( $arr as $arr2 ) {
						$hook_name = get_plugin_page_hookname( $arr2[2], $parent_page );
						if ( has_action( $hook_name ) ) {
							$parent_plugin_pages[] = sanitize_text_field( $hook_name );
						}
					}
				}
				echo wp_json_encode(
					array(
						'menu'                => $menu,
						'submenu'             => $submenu,
						'admin_page_hooks'    => $admin_page_hooks,
						'parent_plugin_pages' => $parent_plugin_pages,
					)
				);
				delete_site_transient( 'fdp_query_menu_nonce_' . sanitize_text_field( $_REQUEST['id'] ) );
				die();
				exit;
			}
		}
	}
);

add_action(
	'admin_init',
	function() {
		// Rebuild admin menu if plugins deactivated in the backend page.
		if ( ! isset( $_GET['page'] ) || 'eos_dp_admin' !== $_GET['page'] ) {
			if ( defined( 'FDP_SHOW_ADMIN_MENU_DISABLED_PLUGINS' ) && false === FDP_SHOW_ADMIN_MENU_DISABLED_PLUGINS ) {
				return;
			}
			if ( isset( $GLOBALS['fdp_disabled_plugins_for_user'] ) && ! empty( $GLOBALS['fdp_disabled_plugins_for_user'] ) ) {
				return;
			}
			global $eos_dp_paths;
			if ( $eos_dp_paths && ! empty( $eos_dp_paths ) ) {
				global $current_user;
				if ( $current_user && isset( $current_user->allcaps ) && is_array( $current_user->allcaps ) ) {
					$user_caps = array_keys( array_filter( $current_user->allcaps ) );
					if ( in_array( 'manage_options', $user_caps ) && ! in_array( 'unknownfoobar', $user_caps ) ) {
						$user_caps[] = 'unknownfoobar';
					}
					$nums  = array();
					$count = '';
					if ( ! is_multisite() && $current_user->has_cap( 'update_plugins' ) ) {
						if ( ! isset( $update_data ) ) {
							$update_data = wp_get_update_data();
						}
						$count                = sprintf(
							'<span class="update-plugins count-%s"><span class="plugin-count">%s</span></span>',
							$update_data['counts']['plugins'],
							$update_data['counts']['plugins']
						);
						$nums['menu-plugins'] = sprintf( esc_html__( 'Plugins %s' ), $count );
					}
					if ( $current_user->has_cap( 'edit_posts' ) ) {
						$awaiting_mod          = wp_count_comments();
						$awaiting_mod          = $awaiting_mod->moderated;
						$awaiting_mod_i18n     = number_format_i18n( $awaiting_mod );
						$awaiting_mod_text     = sprintf( _n( '%s Comment in moderation', '%s Comments in moderation', $awaiting_mod ), $awaiting_mod_i18n );
						$nums['menu-comments'] = sprintf( esc_html__( 'Comments %s' ), '<span class="awaiting-mod count-' . absint( $awaiting_mod ) . '"><span class="pending-count" aria-hidden="true">' . $awaiting_mod_i18n . '</span><span class="comments-in-moderation-text screen-reader-text">' . $awaiting_mod_text . '</span></span>' );
						unset( $awaiting_mod );
					}
					if ( ! is_multisite() && current_user_can( 'update_core' ) ) {
						$update_data            = isset( $update_data ) ? $update_data : wp_get_update_data();
						$nums['menu-dashboard'] = sprintf(
							__( 'Updates %s' ),
							sprintf(
								'<span class="update-plugins count-%s"><span class="update-count">%s</span></span>',
								$update_data['counts']['total'],
								number_format_i18n( $update_data['counts']['total'] )
							)
						);
					}
					$fdp_admin_menu       = eos_dp_get_option( 'eos_dp_admin_menu' );
					$fdp_admin_submenu    = eos_dp_get_option( 'eos_dp_admin_submenu' );
					$fdp_admin_page_hooks = eos_dp_get_option( 'eos_dp_admin_page_hooks' );
					if ( $fdp_admin_menu && $fdp_admin_submenu && $fdp_admin_page_hooks ) {
						global $menu,$submenu,$admin_page_hooks,$wp_roles;
						$all_slugs            = array();
						$fdp_admin_page_hooks = json_decode( sanitize_text_field( $fdp_admin_page_hooks ), true );
						$fdp_admin_menu       = json_decode( $fdp_admin_menu, true );
						$fdp_admin_submenu    = json_decode( $fdp_admin_submenu, true );
						$fdp_menu_caps        = array();
						if ( $fdp_admin_menu && $fdp_admin_submenu && $fdp_admin_page_hooks ) {
							$core_menu_slugs     = array( 'menu-dashboard', 'menu-media', 'menu-comments', 'menu-plugins' );
							$core_submenu_slugs  = array( 'index.php' );
							$core_menu           = array();
							$core_submenu        = array();
							$parent_plugin_pages = eos_dp_get_option( 'eos_dp_parent_plugin_pages' );
							$parent_plugin_pages = json_decode( sanitize_text_field( $parent_plugin_pages ), true );
							if ( $parent_plugin_pages && is_array( $parent_plugin_pages ) && ! empty( $parent_plugin_pages ) ) {
								foreach ( $parent_plugin_pages as $hook ) {
									add_action( $hook, '__return_false', 0 );
								}
							}
							foreach ( $menu as $nc => $arrc ) {
								if ( isset( $arrc[5] ) && in_array( $arrc[5], $core_menu_slugs ) ) {
									if ( isset( $nums[ sanitize_key( $arrc[5] ) ] ) ) {
										$arrc[0]     = $nums[ $arrc[5] ];
										$menu[ $nc ] = $arrc;
									}
									$core_menu[ sanitize_key( $arrc[5] ) ] = $arrc;
								}
							}
							foreach ( $submenu as $ns => $arrs ) {
								if ( in_array( $ns, $core_submenu_slugs ) ) {
									$core_submenu[ sanitize_key( $ns ) ] = $arrs;
								}
							}
							foreach ( $fdp_admin_menu as $n => $arr ) {
								if ( in_array( $arr[2], $all_slugs ) ) {
									// Avoid duplicated menu items.
									unset( $fdp_admin_menu[ $n ] );
									continue;
								}
								$all_slugs[]              = $arr[2];
								$fdp_menu_caps[ $arr[2] ] = $arr[1];
								if ( isset( $arr[1] ) && ! in_array( $arr[1], $user_caps ) && ! in_array( 'manage_options', $user_caps ) ) {
									if ( ! in_array( 'fdp_plugins_viewer', $user_caps ) || 'eos_dp_menu' !== $arr[2] ) {
										unset( $fdp_admin_menu[ $n ] );
										continue;
									}
								} elseif ( isset( $arr[5] ) && in_array( $arr[5], $core_menu_slugs ) && isset( $core_menu[ sanitize_key( $arr[5] ) ] ) ) {
									$fdp_admin_menu[ $n ] = $core_menu[ sanitize_key( $arr[5] ) ];
								}
								if ( isset( $arr[5] ) && 'toplevel_page_wpseo_dashboard' === $arr[5] ) {
									$notifications = get_user_option( 'yoast_notifications', $current_user->ID );
									if ( $notifications && is_array( $notifications ) ) {
										$notification_count      = count( array_keys( $notifications ) );
										$fdp_admin_menu[ $n ][0] = sprintf( '%s <span class="update-plugins count-%s"><span class="plugin-count" aria-hidden="true">%s</span></span>', esc_html__( 'Yoast SEO', 'wordpress-seo' ), apply_filters( 'fdp_yoast_notification_count',$notification_count ), apply_filters( 'fdp_yoast_notification_count', $notification_count ) );
									}
								}
							}
							foreach ( $fdp_admin_submenu as $nfs => &$arrfs ) {
								if ( isset( $fdp_menu_caps[ $nfs ] ) ) {
									$parent_cap = $fdp_menu_caps[ $nfs ];
								}
								if ( 'woocommerce' === $nfs && apply_filters( 'woocommerce_include_processing_order_count_in_menu', true ) ) {
									global $wpdb;
									$status      = 'wc-processing';
									$order_count = $wpdb->get_var( "SELECT count(ID)  FROM {$wpdb->prefix}posts WHERE post_status LIKE '$status' AND `post_type` LIKE 'shop_order'" );
									foreach ( $arrfs as $fsN => $wooSubs ) {
										if ( isset( $wooSubs[2] ) && $arrfs[ $fsN ][0] ) {
											$arrfs[ $fsN ][0] = explode( ' <span', $arrfs[ $fsN ][0] )[0];
											if ( 'edit.php?post_type=shop_order' === $wooSubs[2] ) {
												$arrfs[ $fsN ][0] .= ' <span class="awaiting-mod update-plugins count-' . apply_filters( 'fdp_woo_order_count', absint( $order_count ) ) . '"><span class="processing-count">' . number_format_i18n( apply_filters( 'fdp_woo_order_count', $order_count ) ) . '</span></span>';
											}
										}
									}
								}
								if ( isset( $arr[1] ) && ! in_array( $parent_cap, $user_caps ) && ! current_user_can( 'manage_options' ) ) {
									if ( ! in_array( 'fdp_plugins_viewer', $user_caps ) || false === strpos( $nfs, 'eos_dp_menu' ) ) {
										unset( $fdp_admin_submenu[ $nfs ] );
										continue;
									}
								}
								if ( in_array( $nfs, $core_submenu_slugs ) && isset( $core_submenu[ sanitize_key( $nfs ) ] ) ) {
									$fdp_admin_submenu[ $nfs ] = $core_submenu[ sanitize_key( $nfs ) ];
								}
							}
							$menu = $fdp_admin_menu;
							if ( isset( $menu[0][2] ) && 'index.php' === $menu[0][2] ) {
								$menu[0][0] = esc_html__( 'Dashboard' );
							}
							$submenu          = $fdp_admin_submenu;
							$admin_page_hooks = $fdp_admin_page_hooks;
							add_action(
								'in_admin_header',
								function() {
									$output  = '<script>';
									$output .= 'function fdp_correct_admin_menu_links(){';
									$output .= 'var a=document.getElementById("adminmenu").getElementsByTagName("a"),n=0;';
									$output .= 'for(n;n<a.length;++n){';
									$output .= 'if(a[n].className.indexOf("toplevel_page_")>0){';
									$output .= 'if(a[n].href === "' . esc_url( admin_url() ) . '" + a[n].className.split("toplevel_page_")[1].split(" ")[0]){';
									$output .= 'a[n].href="' . esc_url( admin_url( 'admin.php' ) ) . '?page=" + a[n].className.split("toplevel_page_")[1].split(" ")[0];';
									$output .= '}';
									$output .= '}';
									$output .= '}';
									$output .= '}';
									$output .= 'fdp_correct_admin_menu_links();';
									$output .= '</script>';
									echo $output; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while filling $output.
								}
							);
							add_filter(
								'admin_body_class',
								function( $class ) {
									return $class . ' fdp-admin-cleaned';
								}
							);
						}
					}
				}
			}
		}
	}
);

add_action(
	'fdp_after_section',
	function() {
		if ( eos_dp_is_fdp_page() ) {
			$output  = '<script>';
			$output .= 'var fdp_admin_menu = document.getElementById("toplevel_page_eos_dp_menu");';
			$output .= 'if(fdp_admin_menu){';
			$output .= 'fdp_admin_menu.className = fdp_admin_menu.className.replace("wp-has-current-submenu wp-menu-open ","").replace( " wp-has-current-submenu wp-menu-open","") + " wp-has-current-submenu wp-menu-open";';
			$output .= 'fdp_admin_menu.className = fdp_admin_menu.className.replace("wp-not-current-submenu ","").replace( " wp-not-current-submenu","");';
			$output .= 'var fdp_admin_menu_as = document.querySelectorAll(".toplevel_page_eos_dp_menu a");';
			$output .= 'if(fdp_admin_menu_as){';
			$output .= 'fdp_admin_menu_as[0].className = fdp_admin_menu_as[0].className[0].replace("wp-has-current-submenu wp-menu-open ","").replace( " wp-has-current-submenu wp-menu-open","") + " wp-has-current-submenu wp-menu-open";';
			$output .= 'fdp_admin_menu_as[0].className = fdp_admin_menu_as[0].className.replace("wp-not-current-submenu ","").replace( " wp-not-current-submenu","");';
			$output .= '}}';
			$output .= 'var fdp_nav_links = document.getElementById("eos-dp-setts-nav").getElementsByTagName("a");';
			$output .= 'for(var fnl=0;fnl<fdp_nav_links.length;++fnl){fdp_nav_links[fnl].addEventListener("click",function(el){el.target.href+="&fdpnc=" + Date.now();});}';
			$output .= '</script>';
			echo $output; //phpcs:ignore WordPress.Security.EscapeOutput -- Escaping not needed on hardcoded value.
		}
	}
);
add_action(
	'adminmenu',
	function() {
		if ( eos_dp_is_fdp_page() ) {
			$output  = '<style>#collapse-menu{display:none}</style>';
			$output .= '<li id="fdp-collapse-menu" class="hide-if-no-js" onclick="fdp_collapse_admin_menu();">';
			$output .= '<button type="button" id="collapse-button" aria-label="' . esc_attr__( 'Collapse Main menu' ) . '" aria-expanded="true">';
			$output .= '<span class="collapse-button-icon" aria-hidden="true"></span>';
			$output .= '<span class="collapse-button-label">' . esc_html__( 'Collapse menu' ) . '</span>';
			$output .= '</button></li>';
			$output .= '<script>';
			$output .= 'function fdp_collapse_admin_menu(){';
			$output .= 'var b=document.body;';
			$output .= 'if(b.className.indexOf(" sticky-menu")>0){';
			$output .= 'b.className=b.className.split(" sticky-menu").join("");';
			$output .= '}';
			$output .= 'if(b.className.indexOf(" folded")>0){';
			$output .= 'b.className=b.className.split(" folded").join("");';
			$output .= '}';
			$output .= 'else{';
			$output .= 'b.className=b.className + " folded";';
			$output .= '}';
			$output .= '}';
			$output .= 'window.history.pushState(document.title,document.title,window.location.href.split("&clear-fdp-cache")[0]);';
			$output .= '</script>';
			echo $output; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on $output.
		}
	},
	1
);

add_action(
	'admin_page_access_denied',
	function() {
		if( isset( $_GET['page'] ) && false !== strpos( sanitize_text_field( $_GET['page'] ), 'eos_dp_' ) ) {
			$die  = '<p>' . esc_html__( 'Sorry, you are not allowed to access this page.' ) . '</p>';
			if( defined( 'EOS_DP_PRO_FDP_MIN_VERSION' ) && version_compare( EOS_DP_PRO_FDP_MIN_VERSION, EOS_DP_MU_VERSION ) > 0 ){
				$die .= '<p>' . esc_html__( 'Please, update Freesoul Deactivate Plugins to the last version.', 'freesoul-deactivate-plugins' ) . '</p>';
			}
			elseif ( ! defined( 'EOS_DP_PRO_VERSION' ) ) {
				$fatal_error_handler = get_site_transient( 'fdp_plugin_disabledd_fatal_error' );
				if( $fatal_error_handler && isset( $fatal_error_handler['plugin'] ) && in_array( $fatal_error_handler['plugin'],$GLOBALS['fdp_all_plugins'] ) && 'freesoul-deactivate-plugins-pro/freesoul-deactivate-plugins-pro.php' === $fatal_error_handler['plugin'] ){
					$die .= '<p>' . esc_html__( 'It looks liike Freesoul Deactivate Plugins PRO triggered a fatal error, and it was disabled on the FDP settings pages.', 'freesoul-deactivate-plugins' ) . '</p>';
					$die .= '<p>' . esc_html__( 'Try to do the following steps:', 'freesoul-deactivate-plugins' ) . '</p>';
					$die .= '<ul>';
					$die .= '<li>' . esc_html__( 'First, try to update both the free and PRO versions.', 'freesoul-deactivate-plugins' ) . '</li>';
					$die .= '<li>' . wp_kses( sprintf( __( 'If you still have the same issue, go to a working %sFDP settings page%s.', 'freesoul-deactivate-plugins' ), '<a href="' . admin_url( 'admin.php?page=eos_dp_menu' ) . '" target="_FDP_Singles">', '</a>' ), array( 'a' => array( 'href' => array(), 'target' => array() ) ) ) . '</li>';
					$die .= '<li>' . esc_html__( 'Show the warnings by going with your mouse over the notification icon in the FDP admin top navigation.', 'freesoul-deactivate-plugins' ) . '</li>';
					$die .= '<li>' . esc_html__( 'If you see a warning that mentions a fatal error caused by FDP PRO, then click on "Show details".', 'freesoul-deactivate-plugins' ) . '</li>';
					$die .= '<li>' . esc_html__( 'Read the details, and then click on "Dismiss".', 'freesoul-deactivate-plugins' ) . '</li>';
					$die .= '</ul>';
				}
				else{
					$die .= '<p>' . esc_html__( 'It looks a page added by Freesoul Deactivate Plugins PRO. Try to activate FDP PRO.', 'freesoul-deactivate-plugins' ) . '</p>';
				}
			} elseif ( defined( 'EOS_DP_PRO_VERSION' ) ) {
				$die .= '<p>' . esc_html__( 'Try to update both Freesoul Deactivate Plugins and Freesoul Deactivate Plugins PRO to the last version.', 'freesoul-deactivate-plugins' ) . '</p>';
			}
			wp_die( wp_kses_post( apply_filters(  'fdp_admin_page_access_denied', $die ) ), 403 );
		}
	}
);

add_action( 'show_user_profile', 'eos_dp_user_preferences' );
add_action( 'edit_user_profile', 'eos_dp_user_preferences' );
// FDP user preferences.
function eos_dp_user_preferences( $user ) {
	if ( ! current_user_can( 'activate_plugins' ) || ( defined( 'FDP_REMOVE_PROFILE_PREFERENCES' ) && FDP_REMOVE_PROFILE_PREFERENCES ) || apply_filters( 'fdp_hide_user_preferences', false ) ) {
		return;
	}
	$toplevel_menu = get_user_meta( $user->ID, 'fdp_toplevel_admin_menu', true );
	$toplevel_menu = ! $toplevel_menu ? true : 'true' === $toplevel_menu;
	$fdp_metabox   = get_user_meta( $user->ID, 'fdp_metabox', true );
	$fdp_metabox   = ! $fdp_metabox ? true : 'true' === $fdp_metabox;
	$fdp_csp   = get_user_meta( $user->ID, 'fdp_csp', true );
	$fdp_csp   = ! $fdp_csp ? true : 'true' === $fdp_csp;
	wp_nonce_field( 'fdp_user_preferences', 'fdp_user_preferences' );
	?>
  <h3><?php esc_html_e( 'FDP Preferences', 'freesoul-deactivate-plugins' ); ?></h3>

  <table class="form-table">
	  <tr>
		  <th id="fdp-user-admin-menu" scope="row"><?php esc_html_e( 'Admin menu', 'freesoul-deactivate-plugins' ); ?></th>
		  <td>
			 <label for="fdp_admin_menu">
				 <input id="fdp_admin_menu" name="fdp_admin_menu" type="checkbox" value="true"<?php echo $toplevel_menu ? ' checked' : ''; ?> />
				 <?php esc_html_e( 'Show the FDP top level admin menu. If unchecked you will still see Plugins Manager under Plugins. ', 'freesoul-deactivate-plugins' ); ?>
			 </label>
		  </td>
	  </tr>
	  <tr>
		  <th id="fdp-user-metabox" scope="row"><?php esc_html_e( 'Metabox in single page/post', 'freesoul-deactivate-plugins' ); ?></th>
		  <td>
			 <label for="fdp_metabox">
				 <input id="fdp_metabox" name="fdp_metabox" type="checkbox" value="true"<?php echo $fdp_metabox ? ' checked' : ''; ?> />
				 <?php esc_html_e( "Uncheck it to don't show the FDP metabox in the single page/post. ", 'freesoul-deactivate-plugins' ); ?>
			 </label>
		  </td>
	  </tr>
	  <tr>
		  <th id="fdp-user-csp" scope="row"><?php esc_html_e( 'Content Security Policy to exclude external scripts on the FDP backend pages.', 'freesoul-deactivate-plugins' ); ?></th>
		  <td>
			 <label for="fdp_csp">
				 <input id="fdp_csp" name="fdp_csp" type="checkbox" value="true"<?php echo $fdp_csp ? ' checked' : ''; ?> />
				 <?php esc_html_e( "Uncheck it if you have issues on the FDP backend pages. ", 'freesoul-deactivate-plugins' ); ?>
			 </label>
		  </td>
	  </tr>
  </table>
	<?php
}

add_action( 'personal_options_update', 'eos_dp_save_user_preferences' );
add_action( 'edit_user_profile_update', 'eos_dp_save_user_preferences' );
// Save FDP user preferences.
function eos_dp_save_user_preferences( $user_id ) {
	if ( ! isset( $_POST['fdp_user_preferences'] ) || ! current_user_can( 'edit_user', $user_id ) || ! wp_verify_nonce( sanitize_text_field( $_POST['fdp_user_preferences'] ), 'fdp_user_preferences' ) ) {
		 return;
	}
	$fdp_admin_menu = isset( $_POST['fdp_admin_menu'] ) && 'true' === $_POST['fdp_admin_menu'] ? 'true' : 'false';
	update_user_meta( $user_id, 'fdp_toplevel_admin_menu', sanitize_text_field( $fdp_admin_menu ) );
	$fdp_metabox = isset( $_POST['fdp_metabox'] ) && 'true' === $_POST['fdp_metabox'] ? 'true' : 'false';
	update_user_meta( $user_id, 'fdp_metabox', sanitize_text_field( $fdp_metabox ) );
	$fdp_csp = isset( $_POST['fdp_csp'] ) && 'true' === $_POST['fdp_csp'] ? 'true' : 'false';
	update_user_meta( $user_id, 'fdp_csp', sanitize_text_field( $fdp_csp ) );
}

add_action( 'activated_plugin', 'eos_dp_flush_main_nav_cache' );
add_action( 'deactivated_plugin', 'eos_dp_flush_main_nav_cache' );
add_action( 'upgrader_process_complete', 'eos_dp_flush_main_nav_cache' );
add_action( 'core_upgrade_preamble', 'eos_dp_flush_main_nav_cache' );
add_action( 'update_option_WPLANG', 'eos_dp_flush_main_nav_cache' );
add_action( 'update_option_show_on_front', 'eos_dp_flush_main_nav_cache' );
add_action( 'profile_update', function( $user_id ) {
	$current_user_id = get_current_user_id();
	if( $current_user_id && $user_id === $current_user_id ) {
		eos_dp_flush_main_nav_cache();
	}
} );
// Flush FDP main navigation cache.
function eos_dp_flush_main_nav_cache() {
	eos_dp_update_fdp_cache( 'nav', '', true );
}


add_action(
	'fdp_top_bar_notifications',
	function() {
		// General notices and warnings.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG && file_exists( WP_CONTENT_DIR . '/debug.log' ) ) {
			$filesize = round( filesize( WP_CONTENT_DIR . '/debug.log' ) / 1024, 1 );
			if ( $filesize > 200 ) {
				$msg = sprintf( esc_html__( 'The size of the file wp-content/debug.log is %s kB. Such a large file may degrade the performance every time WordPress writes into that file.', 'freesoul-deactivate-plugins' ), $filesize );
				eos_dp_display_admin_notice( 'eos_dp_debug_big', esc_html__( 'File wp-content/debug.log too big.', 'freesoul-deactivate-plugins' ), esc_html( $msg ), 'warning' );
			}
		}
	}
);

add_action(
	'admin_init',
	function() {
		if ( isset( $_GET['clear-fdp-cache'] ) && wp_verify_nonce( sanitize_text_field( $_GET['clear-fdp-cache'] ), 'fdp_clear_cache' ) ) {
			eos_dp_update_fdp_cache( 'nav', '', true );
		}
	}
);

add_action(
	'admin_bar_menu',
	function( $wp_admin_bar ) {
		if ( isset( $_GET['page'] ) && false !== strpos( sanitize_text_field( $_GET['page'] ), 'eos_dp_' ) ) {
			$nonce = wp_create_nonce( 'fdp_clear_cache' );
			$url   = admin_url( 'admin.php' );
			foreach ( $_GET as $k => $v ) {
				$url = add_query_arg( sanitize_key( $k ), esc_attr( $v ), $url );
			}
			$args = array(
				'id'    => 'fdp_clear_cache',
				'title' => esc_attr__( 'Refresh FDP Navigation', 'freesoul-deactivate-plugins' ),
				'href'  => add_query_arg( 'clear-fdp-cache', $nonce, $url ),
			);
			$wp_admin_bar->add_node( $args );
		}
	},
	9999
);

add_action( 'plugins_loaded', 'eos_dp_redirect_to_fdp_page' );
// Redirect to settings page if it's a FDP page.
function eos_dp_redirect_to_fdp_page() {
	if ( eos_dp_is_fdp_page() && isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( sanitize_text_field( $_SERVER['REQUEST_URI'] ), '/wp-admin/plugins.php' ) ) {
		wp_safe_redirect( esc_url( add_query_arg( $_GET, admin_url( 'admin.php' ) ) ) );
	}
}

add_action( 'admin_head', 'eos_dp_admin_inline_style' );
// Add inline style on admin pages.
function eos_dp_admin_inline_style() {
	echo '<style>.eos_dp_plugin_upgrade_notice+p:before{content:"";display:none;opacity:0}.fdp-admin-cleaned #adminmenu div.wp-menu-image{overflow:hidden}</style>';
}

// Load all the FDP add-ons.
require_once EOS_DP_PLUGIN_DIR . '/admin/classes/class-fdp-load-addons.php';
$load_fdp_addons = new FDP_Load_Addons();
