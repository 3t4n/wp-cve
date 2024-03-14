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
import { createElement } from '@wordpress/element'
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmLoader from '../commons/loader/index.js';
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';
import DailyEnrollments from './index-daily-enrollments.js';
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
                  <path d="M18,22.2c-3.3,0-6-2.7-6-6c0-3.3,2.7-6,6-6s6,2.7,6,6C24,19.5,21.3,22.2,18,22.2z M18,11.1c-2.9,0-5.2,2.3-5.2,5.2
                    s2.3,5.2,5.2,5.2s5.2-2.3,5.2-5.2S20.9,11.1,18,11.1z"/>
                  <path d="M12,19.3H3.5c-1,0-1.8-0.8-1.8-1.8V4.8c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8
                    s0.8-0.3,0.8-0.8V4.8c-0.1-1.7-1.4-3-2.9-3.1l0,0h-15C1.6,1.8,0.2,3.2,0.2,4.8v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5
                    c0.4,0,0.8-0.3,0.8-0.8C12.7,19.7,12.4,19.3,12,19.3z"/>
                  <path d="M9.1,8C9,8,8.8,7.9,8.7,7.9l0,0C8.3,7.9,8,8.3,8,8.5v5.3c0,0.3,0.2,0.5,0.3,0.6s0.3,0.1,0.4,0.1c0.1,0,0.2,0,0.3-0.1l4-2.7
                    c0.3-0.2,0.5-0.3,0.5-0.5s-0.1-0.3-0.4-0.5L9.1,8z M11.6,11.2l-2.3,1.4V9.7L11.6,11.2z"/>
                  <path d="M20.4,15.6h-1.6V14c0-0.5-0.3-0.8-0.8-0.8s-0.8,0.3-0.8,0.8v1.6h-1.6c-0.5,0-0.8,0.3-0.8,0.8c0,0.5,0.3,0.8,0.8,0.8h1.6v1.6
                    c0,0.5,0.3,0.8,0.8,0.8s0.8-0.3,0.8-0.8v-1.6h1.6c0.5,0,0.8-0.3,0.8-0.8S20.9,15.6,20.4,15.6z"/>
              </svg>
);

registerBlockType( 'wisdm-learndash-reports/daily-enrollments', {
    title: __( 'Daily Enrollments', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Graph of the daily enrollments during the selected timeframe', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-daily-enrollments',
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
                  <DailyEnrollments></DailyEnrollments>
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
                    <div class="wisdm-learndash-reports-daily-enrollments front">
                        
                    </div>
                </div>
            );
        },
} );



