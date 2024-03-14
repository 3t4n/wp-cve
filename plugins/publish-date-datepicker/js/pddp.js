/*
 * PDDP JS
 */
( function ( $ ) {
	'use strict';
	var mm, dd, yy, hh, mn;

	$( document ).ready( function() {
		$('#timestampdiv p').append( '<a href="" class="pddp button">Hide Datepicker</a>' );
		$('#timestampdiv p').after( '<div id="pddp_datepicker"></div>' );

		$( '#misc-publishing-actions a.edit-timestamp' ).click( function() {
			mm = $(this).parent().parent().find( '#mm option' ).filter( ':selected' ).val();
			dd = $(this).parent().parent().find( '#jj' ).val();
			yy = $(this).parent().parent().find( '#aa' ).val();
			hh = $(this).parent().parent().find( '#hh' ).val();
			mn = $(this).parent().parent().find( '#mn' ).val();

			$( '#pddp_datepicker' ).datetimepicker( {
				controlType:	 'select',
				dateFormat:	 'mm/dd/yy',
				yearRange:	 '1900:2100',
				changeMonth:	 true,
				changeYear:	 true,
				showButtonPanel: true,
				defaultDate:	 mm+'/'+dd+'/'+yy,
				hour:		 parseInt(hh),
				minute:		 parseInt(mn),
				onSelect: function( datetimeText, inst ) {
					add_reset_button();

					datetimeText = datetimeText.split( ' ' );
					var dateText = datetimeText[0].split( '/' );
					var dd1 = $(this).parent().parent().find( '#jj' ).val();

					if ( dd1 != dateText[1] ) {
						$( '#timestampdiv' ).find( '#jj' ).val(dateText[1]);
					}

					var timeText = datetimeText[1].split( ':' );
					var hh1 = $(this).parent().parent().find( '#hh' ).val();

					if ( hh1 != timeText[0] ) {
						$( '#timestampdiv' ).find( '#hh' ).val( timeText[0] );
					}

					var mn1 = $(this).parent().parent().find( '#mn' ).val();

					if ( mn1 != timeText[1] ) {
						$( '#timestampdiv' ).find( '#mn' ).val( timeText[1] );
					}
				},
				onChangeMonthYear: function( year, month, inst ) {

					add_reset_button();

					var mm1 = $('#timestampdiv').find('#mm option').filter(':selected').val();
					var yy1 = $('#timestampdiv').find('#aa').val();

					if ( yy1 != year) {
						$('#timestampdiv').find('#aa').val(year);
					}

					month = (month < 10) ? '0'+month : month;

					if ( mm1 != month ) {
						$('#timestampdiv').find('#mm').val( month ).attr('selected',true);
					}
				}
			} );

			add_reset_button();

			$.datepicker._gotoToday = function( id ) {

				var target = $(id);
				var inst = this._getInst(target[0]);

				if ( this._get(inst, 'gotoCurrent') && inst.currentDay ) {
					inst.selectedDay = inst.currentDay;
					inst.drawMonth = inst.selectedMonth = inst.currentMonth;
					inst.drawYear = inst.selectedYear = inst.currentYear;
				} else {
					var date = new Date();
					inst.selectedDay = date.getDate();
					inst.drawMonth = inst.selectedMonth = date.getMonth();
					inst.drawYear = inst.selectedYear = date.getFullYear();
					// the below two lines are new
					this._setDateDatepicker(target, date);
					this._selectDate(id, this._getDateDatepicker(target));
				}

				this._notifyChange(inst);
				this._adjustDate(target);
				var current_date = $( '#pddp_datepicker .ui-datepicker-today .ui-state-highlight').html();
				current_date = ( current_date < 10 ) ? '0' + current_date : current_date;
				$('#timestampdiv').find('#jj').val(current_date);
			}
		} );

		var is_pddp_open = true;

		$( '#timestampdiv p a.pddp' ).click( function( event ) {

			event.preventDefault();

			if ( is_pddp_open ) {
				$(this).html('Show Datepicker');
				is_pddp_open = false;
			} else {
				$(this).html('Hide Datepicker');
				is_pddp_open = true;
			}

			$( '#pddp_datepicker' ).slideToggle();
		} );
	} );

	function add_reset_button() {
		setTimeout( function() {
			if ( ! $( '#pddp_datepicker .ui-datepicker-buttonpane .ui-datepicker-reset').length ) {

				$( '#pddp_datepicker .ui-datepicker-buttonpane').append('<button type="button" class="ui-datepicker-reset ui-state-default ui-priority-secondary ui-corner-all" data-handler="reset" data-event="click">Reset</button>');

				$( '#pddp_datepicker .ui-datepicker-buttonpane .ui-datepicker-reset').click( function( event ) {
					$('#timestampdiv').find('#jj').val(dd);
					$('#pddp_datepicker .ui-datepicker-calendar td a').removeClass('ui-state-active');
					$('#pddp_datepicker .ui-datepicker-calendar td a:contains(' + dd + ')').addClass('ui-state-active');
					$('#pddp_datepicker .ui-datepicker-calendar td a:contains(' + dd + ')').parent().addClass('ui-datepicker-current-day');
					$('#timestampdiv').find('#aa').val(yy);
					$('#pddp_datepicker .ui-datepicker-year').val( yy ).attr('selected',true);
					$('#timestampdiv').find('#mm').val( mm ).attr('selected',true);
					$('#pddp_datepicker .ui-datepicker-month').val( mm-1 ).attr('selected',true);
					$('#timestampdiv').parent().parent().find('#hh').val(hh);
					$('#timestampdiv').parent().parent().find('#mn').val(mn);
					$('#pddp_datepicker .ui_tpicker_time').html( hh + ':' + mn );
					$('#pddp_datepicker .ui_tpicker_hour_slider select').val( hh ).attr('selected',true);
					$('#pddp_datepicker .ui_tpicker_minute_slider select').val( mn ).attr('selected',true);
				} );
			}
		}, 1 );
	}

} )( jQuery );