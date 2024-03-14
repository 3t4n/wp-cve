/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, BaseControl, Button, ButtonGroup, ToggleControl } from '@wordpress/components';
// import { Icon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import FreePreview from '@blocks/freePreview';
import { textDomain, isPro } from '@blocks/config';

/**
 * Settings
 */
const olIcons = [
	{
		val: 'circle',
		icon: (
			<svg
				x='0px'
				y='0px'
				viewBox='0 0 40 40'
				width='20'
				height='20'
				role='img'
				aria-label='circle'
				focusable='false'
			>
				<circle cx='20' cy='20' r='16' />
			</svg>
		),
	},
	{
		val: 'square',
		icon: (
			<svg
				x='0px'
				y='0px'
				viewBox='0 0 40 40'
				width='20'
				height='20'
				role='img'
				aria-label='square'
				focusable='false'
			>
				<rect x='4' y='4' width='32' height='32' />
			</svg>
		),
	},
];

const ulIcons = [
	{
		val: 'dot',
	},
	{
		val: 'check',
	},
	{
		val: 'chevron-right',
	},
	{
		val: 'comment',
	},
];

/**
 * Custom Component
 */
export default ({ attributes, setAttributes }) => {
	const { listTag, icon, showBorder } = attributes;

	return (
		<>
			<PanelBody title={__('List settings', textDomain)} initialOpen={true}>
				<ToggleControl
					label={__('Add a dotted line below the list', textDomain)}
					checked={showBorder}
					onChange={(value) => {
						setAttributes({
							showBorder: value,
						});
					}}
				/>
			</PanelBody>
			<PanelBody title={__('Icon settings', textDomain)} initialOpen={true}>
				<BaseControl>
					<FreePreview
						description={__('you can choose from several types of icons.', textDomain)}
					>
						{'ul' === listTag ? (
							<ButtonGroup className='pb-btn-group'>
								{ulIcons.map((_icon) => {
									const iconVal = _icon.val;
									const isSelected = iconVal === icon;
									return (
										<Button
											// isSecondary
											isPrimary={isSelected}
											key={`pb-ul-icon-${iconVal}`}
											onClick={() => {
												if (!isPro) return;
												setAttributes({
													icon: iconVal,
												});
											}}
										>
											<i className={'pb-icon-' + iconVal}></i>
										</Button>
									);
								})}
							</ButtonGroup>
						) : (
							<ButtonGroup className='pb-btn-group -olicons'>
								{olIcons.map((_icon) => {
									const iconVal = _icon.val;
									const isSelected = iconVal === icon;
									return (
										<Button
											// isSecondary
											isPrimary={isSelected}
											key={`pb-ul-icon-${iconVal}`}
											onClick={() => {
												if (!isPro) return;
												setAttributes({
													icon: iconVal,
												});
											}}
										>
											{_icon.icon}
										</Button>
									);
								})}
							</ButtonGroup>
						)}
					</FreePreview>
				</BaseControl>
			</PanelBody>
		</>
	);
};
