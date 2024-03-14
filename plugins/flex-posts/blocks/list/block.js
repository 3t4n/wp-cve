( function( blocks, components, editor, element, i18n ) {
	var el = element.createElement;
	var __ = i18n.__;

	var ServerSideRender;
	if ( typeof window.wp.serverSideRender !== 'undefined' ) {
		ServerSideRender = window.wp.serverSideRender;
	} else {
		ServerSideRender = components.ServerSideRender
	}

	var InspectorControls, InspectorAdvancedControls;
	if ( typeof window.wp.blockEditor !== 'undefined' ) {
		InspectorControls = window.wp.blockEditor.InspectorControls;
		InspectorAdvancedControls = window.wp.blockEditor.InspectorAdvancedControls;
	} else {
		InspectorControls = editor.InspectorControls;
		InspectorAdvancedControls = editor.InspectorAdvancedControls;
	}

	blocks.registerBlockType( 'flex-posts/list', {
		title: __( 'Flex Posts', 'flex-posts' ),

		icon: 'grid-view',

		category: 'widgets',

		supports: {
			align: [ 'wide', 'full' ],
			html: false,
		},

		attributes: {
			title: {
				type: 'string',
				default: ''
			},
			title_url: {
				type: 'string',
				default: ''
			},
			layout: {
				type: 'number',
				default: 1
			},
			post_type: {
				type: 'string',
				default: "post"
			},
			cat: {
				type: 'string',
				default: ''
			},
			tag: {
				type: 'string',
				default: ''
			},
			order_by: {
				type: 'string',
				default: 'newest'
			},
			number: {
				type: 'number',
				default: 4
			},
			skip: {
				type: 'number',
				default: 0
			},
			exclude_current: {
				type: 'boolean',
				default: false
			},
			show_image: {
				type: 'string',
				default: 'all'
			},
			image_size: {
				type: 'string',
				default: ''
			},
			image_size2: {
				type: 'string',
				default: ''
			},
			show_title: {
				type: 'boolean',
				default: true
			},
			show_categories: {
				type: 'boolean',
				default: false
			},
			show_author: {
				type: 'boolean',
				default: false
			},
			show_date: {
				type: 'boolean',
				default: true
			},
			show_comments: {
				type: 'boolean',
				default: true
			},
			show_excerpt: {
				type: 'boolean',
				default: false
			},
			excerpt_length: {
				type: 'number',
				default: 15
			},
			show_readmore: {
				type: 'boolean',
				default: false
			},
			readmore_text: {
				type: 'string',
				default: ''
			},
			pagination: {
				type: 'boolean',
				default: false
			},
			block_title_el: {
				type: 'string',
				default: ''
			},
			post_title_el: {
				type: 'string',
				default: ''
			}
		},

		edit: function( props ) {
			var attr = props.attributes;
			var layouts = [];
			var has_category_option = false;
			var has_post_tag_option = false;
			if ( typeof flex_posts.taxonomies[ attr.post_type ] !== 'undefined' ) {
				has_category_option = flex_posts.taxonomies[ attr.post_type ].indexOf( 'category' ) !== -1;
				has_post_tag_option = flex_posts.taxonomies[ attr.post_type ].indexOf( 'post_tag' ) !== -1;
			}
			if ( ! has_category_option ) {
				attr.cat = '';
			}
			if ( ! has_post_tag_option ) {
				attr.tag = '';
			}
			for ( i = 1; i <= flex_posts.layouts; i++ ) {
				layouts.push( { value: i, label: __( 'Layout', 'flex-posts' ) + ' ' + i } );
			}

			return [
				el( ServerSideRender, {
					block: 'flex-posts/list',
					attributes: props.attributes
				} ),
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						components.PanelBody,
						{
							title: __( 'General', 'flex-posts' ),
							initialOpen: true
						},
						el(
							components.TextControl,
							{
								type: 'text',
								label: __( 'Title', 'flex-posts' ),
								value: attr.title,
								onChange: function( val ) {
									props.setAttributes( { title: val } )
								}
							}
						),
						el(
							components.TextControl,
							{
								type: 'text',
								label: __( 'Title URL', 'flex-posts' ),
								value: attr.title_url,
								onChange: function( val ) {
									props.setAttributes( { title_url: val } )
								}
							}
						),
						el(
							components.SelectControl,
							{
								label: 'Layout',
								options: layouts,
								value: attr.layout,
								onChange: function( val ) {
									props.setAttributes( { layout: parseInt( val ) } )
								}
							}
						),
					)
				),
				el(
					InspectorControls,
					{},
					el(
						components.PanelBody,
						{
							title: __( 'Query', 'flex-posts' ),
							initialOpen: false
						},
						el(
							components.SelectControl,
							{
								label: 'Post Type',
								value: attr.post_type,
								options: flex_posts.post_types,
								onChange: function( val ) {
									props.setAttributes( { post_type: val } )
								}
							}
						),
						has_category_option && el(
							components.SelectControl,
							{
								label: __( 'Category', 'flex-posts' ),
								value: attr.cat,
								options: flex_posts.categories,
								onChange: function( val ) {
									props.setAttributes( { cat: val } )
								}
							}
						),
						has_post_tag_option && el(
							components.TextControl,
							{
								type: 'text',
								label: __( 'Tag(s)', 'flex-posts' ),
								value: attr.tag,
								onChange: function( val ) {
									props.setAttributes( { tag: val } )
								}
							}
						),
						el(
							components.SelectControl,
							{
								label: __( 'Order by', 'flex-posts' ),
								value: attr.order_by,
								options: flex_posts.order_by,
								onChange: function( val ) {
									props.setAttributes( { order_by: val } )
								}
							}
						),
						el(
							components.RangeControl,
							{
								label: __( 'Number of posts to show', 'flex-posts' ),
								value: attr.number,
								min: 1,
								onChange: function( val ) {
									props.setAttributes( { number: val } )
								}
							}
						),
						el(
							components.RangeControl,
							{
								label: __( 'Number of posts to skip', 'flex-posts' ),
								value: attr.skip,
								min: 0,
								onChange: function( val ) {
									props.setAttributes( { skip: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Exclude current post', 'flex-posts' ),
								checked: attr.exclude_current,
								onChange: function( val ) {
									props.setAttributes( { exclude_current: val } )
								}
							}
						),
					)
				),
				el(
					InspectorControls,
					{},
					el(
						components.PanelBody,
						{
							title: __( 'Display', 'flex-posts' ),
							initialOpen: false
						},
						el(
							components.SelectControl,
							{
								label: __( 'Show image on', 'flex-posts' ),
								value: attr.show_image,
								options: [
									{ value: 'all', label: __( 'All posts', 'flex-posts' ) },
									{ value: 'first', label: __( 'First post only', 'flex-posts' ) },
									{ value: 'none', label: __( 'None', 'flex-posts' ) }
								],
								onChange: function( val ) {
									props.setAttributes( { show_image: val } )
								}
							}
						),
						attr.show_image !== 'none' && ( attr.layout === 1 || attr.layout === 3 ) && el(
							components.SelectControl,
							{
								label: __( 'Thumbnail image size', 'flex-posts' ),
								value: attr.image_size,
								options: flex_posts.image_sizes,
								onChange: function( val ) {
									props.setAttributes( { image_size: val } )
								}
							}
						),
						attr.show_image !== 'none' && attr.layout !== 1 && el(
							components.SelectControl,
							{
								label: __( 'Medium image size', 'flex-posts' ),
								value: attr.image_size2,
								options: flex_posts.image_sizes,
								onChange: function( val ) {
									props.setAttributes( { image_size2: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show post title', 'flex-posts' ),
								checked: attr.show_title,
								onChange: function( val ) {
									props.setAttributes( { show_title: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show categories', 'flex-posts' ),
								checked: attr.show_categories,
								onChange: function( val ) {
									props.setAttributes( { show_categories: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show author', 'flex-posts' ),
								checked: attr.show_author,
								onChange: function( val ) {
									props.setAttributes( { show_author: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show date', 'flex-posts' ),
								checked: attr.show_date,
								onChange: function( val ) {
									props.setAttributes( { show_date: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show comments number', 'flex-posts' ),
								checked: attr.show_comments,
								onChange: function( val ) {
									props.setAttributes( { show_comments: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show excerpt', 'flex-posts' ),
								checked: attr.show_excerpt,
								onChange: function( val ) {
									props.setAttributes( { show_excerpt: val } )
								}
							}
						),
						attr.show_excerpt && el(
							components.RangeControl,
							{
								label: __( 'Excerpt length', 'flex-posts' ),
								value: attr.excerpt_length,
								min: 1,
								onChange: function( val ) {
									props.setAttributes( { excerpt_length: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show read more link', 'flex-posts' ),
								checked: attr.show_readmore,
								onChange: function( val ) {
									props.setAttributes( { show_readmore: val } )
								}
							}
						),
						attr.show_readmore && el(
							components.TextControl,
							{
								type: 'text',
								label: __( 'Read more text', 'flex-posts' ),
								value: attr.readmore_text,
								onChange: function( val ) {
									props.setAttributes( { readmore_text: val } )
								}
							}
						),
						el(
							components.CheckboxControl,
							{
								label: __( 'Show pagination', 'flex-posts' ),
								checked: attr.pagination,
								onChange: function( val ) {
									props.setAttributes( { pagination: val } )
								}
							}
						)
					)
				),
				el(
					InspectorAdvancedControls,
					{},
					el(
						'div',
						{},
						el(
							components.SelectControl,
							{
								label: __( 'Block Title HTML element', 'flex-posts' ),
								value: attr.block_title_el,
								options: flex_posts.title_el,
								onChange: function( val ) {
									props.setAttributes( { block_title_el: val } )
								}
							}
						)
					)
				),
				el(
					InspectorAdvancedControls,
					{},
					el(
						'div',
						{},
						el(
							components.SelectControl,
							{
								label: __( 'Post Title HTML element', 'flex-posts' ),
								value: attr.post_title_el,
								options: flex_posts.title_el,
								onChange: function( val ) {
									props.setAttributes( { post_title_el: val } )
								}
							}
						)
					)
				)
			];
		},

		save: function() {
			// Rendering in PHP
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.components,
	window.wp.editor,
	window.wp.element,
	window.wp.i18n
);
