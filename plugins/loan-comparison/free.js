var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	RadioControl = wp.components.RadioControl,
	SelectControl = wp.components.SelectControl,
	TextareaControl = wp.components.TextareaControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'loancomparison/free', {
	title: 'Comparisons',
	icon: 'list-view',
	category: 'widgets',
	edit: function( props ) {
		return [
			el( 'h2',
				{className: props.className,},
				'Loan Comparisons'
			)
		];
	},
	save: function() {
		return null;
	},
} );