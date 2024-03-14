/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
// import { useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import {
	// BlockControls,
	InspectorControls,
	InnerBlocks,
	useBlockProps,
	__experimentalUseInnerBlocksProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';

const compatibleUseInnerBlocksProps =
	typeof useInnerBlocksProps === 'function'
		? useInnerBlocksProps
		: __experimentalUseInnerBlocksProps;

/**
 * Internal dependencies
 */
import icon from './_icon';
import MySidebar from './_sidebar';
import { textDomain, iconColor } from '@blocks/config';

/**
 * External dependencies
 */
// import classnames from 'classnames';

/**
 * metadata
 */
import metadata from './block.json';
const { name, apiVersion, keywords, supports, category } = metadata;

/**
 * Block
 */
const blockName = 'pb-rating-graph';

registerBlockType(name, {
	apiVersion,
	title: __('Rating Graph', textDomain),
	icon: {
		foreground: iconColor,
		src: icon,
	},
	keywords,
	category,
	supports,
	attributes: metadata.attributes,
	edit: (props) => {
		const { attributes, setAttributes } = props;
		const { colSet } = attributes;

		const blockProps = useBlockProps({
			className: `${blockName}`,
			'data-colset': colSet,
		});
		const innerBlocksProps = compatibleUseInnerBlocksProps(blockProps, {
			allowedBlocks: ['ponhiro-blocks/rating-graph-item'],
			template: [['ponhiro-blocks/rating-graph-item']],
			templateLock: false,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		});

		return (
			<>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div {...innerBlocksProps} />
			</>
		);
	},

	save: ({ attributes }) => {
		const { colSet } = attributes;

		const blockProps = useBlockProps.save({
			className: `${blockName}`,
			'data-colset': colSet,
		});

		return (
			<div {...blockProps}>
				<InnerBlocks.Content />
			</div>
		);
	},
});
