<?php
/**
 * Generate custom post type on request
 */

namespace Reuse\Builder;

use Doctrine\Common\Inflector\Inflector;

class Reuse_Generate_Post_Type {

	/**
	*  Sample Array
	*  $post_types = array(
	*  	array(
	*  		'name' => 'custom_name',
	*  		'supports' => array(),
	*  		'menuPosition' => '',
	*  		'postPublic' => '',
	*  		'publiclyQueryable' => '',
	*  		'showUi' => '',
	*  		'showInMenu' => '',
	*  		'hasArchive' => '',
	*  		'hierarchical' => '',
	*  	)
	*  );
	*/

	protected $config = array(
		"name" 				      => 'custom',
		"postPublic" 		    => true,
		"publiclyQueryable" => true,
		"showUi" 			  		=> true,
		"showInMenu" 				=> true,
		"rewrite" 					=> true,
		"hasArchive" 				=> true,
		"hierarchical" 			=> true,
		"restAPi" 					=> false,
		"menuPosition" 			=> 25,
		"supports" 					=> array(),
		"menuIcon" 					=> 'dashicons-plus',
	);

	protected $supports = array(
		'title' 			=> false,
		'editor' 			=> false,
		'author' 			=> false,
		'thumbnail' 		=> false,
		'excerpt' 			=> false,
		'trackbacks' 		=> false,
		'customFields' 		=> false,
		'comments' 			=> false,
		'revisions' 		=> false,
		'pageAttributes' 	=> false,
		'postFormats' 		=> false,

	);

	public function __construct( $post_types ) {
		$this->generate_custom_post( $post_types );
	}


	/**
	 * Generate Custom post type
	 *
	 * @param array $post_types
	 *
	 * @return void
	 *
	 */

	public function generate_custom_post( $post_types ) {
		$post_type_supports = array();
		if( ! empty( $post_types ) ) {
			foreach ($post_types as $post_type) {
				$post_type = array_merge( $this->config, $post_type );
				$support = array_merge( $this->supports, $post_type['supports'] );
				$post_type_supports[] = ( $support['title'] ? 'title' : '' );
				$post_type_supports[] = ( $support['editor'] ? 'editor' : '' );
				$post_type_supports[] = ( $support['author'] ? 'author' : '' );
				$post_type_supports[] = ( $support['thumbnail'] ? 'thumbnail' : '' );
				$post_type_supports[] = ( $support['excerpt'] ? 'excerpt' : '' );
				$post_type_supports[] = ( $support['trackbacks'] ? 'trackbacks' : '' );
				$post_type_supports[] = ( $support['customFields'] ? 'custom-fields' : '' );
				$post_type_supports[] = ( $support['comments'] ? 'comments' : '' );
				$post_type_supports[] = ( $support['revisions'] ? 'revisions' : '' );
				$post_type_supports[] = ( $support['pageAttributes'] ? 'page-attributes' : '' );
				$post_type_supports[] = ( $support['postFormats']  ? 'post-formats' : '' );
				$plural_name = Inflector::pluralize($post_type['showName']);
				$singular_name = Inflector::singularize($post_type['showName']);
			  // Post type labels
			  $labels = array_merge(array(
					'name'               => _x($plural_name, 'post type general name'),
					'singular_name'      => _x($singular_name, 'post type singular name'),
					'add_new'            => _x('Add New ' . $singular_name, $singular_name),
					'add_new_item'       => __('Add New ' . $singular_name),
					'edit_item'          => __('Edit ' . $singular_name),
					'new_item'           => __('New ' . $singular_name),
					'all_items'          => __('All ' . $singular_name),
					'view_item'          => __('View ' . $singular_name),
					'search_items'       => __('Search ' . $singular_name),
					'not_found'          => __('No ' . $singular_name . ' found'),
					'not_found_in_trash' => __('No ' . $singular_name . ' found in Trash'),
					'parent_item_colon'  => '',
					'menu_name'          => __($plural_name)
				), isset($post_type['label']) ? $post_type['label'] : array() );

				$args = array(
					'labels'             => $labels,
					'public'             => (bool) $post_type['postPublic'],
					'publicly_queryable' => (bool) $post_type['publiclyQueryable'],
					'show_ui'            => (bool) $post_type['showUi'],
					'show_in_menu'       => $post_type['showInMenu'],
					'query_var'          => true,
					'map_meta_cap'       => true,
					'rewrite'            => (bool) $post_type['rewrite'],
					'has_archive'        => (bool) $post_type['hasArchive'],
					'hierarchical'       => (bool) $post_type['hierarchical'],
					'menu_position'      => $post_type['menuPosition'],
					'supports'           => $post_type_supports,
					'menu_icon'			 		 => $post_type['menuIcon'],
					'show_in_rest'			 => $post_type['restAPi']
				);

				$post_type_name = str_replace( ' ', '_', strtolower( $post_type['name'] ) );

				// Register post type
				register_post_type( $post_type_name, $args);

				$post_type_supports = array();

			}
		}
	}
}
