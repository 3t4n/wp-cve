jQuery(document).ready(function () {    
    class ResponsiveIframePatrick {
        constructor(){
            
            
            //setup class variables
            this.initVariables();
            
            //setup only when responsive iframe elements present
            let classSelector = '.'.concat(this.className);
            if(jQuery(classSelector).length > 0){
                //do first time resize
                this.updateIframe();
                this.updateIframe = this.updateIframe.bind(this);
                
                //setUpEventHandler
                jQuery(window).on('resize',this.updateIframe);
            }

        }

        //initiates this variables
        initVariables(){
            this.className = RESPONSIVE_IFRAME_CONSTANTS.CLASS_NAME;
            this.id=RESPONSIVE_IFRAME_CONSTANTS.ID_NAME;
            this.styleID = RESPONSIVE_IFRAME_CONSTANTS.STYLE_ID;
        }
        
        //build a css element to put onto the document, necessary to ensure !important is used and takes precedence over anything else
        buildCSS(cssAttributes){
            //initiate variables
            let styleID = '#'.concat(this.styleID);
            let className = '.'.concat(this.className);
            let css = '';
            let newLine = '\n';
            let tab = '\t';
            
            //create the inline style element,ensures it only runs once
            if(jQuery(styleID).length === 0 && jQuery(className).length > 0){
                jQuery('head:eq(0)').append('<style id="'+ this.styleID + '" type="text/css">' + '' +  '</style>');
            }
            
            //ensure the appended style is empty
            jQuery(styleID).empty();
            
            //build the css elements
            for(let i = 0; i < cssAttributes.length;i++){
                css += cssAttributes[i].id  + '{' + newLine;                              //#ID{        
                css += tab + 'max-width: ' + cssAttributes[i].maxWidth + newLine;                   //disables max width
                css += tab + 'max-height: ' + cssAttributes[i].maxWidth + newLine;                  //disables max height
                css += tab + 'width: ' + cssAttributes[i].width + newLine;                          //sets width
                css += tab + 'height: ' + cssAttributes[i].height + newLine;                        //sets height
                //The Scales
                css += tab + 'transform: ' + cssAttributes[i].scale + newLine;                      //sets scale
                css += tab + '-ms-transform: ' + cssAttributes[i].scale + newLine;                  //sets scale
                css += tab + '-moz-transform : ' + cssAttributes[i].scale + newLine;                //sets scale
                css += tab + '-o-transform: ' + cssAttributes[i].scale + newLine;                   //sets scale
                css += tab + '-webkit-transform: ' + cssAttributes[i].scale + newLine;              //sets scale
                
                //The scale Origins
                css += tab + 'transform-origin: ' + cssAttributes[i].scaleOrigin + newLine;         //sets origin
                css += tab + '-ms-transform-origin: ' + cssAttributes[i].scaleOrigin + newLine;     //sets origin
                css += tab + '-moz-transform-origin: ' + cssAttributes[i].scaleOrigin + newLine;    //sets origin
                css += tab + '-o-transform-origin: ' + cssAttributes[i].scaleOrigin + newLine;      //sets origin
                css += tab + '-webkit-transform-origin: ' + cssAttributes[i].scaleOrigin + newLine; //sets origin
                
                //finish the CSS
                css += tab + 'margin-bottom: ' + cssAttributes[i].marginBottom + newLine;           //sets bottom margin
                css += tab + 'margin-right: ' + cssAttributes[i].marginRight + newLine;             //sets margin-right
                css += '} ' + newLine;                                                    //Close up
            }
            //place the css into the style element
            jQuery(styleID).append(css);
        }
        
        setWidth(selector){
            //initialize variables
            let width;
            const breakPoints = jQuery(selector).data('break-points');
    
            if(breakPoints.length > 0){
                let validBreakPoints = breakPoints.filter((bp)=>{return window.innerWidth <= bp.breakPointW});
                
                //sort to determine the lowest width valid breakPoint
                validBreakPoints.sort(function(a, b){return b.breakPointW - a.breakPointW});

                if(validBreakPoints.length > 0){
                    const activeBreakPoint = validBreakPoints[validBreakPoints.length-1];
                    const siteW = activeBreakPoint.siteW;
                    width = siteW;
                }else{
                    width = jQuery(selector).data('width-iframe');
                }
            }else{
                width = jQuery(selector).data('width-iframe'); 
            }
            
            return width;
        }

        updateIframe(){
            //initiate variables
            let defaultScaleVal=RESPONSIVE_IFRAME_CONSTANTS.DEFAULT_SCALE_VAL;
            let scale = defaultScaleVal;
            let className = '.'.concat(this.className);
            let cssAttributes = [];
            
            //loop thru each classElement
            for(let i = 0; i < jQuery(className).length;i++){
                //initiate variables
                let marginBottom;
                let marginRight;

                //create a selector for jQuery, and then grab some attributes from some elements
                let selector = className.concat(':eq(' +i+ ')');
                let id = jQuery(selector).attr('id');
                let width = this.setWidth(selector);               
                let height = jQuery(selector).data('height-iframe'); 
                let parentWidth = jQuery(selector).parent().width();
                
                //set the scale and margins, which ensure the makes the iframe responsive
                scale = parentWidth/width;
                marginBottom =-1 * height * (defaultScaleVal - scale);
                marginRight = -1 * width * (defaultScaleVal - scale);
                
                //Ensure the parent div does not have a larger height than the iframe
                jQuery(selector).parent().css({'max-height':height*scale});
                
                //build an array of cssAttribute objects
                cssAttributes.push(new RESPONSIVE_IFRAME_CONSTANTS.CSS.attributes(id,width,height,scale,marginBottom,marginRight));
                
            }
            //This builds and assigns the css style to the document itself
            this.buildCSS(cssAttributes);
        }
    }
    
    //initiates the ResponsiveiFrame class
    let responsiveIframePatrick = new ResponsiveIframePatrick();
    
    
    
});