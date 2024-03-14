/**
 * Banner preview content.
 */
import BannerDefault from '../layout-default/layout-default';
import BannerOne from '../layout-one/layout-one';
import { Style } from "react-style-tag";

const { Component, Fragment } = wp.element;

export default class Edit extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockID, blockTitle, blockTitleLayout, blockLayout, blockTitleAlign, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleFontFamily, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, titleTextAlign, titleFontFamily, titleFontSize, titleFontStyle, titleTextTransform, titleTextDecoration, titleFontColor, titleHoverColor, titlelineHeight, descTextAlign, descFontFamily, descFontSize, descFontStyle, descTextTransform, descTextDecoration, descFontColor, desclineHeight, button1TextAlign, button1FontFamily, button1FontSize, button1TextTransform, button1FontColor, button1BackgroundColor, button1PaddingTop, button1PaddingRight, button1PaddingBottom, button1PaddingLeft, button1BorderType, button1BorderWeight, button1BorderColor, button2TextAlign, button2FontFamily, button2FontSize, button2TextTransform, button2FontColor, button2BackgroundColor, button2PaddingTop, button2PaddingRight, button2PaddingBottom, button2PaddingLeft, button2BorderType, button2BorderWeight, blockTitleFontWeight, titleFontWeight, descFontWeight, button1FontWeight, button2FontWeight, button1HoverColor, button2HoverColor, button1BackgroundHoverColor, button2BackgroundHoverColor, button1BorderHoverColor, button2BorderHoverColor, button2BorderColor, blockDynamicCss } = this.props.attributes
        const { setAttributes } = this.props;
        let blockStyle = '';
        blockStyle += '.block-' + blockID + ' .cvmm-block-title{text-align:' + blockTitleAlign + '}'
        if( !typographyOption ) {
            blockStyle += ' .block-' + blockID + ' .cvmm-block-title span{font-family: ' + blockTitleFontFamily + '; font-weight: ' + blockTitleFontWeight + ';font-size: ' + blockTitleFontSize + 'px; font-style: ' + blockTitleFontStyle + '; text-transform: ' + blockTitleTextTransform + '; text-decoration: ' + blockTitleTextDecoration + '; color: ' + blockTitleColor + '; line-height: ' + blockTitleLineHeight + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--two span:before{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' ..cvmm-block-title.layout--two span:after{border-bottom-color: ' + blockTitleBorderColor + ' !important;}'

            blockStyle +=' .block-' + blockID + ' .cvmm-banner-title{ text-align: ' + titleTextAlign + '; font-family: ' + titleFontFamily + ';font-weight: ' + titleFontWeight + '; line-height: ' + titlelineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-banner-title a{font-size: ' + titleFontSize + 'px;font-style: ' + titleFontStyle + ';text-transform: ' + titleTextTransform + ';text-decoration: ' + titleTextDecoration + '; color: ' + titleFontColor + '; }'

            blockStyle +=' .block-' + blockID + ' .cvmm-banner-title a:hover{color: ' + titleHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-banner-desc{text-align: ' + descTextAlign + ';font-family: ' + descFontFamily + ';font-weight: ' + descFontWeight + ';font-size: ' + descFontSize + 'px;font-style: ' + descFontStyle + ';text-transform: ' + descTextTransform + ';text-decoration: ' + descTextDecoration + ';color: ' + descFontColor + ';line-height: ' + desclineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .banner-button-wrap .cvmm-banner-button-one{font-family: ' + button1FontFamily + ';font-weight: ' + button1FontWeight + ';font-size: ' + button1FontSize + 'px;text-transform: ' + button1TextTransform + ';color: ' + button1FontColor + '; text-align: ' + button1TextAlign + '; background-color: ' + button1BackgroundColor + ';padding-top: ' + button1PaddingTop + 'px;padding-right: ' + button1PaddingRight + 'px;padding-bottom: ' + button1PaddingBottom + 'px;padding-left: ' + button1PaddingLeft + 'px;border-style: ' + button1BorderType + ';border-width: ' + button1BorderWeight + 'px;border-color: ' + button1BorderColor + '}'

            blockStyle +=' .block-' + blockID + ' .banner-button-wrap .cvmm-banner-button-two{font-family: ' + button2FontFamily + ';font-weight: ' + button2FontWeight + ';font-size: ' + button2FontSize + 'px;text-transform: ' + button2TextTransform + ';color: ' + button2FontColor + '; text-align: ' + button2TextAlign + '; background-color: ' + button2BackgroundColor + ';padding-top: ' + button2PaddingTop + 'px;padding-right: ' + button2PaddingRight + 'px;padding-bottom: ' + button2PaddingBottom + 'px;padding-left: ' + button2PaddingLeft + 'px;border-style: ' + button2BorderType + ';border-width: ' + button2BorderWeight + 'px;border-color: ' + button2BorderColor + '}'
            
            blockStyle +=' .block-' + blockID + ' .banner-button-wrap .cvmm-banner-button-one:hover{color: ' + button1HoverColor + ';background-color: ' + button1BackgroundHoverColor + ';border-color: ' + button1BorderHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .banner-button-wrap .cvmm-banner-button-two:hover{color: ' + button2HoverColor + ';background-color: ' + button2BackgroundHoverColor + ';border-color: ' + button2BorderHoverColor + '}'
        } else {
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-banner-title a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-title a{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--two span{color: ' + blockPrimaryColor + '}';
        }
        setAttributes({ blockDynamicCss: blockStyle })

        return ( 
            <Fragment>
                <Style id={ `${blockID}-block-style` } className={ "wpmagazine-modules-lite-editor-block-styles" }>
                  {
                    blockStyle
                  }  
                </Style>
                <div id={ `wpmagazine-modules-lite-banner-block-${blockID}` } className={ `wpmagazine-modules-lite-banner-block block-${blockID} cvmm-block cvmm-block-banner--${blockLayout}` } >
                    { blockTitle &&
                        <h2 className={ `cvmm-block-title layout--${blockTitleLayout}` }><span>{ blockTitle }</span></h2>
                    }
                    {
                        ( () => {
                            switch( blockLayout ) {
                                case 'layout-one'   : return <BannerOne { ...this.props } />
                                default: return <BannerDefault { ...this.props } />
                            }
                        }) ()
                    }
                </div>
            </Fragment>
        )
    }
}