<?php

if ( ! defined( 'CANVAS_DIR' ) ) {
	die();
}
require_once dirname( __FILE__ ) . '/canvas_views.class.php';
require_once CANVAS_DIR . 'core/push/canvas-notifications.class.php';

class CanvasAdmin {



	public static $admin_pages         = array(
		'options'                   => 'Theme Options',
		'editor'                    => 'CSS Editor',
		'push'                      => 'Push Notifications',
		'canvas-login-registration' => 'Login & Registration',
	);
	public static $admin_notifications = array(
		'notifications' => 'Notifications',
	);
	private static $admin_screens      = array();
	private static $push_screen        = '';
	public static $utm_source          = 'canvas-plugin';

	public static function init() {
		add_action( 'admin_menu', array( 'CanvasAdmin', 'on_admin_menu' ) );
		add_action( 'current_screen', array( 'CanvasAdmin', 'current_screen' ) );
		add_action( 'admin_init', array( 'CanvasAdmin', 'on_admin_init' ), 1 );
		add_filter( 'script_loader_tag', array( 'CanvasAdmin', 'admin_fix_conflict_scripts' ), 11, 2 );
		add_action( 'wp_ajax_canvas_save_theme', array( 'CanvasAdmin', 'save_theme' ) );
		add_action( 'wp_ajax_canvas_schedule_dismiss', array( 'CanvasAdmin', 'schedule_dismiss' ) );
		add_action( 'wp_ajax_canvas_clean_history', array( 'CanvasAdmin', 'clean_history' ) );
		add_action( 'wp_ajax_canvas_clean_log', array( 'CanvasAdmin', 'clean_log' ) );
		add_action( 'wp_ajax_canvas_get_posts_for_notification', array( 'CanvasAdmin', 'canvas_get_posts_for_notification' ) );
		add_action( 'admin_init', array( 'CanvasAdmin', 'generate_login_template_and_save_to_db' ) );
		add_action( 'admin_init', array( 'CanvasAdmin', 'generate_registration_template_and_save_to_db' ) );
		add_action( 'admin_init', array( 'CanvasAdmin', 'generate_css_template' ) );
		add_action( 'admin_init', array( 'CanvasAdmin', 'redirect_after_deleting_templates' ), 11 );

		add_action( 'admin_footer', function() {
			?>
			<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
			<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
			<?php
		} );

		if ( Canvas::push_keys_set() ) {
			add_action( 'wp_ajax_canvas_attachment_content', array( 'CanvasNotifications', 'attachment_content' ) );
			add_action( 'wp_ajax_canvas_notification_check_duplicate', array( 'CanvasNotifications', 'notification_check_duplicate' ) );
			add_action( 'wp_ajax_canvas_notification_manual_send', array( 'CanvasNotifications', 'notification_manual_send' ) );
			add_action( 'wp_ajax_canvas_notification_history', array( 'CanvasNotifications', 'notification_history' ) );
		}
	}

	public static function admin_fix_conflict_scripts( $link, $handle ) {
		$screen = get_current_screen();
		if ( $screen instanceof WP_Screen && in_array( $screen->id, self::$admin_screens ) ) {
			$conflict_src = array(
				'Chart.min.js',
			);
			foreach ( $conflict_src as $url_src ) {
				if ( strstr( $link, $url_src ) ) {
					$link = '';
				}
			}
		}
		return $link;
	}

	public static function on_admin_init() {
		// Redirect to plugin's page on plugin activation
		if ( get_transient( '__canvas_activation_redirect' ) && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			delete_transient( '__canvas_activation_redirect' );
			if ( isset( $_GET['activate-multi'] ) ) {
				return;
			}

			wp_safe_redirect(
				add_query_arg(
					array(
						'page'       => Canvas::$slug,
						'first-time' => '1',
					),
					admin_url( 'admin.php?page=canvas&tab=canvas-login-registration&canvas-delete-editor-templates' )
				)
			);
		}
		// admin notice
		if ( is_admin() && current_user_can( 'administrator' ) ) {
			if ( ! Canvas::get_option( 'schedule_dismiss' ) ) {
				add_action( 'admin_notices', array( 'CanvasViews', 'add_schedule_demo' ) );
			}
		}
	}

	public static function on_admin_menu() {
		// show basic settings
		$image                 = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiICAgdmVyc2lvbj0iMS4wIiAgIGlkPSJMYXllcl8xIiAgIHg9IjBweCIgICB5PSIwcHgiICAgd2lkdGg9IjI0cHgiICAgaGVpZ2h0PSIyNHB4IiAgIHZpZXdCb3g9IjAgMCAyNCAyNCIgICBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCAyNCAyNCIgICB4bWw6c3BhY2U9InByZXNlcnZlIiAgIGlua3NjYXBlOnZlcnNpb249IjAuNDguNCByOTkzOSIgICBzb2RpcG9kaTpkb2NuYW1lPSJtbC1tZW51LWljb250ci5zdmciPjxtZXRhZGF0YSAgICAgaWQ9Im1ldGFkYXRhMjkiPjxyZGY6UkRGPjxjYzpXb3JrICAgICAgICAgcmRmOmFib3V0PSIiPjxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PjxkYzp0eXBlICAgICAgICAgICByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPjxkYzp0aXRsZSAvPjwvY2M6V29yaz48L3JkZjpSREY+PC9tZXRhZGF0YT48ZGVmcyAgICAgaWQ9ImRlZnMyNyI+PGNsaXBQYXRoICAgICAgIGlkPSJTVkdJRF8yXy0yIj48dXNlICAgICAgICAgaGVpZ2h0PSIxMDUyLjM2MjIiICAgICAgICAgd2lkdGg9Ijc0NC4wOTQ0OCIgICAgICAgICB5PSIwIiAgICAgICAgIHg9IjAiICAgICAgICAgc3R5bGU9Im92ZXJmbG93OnZpc2libGUiICAgICAgICAgeGxpbms6aHJlZj0iI1NWR0lEXzFfLTgiICAgICAgICAgb3ZlcmZsb3c9InZpc2libGUiICAgICAgICAgaWQ9InVzZTktMSIgLz48L2NsaXBQYXRoPjxjbGlwUGF0aCAgICAgICBpZD0iY2xpcFBhdGgzMDE4Ij48dXNlICAgICAgICAgaGVpZ2h0PSIxMDUyLjM2MjIiICAgICAgICAgd2lkdGg9Ijc0NC4wOTQ0OCIgICAgICAgICB5PSIwIiAgICAgICAgIHg9IjAiICAgICAgICAgc3R5bGU9Im92ZXJmbG93OnZpc2libGUiICAgICAgICAgeGxpbms6aHJlZj0iI1NWR0lEXzFfLTgiICAgICAgICAgb3ZlcmZsb3c9InZpc2libGUiICAgICAgICAgaWQ9InVzZTMwMjAiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGggICAgICAgaWQ9ImNsaXBQYXRoMzAyMiI+PHVzZSAgICAgICAgIGhlaWdodD0iMTA1Mi4zNjIyIiAgICAgICAgIHdpZHRoPSI3NDQuMDk0NDgiICAgICAgICAgeT0iMCIgICAgICAgICB4PSIwIiAgICAgICAgIHN0eWxlPSJvdmVyZmxvdzp2aXNpYmxlIiAgICAgICAgIHhsaW5rOmhyZWY9IiNTVkdJRF8xXy04IiAgICAgICAgIG92ZXJmbG93PSJ2aXNpYmxlIiAgICAgICAgIGlkPSJ1c2UzMDI0IiAvPjwvY2xpcFBhdGg+PGNsaXBQYXRoICAgICAgIGlkPSJjbGlwUGF0aDMwMjYiPjx1c2UgICAgICAgICBoZWlnaHQ9IjEwNTIuMzYyMiIgICAgICAgICB3aWR0aD0iNzQ0LjA5NDQ4IiAgICAgICAgIHk9IjAiICAgICAgICAgeD0iMCIgICAgICAgICBzdHlsZT0ib3ZlcmZsb3c6dmlzaWJsZSIgICAgICAgICB4bGluazpocmVmPSIjU1ZHSURfMV8tOCIgICAgICAgICBvdmVyZmxvdz0idmlzaWJsZSIgICAgICAgICBpZD0idXNlMzAyOCIgLz48L2NsaXBQYXRoPjxjbGlwUGF0aCAgICAgICBpZD0iY2xpcFBhdGgzMDMwIj48dXNlICAgICAgICAgaGVpZ2h0PSIxMDUyLjM2MjIiICAgICAgICAgd2lkdGg9Ijc0NC4wOTQ0OCIgICAgICAgICB5PSIwIiAgICAgICAgIHg9IjAiICAgICAgICAgc3R5bGU9Im92ZXJmbG93OnZpc2libGUiICAgICAgICAgeGxpbms6aHJlZj0iI1NWR0lEXzFfLTgiICAgICAgICAgb3ZlcmZsb3c9InZpc2libGUiICAgICAgICAgaWQ9InVzZTMwMzIiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGggICAgICAgaWQ9ImNsaXBQYXRoMzAzNCI+PHVzZSAgICAgICAgIGhlaWdodD0iMTA1Mi4zNjIyIiAgICAgICAgIHdpZHRoPSI3NDQuMDk0NDgiICAgICAgICAgeT0iMCIgICAgICAgICB4PSIwIiAgICAgICAgIHN0eWxlPSJvdmVyZmxvdzp2aXNpYmxlIiAgICAgICAgIHhsaW5rOmhyZWY9IiNTVkdJRF8xXy04IiAgICAgICAgIG92ZXJmbG93PSJ2aXNpYmxlIiAgICAgICAgIGlkPSJ1c2UzMDM2IiAvPjwvY2xpcFBhdGg+PGNsaXBQYXRoICAgICAgIGlkPSJjbGlwUGF0aDMwMzgiPjx1c2UgICAgICAgICBoZWlnaHQ9IjEwNTIuMzYyMiIgICAgICAgICB3aWR0aD0iNzQ0LjA5NDQ4IiAgICAgICAgIHk9IjAiICAgICAgICAgeD0iMCIgICAgICAgICBzdHlsZT0ib3ZlcmZsb3c6dmlzaWJsZSIgICAgICAgICB4bGluazpocmVmPSIjU1ZHSURfMV8tOCIgICAgICAgICBvdmVyZmxvdz0idmlzaWJsZSIgICAgICAgICBpZD0idXNlMzA0MCIgLz48L2NsaXBQYXRoPjxkZWZzICAgICAgIGlkPSJkZWZzNSI+PHJlY3QgICAgICAgICBoZWlnaHQ9IjI0IiAgICAgICAgIHdpZHRoPSIyNCIgICAgICAgICBpZD0iU1ZHSURfMV8iIC8+PC9kZWZzPjxjbGlwUGF0aCAgICAgICBpZD0iU1ZHSURfMl8iPjx1c2UgICAgICAgICBpZD0idXNlOSIgICAgICAgICBvdmVyZmxvdz0idmlzaWJsZSIgICAgICAgICB4bGluazpocmVmPSIjU1ZHSURfMV8iIC8+PC9jbGlwUGF0aD48ZGVmcyAgICAgICBpZD0iZGVmczUtMiI+PHJlY3QgICAgICAgICBoZWlnaHQ9IjI0IiAgICAgICAgIHdpZHRoPSIyNCIgICAgICAgICBpZD0iU1ZHSURfMV8tOCIgICAgICAgICB4PSIwIiAgICAgICAgIHk9IjAiIC8+PC9kZWZzPjxjbGlwUGF0aCAgICAgICBpZD0iY2xpcFBhdGgzMDQ1Ij48dXNlICAgICAgICAgaWQ9InVzZTMwNDciICAgICAgICAgb3ZlcmZsb3c9InZpc2libGUiICAgICAgICAgeGxpbms6aHJlZj0iI1NWR0lEXzFfLTgiICAgICAgICAgc3R5bGU9Im92ZXJmbG93OnZpc2libGUiICAgICAgICAgeD0iMCIgICAgICAgICB5PSIwIiAgICAgICAgIHdpZHRoPSI3NDQuMDk0NDgiICAgICAgICAgaGVpZ2h0PSIxMDUyLjM2MjIiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGggICAgICAgaWQ9IlNWR0lEXzJfLTgiPjxyZWN0ICAgICAgICAgaGVpZ2h0PSIyNCIgICAgICAgICB3aWR0aD0iMjQiICAgICAgICAgaWQ9InVzZTktMiIgICAgICAgICB4PSIwIiAgICAgICAgIHk9IjAiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGggICAgICAgaWQ9ImNsaXBQYXRoMzAxOC0wIj48cmVjdCAgICAgICAgIGhlaWdodD0iMjQiICAgICAgICAgd2lkdGg9IjI0IiAgICAgICAgIGlkPSJ1c2UzMDIwLTkiICAgICAgICAgeD0iMCIgICAgICAgICB5PSIwIiAvPjwvY2xpcFBhdGg+PGNsaXBQYXRoICAgICAgIGlkPSJjbGlwUGF0aDMwMjItNSI+PHJlY3QgICAgICAgICBoZWlnaHQ9IjI0IiAgICAgICAgIHdpZHRoPSIyNCIgICAgICAgICBpZD0idXNlMzAyNC05IiAgICAgICAgIHg9IjAiICAgICAgICAgeT0iMCIgLz48L2NsaXBQYXRoPjxjbGlwUGF0aCAgICAgICBpZD0iY2xpcFBhdGgzMDI2LTciPjxyZWN0ICAgICAgICAgaGVpZ2h0PSIyNCIgICAgICAgICB3aWR0aD0iMjQiICAgICAgICAgaWQ9InVzZTMwMjgtMyIgICAgICAgICB4PSIwIiAgICAgICAgIHk9IjAiIC8+PC9jbGlwUGF0aD48Y2xpcFBhdGggICAgICAgaWQ9ImNsaXBQYXRoMzAzMC0xIj48cmVjdCAgICAgICAgIGhlaWdodD0iMjQiICAgICAgICAgd2lkdGg9IjI0IiAgICAgICAgIGlkPSJ1c2UzMDMyLTEiICAgICAgICAgeD0iMCIgICAgICAgICB5PSIwIiAvPjwvY2xpcFBhdGg+PGNsaXBQYXRoICAgICAgIGlkPSJjbGlwUGF0aDMwMzQtNiI+PHJlY3QgICAgICAgICBoZWlnaHQ9IjI0IiAgICAgICAgIHdpZHRoPSIyNCIgICAgICAgICBpZD0idXNlMzAzNi04IiAgICAgICAgIHg9IjAiICAgICAgICAgeT0iMCIgLz48L2NsaXBQYXRoPjxjbGlwUGF0aCAgICAgICBpZD0iY2xpcFBhdGgzMDM4LTQiPjxyZWN0ICAgICAgICAgaGVpZ2h0PSIyNCIgICAgICAgICB3aWR0aD0iMjQiICAgICAgICAgaWQ9InVzZTMwNDAtMyIgICAgICAgICB4PSIwIiAgICAgICAgIHk9IjAiIC8+PC9jbGlwUGF0aD48L2RlZnM+PHNvZGlwb2RpOm5hbWVkdmlldyAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIiAgICAgYm9yZGVyY29sb3I9IiM2NjY2NjYiICAgICBib3JkZXJvcGFjaXR5PSIxIiAgICAgb2JqZWN0dG9sZXJhbmNlPSIxMCIgICAgIGdyaWR0b2xlcmFuY2U9IjEwIiAgICAgZ3VpZGV0b2xlcmFuY2U9IjEwIiAgICAgaW5rc2NhcGU6cGFnZW9wYWNpdHk9IjAiICAgICBpbmtzY2FwZTpwYWdlc2hhZG93PSIyIiAgICAgaW5rc2NhcGU6d2luZG93LXdpZHRoPSI3MzAiICAgICBpbmtzY2FwZTp3aW5kb3ctaGVpZ2h0PSI0ODAiICAgICBpZD0ibmFtZWR2aWV3MjUiICAgICBzaG93Z3JpZD0iZmFsc2UiICAgICBpbmtzY2FwZTp6b29tPSI5LjgzMzMzMzMiICAgICBpbmtzY2FwZTpjeD0iMy4wMjQxMzI1IiAgICAgaW5rc2NhcGU6Y3k9IjIxLjIwNTUwNSIgICAgIGlua3NjYXBlOndpbmRvdy14PSI1MjUiICAgICBpbmtzY2FwZTp3aW5kb3cteT0iNjYiICAgICBpbmtzY2FwZTp3aW5kb3ctbWF4aW1pemVkPSIwIiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0iTGF5ZXJfMSIgLz48cGF0aCAgICAgc3R5bGU9ImZpbGw6Izk5OTk5OTtmaWxsLW9wYWNpdHk6MSIgICAgIGNsaXAtcGF0aD0idXJsKCNTVkdJRF8yXykiICAgICBkPSJNIDQsMCBDIDEuNzkxLDAgMCwxLjc5MSAwLDQgbCAwLDE2IGMgMCwyLjIwOSAxLjc5MSw0IDQsNCBsIDE2LDAgYyAyLjIwOSwwIDQsLTEuNzkxIDQsLTQgTCAyNCw0IEMgMjQsMS43OTEgMjIuMjA5LDAgMjAsMCBMIDQsMCB6IG0gOS41LDMuNSBjIDAuMTI2NDcsMCAwLjI2MDA3NSwwLjAyNzgwOCAwLjM3NSwwLjA2MjUgMC4wODkzMiwwLjAyNTUxMSAwLjE2OTU2NiwwLjA1MDkyIDAuMjUsMC4wOTM3NSAwLjAyMTI2LDAuMDEyMDMzIDAuMDQxOTgsMC4wMTgwNzMgMC4wNjI1LDAuMDMxMjUgMC4xMTA4OTUsMC4wNjcwMTIgMC4xOTQ5MzcsMC4xNTQyOTg2IDAuMjgxMjUsMC4yNSAwLjA3OTE5LDAuMDg2OTk3IDAuMTMyNTAzLDAuMTc2NjQwOSAwLjE4NzUsMC4yODEyNSBsIDAuMDMxMjUsMCBjIDAuMDE1MjIsMC4wMjk2NTcgMC4wMTYyLDAuMDYzOTkyIDAuMDMxMjUsMC4wOTM3NSAwLjEzMjc5MiwwLjI2MjYwNjMgMC4yNTU2MTEsMC41MTEwNDY2IDAuMzc1LDAuNzgxMjUgMC4wMTMzNCwwLjAzMDE0NiAwLjAxODA4LDAuMDYzNTE5IDAuMDMxMjUsMC4wOTM3NSAwLjExODAzLDAuMjcxNDExMyAwLjIzOTY0OCwwLjUzMzk1OTUgMC4zNDM3NSwwLjgxMjUgMC4xMjU1MjgsMC4zMzQ4MTMyIDAuMjM5NDI0LDAuNjg3MTQ4MyAwLjM0Mzc1LDEuMDMxMjUgMC4wODY3NiwwLjI4NzQ3OTUgMC4xNzgyMjYsMC41ODEzMzQ2IDAuMjUsMC44NzUgMC4wMDQ5LDAuMDE5ODg3IC0wLjAwNDgsMC4wNDI1ODUgMCwwLjA2MjUgMC4wNzM3NywwLjMwNjUyNDcgMC4xNjE3ODksMC42MjQ3NzkgMC4yMTg3NSwwLjkzNzUgMC4wMDE4LDAuMDEwMDI3IC0wLjAwMTgsMC4wMjEyMTYgMCwwLjAzMTI1IDAuMDU4MTQsMC4zMjI1MjQzIDAuMDg1MjgsMC42NDAyMDM1IDAuMTI1LDAuOTY4NzUgMC4wODExMSwwLjY3NjgxMiAwLjEyNSwxLjM2MDc3NCAwLjEyNSwyLjA2MjUgbCAwLDAuMDMxMjUgMC4wMzEyNSwwIDAsMC4wMzEyNSBjIDAsMC42ODUgLTAuMDQ0OCwxLjM3MDEyMiAtMC4xMjUsMi4wMzEyNSAtMC4wMDEyLDAuMDEwMTkgMC4wMDEyLDAuMDIxMDYgMCwwLjAzMTI1IC0wLjAzOTQzLDAuMzE5OTc5IC0wLjA5OTAxLDAuNjIzNTk0IC0wLjE1NjI1LDAuOTM3NSAtMC4wMDM2LDAuMDIwMzEgMC4wMDM3LDAuMDQyMjEgMCwwLjA2MjUgLTAuMDU2NTEsMC4zMDM1NTQgLTAuMTE0OTIxLDAuNjA4Njg3IC0wLjE4NzUsMC45MDYyNSAtMC4wNjUyLDAuMjczNTIzIC0wLjE0MDU0OSwwLjU0NDI2NiAtMC4yMTg3NSwwLjgxMjUgLTAuMTA0OTk4LDAuMzUyNDM4IC0wLjIxNzAxNywwLjY4ODM2NSAtMC4zNDM3NSwxLjAzMTI1IC0wLjIxNjUwMSwwLjU5NjI3NSAtMC40NzEwMDIsMS4xNTU2MzcgLTAuNzUsMS43MTg3NSAtMC4wMTAzMSwwLjAyMDgxIC0wLjAyMDg2LDAuMDQxNzQgLTAuMDMxMjUsMC4wNjI1IC0wLjAwNywwLjAxODkzIDAuMDA3OCwwLjA0Mzk5IDAsMC4wNjI1IC0wLjAxNjg3LDAuMDMzNDMgLTAuMDQ1NDEsMC4wNjA0NSAtMC4wNjI1LDAuMDkzNzUgLTAuMDU1MDcsMC4xMDQ1MjUgLTAuMTA4Mjk4LDAuMTk0MjY5IC0wLjE4NzUsMC4yODEyNSAtMC4wNTQ2LDAuMDYwNDQgLTAuMTIyNjI0LDAuMTA2Nzg5IC0wLjE4NzUsMC4xNTYyNSBDIDE0LjA5NDcxLDIwLjM4OTM2NiAxMy44Mjg2NzQsMjAuNSAxMy41MzEyNSwyMC41IGMgLTAuMTAxMjg3LDAgLTAuMTg2NTU4LC0wLjAwOTYgLTAuMjgxMjUsLTAuMDMxMjUgLTAuMDc1NDYsLTAuMDE1NDQgLTAuMTQ4NTcyLC0wLjAzNDcyIC0wLjIxODc1LC0wLjA2MjUgLTAuMDA3OSwtMC4wMDMzIC0wLjAyMzM5LDAuMDAzNSAtMC4wMzEyNSwwIC0wLjE1NzI2NiwtMC4wNjY0OCAtMC4yODcxODcsLTAuMTYyMzEyIC0wLjQwNjI1LC0wLjI4MTI1IC0wLjIzNzUsLTAuMjM3MjUgLTAuMzc1LC0wLjU3NCAtMC4zNzUsLTAuOTM3NSAwLC0wLjA5OTYxIDAuMDA5MSwtMC4xOTIxMzEgMC4wMzEyNSwtMC4yODEyNSAwLjAwMjMsLTAuMDExMzIgLTAuMDAyNiwtMC4wMjAwNCAwLC0wLjAzMTI1IDAuMDA2MSwtMC4wMjIyMSAwLjAyMzkyLC0wLjA0MDc0IDAuMDMxMjUsLTAuMDYyNSAwLjAyNDU2LC0wLjA4MjgyIDAuMDU0MjIsLTAuMTQzNjQgMC4wOTM3NSwtMC4yMTg3NSBsIC0wLjAzMTI1LDAgYyAxLjAxMSwtMS45NjkgMS41NjI1LC00LjE5NzUgMS41NjI1LC02LjU2MjUgbCAwLC0wLjAzMTI1IDAsLTAuMDMxMjUgYyAwLC0wLjI5NTYyNSAtMC4wMTMzMiwtMC41ODM4ODEgLTAuMDMxMjUsLTAuODc1IEMgMTMuODM5ODgzLDEwLjUxMTI2NiAxMy43NTg1NTUsOS45MzY4NTk0IDEzLjY1NjI1LDkuMzc1IDEzLjU1MTQwNiw4LjgwODY1NzggMTMuNDE5MDc4LDguMjU5ODgwOSAxMy4yNSw3LjcxODc1IDEzLjE2NzI4NSw3LjQ1MDE5NTMgMTMuMDk3NzM0LDcuMTk5MDkzOCAxMyw2LjkzNzUgMTIuODAzMjQyLDYuNDE0NzM0NCAxMi41NjUsNS44OTg1IDEyLjMxMjUsNS40MDYyNSAxMi4zMDgyLDUuMzk3NTMgMTIuMzE2NSw1LjM4MzkwMSAxMi4zMTI1LDUuMzc1IDEyLjI4ODIxNyw1LjMyMjczMTYgMTIuMjY3MzQ3LDUuMjc0NDY4OCAxMi4yNSw1LjIxODc1IDEyLjIzNzk5LDUuMTc2NjM0NiAxMi4yMjY3MzksNS4xMzc2MzU4IDEyLjIxODc1LDUuMDkzNzUgMTIuMjAxMTk5LDUuMDA4MDY4NCAxMi4xODc1LDQuOTAzMzc1IDEyLjE4NzUsNC44MTI1IDEyLjE4NzUsNC4wODU1IDEyLjc3MywzLjUgMTMuNSwzLjUgeiBNIDguNzUsNS45Mzc1IGMgMC4zNzk0MTEzLDAgMC43MzExNDMzLDAuMTc2NTA4OSAwLjk2ODc1LDAuNDM3NSAwLjA3OTIwMiwwLjA4Njk5NyAwLjEzMjQyODksMC4xNzY2NDA5IDAuMTg3NSwwLjI4MTI1IEwgOS45Mzc1LDYuNjI1IGMgMC4wMTkyMzIsMC4wMzc1MjcgMC4wMTI0MTEsMC4wODcyNDEgMC4wMzEyNSwwLjEyNSAwLjU4OTAzMywxLjE4MDYyMTkgMC45ODg3NiwyLjQ4MDY5NjQgMS4xNTYyNSwzLjg0Mzc1IDAuMDU1ODMsMC40NTQzNTEgMC4wOTM3NSwwLjkwNTI2OCAwLjA5Mzc1LDEuMzc1IGwgMCwwLjAzMTI1IDAsMC4wMzEyNSBjIDAsMS45MjQgLTAuNDU5MjUsMy43NDA3NSAtMS4yODEyNSw1LjM0Mzc1IEwgOS45MDYyNSwxNy4zNDM3NSBjIC0wLjIyMDI4NDMsMC40MTg0NzkgLTAuNjE5MTE4MiwwLjcxODc1IC0xLjEyNSwwLjcxODc1IC0wLjcyNiwwIC0xLjMxMjUsLTAuNTg0NSAtMS4zMTI1LC0xLjMxMjUgMCwtMC4yMDU3NDQgMC4wNDA1NjUsLTAuMzg5MDQ5IDAuMTI1LC0wLjU2MjUgTCA3LjU2MjUsMTYuMTU2MjUgYyAwLjYzNCwtMS4yMzcgMSwtMi42NCAxLC00LjEyNSBsIDAsLTAuMDMxMjUgLTAuMDMxMjUsMCAwLC0wLjAzMTI1IGMgMCwtMS40ODUgLTAuMzM0NzUsLTIuODg5IC0wLjk2ODc1LC00LjEyNSBsIDAuMDMxMjUsMCBDIDcuNTQ1NTU3LDcuNzUyMzAxOCA3LjQ5NTc2NDIsNy42NjA0MzU3IDcuNDY4NzUsNy41NjI1IDcuNDQxNzM1Nyw3LjQ2NDU2NDMgNy40Mzc1LDcuMzYwNTU5MSA3LjQzNzUsNy4yNSA3LjQzNzUsNi41MjMgOC4wMjQsNS45Mzc1IDguNzUsNS45Mzc1IHoiICAgICBpZD0icGF0aDExIiAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIgICAgIHRyYW5zZm9ybT0ibWF0cml4KDAuODQ3NDU3NjIsMCwwLDAuODQ3NDU3NjIsMS44MzA1MDg1LDEuODMwNTA4NSkiIC8+PC9zdmc+';
		self::$admin_screens[] = add_submenu_page( Canvas::$slug, 'Configuration', 'Configuration', 'activate_plugins', Canvas::$slug, array( 'CanvasAdmin', 'main_menu' ) );
		self::$admin_screens[] = add_menu_page( 'Canvas', 'Canvas', 'activate_plugins', Canvas::$slug, array( 'CanvasAdmin', 'main_menu' ), $image, '25.31415926' );

		// Show manual notifications only if app id and key set
		if ( Canvas::push_keys_set() ) {
			self::$push_screen     = add_submenu_page( Canvas::$slug, 'Push Notification', 'Push Notifications', 'publish_posts', Canvas::$slug . '_push', array( 'CanvasAdmin', 'push_menu' ) );
			self::$admin_screens[] = self::$push_screen;
		}
		do_action( 'canvas_on_menu', Canvas::$slug );
	}

	public static function current_screen() {
		if ( is_admin() ) {
			$screen = get_current_screen();
			if ( $screen instanceof WP_Screen && in_array( $screen->id, self::$admin_screens ) ) {
				self::add_scripts( self::$push_screen == $screen->id );
			}
		}
	}

	public static function main_menu() {
		$active_tab = ( isset( $_GET['tab'] ) && isset( self::$admin_pages[ $_GET['tab'] ] ) ) ? $_GET['tab'] : 'options';
		// for old WordPress versions
		if ( ! function_exists( 'set_current_screen' ) ) {
			self::add_scripts();
		}
		if ( 'editor' == $active_tab ) {
			self::add_scripts_controls();
		}

		do_action( 'canvas_main_menu', Canvas::$slug );

		// save settings
		$updated = false;
		if ( count( $_POST ) && check_admin_referer( 'form-settings-' . $active_tab ) && ! isset( $_POST['configured'] ) ) {
			switch ( $active_tab ) {
				case 'options':
					Canvas::set_theme( ! empty( $_POST['different_theme_for_app'] ), $_POST['theme'] );
					Canvas::set_option( 'wpadminbar_hide', ! empty( $_POST['wpadminbar_hide'] ) );
					Canvas::set_option( 'identify_app_by_get_param', ! empty( $_POST['identify_app_by_get_param'] ) );
					break;

				case 'editor':
					update_option( 'canvas_editor_css', ( isset( $_POST['canvas_editor_css'] ) ? $_POST['canvas_editor_css'] : '' ) );
					break;

				case 'push':
					Canvas::set_option( 'push_app_id', sanitize_text_field( $_POST['canvas_push_app_id'] ) );
					Canvas::set_option( 'push_key', sanitize_text_field( $_POST['canvas_push_key'] ) );
					Canvas::set_option( 'push_auto_enabled', ! empty( $_POST['canvas_push_auto_enabled'] ) );
					Canvas::set_option( 'push_count_total', '' ); // reset cached number of registered devices.

					Canvas::set_option( 'bb_comment', ! empty( $_POST['canvas_bb_comment'] ) );
					Canvas::set_option( 'bb_reply', ! empty( $_POST['canvas_bb_reply'] ) );

					Canvas::set_option( 'bp_private_messages', ! empty( $_POST['canvas_bp_private_messages'] ) );
					Canvas::set_option( 'bp_private_messages_title', trim( $_POST['canvas_bp_private_messages_title'] ) );
					Canvas::set_option( 'bp_other_notitications', ! empty( $_POST['canvas_bp_other_notitications'] ) );
					Canvas::set_option( 'bp_global_messages', ! empty( $_POST['canvas_bp_global_messages'] ) );
					Canvas::set_option( 'bp_friends', ! empty( $_POST['canvas_bp_friends'] ) );

					Canvas::set_option( 'ld_approved_assignments', ! empty( $_POST['canvas_ld_approved_assignments'] ) );
					Canvas::set_option( 'ld_new_assignment_comment', ! empty( $_POST['canvas_ld_new_assignment_comment'] ) );

					Canvas::set_option( 'ps_mentions_comments', isset( $_POST['canvas_ps_mentions_comments'] ) );
					Canvas::set_option( 'ps_mentions_posts', isset( $_POST['canvas_ps_mentions_posts'] ) );
					Canvas::set_option( 'ps_private_messages', isset( $_POST['canvas_ps_private_messages'] ) );
					Canvas::set_option( 'ps_friends', isset( $_POST['canvas_ps_friends'] ) );
					Canvas::set_option( 'push_woo_email_type', isset( $_POST['canvas_push_woo_email_type'] ) ? $_POST['canvas_push_woo_email_type'] : '' );

					// Categories and taxonomies
					self::push_notification_taxonomies_clear();
					self::push_notification_taxonomies_clear( 'taxonomy' );
					if ( isset( $_POST['canvas_push_categories'] ) ) {
						if ( is_array( $_POST['canvas_push_categories'] ) ) {
							$cat_list = array();
							$tax_list = array();
							foreach ( $_POST['canvas_push_categories'] as $categoryID ) {
								if ( 0 === strpos( $categoryID, 'tax:' ) ) {
									$tax_list[] = absint( str_replace( 'tax:', '', $categoryID ) );
								} else {
									$cat_list[] = $categoryID;
								}
							}
							self::push_notification_taxonomies_set( $cat_list );
							self::push_notification_taxonomies_set( $tax_list, 'taxonomy' );
						}
					}

					// Post types
					$include_post_types = '';
					if ( isset( $_POST['canvas_push_post_types'] ) && count( $_POST['canvas_push_post_types'] ) ) {
						$include_post_types = implode( ',', $_POST['canvas_push_post_types'] );
					}
					Canvas::set_option( 'push_post_types', sanitize_text_field( $include_post_types ) );
					Canvas::set_option( 'push_auto_use_cat', isset( $_POST['canvas_push_auto_use_cat'] ) );

					$push_auto_tags = array();
					if ( ! empty( $_POST['canvas_push_auto_tags'] ) ) {
						$push_auto_tags = explode( ',', $_POST['canvas_push_auto_tags'] );
						foreach ( $push_auto_tags as $key => $value ) {
							$push_auto_tags[ $key ] = strtolower( trim( $value ) );
						}
					}
					Canvas::set_option( 'push_auto_tags', $push_auto_tags );
					Canvas::set_option( 'push_include_image', isset( $_POST['canvas_push_include_image'] ) ? '1' : '0' );

					Canvas::set_option( 'push_log_enable', isset( $_POST['canvas_push_log_enable'] ) );
					Canvas::set_option( 'user_profile', $_POST['canvas_user_profile'] );
					break;

				case 'canvas-login-registration':
					Canvas::set_option( 'login_register_logo_max_width', ( isset( $_POST['canvas_login_register_logo_max_width'] ) ? $_POST['canvas_login_register_logo_max_width'] : '150px' ) );
					Canvas::set_option( 'login_register_logo', ( isset( $_POST['canvas_login_register_logo'] ) ? $_POST['canvas_login_register_logo'] : '' ) );
					Canvas::set_option( 'enabled_registration', ( isset( $_POST['canvas_enabled_registration'] ) ? $_POST['canvas_enabled_registration'] : 0 ) );
					Canvas::set_option( 'forever_logged_in', ( isset( $_POST['canvas_forever_logged_in'] ) ? $_POST['canvas_forever_logged_in'] : 0 ) );
					Canvas::set_option( 'enabled_term', ( isset( $_POST['canvas_enabled_term'] ) ? $_POST['canvas_enabled_term'] : 0 ) );
					Canvas::set_option( 'login_register_redirect_url', ( isset( $_POST['canvas_login_register_redirect_url'] ) ? $_POST['canvas_login_register_redirect_url'] : '' ) );
					Canvas::set_option( 'term_agreement_content', ( isset( $_POST['canvas-term-agreement-content'] ) ? $_POST['canvas-term-agreement-content'] : '' ) );
					Canvas::set_option( 'login_register_bg_color', ( isset( $_POST['canvas_login_register_bg_color'] ) ? $_POST['canvas_login_register_bg_color'] : '#fff' ) );
					Canvas::set_option( 'registration_url', ( isset( $_POST['canvas_registration_url'] ) ? $_POST['canvas_registration_url'] : '' ) );
					Canvas::set_option( 'user_role', ( isset( $_POST['canvas_user_role'] ) ? $_POST['canvas_user_role'] : '' ) );
					Canvas::set_option( 'forgot_pass_url', ( isset( $_POST['canvas_forgot_pass_url'] ) ? $_POST['canvas_forgot_pass_url'] : '' ) );
					Canvas::set_option( 'login_url', ( isset( $_POST['canvas_login_url'] ) ? $_POST['canvas_login_url'] : '' ) );
					Canvas::set_option( 'guest_mode', ( isset( $_POST['canvas_guest_mode'] ) ? $_POST['canvas_guest_mode'] : 0 ) );
					Canvas::set_option( 'term_agreement', ( isset( $_POST['canvas_term_agreement'] ) ? $_POST['canvas_term_agreement'] : '' ) );

					if ( isset( $_POST['canvas-login-editor'] ) ) {
						Canvas::set_option( 'generated-existing-login-html-template', $_POST['canvas-login-editor'] );
					}

					if ( isset( $_POST['canvas-registration-editor'] ) ) {
						Canvas::set_option( 'generated-existing-registration-html-template', $_POST['canvas-registration-editor'] );
					}

					if ( isset( $_POST['canvas-common-css-editor'] ) ) {
						Canvas::set_option( 'generated-existing-css-template', $_POST['canvas-common-css-editor'] );
					}

					break;
			}
			$updated = true;
		}
		// show settings form
		CanvasViews::view(
			'settings-' . $active_tab,
			array(
				'active_tab'   => $active_tab,
				'updated'      => $updated,
				'show_sidebar' => true,
				'tabs'         => self::$admin_pages,
				'tab_path'     => 'admin.php?page=canvas&tab=',
				'show_form'    => true,
			)
		);
	}

	public static function push_menu() {
		// for old WordPress versions
		if ( ! function_exists( 'set_current_screen' ) ) {
			self::add_scripts( true );
		}
		do_action( 'canvas_push_menu', Canvas::$slug );

		// show settings form
		$active_tab = 'notifications';
		CanvasViews::view(
			'push-notifications',
			array(
				'active_tab'   => $active_tab,
				'tabs'         => self::$admin_notifications,
				'tab_path'     => 'admin.php?page=canvas_push&tab=',
				'show_sidebar' => false,
			)
		);
	}

	/**
	 * Add css code editor features when WordPress provide it.
	 *
	 * @since 3.2
	 */
	public static function add_scripts_controls() {
		if ( function_exists( 'wp_enqueue_code_editor' ) ) { // WordPress 4.9.0+
			// enqueue editor scripts and settings.
			wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		}
	}

	public static function add_scripts( $add_google = false ) {
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		// Codemirror scripts.
		$cm_settings = array(
			'css'  => wp_enqueue_code_editor( array( 'type' => 'text/css' ) ),
			'html' => wp_enqueue_code_editor( array( 'type' => 'text/html' ) ),
		);
		wp_localize_script( 'jquery', 'canvas_editor', $cm_settings );

		wp_enqueue_script( 'wp-theme-plugin-editor' );
		wp_enqueue_style( 'wp-codemirror' );

		wp_register_script( 'areyousure', CANVAS_URL . 'assets/libs/jquery.are-you-sure.js', array( 'jquery' ), CANVAS_PLUGIN_VERSION, true );
		wp_register_script( 'jquerychosen', CANVAS_URL . 'assets/libs/chosen/chosen.jquery.min.js', array( 'jquery' ), CANVAS_PLUGIN_VERSION, true );
		wp_register_script( 'notify-js', CANVAS_URL . 'assets/libs/notify/notify.min.js', array( 'jquery' ), CANVAS_PLUGIN_VERSION );
		wp_register_script( 'canvas-admin-js', CANVAS_URL . 'build/canvas-admin.js', array( 'jquery', 'jquerychosen', 'areyousure', 'notify-js', 'wp-color-picker' ), CANVAS_PLUGIN_VERSION, true );
		if ( $add_google ) {
			wp_register_script( 'google_chart', 'https://www.google.com/jsapi' );
			wp_enqueue_script( 'google_chart' );
		}
		wp_register_script( 'sweetalert2-js', CANVAS_URL . 'assets/libs/sweetalert/sweetalert.min.js', array( 'jquery' ), CANVAS_PLUGIN_VERSION );
		wp_enqueue_script( 'sweetalert2-js' );
		wp_enqueue_script( 'areyousure' );
		wp_enqueue_script( 'jquerychosen' );
		wp_enqueue_script( 'notify-js' );
		wp_enqueue_script( 'canvas-admin-js' );

		wp_register_style( 'canvas-css', CANVAS_URL . 'build/canvas-admin.css', array(), CANVAS_PLUGIN_VERSION );
		wp_register_style( 'jquerychosen-css', CANVAS_URL . 'assets/libs/chosen/chosen.css', array(), CANVAS_PLUGIN_VERSION );
		wp_enqueue_style( 'jquerychosen-css' );
		wp_enqueue_style( 'canvas-css' );
	}

	/**
	 * Save options from Theme Options tab
	 */
	public static function save_theme() {
		Canvas::set_theme( ! empty( $_POST['use'] ), $_POST['theme'] );
		die( 'Ok' );
	}

	/**
	 * Return name of log file
	 */
	public static function get_push_log_name( $web_path = false ) {
		$filename = Canvas::get_option( 'push_log_name' );
		if ( empty( $filename ) ) {
			$site     = str_replace( array( 'https://', 'http://', '/', ':' ), array( '', '', '_', '' ), get_site_url() );
			$filename = $site . '-canvaspush' . rand( 10000000, 99999999 ) . '.txt';
			Canvas::set_option( 'push_log_name', $filename );
		}
		$paths = wp_upload_dir();
		if ( $web_path ) {
			return $paths['baseurl'] . '/' . $filename;
		} else {
			return $paths['basedir'] . '/' . $filename;
		}
	}

	/**
	 * Get list of categories or taxonomies allowed for notifications
	 *
	 * @param string $taxonomy
	 */
	public static function push_notification_taxonomies_get( $taxonomy = 'category' ) {
		return Canvas::get_option( 'push_list_' . $taxonomy, array() );
	}

	/**
	 * Clear list of categories or taxonomies allowed for notifications
	 *
	 * @param string $taxonomy
	 */
	private static function push_notification_taxonomies_clear( $taxonomy = 'category' ) {
		Canvas::set_option( 'push_list_' . $taxonomy, array() );
	}

	/**
	 * Save list of categories or taxonomies allowed for notifications
	 *
	 * @param array  $taxonomies_list
	 * @param string $taxonomy
	 */
	private static function push_notification_taxonomies_set( $taxonomies_list, $taxonomy = 'category' ) {
		Canvas::set_option( 'push_list_' . $taxonomy, $taxonomies_list );
	}

	/**
	 * Check if currently on plugin page
	 */
	public static function using_canvas() {
		 return isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'canvas', 'canvas_push' ) ) !== false;
	}

	public static function schedule_dismiss() {
		 Canvas::set_option( 'schedule_dismiss', time() );
		echo 'OK';
		die();
	}

	/**
	* Clean notification history.
	*
	* @since 3.5.3
	*/
	public static function clean_history() {
		if ( check_ajax_referer( 'canvas-clean-history' ) ) {
			CanvasNotificationsDb::clean_notifications();
			echo 'OK';
		}
		die();
	}

	/**
	* Clean notification history.
	*
	* @since 3.5.3
	*/
	public static function clean_log() {
		if ( check_ajax_referer( 'canvas-clean-log' ) ) {
			CanvasNotificationsDb::clean_logs();
			echo 'OK';
		}
		die();
	}

	public static function canvas_get_posts_for_notification() {
		$search_term = filter_input( INPUT_GET, 'search_term', FILTER_SANITIZE_STRING );

		$args  = array(
			'post_type' => empty( $search_term ) ? 'post' : 'any',
			's'         => $search_term,
		);

		$post_data = array();

		$query = new \WP_Query( $args );

		while ( $query->have_posts() ) {
			$query->the_post();

			$content = empty( get_the_excerpt() ) ? get_the_content() : get_the_excerpt();

			$post_type = get_post_type( get_the_ID() );

			$post_data[] = array(
				'id'      => get_the_ID(),
				'text'    => html_entity_decode( get_the_title() ),
				'content' => strip_shortcodes( wp_strip_all_tags( html_entity_decode( $content ) ) ),
				'tags'    => ( 'post' === $post_type ) ? wp_get_post_categories( get_the_ID(), array(
					'fields' => 'slugs'
				) ) : array(),
			);
		}

		wp_send_json_success( $post_data );
	}

	public static function welcome_screen_is_avalaible() {
		return ! Canvas::push_keys_set();
	}

	public static function connect_fs( $url, $method, $context, $fields = null ) {
		if ( false === ( $credentials = request_filesystem_credentials( $url, $method, false, $context, $fields ) ) ) {
			return false;
		}

		//check if credentials are correct or not.
		if ( ! WP_Filesystem( $credentials ) ) {
			request_filesystem_credentials( $url, $method, true, $context );
			return false;
		}

		return true;
	}

	public static function generate_login_template_and_save_to_db() {
		if ( isset( $_GET['canvas-delete-editor-templates'] ) ) {
			delete_option( 'canvas-generated-existing-login-html-template-complete' );
		}

		$isset = Canvas::get_option( 'generated-existing-login-html-template-complete' );

		if ( 'yes' === $isset ) {
			return;
		}

		$input_valid_class = empty( $error_message ) ? '' : ' error';
		$is_enabled_registration = Canvas::get_option( 'enabled_registration' ) === '1';
		$register_txt = Canvas::get_option( 'canvas-ls-register-now', 'Register now' );
		$username_label = Canvas::get_option( 'canvas-ls-username', 'Username:' );
		$password_label = Canvas::get_option( 'canvas-ls-password', 'Password:' );
		$login_btn_txt = Canvas::get_option( 'canvas-ls-login', 'Log In' );
		$registration_url = Canvas::get_option( 'registration_url', home_url( '/canvas-api/registration' ) );
		$forgot_pass_url  = Canvas::get_option( 'forgot_pass_url', home_url( '/canvas-api/forgot-password' ) );
		$guest_mode_enabled = Canvas::get_option( 'guest_mode' ) === '1';
		$guest_mode_txt = Canvas::get_option( 'canvas-ls-continue-as-guest', 'Continue as Guest' );
		$forgot_password_text = Canvas::get_option( 'canvas-ls-forgot-password', 'Forgot your password?' );

		$form_string = '';
		$form_string .= '{{ canvas_logo }}' . PHP_EOL;
		$form_string .= '{{ canvas_notices }}' . PHP_EOL;
		$form_string .= '<form name="canvas-loginform" class="canvas-form" id="canvas-loginform" method="post">' . PHP_EOL;
		$form_string .= "\t" . '{{ canvas_login_nonce }}' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-username canvas-form-group">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<p><label for="canvas_user_login">%s</label></p>', $username_label ) . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<input type="text" name="canvas_user_login" id="canvas_user_login" class="input%s" value="" required>', $input_valid_class ) . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-password canvas-form-group">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<p><label for="canvas_user_pass">%s</label></p>', $password_label ) . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<input type="password" name="canvas_user_pass" id="canvas_user_pass" class="input%s" value="" required>', $input_valid_class ) . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-submit canvas-form-group" id="submit-container">' . PHP_EOL;
		$form_string .= "\t\t" . '<input type="hidden" name="canvas_login_submit">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="%s">', $login_btn_txt ) . PHP_EOL;
		$form_string .= "\t\t" . '<div class="spinner-loading hide">' . PHP_EOL;
		$form_string .= "\t\t\t" . '{{ canvas_spinner }}' . PHP_EOL;
		$form_string .= "\t\t" . '</div>' . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-custom-action">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<p><a id="forgot-password-link" href="%s?app=true">%s</a></p>', $forgot_pass_url, $forgot_password_text ) . PHP_EOL;

		$form_string .= "\t\t" . sprintf( '<div class="%s">', $is_enabled_registration || $guest_mode_enabled ? '' : 'canvas-hide-field' ) . PHP_EOL;
		$form_string .= "\t\t\t" . sprintf( '<a class="%s" id="register-link" href="%s?app=true">%s</a>', $is_enabled_registration ? '' : 'canvas-hide-field', $registration_url, $register_txt ) . PHP_EOL;
		$form_string .= "\t\t\t" . sprintf( '<div class="%s">', $guest_mode_enabled ? '' : 'canvas-hide-field' ) . PHP_EOL;

		$form_string .= "\t\t\t\t" . ( $is_enabled_registration ? ' or ' : '' );
		$form_string .= PHP_EOL;
		$form_string .= "\t\t\t\t" . '<a id="guest-mode-link" href="#" onclick="nativeFunctions.closeModal()">' . PHP_EOL;
		$form_string .= "\t\t\t\t\t" . $guest_mode_txt . PHP_EOL;
		$form_string .= "\t\t\t\t" . '</a>' . PHP_EOL . PHP_EOL;

		$form_string .= "\t\t\t" . '</div>' . PHP_EOL;
		$form_string .= "\t\t" . '</div>' . PHP_EOL;

		$form_string .= "\t" . '</div>' . PHP_EOL;
		$form_string .= '</form>';

		Canvas::set_option( 'generated-existing-login-html-template', $form_string );
		Canvas::set_option( 'generated-existing-login-html-template-complete', 'yes' );
	}

	public static function generate_registration_template_and_save_to_db() {
		if ( isset( $_GET['canvas-delete-editor-templates'] ) ) {
			delete_option( 'canvas-generated-existing-registration-html-template-complete' );
		}

		$isset = Canvas::get_option( 'generated-existing-registration-html-template-complete' );

		if ( 'yes' === $isset ) {
			return;
		}

		$input_valid_class = empty( $error_message ) ? '' : ' error';
		$term_of_agreement_text = Canvas::get_option( 'term_agreement' );
		$term_content = Canvas::get_option( 'term_agreement_content' );
		$enabled_term = Canvas::get_option( 'enabled_term' );
		$default_email = isset( $_POST['canvas_user_email'] ) ? sanitize_text_field( wp_unslash( $_POST['canvas_user_email'] ) ) : '';
		$email_label = Canvas::get_option( 'canvas-rs-username', 'Email:' );
		$password_label = Canvas::get_option( 'canvas-rs-password', 'Password:' );
		$register_btn_txt = Canvas::get_option( 'canvas-rs-register', 'Register' );
		$back_to_login_txt = Canvas::get_option( 'canvas-rs-login', 'Login' );
		$login_url = Canvas::get_option( 'login_url', home_url( '/canvas-api/login' ) );
		$login_url_parsed = wp_parse_url( $login_url );

		if ( ! isset( $login_url_parsed['host'] ) && isset( $login_url_parsed['path'] ) ) {
			$login_url = home_url( $login_url_parsed['path'] );
		}

		$form_string = '';

		$form_string .= '{{ canvas_logo }}' . PHP_EOL;
		$form_string .= '{{ canvas_notices }}' . PHP_EOL;
		$form_string .= '<form name="canvas-register-form" class="canvas-form" id="canvas-register-form" method="post">' . PHP_EOL;
		$form_string .= "\t" . '{{ canvas_registration_nonce }}' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-username canvas-form-group">' . PHP_EOL;
		$form_string .= sprintf( "\t\t" . '<p><label for="canvas_user_email">%s</label></p>' . PHP_EOL, $email_label );
		$form_string .= sprintf( "\t\t" . '<input type="email" name="canvas_user_email" id="canvas_user_email" class="input%s" value="%s" required>' . PHP_EOL, $input_valid_class, $default_email );
		$form_string .= "\t" . '</div>' . PHP_EOL . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-password canvas-form-group">' . PHP_EOL;
		$form_string .= sprintf( "\t\t" . '<p><label for="canvas_user_pass">%s</label></p>' . PHP_EOL, $password_label );
		$form_string .= sprintf( "\t\t" . '<input type="password" name="canvas_user_pass" id="canvas_user_pass" class="input%s" value="" required>' . PHP_EOL, $input_valid_class );
		$form_string .= "\t" . '</div>' . PHP_EOL . PHP_EOL;

		$form_string .= "\t" . sprintf( '<div class="canvas-login-remember canvas-form-group %s">' . PHP_EOL, ! empty( $term_of_agreement_text ) && $enabled_term ? '' : 'canvas-hide-field' );
		$form_string .= "\t" . '<label for="canvas_agree_term" id="label_canvas_agree_term">' . PHP_EOL;
		$form_string .= stripslashes( $term_of_agreement_text ) . PHP_EOL;
		$form_string .= "\t\t" . '<input name="canvas_agree_term" type="checkbox" id="canvas_agree_term" value="1" required>' . PHP_EOL;
		$form_string .= "\t\t" . '<span class="checkmark"></span>' . PHP_EOL;
		$form_string .= "\t" . '</label>' . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-login-submit canvas-form-group" id="submit-container">' . PHP_EOL;
		$form_string .= "\t\t" . '<input type="hidden" name="canvas_register_submit">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="%s">' . PHP_EOL, $register_btn_txt );
		$form_string .= "\t\t" . '<div class="spinner-loading hide">' . PHP_EOL;
		$form_string .= "\t\t\t" . '{{ canvas_spinner }}' . PHP_EOL;
		$form_string .= "\t\t" . '</div>' . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL . PHP_EOL;

		$form_string .= "\t" . '<div class="canvas-custom-action">' . PHP_EOL;
		$form_string .= "\t\t" . sprintf( '<p><a id="login-link" href="%s?app=true">%s</a></p>' . PHP_EOL, $login_url, $back_to_login_txt );
		$form_string .= "\t" . '</div>' . PHP_EOL;

		$form_string .= '</form>' . PHP_EOL . PHP_EOL;

		$form_string .= sprintf( '<div id="term-content" class="modal %s">' . PHP_EOL, ! empty( $term_content ) ? '' : 'canvas-hide-field' );
		$form_string .= "\t" . '<div class="modal-content">' . PHP_EOL;
		$form_string .= "\t\t" . '<span id="close-term-modal">&times;</span>' . PHP_EOL;
		$form_string .= "\t\t" . stripslashes( wpautop( $term_content ) ) . PHP_EOL;
		$form_string .= "\t" . '</div>' . PHP_EOL;
		$form_string .= '</div>' . PHP_EOL;

		Canvas::set_option( 'generated-existing-registration-html-template', $form_string );
		Canvas::set_option( 'generated-existing-registration-html-template-complete', 'yes' );
	}

	public static function generate_css_template() {
		if ( isset( $_GET['canvas-delete-editor-templates'] ) ) {
			delete_option( 'canvas-generated-existing-css-template-complete' );
		}

		$isset = Canvas::get_option( 'generated-existing-css-template-complete' );

		if ( 'yes' === $isset ) {
			return;
		}

		$fields_arr = array(
			'bg_color'           => array(
				'element'       => 'body',
				'attribute'     => 'background-color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'btn_bg_color'       => array(
				'element'       => '#wp-submit,.canvas-login-remember input:checked ~ .checkmark, #wp-link-submit',
				'attribute'     => 'background-color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'btn_bg_hover_color' => array(
				'element'       => '#wp-submit:not(.loading):hover, #wp-link-submit:not(.loading):hover',
				'attribute'     => 'background-color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'btn_text_color'     => array(
				'element'       => '#wp-submit, #wp-link-submit',
				'attribute'     => 'color',
				'default_value' => '#fff',
				'important'     => true,
			),
			'text_color'         => array(
				'element'       => '.canvas-form .canvas-form-group label,.notice-wrapper h3, .notice-wrapper p',
				'attribute'     => 'color',
				'default_value' => '#000',
				'important'     => true,
			),
			'link_color'         => array(
				'element'       => '.canvas-custom-action a,.back-to-login',
				'attribute'     => 'color',
				'default_value' => '#000',
				'important'     => true,
			),
			'link_hover_color'   => array(
				'element'       => '.canvas-custom-action a:hover,.back-to-login:hover',
				'attribute'     => 'color',
				'default_value' => '#9C57C0',
				'important'     => true,
			),
			'spinner_color'      => array(
				'elements'      => array(
					array(
						'element'   => '.spinner-ios div, #loading-full-page .spinner-ios div',
						'attribute' => 'background-color',
						'important' => true,
					),
					array(
						'element'   => '.spinner-android .circonf-2',
						'attribute' => 'border-color',
						'important' => false,
					),
				),
				'default_value' => '#9C57C0',
			),
			'logo_max_width'     => array(
				'element'       => '.canvas-logo',
				'attribute'     => 'max-width',
				'default_value' => '150px',
				'important'     => true,
			),
		);

		$styles     = '';
		foreach ( $fields_arr as $key => $option_arr ) {
			$value = Canvas::get_option( 'login_register_' . $key, $option_arr['default_value'] );
			if ( ! isset( $option_arr['elements'] ) ) {
				$important = $option_arr['important'] ? '!important' : '';
				$styles   .= $option_arr['element'] . " "  . '{' . "\n\t" . $option_arr['attribute'] . ': ' . $value . ' ' . $important .  ';' . "\n" . '}' . PHP_EOL . PHP_EOL;
			} else {
				foreach ( $option_arr['elements'] as $element ) {
					$important = $element['important'] ? '!important' : '';
					$styles   .= $element['element'] . " " . '{' . "\n\t" . $element['attribute'] . ': ' . $value . ' ' . $important . ';' . "\n" . '}' . PHP_EOL . PHP_EOL;
				}
			}
		}

		Canvas::set_option( 'generated-existing-css-template', stripslashes( $styles ) );
		Canvas::set_option( 'generated-existing-css-template-complete', 'yes' );
	}

	/**
	 * Redirects to the login/registration template editor screen
	 * after restoring original templates.
	 */
	public static function redirect_after_deleting_templates() {
		if ( ! isset( $_GET['canvas-delete-editor-templates'] ) ) {
			return;
		}

		wp_redirect(
			admin_url( 'admin.php?page=canvas&tab=canvas-login-registration' )
		);

		die;
	}
}
