/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	ToggleControl,
	RangeControl,
} = wp.components;


/**
 * Add fields to Meta Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setMetaSettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ ( isFieldVisible('meta_title', config, attributes) ) ? (
				<ToggleControl
					label={__("Display item title")}
					checked={ attributes['meta_title'] }
					onChange={ function(val){
						setAttributes({ 'meta_title': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('meta_caption', config, attributes) ) ? (
				<ToggleControl
					label={__("Display item caption")}
					checked={ attributes['meta_caption'] }
					onChange={ function(val){
						setAttributes({ 'meta_caption': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('meta_caption_length', config, attributes) ) ? (
				<RangeControl
					label={__("Caption length")}
					value={ attributes['meta_caption_length'] }
					min={ 1 }
					max={ 1000 }
					onChange={ function(val){
						setAttributes({ 'meta_caption_length': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('meta_category', config, attributes) ) ? (
				<ToggleControl
					label={__("Display meta category")}
					checked={ attributes['meta_category'] }
					onChange={ function(val){
						setAttributes({ 'meta_category': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('meta_date', config, attributes) ) ? (
				<ToggleControl
					label={__("Display meta date")}
					checked={ attributes['meta_date'] }
					onChange={ function(val){
						setAttributes({ 'meta_date': val });
					} }
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.metaSettings.fields', 'sight/metaSettings/set/fields', setMetaSettings, 10);
