import './index.scss';
import { __ } from '@wordpress/i18n';
import React, { Component } from "react";
import WisdmLoader from '../commons/loader/index.js';

document.addEventListener('wisdm-ld-reports-report-type-selected', function(event){
  if('quiz-reports'==event.detail.active_reports_tab) {
    if(!wisdm_ld_reports_common_script_data.is_pro_version_active){
      let upgrade_button = '';
      if (wisdm_ld_reports_common_script_data.is_admin_user) {
        upgrade_button = '<div><a class="wrld-upgrade-btn" target="__blank" href="https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=quiz-reports-all-attempts-log&utm_campaign=upgradetopro#pricing">' + __('Upgrade to PRO', 'learndash-reports-by-wisdmlabs') +'</a></div> <span>' + __("OR", "learndash-reports-by-wisdmlabs") + '</span>';
      }
        const img = "<img src="+wisdm_ld_reports_common_script_data.plugin_asset_url+"'/images/qrt.png'}></img>";
        const ovrlay_data = '<div class="wrld-upgrade-container"><div class="wrld-upgrade-content"><span>'+ __('Available in WISDM Reports PRO', 'learndash-reports-by-wisdmlabs') + '</span>' + upgrade_button + '<div><a class="wrld-learn-more" target="__blank" href="https://wisdmlabs.com/reports-for-learndash/?utm_source=reports-dashborad&utm_medium=quiz-reports-all-attempts-log&utm_campaign=upgradetopro#pricing">' + __("Learn More", "learndash-reports-by-wisdmlabs") + '</a></div></div></div>';
        jQuery('.wisdm-learndash-reports-quiz-reports').html(img);
        jQuery('.wisdm-learndash-reports-quiz-reports').append(ovrlay_data);
        jQuery('.wisdm-learndash-reports-quiz-reports').parent().addClass('wrld-dummy-report');
    }
    jQuery('.wisdm-learndash-reports-quiz-reports').parent().show();
    let elem = document.getElementsByClassName('wrld-loader');
    if (elem.length>0) {
      ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
    }
    // document.getElementById("wisdm-learndash-report-filters-container").scrollIntoView();
  } else {
    jQuery('.wisdm-learndash-reports-quiz-reports').parent().hide();
    // document.getElementById("wisdm-learndash-report-filters-container").scrollIntoView();
  }
});

document.addEventListener('wisdm-ld-reports-default-quiz-report-filters-applied', function(event){
  let url = wisdmAddParamToURL('ld_report_type', 'quiz-reports');
      //url = wisdmAddParamToURL('referer', url);
      url = wisdmAddParamToURL('report', 'quiz', url);
      url = wisdmAddParamToURL('period', 'year', url);
      url = wisdmAddParamToURL('from_date', '', url);
      url = wisdmAddParamToURL('to_date', '', url);
      url = wisdmAddParamToURL('qre_search_field', event.detail.selection_label, url);
      url = wisdmAddParamToURL('search_result_id', event.detail.selection_id, url);
      url = wisdmAddParamToURL(event.detail.selection_type, event.detail.selection_id, url);
      let searchType = 'post';
      if ('quiz'==event.detail.selection_type) {
        searchType= 'post';
        // url = wisdmAddParamToURL('screen', event.detail.selection_type, url);
   } else if ('user'==event.detail.selection_type) {
        searchType= 'user';
   }
    //window.location.href = url;
    let page_number = 1;
    let pn = jQuery('div.pagination-section > input.page').val();
    if (undefined!=pn && pn>0) {
      page_number = pn;
    }
    jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
    jQuery('.wrld-loader').show();
    jQuery.get(
      wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
      {
        action: 'get_quiz_reports_data',
        report: event.detail.report_selector,
        search_result_type:searchType,
        search_result_id:event.detail.selection_id,
        qre_search_field:event.detail.selection_label,
        page: page_number,
        pageno: page_number,
        wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang
      },
      function( response ) {
        jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
        jQuery('.wrld-loader').hide();
        wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams = undefined!=response.data.entries.query_params?response.data.entries.query_params:[];
        showDatatable( '#qre_summarized_data', response.data.entries );
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page',
        );
        let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
      }
    );
});


document.addEventListener('wisdm-ld-reports-default-custom-quiz-report-filters-applied', function(event){
  let fields = event.detail.fields_selected;
  fields.course_filter = event.detail.selected_courses;
  fields.group_filter = event.detail.selected_groups;
  fields.quiz_filter = event.detail.selected_quizes;
  fields.start_date = event.detail.start_date;
  fields.end_date = event.detail.end_date;
  jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
  jQuery('.wrld-loader').show();
  jQuery.ajax(
    {
      type: 'POST',
      url: qre_export_obj.ajax_url,
      timeout: 100000,
      retry_count: 0,
      retry_limit: 1,
      data: {
        action: 'qre_save_filters',
        security: qre_export_obj.custom_reports_nonce,
        fields: fields,
      },
      success: function( result ) {
        jQuery( document ).trigger( 'custom_reports_config_set' );
        getCustomReports(1);
        jQuery('div.pagination-section > a').click(function(event){
          event.preventDefault();
          console.log(event);
        });
        let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
    },
      error: function( xhr, status, error_thrown ) {
        if ( status === 'timeout' ) {
            this.retry_count++;
            if ( this.retry_count <= this.retry_limit ) {
                console.log( 'Retrying' );
                jQuery.ajax( this );
                return;
          } else {
            console.error( 'request timed out' );
            jQuery( document ).trigger( 'custom_reports_config_set' );
          }
        } else {
          console.log( error_thrown );
          jQuery( document ).trigger( 'custom_reports_config_set' );
        }
      } 
    }
    );
});

/**
 * This callback is used to handle the logic when apply filters is done inside the modal.
 */
document.addEventListener('wisdm-ld-reports-custom-quiz-report-filters-applied', function(event){
  let fields = event.detail.fields_selected;
  fields.course_filter = event.detail.selected_courses;
  fields.group_filter = event.detail.selected_groups;
  fields.quiz_filter = event.detail.selected_quizes;
  fields.start_date = event.detail.start_date;
  fields.end_date = event.detail.end_date;
  jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
  jQuery('.wrld-loader').show();
  jQuery.ajax(
    {
      type: 'POST',
      url: qre_export_obj.ajax_url,
      timeout: 100000,
      retry_count: 0,
      retry_limit: 1,
      data: {
        action: 'qre_save_filters',
        security: qre_export_obj.custom_reports_nonce,
        fields: fields,
      },
      success: function( result ) {
        getCustomReports(1);
      },
      error: function( xhr, status, error_thrown ) {
        if ( status === 'timeout' ) {
            this.retry_count++;
            if ( this.retry_count <= this.retry_limit ) {
                console.log( 'Retrying' );
                jQuery.ajax( this );
                return;
          } else {
            console.error( 'request timed out' );
            jQuery( document ).trigger( 'custom_reports_config_set' );
          }
        } else {
          console.log( error_thrown );
          jQuery( document ).trigger( 'custom_reports_config_set' );
        }
      } 
    }
    );
  
    document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
});

function fetchDataCount( data ) {
  jQuery.ajax(
    {
      type: 'POST',
      url: qre_export_obj.ajax_url,
      timeout: 100000,
      retry_count: 0,
      retry_limit: 1,
      data: {
        action: 'wrld_export_entries',
        security: qre_export_obj.custom_reports_nonce,
        fields: data,
      },
      success: function( result ) {
        // getCustomReports(1);
        jQuery('.bulk-export-heading div span').text(result.data.count.attempt_count);
        // jQuery('.export-attempt-learner-answers .report-export-buttons div span').text(result.data.count.quiz_count);
        // jQuery('.bulk-export-progress label').text('Downloading ' + result.data.count.quiz_count + ' quiz attempts');
      }
    }
  );
}

document.addEventListener('wrld-fetch-export-data-count', function(event) {
  
    let fields = {};
    fields.course_filter = event.detail.selected_courses;
    fields.group_filter = event.detail.selected_groups;
    fields.quiz_filter = event.detail.selected_quizes;
    fields.start_date = event.detail.start_date;
    fields.end_date = event.detail.end_date;
    fetchDataCount(fields);
});

document.addEventListener('wrld-bulk-export-attempt-results-success', function(event){
  jQuery('.export-attempt-results .bulk-export-progress').addClass('wrld-hidden');
  jQuery('.export-attempt-results .bulk-export-progress progress').val(0);
  jQuery('.export-attempt-results .bulk-export-progress span').text('');
  jQuery('.export-attempt-results .bulk-export-download').append('<a class="button btn btn-primary" href="' + event.detail.result.data.link + '" download><span class="dashicons dashicons-download"></span>Download ' + event.detail.filetype.toUpperCase() + '</a>');
  jQuery('.export-attempt-results .bulk-export-download').removeClass('wrld-hidden');
});

document.addEventListener('wrld-bulk-export-learner-results-success', function(event){
  jQuery('.export-attempt-learner-answers .bulk-export-progress').addClass('wrld-hidden');
  jQuery('.export-attempt-learner-answers .bulk-export-progress progress').val(0);
  jQuery('.export-attempt-learner-answers .bulk-export-progress span').text('');
  jQuery('.export-attempt-learner-answers .bulk-export-download').append('<a class="button btn btn-primary" href="' + event.detail.result.data.link + '" download><span class="dashicons dashicons-download"></span>Download ' + event.detail.filetype.toUpperCase() + '</a>');
  jQuery('.export-attempt-learner-answers .bulk-export-download').removeClass('wrld-hidden');
});

document.addEventListener('wrld-bulk-export-attempt-results', function(event){
    jQuery('.export-attempt-results .bulk-export-progress label').text(event.detail.type.toUpperCase() + ' export in progress');
    jQuery('.export-attempt-results .bulk-export-progress').removeClass('wrld-hidden');
    // jQuery('.export-attempt-results .bulk-export-download').addClass('wrld-hidden');

    var max = 100;
    var current = 0;
    var entries = jQuery('.export-attempt-results .report-export-buttons div span').text();
    var interval = setInterval( function() {
      jQuery.ajax(
        {
          type: 'POST',
          url: qre_export_obj.ajax_url,
          timeout: 100000,
          retry_count: 0,
          retry_limit: 1,
          data: {
            action: 'wrld_export_progress_results',
            security: qre_export_obj.custom_reports_nonce,
          },
          success: function( result ) {
            current = result.data.percentage;
            if ( current <= max ) {
                jQuery('.export-attempt-results .bulk-export-progress progress').val(current);
                jQuery('.export-attempt-results .bulk-export-progress span').text(current + '% Complete');
            }
            if ( current >= max ) {
              clearInterval(interval);
              return;
            }
          },
          error: function( xhr, status, error_thrown ) {
            if ( status === 'timeout' ) {
                this.retry_count++;
                if ( this.retry_count <= this.retry_limit ) {
                    console.log( 'Retrying' );
                    jQuery.ajax( this );
                    return;
              } else {
                console.error( 'request timed out' );
              }
            } else {
              console.log( error_thrown );
            }
          } 
        }
        );
    }, 1000 );
});

document.addEventListener('wrld-bulk-export-learner-results', function(event){
    jQuery('.export-attempt-learner-answers .bulk-export-progress label').text(event.detail.type.toUpperCase() + ' export in progress');
    jQuery('.export-attempt-learner-answers .bulk-export-progress').removeClass('wrld-hidden');
    // jQuery('.export-attempt-learner-answers .bulk-export-download').addClass('wrld-hidden');

    var max = 100;
    var current = 0;
    var entries = jQuery('.export-attempt-results .report-export-buttons div span').text();
    var interval = setInterval( function() {
      jQuery.ajax(
        {
          type: 'POST',
          url: qre_export_obj.ajax_url,
          timeout: 100000,
          retry_count: 0,
          retry_limit: 1,
          data: {
            action: 'wrld_export_progress_results',
            security: qre_export_obj.custom_reports_nonce,
          },
          success: function( result ) {
            current = result.data.percentage;
            if ( current <= max ) {
              jQuery('.export-attempt-learner-answers .bulk-export-progress progress').val(current);
              jQuery('.export-attempt-learner-answers .bulk-export-progress span').text(current + '% Complete');
            }
            if ( current >= max ) {
              clearInterval(interval);
              return;
            }
          },
          error: function( xhr, status, error_thrown ) {
            if ( status === 'timeout' ) {
                this.retry_count++;
                if ( this.retry_count <= this.retry_limit ) {
                    console.log( 'Retrying' );
                    jQuery.ajax( this );
                    return;
              } else {
                console.error( 'request timed out' );
              }
            } else {
              console.log( error_thrown );
            }
          } 
        }
        );
    }, 1000 );
});

/**
 * This callback is used to handle the logic when apply filters is done inside the modal.
 */
document.addEventListener('wrld-bulk-export-attempt-results', function(event){
  let fields = {};
  fields.course_filter = event.detail.selected_courses;
  fields.group_filter = event.detail.selected_groups;
  fields.quiz_filter = event.detail.selected_quizes;
  fields.type = event.detail.type;
  fields.start_date = event.detail.start_date;
  fields.end_date = event.detail.end_date;
  jQuery('.filter-section').css({'opacity': 0.5, 'pointer-events': 'none'});
  jQuery('.bulk-export-download').css({'opacity': 0.5, 'pointer-events': 'none'});
  // jQuery('.wrld-loader').show();
  jQuery.ajax(
    {
      type: 'POST',
      url: qre_export_obj.ajax_url,
      timeout: 100000,
      retry_count: 0,
      retry_limit: 1,
      data: {
        action: 'wrld_export_attempt_results',
        security: qre_export_obj.custom_reports_nonce,
        fields: fields,
      },
      success: function( result ) {
        jQuery('.filter-section').css({'opacity': 1, 'pointer-events': 'auto'});
        jQuery('.bulk-export-download').css({'opacity': 1, 'pointer-events': 'auto'});
        const attemptQuizReport = new CustomEvent("wrld-bulk-export-attempt-results-success", {
            "detail": {"result": result, "filetype": event.detail.type}});
        document.dispatchEvent(attemptQuizReport);
        // getCustomReports(1);
      },
      error: function( xhr, status, error_thrown ) {
        if ( status === 'timeout' ) {
            this.retry_count++;
            if ( this.retry_count <= this.retry_limit ) {
                console.log( 'Retrying' );
                jQuery.ajax( this );
                return;
          } else {
            console.error( 'request timed out' );
          }
        } else {
          console.log( error_thrown );
        }
      } 
    }
    );
  
    // document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
});

document.addEventListener('wrld-bulk-export-learner-results', function(event){
  let fields = {};
  fields.course_filter = event.detail.selected_courses;
  fields.group_filter = event.detail.selected_groups;
  fields.quiz_filter = event.detail.selected_quizes;
  fields.type = event.detail.type;
  fields.start_date = event.detail.start_date;
  fields.end_date = event.detail.end_date;
  // jQuery('.wrld-loader').show();
  jQuery('.filter-section').css({'opacity': 0.5, 'pointer-events': 'none'});
  jQuery('.bulk-export-download').css({'opacity': 0.5, 'pointer-events': 'none'});
  jQuery.ajax(
    {
      type: 'POST',
      url: qre_export_obj.ajax_url,
      timeout: 100000,
      retry_count: 0,
      retry_limit: 1,
      data: {
        action: 'wrld_export_learner_results',
        security: qre_export_obj.custom_reports_nonce,
        fields: fields,
      },
      success: function( result ) {
        jQuery('.filter-section').css({'opacity': 1, 'pointer-events': 'auto'});
        jQuery('.bulk-export-download').css({'opacity': 1, 'pointer-events': 'auto'});
        const attemptQuizReport = new CustomEvent("wrld-bulk-export-learner-results-success", {
            "detail": {"result": result, "filetype": event.detail.type}});
        document.dispatchEvent(attemptQuizReport);
        // getCustomReports(1);
      },
      error: function( xhr, status, error_thrown ) {
        if ( status === 'timeout' ) {
            this.retry_count++;
            if ( this.retry_count <= this.retry_limit ) {
                console.log( 'Retrying' );
                jQuery.ajax( this );
                return;
          } else {
            console.error( 'request timed out' );
          }
        } else {
          console.log( error_thrown );
        }
      } 
    }
    );
  
    // document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
});

document.addEventListener('wisdm-ld-custom-report-type-select', function(event) {
  // let url = wisdmAddParamToURL('ld_report_type', 'quiz-reports');
  // url     = wisdmAddParamToURL('report', event.detail.report_selector, url);
  jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
  jQuery('.wrld-loader').show();
  if ('custom'==event.detail.report_selector) {
    getCustomReports(1);
  } else {
  let page_number = 1;
  jQuery.get(
    wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
    {
      action: 'get_quiz_reports_data',
      report: event.detail.report_selector,
      page: page_number,
      pageno: page_number,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang,

    },
    function( response ) {
      jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
      showDatatable( '#qre_summarized_data', response.data.entries );
      if ( event.detail.report_selector == '' ) {
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page'
        );
      }
      let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
    }
  );
  }
  // window.location.href = url;
});

/**
 * This method is used to change number of entries shown per page.
 * @param  string pagination_form Pagination Form Selector.
 * @param  string limit_selector  Limit Dropdown Selector.
 */
window.change_entry_count = function( pagination_form, limit_selector) {
  var self = this;
  
  jQuery( limit_selector ).on( 'change', function(){
    let page_number = 1;
    let limit       = jQuery( this ).find( 'option:selected' ).val();
    
    let data        = {
      action: 'get_quiz_reports_data',
      page: page_number,
      pageno: page_number,
      limit: limit,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang,
    };

    let queryParams = undefined!=wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams?wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams:[];
    jQuery.each(queryParams, function(key, value){
      if ( null != data[key] ) {
          return true;
      }
      if (null!==value) {
        data[key] = value;  
      }        
    });

    jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');

    jQuery('.wrld-loader').show();
    jQuery.get(
      wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
      data,
      function( response ) {
        jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
        showDatatable( '#qre_summarized_data', response.data.entries );
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page'
        );
        let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
      }
    );
  } );
}

/**
 * This method is used to control pagination inputs.
 * @param  string pagination_form      Pagination form Selector
 * @param  string page_number_selector Page Number Input Selector
 * @param  string previous_page_btn    Previous page button Selector
 * @param  string next_page_button     Next page button Selector
 */
window.change_page_number = function( pagination_form, page_number_selector, previous_page_btn, next_page_button ) {
  var self = this;
  jQuery( page_number_selector ).on( 'change', function(){
    var page_number     = parseInt( jQuery( this ).val() );
    var max_page_number = parseInt( jQuery( this ).attr( 'data-max' ) ); 
    if ( isNaN( page_number ) ) {
      return false;
    }
    if ( page_number > max_page_number ) {
      return false;
    }
    let limit       = jQuery( 'select.limit' ).find( 'option:selected' ).val();
    let data        = {
      action: 'get_quiz_reports_data',
      page: page_number,
      pageno: page_number,
      limit: limit,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang
    };

    let queryParams = undefined!=wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams?wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams:[];
    jQuery.each(queryParams, function(key, value){
      if ( null != data[key] ) {
        return true;
      }
      if (null!==value) {
        data[key] = value;  
      }        
    });


    jQuery.get(
      wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
      data
      ,
      function( response ) {
        jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
        showDatatable( '#qre_summarized_data', response.data.entries );
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page'
        );
        jQuery( page_number_selector ).val( page_number );
      }
    );
  });
  jQuery( next_page_button ).on( 'click', function() {
    var page_number     = parseInt( jQuery( page_number_selector ).val() );
    var max_page_number = parseInt( jQuery( page_number_selector ).attr( 'data-max' ) );
    if ( page_number === max_page_number ) {
      jQuery( this ).attr( 'disabled', 'disabled' );
      return false;
    }
    let limit       = jQuery( 'select.limit' ).find( 'option:selected' ).val();
    let data        = {
      action: 'get_quiz_reports_data',
      page: page_number + 1,
      pageno: page_number + 1,
      limit: limit,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang,
    };
    let queryParams = undefined!=wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams?wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams:[];
    jQuery.each(queryParams, function(key, value){
      if ( null != data[key] ) {
        return true;
      }
      if (null!==value) {
        data[key] = value;  
      }        
    });
    jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
    jQuery('.wrld-loader').show();
    jQuery.get(
      wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
      data,
      function( response ) {
        jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
        showDatatable( '#qre_summarized_data', response.data.entries );
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page'
        );
        jQuery( page_number_selector ).val( page_number + 1 );
        let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
      }
    );
  } );
  jQuery( previous_page_btn ).on( 'click', function() {
    var page_number     = parseInt( jQuery( page_number_selector ).val() );
    if ( page_number === 1 ) {
      jQuery( this ).attr( 'disabled', 'disabled' );
      return false;
    }
    page_number = page_number - 1;
    if (page_number<1) {
      return false;
    }
    let limit       = jQuery( 'select.limit' ).find( 'option:selected' ).val();
    let data        = {
      action: 'get_quiz_reports_data',
      page: page_number,
      pageno: page_number,
      limit: limit,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang,
    };
    let queryParams = undefined!=wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams?wisdm_learndash_reports_front_end_script_quiz_reports.lastCustomDefaultQuiryParams:[];
    jQuery.each(queryParams, function(key, value){
        if ( null != data[key] ) {
          return true;
        }
      if (null!==value) {
        data[key] = value;  
      }        
    });
    jQuery('.qre-reports-content, .custom-reports-content, .qre-reports-content').html('');
    jQuery('.wrld-loader').show();
    jQuery.get(
      wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
      data,
      function( response ) {
        jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
        showDatatable( '#qre_summarized_data', response.data.entries );
        paginateReportsTable(
          '.pagination-form',
          'select.limit',
          '.pagination-section input.page',
          '.pagination-section button.previous-page',
          '.pagination-section button.next-page'
        );
        jQuery( page_number_selector ).val( page_number );
        let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {
         
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
      }
    );
  } );
}

function paginateReportsTable( pagination_form, limit_selector, page_number_selector, previous_page_btn, next_page_button) {
  change_entry_count( pagination_form, limit_selector);
  change_page_number( pagination_form, page_number_selector, previous_page_btn, next_page_button );
}

function showDatatable( selector, quiz_statistics_data ) {
    var quiz_reports_table;
    if ( typeof quiz_statistics_data === 'undefined' ) {
      return;
    }
    if ( quiz_statistics_data.hasOwnProperty( 'length' ) ) {
      return;
    }
    var $quiz_title = null;
    var $quiz_title_text = '';
    var $user_name = null;
    var $user_name_text = '';
    let index_offset = quiz_statistics_data.limit*(quiz_statistics_data.page-1);
    for ( var i = 0; i < quiz_statistics_data.data.length; i++ ) {
      if ( jQuery( window ).width() < 1500 ) {
        $quiz_title      = jQuery( quiz_statistics_data.data[ i ].quiz_title );
        $quiz_title_text = $quiz_title.text().substring( 0 , 30 );
        if ( $quiz_title.text().length > 30 ) {
          $quiz_title_text += '...';    
        }
        $quiz_title.text( $quiz_title_text );
        quiz_statistics_data.data[ i ].quiz_title = $quiz_title.outerHTML();
        $user_name      = jQuery( quiz_statistics_data.data[ i ].user_name );
        $user_name_text = $user_name.text().substring( 0 , 30 );
        if ( $user_name.text().length > 30 ) {
          $user_name_text += '...';   
        }
        $user_name.text( $user_name_text );
        quiz_statistics_data.data[ i ].user_name = $user_name.outerHTML();
      }
      quiz_statistics_data.data[ i ].index = index_offset + (i + 1);
    }
    quiz_reports_table = jQuery( selector ).DataTable(
    {
      paging: false,
              ordering: false,
              searching: false,
              info: false,
              responsive: false,
      data: quiz_statistics_data.data,
      columns: [
              { data: 'index' },
              { data: 'quiz_title' },
              { data: 'user_name' },
              { data: 'date_attempt' },
              { data: 'score' },
              { data: 'time_taken' },
              { data: 'link' },
          ],
          columnDefs: [
              {
                  targets: [ 0, 3, 4, 5, 6 ],
                  className: 'dt-center'
              },
              {
                targets: [ 1, 2 ],
                className: 'dt-left'
              }
            ],
            language: {
                emptyTable: quiz_statistics_data.no_data
              }
    });
  }


function wisdmAddParamToURL(param, value, url=qre_export_obj.first_custom_url){
  var hash       = {};
  var parser     = document.createElement('a');

  parser.href    = url;
  var parameters = parser.search.split(/\?|&/);

  for(var i=0; i < parameters.length; i++) {
      if(!parameters[i])
          continue;

      var ary      = parameters[i].split('=');
      hash[ary[0]] = ary[1];
  }

  hash[param] = value;

  var list = [];  
  Object.keys(hash).forEach(function (key) {
      list.push(key + '=' + hash[key]);
  });

  parser.search = '?' + list.join('&');
  return parser.href;
}

jQuery('.mb-40').each(function(ind, ele){
    if( jQuery(ele).text().trim().length == 0 ) {
        jQuery(ele).css({'margin-bottom': '0px'});
    }
});

function getCustomReports(page_number) {
  //jQuery('#wisdm-learndash-reports-quiz-report-view').html('');
  if (page_number<1) {
    return false;
  }
  jQuery.get(
    wisdm_learndash_reports_front_end_script_quiz_reports.ajaxurl,
    {
      action: 'get_quiz_reports_data',
      report: 'custom',
      page: page_number,
      pageno: page_number,
      wpml_lang: wisdm_ld_reports_common_script_data.wpml_lang,
    },
    function( response ) {
      jQuery('.wisdm-learndash-reports-quiz-reports').html( response.data.html );
      showDatatable( '#qre_summarized_data', response.data.entries );
      //Disable link redirection in the datatable retrieved & implement ajax based filtering. 
      jQuery('div.pagination-section > a').click(function(event){
        event.preventDefault();
        let link = jQuery(this).attr('href');
        let page = getParameterByName('paged',link);
        getCustomReports(page);
      });
      let elem = document.getElementsByClassName('wrld-loader');
        if (elem.length>0) {    
          ReactDOM.render(React.createElement(WisdmLoader), elem[0]);  
        }
    }
  );
}

function getParameterByName(name, url) {
     if (!url) url = window.location.href;
     name = name.replace(/[\[\]]/g, "\\$&");
     var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
         results = regex.exec(url);
     if (!results) return null;
     if (!results[2]) return '';
     return decodeURIComponent(results[2].replace(/\+/g, " "));
 }

document.addEventListener("DOMContentLoaded", function(event) {
    if (wisdm_learndash_reports_front_end_script_quiz_reports.report_type=='quiz-reports') {
      jQuery('.wisdm-learndash-reports-quiz-reports').parent().show();
      jQuery( '.ld-course-field' ).hide();
      document.getElementById("wisdm-learndash-reports-quiz-report-view").scrollIntoView();
    } else {
      jQuery('.wisdm-learndash-reports-quiz-reports').parent().hide();
      jQuery( '.ld-course-field' ).css('display', 'flex');

    }

    jQuery('.qre-reports-content .back-button').on('click',function(e){
        e.preventDefault();
        open(location, '_self').close();
    });
});




