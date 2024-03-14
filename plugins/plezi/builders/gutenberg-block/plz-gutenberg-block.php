<?php
if ( ! function_exists( 'plz_create_gutenberg_block' ) ) :
  function plz_create_gutenberg_block() {
    register_block_type( __DIR__ . '/build' );
	}
endif;

if ( ! function_exists( 'plz_add_gutenberg_category_block' ) ) :
	function plz_add_gutenberg_category_block( $block_categories, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) :
    	array_push(
        $block_categories,
    		array('slug'  => 'plezi',
    	        'title' => __('Plezi', 'plezi-for-wordpress'),
              'icon'  => null,
    		)
    	);
    endif;

	  return $block_categories;
	}
endif;
