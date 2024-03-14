<?php
/**
 * Translations for WP Portfolio tinymce plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( '_WP_Editors' ) ) {
    require( ABSPATH . WPINC . '/class-wp-editor.php' );
}


$strings = 'tinyMCE.addI18n(
    "' . _WP_Editors::$mce_locale . '.wpptinymce",
	{
		wp_portfolio: "' . esc_js( __( 'WP Portfolio', 'wp-portfolio' ) ) . '",
		what_would_you_like: "' . esc_js( __( 'What would you like?', 'wp-portfolio' ) ) . '",
		portfolio: "' . esc_js( __( 'Portfolio', 'wp-portfolio' ) ) . '",
		single_websites: "' . esc_js( __( 'Single websites', 'wp-portfolio' ) ) . '",
		group_list: "' . esc_js( __( 'Group List', 'wp-portfolio' ) ) . '",
		groups_ids: "' . esc_js( __( 'Groups ids', 'wp-portfolio' ) ) . '",
		groups_ids_separated_by: "' . esc_js( __( 'Groups ids separated by \',\'', 'wp-portfolio' ) ) . '",
		hide_group_info: "' . esc_js( __( 'Hide group info?', 'wp-portfolio' ) ) . '",
		no: "' . esc_js( __( 'No', 'wp-portfolio' ) ) . '",
		yes: "' . esc_js( __( 'Yes', 'wp-portfolio' ) ) . '",
		order_by: "' . esc_js( __( 'Order by', 'wp-portfolio' ) ) . '",
		site_order: "' . esc_js( __( 'Site order', 'wp-portfolio' ) ) . '",
		date_added: "' . esc_js( __( 'Date added', 'wp-portfolio' ) ) . '",
		site_name: "' . esc_js( __( 'Site name', 'wp-portfolio' ) ) . '",
		site_description: "' . esc_js( __( 'Site description', 'wp-portfolio' ) ) . '",
		random: "' . esc_js( __( 'Random', 'wp-portfolio' ) ) . '",
		order: "' . esc_js( __( 'Order', 'wp-portfolio' ) ) . '",
		asc: "' . esc_js( __( 'ASC', 'wp-portfolio' ) ) . '",
		desc: "' . esc_js( __( 'DESC', 'wp-portfolio' ) ) . '",
		columns: "' . esc_js( __( 'Columns', 'wp-portfolio' ) ) . '",
		sites_per_page: "' . esc_js( __( 'Sites per page', 'wp-portfolio' ) ) . '",
		number_of_sites_on_the_page: "' . esc_js( __( 'Number of sites on the page', 'wp-portfolio' ) ) . '",
		websites_ids: "' . esc_js( __( 'Websites ids', 'wp-portfolio' ) ) . '",
		websites_ids_separated_by: "' . esc_js( __( 'Websites ids separated by \',\'', 'wp-portfolio' ) ) . '",
		fill_space: "' . esc_js( __( 'Fill space responsively', 'wp-portfolio' ) ) . '",
		default_behaviour: "' . esc_js( __( 'Default behaviour', 'wp-portfolio' ) ) . '",
		default_filter: "' . esc_js( __( 'Default filter', 'wp-portfolio' ) ) . '",
		default_filter_group_id: "' . esc_js( __( 'Default filter group ID', 'wp-portfolio' ) ) . '"
	}
)';