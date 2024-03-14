jQuery(function($){

	jQuery('.sw_select2_tags select').select2();

	if(jQuery(".solwzd_country_code").length > 0){
		var solwzd_phone_input = jQuery(".solwzd_country_code")[0];
		var defaultCountryCode = 'us';
		if(jQuery(solwzd_phone_input).val() != ''){
			defaultCountryCode = jQuery(solwzd_phone_input).val();
		}
		solwzd_iti = window.intlTelInput(solwzd_phone_input, {
			initialCountry: defaultCountryCode,
			separateDialCode: true,
			customPlaceholder:'',
			utilsScript: ajax_phone_object.phone_utils
		});

		solwzd_phone_input.addEventListener("countrychange", function() {
			console.log(solwzd_iti.getSelectedCountryData().iso2);
			jQuery(solwzd_phone_input).val(solwzd_iti.getSelectedCountryData().iso2)
		});
	}

	var t_input = document.querySelector('input[name=sw_enable_custom_calc_options_list]');
	if(t_input){
		var tagify = new Tagify(t_input);
	}

	setTimeout(function(){
		$('.disable-color .wp-color-result').attr('disabled','disabled');
	}, 1000);

	
	$('.add_row').click(function(e){
		e.preventDefault();
		var rowCount = $('#incentive_table tr').length;
		var newrow = rowCount-1;
		$('#incentive_table tr:last').after('<tr><td><input type="text" name="sw_incentives_repeater_name[]"></td><td><select name="sw_incentives_repeater_value_type[]"><option value="Fixed">Fixed</option><option value="Percentage">Percentage</option></select></td><td><input name="sw_incentives_repeater_value[]" type="number"></td><td><select name="sw_incentives_repeater_applied['+newrow+'][]" multiple=""><option value="Residential">Residential</option><option value="Commercial" disabled="disabled">Commercial</option></select></td><td><a href="#" class="delete button button-primary">Delete</a></td></tr>');
	});
	
	$(document).on("click", "a.delete", function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
	});
	
	// on upload button click
	$('body').on( 'click', '.file-upl', function(e){
 
		e.preventDefault();
 
		var button = $(this),
		custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false
		}).on('select', function() { // it also has "open" and "close" events
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$('input[name="'+ button.attr('data') +'"]').val(attachment.url);
			button.html('<img src="' + attachment.url + '" style="max-width:150px;">').next().val(attachment.id).next().show();
		}).open();
 
	});
	
	if($('.color-field').length > 0){
		$('.color-field').wpColorPicker();
	}
 
	// on remove button click
	$('body').on('click', '.file-rmv', function(e){
 
		e.preventDefault();
 
		var button = $(this);
		$('input[name="'+ button.attr('data') +'"]').val('');
		button.next().val(''); // emptying the hidden field
		button.hide().prev().html('Upload image');
	});
 
});