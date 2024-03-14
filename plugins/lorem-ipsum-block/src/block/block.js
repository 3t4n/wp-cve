/**
 * BLOCK: lorem-ipsum-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import './completers';

import { paragraphs as p } from '../data/lorem';
import { getParagraphTemplates } from '../data/templates';

const {
	InnerBlocks,
	InspectorControls,
} = wp.editor;
const {
	RangeControl,
	PanelBody,
} = wp.components;
const {
	Fragment,
} = wp.element;
const { __ } = wp.i18n; // Import __() from wp.i18n
const {
	registerBlockType,
	createBlock,
} = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
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
registerBlockType( 'eedee/lorem-ipsum-block', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Lorem Ipsum Block' ), // Block title.
	icon: 'text', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	attributes: {
		pCount: {
			type: 'number',
			default: 3,
		},
	},
	keywords: [
		__( 'Lorem Ipsum' ),
		__( 'Text' ),
		__( 'Placeholder' ),
	],

	edit: function( { attributes, setAttributes, className, clientId } ) {
		const { pCount } = attributes;
		// Creates a <p class='wp-block-cgb-block-lorem-ipsum-block'></p>.

		const setPCount = ( count ) => {
			const { replaceInnerBlocks } = wp.data.dispatch( 'core/block-editor' );
			setAttributes( { pCount: count } );
			const newBlocks = [ ...getParagraphTemplates( count ).map( pTemplate => createBlock( ...pTemplate ) ) ];
			replaceInnerBlocks( clientId, newBlocks, false );
		};

		return (
			<Fragment>
				<InspectorControls>
					<PanelBody
						title={ __( 'Lorem Ipsum Block' ) }
					>
						<RangeControl
							label={ __( '# of Paragraphs' ) }
							value={ pCount }
							onChange={ setPCount }
							min={ 1 }
							max={ p.length }
							step={ 1 }
						/>
					</PanelBody>
				</InspectorControls>
				<div className={ className }>
					<InnerBlocks
						template={ getParagraphTemplates( pCount ) }
						templateLock={ false }
					/>
				</div>
			</Fragment>
		);
	},

	save: function( ) {
		return (
			<InnerBlocks.Content />
		);
	},
} );
