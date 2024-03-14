/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import {__} from '@wordpress/i18n';

const {InspectorControls} = wp.blockEditor;
const {PanelBody, PanelRow, SelectControl} = wp.components;

import {__experimentalNumberControl as NumberControl} from '@wordpress/components';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps} from '@wordpress/block-editor';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({attributes, setAttributes}) {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps}>
			<div>
				<InspectorControls>
					<PanelBody
						title={__('Banner setting', 'app-builder')}
						initialOpen={true}
					>
						<PanelRow>
							<SelectControl
								label="AdSize"
								value={attributes.adSize}
								options={[
									{label: "Standard Banner (320x50)", value: 'banner'},
									{label: "Large Banner (320x100)", value: 'largeBanner'},
									{label: "Medium Rectangle (320x250)", value: 'mediumRectangle'},
									{label: "Full-Size Banner (468x60)", value: 'fullBanner'},
									{label: "Leaderboard (728x90)", value: 'leaderboard'},
									{label: "Custom", value: 'custom'},
								]}
								onChange={(val) => setAttributes({adSize: val})}
							/>
						</PanelRow>
						{ attributes.adSize === "custom" && <PanelRow>
							<NumberControl
								label={__('Width', 'app-builder')}
								isShiftStepEnabled={true}
								onChange={(val) => setAttributes({width: parseInt(val)})}
								shiftStep={10}
								value={parseInt(attributes.width)}
							/>
						</PanelRow>}
						{attributes.adSize === "custom" && <PanelRow>
							<NumberControl
								label={__('Height', 'app-builder')}
								isShiftStepEnabled={true}
								onChange={(val) => setAttributes({height: parseInt(val)})}
								shiftStep={10}
								value={parseInt(attributes.height)}
							/>
						</PanelRow>}
					</PanelBody>
				</InspectorControls>
				<div>
					{__('Mobile advertisement banner', 'app-builder')}
				</div>
			</div>
		</div>
	);
}
