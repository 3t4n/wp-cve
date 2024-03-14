import React, { Component } from "react";
import { __ } from '@wordpress/i18n';
import moment from 'moment';

class WisdmFilters extends React.Component {

  constructor(props) {
    super(props);
    if (undefined!=props.request_data) {
      this.category    = undefined!=props.request_data.category?props.request_data.category:'';
      this.group    = undefined!=props.request_data.group?props.request_data.group:'';
      this.course_name = undefined!=props.request_data.course?props.request_data.course:'';
      this.lesson_name = undefined!=props.request_data.lesson?props.request_data.lesson:'';
      this.topic_name = undefined!=props.request_data.topic?props.request_data.topic:'';
      this.learner_name = undefined!=props.request_data.learner?props.request_data.learner:'';
      this.start_date  = undefined!=props.request_data.start_date?props.request_data.start_date:'';
      this.end_date    = undefined!=props.request_data.end_date?props.request_data.end_date:'';
    }
  }


    getElement( label, value ) {
      let html = '';
      if (undefined==value || ''==value) {
        return html;
      }

      html = <div class="wisdm-filter-item">
              <img src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/complete.png'}>
              </img> 
              <span>
                {value}
              </span>
              <div className="wdm-tooltip">{label}: {value}</div>
            </div>
      return html;
    }

    getDuration(label, start_date, end_date) {
      let html = '';
      if (undefined==start_date || ''==start_date || undefined==end_date || ''==end_date) {
        return html;
      }
      html = <div class="wisdm-filter-item">
              <img src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/complete.png'}>
              </img> 
              {moment.unix(start_date).format("MMM, DD YYYY")} - {moment.unix(end_date).format("MMM, DD YYYY")}
            </div>
      return html;
    }

    render() {
     
      let filterData = '';
      if (''==this.category && ''==this.group && ''==this.start_date && ''==this.end_date && ''==this.course_name && ''==this.learner_name) {
        filterData = '';
      } else {
        let category = this.getElement('Category', this.category);
        let group    = this.getElement('Group', this.group);
        let course   = this.getElement('Course', this.course_name);
        let lesson   = this.getElement('Lesson', this.lesson_name);
        let topic   = this.getElement('Topic', this.topic_name);
        let learner   = this.getElement('Learner', this.learner_name);
        let duration = this.getDuration('Duration', this.start_date, this.end_date);

        filterData = <div class="wisdm-applied-filters">
            <label>{__('Filters', 'learndash-reports-by-wisdmlabs')}</label>
            {category}
            {group}
            {course}
            {lesson}
            {topic}
            {learner}
            {duration}
          </div>;
        }
      return filterData;
    }
  }

  export default WisdmFilters;
