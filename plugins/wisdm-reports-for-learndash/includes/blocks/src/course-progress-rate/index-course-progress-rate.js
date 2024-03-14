import './index.scss';
import ApexCharts from 'apexcharts';
import ChartSummarySection from '../commons/chart-summary/index.js';
import WisdmFilters from '../commons/filters/index.js';
import WisdmLoader from '../commons/loader/index.js';
import DummyReports from '../commons/dummy-reports/index.js';
import React, { Component } from "react";
import Chart from "react-apexcharts";
import { __ } from '@wordpress/i18n';
import Modal, {closeStyle} from 'simple-react-modal';
import DurationFilter from './component-duration-filter.js';
import LocalFilters from './component-local-filters.js';
import ProgressDetailsTable from './component-progress-details.js';
// var ld_api_settings = wisdm_learndash_reports_front_end_script_report_filters.ld_api_settings;

/**
 * If user is the group admin this function returns an array of unique
 * user ids which are enrolled in the groups accessible to the current user. 
 */
function wrldGetGroupAdminUsers() {
    let user_accessible_groups = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
    
    let allGroupUsers = Array();
    let includedUserIds = Array();
    if (user_accessible_groups.length<1) {
        return allGroupUsers;
    }

    user_accessible_groups.forEach(function(group){
        if ( ! ( 'group_users' in group ) ) {
            return;
        }
        let groupUsers = group.group_users;
        groupUsers.forEach(function(user) {
            if (!includedUserIds.includes(user.id)) {
                allGroupUsers.push(user);
                includedUserIds.push(user.id);
            }
        });
    });

    return allGroupUsers;
}

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

    let iSAllIncluded = true;
    for (let i in filtered_courses) {
                
        if(i['value'] == null){
           iSAllIncluded = false;
        }
    }
    if (iSAllIncluded) {
      filtered_courses.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
    }
    return filtered_courses;
}

class CourseProgressRate extends Component {
  constructor(props) {
    super(props);
      let error=null;
      if(null==this.getUserType()) {
        error = {message:__('Sorry you are not allowed to access this block, please check if you have proper access permissions','learndash-reports-by-wisdmlabs')}
      }
      let tab_selected = 'course-reports'==wisdm_learndash_reports_front_end_script_report_filters.report_type?1:0;
      this.state = {
        isLoaded: false,
        error: error,
        series:[],
        options:[],
        reportTypeInUse: wisdm_learndash_reports_front_end_script_course_completion_rate.report_type,
        chart_title: wisdm_reports_get_ld_custom_lebel_if_avaiable('Course')+ ' ' + __('Progress Rate', 'learndash-reports-by-wisdmlabs'),
        lock_icon:'',
        request_data:null,        
        active_tab:tab_selected,
        group:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        groups:[],
        course:{value:null,label:__('All courses', 'learndash-reports-by-wisdmlabs')},
        courses:[],
        category:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        categories:[],
        graph_type:'donut',
        graph_summary: [],
        duration: {value: 'all', label: __('All time', 'learndash-reports-by-wisdmlabs')},
        help_text:__('This report displays the average progress rate of courses.','learndash-reports-by-wisdmlabs'),
        course_report_type: null,
        show_supporting_text: false,
        show_progress_details_modal:false,
        page: 1,
      };

      // if (false==wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
      //   this.upgdare_to_pro = 'wisdm-ld-reports-upgrade-to-pro-front';
      //   this.lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
      // } 

      this.applyFilters           = this.applyFilters.bind(this);
      this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
      // this.showDummyImages        = this.showDummyImages.bind(this);
      this.updateLocalDuration    = this.updateLocalDuration.bind(this);
      this.updateLocalGroup       = this.updateLocalGroup.bind(this);
      this.updateLocalCategory    = this.updateLocalCategory.bind(this);
      this.updateLocalLearner     = this.updateLocalLearner.bind(this);
      this.updateLocalCourse      = this.updateLocalCourse.bind(this);
      this.updateLocalTab         = this.updateLocalTab.bind(this);
      this.openProgressDetailsModal  = this.openProgressDetailsModal.bind(this);
      this.closeProgressDetailsModal = this.closeProgressDetailsModal.bind(this);
      this.addMoreData            = this.addMoreData.bind(this);
      this.startCSVDownload            = this.startCSVDownload.bind(this);
      this.defaultFiltersLoaded            = this.defaultFiltersLoaded.bind(this);
    }

    openProgressDetailsModal() {
        document.body.classList.add('wrld-open');
        this.setState({
            show_progress_details_modal:true,
        });
        
        
        // setTimeout(function(){
        //     console.log(jQuery('div[data-modal="true"] > div'));
        //     jQuery('div[data-modal="true"] > div').css({
        //         'padding-top': '0px !important',
        //         'padding-right': '0px !important',
        //         'padding-left': '0px !important'
        //     });
        // }, 8200);
    }

    closeProgressDetailsModal(){
        document.body.classList.remove('wrld-open');
        this.setState({
            show_progress_details_modal: false
        });
        this.setState({selected_data_point: '', table_data: [], more: 'no', page: 1});
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

    defaultFiltersLoaded() {
        let url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?per_page=-1';
      if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active ) {
          for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
              url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
          }
      }
      this.setState({isLoaded:false});


      // wp.apiFetch({
      //     path: url  //Replace with the correct API
      // }).then(response => {
        // let courses     = this.getCourseListFromJson(response);
        let courses     = wisdm_learndash_reports_front_end_script_report_filters.courses;
        let groups      = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        let categories  = wisdm_learndash_reports_front_end_script_report_filters.course_categories;
        // if ( groups.length > 0 ) {
        //   groups.unshift({value: null, label:__('All', 'learndash-reports-by-wisdmlabs')});
        // }
        // if ( categories.length > 0 ) {
        //     categories.unshift({value: null, label:__('All categories', 'learndash-reports-by-wisdmlabs')});
        // }

        if(courses.length === 0){
          this.setState({ isLoaded: true , error:{message : "No courses found."}});
        }
        this.setState(
                {
                  groups:groups,
                  courses:courses,
                  course: courses.length === 0 ? {value: null, label:__('All', 'learndash-reports-by-wisdmlabs')} : courses[0],
                  categories:categories,
              }); 
        //Patch logic for react state updaete on browser refresh bug.
        const groupsLoadEvent = new CustomEvent("progress-parent-groups-changed", {
        "detail": {"value": groups }
        });
        const categoryLoadEvent = new CustomEvent("progress-parent-category-changed", {
        "detail": {"value": categories }
        });
        document.dispatchEvent(groupsLoadEvent);
        document.dispatchEvent(categoryLoadEvent);
        this.updateChart('/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&course=' + courses[0].value);
      // });
      let requestResults = []
      if ('group_leader'==wisdmLdReportsGetUserType()) {
          let groupUsers = wrldGetGroupAdminUsers();
          requestResults.push({value:groupUsers[0].id, label:groupUsers[0].display_name});
          this.setState({learner: requestResults[0]});
      } else {
            let callback_path  = '/wp/v2/users/?search=a'
            callback_path = callback_path + '&reports=1'
            if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
            wp.apiFetch({
                path: callback_path //Replace with the correct API
             }).then(response => {
                if (false!=response && response.length>0) {
                    requestResults.push({value:response[0].id, label:response[0].name});
                }
                this.setState({learner: requestResults[0]});
             }).catch((error) => {
          });
      }
    }

    componentDidMount() {    
      document.addEventListener('wisdm-ld-reports-filters-applied', this.applyFilters);
      document.addEventListener('wisdm-ld-reports-report-type-selected', this.handleReportTypeChange);
      // document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
      document.addEventListener('local_progress_duration_change', this.updateLocalDuration);
      document.addEventListener('local_group_change_progress', this.updateLocalGroup);
      document.addEventListener('local_learner_change_progress', this.updateLocalLearner);
      document.addEventListener('local_category_change_progress', this.updateLocalCategory);
      document.addEventListener('local_tab_change_progress', this.updateLocalTab);
      document.addEventListener('local_course_change_progress', this.updateLocalCourse);
      document.addEventListener('start_csv_download_cp', this.startCSVDownload);
      document.addEventListener('wrld-default-filters-loaded', this.defaultFiltersLoaded);
    }

    addMoreData(evnt) {
        let next = this.state.page + 1;
        this.setState({page:next});
        this.detailsModal(this.state.selected_data_point, true, next);
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
                if ( this.state.categories.length <= 0 ) {
                    this.setState({categories: [{value: null, label: 'All'}]});
                }
                if (null==selection) {
                    // wp.apiFetch({
                    //     path: callback_path //Replace with the correct API
                    //  }).then(response => {
                    //     let courses = this.getCourseListFromJson(response);
                        let courses = wisdm_learndash_reports_front_end_script_report_filters.courses;
                        if (false!=courses && courses.length>0) {
                            this.setState(
                                {
                                courses:courses,
                                course: courses[0],
                                category: this.state.categories[0]
                            });
                        }else{
                            this.setState(
                                {
                                courses:[],
                                course: {value: null, label: __( 'No courses available for the group', 'learndash-reports-by-wisdmlabs' )},
                                category: this.state.categories[0]
                            });
                        }
                        let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + selection + '&category=' + this.state.category.value  + '&course=' + this.state.course.value;
                        this.updateChart(request_url);
                        //Patch logic for react state updaete on browser refresh bug.
                        // const groupsLoadEvent = new CustomEvent("progress-parent-group-changed", {
                        //   "detail": {"value": group }
                        // });
                        // document.dispatchEvent(groupsLoadEvent);
                     // });
                } else {
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
                                courses:courses,
                                course: courses[0],
                                category: this.state.categories[0]
                            });

                        }else{
                            this.setState(
                                {
                                courses:[],
                                course: {value: null, label: __( 'No courses available for the group', 'learndash-reports-by-wisdmlabs' )},
                                category: this.state.categories[0]
                            });
                        }
                        //Patch logic for react state updaete on browser refresh bug.
                        const groupsLoadEvent = new CustomEvent("progress-parent-groups-changed", {
                          "detail": {"value":{"value":null, "label":__('All ', 'learndash-reports-by-wisdmlabs')}}
                        });

                        // document.dispatchEvent(groupsLoadEvent);
                         let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + selection + '&category=' + this.state.category.value  + '&course=' + this.state.course.value;
                         this.updateChart(request_url);
                     });
                }
                break;
            case 'category':
                let url = '';
                if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active ) {
                    for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
                        url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
                    }
                }
                if (null != selection) {
                    callback_path = callback_path + '?ld_course_category[]=' + selection + '&per_page=-1';
                } else {
                    callback_path = callback_path + '?per_page=-1';
                }
                callback_path += url;
                if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
                  callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
                }
                wp.apiFetch({
                    path: callback_path //Replace with the correct API
                 }).then(response => {
                    let courses = this.getCourseListFromJson(response);
                    if (false!=courses && courses.length>0) {
                        //if selected course is not in the list then clear the field
                        let course_in_the_list = false;
                        let selected_course_id = this.state.course.value;
                        courses.forEach(function (course) {
                            if (null!=selected_course_id && course.value==selected_course_id) {
                                course_in_the_list = true;
                            }
                        });
                        if (!course_in_the_list) {
                            this.setState({
                               course: courses[0],
                               group: this.state.groups[0],
                            });
                        }
                        this.setState(
                            {
                            courses:courses,
                        });
                    }
                    let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&category=' + selection  + '&course=' + this.state.course.value;
                    this.updateChart(request_url);
                 }).catch((error) => {
              });
                break;
            default:
                break;
        }
    }

    startCSVDownload(evnt) {
        if ( this.state.active_tab == 1 ) {
            var requestUrl = '/rp/v1/course-progress-rate-csv/?duration=' + this.state.duration.value + '&learner=' + this.state.learner.value + '&page=' + 'all';
        } else {
            var requestUrl = '/rp/v1/course-progress-rate-csv/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&page=' + 'all';
        }
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

    updateLocalGroup(evnt) {
        this.setState({ group:evnt.detail.value });
        if ( null == evnt.detail.value.value ) {
            this.updateSelectorsFor('group', null, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?test=1');
        } else {
            let courses_enrolled = 9999999999999;
            if ( evnt.detail.value.courses_enrolled.length > 0 ) {
                courses_enrolled = evnt.detail.value.courses_enrolled;
            }
            this.updateSelectorsFor('group', evnt.detail.value.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + courses_enrolled);
        }
        // let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + evnt.detail.value.value + '&category=' + this.state.category.value  + '&course=' + this.state.course.value;
        // this.updateChart(request_url);
    }

    updateLocalCategory(evnt) {
        this.setState({ category:evnt.detail.value });
        if (null==evnt.detail.value.value) {
            this.updateSelectorsFor('category', null, '/ldlms/v1/' + ld_api_settings['sfwd-courses']);
        } else {
            this.updateSelectorsFor('category', evnt.detail.value.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses']);
        }
    }

    updateLocalLearner(evnt) {
        this.setState({ learner:evnt.detail.value });
        let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&learner=' + evnt.detail.value.value;
        this.updateChart(request_url);
    }

    updateLocalTab(evnt) {
        this.setState({ active_tab:evnt.detail.value });

        if ( 0 == evnt.detail.value ) {
            let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&category=' + this.state.category.value;
            this.updateChart(request_url);
        } else {
            let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&learner=' + this.state.learner.value;
            this.updateChart(request_url);
        }
    }    

    updateLocalCourse(evnt) {
        this.setState({ course:evnt.detail.value });
        let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&course=' + evnt.detail.value.value + '&category=' + this.state.category.value;
        this.updateChart(request_url);
    }

    updateLocalDuration(evnt) {
        this.setState({duration:evnt.detail.value});
        if ( 0 == this.state.active_tab ) {
            let request_url = '/rp/v1/course-progress-rate?duration=' + evnt.detail.value.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&category=' + this.state.category.value;
            this.updateChart(request_url);
        } else {
            let request_url = '/rp/v1/course-progress-rate?duration=' + evnt.detail.value.value + '&learner=' + this.state.learner.value;
            this.updateChart(request_url);
        }
    }

    componentDidUpdate() {
      jQuery('.CourseProgressRate .mixed-chart').prepend(jQuery('.CourseProgressRate .apexcharts-toolbar'));
      jQuery( ".wisdm-learndash-reports-course-progress-rate .chart-title .dashicons, .wisdm-learndash-reports-course-progress-rate .chart-summary-revenue-figure .dashicons" ).hover(

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
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-progress-rate', false);
        } else {
          wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-progress-rate', true);
        }
    }

    /*showDummyImages(event){
      this.setState({course_report_type:event.detail.report_type});
      let tab_key = 'learner-specific-course-reports' == event.detail.report_type ? 1 : 0;
      const durationEvent = new CustomEvent("local_tab_change_progress", {
        "detail": {"value": tab_key }
      });
      document.dispatchEvent(durationEvent);
    }*/


    applyFilters(event) {
      let category   = event.detail.selected_categories;
      let group      = event.detail.selected_groups;
      let course     = event.detail.selected_courses;
      let lesson     = event.detail.selected_lessons;
      let topic      = event.detail.selected_topics;
      let learner    = event.detail.selected_learners;

      var request_url = '/rp/v1/course-progress-rate/?duration=' + this.state.duration.value;
      if ( undefined == learner ) {
            request_url += '&category=' + category + '&group=' + group;
            this.setState({category: event.detail.selected_categories_obj, group: event.detail.selected_groups_obj, active_tab: 0});
            if ( undefined != course ) {
                request_url += '&course=' + course;
                this.setState({course: event.detail.selected_courses_obj});
            } else {
                request_url += '&course=' + this.state.course.value;
            }
      } else {
            request_url += '&learner=' + learner;
            this.setState({learner: event.detail.selected_learners_obj, active_tab: 1});
        }
   
    if ( undefined != course ) {
      this.setState({show_supporting_text: true});
    } else {
      this.setState({show_supporting_text: false});
    }

      //Time spent on a course chart should not display for lesson/topic
    if (undefined==topic && undefined==lesson) {
      this.updateChart(request_url);
      this.setState({reportTypeInUse:'default-ld-reports'});
      wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-progress-rate' , true );
    } else {
        //hide this block.
        this.setState({reportTypeInUse:'default-ld-reports-lesson-topic'});
        wisdm_reports_change_block_visibility( '.wp-block-wisdm-learndash-reports-course-progress-rate' , false );
    }
  }

    updateChart(requestUrl) {
        this.setState({
            isLoaded: false,
            error:null,
            request_data:null
        });
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
             self.showCompletionChart(response);
             }).catch((error) => {
              window.callStack.pop();

               if(error.data && error.data.requestData) {
                 self.setState({request_data:error.data.requestData})
               }
               self.setState({
                 error:error,
                 isLoaded: true,
               });
             });
          }
        }, 500);
      };
      checkIfEmpty();
      }

    showCompletionChart( response ) {
        this.setState({series:[], options:[], error: null});

        if ( this.state.active_tab == 1 ) {
            const time_spent = [response.upto_20, response.upto_40,response.upto_60,response.upto_80,response.upto_100];
            const users = ['0%-20%', '21%-40%','41%-60%','61%-80%','81%-100%'];
            this.plotPieChart(time_spent, users, __('Courses', 'learndash-reports-by-wisdmlabs'));
            const  chart_title =__('Course Progress', 'learndash-reports-by-wisdmlabs');
            this.setState(
              {
              isLoaded: true,
              chart_title : chart_title,
              graph_summary: {
                left: [{
                      title : __('AVG LEARNER PROGRESS', 'learndash-reports-by-wisdmlabs'),
                      value: response.averageCourseCompletion + '%',
                    },],
                    
                right:[{
                    title : __('Courses: ', 'learndash-reports-by-wisdmlabs'),
                    value: response.course_count,
                  },
                  {
                    title : __('Completed All Courses: ', 'learndash-reports-by-wisdmlabs'),
                    value: response.completedCount,
                  }, 
                  {
                    title: __('Not Started Any Courses: ', 'learndash-reports-by-wisdmlabs'),
                    value:response.notstartedCount,
                  },
                  {
                    title: __('In Progress: ', 'learndash-reports-by-wisdmlabs'),
                    value:response.inprogressCount,
                  }],
                inner_help_text: __( 'Avg Learner Progress = Total Progress in courses/No. of Courses', 'learndash-reports-by-wisdmlabs' ),
              },
              help_text: __("This report shows the list of courses corresponding to the learner's progress rates.", 'learndash-reports-by-wisdmlabs'),
            });
        } else {
            const time_percent = [response.upto_20, response.upto_40,response.upto_60,response.upto_80,response.upto_100];
            const courses = ['0%-20%', '21%-40%','41%-60%','61%-80%','81%-100%'];
            this.plotPieChart(time_percent, courses, __('Learners', 'learndash-reports-by-wisdmlabs'));
            this.setState(
              {
              isLoaded: true,
              chart_title : __('Course Progress', 'learndash-reports-by-wisdmlabs'),
              graph_summary: {
                left: [{
                      title : __('AVG COURSE PROGRESS', 'learndash-reports-by-wisdmlabs'),
                      value: response.percentage + '%',
                    },],
                right:[],
                inner_help_text: __( 'Avg Course Progress = Total Progress by Learners/Learner Count', 'learndash-reports-by-wisdmlabs' ),
              },
              help_text:__("This report shows the number and list of users against course progress rate slabs.",'learndash-reports-by-wisdmlabs'),
            });
        }
    }

    showDetailsModal() {
        jQuery('.button-progress-details').click();
    }

    detailsModal( data_point, is_paginated=false, page=0 ) {
        var self = this;
        if ( 0 == page ) {
            page = this.state.page;
        }
        if ( 1 == this.state.active_tab ) {
            var requestUrl = '/rp/v1/course-progress-details/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&learner=' + this.state.learner.value + '&datapoint=' + data_point + '&page=' + page;
        } else {
            var requestUrl = '/rp/v1/course-progress-details/?duration=' + this.state.duration.value + '&category=' + this.state.category.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&datapoint=' + data_point + '&page=' + page;
        }
        // this.setState({selected_data_point: data_point, table_data: [], more: 'yes'});
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        wp.apiFetch({
            path: requestUrl //Replace with the correct API
        }).then(response => {
            var table = response.tableData;
            if (undefined==response) {
                table = [];
            }
            if ( is_paginated ) {
                table = {
                    ...this.state.table_data,
                    ...table
                };
                // table = this.state.tableData.concat(table); // for array
            }
            this.setState({selected_data_point: data_point, table_data: table, more: response.more_data, error: null});
            self.showDetailsModal();
        }).catch((error) => {
            if(error.data && error.data.requestData) {
                self.setState({request_data:error.data.requestData})
            }
            self.setState({
                error:error,
            });
        });
    }

    plotPieChart(data, labels, tooltipTextLineOne = __('Course Progress', 'learndash-reports-by-wisdmlabs')) {
      var self = this;
      let chart_options = {
        colors:['#5f5f5f'],
        theme: {
          monochrome: {
            enabled: true,
            color:'#008AD8',
            shadeTo: 'dark',
            shadeIntensity: 0.65,
          }
        },
        chart: {
          width: '100%',
          height: '300',
          type: 'donut',
          dropShadow: {
            enabled: true,
            color: '#111',
            top: -1,
            left: 3,
            blur: 3,
            opacity: 0.2
          },
          events: {
            mounted: function(chartContext, config) {
                window.callStack.pop();
              },
            dataPointSelection: (event, chartContext, config) => {
              self.detailsModal(config.w.config.labels[config.dataPointIndex]);
            }
          }
        },
        stroke: {
          width: 0,
        },
        
        plotOptions: {
          pie: {
            donut: {
              dataLabels: {
                enabled: true,
              },
              total: {
                show: false,
                showAlways: false,
                label: 'Total',
                fontSize: '22px',
                fontFamily: 'Helvetica, Arial, sans-serif',
                fontWeight: 600,
                color: '#373d3f',
                formatter: function (w) {
                  return w.globals.seriesTotals.reduce((a, b) => {
                    return a + b
                  }, 0)
                }
              }
            }
          }
        },
        labels: labels,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
          }
        }],
        legend: {
        formatter:function(seriesName, opts) {
              return [seriesName, " - ", opts.w.globals.series[opts.seriesIndex], ' ', tooltipTextLineOne, ""]
        }
        },
        tooltip: {
          custom: function({series, seriesIndex, dataPointIndex, w}) {
            return '<div class="wisdm-donut-chart-tooltip"> <div class="tooltip-body"> <span><strong>' + w.globals.labels[seriesIndex] + '</strong>' + ' | ' + tooltipTextLineOne + ' : ' + series[seriesIndex] + '</span></div></div>';
          },

          y: {
            formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
              return value;
            }
          },
        },
        };
      this.setState({graph_type:'donut',series:data, options:chart_options});
          window.callStack.pop();
    }

    isValidGraphData() {
        if (undefined==this.state.options || 0==this.state.options.length) {
          return false;
        } 
        if (undefined==this.state.series || 0==this.state.series.length) {
          return false;
        }
        
        return true;
    }

    refreshUpdateTime() {
      if ( 0 == this.state.active_tab ) {
          let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&group=' + this.state.group.value + '&course=' + this.state.course.value + '&category=' + this.state.category.value + '&disable_cache=true';
          this.updateChart(request_url);
      } else {
          let request_url = '/rp/v1/course-progress-rate?duration=' + this.state.duration.value + '&learner=' + this.state.learner.value + '&disable_cache=true';
          this.updateChart(request_url);
      }
  }

  render() {
    let body = <div></div>;
    // if(this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active){
    //     body =  <DummyReports image_path='tsoc.png'></DummyReports>;
    //       return (body);
    // }
    let data_validation = '';
    if (!this.isValidGraphData()) {
      data_validation = 'invalid-or-empty-data';
    }
    if(''!=this.state.reportTypeInUse && 'default-ld-reports'!=this.state.reportTypeInUse) {
      body = '';
    } else if (!this.state.isLoaded) {
      // yet loading
      body =  <WisdmLoader text={this.state.show_supporting_text}/>;
  } else {
    let graph = '';
    if (!this.state.error) {
      graph = <div className="course-progress-rate">
                <Chart options={this.state.options} series={this.state.series} width={this.state.options.chart.width} height={this.state.options.chart.height} type={this.state.graph_type} />
              </div>;
    }
    body = 
    <div class={"wisdm-learndash-reports-chart-block " + data_validation}>
    <div class="wisdm-learndash-reports-course-progress-rate graph-card-container">
    <div class="chart-header course-progress-rate-chart-header">
        <div class="chart-title">
          <span>{this.state.chart_title}</span>
          <span class="dashicons dashicons-info-outline widm-ld-reports-info" data-title={this.state.help_text}></span>
          <DurationFilter wrapper_class='chart-summary-inactive-users' duration={this.state.duration} />
        </div>
        <div className="chart_update_time"><span>{__( 'Last updated: ', 'learndash-reports-by-wisdmlabs' )}</span><span>{this.state.updated_on}</span><div className='chart-refresh-icon'><span class="dashicons dashicons-image-rotate" data-title={__('Click this to refresh the chart', 'learndash-reports-by-wisdmlabs')} onClick={this.refreshUpdateTime.bind(this)}></span></div></div>
        <LocalFilters group={this.state.group} category={this.state.category} groups={this.state.groups} categories={this.state.categories} course={this.state.course} courses={this.state.courses} active_tab={this.state.active_tab} learner={this.state.learner}/>
        <ChartSummarySection wrapper_class='chart-summary-course-progress-rate' graph_summary={this.state.graph_summary} error={this.state.error} />
        {/*<SummarySection />*/}
      </div>
      <div>
        {graph}
        <span className="note"><strong>{__( 'Note: ', 'learndash-reports-by-wisdmlabs' )}</strong>{ __( 'Click on any item on the pie chart to see more details.', 'learndash-reports-by-wisdmlabs' ) }</span>
      </div>
    </div>
    <Modal show={this.state.show_progress_details_modal}
            onClose={this.closeProgressDetailsModal}
            containerStyle={{width:'50%'}}
            className={"progress_details_modal"}
            >
            <span className="close-modal dashicons dashicons-no" onClick={this.closeProgressDetailsModal}></span>
            <ProgressDetailsTable type={1 == this.state.active_tab ? 'learner': 'course'} data_point={this.state.selected_data_point} table={this.state.table_data} course={this.state.course.label} learner={ this.state.learner ? this.state.learner.label : ''}/>
            {
                ( 'yes' == this.state.more ) ? <span className="load-more-ajax" onClick={this.addMoreData}>{__( 'View More', 'learndash-reports-by-wisdmlabs' )}</span> : <span></span>
            }
    </Modal>
    <button className="button-progress-details wrld-hidden" onClick={this.openProgressDetailsModal}></button>
  </div>;
  }
    return (body);
  }
}

export default CourseProgressRate;

document.addEventListener("DOMContentLoaded", function(event) {
  
  let elem = document.getElementsByClassName('wisdm-learndash-reports-course-progress-rate front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(CourseProgressRate), elem[0]); 
    }
    
});

