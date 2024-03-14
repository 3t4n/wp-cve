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
import TotalCourses from './index-total-courses.js';
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M9.2,6.9C9,6.8,8.9,6.8,8.7,6.8l0,0C8.3,6.7,8,7.2,8,7.4v5.7c0,0.3,0.2,0.5,0.3,0.6s0.3,0.1,0.4,0.1c0.1,0,0.2,0,0.3-0.1
                  l4.3-2.9c0.3-0.2,0.5-0.3,0.5-0.5s-0.1-0.3-0.4-0.5L9.2,6.9z M11.8,10.3l-2.5,1.5V8.7L11.8,10.3z"/>
                <path d="M22.3,10.7h-7.2c-0.7,0-1.3,0.6-1.3,1.3v10c0,0.7,0.6,1.3,1.3,1.3h7.2c0.7,0,1.3-0.6,1.3-1.3V12
                  C23.6,11.3,23,10.7,22.3,10.7z M22.5,12v10c0,0.1-0.2,0.3-0.3,0.3H15c-0.1,0-0.3-0.2-0.3-0.3V12c0-0.1,0.2-0.3,0.3-0.3h7.2
                  C22.4,11.7,22.5,11.9,22.5,12z"/>
                <path d="M17.7,17.5H16c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C18.2,17.7,17.9,17.5,17.7,17.5z"/>
                <path d="M17.7,19.7H16c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C18.2,19.9,17.9,19.7,17.7,19.7z"/>
                <path d="M21.3,17.5h-1.6c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5S21.5,17.5,21.3,17.5z"/>
                <path d="M21.3,19.7h-1.6c-0.3,0-0.5,0.2-0.5,0.5s0.2,0.5,0.5,0.5h1.6c0.3,0,0.5-0.2,0.5-0.5C21.8,19.9,21.5,19.7,21.3,19.7z"/>
                <path d="M22.6,3.8c-0.1-1.7-1.4-3.1-3.1-3.3l0,0H3.4C1.6,0.7,0.3,2,0.3,3.8v13.7c0,1.9,1.6,3.5,3.5,3.5H12c0.5,0,0.8-0.3,0.8-0.8
                  s-0.3-0.8-0.8-0.8H3.7c-1,0-1.9-0.8-1.9-1.9V3.8c0-1,0.8-1.9,1.9-1.9h15.5c1,0,1.9,0.8,1.9,1.9v5c0,0.5,0.3,0.8,0.8,0.8
                  s0.8-0.3,0.8-0.8L22.6,3.8z"/>
                <g>
                  <path d="M21.6,15.8h-5.8c-0.1,0-0.2-0.1-0.2-0.2v-2.1c0-0.1,0.1-0.2,0.2-0.2h5.8c0.1,0,0.2,0.1,0.2,0.2v2.1
                    C21.8,15.7,21.7,15.8,21.6,15.8z M16,15.3h5.3v-1.6H16V15.3z"/>
                </g>
            </svg>
);


registerBlockType( 'wisdm-learndash-reports/total-courses', {

    title: __( 'Total Courses', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Displays Count of the courses', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-total-courses',
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
                  <div class="wisdm-learndash-reports-total-courses">
                    <TotalCourses></TotalCourses>
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
                    <div class="wisdm-learndash-reports-total-courses front">
                    </div>
                </div>
            );
        },
} );



