import './index.scss';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmLoader from '../commons/loader/index.js';
import React, { Component } from "react";
import { __ } from '@wordpress/i18n';
import CourseCompletionTable from './component-course-completion-table.js';
import DurationFilter from './component-duration-filter.js';
import LocalFilters from './component-local-filters.js';
import CompletionRateModal from './component-completion-rate-modal.js';
import Modal, {closeStyle} from 'simple-react-modal';

class CourseCompletionRate extends Component {
  constructor(props) {
    super(props);
      let error=null;
      // if(null==this.getUserType()) {
      //   error = {message:__( 'Sorry you are not allowed to access this block, please check if you have proper access permissions','learndash-reports-by-wisdmlabs')}
      // }
      this.state = {
        isLoaded: false,
        error: error,
        moreDataLoading:false,
        reportTypeInUse: wisdm_learndash_reports_front_end_script_course_completion_rate.report_type,
        chart_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Course')+ ' ' + __( 'Completion Rate', 'learndash-reports-by-wisdmlabs' ),
        lock_icon:'',
        request_data:null,        
        tableData:{},
        group:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        groups:[],
        page: 1,
        category:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        categories:[],
        graph_summary: [],
        duration: {value: 'all', label: __('All time', 'learndash-reports-by-wisdmlabs')},
        help_text:__( 'This report displays the percentage of learners who have completed a course.','learndash-reports-by-wisdmlabs' ),
        course_report_type: null,
        show_supporting_text: false,
        sort: 'DESC',
        sort_inner: 'DESC',
        show_completion_rate_modal:false,
      };      

      if (false==wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
        this.upgdare_to_pro = 'wisdm-ld-reports-upgrade-to-pro-front';
        this.lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
      } 

      this.applyFilters           = this.applyFilters.bind(this);
      this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
      this.showDummyImages        = this.showDummyImages.bind(this);
      this.updateLocalDuration    = this.updateLocalDuration.bind(this);
      this.updateSorting    = this.updateSorting.bind(this);
      this.updateSortingAsync    = this.updateSortingAsync.bind(this);
      this.updateLocalGroup       = this.updateLocalGroup.bind(this);
      this.updateLocalCategory    = this.updateLocalCategory.bind(this);
      // // this.updateCompletionAverage    = this.updateCompletionAverage.bind(this);
      this.addMoreData            = this.addMoreData.bind(this);
      this.openCompletionRateModal  = this.openCompletionRateModal.bind(this);
      this.closeCompletionRateModal = this.closeCompletionRateModal.bind(this);
      this.startCSVDownload = this.startCSVDownload.bind(this);
      this.defaultFiltersLoaded = this.defaultFiltersLoaded.bind(this);
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
      document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
      document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
      document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
      document.addEventListener('local_completion_duration_change', this.updateLocalDuration);
      document.addEventListener('local_group_change_completion', this.updateLocalGroup);
      document.addEventListener('local_category_change_completion', this.updateLocalCategory);
      document.addEventListener('local_sort_change_completion', this.updateSorting);
      document.addEventListener('local_sort_change_completion_modal', this.updateSortingAsync);
      // document.addEventListener('refresh-course-completion-average', this.updateCompletionAverage);
      document.addEventListener('start_csv_download_cc', this.startCSVDownload);
      document.addEventListener('wrld-default-filters-loaded', this.defaultFiltersLoaded);
      this.updateChart('/rp/v1/course-completion-rate?duration=' + this.state.duration.value);
    }

    defaultFiltersLoaded() {
        let groups      = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        let categories  = wisdm_learndash_reports_front_end_script_report_filters.course_categories;
        // if ( groups.length > 0 ) {
        //   groups.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
        // }
        if ( categories.length > 0 ) {
          categories.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
        }
        // this.setState(
        //         {
        //           graph_summary: [],
        //           isLoaded: true,
        //           groups:groups,
        //           categories:categories,
        //       }); 
        let state_updated = Object.assign({}, this.state);
        state_updated.isLoaded = true;
        state_updated.groups = groups;
        state_updated.categories = categories;
        this.setState(state_updated);
        // console.log(test);
        // console.log(this.state.graph_summary);
        //Patch logic for react state updaete on browser refresh bug.
        const groupsLoadEvent = new CustomEvent("completion-parent-groups-changed", {
          "detail": {"value": groups }
        });
        const categoryLoadEvent = new CustomEvent("completion-parent-category-changed", {
          "detail": {"value": categories }
        });
        const sortLoadEvent = new CustomEvent("completion-parent-sort-changed", {
          "detail": {"value": 'DESC' }
        });
        document.dispatchEvent(groupsLoadEvent);
        document.dispatchEvent(categoryLoadEvent);
        document.dispatchEvent(sortLoadEvent);
    }

    openCompletionRateModal() {
        document.body.classList.add('wrld-open');
        this.setState({
            show_completion_rate_modal:true,
        });
    }

    closeCompletionRateModal(){
        document.body.classList.remove('wrld-open');
        this.setState({
            show_completion_rate_modal: false,
            moreDataLoading:false
        });
        this.setState({table_data: []});
    }

    showDetailsModal() {
        jQuery('.button-completion-rate').click();
    }

    detailsModal(sort=false) {
        var self = this;
        if ( ! sort ) {
            var requestUrl = '/rp/v1/course-completion-rate/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&page=' + 'all' + '&sort=' + this.state.sort_inner;
        } else {
            var requestUrl = '/rp/v1/course-completion-rate/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&page=' + 'all' + '&sort=' + sort;
        }
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        // this.setState({selected_data_point: data_point, table_data: [], more: 'yes'});
        wp.apiFetch({
            path: requestUrl //Replace with the correct API
        }).then(response => {
            var table = response.tableData;
            if (undefined==response) {
                table = [];
            }
            this.setState({table_data: table, moreDataLoading:false, error: null});
            self.showDetailsModal();
        }).catch((error) => {
            if(error.data && error.data.requestData) {
                self.setState({request_data:error.data.requestData,moreDataLoading:false})
            }
            self.setState({
                error:error,
                moreDataLoading:false,
            });
        });
    }

    addMoreData(evnt) {
        // let next = this.state.page + 1;
        // this.setState({page:next});
        // let request_url = '/rp/v1/course-completion-rate/?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value + '&page=' + 'all' + '&sort=' + this.state.sort;
        // this.updateChart(request_url, true);
        this.setState({moreDataLoading : true,});
        this.detailsModal();
    }

    updateLocalGroup(evnt) {        
        this.setState({ group:evnt.detail.value, page: 1, category: {value:null,label:__('All', 'learndash-reports-by-wisdmlabs')} });
        let request_url = '/rp/v1/course-completion-rate?duration=' + this.state.duration.value + '&group=' + evnt.detail.value.value + '&category=' + null + '&sort=' + this.state.sort + '&page=1';
        this.updateChart(request_url);
    }

    startCSVDownload(evnt) {
        var requestUrl = '/rp/v1/course-completion-rate-csv/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&page=' + 'all';
        // this.setState({selected_data_point: data_point, table_data: [], more: 'yes'});
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        wp.apiFetch({
            path: requestUrl //Replace with the correct API
        }).then(response => {
            window.location = response.filename;
            // jQuery('<a href="' + response.filename + '" download class="wrld-hidden"></a>').appendTo('body').trigger('click');
        }).catch((error) => {
            if(error.data && error.data.requestData) {
                self.setState({request_data:error.data.requestData,moreDataLoading:false})
            }
            self.setState({
                error:error,
                moreDataLoading:false,
            });
        });
    }

    updateSorting(evnt) {
        this.setState({ sort:evnt.detail.value, page: 1});
        let request_url = '/rp/v1/course-completion-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value + '&sort=' + evnt.detail.value + '&page=1';
        this.updateChart(request_url);   
    }

    updateSortingAsync(evnt) {
        this.setState({ sort_inner:evnt.detail.value, table_data: [] });
        this.setState({moreDataLoading : true});
        this.detailsModal(evnt.detail.value);
        // let request_url = '/rp/v1/course-completion-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value + '&sort=' + evnt.detail.value + '&page=all';
        // this.updateChart(request_url);   
    }

    // updateCompletionAverage(evnt) {
    //    wp.apiFetch({
    //      path: evnt.detail.url //Replace with the correct API
    //   }).then(response => {
    //      let avgTimeSpent = response.averageCourseCompletion;
    //      this.setState({
    //          error: null,
    //          isLoaded:true,
    //          graph_summary: {
    //            left: [{
    //                  title : __('AVG', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + __('COMPLETION', 'learndash-reports-by-wisdmlabs'),
    //                  value: '??'!=avgTimeSpent?Number(parseFloat(avgTimeSpent).toFixed(2)) + '%':avgTimeSpent,
    //                }],
    //            right:[],
    //            last: response.updated,
    //            refresh_url: '/rp/v1/course-completion-average?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value,
    //            rotate: false,
    //          }
    //      });
    //   });
    // }

    updateLocalCategory(evnt) {
        this.setState({ category:evnt.detail.value, page: 1, group: {value:null,label:__('All', 'learndash-reports-by-wisdmlabs')} });
        let request_url = '/rp/v1/course-completion-rate?duration=' + this.state.duration.value + '&group=' + null + '&category=' + evnt.detail.value.value + '&sort=' + this.state.sort + '&page=1';
        this.updateChart(request_url);
    }

    updateLocalDuration(evnt) {
        this.setState({duration:evnt.detail.value, page: 1});
        let request_url = '/rp/v1/course-completion-rate?duration=' + evnt.detail.value.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&sort=' + this.state.sort + '&page=1';
        this.updateChart(request_url);
    }

    componentDidUpdate() {
      jQuery('.CourseCompletionRate .mixed-chart').prepend(jQuery('.CourseCompletionRate .apexcharts-toolbar'));
      jQuery( ".wisdm-learndash-reports-course-completion-rate .chart-title .dashicons.dashicons-info-outline, .wisdm-learndash-reports-course-completion-rate .chart-summary-revenue-figure .dashicons.dashicons-info-outline" ).hover(

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
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-completion-rate', false);
        } else {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-completion-rate', true);
        }
    }

    showDummyImages(event){
      this.setState({course_report_type:event.detail.report_type})
    }


    applyFilters(event) {
      let category   = event.detail.selected_categories;
      let group      = event.detail.selected_groups;
      let course     = event.detail.selected_courses;
      let lesson     = event.detail.selected_lessons;
      let topic      = event.detail.selected_topics;
      let learner    = event.detail.selected_learners;
   
      let request_url = '/rp/v1/course-completion-rate/?duration=' + this.state.duration.value + '&category=' + category + '&group=' + group + '&page=' + this.state.page;
   
    // if ( undefined != course ) {
    //   this.setState({show_supporting_text: true});
    // } else {
    //   this.setState({show_supporting_text: false});
    // }

      //Time spent on a course chart should not display for lesson/topic
    if (undefined==topic && undefined==lesson && undefined==course) {
      this.updateChart(request_url);
      this.setState({reportTypeInUse:'default-ld-reports'});
      this.setState({group: event.detail.selected_groups_obj,category: event.detail.selected_categories_obj,});
      wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-completion-rate' , true );
    } else {
        //hide this block.
        this.setState({reportTypeInUse:'default-ld-reports-lesson-topic'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-completion-rate' , false );
    }
    if ( undefined != learner || this.state.course_report_type == 'learner-specific-course-reports' ) {
        this.setState({reportTypeInUse:'default-ld-learner-reports'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-completion-rate' , false );
    }
  }

    updateChart(requestUrl, is_paginated=false) {
        if ( ! is_paginated ) {
            this.setState({
                isLoaded: false,
            });
        }
        var self = this;
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
            // console.log(response);
             if(response.requestData) {
               self.setState({request_data:response.requestData})
             }
             if ( response.updated_on ) {
              self.setState({updated_on: response.updated_on});
            }
             self.showCompletionChart(response, is_paginated);
              window.callStack.pop();
             }).catch((error) => {
                window.callStack.pop();

               if(error.data && error.data.requestData) {
                 self.setState({request_data:error.data.requestData})
               }
               // self.setState({
               //      error:error,
               //      graph_summary:[],
               //      isLoaded: true,
               // });
               let state = Object.assign({}, self.state);
               state.error = error;
               state.graph_summary = [];
               state.isLoaded = true;
               self.setState(state);
             });
          }
        }, 500);
      };
      checkIfEmpty();
      }

    showCompletionChart( response, is_paginated ) {
        var table = response.tableData;
        if (undefined==response) {
            table = [];
        }
        if ( is_paginated ) {
            table = {
                ...this.state.tableData,
                ...table
            };
            // table = this.state.tableData.concat(table);
        }
        let avgTimeSpent = response.averageCourseCompletion;
        if ( !wisdm_ld_reports_common_script_data.is_pro_version_active ) {
            avgTimeSpent = '??';
        }
        this.setState({
            tableData: table,
            error: null,
            isLoaded:true,
            more: response.more_data,
            graph_summary: {
              left: [{
                    title : __('AVG', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + __('COMPLETION', 'learndash-reports-by-wisdmlabs'),
                    value: '??'!=avgTimeSpent?Number(parseFloat(avgTimeSpent).toFixed(2)) + '%':avgTimeSpent,
                  }],
              right:[],
              last: response.updated,
              refresh_url: '/rp/v1/course-completion-average?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value,
              rotate: false
            }
        });
    }

    refreshUpdateTime() {
      this.setState({isLoaded: false});
      let requestUrl = '/rp/v1/course-completion-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + this.state.category.value + '&disable_cache=true';
      this.updateChart(requestUrl);
  }

  render() {
    // console.log(this.state.graph_summary);
    let body = <div></div>;
    if(''!=this.state.reportTypeInUse && 'default-ld-reports'!=this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader text={this.state.show_supporting_text}/>;
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div className="CourseCompletionRate">
                <div className="row">
                  <div className="mixed-chart">
                    <CourseCompletionTable tableData={this.state.tableData} sort={this.state.sort}/>
                    {
                        ( 'yes' == this.state.more ) ? <span className="load-more-ajax" onClick={ this.state.moreDataLoading ? ()=>{} : this.addMoreData}>{this.state.moreDataLoading ? <div className='wrld-ccr-more-data-loader'><img src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/loader.svg'}>
                        </img></div> : __( 'Show All Courses', 'learndash-reports-by-wisdmlabs' )} </span> : <span></span>
                    }
                  </div>
                </div>
              </div>;
    }
    this.state.pro_link = 'https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=course-completion-rate&utm_campaign=upgradetopro#pricing';
    if ( this.state.course_report_type == 'learner-specific-course-reports' ) {
      this.state.pro_link = 'https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=learner-course-completion-rate&utm_campaign=upgradetopro#pricing';
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block"}>
    <div class="wisdm-learndash-reports-course-completion-rate graph-card-container">
    <div class="chart-header course-completion-rate-chart-header">
        <div class="chart-title">
          <span>{this.state.chart_title}</span>
          <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          <DurationFilter pro_upgrade_option={this.upgdare_to_pro} wrapper_class='chart-summary-inactive-users' duration={this.state.duration} />
        </div>
        <div className="chart_update_time"><span>{__( 'Last updated: ', 'learndash-reports-by-wisdmlabs' )}</span><span>{this.state.updated_on}</span><div className='chart-refresh-icon'><span class="dashicons dashicons-image-rotate" data-title={__('Click this to refresh the chart', 'learndash-reports-by-wisdmlabs')} onClick={this.refreshUpdateTime.bind(this)}></span></div></div>
        <LocalFilters group={this.state.group} category={this.state.category} groups={this.state.groups} categories={this.state.categories} />
        <ChartSummarySection pro_upgrade_option={this.upgdare_to_pro} wrapper_class='chart-summary-course-completion-rate' graph_summary={this.state.graph_summary} error={this.state.error} pro_link={this.state.pro_link} />
        {/*<SummarySection />*/}
      </div>
      <div>
        {graph}
      </div>
    </div>
    <Modal show={this.state.show_completion_rate_modal}
            onClose={this.closeCompletionRateModal}
            containerStyle={{width:'50%'}}
            className={"completion_rate_modal"}
            >
            <span className="close-modal dashicons dashicons-no" onClick={this.closeCompletionRateModal}></span>
            <CompletionRateModal loading={this.state.moreDataLoading} table={this.state.table_data} group={this.state.group.label} category={this.state.category.label} sort={this.state.sort_inner}/>
    </Modal>
    <button className="button-completion-rate wrld-hidden" onClick={this.openCompletionRateModal}></button>
  </div>;
  }
    return (body);
  }
}

export default CourseCompletionRate;

document.addEventListener("DOMContentLoaded", function(event) {
  
  let elem = document.getElementsByClassName('wisdm-learndash-reports-course-completion-rate front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(CourseCompletionRate), elem[0]); 
    }
    
});

