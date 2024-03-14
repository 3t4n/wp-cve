import "./index.scss";
import { Tab, Tabs, TabList, TabPanel } from "react-tabs";
import Select from "react-select";
import "react-tabs/style/react-tabs.css";
import { __ } from "@wordpress/i18n";
import React, { Component, CSSProperties } from "react";
import WisdmLoader from "../commons/loader/index.js";
import ComponentDatepicker from "./component-date-filter.js";
import Modal, {closeStyle} from 'simple-react-modal';
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

class StudentFilters extends React.Component {
  constructor(props) {
    super(props);
    let courses_disabled = false;
    let error = null;
    if (this.getUserType()) {
      error = {
        message: __(
          "Sorry you are not allowed to access this block, please check if you have proper access permissions",
          "learndash-reports-by-wisdmlabs"
        ),
      };
    }

    this.state = {
      isLoaded: false,
      isLoggedIn: false,
      error: error,
      loading_courses: false,
      loading_quizzes: false,
      selected_courses: {
        value: null,
        label: __("All", "learndash-reports-by-wisdmlabs"),
      },
      selected_quiz: {
        value: null,
        label: __("All", "learndash-reports-by-wisdmlabs"),
      },
      show_quiz_filter_modal:false,
      courses_disabled: courses_disabled,
      courses: [],
      default_courses: [],
      quizzes: [],
      default_quizzes: [],
      quiz_disabled: courses_disabled,
      start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
      end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
      selectedFields:report_preferences.settings,
      user_id:wisdm_learndash_reports_front_end_script_student_table.current_user.ID,
    };
    if ( typeof props.parent.course_label !== 'undefined' ) {
      this.state.selected_courses = {value: props.parent.course, label: props.parent.course_label};
      this.state.selected_quiz = {value: props.parent.quiz, label: props.parent.quiz_label};
      this.state.courses = props.parent.courses;
      this.state.quizzes = props.parent.quizzes;
      this.state.start_date = props.parent.start_date;
      this.state.end_date = props.parent.end_date;
    }

    var localized_data_url = '/rp/v1/report-filters-data';
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      localized_data_url += '/?wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
        path: localized_data_url  //Replace with the correct API
      }).then(response => {
          window.wisdm_learndash_reports_front_end_script_report_filters = response.wisdm_learndash_reports_front_end_script_report_filters;
          window.ld_api_settings = wisdm_learndash_reports_front_end_script_report_filters.ld_api_settings;
      });
    this.applyStudentFilters              = this.applyStudentFilters.bind(this);
    this.previewCustomReport           = this.previewCustomReport.bind(this);
    this.previewReport                 = this.previewReport.bind(this);
    this.openCustomizePreviewModal     = this.openCustomizePreviewModal.bind(this);
    this.closeCustomizePreviewModal    = this.closeCustomizePreviewModal.bind(this);
    this.dateUpdated                   = this.dateUpdated.bind(this);
  }
  getUserType() {
    if(wisdm_learndash_reports_front_end_script_student_table.current_user.ID == 0){
      return true;
    }
    return false;
  }

  dateUpdated(event) {
    this.setState({start_date:event.detail.startDate,end_date:event.detail.endDate});
}

  applyStudentFilters() {
    const applyFilters = new CustomEvent("wisdm-ld-reports-student-filters-applied", {
      "detail": {
                 'start_date':this.state.start_date,
                 'end_date':this.state.end_date,
                 'selected_quiz':this.state.selected_quiz,
                 'selected_courses':this.state.selected_courses,
                 'user_id':this.state.user_id,
                 'courses':this.state.courses,
                 'quizzes':this.state.quizzes,
                 }});


  document.dispatchEvent(applyFilters);
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
previewReport() {
  this.previewCustomReport();
}

previewCustomReport() {
  // let fields_selected = {};

  // const customQuizReport = new CustomEvent("wisdm-ld-reports-custom-quiz-report-filters-applied", {
  //     "detail": {
  //                 'start_date':this.state.start_date,
  //                 'end_date':this.state.end_date,
  //                 'course_completion_dates_from':this.state.start_date,
  //                 'course_completion_dates_to':this.state.end_date,
  //                 'fields_selected':fields_selected,
  //                 'selected_courses': this.state.selected_courses.value,
  //                 'selected_quizes': this.state.selected_quiz.value,
  //             }});
  // document.dispatchEvent(customQuizReport);
}

  getCourseListFromJson(response) {
    let courseList = [];
    if (response.length==0) {
        return courseList; //no courses found    
    }
    
    for (let i = 0; i < response.length; i++) {
         courseList.push({value:response[i].id, label:response[i].title.rendered});
    }
//courseList = getCoursesByGroups(courseList);   
    return courseList;
}

  componentDidMount() {
    document.addEventListener('date_updated', this.dateUpdated);
    if(wisdm_learndash_reports_front_end_script_student_table.courses_enrolled.length > 0){
    let url = '/ldlms/v1/' + ld_api_settings['sfwd-courses'] + '?per_page=-1';
    if ( wisdm_learndash_reports_front_end_script_student_table.courses_enrolled.length > 0 && false!=wisdm_learndash_reports_front_end_script_student_table.is_pro_version_active ) {
        for (var i = 0; i < wisdm_learndash_reports_front_end_script_student_table.courses_enrolled.length; i++) {
            url += '&include[]=' + wisdm_learndash_reports_front_end_script_student_table.courses_enrolled[i];
        }
    }
    if ( this.state.courses.length === 0 ) {
      if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
          url += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
        }
      wp.apiFetch({
          path: url  //Replace with the correct API
      }).then(response => {
            let lock_icon = '';
            let quiz_section_disabled = '';
            if (false==wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active) {
              lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports"></span>
              quiz_section_disabled = 'disabled';
              }
            let courses     = this.getCourseListFromJson(response);
            if ( this.state.quizzes.length === 0 ) {
              let quizes      = getQuizesByCoursesAccessible(courses, wisdm_learndash_reports_front_end_script_report_filters.quizes);
              this.setState({quizzes: quizes});
            }
            this.setState(
                    {
                      isLoaded: true,
                      courses:courses,
                      default_courses:courses,
                      courses_disabled:false,
                      lessons: [],
                      topics:[],
                      learners:[]
                  }); 
          });
    } else{
      this.setState(
        {
          isLoaded: true,
          courses_disabled:false,
      }); 
    }
    }else{
      this.setState(
        {
          isLoaded: true,
          courses_disabled:false,
      }); 
    }
  }

  getAllCourses(){
    callback_path = callback_path + '?ld_course_category[]=' + selection+'&per_page=-1';
            let url = '';
            if ( wisdm_learndash_reports_front_end_script_report_filters.exclude_courses.length > 0 && false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active ) {
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
  //update dropdowns
  updateSelectorsFor(element, selection, callback_path='/wp/v2/categories') {
    callback_path = callback_path + '?course=' + selection+'&per_page=-1';
            if (null==selection) {
                this.setState(
                    {
                    quizzes:wisdm_learndash_reports_front_end_script_student_table.quizes,
                    selected_lessons:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}, selected_topics:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}
                });
            } else {
                this.setState({loading_quizzes:true});
                
                    let course_quizes = [];
                    let quiz_list = wisdm_learndash_reports_front_end_script_student_table.quizes;
                    quiz_list.forEach(function(quiz){
                        if (quiz.course_id==selection) {
                            course_quizes.push(quiz);
                        }
                    });

                    if (false!=course_quizes && course_quizes.length>0) {
                        this.setState(
                            {
                            selected_quiz:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            quizzes:course_quizes,
                            loading_quizzes:false, 
                            quiz_disabled:false,
                        });

                    } else{
                        this.setState(
                            {
                            selected_quiz:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                            quizzes:course_quizes,
                            loading_quizzes:false,
                        });
                    }
            }
}

  //handle course change
  handleCourseChange = (selectedCourse) => {
    if (null==selectedCourse) {
        this.setState({ selected_courses:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
        this.updateSelectorsFor('course', null);
    } else {
        this.setState({ selected_courses:selectedCourse});
        this.updateSelectorsFor('course', selectedCourse.value, '/ldlms/v1/' + ld_api_settings['sfwd-quiz'] + '/');
    }
  };

  handleQuizChange = (selectedCourse) => {
    if (null==selectedCourse) {
        this.setState({ selected_quiz:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}});
    } else {
      this.setState({ selected_quiz:{value:selectedCourse.value, label:__(selectedCourse.label, 'learndash-reports-by-wisdmlabs')}});
    }
  };

  render() {
    let body = <div></div>;
    if (this.state.error) {
      // error
      body = '';
  } else if (!this.state.isLoaded) {
      // yet loading
      body = <WisdmLoader />;
    } else {
      body = (
        <div class="user-filter-section">
          <div className="user-filter-selectors">
            <div class="selector">
              <div class="selector-label">
                {wisdm_reports_get_ld_custom_lebel_if_avaiable("Courses")}
              </div>
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
              <div class="selector-label">
                {wisdm_reports_get_ld_custom_lebel_if_avaiable("Quizzes")}
              </div>
              <div class="select-control">
                <Select
                  isDisabled={this.state.quiz_disabled}
                  isLoading={this.state.loading_quizzes}
                  onChange={this.handleQuizChange}
                  options={this.state.quizzes}
                  value={this.state.selected_quiz}
                  isClearable="true"
                />
              </div>
            </div>

            <div class="selector">
              <div class="selector-label">
              {__('DATE OF ATTEMPT', 'learndash-reports-by-wisdmlabs')}
              </div>
              <div class="select-control">
                <ComponentDatepicker
                  start={this.state.start_date}
                  end={this.state.end_date}
                ></ComponentDatepicker>
              </div>
            </div>
          </div>

          <div class="filter-buttons">
                <div class="filter-button-container">
                    {/* <Modal  show={this.state.show_quiz_filter_modal}
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
                    </Modal> */}
                    {/* <button class="button-customize-preview" onClick={this.openCustomizePreviewModal}>{__('CUSTOMIZE REPORT', 'learndash-reports-by-wisdmlabs')}</button> */}
                    <button class="button-quiz-preview" onClick={this.applyStudentFilters}>{__('APPLY FILTERS', 'learndash-reports-by-wisdmlabs')}</button>
                </div>
            </div>
        </div>
      );
    }

    return body;
  }
}

export default StudentFilters;

/**
 * Based on the current user roles aray this function desides wether a user is a group
 * leader or an Administrator and returns the same.
 */

// document.addEventListener("DOMContentLoaded", function (event) {
//   let elem = document.getElementsByClassName(
//     "wisdm-learndash-reports-student-filters front"
//   );
//   if (elem.length > 0) {
//     ReactDOM.render(React.createElement(StudentFilters), elem[0]);
//   }
// });
