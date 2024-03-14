// JavaScript Document
jQuery(document).ready(function($){
		$('body').on('click', '.wpdp_settings_fields .refresh_wpdp a', function(e){

		e.preventDefault();

		var parent = $(this).parents('.refresh_wpdp:first');
		var this_loader = parent.find('img');
		var this_done = parent.find('.fa-check-circle');
		var this_link = $(this);
		if(this_link.hasClass('wpdb_lock')){return;}
		this_link.addClass('wpdb_lock');

		this_done.hide();

		this_loader.show();

		var data = {
			action: 'wpdb_refresh_scripts_ajax',
			wpdp_nonce: wpdp_obj.nonce
		}

		$.post(ajaxurl, data, function(resp, code){
			this_loader.hide();
			this_link.removeClass('wpdb_lock');
			if(code == 'success'){
				this_done.show();
				setTimeout(function(){this_done.fadeOut()}, 2000);
			}
		});
	});
	$('table.wpdp-daterange-functions tr.wpdp-range-disabled div.form-check .form-check-input').on('click', function(){
		if($(this).is(':checked')){
			$(this).closest('tr.wpdp-range-wrapper').addClass('wpdp-range-enabled');
			$(this).closest('tr.wpdp-range-wrapper').removeClass('wpdp-range-disabled');
		}else{
			$(this).closest('tr.wpdp-range-wrapper').addClass('wpdp-range-disabled');
			$(this).closest('tr.wpdp-range-wrapper').removeClass('wpdp-range-enabled');
		}
	});
	$('.wpdp_settings_fields .alive_wpdp a').on('click', function(e){

		e.preventDefault();
		
		if($('#wp_datepicker_alive_scripts').val()=='yes'){
			$('#wp_datepicker_alive_scripts').val('no');
			$('.wpdp_settings_fields .alive_wpdp').removeClass('awake');
		}else{
			$('#wp_datepicker_alive_scripts').val('yes');
			$('.wpdp_settings_fields .alive_wpdp').addClass('awake');
		}
		
		
		
		
		wpdp_save_form_data();

	});
	$('.wpdp-daterange-functions a.linkage').on('click', function(){
		if($(this).hasClass('linked')){
			$(this).find('svg').removeClass('fa-link').addClass('fa-unlink');
			$(this).removeClass('linked');
		}else{
			$(this).find('svg').removeClass('fa-unlink').addClass('fa-link');
			$(this).addClass('linked');
		}
	});
	
	$('select[name="wpdp_fonts"]').on('change', function(){
		$('label[for="wpdp_fonts"]').attr('style', $('select[name="wpdp_fonts"] option:selected').attr('style'));        
	});
	
	$('body').on('click', '.wpdp_cg_btn', function(){
		$('.wpdp_cg').toggle();
		$(this).toggleClass('clicked');
	});
	
	$('body').on('click', '.wpdp_inline_div img', function(){
		$(this).toggleClass('clicked');
		$(this).parent().find('input').trigger('click');
	});
	
	$('.wpdp .wpdp_settings > h3').click(function(){
		var target = '.wpdp .wpdp_settings > ul.menu-class.pages_'+$(this).attr('data-id');
		if(!$(target).is(':visible')){
			$('.wpdp .wpdp_settings > ul.menu-class').slideUp();
			$(target).slideDown();
		}
	});
	
	if ($('.wpdp div.banner_wrapper').length > 0) {
   	 if ( typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			$('.wpdp').on('click', 'div.banner_wrapper', function(e) {
				e.preventDefault();

				var id = $(this).find('.wpdp_vals');
				wp.media.editor.send.attachment = function(props, attachment) {
					id.val(attachment.id);
				};
				wp.media.editor.open($(this));
				return false;
			});
			
		}
		
	};
	
	if ($('.wpdp').length > 0) {
			setInterval(function(){
				wpdp_methods.update_hi();
				//console.clear();
			}, 1000);
	}


	
	$('.wpdp .head_area').on('click', 'a', function(){
		$('.wpdp .head_area code').fadeToggle();
	});
	
	if($('.wpdp_color').length>0)
		$('.wpdp_color').colorPicker();
	
	$('select[name="wpdp_sel[]"]').on('change', function(){
		var obj = $(this).find(':selected');
		var obj_p = $(this).parent();
		var t = obj.data('type');
		var tag = obj.data('tag');
		//console.log(tag);
		var ds = obj_p.find('input[name^="wpdp_demo_str"]');
		var s = ds.val();
		
		var demo = '';
		switch(t){
			case "#":				
			case ".":
				demo += t+''+s;
				ds.attr('placeholder', 'Enter '+tag+' here');
				
			break;
			case "type":
				s = obj.val();
				ds.attr('placeholder', 'Enter name here');
				demo += tag+'['+t+"='"+s+"'  name='"+ds.val()+"']";
			break;			
		}
		obj_p.find('input[name^="wpdp_demo_output"]').val(demo);
		//console.log(t);
	});
	 $('input[name="wpdp_demo_str[]"]').on('change', function(){
		 $('select[name="wpdp_sel[]"]').trigger('change');
	 });
	 
	$('body').on('click', '.wpdp_pro_calendars li a', function(){

		$('.wpdp_pro_calendars li').removeClass('selected');

		if($(this).data('name') != 'default'){

			$(this).parents('li').addClass('selected');
		}

		$('.wpdp_settings_list ul li.selected img').prop('src', $(this).find('img').prop('src'));



		$('input[name$="[wpdp_options][use_custom_style1]"]').val($(this).data('name')).trigger('focusout');
		// $('input[type="submit"]').eq(0).click();
	});


	function wpdp_update_delete_link(){

		var url = $('.delete_wpdp').prop('href');
		url = new URL(url);
		url.searchParams.set("wpdp_delete_option", $('.wpdp_settings_list ul li.selected input').val()); // setting your param
		var newUrl = url.href;
		$('.delete_wpdp').prop('href', newUrl);

	}

	function wpdp_color_picker(){

		if($('body').find('.wpdp_color'))
			$('body').find('.wpdp_color').colorPicker();
	}
	
	
	$('body').on('click', '.wpdp .wpdp_settings .wpdp_settings_list ul li:not(.wpdp_add_icon)',  function(){


		var is_selected = $(this).hasClass('selected');

		$(this).parent().find('li.selected').removeClass('selected');
		$(this).parent().find('li img').hide();
		$(this).find('img').show();
		$(this).addClass('selected');

		if(!is_selected){

			$('.wpdp_modal').show();

			var option_name = $(this).find('input:hidden').val();

			var data = {

				action : 'wpdp_add_new_datepicker_ajax',
				'wpdp_nonce_action_field': $('#wpdp_nonce_action_field').val(),
				'wpdp_get_selected_datepicker': option_name,

			}

			$.post(ajaxurl, data, function (response, code) {


				if(code == 'success'){

					$('.wpdp').replaceWith(response);

					wpdp_color_picker();

				}

			});
		}






		});

	$('body').on('click', '.wpdp_settings_list ul li.wpdp_add_icon span', function(){

		$('.wpdp_modal').show();

		var wpdp_add_icon = $(this).parents('.wpdp_add_icon');
		var selected_clone = $('.wpdp_settings_list ul li.selected').clone();
		var data = {

			action : 'wpdp_add_new_datepicker_ajax',
			'wpdp_nonce_action_field': $('#wpdp_nonce_action_field').val(),
			'wpdp_add_new_datepicker': true,

		}

		$.post(ajaxurl, data, function (response, code) {

			if(code == 'success'){

				response = JSON.parse(response);


				$('.wpdp_settings_list ul li img').hide();
				selected_clone.find('div').html(response.option_name);
				selected_clone.find('img').prop(response.default_img);
				selected_clone.find('input').val(response.option_name);
				wpdp_add_icon.before(selected_clone);
				$('.wpdp_settings_list ul li').removeClass('selected');
				selected_clone.click();

			}

		});

	});

	function wpdp_save_form_data(){

		var form_data = $('#wpdp_form').serialize();
		$('.wpdp_modal').show();

		var data = {

			action: 'wpdp_add_new_datepicker_ajax',
			'wpdp_nonce_action_field': $('#wpdp_nonce_action_field').val(),
			wpdp_form_data: form_data,

		}

		$.post(ajaxurl, data, function (response, code) {

			$('.wpdp_modal').hide();
			if(code == 'success'){



				$('#wpdp_form .alert-success').fadeIn();
				$('.wpdp .wpdp_fix_alert').fadeIn();

				setTimeout(function () {

					$('#wpdp_form .alert-success').fadeOut();
					$('.wpdp .wpdp_fix_alert').fadeOut('slow');

				}, 1000 * 5)


			}


		});
	}


	$('body').on('focusout','#wpdp_form input:not(.ignore-save),#wpdp_form textarea:not(.ignore-save),#wpdp_form select:not(.ignore-save)', function(){
		
		wpdp_save_form_data();
	});

	$('body').on('click', '.delete_wpdp', function(e){

		e.preventDefault()

		if(confirm(wpdp_obj.del_msg)){

			window.location.href = $(this).prop('href');

		}

	});


	$('body').on('keyup', 'input.wpdp-useable[data-name="[wp_datepicker]"]', function(){

		setTimeout(function(){

			$('.wpdp_settings_list ul li.selected div').html($('input.wpdp-useable[data-name="[wp_datepicker]"]').val());

		},10);

	});
	
	$('body').on('click', '.wpdp_settings .wp_datepicker_months.beforeShowDay > label', function(){
		$(this).parent().toggleClass('collapsed');
	});
	
	
	$('body').on('click', 'select[name="custom-scripts"]', function(){
		//console.log($(this).val());
		$('small.custom-scripts').hide();
		var small_obj = 'small.custom-scripts.script-no-'+$(this).val();
		$(small_obj).show();
	});

	$('body').on('click', '.wpdp.wrap a.nav-tab', function(){
		$(this).siblings().removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.nav-tab-content').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		window.history.replaceState('', '', wpdp_obj.this_url+'&t='+$(this).index());
		$('.wrap.wpdp').attr('class', 'wrap wpdp tab-'+$(this).index());
	});

	$('body').on('change', '.wpdp_developer .wpdp_developer_inputs', function(){

		var all_inputs = $('.wpdp_developer .wpdp_developer_inputs');

		var developer_object = {};
		$.each(all_inputs, function(){

			developer_object[$(this).prop('name')] = $(this).prop('checked') ? 'true' : 'false';

		})

		var data = {
			'action': 'wpdp_update_developer_options',
			'nonce': wpdp_obj.nonce,
			'wpdp_developer_options': developer_object,

		}

		$.post(ajaxurl, data, function (response, code){

			if(code == 'success' && response.status){

				$('.wpdp .wpdp_fix_alert').fadeIn();

				setTimeout(function(){
					$('.wpdp .wpdp_fix_alert').fadeOut('slow');
				}, 1000 * 5);

			}

		});

	});


});		
	
						
var wpdp_methods = {

		update_hi: function(){
			jQuery.each(jQuery('.banner_wrapper .wpdp_vals'), function(){
				if(jQuery(this).val()>0){
					jQuery(this).parent().find('.dashicons').fadeIn();
				}else{
					jQuery(this).parent().find('.dashicons').fadeOut();
				}
			});
		}
}



