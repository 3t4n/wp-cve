/* jshint asi: true */
jQuery( document ).ready(function($){
	
	//INTERCEPT SAVE 
	$('#fca_eoi_save_settings_form').submit( function(e){
		if( $('#fca_eoi_settings\\[gdpr_checkbox\\]').attr('checked') && $('#fca_eoi_settings\\[consent_msg\\]').val() == false  ) {
			alert( 'GDPR checkbox is enabled but the consent statement is blank.  Please add a consent statement to enable this feature.' )
			return false			
		} else {
			return true
		}
		
	})
	
	
	//GDPR TOGGLE
	$('#fca_eoi_settings\\[gdpr_checkbox\\]').change( function(e){
		
		var $thisTable = $(this).closest('table')
		if ( this.checked ) {
			$thisTable.find('tr').show()
		} else {
			$thisTable.find('tr').slice(1).hide()
		}
	}).change()
		
	$('#fca_eoi_save_settings_form').show() 
		
	$('.fca_eoi_settings_text_input').click(function() {
		$(this).select()
	})
	
	$('th').click(function(){
		$(this).next().find('input').click()
	})
		
	//////////////////
	//WYSIWYG EDITOR
	//////////////////

	function detectIE() {
		var ua = window.navigator.userAgent
		var msie = ua.indexOf('MSIE ')
		if (msie > 0) {
			// IE 10 or older
			return true
		}
		var trident = ua.indexOf('Trident/')
		if (trident > 0) {
			// IE 11
			return true
		}
		
		if (document.documentMode || /Edge/.test(navigator.userAgent)) {
			//EDGE
			return true
		}
		// other browser
		return false
	}
	var usingIE = detectIE()

	var wysihtmlParserRules = {

		"attributes": {
			"style": "any",
			"class": "any",
			"data-*": "any",
			"target": "any"
		},
		
		"tags": {
			"strong":	1,
			"b":		1,
			"i":		1,
			"u":		1,
			"div":		1,
			"span":		1,
			"ul":		1,
			"li":		1,
			"ol":		1,
			"p":		0,
			"br":		1,
			"a": {
				"check_attributes": {
					"href":	"url", // important to avoid XSS
					
				},
				"set_attributes": {
					"target": "_blank"
				}
			}
		}
	}

	function fca_attach_wysiwyg() {
		var $ = jQuery
		if ( !usingIE ) {
			$('.fca-wysiwyg-html').not('.editorActive').each(function (index, element) {
				var editor = new wysihtml5.Editor( element, { // element
					toolbar:	  $(element).siblings('.fca-wysiwyg-nav')[0], // toolbar element
					parserRules:  wysihtmlParserRules, // defined in parser rules set
					stylesheets: [fcaEoiSettings.css],
					useLineBreaks:  true
				})
				$(element).siblings('.fca-wysiwyg-nav').find('.fca-wysiwyg-view-html').click(function(){
					$(this).siblings('.fca-wysiwyg-group').toggle()
				})
				
				$(element).addClass('editorActive')
				
			})
		} else {
			//DISABLE FOR IE
			$('.fca-wysiwyg-html').not('.editorActive').each(function (index, element) {
				$(element).addClass('editorActive')
				$(element).siblings('.fca-wysiwyg-group').hide()
			})
			
		}
	}
	fca_attach_wysiwyg()

})