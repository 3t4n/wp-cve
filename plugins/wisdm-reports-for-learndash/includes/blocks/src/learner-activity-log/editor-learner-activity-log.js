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
import LearnerActivityLog from './index-learner-activity-log.js';
import { createHooks } from '@wordpress/hooks';

let globalHooks = createHooks();

import React, { Component } from "react";

const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M11.9,19.3H3.4c-1,0-1.8-0.8-1.8-1.8V4.8c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8
                  s0.8-0.3,0.8-0.8V4.8c-0.1-1.7-1.4-3-2.9-3.1l0,0h-15C1.5,1.8,0.1,3.2,0.1,4.8v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5
                  c0.4,0,0.8-0.3,0.8-0.8C12.6,19.7,12.3,19.3,11.9,19.3z"/>
                <path d="M9,8C8.9,8,8.7,7.9,8.6,7.9l0,0c-0.4,0-0.7,0.4-0.7,0.6v5.3c0,0.3,0.2,0.5,0.3,0.6c0.1,0.1,0.3,0.1,0.4,0.1
                  c0.1,0,0.2,0,0.3-0.1l4-2.7c0.3-0.2,0.5-0.3,0.5-0.5s-0.1-0.3-0.4-0.5L9,8z M11.5,11.2l-2.3,1.4V9.7L11.5,11.2z"/>
                <path d="M18.1,10.9c-3.1,0-5.7,2.5-5.7,5.7c0,3.2,2.5,5.7,5.7,5.7s5.7-2.5,5.7-5.7C23.7,13.4,21.2,10.9,18.1,10.9z M18.1,21.7
                  c-2.8,0-5.2-2.3-5.2-5.2c0-2.8,2.3-5,5.2-5s5.2,2.2,5.2,5C23.2,19.4,21,21.7,18.1,21.7z"/>
                <g>
                  <path d="M16.2,19.8L16.2,19.8c-0.2-0.1-0.4-0.4-0.1-0.6l3.4-6c0.1-0.2,0.4-0.4,0.6-0.1l0,0c0.2,0.1,0.4,0.4,0.1,0.6l-3.4,5.9
                    C16.6,19.8,16.4,20,16.2,19.8z"/>
                  <path d="M15.6,13.8c-0.8,0-1.5,0.7-1.5,1.5c0,0.8,0.7,1.5,1.5,1.5c0.8,0,1.5-0.7,1.5-1.5C17.1,14.5,16.4,13.8,15.6,13.8z"/>
                  <path d="M20.6,16.4c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5S21.4,16.4,20.6,16.4z"/>
                </g>
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/learner-activity-log', {

    title: __( 'Learner Activity Log', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Report of the Learner Activity Log`', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-learner-activity-log',
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
                  <LearnerActivityLog></LearnerActivityLog>
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
                    <div class="wisdm-learndash-reports-learner-activity-log front">
                        
                    </div>
                </div>
            );
        },
} );



