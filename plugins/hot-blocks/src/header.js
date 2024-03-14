const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    RichText,
    InspectorControls,
    ColorPalette,
    MediaUpload,
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

registerBlockType('hotblocks/header', {
    title: "Hot Header",
    icon: 'format-image',
    category: 'hot-blocks',
    description: __('Add header of your page. This block allows you to set your heading and intro text. You can add a background image with overlay color.'),

    supports: {
	    align: true
	},

    attributes: {
        headingString: {
            type: 'array',
            source: 'children',
            selector: 'h1'
        },
        textString: {
            type: 'array',
            source: 'children',
            selector: 'p'
        },
	    fontColor: {
	        type: 'string',
	        default: 'black'
	    },
	    overlayColor: {
	    	type: 'string',
	        default: 'transparent'
	    },
	    backgroundImage: {
	        type: 'string',
	        default: null // no image by default!
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
        const { fontColor, overlayColor, backgroundImage } = props.attributes;

        // This function is called when RichText changes
        // By default the new string is passed to the function
        // not an event object like react normally would do
        function onHeadingChange(changes) {
            setAttributes({
                headingString: changes
            });
        }

        function onTextChange(changes) {
            setAttributes({
                textString: changes
            });
        }

        //create a handler that will set the color when you click on the ColorPalette
		function onTextColorChange(changes) {
		    setAttributes({
		        fontColor: changes
		    })
		}

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

        return ([
		    <InspectorControls>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <p>{ __('Font Color') }</p>
			        <ColorPalette
			            value={fontColor}
			            onChange={onTextColorChange}
			        />
			    </div>
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
			    </div>
			</InspectorControls>,
		    <div 
		        className={className}
		        style={{
		            backgroundImage: `url(${backgroundImage})`,
		            backgroundSize: 'cover',
		            backgroundPosition: 'center'
		        }}>
		        <div className="overlay" style={{ backgroundColor:overlayColor }}></div>
		        <RichText
		            tagName="h1"
		            className="content" // adding a class we can target
		            value={attributes.headingString}
		            onChange={onHeadingChange}
		            placeholder={ __('Enter your heading here!') }
		            style={{color: fontColor}}
		            />
		        <RichText
		            tagName="p"
		            className="content" // adding a class we can target
		            value={attributes.textString}
		            onChange={onTextChange}
		            placeholder={ __('Enter your text here!') }
		            style={{color: fontColor}}
		            />
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { fontColor, overlayColor, backgroundImage } = props.attributes;

	    return (
	        <div 
	        	className={className}
		        style={{
		            backgroundImage: `url(${backgroundImage})`,
		            backgroundSize: 'cover',
		            backgroundPosition: 'center'
		        }}>
	            <div className="overlay" style={{ backgroundColor:overlayColor }}></div>
	            <h1 class="content" style={{ color:fontColor }}>{attributes.headingString}</h1>
	            <p class="content" style={{ color:fontColor }}>{attributes.textString}</p>
	        </div>
	    );
	}
});
