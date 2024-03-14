var el = wp.element.createElement,
registerBlockType = wp.blocks.registerBlockType,
ServerSideRender = wp.components.ServerSideRender,
TextControl = wp.components.TextControl,
RadioControl = wp.components.RadioControl,
SelectControl = wp.components.SelectControl,
TextareaControl = wp.components.TextareaControl,
CheckboxControl = wp.components.CheckboxControl;

registerBlockType( 'locatoraid/locatoraid-map', {
	title: 'Locatoraid Store Locator Map',
	description: 'Display Locatoraid store locator map',
	icon: 'location-alt',
	category: 'widgets',
	edit: function (props) {
		var ret = [];

		// ret.push( wp.element.createElement('h4',
			// { style: { margin: 0, padding: 0 } },
			// 'Locatoraid Map' // Block content
			// )
		// );

		var atts = locatoraidBlockShortcodeParams;

		var makeOnChange = function( key ){
			return function( value ){
				var attrs = {};
				attrs[ key ] = value;
				props.setAttributes( attrs );
				// console.log( key + '=' + value + '=>' + props.attributes[ key ] );
			};
		}

		var inputs = [];
		var ii = 0;
		while( ii < atts.length ){
			var key = atts[ii];
			inputs.push( wp.element.createElement( TextControl, {
				label: key,
				value: props.attributes[key],
				onChange: makeOnChange(key),
			})
			);
			ii++;
		}

		var toggler = el( 'summary', { style: {
			fontSize: '1.5em',
			padding: '.5em 0',
			cursor: 'pointer',
			width: '100%',
			// display: 'block',
			// border: 'red 1px solid',
			textDecoration: 'underline',
			textDecorationStyle: 'dotted',
		}}, 'Locatoraid Map' );

		ret.push( el('details', {}, toggler, inputs) );

		return ret;
	},
	save: function () {
		return null;
	},
});

registerBlockType( 'locatoraid/locatoraid-searchform', {
	title: 'Locatoraid Store Locator Search Form',
	description: 'Display Locatoraid store locator search form widget',
	icon: 'search',
	category: 'widgets',

	edit: function (props) {
		var targetOptions = [];
		for( var ii = 0; ii < locatoraidBlockSearchFormOptions.length; ii++ ){
			targetOptions[ ii ] = { label: locatoraidBlockSearchFormOptions[ii], value: locatoraidBlockSearchFormOptions[ii] };
		}

		var inputs = [];

		// not working
		// inputs.push(
			// wp.element.createElement( ServerSideRender, {
				// block: 'locatoraid/locatoraid-searchform',
				// attributes: props.attributes,
			// }),
		// );

		inputs.push(
			wp.element.createElement( SelectControl, {
				label: 'Target Page',
				value: props.attributes.target,
				options: targetOptions,
				onChange: (value) => {
					props.setAttributes({target: value});
				},
			})
		);

		inputs.push(
			wp.element.createElement( TextControl, {
				label: 'Title',
				value: props.attributes.label,
				onChange: (value) => {
					props.setAttributes({label: value});
				},
			}),
		);

		inputs.push(
			wp.element.createElement( TextControl, {
				label: 'Button Text',
				value: props.attributes.btn,
				onChange: (value) => {
					props.setAttributes({btn: value});
				},
			}),
		);

		var ret = [];

		var toggler = el( 'summary', { style: {
			fontSize: '1.5em',
			padding: '.5em 0',
			cursor: 'pointer',
			width: '100%',
			// display: 'block',
			// border: 'red 1px solid',
			textDecoration: 'underline',
			textDecorationStyle: 'dotted',
		}}, 'Locatoraid Search Form' );

		ret.push( el('details', {}, toggler, inputs) );
		return ret;
	},
	save: function () {
		return null;
	},
});