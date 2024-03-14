import { random, map } from 'lodash';
const { createBlock } = wp.blocks;

import {
	paragraphs,
	headings,
} from './lorem';

String.prototype.capitalize = function() {
	return this.replace( /(?:^|\s)\S/g, function( a ) {
		return a.toUpperCase();
	} );
};

export const getUniqueIndices = ( n, max ) => {
	const s = new Set();
	for ( let j = max - n; j < max; j++ ) {
		const t = random( 0, j );
		s.add( s.has( t ) ? j : t );
	}
	return Array.from( s );
};

/**
 * Curried Function to get an array with number of random elements
 * @param  {Array} array [description]
 * @return {Function} A function that takes one parameter (count) and will return an Array
 */
export const getFromArray = array => count => {
	if ( count >= array.length ) {
		return array;
	}

	return map( getUniqueIndices( count, array.length ), idx => {
		return array[ idx ];
	} );
};

export const getParagraphs = getFromArray( paragraphs );
export const getHeadings = ( count ) => {
	return map( getFromArray( headings )( count ), heading => {
		return heading.capitalize();
	} );
};

export const getParagraphTemplates = ( count ) => {
	return [
		...getParagraphs( count ).map( paragraph => [ 'core/paragraph', { content: paragraph } ] ),
	];
};

export const getHeadingTemplates = ( count ) => {
	return [
		...getHeadings( count ).map( heading => [ 'core/heading', { content: heading, level: 2 } ] ),
	];
};

export const getParagraphBlocks = ( count ) => createBlock( 'core/paragraph', {
	content: getParagraphs( count ),
} );

export const getHeadingBlock = ( level ) => {
	return createBlock( 'core/heading', {
		content: getHeadings( 1 ),
		level,
	} );
};
