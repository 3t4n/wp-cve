var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.serverSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	InspectorControls = wp.blockEditor.InspectorControls,
	Localize = wp.i18n.__,
	ewdUpcpBlocks = ewd_upcp_blocks,
	existingCatalogs = ewdUpcpBlocks.catalogOptions;

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-display-catalog-block', {
	title: Localize( 'Display Product Catalog', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		id: { type: 'string' },
		sidebar: { type: 'string' },
		starting_layout: { type: 'string' },
		excluded_layouts: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Which Catalog?', 'ultimate-product-catalogue' ),
					value: props.attributes.id,
					options: existingCatalogs,
					onChange: ( value ) => { props.setAttributes( { id: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Sidebar', 'ultimate-product-catalogue' ),
					value: props.attributes.sidebar,
					options: [ {value: 'Yes', label: 'Yes'}, {value: 'No', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { sidebar: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Starting Layout', 'ultimate-product-catalogue' ),
					value: props.attributes.starting_layout,
					options: [ {value: 'Thumbnail', label: 'Thumbnail'}, {value: 'Detail', label: 'Detail'}, {value: 'List', label: 'List'} ],
					onChange: ( value ) => { props.setAttributes( { starting_layout: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Excluded Layouts (e.g. "List" or "Thumbnail,List")', 'ultimate-product-catalogue' ),
					value: props.attributes.excluded_layouts,
					onChange: ( value ) => { props.setAttributes( { excluded_layouts: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-display-catalog-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-insert-products-block', {
	title: Localize( 'Insert Products', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		catalogue_id: { type: 'string' },
		catalogue_url: { type: 'string' },
		product_count: { type: 'string' },
		product_ids: { type: 'string' },
		category_id: { type: 'string' },
		subcategory_id: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Which Catalog?', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_id,
					options: existingCatalogs,
					onChange: ( value ) => { props.setAttributes( { catalogue_id: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Catalog URL', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_url,
					onChange: ( value ) => { props.setAttributes( { catalogue_url: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Number of Products', 'ultimate-product-catalogue' ),
					value: props.attributes.product_count,
					onChange: ( value ) => { props.setAttributes( { product_count: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'IDs of Proudcts to Include', 'ultimate-product-catalogue' ),
					value: props.attributes.product_ids,
					onChange: ( value ) => { props.setAttributes( { product_ids: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'IDs of Category(ies) to Include', 'ultimate-product-catalogue' ),
					value: props.attributes.category_id,
					onChange: ( value ) => { props.setAttributes( { category_id: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'IDs of Sub-Category(ies) to Include', 'ultimate-product-catalogue' ),
					value: props.attributes.subcategory_id,
					onChange: ( value ) => { props.setAttributes( { subcategory_id: value } ); },
				} ),
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-insert-products-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-popular-products-block', {
	title: Localize( 'Popular Products', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		catalogue_id: { type: 'string' },
		catalogue_url: { type: 'string' },
		product_count: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Which Catalog?', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_id,
					options: existingCatalogs,
					onChange: ( value ) => { props.setAttributes( { catalogue_id: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Catalog URL', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_url,
					onChange: ( value ) => { props.setAttributes( { catalogue_url: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Number of Products', 'ultimate-product-catalogue' ),
					value: props.attributes.product_count,
					onChange: ( value ) => { props.setAttributes( { product_count: value } ); },
				} ),
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-popular-products-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-recent-products-block', {
	title: Localize( 'Recent Products', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		catalogue_id: { type: 'string' },
		catalogue_url: { type: 'string' },
		product_count: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Which Catalog?', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_id,
					options: existingCatalogs,
					onChange: ( value ) => { props.setAttributes( { catalogue_id: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Catalog URL', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_url,
					onChange: ( value ) => { props.setAttributes( { catalogue_url: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Number of Products', 'ultimate-product-catalogue' ),
					value: props.attributes.product_count,
					onChange: ( value ) => { props.setAttributes( { product_count: value } ); },
				} ),
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-recent-products-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-random-products-block', {
	title: Localize( 'Random Products', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		catalogue_id: { type: 'string' },
		catalogue_url: { type: 'string' },
		product_count: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: Localize( 'Which Catalog?', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_id,
					options: existingCatalogs,
					onChange: ( value ) => { props.setAttributes( { catalogue_id: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Catalog URL', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_url,
					onChange: ( value ) => { props.setAttributes( { catalogue_url: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Number of Products', 'ultimate-product-catalogue' ),
					value: props.attributes.product_count,
					onChange: ( value ) => { props.setAttributes( { product_count: value } ); },
				} ),
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-random-products-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-product-catalogue/ewd-upcp-search-block', {
	title: Localize( 'Product Search', 'ultimate-product-catalogue' ),
	icon: 'feedback',
	category: 'ewd-upcp-blocks',
	attributes: {
		catalogue_url: { type: 'string' },
		search_label: { type: 'string' },
		search_placeholder: { type: 'string' },
		submit_label: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: Localize( 'Catalog URL', 'ultimate-product-catalogue' ),
					value: props.attributes.catalogue_url,
					onChange: ( value ) => { props.setAttributes( { catalogue_url: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Search Label', 'ultimate-product-catalogue' ),
					value: props.attributes.search_label,
					onChange: ( value ) => { props.setAttributes( { search_label: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Search Placeholder', 'ultimate-product-catalogue' ),
					value: props.attributes.search_placeholder,
					onChange: ( value ) => { props.setAttributes( { search_placeholder: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'Submit Label', 'ultimate-product-catalogue' ),
					value: props.attributes.submit_label,
					onChange: ( value ) => { props.setAttributes( { submit_label: value } ); },
				} ),
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-product-catalogue/ewd-upcp-search-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );
