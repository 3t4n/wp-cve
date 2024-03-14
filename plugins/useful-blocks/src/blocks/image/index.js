/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { image as imgIcon } from '@wordpress/icons';

import {
	BlockControls,
	InspectorControls,
	BlockIcon,
	MediaPlaceholder,
	// RichText,
} from '@wordpress/block-editor';
import { useCallback } from '@wordpress/element';

/**
 * External dependencies
 */
import classnames from 'classnames';
import pbIcon from '@blocks/icon';
import { textDomain, isPro } from '@blocks/config';

/**
 * Internal dependencies
 */
import metadata from './block.json';
import MyToolbar from './_toolbar';
import MySidebar from './_sidebar';

/**
 * metadata
 */
const blockName = 'pb-image';
const { name, category, parent, keywords, supports } = metadata;

registerBlockType(name, {
	title: __('CV Image', textDomain),
	icon: {
		foreground: pbIcon.color,
		src: pbIcon.cvBox,
	},
	category,
	keywords,
	supports,
	parent,
	attributes: metadata.attributes,
	edit: (props) => {
		const { attributes, setAttributes, className, noticeUI } = props;
		const { url, alt, id, href, dataSize } = attributes;
		const blockClass = classnames(blockName, className, '-ponhiro-blocks');

		// 画像ソース
		const isExternal = !id;
		const src = isExternal ? url : undefined;

		const deleteImage = useCallback(() => {
			setAttributes({
				url: undefined,
				alt: undefined,
				id: undefined,
			});
		}, []);

		const onSelectImage = useCallback((media) => {
			// console.log( media );
			if (!media || !media.url) {
				// メディア情報が取得できなかった時
				deleteImage();
				return;
			}
			setAttributes({
				url: media.url,
				alt: media.alt,
				id: media.id,
			});
		}, []);

		const onSelectURL = useCallback(
			(newURL) => {
				if (newURL !== url) {
					setAttributes({
						url: newURL,
						id: undefined,
					});
				}
			},
			[url]
		);

		const imgTag = <img className={`${blockName}__img`} src={url} alt={alt || ''} />;

		if (!isPro) {
			return null;
		}

		return (
			<>
				<BlockControls>
					<MyToolbar {...{ id, url, onSelectImage, onSelectURL, deleteImage }} />
				</BlockControls>
				<InspectorControls>
					<MySidebar {...{ attributes, setAttributes }} />
				</InspectorControls>
				{!url ? (
					<MediaPlaceholder
						// icon='image'
						icon={<BlockIcon icon={imgIcon} />}
						onSelect={onSelectImage}
						onSelectURL={onSelectURL}
						notices={noticeUI}
						// onError={ this.onUploadError }
						accept='image/*'
						allowedTypes={['image']}
						value={{ id, src }}
						mediaPreview={
							!!url && (
								<img
									alt={__('Edit image')}
									title={__('Edit image')}
									className={'edit-image-preview'}
									src={url}
								/>
							)
						}
						disableMediaButtons={url}
						// isAppender={ true }
					/>
				) : (
					<figure className={blockClass} data-size={dataSize || null}>
						{href ? (
							<div
								// href={ href }
								className={`${blockName}__link`}
							>
								{imgTag}
							</div>
						) : (
							imgTag
						)}
					</figure>
				)}
			</>
		);
	},

	save: ({ attributes }) => {
		const { id, url, alt, href, rel, isNewTab, dataSize } = attributes;
		if (!url) {
			return null;
		}

		const blockClass = blockName;
		let imgClass = `${blockName}__img`;
		if (!!id) {
			imgClass = classnames(imgClass, `wp-image-${id}`);
		}

		const imgTag = <img className={`${imgClass}`} src={url} alt={alt || ''} />;

		return (
			<>
				<figure className={blockClass} data-size={dataSize || null}>
					{href ? (
						<a
							href={href}
							className={`${blockName}__link`}
							target={isNewTab ? '_blank' : null}
							rel={rel || null}
						>
							{imgTag}
						</a>
					) : (
						imgTag
					)}
				</figure>
			</>
		);
	},
	deprecated: [
		{
			attributes: metadata.attributes,
			supports,
			save: ({ attributes }) => {
				const { url, alt, href, rel, isNewTab, dataSize } = attributes;
				const blockClass = blockName;
				if (!url) {
					return null;
				}
				const imgTag = <img className={`${blockName}__img`} src={url} alt={alt || ''} />;

				return (
					<>
						<figure className={blockClass} data-size={dataSize || null}>
							{href ? (
								<a
									href={href}
									className={`${blockName}__link`}
									target={isNewTab ? '_blank' : null}
									rel={rel || null}
								>
									{imgTag}
								</a>
							) : (
								imgTag
							)}
						</figure>
					</>
				);
			},
		},
	],
});
