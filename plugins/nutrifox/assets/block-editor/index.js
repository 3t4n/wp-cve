const {
	registerBlockType,
} = wp.blocks;

/**
 * Internal dependencies
 */
import blockRegistration from './nutrifox-block';

registerBlockType( 'nutrifox/nutrifox', blockRegistration );
