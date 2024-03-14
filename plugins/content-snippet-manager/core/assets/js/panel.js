jQuery.noConflict()(function($){

	"use strict";

/* ===============================================
   ACE EDITOR
   =============================================== */

	$('.aceEditor').each(function() {

		var width = ($(this).closest('.csm_panel_container').innerWidth() * 0.79) - 40 ;
		var textarea = $(this);
		var editDiv = $('<div>', {
		  position: 'absolute',
		  width: width,
		  height: '400px',
		  'class': textarea.attr('class')
		}).insertBefore(textarea);
		textarea.css('visibility', 'hidden');
		var editor = ace.edit(editDiv[0]);
		editor.renderer.setShowGutter(textarea.data('gutter'));
		editor.getSession().setValue(textarea.val());
		editor.getSession().setMode("ace/mode/html");
		editor.setTheme('ace/theme/tomorrow');
		editor.getSession().setUseSoftTabs(true);
		editor.getSession().setUseWrapMode(true);
		editor.getSession().setUseWorker(true);
		editor.setOptions({
			enableBasicAutocompletion: true,
			enableSnippets: true,
			enableLiveAutocompletion: false
		});
		textarea.closest('form').submit(function() {
			textarea.val(editor.getSession().getValue());
		});
	});

/* ===============================================
   AjaxSelect2
   =============================================== */

	$('.csmAjaxSelect2').select2({

		ajax: {

			url: ajaxurl,
			dataType: 'json',

			delay: 250,

			data: function (params) {

				return {
					action: 'csm_list_posts',
					csm_post_type: $(this).attr('data-type'),
					q: params.term,
					page: params.page

				};

			},

		processResults: function (data, params) {

			params.page = params.page || 1;

				return {
					results: data.items,
					more: false,
				};

			},

			cache: true

		},

		placeholder: 'Type here...',
		minimumInputLength: 3,
		width: '98%'
	});


/* ===============================================
   AjaxSelect2
   =============================================== */

	$('.csmAjaxSelect2Tax').select2({

		ajax: {

			url: ajaxurl,
			dataType: 'json',

			delay: 250,

			data: function (params) {

				return {
					action: 'csm_list_taxonomy',
					csm_taxonomy_type: $(this).attr('data-type'),
					q: params.term,
					page: params.page

				};

			},

		processResults: function (data, params) {

			params.page = params.page || 1;

				return {
					results: data.items,
					more: false,
				};

			},

			cache: true

		},

		placeholder: 'Type here...',
		minimumInputLength: 3,
		width: '98%'

	});

/* ===============================================
   Message, after save options
   =============================================== */

	$('.csm_panel_message').delay(1000).fadeOut(1000);

/* ===============================================
   On off
   =============================================== */

	$('.on-off').on("change",function() {

		if ($(this).val() === "on" ) {
			$('.hidden-element').css({'display':'none'});
		}
		else {
			$('.hidden-element').slideDown("slow");
		}

	});

	$('input[type="checkbox"].on_off').on("change",function() {

		if (!this.checked) {
			$(this).parent('.iPhoneCheckContainer').parent('.csm_panel_box').next('.hidden-element').slideUp("slow");
		} else {
			$(this).parent('.iPhoneCheckContainer').parent('.csm_panel_box').next('.hidden-element').slideDown("slow");
		}

	});

/* ===============================================
   Option panel
   =============================================== */

	$('.csm_panel_container .csm_panel_mainbox').css({'display':'none'});
	$('.csm_panel_container .inactive').next('.csm_panel_mainbox').css({'display':'block'});

	$('.csm_panel_container h5.element').each(function(){

		if($(this).next('.csm_panel_mainbox').css('display') === 'none') {
			$(this).next('input[name="element-opened"]').remove();
		}

		else {
			$(this).next().append('<input type="hidden" name="element-opened" value="'+$(this).attr('id')+'" />');
		}

	});

	$('.csm_panel_container h5.element').on("click", function(){

		if($(this).next('.csm_panel_mainbox').css('display') === 'none') {

			$(this).parent('.csm_panel_container').addClass('unsortableItem');

			$(this).addClass('inactive');
			$(this).children('img').addClass('inactive');
			$('input[name="element-opened"]').remove();
			$(this).next().append('<input type="hidden" name="element-opened" value="'+$(this).attr('id')+'" />');
		}

		else {

			$(this).parent('.csm_panel_container').removeClass('unsortableItem');

			$(this).removeClass('inactive');
			$(this).children('img').removeClass('inactive');
			$(this).next('input[name="element-opened"]').remove();

		}

		$(this).next('.csm_panel_mainbox').stop(true,false).slideToggle('slow');

	});

/* ===============================================
   CHOOSE SCRIPT POSITION
   =============================================== */

	function chooseScriptPosition (type, value, slot) {

		var parent = '#' + slot;

		switch (type) {

			case 'position':

				if ( value === 'scriptOnExcerpt' ) {

					$( parent + ' .excerptLimit').css({'display':'block'});
					$( parent + ' .contentLimit').css({'display':'none'});

				} else if ( value === 'scriptOnContent' ) {

					$( parent + ' .excerptLimit').css({'display':'none'});
					$( parent + ' .contentLimit').css({'display':'block'});

				} else {

					$( parent + ' .excerptLimit').css({'display':'none'});
					$( parent + ' .contentLimit').css({'display':'none'});

				}

				if ( value === 'woocommerceConversion' ) {

					$( parent + ' .MatchValueBox').css({'display':'none'});
					$( parent + ' .switchSection').css({'display':'none'});
					$( parent + ' .MatchValueBox.productType').css({'display':'block'});

				} else if ( value !== 'woocommerceConversion' ) {

					$( parent + ' .switchSection').css({'display':'block'});

					var wholeWebsitevalue = $( parent + ' .wholeWebsite').children('.on-off').val();

					if ( wholeWebsitevalue === 'on' ) {

						$( parent + ' .MatchValueBox').css({'display':'none'});

					} else if ( wholeWebsitevalue === 'off' ) {

						$( parent + ' .MatchValue').each(function() {

							$( parent + ' .MatchValueBox').css({'display':'block'});

						});

					}

				}

			break;

			default:

				if ( value === 'include' && type !== 'woocommerceConversion' ) {

					$( parent + ' .' + type + 'Cpt.MatchValue').css({'display':'block'});
					$( parent + ' .include.' + type + 'cpt').css({'display':'block'});
					$( parent + ' .exclude.' + type + 'cpt').css({'display':'none'});

				} else if ( value === 'exclude' && type !== 'woocommerceConversion' ) {

					$( parent + ' .' + type + 'Cpt.MatchValue').css({'display':'block'});
					$( parent + ' .include.' + type + 'cpt').css({'display':'none'});
					$( parent + ' .exclude.' + type + 'cpt').css({'display':'block'});

				}

			break;

		}

	}

	$('.selectValue').on('change', function() {

		var slot = $(this).closest('.csm_panel_container').attr('id');
		var type = $(this).attr('data-type');
		var value = $(this).val();
		chooseScriptPosition(type, value, slot);

	});

	$('.selectValue').each(function() {

		var slot = $(this).closest('.csm_panel_container').attr('id');
		var type = $(this).attr('data-type');
		var value = $(this).val();
		chooseScriptPosition(type, value, slot);

	});

/* ===============================================
   WHOLE WEBSITE OPTION
   =============================================== */

	function wholeWebsiteSelection (value, slot) {

		var parent = '#' + slot;

		if ( value === 'on' ) {

			$( parent + ' .MatchValueBox').css({'display':'none'});

		} else if ( value === 'off' ) {

			$(parent + ' .MatchValueBox').css({'display':'block'});

		}

	}

	$('.wholeWebsite').on('click', function() {
		var slot = $(this).closest('.csm_panel_container').attr('id');
		var value = $(this).children('.on-off').val();
		wholeWebsiteSelection(value, slot);

	});

/* ===============================================
   RESTORE PLUGIN SETTINGS CONFIRM
   =============================================== */

	$('.csm_restore_settings').on("click", function(){

    	if (!window.confirm('Do you want to restore the plugin settings？')) {

			return false;

		}

	});

	/* ===============================================
	   SAVE SNIPPET CONFIRM
	   =============================================== */

		$('.csm_save_snippet').on("click", function(){

	    	if (!window.confirm('Do you want to save this snippet？')) {

				return false;

			}

		});

	/* ===============================================
	   DELETE SNIPPET CONFIRM
	   =============================================== */

		$('.csm_delete_snippet').on("click", function(){

	    	if (!window.confirm('Do you want to delete this snippet？')) {

				return false;

			}

		});

});
