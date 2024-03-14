<?php
/**
 * Main file for assets generation.
 *
 * @package EditorPlus
 */

require_once EDPL_EDITORPLUS_PLUGIN_DIR . 'includes/utils.php';

add_action(
	'init',
	function () {

		$post_types = get_post_types(
			array(
				'_builtin' => false,
			),
			'names',
			'and'
		);

		$post_types['post'] = 'post';

		foreach ( $post_types as $post_type ) :

			add_post_type_support( $post_type, array( 'custom-fields' ) );

	endforeach;
	}
);
