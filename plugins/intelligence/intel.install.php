<?php

/**
 * Fired when the intel plugin is installed and contains schema info and updates.
 *
 * @link       getlevelten.com/blog/tom
 * @since      1.2.7
 *
 * @package    Intel
 */

// If uninstall not called from WordPress, then exit.
//if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
//	exit;
//}

/**
 * Implements hook_uninstall();
 */
function intel_uninstall() {
	global $wpdb;

	// delete tables
	$tables = array(
		"intel_visitor",
		"intel_visitor_identifier",
		"intel_submission",
		"intel_entity_attr",
		"intel_value_str",
	);
	foreach ($tables as $table) {
		$table_name = $wpdb->prefix . $table;
		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query( $sql );
	}

	// delete options
	$table_name = $wpdb->prefix . "options";
	$sql = "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'intel_%'";
	$wpdb->query( $sql );
}

function intel_update_1001() {
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "intel_entity_attr";

	$sql = "CREATE TABLE $table_name (
			entity_type varchar(64) DEFAULT '',
			entity_id int(10) UNSIGNED DEFAULT NULL,
			path varchar(255) DEFAULT '',
			alias varchar(255) DEFAULT '',
			attr_key varchar(64) NOT NULL DEFAULT '',
			vsid int(10) UNSIGNED DEFAULT NULL,
			value_num float DEFAULT NULL,
			KEY entity (entity_type, entity_id),
			KEY path (path(18)),
  		KEY alias (alias(18)),
  		KEY attr_key (attr_key(4)),
  		KEY vsid (vsid)
		) $charset_collate;";

	dbDelta( $sql );

	$table_name = $wpdb->prefix . "intel_value_str";

	$sql = "CREATE TABLE $table_name (
			vsid int(10) UNSIGNED NOT NULL,
			value_str varchar(255) NOT NULL,
			PRIMARY KEY (vsid),
      KEY value_str (value_str(16))
		) $charset_collate;";

	dbDelta( $sql );
}

function intel_update_1002() {
	$vars = array(
		'page_intent' => '',
		'track_page_terms' => 1,
		'page_attribute' => array(
			'key' => 'b',
			'title' => Intel_Df::t('Tag'),
			'title_plural' => Intel_Df::t('Tags'),
		),
		'visitor_attribute' => array(
		),
	);
	update_option('intel_entity_settings_taxonomy__post_tag', $vars);

	$vars['page_attribute']['key'] = 'c';
	$vars['page_attribute']['title'] = Intel_Df::t('Category');
	$vars['page_attribute']['title_plural'] = Intel_Df::t('Categories');
	update_option('intel_entity_settings_taxonomy__category', $vars);

	$vars['page_attribute']['key'] = 'd';
	$vars['page_attribute']['title'] = Intel_Df::t('Post format');
	$vars['page_attribute']['title_plural'] = Intel_Df::t('Post formats');
	update_option('intel_entity_settings_taxonomy__post_format', $vars);
}

function intel_update_1003() {
	// restructure ga_profile
	$ga_profile = get_option('intel_ga_profile', array());
	$trans = array(
		'viewId' => 'id',
		'viewName' => 'name',
		'internalPropertyId' => 'internalWebPropertyId',
	);
	foreach ($trans as $k => $kk) {
		if (!empty($ga_profile[$k])) {
			$ga_profile[$kk] = $ga_profile[$k];
			unset($ga_profile[$k]);
		}
	}
	update_option('intel_ga_profile', $ga_profile);

	// move intel_setup wizard state info from system_meta
	$sys_meta = get_option('intel_system_meta', array());
	$wizard_state = get_option('intel_wizard_intel_setup_state', array());
	$trans = array(
		'setup_successes' => 'success',
		'setup_complete' => 'completed',
		'setup_step' => 'step',
	);
	foreach ($sys_meta as $k => $v) {
		if (substr($k, 0, 6) == 'setup_') {
			if (!empty($trans[$k])) {
				$wizard_state[$trans[$k]] = $v;
			}
			else {
				$wizard_state[$k] = $v;
			}
			unset($sys_meta[$k]);
		}
	}
	$sys_meta = update_option('intel_system_meta', $sys_meta);
	$wizard_state = update_option('intel_wizard_intel_setup_state', $wizard_state);
}

/**
 * Migrate default form tracking options
 */
function intel_update_1004() {
	$value = get_option('intel_form_submission_tracking_event_name_default', -99);
	if ($value != -99) {
		$value = update_option('intel_form_track_submission_default', $value);
		delete_option('intel_form_submission_tracking_event_name_default');
	}
	$value = get_option('intel_form_submission_tracking_event_value_default', -99);
	if ($value != -99) {
		$value = update_option('intel_form_track_submission_value_default', $value);
		delete_option('intel_form_submission_tracking_event_value_default');
	}
}

/**
 * Adding annotation table
 */
function intel_update_1005() {
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . "intel_annotation";

  $sql = "CREATE TABLE $table_name (
    aid int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    created int(10) UNSIGNED NOT NULL DEFAULT '0',
    updated int(10) UNSIGNED NOT NULL DEFAULT '0',
    timestamp int(10) UNSIGNED NOT NULL DEFAULT '0',
    type varchar(128) NOT NULL DEFAULT '',
    message longtext NOT NULL,
    variables longtext NOT NULL,
    data longtext NOT NULL,
    PRIMARY KEY (aid),
    KEY timestamp (timestamp),
    KEY type (type)
    ) $charset_collate;";

	dbDelta( $sql );
}