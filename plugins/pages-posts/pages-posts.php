<?php
/*
Plugin Name: Pages Posts
Plugin URI: http://redyellow.co.uk/plugins/pages-posts/
Description: Amend pages and put posts inside them - either by category or tag - <a href="options-general.php?page=pagesposts.php">Settings</a>
Author: Rich Gubby
Version: 2.1
Author URI: http://redyellow.co.uk/
*/

require_once('functions.php');

if(is_admin())
{
	// Load menu page
	add_action('admin_menu', 'pagesPostsAddAdminPage');
	// Load admin CSS style sheet
	add_action('admin_head', 'pages_posts_register_head');
} else
{
	// Initialize Pages Posts plugin when viewing a page
	add_action('template_redirect', 'pagesPostsInit');
}