<?php
/*Plugin Name: Weekly Schedule
Plugin URI: https://ylefebvre.github.io/wordpress-plugins/weekly-schedule/
Description: Simplify the management and display of online schedule grids
Version: 3.5
Author: Yannick Lefebvre
Author URI: https://ylefebvre.github.io
Text Domain: weekly-schedule
Copyright 2021  Yannick Lefebvre  (email : ylefebvre@gmail.com)

Contributions to version 2.7 by Daniel R. Baleato
Contributions to version 3.4 by Alexander Perlis

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA*/

$ws_pagehooktop          = "";
$ws_pagehookmoderate     = "";
$ws_pagehooksettingssets = "";
$ws_pagehookstylesheet   = "";
$ws_pagehookreciprocal   = "";

$wsstylesheet = "";

define( 'WEEKLY_SCHEDULE_ADMIN_PAGE_NAME', 'weekly-schedule' );

function ws_get_general_options() {
	$options = get_option( 'WeeklyScheduleGeneral', array() );

	$new_options['stylesheet']           = 'stylesheettemplate.css';
	$new_options['numberschedules']      = 2;
	$new_options['debugmode']            = false;
	$new_options['includestylescript']   = '';
	$new_options['frontpagestylescript'] = false;
	$new_options['version']              = '2.7';
	$new_options['accesslevel']          = 'admin';
	$new_options['csvdelimiter']         = ',';
	$new_options['displayflatmobile']    = false;

	$merged_options = wp_parse_args( $options, $new_options );
	$compare_options = array_diff_key( $new_options, $options );
	if ( empty( $options ) || !empty( $compare_options ) ) {
		update_option( 'WS_PP', $merged_options );
	}
	return $merged_options;
}

global $accesslevelcheck;
$accesslevelcheck = '';

$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

if ( !isset( $genoptions['accesslevel'] ) || empty( $genoptions['accesslevel'] ) ) {
	$genoptions['accesslevel'] = 'admin';
}

switch ( $genoptions['accesslevel'] ) {
	case 'admin':
		$accesslevelcheck = 'manage_options';
		break;

	case 'editor':
		$accesslevelcheck = 'manage_categories';
		break;

	case 'author':
		$accesslevelcheck = 'publish_posts';
		break;

	case 'contributor':
		$accesslevelcheck = 'edit_posts';
		break;

	case 'subscriber':
		$accesslevelcheck = 'read';
		break;

	default:
		$accesslevelcheck = 'manage_options';
		break;
}

function ws_get_options( $schedule_id ) {
	$options = get_option( 'WS_PP' . $schedule_id, array() );

	$new_options['starttime']             = 19;
	$new_options['endtime']               = 22;
	$new_options['timedivision']          = 0.5;
	$new_options['tooltipwidth']          = 300;
	$new_options['tooltiptarget']         = 'right center';
	$new_options['tooltippoint']          = 'left center';
	$new_options['tooltipcolorscheme']    = 'ui-tooltip';
	$new_options['displaydescription']    = "tooltip";
	$new_options['daylist']               = '';
	$new_options['timeformat']            = '24hours';
	$new_options['layout']                = 'horizontal';
	$new_options['adjusttooltipposition'] = true;
	$new_options['schedulename']          = 'Default';
	$new_options['linktarget']            = 'newwindow';
	$new_options['floatthead']			  = false;

	$merged_options = wp_parse_args( $options, $new_options );
	$compare_options = array_diff_key( $new_options, $options );
	if ( empty( $options ) || !empty( $compare_options ) ) {
		update_option( 'WS_PP' . $schedule_id, $merged_options );
	}
	return $merged_options;
}

function ws_db_prefix() {
	global $wpdb;
	if ( method_exists( $wpdb, "get_blog_prefix" ) ) {
		return $wpdb->get_blog_prefix();
	} else {
		return $wpdb->prefix;
	}
}

function ws_install() {
	global $wpdb;

	if ( function_exists( 'is_multisite' ) && is_multisite() ) {
		if ( isset( $_GET['networkwide'] ) && ( $_GET['networkwide'] == 1 ) ) {
			$originalblog = $wpdb->blogid;

			$bloglist = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );
			foreach ( $bloglist as $blog ) {
				switch_to_blog( $blog );
				ws_create_table_and_settings();
			}
			switch_to_blog( $originalblog );

			return;
		}
	}
	ws_create_table_and_settings();

	$genoptions = get_option( 'WeeklyScheduleGeneral' );

	if ( !empty( $genoptions ) ) {
		if ( isset( $genoptions['stylesheet'] ) && isset( $genoptions['fullstylesheet'] ) && !empty( $genoptions['stylesheet'] ) && empty( $genoptions['fullstylesheet'] ) ) {
			$stylesheetlocation           = plugins_url( $genoptions['stylesheet'], __FILE__ );
			$genoptions['fullstylesheet'] = file_get_contents( $stylesheetlocation );

			require_once plugin_dir_path( __FILE__ ) . '/tools/class.csstidy.php';
			$csstidy = new csstidy();

			// Set some options :
			$csstidy->set_cfg('optimise_shorthands', 2);
			$csstidy->set_cfg('template', 'low');
			$csstidy->set_cfg( 'discard_invalid_properties', true );

			// Parse the CSS
			$csstidy->parse( $genoptions['fullstylesheet'] );

			// Get back the optimized CSS Code
			$genoptions['fullstylesheet'] = $csstidy->print->plain();

			update_option( 'WeeklyScheduleGeneral', $genoptions );
		}
	}
}

function ws_new_network_site( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
	global $wpdb;

	if ( ! function_exists( 'is_plugin_active_for_network' ) )
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

	if ( is_plugin_active_for_network( 'weekly-schedule/weekly-schedule.php' ) ) {
		$originalblog = $wpdb->blogid;
		switch_to_blog( $blog_id );
		ws_create_table_and_settings();
		switch_to_blog( $originalblog );
	}
}

function ws_create_table_and_settings() {
	global $wpdb;

	$wpdb->wscategories = ws_db_prefix() . 'wscategories';

	$result = $wpdb->query(
		"
			CREATE TABLE IF NOT EXISTS `$wpdb->wscategories` (
				`id` int(10) unsigned NOT NULL auto_increment,
				`name` varchar(255) CHARACTER SET utf8 NOT NULL,
				`scheduleid` int(10) default NULL,
				`backgroundcolor` varchar(7) NULL,
				PRIMARY KEY  (`id`)
				) "
	);

	$catsresult = $wpdb->query(
		"
			SELECT * from `$wpdb->wscategories`"
	);

	if ( ! $catsresult ) {
		$result = $wpdb->query(
			"
			INSERT INTO `$wpdb->wscategories` (`name`, `scheduleid`, `backgroundcolor`) VALUES
			('Default', 1, NULL)"
		);
	}

	$wpdb->wsdays = ws_db_prefix() . 'wsdays';

	$result = $wpdb->query(
		"
			CREATE TABLE IF NOT EXISTS `$wpdb->wsdays` (
				`id` int(10) unsigned NOT NULL,
				`name` varchar(12) CHARACTER SET utf8 NOT NULL,
				`rows` int(10) unsigned NOT NULL,
				`scheduleid` int(10) NOT NULL default '0',
				PRIMARY KEY  (`id`, `scheduleid`)
				) "
	);

	$daysresult = $wpdb->query(
		"
			SELECT * from `$wpdb->wsdays`"
	);

	if ( ! $daysresult ) {
		$result = $wpdb->query(
			"
			INSERT INTO `$wpdb->wsdays` (`id`, `name`, `rows`, `scheduleid`) VALUES
			(1, 'Sun', 1, 1),
			(2, 'Mon', 1, 1),
			(3, 'Tue', 1, 1),
			(4, 'Wed', 1, 1),
			(5, 'Thu', 1, 1),
			(6, 'Fri', 1, 1),
			(7, 'Sat', 1, 1)"
		);
	}

	$wpdb->wsitems = ws_db_prefix() . 'wsitems';

	$item_table_creation_query = "
			CREATE TABLE " . $wpdb->wsitems . " (
				id int(10) unsigned NOT NULL auto_increment,
				name varchar(255) CHARACTER SET utf8,
				description text CHARACTER SET utf8 NOT NULL,
				address varchar(255) NOT NULL,
				starttime float unsigned NOT NULL,
				duration float NOT NULL,
				row int(10) unsigned NOT NULL,
				day int(10) unsigned NOT NULL,
				category int(10) unsigned NOT NULL,
				scheduleid int(10) NOT NULL default '0',
                backgroundcolor varchar(7) NULL,
                titlecolor varchar(7) NULL,
				UNIQUE KEY idandscheduleid ( id, scheduleid )
			);";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $item_table_creation_query );

	$options = ws_get_options( '1' );
	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );
}

register_activation_hook( __FILE__, 'ws_install' );

function ws_time_string( $t, $timeformat ) {
	// Incoming time $t is an unsigned decimal hour that can go beyond 24; for
	// example, 2.5 means 2:30am today, whereas 26.5 means 2:30am tomorrow.
	if ( $timeformat == "24hours" )
		$f = "G\\hi";
	else if ( $timeformat == "24hourslz" )
		$f = "H\\hi";
	else if ( $timeformat == "24hourscolon" )
		$f = "G:i";
	else if ( $timeformat == "24hourslzcolon" )
		$f = "H:i";
	else if ( $timeformat == "12hours" )
		$f = "g:ia";
	else if ( $timeformat == "12hoursspace" )
		$f = "g:i a";
	else if ( $timeformat == "12hoursno0" || $timeformat == "12hoursno0space" ) {
		$minutes = floor( 60 * ($t - floor( $t ) ) );
		if ( $timeformat == "12hoursno0" )
			$f = ( $minutes == 0 )? "ga" : "g:ia";
		else
			$f = ( $minutes == 0 )? "g a" : "g:i a";
		}
	return date( $f, 3600 * $t );
}

function ws_allowed_html_tags() {
	return array(
		'a' => array(
			'href' => array(),
			'title' => array(),
			'class' => array(),
			'data' => array(),
			'rel'   => array(),
			'id' => array(),
		  ),
		  'br' => array(),
		  'em' => array(),
		  'ul' => array(
			'class' => array(),
			'id' => array(),
		  ),
		  'ol' => array(
			'class' => array(),
		  ),
		  'li' => array(
			'class' => array(),
			'id' => array(),
		  ),
		  'strong' => array(),
		  'div' => array(
			'class' => array(),
			'data' => array(),
			'style' => array(),
			'id' => array(),
		  ),
		  'span' => array(
			'class' => array(),
			'style' => array(),
			'id' => array(),
		  ),
		  'img' => array(
			'alt'    => array(),
			  'class'  => array(),
			  'height' => array(),
			  'src'    => array(),
			  'width'  => array(),
			  'id' => array(),
		  ),
		  'select' => array(
				'id'   => array(),
				'class' => array(),
				'name' => array(),
		  ),
		  'option' => array(
				'value' => array(),
				'selected' => array(),
				'id' => array(),
		  ),
	);
}

if ( is_admin() && !class_exists( 'WS_Admin' ) ) {
	class WS_Admin {
		function __construct() {
			// adds the menu item to the admin interface
			add_action( 'admin_menu', array( $this, 'add_config_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_page_scripts_enqueue_script' ) );
		}

		function add_page_scripts_enqueue_script( $hook ) {
			global $post;

			if( 'weekly-schedule_page_weekly-schedule-stylesheet' === $hook ) {
				$cm_settings['codeEditor'] = wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
				wp_localize_script( 'jquery', 'cm_settings', $cm_settings );
				
				wp_enqueue_script( 'wp-theme-plugin-editor' );
				wp_enqueue_style( 'wp-codemirror' );
			}
		}

		function add_config_page() {
			global $wpdb, $ws_pagehooktop, $ws_pagehookgeneraloptions, $ws_pagehookstylesheet;
			global $accesslevelcheck;
			$ws_pagehooktop            = add_menu_page( __( 'Weekly Schedule', 'weekly-schedule' ) . ' - ' . __( 'General Options', 'weekly-schedule' ), __( 'Weekly Schedule', 'weekly-schedule' ), $accesslevelcheck, WEEKLY_SCHEDULE_ADMIN_PAGE_NAME, array( $this, 'config_page' ), plugins_url( 'icons/calendar-icon-16.png', __FILE__ ) );
			$ws_pagehookgeneraloptions = add_submenu_page( WEEKLY_SCHEDULE_ADMIN_PAGE_NAME, __( 'Weekly Schedule', 'weekly-schedule' ) . ' - ' . __( 'General Options', 'weekly-schedule' ), __( 'General Options', 'weekly-schedule' ), $accesslevelcheck, WEEKLY_SCHEDULE_ADMIN_PAGE_NAME, array( $this, 'config_page' ) );
			$ws_pagehookstylesheet     = add_submenu_page( WEEKLY_SCHEDULE_ADMIN_PAGE_NAME, __( 'Weekly Schedule', 'weekly-schedule' ) . ' - ' . __( 'Stylesheet', 'weekly-schedule' ), __( 'Stylesheet', 'weekly-schedule' ), $accesslevelcheck, 'weekly-schedule-stylesheet', array( $this, 'stylesheet_config_page' ) );

			//add_options_page('Weekly Schedule for Wordpress', 'Weekly Schedule', 9, basename(__FILE__), array('WS_Admin','config_page'));
			add_filter( 'plugin_action_links', array( $this, 'filter_plugin_actions' ), 10, 2 );
		} // end add_WS_config_page()

		function filter_plugin_actions( $links, $file ) {
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( !$this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="options-general.php?page=weekly-schedule">' . __( 'Settings', 'weekly-schedule' ) . '</a>';

				array_unshift( $links, $settings_link ); // before other links
			}

			return $links;
		}

		function stylesheet_config_page() {
			$genoptions = get_option( 'WeeklyScheduleGeneral' );

			if ( isset( $_POST['resetstyle'] ) || empty( $genoptions['fullstylesheet'] ) ) {
				$stylesheetlocation = plugins_url( 'stylesheettemplate.css', __FILE__ );

				$genoptions['fullstylesheet'] = @file_get_contents( $stylesheetlocation );

				require_once plugin_dir_path( __FILE__ ) . '/tools/class.csstidy.php';
				$csstidy = new csstidy();

				// Set some options :
				$csstidy->set_cfg('optimise_shorthands', 2);
				$csstidy->set_cfg('template', 'low');
				$csstidy->set_cfg( 'discard_invalid_properties', true );

				// Parse the CSS
				$csstidy->parse( $genoptions['fullstylesheet'] );

				// Get back the optimized CSS Code
				$genoptions['fullstylesheet'] = $csstidy->print->plain();

				update_option( 'WeeklyScheduleGeneral', $genoptions );
				echo '<div id="warning" class="updated fade"><p><strong>Reset stylesheet to default.</strong></div>';
			}

			if ( isset( $_POST['submitstyle'] ) ) {
				$stylesheet_data = $_POST['fullstylesheet'];

				require_once plugin_dir_path( __FILE__ ) . '/tools/class.csstidy.php';
				$csstidy = new csstidy();

				// Set some options :
				$csstidy->set_cfg('optimise_shorthands', 2);
				$csstidy->set_cfg('template', 'low');
				$csstidy->set_cfg( 'discard_invalid_properties', true );

				// Parse the CSS
				$csstidy->parse( $stylesheet_data );

				// Get back the optimized CSS Code
				$genoptions['fullstylesheet'] = $csstidy->print->plain();

				update_option( 'WeeklyScheduleGeneral', $genoptions );
			}

			?>
			<div class="wrap">
				<h2><?php _e( 'Weekly Schedule Stylesheet Editor', 'weekly-schedule' ); ?></h2>
				<a href="https://ylefebvre.github.io/wordpress-plugins/weekly-schedule/" target="weeklyschedule"><img src="<?php echo plugins_url( '/icons/btn_donate_LG.gif', __FILE__ ); ?>" /></a> |
				<a target='wsinstructions' href='https://wordpress.org/extend/plugins/weekly-schedule/installation/'><?php _e( 'Installation Instructions', 'weekly-schedule' ); ?></a> |
				<a href='https://wordpress.org/extend/plugins/weekly-schedule/faq/' target='llfaq'><?php _e( 'FAQ', 'weekly-schedule' ); ?></a> |
				<a href='https://ylefebvre.github.io/contact-me'><?php _e( 'Contact the Author', 'weekly-schedule' ); ?></a><br />

				<p><?php _e( 'If the stylesheet editor is empty after upgrading, reset to the default stylesheet using the button below or copy/paste your backup stylesheet into the editor', 'weekly-schedule' ); ?>.</p>

				<form name='wsadmingenform' action="<?php echo add_query_arg( 'page', 'weekly-schedule-stylesheet', admin_url( 'admin.php' ) ); ?>" method="post" id="ws-conf">
					<?php
					if ( function_exists( 'wp_nonce_field' ) ) {
						wp_nonce_field( 'wspp-config' );
					}
					?>
					<textarea name='fullstylesheet' id='fancy-textarea' style='font-family:Courier' rows="30" cols="100"><?php echo esc_html( stripslashes( $genoptions['fullstylesheet'] ) ); ?></textarea>

					<div>
						<input type="submit" name="submitstyle" value="<?php _e( 'Submit', 'weekly-schedule' ); ?>" /><span style='padding-left: 650px'><input type="submit" name="resetstyle" value="<?php _e( 'Reset to default', 'weekly-schedule' ); ?>" /></span>
					</div>
				</form>
			</div>

			<script type="text/javascript">
			jQuery(document).ready(function() {
				if ( jQuery( '.CodeMirror' ).length == 0 ) {
					wp.codeEditor.initialize( jQuery('#fancy-textarea'), cm_settings );
				}	
			});
			</script>
		<?php
		}

		function config_page() {
			global $dlextensions;
			global $wpdb;

			$adminpage = '';
			$mode = '';

			$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

			if ( isset( $_GET['schedule'] ) ) {
				$schedule = intval( $_GET['schedule'] );
			} elseif ( isset( $_POST['schedule'] ) ) {
				$schedule = intval( $_POST['schedule'] );
			} else {
				$schedule = 1;
			}

			if ( isset( $_GET['copy'] ) ) {
				$destination = intval( $_GET['copy'] );
				$source      = intval( $_GET['source'] );

				$sourcesettingsname = 'WS_PP' . $source;
				$sourceoptions      = ws_get_options( $sourcesettingsname );

				$destinationsettingsname = 'WS_PP' . $destination;
				update_option( $destinationsettingsname, $sourceoptions );

				$schedule = $destination;
			}

			if ( isset( $_GET['reset'] ) && $_GET['reset'] == "true" ) {

				$options['starttime']             = 19;
				$options['endtime']               = 22;
				$options['timedivision']          = 0.5;
				$options['tooltipwidth']          = 300;
				$options['tooltiptarget']         = 'right center';
				$options['tooltippoint']          = 'left center';
				$options['tooltipcolorscheme']    = 'ui-tooltip';
				$options['displaydescription']    = "tooltip";
				$options['daylist']               = "";
				$options['timeformat']            = "24hours";
				$options['layout']                = 'horizontal';
				$options['adjusttooltipposition'] = true;
				$options['schedulename']          = "Default";
				$options['linktarget']            = "newwindow";
				$options['floatthead']			  = false;

				$schedule     = intval( $_GET['reset'] );
				$schedulename = 'WS_PP' . $schedule;

				update_option( $schedulename, $options );
			}
			if ( isset( $_GET['settings'] ) ) {
				if ( $_GET['settings'] == 'categories' ) {
					$adminpage = 'categories';
				} elseif ( $_GET['settings'] == 'items' ) {
					$adminpage = 'items';
				} elseif ( $_GET['settings'] == 'general' ) {
					$adminpage = 'general';
				} elseif ( $_GET['settings'] == 'days' ) {
					$adminpage = 'days';
				}

			}
			if ( isset( $_POST['submit'] ) ) {
				global $accesslevelcheck;
				if ( !current_user_can( $accesslevelcheck ) ) {
					die( __( 'You cannot edit the Weekly Schedule for WordPress options.', 'weekly-schedule' ) );
				}
				check_admin_referer( 'wspp-config' );

				$options = ws_get_options( $schedule );

				if ( $_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "3.0" ) {
					$itemsquarterhour = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.25 and scheduleid = " . intval( $schedule ) );
					$itemshalfhour    = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.5 and scheduleid = " . intval( $schedule ) );
					$itemshour        = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 1.0 and scheduleid = " . intval( $schedule ) );
					$itemstwohour     = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 2.0 and scheduleid = " . intval( $schedule ) );

					if ( $itemsquarterhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to tri-hourly since some items have quarter-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ( $itemshalfhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to tri-hourly since some items have half-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.5";
					} elseif ( $itemshour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to tri-hourly since some items have hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "1.0";
					} elseif ( $itemstwohour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to tri-hourly since some items have hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "2.0";
					} else {
						$options['timedivision'] = floatval( $_POST['timedivision'] );
					}
				} elseif ( $_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "2.0" ) {
					$itemsquarterhour = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.25 and scheduleid = " . intval( $schedule ) );
					$itemshalfhour    = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.5 and scheduleid = " . intval( $schedule ) );
					$itemshour        = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 1.0 and scheduleid = " . intval( $schedule ) );

					if ( $itemsquarterhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to bi-hourly since some items have quarter-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ( $itemshalfhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to bi-hourly since some items have half-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.5";
					} elseif ( $itemshour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to bi-hourly since some items have hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "1.0";
					} else {
						$options['timedivision'] = floatval( $_POST['timedivision'] );
					}
				} elseif ( $_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "1.0" ) {
					$itemsquarterhour = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.25 and scheduleid = " . intval( $schedule ) );
					$itemshalfhour    = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.5 and scheduleid = " . intval( $schedule ) );

					if ( $itemsquarterhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to hourly since some items have quarter-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.25";
					} elseif ( $itemshalfhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to hourly since some items have half-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.5";
					} else {
						$options['timedivision'] = floatval( $_POST['timedivision'] );
					}
				} elseif ( $_POST['timedivision'] != $options['timedivision'] && $_POST['timedivision'] == "0.5" ) {
					$itemsquarterhour = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE MOD(duration, 1) = 0.25 and scheduleid = " . intval( $schedule ) );

					if ( $itemsquarterhour ) {
						echo '<div id="warning" class="updated fade"><p><strong>' . __( 'Cannot change time division to hourly since some items have quarter-hourly durations', 'weekly-schedule' ) . '</strong></div>';
						$options['timedivision'] = "0.25";
					} else {
						$options['timedivision'] = floatval( $_POST['timedivision'] );
					}
				} else {
					$options['timedivision'] = floatval( $_POST['timedivision'] );
				}

				foreach (
					array(
						'starttime', 'endtime', 'tooltiptarget', 'tooltippoint', 'tooltipcolorscheme',
						'displaydescription', 'daylist', 'timeformat', 'layout', 'schedulename', 'linktarget'
					) as $option_name
				) {
					if ( isset( $_POST[$option_name] ) ) {
						$options[$option_name] = sanitize_text_field( $_POST[$option_name] );
					}
				}

				foreach ( array( 'tooltipwidth' ) as $option_name ) {
					if ( isset( $_POST[$option_name] ) ) {
						$options[$option_name] = intval( $_POST[$option_name] );
					}
				}

				foreach ( array( 'adjusttooltipposition', 'floatthead' ) as $option_name ) {
					if ( isset( $_POST[$option_name] ) ) {
						$options[$option_name] = true;
					} else {
						$options[$option_name] = false;
					}
				}

				$schedulename = 'WS_PP' . $schedule;
				update_option( $schedulename, $options );

				echo '<div id="message" class="updated fade"><p><strong>' . __( 'Weekly Schedule', 'weekly-schedule' ) . ': ' . __( 'Schedule', 'weekly-schedule' ) . ' ' . intval( $schedule ) . ' ' . __( 'Updated', 'weekly-schedule' ) . '</strong></div>';
			}
			if ( isset( $_POST['submitgen'] ) ) {
				global $accesslevelcheck;
				if ( !current_user_can( $accesslevelcheck ) ) {
					die( __( 'You cannot edit the Weekly Schedule for WordPress options.', 'weekly-schedule' ) );
				}
				check_admin_referer( 'wspp-config' );

				foreach ( array( 'stylesheet', 'includestylescript', 'accesslevel', 'csvdelimiter' ) as $option_name ) {
					if ( isset( $_POST[$option_name] ) ) {
						$genoptions[$option_name] = sanitize_text_field( $_POST[$option_name] );
					}
				}

				foreach ( array( 'numberschedules' ) as $option_name ) {
					if ( isset( $_POST[$option_name] ) ) {
						$genoptions[$option_name] = intval( $_POST[$option_name] );
					}
				}

				foreach ( array( 'debugmode', 'frontpagestylescript', 'displayflatmobile' ) as $option_name ) {
					if ( isset( $_POST[$option_name] ) ) {
						$genoptions[$option_name] = true;
					} else {
						$genoptions[$option_name] = false;
					}
				}

				update_option( 'WeeklyScheduleGeneral', $genoptions );
			} elseif ( isset( $_POST['importschedule'] ) ) {
				wp_suspend_cache_addition( true );
				set_time_limit( 600 );

				global $wpdb;

				$handle = fopen( $_FILES['schedulefile']['tmp_name'], 'r' );

				$importmessage = '';
				$filerow = 0;
				$successfulimport = 0;

				if ( !isset( $genoptions['csvdelimiter'] ) ) {
					$genoptions['csvdelimiter'] = ',';
				}

				if ( $handle ) {
					while ( ( $data = fgetcsv( $handle, 5000, $genoptions['csvdelimiter'] ) ) !== false ) {
						$filerow += 1;

						if ( $filerow >= 2 ) {
							$start_time = $data[3];
							$colon_position = strpos( $start_time, ':' );

							if ( false !== $colon_position ) {
								$calc_start_time = substr( $start_time, 0, $colon_position );
								$calc_start_minute = substr( $start_time, $colon_position + 1, 2 );
								$calc_start_minute = ( round ( $calc_start_minute / 15 ) / 4 );
								if ( $calc_start_minute >= 1 )
									$calc_start_minute = 0;

								$start_time = $calc_start_time + $calc_start_minute;
							} else {
								$start_time = floatval( $start_time );
							}

						    if ( count( $data ) > 0 && count( $data ) == 10 ) {
								$newitem = array(
									'name'            => sanitize_text_field( stripslashes( $data[5] ) ),
									'description'     => wp_kses( stripslashes( $data[6] ), ws_allowed_html_tags() ),
									'address'         => esc_url( stripslashes( $data[7] ) ),
									'starttime'       => floatval( $start_time ),
									'duration'        => floatval( $data[4] ),
									'row'             => '',
									'day'             => intval( $data[2] ),
									'category'        => intval( $data[1] ),
									'scheduleid'      => intval( $data[0] ),
									'backgroundcolor' => sanitize_text_field( stripslashes( $data[8] ) ),
									'titlecolor'      => sanitize_text_field( stripslashes( $data[9] ) )
								);

							    $rowsearch = 1;
							    $row       = 1;

							    while ( $rowsearch == 1 ) {
								    $endtime = $newitem['starttime'] + $newitem['duration'];

								    $conflictquery = "SELECT * from " . ws_db_prefix() . "wsitems where day = " . intval( $newitem['day'] );
								    $conflictquery .= " and row = " . intval( $row );
								    $conflictquery .= " and scheduleid = " . intval( $newitem['scheduleid'] );
								    $conflictquery .= " and ((" . floatval( $newitem['starttime'] ) . " < starttime and " . floatval( $endtime ) . " > starttime) or";
								    $conflictquery .= "      (" . floatval( $newitem['starttime'] ) . " >= starttime and " . floatval( $newitem['starttime'] ) . " < starttime + duration)) ";

								    $conflictingitems = $wpdb->get_results( $conflictquery );

								    if ( $conflictingitems ) {
									    $row ++;
								    } else {
									    $rowsearch = 0;
								    }
							    }

							    $dayrow = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wsdays where id = " . intval( $newitem['day'] ) . " AND scheduleid = " . intval( $newitem['scheduleid'] ) );
							    if ( $dayrow->rows < $row ) {
								    $dayid     = array( 'id' => intval( $newitem['day'] ), 'scheduleid' => intval( $newitem['scheduleid'] ) );
								    $newdayrow = array( 'rows' => intval( $row ) );

								    $wpdb->update( ws_db_prefix() . 'wsdays', $newdayrow, $dayid );
							    }

							    $newitem['row'] = intval( $row );

							    $wpdb->insert( ws_db_prefix() . 'wsitems', $newitem );
								$successfulimport++;
							} elseif ( count( $data ) > 0 && count( $data ) != 10 ) {
								$importmessage = 1;
							}
						}
					}
				}

				if ( $successfulimport > 0 ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Successfully imported', 'weekly-schedule' ) . ' ' . intval( $successfulimport ) . ' ' . __( 'record(s) from', 'weekly-schedule' ) . ' ' . intval( $filerow - 1 ) . ' ' . __( 'line(s) in import file', 'weekly-schedule' ) . '</strong></div>';
				}

				if ( $importmessage == 1 ) {
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Some records did not have the right number of fields', 'weekly-schedule' ) . '</strong></div>';
				}

				wp_suspend_cache_addition( false );
			}

			if ( isset( $_GET['editcat'] ) ) {
				$adminpage = 'categories';

				$mode = 'edit';

				$selectedcat = $wpdb->get_row( "select * from " . ws_db_prefix() . "wscategories where id = " . intval( $_GET['editcat'] ) );
			}
			if ( isset( $_POST['newcat'] ) || isset( $_POST['updatecat'] ) ) {
				global $accesslevelcheck;
				if ( !current_user_can( $accesslevelcheck ) ) {
					die( __( 'You cannot edit the Weekly Schedule for WordPress options.', 'weekly-schedule' ) );
				}
				check_admin_referer( 'wspp-config' );

				if ( isset( $_POST['name'] ) ) {
					$newcat = array(
						"name"            => sanitize_text_field( $_POST['name'] ),
						"scheduleid"      => intval( $_POST['schedule'] ),
						'backgroundcolor' => sanitize_text_field( $_POST['backgroundcolor'] )
					);
				} else {
					$newcat = "";
				}

				if ( isset( $_POST['id'] ) ) {
					$id = array( "id" => intval( $_POST['id'] ) );
				}


				if ( isset( $_POST['newcat'] ) ) {
					$wpdb->insert( ws_db_prefix() . 'wscategories', $newcat );
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Inserted New Category', 'weekly-schedule' ) . '</strong></div>';
				} elseif ( isset( $_POST['updatecat'] ) ) {
					$wpdb->update( ws_db_prefix() . 'wscategories', $newcat, $id );
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Category Updated', 'weekly-schedule' ) . '</strong></div>';
				}

				$mode = '';

				$adminpage = 'categories';
			}
			if ( isset( $_GET['deletecat'] ) ) {
				$adminpage = 'categories';

				$catexist = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wscategories WHERE id = " . intval( $_GET['deletecat'] ) );

				if ( $catexist ) {
					$wpdb->query( "DELETE from " . ws_db_prefix() . "wscategories WHERE id = " . intval( $_GET['deletecat'] ) );
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Category Deleted', 'weekly-schedule' ) . '</strong></div>';
				}
			}
			if ( isset( $_GET['edititem'] ) ) {
				$adminpage = 'items';

				$mode = 'edit';

				$selecteditem = $wpdb->get_row( "select * from " . ws_db_prefix() . "wsitems where id = " . intval( $_GET['edititem'] ) . " AND scheduleid = " . intval( $_GET['schedule'] ) );
			} else {
				$selecteditem = new stdClass();
				$selecteditem->name = '';
				$selecteditem->description = '';
				$selecteditem->address = '';
				$selecteditem->starttime = '';
				$selecteditem->category = '';
				$selecteditem->duration = '';
				$selecteditem->day = '';
				$selecteditem->scheduleid = '';
				$selecteditem->backgroundcolor = '';
				$selecteditem->titlecolor = '';
			}
			if ( isset( $_POST['newitem'] ) || isset( $_POST['updateitem'] ) ) {
				// Need to re-work all of this to support multiple schedules
				global $accesslevelcheck;
				if ( !current_user_can( $accesslevelcheck ) ) {
					die( __( 'You cannot edit the Weekly Schedule for WordPress options.', 'weekly-schedule' ) );
				}
				check_admin_referer( 'wspp-config' );

				if ( isset( $_POST['name'] ) && isset( $_POST['starttime'] ) && isset( $_POST['duration'] ) ) {
					$newitem = array(
						'name'            => sanitize_text_field( stripslashes( $_POST['name'] ) ),
						'description'     => wp_kses( stripslashes( $_POST['description'] ), ws_allowed_html_tags() ),
						'address'         => esc_url( $_POST['address'] ),
						'starttime'       => floatval( $_POST['starttime'] ),
						'category'        => intval( $_POST['category'] ),
						'duration'        => floatval( $_POST['duration'] ),
						'day'             => intval( $_POST['day'] ),
						'scheduleid'      => intval( $_POST['schedule'] ),
						'backgroundcolor' => sanitize_text_field( $_POST['backgroundcolor'] ),
						'titlecolor'      => sanitize_text_field( $_POST['titlecolor'] )
					);

					if ( isset( $_POST['updateitem'] ) ) {
						$origrow = intval( $_POST['oldrow'] );
						$origday = intval( $_POST['oldday'] );
					}

					$rowsearch = 1;
					$row       = 1;

					while ( $rowsearch == 1 ) {
						if ( !empty( $_POST['id'] ) ) {
							$checkid = " and id <> " . intval( $_POST['id'] );
						} else {
							$checkid = "";
						}

						$endtime = $newitem['starttime'] + $newitem['duration'];

						$conflictquery = "SELECT * from " . ws_db_prefix() . "wsitems where day = " . intval( $newitem['day'] ) . $checkid;
						$conflictquery .= " and row = " . intval( $row );
						$conflictquery .= " and scheduleid = " . intval( $newitem['scheduleid'] );
						$conflictquery .= " and ((" . floatval( $newitem['starttime'] ) . " < starttime and " . floatval( $endtime ) . " > starttime) or";
						$conflictquery .= "      (" . floatval( $newitem['starttime'] ) . " >= starttime and " . floatval( $newitem['starttime'] ) . " < starttime + duration)) ";

						$conflictingitems = $wpdb->get_results( $conflictquery );

						if ( $conflictingitems ) {
							$row ++;
						} else {
							$rowsearch = 0;
						}
					}

					if ( isset( $_POST['updateitem'] ) ) {
						if ( $origrow != $row || $origday != $_POST['day'] ) {
							if ( $origrow > 1 ) {
								$itemday = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wsdays WHERE id = " . intval( $origday ) . " AND scheduleid = " . intval( $_POST['schedule'] ) );

								$othersonrow = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE day = " . intval( $origday ) . " AND row = " . intval( $origrow ) . " AND scheduleid = " . intval( $_POST['schedule'] ) . " AND id != " . intval( $_POST['id'] ) );
								if ( !$othersonrow ) {
									if ( $origrow != $itemday->rows ) {
										for ( $i = $origrow + 1; $i <= $itemday->rows; $i ++ ) {
											$newrow    = $i - 1;
											$changerow = array( 'row' => intval( $newrow ) );
											$oldrow    = array( 'row' => intval( $i ), 'day' => intval( $origday ) );
											$wpdb->update( ws_db_prefix() . 'wsitems', intval( $changerow ), intval( $oldrow ) );
										}
									}

									$dayid     = array( 'id' => intval( $itemday->id ), 'scheduleid' => intval( $_POST['schedule'] ) );
									$newrow    = intval( $itemday->rows ) - 1;
									$newdayrow = array( 'rows' => intval( $newrow ) );

									$wpdb->update( ws_db_prefix() . 'wsdays', $newdayrow, $dayid );
								}
							}
						}
					}

					$dayrow = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wsdays where id = " . intval( $_POST['day'] ) . " AND scheduleid = " . intval( $_POST['schedule'] ) );
					if ( $dayrow->rows < $row ) {
						$dayid     = array( 'id' => intval( $_POST['day'] ), 'scheduleid' => intval( $_POST['schedule'] ) );
						$newdayrow = array( 'rows' => intval( $row ) );

						$wpdb->update( ws_db_prefix() . 'wsdays', $newdayrow, $dayid );
					}

					$newitem['row'] = intval( $row );

					if ( isset( $_POST['id'] ) ) {
						$id = array( 'id' => intval( $_POST['id'] ), 'scheduleid' => intval( $_POST['schedule'] ) );
					}

					if ( isset( $_POST['newitem'] ) ) {
						$wpdb->insert( ws_db_prefix() . 'wsitems', $newitem );
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Inserted New Item', 'weekly-schedule' ) . '</strong></div>';
					} elseif ( isset( $_POST['updateitem'] ) ) {
						$wpdb->update( ws_db_prefix() . 'wsitems', $newitem, $id );
						echo '<div id="message" class="updated fade"><p><strong>' . __( 'Item Updated', 'weekly-schedule' ) . '</strong></div>';
					}
				}

				$mode = '';

				$adminpage = 'items';
			}
			if ( isset( $_GET['deleteitem'] ) ) {
				$adminpage = 'items';

				$itemexist = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wsitems WHERE id = " . intval( $_GET['deleteitem'] ) . " AND scheduleid = " . intval( $_GET['schedule'] ) );
				$itemday   = $wpdb->get_row( "SELECT * from " . ws_db_prefix() . "wsdays WHERE id = " . intval( $itemexist->day ) . " AND scheduleid = " . intval( $_GET['schedule'] ) );

				if ( $itemexist ) {
					$wpdb->query( "DELETE from " . ws_db_prefix() . "wsitems WHERE id = " . intval( $_GET['deleteitem'] ) . " AND scheduleid = " . intval( $_GET['schedule'] ) );

					if ( $itemday->rows > 1 ) {
						$othersonrow = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsitems WHERE day = " . intval( $itemexist->day ) . " AND scheduleid = " . intval( $_GET['schedule'] ) . " AND row = " . intval( $itemexist->row ) );
						if ( !$othersonrow ) {
							if ( $itemexist->row != $itemday->rows ) {
								for ( $i = $itemexist->row + 1; $i <= $itemday->rows; $i ++ ) {
									$newrow    = $i - 1;
									$changerow = array( 'row' => intval( $newrow ) );
									$oldrow    = array( 'row' => intval( $i ), 'day' => intval( $itemday->id ) );
									$wpdb->update( ws_db_prefix() . 'wsitems', $changerow, $oldrow );
								}
							}

							$dayid     = array( 'id' => intval( $itemexist->day ), 'scheduleid' => intval( $_GET['schedule'] ) );
							$newrow    = intval( $itemday->rows - 1 );
							$newdayrow = array( 'rows' => $newrow );

							$wpdb->update( ws_db_prefix() . 'wsdays', $newdayrow, $dayid );
						}
					}
					echo '<div id="message" class="updated fade"><p><strong>' . __( 'Item Deleted', 'weekly-schedule' ) . '</strong></div>';
				}
			}
			if ( isset( $_POST['updatedays'] ) ) {
				$dayids = array( 1, 2, 3, 4, 5, 6, 7 );

				foreach ( $dayids as $dayid ) {
					$daynamearray = array( "name" => sanitize_text_field( $_POST[$dayid] ) );
					$dayidarray   = array( "id" => intval( $dayid ), "scheduleid" => intval( $_POST['schedule'] ) );

					$wpdb->update( ws_db_prefix() . 'wsdays', $daynamearray, $dayidarray );
				}
			}
			if ( isset( $_POST['deleteallitems'] ) && isset( $_GET['schedule'] ) ) {
				$deletion_query = 'delete from ' . ws_db_prefix() . 'wsitems where scheduleid = ' . intval( $_GET['schedule'] );
				$wpdb->get_results( $deletion_query );

				$days_row_query = 'update ' . ws_db_prefix() . 'wsdays set rows = 1 where scheduleid = ' . intval( $_GET['schedule'] );
				$wpdb->get_results( $days_row_query );
			}

			$wspluginpath = WP_CONTENT_URL . '/plugins/' . plugin_basename( dirname( __FILE__ ) ) . '/';

			if ( empty( $schedule ) ) {
				$options = ws_get_options( '1' );
				$schedule = 1;
			} else {
				$options      = ws_get_options( $schedule );
			}

			if ( empty( $options ) ) {
				$options['starttime']             = 19;
				$options['endtime']               = 22;
				$options['timedivision']          = 0.5;
				$options['tooltipwidth']          = 300;
				$options['tooltiptarget']         = 'right center';
				$options['tooltippoint']          = 'left center';
				$options['tooltipcolorscheme']    = 'ui-tooltip';
				$options['displaydescription']    = "tooltip";
				$options['daylist']               = "";
				$options['timeformat']            = "24hours";
				$options['layout']                = 'horizontal';
				$options['adjusttooltipposition'] = true;
				$options['schedulename']          = "Default";
				$options['linktarget']            = "newwindow";

				$schedulename = 'WS_PP' . $schedule;

				update_option( $schedulename, $options );
			}

			$catsresult = $wpdb->query( "SELECT * from " . ws_db_prefix() . "wscategories where scheduleid = " . intval( $schedule ) );

			if ( !$catsresult ) {
				$sqlstatement = "INSERT INTO " . ws_db_prefix() . "wscategories (`name`, `scheduleid`) VALUES
								('Default', " . intval( $schedule ) . ")";
				$result       = $wpdb->query( $sqlstatement );
			}

			$wpdb->wsdays = ws_db_prefix() . 'wsdays';

			$daysresult = $wpdb->query( "SELECT * from " . ws_db_prefix() . "wsdays where scheduleid = " . intval( $schedule ) );

			if ( !$daysresult ) {
				$sqlstatement = "INSERT INTO " . ws_db_prefix() . "wsdays (`id`, `name`, `rows`, `scheduleid`) VALUES
								(1, 'Sun', 1, " . intval( $schedule ) . "),
								(2, 'Mon', 1, " . intval( $schedule ) . "),
								(3, 'Tue', 1, " . intval( $schedule ) . "),
								(4, 'Wes', 1, " . intval( $schedule ) . "),
								(5, 'Thu', 1, " . intval( $schedule ) . "),
								(6, 'Fri', 1, " . intval( $schedule ) . "),
								(7, 'Sat', 1, " . intval( $schedule ) . ")";
				$result       = $wpdb->query( $sqlstatement );
			}

			?>
			<div class="wrap">
			<h2><?php _e( 'Weekly Schedule Configuration', 'weekly-schedule' ); ?></h2>
			<a href="https://ylefebvre.github.io/wordpress-plugins/weekly-schedule/" target="weeklyschedule"><img src="<?php echo plugins_url( '/icons/btn_donate_LG.gif', __FILE__ ); ?>" /></a> |
			<a target='wsinstructions' href='https://wordpress.org/extend/plugins/weekly-schedule/installation/'><?php _e( 'Installation Instructions', 'weekly-schedule' ); ?></a> |
			<a href='https://wordpress.org/extend/plugins/weekly-schedule/faq/' target='llfaq'><?php _e( 'FAQ', 'weekly-schedule' ); ?></a> |
			<a href='https://ylefebvre.github.io/contact-me'><?php _e( 'Contact the Author', 'weekly-schedule' ); ?></a><br /><br />

			<form name='wsadmingenform' action="<?php echo add_query_arg( 'page', 'weekly-schedule', admin_url( 'options-general.php' ) ); ?>" method="post" id="ws-conf" enctype="multipart/form-data">
				<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
				<?php
				if ( function_exists( 'wp_nonce_field' ) ) {
					wp_nonce_field( 'wspp-config' );
				}
				?>
				<fieldset style='border:1px solid #CCC;padding:10px'>
					<legend class="tooltip" title='<?php _e( 'These apply to all schedules', 'weekly-schedule' ); ?>' style='padding: 0 5px 0 5px;'>
						<strong><?php _e( 'General Settings', 'weekly-schedule' ); ?>
							<span style="border:0;padding-left: 15px;" class="submit"><input type="submit" name="submitgen" value="<?php _e( 'Update General Settings', 'weekly-schedule' ); ?> &raquo;" /></span></strong>
					</legend>
					<table>
						<tr>
							<td style='padding: 8px; vertical-align: top'>
								<table>
									<tr>
										<td><?php _e( 'Import Schedule Items', 'weekly-schedule' ); ?> (<a href="<?php echo plugins_url( 'importtemplate.csv', __FILE__ ); ?>"><?php _e( 'Template', 'weekly-schedule' ); ?></a>)</td>
										<td><input size="80" name="schedulefile" type="file" /></td>
										<td><input type="submit" name="importschedule" value="<?php _e( 'Import Items', 'weekly-schedule' ); ?>" /></td>
									</tr>
									<tr>
										<td><?php _e( 'Import File Delimiter', 'weekly-schedule' ); ?></td>
										<td>
											<input type="text" id="csvdelimiter" name="csvdelimiter" size="1" value="<?php if ( !isset( $genoptions['csvdelimiter'] ) ) $genoptions['csvdelimiter'] = ','; echo esc_html( $genoptions['csvdelimiter'] ); ?>" /></td>
									</tr>
									<tr>
										<td style='width:200px'><?php _e( 'Stylesheet File Name', 'weekly-schedule' ); ?></td>
										<td>
											<input type="text" id="stylesheet" name="stylesheet" size="40" value="<?php echo esc_html( $genoptions['stylesheet'] ); ?>" />
										</td>
									</tr>
									<?php if ( current_user_can( 'manage_options' ) ) { ?>
									<tr>
										<td style='width:200px'><?php _e( 'Access level required', 'weekly-schedule' ); ?></td>
										<td>
											<?php } ?>
											<select <?php if ( !current_user_can( 'manage_options' ) ) {
												echo 'style="display: none"';
											} ?> id="accesslevel" name="accesslevel">
												<?php $levels = array( 'admin' => __( 'Administrator', 'weekly-schedule' ), 'editor' => __( 'Editor', 'weekly-schedule' ), 'author' => __( 'Author', 'weekly-schedule' ), 'contributor' => __( 'Contributor', 'weekly-schedule' ), 'subscriber' => __( 'Subscriber', 'weekly-schedule' ) );
												if ( !isset( $genoptions['accesslevel'] ) || empty( $genoptions['accesslevel'] ) ) {
													$genoptions['accesslevel'] = 'admin';
												}

												foreach ( $levels as $key => $level ) {
													echo '<option value="' . esc_html( $key ) . '" ' . selected( $genoptions['accesslevel'], $key, false ) . '>' . esc_html( $level ) . '</option>';
												} ?>
											</select>
											<?php if ( current_user_can( 'manage_options' ) ) { ?>
										</td>
									</tr>
								<?php } ?>
									<tr>
										<td><?php _e( 'Number of Schedules', 'weekly-schedule' ); ?></td>
										<td>
											<input type="text" id="numberschedules" name="numberschedules" size="5" value="<?php if ( empty( $genoptions['numberschedules'] ) ) {
												echo '2';
											}
											echo intval( $genoptions['numberschedules'] ); ?>" /></td>
									</tr>
									<tr>
										<td><?php _e( 'Display flat schedule for mobile displays', 'weekly-schedule' ); ?></td>
										<td>
											<input type="checkbox" id="displayflatmobile" name="displayflatmobile" <?php checked( $genoptions['displayflatmobile'] ); ?>/></td>
									</tr>
									<tr>
										<td style="padding-left: 10px;padding-right:10px"><?php _e( 'Debug Mode', 'weekly-schedule' ); ?></td>
										<td>
											<input type="checkbox" id="debugmode" name="debugmode" <?php checked( $genoptions['debugmode'] ); ?>/></td>
									</tr>
									<tr>
										<td colspan="2"><?php _e( 'Additional pages to style (Comma-Separated List of Page IDs)', 'weekly-schedule' ); ?></td>
									</tr>
									<tr>
										<td colspan="2">
											<input type='text' name='includestylescript' style='width: 200px' value='<?php echo esc_html( $genoptions['includestylescript'] ); ?>' />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</fieldset>
			</form>

			<div style='padding-top: 15px;clear:both'>
				<fieldset style='border:1px solid #CCC;padding:10px'>
					<legend style='padding: 0 5px 0 5px;'><strong><?php _e( 'Schedule Selection and Usage Instructions', 'weekly-schedule' ); ?></strong>
					</legend>
					<FORM name="scheduleselection">
						<?php _e( 'Select Current Schedule', 'weekly-schedule' ); ?>:
						<SELECT name="schedulelist" style='width: 300px'>
							<?php if ( empty( $genoptions['numberschedules'] ) ) {
								$numberofschedules = 2;
							} else {
								$numberofschedules = intval( $genoptions['numberschedules'] );
							}
							for ( $counter = 1; $counter <= $numberofschedules; $counter ++ ): ?>
								<?php $tempoptions          = ws_get_options( $counter ); ?>
								<option value="<?php echo intval( $counter ) ?>" <?php if ( $schedule == $counter ) {
									echo 'SELECTED';
								} ?>><?php _e( 'Schedule', 'weekly-schedule' ); ?> <?php echo intval( $counter ) ?><?php if ( !empty( $tempoptions ) ) {
										echo " (" . esc_html( $tempoptions['schedulename'] ) . ")";
									} ?></option>
							<?php endfor; ?>
						</SELECT>
						<INPUT type="button" name="go" value="<?php _e( 'Go!', 'weekly-schedule' ); ?>" onClick="window.location= '?page=weekly-schedule&amp;settings=<?php echo esc_html( $adminpage ); ?>&amp;schedule=' + document.scheduleselection.schedulelist.options[document.scheduleselection.schedulelist.selectedIndex].value">
						<?php _e( 'Copy from', 'weekly-schedule' ); ?>:
						<SELECT name="copysource" style='width: 300px'>
							<?php if ( empty( $genoptions['numberschedules'] ) ) {
								$numberofschedules = 2;
							} else {
								$numberofschedules = $genoptions['numberschedules'];
							}
							for ( $counter = 1; $counter <= $numberofschedules; $counter ++ ): ?>
								<?php $tempoptions          = ws_get_options( $counter );
								if ( $counter != $schedule ):?>
									<option value="<?php echo intval( $counter ) ?>" <?php if ( $schedule == $counter ) {
										echo 'SELECTED';
									} ?>><?php _e( 'Schedule', 'weekly-schedule' ); ?> <?php echo intval( $counter ) ?><?php if ( !empty( $tempoptions ) ) {
											echo " (" . esc_html( $tempoptions['schedulename'] ) . ")";
										} ?></option>
								<?php endif;
							endfor; ?>
						</SELECT>
						<INPUT type="button" name="copy" value="<?php _e( 'Copy', 'weekly-schedule' ); ?>!" onClick="window.location= '?page=weekly-schedule&amp;copy=<?php echo intval( $schedule ); ?>&source=' + document.scheduleselection.copysource.options[document.scheduleselection.copysource.selectedIndex].value">
						<br />
						<br />
						<table class='widefat' style='clear:none;width:100%;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
							<thead>
							<tr>
								<th style='width:80px' class="tooltip">
									<?php _e( 'Schedule #', 'weekly-schedule' ); ?>
								</th>
								<th style='width:130px' class="tooltip">
									<?php _e( 'Schedule Name', 'weekly-schedule' ); ?>
								</th>
								<th class="tooltip">
									<?php _e( 'Code to insert on a Wordpress page to see Weekly Schedule', 'weekly-schedule' ); ?>
								</th>
							</tr>
							</thead>
							<tr>
								<td style='background: #FFF'><?php echo intval( $schedule ); ?></td>
								<td style='background: #FFF'><?php echo esc_html( $options['schedulename'] ); ?></a></td>
								<td style='background: #FFF'><?php echo "[weekly-schedule schedule=" . intval( $schedule ) . "]"; ?></td>
								<td style='background: #FFF;text-align:center'></td>
							</tr>
						</table>
						<br />
					</FORM>
				</fieldset>
			</div>
			<br />

			<fieldset style='border:1px solid #CCC;padding:10px'>
			<legend style='padding: 0 5px 0 5px;'>
				<strong><?php _e( 'Settings for Schedule', 'weekly-schedule' ); ?> <?php echo intval( $schedule ); ?> - <?php echo esc_html( $options['schedulename'] ); ?></strong>
			</legend>
			<?php if (( empty( $adminpage ) ) || ( $adminpage == "general" )): ?>
			<a href="?page=weekly-schedule&amp;settings=general&amp;schedule=<?php echo intval( $schedule ); ?>"><strong><?php _e( 'General Settings', 'weekly-schedule' ); ?></strong></a> |
			<a href="?page=weekly-schedule&amp;settings=categories&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Categories', 'weekly-schedule' ); ?></a> |
			<a href="?page=weekly-schedule&amp;settings=items&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Items', 'weekly-schedule' ); ?></a> |
			<a href="?page=weekly-schedule&amp;settings=days&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Days Labels', 'weekly-schedule' ); ?></a><br /><br />

			<form name="wsadminform" action="<?php echo add_query_arg( 'page', 'weekly-schedule', admin_url( 'options-general.php' ) ); ?>" method="post" id="ws-config">
			<?php
			if ( function_exists( 'wp_nonce_field' ) ) {
				wp_nonce_field( 'wspp-config' );
			}
			?>
			<?php _e( 'Schedule Name', 'weekly-schedule' ); ?>:
			<input type="text" id="schedulename" name="schedulename" size="80" value="<?php echo esc_html( $options['schedulename'] ); ?>" /><br /><br />
			<strong><?php _e( 'Time-related Settings', 'weekly-schedule' ); ?></strong><br />
			<input type="hidden" name="schedule" value="<?php echo intval( $schedule ); ?>" />
			<table>
				<tr>
					<td><?php _e( 'Schedule Layout', 'weekly-schedule' ); ?></td>
					<td><select style="width: 200px" name='layout'>
							<?php $layouts = array( "horizontal" => __( "Horizontal", 'weekly-schedule' ), "vertical" => __( "Vertical", 'weekly-schedule' ) );
							foreach ( $layouts as $key => $layout ) {
								echo "<option value='" . esc_html( $key ) . "' " . selected( $key, $options['layout'], false ) . ">" . esc_html( $layout ) . "\n";
							}
							?>
						</select></td>
					<td><?php _e( 'Time Display Format', 'weekly-schedule' ); ?></td>
					<td><select style="width: 200px" name='timeformat'>
							<?php $descriptions = array( "24hours" => __( "24 Hours (e.g., 6h00, 17h30)", 'weekly-schedule' ), "24hourslz" => __( "24 Hours with leading zero (e.g., 06h00, 17h30)", 'weekly-schedule' ),
								"24hourscolon" => __( "24 Hours with colon (e.g., 6:00, 17:30)", 'weekly-schedule' ), "24hourslzcolon" => __( "24 Hours with leading zero and colon (e.g., 06:00, 17:30)", 'weekly-schedule' ),
								"12hours" => __( "12 Hours (e.g., 6:00am, 5:30pm)", 'weekly-schedule' ), "12hoursspace" => __( "12 Hours with space (e.g., 6:00 am, 5:30 pm)", 'weekly-schedule' ),
								"12hoursno0" => __( "12 Hours omitting 0 minutes (e.g., 6am, 5:30pm)", 'weekly-schedule' ) , "12hoursno0space" => __( "12 Hours omitting 0 minutes with space (e.g., 6 am, 5:30 pm)", 'weekly-schedule' ) );
							foreach ( $descriptions as $key => $description ) {
								echo "<option value='" . esc_html( $key ) . "' " . selected( $key, $options['timeformat'], false ) . ">" . esc_html( $description ) . "\n";
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td><?php _e( 'Start Time', 'weekly-schedule' ); ?></td>
					<td><select style='width: 200px' name="starttime">
							<?php $timedivider = ( in_array( $options['timedivision'], array( '1.0', '2.0', '3.0' ) ) ? '1.0' : $options['timedivision'] );
							$maxtime           = 30 + $timedivider;
							for ( $i = 0; $i < $maxtime; $i += $timedivider ) {
								echo "<option value='" . esc_html( $i ) . "'" . selected( $i, $options['starttime'], false) . ">" . ws_time_string($i, $options['timeformat']) . "\n";
							}
							?>
						</select></td>
					<td><?php _e( 'End Time', 'weekly-schedule' ); ?></td>
					<td><select style='width: 200px' name="endtime">
							<?php for ( $i = 0; $i < $maxtime; $i += $timedivider ) {
								echo "<option value='" . esc_html( $i ) . "'" . selected( $i, $options['endtime'], false ) . ">" . ws_time_string($i, $options['timeformat']) . "\n";
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td><?php _e( 'Cell Time Division', 'weekly-schedule' ); ?></td>
					<td><select style='width: 250px' name='timedivision'>
							<?php $timedivisions = array(
								"0.25" => __( "Quarter-Hourly (15 min intervals)", 'weekly-schedule' ),
								"0.5"  => __( "Half-Hourly (30 min intervals)", 'weekly-schedule' ),
								"1"  => __( "Hourly (60 min intervals)", 'weekly-schedule' ),
								"2"  => __( "Bi-Hourly (120 min intervals)", 'weekly-schedule' ),
								"3"  => __( "Tri-Hourly (180 min intervals)", 'weekly-schedule' )
							);
							foreach ( $timedivisions as $key => $timedivision ) {
								echo "<option value='" . esc_html( $key ) . "' " . selected( $key, $options['timedivision'], false ) . ">" . esc_html( $timedivision ) . "\n";
							}
							?>
						</select></td>
					<td><?php _e( 'Show Description', 'weekly-schedule' ); ?></td>
					<td><select style="width: 200px" name='displaydescription'>
							<?php $descriptions = array( "tooltip" => __( "Show as tooltip", 'weekly-schedule' ), "cell" => __( "Show in cell after item name", 'weekly-schedule' ), "none" => __( "Do not display", 'weekly-schedule' ) );
							foreach ( $descriptions as $key => $description ) {
								echo "<option value='" . esc_html( $key ) . "' " . selected( $key, $options['displaydescription'], false ) . ">" . esc_html( $description ) . "\n";
							}
							?>
						</select></td>
				</tr>
				<tr>
					<td><?php _e( 'Make heading row sticky', 'weekly-schedule' ); ?></td>
					<td><input type="checkbox" id="floatthead" name="floatthead" <?php checked( $options['floatthead'] ); ?>/></td>
				</tr>
				<tr>
					<td colspan='2'><?php _e( 'Day List (comma-separated Day IDs to specify days to be displayed and their order)', 'weekly-schedule' ); ?>
					</td>
					<td colspan='2'>
						<input type='text' name='daylist' style='width: 200px' value='<?php echo esc_html( $options['daylist'] ); ?>' />
					</td>
				</tr>
				<tr>
					<td><?php _e( 'Target Window Name', 'weekly-schedule' ); ?>
					</td>
					<td>
						<input type='text' name='linktarget' style='width: 250px' value='<?php echo esc_html( $options['linktarget'] ); ?>' />
					</td>
				</tr>
			</table>
			<br /><br />
			<strong><?php _e( 'Tooltip Configuration', 'weekly-schedule' ); ?></strong>
			<table>
				<tr>
					<td><?php _e( 'Tooltip Color Scheme', 'weekly-schedule' ); ?></td>
					<td><select name='tooltipcolorscheme' style='width: 100px'>
							<?php $colors = array( 'qtip-cream' => __( 'cream', 'weekly-schedule' ), 'qtip-dark' => __( 'dark', 'weekly-schedule' ), 'qtip-green' => __( 'green', 'weekly-schedule' ), 'qtip-light' => __( 'light', 'weekly-schedule' ), 'qtip-red' => __( 'red', 'weekly-schedule' ), 'qtip-blue' => __( 'blue', 'weekly-schedule' ) );
							foreach ( $colors as $key => $color ) {
								echo "<option value='" . esc_html( $key ) . "' " . selected( $key, $options['tooltipcolorscheme'], false ) . ">" . esc_html( $color ) . "\n";
							}
							?>
						</select></td>
					<td><?php _e( 'Tooltip Width', 'weekly-schedule' ); ?></td>
					<td>
						<input type='text' name='tooltipwidth' style='width: 100px' value='<?php echo intval( $options['tooltipwidth'] ); ?>' />
					</td>
				</tr>
				<tr>
					<td><?php _e( 'Tooltip Anchor Point on Data Cell', 'weekly-schedule' ); ?></td>
					<td><select name='tooltiptarget' style='width: 200px'>
							<?php $positions = array(
								'top left'     => __( 'Top-Left Corner', 'weekly-schedule' ), 'top center' => __( 'Middle of Top Side', 'weekly-schedule' ),
								'top right'    => __( 'Top-Right Corner', 'weekly-schedule' ), 'right top' => __( 'Right Side of Top-Right Corner', 'weekly-schedule' ),
								'right center' => __( 'Middle of Right Side', 'weekly-schedule' ), 'right bottom' => __( 'Right Side of Bottom-Right Corner', 'weekly-schedule' ),
								'bottom left'  => __( 'Under Bottom-Left Side', 'weekly-schedule' ), 'bottom center' => __( 'Under Middle of Bottom Side', 'weekly-schedule' ),
								'bottom right' => __( 'Under Bottom-Right Side', 'weekly-schedule' ), 'left top' => __( 'Left Side of Top-Left Corner', 'weekly-schedule' ),
								'left center'  => __( 'Middle of Left Side', 'weekly-schedule' ), 'left bottom' => __( 'Left Side of Bottom-Left Corner', 'weekly-schedule' )
							);

							foreach ( $positions as $index => $position ) {
								echo "<option value='" . esc_html( $index ) . "' " . selected( $index, $options['tooltiptarget'], false ) . ">" . esc_html( $position ) . "\n";
							}

							?>
						</select></td>
					<td><?php _e( 'Tooltip Attachment Point', 'weekly-schedule' ); ?></td>
					<td><select name='tooltippoint' style='width: 200px'>
							<?php $positions = array(
								'top left'     => __( 'Top-Left Corner', 'weekly-schedule' ), 'top center' => __( 'Middle of Top Side', 'weekly-schedule' ),
								'top right'    => __( 'Top-Right Corner', 'weekly-schedule' ), 'right top' => __( 'Right Side of Top-Right Corner', 'weekly-schedule' ),
								'right center' => __( 'Middle of Right Side', 'weekly-schedule' ), 'right bottom' => __( 'Right Side of Bottom-Right Corner', 'weekly-schedule' ),
								'bottom left'  => __( 'Under Bottom-Left Side', 'weekly-schedule' ), 'bottom center' => __( 'Under Middle of Bottom Side', 'weekly-schedule' ),
								'bottom right' => __( 'Under Bottom-Right Side', 'weekly-schedule' ), 'left top' => __( 'Left Side of Top-Left Corner', 'weekly-schedule' ),
								'left center'  => __( 'Middle of Left Side', 'weekly-schedule' ), 'left bottom' => __( 'Left Side of Bottom-Left Corner', 'weekly-schedule' )
							);

							foreach ( $positions as $index => $position ) {
								echo "<option value='" . esc_html( $index ) . "' " . selected( $index, $options['tooltippoint'], false ) . ">" . esc_html( $position ) . "\n";
							}

							?>
						</select></td>
				</tr>
				<tr>
					<td><?php _e( 'Auto-Adjust Position to be visible', 'weekly-schedule' ); ?></td>
					<td>
						<input type="checkbox" id="adjusttooltipposition" name="adjusttooltipposition" <?php checked( $options['adjusttooltipposition'] ); ?>/></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<p style="border:0;" class="submit"><input type="submit" name="submit" value="<?php _e( 'Update Settings', 'weekly-schedule' ); ?> &raquo;" />
			</p>
			</form>
			</fieldset>
			<?php /* --------------------------------------- Categories --------------------------------- */ ?>
			<?php elseif ( $adminpage == "categories" ): ?>
				<a href="?page=weekly-schedule&amp;settings=general&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'General Settings', 'weekly-schedule' ); ?></a> |
				<a href="?page=weekly-schedule&amp;settings=categories&amp;schedule=<?php echo intval( $schedule ); ?>"><strong><?php _e( 'Manage Schedule Categories', 'weekly-schedule' ); ?></strong></a> |
				<a href="?page=weekly-schedule&amp;settings=items&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Items', 'weekly-schedule' ); ?></a> |
				<a href="?page=weekly-schedule&amp;settings=days&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Days Labels', 'weekly-schedule' ); ?></a>
				<br /><br />
				<div style='float:left;margin-right: 15px'>
					<form name="wscatform" action="" method="post" id="ws-config">
						<?php
						if ( function_exists( 'wp_nonce_field' ) ) {
							wp_nonce_field( 'wspp-config' );
						}
						?>
						<?php if ( $mode == "edit" ): ?>
							<strong><?php _e( 'Editing Category', 'weekly-schedule' ); ?> #<?php echo intval( $selectedcat->id ); ?></strong><br />
						<?php endif; ?>
						<?php _e( 'Category Name', 'weekly-schedule' ); ?>: <input style="width:300px" type="text" name="name" <?php if ( $mode == "edit" ) {
							echo "value='" . esc_html( $selectedcat->name ) . "'";
						} ?>/>
						<br><?php _e( 'Background Cell Color (optional)', 'weekly-schedule' ); ?>
						<input style="width:100px" type="text" name="backgroundcolor" <?php if ( $mode == "edit" ) {
							echo "value='" . esc_html( $selectedcat->backgroundcolor ) . "'";
						} ?>/>
						<input type="hidden" name="id" value="<?php if ( $mode == "edit" ) {
							echo intval( $selectedcat->id );
						} ?>" />
						<input type="hidden" name="schedule" value="<?php echo $schedule; ?>" />
						<?php if ( $mode == "edit" ): ?>
							<p style="border:0;" class="submit">
								<input type="submit" name="updatecat" value="<?php _e( 'Update', 'weekly-schedule' ); ?> &raquo;" /></p>
						<?php else: ?>
							<p style="border:0;" class="submit">
								<input type="submit" name="newcat" value="<?php _e( 'Insert New Category', 'weekly-schedule' ); ?> &raquo;" /></p>
						<?php endif; ?>
					</form>
				</div>
				<div>
					<?php $cats = $wpdb->get_results( "SELECT count( i.id ) AS nbitems, c.name, c.id, c.backgroundcolor, c.scheduleid FROM " . ws_db_prefix() . "wscategories c LEFT JOIN " . ws_db_prefix() . "wsitems i ON i.category = c.id WHERE c.scheduleid = " . intval( $schedule ) . " GROUP BY c.id" );

					if ( $cats ): ?>
						<table class='widefat' style='clear:none;width:400px;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
							<thead>
							<tr>
								<th scope='col' style='width: 50px' id='id' class='manage-column column-id'><?php _e( 'ID', 'weekly-schedule' ); ?></th>
								<th scope='col' id='name' class='manage-column column-name' style=''><?php _e( 'Name', 'weekly-schedule' ); ?></th>
								<th scope='col' style='width: 50px;text-align: right' id='color' class='manage-column column-color' style=''><?php _e( 'Color', 'weekly-schedule' ); ?></th>
								<th scope='col' style='width: 50px;text-align: right' id='items' class='manage-column column-items' style=''><?php _e( 'Items', 'weekly-schedule' ); ?></th>
								<th style='width: 30px'></th>
							</tr>
							</thead>

							<tbody id='the-list' class='list:link-cat'>

							<?php foreach ( $cats as $cat ): ?>
								<tr>
									<td class='name column-name' style='background: #FFF'><?php echo intval( $cat->id ); ?></td>
									<td style='background: #FFF'>
										<a href='?page=weekly-schedule&amp;editcat=<?php echo intval( $cat->id ); ?>&schedule=<?php echo intval( $schedule ); ?>'><strong><?php echo esc_html( $cat->name ); ?></strong></a>
									</td>
									<td style='background: <?php echo $cat->backgroundcolor != null ? esc_html( $cat->backgroundcolor ) : '#FFF'; ?>;text-align:right'></td>
									<td style='background: #FFF;text-align:right'><?php echo intval( $cat->nbitems ); ?></td>
									<?php if ( $cat->nbitems == 0 ): ?>
										<td style='background:#FFF'>
											<a href='?page=weekly-schedule&amp;deletecat=<?php echo intval( $cat->id ); ?>&schedule=<?php echo intval( $schedule ); ?>'
												<?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to delete this category '%s'\n  'Cancel' to stop, 'OK' to delete.", 'weekly-schedule' ), esc_html( $cat->name ) ) ) . "') ) { return true;}return false;\"" ?>><img src='<?php echo plugins_url( '/icons/delete.png', __FILE__ ); ?>' /></a>
										</td>
									<?php else: ?>
										<td style='background: #FFF'></td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>

							</tbody>
						</table>

					<?php endif; ?>

					<p><?php _e( "Categories can only be deleted when they don't have any associated items", 'weekly-schedule' ); ?>.</p>
				</div>
				<?php /* --------------------------------------- Items --------------------------------- */ ?>
			<?php
			elseif ( $adminpage == "items" ): ?>
				<a href="?page=weekly-schedule&amp;settings=general&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'General Settings', 'weekly-schedule' ); ?></a> |
				<a href="?page=weekly-schedule&amp;settings=categories&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Categories', 'weekly-schedule' ); ?></a> |
				<a href="?page=weekly-schedule&amp;settings=items&amp;schedule=<?php echo intval( $schedule ); ?>"><strong><?php _e( 'Manage Schedule Items', 'weekly-schedule' ); ?></strong></a> |
				<a href="?page=weekly-schedule&amp;settings=days&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Days Labels', 'weekly-schedule' ); ?></a>
				<br /><br />
				<div style='float:left;margin-right: 15px;width: 500px;'>
					<form name="wsitemsform" action="" method="post" id="ws-config">
						<?php
						if ( function_exists( 'wp_nonce_field' ) ) {
							wp_nonce_field( 'wspp-config' );
						}
						?>

						<input type="hidden" name="id" value="<?php if ( $mode == 'edit' && isset( $selecteditem ) ) {
							echo intval( $selecteditem->id );
						} ?>" />
						<input type="hidden" name="oldrow" value="<?php if ( $mode == "edit" && isset( $selecteditem ) ) {
							echo intval( $selecteditem->row );
						} ?>" />
						<input type="hidden" name="oldday" value="<?php if ( $mode == "edit"  && isset( $selecteditem ) ) {
							echo intval( $selecteditem->day );
						} ?>" />
						<input type="hidden" name="schedule" value="<?php echo intval( $schedule ); ?>" />
						<?php if ( $mode == "edit"  && isset( $selecteditem ) ): ?>
							<strong><?php _e( 'Editing Item', 'weekly-schedule' ); ?> #<?php echo intval( $selecteditem->id ); ?></strong>
						<?php endif; ?>

						<table>
							<?php
							if ( function_exists( 'wp_nonce_field' ) ) {
								wp_nonce_field( 'wspp-config' );
							}
							?>
							<tr>
								<td style='width: 180px'><?php _e( 'Item Title', 'weekly-schedule' ); ?></td>
								<td><input style="width:360px" type="text" name="name" <?php if ( $mode == "edit" && isset( $selecteditem ) ) {
										echo "value='" . esc_html( stripslashes( $selecteditem->name ) ) . "'";
									} ?>/></td>
							</tr>
							<tr>
								<td><?php _e( 'Category', 'weekly-schedule' ); ?></td>
								<td><select style='width: 360px' name="category">
										<?php $cats = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wscategories where scheduleid = " . intval( $schedule ) . " ORDER by name" );

										foreach ( $cats as $cat ) {
											if ( isset( $selecteditem ) && $cat->id == $selecteditem->category ) {
												$selectedstring = "selected='selected'";
											} else {
												$selectedstring = "";
											}

											echo "<option value='" . intval( $cat->id ) . "' " . esc_html( $selectedstring ) . ">" . esc_html( $cat->name ) . "\n";
										}
										?></select></td>
							</tr>
							<tr>
								<td><?php _e( 'Description', 'weekly-schedule' ); ?></td>
								<td>
									<textarea id="description" rows="5" cols="45" name="description"><?php if ( $mode == "edit" && isset( $selecteditem ) ) {
											echo esc_html( stripslashes( $selecteditem->description ) );
										} ?></textarea></td>
							</tr>
							<tr>
								<td><?php _e( 'Web Address', 'weekly-schedule' ); ?></td>
								<td><input style="width:360px" type="text" name="address" <?php if ( $mode == "edit" && isset( $selecteditem ) ) {
										echo "value='" . esc_url( $selecteditem->address ) . "'";
									} ?>/></td>
							</tr>
							<tr>
								<td><?php _e( 'Day', 'weekly-schedule' ); ?></td>
								<td><select style='width: 360px' name="day">
										<?php $days = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsdays where scheduleid = " . intval( $schedule ) . " ORDER by id" );

										foreach ( $days as $day ) {
											echo "<option value='" . intval( $day->id ) . "' " . selected( $day->id, $selecteditem->day, false ) . ">" . esc_html( $day->name ) . "\n";
										}
										?></select></td>
							</tr>
							<tr>
								<td><?php _e( 'Start Time', 'weekly-schedule' ); ?></td>
								<td><select style='width: 360px' name="starttime">
										<?php for ( $i = $options['starttime']; $i < $options['endtime']; $i += $options['timedivision'] ) {
											echo "<option value='" . $i . "'" . selected( $i, $selecteditem->starttime, false ) . ">" . ws_time_string( $i, $options['timeformat'] ) . "\n";
										}
										?></select></td>
							</tr>
							<tr>
								<td><?php _e( 'Duration', 'weekly-schedule' ); ?></td>
								<td><select style='width: 360px' name="duration">
										<?php for ( $i = $options['timedivision']; $i <= ( $options['endtime'] - $options['starttime'] ); $i += $options['timedivision'] ) {
											if ( fmod( $i, 1 ) == 0.25 ) {
												$minutes = "15";
											} elseif ( fmod( $i, 1 ) == 0.50 ) {
												$minutes = "30";
											} elseif ( fmod( $i, 1 ) == 0.75 ) {
												$minutes = "45";
											} else {
												$minutes = "00";
											}

											echo "<option value='" . esc_html( $i ) . "' " . selected( $i, $selecteditem->duration, false ) . ">" . floor( $i ) . "h" . esc_html( $minutes ) . "\n";
										}
										?></select></td>
							</tr>
							<tr>
								<td><?php _e( 'Background Cell Color (optional)', 'weekly-schedule' ); ?></td>
								<td>
									<input style="width:100px" type="text" name="backgroundcolor" <?php if ( $mode == "edit" && isset( $selecteditem ) ) {
										echo "value='" . esc_html( $selecteditem->backgroundcolor ) . "'";
									} ?>/></td>
							</tr>
							<tr>
								<td><?php _e( 'Title Color (optional)', 'weekly-schedule' ); ?></td>
								<td>
									<input style="width:100px" type="text" name="titlecolor" <?php if ( $mode == "edit" && isset( $selecteditem )) {
										echo "value='" . esc_html( $selecteditem->titlecolor ) . "'";
									} ?>/></td>
							</tr>
						</table>
						<?php if ( $mode == "edit" ): ?>
							<p style="border:0;" class="submit">
								<input type="submit" name="updateitem" value="<?php _e( 'Update', 'weekly-schedule' ); ?> &raquo;" /></p>
						<?php else: ?>
							<p style="border:0;" class="submit">
								<input type="submit" name="newitem" value="<?php _e( 'Insert New Item', 'weekly-schedule' ); ?> &raquo;" /></p>
						<?php endif; ?>
					</form>
				</div>
				<div>
					<?php
					$itemquery = "SELECT d.name as dayname, i.id, i.name, i.backgroundcolor, i.day, i.starttime FROM " . ws_db_prefix() . "wsitems as i, " . ws_db_prefix() . "wsdays as d WHERE i.day = d.id
								and i.scheduleid = " . intval( $schedule ) . " and d.scheduleid = " . intval( $_GET['schedule'] ) . " ORDER by d.id, starttime, name";
					$items = $wpdb->get_results( $itemquery );

					if ( $items ): ?>
						<form name="wsitemdeletionform" action="?page=weekly-schedule&settings=items&schedule=<?php echo intval( $schedule ); ?>" method="post" id="ws-config">
							<?php
							if ( function_exists( 'wp_nonce_field' ) ) {
								wp_nonce_field( 'wspp-config' );
							}
							?>

							<input class="button" type="submit" name="deleteallitems" value="<?php _e( 'Delete all items in Schedule', 'weekly-schedule' ); ?> <?php echo intval( $schedule ); ?>" onclick="return confirm('<?php _e( 'Are you sure you want to delete all items in Schedule', 'weekly-schedule' ); ?> <?php echo intval( $schedule ); ?>?')" />
						</form>
						<br />
						<table class='widefat' style='clear:none;width:500px;background: #DFDFDF url(/wp-admin/images/gray-grad.png) repeat-x scroll left top;'>
							<thead>
							<tr>
								<th scope='col' style='width: 50px' id='id' class='manage-column column-id'><?php _e( 'ID', 'weekly-schedule' ); ?></th>
								<th scope='col' id='name' class='manage-column column-name' style=''><?php _e( 'Name', 'weekly-schedule' ); ?></th>
								<th scope='col' id='color' class='manage-column column-color' style=''><?php _e( 'Color', 'weekly-schedule' ); ?></th>
								<th scope='col' id='day' class='manage-column column-day' style='text-align: right'><?php _e( 'Day', 'weekly-schedule' ); ?></th>
								<th scope='col' style='width: 50px;text-align: right' id='starttime' class='manage-column column-items' style=''><?php _e( 'Start Time', 'weekly-schedule' ); ?></th>
								<th style='width: 30px'></th>
							</tr>
							</thead>

							<tbody id='the-list' class='list:link-cat'>

							<?php foreach ( $items as $item ): ?>
								<tr>
									<td class='name column-name' style='background: #FFF'>
										<a href='?page=weekly-schedule&amp;edititem=<?php echo intval( $item->id ); ?>&amp;schedule=<?php echo intval( $schedule ); ?>'><strong><?php echo intval( $item->id ); ?></strong></a>
									</td>
									<td style='background: #FFF'>
										<a href='?page=weekly-schedule&amp;edititem=<?php echo intval( $item->id ); ?>&amp;schedule=<?php echo intval( $schedule ); ?>'><strong><?php echo esc_html( stripslashes( $item->name ) ); ?></strong></a>
									</td>

									<td style='background: <?php echo $item->backgroundcolor ? esc_html( $item->backgroundcolor ) : '#FFF'; ?>'></td>
									<td style='background: #FFF;text-align:right'><?php echo esc_html( $item->dayname ); ?></td>
									<td style='background: #FFF;text-align:right'>
										<?php
										echo ws_time_string( $item->starttime, $options['timeformat'] ) . "\n";
										?></td>
									<td style='background:#FFF'>
										<a href='?page=weekly-schedule&amp;deleteitem=<?php echo intval( $item->id ); ?>&amp;schedule=<?php echo intval( $schedule ); ?>'
											<?php echo "onclick=\"if ( confirm('" . esc_js( sprintf( __( "You are about to delete the item '%s'\n  'Cancel' to stop, 'OK' to delete.", 'weekly-schedule' ), esc_html( $item->name ) ) ) . "') ) { return true;}return false;\""; ?>><img src='<?php echo plugins_url( '/icons/delete.png', __FILE__ ); ?>' /></a>
									</td>
								</tr>
							<?php endforeach; ?>

							</tbody>
						</table>
					<?php else: ?>
						<p><?php _e( 'No items to display', 'weekly-schedule' ); ?></p>
					<?php endif; ?>
				</div>
			<?php
			elseif ( $adminpage == "days" ): ?>
				<div>
					<a href="?page=weekly-schedule&amp;settings=general&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'General Settings', 'weekly-schedule' ); ?></a> |
					<a href="?page=weekly-schedule&amp;settings=categories&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Categories', 'weekly-schedule' ); ?></a> |
					<a href="?page=weekly-schedule&amp;settings=items&amp;schedule=<?php echo intval( $schedule ); ?>"><?php _e( 'Manage Schedule Items', 'weekly-schedule' ); ?></a> |
					<a href="?page=weekly-schedule&amp;settings=days&amp;schedule=<?php echo intval( $schedule ); ?>"><strong><?php _e( 'Manage Days Labels', 'weekly-schedule' ); ?></strong></a><br /><br />

					<div>
						<form name="wsdaysform" action="" method="post" id="ws-config">
							<?php
							if ( function_exists( 'wp_nonce_field' ) ) {
								wp_nonce_field( 'wspp-config' );
							}

							$days = $wpdb->get_results( "SELECT * from " . ws_db_prefix() . "wsdays WHERE scheduleid = " . intval( $schedule ) . " ORDER by id" );

							if ( $days ):
								?>
								<input type="hidden" name="schedule" value="<?php echo intval( $schedule ); ?>" />
								<table>
									<tr>
										<th style='text-align:left'><strong><?php _e( 'ID', 'weekly-schedule' ); ?></strong></th>
										<th style='text-align:left'><strong><?php _e( 'Name', 'weekly-schedule' ); ?></strong></th>
									</tr>
									<?php foreach ( $days as $day ): ?>
										<tr>
											<td style='width:30px;'><?php echo intval( $day->id ); ?></td>
											<td>
												<input style="width:300px" type="text" name="<?php echo intval( $day->id ); ?>" value='<?php echo esc_html( $day->name ); ?>' />
											</td>
										</tr>
									<?php endforeach; ?>
								</table>

								<p style="border:0;" class="submit">
									<input type="submit" name="updatedays" value="<?php _e( 'Update', 'weekly-schedule' ); ?> &raquo;" /></p>

							<?php endif; ?>

						</form>
					</div>
				</div>
			<?php
			endif; ?>
			</div>
		<?php
		} // end config_page()

	} // end class WS_Admin

	$my_ws_admin = new WS_Admin;
} //endif

function ws_library_func( $atts ) {
	$schedule = 1;
	extract(
		shortcode_atts(
			array(
				'schedule' => '',
				'cats' => ''
			), $atts
		)
	);

	if ( empty( $schedule ) ) {
		$options  = ws_get_options( '1' );
		$schedule = 1;
	} else {
		$options      = ws_get_options( intval( $schedule ) );
	}

	if ( $options == false ) {
		return "Requested schedule (Schedule " . intval( $schedule ) . ") is not available from Weekly Schedule<br />";
	}

	return ws_library(
		$schedule, $options['starttime'], $options['endtime'], $options['timedivision'], $options['layout'], $options['tooltipwidth'], $options['tooltiptarget'],
		$options['tooltippoint'], $options['tooltipcolorscheme'], $options['displaydescription'], $options['daylist'], $options['timeformat'],
		$options['adjusttooltipposition'], $options['linktarget'], sanitize_text_field( $cats ), $options['floatthead']
	);
}

function ws_library_flat_func( $atts ) {
	$schedule = 1;

	extract(
		shortcode_atts(
			array(
				'schedule' => '',
				'cats' => ''
			), $atts
		)
	);

	if ( empty( $schedule ) ) {
		$options  = ws_get_options( '1' );
		$schedule = 1;
	} else {
		$options      = ws_get_options( intval( $schedule ) );
	}

	if ( $options == false ) {
		return "Requested schedule (Schedule " . intval( $schedule ) . ") is not available from Weekly Schedule<br />";
	}

	return ws_library_flat(
		$schedule, $options['starttime'], $options['endtime'], $options['timedivision'], $options['layout'], $options['tooltipwidth'], $options['tooltiptarget'],
		$options['tooltippoint'], $options['tooltipcolorscheme'], $options['displaydescription'], $options['daylist'], $options['timeformat'],
		$options['adjusttooltipposition'], sanitize_text_field( $cats ), $options['floatthead']
	);
}


function ws_library(
	$scheduleid = 1, $starttime = 19, $endtime = 22, $timedivision = 0.5, $layout = 'horizontal', $tooltipwidth = 300, $tooltiptarget = 'right center',
	$tooltippoint = 'leftMiddle', $tooltipcolorscheme = 'ui-tooltip', $displaydescription = 'tooltip', $daylist = '', $timeformat = '24hours',
	$adjusttooltipposition = true, $linktarget = 'newwindow', $cats = '', $floatthead = false
) {
	global $wpdb;

	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

	$today = date( 'w', current_time( 'timestamp', 0 ) ) + 1;
	$system_hour = date( 'H', current_time( 'timestamp', 0 ) );
	$system_minute = date( 'i', current_time( 'timestamp', 0 ) ) / 60;
	$time_now = $system_hour + $system_minute;

	$numberofcols = ( $endtime - $starttime ) / $timedivision;

	$output = "<!-- Weekly Schedule Output -->\n";

	$output .= "<div class='ws-schedule";

	if ( $genoptions['displayflatmobile'] ) {
		$output .= ' ws-schedule-desktop';
	}

	$output .= "' id='ws-schedule" . intval( $scheduleid ) . "'>\n";

	if ( $layout == 'horizontal' || empty( $layout ) ) {
		$output .= "<table class='horizontal'><thead>\n";
	} elseif ( $layout == 'vertical' ) {
		$output .= "<div class='verticalcolumn'>\n";
		$output .= "<table class='verticalheader'>\n";
	}

	$output .= "<tr class='topheader'>";

	$output .= "<th class='rowheader'></th>";

	if ( $layout == 'vertical' ) {
		$output .= "</tr>\n";
	}

	for ( $i = $starttime; $i < $endtime; $i += $timedivision ) {

		if ( $layout == 'vertical' ) {
			$output .= "<tr class='datarow'>";
		}

		$output .= "<th>" . ws_time_string( $i, $timeformat ) . "</th>";

		if ( $layout == 'vertical' ) {
			$output .= "</tr>\n";
		}
	}

	if ( $layout == 'horizontal' || empty( $layout ) ) {
		$output .= "</tr></thead><tbody>\n";
	} elseif ( $layout == 'vertical' ) {
		$output .= "</table>\n";
		$output .= "</div>\n";
	}

	$sqldays = "SELECT * from " . ws_db_prefix() . "wsdays where scheduleid = %d";

	if ( !empty( $daylist ) ) {
		$sqldays .= " AND id in ( %s ) ORDER BY FIELD(id, %s)";
		$sqldaysquery = $wpdb->prepare( $sqldays, intval( $scheduleid ), esc_html( $daylist ), esc_html( $daylist ) );
		$sqldaysquery = str_replace( '\'', '', $sqldaysquery );
		$daysoftheweek = $wpdb->get_results( $sqldaysquery );
	} else {
		$daysoftheweek = $wpdb->get_results( $wpdb->prepare( $sqldays, $scheduleid ) );
	}

	foreach ( $daysoftheweek as $day ) {
		for ( $daysrow = 1; $daysrow <= $day->rows; $daysrow ++ ) {
			$columns = $numberofcols;
			$time    = $starttime;
			$firstrowofday = 0;

			if ( $layout == 'vertical' ) {
				$output .= "<div class='verticalcolumn" . intval( $day->rows ) . "'>\n";
				$output .= "<table class='vertical" . intval( $day->rows ) . "'>\n";
				$output .= "<thead class='vertrow" . intval( $day->rows ) . "'>";
			} elseif ( $layout == 'horizontal' || empty( $layout ) ) {
				$output .= "<tr class='row" . intval( $day->rows ) . " ";
				if ( !$firstrowofday ) {
					$output .= "firstrowofday";
					$firstrowofday = 1;
				}
				$output .= "'>\n";
			}

			if ( $daysrow == 1 && ( $layout == 'horizontal' || empty( $layout ) ) ) {
				$output .= "<th rowspan='" . intval( $day->rows ) . "' class='rowheader'>" . esc_html( $day->name ) . "</th>\n";
			}
			if ( $daysrow == 1 && $layout == 'vertical' && $day->rows == 1 ) {
				$output .= "<th class='rowheader'>" . esc_html( $day->name ) . "</th>\n";
			}
			if ( $daysrow == 1 && $layout == 'vertical' && $day->rows > 1 ) {
				$output .= "<th class='rowheader'>&laquo; " . esc_html( $day->name ) . "</th>\n";
			} elseif ( $daysrow != 1 && $layout == 'vertical' ) {
				if ( $daysrow == $day->rows ) {
					$output .= "<th class='rowheader'>" . esc_html( $day->name ) . " &raquo;</th>\n";
				} else {
					$output .= "<th class='rowheader'>&laquo; " . esc_html( $day->name ) . " &raquo;</th>\n";
				}
			}

			if ( $layout == 'vertical' ) {
				$output .= "</thead><tbody>\n";
			}

			$sqlitems = "SELECT *, i.name as itemname, c.name as categoryname, c.id as catid, i.backgroundcolor as itemcolor, c.backgroundcolor as categorycolor, i.day as dayid from " . ws_db_prefix() .
				"wsitems i, " . ws_db_prefix() . "wscategories c WHERE i.day = " . intval( $day->id ) .
				" AND i.scheduleid = %d AND i.row = " . intval( $daysrow ) . " AND i.category = c.id AND i.starttime >= %f AND i.starttime < %f ";

			if ( !empty( $cats ) ) {
				$sqlitems .= ' AND i.category IN ( %s ) ';
			} 

			$sqlitems .= " ORDER by i.starttime";

			if ( empty( $cats ) ) {
				$items = $wpdb->get_results( $wpdb->prepare( $sqlitems, intval ( $scheduleid ), floatval( $starttime ), floatval( $endtime ) ) );
			} else {
				$items = $wpdb->get_results( $wpdb->prepare( $sqlitems, intval( $scheduleid ), floatval( $starttime ), floatval( $endtime ), esc_html( $cats ) ) );
			}

			if ( $items ) {
				foreach ( $items as $item ) {

					for ( $i = $time; $i < $item->starttime; $i += $timedivision ) {
						if ( $layout == 'vertical' ) {
							$output .= "<tr class='datarow'>\n";
						}

						$output .= "<td></td>\n";

						if ( $layout == 'vertical' ) {
							$output .= "</tr>\n";
						}

						$columns -= 1;

					}

					$colspan = $item->duration / $timedivision;

					if ( $colspan > $columns ) {
						$colspan = $columns;
						$columns -= $columns;

						if ( $layout == 'horizontal' ) {
							$continue = "id='continueright' ";
						} elseif ( $layout == 'vertical' ) {
							$continue = "id='continuedown' ";
						}
					} else {
						$columns -= $colspan;
						$continue = "";
					}

					if ( $layout == 'vertical' ) {
						$output .= "<tr class='datarow" . intval( $colspan ) . "'>";
					}

					$output .= '<td class="';

					if ( $item->starttime < $time_now && $time_now < ( $item->starttime + $item->duration ) && $today == $item->dayid ) {
						$output .= 'now-playing ';
					}

					$output .= 'ws-item-' . intval( $item->id ) . ' cat' . intval( $item->catid ) . '" ';


					if ( !empty( $item->itemcolor ) || !empty( $item->categorycolor ) ) {

						$output .= 'style= "' . 'background-color:' . ( !empty( $item->itemcolor ) ? esc_html( $item->itemcolor ) : esc_html( $item->categorycolor ) ) . ';"';
					}

					if ( $displaydescription == "tooltip" && !empty( $item->description ) ) {
						$output .= "tooltip='" . esc_html( htmlspecialchars( stripslashes( $item->description ), ENT_QUOTES ) ) . "' ";
					}

					$output .= $continue;

					if ( $layout == 'horizontal' || empty( $layout ) ) {
						$output .= "colspan='" . intval( $colspan ) . "'";
					}

					$output .= '>';

					$output .= '<div class="';

					if ( $item->starttime < $time_now && $time_now < ( $item->starttime + $item->duration ) && $today == $item->dayid ) {
						$output .= 'now-playing ';
					}

					$output .= 'ws-item-title ws-item-title-' . intval( $item->id ) . '"';

					if ( !empty( $item->titlecolor ) ) {
						$output .= ' style="color:' . esc_html( $item->titlecolor ) . '"';
					}

					$output .= ">";

					if ( !empty( $item->address ) ) {
						$output .= "<a target='" . esc_html( $linktarget ) . "' href='" . esc_url( $item->address ) . "'>";
					}

					$output .= esc_html( stripslashes( $item->itemname ) );

					if ( !empty( $item->address ) ) {
						"</a>";
					}

					$output .= "</div>";

					if ( $displaydescription == "cell" && !empty( $item->description ) ) {
						$output .= "<br />" . esc_html( stripslashes( $item->description ) );
					}

					$output .= "</td>";
					$time = $item->starttime + $item->duration;

					if ( $layout == 'vertical' ) {
						$output .= "</tr>\n";
					}

				}

				for ( $x = $columns; $x > 0; $x -- ) {

					if ( $layout == 'vertical' ) {
						$output .= "<tr class='datarow'>";
					}

					$output .= "<td></td>";
					$columns -= 1;

					if ( $layout == 'vertical' ) {
						$output .= "</tr>";
					}
				}
			} else {
				for ( $i = $starttime; $i < $endtime; $i += $timedivision ) {
					if ( $layout == 'vertical' ) {
						$output .= "<tr class='datarow'>";
					}

					$output .= "<td></td>";

					if ( $layout == 'vertical' ) {
						$output .= "</tr>";
					}
				}
			}

			if ( $layout == 'horizontal' || empty( $layout ) ) {
				$output .= "</tr>";
			}

			if ( $layout == 'vertical' ) {
				$output .= "</tbody></table>\n";
				$output .= "</div>\n";
			}
		}
	}

	if ( $layout == 'horizontal' || empty( $layout ) ) {
		$output .= "</tbody></table>";
	}

	$output .= "</div>\n";

	if ( $displaydescription == "tooltip" ) {
		$output .= "<script type=\"text/javascript\">\n";
		$output .= "// Create the tooltips only on document load\n";

		$output .= "jQuery(document).ready(function()\n";
		$output .= "\t{\n";
		$output .= "\t// Notice the use of the each() method to acquire access to each elements attributes\n";
		$output .= "\tjQuery('.ws-schedule td[tooltip]').each(function()\n";
		$output .= "\t\t{\n";
		$output .= "\t\tjQuery(this).qtip({\n";
		$output .= "\t\t\tcontent: jQuery(this).attr('tooltip'), // Use the tooltip attribute of the element for the content\n";
		$output .= "\t\t\tstyle: {\n";
		$output .= "\t\t\t\twidth: " . intval( $tooltipwidth ) . ",\n";
		$output .= "\t\t\t\tclasses: '" . esc_html( $tooltipcolorscheme ) . "' // Give it a crea mstyle to make it stand out\n";
		$output .= "\t\t\t},\n";
		$output .= "\t\t\tposition: {\n";
		if ( $adjusttooltipposition ) {
			$output .= "\t\t\t\tadjust: {method: 'flip flip'},\n";
		}
		$output .= "\t\t\t\tviewport: jQuery(window),\n";
		$output .= "\t\t\t\tat: '" . esc_html( $tooltiptarget ) . "',\n";
		$output .= "\t\t\t\tmy: '" . esc_html( $tooltippoint ) . "'\n";
		$output .= "\t\t\t}\n";
		$output .= "\t\t});\n";
		$output .= "\t});\n";
		$output .= "});\n";
		$output .= "</script>\n";

	}

	if ( $floatthead ) {
		$output .= "<script type=\"text/javascript\">\n";

		if ( 'vertical' == $layout ) {
			$output .= "jQuery( '.vertical1' ).floatThead();\n";
		} elseif ( 'horizontal' == $layout ) {
			$output .= "jQuery( '.horizontal' ).floatThead();\n";
		}

		$output .= "</script>\n";
	}

	$output .= "<!-- End of Weekly Schedule Output -->\n";

	if ( $genoptions['displayflatmobile'] ) {
		$output .= ws_library_flat( $scheduleid, $starttime, $endtime, $timedivision, $layout, $tooltipwidth, $tooltiptarget, $tooltippoint, $tooltipcolorscheme, $displaydescription, $daylist, $timeformat, $adjusttooltipposition, $cats, $floatthead );
	}

	return $output;
}

function ws_library_flat(
	$scheduleid = 1, $starttime = 19, $endtime = 22, $timedivision = 0.5, $layout = 'horizontal', $tooltipwidth = 300, $tooltiptarget = 'right center',
	$tooltippoint = 'leftMiddle', $tooltipcolorscheme = 'ui-tooltip', $displaydescription = 'tooltip', $daylist = '', $timeformat = '24hours',
	$adjusttooltipposition = true, $cats = '', $floatthead = false
) {
	global $wpdb;

	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

	$today = date( 'w', current_time( 'timestamp', 0 ) ) + 1;
	$system_hour = date( 'H', current_time( 'timestamp', 0 ) );
	$system_minute = date( 'i', current_time( 'timestamp', 0 ) ) / 60;
	$time_now = $system_hour + $system_minute;

	$linktarget = 'newwindow';

	$output = "<!-- Weekly Schedule Flat Output -->\n";

	$output .= "<div class='ws-schedule";

	if ( $genoptions['displayflatmobile'] ) {
		$output .= ' ws-schedule-mobile';
	}

	$output .= "' id='ws-schedule" . intval( $scheduleid ) . "'>\n";

	$sqldays = "SELECT * from " . ws_db_prefix() . "wsdays where scheduleid = %d";

	if ( !empty( $daylist ) ) {
		$sqldays .= " AND id in ( %s ) ORDER BY FIELD(id, %s )";
		$sqldaysquery = $wpdb->prepare( $sqldays, intval( $scheduleid ), esc_html( $daylist ), esc_html( $daylist ) );
		$sqldaysquery = str_replace( '\'', '', $sqldaysquery );
		$daysoftheweek = $wpdb->get_results( $sqldaysquery );
	} else {
		$daysoftheweek = $wpdb->get_results( $wpdb->prepare( $sqldays, intval( $scheduleid ) ) );
	}

	$output .= "<table>\n";

	foreach ( $daysoftheweek as $day ) {
		for ( $daysrow = 1; $daysrow <= $day->rows; $daysrow ++ ) {
			$output .= "<tr><td class='ws-schedule-flat-dayname' colspan='3'>" . esc_html( $day->name ) . "</td></tr>\n";

			$sqlitems = "SELECT *, i.name as itemname, c.name as categoryname, c.id as catid, i.day as dayid, i.backgroundcolor as itemcolor, c.backgroundcolor as categorycolor from " . ws_db_prefix() .
				"wsitems i, " . ws_db_prefix() . "wscategories c WHERE i.day = " . intval( $day->id ) .
				" AND i.scheduleid = %d AND i.row = " . intval( $daysrow ) . " AND i.category = c.id AND i.starttime >= %f AND i.starttime < %f ";

			if ( !empty( $cats ) ) {
				$sqlitems .= 'AND i.category IN ( %s )';
			}

			$sqlitems .= "ORDER by starttime";

			if ( empty( $cats ) ) {
				$items = $wpdb->get_results( $wpdb->prepare( $sqlitems, intval( $scheduleid ), floatval( $starttime ), floatval( $endtime ) ) );
			} else {
				$items = $wpdb->get_results( $wpdb->prepare( $sqlitems, intval( $scheduleid ), floatval( $starttime ), floatval( $endtime ), esc_html( $cats ) ) );
			}

			if ( $items ) {
				foreach ( $items as $item ) {

					$output .= "<tr>\n";

					$output .= "<td>" . ws_time_string( $item->starttime, $timeformat ) . " - ";

					$endtime = $item->starttime + $item->duration;

					$output .= ws_time_string( $endtime, $timeformat ) . "</td>";

					$output .= '<td';

					if ( !empty( $item->itemcolor ) || !empty( $item->categorycolor ) ) {
						$output .= ' style= "' . 'background-color:' . ( !empty( $item->itemcolor ) ? esc_html( $item->itemcolor ) : esc_html( $item->categorycolor ) ) . ';"';
					}

					if ( empty( $item->description ) ) {
						$output .= ' colspan="2"';
					}

					if ( $item->starttime < $time_now && $time_now < ( $item->starttime + $item->duration ) && $today == $item->dayid ) {
						$output .= ' class="now-playing"';
					}

					$output .= ">\n";

					if ( !empty( $item->address ) ) {
						$output .= "<a target='" . esc_html( $linktarget ) . "'href='" . esc_url( $item->address ) . "'>";
					}

					$output .= esc_html( $item->itemname );

					if ( !empty( $item->address ) ) {
						"</a>";
					}

					$output .= "</td>";

					if ( !empty( $item->description ) ) {
						$output .= "<td>" . esc_html( htmlspecialchars( stripslashes( $item->description ), ENT_QUOTES ) ) . "</td>";
					}

					$output .= "</tr>";
				}
			}
		}
	}

	$output .= "</table>";

	$output .= "</div id='ws-schedule'>\n";

	$output .= "<!-- End of Weekly Schedule Flat Output -->\n";

	return $output;
}

$version = "1.0";

add_shortcode( 'weekly-schedule', 'ws_library_func' );

add_shortcode( 'flat-weekly-schedule', 'ws_library_flat_func' );

add_shortcode( 'daily-weekly-schedule', 'ws_day_list_func' );

load_plugin_textdomain( 'weekly-schedule', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

function ws_day_list_func( $atts ) {
	$schedule  = 1;
	$max_items = 5;
	$empty_msg = 'No Items Found';

	extract(
		shortcode_atts(
			array(
				'schedule'  => 1,
				'cats'      => '',
				'max_items' => 5,
				'empty_msg' => 'No Items Found'
			), $atts
		)
	);

	$today  = date( 'w', current_time( 'timestamp', 0 ) ) + 1;
	$output = '<div class="ws_widget_output">';

	//fetch results
	global $wpdb;

	$schedule_query = 'SELECT * from ' . ws_db_prefix() .
		'wsitems WHERE day = ' . $today .
		' AND scheduleid = %d ';

	if ( !empty( $cats ) ) {
		$schedule_query .= ' AND category IN ( %s ) ';
	}

	$schedule_query .= 'ORDER by starttime ASC LIMIT 0, %d';

	if ( empty( $cats ) ) {
		$schedule_items = $wpdb->get_results( $wpdb->prepare( $schedule_query, intval( $schedule ), intval( $max_items ) ) );
	} else {
		$schedule_items = $wpdb->get_results( $wpdb->prepare( $schedule_query, intval( $schedule ), esc_html( $cats ), intval( $max_items ) ) );
	}

	if ( !empty( $schedule_items ) ) {
		$output .= '<ul>';

		foreach ( $schedule_items as $schedule_item ) {
			$item_name  = esc_html( stripslashes( $schedule_item->name ) );

			$output .= '<li>';
			if ( !empty( $schedule_item->address ) ) {
				$output .= '<a href="' . esc_url( $schedule_item->address ) . '">';
			}
			$output .= ws_time_string( $schedule_item->starttime, $options['timeformat'] ) . ' - ' . esc_html( $item_name );

			if ( !empty( $schedule_item->address ) ) {
				$output .= '</a>';
			}
			$output .= '</li>';
		}

		$output .= '</ul>';
	} else {
		$output .= esc_html( $empty_msg );
	}

	$output .= '</div>';

	return $output;
}

add_filter( 'the_posts', 'ws_conditional_header' ); // the_posts gets triggered before wp_head

function ws_conditional_header( $posts ) {
	if ( empty( $posts ) ) {
		return $posts;
	}

	$load_jquery = false;
	$load_qtip   = false;
	$load_floatthead = false;
	$load_style  = false;

	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

	foreach ( $posts as $post ) {
		$continuesearch = true;
		$searchpos      = 0;
		$scheduleids    = array();

		while ( $continuesearch ) {
			$weeklyschedulepos = stripos( $post->post_content, 'weekly-schedule ', $searchpos );
			if ( $weeklyschedulepos == false ) {
				$weeklyschedulepos = stripos( $post->post_content, 'weekly-schedule]', $searchpos );
			}
			$continuesearch = $weeklyschedulepos;
			if ( $continuesearch ) {
				$load_style   = true;
				$shortcodeend = stripos( $post->post_content, ']', $weeklyschedulepos );
				if ( $shortcodeend ) {
					$searchpos = $shortcodeend;
				} else {
					$searchpos = $weeklyschedulepos + 1;
				}

				if ( $shortcodeend ) {
					$settingconfigpos = stripos( $post->post_content, 'settings=', $weeklyschedulepos );
					if ( $settingconfigpos && $settingconfigpos < $shortcodeend ) {
						$schedule = substr( $post->post_content, $settingconfigpos + 9, $shortcodeend - $settingconfigpos - 9 );

						$scheduleids[] = $schedule;
					} else if ( count( $scheduleids ) == 0 ) {
						$scheduleids[] = 1;
					}
				}
			}
		}
	}

	if ( $scheduleids ) {
		foreach ( $scheduleids as $scheduleid ) {
			$options      = ws_get_options( $scheduleid );

			if ( 'tooltip' == $options['displaydescription'] ) {
				$load_jquery = true;
				$load_qtip   = true;
			}

			if ( $options['floatthead'] ) {
				$load_jquery = true;
				$load_floatthead = true;
			}
		}
	}

	if ( isset( $genoptions['includestylescript'] ) && !empty( $genoptions['includestylescript'] ) ) {
		$pagelist = explode( ',', $genoptions['includestylescript'] );
		foreach ( $pagelist as $pageid ) {
			if ( is_page( $pageid ) ) {
				$load_jquery = true;
				$load_style  = true;
				$load_qtip   = true;
				$load_floatthead = true;
			}
		}
	}

	if ( $load_jquery ) {
		wp_enqueue_script( 'jquery' );
	}

	if ( $load_qtip ) {
		wp_enqueue_style( 'qtipstyle', plugins_url( 'jquery-qtip/jquery.qtip.min.css', __FILE__ ) );
		wp_enqueue_script( 'qtip', plugins_url( 'jquery-qtip/jquery.qtip.min.js', __FILE__ ) );
		wp_enqueue_script( 'imagesloaded', plugins_url( 'jquery-qtip/imagesloaded.pkg.min.js', __FILE__ ), 'qtip' );
	}

	if ( $load_floatthead ) {
		wp_enqueue_script( 'floatThead', plugins_url( 'float-thead/jquery.floatThead.min.js', __FILE__ ) );
	}

	return $posts;
}

add_filter( 'the_posts', 'ws_conditionally_add_scripts_and_styles' );

function ws_conditionally_add_scripts_and_styles( $posts ) {
	if ( empty( $posts ) ) {
		return $posts;
	}

	$load_style = false;

	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

	if ( is_admin() ) {
		$load_jquery   = false;
		$load_thickbox = false;
		$load_style    = false;
	} else {
		foreach ( $posts as $post ) {
			$linklibrarypos = stripos( $post->post_content, 'weekly-schedule', 0 );
			if ( $linklibrarypos !== false ) {
				$load_style = true;
			}
		}
	}

	global $wsstylesheet;
	if ( $load_style ) {
		$wsstylesheet = true;
	} else {
		$wsstylesheet = false;
	}

	return $posts;
}

add_action( 'wp_head', 'ws_header_output' );

function ws_header_output() {
	global $wsstylesheet;
	$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );

	if ( $wsstylesheet ) {
		echo "<style id='WeeklyScheduleStyle' type='text/css'>\n";
		echo esc_html( stripslashes( $genoptions['fullstylesheet'] ) );
		echo "</style>\n";
	}
}


/* Register widgets */
add_action( 'widgets_init', 'ws_register_widget' );

function ws_register_widget() {
	register_widget( "WSTodayScheduleWidget" );
}

class WSTodayScheduleWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'weekly_schedule_widget', // Base ID
			__( 'Weekly Schedule Widget', 'weekly-schedule' ), // Name
			array( 'description' => __( 'Displays a list of schedule items', 'weekly-schedule' ) ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wp_locale;
		$before_widget = '';
		$before_title  = '';
		$after_title   = '';
		$after_widget  = '';

		extract( $args );

		$title       = ( apply_filters( 'widget_title', $instance['title'] ) );
		$title_url   = ( !empty( $instance['title_url'] ) ? $instance['title_url'] : "" );
		$max_items   = ( !empty( $instance['max_items'] ) ? $instance['max_items'] : 5 );
		$schedule_id = ( !empty( $instance['schedule_id'] ) ? $instance['schedule_id'] : 1 );
		$empty_msg   = ( !empty( $instance['empty_msg'] ) ? $instance['empty_msg'] : __( 'No Items Found', 'weekly-schedule' ) );
		$only_next_items   = ( !empty( $instance['only_next_items'] ) ? $instance['only_next_items'] : false );

		$options      = ws_get_options( $schedule_id );

		$today = date( 'w', current_time( 'timestamp', 0 ) ) + 1;
		$system_hour = date( 'H', current_time( 'timestamp', 0 ) );
		$system_minute = date( 'i', current_time( 'timestamp', 0 ) ) / 60;
		$time_now = $system_hour + $system_minute;

		$previous_type = '';

		echo wp_kses( $before_widget, ws_allowed_html_tags() );
		if ( !empty( $title ) ) {
			echo wp_kses( $before_title, ws_allowed_html_tags() );
			if ( !empty( $title_url ) ) {
				echo '<a target="_fullschedule" href="' . esc_url( $title_url ) . '">';
			}
			echo esc_html( $title );
			if ( !empty( $title_url ) ) {
				echo '</a>';
			}
			echo wp_kses( $after_title, ws_allowed_html_tags() );
		}

		//fetch results
		global $wpdb;

		$previous_day = $today - 1;
		if ( $previous_day == 0 ) {
			$previous_day = 7;
		}

		$next_day = $today + 1;
		if ( $next_day == 8 ) {
			$next_day = 1;
		}

		$show_day_labels = false;

		$previous_day_items_query = 'SELECT *, "previous_day_item" as type, ( starttime + duration ) as calculated_time from ' . ws_db_prefix() .
								'wsitems WHERE day = ' . intval( $previous_day ) .
								' AND scheduleid = ' . intval( $schedule_id ) .
								' ORDER by starttime ASC';

		$previous_day_items = $wpdb->get_results( $previous_day_items_query );

		if ( !empty( $previous_day_items ) ) {
			$show_day_labels = true;
		}

		$schedule_query = 'SELECT *, "current_day_item" as type from ' . ws_db_prefix() .
			'wsitems WHERE day = ' . intval( $today ) .
			' AND scheduleid = ' . intval( $schedule_id ) . ' ORDER by starttime ASC';

		$schedule_items = $wpdb->get_results( $schedule_query );

		$next_day_item_query = 'SELECT *, "tomorrow_item" as type from ' . ws_db_prefix() .
		                  'wsitems WHERE day = ' . intval( $next_day ) .
		                  ' AND scheduleid = ' . intval( $schedule_id ) . ' ORDER by starttime ASC';

		$next_day_items = $wpdb->get_results( $next_day_item_query );

		if ( !empty( $next_day_items ) ) {
			$show_day_labels = true;
		}

		$combined_items = array_merge( $previous_day_items, $schedule_items, $next_day_items );

		$itemcount = 0;

		if ( !empty( $combined_items ) ) {

			foreach ( $combined_items as $schedule_item ) {
				if ( 'current_day_item' == $schedule_item->type && $only_next_items && $schedule_item->starttime <= $time_now ) {
					continue;
				}

				if ( 'current_day_item' == $schedule_item->type && !$only_next_items && $schedule_item->starttime < $time_now && ! ( $time_now < ( $schedule_item->starttime + $schedule_item->duration ) ) ) {
					continue;
				}

				if ( 'previous_day_item' == $schedule_item->type && $only_next_items ) {
					continue;
				}

				if ( 'previous_day_item' == $schedule_item->type && !$only_next_items && ( $schedule_item->starttime + $schedule_item->duration - 24 < $time_now ) ) {
					continue;
				}

				$itemcount++;

				if ( $itemcount > $max_items ) {
					break;
				}

				if ( $previous_type != $schedule_item->type && $show_day_labels ) {
					if ( !empty( $previous_type ) ) {
						echo '</ul><br />';
					}

					if ( 'previous_day_item' == $schedule_item->type ) {
						echo 'Yesterday<br />';
					} elseif ( 'current_day_item' == $schedule_item->type ) {
						echo 'Today<br />';
					} elseif ( 'tomorrow_item' == $schedule_item->type ) {
						echo 'Tomorrow <br />';
					}
					echo '<ul>';
					$previous_type = $schedule_item->type;
				} elseif ( $previous_type != $schedule_item->type && !$show_day_labels ) {
					echo '<ul>';
				}

				$item_name  = esc_html( stripslashes( $schedule_item->name ) );

				echo '<li';

				if ( $schedule_item->starttime < $time_now && $time_now < ( $schedule_item->starttime + $schedule_item->duration ) && $today == $schedule_item->day ) {
					echo ' class="now-playing"';
				}

				echo '>';
				if ( !empty( $schedule_item->address ) ) {
					echo '<a target="_scheduleitem' . intval( $schedule_item->id ) . '" href="' . esc_url( $schedule_item->address ) . '">';
				}
				echo ws_time_string( $schedule_item->starttime, $options['timeformat'] ) . ' - ' . esc_html( $item_name );

				if ( !empty( $schedule_item->address ) ) {
					echo '</a>';
				}
				echo '</li>';
			}

			echo '</ul>';
		} else {
			echo $empty_msg;
		}

		echo wp_kses( $after_widget, ws_allowed_html_tags() );
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = sanitize_text_field( strip_tags( $new_instance['title'] ) );
		$instance['title_url'] = esc_url( $new_instance['title_url'] );
		$instance['max_items'] = sanitize_text_field( strip_tags( $new_instance['max_items'] ) );

		if ( is_numeric( $new_instance['schedule_id'] ) ) {
			$instance['schedule_id'] = intval( $new_instance['schedule_id'] );
		} else {
			$instance['schedule_id'] = intval( $instance['schedule_id'] );
		}

		if ( isset( $new_instance['only_next_items'] ) ) {
			$instance['only_next_items'] = true;
		} else {
			$instance['only_next_items'] = false;
		}

		$instance['empty_msg'] = sanitize_text_field( strip_tags( $new_instance['empty_msg'] ) );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		/* Set initial values/defaults */
		$title       = ( !empty( $instance['title'] ) ? $instance['title'] : __( "Today's Scheduled Items", 'weekly-schedule' ) );
		$title_url   = ( !empty( $instance['title_url'] ) ? esc_url( $instance['title_url'] ) : "" );
		$max_items   = ( !empty( $instance['max_items'] ) ? $instance['max_items'] : 5 );
		$schedule_id = ( !empty( $instance['schedule_id'] ) ? $instance['schedule_id'] : 1 );
		$empty_msg   = ( !empty( $instance['empty_msg'] ) ? $instance['empty_msg'] : __( 'No Items Found', 'weekly-schedule' ) );
		$only_next_items   = ( !empty( $instance['only_next_items'] ) ? $instance['only_next_items'] : false );

		$genoptions = ws_get_general_options( 'WeeklyScheduleGeneral' );
		?>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title', 'weekly-schedule' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'title_url' ) ); ?>"><?php _e( 'URL for widget title', 'weekly-schedule' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title_url' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title_url' ) ); ?>" type="text" value="<?php echo esc_html( $title_url )  ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'empty_msg' ) ); ?>"><?php _e( 'Empty Item List Message', 'weekly-schedule' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'empty_msg' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'empty_msg' ) ); ?>" type="text" value="<?php echo esc_html( $empty_msg ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'max_items' ) ); ?>"><?php _e( 'Max Number of Items', 'weekly-schedule' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'max_items' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'max_items' ) ); ?>" type="text" value="<?php echo esc_html( $max_items ); ?>" />
			<span class='description'><?php __( 'Maximum number of items to display', 'weekly-schedule' ); ?></span>
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'max_items' ) ); ?>"><?php _e( 'Only show later items', 'weekly-schedule' ); ?></label>
			<input type="checkbox" id="<?php echo esc_html( $this->get_field_id( 'only_next_items' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'only_next_items' ) ); ?>" <?php checked( $only_next_items ); ?> />
		</p>

		<p>
			<label for="<?php echo esc_html( $this->get_field_id( 'schedule_id' ) ); ?>"><?php _e( 'Schedule ID', 'weekly-schedule' ); ?></label>

			<SELECT class="widefat" id="<?php echo esc_html( $this->get_field_id( 'schedule_id' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'schedule_id' ) ); ?>">
				<?php if ( empty( $genoptions['numberschedules'] ) ) {
					$number_of_schedules = 2;
				} else {
					$number_of_schedules = $genoptions['numberschedules'];
				}
				for ( $counter = 1; $counter <= $number_of_schedules; $counter ++ ): ?>
					<?php $tempoptions = ws_get_options( $counter ); ?>
					<option value="<?php echo intval( $counter ); ?>" <?php selected( $schedule_id, $counter, false ); ?>>Schedule <?php echo intval( $counter ); ?><?php if ( !empty( $tempoptions ) ) {
							echo " (" . esc_html( $tempoptions['schedulename'] ) . ")";
						} ?></option>
				<?php endfor; ?>
			</SELECT>
		</p>

	<?php
	}
}


?>
