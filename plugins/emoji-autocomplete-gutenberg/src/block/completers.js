import emojis from 'emoji.json';

import './style.scss';

console.log( emojis );

const acronymCompleter = {
	name: 'emoji',
	triggerPrefix: ':',
	options: emojis,
	isDebounced: true,
	getOptionKeywords( { char, keywords, name } ) {
		let words = [];
		if ( keywords ) {
			words = keywords.split( / \| / );
		}
		words = words.concat( name.split( /\s/ ) );
		// console.log( words );
		// if ( name ) {
		// 	words.concat( name.split( ' ' ) );
		// }
		return [ char, ...words ];
	},
	getOptionLabel: ( { codes, char, name } ) => (
		<span><img className="emoji" alt={ char } src={ `https://s.w.org/images/core/emoji/12.0.0-1/svg/${ codes.toLowerCase() }.svg` } /> { name }</span>
	),
	getOptionCompletion: ( { char, codes } ) => {
		return (
			<img draggable="false" className="eedee-emoji" alt={ char } src={ `https://s.w.org/images/core/emoji/12.0.0-1/svg/${ codes.toLowerCase() }.svg` } />
		);
	},
};

// Our filter function
function appendAcronymCompleter( completers, blockName ) {
	return blockName === 'core/paragraph' ||
		blockName === 'core/heading' ||
		blockName === 'core/quote' ||
		blockName === 'core/list' ||
		blockName === 'core/verse' ||
		blockName === 'core/list' ?
		[ ...completers, acronymCompleter ] :
		completers;
}

// Adding the filter
wp.hooks.addFilter(
	'editor.Autocomplete.completers',
	'eedee/autocompleters/emoji',
	appendAcronymCompleter
);
