/*it_epoll_js*/
jQuery.noConflict();
jQuery(document).ready(function($) {

	

	jQuery('.it_epoll_sys_show_voter_table tr').each(function(){
		var it_epoll_tbl = jQuery(this).find('.it_epoll_sys_show_voter');
		jQuery(this).find('.it_epoll_sys_show_voter_btn').on('click',function(){
			jQuery(it_epoll_tbl+' tr').each(function(){
				jQuery(this).slideToggle();
			});
		});
	});

	jQuery('.it_epoll_color-field').wpColorPicker();
	jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
		jQuery(this).find('#it_epoll_poll_option_rm_btn').click(function() {
			jQuery(it_ele_container).remove();
		});
	});	
	//Adding Voting Poll Option
	jQuery('#it_epoll_add_option_btn').click(function()
	{	
		var date = new Date();
		var components = [
			date.getYear(),
			date.getMonth(),
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			date.getMilliseconds()
		];

		var uniqid = components.join("");
		
		jQuery('#it_epoll_append_option_filed').append('<tr class="it_epoll_append_option_filed_tr">\
			<td><table class="form-table">\
			<tr><td>Option Name</td>\
			<td><input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" required/>\
			</td>\
			</tr>\
			<tr>\
			<td>Option Image</td>\
			<td>\
			<input type="url" class="widefat" id="it_epoll_poll_option_img" name="it_epoll_poll_option_img[]"/>\
			<input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="'+uniqid+'"/>\
			</td>\
			<td>\
			<input type="button" class="button" id="it_epoll_poll_option_btn" name="it_epoll_poll_option_btn" value="Upload"></td>\
			</tr>\
			<tr>\
			<td>Option Cover Image</td>\
			<td><input type="url" class="widefat" id="it_epoll_poll_option_cover_img" name="it_epoll_poll_option_cover_img[]" value=""/>\
			</td>\
			<td>\
			<input type="button" class="button" id="it_epoll_poll_option_ci_btn" name="it_epoll_poll_option_ci_btn" value="Upload">\
			</td>\
			</tr>\
			<tr>\
			<td colspan="2">\
			<input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove This Option">\
			</td>\
			</tr>\
			</table>\
			</td>\
		</tr>');
		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
			jQuery(this).find('#it_epoll_poll_option_rm_btn').click(function() {
				jQuery(it_ele_container).remove();
			});
		});	
		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#it_epoll_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#it_epoll_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});
	});



		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
	
		jQuery(this).find('#it_epoll_poll_option_btn').click(function(e) {

			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});


		jQuery(this).find('#it_epoll_poll_option_ci_btn').click(function(e) {
			var img_val = jQuery(this).parent().parent().find('#it_epoll_poll_option_cover_img');
			var image = wp.media({ 
				title: 'Upload Image',
				// mutiple: true if you want to upload multiple files at once
				multiple: false
			}).open()
			.on('select', function(e){
				// This will return the selected image from the Media Uploader, the result is an object
				var uploaded_image = image.state().get('selection').first();
				// We convert uploaded_image to a JSON object to make accessing it easier
				// Output to the console uploaded_image
		 
				var image_url = uploaded_image.toJSON().url;
				// Let's assign the url value to the input field
				//console.log(img_val);
				
				img_val.val(image_url);
			});
		});
	});//Finish Adding Voting Option


	//Adding Voting Poll Option
	jQuery('#it_epoll_opinion_answer_btn').click(function()
	{	
		var date = new Date();
		var components = [
			date.getYear(),
			date.getMonth(),
			date.getDate(),
			date.getHours(),
			date.getMinutes(),
			date.getSeconds(),
			date.getMilliseconds()
		];

		var uniqid = components.join("");
		
		jQuery('#it_epoll_append_option_filed').append('<tr class="it_epoll_append_option_filed_tr">\
			<td><table class="form-table">\
			<tr><td>Option /Answer</td>\
			<td><input type="hidden" name="it_epoll_poll_option_id[]" id="it_epoll_poll_option_id" value="'+uniqid+'"/>\
			<input type="text" class="widefat" id="it_epoll_poll_option" name="it_epoll_poll_option[]" required/>\
			</td>\
			<td style="text-align:right;">\
			<input type="button" class="button" id="it_epoll_poll_option_rm_btn" name="it_epoll_poll_option_rm_btn" value="Remove">\
			</td>\
			</tr>\
			</table>\
			</td>\
		</tr>');
		jQuery('#it_epoll_append_option_filed .it_epoll_append_option_filed_tr').each(function(){
		var it_ele_container = jQuery(this);
			jQuery(this).find('#it_epoll_poll_option_rm_btn').click(function() {
				jQuery(it_ele_container).remove();
			});
		});	
	});//Finish Adding Voting


	//add plugin activation function
	jQuery('.it_epoll_admin_extensions .plugin-card').each(function(){
		
		var item = jQuery(this);
		//Activating addon
		jQuery(this).find('#activate').on('click',function(){
			
			var formD = {
				'action': jQuery(this).data('action'),
				'wp_nonce':jQuery(this).data('nonce'),
				'extension_id':jQuery(this).data('id'),
				'data': {'id':jQuery(this).data('id'),'name':''}// We pass php values differently!
			};
			var button = jQuery(this);
			jQuery.ajax({url:ajaxurl , type:'POST', data:formD,
				beforeSend:function(){
					jQuery(button).removeClass('updated-message');
					jQuery(button).addClass('updating-message');
					jQuery(button).text("Activating...")
				},
				complete:function(response) {
					
						jQuery(button).removeClass('updating-message');
						jQuery(button).attr('disabled',true);
						if(response.status == 200){
						var it_epoll_json = jQuery.parseJSON(response.responseText);

						if(it_epoll_json.sts == 200){
							jQuery(button).text("Activated");
							window.location.reload();
							jQuery(item).find('#delete').remove();
						}else{
							jQuery(button).text("Failed!");
							jQuery(item).append('<div class="update-notice">'+it_epoll_json.msg+'</div>');
							jQuery(button).addClass('failed-message');	
						}
					}else{
						
						jQuery(button).text("Failed!");
						jQuery(button).addClass('failed-message');	
					}
	
				}
			});
		}); // Addon Activation Code here


		//Deactivating addon
		jQuery(this).find('#deactivate').on('click',function(){
			var formD = {
				'action': jQuery(this).data("action"),
				'wp_nonce':jQuery(this).data('nonce'),
				'extension_id':jQuery(this).data('id'),
				'data': {'id':jQuery(this).data('id'),'name':''}// We pass php values differently!
			};
			var button = jQuery(this);
			jQuery.ajax({url:ajaxurl , type:'POST', data:formD,

				beforeSend:function(){
					jQuery(button).removeClass('updated-message');
					jQuery(button).addClass('updating-message');
					jQuery(button).text("Disabling...")
				},

				complete:function(response) {
					jQuery(button).removeClass('updating-message');
					jQuery(button).attr('disabled',true);
					if(response.status == 200){
						var it_epoll_json = jQuery.parseJSON(response.responseText);

						if(it_epoll_json.sts == 200){
							window.location.reload();
							jQuery(button).text("Deactivated");
							jQuery(button).addClass('button-primary');
							
						}else{
							jQuery(button).text("Failed!");
							
							jQuery(button).addClass('failed-message');
							
						}
					}else{
						jQuery(button).text("Failed!");
						jQuery(button).addClass('failed-message');
					}
					
				}
			});
		}); // Addon Deactivation Code here


		//Uninstall addon
		jQuery(this).find('#delete').on('click',function(){
			var formD = {
				'action': jQuery(this).data("action"),
				'wp_nonce':jQuery(this).data('nonce'),
				'extension_id':jQuery(this).data('id'),
				'data': {'id':jQuery(this).data('id'),'path':jQuery(this).data('path'),'name':''}// We pass php values differently!
			};
			var button = jQuery(this);
			jQuery.ajax({url:ajaxurl , type:'POST', data:formD,

				beforeSend:function(){
					jQuery(button).removeClass('updated-message');
					jQuery(button).addClass('updating-message');
					jQuery(button).text("Uninstalling...")
				},

				complete:function(response) {
					jQuery(button).removeClass('updating-message');
					jQuery(button).attr('disabled',true);
					if(response.status == 200){
						var it_epoll_json = jQuery.parseJSON(response.responseText);

						if(it_epoll_json.sts == 200){
							jQuery(item).remove();
							
						}else{
							jQuery(button).text("Failed!");
							jQuery(item).find('.plugin-card-notice').append('<div class="update-message notice inline notice-error notice-alt"><p>'+it_epoll_json.msg+'</p></div>');
							jQuery(button).addClass('failed-message');
						}
					}else{
						jQuery(button).text("Failed!");
						jQuery(button).addClass('failed-message');
					}
					
				}
			});
		}); // Addon Deactivation Code here





	//Install / Update addon
	jQuery(this).find('#install').on('click',function(){
		var formD = {
			'action': jQuery(this).data("action"),
			'wp_nonce':jQuery(this).data('nonce'),
			'extension_id':jQuery(this).data('id'),
			'data': {'id':jQuery(this).data('id'),'url':jQuery(this).data('url'),'name':''}// We pass php values differently!
		};
		var button = jQuery(this);
		jQuery.ajax({url:ajaxurl , type:'POST', data:formD,

			beforeSend:function(){
				jQuery(button).removeClass('updated-message');
				jQuery(button).addClass('updating-message');
				jQuery(button).text("Installing...")
			},

			complete:function(response) {
				jQuery(button).removeClass('updating-message');
				jQuery(button).attr('disabled',true);
				if(response.status == 200){
					var it_epoll_json = jQuery.parseJSON(response.responseText);

					if(it_epoll_json.sts == 200){
						jQuery(button).text("Installed");
						jQuery(button).addClass('button-primary');
							window.location.href="?page=epoll_addons&tab=installed";
					}else{
						jQuery(button).text("Failed!");
						jQuery(item).find('.plugin-card-notice').append('<div class="update-message notice inline notice-error notice-alt"><p>'+it_epoll_json.msg+'</p></div>');
						jQuery(button).addClass('failed-message');
					}
				}else{
					jQuery(button).text("Failed!");
					jQuery(button).addClass('failed-message');
				}
				
			}
		});
	}); // Addon Deactivation Code here

});
	
});
