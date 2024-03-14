/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	registerBlockStyle,
} = wp.blocks;

/*
 * core/separator
 */
registerBlockStyle( 'core/separator', {
	name: 'cnvs-separator-double',
	label: __( 'Double' ),
} );
registerBlockStyle( 'core/separator', {
	name: 'cnvs-separator-dotted',
	label: __( 'Dotted' ),
} );
registerBlockStyle( 'core/separator', {
	name: 'cnvs-separator-dashed',
	label: __( 'Dashed' ),
} );
