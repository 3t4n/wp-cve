( function () {
	const {registerBlockType} = wp.blocks; //Blocks API
	const {createElement,useState} = wp.element; //React.createElement
	const {__,_x} = wp.i18n; //translation functions
	const {InspectorControls} = wp.blockEditor; //Block inspector wrapper
	const {TextControl,SelectControl,PanelBody,ServerSideRender,RadioControl } = wp.components; //WordPress form inputs and server-side renderer

	registerBlockType( 'yith-proteo-toolkit/testimonials-block', {
		title: _x( 'YITH Proteo Toolkit Testimonials', 'Gutenberg block title', 'yith-proteo-toolkit' ), // Block title.
		category:  'design', // Category.
		attributes:  {
			names : {
				type: 'array',
				default: [],
			},
			layout : {
				type: 'string',
				default: 'grid',
			},
			elements : {
				type: 'array',
				default: yith_proteo_toolkit_testimonials_block_localized_array.elements_to_show,
			},
		},
		icon: 'cover-image',
		edit(props){
			const attributes =  props.attributes;
			const setAttributes =  props.setAttributes;
			//Function to update names attribute
			function showTestimonials(names, layout){
				names && setAttributes({names});
				layout && setAttributes({layout});
			}
			const [ option, setOption ] = useState( attributes.layout || 'grid' );
			
			//Display block preview and UI
			let testimonialsBlock = createElement('div', {}, [
				createElement( ServerSideRender, {
					block: 'yith-proteo-toolkit/testimonials-block',
					attributes: attributes,
					key: 1
					}
				),
				createElement( InspectorControls, { key: 2 },
					[
						createElement( PanelBody, { key:3 }, [
							createElement( SelectControl, {
								value: attributes.names,
								options: yith_proteo_toolkit_testimonials_block_localized_array.testimonials_list,
								label: _x( 'Testimonials to show', 'Gutenbeg block option description', 'yith-proteo-toolkit' ),
								multiple: true,
								onChange: showTestimonials,
								key : 4,
								className: 'yith-proteo-toolkit-select2',
							} ),
						] ),
						createElement( PanelBody, { key:5 }, [
							createElement( RadioControl, {
								help: _x( 'Select the layout for the testimonials', 'Gutenberg block option description', 'yith-proteo-toolkit' ),
								selected: option,
								options: [
									{ label: _x('List', 'Gutenberg block option value', 'yith-proteo-toolkit'), value: 'list' },
									{ label: _x('Grid', 'Gutenberg block option value', 'yith-proteo-toolkit'), value: 'grid' },
								],
								label: _x( 'Layout', 'Gutenberg block option name', 'yith-proteo-toolkit' ),
								multiple: false,
								onChange: ( value ) => { setOption( value ); showTestimonials( null, value ) },
								key : 6,
							} ),
						] ),
					]
				)
			] );


			return testimonialsBlock;
		},
		save(){
			return null;//save has to exist. This all we need
		}
	});
	}
)();