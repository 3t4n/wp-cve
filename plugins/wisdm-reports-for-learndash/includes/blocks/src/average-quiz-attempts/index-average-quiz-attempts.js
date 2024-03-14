import './index.scss';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmFilters from '../commons/filters/index.js';
import WisdmLoader from '../commons/loader/index.js';
import React, { Component } from "react";
import Chart from "react-apexcharts";
import moment from "moment"
import { __ } from '@wordpress/i18n';



class AverageQuizAttempts extends Component {
  constructor(props) {
    super(props);
    let error=null;
    if(null==this.getUserType()) {
      error = {message:__('Sorry you are not allowed to access this block, please check if you have proper access permissions','learndash-reports-by-wisdmlabs')}
    }
    this.state = {
      isLoaded: false,
      error: error,
      graph_type:'bar',
      series:[],
      options:{},
      graph_summary:[],
      reportTypeInUse: wisdm_learndash_reports_front_end_script_average_quiz_attempts.report_type,
      request_data:null,
      hidden:false,
      show_supporting_text: false,
      start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      chart_title:__('Average', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Attempts', 'learndash-reports-by-wisdmlabs'),
      help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ? __('This report displays the average attempts on the quizzes of this course.','learndash-reports-by-wisdmlabs') :  __('This report shows number of attempts quiz wise for a particular learner.','learndash-reports-by-wisdmlabs'),
    };

      this.durationUpdated        = this.durationUpdated.bind(this);
      this.applyFilters           = this.applyFilters.bind(this);
      this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
      this.plotChartTypeBy        = this.plotChartTypeBy.bind(this);
      this.updateChart            = this.updateChart.bind(this);
  }

  isValidGraphData() {
    if (undefined==this.state.options || 0==this.state.options.length) {
      return false;
    } 
    if (undefined==this.state.series || undefined==this.state.series[0]) {
      return false;
    }
    
    return true;
  }


  /**
   * Based on the current user roles aray this function desides wether a user is a group
   * leader or an Administrator and returns the same.
   */
  getUserType() {
    let userRoles = wisdm_learndash_reports_front_end_script_average_quiz_attempts.user_roles;
    if ('object'==typeof(userRoles)) {
      userRoles = Object.keys(userRoles).map((key) => userRoles[key]);
    }
    if (undefined==userRoles || userRoles.length==0) {
      return null;
    }
    if (userRoles.includes('administrator')) {
        return 'administrator';
    } else if (userRoles.includes('group_leader')) {
        return 'group_leader';
    }
    return null;
  }


  componentDidMount() {    
    this.updateChart('/rp/v1/average-quiz-attempts/?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date);
    document.addEventListener('duration_updated', this.durationUpdated);
    document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
    document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
    document.addEventListener('wisdm-ldrp-course-report-type-changed', this.manageBlockVisibility);
  }

  componentDidUpdate() {
    jQuery('.wisdm-learndash-reports-average-quiz-attempts .mixed-chart').prepend(jQuery('.wisdm-learndash-reports-average-quiz-attempts .apexcharts-toolbar'));
    jQuery( ".wisdm-learndash-reports-average-quiz-attempts .chart-title .dashicons, .wisdm-learndash-reports-average-quiz-attempts .chart-summary-revenue-figure .dashicons" ).hover(

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
      wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', false);
    } else {
      // if ( 'learner-specific-course-reports' == event.detail.report_type ) {
      //   this.setState({'hidden':true});
      // }
      if (!this.state.hidden) {
        wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', true); 
      }
    }
  }

  manageBlockVisibility(event) {
    if ('learner-specific-course-reports'==event.detail.report_type) {
      wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-average-quiz-attempts' , false );
    } else {
      wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-average-quiz-attempts', true );
    }
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
   if (null!=learner && learner>0) {
    this.setState({help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ?  __('This report displays the average attempts on the quizzes of this learner.','learndash-reports-by-wisdmlabs') :  __('This report shows number of attempts quiz wise for a particular learner.','learndash-reports-by-wisdmlabs') });
   } else if (null!=topic && topic>0) {
      this.setState({help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ? __('This report displays the average attempts on the quizzes of this topic.','learndash-reports-by-wisdmlabs') :  __('This report shows number of attempts quiz wise for a particular learner.','learndash-reports-by-wisdmlabs')});  
    } else if (null!=lesson && lesson>0) {
    this.setState({help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ? __('This report displays the average attempts on the quizzes of this lesson.','learndash-reports-by-wisdmlabs') :  __('This report shows number of attempts quiz wise for a particular learner.','learndash-reports-by-wisdmlabs')});
   } else {
    this.setState({help_text: ( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports') ? __('This report displays the average attempts on the quizzes of this course.','learndash-reports-by-wisdmlabs') :  __('This report shows number of attempts quiz wise for a particular learner.','learndash-reports-by-wisdmlabs')});
   }
   if ( undefined != course ) {
      this.setState({show_supporting_text: true});
    } else {
      this.setState({show_supporting_text: false});
    }
   let request_url = '/rp/v1/average-quiz-attempts/?start_date=' + start_date + '&end_date=' + end_date + '&category=' + category + '&group=' + group + '&course=' + course + '&lesson=' + lesson + '&topic=' + topic + '&learner=' + learner;

   this.updateChart(request_url);
  
  }

  durationUpdated(event) {
    this.setState({isLoaded: false, start_date:event.detail.startDate, end_date:event.detail.endDate});
    let requestUrl = '/rp/v1/average-quiz-attempts/';
    if ('duration_updated'==event.type) {
      requestUrl = '/rp/v1/average-quiz-attempts/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
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
 
  updateChart(requestUrl) {
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    this.setState({isLoaded:false, error:null, request_data:null});
    wp.apiFetch({
      path: requestUrl //Replace with the correct API
  }).then(response => {
    if(response.requestData) {
        this.setState({request_data:response.requestData})
      }
     wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', true);
     this.setState({hidden:false});
      this.plotChartTypeBy(response);
      }).catch((error) => {
        if(error.data && error.data.requestData) {
          this.setState({request_data:error.data.requestData})
        }
        // console.log(error);
        // console.log(this.state.error);
        this.setState({
          error:error,
          graph_summary:[],
          series:[],
          isLoaded: true,
        });
        // console.log(this.state.error);
        if ('invalid-input'==error.code) {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', false);  
          this.setState({hidden:true});
        } else {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', true);
          this.setState({hidden:false});
        }
        
      });
  }

  plotChartTypeBy(response){
    if (undefined!=response.quizwise_data) {
      const quizzes     = Object.values( response.quizwise_data ).map( obj => obj.title ).filter( obj => obj != null );
      const attempts   = Object.values( response.quizwise_data ).map( obj => parseFloat( obj.count ) ).filter( obj => obj != null );
      
      this.plotBarChart(quizzes, attempts, wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes'), __('Attempts', 'learndash-reports-by-wisdmlabs'));
      let summary_right = Array();
         
      summary_right.push(
        { title : __('Total ', 'learndash-reports-by-wisdmlabs') + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') + ': ',
          value : Object.keys( response.quizwise_data ).length,} );
      if (response.requestData.learner == null || response.requestData.learner.length==0) {
          summary_right.push(
            { title: __('Learners', 'learndash-reports-by-wisdmlabs') + ': ',
              value: response.student_count, });
      }

      summary_right.push(
        { title : __('Total Attempts: ', 'learndash-reports-by-wisdmlabs'),
          value : response.total_attempts,});

      
      this.setState({
        isLoaded:true,
        graph_summary: {
          left: [{
                title : __('AVG ATTEMPTS PER', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz'),
                value: '??'!=response.average_attempts?Number(parseFloat(response.average_attempts).toFixed(2)):response.average_attempts,
              },],

          right:summary_right,
          inner_help_text: __('Avg Quiz Attempts = Avg Attempts made to pass all quizzes/No. of Quizzes', 'learndash-reports-by-wisdmlabs'),
        },
      });
    }
    
  }


  plotBarChart(dataX, dataY, nameX, nameY) {
    if ( wisdm_ld_reports_common_script_data.is_rtl ) {
        dataX = dataX.reverse();
        dataY = dataY.reverse();
    }
    let chart_options = {
      chart: {
        type: 'bar',
        width: dataX.length*75 < 645 ? '100%' : dataX.length*75,
        height: 400,
        zoom:{
          enabled:false,
        },
        toolbar:{
          show:true,
          export: {
            csv: {
              filename: __( 'Avg Quiz Attempts.csv', 'learndash-reports-by-wisdmlabs' ),
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
        }
      },
      zoom: {
        enabled: true,
        type: 'x',
      },
      fill: {
        colors: [function({ value, seriesIndex, w }) {
          return '#444444'
        }],
      },
      dataLabels: {
        enabled: true,
        formatter: function (val) {
          return val;
        },
        offsetY: -25,
        style: {
          fontSize: '12px',
          colors: ["#304758"]
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 5,
          dataLabels: {
            enabled: true,
            position:'top',
          },
        }
      },
      xaxis: {
        title: {
          text: nameX
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
        max: Math.max.apply(Math, dataY)>0?Math.max.apply(Math, dataY) + Math.max.apply(Math, dataY)/10:10,
        axisBorder: {
          show: false
        },
        title: {
          text: nameY,
          offsetX: -20
        },
        opposite: wisdm_ld_reports_common_script_data.is_rtl,
        tickAmount: 5,
        min: 0,
        max: 100,
        labels: {
          show: true,
          formatter: (value) => { return value },
          align: wisdm_ld_reports_common_script_data.is_rtl?'right':'left',
          offsetX: 30
        },
        

      }
    };
    this.setState({graph_type:'bar', series:[{name:nameY, data:dataY}], options:chart_options});
  }

  render() {
    let body = <div></div>;
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
    // console.log(this.state.error);
    
    let graph = '';
    if (!this.state.error) {
      graph = <div className="app">
                <div className="row">
                  <div className="mixed-chart">
                    <Chart
                      options={this.state.options}
                      series={this.state.series}
                      type={this.state.graph_type}
                      width={undefined==this.state.options.chart?0:this.state.options.chart.width}
                      height={undefined==this.state.options.chart?0:this.state.options.chart.height}
                    />
                  </div>
                </div>
              </div>;
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}>
       <div class="wisdm-learndash-reports-average-quiz-attempts graph-card-container">
        <WisdmFilters request_data={this.state.request_data}/>
        <div class="chart-header average-quiz-attempts-chart-header">
          <div class="chart-title">
            <span>{this.state.chart_title}</span>
            <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          </div>
          <ChartSummarySection  wrapper_class='chart-summary-average-quiz-attempts' graph_summary={this.state.graph_summary} error={this.state.error}/>
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

export default AverageQuizAttempts;

document.addEventListener("DOMContentLoaded", function(event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-average-quiz-attempts front');
  if (elem.length>0) {
    ReactDOM.render(React.createElement(AverageQuizAttempts), elem[0]); 
  }
});

