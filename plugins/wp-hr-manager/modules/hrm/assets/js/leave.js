/* jshint devel:true */
/* global wpHr */
/* global wp */

;(function($) {
    'use strict';
    var import_file = '';
    var Leave = {

        initialize: function() {
            
            var self = this;

            //Leave
            $( '.wphr-hr-leave-policy' ).on( 'click', 'a#wphr-leave-policy-new', self, this.policy.create );
            $( '.wphr-hr-leave-policy' ).on( 'click', 'a.link, span.edit a', self, this.policy.edit );
            $( '.wphr-hr-leave-policy' ).on( 'click', 'a.submitdelete', self, this.policy.remove );
            $( 'body' ).on( 'change', '#wphr-hr-leave-req-from-date, #wphr-hr-leave-req-to-date', self, this.leave.requestDates );
            $( 'body').on( 'change', '#wphr-hr-leave-req-from-time, #wphr-hr-leave-req-to-time', self, this.leave.requestTimes),
            $( 'body' ).on( 'change', '#from-time', self, this.leave.toTimes ),
            $( 'body' ).on( 'change', '#wphr-hr-leave-req-employee-id', self, this.leave.setPolicy );
            $( 'body' ).on( 'change', '#wphr-hr-leave-req-leave-policy', self, this.leave.setAvailableDays );
            $( '.hrm-dashboard, .leads-actions' ).on( 'click', '.wphr-hr-new-leave-request-wrap a#wphr-hr-new-leave-req', this.leave.takeLeave );
            $( '.wphr-employee-single' ).on('submit', 'form#wphr-hr-empl-leave-history', this.leave.showHistory );
            $( 'body' ).on("click", '#wphr-hr-leave-houly-req', this.leave.show_timeslots);
            $( 'body' ).on("click", '.hourly_leave_handler', this.leave.show_timeslots2);
            $( '.entitlement-list-table' ).on( 'click', 'a.submitdelete', self, this.entitlement.remove );

            //Holiday
            $( '.wphr-hr-holiday-wrap' ).on( 'click', 'a#wphr-hr-new-holiday', self, this.holiday.create );
            $( '.wphr-hr-holiday-wrap' ).on( 'click', '.wphr-hr-holiday-edit', self, this.holiday.edit );
            $( '.wphr-hr-holiday-wrap' ).on( 'click', '.wphr-hr-holiday-delete', self, this.holiday.remove );
            $( 'body' ).on( 'change', '.wphr-hr-holiday-date-range', self, this.holiday.checkRange );

            //Holiday By Location ( By Taslim )
            $(".wphr-hr-holiday-by-location-wrap").on("click", "a#wphr-hr-new-holiday-by-location", self, this.holiday.createByLocation);
            $(".wphr-hr-holiday-by-location-wrap").on("click", ".wphr-hr-holiday-by-location-edit", self, this.holiday.editByLocation);
            $(".wphr-hr-holiday-by-location-wrap").on("click", ".wphr-hr-holiday-by-location-delete", self, this.holiday.remove);
            $("body").on("change", ".wphr-hr-holiday-by-location-date-range", self, this.holiday.checkRange);
            

            // ICal calendar import
            $( '.wphr-hr-holiday-wrap' ).on( 'click', '#wphr-hr-import-ical', self, this.importICalInit );
            $( '.wphr-hr-holiday-wrap' ).on( 'change', '#wphr-ical-input', self, this.uploadICal );
            $( '.wphr-hr-leave-requests' ).on( 'click', '.wphr-hr-leave-reject-btn', self, this.leave.reject );


            // ICal calendar import By Location ( By Taslim )
            $(".wphr-hr-holiday-by-location-wrap").on("click", "#wphr-hr-import-ical-by-location", self, this.importICalInit_byLocation);
            $("body").on("change", "#wphr-hr-import-by-location", self, this.uploadICal_byLocation), 

            this.initDateField();
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

        initDateField: function() {
            $( '.wphr-leave-date-field' ).datepicker({
                dateFormat: wpHr.date_format,
                changeMonth: true,
                changeYear: true
            });

            $('.wphr-leave-time-field').timepicker({ 
                'showDuration': true,
                'timeFormat': 'g:ia',
                'minTime': '8:00am',
                'maxTime': '11:30pm',
             });

            $( ".wphr-leave-date-picker-from" ).datepicker({
                dateFormat: wpHr.date_format,
                changeYear: true,
                changeMonth: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wphr-leave-date-picker-to" ).datepicker( "option", "minDate", selectedDate );
                }
            });

            $( ".wphr-leave-date-picker-to" ).datepicker({
                dateFormat: wpHr.date_format,
                changeMonth: true,
                changeYear: true,
                numberOfMonths: 1,
                onClose: function( selectedDate ) {
                    $( ".wphr-leave-date-picker-from" ).datepicker( "option", "maxDate", selectedDate );
                }
            });
        },

        holiday: {
            checkRange: function() {
                var self = $('input[name="range"]');

                if ( self.is(':checked') ) {
                    $('input[name="end_date"]').closest('.row').show();
                } else {
                    $('input[name="end_date"]').closest('.row').hide();
                }
            },

            create: function(e) {
                e.preventDefault();

                $.wphrPopup({
                    title: wpHr.popup.holiday,
                    button: wpHr.popup.holiday_create,
                    id: 'wphr-hr-holiday-create-popup',
                    content: wphr.template('wphr-hr-holiday-js-tmp')({ data: null }).trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        Leave.initDateField();
                        Leave.holiday.checkRange();
                        Leave.initToggleCheckbox();
                    },
                    onSubmit: function(modal) {
                        e.data.holiday.submit.call(this, modal);
                    }
                }); //popup
            },
            createByLocation: function(e) {
                e.preventDefault();

                $.wphrPopup({
                    title: 'Holiday By Location',
                    button: wpHr.popup.holiday_create,
                    id: "wphr-hr-holiday-createByLocation-popup",
                    content: wphr.template("wphr-hr-holiday-by-location-js-tmp")({
                        data: null
                    }).trim(),
                    extraClass: "smaller",
                    onReady: function() {
                        Leave.initDateField();
                        Leave.holiday.checkRange();
                        Leave.initToggleCheckbox();
                        if($('select#wphr-hr-leave-holiday-by-location-country').length <= 0)
                        {
                            alert('No location specified!');
                            $('.close').trigger('click');
                            return false;
                        }
                    },
                    onSubmit: function(modal) {
                        e.data.holiday.submit.call(this, modal);
                    }
                }); //popup
            },

            edit: function(e) {
                e.preventDefault();
                var self = $(this);
                $.wphrPopup({
                    title: wpHr.popup.holiday,
                    button: wpHr.popup.holiday_update,
                    id: 'wphr-hr-holiday-create-popup',
                    content: wphr.template('wphr-hr-holiday-js-tmp')({ data: null }).trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        Leave.initDateField();
                        Leave.holiday.checkRange();
                        var modal = this;
                        $( 'header', modal).after( $('<div class="loader"></div>').show() );

                        wp.ajax.send( 'wphr-hr-get-holiday', {
                            data: {
                                id: self.data('id'),
                                _wpnonce: wpHr.nonce
                            },
                            success: function(response) {
                                $( '.loader', modal).remove();
                                var holiday = response.holiday;

                                $( '#wphr-hr-holiday-title', modal ).val( holiday.title );
                                $( '#wphr-hr-holiday-start', modal ).val( holiday.start );
                                $( '#wphr-hr-holiday-end', modal ).val( holiday.end );
                                $( '#wphr-hr-holiday-id', modal ).val( holiday.id );
                                $( '#wphr-hr-holiday-description', modal ).val( holiday.description );
                                $( '#wphr-hr-holiday-action', modal ).val( 'wphr_hr_holiday_create' );

                                var date1 = new Date( holiday.start );
                                var date2 = new Date( holiday.end );
                                var timeDiff = Math.abs( date2.getTime() - date1.getTime() );
                                var diffDays = Math.ceil( timeDiff / ( 1000 * 3600 * 24 ) );

                                if ( diffDays > 0 ) {
                                    $( '#wphr-hr-holiday-range' ).attr( 'checked', 'checked' );
                                    $( '#wphr-hr-holiday-range' ).trigger( 'change' );
                                };
                            }
                        });
                    },
                    onSubmit: function(modal) {
                        e.data.holiday.submit.call(this, modal);
                    }
                }); //popup
            },
            editByLocation: function(e) {
                e.preventDefault();
                var self = $(this);
                $.wphrPopup({
                    title: 'Edit Holiday ( By Location )',
                    button: 'Update Holiday',
                    id: "wphr-hr-holiday-by-location-create-popup",
                    content: wphr.template("wphr-hr-holiday-by-location-js-tmp")({ data: null }).trim(),
                    extraClass: "smaller",
                    onReady: function() {
                        Leave.initDateField();
                        Leave.holiday.checkRange();

                        if($('select#wphr-hr-leave-holiday-by-location-country').length <= 0)
                        {
                            alert('No location specified!');
                            $('.close').trigger('click');
                            return false;
                        }
                        
                        var modal = this;
                        $( 'header', modal).after( $('<div class="loader"></div>').show() );

                        wp.ajax.send("wphr-hr-get-holiday-by-location", {
                            data: {
                                id: self.data("id"),
                                _wpnonce: wpHr.nonce
                            },
                            success: function(response) {
                                $( '.loader', modal).remove();
                                var holiday = response.holiday;
                                
                                $("#wphr-hr-holiday-by-location-title", modal ).val( holiday.title );
                                $("#wphr-hr-holiday-by-location-start", modal ).val( holiday.start), 
                                $("#wphr-hr-holiday-by-location-end", modal ).val( holiday.end), 
                                $("#wphr-hr-holiday-by-location-id", modal ).val( holiday.id), 
                                $("#wphr-hr-holiday-by-location-description", modal ).val( holiday.description), 
                                $("#wphr-hr-leave-holiday-by-location-country", modal ).val( holiday.location_id), 
                                $("#wphr-hr-holiday-by-location-action", modal).val("wphr_hr_holiday_create");
                                
                                var date1 = new Date( holiday.start );
                                var date2 = new Date( holiday.end );
                                var timeDiff = Math.abs( date2.getTime() - date1.getTime() );
                                var diffDays = Math.ceil( timeDiff / ( 1000 * 3600 * 24 ) );
                                
                                if ( diffDays > 0 ) {
                                    $( '#wphr-hr-holiday-by-location-range' ).attr( 'checked', 'checked' );
                                    $( '#wphr-hr-holiday-by-location-range' ).trigger( 'change' );
                                };
                            }
                        })
                    },
                    onSubmit: function(modal) {
                        e.data.holiday.submit.call(this, modal);
                    }
                });
            },

            /**
             * Remove holiday
             *
             * @param  {event}
             */
            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmHoliday ) ) {
                    wp.ajax.send( 'wphr-hr-holiday-delete', {
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

            submit: function(modal) {
                wp.ajax.send( {
                    data: this.serializeObject(),
                    success: function() {
                        modal.closeModal();

                        $( '.list-table-wrap' ).load( window.location.href + ' .list-wrap-inner', function() {
                            Leave.initDateField();
                            Leave.initToggleCheckbox();
                        } );
                    },
                    error: function(error) {
                        modal.enableButton();
                        modal.showError( error );
                    }
                });
            },
        },

        policy: {
            periodField: function() {
                if (3 == $('.wphr-hr-leave-period').val()) {
                    $('.hide-if-manual').hide();
                }

                $('.wphr-hr-leave-period').on( 'change', function() {
                    var type = $(this).val();

                    if ( type == 2 ) {
                        $('.showifschedule').slideDown();
                    } else {
                        $('.showifschedule').slideUp();
                    };

                    if (3 != type) {
                        $('.hide-if-manual').slideDown();
                    } else {
                        $('.hide-if-manual').slideUp();
                    }
                });
            },

            submit: function(modal) {
                wp.ajax.send( {
                    data: this.serializeObject(),
                    success: function() {
                        modal.closeModal();
                        $( '.list-table-wrap' ).load( window.location.href + ' .list-wrap-inner', function() {
                            Leave.initToggleCheckbox();
                        } );
                    },
                    error: function(error) {
                        modal.enableButton();
                        alert( error );
                    }
                });
            },

            create: function(e) {
                e.preventDefault();

                $.wphrPopup({
                    title: wpHr.popup.policy,
                    button: wpHr.popup.policy_create,
                    id: 'wphr-hr-leave-policy-create-popup',
                    content: wp.template('wphr-leave-policy')({ data: null }).trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        Leave.initDateField();
                        $('.wphr-color-picker').wpColorPicker().wpColorPicker( 'color', '#fafafa' );
                        Leave.policy.periodField();
                    },
                    onSubmit: function(modal) {
                        e.data.policy.submit.call(this, modal);
                    }
                }); //popup
            },

            edit: function(e) {
                e.preventDefault();

                var self = $(this),
                    data = self.closest('tr').data('json');

                $.wphrPopup({
                    title: wpHr.popup.policy,
                    button: wpHr.popup.update_status,
                    id: 'wphr-hr-leave-policy-edit-popup',
                    content: wphr.template('wphr-leave-policy')(data).trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        var modal = this;
                        Leave.initDateField();
                        $('.wphr-color-picker').wpColorPicker();

                        $( 'div.row[data-selected]', modal ).each(function() {
                            var self = $(this),
                                selected = self.data('selected');

                            if ( selected !== '' ) {
                                self.find( 'select' ).val( selected );
                            }
                        });

                        $( 'div.row[data-checked]', modal ).each(function( key, val ) {
                            var self = $(this),
                                checked = self.data('checked');

                            if ( checked !== '' ) {
                                self.find( 'input[value="'+checked+'"]' ).attr( 'checked', 'checked' );
                            }
                        });

                        Leave.policy.periodField();
                        $('.wphr-hr-leave-period').trigger('change');
                    },
                    onSubmit: function(modal) {
                        e.data.policy.submit.call(this, modal);
                    }
                }); //popup
            },

            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmPolicy ) ) {
                    wp.ajax.send( 'wphr-hr-leave-policy-delete', {
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

        entitlement: {
            remove: function(e) {
                e.preventDefault();

                var self = $(this);

                if ( confirm( wpHr.delConfirmEntitlement ) ) {
                    wp.ajax.send( 'wphr-hr-leave-entitlement-delete', {
                        data: {
                            '_wpnonce': wpHr.nonce,
                            id: self.data( 'id' ),
                            user_id: self.data( 'user_id' ),
                            policy_id: self.data( 'policy_id' ),
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
            }
        },

        leave: {
            show_timeslots: function(){
                if( $(this).is(':checked') ){
                    $('.wphr-leave-time-slot').parents('div.row').show();
                    $('.wphr-leave-time-slot').attr('required','required');
                    $('.show-days').hide();
                }else{
                    $('.wphr-leave-time-slot').parents('div.row').hide();
                    $('.wphr-leave-time-slot').val('');
                    $('.wphr-leave-time-slot').removeAttr('required');
                    $('.show-days').show();
                }
            },
            show_timeslots2: function(){
                if( $(this).is(':checked') ){
                    //$(this).parents('#wphr-hr-new-leave-req-popup').attr( 'style', 'min-width:780px !important;');
                    $(this).next('.time-slot').show();
                    $(this).next('.time-slot').find('select').attr('required','required');
                }else{
                    $(this).next('.time-slot').hide();
                    $(this).next('.time-slot').find('select').val('');
                    $(this).next('.time-slot').find('select').removeAttr('required');
                }
            },
            takeLeave: function(e) {
                e.preventDefault();

                $.wphrPopup({
                    title: wpHr.popup.new_leave_req,
                    button: wpHr.popup.take_leave,
                    id: 'wphr-hr-new-leave-req-popup',
                    content: wp.template( 'wphr-new-leave-req' )().trim(),
                    extraClass: 'smaller',
                    onReady: function() {
                        Leave.initDateField();
                    },
                    onSubmit: function(modal) {
                        $( 'button[type=submit]', '.wphr-modal' ).attr( 'disabled', 'disabled' );

                        wp.ajax.send( {
                            data: this.serialize(),
                            success: function(res) {
                                modal.enableButton();
                                alert( res );
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
            requestTimes: function() {
                var fromTime = $("#wphr-hr-leave-req-from-time").val(),
                    toTime = $("#wphr-hr-leave-req-to-time").val(),
                    submit = $(this).closest('form').find('input[type=submit]'),
                    user_id = parseInt( $( '#wphr-hr-leave-req-employee-id').val() ),
                    type = $('#wphr-hr-leave-req-leave-policy').val();
                    console.log(fromTime);

                    var firstTime = false,
                    countHours = 0,
                    current_value = toTime;
                    if( !fromTime ){
                        submit.prop("disabled", 1);
                        return false;
                    }   
                    $("#wphr-hr-leave-req-to-time option").show();  
                    $("#wphr-hr-leave-req-to-time option").each( function(){
                        if( !firstTime ){
                            $( this ).hide();
                        }
                        if( $( this ).val() == fromTime ){
                            firstTime = true;
                            var newValue = $("#wphr-hr-leave-req-to-time option[value='"+ fromTime +"']").next().attr('value');
                            $("#wphr-hr-leave-req-to-time").val( newValue );
                            toTime = newValue;
                        }
                        if( firstTime ){
                            countHours++;
                        }   
                    });
                    if( $("#wphr-hr-leave-req-to-time option[value='"+ current_value +"']").css('display') == 'block' ){
                        $("#wphr-hr-leave-req-to-time").val( current_value );
                    }
                    
                    //return;   
                if ( fromTime !== '' && toTime !== '' ) {

                    wp.ajax.send( 'wphr-hr-leave-request-req-time', {
                        data: {
                            '_wpnonce': wpHr.nonce,
                            fromTime : fromTime,
                            toTime : $("#wphr-hr-leave-req-to-time").val(),
                            start_date: $("#wphr-hr-leave-req-from-date").val(),
                            end_date: $("#wphr-hr-leave-req-to-date").val(),
                            employee_id: user_id,
                            type : type
                        },
                        success: function(resp) {
                            submit.prop("disabled", !1);
                        },
                        error: function(response) {
                            submit.attr( 'disabled', 'disable' );
                            if ( typeof response != 'undefined' ) {
                                alert( response );
                            }
                        }
                    });
                }
            },
            toTimes: function() {
                
                var from = $(this),
                    to = from.next().next(),
                    fromTime = from.val(),
                    toTime = to.val();

                    var firstTime = false,
                    countHours = 0,
                    current_value = toTime;
                     
                    to.find("option").show();
                    to.find("option").each( function(){
                        if( !firstTime ){
                            $( this ).hide();
                        }
                        if( $( this ).val() == fromTime ){
                            firstTime = true;
                            var newValue = to.find("option[value='"+ fromTime +"']").next().attr('value');
                            to.val( newValue );
                            toTime = newValue;
                        }
                        if( firstTime ){
                            countHours++;
                        }   
                    });
                    if( to.find("option[value='"+ current_value +"']").css('display') == 'block' ){
                        to.val( current_value );
                    }
            },
            requestDates: function() {
                var from = $('#wphr-hr-leave-req-from-date').val(),
                    to = $('#wphr-hr-leave-req-to-date').val(),
                    submit = $(this).closest('form').find('input[type=submit]'),
                    user_id = parseInt( $( '#wphr-hr-leave-req-employee-id').val() ),
                    type = $('#wphr-hr-leave-req-leave-policy').val();
				
					if( !$(this).closest('form').find('input[type=submit]').length ){
						submit = $(this).closest('form').find('button[type=submit]');
					}
					
                if ( from !== '' && to !== '' ) {

                    wp.ajax.send( 'wphr-hr-leave-request-req-date', {
                        data: {
                            '_wpnonce': wpHr.nonce,
                            from: from,
                            to: to,
                            employee_id: user_id,
                            type : type
                        },
                        success: function(resp) {
                            var html = wp.template('wphr-leave-days')(resp.print);
                            var time_slots_data = resp.time_slot;
                            console.log(time_slots_data);
                            if( from == to && resp.leave_count > 0 ) {
                                $('#wphr-hr-leave-houly-req').parents('div.row').show();
                                jQuery('.wphr-leave-time-slot').attr('required','required');
                                jQuery('.wphr-leave-time-slot').parents('div.row').css('display','block');
                            }
                            else {
                                $('#wphr-hr-leave-houly-req').parents('div.row').hide();
                                $('#wphr-hr-leave-houly-req').attr('checked', false);
                                $('.wphr-leave-time-slot').val('');
                                $('.wphr-leave-time-slot').removeAttr('required');
                                $('.wphr-leave-time-slot').parents('div.row').hide();
                                $('.show-days').show();  
                            }
	                        
							$('#wphr-hr-leave-req-leave-policy').closest('div.row').find('span.description').remove();
	                        $(resp.message).insertAfter('#wphr-hr-leave-req-leave-policy');
							
                            $('div.wphr-hr-leave-req-show-days').html( html );

                            var timeslots = $('select.time-slot');
                            timeslots.html('');
                            timeslots.html("<option value=''>- Select -</option>");

                            $.each( time_slots_data, function(i, time_slots_data){
                                timeslots.append("<option value='"+time_slots_data.key+"'>"+time_slots_data.value+"</option>");
                            });

                            if ( parseInt( resp.leave_count ) <= 0 ) {
                                submit.prop('disabled', true);
                            } else {
                                submit.prop('disabled', false);
                            }

                        },
                        error: function(response) {
                            $('div.wphr-hr-leave-req-show-days').empty();
                            submit.attr( 'disabled', 'disable' );
                            if ( typeof response != 'undefined' ) {
                                alert( response );
                            }
                        }
                    });
                }
            },

            setPolicy: function() {
                Leave.leave.resetDateRange();
                var self = $(this),
                    leaveWrap = $('div.wphr-hr-leave-reqs-wrap'),
                    leavetypewrap = leaveWrap.find('.wphr-hr-leave-type-wrapper'),
                    timeslots = leaveWrap.find('.wphr-leave-time-slot');


                leavetypewrap.html('');

                if ( self.val() == 0 ) {
                    return;
                };

                wp.ajax.send( 'wphr-hr-leave-employee-assign-policies', {
                    data: {
                        '_wpnonce'  : wpHr.nonce,
                        employee_id : self.val()
                    },
                    success: function(resp) {
                        leavetypewrap.html( resp.data ).hide().fadeIn();
                        var time_slots = resp.time_slot;
                        var timeslots_var = '';
                        timeslots.html('');
                        
                        timeslots.append("<option value=''>- Select -</option>");
                        $.each(time_slots, function(i, time_slots){
                                timeslots.append("<option value='"+time_slots.key+"'>"+time_slots.value+"</option>");
                            });
                        console.log(timeslots);
                        leaveWrap.find( 'input[type="text"], textarea').removeAttr('disabled');
                    },
                    error: function(resp) {
                        leavetypewrap.html( wpHr.empty_entitlement_text ).hide().fadeIn();
                    }
                } );
            },

            setAvailableDays: function() {
                Leave.leave.resetDateRange();
                var self = $(this);

                wp.ajax.send( 'wphr-hr-leave-policies-availablity', {
                    data: {
                        '_wpnonce'  : wpHr.nonce,
                        employee_id : $('#wphr-hr-leave-req-employee-id').val(),
                        policy_id   : self.val()
                    },
                    success: function(resp) {
                        self.closest('div.row').find('span.description').remove();
                        $(resp).insertAfter(self);
                    },
                    error: function(resp) {
                        alert( resp );
                    }
                } );
            },

            resetDateRange: function() {
                $('#wphr-hr-leave-req-from-date').val('');
                $('#wphr-hr-leave-req-to-date').val('');
                $('div.wphr-hr-leave-req-show-days').html('');
            },

            showHistory: function(e) {
                e.preventDefault();

                var form = $(this);

                wp.ajax.send( 'wphr-hr-empl-leave-history', {
                    data: form.serializeObject(),
                    success: function(resp) {
                        $('table#wphr-hr-empl-leave-history tbody').html(resp);
                    }
                } );
            },

            pageReload: function() {
                $( '.wphr-hr-leave-requests' ).load( window.location.href + ' .wphr-hr-leave-requests-inner' );
            },


            reject: function(e) {
                e.preventDefault();

                var self = $(this),
                data = {
                    id : self.data('id')
                }

                $.wphrPopup({
                    title: wpHr.popup.leave_reject,
                    button: wpHr.popup.update_status,
                    id: 'wphr-hr-leave-reject-popup',
                    content: wphr.template('wphr-hr-leave-reject-js-tmp')(data).trim(),
                    extraClass: 'smaller',
                    onSubmit: function(modal) {
                        wp.ajax.send( {
                            data: this.serialize()+'&_wpnonce='+wpHr.nonce,
                            success: function(res) {
                                Leave.leave.pageReload();
                                modal.closeModal();
                                //location.reload();
                            },
                            error: function(error) {
                                modal.showError( error );
                            }
                        });
                    }
                }); //popup
            }
        },

        importICalInit: function ( e ) {
            e.preventDefault();
            $( 'body #wphr-ical-input' ).trigger( 'click' );
        },

        uploadICal: function ( e ) {
            e.preventDefault();

            var icsFile = e.target.files[0],
                data = new FormData(),
                form = $(this).parents('form');

            data.append( 'ics', icsFile );
            data.append( 'action', 'wphr-hr-import-ical' );
            data.append( '_wpnonce', wpHr.nonce );

            wp.ajax.send( {
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function() {
                    $( '.list-table-wrap' ).load( window.location.href + ' .list-wrap-inner', function() {
                        Leave.initDateField();
                    } );

                    form[0].reset();
                },
                error: function(error) {
                    form[0].reset();
                    alert( error );
                }
            });
        },
        importICalInit_byLocation: function( e ) {
            e.preventDefault(),

            $.wphrPopup({
                    title:'Import iCal',
                    button: 'submit',
                    id: "wphr-hr-holiday-by-location-import-popup",
                    content: wphr.template("wphr-hr-import-ical-by-location-js-tmp")({
                        data: null
                    }).trim(),
                    extraClass: "smaller",
                    onSubmit: function(a) {
                        var country_val = $('#wphr-hr-leave-holiday-by-location-country').val();
                        var file = $('#wphr-hr-import-by-location').val();
                        if(country_val == '')
                        {
                            alert('Please Select Country to continue import');
                            return false;
                        }
                        else if(file == '')
                        {   
                            alert('File not selected');
                            return false;
                        }
                        importIcalByLocation();
                    }
                })
             //a("body #wphr-ical-input").trigger("click")
        },
        uploadICal_byLocation: function( event ) {
            event.preventDefault();
            import_file = event.target.files[0];
        }
    };

    $(function() {
        Leave.initialize();
    });

    function importIcalByLocation(obj){
            var location_id = $('#wphr-hr-leave-holiday-by-location-country').val();
            var form = $('.wphr-modal-form'),
                data = new FormData,
                d = $('input[name="ics"]').val();

            data.append("ics",import_file);
            data.append("action", "wphr-hr-import-ical");
            data.append("_wpnonce", wpHr.nonce);
            data.append("location_id", location_id);

            wp.ajax.send({
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function() {
                    $(".list-table-wrap").load(window.location.href + " .list-wrap-inner", function() {
                        Leave.initDateField()
                    }), form[0].reset()

                    $('.close').click();

                },
                error: function(error) {
                    form[0].reset();
                    alert( error );
                }
            })
    }
})(jQuery);
