import './index.scss';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmFilters from '../commons/filters/index.js';
import WisdmLoader from '../commons/loader/index.js';
import React, { Component } from "react";
import { __ } from '@wordpress/i18n';
import Chart from "react-apexcharts";
import moment from 'moment';


class RevenueFromCourses extends Component {
  constructor(props) {
    super(props);
    this.state = {
      isLoaded: false,
      error: null,
      reportTypeInUse: wisdm_learndash_reports_front_end_script_revenue_from_courses.report_type,
      options:[],
      series:[],
      graph_summary:[],
      request_data:null,
      start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      chart_title:__('Revenue From', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses'),
      help_text:__('This Report displays the Revenue earned Course-wise for the selected date range.','learndash-reports-by-wisdmlabs'),
    };

    this.durationUpdated        = this.durationUpdated.bind(this);
    this.applyFilters           = this.applyFilters.bind(this);
    this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
  }

  isValidGraphData() {
    if (undefined==this.state.options || 0==this.state.options.length) {
      return false;
    } 
    if (undefined==this.state.series || undefined==this.state.series[0]) {
      return false;
    }
    if (undefined==this.state.series[0].data || 0==this.state.series[0].data.length) {
      return false;
    }
    return true;
  }

  componentDidMount() {
    this.updateChart('/rp/v1/revenue-from-courses/?start_date=' + this.state.start_date + '&&end_date=' + this.state.end_date);  
    document.addEventListener('duration_updated', this.durationUpdated);
    document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
  }
  componentDidUpdate() {
    jQuery('.revenue-from-courses .mixed-chart').prepend(jQuery('.revenue-from-courses .apexcharts-toolbar'));
    jQuery( ".wisdm-learndash-reports-revenue-from-courses .chart-title .dashicons" ).hover(

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
  }

  applyFilters(event) {
    let start_date = event.detail.start_date;
    let end_date   = event.detail.end_date;
    let category   = event.detail.selected_categories;
    let course     = event.detail.selected_courses;
    let lesson     = event.detail.selected_lessons;
    let topic      = event.detail.selected_topics;
    let learner    = event.detail.selected_learners;
 
    let request_url = '/rp/v1/revenue-from-courses/?start_date=' + start_date + '&end_date=' + end_date;
 
    this.updateChart(request_url)
   
   }


  durationUpdated(event) {
    this.setState({isLoaded: false, start_date:event.detail.startDate, end_date:event.detail.endDate});
    let requestUrl = '/rp/v1/revenue-from-courses/?start_date=' + this.state.start_date + '&&end_date=' + this.state.end_date;
    if ( 'duration_updated' == event.type ) {
      requestUrl = '/rp/v1/revenue-from-courses/?start_date=' + event.detail.startDate + '&&end_date=' + event.detail.endDate;
    } 
    this.updateChart(requestUrl);
  }

  updateChart(requestUrl) {
    wisdm_reports_change_block_visibility('.wisdm-learndash-reports-revenue-from-courses', true, '.wp-block-wisdm-learndash-reports-revenue-from-courses');
    this.setState({isLoaded:false, error:null, request_data:null});
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl //Replace with the correct API
   }).then(response => {
        var revenue = 0;
        var previousRevenue = 0;
        var courses = [];
        let formattedRevenueData = wisdm_reports_format_course_revenue_response(response.currentRevenueEarned, response.previousRevenueEarned);
        if(response.requestData) {
          this.setState({request_data:response.requestData})
        }
        if (undefined!=formattedRevenueData['titles'] && formattedRevenueData['titles'].length>0) {
          courses = formattedRevenueData['titles'];
          previousRevenue= formattedRevenueData['past_revenues'];
          revenue = formattedRevenueData['current_revenues'];
        }

        let currency = response.currency;
        currency     = currency.length>1?currency+' ':currency;
        let dataNotFoundText = '';
        if (0==response.currentRevenueEarned.length && 0==response.previousRevenueEarned.length) {
          revenue = [];
          previousRevenue = [];
          courses = [];
          dataNotFoundText = __( 'Revenue data not found', 'learndash-reports-by-wisdmlabs' )
        }
        if ( wisdm_ld_reports_common_script_data.is_rtl ) {
          previousRevenue = previousRevenue.reverse();
          // dataY = dataY.reverse();
        }
        this.setState(
                {
                isLoaded: true,
                series: [{
                  name: __('Current', 'learndash-reports-by-wisdmlabs'),
                  type: 'area',
                  data: revenue,
                  style: {
                    direction: wisdm_ld_reports_common_script_data.is_rtl?'rtl':'ltr',
                  }
                }, {
                  name: __('Past', 'learndash-reports-by-wisdmlabs'),
                  type: 'line',
                  data: previousRevenue,
                }],
                options: {
                  noData: {
                    text: dataNotFoundText,
                    align: 'center',
                    verticalAlign: 'middle',
                  },
                  chart:{
                    height: 400,
                    // width:revenue.length*55 < 645 ? '100%' : revenue.length*55,
                    zoom:{
                      enabled:false,
                    },
                    toolbar:{
                      export: {
                        csv: {
                          filename: __( 'Revenue From Courses.csv', 'learndash-reports-by-wisdmlabs' ),
                          columnDelimiter: ',',
                          headerCategory: wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses'),
                          headerValue: __( 'Revenue per', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('course'),
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
                  dataLabels: {
                    enabled: false
                  },
                  stroke: {
                    curve: 'smooth',
                    width:1,
                  },
                  fill: {
                    type: "gradient",
                    gradient: {
                      shadeIntensity: 1,
                      opacityFrom: 0.7,
                      opacityTo: 0.9,
                      stops: [0, 90, 100]
                    }
                  },
                  colors: ['#2E93fA', '#080808'],
                  xaxis: {
                    tooltip: {
                      enabled: false,
                    },
                    labels:{
                      hideOverlappingLabels: false,
                      trim: true,
                      rotate: wisdm_ld_reports_common_script_data.is_rtl ? 45 : -45,
                    },
                    categories: courses,
                    title: {
                      text: wisdm_reports_get_ld_custom_lebel_if_avaiable('courses'),
                    },
                  },
                  yaxis: {
                    axisBorder: {
                      show: ! wisdm_ld_reports_common_script_data.is_rtl,
                    },
                    title: {
                      text: __( 'Revenue Course-wise.', 'learndash-reports-by-wisdmlabs' ),
                      offsetX: wisdm_ld_reports_common_script_data.is_rtl ? -80 : 0,
                    },
                    labels: {
                      align: wisdm_ld_reports_common_script_data.is_rtl ? 'right' : 'left',
                      offsetX: wisdm_ld_reports_common_script_data.is_rtl ? 25 : 0,
                    },
                    opposite: wisdm_ld_reports_common_script_data.is_rtl,
                  },
                  tooltip: {
                    x: {
                      format: 'dd/MM/yy HH:mm'
                    },
                  },
                },
                graph_summary: {
                  left: [{
                        title : __('AVG REVENUE PER', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('course').toUpperCase(),
                        value: currency + response.averageRevenue ,
                      },],
                      
                  right:[{
                      title : __('Total Revenue: ', 'learndash-reports-by-wisdmlabs'),
                      value: currency + response.totalRevenue  ,
                    }, 
                    {
                      title: __('Total ', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('courses') + ': ',
                      value:response.totalCourses,
                    }],
                },
              }); 
      }).catch((error) => {
        if(error.data && error.data.requestData) {
          this.setState({request_data:error.data.requestData})
        }
        if ('rest_forbidden'==error.code) {
          wisdm_reports_change_block_visibility('.wisdm-learndash-reports-revenue-from-courses', false, '.wp-block-wisdm-learndash-reports-revenue-from-courses');
        }
        this.setState({
          error:error,
              graph_summary:[],
          isLoaded: true,
          series:[],
        });
      });
  }

  render() {
    let body = <div></div>;
    let data_validation = '';
    if (!this.isValidGraphData()) {
      data_validation = 'invalid-or-empty-data';
    }
    if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader />;
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div class="revenue-from-courses">
                <div class="mixed-chart">
                  <Chart options={this.state.options} series={this.state.series} width={this.state.options.chart.width} height={this.state.options.chart.height} type="area"/>
                </div>
              </div>;
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}>
      <div class="wisdm-learndash-reports-revenue-from-courses graph-card-container">
        <WisdmFilters request_data={this.state.request_data}/>
        <div class="chart-header revenue-from-courses-chart-header">
          <div class="chart-title">
            <span>{this.state.chart_title}</span>
            <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          </div>
          <ChartSummarySection wrapper_class='chart-summary-revenue-from-courses' graph_summary={this.state.graph_summary} error={this.state.error}/>
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

export default RevenueFromCourses;

document.addEventListener("DOMContentLoaded", function(event) {
  let elem = document.getElementsByClassName('wisdm-learndash-reports-revenue-from-courses front');
    if (elem.length>0) {
        ReactDOM.render(React.createElement(RevenueFromCourses), elem[0]); 
    }
});

