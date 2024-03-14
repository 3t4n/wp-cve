/**
 * Ticker preview content.
 */
import TickerDefault from '../layout-default/layout-default';
import TickerOne from '../layout-one/layout-one';
import { Style } from "react-style-tag";

const { Component, Fragment } = wp.element;

export default class Edit extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockID, blockTitle, blockTitleLayout, blockLayout, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleAlign, blockTitleFontFamily, blockTitleFontWeight, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, captionTextAlign, captionFontFamily, captionFontWeight, captionFontSize, captionFontStyle, captionTextTransform, captionTextDecoration, captionBackgroundColor, captionFontColor, captionHoverColor, captionlineHeight, contentTextAlign, contentFontFamily, contentFontWeight, contentFontSize, contentFontStyle, contentTextTransform, contentTextDecoration, contentFontColor, contentHoverColor, contentlineHeight } = this.props.attributes
        const { setAttributes } = this.props;

        let blockStyle = '';
        blockStyle += '.block-' + blockID + ' .cvmm-block-title{text-align:' + blockTitleAlign + '}'
        if( !typographyOption ) {
            blockStyle += ' .block-' + blockID + ' .cvmm-block-title span{font-family: ' + blockTitleFontFamily + '; font-weight: ' + blockTitleFontWeight + ';font-size: ' + blockTitleFontSize + 'px; font-style: ' + blockTitleFontStyle + '; text-transform: ' + blockTitleTextTransform + '; text-decoration: ' + blockTitleTextDecoration + '; color: ' + blockTitleColor + '; line-height: ' + blockTitleLineHeight + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--two span:before{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' ..cvmm-block-title.layout--two span:after{border-bottom-color: ' + blockTitleBorderColor + ' !important;}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-caption{ font-family: ' + captionFontFamily + '; text-align: ' + captionTextAlign + ';font-weight: ' + captionFontWeight + '; line-height: ' + captionlineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-caption{ font-size: ' + captionFontSize + 'px;font-style: ' + captionFontStyle + ';text-transform: ' + captionTextTransform + ';text-decoration: ' + captionTextDecoration + '; color: ' + captionFontColor + '; background: ' + captionBackgroundColor + ';}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-caption:hover{color: ' + captionHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-content .cvmm-ticker-single-title{text-align: ' + contentTextAlign + ';font-family: ' + contentFontFamily + ';font-weight: ' + contentFontWeight + ';font-style: ' + contentFontStyle + ';line-height: ' + contentlineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-single-title{font-size: ' + contentFontSize + 'px;font-style: ' + contentFontStyle + ';text-transform: ' + contentTextTransform + ';color: ' + contentFontColor + ';line-height: ' + contentlineHeight + '}'
            
            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-single-title a{font-size: ' + contentFontSize + 'px;font-style: ' + contentFontStyle + ';text-transform: ' + contentTextTransform + ';text-decoration: ' + contentTextDecoration + ';color: ' + contentFontColor + ';line-height: ' + contentlineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-single-title a:hover{color: ' + contentHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-wrapper .cvmm-ticker-single-title a::before{color: ' + contentFontColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-wrapper .cvmm-ticker-single-title a:hover:before{color: ' + contentHoverColor + '}' 
            
        } else {
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-wrapper .cvmm-ticker-single-title a:before{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-single-title a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-single-title a:hover:before{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-ticker-caption{background: ' + blockPrimaryColor + '}';
        }
        setAttributes({ blockDynamicCss: blockStyle })

        return ( 
            <Fragment>
                <Style id={ `${blockID}-block-style` } className={ "wpmagazine-modules-lite-editor-block-styles" }>
                  {
                    blockStyle
                  }  
                </Style>
                <div id={ `wpmagazine-modules-lite-ticker-block-${blockID}` } className={ `wpmagazine-modules-lite-ticker-block block-${blockID} cvmm-block cvmm-block-ticker--${blockLayout}` } >
                    { blockTitle &&
                        <h2 className={ `cvmm-block-title layout--${blockTitleLayout}` }><span>{ blockTitle }</span></h2>
                    }
                    {
                        ( () => {
                            switch( blockLayout ) {
                                case 'layout-one'   : return <TickerOne { ...this.props } />
                                default: return <TickerDefault { ...this.props } />
                            }
                        }) ()
                    }
                </div>
            </Fragment>
        )
    }
} 