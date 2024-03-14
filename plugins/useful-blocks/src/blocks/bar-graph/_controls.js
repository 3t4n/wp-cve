/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, BaseControl, Button, ButtonGroup } from '@wordpress/components';

/**
 * Internal dependencies
 */
import FreePreview from '@blocks/freePreview';
import { textDomain } from '@blocks/config';

/**
 * component
 */
const ColsetDOM = ({ colset }) => {
	return (
		<span className='pb-bar-graph' data-colset={colset} data-bg='1'>
			<span className='pb-bar-graph__dl' data-bg='1'>
				<span className='pb-bar-graph__item'>
					<span className='pb-bar-graph__dt'>
						<span className='pb-bar-graph__fill'></span>
					</span>
					<span className='pb-bar-graph__dd'></span>
				</span>
				<span className='pb-bar-graph__item'>
					<span className='pb-bar-graph__dt'>
						<span className='pb-bar-graph__fill'></span>
					</span>
					<span className='pb-bar-graph__dd'></span>
				</span>
			</span>
		</span>
	);
};

/**
 * 設定
 */
// カラーセット
const colorSets = ['y', 'p', 'g', 'b', '1'];

// 右テキストの位置
const valuePosChoices = {
	left: __('Left justified', textDomain),
	right: __('Right justified', textDomain),
};

// 左テキストの位置
const labelPosChoices = {
	top: __('Top', textDomain),
	inner: __('Inner', textDomain),
};

/**
 * InspectorControls
 */
export default ({ attributes, setAttributes }) => {
	const { colSet, hideTtl, ttlData, bg, barBg, valuePos, labelPos } = attributes;

	return (
		<>
			<PanelBody title={__('Color set', textDomain)} initialOpen={true}>
				<BaseControl>
					<ButtonGroup className='pb-panel--colorSet -bar-graph'>
						{colorSets.map((setNum) => {
							const isSelected = colSet === setNum;
							const buttonId = 'pb-iconbox-colset-' + setNum;
							return (
								<div className='__btnBox' key={`key_style_${setNum}`}>
									<button
										type='button'
										id={buttonId}
										className='__btn'
										onClick={() => {
											setAttributes({
												colSet: setNum,
											});
										}}
									></button>
									<label
										htmlFor={buttonId}
										className='__label'
										data-selected={isSelected || null}
									>
										<ColsetDOM colset={setNum} />
									</label>
								</div>
							);
						})}
					</ButtonGroup>
				</BaseControl>
			</PanelBody>
			<PanelBody title={__('Title settings', textDomain)} initialOpen={true}>
				<ToggleControl
					label={__("Don't show", textDomain)}
					checked={hideTtl}
					onChange={(bool) => {
						setAttributes({ hideTtl: bool });
					}}
				/>

				<ToggleControl
					label={__('Add a border below', textDomain)}
					checked={'border' === ttlData}
					onChange={(bool) => {
						if (bool) {
							setAttributes({ ttlData: 'border' });
						} else {
							setAttributes({ ttlData: 'normal' });
						}
					}}
				/>
			</PanelBody>
			<PanelBody title={__('Graph settings', textDomain)} initialOpen={true}>
				<ToggleControl
					label={__('Add background color', textDomain)}
					checked={bg}
					onChange={(value) => {
						setAttributes({ bg: value });
					}}
				/>
				<FreePreview description={__('you can make more detailed settings.', textDomain)}>
					<ToggleControl
						label={__('Color the right side of the graph', textDomain)}
						checked={barBg}
						onChange={(value) => {
							setAttributes({ barBg: value });
						}}
					/>
					<BaseControl>
						<BaseControl.VisualLabel>
							{__('The position of the label on the left', textDomain)}
						</BaseControl.VisualLabel>
						<ButtonGroup className='pb-btn-group'>
							{Object.keys(labelPosChoices).map((pos) => {
								return (
									<Button
										key={`key_${pos}`}
										isPrimary={pos === labelPos}
										onClick={() => {
											setAttributes({ labelPos: pos });
										}}
									>
										{labelPosChoices[pos]}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
					<BaseControl>
						<BaseControl.VisualLabel>
							{__('The position of the label on the right', textDomain)}
						</BaseControl.VisualLabel>
						<ButtonGroup className='pb-btn-group'>
							{Object.keys(valuePosChoices).map((pos) => {
								return (
									<Button
										key={`key_${pos}`}
										isPrimary={pos === valuePos}
										onClick={() => {
											setAttributes({ valuePos: pos });
										}}
									>
										{valuePosChoices[pos]}
									</Button>
								);
							})}
						</ButtonGroup>
					</BaseControl>
				</FreePreview>
			</PanelBody>
		</>
	);
};
