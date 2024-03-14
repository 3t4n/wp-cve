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
	
	var iconGroup = [];
	iconGroup.push(	el(
			'path',
			{ d: "M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"}
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
	registerBlockType( 'staff-directory-pro/search-staff', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Search Staff' ),

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
						posts: select( 'core' ).getEntityRecords( 'postType', 'staff-member', {
							orderBy: 'title',
							order: 'asc',
							per_page: -1,						
						} )
					};
				} ) ( function( props ) {
						var retval = [],
							inspector_controls = [],
							primary_fields = [],
							id = props.attributes.id || '',
							mode = 'basic',
							form_title = props.attributes.form_title || '',
							focus = props.isSelected;
							
						// add text input for Form Title						
						var controlOptions = {
							label: __('Form Title:'),
							value: form_title,
							className: 'form_title',
							onChange: function( newVal ) {
								props.setAttributes({
									form_title: newVal
								});
							},
						};

						primary_fields.push( 
							el(  wp.components.TextControl, controlOptions )
						);
						
						inspector_controls.push( 
							el (
								wp.components.PanelBody,
								{
									title: __('Options'),
									className: 'gp-panel-body',
									initialOpen: true,
									/* onToggle: update_paginate_panel, */
								},
								el('div', { className: 'janus_editor_field_group janus_editor_field_group_no_heading' }, primary_fields)
							)
						);
						
						retval.push(
							el( wp.editor.InspectorControls, {}, inspector_controls ) 
						);

						// show a box in the editor representing the block
						var inner_fields = [];
						inner_fields.push( el('h3', { className: 'block-heading' }, 'Company Directory - Search Form') );
						retval.push( el('div', {'className': 'staff-directory-pro-editor-not-selected'}, inner_fields ) );
					
				return el( 'div', { className: 'staff-directory-pro-search-staff-editor'}, retval );
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
			form_title: {
				type: 'string',
			},
			mode: {
				type: 'string',
			},
		},
		icon: iconEl,
	} );
} )(
	window.wp
);
