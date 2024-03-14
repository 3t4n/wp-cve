<?php
/**
 * Generate MetaBox
 */

namespace Reuse\Builder;

class Reuse_Builder_Generate_MetaBox {

  public function __construct( $args ) {
      $this->generate_metabox( $args );
  }

	public function generate_metabox( $args ) {
		if( !empty( $args ) ){
			foreach ( $args as $key => $arg ) {
				if( isset( $arg['meta_preview'] ) ) {
					$current_screen = get_current_screen();
					if ($arg['post_type'] == 'user' && ($current_screen->base == 'profile' OR $current_screen->base == 'user-edit')) {
						$template = $arg;
						$post = $arg['post'];
						require( REUSE_BUILDER_DIR. '/admin-templates/form/metabox-preview.php');
					}else {
						add_meta_box( $arg['id'], __($arg['name'], 'reuse-builder'),
				      		array( $this , 'reuseb_render_dynamic_meta_box') ,
				      		$arg['post_type'], 'normal', $arg['position'], array( 'path' => $arg['template_path'], 'meta_preview' => $arg['meta_preview'] ) );
					}
				} else {
					add_meta_box( $arg['id'], __($arg['name'], 'reuse-builder'),
		      		array( $this , 'reuseb_render_meta_box') ,
		      		$arg['post_type'], 'normal', $arg['position'], array( 'path' => $arg['template_path'] ) );
				}
			}
		}
		register_rest_field( $arg['post_type'], 'metadata', array(
			'get_callback' => function ( $data ) {
				return get_post_meta( $data['id'], '', '' );
			}, 
		));
	}

	public function reuseb_render_meta_box( $post, $template ) {
		include_once( REUSE_BUILDER_DIR. '/admin-templates/'.$template['args']['path'] );
	}

	public function reuseb_render_dynamic_meta_box( $post, $template ) {
		require( REUSE_BUILDER_DIR. '/admin-templates/'.$template['args']['path'] );
	}
}
