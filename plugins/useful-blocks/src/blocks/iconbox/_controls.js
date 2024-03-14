/**
 * WordPress dependencies
 */

import { __ } from '@wordpress/i18n';
// import { useSelect, useDispatch } from '@wordpress/data';
import {
	BaseControl,
	PanelBody,
	Button,
	ButtonGroup,
	ToggleControl,
	RangeControl,
} from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

/**
 * External dependencies
 */
// import classnames from 'classnames';
import { textDomain, isPro } from '@blocks/config';

import FreePreview from '@blocks/freePreview';

/**
 * Custom Component
 */
export default function ({ attributes, setAttributes }) {
	const { colSet, iconSet, iconPos, isCenter, commentStyle, iconY } = attributes;

	// カラーセット
	const colorSets = ['y', 'p', 'g', 'b', '1'];

	// ふきだしスタイル
	const balloonStyles = ['normal', 'simple'];

	// アイコンセット
	const iconSets = ['01', '02', '03', '04'];

	const iconPositions = [
		{ val: 'left', label: __('Left', textDomain) },
		{ val: 'right', label: __('Right', textDomain) },
		{ val: 'top-left', label: __('Top left', textDomain) },
		{ val: 'top-right', label: __('Top right', textDomain) },
	];

	const isTopIcon = -1 !== iconPos.indexOf('top');

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Color set', textDomain)} initialOpen={true}>
					<BaseControl>
						<ButtonGroup className='pb-panel--colorSet -iconbox'>
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
											<span className='pb-iconbox' data-colset={setNum}>
												<span className='pb-iconbox__inner'>
													<span className='pb-iconbox__head'></span>
													<span className='pb-iconbox__body'></span>
												</span>
											</span>
										</label>
									</div>
								);
							})}
						</ButtonGroup>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('Icon set', textDomain)} initialOpen={true}>
					<BaseControl>
						<FreePreview
							description={__(
								'you can register 4 types of icons and call them easily.',
								textDomain
							)}
						>
							<ButtonGroup className='pb-panel--colorSet -iconset'>
								{iconSets.map((setNum) => {
									const isSelected = iconSet === setNum;
									const buttonId = 'pb-iconbox-iconset-' + setNum;
									return (
										<div className='__btnBox' key={`key_style_${setNum}`}>
											<button
												type='button'
												id={buttonId}
												className='__btn'
												onClick={() => {
													if (!isPro) return;
													setAttributes({
														iconSet: setNum,
														mediaUrl: undefined,
														mediaId: undefined,
													});
												}}
											></button>
											<label
												htmlFor={buttonId}
												className='__label'
												data-selected={isSelected || null}
											>
												<span className='__num'>{setNum}</span>
												<span
													className='pb-iconbox__figure'
													data-iconset={setNum}
												></span>
											</label>
										</div>
									);
								})}
							</ButtonGroup>
							<Button
								// isSmall
								isSecondary
								className='pb-btn--clear'
								onClick={() => {
									setAttributes({
										iconSet: '',
									});
								}}
							>
								{__('Clear', textDomain)}
							</Button>
							{/* <div className='pb-ctrl-description'>
						admin.php?page=useful_blocks#icons から設定できます。
					</div> */}
						</FreePreview>
					</BaseControl>
				</PanelBody>
				<PanelBody title={__('Icon position', textDomain)} initialOpen={true}>
					<FreePreview
						description={__('you can change the placement of the icons.', textDomain)}
					>
						<BaseControl>
							<ButtonGroup className='pb-panel--colorSet -iconbox'>
								{iconPositions.map((data) => {
									const isSelected = data.val === iconPos ? true : false;
									return (
										<Button
											// isSecondary
											isPrimary={isSelected}
											key={`pb-iconpos-${data.val}`}
											onClick={() => {
												if (!isPro) return;
												setAttributes({
													iconPos: data.val,
												});
											}}
										>
											{data.label}
										</Button>
									);
								})}
							</ButtonGroup>
						</BaseControl>
						{isTopIcon ? (
							<BaseControl>
								<RangeControl
									label={__('Move icon down', textDomain)}
									value={iconY}
									onChange={(val) => {
										setAttributes({ iconY: val });
									}}
									min={0}
									max={5}
								/>
							</BaseControl>
						) : (
							<ToggleControl
								className='pb-mt-20'
								label={__('Align to top/bottom center', textDomain)}
								checked={isCenter}
								onChange={(value) => {
									setAttributes({ isCenter: value });
								}}
							/>
						)}
					</FreePreview>
				</PanelBody>
				<PanelBody title={__('Baloon style', textDomain)} initialOpen={true}>
					<BaseControl>
						<FreePreview
							description={__('you can change the balloon style.', textDomain)}
						>
							<ButtonGroup className='pb-panel--colorSet -balloon'>
								{balloonStyles.map((style) => {
									const isSelected = commentStyle === style;
									const buttonId = 'pb-iconbox-colset-' + style;
									return (
										<div className='__btnBox' key={`key_style_${style}`}>
											<button
												type='button'
												id={buttonId}
												className='__btn'
												onClick={() => {
													setAttributes({
														commentStyle: style,
													});
												}}
											></button>
											<label
												htmlFor={buttonId}
												className='__label'
												data-selected={isSelected || null}
											>
												<span
													className='pb-iconbox__innerIcon'
													data-colset={style}
												>
													<span
														className={`pb-iconbox__comment -${style}`}
													>
														Text
													</span>
												</span>
											</label>
										</div>
									);
								})}
							</ButtonGroup>
						</FreePreview>
					</BaseControl>
				</PanelBody>
			</InspectorControls>
		</>
	);
}
