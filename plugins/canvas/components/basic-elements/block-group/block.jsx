/**
 * WordPress dependencies
 */

const {
	__,
} = wp.i18n;

const {
	registerBlockStyle,
} = wp.blocks;

registerBlockStyle( 'core/group', {
	name: 'cnvs-block-bordered',
	label: __( 'Bordered' ),
} );

registerBlockStyle( 'core/group', {
	name: 'cnvs-block-single-border',
	label: __( 'Single Border' ),
} );

registerBlockStyle( 'core/group', {
	name: 'cnvs-block-bg-light',
	label: __( 'Background Light' ),
} );

registerBlockStyle( 'core/group', {
	name: 'cnvs-block-bg-inverse',
	label: __( 'Background Inverse' ),
} );

registerBlockStyle( 'core/group', {
	name: 'cnvs-block-shadow',
	label: __( 'Shadow' ),
} );

wp.hooks.addFilter(
	'blocks.registerBlockType',
	'change/align/group',
	function( settings, name ) {
		if ( name === 'core/group' ) {
			return lodash.assign( {}, settings, {
				supports: lodash.assign( {}, settings.supports, {
					align: ['left', 'center', 'right', 'wide', 'full'],
				} ),
			} );
		}
		return settings;
	}
);