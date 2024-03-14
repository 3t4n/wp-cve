<?php
/*
 * Plugin Name: myCred - Learndash
 * Description: myCred Learndash is a plugin for WordPress that enabled you to build points based on Learndash plugin.
 * Version:     2.1.1
 * Requires at least: 4.8
 * Tested up to: 6.3.2
 * Author: 		myCRED
 * Author URI:  https://mycred.me
 * Text Domain: mycred-learndash
 */


define('MYCRED_LEARNDASH',  	   __FILE__ );
define('MYCRED_LEARNDASH_URL', 	   plugin_dir_url( MYCRED_LEARNDASH ));
define('MYCRED_LEARNDASH_PATH',    plugin_dir_path( MYCRED_LEARNDASH ));
define('MYCRED_LEARNDASH_SLUG',    'mycred-learndash');
define('MYCRED_LEARNDASH_VERSION', '2.1.1');


/**
 * Including files of myCred Learndash hook
 */

require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-complete-topic-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-complete-lesson-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-group-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-completing-course-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-course-enrollment-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-completing-quiz-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-completing-quiz-max-percentage-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-completing-quiz-percentage-range-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-quiz-failed-hook.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-buy-pts-handler.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/admin/settings/class-mycred-learndash-settings.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/admin/settings/class-mycred-learndash-course-settings.php';
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-leaderboard.php';
new MyCred_LearnDash_Settings();

/**
 * Add points specific for course//quiz
 */
require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash.php';
new myCRED_Learndash();

add_action('init', 'mycred_ld_load_textdomain');

if ( !function_exists('mycred_ld_load_textdomain') )
{
    function mycred_ld_load_textdomain()
    {
        load_plugin_textdomain('mycred-learndash', false, 'mycred-learndash/languages' );
    }
}

add_action('init', 'mycred_learndash_badge');

function mycred_learndash_badge() {
    if (class_exists('myCRED_Badge')) {
        require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-badges.php';
        new myCRED_Learndash_Badges();
    }
}

add_action('wp', 'mycred_learndash_leaderboard_hook');

function mycred_learndash_leaderboard_hook() {

    if (class_exists('myCRED_Query_Log')) {
        require_once MYCRED_LEARNDASH_PATH . 'inc/mycred-learndash-lb-shortcode.php';
        require_once MYCRED_LEARNDASH_PATH . 'inc/class-mycred-learndash-lb-functions.php';

        
    }
}

add_action('wp_enqueue_scripts', 'mycred_learndash_lb_scripts');

function mycred_learndash_lb_scripts() {
    wp_enqueue_style('leaderboard-style', plugin_dir_url(__FILE__) . 'inc/assets/css/mycred-leaderboard-style.css');

   
}







