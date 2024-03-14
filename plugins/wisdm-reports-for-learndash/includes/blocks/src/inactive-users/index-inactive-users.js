import './index.scss';
import WisdmFilters from '../commons/filters/index.js';
import DurationFilter from './component-duration-filter.js';
import LocalFilters from './component-local-filters.js';
import WisdmLoader from '../commons/loader/index.js';
import DummyReports from '../commons/dummy-reports/index.js';
import React, { Component } from "react";
import { __ } from '@wordpress/i18n';
import { useTable, usePagination } from "react-table";

// var ld_api_settings = wisdm_learndash_reports_front_end_script_report_filters.ld_api_settings;

/**
 * Based on the current user roles aray this function desides wether a user is a group
 * leader or an Administrator and returns the same.
 */
function wisdmLdReportsGetUserType() {
    let userRoles = wisdm_learndash_reports_front_end_script_report_filters.user_roles;
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
    } else if (userRoles.includes('wdm_instructor')) {
        return 'instructor';
    }
    return null;
}

function getCoursesByGroups(courseList) {
    let user_type = wisdmLdReportsGetUserType();
    let filtered_courses = [];
    if('group_leader'==user_type) {
        let course_groups = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        let group_course_list = [];
        if (course_groups.length>0) {
            course_groups.forEach(function(course_group){
                if ( ! ( 'courses_enrolled' in course_group ) ) {
                    return;
                }
                let courses = course_group.courses_enrolled;
                courses.forEach(function(course_id){
                    if(!group_course_list.includes(course_id)) {
                        group_course_list.push(course_id);
                    }
                });
            });    
        }
        
        if (group_course_list.length>0) {
            courseList.forEach(function(course){
                if (group_course_list.includes(course.value)) {
                    filtered_courses.push(course);
                }
            });    
        } 
    } else if('instructor'==user_type){
        filtered_courses = wisdm_learndash_reports_front_end_script_report_filters.courses;
    } else {
        filtered_courses = courseList;
    }

    if('administrator' != user_type)
    {
      if ( filtered_courses.length > 0 ) {
        filtered_courses.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
      }
    }
    return filtered_courses;
}

function loadInactiveUsers(event) {
    const durationEvent = new CustomEvent("load_next_page_inactive_users", {
        "detail": {"value": event }
      });
    document.dispatchEvent(durationEvent);
}

function Table({ columns, data }) {
  // Use the state and functions returned from useTable to build your UI
  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    prepareRow,
    page, // Instead of using 'rows', we'll use page,
    // which has only the rows for the active page

    // The rest of these things are super handy, too ;)
    canPreviousPage,
    canNextPage,
    pageOptions,
    pageCount,
    gotoPage,
    nextPage,
    previousPage,
    setPageSize,
    state: { pageIndex, pageSize }} = useTable({columns, data, initialState: { pageIndex: 0, pageSize: 5000 }},
    usePagination
  );
 
  //tooltip message configuration 
  let tooltip_text = "";
  let icon_enabled =false;

  // Render the UI for your table
  return (
    <>
    <div className="course-reports-wrapper">
    <div className="inactive-user-table-wrap">
    <table class="course-list-table" {...getTableProps()}>
      <thead>
         {headerGroups.map(headerGroup => (
             <tr {...headerGroup.getHeaderGroupProps()}>
               {headerGroup.headers.map(column => (
                   <th
                       {...column.getHeaderProps()}
                       className={column.className}
                   >
                     {column.render('Header')}
                   </th>
               ))}
             </tr>
         ))}
      </thead>
        <tbody {...getTableBodyProps()}>
          {page.map((row, i) => {
            prepareRow(row);
            return (
              <tr class="course-list-table-data-row"{...row.getRowProps()}>
                {row.cells.map((cell) => {
                  return (
                    <td className={cell.column.className} {...cell.getCellProps()}>{cell.render("Cell")}</td>
                  );
                })}
              </tr>
            );
          })}
        </tbody>
      </table>
      </div>
      {/* 
        Pagination can be built however you'd like. 
        This is just a very basic UI implementation:
      */}
        {/*<button onClick={() => gotoPage(0)} disabled={!canPreviousPage}>
          {"<<"}
        </button>{" "}
        <button onClick={() => previousPage()} disabled={!canPreviousPage}>
          {"<"}
        </button>{" "}
        <span>
          {__('Page', 'learndash-reports-by-wisdmlabs') + " "}
          <strong>
            {pageIndex + 1}  {' ' + __('Of', 'learndash-reports-by-wisdmlabs') + ' ' }  {pageOptions.length}
          </strong>{" "}
        </span>
        <button onClick={() => nextPage()} disabled={!canNextPage}>
          {">"}
        </button>{" "}
        <button 78 6onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}>
          {">>"}
        </button>{" "}*/}
          {/*<button onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}>
              {">>"}
            </button>{" "}*/}
        {/*{ canNextPage &&
            <span className="load-more-ajax" onClick={loadInactiveUsers}>{__( 'View More', 'learndash-reports-by-wisdmlabs' )}</span>
        }*/}
      </div>
    </>
  );
}

class InactiveUsers extends Component {
  constructor(props) {
    super(props);
      let error=null;
      if(null==this.getUserType()) {
        error = {message:__('Sorry you are not allowed to access this block, please check if you have proper access permissions','learndash-reports-by-wisdmlabs')}
      }
      this.state = {
        isLoaded: false,
        error: error,
        reportTypeInUse: wisdm_learndash_reports_front_end_script_inactive_users.report_type,
        duration: {value: '30 days', label: __('Last 30 days', 'learndash-reports-by-wisdmlabs')},
        page: 1,
        group:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        course:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        courses:[],
        groups:[],
        chart_title: __('Inactive Users List', 'learndash-reports-by-wisdmlabs'),
        lock_icon:'',
        request_data:null,        
        help_text:__('This report displays the inactive users on the website. Note: The users who are created from the backend and enrolled in a course, but never visited the platform will not be displayed in the table.','learndash-reports-by-wisdmlabs'),
        course_report_type: null,
        tableHeaders:[],
        tableData:[],
        show_supporting_text: false,
      };

      if (false==wisdm_learndash_reports_front_end_script_inactive_users.is_pro_version_active) {
        this.upgdare_to_pro = 'wisdm-ld-reports-upgrade-to-pro-front';
        this.lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
      } 

      this.applyFilters           = this.applyFilters.bind(this);
      this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
      this.showDummyImages        = this.showDummyImages.bind(this);
      this.updateLocalDuration    = this.updateLocalDuration.bind(this);
      this.updateLocalGroup       = this.updateLocalGroup.bind(this);
      this.updateLocalCourse      = this.updateLocalCourse.bind(this);
      this.addMoreData            = this.addMoreData.bind(this);
      this.defaultFiltersLoaded            = this.defaultFiltersLoaded.bind(this);
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
      // this.updateChart('/rp/v1/inactive-users?duration=' + this.state.duration.value);
      this.getCourseListStateData( '/rp/v1/inactive-users?duration=' + this.state.duration.value );
      document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
      document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
      document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
      document.addEventListener('local_duration_change', this.updateLocalDuration);
      document.addEventListener('local_group_change', this.updateLocalGroup);
      document.addEventListener('local_course_change', this.updateLocalCourse);
      document.addEventListener('load_next_page_inactive_users', this.addMoreData);
      document.addEventListener('wrld-default-filters-loaded', this.defaultFiltersLoaded);
    }

    defaultFiltersLoaded() {
      /*  let url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?per_page=-1';
      if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active ) {
          for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
              url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
          }
      }*/
      /*wp.apiFetch({
          path: url  //Replace with the correct API
      }).then(response => {*/
        let courses     = [{value: null, label:__('All', 'learndash-reports-by-wisdmlabs')}];
        courses     = courses.concat( wisdm_learndash_reports_front_end_script_report_filters.courses );
        let groups      = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        if ( groups.length > 0 ) {
          groups.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
        }
        
        this.setState(
                {
                  isLoaded: true,
                  groups:groups,
                  courses:courses,
              }); 
        //Patch logic for react state updaete on browser refresh bug.
        const groupsLoadEvent = new CustomEvent("wisdm-ld-reports-parent-groups-changed", {
          "detail": {"value": groups }
        });
        document.dispatchEvent(groupsLoadEvent);
      // });
    }

    updateLocalDuration(evnt) {
        this.setState({duration:evnt.detail.value, page: 1});
        let request_url = '/rp/v1/inactive-users/?duration=' + evnt.detail.value.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&page=1';
        this.getCourseListStateData(request_url);
    }
    updateLocalGroup(evnt) {
        if (null==evnt.detail.value.value) {
            this.setState({ group:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, page: 1 });
            this.updateSelectorsFor('group', null, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?test=1');
        } else {
            this.setState({ group:evnt.detail.value, page: 1 });
            let courses_enrolled = 9999999999999;
            if ( evnt.detail.value.courses_enrolled.length > 0 ) {
                courses_enrolled = evnt.detail.value.courses_enrolled;
            }
            this.updateSelectorsFor('group', evnt.detail.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + courses_enrolled);
        }
        let request_url = '/rp/v1/inactive-users/?duration=' + this.state.duration.value + '&group=' + evnt.detail.value.value + '&course=' + this.state.course.value + '&page=1';
        this.getCourseListStateData(request_url);
    }
    updateLocalCourse(evnt) {
        if (null==evnt.detail.value) {
            this.setState({ course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, page: 1 });
        } else {
      
            this.setState({ course:evnt.detail.value, page: 1 });
        }
        let request_url = '/rp/v1/inactive-users/?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&course=' + evnt.detail.value.value + '&page=1';
        this.getCourseListStateData(request_url);
    }

    addMoreData(evnt) {
        let next = this.state.page + 1;
        this.setState({page:next});
        let request_url = '/rp/v1/inactive-users/?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&page=' + next;
        this.getCourseListStateData(request_url, true);
    }

    componentDidUpdate() {
      jQuery( ".wisdm-learndash-reports-inactive-users .chart-title .dashicons, .wisdm-learndash-reports-inactive-users .chart-summary-revenue-figure .dashicons" ).hover(

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
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-inactive-users', false);
        } else {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-inactive-users', true);
        }
    }

    showDummyImages(event){
      this.setState({course_report_type:event.detail.report_type})
      if ( 'learner-specific-course-reports' === event.detail.report_type ) {
        jQuery('.wisdm-learndash-reports-inactive-users').parent().hide();
      } else {
        jQuery('.wisdm-learndash-reports-inactive-users').parent().show();
      }
    }


    applyFilters(event) {
      if ( 'learner-specific-course-reports' === this.state.course_report_type ) {
        jQuery('.wisdm-learndash-reports-inactive-users').parent().hide();
      }

      let group      = event.detail.selected_groups;
      let course     = event.detail.selected_courses;
      let request_url = '/rp/v1/inactive-users/?duration=' + this.state.duration.value + '&group=' + group + '&course=' + course + '&page=' + this.state.page;
      if ( undefined != course ) {
        this.setState({show_supporting_text: true});
      } else {
        this.setState({show_supporting_text: false});
      }
      this.getCourseListStateData(request_url);

      let course_label = this.state.courses.find(o => o.value === course);
      let group_label = this.state.groups.find(o => o.value === group);

      this.setState({course:course_label,group:group_label});
    
    }

    getCourseListStateData(request_url='/rp/v1/inactive-users', is_paginated=false) {
        if ( ! is_paginated ) {
          this.setState({
            isLoaded: false,
          });
        }
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          request_url += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        wp.apiFetch({
            path: request_url,
         }).then(response => {
              var table = response.table;
             if (undefined==response) {
               table = [];
             }
             if ( is_paginated ) {
               table = this.state.tableData.concat(table);
             }
              this.setState(
                      {
                      isLoaded: true,
                      error:null,
                      isProVersion:wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active,
                      tableHeaders: this.getTableHeadersByType(table),
                      tableData: table,
                      request_data: response.requestData,
                      more: response.more_data
                    }); 
            }).catch((error) => {
              if(error.data && error.data.requestData) {
                 this.setState({request_data:error.data.requestData})
               }
              this.setState({
                error:error,
                isLoaded: true,
              });
            });
    }

    getCourseListFromJson(response) {
        let courseList = [];
        if (response.length==0) {
            return courseList; //no courses found    
        }
        
        for (let i = 0; i < response.length; i++) {
             courseList.push({value:response[i].id, label:response[i].title.rendered});
        }
        courseList = getCoursesByGroups(courseList);   
        return courseList;
    }

    updateSelectorsFor(element, selection, callback_path='/wp/v2/categories/') {
        switch (element) {
            case 'group':
                callback_path = callback_path+'&per_page=-1';
                if (null==selection) {
                    // this.setState(
                    //     {
                    //     course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                    // });
                    // wp.apiFetch({
                    //     path: callback_path //Replace with the correct API
                    //  }).then(response => {
                    //     let courses = this.getCourseListFromJson(response);
                        let courses = wisdm_learndash_reports_front_end_script_report_filters.courses;
                        if (false!=courses && courses.length>0) {
                            this.setState(
                                {
                                courses:courses,
                                courses_disabled:false, 
                                loading_courses:false,
                                course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            });

                        }else{
                            this.setState(
                                {
                                courses:[],
                                course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                loading_courses:false,
                            });
                        }
                        //Patch logic for react state updaete on browser refresh bug.
                        const groupsLoadEvent = new CustomEvent("wisdm-ld-reports-course-changed", {
                          "detail": {"value": this.state.course }
                        });
                        document.dispatchEvent(groupsLoadEvent);
                     // }); 
                } else {
                    this.setState({loading_courses:true});
                    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
                      callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
                    }
                    wp.apiFetch({
                        path: callback_path //Replace with the correct API
                     }).then(response => {
                        let courses = this.getCourseListFromJson(response);
                        if (false!=courses && courses.length>0) {
                            this.setState(
                                {
                                course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                courses:courses,
                                courses_disabled:false, 
                                loading_courses:false,
                            });

                        }else{
                            this.setState(
                                {
                                course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                courses:[],
                                loading_courses:false,
                            });
                        }
                        //Patch logic for react state updaete on browser refresh bug.
                        const groupsLoadEvent = new CustomEvent("wisdm-ld-reports-course-changed", {
                          "detail": {"value":{"value":null, "label":__('All', 'learndash-reports-by-wisdmlabs')}}
                        });
                        document.dispatchEvent(groupsLoadEvent);
                     });
                }
                break;
            default:
                break;
        }
    }

    getTableHeadersByType(response) {
      let headers= []; 
      let table_header_names = {
        id                    : __('ID', 'learndash-reports-by-wisdmlabs'),
        name                  : __('Name', 'learndash-reports-by-wisdmlabs'),
        email                 : __( 'Email ID', 'learndash-reports-by-wisdmlabs' ),
        status                : __( 'Status', 'learndash-reports-by-wisdmlabs' ),
        steps                 : __( 'Steps Completed', 'learndash-reports-by-wisdmlabs' ),
        date                  : __( 'Completion Date', 'learndash-reports-by-wisdmlabs' ),
        time                  : __( 'Time spent', 'learndash-reports-by-wisdmlabs' ),
        total_spent_time      : __( 'Total Time Spent', 'learndash-reports-by-wisdmlabs' ),
        category              : wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + ' ' + __('Category', 'learndash-reports-by-wisdmlabs'),
        course                : wisdm_reports_get_ld_custom_lebel_if_avaiable('Course'),
        groups                : __('Groups', 'learndash-reports-by-wisdmlabs'),
        user_name             : __('Learner', 'learndash-reports-by-wisdmlabs'),
        instructors           : __('Instructor', 'learndash-reports-by-wisdmlabs'),
        students              : __('No. Of Students', 'learndash-reports-by-wisdmlabs'),
        start_date            : __('Start Date', 'learndash-reports-by-wisdmlabs'),
        started               : __('Enrolled On', 'learndash-reports-by-wisdmlabs'),
        end_date              : __('End Date', 'learndash-reports-by-wisdmlabs'),
        completed             : __('Completion Date', 'learndash-reports-by-wisdmlabs'),
        completion_rate       : __('Completion %', 'learndash-reports-by-wisdmlabs'),
        completion_rate2      : __('% Completion', 'learndash-reports-by-wisdmlabs'),
        completed_users       : __('Completed Learners', 'learndash-reports-by-wisdmlabs'),
        in_progress           : __( 'In Progress', 'learndash-reports-by-wisdmlabs' ),
        not_started           : __( 'Not Started', 'learndash-reports-by-wisdmlabs' ),
        lesson                : __('Lesson', 'learndash-reports-by-wisdmlabs'),
        course_progress       : __('Completion %', 'learndash-reports-by-wisdmlabs'),
        quizzes               : __('No. Of', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes') ,
        quiz_count            : __('No. Of', 'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quizzes'),
        quiz_title            : wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Title', 'learndash-reports-by-wisdmlabs'),
        total_attempts        : __('Total Attempts', 'learndash-reports-by-wisdmlabs'),
        attempts              : wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Attempts', 'learndash-reports-by-wisdmlabs'),
        pass_rate             : wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Pass %', 'learndash-reports-by-wisdmlabs'),
        avg_score             : __( 'Avg', 'learndash-reports-by-wisdmlabs' ) + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Score', 'learndash-reports-by-wisdmlabs'),
        pass_count            : __('No. Of Quizzes Pass', 'learndash-reports-by-wisdmlabs'),
        fail_count            : __('No. Of Quizzes Fail', 'learndash-reports-by-wisdmlabs'),
        time_spent            : __('Time Spent', 'learndash-reports-by-wisdmlabs'),
        total_time_spent      : __('Total Time Spent', 'learndash-reports-by-wisdmlabs'),
        avg_total_time_spent  : __('Avg. Total Time Spent', 'learndash-reports-by-wisdmlabs'),
        course_completion_time: __('Completion Time', 'learndash-reports-by-wisdmlabs'),
        avg_time_spent        : __('Avg. Completion Time', 'learndash-reports-by-wisdmlabs'),
        quiz_attendant_count  : __('No. Of Students Completed Quiz', 'learndash-reports-by-wisdmlabs'),
        last_access           : __('Last Activity', 'learndash-reports-by-wisdmlabs'),
        topic_title           : wisdm_reports_get_ld_custom_lebel_if_avaiable('Topic') + ' ' + __(' Title', 'learndash-reports-by-wisdmlabs'),
        topic_completion_count: wisdm_reports_get_ld_custom_lebel_if_avaiable('Topic') + ' ' +__('Completed By Students', 'learndash-reports-by-wisdmlabs'),
        quiz_time             : wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Time', 'learndash-reports-by-wisdmlabs'),
        quiz_attempts         : wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Attempts', 'learndash-reports-by-wisdmlabs'),
      };
      
      if (null!=response && response.length>0) {
        var response_headers = Object.keys(response[0]);
        if (response_headers.length>0) {
          for (let i = 0; i < response_headers.length; i++) {
            let name = response_headers[i];
            if (undefined==table_header_names[name]) {
              headers.push({"Header":name, "accessor":name, className: 'table-'+name , toolTip : name == 'total_time_spent' || name == 'time_spent' || name == 'time' || name == 'avg_time_spent' || name == 'course_completion_time' || name == 'avg_total_time_spent' ? true : false});
            } else {
              headers.push({"Header":table_header_names[name], "accessor":name, className: 'table-'+name , toolTip : name == 'total_time_spent' || name == 'time_spent' || name == 'time' || name == 'avg_time_spent' || name == 'course_completion_time' || name == 'avg_total_time_spent' ? true : false});
            }  
          }
        }
      }
      return headers;
    }

    updateChart(requestUrl) {
      this.setState({isLoaded:false, error:null, request_data:null});
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        wp.apiFetch({
          path: requestUrl //Replace with the correct API
       }).then(response => {
          if(response.requestData) {
            this.setState({request_data:response.requestData})
          }
          }).catch((error) => {
            if(error.data && error.data.requestData) {
              this.setState({request_data:error.data.requestData})
            }
            this.setState({
              error:error,
              isLoaded: true,
            });
          });
      }

  render() {
    let body = <div></div>;
    if ( this.state.course_report_type == 'learner-specific-course-reports' ) {
      return '';
    }
    if(!wisdm_ld_reports_common_script_data.is_pro_version_active){
        body =  <DummyReports image_path='iu.jpg' url='https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=inactive-user-list&utm_campaign=upgradetopro#pricing'></DummyReports>;
        // body = '';
        return (body);
    }
    if(''!=this.state.reportTypeInUse && 'default-ld-reports'!=this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader text={this.state.show_supporting_text} />;
  } else if (this.state.error) {
      body = 
      <div class={"wisdm-learndash-reports-chart-block "}>
      <div class="wisdm-learndash-reports-inactive-users graph-card-container">
      <div class="chart-header inactive-users-chart-header">
          <div class="chart-title">
            <div>
              <span>{this.state.chart_title}</span>
              <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
            </div>
            <DurationFilter pro_upgrade_option={this.upgdare_to_pro} wrapper_class='chart-summary-inactive-users' duration={this.state.duration}/>
          </div>
          <LocalFilters group={this.state.group} course={this.state.course} groups={this.state.groups} courses={this.state.courses} />
        </div>
        <div>{this.state.error.message}</div>
      </div>
    </div>;
  } else {
    body = 
    <div class={"wisdm-learndash-reports-chart-block "}>
    <div class="wisdm-learndash-reports-inactive-users graph-card-container">
    <div class="chart-header inactive-users-chart-header">
        <div class="chart-title">
          <div>
            <span>{this.state.chart_title}</span>
            <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          </div>
          <DurationFilter pro_upgrade_option={this.upgdare_to_pro} wrapper_class='chart-summary-inactive-users' duration={this.state.duration}/>
        </div>
        <LocalFilters group={this.state.group} course={this.state.course} groups={this.state.groups} courses={this.state.courses} />
      </div>
      <div>
        {
            ( this.state.tableHeaders.length > 0 )?<Table columns={this.state.tableHeaders} data={this.state.tableData}/>:<div className="error-message"><span>{__('No Data Found', 'learndash-reports-by-wisdmlabs')}</span></div>
        }
        {
            ( 'yes' == this.state.more ) ? <span className="load-more-ajax" onClick={loadInactiveUsers}>{__( 'View More', 'learndash-reports-by-wisdmlabs' )}</span> : <span></span>
        }
      </div>
    </div>
  </div>;
  }
    return (body);
  }
}

export default InactiveUsers;

document.addEventListener("DOMContentLoaded", function(event) {
  
  let elem = document.getElementsByClassName('wisdm-learndash-reports-inactive-users front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(InactiveUsers), elem[0]); 
    }
    
});

