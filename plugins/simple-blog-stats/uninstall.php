<?php // uninstall remove options

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit();

// delete options
delete_option('sbs_options');

// delete transients
delete_transient('sbs_word_count');
delete_transient('sbs_post_count');
delete_transient('sbs_page_count');
delete_transient('sbs_draft_count');
delete_transient('sbs_user_count');
delete_transient('sbs_comments_approved_count');
delete_transient('sbs_comments_moderated_count');
delete_transient('sbs_comments_total_count');