jQuery(document).ready(function($){
	var custom_uploader, txt;
	$('.upload_button').click(function(e) {
		txt = $(this).closest('td').find('.upload_text');
		
		e.preventDefault();
		if (custom_uploader) {custom_uploader.open();return;}
		custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Logo',button: {text: 'Insert Logo'},multiple: false});
		custom_uploader.on('select', function() {
			attachment = custom_uploader.state().get('selection').first().toJSON();
			txt.val(attachment.url);
		});
		custom_uploader.open();
	});
	
	$('.loancomparison-color').wpColorPicker();
	
	// add 4 tick limit to inner 
	$('#loancomparison_column_rsort input[type=checkbox]').change(function(event) {
		if ($('#loancomparison_column_rsort input[type=checkbox]:checked').size() > 6) {
			$(this).prop('checked',false);
			
			event.preventDefault();
			return false;
		}
	});
});