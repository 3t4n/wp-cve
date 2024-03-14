<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package content-views-query-and-display-post-page
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'contentviews_block_init' );
function contentviews_block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}

	wp_register_style(
		'contentviews-old-style',
		plugins_url( 'editor.css', __FILE__ ),
		array(),
		PT_CV_VERSION
	);

	wp_register_script(
		'contentviews-old-script',
		plugins_url( 'index.min.js', __FILE__ ),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components'
		),
		PT_CV_VERSION,
		true // in footer is required
	);

	register_block_type( 'content-views/block', array(
		'attributes' => [
			'viewId'  => [
				'type' => 'string',
			]
		],
		'editor_style'  => 'contentviews-old-style',
		'editor_script'  => 'contentviews-old-script',
		'render_callback' => 'contentviews_block_output',
//		 https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/
//		 maybe, if WP before 5.9 to show raw shortcode. otherwise, show full output
//		'style' => '',
//		'script' => '',
	) );

}

add_action( 'enqueue_block_editor_assets', 'contentviews_block_enqueue_assets' );
function contentviews_block_enqueue_assets() {
	$js_data = array(
		'views_list' => contentviews_get_views_list(),
		'edit_link'	 => PT_CV_Functions::view_link( 'VIEWID' ),		
		'texts' => array(
			'title'			 => 'Content Views (Shortcode)',
			'description'  => __( 'Select and display one of your views.', 'content-views-query-and-display-post-page' ),
			'keywords'     => [
				'content',
				__( 'grid', 'content-views-query-and-display-post-page' ),
				__( 'content', 'content-views-query-and-display-post-page' ),
				__( 'view', 'content-views-query-and-display-post-page' ),
				__( 'post', 'content-views-query-and-display-post-page' ),
			],
			'edit' => __( 'Edit View', 'content-views-query-and-display-post-page' ),
		),
	);

	wp_localize_script(
		'contentviews-old-script',
		'ContentViewsBlock',
		$js_data
	);
}

function contentviews_block_output( $attr ) {
	$id = !empty( $attr[ 'viewId' ] ) ? cv_sanitize_vid( $attr[ 'viewId' ] ) : 0;

	if ( empty( $id ) ) {
		return '';
	}

	$output = '';
	$is_gb_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && !empty( $_REQUEST[ 'context' ] ) && $_REQUEST[ 'context' ] === 'edit';
	if ( $is_gb_editor ) {
		#$output .= '<p style="background:#ececec; text-align:center;">' . __( 'Some functions do not work here. Please preview this post to see fully functional output.', 'content-views-query-and-display-post-page' ) . '</p>';

		PT_CV_Html::frontend_styles();
		#PT_CV_Html::frontend_scripts();

		ob_start();
		wp_print_styles( PT_CV_PREFIX . 'public-style' );
		wp_print_styles( PT_CV_PREFIX . 'public-pro-style' );
		#wp_print_scripts( PT_CV_PREFIX . 'content-views-script' );
		#wp_print_scripts( PT_CV_PREFIX . 'public-pro-script' );
		$output .= ob_get_clean();
		#$output = preg_replace( '/<script[^>]*jquery[^>]*><\/script>/', '', $output );

		// disable Pro lazyload, as it might not work in backend block editor
		add_filter( PT_CV_PREFIX_ . 'do_lazy_image', '__return_false' );
	}

	ob_start();
	echo do_shortcode( "[pt_view id='$id']" );
	$output .= ob_get_clean();

	return $output;
}


function contentviews_get_views_list() {
	$result = array(
		array(
			'value'	 => '',
			'label'	 => __( 'Select a View', 'content-views-query-and-display-post-page' )
		)
	);

	$views = get_posts( array(
		'suppress_filters'	 => true,
		'post_type'			 => PT_CV_POST_TYPE,
		'post_status'		 => 'publish',
		'posts_per_page'	 => -1
	) );

	foreach ( $views as $view ) {
		$viewid = get_post_meta( $view->ID, PT_CV_META_ID, true );
		if ( $viewid ) {
			$result[] = array(
				'value'	 => $viewid,
				'label'	 => apply_filters( 'the_title', $view->post_title, $view->ID )
			);
		}
	}
	// error_log( 'get views list executed' );
	return $result;
}
