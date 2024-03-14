var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	RadioControl = wp.components.RadioControl,
	SelectControl = wp.components.SelectControl,
	TextareaControl = wp.components.TextareaControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'loancomparison/premium', {
	title: 'Comparisons',
	icon: 'list-view',
	category: 'widgets',
	edit: function( props ) {
		return [
			el( 'h2',
				{className: props.className,},
				'Loan Comparison Table ' + props.attributes.table
			),
			el( InspectorControls, {},
				el( SelectControl, {
						'type':'number',
						'label':'Table Number:',
						'value':props.attributes.table,
						'options': [
							{'label':'One','value':'1'},
							{'label':'Two','value':'2'},
							{'label':'Three','value':'3'},
							{'label':'Four','value':'4'},
							{'label':'Five','value':'5'},
							{'label':'Six','value':'6'},
							{'label':'Seven','value':'7'},
							{'label':'Eight','value':'8'},
							{'label':'Nine','value':'9'},
							{'label':'Ten','value':'8'},
						],
						onChange: ( option ) => { props.setAttributes( { table: option } ); }
					}
				),
			),
		];
	},
	save: function() {
		return null;
	},
} );