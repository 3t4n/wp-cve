<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

$query = "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' ORDER BY post_title ASC";

$results = $wpdb->get_results( $query );

foreach ( $results as $p ) {
    // Ref.: L1406 in /wp-content/themes/bricks/includes/admin.php of Bricks 1.4.
	$edit_url = \Bricks\Helpers::get_builder_edit_link( $p->ID );

	$wp_admin_bar->add_node(
		array(
			'id'     => 'bricks-page' . $p->ID,
			'title'  => $p->post_title,
			'parent' => 'bn-bricks-pages',
			'href'   => esc_url( $edit_url ),
			'meta'   => array(
				'title' => __( 'Edit this Page with Bricks' ),
				'class' => 'bn-parent-of-mini-child',
			),
		)
	);

	// Links to open in a new tab.
	$wp_admin_bar->add_node(
		array(
			'id'     => 'bricks-page-new-tab' . $p->ID,
			'title'  => $p->post_title,
			'parent' => 'bricks-page' . $p->ID,
			'href'   => esc_url( $edit_url ),
			'meta'   => array(
				'target' => '_blank',
				'title'  => __( 'Edit this Page with Bricks in a new tab' ),
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);
} // End foreach().