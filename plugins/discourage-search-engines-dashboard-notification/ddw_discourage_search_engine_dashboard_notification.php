<?php
/**
 * Plugin Name: Discourage Search Engines - Dashboard Notification
 * Version: 1.6
 * Author: Dirk De Wever
 * Author URI: https://hoolite.be
 * Description: Show a dashboard notification to notify when 'Discourage Search Engines' is enabled in the settings.
 * Text Domain: discourage-search-engines-dashboard-notification
 *
 * @package DiscourageSEDashboardNotification
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Display dashboard notifications based on 'Discourage Search Engines' setting.
function ddw_discourage_search_engine_dashboard_notification() {
	if ( ! current_user_can( 'administrator' ) ) {
		return;
	}

	global $current_user;
	$user_id = $current_user->ID;

	$dismissed = get_user_meta( $user_id, 'GoodNewsMessage_ignore', true );

	if ( 1 === (int) get_option( 'blog_public' ) ) {
		if ( ! $dismissed ) {
			$notification_good_news_btn_title = __( 'No, wait a minute! I\'m not ready with developing the website. I\'ll let Google index my site later and I want to block the robots for now.', 'discourage-search-engines-dashboard-notification' );
			$notification_good_news_btn_txt   = __( 'No! Block the bots', 'discourage-search-engines-dashboard-notification' );

			echo '<div class="notice notice-success" style="display: flex; justify-content: space-between; align-items: center;">';
			echo '<p>' . esc_html__( 'Good news! Your website is being indexed by the Google bots.', 'discourage-search-engines-dashboard-notification' ) . '</p>';
			echo '<div style="display: flex; gap: 10px;">';
			echo '<a class="button button-primary" title="' . esc_attr( $notification_good_news_btn_title ) . '" href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">' . esc_html( $notification_good_news_btn_txt ) . '</a>';
			echo '<a class="button button-secondary" href="' . esc_url( add_query_arg( 'dismiss-goodnews', 'true' ) ) . '">' . esc_html__( 'Dismiss Notice', 'discourage-search-engines-dashboard-notification' ) . '</a>';
			echo '</div>';
			echo '</div>';
		}
	} else {
		if ( ! $dismissed ) {
			$notification_btn_title = __( 'Yes, I\'m done editing and I want to let the search engines to index this website! 🤖', 'discourage-search-engines-dashboard-notification' );
			$notification_btn_txt   = __( 'Release the bots', 'discourage-search-engines-dashboard-notification' );

			echo '<div class="notice notice-info" style="display: flex; justify-content: space-between; align-items: center;">';
			echo '<p>' . esc_html__( 'Don\'t forget, search engines are still discouraged to index this website.', 'discourage-search-engines-dashboard-notification' ) . '</p>';
			echo '<div style="display: flex; gap: 10px;">';
			echo '<a class="button button-primary" title="' . esc_attr( $notification_btn_title ) . '" href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">' . esc_html( $notification_btn_txt ) . '</a>';
			echo '</div>';
			echo '</div>';
		}
	}
}
add_action( 'admin_notices', 'ddw_discourage_search_engine_dashboard_notification' );

// Add settings link to plugin actions.
function ddw_discourage_search_engine_dashboard_notification_settings_link( $links ) {
	$settings_txt  = __( 'Settings', 'discourage-search-engines-dashboard-notification' );
	$settings_link = '<a href="' . esc_url( admin_url( 'options-reading.php' ) ) . '">' . esc_html( $settings_txt ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ddw_discourage_search_engine_dashboard_notification_settings_link' );

// Handle dismissal of the notice.
function ddw_handle_dismiss_notice() {
	if ( isset( $_GET['dismiss-goodnews'] ) && 'true' === $_GET['dismiss-goodnews'] ) {
		global $current_user;
		$user_id = $current_user->ID;
		add_user_meta( $user_id, 'GoodNewsMessage_ignore', 'true', true );
		wp_redirect( remove_query_arg( 'dismiss-goodnews' ) );
		exit();
	}
}
add_action( 'admin_init', 'ddw_handle_dismiss_notice' );

// Delete user meta when switching blog_public option.
function ddw_delete_user_meta_on_option_change( $old_value, $new_value ) {
	if ( $old_value != $new_value ) {
		global $current_user;
		$user_id = $current_user->ID;
		delete_user_meta( $user_id, 'GoodNewsMessage_ignore' );
	}
}
add_action( 'update_option_blog_public', 'ddw_delete_user_meta_on_option_change', 10, 2 );
