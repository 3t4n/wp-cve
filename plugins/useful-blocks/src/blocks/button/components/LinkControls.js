/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { memo } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';

import { TextControl, ToggleControl, TextareaControl } from '@wordpress/components';

/**
 * External dependencies
 */
// import classnames from 'classnames';
import { textDomain } from '@blocks/config';

/**
 * Custom Component
 */
export default memo((props) => {
	const { attributes, setAttributes, siblingsImageId } = props;

	const { url, isNewTab, rel, imgTag } = attributes;

	const { updateBlockAttributes } = useDispatch('core/block-editor');

	return (
		<>
			<TextControl
				label={__('URL')}
				type='url'
				value={url}
				onChange={(val) => {
					setAttributes({ url: val });

					// 画像ブロックにも反映
					if (siblingsImageId) {
						updateBlockAttributes(siblingsImageId, { href: val });
					}
				}}
			/>
			<ToggleControl
				label={__('Open in new tab')}
				checked={isNewTab}
				onChange={(value) => {
					let newRel = rel || '';
					if (value) {
						// noopener / noreferrerがなければつける
						if (-1 === newRel.indexOf('noopener')) {
							newRel += ' noopener';
						}
						if (-1 === newRel.indexOf('noreferrer')) {
							newRel += ' noreferrer';
						}
					} else {
						// noopener / noreferrerを消す
						newRel = newRel.replace('noopener', '');
						newRel = newRel.replace('noreferrer', '');
					}
					setAttributes({
						isNewTab: value,
						rel: newRel.trim(),
					});

					// 画像ブロックにも反映
					if (siblingsImageId) {
						updateBlockAttributes(siblingsImageId, {
							isNewTab: value,
							rel: newRel.trim(),
						});
					}
				}}
			/>
			<TextControl
				label={__('Link rel')}
				value={rel || ''}
				onChange={(value) => {
					setAttributes({ rel: value });

					// 画像ブロックにも反映
					if (siblingsImageId) {
						updateBlockAttributes(siblingsImageId, {
							rel: value,
						});
					}
				}}
			/>
			<TextareaControl
				label={__('Img tag for measurement', textDomain)}
				// help=''
				value={imgTag}
				rows='4'
				onChange={(html) => {
					setAttributes({ imgTag: html });
				}}
			/>
		</>
	);
});
