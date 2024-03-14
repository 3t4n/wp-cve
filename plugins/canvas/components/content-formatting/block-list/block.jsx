/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	registerBlockStyle,
} = wp.blocks;

registerBlockStyle( 'core/list', {
	name: 'cnvs-list-styled',
	label: __( 'Styled' ),
} );
registerBlockStyle( 'core/list', {
	name: 'cnvs-list-styled-positive',
	label: __( 'Positive' ),
} );
registerBlockStyle( 'core/list', {
	name: 'cnvs-list-styled-negative',
	label: __( 'Negative' ),
} );
