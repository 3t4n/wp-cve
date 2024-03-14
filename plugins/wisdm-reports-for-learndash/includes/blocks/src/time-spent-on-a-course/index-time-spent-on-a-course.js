import "./index.scss";
import ApexCharts from "apexcharts";
import ChartSummarySection from "../commons/chart-summary/index.js";
import WisdmFilters from "../commons/filters/index.js";
import WisdmLoader from "../commons/loader/index.js";
import DummyReports from "../commons/dummy-reports/index.js";
import React, { Component } from "react";
import Chart from "react-apexcharts";
import { __ } from "@wordpress/i18n";
import Select from "react-select";
import Modal, { closeStyle } from "simple-react-modal";
import DurationFilter from "./component-duration-filter.js";
import LocalFilters from "./component-local-filters.js";
import TimeSpentTable from "./component-time-spent-table.js";
import Cookies from 'js-cookie';

// var ld_api_settings =
//   wisdm_learndash_reports_front_end_script_report_filters.ld_api_settings;

window.ld_api_settings = {
  'sfwd-courses':'sfwd-courses', 
  'sfwd-lessons':'sfwd-lessons', 
  'sfwd-topic':'sfwd-topic', 
  'sfwd-quiz':'sfwd-quiz', 
  'sfwd-question':'sfwd-question', 
  'users':'users', 
  'groups':'groups', 
}

function wisdmLDRConvertTime(seconds) {
  var hours = Math.floor(seconds / 3600);
  var minutes = Math.floor((seconds % 3600) / 60);
  var seconds = Math.floor((seconds % 3600) % 60);
  if (hours < 10) {
    hours = "0" + hours;
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }
  if (seconds < 10) {
    seconds = "0" + seconds;
  }
  if (!!hours) {
    if (!!minutes) {
      return `${hours}:${minutes}:${seconds}`;
    } else {
      return `${hours}:00:${seconds}`;
    }
  }
  if (!!minutes) {
    return `00:${minutes}:${seconds}`;
  }
  return `00:00:${seconds}`;
}
/**
 * If user is the group admin this function returns an array of unique
 * user ids which are enrolled in the groups accessible to the current user.
 */
function wrldGetGroupAdminUsers() {
  let user_accessible_groups =
    wisdm_learndash_reports_front_end_script_report_filters.course_groups;

  let allGroupUsers = Array();
  let includedUserIds = Array();
  if (user_accessible_groups.length < 1) {
    return allGroupUsers;
  }

  user_accessible_groups.forEach(function (group) {
    if (!("group_users" in group)) {
      return;
    }
    let groupUsers = group.group_users;
    groupUsers.forEach(function (user) {
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
  let userRoles =
    wisdm_learndash_reports_front_end_script_report_filters.user_roles;
  if ("object" == typeof userRoles) {
    userRoles = Object.keys(userRoles).map((key) => userRoles[key]);
  }
  if (undefined == userRoles || userRoles.length == 0) {
    return null;
  }
  if (userRoles.includes("administrator")) {
    return "administrator";
  } else if (userRoles.includes("group_leader")) {
    return "group_leader";
  } else if (userRoles.includes("wdm_instructor")) {
    return "instructor";
  }
  return null;
}

function getCoursesByGroups(courseList) {
  let user_type = wisdmLdReportsGetUserType();
  let filtered_courses = [];
  if ("group_leader" == user_type) {
    let course_groups =
      wisdm_learndash_reports_front_end_script_report_filters.course_groups;
    let group_course_list = [];
    if (course_groups.length > 0) {
      course_groups.forEach(function (course_group) {
        if (!("courses_enrolled" in course_group)) {
          return;
        }
        let courses = course_group.courses_enrolled;
        courses.forEach(function (course_id) {
          if (!group_course_list.includes(course_id)) {
            group_course_list.push(course_id);
          }
        });
      });
    }

    if (group_course_list.length > 0) {
      courseList.forEach(function (course) {
        if (group_course_list.includes(course.value)) {
          filtered_courses.push(course);
        }
      });
    }
  } else if ("instructor" == user_type) {
    filtered_courses =
      wisdm_learndash_reports_front_end_script_report_filters.courses;
  } else {
    filtered_courses = courseList;
  }

  let iSAllIncluded = true;
  for (let i in filtered_courses) {
    if (i["value"] == null) {
      iSAllIncluded = false;
    }
  }
  if (iSAllIncluded) {
    filtered_courses.unshift({
      value: null,
      label: __("All", "learndash-reports-by-wisdmlabs"),
    });
  }
  return filtered_courses;
}



class TimeSpent extends Component {
  constructor(props) {
    super(props);
    let error = null;
    if (null == this.getUserType()) {
      error = {
        message: __(
          "Sorry you are not allowed to access this block, please check if you have proper access permissions",
          "learndash-reports-by-wisdmlabs"
        ),
      };
    }
    let tab_selected =
      "course-reports" ==
      wisdm_learndash_reports_front_end_script_report_filters.report_type
        ? 1
        : 0;
    this.state = {
      isLoaded: false,
      noData : false,
      error: error,
      series: [],
      options: [],
      reportTypeInUse:
        wisdm_learndash_reports_front_end_script_course_completion_rate.report_type,
      chart_title: __("Time spent", "learndash-reports-by-wisdmlabs"),
      lock_icon: "",
      request_data: null,
      active_tab: tab_selected,
      group: {
        value: null,
        label: __("All", "learndash-reports-by-wisdmlabs"),
      },
      groups: [],
      course: {
        value: null,
        label: __("All courses", "learndash-reports-by-wisdmlabs"),
      },
      courses: [],
      category: {
        value: null,
        label: __("All", "learndash-reports-by-wisdmlabs"),
      },
      categories: [],
      graph_type: "donut",
      graph_summary: [],
      duration: {
        value: "all",
        label: __("All time", "learndash-reports-by-wisdmlabs"),
      },
      help_text: __(
        "This report displays the time spent on a courses.",
        "learndash-reports-by-wisdmlabs"
      ),
      course_report_type: null,
      show_supporting_text: false,
      is_group_enabled: false,
      is_category_enabled: false,
      show_time_spent_detail_modal: false,
      page: 1,
      timePeriodOptions: [
        { value: 1, label: __("Minutes", "learndash-reports-by-wisdmlabs") },
        { value: 2, label: __("Hours", "learndash-reports-by-wisdmlabs") },
        { value: 3, label: __("Days", "learndash-reports-by-wisdmlabs") },
      ],
      selectedTimePeriodOptions: { value: 1, label:  __("Minutes", "learndash-reports-by-wisdmlabs") },
      isToggleChecked: false,
      learnerTotaltimeonEachCourse: [],
      learnerTotalAvgTimeByAll: [],
      learnerLabels: [],
      hourIntervalForLearner:0,
      minuteIntervalForLerner:0,
      daysIntervalForLearner:0,
      isOldDataMigrated : true,
    };

    // if (false==wisdm_learndash_reports_front_end_script_course_completion_rate.is_pro_version_active) {
    //   this.upgdare_to_pro = 'wisdm-ld-reports-upgrade-to-pro-front';
    //   this.lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
    // }

    this.applyFilters = this.applyFilters.bind(this);
    this.handleReportTypeChange = this.handleReportTypeChange.bind(this);
    // this.showDummyImages        = this.showDummyImages.bind(this);
    this.updateLocalDuration = this.updateLocalDuration.bind(this);
    this.updateLocalGroup = this.updateLocalGroup.bind(this);
    this.updateLocalCategory = this.updateLocalCategory.bind(this);
    this.updateLocalLearner = this.updateLocalLearner.bind(this);
    this.updateLocalCourse = this.updateLocalCourse.bind(this);
    this.updateLocalTab = this.updateLocalTab.bind(this);
    this.openTimeSpentModal = this.openTimeSpentModal.bind(this);
    this.closeProgressDetailsModal = this.closeProgressDetailsModal.bind(this);
    this.addMoreData = this.addMoreData.bind(this);
    this.startCSVDownload = this.startCSVDownload.bind(this);
    this.timeSpentFilterSetting = this.timeSpentFilterSetting.bind(this);
    this.handleToggleSwich = this.handleToggleSwich.bind(this);
    this.handleTimePeriodChange = this.handleTimePeriodChange.bind(this);
    this.defaultFiltersLoaded            = this.defaultFiltersLoaded.bind(this);
  }

  
  openTimeSpentModal() {
    document.body.classList.add("wrld-ts-open");
    this.setState({
      show_time_spent_detail_modal: true,
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

  closeProgressDetailsModal() {
    document.body.classList.remove("wrld-ts-open");
    this.setState({
      show_time_spent_detail_modal: false,
    });
    this.setState({
      selected_data_point: "",
      table_data: [],
      more: "no",
      page: 1,
    });
  }

  /**
   * Based on the current user roles aray this function desides wether a user is a group
   * leader or an Administrator and returns the same.
   */
  getUserType() {
    let userRoles =
      wisdm_learndash_reports_front_end_script_average_quiz_attempts.user_roles;
    if ("object" == typeof userRoles) {
      userRoles = Object.keys(userRoles).map((key) => userRoles[key]);
    }
    if (undefined == userRoles || userRoles.length == 0) {
      return null;
    }
    if (userRoles.includes("administrator")) {
      return "administrator";
    } else if (userRoles.includes("group_leader")) {
      return "group_leader";
    }
    return null;
  }

  
  defaultFiltersLoaded() {
 
    this.setState({ isLoaded: false });

      let courses     = wisdm_learndash_reports_front_end_script_report_filters.courses;
        let groups      = wisdm_learndash_reports_front_end_script_report_filters.course_groups;
        let categories  = wisdm_learndash_reports_front_end_script_report_filters.course_categories;

        if(courses.length === 0){
          this.setState({ isLoaded: true , noData : true , error:{message : "No courses found"}});
        }
     
      this.setState({
        groups: groups,
        courses: courses,
        course: courses.length === 0 ? {value: null, label:__('All', 'learndash-reports-by-wisdmlabs')} : courses[0],
        categories: categories,
      });

     
      //Patch logic for react state updaete on browser refresh bug.
      const groupsLoadEvent = new CustomEvent(
        "progress-parent-groups-changed",
        {
          detail: { value: groups },
        }
      );
      const categoryLoadEvent = new CustomEvent(
        "progress-parent-category-changed",
        {
          detail: { value: categories },
        }
      );
      document.dispatchEvent(groupsLoadEvent);
      document.dispatchEvent(categoryLoadEvent);
      this.updateChart(
        "/rp/v1/time-spent-on-a-course?duration=" +
          this.state.duration.value +
          "&course=" +
          courses[0].value +
          "&rtype=false" +
          "&timeperiod= 1"
      );
  
    let requestResults = [];
    if ("group_leader" == wisdmLdReportsGetUserType()) {
      let groupUsers = wrldGetGroupAdminUsers();
      requestResults.push({
        value: groupUsers[0].id,
        label: groupUsers[0].display_name,
      });
      this.setState({ learner: requestResults[0] });
    } else {
      let callback_path = "/wp/v2/users/?search=a";
      callback_path = callback_path + "&reports=1";
      if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
        callback_path += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
      }
      wp.apiFetch({
        path: callback_path, //Replace with the correct API
      })
        .then((response) => {
          if (false != response && response.length > 0) {
            requestResults.push({
              value: response[0].id,
              label: response[0].name,
            });
          }
          this.setState({ learner: requestResults[0] });
        })
        .catch((error) => {
          console.log("No data error encountered.");
        });
    }
  }

  componentDidMount() {
    document.addEventListener(
      "wisdm-ld-reports-filters-applied",
      this.applyFilters
    );
    document.addEventListener(
      "wisdm-ld-reports-report-type-selected",
      this.handleReportTypeChange
    );
    // document.addEventListener('wisdm-ldrp-course-report-type-changed', this.showDummyImages);
    document.addEventListener(
      "local_time_spent_duration_change",
      this.updateLocalDuration
    );
    document.addEventListener(
      "local_group_change_time_spent",
      this.updateLocalGroup
    );
    document.addEventListener(
      "local_learner_change_time_spent",
      this.updateLocalLearner
    );
    document.addEventListener(
      "local_category_change_time_spent",
      this.updateLocalCategory
    );
    document.addEventListener(
      "local_tab_change_time_spent",
      this.updateLocalTab
    );
    document.addEventListener(
      "local_course_change_time_spent",
      this.updateLocalCourse
    );
    document.addEventListener("start_csv_download_ts", this.startCSVDownload);
    document.addEventListener(
      "time_spent_filter_setting",
      this.timeSpentFilterSetting
    );
    document.addEventListener('wrld-default-filters-loaded', this.defaultFiltersLoaded);
    
  }

  addMoreData(evnt) {
    let next = this.state.page + 1;
    this.setState({ page: next });
    this.detailsModal(this.state.selected_data_point, true, next);
  }

  getCourseListFromJson(response) {
    let courseList = [];
    if (response.length == 0) {
      return courseList; //no courses found
    }

    for (let i = 0; i < response.length; i++) {
      courseList.push({
        value: response[i].id,
        label: response[i].title.rendered,
      });
    }
    courseList = getCoursesByGroups(courseList);
    return courseList;
  }

  updateSelectorsFor(element, selection, callback_path = "/wp/v2/categories/") {
    switch (element) {
      case "group":
        callback_path = callback_path + "&per_page=-1";
        if ( this.state.categories.length <= 0 ) {
          this.setState({categories: [{value: null, label: 'All'}]});
        }
        if (null == selection) {
          // wp.apiFetch({
          //   path: callback_path, //Replace with the correct API
          // }).then((response) => {

            let courses = wisdm_learndash_reports_front_end_script_report_filters.courses;
            
            if (false != courses && courses.length > 0) {
              this.setState({
                courses: courses,
                course: courses[0],
                category: this.state.categories[0],
              });
            } else {
              this.setState({
                courses: [],
                course: {
                  value: null,
                  label: __(
                    "No courses available for the group",
                    "learndash-reports-by-wisdmlabs"
                  ),
                },
                category: this.state.categories[0],
              });
            }
            let request_url =
              "/rp/v1/time-spent-on-a-course?duration=" +
              this.state.duration.value +
              "&group=" +
              selection +
              "&category=" +
              this.state.category.value +
              "&course=" +
              courses[0].value +
              "&rtype=false" +
              "&timeperiod= 1";
            this.updateChart(request_url);
            //Patch logic for react state updaete on browser refresh bug.
            // const groupsLoadEvent = new CustomEvent("progress-parent-group-changed", {
            //   "detail": {"value": group }
            // });
            // document.dispatchEvent(groupsLoadEvent);
          //});
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
            let request_url =
            "/rp/v1/time-spent-on-a-course?duration=" +
            this.state.duration.value +
            "&group=" +
            selection +
            "&category=" +
            this.state.category.value +
            "&course=" +
            this.state.course.value +
            "&is_group_enabled=" +
            this.state.is_group_enabled +
            "&is_category_enabled=" +
            this.state.is_category_enabled +
            "&rtype=" +
            this.state.isToggleChecked +
            "&timeperiod=" +
            this.state.selectedTimePeriodOptions.value;
            this.updateChart(request_url);
         });

            
         // });
        }
        break;
      case "category":
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
                        const allowed_courses = courses.map(value => value.value);
                        courses = courses.filter( value => allowed_courses.includes(value.value));
                        console.log(courses);
                        if ( courses.length > 0 ) {
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
                          this.setState({
                                 courses: courses
                              });
                        } else {
                          this.setState(
                              {
                              courses:courses,
                              course: {
                                value: null,
                                label: __(
                                  "No courses available for the category",
                                  "learndash-reports-by-wisdmlabs"
                                ),
                                group: this.state.groups[0]
                              },
                              // course: courses[0]
                          });
                        }
                    }
                    let request_url =
              "/rp/v1/time-spent-on-a-course?duration=" +
              this.state.duration.value +
              "&group=" +
              this.state.group.value +
              "&category=" +
              selection +
              "&course=" +
              this.state.course.value +
              "&is_group_enabled=" +
              this.state.is_group_enabled +
              "&is_category_enabled=" +
              this.state.is_category_enabled +
              "&rtype=" +
              this.state.isToggleChecked +
              "&timeperiod=" +
              this.state.selectedTimePeriodOptions.value;
            this.updateChart(request_url);
                 }).catch((error) => {
              });
            
          // })
          // .catch((error) => {});
        break;
      default:
        break;
    }
  }

  timeSpentFilterSetting(evnt) {
    //console.log(evnt);
    this.setState({
      is_group_enabled: evnt.detail.group,
      is_category_enabled: evnt.detail.category,
    });
   //console.log(evnt.detail.group);
  // console.log(evnt.detail.category);
   const group_enbl = evnt.detail.group  ? 'enable' : 'disable';
   const category_enbl = evnt.detail.category  ? 'enable' : 'disable';
   localStorage.setItem('is_group_enabled', group_enbl);
   localStorage.setItem('is_category_enabled', category_enbl);
    //call api to save filter
    // let filter_request_url =
    //           "/rp/v1/time-spent-on-a-course-filter?category=" + evnt.detail.category + "&group="+ evnt.detail.group;
    // wp.apiFetch({
    //   path: filter_request_url, //Replace with the correct API
    // })
    //   .then((response) => {
    //     if (response.requestData) {
    //       console.log("Fiters are saved");
    //     }
    //   })
    //   .catch((error) => {
    //       console.log("Fiters not saved");
    //   });
  }

  startCSVDownload(evnt) {
    let self = this;
    if (this.state.active_tab == 1) {
      var requestUrl =
        "/rp/v1/time-spent-on-a-course-csv/?duration=" +
        this.state.duration.value +
        "&learner=" +
        this.state.learner.value +
        "&page=" +
        "all" +
        "&rtype=" +
        this.state.isToggleChecked +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value;
    } else {
      var requestUrl =
        "/rp/v1/time-spent-on-a-course-csv/?duration=" +
        this.state.duration.value +
        "&category=" +
        this.state.category.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&page=" +
        "all" +
        "&rtype=" +
        this.state.isToggleChecked +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value;
    }
    // this.setState({selected_data_point: data_point, table_data: [], more: 'yes'});
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl, //Replace with the correct API
    })
      .then((response) => {
        window.location = response.filename;
        // jQuery('<a href="' + response.filename + '" download class="wrld-hidden"></a>').appendTo('body').trigger('click');
      })
      .catch((error) => {
        if (error.data && error.data.requestData) {
          self.setState({
            request_data: error.data.requestData,
            moreDataLoading: false,
          });
        }
        self.setState({
          error: error,
          moreDataLoading: false,
        });
      });
  }

  updateLocalGroup(evnt) {
    this.setState({ group: evnt.detail.value });
    if (null == evnt.detail.value.value) {
      this.updateSelectorsFor(
        "group",
        null,
        "/ldlms/v1/" + ld_api_settings["sfwd-courses"] + "?test=1"
      );
    } else {
      this.updateSelectorsFor(
        "group",
        evnt.detail.value.value,
        "/ldlms/v1/" +
          ld_api_settings["sfwd-courses"] +
          "?include=" +
          evnt.detail.value.courses_enrolled
      );
    }
    // let request_url = '/rp/v1/time-spent-on-a-course?duration=' + this.state.duration.value + '&group=' + evnt.detail.value.value + '&category=' + this.state.category.value  + '&course=' + this.state.course.value;
    // this.updateChart(request_url);
  }

  updateLocalCategory(evnt) {
    this.setState({ category: evnt.detail.value });
    if (null == evnt.detail.value.value) {
      this.updateSelectorsFor(
        "category",
        null,
        "/ldlms/v1/" + ld_api_settings["sfwd-courses"]
      );
    } else {
      this.updateSelectorsFor(
        "category",
        evnt.detail.value.value,
        "/ldlms/v1/" + ld_api_settings["sfwd-courses"]
      );
    }
  }

  updateLocalLearner(evnt) {
    this.setState({ learner: evnt.detail.value });
    let request_url =
      "/rp/v1/time-spent-on-a-course?duration=" +
      this.state.duration.value +
      "&learner=" +
      evnt.detail.value.value;
    this.updateChart(request_url);
  }

  updateLocalTab(evnt) {
    this.setState({ active_tab: evnt.detail.value  , selectedTimePeriodOptions: { value: 1, label:  __("Minutes", "learndash-reports-by-wisdmlabs") }});

    if (0 == evnt.detail.value) {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        this.state.duration.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&category=" +
        this.state.category.value +
        "&rtype=" +
        this.state.isToggleChecked +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value;
      this.state.courses.length === 0 ? this.setState({error:{"message" : "No courses found."}}) : this.updateChart(request_url);;
      
    } else {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        this.state.duration.value +
        "&learner=" +
        this.state.learner.value;
        this.state.courses.length === 0 ? this.setState({error:{"message" : "No data available for the selected duration or filters."}}) : this.updateChart(request_url);;
    }
  }

  updateLocalCourse(evnt) {
    this.setState({ course: evnt.detail.value });
    let request_url =
      "/rp/v1/time-spent-on-a-course?duration=" +
      this.state.duration.value +
      "&group=" +
      this.state.group.value +
      "&course=" +
      evnt.detail.value.value +
      "&category=" +
      this.state.category.value +
      "&rtype=" +
      this.state.isToggleChecked +
      "&timeperiod=" +
      this.state.selectedTimePeriodOptions.value;
    this.updateChart(request_url);
  }

  updateLocalDuration(evnt) {
    this.setState({ duration: evnt.detail.value });
    if (0 == this.state.active_tab) {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        evnt.detail.value.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&category=" +
        this.state.category.value +
        "&rtype=" +
        this.state.isToggleChecked +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value;
      this.updateChart(request_url);
    } else {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        evnt.detail.value.value +
        "&learner=" +
        this.state.learner.value;
      this.updateChart(request_url);
    }
  }

  componentDidUpdate() {
    jQuery(".TimeSpent .mixed-chart").prepend(
      jQuery(".TimeSpent .apexcharts-toolbar")
    );
    jQuery(
      ".wisdm-learndash-reports-time-spent-on-a-course .chart-title .dashicons, .wisdm-learndash-reports-time-spent-on-a-course .chart-summary-revenue-figure .dashicons"
    ).hover(
      function () {
        var $div = jQuery("<div/>")
          .addClass("wdm-tooltip")
          .css({
            position: "absolute",
            zIndex: 999,
            display: "none",
          })
          .appendTo(jQuery(this));
        $div.text(jQuery(this).attr("data-title"));
        var $font = jQuery(this)
          .parents(".graph-card-container")
          .css("font-family");
        $div.css("font-family", $font);
        $div.show();
      },
      function () {
        jQuery(this).find(".wdm-tooltip").remove();
      }
    );
  }

  handleReportTypeChange(event) {
    this.setState({ reportTypeInUse: event.detail.active_reports_tab });
    if ("quiz-reports" == event.detail.active_reports_tab) {
      wisdm_reports_change_block_visibility(
        ".wp-block-wisdm-learndash-reports-time-spent-on-a-course",
        false
      );
    } else {
      wisdm_reports_change_block_visibility(
        ".wp-block-wisdm-learndash-reports-time-spent-on-a-course",
        true
      );
    }
  }

  handleToggleSwich(event) {
    this.setState((prevState) => ({
      isToggleChecked: !prevState.isToggleChecked,
    }));

    if (this.state.active_tab == 0) {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        this.state.duration.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&category=" +
        this.state.category.value +
        "&rtype=" +
        !this.state.isToggleChecked +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value;
      this.updateChart(request_url);
    }
  }
  handleTimePeriodChange(envt) {
    this.setState((prevState) => ({
      selectedTimePeriodOptions: envt,
    }));

    if (this.state.active_tab == 0) {
      let request_url =
        "/rp/v1/time-spent-on-a-course?duration=" +
        this.state.duration.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&category=" +
        this.state.category.value +
        "&rtype=" +
        this.state.isToggleChecked +
        "&timeperiod=" +
        envt.value;
      this.updateChart(request_url);
    } else {
      this.setState({
        isLoaded: false,
        selectedTimePeriodOptions: envt,
      });
      console.log(envt);
      this.plotLearnerChart(
        this.state.learnerTotaltimeonEachCourse,
        this.state.learnerTotalAvgTimeByAll,
        this.state.learnerLabels,
        envt,
        __("Courses", "learndash-reports-by-wisdmlabs")
      );
      let self = this;
      setTimeout(() => {
        self.setState({ isLoaded: true });
      }, 500);
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
    let category = event.detail.selected_categories;
    let group = event.detail.selected_groups;
    let course = event.detail.selected_courses;
    let lesson = event.detail.selected_lessons;
    let topic = event.detail.selected_topics;
    let learner = event.detail.selected_learners;

    var request_url =
      "/rp/v1/time-spent-on-a-course/?duration=" + this.state.duration.value;
    if (undefined == learner) {
      request_url += "&category=" + category + "&group=" + group;
      this.setState({
        category: event.detail.selected_categories_obj,
        group: event.detail.selected_groups_obj,
        active_tab: 0,
      });
      if (undefined != course) {
        request_url += "&course=" + course +
        "&rtype=false" +
        "&timeperiod= 1";
        this.setState({ course: event.detail.selected_courses_obj });
      } else {
        request_url += "&course=" + this.state.course.value + 
        "&rtype=false" +
        "&timeperiod= 1";
      }
    } else {
      request_url += "&learner=" + learner +
      "&rtype=false" +
      "&timeperiod= 1";
      this.setState({
        learner: event.detail.selected_learners_obj,
        active_tab: 1,
      });
    }

    if (undefined != course) {
      this.setState({ show_supporting_text: true });
    } else {
      this.setState({ show_supporting_text: false });
    }

    //Time spent on a course chart should not display for lesson/topic
    if (undefined == topic && undefined == lesson) {
      this.updateChart(request_url);
      this.setState({ reportTypeInUse: "default-ld-reports" });
      wisdm_reports_change_block_visibility(
        ".wp-block-wisdm-learndash-reports-time-spent-on-a-course",
        true
      );
    } else {
      //hide this block.
      this.setState({ reportTypeInUse: "default-ld-reports-lesson-topic" });
      wisdm_reports_change_block_visibility(
        ".wp-block-wisdm-learndash-reports-time-spent-on-a-course",
        false
      );
    }
  }

  updateChart(requestUrl) {
    this.setState({
      isLoaded: false,
      error: null,
      request_data: null,
    });
    let self = this;
    let checkIfEmpty = function () {
      setTimeout(function () {
        if (window.callStack.length > 4) {
          checkIfEmpty();
        } else {
          window.callStack.push(requestUrl);
          if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
            requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
          }
          wp.apiFetch({
            path: requestUrl, //Replace with the correct API
          })
            .then((response) => {
              if (response.requestData) {
                self.setState({ request_data: response.requestData });
              }
              if ( response.updated_on ) {
                self.setState({updated_on: response.updated_on});
              }
              self.showCompletionChart(response);
            })
            .catch((error) => {
              window.callStack.pop();

              if(error.data.updated_on != undefined){
                self.setState({updated_on: error.data.updated_on , isOldDataMigrated :error.data.is_old_data_migrated });
              }
              if (error.data && error.data.requestData) {
                self.setState({ request_data: error.data.requestData });
              }
              self.setState({
                error: error,
                isLoaded: true,
              });
            });
        }
      }, 500);
    };
    checkIfEmpty();
  }

  showCompletionChart(response) {
    const local_group_enabled = localStorage.getItem('is_group_enabled') === 'enable' ? true : false;
   const local_category_enabled = localStorage.getItem('is_category_enabled') === 'enable' ? true : false;
   
  
    this.setState({
      series: [],
      options: [],
      error: null,
      learnerLabels: response.courseLabels,
      learnerTotaltimeonEachCourse: response.total_time_by_user_on_each_course,
      learnerTotalAvgTimeByAll: response.average_time_spent_by_all,
      is_group_enabled  : local_group_enabled ,
      is_category_enabled : local_category_enabled,
      isOldDataMigrated : response.is_old_data_migrated,
    });

    if (this.state.active_tab == 1) {
      this.plotLearnerChart(
        response.total_time_by_user_on_each_course,
        response.average_time_spent_by_all,
        response.courseLabels,
        this.state.selectedTimePeriodOptions,
        __("Courses", "learndash-reports-by-wisdmlabs")
      );
      const chart_title = __("Time Spent", "learndash-reports-by-wisdmlabs");
      this.setState({
        isLoaded: true,
        chart_title: chart_title,
        graph_summary: {
          left: [
            {
              title: __(
                "AVG. time spent by learner",
                "learndash-reports-by-wisdmlabs"
              ),
              value: wisdmLDRConvertTime(response.average_time_by_learner),
            },
          ],

          right: [
            {
              title: __("Courses: ", "learndash-reports-by-wisdmlabs"),
              value: response.total_courses,
            },
          ],
          inner_help_text: __(
            "Avg Time Spent by Learner = Total time spent in courses/No. of Courses",
            "learndash-reports-by-wisdmlabs"
          ),
        },
        help_text: __(
          "This report shows the list of courses corresponding to the learner's time spent.",
          "learndash-reports-by-wisdmlabs"
        ),
      });
    } else {
      this.plotBarCourseChart(
        response.learner_counts_in_interval,
        response.time_invervals,
        response.total_learners,
        response.average_time_spent,
        this.state.selectedTimePeriodOptions.value,
        __("Learners", "learndash-reports-by-wisdmlabs")
      );
      this.setState({
        isLoaded: true,
        chart_title: __("Time Spent", "learndash-reports-by-wisdmlabs"),
        graph_summary: {
          left: [
            {
              title: this.state.isToggleChecked
                ? __(
                    "AVG. time spent (course progress)",
                    "learndash-reports-by-wisdmlabs"
                  )
                : __(
                    "AVG. time spent (course completion)",
                    "learndash-reports-by-wisdmlabs"
                  ),
              value: wisdmLDRConvertTime(response.average_time_spent),
            },
          ],
          right: [
            {
              title: __("Total learners: ", "learndash-reports-by-wisdmlabs"),
              value: response.total_learners,
            },

            {
              title: __(
                this.state.isToggleChecked
                  ? "Learners in progress: "
                  : "Learners completed: ",
                "learndash-reports-by-wisdmlabs"
              ),
              value:
                parseInt(response.learner_below_average) +
                parseInt(response.learner_above_average),
            },
            {
              title: __(
                "Behind avg. time : ",
                "learndash-reports-by-wisdmlabs"
              ),
              value: response.learner_below_average,
            },
            {
              title: __(
                "Exceeding avg. time : ",
                "learndash-reports-by-wisdmlabs"
              ),
              value: response.learner_above_average,
            },
          ],
          inner_help_text: __(
            "Avg Time Spent = Total time spent by Learners/Learner Count",
            "learndash-reports-by-wisdmlabs"
          ),
        },
        help_text: __(
          "This report shows the number and list of users against time spent slabs.",
          "learndash-reports-by-wisdmlabs"
        ),
      });
    }
  }

  showDetailsModal() {
    jQuery(".button-timespent-detail").click();
  }

  detailsModal(selectedBarData, is_paginated = false, page = 0) {
    var self = this;
    if (0 == page) {
      page = this.state.page;
    }
    if (1 == this.state.active_tab) {
      var requestUrl =
        "/rp/v1/course-time-details/?duration=" +
        this.state.duration.value +
        "&category=" +
        this.state.category.value +
        "&learner=" +
        this.state.learner.value +
        "&datapoint=" +
        selectedBarData.dataPoint +
        "&lower_limit=" +
        selectedBarData.lower +
        "&upper_limit=" +
        selectedBarData.upper +
        "&page=" +
        page;
    } else {
      var requestUrl =
        "/rp/v1/course-time-details/?duration=" +
        this.state.duration.value +
        "&category=" +
        this.state.category.value +
        "&group=" +
        this.state.group.value +
        "&course=" +
        this.state.course.value +
        "&datapoint=" +
        selectedBarData.dataPoint +
        "&lower_limit=" +
        selectedBarData.lower +
        "&upper_limit=" +
        selectedBarData.upper +
        "&page=" +
        page +
        "&timeperiod=" +
        this.state.selectedTimePeriodOptions.value +
        "&rtype=" +
        this.state.isToggleChecked;
    }
    // this.setState({selected_data_point: data_point, table_data: [], more: 'yes'});
    if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
      requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
    }
    wp.apiFetch({
      path: requestUrl, //Replace with the correct API
    })
      .then((response) => {
        console.log(response);
        var table = response.tableData;
        if (undefined == response) {
          table = [];
        }
        if (is_paginated) {
          console.log("paginated : " + table.length);
          table = [
            ...this.state.table_data,
            ...table,
          ];
          console.log("paginated after : " + table.length);
          // table = this.state.tableData.concat(table); // for array
        }
        this.setState({
          selected_data_point: selectedBarData.dataPoint,
          table_data: table,
          more: response.more_data,
          error: null,
        });
        self.showDetailsModal();
      })
      .catch((error) => {
        if (error.data && error.data.requestData) {
          self.setState({ request_data: error.data.requestData });
        }
        self.setState({
          error: error,
        });
      });
  }
  formateLearnerYaxis(text) {
    const words = text.toString().split(' ');;
    const result = [];
    let currentSubstring = "";

    for (let i = 0; i < words.length; i++) {
      const word = words[i];

      if ((currentSubstring + " " + word).length <= 10) {
        currentSubstring += (currentSubstring ? " " : "") + word;
      } else {
        result.push(currentSubstring);
        currentSubstring = word;
      }
    }

    if (currentSubstring) {
      result.push(currentSubstring);
    }

    return result;
  }

  getMaxHoursLimit(newSeconds){
     // Calculate the minimum and maximum interval values

     if(newSeconds < 518400){
      this.setState({daysIntervalForLearner:(60 * 60)});
      return 518400;
     }
     
     const minValue = 0;
     const maxValue = Math.ceil(newSeconds / (24 * 60 * 60)); // Maximum value for an interval
 
     const intervalCount = 6; // Number of intervals
     
     const intervalSize = Math.ceil((maxValue - minValue) / intervalCount); // Calculate the interval size
     console.log(intervalSize * (24 * 60 * 60));
     this.setState({daysIntervalForLearner:intervalSize * (24 * 60 * 60)});
     console.log("max learner : " + intervalSize * 5 * (24 * 60 * 60));
     const maxHoursLimit = intervalSize * 5 * (24 * 60 * 60);
     return newSeconds;
     return maxHoursLimit;
  }

  getLearnerXaxisLabels(value, timePeriod) {
    
    if (value == 0) {
      return 0;
    }
    if (timePeriod.value == 1) {
      const actualTime = value / 60;
      return parseInt(actualTime);
    }
    if (timePeriod.value == 2) {
      const actualTime = value / (60 * 60);
      return parseInt(actualTime);
    }

    if (timePeriod.value == 3) {
      const actualTime = value / this.state.daysIntervalForLearner;
      return parseInt(actualTime);
    }
    return value;
  }

  plotLearnerChart(
    learnerData,
    allLearnerData,
    labels,
    timePeriod,
    tooltipTextLineOne = __("Time Spent", "learndash-reports-by-wisdmlabs")
  ) {
    var self = this;
   
    if(wisdm_ld_reports_common_script_data.is_rtl){
      learnerData = learnerData.reverse();
      allLearnerData = allLearnerData.reverse();
      labels = labels.reverse();
    }
    const lernerName = this.state.learner.label;
    let series = [
      {
        name:
          __("Total time spent by ", "learndash-reports-by-wisdmlabs") +
          lernerName,
        data: learnerData,
      },
      {
        name: __(
          "Avg. time spent by all learners",
          "learndash-reports-by-wisdmlabs"
        ),
        data: allLearnerData,
      },
    ];
    let dataX = labels;
    const noday = __(
      "Time spent (in " + timePeriod.label.toLowerCase() + ")",
      "learndash-reports-by-wisdmlabs"
    );
    const withday = __(
      "Time spent (in hours)",
      "learndash-reports-by-wisdmlabs"
    );
    const nameX = timePeriod.label === "Days" ? withday : noday;
    const nameY = __("Learners", "learndash-reports-by-wisdmlabs");

    let nchart_options = {
      chart: {
        id: "basic-bar",
        width: 530,
        height: labels.length * 70 < 450 ? 400 : labels.length * 70,
        zoom: {
          enabled: false,
        },
        toolbar: {
          show: false,
        },
        events: {
          mounted: function (chartContext, config) {
            window.callStack.pop();
          },
          dataPointSelection: (event, chartContext, config) => {
            //self.detailsModal(dataX[config.dataPointIndex]);
          },
        },
      },
      legend: {
        position: "top", // Set the position of the legends to 'top'
      },
      colors: ["#008AD8", "#787878"],

      plotOptions: {
        bar: {
          horizontal: true,
          barWidth: "80%", // Adjust the bar width as per your requirement
        },
      },
      xaxis: {
        title: {
          text: nameX,
        },
        categories: dataX,
        labels: {
          formatter: (value) => {
            return this.getLearnerXaxisLabels(value, timePeriod);
          },
          rotate: wisdm_ld_reports_common_script_data.is_rtl ? 45 : -45,
        },
        tickAmount: 6,
        min: 0,               
      },
      yaxis: {
        axisBorder: {
          show: true,
        },
        labels: {
          formatter: (value) => {
            return this.formateLearnerYaxis(value);
          },
          align: wisdm_ld_reports_common_script_data.is_rtl ? 'left' : 'right',
          // offsetX: 50, // Bug offset never gets applied.
        },
        reversed: wisdm_ld_reports_common_script_data.is_rtl,
        min: 0,
        max: 
          timePeriod.value == 1
            ? Math.max(...learnerData) + 600
            : timePeriod.value == 2
            ? (Math.max(...learnerData) < (60 * 60 * 6)  ? (60 * 60 * 6) : Math.max(...learnerData)) + 3600
            : this.getMaxHoursLimit(Math.max(...learnerData)),
      },
      dataLabels: {
        enabled: false,
      },
      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          return '<div class="wisdm-bar-timespent-chart-tooltip"> <div class="tooltip-body-learner"> <div class="wrld-learner-time">  <span class="wrld-lerner-time-lable"></span> <span class="wrld-lerner-time-lable-text"> Total time spent</span> </div> <b><span class="wrld-ts-time wrld-ts-time-learner">  '+ wisdmLDRConvertTime(series[0][dataPointIndex]) +' </span></b>  </br>  <div class="wrld-learner-avarage-time"> <span class="wrld-all-lerner-time-lable"></span> <span class="wrld-lerner-time-lable-text"> Avg. time spent by all learners</span> </div><b> <span class="wrld-ts-time" >'+ wisdmLDRConvertTime(series[1][dataPointIndex]) +'</span></b></div> </div></div>';
        },

        y: {
          formatter: function (
            value,
            { series, seriesIndex, dataPointIndex, w }
          ) {
            return value;
          },
        },
        position: 'middle',
        offsetY: -50, // Adjust this value to move the tooltip vertically
        offsetX: -50, // Adjust this value to move the tooltip horizontally
       },
    };

    this.setState({
      graph_type: "bar",
      series: series,
      options: nchart_options,
    });
    window.callStack.pop();
  }

  getUpperLowerBounds(index, timePeriod) {
    const upper =
      index == 0
        ? 0
        : index == 1
        ? timePeriod === 1
          ? 3600
          : timePeriod === 2
          ? 14400
          : 86400
        : index == 2
        ? timePeriod === 1
          ? 7200
          : timePeriod === 2
          ? 28800
          : 172800
        : index == 3
        ? timePeriod === 1
          ? 10800
          : timePeriod === 2
          ? 43200
          : 259200
        : index == 4
        ? timePeriod === 1
          ? 14400
          : timePeriod === 2
          ? 57600
          : 345600
        : timePeriod === 1
        ? null
        : timePeriod === 2
        ? null
        : null;

    const lower =
      index == 0
        ? 0
        : index == 1
        ? timePeriod === 1
          ? 1
          : timePeriod === 2
          ? 1
          : 1
        : index == 2
        ? timePeriod === 1
          ? 3600
          : timePeriod === 2
          ? 14401
          : 86400
        : index == 3
        ? timePeriod === 1
          ? 7200
          : timePeriod === 2
          ? 28801
          : 172801
        : index == 4
        ? timePeriod === 1
          ? 10800
          : timePeriod === 2
          ? 43201
          : 259201
        : timePeriod === 1
        ? 14400
        : timePeriod === 2
        ? 57600
        : 345600;

    return { lower, upper };
  }

  getAnnotationData(data, timePeriod) {
    let annotationArray = [];

    const index =
      timePeriod === 1
        ? data <= 3600
          ? 1
          : data <= 7200
          ? 2
          : data <= 10800
          ? 3
          : data <= 14400
          ? 4
          : 5
        : timePeriod === 2
        ? data <= 14400
          ? 1
          : data <= 28800
          ? 2
          : data <= 43200
          ? 3
          : data <= 57600
          ? 4
          : 5
        : data <= 86400
        ? 1
        : data <= 172800
        ? 2
        : data <= 259200
        ? 3
        : data <= 345600
        ? 4
        : 5;

    if (data != 0) {
      const d1 = {
        x:
          index == 0
            ? timePeriod === 1
              ? "0"
              : timePeriod === 2
              ? "0"
              : "0"
            : index == 1
            ? timePeriod === 1
              ? "0-60"
              : timePeriod === 2
              ? "0-4"
              : "0-24"
            : index == 2
            ? timePeriod === 1
              ? "61-120"
              : timePeriod === 2
              ? "4-8"
              : "24-48"
            : index == 3
            ? timePeriod === 1
              ? "121-180"
              : timePeriod === 2
              ? "8-12"
              : "48-72"
            : index == 4
            ? timePeriod === 1
              ? "181-240"
              : timePeriod === 2
              ? "12-16"
              : "72-96"
            : timePeriod === 1
            ? ">240"
            : timePeriod === 2
            ? ">16"
            : ">96",
        borderColor: "#775DD0",
        opacity: 1, // Set the opacity to 1 for a solid line
        strokeDashArray: 0, // Set the stroke dash array to 0 for a solid line
        borderWidth: 1,
        label: {
          style: {
            color: "#000",
          },
          text: "Avg. Time " + wisdmLDRConvertTime(data),
        },
      };
      annotationArray.push(d1);
    }

    return annotationArray;
  }

  plotBarCourseChart(
    learnerData,
    labels,
    totalLearner,
    average,
    timePeriod,
    tooltipTextLineOne = __("Time Spent", "learndash-reports-by-wisdmlabs")
  ) {
    var self = this;
    if ( wisdm_ld_reports_common_script_data.is_rtl ) {
      learnerData = learnerData.reverse();
      labels = labels.reverse();
    }
    let series = [
      {
        name: "Learner",
        data: learnerData,
      },
    ];

    const dataX = labels;
    const dataY = learnerData;
    const noday = __(
      "Time spent (in " + self.state.selectedTimePeriodOptions.label.toLowerCase() + ")",
      "learndash-reports-by-wisdmlabs"
    );
    const withday = __(
      "Time spent (in hours)",
      "learndash-reports-by-wisdmlabs"
    );
    const nameX =
      self.state.selectedTimePeriodOptions.label === "Days" ? withday : noday;
    const nameY = __("Learners", "learndash-reports-by-wisdmlabs");

    let nchart_options = {
      chart: {
        id: "basic-bar",
        width: dataX.length * 75 < 645 ? "100%" : dataX.length * 75,
        height: 400,
        zoom: {
          enabled: false,
        },
        toolbar: {
          show: false,
          export: {
            csv: {
              filename: __(
                "Completion Time.csv",
                "learndash-reports-by-wisdmlabs"
              ),
              columnDelimiter: ",",
              headerCategory: nameX,
              headerValue: nameY,
            },
            svg: {
              filename: undefined,
            },
            png: {
              filename: undefined,
            },
          },
        },
        events: {
          mounted: function (chartContext, config) {
            window.callStack.pop();
          },
          dataPointSelection: (event, chartContext, config) => {
            var upper = 0;
            var lower = 0;
            if ( wisdm_ld_reports_common_script_data.is_rtl ) {
              config.dataPointIndex = 5 - config.dataPointIndex;
            }
            const data = self.getUpperLowerBounds(
              config.dataPointIndex,
              self.state.selectedTimePeriodOptions.value
            );
            self.detailsModal({
              dataPoint: dataX[config.dataPointIndex],
              ...data,
            });
          },
        },
      },
      colors: ["#008AD8"],
      dataLabels: {
        enabled: false,
        formatter: function (val) {
          return val;
        },
        offsetY: -25,
        style: {
          fontSize: "12px",
          colors: ["#008AD8"],
        },
      },
      plotOptions: {
        bar: {
          borderRadius: 5,
          dataLabels: {
            enabled: true,
            position: "top",
          },
        },
      },
      xaxis: {
        title: {
          text: nameX,
        },
        categories: dataX,
        labels: {
          hideOverlappingLabels: false,
          trim: true,
          formatter: (value) => {
            return value;
          },
          rotate: wisdm_ld_reports_common_script_data.is_rtl ? 45 : -45,
        },
        tickPlacement: "on",
        min: 1,
      },
      yaxis: {
        max: Math.max(...learnerData) + 1,
        axisBorder: {
          show: ! wisdm_ld_reports_common_script_data.is_rtl,
        },
        title: {
          text: nameY,
          offsetX: wisdm_ld_reports_common_script_data.is_rtl ? -35 : 0,
        },
        labels: {
          formatter: (value) => {
            return parseInt(value);
          },
          align: wisdm_ld_reports_common_script_data.is_rtl ? 'right' : 'left',
          offsetX: wisdm_ld_reports_common_script_data.is_rtl ? 10 : 0,
        },
        opposite: wisdm_ld_reports_common_script_data.is_rtl,
      },

      annotations: {
        xaxis: self.getAnnotationData(average, timePeriod),
      },

      tooltip: {
        custom: function ({ series, seriesIndex, dataPointIndex, w }) {
          const learnerPercentge =
            (parseInt(series[0][dataPointIndex]) / parseInt(totalLearner)) *
            100;
          const tooltip = "<div class='wisdm-bar-timespent-chart-tooltip'><div class='tooltip-body'><div class='wrld-learner'><div className='wrld-learner-container'>No. of learners</div></div><div className='wrld-average-label'><b>" + 
          series[0][dataPointIndex]+ " (" + learnerPercentge.toFixed(2)
          + " %) </b></div></div></div>";
          return tooltip;
        },

        y: {
          formatter: function (
            value,
            { series, seriesIndex, dataPointIndex, w }
          ) {
            return value;
          },
        },
      },
    };

    this.setState({
      graph_type: "bar",
      series: series,
      options: nchart_options,
    });
    window.callStack.pop();
  }

  isValidGraphData() {
    if (undefined == this.state.options || 0 == this.state.options.length) {
      return false;
    }
    if (undefined == this.state.series || 0 == this.state.series.length) {
      return false;
    }

    return true;
  }

  refreshUpdateTime() {
    this.setState({isLoaded: false});
    if(this.state.courses.length === 0){
      let self = this;
      setTimeout(() => {
        self.setState({isLoaded: true});
      }, 1000);
      
      return;
    }
      let requestUrl = '/rp/v1/time-spent-on-a-course/';
      let learner_selected = null;
      let course_selected = null;
       
        if(this.state.active_tab === 1){
          
        requestUrl = requestUrl + "?duration=" +
        this.state.duration.value +
        "&learner=" +
        this.state.learner.value+ '&disable_cache=true' +
          "&timeperiod=" +
          this.state.selectedTimePeriodOptions.value;
         }else{
          course_selected = this.state.course.value;
         
          requestUrl = requestUrl +  "?duration=" +
          this.state.duration.value +
          "&group=" +
          this.state.group.value +
          "&course=" +
          this.state.course.value +
          "&category=" +
          this.state.category.value +
          "&rtype=" +
          this.state.isToggleChecked +
          "&timeperiod=" +
          this.state.selectedTimePeriodOptions.value + '&disable_cache=true';
         }
     
      
      this.updateChart(requestUrl);
      // console.log(this.state.selectedTimePeriodOptions);
      // this.setState({ selectedTimePeriodOptions: { value: 1, label:  __("Minutes", "learndash-reports-by-wisdmlabs") }});
  }

  render() {
    let body = <div></div>;
    // if(this.state.course_report_type == 'learner-specific-course-reports' && !wisdm_ld_reports_common_script_data.is_pro_version_active){
    //     body =  <DummyReports image_path='tsoc.png'></DummyReports>;
    //       return (body);
    // }
    let data_validation = "";
    if (!this.isValidGraphData()) {
      data_validation = "invalid-or-empty-data";
    }
    if (
      "" != this.state.reportTypeInUse &&
      "default-ld-reports" != this.state.reportTypeInUse
    ) {
      body = "";
    } else if (!this.state.isLoaded) {
      // yet loading
      body = <WisdmLoader text={this.state.show_supporting_text} />;
    } else {
      let graph = "";
      if (!this.state.error && !this.state.noData) {
        graph = (
          <div className={this.state.active_tab == 1 ? "time-spent-new" : "time-spent"}>
            <Chart
              options={this.state.options}
              series={this.state.series}
              width={this.state.options.chart.width}
              height={this.state.options.chart.height}
              type={this.state.graph_type}
            />
          </div>
        );
      }

       //tooltip cofiguration
    let tooltip_text = "";
    let icon_enabled = false;
    let block_description = '';
    const time_tracking_enabled =
      wisdm_learndash_reports_front_end_script_course_list.is_idle_tracking_enabled;

      if( undefined == global.reportTypeForTooltip || global.reportTypeForTooltip == 'default-course-reports'){
        block_description =  __("This report displays the avergae time spent on a course by a learner.", 'learndash-reports-by-wisdmlabs');
      }else{
        block_description =  __("This report shows the time spent by a learner compared to others for enrolled course/s .", 'learndash-reports-by-wisdmlabs');
      }

    if (wisdm_learndash_reports_front_end_script_course_list.is_admin_user) {
      //If current user is admin
      if (
        wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active
      ) {
        //need time tracking module setting
        if (time_tracking_enabled == "on") {
          //Checking weather time tracking is enabled or not
          tooltip_text = (
            <p>
              {__(
                "Idle Time Configured , Activated on ",
                "learndash-reports-by-wisdmlabs"
              ) +
                wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from +
                ". "}{" "}
              <a
                href={
                  wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url
                }
              >
                {__(
                  "View Idle Time Configuration Log.",
                  "learndash-reports-by-wisdmlabs"
                )}
              </a>{" "}
            </p>
          );
          icon_enabled = true;
        } else {
          tooltip_text = (
            <div class="tooltip_container">
              <p>
                {__(
                  '"Idle Time" not configured. Configure the Settings from here. ',
                  "learndash-reports-by-wisdmlabs"
                )}
              </p>
              <a
                href={
                  wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url
                }
                class="tooltip_button"
              >
                {__("Time Tracking Setting", "learndash-reports-by-wisdmlabs")}
              </a>
            </div>
          );
        }
      } else {
        tooltip_text = (
          <div class="tooltip_container">
            <p>
              {__(
                '"Idle Time" not configured. This is available in the PRO version of the plugin.',
                "learndash-reports-by-wisdmlabs"
              )}
            </p>
            <a
              href={
                wisdm_learndash_reports_front_end_script_report_filters.upgrade_link
              }
              target="_blank"
              class="tooltip_button"
            >
              {__("Upgrade To PRO", "learndash-reports-by-wisdmlabs")}
            </a>
          </div>
        );
      }
    } else {
      //For non-admin users group leader , instructor
      if (
        wisdm_learndash_reports_front_end_script_course_list.is_pro_version_active
      ) {
        //need time tracking module setting
        if (time_tracking_enabled == "on") {
          tooltip_text =
            __(
              "Idle Time Configured , Activated at ",
              "learndash-reports-by-wisdmlabs"
            ) +
            wisdm_learndash_reports_front_end_script_course_list.idle_tracking_active_from;
          icon_enabled = true;
        } else {
          tooltip_text = __(
            "Idle Time Not Configured",
            "learndash-reports-by-wisdmlabs"
          );
        }
      } else {
        console.log("Pro version is not active");
      }
    }

    const migration_tooltip_text = (
      <p>
        {__(
          "You need to run an data upgrade to see learner's earlier time spent information on this enhanced report. Click below to go to Time Tracking settings or navigate to WP Dashboard > Wisdm Reports > Settings > Time Tracking settings.",
          "learndash-reports-by-wisdmlabs"
        ) }
        <br/>
        <a
          href={
            wisdm_learndash_reports_front_end_script_course_list.time_tacking_setting_url
          }
        >
          {__(
            "Upgrade data now",
            "learndash-reports-by-wisdmlabs"
          )}
        </a>{" "}
      </p>
    );

      body = (
        <div class={"wisdm-learndash-reports-chart-block " + data_validation}>
          <div class="wisdm-learndash-reports-time-spent-on-a-course graph-card-container">
            <div class="chart-header time-spent-chart-header">
              <div class="chart-title">
                <span>{this.state.chart_title}</span>
               
                 <div class="tooltip">
                    {icon_enabled && <img src={wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time_tracking_active.png'}>
                      </img>}

                    {!icon_enabled && <img src={wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/time-tracking-disabled.png'}>
                      </img>}
                  {/* on remove tooltiptext class button inside tootip will not be reachable */}
                  <span class="tooltiptext wdm-tooltip">{block_description}<br/> <br/>{tooltip_text}</span>
              </div>
             { (!this.state.isOldDataMigrated && wisdm_learndash_reports_front_end_script_course_list.is_admin_user )  && <div class="tooltip">
                    
                   <img src={wisdm_learndash_reports_front_end_script_total_courses.plugin_asset_url + '/images/warning_icon_ts.svg'}>
                      </img>
                  {/* on remove tooltiptext class button inside tootip will not be reachable */}
                  <span class="tooltiptext wdm-tooltip">{migration_tooltip_text}</span>
              </div>}
              
                <DurationFilter
                  wrapper_class="chart-summary-inactive-users"
                  duration={this.state.duration}
                />
              </div>
              <div className="chart_update_time"><span>{__( 'Last updated: ', 'learndash-reports-by-wisdmlabs' )}</span><span>{this.state.updated_on}</span><div className='chart-refresh-icon'><span class="dashicons dashicons-image-rotate" data-title={__('Click this to refresh the chart', 'learndash-reports-by-wisdmlabs')} onClick={this.refreshUpdateTime.bind(this)}></span></div></div>
              <LocalFilters
                group={this.state.group}
                category={this.state.category}
                groups={this.state.groups}
                categories={this.state.categories}
                course={this.state.course}
                courses={this.state.courses}
                active_tab={this.state.active_tab}
                learner={this.state.learner}
                is_category_enabled={this.state.is_category_enabled}
                is_group_enabled={this.state.is_group_enabled}
              />
               { !this.state.noData && <div className="wrld-secondary-filter">
                  <div
                    className={
                      this.state.active_tab == 0
                        ? "wrld-secondary-filter-item left-item "
                        : "wrld-secondary-filter-item left-item ts-toggle-hide"
                    }
                  >
                    <span className="wrld-time-spent-label">
                      {__("Learners status", "learndash-reports-by-wisdmlabs")}
                    </span>
                    <div className="wrld-ts-switch-action">
                      <span
                        className={
                          this.state.isToggleChecked
                            ? "labeltext"
                            : "labeltext labeltext-bold"
                        }
                      >
                         {__('Completed', 'learndash-reports-by-wisdmlabs')} 
                      </span>
                      <label
                        className={
                          this.state.isToggleChecked
                            ? "toggle-switch"
                            : "toggle-switch labeltext-bold"
                        }
                      >
                        <input
                          type="checkbox"
                          checked={this.state.isToggleChecked}
                          onChange={this.handleToggleSwich}
                        />
                        <span className="slider"></span>
                      </label>
                      <span
                        className={
                          !this.state.isToggleChecked
                            ? "labeltext"
                            : "labeltext labeltext-bold"
                        }
                      >
                       {__('In Progress', 'learndash-reports-by-wisdmlabs')} 
                      </span>
                    </div>
                  </div>
                  <div className="wrld-secondary-filter-item right-item">
                    <p>{__('Time Period', 'learndash-reports-by-wisdmlabs')} </p>
                    <div className="dropdown-container">
                      <Select
                        options={this.state.timePeriodOptions}
                        value={this.state.selectedTimePeriodOptions}
                        onChange={this.handleTimePeriodChange}
                        isSearchable={false}
                        className="wrld-timeperiod-dropdown"
                      />
                    </div>
                  </div>
                </div> }
              
              <ChartSummarySection
                wrapper_class="chart-summary-time-spent"
                graph_summary={this.state.graph_summary}
                error={this.state.error}
              />
              {/*<SummarySection />*/}
            </div>

            <div>
              {graph}
             { (this.state.active_tab == 0 && !this.state.noData ) && <span className="note">
                <strong>
                  {__("Note: ", "learndash-reports-by-wisdmlabs")}
                </strong>
                { __(
                  "Click on any item on the bar chart to see more details.",
                  "learndash-reports-by-wisdmlabs"
                )}
              </span>}
            </div>
          </div>
          <Modal
            show={this.state.show_time_spent_detail_modal}
            onClose={this.closeProgressDetailsModal}
            containerStyle={{ width: "50%" }}
            className={"time_spent_detail_modal"}
          >
            <span
              className="close-modal dashicons dashicons-no"
              onClick={this.closeProgressDetailsModal}
            ></span>
           { !this.state.noData && <TimeSpentTable
              type={1 == this.state.active_tab ? "learner" : "course"}
              data_point={this.state.selected_data_point}
              table={this.state.table_data}
              course={this.state.course?this.state.course.label:__('No Courses found', 'learndash-reports-by-wisdmlabs')} 
              timeperiod={this.state.selectedTimePeriodOptions.value ?? 1 } 
              learner={this.state.learner?this.state.learner.label:__('No Learners found', 'learndash-reports-by-wisdmlabs')}
            />}
            {"yes" == this.state.more ? (
              <span className="load-more-ajax" onClick={this.addMoreData}>
                {__("View More", "learndash-reports-by-wisdmlabs")}
              </span>
            ) : (
              <span></span>
            )}
          </Modal>
          <button
            className="button-timespent-detail wrld-hidden"
            onClick={this.openTimeSpentModal}
          ></button>
        </div>
      );
    }
    return body;
  }
}

export default TimeSpent;

document.addEventListener("DOMContentLoaded", function (event) {
  // Your code to run since DOM is loaded and ready

  let elem = document.getElementsByClassName(
    "wisdm-learndash-reports-time-spent-on-a-course"
  );
  if (elem.length > 0) {
    ReactDOM.render(React.createElement(TimeSpent), elem[0]);
  }
});
