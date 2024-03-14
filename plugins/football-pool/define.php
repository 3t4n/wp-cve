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

global $wpdb, $pool;

// Admin screen options (defaults per page)
const FOOTBALLPOOL_ADMIN_DEFAULT_PER_PAGE = 20;
if ( ! defined( 'FOOTBALLPOOL_ADMIN_USERS_PER_PAGE' ) ) define( 'FOOTBALLPOOL_ADMIN_USERS_PER_PAGE', 20 );
if ( ! defined( 'FOOTBALLPOOL_ADMIN_MATCHES_PER_PAGE' ) ) define( 'FOOTBALLPOOL_ADMIN_MATCHES_PER_PAGE', 50 );
if ( ! defined( 'FOOTBALLPOOL_ADMIN_USER_ANWERS_PER_PAGE' ) ) define( 'FOOTBALLPOOL_ADMIN_USER_ANWERS_PER_PAGE', 50 );

// Database and path constants
if ( ! defined( 'FOOTBALLPOOL_DB_PREFIX' ) ) define( 'FOOTBALLPOOL_DB_PREFIX', 'pool_' . $wpdb->prefix );
if ( ! defined( 'FOOTBALLPOOL_OPTIONS' ) ) define( 'FOOTBALLPOOL_OPTIONS', 'footballpool_plugin_options' );

if ( ! defined( 'FOOTBALLPOOL_PLUGIN_URL' ) ) define( 'FOOTBALLPOOL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
if ( ! defined( 'FOOTBALLPOOL_PLUGIN_DIR' ) ) define( 'FOOTBALLPOOL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
const FOOTBALLPOOL_PLUGIN_NAME = 'Football Pool';
const FOOTBALLPOOL_TEXT_DOMAIN = 'football-pool';

if ( ! defined( 'FOOTBALLPOOL_HIGHCHARTS_API' ) ) define( 'FOOTBALLPOOL_HIGHCHARTS_API', '/highcharts-js/highcharts.js' );

const FOOTBALLPOOL_ASSETS_URL = FOOTBALLPOOL_PLUGIN_URL . 'assets/';
if ( ! defined( 'FOOTBALLPOOL_ERROR_LOG' ) ) define( 'FOOTBALLPOOL_ERROR_LOG', FOOTBALLPOOL_PLUGIN_DIR . '_error_log.txt' );

$upload_dir = wp_upload_dir();
define( 'FOOTBALLPOOL_UPLOAD_DIR', trailingslashit( $upload_dir['basedir'] ) . trailingslashit( 'football-pool' ) );
define( 'FOOTBALLPOOL_UPLOAD_URL', trailingslashit( $upload_dir['baseurl'] ) . trailingslashit( 'football-pool' ) );

if ( ! defined( 'FOOTBALLPOOL_IMPORT_DIR' ) )
	define( 'FOOTBALLPOOL_IMPORT_DIR', trailingslashit( FOOTBALLPOOL_UPLOAD_DIR . 'imports' ) );

// Pool
if ( ! defined( 'FOOTBALLPOOL_DEFAULT_SEASON' ) ) define( 'FOOTBALLPOOL_DEFAULT_SEASON', 1 );
if ( ! defined( 'FOOTBALLPOOL_FRONTEND_AJAX' ) ) define( 'FOOTBALLPOOL_FRONTEND_AJAX', false );

// Leagues
if ( ! defined( 'FOOTBALLPOOL_LEAGUE_ALL' ) ) define( 'FOOTBALLPOOL_LEAGUE_ALL', 1 );
if ( ! defined( 'FOOTBALLPOOL_LEAGUE_DEFAULT' ) ) define( 'FOOTBALLPOOL_LEAGUE_DEFAULT', 3 );

// Score history
if ( ! defined( 'FOOTBALLPOOL_CALC_STEPSIZE_MATCH' ) ) define( 'FOOTBALLPOOL_CALC_STEPSIZE_MATCH', 400 );
if ( ! defined( 'FOOTBALLPOOL_CALC_STEPSIZE_QUESTION' ) ) define( 'FOOTBALLPOOL_CALC_STEPSIZE_QUESTION', 400 );
if ( ! defined( 'FOOTBALLPOOL_CALC_STEPSIZE_SCORE' ) ) define( 'FOOTBALLPOOL_CALC_STEPSIZE_SCORE', 400 );
if ( ! defined( 'FOOTBALLPOOL_CALC_STEPSIZE_RANKING' ) ) define( 'FOOTBALLPOOL_CALC_STEPSIZE_RANKING', 400 );

const FOOTBALLPOOL_CALC_SESSION = 'footballpool_calc_session';
const FOOTBALLPOOL_LAST_CALC_DATE = 'footballpool_last_calc_date';

const FOOTBALLPOOL_SCORE_TABLE1_FORMAT = 'scorehistory_s%s_t1';
const FOOTBALLPOOL_SCORE_TABLE2_FORMAT = 'scorehistory_s%s_t2';

if ( ! defined( 'FOOTBALLPOOL_RANKING_CALCULATION_AJAX' ) ) define( 'FOOTBALLPOOL_RANKING_CALCULATION_AJAX', true );
if ( ! defined( 'FOOTBALLPOOL_FORCE_CALCULATION' ) ) define( 'FOOTBALLPOOL_FORCE_CALCULATION', false );

const FOOTBALLPOOL_RANKING_AUTOCALCULATION = 1;
const FOOTBALLPOOL_RANKING_DEFAULT         = 1;

const FOOTBALLPOOL_TYPE_MATCH = 0;
const FOOTBALLPOOL_TYPE_QUESTION = 1;

// Default points
const FOOTBALLPOOL_JOKERMULTIPLIER = 2;
const FOOTBALLPOOL_FULLPOINTS      = 5; // 3
const FOOTBALLPOOL_TOTOPOINTS      = 2; // 2
const FOOTBALLPOOL_GOALPOINTS      = 0; // 1
const FOOTBALLPOOL_DIFFPOINTS      = 0; // bonus points for correct goal difference
                                        // (e.g. match result is 4-0 and prediction is 6-2)

// Matches CSV import and export
//define( 'FOOTBALLPOOL_CSV_DATE_FORMAT', 'Y-m-d H:i' ); // set it in the wp-config!
if ( ! defined( 'FOOTBALLPOOL_CSV_DELIMITER' ) ) define( 'FOOTBALLPOOL_CSV_DELIMITER', ',' );
if ( ! defined( 'FOOTBALLPOOL_CSV_UPLOAD_DIR' ) ) define( 'FOOTBALLPOOL_CSV_UPLOAD_DIR', trailingslashit( FOOTBALLPOOL_UPLOAD_DIR . 'schedules' ) );

// Groups page
const FOOTBALLPOOL_GROUPS_PAGE_DEFAULT_MATCHTYPE = 1;
if ( ! defined( 'FOOTBALLPOOL_TEAM_POINTS_WIN' ) ) define( 'FOOTBALLPOOL_TEAM_POINTS_WIN', 3 );
if ( ! defined( 'FOOTBALLPOOL_TEAM_POINTS_DRAW' ) ) define( 'FOOTBALLPOOL_TEAM_POINTS_DRAW', 1 );

// Predictions
const FOOTBALLPOOL_MAXPERIOD = 15 * MINUTE_IN_SECONDS;
const FOOTBALLPOOL_DEFAULT_JOKERS = 1;
const FOOTBALLPOOL_DEFAULT_JOKERS_SETTING = 1;
if ( ! defined( 'FOOTBALLPOOL_AJAX_SAVE_DELAY' ) ) define( 'FOOTBALLPOOL_AJAX_SAVE_DELAY', 500 ); // delay in ms

// Date and time formats (http://php.net/manual/en/function.date.php)
if ( ! defined( 'FOOTBALLPOOL_TIME_FORMAT' ) ) define( 'FOOTBALLPOOL_TIME_FORMAT', 'H:i' );
if ( ! defined( 'FOOTBALLPOOL_DATE_FORMAT' ) ) define( 'FOOTBALLPOOL_DATE_FORMAT', 'Y-m-d' );
if ( ! defined( 'FOOTBALLPOOL_MATCH_DATE_FORMAT' ) ) define( 'FOOTBALLPOOL_MATCH_DATE_FORMAT', 'M d, Y' );
if ( ! defined( 'FOOTBALLPOOL_MATCH_DAY_FORMAT' ) ) define( 'FOOTBALLPOOL_MATCH_DAY_FORMAT', 'l' );

// Cache
const FOOTBALLPOOL_CACHE_MATCHES = 'fp_match_info';
const FOOTBALLPOOL_CACHE_ALL_MATCHES = 'fp_all_match_info';
const FOOTBALLPOOL_CACHE_QUESTIONS = 'fp_bonus_question_info';
const FOOTBALLPOOL_CACHE_TEAMS = 'fp_teams_info';
const FOOTBALLPOOL_CACHE_LEAGUES_ALL = 'fp_get_leagues_all';
const FOOTBALLPOOL_CACHE_LEAGUES_USERDEFINED = 'fp_get_leagues_user_defined';
const FOOTBALLPOOL_CACHE_RANKINGS_ALL = 'fp_get_rankings_all';
const FOOTBALLPOOL_CACHE_RANKINGS_USERDEFINED = 'fp_get_rankings_user_defined';
const FOOTBALLPOOL_WPCACHE_NON_PERSISTENT = 'footballpool-non-persistent';
const FOOTBALLPOOL_WPCACHE_PERSISTENT = 'footballpool-persistent';

// Nonces
const FOOTBALLPOOL_NONCE_CSV = 'football-pool-csv-download';
const FOOTBALLPOOL_NONCE_ADMIN = 'football-pool-admin';
const FOOTBALLPOOL_NONCE_SCORE_CALC = 'football-pool-score-calculation';
const FOOTBALLPOOL_NONCE_BLOG = 'football-pool-blog';
const FOOTBALLPOOL_NONCE_BLOG_INPUT_NAME = '_footballpool_wpnonce';
const FOOTBALLPOOL_NONCE_SHOUTBOX = 'football-pool-shoutbox';
const FOOTBALLPOOL_NONCE_SHOUTBOX_INPUT_NAME = '_footballpool_shoutbox_wpnonce';
const FOOTBALLPOOL_NONCE_PREDICTION_SAVE = 'football-pool-blog';

// Admin capabilities
const FOOTBALLPOOL_ADMIN_BASE_CAPABILITY = 'manage_football_pool';
const FOOTBALLPOOL_ADMIN_MATCHES_CAPABILITY = FOOTBALLPOOL_ADMIN_BASE_CAPABILITY . '_matches';
const FOOTBALLPOOL_ADMIN_QUESTIONS_CAPABILITY = FOOTBALLPOOL_ADMIN_BASE_CAPABILITY . '_questions';

// Others
const MATCH_TABLE_LAYOUT = 1; // 1 = new layout
if ( ! defined( 'FOOTBALLPOOL_TABINDEX' ) ) define( 'FOOTBALLPOOL_TABINDEX', 100 );
if ( ! defined( 'FOOTBALLPOOL_ALLOW_HTML' ) ) define( 'FOOTBALLPOOL_ALLOW_HTML', false );
if ( ! defined( 'FOOTBALLPOOL_CHANGE_STATS_TITLE' ) ) define( 'FOOTBALLPOOL_CHANGE_STATS_TITLE', true );
const FOOTBALLPOOL_DEFAULT_PAGINATION_PAGE_SIZE = 20;
const FOOTBALLPOOL_SHOUTBOX_MAXCHARS            = 150;
if ( ! defined( 'FOOTBALLPOOL_SHOUTBOX_DOUBLE_POST_INTERVAL' ) )
	define( 'FOOTBALLPOOL_SHOUTBOX_DOUBLE_POST_INTERVAL', HOUR_IN_SECONDS );	// time allowed between two (same) shoutbox messages from one user (in seconds)
const FOOTBALLPOOL_DONATE_LINK = 'https://www.paypal.com/donate/?hosted_button_id=83WKPJ6CRMUAA';
const FOOTBALLPOOL_MATCH_SORT  = 0; // date asc
const FOOTBALLPOOL_QUESTION_SORT = 0; // answer_before_date asc, id asc
if ( ! defined( 'FOOTBALLPOOL_NO_AVATAR' ) ) define( 'FOOTBALLPOOL_NO_AVATAR', true ); // set to false if you want to show avatars in the ranking
if ( ! defined( 'FOOTBALLPOOL_SMALL_AVATAR' ) ) define( 'FOOTBALLPOOL_SMALL_AVATAR', 18 ); // size in px
if ( ! defined( 'FOOTBALLPOOL_MEDIUM_AVATAR' ) ) define( 'FOOTBALLPOOL_MEDIUM_AVATAR', 28 ); // size in px
if ( ! defined( 'FOOTBALLPOOL_LARGE_AVATAR' ) ) define( 'FOOTBALLPOOL_LARGE_AVATAR', 36 ); // size in px
if ( ! defined( 'FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER' ) ) define( 'FOOTBALLPOOL_TEMPLATE_PARAM_DELIMITER', '%' );
if ( ! defined( 'FOOTBALLPOOL_CONTENT_FILTER_PRIORITY' ) ) define( 'FOOTBALLPOOL_CONTENT_FILTER_PRIORITY', 50 );
if ( ! defined( 'FOOTBALLPOOL_REDIRECT_FILTER_PRIORITY' ) ) define( 'FOOTBALLPOOL_REDIRECT_FILTER_PRIORITY', 50 );
if ( ! defined( 'FOOTBALLPOOL_TOP_PLAYERS' ) ) define( 'FOOTBALLPOOL_TOP_PLAYERS', 5 ); // used on the stats page
if ( ! defined( 'FOOTBALLPOOL_ADMIN_QUESTION_MAX_CHARS' ) ) define( 'FOOTBALLPOOL_ADMIN_QUESTION_MAX_CHARS', 120 ); // used in the admin question view to cut off very long questions that clutter the screen

// Debug
// define( 'FOOTBALLPOOL_DEBUG_FORCE', 'file' );
const FOOTBALLPOOL_DEBUG_EMAIL = 'wordpressfootballpool@gmail.com';
if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'local' ) {
	define( 'FOOTBALLPOOL_ENABLE_DEBUG', true );
	define( 'FOOTBALLPOOL_LOCAL_MODE', true );
	error_reporting( -1 );
	$wpdb->show_errors();
	// http://wordpress.org/support/topic/scheduled-posts-still-not-working-in-282#post-1175405
	// define( 'ALTERNATE_WP_CRON', true );
} else {
	define( 'FOOTBALLPOOL_ENABLE_DEBUG', false );
	define( 'FOOTBALLPOOL_LOCAL_MODE', false );
}
