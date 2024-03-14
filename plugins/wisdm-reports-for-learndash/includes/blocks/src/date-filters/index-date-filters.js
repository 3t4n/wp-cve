import './index.scss';
import "daterangepicker/daterangepicker.css";
var daterangepicker = require("daterangepicker");
import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";

// To assign event


class Datepickers extends Component {

  constructor(props) {
    super(props);
    this.state = {
      start : moment(new Date(wisdm_ld_reports_common_script_data.start_date)),
      end   : moment(new Date(wisdm_ld_reports_common_script_data.end_date)),
    }

    this.durationUpdated               = this.durationUpdated.bind(this);
  }

  componentDidMount() {
    let lbl_today        = __('Today', 'learndash-reports-by-wisdmlabs');
    let lbl_yesterday    = __('Yesterday', 'learndash-reports-by-wisdmlabs');
    let lbl_last_7_days  = __('Last 7 Days', 'learndash-reports-by-wisdmlabs');
    let lbl_last_30_days = __('Last 30 Days', 'learndash-reports-by-wisdmlabs');
    let lbl_this_month   = __('This Month', 'learndash-reports-by-wisdmlabs');
    let lbl_last_month   = __('Last Month', 'learndash-reports-by-wisdmlabs');
    let lbl_last_year   = __('Last 12 Months', 'learndash-reports-by-wisdmlabs');
    let lbl_last_two_year   = __('Last 2 Years', 'learndash-reports-by-wisdmlabs');
    let lbl_last_three_year   = __('Last 3 Years', 'learndash-reports-by-wisdmlabs');
    let custom_ranges    = {}
    custom_ranges[lbl_today]        = [moment(), moment()],
    custom_ranges[lbl_yesterday]    = [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    custom_ranges[lbl_last_7_days]  = [moment().subtract(6, 'days'), moment()],
    custom_ranges[lbl_last_30_days] = [moment().subtract(29, 'days'), moment()],
    custom_ranges[lbl_this_month]   = [moment().startOf('month'), moment().endOf('month')],
    custom_ranges[lbl_last_year]   = [moment().subtract(12, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month'),]
    custom_ranges[lbl_last_two_year]   = [moment().subtract(24, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month'),]
    custom_ranges[lbl_last_three_year]   = [moment().subtract(36, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month'),]

    let locale_config = {
      
      "applyLabel": __('Apply', 'learndash-reports-by-wisdmlabs'),
      "cancelLabel": __('Cancel', 'learndash-reports-by-wisdmlabs'),
      "fromLabel": __('From', 'learndash-reports-by-wisdmlabs'),
      "toLabel": __('To', 'learndash-reports-by-wisdmlabs'),
      "customRangeLabel": __('Custom Range', 'learndash-reports-by-wisdmlabs'),
      "weekLabel": __('W', 'learndash-reports-by-wisdmlabs'),
      "daysOfWeek": [
          __('Su', 'learndash-reports-by-wisdmlabs'),
          __('Mo', 'learndash-reports-by-wisdmlabs'),
          __('Tu', 'learndash-reports-by-wisdmlabs'),
          __('We', 'learndash-reports-by-wisdmlabs'),
          __('Th', 'learndash-reports-by-wisdmlabs'),
          __('Fr', 'learndash-reports-by-wisdmlabs'),
          __('Sa', 'learndash-reports-by-wisdmlabs'),
      ],
      "monthNames": [
          __('January', 'learndash-reports-by-wisdmlabs'),
          __('February', 'learndash-reports-by-wisdmlabs'),
          __('March', 'learndash-reports-by-wisdmlabs'),
          __('April', 'learndash-reports-by-wisdmlabs'),
          __('May', 'learndash-reports-by-wisdmlabs'),
          __('June', 'learndash-reports-by-wisdmlabs'),
          __('July', 'learndash-reports-by-wisdmlabs'),
          __('August', 'learndash-reports-by-wisdmlabs'),
          __('September', 'learndash-reports-by-wisdmlabs'),
          __('October', 'learndash-reports-by-wisdmlabs'),
          __('November',  'learndash-reports-by-wisdmlabs'),
          __('December',    'learndash-reports-by-wisdmlabs')
      ],
  }

    jQuery('#js-daterangepicker-predefined').daterangepicker({
      locale:locale_config,
      startDate: this.state.start,
      endDate: this.state.end,
      ranges: custom_ranges,
      maxDate:moment()
    }, durationUpdatedCallback);

    jQuery('#js-daterangepicker-predefined').on('apply.daterangepicker', function(ev, picker) {
      var start = picker.startDate;
      var end = picker.endDate;

      const durationEvent = new CustomEvent("duration_updated", {
        "detail": {"startDate":start.unix(),"endDate":end.unix(), "startDateObject": start, "endDateObject":end }
      });
     document.dispatchEvent(durationEvent);
      
    });

    durationUpdatedCallback(this.state.start, this.state.end);
    document.addEventListener('duration_updated', this.durationUpdated);
    const element = document.getElementsByClassName('edit-post-visual-editor__content-area');
    if (element.length) {
      const width = element[0].clientWidth;
      if(width > 1199){
          for (const el of element) {
            el.classList.add('wrld-xl');
          }
      }
      else if(width > 992){
          for (const el of element) {
            el.classList.add('wrld-lg');
          }
      }
      else if(width > 768){
          for (const el of element) {
            el.classList.add('wrld-m');
          }
      }
      else if(width > 584){
          for (const el of element) {
            el.classList.add('wrld-s');
          }
      }
      else{
          for (const el of element) {
            el.classList.add('wrld-xs');
          }
      }
    }
  }

  durationUpdated(event) {
    this.setState(
      {
        start:moment(new Date(event.detail.startDateObject)),
        end:moment(new Date(event.detail.endDateObject))
      }
      );
  }

  render(){
    return (
      <div class="wisdm-learndash-reports-date-filters-container"> 
        <div class="wisdm-ld-reports-title">
            <div class="report-title">
                <h2>{__('Reports Dashboard', 'learndash-reports-by-wisdmlabs')}</h2>
                <span>{this.state.start.format('MMM D, YYYY')} -  {this.state.end.format('MMM D, YYYY')}</span>
            </div>
        </div>
        <div class="wisdm-ld-reports-date-range-picker">
            <div class="date-filter-label">
                <i class="dashicons dashicons-calendar-alt"></i>
                <span>{__('SHOWING DATA FOR ', 'learndash-reports-by-wisdmlabs')}</span>
            </div>
            <div id="js-daterangepicker-predefined">
                <div class="dashicons dashicons-calendar-alt"> </div>
                <span>{this.state.start.format('MMM D, YYYY')} -  {this.state.end.format('MMM D, YYYY')}</span>
            </div>
        </div>
      </div>
    );
  }
}

export default Datepickers;

function durationUpdatedCallback(start, end) {
  // To trigger the Event
  const durationEvent = new CustomEvent("duration_updated", {
      "detail": {"startDate":start.unix(),"endDate":end.unix(), "startDateObject": start, "endDateObject":end }
    });
  //document.dispatchEvent(durationEvent);
}

document.addEventListener("DOMContentLoaded", function(event) {

  function durationUpdatedOldCallback(start, end) {
    // To trigger the Event
    jQuery('#js-daterangepicker-predefined span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
    jQuery('.wisdm-ld-reports-title .report-title>span').text(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));

    const durationEvent = new CustomEvent("duration_updated", {
        "detail": {"startDate":start.unix(),"endDate":end.unix(), "startDateObject": start, "endDateObject":end }
      });
    document.dispatchEvent(durationEvent);
  }

  let elem = document.getElementsByClassName('wisdm-learndash-reports-date-filters front');
  if (elem.length>0) {
    ReactDOM.render(React.createElement(Datepickers), elem[0]); 
  } else {
    //Backword compatibility with version < 1.0.3
    var start = moment(new Date(wisdm_ld_reports_common_script_data.start_date));
    var end   = moment(new Date(wisdm_ld_reports_common_script_data.end_date));
    jQuery('.report-title > span').text(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        // To trigger the Event

    jQuery('#js-daterangepicker-predefined').daterangepicker({
      startDate: start,
      endDate: end,
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      maxDate:moment()
    }, durationUpdatedOldCallback);

    durationUpdatedOldCallback(start, end);
  }   
});

  
