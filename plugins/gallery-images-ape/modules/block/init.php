<?php

if ( ! class_exists( 'ApeGalleryBlocks' ) ) {

	class ApeGalleryBlocks {

		public $prefix = null;
		public $version = null;
		public $path = null;
		public $url = null;

		function __construct() {
			$this->version = WPAPE_GALLERY_VERSION; 
			$this->prefix = 'blocks-ape-gallery-'; 
			
			$this->path = WPAPE_GALLERY_PATH. ( WPAPE_GALLERY_DEBUG ? 'guttenbergBlock/' : 'modules/block/' );	
			$this->url 	= WPAPE_GALLERY_URL	. ( WPAPE_GALLERY_DEBUG ? 'guttenbergBlock/' : 'modules/block/' );	
			
			add_action( 'enqueue_block_assets', 					array( $this, 'block_assets') );
			add_action( 'enqueue_block_editor_assets', 				array( $this, 'editor_assets') );
			add_action( 'init', 									array( $this, 'php_block_init' ) );
			add_action( 'wp_ajax_ape_gallery_get_gallery_json', 	array( $this, 'ajaxGetGalleryJson') );

		}

		function block_assets(){ 

			wp_enqueue_style(
				$this->prefix.'style-css', 
				$this->url.'dist/blocks.style.build.css', 
				array( 'wp-editor' ),
				$this->version
			);
		}

		function editor_assets(){ 

			wp_enqueue_script(
				$this->prefix.'block-js',
				$this->url.'dist/blocks.build.js',
				array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
				$this->version,
				true // Enqueue the script in the footer.
			);

			wp_enqueue_style(
				$this->prefix.'block-editor-css',
				$this->url.'blocks.editor.build.css', 
				array( 'wp-edit-blocks' ),
				$this->version
			);
		}

		function php_block_init(){

			if ( !function_exists( 'register_block_type' ) ) {
				return ;
			}

			register_block_type( 'ape/block-ape-gallery', array(
			    'render_callback' => array( $this, 'renderBlock'),
			    'attributes'	  => array(
					'galleryid'	 => array(
						'type'	=> 'number',
						'default' => 0,
					),
				),
			) );

		}

		function renderBlock( $attributes ) {
	
			if( is_array($attributes) &&  isset($attributes['galleryid']) && $attributes['galleryid']>0 ){
				if(class_exists('apeGalleryHelper')){
					$id = (int) $attributes['galleryid'];
					return apeGalleryHelper::renderGalleryId( $id );
				} else return 'Ape Gallery:: Error 467';
				
			} else {
				return sprintf( 
					'<div><strong>%s</strong>: %s</div>', 
					'Ape Gallery', 
					__("You didn't select any Ape Gallery item in editor. Please select one from the list or create new gallery",'gallery-images-ape')
				) ;
			}    
		}

		function ajaxGetGalleryJson() { 

			$query = new WP_Query( 
				array( 
					'post_type' => WPAPE_GALLERY_POST,
					'post_status' => array( 'publish', 'private', 'future' )
				)
			);

			$posts = $query->posts;

			$returnJson = array();

			if( is_array($posts) && count($posts)){
				foreach($posts as $post) {
					$returnJson[] = array(
						'id' => $post->ID,
						'title' => esc_js($post->post_title),
						'parent' => $post->post_parent,
					);
				}
			}

			wp_send_json( $returnJson );
			wp_die();
		}
	}
}

new ApeGalleryBlocks();



/*add_filter( 'block_categories', 'block_ape_gallery_add_category', 10, 2 );

function block_ape_gallery_add_category( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'ape-blocks',
				'title' => __( 'Ape Gallery Blocks', 'gallery-images-ape' ),
				'icon'  => 'wordpress',
			),
		)
	);
}*/