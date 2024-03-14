<?php

namespace Attire\Blocks;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// makes post search by title only
function atbs_search_by_title( $search, $wp_query ) {
	if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
		global $wpdb;

		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';

		$search = array();

		foreach ( ( array ) $q['search_terms'] as $term ) {
			$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
		}

		if ( ! is_user_logged_in() ) {
			$search[] = "$wpdb->posts.post_password = ''";
		}

		$search = ' AND ' . implode( ' AND ', $search );
	}

	return $search;
}

add_filter( 'posts_search', __NAMESPACE__ . '\atbs_search_by_title', 10, 2 );

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\atbs_enqueue_block_editor_assets' );
/**
 * Enqueue block editor only JavaScript and CSS.
 */

function atbs_enqueue_block_editor_assets() {
	$block_editor_js_path    = 'assets/js/editor.blocks.js';
	$block_editor_style_path = 'assets/css/blocks.editor.css';
	$style_path_both         = 'assets/css/blocks.both.css';

	wp_enqueue_code_editor( array( 'type' => 'text/css' ) );

	wp_add_inline_script(
		'wp-codemirror',
		'window.CodeMirror = wp.CodeMirror;'
	);

	// Enqueue the bundled block JS file
	wp_enqueue_script(
		'attire-blocks-js',
		ATTIRE_BLOCKS_DIR_URL . $block_editor_js_path,
		array(
			'jquery',
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-components',
			'wp-editor',
			'wp-api-fetch',
			'csslint',
			'wp-compose',
			'wp-data',
			'wp-hooks'
		),
		filemtime( ATTIRE_BLOCKS_DIR_PATH . $block_editor_js_path ),
		true
	);
	wp_localize_script(
		'attire-blocks-js',
		'block_editor_data',
		array(
			'assets_url' => ATTIRE_BLOCKS_DIR_URL . 'assets',
			'home_url'   => home_url( '/' ),
		)
	);
	// Enqueue optional editor only styles
	wp_enqueue_style( 'attire-blocks-editor', ATTIRE_BLOCKS_DIR_URL . $block_editor_style_path, [ 'code-editor' ], filemtime( ATTIRE_BLOCKS_DIR_PATH . $block_editor_style_path ) );
	wp_enqueue_style( 'attire-blocks-both', ATTIRE_BLOCKS_DIR_URL . $style_path_both, [], filemtime( ATTIRE_BLOCKS_DIR_PATH . $style_path_both ) );

	// Pass the variables to the block js file.
	wp_localize_script( 'attire-blocks-util-js', 'googleMapScript', array(
		'plugins_url' => plugin_dir_url( __FILE__ ),
	) );

//    wp_enqueue_script(
//        'extend-block-spacing-js',
//        ATTIRE_BLOCKS_DIR_URL . $block_spacing_extender_path,
//        array(),
//        filemtime(ATTIRE_BLOCKS_DIR_PATH . $block_spacing_extender_path),
//        true // Enqueue the script in the footer.
//    );

	wp_enqueue_script( 'attire-blocks-layout-importer', ATTIRE_BLOCKS_DIR_URL . '/assets/js/layout.importer.js', array(
		'wp-plugins',
		'wp-edit-post',
		'wp-element'
	), filemtime( ATTIRE_BLOCKS_DIR_PATH . '/assets/js/layout.importer.js' ) );

	wp_localize_script(
		'attire-blocks-layout-importer',
		'js_data',
		array(
			'assets_url'         => ATTIRE_BLOCKS_DIR_URL . 'assets',
			'is_pro'             => Util::is_pro(),
			'wpdm_blocks_active' => is_plugin_active( 'wpdm-gutenberg-blocks/wpdm-gutenberg-blocks.php' ),
			'license_data'       => get_option( '__atbs_pro' ),
			'license_key'        => get_option( '__atbs_pro_license' )
		)
	);

	wp_enqueue_style( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'attire-blocks-fontawesome', ATTIRE_BLOCKS_DIR_URL . 'lib/fontawesome/css/all.min.css' );
	wp_enqueue_script( 'attire-blocks-popper', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/popper.min.js' );
	wp_enqueue_script( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'atbs-common-admin', ATTIRE_BLOCKS_DIR_URL . 'assets/js/admin.script.js', [ 'jquery' ], filemtime( ATTIRE_BLOCKS_DIR_PATH . 'assets/js/admin.script.js' ) );
}

add_action( 'enqueue_block_assets', __NAMESPACE__ . '\atbs_enqueue_assets' );

/**
 * Enqueue front end and editor JavaScript and CSS assets.
 */
function atbs_enqueue_assets() {
	$style_path      = '/assets/css/blocks.style.css';
	$style_path_both = '/assets/css/blocks.both.css';
	if ( file_exists( ATTIRE_BLOCKS_DIR_URL . $style_path ) ) {
		wp_enqueue_style(
			'attire-blocks-frontend',
			ATTIRE_BLOCKS_DIR_URL . $style_path,
			null,
			filemtime( ATTIRE_BLOCKS_DIR_PATH . $style_path )
		);
	}
	wp_enqueue_style(
		'attire-blocks-both',
		ATTIRE_BLOCKS_DIR_URL . $style_path_both,
		null,
		filemtime( ATTIRE_BLOCKS_DIR_PATH . $style_path_both )
	);
	wp_enqueue_style( 'attire-blocks-global', ATTIRE_BLOCKS_DIR_URL . '/assets/css/main.css', [], filemtime( ATTIRE_BLOCKS_DIR_PATH . '/assets/css/main.css' ) );
}

add_action( 'enqueue_block_assets', __NAMESPACE__ . '\atbs_enqueue_frontend_assets' );
/**
 * Enqueue frontend JavaScript and CSS assets.
 */
function atbs_enqueue_frontend_assets() {

	// If in the backend, bail out.
	if ( is_admin() ) {
		return;
	}

	$script_path = '/assets/js/frontend.blocks.js';
	wp_enqueue_script( 'attire-blocks-frontend', ATTIRE_BLOCKS_DIR_URL . $script_path, [ 'jquery' ], filemtime( ATTIRE_BLOCKS_DIR_PATH . $script_path ) );
	wp_localize_script(
		'attire-blocks-frontend',
		'fe_data',
		array(
			'assets_url' => ATTIRE_BLOCKS_DIR_URL . 'assets',
			'home_url'   => home_url( '/' ),
		)
	);
}

add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\atbs_enqueue_customize_script' );
function atbs_enqueue_customize_script( $hook ) {
	wp_enqueue_script( 'atbs-customizer', ATTIRE_BLOCKS_DIR_URL . 'assets/js/customizer.js', [ 'jquery' ], filemtime( ATTIRE_BLOCKS_DIR_PATH . 'assets/js/customizer.js' ) );
}


function atbs_enqueue_client_scripts() {
	$disabled_assets = get_option( '__atbs_disabled_assets', [] );
	if ( $disabled_assets ) {
		$disabled_assets = json_decode( $disabled_assets );
	}

	$current_theme = wp_get_theme();
	//    Attire enqueues these assets
	if ( array_search( 'bootstrap_css', $disabled_assets ) === false ) {
		wp_enqueue_style( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/css/bootstrap.min.css' );

	}
	if ( array_search( 'font_awesome', $disabled_assets ) === false ) {
		wp_enqueue_style( 'attire-blocks-fontawesome', ATTIRE_BLOCKS_DIR_URL . 'lib/fontawesome/css/all.min.css' );

	}
	if ( array_search( 'bootstrap_js', $disabled_assets ) === false ) {
		wp_enqueue_script( 'attire-blocks-popper', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/popper.min.js' );
		wp_enqueue_script( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
	}
	wp_enqueue_style( 'attire-blocks-global', ATTIRE_BLOCKS_DIR_URL . '/assets/css/main.css', [], filemtime( ATTIRE_BLOCKS_DIR_PATH . '/assets/css/main.css' ) );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\atbs_enqueue_client_scripts' );

function atbs_targeted_link_rel( $rel_values ) {
	return '';
}

add_filter( 'wp_targeted_link_rel', __NAMESPACE__ . '\atbs_targeted_link_rel', 999 );

function atbs_admin_script( $hook ) {
//    setting script
	if ( ( 'toplevel_page_attireblocks' === $hook ) || ( 'toplevel_page_attireblocks_admin' === $hook ) ) {
		wp_enqueue_style( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/css/bootstrap.min.css' );
		wp_enqueue_style( 'attire-blocks-fontawesome', ATTIRE_BLOCKS_DIR_URL . 'lib/fontawesome/css/all.min.css' );
		wp_enqueue_script( 'attire-blocks-popper', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/popper.min.js' );
		wp_enqueue_script( 'attire-blocks-bootstrap', ATTIRE_BLOCKS_DIR_URL . 'lib/bootstrap/js/bootstrap.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'atbs-settings', ATTIRE_BLOCKS_DIR_URL . 'assets/css/settings.css', array(), filemtime( ATTIRE_BLOCKS_DIR_PATH . 'assets/css/settings.css' ) );
		wp_enqueue_script( 'atbs-common-admin', ATTIRE_BLOCKS_DIR_URL . 'assets/js/admin.script.js', [ 'jquery' ], filemtime( ATTIRE_BLOCKS_DIR_PATH . 'assets/js/admin.script.js' ) );
	}

//    layout importer for post/page editor
	if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
		wp_enqueue_style( 'attire-blocks-layout-importer', ATTIRE_BLOCKS_DIR_URL . 'assets/css/layout.importer.css' );
	}
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\atbs_admin_script' );