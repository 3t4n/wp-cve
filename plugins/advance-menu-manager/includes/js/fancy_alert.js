/*
amm_plugin Name: Advance Menu Manager
Plugin URI : wwww.multidots.com
Author : Multidots Solutions Pvt. Ltd.
*/

jQuery.browser = {};
(function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
        jQuery.browser.msie = true;
        jQuery.browser.version = RegExp.$1;
    }
})();

/********************************/
(function($) {	
	$.md_alerts = {				
		okButton: 'OK',         // text for the OK button
		cancelButton: 'Cancel', // text for the Cancel button		
		
	
		
		alert: function(message, title, callback) {
			if( title === null ) { title = 'Alert'; }
			$.md_alerts._show(title, message, null, 'alert', function(result) {
				if( callback ) { callback(result); }
			});
		},
		
		confirm: function(message, title, callback) {
			if( title === null ) { title = 'Confirm'; }
			$.md_alerts._show(title, message, null, 'confirm', function(result) {
				if( callback ) { callback(result); }
			});
		},
			
		prompt: function(message, value, title, callback) {
			if( title === null ) { title = 'Prompt'; }
			$.md_alerts._show(title, message, value, 'prompt', function(result) {
				if( callback ) { callback(result); }
			});
		},
		
		// Private methods
		
		_show: function(title, msg, value, type, callback) {
			
			$.md_alerts._hide();
			//$('BODY').css({overflow: 'hidden',});
			
			$('BODY').append(
			  '<div id="md_alert">' +
			  '<div id="alertBox">' +
			    '<h3 class="md_alert_box_title"></h3>' +			    
			      '<div class="md_message"></div>' +				
			  '</div>' +
			  '</div>');			
			
			
			// IE6 Fix
			var pos = ($.browser.msie && parseInt($.browser.version) <= 6 ) ? 'absolute' : 'fixed'; 
			
			$('#md_alert').css({position: pos, });			
			
			$('#md_alert #alertBox h3.md_alert_box_title').text(title);
			$('#alertBox').addClass(type);
			$('#md_alert #alertBox .md_message').text(msg);
			$('#md_alert #alertBox .md_message').html( $('.md_message').text().replace(/\n/g, '<br />') );			
			
			$('#md_alert').css({
				minWidth: $('#md_alert').outerWidth(),
				maxWidth: $('#md_alert').outerWidth(),
				height: $( document ).height()
			});
			
			$.md_alerts._reposition();
			$.md_alerts._maintainPosition(true);
			
			switch( type ) {
				case 'alert':
					$('.md_message').after('<input type="button" value="' + $.md_alerts.okButton + '" id="md_alert_ok"  class="md_btn" />');
					$('#md_alert_ok').click( function() {
						$.md_alerts._hide();
						callback(true);
					});
					$('#md_alert_ok').focus().keypress( function(e) {
						if( e.keyCode === 13 || e.keyCode === 27 ) { $('#md_alert_ok').trigger('click'); }
					});
				break;
				case 'confirm':
					$('.md_message').after('<input type="button" value="' + $.md_alerts.okButton + '" id="md_ok"  class="md_btn" /> <input type="button" value="' + $.md_alerts.cancelButton + '" id="md_cancel" class="md_btn"/>');
					$('#md_ok').click( function() {
						$.md_alerts._hide();
						if( callback ) { callback(true); }
					});
					$('#md_cancel').click( function() {
						$.md_alerts._hide();
						if( callback ) { callback(false); }
					});
					$('#md_ok').focus();
					$('#md_ok, #md_cancel').keypress( function(e) {
						if( e.keyCode === 13 ) { $('#md_ok').trigger('click'); }
						if( e.keyCode === 27 ) { $('#md_cancel').trigger('click'); }
					});
				break;
				case 'prompt':
					$('.md_message').append('<br /><input type="text" size="30" id="md_prompt" />').after('<div id="md_panel"><input type="button" value="' + $.md_alerts.okButton + '" id="md_ok"  class="md_btn" /> <input type="button" value="' + $.md_alerts.cancelButton + '" id="md_cancel" class="md_btn" /></div>');
					$('#md_prompt').width( $('#popup_message').width() );
					$('#md_ok').click( function() {
						var val = $('#md_prompt').val();
						$.md_alerts._hide();
						if( callback ) { callback( val ); }
					});
					$('#md_cancel').click( function() {
						$.md_alerts._hide();
						if( callback ) { callback( null ); }
					});
					$('#md_prompt, #md_ok, #md_cancel').keypress( function(e) {
						if( e.keyCode === 13 ) { $('#md_ok').trigger('click'); }
						if( e.keyCode === 27 ) { $('#md_cancel').trigger('click'); }
					});
					if( value ) { $('#md_prompt').val(value); }
					$('#md_prompt').focus().select();
				break;
			}			
			$('#alertBox').trigger('click');
		},
		
		_hide: function() {
			//$("BODY").css({overflow: '',});
			$('#md_alert').remove();			
			$.md_alerts._maintainPosition(false);
		},		
		
		
		_reposition: function() {
			var top = (($(window).height() / 2) - ($('#popup_container').outerHeight() / 2)) + $.md_alerts.Top_offset;
			var left = (($(window).width() / 2) - ($('#popup_container').outerWidth() / 2)) + $.md_alerts.Left_offset;
			if( top < 0 ) { top = 0; }
			if( left < 0 ) { left = 0; }
			
			// IE6 fix
			if( $.browser.msie && parseInt($.browser.version) <= 6 ) { top = top + $(window).scrollTop(); }
			
			$('#md_alert').css({top: top + 'px',left: left + 'px'});
			
		},
		
		_maintainPosition: function(status) {
			if( $.md_alerts.repositionOnResize ) {
				switch(status) {
					case true:
						$(window).bind('resize', $.md_alerts._reposition);
					break;
					case false:
						$(window).unbind('resize', $.md_alerts._reposition);
					break;
				}
			}
		}
		
	};
	
	// Shortuct functions
	alert_md = function(message, title, callback) {
		$.md_alerts.alert(message, title, callback);
	};
	
	confirm_md = function(message, title, callback) {
		$.md_alerts.confirm(message, title, callback);
	};
		
	prompt_md = function(message, value, title, callback) {
		$.md_alerts.prompt(message, value, title, callback);
	};
	
})(jQuery);