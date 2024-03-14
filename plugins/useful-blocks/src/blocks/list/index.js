/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { BlockControls, InspectorControls, RichText } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import pbIcon from '@blocks/icon';
import MyToolbar from './_toolbar';
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
const { name, category, keywords, parent, supports } = metadata;

/**
 * Block
 */
const blockName = 'pb-list';

const isListEmpty = (listItems) => {
	if (listItems.length === 0) {
		return true;
	} else if (listItems.length === 1) {
		const firstProps = listItems[0].props;
		if (firstProps.children.length === 0) {
			return true;
		}
	}
	return false;
};

registerBlockType(name, {
	title: __('Useful List', textDomain),
	icon: {
		foreground: pbIcon.color,
		src: pbIcon.list,
	},
	keywords,
	category,
	supports,
	parent,
	attributes: metadata.attributes,
	edit: (props) => {
		const { className, attributes, setAttributes } = props;
		const { listTag, icon, listItems, showBorder } = attributes;
		let blockClass = classnames(blockName, className);

		blockClass = classnames(blockClass, '-icon-' + icon);

		if (isListEmpty(listItems)) {
			blockClass = classnames(blockClass, 'is-null');
		}
		if (showBorder) {
			blockClass = classnames(blockClass, '-border-on');
		}

		return (
			<>
				<BlockControls>
					<MyToolbar {...{ listTag, setAttributes }} />
				</BlockControls>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				<RichText
					tagName={listTag}
					className={blockClass}
					multiline='li'
					placeholder={__('Text…', textDomain)}
					value={listItems}
					onChange={(value) => setAttributes({ listItems: value })}
				/>
			</>
		);
	},

	save: ({ attributes }) => {
		const { listTag, listItems, icon, showBorder } = attributes;

		// 空の時は何も出力しない
		if (isListEmpty(listItems)) {
			return null;
		}

		let blockClass = classnames(blockName, '-icon-' + icon);

		if (showBorder) {
			blockClass = classnames(blockClass, '-border-on');
		}

		return <RichText.Content tagName={listTag} className={blockClass} value={listItems} />;
	},
});
