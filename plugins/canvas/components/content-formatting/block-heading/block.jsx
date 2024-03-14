/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	registerBlockStyle,
} = wp.blocks;

const {
	addFilter,
} = wp.hooks;

const {
	createHigherOrderComponent,
} = wp.compose;

registerBlockStyle( 'core/heading', {
	name: 'cnvs-heading-numbered',
	label: __( 'Numbered' ),
} );

const canvasHeadingNumberedAdditionalAttributes = createHigherOrderComponent( ( BlockListBlock ) => {
	return ( props ) => {
		const {
			attributes,
			name,
		} = props;

		const {
			level,
		} = attributes;

		let {
			className,
		} = attributes;

		if ( 'core/heading' !== name || ! /is-style-cnvs-heading-numbered/.test( className ) ) {
			return <BlockListBlock { ...props } />;
		}

		className = classnames(
			className,
			`cnvs-heading-numbered-${ level }`
		);

		return (
			<BlockListBlock
				{ ...props }
				className={ className }
				wrapperProps={{ 'data-heading': `cnvs-heading-numbered-${ level }` }}
			/>
		);
	};
}, 'canvasHeadingNumberedAdditionalAttributes' );

addFilter( 'editor.BlockListBlock', 'canvas/heading/numberedAdditionalAttributes', canvasHeadingNumberedAdditionalAttributes );
