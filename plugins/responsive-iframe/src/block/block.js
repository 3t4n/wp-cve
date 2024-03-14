/**
 * BLOCK: responsive-iframes
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './editor.scss';
import './style.scss';

//  Import JS
import Inspector from './inspector';
import attributes from '../js/attributes';

// Import Depreciated Blocks
import v1_1_1 from './depreciated/v1_1_1';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const { Fragment } = wp.element;
const { Placeholder } = wp.components;
const { Component } = wp.element;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'patrickp/responsive-iframes', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Responsive iframes' ), // Block title.
	icon: 'desktop', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'embed', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'responsive-iframes' ),
		__( 'iframe' ),
		__( 'responsive' ),
	],
    attributes,
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Component.
	 */
	edit: class ResponsiveIframe extends Component{
        constructor(props){
            super(props);
            
            //reference to the ResponsiveIframe class, neede to access the static variable index
            let className=ResponsiveIframe;
            
            //setup reference to the setAttributes function, then set the id
            let {setAttributes} = this.props;
            let id = RESPONSIVE_IFRAME_CONSTANTS.ID_NAME.concat(className.index);
            
            setAttributes({
                id:id
            });
            
            //incriment the static index variable, this is necessary to ensure the ID on line 79 is unique
            className.index++;
        }
        
        //needed to ensure id is unique
        static index = 0;
                
        //Edit stuff that just got mounted
        componentDidMount(){
            //initiate variables and references
            let {attributes} = this.props;
            let id = '#'.concat(attributes.id);
            
            //set the attributes for the iframe element, max width needed to be set to none as 2017 theme sets a max width to the iframe in the editor
            jQuery(id).css({'width':attributes.iFrameWidth,'height':attributes.iFrameHeight,'maxWidth':'none'});
            
            //scale the iframe for the first time
            this.scaleIframe();
        }
        
        //update the iframe on each edit
        componentDidUpdate(){
            ///initiate variables and references
            let {attributes} = this.props;
            let id = '#'.concat(attributes.id);
            
            //set the attributes for the iframe element
            jQuery(id).css({'width':attributes.iFrameWidth,'height':attributes.iFrameHeight})
            
            //scale the iframe on each update
            this.scaleIframe();
        }
        
        //scales the iframe so that it fits within its parent's box
        scaleIframe(){
            //initiate variables
            let defaultScaleVal = RESPONSIVE_IFRAME_CONSTANTS.DEFAULT_SCALE_VAL;
            let scale = defaultScaleVal;
            let marginBottom;
            let marginRight;
            let {attributes} = this.props;
            let id = '#'.concat(attributes.id)
            
            //grab the iframe attributes and the parent attributes
            let parentWidth = jQuery(id).parent().width();
            let iframeWidth = jQuery(id).width();
            let iframeHeight = jQuery(id).height();

            //set the updated scale, and the margins
            scale = parentWidth/iframeWidth;
            marginBottom =-1 * iframeHeight * (defaultScaleVal - scale);
            marginRight = -1 * iframeWidth * (defaultScaleVal - scale);
            
            //assign the iframe attributes
            jQuery(id).css(this.buildCSS(scale,marginBottom,marginRight));
            
        }

        //it will build the css object for the jQuery.css function to receive
        buildCSS(scale,marginBottom,marginRight){
            let scaleStrn = 'scale('+scale+')';
            let origin = '0 0';
            let css = {
                //transforms
                'transform':scaleStrn,
                '-ms-transform':scaleStrn,
                '-moz-transform':scaleStrn,
                '-o-transform':scaleStrn,
                '-webkit-transform':scaleStrn,
                
                //origins
                'transform-origin':origin,
                '-ms-transform-origin':origin,
                '-moz-transform-origin':origin,
                '-o-transform-origin':origin,
                '-webkit-transform-origin':origin,
                
                //margins
                'margin-bottom':marginBottom,
                'margin-right':marginRight
            };
            
            return css;
        }
        
        render(){
            //initiate references
            let {attributes,setAttributes} = this.props;
            
            //additional attributes
            let scrollBar = attributes.scrollBarChecked ? 'yes' : 'no';
            let border = attributes.borderChecked ? {} : {border:'none'};
            let maxWidth = attributes.wrapperWidth ? {maxWidth:attributes.wrapperWidth + '%'} : {};
            let center = {marginLeft:'auto',marginRight:'auto'};
            let className = attributes.className ? attributes.wrapperClass + ' ' + attributes.className : attributes.wrapperClass;
            
            //style for the wrapper
            let style = Object.assign(center,maxWidth);
            
            //setup the display block and the placeholder
            let block = attributes.iFrameURL ? 
                <iframe id={attributes.id} style={border} class={RESPONSIVE_IFRAME_CONSTANTS.CLASS_NAME} scrolling={scrollBar} src={attributes.iFrameURL}></iframe> 
                : <Placeholder> <p>Enter a url into the block settings to the right.</p> </Placeholder>;

            return (
                <Fragment>
                    <Inspector {...this.props}/>
                    <div style={style} className={className}>
                        {block}
                    </div>
                </Fragment>
            );
        }
    },
    	
	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 *
	 * @param {Object} props Props.
	 * @returns {Mixed} JSX Frontend HTML.
	 */
	save: ( props ) => {
        //initiate reference
        let {attributes} = props;
        
        //additional attributes
        let scrollBar = attributes.scrollBarChecked ? 'yes' : 'no';
        let border = attributes.borderChecked ? {} : {border:'none'};
        let maxWidth = attributes.wrapperWidth ? {maxWidth:attributes.wrapperWidth + '%'} : {};
        let center = {marginLeft:'auto',marginRight:'auto'};
        let className = attributes.className ? attributes.wrapperClass + ' ' + attributes.className : attributes.wrapperClass;
        let breakPoints = JSON.stringify(attributes.breakPoints);

        //user using advanced settings to specify max-width of iframe
        if(attributes.useMaxWidth){
            maxWidth = {maxWidth:attributes.maxWidth + "px"};
        }
        //style for the wrapper
        let style = Object.assign(center,maxWidth);
        
        //The block holds data attributes for the actual width and height of the iframe
        let block = <iframe style={border} id={attributes.id} className={RESPONSIVE_IFRAME_CONSTANTS.CLASS_NAME} data-break-points={breakPoints} data-width-iframe={attributes.iFrameWidth} data-height-iframe={attributes.iFrameHeight} scrolling={scrollBar} src={attributes.iFrameURL}></iframe> 
    
		return (
            <Fragment>
                <div style={style} className={className}>
                    {block}
                </div>
            </Fragment>
		);
	},
    deprecated:[
        v1_1_1
    ]
} );
