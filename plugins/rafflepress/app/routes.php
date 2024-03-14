<?php


/**
 * Postback Routes
 */




/**
 * Rewrite EndPoints Routes
 */

add_action( 'init', 'rafflepress_lite_add_rules' );

function rafflepress_lite_add_rules() {
	add_rewrite_rule(
		'^rafflepress/([0-9]+)/?',
		'index.php?rafflepress_page=rafflepress_render&rafflepress_id=$matches[1]',
		'top'
	);
	add_rewrite_rule(
		'^rp/([0-9]+)/?',
		'index.php?rafflepress_page=rafflepress_render&rafflepress_id=$matches[1]',
		'top'
	);
}


function rafflepress_lite_add_custom_slug( $slug ) {
	if ( ! empty( $slug ) ) {
		add_rewrite_rule(
			'^' . $slug . '/([0-9]+)/?',
			'index.php?rafflepress_page=rafflepress_render&rafflepress_id=$matches[1]',
			'top'
		);
		flush_rewrite_rules();
	}
}

// Add the vars so that WP recognizes it
add_filter( 'query_vars', 'rafflepress_lite_add_query_var' );

function rafflepress_lite_add_query_var( $vars ) {
	$vars[] = 'rafflepress_page';
	$vars[] = 'rafflepress_id';
	return $vars;
}

add_action( 'template_redirect', 'rafflepress_lite_routes' );

function rafflepress_lite_routes() {
	 $rafflepress_id = get_query_var( 'rafflepress_id' );
	if ( empty( $rafflepress_id ) ) {
		// referral id
		if ( ! empty( $_GET['rpid'] ) ) {
			$rafflepress_id = absint( $_GET['rpid'] );
		}
	}

	if ( ! empty( $rafflepress_id ) ) {
		//$rafflepress_id = get_query_var('rafflepress_id');
		$c = ob_get_contents();
		if ( $c ) {
			@ob_end_clean();
		}

		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' ); // HTTP 1.1.
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' ); // HTTP 1.0.
		header( 'Expires: 0 ' );

		require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/views/rafflepress-giveaway.php';
		exit();
	}
}


/**
 * Admin Menu Routes
 */

add_action( 'admin_menu', 'rafflepress_lite_create_menus' );

function rafflepress_lite_create_menus() {
	$menu_capability = apply_filters( 'rafflepress_menu_capability', 'edit_others_posts' );

	// Return if current user does not have specified menu capability
	if ( ! current_user_can( $menu_capability ) ) {
		return; }

	// get notifications count
	$notification        = '';
	$n                   = new RafflePress_Notifications();
	$notifications_count = $n->get_count();


	if ( ! empty( $notifications_count ) ) {
		$notification = '<span class="update-plugins"><span class="plugin-count">' . $notifications_count . '</span></span>';
	}

	add_menu_page(
		'RafflePress',
		'RafflePress ' . $notification,
		$menu_capability,
		'rafflepress_lite',
		'rafflepress_lite_dashboard_page',
		'data:image/svg+xml;base64,' . 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB3aWR0aD0iNDgwcHgiIGhlaWdodD0iNTA3cHgiIHZpZXdCb3g9IjAgMCA0ODAgNTA3IiB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPgogICAgPGcgaWQ9IlBhZ2UtMSIgc3Ryb2tlPSJub25lIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+CiAgICAgICAgPGcgaWQ9IkFzc2V0LTEiIGZpbGw9IiNGRkZGRkYiIGZpbGwtcnVsZT0ibm9uemVybyI+CiAgICAgICAgICAgIDxwYXRoIGQ9Ik00NzkuNTEsMjUzLjYxIEM0NzkuMjIzMzMzLDI3OS45OTY2NjcgNDc4LjkzNjY2NywzMDYuMzkzMzMzIDQ3OC42NSwzMzIuOCBDNDc4LjU2LDM0MC40IDQ3OC4zMywzNDcuOTkgNDc4LjIsMzU1LjU5IEM0NzcuODksMzcyLjU5IDQ3MC40OCwzODUuODEgNDU2LjgxLDM5NS42NiBDNDU0LjE2LDM5Ny41OCA0NTEuMzcsMzk5LjMxIDQ0OC42Miw0MDEuMSBDNDExLjA4LDQyNS42IDM3Mi4wNiw0NDcuNTEgMzMyLjA5LDQ2Ny43NCBDMzA4LjYyLDQ3OS42MiAyODQuODYsNDkwLjg5IDI2MC45LDUwMS43NCBDMjQ3LjEsNTA3Ljk4IDIzMy4xMyw1MDguMzIgMjE5LjMsNTAyLjEgQzE2Ni43MSw0NzguNDMgMTE1LjE2LDQ1Mi42OSA2NS43LDQyMi45NCBDNTEuNTksNDE0LjQ1IDM3LjcsNDA1LjUzIDIzLjksMzk2LjYxIEMxMy44Njk3MjM2LDM5MC4zNTc2MTkgNi40NzQ2MDE1NSwzODAuNjUwNDA5IDMuMTEsMzY5LjMyIEMxLjg5NzcyMzQ0LDM2NS4zMjM0NTQgMS4yNTQ3MzM4NiwzNjEuMTc2MDAzIDEuMiwzNTcgQzAuOSwzMzkgMC40MSwzMjAuODkgMC4zMSwzMDIuODEgQzAuMTAzMzMzMzMzLDI2MS45NDMzMzMgMCwyMjEuMDY2NjY3IDAsMTgwLjE4IEMwLDE2OS40OCAwLjU0LDE1OC43NSAxLjI5LDE0OC4wNyBDMi40MywxMzEuODQgMTAuMTksMTE5LjI1IDIzLjU3LDExMC4xOSBDMzEuNDUsMTA0Ljg2IDM5LjQ5LDk5Ljc3IDQ3LjU3LDk0LjcyIEM4OS44Myw2OC4yNCAxMzMuNzgsNDQuODUgMTc4Ljc0LDIzLjM0IEMxOTIuMDEzMzMzLDE3IDIwNS4zNDY2NjcsMTAuNzk2NjY2NyAyMTguNzQsNC43MyBDMjMyLjUzLC0xLjUyIDI0Ni41MSwtMS42IDI2MC4zMyw0LjYzIEMzMDYuNTYsMjUuNDYgMzUyLDQ4IDM5NS45NCw3My4yMyBDNDE2LjA0LDg0Ljc5IDQzNS43NjY2NjcsOTYuOTY2NjY2NyA0NTUuMTIsMTA5Ljc2IEM0NjYuNDcsMTE3LjI5IDQ3NC4wNCwxMjcuNTUgNDc3LjAzLDE0MC45MyBDNDc3LjY3ODYxMiwxNDQuMDYyMjYxIDQ3OC4wNjMyOTUsMTQ3LjI0MzQxOCA0NzguMTgsMTUwLjQ0IEM0NzkuMzQsMTcyLjg5IDQ3OS41NywxOTUuMzcgNDc5LjU4LDIxNy44NSBDNDc5LjU4LDIyOS43NyA0NzkuNTgsMjQxLjY5IDQ3OS41OCwyNTMuNjEgTDQ3OS41MSwyNTMuNjEgWiBNMzU2LjA1LDQxMS43IEMzNTcuMDUsNDExLjIyIDM1Ny42NSw0MTAuOTMgMzU4LjI3LDQxMC41OSBDMzgzLjk3LDM5Ni40MSA0MDkuMTUsMzgxLjM1IDQzMy42MSwzNjUuMTIgQzQzNC40ODY5MjgsMzY0LjU1ODkxMSA0MzUuMzI4NTE3LDM2My45NDQ0MTcgNDM2LjEzLDM2My4yOCBDNDM4LjU2NTMzNCwzNjEuMzQ3OTkgNDM5Ljk1NjU3MywzNTguMzg3OTA3IDQzOS44OSwzNTUuMjggQzQzOS44OSwzNTEuOTIgNDM5Ljk5LDM0OC41NyA0NDAuMDUsMzQ1LjIxIEM0NDEuMDUsMjkwLjIxIDQ0MS41NiwyMzUuMyA0NDAuNzcsMTgwLjM0IEM0NDAuNjMsMTcwLjY2IDQ0MC4xOSwxNjAuOTkgNDM5Ljg5LDE1MS4zNCBDNDM5LjgzODQwMywxNDguMDAxNjcxIDQzOC4yNTY5MDksMTQ0Ljg3MTg2NCA0MzUuNiwxNDIuODUgQzQzNC43MjA2NTQsMTQyLjE1ODE0IDQzMy44MDI0OTEsMTQxLjUxNzA5NSA0MzIuODUsMTQwLjkzIEM0MTguMzUsMTMxLjg1IDQwNCwxMjIuNTIgMzg5LjMsMTEzLjc2IEMzNDIuNzQsODYuMDMgMjk0LjMsNjIgMjQ0Ljk0LDM5LjY1IEMyNDEuNDA1OTI0LDM4LjAxNTMxMDkgMjM3LjMxNjQ2NCwzOC4wOTY0MzYzIDIzMy44NSwzOS44NyBDMjA4LjQ1LDUxLjQxIDE4My4yNiw2My4zOSAxNTguNDIsNzYuMTEgQzExOS42OTE4MTgsOTUuNzYxNTU4MSA4Mi4wODA5NjczLDExNy41NDE1MzMgNDUuNzYsMTQxLjM1IEM0MS41MiwxNDQuMTUgMzkuMzYsMTQ3Ljc1IDM5LjI4LDE1Mi44NyBDMzkuMDUsMTY3LjExIDM4LjM1LDE4MS4zNCAzOC4zNTk4OTQsMTk1LjU4IEMzOC4zNTk4OTQsMjM5LjAxIDM4LjY1LDI4Mi40NSAzOC44OCwzMjUuODkgQzM4Ljg4LDMzNS42NSAzOS4yNiwzNDUuNCAzOS4zOSwzNTUuMTYgQzM5LjM3MTQ2OTUsMzU4LjY3MTA2NiA0MS4wOTkyNTExLDM2MS45NjE3MjIgNDQsMzYzLjk0IEM0NC43MSwzNjQuNDcgNDUuNDQsMzY0Ljk0IDQ2LjE4LDM2NS40NCBDNzAuNiwzODEuNTkgOTUuNywzOTYuNjIgMTIxLjM1LDQxMC43NCBDMTIyLjAyLDQxMS4xMiAxMjIuNzMsNDExLjQ0IDEyMy42Myw0MTEuODkgQzEyMy42Myw0MTEuMDcgMTIzLjYzLDQxMC41MiAxMjMuNjMsNDA5Ljk4IEMxMjMuMTQsNDAzLjY5IDEyMi41NiwzOTcuMzkgMTIyLjE1LDM5MS4wOSBDMTIwLjk5LDM3My4wOSAxMjAuOTUsMzU1LjA5IDEyMy4yNywzMzcuMTkgQzEyNC4zNTQ0NzMsMzI4LjIxMzMzNSAxMjYuNDQ3Njk3LDMxOS4zODc1NzkgMTI5LjUxLDMxMC44OCBDMTMyLjA0Mjk0OSwzMDQuMDE1NTY3IDEzNi4zMTk5ODQsMjk3LjkyOTE1IDE0MS45MiwyOTMuMjIgQzE0Ni4yNjgyMjYsMjg5LjU1OTMzNSAxNTEuMDMwNzU0LDI4Ni40MjEyMzUgMTU2LjExLDI4My44NyBDMTU2Ljg2LDI4My40OCAxNTcuNTksMjgzLjA0IDE1OC40OSwyODIuNTQgQzE1Ny43OCwyODEuNTQgMTU3LjIsMjgwLjY2IDE1Ni42MywyNzkuODEgQzEzNS43LDI0OC44NyAxMjEuODMsMjE1LjE5IDExNi43LDE3OC4wMiBDMTE0LjQyLDE2MS40OSAxMTkuMTQsMTQ3LjUyIDEzMC45MSwxMzUuODggQzEzOC44NjIzNTksMTI4LjAxNDAyIDE0OC43ODY2NTMsMTIyLjQzNTI3NCAxNTkuNjQsMTE5LjczIEMxNjUuOTcxNTgxLDExNy45MTc3NzQgMTcyLjYzOTQ5LDExNy42MTI5NzUgMTc5LjExLDExOC44NCBDMTg3LjY3LDEyMC42MyAxOTMuNiwxMjUuMzggMTk1LjQ5LDEzNC4yNiBDMTk1Ljg3MDcwOCwxMzYuNDY4NTEzIDE5Ni4xMDEyMzcsMTM4LjcwMDI5OCAxOTYuMTgsMTQwLjk0IEMxOTcuMTgsMTU0LjQxIDE5OC4wMSwxNjcuOTQgMTk5LjI3LDE4MS4zNyBDMjAwLjU3LDE5NS4zIDIwMi4wOCwyMDkuMjIgMjAzLjg5LDIyMy4wOSBDMjA1Ljc3LDIzNy41MiAyMDguMTMsMjUxLjg4IDIxMC4yOSwyNjYuMjcgQzIxMC40MiwyNjcuMTIgMjEwLjY2LDI2Ny45NSAyMTAuODksMjY4Ljk0IEMyMzAuMTY1NjExLDI2Ni44MjcyMDggMjQ5LjYxNDM4OSwyNjYuODI3MjA4IDI2OC44OSwyNjguOTQgQzI2OS4xMSwyNjguMDIgMjY5LjMyLDI2Ny4zNCAyNjkuNDMsMjY2LjY1IEMyNzAuNjEsMjU5LjM5IDI3MS45MSwyNTIuMTQgMjcyLjksMjQ0Ljg1IEMyNzQuODIsMjMwLjc1IDI3Ni43NSwyMTYuNjUgMjc4LjM0LDIwMi41MSBDMjc5LjgxLDE4OS41MSAyODAuOTEsMTc2LjQgMjgyLjAzLDE2My4zNCBDMjgyLjc4LDE1NC41NyAyODMuMTgsMTQ1Ljc4IDI4My45MSwxMzcuMDEgQzI4NC42NCwxMjguMjQgMjg5LjI3LDEyMi41MiAyOTcuNTQsMTE5LjY5IEMzMDEuNDE0Nzk1LDExOC4zNjk3MTYgMzA1LjUyMDk0MSwxMTcuODY1ODExIDMwOS42LDExOC4yMSBDMzI4Ljg0LDExOS43MSAzNDQuMjQsMTI4LjI5IDM1NS41MywxNDMuOSBDMzYxLjg1NzYyMywxNTIuNjIwNzMzIDM2NC42OTAyOCwxNjMuMzk0ODE5IDM2My40NywxNzQuMSBDMzYyLjY3NDQ1MiwxODIuMDYxNTY2IDM2MS4zNTQ5MjQsMTg5Ljk2MjAyOSAzNTkuNTIsMTk3Ljc1IEMzNTIuNTYxODI0LDIyNy4zMzIzNzEgMzQwLjEwOTI4NCwyNTUuMzQ0NjUxIDMyMi44MSwyODAuMzMgQzMyMi4zMiwyODEuMDQgMzIxLjg3LDI4MS43OCAzMjEuMzMsMjgyLjYxIEMzMjIuMTcsMjgzLjA4IDMyMi44NywyODMuNDQgMzIzLjU0LDI4My44NCBDMzI2Ljc3LDI4NS43NiAzMzAuMDgsMjg3LjU1IDMzMy4xOSwyODkuNjUgQzM0MS44MDg4MzIsMjk1LjM0OTY3OCAzNDguMjAzNTc1LDMwMy44NDE4NjkgMzUxLjMsMzEzLjcgQzM1NS4xOCwzMjUuNzUgMzU2Ljk5LDMzOC4xMyAzNTcuODEsMzUwLjcgQzM1OC43NTcwMzEsMzY3LjE1NzEyMSAzNTguNDkyOTg2LDM4My42NjE1OTYgMzU3LjAyLDQwMC4wOCBDMzU2LjcsNDAzLjc5IDM1Ni4zOSw0MDcuNTMgMzU2LjA1LDQxMS43IFogTTIxNy44ODAwODQsMzY0IEMyMTcuODg0MDQyLDM1NS4yMjg1MyAyMTIuNjAyMjc5LDM0Ny4zMTg4ODQgMjA0LjQ5ODg4MywzNDMuOTYxMjU1IEMxOTYuMzk1NDg3LDM0MC42MDM2MjcgMTg3LjA2NzMyNCwzNDIuNDU5Njc1IDE4MC44NjYzOSwzNDguNjYzNDczIEMxNzQuNjY1NDU1LDM1NC44NjcyNyAxNzIuODEzNzEzLDM2NC4xOTYyODggMTc2LjE3NTA4MSwzNzIuMjk4MTM0IEMxNzkuNTM2NDUsMzgwLjM5OTk3OSAxODcuNDQ4NTMzLDM4NS42NzgwOTEgMTk2LjIyLDM4NS42NzAwNTggQzIwMS45NzAzOTksMzg1LjY4MzMyNyAyMDcuNDg4OTY5LDM4My40MDQyMDQgMjExLjU1NDE4NywzNzkuMzM3MTA5IEMyMTUuNjE5NDA1LDM3NS4yNzAwMTQgMjE3Ljg5NTk4MSwzNjkuNzUwMzkyIDIxNy44ODAwODQsMzY0IEwyMTcuODgwMDg0LDM2NCBaIE0zMDUuMTkwMTQ5LDM2NC4wNyBDMzA1LjIyMjM1NSwzNTUuMjk3Njk1IDI5OS45NjUwODYsMzQ3LjM3MDQxNyAyOTEuODcxMzYxLDM0My45ODcyMjUgQzI4My43Nzc2MzUsMzQwLjYwNDAzMiAyNzQuNDQyNzU0LDM0Mi40MzE3NjMgMjY4LjIyMjU5NywzNDguNjE3NTU2IEMyNjIuMDAyNDM5LDM1NC44MDMzNDggMjYwLjEyMzAxOSwzNjQuMTI3OTYgMjYzLjQ2MTMyLDM3Mi4yNDAzMDQgQzI2Ni43OTk2MiwzODAuMzUyNjQ5IDI3NC42OTc2NSwzODUuNjUzNzU1IDI4My40NywzODUuNjcwMzM0IEMyODkuMjEyNTE0LDM4NS43MDE5MTMgMjk0LjczMTM1OCwzODMuNDQ1NzkzIDI5OC44MDY5NzUsMzc5LjQwMDE5OSBDMzAyLjg4MjU5MiwzNzUuMzU0NjA1IDMwNS4xNzk0NTQsMzY5Ljg1MjU5MyAzMDUuMTkwMTQ5LDM2NC4xMSBMMzA1LjE5MDE0OSwzNjQuMDcgWiIgaWQ9IlNoYXBlIj48L3BhdGg+CiAgICAgICAgPC9nPgogICAgPC9nPgo8L3N2Zz4=',
		apply_filters( 'rafflepress_top_level_menu_postion', 58 )
	);

	add_submenu_page(
		'rafflepress_lite',
		__( 'Giveaways', 'rafflepress' ),
		__( 'Giveaways', 'rafflepress' ),
		$menu_capability,
		'rafflepress_lite',
		'rafflepress_lite_dashboard_page'
	);

	add_submenu_page(
		'rafflepress_lite',
		__( 'Add New', 'rafflepress' ),
		__( 'Add New', 'rafflepress' ),
		$menu_capability,
		'rafflepress_lite_add_new',
		'rafflepress_lite_add_new_page'
	);

	add_submenu_page(
		'rafflepress_lite',
		__( 'Settings', 'rafflepress' ),
		__( 'Settings', 'rafflepress' ),
		$menu_capability,
		'rafflepress_lite_settings',
		'rafflepress_lite_settings_page'
	);

	add_submenu_page(
		'rafflepress_lite',
		__( 'About Us', 'rafflepress' ),
		__( 'About Us', 'rafflepress' ),
		$menu_capability,
		'rafflepress_lite_about_us',
		'rafflepress_lite_about_us_page'
	);

	if ( RAFFLEPRESS_BUILD == 'lite') {
		add_submenu_page(
			'rafflepress_lite',
			__( 'Get Pro', 'rafflepress' ),
			'<span id="rp-lite-admin-menu__upgrade">' . __( 'Get Pro', 'rafflepress' ) . '</span>',
			'read',
			'rafflepress_lite_get_pro',
			'rafflepress_lite_get_pro_page'
		);
		// add upgrade link color and blank target
		add_action( 'admin_footer', 'seedprod_pro_upgrade_link_class' );
		function seedprod_pro_upgrade_link_class() {
			echo "<script>jQuery(function($) { $('#rp-lite-admin-menu__upgrade').parent().attr('target','_blank'); $('#rp-lite-admin-menu__upgrade').parent().parent().addClass('rp-lite-admin-menu__upgrade_wrapper')});</script>";
		}

	}

	add_submenu_page(
		'rafflepress_lite',
		__( 'Builder', 'rafflepress' ),
		__( 'Builder', 'rafflepress' ),
		'read',
		'rafflepress_lite_builder',
		'rafflepress_lite_builder_page'
	);

	add_submenu_page(
		'rafflepress_lite',
		'Debug',
		'Debug',
		'read',
		'rafflepress_lite_debug',
		'rafflepress_lite_debug_page'
	);
}

add_action( 'admin_head', 'rafflepress_lite_remove_menus' );

function rafflepress_lite_remove_menus() {
	remove_submenu_page( 'rafflepress_lite', 'rafflepress_lite_builder' );
	remove_submenu_page( 'rafflepress_lite', 'rafflepress_lite_debug' );
}

function rafflepress_lite_dashboard_page() {
	 require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/views/dashboard.php';
}


function rafflepress_lite_builder_page() {
	require_once RAFFLEPRESS_PLUGIN_PATH . 'resources/views/builder.php';
}

function rafflepress_lite_debug_page() {
	 $log = get_option( 'rafflepress_log' );
	echo 'Debug Log (Clear query param: rp-clear-debug=1)';
	echo( $log );
	echo '<br>';
	echo '<br><br>Debug On or Off (Enable query param: rp-enable=1 for on and rp-enable=2 for off)';
	echo( get_option( 'rafflepress_enable_log' ) );

	// clear log
	if ( ! empty( $_GET['rp-clear-debug'] ) ) {
		update_option( 'rafflepress_log', '' );
	}
	// turn on or off debug
	if ( ! empty( $_GET['rp-enable'] ) && $_GET['rp-enable'] == '1' ) {
		update_option( 'rafflepress_enable_log', true );
	}
	if ( ! empty( $_GET['rp-enable'] ) && $_GET['rp-enable'] == '2' ) {
		update_option( 'rafflepress_enable_log', false );
	}
}

/* Short circuit new request */

add_action( 'admin_init', 'rafflepress_lite_new_giveaway', 1 );


/* Redirect to SPA */

add_action( 'admin_init', 'rafflepress_lite_redirect_to_site', 1 );

function rafflepress_lite_redirect_to_site() {
	// settings page
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'rafflepress_lite_settings' ) {
		wp_redirect( 'admin.php?page=rafflepress_lite#/settings' );
		exit();
	}
	// add new page
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'rafflepress_lite_add_new' ) {
		wp_redirect( 'admin.php?page=rafflepress_lite_builder&id=0#/template' );
		exit();
	}

	//  about us page
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'rafflepress_lite_about_us' ) {
		wp_redirect( 'admin.php?page=rafflepress_lite#/aboutus' );
		exit();
	}

	// getpro page
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'rafflepress_lite_get_pro' ) {
		wp_redirect( rafflepress_lite_upgrade_link( 'wp-sidebar-menu' ) );
		exit();
	}
}

/**
 * Ajax Request Routes
 */

if ( defined( 'DOING_AJAX' ) ) {
	add_action( 'wp_ajax_rafflepress_lite_dismiss_settings_lite_cta', 'rafflepress_lite_dismiss_settings_lite_cta' );
	add_action( 'wp_ajax_rafflepress_lite_save_settings', 'rafflepress_lite_save_settings' );
	add_action( 'wp_ajax_rafflepress_lite_save_api_key', 'rafflepress_lite_save_api_key' );
	add_action( 'wp_ajax_rafflepress_lite_save_template', 'rafflepress_lite_save_template' );
	add_action( 'wp_ajax_rafflepress_lite_save_giveaway', 'rafflepress_lite_save_giveaway' );
	add_action( 'wp_ajax_rafflepress_lite_save_slug', 'rafflepress_lite_save_slug' );
	add_action( 'wp_ajax_rafflepress_lite_get_utc_offset', 'rafflepress_lite_get_utc_offset' );
	add_action( 'wp_ajax_rafflepress_lite_save_publish', 'rafflepress_lite_save_publish' );
	add_action( 'wp_ajax_rafflepress_lite_giveaway_datatable', 'rafflepress_lite_giveaway_datatable' );
	add_action( 'wp_ajax_rafflepress_lite_duplicate_giveaway', 'rafflepress_lite_duplicate_giveaway' );
	add_action( 'wp_ajax_rafflepress_lite_get_giveaway_list', 'rafflepress_lite_get_giveaway_list' );
	add_action( 'wp_ajax_rafflepress_lite_archive_selected_giveaways', 'rafflepress_lite_archive_selected_giveaways' );
	add_action( 'wp_ajax_rafflepress_lite_unarchive_selected_giveaways', 'rafflepress_lite_unarchive_selected_giveaways' );
	add_action( 'wp_ajax_rafflepress_lite_delete_archived_giveaways', 'rafflepress_lite_delete_archived_giveaways' );
	add_action( 'wp_ajax_rafflepress_lite_end_giveaway', 'rafflepress_lite_end_giveaway' );
	add_action( 'wp_ajax_rafflepress_lite_start_giveaway', 'rafflepress_lite_start_giveaway' );
	add_action( 'wp_ajax_rafflepress_lite_enable_disable_giveaway', 'rafflepress_lite_enable_disable_giveaway' );

	add_action( 'wp_ajax_rafflepress_lite_get_automation_tool_list', 'rafflepress_lite_get_automation_tool_list' );

	add_action( 'wp_ajax_rafflepress_lite_entries_report_datatable', 'rafflepress_lite_entries_report_datatable' );
	add_action( 'wp_ajax_rafflepress_lite_ps_results_datatable', 'rafflepress_lite_ps_results_datatable' );
	add_action( 'wp_ajax_rafflepress_lite_entries_datatable', 'rafflepress_lite_entries_datatable' );
	add_action( 'wp_ajax_rafflepress_lite_valid_selected_entries', 'rafflepress_lite_valid_selected_entries' );
	add_action( 'wp_ajax_rafflepress_lite_invalid_selected_entries', 'rafflepress_lite_invalid_selected_entries' );
	add_action( 'wp_ajax_rafflepress_lite_delete_invalid_entries', 'rafflepress_lite_delete_invalid_entries' );
	add_action( 'wp_ajax_rafflepress_lite_pick_winners', 'rafflepress_lite_pick_winners' );


	add_action( 'wp_ajax_rafflepress_lite_contestants_resend_email', 'rafflepress_lite_contestants_resend_email' );
	add_action( 'wp_ajax_rafflepress_lite_contestants_datatable', 'rafflepress_lite_contestants_datatable' );
	add_action( 'wp_ajax_rafflepress_lite_confirm_selected_contestants', 'rafflepress_lite_confirm_selected_contestants' );
	add_action( 'wp_ajax_rafflepress_lite_unconfirm_selected_contestants', 'rafflepress_lite_unconfirm_selected_contestants' );
	add_action( 'wp_ajax_rafflepress_lite_invalid_selected_contestants', 'rafflepress_lite_invalid_selected_contestants' );
	add_action( 'wp_ajax_rafflepress_lite_delete_invalid_contestants', 'rafflepress_lite_delete_invalid_contestants' );

	add_action( 'wp_ajax_rafflepress_lite_get_font', 'rafflepress_lite_get_font' );
	add_action( 'wp_ajax_rafflepress_lite_get_plugins_list', 'rafflepress_lite_get_plugins_list' );

	add_action( 'wp_ajax_rafflepress_lite_install_addon', 'rafflepress_lite_install_addon' );
	add_action( 'wp_ajax_rafflepress_lite_activate_addon', 'rafflepress_lite_activate_addon' );
	add_action( 'wp_ajax_rafflepress_lite_deactivate_addon', 'rafflepress_lite_deactivate_addon' );

	add_action( 'wp_ajax_rafflepress_lite_install_automation', 'rafflepress_lite_install_automation' );
	add_action( 'wp_ajax_rafflepress_lite_activate_automation', 'rafflepress_lite_activate_automation' );
	add_action( 'wp_ajax_rafflepress_lite_deactivate_automation', 'rafflepress_lite_deactivate_automation' );

	add_action( 'wp_ajax_rafflepress_lite_install_addon', 'rafflepress_lite_install_addon' );
	add_action( 'wp_ajax_rafflepress_lite_deactivate_addon', 'rafflepress_lite_deactivate_addon' );
	add_action( 'wp_ajax_rafflepress_lite_activate_addon', 'rafflepress_lite_activate_addon' );
	add_action( 'wp_ajax_rafflepress_lite_plugin_nonce', 'rafflepress_lite_plugin_nonce' );

	add_action( 'wp_ajax_rafflepress_lite_giveaway_api', 'rafflepress_lite_giveaway_api' );
	add_action( 'wp_ajax_nopriv_rafflepress_lite_giveaway_api', 'rafflepress_lite_giveaway_api' );

	add_action( 'wp_ajax_rafflepress_lite_giveaway_comment', 'rafflepress_lite_giveaway_comment' );
	add_action( 'wp_ajax_nopriv_rafflepress_lite_giveaway_comment', 'rafflepress_lite_giveaway_comment' );

	add_action( 'wp_ajax_nopriv_rafflepress_lite_run_one_click_upgrade', 'rafflepress_lite_run_one_click_upgrade' );
	add_action( 'wp_ajax_rafflepress_lite_upgrade_license', 'rafflepress_lite_upgrade_license' );
}

