const RESPONSIVE_IFRAME_CONSTANTS = {
    WRAPPER_CLASS_NAME:'responsiveIframeWrapper',
    CLASS_NAME:'responsiveIframePatrickP',
    ID_NAME:'responsiveIframe',
    STYLE_ID:'responsiveIframeStyle',
    CHK_BX_CLASS:'responsiveFrameChkBx',
    CSS:{
        //structures
        attributes:function(id,width,height,scale,marginBottom,marginRight){
            
            //Object constructor
            this.id = '#' + id;
            this.width = width + 'px' + ' !important;';
            this.height = height + 'px' + ' !important;';
            this.scale = 'scale(' + scale + ') !important;';
            this.maxWidth = 'none !important;';
            this.maxHeight = 'none !important;';
            this.marginBottom = marginBottom + 'px' + ' !important;';
            this.marginRight = marginRight + 'px' + ' !important;';
            this.scaleOrigin = '0 0 !important;';
        }
    },
    DEFAULT_SCALE_VAL:1,
    URL_REGEX:/^http.*\/\/[\w.]*/
}; 