var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.serverSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	BaseControl = wp.components.BaseControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.blockEditor.InspectorControls,
	Localize = wp.i18n.__,
	ewdUfaqBlocks = ewd_ufaq_blocks,
	faqCategories = ewdUfaqBlocks.faqCategoryOptions;

registerBlockType( 'ultimate-faqs/ewd-ufaq-display-faq-block', {
	title: Localize( 'Display FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
		group_by_category: { type: 'string' },
		faq_accordion: { type: 'string' },
		category_accordion: { type: 'string' },
		include_category_array: { type: 'object', default: {} },
		include_category: { type: 'string' },
		exclude_category: { type: 'string' },
		post__in_string: { type: 'string' },
	},

	edit: function( props ) {

		var include_category_array = props.attributes.include_category_array;

		// convert from the old include_category attribute to the new include_category_array attribute
		if ( props.attributes.hasOwnProperty( 'include_category' ) && props.attributes.include_category.length ) { 
			
			var old_categories = props.attributes.include_category.split( ',' );
			
			jQuery( old_categories ).each( function( index, el ) {

				include_category_array[ jQuery.trim( el ) ] = 'true';
			} );
			
			props.setAttributes( { include_category_array: include_category_array } );

			props.setAttributes( { include_category: '' } );
		}

		var checkboxes = faqCategories.map( function( term ) {
			var termLabel = term.label;
			var termSlug = term.slug;
			
			return el( CheckboxControl, {
				label: termLabel,
				checked: include_category_array[termSlug],
				onChange: updateFAQCategories.bind( termSlug ),
			} );
		} );

		function updateFAQCategories( checked ) {
			var copy = Object.assign( {}, include_category_array );
			copy[this] = checked;
			props.setAttributes( { include_category_array: copy } );
		}

		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					help: Localize( 'The default of -1 means to display all FAQs.', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Group FAQs by Category', 'ultimate-faqs' ),
					value: props.attributes.group_by_category,
					options: [ {value: '', label: 'Default (from settings)'}, {value: 'yes', label: 'Yes'}, {value: 'no', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { group_by_category: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'FAQ Accordion', 'ultimate-faqs' ),
					value: props.attributes.faq_accordion,
					options: [ {value: '', label: 'Default (from settings)'}, {value: 'yes', label: 'Yes'}, {value: 'no', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { faq_accordion: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Category Accordion', 'ultimate-faqs' ),
					value: props.attributes.category_accordion,
					options: [ {value: '', label: 'Default (from settings)'}, {value: 'yes', label: 'Yes'}, {value: 'no', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { category_accordion: value } ); },
				} ),
				el(
					BaseControl, {
						label: Localize( 'Categories to Include', 'ultimate-faqs' ),
					}, 
					checkboxes
				),
				el( TextControl, {
					label: Localize( 'Exclude Category', 'ultimate-faqs' ),
					help: Localize( 'Comma-separated list of category IDs you\'d like to exclude.', 'ultimate-faqs' ),
					value: props.attributes.exclude_category,
					onChange: ( value ) => { props.setAttributes( { exclude_category: value } ); },
				} ),
				el( TextControl, {
					label: Localize( 'FAQ IDs', 'ultimate-faqs' ),
					help: Localize( 'Comma-separated list of IDs, if you\'d like to display specific FAQs.', 'ultimate-faqs' ),
					value: props.attributes.post__in_string,
					onChange: ( value ) => { props.setAttributes( { post__in_string: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-display-faq-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-search-block', {
	title: Localize( 'Search FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		include_category_array: { type: 'object', default: {} },
		include_category: { type: 'string' },
		exclude_category: { type: 'string' },
		show_on_load: { type: 'string' },
	},

	edit: function( props ) {

		var include_category_array = props.attributes.include_category_array;

		// convert from the old include_category attribute to the new include_category_array attribute
		if ( props.attributes.hasOwnProperty( 'include_category' ) && props.attributes.include_category.length ) { 
			
			var old_categories = props.attributes.include_category.split( ',' );
			
			jQuery( old_categories ).each( function( index, el ) {

				include_category_array[ el ] = 'true';
			} );
			
			props.setAttributes( { include_category_array: include_category_array } );

			props.setAttributes( { include_category: '' } );
		}

		var checkboxes = faqCategories.map( function( term ) {
			var termLabel = term.label;
			var termSlug = term.slug;
			
			return el( CheckboxControl, {
				label: termLabel,
				checked: include_category_array[termSlug],
				onChange: updateFAQCategories.bind( termSlug ),
			} );
		} );

		function updateFAQCategories( checked ) {
			var copy = Object.assign( {}, include_category_array );
			copy[this] = checked;
			props.setAttributes( { include_category_array: copy } );
		}

		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el(
					BaseControl, {
						label: Localize( 'Categories to Include', 'ultimate-faqs' ),
					}, 
					checkboxes
				),
				el( TextControl, {
					label: Localize( 'Exclude Category', 'ultimate-faqs' ),
					value: props.attributes.exclude_category,
					onChange: ( value ) => { props.setAttributes( { exclude_category: value } ); },
				} ),
				el( SelectControl, {
					label: Localize( 'Show all FAQs on Page Load?', 'ultimate-faqs' ),
					value: props.attributes.show_on_load,
					options: [ {value: '', label: 'No'}, {value: 'Yes', label: 'Yes'} ],
					onChange: ( value ) => { props.setAttributes( { show_on_load: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-search-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-submit-faq-block', {
	title: Localize( 'Submit FAQ', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-submit-faq-block',
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-recent-faqs-block', {
	title: Localize( 'Recent FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-recent-faqs-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'ultimate-faqs/ewd-ufaq-popular-faqs-block', {
	title: Localize( 'Popular FAQs', 'ultimate-faqs' ),
	icon: 'editor-help',
	category: 'ewd-ufaq-blocks',
	attributes: {
		post_count: { 
			type: 'string',
			default: -1
		},
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					type: 'number',
					label: Localize( 'Number of FAQs', 'ultimate-faqs' ),
					value: props.attributes.post_count,
					onChange: ( value ) => { props.setAttributes( { post_count: value } ); },
				} )
			),
		);
		returnString.push( el( ServerSideRender, { 
			block: 'ultimate-faqs/ewd-ufaq-popular-faqs-block',
			attributes: props.attributes
		} ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );


