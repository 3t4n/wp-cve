import './assets/scss/editor.scss';
import metadata from './block.json';

import { useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { registerBlockType } from "@wordpress/blocks";
import { PanelBody } from '@wordpress/components';
import {
    useBlockProps,
    RichText,
    AlignmentToolbar,
    BlockAlignmentToolbar,
    BlockControls,
    InspectorControls,
} from '@wordpress/block-editor';

import fetchWeatherData from './components/fetchWeatherData';
import weatherStatusCode from './components/weatherStatusCode';

import InputWeatherLocation from './components/settings/data/InputWeatherLocation';
import InputRadioTempUnits from './components/settings/data/InputRadioTempUnits';
import InputSelectStyle from './components/settings/data/InputSelectStyle';
import InputCheckWeatherData from './components/settings/data/InputCheckWeatherData';

import InputColorText from './components/settings/customize/InputColorText';
import InputColorBg from './components/settings/customize/InputColorBg';
import InputRangeRoundness from './components/settings/customize/InputRangeRoundness';
import InputBoxControlPadding from './components/settings/customize/InputBoxControlPadding';
import InputShadowControlDropShadow from './components/settings/customize/InputShadowControlDropShadow';

import CurrentDate from './components/CurrentDate';
import FooterGroup from './components/FooterGroup';


registerBlockType( metadata.name, {
    attributes: {
        location:       { type: 'string', default: 'Santorini' },
        title:          { type: 'string', default: 'Santorini, GR' },
        description:    { type: 'string' },
        tempUnits:      { type: 'string', default: 'C' },
        temperature:    { type: 'string' },
        wind:           { type: 'string' },
        tempMin:        { type: 'string' },
        tempMax:        { type: 'string' },
        style:          { type: 'string', default: 'style-expanded' },
        iconCode:       { type: 'number' },
        iconClass:      { type: 'string', default: 'weather-i-sea-and-sun' },
        bgImgClass:     { type: 'string', default: ' bg-img-broken-clouds' },

        dataIcon:       { type: 'boolean', default: true },
        dataBgImg:      { type: 'boolean', default: true },
        dataDesc:       { type: 'boolean', default: true },
        dataDate:       { type: 'boolean', default: true },
        dataWind:       { type: 'boolean', default: true },
        dataMin:        { type: 'boolean', default: true },
        dataMax:        { type: 'boolean', default: true },

        blockAlignment: { type: 'string' },
        alignment:      { type: 'string' },
        textColor:      { type: 'string', default: '#fff' },
        bgColor:        { type: 'string', default: 'linear-gradient(74.1deg, #537895 11.78%, #A3BECD 90.32%)' },
        roundness:      { type: 'number', default: 18 },
        paddingMin:     { type: 'array', default: { top: '30px', left: '30px', right: '30px', bottom: '30px' } },
        paddingExp:     { type: 'array', default: { top: '115px', left: '40px', right: '40px', bottom: '40px' } },
        shadowSize:     { type: 'number', default: 30 },
        shadowColor:    { type: 'string', default: 'rgba(51, 51, 51, 0.35)' },
    },
    edit: ( { attributes, setAttributes } ) => {

        useEffect(() => {
            fetchWeatherData({ attributes, setAttributes })
        }, []);

        useEffect(() => {
            weatherStatusCode({ attributes, setAttributes })
        }, [ attributes.iconClass, attributes.bgImgClass ]);

        const onTitleChange = newTitle => setAttributes({ title: newTitle });
        const onAlignmentChange = newAlignment => setAttributes({ alignment: newAlignment === undefined ? 'none' : newAlignment });
        const onBlockAlignmentChange = newBlockAlignment => setAttributes({ blockAlignment: newBlockAlignment === undefined ? 'left' : newBlockAlignment });

        const cssStyle = {
            "--weather-widget-wp-text-align": attributes.alignment,
            "--weather-widget-wp-text-color": attributes.textColor,
            "--weather-widget-wp-bg": attributes.bgColor,
            "--weather-widget-wp-roundness": attributes.roundness + 'px',
            "--weather-widget-wp-padding-min": `${attributes.paddingMin ? `${attributes.paddingMin.top} ${attributes.paddingMin.right} ${attributes.paddingMin.bottom} ${attributes.paddingMin.left}` : '' }`,
            "--weather-widget-wp-padding-exp": `${attributes.paddingExp ? `${attributes.paddingExp.top} ${attributes.paddingExp.right} ${attributes.paddingExp.bottom} ${attributes.paddingExp.left}` : '' }`,
            "--weather-widget-wp-shadow-size": `0 ${attributes.shadowSize}px ${attributes.shadowSize * 2}px ${attributes.shadowSize * 0.16}px`,
            "--weather-widget-wp-shadow-color": attributes.shadowColor,
        }

        const hasStyleExpanded = () => {
            if ( attributes.style === 'style-expanded' ) return true;
        }

        const widgetClassName = 'weather-widget-wp ' + attributes.style + ( attributes.dataBgImg ? attributes.bgImgClass : '' ) + ( attributes.dataIcon ? '' : ' no-icon' ) + ( attributes.className ? ` ${attributes.className}` : '' );

        const blockAlignmentClass = attributes.blockAlignment ? ` block-align-${attributes.blockAlignment}` : '';

        return (
            <div { ...useBlockProps() }>
                {
                    <BlockControls>
                        <AlignmentToolbar
                            value={ attributes.alignment }
                            onChange={ onAlignmentChange }
                        />
                        <BlockAlignmentToolbar
                            value={ attributes.blockAlignment }
                            onChange={ onBlockAlignmentChange }
                        />
                    </BlockControls>
                }

                <InspectorControls key='settings'>
                    <div id="weather-widget-wp-settings">
                        <PanelBody title={  __( 'Settings', 'weather-widget-wp' ) } initialOpen={ true }>
                            <InputWeatherLocation attributes={ attributes } setAttributes={ setAttributes } />
                            <InputRadioTempUnits attributes={ attributes } setAttributes={ setAttributes } />
                            <InputSelectStyle attributes={ attributes } setAttributes={ setAttributes } />
                            <InputCheckWeatherData attributes={ attributes } setAttributes={ setAttributes } />
                        </PanelBody>
                        <PanelBody title={  __( 'Customize Style', 'weather-widget-wp' ) } initialOpen={ false }>
                            <InputColorText attributes={ attributes } setAttributes={ setAttributes } />
                            <InputColorBg attributes={ attributes } setAttributes={ setAttributes } />
                            <InputRangeRoundness attributes={ attributes } setAttributes={ setAttributes } />
                            <InputBoxControlPadding attributes={ attributes } setAttributes={ setAttributes } />
                            <InputShadowControlDropShadow attributes={ attributes } setAttributes={ setAttributes } />
                        </PanelBody>
                    </div>
                </InspectorControls>

                <div className={`wrapper-weather-widget-wp${blockAlignmentClass}`}>
                    <div className={ widgetClassName } style={ cssStyle }>
                        <div className="temp-group">

                            { attributes.dataIcon ? <i className={ attributes.iconClass }></i> : null }

                            { ( attributes.dataDate && hasStyleExpanded() ) ? <CurrentDate /> : null }

                            <span className="weather-temp">{ attributes.temperature }<span className="temp-units">°{ attributes.tempUnits }</span></span>
                        </div>

                        <div className="info-group">
                            <RichText
                                tagName='span'
                                className='weather-title'
                                value={ attributes.title }
                                onChange={ onTitleChange }
                                placeholder={ __( 'Enter title here, e.g. "Santorini, GR"', 'weather-widget-wp' ) }
                            />

                            { attributes.dataDesc ? <small className="weather-description">{ attributes.description }</small> : null }
                        </div>

                        { hasStyleExpanded() && ( attributes.dataWind || attributes.dataMax || attributes.dataMin ) ? <FooterGroup attributes={ attributes } /> : null }
                    </div>
                </div>
            </div>
        )
    },
    save: ({ attributes }) => {

        /**
         * Custom block front-end output:
         *
         * just building the shortcode that is created with PHP.
         * This allows us to have both classic shortcode & custom block with the same code.
         */

        const blockAlign = attributes.blockAlignment ? `block_align="${attributes.blockAlignment}"` : '';

        const additionalClassNames = attributes.className ? `css_class="${attributes.className}"` : '';

        const hasStyleExpanded = () => {
            if ( attributes.style === 'style-expanded' ) return true;
        }

        const showIcon = () => {
            return attributes.dataIcon ? 'icon=1' : 'icon=0';
        }

        const showBgImg = () => {
            return attributes.dataBgImg ? 'bg_img=1' : 'bg_img=0';
        }

        const showDescription = () => {
            return attributes.dataDesc ? 'desc=1' : 'desc=0';
        }

        const showDate = () => {
            if ( hasStyleExpanded() && attributes.dataDate ) {
                return 'date=1';
            }
            return 'date=0';
        }

        const showWind = () => {
            if ( hasStyleExpanded() && attributes.dataWind ) {
                return 'wind=1';
            }
            return 'wind=0';
        }

        const showTempMin = () => {
            if ( hasStyleExpanded() && attributes.dataMin ) {
                return 'min=1';
            }
            return 'min=0';
        }

        const showTempMax = () => {
            if ( hasStyleExpanded() && attributes.dataMax ) {
                return 'max=1';
            }
            return 'max=0';
        }

        const customCSS = () => {

            // if the attr prop is not undefined and is not its default, display the css var customization.
            const textAlignment  = attributes.alignment && attributes.alignment !== 'left' ? `--weather-widget-wp-text-align:${attributes.alignment};` : '';
            const bgColor        = attributes.bgColor && attributes.bgColor !== 'linear-gradient(74.1deg, #537895 11.78%, #A3BECD 90.32%)' ? `--weather-widget-wp-bg:${attributes.bgColor};` : '';
            const textColor      = attributes.textColor && attributes.textColor !== '#fff' ? `--weather-widget-wp-text-color:${attributes.textColor};` : '';
            const roundness      = attributes.roundness !== 18 ? `--weather-widget-wp-roundness:${attributes.roundness + 'px'};` : '';
            const paddingMin     = attributes.paddingMin && JSON.stringify(attributes.paddingMin) !== JSON.stringify({top: '30px', left: '30px', right: '30px', bottom: '30px'}) ? `--weather-widget-wp-padding-min:${attributes.paddingMin.top} ${attributes.paddingMin.right} ${attributes.paddingMin.bottom} ${attributes.paddingMin.left};` : '';
            const paddingExp     = attributes.paddingExp && JSON.stringify(attributes.paddingExp) !== JSON.stringify({ top: '115px', left: '40px', right: '40px', bottom: '40px' }) ? `--weather-widget-wp-padding-exp:${attributes.paddingExp.top} ${attributes.paddingExp.right} ${attributes.paddingExp.bottom} ${attributes.paddingExp.left};` : '';
            const shadowSize     = attributes.shadowSize && attributes.shadowSize !== 30 ? `--weather-widget-wp-shadow-size:0 ${attributes.shadowSize}px ${attributes.shadowSize * 2}px ${attributes.shadowSize * 0.16}px;` : '';
            const shadowColor    = attributes.shadowColor && attributes.shadowColor !== 'rgba(51, 51, 51, 0.35)' ? `--weather-widget-wp-shadow-color:${attributes.shadowColor};` : '';

            if ( textAlignment || bgColor || textColor || roundness || paddingMin || paddingExp || shadowSize || shadowColor ) {
                return `css_style="${textAlignment + bgColor + textColor + roundness + paddingMin + paddingExp + shadowSize + shadowColor}"`;
            }
            return '';
        }

        const shortcode = `[weather_widget_wp_location city="${ attributes.location }" title="${ attributes.title }" units="${ attributes.tempUnits }" style="${attributes.style}" ${showIcon()} ${showBgImg()} ${showDate()} ${showDescription()} ${showWind()} ${showTempMin()} ${showTempMax()} ${blockAlign} ${additionalClassNames} ${customCSS()}]`;
        // console.log(shortcode);

        return shortcode;
    }
});
