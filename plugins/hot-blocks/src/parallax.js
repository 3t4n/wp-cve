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


registerBlockType('hotblocks/parallax', {
    title: "Hot Parallax",
    icon: 'image-flip-vertical',
    category: 'hot-blocks',
    description: __('This block is a placeholder for other blocks. Assign a background image to this block and it will use a parallax scrolling effect. Make sure you added one or more blocks into the Hot Parallax block as well. ;)'),

    supports: {
	    align: true,
	    anchor: true
	},

    attributes: {
	    overlayColor: {
	    	type: 'string',
	        default: 'transparent'
	    },
	    backgroundImage: {
	        type: 'string',
	        default: null // no image by default!
	    },
	    maxContentWidth: {
	        type: 'string',
	        default: '600px'
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
        const { overlayColor, backgroundImage, maxContentWidth } = props.attributes;

        //create a handler that will set the color when you click on the ColorPalette
		function onOverlayColorChange(changes) {
		    setAttributes({
		        overlayColor: changes
		    })
		}

		// handles image object
		function onImageSelect(imageObject) {
		    setAttributes({
		        backgroundImage: imageObject.sizes.full.url
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
			        <p>{ __('Overlay Color') }</p>
			        <ColorPalette
			            value={overlayColor}
			            onChange={onOverlayColorChange}
			        />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <p>{ __('Background Image') }</p>
			        <MediaUpload
			            onSelect={onImageSelect}
			            type="image"
			            value={backgroundImage}
			            render={({ open }) => (
			                <button className="button" onClick={open}>
			                    Add Image
			                </button>
			            )}
			        />
			        <p></p>
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
		            backgroundImage: `url(${backgroundImage})`,
		            backgroundAttachment: 'fixed',
		            backgroundSize: 'cover'
		        }}>
		        <div className="overlay" style={{ backgroundColor:overlayColor }}></div>
		        <div className="parallax_content" style={{ width:maxContentWidth }}>
		        	<InnerBlocks />
		        </div>
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { overlayColor, backgroundImage, maxContentWidth } = props.attributes;

	    return (
	        <div 
	        	className={className}
		        style={{
		            backgroundImage: `url(${backgroundImage})`,
		            backgroundAttachment: 'fixed',
		            backgroundSize: 'cover'
		        }}>
	            <div className="overlay" style={{ backgroundColor:overlayColor }}></div>
	            <div className="parallax_content" style={{ width:maxContentWidth }}>
	            	<InnerBlocks.Content />
	            </div>
	        </div>
	    );
	}
});
