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
import TotalRevenueEarned from './index-total-revenue-earned.js';
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M12.4,18.4l0.2,0c1-0.1,1.8-1,1.8-2c0-1.1-0.9-2-2-2h-0.9c-0.5,0-0.9-0.4-0.9-0.9s0.4-0.9,0.9-0.9h2.5
                  c0.4,0,0.6-0.2,0.6-0.6s-0.2-0.6-0.6-0.6h-1.5v-0.9c0-0.4-0.2-0.6-0.6-0.6c-0.3,0-0.6,0.2-0.6,0.6v0.9l-0.1,0c-1,0.1-1.7,1-1.7,2
                  c0,1.1,0.9,2,2,2h0.9c0.5,0,0.9,0.4,0.9,0.9s-0.4,0.9-0.9,0.9H9.8c-0.4,0-0.6,0.2-0.6,0.6s0.2,0.6,0.6,0.6h1.4v0.9
                  c0,0.3,0.2,0.6,0.6,0.6c0.4,0,0.6-0.2,0.6-0.6V18.4z"/>
                <path d="M8.5,23.7h6.8c1.5,0,2.7-0.5,3.7-1.5c1.1-1.2,1.7-2.9,1.7-4.9c0-3.8-1.8-7.9-4.4-10.2L16.1,7l0.2-0.1
                  c0.4-0.3,0.5-0.7,0.5-1.2c0-0.6-0.3-1.1-0.8-1.3l-0.2-0.1L17.3,3c0.6-0.4,0.8-1.1,0.5-1.7c-0.2-0.6-0.8-1-1.4-1H7.4
                  c-0.6,0-1.1,0.4-1.5,1C5.6,1.9,5.8,2.6,6.4,3L8,4.3L7.8,4.4C7.3,4.7,7,5.2,7,5.7c0,0.4,0.2,0.8,0.5,1.1L7.6,7L7.5,7.1
                  c-2.6,2.3-4.4,6.4-4.4,10.2C3.1,20.5,4.7,23.7,8.5,23.7z M15.7,5.7c0,0.3-0.2,0.4-0.4,0.4h-0.4c-0.4-0.1-1.4-0.1-5.8,0l-0.5,0
                  C8.2,6.2,8.1,6,8.1,5.7s0.2-0.4,0.4-0.4h6.8C15.6,5.3,15.7,5.5,15.7,5.7z M7.2,2C7,1.9,7,1.8,7,1.7c0,0,0-0.1,0-0.1l0-0.1
                  c0,0,0.1-0.2,0.3-0.2h9.2c0.1,0,0.2,0,0.2,0.1l0.1,0.2c0,0,0,0.3-0.2,0.4l0,0L14,4.2H9.9L7.2,2z M9.2,7.3L9.2,7.3l5.4,0l0,0
                  c2.8,1.8,4.9,6.1,4.9,10.1c0,2.4-1.1,5.3-4.2,5.3H8.5c-3.1,0-4.2-2.8-4.2-5.3C4.2,13.2,6.3,9,9.2,7.3z"/>                            
            </svg>
);

registerBlockType( 'wisdm-learndash-reports/total-revenue-earned', {

    title: __( 'Total Revenue', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'Displays the total revenue earned during the selected time & its comparison with the previous duration', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-total-revenue-earned',
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
                        <TotalRevenueEarned></TotalRevenueEarned>                   
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
                  <div class="wisdm-learndash-reports-total-revenue-earned front">
                  </div>
                </div>
            );
        },
} );



