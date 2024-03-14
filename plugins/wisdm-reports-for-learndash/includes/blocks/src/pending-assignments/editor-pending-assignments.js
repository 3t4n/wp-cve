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
import PendingAssignments from './index-pending-assignments.js';
var ld_api_settings = wisdm_learndash_reports_front_end_script_pending_assignments.ld_api_settings;
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <g>
                  <g>
                    <path d="M17.7,6.6h-5.5c-0.4,0-0.7,0.3-0.7,0.6c-0.1,0.1-0.1,0.3,0,0.4c0.1,0.3,0.4,0.5,0.8,0.5h5.5c0.5,0,0.7-0.4,0.7-0.7
                      C18.4,6.9,18,6.6,17.7,6.6z"/>
                    <path d="M17.7,10.4H6.5c-0.4,0-0.7,0.4-0.7,0.7c-0.1,0.2,0,0.4,0,0.5c0.1,0.2,0.4,0.3,0.7,0.3h11.1c0.5,0,0.7-0.4,0.7-0.7
                      C18.4,10.7,18,10.4,17.7,10.4z"/>
                    <path d="M17.7,13.9H6.5c-0.5,0-0.7,0.4-0.7,0.7c0,0.3,0.2,0.9,0.7,0.9h11.1c0.5,0,0.7-0.4,0.7-0.7C18.4,14.5,18.2,13.9,17.7,13.9z
                      "/>
                    <path d="M17.7,17.6H6.5c-0.5,0-0.7,0.4-0.7,0.7c0,0.3,0.2,0.9,0.7,0.9h11.1c0.5,0,0.7-0.4,0.7-0.7C18.4,18.2,18.2,17.6,17.7,17.6z
                      "/>
                  </g>
                  <path d="M21.1,2.4C20.9,1.6,20.3,1,19.4,1H4.6C3.7,1,2.9,1.8,2.9,2.7l0,9.3l0,9.3c0,0.9,0.7,1.7,1.7,1.7h14.9
                    c0.8,0,1.5-0.5,1.7-1.3V2.4z M19.8,21.2c0,0.2-0.1,0.4-0.2,0.4l-1.9,0H5.6v0H4.6c-0.2,0-0.2-0.2-0.3-0.3l0-6.5c0,0,0,0,0,0l0-2.8
                    l0-2.8c0,0,0,0,0,0l0-6.5c0-0.1,0.1-0.3,0.3-0.3h1.1v2c0,1.2,0.9,2.1,2.1,2.1c1.2,0,2.1-0.9,2.1-2.1V4.1c0-0.4-0.3-0.7-0.7-0.7
                    c-0.3,0-0.6,0.3-0.6,0.7v0.4c0,0.3-0.3,0.7-0.7,0.7C7.5,5.2,7.2,5,7.2,4.6V2.6l12.4,0c0.1,0,0.3,0.1,0.3,0.3V9V15V21.2z"/>
                </g>
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/pending-assignments', {

    title: __( 'Pending Assignments', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Displays Count of the pending assignments', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-pending-assignments',
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
                  <div class="wisdm-learndash-reports-pending-assignments">
                    <PendingAssignments></PendingAssignments>
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
                    <div class="wisdm-learndash-reports-pending-assignments front">
                    
                    </div>
                </div>
            );
        },
} );



