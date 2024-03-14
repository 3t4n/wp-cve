/**
 * Needed this section without connecting the external dependencies from the new wp scripts webpack config
 */

// const { __ } = window.wp.i18n;
// const { registerBlockType } = wp.blocks;

/**
 * externals connected
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
// import ServerSideRender from '@wordpress/server-side-render';

import AddToCartBlock from './block';

let trs_a2c_defaultContentOrder = [
	'title',
	'separator',
	'price',
	'quantity',
	'button',
];

let trs_a2c_defaultContentVisibility = {
	title: true,
	separator: true,
	price: true,
	quantity: true,
	button: true,
}

let trs_a2c_defaultButtonText = __( 'Add to cart', 'woocommerce' );

if ( global.A2C ) {
	let trs_a2c_buttonText = global.A2C.buttonText;
	if ( trs_a2c_buttonText && trs_a2c_buttonText !== undefined && trs_a2c_buttonText.length > 0 ) {
		trs_a2c_defaultButtonText = trs_a2c_buttonText;
	}
}

registerBlockType( 'add-to-cart-pro/a2cp', {
	title: 'AJAX Add to Cart Block',
	icon: 'cart',
	category: 'widgets',
	description: __(
		'Non-redirect add to cart button wherever you want!',
		'enhanced-ajax-add-to-cart-wc'
	),
	supports: {
		html: false,
		align: true,
	},

	attributes: {
		editMode: {
			type: 'boolean',
			default: true,
		},
		isPreview: {
			type: 'boolean',
			default: false,
		},
		contentVisibility: {
			type: 'object',
			default: trs_a2c_defaultContentVisibility,
		},
		contentOrder: {
			type: 'array',
			default: trs_a2c_defaultContentOrder,
		},
		buttonText: {
			type: 'string',
			default: trs_a2c_defaultButtonText,
		},
		quantity: {
			type: 'object',
			default: {
				default: 1,
				min: 1,
				max: -1,
			},
		},
		titleAction: {
			type: 'string',
			default: '',
		},
		titleType: {
			type: 'string',
			default: 'full',
		},
		products: {
			type: 'array',
			default: [],
		},
		variations: {
			type: 'array',
			default: [],
		},
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
	 *
	 * @param {Object} [props] Properties passed from the editor.
	 * @return {Element}       Element to render.
	 */
	// edit: function( props ) {
	// 	return el(
	// 		'p',
	// 		{ className: props.className },
	// 		__( 'Hello from the editor!', 'enhanced-ajax-add-to-cart-for-wc' )
	// 	);
	// },
	// edit( props ) {
	// 	// return <div><p>block should be here</p></div>;
	// 	return <AddToCartBlock { ...props } />;
	// },
	// edit: function( props ) {
	// 	return <AddToCartBlock { ...props } />;
	// },
	edit: ( props ) => {
		// return (
		// 	el( AddToCartBlock, { attributes, setAttributes } )
		// );
		return <AddToCartBlock { ...props } isEditor={ true } />;
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into `post_content`.
	 *
	 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
	 *
	 * @return {Element}       Element to render.
	 */
	// save: function() {
	// 	return el(
	// 		'p',
	// 		{},
	// 		__( 'Hello from the saved content!', 'enhanced-ajax-add-to-cart-for-wc' )
	// 	);
	// },
	save( attributes ) {
		// return <AddToCartBlock { ...attributes } />;
		return null;
	},
} );
