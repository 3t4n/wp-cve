<?php // Uninstall

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

delete_option('contactformx_email');
delete_option('contactformx_form');
delete_option('contactformx_customize');
delete_option('contactformx_appearance');
delete_option('contactformx_advanced');

delete_option('contactformx_init');

$cfx_email_posts = get_posts(array('post_type' => 'cfx_email', 'post_status' => 'any', 'posts_per_page' => -1));
if ($cfx_email_posts) { foreach ($cfx_email_posts as $cfx_email_post) wp_delete_post($cfx_email_post->ID, false); }

/* legacy */
global $wpdb;
$cfx_email = $wpdb->prefix .'cfx_email';
$wpdb->query("DROP TABLE IF EXISTS {$cfx_email}");