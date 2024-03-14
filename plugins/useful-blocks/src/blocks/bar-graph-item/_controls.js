/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	RangeControl,
	ToggleControl,
	BaseControl,
	ColorPalette,
} from '@wordpress/components';

/**
 * Internal dependencies
 */
import FreePreview from '@blocks/freePreview';
import { textDomain, isPro } from '@blocks/config';

export default function ({ attributes, setAttributes }) {
	const { color, ratio, isThin } = attributes;

	return (
		<>
			<PanelBody
				title={
					<>
						{__('Graph Color', textDomain)}
						{color && (
							<span
								className='component-color-indicator -pb'
								style={{ backgroundColor: color }}
							></span>
						)}
					</>
				}
				initialOpen={true}
			>
				<BaseControl>
					<FreePreview
						description={__(
							'you can choose the color of the graph as you like.',
							textDomain
						)}
					>
						<ColorPalette
							value={color}
							// disableCustomColors={true}
							colors={[
								{
									name: '01',
									color: 'var(--pb_colset_bar_01)',
								},
								{
									name: '02',
									color: 'var(--pb_colset_bar_02)',
								},
								{
									name: '03',
									color: 'var(--pb_colset_bar_03)',
								},
								{
									name: '04',
									color: 'var(--pb_colset_bar_04)',
								},
							]}
							onChange={(val) => {
								if (!isPro) return;
								setAttributes({ color: val });
							}}
						/>
					</FreePreview>
				</BaseControl>
				<BaseControl>
					<ToggleControl
						label={__('Lighten the color', textDomain)}
						checked={isThin}
						onChange={(colorValue) => {
							setAttributes({ isThin: colorValue });
						}}
					/>
				</BaseControl>
			</PanelBody>
			<PanelBody title={__('Percentage of graph', textDomain) + '( % )'} initialOpen={true}>
				<RangeControl
					value={ratio}
					onChange={(val) => {
						setAttributes({ ratio: val });
					}}
					min={0}
					max={100}
				/>
			</PanelBody>
		</>
	);
}
