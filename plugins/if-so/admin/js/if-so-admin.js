(function( $ ) {
	'use strict';
	/**
	 * Main javascript file powering if-so admin pages (mainly "edit trigger" page)
	 */

	// Global Vars
	var datetime_index = 0; // Used to re-enable datetime when adding new item
	var removed_items = 0; // number of removed items
	var isScrolled = false; // Indication for first scroll (new trigger)
	var notRequiredFields = [ "time-date-pick-start-date", "time-date-pick-end-date", "cookie" ];
	var scheduleInterval = (typeof(scheduleIntervalSetting)!=='undefined' && scheduleIntervalSetting) ? scheduleIntervalSetting : 60;
	var scheduleSettings = {        
	        // days: [2, 3, 4, 5, 6, 0, 1], 	
	        startTime: '0:00',
	        endTime: '24:00',
	        interval: scheduleInterval
	      };
	// general helper functions
	/*
	 * returns true if needle is in haystack, false otherwise
	 */
	function isInArray(needle, haystack) {
  		return haystack.indexOf(needle) > -1;
	}

	//Control grayed out checkboxes
	$(function () {
		$("#dimming-checkbox").click(enable_cb);
	});
	function enable_cb() {
		if (this.checked) {
			$("input.group1").removeAttr("disabled");
			$(".notification-line").css("color", "black");
		} else {
			 $("input.group1").attr("disabled", true);
			 $(".notification-line").css("color", "#DCDCDC");
		}
	}
		
	function generateVersionSymbol(versionNumber) {
	    var numberOfCharactersInABC = 26;
	    var baseAscii = 64;
	    versionNumber -= baseAscii;
	    var postfix = '';
	    if (versionNumber > numberOfCharactersInABC) {
	        postfix = parseInt(versionNumber / numberOfCharactersInABC, 10) + 1;
	        versionNumber %= numberOfCharactersInABC;
	        if (versionNumber == 0) {
	            versionNumber = numberOfCharactersInABC;
	            postfix -= 1;    
	        }
	    }
	    
	    versionNumber += baseAscii;
	    return String.fromCharCode(versionNumber) + postfix.toString();
	}
	// Enable tooltip
	function activeTooltip(items) {	
		items = "." + items;
		$(document).tooltip({
			items: items,
			track: true,
			show: null, // show immediately
			open: function(event, ui) {
			    if (typeof(event.originalEvent) === 'undefined') {
			        return false;
			    }
			    var $id = $(ui.tooltip).attr('id');
			    // close any lingering tooltips
			    $('div.ui-tooltip').not('#' + $id).remove();
			    // ajax function to pull in data and add it to the tooltip goes here
			},
			close: function(event, ui) {
			    ui.tooltip.hover(function() {
			        $(this).stop(true).fadeTo(400, 1); 
			    },
			    function() {
			        $(this).fadeOut('400', function() {
			            $(this).remove();
			        });
			    });
			}
		});
	}
	function activateFreezeTooltip() {
		var freezeTooltipClass = ".ifso-freeze-overlay";
		var freezeTooltipStyleClass = "ifso_freeze_tooltip_styling";
		//setTooltipClass(freezeTooltipClass, freezetooltipStyleClass);
	
		$(freezeTooltipClass).tooltip({
			tooltipClass: freezeTooltipStyleClass,
			track: true,
			show: null, // show immediately
			open: function(event, ui)
			{
			    if (typeof(event.originalEvent) === 'undefined')
			    {
			        return false;
			    }
			    var $id = $(ui.tooltip).attr('id');
			    // close any lingering tooltips
			    $('div.ui-tooltip').not('#' + $id).remove();
			    // ajax function to pull in data and add it to the tooltip goes here
			},
			close: function(event, ui)
			{
			    ui.tooltip.hover(function()
			    {
			        $(this).stop(true).fadeTo(400, 1); 
			    },
			    function()
			    {
			        $(this).fadeOut('400', function()
			        {
			            $(this).remove();
			        });
			    });
			}
		});	
	}
	activeTooltip("ifso_tooltip");
	activateFreezeTooltip();
	 
	$(document).ready(function () {
		/* IfSo first-use instructions */
		if ( $("#ifso-modal-first-use").length ) {
			$(".ifso-first-use-images").modaal({
			    type: 'image'
			});
			$(".ifso-first-use-images").first().modaal('open');
		}
		
		// Enable Time/Day Schedule
		$(".date-time-schedule").dayScheduleSelector(scheduleSettings);
		// Enable DateTimePicker
		$('.ifsodatetimepicker').ifsodatetimepicker();
		
		$(".date-time-schedule").on('selected.artsy.dayScheduleSelector', function (e, selected) {/* selected is an array of time slots selected this time. */});
		// create repeater
		$(document).on( 'click', '#reapeater-add', function(btnprs) {
			btnprs.target.querySelector('i').classList.add('spin');
			var repeaterItemTemplate = $('#repeater-template').html();
			var index = $('.reapeater-item').length - 1;
			var fake_index = index-removed_items;
			var versionInstructions = "";
			if (fake_index == 0) {
				versionInstructions = "Select a condition, the content will be displayed only if it&apos;s met";
			} else if (fake_index== 1) {
				versionInstructions = "Select a condition, the content will be displayed only if it&apos;s met and if version A is not realized";
			} else {
				versionInstructions = "Select a condition, the content will be displayed only if it&apos;s met and if versions A-"+generateVersionSymbol(64+fake_index)+" are not realized";
			}
			datetime_index += 1;
			
			repeaterItemTemplate = repeaterItemTemplate.replace('{version_number}', index+1);
			repeaterItemTemplate = repeaterItemTemplate.replace(/{datetime_number}/g, datetime_index);
			repeaterItemTemplate = repeaterItemTemplate.replace('{version_char}', generateVersionSymbol(65+fake_index));
			repeaterItemTemplate = repeaterItemTemplate.replace(/index_placeholder/g, (index));
			repeaterItemTemplate = repeaterItemTemplate.replace('{version_instructions}', versionInstructions);

			$('.reapeater-item').last().after(repeaterItemTemplate);
			var clonedElement = $('.reapeater-item-cloned').last();
			clonedElement.find('textarea').addClass('textarea'+index);
			// city
			var $clonedCityAutocompleteInput = clonedElement.find('input.autocomplete');
			// country
			var $clonedCountryAutocompleteInput = clonedElement.find('input.countries-autocomplete');
			// continent
			var $clonedContinentAutocompleteInput = clonedElement.find('input.continents-autocomplete');
			// state
			var $clonedStateAutocompleteInput = clonedElement.find('input.states-autocomplete');
			// country
			var $newCountryAutocompleteInput = $('<input>').attr({
				type: 'text',
				class: 'countries-autocomplete ifso-input-autocomplete',
				placeholder: 'Select a country',
				'data-symbol': 'COUNTRY'
			});
			// continent
			var $newContinentAutocompleteInput = $('<input>').attr({
				type: 'text',
				class: 'continents-autocomplete ifso-input-autocomplete',
				placeholder: 'Select continent',
				'data-symbol': 'CONTINENT'
			});
			// country
			$clonedCountryAutocompleteInput.after($newCountryAutocompleteInput);
			$clonedCountryAutocompleteInput.remove();
			// continent
			$clonedContinentAutocompleteInput.after($newContinentAutocompleteInput);
			$clonedContinentAutocompleteInput.remove();

			initCityAutocomplete($clonedCityAutocompleteInput[0]);
			initStateAutocomplete($clonedStateAutocompleteInput[0]);
			initEasyAutocompletes();
			var data = {
				'action': 'load_tinymce_repeater',
				'nonce': nonce,
				'editor_id': (index)
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				clonedElement.find('.repeater-editor-wrap').append(response);
				var editors = ['repeatable_editor_content'+(index)];
				tinyMCE_bulk_init(editors);
				clonedElement.slideDown(function(){
					var selectedEditor = ( getUserSetting( 'editor' ) == 'html' ) ? 'html':
																					'tmce';
					clonedElement.find(".wp-editor-tabs").each(function(elem) {
						$(this).find('.switch-' + selectedEditor).trigger('click');  //Select previously selected editor mode(text/visual) based on the lase editor interacted with
					});

					if($('#wp-repeatable_editor_content0-wrap').hasClass('html-active')){
						$('#wp-repeatable_editor_content0-wrap .wp-switch-editor.switch-html').trigger('click');
					}

					clonedElement.find('iframe').css('height', '250px');

				});
				// $('.post-type-ifso_triggers #post').validator('update');
				 $(".date-time-schedule").dayScheduleSelector(scheduleSettings);
				$(".date-time-schedule").on('selected.artsy.dayScheduleSelector', function (e, selected) {/* selected is an array of time slots selected this time. */});
				// Re-Enable DateTimePicker
				$('.datetimepickercustom-' + datetime_index).ifsodatetimepicker();
				if (!isScrolled) {
					isScrolled = true;
                    scrollToElement(clonedElement);
				} else {
					scrollToElement(clonedElement);
				}

				//"Activate" notice boxes
				showOnFocus($(clonedElement[0].querySelector('.showhide_input.utm_input')),$(clonedElement[0].querySelector('.showhide_container.utm-noticebox')),$(clonedElement[0].querySelector('.closingX.utm-closingx')),'utm_input');
				showOnFocus(null,$(clonedElement[0].querySelector('.settimeinstructions')),$(clonedElement[0].querySelector('.settimeinstructions .closeX')),'settime_instructions');
				showOnFocus(null,$(clonedElement[0].querySelector('.newusernotice')),$(clonedElement[0].querySelector('.newusernotice .closeX')),'newuser_notice');
				showOnFocus(null,$('.nogroups_noticebox'),$('.nogroups_noticebox .closeX'),'no_groups_notice');
				showOnFocus(null,$('.abt-noticebox'),$('.abt-noticebox .closeX'),'abt_notice');
				document.dispatchEvent(versionAdded);
				btnprs.target.querySelector('i').classList.remove('spin');
				notifyIfTooManyVersions();

			});
		});
		$(document).on('click', '.admin-trigger-wrap .switch-tmce, .rule-item .switch-tmce', function(){
			$(this).closest('.wp-editor-tabs').find('.switch-html').trigger('click');
		});
		// handle repeater item delete
		$(document).on( 'click', '.repeater-delete', function() {
			// Check if trying to remove testing-mode item
			var $repeaterParent = $(this).closest(".reapeater-item");
			if($repeaterParent.find(".circle-active").length)
				alert("A testing mode version cannot be deleted.");
			else if(confirm('Are you sure you want to delete this version?')) {
				removed_items++;
				var itemWrap = $(this).closest('.reapeater-item');
				itemWrap.slideUp( "slow", function() {
					itemWrap.find(".rule-toolbar-wrap").removeClass("rule-toolbar-wrap");
					itemWrap.find('select').remove();
					itemWrap.find('input').remove();
					itemWrap.find('textarea').remove();
					itemWrap.find('.wp-editor-area').remove();
					reSortVersions();
				});
			}
		});
		// updates version's instructions + number
		function reSortVersions() {
			$('.rule-toolbar-wrap').each(function(index){
				if (index === 0 ) return;	//For the template element
				var newIndex = index - 1;
				var versionNumber = newIndex+1;
				var templateTitle = "Dynamic Content – "+jsTranslations['Version']+" {version_char}";
				var versionInstructions;
				var switchWrap = $(this).closest('.rule-wrap');
				if (newIndex == 0) {
					versionInstructions = "Select a condition, the content will be displayed only if it's met:";
				} else if (newIndex == 1) {
					versionInstructions = "Appears only if option A is not realized:";
				} else {
					versionInstructions = "Appears only if option A-"+generateVersionSymbol(64+newIndex)+" are not realized:";
				}
				if ($(this).find('.version-alpha').text() != templateTitle) {
					switchWrap.find('.versioninstructions').text(versionInstructions);
					$(this).find('.version-count').text(versionNumber);
					$(this).find('.version-alpha').text("Dynamic Content - "+jsTranslations['Version']+' '+generateVersionSymbol(65+newIndex));
				}
			});
		}
		
		// toggle PHP code
		$(document).on( 'click', '.php-shortcode-toggle-link', function(e) {
			$('.php-shortcode-toggle-wrap').slideToggle( "slow", function() {});
			e.target.querySelector('.ifso-turnme').classList.toggle('ifso-turnt-around')
		});
		$(document).on( 'click', '.shortcode-withtitle-toggle-link', function(e) {
			$('.shortcode-withtitle-toggle-wrap').slideToggle( "slow", function() {});
			e.target.querySelector('.ifso-turnme').classList.toggle('ifso-turnt-around')
		});
		// toggle analytics meta box info
		$(document).on( 'click', '.php-analytics-toggle-link', function(e) {
			$('.php-analytics-toggle-wrap').slideToggle( "slow", function() {});
			e.target.querySelector('.ifso-turnme').classList.toggle('ifso-turnt-around')
		});
		// popup notice/generator
		$(document).on( 'click', '.ifso-popup-shortcode-link', function(e) {
			$('.ifso-popup-notice-wrap').slideToggle( "slow", function() {});
			e.target.querySelector('.ifso-turnme').classList.toggle('ifso-turnt-around')
		});
		
		$('.post-type-ifso_triggers #post').on('submit', function (e) {
			// Updating all the schedule data with their correspond hidden input
			$(".date-time-schedule").each(function() {
				var $elem = $(this);
				var $parent = $elem.parent();
				var scheudleInput = $parent.find(".schedule-input");
				scheudleInput.val(JSON.stringify($elem.data('artsy.dayScheduleSelector').serialize()));
			});
		})
		function platform_symbols($elem) {
			var selectedOptionLabel = $elem.find(':selected')[0].label;
			var switchWrap = $elem.closest('.rule-wrap');
			var platSymbol = switchWrap.find(".platform-symbol");
			if (selectedOptionLabel == "Facebook Ads") {
				platSymbol.html("");
			} else if (selectedOptionLabel == "Google Adwords"){
				platSymbol.html("{lpurl}?");
			}
		}
		
		$(document).on( 'change', '.advertising-platforms-option', function() {
			platform_symbols($(this));
		});
		function rawRecurrenceToVisual(recurrenceType) {
			var lowerRecurrenceType = recurrenceType.toLowerCase();
			if (lowerRecurrenceType.indexOf("none") != -1) {
				return "None";
			} else if (lowerRecurrenceType.indexOf("session") != -1) {
				return "Session";
			} else if (lowerRecurrenceType.indexOf("always") != -1) {
				return "Always";
			} else if (lowerRecurrenceType.indexOf("custom") != -1) {
				return "Custom";
			} else {
				return "unkown";
			}
		}
		$(document).on('change', '.rule-wrap input[type="radio"]', function() {
			var $recurrenceCustomSelectionContainer = $(this).closest('.recurrence-selection').find('.recurrence-custom-selection-container');
			var recurrenceType = null;
			if ($(this).hasClass("recurrence-custom-radio")) {
				// clicked on 'custom' selection
				$recurrenceCustomSelectionContainer.show();
				recurrenceType = "Custom";
			} else {
				$recurrenceCustomSelectionContainer.hide();
				recurrenceType = $(this).closest('.recurrence-option').find('.recurrence-option-title').text();
			}
			$(this).closest('.recurrence-container').find('.current-recurrence-type').text(rawRecurrenceToVisual(recurrenceType));
		});
		$(document).on( 'change', '.rule-wrap select', function() {
			var selectedOption = $(this).find(':selected');
			var switchWrap = $(this).closest('.rule-wrap');
			var ruleToolbarWrap = switchWrap.find('.rule-toolbar-wrap');
			var nextFieldAttr = selectedOption.data('next-field');
			var resetFieldsDataAttr = selectedOption.data('reset');
			var closestLeftPanel = $(this).closest('.col-md-3');
			var textarea = switchWrap.find("textarea");
			// reset fields
			if (typeof resetFieldsDataAttr !== 'undefined') {
				var resetFields = resetFieldsDataAttr.split('|');
				$.each( resetFields, function( key, resetAttrValue ) {
					switchWrap.find("[data-field*='" + resetAttrValue + "']").hide();
					switchWrap.find("[data-field*='" + resetAttrValue + "']").val("").prop('selectedIndex', 0);
					switchWrap.find("[data-field*='" + resetAttrValue + "']").prop('required', false);
					// Treat special data-fields
					if (resetAttrValue == "advertising-platforms-selection") {
						// switchWrap.find("[data-field*='" + resetAttrValue + "']").trigger('change');
						var elem = switchWrap.find("[data-field*='" + resetAttrValue + "']");
						platform_symbols(elem);
					}
				});
			}

			if (typeof nextFieldAttr === 'undefined') return;
			var nextFields = nextFieldAttr.split('|');
			$.each( nextFields, function( key, nextAttrValue ) {
				console.log(nextAttrValue + ":");
				console.log(switchWrap.find("[data-field='" + nextAttrValue + "']"));
				switchWrap.find("[data-field='" + nextAttrValue + "']").show();
				var isRequired = !isInArray(nextAttrValue, notRequiredFields);
				switchWrap.find("[data-field='" + nextAttrValue + "']").prop('required', isRequired);
			});
			var newTextAreaHeight = closestLeftPanel.height() - 60;
			if (newTextAreaHeight < 250) newTextAreaHeight = 250;
			// alert(newTextAreaHeight);
			textarea.css("height", newTextAreaHeight);


		});
		$(document).on('change', '.ifso-autocomplete-opener', function() {
			var $this = $(this);
			var effectRate = 250;
			// Handle already shown element
			var $currentShownElem = $('.ifso-geo-selected');
			$currentShownElem.stop(true).slideUp(effectRate);
			$currentShownElem.removeClass('ifso-geo-selected');
			// Handle new element
			var classNameOfElemToShow = $this.data("open");
			var $elemToShow = $("." + classNameOfElemToShow);
			$elemToShow.addClass('ifso-geo-selected');
			$elemToShow.stop(true).slideDown(effectRate);
		});
		
		// update query string text in the instruction box
		$(document).on( 'keyup', "input[data-field='url-custom']", function() {
			var inputValue = $(this).val();
			
			var isValid = true;-
			$("input[data-field='url-custom']").not(this).each(function( index ) {
				if($(this).val() != '') {
					if(inputValue == $(this).val()) {
						// handle duplicated query string trigger
						isValid = false;
					}
				}
			});
			
			if(!isValid) {
				// handle invalid query string
				$(this).closest('.form-group').addClass('has-danger').addClass('has-error');
				$(this).after('<div class="help-block">'+jsTranslations['translatable_dupplicated_query_string_notification_trigger']+'</div>');
				
				$('#publishing-action').append('<div class="query-string-err-notification">'+jsTranslations['translatable_dupplicated_query_string_notification_publish']+'!</div>');
			}
			else {
				// query string is valid
				$(this).closest('.form-group').removeClass('has-danger').removeClass('has-error');
				$(this).closest('.form-group').find('.help-block').remove();
				$('#publishing-action .query-string-err-notification').remove();
			}
			
			var queryStringTyped = ($(this).val() == '') ? 'your-query-string' : $(this).val();
			$(this).closest('.rule-wrap').find('.instructions b').text(queryStringTyped);
		});
		// update query string text in the instruction box
		$(document).on( 'keyup', "input[data-field='advertising-platforms-selection']", function() {
			var inputValue = $(this).val();
			
			var isValid = true;
			$("input[data-field='advertising-platforms-selection']").not(this).each(function( index ) {
				if($(this).val() != '') {
					if(inputValue == $(this).val()) {
						// handle duplicated query string trigger
						isValid = false;
					}
				}
			});
			
			if(!isValid) {
				// handle invalid query string
				$(this).closest('.form-group').addClass('has-danger').addClass('has-error');
				$(this).after('<div class="help-block">'+jsTranslations['translatable_dupplicated_query_string_notification_trigger']+'</div>');
				
				$('#publishing-action').append('<div class="query-string-err-notification">'+jsTranslations['translatable_dupplicated_query_string_notification_publish']+'!</div>');
			}
			else {
				// query string is valid
				$(this).closest('.form-group').removeClass('has-danger').removeClass('has-error');
				$(this).closest('.form-group').find('.help-block').remove();
				$('#publishing-action .query-string-err-notification').remove();
			}
			
			var queryStringTyped = ($(this).val() == '') ? 'the-name-you-choose' : $(this).val();
			$(this).closest('.rule-wrap').find('.instructions b').text(queryStringTyped);
		});
		
		// set custom Add New link active
		if(window.location.href.indexOf("post-new.php?post_type=ifso_triggers") > -1) {
			$('a[href="'+window.location.href+'"]').closest('li').addClass('current');
		}

		$( "#ifso-versions-container .ifso-versions-sortable" ).sortable({
				handle: '.ifso-btn-drag',
					beforeStop: function( e, ui ) {
						reSortVersions();
						if($($(ui['item'][0]).find('.wp-editor-wrap')).hasClass('html-active')){
							setTimeout(function () {//fix bug- stitch editor to html if appropriate
								$($(ui['item'][0]).find('.wp-editor-wrap .wp-editor-tabs .wp-switch-editor.switch-html')).trigger('click');
							},500)
						}
				},
				start: function (e, ui) {
					$(ui.item).find('textarea').each(function () {
						tinymce.execCommand('mceRemoveEditor', false, $(this).attr('id'));
					});
				},
				stop: function (e, ui) {
					$(ui.item).find('textarea').each(function () {
						tinymce.execCommand('mceAddEditor', true, $(this).attr('id'));
					});
				}
		});
		$( "#ifso-versions-container .ifso-versions-sortable" ).disableSelection();
		$(".advPlatformsCode, .ifso-dynamic-link-code, .wp-editor-area").on("mouseenter", function(){
			$( "#ifso-versions-container .ifso-versions-sortable" ).enableSelection();
		});
		$(".advPlatformsCode, .ifso-dynamic-link-code, .wp-editor-area").on("mouseleave", function(){
			$( "#ifso-versions-container .ifso-versions-sortable" ).disableSelection();
		});
		$(".ifso-versions-sortable").keydown(function(e){
		    if (e.keyCode == 65 && e.ctrlKey) {
		        e.target.select()
		    }
		})
		//Adding the license messages with AJAX
		var versionAdded = new Event('versionAdded');  //Event fires when a version is added to a trigger that's being created
		if($('#conditionbox_target').length>0 && $('#versionbox_target').length>0){
			ajaxPost({action:'get_license_message',page:'triggerPage'},function(res){
				var msgs = JSON.parse(res);
				if(msgs.version){
					$('#versionbox_target').append(msgs.version);
				}
				if(msgs.version){
					if(document.querySelectorAll('#conditionbox_target').length>1){
						document.querySelectorAll('#conditionbox_target').forEach(function(el){$(el).append(msgs.condition);});
					}
					else document.querySelectorAll('#conditionbox_target').append(msgs.condition);

					$(document).on( 'versionAdded', function(e){
						$('#conditionbox_target')[$('#conditionbox_target').length-1].append(msgs.condition);
					});
				}
			});
		};

		if($('#nolicense_message_target').length>0){
			ajaxPost({action:'get_license_message',page:'licensePage'},function(res){
				$('#nolicense_message_target').append(res);
			});
		}

		//Add trigger page - show case sensitivity message when focusing certain boxes
		if($('.showhide_input').length>0 && $('.showhide_container').length>0){
			showOnFocus($('.showhide_input.utm_input'),$('.showhide_container.utm-noticebox'),$('.closingX.utm-closingx'),'utm_input');
		}
		if($('.settimeinstructions').length>0){
		    showOnFocus(null,$('.settimeinstructions'),$('.settimeinstructions .closeX'),'settime_instructions');
        }
		if($('.newusernotice').length>0){
			showOnFocus(null,$('.newusernotice'),$('.newusernotice .closeX'),'newuser_notice');
		}
		if($('.ifso-autocomplete-container.select-city-container').length>0){
			showOnFocus($('.ifso-autocomplete-container.select-city-container input'),$('.ifso-form-group .geo-info-container'),$('.ifso-form-group .setgeoinstructions .closeXGeo'),'geo_city_accuracy');
		}
		if($('.nogroups_noticebox').length>0){
			showOnFocus(null,$('.nogroups_noticebox'),$('.nogroups_noticebox .closeX'),'no_groups_notice');
		}
		if($('.analytics-noticebox').length>0){
			showOnFocus(null,$('.analytics-noticebox'),$('.analytics-noticebox .closeX'),'analytics_notice');
		}
		if($('.pagebuilders-noticebox').length>0){
			showOnFocus(null,$('.pagebuilders-noticebox'),$('.pagebuilders-noticebox .closeX'),'pagebuilder_notice');
		}
		if($('.abt-noticebox').length>0){
			showOnFocus(null,$('.abt-noticebox'),$('.abt-noticebox .closeX'),'abt_notice');
		}

		if($('#ifso-modal-caching-compat').length>0){
			showOnFocus(null,$('#ifso-modal-caching-compat'),$('#ifso-modal-caching-compat .buttons .neverAgain'),'caching_modal');
			$('#ifso-modal-caching-compat .buttons .cls').on('click',function(){
				$(this).closest('#ifso-modal-caching-compat').hide();
			});
		}

		if($('.ifso-modal-need-help').length>0){
			showOnFocus(null,$('.ifso-modal-need-help'),$('.ifso-modal-need-help .closeX'),'need_help');
		}


		/*Send test email - send AJAX*/
		if($('#ifso_send_test_email').length>0){
			$('#ifso_send_test_email').on('click',function(){ajaxPost({action:'send_test_mail'},function(a){alert('A testing email was sent successfully. Please check your spam folder if you do not see it in your inbox.')},function(a,b){var errText = a.responseText || '';alert('Something went wrong! Please check your internet connection and try again!\n'+errText)})});
		}

		//Contstruct the analytics UI if we're in a relevant page(the fuction defined in analytics metabox template)
		if(typeof(constructAnalyticsUi)=='function'){
			constructAnalyticsUi();
		}

		notifyIfTooManyVersions();
		
	}); /* END of .ready */

	// define the skeleton of the overlay
	var overlayDivHTML = '<div class="ifso-tm-overlay"><span class="text">Testing Mode <br/><br/> <span class="cancel-freezemode"> Another version is forced to be displayed </span> </span></div>';
	var overlayFreezeHTML = '<div class="ifso-freeze-overlay ifso_tooltip"><span class="text">Version is inactive</span> </div>';
	var selectedTestingMode = false;
	function disableTestingMode($elem, $repeaterParent, isDefaultRepeater) {
		// before appending, removing all the 'ifso-tm-overlay' present
		// due to prior appending
		$(".ifso-tm-overlay").remove();
		$("#tm-input").attr("value", "");
	}
	function activateTestingMode($elem, $repeaterParent, isDefaultRepeater) {
		var versionIndex = 0;
		var i = 0;
		// append 'overlayDiv' to any version
		$(".reapeater-item").each(function() {
			// iterate over each 'rule-item' class
			// and append 'overlayDiv' at the end
			// * Skipping the current .rule-item
			// * to not overlay the selected Forcing Mode item
			var $elem = $(this);
			i++;
			if (!$elem.is($repeaterParent)) // if not the selected repeater
				$elem.append(overlayDivHTML);
			else
				versionIndex = i;
		});
		// append 'overlayDiv' to the default content
		// if not selected the default content
		if (!isDefaultRepeater)
			$(".default-repeater-item").append(overlayDivHTML);
		else
			versionIndex = 0; // indicating default content
		$("#tm-input").attr("value", versionIndex);
	}
	$(document).on("click", ".ifso-tm", function(e) {		
		var $elem = $(this);
		var $repeaterParent = null;
		var isDefaultRepeater = false;
		// check if active button already exist
		if ($(".circle-active").length)
			selectedTestingMode = true;
		// Check if it's the default repeater
		var defaultRepreaterParent = $(this).closest(".default-repeater-item");
		if (defaultRepreaterParent.length > 0) {
			isDefaultRepeater = true;
			$repeaterParent = defaultRepreaterParent[0];
		}
		else
			$repeaterParent = $(this).closest(".reapeater-item")[0];
		if (selectedTestingMode) {
			selectedTestingMode = false;
			$(".ifso-tm").removeClass("circle-active");
			disableTestingMode($elem, $repeaterParent, isDefaultRepeater);
		} else {
			selectedTestingMode = true;
			$(this).addClass("circle-active");
			activateTestingMode($elem, $repeaterParent, isDefaultRepeater);
		}
	});
	$(document).on("click", ".ifso-freezemode", function(e) {		
		var $elem = $(this);
		var $inptDom = $elem.parent().find(".freeze-mode-val");
		var isActive = ($inptDom.val() == "true") ? true : false;
		var $parent = $elem.parent();
		var $ancParent = $elem.closest('.reapeater-item');
		// Check if trying to freeze testing-mode item
		if($ancParent.find(".circle-active").length) {
			alert("A testing mode version cannot be deactivated.");
			return;
		}
		// Switch false <-> true
		if (isActive) $inptDom.val("false");
		else $inptDom.val("true");
		if (isActive) {
			// Handle deactive
			$ancParent.find(".ifso-freeze-overlay").remove();
			$parent.removeClass("freeze-overlay-active-container");
			$elem.find(".text").html('<i class="fa fa-pause" aria-hidden="true">');
		} else {
			// Handle  active
			$ancParent.append(overlayFreezeHTML);
			$parent.addClass("freeze-overlay-active-container");
			$elem.find(".text").html('<i class="fa fa-play" aria-hidden="true">');
			activeTooltip("ifso_tooltip");
			activateFreezeTooltip();
		}
	});
	$(document).on("click", ".recurrence-expander", function() {
		var $this = $(this);
		var $recSelectionContainer = $this.closest('.recurrence-container').find(".recurrence-selection");
		$recSelectionContainer.stop(true).toggle();
		
		if ($this.text().trim() == "+") {
			$this.text("-");
		} else {
			$this.text("+");
		}
		$this.toggleClass("recurrence-expander-show");
	});

	$(document).on("click", ".groups-expander", function() {
		var $this = $(this);
		var $recSelectionContainer = $this.closest('.ifso-form-group').find(".groups-selection");
		$recSelectionContainer.stop(true).toggle();

		if ($this.text().trim() == "+") {
			$this.text("-");
		} else {
			$this.text("+");
		}
		$this.toggleClass("groups-expander-show");
	});

	//Custom version name toggle
	$(document).on("click", ".ifso-btn-version-name", function(e) {
		if(e.target.tagName==='INPUT')
			return;
		this.querySelector('.ifso-form-group').classList.toggle('nodisplay')
	});



		/* Utils Funcs */
	function sendAjaxReq(action, data, cb) {
		data['action'] = action;
		data['nonce'] = nonce;
		console.log("Data", data);
		jQuery.post(ajaxurl, data, function(response) {
			if (cb)
				cb(response);
		});
	}
	function scrollToElement($elem) {
	    $('html, body').animate({
	        scrollTop: $elem.offset().top - 50
	    }, 0);
	}
	/* Settings Tabs JS Related */
	$(document).on("click", ".ifso-license-tabs-header .ifso-tab", function() {
		if ( $(this).hasClass("selected-tab") )
			return;
		var contentToShow = "." + $(this).data('tab');
		var $selectedTab = $(".selected-tab");
		var firstOpen = $selectedTab.length===0;
		var contentToHide = "." + $selectedTab.data("tab");
		// switch classes
		$selectedTab.removeClass("selected-tab");
		$(this).addClass("selected-tab");
		// switch contents
		if(firstOpen)
			$(contentToShow).stop(true).fadeIn();
		else{
			$(contentToHide).stop(true).fadeOut('fast', function() {$(contentToShow).stop(true).fadeIn();});
		}
	});
	//Geo page -switch tabs according to the hash
	$(document).ready(function(){
		if($('[data-tab]').length>0){
			if(window.location.hash!='' && $('[data-tab=' + window.location.hash.substring(1) + ']').length>0){
				$('[data-tab=' + window.location.hash.substring(1) + ']').click();
			}
			else if($('.ifso-tab.default-tab').length>0){
				$('.ifso-tab.default-tab').click();
			}
		}
	})
})( jQuery );

function tinyMCE_bulk_init( editor_ids ) {
    var init, ed, qt, first_init, DOM, el, i, qInit;
    if ( typeof(tinymce) == 'object' ) {
        var editor;
        for ( e in tinyMCEPreInit.mceInit ) {
            editor = e;
            break;
        }
        for ( i in editor_ids ) {
            var ed_id = editor_ids[i];
            tinyMCEPreInit.mceInit[ed_id] = tinyMCEPreInit.mceInit[editor];
            tinyMCEPreInit.mceInit[ed_id]['elements'] = ed_id;
            tinyMCEPreInit.mceInit[ed_id]['body_class'] = ed_id;
            tinyMCEPreInit.mceInit[ed_id]['succesful'] =  false;
			tinyMCEPreInit.mceInit[ed_id]['height'] =  '220';
			
			// init qTags
			function getTemplateWidgetId( id ){
				var form = jQuery( 'textarea[id="' + id + '"]' ).closest( 'form' );
				var id_base = form.find( 'input[name="id_base"]' ).val();
				var widget_id = form.find( 'input[name="widget-id"]' ).val();
				return id.replace( widget_id, id_base + '-__i__' );
			}
			
			var qInit;
			if( typeof tinyMCEPreInit.qtInit[ ed_id ] == 'undefined' ){
				qInit = tinyMCEPreInit.qtInit[ ed_id ] = jQuery.extend( {}, tinyMCEPreInit.qtInit[ getTemplateWidgetId( ed_id ) ] );
				qInit['id'] = ed_id;
			}else{
				qInit = tinyMCEPreInit.qtInit[ ed_id ];
			}
			
			if ( typeof(QTags) == 'function' ) {
				jQuery( '[id="wp-' + ed_id + '-wrap"]' ).unbind( 'onmousedown' );
				jQuery( '[id="wp-' + ed_id + '-wrap"]' ).bind( 'onmousedown', function(){
					wpActiveEditor = ed_id;
				});
				QTags( tinyMCEPreInit.qtInit[ ed_id ] );
				QTags._buttonsInit();
				// switchEditors.go( $( 'textarea[id="' + editor_id + '"]' ).closest( '.widget-mce' ).find( '.wp-switch-editor.switch-' + ( getUserSetting( 'editor' ) == 'html' ? 'html' : 'tmce' ) )[0] );
			}
			// END - init qTags
        }
        for ( ed in tinyMCEPreInit.mceInit ) {
			if(editor_ids.length===1){	//Only init on the new editor being added
				if(editor_ids[0]!==ed)
					continue;
			}
            // check if there is an adjacent span with the class mceEditor
            if ( ! jQuery('#'+ed).next().hasClass('mceEditor') ) {
                init = tinyMCEPreInit.mceInit[ed];
				// jQuery( document ).triggerHandler( 'quicktags-init', [ ed ] );
                try {
                    tinymce.init(init);
                    tinymce.execCommand( 'mceAddEditor', true, ed_id );
                } catch(e){
                    console.log('failed');
                    console.log( e );
                }
            }
        }
    }
}

//check on the front end if a cookie exists
function getCookie(c_name) {
	var c_value = document.cookie,
		c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1) c_start = c_value.indexOf(c_name + "=");
	if (c_start == -1) {
		c_value = null;
	} else {
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1) {
			c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start, c_end));
	}
	return c_value;
}

// Create cookie
function createCookie(name, value, days) {
	var expires;
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	}
	else {
		expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

function ajaxPost(data,callback,errCallback){
	if(data==undefined) data = {};
	if(callback==undefined) callback = function(){};	//Not using default parameters to prevent from breaking in IE
	if(errCallback==undefined) errCallback = function(){};
	if(typeof(nonce)!=='undefined' && nonce)
		data['_ifsononce'] = nonce;
	jQuery.post(ajaxurl,data,callback).fail(errCallback);
}

function showOnFocus(toFocus,toShow,closingX,notif_id){
	if(typeof(notif_id)=='undefined') notif_id = false;  //Because default parameters don't work in IE
	if(notif_id===false || !getCookie('ifso_hide_'+notif_id)){
		if(typeof(toFocus)!='undefined' && toFocus!==null) {
            jQuery.each(toFocus, function (key, val) {
                if (val.value != '') {
                    console.log(toShow.selector + '.show-selection');
                    jQuery(toShow.selector + '.show-selection').removeClass('nodisplay');
                }
            });
            toFocus.on('focus', function (e) {
                //if(toShow.length>1) jQuery(e.target.parentNode.querySelector(toShow.selector)).removeClass('nodisplay');
                if (toShow.length > 1) jQuery(e.target).closest('.ifso-form-group').find(toShow.selector).removeClass('nodisplay');
                else jQuery(toShow).removeClass('nodisplay');
            });
            toFocus.on('blur', function (e) {
                if (e.target.value == '') {
                    toShow.addClass('nodisplay');
                }
            });
        }

		if(typeof(closingX!='undefined')){
			closingX.on('click',function(f){
				toShow.addClass('nodisplay');
				if(notif_id!==false){
					createCookie('ifso_hide_'+notif_id,1,365);
				}
			})
		}
	}
}

function resetAllAnalyticsDataAction(){
	if(confirm('Are you sure you want to reset all of the analytics data accumulated by if-so? This data cannot be recovered')){
		ajaxPost({action:'ifso_analytics_req',an_action:'resetAllAnalytics',postid:0}, function(){
			alert('All of the if-so analytics data has been deleted!');
		},function(){alert('Something went wrong. Please check your connection and try again!');})
	}
}

var shownTooManyVersionsNotif = false;

function notifyIfTooManyVersions(){
	if(!shownTooManyVersionsNotif){
		var versions = document.querySelectorAll('li.rule-item');
		if(versions.length>8){
			var notif = document.querySelector('.too-many-conditions-notif');
			if(notif!=null && typeof(notif)!='undefined'){
				notif.classList.remove('nodisplay');
				notif.querySelector('.closingX').addEventListener('click',function(e){
					e.target.parentElement.classList.add('nodisplay');
					createCookie('hide_too_many_conditions_notif','true',356);

				});
				shownTooManyVersionsNotif=true;
			}
		}
	}
}