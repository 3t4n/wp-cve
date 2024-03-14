<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// View All Pages.
$wp_admin_bar->add_node(
	array(
		'id'     => 'bn-pages-view-all',
		'title'  => __( 'View All Pages', 'bricks-navigator' ),
		'parent' => 'bn-pages',
		'href'   => admin_url( 'edit.php?post_type=page' ),
		'meta'   => array(
			'class' => 'kn-view-all kn-parent-of-mini-child',
		),
	)
);

global $wpdb;

$query = "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type = 'page' AND post_status='publish' ORDER BY $wpdb->posts.post_title ASC";

$results = $wpdb->get_results( $query );

foreach ( $results as $p ) {
	$wp_admin_bar->add_node(
		array(
			'id'     => 'kn-page-' . $p->ID,
			'title'  => $p->post_title,
			'parent' => 'bn-pages',
			'href'   => esc_url( get_edit_post_link( $p ) ),
			'meta'   => array(
				'title' => __( 'Edit this Page', 'bricks-navigator' ),
				'class' => 'kn-parent-of-mini-child',
			),
		)
	);

		// View Page links.
		$wp_admin_bar->add_node(
			array(
				'id'     => 'kn-page-sub-' . $p->ID,
				'title'  => 'View: ' . $p->post_title,
				'parent' => 'kn-page-' . $p->ID,
				'href'   => esc_url( get_permalink( $p->ID ) ),
				'meta'   => array(
					'title'  => __( 'View: ', 'bricks-navigator' ) . $p->post_title,
					'class'  => 'kn-mini-child kn-mini-child-view',
				),
			)
		);
}