import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
import Select from 'react-select';

class LocalFilters extends Component {

    constructor(props) {
        super(props);
        this.state = {
          group: props.group,
          course: props.course,
          groups: props.groups,
          courses: props.courses,
          loading_groups: false
        }
        this.changeGroups = this.changeGroups.bind(this);
        this.changeCourse = this.changeCourse.bind(this);
    }

    changeGroups(evnt) {
        this.setState({groups:event.detail.value});
    }

    changeCourse(evnt) {
        this.setState({course:evnt.detail.value});
    }

    handleGroupChange = (selectedGroup) => {
        const durationEvent = new CustomEvent("local_group_change", {
          "detail": {"value": selectedGroup }
        });
        document.dispatchEvent(durationEvent);
        this.setState({group:selectedGroup});
    }

    handleCourseChange = (selectedCourse) => {
        const durationEvent = new CustomEvent("local_course_change", {
          "detail": {"value": selectedCourse }
        });
        document.dispatchEvent(durationEvent);
        this.setState({course:selectedCourse});
    }

    componentDidMount() {
        //Patch logic for react state updaete on browser refresh bug.
        document.addEventListener('wisdm-ld-reports-parent-groups-changed', this.changeGroups);
        document.addEventListener('wisdm-ld-reports-course-changed', this.changeCourse);
    }

    static getDerivedStateFromProps(props, state) {
        if( props.courses !== state.courses ){
            //Change in props
            return{
                courses: props.courses
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
        if( props.course !== state.course ){
            //Change in props
            return{
                course: props.course
            };
        }
        return null; // No change to state
    }

  render(){
    return (
    	<div class="wisdm-learndash-reports-local-filters">
        <div class="selector lr-learner">
            <div class="selector-label">{__('Groups','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
            </div>
            <div className = 'select-control'>
              	<Select 
                    onChange={this.handleGroupChange.bind(this)}
    				options={this.state.groups}
    				value={{value: this.state.group.value, label: this.state.group.label}}
          			/>
            </div>
        </div>
        <div class="selector lr-learner">
            <div class="selector-label">{__('Courses','learndash-reports-by-wisdmlabs')}{this.state.lock_icon}
            </div>
            <div className = 'select-control'>
            <Select 
                onChange={this.handleCourseChange.bind(this)}    
                options={this.state.courses}
                value={{value: this.state.course.value, label: this.state.course.label}}
            />
            </div>
        </div>
            
      </div>
    );
  }
}

export default LocalFilters;
