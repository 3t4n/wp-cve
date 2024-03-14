/**
 * Timeline preview content.
 */
import TimelineDefault from '../layout-default/layout-default';
import TimelineOne from '../layout-one/layout-one';
import { Style } from "react-style-tag";

const { Component, Fragment } = wp.element;

export default class Edit extends Component {
    constructor( props ) {
        super( ...arguments )
    }

    render() {
        const { blockID, blockTitle, blockTitleLayout, blockLayout, blockPrimaryColor, blockHoverColor, typographyOption, blockTitleAlign, blockTitleFontFamily, blockTitleFontWeight, blockTitleFontSize, blockTitleFontStyle, blockTitleTextTransform, blockTitleTextDecoration, blockTitleColor, blockTitleLineHeight, blockTitleBorderColor, titleTextAlign, titleFontFamily, titleFontWeight, titleFontSize, titleFontStyle, titleTextTransform, titleTextDecoration, titleFontColor, titleHoverColor, titlelineHeight, dateTextAlign, dateFontFamily, dateFontWeight, dateFontSize, dateFontStyle, dateTextTransform, dateTextDecoration, dateFontColor, dateHoverColor, datelineHeight, descTextAlign, descFontFamily, descFontWeight, descFontSize, descFontStyle, descTextTransform, descTextDecoration, descFontColor, desclineHeight } = this.props.attributes
        const { setAttributes } = this.props;
        let blockStyle = '';
        blockStyle += '.block-' + blockID + ' .cvmm-block-title{text-align:' + blockTitleAlign + '}'
        if( !typographyOption ) {
            blockStyle += ' .block-' + blockID + ' .cvmm-block-title span{font-family: ' + blockTitleFontFamily + '; font-weight: ' + blockTitleFontWeight + ';font-size: ' + blockTitleFontSize + 'px; font-style: ' + blockTitleFontStyle + '; text-transform: ' + blockTitleTextTransform + '; text-decoration: ' + blockTitleTextDecoration + '; color: ' + blockTitleColor + '; line-height: ' + blockTitleLineHeight + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--two span:before{border-bottom-color: ' + blockTitleBorderColor + '}'

            blockStyle += ' .block-' + blockID + ' .cvmm-block-title.layout--two span:after{border-bottom-color: ' + blockTitleBorderColor + ' !important;}'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-title{ font-family: ' + titleFontFamily + '; text-align: ' + titleTextAlign + ';font-weight: ' + titleFontWeight + '; line-height: ' + titlelineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-title a{ font-size: ' + titleFontSize + 'px;font-style: ' + titleFontStyle + ';text-transform: ' + titleTextTransform + ';text-decoration: ' + titleTextDecoration + '; color: ' + titleFontColor + '; }'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-title a:hover{color: ' + titleHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-date{text-align: ' + dateTextAlign + ';font-family: ' + dateFontFamily + ';font-weight: ' + dateFontWeight + '; font-size: ' + dateFontSize + 'px;font-style: ' + dateFontStyle + ';text-transform: ' + dateTextTransform + ';text-decoration: ' + dateTextDecoration + ';color: ' + dateFontColor + ';line-height: ' + datelineHeight + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-date:hover{color: ' + dateHoverColor + '}'

            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-desc p{text-align: ' + descTextAlign + ';font-family: ' + descFontFamily + ';font-weight: ' + descFontWeight + ';font-size: ' + descFontSize + 'px;font-style: ' + descFontStyle + ';text-transform: ' + descTextTransform + ';text-decoration: ' + descTextDecoration + ';color: ' + descFontColor + ';line-height: ' + desclineHeight + '}'
        } else {
            blockStyle +=' .block-' + blockID + ' .cvmm-block-title.layout--one span{border-bottom-color: ' + blockPrimaryColor + '}';
            blockStyle +=' .block-' + blockID + ' .cvmm-timeline-title a:hover{color: ' + blockHoverColor + '}';
        }
        setAttributes({ blockDynamicCss: blockStyle })
        return ( 
            <Fragment>
                <Style id={ `${blockID}-block-style` } className={ "wpmagazine-modules-lite-editor-block-styles" }>
                  {
                    blockStyle
                  }  
                </Style>
                <div id={ `wpmagazine-modules-lite-timeline-block-${blockID}` } className={ `wpmagazine-modules-lite-timeline-block block-${blockID} cvmm-block cvmm-block-timeline--${blockLayout}` } >
                    { blockTitle &&
                        <h2 className={ `cvmm-block-title layout--${blockTitleLayout}` }><span>{ blockTitle }</span></h2>
                    }
                    {
                        ( () => {
                            switch( blockLayout ) {
                                case 'layout-one'   : return <TimelineOne { ...this.props } />
                                default: return <TimelineDefault { ...this.props } />
                            }
                        }) ()
                    }
                </div>
            </Fragment>
        )
    }
}