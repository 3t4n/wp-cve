const { __ } = wp.i18n;

const {
	registerBlockType
} = wp.blocks;

const {
    RichText,
    InspectorControls,
    ColorPalette,
    BlockControls,
	AlignmentToolbar
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


registerBlockType('hotblocks/button', {
    title: "Hot Button",
    icon: 'arrow-right-alt',
    category: 'hot-blocks',
    description: __('Create a simple button. Set colors and dimensions of the button and assing a link to it.'),

    supports: {
	    align: true
	},

    attributes: {
    	textString: {
            type: 'array',
            source: 'children',
            selector: 'a'
        },
        alignment: {
			type: 'string',
		},
		fontColor: {
	        type: 'string',
	        default: 'black'
	    },
	    buttonColor: {
	    	type: 'string',
	        default: 'orange'
	    },
	    fontSize: {
	        type: 'string',
	        default: '18px'
	    },
	    buttonWidth: {
	    	type: 'string',
	        default: '200px'
	    },
	    buttonHeight: {
	    	type: 'string',
	        default: '30px'
	    },
	    buttonBorderRadius: {
	    	type: 'string',
	        default: '5px'
	    },
	    buttonLink: {
	    	type: 'string',
	        default: '#'
	    },
	    linkNewTab: {
	        type: 'boolean',
	        default: false
	    },
	    linkNewTabDisplay: {
	        type: 'string',
	        default: '_self'
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
        const { alignment, fontColor, buttonColor, fontSize, buttonWidth, buttonHeight, buttonBorderRadius, buttonLink, linkNewTab, linkNewTabDisplay } = props.attributes;

        function onTextChange(changes) {
            setAttributes({
                textString: changes
            });
        }

		function onChangeAlignment( updatedAlignment ) {
			setAttributes( { alignment: updatedAlignment } );
		}

		//create a handler that will set the color when you click on the ColorPalette
		function onTextColorChange(changes) {
		    setAttributes({
		        fontColor: changes
		    })
		}

		function onButtonColorChange(changes) {
		    setAttributes({
		        buttonColor: changes
		    })
		}

		function onFontSizeChange(changes) {
		    setAttributes({
		        fontSize: changes
		    })
		}

		function onButtonWidthChange(changes) {
		    setAttributes({
		        buttonWidth: changes
		    })
		}

		function onButtonHeightChange(changes) {
		    setAttributes({
		        buttonHeight: changes
		    })
		}

		function onButtonBorderRadiusChange(changes) {
		    setAttributes({
		        buttonBorderRadius: changes
		    })
		}

		function onButtonLinkChange(changes) {
		    setAttributes({
		        buttonLink: changes
		    })
		}

		function onLinkNewTabChange(changes) {
		    setAttributes({
		        linkNewTab: ! linkNewTab
		    });
		    if ( linkNewTab === true ) {
		    	setAttributes({
			        linkNewTabDisplay: "_self"
			    });
		    } else {
		    	setAttributes({
			        linkNewTabDisplay: "_blank"
			    });
		    }
		}

        return ([
        	<InspectorControls>
		        <PanelBody title={ __('Color Settings') } initialOpen = { false }>
	                <PanelRow
	                	className={ "flex_wrap_wrap" }>
	                    <p>{ __( 'Font Color' ) }</p>
		                <ColorPalette
				            value={ fontColor }
				            onChange={ onTextColorChange }
				        />
	                </PanelRow>
	                <PanelRow
	                	className={ "flex_wrap_wrap" }>
	                    <p>{ __( 'Button Color' ) }</p>
	                    <ColorPalette
				            value={ buttonColor }
				            onChange={ onButtonColorChange }
				        />
	                </PanelRow>
	            </PanelBody>
	            <PanelBody title={ __('Dimensions') } initialOpen = { false }>
	            	<PanelRow>
				        <TextControl
					        label={ __( 'Font Size' ) }
					        value={ attributes.fontSize }
					        onChange={ onFontSizeChange }
					    />
	                </PanelRow>
	                <PanelRow>
				        <TextControl
					        label={ __( 'Button Width' ) }
					        value={ attributes.buttonWidth }
					        onChange={ onButtonWidthChange }
					    />
	                </PanelRow>
	                <PanelRow>
					    <TextControl
					        label={ __( 'Button Height' ) }
					        value={ attributes.buttonHeight }
					        onChange={ onButtonHeightChange }
					    />
					</PanelRow>
					<PanelRow>
					    <TextControl
					        label={ __( 'Border Radius' ) }
					        value={ attributes.buttonBorderRadius }
					        onChange={ onButtonBorderRadiusChange }
					    />
					</PanelRow>
	            </PanelBody>
	            <PanelBody title={ __('Link') } initialOpen = { false }>
	            	<PanelRow>
					    <TextControl
					        label={ __( 'Button Link' ) }
					        value={ attributes.buttonLink }
					        onChange={ onButtonLinkChange }
					    />
					</PanelRow>
					<PanelRow>
						<div>{ __('Open Link in New Tab') }</div>
				        <FormToggle
					        checked={ linkNewTab }
					        onChange={ onLinkNewTabChange } 
					    />
				    </PanelRow>
	            </PanelBody>
			</InspectorControls>,
            <BlockControls key="controls">
                <AlignmentToolbar
					value={ attributes.alignment }
					onChange={ onChangeAlignment }
				/>
            </BlockControls>,
            <div className={ className } style={{backgroundColor: buttonColor, width: buttonWidth, borderRadius: buttonBorderRadius}}>
		    	<RichText
		            tagName="a"
		            value={attributes.textString}
		            onChange={onTextChange}
		            placeholder="Enter button text"
		            style={{textAlign: alignment, fontSize: fontSize, color: fontColor, height: buttonHeight, lineHeight: buttonHeight}}
		            target={attributes.linkNewTabDisplay}
		            />
			</div>
		]);
    },

    // again, props are automatically passed to save and edit
	save(props) {

	    const { attributes, className } = props;
	    const { alignment, fontColor, buttonColor, fontSize, buttonWidth, buttonHeight, buttonBorderRadius, buttonLink, linkNewTab, linkNewTabDisplay } = props.attributes;

	    return (
	    	<div className={ className } style={{backgroundColor: buttonColor, width: buttonWidth, borderRadius: buttonBorderRadius}}>
		        <a href={attributes.buttonLink} target={attributes.linkNewTabDisplay} style={{textAlign: attributes.alignment, fontSize: fontSize, color: fontColor, height: buttonHeight, lineHeight: buttonHeight}}>
					{attributes.textString}
				</a>
			</div>
	    );
	}
});