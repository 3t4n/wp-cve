import React, { Component } from "react";
import { __ } from '@wordpress/i18n';

class DummyReports extends React.Component {

    constructor(props) {
        super(props);
        this.image = undefined!=props.image_path?props.image_path:'#';
        this.url = undefined!=props.url?props.url:'https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=learner-reports&utm_campaign=Learner-Reports&utm_term=learner-reports#pricing'
    }

    render() {
      let dummyContent = '';
      let upgrade_button = '';
      let or_txt = '';
      if (wisdm_ld_reports_common_script_data.is_admin_user) {
        upgrade_button = <div><a className="wrld-upgrade-btn" target='__blank' href={this.url}>{__('Upgrade to PRO', 'learndash-reports-by-wisdmlabs')}</a></div>;
        or_txt = <span>{__('OR', 'learndash-reports-by-wisdmlabs')}</span>;
      }
      dummyContent = <div class={"wisdm-learndash-reports-chart-block wrld-dummy-images"}> 
      <div class="wisdm-learndash-reports-time-spent-on-a-course graph-card-container">
        <div className="wrld-upgrade-container">
          <div className="wrld-upgrade-content">
            <span>{__('Available in WISDM Reports PRO', 'learndash-reports-by-wisdmlabs')}</span>
            {upgrade_button} {or_txt}
            <div><a className="wrld-learn-more" target="__blank" href={this.url}>{__('Learn More', 'learndash-reports-by-wisdmlabs')}</a></div>
          </div>
        </div>
        <img src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/' + this.image}></img>  
      </div>
    </div>;
      return dummyContent;
    }
  }

  export default DummyReports;


