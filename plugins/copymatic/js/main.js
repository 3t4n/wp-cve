const api_url = 'https://api.copymatic.ai/';
const api_actions_url = 'https://wp-api.copymatic.ai/';
const admin_url = copymatic_ajax_object.ajax_url;
const loader_html = '<div class="loading-results"><div class="dot-opacity-loader"><span></span><span></span><span></span></div><h2>Generating Ideas</h2><p class="description">Please wait, AI can take up to 30 seconds!</p></div>';

jQuery(function($){
    $('#submit-copymatic-api-key').on('click', function(){
		var api_key = $('#copymatic_apikey').val();
		var form = $('#copymatic_key_submit');
		var _this = $(this);
		$('.copymatic-alert').remove();
		_this.text('Verifying...');
		if(api_key!=''){
			$.ajax({
				type: "POST",
				dataType: "json",
				url: admin_url,
				data: {action: "check_copymatic_api", apikey: api_key},
				success: function(msg){
				  _this.text('Save Settings');
				  $('.copymatic-alert').remove();
				  if(typeof msg.success !== 'undefined') {
					if(msg.success == 1){
						document.getElementById('copymatic_key_submit').submit();
					}else{
						form.prepend('<div class="copymatic-alert warning">Invalid API Key</div>');
					}
				  }else{
					form.prepend('<div class="copymatic-alert warning">An error occured while checking your API Key. Please try again or contact support.</div>');
				  }
				}
			});
		}else{
			form.prepend('<div class="copymatic-alert warning">Invalid API Key</div>');
		}
	});
	
	$('#connect-website-copymatic').on('click', function(){
		var _this = $(this);
		_this.text('Connecting...');
		var api_key = $('#api_key').val();
		if(api_key!=''){
			$.ajax({
				type: "POST",
				dataType: "json",
				url: admin_url,
				data: {action: "check_copymatic_api", apikey: api_key},
				success: function(msg){
				  _this.text('Connect Website');
				  $('.copymatic-alert').remove();
				  if(typeof msg.success !== 'undefined') {
					if(msg.success == 1){
						location.reload();
					}else{
						$('.wrap').prepend('<div class="copymatic-alert warning">Invalid API Key</div>');
					}
				  }else{
					$('.wrap').prepend('<div class="copymatic-alert warning">An error occured while checking your API Key. Please try again or contact support.</div>');
				  }
				}
			});
		}
	});
	
	function load_articles(api_key){
		var table = $('.copymatic-articles-table tbody');
		$.ajax({
			type : "POST",
			dataType : "json",
			url : admin_url,
			data: {action: "load_copymatic_articles"},
			success: function(response) {
				var json = response.data;
				var data = $.parseJSON(json);
				var acnt = data.length;
				if(acnt>0){
					table.empty();
					$.each(data, function (key, val) {
						var edit_link = 'https://copymatic.ai/blog-writer/?id='+val.id;
						var import_class = 'import_article';
						if(val.type=='generated_articles'){
							edit_link = 'https://copymatic.ai/article-generator/?id='+val.id;
							import_class = 'import_generated_article';
						}
						table.append('<tr data-id="'+val.id+'"><td><a href="https://copymatic.ai/blog-writer/?id='+val.id+'" target="_blank">'+val.title+'</a></td><td>'+val.word_count+'</td><td>'+val.date+'</td><td class="copymatic-actions"><a href="#" class="button button-primary '+import_class+'">Import</a><a href="'+edit_link+'" target="_blank" class="button button-secondary edit_article">Edit in Copymatic</a><a href="#" class="button button-secondary delete_article">Delete</a></td></tr>');
					});
				}else{
					$('.loading-row').html("<p>You don't have any article on Copymatic.<br>Start creating one by clicking the button below:</p><a href=\"https://copymatic.ai/blog-writer/\" class=\"button button-primary\" target=\"_blank\">Create Article</a>");
				}
			}
		});
	}
	
	$('#refresh-articles').click(function(){
		var loading_html = '<tr class="alternate"><td colspan="4"><div class="loading-row"><div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>Loading your articles from Copymatic...</div></td></tr>';
		$('.copymatic-articles-table tbody').html(loading_html);
		load_articles(api_key);
	});
	
	if($('.copymatic-articles-table').length > 0){
		load_articles(api_key);	
	}
	
	$(document).on('click', '.delete_article', function(e){
		e.preventDefault();
		var id = $(this).closest('tr').data('id');
		var _this = $(this);
		var parent = $(this).closest('tr');
		_this.text('Deleting...');
		_this.attr('disabled','disabled');
		$.ajax({
			type : "post",
			dataType : "json",
			url : admin_url,
			data : {action: "copymatic_delete_article", id: id},
			success: function(r) {
				if(typeof r.success !== 'undefined' && r.success==true){
					parent.remove();
					if($('.copymatic-articles-table tbody tr').length < 1){
						$('.copymatic-articles-table tbody').html('<tr class="alternate"><td colspan="4"><div class="loading-row"><p>You don\'t have any article on Copymatic.<br>Start creating one by clicking the button below:</p><a href="https://copymatic.ai/blog-writer/" class="button button-primary" target="_blank">Create Article</a></div></td></tr>');
					}
					
				}
			}
		});
	});
	
	$(document).on('click', '.import_article', function(e){
		e.preventDefault();
		var id = $(this).closest('tr').data('id');
		var _this = $(this);
		var parent = $(this).closest('tr');
		var actions_td = $(this).parent();
		_this.text('Importing...');
		_this.attr('disabled','disabled');
		$.ajax({
			type : "post",
			dataType : "json",
			url : admin_url,
			data : {action: "copymatic_import_article", id: id},
			success: function(r) {
				if(typeof r.success !== 'undefined' && r.success == true){
					var json = r.data;
					if(json.post_id){
						_this.remove();
						var posts_admin_url = admin_url.replace('admin-ajax.php', '');
						var html_view_article = '<a href="'+posts_admin_url+'post.php?post='+json.post_id+'&action=edit" class="button button-primary" target="_blank">View article</a>';
						actions_td.prepend(html_view_article);
					}
				}
			}
		});
	});
	
	$(document).on('click', '.import_generated_article', function(e){
		e.preventDefault();
		var id = $(this).closest('tr').data('id');
		var _this = $(this);
		var parent = $(this).closest('tr');
		var title = parent.find('td:first-child a').text();
		var actions_td = $(this).parent();
		_this.text('Importing...');
		_this.attr('disabled','disabled');
		$.ajax({
			type : "post",
			dataType : "json",
			url : admin_url,
			data : {action: "copymatic_import_generated_article", id: id, title: title},
			success: function(r) {
				if(typeof r.success !== 'undefined' && r.success == true){
					var json = r.data;
					if(json.post_id){
						_this.remove();
						var posts_admin_url = admin_url.replace('admin-ajax.php', '');
						var html_view_article = '<a href="'+posts_admin_url+'post.php?post='+json.post_id+'&action=edit" class="button button-primary" target="_blank">View article</a>';
						actions_td.prepend(html_view_article);
					}
				}
			}
		});
	});
	
	$('#copymatic_model').change(function(e){
		$('.copymatic_additionals > div').hide();
		$('.copymatic_additionals .copymatic_'+$(this).val()).show();
	});
	$('.copymatic-generate').click(function(e){
		e.preventDefault();
		var m = $('#copymatic_model').val();
		var t = $("#copymatic_model option:selected").text();
		var pt= $('#copymatic_page_type').val();
		var api_key = $('#copymatic_api_key').val();
		if(m == ''){
			swal("Tool Missing", "Please select a tool to use.", "warning");
			return false;
		}
		if(m == 'blog-intros' || m == 'blog-outline'){
			var title = $('#titlewrap #title').val();
			if(title.length < 2){
				swal("Title Missing", "Please fill an article title in to be able to generate introductions.", "warning");
				return false;
			}
		}
		if(m == 'meta-descriptions'){
			var keyword = $('#keyword').val();
			if(keyword.length < 2){
				swal("Missing Fields", "Please fill all the fields in.", "warning");
				return false;
			}
		}
		if(m == 'subheading-paragraph'){
			var subheading = $('#subheading').val();
			if(subheading.length < 2){
				swal("Subheading Missing", "Please fill all the fields in.", "warning");
				return false;
			}
		}
		var copymatic_description = $('#copymatic_description').val();
		if(copymatic_description.length < 2){
			swal("Description Missing", "Please enter a description to be able to generate content.", "warning");
			return false;
		}
		if($('#audience').is(":visible")){
			var audience = $('#audience').val();
			if(audience.length < 2){
				swal("Audience Missing", "Please enter an audience to be able to generate content.", "warning");
				return false;
			}
		}
		if($('#question').is(":visible")){
			var question = $('#question').val();
			if(question.length < 2){
				swal("Question Missing", "Please enter a question to be able to generate answers.", "warning");
				return false;
			}
		}
		open_copymatic_modal(t,m);
	});
	
	$('.copymatic_regenerate').click(function(e){
		e.preventDefault();
		var m = $(this).data('model');
		var api_key = $('#copymatic_api_key').val();
		generate_ideas(m);
	});
	
	$('.copymatic-modal .media-modal-close').click(function(e){
		e.preventDefault();
		close_copymatic_modal();
	});
	
	function open_copymatic_modal(title, model){
		$('body').addClass('copymatic-modal-open');
		if(title != ""){
			$('.copymatic-modal h1').text(title);
		}
		$('.copymatic-modal .copymatic_regenerate').attr('data-model', model);
		$('.copymatic-modal').show();
		// FETCH CONTENT IDEAS
		generate_ideas(model);
	}
	
	function generate_ideas(model){
		$('.copymatic-modal-ideas').html(loader_html);
		var copymatic_description = $('#copymatic_description').val();
		var language = $('#copymatic_language').val();
		var pt = $('#copymatic_page_type').val();
		var request_data = {
            action:"copymatic_api_request",
            model:model,
            language:language,
			creativity:"regular"
        }
		if(model=='blog-intros' || model=='blog-outline'){
			request_data['blog_title'] = $('#titlewrap #title').val();
		}
		if(model=='meta-descriptions'){
			request_data['page_type'] = $('#copymatic_page_type').val();
			request_data['website_name'] = $('#copymatic_website_name').val();
			request_data['keyword'] = $('#keyword').val();
			request_data['business_description'] = $('#business_description').val();
		}
		if(model=='subheading-paragraph'){
			request_data['subheading'] = $('#subheading').val();
		}
		if(pt=='landing page'){
			var audience = $('#audience').val();
			request_data['description'] = copymatic_description;
			request_data['audience'] = audience;
			if($('#tone').is(":visible")){
				request_data['tone'] = $('#tone').val();
			}
			if($('#question').is(":visible")){
				request_data['question'] = $('#question').val();
			}
		}else if(pt=='blog article'){
			request_data['topic'] = copymatic_description;
			request_data['productname'] = $('#copymatic_website_name').val();
		}
		
		if(copymatic_description!=""){
			$.ajax({
				type : "post",
				dataType : "json",
				url : admin_url,
				data: request_data,
				success: function(r) {
					if(typeof r.success !== 'undefined' && r.success == false){ // error
					}else{ // success
						$('.loading-results').remove();
						if(r['no_enough_credits']){						
							$('.copymatic_regenerate, .copymatic-generate').prop('disabled', true);
							close_copymatic_modal()
							swal({
								title: "Upgrade your account",
								text: "You ran out of credits. Please upgrade your account to unlock unlimited content writing.",
								icon: "warning",
								button: "Upgrade",
							}).then(function(button) {
								if(button){
									window.location.href = "https://copymatic.ai/upgrade/";
								}
							});
						}else{
							if(r['balance']!=''){
								$('#copymatic-post-box label[for="copymatic_description"] div').html('Credits: <strong>'+r['balance']+'</strong>');
							}
							var ideas = r['ideas'];
							$.each(ideas, function(i, t){
								setTimeout(function(){
									$('.copymatic-modal-ideas').append('<div class="idea"><p>'+t+'</p><span class="index">'+i+'</span><div class="btn-ideas"><button class="btn white rounded shadow-sm btn-sm copy"><span class="dashicons dashicons-admin-page"></span> Copy</button></div></div>');
								}, 500*i);
							});
						}
					}
				}
			});
		}
	}

	function close_copymatic_modal(){
		$('body').removeClass('copymatic-modal-open');
		$('.copymatic-modal-ideas').empty();
		$('.copymatic-modal').hide();
	}
	$(document).on('click', '.copy', function() {
		var elem = $(this).parents('.idea').find('p');
		copyToClipboard(elem);
		var _this = $(this);
		_this.html('<span class="dashicons dashicons-saved"></span> Copied!');
		setTimeout(function(){
			_this.html('<span class="dashicons dashicons-admin-page"></span> Copy');
		}, 600);
	});
	function copyToClipboard(element) {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}
});