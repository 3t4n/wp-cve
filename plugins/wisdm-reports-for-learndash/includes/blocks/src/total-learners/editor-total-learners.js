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
import React, { Component } from "react";
import WisdmLoader from '../commons/loader/index.js';
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';
import TotalLearners from './index-total-learners.js';
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <g>
                  <path d="M7.4,10.8L7.4,10.8l0.1,0.1C7.6,12,8.3,13,9.2,13.8l0.8,0.4c1,0.5,2.7,0.5,3.7,0l0.8-0.4c0.9-0.8,1.5-1.8,1.7-2.9v-0.1h0.1
                    c0.4-0.1,0.7-0.5,0.7-1v-3l0,0c0.1-0.1,0.2-0.4,0.2-0.6V4.8l2.1-0.4c0.3-0.1,0.6-0.3,0.6-0.8l0,0c0.1-0.2,0-0.4-0.1-0.5
                    c-0.1-0.2-0.3-0.3-0.5-0.4l-7.5-2.4h-0.5L4.1,2.7C3.7,2.8,3.5,3.2,3.5,3.6c0,0.2,0.1,0.3,0.2,0.5c0,0.1,0.1,0.1,0.1,0.2v0.1v1.8
                    H3.7c-0.5,0-0.8,0.4-0.8,0.7v0.9l-0.4,3.1c0,0.2,0,0.5,0.1,0.8C2.7,11.9,2.9,12,3.3,12H5c0.3,0,0.5-0.1,0.7-0.3l0,0
                    c0.1-0.2,0.2-0.5,0.1-0.6V11L5.4,8V6.9c0-0.3-0.3-0.7-0.7-0.8H4.6V4.5l1.7,0.3v1.5c0,0.3,0.1,0.5,0.2,0.6l0,0v3
                    C6.6,10.3,7,10.7,7.4,10.8z M16.1,10.1h-0.2c-0.4,0-0.6,0.3-0.6,0.6c-0.1,1.9-1.6,3.1-3.4,3.1s-3.3-1.2-3.4-3.1
                    c0-0.4-0.3-0.7-0.7-0.7c-0.1,0-0.3-0.1-0.3-0.3V9h0.4c0.7,0,1.2-0.5,1.2-1.2V7h5.4v0.7c0,0.7,0.6,1.3,1.2,1.3h0.4V10.1z M7.5,8.1V7
                    h0.8v0.7c0,0.3-0.1,0.4-0.4,0.4C7.9,8.1,7.5,8.1,7.5,8.1z M16.1,8.1h-0.4c-0.1,0-0.4-0.1-0.4-0.4V7h0.8V8.1z M3.8,7h0.7v0.5H3.8V7z
                     M4.6,8.3L5,10.9H3.3l0.4-2.5L4.6,8.3L4.6,8.3z M4.4,3.5l7.4-2.4l7.4,2.4l-2.4,0.4L15.7,4c-0.1,0.1-0.1,0.2-0.1,0.4
                    c0,0.3,0.1,0.4,0.4,0.5L16.7,5v1.3H7.3V4.9l3.9-0.5c0.5-0.1,1-0.1,1.7,0l1.3,0.1c0.1,0,0.3-0.2,0.3-0.3c0-0.3-0.1-0.4-0.4-0.5
                    l-1.3-0.1c-0.7-0.1-1.3-0.1-1.8,0L6.8,4L4.4,3.5z"/>
                  <g>
                    <path d="M22,18.9v-0.4c0-1-0.7-1.8-1.6-2.1l-6.2-1.7v-1l-0.9,0.6v0.5L12,16.1l-1.3-1.3v-0.5l-0.9-0.5v0.9l-6.2,1.7
                      c-1,0.3-1.6,1.1-1.6,2.1v0.4v0.9v2v1.5c0,0.2,0.2,0.4,0.4,0.4h19.2c0.2,0,0.4-0.2,0.4-0.4v-1.1v-2.4V18.9z M21.1,22.8h-2.6v-3.5
                      h-0.9v3.5H6.3v-3.5H5.5v3.5H2.9v-4.3c0-0.6,0.4-1.1,1-1.3l6.3-1.8l1.6,1.6c0.2,0.2,0.4,0.2,0.6,0l1.6-1.6l6.3,1.8
                      c0.6,0.2,1,0.7,1,1.3v4.3H21.1z"/>
                  </g>
                </g>                
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/total-learners', {

    title: __( 'Total Learners', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Displays Count of the enrolled learners', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-total-learners',
    icon ,
    attributes: {
        blockContent: {
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
                  <div class="wisdm-learndash-reports-total-learners">
                    <TotalLearners></TotalLearners>
                  </div>
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
                        <div class="wisdm-learndash-reports-total-learners front">
                            
                        </div>
                    </div>
            );
        },
} );



