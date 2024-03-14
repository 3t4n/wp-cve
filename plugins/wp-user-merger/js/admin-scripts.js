jQuery(document).ready(function ($) {
	
	$('#merge_btn').attr('disabled', true);
	
	$('#wpsu_user_1 , #wpsu_user_2').change(function(){   
	
		var user1_val = $('#wpsu_user_1').val();
		var user2_val = $('#wpsu_user_2').val();
		
		if(user1_val == user2_val){
			$('#merge_btn').attr('disabled', true);
			$('.wpsu_same_users').fadeIn().delay(7500).fadeOut();
		}else {
			$('#merge_btn').attr('disabled', false);
		}
	
	})
	
	$('check_id').change(function(){
	
		console.log(this);
	
	});

	$('.wpus_wrapper_div a.nav-tab').click(function(){

		$(this).siblings().removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		// , form:not(.wrap.wc_settings_div .nav-tab-content)'
		$('.nav-tab-content').hide();
		$('.nav-tab-content').eq($(this).index()).show();
		window.history.replaceState('', '', wpsu_obj.this_url+'&t='+$(this).index());
		$('form input[name="wpsu_tn"]').val($(this).index());
		wpsu_obj.wpsu_tab_tab = $(this).index();
		wpsu_obj.wpsu_tab = $(this).index();

	});

	function parse_query_string(query) {
	  var vars = query.split("&");
	  var query_string = {};
	  for (var i = 0; i < vars.length; i++) {
		var pair = vars[i].split("=");
		// If first entry with this name
		if (typeof query_string[pair[0]] === "undefined") {
		  query_string[pair[0]] = decodeURIComponent(pair[1]);
		  // If second entry with this name
		} else if (typeof query_string[pair[0]] === "string") {
		  var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
		  query_string[pair[0]] = arr;
		  // If third or later entry with this name
		} else {
		  query_string[pair[0]].push(decodeURIComponent(pair[1]));
		}
	  }
	  return query_string;
	}	
		
	var query = window.location.search.substring(1);
	var qs = parse_query_string(query);		
	
	if(typeof(qs.t)!='undefined'){
		$('.wpus_wrapper_div a.nav-tab').eq(qs.t).click();
		
	}

	$('.wpsu_switch input[type="checkbox"]').on('change', function(){

		var this_parent = $(this).parents('.wpsu_switch:first');
		var hidden_input = this_parent.find('input[type="hidden"]');

		if($(this).prop('checked')){
			hidden_input.val(1);
		}else{
			hidden_input.val();
		}

   });


   var ims_general_save_change = $('.ims_general_order_status .ims_save_changes .btn');
   var ims_general_save_loading = $('.ims_general_order_status .ims_save_changes .save_loading');

   function wpsu_save_settings(){

	   var data = {
		   'action': 'wpsu_update_options',
		   'wpsu_fields': $('form.wp_user_merger_form').serialize(),
	   };

	   $('.wpsu_setting_alert').addClass('d-none');
	   $.post(ajaxurl, data, function (response) {
		   ims_general_save_change.prop('disabled', false);
		   ims_general_save_loading.hide();
		   $('.wpsu_setting_alert').removeClass('d-none');
		   setTimeout(function(){ $('.wpsu_setting_alert').addClass('d-none'); }, 5000);
		   
	   });
   }
   
   $('div.wpsu_input_wrapper').on('change', 'div.row :input', function(){
	   
		wpsu_save_settings();

   });

   if($('select#wpsu_user_1').length > 0 && wpsu_obj.user_searchable){

		const slim_user_1 = new SlimSelect({
			select: 'select#wpsu_user_1',
			searchingText: wpsu_obj.searching,
			placeholder: wpsu_obj.placeholder,
			ajax: function(search, callback){
				wpsu_user_load_ajax(search, callback, this);
			},
			onChange: function(obj){
				wpsu_load_user_assets(obj.value, '.wpsu_user_1_assets');
			}
		});

	}

	if($('select#wpsu_user_2').length > 0 && wpsu_obj.user_searchable){

		const slim_user_2 = new SlimSelect({
			select: 'select#wpsu_user_2',
			searchingText: wpsu_obj.searching,
			placeholder: wpsu_obj.placeholder,
			ajax: function(search, callback){
				wpsu_user_load_ajax(search, callback, this);
			},
			onChange: function(obj){
				wpsu_load_user_assets(obj.value, '.wpsu_user_2_assets');
			}
		});

	}

	function wpsu_user_load_ajax(search, callback, this_obj) {
		// Check search value. If you dont like it callback(false) or callback('Message String')
		if (search.length < 3) {
		  callback(wpsu_obj.need_character)
		  return
		}

		
		var data = {

			action : 'wpsu_get_users_list',
			wpsu_user_search_string: search,
			wpsu_nonce: wpsu_obj.wpsu_nonce,

		};


		fetch(ajaxurl, {
			method: 'POST', // or 'PUT'
			headers: {
			  'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams(data),
		  })
		  .then(response => response.json())
		  .then(data => {

			
			if(data.status){
				callback(data.data);
			}else{
				callback(false);
			}
		  })
		  .catch((error) => {					 
			callback(false);
		  });


	}
	
	function wpsu_load_user_assets(user_id, target) {
		if(user_id>0){
			var data = {

				action : 'wpsu_get_user_assets',
				wpsu_user_id: user_id,
				wpsu_nonce: wpsu_obj.wpsu_nonce,
	
			};
			$(target).find('ul').html('').hide();
			$(target).find('> strong').hide();
			$.post(ajaxurl, data, function(response){
				if(response.status){
					$(target).find('> strong').show();
					$(target).find('> strong > span').html(response.total);
					$(target).find('> strong > i.fa-minus-square').show();
					$.each(response.data, function(i, v){
						$(target).find('ul').append('<li><label><strong>'+i+'</strong> <span>('+v+')</span></label></li>').show();
					});
				}
			});
		}
	}
	
	$('body').on('click', '.wpsu_user_assets > strong > i', function(){
		
		if($(this).hasClass('fa-minus-square')){
			$(this).parent().find('.fa-plus-square').show();
			$(this).parents().eq(1).find('> ul').hide();
		}
		if($(this).hasClass('fa-plus-square')){
			$(this).parent().find('.fa-minus-square').show();
			$(this).parents().eq(1).find('> ul').show();
		}
		$(this).hide();
		
		
	});
	
	if($('.wpus_wrapper_div').length>0){
		$('.wpus_wrapper_div a[data-type="screenshot"]').magnificPopup({
		  type: 'image',
		  gallery: {
			// options for gallery
			enabled: true
		  },
		  mainClass: 'mfp-with-zoom', // this class is for CSS animation below
			
		  zoom: {
			enabled: false, // By default it's false, so don't forget to enable it
		
			duration: 400, // duration of the effect, in milliseconds
			easing: 'ease-in', // CSS transition easing function
		
			// The "opener" function should return the element from which popup will be zoomed in
			// and to which popup will be scaled down
			// By defailt it looks for an image tag:
			opener: function(openerElement) {
			  // openerElement is the element on which popup was initialized, in this case its <a> tag
			  // you don't need to add "opener" option if this code matches your needs, it's defailt one.
			  return openerElement.is('img') ? openerElement : openerElement.find('img');
			}
		  }
		});
	}


});