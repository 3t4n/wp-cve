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
import QuizCompletionTimePerCourse from './index-quiz-completion-time-per-course.js';
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
                <path class="st0" d="M17.8,16.5l-1.6,1.6l0.9,0.9l1.7-1.7C19,17.1,19,17,19,16.8V13h-1.2C17.8,13,17.8,16.5,17.8,16.5z"/>
                <path d="M12,19.1H3.5c-1,0-1.8-0.8-1.8-1.8V4.6c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8
                  s0.8-0.3,0.8-0.8V4.6c-0.1-1.7-1.4-3-2.9-3.1l0,0h-15C1.6,1.6,0.2,3,0.2,4.6v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5c0.4,0,0.8-0.3,0.8-0.8
                  C12.7,19.5,12.4,19.1,12,19.1z"/>
                <path d="M9.1,7.9C9,7.8,8.8,7.8,8.7,7.8l0,0C8.3,7.7,8,8.2,8,8.4v5.3c0,0.3,0.2,0.5,0.3,0.6s0.3,0.1,0.4,0.1c0.1,0,0.2,0,0.3-0.1
                  l4-2.7c0.3-0.2,0.5-0.3,0.5-0.5s-0.1-0.3-0.4-0.5L9.1,7.9z M11.6,11.1l-2.3,1.4V9.6L11.6,11.1z"/>
                <path d="M17.8,22c-3.3,0-6-2.7-6-6c0-3.3,2.7-6,6-6s6,2.7,6,6C23.8,19.3,21.1,22,17.8,22z M17.8,10.8c-2.9,0-5.2,2.3-5.2,5.2
                  s2.3,5.2,5.2,5.2S23,18.9,23,16S20.7,10.8,17.8,10.8z"/>          
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/quiz-completion-time-per-course', {

    title: __( 'Quiz completion time per course', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Graph of the quiz completion time per course', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-quiz-completion-time-per-course',
    icon ,
    attributes: {
        chartContent: {
            type:'html',
            default:'',
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
                    <QuizCompletionTimePerCourse></QuizCompletionTimePerCourse>
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
                    <div class="wisdm-learndash-reports-quiz-completion-time-per-course front">
                       
                    </div>
                </div>
            );
        },
} );



