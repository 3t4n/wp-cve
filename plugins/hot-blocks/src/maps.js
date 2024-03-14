const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    InspectorControls,
    BlockControls,
	AlignmentToolbar
} = wp.editor;

const {
    PanelBody,
    PanelRow,
    TextControl
} = wp.components;
 
const {
    Fragment
} = wp.element;


registerBlockType('hotblocks/map', {
    title: "Hot Map",
    icon: 'location-alt',
    category: 'hot-blocks',
    description: __('Enter your location or address and map will be fetched from Google Maps.'),

    supports: {
	    align: true
	},

    attributes: {
        mapLocation: {
	    	type: 'string',
	        default: 'guggenheim museum new york'
	    },
	    mapHeight: {
	    	type: 'string',
	        default: '400px'
	    },
    },

    // props are passed to edit by default
    // props contains things like setAttributes and attributes
    edit(props) {

        // we are peeling off the things we need
        const {
        	setAttributes,
        	attributes,
        	className, // The class name as a string!
        	focus // this is "true" when the user clicks on the block
        } = props;
        const { mapLocation, mapHeight } = props.attributes;

        function onMapLocationChange(changes) {
            setAttributes({
                mapLocation: changes
            });
        }

		function onMapHeightChange(changes) {
		    setAttributes({
		        mapHeight: changes
		    })
		}

        return ([
		    <InspectorControls>
		    	<div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Location' ) }
				        value={ attributes.mapLocation }
				        onChange={ onMapLocationChange }
				    />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Height' ) }
				        value={ attributes.mapHeight }
				        onChange={ onMapHeightChange }
				    />
			    </div>
			</InspectorControls>,
		    <div className={className}>
		        <iframe
		        	src={'https://maps.google.com/maps?q=' + attributes.mapLocation + '&ie=UTF8&view=map&saddr=' + attributes.mapLocation + '&f=q&output=embed'}
		        	style={{ height: mapHeight }}>
		        </iframe>
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { mapHeight } = props.attributes;

	    return (
	        <div className={className}>
	            <iframe
	            	src={'https://maps.google.com/maps?q=' + attributes.mapLocation + '&ie=UTF8&view=map&saddr=' + attributes.mapLocation + '&f=q&output=embed'}
	            	style={{ height: mapHeight }}>
	            </iframe>
	        </div>
	    );
	}
});