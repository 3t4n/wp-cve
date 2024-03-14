<?php
/**
 * Handle custom post type registration
 *
 * @package UpStream
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Registers and sets up the Downloads custom post type
 *
 * @return void
 * @since 1.0
 */
function upstream_setup_post_types() {
	up_debug();

	$project_base = upstream_get_project_base();
	$client_base  = upstream_get_client_base();

	$project_labels = apply_filters(
		'upstream_project_labels',
		array(
			'name'                  => '%2$s',
			'singular_name'         => '%1$s',
			// translators: %1s: project singular name.
			'add_new'               => __( 'New %1s', 'upstream' ),
			// translators: %1s: project singular name.
			'add_new_item'          => __( 'Add New %1$s', 'upstream' ),
			// translators: %1s: project singular name.
			'edit_item'             => __( 'Edit %1$s', 'upstream' ),
			// translators: %1s: project singular name.
			'new_item'              => __( 'New %1$s', 'upstream' ),
			'all_items'             => '%2$s',
			// translators: %1s: project singular name.
			'view_item'             => __( 'View %1$s', 'upstream' ),
			// translators: %2$s: project plural name.
			'search_items'          => __( 'Search %2$s', 'upstream' ),
			// translators: %2$s: project plural name.
			'not_found'             => __( 'No %2$s found', 'upstream' ),
			// translators: %2$s: project plural name.
			'not_found_in_trash'    => __( 'No %2$s found in Trash', 'upstream' ),
			'parent_item_colon'     => '',
			'menu_name'             => '%2$s',
			// translators: %1s: project singular name.
			'featured_image'        => __( '%1$s Image', 'upstream' ),
			// translators: %1s: project singular name.
			'set_featured_image'    => __( 'Set %1$s Image', 'upstream' ),
			// translators: %1s: project singular name.
			'remove_featured_image' => __( 'Remove %1$s Image', 'upstream' ),
			// translators: %1s: project singular name.
			'use_featured_image'    => __( 'Use as %1$s Image', 'upstream' ),
			// translators: %2$s: project plural name.
			'filter_items_list'     => __( 'Filter %2$s list', 'upstream' ),
			// translators: %2$s: project plural name.
			'items_list_navigation' => __( '%2$s list navigation', 'upstream' ),
			// translators: %2$s: project plural name.
			'items_list'            => __( '%2$s list', 'upstream' ),
		)
	);

	foreach ( $project_labels as $key => $value ) {
		$project_labels[ $key ] = sprintf( $value, upstream_project_label(), upstream_project_label_plural() );
	}

	$project_args = array(
		'labels'             => $project_labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-arrow-up-alt',
		'menu_position'      => is_plugin_active( 'woocommerce-product-vendors/woocommerce-product-vendors.php' ) ? 993 : 56,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => $project_base,
			'with_front' => false,
		),
		'capability_type'    => 'project',
		'map_meta_cap'       => true,
		'has_archive'        => $project_base,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'upstream_project_supports', array( 'title', 'revisions', 'author' ) ),
	);
	register_post_type( 'project', apply_filters( 'upstream_project_post_type_args', $project_args ) );

	if ( upstream_is_clients_disabled() ) {
		return;
	}

	/* Client Post Type */
	$client_labels = apply_filters(
		'upstream_client_labels',
		array(
			'name'                  => '%2$s',
			'singular_name'         => '%1$s',
			// translators: %1s: client singular name.
			'add_new'               => __( 'New %1s', 'upstream' ),
			// translators: %1s: client singular name.
			'add_new_item'          => __( 'Add New %1$s', 'upstream' ),
			// translators: %1s: client singular name.
			'edit_item'             => __( 'Edit %1$s', 'upstream' ),
			// translators: %1s: client singular name.
			'new_item'              => __( 'New %1$s', 'upstream' ),
			'all_items'             => '%2$s',
			// translators: %1s: client singular name.
			'view_item'             => __( 'View %1$s', 'upstream' ),
			// translators: %2s: client singular name.
			'search_items'          => __( 'Search %2$s', 'upstream' ),
			// translators: %2s: client singular name.
			'not_found'             => __( 'No %2$s found', 'upstream' ),
			// translators: %2s: client singular name.
			'not_found_in_trash'    => __( 'No %2$s found in Trash', 'upstream' ),
			'parent_item_colon'     => '',
			'menu_name'             => '%2$s',
			// translators: %1s: client singular name.
			'featured_image'        => __( '%1$s Image', 'upstream' ),
			// translators: %1s: client singular name.
			'set_featured_image'    => __( 'Set %1$s Image', 'upstream' ),
			// translators: %1s: client singular name.
			'remove_featured_image' => __( 'Remove %1$s Image', 'upstream' ),
			// translators: %1s: client singular name.
			'use_featured_image'    => __( 'Use as %1$s Image', 'upstream' ),
			// translators: %2s: client singular name.
			'filter_items_list'     => __( 'Filter %2$s list', 'upstream' ),
			// translators: %2s: client singular name.
			'items_list_navigation' => __( '%2$s list navigation', 'upstream' ),
			// translators: %2s: client singular name.
			'items_list'            => __( '%2$s list', 'upstream' ),
		)
	);

	foreach ( $client_labels as $key => $value ) {
		$client_labels[ $key ] = sprintf( $value, upstream_client_label(), upstream_client_label_plural() );
	}

	$client_args = array(
		'labels'             => $client_labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => true,
		'rewrite'            => array(
			'slug'       => $client_base,
			'with_front' => false,
		),
		'capability_type'    => 'client',
		'map_meta_cap'       => true,
		'has_archive'        => false,
		'hierarchical'       => false,
		'supports'           => apply_filters( 'upstream_client_supports', array( 'title', 'revisions' ) ),
	);
	register_post_type( 'client', apply_filters( 'upstream_client_post_type_args', $client_args ) );

	\UpStream\Milestones::getInstance()->create_post_type();
}

add_action( 'init', 'upstream_setup_post_types', 1 );

/**
 * Registers the custom taxonomies for the projects custom post type
 *
 * @return void
 * @since 1.0
 */
function upstream_setup_taxonomies() {
	if ( ! upstream_is_project_categorization_disabled() ) {

		$slug = defined( 'UPSTREAM_CAT_SLUG' ) ? UPSTREAM_CAT_SLUG : 'projects';

		/** Categories */
		$category_labels = array(
			'name'              => _x( 'Category', 'taxonomy general name', 'upstream' ),
			'singular_name'     => _x( 'Category', 'taxonomy singular name', 'upstream' ),
			'search_items'      => sprintf(
				// translators: %s: upstream project label.
				__( 'Search %s Categories', 'upstream' ),
				upstream_project_label()
			),
			'all_items'         => sprintf(
				// translators: %s: upstream project label.
				__( 'All %s Categories', 'upstream' ),
				upstream_project_label()
			),
			'parent_item'       => sprintf(
				// translators: %s: upstream project label.
				__( 'Parent %s Category', 'upstream' ),
				upstream_project_label()
			),
			'parent_item_colon' => sprintf(
				// translators: %s: upstream project label.
				__( 'Parent %s Category:', 'upstream' ),
				upstream_project_label()
			),
			'edit_item'         => sprintf(
				// translators: %s: upstream project label.
				__( 'Edit %s Category', 'upstream' ),
				upstream_project_label()
			),
			'update_item'       => sprintf(
				// translators: %s: upstream project label.
				__( 'Update %s Category', 'upstream' ),
				upstream_project_label()
			),
			'add_new_item'      => sprintf(
				// translators: %s: upstream project label.
				__( 'Add New %s Category', 'upstream' ),
				upstream_project_label()
			),
			'new_item_name'     => sprintf(
				// translators: %s: upstream project label.
				__( 'New %s Category Name', 'upstream' ),
				upstream_project_label()
			),
			'menu_name'         => __( 'Categories', 'upstream' ),
		);

		$category_args = apply_filters(
			'upstream_project_category_args',
			array(
				'hierarchical'      => true,
				'labels'            => apply_filters( '_upstream_project_category_labels', $category_labels ),
				'show_ui'           => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'query_var'         => 'project_category',
				'rewrite'           => array(
					'slug'         => $slug . '/category',
					'with_front'   => false,
					'hierarchical' => true,
				),
				'capabilities'      => array(
					'manage_terms' => 'manage_project_terms',
					'edit_terms'   => 'edit_project_terms',
					'assign_terms' => 'assign_project_terms',
					'delete_terms' => 'delete_project_terms',
				),
			)
		);

		register_taxonomy( 'project_category', array( 'project' ), $category_args );
		register_taxonomy_for_object_type( 'project_category', 'project' );

		/** Tags */
		$tags_labels = array(
			'name'                       => _x( 'Tags', 'taxonomy (tag) general name', 'upstream' ),
			'singular_name'              => _x( 'Tag', 'taxonomy (tag) singular name', 'upstream' ),
			'search_items'               => __( 'Search Tags', 'upstream' ),
			'popular_items'              => __( 'Popular Tags' ),
			'all_items'                  => __( 'All Tags', 'upstream' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Tag', 'upstream' ),
			'update_item'                => __( 'Update Tag', 'upstream' ),
			'add_new_item'               => __( 'Add New Tag', 'upstream' ),
			'new_item_name'              => __( 'New Tag Name', 'upstream' ),
			'add_or_remove_items'        => __( 'Add or remove tags' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'choose_from_most_used'      => __( 'Choose from the most used tags' ),
			'menu_name'                  => __( 'Tags', 'upstream' ),
		);

		$args = array(
			'hierarchical'      => false,
			'labels'            => apply_filters( '_upstream_project_tags_labels', $tags_labels ),
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => 'upstream_tag',
			'rewrite'           => array(
				'slug'         => 'upstream/tag',
				'with_front'   => false,
				'hierarchical' => false,
			),
			'capabilities'      => array(
				'manage_terms' => 'manage_project_terms',
				'edit_terms'   => 'edit_project_terms',
				'assign_terms' => 'assign_project_terms',
				'delete_terms' => 'delete_project_terms',
			),
		);

		register_taxonomy( 'upstream_tag', array( 'project' ), $args );
		register_taxonomy_for_object_type( 'upstream_tag', 'project' );

	}

	/** Milestone Categories */
	$tags_labels = array(
		'name'                       => upstream_milestone_category_label_plural(),
		'singular_name'              => upstream_milestone_category_label(),
		'search_items'               => sprintf(
			// translators: %s: upstream_milestone_category_label_plural.
			__( 'Search %s', 'upstream' ),
			upstream_milestone_category_label_plural()
		),
		'popular_items'              => sprintf(
			// translators: %s: upstream_milestone_category_label_plural.
			__( 'Popular %s' ),
			upstream_milestone_category_label_plural()
		),
		'all_items'                  => sprintf(
			// translators: %s: upstream_milestone_category_label_plural.
			__( 'All %s', 'upstream' ),
			upstream_milestone_category_label_plural()
		),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'Edit %s', 'upstream' ),
			upstream_milestone_category_label()
		),
		'update_item'                => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'Update %s', 'upstream' ),
			upstream_milestone_category_label()
		),
		'add_new_item'               => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'Add New %s', 'upstream' ),
			upstream_milestone_category_label()
		),
		'new_item_name'              => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'New %s Name', 'upstream' ),
			upstream_milestone_category_label()
		),
		'add_or_remove_items'        => sprintf(
			// translators: %s: upstream_milestone_category_label_plural.
			__( 'Add or remove %s' ),
			upstream_milestone_category_label_plural()
		),
		'separate_items_with_commas' => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'Separate %s with commas' ),
			upstream_milestone_category_label()
		),
		'choose_from_most_used'      => sprintf(
			// translators: %s: upstream_milestone_category_label.
			__( 'Choose from the most used %s' ),
			upstream_milestone_category_label()
		),
		'menu_name'                  => upstream_milestone_category_label(),
	);

	if ( ! upstream_disable_milestone_categories() ) {
		$args = array(
			'hierarchical'      => true,
			'labels'            => apply_filters( '_upstream_milestone_categories_labels', $tags_labels ),
			'show_ui'           => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'query_var'         => 'upstream_milestone_category',
			'rewrite'           => array(
				'slug'         => 'upstream/milestone_category',
				'with_front'   => false,
				'hierarchical' => false,
			),
			'capabilities'      => array(
				'manage_terms' => 'manage_project_terms',
				'edit_terms'   => 'edit_project_terms',
				'assign_terms' => 'assign_project_terms',
				'delete_terms' => 'delete_project_terms',
			),
		);

		register_taxonomy( 'upst_milestone_category', array( 'upst_milestone' ), $args );
		register_taxonomy_for_object_type( 'upst_milestone_category', 'upst_milestone' );
	}
}

add_action( 'init', 'upstream_setup_taxonomies', 1 );

/**
 * Milestone taxonomies custom fields.
 *
 * @param WP_Tax $taxonomy Taxonomy object.
 */
function upstream_milestone_category_form_fields( $taxonomy ) {
	$value = '';
	if ( is_object( $taxonomy ) ) {
		$value = get_term_meta( $taxonomy->term_id, 'color', true );
	}

	wp_nonce_field( 'upstream_admin_milestone_category_form', 'upstream_admin_milestone_category_form_nonce' );

	?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_color"><?php esc_html_e( 'Default color', 'upstream' ); ?></label>
		</th>
		<td>
			<input type="text" name="color" class="color-field" id="term_color" value="<?php echo esc_attr( $value ); ?>" />
			<p class="description">Select a default color for milestones related to this category.</p>
		</td>
	</tr>
	<br>
	<?php
}

/**
 * Milestone taxonomies custom fields.
 *
 * @param int $term_id Term id.
 */
function upstream_save_milestone_category_form_fields( $term_id ) {
	$post_data = isset( $_POST ) ? wp_unslash( $_POST ) : array();
	$nonce     = isset( $post_data['upstream_admin_milestone_category_form_nonce'] ) ? $post_data['upstream_admin_milestone_category_form_nonce'] : null;

	if ( ! wp_verify_nonce( $nonce, 'upstream_admin_milestone_category_form' ) ) {
		return;
	}

	if ( isset( $post_data['color'] ) ) {
		update_term_meta( $term_id, 'color', sanitize_hex_color( $post_data['color'] ) );
	}
}

if ( ! upstream_is_project_categorization_disabled() ) {
	add_action( 'upst_milestone_category_add_form_fields', 'upstream_milestone_category_form_fields' );
	add_action( 'upst_milestone_category_edit_form_fields', 'upstream_milestone_category_form_fields' );
	add_action( 'edit_terms', 'upstream_save_milestone_category_form_fields' );
	add_action( 'create_term', 'upstream_save_milestone_category_form_fields' );
}
