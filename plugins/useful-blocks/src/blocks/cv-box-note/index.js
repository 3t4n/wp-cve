/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, RichText } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import pbIcon from '@blocks/icon';
import MySidebar from './_sidebar';
import { textDomain } from '@blocks/config';

/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * metadata
 */
import metadata from './block.json';
const { name, category, parent, supports } = metadata;

/**
 * Block
 */
const blockName = 'pb-cv-box__note';

registerBlockType(name, {
	title: __('CV Text', textDomain),
	icon: {
		foreground: pbIcon.color,
		src: pbIcon.cvBox,
	},
	// keywords: [],
	category,
	supports,
	parent,
	attributes: metadata.attributes,

	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { content, icon, dataStyle } = attributes;
		let blockClass = classnames(blockName, className);

		if (!content) {
			blockClass = classnames(blockClass, 'has-no-content');
		}

		return (
			<>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<div className={blockClass} data-style={dataStyle}>
					{icon && (
						<div className='__icon'>
							<i className={icon}></i>
						</div>
					)}
					<RichText
						tagName='div'
						className='__text'
						placeholder={__('Text…', textDomain)}
						value={content}
						onChange={(value) => setAttributes({ content: value })}
					/>
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { content, icon, dataStyle } = attributes;
		if (RichText.isEmpty(content)) {
			return null;
		}
		return (
			<div className={blockName} data-style={dataStyle}>
				{icon && (
					<div className='__icon'>
						<i className={icon}></i>
					</div>
				)}
				<RichText.Content tagName='div' className='__text' value={content} />
			</div>
		);
	},
});
