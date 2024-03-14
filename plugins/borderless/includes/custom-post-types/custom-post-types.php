<?php

defined( 'ABSPATH' ) || exit;

/**
* Dynamic Custom Post Types
*
* A custom built plugin that helps user generate dinamic custom post types.
*
* @since    1.1.8
*/


function borderless_cpt_init() {
	$labels = array(
		'name'                  => _x( 'Post Types', 'Post Type General Name', 'borderless' ),
		'singular_name'         => _x( 'Post Type', 'Post Type Singular Name', 'borderless' ),
		'menu_name'             => __( 'Post Types', 'borderless' ),
		'name_admin_bar'        => __( 'Post Type', 'borderless' ),
		'archives'              => __( 'Item Archives', 'borderless' ),
		'attributes'            => __( 'Item Attributes', 'borderless' ),
		'parent_item_colon'     => __( 'Parent Item:', 'borderless' ),
		'all_items'             => __( 'All Items', 'borderless' ),
		'add_new_item'          => __( 'Add New Item', 'borderless' ),
		'add_new'               => __( 'Add New', 'borderless' ),
		'new_item'              => __( 'New Item', 'borderless' ),
		'edit_item'             => __( 'Edit Item', 'borderless' ),
		'update_item'           => __( 'Update Item', 'borderless' ),
		'view_item'             => __( 'View Item', 'borderless' ),
		'view_items'            => __( 'View Items', 'borderless' ),
		'search_items'          => __( 'Search Item', 'borderless' ),
		'not_found'             => __( 'Not found', 'borderless' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'borderless' ),
		'featured_image'        => __( 'Featured Image', 'borderless' ),
		'set_featured_image'    => __( 'Set featured image', 'borderless' ),
		'remove_featured_image' => __( 'Remove featured image', 'borderless' ),
		'use_featured_image'    => __( 'Use as featured image', 'borderless' ),
		'insert_into_item'      => __( 'Insert into item', 'borderless' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'borderless' ),
		'items_list'            => __( 'Items list', 'borderless' ),
		'items_list_navigation' => __( 'Items list navigation', 'borderless' ),
		'filter_items_list'     => __( 'Filter items list', 'borderless' ),
	);
	$args = array(
		'label'                 => __( 'Post Type', 'borderless' ),
		'description'           => __( 'Borderless Custom Post Types', 'borderless' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=borderless_cpt',
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'query_var'             => true,
		'rewrite'               => true,
	);
	register_post_type( 'borderless_cpt', $args );
	
	
	
	$the_query = new WP_Query(array('post_type' => array('borderless_cpt')));
	while ($the_query->have_posts()) : $the_query->the_post();
	$taxonomies[] = '';
	$supports[] = '';
	
	/**
	* General
	*/
	
	$singular_name = get_post_meta( get_the_ID(), 'borderless-cpt-general-singular-name', true );
	$plural_name = get_post_meta( get_the_ID(), 'borderless-cpt-general-plural-name', true );
	$slug = strtolower( get_post_meta( get_the_ID(), 'borderless-cpt-general-slug', true ) );
	$hierarchical = get_post_meta( get_the_ID(), 'borderless-cpt-general-hierarchical', true ) == '2' ? true : false;
	$categories = get_post_meta( get_the_ID(), 'borderless-cpt-general-categories', true ) == '2' ? $taxonomies[] = 'category' : '';
	$tags = get_post_meta( get_the_ID(), 'borderless-cpt-general-tags', true ) == '2' ? $taxonomies[] = 'post_tag' : '';
	
	
	/**
	* Labels
	*/
	
	$menu_name = get_post_meta( get_the_ID(), 'borderless-cpt-labels-menu-name', true );
	$admin_bar_name = get_post_meta( get_the_ID(), 'borderless-cpt-labels-admin-bar-name', true );
	$archives = get_post_meta( get_the_ID(), 'borderless-cpt-labels-archives', true );
	$attributes = get_post_meta( get_the_ID(), 'borderless-cpt-labels-attributes', true );
	$parent_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-parent-item', true );
	$all_items = get_post_meta( get_the_ID(), 'borderless-cpt-labels-all-items', true );
	$add_new_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-add-new-item', true );
	$add_new = get_post_meta( get_the_ID(), 'borderless-cpt-labels-add-new', true );
	$new_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-new-item', true );
	$edit_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-edit-item', true );
	$update_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-update-item', true );
	$view_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-view-item', true );
	$view_items = get_post_meta( get_the_ID(), 'borderless-cpt-labels-view-items', true );
	$search_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-search-item', true );
	$not_found = get_post_meta( get_the_ID(), 'borderless-cpt-labels-not-found', true );
	$not_found_in_trash = get_post_meta( get_the_ID(), 'borderless-cpt-labels-not-found-in-trash', true );
	$featured_image = get_post_meta( get_the_ID(), 'borderless-cpt-labels-featured-image', true );
	$set_featured_image = get_post_meta( get_the_ID(), 'borderless-cpt-labels-set-featured-image', true );
	$remove_featured_image = get_post_meta( get_the_ID(), 'borderless-cpt-labels-remove-featured-image', true );
	$use_as_featured_image = get_post_meta( get_the_ID(), 'borderless-cpt-labels-use-as-featured-image', true );
	$insert_into_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-insert-into-item', true );
	$uploaded_to_this_item = get_post_meta( get_the_ID(), 'borderless-cpt-labels-uploaded-to-this-item', true );
	$items_list = get_post_meta( get_the_ID(), 'borderless-cpt-labels-items-list', true );
	$items_list_navigation = get_post_meta( get_the_ID(), 'borderless-cpt-labels-items-list-navigation', true );
	$filter_items_list = get_post_meta( get_the_ID(), 'borderless-cpt-labels-filter-items-list', true );
	
	
	/**
	* Options
	*/
	
	$title = get_post_meta( get_the_ID(), 'borderless-cpt-options-title', true ) == '2' ? $supports[] = 'title' : '';
	$editor = get_post_meta( get_the_ID(), 'borderless-cpt-options-editor', true ) == '2' ? $supports[] = 'editor' : '';
	$excerpt = get_post_meta( get_the_ID(), 'borderless-cpt-options-excerpt', true ) == '2' ? $supports[] = 'excerpt' : '';
	$author = get_post_meta( get_the_ID(), 'borderless-cpt-options-author', true ) == '2' ? $supports[] = 'author' : '';
	$featured_image_support = get_post_meta( get_the_ID(), 'borderless-cpt-options-featured-image', true ) == '2' ? $supports[] = 'thumbnail' : '';
	$comments = get_post_meta( get_the_ID(), 'borderless-cpt-options-comments', true ) == '2' ? $supports[] = 'comments' : '';
	$trackbacks = get_post_meta( get_the_ID(), 'borderless-cpt-options-trackbacks', true ) == '2' ? $supports[] = 'trackbacks' : '';
	$revisions = get_post_meta( get_the_ID(), 'borderless-cpt-options-revisions', true ) == '2' ? $supports[] = 'revisions' : '';
	$custom_fields = get_post_meta( get_the_ID(), 'borderless-cpt-options-custom-fields', true ) == '2' ? $supports[] = 'custom-fields' : '';
	$page_attributes = get_post_meta( get_the_ID(), 'borderless-cpt-options-page-attributes', true ) == '2' ? $supports[] = 'page-attributes' : '';
	$post_formats = get_post_meta( get_the_ID(), 'borderless-cpt-options-post-formats', true ) == '2' ? $supports[] = 'post-formats' : '';
	$exclude_from_search = get_post_meta( get_the_ID(), 'borderless-cpt-options-exclude-from-search', true ) == '2' ? true : false;
	$enable_export = get_post_meta( get_the_ID(), 'borderless-cpt-options-enable-export', true ) == '2' ? true : false;
	$enable_archives = get_post_meta( get_the_ID(), 'borderless-cpt-options-enable-archives', true );
	$custom_archive_slug = get_post_meta( get_the_ID(), 'borderless-cpt-options-custom-archive-slug', true );
	
	if ( $enable_archives == 1 ) {
		$has_archive = false;
	} else if ( $enable_archives == 2 ) {
		$has_archive = true;
	} else if ( $enable_archives == 3 ) {
		if ( $custom_archive_slug == '' ) { $custom_archive_slug = true; }
		$has_archive = $custom_archive_slug;
	} else {
		$has_archive = true;
	}
	
	
	/**
	* Presentation
	*/
	
	$public = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-public', true ) == '2' ? true : false;
	$show_ui = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-show-ui', true ) == '2' ? true : false;
	$show_in_admin_sidebar = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-show-in-admin-sidebar', true );
	$show_in_admin_bar = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-show-in-admin-bar', true ) == '2' ? true : false;
	$show_in_navigation_menus = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-show-in-navigation-menus', true ) == '2' ? true : false;
	$admin_sidebar_icon = get_post_meta( get_the_ID(), 'borderless-cpt-presentation-admin-sidebar-icon', true );
	
	
	/**
	* Query
	*/
	
	$query = get_post_meta( get_the_ID(), 'borderless-cpt-query-query', true );
	$publicly_queryable = get_post_meta( get_the_ID(), 'borderless-cpt-query-publicly-queryable', true ) == '2' ? true : false;
	$custom_query = get_post_meta( get_the_ID(), 'borderless-cpt-query-custom-query', true );
	if ( $query == '1' ) {
		$query = $slug;
	} else {
		if ($custom_query == '') { $custom_query = $slug; }
		$query = $custom_query;
	}
	
	
	/**
	* Permalinks
	*/
	
	$permalink_rewrite = get_post_meta( get_the_ID(), 'borderless-cpt-permalinks-permalink-rewrite', true );
	$use_url_slug = get_post_meta( get_the_ID(), 'borderless-cpt-permalinks-use-url-slug', true ) == '2' ? true : false;
	$pagination = get_post_meta( get_the_ID(), 'borderless-cpt-permalinks-pagination', true ) == '2' ? true : false;
	$feeds = get_post_meta( get_the_ID(), 'borderless-cpt-permalinks-feeds', true ) == '2' ? true : false;
	$url_slug = strtolower( get_post_meta( get_the_ID(), 'borderless-cpt-permalinks-url-slug', true ) );
	
	if ( $permalink_rewrite == '1' ) {
		$rewrite = $slug;
	} else if ( $permalink_rewrite == '2' ) {
		$rewrite = false;
	} else if ( $permalink_rewrite == '3' ) {
		if ($url_slug == '') { $url_slug = $slug; }
		
		$rewrite = array(
			'slug'                  => $url_slug,
			'with_front'            => $use_url_slug,
			'pages'                 => $pagination,
			'feeds'                 => $feeds,
		);
	} else {
		$rewrite = $slug;
	}
	
	
	/**
	* Capabilities
	*/
	
	$base_capabilities = get_post_meta( get_the_ID(), 'borderless-cpt-capabilities-base-capabilities', true );
	$base_capability_type = get_post_meta( get_the_ID(), 'borderless-cpt-capabilities-base-capability-type', true );
	$capability_type = get_post_meta( get_the_ID(), 'borderless-cpt-capabilities-capability-type', true );
	
	if ( $base_capabilities == '2' ) {
		if ( !empty( $capability_type ) ) {
			if ( false !== strpos( $capability_type, ',' ) ) {
				$caps = array_map( 'trim', explode( ',', $capability_type ) );
				if ( count( $caps ) > 2 ) {
					$caps = array_slice( $caps, 0, 2 );
				}
				$capability_type = $caps;
			}
			$capabilities = array(
				'capabilities'          => $capability_type,
			);
		}
	}
	
	
	/**
	* Rest API
	*/
	
	$show_in_rest = get_post_meta( get_the_ID(), 'borderless-cpt-rest-api-show-in-rest', true ) == '2' ? true : false;
	$rest_base = get_post_meta( get_the_ID(), 'borderless-cpt-rest-api-rest-base', true );
	$rest_controller_class = get_post_meta( get_the_ID(), 'borderless-cpt-rest-api-show-in-rest', true );
	
	if ($rest_base == '') {$rest_base = $slug;}
	
	$labels = array(
		'name'                  => _x( $plural_name, 'Post Type General Name', 'borderless' ),
		'singular_name'         => _x( $singular_name, 'Post Type Singular Name', 'borderless' ),
		'menu_name'             => __( $menu_name, 'borderless' ),
		'name_admin_bar'        => __( $admin_bar_name, 'borderless' ),
		'archives'              => __( $archives, 'borderless' ),
		'attributes'            => __( $attributes, 'borderless' ),
		'parent_item_colon'     => __( $parent_item, 'borderless' ),
		'all_items'             => __( $all_items, 'borderless' ),
		'add_new_item'          => __( $add_new_item, 'borderless' ),
		'add_new'               => __( $add_new, 'borderless' ),
		'new_item'              => __( $new_item, 'borderless' ),
		'edit_item'             => __( $edit_item, 'borderless' ),
		'update_item'           => __( $update_item, 'borderless' ),
		'view_item'             => __( $view_item, 'borderless' ),
		'view_items'            => __( $view_items, 'borderless' ),
		'search_items'          => __( $search_item, 'borderless' ),
		'not_found'             => __( $not_found, 'borderless' ),
		'not_found_in_trash'    => __( $not_found_in_trash, 'borderless' ),
		'featured_image'        => __( $featured_image, 'borderless' ),
		'set_featured_image'    => __( $set_featured_image, 'borderless' ),
		'remove_featured_image' => __( $remove_featured_image, 'borderless' ),
		'use_featured_image'    => __( $use_as_featured_image, 'borderless' ),
		'insert_into_item'      => __( $insert_into_item, 'borderless' ),
		'uploaded_to_this_item' => __( $uploaded_to_this_item, 'borderless' ),
		'items_list'            => __( $items_list, 'borderless' ),
		'items_list_navigation' => __( $items_list_navigation, 'borderless' ),
		'filter_items_list'     => __( $filter_items_list, 'borderless' ),
	);
	$args = array(
		'label'                 => __( $singular_name, 'borderless' ),
		'description'           => __( 'Post Type Description', 'borderless' ),
		'labels'                => $labels,
		'supports'              => $supports,
		'taxonomies'            => $taxonomies,
		'hierarchical'          => $hierarchical,
		'public'                => $public,
		'show_ui'               => $show_ui,
		'show_in_menu'          => true,
		'menu_position'         => $show_in_admin_sidebar,
		'menu_icon'             => $admin_sidebar_icon,
		'show_in_admin_bar'     => $show_in_admin_bar,
		'show_in_nav_menus'     => $show_in_navigation_menus,
		'can_export'            => $enable_export,
		'has_archive'           => $has_archive,
		'exclude_from_search'   => $exclude_from_search,
		'publicly_queryable'    => $publicly_queryable ,
		'query_var'             => $query,
		'rewrite'               => $rewrite,
		'capability_type'       => $base_capability_type,
		'show_in_rest'          => $show_in_rest,
		'rest_base'             => $rest_base,
		'rest_controller_class' => $rest_controller_class,
	);
	if ( $base_capabilities == '2' && !empty( $capability_type ) ) { $args = array_merge($args, $capabilities); }
	register_post_type($slug , $args);
	
endwhile;
}


/**
* General
*/

class borderless_cpt_general {
	private $config = '{"title":"General","prefix":"borderless-cpt-general-","domain":"borderless","class_name":"borderless_cpt_general","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"text","label":"Singular Name","default":"Post Type","id":"borderless-cpt-general-singular-name"},{"type":"text","label":"Plural Name","default":"Post Types","id":"borderless-cpt-general-plural-name"},{"type":"text","label":"Slug","id":"borderless-cpt-general-slug"},{"type":"select","label":"Categories","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-general-categories"},{"type":"select","label":"Tags","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-general-tags"}]}';

	public function __construct() {
		$this->config = json_decode( $this->config, true );
		$this->process_cpts();
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
		add_action( 'save_post', [ $this, 'save_post' ] );
	}

	public function process_cpts() {
		if ( !empty( $this->config['cpt'] ) ) {
			if ( empty( $this->config['post-type'] ) ) {
				$this->config['post-type'] = [];
			}
			$parts = explode( ',', $this->config['cpt'] );
			$parts = array_map( 'trim', $parts );
			$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
		}
	}

	public function add_meta_boxes() {
		foreach ( $this->config['post-type'] as $screen ) {
			add_meta_box(
				sanitize_title( $this->config['title'] ),
				$this->config['title'],
				[ $this, 'add_meta_box_callback' ],
				$screen,
				$this->config['context'],
				$this->config['priority']
			);
		}
	}

	public function save_post( $post_id ) {
		foreach ( $this->config['fields'] as $field ) {
			switch ( $field['type'] ) {
				default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
			}
		}
	}

	public function add_meta_box_callback() {
		$this->fields_table();
	}

	private function fields_table() {
		?><table class="form-table" role="presentation">
			<tbody><?php
				foreach ( $this->config['fields'] as $field ) {
					?><tr>
						<th scope="row"><?php $this->label( $field ); ?></th>
						<td><?php $this->field( $field ); ?></td>
					</tr><?php
				}
			?></tbody>
		</table><?php
	}

	private function label( $field ) {
		switch ( $field['type'] ) {
			default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'], $field['label']
				);
		}
	}

	private function field( $field ) {
		switch ( $field['type'] ) {
			case 'select':
				$this->select( $field );
				break;
			default:
				$this->input( $field );
		}
	}

	private function input( $field ) {
		printf(
			'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
			isset( $field['class'] ) ? $field['class'] : '',
			$field['id'], $field['id'],
			isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
			$field['type'],
			$this->value( $field )
		);
	}

	private function select( $field ) {
		printf(
			'<select id="%s" name="%s">%s</select>',
			$field['id'], $field['id'],
			$this->select_options( $field )
		);
	}

	private function select_selected( $field, $current ) {
		$value = $this->value( $field );
		if ( $value === $current ) {
			return 'selected';
		}
		return '';
	}

	private function select_options( $field ) {
		$output = [];
		$options = explode( "\r\n", $field['options'] );
		$i = 0;
		foreach ( $options as $option ) {
			$pair = explode( ':', $option );
			$pair = array_map( 'trim', $pair );
			$output[] = sprintf(
				'<option %s value="%s"> %s</option>',
				$this->select_selected( $field, $pair[0] ),
				$pair[0], $pair[1]
			);
			$i++;
		}
		return implode( '<br>', $output );
	}

	private function value( $field ) {
		global $post;
		if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
			$value = get_post_meta( $post->ID, $field['id'], true );
		} else if ( isset( $field['default'] ) ) {
			$value = $field['default'];
		} else {
			return '';
		}
		return str_replace( '\u0027', "'", $value );
	}

}
new borderless_cpt_general;
	
	
	/**
	* Labels
	*/
	
	class borderless_cpt_labels {
		private $config = '{"title":"Labels","prefix":"borderless-cpt-labels-","domain":"borderless","class_name":"borderless_cpt_labels","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"text","label":"Menu Name","default":"Post Types","id":"borderless-cpt-labels-menu-name"},{"type":"text","label":"Admin Bar Name","default":"Post Type","id":"borderless-cpt-labels-admin-bar-name"},{"type":"text","label":"Archives","default":"Item Archives","id":"borderless-cpt-labels-archives"},{"type":"text","label":"Attributes","default":"Item Attributes","id":"borderless-cpt-labels-attributes"},{"type":"text","label":"Parent Item","default":"Parent Item:","id":"borderless-cpt-labels-parent-item"},{"type":"text","label":"All Items","default":"All Items","id":"borderless-cpt-labels-all-items"},{"type":"text","label":"Add New Item","default":"Add New Item","id":"borderless-cpt-labels-add-new-item"},{"type":"text","label":"Add New","default":"Add New","id":"borderless-cpt-labels-add-new"},{"type":"text","label":"New Item","default":"New Item","id":"borderless-cpt-labels-new-item"},{"type":"text","label":"Edit Item","default":"Edit Item","id":"borderless-cpt-labels-edit-item"},{"type":"text","label":"Update Item","default":"Update Item","id":"borderless-cpt-labels-update-item"},{"type":"text","label":"View Item","default":"View Item","id":"borderless-cpt-labels-view-item"},{"type":"text","label":"View Items","default":"View Items","id":"borderless-cpt-labels-view-items"},{"type":"text","label":"Search Item","default":"Search Item","id":"borderless-cpt-labels-search-item"},{"type":"text","label":"Not Found","default":"Not found","id":"borderless-cpt-labels-not-found"},{"type":"text","label":"Not Found in Trash","default":"Not found in Trash","id":"borderless-cpt-labels-not-found-in-trash"},{"type":"text","label":"Featured Image","default":"Featured Image","id":"borderless-cpt-labels-featured-image"},{"type":"text","label":"Set featured image","default":"Set featured image","id":"borderless-cpt-labels-set-featured-image"},{"type":"text","label":"Remove featured image","default":"Remove featured image","id":"borderless-cpt-labels-remove-featured-image"},{"type":"text","label":"Use as featured image","default":"Use as featured image","id":"borderless-cpt-labels-use-as-featured-image"},{"type":"text","label":"Insert into item","default":"Insert into item","id":"borderless-cpt-labels-insert-into-item"},{"type":"text","label":"Uploaded to this item","default":"Uploaded to this item","id":"borderless-cpt-labels-uploaded-to-this-item"},{"type":"text","label":"Items list","default":"Items list","id":"borderless-cpt-labels-items-list"},{"type":"text","label":"Items list navigation","default":"Items list navigation","id":"borderless-cpt-labels-items-list-navigation"},{"type":"text","label":"Filter items list","default":"Filter items list","id":"borderless-cpt-labels-filter-items-list"}]}';
		
		public function __construct() {
			$this->config = json_decode( $this->config, true );
			$this->process_cpts();
			add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
			add_action( 'save_post', [ $this, 'save_post' ] );
		}
		
		public function process_cpts() {
			if ( !empty( $this->config['cpt'] ) ) {
				if ( empty( $this->config['post-type'] ) ) {
					$this->config['post-type'] = [];
				}
				$parts = explode( ',', $this->config['cpt'] );
				$parts = array_map( 'trim', $parts );
				$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
			}
		}
		
		public function add_meta_boxes() {
			foreach ( $this->config['post-type'] as $screen ) {
				add_meta_box(
					sanitize_title( $this->config['title'] ),
					$this->config['title'],
					[ $this, 'add_meta_box_callback' ],
					$screen,
					$this->config['context'],
					$this->config['priority']
				);
			}
		}
		
		public function save_post( $post_id ) {
			foreach ( $this->config['fields'] as $field ) {
				switch ( $field['type'] ) {
					default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
				}
			}
		}
		
		public function add_meta_box_callback() {
			$this->fields_table();
		}
		
		private function fields_table() {
			?><table class="form-table" role="presentation">
			<tbody><?php
			foreach ( $this->config['fields'] as $field ) {
				?><tr>
				<th scope="row"><?php $this->label( $field ); ?></th>
				<td><?php $this->field( $field ); ?></td>
				</tr><?php
			}
			?></tbody>
			</table><?php
		}
		
		private function label( $field ) {
			switch ( $field['type'] ) {
				default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'], $field['label']
				);
			}
		}
		
		private function field( $field ) {
			switch ( $field['type'] ) {
				default:
				$this->input( $field );
			}
		}
		
		private function input( $field ) {
			printf(
				'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
				isset( $field['class'] ) ? $field['class'] : '',
				$field['id'], $field['id'],
				isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
				$field['type'],
				$this->value( $field )
			);
		}
		
		private function value( $field ) {
			global $post;
			if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
				$value = get_post_meta( $post->ID, $field['id'], true );
			} else if ( isset( $field['default'] ) ) {
				$value = $field['default'];
			} else {
				return '';
			}
			return str_replace( '\u0027', "'", $value );
		}
		
	}
	new borderless_cpt_labels;
	
	
	/**
	* Options
	*/
	
	class borderless_cpt_options {
		private $config = '{"title":"Options","prefix":"borderless-cpt-options-","domain":"borderless","class_name":"borderless_cpt_options","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Title","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-title"},{"type":"select","label":"Editor","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-editor"},{"type":"select","label":"Excerpt","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-excerpt"},{"type":"select","label":"Author","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-author"},{"type":"select","label":"Featured Image","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-featured-image"},{"type":"select","label":"Comments","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-comments"},{"type":"select","label":"Trackbacks","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-trackbacks"},{"type":"select","label":"Revisions","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-revisions"},{"type":"select","label":"Custom Fields","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-custom-fields"},{"type":"select","label":"Page Attributes","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-page-attributes"},{"type":"select","label":"Post Formats","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-post-formats"},{"type":"select","label":"Exclude From Search","default":"1","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-exclude-from-search"},{"type":"select","label":"Enable Export","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-options-enable-export"},{"type":"select","label":"Enable Archives","default":"2","options":"1 : Prevent archive pages\r\n2 : Use default slug\r\n3 : Set custom archive slug","id":"borderless-cpt-options-enable-archives"},{"type":"text","label":"Custom Archive Slug","id":"borderless-cpt-options-custom-archive-slug"}]}';
		
		public function __construct() {
			$this->config = json_decode( $this->config, true );
			$this->process_cpts();
			add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
			add_action( 'save_post', [ $this, 'save_post' ] );
		}
		
		public function process_cpts() {
			if ( !empty( $this->config['cpt'] ) ) {
				if ( empty( $this->config['post-type'] ) ) {
					$this->config['post-type'] = [];
				}
				$parts = explode( ',', $this->config['cpt'] );
				$parts = array_map( 'trim', $parts );
				$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
			}
		}
		
		public function add_meta_boxes() {
			foreach ( $this->config['post-type'] as $screen ) {
				add_meta_box(
					sanitize_title( $this->config['title'] ),
					$this->config['title'],
					[ $this, 'add_meta_box_callback' ],
					$screen,
					$this->config['context'],
					$this->config['priority']
				);
			}
		}
		
		public function save_post( $post_id ) {
			foreach ( $this->config['fields'] as $field ) {
				switch ( $field['type'] ) {
					default:
					if ( isset( $_POST[ $field['id'] ] ) ) {
						$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
						update_post_meta( $post_id, $field['id'], $sanitized );
					}
				}
			}
		}
		
		public function add_meta_box_callback() {
			$this->fields_table();
		}
		
		private function fields_table() {
			?><table class="form-table" role="presentation">
			<tbody><?php
			foreach ( $this->config['fields'] as $field ) {
				?><tr>
				<th scope="row"><?php $this->label( $field ); ?></th>
				<td><?php $this->field( $field ); ?></td>
				</tr><?php
			}
			?></tbody>
			</table><?php
		}
		
		private function label( $field ) {
			switch ( $field['type'] ) {
				default:
				printf(
					'<label class="" for="%s">%s</label>',
					$field['id'], $field['label']
				);
			}
		}
		
		private function field( $field ) {
			switch ( $field['type'] ) {
				case 'select':
					$this->select( $field );
					break;
					default:
					$this->input( $field );
				}
			}
			
			private function input( $field ) {
				printf(
					'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
					isset( $field['class'] ) ? $field['class'] : '',
					$field['id'], $field['id'],
					isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
					$field['type'],
					$this->value( $field )
				);
			}
			
			private function select( $field ) {
				printf(
					'<select id="%s" name="%s">%s</select>',
					$field['id'], $field['id'],
					$this->select_options( $field )
				);
			}
			
			private function select_selected( $field, $current ) {
				$value = $this->value( $field );
				if ( $value === $current ) {
					return 'selected';
				}
				return '';
			}
			
			private function select_options( $field ) {
				$output = [];
				$options = explode( "\r\n", $field['options'] );
				$i = 0;
				foreach ( $options as $option ) {
					$pair = explode( ':', $option );
					$pair = array_map( 'trim', $pair );
					$output[] = sprintf(
						'<option %s value="%s"> %s</option>',
						$this->select_selected( $field, $pair[0] ),
						$pair[0], $pair[1]
					);
					$i++;
				}
				return implode( '<br>', $output );
			}
			
			private function value( $field ) {
				global $post;
				if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
					$value = get_post_meta( $post->ID, $field['id'], true );
				} else if ( isset( $field['default'] ) ) {
					$value = $field['default'];
				} else {
					return '';
				}
				return str_replace( '\u0027', "'", $value );
			}
			
		}
		new borderless_cpt_options;
		
		
		/**
		* Presentation
		*/
		
		class borderless_cpt_presentation {
			private $config = '{"title":"Presentation","prefix":"borderless-cpt-presentation-","domain":"borderless","class_name":"borderless_cpt_presentation","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Public","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-presentation-public"},{"type":"select","label":"Show in Admin Bar","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-presentation-show-in-admin-bar"},{"type":"select","label":"Show in Navigation Menus","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-presentation-show-in-navigation-menus"},{"type":"select","label":"Show UI","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-presentation-show-ui"},{"type":"select","label":"Show in Admin Sidebar","default":"5","options":"2 : Dashboard\r\n4 : Separator\r\n5 : Posts\r\n10 : Media\r\n15 : Links\r\n20 : Pages\r\n25 : Comments\r\n59 : Separator\r\n60 : Appearance\r\n65 : Plugins\r\n70 : Users\r\n75 : Tools\r\n80 : Settings\r\n99 : Separator","id":"borderless-cpt-presentation-show-in-admin-sidebar"},{"type":"text","label":"Admin Sidebar Icon","default":"dashicons-admin-generic","id":"borderless-cpt-presentation-admin-sidebar-icon"}]}';
			
			public function __construct() {
				$this->config = json_decode( $this->config, true );
				$this->process_cpts();
				add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
				add_action( 'save_post', [ $this, 'save_post' ] );
			}
			
			public function process_cpts() {
				if ( !empty( $this->config['cpt'] ) ) {
					if ( empty( $this->config['post-type'] ) ) {
						$this->config['post-type'] = [];
					}
					$parts = explode( ',', $this->config['cpt'] );
					$parts = array_map( 'trim', $parts );
					$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
				}
			}
			
			public function add_meta_boxes() {
				foreach ( $this->config['post-type'] as $screen ) {
					add_meta_box(
						sanitize_title( $this->config['title'] ),
						$this->config['title'],
						[ $this, 'add_meta_box_callback' ],
						$screen,
						$this->config['context'],
						$this->config['priority']
					);
				}
			}
			
			public function save_post( $post_id ) {
				foreach ( $this->config['fields'] as $field ) {
					switch ( $field['type'] ) {
						default:
						if ( isset( $_POST[ $field['id'] ] ) ) {
							$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
							update_post_meta( $post_id, $field['id'], $sanitized );
						}
					}
				}
			}
			
			public function add_meta_box_callback() {
				$this->fields_table();
			}
			
			private function fields_table() {
				?><table class="form-table" role="presentation">
				<tbody><?php
				foreach ( $this->config['fields'] as $field ) {
					?><tr>
					<th scope="row"><?php $this->label( $field ); ?></th>
					<td><?php $this->field( $field ); ?></td>
					</tr><?php
				}
				?></tbody>
				</table><?php
			}
			
			private function label( $field ) {
				switch ( $field['type'] ) {
					default:
					printf(
						'<label class="" for="%s">%s</label>',
						$field['id'], $field['label']
					);
				}
			}
			
			private function field( $field ) {
				switch ( $field['type'] ) {
					case 'select':
						$this->select( $field );
						break;
						default:
						$this->input( $field );
					}
				}
				
				private function input( $field ) {
					printf(
						'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
						isset( $field['class'] ) ? $field['class'] : '',
						$field['id'], $field['id'],
						isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
						$field['type'],
						$this->value( $field )
					);
				}
				
				private function select( $field ) {
					printf(
						'<select id="%s" name="%s">%s</select>',
						$field['id'], $field['id'],
						$this->select_options( $field )
					);
				}
				
				private function select_selected( $field, $current ) {
					$value = $this->value( $field );
					if ( $value === $current ) {
						return 'selected';
					}
					return '';
				}
				
				private function select_options( $field ) {
					$output = [];
					$options = explode( "\r\n", $field['options'] );
					$i = 0;
					foreach ( $options as $option ) {
						$pair = explode( ':', $option );
						$pair = array_map( 'trim', $pair );
						$output[] = sprintf(
							'<option %s value="%s"> %s</option>',
							$this->select_selected( $field, $pair[0] ),
							$pair[0], $pair[1]
						);
						$i++;
					}
					return implode( '<br>', $output );
				}
				
				private function value( $field ) {
					global $post;
					if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
						$value = get_post_meta( $post->ID, $field['id'], true );
					} else if ( isset( $field['default'] ) ) {
						$value = $field['default'];
					} else {
						return '';
					}
					return str_replace( '\u0027', "'", $value );
				}
				
			}
			new borderless_cpt_presentation;
			
			
			/**
			* Query
			*/
			
			class borderless_cpt_query {
				private $config = '{"title":"Query","prefix":"borderless-cpt-query-","domain":"borderless","class_name":"borderless_cpt_query","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Query","default":"1","options":"1 : Default post type key\r\n2 : Custom query variable","id":"borderless-cpt-query-query"},{"type":"select","label":"Publicly Queryable","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-query-publicly-queryable"},{"type":"text","label":"Custom Query","id":"borderless-cpt-query-custom-query"}]}';
				
				public function __construct() {
					$this->config = json_decode( $this->config, true );
					$this->process_cpts();
					add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
					add_action( 'save_post', [ $this, 'save_post' ] );
				}
				
				public function process_cpts() {
					if ( !empty( $this->config['cpt'] ) ) {
						if ( empty( $this->config['post-type'] ) ) {
							$this->config['post-type'] = [];
						}
						$parts = explode( ',', $this->config['cpt'] );
						$parts = array_map( 'trim', $parts );
						$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
					}
				}
				
				public function add_meta_boxes() {
					foreach ( $this->config['post-type'] as $screen ) {
						add_meta_box(
							sanitize_title( $this->config['title'] ),
							$this->config['title'],
							[ $this, 'add_meta_box_callback' ],
							$screen,
							$this->config['context'],
							$this->config['priority']
						);
					}
				}
				
				public function save_post( $post_id ) {
					foreach ( $this->config['fields'] as $field ) {
						switch ( $field['type'] ) {
							default:
							if ( isset( $_POST[ $field['id'] ] ) ) {
								$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
								update_post_meta( $post_id, $field['id'], $sanitized );
							}
						}
					}
				}
				
				public function add_meta_box_callback() {
					$this->fields_table();
				}
				
				private function fields_table() {
					?><table class="form-table" role="presentation">
					<tbody><?php
					foreach ( $this->config['fields'] as $field ) {
						?><tr>
						<th scope="row"><?php $this->label( $field ); ?></th>
						<td><?php $this->field( $field ); ?></td>
						</tr><?php
					}
					?></tbody>
					</table><?php
				}
				
				private function label( $field ) {
					switch ( $field['type'] ) {
						default:
						printf(
							'<label class="" for="%s">%s</label>',
							$field['id'], $field['label']
						);
					}
				}
				
				private function field( $field ) {
					switch ( $field['type'] ) {
						case 'select':
							$this->select( $field );
							break;
							default:
							$this->input( $field );
						}
					}
					
					private function input( $field ) {
						printf(
							'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
							isset( $field['class'] ) ? $field['class'] : '',
							$field['id'], $field['id'],
							isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
							$field['type'],
							$this->value( $field )
						);
					}
					
					private function select( $field ) {
						printf(
							'<select id="%s" name="%s">%s</select>',
							$field['id'], $field['id'],
							$this->select_options( $field )
						);
					}
					
					private function select_selected( $field, $current ) {
						$value = $this->value( $field );
						if ( $value === $current ) {
							return 'selected';
						}
						return '';
					}
					
					private function select_options( $field ) {
						$output = [];
						$options = explode( "\r\n", $field['options'] );
						$i = 0;
						foreach ( $options as $option ) {
							$pair = explode( ':', $option );
							$pair = array_map( 'trim', $pair );
							$output[] = sprintf(
								'<option %s value="%s"> %s</option>',
								$this->select_selected( $field, $pair[0] ),
								$pair[0], $pair[1]
							);
							$i++;
						}
						return implode( '<br>', $output );
					}
					
					private function value( $field ) {
						global $post;
						if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
							$value = get_post_meta( $post->ID, $field['id'], true );
						} else if ( isset( $field['default'] ) ) {
							$value = $field['default'];
						} else {
							return '';
						}
						return str_replace( '\u0027', "'", $value );
					}
					
				}
				new borderless_cpt_query;
				
				
				/**
				* Permalinks
				*/
				
				class borderless_cpt_permalinks {
					private $config = '{"title":"Permalinks","prefix":"borderless-cpt-permalinks-","domain":"borderless","class_name":"borderless_cpt_permalinks","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Permalink Rewrite","default":"1","options":"1 : Default permalink\r\n2 : No permalink\r\n3 : Custom permalink","id":"borderless-cpt-permalinks-permalink-rewrite"},{"type":"select","label":"Use URL Slug","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-permalinks-use-url-slug"},{"type":"select","label":"Pagination","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-permalinks-pagination"},{"type":"select","label":"Feeds","default":"2","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-permalinks-feeds"},{"type":"text","label":"URL Slug","id":"borderless-cpt-permalinks-url-slug"}]}';
					
					public function __construct() {
						$this->config = json_decode( $this->config, true );
						$this->process_cpts();
						add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
						add_action( 'save_post', [ $this, 'save_post' ] );
					}
					
					public function process_cpts() {
						if ( !empty( $this->config['cpt'] ) ) {
							if ( empty( $this->config['post-type'] ) ) {
								$this->config['post-type'] = [];
							}
							$parts = explode( ',', $this->config['cpt'] );
							$parts = array_map( 'trim', $parts );
							$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
						}
					}
					
					public function add_meta_boxes() {
						foreach ( $this->config['post-type'] as $screen ) {
							add_meta_box(
								sanitize_title( $this->config['title'] ),
								$this->config['title'],
								[ $this, 'add_meta_box_callback' ],
								$screen,
								$this->config['context'],
								$this->config['priority']
							);
						}
					}
					
					public function save_post( $post_id ) {
						foreach ( $this->config['fields'] as $field ) {
							switch ( $field['type'] ) {
								default:
								if ( isset( $_POST[ $field['id'] ] ) ) {
									$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
									update_post_meta( $post_id, $field['id'], $sanitized );
								}
							}
						}
					}
					
					public function add_meta_box_callback() {
						$this->fields_table();
					}
					
					private function fields_table() {
						?><table class="form-table" role="presentation">
						<tbody><?php
						foreach ( $this->config['fields'] as $field ) {
							?><tr>
							<th scope="row"><?php $this->label( $field ); ?></th>
							<td><?php $this->field( $field ); ?></td>
							</tr><?php
						}
						?></tbody>
						</table><?php
					}
					
					private function label( $field ) {
						switch ( $field['type'] ) {
							default:
							printf(
								'<label class="" for="%s">%s</label>',
								$field['id'], $field['label']
							);
						}
					}
					
					private function field( $field ) {
						switch ( $field['type'] ) {
							case 'select':
								$this->select( $field );
								break;
								default:
								$this->input( $field );
							}
						}
						
						private function input( $field ) {
							printf(
								'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
								isset( $field['class'] ) ? $field['class'] : '',
								$field['id'], $field['id'],
								isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
								$field['type'],
								$this->value( $field )
							);
						}
						
						private function select( $field ) {
							printf(
								'<select id="%s" name="%s">%s</select>',
								$field['id'], $field['id'],
								$this->select_options( $field )
							);
						}
						
						private function select_selected( $field, $current ) {
							$value = $this->value( $field );
							if ( $value === $current ) {
								return 'selected';
							}
							return '';
						}
						
						private function select_options( $field ) {
							$output = [];
							$options = explode( "\r\n", $field['options'] );
							$i = 0;
							foreach ( $options as $option ) {
								$pair = explode( ':', $option );
								$pair = array_map( 'trim', $pair );
								$output[] = sprintf(
									'<option %s value="%s"> %s</option>',
									$this->select_selected( $field, $pair[0] ),
									$pair[0], $pair[1]
								);
								$i++;
							}
							return implode( '<br>', $output );
						}
						
						private function value( $field ) {
							global $post;
							if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
								$value = get_post_meta( $post->ID, $field['id'], true );
							} else if ( isset( $field['default'] ) ) {
								$value = $field['default'];
							} else {
								return '';
							}
							return str_replace( '\u0027', "'", $value );
						}
						
					}
					new borderless_cpt_permalinks;
					
					
					/**
					* Capabilities
					*/
					
					class borderless_cpt_capabilities {
						private $config = '{"title":"Capabilities","prefix":"borderless-cpt-capabilities-","domain":"borderless","class_name":"borderless_cpt_capabilities","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Base Capabilities","default":"1","options":"1 : Base capabilities\r\n2 : Custom capabilities","id":"borderless-cpt-capabilities-base-capabilities"},{"type":"select","label":"Base Capability Type","default":"page","options":"page : Pages\r\npost : Posts","id":"borderless-cpt-capabilities-base-capability-type"},{"type":"text","label":"Capability Type","default":"story, stories","id":"borderless-cpt-capabilities-capability-type"}]}';
						
						public function __construct() {
							$this->config = json_decode( $this->config, true );
							$this->process_cpts();
							add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
							add_action( 'save_post', [ $this, 'save_post' ] );
						}
						
						public function process_cpts() {
							if ( !empty( $this->config['cpt'] ) ) {
								if ( empty( $this->config['post-type'] ) ) {
									$this->config['post-type'] = [];
								}
								$parts = explode( ',', $this->config['cpt'] );
								$parts = array_map( 'trim', $parts );
								$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
							}
						}
						
						public function add_meta_boxes() {
							foreach ( $this->config['post-type'] as $screen ) {
								add_meta_box(
									sanitize_title( $this->config['title'] ),
									$this->config['title'],
									[ $this, 'add_meta_box_callback' ],
									$screen,
									$this->config['context'],
									$this->config['priority']
								);
							}
						}
						
						public function save_post( $post_id ) {
							foreach ( $this->config['fields'] as $field ) {
								switch ( $field['type'] ) {
									default:
									if ( isset( $_POST[ $field['id'] ] ) ) {
										$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
										update_post_meta( $post_id, $field['id'], $sanitized );
									}
								}
							}
						}
						
						public function add_meta_box_callback() {
							$this->fields_table();
						}
						
						private function fields_table() {
							?><table class="form-table" role="presentation">
							<tbody><?php
							foreach ( $this->config['fields'] as $field ) {
								?><tr>
								<th scope="row"><?php $this->label( $field ); ?></th>
								<td><?php $this->field( $field ); ?></td>
								</tr><?php
							}
							?></tbody>
							</table><?php
						}
						
						private function label( $field ) {
							switch ( $field['type'] ) {
								default:
								printf(
									'<label class="" for="%s">%s</label>',
									$field['id'], $field['label']
								);
							}
						}
						
						private function field( $field ) {
							switch ( $field['type'] ) {
								case 'select':
									$this->select( $field );
									break;
									default:
									$this->input( $field );
								}
							}
							
							private function input( $field ) {
								printf(
									'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
									isset( $field['class'] ) ? $field['class'] : '',
									$field['id'], $field['id'],
									isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
									$field['type'],
									$this->value( $field )
								);
							}
							
							private function select( $field ) {
								printf(
									'<select id="%s" name="%s">%s</select>',
									$field['id'], $field['id'],
									$this->select_options( $field )
								);
							}
							
							private function select_selected( $field, $current ) {
								$value = $this->value( $field );
								if ( $value === $current ) {
									return 'selected';
								}
								return '';
							}
							
							private function select_options( $field ) {
								$output = [];
								$options = explode( "\r\n", $field['options'] );
								$i = 0;
								foreach ( $options as $option ) {
									$pair = explode( ':', $option );
									$pair = array_map( 'trim', $pair );
									$output[] = sprintf(
										'<option %s value="%s"> %s</option>',
										$this->select_selected( $field, $pair[0] ),
										$pair[0], $pair[1]
									);
									$i++;
								}
								return implode( '<br>', $output );
							}
							
							private function value( $field ) {
								global $post;
								if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
									$value = get_post_meta( $post->ID, $field['id'], true );
								} else if ( isset( $field['default'] ) ) {
									$value = $field['default'];
								} else {
									return '';
								}
								return str_replace( '\u0027', "'", $value );
							}
							
						}
						new borderless_cpt_capabilities;
						
						
						/**
						* Rest API
						*/
						
						class borderless_cpt_rest_api {
							private $config = '{"title":"Rest API","prefix":"borderless-cpt-rest-api-","domain":"borderless","class_name":"borderless_cpt_rest_api","context":"normal","priority":"default","cpt":"borderless_cpt","fields":[{"type":"select","label":"Show in Rest","default":"1","options":"1 : No\r\n2 : Yes","id":"borderless-cpt-rest-api-show-in-rest"},{"type":"text","label":"Rest Base","id":"borderless-cpt-rest-api-rest-base"},{"type":"text","label":"Rest Controller Class","default":"WP_REST_Posts_Controller","id":"borderless-cpt-rest-api-rest-controller-class"}]}';
							
							public function __construct() {
								$this->config = json_decode( $this->config, true );
								$this->process_cpts();
								add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
								add_action( 'save_post', [ $this, 'save_post' ] );
							}
							
							public function process_cpts() {
								if ( !empty( $this->config['cpt'] ) ) {
									if ( empty( $this->config['post-type'] ) ) {
										$this->config['post-type'] = [];
									}
									$parts = explode( ',', $this->config['cpt'] );
									$parts = array_map( 'trim', $parts );
									$this->config['post-type'] = array_merge( $this->config['post-type'], $parts );
								}
							}
							
							public function add_meta_boxes() {
								foreach ( $this->config['post-type'] as $screen ) {
									add_meta_box(
										sanitize_title( $this->config['title'] ),
										$this->config['title'],
										[ $this, 'add_meta_box_callback' ],
										$screen,
										$this->config['context'],
										$this->config['priority']
									);
								}
							}
							
							public function save_post( $post_id ) {
								foreach ( $this->config['fields'] as $field ) {
									switch ( $field['type'] ) {
										default:
										if ( isset( $_POST[ $field['id'] ] ) ) {
											$sanitized = sanitize_text_field( htmlspecialchars( $_POST[ $field['id'] ] ) );
											update_post_meta( $post_id, $field['id'], $sanitized );
										}
									}
								}
							}
							
							public function add_meta_box_callback() {
								$this->fields_table();
							}
							
							private function fields_table() {
								?><table class="form-table" role="presentation">
								<tbody><?php
								foreach ( $this->config['fields'] as $field ) {
									?><tr>
									<th scope="row"><?php $this->label( $field ); ?></th>
									<td><?php $this->field( $field ); ?></td>
									</tr><?php
								}
								?></tbody>
								</table><?php
							}
							
							private function label( $field ) {
								switch ( $field['type'] ) {
									default:
									printf(
										'<label class="" for="%s">%s</label>',
										$field['id'], $field['label']
									);
								}
							}
							
							private function field( $field ) {
								switch ( $field['type'] ) {
									case 'select':
										$this->select( $field );
										break;
										default:
										$this->input( $field );
									}
								}
								
								private function input( $field ) {
									printf(
										'<input class="regular-text %s" id="%s" name="%s" %s type="%s" value="%s">',
										isset( $field['class'] ) ? $field['class'] : '',
										$field['id'], $field['id'],
										isset( $field['pattern'] ) ? "pattern='{$field['pattern']}'" : '',
										$field['type'],
										$this->value( $field )
									);
								}
								
								private function select( $field ) {
									printf(
										'<select id="%s" name="%s">%s</select>',
										$field['id'], $field['id'],
										$this->select_options( $field )
									);
								}
								
								private function select_selected( $field, $current ) {
									$value = $this->value( $field );
									if ( $value === $current ) {
										return 'selected';
									}
									return '';
								}
								
								private function select_options( $field ) {
									$output = [];
									$options = explode( "\r\n", $field['options'] );
									$i = 0;
									foreach ( $options as $option ) {
										$pair = explode( ':', $option );
										$pair = array_map( 'trim', $pair );
										$output[] = sprintf(
											'<option %s value="%s"> %s</option>',
											$this->select_selected( $field, $pair[0] ),
											$pair[0], $pair[1]
										);
										$i++;
									}
									return implode( '<br>', $output );
								}
								
								private function value( $field ) {
									global $post;
									if ( metadata_exists( 'post', $post->ID, $field['id'] ) ) {
										$value = get_post_meta( $post->ID, $field['id'], true );
									} else if ( isset( $field['default'] ) ) {
										$value = $field['default'];
									} else {
										return '';
									}
									return str_replace( '\u0027', "'", $value );
								}
								
							}
							new borderless_cpt_rest_api;
							
							add_action('init', 'borderless_cpt_init');
							?>