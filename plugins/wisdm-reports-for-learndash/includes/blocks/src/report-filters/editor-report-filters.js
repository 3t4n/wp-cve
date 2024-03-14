/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType, RichText, source  } from '@wordpress/blocks';
import './editor.scss';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';
var moment = require('moment');
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import DummyFilters from '../dummy-quiz-reports/index.js';
import 'react-tabs/style/react-tabs.css';

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element';
import React, { Component, CSSProperties } from "react";
import WisdmLoader from '../commons/loader/index.js';
import { array } from 'prop-types';
import Modal, {closeStyle} from 'simple-react-modal';
import { useBlockProps } from '@wordpress/block-editor';

window.ld_api_settings = {
    'sfwd-courses':'sfwd-courses', 
    'sfwd-lessons':'sfwd-lessons', 
    'sfwd-topic':'sfwd-topic', 
    'sfwd-quiz':'sfwd-quiz', 
    'sfwd-question':'sfwd-question', 
    'users':'users', 
    'groups':'groups', 
}
const icon = (<svg  version='1.0'
                    xmlns='http://www.w3.org/2000/svg'
                    width='24.000000pt'
                    height='24.000000pt'
                    viewBox='0 0 24.000000 24.000000'
                    preserveAspectRatio='xMidYMid meet' >
                <path d="M2.9,21.6c0,1.1,0.9,2,1.9,2h14.4c1.1,0,1.9-0.9,1.9-2V3.7c0-1.1-0.9-2-1.9-2h-2.6l-0.1-0.1c-0.7-0.8-1.7-1.2-2.7-1.2h-3.7
                    c-1,0-1.9,0.4-2.6,1.2L7.4,1.7H4.8c-1.1,0-2,0.9-2,2V21.6z M7.8,3.2c0.4-1,1.4-1.7,2.3-1.7h3.7c1,0,2,0.7,2.3,1.7l0.1,0.2H7.7
                    L7.8,3.2z M4.1,3.7c0-0.5,0.4-0.9,0.8-0.9h2L6.7,3C6.7,3.3,6.5,3.7,6.5,4c0,0.4,0.2,0.6,0.6,0.6h9.7c0.3,0,0.5-0.3,0.5-0.6
                    c0-0.3,0-0.6-0.1-0.9l-0.1-0.2h2c0.5,0,0.8,0.4,0.8,0.9v17.9c0,0.5-0.4,0.9-0.8,0.9H4.8c-0.5,0-0.8-0.4-0.8-0.9V3.7z"/>
                <path d="M8.8,14l-1.1,1.1l-0.4-0.4c-0.1-0.1-0.3-0.1-0.4-0.1c-0.1,0-0.2,0-0.4,0.1c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0,0l0.6,0.7c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l1.4-1.4c0.2-0.1,0.3-0.3,0.3-0.4c0-0.1,0-0.3-0.2-0.4
                    c-0.1-0.1-0.3-0.2-0.5-0.2C8.9,13.9,8.8,13.9,8.8,14z"/>
                <path d="M11.6,14.6c-0.3,0-0.5,0.3-0.5,0.6s0.2,0.6,0.5,0.6h5.5c0.3,0,0.5-0.3,0.5-0.6c0-0.4-0.2-0.6-0.5-0.6H11.6z"/>
                <path d="M8.8,17.9L7.7,19l-0.4-0.4c-0.1-0.1-0.3-0.1-0.4-0.1s-0.2,0-0.4,0.1c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0.7,0.7c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l1.4-1.5c0.1-0.1,0.2-0.2,0.2-0.3c0-0.2,0-0.4-0.2-0.5S9.2,17.7,9,17.8
                    C8.9,17.8,8.8,17.8,8.8,17.9z"/>
                <path d="M11.6,18.5c-0.3,0-0.5,0.3-0.5,0.6s0.2,0.6,0.5,0.6h5.5c0.3,0,0.5-0.3,0.5-0.6c0-0.4-0.2-0.6-0.5-0.6H11.6z"/>
                <path d="M16,9.8c0.3,0,0.5-0.3,0.5-0.6l0-1.7c0-0.1,0-0.2,0-0.3C16.4,7,16.3,7,16.1,7h-1.7c-0.3,0-0.5,0.3-0.5,0.6
                    c0,0.3,0.2,0.6,0.5,0.6l0.4,0L13,10.1l-2.2-2.2c-0.3-0.2-0.6-0.2-0.9-0.1l-2.5,2.5c-0.1,0.1-0.2,0.3-0.2,0.5c0,0.1,0.1,0.2,0.2,0.3
                    l0,0c0.1,0.1,0.3,0.2,0.5,0.2c0.1,0,0.2-0.1,0.3-0.2l2-2.1l2.2,2.1c0.1,0.1,0.3,0.1,0.4,0.1s0.2,0,0.4-0.1l2.4-2.4l0,0.4
                    C15.5,9.4,15.7,9.8,16,9.8z"/>
            </svg>
);

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
        let quiz_section_disabled = 'disabled';
        let report_type_selected= 'default-quiz-reports';
        if (false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active) {
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
        let selected_course = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_group = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        let selected_quiz = {value:-1,label:__('All', 'learndash-reports-by-wisdmlabs')};
        if (undefined!=wisdm_learndash_reports_front_end_script_report_filters.qre_filters) {
            let qre_filters = wisdm_learndash_reports_front_end_script_report_filters.qre_filters;
            let selected_course_id = undefined!=qre_filters.course_filter&&qre_filters.course_filter>0?parseInt(qre_filters.course_filter):-1;
            selected_course = getSelectionByValueId(selected_course_id, courses);
            let selected_group_id = undefined!=qre_filters.group_filter&&qre_filters.group_filter>0?parseInt(qre_filters.group_filter):-1;
            selected_group = getSelectionByValueId(selected_group_id, this.default_groups);
            let selected_quiz_id = undefined!=qre_filters.quiz_filter&&qre_filters.quiz_filter>0?parseInt(qre_filters.quiz_filter):-1;
            selected_quiz = getSelectionByValueId(selected_quiz_id, this.default_quizes);
        }

        if ('administrator'==userType) { 
            }
        else if('group_leader'==userType) {
            categories_disabled = true;
            groups_disabled = false;
        }

        this.state = {
          isLoaded: false,
          error: null,
          report_type_selected:report_type_selected,
          courses_disabled:'',
          groups_disabled:groups_disabled,
          quizes_disabled:quizes_disabled,
          categories:wisdm_learndash_reports_front_end_script_report_filters.course_categories,
          courses:courses,
          groups:this.default_groups,
          quizes:this.default_quizes,
          show_quiz_filter_modal:false,
          custom_report_fields:[],
          selected_courses:selected_course,
          selected_groups:selected_group,
          selected_quizes:selected_quiz,
          selectedElementsInDefaultFilter:null,
          start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
          end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
          selectedValue: report_preferences.settings,
          selectedFields:report_preferences.settings,
          selectedCourseTitle: report_preferences.selected_course_title,
          selectedGroupTitle: report_preferences.selected_group_title,
          selectedQuizTitle: report_preferences.selected_quiz_title,
        }; 

        this.durationUpdated               = this.durationUpdated.bind(this);
        this.onQuizReportViewChange        = this.onQuizReportViewChange.bind(this);
        this.handleQuizFilterDefaultSearch = this.handleQuizFilterDefaultSearch.bind(this);
        this.openCustomizePreviewModal     = this.openCustomizePreviewModal.bind(this);
        this.closeCustomizePreviewModal    = this.closeCustomizePreviewModal.bind(this);
        this.handleQuizSearch              = this.handleQuizSearch.bind(this);
        this.handleCourseSearch            = this.handleCourseSearch.bind(this);
        this.handleGroupSearch             = this.handleGroupSearch.bind(this);
        this.handleDefaultQuizFilterChange = this.handleDefaultQuizFilterChange.bind(this);
        this.handleQuizCourseChange        = this.handleQuizCourseChange.bind(this);
        this.handleQuizGroupChange         = this.handleQuizGroupChange.bind(this);
        this.handleQuizChange              = this.handleQuizChange.bind(this);
        this.applyQuizFilters              = this.applyQuizFilters.bind(this);
        this.previewCustomReport           = this.previewCustomReport.bind(this);
        this.previewReport                 = this.previewReport.bind(this);
    }

    componentDidMount() { 
        document.addEventListener('duration_updated', this.durationUpdated);
    }

    durationUpdated(event) {
        this.setState({start_date:event.detail.startDate, end_date:event.detail.endDate});
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
    }


    handleCourseSearch(inputString, callback) {
         // perform a request
         let callback_path  = '/ldlms/v1/sfwd-courses/?search='
         let requestResults = []
         if (2<inputString.length) {
             callback_path = callback_path + inputString
             if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
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
            if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
              callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
            }
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
           callback_path = callback_path + inputString
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

    openCustomizePreviewModal() {
        this.setState({
            show_quiz_filter_modal:true,
        });
    }

    closeCustomizePreviewModal(){
        this.setState({
            show_quiz_filter_modal:false,
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

        // fields_selected['category_filter'] = this.state.selected_categories.value;
        fields_selected['course_filter'] = this.state.selected_courses.value;
        fields_selected['group_filter'] = this.state.selected_groups.value;
        fields_selected['quiz_filter'] = this.state.selected_quizes.value;

        this.setState({selectedFields:fields_selected});

        const customQuizReport = new CustomEvent("wisdm-ld-reports-custom-quiz-report-filters-applied", {
            "detail": {
                        'start_date':this.state.start_date,
                        'end_date':this.state.end_date,
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

    render() {
        let body = '';
        //Default Filers
        let filterSection = 
            <div class="quiz-eporting-filter-section default-filters">
                <div class="selector search-input">
                    <div class="selector-label">{__('Search','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                    <div class="select-control">
                    <AsyncSelect
                        components={{ DropdownIndicator:() => null, IndicatorSeparator:() => null, NoOptionsMessage: (element) => {return element.selectProps.inputValue.length>2?__(' No learners/quizzes/courses found for the search string \'' + element.selectProps.inputValue +'\'', 'learndash-reports-by-wisdmlabs'):__(' Type 3 or more letters to search', 'learndash-reports-by-wisdmlabs') } }}
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
            </div>;
        //Custom Filers
        if ("custom-quiz-reports"===this.state.report_type_selected) {
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
            filterSection = 
            <div class="quiz-eporting-filter-section custom-filters">
                <div class="help-section">
                    <p>{__('Customize quiz reports view helps you analyze exam results in more detailed manner, please select the filters you need from th form below, and click the "Preview Report" to display the reports here.',  'learndash-reports-by-wisdmlabs')}</p>   
                    <p class="note"><b>{__('Note:',  'learndash-reports-by-wisdmlabs')}</b>{__(' It may take a while for a report to be generated depending of the amount of the data selected.',  'learndash-reports-by-wisdmlabs')}</p>
                </div>    
                {customFilterDropDowns}
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
                                    <div class="selector-label">{__('Custom Report Fields','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}</div>
                                    <div class="select-control">
                                        <Checkbox isChecked="yes" always_checked="yes" name="user_name" label={__('User Name',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="quiz_title" label={__('Exam Title',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="course_title" label={__('Course', 'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.course_category} name="course_category" label={__('Course Category','learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.group_name} name="group_name" label={__('Group',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.user_email} name="user_email" label={__('User Email',   'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked={this.state.selectedFields.quiz_status} name="quiz_status" label={__('Exam Status',      'learndash-reports-by-wisdmlabs')}/>
                                        <Checkbox isChecked="yes" always_checked="yes" name="quiz_category" label={__('Exam Category',      'learndash-reports-by-wisdmlabs')}/>
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
     //  wp.apiFetch({
     //    path: '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?per_page=-1'  //Replace with the correct API
     // }).then(response => {
     //      let lock_icon = '';
     //      let quiz_section_disabled = '';
     //      if (false==wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active) {
     //        lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports"></span>
     //        quiz_section_disabled = 'disabled';
     //        }
     //      let courses     = this.getCourseListFromJson(response);
     //      this.setState(
     //              {
     //                isLoaded: true,
     //                lock_icon:lock_icon,
     //                quiz_section_disabled:quiz_section_disabled,
     //                categories:wisdm_learndash_reports_front_end_script_report_filters.course_categories,
     //                groups:wisdm_learndash_reports_front_end_script_report_filters.course_groups,
     //                courses:courses,
     //                default_courses:courses,
     //                courses_disabled:false,
     //                lessons: [],
     //                topics:[],
     //                learners:[]
     //            }); 
     //    });
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

    handleGroupChange = (selectedGroup) => {
        if (null==selectedGroup) {
            this.setState({ selected_groups:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} });
            this.updateSelectorsFor('group', null);
            this.setState({courses:this.state.default_courses});
        } else {
            this.setState({ selected_groups:selectedGroup });
            this.updateSelectorsFor('group', selectedGroup.value, '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + selectedGroup.courses_enrolled);
        }
        //update courses/lessons/topics fetched
        this.setState({ courses_disabled:false });
    }

    handleAdminGroupChange = (selectedGroup) => {
        let categorySelectedByAdmin = this.state.selected_categories.value;
        if (null==selectedGroup) {
            this.setState({ selected_groups:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')} });
            this.updateSelectorsFor('group', null);
            this.setState({courses:this.state.default_courses , categories_disabled: false});
        } else {
            this.setState({ selected_groups:selectedGroup , categories_disabled: true });
            let callback_url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?include=' + selectedGroup.courses_enrolled;
            if(categorySelectedByAdmin != null){
                 //including category filter in url
                 callback_url = callback_url + '&ld_course_category[]=' + categorySelectedByAdmin;
                let url = '';
                if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active ) {
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
            callback_path = callback_path + inputString
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
                callback_path = callback_path + '?ld_course_category[]=' + selection;
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
                callback_path = callback_path;
                if (null==selection) {
                    this.setState(
                        {
                        lessons:[],topics:[],
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
                            this.setState(
                                {
                                courses:courses,
                                courses_disabled:false, 
                                loading_courses:false,
                            });

                        }
                     });
                }
                break;
            case 'course':
                callback_path = callback_path + '?course=' + selection;
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
                callback_path = callback_path + '?course=' + this.state.selected_courses.value + '&lesson=' + selection;
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
        const applyFilters = new CustomEvent("wisdm-ld-reports-filters-applied", {
            "detail": {
                       'start_date':this.state.start_date,
                       'end_date':this.state.end_date,
                       'selected_categories':this.state.selected_categories.value,
                       'selected_groups':this.state.selected_groups.value,
                       'selected_courses':this.state.selected_courses.value,
                       'selected_lessons':this.state.selected_lessons.value,
                       'selected_topics':this.state.selected_topics.value,
                       'selected_learners':null!=this.state.selected_learners?this.state.selected_learners.value:'', }});

        if (null==this.state.selected_learners && 'learner-specific-course-reports'==this.state.report_type_selected) {
            alert("Please select a learner from the dropdown");
            return ;
        }
        document.dispatchEvent(applyFilters);
    }

    handleTabSelection(tab_key) {
        this.setState({ active_tab: tab_key });
        let tabSwitchEvent = new CustomEvent("wisdm-ld-reports-report-type-selected", {
            "detail": {'active_reports_tab': 'default-ld-reports',}});
        if(1==tab_key) {
            tabSwitchEvent = new CustomEvent("wisdm-ld-reports-report-type-selected", {
                "detail": {'active_reports_tab': 'quiz-reports',}});
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
                                components={{ DropdownIndicator:() => null, IndicatorSeparator:() => null, NoOptionsMessage: (element) => {return element.selectProps.inputValue.length>2?__(' No learners found for the search string \'' + element.selectProps.inputValue +'\'', 'learndash-reports-by-wisdmlabs'):__(' Type 3 or more letters to search', 'learndash-reports-by-wisdmlabs') }}}
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


registerBlockType( 'wisdm-learndash-reports/report-filters', {

    title: __( 'Report Tools', 'learndash-reports-by-wisdmlabs' ),
    description: __( 'A block with duration selectors for the LearnDash reports', 'learndash-reports-by-wisdmlabs' ),
    category: 'wisdm-learndash-reports',
    className: 'learndash-reports-by-wisdmlabs-report-filters',
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
                    <ReportFilters></ReportFilters>
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
                    <div class="wisdm-learndash-reports-report-filters front">
                       
                    </div>
                </div>
            );
        },
} );



