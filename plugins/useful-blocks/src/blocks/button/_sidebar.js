/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

import {
	PanelBody,
	TextControl,
	ToggleControl,
	BaseControl,
	Button,
	ButtonGroup,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import { textDomain, isPro } from '@blocks/config';
import FreePreview from '@blocks/freePreview';
import LinkControls from './components/LinkControls';

/**
 * External dependencies
 */
// import classnames from 'classnames';

/**
 * Settings
 */
const emIcons = [
	{
		val: 'pb-icon-arrow-down',
	},
	{
		val: 'pb-icon-mail',
	},
	{
		val: 'pb-icon-cart',
	},
];

const arrowIcons = [
	{
		val: '',
	},
	{
		val: 'pb-icon-chevron-right',
	},
	{
		val: 'pb-icon-chevron-circle-right',
	},
];

export default ({ attributes, setAttributes, siblingsImageId }) => {
	const { btnEm, linkLabel, linkUrl, url, arrowIcon, emIcon, isShowLink, isRound } = attributes;
	// const { updateBlockAttributes } = useDispatch('core/block-editor');

	const emIconButtons = (
		<ButtonGroup className='pb-btn-group'>
			{emIcons.map((icon) => {
				const iconName = icon.val;
				const isSelected = iconName === emIcon;
				return (
					<Button
						// isSecondary
						isPrimary={isSelected}
						key={`pb-em-icon-${iconName}`}
						onClick={() => {
							if (!isPro) return;
							setAttributes({
								emIcon: iconName,
							});
						}}
					>
						<i className={iconName}></i>
					</Button>
				);
			})}
		</ButtonGroup>
	);

	const arrowIconButtons = (
		<ButtonGroup className='pb-btn-group'>
			{arrowIcons.map((icon) => {
				const iconName = icon.val;
				const isSelected = iconName === arrowIcon;
				return (
					<Button
						// isSecondary
						isPrimary={isSelected}
						key={`pb-arrow-icon-${iconName}`}
						onClick={() => {
							if (!isPro) return;
							setAttributes({
								arrowIcon: iconName,
							});
						}}
					>
						{iconName ? <i className={iconName}></i> : <span>なし</span>}
					</Button>
				);
			})}
		</ButtonGroup>
	);

	return (
		<>
			<PanelBody title={__('Link settings', textDomain)} initialOpen={true}>
				<LinkControls {...{ attributes, setAttributes, siblingsImageId }} />
			</PanelBody>
			<PanelBody title={__('Button settings', textDomain)} initialOpen={true}>
				<BaseControl className='pb-cvbtn-left-controls'>
					<BaseControl.VisualLabel>
						{__('Left side of button', textDomain)}
					</BaseControl.VisualLabel>
					<ButtonGroup className='pb-btn-group'>
						<Button
							variant={emIcon ? 'secondary' : 'primary'}
							onClick={() => {
								setAttributes({ emIcon: '' });
							}}
						>
							{__('Text', textDomain)}
						</Button>
						<Button
							variant={!emIcon ? 'secondary' : 'primary'}
							onClick={() => {
								setAttributes({
									emIcon: 'pb-icon-arrow-down',
								});
							}}
						>
							{__('Icon', textDomain)}
						</Button>
					</ButtonGroup>
					<div className='__controls'>
						{!emIcon ? (
							<TextControl
								value={btnEm}
								onChange={(val) => {
									setAttributes({ btnEm: val });
								}}
							/>
						) : (
							<BaseControl>
								<FreePreview
									description={__(
										'you can choose from several types of icons.',
										textDomain
									)}
								>
									{emIconButtons}
								</FreePreview>
							</BaseControl>
						)}
					</div>
				</BaseControl>
				<BaseControl>
					<BaseControl.VisualLabel>
						{__('Icon displayed on the right edge of the button', textDomain)}
					</BaseControl.VisualLabel>
					<FreePreview
						description={__('you can choose from several types of icons.', textDomain)}
					>
						{arrowIconButtons}
					</FreePreview>
				</BaseControl>
				<ToggleControl
					label={__('Round button', textDomain)}
					checked={isRound}
					onChange={(value) => {
						setAttributes({
							isRound: value,
						});
					}}
				/>
				<ToggleControl
					label={__('Display text link below button', textDomain)}
					checked={isShowLink}
					onChange={(value) => {
						setAttributes({
							isShowLink: value,
						});
						if (value) {
							setAttributes({
								linkLabel: __('Link to : ', textDomain),
							});
							setAttributes({
								linkUrl: url,
							});
						}
					}}
				/>
			</PanelBody>
			<PanelBody
				title={__('Text link settings', textDomain)}
				initialOpen={true}
				className={!isShowLink ? 'pb-is-hide' : null}
			>
				<TextControl
					label={__('Text before URL', textDomain)}
					className='pb-is-harf-size'
					value={linkLabel}
					onChange={(val) => {
						setAttributes({ linkLabel: val });
					}}
				/>
				<TextControl
					label={__('URL text to display', textDomain)}
					type='url'
					value={linkUrl || url}
					onChange={(val) => {
						setAttributes({ linkUrl: val });
					}}
				/>
			</PanelBody>
		</>
	);
};
