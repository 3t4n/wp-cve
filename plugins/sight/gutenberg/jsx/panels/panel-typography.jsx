/**
 * Components dependencies
 */
import DimensionControl from '../components/dimension-control';

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
/**
 * Add fields to Typography Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setTypographySettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ ( isFieldVisible('typography_heading', config, attributes) ) ? (
				<DimensionControl
					label={__("Heading Font Size")}
					value={ attributes['typography_heading'] }
					onChange={ function(val){
						setAttributes({ 'typography_heading': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('typography_heading_tag', config, attributes) ) ? (
				<SelectControl
					label={__("Heading Tag")}
					value={attributes['typography_heading_tag']}
					options={
						[
							{ value: 'h1', label: __('H1') },
							{ value: 'h2', label: __('H2') },
							{ value: 'h3', label: __('H3') },
							{ value: 'h4', label: __('H4') },
							{ value: 'h5', label: __('H5') },
							{ value: 'h6', label: __('H6') },
							{ value: 'p', label: __('P') },
							{ value: 'div', label: __('DIV') },
						]
					}
					onChange={function (val) {
						setAttributes({ 'typography_heading_tag': val });
					}}
				/>
			) : ( null ) }

			{ ( isFieldVisible('typography_caption', config, attributes) ) ? (
				<DimensionControl
					label={__("Caption Font Size")}
					value={ attributes['typography_caption'] }
					onChange={ function(val){
						setAttributes({ 'typography_caption': val });
					} }
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.typographySettings.fields', 'sight/typographySettings/set/fields', setTypographySettings, 10);
