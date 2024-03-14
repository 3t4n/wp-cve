<?php
/*
GUTENBERG BLOCK INTEGRATION
*/


function fca_eoi_gutenblock() {
	
	wp_register_script(
		'fca_eoi_gutenblock_script',
		FCA_EOI_PLUGIN_URL . '/assets/admin/block.js',
		array( 'wp-blocks', 'wp-element', 'wp-editor' ),
		FCA_EOI_VER
	);
	wp_register_style( 'fca-eoi-common-css', FCA_EOI_PLUGIN_URL . '/assets/style-new.min.css', array(), FCA_EOI_VER );
	wp_register_style ( 'theme-style', get_template_directory_uri().'/style.css' );
			
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type( 'optin-cat/gutenblock',
			array(
				'editor_script' => array( 'fca_eoi_gutenblock_script' ),
				'editor_style' => 'fca-eoi-common-css',
				'render_callback' => 'fca_eoi_gutenblock_render',
				'attributes' => array( 
					'post_id' => array( 
						'type' => 'string',
						'default' => '0'				
					)
				)
			)
		);
		register_block_type( 'optin-cat/gutenblock-twostep',
			array(
				'editor_script' => array( 'fca_eoi_gutenblock_script' ),
				'editor_style' => 'fca-eoi-common-css',
				'render_callback' => 'fca_eoi_gutenblock_twostep_render',
				'attributes' => array( 
					'post_id' => array( 
						'type' => 'string',
						'default' => '0'				
					)
				)
			)
		);
	}
	
}
add_action( 'init', 'fca_eoi_gutenblock' );


function fca_eoi_gutenblock_enqueue() {
	
	$posts = get_posts( array(
		'post_type' => 'easy-opt-ins',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids'
	));
	
	$table_list = array( 
		array(
			'value' => 0,
			'label' => 'Select an optin form',
		) 
	);

	$table_twostep_list = array( 
		array(
			'value' => 0,
			'label' => 'Select an optin form',
		) 
	);

	forEach ( $posts as $p ) {
		$fca_eoi = get_post_meta( $p, 'fca_eoi', true );
		$publish_mode = empty ( $fca_eoi[ 'publish_lightbox_mode' ] ) ? '' : $fca_eoi[ 'publish_lightbox_mode' ];
		$layout_string = empty( $fca_eoi[ 'layout' ] ) ? '' : $fca_eoi[ 'layout' ];
		$title = get_the_title( $p );
		if ( empty( $title ) ) {
			$title = __("(no title)", 'easy-opt-ins' );
		}
		if ( strpos( $layout_string, 'postbox' )  !== false ) {
			$table_list[] = array(
				'value' => $p,
				'label' => html_entity_decode( $title ),
			);
		}
		if ( $publish_mode === 'two_step_optin' ) {
			$table_twostep_list[] = array(
				'value' => $p,
				'label' => html_entity_decode( $title ),
			);
		}
	}
	
	wp_localize_script( 'fca_eoi_gutenblock_script', 'fca_eoi_gutenblock_script_data', array( 'optins' => $table_list, 'twostep_optins' => $table_twostep_list, 'editurl' => admin_url( 'post.php' ), 'newurl' => admin_url( 'post-new.php?post_type=easy-opt-ins' )  ) );
	
}
add_action( 'enqueue_block_editor_assets', 'fca_eoi_gutenblock_enqueue' );


function fca_eoi_gutenblock_render( $attributes ) {

	$id = empty( $attributes['post_id'] ) ? 0 : $attributes['post_id'];
	if ( $id ) {		
		return do_shortcode( "[easy-opt-in id='$id']" );
	}
	return '<p>' . __( 'Click here and select an optin from the block sidebar.', 'easy-opt-ins' ) . '</p>';
}

function fca_eoi_gutenblock_twostep_render( $attributes ) {

	$id = empty( $attributes[ 'post_id' ] ) ? 0 : $attributes[ 'post_id' ];
	$fca_eoi = get_post_meta( $id, 'fca_eoi', true );
	$cta_link = empty( $fca_eoi[ 'lightbox_cta_link' ] ) ? 'No link found' : $fca_eoi[ 'lightbox_cta_link' ];

	if ( $id ) {		
		return '<p class=fca_eoi_twostep_button>' . $cta_link . '</p>';
	}
	return '<p>' . __( 'Click here and select a two-step optin from the block sidebar.', 'easy-opt-ins' ) . '</p>';
}