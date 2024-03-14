/**
 * Components dependencies
 */
import ReactSelectControl from '../components/react-select-control';

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
 * Add fields to Query Settings.
 *
 * @param {JSX}    fields Original block.
 * @param {Object} props  Block data.
 * @param {Object} config Block config.
 *
 * @return {JSX} Block.
 */
function setQuerySettings(fields, props, config) {
	const {
		attributes,
		setAttributes,
		isFieldVisible,
	} = props;

	if ( 'projects' !== attributes['source'] && 'categories' !== attributes['source'] ) {
		return fields;
	}

	return (
		<div>
			{ ( isFieldVisible('projects_filter_post_type', config, attributes) ) ? (
				<SelectControl
					label={__("Filter by Post Type")}
					value={ attributes['projects_filter_post_type'] }
					options={ config.post_types_stack }
					onChange={ function(val){
						setAttributes({ 'projects_filter_post_type': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('projects_filter_categories', config, attributes) ) ? (
				<ReactSelectControl
					label={__("Filter by Categories")}
					isMulti={ true }
					val={ attributes['projects_filter_categories'] }
					options={ config.categories_stack }
					onChange={ function(val){
						setAttributes({ 'projects_filter_categories': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('projects_filter_offset', config, attributes) ) ? (
				<TextControl
					label={__("Offset")}
					value={ attributes['projects_filter_offset'] }
					onChange={ function(val){
						setAttributes({ 'projects_filter_offset': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('projects_orderby', config, attributes) ) ? (
				<SelectControl
					label={__("Order By")}
					value={ attributes['projects_orderby'] }
					options={
						[
							{ value: 'date', label: __('Published Date') },
							{ value: 'modified', label: __('Modified Date') },
							{ value: 'title', label: __('Title') },
							{ value: 'rand', label: __('Random') },
							{ value: 'views', label: __('Views') },
							{ value: 'comment_count', label: __('Comment Count') },
							{ value: 'ID', label: __('ID') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'projects_orderby': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('projects_order', config, attributes) ) ? (
				<SelectControl
					label={__("Order")}
					value={ attributes['projects_order'] }
					options={
						[
							{ value: 'DESC', label: __('Descending') },
							{ value: 'ASC', label: __('Ascending') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'projects_order': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('categories_filter_ids', config, attributes) ) ? (
				<ReactSelectControl
					label={__("Filter by Categories")}
					isMulti={ true }
					val={ attributes['categories_filter_ids'] }
					options={ config.categories_stack }
					onChange={ function(val){
						setAttributes({ 'categories_filter_ids': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('categories_orderby', config, attributes) ) ? (
				<SelectControl
					label={__("Order By")}
					value={ attributes['categories_orderby'] }
					options={
						[
							{ value: 'name', label: __('Name') },
							{ value: 'count', label: __('Posts count') },
							{ value: 'include', label: __('Filter include') },
							{ value: 'id', label: __('ID') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'categories_orderby': val });
					} }
				/>
			) : ( null ) }

			{ ( isFieldVisible('categories_order', config, attributes) ) ? (
				<SelectControl
					label={__("Order")}
					value={ attributes['categories_order'] }
					options={
						[
							{ value: 'DESC', label: __('Descending') },
							{ value: 'ASC', label: __('Ascending') },
						]
					}
					onChange={ function(val){
						setAttributes({ 'categories_order': val });
					} }
				/>
			) : ( null ) }
		</div>
	);
}
addFilter('sight.querySettings.fields', 'sight/querySettings/set/fields', setQuerySettings, 10);
