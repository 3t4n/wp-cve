/**
 * Block View wrapper
 */
import PostFilterDefault from '../layout-default/layout-default'
import PostFilterOne from '../layout-one/layout-one'
import { Style } from "react-style-tag";

const { escapeHTML } = wp.escapeHtml;
const { Component, Fragment } = wp.element;

export default class Edit extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockID, blockLayout, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleAlign, blockTitleFontFamily, blockTitleFontWeight, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, tabTitleTextAlign, tabTitleFontFamily, tabTitleFontSize, tabTitleFontStyle, tabTitleTextTransform, tabTitleTextDecoration, tabTitleFontColor, tabTitleHoverColor, tabTitlelineHeight, tabTitleFontWeight, titleTextAlign, titleFontFamily, titleFontWeight, titleFontSize, titleFontStyle, titleTextTransform, titleTextDecoration, titleFontColor, titleHoverColor, titlelineHeight, metaTextAlign, metaFontFamily, metaFontWeight, metaFontSize, metaFontStyle, metaTextTransform, metaTextDecoration, metaFontColor, metaHoverColor, metalineHeight, descTextAlign, descFontFamily, descFontWeight, descFontSize, descFontStyle, descTextTransform, descTextDecoration, descFontColor, desclineHeight, buttonTextAlign, buttonFontFamily, buttonFontWeight, buttonFontSize, buttonTextTransform, buttonFontColor, buttonHoverColor, buttonBackgroundColor, buttonBackgroundHoverColor, buttonPaddingTop, buttonPaddingRight, buttonPaddingBottom, buttonPaddingLeft, buttonBorderType, buttonBorderWeight, buttonBorderColor, buttonBorderHoverColor, blockDynamicCss } = this.props.attributes;
        const { setAttributes } = this.props;

        let blockStyle = '';
        blockStyle += '.block-' + blockID + ' .cvmm-block-title{text-align:' + blockTitleAlign + '}'
        if( !typographyOption ) {
            blockStyle += ' .block-' + blockID + ' .cvmm-block-title span{font-family: ' + blockTitleFontFamily + '; font-weight: ' + blockTitleFontWeight + ';font-size: ' + blockTitleFontSize + 'px; font-style: ' + blockTitleFontStyle + '; text-transform: ' + blockTitleTextTransform + '; text-decoration: ' + blockTitleTextDecoration + '; color: ' + blockTitleColor + '; line-height: ' + blockTitleLineHeight + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--two span:before{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' ..cvmm-block-title.layout--two span:after{border-bottom-color: ' + blockTitleBorderColor + ' !important;}'

            blockStyle += ' .block-' + blockID + ' .cvmm-term-titles-wrap{text-align:' + tabTitleTextAlign + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-term-titles-wrap li{text-align:' + tabTitleTextAlign + '; font-family: ' + tabTitleFontFamily + '; font-size: ' + tabTitleFontSize + 'px;  text-transform: ' + tabTitleTextTransform + '; text-decoration: ' + tabTitleTextDecoration + '; color: ' + tabTitleFontColor + '; line-height: ' + tabTitlelineHeight + '; font-weight: ' + tabTitleFontWeight + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-term-titles-wrap li:hover{ color:' + tabTitleHoverColor + ' }'

            blockStyle += ' .block-' + blockID + ' .cvmm-term-titles-wrap li.active{ color:' + tabTitleHoverColor + ' }'

            blockStyle += ' .block-' + blockID + ' .cvmm-term-titles-wrap li:hover{ border-bottom-color:' + tabTitleHoverColor + ' }'

            blockStyle += ' .block-' + blockID + '.cvmm-block-post-filter--layout-default .cvmm-term-titles-wrap li.active{ border-bottom-color:' + tabTitleHoverColor + ' }'

            blockStyle += ' .block-' + blockID + '.cvmm-block-post-filter--layout-default .cvmm-term-titles-wrap li:hover{ border-bottom-color:' + tabTitleHoverColor + ' }'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-title{ font-family: ' + titleFontFamily + '; text-align: ' + titleTextAlign + ';font-weight: ' + titleFontWeight + '; line-height: ' + titlelineHeight + '; font-style:' + tabTitleFontStyle + '; }'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-title a{ font-size: ' + titleFontSize + 'px; font-style: ' + titleFontStyle + ';text-transform: ' + titleTextTransform + ';text-decoration: ' + titleTextDecoration + '; color: ' + titleFontColor + '; }'

            blockStyle +=' .block-' + blockID + ' .cvmm-title-posts-main-wrapper .cvmm-post-block-main-post-wrap .cvmm-post-title a{ font-size: ' + titleFontSize + 4 + 'px }'

            blockStyle +=' .block-' + blockID + ' .cvmm-title-posts-main-wrapper .cvmm-post-block-trailing-post-wrap .cvmm-post-title a{ font-size: ' + titleFontSize + 'px }'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-title a:hover{color: ' + titleHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta{text-align: ' + metaTextAlign + ';font-family: ' + metaFontFamily + ';font-weight: ' + metaFontWeight + ';font-size: ' + metaFontSize + 'px;font-style: ' + metaFontStyle + ';text-transform: ' + metaTextTransform + ';text-decoration: ' + metaTextDecoration + ';color: ' + metaFontColor + ';line-height: ' + metalineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta a{text-align: ' + metaTextAlign + ';font-family: ' + metaFontFamily + ';font-weight: ' + metaFontWeight + ';font-size: ' + metaFontSize + 'px;font-style: ' + metaFontStyle + ';text-transform: ' + metaTextTransform + ';text-decoration: ' + metaTextDecoration + ';color: ' + metaFontColor + ';line-height: ' + metalineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta > span{text-align: ' + metaTextAlign + ';font-family: ' + metaFontFamily + ';font-weight: ' + metaFontWeight + ';font-size: ' + metaFontSize + 'px;font-style: ' + metaFontStyle + ';text-transform: ' + metaTextTransform + ';text-decoration: ' + metaTextDecoration + ';color: ' + metaFontColor + ';line-height: ' + metalineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta a:hover{color: ' + metaHoverColor + '}'

            blockStyle +=' .block-' + blockID + '  .cvmm-post-meta .cvmm-post-meta-item:hover:before{color: ' + metaHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta .cvmm-post-meta-item:hover>a{color: ' + metaHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-content{text-align: ' + descTextAlign + ';font-family: ' + descFontFamily + ';font-weight: ' + descFontWeight + ';font-size: ' + descFontSize + 'px;font-style: ' + descFontStyle + ';text-transform: ' + descTextTransform + ';text-decoration: ' + descTextDecoration + ';color: ' + descFontColor + ';line-height: ' + desclineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-block-main-post-wrap .cvmm-post-content{font-size: ' + 2 + descFontSize + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-post-tiles-slider-post-wrapper .cvmm-post-content{font-size: ' + descFontSize + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-read-more a{font-family: ' + buttonFontFamily + ';font-weight: ' + buttonFontWeight + ';font-size: ' + buttonFontSize + 'px;text-transform: ' + buttonTextTransform + ';color: ' + buttonFontColor + ';background-color: ' + buttonBackgroundColor + ';padding-top: ' + buttonPaddingTop + 'px;padding-right: ' + buttonPaddingRight + 'px;padding-bottom: ' + buttonPaddingBottom + 'px;padding-left: ' + buttonPaddingLeft + 'px;border-style: ' + buttonBorderType + ';border-width: ' + buttonBorderWeight + 'px;border-color: ' + buttonBorderColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-read-more{text-align: ' + buttonTextAlign + '};.block-' + blockID + ' .cvmm-read-more a:hover{color: ' + buttonHoverColor + 'background-color: ' + buttonBackgroundHoverColor + 'border-color: ' + buttonBorderHoverColor + '}'
        } else {
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--default{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--two span{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-title a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta .cvmm-post-meta-item:hover>a{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-title a{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post-meta .cvmm-post-meta-item:hover:before{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-read-more a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-term-titles-wrap li.active{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-term-titles-wrap li:hover{color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-post.cvmm-icon .cvmm-post-thumb::after{background: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-view-more a:hover{color: ' + blockHoverColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-view-more a:hover{border-color: ' + blockHoverColor + '}';

        }
        setAttributes({ blockDynamicCss: blockStyle })
        
        return (
            <Fragment>
                <Style id={ `${blockID}-block-style` } className={ "wpmagazine-modules-lite-editor-block-styles" }>
                  {
                    blockStyle
                  }  
                </Style>
                <div id={ `wpmagazine-modules-lite-post-filter-block-${blockID}` } className={ `wpmagazine-modules-lite-post-filter-block block-${blockID} cvmm-block cvmm-block-post-filter--${blockLayout}` }>
                    {
                        ( () => {
                            switch( blockLayout ) {
                                case 'layout-one' : return <PostFilterOne { ...this.props }/>
                                default: return <PostFilterDefault { ...this.props }/>
                            }
                        }) ()
                    }
                </div>
            </Fragment>
        );
    }
}