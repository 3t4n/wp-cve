jQuery(document).ready(function($){
	jQuery('#admin-form').submit(function(){
		var errorsCount = 0;
		jQuery('#admin-form .form-required').each(function(){
			if(jQuery(this).find('input').val() == "")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else if(jQuery(this).find('input').hasClass('numeric') && jQuery(this).find('input').val() == "0")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else {
				jQuery(this).removeClass('form-invalid');
			}
		})
		
		if(errorsCount > 0){
			return false;
		}
		return true;
	});

	jQuery('#add_blocked_date').click(function(){
		var counter = jQuery('#blocked_dates_wrapper').find('div.blocked_date').length;
	
		var new_block_html = '<div class="blocked_date row'+counter+'">'+
		'<input name="blocked_dates[]" id="blocked_dates'+counter+'" value="" class="inputbox datepicker_input" type="text" autocomplete="off" />'+
		'<button type="button" class="btn remove_blocked_date" style="margin: 0px 0px 9px 9px;">'+
		'<span class="dashicons dashicons-no"></span>'+
		'</button>'+            
		'</div>';
	
		jQuery('div#blocked_dates_wrapper').append(new_block_html);
		jQuery('.datepicker_input').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: new Date()
		});
	});
	jQuery(document).on("click", '.remove_blocked_date', function (e) {
		var r = confirm("Are you sure?");
		if (r == true) {
		    jQuery(this).closest('div.blocked_date').remove();
		}
	});
	jQuery('.datepicker_input').datepicker({
		dateFormat: "yy-mm-dd",
		minDate: new Date()
	});
	jQuery('[name="track_availability"]').change(function(){
		if(jQuery(this).val()==1) {
			jQuery('.availability-table').show('slow');
		} else {
			jQuery('.availability-table').hide('slow');
		}
	});

	jQuery('.btn-group-yesno label.btn').click(function () {
		if (jQuery(this).prop("checked")) {
			// checked
			return;
		}
		jQuery(this).siblings('.btn').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent('.btn-group-yesno').children('input').attr('checked', false);
		jQuery(this).prev('input').attr('checked', true);
	});		
});