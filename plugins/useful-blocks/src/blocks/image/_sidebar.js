/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, BaseControl, Button, ButtonGroup, TextControl } from '@wordpress/components';

/**
 * External dependencies
 */
// import classnames from 'classnames';
import { textDomain } from '@blocks/config';

/**
 * Settings
 */
const sizeButtons = [
	{
		label: '25%',
		val: '25',
	},
	{
		label: '50%',
		val: '50',
	},
	{
		label: '75%',
		val: '75',
	},
	{
		label: '100%',
		val: '',
	},
];

/**
 * Custom Component
 */
export default ({ attributes, setAttributes }) => {
	const { url, alt, dataSize } = attributes;

	return (
		<>
			{url && (
				<PanelBody title={__('Image settings', textDomain)} initialOpen={true}>
					<TextControl
						label='alt'
						value={alt}
						onChange={(val) => {
							setAttributes({ alt: val });
						}}
					/>
					<BaseControl>
						<BaseControl.VisualLabel>
							{__('Image Size', textDomain)}
						</BaseControl.VisualLabel>
						<ButtonGroup>
							{sizeButtons.map((btn) => {
								const btnVal = btn.val;
								const isSelected = btnVal === dataSize;
								return (
									<Button
										key={`pb-img-size-${btnVal}`}
										variant={isSelected ? 'primary' : 'secondary'}
										onClick={() => {
											setAttributes({ dataSize: btnVal });
										}}
										text={btn.label}
									/>
								);
							})}
						</ButtonGroup>
					</BaseControl>
				</PanelBody>
			)}
		</>
	);
};
