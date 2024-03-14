<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 1.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue block assets for both frontend + backend.
 *
 * `wp-blocks`: includes block type registration and related functions.
 *
 * @since 1.6
 */
function drop_shadow_block_assets() {
	// Styles.
	wp_enqueue_style(
		'drop_shadow_block-style-css', // Handle.
		plugins_url( 'block/blocks.style.build.css', dirname( __FILE__ ) ), // Block style CSS.
		array( 'wp-editor' ),
		DropShadowBoxes::$version
	);
}

// Hook: Frontend assets.
add_action( 'enqueue_block_assets', 'drop_shadow_block_assets' );

/**
 * Enqueue Gutenberg block assets for backend editor.
 *
 * `wp-blocks`: includes block type registration and related functions.
 * `wp-element`: includes the WordPress Element abstraction for describing the structure of your blocks.
 * `wp-i18n`: To internationalize the block's text.
 *
 * @since 1.0.0
 */
function drop_shadow_block_editor_assets() {
	// Scripts.
	wp_enqueue_script(
		'drop_shadow_block-js', // Handle.
		plugins_url( '/block/blocks.build.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		DropShadowBoxes::$version
	);

	// Styles.
	/*
	wp_enqueue_style(
		'drop_shadow_block-editor-css', // Handle.
		plugins_url( 'block/blocks.editor.build.css', dirname( __FILE__ ) ), // Block editor CSS.
		array( 'wp-edit-blocks' ),
		DropShadowBoxes::$version
	);
*/
	$locale_data = drop_shadow_block_get_jed_locale_data( 'drop-shadow-boxes' );
	wp_add_inline_script(
		'wp-editor',
		'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ', "drop-shadow-boxes" );',
		'after'
	);
}

// Hook: Editor assets.
add_action( 'enqueue_block_editor_assets', 'drop_shadow_block_editor_assets' );

/**
 * Returns Jed-formatted localization data.
 *
 * @since 0.1.0
 *
 * @param  string $domain Translation domain.
 *
 * @return array
 */
function drop_shadow_block_get_jed_locale_data( $domain ) {
	$translations = get_translations_for_domain( $domain );

	$locale = array(
		'' => array(
			'domain' => $domain,
			'lang'   => is_admin() ? get_user_locale() : get_locale(),
		),
	);

	if ( ! empty( $translations->headers['Plural-Forms'] ) ) {
		$locale['']['plural_forms'] = $translations->headers['Plural-Forms'];
	}

	foreach ( $translations->entries as $msgid => $entry ) {
		$locale[ $msgid ] = $entry->translations;
	}

	return $locale;
}
