import './index.scss';
import React, { Component } from "react";
import WisdmLoader from '../commons/loader/index.js';
import { __ } from '@wordpress/i18n';
import moment from 'moment';
var ld_api_settings = wisdm_learndash_reports_front_end_script_pending_assignments.ld_api_settings;

class PendingAssignments extends Component {
    constructor(props) {
      super(props);
  
      this.state = {
        isLoaded: false,
        error: null,
        start_date:null,
        end_date:null,
        lock_icon:'',
        start_date:moment(new Date(wisdm_ld_reports_common_script_data.start_date)).unix(),
        end_date:moment(new Date(wisdm_ld_reports_common_script_data.end_date)).unix(),
        upgrade_class:'wisdm-class'
      };

      this.durationUpdated  = this.durationUpdated.bind(this);
      this.updateBlock = this.updateBlock.bind(this);
    }
  
    durationUpdated(event) {
      this.setState({start_date:event.detail.startDate, end_date:event.detail.endDate});
      if (wisdm_learndash_reports_front_end_script_pending_assignments.is_pro_version_active) {
        this.updateBlock();
      }
    }

    componentDidMount() {    
        this.updateBlock();  
    }

    updateBlock() {
      if (undefined==ld_api_settings['sfwd-assignment']) {
        ld_api_settings['sfwd-assignment'] = 'sfwd-assignment';
      }
      let requestUrl = '/rp/v1/pending-assignments?start_date=' + this.state.start_date + '&end_date=' + this.state.end_date;
      if ( wisdm_ld_reports_common_script_data.wpml_lang ) {
        requestUrl += '&wpml_lang=' + wisdm_ld_reports_common_script_data.wpml_lang;
      }
      wp.apiFetch({
        path: requestUrl,
     }).then(response => {
       if (true!=wisdm_learndash_reports_front_end_script_pending_assignments.is_pro_version_active) {
        let lock_icon = <span title={__('Please upgrade the plugin to access this feature', 'learndash-reports-by-wisdmlabs')} class="dashicons dashicons-lock ld-reports top-corner"></span>
        let hideChange           = '';
        this.setState(
          {
          graphData: {
            pendingAssignments: '??',
            percentChange:'--' + '%',
            chnageDirectionClass:'udup',
            percentValueClass:'change-value',
            hideChange: hideChange
          },
          upgrade_class: 'wisdm-upgrade-to-pro',
          isLoaded: true,
          lock_icon:lock_icon,

        });
       }
       else {
          let pendingAssignments = response.pendingAssignments
          let percentChange        = 0;
          let chnageDirectionClass = 'udup';
          let percentValueClass    = 'change-value';
          let hideChange           = '';
          let udtxt = '';
          let udsrc = '';
          if (0<percentChange) {
            chnageDirectionClass = 'udup';
            percentValueClass    = 'change-value-positive';
            udtxt = __('Up', 'learndash-reports-by-wisdmlabs');
            udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
          } else if(0>percentChange) {
            chnageDirectionClass = 'uddown'
            percentValueClass    = 'change-value-negative';
            udtxt = __('Down', 'learndash-reports-by-wisdmlabs');
            udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/down.png';
          } else if( 0 == percentChange ){
            hideChange = 'wrld-hidden';
            udtxt = __('Up', 'learndash-reports-by-wisdmlabs');
            udsrc = wisdm_learndash_reports_front_end_script_total_revenue_earned.plugin_asset_url + '/images/up.png';
          }
          this.setState(
                  {
                  isLoaded: true,
                  graphData: {
                    pendingAssignments: pendingAssignments,
                    percentChange:percentChange + '%',
                    chnageDirectionClass:chnageDirectionClass,
                    percentValueClass:percentValueClass,
                    hideChange: hideChange,
                    udtxt: udtxt,
                    udsrc: udsrc
                  },
                });
             }
      }).catch((error) => {
        this.setState({
          error:error,
              graph_summary:[],
          series:[],
          isLoaded: true,
        });
      });
    }
  
    render() {
      let body = <div></div>;
    if (!this.state.isLoaded) {
        // yet loading
        body =  <WisdmLoader />
    } else if (this.state.error) {
        // error
        body = <div class="wisdm-learndash-reports-chart-block error">
        <div>{this.state.error.message}</div>
        </div>;
    } else {
      let upgrade_notice = '';
      if ( true==wisdm_learndash_reports_front_end_script_pending_assignments.is_admin_user ) {
        upgrade_notice = <a class="overlay pro-upgrade" href="https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=assignments-pending&utm_campaign=upgradetopro#pricing" target="__blank">
                <div class="description">
                  <span class="upgrade-text">{__('Available in PRO version')}</span>
                  <button class="upgrade-button">{__('UPGRADE TO PRO', 'learndash-reports-by-wisdmlabs')}</button>
                </div>
              </a>;
      }
      body =
      <div class={"wisdm-learndash-reports-chart-block " +  this.state.upgrade_class}> 
            {this.state.lock_icon} 
            <div class="pending-assignments-container top-card-container ">
                <div class="pending-assignments-icon">
                <img src={wisdm_learndash_reports_front_end_script_pending_assignments.plugin_asset_url + '/images/icon_pending_assignment_counter.png'}>   
                </img>
                </div>
                <div class="pending-assignments-details">
                    <div class="pending-assignments-text top-label-text">
                        <span>{__('Assignments Pending', 'learndash-reports-by-wisdmlabs')}</span>
                    </div>
                    <div class="pending-assignments-figure">
                        <span>{this.state.graphData.pendingAssignments}</span>
                    </div>
                    <div class={`pending-assignments-percent-change ${this.state.graphData.hideChange}`}>
                      <span class={this.state.graphData.chnageDirectionClass}>
                        <img src={this.state.graphData.udsrc}></img>
                      </span>
                      <span class={this.state.graphData.percentValueClass}>{this.state.graphData.percentChange}</span>
                      <span class="ud-txt">{this.state.graphData.udtxt}</span>
                    </div>
                </div>
            </div>
              {upgrade_notice}
      </div>;
    }
      return (body);
    }
  }

export default PendingAssignments;

document.addEventListener("DOMContentLoaded", function(event) {
    let elem = document.getElementsByClassName('wisdm-learndash-reports-pending-assignments front');
    if (elem.length>0) {
        ReactDOM.render(React.createElement(PendingAssignments), elem[0]); 
    }
});

