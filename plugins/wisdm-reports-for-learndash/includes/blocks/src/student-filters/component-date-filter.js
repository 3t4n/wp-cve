import { __ } from '@wordpress/i18n';
import { createElement } from '@wordpress/element'
import React, { Component, CSSProperties } from "react";
import "daterangepicker/daterangepicker.css";
var daterangepicker = require("daterangepicker");

class ComponentDatepicker extends Component {

  constructor(props) {
    super(props);
    // this.state = {
    //   start : moment(new Date(wisdm_ld_reports_common_script_data.start_date)),
    //   end   : moment(new Date(wisdm_ld_reports_common_script_data.end_date)),
    // }
    this.state = {
      start : moment(props.start * 1000),
      end   : moment(props.end * 1000),
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
    let custom_ranges    = {}
    custom_ranges[lbl_today]        = [moment(), moment()],
    custom_ranges[lbl_yesterday]    = [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    custom_ranges[lbl_last_7_days]  = [moment().subtract(6, 'days'), moment()],
    custom_ranges[lbl_last_30_days] = [moment().subtract(29, 'days'), moment()],
    custom_ranges[lbl_this_month]   = [moment().startOf('month'), moment().endOf('month')],
    custom_ranges[lbl_last_month]   = [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]

    let locale_config = {
      
      "applyLabel": __('Select', 'learndash-reports-by-wisdmlabs'),
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

    jQuery('.js-daterangepicker-predefined2').daterangepicker({
      locale:locale_config,
      startDate: this.state.start,
      endDate: this.state.end,
      ranges: custom_ranges,
      maxDate:moment()
    }, durationUpdatedCallback);

    durationUpdatedCallback(this.state.start, this.state.end);
    document.addEventListener('date_updated', this.durationUpdated);
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
      <div class="wisdm-learndash-reports-date-filters-container2">         
          <div className="js-daterangepicker-predefined2">
              <span>{this.state.start.format('MMM D, YYYY')} -  {this.state.end.format('MMM D, YYYY')}</span>
          </div>
      </div>
    );
  }
}

export default ComponentDatepicker;

function durationUpdatedCallback(start, end) {
  jQuery('.js-daterangepicker-predefined2').on('apply.daterangepicker', function(ev, picker) {
    //do something, like clearing an input
    const durationEvent = new CustomEvent("date_updated", {
        "detail": {"startDate":start.unix(),"endDate":end.unix(), "startDateObject": start, "endDateObject":end }
      });
    document.dispatchEvent(durationEvent);
  });
}

document.addEventListener("DOMContentLoaded", function(event) {

  function durationUpdatedOldCallback(start, end) {
    // To trigger the Event
    jQuery('.js-daterangepicker-predefined2 span').html(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
    jQuery('.js-daterangepicker-predefined2').on('apply.daterangepicker', function(ev, picker) {
      const durationEvent = new CustomEvent("date_updated", {
          "detail": {"startDate":start.unix(),"endDate":end.unix(), "startDateObject": start, "endDateObject":end }
        });
      document.dispatchEvent(durationEvent);
    });
  }

  let elem = document.getElementsByClassName('export-date-range');
  if (elem.length>0) {
    ReactDOM.render(React.createElement(ComponentDatepicker), elem[0]); 
  } else {
    //Backword compatibility with version < 1.0.3
    var start = moment(new Date(wisdm_ld_reports_common_script_data.start_date));
    var end   = moment(new Date(wisdm_ld_reports_common_script_data.end_date));
    // jQuery('.report-title > span').text(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));
        // To trigger the Event

    jQuery('.js-daterangepicker-predefined2').daterangepicker({
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
