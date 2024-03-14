"use strict";
jQuery(document).ready(function(){
	jQuery('#wtbpMailTestForm').submit(function(){
		jQuery(this).sendFormWtbp({
			btn: jQuery(this).find('button:first')
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#wtbpMailTestForm').slideUp( 300 );
					jQuery('#wtbpMailTestResShell').slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('.wtbpMailTestResBtn').click(function(){
		var result = parseInt(jQuery(this).data('res'));
		jQuery.sendFormWtbp({
			btn: this
		,	data: {mod: 'mail', action: 'saveMailTestRes', result: result}
		,	onSuccess: function(res) {
				if(!res.error) {
					jQuery('#wtbpMailTestResShell').slideUp( 300 );
					jQuery('#'+ (result ? 'wtbpMailTestResSuccess' : 'wtbpMailTestResFail')).slideDown( 300 );
				}
			}
		});
		return false;
	});
	jQuery('#wtbpMailSettingsForm').submit(function(){
		jQuery(this).sendFormWtbp({
			btn: jQuery(this).find('button:first')
		});
		return false; 
	});
});