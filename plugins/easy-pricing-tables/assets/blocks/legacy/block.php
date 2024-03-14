<?php
/*
GUTENBERG BLOCK INTEGRATION
*/

function dh_ptp_gutenblock_register() {
	
	$block_file = in_array( DH_PTP_LICENSE_PACKAGE, array( 'Free', 'Personal' ) ) ? 'block.js' : 'block-premium.js';
		
	wp_register_script( 'dh_ptp_gutenblock_script', PTP_PLUGIN_URL . "/assets/blocks/legacy/$block_file", array( 'wp-blocks', 'wp-element', 'wp-editor' ), PTP_PLUGIN_VER );
	
	wp_register_style( 'dh-ptp-block-css', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/blocks/legacy/block.css' );
	wp_register_style( 'dh-ptp-design1', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design1/pricingtable.min.css' );
	if( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		wp_register_style( 'ept-font-awesome', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/font-awesome/css/font-awesome.min.css' );
		wp_register_style( 'ept-foundation', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/ui/foundation/foundation.min.css' );
		wp_register_style( 'fancy-flat-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/fancy-flat/pricingtable.min.css' );
		wp_register_style( 'stylish-flat-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/stylish-flat/css/pricingtable.min.css' );
		wp_register_style( 'design4-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design4/css/pricingtable.min.css' );
		wp_register_style( 'design5-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design5/pricingtable.min.css' );
		wp_register_style( 'design6-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design6/pricingtable.min.css' );
		wp_register_style( 'design7-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/design7/pricingtable.min.css' );
		wp_register_style( 'comparison1-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison1/css/comparison1-common.min.css' );
		wp_register_style( 'comparison2-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison2/css/comparison2-common.min.css' );      
		wp_register_style( 'comparison3-table-style', PTP_PLUGIN_PATH_FOR_SUBDIRS . '/assets/pricing-tables/comparison3/css/comparison3-common.min.css' );
	}
	
	if ( function_exists( 'register_block_type' ) ) {
		register_block_type( 'easy-pricing-tables/gutenblock',
			array(
				'editor_script' => 'dh_ptp_gutenblock_script',
				'render_callback' => 'dh_ptp_gutenblock_render',
				'attributes' => array( 
					'post_id' => array( 
						'type' => 'string',
						'default' => '0'				
					),	
					'layout' => array( 
						'type' => 'string',
						'default' => 'dh-ptp-simple-flat-template',				
					),	
					'alternate_pricing_table_id' => array( 
						'type' => 'string',
						'default' => '0'				
					),
					'alternate_2_pricing_table_id' => array( 
						'type' => 'string',
						'default' => '0'				
					),
					'mode' => array(
						'type' => 'string',
						'default' => 'table',
					),
					'default_title' => array(
						'type' => 'string',
						'default' => '',
					),	
					'default_subtitle' => array(
						'type' => 'string',
						'default' => '',
					),	
					'alternate_title' => array(
						'type' => 'string',
						'default' => '',
					),	
					'alternate_subtitle' => array(
						'type' => 'string',
						'default' => '',
					),	
					'alternate_2_title' => array(
						'type' => 'string',
						'default' => '',
					),	
					'alternate_2_subtitle' => array(
						'type' => 'string',
						'default' => '',
					),	
					'background_color'=> array(
						'type' => 'string',
						'default' => '#3498db',
					),
					'font_color'=> array(
						'type' => 'string',
						'default' => '#333333',
					),	
					'border_color'=> array(
						'type' => 'string',
						'default' => '#3498db',
					),
				)
			)
		);
	}	
}
add_action( 'init', 'dh_ptp_gutenblock_register' );


function dh_ptp_gutenblock_enqueue() {
	
	$posts = get_posts( array(
		'post_type' => 'easy-pricing-table',
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'fields' => 'ids'
	));
	
	$table_list = array( 
		array(
			'value' => 0,
			'label' => 'Select a pricing table',
			'layout' => '',
		) 
	);
	
	forEach ( $posts as $p ) {
		$title =  get_the_title( $p );
		if ( empty( $title ) ) {
			$title = __("(no title)", 'easy-pricing-tables' );
		}

		$table_list[] = array(
			'value' => $p,
			'label' => html_entity_decode( $title ),
			'layout' => dh_ptp_selected_layout( $p )
		);
		
	}

	
	wp_enqueue_style( 'dh-ptp-design1' );
	if( DH_PTP_LICENSE_PACKAGE !== 'Free' ) {
		wp_enqueue_style( array(
			'ept-font-awesome',
			'ept-foundation',
			'fancy-flat-table-style',
			'stylish-flat-table-style',
			'design4-table-style',
			'design5-table-style',
			'design6-table-style',
			'design7-table-style',
			'comparison1-table-style',
			'comparison2-table-style',
			'comparison3-table-style'
		) );		
	}	
	
	wp_enqueue_style( 'dh-ptp-block-css' );
	wp_localize_script( 'dh_ptp_gutenblock_script', 'dh_ptp_gutenblock_script_data', array( 'tables' => $table_list, 'editurl' => admin_url( 'post.php' ), 'newurl' => admin_url( 'post-new.php' )  ) );
	
}
add_action( 'enqueue_block_editor_assets', 'dh_ptp_gutenblock_enqueue' );

function dh_ptp_selected_layout( $post_id ) {

	$meta = get_post_meta( $post_id, '1_dh_ptp_settings', true);

	$layouts = array(
		'dh-ptp-fancy-flat-template',
		'dh-ptp-stylish-flat-template',
		'dh-ptp-design4-template',
		'dh-ptp-dg5-template',
		'dh-ptp-dg6-template',
		'dh-ptp-dg7-template',
		'dh-ptp-dg6-template',
		'dh-ptp-comparison1-template',
		'dh-ptp-comparison2-template',
		'dh-ptp-comparison3-template',
	);
		
	forEach( $layouts as $value ) {
		
		if ( isset( $meta[ $value ] ) && $meta[ $value ] == 'selected' ) {
			return $value;
		}
	}
	
	return 'dh-ptp-simple-flat-template';
	
}

function dh_ptp_gutenblock_render( $attributes ) {

	$id = empty( $attributes['post_id'] ) ? 0 : $attributes['post_id'];
	$mode = empty( $attributes['mode'] ) ? 'table' : $attributes['mode'];
	
	if ( empty( $id ) ) {
		return '<p>' . __( 'Click here and select a pricing table from the block sidebar menu.', 'easy-pricing-tables' ) . '</p>';
	}

	if ( $mode === 'table' ) {
		return do_shortcode( "[easy-pricing-table id='$id']" );
	}	
	if ( $mode === 'toggle' ) {
		$alternate_pricing_table_id = empty( $attributes['alternate_pricing_table_id'] ) ? '' : $attributes['alternate_pricing_table_id'];
		$alternate_2_pricing_table_id = empty( $attributes['alternate_2_pricing_table_id'] ) ? '' : $attributes['alternate_2_pricing_table_id'];
		$default_title = empty( $attributes['default_title'] ) ? '' : $attributes['default_title'];
		$default_subtitle = empty( $attributes['default_subtitle'] ) ? '' : $attributes['default_subtitle'];
		$alternate_title = empty( $attributes['alternate_title'] ) ? '' : $attributes['alternate_title']; 
		$alternate_subtitle = empty( $attributes['alternate_subtitle'] ) ? '' : $attributes['alternate_subtitle'];
		$alternate_2_title = empty( $attributes['alternate_2_title'] ) ? '' : $attributes['alternate_2_title'];
		$alternate_2_subtitle = empty( $attributes['alternate_2_subtitle'] ) ? '' : $attributes['alternate_2_subtitle'];
		$background_color = empty( $attributes['background_color'] ) ? '' : $attributes['background_color'];
		$font_color = empty( $attributes['font_color'] ) ? '' : $attributes['font_color'];	
		$border_color = empty( $attributes['border_color'] ) ? '' : $attributes['border_color'];
		
		return do_shortcode( "[easy-pricing-toggle default_pricing_table_id='$id' default_title='$default_title' default_subtitle='$default_subtitle' alternate_title='$alternate_title' alternate_subtitle='$alternate_subtitle' alternate_pricing_table_id='$alternate_pricing_table_id' font_color='$font_color' background_color='$background_color' border_color='$border_color' alternate_2_title='$alternate_2_title' alternate_2_subtitle='$alternate_2_subtitle' alternate_2_pricing_table_id='$alternate_2_pricing_table_id' ]" );
	}
	
}
