import React, { Component } from "react";
import { __ } from '@wordpress/i18n';

class WisdmLoader extends React.Component {

    constructor(props) {
      super(props);
    }

    render() {
      let loadingData = '';
      let show_text = '';
      if ( true == this.props.text ) {
        show_text = <span className='supporting-text'>{__( 'Your report is being generated.', 'learndash-reports-by-wisdmlabs' )}</span>;
      }
      loadingData = <div class="wisdm-learndash-reports-chart-block">
                    <div class="wisdm-learndash-reports-revenue-from-courses graph-card-container">
                      <div class="wisdm-graph-loading">
                        <img src={wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/loader.svg'}>
                        </img>
                        {show_text}
                      </div>
                </div>
              </div>;
      return loadingData;
    }
  }

  export default WisdmLoader;
