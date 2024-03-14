<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */

/*
 * Plugin Name: Football pool
 * Author: Antoine Hurkmans
 * Author URI: mailto:wordpressfootballpool@gmail.com
 * Plugin URI: http://wordpress.org/support/plugin/football-pool
 * Description: This plugin adds a fantasy sports pool to your blog. Play against other users, predict outcomes of matches and earn points.
 * Tags: pool, football, prediction, competition, world cup, european championship, champions league, fantasy football, sports
 * License: GPLv3 or later
 * Text Domain: football-pool
 * Domain Path: /languages
 * Requires at least: 4.7
 * Requires PHP: 7.4
 * Version: 2.11.4
 */

const FOOTBALLPOOL_DB_VERSION = '2.11.4';

if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
	// Let's not load Football Pool during cron events.
	// Maybe in a future version I can add some cron actions. But right now we have none.
	return;
}

// Load global constants and default settings.
require_once 'define.php';

// Try to exclude plugin from persistent object caching
wp_cache_add_non_persistent_groups( [FOOTBALLPOOL_WPCACHE_NON_PERSISTENT] );

// Plugin base classes; always needed.
require_once 'classes/class-football-pool.php';
require_once 'classes/class-football-pool-utils.php';
require_once 'classes/class-football-pool-teams.php';
require_once 'classes/class-football-pool-team.php';
require_once 'classes/class-football-pool-matches.php';
require_once 'classes/class-football-pool-stadiums.php';
require_once 'classes/class-football-pool-stadium.php';
require_once 'classes/class-football-pool-groups.php';
require_once 'classes/class-football-pool-pool.php';

if ( ! wp_doing_ajax() ) {
	require_once 'classes/class-football-pool-shoutbox.php';
	require_once 'classes/class-football-pool-pagination.php';
	require_once 'classes/class-football-pool-chart.php';
}

// Not needed in the admin.
if ( ! is_admin() && ! wp_doing_ajax() ) {
	require_once 'pages/class-football-pool-tournament-page.php';
	require_once 'pages/class-football-pool-teams-page.php';
	require_once 'pages/class-football-pool-groups-page.php';
	require_once 'pages/class-football-pool-stadiums-page.php';
	require_once 'pages/class-football-pool-ranking-page.php';
	require_once 'pages/class-football-pool-statistics-page.php';
	require_once 'pages/class-football-pool-user-page.php';
	require_once 'pages/class-football-pool-pool-page.php';
	require_once 'classes/class-football-pool-chart-data.php';
	require_once 'classes/class-football-pool-statistics.php';
	require_once 'classes/class-football-pool-shortcodes.php';
}

// Widgets (always load these; front-end, admin and ajax).
require_once 'classes/class-football-pool-widget.php'; // First load the base class and then all the widgets.
require_once 'widgets/widget-football-pool-ranking.php';
require_once 'widgets/widget-football-pool-lastgames.php';
require_once 'widgets/widget-football-pool-shoutbox.php';
require_once 'widgets/widget-football-pool-group.php';
require_once 'widgets/widget-football-pool-next-prediction.php';

// Plugin initialisation and activation or update.
// Activate the plugin
register_activation_hook( __FILE__, ['Football_Pool', 'activate'] );
register_deactivation_hook( __FILE__, ['Football_Pool', 'deactivate'] );

// Upgrading the plugin?
add_action( 'plugins_loaded', ['Football_Pool', 'update_db_check'], 10 );

if ( ! wp_doing_ajax() ) {
	// Initialize the plugin.
	add_action( 'init', ['Football_Pool', 'init'] );
}

// Admin bar and content handling.
if ( ! is_admin() && ! wp_doing_ajax() ) {
	add_filter( 'show_admin_bar', ['Football_Pool', 'show_admin_bar'] );
	add_filter( 'the_content', ['Football_Pool', 'the_content'], FOOTBALLPOOL_CONTENT_FILTER_PRIORITY );
	if ( FOOTBALLPOOL_CHANGE_STATS_TITLE ) {
		add_filter( 'the_title', ['Football_Pool_Statistics_Page', 'stats_page_title'],
			FOOTBALLPOOL_CONTENT_FILTER_PRIORITY );
	}
	add_action( 'wp_head', ['Football_Pool', 'change_html_head'] );
	add_filter( 'document_title_parts', ['Football_Pool', 'change_wp_title'],
		FOOTBALLPOOL_CONTENT_FILTER_PRIORITY );
}

// User registration extension (precaution: also set for AJAX requests).
add_action( 'user_register', ['Football_Pool', 'new_pool_user'] );
add_action( 'register_form', ['Football_Pool', 'registration_form_extra_fields'] );
add_action( 'register_post', ['Football_Pool', 'registration_form_post'], null, 3 );
add_filter( 'registration_errors', ['Football_Pool', 'registration_check_fields'], null, 3 );
// Redirect players of the pool after login or registration
add_filter( 'login_redirect', ['Football_Pool', 'player_login_redirect'],
	FOOTBALLPOOL_REDIRECT_FILTER_PRIORITY, 3 );
add_filter( 'registration_redirect', ['Football_Pool', 'player_registration_redirect'],
	FOOTBALLPOOL_REDIRECT_FILTER_PRIORITY );

// Personal data exporter.
require_once 'admin/class-football-pool-admin-personal-data.php';
add_filter( 'wp_privacy_personal_data_exporters', ['Football_Pool_Admin_Personal_Data', 'register_user_data_exporters'] );
if ( Football_Pool_Utils::get_fp_option( 'erase_personal_data', 0, 'int' ) === 1 ) {
	add_filter( 'wp_privacy_personal_data_erasers', ['Football_Pool_Admin_Personal_Data', 'register_privacy_erasers'] );
}

// Needed for admin and wp-cli.
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
	require_once 'admin/class-football-pool-admin.php'; // base class for the admin
	require_once 'admin/class-football-pool-admin-score-calculation.php';
	require_once 'admin/class-football-pool-admin-users.php';

	add_action( 'deleted_user', ['Football_Pool_Admin_Users', 'delete_user_from_pool'] );
}

// Admin only.
if ( is_admin() && ! wp_doing_ajax() ) {
	require_once 'admin/class-football-pool-admin-options.php';
	require_once 'admin/class-football-pool-admin-games.php';
	require_once 'admin/class-football-pool-admin-bonusquestions.php';
	require_once 'admin/class-football-pool-admin-teams.php';
//	require_once 'admin/class-football-pool-admin-teams-position.php';
	require_once 'admin/class-football-pool-admin-leagues.php';
	require_once 'admin/class-football-pool-admin-shoutbox.php';
	require_once 'admin/class-football-pool-admin-help.php';
	require_once 'admin/class-football-pool-admin-users.php';
	require_once 'admin/class-football-pool-admin-stadiums.php';
	require_once 'admin/class-football-pool-admin-matchtypes.php';
	require_once 'admin/class-football-pool-admin-groups.php';
	require_once 'admin/class-football-pool-admin-rankings.php';
	require_once 'admin/class-football-pool-admin-feature-pointers.php';
	require_once 'admin/class-football-pool-admin-predictions-audit-log.php';

	add_action( 'admin_menu', ['Football_Pool_Admin', 'admin_menu_init'] );
	// add_action( 'admin_head', array( 'Football_Pool_Admin', 'adminhook_suffix' ) ); // debugging helper
	add_action( 'show_user_profile', ['Football_Pool_Admin_Users', 'add_extra_profile_fields'] );
	add_action( 'edit_user_profile', ['Football_Pool_Admin_Users', 'add_extra_profile_fields'] );
	add_action( 'personal_options_update', ['Football_Pool_Admin_Users', 'update_user_options'] );
	add_action( 'edit_user_profile_update', ['Football_Pool_Admin_Users', 'update_user_options'] );
	add_action( 'admin_enqueue_scripts', ['Football_Pool_Admin', 'initialize_wp_media'] );
	add_action( 'wp_dashboard_setup', ['Football_Pool', 'add_dashboard_widgets'] );
	if ( Football_Pool_Utils::get_fp_option( 'add_tinymce_button', 1, 'int' ) === 1 ) {
		add_action( 'admin_init', ['Football_Pool_Admin', 'tinymce_add_plugin'] );
	}
	// add_action( 'admin_notices', ['Football_Pool', 'admin_notice'] );
	add_action( 'admin_enqueue_scripts', ['Football_Pool_Admin_Feature_Pointers', 'init'] );
	add_filter( 'admin_body_class', ['Football_Pool_Admin', 'add_body_class'] );
	add_filter( 'plugin_action_links', ['Football_Pool_Admin', 'add_plugin_settings_link'], null, 2 );
	// note to self: don't remove the '10' in the following line! It will break again ;)
	add_filter( 'set-screen-option', ['Football_Pool_Admin', 'set_screen_options'], 10, 3 );
	// Add plugin upgrade notification.
	add_action( 'in_plugin_update_message-' . plugin_basename( __FILE__ ),
		['Football_Pool', 'show_upgrade_notification'], null, 2 );

	add_filter( 'plugins_loaded', ['Football_Pool_Admin', 'init_admin'], 20 );
}

// AJAX calls.
if ( is_admin() ) {
	// Score calculation.
	add_action( 'wp_ajax_footballpool_calculate_scorehistory', ['Football_Pool_Admin_Score_Calculation', 'process'] );
	// Set joker.
	add_action( 'wp_ajax_footballpool_update_joker', ['Football_Pool_Pool', 'update_joker'] );
	// Save match score.
	add_action( 'wp_ajax_footballpool_update_team_prediction', ['Football_Pool_Pool', 'update_prediction'] );
	// Save bonus question.
	add_action( 'wp_ajax_footballpool_update_bonus_question', ['Football_Pool_Pool', 'update_question'] );
}

// WP-CLI commands.
if ( defined( 'WP_CLI' ) && WP_CLI && ! wp_doing_ajax() ) {
	require_once 'cli/class-football-pool-cli-score-calculation.php';
	require_once 'cli/class-football-pool-cli-import-match-results.php';
	require_once 'cli/class-football-pool-cli-create-test-data.php';
}
