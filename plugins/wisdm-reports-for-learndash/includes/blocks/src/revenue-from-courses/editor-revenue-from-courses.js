/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType, RichText, source  } from '@wordpress/blocks';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmLoader from '../commons/loader/index.js';
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';
import RevenueFromCourses from './index-revenue-from-courses.js';
import ApexCharts from 'apexcharts';
import { createHooks } from '@wordpress/hooks';

let globalHooks = createHooks();

import React, { Component } from "react";
import Chart from "react-apexcharts";
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M8.7,23.7h6.8c1.5,0,2.7-0.5,3.7-1.5c1.1-1.2,1.7-2.9,1.7-4.9c0-3.8-1.8-7.9-4.4-10.2L16.3,7l0.2-0.1
                  C16.8,6.6,17,6.2,17,5.7c0-0.6-0.3-1.1-0.8-1.3l-0.2-0.1L17.4,3C18,2.6,18.2,1.9,18,1.3c-0.2-0.6-0.8-1-1.4-1H7.5
                  c-0.6,0-1.1,0.4-1.5,1C5.8,1.9,6,2.6,6.5,3l1.6,1.3L7.9,4.4C7.4,4.7,7.1,5.2,7.1,5.7c0,0.4,0.2,0.8,0.5,1.1L7.8,7L7.6,7.1
                  C5,9.4,3.2,13.5,3.2,17.4C3.2,20.5,4.9,23.7,8.7,23.7z M15.9,5.7c0,0.3-0.2,0.4-0.4,0.4H15c-0.4-0.1-1.4-0.1-5.8,0l-0.5,0
                  C8.4,6.2,8.2,6,8.2,5.7s0.2-0.4,0.4-0.4h6.8C15.7,5.3,15.9,5.5,15.9,5.7z M7.3,2C7.2,1.9,7.2,1.8,7.2,1.7c0,0,0-0.1,0-0.1l0-0.1
                  c0,0,0.1-0.2,0.3-0.2h9.2c0.1,0,0.2,0,0.2,0.1L17,1.6c0,0,0,0.3-0.2,0.4l0,0l-2.7,2.1H10L7.3,2z M9.4,7.3L9.4,7.3l5.4,0l0,0
                  c2.8,1.8,4.9,6.1,4.9,10.1c0,2.4-1.1,5.3-4.2,5.3H8.7c-3.1,0-4.2-2.8-4.2-5.3C4.4,13.2,6.5,9,9.4,7.3z"/>
                <path d="M18,13.7l-5.8-2.1l-0.2,0l-5.6,2.1c-0.2,0.1-0.3,0.3-0.3,0.4c0,0.1,0.1,0.3,0.3,0.4l0.4,0.1v2c-0.4,0.1-0.6,0.5-0.6,0.9
                  c0,0.5,0.4,1,1,1c0.5,0,1-0.4,1-1c0-0.4-0.2-0.7-0.6-0.9v-1.7l0.9,0.3v2.5c0,0.2,0.1,0.3,0.2,0.4l0,0c1,0.5,2.1,0.7,3.4,0.7
                  c1.3,0,2.5-0.2,3.8-0.7c0.2,0,0.3-0.3,0.3-0.4v-2.5l1.9-0.7c0.1-0.1,0.2-0.2,0.2-0.4C18.3,13.9,18.2,13.8,18,13.7z M16.5,14.1
                  l-4.4,1.5l-4.2-1.5l4.2-1.7L16.5,14.1z M9.2,15.5l2.7,0.9l0.2,0l3.2-1.1v2.1c-2.3,0.8-4.4,0.8-6.2,0V15.5z"/>
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/revenue-from-courses', {

    title: __( 'Revenue From Courses', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Graph of the revenue earned from the courses during the selected timeframe', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-revenue-from-courses',
    icon ,
    attributes: {
        chartContent: {
            type:'html',
            default:'',
        },
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
                  <RevenueFromCourses></RevenueFromCourses>
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
                    <div class="wisdm-learndash-reports-revenue-from-courses front">
                    </div>
                </div>
            );
        },
} );



