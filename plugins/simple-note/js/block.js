( function( blocks, editor, i18n, element ) {
	var el = element.createElement;
	var __ = i18n.__;
	var RichText = wp.blockEditor.RichText;

	blocks.registerBlockType( 'simple-note/info', {
		title: __( 'Info', 'simple-note' ),
		description: __( 'Write info note', 'simple-note' ),
		icon: {
				background: '#bde5f8',
				foreground: '#00529b',
				src: 'info',
			},
		category: 'common',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'div',
			},
		},

		example: {
			attributes: {
				content: __( 'This is example info note', 'simple-note' ),
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			return el( RichText, {
				tagName: 'div',
				className: props.className,
				onChange: onChangeContent,
				value: content,
			} );
		},

		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'div',
				value: props.attributes.content,
			} );
		},
	} );


	blocks.registerBlockType( 'simple-note/success', {
		title: __( 'Success', 'simple-note' ),
		description: __( 'Write success note', 'simple-note' ),
		icon: {
				background: '#dff2bf',
				foreground: '#4f8a10',
				src: 'yes-alt',
			},
		category: 'common',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'div',
			},
		},

		example: {
			attributes: {
				content: __( 'This is example success note', 'simple-note' ),
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			return el( RichText, {
				tagName: 'div',
				className: props.className,
				onChange: onChangeContent,
				value: content,
			} );
		},

		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'div',
				value: props.attributes.content,
			} );
		},
	} );


	blocks.registerBlockType( 'simple-note/warning', {
		title: __( 'Warning', 'simple-note' ),
		description: __( 'Write warning note', 'simple-note' ),
		icon: {
				background: '#feefb3',
				foreground: '#9f6000',
				src: 'warning',
			},
		category: 'common',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'div',
			},
		},

		example: {
			attributes: {
				content: __( 'This is example warning note', 'simple-note' ),
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			return el( RichText, {
				tagName: 'div',
				className: props.className,
				onChange: onChangeContent,
				value: content,
			} );
		},

		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'div',
				value: props.attributes.content,
			} );
		},
	} );


	blocks.registerBlockType( 'simple-note/error', {
		title: __( 'Error', 'simple-note' ),
		description: __( 'Write error note', 'simple-note' ),
		icon: {
				background: '#ffccba',
				foreground: '#d63301',
				src: 'dismiss',
			},
		category: 'common',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'div',
			},
		},

		example: {
			attributes: {
				content: __( 'This is example error note', 'simple-note' ),
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			return el( RichText, {
				tagName: 'div',
				className: props.className,
				onChange: onChangeContent,
				value: content,
			} );
		},

		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'div',
				value: props.attributes.content,
			} );
		},
	} );


	blocks.registerBlockType( 'simple-note/quote', {
		title: __( 'Quote', 'simple-note' ),
		description: __( 'Write quote note', 'simple-note' ),
		icon: {
				background: '#eff1f5',
				foreground: '#222222',
				src: 'admin-comments',
			},
		category: 'common',

		attributes: {
			content: {
				type: 'array',
				source: 'children',
				selector: 'div',
			},
		},

		example: {
			attributes: {
				content: __( 'This is example quote note', 'simple-note' ),
			},
		},

		edit: function( props ) {
			var content = props.attributes.content;
			function onChangeContent( newContent ) {
				props.setAttributes( { content: newContent } );
			}

			return el( RichText, {
				tagName: 'div',
				className: props.className,
				onChange: onChangeContent,
				value: content,
			} );
		},

		save: function( props ) {
			return el( RichText.Content, {
				tagName: 'div',
				value: props.attributes.content,
			} );
		},
	} );

} )( window.wp.blocks, window.wp.editor, window.wp.i18n, window.wp.element );