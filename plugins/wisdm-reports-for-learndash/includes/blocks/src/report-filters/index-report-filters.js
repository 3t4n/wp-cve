import './index.scss';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
var moment = require('moment');

import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
import WisdmLoader from '../commons/loader/index.js';
import DummyFilters from '../dummy-quiz-reports/index.js'
import { array } from 'prop-types';
import Modal, {closeStyle} from 'simple-react-modal';
import ComponentDatepicker from './component-date-filter.js';

window.ld_api_settings = {
    'sfwd-courses':'sfwd-courses', 
    'sfwd-lessons':'sfwd-lessons', 
    'sfwd-topic':'sfwd-topic', 
    'sfwd-quiz':'sfwd-quiz', 
    'sfwd-question':'sfwd-question', 
    'users':'users', 
    'groups':'groups', 
}

class Checkbox extends React.Component {
    constructor(props) {
      super(props);
      this.state = {
        isChecked: props.isChecked == "yes" ? true : false,
        name:props.name,
        label:props.label,
        value:'yes',
        always_checked: props.always_checked,
        disabled:props.always_checked=="yes"?'disabled':'',
      };
    }
    
    toggleChange = () => {
        if (this.state.always_checked!="yes") {
            this.setState({
                isChecked: !this.state.isChecked,
              });      
        }
    }
    
    render() {
      return (
        <div class="checkbox-wrapper">
            <label>
              <input type="checkbox"
                name={this.state.name}
                value={this.state.value}
                defaultChecked={this.state.isChecked}
                onChange={this.toggleChange}
                disabled={this.state.disabled}
              />
              {this.state.label}
            </label>
         </div>
      );
    }
  }

class QuizFilters extends Component {
    constructor(props) {
        super(props);
        
        let selected_course = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_group = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_quiz = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let start = moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix();
        let end = moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix();
        this.state = {
          isLoaded: false,
          error: null,
          report_type_selected:'default-quiz-reports',
          courses_disabled:'',
          groups_disabled:false,
          quizes_disabled:false,
          show_quiz_filter_modal:false,
          show_bulk_export_modal:false,
          show_bulk_attempt_progress:'wrld-hidden',
          show_bulk_learner_progress:'wrld-hidden',
          show_bulk_attempt_download:'wrld-hidden',
          show_bulk_learner_download:'wrld-hidden',
          custom_report_fields:[],
          selected_courses:selected_course,
          selected_groups:selected_group,
          selected_quizes:selected_quiz,
          selectedElementsInDefaultFilter:null,
          start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
          end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
          export_start_date:start,
          export_end_date:end,
          selectedValue: report_preferences.settings,
          selectedFields:report_preferences.settings,
          selectedCourseTitle: report_preferences.selected_course_title,
          selectedGroupTitle: report_preferences.selected_group_title,
          selectedQuizTitle: report_preferences.selected_quiz_title,
          disabled_button: true,
          isPro: wisdm_ld_reports_common_script_data.is_pro_version_active,
        }; 

        this.durationUpdated               = this.durationUpdated.bind(this);
        this.dateUpdated                   = this.dateUpdated.bind(this);
        this.onQuizReportViewChange        = this.onQuizReportViewChange.bind(this);
        this.handleQuizFilterDefaultSearch = this.handleQuizFilterDefaultSearch.bind(this);
        this.openCustomizePreviewModal     = this.openCustomizePreviewModal.bind(this);
        this.openBulkExportModal           = this.openBulkExportModal.bind(this);
        this.openBulkProgressModal         = this.openBulkProgressModal.bind(this);
        this.closeCustomizePreviewModal    = this.closeCustomizePreviewModal.bind(this);
        this.closeBulkExportModal          = this.closeBulkExportModal.bind(this);
        // this.closeBulkProgressModal        = this.closeBulkProgressModal.bind(this);
        this.handleQuizSearch              = this.handleQuizSearch.bind(this);
        this.handleCourseSearch            = this.handleCourseSearch.bind(this);
        this.handleGroupSearch             = this.handleGroupSearch.bind(this);
        this.handleDefaultQuizFilterChange = this.handleDefaultQuizFilterChange.bind(this);
        this.handleQuizCourseChange        = this.handleQuizCourseChange.bind(this);
        this.handleQuizGroupChange         = this.handleQuizGroupChange.bind(this);
        this.handleQuizChange              = this.handleQuizChange.bind(this);
        this.applyQuizFilters              = this.applyQuizFilters.bind(this);
        this.applyExportFilters            = this.applyExportFilters.bind(this);
        this.previewCustomReport           = this.previewCustomReport.bind(this);
        this.previewReport                 = this.previewReport.bind(this);
        this.defaultFiltersLoaded                 = this.defaultFiltersLoaded.bind(this);
        var localized_data_url = '/rp/v1/report-filters-data';
        if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          localized_data_url += '/?wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
        wp.apiFetch({
            path: localized_data_url  //Replace with the correct API
        }).then(response => {
            window.wisdm_learndash_reports_front_end_script_report_filters = response.wisdm_learndash_reports_front_end_script_report_filters;
            this.defaultFiltersLoaded();
        });
    }

    defaultFiltersLoaded() {
        let quiz_section_disabled = 'disabled';
        let report_type_selected= 'default-quiz-reports';
        let selected_course = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_group = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_quiz = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let start = moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix();
        let end = moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix();
        if (false!=wisdm_ld_reports_common_script_data.is_pro_version_active) {
            quiz_section_disabled = 'enabled';
        }

        if (undefined!=wisdm_learndash_reports_front_end_script_report_filters.qre_request_params && wisdm_learndash_reports_front_end_script_report_filters.qre_request_params.report=='custom') {
            report_type_selected = 'custom-quiz-reports';
        }

        let userType = wisdmLdReportsGetUserType();
        let groups_disabled = false;
        let quizes_disabled = false;
        let categories_disabled = false;
        let courses = getCoursesByGroups(wisdm_learndash_reports_front_end_script_report_filters.courses);
        let quizes  = getQuizesByCoursesAccessible(courses, wisdm_learndash_reports_front_end_script_report_filters.quizes);
        this.default_quizes = quizes;
        this.default_groups = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        if (undefined!=wisdm_learndash_reports_front_end_script_report_filters.qre_filters) {
            let qre_filters = wisdm_learndash_reports_front_end_script_report_filters.qre_filters;
            let selected_course_id = undefined!=qre_filters.course_filter&&qre_filters.course_filter>0?parseInt(qre_filters.course_filter):-1;
            selected_course = getSelectionByValueId(selected_course_id, courses);
            let selected_group_id = undefined!=qre_filters.group_filter&&qre_filters.group_filter>0?parseInt(qre_filters.group_filter):-1;
            selected_group = getSelectionByValueId(selected_group_id, this.default_groups);
            let selected_quiz_id = undefined!=qre_filters.quiz_filter&&qre_filters.quiz_filter>0?parseInt(qre_filters.quiz_filter):-1;
            selected_quiz = getSelectionByValueId(selected_quiz_id, this.default_quizes);
            start = undefined!=qre_filters.start_date?parseInt(qre_filters.start_date):start;
            end = undefined!=qre_filters.end_date?parseInt(qre_filters.end_date):end;
        }
        if ('administrator'==userType) { 
            }
        else if('group_leader'==userType) {
            categories_disabled = true;
            groups_disabled = false;
        }
        this.setState({
            report_type_selected: report_type_selected,
            groups_disabled: groups_disabled,
            quizes_disabled: quizes_disabled,
            categories:wisdm_learndash_reports_front_end_script_report_filters.course_categories,
            courses:courses,
            groups:this.default_groups,
            quizes:this.default_quizes,
            selected_courses:selected_course,
            selected_groups:selected_group,
            selected_quizes:selected_quiz,
            export_start_date:start,
            export_end_date:end,
        })
    }

    componentDidMount() { 
        document.addEventListener('duration_updated', this.durationUpdated);
        document.addEventListener('date_updated', this.dateUpdated);
        // document.addEventListener('wrld-default-filters-loaded', this.defaultFiltersLoaded);

        if (this.state.report_type_selected=='default-quiz-reports') {
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-time-spent-on-a-course', false);
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-course-completion-rate', false);
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-quiz-completion-rate-per-course', false);
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-quiz-completion-time-per-course', false);
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-learner-pass-fail-rate-per-course', false);
            wisdm_reports_change_block_visibility('.wp-block-wisdm-learndash-reports-average-quiz-attempts', false);
        }
    }

    componentDidUpdate() {
        jQuery( '.export-attempt-results .dashicons-info-outline, .export-attempt-learner-answers .dashicons-info-outline' ).hover(

          function() {
            var $div = jQuery('<div/>').addClass('wdm-tooltip').css({
                position: 'absolute',
                zIndex: 999,
                display: 'none'
            }).appendTo(jQuery(this));
            $div.text(jQuery(this).attr('data-title'));
            var $font = jQuery(this).parents('.report-label').css('font-family');
            $div.css('font-family', $font);
            $div.show();
          }, function() {
            jQuery( this ).find( ".wdm-tooltip" ).remove();
          }
        );
    }

    durationUpdated(event) {
        this.setState({start_date:event.detail.startDate, end_date:event.detail.endDate});
    }

    dateUpdated(event) {
        this.setState({export_start_date:event.detail.startDate, export_end_date:event.detail.endDate});
        // jQuery('.apply-bulk-filters').removeAttr('disabled');
        this.setState({disabled_button: false});
        /*const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);*/
    }

    handleQuizSearch = (inputString, callback) => {
        // perform a request
        let callback_path  = '/ldlms/v1/'+ ld_api_settings['sfwd-quiz'] + '/';
        let requestResults = []
        if (2<inputString.length) {
            callback_path = callback_path + '?search=' +  inputString
            if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
            wp.apiFetch({
                path: callback_path  //Replace with the correct API
             }).then(response => {
                if (false!=response && response.length>0) {
                    response.forEach(element => {
                        requestResults.push({value:element.id, label:element.title.rendered});
                    });
                }
                callback(requestResults);
             }).catch((error) => {
                    callback(requestResults)
              });
        }
      }

    handleQuizChange(selected_quizes) {
        if (null==selected_quizes) {
            this.setState({ selected_quizes:{value:-1}, selectedValue:{quiz_filter: -1}, selectedQuizTitle: __('All', 'learndash-reports-by-wisdmlabs')});
        } else {
            this.setState({selected_quizes:selected_quizes, selectedValue:{quiz_filter: selected_quizes}, selectedQuizTitle: selected_quizes.label});
        }
        // jQuery('.apply-bulk-filters').removeAttr('disabled');
        this.setState({disabled_button: false});
        /*const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);*/
    }


    handleQuizFilterDefaultSearch(inputString, callback) {
        // perform a request
        let callback_path  = '/rp/v1/qre-live-search/?search_term=';
        let requestResults = [];
        if (2<inputString.length) {
            callback_path = callback_path + inputString
            if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
            wp.apiFetch({
                path: callback_path //Replace with the correct API
             }).then(response => {
                let userResults = [];
                let quizResults = [];
                let courseResults = [];
                if (false!=response && response.search_results.length>0) {
                    response.search_results.forEach(element => {
                        if ('user'==element.type) {
                            userResults.push({value:element.ID, label:element.title , type:element.type});
                        } else if ('quiz'==element.type) {
                            quizResults.push({value:element.ID, label:element.title , type:element.type});
                        } else if ('post'==element.type) {
                            courseResults.push({value:element.ID, label:element.title , type:element.type});
                        }
                    });
                    requestResults = [
                        { label: __('Users','learndash-reports-by-wisdmlabs'),
                        options:userResults},
                        { label: __('Quizzes','learndash-reports-by-wisdmlabs'),
                        options:quizResults},
                        { label: __('Courses','learndash-reports-by-wisdmlabs'),
                        options:courseResults}
                    ]
                }
                callback(requestResults);
             }).catch((error) => {
                callback(requestResults)
          });
        } else {
            callback(requestResults);
        }
    }

    handleDefaultQuizFilterChange(selectedElements) {
        this.setState({selectedElementsInDefaultFilter:selectedElements});
    }

    onQuizReportViewChange(event) {
        this.setState({report_type_selected:event.target.value});
        let custom_report_type = '';
        if ('default-quiz-reports'==event.target.value) {
            custom_report_type = '';
        } else if ('custom-quiz-reports'==event.target.value) {
            custom_report_type = 'custom';
        }
        document.dispatchEvent( new CustomEvent("wisdm-ld-custom-report-type-select", {
            "detail": {'report_selector': custom_report_type}}));
    }

    handleQuizCourseChange(selected_course) {
        if (null==selected_course) {
            this.setState({ selected_courses:{value:-1}, selectedValue:{course_filter: -1}, selectedCourseTitle: 'All', quizes:this.default_quizes, groups:this.default_groups});

        } else {
            let course_quizes = this.getCourseQuizes(selected_course.value,this.default_quizes);
            let course_groups = this.getCourseGroups(selected_course.value, this.default_groups);
            this.setState({selected_courses:selected_course, selectedValue:{course_filter: selected_course}, 
                selectedCourseTitle: selected_course.label, quizes:course_quizes,
                selectedValue:{quiz_filter: -1}, selectedQuizTitle: __('All', 'learndash-reports-by-wisdmlabs'),
                groups : course_groups,
            });
        }
        this.setState({disabled_button: false});
        // jQuery('.apply-bulk-filters').removeAttr('disabled');
        /*const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);*/
    }

    getCourseQuizes(course_id, quiz_list) {
        let course_quizes = [];
        quiz_list.forEach(function(quiz){
            if (quiz.course_id==course_id) {
                course_quizes.push(quiz);
            }
        });
        return course_quizes;
    }

    getCourseGroups(course_id, group_list = []) {
        let course_groups = [];
        if (group_list.length>0) {
            group_list.forEach(function(group){
                if ( ! ( 'courses_enrolled' in group ) ) {
                    return;
                }
                if (group.courses_enrolled.includes(course_id)) {
                    course_groups.push(group);
                }
            });
        }
        return course_groups;

    }

    handleQuizGroupChange(groups_selected) {
        if (null==groups_selected) {
            this.setState({ selected_groups:{value:-1, label:__('All', 'learndash-reports-by-wisdmlabs')}, selectedValue:{group_filter: -1}});
        } else {
        this.setState({selected_groups:groups_selected, selectedValue:{group_filter: groups_selected}, selectedGroupTitle: groups_selected.label});
        }
        this.setState({disabled_button: false});

        // jQuery('.apply-bulk-filters').removeAttr('disabled');
        /*const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);*/
    }

    applyExportFilters() {
        this.setState({disabled_button: true, show_bulk_attempt_download: 'wrld-hidden', show_bulk_learner_download: 'wrld-hidden'});
        jQuery('.report-export-buttons button').removeAttr('disabled');
        jQuery('.bulk-export-download').addClass('wrld-hidden').html('');
        // event.currentTarget.disabled = true;
        const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);
    }

    handleCourseSearch(inputString, callback) {
         // perform a request
         let callback_path  = '/ldlms/v1/sfwd-courses/?search='
         let requestResults = []
         if (2<inputString.length) {
             callback_path = callback_path + inputString
             wp.apiFetch({
                 path: callback_path //Replace with the correct API
              }).then(response => {
                 if (false!=response && response.length>0) {
                     response.forEach(element => {
                         requestResults.push({value:element.id, label:element.title.rendered});
                     });
                 }
                 callback(requestResults);
              }).catch((error) => {
                 callback(requestResults)
           });
         }
    }

    handleGroupSearch(inputString, callback) {
        // perform a request
        let callback_path  = '/ldlms/v1/groups/?search='
        let requestResults = []
        if (2<inputString.length) {
            callback_path = callback_path + inputString
            wp.apiFetch({
                path: callback_path //Replace with the correct API
             }).then(response => {
                if (false!=response && response.length>0) {
                    response.forEach(element => {
                        requestResults.push({value:element.id, label:element.title.rendered});
                    });
                }
                callback(requestResults);
             }).catch((error) => {
                callback(requestResults)
          });
        }
    }

    handleUserSearch(inputString, callback) {
       // perform a request
       let requestResults = []
       if (3>inputString.length) {
           return callback(requestResults);
       }
       if ('group_leader'==wisdmLdReportsGetUserType()) {
           let groupUsers = wrldGetGroupAdminUsers();
           groupUsers.forEach(user => {
               if (user.display_name.toLowerCase().includes(inputString.toLowerCase()) || user.user_nicename.toLowerCase().includes(inputString.toLowerCase())) {
                   requestResults.push({value:user.id, label:user.display_name});        
               }
           });
           callback(requestResults);
       } else {
           let callback_path  = '/wp/v2/users/?search='
           callback_path = callback_path + inputString + '&reports=1'
           wp.apiFetch({
               path: callback_path //Replace with the correct API
            }).then(response => {
               if (false!=response && response.length>0) {
                   response.forEach(element => {
                       requestResults.push({value:element.id, label:element.name});
                   });
               }
               callback(requestResults);
            }).catch((error) => {
                callback(requestResults)
          });
       }
    }

    openCustomizePreviewModal() {
        document.body.classList.add('wrld-open');
        this.setState({
            show_quiz_filter_modal:true,
        });
    }

    closeCustomizePreviewModal(){
        document.body.classList.remove('wrld-open');
        this.setState({
            show_quiz_filter_modal:false,
        });
    }

    openBulkExportModal() {
        document.body.classList.add('wrld-open');
        this.setState({
            show_bulk_export_modal:true,
        });
        
        const defaultEntryCounts = new CustomEvent("wrld-fetch-export-data-count", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(defaultEntryCounts);
        // setTimeout(function(){
        //     console.log(jQuery('div[data-modal="true"] > div'));
        //     jQuery('div[data-modal="true"] > div').css({
        //         'padding-top': '0px !important',
        //         'padding-right': '0px !important',
        //         'padding-left': '0px !important'
        //     });
        // }, 8200);
    }

    closeBulkExportModal(){
        document.body.classList.remove('wrld-open');
        this.setState({
            show_bulk_export_modal:false,
            show_bulk_attempt_progress: 'wrld-hidden',
            show_bulk_learner_progress: 'wrld-hidden'
        });
    }
    
    openBulkProgressModal(type) {
        // document.body.classList.add('wrld-open');
        if ( 'attempt' === type ) {
            this.setState({
                show_bulk_attempt_progress:'',
            });
        } else {
            this.setState({
                show_bulk_learner_progress:'',
            });
        }
    }

    closeBulkProgressModal(){
        document.body.classList.remove('wrld-open');
        this.setState({
            show_bulk_progress_modal:false,
        });
    }



    applyQuizFilters() {
        if (null!=this.state.selectedElementsInDefaultFilter) {
            let selecion_label = this.state.selectedElementsInDefaultFilter.label;
            let selection_type = this.state.selectedElementsInDefaultFilter.type;
            let selection_id   = this.state.selectedElementsInDefaultFilter.value;
            const defaultQuizReport = new CustomEvent("wisdm-ld-reports-default-quiz-report-filters-applied", {
                "detail": {
                            'start_date':this.state.start_date,
                            'end_date':this.state.end_date,
                            'selection_label':selecion_label,
                            'selection_type': selection_type,
                            'selection_id': selection_id,
                        }});
            document.dispatchEvent(defaultQuizReport);
        }
    }

    previewReport() {
        // const defaultCustomQuizReport = new CustomEvent("wisdm-ld-reports-default-custom-quiz-report-filters-applied", {
        //     "detail": {
        //                'start_date':this.state.start_date,
        //                'end_date':this.state.end_date,
        //                'selected_courses': this.state.selected_courses.value,
        //                'selected_groups': this.state.selected_groups.value,
        //                'selected_quizes': this.state.selected_quizes.value,
        //             }});
        // document.dispatchEvent(defaultCustomQuizReport);
        this.previewCustomReport();
    }

    previewCustomReport() {
        let fields_selected = {};
        let course_completion_dates_from = jQuery('#course-completion-from-date').val();
        let course_completion_dates_to = jQuery('#course-completion-to-date').val();	
		jQuery( '.quiz-filter-modal' ).find( 'input[type=checkbox]' ).each( function( ind, el ){
			if ( jQuery( el ).is( ':checked' ) ) {
                let index = jQuery( el ).attr( 'name' );
                fields_selected[index] = jQuery( el ).val();
			}
		});
		jQuery( '.quiz-filter-modal' ).find( 'select, input[type=text]' ).each( function( ind, el ){
            let index = jQuery( el ).attr( 'name' );
			fields_selected[ index ] = jQuery( el ).val();
		});

        fields_selected['course_filter'] = this.state.selected_courses.value;
        fields_selected['group_filter'] = this.state.selected_groups.value;
        fields_selected['quiz_filter'] = this.state.selected_quizes.value;
        if ( jQuery( '.quiz-filter-modal' ).length === 0 ) {
            fields_selected['select_event'] = 1;
            let field_values = this.state.selectedFields;
            field_values.course_filter = this.state.selected_courses.value;
            field_values.group_filter = this.state.selected_groups.value;
            field_values.quiz_filter = this.state.selected_quizes.value;
            this.setState({selectedFields: field_values});
        } else {
            this.setState({selectedFields:fields_selected});
        }

        // fields_selected['category_filter'] = this.state.selected_categories.value;



        const customQuizReport = new CustomEvent("wisdm-ld-reports-custom-quiz-report-filters-applied", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'course_completion_dates_from':course_completion_dates_from,
                        'course_completion_dates_to':course_completion_dates_to,
                        'fields_selected':fields_selected,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                    }});
        document.dispatchEvent(customQuizReport);
        this.closeCustomizePreviewModal();
    }

    exportAttemptCSV(event) {
        jQuery(event.target).attr('disabled', 'disabled');
        this.exportAttemptResults('csv');
    }

    exportAttemptXLSX(event) {
        jQuery(event.target).attr('disabled', 'disabled');
        this.exportAttemptResults('xlsx');
    }

    exportLearnerCSV(event) {
        jQuery(event.target).attr('disabled', 'disabled');
        this.exportLearnerResults('csv');
    }

    exportLearnerXLSX(event) {
        jQuery(event.target).attr('disabled', 'disabled');
        this.exportLearnerResults('xlsx');
    }

    exportAttemptResults(type) {
        const attemptQuizReport = new CustomEvent("wrld-bulk-export-attempt-results", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                        'type': type,
                    }});
        document.dispatchEvent(attemptQuizReport);
        this.openBulkProgressModal('attempt');
    }

    exportLearnerResults(type) {
        const learnerQuizReport = new CustomEvent("wrld-bulk-export-learner-results", {
            "detail": {
                        'start_date':this.state.export_start_date,
                        'end_date':this.state.export_end_date,
                        'selected_courses': this.state.selected_courses.value,
                        'selected_groups': this.state.selected_groups.value,
                        'selected_quizes': this.state.selected_quizes.value,
                        'type': type,
                    }});
        document.dispatchEvent(learnerQuizReport);
        this.openBulkProgressModal('learner');
    }

    render() {
        let body = '';
        let customFilterDropDowns = 
            <div class="quiz-reporting-custom-filters">
                <div class="selector">
                    <div class="selector-label">{__('Courses','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select 
                        isDisabled = {this.state.courses_disabled}
                        // loadOptions={this.handleCourseSearch}
                        options={this.state.courses}
                        placeholder={__('All','learndash-reports-by-wisdmlabs')}
                        onChange={this.handleQuizCourseChange}
                        isClearable="true"
                        value={{value: this.state.selectedValue.course_filter, label: this.state.selectedCourseTitle}}
                    />
                    </div>
                </div>
                <div class="selector">
                    <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select 
                         onChange={this.handleQuizGroupChange}
                         options={this.state.groups}
                         placeholder={__('All','learndash-reports-by-wisdmlabs')}
                         isClearable="true"
                         value={this.state.selected_groups}
                    />
                    </div>
                </div>
                <div class="selector">
                    <div class="selector-label">{__('Quizzes','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select
                        // loadOptions={this.handleQuizSearch}
                        onChange={this.handleQuizChange}
                        options={this.state.quizes}
                        placeholder={__('All','learndash-reports-by-wisdmlabs')}
                        isClearable="true"
                        value={{value: this.state.selectedValue.quiz_filter, label: this.state.selectedQuizTitle}}
                    />
                    </div>
                </div>
            </div>;
        //Default Filers
        let filterSection = 
            <div class="quiz-eporting-filter-section default-filters">
                <div class="selector search-input">
                    <div class="selector-label">{__('Search','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <AsyncSelect
                        components={{ DropdownIndicator:() => null, IndicatorSeparator:() => null, NoOptionsMessage: (element) => {return element.selectProps.inputValue.length>2?__(' No learners/quizzes/courses found for the search string \'', 'learndash-reports-by-wisdmlabs') + element.selectProps.inputValue +'\'':__(' Type 3 or more letters to search', 'learndash-reports-by-wisdmlabs') } }}
                        closeMenuOnSelect={false}
                        placeholder={__('Search any user, quiz or course','learndash-reports-by-wisdmlabs')}
                        loadOptions={this.handleQuizFilterDefaultSearch}
                        onChange={this.handleDefaultQuizFilterChange}
                        isClearable="true"
                    />
                    </div>
                </div>
                <div class="selector button-filter">
                    <div class="apply-filters">
                        <button onClick={this.applyQuizFilters}>{__('Show Reports', 'learndash-reports-by-wisdmlabs')}</button>
                    </div>
                </div>
            <Modal show={this.state.show_bulk_export_modal}
                        onClose={this.closeBulkExportModal}
                        containerStyle={{width:'50%'}}
                        className={"bulk_export_modal"}
                        >
                        <span class="close-modal dashicons dashicons-no" onClick={this.closeBulkExportModal}></span>
                        <div className="header bulk-export-header wrld-hidden">
                        </div>
                        <div className="filter-section">
                            {customFilterDropDowns}
                            <div className="date-container">
                                <div className="calendar-label">
                                    <span>{__('DATE OF ATTEMPT', 'learndash-reports-by-wisdmlabs')}</span>
                                </div>
                                <span className="export-date-range">
                                    <ComponentDatepicker start={this.state.export_start_date} end={this.state.export_end_date}></ComponentDatepicker>
                                    <div className="apply_filters"><button className="apply-bulk-filters" onClick={this.applyExportFilters} disabled={this.state.disabled_button}>{__('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')}</button></div>
                                </span>
                            </div>
                        </div>
                        <div className="bulk-export-heading">
                            <h3>{__('Export', 'learndash-reports-by-wisdmlabs')}</h3>
                            <div>Total quiz attempts - <span>???</span><div> selected</div></div>
                        </div>
                        <div className="export-attempt-results">
                            <div className="report-label">
                                <label>
                                    {__('Export all quiz attempts result', 'learndash-reports-by-wisdmlabs')}
                                </label>
                                <span className="dashicons dashicons-info-outline" data-title={__('This report exports the summarized information of all quiz attempts', 'learndash-reports-by-wisdmlabs')}>                                    
                                </span>
                            </div>
                            <div className="report-export-buttons">
                                <button className="export-attempt-csv" onClick={this.exportAttemptCSV.bind(this)}>
                                    CSV
                                </button>
                                <button className="export-attempt-xlsx" onClick={this.exportAttemptXLSX.bind(this)}>
                                    XLSX
                                </button>
                            </div>
                            <div className="export-link-wrapper">
                                <div className={`bulk-export-download ${this.state.show_bulk_attempt_download}`}></div>
                                <div className={`bulk-export-progress ${this.state.show_bulk_attempt_progress}`}>
                                    <label>{__('Downloading progress:', 'learndash-reports-by-wisdmlabs')}</label>
                                    <progress value="0" max="100"></progress>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div className="export-attempt-learner-answers">
                            <div className="report-label">
                                <label>
                                    {__('Export quiz attempts learner answers', 'learndash-reports-by-wisdmlabs')}
                                </label>
                                <span className="dashicons dashicons-info-outline" data-title={__('This report exports the actual answers provided by learners for all the quiz attempts', 'learndash-reports-by-wisdmlabs')}>
                                </span>
                            </div>
                            <div className="report-export-buttons">
                                <button className="export-learner-csv" onClick={this.exportLearnerCSV.bind(this)}>
                                    CSV
                                </button>
                                <button className="export-learner-xlsx" onClick={this.exportLearnerXLSX.bind(this)}>
                                    XLSX
                                </button>
                            </div>
                            <div className="export-link-wrapper">
                                <div className={`bulk-export-download ${this.state.show_bulk_learner_download}`}></div>
                                <div className={`bulk-export-progress ${this.state.show_bulk_learner_progress}`}>
                                    <label>{__('Downloading progress:', 'learndash-reports-by-wisdmlabs')}</label>
                                    <progress value="0" max="100"></progress>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                        <div className="export-note">
                            <span>{__('Note: We recommend to download atmost 10000 number of quiz attempts to avoid server timeout.', 'learndash-reports-by-wisdmlabs')}</span>
                        </div>
                </Modal>
                <button class="button-bulk-export" onClick={this.openBulkExportModal}>{__('Bulk Export', 'learndash-reports-by-wisdmlabs')}</button>
            </div>;
        //Custom Filers
        if ("custom-quiz-reports"===this.state.report_type_selected) {
            customFilterDropDowns = 
            <div class="quiz-reporting-custom-filters">
                <div class="selector">
                    <div class="selector-label">{__('Courses','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select 
                        isDisabled = {this.state.courses_disabled}
                        // loadOptions={this.handleCourseSearch}
                        options={this.state.courses}
                        placeholder={__('All','learndash-reports-by-wisdmlabs')}
                        onChange={this.handleQuizCourseChange}
                        isClearable="true"
                        value={{value: this.state.selectedValue.course_filter, label: this.state.selectedCourseTitle}}
                    />
                    </div>
                </div>
                <div class="selector">
                    <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select 
                         onChange={this.handleQuizGroupChange}
                         options={this.state.groups}
                         placeholder={__('All','learndash-reports-by-wisdmlabs')}
                         isClearable="true"
                         value={this.state.selected_groups}
                    />
                    </div>
                </div>
                <div class="selector">
                    <div class="selector-label">{__('Quizzes','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <Select
                        // loadOptions={this.handleQuizSearch}
                        onChange={this.handleQuizChange}
                        options={this.state.quizes}
                        placeholder={__('All','learndash-reports-by-wisdmlabs')}
                        isClearable="true"
                        value={{value: this.state.selectedValue.quiz_filter, label: this.state.selectedQuizTitle}}
                    />
                    </div>
                </div>
            </div>;
            filterSection = 
            <div class="quiz-eporting-filter-section custom-filters">
                <div class="help-section">
                    <p>{__('Customize your Quiz Results and analyze them in a detailed view. Please select the appropriate filters and the fields (by clicking on the Customize Report Button) and click on Apply Filters to display the reports below.',  'learndash-reports-by-wisdmlabs')}</p>   
                    <p class="note"><b>{__('Note:',  'learndash-reports-by-wisdmlabs')}</b>{__(' It may take a while for a report to be generated depending of the amount of the data selected.',  'learndash-reports-by-wisdmlabs')}</p>
                </div>
                <div className="filter-wrap">    
                    {customFilterDropDowns}
                    <div className="date-container">
                        <div className="calendar-label">
                            <span>{__('DATE OF ATTEMPT', 'learndash-reports-by-wisdmlabs')}</span>
                        </div>
                        <ComponentDatepicker start={this.state.export_start_date} end={this.state.export_end_date}></ComponentDatepicker>
                    </div>
                </div>
            <div class="filter-buttons">
                <div class="filter-button-container">
                    <Modal  show={this.state.show_quiz_filter_modal}
                            onClose={this.closeCustomizePreviewModal}
                            containerStyle={{width:'80%'}}
                            >
                        <div class="quiz-filter-modal">
                            <div class="header">
                                <h2>{__('Customize Report', 'learndash-reports-by-wisdmlabs')}</h2>
                            </div>
                            <div class="quiz-reporting-custom-filters lr-dropdowns">
                                <div class="selector">
                                    <div class="selector-label">{__('All Attempts Report Fields','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                                    <div class="select-control">
                                        <Checkbox isChecked="yes" always_checked="yes" name="user_name" label={__('Username',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="quiz_title" label={__('Quiz',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="course_title" label={__('Course', 'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.course_category} name="course_category" label={__('Course Category','learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.group_name} name="group_name" label={__('Group',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.user_email} name="user_email" label={__('User Email',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.quiz_status} name="quiz_status" label={__('Quiz Status',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="quiz_category" label={__('Quiz Category',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="quiz_points_earned" label={__('Points Earned',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.quiz_score_percent} name="quiz_score_percent" label={__('Score (in%)',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="date_of_attempt" label={__('Date of attempt',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="time_taken" label={__('Time Taken',      'learndash-reports-by-wisdmlabs')}/>
                                    </div>
                                </div>
                                <div class="selector">
                                    <div class="selector-label">{__('Question Response Report Fields','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                                    </div>
                                    <div class="select-control">
                                        <Checkbox isChecked={this.state.selectedFields.question_type} name="question_type" label={__('Question Type',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.user_first_name} name="user_first_name" label={__('First Name',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.user_last_name} name="user_last_name" label={__('Last Name',   'learndash-reports-by-wisdmlabs')}/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-action-buttons">
                                <button class="button-customize-preview cancel" onClick={this.closeCustomizePreviewModal}>{__('Cancel', 'learndash-reports-by-wisdmlabs')}</button>
                                <button class="button-quiz-preview" onClick={this.previewCustomReport}>{__('Apply', 'learndash-reports-by-wisdmlabs')}</button>
                            </div>
                        </div>
                    </Modal>
                    <button class="button-customize-preview" onClick={this.openCustomizePreviewModal}>{__('CUSTOMIZE REPORT', 'learndash-reports-by-wisdmlabs')}</button>
                    <button class="button-quiz-preview" onClick={this.previewReport}>{__('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')}</button>
                </div>
            </div>
            <Modal show={this.state.show_bulk_export_modal}
                    onClose={this.closeBulkExportModal}
                    containerStyle={{width:'50%'}}
                    className={"bulk_export_modal"}        
                    >
                    <span class="close-modal dashicons dashicons-no" onClick={this.closeBulkExportModal}></span>    
                    <div className="header bulk-export-header">
                    </div>
                    <div className="filter-section">
                        {customFilterDropDowns}
                        <div className="date-container">
                            <div className="calendar-label">
                                <span>{__('DATE OF ATTEMPT', 'learndash-reports-by-wisdmlabs')}</span>
                            </div>
                            <span className="export-date-range">
                                <ComponentDatepicker start={this.state.export_start_date} end={this.state.export_end_date}></ComponentDatepicker>
                                <div className="apply_filters"><button className="apply-bulk-filters" disabled={this.state.disabled_button} onClick={this.applyExportFilters}>{__('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')}</button></div>
                            </span>
                        </div>
                    </div>
                    <div className="bulk-export-heading">
                        <h3>{__('Export', 'learndash-reports-by-wisdmlabs')}</h3>
                        <div>Total quiz attempts - <span>???</span><div> selected</div></div>
                    </div>
                    <div className="export-attempt-results">
                        <div className="report-label">
                            <label>
                                {__('Export all quiz attempts result', 'learndash-reports-by-wisdmlabs')}
                            </label>
                            <span className="dashicons dashicons-info-outline" data-title={__('This report exports the summarized information of all quiz attempts', 'learndash-reports-by-wisdmlabs')}>                                    
                            </span>
                        </div>
                        <div className="report-export-buttons">
                            <button className="export-attempt-csv" onClick={this.exportAttemptCSV.bind(this)}>
                                CSV
                            </button>
                            <button className="export-attempt-xlsx" onClick={this.exportAttemptXLSX.bind(this)}>
                                XLSX
                            </button>
                        </div>
                        <div className="export-link-wrapper">
                            <div className={`bulk-export-download ${this.state.show_bulk_attempt_download}`}></div>
                            <div className={`bulk-export-progress ${this.state.show_bulk_attempt_progress}`}>
                                <label>{__('Downloading progress:', 'learndash-reports-by-wisdmlabs')}</label>
                                <progress value="0" max="100"></progress>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div className="export-attempt-learner-answers">
                        <div className="report-label">
                            <label>
                                {__('Export quiz attempts learner answers', 'learndash-reports-by-wisdmlabs')}
                            </label>
                            <span className="dashicons dashicons-info-outline" data-title={__('This report exports the actual answers provided by learners for all the quiz attempts', 'learndash-reports-by-wisdmlabs')}>
                            </span>
                        </div>
                        <div className="report-export-buttons">
                            <button className="export-learner-csv" onClick={this.exportLearnerCSV.bind(this)}>
                                CSV
                            </button>
                            <button className="export-learner-xlsx" onClick={this.exportLearnerXLSX.bind(this)}>
                                XLSX
                            </button>
                        </div>
                        <div className="export-link-wrapper">
                            <div className={`bulk-export-download ${this.state.show_bulk_learner_download}`}></div>
                            <div className={`bulk-export-progress ${this.state.show_bulk_learner_progress}`}>
                                <label>{__('Downloading progress:', 'learndash-reports-by-wisdmlabs')}</label>
                                <progress value="0" max="100"></progress>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div className="export-note">
                        <span>{__('Note: We recommend to download atmost 10000 number of quiz attempts to avoid server timeout.', 'learndash-reports-by-wisdmlabs')}</span>
                    </div>
            </Modal>
            <button class="button-bulk-export" onClick={this.openBulkExportModal}>{__('Bulk Export', 'learndash-reports-by-wisdmlabs')}</button>
        </div>;
      }
      if ('disabled'==this.quiz_section_disabled) {
          body = '';
      } else {
          let default_quizz_reports_label = __('Default',  'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' +__('Report View', 'learndash-reports-by-wisdmlabs');
          let custom_quizz_reports_label  = __('Customized',  'learndash-reports-by-wisdmlabs') + ' ' + wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Report View', 'learndash-reports-by-wisdmlabs');
          body = 
          <div class='quiz-report-filters-wrapper'>
            <div class='select-view'>
                <span>{__('Select View',  'learndash-reports-by-wisdmlabs')}</span>
            </div>
            <div class='quiz-report-types' onChange={this.onQuizReportViewChange}>
                
                <input id="dfr" type="radio" value="default-quiz-reports" name="quiz-report-types" checked={"default-quiz-reports" === this.state.report_type_selected}/>
                <label for="dfr" class={"default-quiz-reports" === this.state.report_type_selected ? 'checked' : ''}>{default_quizz_reports_label}</label>
                <input id="cqr" type="radio" value="custom-quiz-reports" name="quiz-report-types" checked={"custom-quiz-reports" === this.state.report_type_selected}/>
                <label for="cqr" class={"custom-quiz-reports" === this.state.report_type_selected ? 'checked' : ''}> {custom_quizz_reports_label}</label>
            </div>
            <div>
                {filterSection}
            </div>
          </div>
        ;
      }
      return body;
    }
}


class ReportFilters extends Component {
    
    constructor(props) {
      super(props);
      window.callStack = [];
      let learners_disabled = true;
      let categories_disabled = true;
      let groups_disabled = true;
      let courses_disabled = false;
      var localized_data_url = '/rp/v1/report-filters-data';
      this.state = {
        isLoaded: false,
        error: null,
        loading_categories:false,
        loading_groups:false,
        loading_courses:false,
        loading_lessons:false,
        loading_topics:false,
        loading_learners:false,
        selected_categories:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        selected_groups:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        selected_courses:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        selected_lessons:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        selected_topics:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
        selected_learners:null,
        categories_disabled:categories_disabled,
        groups_disabled:groups_disabled,
        courses_disabled:courses_disabled,
        lessons_disabled:true,
        topics_disabled:true,
        courses:[],
        default_courses:[],
        learners_disabled:learners_disabled,
        // active_tab:tab_selected,
        start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
        end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
        report_type_selected:'default-course-reports',
        isPro: wisdm_ld_reports_common_script_data.is_pro_version_active,
      };
      window.globalfilters = {'detail' : {
          'start_date':this.state.start_date,
          'end_date':this.state.end_date,
          'selected_categories':this.state.selected_categories.value,
          'selected_groups':this.state.selected_groups.value,
          'selected_courses':this.state.selected_courses.value,
          'selected_lessons':this.state.selected_lessons.value,
          'selected_topics':this.state.selected_topics.value,
          'selected_learners':null!=this.state.selected_learners?this.state.selected_learners.value:null, }};
      if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          localized_data_url += '/?wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
      wp.apiFetch({
        path: localized_data_url  //Replace with the correct API
      }).then(response => {
          window.wisdm_learndash_reports_front_end_script_report_filters = response.wisdm_learndash_reports_front_end_script_report_filters;
          if (false!=wisdm_ld_reports_common_script_data.is_pro_version_active) {
            this.state.learners_disabled = false;
            this.state.categories_disabled = false;
            this.state.groups_disabled = false;
          }
          window.ld_api_settings = wisdm_learndash_reports_front_end_script_report_filters.ld_api_settings;
          let tab_selected = 'quiz-reports'==wisdm_learndash_reports_front_end_script_report_filters.report_type?1:0;
          this.state.active_tab = tab_selected;
          this.getDefaultOptions();
          let url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?per_page=-1';
          if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_ld_reports_common_script_data.is_pro_version_active ) {
              for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
                  url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
              }
          }
          // wp.apiFetch({
          //     path: url  //Replace with the correct API
          // }).then(response => {
                let lock_icon = '';
                let quiz_section_disabled = '';
                if (false==wisdm_ld_reports_common_script_data.is_pro_version_active) {
                  lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports"></span>
                  quiz_section_disabled = 'disabled';
                  }
                // let courses     = this.getCourseListFromJson(response);
                let courses = window.wisdm_learndash_reports_front_end_script_report_filters.courses;
                console.log(courses);
                this.setState(
                        {
                          isLoaded: true,
                          lock_icon:lock_icon,
                          quiz_section_disabled:quiz_section_disabled,
                          categories:wisdm_learndash_reports_front_end_script_report_filters.course_categories,
                          groups:wisdm_learndash_reports_front_end_script_report_filters.course_groups,
                          courses:courses,
                          default_courses:courses,
                          courses_disabled:false,
                          lessons: [],
                          topics:[],
                          learners:[],
                          isPro: wisdm_ld_reports_common_script_data.is_pro_version_active,
                      }); 
              // });
          const defaultOptionsLoaded = new CustomEvent("wrld-default-filters-loaded");
          document.dispatchEvent(defaultOptionsLoaded);
      });

      this.durationUpdated = this.durationUpdated.bind(this);
      this.applyFilters = this.applyFilters.bind(this);
      this.handleTabSelection = this.handleTabSelection.bind(this);
      this.changeCourseReportType = this.changeCourseReportType.bind(this);
    }

    durationUpdated(event) {
        this.setState({start_date:event.detail.startDate, end_date:event.detail.endDate});
        // this.setState({selected_categories:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},selected_groups:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},selected_courses:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},selected_lessons:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},selected_topics:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},selected_learners:null,});
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
  
    getLessonListFromJson(response) {
        let lessonList = [];
        if (response.length==0) {
            return false; //no courses found    
        }

        for (let i = 0; i < response.length; i++) {
             lessonList.push({value:response[i].id, label:response[i].title.rendered});
        }   
        return lessonList;
    }
    
    getTopicListFromJson(response) {
        let topicList = [];
        if (response.length==0) {
            return false; //no courses found    
        }

        for (let i = 0; i < response.length; i++) {
            topicList.push({value:response[i].id, label:response[i].title.rendered});
        }   
        return topicList;
    }

    componentDidMount() { 
    document.addEventListener('duration_updated', this.durationUpdated);
    }
  
    handleCategoryChange = (selectedCategory) => {
        if (null==selectedCategory) {
            this.setState({ selected_categories:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
            this.updateSelectorsFor('category', null);
            this.setState({courses:this.state.default_courses});    
        } else {
            this.setState({ selected_categories:selectedCategory});
            this.updateSelectorsFor('category', selectedCategory.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses']);
        }
    }

    handleAdminGroupChange = (selectedGroup) => {
        let categorySelectedByAdmin = this.state.selected_categories.value;
        if (null==selectedGroup) {
            this.setState({ selected_groups:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} });
            this.updateSelectorsFor('group', null, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?test=1');
            this.setState({courses:this.state.default_courses , categories_disabled: false});
        } else {
            this.setState({ selected_groups:selectedGroup , categories_disabled: true,selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} });
            let callback_url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + selectedGroup.courses_enrolled;
            if(categorySelectedByAdmin != null){
                 //including category filter in url
                 callback_url = callback_url + '&ld_course_category[]=' + categorySelectedByAdmin;
                let url = '';
                if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_ld_reports_common_script_data.is_pro_version_active ) {
                    for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
                        url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
                    }
                }
                callback_url += url;
            }
            this.updateSelectorsFor('group', selectedGroup.value,callback_url );
        }
        //update courses/lessons/topics fetched
        this.setState({ courses_disabled:false });
    }

    handleGroupChange = (selectedGroup) => {
        if (null==selectedGroup || null==selectedGroup.value) {
            this.setState({ selected_groups:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} , selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} });
            this.updateSelectorsFor('group', null, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?test=1');
            this.setState({courses:this.state.default_courses , categories_disabled: false});
        } else {
            this.setState({ selected_groups:selectedGroup , categories_disabled: true ,selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
            this.updateSelectorsFor('group', selectedGroup.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + selectedGroup.courses_enrolled);
        }
        //update courses/lessons/topics fetched
        this.setState({ courses_disabled:false });
    }

    handleCourseChange = (selectedCourse) => {
        if (null==selectedCourse) {
            this.setState({ selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
            this.updateSelectorsFor('course', null); 
        } else {
            this.setState({ selected_courses:selectedCourse});
            this.updateSelectorsFor('course', selectedCourse.value, '/ldlms/v1/' + ld_api_settings['sfwd-lessons'] + '/');
        }
    }

    handleLessonChange = (selectedLesson) => {
        if (null==selectedLesson) {
            this.setState({ selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
            this.updateSelectorsFor('lesson', null);
        } else {
            this.setState({ selected_lessons:selectedLesson});
            this.updateSelectorsFor('lesson', selectedLesson.value, 'ldlms/v1/' + ld_api_settings['sfwd-topic'] + '/');
        }
    }

    handleTopicChange = (selectedTopic) => {
        if (null==selectedTopic) {
            this.setState({ selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
            this.updateSelectorsFor('topic', null);
        } else {
            this.setState({ selected_topics:selectedTopic});
            this.updateSelectorsFor('topic', selectedTopic.value);
        }
    }
  
    handleLearnerChange = (selectedLearner) => {
        if (null==selectedLearner) {
            this.setState({ selected_learners:null, courses_disabled:false, categories_disabled:false});
            // this.updateSelectorsFor('learner', null);
        } else {
            this.setState({ selected_learners:selectedLearner });
            this.setState({
                selected_categories:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
            }); //Clear category, course , lesson, topics selected.
            // this.updateSelectorsFor('learner', selectedLearner.value);
        }
    }

    handleLearnerSearch = (inputString, callback) => {
        // perform a request
        let requestResults = []
        // if (3>inputString.length) {
        //     return callback(requestResults);
        // }
        if ('group_leader'==wisdmLdReportsGetUserType()) {
            let groupUsers = wrldGetGroupAdminUsers();
            groupUsers.forEach(user => {
                if (user.display_name.toLowerCase().includes(inputString.toLowerCase()) || user.user_nicename.toLowerCase().includes(inputString.toLowerCase())) {
                    requestResults.push({value:user.id, label:user.display_name});        
                }
            });
            callback(requestResults);
        } else {
            let callback_path  = '/wp/v2/users/?search='
            callback_path = callback_path + inputString + '&reports=1'
            if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
            wp.apiFetch({
                path: callback_path //Replace with the correct API
             }).then(response => {
                if (false!=response && response.length>0) {
                    response.forEach(element => {
                        requestResults.push({value:element.id, label:element.name});
                    });
                }
                callback(requestResults);
             }).catch((error) => {
                callback(requestResults)
          });
        }
    }

    getDefaultOptions = () => {
        // perform a request
        let requestResults = []
            // if (3>inputString.length) {
            //     return callback(requestResults);
            // }

           
            if ('group_leader'==wisdmLdReportsGetUserType()) {
                let groupUsers = wrldGetGroupAdminUsers();
                groupUsers.forEach(user => {
                    // if (user.display_name.toLowerCase().includes(inputString.toLowerCase()) || user.user_nicename.toLowerCase().includes(inputString.toLowerCase())) {
                    //     requestResults.push({value:user.id, label:user.display_name});        
                    // }

                    requestResults.push({value:user.id, label:user.display_name}); 
                });
                // return requestResults;
                this.setState({default_options: requestResults});    
            } else {
                let callback_path  = '/wp/v2/users/?search='
                callback_path = callback_path + '&per_page=5' + '&reports=1'
                if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
                  callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
                }
                wp.apiFetch({
                    path: callback_path //Replace with the correct API
                 }).then(response => {
                    if (false!=response && response.length>0) {
                        response.forEach(element => {
                            requestResults.push({value:element.id, label:element.name});
                        });
                    }
                    this.setState({default_options: requestResults});
                 }).catch((error) => {
                    return requestResults;
              });
            }
    }

    updateSelectorsFor(element, selection, callback_path='/wp/v2/categories') {
        switch (element) {
            case 'category':
                callback_path = callback_path + '?ld_course_category[]=' + selection+'&per_page=-1';
                let url = '';
                if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_ld_reports_common_script_data.is_pro_version_active ) {
                    for (var i = 0; i < wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length; i++) {
                        url += '&exclude[]=' + wisdm_learndash_reports_front_end_script_report_filters.exclude_courses[i];
                    }
                }
                callback_path += url;
                if (null==selection) {
                    this.setState(
                        {
                        courses:[],lessons:[],topics:[],
                        selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                        lessons_disabled:true, topics_disabled:true,
                    });
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
                            //if selected course is not in the list then clear the field
                            let course_in_the_list = false;
                            let selected_course_id = this.state.selected_courses.value;
                            courses.forEach(function (course) {
                                if (null!=selected_course_id && course.value==selected_course_id) {
                                    course_in_the_list = true;
                                }
                            });
                            if (!course_in_the_list) {
                                this.setState({
                                    selected_courses:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
                                    selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                    selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                    lessons_disabled:true,
                                    topics_disabled:true,
                            });
                            }
                            this.setState(
                                {
                                courses:courses,
                                courses_disabled:false, 
                                loading_courses:false,
                            });

                        }
                     }).catch((error) => {
                        this.setState({
                            selected_courses:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
                            selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            lessons_disabled:true,
                            topics_disabled:true,
                    });
                  });
                }
                break;
            case 'group':
                callback_path = callback_path+'&per_page=-1';
                if (null==selection) {
                    this.setState(
                        {
                        lessons:[],topics:[],
                        selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                        lessons_disabled:true, topics_disabled:true,
                    });
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
                                lessons:[],
                                topics:[],
                                courses_disabled:false, 
                                loading_courses:false,
                                selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            });

                        }else{
                            this.setState(
                                {
                                courses:[],
                                lessons:[],
                                topics:[],
                                course:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                loading_courses:false,
                                selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            });
                        }
                     });
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
                                courses:courses,
                                courses_disabled:false, 
                                loading_courses:false,
                            });

                        }else{
                            this.setState(
                                {
                                courses:[],
                                lessons:[],
                                topics:[],
                                selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                lessons_disabled:true, topics_disabled:true, 
                                loading_courses:false,
                            });
                        }
                     });
                }
                break;
            case 'course':
                callback_path = callback_path + '?course=' + selection+'&per_page=-1';
                if (null==selection) {
                    this.setState(
                        {
                        lessons:[],
                        topics:[],
                        lessons_disabled:true,
                        topics_disabled:true,
                        selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}
                    });
                } else {
                    this.setState({loading_lessons:true});
                    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
                      callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
                    }
                    wp.apiFetch({
                        path: callback_path //Replace with the correct API
                     }).then(response => {
                        let lessons = this.getLessonListFromJson(response);
                        if (false!=lessons && lessons.length>0) {
                            this.setState(
                                {
                                selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                lessons:lessons,
                                lessons_disabled:false, 
                                loading_lessons:false,
                            });

                        } else{
                            this.setState(
                                {
                                selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                                lessons:lessons,
                                lessons_disabled:true, 
                                loading_lessons:false,
                            });
                        }
                     }).catch((error) => {
                        this.setState(
                            {
                            selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            lessons_disabled:false, 
                            loading_lessons:false,
                        });
                  });;
                }
                break;
            case 'lesson':
                callback_path = callback_path + '?course=' + this.state.selected_courses.value + '&lesson=' + selection +'&per_page=-1' ;
                if (null==selection) {
                    this.setState(
                        {
                        topics:[],
                        topics_disabled:true,
                        selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}
                    });
                } else {
                    this.setState({loading_topics:true});
                    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
                      callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
                    }
                    wp.apiFetch({
                        path: callback_path //Replace with the correct API
                     }).then(response => {
                        let topics = this.getTopicListFromJson(response);
                        if (false!=topics && topics.length>0) {
                            this.setState(
                                {
                                selected_topics:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
                                topics:topics,
                                topics_disabled:false, 
                                loading_topics:false,
                            });
                        
                        } else {
                            this.setState(
                                {
                                selected_topics:{value:null,label:__('All', 'learndash-reports-by-wisdmlabs')},
                                topics:topics,
                                topics_disabled:true, 
                                loading_topics:false,
                            });
                        }
                     });
                }
                break;
            case 'topic':
                callback_path = callback_path + '?course_topic=' + selection;
                //Callback & action if required.
                break;
            case 'learner':
                callback_path = callback_path + '?learner=' + selection;
                //Callback & action if required.
                break;
            default:
                break;
        }
    }

    /**
     * Triggers the apply filters event with the
     */
    applyFilters() {

        window.globalfilters = {'detail' : {
            'start_date':this.state.start_date,
            'end_date':this.state.end_date,
            'selected_categories':this.state.selected_categories.value,
            'selected_groups':this.state.selected_groups.value,
            'selected_courses':this.state.selected_courses.value,
            'selected_lessons':this.state.selected_lessons.value,
            'selected_topics':this.state.selected_topics.value,
            'selected_learners':null!=this.state.selected_learners?this.state.selected_learners.value:null, }};

        const applyFilters = new CustomEvent("wisdm-ld-reports-filters-applied", {
            "detail": {
                       'start_date':this.state.start_date,
                       'end_date':this.state.end_date,
                       'selected_categories':this.state.selected_categories.value,
                       'selected_categories_obj':this.state.selected_categories,
                       'selected_groups':this.state.selected_groups.value,
                       'selected_groups_obj':this.state.selected_groups,
                       'selected_courses':this.state.selected_courses.value,
                       'selected_courses_obj':this.state.selected_courses,
                       'selected_lessons':this.state.selected_lessons.value,
                       'selected_lessons_obj':this.state.selected_lessons,
                       'selected_topics':this.state.selected_topics.value,
                       'selected_topics_obj':this.state.selected_topics,
                       'selected_learners':null!=this.state.selected_learners?this.state.selected_learners.value:null,
                       'selected_learners_obj':null!=this.state.selected_learners?this.state.selected_learners:null, 
            }
        });

        if (null==this.state.selected_learners && 'learner-specific-course-reports'==this.state.report_type_selected) {
            alert("Please select a learner from the dropdown");
            return ;
        }
        document.dispatchEvent(applyFilters);
    }

    handleTabSelection(tab_key) {
        this.setState({ active_tab: tab_key });
        let tabSwitchEvent = new CustomEvent("wisdm-ld-reports-report-type-selected", {
            "detail": {'active_reports_tab': 'default-ld-reports','report_type':this.state.report_type_selected,}});
        if(1==tab_key) {
            tabSwitchEvent = new CustomEvent("wisdm-ld-reports-report-type-selected", {
                "detail": {'active_reports_tab': 'quiz-reports',}});
                document.dispatchEvent( new CustomEvent("wisdm-ld-custom-report-type-select", {
                    "detail": {'report_selector': ''}}));
        }
        document.dispatchEvent(tabSwitchEvent);
        if ( 1 == tab_key ) {
            jQuery( '.ld-course-field' ).hide();
        } else {
            jQuery( '.ld-course-field' ).css('display', 'flex');
        }
    }

    changeCourseReportType(event) {
        this.setState({report_type_selected:event.target.value});
        let report_type = '';
        if ('default-course-reports'==event.target.value) {
            report_type = 'default-course-reports';
            this.setState({
                selected_learners:null,
                lessons_disabled:true,
                topics_disabled:true,
                courses:this.state.default_courses ,
                categories_disabled: false,
            });
          
        } else if ('learner-specific-course-reports'==event.target.value) {
            report_type = 'learner-specific-course-reports';
            this.setState({
                selected_groups:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_categories:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                lessons_disabled:true,
                topics_disabled:true,
            });
        }

        global.reportTypeForTooltip = report_type;
        document.dispatchEvent( new CustomEvent("wisdm-ldrp-course-report-type-changed", {
            "detail": {'report_type': report_type}}));
    }

    render() {
        let user_selector_for_demo = '';
        if (wisdm_ld_reports_common_script_data.is_demo) {
            user_selector_for_demo = <div className='demo-pre-selection-options'>
                <span className='try-searching'>(Try Searching)</span> 
                <span className="sample-name" onClick={()=>{this.setState({selected_learners:{value:18, label:'Paul John'}}); }}>Paul John</span>
                <span>Or</span>
                <span className='sample-name' onClick={()=>{this.setState({selected_learners:{value:7, label:'Michelle Schowalter'}}); }}>Michelle Schowalter</span>
                <sapn>)</sapn>
            </div>
        }
        let upgrade_section = '';
        let proclass = 'select-control';
        let wrldplaceholder = __('Search', 'learndash-reports-by-wisdmlabs');
        let quiz_section = <DummyFilters></DummyFilters>;
        let gl_class = ''; 
        let userType = wisdmLdReportsGetUserType();
        if(this.state.isPro){
          quiz_section = <QuizFilters></QuizFilters>;
        }
        if (true!=this.state.isPro) {
            upgrade_section = <div className="wrld-pro-note">
                      <div className="wrld-pro-note-content">
                        <span><b>{__('Note: ', 'learndash-reports-by-wisdmlabs')}</b>{__('Below is the dummy representation of the Learner Reports available in WISDM Reports PRO.', 'learndash-reports-by-wisdmlabs')}</span>
                      </div>
                    </div>
            proclass = 'ldr-pro';
            wrldplaceholder = __('PAUL JOHN', 'learndash-reports-by-wisdmlabs');
            if('group_leader'==userType || 'instructor'==userType){
              gl_class = 'wrld-gl';
            }
          }

      let body = <div></div>;
      if (!this.state.isLoaded) {
        // yet loading
        body =  <WisdmLoader />;
    } else if (this.state.error) {
        // error
        body = <div class="wisdm-learndash-reports-chart-block error">
        <div>{this.state.error.message}</div>
        </div>;
    } else {
        let conditionalCategoryGroupSelector = '';
        let conditionalAdminGroup = '';
        let userType = wisdmLdReportsGetUserType();

        if ('administrator'==userType) {
            conditionalCategoryGroupSelector = null;
            conditionalAdminGroup = 
            <div className={"wisdm-learndash-reports-report-filters admin-group-category-container " + this.state.report_type_selected}>
                <div class="selector admin-cg-selector">
                    <div class="selector-label"> { __('Categories','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div className = { proclass }>
                        <Select 
                            isDisabled={this.state.categories_disabled}
                            isLoading={this.state.loading_categories}  
                            onChange={this.handleCategoryChange}
                            options={this.state.categories}
                            value={this.state.selected_categories}
                            isClearable="true"
                        />
                    </div>
                </div>
                <div class="selector admin-cg-selector">
                    <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                        <Select 
                            isDisabled={this.state.groups_disabled}  
                            isLoading={this.state.loading_groups}
                            onChange={this.handleAdminGroupChange}
                            options={this.state.groups}
                            value={this.state.selected_groups}
                            isClearable="true"
                        />
                    </div>
                </div>
                <div class="selector admin-cg-selector d-none">
                </div>
        </div>;
        } else if('group_leader'==userType) {
            conditionalCategoryGroupSelector = <div class="selector">
            <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
            <div class="select-control">
                <Select 
                    isDisabled={this.state.groups_disabled}  
                    isLoading={this.state.loading_groups}
                    onChange={this.handleGroupChange}
                    options={this.state.groups}
                    value={this.state.selected_groups}
                    isClearable="true"
                />
            </div>
        </div>;
        }
        let tabQR = <Tab>{this.state.lock_icon} <span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Reports ','learndash-reports-by-wisdmlabs')}</span></Tab>;

        if (this.state.quiz_section_disabled=='disabled') {
          if('group_leader'==userType || 'instructor'==userType){
            tabQR = <Tab disabled>{this.state.lock_icon} <span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Reports ','learndash-reports-by-wisdmlabs')}</span></Tab>;            
          }
          else{
            tabQR = <Tab><span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Quiz') + ' ' + __('Reports ','learndash-reports-by-wisdmlabs')}</span></Tab>;
          }
        }
      body = 
      <div class="wisdm-learndash-reports-chart-block" id="wisdm-learndash-report-filters-container">
        
        <Tabs selectedIndex={this.state.active_tab} onSelect={this.handleTabSelection}>
            <TabList>
              <Tab><span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + __(' Reports','learndash-reports-by-wisdmlabs')}</span></Tab>
              {tabQR}
            </TabList>
            <TabPanel>
                <div className='wisdm-learndash-reports-course-report-tools-wrap'>
                    <div class='course-report-by' onChange={this.changeCourseReportType}>
                        
                        <input id="csr" type="radio" value="default-course-reports" name="course-report-types" checked={"default-course-reports" === this.state.report_type_selected}/> 
                        <label for="csr" class={"default-course-reports" === this.state.report_type_selected ? 'checked' : ''}><span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Course') + __(' Specific Reports',  'learndash-reports-by-wisdmlabs')}</span></label>
                        <input id="lsr" className={gl_class} type="radio" value="learner-specific-course-reports" name="course-report-types" checked={"learner-specific-course-reports" === this.state.report_type_selected}/>
                        <label id={gl_class} for="lsr" class={"learner-specific-course-reports" === this.state.report_type_selected ? 'checked' : ''}> {__('Learner Specific Reports',  'learndash-reports-by-wisdmlabs')}</label>
                    </div>
                    { "learner-specific-course-reports" === this.state.report_type_selected ? '' : conditionalAdminGroup}
                    <div className={"wisdm-learndash-reports-report-filters " + this.state.report_type_selected}>
                        {conditionalCategoryGroupSelector}
                        <div class="selector">
                            <div class="selector-label">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses')}</div>
                            <div class="select-control">
                                <Select
                                    isDisabled={this.state.courses_disabled}
                                    isLoading={this.state.loading_courses}
                                    onChange={this.handleCourseChange}
                                    options={this.state.courses}
                                    value={this.state.selected_courses}
                                    isClearable="true"
                                />
                            </div>
                        </div>
                        <div class="selector">
                            <div class="selector-label">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Lessons')}</div>
                            <div class="select-control">
                                <Select
                                    isDisabled={this.state.lessons_disabled}
                                    isLoading={this.state.loading_lessons}  
                                    onChange={this.handleLessonChange}
                                    options={this.state.lessons}
                                    value={this.state.selected_lessons}
                                    isClearable="true"
                                />
                            </div>
                        </div>
                        <div class="selector">
                            <div class="selector-label">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Topics')}</div>
                            <div class="select-control">
                                <Select 
                                    isDisabled={this.state.topics_disabled}
                                    isLoading={this.state.loading_topics}
                                    onChange={this.handleTopicChange}
                                    options={this.state.topics}
                                    value={this.state.selected_topics}
                                    isClearable="true"
                                />
                            </div>
                        </div>
                        <div class="selector lr-apply">
                            <div class="apply-filters">
                                <button onClick={this.applyFilters}>{__('Apply', 'learndash-reports-by-wisdmlabs')}</button>
                            </div>
                        </div>
                    </div>
                    <div className={"wisdm-learndash-reports-report-filters-for-users " + this.state.report_type_selected}>
                    {upgrade_section}
                    <div class="selector lr-learner">
                            <div class="selector-label">{__('Learners','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                                {user_selector_for_demo}
                            </div>
                            <div className = { proclass }>
                            <AsyncSelect
                                components={{ DropdownIndicator:() => null, IndicatorSeparator:() => null, NoOptionsMessage: (element) => {return element.selectProps.inputValue.length>2?__(' No learners found for the search string\'', 'learndash-reports-by-wisdmlabs') + element.selectProps.inputValue +'\'':__(' Type 3 or more letters to search', 'learndash-reports-by-wisdmlabs') }}}
                                placeholder={wrldplaceholder}
                                isDisabled={this.state.learners_disabled}
                                value={this.state.selected_learners}
                                loadOptions={this.handleLearnerSearch}
                                onChange={this.handleLearnerChange}
                                isClearable="true"
                                defaultOptions={this.state.default_options}
                            />
                            </div>
                        </div>
                        <div class="selector">
                            <div class="apply-filters">
                                <button onClick={this.applyFilters}>{__('Apply', 'learndash-reports-by-wisdmlabs')}</button>
                                <span className="wrld-applied"><i class="dashicons dashicons-saved"></i>{__('Applied', 'learndash-reports-by-wisdmlabs')}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </TabPanel>
            <TabPanel>
                {quiz_section}
            </TabPanel>
        </Tabs>
        </div>;
    } 
  
      return (body);
    }
}

export default ReportFilters;

    /**
     * Based on the current user roles aray this function desides wether a user is a group
     * leader or an Administrator and returns the same.
     */
    function wisdmLdReportsGetUserType() {
        let userRoles = wisdm_ld_reports_common_script_data.user_roles;
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
        return filtered_courses;
    }

    function getQuizesByCoursesAccessible(courseList, quizes) {
        let user_type = wisdmLdReportsGetUserType();
        let filtered_quizes = [];
        if('group_leader'==user_type) {
            let courseIds = Array();
            courseList.forEach(function(course){
                courseIds.push(course.value);
            });

            quizes.forEach(function(quiz){
                if (courseIds.includes(parseInt(quiz.course_id))) {
                    filtered_quizes.push(quiz);
                }
            });

        } else if('instructor'==user_type){
            filtered_quizes = quizes;
        } else {
            filtered_quizes=quizes;
        }
        return filtered_quizes;
    }

    function getSelectionByValueId(selectionId, list=[]) {
        let selectedItem = {value:-1, label:__('All', 'learndash-reports-by-wisdmlabs')};
        if (-1==selectionId) {
            return selectedItem;
        } 

        if (list.length>0) {
            list.forEach(function(item){
                if (selectionId==item.value) {
                    selectedItem = item;
                }
            });
        }
        return selectedItem;
    }

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

document.addEventListener("DOMContentLoaded", function(event) {
    let elem = document.getElementsByClassName('wisdm-learndash-reports-report-filters front');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(ReportFilters), elem[0]); 
    }
});

