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

import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';
import TimeSpentOnACourseDonutChart from './index-time-spent-on-a-course.js';
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
                <g>
                  <path d="M23.1,15.7L23.1,15.7c-0.2,0.1-0.4,0-0.4-0.2L22.4,15c-0.1-0.2,0-0.4,0.2-0.4v0c0.2-0.1,0.4,0,0.4,0.2l0.2,0.4
                    C23.3,15.4,23.2,15.6,23.1,15.7z"/>
                  <path d="M23.3,17.3L23.3,17.3c-0.2,0-0.3-0.1-0.4-0.3l0-0.5c0-0.2,0.1-0.3,0.3-0.4l0,0c0.2,0,0.3,0.1,0.4,0.3l0,0.5
                    C23.6,17.1,23.5,17.3,23.3,17.3z"/>
                  <path d="M22.7,18.9L22.7,18.9c-0.2-0.1-0.3-0.3-0.2-0.4l0.2-0.4c0.1-0.2,0.3-0.3,0.4-0.2l0,0c0.2,0.1,0.3,0.3,0.2,0.4l-0.2,0.4
                    C23.1,18.8,22.9,18.9,22.7,18.9z"/>
                  <path d="M21.8,20.4L21.8,20.4c-0.1-0.1-0.2-0.3-0.1-0.5l0.3-0.4c0.1-0.1,0.3-0.2,0.5-0.1h0c0.1,0.1,0.2,0.3,0.1,0.5l-0.3,0.4
                    C22.1,20.4,21.9,20.5,21.8,20.4z"/>
                  <path d="M20.5,21.4L20.5,21.4c-0.1-0.1-0.1-0.4,0.1-0.5l0.4-0.3c0.1-0.1,0.4-0.1,0.5,0.1h0c0.1,0.1,0.1,0.4-0.1,0.5L21,21.5
                    C20.8,21.6,20.6,21.5,20.5,21.4z"/>
                  <path d="M18.9,21.9L18.9,21.9c0-0.2,0.1-0.4,0.2-0.4l0.5-0.1c0.2,0,0.4,0.1,0.4,0.2l0,0c0,0.2-0.1,0.4-0.2,0.4l-0.5,0.1
                    C19.1,22.2,18.9,22,18.9,21.9z"/>
                </g>
                <path class="st0" d="M13.6,11.8c0-0.1-0.1-0.3-0.2-0.3L9.3,8.8C9.1,8.7,9,8.7,8.8,8.8C8.7,8.9,8.6,9,8.6,9.1v5.3
                  c0,0.2,0.1,0.3,0.2,0.4c0.1,0.1,0.3,0.1,0.4,0l4.1-2.7C13.5,12.1,13.6,11.9,13.6,11.8z M9.4,13.7V9.9l3,1.9L9.4,13.7z"/>
                <path d="M18.6,2.3L18.6,2.3h-15c-1.6,0.1-3,1.5-3,3.1v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5c0.4,0,0.8-0.3,0.8-0.8S12.8,20,12.4,20H3.9
                  c-1,0-1.8-0.8-1.8-1.8V5.4c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8s0.8-0.3,0.8-0.8V5.4
                  C21.5,3.8,20.2,2.4,18.6,2.3z"/>
                <path class="st1" d="M17.5,21.4c-0.3-0.1-0.8-0.2-1.1-0.3l0,0c-0.1,0-0.1-0.1-0.2-0.1l0,0c-0.1-0.1-0.3-0.1-0.5-0.2
                  c-2.1-1.5-2.5-4.2-1.1-6.3c0.3-0.5,0.7-0.8,1.1-1.1l0,0c1.5-1,3.5-1.1,5.1,0l-0.3,0.3c-0.1,0.1,0,0.2,0.1,0.2l1.5-0.1
                  c0.1,0,0.2-0.1,0.2-0.3L21.9,12c0-0.1-0.1-0.2-0.2,0l-0.3,0.3c-1.1-0.8-2.5-1-3.9-0.8c-0.1,0-0.2,0-0.5,0.1l0,0l0,0
                  c-1.1,0.3-2.2,1-3,1.9l0,0c0,0,0,0.1-0.1,0.1l-0.1,0.1l0,0c-0.6,0.9-0.9,2.1-0.8,3.2l0,0c0,0.1,0,0.2,0,0.3l0,0c0,0.1,0,0.2,0,0.3
                  c0.2,1.1,0.7,2.2,1.5,2.9l0,0l0,0c0.2,0.2,0.5,0.5,0.7,0.6c0.7,0.5,1.4,0.8,2.2,0.9c0.2,0,0.5-0.1,0.5-0.3
                  C17.9,21.6,17.7,21.4,17.5,21.4z"/>
                <path d="M20.5,18l-2.1-1.1v-2.2c0-0.2-0.2-0.4-0.5-0.4s-0.5,0.2-0.5,0.5v2.8l2.5,1.3h0.2c0.2,0,0.3-0.1,0.4-0.2v-0.1
                  C20.7,18.3,20.5,18.1,20.5,18z"/>
                </svg>
);

registerBlockType( 'wisdm-learndash-reports/time-spent-on-a-course', {

    title: __( 'Time Spent on a', 'learndash-reports-by-wisdmlabs' ) + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Course'),
    description: __( 'Graph of the time spent on the courses', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-time-spent-on-a-course',
    icon ,
    attributes: {
        categories: {
            type:'object'
        },
        selectedCategory: {
            type:'string'
        }, 
        chartContent: {
            type:'html',
            default:''
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
                  <TimeSpentOnACourseDonutChart></TimeSpentOnACourseDonutChart>
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
                    <div class="wisdm-learndash-reports-time-spent-on-a-course">
                    </div>
                </div>
            );
        },
} );



