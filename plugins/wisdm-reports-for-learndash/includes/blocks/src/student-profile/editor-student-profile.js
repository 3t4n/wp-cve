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
import { createHooks } from '@wordpress/hooks';

let globalHooks = createHooks();

import React, { Component } from "react";
import StudentProfile from './index-student-profile';

const icon = (
            <svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
              <g>
                <path d="M9.5,12.1c-0.3-0.2-1,0-1.1,0.2L7,13.6l-1.3-1.3c-0.2-0.3-0.9-0.4-1.2-0.1c-0.3,0.4-0.1,1,0.1,1.1l1.3,1.3L4.6,16
                  c-0.2,0.2-0.4,0.7,0,1.1c0.3,0.4,0.9,0.1,1.1-0.1L7,15.8l1.3,1.3c0.2,0.2,0.8,0.3,1.2,0c0.3-0.3,0.1-1-0.1-1.1l-1.3-1.3l1.3-1.3
                  C9.7,13.2,9.9,12.5,9.5,12.1z M5.4,16.9L5.4,16.9L5.4,16.9L5.4,16.9z"/>
                <path d="M10.2,4.7C10,4.6,9.9,4.5,9.5,4.5C9.2,4.5,9,4.7,8.9,4.8L6,7.7l-1-1c-0.3-0.3-0.9-0.3-1.2,0C3.4,7,3.4,7.5,3.8,7.9l1.7,1.6
                  c0.2,0.2,0.4,0.3,0.6,0.3s0.4-0.1,0.6-0.3L10.2,6c0.2-0.2,0.3-0.4,0.3-0.6S10.4,4.9,10.2,4.7z"/>
              </g>
              <path d="M12,19.3H3.5c-1,0-1.8-0.8-1.8-1.8V4.8c0-1,0.8-1.8,1.8-1.8h14.4c1,0,1.8,0.8,1.8,1.8v4.7c0,0.4,0.3,0.8,0.8,0.8
                s0.8-0.3,0.8-0.8V4.8c-0.1-1.7-1.4-3-2.9-3.1l0,0h-15C1.6,1.8,0.2,3.2,0.2,4.8v12.8c0,1.8,1.5,3.2,3.2,3.2h8.5
                c0.4,0,0.8-0.3,0.8-0.8C12.7,19.7,12.4,19.3,12,19.3z"/>
              <g>
                <path d="M15.9,19.6L15.9,19.6c-0.2-0.1-0.4-0.4-0.1-0.6l3.4-6c0.1-0.2,0.4-0.4,0.6-0.1l0,0c0.2,0.1,0.4,0.4,0.1,0.6l-3.4,5.9
                  C16.4,19.6,16.2,19.7,15.9,19.6z"/>
                <path d="M15.3,13.5c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5c0.8,0,1.5-0.7,1.5-1.5C16.9,14.2,16.2,13.5,15.3,13.5z"/>
                <path d="M20.3,16.2c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5S21.2,16.2,20.3,16.2z"/>
              </g>
              <path d="M17.8,22.1c-3.3,0-6-2.7-6-6c0-3.3,2.7-6,6-6s6,2.7,6,6C23.7,19.5,21.1,22.1,17.8,22.1z M17.8,11c-2.9,0-5.2,2.3-5.2,5.2
                s2.3,5.2,5.2,5.2S23,19,23,16.2S20.6,11,17.8,11z"/>
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/student-profile', {

    title: __( 'Student Profile', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Student Profile', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-student-dashboard',
    icon ,
    attributes: {
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
                    <StudentProfile></StudentProfile>
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
                    <div class="wisdm-learndash-reports-student-profile front">
                        
                    </div>
                </div>
            );
        },
} );



