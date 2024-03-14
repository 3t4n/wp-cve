<?php 
// Register Master Accordion Blocks
add_action( 'init', 'jltmaf_register_master_accordion_block' );

if ( ! function_exists( 'jltmaf_register_master_accordion_block' ) ) {
	function jltmaf_register_master_accordion_block(){

		// Gutenberg is not active.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_style(
			'jltmaf-style', // Handle.
			MAF_URL . '/inc/gutenberg/dist/blocks.style.build.css',
			// plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
			is_admin() ? array( 'wp-editor' ) : null, // Dependency to include the CSS after it.
			null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
		);

		// Register block editor script for backend.
		wp_register_script(
			'jltmaf-block', // Handle.
			MAF_URL . '/inc/gutenberg/dist/blocks.build.js',
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
			null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
			true // Enqueue the script in the footer.
		);

		// Register block editor styles for backend.
		wp_register_style(
			'jltmaf-editor', // Handle.
			MAF_URL . '/inc/gutenberg/dist/blocks.editor.build.css',
			array( 'wp-edit-blocks' ), // Dependency to include the CSS after it.
			null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
		);


		/**
		 * Register Gutenberg block on server-side.
		 *
		 * Register the block on server-side to ensure that the block
		 * scripts and styles for both frontend and backend are
		 * enqueued when the editor loads.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
		 * @since 1.16.0
		 */		
		register_block_type(
			'jltmaf/master-accordion', array(
				// Enqueue blocks.style.build.css on both frontend & backend.
				'style'         => 'jltmaf-style',
				// Enqueue blocks.build.js in the editor only.
				'editor_script' => 'jltmaf-block',
				// Enqueue blocks.editor.build.css in the editor only.
				'editor_style'  => 'jltmaf-editor',
				'render_callback' => 'jltmaf_guten_render_callbacks',
				'attributes' => array(
					'className' => array(
						'type' => 'string',
					),
					'align' => array(
						'type' => 'string',
						'default' => 'center',
					),				
					'postsToShow' => array(
						'type' => 'number',
						'default' => 6,
					),
					'orderBy'  => array(
						'type' => 'string',
						'default' => 'date',
					),				
					'faqCat' => array(
						'type' => 'string',
						'def;ault' => '',
					),				
					'faqTags' => array(
						'type' => 'string',
					),
					'order' => array(
						'type' => 'string',
						'default' => 'desc',
					),

				),

			)
		);

		add_shortcode( 'faq', 'jltmaf_guten_render_callbacks' );

	}
}



/**
 * Server side rendering functions
 */

function jltmaf_guten_render_callbacks( $attributes, $content ){
	global $post;

	$faqCat = isset( $attributes['faqCat'] ) ? $attributes['faqCat'] : '';
	$faqTags = isset( $attributes['faqTags'] ) ? $attributes['faqTags'] : '';
	$items = isset( $attributes['postsToShow'] ) ? $attributes['postsToShow'] : '-1';
	$order = isset( $attributes['order'] ) ? $attributes['order'] : 'desc';
	$orderBy = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'title';


	$recent_posts = wp_get_recent_posts( array(
		'post_type'        	=> 'faq',
		'numberposts' 		=> $items,
		'post_status' 		=> 'publish',
		'order' 			=> $order,
		'orderby' 			=> $orderBy,
		'faq_cat' 			=> $faqCat
	), 'OBJECT' );


	$list_items_markup = '';

	$count = 0; 
	$accordion = 'accordion-' . time() . rand();

	$jltmaf_id = $accordion .  $count;
	

	if ( $recent_posts ) {
		foreach ( $recent_posts as $post ) {
			$post_id = $post->ID;


			$list_items_markup .= sprintf(
				'<div class="%1$s"><div class="%2$s"><h3 class="%3$s">
				<a data-toggle="collapse" class="collapsed" data-parent="#%4$s" href="#%5$s-%6$s">
				<span class="pull-right jltmaf-icon">%7$s</span>%8$s</a></h3></div></div><div id="%5$s-%6$s" class="%9$s"><div class="panel-body">%10$s</div></div></div>',

				'panel panel-default',
				'jltmaf-item panel-heading',
				'panel-title',
				$jltmaf_id,
				$accordion,
				get_the_ID(),
				'',
				get_the_title( $post_id ),
				'panel-collapse collapse',
				$post->post_content
			);


		}
	}


	// Output the post markup
	$block_content = sprintf(
		'<div id="jltmaf-awesome-faq-%1$s"><div class="panel-group" id="%2$s">%3$s</div></div>',
		esc_attr( $accordion ),
		esc_attr( $jltmaf_id ),
		$list_items_markup
	);

	return $block_content;

}