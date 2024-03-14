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
 * Add fields to Media Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setMediaSettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ ( isFieldVisible('attachment_lightbox', config, attributes) ) ? (
				<ToggleControl
					label={__("Enable lightbox")}
					checked={ attributes['attachment_lightbox'] }
					onChange={ function(val){
						setAttributes({ 'attachment_lightbox': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('attachment_lightbox_icon', config, attributes) ) ? (
				<ToggleControl
					label={__("Display lightbox zoom icon")}
					checked={ attributes['attachment_lightbox_icon'] }
					onChange={ function(val){
						setAttributes({ 'attachment_lightbox_icon': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('attachment_link_to', config, attributes) ) ? (
				<SelectControl
					label={__("Link To")}
					value={ attributes['attachment_link_to'] }
					options={
						( 'categories' === attributes['source'] && ! config.archive ) ? [
							{ value: 'none', label: __('None') },
							{ value: 'media', label: __('Media File') },
						] : [
							{ value: 'none', label: __('None') },
							{ value: 'media', label: __('Media File') },
							{ value: 'page', label: __('Page') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'attachment_link_to': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('attachment_view_more', config, attributes) ) ? (
				<ToggleControl
					label={__("Enable view more")}
					checked={ attributes['attachment_view_more'] }
					onChange={ function(val){
						setAttributes({ 'attachment_view_more': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('attachment_size', config, attributes) ) ? (
				<SelectControl
					label={__("Size")}
					value={ attributes['attachment_size'] }
					options={ config.image_sizes }
					onChange={ function(val){
						setAttributes({ 'attachment_size': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('attachment_orientation', config, attributes) ) ? (
				<SelectControl
					label={__("Orientation")}
					value={ attributes['attachment_orientation'] }
					options={
						[
							{ value: 'original', label: __('Original') },
							{ value: 'landscape-4-3', label: __('Landscape 4:3') },
							{ value: 'landscape-3-2', label: __('Landscape 3:2') },
							{ value: 'landscape-16-9', label: __('Landscape 16:9') },
							{ value: 'portrait-3-4', label: __('Portrait 3:4') },
							{ value: 'portrait-2-3', label: __('Portrait 2:3') },
							{ value: 'square', label: __('Square') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'attachment_orientation': val });
					} }
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.mediaSettings.fields', 'sight/mediaSettings/set/fields', setMediaSettings, 10);
