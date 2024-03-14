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
import QuizCompletionRate from './index-quiz-completion-rate-per-course.js';
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
                  <path d="M10.6,15H8.7c-0.1,0-0.3,0-0.4,0.1c-0.1,0-0.1,0.1-0.1,0.2v6c0,0,0,0.1,0.1,0.2c0.1,0.1,0.2,0.1,0.4,0.1h2.1
                    c0.5,0,0.9-0.1,1.3-0.4c0.3-0.3,0.5-0.8,0.5-1.4v-0.2c0-0.4-0.1-0.7-0.2-0.9c-0.1-0.2-0.4-0.3-0.6-0.5L11.2,18l0.4-0.2
                    c0.5-0.2,0.7-0.6,0.7-1.3C12.3,15.5,11.8,15,10.6,15z M9.3,15.9h1.2c0.2,0,0.4,0.1,0.5,0.3c0.1,0.2,0.2,0.4,0.2,0.6
                    c0,0.2-0.1,0.4-0.2,0.6c-0.1,0.2-0.3,0.3-0.5,0.3H9.3V15.9z M11.4,19.4v0.2c0,0.9-0.6,1-0.9,1H9.3v-2.2h1.1
                    C10.8,18.4,11.4,18.5,11.4,19.4z"/>
                  <path d="M6,15H0.9c-0.4,0-0.7,0.3-0.7,0.7v5.2c0,0.4,0.3,0.7,0.7,0.7H6c0.4,0,0.7-0.3,0.7-0.7v-5.2C6.7,15.3,6.4,15,6,15z
                     M5.4,20.2H1.5v-3.9h3.9V20.2z"/>
                  <g>
                    <path d="M23.6,2.5h-8c0,0-0.2,0.2-0.2,0.6c0,0.4,0.2,0.6,0.2,0.6h8c0,0,0.2-0.2,0.2-0.6C23.8,2.6,23.6,2.5,23.6,2.5z"/>
                    <path d="M15.8,5.6c-0.2,0-0.4,0.3-0.4,0.6s0.2,0.6,0.4,0.6H21c0.2,0,0.4-0.3,0.4-0.6S21.3,5.6,21,5.6H15.8z"/>
                    <path d="M23.6,15h-8c0,0-0.2,0.2-0.2,0.6s0.2,0.6,0.2,0.6h8c0,0,0.2-0.2,0.2-0.6S23.6,15,23.6,15z"/>
                    <path d="M15.8,18.2c-0.2,0-0.4,0.3-0.4,0.6s0.2,0.6,0.4,0.6H21c0.2,0,0.4-0.3,0.4-0.6s-0.2-0.6-0.4-0.6H15.8z"/>
                  </g>
                  <path d="M11.1,2.6c-0.3-0.1-0.8-0.1-1.1,0C9.9,2.6,9.8,2.7,9.8,2.8L8,8.6c0,0,0,0,0,0.1c0,0,0,0.1,0.2,0.3C8.4,9,8.6,9,8.8,9
                    C9,9,9.1,8.9,9.1,8.9l0.4-1.3h2.3l0.4,1.3c0,0,0,0.1,0.3,0.1c0.2,0,0.4-0.1,0.5-0.2c0.2-0.1,0.2-0.2,0.2-0.3c0,0,0,0,0-0.1
                    l-1.8-5.7C11.3,2.7,11.2,2.6,11.1,2.6z M9.6,6.8l0.9-3.4l0.9,3.4H9.6z"/>
                  <path d="M6.7,3.2c0-0.4-0.3-0.7-0.7-0.7H0.9c-0.4,0-0.7,0.3-0.7,0.7v5.2C0.2,8.7,0.5,9,0.9,9H6c0.4,0,0.7-0.3,0.7-0.7V3.2z
                     M5.4,7.7H1.5V3.8h3.9V7.7z"/>
                </g>
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/quiz-completion-rate-per-course', {

    title: __( 'Quiz Completion Rate', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Graph of the quiz completion rate', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-quiz-completion-rate-per-course',
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
                    <QuizCompletionRate></QuizCompletionRate>
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
                    <div class="wisdm-learndash-reports-quiz-completion-rate-per-course front">
                       
                    </div>
                </div>
            );
        },
} );



