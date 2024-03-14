const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    RichText,
    InspectorControls,
    ColorPalette,
    BlockControls,
	AlignmentToolbar,
	InnerBlocks
} = wp.editor;

const {
    PanelBody,
    PanelRow,
    TextControl,
    FormToggle
} = wp.components;
 
const {
    Fragment
} = wp.element;

registerBlockType('hotblocks/accordion', {
    title: "Hot Accordion",
    icon: 'list-view',
    category: 'hot-blocks',
    description: __('Accordion block contains heading and placeholder for other blocks. Click on the heading reveals the content below it.'),

    supports: {
	    align: true
	},

    attributes: {
        headingString: {
            type: 'array',
            source: 'children',
            selector: 'h4'
        },
	    fontColor: {
	        type: 'string',
	        default: 'black'
	    },
	    tabOpen: {
	        type: 'boolean',
	        default: false
	    },
	    tabOpenDisplay: {
	        type: 'string',
	        default: 'none'
	    },
	    activeTabClass: {
	        type: 'string',
	        default: 'accordion-heading'
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
        const { fontColor, tabOpen, tabOpenDisplay, activeTabClass } = props.attributes;

        // This function is called when RichText changes
        // By default the new string is passed to the function
        // not an event object like react normally would do
        function onHeadingChange(changes) {
            setAttributes({
                headingString: changes
            });
        }

        //create a handler that will set the color when you click on the ColorPalette
		function onTextColorChange(changes) {
		    setAttributes({
		        fontColor: changes
		    })
		}

		function onTabOpenChange(changes) {
		    setAttributes({
		        tabOpen: ! tabOpen
		    });
		    if ( tabOpen === true ) {
		    	setAttributes({
			        tabOpenDisplay: "none",
			        activeTabClass: "accordion-heading"
			    });
		    } else {
		    	setAttributes({
			        tabOpenDisplay: "block",
			        activeTabClass: "accordion-heading active_tab"
			    });
		    }
		}

        return ([
		    <InspectorControls>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <p>{ __('Heading Color') }</p>
			        <ColorPalette
			            value={ fontColor }
			            onChange={ onTextColorChange }
			        />
			    </div>
			    <div style={{
		            padding:"4px 16px"
		        }}>
			        <p>{ __('Tab Open By Default') }</p>
			        <FormToggle 
				        checked={ tabOpen }
				        onChange={ onTabOpenChange } 
				    />
			    </div>
			</InspectorControls>,
		    <div className={className}>
		        <RichText
		            tagName="h4"
		            className={activeTabClass} // adding a class we can target
		            value={attributes.headingString}
		            onChange={onHeadingChange}
		            placeholder={ __('Enter your heading here!') }
		            style={{color: fontColor}}
		            />
		        <div className="accordion-content" style={{display: tabOpenDisplay}}>
		        	<InnerBlocks />
		        </div>
		    </div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { fontColor, tabOpen, tabOpenDisplay, activeTabClass } = props.attributes;

	    return (
	        <div className={className}>
	            <h4 className={activeTabClass} style={{ color:fontColor }}>{attributes.headingString}</h4>
	            <div className="accordion-content" style={{display: tabOpenDisplay}}>
	            	<InnerBlocks.Content />
	            </div>
	        </div>
	    );
	}
});