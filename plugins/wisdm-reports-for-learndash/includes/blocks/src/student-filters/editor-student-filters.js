/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType, RichText, source  } from '@wordpress/blocks';
import './editor.scss';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
var moment = require('moment');
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import React, { Component, CSSProperties } from "react";

import { useBlockProps } from '@wordpress/block-editor';
import StudentFilters from './index-student-filters';

const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M2.9,21.6c0,1.1,0.9,2,1.9,2h14.4c1.1,0,1.9-0.9,1.9-2V3.7c0-1.1-0.9-2-1.9-2h-2.6l-0.1-0.1c-0.7-0.8-1.7-1.2-2.7-1.2h-3.7
                    c-1,0-1.9,0.4-2.6,1.2L7.4,1.7H4.8c-1.1,0-2,0.9-2,2V21.6z M7.8,3.2c0.4-1,1.4-1.7,2.3-1.7h3.7c1,0,2,0.7,2.3,1.7l0.1,0.2H7.7
                    L7.8,3.2z M4.1,3.7c0-0.5,0.4-0.9,0.8-0.9h2L6.7,3C6.7,3.3,6.5,3.7,6.5,4c0,0.4,0.2,0.6,0.6,0.6h9.7c0.3,0,0.5-0.3,0.5-0.6
                    c0-0.3,0-0.6-0.1-0.9l-0.1-0.2h2c0.5,0,0.8,0.4,0.8,0.9v17.9c0,0.5-0.4,0.9-0.8,0.9H4.8c-0.5,0-0.8-0.4-0.8-0.9V3.7z"/>
                <path d="M8.8,14l-1.1,1.1l-0.4-0.4c-0.1-0.1-0.3-0.1-0.4-0.1c-0.1,0-0.2,0-0.4,0.1c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0,0l0.6,0.7c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l1.4-1.4c0.2-0.1,0.3-0.3,0.3-0.4c0-0.1,0-0.3-0.2-0.4
                    c-0.1-0.1-0.3-0.2-0.5-0.2C8.9,13.9,8.8,13.9,8.8,14z"/>
                <path d="M11.6,14.6c-0.3,0-0.5,0.3-0.5,0.6s0.2,0.6,0.5,0.6h5.5c0.3,0,0.5-0.3,0.5-0.6c0-0.4-0.2-0.6-0.5-0.6H11.6z"/>
                <path d="M8.8,17.9L7.7,19l-0.4-0.4c-0.1-0.1-0.3-0.1-0.4-0.1s-0.2,0-0.4,0.1c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0.7,0.7c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l1.4-1.5c0.1-0.1,0.2-0.2,0.2-0.3c0-0.2,0-0.4-0.2-0.5S9.2,17.7,9,17.8
                    C8.9,17.8,8.8,17.8,8.8,17.9z"/>
                <path d="M11.6,18.5c-0.3,0-0.5,0.3-0.5,0.6s0.2,0.6,0.5,0.6h5.5c0.3,0,0.5-0.3,0.5-0.6c0-0.4-0.2-0.6-0.5-0.6H11.6z"/>
                <path d="M16,9.8c0.3,0,0.5-0.3,0.5-0.6l0-1.7c0-0.1,0-0.2,0-0.3C16.4,7,16.3,7,16.1,7h-1.7c-0.3,0-0.5,0.3-0.5,0.6
                    c0,0.3,0.2,0.6,0.5,0.6l0.4,0L13,10.1l-2.2-2.2c-0.3-0.2-0.6-0.2-0.9-0.1l-2.5,2.5c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0,0c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l2-2.1l2.2,2.1c0.1,0.1,0.3,0.1,0.4,0.1s0.2,0,0.4-0.1l2.4-2.4l0,0.4
                    C15.5,9.4,15.7,9.8,16,9.8z"/>
            </svg>
);

document.addEventListener("DOMContentLoaded", function(event) {
    let elem = document.getElementsByClassName('wisdm-learndash-reports-report-filters front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(ReportFilters), elem[0]); 
    }
});


registerBlockType( 'wisdm-learndash-reports/student-filters', {

    title: __( 'Student Filter', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'A block with duration selectors for the LearnDash reports', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-student-filters',
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
                    <StudentFilters></StudentFilters>
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
                    <div class="wisdm-learndash-reports-student-filters front">
                       
                    </div>
                </div>
            );
        },
} );



