/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	ToggleControl,
	SelectControl,
} = wp.components;

/**
 * Add fields to Block Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setStandardBlockSettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	return (
		<div>
			{ fields }

			{ ( isFieldVisible('standard_filter_items', config, attributes) ) ? (
				<ToggleControl
					label={__("Display category filter")}
					checked={ attributes['standard_filter_items'] }
					onChange={ function(val){
						setAttributes({ 'standard_filter_items': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('standard_pagination_type', config, attributes) ) ? (
				<SelectControl
					label={__("Pagination type")}
					value={attributes['standard_pagination_type']}
					options={
						[
							{ value: 'none', label: __('None') },
							{ value: 'ajax', label: __('Load More') },
							{ value: 'infinite', label: __('Infinite Load') },
						]
					}
					onChange={function (val) {
						setAttributes({ 'standard_pagination_type': val });
					}}
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.blockSettings.fields', 'sight/standardBlockSettings/set/fields', setStandardBlockSettings, 15 );
