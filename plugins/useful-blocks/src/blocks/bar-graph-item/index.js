/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
// import { useSelect } from '@wordpress/data';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText } from '@wordpress/block-editor';

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
const { name, parent, supports, category } = metadata;

/**
 * Block
 */
const blockName = 'pb-bar-graph';

registerBlockType(name, {
	title: __('Graph', textDomain),
	icon: {
		foreground: iconColor,
		src: icon,
	},
	// keywords: [],
	category,
	supports,
	parent,
	attributes: metadata.attributes,
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { color, value, label, ratio, isThin } = attributes;

		return (
			<>
				<InspectorControls>
					<MyControls {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div
					className={classnames(`${blockName}__item`, className)}
					data-thin={isThin ? '1' : null}
				>
					<div className={`${blockName}__dt`} style={{ width: `${ratio}%` }}>
						<span
							className={`${blockName}__fill`}
							role='presentation'
							style={color ? { backgroundColor: color } : null}
						></span>
						<RichText
							tagName='span'
							className={`${blockName}__label`}
							placeholder={__('Text…', textDomain)}
							value={label}
							onChange={(val) => setAttributes({ label: val })}
						/>
					</div>
					<div className={`${blockName}__dd`}>
						<RichText
							tagName='span'
							className={`${blockName}__value`}
							placeholder={__('Text…', textDomain)}
							value={value}
							onChange={(val) => setAttributes({ value: val })}
						/>
					</div>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { color, value, label, ratio, isThin } = attributes;
		return (
			<div className={`${blockName}__item`} data-thin={isThin ? '1' : null}>
				<dt className={`${blockName}__dt`} style={{ width: `${ratio}%` }}>
					<span
						className={`${blockName}__fill`}
						style={color ? { backgroundColor: color } : null}
						role='presentation'
					></span>
					<RichText.Content
						tagName='span'
						className={`${blockName}__label`}
						value={label}
					/>
				</dt>
				<dd className={`${blockName}__dd`}>
					<RichText.Content
						tagName='span'
						className={`${blockName}__value`}
						value={value}
					/>
				</dd>
			</div>
		);
	},
});
