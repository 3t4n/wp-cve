/**
 * Main iubenda legal block functions
 *
 * @package  Iubenda
 */

( function ( blocks, editor, element, components, _, blockEditor ) {
	var el            = element.createElement;
	var RichText      = blockEditor.RichText;
	var useBlockProps = blockEditor.useBlockProps;

	const iconEl = el(
		'svg',
		{},
		el(
			'path',
			{
				fill:"#1CC691" ,
				d: "M 11.6671 0 c 3.1311 0 5.6675 2.5268 5.6675 5.6464 c 0 1.3892 -0.5031 2.6608 -1.3408 3.6454 L 17.1974 25.5634 H 6.4376 l 1.1666 -15.9874 A 5.6025 5.6025 90 0 1 6 5.6468 C 6 2.5268 8.5364 0 11.6671 0 z m 1.0762 11.8411 l -1.865 1.8653 v 7.38 h 1.865 V 11.8411 z M 11.6671 4.1886 c -0.7528 0 -1.3628 0.6142 -1.3628 1.3723 c 0 0.7576 0.61 1.372 1.3628 1.372 s 1.3631 -0.6142 1.3631 -1.372 c 0 -0.7582 -0.6104 -1.3723 -1.3631 -1.3723 z"
			}
		)
	);

	blocks.registerBlockType(
		iub_block_js_vars.block_name,
		{
			title: 'Iubenda',
			icon: iconEl,
			category: 'layout',
			attributes: {
				title: {
					type: 'string',
					default: iub_block_js_vars.iub_legal_block_short_title
				},
			},
			edit: function ( props ) {
				var attributes = props.attributes;

				return el(
					'div',
					useBlockProps( { className: props.className } ),
					el(
						RichText,
						{
							tagName: 'p',
							placeholder: 'Iubenda',
							value: attributes.title,
							onChange: function ( value ) {
								props.setAttributes( { title: value } );
							},
						}
					),
					el( 'p', {}, '' ),
				);
			},
			save: function ( props ) {
				var attributes = props.attributes;

				return el(
					'div',
					useBlockProps.save( { className: props.className } ),
					el(
						RichText.Content,
						{
							tagName: 'p',
							value: attributes.title,
						}
					),
					el( 'p', {}, '[' + iub_block_js_vars.iub_legal_block_shortcode + ']' ),
				);
			},
		}
	);
} )(
	window.wp.blocks,
	window.wp.editor,
	window.wp.element,
	window.wp.components,
	window._,
	window.wp.blockEditor
);
