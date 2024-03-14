/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType, RichText, source  } from '@wordpress/blocks';
var moment = require('moment');
/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'

import { useBlockProps } from '@wordpress/block-editor';
import { createHooks } from '@wordpress/hooks';
var daterangepicker = require("daterangepicker");
import "daterangepicker/daterangepicker.css";

import './editor.scss';
import Datepickers from './index-date-filters.js';

let globalHooks = createHooks();

import React, { Component } from "react";

const icon = (
            <svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path class="st0" d="M17.4,12.8c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S18,12.8,17.4,12.8z M17.4,14.8
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9S17.8,14.8,17.4,14.8z"/>
                <path class="st0" d="M12.1,12.8c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S12.7,12.8,12.1,12.8z M12.1,14.8
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9S12.6,14.8,12.1,14.8z"/>
                <path class="st0" d="M6.5,17.3c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S7.1,17.3,6.5,17.3z M6.5,19.3
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9S6.9,19.3,6.5,19.3z"/>
                <path class="st0" d="M6.5,12.8c-0.6,0-1.1,0.5-1.1,1.1S5.9,15,6.5,15s1.1-0.5,1.1-1.1S7.1,12.8,6.5,12.8z M6.5,14.8
                    c-0.5,0-0.9-0.4-0.9-0.9S6,13,6.5,13s0.9,0.4,0.9,0.9S6.9,14.8,6.5,14.8z"/>
                <path class="st0" d="M17.4,8.2c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S18,8.2,17.4,8.2z M17.4,10.2
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9S17.8,10.2,17.4,10.2z"/>
                <path class="st0" d="M12.1,8.2c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S12.7,8.2,12.1,8.2z M12.1,10.2
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9S13,8.8,13,9.3S12.5,10.2,12.1,10.2z"/>
                <path class="st0" d="M12.1,17.3c-0.6,0-1.1,0.5-1.1,1.1s0.5,1.1,1.1,1.1s1.1-0.5,1.1-1.1S12.7,17.3,12.1,17.3z M12.1,19.3
                    c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9S12.5,19.3,12.1,19.3z"/>
                <circle class="st0" cx="6.5" cy="9.3" r="1.7"/>
                <circle class="st0" cx="17.4" cy="18.4" r="1.7"/>
                <path class="st0" d="M19.6,2.7h-1V2.2c0-0.6-0.5-1-1-1c-0.6,0-1,0.5-1,1v0.5h-3.6V2.2c0-0.6-0.5-1-1-1c-0.6,0-1,0.5-1,1v0.5H7.1V2.2
                    c0-0.6-0.5-1-1-1c-0.6,0-1,0.5-1,1v0.5H4.2C2.3,2.8,0.8,4.3,0.8,6.2v13.7c0,1.9,1.5,3.3,3.4,3.3h15.3c1.9,0,3.4-1.5,3.4-3.4V6.2
                    C23,4.3,21.5,2.7,19.6,2.7z M21.7,10.7v0.2v8.8c0,1-0.8,1.8-1.8,1.8H4.4c-0.5,0-0.9-0.1-1.3-0.5c-0.4-0.3-0.6-0.8-0.6-1.4V6.2
                    c0-1,0.8-1.8,1.8-1.8H5v0.8c0,0.6,0.5,1,1,1c0.6,0,1-0.5,1-1V4.3h3.7v0.9c0,0.6,0.5,1,1,1c0.3,0,0.6-0.1,0.8-0.3
                    c0.2-0.2,0.3-0.4,0.3-0.7c0,0,0,0,0,0v0c0,0,0,0,0,0V4.3h3.6v0.9c0,0.6,0.5,1,1,1c0.3,0,0.6-0.1,0.7-0.3c0.2-0.2,0.3-0.4,0.3-0.7
                    V4.3h0.9c1.3,0,2.2,0.8,2.2,1.9V10.7z"/>
            </svg>
);



registerBlockType( 'wisdm-learndash-reports/date-filters', {

    title: __( 'Duration Selectors', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'A block with duration selectors for the LearnDash reports', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-date-filters',
    icon ,
    attributes: {
        blockContent: {
            type:'html',
            default:<p></p>,
        }
    },
  
    /**
         * edit function
         * 
         * Makes the markup for the editor interface.
         * 
         * @param {object} ObjectArgs {
         *      className - Automatic CSS class. Based on the block name: gutenberg-block-samples-block-simple
         * }
         * 
         * @return {JSX object} ECMAScript JSX Markup for the editor 
         */
        edit ( props ) { 
            return (
                <div { ...useBlockProps() }>
                    <Datepickers></Datepickers>
                </div>
            )
        },
 
        /**
         * save function
         * 
         * Makes the markup that will be rendered on the site page
         * 
         * @return {JSX object} ECMAScript JSX Markup for the site
         */
        save ( ) {
            return (
                <div { ...useBlockProps.save() }>
                    <div class="wisdm-learndash-reports-chart-block">
                      <div class="wisdm-learndash-reports-date-filters front">
                           
                        </div>
                    </div>
                </div>
            );
        },
} );



