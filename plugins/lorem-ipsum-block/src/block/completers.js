import { getParagraphBlocks, getHeadingBlock } from '../data/templates';
const { createBlock } = wp.blocks;
const { createRef } = wp.element;

const acronymCompleter = {
	name: 'lorem ipsum',
	triggerPrefix: 'lorem',
	options: [
		{ type: 'Paragraph' },
		{ type: 'Heading' },
		{ type: 'Heading2', keywords: 'h2' },
		{ type: 'Heading3', keywords: 'h3' },
		{ type: 'Heading4', keywords: 'h4' },
	],
	getOptionKeywords( { type, keywords } ) {
		let words = [];
		if ( keywords ) {
			words = keywords.split( /\s+/ );
		}
		return [ type, ...words ];
	},
	getOptionLabel: option => option.type,
	getOptionCompletion: ( { type } ) => {
		const ret = {
			action: 'replace',
		};

		switch ( type ) {
			case 'Paragraph':
				ret.value = getParagraphBlocks( 1 );
				break;
			case 'Heading':
				ret.value = getHeadingBlock( 2 );
				break;
			case 'Heading2':
				ret.value = getHeadingBlock( 2 );
				break;
			case 'Heading3':
				ret.value = getHeadingBlock( 3 );
				break;
			case 'Heading4':
				ret.value = getHeadingBlock( 4 );
				break;
			default:
				ret.value = getParagraphBlocks( 1 );
		}

		const nextBlock = createBlock( 'core/paragraph' );
		const previousClientId = wp.data.select( 'core/editor' ).getPreviousBlockClientId();
		const previousBlockIndex = wp.data.select( 'core/editor' ).getBlockIndex( previousClientId );

		wp.data.dispatch( 'core/editor' ).insertBlock( nextBlock, previousBlockIndex + 2 );

		return ret;
	},
};

// Our filter function
function appendAcronymCompleter( completers, blockName ) {
	return blockName === 'core/paragraph' ?
		[ ...completers, acronymCompleter ] :
		completers;
}

// Adding the filter
wp.hooks.addFilter(
	'editor.Autocomplete.completers',
	'eedee/lorem-ipsum-block/autocompleters/lorem',
	appendAcronymCompleter
);
