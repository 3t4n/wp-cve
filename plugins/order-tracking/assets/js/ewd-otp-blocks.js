var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.serverSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	InspectorControls = wp.blockEditor.InspectorControls,
	Localize = wp.i18n.__,
	ewdOtpBlocks = ewd_otp_blocks,
	existingLocations = ewdOtpBlocks.locationOptions;

registerBlockType( 'order-tracking/ewd-otp-display-tracking-form-block', {
	title: Localize( 'Tracking Form', 'order-tracking' ),
	icon: 'clipboard',
	category: 'ewd-otp-blocks',
	attributes: {
		show_orders: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Show Orders?', 'order-tracking' ),
					value: props.attributes.show_orders,
					options: [ {value: 'No', label: 'No'}, {value: 'Yes', label: 'Yes'} ],
					onChange: ( value ) => { props.setAttributes( { show_orders: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'order-tracking/ewd-otp-display-tracking-form-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'order-tracking/ewd-otp-display-customer-form-block', {
	title: Localize( 'Customer Form', 'order-tracking' ),
	icon: 'clipboard',
	category: 'ewd-otp-blocks',

	edit: function( props ) {
		var returnString = [];
		returnString.push( el( ServerSideRender, { 
			block: 'order-tracking/ewd-otp-display-customer-form-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'order-tracking/ewd-otp-display-sales-rep-form-block', {
	title: Localize( 'Sales Rep Form', 'order-tracking' ),
	icon: 'clipboard',
	category: 'ewd-otp-blocks',

	edit: function( props ) {
		var returnString = [];
		returnString.push( el( ServerSideRender, { 
			block: 'order-tracking/ewd-otp-display-sales-rep-form-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'order-tracking/ewd-otp-display-customer-order-form-block', {
	title: Localize( 'Customer Order Form', 'order-tracking' ),
	icon: 'clipboard',
	category: 'ewd-otp-blocks',
	attributes: {
		location: { type: 'string' },
		success_redirect_page: { type: 'string' }
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Starting Location', 'order-tracking' ),
					value: props.attributes.location,
					options: existingLocations,
					onChange: ( value ) => { props.setAttributes( { location: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Redirect URL', 'order-tracking' ),
					value: props.attributes.success_redirect_page,
					onChange: ( value ) => { props.setAttributes( { success_redirect_page: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'order-tracking/ewd-otp-display-customer-order-form-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'order-tracking/ewd-otp-order-number-search-block', {
	title: Localize( 'Order Number Search', 'order-tracking' ),
	icon: 'clipboard',
	category: 'ewd-otp-blocks',
	attributes: {
		tracking_page_url: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: Localize( 'Tracking Page URL', 'order-tracking' ),
					value: props.attributes.tracking_page_url,
					onChange: ( value ) => { props.setAttributes( { tracking_page_url: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'order-tracking/ewd-otp-order-number-search-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );