<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$strings = 'tinyMCE.addI18n({' . _WP_Editors::$mce_locale . ': {
	bbpress_shortcodes: {
		shortcode_title: "' . esc_js( __( 'bbPress', 'bbpress-shortcodes' ) ) . '",
		forums: "' . esc_js( __( 'Forums', 'bbpress-shortcodes' ) ) . '",
		forum_index: "' . esc_js( __( 'Forum Index', 'bbpress-shortcodes' ) ) . '",
		forum_form: "' . esc_js( __( 'New Forum Form', 'bbpress-shortcodes' ) ) . '",
		single_forum: "' . esc_js( __( 'Single Forum', 'bbpress-shortcodes' ) ) . '",
		forum_id: "' . esc_js( __( 'Forum ID', 'bbpress-shortcodes' ) ) . '",
		topic_id: "' . esc_js( __( 'Topic ID', 'bbpress-shortcodes' ) ) . '",
		reply_id: "' . esc_js( __( 'Reply ID', 'bbpress-shortcodes' ) ) . '",
		tag_id: "' . esc_js( __( 'Tag ID', 'bbpress-shortcodes' ) ) . '",
		need_id: "' . esc_js( __( 'You need to use an ID!', 'bbpress-shortcodes' ) ) . '",
		topics: "' . esc_js( __( 'Topics', 'bbpress-shortcodes' ) ) . '",
		topic_index: "' . esc_js( __( 'Topic Index', 'bbpress-shortcodes' ) ) . '",
		topic_form: "' . esc_js( __( 'New Topic Form', 'bbpress-shortcodes' ) ) . '",
		forum_topic_form: "' . esc_js( __( 'Specific Forum New Topic Form', 'bbpress-shortcodes' ) ) . '",
		single_topic: "' . esc_js( __( 'Single Topic', 'bbpress-shortcodes' ) ) . '",
		replies: "' . esc_js( __( 'Replies', 'bbpress-shortcodes' ) ) . '",
		reply_form: "' . esc_js( __( 'New Reply Form', 'bbpress-shortcodes' ) ) . '",
		single_reply: "' . esc_js( __( 'Single Reply', 'bbpress-shortcodes' ) ) . '",
		topic_tags: "' . esc_js( __( 'Topic Tags', 'bbpress-shortcodes' ) ) . '",
		display_topic_tags: "' . esc_js( __( 'Display Topic Tags', 'bbpress-shortcodes' ) ) . '",
		single_tag: "' . esc_js( __( 'Single Tag', 'bbpress-shortcodes' ) ) . '",
		views: "' . esc_js( __( 'Views', 'bbpress-shortcodes' ) ) . '",
		popular: "' . esc_js( __( 'Popular', 'bbpress-shortcodes' ) ) . '",
		no_replies: "' . esc_js( __( 'No Replies', 'bbpress-shortcodes' ) ) . '",
		search: "' . esc_js( __( 'Search', 'bbpress-shortcodes' ) ) . '",
		search_input: "' . esc_js( __( 'Search Input Form', 'bbpress-shortcodes' ) ) . '",
		search_form: "' . esc_js( __( 'Search Form Template', 'bbpress-shortcodes' ) ) . '",
		account: "' . esc_js( __( 'Account', 'bbpress-shortcodes' ) ) . '",
		login: "' . esc_js( __( 'Login', 'bbpress-shortcodes' ) ) . '",
		register: "' . esc_js( __( 'Register', 'bbpress-shortcodes' ) ) . '",
		lost_pass: "' . esc_js( __( 'Lost Password', 'bbpress-shortcodes' ) ) . '",
		statistics: "' . esc_js( __( 'Statistics', 'bbpress-shortcodes' ) ) . '"
	}
}});';
