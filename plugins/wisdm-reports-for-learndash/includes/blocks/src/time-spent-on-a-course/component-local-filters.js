import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import Select from 'react-select';
import AsyncSelect from 'react-select/async';


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

class LocalFilters extends Component {

    constructor(props) {
        super(props);
        let learners_disabled = true;
        let categories_disabled = true;
        let groups_disabled = true;
        if ( 1/*false!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active*/ ) {
            learners_disabled = false;
            categories_disabled = false;
            groups_disabled = false;
        }
        this.state = {
          group: props.group,
          category: props.category,
          course: props.course,
          groups: props.groups,
          is_group_enabled : props.is_group_enabled,
          is_category_enabled : props.is_category_enabled,
          isDropdownOpen: false,
          categories: props.categories,
          courses: props.courses,
          learner: null,
          loading_groups: false,
          learners_disabled:learners_disabled,
          categories_disabled:categories_disabled,
          groups_disabled:groups_disabled,
          active_tab:props.active_tab,
        }
        this.changeGroups = this.changeGroups.bind(this);
        this.changeCategory = this.changeCategory.bind(this);
        this.changeCourses = this.changeCourses.bind(this);
        this.handleTabSelection = this.handleTabSelection.bind(this);
        this.toggleCategoryFilterChange = this.toggleCategoryFilterChange.bind(this);
        this.toggleGroupFilterChange = this.toggleGroupFilterChange.bind(this);
        this.getDefaultOptions();



        let currentState =this;

        //for dropdown filters
        jQuery(document).click(function(e) {
            var checkboxes = jQuery(".wrld-filter-checkboxes");
            var container = jQuery(".wrld-time-spent-filter-text");
            var container2 = jQuery(".wrld-time-spent-filter-text");
            var container3 = jQuery(".wrld-ts-img-icon");
            var container4 = jQuery(".wrld-ts-group-checkbox");
            var container5 = jQuery(".wrld-ts-category-checkbox");
            var container6 = jQuery(".wrld-ts-course-checkbox");

            // Check if the clicked element is not the div or a descendant of the div
            if ( !container3.is(e.target) && !container2.is(e.target) && !container.is(e.target) && !container4.is(e.target) && !container5.is(e.target) && !container6.is(e.target)  && container.has(e.target).length === 0  && container2.has(e.target).length === 0  && container3.has(e.target).length === 0 && container4.has(e.target).length === 0 && container5.has(e.target).length === 0 && container6.has(e.target).length === 0) {
                if (jQuery(".wrld-filter-checkboxes").is(":visible")) {
                    checkboxes.hide(300);
                    currentState.setState({isDropdownOpen:false});
                  }
                
            }
        });
    }

    changeGroups(evnt) {
        this.setState({groups:event.detail.value});
    }

    changeCategory(evnt) {
        this.setState({category:evnt.detail.value});
    }

    changeCourses(evnt) {
        this.setState({course:evnt.detail.value});
    }

    handleTabSelection(tab_key) {
        const durationEvent = new CustomEvent("local_tab_change_time_spent", {
          "detail": {"value": tab_key }
        });
        document.dispatchEvent(durationEvent);
        this.setState({ active_tab: tab_key });
       
    }

    toggleDropdown = () => {

        this.state.isDropdownOpen ?  jQuery(".wrld-filter-checkboxes").hide(300) : jQuery(".wrld-filter-checkboxes").show(300) ;
        this.setState((prevState) => ({
          isDropdownOpen: !prevState.isDropdownOpen,
        }));
    
      };

      toggleCategoryFilterChange(event){
        console.log(event.target.checked);
        if(event.target.checked){
            
        }
         const filterEvent = new CustomEvent("time_spent_filter_setting", {
            "detail": {"group": this.state.is_group_enabled ,
            "category": event.target.checked,
        }
          });
        document.dispatchEvent(filterEvent);
        this.setState({is_category_enabled : event.target.checked});
      }

      toggleGroupFilterChange(event){
        console.log(event.target.checked);
        const filterEvent = new CustomEvent("time_spent_filter_setting", {
            "detail": {"category": this.state.is_category_enabled ,
            "group": event.target.checked,
        }
          });
        document.dispatchEvent(filterEvent);
        this.setState({is_group_enabled : event.target.checked});
      }
    

    handleGroupChange = (selectedGroup) => {
        const durationEvent = new CustomEvent("local_group_change_time_spent", {
          "detail": {"value": selectedGroup }
        });
        document.dispatchEvent(durationEvent);
        this.setState({group:selectedGroup});
    }

    handleCategoryChange = (selectedCourse) => {
        const durationEvent = new CustomEvent("local_category_change_time_spent", {
          "detail": {"value": selectedCourse }
        });
        document.dispatchEvent(durationEvent);
        this.setState({category:selectedCourse});
    }

    handleCourseChange = (selectedCourse) => {
        const durationEvent = new CustomEvent("local_course_change_time_spent", {
          "detail": {"value": selectedCourse }
        });
        document.dispatchEvent(durationEvent);
        this.setState({course:selectedCourse});
    }

    handleLearnerChange = (selectedLearner) => {
        if (null==selectedLearner) {
            this.setState({ learner:null, courses_disabled:false, categories_disabled:false});
            // this.updateSelectorsFor('learner', null);
        } else {
            const durationEvent = new CustomEvent("local_learner_change_time_spent", {
              "detail": {"value": selectedLearner }
            });
            document.dispatchEvent(durationEvent);
            /*const categoryEvent = new CustomEvent("local_category_change_progress", {
              "detail": {value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}
            });
            document.dispatchEvent(categoryEvent);
            const groupEvent = new CustomEvent("local_group_change_time_spent", {
              "detail": {value:null, label:__('All', 'learndash-reports-by-wisdmlabs')}
            });
            document.dispatchEvent(groupEvent);*/
            this.setState({ learner:selectedLearner });
            this.setState({
                category:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
                group:{value:null, label:__('All', 'learndash-reports-by-wisdmlabs')},
            }); //Clear category, course , lesson, topics selected.
            // this.updateSelectorsFor('learner', selectedLearner.value);
        }
    }

    handleLearnerSearch = (inputString, callback) => {
        // perform a request
        let requestResults = []
        if ( inputString ) {
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
        } else {
            if ('group_leader'==wisdmLdReportsGetUserType()) {
                let groupUsers = wrldGetGroupAdminUsers();
                requestResults.push({value:groupUsers[0].id, label:groupUsers[0].display_name});
                this.setState({learner: requestResults[0]});
                callback(requestResults)
            } else {
                  let callback_path  = '/wp/v2/users/?search='
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
                      callback(requestResults);
                   }).catch((error) => {
                      callback(requestResults)
                });
            }
        }
    }

    getDefaultOptions = () => {
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

    componentDidMount() {
        //Patch logic for react state updaete on browser refresh bug.
        document.addEventListener('progress-parent-groups-changed', this.changeGroups);
        document.addEventListener('progress-parent-category-changed', this.changeCategory);
        document.addEventListener('progress-parent-course-changed', this.changeCourses);
        let lock_icon = '';
        if ( false/*false==wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active*/) {
            lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports"></span>
        }
        this.setState({lock_icon: lock_icon});       
    }

    static getDerivedStateFromProps(props, state) {
        if( props.categories !== state.categories ){
            //Change in props
            return{
                categories: props.categories
            };
        }
        if( props.groups !== state.groups ){
            //Change in props
            return{
                groups: props.groups
            };
        }
        if( props.group !== state.group ){
            //Change in props
            return{
                group: props.group
            };
        }
        if( props.courses !== state.courses ){
            //Change in props
            return{
                courses: props.courses
            };
        }
        if( props.course !== state.course ){
            //Change in props
            return{
                course: props.course
            };
        }
        if( props.category !== state.category ){
            //Change in props
            return{
                category: props.category
            };
        }
        if( props.learner !== state.learner ){
            //Change in props
            return{
                learner: props.learner
            };
        }
        if( props.active_tab !== state.active_tab ){
            //Change in props
            return{
                active_tab: props.active_tab
            };
        }
        return null; // No change to state
    }

  render(){
    let proclass = 'select-control learner-time-spent width-50-percent';
    let wrldplaceholder = __('Search', 'learndash-reports-by-wisdmlabs');
    if (true!=wisdm_learndash_reports_front_end_script_report_filters.is_pro_version_active) {
        proclass = 'ldr-pro width-50-percent';
    }
    return (
    	<div class={ this.state.active_tab == 0 ? "wisdm-learndash-reports-local-filters mr-bottom-48" : "wisdm-learndash-reports-local-filters mr-bottom-15"}>
            <Tabs selectedIndex={this.state.active_tab} onSelect={this.handleTabSelection}>
                <TabList>
                  <Tab><span class="wrld-labels">{wisdm_reports_get_ld_custom_lebel_if_avaiable('Courses')}</span></Tab>
                  <Tab><span class="wrld-labels">{__('Learners', 'learndash-reports-by-wisdmlabs')}</span></Tab>
                </TabList>
                <TabPanel>
                    <div className='wrld-time-spent-dropdown-filter'>
                        <span onClick={this.toggleDropdown } class="wrld-time-spent-filter-icon"><img className='wrld-ts-img-icon' src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/time-spent-filter.svg'}>
                        </img> </span>
                        <span className='wrld-time-spent-filter-text' onClick={this.toggleDropdown } > {__('3 Filters', 'learndash-reports-by-wisdmlabs')}  </span>
                        <div className="wrld-filter-checkboxes">
                            <div className='wrld-filter-checkbox-container'>
                            
                                <div class="checkbox-wrapper course-checkbox-wraper">
                                    <label>
                                    <input type="checkbox"
                                        name="time_spent_course"
                                        className='wrld-ts-course-checkbox'
                                        value="true"
                                        defaultChecked="true"
                                        disabled="true"
                                    />
                                    {__( "Course", 'learndash-reports-by-wisdmlabs' )}
                                    </label>
                                </div>
                                <div class="wrld-group-category-wrap">
                                <div class="checkbox-wrapper">
                                    <label>
                                    <input type="checkbox"
                                        name="time_spent_category"
                                        className='wrld-ts-category-checkbox'
                                        value={this.state.is_category_enabled}
                                        onChange={this.toggleCategoryFilterChange}
                                        defaultChecked={this.state.is_category_enabled}
                                    />
                                    {__( "Category", 'learndash-reports-by-wisdmlabs' )}
                                    </label>
                                </div>
                                <div class="checkbox-wrapper">
                                    <label>
                                    <input type="checkbox"
                                        name="time_spent_group"
                                        className='wrld-ts-group-checkbox'
                                        value={this.state.is_group_enabled}
                                        onChange={this.toggleGroupFilterChange}
                                        defaultChecked={this.state.is_group_enabled}
                                    />
                                    {__( "Group", 'learndash-reports-by-wisdmlabs' )}
                                    </label>
                                </div>
                                </div>
                             
                            </div>
                        </div>
                    </div>
                   { (this.state.is_group_enabled || this.state.is_category_enabled) && <div className='wrld-time-spent-category-group-dropdown'>
                      { this.state.is_category_enabled && <div class="selector">
                            <div class="selector-label">{__('Categories','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                            </div>
                            <div className = 'select-control'>
                            <Select 
                                onChange={this.handleCategoryChange.bind(this)}
                                isDisabled={this.state.categories_disabled}
                                options={this.state.categories}
                                value={{value: this.state.category.value, label: this.state.category.label}}
                            />
                            </div>
                        </div>
                        }
                       { this.state.is_group_enabled && <div class="selector">
                            <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                            </div>
                            <div className = 'select-control'>
                                <Select 
                                    onChange={this.handleGroupChange.bind(this)}
                                    isDisabled={this.state.groups_disabled}
                                    options={this.state.groups}
                                    value={{value: this.state.group.value, label: this.state.group.label}}
                                    />
                            </div>
                        </div>
                        }
                    </div> }
                    <div className='wrld-time-spent-course-dropdown'>
                        <div class="selector course-selector">
                            <div class="selector-label">{__('Courses','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                            </div>
                            <div className = 'select-control'>
                                <Select 
                                    onChange={this.handleCourseChange.bind(this)}
                                    options={this.state.courses}
                                    value={{value: this.state.course ? this.state.course.value : 0, label: this.state.course ? this.state.course.label : __('No Courses found', 'learndash-reports-by-wisdmlabs')}}
                                    />
                            </div>
                        </div>
                    </div>
                    
                </TabPanel>
                <TabPanel>
                    <div class="selector lr-learner learner-dd">
                        <div class="selector-label">{__('Learners','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
                        </div>
                        <div className = { proclass }>
                        <AsyncSelect
                            components={{ DropdownIndicator:() => null, IndicatorSeparator:() => null, NoOptionsMessage: (element) => {return element.selectProps.inputValue.length>2?__(' No learners found for the search string \'' + element.selectProps.inputValue +'\'', 'learndash-reports-by-wisdmlabs'):__(' Type 3 or more letters to search', 'learndash-reports-by-wisdmlabs') }}}
                            placeholder={wrldplaceholder}
                            isDisabled={this.state.learners_disabled}
                            value={this.state.learner}
                            loadOptions={this.handleLearnerSearch}
                            onChange={this.handleLearnerChange}
                            defaultOptions={this.state.default_options}
                            // defaultOptions="true"
                            isClearable="true"
                        />
                        </div>
                    </div>
                </TabPanel>
            </Tabs>
      </div>
    );
  }
}

export default LocalFilters;
