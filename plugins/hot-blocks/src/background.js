const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    InspectorControls,
    ColorPalette,
    MediaUpload,
    BlockControls,
	AlignmentToolbar,
	InnerBlocks
} = wp.editor;

const {
    PanelBody,
    PanelRow,
    TextControl
} = wp.components;
 
const {
    Fragment
} = wp.element;


registerBlockType('hotblocks/background-color', {
    title: "Background Color",
    icon: 'admin-appearance',
    category: 'hot-blocks',
    description: __('This block is a placeholder for other blocks. You can assign a background color to this block and set width. Make sure you added one or more blocks into the Background Color block as well. ;)'),

    supports: {
	    align: true,
	    anchor: true
	},

    attributes: {
	    backgroundColor: {
	    	type: 'string',
	        default: 'transparent'
	    },
	    maxContentWidth: {
	        type: 'string',
	        default: 'auto'
	    }
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
        const { backgroundColor, maxContentWidth } = props.attributes;

        //create a handler that will set the color when you click on the ColorPalette
		function onBackgroundColorChange(changes) {
		    setAttributes({
		        backgroundColor: changes
		    })
		}

		function onMaxContentWidthChange(changes) {
		    setAttributes({
		        maxContentWidth: changes
		    })
		}

        return ([
		    <InspectorControls>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <p>{ __('Background Color') }</p>
			        <ColorPalette
			            value={backgroundColor}
			            onChange={onBackgroundColorChange}
			        />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <TextControl
				        label={ __( 'Content Width' ) }
				        value={ attributes.maxContentWidth }
				        onChange={ onMaxContentWidthChange }
				    />
			    </div>
			</InspectorControls>,
		    <div 
		        className={className}
		        style={{
		            backgroundColor:backgroundColor
		        }}>
		        <div className="background_content" style={{ width:maxContentWidth }}>
		        	<InnerBlocks />
		        </div>
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { backgroundColor, maxContentWidth } = props.attributes;

	    return (
	        <div 
	        	className={className}
		        style={{
		            backgroundColor:backgroundColor
		        }}>
	            <div className="background_content" style={{ width:maxContentWidth }}>
	            	<InnerBlocks.Content />
	            </div>
	        </div>
	    );
	}
});
