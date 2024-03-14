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
	
	var build_post_options = function(posts) {
		var opts = [
			{
				label: __('Select a Staff Member...'),
				value: ''
			}
		];

		// build list of options from goals
		for( var i in posts ) {
			post = posts[i];
			opts.push( 
			{
				label: post.title.rendered || __('(no title)'),
				value: post.id
			});
		}
		return opts;
	};
	
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
				value: cat.id
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
	
	var generate_post_output = function(id, post, props) {
		var post_elements = [],
			post_output,
			//show_photo = typeof(last_show_photo[id]) != 'undefined' ? last_show_photo[id] : true
			show_photo = props.attributes.show_photo,
			div_class = '';

		post_elements.push( el('p', {}, el('strong', {}, post.title.rendered)) );
		
		if ( props.attributes.show_title && post.metadata.title ) {
			post_elements.push( el('p', {}, post.metadata.title) );
		}
		
		if ( show_photo && post.featured_image_src ) {
			post_elements.push( el('img', { src: post.featured_image_src, className: 'staff-directory-pro-block-featured-image' }) );
			div_class = 'has_photo';
		} else {
			div_class = 'no_photo';
		}
		
		post_output = el('div', { className: div_class }, post_elements);
		return post_output;
	};
	
	var load_staff_member_data = function(id, props) {
		var now = new Date();
		if ( typeof(post_cache[id]) != 'undefined' 
			 && typeof(post_cache[id].age) != 'undefined' 
			 && typeof(post_cache[id].data) != 'undefined' 
			 && ( (now.getTime() - post_cache[id].age.getTime() ) < cache_expiration) ) {
				 
				props.setAttributes({
					post_output: post_cache[id].data
				});
				
			 }
		else {
			
			wp.apiFetch( { path: '/wp/v2/staff-member/' + Number.parseInt(id) } ).then( post => {
				var output = generate_post_output(id, post, props);
				post_cache[id] = [];
				post_cache[id].data = output;
				post_cache[id].age = now;
				props.setAttributes({
					post_output: output
				});
			} );		
		}
	};
	
	var last_fetched_id = 0;
	var last_show_photo = [];
	var post_cache = [];
	var cache_expiration = 5000; // 5s

	var iconGroup = [];
	iconGroup.push(	el(
			'path',
			{ d: "M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"}
		)
	);
	iconGroup.push(	el(
			'path',
			{ d: "M0 0h24v24H0z", fill: 'none' }
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
	registerBlockType( 'staff-directory-pro/single-staff', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Staff Member' ),

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
/* 					return {
						categories: select( 'core' ).getEntityRecords( 'taxonomy', 'staff-member-category', {
							order: 'asc',
							per_page: -1,
							orderby: 'id'
						})
					};
 */					return {
						posts: select( 'core' ).getEntityRecords( 'postType', 'staff-member', {
							orderBy: 'title',
							order: 'asc',
							per_page: -1,
						} )
					};
				} ) ( function( props ) {
						var retval = [];
						var inspector_controls = [],
							id = props.attributes.id || '',
							post_output = props.attributes.post_output || '',
							post = props.attributes.post || [],
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
							
			
						var primary_fields = [];
						props.setAttributes({
							show_address: show_address,
							show_bio: show_bio,
							show_photo: show_photo,
							show_title: show_title,
						});
						load_staff_member_data(id, props);
						
						// add <select> to choose the Staff Member
						var opts = build_post_options(props.posts);
						var controlOptions = {
							label: __('Staff Member:'),
							value: id,
							onChange: function( newVal ) {
								props.setAttributes({
									id: newVal,
								});
								if ( newVal ) {
									load_staff_member_data(newVal, props);
								} else {
									props.setAttributes({
										post_output: '',
									});
								}
							},
							options: opts,
						};
					
						primary_fields.push(
							el(  wp.components.SelectControl, controlOptions )
						);

						inspector_controls.push(							
							el (
								wp.components.PanelBody,
								{
									title: __('Staff Member To Display'),
									className: 'gp-panel-body',
									initialOpen: true,
								},
								primary_fields
							)
						);
						
						retval.push(
							el( wp.editor.InspectorControls, {}, inspector_controls ) 
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
								if (props.attributes.id) {
									last_show_photo[props.attributes.id] = newVal;
								}
								post_cache[props.attributes.id] = [];
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
						

						// show a box in the editor representing the block
						var inner_fields = [];
						if ( !id ) {
							inner_fields.push( el('h3', { className: 'block-heading' }, 'Company Directory - Staff Member') );
						}						
						
						//inner_fields.push( el('blockquote', { className: 'single-staff-placeholder' }, __('“Displays a single Staff Member from your database.”') ) );
						inner_fields.push( post_output );
						retval.push( el('div', {'className': 'staff-directory-pro-editor-not-selected'}, inner_fields ) );
					
				return el( 'div', { className: 'staff-directory-pro-single-staff-editor'}, retval );
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
			id: {
				type: 'string',
			},
			post_output: {
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
