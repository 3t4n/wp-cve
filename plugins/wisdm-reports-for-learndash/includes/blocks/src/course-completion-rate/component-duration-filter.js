import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
import Select from 'react-select';

class DurationFilter extends Component {

  constructor(props) {
    super(props);
    this.state = {
      value : props.duration,      
    }
    this.handleValueChange = this.handleValueChange.bind(this);
    document.addEventListener('click', function handleClickOutsideBox(event) {
      const box = document.getElementsByClassName('download_csv');
      const box2 = document.getElementsByClassName('ellipses');
      // console.log(box);
      if (!box[0].contains(event.target) && !box2[0].contains(event.target)) {
        box[0].classList.add('wrld-hidden');
      }
    });
  }

  handleValueChange(event) {
    this.setState({value:event});
  	const durationEvent = new CustomEvent("local_completion_duration_change", {
        "detail": {"value": event }
      });
    document.dispatchEvent(durationEvent);
  }

  downloadLink(evnt) {
      jQuery(evnt.target).next().toggleClass('wrld-hidden');
  }

  startCSVDownload(event) {
    const durationEvent = new CustomEvent("start_csv_download_cc", {
        "detail": {"value": event }
      });
    document.dispatchEvent(durationEvent);
  }

  render(){
  	let options = [
  		{value: 'all', label: __('All time', 'learndash-reports-by-wisdmlabs')},
      {value: '6 months', label: __('Last 6 months', 'learndash-reports-by-wisdmlabs')},
      {value: '3 months', label: __('Last 3 months', 'learndash-reports-by-wisdmlabs')},
      {value: '30 days', label: __('Last 30 days', 'learndash-reports-by-wisdmlabs')},
      {value: '7 days', label: __('Last 7 days', 'learndash-reports-by-wisdmlabs')},
      {value: '1 day', label: __('Last 1 day', 'learndash-reports-by-wisdmlabs')},
  	]
    return (
    	<div class="wisdm-learndash-reports-duration-filter-completion">
          <div class="selector-label">{ __( 'Enrollment date','learndash-reports-by-wisdmlabs' ) }
          </div>
          <div className = 'select-control'>
          	<Select 
				options={options}
				onChange={this.handleValueChange}
				value={{value: this.state.value.value, label: this.state.value.label}}
			/>
          </div>
          <span className="ellipses" onClick={this.downloadLink.bind(this)}></span>
          <span className="wrld-hidden download_csv" onClick={this.startCSVDownload.bind(this)}>{__('Download CSV', 'learndash-reports-by-wisdmlabs')}</span>
        </div>
    );
  }
}

export default DurationFilter;
