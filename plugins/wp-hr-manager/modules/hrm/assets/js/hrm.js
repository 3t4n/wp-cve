/* jshint devel:true */
/* global wpHr */
/* global wp */

;(function($) {
    'use strict';

    var clsWP_HR_HR = {

        /**
         * Initialize the events
         *
         * @return {void}
         */
        initialize: function() {
            // Dasboard Overview
            $( 'ul.wphr-dashboard-announcement' ).on( 'click', 'a.mark-read', this.dashboard.markAnnouncementRead );
            $( 'ul.wphr-dashboard-announcement' ).on( 'click', 'a.view-full', this.dashboard.viewAnnouncement );
            $( 'ul.wphr-dashboard-announcement' ).on( 'click', '.announcement-title a', this.dashboard.viewAnnouncementTitle );

            // Department
            $( 'body' ).on( 'click', 'a#wphr-new-dept', this.department.create );
            $( '.wphr-hr-depts' ).on( 'click', 'a.submitdelete', this.department.remove );
            $( '.wphr-hr-depts' ).on( 'click', 'span.edit a', this.department.edit );

            // Role
            $( 'body' ).on( 'click', 'a#wphr-new-designation', this.designation.create );
            $( '.wphr-hr-designation' ).on( 'click', 'a.submitdelete', this.designation.remove );
            $( '.wphr-hr-designation' ).on( 'click', 'span.edit a', this.designation.edit );

            // employee
            $( '.wphr-hr-employees' ).on( 'click', 'a.ressend_welcome_email', this.employee.ressend_welcome_email );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-employee-new', this.employee.create );
            $( '.wphr-hr-employees' ).on( 'click', 'span.edit a', this.employee.edit );
            $( '.wphr-hr-employees' ).on( 'click', 'a.submitdelete', this.employee.remove );
            $( '.wphr-hr-employees' ).on( 'click', 'a.submitrestore', this.employee.restore );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-status', this.employee.updateJobStatus );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-compensation', this.employee.updateJobStatus );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-jobinfo', this.employee.updateJobStatus );
            $( '.wphr-hr-employees' ).on( 'click', 'td.action a.remove', this.employee.removeHistory );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-employee-print', this.employee.printData );
            $( 'body' ).on( 'focusout', 'input#wphr-hr-user-email', this.employee.checkUserEmail );
            $( 'body' ).on( 'click', 'a#wphr-hr-create-wp-user-to-employee', this.employee.makeUserEmployee );

            // Single Employee
            $( '.wphr-employee-single' ).on( 'click', 'a#wphr-employee-terminate', this.employee.terminateEmployee );
            // $( '.wphr-employee-single' ).on( 'click', 'a#wphr-employee-activate', this.employee.activateEmployee ); // @TODO: Needs to modify it later. :p
            $( '.wphr-employee-single' ).on( 'click', 'input#wphr-hr-employee-status-update', this.employee.changeEmployeeStatus );

            // Performance
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-performance-reviews', this.employee.updatePerformance );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-performance-comments', this.employee.updatePerformance );
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-performance-goals', this.employee.updatePerformance );
            $( '.wphr-hr-employees' ).on( 'click', '.performance-tab-wrap td.action a.performance-remove', this.employee.removePerformance );
            // work experience
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-add-exp', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.work-experience-edit', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.work-experience-delete', this.employee.general.remove );

            // education
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-add-education', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.education-edit', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.education-delete', this.employee.general.remove );

            // dependent
            $( '.wphr-hr-employees' ).on( 'click', 'a#wphr-empl-add-dependent', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.dependent-edit', this.employee.general.create );
            $( '.wphr-hr-employees' ).on( 'click', 'a.dependent-delete', this.employee.general.remove );

            // notes
            $( '.wphr-hr-employees' ).on( 'submit', '.note-tab-wrap form', this.employee.addNote );
            $( '.wphr-hr-employees' ).on( 'click', '.note-tab-wrap input#wphr-load-notes', this.employee.loadNotes );
            $( '.wphr-hr-employees' ).on( 'click', '.note-tab-wrap a.delete_note', this.employee.deleteNote );
			
			// Permission
			$('.wphr-hr-employees').on( 'click', '#enable_manager', this.employee.extendUserCapabities );
			
            // photos
            $( 'body' ).on( 'click', 'a#wphr-set-emp-photo', this.employee.setPhoto );
            $( 'body' ).on( 'click', 'a.wphr-remove-photo', this.employee.removePhoto );

            // Trigger
            $('body').on( 'wphr-hr-after-new-dept', this.department.afterNew );
            $('body').on( 'wphr-hr-after-new-desig', this.designation.afterNew );
			
            this.initTipTip();
        },

        initToggleCheckbox: function() {
            var lastClicked = false;

            // check all checkboxes
            $('tbody').children().children('.check-column').find(':checkbox').click( function(e) {
                if ( 'undefined' == e.shiftKey ) { return true; }
                if ( e.shiftKey ) {
                    if ( ! lastClicked ) {
                        return true;
                    }

                    checks  = $( lastClicked ).closest( 'form' ).find( ':checkbox' ).filter( ':visible:enabled' );
                    first   = checks.index( lastClicked );
                    last    = checks.index( this );
                    checked = $(this).prop('checked');

                    if ( 0 < first && 0 < last && first != last ) {
                        sliced = ( last > first ) ? checks.slice( first, last ) : checks.slice( last, first );
                        sliced.prop( 'checked', function() {
                            if ( $(this).closest('tr').is(':visible') )
                                return checked;

                            return false;
                        });
                    }
                }

                lastClicked = this;

                // toggle "check all" checkboxes
                var unchecked = $(this).closest('tbody').find(':checkbox').filter(':visible:enabled').not(':checked');
                $(this).closest('table').children('thead, tfoot').find(':checkbox').prop('checked', function() {
                    return ( 0 === unchecked.length );
                });

                return true;
            });

            $('thead, tfoot').find('.check-column :checkbox').on( 'click.wp-toggle-checkboxes', function( event ) {
                var $this          = $(this),
                    $table         = $this.closest( 'table' ),
                    controlChecked = $this.prop('checked'),
                    toggle         = event.shiftKey || $this.data('wp-toggle');

                $table.children( 'tbody' ).filter(':visible')
                    .children().children('.check-column').find(':checkbox')
                    .prop('checked', function() {
                        if ( $(this).is(':hidden,:disabled') ) {
                            return false;
                        }

                        if ( toggle ) {
                            return ! $(this).prop( 'checked' );
                        } else if ( controlChecked ) {
                            return true;
                        }

                        return false;
                    });

                $table.children('thead,  tfoot').filter(':visible')
                    .children().children('.check-column').find(':checkbox')
                    .prop('checked', function() {
                        if ( toggle ) {
                            return false;
                        } else if ( controlChecked ) {
                            return true;
                        }

                        return false;
                    });
            });
        },

        initTipTip: function() {
            $( '.wphr-tips' ).tipTip( {
                defaultPosition: "top",
                fadeIn: 100,
                fadeOut: 100
            } );
        },

        initDateField: function() {
            $( '.wphr-date-field').datepicker({
                dateFormat: wpHr.date_format,
                changeMonth: true,
                changeYear: true,
                yearRange: '-100:+5',
            });
        },

        reloadPage: function() {
            $( '.wphr-area-left' ).load( window.location.href + ' #wphr-area-left-inner', function() {
                $('.select2').select2();
            } );
        },

        dashboard : {
            markAnnouncementRead: function(e) {
                e.preventDefault();
                var self = $(this);

                if ( ! self.closest( 'li' ).hasClass('unread') ) {
                    return;
                }

                wp.ajax.send( 'wphr_hr_announcement_mark_read', {
                    data: {
                        id : self.data( 'row_id' ),
                        _wpnonce: wpHr.nonce
                    },
                    success: function(res) {
                        self.closest( 'li' ).removeClass( 'unread' ).addClass( 'read' );
                        self.addClass( 'wphr-hide' );
                    },
                    error: function(error) {
                        alert( error );
                    }
                });
            },

            viewAnnouncementTitle: function(e) {
                e.preventDefault();
                var self = $(this).closest( 'li' ).find( 'a.view-full' );
                wp.ajax.send( 'wphr_hr_announcement_view', {
                    data: {
                        id : self.data( 'row_id' ),
                        _wpnonce: wpHr.nonce
                    },
                    success: function(res) {
                        $.wphrPopup({
                            title: res.title,
                            button: '',
                            id: 'wphr-hr-announcement',
                            content: '<p>'+ res.content +'</p>',
                            extraClass: 'midium',
                        });
                        self.closest( 'li' ).removeClass( 'unread' );
                        self.siblings( '.mark-read' ).addClass( 'wphr-hide' );
                    },
                    error: function(error) {
                        alert( error );
                    }
                });
            },

            viewAnnouncement: function(e) {
                e.preventDefault();
                var self = $(this);

                wp.ajax.send( 'wphr_hr_announcement_view', {
                    data: {
                        id : self.data( 'row_id' ),
                        _wpnonce: wpHr.nonce
                    },
                    success: function(res) {
                        $.wphrPopup({
                            title: res.title,
                            button: '',
                            id: 'wphr-hr-announcement',
                            content: '<p>'+ res.content +'</p>',
                            extraClass: 'midium',
                        });
                        self.closest( 'li' ).removeClass( 'unread' );
                        self.siblings( '.mark-read' ).addClass( 'wphr-hide' );
                    },
                    error: function(error) {
                        alert( error );
                    }
                });
            }
        },

        department: {

            /**
             * After create new department
             *
             * @return {void}
             */
            afterNew: function( e, res ) {
                var selectdrop = $('.wphr-hr-dept-drop-down');
                wphr.scriptReload( 'wphr_hr_script_reload', 'tmpl-wphr-new-employee' );
                selectdrop.append('<option selected="selected" value="'+res.id+'">'+res.title+'</option>');
                selectdrop.select2().select2("val", res.id);
            },

            /**
             * Reload the department area
             *
             * @return {void}
             */
            reload: function() {
                $( '#wphr-dept-table-wrap' ).load( window.location.href + ' #wphr-dept-table-wrap', function() {
                    clsWP_HR_HR.initToggleCheckbox();
                } );
            },

            /**
             * Template reload after insert, edit, delete
             *
             * @return {void}
             */
            tempReload: function() {
                wphr.scriptReload( 'wphr_hr_new_dept_tmp_reload', 'tmpl-wphr-new-dept' );
            },

            /**
             * Create new department
             *
             * @param  {event}
             */
            create: function(e) {
                e.preventDefault();
                var self = $(this),
                    is_single = self.data('single');

                $.wphrPopup({
                    title: wpHr.popup.dept_title,
                    button: wpHr.popup.dept_submit,
                    id: 'wphr-hr-new-department',
                    content: wphr.template('wphr-new-dept')().trim(),
                    extraClass: 'smaller',
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function(res) {
                                clsWP_HR_HR.department.reload();

                                if ( is_single != '1' ) {
                                    $('body').trigger( 'wphr-hr-after-new-dept', [res]);
                                } else {
                                    clsWP_HR_HR.department.tempReload();
                                }

                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.showError( error );
                            }
                        });
                    }
                }); //popup
            },

            /**
             * Edit a department in popup
             *
             * @param  {event}
             */
            edit: function(e) {
                e.preventDefault();

                var self = $(this);

                $.wphrPopup({
                    title: wpHr.popup.dept_update,
                    button: wpHr.popup.dept_update,
                    id: 'wphr-hr-new-department',
                    content: wp.template('wphr-new-dept')().trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        var modal = this;

                        $( 'header', modal).after( $('<div class="loader"></div>').show() );

                        wp.ajax.send( 'wphr-hr-get-dept', {
                            data: {
                                id: self.data('id'),
                                _wpnonce: wpHr.nonce
                            },
                            success: function(response) {
                                $( '.loader', modal).remove();

                                $('#dept-title', modal).val( response.name );
                                $('#emp-profile-label', modal).val( response.data.employee_label );
                                $('#dept-desc', modal).val( response.data.description );
                                $('#dept-parent', modal).val( response.data.parent );
                                $('#dept-lead', modal).val( response.data.lead );
                                $('#dept-id', modal).val( response.id );
                                $('#dept-action', modal).val( 'wphr-hr-update-dept' );

                                // disable current one
                                $('#dept-parent option[value="' + self.data('id') + '"]', modal).attr( 'disabled', 'disabled' );

                            }
                        });
                    },
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function() {
                                clsWP_HR_HR.department.reload();
                                clsWP_HR_HR.department.tempReload();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            /**
             * Delete a department
             *
             * @param  {event}
             */
            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmDept ) ) {
                    wp.ajax.send( 'wphr-hr-del-dept', {
                        data: {
                            '_wpnonce': wpHr.nonce,
                            id: self.data( 'id' )
                        },
                        success: function() {
                            self.closest('tr').fadeOut( 'fast', function() {
                                $(this).remove();
                                clsWP_HR_HR.department.tempReload();
                            });
                        },
                        error: function(response) {
                            alert( response );
                        }
                    });
                }
            },

        },

        designation: {

            /**
             * After create new desination
             *
             * @return {void}
             */
            afterNew: function( e, res ) {
                var selectdrop = $('.wphr-hr-desi-drop-down');
                wphr.scriptReload( 'wphr_hr_script_reload', 'tmpl-wphr-new-employee' );
                selectdrop.append('<option selected="selected" value="'+res.id+'">'+res.title+'</option>');
                clsWP_HR_HR.employee.select2AddMoreActive('wphr-hr-desi-drop-down');
                selectdrop.select2("val", res.id);
            },

            /**
             * Reload the department area
             *
             * @return {void}
             */
            reload: function() {
                $( '.wphr-hr-designation' ).load( window.location.href + ' .wphr-hr-designation', function() {
                    clsWP_HR_HR.initToggleCheckbox();
                } );
            },

            /**
             * Create designation
             *
             * @param  {event}
             *
             * @return {void}
             */
            create: function(e) {
                e.preventDefault();
                var is_single = $(this).data('single');
                $.wphrPopup({
                    title: wpHr.popup.desig_title,
                    button: wpHr.popup.desig_submit,
                    id: 'wphr-hr-new-designation',
                    content: wp.template( 'wphr-new-desig' )().trim(),
                    extraClass: 'smaller',
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function(res) {
                                clsWP_HR_HR.designation.reload();
                                if ( is_single != '1' ) {
                                    $('body').trigger( 'wphr-hr-after-new-desig', [res] );
                                }
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            /**
             * Edit a department in popup
             *
             * @param  {event}
             */
            edit: function(e) {
                e.preventDefault();

                var self = $(this);

                $.wphrPopup({
                    title: wpHr.popup.desig_update,
                    button: wpHr.popup.desig_update,
                    content: wp.template( 'wphr-new-desig' )().trim(),
                    id: 'wphr-update-designation',
                    extraClass: 'smaller',
                    onReady: function() {
                        var modal = this;

                        $( 'header', modal).after( $('<div class="loader"></div>').show() );

                        wp.ajax.send( 'wphr-hr-get-desig', {
                            data: {
                                id: self.data('id'),
                                _wpnonce: wpHr.nonce
                            },
                            success: function(response) {
                                $( '.loader', modal).remove();

                                $('#desig-title', modal).val( response.name );
                                $('#desig-desc', modal).val( response.data.description );
                                $('#desig-id', modal).val( response.id );
                                $('#desig-action', modal).val( 'wphr-hr-update-desig' );
                            }
                        });
                    },
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function() {
                                clsWP_HR_HR.designation.reload();

                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            /**
             * Delete a department
             *
             * @param  {event}
             */
            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmDept ) ) {
                    wp.ajax.send( 'wphr-hr-del-desig', {
                        data: {
                            '_wpnonce': wpHr.nonce,
                            id: self.data( 'id' )
                        },
                        success: function() {
                            self.closest('tr').fadeOut( 'fast', function() {
                                $(this).remove();
                            });
                        },
                        error: function(response) {
                            alert( response );
                        }
                    });
                }
            },
        },

        employee: {
			
           /**
            * Extend the user capabities
            *
            * @return {void}
            */
			extendUserCapabities: function(){
				$('.manager_services').toggleClass('wphr-hide');
			},
			
            /**
             * Reload the department area
             *
             * @return {void}
             */
            reload: function() {
                $( '.wphr-hr-employees-wrap' ).load( window.location.href + ' .wphr-hr-employees-wrap-inner', function() {
                    clsWP_HR_HR.initToggleCheckbox();
                } );
            },

            /**
             * Set photo popup
             *
             * @param {event}
             */
            setPhoto: function(e) {
                e.preventDefault();
                e.stopPropagation();

                var frame;

                if ( frame ) {
                    frame.open();
                    return;
                }

                frame = wp.media({
                    title: wpHr.emp_upload_photo,
                    button: { text: wpHr.emp_set_photo }
                });

                frame.on('select', function() {
                    var selection = frame.state().get('selection');

                    selection.map( function( attachment ) {
                        attachment = attachment.toJSON();

                        var html = '<img src="' + attachment.url + '" alt="" />';
                        html += '<input type="hidden" id="emp-photo-id" name="personal[photo_id]" value="' + attachment.id + '" />';
                        html += '<a href="#" class="wphr-remove-photo">&times;</a>';

                        $( '.photo-container', '.wphr-employee-form' ).html( html );
                    });
                });

                frame.open();
            },

            /**
             * Remove an employees avatar
             *
             * @param  {event}
             */
            removePhoto: function(e) {
                e.preventDefault();

                var html = '<a href="#" id="wphr-set-emp-photo" class="button button-small">' + wpHr.emp_upload_photo + '</a>';
                html += '<input type="hidden" name="personal[photo_id]" id="emp-photo-id" value="0">';

                $( '.photo-container', '.wphr-employee-form' ).html( html );
            },

            /**
             * Create a new employee modal
             *
             * @param  {event}
             */
            create: function(e) {
                if ( typeof e !== 'undefined' ) {
                    e.preventDefault();
                }

                if ( typeof wpHr.employee_empty === 'undefined' ) {
                    return;
                }

                $.wphrPopup({
                    title: wpHr.popup.employee_title,
                    button: wpHr.popup.employee_create,
                    id: "wphr-new-employee-popup",
                    content: wphr.template('wphr-new-employee')( wpHr.employee_empty ).trim(),

                    onReady: function() {
                        clsWP_HR_HR.initDateField();
                        $('.select2').select2();
                        clsWP_HR_HR.employee.select2Action('wphr-hrm-select2');
                        clsWP_HR_HR.employee.select2AddMoreContent();

                        $( '#user_notification').on('click', function() {
                            if ( $(this).is(':checked') ) {
                                $('.show-if-notification').show();
                            } else {
                                $('.show-if-notification').hide();
                            }
                        });
                    },

                    /**
                     * Handle the onsubmit function
                     *
                     * @param  {modal}
                     */
                    onSubmit: function(modal) {
                        $( 'button[type=submit]', '.wphr-modal' ).attr( 'disabled', 'disabled' );

                        wp.ajax.send( 'wphr-hr-employee-new', {
                            data: this.serialize(),
                            success: function(response) {
                                clsWP_HR_HR.employee.reload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.enableButton();
                                modal.showError(error);
                            }
                        });
                    }
                });
            },

            /**
             * select2 with add more button content
             *
             * @return  {void}
             */
            select2AddMoreContent: function() {
                var selects = $('.wphr-hrm-select2-add-more');
                $.each( selects, function( key, element ) {
                    clsWP_HR_HR.employee.select2AddMoreActive(element);
                });
            },

            /**
             * select2 with add more button active
             *
             * @return  {void}
             */
            select2AddMoreActive: function(element) {
                var id = $(element).data('id');
                $(element).select2({
                    width: 'element',
                    "language": {
                        noResults: function(){
                            return '<a href="#" class="button button-primary" id="'+id+'">Add New</a>';
                        }
                    },
                    escapeMarkup: function (markup) {
                        return markup;
                    }

                });
            },

            /**
             * select2 action
             *
             * @return  {void}
             */
            select2Action: function(element) {
                $('.'+element).select2({
                    width: 'element',
                });
            },

            /**
             * Edit an employee
             *
             * @param  {event}
             */
            edit: function(e) {
                e.preventDefault();

                var self = $(this);

                $.wphrPopup({
                    title: wpHr.popup.employee_update,
                    button: wpHr.popup.employee_update,
                    id: 'wphr-employee-edit',
                    onReady: function() {
                        var modal = this;

                        $( 'header', modal).after( $('<div class="loader"></div>').show() );

                        wp.ajax.send( 'wphr-hr-emp-get', {
                            data: {
                                id: self.data('id'),
                                _wpnonce: wpHr.nonce
                            },
                            success: function(response) {
                                var html = wp.template('wphr-new-employee')( response );
                                $( '.content', modal ).html( html );
                                $( '.loader', modal).remove();

                                clsWP_HR_HR.initDateField();
                                $('.select2').select2();
                                clsWP_HR_HR.employee.select2Action('wphr-hrm-select2');
                                clsWP_HR_HR.employee.select2AddMoreContent();

                                $( 'li[data-selected]', modal ).each(function() {
                                    var self = $(this),
                                        selected = self.data('selected');

                                    if ( selected !== '' ) {
                                        self.find( 'select' ).val( selected ).trigger('change');
                                        self.find("input[type=radio][value='"+selected+"']").prop("checked",true);
                                        $.each(self.find("input[type=checkbox]"), function(index, data) {
                                            if($.inArray($(data).val(), selected.split(',')) != -1) {
                                                $(data).prop('checked', true);
                                            }
                                        });
                                    }
                                });

                                // disable current one
                                $('#work_reporting_to option[value="' + response.id + '"]', modal).attr( 'disabled', 'disabled' );
                            }
                        });
                    },
                    onSubmit: function(modal) {
                        modal.disableButton();

                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function(response) {
                                clsWP_HR_HR.employee.reload();
                                modal.enableButton();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.enableButton();
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            /**
             * Remove an employee
             *
             * @param  {event}
             */
            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmEmployee ) ) {
                    wp.ajax.send( 'wphr-hr-emp-delete', {
                        data: {
                            _wpnonce: wpHr.nonce,
                            id: self.data( 'id' ),
                            hard: self.data( 'hard' )
                        },
                        success: function() {
                            self.closest('tr').fadeOut( 'fast', function() {
                                $(this).remove();
                                clsWP_HR_HR.employee.reload();
                            });
                        },
                        error: function(response) {
                            alert( response );
                        }
                    });
                }
            },

            restore: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.restoreConfirmEmployee ) ) {
                    wp.ajax.send( 'wphr-hr-emp-restore', {
                        data: {
                            _wpnonce: wpHr.nonce,
                            id: self.data( 'id' ),
                        },
                        success: function() {
                            self.closest('tr').fadeOut( 'fast', function() {
                                $(this).remove();
                                clsWP_HR_HR.employee.reload();
                            });
                        },
                        error: function(response) {
                            alert( response );
                        }
                    });
                }

            },

            general: {

                create: function(e) {
					console.log(e);
                    if ( typeof e !== 'undefined' ) {
                        e.preventDefault();
                    }

                    var self = $(this);

                    $.wphrPopup({
                        title: self.data('title'),
                        content: wp.template( self.data('template' ) )( self.data('data') ),
                        extraClass: 'smaller',
                        id: 'wphr-hr-new-general',
                        button: self.data('button'),
                        onReady: function() {
                            clsWP_HR_HR.initDateField();
                        },
                        onSubmit: function(modal) {
                            wp.ajax.send( {
                                data: this.serializeObject(),
                                success: function() {
                                    clsWP_HR_HR.reloadPage();
                                    modal.closeModal();
                                },
                                error: function(error) {
                                    modal.enableButton();
                                    modal.showError( error );
                                }
                            });
                        }
                    });
                },

                remove: function(e) {
                    e.preventDefault();

                    var self = $(this);

                    if ( confirm( wpHr.confirm ) ) {
                        wp.ajax.send( self.data('action'), {
                            data: {
                                id: self.data('id'),
                                employee_id: self.data('employee_id'),
                                _wpnonce: wpHr.nonce
                            },
                            success: function() {
                                clsWP_HR_HR.reloadPage();
                            },
                            error: function(error) {
                                alert( error );
                            }
                        });
                    }
                },
            },

            updateJobStatus: function(e) {
                if ( typeof e !== 'undefined' ) {
                    e.preventDefault();
                }

                var self = $(this);

                $.wphrPopup({
                    title: self.data('title'),
                    button: wpHr.popup.update_status,
                    id: 'wphr-hr-update-job-status',
                    content: '',
                    extraClass: 'smaller',
                    onReady: function() {
                        var html = wp.template( self.data('template') )(window.wpHrCurrentEmployee);
                        $( '.content', this ).html( html );
                        clsWP_HR_HR.initDateField();

                        $( '.row[data-selected]', this ).each(function() {
                            var self = $(this),
                                selected = self.data('selected');

                            if ( selected !== '' ) {
                                self.find( 'select' ).val( selected );
                            }
                        });
                    },
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serializeObject(),
                            success: function() {
                                clsWP_HR_HR.reloadPage();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.enableButton();
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            removeHistory: function(e) {
                e.preventDefault();

                if ( confirm( wpHr.confirm ) ) {
                    wp.ajax.send( 'wphr-hr-emp-delete-history', {
                        data: {
                            id: $(this).data('id'),
                            _wpnonce: wpHr.nonce
                        },
                        success: function() {
                            clsWP_HR_HR.reloadPage();
                        }
                    });
                }
            },

            printData: function(e) {
                e.preventDefault();
                window.print();
            },

            checkUserEmail: function() {
                var self = $(this),
                    val = self.val(),
                    id = self.closest('form').find('#wphr-employee-id').val();

                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

                if ( val == '' || !re.test( val ) ) {
                    return false;
                }

                if ( id != '0' ) {
                    return false;
                }

                wp.ajax.send( 'wphr_hr_check_user_exist', {
                    data: {
                        email: val,
                        _wpnonce: wpHr.nonce
                    },
                    success: function() {
                        var form = self.closest('form');
                        form.find('.modal-suggession').fadeOut( 300, function() {
                            $(this).remove();
                        });
                        form.find('button[type=submit]' ).removeAttr( 'disabled' );
                    },
                    error: function( response ) {
                        var form = self.closest('form');
                        form.find('button[type=submit]' ).attr( 'disabled', 'disabled');

                        if ( response.type == 'employee' ) {
                            form.find('.modal-suggession').remove();
                            form.find('header.modal-header').append('<div class="modal-suggession">' + wpHr.employee_exit + '</div>');
                        }

                        if ( response.type == 'wp_user' ) {
                            form.find('.modal-suggession').remove();
                            form.find('header.modal-header').append('<div class="modal-suggession">'+ wpHr.make_employee_text +' <a href="#" id="wphr-hr-create-wp-user-to-employee" data-user_id="'+ response.data.ID +'">' + wpHr.create_employee_text + '</a></div>' );
                        }

                        $('.modal-suggession').hide().slideDown( function() {
                            form.find('.content-container').css({ 'marginTop': '15px' });
                        });
                    }
                });
            },

            makeUserEmployee: function(e) {
                e.preventDefault();
                var self = $(this),
                    user_id = self.data('user_id');

                self.closest('.modal-suggession').append('<div class="wphr-loader" style="top:9px; right:10px;"></div>');

                wp.ajax.send( 'wphr-hr-convert-wp-to-employee', {
                    data: {
                        user_id: user_id,
                        _wpnonce: wpHr.nonce
                    },
                    success: function() {
                        self.closest('.modal-suggession').find('.wphr-loader').remove();
                        self.closest('.wphr-modal').remove();
                        $('.wphr-modal-backdrop').remove();
                        clsWP_HR_HR.employee.reload();

                        $.wphrPopup({
                            title: wpHr.popup.employee_update,
                            button: wpHr.popup.employee_update,
                            id: 'wphr-employee-edit',
                            onReady: function() {
                                var modal = this;

                                $( 'header', modal).after( $('<div class="loader"></div>').show() );

                                wp.ajax.send( 'wphr-hr-emp-get', {
                                    data: {
                                        id: user_id,
                                        _wpnonce: wpHr.nonce
                                    },
                                    success: function(response) {
                                        var html = wp.template('wphr-new-employee')( response );
                                        $( '.content', modal ).html( html );
                                        $( '.loader', modal).remove();

                                        clsWP_HR_HR.initDateField();

                                        $( 'li[data-selected]', modal ).each(function() {
                                            var self = $(this),
                                                selected = self.data('selected');

                                            if ( selected !== '' ) {
                                                self.find( 'select' ).val( selected ).trigger('change');
                                            }
                                        });

                                        // disable current one
                                        $('#work_reporting_to option[value="' + response.id + '"]', modal).attr( 'disabled', 'disabled' );
                                    }
                                });
                            },
                            onSubmit: function(modal) {
                                modal.disableButton();

                                wp.ajax.send( {
                                    data: this.serialize(),
                                    success: function(response) {
                                        clsWP_HR_HR.employee.reload();
                                        modal.enableButton();
                                        modal.closeModal();
                                    },
                                    error: function(error) {
                                        modal.enableButton();
                                        modal.showError( error );
                                    }
                                });
                            }
                        });

                    },
                    error: function( response ) {
                        alert(response);
                    }
                });
            },

            addNote: function(e) {
                e.preventDefault();

                var form = $(this),
                    submit = form.find( 'input[type=submit]');

                submit.attr('disabled', 'disabled');
                form.find('.wphr-note-loader').show();

                wp.ajax.send({
                    data: form.serializeObject(),
                    success: function() {
                        $.get( window.location.href, function( data ) {
                            if( $('ul.notes-list li').length < 0 ){
                                $('ul.notes-list').prepend( $(data).find( 'ul.notes-list' ).after() );
                            }else {
                                $('ul.notes-list').prepend( $(data).find( 'ul.notes-list li' ).first() );
                            }

                            if( $('ul.notes-list li').length > 10 ){
                                $('ul.notes-list li').last().remove();
                            }
                            clsWP_HR_HR.employee.showLoadMoreBtn() ;
                            form.find('.wphr-note-loader').hide();
                            form.find('textarea').val('');
                            submit.removeAttr( 'disabled' );
                        });

                    },
                    error: function() {
                        submit.removeAttr('disabled');
                        form.find('.wphr-note-loader').hide();
                    }
                });
            },

            showLoadMoreBtn: function(){
                if( $('ul.notes-list li').length >= 10 ){
                    $('.wpwphr-load-more-btn').show();
                }else {
                    $('.wpwphr-load-more-btn').hide();
                }
            },

            loadNotes: function(e) {
                e.preventDefault();

                var self = $(this),
                    data = {
                        action : 'wphr-load-more-notes',
                        user_id : self.data('user_id'),
                        total_no : self.data('total_no'),
                        offset_no : self.data('offset_no')
                    };

                var spiner = '<span class="wphr-loader" style="margin:4px 0px 0px 10px"></span>';

                self.closest('p')
                    .append( spiner )
                    .find('.wphr-loader')
                    .show();

                self.attr( 'disabled', true );

                wp.ajax.send({
                    data: data,
                    success: function( resp ) {
                        self.data( 'offset_no', parseInt(data.total_no)+parseInt(data.offset_no) );
                        $(resp.content).appendTo(self.closest('.note-tab-wrap').find('ul.notes-list')).hide().fadeIn();
                        self.removeAttr( 'disabled' );
                        $('.wphr-loader').remove();
                    },
                    error: function( error ) {
                        alert(error);
                    }
                });
            },

            deleteNote: function(e) {
                e.preventDefault();

                if ( confirm( wpHr.delConfirmEmployeeNote ) ) {

                    var self = $(this),
                        data = {
                            action: 'wphr-delete-employee-note',
                            note_id: self.data('note_id'),
                            _wpnonce : wpHr.nonce
                        };

                    wp.ajax.send({
                        data: data,
                        success: function( resp ) {
                            self.closest('li').fadeOut( 400, function() {
                                $(this).remove();
                                clsWP_HR_HR.employee.showLoadMoreBtn() ;
                            });
                        },
                        error: function( error ) {
                            alert(error);
                        }
                    });
                }
            },

            updatePerformance: function(e) {

                if ( typeof e !== 'undefined' ) {
                    e.preventDefault();
                }

                var self = $(this);

                $.wphrPopup({
                    title: self.data('title'),
                    button: wpHr.popup.update_status,
                    id: 'wphr-hr-update-performance',
                    content: '',
                    extraClass: 'smaller',
                    onReady: function() {
                        var html = wp.template( self.data('template') )(window.wpHrCurrentEmployee);
                        $( '.content', this ).html( html );
                        clsWP_HR_HR.initDateField();
                        clsWP_HR_HR.employee.select2Action('wphr-hrm-select2');

                        $( '.row[data-selected]', this ).each(function() {
                            var self = $(this),
                                selected = self.data('selected');

                            if ( selected !== '' ) {
                                self.find( 'select' ).val( selected );
                            }
                        });
                    },
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serializeObject(),
                            success: function() {
                                clsWP_HR_HR.reloadPage();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.enableButton();
                                modal.showError( error );
                            }
                        });
                    }
                });
            },

            removePerformance: function(e) {
                e.preventDefault();

                if ( confirm( wpHr.confirm ) ) {
                    wp.ajax.send({
                        data: {
                            action: 'wphr-hr-emp-delete-performance',
                            id: $(this).data('id'),
                            _wpnonce: wpHr.nonce
                        },
                        success: function() {
                            clsWP_HR_HR.reloadPage();
                        }
                    });
                }
            },

            terminateEmployee: function(e) {

                if ( typeof e !== 'undefined' ) {
                    e.preventDefault();
                }

                var self = $(this);

                if ( self.data('data') ) {
                    var terminateData = self.data('data');
                } else {
                    var terminateData = window.wpHrCurrentEmployee;
                }

                $.wphrPopup({
                    title: self.data('title'),
                    button: wpHr.popup.terminate,
                    id: 'wphr-hr-employee-terminate',
                    content: '',
                    extraClass: 'smaller',
                    onReady: function() {
                        var html = wp.template( self.data('template') )( terminateData );
                        $( '.content', this ).html( html );
                        clsWP_HR_HR.initDateField();

                        $( '.row[data-selected]', this ).each(function() {
                            var self = $(this),
                                selected = self.data('selected');

                            if ( selected !== '' ) {
                                self.find( 'select' ).val( selected );
                            }
                        });

                        clsWP_HR_HR.employee.select2Action('wphr-hrm-select2');
                    },
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serializeObject(),
                            success: function() {
                                clsWP_HR_HR.reloadPage();
                                modal.closeModal();
                            },
                            error: function(error) {
                                modal.enableButton();
                                modal.showError( error );
                            }
                        });
                    }
                });

            },

            activateEmployee: function(e) {
                e.preventDefault();

                if ( confirm( wpHr.confirm ) ) {
                    wp.ajax.send({
                        data: {
                            action: 'wphr-hr-emp-activate',
                            id: $(this).data('id'),
                            _wpnonce: wpHr.nonce
                        },
                        success: function() {
                            clsWP_HR_HR.reloadPage();
                        }
                    });
                }
            },

            changeEmployeeStatus: function(e) {
                e.preventDefault();

                var self = $(this),
                    form = self.closest('form'),
                    selectField = form.find( 'select#wphr-hr-employee-status-option' ),
                    optionVal = selectField.val(),
                    selected = selectField.attr('data-selected');


                if ( 'terminated' == optionVal  ) {
                    if ( optionVal != selected ) {
                        $.wphrPopup({
                            title: self.data('title'),
                            button: wpHr.popup.terminate,
                            id: 'wphr-hr-employee-terminate',
                            content: '',
                            extraClass: 'smaller',
                            onReady: function() {
                                var html = wp.template( 'wphr-employment-terminate' )({});
                                $( '.content', this ).html( html );
                                clsWP_HR_HR.initDateField();

                                clsWP_HR_HR.employee.select2Action('wphr-hrm-select2');
                            },
                            onSubmit: function(modal) {
                                wp.ajax.send( {
                                    data: this.serializeObject(),
                                    success: function() {
                                        clsWP_HR_HR.reloadPage();
                                        modal.closeModal();
                                    },
                                    error: function(error) {
                                        modal.enableButton();
                                        modal.showError( error );
                                    }
                                });
                            }
                        });
                    } else {
                        alert( wpHr.popup.already_terminate );
                    }
                } else if ( 'active' == optionVal ) {
                    if ( optionVal != selected ) {
                        var self = $(this);
                        $.wphrPopup({
                            title: wpHr.popup.employment_status,
                            button: wpHr.popup.update_status,
                            id: 'wphr-hr-update-job-status',
                            content: '',
                            extraClass: 'smaller',
                            onReady: function() {
                                var html = wp.template('wphr-employment-status')(window.wpHrCurrentEmployee);
                                $( '.content', this ).html( html );
                                clsWP_HR_HR.initDateField();
                            },
                            onSubmit: function(modal) {
                                wp.ajax.send( {
                                    data: this.serializeObject(),
                                    success: function() {
                                        modal.closeModal();
                                        form.submit();
                                    },
                                    error: function(error) {
                                        modal.enableButton();
                                        modal.showError( error );
                                    }
                                });
                            }
                        });
                    } else {
                        alert( wpHr.popup.already_active );
                    }

                } else {
                    form.submit();
                }
            },

			ressend_welcome_email: function(e) {
				e.preventDefault();
				var data = {
					user_id: $(this).data('user_id'),
					_wpnonce: $('#_wpnonce').val(), 	
				};
				if (typeof $(this).attr('disabled') !== typeof undefined && $(this).attr('disabled') !== false) {
					return false;
				}
				$(this).parent('.resend_email').find('span.spinner').addClass('active');
				$(this).attr('disabled', 'disabled');
				var success_text = $(this).data('success');
				var currentObj = $(this);
                wp.ajax.send( 'wphr-hr-employee-resend-email', {
                    data: data,
                    success: function(response) {
						$('.wphr-hr-employees table.wp-list-table .resend_email span.spinner').removeClass('active');
						
						if( response == 1 ){
							$(currentObj).html(success_text);
						}else{
							$(currentObj).removeAttr('disabled');
						}
                    },
                });
			}
        }
    };

    $(function() {
        clsWP_HR_HR.initialize();
    });
})(jQuery);
