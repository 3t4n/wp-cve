import './index.scss';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmFilters from '../commons/filters/index.js';
import WisdmLoader from '../commons/loader/index.js';
import DummyReports from '../commons/dummy-reports/index.js';
import React, { Component } from "react";
import Chart from "react-apexcharts";
import moment from 'moment';
import { __ } from '@wordpress/i18n';

function wisdmLDRConvertTime(seconds) {                     
  var hours = Math.floor(seconds / 3600);
  var minutes = Math.floor(seconds % 3600 / 60);
  var seconds = Math.floor(seconds % 3600 % 60);
  if (hours   < 10) {hours   = "0" + hours;}
  if (minutes < 10) {minutes = "0" + minutes;}
  if (seconds < 10) {seconds = "0" + seconds;}
  if ( !!hours ) {                                         
    if ( !!minutes ) {                                     
      return `${hours}:${minutes}:${seconds}`           
    } else {                                               
      return `${hours}:00:${seconds}`                       
    }                                                      
  }                                                        
  if ( !!minutes ) {                                       
    return `00:${minutes}:${seconds}`                       
  }                                                        
  return `00:00:${seconds}`                                     
}

class QuizCompletionTimePerCourse extends Component {
  constructor(props) {
    super(props);

    this.state = {
      isLoaded: false,
      error: null,   
      graph_type:'bar',
      series:[],
      options:[],
      graph_summary:[],
      show_supporting_text: false,
      learner_filter_applied:false,
      course_filter_applied:false,
      start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      reportTypeInUse: wisdm_learndash_reports_front_end_script_quiz_completion_time_per_course.report_type,
      request_data:null,
      chart_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Completion Time', 'learndash-reports-by-wisdmlabs'),
      help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ?  __('This report displays the avg time learners take to complete the quizzes in each course.','learndash-reports-by-wisdmlabs') : __('This report displays the time taken by a learner to complete quizzes in a particular course.','learndash-reports-by-wisdmlabs'),
      course_report_type: null,
    };

    this.durationUpdated        = this.durationUpdated.bind(this);
    this.applyFilters           = this.applyFilters.bind(this);
    this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
    this.showDummyImages        = this.showDummyImages.bind(this);
  }

  isValidGraphData() {
    if (undefined==this.state.options || 0==this.state.options.length) {
      return false;
    } 
    if (undefined==this.state.series || undefined==this.state.series[0]) {
      return false;
    }
    if (undefined==this.state.series[0] || 0==this.state.series[0].length) {
      return false;
    }
    return true;
  }


  componentDidMount() {   
    this.updateChart('/rp/v1/quiz-completion-time-per-course/?start_date=' + this.state.start_date + "&&end_date=" + this.state.end_date);
    document.addEventListener('duration_updated', this.durationUpdated); 
    document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
    document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
    document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
  }

  componentDidUpdate() {
    jQuery('.wisdm-learndash-reports-quiz-completion-time-per-course .mixed-chart').prepend(jQuery('.wisdm-learndash-reports-quiz-completion-time-per-course .apexcharts-toolbar'));
    jQuery( ".wisdm-learndash-reports-quiz-completion-time-per-course .chart-title .dashicons, .wisdm-learndash-reports-quiz-completion-time-per-course .chart-summary-revenue-figure .dashicons" ).hover(

      function() {
        var $div = jQuery('<div/>').addClass('wdm-tooltip').css({
            position: 'absolute',
            zIndex: 999,
            display: 'none'
        }).appendTo(jQuery(this));
        $div.text(jQuery(this).attr('data-title'));
        var $font = jQuery(this).parents('.graph-card-container').css('font-family');
        $div.css('font-family', $font);
        $div.show();
      }, function() {
        jQuery( this ).find( ".wdm-tooltip" ).remove();
      }
    );
  }

  handleReportTypeChange(event) {
    this.setState({reportTypeInUse:event.detail.active_reports_tab});
    if ( 'quiz-reports' == event.detail.active_reports_tab ) {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-quiz-completion-time-per-course', false);
    } else {
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-quiz-completion-time-per-course', true);
    }
  }

  showDummyImages(event){
    this.setState({course_report_type:event.detail.report_type})
  }

  updateChart(requestUrl) {
    this.setState({isLoaded:false, error:null, request_data:null});
    let self = this;  
    let checkIfEmpty = function() {
        setTimeout(function () {
          if (window.callStack.length > 4) {
            checkIfEmpty();
          }
          else {
          window.callStack.push(requestUrl);
          if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
            requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
          }
          wp.apiFetch({
            path: requestUrl //Replace with the correct API
          }).then(response => {
            if(response.requestData) {
              self.setState({request_data:response.requestData})
            }
            if ( response.updated_on ) {
              self.setState({updated_on: response.updated_on});
            }
              self.plotChartTypeBy(response); 
            }).catch((error) => {
              window.callStack.pop();
              if(error.data && error.data.requestData) {
                self.setState({request_data:error.data.requestData})
              }
              self.setState({
                error:error,
                    graph_summary:[],
                isLoaded: true,
                series:[],
              })
            });
          }
        }, 500);
      };
      checkIfEmpty();
  }

  plotChartTypeBy(response) {
    if (undefined!=response.courseWiseTime) {
      const time = Object.values( response.courseWiseTime ).map( obj => parseInt( obj.time ) );
      const courses = Object.values( response.courseWiseTime ).map( obj => obj.course );
      let labelX    = 0!=response.requestData.course.length?__('Learners', 'learndash-reports-by-wisdmlabs'):wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses');
      this.plotBarChart(courses, time, labelX, __('Time spent on ', 'learndash-reports-by-wisdmlabs')+ ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes'));

      let summaryLeftLabel =  __('AVG', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('COMPLETION TIME', 'learndash-reports-by-wisdmlabs');
      this.setState(
        {
        isLoaded: true,
        graph_summary: {
          left: [{
                title : summaryLeftLabel,
                value: wisdmLDRConvertTime(response.averageCourseTime),
              },],
              
          right:[ 
            {
              title: __('Avg. Time Spent on ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') + __(' all ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('courses') + ': ',
              value:wisdmLDRConvertTime(response.courseTotalTime),
            },
            {
              title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses') + ': ',
              value: Object.keys(response.courseWiseTime).length,
            }],
          inner_help_text: __('Avg Quiz Completion Time = Avg Time spent on quizzes in all courses/No. of Courses.', 'learndash-reports-by-wisdmlabs'),
        },
        help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ? __('This report displays the avg time learners take to complete the quizzes in each course.','learndash-reports-by-wisdmlabs') : __('This report displays the time taken by a learner to complete quizzes in a particular course.','learndash-reports-by-wisdmlabs'),
      });

    } else if(undefined!=response.learnerWiseTime) {
      let user_names = Object.values( response.learnerWiseTime ).map( obj => obj.name );
      let time  = Object.values( response.learnerWiseTime ).map( obj => obj.time );
      let labelX    = 0!=response.requestData.course.length?__('Learners', 'learndash-reports-by-wisdmlabs'):wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses');
      this.plotBarChart(user_names, time, labelX, __('Avg Quiz Completion Time.', 'learndash-reports-by-wisdmlabs'));          
      if ( this.state.request_data.lesson == "" ) {
        this.setState(
          {
          isLoaded: true,
          graph_summary: {
            left: [{
                  title : __('AVG QUIZ COMPLETION TIME PER LEARNER', 'learndash-reports-by-wisdmlabs'),
                  value: wisdmLDRConvertTime(response.averageQuizTime),
                },],
                
            right:[ 
              {
                title: __('Avg Time Spent on ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') + ': ',
                value:wisdmLDRConvertTime(response.quizTotalTime),
              },
              {
                title: __('Learners - Attempted Quizzes', 'learndash-reports-by-wisdmlabs') + ': ',
                value: response.studentCount
              }],
            inner_help_text: __('Avg Quiz Completion Time = Avg time taken by all learners to complete quizzes in this course/No. of Learners', 'learndash-reports-by-wisdmlabs'),
          },
          help_text: __('This report displays the average time that learners in this course take to complete quizzes.', 'learndash-reports-by-wisdmlabs'),
        });
      } else if ( this.state.request_data.lesson != "" && this.state.request_data.topic != "" ) {
        this.setState({
          isLoaded: true,
          graph_summary: {
            left: [{
                title : __('AVG QUIZ COMPLETION TIME PER LEARNER', 'learndash-reports-by-wisdmlabs'),
                value: wisdmLDRConvertTime(response.averageQuizTime),
              },],
              
          right:[ 
            {
              title: __('Avg Time Spent on ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') + ': ',
              value:wisdmLDRConvertTime(response.quizTotalTime),
            },
            {
              title: __('Learners - Attempted Quizzes', 'learndash-reports-by-wisdmlabs') + ': ',
              value: response.studentCount
            }],
            inner_help_text: __('Avg Quiz Completion Time = Avg time taken by all learners to complete quizzes in this topic/No. of Learners', 'learndash-reports-by-wisdmlabs'),
          },
          help_text: __('This report displays the average time that learners in this topic take to complete quizzes.', 'learndash-reports-by-wisdmlabs'),
        }); 
      } else if (this.state.request_data.topic == "") {
          this.setState({
            isLoaded: true,
            graph_summary: {
              left: [{
                  title : __('AVG QUIZ COMPLETION TIME PER LEARNER', 'learndash-reports-by-wisdmlabs'),
                  value: wisdmLDRConvertTime(response.averageQuizTime),
                },],
              right:[ 
                {
                  title: __('Avg Time Spent on ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') + ': ',
                  value:wisdmLDRConvertTime(response.quizTotalTime),
                },
                {
                  title: __('Learners - Attempted Quizzes', 'learndash-reports-by-wisdmlabs') + ': ',
                  value: response.studentCount
                }],
              inner_help_text: __('Avg Quiz Completion Time = Avg time taken by all learners to complete quizzes in this lesson/No. of Learners', 'learndash-reports-by-wisdmlabs'),
            },
          help_text: __('This report displays the average time that learners in this lesson take to complete quizzes.', 'learndash-reports-by-wisdmlabs'),
          }); 
      }
    }
  }

  plotBarChart(dataX, dataY, nameX, nameY) {
    if ( wisdm_ld_reports_common_script_data.is_rtl ) {
      dataX = dataX.reverse();
      dataY = dataY.reverse();
    }
    let chart_options = {
      chart: {
        id: "basic-bar",
        width: dataX.length*75 < 645 ? '100%' : dataX.length*75,
        height: 400,
        zoom:{
          enabled:false,
        },
        toolbar:{
          show:true,
          export: {
            csv: {
              filename: __( 'Completion Time.csv', 'learndash-reports-by-wisdmlabs' ),
              columnDelimiter: ',',
              headerCategory: nameX,
              headerValue: nameY,
            },
            svg: {
              filename: undefined,
            },
            png: {
              filename: undefined,
            }
          },
        },
        events: {
          mounted: function(chartContext, config) {
              window.callStack.pop();
            }
        }
      },
      colors: ["#008AD8"],
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return wisdmLDRConvertTime( val );
        },
        offsetY: -25,
        style: {
          fontSize: '12px',
          colors: ["#008AD8"]
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 5,
          dataLabels:{
            enabled: true,
            position:'top',
          }
        }
      },
      xaxis: {
        title: {
          text: nameX,
        },
        categories: dataX,
        labels:{
          hideOverlappingLabels: false,
          trim: true,
          rotate: wisdm_ld_reports_common_script_data.is_rtl ? 45 : -45,
        },
        tickPlacement: 'on',
        min:1,
      },
      yaxis: {
        
        max: Math.max.apply(Math, dataY)>0? Math.max.apply(Math, dataY) + Math.max.apply(Math, dataY)/10:10,
        tickAmount: 10,
        axisBorder: {
          show: ! wisdm_ld_reports_common_script_data.is_rtl
        },
        title: {
          text: nameY,
          offsetX: wisdm_ld_reports_common_script_data.is_rtl ? -20 : 0,
        },
        labels:{
          formatter: (value) => { return wisdmLDRConvertTime(value) },
          align: wisdm_ld_reports_common_script_data.is_rtl ? 'right' : 'left',
          offsetX: wisdm_ld_reports_common_script_data.is_rtl ? 30 : 0,
        },
        opposite: wisdm_ld_reports_common_script_data.is_rtl
      },
      tooltip: {
        enabled: true,
        y: {
          formatter: function(value){return wisdmLDRConvertTime(value)},
          title:{
            formatter:function(title) {
              return __('Time Spent: ', 'learndash-reports-by-wisdmlabs');
            }
          },
        },
      }
    };
    this.setState({graph_type:'bar', series:[{name:nameY, data:dataY}], options:chart_options});
  }

  applyFilters(event) {
    let start_date = event.detail.start_date;
    let end_date   = event.detail.end_date;
    let category   = event.detail.selected_categories;
    let group      = event.detail.selected_groups;
    let course     = event.detail.selected_courses;
    let lesson     = event.detail.selected_lessons;
    let topic      = event.detail.selected_topics;
    let learner    = event.detail.selected_learners;
    if ( undefined != course ) {
      this.setState({show_supporting_text: true});
    } else {
      this.setState({show_supporting_text: false});
    }
    
    this.setState({learner_filter_applied:null!=learner?true:false, error: null});
    this.setState({course_filter_applied:null!=course?true:false, error: null,});
    
 
    let request_url = '/rp/v1/quiz-completion-time-per-course/?start_date=' + start_date + '&end_date=' + end_date + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
 
    this.updateChart(request_url)
   
   }

  durationUpdated(event) {
    this.setState({isLoaded: false, start_date:event.detail.startDate, end_date:event.detail.endDate});
    let requestUrl = '/rp/v1/quiz-completion-time-per-course/';
    if ('duration_updated'==event.type) {
      requestUrl = '/rp/v1/quiz-completion-time-per-course/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
    } 
    if (window.globalfilters != undefined) {
      let category   = window.globalfilters.detail.selected_categories;
      let group      = window.globalfilters.detail.selected_groups;
      let course     = window.globalfilters.detail.selected_courses;
      let lesson     = window.globalfilters.detail.selected_lessons;
      let topic      = window.globalfilters.detail.selected_topics;
      let learner    = window.globalfilters.detail.selected_learners;
      requestUrl = requestUrl  + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;
      
    } 
    
    
      this.updateChart(requestUrl);
  }

  refreshUpdateTime() {
      this.setState({isLoaded: false});
      let requestUrl = '/rp/v1/quiz-completion-time-per-course/';
      if (window.globalfilters != undefined) {
        let category   = window.globalfilters.detail.selected_categories;
        let group      = window.globalfilters.detail.selected_groups;
        let course     = window.globalfilters.detail.selected_courses;
        let lesson     = window.globalfilters.detail.selected_lessons;
        let topic      = window.globalfilters.detail.selected_topics;
        let learner    = window.globalfilters.detail.selected_learners;
        requestUrl = requestUrl  + '?category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner + '&disable_cache=true';
      }   
      this.updateChart(requestUrl);
  }

  render() {
    let body = <div></div>;
    if(this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active){
      body =  <DummyReports image_path='qct.png' url="https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=learner-quiz-completion-time&utm_campaign=upgradetopro#pricing"></DummyReports>;
      return (body);
    }
    let data_validation = '';
    if (!this.isValidGraphData()) {
      data_validation = 'invalid-or-empty-data';
    }
    if(''!=this.state.reportTypeInUse && 'default-ld-reports'!=this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader text={this.state.show_supporting_text} />;
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div className="app">
                <div className="row">
                  <div className="mixed-chart">
                    <Chart
                      options={this.state.options}
                      series={this.state.series}
                      type={this.state.graph_type}
                      width={this.state.options.chart.width}
                      height={this.state.options.chart.height}
                    />
                  </div>
                </div>
              </div>;
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}>  
      <div class="wisdm-learndash-reports-quiz-completion-time-per-course graph-card-container">
        <WisdmFilters request_data={this.state.request_data}/>
        <div class="chart-header quiz-completion-time-per-course-chart-header">
          <div class="chart-title">
            <span>{this.state.chart_title}</span>
            <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          </div>
          <div className="chart_update_time"><span>{__( 'Last updated: ', 'learndash-reports-by-wisdmlabs' )}</span><span>{this.state.updated_on}</span><div className='chart-refresh-icon'><span class="dashicons dashicons-image-rotate" data-title={__('Click this to refresh the chart', 'learndash-reports-by-wisdmlabs')} onClick={this.refreshUpdateTime.bind(this)}></span></div></div>
          <ChartSummarySection wrapper_class='chart-summary-quiz-completion-time-per-course' graph_summary={this.state.graph_summary} error={this.state.error}/>
        </div>
        <div>
          {graph}
        </div>
      </div>
    </div>;
  }    
    return (body);
  }
}

export default QuizCompletionTimePerCourse;

document.addEventListener("DOMContentLoaded", function(event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-quiz-completion-time-per-course front');
  if (elem.length>0) {
    ReactDOM.render(React.createElement(QuizCompletionTimePerCourse), elem[0]); 
  }
});

