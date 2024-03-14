<?php
/*
Plugin Name: bbPress Voting
Plugin URI: https://wordpress.org/plugins/bbp-voting/
Author: WP For The Win
Author URI: https://wpforthewin.com
Description: Let users vote up or down on bbPress topics and replies just like Reddit or Stack Overflow.
Text Domain: bbp-voting
Version: 2.1.12.0
Requires at least: 4.0.0
Tested up to: 6.4.2
License: GPLv3
*/

if(!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

// Let the pro plugin know we're active
define('BBPVOTING', true);
// define('BBPVOTINGDEBUG', true);

// The plugin basename, "folder/file.php"
$plugin = plugin_basename(__FILE__);

// Setup all the hooks for getting setting values
global $bbp_voting_hooks;
$bbp_voting_hooks = array(
    'bbp_voting_show_labels' => 'bool',
    'bbp_voting_helpful' => 'string',
    'bbp_voting_not_helpful' => 'string',
    'bbp_voting_display_vote_nums' => 'select',
    'bbp_voting_only_topics' => 'bool',
    'bbp_voting_only_replies' => 'bool',
    'bbp_voting_disable_voting_for_visitors' => 'bool',
    'bbp_voting_disable_voting_on_closed_topic' => 'bool',
    'bbp_voting_disable_down_votes' => 'bool',
    'bbp_voting_disable_author_vote' => 'bool',
    'bbp_voting_admin_bypass' => 'bool',
    'sort_bbpress_topics_by_votes' => 'bool',
    'sort_bbpress_replies_by_votes' => 'bool',
    'bbp_voting_use_filter_hooks_for_buttons' => 'bool',
    'bbp_voting_lead_topic' => 'bool',
);
$bbp_voting_hooks = isset($bbp_voting_pro_hooks) ? array_merge($bbp_voting_hooks, $bbp_voting_pro_hooks) : $bbp_voting_hooks;

// Helpers are helpful
require_once plugin_dir_path( __FILE__ ) . 'helpers.php';

foreach($bbp_voting_hooks as $bbp_voting_hook => $bbp_voting_hook_type) {
    add_filter( $bbp_voting_hook, 'bbp_voting_hook_setting');
}

// Require only the appropriate files
if(wp_doing_ajax()) {
	// Ajax
    require_once plugin_dir_path( __FILE__ ) . 'ajax.php';
} elseif(is_admin()) {
	// Backend
    require_once plugin_dir_path( __FILE__ ) . 'backend.php';
    require_once plugin_dir_path( __FILE__ ) . 'metabox.php';
} else {
	// Frontend
    require_once plugin_dir_path( __FILE__ ) . 'frontend.php';
}
