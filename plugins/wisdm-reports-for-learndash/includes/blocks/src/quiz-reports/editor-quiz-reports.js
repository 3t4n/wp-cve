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

import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';

import React, { Component } from "react";
const icon = (
    <svg version='1.0'
    xmlns='http://www.w3.org/2000/svg'
    width='24.000000pt'
    height='24.000000pt'
    viewBox='0 0 24.000000 24.000000'
    preserveAspectRatio='xMidYMid meet' >
        <path d="M11.9,8.7h4.8c0.3,0,0.6-0.3,0.6-0.7c0-0.4-0.3-0.7-0.6-0.7h-4.8c-0.3,0-0.6,0.3-0.6,0.7C11.4,8.3,11.6,8.7,11.9,8.7z"/>
        <path d="M16.7,12.4h-4.8c-0.3,0-0.6,0.3-0.6,0.7s0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7S17,12.4,16.7,12.4z"/>
        <path d="M16.5,17.5h-4.8c-0.3,0-0.6,0.3-0.6,0.7c0,0.4,0.3,0.7,0.6,0.7h4.8c0.3,0,0.6-0.3,0.6-0.7C17.1,17.5,16.7,17.5,16.5,17.5z"
        	/>
        <path d="M7.5,6.2C7.4,6.1,7.2,6.1,7,6.2C6.8,6.3,6.7,6.5,6.7,6.7v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4
        	C9.9,8.4,10,8.2,10,8.1c0-0.2-0.1-0.3-0.2-0.4L7.5,6.2z M8.6,8.1L7.7,8.6v-1L8.6,8.1z"/>
        <path d="M7.5,11.2c-0.1-0.1-0.3-0.1-0.5,0c-0.2,0.1-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4
        	c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,11.2z M8.6,13.1l-0.9,0.5v-1L8.6,13.1z"/>
        <path d="M7.5,16.2c-0.1-0.1-0.3-0.1-0.5,0s-0.3,0.3-0.3,0.5v2.9c0,0.2,0.1,0.5,0.3,0.5c0.2,0,0.4-0.1,0.5-0.2l2.3-1.4
        	c0.1-0.1,0.2-0.3,0.2-0.4c0-0.2-0.1-0.3-0.2-0.4L7.5,16.2z M8.6,18.1l-0.9,0.5v-1L8.6,18.1z"/>
        <path d="M2.9,21.6c0,1.1,0.9,2,1.9,2h14.4c1.1,0,1.9-0.9,1.9-2V3.7c0-1.1-0.9-2-1.9-2h-2.6l-0.1-0.1c-0.7-0.8-1.7-1.2-2.7-1.2h-3.7
        	c-1,0-1.9,0.4-2.6,1.2l0,0.1H4.9c-1.1,0-2,0.9-2,2V21.6z M7.8,3.2c0.4-1,1.4-1.7,2.3-1.7h3.7c1,0,2,0.7,2.3,1.7l0.1,0.2H7.7L7.8,3.2
        	z M4.1,3.7c0-0.5,0.4-0.9,0.8-0.9h2L6.8,3C6.7,3.3,6.6,3.7,6.6,4c0,0.4,0.2,0.6,0.6,0.6h9.7c0.3,0,0.5-0.3,0.5-0.6s0-0.6-0.1-0.9
        	l-0.1-0.2h2c0.5,0,0.8,0.4,0.8,0.9v17.9c0,0.5-0.4,0.9-0.8,0.9H4.9c-0.5,0-0.8-0.4-0.8-0.9V3.7z"/>
    </svg>
);

class QuizReport extends Component {
    constructor(props) {
      super(props);
  
        this.state = {
          isLoaded: false,
          error: null,
        };
      }
    
      componentDidMount() {    
        this.setState(
          {
          isLoaded: true,
          isProVersion:wisdm_learndash_reports_editor_script_quiz_reports.is_pro_version_active,
        }); 
      }
  
    render() {
      let body = <div>{__('Loading...', 'learndash-reports-by-wisdmlabs')}</div>;
      if (!this.state.isLoaded) {
        body = <div class="wisdm-learndash-reports-chart-block wisdm-ld-loading">
            <div>
              {__('Loading...', 'learndash-reports-by-wisdmlabs')}
            </div>
          </div>;
    } else if (this.state.error) {
        // error
        body = <div class="wisdm-learndash-reports-chart-block error">
              <div>{this.state.error.message}</div>
              </div>;
    } else {
        body = 
        <div class="wisdm-learndash-reports-chart-block">
          <div class="wisdm-learndash-reports-quiz-reports">
            <span>{__('This block will be hidden by default & will display the Quiz Reports when quiz reports are requested', 'learndash-reports-by-wisdmlabs')}</span>
          </div>
        </div>;    
    }
      return (body);
    }
  }

registerBlockType( 'wisdm-learndash-reports/quiz-reports', {
    title: __( 'Quiz Reports', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'A table containing a list of the courses', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-quiz-reports',
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
                  <QuizReport></QuizReport>
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
                    <div id="wisdm-learndash-reports-quiz-report-view" class="wisdm-learndash-reports-quiz-reports">
                      [ldrp_quiz_reports]
                    </div>
                </div>
            );
        },
} );



