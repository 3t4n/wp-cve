<?php
/**
 *
 */

namespace Reuse\Builder;

class Reuse_Builder_Listing {

	public function __construct() {
		add_action(	'init', array( $this, 'register_post_type' ) );
		add_action(	'init', array( $this, 'register_taxonomy' ) );
		add_action( 'add_meta_boxes', array( $this , 'add_metabox' ) );
		add_action( 'show_user_profile', array( $this , 'add_metabox' ) );
		add_action( 'edit_user_profile', array( $this , 'add_metabox' ) );
	}

	public function register_taxonomy() {

		$args = array(
			'post_type' 	=> 'reuseb_taxonomy',
			'posts_per_page' => -1,
		);

		$taxonomies = get_posts( $args );

		// Init the preloaded taxonomy here.
		$taxnomy_lists = array();

		foreach( $taxonomies as $taxonomy) {
			$settings = json_decode( get_post_meta($taxonomy->ID, '_reuse_builder_taxonomies_data', true), true );
			// _log($settings);
			$taxnomy_lists[] = array(
				"name" 		=> str_replace( ' ', '_', strtolower( $taxonomy->post_title ) ),
				"showName" 	=> $taxonomy->post_title,
				'postType' 	=> $settings['reuseb_taxonomy_post_select'],
				"hierarchy" => ( !empty( $settings['reuseb_taxonomy_hierarchy'] ) ) ? $settings['reuseb_taxonomy_hierarchy'] : false,
				"show_in_rest" => ( !empty( $settings['reuseb_taxonomy_rest_api_support'] ) &&  $settings['reuseb_taxonomy_rest_api_support'] === 'true') ? true : false,
			);
		}

		new Reuse_Generate_Taxonomy( $taxnomy_lists );
	}
	public function register_post_type() {

		$args = array(
			'post_type' 	=> 'reuseb_post_type',
			'posts_per_page' => -1,
		);

		$post_types = get_posts( $args );

		$dynamic_post_types = array();
		foreach ($post_types as $post_type) {
			$post_helpers = get_post_meta( $post_type->ID, '_reuse_builder_post_types_data', true );

			$post_types_data = json_decode( $post_helpers, true );

			$post_show_name = $post_type->post_title;

			$post_type_name = str_replace( ' ', '_', strtolower( $post_type->post_title ) );

			$post_type_support = array();

			$post_menu_icon = 'dashicons-plus';
			$rest_api_support = false;

			foreach ($post_types_data as $key => $value) {

				if( $key === 'reuseb_post_type_slug' && !empty( $value ) ) {
						$post_type_name = $value;
				}
				if( $key === 'reuseb_post_type_support_rest_api' && !empty( $value ) ) {
						$rest_api_support = $value === 'true' ? true : false;
				}

					if( $key === 'reuseb_post_type_menu_position' ) {
						$post_menu_position = intval ( $value );
					}

					if( $key === 'reuseb_post_type_menu_icon' ) {
						$menu_icon_select = get_post_meta( $post_type->ID, 'reuseb_post_type_menu_icon_select', true );
						if( $menu_icon_select === 'default_icon' ) {
							$post_menu_icon = $value;
						} else {
							$get_custom_icon = get_post_meta( $post_type->ID, 'reuseb_post_type_menu_icon_custom', true );
							if(isset($get_custom_icon) && !empty($get_custom_icon)){
								$post_menu_icon = $get_custom_icon[0]['url'];
							}

						}

					}

					switch ($key) {
						case 'reuseb_post_type_support_title':
							if( $value == 'true' ) {
								$post_type_support['title'] = true;
							}
							break;

						case 'reuseb_post_type_support_editor':
							if( $value == 'true' ) {
								$post_type_support['editor'] = true;
							}
							break;

						case 'reuseb_post_type_support_author':
							if( $value == 'true' ) {
								$post_type_support['author'] = true;
							}
							break;

						case 'reuseb_post_type_support_thumbnail':
							if( $value == 'true' ) {
								$post_type_support['thumbnail'] = true;
							}
							break;

						case 'reuseb_post_type_support_excerpt':
							if( $value == 'true' ) {
								$post_type_support['excerpt'] = true;
							}
							break;

						case 'reuseb_post_type_support_trackbacks':
							if( $value == 'true' ) {
								$post_type_support['trackback'] = true;
							}
							break;

						case 'reuseb_post_type_support_custom_fields':
							if( $value == 'true' ) {
								$post_type_support['customFields'] = true;
							}
							break;

						case 'reuseb_post_type_support_comments':
							if( $value == 'true' ) {
								$post_type_support['comments'] = true;
							}
							break;

						case 'reuseb_post_type_support_revisions':
							if( $value == 'true' ) {
								$post_type_support['revisions'] = true;
							}
							break;

						case 'reuseb_post_type_support_page_attributes':
							if( $value == 'true' ) {
								$post_type_support['pageAttributes'] = true;
							}
							break;

						case 'reuseb_post_type_support_post_formats':
							if( $value == 'true' ) {
								$post_type_support['postFromats'] = true;
							}
							break;

						default:
							# code...
							break;
					}

				}

			$dynamic_post_types[] = array(
				'name' 			=> $post_type_name,
				'showName' 		=> $post_show_name,
				'supports' 		=> $post_type_support,
				'menuPosition' 	=> $post_menu_position,
				'menuIcon' 		=> $post_menu_icon,
				'restAPi' 		=> $rest_api_support,
			);
		}

		$custom_post_types_args = apply_filters( 'reuseb_custom_post_type_args', array(
			array(
				"name" 				=> "reuseb_post_type",
				"showInMenu" 		=> "reuse_builder",
				"showName" 			=> __("Post Type", "reuse-builder"),
				"label" 			=> array(
					'all_items' 	=> __("Post Types", "reuse-builder"),
				),
				'supports' 			=> array(
					'title' 		=> true,
				),
				"publiclyQueryable" => false,
				"hasArchive" 		=> false,
				"hierarchical" 		=> false,
			),
			array(
				"name" 				=> "reuseb_taxonomy",
				"showInMenu" 		=> 'reuse_builder',
				"showName" 			=> __("Taxonomies", "reuse-builder"),
				"label" 			=> array(
					'all_items' 	=> __("Taxonomies", "reuse-builder"),
				),
				'supports' 			=> array(
					'title' 		=> true,
				),
				"publiclyQueryable" => false,
				"hasArchive" 		=> false,
				"hierarchical" 		=> false,
			),
			array(
				"name" 				=> "reuseb_term_metabox",
				"showInMenu" 		=> 'reuse_builder',
				"showName" 			=> __("Term Metabox", "reuse-builder"),
				"label" 			=> array(
					'all_items' 	=> __("Term Metaboxes", "reuse-builder"),
				),
				'supports' 			=> array(
					'title' 		=> true,
				),
				"publiclyQueryable" => false,
				"hasArchive" 		=> false,
				"hierarchical" 		=> false,
			),
			array(
				"name" 				=> "reuseb_metabox",
				"showInMenu" 		=> 'reuse_builder',
				"showName" 			=> __("Metaboxes", "reuse-builder"),
				"label" 			=> array(
					'all_items' 	=> __("Metaboxes", "reuse-builder"),
				),
				'supports' 			=> array(
					'title' 		=> true,
				),
				"publiclyQueryable" => false,
				"hasArchive" 		=> false,
				"hierarchical" 		=> false,
			),
			array(
				"name" 				=> "reuseb_template",
				"showInMenu" 		=> "reuse_builder",
				"showName" 			=> __("Template", "reuse-builder"),
				"label" 			=> array(
					'all_items' 	=> __("Templates", "reuse-builder"),
				),
				'supports' 			=> array(
					'title' 		=> true,
					'editor' 		=> true,
				),
				"publiclyQueryable" => false,
				"hasArchive" 		=> false,
				"hierarchical" 		=> false,
			),
		) );

		new Reuse_Generate_Post_Type( array_merge($custom_post_types_args , $dynamic_post_types ) );
	}
	public function add_metabox($info_data) {
		global $wpdb;
		$query = $wpdb->prepare("SELECT * FROM {$wpdb->posts} WHERE post_type = %s", 'reuseb_metabox');
		$the_query = $wpdb->get_results($query);

		// get dynamic metaboxes from the builder
		$dynamic_metabox = array();

		foreach( $the_query as $post ) {
			$form_data = get_post_meta( $post->ID, 'formBuilder', true );
			$post_type = get_post_meta( $post->ID, 'reuseb_post_type_select', true );
			if(isset($form_data['fields'])) {
				foreach ($form_data['fields'] as $key => $field) {
					$args = array(
						'type' => 'string',
						'description' => $field['id'],
						'single' => true,
						'show_in_rest' => true,
					);
					register_meta( 'post', $field['id'], $args );
				}
			}
			if( !empty( $post_type ) && !empty( $form_data ) ) {
				$dynamic_metabox[] = array(
					'id' 			=> $post->post_name,
					'name' 			=> $post->post_title,
					'meta_preview' 	=> $form_data,
					'post_type' 	=> $post_type,
					'post' 	=> $info_data,
					'position' 		=> 'high',
					'template_path' => '/form/metabox-preview.php'
				);
			}
		}

		$args = array(
			array(
				'id' 			=> 'reuse_builder_settings',
				'name' 			=> __('Post Type Settings', 'reuse-builder'),
				'post_type' 	=> 'reuseb_post_type',
				'position' 		=> 'high',
				'template_path' => '/form/post-type-builder.php',
			),
			array(
				'id' 			=> 'reuseb_taxonomy',
				'name' 			=> __('Taxonomy Settings', 'reuse-builder'),
				'post_type' 	=> 'reuseb_taxonomy',
				'position' 		=> 'high',
				'template_path' => '/form/taxonomy-builder.php'
			),
			array(
				'id' 			=> 'reuseb_taxonomy',
				'name' 			=> __('Term Meta Settings', 'reuse-builder'),
				'post_type' 	=> 'reuseb_term_metabox',
				'position' 		=> 'high',
				'template_path' => '/form/termmeta-builder.php'
			),
			array(
				'id' 			=> 'reuseb_taxonomy',
				'name' 			=> __('Metabox Builder', 'reuse-builder'),
				'post_type' 	=> 'reuseb_metabox',
				'position' 		=> 'high',
				'template_path' => '/form/metabox-builder.php',
			),
			array(
				'id' 			=> 'reuseb_condition_builder',
				'name' 			=> __('Condition Builder', 'reuse-builder'),
				'post_type' 	=> 'reuseb_metabox',
				'position' 		=> 'high',
				'template_path' => '/form/condition-builder.php'
			),
			array(
				'id' 			=> 'reuseb_template_settings',
				'name' 			=> __('Template Settings', 'reuse-builder'),
				'post_type' 	=> 'reuseb_template',
				'position' 		=> 'high',
				'template_path' => '/template-settings.php',
			),
		);

		$reuse_builder_settings = stripslashes_deep(get_option('reuseb_settings', true ));
		$geobox_post_types = json_decode($reuse_builder_settings);
    $geobox_post_types_array = $geobox_post_types != '1' && $geobox_post_types->geobox_enable_post_type !='' ? explode(',', $geobox_post_types->geobox_enable_post_type) : [];
    if(!empty($geobox_post_types_array)){
      foreach( $geobox_post_types_array as $post_type ) {
        $args[] = array(
          'id' => 'geo_metabox_preview',
          'name' => __('Location', 'reuse-builder'),
          'post_type' => $post_type,
          'position' => 'high',
          'template_path' => '/geo-metabox-preview.php'
        );
      }
    }

		new Reuse_Builder_Generate_MetaBox( array_merge( $args, $dynamic_metabox ) );
	}
}
