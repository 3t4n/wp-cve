(function (wp) {
	var el = wp.element.createElement,
		registerBlockType = wp.blocks.registerBlockType,
		ServerSideRender = wp.components.ServerSideRender,
		TextControl = wp.components.TextControl,
		InspectorControls = wp.editor.InspectorControls;

	// Visit https://wordpress.org/gutenberg/handbook/block-api/ to learn about Block API
	wp.blocks.registerBlockType('adsense-in-post-ads-by-oizuled/adsense-ad-a', {
		title: 'AdSense Ad A',

		category: 'widgets',

		// Remove to make block editable in HTML mode.
		supportHTML: false,

		/*
		 * In most other blocks, you'd see an 'attributes' property being defined here.
		 * We've defined attributes in the PHP, that information is automatically sent
		 * to the block editor, so we don't need to redefine it here.
		 */

		/**
		 * Called when Gutenberg initially loads the block.
		 */
		edit: function (props) {
			return[
				'Your ad will be displayed here.'
			]
		},
		/**
		 * Called when Gutenberg "saves" the block to post_content
		 */
		save: function (props) {
			return null;
		}
	});
})(
	window.wp
);