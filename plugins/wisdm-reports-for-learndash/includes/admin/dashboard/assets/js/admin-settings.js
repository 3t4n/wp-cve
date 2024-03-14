jQuery(function() {
    jQuery(document).ready(function(){
        const preloadparams = new URLSearchParams(window.location.search);
        // Check if we have the param
        if (preloadparams.has('preload_activity')) {
            wp.data.dispatch( 'core/editor' ).editPost( { meta: { _my_meta_data: 1 } });
            var blocks = wp.data.select('core/editor').getBlocks();
            var target_block = blocks[ blocks.length - 1 ].clientId;
            var source = document.querySelector( '[data-block="' + target_block + '"]' );
            // var container = wp.dom.getScrollContainer( source );
            source.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        if (preloadparams.has('preload_progress')) {
            wp.data.dispatch( 'core/editor' ).editPost( { meta: { _my_meta_data: 1 } });
            var blocks = wp.data.select('core/editor').getBlocks();
            var target_block = blocks[ blocks.length - 1 ].clientId;
            var source = document.querySelector( '[data-block="' + target_block + '"]' );
            // var container = wp.dom.getScrollContainer( source );
            source.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        if ( preloadparams.has('updated_pattern') ) {
            var source = document.querySelector( '.wp-block-wisdm-learndash-reports-course-progress-rate' );
            // var container = wp.dom.getScrollContainer( source );
            jQuery( 'body' ).append( '<div id="wrld-success">' + wrld_admin_settings_data.success_text + '</div>' );
            var x = document.getElementById( "wrld-success" );
            setTimeout( function() {
                source.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 2000 );
            setTimeout( function() {
                x.className = "show";
            }, 2500 );
            setTimeout( function() {
                x.className = x.className.replace("show", "");
            }, 8500 );
        }
    });
    var users, user_roles, courses, load_more = true, userMultiSelect;
    var courseMultiSelect;
    var change_users = [], change_ur = [], change_courses = [];
    var currentXhr, search_string, lag;
    jQuery('.wrld-la-auto-button').on('click', function ( event ) {
        //ajax query to skip licensing;
        //action:
        event.preventDefault();
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_autoupdate_dashboard',
                'nonce':wrld_admin_settings_data.nonce,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                window.location.href=response.data.url;
            },
            error: function(eventData){
            },
        });
    });

    jQuery('.wrld-license-page-skip-now-button').on('click', function ( event ) {
        //ajax query to skip licensing;
        //action:
        event.preventDefault();
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_skip_license_activation',
                'nonce':wrld_admin_settings_data.nonce,
                'skip-licence':true
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                window.location.href=jQuery('.wrld-license-page-skip-now-button').data('link');
            },
            error: function(eventData){
            },
        });
    });

    jQuery('.wrld-license-action.action_activate').on('click', function( event ) {
        let license_key = jQuery('#wrld-pro-license-key').val();
        if (license_key.length<1) {
            alert('Please enter the valid license key');
            return;
        }

        event.preventDefault();
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        let edd_nonce = jQuery(this).closest('.wrld-dashboard-licence-tool-container').data('nonce');
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_license_activate',
                'nonce':wrld_admin_settings_data.nonce,
                'license_key':license_key,
                'edd_learndash-reports-pro_nonce':edd_nonce,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                window.location.href = jQuery('#wrld-license-activation').attr('href');
            },
            error: function(eventData){
                window.location.href = jQuery('#wrld-license-activation').attr('href');
            },
        });
    });

    jQuery('.wrld-license-action.action_deactivate').on('click', function(event) {
        event.preventDefault();
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        let edd_nonce = jQuery(this).closest('.wrld-dashboard-licence-tool-container').data('nonce');
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_license_deactivate',
                'nonce':wrld_admin_settings_data.nonce,
                'edd_learndash-reports-pro_nonce':edd_nonce,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                window.location.href = jQuery('#wrld-license-activation').attr('href');
            },
            error: function(eventData){
                window.location.href = jQuery('#wrld-license-activation').attr('href');
            },
        });
    });

    jQuery('.wrld-dashboard-button-action.set-config').on('click', function(event) {
        event.preventDefault();
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        let data = {
            'action': 'wrld_set_configuration',
            'nonce':wrld_admin_settings_data.nonce,
        };
        if ( jQuery('#wrld-menu-config-setting').length > 0 ) {
            let add_in_menu = jQuery('#wrld-menu-config-setting').is(':checked')?true:false;
            data.add_in_menu = add_in_menu;
        }
        if ( jQuery('#wrld-menu-student-setting').length > 0 ) {
            let add_student_in_menu = jQuery('#wrld-menu-student-setting').is(':checked')?true:false;
            data.add_student_in_menu = add_student_in_menu;
        }
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: data,
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                window.location.href = $this.closest('a').attr('href');
            },
            error: function(eventData){
            },
        });
    });

   
    jQuery('#wrld-action-update-settings').on('click', function( event ){
        event.preventDefault();
        jQuery('.wrld-access-status.wrld-access-status-enabled, .wrld-access-status.wrld-access-status-disabled').css('display', 'none');
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        // let add_in_menu = jQuery('#wrld-menu-config-setting').is(':checked')?true:false;
        // let add_student_in_menu = jQuery('#wrld-menu-student-setting').is(':checked')?true:false;
        let access_to_group_leader= jQuery('#wrld-menu-access-setting-group-leader').is(':checked')?true:false;
        let access_to_wdm_instructor= jQuery('#wrld-menu-access-setting-wdm-instructor').is(':checked')?true:false;
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_update_settings',
                'nonce':wrld_admin_settings_data.nonce,
                // 'add_in_menu':add_in_menu,
                // 'add_student_in_menu':add_student_in_menu,
                'access_to_group_leader':access_to_group_leader,
                'access_to_wdm_instructor':access_to_wdm_instructor
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                if(response.success){
                    jQuery('#wrld-action-update-settings').text('Apply');
                    if ( response.data.settings_data['dashboard-access-roles'].group_leader ){
                        jQuery('.wrld-access-status.wrld-group-leader-enabled').css('display', 'inline-block');
                    } else {
                        jQuery('.wrld-access-status.wrld-group-leader-disabled').css('display', 'inline-block');
                    }
                    let ir_active = ! jQuery('#wrld-menu-access-setting-wdm-instructor').is(':disabled');
                    if (ir_active) {
                        if ( response.data.settings_data['dashboard-access-roles'].wdm_instructor ){
                            jQuery('.wrld-access-status.wrld-wisdm-instructor-enabled').css('display', 'inline-block');
                        } else {
                            jQuery('.wrld-access-status.wrld-wisdm-instructor-disabled').css('display', 'inline-block');
                        }    
                    }
                    jQuery('#wrld-action-update-settings').css('cursor', 'not-allowed');
                    jQuery('#wrld-action-update-settings').attr('disabled', true);   
                    $this.after('<div class="temp-message">Applied Successfully!</div>');
                    setTimeout(function(){
                        jQuery('.temp-message').remove();
                    }, 3000);
                }
            },
            error: function(eventData){

            },
        });
    });
    jQuery('#wrld-action-update-menu-settings').on('click', function( event ){
        event.preventDefault();
        // jQuery('.wrld-access-status.wrld-access-status-enabled, .wrld-access-status.wrld-access-status-disabled').css('display', 'none');
        let $this = jQuery(this);
        $this.text(wrld_admin_settings_data.wait_text);
        $this.css('cursor', 'progress');
        let add_in_menu = jQuery('#wrld-menu-config-setting').is(':checked')?true:false;
        let add_student_in_menu = jQuery('#wrld-menu-student-setting').is(':checked')?true:false;
        // let access_to_group_leader= jQuery('#wrld-menu-access-setting-group-leader').is(':checked')?true:false;
        // let access_to_wdm_instructor= jQuery('#wrld-menu-access-setting-wdm-instructor').is(':checked')?true:false;
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_update_settings',
                'nonce':wrld_admin_settings_data.nonce,
                'add_in_menu':add_in_menu,
                'add_student_in_menu':add_student_in_menu,
                // 'access_to_group_leader':access_to_group_leader,
                // 'access_to_wdm_instructor':access_to_wdm_instructor
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function(response){
                if(response.success){
                    jQuery('#wrld-action-update-menu-settings').text('Apply');
                    // if ( response.data.settings_data['dashboard-access-roles'].group_leader ){
                    //     jQuery('.wrld-access-status.wrld-group-leader-enabled').css('display', 'inline-block');
                    // } else {
                    //     jQuery('.wrld-access-status.wrld-group-leader-disabled').css('display', 'inline-block');
                    // }
                    // let ir_active = ! jQuery('#wrld-menu-access-setting-wdm-instructor').is(':disabled');
                    // if (ir_active) {
                    //     if ( response.data.settings_data['dashboard-access-roles'].wdm_instructor ){
                    //         jQuery('.wrld-access-status.wrld-wisdm-instructor-enabled').css('display', 'inline-block');
                    //     } else {
                    //         jQuery('.wrld-access-status.wrld-wisdm-instructor-disabled').css('display', 'inline-block');
                    //     }    
                    // }
                    jQuery('#wrld-action-update-menu-settings').css('cursor', 'not-allowed');
                    jQuery('#wrld-action-update-menu-settings').attr('disabled', true);
                    $this.after('<div class="temp-message">Applied Successfully!</div>');
                    setTimeout(function(){
                        jQuery('.temp-message').remove();
                    }, 3000);
                }
            },
            error: function(eventData){

            },
        });
    });

    let params = new URLSearchParams(window.location.search);
    let page = params.get('page');
    jQuery.ajax({
        url: wrld_admin_settings_data.wp_ajax_url,
        type: 'post',
        data: {
            'action': 'wrld_license_page_visit',
            'nonce':wrld_admin_settings_data.nonce,
            'page':page
        },
        timeout: 60000, // sets timeout to 1 minute
        success: function(response){
            if(response.success){
                //do nothing
            }
          },
          error: function(eventData){
        },
    });


    jQuery('#wrld-menu-access-setting-group-leader, #wrld-menu-access-setting-wdm-instructor').on('click', function (event) {
        jQuery('#wrld-action-update-settings').removeAttr('disabled');
        jQuery('#wrld-action-update-settings').css('cursor', 'pointer');
        if (this.checked) {
            jQuery(this).closest('label.checkbox-label').removeClass('no-access');
        } else {
            jQuery(this).closest('label.checkbox-label').addClass('no-access');
        }
    });
    jQuery('#wrld-menu-config-setting, #wrld-menu-student-setting').on('click', function (event) {
        jQuery('#wrld-action-update-menu-settings').removeAttr('disabled');
        jQuery('#wrld-action-update-menu-settings').css('cursor', 'pointer');
        if (this.checked) {
            jQuery(this).closest('label.checkbox-label').removeClass('no-access');
        } else {
            jQuery(this).closest('label.checkbox-label').addClass('no-access');
        }
    });

    jQuery('.wrld-license-page-config-button').on('click', function(){
        window.location.href=jQuery(this).data('link');
    });

    var showBorderIfNeeded = function() {
        if ( jQuery( 'select.exclude_users + .multiselect__container .multiselect__selected' ).length > 0 ) {
            jQuery( 'select.exclude_users + .multiselect__container' ).addClass( 'wrld-no-shadow' );
        } else {
            jQuery( 'select.exclude_users + .multiselect__container' ).removeClass( 'wrld-no-shadow' );
        }
        if ( jQuery( 'select.exclude_ur + .multiselect__container .multiselect__selected' ).length > 0 ) {
            jQuery( 'select.exclude_ur + .multiselect__container' ).addClass( 'wrld-no-shadow' );
        } else {
            jQuery( 'select.exclude_ur + .multiselect__container' ).removeClass( 'wrld-no-shadow' );
        }
        if ( jQuery( 'select.exclude_courses + .multiselect__container .multiselect__selected' ).length > 0 ) {
            jQuery( 'select.exclude_courses + .multiselect__container' ).addClass( 'wrld-no-shadow' );
        } else {
            jQuery( 'select.exclude_courses + .multiselect__container' ).removeClass( 'wrld-no-shadow' );
        }
    }

    /* Function used to save the exclusion setting in the database. */
    var save_exclude_values = function( type, value, button_obj ) {
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_exclude_settings_save',
                'nonce':wrld_admin_settings_data.nonce,
                'type':type,
                'value':value,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function( response ){
                if( response.success ){
                    button_obj.hide();
                    if ( button_obj.parents('.users-field') ) {
                        jQuery('.users-field select.exclude_users').html( window.users_dropdown_bu );
                    }

                    var parent = button_obj.parents('.right-fields');
                    var dropdown = parent.find('select');
                    var custom_dropdown = parent.find('.multiselect__container .multiselect__options li');
                    custom_dropdown.each(function(ind, el) {
                        if ( jQuery(el).hasClass('multiselect__options--selected') ) {
                            if ( dropdown.find('option[value="' + jQuery(el).attr('data-value') + '"]').length > 0 ) {
                                dropdown.find('option[value="' + jQuery(el).attr('data-value') + '"]').attr('selected', 'selected');
                            } else {
                                dropdown.append('<option value="' + jQuery(el).attr('data-value') + '" selected="selected">' + jQuery(el).text() + '</option>');
                            }
                        } else {
                            dropdown.find('option[value="' + jQuery(el).attr('data-value') + '"]').removeAttr('selected');
                        }
                    });
                    // jQuery( '.exclude_' + type ).attr( 'disabled', 'disabled' );
                    // jQuery( '.exclude_' + type ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
                    // jQuery( '.exclude_' + type ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
                    // jQuery( '.exclude_' + type ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
                    // showBorderIfNeeded();
                }
            },
            error: function( eventData ){
            },
        });
    } 

    var reInitializeAfterSelect = function() {
        jQuery( '.users-field .multiselect__container' ).remove();
        // Initialize User Multiselect.
        userMultiSelect = new IconicMultiSelect({
            select: ".exclude_users",
            disabled: false,
            placeholder: wrld_admin_settings_data.user_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        userMultiSelect.init();

        // Patch code for disabling the multiselect when original select is disabled.
        // if ( jQuery( '.exclude_users' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }

        // Add subscription event for dropdown value changes.
        userMultiSelect.subscribe( function( event ) {
            jQuery( '.users-field .apply_users_exclude' ).show();
            jQuery( '.users-field .discard_users_exclude' ).show();
            // change_users.push({})
            users = event.selection.map( ( { value } ) => value );
        } );
    };

    /**
     * This method is used to initialize the multiselects and add subscription events.
     */
    jQuery( document ).ready( function() {
        if ( jQuery( '.exclude_users' ).length < 1 ) {
            return;
        }
        var urlHash = window.location.href.split("#");
        if ( urlHash.length > 1 ) {
            urlHash = urlHash[1];
            jQuery( 'html, body' ).animate({
                scrollTop: jQuery('#' + urlHash).offset().top
            }, 1500);
        }
        var disabled = false;
        if ( jQuery('.wrld-upgrade-button').length > 0 ) {
            disabled = true;
        }
        // Initialize User Multiselect.
        userMultiSelect = new IconicMultiSelect({
            select: ".exclude_users",
            disabled: disabled,
            placeholder: wrld_admin_settings_data.user_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        userMultiSelect.init();

        // Patch code for disabling the multiselect when original select is disabled.
        if ( jQuery( '.exclude_users' ).attr( 'disabled' ) ) {
            jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
            jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_users' ).next().find( '.multiselect__selected' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_users' ).next().find( '.multiselect__wrapper' ).css( 'pointer-events', 'not-allowed' );
        }

        var user_wrapper = jQuery('.users-field .multiselect__selected').parent();
        var user_length = user_wrapper.find('.multiselect__selected').length;
        if ( user_length < 2 ) {
            user_wrapper.next().addClass('dont-show');
        } else {
            user_wrapper.next().removeClass('dont-show');
        }

        // Add subscription event for dropdown value changes.
        userMultiSelect.subscribe( function( event ) {
            jQuery( '.users-field .apply_users_exclude' ).show();
            jQuery( '.users-field .discard_users_exclude' ).show();
            var selected = jQuery('.users-field .multiselect__selected').parent();
            var length = selected.find('.multiselect__selected').length
            if ( length < 2 ) {
                selected.next().addClass('dont-show');
            } else {
                selected.next().removeClass('dont-show');
            }
            // change_users.push({})
            users = event.selection.map( ( { value } ) => value );
            // reInitializeAfterSelect();
        } );



        // Initialize Userrole Multiselect.
        var urMultiSelect = new IconicMultiSelect({
            select: ".exclude_ur",
            disabled: disabled,
            placeholder: wrld_admin_settings_data.ur_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        urMultiSelect.init();
        // Patch code for disabling the multiselect when original select is disabled.
        if ( jQuery( '.exclude_ur' ).attr( 'disabled' ) ) {
            jQuery( '.exclude_ur' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
            jQuery( '.exclude_ur' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_ur' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_ur' ).next().find( '.multiselect__selected' ).css( 'pointer-events', 'none' );
            jQuery( '.exclude_ur' ).next().find( '.multiselect__wrapper' ).css( 'pointer-events', 'not-allowed' );
        }

        var ur_wrapper = jQuery('.user-roles-field .multiselect__selected').parent();
        var ur_length = ur_wrapper.find('.multiselect__selected').length;
        if ( ur_length < 2 ) {
            ur_wrapper.next().addClass('dont-show');
        } else {
            ur_wrapper.next().removeClass('dont-show');
        }

        // Add subscription event for dropdown value changes.
        urMultiSelect.subscribe( function( event ) {
            jQuery( '.user-roles-field .apply_ur_exclude' ).show();
            jQuery( '.user-roles-field .discard_ur_exclude' ).show();
            var selected = jQuery('.user-roles-field .multiselect__selected').parent();
            var length = selected.find('.multiselect__selected').length
            if ( length < 2 ) {
                selected.next().addClass('dont-show');
            } else {
                selected.next().removeClass('dont-show');
            }
            user_roles = event.selection.map( ( { value } ) => value );
        } );

        // Initialize Course Multiselect.
        courseMultiSelect = new IconicMultiSelect({
            select: ".exclude_courses",
            disabled: false,
            placeholder: wrld_admin_settings_data.course_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        courseMultiSelect.init();

        var course_wrapper = jQuery('.courses-field .multiselect__selected').parent();
        var course_length = course_wrapper.find('.multiselect__selected').length;
        if ( course_length < 2 ) {
            course_wrapper.next().addClass('dont-show');
        } else {
            course_wrapper.next().removeClass('dont-show');
        }

        // Patch code for disabling the multiselect when original select is disabled.
        // if ( jQuery( '.exclude_courses' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_courses' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }

        // Add subscription event for dropdown value changes.
        courseMultiSelect.subscribe( function( event ) {
            jQuery( '.courses-field .apply_courses_exclude' ).show();
            jQuery( '.courses-field .discard_courses_exclude' ).show();
            var selected = jQuery('.courses-field .multiselect__selected').parent();
            var length = selected.find('.multiselect__selected').length
            if ( length < 2 ) {
                selected.next().addClass('dont-show');
            } else {
                selected.next().removeClass('dont-show');
            }
            courses = event.selection.map( ( { value } ) => value );
            // var changes  = change_courses[event.action] != undefined ? change_courses[event.action] : [];
            // changes.push(event.value);
            // change_courses[event.action] = changes;
            // change_courses.push({event.action: })
            // save_exclude_values( 'courses', courses );
        } );
        // showBorderIfNeeded();
    } );

    /* Toggle the multiselect on edit icon click */
    // jQuery( '.users-field .dashicons-edit' ).on( 'click', function() {
    //     jQuery( this ).attr( 'disabled', 'disabled' );
    //     if ( jQuery( '.exclude_users' ).attr( 'disabled' ) ) {
    //         jQuery( '.exclude_users' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_users + .multiselect__container' ).removeClass('wrld-no-shadow');
    //         jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'auto' );
    //         jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'auto' );
    //     }/* else {
    //         jQuery( '.exclude_users' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
    //         jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
    //     }*/
    // } );

    /* Save button click for users setting */
    jQuery( '.users-field .apply_users_exclude' ).on( 'click', function() {
        jQuery( '.discard_users_exclude' ).hide();
        save_exclude_values( 'users', users, jQuery( this ) );
        // jQuery( '.users-field .dashicons-edit' ).removeAttr( 'disabled' );
        // reInitializeAfterSelect();
    });

    /* Save button click for user roles setting */
    jQuery( '.user-roles-field .apply_ur_exclude' ).on( 'click', function() {
        jQuery( '.discard_ur_exclude' ).hide();
        save_exclude_values( 'ur', user_roles, jQuery( this ) );
        // jQuery( '.user-roles-field .dashicons-edit' ).removeAttr( 'disabled' );
    });

    /* Save button click for courses setting */
    jQuery( '.courses-field .apply_courses_exclude' ).on( 'click', function() {
        jQuery( '.discard_courses_exclude' ).hide();
        save_exclude_values( 'courses', courses, jQuery( this ) );
        // jQuery( '.courses-field .dashicons-edit' ).removeAttr( 'disabled' );
        change_courses = [];
    });

    jQuery( document ).on( 'change, keyup', '[name=wrld_time_tracking_status], .toggle-settings input', function() {
        jQuery('.apply_time_tracking_settings').removeAttr('disabled');
    } );

    let wrldModalCancelPressed = false;

    jQuery( '.wrld-warning-popup-modal .modal-button-cancel' ).on( 'click', function() {
        jQuery('.wrld-warning-popup-modal').hide();
        wrldModalCancelPressed = true;
        jQuery('[name=wrld_time_tracking_status]').trigger('click');
    } );

    jQuery( '.wrld-warning-popup-modal .modal-button-proceed' ).on( 'click', function() {
        jQuery('.wrld-warning-popup-modal').hide();
        wrldModalCancelPressed=false;
        var status = 'off';
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'apply_time_tracking_settings',
                'nonce':wrld_admin_settings_data.nonce,
                'status':status,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function( response ){
                jQuery('.apply_time_tracking_settings').attr('disabled', 'disabled');
                jQuery('.wrld-dashboard-text.note-text .latest').html(response.data.time);
                jQuery('.idle-configuration-log tr:nth-child(2)').before('<tr><td>' + wrld_admin_settings_data.deactivated_18n + '</td><td>' + response.data.time + '</td></tr>');
                if ( jQuery('.idle-configuration-log tr').length > 10 ) {    
                    jQuery('.idle-configuration-log tr:last-child').remove();
                }    
            },
            error: function( eventData ){
            },
        });
    } );

    jQuery('[name=wrld_time_tracking_status]').on('change', function() {
        if ( (! jQuery( this ).is(':checked') ) || wrldModalCancelPressed ) {
           if(! wrldModalCancelPressed){
            jQuery('.wrld-warning-popup-modal').fadeIn(500);
            //jQuery('.apply_time_tracking_settings').hide();
            jQuery('.wrld-dashboard-text.note-text').hide();
           }
            wrldModalCancelPressed =  false;
        } else {
            var status = 'on';
            jQuery.ajax({
                url: wrld_admin_settings_data.wp_ajax_url,
                type: 'post',
                data: {
                    'action': 'apply_time_tracking_settings',
                    'nonce':wrld_admin_settings_data.nonce,
                    'status':status,
                },
                timeout: 60000, // sets timeout to 1 minute
                success: function( response ){
                    jQuery('.apply_time_tracking_settings').show();
                    jQuery('.wrld-dashboard-text.note-text').show();
                    jQuery('.apply_time_tracking_settings').attr('disabled', 'disabled');
                    jQuery('.wrld-dashboard-text.note-text .latest').html(response.data.time);
                    jQuery('.idle-configuration-log tr:nth-child(2)').before('<tr><td>' + wrld_admin_settings_data.activated_18n + '</td><td>' + response.data.time + '</td></tr>');
                    if ( jQuery('.idle-configuration-log tr').length > 10 ) {
                        jQuery('.idle-configuration-log tr:last-child').remove();
                    }
                },
                error: function( eventData ){
                },
            });
        }
    });

    jQuery( '.apply_time_tracking_settings' ).on( 'click', function() { 
        let $this = jQuery( this );
        $this.text(wrld_admin_settings_data.wait_text);
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'apply_time_tracking_settings',
                'nonce':wrld_admin_settings_data.nonce,
                'timeout':jQuery('input[name=wrld_idle_time]').val(),
                'message':jQuery('input[name=wrld_idle_msg]').val(),
                'btnlabel':jQuery('input[name=wrld_idle_btn_label]').val(),
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function( response ){
                jQuery('.apply_time_tracking_settings').text('Save Changes');
                jQuery('.apply_time_tracking_settings').attr('disabled', 'disabled');
                $this.after('<div class="temp-message">Saved Successfully!</div>');
                setTimeout(function(){
                    jQuery('.temp-message').remove();
                }, 3000);
            },
            error: function( eventData ){
            },
        });
    } );

    jQuery( '.users-field .discard_users_exclude' ).on( 'click', function() {
        // save_exclude_values( 'users', users, jQuery( this ) );
        // jQuery( '.users-field .dashicons-edit' ).removeAttr( 'disabled' );
        jQuery( this ).hide();  
        // jQuery( '.exclude_users' ).attr( 'disabled', 'disabled' );
        jQuery( '.apply_users_exclude' ).hide();
        // if ( jQuery( '.exclude_users' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }
        jQuery('.users-field select.exclude_users').html(window.users_dropdown_bu);
        jQuery('.users-field .multiselect__container').remove();
        // Initialize User Multiselect.
        userMultiSelect = new IconicMultiSelect({
            select: ".exclude_users",
            disabled: false,
            placeholder: wrld_admin_settings_data.user_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        userMultiSelect.init();

        // Patch code for disabling the multiselect when original select is disabled.
        // if ( jQuery( '.exclude_users' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_users' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_users' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }

        // Add subscription event for dropdown value changes.
        userMultiSelect.subscribe( function( event ) {
            jQuery( '.users-field .apply_users_exclude' ).show();
            jQuery( '.users-field .discard_users_exclude' ).show();
            // change_users.push({})
            users = event.selection.map( ( { value } ) => value );
        } );
        // showBorderIfNeeded();
    });

    /* Save button click for user roles setting */
    jQuery( '.user-roles-field .discard_ur_exclude' ).on( 'click', function() {
        // save_exclude_values( 'ur', user_roles, jQuery( this ) );
        jQuery( this ).hide();
        // jQuery( '.user-roles-field .dashicons-edit' ).removeAttr( 'disabled' );
        // jQuery( '.exclude_ur' ).attr( 'disabled', 'disabled' );
        jQuery( '.apply_ur_exclude' ).hide();
        // if ( jQuery( '.exclude_ur' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_ur' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_ur' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_ur' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }
        jQuery('.user-roles-field .multiselect__container').remove();
        // Initialize Userrole Multiselect.
        var urMultiSelect = new IconicMultiSelect({
            select: ".exclude_ur",
            disabled: false,
            placeholder: wrld_admin_settings_data.ur_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        urMultiSelect.init();
        // Patch code for disabling the multiselect when original select is disabled.
        // if ( jQuery( '.exclude_ur' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_ur' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_ur' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_ur' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }

        // Add subscription event for dropdown value changes.
        urMultiSelect.subscribe( function( event ) {
            jQuery( '.user-roles-field .apply_ur_exclude' ).show();
            jQuery( '.user-roles-field .discard_ur_exclude' ).show();
            user_roles = event.selection.map( ( { value } ) => value );
        } );
        //showBorderIfNeeded();
    });

    /* Save button click for courses setting */
    jQuery( '.courses-field .discard_courses_exclude' ).on( 'click', function() {
        jQuery( this ).hide();
        // jQuery( '.courses-field .dashicons-edit' ).removeAttr( 'disabled' );
        // jQuery( '.exclude_courses' ).attr( 'disabled', 'disabled' );
        jQuery( '.apply_courses_exclude' ).hide();
        // if ( jQuery( '.exclude_courses' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_courses' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }
        jQuery('.courses-field .multiselect__container').remove();
        // Initialize Course Multiselect.
        courseMultiSelect = new IconicMultiSelect({
            select: ".exclude_courses",
            disabled: false,
            placeholder: wrld_admin_settings_data.course_placeholder,
            noResults: wrld_admin_settings_data.loading_text
        });
        courseMultiSelect.init();

        // Patch code for disabling the multiselect when original select is disabled.
        // if ( jQuery( '.exclude_courses' ).attr( 'disabled' ) ) {
        //     jQuery( '.exclude_courses' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'none' );
        //     jQuery( '.exclude_courses' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'none' );
        // }

        // Add subscription event for dropdown value changes.
        courseMultiSelect.subscribe( function( event ) {
            jQuery( '.courses-field .apply_courses_exclude' ).show();
            jQuery( '.courses-field .discard_courses_exclude' ).show();
            courses = event.selection.map( ( { value } ) => value );
            // var changes  = change_courses[event.action] != undefined ? change_courses[event.action] : [];
            // changes.push(event.value);
            // change_courses[event.action] = changes;
            // change_courses.push({event.action: })
            // save_exclude_values( 'courses', courses );
        } );
        /*if ( change_courses['ADD_OPTION'] != undefined ) {
            for (var i = 0; i < change_courses['ADD_OPTION'].length; i++) {
                // target = jQuery( '.exclude_courses option[value=' + change_courses['ADD_OPTION'][i] + ']' );
                target = jQuery( '.courses-field li[data-value=' + change_courses['ADD_OPTION'][i] + ']' );
                target.removeClass(`multiselect__options--selected`);
                courseMultiSelect._removeOptionFromList(change_courses['ADD_OPTION'][i]);
                courseMultiSelect._handleClearSelectionBtn();
                courseMultiSelect._handlePlaceholder();
                jQuery( '.exclude_courses .multiselect__selected[data-value=' + change_courses['ADD_OPTION'][i] + ']' ).remove();
            }
            change_courses['ADD_OPTION'] = [];
        }
        if ( change_courses['REMOVE_OPTION'] != undefined ) {
            for (var i = 0; i < change_courses['REMOVE_OPTION'].length; i++) {
                text = jQuery( '.courses-field .exclude_courses option[value=' + change_courses['REMOVE_OPTION'][i] + ']' ).text();
                courseMultiSelect._addOptionToList({text: text, value: change_courses['REMOVE_OPTION'][i]}, jQuery( '.courses-field li[data-value=' + change_courses['REMOVE_OPTION'][i] + ']' ).index());
                target = jQuery( '.courses-field li[data-value=' + change_courses['REMOVE_OPTION'][i] + ']' );
                target.addClass(`multiselect__options--selected`);
                courseMultiSelect._handleClearSelectionBtn();
                courseMultiSelect._handlePlaceholder();
            }
            change_courses['REMOVE_OPTION'] = [];
        }
        change_courses = [];*/
        // showBorderIfNeeded();
    });

    /* Toggle the multiselect on edit icon click */
    // jQuery( '.user-roles-field .dashicons-edit' ).on( 'click', function() {
    //     jQuery( this ).attr( 'disabled', 'disabled' );
    //     if ( jQuery( '.exclude_ur' ).attr( 'disabled' ) ) {
    //         jQuery( '.exclude_ur' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_ur + .multiselect__container' ).removeClass('wrld-no-shadow');
    //         jQuery( '.exclude_ur' ).next().find( 'input.multiselect__input' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_ur' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'auto' );
    //         jQuery( '.exclude_ur' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'auto' );
    //     }/* else {
    //         jQuery( '.exclude_ur' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_ur' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_ur' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'auto' );
    //         jQuery( '.exclude_ur' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'auto' );
    //     }*/
    // } );

    /* Toggle the multiselect on edit icon click */
    // jQuery( '.courses-field .dashicons-edit' ).on( 'click', function() {
    //     jQuery( this ).attr( 'disabled', 'disabled' );
    //     if ( jQuery( '.exclude_courses' ).attr( 'disabled' ) ) {
    //         jQuery( '.exclude_courses' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_courses + .multiselect__container' ).removeClass('wrld-no-shadow');
    //         jQuery( '.exclude_courses' ).next().find( 'input.multiselect__input' ).removeAttr( 'disabled' );
    //         jQuery( '.exclude_courses' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'auto' );
    //         jQuery( '.exclude_courses' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'auto' );
    //     }/* else {
    //         jQuery( '.exclude_courses' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_courses' ).next().find( 'input.multiselect__input' ).attr( 'disabled', 'disabled' );
    //         jQuery( '.exclude_courses' ).next().find( '.multiselect__clear-btn' ).css( 'pointer-events', 'auto' );
    //         jQuery( '.exclude_courses' ).next().find( '.multiselect__remove-btn' ).css( 'pointer-events', 'auto' );
    //     }*/
    // } );

    jQuery( 'body' ).on( 'focusout', '.users-field input.multiselect__input', function() {
        jQuery('.users-field select.exclude_users').html(window.users_dropdown_bu);
    } );

    jQuery( 'body' ).on( 'focus', '.users-field input.multiselect__input', function() {
        if ( ! load_more ) {
            return;
        }
        if ( jQuery( this ).parents('.multiselect__container').find('.multiselect__options ul li').length >= 10 && jQuery( this ).parents('.multiselect__container').find( '.multiselect__options .load-more' ).length <= 0 ) {
            jQuery( this ).parents('.multiselect__container').find('.multiselect__options ul').append('<span class="load-more">Load More</span>');
        } else {
            jQuery( this ).parents('.multiselect__container').find( '.multiselect__options ul .load-more' ).remove();
            jQuery( this ).parents('.multiselect__container').find('.multiselect__options ul').append('<span class="load-more">Load More</span>');
        }
    } );

    jQuery( 'body' ).on( 'input focus', '.users-field input.multiselect__input', function( event ){
        clearTimeout(lag);
        jQuery( '.users-field .multiselect__container .multiselect__options ul .load-more' ).remove();
        lag = setTimeout( function() {
            currentXhr && currentXhr.readyState != 4 && currentXhr.abort(); // clear previous request
            jQuery( '.users-field .multiselect__container .multiselect__options ul' ).html('<span class="loading">Loading</span>');
            currentXhr = jQuery.ajax({
                url: wrld_admin_settings_data.wp_ajax_url,
                type: 'post',
                async: false,
                data: {
                    'action': 'wrld_exclude_load_users',
                    'nonce': wrld_admin_settings_data.nonce,
                    'search': event.target.value,
                },
                timeout: 60000, // sets timeout to 1 minute
                success: function( response ){
                    if( response.success ){
                        jQuery( '.users-field .multiselect__container .multiselect__options ul .loading' ).remove();
                        window.users_dropdown_bu = jQuery( '.users-field select option' ).detach();
                        jQuery( '.users-field select' ).empty();
                        jQuery( '.users-field select' ).append( response.data.data.map((option) => {
                            if ( jQuery( '.users-field select option[value="' + option.ID + '"]' ).length == 0 ) {
                                return `
                                <option class="search-results" value="${option.ID}">${option.display_name}</option>
                                `;
                            }
                            return "";
                        }).join("") );
                        jQuery('.users-field .multiselect__selected').each(function(ind, el){
                            if ( jQuery( '.users-field .multiselect__selected[data-value="' + jQuery(el).attr('data-value') + '"]' ).length == 0 ) {
                                jQuery( '.users-field select' ).append('<option class="search-results" value="'+ jQuery(el).attr('data-value') + '">' + jQuery(el).text() + '</option>');
                            }
                        });
                        window.users_dropdown_bu = [window.users_dropdown_bu, jQuery( '.users-field select option' )].reduce(function(total, current){
                            return jQuery(total).add(current)
                        });
                        userMultiSelect.edit();
                        jQuery('.users-field .multiselect__selected').each(function(ind, el){
                            if ( jQuery( '.users-field .multiselect__options li[data-value="' + jQuery(el).attr('data-value') + '"]' ).length == 0 ) {
                                jQuery( '.users-field .multiselect__options ul' ).append('<li class="multiselect__options--selected" data-value="'+ jQuery(el).attr('data-value') + '">' + jQuery(el).text() + '</li>');
                            } else {
                                // jQuery(el).addClass('multiselect__options--selected');
                                jQuery('.users-field .multiselect__options li[data-value="' + jQuery(el).attr('data-value') + '"]' ).addClass('multiselect__options--selected');
                            }
                        });
                        if ( jQuery( '.users-field .multiselect__container .multiselect__options ul .load-more' ).length <= 0 && response.data.data.length >= 10 ) {
                            load_more = true;
                            jQuery( '.users-field .multiselect__container .multiselect__options ul').append('<span class="load-more">Load More</span>');
                        }
                        search_string = event.target.value;
                    } else {
                        jQuery( '.users-field .multiselect__container .multiselect__options ul .loading' ).text('No Results Found');
                        load_more = false;
                        jQuery( '.users-field .multiselect__container .multiselect__options ul .load-more' ).remove();
                    }
                },
                error: function( eventData ){
                },
            });
        }, 2000 );
    });

    jQuery( 'body' ).on( 'click', '.multiselect__container .load-more', function() {
        jQuery( this ).remove();
        var entries = jQuery( '.users-field .multiselect__container .multiselect__options ul li' ).length;
        var page    = parseInt( Math.ceil( entries / 10 ) ) + 1;
        jQuery.ajax({
            url: wrld_admin_settings_data.wp_ajax_url,
            type: 'post',
            data: {
                'action': 'wrld_exclude_load_users',
                'nonce': wrld_admin_settings_data.nonce,
                'page': page,
                'search': search_string,
            },
            timeout: 60000, // sets timeout to 1 minute
            success: function( response ){
                if( response.success ){
                    jQuery( '.users-field select' ).append( response.data.data.map((option) => {
                        if ( jQuery( '.users-field select option[value="' + option.ID + '"]' ).length == 0 ) {
                            return `
                            <option value="${option.ID}">${option.display_name}</option>
                            `;
                        }
                        return "";
                    }).join("") );
                    // jQuery( '.users-field select' ).append( jQuery('.users-field .multiselect__selected').map((option) => {
                    //     return `
                    //     <option class="search-results" value="${option.attr('data-value')}">${option.text()}</option>
                    //     `;
                    // }).join("") );
                    jQuery('.users-field .multiselect__selected').each(function(ind, el){
                        if ( jQuery( '.users-field .multiselect__selected[data-value="' + jQuery(el).attr('data-value') + '"]' ).length == 0 ) {
                            jQuery( '.users-field select' ).append('<option value="'+ jQuery(el).attr('data-value') + '">' + jQuery(el).text() + '</option>');
                        }
                    });
                    userMultiSelect.edit();
                    jQuery('.users-field .multiselect__selected').each(function(ind, el){
                        if ( jQuery( '.users-field .multiselect__options li[data-value="' + jQuery(el).attr('data-value') + '"]' ).length == 0 ) {
                            jQuery( '.users-field .multiselect__options ul' ).append('<li class="multiselect__options--selected" data-value="'+ jQuery(el).attr('data-value') + '">' + jQuery(el).text() + '</li>');
                        } else {
                            jQuery('.users-field .multiselect__options li[data-value="' + jQuery(el).attr('data-value') + '"]' ).addClass('multiselect__options--selected');
                        }
                    });
                    load_more = true;
                    if ( jQuery( '.users-field .multiselect__container .multiselect__options ul .load-more' ).length <= 0 ) {
                        jQuery( '.users-field .multiselect__container .multiselect__options ul').append('<span class="load-more">Load More</span>');
                    }
                } else {
                    load_more = false;
                    jQuery( '.users-field .multiselect__container .multiselect__options ul .load-more' ).remove();
                }
            },
            error: function( eventData ){
            },
        });
    } );

    jQuery("#wlrp_more span").on('click',function(){
        jQuery("#wlrp_more li").toggle(600);
        jQuery("#wlrp_more span").hide(0);
      });

      jQuery(".wlrp_less").on('click',function(){
        jQuery("#wlrp_more li").toggle(600);
        jQuery("#wlrp_more span").show(0);
      });

      //time setting

      jQuery('#wrld_idle_hh').on('input', function(){
        this.value = this.value.replace(/[^0-9]/g, '').slice(0,2);
        saveIdleTimeValue();
      });
      jQuery('#wrld_idle_mm').on('input', function(){
        this.value = this.value.replace(/[^0-9]/g, '').slice(0,2);
        saveIdleTimeValue();
      });
      jQuery('#wrld_idle_ss').on('input', function(){
        this.value = this.value.replace(/[^0-9]/g, '').slice(0,2);
        saveIdleTimeValue();
      });

    function saveIdleTimeValue() {
        jQuery('.apply_time_tracking_settings').removeAttr('disabled');
        let hours = jQuery('#wrld_idle_hh').val();
        let minutes = jQuery('#wrld_idle_mm').val();
        let remainingSeconds = jQuery('#wrld_idle_ss').val();
        let totalSeconds = (hours * 3600) + (minutes * 60) + remainingSeconds * 1;

        jQuery('#current_idle_time').val(totalSeconds);
    }
    function setIdleTimeValue() {
       var tvalue = jQuery('#current_idle_time').val();
       let hours = Math.floor(tvalue / 3600);
       let minutes = Math.floor((tvalue - (hours * 3600)) / 60);
       let remainingSeconds = tvalue - (hours * 3600) - (minutes * 60);
       jQuery('#wrld_idle_ss').val(remainingSeconds);
       jQuery('#wrld_idle_mm').val(minutes);
       jQuery('#wrld_idle_hh').val(hours);
    }
    setIdleTimeValue();


    jQuery( document ).ready(function(){
        var active_tab = jQuery('.current-sub-setting-tab').attr('data-tab');
        jQuery(".setting-tabs").hide();
        jQuery('#'+active_tab).show(0);
    });
    // admin setting subtabs logic
    jQuery('#wrld-settings-sub').on('click','li' , function(e){
        jQuery(".setting-tabs-links").each(function(element){
              jQuery(this).removeClass('current-sub-setting-tab');                
        });
        jQuery(this).addClass('current-sub-setting-tab');
        const pannelId = jQuery(this).attr("data-tab");
        jQuery(".setting-tabs").hide();
        jQuery('#'+pannelId).show(0);
    });

    var hashFragment = window.location.hash;
    if(hashFragment === "#wlrd-dashboard-time-settings"){
      jQuery(".setting-tabs-links").each(function(element){
        jQuery(this).removeClass('current-sub-setting-tab');                
    });
    
       setTimeout(() => {
        jQuery('#wrld-time-tracking-menu').show(0);
        jQuery('#wrld-time-tracking-menu').addClass('current-sub-setting-tab');
       }, 1000);

       jQuery('.wrld-ts-main-container').hide(0);
    }
    console.log(hashFragment);

});
