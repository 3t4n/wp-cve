/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, InnerBlocks } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import pbIcon from '@blocks/icon';
import { textDomain, isPro } from '@blocks/config';
import MySidebar from './_sidebar';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * metadata
 */
import metadata from './block.json';
const { name, category, keywords, supports } = metadata;

/**
 * Block
 */
const blockName = 'pb-cv-box';

registerBlockType(name, {
	title: __('CV Box', textDomain),
	icon: {
		foreground: pbIcon.color,
		src: pbIcon.cvBox,
	},
	keywords,
	category,
	supports,
	attributes: metadata.attributes,
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { colSet, bgStyle } = attributes;
		let blockClass = classnames(blockName, className, '-ponhiro-blocks');
		if (!isPro) {
			blockClass = classnames(blockClass, '-is-free');
		}

		return (
			<>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass} data-colset={colSet} data-bg={bgStyle}>
					<div className={`${blockName}__inner`}>
						<InnerBlocks
							allowedBlocks={[
								'ponhiro-blocks/list',
								'ponhiro-blocks/image',
								'ponhiro-blocks/button',
								'ponhiro-blocks/cv-box-note',
							]}
							templateLock={'all'}
							template={[
								['ponhiro-blocks/image', {}, []],
								['ponhiro-blocks/list', { icon: 'check' }, []],
								['ponhiro-blocks/button', {}, []],
								['ponhiro-blocks/cv-box-note', {}, []],
							]}
						/>
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { colSet, bgStyle } = attributes;

		return (
			<div className={blockName} data-colset={colSet} data-bg={bgStyle}>
				<div className={`${blockName}__inner`}>
					<InnerBlocks.Content />
				</div>
			</div>
		);
	},
});
