import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
// import WisdmLoader from '../commons/loader/index.js';

class ProgressDetailsTable extends Component {

  constructor(props) {
    super(props);
    this.state = {
      type : props.type,
      data_point : props.data_point,
      table : props.table,
      course : props.course,
      learner : props.learner,
    }
  }

  static getDerivedStateFromProps(props, state) {
      if( props.type !== state.type ){
          //Change in props
          return{
              type: props.type
          };
      }
      if( props.data_point !== state.data_point ){
          //Change in props
          return{
              data_point: props.data_point
          };
      }
      if( props.table !== state.table ){
          //Change in props
          return{
              table: props.table
          };
      }
      if( props.course !== state.course ){
          //Change in props
          return{
              course: props.course
          };
      }
      if( props.learner !== state.learner ){
          //Change in props
          return{
              learner: props.learner
          };
      }
      return null; // No change to state
  }

  render(){
    let table = ( 
        <table>
            <tbody>
                <tr>
                    <th>
                        {this.state.type == 'learner' ? __( 'Courses', 'learndash-reports-by-wisdmlabs' ) : __( 'Active Learners', 'learndash-reports-by-wisdmlabs' )}
                    </th>
                    <th>{__('Enrollment Date', 'learndash-reports-by-wisdmlabs')}</th>
                    <th>
                        {this.state.type == 'learner' ? __( 'Progress %', 'learndash-reports-by-wisdmlabs' ) : __( 'Progress %', 'learndash-reports-by-wisdmlabs' )}
                    </th>
                </tr>
                {
                    Object.keys(this.state.table).map( ( key, index ) => (
                      <tr>
                        <td width='45%'><span className='course-name'>{ key }</span></td>
                        <td>
                            <span>{this.state.table[ key ].enrolled_on}</span>
                        </td>
                        <td>
                            <span>{this.state.table[ key ].progress}%</span>
                        </td>
                      </tr>
                    ) )
                }
                {
                  ( 0 == this.state.table.length ) ? <tr>{this.state.type == 'learner' ? __( 'No Courses in this progress range.', 'learndash-reports-by-wisdmlabs' ) : __( 'No Learners in this progress range.', 'learndash-reports-by-wisdmlabs' )}</tr> : ''
                }
            </tbody>
        </table>
    );
    let header = (
        <div className='heading_wrapper'>
            <h1>{this.state.type=='learner' ? this.state.learner + '\'s progress' : this.state.course + ' course'}</h1>
            <div>
              {this.state.type=='learner' ?
              <><span>{__( 'Following are the courses for which completion percentage rate is ', 'learndash-reports-by-wisdmlabs' )}</span><strong>{this.state.data_point}</strong></> :
              <><span>{__( 'Following are the learners in this course for which completion percentage rate is ', 'learndash-reports-by-wisdmlabs' )}</span><strong>{this.state.data_point}</strong></>}
            </div>
        </div>
    );
    return (
        <div>
        	<div className="header">
                {header}
            </div>
            <div className="wisdm-learndash-reports-course-completion-table">
                {table}
            </div>
        </div>
    );
  }
}

export default ProgressDetailsTable;
