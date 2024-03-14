
function wisdm_reports_format_course_revenue_response(currentRevenue, pastRevenue) {
    let revenueData       = [];
    let currentCourseIds  = (typeof currentRevenue === 'object' && null!=currentRevenue)?Object.keys(currentRevenue):[];
    let pastCourseIds     = (typeof pastRevenue    === 'object' && null!=pastRevenue)?Object.keys(pastRevenue):[];
    let coursesToShow     = currentCourseIds;

    if (pastCourseIds.length>0) {
      pastCourseIds.forEach(element => {
        if(!coursesToShow.includes(element)) {
          coursesToShow.push(element);  
        } 
      });
    }

    if (coursesToShow.length>0) {
      revenueData['titles']           = [];
      revenueData['past_revenues']    = [];
      revenueData['current_revenues'] = [];
      coursesToShow.forEach(courseId => {
        if (undefined!=currentRevenue[courseId]) {
          revenueData['titles'].push(currentRevenue[courseId].title);
          revenueData['current_revenues'].push(currentRevenue[courseId].total);
          revenueData['past_revenues'].push(undefined!=pastRevenue[courseId]?pastRevenue[courseId].total:0);
        } else if(undefined!=pastRevenue[courseId]) {
          revenueData['titles'].push(pastRevenue[courseId].title);
          revenueData['past_revenues'].push(pastRevenue[courseId].total);
          revenueData['current_revenues'].push(0);
        }
      });
    }

    return revenueData;
  }


  function wisdm_reports_change_block_visibility( elementSelector, visible = true, parent_element = undefined) {
    if (undefined!=parent_element) {
      elementSelector = jQuery( elementSelector ).closest( parent_element );
    }

    if (visible) {
      jQuery( elementSelector ).show();
    } else {
      jQuery( elementSelector ).hide();
    }
  }

  function wisdm_reports_get_ld_custom_lebel_if_avaiable( ld_entity ) {
    let custom_label ='';
    if ( undefined != ld_entity ) {
      ld_entity1 = ld_entity.toLowerCase();
      
      return wisdm_ld_reports_common_script_data.ld_custom_labels[ld_entity1];
    }
    return custom_label;
  }

  function wrld_output_resize() {
      jQuery('.wrld-mw-1400').removeClass('wrld-xl');
      jQuery('.wrld-mw-1400').removeClass('wrld-lg');
      jQuery('.wrld-mw-1400').removeClass('wrld-m');
      jQuery('.wrld-mw-1400').removeClass('wrld-s');
      jQuery('.wrld-mw-1400').removeClass('wrld-xs');
      const width = jQuery('.wrld-mw-1400').width();
      
      if(width > 1199){
        jQuery('.wrld-mw-1400').addClass('wrld-xl');
      }
      else if(width > 992){
        jQuery('.wrld-mw-1400').addClass('wrld-lg');
      }
      else if(width > 768){
        jQuery('.wrld-mw-1400').addClass('wrld-m');
      }
      else if(width > 584){
        jQuery('.wrld-mw-1400').addClass('wrld-s');
      }
      else{
        jQuery('.wrld-mw-1400').addClass('wrld-xs');
      }
  }


document.addEventListener("DOMContentLoaded", function(event) {
    if ( document.querySelectorAll('.wrld-mw-1400').length > 0 ) {
        let wrldResizeObserver = new ResizeObserver(wrld_output_resize).observe(document.querySelectorAll('.wrld-mw-1400')[0]);
        const width = jQuery('.wrld-mw-1400').width();
        jQuery('.wrld-mw-1400').removeClass('wrld-xl');
        jQuery('.wrld-mw-1400').removeClass('wrld-lg');
        jQuery('.wrld-mw-1400').removeClass('wrld-m');
        jQuery('.wrld-mw-1400').removeClass('wrld-s');
        jQuery('.wrld-mw-1400').removeClass('wrld-xs');
        if(width > 1199){
          jQuery('.wrld-mw-1400').addClass('wrld-xl');
        }
        else if(width > 992){
          jQuery('.wrld-mw-1400').addClass('wrld-lg');
        }
        else if(width > 768){
          jQuery('.wrld-mw-1400').addClass('wrld-m');
        }
        else if(width > 584){
          jQuery('.wrld-mw-1400').addClass('wrld-s');
        }
        else{
          jQuery('.wrld-mw-1400').addClass('wrld-xs');
        }
    }

    //Show notic on the edit page
    if (wisdm_ld_reports_common_script_data.page_configuration_status) {
      if (document.getElementsByClassName("wrld-notice").length==0) {
          ( function ( wp ) {
              wp.data.dispatch( 'core/notices' ).createNotice(
                  'success', // Can be one of: success, info, warning, error.
                  '<div class="wrld-notice"><span>' + wisdm_ld_reports_common_script_data.notice_content['header'] + '</span><ul> <li>' + wisdm_ld_reports_common_script_data.notice_content['li_1'] + '</li> <li>' + wisdm_ld_reports_common_script_data.notice_content['li_2'] + '</li><li>' + wisdm_ld_reports_common_script_data.notice_content['li_3'] + '</li><ul></div>', 
                  {
                      __unstableHTML: true,
                      isDismissible: true, // Whether the user can dismiss the notice.
                  }
              );
          } )( window.wp );    
      }
    }
    if (wisdm_ld_reports_common_script_data.page_student_configuration_status) {
      if (document.getElementsByClassName("wrld-notice").length==0) {
          ( function ( wp ) {
              wp.data.dispatch( 'core/notices' ).createNotice(
                  'success', // Can be one of: success, info, warning, error.
                  '<div class="wrld-notice"><span>' + wisdm_ld_reports_common_script_data.notice_student_content['header'] + '</span><ul> <li>' + wisdm_ld_reports_common_script_data.notice_student_content['li_1'] + '</li> <li>' + wisdm_ld_reports_common_script_data.notice_student_content['li_2'] + '</li><ul></div>', 
                  {
                      __unstableHTML: true,
                      isDismissible: true, // Whether the user can dismiss the notice.
                  }
              );
          } )( window.wp );    
      }
    }

    const { isSavingPost } = wp.data.select( 'core/editor' );
    if ( typeof isSavingPost !== 'undefined' ) {
      var checked = true, checked2 = true; // Start in a checked state.
      wp.data.subscribe( () => {
          if ( isSavingPost() ) {
           checked = false;
          } else {
           if ( ! checked ) {
              if ('publish'==wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' ) 
              && wisdm_ld_reports_common_script_data.dashboard_page_id==wp.data.select( 'core/editor' ).getEditedPostAttribute( 'id' )
              && (wisdm_ld_reports_common_script_data.visited_dashboard==false || wisdm_ld_reports_common_script_data.visited_dashboard=='free' ) ) {
                window.location.href = wp.data.select( 'core/editor' ).getEditedPostAttribute('link');
              }
              checked = true;
              }

          }
      } );

      wp.data.subscribe( () => {
          if ( isSavingPost() ) {
           checked2 = false;
          } else {
           if ( ! checked2 ) {
              if ('publish'==wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' ) 
              && wisdm_ld_reports_common_script_data.student_page_id==wp.data.select( 'core/editor' ).getEditedPostAttribute( 'id' )
              && (wisdm_ld_reports_common_script_data.visited_student_dashboard==false ) ) {
                window.location.href = wp.data.select( 'core/editor' ).getEditedPostAttribute('link');
              }
              checked2 = true;
              }
              
          }
      } );
    }


});