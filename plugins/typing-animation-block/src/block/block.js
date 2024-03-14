/**
 * BLOCK: typing-animation-block
 *
 * Registering a basic block with Gutenberg.
 */

import './editor.scss';

import Edit from './edit';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'cgb/block-typing-animation-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Typing Animation' ), // Block title.
	icon: <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M5 4v3h5.5v12h3V7H19V4z"></path><path fill="none" d="M0 0h24v24H0V0z"></path></svg>,
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'animation' ),
		__( 'typing' ),
		__( 'typewriter' ),
		__( 'typing' ),
		__( 'effect' ),
	],
	description: 'A block to simulate typing behaviour',
	example: {},
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: ( props ) => ( <Edit { ...props } /> ),

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: () => null,
	attributes: {
		element: {
			type: 'string',
			default: 'h2',
		},
		strings: {
			type: 'array',
			default: [ 'Default value', '<b>Please</b> <i>override me</i>' ],
		},
		// Typing speed
		typeSpeed: {
			type: 'number',
			default: 60,
		},
		startDelay: {
			type: 'number',
			default: 0,
		},
		backSpeed: {
			type: 'number',
			default: 40,
		},
		backDelay: {
			type: 'number',
			default: 700,
		},
		// Loop
		loop: {
			type: 'boolean',
			default: false,
		},
		loopCount: {
			type: 'number',
			default: 0,
		},
		// Misc
		smartBackspace: {
			type: 'boolean',
			default: true,
		},
		shuffle: {
			type: 'boolean',
			default: false,
		},
		fadeOut: {
			type: 'boolean',
			default: false,
		},
		showCursor: {
			type: 'boolean',
			default: true,
		},
		cursorChar: {
			type: 'string',
			default: '|',
		},
	},
	supports: {
		align: true,
	},
} );
