( 
	function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;
	
	var build_category_options = function(categories) {
		var opts = [
			{
				label: 'All Categories',
				value: ''
			}
		];

		// build list of options from goals
		for( var i in categories ) {
			cat = categories[i];
			opts.push( 
			{
				label: cat.name,
				value: cat.slug
			});
		}
		return opts;
	};	

	var extract_label_from_options = function (opts, val) {
		var label = '';
		for (j in opts) {
			if ( opts[j].value == val ) {
				label = opts[j].label;
				break;
			}										
		}
		return label;
	};
	
	var checkbox_control = function (label, checked, onChangeFn) {
		// add checkboxes for which fields to display
		var controlOptions = {
			checked: checked,
			label: label,
			value: '1',
			onChange: onChangeFn,
		};	
		return el(  wp.components.CheckboxControl, controlOptions );
	};

	var update_paginate_panel = function () {
		setTimeout( function () {
			var field_groups =  jQuery('.janus_editor_field_group');
			field_groups.each(function () {
				field_group = jQuery(this);
				var val = field_group.find(':checked').val();
				if ( 'max' == val ) {
					field_group.find('.field_per_page').show();
					field_group.find('.field_count').hide();
				}
				else if ( 'paginate' == val ) {
					field_group.find('.field_per_page').hide();
					field_group.find('.field_count').show();
				}
				else {
					field_group.find('.field_per_page').hide();
					field_group.find('.field_count').hide();
				}			
				
				return true;
			});
		}, 100 );
	};
	
	var iconGroup = [];
	iconGroup.push(	el(
			'path',
			{ d: "M0 0h24v24H0z", fill: 'none' }
		)
	);
	iconGroup.push(	el(
			'path',
			{ d: "M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"}
		)
	);

	var iconEl = el(
		'svg', 
		{ width: 24, height: 24 },
		iconGroup
	);

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/block-api/
	 */
	registerBlockType( 'staff-directory-pro/staff-list', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Staff List' ),

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'company-directory',

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: wp.data.withSelect( function( select ) {
 					return {
						categories: select( 'core' ).getEntityRecords( 'taxonomy', 'staff-member-category', {
							order: 'asc',
							per_page: -1,
							orderby: 'id'
						})
					};
				} ) ( function( props ) {
						var retval = [];
						var inspector_controls = [],
							category = props.attributes.category || '',
							paginate = props.attributes.paginate || 'all',
							count = props.attributes.count || '',
							per_page = props.attributes.per_page || '',
							order = props.attributes.order || '',
							orderby = props.attributes.orderby || '',
							show_name = typeof(props.attributes.show_name) != 'undefined' ? props.attributes.show_name : true,
							show_title = typeof(props.attributes.show_title) != 'undefined' ? props.attributes.show_title : true,
							show_bio = typeof(props.attributes.show_bio) != 'undefined' ? props.attributes.show_bio : true,
							show_photo = typeof(props.attributes.show_photo) != 'undefined' ? props.attributes.show_photo : true,
							show_email = typeof(props.attributes.show_email) != 'undefined' ? props.attributes.show_email : true,
							show_phone = typeof(props.attributes.show_phone) != 'undefined' ? props.attributes.show_phone : true,
							show_address = typeof(props.attributes.show_address) != 'undefined' ? props.attributes.show_address : true,
							show_website = typeof(props.attributes.show_website) != 'undefined' ? props.attributes.show_website : true,
							show_department = typeof(props.attributes.show_department) != 'undefined' ? props.attributes.show_department : false,
							focus = props.isSelected;
							
			
						// add <select> to choose the Category
						var category_fields = [];
						var controlOptions = {
							label: __('Select a Category:'),
							value: category,
							onChange: function( newVal ) {
								props.setAttributes({
									category: newVal
								});
							},
							options: build_category_options(props.categories),
						};
					
						category_fields.push(
							el(  wp.components.SelectControl, controlOptions )
						);

						inspector_controls.push(							
							el (
								wp.components.PanelBody,
								{
									title: __('Category'),
									className: 'gp-panel-body',
									initialOpen: false,
								},
								category_fields
							)
						);
						
						// Order by fields
						var order_by_fields = [];
						var orderby_opts = [
							{
								label: 'Title',
								value: 'title',
							},
							{
								label: 'Random',
								value: 'rand',
							},
							{
								label: 'ID',
								value: 'id',
							},
							{
								label: 'Author',
								value: 'author',
							},
							{
								label: 'Name',
								value: 'name',
							},
							{
								label: 'Date',
								value: 'date',
							},
							{
								label: 'Last Modified',
								value: 'last_modified',
							},
							{
								label: 'Parent ID',
								value: 'parent_id',
							},
						];

						// add <select> to choose the Order By Field
						var controlOptions = {
							label: __('Order By:'),
							value: orderby,
							onChange: function( newVal ) {
								props.setAttributes({
									orderby: newVal
								});
							},
							options: orderby_opts,
						};

						order_by_fields.push(
							el(  wp.components.SelectControl, controlOptions )
						);

						var order_opts = [
							{
								label: 'Ascending (A-Z)',
								value: 'asc',
							},
							{
								label: 'Descending (Z-A)',
								value: 'desc',
							},
						];

						// add <select> to choose the Order (asc, desc)
						var controlOptions = {
							label: __('Order:'),
							value: order,
							onChange: function( newVal ) {
								props.setAttributes({
									order: newVal
								});
							},
							options: order_opts,
						};

						order_by_fields.push(
							el(  wp.components.SelectControl, controlOptions )
						);

						inspector_controls.push(							
							el (
								wp.components.PanelBody,
								{
									title: __('Order'),
									className: 'gp-panel-body',
									initialOpen: false,
								},
								order_by_fields
							)
						);


						// add checkboxes for which fields to display
						var display_fields = [];							
						display_fields.push( 
							checkbox_control( __('Name'), show_name, function( newVal ) {
								props.setAttributes({
									show_name: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Title'), show_title, function( newVal ) {
								props.setAttributes({
									show_title: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Bio'), show_bio, function( newVal, ev ) {
								props.setAttributes({
									show_bio: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Photo'), show_photo, function( newVal ) {
								props.setAttributes({
									show_photo: newVal,
								});
							})
						);
						display_fields.push( 
							checkbox_control( __('Email'), show_email, function( newVal ) {
								props.setAttributes({
									show_email: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Phone'), show_phone, function( newVal ) {
								props.setAttributes({
									show_phone: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Mailing Address'), show_address, function( newVal ) {
								props.setAttributes({
									show_address: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Website'), show_website, function( newVal ) {
								props.setAttributes({
									show_website: newVal,
								});
							})
						);

						display_fields.push( 
							checkbox_control( __('Department'), show_department, function( newVal ) {
								props.setAttributes({
									show_department: newVal,
								});
							})
						);

						inspector_controls.push( 
							el (
								wp.components.PanelBody,
								{
									title: __('Fields To Display'),
									className: 'gp-panel-body',
									initialOpen: false,
								},
								el('div', { className: 'janus_editor_field_group' }, display_fields)
							)
						);

						// add Staff Members Per Page options panel
						var per_page_fields = [];
						var per_page_opts = [
							{
								label: __('All On One Page'),
								value: 'all'
							},
							{
								label: __('Max Per Page'),
								value: 'max'
							},
						];

						var controlOptions = {
							label: __('Staff Members Per Page:'),
							onChange: function( newVal ) {
								props.setAttributes({
									paginate: newVal
								});
								update_paginate_panel();
							},
							options: per_page_opts,
							selected: paginate,
							className: 'field_paginate',
						};

						per_page_fields.push(
								el(  wp.components.RadioControl, controlOptions )
						);

						// add text input for Count
						var controlOptions = {
							label: __('Number To Show:'),
							value: count,
							className: 'field_count',
							onChange: function( newVal ) {
								props.setAttributes({
									count: newVal
								});
							},
						};

						per_page_fields.push( 
							el(  wp.components.TextControl, controlOptions )
						);

						// add text input for Per Page
						var controlOptions = {
							label: __('Number Per Page:'),
							value: per_page,
							className: 'field_per_page',
							onChange: function( newVal ) {
								props.setAttributes({
									per_page: newVal
								});
							},
						};

						per_page_fields.push( 
							el(  wp.components.TextControl, controlOptions )
						);

						inspector_controls.push( 
							el (
								wp.components.PanelBody,
								{
									title: __('Staff Members Per Page'),
									className: 'gp-panel-body',
									initialOpen: false,
									onToggle: update_paginate_panel,
								},
								el('div', { className: 'janus_editor_field_group janus_editor_field_group_no_heading' }, per_page_fields)
							)
						);
						

						retval.push(
							el( wp.editor.InspectorControls, {}, inspector_controls ) 
						);
						
						// show a box in the editor representing the block
						var inner_fields = [];
						if ( !! focus && false ) {
							retval.push( el('h3', { className: 'block-heading' }, __('Company Directory - Staff List') ) );
						} else {
							inner_fields.push( el('h3', { className: 'block-heading' }, 'Company Directory - Staff List') );
						}						
						
						//inner_fields.push( el('blockquote', { className: 'staff-list-placeholder' }, __('“Displays a list of Staff Members from your database.”') ) );
						retval.push( el('div', {'className': 'staff-directory-pro-editor-not-selected'}, inner_fields ) );
					
				return el( 'div', { className: 'staff-directory-pro-staff-list-editor'}, retval );
			} ),

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function() {
			return null;
		},
		attributes: {
			show_name: {
				type: 'boolean',
			},
			category: {
				type: 'string',
			},
			paginate: {
				type: 'string',
			},
			count: {
				type: 'string',
			},
			per_page: {
				type: 'string',
			},
			order: {
				type: 'string',
			},
			orderby: {
				type: 'string',
			},
			show_name: {
				type: 'boolean',
			},
			show_title: {
				type: 'boolean',
			},
			show_bio: {
				type: 'boolean',
			},
			show_photo: {
				type: 'boolean',
			},
			show_email: {
				type: 'boolean',
			},
			show_phone: {
				type: 'boolean',
			},
			show_address: {
				type: 'boolean',
			},
			show_website: {
				type: 'boolean',
			},
			show_department: {
				type: 'boolean',
			},
		},
		icon: iconEl,
	} );
} )(
	window.wp
);
