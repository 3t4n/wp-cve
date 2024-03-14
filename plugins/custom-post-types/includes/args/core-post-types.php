<?php

defined( 'ABSPATH' ) || exit;

$args = array();

$default_args = array(
	'public'              => false,
	'publicly_queryable'  => false,
	'show_ui'             => true,
	'show_in_menu'        => true,
	'show_in_rest'        => false,
	'query_var'           => false,
	'rewrite'             => false,
	'exclude_from_search' => true,
	'capabilities'        => array(
		'edit_post'          => 'update_core',
		'read_post'          => 'update_core',
		'delete_post'        => 'update_core',
		'edit_posts'         => 'update_core',
		'edit_others_posts'  => 'update_core',
		'delete_posts'       => 'update_core',
		'publish_posts'      => 'update_core',
		'read_private_posts' => 'update_core',
	),
	'has_archive'         => false,
	'hierarchical'        => false,
	'supports'            => array( '' ),
	'menu_icon'           => 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( CPT_PATH . 'assets/dashboard-icon.svg' ) ),
	'can_export'          => false,
);
// Create/edit new post type
$args[] = array(
	'id'       => CPT_UI_PREFIX,
	'singular' => __( 'Post type', 'custom-post-types' ),
	'plural'   => __( 'Post types', 'custom-post-types' ),
	'labels'   => array(
		'name'               => _x( 'Custom post types', 'Dashboard menu', 'custom-post-types' ),
		'singular_name'      => __( 'Post type', 'custom-post-types' ),
		'menu_name'          => __( 'Extend / Manage', 'custom-post-types' ),
		'name_admin_bar'     => __( 'Post type', 'custom-post-types' ),
		'add_new'            => __( 'Add post type', 'custom-post-types' ),
		'add_new_item'       => __( 'Add new post type', 'custom-post-types' ),
		'new_item'           => __( 'New post type', 'custom-post-types' ),
		'edit_item'          => __( 'Edit post type', 'custom-post-types' ),
		'view_item'          => __( 'View post type', 'custom-post-types' ),
		'item_updated'       => __( 'Post type updated', 'custom-post-types' ),
		'all_items'          => _x( 'Post types', 'Dashboard menu', 'custom-post-types' ),
		'search_items'       => __( 'Search post type', 'custom-post-types' ),
		'not_found'          => __( 'No post type available.', 'custom-post-types' ),
		'not_found_in_trash' => __( 'No post type in the trash.', 'custom-post-types' ),
	),
	'args'     => array_replace_recursive(
		$default_args,
		array(
			'description'   => __( 'Create and manage custom post types.', 'custom-post-types' ),
			'menu_position' => 900,
		)
	),
	'columns'  => array(
		'title'      => array(
			'label' => __( 'Plural', 'custom-post-types' ),
		),
		'item_key'   => array(
			'label'    => __( 'ID', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				echo esc_html( get_post_meta( $post_id, 'id', true ) );
			},
		),
		'item_count' => array(
			'label'    => __( 'Count', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$key = get_post_meta( $post_id, 'id', true );
				if ( empty( $key ) || ! ( isset( wp_count_posts( $key )->publish ) ? wp_count_posts( $key )->publish : false ) ) {
					echo '0';
					return;
				}
				printf(
					'<a href="%s" title="%s">%s</a>',
					admin_url( 'edit.php?post_type=' . $key ),
					__( 'View', 'custom-post-types' ),
					wp_count_posts( $key )->publish
				);
			},
		),
		'date'       => array(),
	),
);
// Create/edit new tax
$args[] = array(
	'id'       => CPT_UI_PREFIX . '_tax',
	'singular' => __( 'Taxonomy', 'custom-post-types' ),
	'plural'   => __( 'Taxonomies', 'custom-post-types' ),
	'labels'   => array(
		'name'               => __( 'Custom taxonomies', 'custom-post-types' ),
		'singular_name'      => __( 'Taxonomy', 'custom-post-types' ),
		'menu_name'          => __( 'Taxonomy', 'custom-post-types' ),
		'name_admin_bar'     => __( 'Taxonomy', 'custom-post-types' ),
		'add_new'            => __( 'Add taxonomy', 'custom-post-types' ),
		'add_new_item'       => __( 'Add new taxonomy', 'custom-post-types' ),
		'new_item'           => __( 'New taxonomy', 'custom-post-types' ),
		'edit_item'          => __( 'Edit taxonomy', 'custom-post-types' ),
		'view_item'          => __( 'View taxonomy', 'custom-post-types' ),
		'item_updated'       => __( 'Taxonomy updated', 'custom-post-types' ),
		'all_items'          => __( 'Taxonomies', 'custom-post-types' ),
		'search_items'       => __( 'Search taxonomy', 'custom-post-types' ),
		'not_found'          => __( 'No taxonomy available.', 'custom-post-types' ),
		'not_found_in_trash' => __( 'No taxonomy in the trash.', 'custom-post-types' ),
	),
	'args'     => array_replace_recursive(
		$default_args,
		array(
			'description'  => __( 'Create and manage custom taxonomies.', 'custom-post-types' ),
			'show_in_menu' => 'edit.php?post_type=' . CPT_UI_PREFIX,
		)
	),
	'columns'  => array(
		'title'      => array(
			'label' => __( 'Plural', 'custom-post-types' ),
		),
		'item_key'   => array(
			'label'    => __( 'ID', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				echo esc_html( get_post_meta( $post_id, 'id', true ) );
			},
		),
		'item_count' => array(
			'label'    => __( 'Count', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$key = get_post_meta( $post_id, 'id', true );
				if ( empty( $key ) || is_wp_error( wp_count_terms( array( 'taxonomy' => $key ) ) ) ) {
					echo '0';
					return;
				}
				printf(
					'<a href="%s" title="%s">%s</a>',
					admin_url( 'edit-tags.php?taxonomy=' . $key ),
					__( 'View', 'custom-post-types' ),
					wp_count_terms( array( 'taxonomy' => $key ) )
				);
			},
		),
		'used_by'    => array(
			'label'    => __( 'Assignment', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$supports = get_post_meta( $post_id, 'supports', true );
				if ( empty( $supports ) ) {
					return;
				}
				$output = array();
				foreach ( $supports as $post_type ) {
					if ( ! get_post_type_object( $post_type ) ) {
						continue;
					}
					$output[] = sprintf(
						'<a href="%s" title="%s">%s</a>',
						admin_url( 'edit.php?post_type=' . $post_type ),
						__( 'View', 'custom-post-types' ),
						get_post_type_object( $post_type )->labels->name
					);
				}
				echo implode( ', ', $output );
			},
		),
		'date'       => array(),
	),
);
// Create/edit new field group
$args[] = array(
	'id'       => CPT_UI_PREFIX . '_field',
	'singular' => __( 'Field group', 'custom-post-types' ),
	'plural'   => __( 'Field groups', 'custom-post-types' ),
	'labels'   => array(
		'name'               => __( 'Custom field groups', 'custom-post-types' ),
		'singular_name'      => __( 'Field group', 'custom-post-types' ),
		'menu_name'          => __( 'Field group', 'custom-post-types' ),
		'name_admin_bar'     => __( 'Field group', 'custom-post-types' ),
		'add_new'            => __( 'Add field group', 'custom-post-types' ),
		'add_new_item'       => __( 'Add new field group', 'custom-post-types' ),
		'new_item'           => __( 'New field group', 'custom-post-types' ),
		'edit_item'          => __( 'Edit field group', 'custom-post-types' ),
		'view_item'          => __( 'View field group', 'custom-post-types' ),
		'item_updated'       => __( 'Field group updated', 'custom-post-types' ),
		'all_items'          => __( 'Field groups', 'custom-post-types' ),
		'search_items'       => __( 'Search field group', 'custom-post-types' ),
		'not_found'          => __( 'No field group available.', 'custom-post-types' ),
		'not_found_in_trash' => __( 'No field group in the trash.', 'custom-post-types' ),
	),
	'args'     => array_replace_recursive(
		$default_args,
		array(
			'description'  => __( 'Create and manage custom field groups.', 'custom-post-types' ),
			'show_in_menu' => 'edit.php?post_type=' . CPT_UI_PREFIX,
			'supports'     => array( 'title' ),
		)
	),
	'columns'  => array(
		'title'         => array(
			'label' => __( 'Name', 'custom-post-types' ),
		),
		'item_key'      => array(
			'label'    => __( 'ID', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				echo esc_html( get_post_meta( $post_id, 'id', true ) );
			},
		),
		'item_count'    => array(
			'label'    => __( 'Fields', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$fields = get_post_meta( $post_id, 'fields', true );
				if ( empty( $fields ) ) {
					return;
				}
				$fields_labels_array = array_map(
					function ( $field ) {
						return $field['label'] . ' (' . $field['key'] . ')';
					},
					$fields
				);
				echo esc_html( implode( ', ', $fields_labels_array ) );
			},
		),
		'item_position' => array(
			'label'    => __( 'Position', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$available = array(
					''         => __( 'NORMAL', 'custom-post-types' ),
					'normal'   => __( 'NORMAL', 'custom-post-types' ),
					'side'     => __( 'SIDEBAR', 'custom-post-types' ),
					'advanced' => __( 'ADVANCED', 'custom-post-types' ),
				);
				echo esc_html( $available[ get_post_meta( $post_id, 'position', true ) ] );
			},
		),
		'used_by'       => array(
			'label'    => __( 'Assignment', 'custom-post-types' ),
			'callback' => function ( $post_id ) {
				$supports = get_post_meta( $post_id, 'supports', true );
				if ( empty( $supports ) ) {
					return;
				}
				$output = array();
				foreach ( $supports as $post_type ) {
					$content_type = \CPT_Field_Groups::SUPPORT_TYPE_CPT;
					$content      = $post_type;

					if ( strpos( $post_type, '/' ) !== false ) {
						$content_type = explode( '/', $post_type )[0];
						$content      = explode( '/', $post_type )[1];
					}

					switch ( $content_type ) {
						case \CPT_Field_Groups::SUPPORT_TYPE_CPT:
							if ( get_post_type_object( $content ) ) {
								$output[] = sprintf(
									'<a href="%s" title="%s">%s</a>',
									admin_url( 'edit.php?post_type=' . $content ),
									__( 'View', 'custom-post-types' ),
									get_post_type_object( $content )->labels->name
								);
							}
							break;
						case \CPT_Field_Groups::SUPPORT_TYPE_TAX:
							if ( get_taxonomy( $content ) ) {
								$output[] = sprintf(
									'<a href="%s" title="%s">%s</a>',
									admin_url( 'edit-tags.php?taxonomy=' . $content ),
									__( 'View', 'custom-post-types' ),
									get_taxonomy( $content )->labels->name
								);
							}
							break;
						case \CPT_Field_Groups::SUPPORT_TYPE_EXTRA:
							switch ( $content ) {
								case \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_USERS:
									$output[] = sprintf(
										'<a href="%s" title="%s">%s</a>',
										admin_url( 'users.php' ),
										__( 'View', 'custom-post-types' ),
										__( 'Users' )
									);
									break;
								case \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MEDIA:
									$output[] = sprintf(
										'<a href="%s" title="%s">%s</a>',
										admin_url( 'upload.php' ),
										__( 'View', 'custom-post-types' ),
										__( 'Media' )
									);
									break;
								case \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_COMMENTS:
									$output[] = sprintf(
										'<a href="%s" title="%s">%s</a>',
										admin_url( 'edit-comments.php' ),
										__( 'View', 'custom-post-types' ),
										__( 'Comments' )
									);
									break;
								case \CPT_Field_Groups::SUPPORT_TYPE_EXTRA_MENU:
									$output[] = sprintf(
										'<a href="%s" title="%s">%s</a>',
										admin_url( 'nav-menus.php' ),
										__( 'View', 'custom-post-types' ),
										__( 'Menu items' )
									);
									break;
							}
							break;
						case \CPT_Field_Groups::SUPPORT_TYPE_OPTIONS:
							if ( isset( cpt_utils()->get_settings_pages_options()[ $content ] ) ) {
								$page_url  = ! empty( $this->get_settings_pages_options()[ $content ]['url'] ) ? admin_url( $this->get_settings_pages_options()[ $content ]['url'] ) : menu_page_url( $content, false );
								if ( $page_url ) {
									$output[] = sprintf(
										'<a href="%s" title="%s">%s</a>',
										$page_url,
										__( 'View', 'custom-post-types' ),
										$this->get_settings_pages_options()[ $content ]['title']
									);
								}
							}
							break;
					}
				}
				echo implode( ', ', $output );
			},
		),
		'date'          => array(),
	),
);

return $args;
