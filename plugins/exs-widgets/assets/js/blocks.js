( function( blocks, element, serverSideRender, components, blockEditor, i18n , compose, data) {
	var el = element.createElement,
		Fragment = element.Fragment,
		useState = element.useState,
		useRef = element.useRef,
		select = data.select,
		useSelect = data.useSelect,

		registerBlockType = blocks.registerBlockType,
		ServerSideRender = serverSideRender,
		InspectorControls = blockEditor ? blockEditor.InspectorControls : 'div',
		withState = compose.withState,
		//components
		Panel         = components.Panel,
		PanelBody     = components.PanelBody,
		TextControl   = components.TextControl,
		ToggleControl = components.ToggleControl,
		SelectControl = components.SelectControl,
		RangeControl  = components.RangeControl,
		__ = i18n.__;

	var isResolving = select('core').getEntityRecords('taxonomy', 'category',{'per_page': -1});

	registerBlockType( 'exs-blocks/exs-widget-posts', {
			title: __('ExS Posts', 'exs'),
			icon: 'megaphone',
			category: 'widgets',

			edit: function( props ) {
				var catOptionsBlank = [{value: '', label: __('All', 'exs')}];
				var catOptions = catOptionsBlank;
				var allCats = useSelect(function(sel) {
					if(isResolving) {
						return catOptionsBlank;
					} else {
						var isResolved = sel('core').getEntityRecords('taxonomy', 'category',{'per_page': -1});
						if(isResolved){
							var allCategories = isResolved.map(function (cat,i){
								return {value:cat.id,label:cat.name + ' (' + cat.count + ')'};
							});
							catOptions = allCategories.concat(catOptionsBlank);
							return catOptions;
						}
					}
				},[]);

				return el(
					Fragment,
					{},
					el(
						ServerSideRender,
						{
							block: 'exs-blocks/exs-widget-posts',
							attributes: props.attributes,
						}
					),
					el(
						InspectorControls,
						{},
						el(
							PanelBody,
							{},
							el(
								RangeControl,
								{
									label: __('Posts Number', 'exs'),
									value: props.attributes.number,
									min: 1,
									max: 16,
									onChange: function(val) {
										props.setAttributes(
											{
												number: val
											}
										);
										return val;
									},
								}
							),
							el(
								SelectControl,
								{
									label: __('Layout', 'exs'),
									value: props.attributes.layout,
									options: [
										{value:'default', label: __( 'Default list', 'exs' )},
										{value:'title-only', label: __( 'Only titles', 'exs' )},
										{value:'featured-columns', label: __( 'Large first post - layout 1', 'exs' )},
										{value:'featured', label: __( 'Large first post - layout 2', 'exs' )},
										{value:'featured-3', label: __( 'Large two first posts', 'exs' )},
										{value:'cols', label: __( 'Grid - 2 columns', 'exs' )},
										{value:'cols 3', label: __( 'Grid - 3 columns', 'exs' )},
										{value:'cols 4', label: __( 'Grid - 4 columns', 'exs' )},
										{value:'cols-absolute-single', label: __( '1 column - title overlap', 'exs' )},
										{value:'cols-absolute', label: __( 'Grid - 2 cols - title overlap', 'exs' )},
										{value:'cols-absolute 3', label: __( 'Grid - 3 cols - title overlap', 'exs' )},
										{value:'cols-absolute 4', label: __( 'Grid - 4 cols - title overlap', 'exs' )},
										{value:'side', label: __( 'Side featured image', 'exs' )},
									],
									onChange: function (val) {
										return props.setAttributes(
											{
												layout: val
											}
										);
									}
								}
							),
							el(
								SelectControl,
								{
									label: __('Gap (for grid layouts)', 'exs'),
									value: props.attributes.gap,
									options: [
										{value:'', label: __( 'Default', 'exs' )},
										{value:'1', label: __( '1px', 'exs' )},
										{value:'2', label: __( '2px', 'exs' )},
										{value:'3', label: __( '3px', 'exs' )},
										{value:'4', label: __( '4px', 'exs' )},
										{value:'5', label: __( '5px', 'exs' )},
										{value:'10', label: __( '10px', 'exs' )},
										{value:'15', label: __( '15px', 'exs' )},
										{value:'20', label: __( '20px', 'exs' )},
										{value:'30', label: __( '30px', 'exs' )},
										{value:'40', label: __( '40px', 'exs' )},
										{value:'50', label: __( '50px', 'exs' )},
										{value:'60', label: __( '60px', 'exs' )},
									],
									onChange: function (val) {
										return props.setAttributes(
											{
												gap: val
											}
										);
									}
								}
							),
							el(
								SelectControl,
								{
									label: __('Category', 'exs'),
									value: props.attributes.category,
									options: allCats,
									onChange: function (val) {
										return props.setAttributes(
											{
												category: val
											}
										);
									}
								}
							),
							el(
								TextControl,
								{
									label: __('"Read More" text', 'exs'),
									value: props.attributes.read_all,
									onChange: function (val) {
										return props.setAttributes(
											{
												read_all: val
											}
										);
									}
								}
							),
							el(
								ToggleControl,
								{
									label: __('Show date', 'exs'),
									checked: props.attributes.show_date,
									onChange: function(val) {
										return props.setAttributes(
											{
												show_date: val
											}
										);
									},
									help: function() {
										return !! props.attributes.show_date ? __('Show', 'exs') : __('Hide', 'exs');
									}
								}
							),
							el(
								ToggleControl,
								{
									label: __('Center alignment', 'exs'),
									checked: props.attributes.text_center,
									onChange: function(val) {
										return props.setAttributes(
											{
												text_center: val
											}
										);
									},
									help: function() {
										return !! props.attributes.text_center ? __('Center', 'exs') : __('Default', 'exs');
									}
								}
							),
							el(
								SelectControl,
								{
									label: __('Show categories', 'exs'),
									value: props.attributes.cats,
									options: [
										{value:'', label: __( 'No', 'exs' )},
										{value:'links-all', label: __( 'All (simple links)', 'exs' )},
										{value:'links-first', label: __( 'Only first (simple link)', 'exs' )},
										{value:'pills-all', label: __( 'All (buttons)', 'exs' )},
										{value:'pills-first', label: __( 'Only first (button)', 'exs' )}
									],
									onChange: function (val) {
										return props.setAttributes(
											{
												cats: val
											}
										);
									}
								}
							)
						)
					)
				);
			}
		}
	); //registerBlockType
}(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.components,
	window.wp.blockEditor,
	window.wp.i18n,
	window.wp.compose,
	window.wp.data
) );
