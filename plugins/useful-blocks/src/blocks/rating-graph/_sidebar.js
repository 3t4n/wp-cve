/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { PanelBody, BaseControl, ButtonGroup } from '@wordpress/components';

/**
 * Internal dependencies
 */
// import FreePreview from '@blocks/freePreview';
import { textDomain } from '@blocks/config';

/**
 * 設定
 */
// カラーセット
const colorSets = ['y', 'p', 'g', 'b', '1'];

/**
 * component
 */
const ColsetDOM = ({ colset }) => {
	return (
		<span className='pb-rating-graph' data-colset={colset}>
			<span className='pb-rating-graph__item'>
				<span className='pb-rating-graph__wrap'>
					<span className='pb-rating-graph__axis'>
						<span className='pb-rating-graph__scale'>
							<span className='__shape -dot'></span>
						</span>
						<span className='pb-rating-graph__scale' data-check='1'>
							<span className='__shape -dot'></span>
						</span>
						<span className='pb-rating-graph__scale'>
							<span className='__shape -dot'></span>
						</span>
					</span>
				</span>
			</span>
		</span>
	);
};

/**
 * InspectorControls
 */
export default ({ attributes, setAttributes }) => {
	const { colSet } = attributes;

	return (
		<>
			<PanelBody title={__('Color set', textDomain)} initialOpen={true}>
				<BaseControl>
					<ButtonGroup className='pb-panel--colorSet -rating-graph'>
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
											setAttributes({ colSet: setNum });
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
		</>
	);
};
