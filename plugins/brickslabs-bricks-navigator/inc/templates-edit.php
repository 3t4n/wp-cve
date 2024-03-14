<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Link to Add New template page.
$wp_admin_bar->add_node(
    array(
        'id'     => 'bn-bricks-add-new-template',
        'title'  => __( 'Add New', 'bricks-navigator' ),
        'parent' => 'bn-bricks-templates',
        'href'   => admin_url( 'post-new.php?post_type=bricks_template' ),
        'meta'   => array(
            'class'  => 'bn-parent-of-mini-child bn-has-bottom-border',
        ),
    )
);
// Links to Bricks template page in a new tab.
$wp_admin_bar->add_node(
    array(
        'id'     => 'bn-bricks-add-new-template-new-tab',
        'parent' => 'bn-bricks-add-new-template',
        'href'   => admin_url( 'post-new.php?post_type=bricks_template' ),
        'meta'   => array(
            'title' => __( 'Add New template in a new tab', 'bricks-navigator' ),
            'target' => '_blank',
            'class'  => 'bn-mini-child bn-mini-child-new-tab',
        ),
    )
);

global $wpdb;

$query = "SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type = 'bricks_template' AND post_status='publish' ORDER BY post_title ASC";

$results = $wpdb->get_results( $query );

foreach ( $results as $p ) {
    // Ref.: L1406 in /wp-content/themes/bricks/includes/admin.php of Bricks 1.4.
	$edit_url = \Bricks\Helpers::get_builder_edit_link( $p->ID );

	$wp_admin_bar->add_node(
		array(
			'id'     => 'bricks-template' . $p->ID,
			'title'  => $p->post_title,
			'parent' => 'bn-bricks-templates',
			'href'   => esc_url( $edit_url ),
			'meta'   => array(
				'title' => __( 'Edit this Template with Bricks' ),
				'class' => 'bn-parent-of-mini-child',
			),
		)
	);

	// Links to open in a new tab.
	$wp_admin_bar->add_node(
		array(
			'id'     => 'bricks-template-new-tab' . $p->ID,
			'title'  => $p->post_title,
			'parent' => 'bricks-template' . $p->ID,
			'href'   => esc_url( $edit_url ),
			'meta'   => array(
				'target' => '_blank',
				'title'  => __( 'Edit this Template with Bricks in a new tab' ),
				'class'  => 'bn-mini-child bn-mini-child-new-tab',
			),
		)
	);
} // End foreach().