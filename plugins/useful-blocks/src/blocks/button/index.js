/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import { RawHTML } from '@wordpress/element';
import { registerBlockType } from '@wordpress/blocks';
import {
	BlockControls,
	InspectorControls,
	RichText,
	// InnerBlocks,
} from '@wordpress/block-editor';

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
const { name, parent, category, keywords, supports } = metadata;

/**
 * Block
 */
const blockName = 'pb-button';

registerBlockType(name, {
	title: __('CV Botton', textDomain),
	icon: {
		foreground: pbIcon.color,
		src: pbIcon.button,
	},
	keywords,
	category,
	supports,
	parent,
	attributes: {
		arrowIcon: {
			type: 'string',
			default: 'pb-icon-chevron-right',
		},
		emIcon: {
			type: 'string',
			default: '',
		},
		url: {
			type: 'string',
			default: '',
		},
		btnEm: {
			type: 'string',
			default: __('必見', textDomain),
		},
		btnText: {
			type: 'array',
			source: 'children',
			selector: '.pb-button__text',
		},
		linkLabel: {
			type: 'string',
			source: 'html',
			selector: '.pb-text-link__label',
			default: __('Link to : ', textDomain),
		},
		linkUrl: {
			type: 'string',
			source: 'html',
			selector: '.pb-text-link__url',
		},
		isNewTab: {
			type: 'boolean',
			default: false,
		},
		rel: {
			type: 'string',
			source: 'attribute',
			selector: 'a',
			attribute: 'rel',
		},
		isRound: {
			type: 'boolean',
			default: false,
		},
		isShowLink: {
			type: 'boolean',
			default: true,
		},
		imgTag: {
			type: 'string',
			default: '',
		},
	},

	edit: (props) => {
		const { clientId, className, attributes, setAttributes } = props;
		const {
			btnEm,
			btnText,
			linkLabel,
			url,
			arrowIcon,
			emIcon,
			isShowLink,
			isRound,
			linkUrl,
		} = attributes;
		let blockClass = classnames(blockName, className);

		if (isRound) {
			blockClass = classnames(blockClass, 'is-round');
		}

		// 兄弟要素の画像ブロックのIDを取得
		const siblingsImageId = useSelect(
			(select) => {
				let theId = '';

				// 親ブロックの取得
				const parents = select('core/block-editor').getBlockParents(clientId);

				// 一つ上の親
				const parentBoxId = parents[0];

				// その子ブロックのデータを取得（つまり、自分の兄弟要素）
				const parentData = select('core/block-editor').getBlocksByClientId(parentBoxId)[0];

				// その中から画像ブロックを探す
				parentData.innerBlocks.forEach((block) => {
					if ('ponhiro-blocks/image' === block.name) {
						theId = block.clientId;
					}
				});

				return theId;
			},
			[clientId]
		);

		let btnEmContent = null;
		if (emIcon) {
			btnEmContent = (
				<span className={`${blockName}__em has-icon`}>
					<i className={emIcon}></i>
				</span>
			);
		} else if (btnEm) {
			btnEmContent = <span className={`${blockName}__em`}>{btnEm}</span>;
		}

		return (
			<>
				<BlockControls>
					<MyToolbar {...{ attributes, setAttributes, siblingsImageId }} />
				</BlockControls>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes, siblingsImageId }} />
				</InspectorControls>
				<div className={blockClass}>
					<div className={`${blockName}__btn`}>
						{btnEmContent}
						<RichText
							tagName='span'
							className={`${blockName}__text`}
							placeholder={__('Button text…', textDomain)}
							value={btnText}
							onChange={(value) => setAttributes({ btnText: value })}
							allowedFormats={[]} //[ 'core/bold', 'core/link' ] とかで細かく指定できる
						/>
						{arrowIcon && <i className={arrowIcon}></i>}
					</div>
				</div>
				{isShowLink && (
					<div className={`pb-text-link`}>
						<span className={`pb-text-link__label`}>{linkLabel}</span>
						<span className={`pb-text-link__url`}>{linkUrl || url}</span>
					</div>
				)}
			</>
		);
	},

	save: ({ attributes }) => {
		const {
			btnEm,
			btnText,
			linkLabel,
			url,
			arrowIcon,
			emIcon,
			isNewTab,
			isRound,
			rel,
			isShowLink,
			linkUrl,
			imgTag,
		} = attributes;

		let blockClass = blockName;
		if (isRound) {
			blockClass = classnames(blockClass, 'is-round');
		}

		let btnEmContent = null;
		if (emIcon) {
			btnEmContent = (
				<span className={`${blockName}__em has-icon`}>
					<i className={emIcon}></i>
				</span>
			);
		} else if (btnEm) {
			btnEmContent = <span className={`${blockName}__em`}>{btnEm}</span>;
		}

		return (
			<>
				<div className={blockClass}>
					<a
						href={url}
						className={`${blockName}__btn`}
						target={isNewTab ? '_blank' : null}
						rel={rel || null}
					>
						{btnEmContent}
						<RichText.Content
							tagName='span'
							className={`${blockName}__text`}
							value={btnText}
						/>
						{arrowIcon && <i className={arrowIcon}></i>}
					</a>
					{!!imgTag && (
						// 計測用HTMLタグ
						<RawHTML>{imgTag}</RawHTML>
					)}
				</div>
				{isShowLink && (
					<div className={`pb-text-link`}>
						<span className={`pb-text-link__label`}>{linkLabel}</span>
						<a
							href={url}
							className={`pb-text-link__url`}
							target={isNewTab ? '_blank' : null}
							rel={rel || null}
						>
							{linkUrl || url}
						</a>
					</div>
				)}
			</>
		);
	},
});
