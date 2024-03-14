const {registerBlockType} = wp.blocks; //Blocks API
const {createElement} = wp.element; //React.createElement
const {__} = wp.i18n; //translation functions
const {InspectorControls} = wp.blockEditor; //Block inspector wrapper
const {TextControl,SelectControl,PanelBody,ServerSideRender} = wp.components; //WordPress form inputs and server-side renderer

registerBlockType( 'yith-slider-for-page-builders/slider-block', {
	title: __( 'YITH Slider for page builders' ), // Block title.
	category:  __( 'media', 'yith-slider-for-page-builders' ), //category
	attributes:  {
		slider : {
			default: '',
		},
	},
	icon: 'cover-image',
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		//Function to update slider id attribute
		function changeId(slider){
			setAttributes({slider});
		}
		
		//Display block preview and UI
        let yithSliderBlock = createElement('div', {}, [
            createElement( ServerSideRender, {
                block: 'yith-slider-for-page-builders/slider-block',
                attributes: attributes,
                key: 1
            } ),
            createElement( InspectorControls, { key: 2 },
                [
                    createElement( PanelBody, { key:attributes.slider }, [
                        createElement( SelectControl, {
                            value: attributes.slider,
                            options: yith_slider_for_page_builders_block_localized_array.slidersArray,
                            label: __( 'Slider to show', 'yith-slider-for-page-builders' ),
                            multiple: false,
                            onChange: changeId,
                            key : attributes.slider
                        } ),
                    ] )
                ]
            )
        ] );

		return yithSliderBlock;
	},
	save(){
		return null;//save has to exist. This all we need
	}
});
