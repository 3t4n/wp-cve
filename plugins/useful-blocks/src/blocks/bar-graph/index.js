/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
// import { useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText, InnerBlocks } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import icon from './_icon';
import MyControls from './_controls';
import { textDomain, iconColor } from '@blocks/config';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * metadata
 */
import metadata from './block.json';
const { name, keywords, supports, category } = metadata;

/**
 * Block
 */
const blockName = 'pb-bar-graph';

registerBlockType(name, {
	title: __('Bar Graph', textDomain),
	icon: {
		foreground: iconColor,
		src: icon,
	},
	keywords,
	category,
	supports,
	attributes: metadata.attributes,
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { colSet, title, bg, barBg, valuePos, labelPos, hideTtl, ttlData } = attributes;
		const blockClass = classnames(blockName, className);

		return (
			<>
				<InspectorControls>
					<MyControls {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass} data-colset={colSet} data-bg={bg ? '1' : null}>
					{!hideTtl && (
						<RichText
							tagName='div'
							className={`${blockName}__title -${ttlData}`}
							placeholder={__('Text…', textDomain)}
							value={title}
							onChange={(value) => setAttributes({ title: value })}
						/>
					)}

					<div
						className={`${blockName}__dl`}
						data-bg={barBg ? '1' : null}
						data-label={labelPos}
						data-value={valuePos}
					>
						<InnerBlocks
							allowedBlocks={['ponhiro-blocks/bar-graph-item']}
							templateLock={false}
							template={[
								['ponhiro-blocks/bar-graph-item', {}, []],
								['ponhiro-blocks/bar-graph-item', { isThin: true, ratio: 40 }, []],
								['ponhiro-blocks/bar-graph-item', { isThin: true, ratio: 30 }, []],
							]}
							renderAppender={InnerBlocks.ButtonBlockAppender}
						/>
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { colSet, title, bg, barBg, hideTtl, ttlData, labelPos, valuePos } = attributes;
		return (
			<div className={blockName} data-colset={colSet} data-bg={bg ? '1' : null}>
				{!hideTtl && (
					<RichText.Content
						tagName='div'
						className={`${blockName}__title -${ttlData}`}
						data-ttl={ttlData}
						value={title}
					/>
				)}
				<dl
					className={`${blockName}__dl`}
					data-bg={barBg ? '1' : null}
					data-label={labelPos}
					data-value={valuePos}
				>
					<InnerBlocks.Content />
				</dl>
			</div>
		);
	},
});
