/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	BaseControl,
	Placeholder,
	ToggleControl,
	TextControl,
	TextareaControl,
	SelectControl,
	RangeControl,
	PanelBody,
	Disabled,
	Notice,
} = wp.components;

const {
	ColorPalette,
} = wp.editor;
/**
 * Add fields to Color Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setColorSettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ ( isFieldVisible('color_heading', config, attributes) ) ? (
				<BaseControl
					label={__("Heading Color")}
				>
					{ <ColorPalette
						value={ attributes['color_heading'] || '' }
						onChange={ function(val){
							setAttributes({ 'color_heading': val });
						} }
					/> }
				</BaseControl>
			) : ( null ) }

			{ ( isFieldVisible('color_heading_hover', config, attributes) ) ? (
				<BaseControl
					label={__("Heading Hover Color")}
				>
					{ <ColorPalette
						value={ attributes['color_heading_hover'] || '' }
						onChange={ function(val){
							setAttributes({ 'color_heading_hover': val });
						} }
					/> }
				</BaseControl>
			) : ( null ) }

				{ ( isFieldVisible('color_caption', config, attributes) ) ? (
				<BaseControl
					label={__("Caption Color")}
				>
					{ <ColorPalette
						value={ attributes['color_caption'] || '' }
						onChange={ function(val){
							setAttributes({ 'color_caption': val });
						} }
					/> }
				</BaseControl>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.colorSettings.fields', 'sight/colorSettings/set/fields', setColorSettings, 10);
